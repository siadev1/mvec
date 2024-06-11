<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Image;

class BrandController extends Controller
{
    public function AllBrand(){
        $brands = Brand::latest()->get();
        return view('Admin.brand.brand_all', compact('brands'));
    }
    public function AddBrand(){

    return view('Admin.brand.add_brand');
    }
    
    public function StoreBrand(Request $request){
        $request->validate([
            'brand_name'=>'required',
            'brand_image'=> 'required'
        ]);
        $image = $request->file('brand_image');
        $name_gen= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/brand_images/'.$name_gen);
        $image->move('upload/brand_images/', $name_gen);
        $url = 'upload/brand_images/'.$name_gen;
        Brand::insert([
            'brand_name'=>$request->brand_name,
            'brand_slug'=>strtolower(str_replace(' ','-',$request->brand_name)),
            'brand_image'=> $url
        ]);
        $notification = [
            'message'=> "Brand Added Successfully",
            'alert-type'=>"success"
        ];


        return redirect()->back()->with($notification);
    }
    public function BrandEdit($id){
        
        $brandData = Brand::findOrFail($id);
        return view('Admin.brand.brand_edit',compact('brandData')); 
    }
    public function StoreEditBrand(Request $request){
        // $request->validate([
        //     'brand_id'=>'required',
        //     'old_image'=> 'required'
        // ]);
        $brand_id= $request->id;
        $old_image = $request->old_image;
        if($request->file('brand_image') ){
            $image = $request->file('brand_image');

            $name_gen= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/brand_images/'.$name_gen);
            $image->move('upload/brand_images/', $name_gen);
            $url = 'upload/brand_images/'.$name_gen;
            if(file_exists($old_image)){
                unlink($old_image);
            }
            Brand::findOrFail($brand_id)->update([
                'brand_name'=>$request->brand_name,
                'brand_slug'=>strtolower(str_replace(' ','-',$request->brand_name)),
                'brand_image'=> $url
            ]);
            $notification = [
                'message'=> "Brand update Successfull",
                'alert-type'=>"success"
            ];
            return redirect()->url('all.brand')->with($notification);
        }else{

            Brand::whereId($brand_id)->update([
                'brand_name'=>$request->brand_name,
                'brand_slug'=>strtolower(str_replace(' ','-',$request->brand_name)),
            ]);
            $notification = [
                'message'=> "Brand name update Successfull",
                'alert-type'=>"success"
            ];
            return redirect()->route('all.brand')->with($notification);
        }
        
    }
    public function DeleteBrand($id){

        $brand_image= Brand::findOrFail($id)->brand_image;
        if(file_exists($brand_image)){

            unlink($brand_image);
        }
        Brand::findOrFail($id)->delete();
        $notification = [
            'message'=> "Brand Delete Successfull",
            'alert-type'=>"success"
        ];
        return redirect()->route('all.brand')->with($notification);

    }
}
