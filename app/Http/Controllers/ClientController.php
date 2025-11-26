<?php

namespace App\Http\Controllers;
use App\Location;
use App\UserLocation;
use App\Client;
use App\ClientAttachment;
use App\ClientLocation;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;


class ClientController extends Controller
{
    //
    public function search(Request $request)
    {
        $selectedLocation = $request->selectedLocation;
        $term = $request->get('q');
        // return $locationIds;
        $clients = Client::where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                  ->orWhere('last_name', 'like', "%{$term}%");
            })
            ->whereHas('locations', function ($query) use ($selectedLocation) {
                $query->where('locations.id', $selectedLocation);
            })
            ->orderBy('last_name')
            ->limit(100)
            ->get();

        return response()->json($clients);
    }
    public function index(Request $request)
    {
        $locations = auth()->user()->locations;
        $locationIds = $locations->pluck('id');

        $query = Client::whereHas('locations', function ($q) use ($locationIds) {
            $q->whereIn('locations.id', $locationIds);
        })->with('locations');

        // 🔹 Filter by location
        if ($request->filled('location_id')) {
            $query->whereHas('locations', function ($q) use ($request) {
                $q->where('locations.id', $request->location_id);
            });
        }

        // 🔹 Search by name, email, or contact
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(10);

        return view('clients.index', compact('locations', 'clients'));
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
    public function viewAttachment(Request $request,$id)
    {
        
        $attachment = ClientAttachment::findOrFail($id);
        $filePath = $attachment->file;
        // dd($filePath);
        // If using S3, generate a temporary URL
        if (Storage::disk('s3')->exists($filePath)) {
            $url = Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes(5));
            return redirect($url);
        }

        // If the file does not exist, return a 404 error
        abort(404, 'File not found');
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

        $file = $request->file('file');
        $path = Storage::disk('s3')->put('client-attachments', $file);
        $file_name = $path;
        $client->document_name = $file->getClientOriginalName();
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
        $client->avatar = $request->image_data;
        $client->save();
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function deleteClient($id)
    {
        $client = Client::findOrFail($id);

        // If you want to delete attachments as well:
        // foreach ($client->attachments as $a) {
        //     $a->delete();
        // }

        $client->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return redirect('/clients');
    }
}
