<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\DailyProductionEntryOperationModel;
use App\Models\NewJobOpeningDetailModel;
use App\Models\DailyProductionEntryDetailOperationModel;
use App\Models\OBMasterModel;
use Illuminate\Http\Request;
use DataTables;
use Session;
use DB;
use App\Traits\EmployeeTrait;
use DatePeriod;
use DateTime;
use DateInterval;

class DailyProductionEntry extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      use EmployeeTrait;  
     
     
    public function index(Request $request)
    {
        
        
      if($request->ajax()) 
        {
            $data=DailyProductionEntryOperationModel::select("daily_production_entry_masters.daily_pr_entry_id",
            "daily_production_entry_masters.daily_pr_date","usermaster.username","line_master.line_name","group_masters.group_name",
            'sub_company_master.sub_company_name','daily_production_entry_masters.overall_sam','daily_production_entry_masters.overall_output',
            'daily_production_entry_masters.total_present','main_style_master_operation.mainstyle_name')
            ->leftJoin('usermaster','usermaster.userId','=','daily_production_entry_masters.userId')
            ->join('line_master','line_master.line_id','=','daily_production_entry_masters.dept_id')  
            ->leftJoin('group_masters','group_masters.group_id','=','daily_production_entry_masters.group_id')   
            ->leftJoin('sub_company_master','sub_company_master.erp_sub_company_id', '=', 'daily_production_entry_masters.sub_company_id')
            ->join('main_style_master_operation','main_style_master_operation.mainstyle_id', '=', 'daily_production_entry_masters.mainstyle_id');  
            
             $data->where('daily_production_entry_masters.is_deleted',0);
                  
                  
            if(Session::get('user_type')==1 || Session::get('user_type')==7)
            {
                
              if(isset($request->daily_production_date))
             {
                
                $data->where('daily_production_entry_masters.daily_pr_date',$request->daily_production_date);     
                
             } else{
                 
                 
             }
                 if(isset($request->sub_company_id))
             {
                
                $data->where('daily_production_entry_masters.sub_company_id',$request->sub_company_id);     
                
             } else{
                 
             }
             
              if(isset($request->dept_id))
             {
                
                $data->where('daily_production_entry_masters.dept_id',$request->dept_id);     
                
             } else{
                 
                 
             }
                
        
              if(isset($request->group_id))
             {
                
                $data->where('daily_production_entry_masters.group_id',$request->group_id);     
                
             } else{
                 
                 
             }        
                
                
       
            } else{
                
                
              if(isset($request->daily_production_date))
             {
                
                $data->where('daily_production_entry_masters.daily_pr_date',$request->daily_production_date);     
                
             } else{
                 
                 
             }
       
             
              if(isset($request->dept_id))
             {
                
                $data->where('daily_production_entry_masters.dept_id',$request->dept_id);     
                
             } else{
                 
                 
             }
                
        
              if(isset($request->group_id))
             {
                
                $data->where('daily_production_entry_masters.group_id',$request->group_id);     
                
             } else{
                 
                 
             }        
                     
                
              $data->where('daily_production_entry_masters.sub_company_id',Session::get('vendorId'));    
                
            }
           
           
           
            $data->orderBy('daily_production_entry_masters.daily_pr_date','desc');
            
            return Datatables::of($data)
            ->addIndexColumn()
               ->addColumn('action1', function($row)
            {
                $btn = '<a data-target="#exampleModal" data-id="'.$row['daily_pr_entry_id'].'" class="OpenModel">'.date('d-m-Y',strtotime($row['daily_pr_date'])).'</a>';
                return $btn;
            })   
            
            ->addColumn('action2', function($row)
            {
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('daily_production_entry.edit', $row['daily_pr_entry_id']).'" ><i class="feather feather-edit" data-toggle="tooltip" data-original-title="Edit"></i></a>';
                return $btn;
            })
            ->addColumn('action3', function($row)
            {
                
                
                  if(Session::get('user_type')==1 || Session::get('user_type')==7)
            {
                
                $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['daily_pr_entry_id'].'"  data-route="'.route('daily_production_entry.destroy', $row['daily_pr_entry_id']).'"><i class="feather feather-trash-2"></i></a>';
                return $btn3;
                
            } else{
                
               $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete" ><i class="feather feather-lock-2"></i></a>';
                return $btn3;   
                
            }
                
                
            })
            ->rawColumns(['action1','action2','action3'])
            ->make(true);
        }
          
          
          
          
        
            $data=DB::table('sub_company_master')->where('delflag',0);
            
       
            $sub_company_list=$data->get();  
            
            
               $dept_list = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
               $groupList = DB::table('group_masters')->Select('*')->get();  
        
            return view('Operation.daily_production_entry_list',compact('sub_company_list','dept_list','groupList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $emp_code_list = DB::table('employeemaster_operation')->where('maincompany_id',Session::get('maincompany_id'))->where('delflag',0)->where('egroup_id',42)->get();
       $dept_list = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
        $location_list = DB::connection('hrms_database')->table('maincompany_master')->select('maincompany_id','maincompany_name')->where('delflag',0)->get();
        $qualification_list = DB::connection('hrms_database')->table('qualification_master')->where('delflag',0)->get();
        $branch_list = DB::table('branch_master')->where('delflag',0)->get();
        $empcategoryList=DB::connection('hrms_database')->table('emp_category_master')->select('emp_cat_id','emp_cat_name')->where('emp_cat_id','!=',1)->where('delflag',0)->get();
        
        $datafetch=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')
          ->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[67,71,64,79,106,73]);
          
          
         $employeelist=$datafetch->get(); 
          
          
       // $operationList=DB::table('ob_masters')->select('operation_id','operation_name')->get();  
        
         $operationList=DB::table('ob_details')->select('operation_id','operation_name')->get(); 
        
        
        
        $styleList = DB::table('main_style_master_operation')->Select('*')->get();    
        $groupList = DB::table('group_masters')->Select('*')->get();  
        $machineTypeList = DB::table('machine_type_masters')->Select('*')->get(); 
         
        
        return view('Operation.daily_production_entry',compact('emp_code_list','dept_list','location_list','qualification_list','branch_list','empcategoryList','operationList','styleList','groupList','machineTypeList','employeelist'));

    }
    
    

    
    
        
        public function get_routes(Request $request)
        {
        
        $type_id = $request->type_id;
        
        $html="";
        
        switch ($type_id) {
        case "1":
        echo $html.="https://kenerp.com/unit_wise_efficiency"; 
        break;
        case "2":
        echo $html.="https://kenerp.com/unit_wise_efficiency_pcs";
        
        }  
        
        }
        
    
    
    
        public function daily_production_create()
    {
        
        $emp_code_list =DB::connection('hrms_database')->table('employeemaster_operation')->where('maincompany_id',Session::get('maincompany_id'))->where('delflag',0)->where('egroup_id',42)->get();
        $dept_list =  DB::connection('hrms_database')->table('department_master')->where('delflag',0)->whereIn('dept_id',[65,66,67,68])->get();
        $location_list = DB::connection('hrms_database')->table('maincompany_master')->select('maincompany_id','maincompany_name')->where('delflag',0)->get();
        $qualification_list = DB::connection('hrms_database')->table('qualification_master')->where('delflag',0)->get();
        $branch_list = DB::table('branch_master')->where('delflag',0)->get();
        $empcategoryList=DB::connection('hrms_database')->table('emp_category_master')->select('emp_cat_id','emp_cat_name')->where('emp_cat_id','!=',1)->where('delflag',0)->get();
        
          $employeelist=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[67,71,64,106])->get(); 
         $operationList=DB::table('ob_masters')->select('operation_id','operation_name')->get();  
        $styleList = DB::table('main_style_master_operation')->Select('*')->get();    
        $groupList = DB::table('group_masters')->Select('*')->get();  
         $machineTypeList = DB::table('machine_type_masters')->Select('*')->get(); 
         

         
         
        
        return view('daily_production_entry_new',compact('emp_code_list','dept_list','location_list','qualification_list','branch_list','empcategoryList','operationList','styleList','groupList','machineTypeList','employeelist'));

    }
    
     public function generate_attendance_nandani(Request $request)
    {
        
        $monthNo=date("m",strtotime('2024-09-01'));
        $Year=date("y",strtotime('2024-09-30'));
        
        
        
        $empArray=[140003,140002,140013,140015,140012,140007,140009,140014,140054,140021,140020,140006,140034,140060,110281,140067,140001,140036,140004,140071,140072,140062,110294];

        
        for($i=1;$i<=30;$i++)
        {
            
          $dates=date("Y-m-d",mktime(0, 0, 0,$monthNo,$i,$Year));
          
          for($j=0;$j<count($empArray);$j++)
         {
       
            //  DB::select("INSERT INTO `attendancelogs`(`AttendanceDate`, `EmployeeCode`, `employeeName`, `Company`, 
            //  `Department`, `Category`, `Degination`, `Grade`, `Team`, `Shift`, `InTime`, `OutTime`, `Duration`, `LateBy`, `EarlyBy`, `Status`,
            //  `Punch_Records`, `OverTime`, `longitude`, `lattitude`, `address`, `todays_click`, `created_at`, `updated_at`, `enteryDate`, `startKm`, 
            //  `startKmImage`, `endKm`, `endKmImage`, `attendanceFlag`, `locationId`, `workTypeId`, `modeOfTravellingId`, `remark`, `OutTimeActual`,
            //  `day_close_lattitude`, `day_close_longitude`, `complianceOutTime`, `complianceShift`) VALUES ('".$dates."','".$empArray[$j]."',
            //  '','','','','','','','','09:00:00','17:40:00','08:40',
            //  '','','14','','','','','','','',
            //  '','".date('Y-m-d')."','','','','','3','',
            //  '','','','','','','','')");
             
         } 
        
        }
        
    }
    
    
     public function check_exists_production(Request $request)
    {
       
             $isExist=DailyProductionEntryDetailOperationModel::
             select('group_masters.group_name')
            ->join('daily_production_entry_masters','daily_production_entry_masters.daily_pr_entry_id','=','daily_production_entry_details_operation.daily_pr_entry_id')    
          ->join('group_masters','group_masters.group_id','=','daily_production_entry_masters.group_id')
          ->where(["daily_production_entry_details_operation.dept_id"=>$request->dept_id,
          "daily_production_entry_details_operation.daily_pr_date"=>$request->daily_pr_date,
          "daily_production_entry_details_operation.operation_id"=>$request->operation_id,
          "daily_production_entry_details_operation.employeeCode"=>$request->employeeCode,
          "daily_production_entry_details_operation.sub_company_id"=>Session::get('sub_company_id'),
          "daily_production_entry_details_operation.pieces"=>$request->pieces])->get();
         
         if(count($isExist) > 0)
         {
             $flag=1;
             $group_name=$isExist[0]->group_name ?? 0;
         } else{
             
               $flag=0;
               $group_name="";
         }
        
         return response()->json(["flag"=>$flag,"group_name"=>$group_name]);
         

    }
       public function check_exist_record(Request $request)
    {
       
            $isExist=DailyProductionEntryOperationModel::
            select('*')
            ->where(["daily_production_entry_masters.dept_id"=>$request->dept_id,
            "daily_production_entry_masters.daily_pr_date"=>$request->daily_pr_date,
            "daily_production_entry_masters.mainstyle_id"=>$request->mainstyle_id,  
            "daily_production_entry_masters.group_id"=>$request->group_id,   
            "daily_production_entry_masters.sub_company_id"=>Session::get('vendorId')])->count();
         
         if($isExist > 0)
         {
             $flag=1;
           
         } else{
             
               $flag=0;
              
         }
        
         return response()->json($flag);
         

    }
    
    
    
    
     public function line_wise_operator(Request $request)
    {
        
         $lineFetch=DailyProductionEntryOperationModel::select('department_master.dept_name','line_plan_masters.line_plan_id')
         ->join('department_master','department_master.dept_id', '=','line_plan_masters.dept_id')
         ->groupBy('department_master.dept_name')
         ->orderBy('department_master.dept_id')
         ->where('is_deleted',0)->get();
        
         return view('lineWiseOperator',compact('lineFetch'));

    }
    
    
    
        public function get_unitwise_efficiency(Request $request)
     {
        
      $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
      $employeeList=DB::table('employeemaster_operation')->select('employeeCode','fullName')->where('egroup_id',71)->whereNotIn('employee_status_id',[3,4])->get();
   
      return view('Operation.unit_wise_efficiency_report',compact('deptlist','employeeList'));
    }
    
           public function get_average_efficiency_by_operator(Request $request)
     {
        
      $deptlist=DB::table('department_master')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
      $employeeList=DB::table('employeemaster_operation')->select('employeeCode','fullName')->where('egroup_id',71)->whereNotIn('employee_status_id',[3,4])->get();
   
      return view('get_average_efficiency_by_operator',compact('deptlist','employeeList'));
    }
    
      public function get_unitwise_pl(Request $request)
     {
        
      $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
      $employeeList=DB::table('employeemaster_operation')->select('employeeCode','fullName')->where('egroup_id',71)->whereNotIn('employee_status_id',[3,4])->get();
   
      return view('Operation.get_unit_wise_pl',compact('deptlist','employeeList'));
    }
    
          public function get_unitwise_mis_chart(Request $request)
     {
        
      $deptlist=DB::table('department_master')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
      $employeeList=DB::table('employeemaster_operation')->select('employeeCode','fullName')->where('egroup_id',71)->whereNotIn('employee_status_id',[3,4])->get();
   
      return view('get_mis_chart_by_dates',compact('deptlist','employeeList'));
    }
    
    
        public function get_unitwise_pl_register(Request $request)
     {
        
      $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
      $employeeList=DB::table('employeemaster_operation')->select('employeeCode','fullName')->where('egroup_id',71)->whereNotIn('employee_status_id',[3,4])->get();
   
      return view('Operation.get_unit_wise_pl_register',compact('deptlist','employeeList'));
    }
    
    
      public function get_linewise_efficiency(Request $request)
     {
        
         return view('Operation.line_wise_efficiency_report');
    }
    
     public function get_linewise_efficiency_weekly(Request $request)
     {
     

         return view('Operation.get_line_wise_efficiency_weekly');
    }
    
        public function get_linewise_efficiency_yearly(Request $request)
       {
        
     
         return view('Operation.get_line_wise_efficiency_yearly');
         
      }
      
         public function get_all_unit_efficiency_yearly(Request $request)
       {
        
       $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();

     
         return view('Operation.get_all_unit_wise_efficiency_yearly',compact('deptlist'));
         
      }
      
          public function get_unit_wise_mis_chart_yearly(Request $request)
       {
        
        $deptlist=DB::table('department_master')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();

     
         return view('get_unit_wise_mis_chart_yearly',compact('deptlist'));
         
      }
    
    
    
    public function get_daily_production(Request $request)
    {
        
       $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
       
   
         return view('Operation.get_daily_production',compact('deptlist'));
    }
    
        public function get_top_n_bottom_n(Request $request)
    {
        
         $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
    
   
         return view('Operation.get_top_n_bottom_n',compact('deptlist'));
    }
    
    
    
        public function unit_wise_efficiency(Request $request)
    {
        
       
        
       $lineList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
       
            
        // $period = $this->getBetweenDates($request->fromDate,$request->toDate);
         
          $fdate=$request->fromDate;
          $tdate=$request->toDate;
          $sub_company_id=$request->sub_company_id;
         
         //DB::enableQueryLog();
         $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$request->dept_id)
         ->where('daily_production_entry_details_operation.sub_company_id',$sub_company_id)
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
         
         //dd(DB::getQueryLog());
         
         
         $employeeCode=$request->employeeCode;
         
         
        $dept_id= $request->dept_id;
         
         
        $lineName=DB::table('line_master')->select('line_id','line_name')->where('line_id',$request->dept_id)->first();
        
        $dept_name=$lineName->line_name;
       


       
       
        $deptList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,68,69,70])->paginate(1);
       
       
   
         return view('Operation.show_efficiency_report',compact('lineList','operatorList','dept_name','dept_id','fdate','tdate','deptList','employeeCode','sub_company_id'));
    }
    
    
    
        
        public function unit_wise_efficiency_pcs(Request $request)
    {
        
       
        
       $lineList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
       
            
        // $period = $this->getBetweenDates($request->fromDate,$request->toDate);
         
          $fdate=$request->fromDate;
          $tdate=$request->toDate;
          $sub_company_id=$request->sub_company_id;
         
         //DB::enableQueryLog();
         $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$request->dept_id)
         ->where('daily_production_entry_details_operation.sub_company_id',$sub_company_id)
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
         
         //dd(DB::getQueryLog());
         
         
         $employeeCode=$request->employeeCode;
         
         
        $dept_id= $request->dept_id;
         
         
        $lineName=DB::table('line_master')->select('line_id','line_name')->where('line_id',$request->dept_id)->first();
        
        $dept_name=$lineName->line_name;
       

       
       
        $deptList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,68,69,70])->paginate(1);
       
       
   
         return view('Operation.show_efficiency_pc_report',compact('lineList','operatorList','dept_name','dept_id','fdate','tdate','deptList','employeeCode','sub_company_id'));
    }
    
    
    
    
    
    
    
            public function unit_wise_pl(Request $request)
       {
        
        
       $lineList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
       
            
        // $period = $this->getBetweenDates($request->fromDate,$request->toDate);
         
          $fdate=$request->fromDate;
          $tdate=$request->toDate;
          $sub_company_id=$request->sub_company_id; 
         
         //DB::enableQueryLog();
         $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName','employeemaster_operation.misRate')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$request->dept_id)
         ->where('daily_production_entry_details_operation.sub_company_id',$sub_company_id)  
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
         
         //dd(DB::getQueryLog());
         
         
         $employeeCode=$request->employeeCode;
         
         
        $dept_id= $request->dept_id;
         
         
        $lineName=DB::table('department_master_operation')->select('dept_id','dept_name')->where('dept_id',$request->dept_id)->first();
        
       $dept_name=$lineName->dept_name;
       

       
       
        $deptList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,68,69,70])->paginate(1);
       
       
   
         return view('Operation.show_unit_wise_pl',compact('lineList','operatorList','dept_name','dept_id','fdate','tdate','deptList','employeeCode','sub_company_id'));
    }
    
    
    
        public function unit_wise_mis_chart(Request $request)
       {
        
        
       
            
        // $period = $this->getBetweenDates($request->fromDate,$request->toDate);
         
          $fdate=$request->fromDate;
          $tdate=$request->toDate;
  
         
         //dd(DB::getQueryLog());
         
         
         $employeeCode=$request->employeeCode;
         
         
        $sub_company_id= $request->sub_company_id;
  
   
         return view('show_mis_chart_by_dates',compact('sub_company_id','fdate','tdate','employeeCode'));
    }
    
    
    
    
        public function unit_wise_pl_register(Request $request)
       {
        
        
       $lineList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
       
            
        // $period = $this->getBetweenDates($request->fromDate,$request->toDate);
         
          $fdate=$request->fromDate;
          $tdate=$request->toDate;
          $sub_company_id=$request->sub_company_id;
         
         //DB::enableQueryLog();
         $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$request->dept_id)
        ->where('daily_production_entry_details_operation.sub_company_id',$sub_company_id) 
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
         
         //dd(DB::getQueryLog());
         
         
         $employeeCode=$request->employeeCode;
         
         
        $dept_id= $request->dept_id;
         
         
        $lineName=DB::table('department_master_operation')->select('dept_id','dept_name')->where('dept_id',$request->dept_id)->first();
        
       $dept_name=$lineName->dept_name;
       

       
       
        $deptList=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,68,69,70])->paginate(1);
       
       
   
         return view('Operation.show_unit_wise_pl_register',compact('lineList','operatorList','dept_name','dept_id','fdate','tdate','deptList','employeeCode','sub_company_id'));
    }
    
    
    
     public function load_efficiency_by_unit(Request $request)
    {
        
       
        
         $period = $this->getBetweenDates($request->fromDate,$request->toDate);
   
 
         $output="";  
         
         $empArrayByDept=[];
      
      foreach($request->dept_id as $deptArray)
      {
            // DB::enableQueryLog();
            $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$deptArray)
         ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
        //dd(DB::getQueryLog());
        
        $deptFetch=DB::table('line_master')->select('line_id','line_name')->where('line_id',$deptArray)->first();
        
        $subFetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();

    $output.='
         <center><div class="row "> <span style="font-weight:bold">'.$subFetch->sub_company_name.'</span></div> </center><br>
       <center><div class="row "> <span style="font-weight:bold">'.$deptFetch->line_name.'</span></div> </center>
    <div class="outer-wrapper">
    <div class="table-wrapper">

    <table id="rptsum'.$deptArray.'">
        <thead>
            <th onclick="sortTable(0)">Operator </th>';
            $nosAvg=0; $nosAvgMTD=0; 
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d',strtotime($date));
                  
                    $output.='<th onclick="sortTable($index + 1)">'.$dayName.'</th>';
                    
                    $nosAvg=($index + 2); $nosAvgMTD=($index + 3); 
              }
                 $output.='<th onclick="sortTable('.$nosAvg.')">AVG</th> 
                 <th onclick="sortTable('.$nosAvgMTD.')">MTD</th> 
        </thead>
        <tbody>';
          
            
               
                   $sumEfficiency = 0;
                $countEntries = 0; 
                $overall_avg=0;
               $empArray=[];
               
               
               
             
                  
     foreach($operatorList as $rowOperator)   
     {
         
                $fromDateMonth=date('Y-m-01',strtotime($request->fromDate));
                $toDateMonth=date('Y-m-t',strtotime($fromDateMonth));
         
        $dailyAverages = DB::table('daily_production_entry_details_operation')
        ->select(DB::raw("ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (CASE WHEN is_half_day = 1 THEN 2.4 ELSE 4.8 END),2) as daily_avg"))
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')
            ->whereColumn('ob_details.mainstyle_id', '=', 'daily_production_entry_details_operation.mainstyle_id');
            })    
        ->where('dept_id', $deptArray)
           ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
        ->whereBetween('daily_pr_date', [$fromDateMonth,$toDateMonth])
        ->where('employeeCode', $rowOperator->employeeCode)
        ->where('ob_details.sam', '>', 0)
       ->groupBy('daily_production_entry_details_operation.daily_pr_date',
       'daily_production_entry_details_operation.employeeCode','daily_production_entry_details_operation.operation_id')
        ->get();
        
        // Calculate the overall average
        $overallAvg = $dailyAverages->avg('daily_avg');
         
         
            $output.='<tr class="operator-row">
                
                  <td style="border:1px solid black fillter">'.$rowOperator->fullName.'</td>';
                
              foreach($period  as $index => $date)   
              {
            
             
                $fromDate=date('Y-m-01',strtotime($date));
                $toDate=date('Y-m-t',strtotime($fromDate));
             
          $effDetail=DB::table('daily_production_entry_details_operation')
          ->select(DB::raw("ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (CASE WHEN is_half_day = 1 THEN 2.4 ELSE 4.8 END),2) as efficiency
          "))
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')
            ->whereColumn('ob_details.mainstyle_id', '=', 'daily_production_entry_details_operation.mainstyle_id');
            })
         ->where('daily_production_entry_details_operation.daily_pr_date',$date)
         ->where('daily_production_entry_details_operation.employeeCode',$rowOperator->employeeCode)
         ->where('daily_production_entry_details_operation.dept_id',$deptArray) 
         ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
          ->groupBy('daily_production_entry_details_operation.daily_pr_date',
          'daily_production_entry_details_operation.employeeCode','daily_production_entry_details_operation.operation_id')
         ->get();
         
         
              
              if(isset($effDetail[0]->efficiency) ?? 0!=0)
              {
                $output.='<td class="amount" style="border:1px solid black;text-align:right !important" onClick="get_operation_detail('.$rowOperator->employeeCode.',\'' .$date. '\','.$deptArray.')">'.number_format((float)($effDetail[0]->efficiency ?? 0), 2, '.', '').'</td>';
                
              } else{
               $output.='<td class="amount" style="border:1px solid black;text-align:right !important">-</td>';
              }
                
               
                   if ($effDetail) {
                        $sumEfficiency += $effDetail[0]->efficiency ?? 0;
                        
                        if(isset($effDetail[0]->efficiency) ?? 0 !=0)
                        {
                        $countEntries++;
                        }
                        
                        
                      $empArray[]=$rowOperator->employeeCode;    
                      
                      
               
                    
                    // Calculate the overall average
                  
                        
                    }
           
             
                
              }
                     $output.='<td class="amount text-right TOTEFF" style="border:1px solid black;text-align:right !important;font-weight:bold" onclick="showOperatorsEffDetails(\'' .$request->fromDate. '\',\'' .$request->toDate. '\','.$rowOperator->employeeCode.','.$deptArray.')">'.($countEntries > 0 ?  number_format((float)($sumEfficiency / $countEntries), 2, '.', '') : 0).'</td>
                      <td class="amount text-right TOTEFF_MTD" style="border:1px solid black;text-align:right !important;font-weight:bold" onclick="showOperatorsEffDetails(\'' .date('Y-m-01',strtotime($request->fromDate)). '\',\'' .date('Y-m-t',strtotime($request->toDate)). '\','.$rowOperator->employeeCode.','.$deptArray.')">'.number_format((float)($overallAvg ?? 0), 2, '.', '').'</td>
            </tr>';
               $countEntries=0;$sumEfficiency=0;                
               
               
               $empArrayByDept[]=$rowOperator->employeeCode;  
     }
     
     if(count($empArrayByDept)>2)
     {
            $output.='<tfoot>    
           <tr>
           
             <td  style="text-align:right;background-color:#e01414;color:#000" class="notConsider">1-19.99%</td>';
      
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#e01414;color:#000" class="efficiency-count-1-to-19" onclick="showOperatorsInRangeMultiple(1, 20,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
                }    
                  $output.='<td  style="text-align:right;background-color:#e01414" id="'.$deptArray.'1_to_19" onclick="showOperatorsInRangeMultiple(1,20,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                  <td  style="text-align:right;background-color:#e01414" id="'.$deptArray.'1_to_19_MTD" onclick="showOperatorsInRangeMultiple(1,20,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
       </tr>
         <tr>
           
             <td  style="text-align:right;background-color:#ef3232;color:#000" class="notConsider">20-39.99%</td>';
                foreach($period  as $index => $date)   
                {
               $output.='<td  style="text-align:right;background-color:#ef3232" class="efficiency-count-20-to-39" onclick="showOperatorsInRangeMultiple(20,40,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }
                 $output.='<td  style="text-align:right;background-color:#ef3232" id="'.$deptArray.'20_to_39"  onclick="showOperatorsInRangeMultiple(20,40,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
               <td  style="text-align:right;background-color:#ef3232" id="'.$deptArray.'20_to_39_MTD" onclick="showOperatorsInRangeMultiple(20,40,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>   
        </tr>
               <tr>
             <td  style="text-align:right;background-color:#f7c560;color:#000">40-54.99%</td>';
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#f7c560" class="efficiency-count-40-to-54" onclick="showOperatorsInRangeMultiple(40,55,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }  
                $output.='<td  style="text-align:right;background-color:#f7c560" id="'.$deptArray.'40_to_54" onclick="showOperatorsInRangeMultiple(40,55,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                <td  style="text-align:right;background-color:#f7c560" id="'.$deptArray.'40_to_54_MTD" onclick="showOperatorsInRangeMultiple(40,55,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>  
         <tr>
             <td  style="text-align:right;background-color:#f8fc2d;color:#000">55-69.99%</td>';
               foreach($period  as $index => $date)   
               {
              $output.='<td  style="text-align:right;background-color:#f8fc2d" class="efficiency-count-55-to-69" onclick="showOperatorsInRangeMultiple(55,70,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }
                   $output.='<td  style="text-align:right;background-color:#f8fc2d" id="'.$deptArray.'55_to_69" onclick="showOperatorsInRangeMultiple(55,70,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                    <td  style="text-align:right;background-color:#f8fc2d" id="'.$deptArray.'55_to_69_MTD" onclick="showOperatorsInRangeMultiple(55,70,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#1594ef;color:#000">70-84.99%</td>';
              foreach($period  as $index => $date)   
              {
               $output.='<td  style="text-align:right;background-color:#1594ef" class="efficiency-count-70-to-84" onclick="showOperatorsInRangeMultiple(70,85,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
              }
                 $output.='<td  style="text-align:right;background-color:#1594ef" id="'.$deptArray.'70_84" onclick="showOperatorsInRangeMultiple(70,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                  <td  style="text-align:right;background-color:#1594ef" id="'.$deptArray.'70_84_MTD" onclick="showOperatorsInRangeMultiple(70,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
         </tr>
           <tr>
           
             <td  style="text-align:right;background-color:#0ba01f;color:#000">ABOVE 85%</td>';
              foreach($period  as $index => $date)   
              {
              $output.='<td  style="text-align:right;background-color:#0ba01f" class="efficiency-count-above-85" onclick="showOperatorsInRangeMultiple(0,85,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
              }  
                $output.='<td  style="text-align:right;background-color:#0ba01f" id="'.$deptArray.'above_85" onclick="showOperatorsInRangeMultiple(0,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                <td  style="text-align:right;background-color:#0ba01f" id="'.$deptArray.'above_85_MTD" onclick="showOperatorsInRangeMultiple(0,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#f4bab5;color:#000">ABOVE 60%</td>';
                foreach($period  as $index => $date)   
                {
              $output.='<td  style="text-align:right;background-color:#f4bab5"  class="efficiency-count-above-60" onclick="showOperatorsInRangeMultiple(0,60,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
                }
                      $output.='<td  style="text-align:right;background-color:#f4bab5"  id="'.$deptArray.'above_60" onclick="showOperatorsInRangeMultiple(0,60,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                      <td  style="text-align:right;background-color:#f4bab5"  id="'.$deptArray.'above_60_MTD" onclick="showOperatorsInRangeMultiple(0,60,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
        </tfoot>';
    
    }
    
    

         $output.=' </tbody>
    </table>
    

  
</div>
</div>';

  
      }
  
     if(count($empArrayByDept)>2)
     {
       $output.='
    <div class="outer-wrapper">
    <div class="table-wrapper">
       
       <table style="border:1px solid #000" id="grandTable">
       <thead>';
       
        $output.='<th></th>';
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d',strtotime($date));
                  
                  $output.='<th>'.$dayName.'</th>';
                    
                  
              }
                 $output.='<th>AVG</th>';
                    $output.='<th>MTD</th>';
        $output.='</thead>
       
         <tfoot>
     <tr>
           
          
             <td  style="text-align:right;background-color:#e01414;color:#000" class="notConsider">1-19.99%</td>';
      
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#e01414;color:#000" class="efficiency-count-1-to-19-grand"></td>';
                }    
                  $output.='<td  style="text-align:right;background-color:#e01414" id="grand-avg-1-to-19"></td>
                  <td  style="text-align:right;background-color:#e01414" id="grand-mtd-1-to-19"></td>
       </tr>
       
          <tr>
           
             <td  style="text-align:right;background-color:#ef3232;color:#000" class="notConsider">20-39.99%</td>';
                foreach($period  as $index => $date)   
                {
               $output.='<td  style="text-align:right;background-color:#ef3232" class="efficiency-count-20-to-39-grand"></td>';
               }
                 $output.='<td  style="text-align:right;background-color:#ef3232"  id="grand-avg-20-to-39"></td>
               <td  style="text-align:right;background-color:#ef3232" id="grand-mtd-20-to-39"></td>   
        </tr>
         <tr>
             <td  style="text-align:right;background-color:#f7c560;color:#000">40-54.99%</td>';
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#f7c560" class="efficiency-count-40-to-54-grand"></td>';
               }  
                $output.='<td  style="text-align:right;background-color:#f7c560" id="grand-avg-40-to-54"></td>
                <td  style="text-align:right;background-color:#f7c560"  id="grand-mtd-40-to-54"></td>
        </tr>  
        
             <tr>
             <td  style="text-align:right;background-color:#f8fc2d;color:#000">55-69.99%</td>';
               foreach($period  as $index => $date)   
               {
              $output.='<td  style="text-align:right;background-color:#f8fc2d" class="efficiency-count-55-to-69-grand"></td>';
               }
                   $output.='<td  style="text-align:right;background-color:#f8fc2d" id="grand-avg-55-to-69"></td>
                    <td  style="text-align:right;background-color:#f8fc2d" id="grand-mtd-55-to-69"></td>
        </tr>  
                 <tr>
           
             <td  style="text-align:right;background-color:#1594ef;color:#000">70-84.99%</td>';
              foreach($period  as $index => $date)   
              {
               $output.='<td  style="text-align:right;background-color:#1594ef" class="efficiency-count-70-to-84-grand"></td>';
              }
                 $output.='<td  style="text-align:right;background-color:#1594ef" id="grand-avg-70-to-84"></td>
                  <td  style="text-align:right;background-color:#1594ef" id="grand-mtd-70-to-84"></td>
         </tr>
        
                 <tr>
           
             <td  style="text-align:right;background-color:#0ba01f;color:#000">ABOVE 85%</td>';
              foreach($period  as $index => $date)   
              {
              $output.='<td  style="text-align:right;background-color:#0ba01f" class="efficiency-count-above-85-grand"></td>';
              }  
                $output.='<td  style="text-align:right;background-color:#0ba01f" id="grand-avg-above-85"></td>
                <td  style="text-align:right;background-color:#0ba01f" id="grand-mtd-above-85"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#f4bab5;color:#000">ABOVE 60%</td>';
                foreach($period  as $index => $date)   
                {
              $output.='<td  style="text-align:right;background-color:#f4bab5"  class="efficiency-count-above-60-grand"></td>';
                }
                      $output.='<td  style="text-align:right;background-color:#f4bab5" id="grand-avg-above-60"></td>
                      <td  style="text-align:right;background-color:#f4bab5" id="grand-mtd-above-60"></td>
        </tr>
         </tfoot>
       
    </table>
    
    </div>
