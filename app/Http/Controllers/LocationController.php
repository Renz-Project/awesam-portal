<?php

namespace App\Http\Controllers;
use App\Location;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
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
    public function store(Request $request)
    {
          // dd($request->all());
        $location = new Location;
        $location->name = $request->name;
        $location->address = $request->address;
        $location->save();
         Alert::success('Successfully Encoded')->persistent('Dismiss');
        return back();
        
      
    }
}
