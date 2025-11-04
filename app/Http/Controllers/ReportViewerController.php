<?php

namespace App\Http\Controllers;

use App\Models\ReportViewerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ReportViewerController extends Controller
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
         ->where('form_id', '171')
         ->first();
         
        $ReportMgmtList = DB::table('report_viewer')
                            ->select('report_viewer.*','module_master.moduleName', 'form_master.form_label')
                            ->join('module_master','module_master.moduleId', '=', 'report_viewer.moduleId') 
                            ->join('form_master','form_master.form_code', '=', 'report_viewer.form_code')
                            ->get();
       
        return view('ReportManagementList',compact('chekform','ReportMgmtList'));
    }

/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
    public function create()
    {
   
       $moduleList = DB::table('module_master')->get();
       $formList = DB::table('form_master')->WHEREIN('head_id', [4,5])->get();
       
       return view('ReportManagementMaster',compact('moduleList','formList'));
    
    }

/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
    public function store(Request $request)
    {
        $data=array(
        'moduleId'=>$request->moduleId, 
        'form_code'=>$request->form_code,
        'description'=>$request->description, 
        );
        
      ReportViewerModel::insert($data);
      return redirect()->route('ReportViewer.index')->with('message', 'Add Record Succesfully');
    
    }

/**
* Display the specified resource.
*
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
    public function show(ReceiptModel $receiptModel)
    {
    //
    }

/**
* Show the form for editing the specified resource.
*
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
    public function edit($id)
    {
    
        $ReportList = ReportViewerModel::find($id);
        $moduleList = DB::table('module_master')->get();
        $formList = DB::table('form_master')->WHEREIN('head_id', [4,5])->get();
        return view('ReportManagementMaster', compact('ReportList','moduleList','formList'));
    
    }

/**
* Update the specified resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
    public function update(Request $request, $id)
    {
      
        $reportViewerId = ReportViewerModel::findOrFail($id);
        $this->validate($request, [
            'moduleId' => 'required',
            'form_code' => 'required',
        ]);
        $input = $request->all();
       
        
        $reportViewerId->fill($input)->save();
       
        return redirect()->route('ReportViewer.index')->with('message', 'Update Record Succesfully');
    }

/**
* Remove the specified resource from storage.
*
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
    public function destroy($id)
    {
        DB::table('report_viewer')->where('reportViewerId', $id)->delete();
        return 1;
    }
    
      
    public function ReportViewerDashboard()
    {
        //DB::enableQueryLog();
       $ReportMgmtList = DB::table('module_master')->select('*')->get();
        //dd(DB::getQueryLog());                  
       return view('ReportViewerDashboard',compact('ReportMgmtList'));
    }
}
