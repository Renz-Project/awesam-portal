<?php

namespace App\Http\Controllers;
use App\Location;
use App\Client;
use App\Product;
use App\OfficeSupply;
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
       ->with('transactions')
       ->get()
       ->map(function ($location) {
           $totalAmountPaid = $location->transactions->sum('amount_paid');
           $totalAmountPaidDaily = ($location->transactions)->where('date',date('Y-m-d'))->sum('amount_paid');
           $clientCount = ($location->transactions)->where('date',date('Y-m-d'))->pluck('client_id')->unique()->count();
   
           return [
               'location_id' => $location->id,
               'location_name' => $location->name,
               'total_amount_paid' => $totalAmountPaid,
               'totalAmountPaidDaily' => $totalAmountPaidDaily,
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
            $monthlySalesByLocation = [];
            $locations = Location::whereIn('id', $locationIds)->pluck('name', 'id'); // e.g. [1 => 'Type 1', ...]

// Initialize with 0 values
            foreach ($locationIds as $locationId) {
                $monthlySalesByLocation[$locationId] = array_fill(0, 12, 0);
            }
            $year_transactions = ClientTransaction::whereIn('location_id', $locationIds)
            ->whereYear('date', $year)
            ->get();
                foreach ($transactions as $transaction) {
            $monthIndex = Carbon::parse($transaction->date)->month - 1; // Jan = 0
            $monthlySalesByLocation[$transaction->location_id][$monthIndex] += $transaction->amount_paid;
        }

// Format for chart
        $data = [];
        foreach ($monthlySalesByLocation as $locationId => $monthlyData) {
            $data[] = [
                'name' => $locations[$locationId] ?? 'Unknown',
                'data' => $monthlyData
            ];
        }

      
        $products = Product::with('stockMovements')->get();
        $fdos = OfficeSupply::with('stockMovements')->get();
        $locations = auth()->user()->locations;
        $locationIds = $locations->pluck('id');
        $locations = Location::whereIn('id',$locationIds)->get();
        // dd($selectedLocation);
        $report = [];
        $report_office_supplies = [];

        foreach ($products as $product) {
            foreach ($locations as $location) {
                $in = $product->stockMovements->where('location_id', $location->id)->where('type', 'inflow')->sum('quantity');
                $out = $product->stockMovements->where('location_id', $location->id)->where('type', 'outflow')->sum('quantity');
                $available = $in - $out;
                $report[] = [
                    'location_id' => $location->id,
                    'product_id' => $product->id,
                    'product_code' => $product->product_code,
                    'product_name' => $product->product_name,
                    'category' => $product->category,
                    'unit_price' => $product->unit_price,
                    'ideal_stock' => $product->ideal_stock,
                    'location' => $location->name,
                    'available_stock' => $available,
                    'notification' => $available < $product->ideal_stock ? '⚠ Low Stock' : '',
                    'available_stock_value' => $available * $product->unit_price,
                    'total_stock_value' => $in * $product->unit_price,
                ];
            }
        }
        foreach ($fdos as $product) {
            foreach ($locations as $location) {
                $in = $product->stockMovements->where('location_id', $location->id)->where('type', 'inflow')->sum('quantity');
                $out = $product->stockMovements->where('location_id', $location->id)->where('type', 'outflow')->sum('quantity');
                $available = $in - $out;
                $report_office_supplies[] = [
                    'location_id' => $location->id,
                    'product_id' => $product->id,
                    'product_code' => $product->product_code,
                    'product_name' => $product->product_name,
                    'category' => $product->category,
                    'unit_price' => $product->unit_price,
                    'ideal_stock' => $product->ideal_stock,
                    'location' => $location->name,
                    'available_stock' => $available,
                    'notification' => $available < $product->ideal_stock ? '⚠ Low Stock' : '',
                    'available_stock_value' => $available * $product->unit_price,
                    'total_stock_value' => $in * $product->unit_price,
                ];
            }
        }

        $lowStockReport = collect($report)->filter(function ($item) {
            return $item['available_stock'] < $item['ideal_stock'];
        });
        $lowStockReportFdos = collect($report_office_supplies)->filter(function ($item) {
            return $item['available_stock'] < $item['ideal_stock'];
        });
        return view('home',
        array(
            'lowStockReport' => $lowStockReport,
            'lowStockReportFdos' => $lowStockReportFdos,
            'locations' => $locations_d,
            'clients' => $clients,
            'transactions' => $transactions,
            'transactions_group' => $transactions_group,
            'data' => $data,
            ));
    }
}