</div>';
     }
  
  return response()->json(['html'=>$output,'empArrayList'=>$empArray]);
        
    }
    
    
    
        
     public function load_efficiency_by_unit_optimize(Request $request)
    {
        
       
        
         $period = $this->getBetweenDates($request->fromDate,$request->toDate);
   
 
         $output="";  
         
         $empArrayByDept=[];
      
      foreach($request->dept_id as $deptArray)
      {
            // DB::enableQueryLog();
            $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$deptArray)
         ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
        //dd(DB::getQueryLog());
        
        $deptFetch=DB::table('line_master')->select('line_id','line_name')->where('line_id',$deptArray)->first();
        
        $subFetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();

    $output.='
         <center><div class="row "> <span style="font-weight:bold">'.$subFetch->sub_company_name.'</span></div> </center><br>
       <center><div class="row "> <span style="font-weight:bold">'.$deptFetch->line_name.'</span></div> </center>
    <div class="outer-wrapper">
    <div class="table-wrapper">

    <table id="rptsum'.$deptArray.'">
        <thead>
            <th onclick="sortTable(0)">Operator </th>';
            $nosAvg=0; $nosAvgMTD=0; 
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d',strtotime($date));
                  
                    $output.='<th onclick="sortTable($index + 1)">'.$dayName.'</th>';
                    
                    $nosAvg=($index + 2); $nosAvgMTD=($index + 3); 
              }
                 $output.='<th onclick="sortTable('.$nosAvg.')">AVG</th> 
                 <th onclick="sortTable('.$nosAvgMTD.')">MTD</th> 
        </thead>
        <tbody>';
          
            
               
                   $sumEfficiency = 0;
                $countEntries = 0; 
                $overall_avg=0;
               $empArray=[];
               
               
                     $fromDateMonthNEW=date('Y-m-01',strtotime($request->fromDate));
                     $toDateMonthNEW=date('Y-m-t',strtotime($fromDateMonthNEW));
        
                
        
        $operatorArray = $operatorList instanceof \Illuminate\Support\Collection ? $operatorList->toArray() : $operatorList;

      // Extract operator from the array
        $operatorsArr = array_column($operatorArray, 'employeeCode');
               
               
                        $dailyAverages = DB::table('daily_production_entry_details_operation')
                        ->select(DB::raw("ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (CASE WHEN is_half_day = 1 THEN 2.4 ELSE 4.8 END),2) as daily_avg,daily_production_entry_details_operation.employeeCode,COUNT(daily_production_entry_details_operation.operation_id) as avgCount,daily_production_entry_details_operation.daily_pr_date"))
                        ->join('ob_details', function ($join) {
                        $join->on('ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')
                        ->whereColumn('ob_details.mainstyle_id', '=', 'daily_production_entry_details_operation.mainstyle_id');
                        })    
                        ->where('dept_id', $deptArray)
                        ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
                        ->whereBetween('daily_pr_date', [$fromDateMonthNEW,$toDateMonthNEW])
                          ->whereIn('employeeCode', $operatorsArr)  
                        ->where('ob_details.sam', '>', 0)
                        ->groupBy('daily_production_entry_details_operation.daily_pr_date',
                        'daily_production_entry_details_operation.employeeCode','daily_production_entry_details_operation.operation_id')
                        ->get();
                        
                        
          $dataMap = [];

        foreach ($dailyAverages as $record) {
        $dataMap[$record->employeeCode][$fromDateMonthNEW][$toDateMonthNEW][] = [
        'daily_avg' => $record->daily_avg,
       'avgCount'=> $record->avgCount,
       'daily_pr_date'=>$record->daily_pr_date
        ];
        
        }                

               
                   $effDetail=DB::table('daily_production_entry_details_operation')
          ->select(DB::raw("ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (CASE WHEN is_half_day = 1 THEN 2.4 ELSE 4.8 END),2) as efficiency,
          daily_production_entry_details_operation.employeeCode,daily_production_entry_details_operation.daily_pr_date,SUM(ob_details.sam) as sam,
          SUM(daily_production_entry_details_operation.pieces) as pieces,daily_production_entry_details_operation.is_half_day
          "))
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')
            ->whereColumn('ob_details.mainstyle_id', '=', 'daily_production_entry_details_operation.mainstyle_id');
            })
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$fromDateMonthNEW,$toDateMonthNEW])
         ->whereIn('daily_production_entry_details_operation.employeeCode',$operatorsArr)
         ->where('daily_production_entry_details_operation.dept_id',$deptArray) 
         ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
          ->groupBy('daily_production_entry_details_operation.daily_pr_date',
          'daily_production_entry_details_operation.employeeCode','daily_production_entry_details_operation.operation_id')
         ->get();
               
               
                       
                $dataMapData = [];
                
                foreach ($effDetail as $recordData) {
                $dataMapData[$recordData->employeeCode][$recordData->daily_pr_date][] = [
                'sam' => $recordData->sam,
                'pieces' => $recordData->pieces,
                'is_half_day'=>$recordData->is_half_day
             
                
                ];
                
                
                }
               
             
                  
     foreach($operatorList as $rowOperator)   
     {
         
                $fromDateMonth=date('Y-m-01',strtotime($request->fromDate));
                $toDateMonth=date('Y-m-t',strtotime($fromDateMonth));
         
                 $overallAvg=0;
                $OverallTotal=0;
                        $avgCount=0;
              
          $addedAvgCountDates = [];
                                $mapCount=0;
            if (isset($dataMap[$rowOperator->employeeCode])) {
        foreach ($dataMap[$rowOperator->employeeCode][$fromDateMonth][$toDateMonth] as $record) {
            $overallAvg+= ($record['daily_avg']);
            $OverallTotal+= ($record['daily_avg']); 
           
                          
               $avgCount += $record['avgCount'];
               
    
                          
              $date = $record['daily_pr_date'];

            if (isset($record['daily_avg']) && !in_array($date, $addedAvgCountDates)) {
                $mapCount++;
                $addedAvgCountDates[] = $date;
            }
             
        }
    } 

        
        // Calculate the overall average
       // $overallAvg = $dailyAverages->avg('daily_avg');
         
         
            $output.='<tr class="operator-row">
                
                  <td style="border:1px solid black fillter">'.$rowOperator->fullName.'</td>';
                
              foreach($period  as $index => $date)   
              {
            
             
                $fromDate=date('Y-m-01',strtotime($date));
                $toDate=date('Y-m-t',strtotime($fromDate));
             
                  
                  $proMin=0;
        
                            
                            if (isset($dataMapData[$rowOperator->employeeCode][$date])) {
                            foreach ($dataMapData[$rowOperator->employeeCode][$date] as $recordss) {
                                
                                if($recordss['is_half_day']==1)
                                {
                                  $hrsCal=2.4;  
                                    
                                } else{
                                    
                                    $hrsCal=4.8;   
                                }
                                
                            $proMin+= (($recordss['sam'] * $recordss['pieces']) / ($hrsCal));
                            
                             
                           
                            }
                            } 
         
         
              
              if(isset($proMin) ?? 0!=0)
              {
                $output.='<td class="amount" style="border:1px solid black;text-align:right !important" onClick="get_operation_detail('.$rowOperator->employeeCode.',\'' .$date. '\','.$deptArray.')">'.number_format((float)($proMin ?? 0), 2, '.', '').'</td>';
                
              } else{
               $output.='<td class="amount" style="border:1px solid black;text-align:right !important">-</td>';
              }
                
               
                //   if ($effDetail) {
                //         $sumEfficiency += $proMin?? 0;
                        
                //         if(isset($proMin) ?? 0 !=0)
                //         {
                //         $countEntries++;
                //         }
                        
                        
                //       $empArray[]=$rowOperator->employeeCode;    
                  
                        
                //     }
                    
                    
                        if ($effDetail) {
                        $sumEfficiency += round($proMin,2);
    
                        
                        
                        // Only increment countEntries if proMin is NOT 0
                        if ($proMin != 0) {
                        $countEntries++;
                        }
                        
                        $empArray[] = $rowOperator->employeeCode;
                        }

             
                
              }
                     $output.='<td class="amount text-right TOTEFF" style="border:1px solid black;text-align:right !important;font-weight:bold" onclick="showOperatorsEffDetails(\'' .$request->fromDate. '\',\'' .$request->toDate. '\','.$rowOperator->employeeCode.','.$deptArray.')">'.($countEntries > 0 ?  number_format((float)($sumEfficiency / $countEntries), 2, '.', '') : 0).'</td>
                      <td class="amount text-right TOTEFF_MTD" style="border:1px solid black;text-align:right !important;font-weight:bold" onclick="showOperatorsEffDetails(\'' .date('Y-m-01',strtotime($request->fromDate)). '\',\'' .date('Y-m-t',strtotime($request->toDate)). '\','.$rowOperator->employeeCode.','.$deptArray.')">'.($mapCount > 0 ?  number_format((float)($overallAvg / $mapCount), 2, '.', '') : 0).'</td>
            </tr>';
               $countEntries=0;$sumEfficiency=0;$mapCount=0;                                                                                                                                                                             
               
               
               $empArrayByDept[]=$rowOperator->employeeCode;  
     }
     
     if(count($empArrayByDept)>2)
     {
            $output.='<tfoot>    
           <tr>
           
             <td  style="text-align:right;background-color:#e01414;color:#000" class="notConsider">1-19.99%</td>';
      
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#e01414;color:#000" class="efficiency-count-1-to-19" onclick="showOperatorsInRangeMultiple(1, 20,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
                }    
                  $output.='<td  style="text-align:right;background-color:#e01414" id="'.$deptArray.'1_to_19" onclick="showOperatorsInRangeMultiple(1,20,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                  <td  style="text-align:right;background-color:#e01414" id="'.$deptArray.'1_to_19_MTD" onclick="showOperatorsInRangeMultiple(1,20,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
       </tr>
         <tr>
           
             <td  style="text-align:right;background-color:#ef3232;color:#000" class="notConsider">20-39.99%</td>';
                foreach($period  as $index => $date)   
                {
               $output.='<td  style="text-align:right;background-color:#ef3232" class="efficiency-count-20-to-39" onclick="showOperatorsInRangeMultiple(20,40,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }
                 $output.='<td  style="text-align:right;background-color:#ef3232" id="'.$deptArray.'20_to_39"  onclick="showOperatorsInRangeMultiple(20,40,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
               <td  style="text-align:right;background-color:#ef3232" id="'.$deptArray.'20_to_39_MTD" onclick="showOperatorsInRangeMultiple(20,40,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>   
        </tr>
               <tr>
             <td  style="text-align:right;background-color:#f7c560;color:#000">40-54.99%</td>';
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#f7c560" class="efficiency-count-40-to-54" onclick="showOperatorsInRangeMultiple(40,55,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }  
                $output.='<td  style="text-align:right;background-color:#f7c560" id="'.$deptArray.'40_to_54" onclick="showOperatorsInRangeMultiple(40,55,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                <td  style="text-align:right;background-color:#f7c560" id="'.$deptArray.'40_to_54_MTD" onclick="showOperatorsInRangeMultiple(40,55,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>  
         <tr>
             <td  style="text-align:right;background-color:#f8fc2d;color:#000">55-69.99%</td>';
               foreach($period  as $index => $date)   
               {
              $output.='<td  style="text-align:right;background-color:#f8fc2d" class="efficiency-count-55-to-69" onclick="showOperatorsInRangeMultiple(55,70,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }
                   $output.='<td  style="text-align:right;background-color:#f8fc2d" id="'.$deptArray.'55_to_69" onclick="showOperatorsInRangeMultiple(55,70,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                    <td  style="text-align:right;background-color:#f8fc2d" id="'.$deptArray.'55_to_69_MTD" onclick="showOperatorsInRangeMultiple(55,70,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#1594ef;color:#000">70-84.99%</td>';
              foreach($period  as $index => $date)   
              {
               $output.='<td  style="text-align:right;background-color:#1594ef" class="efficiency-count-70-to-84" onclick="showOperatorsInRangeMultiple(70,85,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
              }
                 $output.='<td  style="text-align:right;background-color:#1594ef" id="'.$deptArray.'70_84" onclick="showOperatorsInRangeMultiple(70,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                  <td  style="text-align:right;background-color:#1594ef" id="'.$deptArray.'70_84_MTD" onclick="showOperatorsInRangeMultiple(70,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
         </tr>
           <tr>
           
             <td  style="text-align:right;background-color:#0ba01f;color:#000">ABOVE 85%</td>';
              foreach($period  as $index => $date)   
              {
              $output.='<td  style="text-align:right;background-color:#0ba01f" class="efficiency-count-above-85" onclick="showOperatorsInRangeMultiple(0,85,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
              }  
                $output.='<td  style="text-align:right;background-color:#0ba01f" id="'.$deptArray.'above_85" onclick="showOperatorsInRangeMultiple(0,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                <td  style="text-align:right;background-color:#0ba01f" id="'.$deptArray.'above_85_MTD" onclick="showOperatorsInRangeMultiple(0,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#f4bab5;color:#000">ABOVE 60%</td>';
                foreach($period  as $index => $date)   
                {
              $output.='<td  style="text-align:right;background-color:#f4bab5"  class="efficiency-count-above-60" onclick="showOperatorsInRangeMultiple(0,60,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
                }
                      $output.='<td  style="text-align:right;background-color:#f4bab5"  id="'.$deptArray.'above_60" onclick="showOperatorsInRangeMultiple(0,60,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                      <td  style="text-align:right;background-color:#f4bab5"  id="'.$deptArray.'above_60_MTD" onclick="showOperatorsInRangeMultiple(0,60,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
        </tfoot>';
    
    }
    
    

         $output.=' </tbody>
    </table>
    

  
</div>
</div>';

  
      }
  
     if(count($empArrayByDept)>2)
     {
       $output.='
    <div class="outer-wrapper">
    <div class="table-wrapper">
       
       <table style="border:1px solid #000" id="grandTable">
       <thead>';
       
        $output.='<th></th>';
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d',strtotime($date));
                  
                  $output.='<th>'.$dayName.'</th>';
                    
                  
              }
                 $output.='<th>AVG</th>';
                    $output.='<th>MTD</th>';
        $output.='</thead>
       
         <tfoot>
     <tr>
           
          
             <td  style="text-align:right;background-color:#e01414;color:#000" class="notConsider">1-19.99%</td>';
      
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#e01414;color:#000" class="efficiency-count-1-to-19-grand"></td>';
                }    
                  $output.='<td  style="text-align:right;background-color:#e01414" id="grand-avg-1-to-19"></td>
                  <td  style="text-align:right;background-color:#e01414" id="grand-mtd-1-to-19"></td>
       </tr>
       
          <tr>
           
             <td  style="text-align:right;background-color:#ef3232;color:#000" class="notConsider">20-39.99%</td>';
                foreach($period  as $index => $date)   
                {
               $output.='<td  style="text-align:right;background-color:#ef3232" class="efficiency-count-20-to-39-grand"></td>';
               }
                 $output.='<td  style="text-align:right;background-color:#ef3232"  id="grand-avg-20-to-39"></td>
               <td  style="text-align:right;background-color:#ef3232" id="grand-mtd-20-to-39"></td>   
        </tr>
         <tr>
             <td  style="text-align:right;background-color:#f7c560;color:#000">40-54.99%</td>';
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#f7c560" class="efficiency-count-40-to-54-grand"></td>';
               }  
                $output.='<td  style="text-align:right;background-color:#f7c560" id="grand-avg-40-to-54"></td>
                <td  style="text-align:right;background-color:#f7c560"  id="grand-mtd-40-to-54"></td>
        </tr>  
        
             <tr>
             <td  style="text-align:right;background-color:#f8fc2d;color:#000">55-69.99%</td>';
               foreach($period  as $index => $date)   
               {
              $output.='<td  style="text-align:right;background-color:#f8fc2d" class="efficiency-count-55-to-69-grand"></td>';
               }
                   $output.='<td  style="text-align:right;background-color:#f8fc2d" id="grand-avg-55-to-69"></td>
                    <td  style="text-align:right;background-color:#f8fc2d" id="grand-mtd-55-to-69"></td>
        </tr>  
                 <tr>
           
             <td  style="text-align:right;background-color:#1594ef;color:#000">70-84.99%</td>';
              foreach($period  as $index => $date)   
              {
               $output.='<td  style="text-align:right;background-color:#1594ef" class="efficiency-count-70-to-84-grand"></td>';
              }
                 $output.='<td  style="text-align:right;background-color:#1594ef" id="grand-avg-70-to-84"></td>
                  <td  style="text-align:right;background-color:#1594ef" id="grand-mtd-70-to-84"></td>
         </tr>
        
                 <tr>
           
             <td  style="text-align:right;background-color:#0ba01f;color:#000">ABOVE 85%</td>';
              foreach($period  as $index => $date)   
              {
              $output.='<td  style="text-align:right;background-color:#0ba01f" class="efficiency-count-above-85-grand"></td>';
              }  
                $output.='<td  style="text-align:right;background-color:#0ba01f" id="grand-avg-above-85"></td>
                <td  style="text-align:right;background-color:#0ba01f" id="grand-mtd-above-85"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#f4bab5;color:#000">ABOVE 60%</td>';
                foreach($period  as $index => $date)   
                {
              $output.='<td  style="text-align:right;background-color:#f4bab5"  class="efficiency-count-above-60-grand"></td>';
                }
                      $output.='<td  style="text-align:right;background-color:#f4bab5" id="grand-avg-above-60"></td>
                      <td  style="text-align:right;background-color:#f4bab5" id="grand-mtd-above-60"></td>
        </tr>
         </tfoot>
       
    </table>
    
    </div>
