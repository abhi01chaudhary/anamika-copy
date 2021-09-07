<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function search(){
        $results = Vendor::orderBy('firstname')
            ->when(request('q'), function($query) {
                $query->where('firstname', 'like', '%'.request('q').'%')
                    ->orWhere('lastname', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%');
            })
            ->limit(6)->get();

        return response()
            ->json(['results' => $results]);
    }

    public function totalRows(){
        $results = Vendor::latest()->paginate(request('total_rows'));
        return response()
            ->json(['results' => $results]);
    }

    public function liveSearch(){
        $results = Vendor::latest()
            ->when(request('q'), function($query) {
                $query->where('firstname', 'like', '%'.request('q').'%')
                    ->orWhere('lastname', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%')
                    ->orWhere('phone', 'like', '%'.request('q').'%');
            })->paginate(10);

        return response()
            ->json(['results' => $results]);
    }

    public function index(){
        $results = Vendor::latest()->paginate(10);
        return response()
            ->json(['results' => $results]);
    }

    public function store(Request $request){
        
        $request->validate([
           'firstname' => 'required',
           'lastname' => 'required',
           'vendor_name' => 'required',
           'email' => 'required|email|unique:vendors',
           'phone' => 'required|numeric|digits:10',
           'address' => 'required',
        ]);

        $vendor = Vendor::create($request->all());

        return response()
            ->json([
                'saved' => true,
                'result' => $vendor,
                'status' => 200
            ]);
    }

    public function edit($id)
    {
        $form = Vendor::findOrFail($id);
        return response()
            ->json(['form' => $form]);
    }

    public function update(Request $request, $id){

        $vendor = Vendor::findOrFail($id);
        
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'vendor_name' => 'required',
            'email' => 'required|email|unique:vendors,email,'.$id,
            'phone' => 'required|numeric|digits:10',
            'address' => 'required',
        ]);
 
        $vendor->update($request->all());

        return response()
            ->json(['saved' => true, 'id' => $vendor->id, 'status' => 200]);

    }

    public function show($id){
        $model = Vendor::findOrFail($id);
        return response()->json(['model' => $model]);
    }

    public function destroy($id){
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return response()
            ->json(['deleted' => true]);
    }
}
