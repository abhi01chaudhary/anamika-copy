<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;
use DB;
use App\Http\Traits\NepaliDateConverter;

class InvoiceController extends Controller
{
    use NepaliDateConverter;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $totalTurnOver;
    private $paidTurnOver;
    private $unPaidTurnOver;

    public function __construct(){
        $this->totalTurnOver = Invoice::sum('total');
        $this->paidTurnOver = Invoice::where('status', 'Paid')->sum('total');
        $this->unPaidTurnOver = Invoice::where('status', 'Un paid')->sum('total');
    }

    public function index()
    {
        $results = Invoice::latest()->with(['customer'])
        ->paginate(15);

        return response()
        ->json([
            'results' => $results, 
            'totalTurnOver' => $this->totalTurnOver, 
            'paidTurnOver' => $this->paidTurnOver,
            'unPaidTurnOver' => $this->unPaidTurnOver
        ]);
    }

    public function totalRows(){
        $results = Invoice::latest()->with(['customer'])->paginate(request('total_rows'));
        return response()
            ->json([
                'results' => $results,
                'totalTurnOver' => $this->totalTurnOver, 
                'paidTurnOver' => $this->paidTurnOver,
                'unPaidTurnOver' => $this->unPaidTurnOver
            ]);
    }

    public function liveSearch(){

        $results = Invoice::join('customers', 'invoices.customer_id', 'customers.id')
                ->with('customer')
                ->where('firstname', 'like', '%' . request('q') . '%')
                ->orWhere('lastname', 'like', '%' . request('q') . '%')
                ->orWhere('number', 'like', '%' . request('q') . '%')
                ->paginate(15);

        return response()
        ->json([
            'results' => $results,
            'totalTurnOver' => $this->totalTurnOver, 
            'paidTurnOver' => $this->paidTurnOver,
            'unPaidTurnOver' => $this->unPaidTurnOver
        ]);
    }

    public function statusSearch(){
        $results = Invoice::join('customers', 'invoices.customer_id', 'customers.id')
                ->with('customer')
                ->where('status', request('status'))
                ->paginate(15);

        return response()
        ->json([
            'results' => $results,
            'totalTurnOver' => $this->totalTurnOver, 
            'paidTurnOver' => $this->paidTurnOver,
            'unPaidTurnOver' => $this->unPaidTurnOver
        ]);
    }

    
    public function dateSearch(){
        $firstDate = request('first_date');
        $secondDate = request('second_date');

        $results = Invoice::with(['customer'])
            ->latest()
            ->paginate(15);

        if($firstDate != '' && $secondDate != ''){
            $results = Invoice::latest()->with('customer')->whereBetween('date', [$firstDate, $secondDate])
            ->paginate(15);
        }

        return response()
        ->json([
            'results' => $results,
            'totalTurnOver' => $this->totalTurnOver, 
            'paidTurnOver' => $this->paidTurnOver,
            'unPaidTurnOver' => $this->unPaidTurnOver
        ]);
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
        
        $counter = Counter::where('key', 'Invoice')->first();

        $form = [
            'number' => $counter->prefix . $counter->value,
            'customer_id' => null,
            'customer' => null,
            'date' => $todayNepaliDate,
            'due_date' => $todayNepaliDate,
            'reference' => null,
            'discount' => 0,
            'terms_and_conditions' => 'Default Terms',
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
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
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'reference' => 'nullable|max:100',
            'discount' => 'required|numeric|min:0',
            'terms_and_conditions' => 'required|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        $invoice = new Invoice;
        $invoice->fill($request->except('items'));

        $invoice->sub_total = collect($request->items)->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        $invoice = DB::transaction(function() use ($invoice, $request) {
            $counter = Counter::where('key', 'Invoice')->first();
            $invoice->number = $counter->prefix . $counter->value;

            // custom method from app/Helper/HasManyRelation
            $invoice->storeHasMany([
                'items' => $request->items
            ]);

            $counter->increment('value');

            return $invoice;
        });

        return response()
            ->json(['saved' => true, 'id' => $invoice->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Invoice::with(['customer', 'items.product'])
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
        $form = Invoice::with(['customer', 'items.product'])
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
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'reference' => 'nullable|max:100',
            'discount' => 'required|numeric|min:0',
            'terms_and_conditions' => 'required|max:2000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|required|integer|exists:invoice_items,id,invoice_id,'.$invoice->id,
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        $invoice->fill($request->except('items'));

        $invoice->sub_total = collect($request->items)->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        $invoice = DB::transaction(function() use ($invoice, $request) {
            // custom method from app/Helper/HasManyRelation
            $invoice->updateHasMany([
                'items' => $request->items
            ]);

            return $invoice;
        });

        return response()
            ->json(['saved' => true, 'id' => $invoice->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->items()->delete();

        $invoice->delete();

        return response()
            ->json(['deleted' => true]);
    }
}
