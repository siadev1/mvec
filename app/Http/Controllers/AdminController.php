<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard(){


        return view('admin.index');
    }

    public function AdminLogin(){
        return view('admin.AdminLogin');
    }

    public function AdminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.adminProfile',compact('adminData'));
    }
    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $adminData = User::findOrFail($id);
        $adminData->name = $request->name;
        $adminData->email = $request->email;
        $adminData->phone = $request->phone;
        $adminData->address = $request->address;
        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$adminData->photo));
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $adminData->photo = $filename;
            // $adminData['photo'] = $filename; 
        }
        $adminData->save();
        $notification = [
            'message'=> "Admin Profile Updated Successfully",
            'alert-type'=>"success"
        ];
        return  redirect()->back()->with($notification);
    }
    public function AdminChangePassword(){
        return view("admin.admin_change_password");
    }
    public function AdminUpdatePassword(Request $request){

        $request->validate([
            "old_password"=> 'required',
            "new_password"=>'required|confirmed'
        ]);
        $old_password = $request->old_password;

        if(!Hash::check($old_password, Auth::user()->password)){

            return back()->with('error',"old password don't match");
        };
        User::whereId(auth()->user()->id)->update([
            'password'=> Hash::make($request->new_password)
        ]);
        return back()->with('status',"password changed successfully");
    }
    public function AdminDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
