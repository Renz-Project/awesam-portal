<?php

namespace App\Http\Controllers;
use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //

    public function index()
    {
        $locations = Location::get();
           return view('locations.index',
        
            array(
                'locations' => $locations,
            )
        );
    }
}
