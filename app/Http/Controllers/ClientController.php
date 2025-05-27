<?php

namespace App\Http\Controllers;
use App\Location;
use App\UserLocation;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //
    public function index()
    {
       $locations = auth()->user()->locations;

         return view('clients.index',
        array(
            'locations' => $locations,
        ));
    }
    public function store(Request $request)
    {
        dd($request->all());
    }
}
