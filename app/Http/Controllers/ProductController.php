<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function search()
    {
        $results = Product::orderBy('item_code')
            ->when(request('q'), function($query) {
                $query->where('item_code', 'like', '%'.request('q').'%')
                ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();

        return response()
            ->json(['results' => $results]);
    }

    public function totalRows(){
        $results = Product::latest()->paginate(request('total_rows'));
        return response()
            ->json(['results' => $results]);
    }

    public function liveSearch(){
        $results = Product::orderBy('item_code')
            ->when(request('q'), function($query) {
                $query->where('item_code', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })->paginate(10);

        return response()
            ->json(['results' => $results]);
    }

    public function index(){
        
        $results = Product::latest()->paginate(10);

        return response()
            ->json(['results' => $results]);
    }

    public function show($id){
        $model = Product::find($id);
        return response()->json(['model' => $model]);
    }

    public function create()
    {
        $form = [
            'item_code' => '',
            'unit_price' => 0,
            'quantity' => 1,
            'description' => ''
        ];

        return response()
            ->json(['form' => $form]);
    }

    public function store(Request $request){
        
        $request->validate([
            'item_code' => 'required',
            'description' => 'required',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|numeric'
        ]);
 
        $product = Product::create($request->all());
 
        return response()
            ->json([
                'saved' => true,
                'result' => $product,
                'status' => 200
            ]);
    }

    public function update(Request $request, $id){
        
        $product = Product::findOrFail($id);
        
        $request->validate([
            'item_code' => 'required|unique:products,item_code,'.$id,
            'description' => 'required',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|numeric'
        ]);
        
        $product->update($request->all());

        return response()
            ->json(['saved' => true, 'id' => $product->id, 'status' => 200]);
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $product->delete();
        return response()
            ->json(['deleted' => true]);
    }
}
