<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function AllSubCategory(){
        $subCategories = SubCategory::latest()->with(['category'])->get();
        return view('Admin.subCategory.subCategory_all', compact('subCategories'));
    }
    public function AddSubCategory(){
        $categories = Category::orderBy('category_name', 'ASC')->get();
    return view('Admin.subCategory.add_subCategory',compact('categories'));
    }
    
    public function StoreSubCategory(Request $request){
        $request->validate([
            'category_id'=>'required',
            'subCategory_name'=> 'required'
        ]);
        SubCategory::insert([
            'category_id'=>$request->category_id,
            'subCategory_name'=>$request->subCategory_name,
            'subCategory_slug'=>strtolower(str_replace(' ','-',$request->subCategory_name)),
        ]);
        $notification = [
            'message'=> "SubCategory Added Successfully",
            'alert-type'=>"success"
        ];


        return redirect()->back()->with($notification);
    }
    public function SubCategoryEdit($id){
        $categories = Category::orderBy('category_name', 'ASC')->get();
        
        $subCategoryData = SubCategory::with(['category'])->findOrFail($id);
        return view('Admin.subCategory.subCategory_edit',compact('subCategoryData','categories')); 
    }
    public function StoreEditSubCategory(Request $request){
        
        $request->validate([
            'id'=>'required',
            'category_id'=>'required',
            'subCategory_name'=> 'required'
        ]);

        $subCategory_id= $request->id;
        
        
            
        SubCategory::findOrFail($subCategory_id)->update([
            'category_id'=> $request->category_id,
            'subCategory_name'=>$request->subCategory_name,
            'subCategory_slug'=>strtolower(str_replace(' ','-',$request->subCategory_name)),
        ]);
        $notification = [
            'message'=> "SubCategory update Successfull",
            'alert-type'=>"success"
        ];
        return redirect()->route('all.subCategory')->with($notification);
        
                
        
        
    }
    public function DeleteSubCategory($id){

        // $brand_image= SubCategory::findOrFail($id)->subCategory_image;
        // if(file_exists($subCategory_image)){

        //     unlink($subCategory_image);
        // }
        SubCategory::findOrFail($id)->delete();
        $notification = [
            'message'=> "SubCategory Delete Successfull",
            'alert-type'=>"success"
        ];
        return redirect()->route('all.subCategory')->with($notification);

    }
}
