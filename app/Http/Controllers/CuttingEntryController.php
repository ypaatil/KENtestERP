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
use Session;
use DataTables;
use DB;


class CuttingEntryController extends Controller
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
        ->where('form_id', '245')
        ->first();
        
        $data = CuttingEntryModel::select('cutting_entry_master.*','main_style_master.mainstyle_name','usermaster.username')
                        ->join('usermaster', 'usermaster.userId', '=', 'cutting_entry_master.userId', 'left outer')
                        ->join('main_style_master','main_style_master.mainstyle_id','cutting_entry_master.main_style_id', 'left outer');
                        
                        
             if(Session::get('user_type')==1)
            {
         
            } else{
                
               $data->where('cutting_entry_master.vendorId',Session::get('vendorId'));
            }
                        
                        $data->where('cutting_entry_master.delflag','=', '0');
                        $data->orderBy('cutting_entry_master.cuttingEntryId','DESC');
                        $CuttingEntryList=$data->get(); 
                        
                        
        
        return view('cuttingEntryMasterList', compact('CuttingEntryList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $MainStyleList = DB::table('main_style_master')->where('main_style_master.delflag','=', '0')->get();  
      
       // $SalesOrderList = DB::table('buyer_purchse_order_master')->join('operation_master', 'operation_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')->where('buyer_purchse_order_master.delflag','=', '0')->get(); 
        
         $SalesOrderList = DB::table('assigned_to_orders')->get(); 
        
        
        $ColorList = DB::table('color_master')->select('color_master.color_id', 'color_master.color_name')  
                    ->join('buyer_purchase_order_detail','buyer_purchase_order_detail.color_id','=','color_master.color_id')  
                    ->groupBy('buyer_purchase_order_detail.color_id')
                    ->get();
                    
                     $JobPartList= DB::table('job_part_master')->where('delflag','=', '0')->get();
     
        return view('CuttingEntryMaster',compact('MainStyleList','SalesOrderList','ColorList','JobPartList'));
    }
    
      public function cutting_slip($id)
    {
        
        
           $CuttingEntryDetailList = CuttingEntryDetailModel::join('color_master','color_master.color_id','=','cutting_entry_details.color_id')
           ->join('size_detail','size_detail.size_id','=','cutting_entry_details.size')
               ->where('cuttingEntryId','=', $id)
               
                  ->get();  

           
           
           
        
        return view('CuttingSlip',compact('CuttingEntryDetailList'));
        
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          // echo '<pre>';print_R($_POST);exit;
          
          
             $jpart_ids = implode(',', $request->jpart_id);
          
            $data1=array(
                'cuttingEntryDate'=>$request->cuttingEntryDate, 
                'sales_order_no'=>$request->sales_order_no, 
                'main_style_id'=>$request->main_style_id,  
                'fg_id'=>$request->fg_id,   
                'total_cut_qty'=>$request->total_cut_qty,
                'jpart_id'=>$jpart_ids, 
                'userId'=>$request->userId,
                'vendorId'=>Session::get('vendorId'),
                'delflag'=>'0'
            );
         
            CuttingEntryModel::insert($data1);
            
            $cuttingEntryId = DB::table('cutting_entry_master')->max('cuttingEntryId');
            if($request->bundleNo != "")
            {
                for($i=0;$i<count($request->bundleNo);$i++)
                { 
                     DB::select("update counter_number set tr_no = tr_no+1 where code ='B' AND type='BundleTrackCode'");
                    $data2=array(
                    'cuttingEntryId'=>$cuttingEntryId, 
                    'bundleNo'=>$request->bundleNo[$i], 
                    'bundle_track_code'=>$request->bundle_track_code[$i],   
                    'slipNo'=>$request->slipNo[$i],   
                    'lotNo'=>$request->lotNo[$i],     
                    'size'=>$request->size[$i],   
                    'cut_panel_issue_qty'=>$request->cut_panel_issue_qty[$i], 
                    'color_id'=>$request->color_id[$i], 
                    'vendorId'=>Session::get('vendorId'),  
                    );
                    
                   
                    CuttingEntryDetailModel::insert($data2);   
                }
            }
            return redirect()->route('CuttingEntry.index')->with('message', 'Data Saved Succesfully');  
      
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
        $CuttingEntryList = CuttingEntryModel::find($id);
        $fg_id = isset($CuttingEntryList->fg_id) ? $CuttingEntryList->fg_id : 0;
        $main_style_id = isset($CuttingEntryList->main_style_id) ? $CuttingEntryList->main_style_id : 0;
        $sales_order_no = isset($CuttingEntryList->sales_order_no) ? $CuttingEntryList->sales_order_no : 0;
            
        //DB::enableQueryLog();

        $CuttingEntryDetailList = CuttingEntryDetailModel::where('cuttingEntryId','=', $id)->get();  
       // dd(DB::getQueryLog());
        $MainStyleList = DB::table('main_style_master')->where('main_style_master.delflag','=', '0')->get();  
        //$SalesOrderList = DB::table('buyer_purchse_order_master')->join('operation_master', 'operation_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')->where('buyer_purchse_order_master.delflag','=', '0')->get();  
       
         $SalesOrderList = DB::table('assigned_to_orders')->get(); 
        $fgData = DB::table('fg_master')->select('fg_name')->where('fg_id','=', $fg_id)->first();  
        $fg_name = isset($fgData->fg_name) ? $fgData->fg_name : "";
        
        $jobPartList = DB::table('job_part_master') 
            ->where('job_part_master.delflag','=', 0)
            ->get();   
            
       // $OperationNameList = DB::table('operation_name_master')->select('operation_name_master.operationNameId', 'operation_name_master.operation_name') 
        //    ->where('main_style_id','=',$CuttingEntryList->main_style_id) 
         //   ->get();
        
        $OperationNameList = DB::table('operation_name_master')->select('operation_name_master.operationNameId', 'operation_name_master.operation_name')  
            ->join('operation_details','operation_details.operationNameId','=','operation_name_master.operationNameId')
            ->join('operation_master','operation_master.operationId','=','operation_details.operationId')
            ->where('operation_name_master.main_style_id','=',$main_style_id) 
            ->where('sales_order_no','=',$sales_order_no) 
            ->get();
                    
        $sizeList = DB::table('size_detail')
                            ->join('buyer_purchse_order_master','buyer_purchse_order_master.sz_code','=','size_detail.sz_code')
                            ->where('buyer_purchse_order_master.delflag','=', '0')
                            ->where('tr_code','=', $sales_order_no)
                            ->get();   
        
        $ColorList = DB::table('color_master')->select('color_master.color_id', 'color_master.color_name')  
                    ->leftjoin('buyer_purchase_order_detail','buyer_purchase_order_detail.color_id','=','color_master.color_id') 
                     ->where('tr_code','=',$sales_order_no)  
                    ->groupBy('buyer_purchase_order_detail.color_id')
                    ->get();
                    
                    
                  $JobPartList= DB::table('job_part_master')->where('delflag','=', '0')->get();
                    
        return view('CuttingEntryMasterEdit',compact('CuttingEntryList','CuttingEntryDetailList','MainStyleList','SalesOrderList','fg_name','jobPartList','OperationNameList','sizeList','ColorList','JobPartList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cuttingEntryId)
    {  
        
        
          $jpart_ids = implode(',', $request->jpart_id);
          
          
             $data1=array(
                'cuttingEntryDate'=>$request->cuttingEntryDate, 
                'sales_order_no'=>$request->sales_order_no, 
                'main_style_id'=>$request->main_style_id,  
                'fg_id'=>$request->fg_id,  
                'userId'=>$request->userId,
                'total_cut_qty'=>$request->total_cut_qty,
                'jpart_id'=>$jpart_ids,  
                'delflag'=>'0',
                'vendorId'=>Session::get('vendorId')
            );
 
            $cuttingEntryList = CuttingEntryModel::findOrFail($cuttingEntryId); 
 
            $cuttingEntryList->fill($data1)->save();
            DB::table('cutting_entry_details')->where('cuttingEntryId', $cuttingEntryId)->delete();  
            
            for($i=0;$i<count($request->bundleNo);$i++)
            {
                DB::select("update counter_number set tr_no = ".$request->tr_no[$i]."  where code ='B' AND type='BundleTrackCode'");
                $data2=array(
                'cuttingEntryId'=>$cuttingEntryId,  
                'bundleNo'=>$request->bundleNo[$i],  
                'bundle_track_code'=>$request->bundle_track_code[$i],   
                'slipNo'=>$request->slipNo[$i],   
                'lotNo'=>$request->lotNo[$i],   
                'size'=>$request->size[$i],   
                'cut_panel_issue_qty'=>$request->cut_panel_issue_qty[$i], 
                'color_id'=>$request->color_id[$i], 
                'vendorId'=>Session::get('vendorId'),      
                );
                 
                CuttingEntryDetailModel::insert($data2);   
            }
            return redirect()->route('CuttingEntry.index')->with('message', 'Data Updated Succesfully');  
              
    }
     
     
         public function cutting_bundle_report(Request $request)
    {   
        
        $salesOrderList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code')
                    ->join('cutting_entry_master', 'cutting_entry_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                    ->where('buyer_purchse_order_master.delflag','=', 0)
                    ->groupBy('cutting_entry_master.sales_order_no')
                    ->get();  

            
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $vendorId=isset($request->vendorId) ? $request->vendorId : "";
        $bundleNo=isset($request->bundleNo) ? $request->bundleNo : "";
        $color_id=isset($request->color_id) ? $request->color_id : "";
        
        
        $filter="";
         
            $filter = DB::table('cutting_entry_details AS dps')
            ->select(
             'cutting_entry_master.cuttingEntryId',        
             'cutting_entry_master.cuttingEntryDate', 
             'cutting_entry_master.sales_order_no', 
            'main_style_master.mainstyle_name','color_master.color_name','dps.lotNo','dps.bundleNo','size_detail.size_name','ledger_master.ac_short_name',
            DB::raw('SUM(dps.cut_panel_issue_qty) as stiching_qty,dps.bundle_track_code')
            )
            ->join('cutting_entry_master', 'cutting_entry_master.cuttingEntryId', '=', 'dps.cuttingEntryId')   
            ->join('color_master', 'color_master.color_id', '=', 'dps.color_id')   
            ->join('size_detail', 'size_detail.size_id', '=', 'dps.size')     
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'cutting_entry_master.main_style_id')  
            ->join('ledger_master', 'ledger_master.ac_code', '=', 'dps.vendorId');      
    
    
        
        if($fromDate != "" && $toDate != "")
        {
            
            $filter->whereBetween('cutting_entry_master.cuttingEntryDate', [$fromDate,$toDate]);
        }
        
        if($sales_order_no != "")
        {
             
              $filter->where('cutting_entry_master.sales_order_no', $sales_order_no);
        }
             if($vendorId != "")
        {
             
              $filter->where('dps.vendorId', $vendorId);
        }
        
         if($color_id!= "")
        {
             
              $filter->where('dps.color_id', $color_id);
        }
              if($bundleNo!= "")
        {
             
              $filter->where('dps.bundleNo', $bundleNo);
        }
        
        if(Session::get('user_type')!=1)
        {
            
        $filter->where('dps.vendorId',Session::get('vendorId')); 
        
        } 
        
    
            $filter->groupBy(
            'cutting_entry_master.cuttingEntryDate',
            'cutting_entry_master.sales_order_no',
            'main_style_master.mainstyle_name',
            'color_master.color_name',
            'dps.lotNo',
            'dps.bundleNo',
            'size_detail.size_name',
            'ledger_master.ac_short_name',
            'dps.bundle_track_code'
            );
            $data=$filter->get();
            
            
            
            
                  $vendorId=Session::get('vendorId');
            
                  $dataUnit= DB::table('ledger_master')->select('*');
                 
                 
                 if(Session::get('user_type')!=1)
                 {
                    $dataUnit->where('ac_code',Session::get('vendorId')); 
                    
                 } else{
                     
                   $dataUnit->whereIn('ac_code',[56,113,115,110,628,686]); 
                     
                  }
                  
                  
                    $unitList=$dataUnit->get();  
                    
         
        
        return view('cutting_bundle_detail_report',compact('fromDate','toDate','data','salesOrderList','sales_order_no','vendorId','unitList'));
    }
     
     
     
      
    public function destroy($id)
    { 
        DB::table('cutting_entry_master')->where('cuttingEntryId', $id)->delete(); 
        DB::table('cutting_entry_details')->where('cuttingEntryId', $id)->delete();  
        Session::flash('messagedelete', 'Deleted record successfully');  
    }
     
    public function GetOperationList(Request $request)
    {
        $OperationList = DB::table('operation_name_master')->select('operation_name_master.operationNameId', 'operation_name_master.operation_name') 
                    ->where('main_style_id','=',$request->main_style_id) 
                    ->get();
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($OperationList as $row) 
        {
            $html .= '<option value="'.$row->operationNameId.'">'.$row->operation_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
    
    public function GetEmpList()
    {
        $emplList = DB::table('employeemaster')
            ->select('employeemaster.employeeCode','employeemaster.employeeName') 
            ->get();
        
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($emplList as $row) 
        {
            $html .= '<option value="'.$row->employeeCode.'">('.$row->employeeCode.') '.$row->employeeName.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 
 
    public function GetBuyerPurchaseData(Request $request)
    {
        $BuyerList = DB::table('buyer_purchse_order_master')->select('ledger_master.Ac_name','buyer_purchse_order_master.style_no','buyer_purchse_order_master.sam','brand_master.brand_name','buyer_purchse_order_master.sz_code','main_style_master.mainstyle_name','main_style_master.mainstyle_id','fg_master.fg_id','fg_master.fg_name') 
                        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')  
                        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')  
                        ->join('ledger_master', 'ledger_master.ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer') 
                        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer') 
                        ->where('tr_code','=',$request->sales_order_no) 
                        ->first();
         
        $main_style_id = isset($BuyerList->mainstyle_id) ? $BuyerList->mainstyle_id : "";
        $mainstyle_name = isset($BuyerList->mainstyle_name) ? $BuyerList->mainstyle_name : "";
        $fg_id = isset($BuyerList->fg_id) ? $BuyerList->fg_id : "";
        $fg_name = isset($BuyerList->fg_name) ? $BuyerList->fg_name : "";
        $Ac_name = isset($BuyerList->Ac_name) ? $BuyerList->Ac_name : "";
        $style_no = isset($BuyerList->style_no) ? $BuyerList->style_no : "";
        $sam = isset($BuyerList->sam) ? $BuyerList->sam : "";
        $brand_name = isset($BuyerList->brand_name) ? $BuyerList->brand_name : "";
        $sizeList = DB::table('size_detail')->where('sz_code','=', $BuyerList->sz_code)->get(); 
        
        $sizehtml = '';
        $sizehtml = '<option value="">--Select--</option>';
        
        foreach ($sizeList as $sizes) 
        {
            $sizehtml .= '<option value="'.$sizes->size_id.'">'.$sizes->size_name.'</option>';
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
        
        
        return response()->json(['main_style_id' => $main_style_id,'mainstyle_name'=>$mainstyle_name, 'fg_id'=>$fg_id, 'fg_name' => $fg_name, 'sizehtml'=>$sizehtml,'Ac_name'=>$Ac_name,'style_no'=>$style_no,'sam'=>$sam,'brand_name'=>$brand_name,'colorHtml' => $colorHtml]);
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
    
        
    public function GetCuttingOperationList(Request $request)
    {
        
        $OperationList = DB::table('operation_details') 
                        ->select('operation_name_master.operationNameId', 'operation_name_master.operation_name') 
                        ->join('operation_name_master', 'operation_name_master.operationId', '=', 'operation_details.operationId') 
                        ->where('operation_name_master.main_style_id','=',$request->main_style_id) 
                        ->where('operation_name_master.sales_order','=',$request->sales_order) 
                        ->get();
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($OperationList as $row) 
        {
            $html .= '<option value="'.$row->operationNameId.'">'.$row->operation_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
     
             
    public function checkDuplicateBundleNo(Request $request)
    {
        
        $cuttingData = DB::SELECT("SELECT count(*) as total_count FROM cutting_entry_master 
                LEFT JOIN cutting_entry_details ON cutting_entry_details.cuttingEntryId = cutting_entry_master.cuttingEntryId 
                WHERE 
    cutting_entry_master.sales_order_no='".$request->sales_order_no."' AND cutting_entry_details.color_id=".$request->color_id." AND  cutting_entry_details.bundleNo='".$request->bundleNo."' AND cutting_entry_details.vendorId='".Session::get('vendorId')."'");
                        
        $total_count =  isset($cuttingData[0]->total_count) ? $cuttingData[0]->total_count : 0;
        
        return response()->json(['total_count' => $total_count]);
    }
    
    
    public function get_cutting_detail(Request $request){
        
        
        
    $salesOrderList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code')
                    ->join('daily_production_entry_details', 'daily_production_entry_details.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                    ->where('buyer_purchse_order_master.delflag','=', 0)
                    ->groupBy('daily_production_entry_details.sales_order_no')
                    ->get();  
                    
                    
                       $BrandList = DB::table('buyer_purchse_order_master')->select('brand_master.brand_name','brand_master.brand_id')
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')    
                    ->where('buyer_purchse_order_master.delflag','=', 0)
                    ->groupBy('brand_master.brand_id')
                    ->get(); 
                    
                    
                    $styleList = DB::table('main_style_master')->select('mainstyle_id','mainstyle_name')
                    ->where('main_style_master.delflag','=', 0)
                    ->get(); 
                    
                    

            
        $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $employeeCode = isset($request->employeeCode) ? $request->employeeCode : "";
        $operationNameId = isset($request->operationNameId) ? $request->operationNameId : "";
         
        // $filter = "";
        
        // if($fromDate != "" && $toDate != "")
        // {
        //     $filter .= " AND daily_production_entry_details.dailyProductionEntryDate BETWEEN '".$fromDate."' AND '".$toDate."'";
        // }
        
        // if($sales_order_no != "")
        // {
        //      $filter .= " AND daily_production_entry_details.sales_order_no = '".$sales_order_no."'";
        // }
        
        // if($employeeCode != "")
        // {
        //      $filter .= " AND daily_production_entry.employeeCode = '".$employeeCode."'";
        // }
        
        // if($operationNameId != "")
        // {
        //      $filter .= " AND daily_production_entry_details.operationNameId = '".$operationNameId."'";
        // }

         
        
        return view('get_cutting_detail',compact('fromDate','toDate','salesOrderList','sales_order_no','BrandList','styleList'));   
        
       
    }
    
    
    
    public function show_cutting_detail(){
        
                $fromDate = isset($request->fromDate) ? $request->fromDate: date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate: date('Y-m-d');
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $brand_id = isset($request->brand_id) ? $request->brand_id : "";
        $mainstyle_id = isset($request->mainstyle_id) ? $request->mainstyle_id : ""; 
        
        
        
        
         
         $filter=DB::table('cutting_entry_details')
         ->join('cutting_entry_master','cutting_entry_master.cuttingEntryId','=','cutting_entry_details.cuttingEntryId')
         ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','cutting_entry_master.sales_order_no')
         ->join('size_detail','size_detail.size_id','=','cutting_entry_details.size')
         ->join('color_master','color_master.color_id','=','cutting_entry_details.color_id');  
         
    
        
        if($fromDate != "" && $toDate != "")
        {
            
            $filter->whereBetween('cutting_entry_master.cuttingEntryDate', [$fromDate,$toDate]);
        }
        
        if($sales_order_no != "")
        {
             
              $filter->where('cutting_entry_master.sales_order_no', $sales_order_no);
        }
        
        if($brand_id != "")
        {
             $filter->where('buyer_purchse_order_master.brand_id', $brand_id);
        }
        
           
            $data=$filter->get();
     
     
        $FirmDetail =  DB::table('firm_master')->first();
        
         return view('show_cutting_detail',compact('fromDate','toDate','sales_order_no','data','FirmDetail'));   
         
         
    }
    
    
    
    
    
    
}