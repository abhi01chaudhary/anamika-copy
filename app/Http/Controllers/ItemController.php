<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function search()
    {
        $results = Item::when(request('q'), function($query) {
                $query->where('item_name', 'like', '%'.request('q').'%')
                ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();

        return response()
            ->json(['results' => $results]);
    }

    public function totalRows(){
        $results = Item::latest()->paginate(request('total_rows'));
        return response()
            ->json(['results' => $results]);
    }

    public function liveSearch(){
        $results = Item::orderBy('item_name')
            ->when(request('q'), function($query) {
                $query->where('item_name', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })->paginate(10);

        return response()
            ->json(['results' => $results]);
    }

    public function index(){
        $results = Item::latest()->paginate(10);
        return response()
            ->json(['results' => $results]);
    }

    public function store(Request $request){
        $request->validate([
            'item_name' => 'required',
            'description' => 'required',
            'unit_price' => 'required|numeric'
        ]);
 
        $item = Item::create($request->all());
 
        return response()
            ->json([
                'saved' => true,
                'result' => $item,
                'status' => 200
            ]);
    }

    public function show($id){
        $model = Item::findOrFail($id);
        return response()->json(['model' => $model]);
    }

    public function update(Request $request, $id){

        $item = Item::findOrFail($id);
        
        $request->validate([
            'item_name' => 'required',
            'description' => 'required',
            'unit_price' => 'required|numeric'
        ]);
 
        $item->update($request->all());

        return response()
            ->json(['saved' => true, 'id' => $item->id, 'status' => 200]);

    }
    
    public function destroy($id){
        $item = Item::findOrFail($id);
        $item->delete();
        return response()
            ->json(['deleted' => true]);
    }
}
