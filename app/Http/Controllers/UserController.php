<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function UserDashboard(){
        $id = Auth::user()->id;
        $UserData = User::find($id);
        return view('index',compact('UserData'));
    }
    public function UserDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $notification = [
            'message'=> "Logout Successfull",
            'alert-type'=>"success"
        ];

        return redirect('/login')->with($notification);
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
    public function UserUpdatePassword(Request $request){

        $request->validate([
            "old_password"=> 'required',
            "new_password"=>'required|confirmed'
        ]);
        $old_password = $request->old_password;

        if(!Hash::check($old_password, Auth::user()->password)){

            return back()->with('error',"old password did'nt match");
        };
        User::whereId(auth()->user()->id)->update([
            'password'=> Hash::make($request->new_password)
        ]);
        return back()->with('status',"password changed successfully");
    }
}