</div>';
     }
  
  return response()->json(['html'=>$output,'empArrayList'=>$empArray]);
        
    }
    
    
     public function load_efficiency_by_unit_pc(Request $request)
    {
        
       
        
         $period = $this->getBetweenDates($request->fromDate,$request->toDate);
   
 
         $output="";  
         
         $empArrayByDept=[];
      
      foreach($request->dept_id as $deptArray)
      {
            // DB::enableQueryLog();
            $data=DB::table('daily_production_entry_details')->select('daily_production_entry_details.employeeCode','employeemaster_operation.fullName')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details.employeeCode')
         ->where('daily_production_entry_details.line_no',$deptArray)
         ->where('daily_production_entry_details.vendorId',$request->sub_company_id)  
         ->whereBetween('daily_production_entry_details.dailyProductionEntryDate',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
          $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
             $operatorList=$data->get();    
          }
        //dd(DB::getQueryLog());
        
        
        $deptFetch=DB::table('line_master')->select('line_name')->where('line_id',$deptArray)->first();
        $subFetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();
        
        
        
                   $fromDateMonthNEW=date('Y-m-01',strtotime($request->fromDate));
                   $toDateMonthNEW=date('Y-m-t',strtotime($fromDateMonthNEW));
        
                
        
        $operatorArray = $operatorList instanceof \Illuminate\Support\Collection ? $operatorList->toArray() : $operatorList;

      // Extract operator from the array
        $operatorsArr = array_column($operatorArray, 'employeeCode');
  
        
        
        $dailyAverages = DB::table('daily_production_entry_details')
        ->select(DB::raw("ROUND(SUM(ob_details.sam * daily_production_entry_details.stiching_qty) / (4.8),2) as daily_avg,daily_production_entry_details.employeeCode"))
           ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'daily_production_entry_details.sales_order_no')
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'daily_production_entry_details.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            })
         ->where('daily_production_entry_details.line_no', $deptArray)
         ->where('daily_production_entry_details.vendorId',$request->sub_company_id)  
         ->whereBetween('dailyProductionEntryDate', [$fromDateMonthNEW,$toDateMonthNEW])
         ->whereIn('employeeCode', $operatorsArr)
         ->where('ob_details.sam', '>', 0)
      ->groupBy('daily_production_entry_details.dailyProductionEntryDate','daily_production_entry_details.operationNameId','daily_production_entry_details.bundleNo','daily_production_entry_details.employeeCode')  
         ->get();
    
        
        $dataMap = [];

        foreach ($dailyAverages as $record) {
        $dataMap[$record->employeeCode][$fromDateMonthNEW][$toDateMonthNEW][] = [
        'daily_avg' => $record->daily_avg
        ];
        
        }
        
        
                    
                    
                    
                    $effDetail=DB::table('daily_production_entry_details')
                    ->select(DB::raw("ob_details.sam, daily_production_entry_details.stiching_qty,daily_production_entry_details.employeeCode,daily_production_entry_details.dailyProductionEntryDate"))
                        ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'daily_production_entry_details.sales_order_no')
                        ->join('ob_details', function ($join) {
                        $join->on('ob_details.operation_id', '=', 'daily_production_entry_details.operationNameId')
                        ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
                        })
                    ->whereBetween('daily_production_entry_details.dailyProductionEntryDate',[$fromDateMonthNEW,$toDateMonthNEW])
                    ->whereIn('daily_production_entry_details.employeeCode',$operatorsArr)
                    ->where('daily_production_entry_details.line_no',$deptArray) 
                    ->where('daily_production_entry_details.vendorId',$request->sub_company_id)  
                     ->groupBy('daily_production_entry_details.dailyProductionEntryDate','daily_production_entry_details.operationNameId','daily_production_entry_details.bundleNo','daily_production_entry_details.employeeCode')   
                    ->get();

                
                
                
                $dataMapData = [];
                
                foreach ($effDetail as $recordData) {
                $dataMapData[$recordData->employeeCode][$recordData->dailyProductionEntryDate][] = [
                'sam' => $recordData->sam,
                'stiching_qty' => $recordData->stiching_qty
                
                ];
                
                
                }
        
        
        

    $output.='
         <center><div class="row "> <span style="font-weight:bold">'.$subFetch->sub_company_name.'</span></div> </center><br>
       <center><div class="row "> <span style="font-weight:bold">'.$deptFetch->line_name.'</span></div> </center>
    <div class="outer-wrapper">
    <div class="table-wrapper">

    <table id="rptsum'.$deptArray.'">
        <thead>
            <th onclick="sortTable(0)">Operator </th>';
            $nosAvg=0; $nosAvgMTD=0; 
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d',strtotime($date));
                  
                    $output.='<th onclick="sortTable($index + 1)">'.$dayName.'</th>';
                    
                    $nosAvg=($index + 2); $nosAvgMTD=($index + 3); 
              }
                 $output.='<th onclick="sortTable('.$nosAvg.')">AVG</th> 
                 <th onclick="sortTable('.$nosAvgMTD.')">MTD</th> 
        </thead>
        <tbody>';
          
            
               
                   $sumEfficiency = 0;
                $countEntries = 0; 
                $overall_avg=0;
               $empArray=[];
             
                  
     foreach($operatorList as $rowOperator)   
     {
         
                $fromDateMonth=date('Y-m-01',strtotime($request->fromDate));
                $toDateMonth=date('Y-m-t',strtotime($fromDateMonth));
                
                $overallAvg=0;
                $OverallTotal=0;
                
                
                          $mapCount=0;
            if (isset($dataMap[$rowOperator->employeeCode])) {
        foreach ($dataMap[$rowOperator->employeeCode][$fromDateMonth][$toDateMonth] as $record) {
            $overallAvg+= ($record['daily_avg']);
            $OverallTotal+= ($record['daily_avg']); 
            
             
             if(isset($record['daily_avg']))
             {
                 $mapCount++;
             }
             
        }
    } 
                
                
                
                
         

         
         
            $output.='<tr class="operator-row">
                
                  <td style="border:1px solid black fillter">'.$rowOperator->fullName.'</td>';
                
              foreach($period  as $index => $date)   
              {
            
             
                $fromDate=date('Y-m-01',strtotime($date));
                $toDate=date('Y-m-t',strtotime($fromDate));
                
                
                
                    $proMin=0;
                            
                            if (isset($dataMapData[$rowOperator->employeeCode][$date])) {
                            foreach ($dataMapData[$rowOperator->employeeCode][$date] as $recordss) {
                            $proMin+= (($recordss['sam'] * $recordss['stiching_qty']) / (4.8));
                           
                            }
                            } 
             
         
              
              if($proMin!=0)
              {
                $output.='<td class="amount" style="border:1px solid black;text-align:right !important" onClick="get_operation_detail('.$rowOperator->employeeCode.',\'' .$date. '\','.$deptArray.')">'.number_format((float)($proMin ?? 0), 2, '.', '').'</td>';
                
              } else{
               $output.='<td class="amount" style="border:1px solid black;text-align:right !important">-</td>';
              }
                
               
                   if ($proMin) {
                        $sumEfficiency += $proMin ?? 0;
                        
                        if($proMin!=0)
                        {
                        $countEntries++;
                        }
                        
                        
                      $empArray[]=$rowOperator->employeeCode;    
                      
                      
               
                    
                    // Calculate the overall average
                  
                        
                    }
           
             
                
              }
                     $output.='<td class="amount text-right TOTEFF" style="border:1px solid black;text-align:right !important;font-weight:bold" onclick="showOperatorsEffDetails(\'' .$request->fromDate. '\',\'' .$request->toDate. '\','.$rowOperator->employeeCode.','.$deptArray.')">'.($countEntries > 0 ?  number_format((float)($sumEfficiency / $countEntries), 2, '.', '') : 0).'</td>
                      <td class="amount text-right TOTEFF_MTD" style="border:1px solid black;text-align:right !important;font-weight:bold" onclick="showOperatorsEffDetails(\'' .date('Y-m-01',strtotime($request->fromDate)). '\',\'' .date('Y-m-t',strtotime($request->toDate)). '\','.$rowOperator->employeeCode.','.$deptArray.')">'.number_format((float)($overallAvg ?? 0), 2, '.', '').'</td>
            </tr>';
               $countEntries=0;$sumEfficiency=0;                
               
               
               $empArrayByDept[]=$rowOperator->employeeCode;  
     }
     
     if(count($empArrayByDept)>2)
     {
            $output.='<tfoot>    
           <tr>
           
             <td  style="text-align:right;background-color:#e01414;color:#000" class="notConsider">1-19.99%</td>';
      
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#e01414;color:#000" class="efficiency-count-1-to-19" onclick="showOperatorsInRangeMultiple(1, 20,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
                }    
                  $output.='<td  style="text-align:right;background-color:#e01414" id="'.$deptArray.'1_to_19" onclick="showOperatorsInRangeMultiple(1,20,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                  <td  style="text-align:right;background-color:#e01414" id="'.$deptArray.'1_to_19_MTD" onclick="showOperatorsInRangeMultiple(1,20,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
       </tr>
         <tr>
           
             <td  style="text-align:right;background-color:#ef3232;color:#000" class="notConsider">20-39.99%</td>';
                foreach($period  as $index => $date)   
                {
               $output.='<td  style="text-align:right;background-color:#ef3232" class="efficiency-count-20-to-39" onclick="showOperatorsInRangeMultiple(20,40,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }
                 $output.='<td  style="text-align:right;background-color:#ef3232" id="'.$deptArray.'20_to_39"  onclick="showOperatorsInRangeMultiple(20,40,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
               <td  style="text-align:right;background-color:#ef3232" id="'.$deptArray.'20_to_39_MTD" onclick="showOperatorsInRangeMultiple(20,40,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>   
        </tr>
               <tr>
             <td  style="text-align:right;background-color:#f7c560;color:#000">40-54.99%</td>';
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#f7c560" class="efficiency-count-40-to-54" onclick="showOperatorsInRangeMultiple(40,55,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }  
                $output.='<td  style="text-align:right;background-color:#f7c560" id="'.$deptArray.'40_to_54" onclick="showOperatorsInRangeMultiple(40,55,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                <td  style="text-align:right;background-color:#f7c560" id="'.$deptArray.'40_to_54_MTD" onclick="showOperatorsInRangeMultiple(40,55,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>  
         <tr>
             <td  style="text-align:right;background-color:#f8fc2d;color:#000">55-69.99%</td>';
               foreach($period  as $index => $date)   
               {
              $output.='<td  style="text-align:right;background-color:#f8fc2d" class="efficiency-count-55-to-69" onclick="showOperatorsInRangeMultiple(55,70,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
               }
                   $output.='<td  style="text-align:right;background-color:#f8fc2d" id="'.$deptArray.'55_to_69" onclick="showOperatorsInRangeMultiple(55,70,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                    <td  style="text-align:right;background-color:#f8fc2d" id="'.$deptArray.'55_to_69_MTD" onclick="showOperatorsInRangeMultiple(55,70,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#1594ef;color:#000">70-84.99%</td>';
              foreach($period  as $index => $date)   
              {
               $output.='<td  style="text-align:right;background-color:#1594ef" class="efficiency-count-70-to-84" onclick="showOperatorsInRangeMultiple(70,85,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
              }
                 $output.='<td  style="text-align:right;background-color:#1594ef" id="'.$deptArray.'70_84" onclick="showOperatorsInRangeMultiple(70,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                  <td  style="text-align:right;background-color:#1594ef" id="'.$deptArray.'70_84_MTD" onclick="showOperatorsInRangeMultiple(70,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
         </tr>
           <tr>
           
             <td  style="text-align:right;background-color:#0ba01f;color:#000">ABOVE 85%</td>';
              foreach($period  as $index => $date)   
              {
              $output.='<td  style="text-align:right;background-color:#0ba01f" class="efficiency-count-above-85" onclick="showOperatorsInRangeMultiple(0,85,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
              }  
                $output.='<td  style="text-align:right;background-color:#0ba01f" id="'.$deptArray.'above_85" onclick="showOperatorsInRangeMultiple(0,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                <td  style="text-align:right;background-color:#0ba01f" id="'.$deptArray.'above_85_MTD" onclick="showOperatorsInRangeMultiple(0,85,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#f4bab5;color:#000">ABOVE 60%</td>';
                foreach($period  as $index => $date)   
                {
              $output.='<td  style="text-align:right;background-color:#f4bab5"  class="efficiency-count-above-60" onclick="showOperatorsInRangeMultiple(0,60,\'' .$date. '\','.$deptArray.',0,0,0,'.json_encode($empArray).')"></td>';
                }
                      $output.='<td  style="text-align:right;background-color:#f4bab5"  id="'.$deptArray.'above_60" onclick="showOperatorsInRangeMultiple(0,60,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',2,'.json_encode($empArray).')"></td>
                      <td  style="text-align:right;background-color:#f4bab5"  id="'.$deptArray.'above_60_MTD" onclick="showOperatorsInRangeMultiple(0,60,0,'.$deptArray.',\''.$request->fromDate.'\',\''.$request->toDate.'\',3,'.json_encode($empArray).')"></td>
        </tr>
        </tfoot>';
    
    }
    
    

         $output.=' </tbody>
    </table>
    

  
