<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; 

use App\Notifications\VendorApproveNotification;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function AdminDashboard(){

        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.index',compact('adminData'));
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

            return back()->with('error',"old password did'nt match");
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
    public function InactiveVendor(){
        $inActiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();
        return view('vendor.inactive_vendor',compact('inActiveVendor'));

    }// End Mehtod 

    public function ActiveVendor(){
        $ActiveVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return view('vendor.active_vendor',compact('ActiveVendor'));

    }// End Mehtod 

    public function InactiveVendorDetails($id){

        $inactiveVendorDetails = User::findOrFail($id);
        return view('vendor.inactive_vendor_details',compact('inactiveVendorDetails'));

    }// End Mehtod 

    public function ActiveVendorApprove(Request $request){

        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'active',
        ]);

        $notification = array(
            'message' => 'Vendor Activated Successfully',
            'alert-type' => 'success'
        );
        $vuser = User::where('role','vendor')->get();
        Notification::send($vuser, new VendorApproveNotification($request));
        return redirect()->route('active.vendor')->with($notification);

    }// End Mehtod 

    public function ActiveVendorDetails($id){

        $activeVendorDetails = User::findOrFail($id);
        return view('vendor.active_vendor_details',compact('activeVendorDetails'));

    }// End Mehtod 


    public function InActiveVendorApprove(Request $request){

        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'inactive',
        ]);

        $notification = array(
            'message' => 'Vendor Deactivated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('inactive.vendor')->with($notification);

    }

    
    public function AllAdmin(){
        $alladminuser = User::where('role','admin')->latest()->get();
        return view('admin.admins.all_admin',compact('alladminuser'));
    }// End Mehtod  
    
    public function AddAdmin(){
        $roles = Role::all();
        return view('admin.admins.add_admin',compact('roles'));
    }// End Mehtod 


    public function AdminUserStore(Request $request){

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

         $notification = array(
            'message' => 'New Admin User Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);

    }// End Mehtod 




    public function EditAdminRole($id){

        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.admins.edit_admin',compact('user','roles'));
    }// End Mehtod 


    public function AdminUserUpdate(Request $request,$id){


        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address; 
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

         $notification = array(
            'message' => 'New Admin User Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);

    }// End Mehtod 


    public function DeleteAdminRole($id){

        $user = User::findOrFail($id);
        if (!is_null($user)) {
            $user->delete();
        }
 
         $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Mehtod
}