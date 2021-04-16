<?php

namespace App\Http\Controllers\Admin;

use App\models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;


class CategoryController extends Controller
{
     
    public function index()
    {
        $categories = Category::all();
        // dd($categories);
        return view('dashboard.categories.index',compact('categories'));
    }

   
    public function create()
    {
        
    }

     
    public function store(Request $request)
    {
        try{

            $validation = Validator::make($request->all(), [
                'category_name' => 'required|unique:categories|max:255',
                // 'description' => 'required',
            ],[
                'category_name.required' =>'يرجي ادخال اسم القسم',
                'category_name.unique' =>'اسم القسم مسجل مسبقا',
                // 'description.required' =>' يرجي ادخال وصف القسم  ',
            ]);
                 
            if ($validation->fails()) {
            return redirect()->route('categories.index')->withErrors($validation)->with('add_category','error');
            }
     
            $categories = new Category;
            $categories->category_name = $request->category_name;
            $categories->description = $request->description;
            $categories->Created_by = auth()->guard('admin')->user()->name;
            $categories->save();
    
            return redirect()->route('categories.index')->with(['success' => 'تم اضافه القسم بنجاح  ']);
        
        }catch (\Exception $ex)
        {
            dd($ex);
            return redirect()->route('categories.index')->with(['errors' => ' هناك خطا في البيانات  ']);

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

        return $request->all();
        try{

        $categories = Category::find($request->category_id);

        
        $validation = Validator::make($request->all(), [
            'category_name' => 'required|max:255|unique:categories,category_name,'.$id,
            'description' => 'required',

        ],[
            'category_name.required' =>'يرجي ادخال اسم القسم',
            'category_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',
        ]);

         if ($validation->fails()) {
            return redirect()->route('categories.index')->withErrors($validation)->with('edit_category','edit_error');
          }
         
       
          $form_data = array(
            'category_name'                    =>  $request->category_name,
            'description'                      =>  $request->description,
        );
        // dd($form_data);

        $categories->update($form_data);

        return redirect()->route('categories.index')->with(['success' => 'Updated successfuly']);     

        }catch(\Exception $ex){
        dd($ex);
        return redirect()->route('categories.index')->with(['errors' => 'error']);     

        }  
        
    }

    
    public function destroy(Request $request)
    {
        $id = $request->id;

        $cat = Category::find($id);

        $product = $cat->products();
        if (isset($product) && $product->count() > 0) {
            return redirect()->route('categories.index')->with(['errors' => ' لأ يمكن حذف هذا القسم يوجد منتجات مرتبطه به  ']);
        }

        $cat->delete();
        // dd($product);

        // Category::find($id)->delete();
        return redirect()->route('categories.index')->with(['success' => 'تم حذف القسم بنجاح']);     
      
    }
}
