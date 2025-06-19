<?php

namespace App\Http\Controllers;
use App\Location;
use App\Client;
use App\ClientTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactionsPerMonth = array_fill(0, 12, 0); // Index 0 = Jan
        $salesPerMonth = array_fill(0, 12, 0);
        $year = date('Y');
       $locations = auth()->user()->locations;
       $locationIds = $locations->pluck('id');
       $locations_d = Location::whereIn('id', $locationIds)
       ->with(['transactions' => function ($query) {
           $query->select('id', 'location_id', 'client_id', 'amount_paid');
       }])
       ->get()
       ->map(function ($location) {
           $totalAmountPaid = $location->transactions->sum('amount_paid');
           $clientCount = $location->transactions->pluck('client_id')->unique()->count();
   
           return [
               'location_id' => $location->id,
               'location_name' => $location->name,
               'total_amount_paid' => $totalAmountPaid,
               'client_count' => $clientCount,
           ];
       });
        $clients = Client::whereHas('locations', function ($query) use ($locationIds) {
            $query->whereIn('locations.id', $locationIds);
        })->with('locations')->get();
        $transactions = ClientTransaction::whereIn('location_id',$locationIds)->whereMonth('date',date('m'))->whereYear('date',date('Y'))->get();
        $transactions_group = ClientTransaction::whereIn('location_id', $locationIds)
            ->where('date', date('Y-m-d'))
            ->get()
            ->groupBy('client_id');
            $year_transactions = ClientTransaction::whereIn('location_id',$locationIds)->whereYear('date', $year)->get();
            foreach ($year_transactions as $transaction) {
                $monthIndex = Carbon::parse($transaction->date)->month - 1; // 0-indexed for Jan
                $transactionsPerMonth[$monthIndex]++; // count of transactions
                $salesPerMonth[$monthIndex] += $transaction->amount_paid; // sum of sales
            }
            

            $data = [
                [
                    'name' => 'Sales',
                    'data' => $salesPerMonth
                ]
            ];
        return view('home',
        array(
            'locations' => $locations_d,
            'clients' => $clients,
            'transactions' => $transactions,
            'transactions_group' => $transactions_group,
            'data' => $data,
            ));
    }
}
