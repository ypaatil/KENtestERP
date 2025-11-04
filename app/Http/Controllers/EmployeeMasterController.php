<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel; 
use App\Models\EmployeeWiseOperationModel; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class EmployeeMasterController extends Controller
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
        ->where('form_id', '249')
        ->first();  
        
  
        
           $sub_CompFetch=DB::table('sub_company_master')->select('sub_company_id')->where('erp_sub_company_id',Session::get('vendorId'))->first();
       
        
             $data = EmployeeModel::
             join('company_master','company_master.company_id','=','employeemaster_operation.maincompany_id')
             ->join('sub_company_master','sub_company_master.sub_company_id','=','employeemaster_operation.sub_company_id')
            ->where('employeemaster_operation.delflag','=', '0')->where('emp_cat_id',3);
        
        if(Session::get('user_type')!=1)
        {
        
        $data->where('employeemaster_operation.sub_company_id','=', $sub_CompFetch->sub_company_id);    
        
        } else{
        
        
        }
        $employeeList=$data->get(['employeemaster_operation.employeeCode','employeemaster_operation.fullName','employeemaster_operation.employeeId','company_master.company_name','sub_company_master.sub_company_name']);
  
  
        return view('EmployeeMasterList', compact('employeeList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
     
             $data1 = DB::table('sub_company_master')->select()->where('delflag','=', '0');
             
            if(Session::get('user_type')!=1)
           {
             
             $data1->where('erp_sub_company_id',Session::get('vendorId'));
           } else{
               
               
           }
             $subCompanyList=$data1->get();
             
         
              $data2= DB::table('company_master')->select()->where('delflag','=', '0');
             
             
              if(Session::get('user_type')!=1)
           {
             $data2->where('company_id',$subCompanyList[0]->maincompany_id);
             
           } else{
               
               
           }
             
             $companyList=$data2->get();
         
         
         $OperationNameList = DB::table('operation_name_master')->select('operation_name_master.operationNameId', 'operation_name_master.operation_name')->get();
         
         return view('EmployeeMaster',compact('companyList','subCompanyList','OperationNameList'));
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
            'employeeCode'=> 'required',
            'fullName'=> 'required'
        ]);
        
               try {  
           
            DB::beginTransaction();
             
          $data = $request->all();
           
           $id = $data['id'] ?? null;
        
            $IODetails = EmployeeModel::updateOrCreate(
                ['employeeId'=> $id],
                $data);

                
               DB::commit();
            $msg = "";

            if($id == null){
                $msg = 'Employee saved successfully';
            } else {
                $msg = 'Employee  updated successfully';
            }

         
         return redirect()->route('Employee.index')->with('message', $msg);
         
         } catch (\Exception $e) {
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
  
    DB::rollBack();
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
 
    }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeModel  $EmployeeModel
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeModel $EmployeeModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeModel  $EmployeeModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $EmployeeList = EmployeeModel::find($id);
        $companyList = DB::table('company_master')->select()->where('delflag','=', '0')->get();
        $subCompanyList = DB::table('sub_company_master')->select()->where('delflag','=', '0')->get();
            
        return view('EmployeeMaster', compact('EmployeeList','companyList','subCompanyList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeModel  $EmployeeModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $EmployeeList = EmployeeModel::findOrFail($id);

        $this->validate($request, [
            'employeeCode'=> 'required',
            'employeeName'=> 'required'
        ]);

        $input = $request->all();

        $EmployeeList->fill($input)->save();
        DB::table('employee_wise_operations')->where('employeeCode', $request->employeeCode)->delete();  
        foreach($request->operationNameId as $row)
        {
            $data=array
            (
                'employeeCode'=>$request->employeeCode, 
                'operationNameId'=>$row  
             );
         
            EmployeeWiseOperationModel::insert($data);
        }

        return redirect()->route('Employee.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeModel  $EmployeeModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeModel::where('employeeId', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
