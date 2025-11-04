<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class CategoryContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '7')
        ->first();
        
               $Categorys = CategoryModel::join('usermaster', 'usermaster.userId', '=', 'item_category.userId')
        ->where('item_category.delflag','=', '0')
        ->get(['item_category.*','usermaster.username']);



       // $Countrys = Country::where('delflag','=', '0')->get();   

        return view('Category_Master_List', compact('Categorys','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Category_Master');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
           $this->validate($request, [
            'cat_name' => 'required',
        ]);

        $input = $request->all();

        CategoryModel::create($input);

        return redirect()->route('Category.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryModel  $categoryModel
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryModel $categoryModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryModel  $categoryModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $categorys = CategoryModel::find($id);
        
        return view('Category_Master',compact('categorys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryModel  $categoryModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $category = CategoryModel::findOrFail($id);

        $this->validate($request, [
            'cat_name' => 'required',
        ]);

        $input = $request->all();

        $category->fill($input)->save();

        return redirect()->route('Category.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryModel  $categoryModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
         CategoryModel::where('cat_id', $id)->update(array('delflag' => 1));

        
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
