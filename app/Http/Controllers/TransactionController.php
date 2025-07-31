<?php

namespace App\Http\Controllers;
use App\Client;
use App\Location;
use App\Product;
use App\ClientPayment;
use App\ClientTransaction;
use App\ClientAttachment;
use App\Expense;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    //
    public function index(Request $request)
    {
        $selectedLocation = $request->location;
        $date_from = date('Y-m-d');
        $date_to =  date('Y-m-d');
        if($request->date_from)
        {
            $date_from = $request->date_from;
            $date_to = $request->date_to;
        }
        $locations = auth()->user()->locations;
       $locationIds = $locations->pluck('id');
       $locations_d = Location::whereIn('id',$locationIds)->get();

       $expenses = Expense::where('location_id',$selectedLocation)->whereBetween('date', [$date_from, $date_to])->get();
        $clients = Client::whereHas('locations', function ($query) use ($selectedLocation) {
            $query->where('locations.id', $selectedLocation);
        })->with('locations')->get();
        
         $transactions = Client::whereHas('locations', function ($query) use ($selectedLocation) {
            $query->where('locations.id', $selectedLocation);
        })->whereHas('transactions', function ($query)  {
            $query->where('date', date('Y-m-d'));
        }
        
        )->with([
    'locations',
    'transactions' => function ($query) {
        $query->whereDate('date', date('Y-m-d'));
    }
])->get();
        $products = Product::select('id', 'product_name', 'unit_price')->get();
         return view('transactions.index',
            array(
                'clients' => $clients,
                'transactions' => $transactions,
                'locations' => $locations_d,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'products' => $products,
                'selectedLocation' => $selectedLocation,
                'expenses' => $expenses,
            )
            );
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $first_id = 0;
           if (!empty($request->treatment)) {
                foreach($request->treatment as $key => $treatment)
                {
                    
                    $transaction = new ClientTransaction;
                    $transaction->client_id = $request->client_id;
                    $transaction->dentist = $request->dentist;
                    $transaction->dentist_2 = $request->dentist_2;
                    $transaction->dentist_3 = $request->dentist_3;
                    $transaction->treatment = $treatment;
                    $transaction->amount_paid = $request->amount[$key];
                    $transaction->type = $request->type;
                    $transaction->remarks = $request->remarks;
                    $transaction->location_id = $request->location;
                    $transaction->date = $request->date;
                    $transaction->user_id = auth()->user()->id;
                    $transaction->save();
                    if($first_id == 0)
                    {
                        $first_id = $transaction->id;
                    }
                }
            }
        if (!empty($request->product)) {
            foreach($request->product as $key => $product)
            {
                $transaction = new ClientTransaction;
                $transaction->client_id = $request->client_id;
                $transaction->dentist = $request->dentist;
                $transaction->dentist_2 = $request->dentist_2;
                $transaction->dentist_3 = $request->dentist_3;
                $transaction->product_id = $product;
                $transaction->amount_paid = $request->product_amount[$key];
                $transaction->qty = $request->quantity[$key];
                $transaction->type = $request->type;
                $transaction->remarks = $request->remarks;
                $transaction->location_id = $request->location;
                $transaction->date = $request->date;
                $transaction->user_id = auth()->user()->id;
                $transaction->save();
                 if($first_id == 0)
                {
                    $first_id = $transaction->id;
                }
            }
        }
  
        foreach($request->payment_type as $keyabs => $paymentd)
        {
        
            $payment = new ClientPayment;
            $payment->client_transaction_id = $first_id;
            $payment->payment_type = $paymentd;
            $payment->amount = $request->payment_amount[$keyabs];
            $payment->save();
        }
        if($request->hasFile('file'))
        {
            $client = new ClientAttachment;
            $client->client_id = $request->client_id;

            $attachment = $request->file('file');
            $original_name = $attachment->getClientOriginalName();
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path().'/attachments/', $name);
            $file_name = '/attachments/'.$name;
            $client->document_name = $original_name;
            $client->file = $file_name;
            $client->save();
        }
        
         Alert::success('Successfully Encoded')->persistent('Dismiss');
        return back();
    }
    public function report(Request $request)
    {
        $selectedLocation = $request->location;
        $date_from = date('Y-m-d');
        $date_to =  date('Y-m-d');
        if($request->date_from)
        {
            $date_from = $request->date_from;
            $date_to = $request->date_to;
        }
        $locations = auth()->user()->locations;
       $locationIds = $locations->pluck('id');
       $locations_d = Location::whereIn('id',$locationIds)->get();
       $expenses = Expense::where('location_id',$selectedLocation)->whereBetween('date', [$date_from, $date_to])->get();
        $clients = Client::whereHas('locations', function ($query) use ($locationIds) {
            $query->whereIn('locations.id', $locationIds);
        })->with('locations')->get();

        $transactions = ClientTransaction::where('location_id',$selectedLocation)->whereIn('location_id',$locationIds)->whereBetween('date', [$date_from, $date_to])->get();
        // dd($transactions);
         return view('transactions.report',
            array(
                'clients' => $clients,
                'transactions' => $transactions,
                'locations' => $locations_d,
                'selectedLocation' => $selectedLocation,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'expenses' => $expenses,
            )
            );
    }
}
