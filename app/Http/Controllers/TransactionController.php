<?php

namespace App\Http\Controllers;
use App\Client;
use App\Location;
use App\ClientTransaction;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    //
    public function index(Request $request)
    {
     
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
        $clients = Client::whereHas('locations', function ($query) use ($locationIds) {
            $query->whereIn('locations.id', $locationIds);
        })->with('locations')->get();

        $transactions = ClientTransaction::whereBetween('created_at', [$date_from." 00:00:00", $date_to." 23:59:59"])->get();
         return view('transactions.index',
            array(
                'clients' => $clients,
                'transactions' => $transactions,
                'locations' => $locations_d,
                'date_from' => $date_from,
                'date_to' => $date_to,
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
            $transaction->date = $request->date;
            $transaction->user_id = auth()->user()->id;
            $transaction->save();
        }
         Alert::success('Successfully Encoded')->persistent('Dismiss');
        return back();
    }
    public function report(Request $request)
    {
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
        $clients = Client::whereHas('locations', function ($query) use ($locationIds) {
            $query->whereIn('locations.id', $locationIds);
        })->with('locations')->get();

        $transactions = ClientTransaction::whereBetween('created_at', [$date_from." 00:00:00", $date_to." 23:59:59"])->get();
         return view('transactions.report',
            array(
                'clients' => $clients,
                'transactions' => $transactions,
                'locations' => $locations_d,
                'date_from' => $date_from,
                'date_to' => $date_to,
            )
            );
    }
}
