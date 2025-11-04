<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\EmployeeModel;
use App\Models\IncrementModel;
use DB;
use Session;

trait EmployeeTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function get_employee_list($sub_company_id) {
        
        $html = '';
        $html .= '<option value="0">All</option>';
       
        $workerlist = DB::table('employeemaster')->where('sub_company_id',$sub_company_id)->whereNotIn('employee_status_id',[3,4])->get();
        foreach ($workerlist as $rowworker) {
                $html .= '<option value="'.$rowworker->employeeCode.'">'.$rowworker->employeeCode.'</option>';
              
  }
        return $html;

    }
    
    
    
        public function get_operator_list($dept_id,$mainstyle_id) {
        
        $html = '';
        $html .= '<option value="">All</option>';
       
       //DB::enableQueryLog();
        $workerlist = DB::table('line_plan_detail')
        ->join('line_plan_masters','line_plan_masters.line_plan_id','=','line_plan_detail.line_plan_id')
          ->join('employeemaster','employeemaster.employeeCode','=','line_plan_detail.employeeCode')
        ->where('line_plan_masters.dept_id',$dept_id)
       ->where('line_plan_masters.mainstyle_id',$mainstyle_id)  
        ->groupBy('employeemaster.employeeCode')
        ->get();
        //dd(DB::getQueryLog());
        
        foreach ($workerlist as $rowworker) {
                $html .= '<option value="'.$rowworker->employeeCode.'">'.$rowworker->fullName.'</option>';
              
          }
        return $html;
    }
    
          public function get_operators_trait($dept_id) {
        
        $html = '';
        $html .= '<option value="">All</option>';
       
       //DB::enableQueryLog();
        $workerlist = DB::table('line_plan_detail')
        ->join('line_plan_masters','line_plan_masters.line_plan_id','=','line_plan_detail.line_plan_id')
          ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','line_plan_detail.employeeCode')
        ->whereIn('line_plan_masters.dept_id',$dept_id)
        ->groupBy('employeemaster_operation.employeeCode')
        ->get();
        //dd(DB::getQueryLog());
        
        foreach ($workerlist as $rowworker) {
                $html .= '<option value="'.$rowworker->employeeCode.'">'.$rowworker->fullName.'</option>';
              
          }
        return $html;
    }
    
            public function get_Line_trait($sub_company_id) {
        
        $html = '';
        $html .= '<option value="">All</option>';
       
       //DB::enableQueryLog();
        $workerlist = DB::table('line_master')
        ->where('line_master.Ac_code',$sub_company_id)
        ->get();
        //dd(DB::getQueryLog());
        
        foreach ($workerlist as $rowworker) {
                $html .= '<option value="'.$rowworker->line_id.'">'.$rowworker->line_name.'</option>';
              
          }
        return $html;
    }
    
        public function get_operation_list_by_operator($employeeCode) {
        
        $html = '';
        $html .= '<option value="0">select</option>';
       
       //DB::enableQueryLog();
        $workerlist = DB::table('line_plan_detail')
        ->join('ob_masters','ob_masters.operation_id','=','line_plan_detail.operation_id')
        ->where('line_plan_detail.employeeCode',$employeeCode)
        ->groupBy('line_plan_detail.operation_id')
        ->get();
        //dd(DB::getQueryLog());
        
        foreach ($workerlist as $rowworker) {
           $html .= '<option value="'.$rowworker->operation_id.'">'.$rowworker->operation_id.'('.$rowworker->operation_name.')</option>';
              
          }
        return $html;
    }
    
    
            public function get_group_ids_by_line_trait($dept_id,$daily_pr_date,$mainstyle_id) {
        
        $html = '';
        $html .= '<option value="0">Select The Group</option>';
       

    $groupList = DB::table('group_masters as gm')
    ->leftJoin('daily_production_entry_masters as dpem', function($join) use($daily_pr_date,$dept_id,$mainstyle_id) {
        $join->on('gm.group_id', '=', 'dpem.group_id')
             ->where('dpem.dept_id', '=',  $dept_id)
             ->where('dpem.daily_pr_date', '=', $daily_pr_date)
             ->where('dpem.mainstyle_id', '=', $mainstyle_id)  
              ->where('dpem.sub_company_id', '=', Session::get('vendorId'));
    })
    ->whereNull('dpem.group_id')
    ->select('gm.group_id','gm.group_name')
    ->get();
        
        
        
        foreach ($groupList as $row) {
           $html .= '<option value="'.$row->group_id.'">'.$row->group_name.'</option>';
              
          }
        return $html;
    }
    
    
    
    public function get_operation_list($mainstyle_id) {
        
        $html = '';
        $html .= '<option value="0">select</option>';
       
        $workerlist = DB::table('ob_details')->select('operation_id','operation_name')->where('mainstyle_id',$mainstyle_id)->get();
        foreach ($workerlist as $rowworker) {
                $html .= '<option value="'.$rowworker->operation_id.'">'.$rowworker->operation_id.'('.$rowworker->operation_name.')</option>';
              
  }
        return $html;

    }
    
    
    
        public function get_employee_list_for_single($sub_company_id) {
        
        $html = '';
     
        $workerlist = DB::table('employeemaster')->where('sub_company_id',$sub_company_id)->whereNotIn('employee_status_id',[3,4])->get();
         $html .= '<option value="">Select</option>';
        foreach ($workerlist as $rowworker) {
                $html .= '<option value="'.$rowworker->employeeCode.'">'.$rowworker->employeeCode.'</option>';
              
     }
        return $html;

    }  
    
     public function get_employee_info($employeeCode) {
        
      $detail=EmployeeModel::select('employeemaster.fullName','employeemaster.egroup_id','employeemaster.dept_id','employeemaster.maincompany_id','employeemaster.perMonthCtc',
      DB::raw('tbl2.egroup_id as egroup_idreportingManager'),'employeemaster.employee_status_id','employeemaster.genderId','employeemaster.sub_company_id')
      ->leftJoin('employeemaster as tbl2','tbl2.employeeCode','=','employeemaster.reportingmanager')
      ->where('employeemaster.employeeCode',$employeeCode)->first();
        
        return $detail;

    }   
    
        public function get_rate_info($employeeCode,$fdate,$tdate) {
        
        
        $fromDate=date('Y-m-d',strtotime($fdate));
        $toDate=$tdate;
        
     //DB::enableQueryLog();
   
      $detail=IncrementModel::select('increment_id','letter_ref','employeeCode','egroup_id','emp_dept_id','maincompany_id',
      'reporting_dept_id','wef_date','previous_ctc','increment_amount', 'new_ctc', 'basic', 'da', 'hra', 'occupational_allowance', 'traveling_allowance', 
      'tea_and_tiffin_allowance','uniform_allowance','washing_allowance', 'lta', 'emp_deduct_pf', 'emp_deduct_esic', 'emp_deduct_pt','cmp_contri_pf','cmp_contri_esic',
      'cmp_contri_gratuity','ex_gratia','fromDate','toDate', 'skill_type_id','emp_cat_id','salaryType', 'costValue','grossSalary',
      'annualBonus','costToCompanyMonthly', 'annualCtc', 'perMonthCtc', 'travalRate', 'rate')
      ->where('employeeCode',$employeeCode)->where('fromDate',$fromDate)->where('toDate',$toDate)->first();
      
      //dd(DB::getQueryLog());
        
        return $detail;

    }   
    
    
        public function employee_status_by_date_and_sub_company_trait($deviceName,$status,$flag,$Attendancedate)
       {

             //DB::enableQueryLog();               
            $emplistdata =DB::table('employeemaster')->select('employeemaster.employeeCode','emp_groupmaster.egroup_name','employeemaster.fullName','employee_attendance_status.empAttStatus',
             'attendancelogs.AttendanceDate','employeemaster.misRate')
             ->join('attendancelogs','attendancelogs.EmployeeCode','=','employeemaster.employeeCode')
             ->join('employee_attendance_status','employee_attendance_status.empAttStatusId','=','attendancelogs.Status')
              ->leftJoin('emp_groupmaster','emp_groupmaster.egroup_id','=','employeemaster.egroup_id')
             ->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(Punch_Records,')',1),'(',-1),'".$deviceName."')") 
             ->where('attendancelogs.AttendanceDate',$Attendancedate)
            //  ->where('employeemaster.sub_company_id',5)
             ->where('attendancelogs.Status','!=',15)
            ->whereNotIn('employeemaster.employee_status_id',[3,4]);
            
             if($flag==1)
             {
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',2);
               $emplist=$emplistdata->get();
               
             } elseif($flag==2)
             {
                 
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
               $emplist=$emplistdata->get();   
                 
             }
             
          //dd(DB::getQueryLog());
             
        return $emplist;
       
       } 
       
        public function cab_employee_list_trait($Attendancedate,$branch_id)
       {

             //DB::enableQueryLog();               
                         $attendanceLogs = DB::table('attendancelogs')
                        ->join('cab_details', 'cab_details.card_id', '=', 'attendancelogs.EmployeeCode')
                        ->leftJoin('cab_expenses_details', 'cab_expenses_details.card_id', '=', 'attendancelogs.EmployeeCode')
                        ->where('attendancelogs.AttendanceDate',$Attendancedate)
                        ->select(
                        'attendancelogs.AttendanceDate as cab_expenses_date',
                        'attendancelogs.EmployeeCode as card_id',
                        'cab_details.per_day_rate as amount',
                        'cab_details.vehicle_no',
                        'cab_details.driver_name',
                        'cab_details.route'
                        )
                        ->groupBy('attendancelogs.AttendanceDate', 'attendancelogs.EmployeeCode');
                        
                        $cabExpensesDetails = DB::table('cab_expenses_details')
                        ->leftJoin('cab_details', 'cab_details.card_id', '=', 'cab_expenses_details.card_id')
                        ->where('cab_expenses_details.cab_expenses_date',$Attendancedate)
                        ->select(
                        'cab_expenses_details.cab_expenses_date as cab_expenses_date',
                        'cab_expenses_details.card_id as card_id',
                        'cab_details.per_day_rate as amount',
                        'cab_details.vehicle_no',
                        'cab_details.driver_name',
                        'cab_details.route'
                        )
                        ->groupBy('cab_expenses_details.cab_expenses_date', 'cab_expenses_details.card_id');
                        
                        
                        $combinedQuery = $attendanceLogs->unionAll($cabExpensesDetails);
                        
                        $cabList = $combinedQuery->get();
             
          //dd(DB::getQueryLog());
             
        return $cabList;
       
       } 
       
       
       
      public function employee_status_by_date_and_location_np_trait($flag,$deviceName,$Attendancedate)
       {
           

             //DB::enableQueryLog();               
            $emplistdata =DB::table('employeemaster')->select('employeemaster.employeeCode','emp_groupmaster.egroup_name','employeemaster.fullName',
            'employee_attendance_status.empAttStatus',
             'attendancelogs.AttendanceDate','employeemaster.misRate')
             ->join('attendancelogs','attendancelogs.EmployeeCode','=','employeemaster.employeeCode')
             ->join('employee_attendance_status','employee_attendance_status.empAttStatusId','=','attendancelogs.Status')
              ->leftJoin('emp_groupmaster','emp_groupmaster.egroup_id','=','employeemaster.egroup_id')
             ->where('attendancelogs.AttendanceDate',$Attendancedate)
            //  ->where('employeemaster.sub_company_id',5)
             ->where('attendancelogs.Status','!=',15);
           
            
              if($flag==1)
             {
                 
               $emplistdata->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(Punch_Records,')',1),'(',-1),'".$deviceName."')");    
               $emplistdata->whereRaw("employeemaster.cost=3");    

               $emplistdata->where('employeemaster.emp_cat_id',3);
  
             } elseif($flag==2)
             {
                $emplistdata->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(Punch_Records,')',1),'(',-1),'".$deviceName."')");      
                $emplistdata->whereRaw("employeemaster.cost !=3");    
                $emplistdata->where('employeemaster.emp_cat_id',3);
                 
             }
             elseif($flag==3)
             {
               $emplistdata->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(Punch_Records,')',1),'(',-1),'".$deviceName."')");    
               $emplistdata->where('employeemaster.emp_cat_id',2);
                 
             }
               elseif($flag==4)
             {
                 
                $emplistdata->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(Punch_Records,')',1),'(',-1),'".$deviceName."')");    
                $emplistdata->where('employeemaster.cost',1);
                
             }
             elseif($flag==5)
             {
                 
                 $emplistdata->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(Punch_Records,')',1),'(',-1),'".$deviceName."')");     
                $emplistdata->where('employeemaster.cost',2);
                
             }
                 elseif($flag==6)
             {
           
                
                 $emplistdata->where('employeemaster.branch_id',$deviceName);
                 $emplistdata->where('employeemaster.is_auto_attendance',1);
                
             }
             
              $emplistdata->whereNotIn('employeemaster.employee_status_id',[3,4]);
             $emplist=$emplistdata->get();
          //dd(DB::getQueryLog());
             
        return $emplist;
       
       } 
    
            public function employee_list_by_date_and_sub_company_operators_trait($deviceName,$status,$flag,$Attendancedate)
       {

             //DB::enableQueryLog();               
            $emplistdata =DB::table('employeemaster')->select('employeemaster.employeeCode','emp_groupmaster.egroup_name','employeemaster.fullName','employee_attendance_status.empAttStatus',
             'attendancelogs.AttendanceDate','employeemaster.misRate')
             ->join('attendancelogs','attendancelogs.EmployeeCode','=','employeemaster.employeeCode')
             ->join('employee_attendance_status','employee_attendance_status.empAttStatusId','=','attendancelogs.Status')
              ->leftJoin('emp_groupmaster','emp_groupmaster.egroup_id','=','employeemaster.egroup_id')
             ->whereRaw("FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(Punch_Records,')',1),'(',-1),'".$deviceName."')") 
             ->where('attendancelogs.AttendanceDate',$Attendancedate)
               ->whereNotIn('employeemaster.employee_status_id',[3,4]);
            //  ->where('employeemaster.sub_company_id',5)
             
             if($status==1)
             {
               $emplist=$emplistdata->where('attendancelogs.Status','!=',15);    
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
               $emplist=$emplistdata->where('employeemaster.egroup_id',71);
               $emplist=$emplistdata->get();
               
             } elseif($status==2)
             {
                 
              $emplist=$emplistdata->where('attendancelogs.Status','=',15);    
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
               $emplist=$emplistdata->where('employeemaster.egroup_id',71);
               $emplist=$emplistdata->get();   
                 
             }
             
          //dd(DB::getQueryLog());
             
        return $emplist;
       
       } 
       
                   public function employee_list_by_date_and_branch_operators_trait($branch_id,$status,$flag,$Attendancedate)
       {

             //DB::enableQueryLog();               
            $emplistdata =DB::table('employeemaster')->select('employeemaster.employeeCode','emp_groupmaster.egroup_name','employeemaster.fullName','employee_attendance_status.empAttStatus',
             'attendancelogs.AttendanceDate','employeemaster.misRate')
             ->join('attendancelogs','attendancelogs.EmployeeCode','=','employeemaster.employeeCode')
             ->join('employee_attendance_status','employee_attendance_status.empAttStatusId','=','attendancelogs.Status')
              ->leftJoin('emp_groupmaster','emp_groupmaster.egroup_id','=','employeemaster.egroup_id')
             ->whereRaw("employeemaster.mis_location='".$branch_id."'") 
             ->where('attendancelogs.AttendanceDate',$Attendancedate)
               ->whereNotIn('employeemaster.employee_status_id',[3,4]);
            //  ->where('employeemaster.sub_company_id',5)
             
             if($status==1)
             {
               $emplist=$emplistdata->whereNotIn('attendancelogs.Status',[15,16,21,25]);    
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
               $emplist=$emplistdata->where('employeemaster.egroup_id',71);
               $emplist=$emplistdata->get();
               
             } elseif($status==2)
             {
                 
              $emplist=$emplistdata->where('attendancelogs.Status','=',15);    
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
               $emplist=$emplistdata->where('employeemaster.egroup_id',71);
               $emplist=$emplistdata->get();   
                 
             }
             
          //dd(DB::getQueryLog());
             
        return $emplist;
       
       } 
       
        public function employee_list_branch_wise_attendance_cost_trait($flag,$mis_location,$Attendancedate)
       {

             //DB::enableQueryLog();  
             
        //       if($flag==4)
        //      {
                 
        //      $emplist = DB::table('employeeCostingSummaryTemp')
        //   ->select(
        //     'employeemaster.fullName','employeemaster.employeeCode','emp_groupmaster.egroup_name',
        //     'employeemaster.misRate')
        //      ->join('employeemaster','employeemaster.employeeCode','=','employeeCostingSummaryTemp.EmployeeCode')
        //       ->leftJoin('emp_groupmaster','emp_groupmaster.egroup_id','=','employeemaster.egroup_id')
        //     ->whereIn('employeeCostingSummaryTemp.deptCostId',[10,15,19,23,30,44,71,72,73])
        //     ->where('employeeCostingSummaryTemp.AttendanceDate',$Attendancedate)
        //     ->where('employeemaster.branch_id',$branch_id)
        //     ->get();    
                 
        //      } else{
                $emplistdata = DB::table('attendancelogs')
                ->select(
                'employeemaster.fullName','employeemaster.employeeCode','emp_groupmaster.egroup_name','attendancelogs.AttendanceDate','sub_company_master.sub_company_name','employeemaster.misRate','attendancelogs.Status','employee_attendance_status.empAttStatus')
                ->join('employeemaster','employeemaster.employeeCode','=','attendancelogs.EmployeeCode')
                ->leftJoin('emp_groupmaster','emp_groupmaster.egroup_id','=','employeemaster.egroup_id')
                ->join('sub_company_master','sub_company_master.sub_company_id','=','employeemaster.sub_company_id')  
                ->join('employee_attendance_status','employee_attendance_status.empAttStatusId','=','attendancelogs.Status')   
                ->where('attendancelogs.AttendanceDate',$Attendancedate)
                ->where('employeemaster.mis_location', $mis_location);
         
             
             if($flag==1)
             {
               $emplist=$emplistdata->whereRaw("employeemaster.cost=3");    
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
  
             } elseif($flag==2)
             {
                $emplist=$emplistdata->whereRaw("employeemaster.cost !=3");    
                $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
                 
             }
             elseif($flag==3)
             {
           
                $emplist=$emplistdata->where('employeemaster.emp_cat_id',2);
                 
             }
             elseif($flag==4)
             {
           
                $emplist=$emplistdata->where('employeemaster.cost',1);
                 
             } 
             elseif($flag==5)
             {
           
                $emplist=$emplistdata->where('employeemaster.cost',2);
                 
             }
             
              
               $emplist=$emplistdata->where('employeemaster.delflag',0);
              $emplist=$emplistdata->whereNotIn('employeemaster.employee_status_id',[3,4]);
               $emplist=$emplistdata->whereNotIn('attendancelogs.Status',[15,16,21,25]); 
              
               $emplist=$emplistdata->get();
            // }
              
             
          //dd(DB::getQueryLog());
             
        return $emplist;
       
       } 
       
       
             public function employee_list_by_date_and_branch_trait($branch_id,$status,$flag,$Attendancedate)
       {
          //DB::enableQueryLog();
            
            $emplistdata =DB::table('employeemaster')
             ->select('employeemaster.employeeCode','emp_groupmaster.egroup_name','employeemaster.fullName','employeemaster.misRate','employee_attendance_status.empAttStatus','attendancelogs.AttendanceDate')
             ->join('attendancelogs','attendancelogs.EmployeeCode','=','employeemaster.employeeCode')
             ->join('employee_attendance_status','employee_attendance_status.empAttStatusId','=','attendancelogs.Status')
             ->leftJoin('emp_groupmaster','emp_groupmaster.egroup_id','=','employeemaster.egroup_id')
             ->where('employeemaster.branch_id',$branch_id)
             ->whereNotIn('employeemaster.employee_status_id',[3,4])
             ->where('attendancelogs.AttendanceDate',$Attendancedate)
             ->whereNotIn('attendancelogs.Status',[15,16,21,25]);
             
             
             if($flag==1)
             {
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',2);
               $emplist=$emplistdata->get();
               
             } elseif($flag==2)
             {
                 
               $emplist=$emplistdata->where('employeemaster.emp_cat_id',3);
               $emplist=$emplistdata->get();   
                 
             }   
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           //dd(DB::getQueryLog());
       
        return $emplist;
       }
       
       public function employee_gration_mapping_list_trait($gration_mapping_type_id,$level_gration_id)
       {
          //DB::enableQueryLog();
            
         $emplist = DB::table('employeemaster')
    ->select(
        'employeemaster.employeeCode',
        'lgm.level_gration_name',
        'gmt.gration_map_type_name',
        'employeemaster.fullName',
        'employeemaster.misRate',
        'eas.empAttStatus'
    )
    ->join('level_gration_mapping_masters as lgm', 'lgm.level_gration_id', '=', 'employeemaster.level_gration_id')
    ->join('gration_mapping_types as gmt', 'gmt.gration_mapping_type_id', '=', 'employeemaster.gration_mapping_type_id')
    ->join('attendancelogs as al', 'al.EmployeeCode', '=', 'employeemaster.employeeCode')
    ->join('employee_attendance_status as eas', 'eas.empAttStatusId', '=', 'al.Status')  
    ->where([
        'employeemaster.gration_mapping_type_id' => $gration_mapping_type_id,
        'employeemaster.level_gration_id' => $level_gration_id,
        'al.AttendanceDate' => date('Y-m-d')
    ])
    ->groupBy('employeemaster.employeeCode')
    ->get();
             
           //dd(DB::getQueryLog());
       
        return $emplist;
       }    
       
        public function continues_absent_employee__list_trait($branch_id,$empArray)
       {
          //DB::enableQueryLog();
            
            $emplist =DB::table('employeemaster')
             ->select('employeemaster.employeeCode','employeemaster.fullName')
            ->whereIn('employeeCode',json_decode($empArray))->get();
             
             
           //dd(DB::getQueryLog());
       
        return $emplist;
       }   
       
        public function get_mis_location_trait($maincompany_id)
        {
        $html = '';
        if (!$maincompany_id) {
        $html = '<option value="">--Select MIS Location--</option>';
        } else {
        $html = '<option value="">--Select MIS Location--</option>';
        
        $states = DB::table('sub_company_master')->where('maincompany_id',$maincompany_id)->where('delflag', 0)->get();
        //$states = TalukaModel::where('dist_id', $request->dist_id)->get();
        foreach ($states as $rowsub) {
        $html .= '<option value="'.$rowsub->erp_sub_company_id.'">'.$rowsub->sub_company_name.'</option>';
        
        }
        }
        
        return $html;
        
        } 
          public function get_sales_order_by_style_trait()
        {
        $html = '';

        $html = '<option value="">--Select--</option>';
        
        $orderList = DB::table('buyer_purchse_order_master')->whereRaw("buyer_purchse_order_master.tr_code NOT 
        IN(select sales_order_no from assigned_to_orders where assigned_to_orders.sales_order_no=buyer_purchse_order_master.tr_code)")->get();
       
        foreach ($orderList as $rowOrder) {
        $html .= '<option value="'.$rowOrder->tr_code.'">'.$rowOrder->tr_code.'</option>';
        
        }
        
        
        return $html;
        
        } 
    

}