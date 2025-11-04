<?php

namespace App\Http\Controllers;

use App\Models\FormTableMasterModel;
use App\Models\FormTableDetailModel; 

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FormTableMasterController extends Controller
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
        ->where('form_id', '340')
        ->first();

        $data = FormTableMasterModel::select('year_end_form_table_master.*', 'form_master.form_label','usermaster.username')
        ->join('usermaster', 'usermaster.userId', '=', 'year_end_form_table_master.userId')
        ->Join('form_master', 'form_master.form_code','year_end_form_table_master.form_id')
            ->orderBy('year_end_form_table_master.form_id', 'DESC')
            ->get();


        return view('FormTableMasterList', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $FormList = DB::table('form_master')->where('delflag','=', '0')->where('head_id','=', '2')->get();
           return view('FormTableMaster',compact('FormList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
           $data1=array
            (
                'form_id'=>$request->form_id, 
                'form_detail'=>$request->form_detail, 
                'userId'=>$request->userId,
                'seq_no'=>$request->seq_no,
                'updated_by'=>$request->userId,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
             );
         
            FormTableMasterModel::insert($data1);
           
            $last_year_database_name = $request->last_year_database_name;
            if(count($last_year_database_name)>0)
            {   
                    
                for($x=0; $x<count($last_year_database_name); $x++) 
                {
                  
                
                        $data2 =array
                        ( 
                            'form_id'=>$request->form_id, 
                            'last_year_database_name'=>$request->last_year_database_name[$x],
                            'new_year_database_name'=>$request->new_year_database_name[$x],
                            'table_name'=>$request->table_name[$x],
                            'p_key_name'=>$request->p_key_name[$x], 
                       );
                   
                       FormTableDetailModel::insert($data2);
                    } 
            }
            
        return redirect()->route('FormTableMaster.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormTableMaster  $FormTableMaster
     * @return \Illuminate\Http\Response
     */
    public function show(FormTableMaster $FormTableMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormTableMaster  $FormTableMaster
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $FormTableMaster = FormTableMasterModel::find($id);  
        $FormTableDetailList = FormTableDetailModel::where('form_id','=', $FormTableMaster->form_id)->get();
        $FormList = DB::table('form_master')->where('delflag','=', '0')->where('head_id','=', '2')->get();  
        return view('FormTableMasterEdit',compact('FormTableMaster', 'FormTableDetailList', 'FormList')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormTableMaster  $FormTableMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        // echo '<pre>'; print_R($_POST);exit;
            $data1=array
            (
                'form_id'=>$request->form_id, 
                'form_detail'=>$request->form_detail, 
                'userId'=>$request->userId,
                'seq_no'=>$request->seq_no,
                'updated_by'=>$request->userId,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
             );
        
        $FormTableMasterList = FormTableMasterModel::findOrFail($request->form_id);
        $FormTableMasterList->fill($data1)->save(); 
        
        $last_year_database_name = $request->last_year_database_name;
        if(count($last_year_database_name)>0) 
        {   
            DB::table('year_end_form_table_detail')->where('form_id', $request->form_id)->delete();
            
            for($x=0; $x<count($last_year_database_name); $x++) 
            {
              
            //   if($request->order_qty[$x]>0)
            //   {
                $data2 =array
                ( 
                    'form_id'=>$request->form_id, 
                    'last_year_database_name'=>$request->last_year_database_name[$x],
                    'new_year_database_name'=>$request->new_year_database_name[$x],
                    'table_name'=>$request->table_name[$x],
                    'p_key_name'=>$request->p_key_name[$x], 
               );
              
                          
                      FormTableDetailModel::insert($data2);
                  }  
                  
                // } 
            }
        
        return redirect()->route('FormTableMaster.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormTableMaster  $FormTableMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        FormTableMasterModel::where('form_id', $id)->delete(); 
        FormTableDetailModel::where('form_id', $id)->delete(); 
        return redirect()->route('FormTableMaster.index')->with('message', 'Deleted Record Succesfully');
    }
     

}
