<?php

namespace App\Http\Controllers;
use App\Location;
use App\UserLocation;
use App\Client;
use App\ClientAttachment;
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
          return redirect("client/{$client->id}");
      
    }

    public function view(Request $request,$id)
    {
        
        $client = Client::with('locations')->findOrfail($id);
        $locations = auth()->user()->locations;
         return view('clients.view',
        array(
            'client' => $client,
            'locations' => $locations,
        ));
    }
    public function updateLocation(Request $request,$id)
    {
   
        $user_locations = ClientLocation::where('client_id',$id)->delete();
        //  dd($user_locations);
        foreach($request->locations as $location)
        {
            $new_location = new ClientLocation;
            $new_location->location_id = $location;
            $new_location->client_id = $id;
            $new_location->save();
        }

        Alert::success('Successfully Update')->persistent('Dismiss');
        return back();
    }
    public function upload(Request $request,$id)
    {
        // dd($request->all());

        $client = new ClientAttachment;
        $client->client_id = $id;

        $attachment = $request->file('file');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/attachments/', $name);
        $file_name = '/attachments/'.$name;
        $client->document_name = $original_name;
        $client->file = $file_name;
        $client->save();


        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();
    }
    public function updateInformation(Request $request,$id)
    {
        // dd($request->all());
        $client = Client::findOrFail($id);
      $client->first_name       = $request->first_name;
    $client->middle_name      = $request->middle_name;
    $client->last_name        = $request->last_name;
    $client->nickname         = $request->nickname;
    $client->birth_date        = $request->birthdate;
    $client->sex              = $request->sex;
    $client->religion         = $request->religion;
    $client->nationality      = $request->nationality;
    $client->home_address     = $request->home_address;
    $client->home_number      = $request->home_number;
    $client->occupation       = $request->occupation;
    $client->office_number    = $request->office_number;
    $client->fax_number       = $request->fax_number;
    $client->mobile_number     = $request->phone_number;
    $client->email_address    = $request->email_address;
    $client->dental_insurance = $request->dental_insurance;
    $client->effective_date   = $request->effective_date;

    $client->save();


        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function changeAvatar(Request $request,$id)
    {
        // dd($request->all());
        $client = Client::findOrFail($id);
        $attachment = $request->file('avatar');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/avatars/', $name);
        $file_name = '/avatars/'.$name;
        $client->avatar = $file_name;

        $client->save();


        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
}
