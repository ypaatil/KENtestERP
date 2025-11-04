<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\LinePlanMasterModel;
use App\Models\NewJobOpeningDetailModel;
use App\Models\LinePlanDetailModel;
use App\Models\OBMasterModel;
use App\Models\OBDetailModel;
use Illuminate\Http\Request;
use DataTables;
use Session;
use DB;
use App\Traits\EmployeeTrait;

class LinePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      use EmployeeTrait;  
     
     
    public function index(Request $request)
    {
        
        
     $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '310')
        ->first();  
        
        


      if ($request->ajax()) 
        {
            $data=LinePlanMasterModel::select("line_plan_masters.line_plan_id","line_date","usermaster.username",
             "main_style_master_operation.mainstyle_name","station_no","sub_company_master.sub_company_name","line_master.line_name")
             ->leftJoin('usermaster','usermaster.userId','=','line_plan_masters.userId')
            ->leftJoin('main_style_master_operation','main_style_master_operation.mainstyle_id','=','line_plan_masters.mainstyle_id')
              ->join('line_master','line_master.line_id','=','line_plan_masters.dept_id')  
            ->leftJoin('sub_company_master','sub_company_master.erp_sub_company_id','=','line_plan_masters.sub_company_id')
            ->where('line_plan_masters.is_deleted',0);
            
            
     
               if(Session::get('user_type')==1 || Session::get('user_type')==7)
            {
                
               if(isset($request->sub_company_id))
             {
                
                $data->where('line_plan_masters.sub_company_id',$request->sub_company_id);     
                
             } else{
                 
             }
             
              if(isset($request->dept_id))
             {
                
                $data->where('line_plan_masters.dept_id',$request->dept_id);     
                
             } else{
                 
                 
             }     
                
            if(isset($request->mainstyle_id))
             {
                
                $data->where('line_plan_masters.mainstyle_id',$request->mainstyle_id);     
                
             } else{
                 
                 
             }       
                
                $data->where('line_plan_masters.sub_company_id',Session::get('vendorId'));
                
            } else{
                
               if(isset($request->dept_id))
             {
                
                $data->where('line_plan_masters.dept_id',$request->dept_id);     
                
             } else{
                 
                 
             }         
                
            if(isset($request->mainstyle_id))
             {
                
                $data->where('line_plan_masters.mainstyle_id',$request->mainstyle_id);     
                
             } else{
                 
                 
             }         
                
              $data->where('line_plan_masters.sub_company_id',Session::get('vendorId'));
            }
            
              $data->orderBy('line_plan_masters.line_plan_id','DESC');
            

            return Datatables::of($data)
            ->addIndexColumn()
               ->addColumn('action1', function($row)
            {
                $btn = '<a  data-id="'.$row['line_plan_id'].'">'.date('d-m-Y',strtotime($row['line_date'])).'</a>';
                return $btn;
            })   
             ->addColumn('action2', function($row)
            {
                $btn = '<a  data-id="'.$row['line_plan_id'].'" ></a>';
                return $btn;
            }) 
            
            ->addColumn('action3', function($row) use($chekform)
            {
                
                   if($chekform->edit_access==1)
       {
                
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('line_plan.edit', $row['line_plan_id']).'" ><i class="feather feather-edit" data-toggle="tooltip" data-original-title="Edit"></i></a>';
              
        } else{
           
           
                    $btn = '
 <a class="btn btn-primary btn-icon btn-sm">
                                                                <i class="feather feather-lock" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                           ';    
           
       }
                         
                  return $btn;
                
            })
            ->addColumn('action4', function($row) use($chekform)
            {
                
         if($chekform->delete_access==1)
       {    
                
                $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['line_plan_id'].'"  data-route="'.route('line_plan.destroy', $row['line_plan_id']).'"><i class="feather feather-trash-2"></i></a>';
              
                
       } else{
           
                       $btn3 = '
 <a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"><i class="feather feather-lock"></i></a>
                           ';
           
       }
       
         return $btn3;
       
            })
            ->rawColumns(['action1','action2','action3','action4'])
            ->make(true);
        }
        






          $data=DB::table('sub_company_master')->where('delflag',0);
            
            if(Session::get('user_type')==1)
            {
             $data->where('maincompany_id',1); 
            } else{
                
                   $data->where('erp_sub_company_id',Session::get('vendorId'));
                   $data->where('maincompany_id',1); 
                 
            }
            
            $sub_company_list=$data->get();  
           
           $dept_list = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
            $styleList = DB::table('main_style_master_operation')->Select('*')->get();    
        
            return view('Operation.line_plan_master_list',compact('sub_company_list','dept_list','styleList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $emp_code_list = DB::connection('hrms_database')->table('employeemaster')->where('maincompany_id',Session::get('maincompany_id'))->where('delflag',0)->where('egroup_id',42)->get();
        $dept_list = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
        $location_list = DB::connection('hrms_database')->table('maincompany_master')->select('maincompany_id','maincompany_name')->where('delflag',0)->get();
        $qualification_list = DB::connection('hrms_database')->table('qualification_master')->where('delflag',0)->get();
        $branch_list = DB::connection('hrms_database')->table('branch_master')->where('delflag',0)->get();
        $empcategoryList=DB::connection('hrms_database')->table('emp_category_master')->select('emp_cat_id','emp_cat_name')->where('emp_cat_id','!=',1)->where('delflag',0)->get();
        
          
               $datafetch=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')
          ->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[67,71,64,79,106,73,112]);
          
          
         $employeelist=$datafetch->get(); 
         
          
          
          
          
         $operationList=DB::table('ob_details')->select('operation_id','operation_name')->get();  
         
         
        $styleList = DB::table('main_style_master_operation')->Select('*')->get();    
        $groupList = DB::table('group_masters')->Select('*')->get();  
         $machineTypeList = DB::table('machine_type_masters')->Select('*')->get(); 
        
        return view('Operation.line_plan_master',compact('emp_code_list','dept_list','location_list','qualification_list','branch_list','empcategoryList','operationList','styleList','groupList','machineTypeList','employeelist'));

    }
    
        
    
    
     public function line_wise_operator(Request $request)
    {
        
         $lineFetch=LinePlanMasterModel::select('department_master.dept_name','line_plan_masters.line_plan_id','line_plan_masters.dept_id')
         ->join('department_master','department_master.dept_id', '=','line_plan_masters.dept_id')
         ->groupBy('department_master.dept_name')
         ->orderBy('department_master.dept_id')
         ->where('is_deleted',0)->get();
        
         return view('Operation.lineWiseOperator',compact('lineFetch'));

    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
       try {
           
            DB::beginTransaction();
             
          $data = $request->all();
           
           $id = $data['id'] ?? null;
        
            $IODetails = LinePlanMasterModel::updateOrCreate(
                ['line_plan_id'=> $id],
                $data);
                
                
                
                  if($id == null){
             $line_plan_id=LinePlanMasterModel::max('line_plan_id');
            } else{

                $line_plan_id=$id; 
            }
                
                
                
           $operation_id = $request->input('operation_id');

           
            if(count($operation_id)>0)
            {

                 LinePlanDetailModel::where('line_plan_id',$line_plan_id)->delete();
               
                $data1=array();
            for($x=0; $x<count($operation_id); $x++) {
           
            $data1[]=array(
             'line_plan_id'=>$line_plan_id,  
             'sub_company_id'=>$request->sub_company_id,
             'operation_id'=>$request->operation_id[$x],
              'operation_name'=>$request->operation_name[$x],  
             'group_id'=>$request->group_id[$x],    
             'machine_type_id'=>$request->machine_type_id[$x],
              'dept_id'=>$request->dept_id,   
              'sam'=>$request->sam[$x], 
              'required_skill_set'=>$request->required_skill_set[$x],
             'employeeCode'=>$request->employeeCode[$x]
            );
          
            }
            LinePlanDetailModel::insert($data1);
          
        }
           
            $msg = "";
             DB::commit();

            if($id == null){
                $msg = 'Line plan saved successfully';
            } else {
                $msg = 'Line plan updated successfully';
            }

         

         return redirect()->route('line_plan.index')->with('message', $msg);
         
         } catch (\Exception $e) {
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
     DB::rollBack();
  
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
 
    }
    }
    
    
    
    public function get_line_table(Request $request){
        
        
        $masterFetch=LinePlanMasterModel::where('line_plan_id',$request->line_plan_id)->first();
        
        
        
      $result=LinePlanDetailModel::
          select('group_masters.group_name','machine_type_masters.machine_type_name','employeemaster.fullName','line_plan_detail.operation_id',
          'ob_masters.operation_name','line_plan_detail.sam','line_plan_detail.required_skill_set')
          ->join('group_masters','group_masters.group_id','=','line_plan_detail.group_id')
            ->join('machine_type_masters','machine_type_masters.machine_type_id','=','line_plan_detail.machine_type_id')
            ->join('employeemaster','employeemaster.employeeCode','=','line_plan_detail.employeeCode')   
            ->join('ob_masters','ob_masters.operation_id','=','line_plan_detail.operation_id')
            
          ->where("line_plan_id",$request->line_plan_id)->get();
          
          
        
         $html="";
        
        if(count($result) > 0)
        {
       
        
$html.='        
    <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Operation ID</th>
        <th scope="col">Operation Name</th>
        <th scope="col">Group</th>
        <th scope="col">Machine Type</th>
        <th scope="col">SAM</th> 
        <th scope="col">Required Skill Set</th>    
        <th scope="col">Operator</th>       
      </tr>
    </thead>
    <tbody>';
    
    foreach($result as $row)
    {
    $html.='    <tr>
        <td>'.$row->operation_id.'</td>
        <td>'.$row->operation_name.'</td>
        <td>'.$row->group_name.'</td>
        <td>'.$row->machine_type_name.'</td>
        <td>'.$row->sam.'</td>
        <td>'.$row->required_skill_set.'</td>
         <td>'.$row->fullName.'</td>
      </tr>';
    }
    
   $html.='</tbody>
  </table> ';   
        } else{
            
            
            $html.="No record found...!!";
            
        }
  
  return response()->json($html);
            
        
        
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LinePlanMasterModel  $LinePlanMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(LinePlanMasterModel $LinePlanMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LinePlanMasterModel  $LinePlanMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         
           $emp_code_list = DB::table('employeemaster_operation')->where('maincompany_id',Session::get('maincompany_id'))->where('delflag',0)->where('egroup_id',42)->get();
       $dept_list = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
        $location_list = DB::connection('hrms_database')->table('maincompany_master')->select('maincompany_id','maincompany_name')->where('delflag',0)->get();
        $qualification_list = DB::connection('hrms_database')->table('qualification_master')->where('delflag',0)->get();
        $branch_list = DB::connection('hrms_database')->table('branch_master')->where('delflag',0)->get();
        $empcategoryList=DB::connection('hrms_database')->table('emp_category_master')->select('emp_cat_id','emp_cat_name')->where('emp_cat_id','!=',1)->where('delflag',0)->get();
        
 
         
         					
        $employeeList = DB::table('employeemaster_operation')
        ->select('employeemaster_operation.employeeCode',DB::raw('employeemaster_operation.fullName as fullName'))
        ->where('emp_cat_id',3)->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[67,71,64,79,106,73,112])->where('employeemaster_operation.delflag','=', '0')->get();   
         
            $employeeMap=[];
          
      foreach ($employeeList as $rowEmp) {
         $employeeMap[] = [
        'employeeCode' => $rowEmp->employeeCode,
         'fullName' => $rowEmp->fullName
        ];
        
        }
         
         
         
          
          //$operationList=DB::table('ob_masters')->select('operation_id','operation_name')->get(); 
          
          
         
           $operationList=DB::table('ob_details')->select('operation_id','operation_name')->get();  
          
           $operationListMap=[];
           
         foreach ($operationList as $rowOP) {
         $operationListMap[] = [
        'operation_id' => $rowOP->operation_id,
         'operation_name' => $rowOP->operation_name
          ];
        
         }  
          
          
           
         $styleList = DB::table('main_style_master_operation')->Select('*')->get();    
         $groupList = DB::table('group_masters')->Select('*')->get();  
         $machineTypeList = DB::table('machine_type_masters')->Select('*')->get(); 
         
           $lineFetch =LinePlanMasterModel::find($id);
           
            $lineFetchDetail =LinePlanDetailModel::where("line_plan_id",$id)->get();
         
        
        return view('Operation.line_plan_master',compact('emp_code_list','dept_list','location_list','operationListMap','qualification_list','branch_list','empcategoryList','operationList','styleList','groupList','machineTypeList','employeeMap','lineFetch','lineFetchDetail'));

   
    }
    
    
        public function get_operation_ids(Request $request)
    {
        
        return response()->json(['html' => $this->get_operation_list($request->mainstyle_id)]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LinePlanMasterModel  $LinePlanMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
   
   


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LinePlanMasterModel  $LinePlanMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
       // LinePlanMasterModel::where('line_plan_id', $id)->update(array('is_deleted' => 1));
        
        LinePlanMasterModel::where('line_plan_id',$id)->delete();
        LinePlanDetailModel::where('line_plan_id',$id)->delete(); 
        
        
        return redirect()->route('line_plan.index')->with('message', 'Delete Record Succesfully');


    }
    
    public function GetEmpDetailFromEmpCode(Request $request)
    {
        $data=EmployeeModel::select("employeemaster.employeeCode","employeemaster.fullName", "employeemaster.dept_id","employeemaster.sub_company_id","sub_company_master.sub_company_name","department_master.dept_name")
        ->leftJoin('department_master','department_master.dept_id', '=','employeemaster.dept_id')
        ->leftJoin('sub_company_master','sub_company_master.sub_company_id', '=','employeemaster.sub_company_id')
        ->where('employeemaster.employeeCode','=', $request->employeeCode)
        ->first(); 
        return response()->json(['employeeCode' => $data->employeeCode,'fullName' => $data->fullName,'dept_id' => $data->dept_id,'sub_company_id' => $data->sub_company_id,'sub_company_name' => $data->sub_company_name,'dept_name' => $data->dept_name]);
    }    
    
    public function GetNewJobOpeningReport(Request $request)
    {
        $employeelist=DB::table('employeemaster')->select('employeeId','employeeCode','fullName')->where('employee_status_id','!=',3)->get();
        
        return view('GetNewJobOpeningReport',compact('employeelist'));
        
    } 
    
    public function rptNewJobOpeningDailyReport(Request $request)
    {
        $DailyReportData = DB::table('new_job_opening_form')->select('new_job_opening_form.*','hiring.fullName as hiring_manager_name',
                    'reporting.fullName as reporting_manager_name',"locationMaster.location","department_master.dept_name","qualification_master.qualification")
                 ->leftJoin('employeemaster as hiring','hiring.employeeCode', '=','new_job_opening_form.hiring_manager')
                 ->leftJoin('employeemaster as reporting','reporting.employeeCode', '=','new_job_opening_form.reporting_manager')
                 ->leftJoin('department_master','department_master.dept_id', '=','new_job_opening_form.dept_id')
                 ->leftJoin('locationMaster','locationMaster.locationId', '=','new_job_opening_form.locationId')
                 ->leftJoin('qualification_master','qualification_master.qualificationId', '=','new_job_opening_form.qualificationId')
                 ->where('new_job_opening_form.delflag','=', '0')  
                 ->whereBetween('job_opening_Date',[$request->fromDate,$request->toDate]) 
                 ->get(); 
      

        return view('rptNewJobOpeningDailyReport',compact('DailyReportData'));
    }
    
    public function GetNewJobOpeningMonthlySummaryReport(Request $request)
    {  
        $positionList = DB::table('new_job_opening_form')->select('NewJobOpeningFormId','position_name')
                 ->where('new_job_opening_form.delflag','=', '0')  
                 ->get();  
        $dept_list = DB::table('department_master')->where('delflag',0)->get();
        $location_list = DB::table('locationMaster')->where('delflag',0)->get();

        return view('GetNewJobOpeningMonthlySummaryReport',compact('positionList','dept_list','location_list'));
        
    } 
    
    
    public function get_operation_detail(Request $request)
    {

     $OBDetail = OBDetailModel::select("operation_name","group_id","machine_type_id","sam","required_skill_set")->where('operation_id',$request->operation_id)->where('mainstyle_id',$request->mainstyle_id)->orderBy('created_at', 'desc')->first();
    
    // $OBDetail = OBMasterModel::select("operation_name","group_id","machine_type_id","sam","required_skill_set")->where('operation_id',$request->operation_id)->where('mainstyle_id',$request->mainstyle_id)->orderBy('created_at', 'desc')->first();
        
    return response()->json($OBDetail);
    }
    
    
    public function get_selected_operator(Request $request)
    {
        
     $employeelist=DB::table('employeemaster')->select('employeeId','employeeCode','fullName')->whereNotIn('employee_status_id',[3,4])->where('egroup_id',71)->get(); 
     $statuslist=DB::table('opening_status_masters')->select('opening_status_id','opening_status')->get();    
        
      $html='';
    
           $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                <thead>
                    <tr>
                        <th>Sr No</th>
                        <th>Operator Name</th>
                    </tr>
                </thead>
            <tbody>';
    $no=1;
    
    
     
    for($i=0;$i<$request->no_of_station;$i++) 
    {
       
        $html .='<tr>';
            
        $html .='<td><input type="text" name="id[]" value="'.$no.'" style="width:50px;"/></td>
                <td>
                    <select name="employeeCode[]" class="form-control" id="employeeCode">
                <option value="">--- Select Employee ---</option>';
        foreach($employeelist as  $rowemp)
        {
            $html.='<option value="'.$rowemp->employeeCode.'"';
            $html.='>'.$rowemp->fullName.'</option>';
        }
        
        $html.='</select></td>';
        
         
            $html .='</tr>';
        
    
        $no=$no+1;


    }
        $html .='<input type="number" value="'.$no.'" name="cnt" id="cnt" readonly="" hidden="true"  /></table>';
        return response()->json(['html' => $html]);    
        
        
    }
    
    
    public function rptNewJobOpeningMonthlySummaryReport(Request $request)
    { 
        if($request->dept_id > 0 )
        {
            $dept = ' AND dept_id='.$request->dept_id;  
        }
        else
        {    
            $dept = '';
        }
        if($request->locationId > 0)
        {
            $loc = ' AND locationId='.$request->locationId;  
        }
        else
        {
            $loc = '';
        } 
        if($request->NewJobOpeningFormId > 0)
        {
            $NewJobOpeningFormId = ' AND NewJobOpeningFormId='.$request->NewJobOpeningFormId;  
        }
        else
        {
             $NewJobOpeningFormId = '';
        }
        
        $jobOpeningList = DB::select('SELECT * FROM new_job_opening_form WHERE 1 '.$dept.$loc.$NewJobOpeningFormId.' GROUP BY position_name');
        $months = explode(" ","Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec");
     
        return view('rptNewJobOpeningMonthlySummaryReport',compact('jobOpeningList','months'));

    } 
    
}
