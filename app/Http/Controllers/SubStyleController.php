<?php

namespace App\Http\Controllers;

use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SubStyleController extends Controller
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
        ->where('form_id', '87')
        ->first();  
        
        
        $SubStyleList = SubStyleModel::join('usermaster', 'usermaster.userId', '=', 'sub_style_master.userId')
        ->join('main_style_master', 'main_style_master.mainstyle_id','=','sub_style_master.mainstyle_id')
        ->where('sub_style_master.delflag','=', '0')
        ->get(['sub_style_master.*','usermaster.username','main_style_master.mainstyle_name' ]);
  
        return view('SubStyleMasterList', compact('SubStyleList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //DB::enableQueryLog(); 
       
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=','0')->get();
       
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        
        return view('SubStyleMaster',compact('MainStyleList'));
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
             
            'mainstyle_id'=> 'required',  
            'substyle_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    SubStyleModel::create($input);

    return redirect()->route('SubStyle.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubStyleModel  $SubStyleModel
     * @return \Illuminate\Http\Response
     */
    public function show(SubStyleModel $SubStyleModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubStyleModel  $SubStyleModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=','0')->get();
        $SubStyleList = SubStyleModel::find($id);
         
        return view('SubStyleMaster', compact('SubStyleList','MainStyleList'));
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
        $SubStyleList = SubStyleModel::findOrFail($id);

        $this->validate($request, [
            
            'mainstyle_id'=> 'required',  
            'substyle_name'=> 'required',
           
        ]);

        $input = $request->all();

        $SubStyleList->fill($input)->save();

        return redirect()->route('SubStyle.index')->with('message', 'Update Record Succesfully');
    }
    
    
    public function GetSubStyleList(Request $request)
    {  $html = '';
          if (!$request->mainstyle_id) {
        $html = '<option value="">--Sub Style--</option>';
        } else {
      
        $html = '<option value="">--Sub Style--</option>';
        $SubStyleList = DB::table('sub_style_master')->where('mainstyle_id', $request->mainstyle_id)->where("delflag","=",0)->where("status","=",1)->get();
        
        foreach ($SubStyleList as $row) {
                $html .= '<option value="'.$row->substyle_id.'">'.$row->substyle_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
    }
    
    

 public function GetStyleList(Request $request)
    { $html = '';
          if (!$request->substyle_id) {
        $html = '<option value="">--Style Name--</option>';
        } else {
       
         $html = '<option value="">--Style Name--</option>';
        $StyleList = DB::table('fg_master')->where('substyle_id', $request->substyle_id)->where("delflag","=",0)->where("status","=",1)->get();
        
        foreach ($StyleList as $row) {
                $html .= '<option value="'.$row->fg_id.'">'.$row->fg_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubStyleModel  $SubStyleModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubStyleModel::where('substyle_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    
    public function changeSubStyleCategoryStatus(Request $request)
    {    
         DB::table('sub_style_master')->where('substyle_id',$request->substyle_id)->update(['status'=>$request->status]);
         return 1;
    }
    
}
