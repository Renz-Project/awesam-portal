<?php

namespace App\Http\Controllers;
use App\User;
use App\Location;
use App\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use RealRashid\SweetAlert\Facades\Alert;
class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::get();
        $locations = Location::get();
        return view('users.index',
            array(
                'users' => $users,
                'locations' => $locations,
            )
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
          $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'email' => 'email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);


        $new_account = new User;
        $new_account->name = $request->name;
        $new_account->email = $request->email;
        $new_account->position = $request->position;
        $new_account->role = $request->role;
        $new_account->password = bcrypt($request->password);
        $new_account->status = "Active";
        $new_account->save();

        foreach($request->locations as $location)
        {
            $new_location = new UserLocation;
            $new_location->location_id = $location;
            $new_location->user_id = $new_account->id;
            $new_location->save();
        }
        Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
    }

    public function editUser(Request $request,$id)
    {
        // dd($request->all());
        $this->validate($request, [
            'email' => 'unique:users,email,' . $id,
        ]);

        $account = User::where('id', $id)->first();
        $account->name = $request->name;
        $account->email = $request->email;
        $account->position = $request->position;
        $account->role = $request->role;
        $account->save();

        $user_locations = UserLocation::where('user_id',$id)->delete();
    
        foreach($request->locations_data as $location)
        {
            $new_location = new UserLocation;
            $new_location->location_id = $location;
            $new_location->user_id = $account->id;
            $new_location->save();
        }
        
      

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function updatePassword(Request $request)
    {
        $validator = $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
    
        $user = User::findOrFail(auth()->user()->id);
        $user->password = bcrypt($request->input('password'));
        $user->save();

        Alert::success('Password Updated')->persistent('Dismiss');
        return redirect('/users');
    }
    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        // Update fields
        $user->status = 'Inactive';
        $user->role = '';
        $user->password = bcrypt(str_random(16)); // safer than blank
        $user->save();

        // If currently logged-in user is deactivated
        if (Auth::check() && Auth::id() == $id) {
            Auth::logout();
            Session::flush();
            Alert::success('Deactivated', 'Your account has been deactivated.')->persistent('Dismiss');
            return redirect('/login');
        }

        // For admin or another user deactivating someone else
        Alert::success('Success', 'User has been deactivated successfully.')->persistent('Dismiss');
        return redirect()->back();
    }
}
