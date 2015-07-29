<?php

use App\Product;
use App\Description;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    protected $jsonHeaders = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Laravel 5');
    }

    public function testGetProducts()
    {
        $products = factory(Product::class, 3)->create();

        $this->get(route('api.products.index'))
             ->assertResponseOk();

        array_map(function ($product) {
            $this->seeJson($product->jsonSerialize());
        }, $products->all());
    }

    public function testGetProductDescriptions()
    {
        $product = factory(Product::class)->create();
        $product->descriptions()->saveMany(factory(Description::class, 3)->make());

        $this->get(route('api.products.descriptions.index', ['productId' => $product->id]))
             ->assertResponseOk();

        array_map(function ($description) {
            $this->seeJson($description->jsonSerialize());
        }, $product->descriptions->all());
    }

    public function testCreateProduct()
    {
        $product = factory(Product::class)->make(['name' => 'meats']);

        $this->post(route('api.products.store'), $product->jsonSerialize(), $this->jsonHeaders)
             ->seeInDatabase('products', ['name' => $product->name])
             ->assertResponseOk();
    }

    public function testCreateProductFailsIfNameNotQuality()
    {
        $product = factory(Product::class)->make(['name' => 'notquality']);

        $this->post(route('api.products.store'), $product->jsonSerialize(), $this->jsonHeaders)
             ->seeJson(['name' => ['This product name does not fit very well in the product ecosystem we\'ve artfully crafted.']])
             ->assertResponseStatus(422);
    }

    public function testCreateProductFailsIfNameNotProvided()
    {
        $this->post(route('api.products.store'), ['name' => ''], $this->jsonHeaders)
             ->seeJson(['name' => ['The name field is required.']])
             ->assertResponseStatus(422);
    }

    public function testCreateProductFailsIfNameAlreadyExists()
    {
        $product = factory(Product::class)->create([
            'name' => 'beats',
        ]);

        $this->post(route('api.products.store'), ['name' => $product->name], $this->jsonHeaders)
             ->seeJson(['name' => ['The name has already been taken.']])
             ->assertResponseStatus(422);
    }

    public function testUpdateProduct()
    {
        $product = factory(Product::class)->create(['name' => 'beats']);

        $this->put(route('api.products.update', ['productId' => $product->id]), ['name' => 'feets'], $this->jsonHeaders)
             ->seeInDatabase('products', ['name' => 'feets'])
             ->assertResponseOk();
    }

    public function testUpdateProductFailsIfNameNotProvided()
    {
        $product = factory(Product::class)->create(['name' => 'beats']);

        $this->put(route('api.products.update', ['productId' => $product->id]), ['name' => ''], $this->jsonHeaders)
             ->seeJson(['name' => ['The name field is required.']])
             ->assertResponseStatus(422);
    }

    public function testUpdateProductFailsIfNameAlreadyExists()
    {
        $product1 = factory(Product::class)->create(['name' => 'beats']);
        $product2 = factory(Product::class)->create(['name' => 'feets']);

        $this->put(route('api.products.update', ['productId' => $product1->id]), ['name' => $product2->name], $this->jsonHeaders)
             ->seeJson(['name' => ['The name has already been taken.']])
             ->assertResponseStatus(422);
    }

    public function testCreateProductDescription()
    {
        $product = factory(Product::class)->create();
        $description = factory(Description::class)->make();

        $this->post(route('api.products.descriptions.store', ['products' => $product->id]), $description->jsonSerialize(), $this->jsonHeaders)
             ->seeInDatabase('descriptions', ['product_id' => $product->id, 'body' => $description->body])
             ->assertResponseOk();
    }

    public function testCreateProductDescriptionFailsIfNameNotProvided()
    {
        $product = factory(Product::class)->create();

        $this->post(route('api.products.descriptions.store', ['products' => $product->id]), ['body' => ''], $this->jsonHeaders)
             ->seeJson(['body' => ['The body field is required.']])
             ->assertResponseStatus(422);
    }
}
