<?php

namespace App\Http\Controllers;

use App\Models\TrimsOutwardMasterModel;
use App\Models\TrimsOutwardDetailModel;
use App\Models\LedgerModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\ItemModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class TrimsOutwardController extends Controller
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
->where('form_id', '107')
->first();
        
        
        
         //   DB::enableQueryLog();
         $FabricOutwardList = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
             ->join('process_master', 'process_master.process_id', '=', 'trimOutwardMaster.trim_type')
         ->where('trimOutwardMaster.delflag','=', '0')
         ->get(['trimOutwardMaster.*','usermaster.username','ledger_master.Ac_name','process_master.process_name']);
    
    //   DB::enableQueryLog(); // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('trimOutwardList', compact('FabricOutwardList','chekform'));   
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     
      $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FABRIC_OUTWARD'");
      
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        
          $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $vendorcodeList = DB::table('vendor_work_order_master')->select('vw_code')->where('vendor_work_order_master.delflag','=', '0')->get();
        $itemlist = ItemModel::where('item_master.delflag','=', '0')->get();
         $unitlist = DB::table('unit_master')->get();
        
        return view('TrimsOutward',compact('Ledger','counter_number','MainStyleList','SubStyleList','vendorcodeList','FGList','itemlist','unitlist'));
     
    }

 public function getTrimsItemRate(Request $request)
    { 
        $po_code= base64_decode($request->input('po_code'));
        $item_code= $request->input('item_code');
       
 
//   DB::enableQueryLog();

        $Rate = DB::select("select  item_rate from    trimsInwardDetail
        inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
        where trimsInwardMaster.po_code='".$po_code."' and trimsInwardDetail.item_code='".$item_code."'");
       
//       $query = DB::getQueryLog();
// $query = end($query);
// dd($query); 
        return json_encode($Rate);
    }

public function getVendorCode(Request $request)
{

	    $html = '';
        $html .= '<option value="">--Select--</option>';
       
        $vendorlist = DB::table('vendor_work_order_master')
        ->select('vw_code','sales_order_no')->where('delflag',0)->where('vendorId',$request->vendorId)
        ->get();
        
        
        // ->whereRaw("vendor_work_order_master.vw_code  NOT IN(select trimOutwardMaster.vw_code from trimOutwardMaster where trimOutwardMaster.vw_code= vendor_work_order_master.vw_code)")
        
        
        foreach ($vendorlist as $rowvendor) {
        $html .= '<option value="'.$rowvendor->vw_code.'">'.$rowvendor->vw_code.' ('.$rowvendor->sales_order_no.')</option>';
              }
       
        return response()->json(['html' => $html]);
}


public function getVendorProcessOrder(Request $request)
{

	    $html = '';
        $html .= '<option value="">--Select--</option>';
       
        $vendorlist = DB::table('vendor_purchase_order_master')
        ->select('vpo_code','sales_order_no')->where('delflag',0)->where('vendorId',$request->vendorId)
        
        ->get();
      //  ->whereRaw("vendor_purchase_order_master.vpo_code  NOT IN(select trimOutwardMaster.vpo_code from trimOutwardMaster where trimOutwardMaster.vpo_code= vendor_purchase_order_master.vpo_code)")
        foreach ($vendorlist as $rowvendor) {
        $html .= '<option value="'.$rowvendor->vpo_code.'">'.$rowvendor->vpo_code.' ('.$rowvendor->sales_order_no.')</option>';
              }
       
        return response()->json(['html' => $html]);
}



public function getVendorMasterDetail(Request $request)
{
    
    
    $fetchdata=DB::table('vendor_work_order_master')
    ->select('mainstyle_id','substyle_id','fg_id','style_no','style_description')->where('delflag',0)->where('vw_code',$request->vw_code)->first();
    
    
    echo json_encode($fetchdata);
    
    
}



public function getvendortablenew(Request $request)
{
    
    
    $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
   $itemlist=DB::table('item_master')->get();

    $unitlist=DB::table('unit_master')->get();

    $data=DB::select(DB::raw("SELECT   vendor_work_order_sewing_trims_details.`item_code`,sum(`bom_qty`)  as totalQty,vendor_work_order_sewing_trims_details.unit_id, item_master.item_description  FROM `vendor_work_order_sewing_trims_details`
    inner join item_master on item_master.item_code=vendor_work_order_sewing_trims_details.item_code
    where vw_code='".$request->vw_code."'  group by item_code"));
      
   $html ='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';

       $html .= '
       <div class="table-wrap" id="trimInward">
<div class="table-responsive">
       <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>SrNo</th>
<th>PO NO</th>
<th>Item Code</th>
<th>Item Name</th>
<th>Description</th>
<th>HSN</th>
<th>Unit</th>
<th>Order Qty</th>
<th>Stock Qty</th>
<th>Quantity</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody>';
$no=1;
      foreach ($data as $value) {
    
    
     $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                ) as Stock"));
                
$StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
where item_code='".$value->item_code."' and vw_code='".$request->vw_code."'"));
      
   $html .='<tr>';
    
  $html .=' 
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
<td> <select name="po_code[]" class="select2"  id="po_code" style="width:250px; height:30px;" required onchange="GetTrimsItemList(this);" >
<option value="">--PO NO--</option>';

foreach($POList as  $rowpo)
{
    $html.='<option value="'.$rowpo->po_code.'"';
    $html.='>'.$rowpo->po_code.'</option>';
}
 
$html.='</select></td>
<td>'.$value->item_code.'</td>
<td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" required disabled>
<option value="">--Select Item--</option>';

foreach($itemlist as  $row1)
{
    $html.='<option value="'.$row1->item_code.'"';

    $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->item_name.'</option>';
}
 
$html.='</select></td> 
<td>'.$value->item_description.'</td>
<td><input type="text"  name="hsn_code[]" value="0" id="hsn_code" style="width:80px;" required/> </td>';

$html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
<option value="">--Select Unit--</option>';

foreach($unitlist as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 


    $html.='>'.$rowunit->unit_name.'</option>';
}
 
$html.='</select></td>';
$html.='<td><input type="text"    value="'.($value->totalQty-$StockOut[0]->StockOut).'"   style="width:80px;" readOnly/></td>';
$html.='<td><input type="text"    value="'.$stock[0]->Stock.'"   style="width:80px;" readOnly/></td>';
$html.='<td><input type="text" class="QTY"  name="item_qtys[]"   value="0" id="item_qty" style="width:80px;" required onkeyup="mycalc();"/>
<input type="hidden"    name="item_rate[]"   value="0" id="item_rate" style="width:80px;" required/>
</td> 

<td><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;


      }

     
       $html .='</table>
       </div>
</div>
       
       ';


return response()->json(['html' => $html]); 
    
    
    
    
}


public function getProcessTrimData(Request $request)
{
    
    
     $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
   $itemlist=DB::table('item_master')->get();

    $unitlist=DB::table('unit_master')->get();

    $data=DB::select(DB::raw("SELECT   vendor_purchase_order_packing_trims_details.`item_code`,sum(`bom_qty`)  as totalQty,item_master.item_description, vendor_purchase_order_packing_trims_details.unit_id  FROM `vendor_purchase_order_packing_trims_details`
    inner join item_master on item_master.item_code=vendor_purchase_order_packing_trims_details.item_code
    where vpo_code='".$request->vpo_code."'  group by item_code"));
      
   $html='';

    $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  /> 
       <div class="table-wrap" id="trimInward">
<div class="table-responsive">
       <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>SrNo</th>
<th>PO NO</th>
<th>Item Code</th>
<th>Item Name</th>
<th>Description</th>
<th>HSN</th>
<th>Unit</th>
<th>Order Qty (Remain)</th>
<th>Stock</th>
<th>Quantity</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody>';
$no=1;
      foreach ($data as $value) {
    
       $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                ) as Stock"));
                
$StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
where item_code='".$value->item_code."' and vpo_code='".$request->vpo_code."'"));
    
    
   $html .='<tr>';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 <td> <select name="po_code[]" class="select2"  id="po_code" style="width:250px; height:30px;" required onchange="GetTrimsItemList(this);" >
<option value="">--PO NO--</option>';

foreach($POList as  $rowpo)
{
    $html.='<option value="'.$rowpo->po_code.'"';
    $html.='>'.$rowpo->po_code.'</option>';
}
 
$html.='</select></td> 

<td>'.$value->item_code.' </td>
<td> <select name="item_codes[]"  id="item_code" sstyle="width:250px; height:30px;" required disabled>
<option value="">--Select Item--</option>';

foreach($itemlist as  $row1)
{
    $html.='<option value="'.$row1->item_code.'"';

    $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->item_name.'</option>';
}
 
$html.='</select></td> 
<td>'.$value->item_description.'</td>
<td><input type="text"  name="hsn_code[]" value="0" id="hsn_code" style="width:80px;" required/> </td>';

$html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
<option value="">--Select Unit--</option>';

foreach($unitlist as  $rowunit)
{  
    $html.='<option value="'.$rowunit->unit_id.'"';
    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
    $html.='>'.$rowunit->unit_name.'</option>';
}
$html.='</select></td>';
$html.='<td><input type="text"    value="'.($value->totalQty-$StockOut[0]->StockOut).'" style="width:80px;" readOnly/></td>';
$html.='<td><input type="text"    value="'.$stock[0]->Stock.'"   style="width:80px;" readOnly/></td>';
$html.='<td><input type="text" class="QTY"  name="item_qtys[]"   value="0" id="item_qty" style="width:80px;" required onkeyup="mycalc();"/>
<input type="hidden"    name="item_rate[]"   value="0" id="item_rate" style="width:80px;" required/>
</td>
<td><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;


      }

       
       $html .='</table>
       </div>
</div>
       
       ';


return response()->json(['html' => $html]); 
    
    
    
    
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     

//DB::enableQueryLog();
  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','TRIMOUTWARD')
   ->where('firm_id','=',1)
  ->first();
  //DB::enableQueryLog();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.''.$codefetch->tr_no;
 

    $data = array('trimOutCode'=>$TrNo,
"tout_date"=> $request->input('trimDate'),  
"vendorId"=> $request->input('vendorId'),
"trim_type"=> $request->input('trim_type'),
"vpo_code"=> $request->input('vpo_code'),
"vw_code"=> $request->input('vw_code'),
"mainstyle_id"=> $request->input('mainstyle_id'),
"substyle_id"=> $request->input('substyle_id'),
"fg_id"=> $request->input('fg_id'),
"style_no"=> $request->input('style_no'),
"style_description"=> $request->input('style_description'),
"total_qty"=> $request->input('totalqty'),
"c_code"=> $codefetch->c_code,
"userId"=> $request->input('userId'),
"delflag"=>0
);

// Insert
$value = TrimsOutwardMasterModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='TRIMOUTWARD' AND firm_id=1");  



$itemcodes=count($request->item_codes);



for($x=0;$x<$itemcodes; $x++) {
# code...

$data2=array(

'trimOutCode' =>$TrNo,
'tout_date' => $request->input('trimDate'),
'vendorId' => $request->input('vendorId'),
"trim_type"=> $request->input('trim_type'),
"vpo_code"=> $request->input('vpo_code'),
"vw_code"=> $request->input('vw_code'),
'po_code' => $request->po_code[$x],
'item_code' => $request->item_codes[$x],
'hsn_code' => $request->hsn_code[$x],
'unit_id' => $request->unit_id[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rate[$x]);

TrimsOutwardDetailModel::insert($data2);


}




  return redirect()->route('TrimsOutward.index')->with('message', 'Add Record Succesfully');
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($trimOutCode)
    {
        
        
          $chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '52')
->first();
        
        
        
         //   DB::enableQueryLog();
         $datafetch = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
         ->where('trimOutwardMaster.delflag','=', '0')
           ->where('trimOutwardMaster.trimOutCode',  $trimOutCode)
         ->get(['trimOutwardMaster.*','usermaster.username','ledger_master.Ac_name']);
    
    //   DB::enableQueryLog(); // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('printTrimOutward', compact('datafetch'));   
        
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
      $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
       
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  DB::table('shade_master')->get();
      
        
          $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
          $vendorcodeList = DB::table('vendor_work_order_master')->select('vw_code')->where('vendor_work_order_master.delflag','=', '0')->get();
           $itemlist = ItemModel::where('item_master.delflag','=', '0')->get();
         $unitlist = DB::table('unit_master')->get();
          
        $purchasefetch = TrimsOutwardMasterModel::find($id);
        // DB::enableQueryLog();
        $detailpurchase = TrimsOutwardDetailModel::where('trimOutCode','=', $purchasefetch->trimOutCode)->get(['trimsOutwardDetail.*']);
        // DB::enableQueryLog();
        
   $vendorProcessList = DB::table('vendor_purchase_order_master')->select('vpo_code','sales_order_no')->where('vendor_purchase_order_master.vendorId','=', $purchasefetch->vendorId)->get();
  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
  return view('TrimsOutwardEdit',compact('purchasefetch','vendorProcessList' ,'POList','ShadeList','Ledger','CPList','MainStyleList','SubStyleList','FGList','ItemList','detailpurchase','vendorcodeList','itemlist','unitlist'));
  
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code)
    {
     
     
   $data = array('trimOutCode'=>$request->input('trimOutCode'),
"tout_date"=> $request->input('trimDate'),  
"vendorId"=> $request->input('vendorId'),
"trim_type"=> $request->input('trim_type'),
"vpo_code"=> $request->input('vpo_code'),
"vw_code"=> $request->input('vw_code'),
"mainstyle_id"=> $request->input('mainstyle_id'),
"substyle_id"=> $request->input('substyle_id'),
"fg_id"=> $request->input('fg_id'),
"style_no"=> $request->input('style_no'),
"style_description"=> $request->input('style_description'),
"total_qty"=> $request->input('totalqty'),
"c_code"=> $request->input('c_code'),
"userId"=> $request->input('userId'),
"delflag"=>0
);
 
// Insert
$purchase = TrimsOutwardMasterModel::findOrFail($pur_code);  

$purchase->fill($data)->save();



   DB::table('trimsOutwardDetail')->where('trimOutCode', $request->input('trimOutCode'))->delete();



$itemcodes=count($request->item_codes);



for($x=0;$x<$itemcodes;$x++) {
# code...

$data2=array(

'trimOutCode' =>$request->input('trimOutCode'),
'tout_date' => $request->input('trimDate'),
'vendorId' => $request->input('vendorId'),
"trim_type"=> $request->input('trim_type'),
"vpo_code"=> $request->input('vpo_code'),
"vw_code"=> $request->input('vw_code'),
'po_code' => $request->po_code[$x],
'item_code' => $request->item_codes[$x],
'hsn_code' => $request->hsn_code[$x],
'unit_id' => $request->unit_id[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rate[$x]);

TrimsOutwardDetailModel::insert($data2);


}
  return redirect()->route('TrimsOutward.index')->with('message', 'Update Record Succesfully');

     }


 public function TrimsOutwardData()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        
        
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        
        
        //  DB::enableQueryLog();
        $TrimsOutwardDetails = TrimsOutwardDetailModel::
            leftJoin('trimOutwardMaster', 'trimOutwardMaster.trimOutCode', '=', 'trimsOutwardDetail.trimOutCode')
         -> leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimOutwardMaster.vendorId')
          ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsOutwardDetail.item_code')
            ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
          ->get(['trimsOutwardDetail.*',   'ledger_master.ac_name','item_master.dimension','quality_master.quality_name', 'item_master.item_name','item_master.color_name','item_master.item_description' ]);
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('TrimsOutwardData',compact('TrimsOutwardDetails'));
    }





  public function TrimOutwardStandardPrint($trimCode)
    {
        
        
         $TrimsOutwardMaster = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
    
         ->where('trimOutwardMaster.trimOutCode', $trimCode)
         ->get(['trimOutwardMaster.*','usermaster.username','ledger_master.Ac_name', 'ledger_master.state_id','ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address']);
         return view('TrimsOutwardStandardPrint', compact('TrimsOutwardMaster'));
      
    }

  public function TrimOutwardStandardPrint2($trimCode)
    {
        
        
         $TrimsOutwardMaster = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
    
         ->where('trimOutwardMaster.trimOutCode', $trimCode)
         ->get(['trimOutwardMaster.*','usermaster.username','ledger_master.Ac_name', 'ledger_master.state_id','ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address']);
         return view('TrimsOutwardStandardPrint2', compact('TrimsOutwardMaster'));
      
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($pur_code)
    {
        
           $pur_codes=$pur_code;
          
            $master =TrimsOutwardMasterModel::where('trimOutCode',$pur_code)->delete();      
            $detail =TrimsOutwardDetailModel::where('trimOutCode',$pur_code)->delete();
         
            Session::flash('delete', 'Deleted record successfully');     
        
        
    }
}
