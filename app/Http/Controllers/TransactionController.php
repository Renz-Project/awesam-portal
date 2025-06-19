<?php

namespace App\Http\Controllers;
use App\Client;
use App\ClientTransaction;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    //
    public function index()
    {
        $locations = auth()->user()->locations;
       $locationIds = $locations->pluck('id');
        $clients = Client::whereHas('locations', function ($query) use ($locationIds) {
            $query->whereIn('locations.id', $locationIds);
        })->with('locations')->get();

        $transactions = ClientTransaction::get();
         return view('transactions.index',
            array(
                'clients' => $clients,
                'transactions' => $transactions
            )
            );
    }
    public function store(Request $request)
    {
        foreach($request->treatment as $key => $treatment)
        {
            $transaction = new ClientTransaction;
            $transaction->client_id = $request->client_id;
            $transaction->dentist = $request->dentist;
            $transaction->treatment = $treatment;
            $transaction->amount_paid = $request->amount[$key];
            $transaction->type = $request->type;
            $transaction->remarks = $request->remarks;
            $transaction->location_id = $request->location;
            $transaction->user_id = auth()->user()->id;
            $transaction->save();
        }
         Alert::success('Successfully Encoded')->persistent('Dismiss');
        return back();
    }
}
