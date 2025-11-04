<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DHUStichingOperationModel;
use App\Models\DHUStichingDefectTypeModel;
use App\Models\DHUModel;
use App\Models\DHUDetailModel;
use App\Models\MainStyleModel;
use App\Models\VendorWorkOrderModel;
use App\Models\LedgerModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\SourceModel;
use App\Models\DestinationModel;
use Session;
use DataTables;

class DHUController extends Controller
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
            ->where('form_id', '52')
            ->first();
        
        $dhuList = DB::table('dhu_master')
                   ->select('dhu_master.*','line_master.line_name','LM1.ac_name as buyer_name','LM2.ac_name as vendor_name',
                    'job_status_master.job_status_name','main_style_master.mainstyle_name','fg_master.fg_name')
                   ->Join('line_master','line_master.line_id','=','dhu_master.line_no')
                   ->Join('ledger_master as LM1','LM1.ac_code','=','dhu_master.ac_code')
                   ->Join('ledger_master as LM2','LM2.ac_code','=','dhu_master.vendorId')
                   ->Join('vendor_work_order_master','vendor_work_order_master.vw_code','=','dhu_master.vw_code')
                   ->Join('main_style_master','main_style_master.mainstyle_id','=','dhu_master.mainstyle_id')
                   ->Join('fg_master','fg_master.fg_id','=','dhu_master.fg_id')
                   ->join('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_work_order_master.endflag', 'left outer')
                   ->get();
       
        return view('DHUMaster_List',compact('dhuList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='StitchingInhouse'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
      
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $vendorId=Session::get('vendorId');
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
            $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
          $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->where('vendor_work_order_master.vendorId',$vendorId)->get();
          $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        } 
        
        $dhuOperationList = DB::table('dhu_stiching_operation')->select('dhu_stiching_operation.*')->get();
        $DHUStichingOperationList= DHUStichingOperationModel::select('dhu_so_Id','dhu_so_Name')->get();
        $DHUDefectList= DHUStichingDefectTypeModel::select('dhu_sdt_Id','dhu_sdt_Name')->get();
            
        return view('DHUMaster',compact('dhuOperationList','VendorWorkOrderList','Ledger','BuyerList','MainStyleList','FGList','SubStyleList','counter_number','DHUStichingOperationList','DHUDefectList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
        ->where('c_name','=','C1')
        ->where('type','=','DHU')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
        
        $data=array(
                'dhu_code'=>$TrNo, 
                'dhu_date'=>$request->dhu_date, 
                'vendorId'=>$request->vendorId, 
                'sales_order_no'=>$request->sales_order_no, 
                'vw_code'=>$request->vw_code, 
                'line_no'=>$request->line_no, 
                'mainstyle_id'=>$request->mainstyle_id, 
                'Ac_code'=>$request->Ac_code, 
                'substyle_id'=>$request->substyle_id, 
                'fg_id'=>$request->fg_id, 
                'style_no'=>$request->style_no, 
                'style_description'=>$request->style_description, 
                'total_defect_qty'=>$request->total_defect_qty, 
                'userId'=>$request->userId, 
                'c_code'=>$request->c_code,
                'created_at'=>date('Y-m-d'),
                'updated_at'=>""
        );
        
        DHUModel::insert($data);
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='DHU'");
        $dhu_so_Id= $request->input('dhu_so_Id');
        $total_defect_qty = 0;
        if(count($dhu_so_Id)>0)
        {   
            for($x=0; $x<count($dhu_so_Id); $x++) 
            {
                $data1=array(
                    'dhu_code'=>$TrNo, 
                    'dhu_so_Id'=>$request->dhu_so_Id[$x], 
                    'dhu_sdt_Id'=>$request->dhu_sdt_Id[$x], 
                    'defect_qty'=>$request->defect_qty[$x]
                );
                $total_defect_qty += $request->defect_qty[$x];
                DHUDetailModel::insert($data1);
            }
        }
        
        
        DB::connection('hrms_database')->table('production_detail')->where('productionDate','=',$request->dhu_date)->where('vendorId','=',$request->vendorId)->where('deptCostId','=',$request->line_no)->update(['defect' => $total_defect_qty]); 
        SourceModel::on('mysql');
            
        return redirect()->route('DHU.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(DHUModel $DHUModel)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dhuList = DHUModel::find($id);
        
        $dhuDetailList = DHUDetailModel::where('dhu_code','=', $id)->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
      
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $vendorId=Session::get('vendorId');
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
            $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
          $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->where('vendor_work_order_master.vendorId',$vendorId)->get();
          $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        } 
        
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='DHU'");
        $dhuOperationList = DB::table('dhu_stiching_operation')->select('dhu_stiching_operation.*')->get();
        $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->get();
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $DHUStichingOperationList= DHUStichingOperationModel::select('dhu_stiching_operation.dhu_so_Id','dhu_so_Name')
                                    ->Join('dhu_stiching_defect_type','dhu_stiching_defect_type.dhu_so_Id','=','dhu_stiching_operation.dhu_so_Id')
                                    ->where('mainstyle_id','=', $dhuList->mainstyle_id)
                                    ->groupBy('dhu_stiching_defect_type.dhu_so_Id')
                                    ->get();
                                    
        $DHUDefectList= DHUStichingDefectTypeModel::select('dhu_sdt_Id','dhu_sdt_Name')->get();
        
        return view('DHUMaster',compact('dhuList','dhuDetailList','dhuOperationList','VendorWorkOrderList','SubStyleList','FGList','Ledger','BuyerList','MainStyleList','counter_number','DHUStichingOperationList','DHUDefectList'));
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DHUStichingOperationModel  $DHUStichingOperationModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DHUModel $DHUModel)
    {
        //echo '<pre>';print_R($_POST);exit;
            $data=array(
                'dhu_code'=>$request->dhu_code, 
                'dhu_date'=>$request->dhu_date, 
                'vendorId'=>$request->vendorId, 
                'sales_order_no'=>$request->sales_order_no, 
                'vw_code'=>$request->vw_code, 
                'line_no'=>$request->line_no, 
                'mainstyle_id'=>$request->mainstyle_id, 
                'Ac_code'=>$request->Ac_code, 
                'substyle_id'=>$request->substyle_id, 
                'fg_id'=>$request->fg_id, 
                'style_no'=>$request->style_no, 
                'style_description'=>$request->style_description, 
                'total_defect_qty'=>$request->total_defect_qty, 
                'userId'=>$request->userId, 
                'c_code'=>$request->c_code,
                'created_at'=>date('Y-m-d'),
                'updated_at'=>date('Y-m-d'),
            );
            //DB::enableQueryLog();
            $dhuList = DHUModel::findOrFail($request->dhu_code); 
             // dd(DB::getQueryLog());
            $dhuList->fill($data)->save();
          
            DB::table('dhu_details')->where('dhu_code', $request->input('dhu_code'))->delete();
           
            $dhu_so_Id= $request->input('dhu_so_Id');
            $total_defect_qty = 0;
            
            if(count($dhu_so_Id)>0)
            {   
                for($x=0; $x<count($dhu_so_Id); $x++) 
                {
                    $data1=array(
                        'dhu_code'=>$request->dhu_code, 
                        'dhu_so_Id'=>$request->dhu_so_Id[$x], 
                        'dhu_sdt_Id'=>$request->dhu_sdt_Id[$x], 
                        'defect_qty'=>$request->defect_qty[$x]
                    );
                    
                    $total_defect_qty += $request->defect_qty[$x];
                    DHUDetailModel::insert($data1);
                }
            }
            
            
            DB::connection('hrms_database')->table('production_detail')->where('productionDate','=',$request->dhu_date)->where('vendorId','=',$request->vendorId)->where('deptCostId','=',$request->line_no)->update(['defect' => $total_defect_qty]); 
            SourceModel::on('mysql');
        
            return redirect()->route('DHU.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DHUStichingOperationModel  $DHUStichingOperationModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('dhu_master')->where('dhu_code', $id)->delete();
        DB::table('dhu_details')->where('dhu_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
    public function GetDHUReport()
    {   
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();
        return view('GetDHUReport', compact('Ledger'));
    }
    public function rptDHU(Request $request)
    {
        //echo $request->month;;exit;
        $vendorId = $request->vendorId;
        $line_no = $request->line_id;
        $monthDate = $request->month;
        $month = explode("-",$request->month);
    
        $days = cal_days_in_month(CAL_GREGORIAN,$month[1],$month[0]);
        $dateSun = $this->getSunday($month[0].'-'.$month[1].'-01', $month[0].'-'.$month[1].'-'.$days, 0);
      
       //DB::enableQueryLog();
       $DHUOp = DB::table('dhu_details')->select('dhu_stiching_operation.dhu_so_Name','dhu_stiching_operation.dhu_so_Id')
        ->leftJoin('dhu_master','dhu_master.dhu_code','=','dhu_details.dhu_code')
        ->Join('dhu_stiching_operation','dhu_stiching_operation.dhu_so_Id','=','dhu_details.dhu_so_Id')
        ->where('dhu_master.vendorId','=',$vendorId)
        ->where('dhu_master.line_no','=',$line_no)
        ->DISTINCT()
        ->get();
        //dd(DB::getQueryLog());
        
        $lineData = DB::table('line_master')->select('line_name')
                    ->where('Ac_code','=',$vendorId)
                    ->where('line_id','=',$line_no)
                    ->first();
                    
        $line_name = $lineData->line_name ? $lineData->line_name : "";
        
        return view('rptDHU',compact('days','dateSun','monthDate','DHUOp','vendorId','line_no','line_name'));
    }
    
    
    function getSunday($startDt, $endDt, $weekNum)
    {
        $startDt = strtotime($startDt);
        $endDt = strtotime($endDt);
    
        $dateSun = array();
    
        do
        {
            if(date("w", $startDt) != $weekNum)
            {
                $startDt += (24 * 3600); // add 1 day
            }
        } while(date("w", $startDt) != $weekNum);
    
    
        while($startDt <= $endDt)
        {
            $dateSun[] = date('d', $startDt);
            $startDt += (7 * 24 * 3600); // add 7 days
        }
    
        return($dateSun);
    }
    
    public function GetDHUMainStyleList(Request $request)
    {
        $html = "<option>--Select--</option>";
        $DHUDefectList= DHUStichingDefectTypeModel::select('dhu_sdt_Id','dhu_sdt_Name')->Where('mainstyle_id','=',$request->mainstyle_id)->get();
        foreach($DHUDefectList as $row)
        {
            $html .='<option value="'.$row->dhu_sdt_Id.'">'.$row->dhu_sdt_Name.'</option>';
        }
        
         $DHUStichingOperationList= DHUStichingOperationModel::select('dhu_stiching_operation.dhu_so_Id','dhu_so_Name')
                                    ->Join('dhu_stiching_defect_type','dhu_stiching_defect_type.dhu_so_Id','=','dhu_stiching_operation.dhu_so_Id')
                                    ->where('mainstyle_id','=', $request->mainstyle_id)
                                    ->groupBy('dhu_stiching_defect_type.dhu_so_Id')
                                    ->get();
                                    
        $html1 = "<option>--Select--</option>";
        foreach($DHUStichingOperationList as $row1)
        {
            $html1 .='<option value="'.$row1->dhu_so_Id.'">'.$row1->dhu_so_Name.'</option>';
        }                          
        return response()->json(['html' => $html,'html1' => $html1]);
    }

}
 