<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VendorRegNotification;
use Illuminate\Support\Facades\Notification;

class VendorController extends Controller
{
    public function signUp(){

        return view('auth.vendor_signUp');
    }
    public function vendorRegister(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed'],
        ]);

        $vuser = User::where('role','admin')->get();

        $user = User::insert([
            'name' => $request->name,
            'username'=>$request->username,
            'email' => $request->email,
            'phone'=>$request->phone,
            'vendor_join'=>$request->vendor_join,
            'password' => Hash::make($request->password),
            'role'=>'vendor',
            'status'=>'inactive'
        ]);

        $notification = [
            'message'=> "Vendor Registered Successfully",
            'alert-type'=>"success"
        ];
        Notification::send($vuser, new VendorRegNotification($request));
        return  redirect()->route('vendor.login')->with($notification);
    }
    public function VendorLogin(){
        return view('vendor.VendorLogin');
    }
    public function VendorDashboard(){

        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.index',compact('vendorData'));
    }

    public function VendorProfile(){
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.VendorProfile',compact('vendorData'));
    }
    public function VendorProfileStore(Request $request){
        $id = Auth::user()->id;
        $vendorData = User::findOrFail($id);
        $vendorData->name = $request->name;
        $vendorData->email = $request->email;
        $vendorData->phone = $request->phone;
        $vendorData->address = $request->address;
        $vendorData->vendor_short_info = $request->vendor_info;
        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/Vendor_images/'.$vendorData->photo));
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/Vendor_images'), $filename);
            $vendorData->photo = $filename;
            // $vendorData['photo'] = $filename; 
        }
        $vendorData->save();
        $notification = [
            'message'=> "Vendor Profile Updated Successfully",
            'alert-type'=>"success"
        ];
        return  redirect()->back()->with($notification);
    }
    public function VendorChangePassword(){
        return view("vendor.Vendor_change_password");
    }
    public function VendorUpdatePassword(Request $request){

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
    public function VendorDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    }
}
