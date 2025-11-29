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
use App\Models\TrimsOutwardCuttingDepartmentMasterModel;
use App\Models\TrimsOutwardCuttingDepartmentDetailModel;
use App\Models\RackModel;
use Session;
use DataTables;
date_default_timezone_set("Asia/Kolkata");
ini_set('memory_limit', '1G');
setlocale(LC_MONETARY, 'en_IN');

class TrimsOutwardCuttingDepartmentController extends Controller
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
        ->where('form_id', '365')
        ->first();
        
        
         $TrimsOutwardList = DB::table('trims_outward_cutting_department_master')->
         select('trims_outward_cutting_department_master.*','usermaster.username','ledger_master.ac_short_name',
         'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name')
         ->leftjoin('usermaster', 'usermaster.userId', '=', 'trims_outward_cutting_department_master.userId')
         ->leftjoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'trims_outward_cutting_department_master.mainstyle_id')
         ->leftjoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'trims_outward_cutting_department_master.substyle_id')
         ->leftjoin('fg_master', 'fg_master.fg_id', '=', 'trims_outward_cutting_department_master.fg_id')
         ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'trims_outward_cutting_department_master.vendorId')
         ->where('trims_outward_cutting_department_master.delflag','=', '0')
         ->get();
     
         
         if ($request->ajax()) 
         {
                return Datatables::of($TrimsOutwardList)
                ->addIndexColumn() 
                ->addColumn('action1', function ($row) use ($chekform)
                {
                    
                    if($chekform->edit_access==1 && $row->userId == Session::get('userId') || Session::get('user_type') == 1)
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('TrimsOutwardCuttingDepartment.edit', $row->tocd_code).'" >
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
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->tocd_code.'"  data-route="'.route('TrimsOutwardCuttingDepartment.destroy', $row->tocd_code).'"><i class="fas fa-trash"></i></a>'; 
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
         return view('TrimsOutwardCuttingDepartmentList', compact('TrimsOutwardList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='TrimsOutwardCuttingDepartment'");
        $toutList = DB::table('trimoutwardmaster')->where('delflag','=', '0')->get();
        $main_style_master = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
        $sub_style_master = DB::SELECT("SELECT * FROM sub_style_master WHERE delflag=0");
        $ledger_master = DB::SELECT("SELECT * FROM ledger_master WHERE delflag=0");
        $fg_master = DB::SELECT("SELECT * FROM fg_master WHERE delflag=0");
        
        return view('TrimsOutwardCuttingDepartmentMaster',compact('toutList','main_style_master','sub_style_master','ledger_master','fg_master','counter_number'));

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
                'tocd_code'=>$request->tocd_code, 
                'tocd_date'=>$request->tocd_date,
                'dc_no'=>$request->dc_no,
                'outward_date'=>$request->outward_date, 
                'vendorId'=>$request->vendorId,
                'cutting_po_no'=>$request->cutting_po_no,
                'mainstyle_id' =>$request->mainstyle_id, 
                'substyle_id' =>$request->substyle_id,  
                'fg_id' =>$request->fg_id,
                'style_no' => $request->style_no,  
                'style_description' => $request->style_description,
                'total_outward_meter' => $request->total_outward_meter, 
                'total_received_meter' => $request->total_received_meter, 
                'remark' => $request->remark,
                'userId'=>$request->userId, 
                'updated_at' => date("Y-m-d h:i:s"),
                'created_at' => date("Y-m-d h:i:s"),
                'delflag'=>'0'
            );
 
            TrimsOutwardCuttingDepartmentMasterModel::insert($data1);
            
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='TrimsOutwardCuttingDepartment'");
            $item_code = $request->input('item_code');
            if(count($item_code)>0)
            {
                    
                for($x=0; $x < count($request->item_code); $x++) 
                {
                    $data2 = array(
                        'tocd_code'        => $request->tocd_code,
                        'tocd_date'        => $request->tocd_date, 
                        'item_code'        => $request->item_code[$x],
                        'item_name'        => $request->item_name[$x], 
                        'item_qty'         => $request->item_qty[$x],
                        'outward_qty'      => $request->outward_qty[$x]
                    ); 
                
                    TrimsOutwardCuttingDepartmentDetailModel::insert($data2);
                }

            } 
        
        return redirect()->route('TrimsOutwardCuttingDepartment.index');
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
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='TrimsOutwardCuttingDepartment'");
        $foutList = DB::table('trimoutwardmaster')->where('delflag','=', '0')->get();
        $main_style_master = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
        $sub_style_master = DB::SELECT("SELECT * FROM sub_style_master WHERE delflag=0");
        $ledger_master = DB::SELECT("SELECT * FROM ledger_master WHERE delflag=0");
        $fg_master = DB::SELECT("SELECT * FROM fg_master WHERE delflag=0");
       
        $TrimsOutwardCuttingMasterList = TrimsOutwardCuttingDepartmentMasterModel::find($id);
        // DB::enableQueryLog();
         
        $TrimsInwardCuttingDetailList = TrimsOutwardCuttingDepartmentDetailModel::join('item_master', 'item_master.item_code', '=', 'trims_outward_cutting_department_details.item_code')
        ->where('trims_outward_cutting_department_details.tocd_code','=', $TrimsOutwardCuttingMasterList->tocd_code)
        ->get(['trims_outward_cutting_department_details.*','item_master.item_name', 'item_master.item_description']);
   
        return view('TrimsOutwardCuttingDepartmentEdit',compact('TrimsOutwardCuttingMasterList','TrimsInwardCuttingDetailList', 'foutList', 'main_style_master', 'sub_style_master', 'ledger_master','fg_master','counter_number'));
   
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
                'tocd_code'=>$request->tocd_code, 
                'tocd_date'=>$request->tocd_date,
                'dc_no'=>$request->dc_no,
                'outward_date'=>$request->outward_date, 
                'vendorId'=>$request->vendorId,
                'cutting_po_no'=>$request->cutting_po_no,
                'mainstyle_id' =>$request->mainstyle_id, 
                'substyle_id' =>$request->substyle_id,  
                'fg_id' =>$request->fg_id,
                'style_no' => $request->style_no,  
                'style_description' => $request->style_description,
                'total_outward_meter' => $request->total_outward_meter, 
                'total_received_meter' => $request->total_received_meter, 
                'remark' => $request->remark,
                'userId'=>$request->userId, 
                'updated_at' => date("Y-m-d h:i:s"), 
                'delflag'=>'0'
            );
            
            $FabricInwardCuttingList = TrimsOutwardCuttingDepartmentMasterModel::findOrFail($request->tocd_code);  
   
            $FabricInwardCuttingList->fill($data1)->save();

            DB::table('trims_outward_cutting_department_details')->where('tocd_code', $request->tocd_code)->delete();

            $item_code = $request->input('item_code');
            if(count($item_code)>0)
            {
                 
                for($x=0; $x < count($request->item_code); $x++) 
                {
                    $data2 = array(
                        'tocd_code'        => $request->tocd_code,
                        'tocd_date'        => $request->tocd_date, 
                        'item_code'        => $request->item_code[$x],
                        'item_name'        => $request->item_name[$x], 
                        'item_qty'         => $request->item_qty[$x],
                        'outward_qty'      => $request->outward_qty[$x]
                    ); 
                
                    TrimsOutwardCuttingDepartmentDetailModel::insert($data2);
                }

            } 
                
            return redirect()->route('TrimsOutwardCuttingDepartment.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {      
        
        DB::table('trims_outward_cutting_department_master')->where('tocd_code', $id)->delete();
        DB::table('trims_outward_cutting_department_details')->where('tocd_code', $id)->delete();
  
        Session::flash('delete', 'Deleted record successfully'); 
        
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

    public function GetTrimsOutwardCuttingData(Request $request)
    {
        // DB::enableQueryLog();
        $masterData = DB::SELECT("SELECT * FROM trimoutwardmaster WHERE trimOutCode='".$request->trimOutCode."'");
        // dd(DB::getQueryLog());
        $detailData = DB::SELECT("SELECT trimsoutwarddetail.*, item_master.item_name,item_master.color_name,item_master.quality_code FROM trimsoutwarddetail 
                                  INNER JOIN item_master ON item_master.item_code = trimsoutwarddetail.item_code
                                  WHERE trimsoutwarddetail.trimOutCode='".$request->trimOutCode."'");
                                  
        $html = '';
        $sr_no = 1;
        
        $fg_id = isset($masterData[0]->fg_id) ? $masterData[0]->fg_id : 0;
        $mainstyle_id = isset($masterData[0]->mainstyle_id) ? $masterData[0]->mainstyle_id : 0;
        $substyle_id = isset($masterData[0]->substyle_id) ? $masterData[0]->substyle_id : 0;
        $vendorId = isset($masterData[0]->vendorId) ? $masterData[0]->vendorId : 0;
        $style_no = isset($masterData[0]->style_no) ? $masterData[0]->style_no : 0;
        $style_description = isset($masterData[0]->style_description) ? $masterData[0]->style_description : 0;
        $vpo_code = isset($masterData[0]->vpo_code) ? $masterData[0]->vpo_code : 0;
        $outward_date = isset($masterData[0]->tout_date) ? $masterData[0]->tout_date : ''; 

        foreach($detailData as $row)
        {
          
            $html .='<tr>
                       <td><input type="text"  style="width:60px;" value="'.($sr_no++).'" readonly></td>
                       <td><input type="text" name="item_code[]" value="'.$row->item_code.'" id="item_code" style="width:80px;" readonly></td>
                       <td><input type="text" name="item_name[]" value="'.$row->item_name.'" id="item_name" style="width:80px;" readonly></td>
                       <td><input type="number" step="any" name="item_qty[]" value="'.$row->item_qty.'" id="item_qty" onchange="calTotals();" style="width:80px;" readonly></td>
                       <td><input type="number" step="any" name="outward_qty[]" max="'.$row->item_qty.'" id="outward_qty" onchange="calTotals();" onkeyup="checkMax(this);" style="width:80px;"></td>  
                    </tr>';
        }
        
        return response()->json(['masterData' => $masterData, 'html'=>$html, 'fg_id'=>$fg_id,'mainstyle_id'=>$mainstyle_id,'substyle_id'=>$substyle_id,'vendorId'=>$vendorId,
                                'style_no'=>$style_no,'style_description'=>$style_description,'vpo_code'=>$vpo_code,'outward_date'=>$outward_date]);
    }
 

    public function GetTrimsOutwardCuttingDeptData(Request $request)
    {
        $detailData = DB::SELECT("
            SELECT trims_outward_cutting_department_details.*, 
                item_master.item_name,
                item_master.color_name,
                item_master.unit_id 
            FROM trims_outward_cutting_department_details 
            INNER JOIN item_master 
                ON item_master.item_code = trims_outward_cutting_department_details.item_code
            WHERE trims_outward_cutting_department_details.tocd_code = ?
        ", [$request->tocd_code]);

        $html = '';
        $sr_no = 1;

        $itemlist = DB::table('item_master')
                        ->where('delflag', '0')
                        ->where('cat_id', '!=', '1')
                        ->get();

        $unitlist = DB::table('unit_master')
                        ->where('delflag', '0')
                        ->get(); 

        $RackList = RackModel::where('delflag', '0')->get();

        foreach($detailData as $row)
        {
            $html .= '<tr>
                <td><input type="text" style="width:60px;" value="'.($sr_no++).'" readonly></td>
                <td>
                    <select name="item_codes[]" style="width:150px;height:30px;">
                        <option value="">--- Select ---</option>';

                        foreach($itemlist as $item){
                            $selected = ($item->item_code == $row->item_code) ? 'selected' : '';
                            $html .= '<option value="'.$item->item_code.'" '.$selected.'>'.$item->item_name.'</option>';
                        }

            $html .= '</select>
                </td>

                <td>
                    <select name="unit_ids[]" style="width:150px;height:30px;">
                        <option value="">--- Select ---</option>';

                        foreach($unitlist as $unit){
                            $selected = ($unit->unit_id == $row->unit_id) ? 'selected' : '';
                            $html .= '<option value="'.$unit->unit_id.'" '.$selected.'>'.$unit->unit_name.'</option>';
                        }

            $html .= '</select>
                </td>

                <td><input type="number" step="any" class="QTY"
                        name="item_qtys[]" onchange="SetQtyToBtn(this);" 
                        value="'.$row->item_qty.'" style="width:80px;height:30px;" required></td>

                <td><input type="number" step="any" name="item_rates[]" value="0"
                        style="width:80px;height:30px;" required></td>

                <td><input type="number" step="any" class="AMT" readonly
                        name="amounts[]" value="0"
                        style="width:80px;height:30px;" required>
                    <input type="hidden" name="hsn_codes[]" value="0">
                </td>

                <td>
                    <select name="rack_id[]" class="select2" style="width:100px;height:30px;" required>
                        <option value="">--Racks--</option>';

                        foreach($RackList as $rack){
                            $html .= '<option value="'.$rack->rack_id.'">'.$rack->rack_name.'</option>';
                        }

            $html .= '</select>
                </td>

                <td class="text-center">
                    <button type="button" onclick="mycalc();" class="btn btn-warning">+</button>
                </td>

                <td class="text-center">
                    <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X">
                </td>
            </tr>';
        }

        return response()->json([
            'html' => $html
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

}
