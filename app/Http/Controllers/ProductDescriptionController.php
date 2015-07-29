<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Description;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $productId
     * @param  Request  $request
     * @return Response
     */
    public function index($productId, Request $request)
    {
        return Description::ofProduct($productId)
            ->withKeyword($request->input('keyword'))
            ->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int      $productId
     * @param  Request  $request
     * @return Response
     */
    public function store($productId, Request $request)
    {
        $this->validate($request, [
            'body' => ['required'],
        ]);

        $product = Product::findOrFail($productId);

        return $product->descriptions()->save(new Description([
            'body' => $request->input('body'),
        ]));
    }
}