</div>
</div>';

  
      }
  
     if(count($empArrayByDept)>2)
     {
       $output.='
    <div class="outer-wrapper">
    <div class="table-wrapper">
       
       <table style="border:1px solid #000" id="grandTable">
       <thead>';
       
        $output.='<th></th>';
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d',strtotime($date));
                  
                  $output.='<th>'.$dayName.'</th>';
                    
                  
              }
                 $output.='<th>AVG</th>';
                    $output.='<th>MTD</th>';
        $output.='</thead>
       
         <tfoot>
     <tr>
           
          
             <td  style="text-align:right;background-color:#e01414;color:#000" class="notConsider">1-19.99%</td>';
      
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#e01414;color:#000" class="efficiency-count-1-to-19-grand"></td>';
                }    
                  $output.='<td  style="text-align:right;background-color:#e01414" id="grand-avg-1-to-19"></td>
                  <td  style="text-align:right;background-color:#e01414" id="grand-mtd-1-to-19"></td>
       </tr>
       
          <tr>
           
             <td  style="text-align:right;background-color:#ef3232;color:#000" class="notConsider">20-39.99%</td>';
                foreach($period  as $index => $date)   
                {
               $output.='<td  style="text-align:right;background-color:#ef3232" class="efficiency-count-20-to-39-grand"></td>';
               }
                 $output.='<td  style="text-align:right;background-color:#ef3232"  id="grand-avg-20-to-39"></td>
               <td  style="text-align:right;background-color:#ef3232" id="grand-mtd-20-to-39"></td>   
        </tr>
         <tr>
             <td  style="text-align:right;background-color:#f7c560;color:#000">40-54.99%</td>';
               foreach($period  as $index => $date)   
               {
               $output.='<td  style="text-align:right;background-color:#f7c560" class="efficiency-count-40-to-54-grand"></td>';
               }  
                $output.='<td  style="text-align:right;background-color:#f7c560" id="grand-avg-40-to-54"></td>
                <td  style="text-align:right;background-color:#f7c560"  id="grand-mtd-40-to-54"></td>
        </tr>  
        
             <tr>
             <td  style="text-align:right;background-color:#f8fc2d;color:#000">55-69.99%</td>';
               foreach($period  as $index => $date)   
               {
              $output.='<td  style="text-align:right;background-color:#f8fc2d" class="efficiency-count-55-to-69-grand"></td>';
               }
                   $output.='<td  style="text-align:right;background-color:#f8fc2d" id="grand-avg-55-to-69"></td>
                    <td  style="text-align:right;background-color:#f8fc2d" id="grand-mtd-55-to-69"></td>
        </tr>  
                 <tr>
           
             <td  style="text-align:right;background-color:#1594ef;color:#000">70-84.99%</td>';
              foreach($period  as $index => $date)   
              {
               $output.='<td  style="text-align:right;background-color:#1594ef" class="efficiency-count-70-to-84-grand"></td>';
              }
                 $output.='<td  style="text-align:right;background-color:#1594ef" id="grand-avg-70-to-84"></td>
                  <td  style="text-align:right;background-color:#1594ef" id="grand-mtd-70-to-84"></td>
         </tr>
        
                 <tr>
           
             <td  style="text-align:right;background-color:#0ba01f;color:#000">ABOVE 85%</td>';
              foreach($period  as $index => $date)   
              {
              $output.='<td  style="text-align:right;background-color:#0ba01f" class="efficiency-count-above-85-grand"></td>';
              }  
                $output.='<td  style="text-align:right;background-color:#0ba01f" id="grand-avg-above-85"></td>
                <td  style="text-align:right;background-color:#0ba01f" id="grand-mtd-above-85"></td>
        </tr>
          <tr>
           
             <td  style="text-align:right;background-color:#f4bab5;color:#000">ABOVE 60%</td>';
                foreach($period  as $index => $date)   
                {
              $output.='<td  style="text-align:right;background-color:#f4bab5"  class="efficiency-count-above-60-grand"></td>';
                }
                      $output.='<td  style="text-align:right;background-color:#f4bab5" id="grand-avg-above-60"></td>
                      <td  style="text-align:right;background-color:#f4bab5" id="grand-mtd-above-60"></td>
        </tr>
         </tfoot>
       
    </table>
    
    </div>
</div>';
     }
  
  return response()->json(['html'=>$output,'empArrayList'=>$empArray]);
        
    }
    
    
    
    
         public function load_pl_by_unit(Request $request)
       {
        
       
         $period = $this->getBetweenDates($request->fromDate,$request->toDate);
   
        
 
         $output="";  
         
         $empArrayByDept=[];
              
      
      foreach($request->dept_id as $deptArray)
      {
            // DB::enableQueryLog();
            $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName','employeemaster_operation.misRate')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$deptArray)
         ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[date('Y-m-01',strtotime($request->fromDate)),date('Y-m-t',strtotime($request->fromDate))]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',$request->employeeCode);     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
       // dd(DB::getQueryLog());
        
        
        $deptFetch=DB::table('department_master_operation')->select('dept_name')->where('dept_id',$deptArray)->first();
        $subFetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();

    $output.='
       <center><div class="row "> <span style="font-weight:bold">'.$subFetch->sub_company_name.'</span></div> </center><br>
       <center><div class="row "> <span style="font-weight:bold">'.$deptFetch->dept_name.'</span></div> </center>
    <div class="outer-wrapper">
    <div class="table-wrapper">

    <table id="rptsum'.$deptArray.'">
        <thead>
            <th onclick="sortTable(0)">Operator </th>';
            $nosAvg=0; $nosAvgMTD=0; 
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d',strtotime($date));
                  
                    $output.='<th onclick="sortTable($index + 1)">'.$dayName.'</th>';
                    
                    $nosAvg=($index + 2); $nosAvgMTD=($index + 3); 
              }
                 $output.='
                 <th>Total(Date Range)</th> 
                 <th onclick="sortTable('.$nosAvg.')">AVG(Date Range)</th> 
                 <th onclick="sortTable('.$nosAvg.')">Total MTD</th>   
                 <th onclick="sortTable('.$nosAvgMTD.')">AVG MTD</th> 
        </thead>
        <tbody>';
          
            
               
                   $sumEfficiencyTotal =0;
                $countEntries = 0; 
               // $overall_avg=0;
               $empArray=[];
               $AvgSum=0;
               $totalMtd=0;
              $AvgCount=[];
             // $OverallTotal=0;
              
                $fromDateMonthNEW=date('Y-m-01',strtotime($request->fromDate));
                $toDateMonthNEW=date('Y-m-t',strtotime($fromDateMonthNEW));
              
         $dailyAveragesCount=DB::table('daily_production_entry_details_operation')
        ->select("*")
        ->where('dept_id', $deptArray)
       ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)     
        ->whereBetween('daily_pr_date', [$fromDateMonthNEW,$toDateMonthNEW])
        ->where('sam','>',0)  
       ->where('sam','!=',' ')    
          ->where('pieces','>',0)  
       ->where('pieces','!=',' ')  
        ->groupBy('daily_pr_date')
        ->get();
        
        
        
        
        
        $operatorArray = $operatorList instanceof \Illuminate\Support\Collection ? $operatorList->toArray() : $operatorList;

      // Extract operator from the array
        $operatorsArr = array_column($operatorArray, 'employeeCode');
  
        
        
         $dailyAverages = DB::table('daily_production_entry_details_operation')
        ->select(DB::raw("daily_production_entry_details_operation.employeeCode,(ROUND((1.25) * SUM(ob_details.sam * daily_production_entry_details_operation.pieces))-employeemaster_operation.misRate)    as daily_avg"))
        ->join('ob_details', 'ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')    
        ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
        ->where('daily_production_entry_details_operation.dept_id', $deptArray)
       ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)     
        ->whereBetween('daily_production_entry_details_operation.daily_pr_date', [$fromDateMonthNEW,$toDateMonthNEW])
        ->whereIn('daily_production_entry_details_operation.employeeCode', $operatorsArr)
        ->where('daily_production_entry_details_operation.sam','>',0)  
       ->where('daily_production_entry_details_operation.sam','!=',' ')    
        ->groupBy('daily_production_entry_details_operation.daily_pr_date','daily_production_entry_details_operation.employeeCode')
        ->get();
        
        
        $dataMap = [];

        foreach ($dailyAverages as $record) {
        $dataMap[$record->employeeCode][$fromDateMonthNEW][$toDateMonthNEW][] = [
        'daily_avg' => $record->daily_avg
        ];
        
        
        }
        
        
                $effDetail=DB::table('daily_production_entry_details_operation')
                ->select('ob_details.sam','daily_production_entry_details_operation.pieces',
                'daily_production_entry_details_operation.employeeCode','daily_production_entry_details_operation.daily_pr_date')
                  ->join('ob_details', 'ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')    
                ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$fromDateMonthNEW,$toDateMonthNEW])
                ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id) 
                ->whereIn('daily_production_entry_details_operation.employeeCode',$operatorsArr)
                ->where('daily_production_entry_details_operation.dept_id',$request->dept_id) 
                ->get();
                
                
                $dataMapData = [];
                
                foreach ($effDetail as $recordData) {
                $dataMapData[$recordData->employeeCode][$recordData->daily_pr_date][] = [
                'sam' => $recordData->sam,
                'pieces' => $recordData->pieces
                
                ];
                
                
                }
        
        
        
        
        
        
        
       
                  
     foreach($operatorList as $rowOperator)   
     {
         
                $fromDateMonth=date('Y-m-01',strtotime($request->fromDate));
                $toDateMonth=date('Y-m-t',strtotime($fromDateMonth));
                 $misRate=$rowOperator->misRate ? $rowOperator->misRate : 0;
                 $overallAvg=0;
                 $OverallTotal=0;
         
              $mapCount=0;
            if (isset($dataMap[$rowOperator->employeeCode])) {
        foreach ($dataMap[$rowOperator->employeeCode][$fromDateMonth][$toDateMonth] as $record) {
            $overallAvg+= ($record['daily_avg']);
            $OverallTotal+= ($record['daily_avg']); 
            
             
             if(isset($record['daily_avg']))
             {
                 $mapCount++;
             }
             
        }
    } 
        
        
       
        
        

        
        // Calculate the overall average
       // $overallAvg = $dailyAverages->avg('daily_avg');
       
       if($overallAvg!=0 && $mapCount!=0)
       {
          $overallAvg=($overallAvg / $mapCount);
       } else{
           
           $overallAvg=0;
       }
          
      
        

         
         
            $output.='<tr class="operator-row">
                
                  <td style="border:1px solid black fillter">'.$rowOperator->fullName.'</td>';
                
              foreach($period  as $index => $date)   
              {
            
              
             
                $fromDate=date('Y-m-01',strtotime($date));
                $toDate=date('Y-m-t',strtotime($fromDate));
                
                
                
                      $proMin=0;
                            
                            if (isset($dataMapData[$rowOperator->employeeCode][$date])) {
                            foreach ($dataMapData[$rowOperator->employeeCode][$date] as $recordss) {
                            $proMin+= ((1.25) * ($recordss['sam'] * $recordss['pieces']));
                           
                            }
                            } 
        
                
                
                
                
                
                
             
        //   $effDetail=DB::table('daily_production_entry_details_operation')
        //   ->select(DB::raw("ROUND((1.25) * ((SUM(sam)) * (SUM(pieces)))) as efficiency
        //   "))
        //  ->where('daily_production_entry_details_operation.daily_pr_date',$date)
        //   ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
        //  ->where('daily_production_entry_details_operation.employeeCode',$rowOperator->employeeCode)
        //  ->where('daily_production_entry_details_operation.dept_id',$deptArray) 
        //  ->get();
         
         
              
              if($proMin!=0)
              {
                $output.='<td class="amount" style="border:1px solid black;text-align:right !important" >'.number_format((float)(($proMin ?? 0) - $misRate), 0, '.', '').'</td>';
                
              } else{
               $output.='<td class="amount" style="border:1px solid black;text-align:right !important">-</td>';
              }
                
               
                   if ($proMin) {
                       // $sumEfficiencyTotal +=(($effDetail[0]->efficiency ?? 0) - $misRate);
                        
                      
                        
                        if($proMin!=0)
                        {
                        $countEntries++;
                        }
                        
                        
                      $empArray[]=$rowOperator->employeeCode;    
               
                    
                    // Calculate the overall average
                  
                       
                    }
           
                

              }
              
       
              
                     $output.='
                     <td class="text-right TOTALSAM"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                      <td class="text-right TOTEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                       <td class="text-right TOTALMTDS" style="border:1px solid black;text-align:right !important;font-weight:bold">'.($OverallTotal).'</td>
                      <td class="text-right TOTEFF_MTD" style="border:1px solid black;text-align:right !important;font-weight:bold">'.number_format((float)(($overallAvg) ?? 0),2, '.', '').'</td>
            </tr>';
              
               
               $empArrayByDept[]=$rowOperator->employeeCode; 
           
               $countEntries=0;
               
             
                        
                 $sumEfficiencyTotal=0;
             
              
              
              
             
     }
     

     
            $output.='
        </tbody>
        <tfoot>
        <tr>
        <td class="notConsider">Total</td>';
             foreach($period  as $index => $date)   
              {
            $output.='<td class="grandTot"></td>';
              }
              
                  $output.='<td class="grandTotAll"></td>';
                  $output.='<td class="grandTotAVG"></td>';
                  $output.='<td class="grandTotMTDs"></td>';
                  $output.='<td class="grandTotMTD"></td>';
                 
                  
         $output.='</tr>
        <tfoot>
        
        
    </table>
    

  
