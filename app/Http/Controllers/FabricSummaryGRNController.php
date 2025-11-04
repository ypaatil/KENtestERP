<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FabricSummaryGRNMasterModel;
use App\Models\FabricSummaryGRNDetailModel;
use App\Models\StockAssociationForFabricModel;
use App\Models\FabricInwardModel;
use App\Models\FabricInwardDetailModel;
use App\Models\transportModel;

use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\PurchaseOrderModel; 
use App\Models\ColorModel;
use App\Models\QualityModel;
use App\Models\PartModel;
use App\Models\ItemModel;
use App\Models\RackModel;
use App\Models\CounterNumberModel;
use Image;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Printing;
use Session;
use App\Models\POTypeModel;

 
class FabricSummaryGRNController extends Controller
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
        ->where('form_id', '194')
        ->first(); 
        
         $FabricGRNList = FabricSummaryGRNMasterModel::join('usermaster', 'usermaster.userId', '=', 'fabric_summary_grn_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_summary_grn_master.supplier_id')
         ->leftJoin('transport_master', 'transport_master.transport_id', '=', 'fabric_summary_grn_master.transport_id')
         ->where('fabric_summary_grn_master.delflag','=', '0')
         ->get(['fabric_summary_grn_master.*','usermaster.username','ledger_master.Ac_name','transport_master.transport_name']);
  
         return view('FabricSummaryGRNMasterList', compact('FabricGRNList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no', PBarcode, CBarcode
        from counter_number where c_name ='C1' AND type='FABRIC_SUMMARY_GRN'");
        
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get(); 
        $chkList = DB::SELECT("SELECT fabric_checking_master.*  FROM fabric_checking_master LEFT JOIN fabric_summary_grn_master  ON fabric_checking_master.chk_code = fabric_summary_grn_master.chk_code WHERE fabric_summary_grn_master.chk_code IS NULL");
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $TransportList = transportModel::where('transport_master.delflag','=', '0')->get();
        return view('FabricSummaryGRNMaster',compact('Ledger','chkList','POList', 'TransportList', 'counter_number','ItemList','POTypeList','gstlist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
             
                'fsg_code'=>'required',
                'fsg_date'=>'required',
                'supplier_id'=>'required',
                'total_qty'=>'required',
                'c_code'=>'required',
                 ]);
                 
        
                
    $data1=array(
        'fsg_code'=>$request->fsg_code, 'fsg_date'=>$request->fsg_date,'po_code'=>$request->po_code,'chk_code'=>$request->chk_code,'challan_no'=>$request->challan_no,'po_type_id'=>$request->po_type_id, 
        'supplier_id'=>$request->supplier_id, 'challan_date'=>$request->challan_date,
        'invoice_no' =>$request->invoice_no, 'invoice_date' => $request->invoice_date, 'transport_id'=>$request->transport_id,
        'freight_paid'=>$request->freight_paid,  'total_qty'=>$request->total_qty, 
        'narration'=>$request->narration,  'c_code' => $request->c_code,'userId'=>$request->userId, 'delflag'=>'0'
     );
    
    FabricSummaryGRNMasterModel::insert($data1);
 
    DB::select("update counter_number set tr_no=tr_no + 1   where c_name ='C1' AND type='FABRIC_SUMMARY_GRN'"); 
  
    $item_codes = $request->input('item_codes');
    if($request->allocate_qty != "")
    {
      
        $allocate_qtys=count($request->allocate_qty);
    }
    if(count($item_codes)>0)
    {
        
        for($x=0; $x<count($item_codes); $x++) {
        # code...
       
                    $data2[]=array(
                    'fsg_code' =>$request->fsg_code,
                    'fsg_date' => $request->fsg_date,
                    'po_code' => $request->po_code,
                    'challan_no'=>$request->challan_no,
                    'challan_date' =>$request->challan_date,
                    'invoice_no' =>$request->invoice_no,
                    'invoice_date'=>$request->invoice_date,
                    'item_code'=>$request->item_codes[$x], 
                    'item_qty' => $request->item_qtys[$x],
                    'item_rate' => $request->item_rates[$x],
                    );
                   
            }
         FabricSummaryGRNDetailModel::insert($data2);
    }
    for($y=0;$y<$allocate_qtys; $y++) 
    {
        
           $data3 = array(
                "po_code"=> $request->po_code,
                "po_date"=> $request->input('fsg_date'),
                "tr_code"=> $request->fsg_code,  
                "tr_date"=> $request->input('fsg_date'),
                'bom_code'=> $request->stock_bom_code[$y],
                'sales_order_no'=>$request->sales_order_no[$y],
                'cat_id'=>$request->cat_id[$y],
                'class_id'=>$request->class_id[$y],
                "item_code"=> $request->item_code[$y],
                'unit_id' => 0,
                'qty' => $request->allocate_qty[$y],
                "tr_type"=> 1,
            );

            StockAssociationForFabricModel::insert($data3);
    }
   return redirect()->route('FabricSummaryGRN.index')->with('message', ' Record Created Succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricInwardModel $fabricInwardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        //$id=base64_decode($id);
       // echo $id; exit;
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no',PBarcode,CBarcode
      
        from counter_number where c_name ='C1' AND type='FABRIC_SUMMARY_GRN'");
        
        //   (SELECT max(substr(track_code,2,15))  FROM inward_details WHERE track_code like 'P%') as PBarcode,
        // (SELECT max(substr(track_code,2,15))  FROM inward_details WHERE track_code like 'I%') as CBarcode
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $TransportList = transportModel::where('transport_master.delflag','=', '0')->get();
        $itemlist = ItemModel::where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $unitlist = DB::table('unit_master')->where('unit_master.delflag','=', '0')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        $chkList = DB::table('fabric_checking_master')->where('fabric_checking_master.delflag','=', '0')->get(); 
        //  DB::enableQueryLog();
        $FabricSummaryGRNMasterList = FabricSummaryGRNMasterModel::where('sr_no',$id)->first();
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        //$FabricSummaryGRNDetails = FabricSummaryGRNDetailModel::where('fabric_summary_grn_detail.fsg_code','=', $FabricSummaryGRNMasterList->fsg_code)->get(['fabric_summary_grn_detail.*']);
  
        // $FabricSummaryGRNDetails = FabricSummaryGRNMasterModel::join('item_master','item_master.item_code', '=', 'fabric_summary_grn_detail.item_code')
        // ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'fabric_summary_grn_detail.po_code')
        // ->where('sr_no','=', $id)
        // ->get(['fabric_summary_grn_detail.*','item_master.item_name','item_master.item_description','purchase_order.bom_code','item_master.cat_id','item_master.class_id']);
       
       $po_sr_no=DB::select("select sr_no from purchase_order where pur_code='".$FabricSummaryGRNMasterList->po_code."'");
       if(count($po_sr_no) == 0)
       {
            $po_sr_no=DB::select("select sr_no from fabric_checking_details where po_code='".$FabricSummaryGRNMasterList->po_code."'");
       }
    //   DB::enableQueryLog();
       $FabricSummaryGRNDetails = DB::select("select fabric_summary_grn_detail.*, item_master.item_name, item_master.item_description, purchase_order.bom_code,item_master.hsn_code, item_master.cat_id, item_master.unit_id,
        item_master.class_id,fabric_summary_grn_master.total_qty,inward_master.is_opening from fabric_summary_grn_master
        LEFT JOIN fabric_summary_grn_detail ON fabric_summary_grn_detail.fsg_code = fabric_summary_grn_master.fsg_code 
        LEFT join item_master on item_master.item_code = fabric_summary_grn_detail.item_code 
        LEFT join purchase_order on purchase_order.pur_code = fabric_summary_grn_detail.po_code 
        LEFT JOIN inward_master ON inward_master.po_code = purchase_order.pur_code 
        where fabric_summary_grn_master.sr_no=".$id." GROUP BY fabric_summary_grn_detail.item_code"); 
        //  dd(DB::getQueryLog());
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('FabricSummaryGRNMasterEdit',compact('chkList','FabricSummaryGRNMasterList','POList','po_sr_no', 'ColorList','RackList','unitlist', 'TransportList', 'Ledger',   'FabricSummaryGRNDetails','counter_number','itemlist','POTypeList','gstlist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    { 
        
       
        
        $this->validate($request, [
             
                'fsg_code'=>'required',
                'fsg_date'=>'required',
                'supplier_id'=>'required',
                'total_qty'=>'required',
                'c_code'=>'required',
             ]);

 
 $fsg_code=base64_decode($request->fsg_code);
 //echo $fsg_code;
 $data1=array(

        'fsg_code'=>$fsg_code, 'fsg_date'=>$request->fsg_date,'po_code'=>$request->po_code,'chk_code'=>$request->chk_code,'challan_no'=>$request->challan_no,'po_type_id'=>$request->po_type_id, 
        'supplier_id'=>$request->supplier_id, 'challan_date'=>$request->challan_date,
        'invoice_no' =>$request->invoice_no, 'invoice_date' => $request->invoice_date, 'transport_id'=>$request->transport_id,
        'freight_paid'=>$request->freight_paid,  'total_qty'=>$request->total_qty, 
        'narration'=>$request->narration,  'c_code' => $request->c_code,'userId'=>$request->userId, 'delflag'=>'0'
        
        
    );

 
//print_r($data1);
// DB::enableQueryLog();

        $FabricSummaryGRNMasterList = FabricSummaryGRNMasterModel::findOrFail($id);  
      $FabricSummaryGRNMasterList->fill($data1)->save();
        //  $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
  
        DB::table('fabric_summary_grn_detail')->where('fsg_code', $fsg_code)->delete();
 
       
    $item_code = $request->input('item_codes');
    if($request->allocate_qty != "")
    {
        $allocate_qtys=count($request->allocate_qty);
    }
        
    if(count($item_code)>0)
    { 
                for($x=0; $x<count($item_code); $x++)
                {
                          # code...
       
                    $data2[]=array(
                    'fsg_code' =>$fsg_code,
                    'fsg_date' => $request->fsg_date,
                    'po_code' => $request->po_code,
                    'challan_no'=>$request->challan_no,
                    'challan_date' =>$request->challan_date,
                    'invoice_no' =>$request->invoice_no,
                    'invoice_date'=>$request->invoice_date,
                    'item_code'=>$request->item_codes[$x], 
                  
                    'item_qty' => $request->item_qtys[$x],
                    'item_rate' => $request->item_rates[$x],
                    );
                   
                 }
             FabricSummaryGRNDetailModel::insert($data2);
                      
        }
        for($y=0;$y<$allocate_qtys; $y++) 
        {
            
               $data3 = array(
                    "po_code"=> $request->po_code,
                    "po_date"=> $request->fsg_date,
                    "tr_code"=> $fsg_code,
                    "tr_date"=> $request->fsg_date,
                    'bom_code'=> $request->stock_bom_code[$y],
                    'sales_order_no'=>$request->sales_order_no[$y],
                    'cat_id'=>$request->cat_id[$y],
                    'class_id'=>$request->class_id[$y],
                    "item_code"=> $request->item_code[$y],
                    'unit_id' => 0,
                    'qty' => $request->allocate_qty[$y],
                    "tr_type"=> 1,
                );
    
        StockAssociationForFabricModel::insert($data3);
        }    
            return redirect()->route('FabricSummaryGRN.index')->with('message', 'Update Record Succesfully');
    }

 
 
 
 
 public function GetPOColorList(Request $request)
{
     $po_code=base64_decode($request->po_code);
     $ColorList = DB::table('purchaseorder_detail')->select('purchaseorder_detail.color_id',
     'color_name')->join('color_master', 'color_master.color_id', '=', 'purchaseorder_detail.color_id', 'left outer')
    ->where('pur_code','=',$po_code)->DISTINCT()->get();
    if (!$request->po_code)
    {
        $html = '<option value="">--Color List--</option>';
        } else {
        $html = '';
       // $html = '<option value="">--Color List--</option>';
        
        foreach ($ColorList as $row) 
        {$html .= '<option value="'.$row->color_id.'">'.$row->color_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}
 
 
 
 
public function GetPOItemList(Request $request)
{
    $po_code=base64_decode($request->po_code);
     $ItemList = DB::table('purchaseorder_detail')->select('purchaseorder_detail.item_code', 'item_name')
    ->leftJoin('item_master', 'item_master.item_code', '=', 'purchaseorder_detail.item_code')
    ->where('pur_code','=',$po_code)->distinct()->get();
    
    
    if (!$request->po_code)
    {
        $html = '<option value="">--Item List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.$row->item_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}
 
 
 
 

public function PrintFabricBarcode(Request $request)
{
    $data='';
    $Colors=ColorModel::where('color_id','=',$request->color_id )->first(); 
    $color_name=$Colors->color_name;
    $Parts=PartModel::where('part_id','=',$request->part_id )->first(); 
    $part_name=$Parts->part_name;
    $QualityList = QualityModel::where('quality_code','=',$request->quality_code )->first(); 
    $quality_name=$QualityList->quality_name;
    $start=''; $end='';
    
    
$start= "<xpml><page quantity='0' pitch='40.0 mm'></xpml>"; 
$end="<xpml></page></xpml><xpml><end/></xpml>";
     	           
// $data=$data.'SIZE 79.8 mm, 40 mm
// GAP 3 mm, 0 mm
// DIRECTION 0,0
// REFERENCE 0,0
// OFFSET 0 mm
// SET PEEL OFF
// SET CUTTER OFF
// SET PARTIAL_CUTTER OFF
// SET TEAR ON
// CLS
// CODEPAGE 1252
// TEXT 583,284,"0",180,13,10,"Color:"
// TEXT 471,284,"0",180,13,10,"'.$color_name.'"
// TEXT 609,215,"0",180,13,10,"Use for:"
// TEXT 471,215,"0",180,13,10,"'.$part_name.'"
// TEXT 272,284,"0",180,13,10,"Width:"
// TEXT 155,284,"0",180,13,10,"'.$request->width.'"
// TEXT 269,215,"0",180,13,10,"Meter:"
// TEXT 156,215,"0",180,13,10,"'.$request->meter.'"
// TEXT 617,144,"0",180,13,10,"StyleNo:"
// TEXT 471,144,"0",180,13,10,"'.$request->style_no.'"
// TEXT 230,144,"0",180,13,10,"JC:"
// TEXT 159,144,"0",180,13,10,"'.$request->job_code.'"
// BARCODE 464,96,"39",40,0,180,3,8,"'.$request->track_code.'"
// TEXT 344,50,"ROMAN.TTF",180,1,10,"'.$request->track_code.'"
// PRINT 1,2
// ';     	           
    	 
    	 
  $data=$data.'I8,A
q640
O
JF
ZT
Q320,25
<xpml></page></xpml><xpml><page quantity="2" pitch="40.0 mm"></xpml>FK"SSFMT002"
FK"SSFMT002"
FS"SSFMT002"
A607,285,2,3,1,1,N,"Job:"
A533,285,2,3,1,1,N,"'.$request->job_code.'"
A314,285,2,3,1,1,N,"#"
A289,285,2,3,1,1,N,"'.$request->style_no.'"
A607,235,2,3,1,1,N,"CLR:"
A530,235,2,3,1,1,N,"'.$color_name.'"
A319,235,2,3,1,1,N,"W:"
A291,235,2,3,1,1,N,"'.$request->width.'"
A607,184,2,3,1,1,N,"For:"
A530,184,2,3,1,1,N,"'.$part_name.'"
A361,180,2,3,1,1,N,"Qlty:"
B490,92,2,3,3,8,51,N,"'.$request->track_code.'"
A369,35,2,3,1,1,N,"'.$request->track_code.'"
A185,235,2,3,1,1,N,"Mtr:'.$request->meter.'"
A585,135,2,3,1,1,N,"Kg:"
A529,135,2,3,1,1,N,"'.$request->kg.'"
A291,180,2,3,1,1,N,"'.$quality_name.'"
A291,151,2,3,1,1,N,"Laffer"
FE
N
FR"SSFMT002"
P2
';     	 
    	 
    	 $data=$start.$data.$end;
    	            
                    					 
                    $dir="barcode";
                    $pagename = 'data';
                    $newFileName = $dir."/".$pagename.".prn";
                    $newFileContent = $data;
                    if (file_put_contents($newFileName, $newFileContent) !== false) {
                       // echo "File created (" . basename($newFileName) . ")";
                        $result= array('result' => 'success');
                    } else {
                        //echo "Cannot create file (" . basename($newFileName) . ")";
                         $result= array('result' => 'failed');
                    }
                    
                    
                    // $printJob = Printing::newPrintTask()
                    // ->printer($printerId)
                    // ->file($newFileName)
                    // ->send();
                    
                    
                    return json_encode($result);
                    
                    
}







   public function getPo(Request $request)
    {
    

 $ItemList = DB::table('purchaseorder_detail')->select('purchaseorder_detail.item_code', 'item_name')
    ->leftJoin('item_master', 'item_master.item_code', '=', 'purchaseorder_detail.item_code')
    ->where('pur_code','=',$request->po_code)->distinct()->get();
    
    
    if (!$request->po_code)
    {
        $html = '<option value="">--Item List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.$row->item_name.'</option>';}
    }
      return response()->json(['html' => $html]);

    
    }
    
    public function getPoForFabric(Request $request)
    {
        $po_code= $request->po_code;
        $chk_code = $request->chk_code;
        
        $itemlist=DB::table('item_master')->where('item_master.cat_id','=','1')->where('item_master.delflag','0')->get();
        $unitlist=DB::table('unit_master')->where('unit_master.delflag','0')->get();
        $RackList=DB::table('rack_master')->where('rack_master.delflag','0')->get();
       
       
     // DB::enableQueryLog();
        $data=DB::select(DB::raw("SELECT purchase_order.sr_no, purchaseorder_detail.bom_code, purchaseorder_detail.pur_code,
         purchaseorder_detail.pur_date, purchaseorder_detail.Ac_code, 
         purchaseorder_detail.item_code,item_master.item_description, item_master.cat_id,item_master.class_id, purchaseorder_detail.hsn_code,
         purchaseorder_detail.unit_id,purchaseorder_detail.item_rate, purchaseorder_detail.item_qty,
         
         (select sum(meter) from fabric_checking_details where po_code='".$po_code."' and chk_code='".$chk_code."' and item_code=purchaseorder_detail.item_code) as totalQty,
         purchaseorder_detail.sales_order_no,inward_master.is_opening
         FROM   purchaseorder_detail
         inner join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
         LEFT join item_master on item_master.item_code=purchaseorder_detail.item_code
         LEFT join bom_master ON bom_master.sales_order_no = purchaseorder_detail.sales_order_no
         INNER JOIN inward_master ON inward_master.po_code = purchase_order.pur_code 
         where purchase_order.pur_code='".$po_code."' AND purchaseorder_detail.bom_type=1 
         and purchaseorder_detail.item_code in (select item_code from fabric_checking_details where po_code='".$po_code."' and chk_code='".$chk_code."' and item_code=purchaseorder_detail.item_code)
         GROUP BY purchaseorder_detail.bom_code, purchaseorder_detail.item_code"));
         
         if(count($data) == 0)
         { 
                $data = DB::SELECT("SELECT fabric_checking_details.sr_no,fabric_checking_details.po_code,fabric_checking_details.Ac_code,fabric_checking_details.item_code,item_master.item_description,item_master.cat_id,item_master.class_id,
                    item_master.hsn_code,0 as sales_order_no,0 as bom_code,
                    item_master.item_name,unit_master.unit_id,unit_master.unit_name, ifnull(sum(fabric_checking_details.meter),0) as totalQty,fabric_checking_details.item_rate,inward_master.is_opening
                    FROM fabric_checking_details 
                    INNER JOIN item_master ON item_master.item_code = fabric_checking_details.item_code  
                    INNER JOIN unit_master ON unit_master.unit_id = item_master.unit_id   
                    INNER JOIN fabric_checking_master ON fabric_checking_master.chk_code = fabric_checking_details.chk_code  
                    INNER JOIN inward_master ON inward_master.in_code = fabric_checking_master.in_code  
                    where fabric_checking_details.po_code='".$po_code."' and fabric_checking_details.chk_code='".$chk_code."' GROUP BY fabric_checking_details.item_code");  
         }
       //dd(DB::getQueryLog());  
        $html='';

        $html .='<div class="table-wrap" id="fabricInward">
                <div class="table-responsive">
                       <table id="footable_2" class="table table-bordered table-striped m-b-0  footable_2">
                <thead>
                <tr>
                    <th>SrNo</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Item Description</th>
                    <th>UOM</th>
                    <th>Qty</th>
                    <th>Add/Remove</th>
                </tr>
                </thead>
                <tbody>';
                $no=1;
                foreach ($data as $value) 
                {
                    
                   $html .='<tr>';
                    
                    $html .='
                    <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;" readOnly /></td>
                     
                      
                     
                    <td> <span onclick="openmodal('.$value->sr_no.','.$value->item_code.');" style="color:#556ee6; cursor: pointer;">'.$value->item_code.'</span></td>
                     
                     
                    <td> <select name="item_codes[]"  id="item_codes" style="width:300px; height:30px;" disabled >
                    <option value="">--Select Item--</option>';
                    
                    foreach($itemlist as  $row1)
                    {
                        
                        if($row1->item_code == $value->item_code)
                        {
                            $selected = "selected";
                        }
                        else
                        {
                            $selected = "";
                        }
                        
                        $html.='<option value="'.$row1->item_code.'" '.$selected.'>'.$row1->item_name.'</option>';
                    }
                     
                    $html.='</select></td> ';
                    $html.='<td><input type="text" value="'.$value->item_description.'" style="width:250px;height:30px;" readOnly/>';
                    $html .='<td> <select name="unit_ids[]"  id="unit_ids" style="width:100px; height:30px;" disabled >
                    <option value="">--Select Unit--</option>';
                    
                    foreach($unitlist as  $rowunit)
                    {
                        $html.='<option value="'.$rowunit->unit_id.'"';
                    
                        $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$rowunit->unit_name.'</option>';
                    }
                     
                    $html.='</select></td>';
                     
                    $html.='<td><input type="text" class="QTY"  name="item_qtys[]" onchange="SetQtyToBtn(this);" value="'.round($value->totalQty,2).'" id="item_qty" style="width:80px;height:30px;" readonly/>
                    <input type="hidden"  name="hsn_code[]" value="'.$value->hsn_code  .'" id="hsn_code" style="width:80px; height:30px;" readOnly/>
                    <input type="hidden"   name="item_rates[]" readOnly  value="'.round($value->item_rate,5).'" id="item_rates" style="width:80px;height:30px;" />
                    <input type="hidden" class="AMT"  name="amounts[]" readOnly  value="'.(round($value->totalQty,2)*round($value->item_rate,2)).'" id="amounts" style="width:80px;height:30px;" readOnly/>
                    </td> 
                    <td nowrap><button type="button" onclick=" mycalc(); " class="btn btn-warning pull-left Abutton">+</button> 
                    <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" >
                    <button type="button" name="allocate[]"  onclick="stockAllocateForFabric(this);" item_code="'.$value->item_code.'" isClick = "0" is_opening='.$value->is_opening.' qty="'.$value->totalQty.'" bom_code="'.$value->bom_code.'" cat_id="'.$value->cat_id.'" class_id="'.$value->class_id.'" class="btn btn-success pull-left">Allocate</button> 
                    </td>';
                    $html .='</tr>';
                    $no=$no+1;
                
                }
                
                   $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';
                   $html .='</table>
                   </div>
                </div>';
              //  exit;
        return response()->json(['html' => $html]);
    }
    
    public function stockAllocateForFabric(Request $request)
    {
        //echo '<pre>';print_R($_GET);exit;
        $bom_code = $request->bom_code;
        
        $item_code = $request->item_code;
        $exist_Item_qty = $request->item_qty;  
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        $item_name = $request->item_name;
        $po_type_id = $request->po_type_id;
        $is_opening = $request->is_opening;
        $total_avaliable_qty = 0;
        $bom_fabricData = "";
        $bomArray = explode(",", $bom_code);
        $qtyArr = [];
        $html = "";
        $totalQty= 0;
        $allocate_qty = 0;
        $bom_Total = 0;
          
      //DB::enableQueryLog();
        $bomData1 = DB::select("SELECT *,sum(item_qty) as item_qty  FROM bom_fabric_details WHERE bom_code IN ('".str_replace(",", "','", $bom_code)."') AND item_code =".$item_code." GROUP BY bom_code,item_code");
        $bomData2 = DB::select("SELECT *,sum(item_qty) as item_qty  FROM bom_trim_fabric_details WHERE bom_code IN ('".str_replace(",", "','", $bom_code)."') AND item_code =".$item_code." GROUP BY bom_code,item_code");
        
        if (!empty($bomData1))
        {
            $bomData = $bomData1;
        }
        else
        {
             $bomData = $bomData2;
        }
      
      //dd(DB::getQueryLog());
    
         if($is_opening == 1 || $po_type_id == 2)
        {
            $allocate_qty =  $exist_Item_qty;
            $itemlist=DB::table('item_master')->where('item_master.item_code','=',$item_code)->where('item_master.delflag','0')->get();
             $html .='<tr>
                    <td><input type="text" name="stock_bom_code[]" value="" class="form-control" style="width:100px;" readonly /></td>
                    <td><input type="text" name="sales_order_no[]" value="0" class="form-control" style="width:100px;" readonly/></td>
                    <td><input type="text" name="item_code[]" value="'.$item_code.'" class="form-control" style="width:100px;" readonly/></td>
                    <td nowrap><input type="text" name="item_name[]" value="'.$itemlist[0]->item_name.'" class="form-control" style="width:300px;" readonly/></td>
                    <td nowrap><input type="text" name="allocate_qty[]" value="'.round($allocate_qty,2).'" class="form-control allocate_qty" style="width:100px;" readonly />
                                <input type="hidden" name="cat_id[]" value="'.$cat_id.'" class="form-control" style="width:100px;" />
                                <input type="hidden" name="class_id[]" value="'.$class_id.'" class="form-control" style="width:100px;" />
                    </td>
                </tr>';
        }
        else
        {
            foreach($bomData as $bom)
            {
              
    
                $bom_Total1 = DB::select("SELECT sum(bom_qty) as totalQty FROM bom_fabric_details  
                     WHERE bom_code IN ('".str_replace(",", "','", $bom_code)."') AND item_code =".$item_code);
                //DB::enableQueryLog();
                $bom_Total2 = DB::select("SELECT sum(bom_qty) as totalQty FROM bom_trim_fabric_details  
                     WHERE bom_code IN ('".str_replace(",", "','", $bom_code)."') AND item_code =".$item_code);
                     
               //dd(DB::getQueryLog());
               // echo $bom_Total[0]->totalQty;exit;
               
                if ($bom_Total1[0]->totalQty > 0)
                {
                    $bom_Total2 = $bom_Total1[0]->totalQty;
                }
                else
                {
                     $bom_Total2 = $bom_Total2[0]->totalQty; 
                }
                
                $totalQty= $bom_Total2;
                //echo $totalQty;exit;
                $itemlist=DB::table('item_master')->where('item_master.item_code','=',$bom->item_code)->where('item_master.delflag','0')->get();
                $salesOrderData = DB::select("SELECT sales_order_no FROM bom_master WHERE bom_master.bom_code ='".$bom->bom_code."'");
               
                $bom_fabricData1 = DB::select("SELECT bom_code,item_qty,sales_order_no,item_code FROM bom_fabric_details 
                                WHERE bom_code = '".$bom->bom_code."'  AND item_code=".$bom->item_code);
                                
                                
                $bom_fabricData2 = DB::select("SELECT bom_code,item_qty,sales_order_no,item_code FROM bom_trim_fabric_details 
                                WHERE bom_code = '".$bom->bom_code."'  AND item_code=".$bom->item_code);
                      
                if (!empty($bom_fabricData1))
                {
                    $bom_fabricData = $bom_fabricData1;
                }
                else
                {
                     $bom_fabricData = $bom_fabricData2;
                }
        
                if(count($bom_fabricData) > 0)
                {
                    $sales_order_no = $salesOrderData[0]->sales_order_no;
                    $item_qty = $bom_fabricData[0]->item_qty;
                }
                else
                {
                    $sales_order_no='0';
                    
                    $item_qty = 0;
                }
                if($totalQty > 0)
                {
                    //$allocate_qty = ($item_qty/$exist_Item_qty);
                     $allocate_qty = (round($bom->bom_qty)/(round($totalQty)))  * $exist_Item_qty; 
                }
                elseif($totalQty == 0 && $exist_Item_qty!=0)
                {
                    $allocate_qty=$exist_Item_qty;
                }
                else
                {
                    $allocate_qty=0;
                }
                
                if($allocate_qty > 0)
                {
                    $html .='<tr>
                        <td><input type="text" name="stock_bom_code[]" value="'.$bom->bom_code.'" class="form-control" style="width:100px;" readonly /></td>
                        <td><input type="text" name="sales_order_no[]" value="'.$bom->sales_order_no.'" class="form-control" style="width:100px;" readonly/></td>
                        <td><input type="text" name="item_code[]" value="'.$bom->item_code.'" class="form-control" style="width:100px;" readonly/></td>
                        <td nowrap><input type="text" name="item_name[]" value="'.$itemlist[0]->item_name.'" class="form-control" style="width:100px;" readonly/></td>
                        <td nowrap>
                                <input type="text" name="allocate_qty[]" value="'.round($allocate_qty,2).'" class="form-control" style="width:100px;" readonly />
                                <input type="hidden" name="cat_id[]" value="'.$cat_id.'" class="form-control" style="width:100px;" />
                                <input type="hidden" name="class_id[]" value="'.$class_id.'" class="form-control" style="width:100px;" />
                        </td>
                    </tr>';
                }
                
            }

        }
        return response()->json(['html' => $html]);
    }
    

    public function getPoMasterDetail(Request $request)
    {


         $po_codee= $request->po_code;

    $data=DB::table('purchase_order')->where('pur_code','=',$po_codee)
   ->get(['purchase_order.*']);

 
  return $data;


    }


  public function getPODetails(Request $request)
    { 
        $po_code= $request->input('po_code');
        $MasterdataList = DB::select("select pur_code, purchase_order.Ac_code, ledger_master.ac_name, po_type_id from purchase_order
        inner join ledger_master on ledger_master.ac_code=purchase_order.Ac_code
        where purchase_order.po_status=1 and pur_code='". $po_code."'");
        return json_encode($MasterdataList);
    }


  public function getItemRateFromPO(Request $request)
    { 
        $po_code= $request->input('po_code');
        $item_code= $request->input('item_code');
        $Rate = DB::select("select  item_rate from    purchaseorder_detail
        where purchaseorder_detail.pur_code='". $po_code."' and item_code='".$item_code."'");
        return json_encode($Rate);
    }


public function FabricGRNData()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
        // DB::enableQueryLog();
        $FabricInwardDetails = FabricInwardDetailModel::
          leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'inward_details.Ac_code')
          ->leftJoin('item_master', 'item_master.item_code', '=', 'inward_details.item_code')
          ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
          ->leftJoin('part_master', 'part_master.part_id', '=', 'inward_details.part_id')
          ->leftJoin('cp_master', 'cp_master.cp_id', '=', 'inward_details.cp_id')
          ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'inward_details.rack_id')
          ->get(['inward_details.*', 'cp_master.cp_name','part_master.part_name','ledger_master.ac_name','item_master.dimension', 'item_master.item_name','item_master.color_name','item_master.item_description','quality_master.quality_name','rack_master.rack_name']);
     // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('FabricGRNData',compact('FabricInwardDetails'));
    }
    
    
    
    
    public function FabricStockData()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
        //DB::enableQueryLog();
        $FabricInwardDetails =DB::select("select inward_details.* ,inward_master.po_code as po_codes, inward_master.invoice_no,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
        cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
        item_master.item_name,item_master.color_name,item_master.item_description,
        quality_master.quality_name,rack_master.rack_name from inward_details
        left join inward_master on inward_master.in_code=inward_details.in_code
        left  join cp_master on cp_master.cp_id=inward_details.cp_id 
        left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
        left join item_master on item_master.item_code=inward_details.item_code 
        left join quality_master on quality_master.quality_code=item_master.quality_code 
        left join part_master on part_master.part_id=inward_details.part_id 
        left join rack_master on rack_master.rack_id=inward_details.rack_id");
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('FabricStockData',compact('FabricInwardDetails'));
    }



    public function FabricStockSummaryData()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
        //DB::enableQueryLog();
        $FabricInwardDetails =DB::select("select inward_details.item_code , item_master.item_name, item_master.color_name, item_master.item_description, sum(inward_details.meter) as meter, quality_master.quality_name, inward_details.item_rate,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.item_code = inward_details.item_code)  as out_meter ,
        cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
        item_master.item_name,item_master.color_name,item_master.item_description,
        quality_master.quality_name,rack_master.rack_name from inward_details
        left join inward_master on inward_master.in_code=inward_details.in_code
        left  join cp_master on cp_master.cp_id=inward_details.cp_id 
        left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
        left join item_master on item_master.item_code=inward_details.item_code 
        left join quality_master on quality_master.quality_code=item_master.quality_code 
        left join part_master on part_master.part_id=inward_details.part_id 
        left join rack_master on rack_master.rack_id=inward_details.rack_id
        group by inward_details.item_code
        
        ");
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('FabricSummaryStock',compact('FabricInwardDetails'));
    }






    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $id=base64_decode($id);
        // echo $id; exit;
        //$Code=DB::select('select fsg_code from fabric_summary_grn_master where sr_no="'.$id.'"');
       // echo $Code[0]->fsg_code;
       // exit;
        DB::table('fabric_summary_grn_master')->where('fsg_code', $id)->delete();
        DB::table('fabric_summary_grn_detail')->where('fsg_code', $id)->delete();
        $detail =StockAssociationForFabricModel::where('tr_code',$id)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
    public function GetPoCodeFromChk(Request $request)
    { 
        $chk_code = $request->input('chk_code'); 
        $html = "";
        $records =  DB::table('fabric_checking_master')->where('chk_code', $chk_code)->first(); 
        
        $html .="<option value='".$records->po_code."'>".$records->po_code."</option>";
        return json_encode($html);
    }
}
