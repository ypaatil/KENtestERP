<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\MainStyleModel; 
use App\Models\OperationMasterModel;
use App\Models\OperationDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\OperationNameMasterModel;
use App\Models\CuttingEntryModel; 
use App\Models\CuttingEntryDetailModel; 
use App\Models\DailyProductionEntryModel; 
use App\Models\DailyProductionEntryDetailModel; 
use Session;
use DataTables;
use DB;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeDetailedProductionExport;
use App\Exports\EmployeeDetailedSalaryReportExport;
use App\Exports\EmployeeProductionEntryExport;



ini_set('memory_limit', '1G');

class DailyProductionEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '246')
        ->first();
        
        // $DailyProductionEntryList = DailyProductionEntryModel::select('daily_production_entry.*','usermaster.username','employeemaster.employeeName')
        //                 ->join('usermaster', 'usermaster.userId', '=', 'daily_production_entry.userId', 'left outer')
        //                 ->join('employeemaster', 'employeemaster.employeeCode', '=', 'daily_production_entry.employeeCode', 'left outer')
        //                 ->where('daily_production_entry.delflag','=', '0')
        //                 ->orderBy('daily_production_entry.dailyProductionEntryId','DESC')
        //                 ->get();  
        
         $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
          $employeeCode = isset($request->employeeCode) ? $request->employeeCode : "";
           $dailyProductionEntryId = isset($request->dailyProductionEntryId) ? $request->dailyProductionEntryId : "";
        
        $data = DailyProductionEntryModel::select(
                'daily_production_entry.*',
                'usermaster.username',
                'employeemaster_operation.fullName',
                DB::raw('GROUP_CONCAT(DISTINCT daily_production_entry_details.sales_order_no ORDER BY daily_production_entry_details.sales_order_no ASC SEPARATOR ", ") as salesOrders'),
                DB::raw('GROUP_CONCAT(DISTINCT color_master.color_name ORDER BY color_master.color_name ASC SEPARATOR ", ") as colors,GROUP_CONCAT(DISTINCT ob_details.operation_name ORDER BY ob_details.operation_name ASC SEPARATOR ", ") as operation_name'),
                DB::raw('SUM(daily_production_entry_details.stiching_qty) as total_stiching_qty'),
                DB::raw('SUM(daily_production_entry_details.amount) as total_amount')
              )
            ->join('usermaster', 'usermaster.userId', '=', 'daily_production_entry.userId', 'left outer')
            ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry.employeeCode', 'left outer')
            ->leftJoin('daily_production_entry_details', 'daily_production_entry_details.dailyProductionEntryId', '=', 'daily_production_entry.dailyProductionEntryId')
               ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'daily_production_entry_details.sales_order_no')
                ->leftJoin('ob_details', function ($join) {
                $join->on('ob_details.operation_id', '=', 'daily_production_entry_details.operationNameId')
                ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
                })
            ->leftJoin('color_master', 'color_master.color_id', '=', 'daily_production_entry_details.color_id');
          
       
       
            if($fromDate != "" && $toDate != "")
            {
            
            $data->whereBetween('daily_production_entry.dailyProductionEntryDate', [$fromDate,$toDate]);
            }
            
            if($employeeCode!="")
            {
                 $data->where('daily_production_entry.employeeCode',$employeeCode);
         
            } else{
                
             
            }
            
                 if($dailyProductionEntryId!="")
            {
                 $data->where('daily_production_entry.dailyProductionEntryId',$dailyProductionEntryId);
         
            } else{
                
             
            }
            
            
            
            

            if(Session::get('user_type')==1)
            {
         
            } else{
                
              $data->where('daily_production_entry.vendorId',Session::get('vendorId'));
            }
            
              $data->where('daily_production_entry.delflag', '=', '0');
              $data->groupBy('daily_production_entry.dailyProductionEntryId', 'usermaster.username', 'employeemaster_operation.fullName');
              $data->orderBy('daily_production_entry.dailyProductionEntryId', 'DESC');   
              $DailyProductionEntryList=$data->paginate(50);
            
              
                      
        // if ($request->ajax()) 
        // {
    
        //   $data = DailyProductionEntryModel::select(
        //         'daily_production_entry.*',
        //         'usermaster.username',
        //         'employeemaster_operation.fullName',
        //         DB::raw('GROUP_CONCAT(DISTINCT daily_production_entry_details.sales_order_no ORDER BY daily_production_entry_details.sales_order_no ASC SEPARATOR ", ") as sales_order_no'),
        //         DB::raw('GROUP_CONCAT(DISTINCT color_master.color_name ORDER BY color_master.color_name ASC SEPARATOR ", ") as color_name'),
        //         DB::raw('SUM(daily_production_entry_details.stiching_qty) as stiching_qty'),
        //         DB::raw('SUM(daily_production_entry_details.amount) as total_amount')
        //     )
        //     ->join('usermaster', 'usermaster.userId', '=', 'daily_production_entry.userId', 'left outer')
        //     ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry.employeeCode', 'left outer')
        //     ->leftJoin('daily_production_entry_details', 'daily_production_entry_details.dailyProductionEntryId', '=', 'daily_production_entry.dailyProductionEntryId')
        //     ->leftJoin('color_master', 'color_master.color_id', '=', 'daily_production_entry_details.color_id');
            
            
        //      if(Session::get('user_type')==1)
        //     {
         
        //     } else{
                
        //       $data->where('daily_production_entry.vendorId',Session::get('vendorId'));
        //     }
            
        //       $data->where('daily_production_entry.delflag', '=', '0');
        //       $data->groupBy('daily_production_entry.dailyProductionEntryId', 'usermaster.username', 'employeemaster_operation.fullName');
        //       $data->orderBy('daily_production_entry.dailyProductionEntryId', 'DESC');   
           

        //     return Datatables::of($data)
        //     ->addIndexColumn()
        //      ->addColumn('action1', function($row)
        //     {
                
                
        //       $detailfetch= DB::table('daily_production_entry_details')
        //             ->select('ob_details.operation_name')
        //             ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'daily_production_entry_details.sales_order_no')
        //             ->join('ob_details', function ($join) {
        //             $join->on('ob_details.operation_id', '=', 'daily_production_entry_details.operationNameId')
        //             ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
        //             })
        //             ->where('dailyProductionEntryId',$row['dailyProductionEntryId'])->get();
                    
                    
        //             $detailArray = $detailfetch instanceof \Illuminate\Support\Collection ? $detailfetch->toArray() : $detailfetch;
                    
        //             $detailArr = array_column($detailArray, 'operation_name');    
                
                
        //         $btn = '<a >'.implode(",",array_unique($detailArr)).'</a>';
        //         return $btn;
        //     })   
        //     ->addColumn('action2', function($row)
        //     {
        //         $btn = '<a class="btn btn-outline-secondary btn-sm edit"  href="'.route('DailyProductionEntry.edit', $row['dailyProductionEntryId']).'" > <i class="fas fa-pencil-alt"></i></a>';
        //         return $btn;
        //     })
        //     ->addColumn('action3', function($row)
        //     {
        //         $btn3 = '<a class="btn btn-outline-secondary btn-sm  DeleteRecord" id="DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['dailyProductionEntryId'].'"  data-route="'.route('DailyProductionEntry.destroy', $row['dailyProductionEntryId']).'">  <i class="fas fa-trash"></i></a>';
        //         return $btn3;
        //     })
        //     ->rawColumns(['action1','action2','action3'])
        //     ->make(true);
        // }
        
        
               $empList = DB::table('employeemaster_operation')->select('employeemaster_operation.*')
            ->join('daily_production_entry', 'daily_production_entry.employeeCode', '=', 'employeemaster_operation.employeeCode')
            ->where('employeemaster_operation.delflag','=', 0)
            ->groupBy('daily_production_entry.employeeCode')
            ->get(); 
            
       	  
	      $employeeMap=[];
          
      foreach ($empList as $rowEmp) {
         $employeeMap[] = [
        'employeeCode' => $rowEmp->employeeCode,
         'employeeName' => $rowEmp->fullName
        ];
        
        }  
        
        
        
        
        
            

        return view('dailyProductionEntryMasterList', compact('chekform','DailyProductionEntryList','employeeMap','employeeCode','dailyProductionEntryId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $MainStyleList = DB::table('main_style_master')->where('main_style_master.delflag','=', '0')->get();  
        $SalesOrderList = DB::table('cutting_entry_master')->where('cutting_entry_master.delflag','=', '0')->groupBy('sales_order_no')->get(); 
        $lineList = DB::table('line_master')->where('delflag','=', '0')->where('Ac_code',Session::get('vendorId'))->get();   
        $OperationList = DB::table('operation_name_master')->where('delflag','=', '0')->get();   

        //             ->join('buyer_purchase_order_detail','buyer_purchase_order_detail.color_id','=','color_master.color_id')   
        //             ->groupBy('buyer_purchase_order_detail.color_id')
        //             ->get();
        // $sizeList = DB::table('size_detail')
        //             ->join('buyer_purchse_order_master','buyer_purchse_order_master.sz_code','=','size_detail.sz_code')
        //             ->where('buyer_purchse_order_master.delflag','=', '0') 
        //             ->get();   
        
        $ColorList = DB::table('color_master')->select('color_master.color_id', 'color_master.color_name')->get();  
        $employeeList = DB::table('employeemaster_operation')
        ->join('sub_company_master','sub_company_master.sub_company_id','=','employeemaster_operation.sub_company_id')
        ->select('employeemaster_operation.employeeCode',DB::raw('employeemaster_operation.fullName as employeeName,sub_company_master.sub_company_name'))
        ->where('emp_cat_id',3)->whereNotIn('employee_status_id',[3,4])->where('employeemaster_operation.delflag','=', '0')->get();   
                  
        return view('DailyProductionEntryMaster',compact('MainStyleList','SalesOrderList','lineList','OperationList','employeeList','ColorList'));
    }
    
    
    public function previous_production_exist_record(Request $request)
    {
        
          $bundleNo=$request->bundleNo;
          $sales_order_no=$request->sales_order_no;
          $operationNameId=$request->operationNameId;
          $color_id=$request->color_id;
          

          $result=DB::table('daily_production_entry_details')
          ->select('daily_production_entry_details.dailyProductionEntryDate','daily_production_entry_details.sales_order_no','daily_production_entry_details.stiching_qty',
          'daily_production_entry_details.bundleNo','daily_production_entry_details.operationNameId','color_master.color_name','employeemaster_operation.fullName',
          'daily_production_entry_details.employeeCode','size_detail.size_name')
          ->join('color_master','color_master.color_id','=','daily_production_entry_details.color_id')
          ->join('employeemaster_operation','employeemaster_operation.employeeCode','=','daily_production_entry_details.employeeCode')
         ->join('size_detail','size_detail.size_id','=','daily_production_entry_details.size_id')    
          ->where(['bundleNo'=>$bundleNo,'sales_order_no'=>$sales_order_no,'operationNameId'=>$operationNameId,'daily_production_entry_details.color_id'=>$color_id,'daily_production_entry_details.vendorId'=>Session::get('vendorId')])->get();
         
     
          
          
        return response()->json(['result'=>$result]);  
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // echo '<pre>'; print_r($_POST);exit;
            $data1=array(
                'dailyProductionEntryDate'=>$request->dailyProductionEntryDate, 
                'employeeCode'=>$request->employeeCode,
                'employeeName'=>$request->employeeName,
                'userId'=>$request->userId,
                'vendorId'=>Session::get('vendorId'),
                'delflag'=>'0'
            );
         
            DailyProductionEntryModel::insert($data1);
            
            $dailyProductionEntryId = DB::table('daily_production_entry')->max('dailyProductionEntryId');
            for($i=0;$i<count($request->operationNameId);$i++)
            {
                $data2=array(
                'dailyProductionEntryId'=>$dailyProductionEntryId,
                 'dailyProductionEntryDate'=>$request->dailyProductionEntryDate, 
                 'employeeCode'=>$request->employeeCode, 
                'operationNameId'=>$request->operationNameId[$i], 
                'bundle_track_code'=>$request->bundle_track_code[$i], 
                'lotNo'=>$request->lotNo[$i], 
                'sales_order_no'=>$request->sales_order_no[$i], 
                'bundleNo'=>$request->bundleNo[$i],  
                'slipNo'=>$request->slipNo[$i],   
                'line_no'=>$request->line_no[$i],   
                'stiching_qty'=>$request->stiching_qty[$i],  
                'cut_panel_issue_qty'=>$request->cut_panel_issue_qty[$i],    
                'rate'=>$request->rate[$i],  
                'amount'=>$request->amount[$i], 
                'color_id'=>$request->color_id[$i], 
                'size_id'=>$request->size_id[$i], 
                'vendorId'=>Session::get('vendorId'),
                );
                
                DailyProductionEntryDetailModel::insert($data2);   
            } 
            return redirect()->route('DailyProductionEntry.index')->with('message', 'Data Saved Succesfully');  
      
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($bom_code)
    {
        
         $BOMList = CuttingEntryModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'bom_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'bom_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'bom_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'bom_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'bom_master.fg_id', 'left outer') 
        ->where('bom_master.delflag','=', '0')
        ->where('bom_master.bom_code','=', $bom_code) 
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name'
        ,'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
   
        return view('budgetPrint', compact('BOMList'));  
      
    }
 
 
    public function edit($id)
    {   
        $DailyProductionEntryList = DailyProductionEntryModel::find($id);
        //DB::enableQueryLog();
        $DailyProductionEntryDetailList = DB::table('daily_production_entry_details')
                                        ->select('daily_production_entry_details.*','color_master.color_name','size_detail.size_name')
                                        ->leftjoin('size_detail','size_detail.size_id', '=', 'daily_production_entry_details.size_id')
                                        ->leftjoin('color_master','color_master.color_id', '=', 'daily_production_entry_details.color_id')
                                        ->where('dailyProductionEntryId','=', $id)
                                        ->get();    
        //dd(DB::getQueryLog());
        $SalesOrderList = DB::table('cutting_entry_master')->where('cutting_entry_master.delflag','=', '0')->groupBy('sales_order_no')->get(); 
        
        
                    $SalesOrderMap=[];
          
      foreach ($SalesOrderList as $rowSales) {
         $SalesOrderMap[$rowSales->sales_order_no][] = [
        'sales_order_no' => $rowSales->sales_order_no
        ];
        
        }
         
        // $OperationNameList = DB::table('operation_name_master')->select('operation_name_master.operationNameId', 'operation_name_master.operation_name')  
        //     ->get();
        

        
    
        $lineList = DB::table('line_master')->where('delflag','=', '0')->where('Ac_code',Session::get('vendorId'))->get();  
        
        // $sizeList = DB::table('size_detail')
        //             ->join('buyer_purchse_order_master','buyer_purchse_order_master.sz_code','=','size_detail.sz_code')
        //             ->where('buyer_purchse_order_master.delflag','=', '0') 
        //             ->get();   
                    
       // $employeeList = DB::table('employeemaster')->select('employeemaster.employeeCode','employeemaster.employeeName')->where('delflag','=', '0')->get();
        
             $employeeList = DB::table('employeemaster_operation')
        ->join('sub_company_master','sub_company_master.sub_company_id','=','employeemaster_operation.sub_company_id')
        ->select('employeemaster_operation.employeeCode',DB::raw('employeemaster_operation.fullName as employeeName,sub_company_master.sub_company_name'))
         ->where('emp_cat_id',3)->whereNotIn('employee_status_id',[3,4])->where('employeemaster_operation.delflag','=', '0')->get();   
         
            $employeeMap=[];
          
      foreach ($employeeList as $rowEmp) {
         $employeeMap[] = [
        'employeeCode' => $rowEmp->employeeCode,
         'employeeName' => $rowEmp->employeeName,
         'sub_company_name'=>$rowEmp->sub_company_name
        ];
        
        }
         
        
         $ColorList = DB::table('color_master')->select('color_master.color_id', 'color_master.color_name')->get();  
         
            
            $colorMap=[];
            
            foreach($ColorList as $rowColor) {
            $colorMap[$rowColor->color_id][] = [
            'color_id' => $rowColor->color_id,
            'color_name' => $rowColor->color_name
            ];
            
            }

         
         
            
        return view('DailyProductionEntryMasterEdit',compact('DailyProductionEntryList','DailyProductionEntryDetailList','SalesOrderMap','lineList','employeeMap','colorMap'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $dailyProductionEntryId)
    {  
            //echo '<pre>'; print_R($_POST);exit;
             $data1=array(
                'dailyProductionEntryDate'=>$request->dailyProductionEntryDate,  
                'employeeCode'=>$request->employeeCode,
                'employeeName'=>$request->employeeName,
                'userId'=>$request->userId,
                'delflag'=>'0',
               'vendorId'=>Session::get('vendorId')   
            );
 
            $dailyProductionEntryList = DailyProductionEntryModel::findOrFail($dailyProductionEntryId); 
 
            $dailyProductionEntryList->fill($data1)->save();
            DB::table('daily_production_entry_details')->where('dailyProductionEntryId', $dailyProductionEntryId)->delete();  
            
            for($i=0;$i<count($request->operationNameId);$i++)
            {
                $data2=array(
                'dailyProductionEntryId'=>$dailyProductionEntryId,
                'dailyProductionEntryDate'=>$request->dailyProductionEntryDate, 
                'employeeCode'=>$request->employeeCode,    
                'operationNameId'=>$request->operationNameId[$i], 
                'bundle_track_code'=>$request->bundle_track_code[$i], 
                'lotNo'=>$request->lotNo[$i], 
                'sales_order_no'=>$request->sales_order_no[$i], 
                'bundleNo'=>$request->bundleNo[$i],  
                'slipNo'=>$request->slipNo[$i],   
                'line_no'=>$request->line_no[$i],   
                'stiching_qty'=>$request->stiching_qty[$i],  
                'cut_panel_issue_qty'=>$request->cut_panel_issue_qty[$i],    
                'rate'=>$request->rate[$i],  
                'amount'=>$request->amount[$i], 
                'color_id'=>$request->color_id[$i], 
                'size_id'=>$request->size_id[$i], 
               'vendorId'=>Session::get('vendorId')   
                );
                
                DailyProductionEntryDetailModel::insert($data2);   
            } 
            return redirect()->route('DailyProductionEntry.index')->with('message', 'Data Updated Succesfully');  
              
    }
     
     
      
    public function destroy($id)
    { 
        DB::table('daily_production_entry')->where('dailyProductionEntryId', $id)->delete(); 
        DB::table('daily_production_entry_details')->where('dailyProductionEntryId', $id)->delete();  
        Session::flash('messagedelete', 'Deleted record successfully');  
    }
     
    public function GetDailyProductionOperationList(Request $request)
    {
        $OperationList = DB::table('operation_name_master')->select('operation_name_master.operationNameId', 'operation_name_master.operation_name')  
                    ->join('operation_details','operation_details.operationNameId','=','operation_name_master.operationNameId')
                    ->join('operation_master','operation_master.operationId','=','operation_details.operationId')
                    ->where('operation_name_master.main_style_id','=',$request->main_style_id) 
                    ->where('sales_order_no','=',$request->sales_order_no) 
                    ->get();
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($OperationList as $row) 
        {
            $html .= '<option value="'.$row->operationNameId.'">'.$row->operation_name.'</option>';
        } 
        
        $ColorList = DB::table('color_master')->select('color_master.color_id', 'color_master.color_name')  
                    ->join('buyer_purchase_order_detail','buyer_purchase_order_detail.color_id','=','color_master.color_id') 
                    ->where('buyer_purchase_order_detail.tr_code','=',$request->sales_order_no) 
                    ->groupBy('buyer_purchase_order_detail.color_id')
                    ->get();
         
        $colorHtml = '';
        $colorHtml = '<option value="">--Select--</option>';
        
        foreach ($ColorList as $row) 
        {
            $colorHtml .= '<option value="'.$row->color_id.'">'.$row->color_name.'</option>';
        } 
        
        return response()->json(['html' => $html,'colorHtml' => $colorHtml]);
    }
    
    public function GetEmpList()
    {
        config(['database.default' => 'hrms_database']);
 
    //   ->select('employeemaster.employeeCode','fullName','maincompany_id','sub_company_id','branch_id','employee_status_id','emp_cat_id','misRate','rate')   
        $emplList = DB::connection('hrms_database')->table('employeemaster')
                ->select('employeemaster.employeeCode','employeemaster.fullName')
                ->leftjoin('attendancelogs','attendancelogs.EmployeeCode','=','employeemaster.employeeCode')
                ->where('maincompany_id','=',1)
                ->whereIn('sub_company_id', [4,7,8,11]) 
                ->where('attendancelogs.AttendanceDate','=','2023-12-22')
                ->where('attendancelogs.Status','=',14)
                ->groupBy('employeemaster.employeeCode')
                ->get();
        config(['database.hrms_database' => 'mysql']);
        
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($emplList as $row) 
        {
            $html .= '<option value="'.$row->employeeCode.'">('.$row->employeeCode.') '.$row->fullName.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 
 
    public function GetBuyerPurchaseData(Request $request)
    {
        $BuyerList = DB::table('buyer_purchse_order_master')->select('main_style_master.mainstyle_name','main_style_master.mainstyle_id','fg_master.fg_id','fg_master.fg_name') 
                        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')  
                        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer') 
                        ->where('tr_code','=',$request->sales_order_no) 
                        ->first();
         
        $main_style_id = isset($BuyerList->mainstyle_id) ? $BuyerList->mainstyle_id : "";
        $mainstyle_name = isset($BuyerList->mainstyle_name) ? $BuyerList->mainstyle_name : "";
        $fg_id = isset($BuyerList->fg_id) ? $BuyerList->fg_id : "";
        $fg_name = isset($BuyerList->fg_name) ? $BuyerList->fg_name : "";
      
        return response()->json(['main_style_id' => $main_style_id,'mainstyle_name'=>$mainstyle_name, 'fg_id'=>$fg_id, 'fg_name' => $fg_name]);
    }
    
    public function GetPartList(Request $request)
    {
       
        $jobPartList = DB::table('job_part_detail')
            ->join('job_part_master', 'job_part_master.jpart_id', '=', 'job_part_detail.jpart_id', 'left outer') 
            ->join('fg_master', 'fg_master.fg_id', '=', 'job_part_master.fg_id', 'left outer') 
            // ->where('fg_master.fg_id','=', $request->fg_id)
            ->get();   
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($jobPartList as $row) 
        {
            $html .= '<option value="'.$row->jpart_id.'">'.$row->jpart_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
    
     
    public function GetCuttingEntryData(Request $request)
    {
         // DB::enableQueryLog();
        $CuttingEntryList = DB::table('cutting_entry_details')->select('cutting_entry_details.*',
        'color_master.color_name','color_master.color_id','size_detail.size_name','size_detail.size_id',
                        'cutting_entry_master.sales_order_no') 
                        ->join('cutting_entry_master', 'cutting_entry_master.cuttingEntryId', '=', 'cutting_entry_details.cuttingEntryId', 'left outer')  
                        ->join('color_master', 'color_master.color_id', '=', 'cutting_entry_details.color_id', 'left outer')  
                        ->join('size_detail', 'size_detail.size_id', '=', 'cutting_entry_details.size', 'left outer')  
                        ->where('cutting_entry_master.sales_order_no','=',$request->sales_order_no)   
                        ->where('cutting_entry_details.color_id','=',$request->color_id)      
                        ->where('cutting_entry_details.bundleNo','=',$request->bundleNo)   
                         ->where('cutting_entry_details.vendorId','=',Session::get('vendorId'))   
                        ->first();
          // dd(DB::getQueryLog());
        $slipNo = isset($CuttingEntryList->slipNo) ? $CuttingEntryList->slipNo : "";
     
        $lotNo = isset($CuttingEntryList->lotNo) ? $CuttingEntryList->lotNo : ""; 
        $sales_order_no = isset($CuttingEntryList->sales_order_no) ? $CuttingEntryList->sales_order_no : ""; 
        $bundleNo = isset($CuttingEntryList->bundleNo) ? $CuttingEntryList->bundleNo : "";
        $bundle_track_code = isset($CuttingEntryList->bundle_track_code) ? $CuttingEntryList->bundle_track_code : "";
        $cut_panel_issue_qty = isset($CuttingEntryList->cut_panel_issue_qty) ? $CuttingEntryList->cut_panel_issue_qty : "";
        $color_id = isset($CuttingEntryList->color_id) ? $CuttingEntryList->color_id : "";
        $color_name = isset($CuttingEntryList->color_name) ? $CuttingEntryList->color_name : "";
        $size_id = isset($CuttingEntryList->size_id) ? $CuttingEntryList->size_id : "";
        $size_name = isset($CuttingEntryList->size_name) ? $CuttingEntryList->size_name : "";
        
        $balanceCutData = DB::SELECT("SELECT ((SELECT ifnull((cut_panel_issue_qty),0) FROM cutting_entry_details 
                                            INNER JOIN cutting_entry_master ON cutting_entry_master.cuttingEntryId = cutting_entry_details.cuttingEntryId 
                                            WHERE bundleNo='".$request->bundleNo."' AND cutting_entry_details.color_id='".$request->color_id."'   AND  cutting_entry_master.sales_order_no='".$request->sales_order_no."' and cutting_entry_master.vendorId='".Session::get('vendorId')."'  GROUP BY bundleNo) 
                                            - (SELECT ifnull(SUM(stiching_qty),0) FROM daily_production_entry_details 
                                             INNER JOIN daily_production_entry ON daily_production_entry.dailyProductionEntryId = daily_production_entry_details.dailyProductionEntryId
                                             WHERE daily_production_entry_details.bundleNo='".$request->bundleNo."'   AND 
                                             daily_production_entry_details.sales_order_no='".$request->sales_order_no."' AND daily_production_entry_details.color_id='".$request->color_id."' AND operationNameId='".$request->operationNameId."' AND  daily_production_entry.vendorId='".Session::get('vendorId')."')) as balanceQty");
 
        $cut_panel_issue_qty = isset($balanceCutData[0]->balanceQty) ? $balanceCutData[0]->balanceQty : 0; 
        
        return response()->json(['size_id'=>$size_id,'size_name'=>$size_name,'slipNo' => $slipNo,'operationNameId'=>0,'lotNo'=>$lotNo,'sales_order_no'=>$sales_order_no,'bundleNo'=>$bundleNo,'operation_rate'=>0,'cut_panel_issue_qty'=>$cut_panel_issue_qty,'bundle_track_code'=>$bundle_track_code,'color_id'=>$color_id,'color_name'=>$color_name]);
    }
    

    
    public function GetEmployeeWiseDetailReport()
    { 

        return view('GetEmployeeWiseDetailReport');
    }
    
    public function employeeWiseDetailReport(Request $request)
    { 
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $employeeCode = $request->employeeCode;
        $sales_order_no = $request->sales_order_no;
        $mainstyle_id = $request->mainstyle_id;
        $report_type = $request->report_type;

        if($from_date != "" && $to_date != "")
        {
            $DateFilter = " AND daily_production_entry.dailyProductionEntryDate BETWEEN '".$from_date."' AND '".$to_date."'";
        }
        else
        {
             $DateFilter = "";
        }
        
        if($employeeCode > 0)
        {
            $employeeFilter = " AND daily_production_entry.employeeCode = '".$employeeCode."'";
        }
        else
        {
            $employeeFilter = "";
        }
        
        if($sales_order_no != "")
        {
            
            $salesOrderNoFilter = " AND daily_production_entry_details.sales_order_no = '".$sales_order_no."'";
        }
        else
        {
            $salesOrderNoFilter = "";
        }
        
        
        if($mainstyle_id != "")
        { 
            $mainstyleFilter = " AND cutting_entry_master.main_style_id = '".$mainstyle_id."'";
        }
        else
        {
            $mainstyleFilter = "";
        }
       // DB::enableQueryLog();
         
        //dd(DB::getQueryLog());
        if($report_type == 2)
        {
                $dailyProductionList = DB::SELECT("SELECT daily_production_entry_details.*, sum(stiching_qty) as total_stiching_qty,sum(amount) as total_amount,operation_name_master.operation_name,daily_production_entry.*,main_style_master.mainstyle_name
                                FROM daily_production_entry_details
                                LEFT JOIN daily_production_entry  ON daily_production_entry.dailyProductionEntryId = daily_production_entry_details.dailyProductionEntryId
                                LEFT JOIN cutting_entry_details  ON cutting_entry_details.bundle_track_code = daily_production_entry_details.bundle_track_code
                                LEFT JOIN cutting_entry_master  ON cutting_entry_master.cuttingEntryId = cutting_entry_details.cuttingEntryId
                                LEFT JOIN operation_name_master  ON operation_name_master.operationNameId = daily_production_entry_details.operationNameId
                                LEFT JOIN main_style_master  ON main_style_master.mainstyle_id = cutting_entry_master.main_style_id 
                                WHERE 1 ".$DateFilter." ".$employeeFilter." ".$salesOrderNoFilter." ".$mainstyleFilter." 
                                GROUP BY daily_production_entry.employeeCode");
                                
                return view('employeeWiseSummaryReport',compact('dailyProductionList','from_date','to_date','report_type'));
        }
        else
        { 
            
            $dailyProductionList = DB::SELECT("SELECT daily_production_entry_details.*, sum(stiching_qty) as total_stiching_qty,sum(amount) as total_amount,operation_name_master.operation_name,daily_production_entry.*,main_style_master.mainstyle_name
                                FROM daily_production_entry_details
                                LEFT JOIN daily_production_entry  ON daily_production_entry.dailyProductionEntryId = daily_production_entry_details.dailyProductionEntryId
                                LEFT JOIN cutting_entry_details  ON cutting_entry_details.bundle_track_code = daily_production_entry_details.bundle_track_code
                                LEFT JOIN cutting_entry_master  ON cutting_entry_master.cuttingEntryId = cutting_entry_details.cuttingEntryId
                                LEFT JOIN operation_name_master  ON operation_name_master.operationNameId = daily_production_entry_details.operationNameId
                                LEFT JOIN main_style_master  ON main_style_master.mainstyle_id = cutting_entry_master.main_style_id 
                                WHERE 1 ".$DateFilter." ".$employeeFilter." ".$salesOrderNoFilter." ".$mainstyleFilter." 
                                GROUP BY daily_production_entry_details.sales_order_no,cutting_entry_master.main_style_id,daily_production_entry_details.bundle_track_code");
                                
            return view('employeeWiseDetailReport',compact('dailyProductionList','from_date','to_date','report_type'));
        }
    }
    
    public function GetHRMSCompanyList()
    {
        config(['database.default' => 'hrms_database']);
 
    //   ->select('employeemaster.employeeCode','fullName','maincompany_id','sub_company_id','branch_id','employee_status_id','emp_cat_id','misRate','rate')   
        $companylList = DB::connection('hrms_database')->table('maincompany_master')
                ->select('maincompany_master.maincompany_id','maincompany_master.maincompany_name') 
                ->get();
        config(['database.hrms_database' => 'mysql']);
        
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($companylList as $row) 
        {
            $html .= '<option value="'.$row->maincompany_id.'">'.$row->maincompany_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 
    
     
    public function GetHRMSSubCompanyList(Request $request)
    {
        config(['database.default' => 'hrms_database']);
 
    //   ->select('employeemaster.employeeCode','fullName','maincompany_id','sub_company_id','branch_id','employee_status_id','emp_cat_id','misRate','rate')   
        $companylList = DB::connection('hrms_database')->table('sub_company_master')
                ->select('sub_company_master.sub_company_id','sub_company_master.sub_company_name') 
                ->where('sub_company_master.maincompany_id','=',$request->maincompany_id)  
                ->get();
        config(['database.hrms_database' => 'mysql']);
        
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($companylList as $row) 
        {
            $html .= '<option value="'.$row->sub_company_id.'">'.$row->sub_company_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 
    
         
    public function GetHRMSDepartmentList(Request $request)
    {
        config(['database.default' => 'hrms_database']);
 
    //   ->select('employeemaster.employeeCode','fullName','maincompany_id','sub_company_id','branch_id','employee_status_id','emp_cat_id','misRate','rate')   
        $companylList = DB::connection('hrms_database')->table('department_master')
                ->select('department_master.dept_id','department_master.dept_name') 
                ->where('department_master.branch_id','=',$request->sub_company_id)  
                ->get();
        config(['database.hrms_database' => 'mysql']);
        
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($companylList as $row) 
        {
            $html .= '<option value="'.$row->dept_id.'">'.$row->dept_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 
    
        
    public function GetHRMSEmployeeList(Request $request)
    {
        config(['database.default' => 'hrms_database']);
 
    //   ->select('employeemaster.employeeCode','fullName','maincompany_id','sub_company_id','branch_id','employee_status_id','emp_cat_id','misRate','rate')   
        $emplList = DB::connection('hrms_database')->table('employeemaster')
                ->select('employeemaster.employeeCode','employeemaster.fullName')
                ->where('maincompany_id','=',$request->maincompany_id)
                ->where('sub_company_id','=',$request->sub_company_id)
                ->get();
        config(['database.hrms_database' => 'mysql']);
        
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($emplList as $row) 
        {
            $html .= '<option value="'.$row->employeeCode.'">('.$row->employeeCode.') '.$row->fullName.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 
    
    public function GetEmpWiseSalesOrderList(Request $request)
    {
       
        $ProductionEntryList = DB::table('daily_production_entry_details')
             ->join('daily_production_entry', 'daily_production_entry.dailyProductionEntryId', '=', 'daily_production_entry_details.dailyProductionEntryId', 'left outer')  
             ->where('daily_production_entry.employeeCode','=', $request->employeeCode)
             ->groupBy('daily_production_entry_details.sales_order_no')
             ->get();   
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($ProductionEntryList as $row) 
        {
            $html .= '<option value="'.$row->sales_order_no.'">'.$row->sales_order_no.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
     
         
    public function GetEmpWiseMainStyleList(Request $request)
    {
       
        $MainStyleList = DB::table('cutting_entry_master')
             ->select('main_style_master.mainstyle_name','main_style_master.mainstyle_id')
             ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'cutting_entry_master.main_style_id', 'left outer')  
             ->where('cutting_entry_master.sales_order_no','=', $request->sales_order_no)
             ->groupBy('cutting_entry_master.main_style_id')
             ->get();   
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($MainStyleList as $row) 
        {
            $html .= '<option value="'.$row->mainstyle_id.'">'.$row->mainstyle_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
  
    public function SetDailyOperator(Request $request)
    { 
        
        $dopDate = isset($request->dopDate) ? $request->dopDate : "";
        $company_id = isset($request->company_id) ? $request->company_id : 0; 
        $line_no = isset($request->line_no) ? $request->line_no : 0; 
        $employeecode = isset($request->employeecode) ? $request->employeecode : "";
        $sub_company_id = isset($request->sub_company_id) ? $request->sub_company_id : 0;
        
        DB::table('daily_operators_line_wise')->where('dopDate','=', $dopDate)->where('employeecode','=', $employeecode)->delete(); 
        
        DB::SELECT('INSERT INTO daily_operators_line_wise(dopDate,company_id,line_no,employeecode,sub_company_id)
                 select "'.$dopDate.'",'.$company_id.','.$line_no.','.$employeecode.','.$sub_company_id);   
                 
        return response()->json(['html' => 1]);
    }
    public function DailyOperatorsLineWise(Request $request)
    {  
        DB::table('kenerp_KenGlobalERP.employeemaster1')->delete();
        DB::select('call StoreHRMsEmployee()');
        
        $dopDate = isset($request->dopDate) ? $request->dopDate : "";
        $company_id = isset($request->company_id) ? $request->company_id : 0;
        $sub_company_id = isset($request->sub_company_id) ? $request->sub_company_id : 0;
         
        return view('DailyOperatorsLineWise',compact('company_id','sub_company_id','dopDate'));
    }
        
    public function productionCostReport(Request $request)
    {  
        DB::table('kenerp_KenGlobalERP.employeemaster1')->delete();
        DB::table('kenerp_KenGlobalERP.attendancelogs')->delete();
        
        DB::select('call StoreHRMsEmployee()');
        DB::select('call StoreAttendanceLogs()');
        
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        
        $allDates = $this->getAllDatesBetween($fromDate, $toDate);
        
        return view('productionCostReport',compact('fromDate','toDate','allDates'));
    }
    
    public function EmployeeListCostingWise($date)
    {  
       // DB::enableQueryLog();

        $empList = DB::SELECT("SELECT employeemaster1.employeeCode,employeemaster1.fullName,employeemaster1.misRate,employee_attendance_status.empAttStatus as status FROM employeemaster1 
                              INNER JOIN attendancelogs ON attendancelogs.employeeCode = employeemaster1.employeeCode
                              INNER JOIN employee_attendance_status ON employee_attendance_status.empAttStatusId = attendancelogs.Status 
                              WHERE attendancelogs.Status != 15 AND AttendanceDate='".$date."' AND employeemaster1.dept_id = 5 AND employeemaster1.mis_location IN(56,115) GROUP BY attendancelogs.employeeCode
                              ");
         //  dd(DB::getQueryLog());                   
        return view('EmployeeListCostingWise',compact('empList'));
    }
    
    public function getAllDatesBetween($fromDate, $toDate) 
    {
        $dates = [];
        
        $currentDate = new DateTime($fromDate);
        $endDate = new DateTime($toDate);
        
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->modify('+1 day');
        }
        
        return $dates;
    }
    
    
    public function operationWiseProductionList(Request $request)
    {
       
        $operationList = DB::table('operation_details')->select('operation_name_master.operationNameId','operation_name_master.operation_name')
            ->join('operation_master', 'operation_master.operationId', '=', 'operation_details.operationId', 'left outer') 
            ->join('operation_name_master', 'operation_name_master.operationNameId', '=', 'operation_details.operationNameId', 'left outer') 
            ->join('employee_wise_operations', 'employee_wise_operations.operationNameId', '=', 'operation_details.operationNameId', 'left outer') 
            ->where('operation_master.sales_order_no','=', $request->sales_order_no)
            ->where('employee_wise_operations.employeeCode','=', $request->employeeCode)
            ->groupBy('operation_details.operationNameId')
            ->get();   
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($operationList as $row) 
        {
            $html .= '<option value="'.$row->operationNameId.'">'.$row->operation_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
    
    
        public function operation_list(Request $request)
    {
       
        $operationList = DB::table('ob_details')->select('ob_details.operation_id','ob_details.operation_name')
             ->distinct() 
            ->join('assigned_to_orders', 'assigned_to_orders.mainstyle_id_operation', '=', 'ob_details.mainstyle_id') 
            ->where('assigned_to_orders.sales_order_no','=', $request->sales_order_no)
            ->get();   
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($operationList as $row) 
        {
            $html .= '<option value="'.$row->operation_id.'">'.$row->operation_name.'('.$row->operation_id.')</option>';
        } 
        return response()->json(['html' => $html]);
    }
    
    
    
    public function get_styles(Request $request)
    {
        
    
       
        $styleList = DB::table('assigned_to_orders')
       ->select('mainstyle_id','mainstyle_name') 
        ->join('main_style_master_operation','main_style_master_operation.mainstyle_id','=','assigned_to_orders.mainstyle_id_operation')
        ->where('main_style_master_operation.delflag','=', 0)
        ->where('assigned_to_orders.sales_order_no',$request->sales_order_no)
        ->get(); 
  
        
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($styleList as $row) 
        {
            $html .= '<option value="'.$row->mainstyle_id.'">'.$row->mainstyle_name.'</option>';
        } 
        
         return response()->json(['html' => $html]);
    }
    
    
            public function get_rates(Request $request)
    {
       
           $rates = DB::table('ob_details')->select('ob_details.rate')
            ->where('ob_details.operation_id','=', $request->operationNameId)
            ->first();   
            
         return response()->json($rates);
    }
    
                public function get_groups(Request $request)
    {
       
            $counts = DB::table('ob_details')->select('*')
           ->join('assigned_to_orders','assigned_to_orders.mainstyle_id_operation','=','ob_details.mainstyle_id')
           ->where('assigned_to_orders.sales_order_no',$request->sales_order_no)
           ->where('ob_details.operation_id','=', $request->operationNameId)->where('group_id',8)->count();   
            
         return response()->json($counts);
    }
    
            
    public function EmployeeDateWiseSalary(Request $request)
    {   
        
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $vendorId = isset($request->vendorId) ? $request->vendorId: 0;  
        
        
        $employeeList = DB::table('employeemaster')->select('employeemaster.*','ledger_master.ac_name')
                        ->join('daily_production_entry', 'daily_production_entry.employeeCode', '=', 'employeemaster.employeeCode')
                        ->leftJoin('ledger_master','ledger_master.ac_code','=','daily_production_entry.vendorId')    
                        ->where('employeemaster.delflag','=', 0)
                        ->where('daily_production_entry.vendorId', $vendorId) 
                        ->groupBy('daily_production_entry.employeeCode')
                        ->get();   
            
        
        $allDates = $this->getAllDatesBetween($fromDate, $toDate);
        
        
        
                    $data= DB::table('ledger_master')->select('*');
                    
                      if(Session::get('user_type')!=1)
                     {
                      $data->where('ac_code',Session::get('vendorId'));      
                         
                     } else{
                    $data->whereIn('ac_code',[56,113,115,110,628,686]); 
                     }
                     
                    $unitList=$data->get(); 
                    
                    
                    
                    
                    $vendorId=Session::get('vendorId');
        
        
        
        return view('EmployeeDateWiseSalary',compact('fromDate','toDate','allDates','employeeList','unitList','vendorId'));
    }
       
            
    public function EmployeeDetailedSalaryReport(Request $request)
    {   
        
        
        $salesOrderList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code')
                    ->join('daily_production_entry_details', 'daily_production_entry_details.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                    ->where('buyer_purchse_order_master.delflag','=', 0)
                    ->groupBy('daily_production_entry_details.sales_order_no')
                    ->get();  
                    
       $empList = DB::table('employeemaster_operation')->select('employeemaster_operation.*')
            ->join('daily_production_entry', 'daily_production_entry.employeeCode', '=', 'employeemaster_operation.employeeCode')
            ->where('employeemaster_operation.delflag','=', 0)
            ->groupBy('daily_production_entry.employeeCode')
            ->get(); 
            
                   
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $employeeCode = isset($request->employeeCode) ? $request->employeeCode : "";
        $vendorId = isset($request->vendorId) ? $request->vendorId : "";
        
        //DB::enableQueryLog();
            $filter = DB::table('daily_production_entry_details AS dps')
            ->select(
            'em.fullName',
             'em.employeeCode',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate', 
             'dps.sales_order_no', 
            'ob_details.operation_name',
            'ob_details.rate', 
            'ob_details.sam','dps.dailyProductionEntryId',
            'main_style_master_operation.mainstyle_name','ledger_master.ac_name',
            DB::raw('sum(dps.stiching_qty) as stiching_qty,dps.vendorId,ob_details.rate3,ob_details.rate4,ob_details.rate5,ob_details.rate6'))
            ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
            ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('main_style_master_operation', 'main_style_master_operation.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation')  
           ->join('ledger_master', 'ledger_master.ac_code', '=', 'dps.vendorId')     
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            });
         
        
    
        
        if($fromDate != "" && $toDate != "")
        {
            
            $filter->whereBetween('dps.dailyProductionEntryDate', [$fromDate,$toDate]);
        }
        
        if($sales_order_no != "")
        {
             
              $filter->where('dps.sales_order_no', $sales_order_no);
        }
        
          if($vendorId != "")
        {
             
              $filter->where('dps.vendorId', $vendorId);
        }
        
        if($employeeCode != "")
        {
             $filter->where('dps.employeeCode', $employeeCode);
        }
        
        if(Session::get('user_type')!=1)
        {
            
        $filter->where('dps.vendorId',Session::get('vendorId')); 
        
        } 
        
        
         
           
            $filter->groupBy('dps.dailyProductionEntryDate','dps.bundleNo','dps.employeeCode','dps.operationNameId','dps.color_id');
            $data=$filter->paginate(100);
            //dd(DB::getQueryLog());
            
            
                 $dataFetch= DB::table('ledger_master')->select('*');
                
                
                    if(Session::get('user_type')!=1)
                    {
                        
                    $dataFetch->where('ac_code',Session::get('vendorId'));  
                    
                    } else{
                       
                        $dataFetch->whereIn('ac_code',[56,113,115,110,628,686]); 
                    } 
                
                    $unitList=$dataFetch->get();
                    
                    $vendorId=Session::get('vendorId');
                    $vendorIdrequest=$request->vendorId;
      
        
        return view('EmployeeDetailedSalaryReport',compact('fromDate','toDate','data','sales_order_no','employeeCode','salesOrderList','empList','unitList','vendorId','vendorIdrequest'));
    }
    
    
            public function bundle_pending_for_production(Request $request)
    {   
        
        
                           $data= DB::table('ledger_master')->select('*');
                    
                      if(Session::get('user_type')!=1)
                     {
                      $data->where('ac_code',Session::get('vendorId'));      
                         
                     } else{
                    $data->whereIn('ac_code',[56,113,115,110,628,686]); 
                     }
                     
                    $unitList=$data->get(); 
                    
                    
                         
        $color_id = isset($request->color_id) ? $request->color_id : "";
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $mainstyle_id = isset($request->mainstyle_id) ? $request->mainstyle_id : "";
        $vendorId = isset($request->vendorId) ? $request->vendorId : "";
        
        
                            
     $salesOrderList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code')
                    ->join('cutting_entry_master', 'cutting_entry_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                    ->where('buyer_purchse_order_master.delflag','=', 0)
                    ->groupBy('cutting_entry_master.sales_order_no')
                    ->get();  
         
         if($sales_order_no != "")
        {       
                    
//DB::enableQueryLog();
     
            $filter = DB::table('daily_production_entry_details AS dps')
             ->leftJoin('daily_production_entry_masters','daily_production_entry_masters.daily_pr_entry_id','=','dps.dailyProductionEntryId')
             ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            })    
            ->select(
            'dps.bundleNo',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate',
            'dps.sales_order_no','dps.color_id','ob_details.mainstyle_id','dps.vendorId');
       
        
        if($sales_order_no != "")
        {
             $filter->where('dps.sales_order_no', $sales_order_no);
        }
            if($color_id != "")
        {
             $filter->where('dps.color_id', $color_id);
        }
            if($mainstyle_id != "")
        {
             $filter->where('ob_details.mainstyle_id',$mainstyle_id);
        }
         
         if($vendorId != "")
        {
             $filter->where('dps.vendorId',$vendorId);
        }
          
            $filter->orderBy('dps.bundleNo');
             $filter->groupBy('dps.bundleNo');
            $data=$filter->get();
        } else{
            $data=[];
            
        }

          //  dd(DB::getQueryLog());
            
            $operationFilter = DB::table('ob_details')
    ->select('ob_details.operation_name','ob_details.operation_id')        
    ->leftJoin('daily_production_entry_details AS dps', function($join) use ($sales_order_no) {
        $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
             ->where('dps.sales_order_no', '=', $sales_order_no);
    });
      if($mainstyle_id != "")
        {
             $operationFilter->where('ob_details.mainstyle_id',$mainstyle_id);
        }
        
      $operationFilter->groupBy('ob_details.operation_id');
      $operationList=$operationFilter->get();
       
       
       
       
            
      
        
        return view('bundle_pending_for_production',compact('data','sales_order_no','color_id','mainstyle_id','unitList','salesOrderList','operationList','vendorId'));
    }
    
    
        public function bundle_pending_for_production_print(Request $request)
    {    
               
                    
                    
     $salesOrderList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code')
                    ->join('cutting_entry_master', 'cutting_entry_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                    ->where('buyer_purchse_order_master.delflag','=', 0)
                    ->groupBy('cutting_entry_master.sales_order_no')
                    ->get();  
                   
     
        $color_id = isset($request->color_id) ? $request->color_id : "";
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $mainstyle_id = isset($request->mainstyle_id) ? $request->mainstyle_id : "";
        $vendorId = isset($request->vendorId) ? $request->vendorId : ""; 
        
        
          if($sales_order_no != "")
        {  
         
     
            $filter = DB::table('daily_production_entry_details AS dps')
             ->leftJoin('daily_production_entry_masters','daily_production_entry_masters.daily_pr_entry_id','=','dps.dailyProductionEntryId')
                ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->leftJoin('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            })    
            ->select(
            'dps.bundleNo',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate',
            'dps.sales_order_no','dps.color_id','ob_details.mainstyle_id','dps.vendorId');
            
            
               
        
      
        if($sales_order_no != "")
        {
             $filter->where('dps.sales_order_no', $sales_order_no);
        }
            if($color_id != "")
        {
             $filter->where('dps.color_id', $color_id);
        }
            if($mainstyle_id != "")
        {
             $filter->where('ob_details.mainstyle_id',$mainstyle_id);
        }
         
         if($vendorId != "")
        {
             $filter->where('dps.vendorId',$vendorId);
        }
          
             $filter->groupBy('dps.bundleNo');
             $data=$filter->get();
        } else{
            $data=[];
            
        }
          
 
            

            
            
            $operationFilter = DB::table('ob_details')
    ->select('ob_details.operation_name','ob_details.operation_id')        
    ->leftJoin('daily_production_entry_details AS dps', function($join) use ($sales_order_no) {
        $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
             ->where('dps.sales_order_no', '=', $sales_order_no);
    });
      if($mainstyle_id != "")
        {
             $operationFilter->where('ob_details.mainstyle_id',$mainstyle_id);
        }
        
      $operationFilter->groupBy('ob_details.operation_id');
      $operationList=$operationFilter->get();
      
      
              $fetchStyle=DB::table('main_style_master_operation')->where('mainstyle_id',$mainstyle_id)->first();
             
              $mainstyle_name=$fetchStyle->mainstyle_name;
              
              
                    $fetchColor=DB::table('color_master')->where('color_id',$color_id)->first();
             
                   $color_name=$fetchColor->color_name;
      
      
       
             $FirmDetail =  DB::table('firm_master')->first();
      
        
        return view('bundle_pending_for_production_print',compact('data','sales_order_no','color_id','mainstyle_id','salesOrderList','operationList','FirmDetail','mainstyle_name','color_name','vendorId'));
        
    }  
    
    
    
        public function pcs_rate_salary_report(Request $request)
    {   
        
        
                           $data= DB::table('ledger_master')->select('*');
                    
                      if(Session::get('user_type')!=1)
                     {
                      $data->where('ac_code',Session::get('vendorId'));      
                         
                     } else{
                    $data->whereIn('ac_code',[56,113,115,110,628,686]); 
                     }
                     
                    $unitList=$data->get(); 
         
                    
                    
                    
       $empList = DB::table('employeemaster_operation')->select('employeemaster_operation.*')
            ->join('daily_production_entry', 'daily_production_entry.employeeCode', '=', 'employeemaster_operation.employeeCode')
            ->where('employeemaster_operation.delflag','=', 0)
            ->groupBy('daily_production_entry.employeeCode')
            ->get(); 
            
                   
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $vendorId = isset($request->vendorId) ? $request->vendorId : "";
        $employeeCode = isset($request->employeeCode) ? $request->employeeCode : "";
        
        
         
            $filter = DB::table('daily_production_entry_details AS dps')
            ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
            ->join(
            DB::raw('(SELECT DISTINCT sales_order_no, mainstyle_id_operation FROM assigned_to_orders) AS assigned_to_orders'),
            'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no'
            )
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->on('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            })
            ->select(
            'em.fullName',
            'em.employeeCode',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate',
            'dps.sales_order_no',
            'ob_details.operation_name',
            'ob_details.sam',
            DB::raw('SUM(dps.stiching_qty) as stiching_qty'),
            DB::raw('COUNT(DISTINCT dps.dailyProductionEntryDate) AS present_days,dps.vendorId,ob_details.rate,ob_details.rate3,ob_details.rate3,ob_details.rate4,ob_details.rate5,ob_details.rate6')
            );
   
        
    
        
        if($fromDate != "" && $toDate != "")
        {
            
            $filter->whereBetween('dps.dailyProductionEntryDate', [$fromDate,$toDate]);
        }
        
        if($vendorId != "")
        {
             
              $filter->where('dps.vendorId', $vendorId);
        }
        
        if($employeeCode != "")
        {
             $filter->where('dps.employeeCode', $employeeCode);
        }
        
            $filter->groupBy('dps.employeeCode');
            $data=$filter->get();
            
            
            
      
        
        return view('PCSRateSalaryReport',compact('fromDate','toDate','data','vendorId','employeeCode','unitList','empList'));
    }
                
                
        public function pcs_rate_salary_report_print(Request $request)
    {    

        
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $vendorId = isset($request->vendorId) ? $request->vendorId : "";
        $employeeCode = isset($request->employeeCode) ? $request->employeeCode : "";
         
                      $filter = DB::table('daily_production_entry_details AS dps')
            ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
            ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->on('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            })
            ->select(
            'em.fullName',
            'em.employeeCode',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate',
            'dps.sales_order_no',
            'ob_details.operation_name',
            'ob_details.sam',
            DB::raw('SUM(dps.stiching_qty) as stiching_qty,SUM(dps.stiching_qty * ob_details.rate) AS stiching_amount'),
            DB::raw('COUNT(DISTINCT dps.dailyProductionEntryDate) AS present_days')
            );
   
        
    
        
        if($fromDate != "" && $toDate != "")
        {
            
            $filter->whereBetween('dps.dailyProductionEntryDate', [$fromDate,$toDate]);
        }
        
        if($vendorId != "")
        {
             
              $filter->where('dps.vendorId', $vendorId);
        }
        
        if($employeeCode != "")
        {
             $filter->where('dps.employeeCode', $employeeCode);
        }
        
            $filter->groupBy('em.fullName','em.employeeCode');
            $data=$filter->get();
     
     
        $FirmDetail =  DB::table('firm_master')->first();
         
        
        return view('PCSRateSalaryReportPrint',compact('fromDate','toDate','data','vendorId','employeeCode','FirmDetail'));
        
        
    }              
           
                
                
                
    public function EmployeeDetailedSalaryReportPrint(Request $request)
    {    
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $employeeCode = isset($request->employeeCode) ? $request->employeeCode : "";
        $vendorId = isset($request->vendorId) ? $request->vendorId : ""; 
        
        
        
        
        
            $filter = DB::table('daily_production_entry_details AS dps')
            ->select(
            'em.fullName',
             'em.employeeCode',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate', 
             'dps.sales_order_no', 
            'ob_details.operation_name',
            'ob_details.rate', 
            'ob_details.sam','dps.dailyProductionEntryId',
            'main_style_master_operation.mainstyle_name','ledger_master.ac_name',
            DB::raw('sum(dps.stiching_qty) as stiching_qty,dps.vendorId,ob_details.rate3,ob_details.rate4,ob_details.rate5,ob_details.rate6,
            COUNT(DISTINCT dps.dailyProductionEntryDate) AS present_days'))
            ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
            ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('main_style_master_operation', 'main_style_master_operation.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation')  
           ->join('ledger_master', 'ledger_master.ac_code', '=', 'dps.vendorId')     
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            });
         
        
    
        
        if($fromDate != "" && $toDate != "")
        {
            
            $filter->whereBetween('dps.dailyProductionEntryDate', [$fromDate,$toDate]);
        }
        
        if($sales_order_no != "")
        {
             
              $filter->where('dps.sales_order_no', $sales_order_no);
        }
        
          if($vendorId != "")
        {
             
              $filter->where('dps.vendorId', $vendorId);
        }
        
        if($employeeCode != "")
        {
             $filter->where('dps.employeeCode', $employeeCode);
        }
        
        if(Session::get('user_type')!=1)
        {
            
        $filter->where('dps.vendorId',Session::get('vendorId')); 
        
        } 
        
        
         
            $filter->orderBy('dps.dailyProductionEntryDate');
            $filter->groupBy('dps.dailyProductionEntryDate','dps.employeeCode','dps.operationNameId','dps.sales_order_no');
            $data=$filter->get();
                     
            
        
            
                 $detailArray = $data instanceof \Illuminate\Support\Collection ? $data->toArray() : $data;
                
                 $PresentCount = array_column($detailArray, 'dailyProductionEntryDate');      
            
     
     
        $FirmDetail =  DB::table('firm_master')->first();
         
        
        return view('EmployeeDetailedSalaryReportPrint',compact('fromDate','toDate','data','sales_order_no','employeeCode','FirmDetail','PresentCount'));
    }  
            
    public function EmployeeDetailedProductionReport(Request $request)
    {   
        
        $salesOrderList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code')
                    ->join('daily_production_entry_details', 'daily_production_entry_details.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                    ->where('buyer_purchse_order_master.delflag','=', 0)
                    ->groupBy('daily_production_entry_details.sales_order_no')
                    ->get();  
                    
       $empList = DB::table('employeemaster_operation')->select('employeemaster_operation.*')
            ->join('daily_production_entry', 'daily_production_entry.employeeCode', '=', 'employeemaster_operation.employeeCode')
            ->where('employeemaster_operation.delflag','=', 0)
            ->groupBy('daily_production_entry.employeeCode')
            ->get(); 
            
       	  
	      $employeeMap=[];
          
      foreach ($empList as $rowEmp) {
         $employeeMap[] = [
        'employeeCode' => $rowEmp->employeeCode,
         'employeeName' => $rowEmp->fullName
        ];
        
        }     
            
            
            
            
            
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $employeeCode = isset($request->employeeCode) ? $request->employeeCode : "";
        $operationNameId = isset($request->operationNameId) ? $request->operationNameId : "";
        
        $vendorId=isset($request->vendorId) ? $request->vendorId : "";
        $bundleNo=isset($request->bundleNo) ? $request->bundleNo : "";
        
        $filter="";
         
            $filter = DB::table('daily_production_entry_details AS dps')
            ->select(
            'em.fullName',
             'em.employeeCode',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate', 
             'dps.sales_order_no', 
            'ob_details.operation_name',
            'ob_details.rate', 
            'ob_details.rate3',
            'ob_details.rate4',
            'ob_details.rate5',
            'ob_details.rate6',
            'dps.vendorId',
            'ob_details.sam','dps.dailyProductionEntryId',
            'main_style_master_operation.mainstyle_name','color_master.color_name','dps.lotNo','dps.bundleNo','size_detail.size_name','ledger_master.ac_name',
            DB::raw('sum(dps.stiching_qty) as stiching_qty,SUM(dps.amount) as total_amount,dps.bundle_track_code,usermaster.username')
            )
            ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
            ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('color_master', 'color_master.color_id', '=', 'dps.color_id')   
            ->join('daily_production_entry','daily_production_entry.dailyProductionEntryId','=','dps.dailyProductionEntryId')
            ->join('usermaster','usermaster.userId','=','daily_production_entry.userId')
            ->leftJoin('size_detail', 'size_detail.size_id', '=', 'dps.size_id')     
            ->join('main_style_master_operation', 'main_style_master_operation.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation')  
            ->join('ledger_master', 'ledger_master.ac_code', '=', 'dps.vendorId')      
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            });
         

        if($fromDate != "" && $toDate != "")
        {
            
            $filter->whereBetween('dps.dailyProductionEntryDate', [$fromDate,$toDate]);
        }
        
        if($sales_order_no != "")
        {
             
              $filter->where('dps.sales_order_no', $sales_order_no);
        }
        
        if($bundleNo != "")
        {
             
              $filter->where('dps.bundleNo', $bundleNo);
        } 
        
        
        if($vendorId != "")
        {
             
              $filter->where('dps.vendorId', $vendorId);
        }
        
        
        if($employeeCode != "")
        {
             $filter->where('dps.employeeCode', $employeeCode);
        }
        
        if(Session::get('user_type')!=1)
        {
            
        $filter->where('dps.vendorId',Session::get('vendorId')); 
        
        } 
        
            $filter->groupBy('dps.dailyProductionEntryDate','dps.bundleNo','dps.employeeCode','dps.operationNameId','dps.color_id');
            $data=$filter->paginate(100);
            
            
            
            
    //           $dataMap=[];
          
    //   foreach ($data as $rows) {
    //      $dataMap[] = $rows;
    //     }     
            
            
            
            
            
            
                  $unitfetch= DB::table('ledger_master')->select('*');
                 
                 
                    if(Session::get('user_type')!=1)
                    {
                    
                    $unitfetch->where('ac_code',Session::get('vendorId')); 
                    
                    } else{
                 
                    $unitfetch->whereIn('ac_code',[56,113,115,110,628,686]); 
                    }
                    
                    $unitList=$unitfetch->get();  
                    
                    
                    $vendorId=Session::get('vendorId');
                    
                    $vendorIdrequest=isset($request->vendorId) ? $request->vendorId : "";
            
         
        
        return view('EmployeeDetailedProductionReport',compact('fromDate','toDate','data','employeeMap','salesOrderList','sales_order_no','employeeCode','vendorId','unitList','vendorIdrequest'));
    }
    
    
        
    public function EmployeeDetailedProductionExport($fromDate, $toDate, $sales_order_no = "", $bundleNo = "", $vendorId = "", $employeeCode = "")
    {
        return Excel::download(new EmployeeDetailedProductionExport($fromDate, $toDate, $sales_order_no, $bundleNo, $vendorId, $employeeCode), 'Employee_Detailed_Production_Report.xlsx');
    }

    public function EmployeeDetailedSalaryReportExport($fromDate, $toDate, $sales_order_no = "", $vendorId = "", $employeeCode = "")
    { 
        return Excel::download(new EmployeeDetailedSalaryReportExport($fromDate, $toDate, $sales_order_no, $vendorId, $employeeCode), 'Employee_Detailed_Salary_Report.xlsx');
    }
    
    
    public function employee_detailed_production_export($fromDate, $toDate,$employeeCode,$dailyProductionEntryId)
    { 
        return Excel::download(new EmployeeProductionEntryExport($fromDate, $toDate,$employeeCode,$dailyProductionEntryId), 'employee_detailed_production_export.xlsx');
    }
        
        
    public function SetStitichingQtyForDailyProduction(Request $request)
    {
        
        $vendorData = DB::SELECT('SELECT vendorId  FROM usermaster WHERE userId ='.$request->userId); 
        $vendorId = isset($vendorData[0]->vendorId) ? $vendorData[0]->vendorId : 0;
                     
        $Stitiching = DB::SELECT("SELECT (ifnull(sum(size_qty_total),0)) as stitch_max_qty FROM stitching_inhouse_size_detail 
                        WHERE vendorId='".$vendorId."' AND sales_order_no='".$request->sales_order_no."' AND color_id=".$request->color_id); 
        
        $DailyProduction = DB::SELECT("SELECT (ifnull(sum(stiching_qty),0)) as prod_max_qty FROM daily_production_entry_details 
                        WHERE vendorId='".$vendorId."' AND sales_order_no='".$request->sales_order_no."' AND color_id=".$request->color_id." AND operationNameId='".$request->operationNameId."'");
                        
        
        $obData = DB::SELECT("SELECT ob_details.group_id as group_id FROM ob_details INNER JOIN assigned_to_orders ON assigned_to_orders.mainstyle_id_operation = ob_details.mainstyle_id WHERE sales_order_no = '".$request->sales_order_no."' AND ob_details.operation_name='".$request->operationNameId."'");
        $group_id = isset($obData[0]->group_id) ? $obData[0]->group_id : 0;
        
        $stitch_max_qty = isset($Stitiching[0]->stitch_max_qty) ? $Stitiching[0]->stitch_max_qty: '';
        $prod_max_qty = isset($DailyProduction[0]->prod_max_qty) ? $DailyProduction[0]->prod_max_qty: '';        
        $max_qty = $stitch_max_qty - $prod_max_qty;
        
        return response()->json(['max' => $max_qty,'group_id'=> $group_id]);
    }
    
}