</div>
</div>';

  
      }
  
  
  return response()->json(['html'=>$output,'empArrayList'=>$empArray,'dailyAveragesCount'=>count($dailyAveragesCount),'TotalSum'=>$sumEfficiencyTotal]);
        
    }
    
    
    
    
    
             public function load_mis_chart_by_unit(Request $request)
       {
        
       
         $period = $this->getBetweenDates($request->fromDate,$request->toDate);
   
   
 
         $output="";  
         
         $empArrayByDept=[];
              
      
      foreach($request->sub_company_id as $deptArray)
      {



$dataMonthly = 
     DB::table('production_detail')
    ->select(
        'production_detail.productionDate','production_detail.branch_id',
        DB::raw("(production_detail.sam) as sam"),
        DB::raw("(production_detail.totalProduction) as pieces")
    )
    ->where('production_detail.branch_id', $deptArray)
    ->whereBetween('production_detail.productionDate', [date('Y-m-01',strtotime($request->fromDate)),date('Y-m-t',strtotime($request->toDate))])
      ->where('sti_type',1)
   ->groupBy('srNo','branch_id')
    ->get();
    
    

// Create a map to easily access totals by date

$grandTotalMinProduced=0;
$grandTotalPieces=0;
foreach($dataMonthly as $recordMonthly) {
    
       $grandTotalMinProduced+=($recordMonthly->sam * $recordMonthly->pieces);
       $grandTotalPieces+=$recordMonthly->pieces;
    
       
}

       $grandTotalMinProduced= round($grandTotalMinProduced);



// Fetch all data for the entire period in one go
$data = DB::table('production_detail')
    ->select(
        'production_detail.productionDate','production_detail.branch_id',
        DB::raw("(production_detail.sam) as sam"),
        DB::raw("(production_detail.totalProduction) as pieces")
    )
    ->where('production_detail.branch_id', $deptArray)
    ->whereBetween('production_detail.productionDate', [$request->fromDate, $request->toDate])
      ->where('sti_type',1)
    ->groupBy('srNo','branch_id')
    ->get();

// Create a map to easily access totals by date
$dataMap = [];

foreach ($data as $record) {
    $dataMap[$record->productionDate][$record->branch_id][] = [
        'sam' => $record->sam,
        'pieces' => $record->pieces,
    ];
    
 
}
         

        
        
        $subFetch=DB::table('sub_company_master')->select('sub_company_name','erp_sub_company_id')->where('erp_sub_company_id',$deptArray)->first();
        
        
        
        
        
                $dataOpexMonthly = DB::table('opex_masters')
        ->select('opex_amount','opex_date','branch_id','total_operator','man_power')
        ->where('opex_masters.branch_id',$deptArray)
        ->whereBetween('opex_date', [date('Y-m-01',strtotime($request->fromDate)),date('Y-m-t',strtotime($request->toDate))])
        ->get();
          // dd(DB::getQueryLog());
        
        
        // Create a map to easily access totals by date
        $grandTotalOpex = 0;
        $grandMachineOperators=0;
        $grandManpower=0;
        foreach ($dataOpexMonthly as $recordsOpexMontly) {
              $grandTotalOpex+=$recordsOpexMontly->opex_amount;
           $grandMachineOperators+=$recordsOpexMontly->total_operator;
           $grandManpower+=$recordsOpexMontly->man_power;
        
        }       
        
        
        
        
        // DB::enableQueryLog();
        
         $dataOpex = DB::table('opex_masters')
        ->select('opex_amount','opex_date','branch_id','total_operator','man_power')
        ->where('opex_masters.branch_id',$deptArray)
        ->whereBetween('opex_date', [$request->fromDate,$request->toDate])
        ->get();
        
          // dd(DB::getQueryLog());
        
        
        // Create a map to easily access totals by date
        $dataMapOpex = [];
        foreach ($dataOpex as $records) {
         $dataMapOpex[$records->opex_date][$records->branch_id][] = [
         'opex' => $records->opex_amount,
         'machine_operators' => $records->total_operator,
         'total_manpower' => $records->man_power, 
         'dhu_per' => 0, 
         'rejection_per' => 0
        ];
        
        }       
         
               

    $output.='

       <center><div class="row "> <span style="font-weight:bold">'.$subFetch->sub_company_name.'</span></div> </center>
    <div class="outer-wrapper">
    <div class="table-wrapper">

    <table id="rptsum'.$deptArray.'">
        <thead>
            <th onclick="sortTable(0)"> </th>';
            $nosAvg=0; $nosAvgMTD=0; 
              foreach($period as $index => $date)
              {
                 
                  
                   $dayName=  date('d M',strtotime($date));
                  
                    $output.='<th onclick="sortTable($index + 1)">'.$dayName.'</th>';
                    
                    $nosAvg=($index + 2); $nosAvgMTD=($index + 3); 
              }
                 $output.='
                 <th>Total</th> 
                 <th onclick="sortTable('.$nosAvg.')">AVG</th> 
                 <th onclick="sortTable('.$nosAvgMTD.')">MTD</th> 
        </thead>
        <tbody>';
          
      $output.=' <tr>
      <td>Total Min Produced</td>';

      
       foreach($period as $index => $date)
              {
                  
                  
        $totalMinProduced = 0;

    // Check if we have data for this date
    if (isset($dataMap[$date])) {
        foreach ($dataMap[$date][$deptArray] as $record) {
            $totalMinProduced+= $record['pieces'] * $record['sam'];
        }
    }  
    
    
       if($totalMinProduced > 0)
          {
         $output.='<td class="MINPRODUCED"  style="border:1px solid black;text-align:right!important;font-weight:bold">'.indian_number_format_wd(round($totalMinProduced)).'</td>';
          } else{
              
              $output.='<td class="MINPRODUCED" style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';
          }
         
         
              }
              
              
                 
              
            $output.='
                     <td class="text-right TOTALPRODUCEDMIN"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                      <td class="text-right TOTPRODUCEDMINEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                      <td class="text-right TOTEFF_MTD" style="border:1px solid black;text-align:right !important;font-weight:bold">'.$grandTotalMinProduced.'</td>';    
              
       $output.='</tr>';
              
   $output.=' <tr>
      <td>Pieces</td>';
      
      
             foreach($period as $index => $date)
              {
                  
        $Pieces = 0;

    // Check if we have data for this date
    if (isset($dataMap[$date])) {
        foreach ($dataMap[$date][$deptArray] as $record) {
            $Pieces += ($record['pieces']);
        }
    }  
     
     
       if($Pieces > 0)
          {
         $output.='<td class="PIECES" style="border:1px solid black;text-align:right !important;font-weight:bold">'.indian_number_format_wd($Pieces).'</td>';
         
          } else{
              
               $output.='<td class="PIECES" style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';
          }
         
              }
      
                  $output.='
                     <td class="text-right TOTALPIECES"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                      <td class="text-right TOTPIECESEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                      <td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.$grandTotalPieces.'</td>';   
      
      $output.='</tr>';
      
         $output.=' <tr>
      <td>Opex</td>';
      
             foreach($period as $index => $dates)
             {
                  
        $Opex = 0;

    // Check if we have data for this date
    if (isset($dataMapOpex[$dates][$subFetch->erp_sub_company_id]))  {
        foreach($dataMapOpex[$dates][$subFetch->erp_sub_company_id] as $recordss) {
            $Opex += ($recordss['opex']);
        }
    }  
          
          if($Opex > 0)
          {
         $output.='<td class="OPEX" style="border:1px solid black;text-align:right !important;font-weight:bold">'.indian_number_format_wd($Opex).'</td>';
          } else{
              
               $output.='<td class="OPEX" style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';
          }
         
         
              }
              
               $output.='
                     <td class="text-right TOTALOPEX"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                      <td class="text-right TOTOPEXEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';
                      
                      if($grandTotalOpex!=0 || $grandTotalOpex!='')
                      {
                        $output.='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.$grandTotalOpex.'</td>';   
                      } else{
                          
                            $output.='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';    
                      }
                      
      
       $output.='</tr>';
           $output.=' <tr>
      <td>Cost Per Min (CPM)</td>';
      
      $cntCPM=0;
      
    
       foreach($period as $index => $date)
              {
                  
                 $totalMinProduced = 0;
    $Opex = 0;

    // Calculate total produced minutes for the date
    if (isset($dataMap[$date])) {
        foreach ($dataMap[$date][$deptArray] as $record) {
            $totalMinProduced += round($record['sam'] * $record['pieces']);
        }
    }

    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
            $Opex += ($recordss['opex']);
        }
    }

    // Calculate CPM if totalMinProduced is greater than 0 to avoid division by zero
    if ($totalMinProduced > 0 && $Opex > 0) {
        $cpm = $Opex / $totalMinProduced;
       
        $output .= '<td class="CPM" style="border:1px solid black;text-align:right !important;font-weight:bold">' . number_format($cpm, 2) . '</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
    

    
    
}     

     $output.='
                     <td class="text-right TOTALCPM"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
                      <td class="text-right TOTCPMEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';
                      
                      if($grandTotalOpex!='' && $grandTotalMinProduced!='')
                      {
                       $output.='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.round(($grandTotalOpex / $grandTotalMinProduced),2).'</td>'; 
                      } else{
                          $output.='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; 
                      }
       
       $output.='</tr>';
      $output.=' <tr>
      <td>CPAM</td>';
      
          
       foreach($period as $index => $date)
              {
                  
                
    $Opex = 0;
    $machine_operators=0;


    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
            $Opex += ($recordss['opex']);
            $machine_operators += ($recordss['machine_operators']);
        }
    }

    // Calculate CPM if totalMinProduced is greater than 0 to avoid division by zero
    if ($Opex > 0 && $machine_operators > 0) {
        
        
        $cpam = ($Opex / ($machine_operators * 480));
        
        
        $output .= '<td class="CPAM" style="border:1px solid black;text-align:right !important;font-weight:bold">' . number_format($cpam, 2) . '</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
}     


        $output.='
        <td class="text-right TOTALCPAM"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right TOTCPAMEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';
        
        if($grandTotalOpex!=0 && $grandMachineOperators!=0)
        {
         $output.='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.round(($grandTotalOpex / ($grandMachineOperators * 480)),2).'</td>';  
      } else{
        $output.='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';  
          
      }
      
      $output.='</tr>';
       $output.=' <tr>
      <td>Total Manpower</td>';
      
           foreach($period as $index => $date)
              {
          $TotalManpower = 0;

    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
            $TotalManpower += ($recordss['total_manpower']);
            
        }
    }

    // Calculate CPM if totalMinProduced is greater than 0 to avoid division by zero
    if ($TotalManpower > 0) {
      
        $output .= '<td class="ManPower" style="border:1px solid black;text-align:right !important;font-weight:bold">' . number_format($TotalManpower, 2) . '</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
}    
      
          $output.='
        <td class="text-right TotalManPower"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right TotalManPowerEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';
        
        if($grandManpower!=0 || $grandManpower!='')
        {
        $output .= '<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.$grandManpower.'</td>';    
      } else{
          
            $output .= '<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';    
      }
      
      
      $output .= '</tr>';
       $output.=' <tr>
      <td>Machine Operators</td>';
      
      
             foreach($period as $index => $date)
              {
                  
     
    $machine_operators=0;


    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
            $machine_operators += ($recordss['machine_operators']);
        }
    }

    // Calculate CPM if totalMinProduced is greater than 0 to avoid division by zero
    if ($machine_operators > 0) {
  
        $output .= '<td class="MACHINEOP" style="border:1px solid black;text-align:right !important;font-weight:bold">'.number_format($machine_operators, 2).'</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
}     



              $output.='
        <td class="text-right totalMachineOP"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right totalMachineOPEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';
          if($grandMachineOperators!=0 || $grandMachineOperators!='')
        {
        
       $output.=' <td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.$grandMachineOperators.'</td>';   
      } else{
          $output.=' <td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';   
          
      }
      
      $output .= '</tr>';
      $output.=' <tr>
      <td>Man-Machine Ratio</td>';
      
       foreach($period as $index => $date)
              {
                  
            $machine_operators=0;
            $TotalManpower = 0;

    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
             $TotalManpower += ($recordss['total_manpower']);
            $machine_operators += ($recordss['machine_operators']);
        }
    }
      
          // Calculate CPM if totalMinProduced is greater than 0 to avoid division by zero
    if ($machine_operators > 0 && $TotalManpower > 0) {
        
        $manMachineRatio= ($TotalManpower / $machine_operators);
  
        $output .= '<td class="MMR" style="border:1px solid black;text-align:right !important;font-weight:bold">'.number_format($manMachineRatio, 2).'</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
              }
              
                       $output.='
        <td class="text-right totalManMachineRatio"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right totalManMachineEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';
        
        if($grandManpower!=0 && $grandMachineOperators!=0)
        {
        $output .='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.round($grandManpower / $grandMachineOperators,2).'</td>';
         } else{
             
                 $output .='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';
         }
        
       $output .= '</tr>';
        $output.=' <tr>
      <td>DHU%</td>';
      
      
      
           foreach($period as $index => $date)
              {
                  
            $dhu_per=0;
          

    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
             $dhu_per += ($recordss['dhu_per']);
           
        }
    }
      
          // Calculate CPM if totalMinProduced is greater than 0 to avoid division by zero
    if ($dhu_per > 0) {
        
  
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">'.number_format($dhu_per, 2).'</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
              }  
      
              $output.='
        <td class="text-right totalDHUPER"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right totalDHUEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';     
      
      
       $output .= '</tr>';
       $output.=' <tr>
      <td>Rejection%</td>';
      
           foreach($period as $index => $date)
              {
                  
            $rejection_per=0;
          

    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
             $rejection_per += ($recordss['rejection_per']);
           
        }
    }
      
          // Calculate CPM if totalMinProduced is greater than 0 to avoid division by zero
    if ($rejection_per > 0) {
        
  
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">'.number_format($rejection_per, 2).'</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
              }  
      
      
                    $output.='
        <td class="text-right totalDHUPER"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right totalDHUEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold"></td>'; 
      
       $output.='</tr>';
       $output.=' <tr>
      <td>Efficiency%</td>';
      
             foreach($period as $index => $date)
             {
                  
        $totalMinProduced = 0;
        $machine_operators=0;

    // Check if we have data for this date
    if (isset($dataMap[$date])) {
        foreach ($dataMap[$date][$deptArray] as $record) {
            $totalMinProduced += $record['sam'] * $record['pieces'];
        }
    }  
    
    
        if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
            
            $machine_operators += ($recordss['machine_operators']);
        }
    }
    
    
        if ($totalMinProduced > 0 && $machine_operators > 0) {
        
            
          $eff=(($totalMinProduced / $machine_operators)  / (480)) * (100);
  
        $output .= '<td class="EFFPER" style="border:1px solid black;text-align:right !important;font-weight:bold">'.number_format($eff, 2).'</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
     
       }
      
                    $output.='
        <td class="text-right totalEFFPER"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right totalEFFPERAVG" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>';
        
        
            if($grandTotalMinProduced!=0 && $grandMachineOperators!=0)
        {
        $output .='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.number_format((($grandTotalMinProduced / $grandMachineOperators) /(480)) *(100),2).'</td>';
         } else{
             
       $output .='<td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>';
       
         }
      
       $output.='</tr>';
        $output.=' <tr>
      <td>Surplus/Deficit</td>';
      
                $totalSurplusDeficit=0;$array1=0;
             foreach($period as $index => $date)
              {
           if($subFetch->erp_sub_company_id==56)
     {
         
        $surPlusPer='4.25';
     
     } else if($subFetch->erp_sub_company_id==115)
     {
        $surPlusPer='3.75';
         
     } else if($subFetch->erp_sub_company_id==241)
     {
         
          $surPlusPer='3.25';
 
     } else if($subFetch->erp_sub_company_id==110)
     {
       $surPlusPer='3.25';
 
     } else{
         
         $surPlusPer='4.25';
     }
      
      
      $totalMinProduced=0;
          if (isset($dataMap[$date])) {
        foreach ($dataMap[$date][$deptArray] as $record) {
            $totalMinProduced += ($record['sam'] * $record['pieces']);
        }
    } 
      
          $Opex = 0;

    // Calculate Opex for the date
    if (isset($dataMapOpex[$date][$subFetch->erp_sub_company_id])) {
        foreach ($dataMapOpex[$date][$subFetch->erp_sub_company_id] as $recordss) {
            $Opex += ($recordss['opex']);
            
        }
    }
      
      
         if ($totalMinProduced > 0 && $Opex > 0) {
        
            
          $Surplus_Deficit=(($totalMinProduced * $surPlusPer)-$Opex);
  
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold" class="SD">'.number_format($Surplus_Deficit, 2).'</td>'; // Format to 2 decimal places
    } else {
        $output .= '<td style="border:1px solid black;text-align:right !important;font-weight:bold">-</td>'; // If no production, show N/A
    }
      
      
        if ($totalMinProduced > 0 && $Opex > 0) {
      $array1+=$Surplus_Deficit;
        }
              }
      


                    $output.='
        <td class="text-right totalSURPLUSDEF"  style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right totalSURPLUSDEFEFF" style="border:1px solid black;text-align:right !important;font-weight:bold"></td>
        <td class="text-right " style="border:1px solid black;text-align:right !important;font-weight:bold">'.indian_number_format_wd($array1).'</td>'; 

       $output.='</tr>';
            $output.='
        </tbody>

        
        
    </table>
    

  
</div>
</div>';

  
      }
  
  
  return response()->json(['html'=>$output]);
        
    }
    
    
    
    
    
    
    
    
    
             public function load_pl_register_by_unit(Request $request)
         {
        
        
         $period = $this->getBetweenDates($request->fromDate,$request->toDate);
   
                  
 
         $output="";  
         
         $empArrayByDept=[];
      
    
            // DB::enableQueryLog();
            $data=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.employeeCode','employeemaster_operation.fullName','misRate')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->where('daily_production_entry_details_operation.dept_id',$request->dept_id)
          ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id)  
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate]);
         
         
         if(!empty($request->employeeCode))
         {
          $operatorList=$data->whereIn('employeemaster_operation.employeeCode',array_unique($request->employeeCode));     
         $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
          $operatorList=$data->get();
          } else{
              
             $operatorList=$data->groupBy('employeemaster_operation.employeeCode');
            $operatorList=$data->get();    
          }
        //dd(DB::getQueryLog());
        
        
         $deptFetch=DB::table('department_master_operation')->select('dept_name')->where('dept_id',$request->dept_id)->first();
         $subFetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();

    $output.='
       <center><div class="row "> <span style="font-weight:bold">'.$subFetch->sub_company_name.'</span></div> </center><br>
       <center><div class="row "> <span style="font-weight:bold">'.$deptFetch->dept_name.'</span></div> </center>
    <div class="outer-wrapper">
    <div class="table-wrapper">

    <table id="rptsum" class="sortable"> 
        <thead>
         
        <tr>
            <th  rowspan="2" style="width:250px" onclick="sortTable(0)">Operator </th>
              <th  rowspan="2">Daily Wages</th>
            ';
            $nosAvg=0; $nosAvgMTD=0; 
              foreach($period as $index => $date)
              {
                  
                   $dayName=  date('d/m/Y',strtotime($date));
                  
                    $output.='<th  colspan="3" style="position:sticky;z-index: 3;">'.$dayName.'</th>';
                    
                    $nosAvg=($index + 2); $nosAvgMTD=($index + 3); 
              }
              
               $output.='<th  colspan="3" style="position:sticky;z-index: 3;">Total</th>';
                 $output.='
                 </tr>
                    <tr>';
                     foreach($period as $index => $date)
              {
                 $output.='
                 <th rowspan="2" style="position:sticky;z-index: 3;top:37px">Pro. Min.</th>
                 <th rowspan="2" style="position:sticky;z-index: 3;top:37px">Earn</th>
                 <th rowspan="2" style="position:sticky;z-index: 3;top:37px">P/L</th>';    
              }
              
              $output.='
                 <th rowspan="2" style="position:sticky;z-index: 3;top:37px">Pro. Min.</th>
                 <th rowspan="2" style="position:sticky;z-index: 3;top:37px">Earn</th>
                 <th rowspan="2" style="position:sticky;z-index: 3;top:37px">P/L</th>';     
              
                   $output.='</tr>
               
        </thead>
        <tbody>
            <tr></tr>
        ';
          
            
               
                   $sumEfficiency = 0;
                $countEntries = 0; 
                $overall_avg=0;
               $empArray=[];
               $AvgSum=0;
               $totalMtd=0;
              $AvgCount=[];
              
              
           $operatorArray = $operatorList instanceof \Illuminate\Support\Collection ? $operatorList->toArray() : $operatorList;

            // Extract operator from the array
           $operatorsArr = array_column($operatorArray, 'employeeCode');
              
              
          $effDetail=DB::table('daily_production_entry_details_operation')
          ->select('ob_details.sam','daily_production_entry_details_operation.pieces',
          'daily_production_entry_details_operation.employeeCode','daily_production_entry_details_operation.daily_pr_date')
           ->join('ob_details', 'ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')  
         ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate,$request->toDate])
          ->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id) 
         ->whereIn('daily_production_entry_details_operation.employeeCode',$operatorsArr)
         ->where('daily_production_entry_details_operation.dept_id',$request->dept_id) 
         ->get();
              
              
                  $dataMap = [];

        foreach ($effDetail as $record) {
        $dataMap[$record->employeeCode][$record->daily_pr_date][] = [
        'sam' => $record->sam,
        'pieces' => $record->pieces
        
        ];
        
        
        }
              
              
              
                  
     foreach($operatorList as $rowOperator)   
     {
         
                $fromDateMonth=date('Y-m-01',strtotime($request->fromDate));
                $toDateMonth=date('Y-m-t',strtotime($fromDateMonth));
         
         
         
            $output.='
        
            <tr class="operator-row">
                
                  <td style="border:1px solid black fillter" nowrap>'.$rowOperator->fullName.'</td>
                  <td style="border:1px solid black fillter;text-align:right">'.$rowOperator->misRate.'</td>';
                
              foreach($period  as $index => $date)   
              {
            
             
                $fromDate=date('Y-m-01',strtotime($date));
                $toDate=date('Y-m-t',strtotime($fromDate));
                
                
                            
                            $proMin=0;
                            
                            if (isset($dataMap[$rowOperator->employeeCode][$date])) {
                            foreach ($dataMap[$rowOperator->employeeCode][$date] as $record) {
                            $proMin+= ($record['sam'] * $record['pieces']);
                           
                            }
                            } 
 

         
         
              
              if($proMin!=0)
              {
                $output.='
                <td class="amount PROM" style="border:1px solid black;text-align:right !important" >'.number_format((float)(($proMin ?? 0)), 0, '.', '').'</td>
                <td class="amount PROEARN" style="border:1px solid black;text-align:right !important" >'.number_format((float)(($proMin ?? 0) * (1.25)),0, '.', '').'</td>
                <td class="amount PL" style="border:1px solid black;text-align:right !important" >'.number_format((float)((($proMin ?? 0) * (1.25))- ($rowOperator->misRate)), 0, '.', '').'</td>
                ';
                
              } else{
               $output.='
               <td class="amount" style="border:1px solid black;text-align:right !important">-</td>
               <td class="amount" style="border:1px solid black;text-align:right !important">-</td>
               <td class="amount" style="border:1px solid black;text-align:right !important">-</td>
               ';
              }
                
               
                   if ($effDetail) {
                        $sumEfficiency += $proMin ?? 0;
                        
                        if($proMin!=0)
                        {
                        $countEntries++;
                        }
                        
                        
                      $empArray[]=$rowOperator->employeeCode;    
               
                    
                    // Calculate the overall average
                  
                        
                    }
           
             
                
              }
              
              
                 $output.='
                <td class="amount TOTALPROMIN" style="border:1px solid black;text-align:right !important" ></td>
                <td class="amount TOTALEARN" style="border:1px solid black;text-align:right !important" ></td>
                <td class="amount TOTALPL" style="border:1px solid black;text-align:right !important" ></td>';
              
                     $output.='

            </tr>';
              
               
               
               $empArrayByDept[]=$rowOperator->employeeCode; 
          
              
               $countEntries=0;$sumEfficiency=0; 
               
               
     }
            $output.='
        </tbody>
        <tfoot>
        <tr>
        <td class="notConsider">Total</td>
        
          <td class=""></td>
        ';
        
        
             foreach($period  as $index => $date)   
              {
            $output.='
            <td class=""></td>
            <td class=""></td>
            <td class=""></td>';
              }
              
                $output.='
            <td class="GRANDPROMIN"></td>
            <td class="GRANDEARN"></td>
            <td class="GRANDPL"></td>';
                
         $output.='</tr>
        <tfoot>
        
        
    </table>
    

  
