<?php

namespace App\Http\Controllers;
use App\Location;
use App\UserLocation;
use App\Client;
use App\ClientLocation;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ClientController extends Controller
{
    //
    public function index()
    {
       $locations = auth()->user()->locations;
       $locationIds = $locations->pluck('id');
        $clients = Client::whereHas('locations', function ($query) use ($locationIds) {
            $query->whereIn('locations.id', $locationIds);
        })->with('locations')->get();

         return view('clients.index',
        array(
            'locations' => $locations,
            'clients' => $clients
        ));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $client = new Client;
        $client->first_name = $request->first_name;
        $client->middle_name = $request->middle_name;
        $client->last_name = $request->last_name;
        $client->save();
        foreach($request->locations as $location)
        {
            $client_location = new ClientLocation;
            $client_location->client_id = $client->id;
            $client_location->location_id = $location;
            $client_location->save();
        }
         Alert::success('Successfully Encoded')->persistent('Dismiss');
          return redirect("client/view/{$client->id}");
      
    }

    public function view(Request $request,$id)
    {
        $client = Client::findOrfail($id);

         return view('clients.view',
        array(
            'client' => $client,
        ));
    }
}
