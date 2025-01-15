<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::all();
        return response([
            'message' => 'Product has been founded',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'photo' => 'required|image:jpg,jpeg|max:1024',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|digits_between:5,10',
            'qty' => 'required|integer'
        ]);

        $time = Carbon::now()->format("Y-m-d_H_i_s");
        $photo = $time . '.' . $request->photo->extension();
        $request->file('photo')->move(public_path("upload/product"), $photo);


        Product::create([
            'product_name' => $request->product_name,
            'photo' =>url('upload/product') . '/' . $photo,
            'photo_name' => $photo,
'category_id' => $request->category_id,
            'price' => $request->price,
            'qty' => $request->qty
        ]);

        return response([
            'message' => 'Product has been created'
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
