<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::all();
        return $data->count() > 0 ? response([
            'message' => 'Product has been founded',
            'data' => $data
        ], 200) : response([
            'message' => 'Product not found'
        ], 404);
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
            'qty' => 'required|integer',
            'description' => 'required|string'
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
            'qty' => $request->qty,
            'description' => $request->description
        ]);

        return response([
            'message' => 'Product has been created'
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($product)
    {
        $data = Product::find($product);

        return isset($data) ? response([
            'message' => 'Product detail has been founded',
            'data' => $data
        ], 200) : response([
            'message' => 'Product detail not found'
        ],404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $product)
    {
        
        $request->validate([
            'product_name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|digits_between:5,10',
            'qty' => 'required|integer',
            'description' => 'required|string'
        ]);
        
        $data = Product::find($product);

        if(!isset($data)){
            return response([
                'message' => 'Product not found',
            ],404);
        }

        if(isset($request->photo)){
            $request->validate([
                'photo' => 'required|image:jpg,jpeg,png',
            ]);

            $request->file('photo')->move(public_path("upload/product"), $data->photo_name);
        }

        
        $data->product_name = $request->product_name;
        $data->category_id = $request->category_id;
        $data->price = $request->price;
        $data->qty = $request->qty;
        $data->description = $request->description;
        $data->save();

        return response([
            'message' => 'Product has been updated'
        ],200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        $data = Product::find($product);

        if(!isset($data)){
        
            return response([
                'message' => 'Product not found',
            ],404);
        }
        File::delete(public_path('upload/product') . "/" . $data->product_image_name);
        $data->delete();
        
        return response([
            'message' => 'Product has been deleted',
        ],201);
    }
}
