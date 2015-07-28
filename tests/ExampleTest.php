<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
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
        $this->get(route('products.index'))
             ->assertResponseOk();
    }

    public function testGetDescriptions()
    {
        $this->get(route('descriptions.index'))
             ->assertResponseOk();
    }
}
