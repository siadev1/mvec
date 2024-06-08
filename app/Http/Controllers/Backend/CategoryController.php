<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function AllCategory(){
        $categories = Category::latest()->get();
        return view('Admin.category.category_all', compact('categories'));
    }
    public function AddCategory(){

    return view('Admin.category.add_category');
    }
    
    public function StoreCategory(Request $request){
        $request->validate([
            'category_name'=>'required',
            'category_image'=> 'required'
        ]);
        $image = $request->file('category_image');
        $name_gen= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        // Image::make($image)->resize(300,300)->save('upload/brand_images/'.$name_gen);
        $image->move('upload/category_images/', $name_gen);
        $url = 'upload/category_images/'.$name_gen;
        Category::insert([
            'category_name'=>$request->category_name,
            'category_slug'=>strtolower(str_replace(' ','-',$request->category_name)),
            'category_image'=> $url
        ]);
        $notification = [
            'message'=> "Category Added Successfully",
            'alert-type'=>"success"
        ];


        return redirect()->back()->with($notification);
    }
    public function CategoryEdit($id){
        
        $categoryData = Category::findOrFail($id);
        return view('Admin.category.category_edit',compact('categoryData')); 
    }
    public function StoreEditCategory(Request $request){
        // $request->validate([
        //     'brand_id'=>'required',
        //     'old_image'=> 'required'
        // ]);
        $category_id= $request->id;
        $old_image = $request->old_image;
        if($request->file('category_image') ){
            $image = $request->file('category_image');

            $name_gen= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            // Image::make($image)->resize(300,300)->save('upload/category_images/'.$name_gen);
            $image->move('upload/category_images/', $name_gen);
            $url = 'upload/category_images/'.$name_gen;
            if(file_exists($old_image)){
                unlink($old_image);
            }
            Category::findOrFail($category_id)->update([
                'category_name'=>$request->category_name,
                'category_slug'=>strtolower(str_replace(' ','-',$request->category_name)),
                'category_image'=> $url
            ]);
            $notification = [
                'message'=> "Category update Successfull",
                'alert-type'=>"success"
            ];
            return redirect()->url('all.category')->with($notification);
        }else{

            Category::whereId($category_id)->update([
                'category_name'=>$request->category_name,
                'category_slug'=>strtolower(str_replace(' ','-',$request->category_name)),
            ]);
            $notification = [
                'message'=> "Category name update Successfull",
                'alert-type'=>"success"
            ];
            return redirect()->route('all.category')->with($notification);
        }
        
    }
    public function DeleteCategory($id){

        $brand_image= Category::findOrFail($id)->brand_image;
        if(file_exists($brand_image)){

            unlink($brand_image);
        }
        // Category::findOrFail($id)->delete();
        $notification = [
            'message'=> "Brand Delete Successfull",
            'alert-type'=>"success"
        ];
        return redirect()->route('all.category')->with($notification);

    }

}
