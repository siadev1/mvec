<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function UserDashboard(){
        $id = Auth::user()->id;
        $UserData = User::find($id);
        return view('index',compact('UserData'));
    }
    public function UserDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function UserProfileUpdate(Request $request){
        $id = Auth::user()->id;
        $UserData = User::findOrFail($id);
        $UserData->name = $request->name;
        $UserData->email = $request->email;
        $UserData->phone = $request->phone;
        $UserData->address = $request->address;
        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/User_images/'.$UserData->photo));
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/User_images'), $filename);
            $UserData->photo = $filename;
            // $UserData['photo'] = $filename; 
        }
        $UserData->save();
        $notification = [
            'message'=> "User Profile Updated Successfully",
            'alert-type'=>"success"
        ];
        return  redirect()->back()->with($notification);
    }
}