</div>
</div>';

 
  
  return response()->json(['html'=>$output,'empArrayList'=>$empArray]);
        
    }
    
    

    
    public function line_wise_efficiency(Request $request)
    {
        
        $fromDate=$request->fromDate;
        $toDate=$request->toDate;
        $sub_company_id=$request->sub_company_id;
        
           $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',$sub_company_id)->get();
          
            $period = $this->getBetweenDates($request->fromDate,$request->toDate);
            
            //$this->store_monthly_efficiency($period,$deptlist);
            
            $subfetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();
            
            
            $sub_company_name=$subfetch->sub_company_name;
            
            
       
       return view('Operation.show_line_wise_efficiency_report',compact('fromDate','toDate','deptlist','period','sub_company_id','sub_company_name'));

    }
    
    
    
     public function store_monthly_efficiency($period,$deptList)
    {
        
        
    foreach($deptList as $rowDept) {      
    
    foreach($period as $rowDates) { 
        
        
         DB::table('daily_production_entry_monthly')->where('dept_id',$rowDept->dept_id)->where('daily_pr_date',$rowDates)->delete();
        
        
          $fetchMaster=DB::table('daily_production_entry_masters')->select(DB::raw("sum(overall_efficiency) as overall_efficiency,sum(total_present) as total_present,
          sum(overall_sam) as overall_sam,sum(overall_output) as overall_output"))
         ->where('daily_pr_date',$rowDates)->where('dept_id',$rowDept->dept_id)->first();
         
         
        $producedMin=round(($fetchMaster->overall_output ?? 0)*  ($fetchMaster->overall_sam ?? 0));
        $overall_efficiency=$fetchMaster->overall_efficiency ?? 0;
        $total_present=$fetchMaster->total_present ?? 0; 

       $detail=DB::select("INSERT INTO daily_production_entry_monthly(`daily_pr_date`,dept_id,sub_company_id,`produced_mins`,total_present,`efficiency`) values('".$rowDates."','".$rowDept->dept_id."','".Session::get('sub_company_id')."','".$producedMin."','".$total_present."','".$overall_efficiency."')"); 
  
        }
    }

  
    
    }
    
    
    
    
    
            public function show_unit_wise_mis_chart_yearly(Request $request)
    {
        
          $year=$request->year;
     
        
          $deptlist=DB::table('department_master')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
        
          $unitList=DB::table('sub_company_master')->select('erp_sub_company_id','sub_company_name')->where('delflag',0)->whereIn('erp_sub_company_id',[56,115,110,628,686])->get();
          
 
       
       return view('show_unit_wise_mis_chart_yearly',compact('deptlist','year','unitList'));

    }
    
    
      public function show_unit_wise_mis_report_monthly($year)
    {
        
          
      $MonthList=DB::table('monthMaster')->select('monthId','MonthName')->get();
        
          $deptlist=DB::table('department_master')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
          
          
          $unitList=DB::table('sub_company_master')->select('erp_sub_company_id','sub_company_name')->where('delflag',0)->whereIn('erp_sub_company_id',[56,115,110,628,686])->get();
          
 
       
       return view('show_unit_wise_mis_report_monthly',compact('deptlist','year','unitList','MonthList'));

    }
    
         public function show_unit_wise_mis_report_weekly($month,$year)
    {
        
          
      $MonthList=DB::table('monthMaster')->select('monthId','MonthName')->get();
        
          $deptlist=DB::table('department_master')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
          
          
          $unitList=DB::table('sub_company_master')->select('erp_sub_company_id','sub_company_name')->where('delflag',0)->whereIn('erp_sub_company_id',[56,115,110,628,686])->get();
          
 
       
       return view('show_unit_wise_mis_report_weekly',compact('deptlist','month','year','unitList','MonthList'));

    }
    
             public function show_unit_wise_mis_report_by_dates($fromDate,$toDate)
    {
        
          $period = $this->getBetweenDates($fromDate,$toDate);
        
          
         $MonthList=DB::table('monthMaster')->select('monthId','MonthName')->get();
        
          $deptlist=DB::table('department_master')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
          
          
          $unitList=DB::table('sub_company_master')->select('erp_sub_company_id','sub_company_name')->where('delflag',0)->whereIn('erp_sub_company_id',[56,115,110,628,686])->get();
          
 
       
       return view('show_mis_report_by_dates',compact('deptlist','fromDate','toDate','unitList','MonthList','period'));

    }
    
    
    
        public function show_line_wise_efficiency_yearly(Request $request)
    {
        
        $year=$request->year;
        $sub_company_id=$request->sub_company_id;
     
        
             $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',$sub_company_id)->get();
            $subfetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();
            
            
            $sub_company_name=$subfetch->sub_company_name;
            
            
                $period = $this->getBetweenDates('2024-10-01','2024-10-30');
       
                
          $subCompanyList=DB::table('sub_company_master')->select('sub_company_name')->whereIn('erp_sub_company_id',[56,115,628,110])->get();
        
       
       return view('Operation.show_line_wise_efficiency_yearly_report',compact('deptlist','year','sub_company_id','sub_company_name','period','subCompanyList'));

    }
    
            public function show_line_wise_efficiency_yearly_all_unit(Request $request)
    {
        
        $year=$request->year;
       
     
                
          $subCompanyList=DB::table('sub_company_master')->select('sub_company_name','sub_company_id')->whereIn('erp_sub_company_id',[56,115,628,110])->get();
        
       
       return view('Operation.show_line_wise_efficiency_yearly_report_new',compact('year','subCompanyList'));

    }
    
    
    
    public function show_line_wise_efficiency_monthly($year,$sub_company_id)
    {
        
        
               $MonthList=DB::table('monthMaster')->select('monthId','MonthName')->get();
        
                $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',$sub_company_id)->get();
          
                 $subfetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$sub_company_id)->first();
            
            
            $sub_company_name=$subfetch->sub_company_name;
          
        
       
       return view('Operation.show_line_wise_efficiency_monthly_report',compact('deptlist','MonthList','year','sub_company_id','sub_company_name'));

    }
    
    
        public function show_all_unit_wise_efficiency_monthly($year)
    {
        
        
         $MonthList=DB::table('monthMaster')->select('monthId','MonthName')->get();
      
        
          $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
          
           $subCompanyList=DB::table('sub_company_master')->select('sub_company_name','sub_company_id')->whereIn('erp_sub_company_id',[56,115,628,110])->get();
          
        
       
       return view('Operation.show_all_unitwise_efficiency_monthly',compact('deptlist','MonthList','year','subCompanyList'));

    }
    
    public function show_all_unit_and_datewise_efficiency($fromDate,$toDate)
    {
        
        
         $MonthList=DB::table('monthMaster')->select('monthId','MonthName')->get();
      
        
          $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
          
           $subCompanyList=DB::table('sub_company_master')->select('sub_company_name','sub_company_id')->whereIn('erp_sub_company_id',[56,115,628,110])->get();
          
        
       
       return view('Operation.show_all_unit_and_datewise_efficiency',compact('deptlist','fromDate','toDate','subCompanyList'));

    }
    
    
        
    public function show_line_wise_efficiency_weekly(Request $request)
    {
        
        $fromDate=$request->fromDate;
        $toDate=$request->toDate;
        
            $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
          
             $period = $this->getBetweenDates($request->fromDate,$request->toDate);
        
       
       return view('Operation.show_line_wise_efficiency_weekly_report',compact('fromDate','toDate','deptlist','period'));

    }
    
    
       public function show_line_wise_efficiency_weekly_new($month,$year,$sub_company_id)
    {
        

        
                  $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',$sub_company_id)->get();
          
                 $subfetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$sub_company_id)->first();
            
            
            $sub_company_name=$subfetch->sub_company_name;
        
       
       return view('Operation.show_line_wise_efficiency_weekly_report_new',compact('month','year','deptlist','sub_company_id','sub_company_name'));

    }
    
     public function show_all_unitwise_efficiency_weekly($month,$year)
     {
        
        
      $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
      
        $subCompanyList=DB::table('sub_company_master')->select('sub_company_name','sub_company_id')->whereIn('erp_sub_company_id',[56,115,628,110])->get();
        
       
       return view('Operation.show_all_unitwise_efficiency_weekly',compact('month','year','deptlist','subCompanyList'));

     }
    
    
    
    
       public function show_daily_production(Request $request)
    {
        

                $fromDate=$request->fromDate;
                $dept_id=$request->dept_id;
                $sub_company_id=$request->sub_company_id;
                
                
                $lineFetch= DB::table('line_master')->where('delflag',0)->where('line_id',$dept_id)->first();   
                
                $sub_comp_fetch= DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$sub_company_id)->first();
                
                
                
                $line_name=$lineFetch->line_name;
                
                
                $sub_company_name=$sub_comp_fetch->sub_company_name;
                
                
                
                         $orderNos = DB::table('daily_production_entry_details AS dps')
                         ->select('dps.sales_order_no')
                         ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
                         ->join('ob_details', 'ob_details.operation_id', '=', 'dps.operationNameId')  
                         ->where('dps.line_no', $dept_id)   
                         ->where('dps.vendorId',$sub_company_id)     
                         ->where('dps.dailyProductionEntryDate',$fromDate)
                         ->groupBy('dps.sales_order_no')
                         ->get();
                
                        $sales_order_noArray = $orderNos instanceof \Illuminate\Support\Collection ? $orderNos->toArray() : $orderNos;

                       $orderNoLists = array_column($sales_order_noArray, 'sales_order_no');
                
                
                         $multiStyleFetchPCS=DB::table('assigned_to_orders')->selectRaw("main_style_master_operation.mainstyle_name")
                        ->join('main_style_master_operation','main_style_master_operation.mainstyle_id','=','assigned_to_orders.mainstyle_id_operation')
                        ->whereIn('assigned_to_orders.sales_order_no',$orderNoLists)   
                        ->get();
                  
                        $styleArrayPCS=[];
        
                        foreach($multiStyleFetchPCS as $rowFetchStyle)
                        {
                        
                        $styleArrayPCS[]=$rowFetchStyle->mainstyle_name;
                        
                        }
               
       
        
         return view('Operation.show_daily_production',compact('fromDate','sub_company_id','sub_company_name','dept_id','line_name','styleArrayPCS'));

    }
    
    
          public function show_average_effi_by_operator(Request $request)
    {
        

        $fromDate=$request->fromDate;
        $dept_id=$request->dept_id;
        $sub_company_id=$request->sub_company_id;
       
           
       $lineList=DB::table('daily_production_entry_details_operation')->select('daily_production_entry_details_operation.dept_id','department_master.dept_name')
       ->join('department_master','department_master.dept_id','=','daily_production_entry_details_operation.dept_id')
       ->where('daily_production_entry_details_operation.dept_id',$dept_id)
       ->where('daily_production_entry_details_operation.sub_company_id',$sub_company_id)
       ->groupBy('daily_production_entry_details_operation.dept_id')
       ->get(); 
        
               $sub_comp_fetch= DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$sub_company_id)->first();
               
               
                $sub_company_name=$sub_comp_fetch->sub_company_name;
       
        
         return view('show_average_efficiency_by_operator',compact('fromDate','lineList','sub_company_id','sub_company_name'));

    }
    
           public function show_top_n_bottom_n_efficiency(Request $request)
    {
        

        $fromDate=$request->fromDate;
        $toDate=$request->toDate;  
        $dept_id=$request->dept_id;
        $top_n=$request->top_n; 
        $bottom_n=$request->bottom_n;  
        
        //count($dept_id); exit;
        
        
        $query = DB::table('daily_production_entry_details_operation')
         ->select('daily_production_entry_details_operation.dept_id', 'line_master.line_name',
         DB::raw('(ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (CASE 
            WHEN is_half_day = 1 THEN 2.4 
            ELSE 4.8 
        END),2) / COUNT(DISTINCT(daily_production_entry_details_operation.daily_pr_date)))  as avg_efficiency'), 'employeemaster_operation.fullName')
            ->join('ob_details', function ($join) {
          $join->on('ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')
         ->whereColumn('ob_details.mainstyle_id', '=', 'daily_production_entry_details_operation.mainstyle_id');
            })
          ->join('line_master', 'line_master.line_id', '=', 'daily_production_entry_details_operation.dept_id')
        ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry_details_operation.employeeCode'); 
        if(count($dept_id)==0) {
  
        $query->whereBetween('daily_production_entry_details_operation.daily_pr_date', [$fromDate, $toDate]);
        } else {
        $query->whereIn('daily_production_entry_details_operation.dept_id', $dept_id)
        ->whereBetween('daily_production_entry_details_operation.daily_pr_date', [$fromDate, $toDate]);
        }
        
         $topList = $query->where('daily_production_entry_details_operation.efficiency','>',0);
         $topList = $query->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id);  
        // $topList = $query->groupBy('employeemaster_operation.fullName','line_master.line_name','line_master.line_id');
         $bottomList = $query->groupBy('employeemaster_operation.employeeCode');
         $topList = $query->orderBy('avg_efficiency', 'DESC');
         $topList=$query->limit($top_n);
         $topList=$query->get(); 
         
         
         //DB::enableQueryLog();
         
            $query1 = DB::table('daily_production_entry_details_operation')
         ->select('daily_production_entry_details_operation.dept_id', 'line_master.line_name',
         DB::raw('(ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (CASE 
            WHEN is_half_day = 1 THEN 2.4 
            ELSE 4.8 
        END),2) / COUNT(DISTINCT(daily_production_entry_details_operation.daily_pr_date))) as avg_efficiency_bottom'), 'employeemaster_operation.fullName')
               ->join('ob_details', function ($join) {
          $join->on('ob_details.operation_id', '=', 'daily_production_entry_details_operation.operation_id')
         ->whereColumn('ob_details.mainstyle_id', '=', 'daily_production_entry_details_operation.mainstyle_id');
            })
          ->join('line_master', 'line_master.line_id', '=', 'daily_production_entry_details_operation.dept_id')
        ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry_details_operation.employeeCode'); 
        if(count($dept_id)==0) {
  
        $query1->whereBetween('daily_production_entry_details_operation.daily_pr_date', [$fromDate, $toDate]);
        } else {
        $query1->whereIn('daily_production_entry_details_operation.dept_id', $dept_id)
        ->whereBetween('daily_production_entry_details_operation.daily_pr_date', [$fromDate, $toDate]);
        }
        
         $bottomList = $query1->where('daily_production_entry_details_operation.efficiency','!=',0);
         $bottomList = $query1->where('daily_production_entry_details_operation.sub_company_id',$request->sub_company_id);     
        // $bottomList = $query1->groupBy('employeemaster_operation.fullName','line_master.line_name','line_master.line_id');
         $bottomList = $query1->groupBy('employeemaster_operation.employeeCode');
         $bottomList = $query1->orderBy('avg_efficiency_bottom', 'ASC');
         $bottomList=$query1->limit($bottom_n);
         $bottomList=$query1->get(); 
         //dd(DB::getQueryLog());
         
         
              $sub_fetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();
           
           
               $deptList=DB::table('line_master')->select('line_name')->whereIn('line_id',$dept_id)->get();
            
                $deptArray = $deptList instanceof \Illuminate\Support\Collection ? $deptList->toArray() : $deptList;

              // Extract operator from the array
               $deptArr = array_column($deptArray, 'line_name');
           
        
         return view('Operation.show_top_n_bottom_n_efficiency',compact('fromDate','toDate','top_n','bottom_n','dept_id','bottomList','topList','sub_fetch','deptArr'));

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
           
             
          $data = $request->all();
          
          DB::beginTransaction();
           
           $id = $data['id'] ?? null;
           
          $is_style_change = $request->has('is_style_change') ? 1 : 0;
   
          
          
          $data['is_style_change']=$is_style_change;
          $is_half_day_input = $request->is_half_day;
       
        
            $IODetails = DailyProductionEntryOperationModel::updateOrCreate(
                ['daily_pr_entry_id'=> $id],
                $data);
                
                
                
                  if($id == null){
             $DPE_id=DailyProductionEntryOperationModel::max('daily_pr_entry_id');
            } else{

                $DPE_id=$id; 
            }
                
                
                
           $operation_id = $request->input('operation_id');
         
           
            if(!empty($operation_id))
            {

                 DailyProductionEntryDetailOperationModel::where('daily_pr_entry_id',$DPE_id)->delete();
            
               
                $data1=array();
                $EMPArray=[];
            for($x=0; $x<count($operation_id); $x++) {
                
            //       $isExist= DailyProductionEntryDetailOperationModel::where('daily_pr_date',$request->daily_pr_date)
            //   ->where('employeeCode',$request->employeeCode[$x])->where('operation_id',$request->operation_id[$x])
            //   ->where('efficiency',$request->efficiency[$x])->where('pieces',$request->pieces[$x])->get();  
               
            //      if(count($isExist) > 0)
            //      {
                     
                     
            //         $EMPArray[]=$request->employeeCode[$x];
                     
                     
            //      }else{  
                
            $data1[]=array(
              'daily_pr_entry_id'=>$DPE_id,  
              'daily_pr_date'=>$request->daily_pr_date,  
              'sub_company_id'=>$request->sub_company_id,
              'operation_id'=>$request->operation_id[$x],
              'operation_name'=>$request->operation_name[$x], 
              'employeeCode'=>$request->employeeCode[$x],   
              'mainstyle_id'=>$request->mainstyle_id,
              'dept_id'=>$request->dept_id, 
               'sam'=>$request->sam[$x],  
              'pieces'=>$request->pieces[$x],
              'efficiency'=>$request->efficiency[$x],
              'station_no'=>$request->station_no[$x],
             'remark'=>$request->remark[$x],
            'is_half_day'=> isset($is_half_day_input[$x]) ? 1 : 0, 
            );
            
              //}   
          
            }
    
            
            // if(count($EMPArray) > 0)
            // {
            //  Convert EMPArray to a comma-separated string
            // $EMPArrayString = implode(', ', $EMPArray);
            
            //  Construct the alert message
            // $msg = "Failed to save! Duplicate Entry For - " . $EMPArrayString;
            
            //  Output the JavaScript alert
            // echo "<script type='text/javascript'>"
            // . "alert('Message:: " . addslashes($msg) . "');"
            // . "</script>";
            
            //   DB::rollBack();
            
            // DailyProductionEntryOperationModel::where('daily_pr_entry_id',$DPE_id)->where('daily_pr_date',$request->daily_pr_date)->delete();
            // }
             
            DailyProductionEntryDetailOperationModel::insert($data1);
          
        }
            DB::commit();
            $msg = "";

            if($id == null){
                $msg = 'Daily production saved successfully';
            } else {
                $msg = 'Daily production updated successfully';
            }


         return redirect()->route('daily_production_entry.index')->with('message', $msg);
         
         } catch (\Exception $e) {
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
  
      DB::rollBack();
  
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
 
    }
    }
    
    
    public function get_operation_ids_by_operator(Request $request)
    {
        
        return response()->json(['html' => $this->get_operation_list_by_operator($request->employeeCode)]);

    }
    
        public function get_group_ids_by_line(Request $request)
    {
        
        return response()->json(['html' => $this->get_group_ids_by_line_trait($request->dept_id,$request->daily_pr_date,$request->mainstyle_id)]);

    }
    
    public function get_daily_production_table(Request $request){
        
        
            $master=DailyProductionEntryOperationModel::
            select('total_efficiency')
          ->where("daily_pr_entry_id",$request->daily_pr_entry_id)->first();
        
        
      $result=DailyProductionEntryDetailOperationModel::
          select('department_master.dept_name','main_style_master_operation.mainstyle_name','daily_production_entry_details_operation.operation_id','daily_production_entry_details_operation.sam',
          'daily_production_entry_details_operation.pieces','daily_production_entry_details_operation.efficiency','employeemaster_operation.fullName','daily_production_entry_details_operation.employeeCode')
          ->join('department_master','department_master.dept_id','=','daily_production_entry_details_operation.dept_id')
          ->join('main_style_master_operation','main_style_master_operation.mainstyle_id','=','daily_production_entry_details_operation.mainstyle_id') 
            ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode') 
          ->where("daily_pr_entry_id",$request->daily_pr_entry_id)->get();
          
          
        
         $html="";
        
        if(count($result) > 0)
        {
       
        
$html.='        
    <table class="table table-striped" id="stickyHeader">
    <thead>
      <tr>
        <th scope="col">Line</th>
        <th scope="col">Operator</th>     
        <th scope="col">Style</th>
        <th scope="col">Operation</th>
        <th scope="col">SAM</th> 
        <th scope="col">Output</th>   
       <th scope="col">Efficiency%</th>      
      </tr>
    </thead>
    <tbody>';
    
    foreach($result as $row)
    {
    $html.='    <tr>
        <td>'.$row->dept_name.'</td>
        <td>'.$row->fullName.'('.$row->employeeCode.')</td>  
        <td>'.$row->mainstyle_name.'</td>
        <td>'.$row->operation_id.'</td>
        <td>'.$row->sam.'</td>
        <td>'.$row->pieces.'</td>
        <td>'.$row->efficiency.'</td>
      </tr>';
    }
    
   $html.='</tbody>
   
   <tfoot>
     <tr>
      <td></td> 
        <td></td> 
          <td></td> 
            <td></td> 
      <td>Total</td> 
     <td>'.$master->total_efficiency.'</td> 
     </tr>
   </tfoot>
   
   
  </table> ';   
        } else{
            
            
            $html.="No record found...!!";
            
        }
  
  return response()->json($html);
            
        
        
        
    }
    
    
        public function get_daily_production_table_by_operator(Request $request){
        
        
        
        $maxLineDate = DB::table('line_plan_masters')
    ->where('mainstyle_id', $request->mainstyle_id)
     ->where('dept_id',$request->dept_id)
    ->where('sub_company_id', Session::get('vendorId'))
    ->max('line_date');
        
        
        
           $data = DB::table('line_plan_detail')
     ->select("operation_id","operation_name","sam","line_plan_masters.mainstyle_id","line_plan_masters.dept_id","line_plan_detail.employeeCode",'line_plan_detail.line_plan_id')
     ->join('line_plan_masters','line_plan_masters.line_plan_id','=','line_plan_detail.line_plan_id')
     ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','line_plan_detail.employeeCode')
     ->where('line_plan_detail.dept_id',$request->dept_id)->where('line_plan_masters.mainstyle_id',$request->mainstyle_id)
     ->where('line_plan_masters.sub_company_id',Session::get('vendorId'))
     ->where('line_plan_masters.line_date', $maxLineDate);

    
    
     if($request->group_id==7)
    {
     $data->where('line_plan_detail.group_id','!=',3);
     $data->orderBy('line_plan_detail.sr_no','ASC');
    } else{
        
     $data->where('line_plan_detail.group_id',$request->group_id);
     $data->orderBy('line_plan_detail.sr_no','ASC');     
    }
     
     $result= $data->get();
     
    
    
          $dept_list = DB::table('department_master')->where('delflag',0)->whereIn('dept_id',[65,66,67,68])->get();
          $employeelist=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')
          ->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[67,64,71,79,112,106])->get(); 
          
          
          //$operationList=DB::table('ob_masters')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
         
         $operationList=DB::table('ob_details')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
          
          
          $styleList = DB::table('main_style_master_operation')->Select('*')->get();    

         
         $html="";
         
           $html.='<table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead>
                              <tr>
                             <th class="text-center">Sr.No.</th>    
                              <th class="text-center">Half Day</th>   
                                 <th class="text-center">Operator</th>   
                                    <th class="text-center">Operation</th>
                                    <th class="text-center">Sam</th> 
                                <th class="text-center">Output</th>   
                                 <th class="text-center">Remark</th>    
                                 <th class="text-center">Add</th>
                                <th class="text-center">Remove</th>  
                              </tr>
                           </thead>
                           <tbody id="tbodyData">';
                           
                           $no=1;
    
    foreach($result as $index => $row)
    {
    $html.=' <tr class="rowcheck" id="tbodyData">
    
                               <td style="text-align:center;">
                             <input type="number" style="text-align:center;width:50px" readOnly name="srNo[]" value="'.$no++.'">
                              </td>
     
                              <td>
                             <input type="checkbox" name="is_half_day['.$index.']" value="1">
                          
                           </td>
                                 
                                 <td>
                                  <select class="form-control"  name="employeeCode[]"  style="width:300px" id="employeeCode" required onChange="previousData(this);">
                           <option value="">--- Select---</option>';  
                           foreach($employeelist as $rowemp)
                           {
                           $html.='<option value="'.$rowemp->employeeCode.'"';
                           
                          $rowemp->employeeCode== $row->employeeCode ? $html.="selected='selected'" : ""; 
                           
                            $html.='>'.$rowemp->fullName.'('.$rowemp->employeeCode.')</option>';
                           }
                        $html.='</select>';     
                                     
                                 $html.='</td>
                                  
                               <td>
                            <select class="form-control CAT required" required style="width:300px"  name="operation_id[]" id="operation_id" onChange="get_detail(this,this.value);previousData(this);">
                            ';
                            foreach($operationList as $operation)
                            {
                            $html.='<option value="'.$operation->operation_id.'"'; 
                            
                            $row->operation_id== $operation->operation_id ? $html.="selected='selected'" : ""; 
                            
                            
                            $html.='>'.$operation->operation_id.'('.$operation->operation_name.')</option>';
                            }
                            $html.='</select>   
                                 </td>
 
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control required SAM" disabled required  name="sam[]" id="sam"  value="'.$row->sam.'" style="width:80px;">
                                   <input type="hidden"   class="form-control"   name="operation_name[]" id="operation_name"  value="'.$row->operation_name.'" style="width:100px;">
                                    <input type="hidden"   class="form-control"   name="station_no[]" id="station_no"  value="'.$row->line_plan_id.'" style="width:100px;">
                                 </td>   
                             
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control required PIECES" required  name="pieces[]" id="pieces"  value="" style="width:80px;" onBlur="checkExist(this,this.value)">
                                
                                 <input type="hidden" step="any" min="0"   class="form-control required EFFICIENCY" required  name="efficiency[]" id="efficiency"  value="" style="width:100px;">
                                 </td>   
                                <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:200px;">
                                 </td>    
                                 
                                 <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>
                              ';



}

                          $html.='</tbody>
                         </table>';
    
  
  return response()->json($html);

        
    }
    
     public function get_daily_production_table_by_operator_new(Request $request){
        
        
        
        $maxLineDate = DB::table('line_plan_masters')
    ->where('mainstyle_id', $request->mainstyle_id)
    ->where('sub_company_id', Session::get('vendorId'))
    ->max('line_date');
        
        
        
           $data = DB::table('line_plan_detail')
     ->select("operation_id","operation_name","sam","line_plan_masters.mainstyle_id","line_plan_masters.dept_id","line_plan_detail.employeeCode",'line_plan_detail.line_plan_id')
     ->join('line_plan_masters','line_plan_masters.line_plan_id','=','line_plan_detail.line_plan_id')
     ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','line_plan_detail.employeeCode')
     ->where('line_plan_detail.dept_id',$request->dept_id)->where('line_plan_masters.mainstyle_id',$request->mainstyle_id)
     ->where('line_plan_masters.sub_company_id',Session::get('vendorId'))
     ->where('line_plan_masters.line_date', $maxLineDate);

    
    
     if($request->group_id==7)
    {
     $data->where('line_plan_detail.group_id','!=',3);
     $data->orderBy('line_plan_detail.sr_no','ASC');
    } else{
        
     $data->where('line_plan_detail.group_id',$request->group_id);
     $data->orderBy('line_plan_detail.sr_no','ASC');     
    }
     
     $result= $data->get();
     
    
    
          $dept_list = DB::table('department_master')->where('delflag',0)->whereIn('dept_id',[65,66,67,68])->get();
          $employeelist=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[67,64,71,79,106])->get(); 
          
          
          //$operationList=DB::table('ob_masters')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
         
         $operationList=DB::table('ob_details')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
          
          
          $styleList = DB::table('main_style_master_operation')->Select('*')->get();    

         
         $html="";
         
           $html.='<table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead>
                              <tr>
                                 <th class="text-center">Operator</th>   
                                    <th class="text-center">Operation</th>
                                  <th class="text-center">Target/HR</th> 
                                 <th class="text-center">Target/Day</th>    
                                 <th class="text-center">9-10</th>    
                                <th class="text-center">10-11</th>    
                               <th class="text-center">11-12</th>    
                               <th class="text-center">12-1</th>     
                             <th class="text-center">1.30-2.30</th>    
                               <th class="text-center">2.30-3.30</th>      
                             <th class="text-center">3.30-4.40</th>   
                             <th class="text-center">4.40-5.40</th>   
                               <th class="text-center">Total</th>  
                              <th class="text-center">Remark</th>     
                                  <th class="text-center">Add</th>
                                 <th class="text-center">Remove</th>  
                                 </tr>
                                 </thead>
                                <tbody id="tbodyData">';
    
    foreach($result as $row)
    {
    $html.=' <tr class="rowcheck" id="tbodyData">
     
                                 
                                 <td>
                                  <select class="form-control"  name="employeeCode[]"  style="width:300px" id="employeeCode" required onChange="previousData(this);">
                           <option value="">--- Select---</option>';  
                           foreach($employeelist as $rowemp)
                           {
                           $html.='<option value="'.$rowemp->employeeCode.'"';
                           
                          $rowemp->employeeCode== $row->employeeCode ? $html.="selected='selected'" : ""; 
                           
                            $html.='>'.$rowemp->fullName.'('.$rowemp->employeeCode.')</option>';
                           }
                        $html.='</select>';     
                                     
                                 $html.='</td>
                                  
                               <td>
                            <select class="form-control CAT required" required style="width:300px"  name="operation_id[]" id="operation_id" onChange="get_detail(this,this.value);previousData(this);">
                            ';
                            foreach($operationList as $operation)
                            {
                            $html.='<option value="'.$operation->operation_id.'"'; 
                            
                            $row->operation_id== $operation->operation_id ? $html.="selected='selected'" : ""; 
                            
                            
                            $html.='>'.$operation->operation_id.'('.$operation->operation_name.')</option>';
                            }
                            $html.='</select>   
                                 </td>
 
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control required SAM" disabled required  name="sam[]" id="sam"  value="'.$row->sam.'" style="width:80px;">
                                   <input type="hidden"   class="form-control"   name="operation_name[]" id="operation_name"  value="'.$row->operation_name.'" style="width:100px;">
                                    <input type="hidden"   class="form-control"   name="station_no[]" id="station_no"  value="'.$row->line_plan_id.'" style="width:100px;">
                                 </td>   
                             
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control required PIECES" required  name="pieces[]" id="pieces"  value="" style="width:80px;" onBlur="checkExist(this,this.value)">
                                
                                 <input type="hidden" step="any" min="0"   class="form-control required EFFICIENCY" required  name="efficiency[]" id="efficiency"  value="" style="width:100px;">
                                 </td>   
                               <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:90px;">
                                 </td>   
                                 
                                 <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>
                              ';



}

                          $html.='</tbody>
                         </table>';
    
  
  return response()->json($html);

        
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DailyProductionEntryOperationModel  $DailyProductionEntryOperationModel
     * @return \Illuminate\Http\Response
     */
    public function show(DailyProductionEntryOperationModel $DailyProductionEntryOperationModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DailyProductionEntryOperationModel  $DailyProductionEntryOperationModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         
           $emp_code_list = DB::table('employeemaster_operation')->where('maincompany_id',Session::get('maincompany_id'))->where('delflag',0)->where('egroup_id',42)->get();
         $dept_list = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
        $location_list = DB::connection('hrms_database')->table('maincompany_master')->select('maincompany_id','maincompany_name')->where('delflag',0)->get();
        $qualification_list = DB::connection('hrms_database')->table('qualification_master')->where('delflag',0)->get();
        $branch_list = DB::table('branch_master')->where('delflag',0)->get();
        $empcategoryList=DB::connection('hrms_database')->table('emp_category_master')->select('emp_cat_id','emp_cat_name')->where('emp_cat_id','!=',1)->where('delflag',0)->get();
        
        
        $datafetch=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')
          ->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[67,71,64,79,112,106,73]);
          
          
         $employeelist=$datafetch->get(); 
         
          
        
        $styleList = DB::table('main_style_master_operation')->Select('*')->get();    
        $groupList = DB::table('group_masters')->Select('*')->get();  
        $machineTypeList = DB::table('machine_type_masters')->Select('*')->get(); 
         
         
            $dailyFetch =DailyProductionEntryOperationModel::find($id);
           
          // $operationList=DB::table('ob_masters')->select('operation_id','operation_name')->where('mainstyle_id',$dailyFetch->mainstyle_id)->get();      
          
           $operationList=DB::table('ob_details')->select('operation_id','operation_name')->where('mainstyle_id',$dailyFetch->mainstyle_id)->get(); 
           
           
           //DB::enableQueryLog();
           $dailyFetchDetail =DailyProductionEntryDetailOperationModel::where("daily_pr_entry_id",$id)->get();
         //dd(DB::getQueryLog());
        
        return view('Operation.daily_production_entry',compact('emp_code_list','dept_list','location_list','qualification_list','branch_list','empcategoryList','operationList','styleList','groupList','machineTypeList','employeelist','dailyFetch','dailyFetchDetail'));

   
    }
    
    
        public function get_operators(Request $request)
    {
        
        return response()->json(['html' => $this->get_operator_list($request->dept_id,$request->mainstyle_id)]);

    }
        public function get_operators_list(Request $request)
    {
        
       
        
        return response()->json(['html' => $this->get_operators_trait($request->dept_id)]);

    }
        public function get_line_list(Request $request)
    {
        
       
        
        return response()->json(['html' => $this->get_Line_trait($request->sub_company_id)]);

    }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyProductionEntryOperationModel  $DailyProductionEntryOperationModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
   
   


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DailyProductionEntryOperationModel  $DailyProductionEntryOperationModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        DailyProductionEntryOperationModel::where('daily_pr_entry_id',$id)->delete();
        DailyProductionEntryDetailOperationModel::where('daily_pr_entry_id',$id)->delete(); 
        
        return redirect()->route('daily_production_entry.index')->with('message', 'Delete Record Succesfully');


    }
    
    public function GetEmpDetailFromEmpCode(Request $request)
    {
        $data=EmployeeModel::select("employeemaster_operation.employeeCode","employeemaster_operation.fullName", "employeemaster_operation.dept_id","employeemaster_operation.sub_company_id","sub_company_master.sub_company_name","department_master.dept_name")
        ->leftJoin('department_master','department_master.dept_id', '=','employeemaster_operation.dept_id')
        ->leftJoin('sub_company_master','sub_company_master.sub_company_id', '=','employeemaster_operation.sub_company_id')
        ->where('employeemaster_operation.employeeCode','=', $request->employeeCode)
        ->first(); 
        return response()->json(['employeeCode' => $data->employeeCode,'fullName' => $data->fullName,'dept_id' => $data->dept_id,'sub_company_id' => $data->sub_company_id,'sub_company_name' => $data->sub_company_name,'dept_name' => $data->dept_name]);
    }    
    
       public function get_over_all_sam(Request $request)
    {
        
        $data=DB::table('department_master')->select("over_all_sam")
        ->where('dept_id',$request->dept_id)
        ->first(); 
        
        return response()->json($data);
    } 
    
    
    
    
    public function GetNewJobOpeningReport(Request $request)
    {
        $employeelist=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')->where('employee_status_id','!=',3)->get();
        
        return view('GetNewJobOpeningReport',compact('employeelist'));
        
    } 
    
    public function rptNewJobOpeningDailyReport(Request $request)
    {
        $DailyReportData = DB::table('new_job_opening_form')->select('new_job_opening_form.*','hiring.fullName as hiring_manager_name',
                    'reporting.fullName as reporting_manager_name',"locationMaster.location","department_master.dept_name","qualification_master.qualification")
                 ->leftJoin('employeemaster_operation as hiring','hiring.employeeCode', '=','new_job_opening_form.hiring_manager')
                 ->leftJoin('employeemaster_operation as reporting','reporting.employeeCode', '=','new_job_opening_form.reporting_manager')
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
    
    
    public function get_operator_detail(Request $request)
    {
       
    $FetchDetail = DB::table('line_plan_detail')
    ->select("operation_id","operation_name","sam","line_plan_masters.mainstyle_id")
    ->join('line_plan_masters','line_plan_masters.line_plan_id','=','line_plan_detail.line_plan_id')
    ->where('employeeCode',$request->employeeCode)->first();
        
    return response()->json($FetchDetail);
    }
    
    
    public function get_selected_operator(Request $request)
    {
        
     $employeelist=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')->whereNotIn('employee_status_id',[3,4])->where('egroup_id',71)->get(); 
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
    
    
    
     public function get_range_wise_operators(Request $request)
    { 
        
       $minRange=$request->minRange;
       $maxRange=$request->maxRange;
       $Rangedate=$request->Rangedate; 
       $dept_id=$request->dept_id; 
       
       $empArray= array_unique($request->empArray);
       
       
       
       
       if($dept_id==0)
       {
           
          
           
        if($request->flag==2)
        {
            
            //DB::enableQueryLog();

        $data = DB::table('daily_production_entry_details_operation')
         ->select('employeemaster_operation.fullName',
        DB::raw('ROUND(SUM(daily_production_entry_details_operation.efficiency),2) AS efficiency'),
        DB::raw('(
            WITH DailyAverages AS (
                SELECT
                    ed.daily_pr_date,
                    ROUND(SUM(ed.efficiency),2) AS daily_avg
                FROM
                    daily_production_entry_details_operation ed
                INNER JOIN employeemaster_operation ON employeemaster_operation.employeeCode = daily_production_entry_details_operation.employeeCode
                WHERE
                    ed.dept_id= "'.$dept_id.'" AND 
                    ed.daily_pr_date BETWEEN "'.$request->fromDate.'" AND "'.$request->toDate.'"
                    AND ed.efficiency > 0 AND ed.employeeCode=employeemaster_operation.employeeCode
               
                GROUP BY
                    ed.daily_pr_date
            )
            SELECT
                ROUND(AVG(daily_avg),2)
            FROM
                DailyAverages
        ) AS overall_avg')
    )
    ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry_details_operation.employeeCode')
    ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate, $request->toDate])
      ->whereIn('daily_production_entry_details_operation.employeeCode',$empArray)
    ->groupBy('daily_production_entry_details_operation.employeeCode');
    
    
     if($Rangedate==0)
         {
             
      if($minRange==0)
         {        
             
      $rangeList=$data->havingRaw("overall_avg >'".$maxRange."'");
    
         } else{
             
                 $rangeList=$data->havingRaw("overall_avg >= $minRange AND overall_avg < $maxRange");
         }
    
         } 
    
    $rangeList=$data->get();
        
                  //dd(DB::getQueryLog());
           
        } elseif($request->flag==3){
            
            
            //DB::enableQueryLog();
            
        $data = DB::table('daily_production_entry_details_operation')
         ->select('employeemaster_operation.fullName',
        DB::raw('ROUND(SUM(daily_production_entry_details_operation.efficiency),2)  AS efficiency'),
        DB::raw('(
            WITH DailyAverages AS (
                SELECT
                    ed.daily_pr_date,
                    ROUND(SUM(ed.efficiency),2) AS daily_avg
                FROM
                    daily_production_entry_details_operation ed
                INNER JOIN employeemaster_operation ON employeemaster_operation.employeeCode = daily_production_entry_details_operation.employeeCode
                WHERE
                    ed.dept_id= "'.$dept_id.'" AND 
                    ed.daily_pr_date BETWEEN "'.date('Y-m-01',strtotime($request->fromDate)).'" AND "'.date('Y-m-t',strtotime($request->fromDate)).'"
                    AND ed.efficiency > 0 AND ed.employeeCode=employeemaster_operation.employeeCode
               
                GROUP BY
                    ed.daily_pr_date
            )
            SELECT
                ROUND(AVG(daily_avg),2)
            FROM
                DailyAverages
        ) AS overall_avg')
    )
    ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry_details_operation.employeeCode')
    ->whereBetween('daily_production_entry_details_operation.daily_pr_date', [date('Y-m-01',strtotime($request->fromDate)),date('Y-m-t',strtotime($request->fromDate))])
    ->whereIn('daily_production_entry_details_operation.employeeCode',$empArray)
    ->groupBy('daily_production_entry_details_operation.employeeCode');
    
    
     if($Rangedate==0)
         {
             
      if($minRange==0)
         {        
             
      $rangeList=$data->havingRaw("overall_avg >'".$maxRange."'");
    
         } else{
             
                 $rangeList=$data->havingRaw("overall_avg >= $minRange AND overall_avg < $maxRange");
         }
    
         } 
    
    $rangeList=$data->get();
            
             //dd(DB::getQueryLog());
        }
        else{
            
          
            
              // DB::enableQueryLog();
               $data=DB::table('daily_production_entry_details_operation')
        ->select('employeemaster_operation.fullName',DB::raw('ROUND(sum(daily_production_entry_details_operation.efficiency),2) as overall_avg'))
        ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode');
     
         
         if($Rangedate==0)
         {
             
         if($minRange==0)
         {
          $rangeList=$data->whereBetween('daily_pr_date',[$request->fromDate,$request->toDate]);
          
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');     
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >$maxRange");
         } else{
           $rangeList=$data->whereBetween('daily_pr_date',[$request->fromDate,$request->toDate]);
      
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');        
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >=$minRange AND SUM(daily_production_entry_details_operation.efficiency) < $maxRange");
         }      
           
         }
         else{
         if($minRange==0)
         {
            $rangeList=$data->where('daily_pr_date',$Rangedate);
        
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');     
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >$maxRange");
         } else{
                    $rangeList=$data->where('daily_pr_date',$Rangedate);
  
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');        
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >=$minRange AND SUM(daily_production_entry_details_operation.efficiency) < $maxRange");
         }
         }
         
         
           $rangeList=$data->get();    
            
             //dd(DB::getQueryLog());
        }  
           
           
          
           
           
           
       } else{
       
       
        if($request->flag==2)
        {
            
            //DB::enableQueryLog();

        $data = DB::table('daily_production_entry_details_operation')
         ->select('employeemaster_operation.fullName',
        DB::raw('ROUND(SUM(daily_production_entry_details_operation.efficiency),2) AS efficiency'),
        DB::raw('(
            WITH DailyAverages AS (
                SELECT
                    ed.daily_pr_date,
                    ROUND(SUM(ed.efficiency),2) AS daily_avg
                FROM
                    daily_production_entry_details_operation ed
                INNER JOIN employeemaster_operation ON employeemaster_operation.employeeCode = daily_production_entry_details_operation.employeeCode
                WHERE
                    ed.dept_id= "'.$dept_id.'" AND 
                    ed.daily_pr_date BETWEEN "'.$request->fromDate.'" AND "'.$request->toDate.'"
                    AND ed.efficiency > 0 AND ed.employeeCode=employeemaster_operation.employeeCode
               
                GROUP BY
                    ed.daily_pr_date
            )
            SELECT
                ROUND(AVG(daily_avg),2)
            FROM
                DailyAverages
        ) AS overall_avg')
    )
    ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry_details_operation.employeeCode')
    ->whereBetween('daily_production_entry_details_operation.daily_pr_date',[$request->fromDate, $request->toDate])
    ->where('daily_production_entry_details_operation.dept_id', $dept_id)
      ->whereIn('daily_production_entry_details_operation.employeeCode',$empArray)
    ->groupBy('daily_production_entry_details_operation.employeeCode');
    
    
     if($Rangedate==0)
         {
             
      if($minRange==0)
         {        
             
      $rangeList=$data->havingRaw("overall_avg >'".$maxRange."'");
    
         } else{
             
                 $rangeList=$data->havingRaw("overall_avg >= $minRange AND overall_avg < $maxRange");
         }
    
         } 
    
    $rangeList=$data->get();
        
                  //dd(DB::getQueryLog());
           
        } elseif($request->flag==3){
            
            
            //DB::enableQueryLog();
            
        $data = DB::table('daily_production_entry_details_operation')
         ->select('employeemaster_operation.fullName',
        DB::raw('ROUND(SUM(daily_production_entry_details_operation.efficiency),2)  AS efficiency'),
        DB::raw('(
            WITH DailyAverages AS (
                SELECT
                    ed.daily_pr_date,
                    ROUND(SUM(ed.efficiency),2) AS daily_avg
                FROM
                    daily_production_entry_details_operation ed
                INNER JOIN employeemaster_operation ON employeemaster_operation.employeeCode = daily_production_entry_details_operation.employeeCode
                WHERE
                    ed.dept_id= "'.$dept_id.'" AND 
                    ed.daily_pr_date BETWEEN "'.date('Y-m-01',strtotime($request->fromDate)).'" AND "'.date('Y-m-t',strtotime($request->fromDate)).'"
                    AND ed.efficiency > 0 AND ed.employeeCode=employeemaster_operation.employeeCode
               
                GROUP BY
                    ed.daily_pr_date
            )
            SELECT
                ROUND(AVG(daily_avg),2)
            FROM
                DailyAverages
        ) AS overall_avg')
    )
    ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry_details_operation.employeeCode')
    ->whereBetween('daily_production_entry_details_operation.daily_pr_date', [date('Y-m-01',strtotime($request->fromDate)),date('Y-m-t',strtotime($request->fromDate))])
    ->where('daily_production_entry_details_operation.dept_id', $dept_id)
    ->whereIn('daily_production_entry_details_operation.employeeCode',$empArray)
    ->groupBy('daily_production_entry_details_operation.employeeCode');
    
    
     if($Rangedate==0)
         {
             
      if($minRange==0)
         {        
             
      $rangeList=$data->havingRaw("overall_avg >'".$maxRange."'");
    
         } else{
             
                 $rangeList=$data->havingRaw("overall_avg >= $minRange AND overall_avg < $maxRange");
         }
    
         } 
    
    $rangeList=$data->get();
            
             //dd(DB::getQueryLog());
        }
        else{
            
          
            
              // DB::enableQueryLog();
               $data=DB::table('daily_production_entry_details_operation')
        ->select('employeemaster_operation.fullName',DB::raw('ROUND(sum(daily_production_entry_details_operation.efficiency),2) as overall_avg'))
        ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode');
     
         
         if($Rangedate==0)
         {
             
         if($minRange==0)
         {
          $rangeList=$data->whereBetween('daily_pr_date',[$request->fromDate,$request->toDate]);
           $rangeList=$data->where('daily_production_entry_details_operation.dept_id',$dept_id);
             $rangeList=$data->whereIn('daily_production_entry_details_operation.employeeCode',$empArray);
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');     
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >$maxRange");
         } else{
           $rangeList=$data->whereBetween('daily_pr_date',[$request->fromDate,$request->toDate]);
           $rangeList=$data->where('daily_production_entry_details_operation.dept_id',$dept_id);
             $rangeList=$data->whereIn('daily_production_entry_details_operation.employeeCode',$empArray);
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');        
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >=$minRange AND SUM(daily_production_entry_details_operation.efficiency) < $maxRange");
         }      
           
         }
         else{
         if($minRange==0)
         {
            $rangeList=$data->where('daily_pr_date',$Rangedate);
           $rangeList=$data->where('daily_production_entry_details_operation.dept_id',$dept_id);
             $rangeList=$data->whereIn('daily_production_entry_details_operation.employeeCode',$empArray);
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');     
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >$maxRange");
         } else{
                    $rangeList=$data->where('daily_pr_date',$Rangedate);
           $rangeList=$data->where('daily_production_entry_details_operation.dept_id',$dept_id);
             $rangeList=$data->whereIn('daily_production_entry_details_operation.employeeCode',$empArray);
          $rangeList=$data->groupBy('employeemaster_operation.employeeCode');        
         $rangeList=$data->havingRaw("SUM(daily_production_entry_details_operation.efficiency) >=$minRange AND SUM(daily_production_entry_details_operation.efficiency) < $maxRange");
         }
         }
         
         
           $rangeList=$data->get();    
            
             //dd(DB::getQueryLog());
        }
        
       }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
         

         
         
         $html="";
      
        
         $html.='<table style="border:1px solid #000">
         <tr>
         <th style="border:1px solid #000;background-color:none;">S.No.</th>
         <th style="border:1px solid #000;background-color:none;">Operator</th>
        <th style="border:1px solid #000;background-color:none;">Efficiency%</th>  
         </tr>
         <tbody>';
            $no=1;
          foreach($rangeList as $rowList)
         {
          $html.='<tr>
          <td style="border:1px solid #000">'.$no++.'</td>
         <td style="border:1px solid #000">'.$rowList->fullName.'</td>
        <td style="border:1px solid #000">'.number_format((float)($rowList->overall_avg ?? 0), 2, '.', '').'%</td>  
         </tr>';
            }
          $html.='</tbody>
           </table>';
      
         
       return response()->json(['html'=>$html]);
        
    }
    
    
    
    
    
         public function get_eff_datewise_operators(Request $request)
    { 
        
            $employeeCode= $request->employeeCode;
            $fromDate= $request->fromDate;
            $toDate= $request->toDate; 
            $dept_id= $request->dept_id;
        
        
    
        
          $data= DB::table('daily_production_entry_details_operation')
         ->select('ob_details.operation_name','employeemaster_operation.fullName','daily_pr_date','daily_production_entry_details_operation.pieces','daily_production_entry_details_operation.sam','daily_production_entry_details_operation.efficiency','daily_production_entry_details_operation.remark')
          ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
          ->leftJoin('ob_details','ob_details.operation_id','=','daily_production_entry_details_operation.operation_id')
         ->where(["daily_production_entry_details_operation.employeeCode"=>$employeeCode,"daily_production_entry_details_operation.dept_id"=>$dept_id])
         ->whereBetween("daily_production_entry_details_operation.daily_pr_date",[$fromDate,$toDate])
         ->orderBy('daily_production_entry_details_operation.daily_pr_date')
         ->groupBy('daily_production_entry_details_operation.operation_id','daily_production_entry_details_operation.daily_pr_date')
         ->get();

               $fetchMaster=DB::table('employeemaster_operation')->select('fullName')->where('employeeCode',$employeeCode)->first();
         
         
         $html="";
      
        
         $html.='<table style="border:1px solid #000">
         <tr>
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;text-align:left" colspan="8">'.$fetchMaster->fullName.'</th>
         </tr>
         <tr>
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">S.No.</th>
        <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Production Date</th> 
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Operation</th>
          <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Sam</th>
            <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Target Per. Hour</th>
              <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Efficiency</th>
          <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Output</th>
           <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Remark</th> 
         </tr>
         <tbody>';
            $no=1;
          foreach($data as $rowList)
         {
          $html.='<tr>
          <td style="border:1px solid #000;background-color:#91d14f;color:#000;">'.$no++.'</td>
         <td style="border:1px solid #000">'.$rowList->daily_pr_date.'</td>
         <td style="border:1px solid #000">'.$rowList->operation_name.'</td>
          <td style="border:1px solid #000">'.$rowList->sam.'</td>
           <td style="border:1px solid #000">'.(round(60 / $rowList->sam)).'</td>
          <td style="border:1px solid #000">'.$rowList->efficiency.'</td>
           <td style="border:1px solid #000">'.$rowList->pieces.'</td>
          <td style="border:1px solid #000">'.$rowList->remark.'</td>    
         </tr>';
            }
          $html.='</tbody>
           </table>';
      
         
       return response()->json(['html'=>$html]);
        
    }
    
    
    
    
         public function get_employee_sub_company(Request $request)
    {
        
        return response()->json(['html' => $this->get_employee_list($request->sub_company_id)]);

    }
    
    
         public function get_date_wise_operation_detail(Request $request)
    { 
        
    
        $employeeCode= $request->employeeCode;
        $productionDate= $request->productionDate;
        $deptId= $request->deptId;
        
        
        
        
        // $master= DB::table('line_plan_masters')->select('target_efficiency')->where('daily_pr_date',$productionDate)->where('dept_id',$deptId)->first();
        
          $data= DB::table('daily_production_entry_details_operation')
         ->select('ob_details.operation_name','employeemaster_operation.fullName','daily_production_entry_details_operation.pieces','daily_production_entry_details_operation.sam','daily_production_entry_details_operation.efficiency','daily_production_entry_details_operation.remark')
          ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details_operation.employeeCode')
         ->leftJoin('ob_details','ob_details.operation_id','=','daily_production_entry_details_operation.operation_id')
         ->where(["daily_production_entry_details_operation.employeeCode"=>$employeeCode,
         "daily_production_entry_details_operation.daily_pr_date"=>$productionDate,"daily_production_entry_details_operation.dept_id"=>$deptId])
         ->groupBy('daily_production_entry_details_operation.operation_id')
         ->get();

         
         
         $html="";
      
        
         $html.='<table style="border:1px solid #000">

         <tr>
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">S.No.</th>
        <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Production Date</th> 
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Operator</th> 
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Operation</th>
          <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Sam</th>
            <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Target Per. Hour</th>
              <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Efficiency</th>
          <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Quantity</th>
           <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Remark</th> 
         </tr>
         <tbody>';
            $no=1;
          foreach($data as $rowList)
         {
          $html.='<tr>
          <td style="border:1px solid #000;background-color:#91d14f;color:#000;">'.$no++.'</td>
         <td style="border:1px solid #000">'.$productionDate.'</td>
         <td style="border:1px solid #000">'.$rowList->fullName.'</td> 
         <td style="border:1px solid #000">'.$rowList->operation_name.'</td>
          <td style="border:1px solid #000">'.$rowList->sam.'</td>
           <td style="border:1px solid #000">'.(round(60 / $rowList->sam)).'</td>
          <td style="border:1px solid #000">'.$rowList->efficiency.'</td>
           <td style="border:1px solid #000">'.$rowList->pieces.'</td>
          <td style="border:1px solid #000">'.$rowList->remark.'</td>    
         </tr>';
            }
          $html.='</tbody>
           </table>';
      
         
       return response()->json(['html'=>$html]);
        
    }
    
    
    
             public function get_date_wise_operation_detail_pcs(Request $request)
    { 
        
    
        $employeeCode= $request->employeeCode;
        $productionDate= $request->productionDate;
        $deptId= $request->deptId;
        
        
        
        
        // $master= DB::table('line_plan_masters')->select('target_efficiency')->where('daily_pr_date',$productionDate)->where('dept_id',$deptId)->first();
        
          $data= DB::table('daily_production_entry_details')
         ->select('ob_details.operation_name','daily_production_entry_details.bundleNo','employeemaster_operation.fullName',DB::raw('daily_production_entry_details.stiching_qty'),'ob_details.sam')
          ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details.employeeCode')
         ->leftJoin('ob_details','ob_details.operation_id','=','daily_production_entry_details.operationNameId')
         ->where(["daily_production_entry_details.employeeCode"=>$employeeCode,
         "daily_production_entry_details.dailyProductionEntryDate"=>$productionDate,"daily_production_entry_details.line_no"=>$deptId])
         ->groupBy('daily_production_entry_details.dailyProductionEntryDate','daily_production_entry_details.operationNameId','daily_production_entry_details.bundleNo','daily_production_entry_details.employeeCode')
         ->get();

         
         
         $html="";
      
        
         $html.='<table style="border:1px solid #000">

         <tr>
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">S.No.</th>
        <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Production Date</th> 
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Bundle No.</th>   
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Operator</th> 
         <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Operation</th>
          <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Sam</th>
            <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Target Per. Hour</th>
              <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Efficiency</th>
          <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Quantity</th>
           <th style="border:1px solid #000;background-color:#91d14f;color:#000;">Remark</th> 
         </tr>
         <tbody>';
            $no=1;
          foreach($data as $rowList)
         {
          $html.='<tr>
          <td style="border:1px solid #000;background-color:#91d14f;color:#000;">'.$no++.'</td>
         <td style="border:1px solid #000">'.$productionDate.'</td>
        <td style="border:1px solid #000">'.$rowList->bundleNo.'</td> 
         <td style="border:1px solid #000">'.$rowList->fullName.'</td> 
         <td style="border:1px solid #000">'.$rowList->operation_name.'</td>
          <td style="border:1px solid #000">'.$rowList->sam.'</td>
           <td style="border:1px solid #000">'.(round(60 / $rowList->sam)).'</td>
          <td style="border:1px solid #000">'.round((($rowList->sam *  $rowList->stiching_qty) / 4.8),2).'</td>
           <td style="border:1px solid #000">'.$rowList->stiching_qty.'</td>
          <td style="border:1px solid #000">-</td>    
         </tr>';
            }
          $html.='</tbody>
           </table>';
      
         
       return response()->json(['html'=>$html]);
        
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
    
       public  function getBetweenDates($startDate, $endDate)
{
    $rangArray = [];

    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    for (
        $currentDate = $startDate;
        $currentDate <= $endDate;
        $currentDate += (86400)
    ) {

        $date = date('Y-m-d', $currentDate);
        $rangArray[] = $date;
    }

    return $rangArray;
}
    
}
