<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseCounter;
use DB;
use App\Http\Traits\NepaliDateConverter;

class ExpenseController extends Controller
{
    use NepaliDateConverter;

    public function index()
    {
        $results = Expense::with(['vendor'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return response()
        ->json(['results' => $results]);
    }

    public function totalRows(){
        $results = Expense::latest()->with(['vendor'])->paginate(request('total_rows'));
        return response()
            ->json(['results' => $results]);
    }

    public function liveSearch(){
        $results = Expense::join('vendors', 'expenses.vendor_id', 'vendors.id')
                ->with('vendor')
                ->where('firstname', 'like', '%' . request('q') . '%')
                ->orWhere('lastname', 'like', '%' . request('q') . '%')
                ->orWhere('number', 'like', '%' . request('q') . '%')
                ->paginate(10);

        return response()
            ->json(['results' => $results]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $date = $this->get_nepali_date(date('Y'), date('m'), date('d'));

        $todayNepaliDate = $date['y']. '-'.$date['m'] . '-'.$date['d']; 

        $counter = ExpenseCounter::where('key', 'Expense')->first();

        $form = [
            'number' => $counter->prefix . $counter->value,
            'vendor_id' => null,
            'vendor' => null,
            'date' => $todayNepaliDate,
            'due_date' => $todayNepaliDate,
            'reference' => null,
            'discount' => 0,
            'terms_and_conditions' => 'Default Terms',
            'items' => [
                [
                    'item_id' => null,
                    'item' => null,
                    'unit_price' => 0,
                    'qty' => 1
                ]
            ]
        ];
        
        return response()
            ->json(['form' => $form]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'date_format:Y-m-d',
            'reference' => 'nullable|max:100',
            'discount' => 'required|numeric|min:0',
            'terms_and_conditions' => 'required|max:2000',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer|exists:items,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        $expense = new Expense;
        $expense->fill($request->except('items'));
        $expense->sub_total = collect($request->items)->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        $expense = DB::transaction(function() use ($expense, $request) {
            $counter = ExpenseCounter::where('key', 'Expense')->first();
            $expense->number = $counter->prefix . $counter->value;
            // custom method from app/Helper/HasManyRelation
            $expense->storeHasMany([
                'items' => $request->items
            ]);

            $counter->increment('value');

            return $expense;
        });

        return response()
            ->json(['saved' => true, 'id' => $expense->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Expense::with(['vendor', 'items.item'])
            ->findOrFail($id);

        return response()
            ->json(['model' => $model]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = Expense::with(['vendor', 'items.item'])
            ->findOrFail($id);

        return response()
            ->json(['form' => $form]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'reference' => 'nullable|max:100',
            'discount' => 'required|numeric|min:0',
            'terms_and_conditions' => 'required|max:2000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|required|integer|exists:expense_items,id,expense_id,'.$expense->id,
            'items.*.item_id' => 'required|integer|exists:items,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        $expense->fill($request->except('items'));

        $expense->sub_total = collect($request->items)->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        $expense = DB::transaction(function() use ($expense, $request) {
            // custom method from app/Helper/HasManyRelation
            $expense->updateHasMany([
                'items' => $request->items
            ]);

            return $expense;
        });

        return response()
            ->json(['saved' => true, 'id' => $expense->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Expense::findOrFail($id);

        $invoice->items()->delete();

        $invoice->delete();

        return response()
            ->json(['deleted' => true]);
    }
}
