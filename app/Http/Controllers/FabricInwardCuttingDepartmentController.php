<?php

namespace App\Http\Controllers;
use App\Models\FabricOutwardModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\ItemModel;
use App\Models\PartModel;
use App\Models\FabricTransactionModel;
use App\Models\FabricTrimPartModel;
use App\Models\CounterNumberModel;
use Illuminate\Support\Facades\DB;
use App\Models\VendorPurchaseOrderModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\FabricInwardCuttingDepartmentMasterModel;
use App\Models\FabricInwardCuttingDepartmentDetailModel;
use Session;
use DataTables;
date_default_timezone_set("Asia/Kolkata");
ini_set('memory_limit', '1G');

class FabricInwardCuttingDepartmentController extends Controller
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
        ->where('form_id', '358')
        ->first();
        
        
         $FabricOutwardList = DB::table('fabric_inward_cutting_department_master')->
         select('fabric_inward_cutting_department_master.*','usermaster.username','ledger_master.ac_short_name',
         'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name')
         ->leftjoin('usermaster', 'usermaster.userId', '=', 'fabric_inward_cutting_department_master.userId')
         ->leftjoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'fabric_inward_cutting_department_master.mainstyle_id')
         ->leftjoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'fabric_inward_cutting_department_master.substyle_id')
         ->leftjoin('fg_master', 'fg_master.fg_id', '=', 'fabric_inward_cutting_department_master.fg_id')
         ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'fabric_inward_cutting_department_master.vendorId')
         ->where('fabric_inward_cutting_department_master.delflag','=', '0')
         ->get();
     
         
         if ($request->ajax()) 
         {
                return Datatables::of($FabricOutwardList)
                ->addIndexColumn() 
                ->addColumn('action1', function ($row) use ($chekform)
                {
                    
                    if($chekform->edit_access==1 && $row->userId == Session::get('userId') || Session::get('user_type') == 1)
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('FabricInwardCuttingDepartment.edit', $row->ficd_code).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn3;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
              
                    if($chekform->delete_access==1 && $row->userId == Session::get('userId') || Session::get('user_type') == 1)
                    {      
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->ficd_code.'"  data-route="'.route('FabricInwardCuttingDepartment.destroy', $row->ficd_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['action1','action2'])
        
                ->make(true);
        }
         return view('FabricInwardCuttingDepartmentList', compact('FabricOutwardList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FabricInwardCuttingDepartment'");
        $foutList = DB::table('fabric_outward_master')->where('delflag','=', '0')->get();
        $main_style_master = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
        $sub_style_master = DB::SELECT("SELECT * FROM sub_style_master WHERE delflag=0");
        $ledger_master = DB::SELECT("SELECT * FROM ledger_master WHERE delflag=0");
        $fg_master = DB::SELECT("SELECT * FROM fg_master WHERE delflag=0");
        
        return view('FabricInwardCuttingDepartmentMaster',compact('foutList','main_style_master','sub_style_master','ledger_master','fg_master','counter_number'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        //echo '<pre>'; print_r($_POST);exit;
            $data1=array(
                'ficd_code'=>$request->ficd_code, 
                'ficd_date'=>$request->ficd_date,
                'dc_no'=>$request->dc_no,
                'outward_date'=>$request->outward_date, 
                'vendorId'=>$request->vendorId,
                'cutting_po_no'=>$request->cutting_po_no,
                'mainstyle_id' =>$request->mainstyle_id, 
                'substyle_id' =>$request->substyle_id,  
                'fg_id' =>$request->fg_id,
                'style_no' => $request->style_no,  
                'style_description' => $request->style_description,
                'total_challan_meter' => $request->total_challan_meter,
                'total_received_meter' => $request->total_received_meter,
                'total_roll' => $request->total_roll,
                'remark' => $request->remark,
                'userId'=>$request->userId, 
                'updated_at' => $request->updated_at,
                'created_at' => $request->created_at,
                'delflag'=>'0'
            );
 
            FabricInwardCuttingDepartmentMasterModel::insert($data1);
            
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FabricInwardCuttingDepartment'");
            $item_code = $request->input('item_code');
            if(count($item_code)>0)
            {
                    
                for($x=0; $x < count($request->item_code); $x++) 
                {
                    $data2 = array(
                        'ficd_code'        => $request->ficd_code,
                        'ficd_date'        => $request->ficd_date,
                        'roll_no'          => $request->roll_no[$x],
                        'suplier_roll_no'  => $request->suplier_roll_no[$x],
                        'item_code'        => $request->item_code[$x],
                        'item_name'        => $request->item_name[$x],
                        'color_name'       => $request->color_name[$x],
                        'quality_code'     => $request->quality_code[$x],
                        'shade_id'         => $request->shade_id[$x],
                        'width'            => $request->width[$x],
                        'challan_meter'    => $request->challan_meter[$x],
                        'receive_meter'    => $request->receive_meter[$x],
                        'is_approved'      => $request->is_approved[$x] ?? 0,
                    ); 
                
                    FabricInwardCuttingDepartmentDetailModel::insert($data2);
                }

            } 
        
        return redirect()->route('FabricInwardCuttingDepartment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricOutwardModel $fabricOutwardModel)
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
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FabricInwardCuttingDepartment'");
        $foutList = DB::table('fabric_outward_master')->where('delflag','=', '0')->get();
        $main_style_master = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
        $sub_style_master = DB::SELECT("SELECT * FROM sub_style_master WHERE delflag=0");
        $ledger_master = DB::SELECT("SELECT * FROM ledger_master WHERE delflag=0");
        $fg_master = DB::SELECT("SELECT * FROM fg_master WHERE delflag=0");
        $shade_master = DB::SELECT("SELECT * FROM shade_master WHERE delflag=0");
        $quality_master = DB::SELECT("SELECT * FROM quality_master WHERE delflag=0");
       
        $FabricInwardCuttingMasterList = FabricInwardCuttingDepartmentMasterModel::find($id);
        // DB::enableQueryLog();
         
        $FabricInwardCuttingDetailList = FabricInwardCuttingDepartmentDetailModel::join('quality_master','quality_master.quality_code', '=', 'fabric_inward_cutting_department_details.quality_code') 
        ->join('item_master', 'item_master.item_code', '=', 'fabric_inward_cutting_department_details.item_code')
        ->join('shade_master', 'shade_master.shade_id', '=', 'fabric_inward_cutting_department_details.shade_id')
        ->where('fabric_inward_cutting_department_details.ficd_code','=', $FabricInwardCuttingMasterList->ficd_code)
        ->get(['fabric_inward_cutting_department_details.*','quality_master.quality_name','item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'shade_master.shade_name']);
   
        return view('FabricInwardCuttingDepartmentEdit',compact('FabricInwardCuttingMasterList','FabricInwardCuttingDetailList', 'foutList', 'main_style_master', 'sub_style_master', 'ledger_master','fg_master','counter_number','shade_master','quality_master'));
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FabricOutwardModel $fabricOutwardModel)
    {
            // echo '<pre>'; print_r($_POST);exit;
             $data1=array(
                'ficd_code'=>$request->ficd_code, 
                'ficd_date'=>$request->ficd_date,
                'dc_no'=>$request->dc_no,
                'outward_date'=>$request->outward_date, 
                'vendorId'=>$request->vendorId,
                'cutting_po_no'=>$request->cutting_po_no,
                'mainstyle_id' =>$request->mainstyle_id, 
                'substyle_id' =>$request->substyle_id,  
                'fg_id' =>$request->fg_id,
                'style_no' => $request->style_no,  
                'style_description' => $request->style_description,
                'total_challan_meter' => $request->total_challan_meter,
                'total_received_meter' => $request->total_received_meter,
                'total_roll' => $request->total_roll,
                'remark' => $request->remark,
                'userId'=>$request->userId, 
                'updated_at' => $request->updated_at,
                'created_at' => $request->created_at,
                'delflag'=>'0'
            );
            
            $FabricInwardCuttingList = FabricInwardCuttingDepartmentMasterModel::findOrFail($request->ficd_code);  
   
            $FabricInwardCuttingList->fill($data1)->save();

            DB::table('fabric_inward_cutting_department_details')->where('ficd_code', $request->ficd_code)->delete();

            $item_code = $request->input('item_code');
            if(count($item_code)>0)
            {
                for($x=0; $x < count($request->item_code); $x++) 
                {
                    $data2 = array(
                        'ficd_code'        => $request->ficd_code,
                        'ficd_date'        => $request->ficd_date,
                        'roll_no'          => $request->roll_no[$x],
                        'suplier_roll_no'  => $request->suplier_roll_no[$x],
                        'item_code'        => $request->item_code[$x],
                        'item_name'        => $request->item_name[$x],
                        'color_name'       => $request->color_name[$x],
                        'quality_code'     => $request->quality_code[$x],
                        'shade_id'         => $request->shade_id[$x],
                        'width'            => $request->width[$x],
                        'challan_meter'    => $request->challan_meter[$x],
                        'receive_meter'    => $request->receive_meter[$x],
                        'is_approved'      => $request->is_approved[$x] ?? 0,
                    ); 
                
                    FabricInwardCuttingDepartmentDetailModel::insert($data2);
                }
            } 
                
            return redirect()->route('FabricInwardCuttingDepartment.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {      
        
        DB::table('fabric_inward_cutting_department_master')->where('ficd_code', $id)->delete();
        DB::table('fabric_inward_cutting_department_details')->where('ficd_code', $id)->delete();
  
        Session::flash('delete', 'Deleted record successfully'); 
        
    }

    
    public function GetFabricOutwardData(Request $request)
    {
        $masterData = DB::SELECT("SELECT * FROM fabric_outward_master WHERE fout_code='".$request->fout_code."'");
        $detailData = DB::SELECT("SELECT fabric_outward_details.*, item_master.item_name,item_master.color_name,item_master.quality_code FROM fabric_outward_details 
                                  INNER JOIN item_master ON item_master.item_code = fabric_outward_details.item_code
                                  WHERE fabric_outward_details.fout_code='".$request->fout_code."'");
                                  
        $html = '';
        $sr_no = 1;
        
        $fg_id = isset($masterData[0]->fg_id) ? $masterData[0]->fg_id : 0;
        $mainstyle_id = isset($masterData[0]->mainstyle_id) ? $masterData[0]->mainstyle_id : 0;
        $substyle_id = isset($masterData[0]->substyle_id) ? $masterData[0]->substyle_id : 0;
        $vendorId = isset($masterData[0]->vendorId) ? $masterData[0]->vendorId : 0;
        $style_no = isset($masterData[0]->style_no) ? $masterData[0]->style_no : 0;
        $style_description = isset($masterData[0]->style_description) ? $masterData[0]->style_description : 0;
        $vpo_code = isset($masterData[0]->vpo_code) ? $masterData[0]->vpo_code : 0;
        $outward_date = isset($masterData[0]->fout_date) ? $masterData[0]->fout_date : '';
        
        $shade_master = DB::SELECT("SELECT * FROM shade_master WHERE delflag=0");
        $quality_master = DB::SELECT("SELECT * FROM quality_master WHERE delflag=0");
        $main_style_master = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
        $sub_style_master = DB::SELECT("SELECT * FROM sub_style_master WHERE delflag=0");
        $ledger_master = DB::SELECT("SELECT * FROM ledger_master WHERE delflag=0");
        $fg_master = DB::SELECT("SELECT * FROM fg_master WHERE delflag=0");
        
        foreach($detailData as $row)
        {
          
            $html .='<tr>
                       <td><input type="text"  style="width:60px;" value="'.($sr_no++).'" readonly></td>
                       <td><input type="text" name="roll_no[]" value="'.$row->track_code.'" id="roll_no" style="width:80px;" readonly></td>
                       <td><input type="text" name="suplier_roll_no[]" value="'.$row->roll_no.'" onchange="calTotals();" id="suplier_roll_no" style="width:80px;" readonly></td>
                       <td><input type="text" name="item_code[]" value="'.$row->item_code.'" id="suplier_roll_no" style="width:80px;" readonly></td>
                       <td><input type="text" name="item_name[]" value="'.$row->item_name.'" id="item_name" style="width:80px;" readonly></td>
                       <td><input type="text" name="color_name[]" value="'.$row->color_name.'" id="color_id" style="width:80px;" readonly></td>
                       <td>
                            <select name="quality_code[]" class="form-select select2" id="quality_code"  style="width:100px;" disabled>
                               <option value="">-- Select --</option>';
                               foreach($quality_master as $qrow)
                               {  
                                    $selected1 = '';
                                    if($qrow->quality_code == $row->quality_code)
                                    {
                                        $selected1 = 'selected';
                                    }
            
                                    $html .='<option value="'.$qrow->quality_code.'" '.$selected1.'>'.$qrow->quality_name.'</option>';
                               }
                            $html .='</select>
                       </td>
                       <td>
                            <select name="shade_id[]" class="form-select select2" id="shade_id"  style="width:80px;" disabled>
                               <option value="">-- Select --</option>';
                               foreach($shade_master as $srow)
                               {
                                    $selected2 = '';
                                    if($srow->shade_id == $row->shade_id)
                                    {
                                        $selected2 = 'selected';
                                    }
            
                                    $html .='<option value="'.$srow->shade_id.'" '.$selected2.'>'.$srow->shade_name.'</option>';
                               }
                            $html .='</select>
                       </td>
                       <td><input type="text" name="width[]" value="'.$row->width.'" id="width" style="width:80px;" readonly></td>
                       <td><input type="text" name="challan_meter[]" value="'.$row->meter.'" id="challan_meter" onchange="calTotals();" style="width:80px;"></td>
                       <td><input type="text" name="receive_meter[]" value="'.$row->meter.'" id="receive_meter" onchange="calTotals();" style="width:80px;"></td> 
                       <td>
                            <input type="hidden" name="is_approved[]" value="0" class="is_approved">
                            <input type="checkbox" name="is_approved[]" value="1" id="is_approved" class="approve-checkbox" onchange="GetApprovedStatus(this);" style="width:80px;height: 25px;">
                       </td>
                    </tr>';
        }
        
        return response()->json(['masterData' => $masterData, 'html'=>$html, 'fg_id'=>$fg_id,'mainstyle_id'=>$mainstyle_id,'substyle_id'=>$substyle_id,'vendorId'=>$vendorId,
                                'style_no'=>$style_no,'style_description'=>$style_description,'vpo_code'=>$vpo_code,'outward_date'=>$outward_date]);
    }
 
    public function FabricStockCuttingWIP(Request $request)
    {
        $fromDate = $request->fromDate ?? "Y-m-01";
        $toDate = $request->toDate ?? "Y-m-d";
        $vendorId = $request->vendorId ?? 0;
        $previous_date = date('Y-m-d', strtotime($fromDate . ' -1 day'));
        $filter1 = '';
        $filter2 = '';
        
        if($vendorId > 0)
        {
            $filter1 .= ' AND cut_panel_grn_detail.vendorId='.$vendorId;
            $filter2 .= ' AND fabric_inward_cutting_department_master.vendorId='.$vendorId;
        }
        
        $vendorData = DB::select("SELECT ledger_master.ac_code, ledger_master.ac_short_name 
            FROM ledger_master 
            INNER JOIN cut_panel_grn_detail 
                ON cut_panel_grn_detail.vendorId = ledger_master.ac_code 
            WHERE ledger_master.delflag = 0 AND ledger_master.bt_id = 4 GROUP BY cut_panel_grn_detail.vendorId");

      
         $FabricStockCuttingWIPData = DB::SELECT("
                                    SELECT 
                                        cut_panel_grn_detail.cpg_date AS cpg_date,
                                        cut_panel_grn_detail.sales_order_no,
                                        cut_panel_grn_detail.vpo_code,
                                        ledger_master.ac_short_name AS vendorName,
                                        cut_panel_grn_detail.item_code,
                                        item_master.item_name,
                                        color_master.color_name,
                                        cut_panel_grn_detail.vendorId,
                                        cut_panel_grn_detail.color_id,
                                        sales_order_fabric_costing_details.consumption
                                
                                    FROM cut_panel_grn_detail 
                                
                                    INNER JOIN ledger_master 
                                        ON ledger_master.ac_code = cut_panel_grn_detail.vendorId
                                
                                    INNER JOIN vendor_purchase_order_detail 
                                        ON vendor_purchase_order_detail.vpo_code = cut_panel_grn_detail.vpo_code AND vendor_purchase_order_detail.item_code = cut_panel_grn_detail.item_code 
                                        
                                    INNER JOIN sales_order_fabric_costing_details 
                                        ON sales_order_fabric_costing_details.sales_order_no = vendor_purchase_order_detail.sales_order_no
                                
                                    INNER JOIN color_master 
                                        ON color_master.color_id = cut_panel_grn_detail.color_id   
                                
                                    INNER JOIN item_master 
                                        ON item_master.item_code = cut_panel_grn_detail.item_code 
                                
                                    WHERE cut_panel_grn_detail.cpg_date BETWEEN '".$fromDate."' AND '".$toDate."' ".$filter1."
                                
                                    GROUP BY  
                                        cut_panel_grn_detail.vpo_code,
                                        cut_panel_grn_detail.cpg_date,
                                        cut_panel_grn_detail.item_code
                                
                                    UNION
                                
                                    SELECT 
                                        fabric_inward_cutting_department_details.ficd_date AS cpg_date,
                                        vendor_purchase_order_detail.sales_order_no,
                                        vendor_purchase_order_detail.vpo_code,
                                        ledger_master.ac_short_name AS vendorName,
                                        fabric_inward_cutting_department_details.item_code,
                                        item_master.item_name,
                                        color_master.color_name,
                                        fabric_inward_cutting_department_master.vendorId,
                                        vendor_purchase_order_detail.color_id,
                                        sales_order_fabric_costing_details.consumption
                                
                                    FROM fabric_inward_cutting_department_master
                                
                                    LEFT JOIN fabric_inward_cutting_department_details 
                                        ON fabric_inward_cutting_department_details.ficd_code = fabric_inward_cutting_department_master.ficd_code 
                                        
                                    INNER JOIN vendor_purchase_order_detail 
                                        ON vendor_purchase_order_detail.vpo_code = fabric_inward_cutting_department_master.cutting_po_no AND vendor_purchase_order_detail.item_code = fabric_inward_cutting_department_details.item_code 
                                
                                    INNER JOIN sales_order_fabric_costing_details 
                                        ON sales_order_fabric_costing_details.sales_order_no = vendor_purchase_order_detail.sales_order_no
                                         
                                    INNER JOIN color_master 
                                        ON color_master.color_id = vendor_purchase_order_detail.color_id 
                                        
                                    INNER JOIN item_master 
                                        ON item_master.item_code = fabric_inward_cutting_department_details.item_code 
                                
                                    INNER JOIN ledger_master 
                                        ON ledger_master.ac_code = fabric_inward_cutting_department_master.vendorId 
                                
                                    WHERE fabric_inward_cutting_department_details.ficd_date BETWEEN '".$fromDate."' AND '".$toDate."' ".$filter2."
                                
                                    GROUP BY 
                                        fabric_inward_cutting_department_master.cutting_po_no,
                                        fabric_inward_cutting_department_details.ficd_date,
                                        fabric_inward_cutting_department_details.item_code");

    
        
        return view('FabricStockCuttingWIP', compact('FabricStockCuttingWIPData', 'fromDate','toDate', 'vendorData', 'vendorId'));
    }
    
    public function getBetweenDates($startDate, $endDate)
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
