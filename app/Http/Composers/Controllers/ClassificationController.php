<?php

namespace App\Http\Controllers;

use App\Models\ClassificationModel;
use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ClassificationController extends Controller
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
        ->where('form_id', '89')
        ->first();  
     
         $ClassificationList = ClassificationModel::join('usermaster', 'usermaster.userId', '=', 'classification_master.userId')
        ->join('item_category', 'item_category.cat_id', '=', 'classification_master.cat_id')
        ->where('classification_master.delflag','=', '0')
        ->get(['classification_master.*','usermaster.username','item_category.cat_name' ]);
  
  
        return view('ClassificationMasterList', compact('ClassificationList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //DB::enableQueryLog(); 
       
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
       
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        
        return view('ClassificationMaster',compact('Categorylist'));
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
             
            'cat_id'=> 'required',  
            'class_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    ClassificationModel::create($input);

    return redirect()->route('Classification.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassificationModel  $ClassificationModel
     * @return \Illuminate\Http\Response
     */
    public function show(ClassificationModel $ClassificationModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassificationModel  $ClassificationModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $Categorylist = CategoryModel::where('delflag','=','0')->get();
        $ClassificationList = ClassificationModel::find($id);
         
        return view('ClassificationMaster', compact('ClassificationList','Categorylist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubStyleModel  $SubStyleModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $ClassificationList = ClassificationModel::findOrFail($id);

        $this->validate($request, [
            
            'cat_id'=> 'required',  
            'class_name'=> 'required',  
           
        ]);

        $input = $request->all();

        $ClassificationList->fill($input)->save();

        return redirect()->route('Classification.index')->with('message', 'Update Record Succesfully');
    }
    
    
    public function GetCategoryList(Request $request)
    {  
        
        // $html = '';
        //   if (!$request->cat_id) {
        // $html = '<option value="">--Category--</option>';
        // } else {
      
        // $html = '<option value="">--Category--</option>';
        // $CategoryList = DB::table('sub_style_master')->where('mainstyle_id', $request->mainstyle_id)->get();
        
        // foreach ($SubStyleList as $row) {
        //         $html .= '<option value="'.$row->substyle_id.'">'.$row->substyle_name.'</option>';
              
        // }
        // }
        
        // return response()->json(['html' => $html]);
    }
    
    

 public function GetStyleList(Request $request)
    { 
        // $html = '';
        //   if (!$request->substyle_id) {
        // $html = '<option value="">--Style Name--</option>';
        // } else {
       
        //  $html = '<option value="">--Style Name--</option>';
        // $StyleList = DB::table('fg_master')->where('substyle_id', $request->substyle_id)->get();
        
        // foreach ($StyleList as $row) {
        //         $html .= '<option value="'.$row->fg_id.'">'.$row->fg_name.'</option>';
              
        // }
        // }
        
        // return response()->json(['html' => $html]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubStyleModel  $SubStyleModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClassificationModel::where('class_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
