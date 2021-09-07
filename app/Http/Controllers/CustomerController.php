<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function search(){
        $results = Customer::orderBy('firstname')
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
        $results = Customer::latest()->paginate(request('total_rows'));
        return response()
            ->json(['results' => $results]);
    }

    public function liveSearch(){
        $results = Customer::latest()
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
        $results = Customer::latest()->paginate(10);
        return response()
            ->json(['results' => $results]);
    }

    public function store(Request $request){
        
        $request->validate([
           'firstname' => 'required',
           'lastname' => 'required',
           'email' => 'required|email|unique:customers',
           'phone' => 'required|numeric|digits:10',
           'address' => 'required',
        ]);

        $customer = Customer::create($request->all());

        return response()
            ->json([
                'saved' => true,
                'result' => $customer,
                'status' => 200
            ]);
    }

    public function edit($id)
    {
        $form = Customer::findOrFail($id);
        return response()
            ->json(['form' => $form]);
    }

    public function update(Request $request, $id){

        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:customers,email,'.$id,
            'phone' => 'required|numeric|digits:10',
            'address' => 'required',
        ]);
 
        $customer->update($request->all());

        return response()
            ->json(['saved' => true, 'id' => $customer->id, 'status' => 200]);

    }

    public function show($id){
        $model = Customer::findOrFail($id);
        return response()->json(['model' => $model]);
    }

    public function destroy($id){
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()
            ->json(['deleted' => true]);
    }
}
