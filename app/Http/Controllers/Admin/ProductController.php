<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\models\Product;
use App\models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
     
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('dashboard.products.index',compact('categories','products'));
    }

   
    public function create()
    {
        
    }

     
    public function store(Request $request)
    {
        try{
            $validation = Validator::make($request->all(), [
                'product_name' => 'required|unique:products|max:255',
                'description' => 'required',
                'category_id' => 'required',
            ],[
                'product_name.required' =>'يرجي ادخال اسم المنتج',
                'product_name.unique' =>'اسم المنتج مسجل مسبقا',
                'description.required' =>' يرجي ادخال وصف المنتج  ',
                'category_id.required' =>'يرجي ادخال قسم لهذا المنتج',
            ]);
                 
            if ($validation->fails()) {
            return redirect()->route('products.index')->withErrors($validation)->with('add_product','error');
            }
     
            $products = new Product;
            $products->product_name = $request->product_name;
            $products->description = $request->description;
            $products->category_id = $request->category_id;
            $products->save();
    
            return redirect()->route('products.index')->with(['success' => 'تم اضافه المنتج بنجاح  ']);
        
        }catch (\Exception $ex)
        {
            dd($ex);
            return redirect()->route('products.index')->with(['errors' => ' هناك خطا في البيانات  ']);

        }
        
    }

    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request,$id)
    {

        try{

        // return $request->product_name;

        $products = Product::find($request->product_id);

        $validation = Validator::make($request->all(), [
            'product_name' => 'required|max:255|unique:products,product_name,'.$id,
            'description' => 'required',
            'category_id' => 'required',


        ],[
            'product_name.required' =>'يرجي ادخال اسم المنتج',
            'product_name.unique' =>'اسم المنتج مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',
            'category_id.required' =>'يرجي ادخال قسم لهذا المنتج',

        ]);

         if ($validation->fails()) {
            return redirect()->route('products.index')->withErrors($validation)->with('edit_product','edit_error');
          }
         
       
          $form_data = array(
            'product_name'                    =>  $request->product_name,
            'description'                      =>  $request->description,
            'category_id'                      =>  $request->category_id,
        );
        // dd($form_data);

        $products->update($form_data);

        return redirect()->route('products.index')->with(['success' => 'Updated successfuly']);     

        }catch(\Exception $ex){
        dd($ex);
        return redirect()->route('products.index')->with(['errors' => 'error']);     

        }  
        
    }

    
    public function destroy(Request $request)
    {
        $id = $request->id;

        // $products = Product::find($id);

        // dd($id);
        Product::find($id)->delete();
        return redirect()->route('products.index')->with(['success' => 'تم حذف المنتج بنجاح']);     
      
    }
}
