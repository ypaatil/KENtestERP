<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\HourlyProductionMasterModel;
use App\Models\NewJobOpeningDetailModel;
use App\Models\HourlyProductionDetailModel;
use App\Models\OBMasterModel;
use Illuminate\Http\Request;
use DataTables;
use Session;
use DB;
use App\Traits\EmployeeTrait;
use DatePeriod;
use DateTime;
use DateInterval;
use App\Imports\LineAttendanceDetailImport;
use Excel;
use App\Models\BarcodeBrandModel;


class BarcodeBrandController extends Controller
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
            
            
            $data=BarcodeBrandModel::select("barcode_brand_masters.barcode_brand_id",
           "usermaster.username",'main_style_master.mainstyle_name','brand_master.brand_name','barcode_brand_rate')
            ->leftJoin('usermaster','usermaster.userId','=','barcode_brand_masters.userId')
            ->leftJoin('brand_master','brand_master.brand_id', '=', 'barcode_brand_masters.brand_id')
            ->join('main_style_master','main_style_master.mainstyle_id', '=', 'barcode_brand_masters.mainstyle_id');  
             $data->where('barcode_brand_masters.delflag',0);
 
            
            return Datatables::of($data)
            ->addIndexColumn()
          ->addColumn('srno',function ($row) { 
                static $srno = 1;   
                return $srno++;
            }) 
            
            ->addColumn('action1', function($row)
            {
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('barcode_brand.edit', $row['barcode_brand_id']).'" ><i class="fas fa-pencil-alt" data-toggle="tooltip" data-original-title="Edit"></i></a>';
                return $btn;
            })
            ->addColumn('action2', function($row)
            {
            
                $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['barcode_brand_id'].'"  data-route="'.route('barcode_brand.destroy', $row['barcode_brand_id']).'"><i class="fas fa-trash"></i></a>';
                return $btn3;
                
            })
                ->addColumn('action3', function($row)
            {
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="barcode_brand_print/'.$row['barcode_brand_id'].'" ><i class="fas fa-print" data-toggle="tooltip" data-original-title="Edit"></i></a>';
                return $btn;
            })
            ->rawColumns(['action1','action2','action3'])
            ->make(true);
        }
          
          
        
            return view('BarcodeBrandList');
    }
    
    
    
    public function barcode_brand_print($id){
        
            $data=BarcodeBrandModel::select("barcode_brand_masters.barcode_brand_id",
            "usermaster.username",'main_style_master.mainstyle_name','brand_master.brand_name','barcode_brand_rate')
            ->leftJoin('usermaster','usermaster.userId','=','barcode_brand_masters.userId')
            ->leftJoin('brand_master','brand_master.brand_id', '=', 'barcode_brand_masters.brand_id')
            ->join('main_style_master','main_style_master.mainstyle_id', '=', 'barcode_brand_masters.mainstyle_id')
             ->where('barcode_brand_masters.delflag',0)->where('barcode_brand_id',$id)->first();
        
        
        return view('brand_barcode_print',compact('data'));
        
    }
    
    
    
    public function get_attendance(Request $request){
        
      
 if ($request->ajax()) {
            

    $data=DB::select('select line_wise_attendancelogs.lineAttendanceDate from line_wise_attendancelogs group by line_wise_attendancelogs.lineAttendanceDate');


 return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action1', function ($row) {
                        
     
                           $btn = '
 <a class="btn btn-primary btn-icon btn-sm"  href="get_attendance/'.base64_encode($row->lineAttendanceDate).'" >
                                                                <i class="feather feather-edit"  data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                           ';
                           
                           
                           
    
                            return $btn;
                    })
                     ->addColumn('action2', function ($row) {
     
     
    
     
                           $btn2 = '
 <a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'"  data-route="'.route('attendance_delete', $row->lineAttendanceDate).'" data-id="'.$row->lineAttendanceDate.'" ><i class="feather feather-trash-2"></i></a>
                           ';
                           
       
    
                            return $btn2;
                    })
                    ->rawColumns(['action1','action2'])

 ->make(true);

        }
        
      return view('Operation.Line_wise_AttendanceList');           
        
    }
    
    

    public function attendance_import(Request $request)
    {
        
        
         Excel::import(new LineAttendanceDetailImport,request()->file('attendancefile'));
         
         return view('Operation.Line_wise_AttendanceList');
        
        
    }
       public function attendance_delete(Request $request)
    {

        
     $master = DB::table('line_wise_attendancelogs')->where('lineAttendanceDate', $request->id)->delete();
      
      Session::flash('delete', 'Deleted record successfully');   
    }
    
    
     public function get_hourly_operation_production(Request $request)
    {
        
         $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
    
   
         return view('Operation.get_hourly_production_report',compact('deptlist'));
    }
    
         public function get_hourly_operation_production_detail(Request $request)
    {
        
         $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
    
   
         return view('Operation.get_hourly_production_report_detail',compact('deptlist'));
    }
    
         public function show_hourly_operation_production(Request $request)
    {
        
          $sub_company_id=$request->sub_company_id;
          $dept_id=$request->dept_id;
          $fromDate=$request->fromDate;
          $toDate=$request->toDate;
          
 
    
         $data=DB::table('hourly_production_entry_details')->select(DB::raw('hourlyEntryDate,sum(nine_ten) as nine_ten,sum(ten_eleven) as ten_eleven,
         sum(eleven_twelve) as eleven_twelve,sum(twelve_one) as twelve_one,sum(oneThirty_twoThirty) as oneThirty_twoThirty,sum(twoThirty_threeThirty) as twoThirty_threeThirty,sum(threeThirty_fourefourty) as threeThirty_fourefourty,sum(fourefourty_fiveFourty) as fourefourty_fiveFourty,sum(total_output) as total_output'))->whereBetween('hourlyEntryDate',[$fromDate,$toDate])
         ->where('sub_company_id',$request->sub_company_id)->where('dept_id',$request->dept_id)->groupBy('hourlyEntryDate')->get();
         
          $subCompanyFetch=DB::table('sub_company_master')->select('sub_company_name','sub_company_id')->where('erp_sub_company_id',$request->sub_company_id)->first();
          $sub_company_name=$subCompanyFetch->sub_company_name;
          
           $deptfetch = DB::table('line_master')->where('delflag',0)->where('line_id',$request->dept_id)->first();
           $line_name=$deptfetch->line_name;
         
   
         return view('Operation.hourly_operation_production_report',compact('data','sub_company_name','line_name'));
    }
    
             public function show_hourly_operation_production_detail(Request $request)
    {
        
          $sub_company_id=$request->sub_company_id;
          $dept_id=$request->dept_id;
          $fromDate=$request->fromDate;
          $toDate=$request->toDate;
          
 
    
         $data=DB::table('hourly_production_entry_details')
         ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','hourly_production_entry_details.employeeCode')
          ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'hourly_production_entry_details.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'hourly_production_entry_details.mainstyle_id');
            }) 
         ->select(DB::raw('hourlyEntryDate,sum(nine_ten) as nine_ten,sum(ten_eleven) as ten_eleven,
         sum(eleven_twelve) as eleven_twelve,sum(twelve_one) as twelve_one,sum(oneThirty_twoThirty) as oneThirty_twoThirty,sum(twoThirty_threeThirty) as twoThirty_threeThirty,
         sum(threeThirty_fourefourty) as threeThirty_fourefourty,sum(fourefourty_fiveFourty) as fourefourty_fiveFourty,sum(total_output) as total_output,
         employeemaster_operation.fullName,ob_details.operation_name'))
         ->where('hourlyEntryDate',$fromDate)
         ->where('hourly_production_entry_details.sub_company_id',$request->sub_company_id)
         ->where('hourly_production_entry_details.dept_id',$request->dept_id)
         ->groupBy('hourlyEntryDate')
         ->groupBy('hourly_production_entry_details.employeeCode')->groupBy('hourly_production_entry_details.operationNameId')->get();
         
          $subCompanyFetch=DB::table('sub_company_master')->select('sub_company_name','sub_company_id')->where('erp_sub_company_id',$request->sub_company_id)->first();
          $sub_company_name=$subCompanyFetch->sub_company_name;
          
           $deptfetch = DB::table('line_master')->where('delflag',0)->where('line_id',$request->dept_id)->first();
           $line_name=$deptfetch->line_name;
         
   
         return view('Operation.hourly_operation_production_report_detail',compact('data','sub_company_name','line_name','fromDate'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $brandList= DB::table('brand_master')->get();
        $styleList= DB::table('main_style_master')->get(); 
        
        return view('barcode_brand',compact('brandList','styleList'));

    }

    
    
     public function check_exists_production(Request $request)
    {
       
             $isExist=HourlyProductionDetailModel::
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
       
            $isExist=HourlyProductionMasterModel::
            select('*')
            ->where(["daily_production_entry_masters.dept_id"=>$request->dept_id,
            "daily_production_entry_masters.daily_pr_date"=>$request->daily_pr_date,
            "daily_production_entry_masters.mainstyle_id"=>$request->mainstyle_id,  
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
        
         $lineFetch=HourlyProductionMasterModel::select('department_master.dept_name','line_plan_masters.line_plan_id')
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
        ->select(DB::raw("ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (4.8),2) as daily_avg"))
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
          ->select(DB::raw("ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (4.8),2) as efficiency
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
     
        
           $deptlist = DB::table('line_master')->where('delflag',0)->where('Ac_code',Session::get('vendorId'))->get();
            $subfetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();
            
            
            $sub_company_name=$subfetch->sub_company_name;
            
            
                $period = $this->getBetweenDates('2024-10-01','2024-10-30');
       
                
          $subCompanyList=DB::table('sub_company_master')->select('sub_company_name')->whereIn('erp_sub_company_id',[56,115,628,110])->get();
        
       
       return view('Operation.show_line_wise_efficiency_yearly_report',compact('deptlist','year','sub_company_id','sub_company_name','period','subCompanyList'));

    }
    
            public function show_line_wise_efficiency_yearly_all_unit(Request $request)
    {
        
        $year=$request->year;
       
     
        
            $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
           
                
          $subCompanyList=DB::table('sub_company_master')->select('sub_company_name','sub_company_id')->whereIn('erp_sub_company_id',[56,115,628,110])->get();
        
       
       return view('Operation.show_line_wise_efficiency_yearly_report_new',compact('deptlist','year','subCompanyList'));

    }
    
    
    
    public function show_line_wise_efficiency_monthly($year,$sub_company_id)
    {
        
        
         $MonthList=DB::table('monthMaster')->select('monthId','MonthName')->get();
      
        
          $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
          
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
        

        
           $deptlist=DB::table('department_master_operation')->select('dept_id','dept_name')->whereIn('dept_id',[65,66,67,68])->get();
          
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
         DB::raw('(ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (4.8),2) / COUNT(DISTINCT(daily_production_entry_details_operation.daily_pr_date)))  as avg_efficiency'), 'employeemaster_operation.fullName')
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
         $topList = $query->groupBy('employeemaster_operation.fullName','line_master.line_name','line_master.line_id');
         $topList = $query->orderBy('avg_efficiency', 'DESC');
         $topList=$query->limit($top_n);
         $topList=$query->get(); 
         
         
         //DB::enableQueryLog();
         
            $query1 = DB::table('daily_production_entry_details_operation')
         ->select('daily_production_entry_details_operation.dept_id', 'line_master.line_name',
         DB::raw('(ROUND(SUM(ob_details.sam * daily_production_entry_details_operation.pieces) / (4.8),2) / COUNT(DISTINCT(daily_production_entry_details_operation.daily_pr_date))) as avg_efficiency_bottom'), 'employeemaster_operation.fullName')
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
         $bottomList = $query1->groupBy('employeemaster_operation.fullName','line_master.line_name','line_master.line_id');
         $bottomList = $query1->orderBy('avg_efficiency_bottom', 'ASC');
         $bottomList=$query1->limit($bottom_n);
         $bottomList=$query1->get(); 
         //dd(DB::getQueryLog());
         
         
           $sub_fetch=DB::table('sub_company_master')->select('sub_company_name')->where('erp_sub_company_id',$request->sub_company_id)->first();
         
        
         return view('Operation.show_top_n_bottom_n_efficiency',compact('fromDate','toDate','top_n','bottom_n','dept_id','bottomList','topList','sub_fetch'));

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
           
           
                $IODetails = BarcodeBrandModel::updateOrCreate(
                ['barcode_brand_id'=> $id],
                $data);
                

            DB::commit();
            $msg = "";

            if($id == null){
                $msg = 'Brand saved successfully';
            } else {
                $msg = 'Brand updated successfully';
            }


         return redirect()->route('barcode_brand.index')->with('message', $msg);
         
         } catch (\Exception $e) {
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
  
      DB::rollBack();
  
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
 
    }
    
    }

    
    
       public function store_update_hourly_production(Request $request)
    {
        
        
           try {  
           
          
          DB::beginTransaction();
           
               
                
            $existingRecord = HourlyProductionMasterModel::updateOrCreate(
            [
            'hourlyEntryDate' => $request->hourlyEntryDate,
            'sub_company_id' => $request->sub_company_id,
             'mainstyle_id' => $request->mainstyle_id, 
             'dept_id' => $request->dept_id,   
            ],
            [
            'hourlyEntryDate'=>$request->hourlyEntryDate,    
            'mainstyle_id' => $request->mainstyle_id,
            'sub_company_id' => $request->sub_company_id,
            'dept_id' => $request->dept_id,  
            'total_production'=>$request->total_production, 
             'userId'=>$request->userId  
            ]
            );   
            
   
              $exists=HourlyProductionMasterModel::where(['hourlyEntryDate'=>$request->hourlyEntryDate,'sub_company_id'=>$request->sub_company_id,'mainstyle_id'=>$request->mainstyle_id,'dept_id'=>$request->dept_id])->first();
                
                if($exists == null){
                $DPE_id=HourlyProductionMasterModel::max('hourlyProductionId');
                } else{
                
                $DPE_id=$exists->hourlyProductionId; 
                }

                
                
           $operation_id = $request->input('operation_id');
         
           
            if(!empty($operation_id))
            {

            
             
                
          $existingRecord = HourlyProductionDetailModel::updateOrCreate(
            [
            'hourlyProductionId'=>$DPE_id,       
            'employeeCode' => $request->employeeCode,  
            'operationNameId' => $request->operation_id,
            'hourlyEntryDate'=>$request->hourlyEntryDate  
            ],
            [
               'hourlyProductionId'=>$DPE_id,  
              'hourlyEntryDate'=>$request->hourlyEntryDate,  
              'sub_company_id'=>$request->sub_company_id,
              'mainstyle_id'=>$request->mainstyle_id,   
                'dept_id'=>$request->dept_id,   
              'employeeCode'=>$request->employeeCode,      
              'operationNameId'=>$request->operation_id,
              'operation_type'=>$request->operation_type,    
              'nine_ten'=>$request->nine_ten,  
              'ten_eleven'=>$request->ten_eleven,
              'eleven_twelve'=>$request->eleven_twelve,
              'twelve_one'=>$request->twelve_one,
              'oneThirty_twoThirty'=>$request->oneThirty_twoThirty,
              'twoThirty_threeThirty'=>$request->twoThirty_threeThirty,
              'threeThirty_fourefourty'=>$request->threeThirty_fourefourty,
              'fourefourty_fiveFourty'=>$request->fourefourty_fiveFourty, 
             'total_output'=>$request->total_output,  
             'remark'=>$request->remark,
             'other_remark'=>$request->other_remark
            ]
            ); 
                 
            
             
          
        }
            DB::commit();

         return response()->json('ok');
         
         } catch (\Exception $e) {
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
  
      DB::rollBack();
  
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
 
    }    
        
        
    }
    
    
           public function update_hourly_production_down_time(Request $request)
    {
        
      
        
           try {  
           
          
          DB::beginTransaction();
           
                
           $operation_idSubmit = $request->input('operation_idSubmit');
         
        
            if(!empty($operation_idSubmit))
            {
   
                    // First, find the record by your condition
                $existingRecord = HourlyProductionDetailModel::
                where('employeeCode', $request->employeeCodeSubmit)
                ->where('operationNameId', $request->operation_idSubmit)
                ->where('hourlyEntryDate', $request->hourlyEntryDate)
                ->where('mainstyle_id', $request->mainstyle_id)
                ->where('dept_id', $request->dept_id)
                ->first();

                    
                    if ($existingRecord) {
                    // Initialize empty array
                    $updateFields = [];
                    
                    // Conditionally add fields to the update array
                    if ($request->hoursSubmit === 'nine_ten_down_time_min') {
                    $updateFields['nine_ten_down_time_min'] = $request->down_time_min;
                    $updateFields['nine_ten_reason'] = $request->down_time_reason;
                    }
                    
                       if ($request->hoursSubmit === 'ten_eleven_down_time_min') {
                    $updateFields['ten_eleven_down_time_min'] = $request->down_time_min;
                    $updateFields['ten_eleven_reason'] = $request->down_time_reason;
                    }
                    
                       if ($request->hoursSubmit === 'eleven_twelve_down_time_min') {
                    $updateFields['eleven_twelve_down_time_min'] = $request->down_time_min;
                    $updateFields['eleven_twelve_reason'] = $request->down_time_reason;
                    }
                    
                    
                      if ($request->hoursSubmit === 'twelve_one_dtm') {
                    $updateFields['twelve_one_dtm'] = $request->down_time_min;
                    $updateFields['twelve_one_reason'] = $request->down_time_reason;
                    }
                    
                      if ($request->hoursSubmit === 'oneThirty_twoThirty_dtm') {
                    $updateFields['oneThirty_twoThirty_dtm'] = $request->down_time_min;
                    $updateFields['oneThirty_twoThirty_reason'] = $request->down_time_reason;
                    }
                    
                    
                       if ($request->hoursSubmit === 'twoThirty_threeThirty_dtm') {
                    $updateFields['twoThirty_threeThirty_dtm'] = $request->down_time_min;
                    $updateFields['twoThirty_threeThirty_reason'] = $request->down_time_reason;
                    }
                    
                    
                            if ($request->hoursSubmit === 'threeThirty_fourefourty_dtm') {
                    $updateFields['threeThirty_fourefourty_dtm'] = $request->down_time_min;
                    $updateFields['threeThirty_fourefourty_reason'] = $request->down_time_reason;
                    }
                    
                    
                    if ($request->hoursSubmit === 'fourefourty_fiveFourty_reason') {
                    $updateFields['fourefourty_fiveFourty_reason'] = $request->down_time_min;
                    $updateFields['fourefourty_fiveFourty_reason'] = $request->down_time_reason;
                    }
                    
                    
                    // Only update if there is something to update
                    if (!empty($updateFields)) {
                    $existingRecord->update($updateFields);
                  
                    } else {
                  
                    }
                    } else {
                  
                    }
   
         
        }
            DB::commit();

         return response()->json('ok');
         
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
        
        
            $master=HourlyProductionMasterModel::
            select('total_efficiency')
          ->where("daily_pr_entry_id",$request->daily_pr_entry_id)->first();
        
        
      $result=HourlyProductionDetailModel::
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
          $employeelist=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[64,71,79])->get(); 
          
          
          //$operationList=DB::table('ob_masters')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
         
         $operationList=DB::table('ob_details')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
          
          
          $styleList = DB::table('main_style_master_operation')->Select('*')->get();    

         
         $html="";
         
           $html.='<table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead>
                              <tr>
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
    
     public function get_hourly_production_table_by_operator_new(Request $request){
         
         
         
         
                    $linefetch= DB::table('line_master')->select('line_name')->where('line_id',$request->dept_id)->first();
                    
            
            
            
            
$result = DB::table('line_wise_attendancelogs as lwal')
    ->select(
        'lwal.EmployeeCode as employeeCode',
        'hped.nine_ten',
        'hped.ten_eleven',
        'hped.eleven_twelve',
        'hped.twelve_one',
        'hped.oneThirty_twoThirty',
        'hped.twoThirty_threeThirty',
        'hped.threeThirty_fourefourty',
        'hped.fourefourty_fiveFourty',
        'hped.total_output',
        'hped.remark',
        'hped.other_remark',
        'hped.operationNameId',
        'hped.operation_type'
    )
    ->leftJoin('hourly_production_entry_details as hped', function($join) use ($request) {
        $join->on('lwal.EmployeeCode', '=', 'hped.employeeCode')
            ->where('hped.hourlyEntryDate', '=', $request->hourlyEntryDate)
            ->where('hped.dept_id', '=', $request->dept_id)
            ->where('hped.mainstyle_id', '=', $request->mainstyle_id);
    })
    ->where('lwal.lineAttendanceDate', '=', $request->hourlyEntryDate)
    ->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(lwal.Punch_Records, ')', 1), '(', -1), ?) > 0", [$linefetch->line_name])
    ->orderBy('InTime')
    ->get();


            
            

            
            
    //   $result = DB::table('hourly_production_entry_details as hped')
    //         ->leftJoin('line_wise_attendancelogs as lwal', function ($join) use ($request, $linefetch) {
    //             $join->on('lwal.EmployeeCode', '=', 'hped.employeeCode')
    //                  ->where('lwal.lineAttendanceDate', '=', $request->hourlyEntryDate)
    //                  ->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(lwal.Punch_Records, ')', 1), '(', -1), ?)", [$linefetch->line_name]);
    //         })
    //         ->where('hped.hourlyEntryDate', '=', $request->hourlyEntryDate)
    //         ->where('hped.dept_id', '=', $request->dept_id)
    //         ->where('hped.mainstyle_id', '=', $request->mainstyle_id)
    //         ->get(['hped.employeeCode','nine_ten','ten_eleven','eleven_twelve','twelve_one','oneThirty_twoThirty','twoThirty_threeThirty','threeThirty_fourefourty',
    //         'fourefourty_fiveFourty','total_output','remark','other_remark','operationNameId']);


  
    
          
          $employeelist=DB::table('employeemaster_operation')->select('employeeId','employeeCode','fullName')
          ->whereNotIn('employee_status_id',[3,4])->whereIn('egroup_id',[64,71,79,112])->get(); 
          
          $employeeMap=[];
          
      foreach ($employeelist as $rowEmp) {
         $employeeMap[] = [
        'employeeCode' => $rowEmp->employeeCode,
         'employeeId' => $rowEmp->employeeId,
         'fullName' => $rowEmp->fullName  
        ];
        
        }
                           
          
          //$operationList=DB::table('ob_masters')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
         
         $operationList=DB::table('ob_details')->select('operation_id','operation_name')->where('mainstyle_id',$request->mainstyle_id)->get();  
          
          
          $styleList = DB::table('main_style_master_operation')->Select('*')->get();    

         
         $html="";
         
           $html.='<table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead>
                              <tr>
                              <th class="text-center">Sr.No.</th>   
                                 <th class="text-center">Operator</th>   
                                    <th class="text-center">Operation</th>
                                     <th class="text-center">Operation Type</th>   
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
                            <th class="text-center">Other Remark</th>   
                                  <th class="text-center">Add</th>
                                 <th class="text-center">Remove</th>  
                                 </tr>
                                 </thead>
                                <tbody id="tbodyData">';
    $no=1;
    foreach($result as $row)
    {
        
          $nine_ten =$row->nine_ten ?? '';
          $ten_eleven=$row->ten_eleven ?? '';
          $eleven_twelve=$row->eleven_twelve ?? '';
          $twelve_one=$row->twelve_one ?? '';
          $oneThirty_twoThirty=$row->oneThirty_twoThirty ?? '';
          $twoThirty_threeThirty=$row->twoThirty_threeThirty ?? '';
          $threeThirty_fourefourty=$row->threeThirty_fourefourty ?? '';
          $fourefourty_fiveFourty=$row->fourefourty_fiveFourty ?? '';
          
          
          //'.($exists ? 'disabled' : '').'
          
          
          $nine_ten_var="nine_ten_down_time_min";
          $ten_eleven_var='ten_eleven_down_time_min';
          $eleven_twelve_down_time_min_var='eleven_twelve_down_time_min'; 
          $twelve_one_dtm_var='twelve_one_dtm'; 
          $oneThirty_twoThirty_dtm_var='oneThirty_twoThirty_dtm';
          $twoThirty_threeThirty_dtm_var='twoThirty_threeThirty_dtm';
          $threeThirty_fourefourty_dtm_var='threeThirty_fourefourty_dtm';
          $fourefourty_fiveFourty_dtm_var='fourefourty_fiveFourty_dtm';
          
          
          $exists=DB::table('hourly_production_entry_details')->where('employeeCode',$row->employeeCode)->where('operationNameId',$row->operationNameId)->exists();
          
          
        
    $html.=' <tr class="rowcheck" id="tbodyData">
                                    <td>
                                     <input type="text" step="any" min="0"   class="form-control"  name="sr_no[]"  value="'.$no++.'" style="width:44px;">
                                 </td>  
                                 <td>
                                  <select class="form-control SelectDrop"  name="employeeCode[]" disabled   id="employeeCode" required onChange="previousData(this);">
                           <option value="">--- Select---</option>';  
                           
                       if (isset($employeeMap) && is_array($employeeMap)) {
                            $chunkSize = 200;
                            
                            foreach (array_chunk($employeeMap, $chunkSize) as $chunk) {
                                foreach ($chunk as $rowemp) {
                                   
                                    if (isset($rowemp['employeeCode'], $rowemp['fullName'])) {
                                       
                                        $html .= '<option value="' . htmlspecialchars($rowemp['employeeCode']) . '"';
                            
                                       
                                        if (isset($row->employeeCode) && $rowemp['employeeCode'] == $row->employeeCode) {
                                            $html .= " selected='selected'";
                                        }
                            
                                        
                                        $html .= '>' . htmlspecialchars($rowemp['fullName']) . ' (' . htmlspecialchars($rowemp['employeeCode']) . ')</option>';
                                    }
                                }
                            }
                        }
                            
                        $html.='</select>';     
                                     
                                 $html.='</td>
                                  
                               <td>
                            <select class="CAT required SelectDrop" required  name="operation_id[]" id="operation_id"    onChange="get_detail(this,this.value);previousData(this);">
                             <option value="">--- Select---</option>';  
                              
                            foreach($operationList as $operation)
                            {
                            $html.='<option value="'.$operation->operation_id.'"'; 
                            
                            $operation->operation_id== $row->operationNameId ? $html.="selected='selected'" : ""; 
                            
                            
                            $html.='>'.$operation->operation_id.'('.$operation->operation_name.')</option>';
                            }
                            $html.='</select>   
                            
                            
                                 </td>';
                                 
                                    $selectedOperationType = $row->operation_type == 1 ? 'selected' : ''; 
                                    $selectedOperationType2 = $row->operation_type == 2 ? 'selected' : '';
                                 
                                 $html.=' <td>
                            <select class="form-control"   name="operation_type[]" id="operation_type">
                            <option value="">Operation Type</option>
                            <option value="1" '.$selectedOperationType.'>Fixed</option>
                            <option value="2" '.$selectedOperationType2.'>Piece</option>
                            </select>   
                               </td>   
                                 
                              <td>            
                              
                                <div class="input-wrapper">
                                    <input type="text" step="any" min="0"   class="form-control  nine_ten"    name="nine_ten[]" id="nine_ten"  value="'.$nine_ten.'" style="width:70px;" onChange="auto_save(this);">
                                
                                   <span class="add-icon" onclick="openmodel(this,\'' . $nine_ten_var . '\')">&#x2795;</span>
                                   </div>
                                 </td>  
                             
                                 <td>
                                  <div class="input-wrapper">
                                    <input type="text" step="any" min="0"   class="form-control  ten_eleven"   name="ten_eleven[]" id="ten_eleven"  value="'.$ten_eleven.'" style="width:70px;" onChange="auto_save(this);">
                                  <span class="add-icon" onclick="openmodel(this,\'' . $ten_eleven_var . '\')">&#x2795;</span>
                                    </div>
                                 </td>  
                                 
                                
                                   <td>
                                    <div class="input-wrapper">
                                    <input type="text" step="any" min="0"   class="form-control  eleven_twelve"   name="eleven_twelve[]" id="eleven_twelve"  value="'.$eleven_twelve.'" style="width:70px;" onChange="auto_save(this);">
                                  <span class="add-icon" onclick="openmodel(this,\'' . $eleven_twelve_down_time_min_var . '\')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                    <div class="input-wrapper">    
                                    <input type="text" step="any" min="0"   class="form-control  twelve_one"   name="twelve_one[]" id="twelve_one"  value="'.$twelve_one.'" style="width:70px;" onChange="auto_save(this);">
                                 <span class="add-icon" onclick="openmodel(this,\'' . $twelve_one_dtm_var . '\')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                     <div class="input-wrapper">        
                                    <input type="text" step="any" min="0"   class="form-control  oneThirty_twoThirty"   name="oneThirty_twoThirty[]" id="oneThirty_twoThirty"  value="'.$oneThirty_twoThirty.'" style="width:70px;" onChange="auto_save(this);">
                                  <span class="add-icon" onclick="openmodel(this,\'' . $oneThirty_twoThirty_dtm_var . '\')">&#x2795;</span>
                                    </div>
                                 </td> 
                           
                                   <td>
                                      <div class="input-wrapper">        
                                    <input type="text" step="any" min="0"   class="form-control  twoThirty_threeThirty"   name="twoThirty_threeThirty[]" id="twoThirty_threeThirty"  value="'.$twoThirty_threeThirty.'" style="width:70px;" onChange="auto_save(this);">
                                    <span class="add-icon" onclick="openmodel(this,\'' . $twoThirty_threeThirty_dtm_var . '\')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                      <div class="input-wrapper">        
                                    <input type="text" step="any" min="0"   class="form-control  threeThirty_fourefourty"   name="threeThirty_fourefourty[]" id="threeThirty_fourefourty"  value="'.$threeThirty_fourefourty.'" style="width:70px;" onChange="auto_save(this);">
                                  <span class="add-icon" onclick="openmodel(this,'.$threeThirty_fourefourty_dtm_var.')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                     <div class="input-wrapper">         
                                    <input type="text" step="any" min="0"   class="form-control  fourefourty_fiveFourty"   name="fourefourty_fiveFourty[]" id="fourefourty_fiveFourty"  value="'.$fourefourty_fiveFourty.'" style="width:70px;" onChange="auto_save(this);">
                                    <span class="add-icon" onclick="openmodel(this,\'' . $fourefourty_fiveFourty_dtm_var . '\')">&#x2795;</span>
                                    </div>
                                 </td> 
                                         <td>
                                    <input type="text" step="any" min="0"   class="form-control  total_output"   name="total_output[]" id="total_output"  value="'.$row->total_output.'" style="width:90px;" onChange="auto_save(this);">
                                 </td> 
                                   <td>'; 
                                   
                                    $selected1 = $row->remark == '' ? 'selected' : ''; 
                                    $selected2 = $row->remark == 'Feeding Problem' ? 'selected' : ''; 
                                    $selected3 = $row->remark == 'Machine Problem' ? 'selected' : '';  
                                    $selected4 = $row->remark == 'Half Day' ? 'selected' : '';   
                                    $selected5 = $row->remark == 'Change Over' ? 'selected' : ''; 
                                    $selected6 = $row->remark == 'Input Delays' ? 'selected' : '';  
                                    $selected7 = $row->remark == 'Other' ? 'selected' : '';   
                        
                                   $html.='<select name="remark[]" id="remark" class="form-control REMARK SelectDrop"  onChange="otherRemarkData(this);auto_save(this);">
                                    <option value=""  '.$selected1.'>Select</option>       
                                    <option value="Feeding Problem"  '.$selected2.'>Feeding Problem</option>
                                    <option value="Machine Problem"  '.$selected3.'>Machine Problem</option>
                                    <option value="Half Day" '.$selected4.' >Half Day</option>
                                    <option value="Change Over"  '.$selected5.'>Change Over</option>
                                    <option value="Input Delays" '.$selected6.' >Input Delays</option>
                                    <option value="Other"  '.$selected7.'>Other</option>   
                                    </select>
                                 
                                 
                                 </td>    
                                  <td  class="otherInputTd">
                                <input type="text" name="other_remark[]" placeholder="Please specify..." value="'.$row->other_remark.'" disabled    class="form-control SelectDrop" onChange="auto_save(this);" / >
                                </td>
                                 <td>
                                    <input type="button" style="width:40px;" onclick="AddNewRow(this);"  id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger deleteOperation" onclick="deleteRow(this);" data-employeeCode="'.$row->employeeCode.'" data-operationNameId="'.$row->operationNameId.'"  value="X" >
                                 </td> 
                                 
                              </tr>
                              ';


}

                          $html.='</tbody>
                          
                    <tr class="footerTotalRow">     
      <td  class=""  style="color:#000;text-align:right;"></td>               
         <td  class=""  style="color:#000;text-align:right;"></td>    
               <td  class="sticky-col-1"  style="color:#000;text-align:right;"></td>    
    <td  class="sticky-col-1"  style="color:#000;text-align:right;font-weight:bold">Total</td>
     <td style="color:#000;text-align:right;font-weight:bold" id="total1"></td>
      <td style="color:#000;text-align:right;font-weight:bold" id="total2"></td>
        <td style="color:#000;text-align:right;font-weight:bold" id="total3"></td>
      <td style="color:#000;text-align:right;font-weight:bold" id="total4"></td>
        <td style="color:#000;text-align:right;font-weight:bold" id="total5"></td>
      <td style="color:#000;text-align:right;font-weight:bold" id="total6"></td>
        <td style="color:#000;text-align:right;font-weight:bold" id="total7"></td>
      <td style="color:#000;text-align:right;font-weight:bold" id="total8"></td>
          <td style="color:#000;text-align:right;font-weight:bold" id="GrandTotal"></td>
           <td style="color:#000;text-align:right" id=""></td>    
          <td style="color:#000;text-align:right" id=""></td>       
          </tr>
                         </table>';
    
  
  return response()->json($html);

        
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HourlyProductionMasterModel  $HourlyProductionMasterModel
     * @return \Illuminate\Http\Response
     */
   public function show(Request $request,$enetrydate)
    {
        
        
        $details= DB::table('line_wise_attendancelogs')->where('lineAttendanceDate',base64_decode($enetrydate))->get();


        return view('Operation.AttendanceLogDetailList',compact('details')); 
        
    

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HourlyProductionMasterModel  $HourlyProductionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $brandList= DB::table('brand_master')->get();
        $styleList= DB::table('main_style_master')->get(); 
        $BarcodeBrandFetch =BarcodeBrandModel::find($id);
        
        return view('barcode_brand',compact('brandList','styleList','BarcodeBrandFetch'));
        
   
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
     * @param  \App\Models\HourlyProductionMasterModel  $HourlyProductionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
   
   


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HourlyProductionMasterModel  $HourlyProductionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        HourlyProductionMasterModel::where('daily_pr_entry_id',$id)->delete();
        HourlyProductionDetailModel::where('daily_pr_entry_id',$id)->delete(); 
        
        return redirect()->route('daily_production_entry.index')->with('message', 'Delete Record Succesfully');


    }
    
       public function delete_operator(Request $request)
    {
        
        
        DB::table('hourly_production_entry_details')->where(["employeeCode"=>$request->employeeCode,"operationNameId"=>$request->operationNameId])->delete();

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
