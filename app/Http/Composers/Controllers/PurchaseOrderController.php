<?php
namespace App\Http\Controllers;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\CategoryModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;

class PurchaseOrderController extends Controller
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
        ->where('form_id', '31')
        ->first();
 
        $InwardFabric = DB::select("SELECT ifnull((select count(sr_no) from purchase_order),0)  as noOfPO,  ifnull((select sum(Net_Amount) from purchase_order),0) as poTotal,
        ifnull((select sum(Net_Amount) from purchase_order where po_status=2),0) as receivedTotal 
        ");
 
        $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
        ->leftJoin('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
        ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
        ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'purchase_order.po_type_id')
        ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
        ->where('purchase_order.delflag','=', '0')
        ->where('purchase_order.approveFlag','=', '0')
        ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','po_type_master.po_type_name']);

        return view('PurchaseOrderList', compact('data','chekform', 'InwardFabric'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='Other_Purchase' and c_name='C1'"));
        $JobStatusList = JobStatusModel::where('job_status_master.delflag','=', '0')->get();
        $firmlist = DB::table('firm_master')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->where('ledger_master.ac_code','>', '39')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $itemlist = DB::table('item_master')->get();
        $unitlist = DB::table('unit_master')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code','sales_order_no')->get();
        return view('PurchaseOrder',compact('firmlist','ledgerlist','gstlist','itemlist','ClassList','code','unitlist','POTypeList','JobStatusList','BOMLIST'));     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $firm_id=$request->input('firm_id');      

        //DB::enableQueryLog();
          $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
          ->where('c_name','=','C1')
          ->where('type','=','PurchaseOrder')
           ->where('firm_id','=',$firm_id)
          ->first();
          //DB::enableQueryLog();
        /*$query = DB::getQueryLog();
        $query = end($query);
        dd($query);*/

        

if($request->input('bom_codes')!=''){$bom_code=implode(",",$request->input('bom_codes'));}else{ $bom_code='';}


$bom_type=implode(",",$request->input('bom_type'));
$class_ids=implode(",",$request->input('class_id'));



if($bom_type=='1')
        {
        
         $TrNo=$codefetch->code.'/21-22/'.'F'.$codefetch->tr_no;
        } else{
            
           $TrNo=$codefetch->code.'/21-22/'.'T'.$codefetch->tr_no;
            
        }




$data = array('pur_code'=>$TrNo,
"bom_code"=>$bom_code,
"bom_type"=> $bom_type,
"class_id"=> $class_ids,
"pur_date"=> $request->input('pur_date'),
"Ac_code"=> $request->input('Ac_code'),
"po_type_id"=> $request->input('po_type_id'),
"tax_type_id"=> $request->input('tax_type_id'),
"total_qty"=> $request->input('total_qty'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"totFreightAmt"=> $request->input('totFreightAmt'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $codefetch->c_code,
"gstNo"=> $request->input('gstNo'),
"address"=> $request->input('address'),
"deliveryAddress"=> $request->input('deliveryAddress'),
"supplierRef"=> $request->input('supplierRef'),
"terms_and_conditions"=> $request->input('terms_and_conditions'),
"delivery_date"=>$request->input('delivery_date'),
"po_status"=>$request->input('po_status'),
"userId"=> $request->input('userId'),
"reason_disapproval"=> "0",
"approveFlag"=> 0,
"delflag"=>0
);

// Insert
$value = PurchaseOrderModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}



$cnt = $request->input('cnt');

$itemcodes=count($request->item_codes);

//print_r($request->bom_type);

if($itemcodes>0)
{
$item_code='';
for($x=0;$x<$itemcodes; $x++) {
# code...

$data2[]=array(

'pur_code' =>$TrNo,
'pur_date' => $request->input('pur_date'),
"bom_code"=>$bom_code,
"bom_type"=> $bom_type,
"class_id"=> $class_ids,
'Ac_code' => $request->input('Ac_code'),
'sales_order_no' => $request->sales_order_no[$x],
'item_code' => $request->item_codes[$x],
'hsn_code' => $request->hsn_code[$x],
'unit_id' => $request->unit_id[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'disc_per' => $request->disc_pers[$x],
'disc_amount' => $request->disc_amounts[$x],
'pur_cgst' => $request->pur_cgsts[$x],
'camt' => $request->camts[$x],
'pur_sgst' => $request->pur_sgsts[$x],
'samt' => $request->samts[$x],
'pur_igst' => $request->pur_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'freight_hsn' => $request->freight_hsn[$x],
'freight_amt' => $request->freight_amt[$x],
'total_amount' => $request->total_amounts[$x],

 'conQty'=> $request->conQtys[$x], 
 'unitIdM'=> $request->unitIdMs[$x],
 'priUnitd'=> $request->priUnitds[$x],
 'SecConQty'=> $request->SecConQtys[$x],
 'secUnitId'=> $request->secUnitIds[$x],
 'poQty'=> $request->poQtys[$x],
 'poUnitId'=> $request->poUnitIds[$x],
 'rateM'=> $request->rateMs[$x],
 'totalQty'=> $request->totalQtys[$x],
 
'firm_id' => $request->firm_id);

$item_code=$item_code.$request->item_codes[$x].',';

}
PurchaseOrderDetailModel::insert($data2);
}


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='PurchaseOrder' AND firm_id='".$request->input('firm_id')."'");  
$item_code=rtrim($item_code,",");
$updateBOMItem = DB::select("update bom_fabric_details set usedFlag= 1   where   bom_code='".$bom_code."' and item_code in (".$item_code.")");  


  return redirect()->route('PurchaseOrder.index')->with('message', 'Add Record Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '31')
        ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
        ->where('purchase_order.delflag','=', '0')
         ->where('purchase_order.approveFlag','=', '1')
        ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }
    
    
    
     public function Disapprovedshow()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '31')
        ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
        ->where('purchase_order.delflag','=', '0')
         ->where('purchase_order.approveFlag','=', '2')
        ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $JobStatusList = JobStatusModel::where('job_status_master.delflag','=', '0')->get();
        $firmlist = DB::table('firm_master')->get();
        $ledgerlist = DB::table('ledger_master')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $itemlist = DB::table('item_master')->get();
        $unitlist = DB::table('unit_master')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->get();
        $CatList = CategoryModel::where('delflag','=', '0')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $purchasefetch = PurchaseOrderModel::find($id);
        $BOMLIST = DB::table('bom_master')->select('bom_code','sales_order_no')->get();
    
    
     // DB::enableQueryLog();
  
     
    
    
        $detailpurchase = PurchaseOrderDetailModel::join('item_master','item_master.item_code', '=', 'purchaseorder_detail.item_code')
        ->where('pur_code','=', $purchasefetch->pur_code)->get(['purchaseorder_detail.*','item_master.item_name','item_master.cat_id','item_master.item_image_path']);
       
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    
       
        return view('PurchaseOrderEdit',compact('purchasefetch','CatList','firmlist','ledgerlist','gstlist','ClassList','itemlist','detailpurchase','unitlist','POTypeList','JobStatusList','BOMLIST'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code)
    {
        
   if($request->input('bom_codes')!=''){$bom_code=implode(",",$request->input('bom_codes'));}else{ $bom_code='';}  
    $bom_type=implode(",",$request->input('bom_type'));
    $class_ids=implode(",",$request->input('class_id'));
    
$data = array('pur_code'=>$request->input('pur_code'),
"bom_code"=> $bom_code,
"bom_type"=> $bom_type,
 
"class_id"=> $class_ids,
"pur_date"=> $request->input('pur_date'),
"Ac_code"=> $request->input('Ac_code'),
"po_type_id"=> $request->input('po_type_id'),
"tax_type_id"=> $request->input('tax_type_id'),
"total_qty"=> $request->input('total_qty'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"totFreightAmt"=> $request->input('totFreightAmt'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code'),
"gstNo"=> $request->input('gstNo'),
"address"=> $request->input('address'),
"deliveryAddress"=> $request->input('deliveryAddress'),
"supplierRef"=> $request->input('supplierRef'),
"userId"=> $request->input('userId'),
"approveFlag"=> $request->input('approveFlag'),
"terms_and_conditions"=> $request->input('terms_and_conditions'),
"delivery_date"=>$request->input('delivery_date'),
"po_status"=>$request->input('po_status'),
"delflag"=>0,
"reason_disapproval"=> $request->input('reason_disapproval'),
);

// Insert
$purchase = PurchaseOrderModel::findOrFail($pur_code);  

$purchase->fill($data)->save();



   DB::table('purchaseorder_detail')->where('pur_code', $request->input('pur_code'))->delete();
   DB::table('ledgerentry_details')->where('tr_no', $request->input('pur_code'))->delete();
   DB::table('ledgerentry')->where('TrNo', $request->input('pur_code'))->delete();
   DB::table('transactions')->where('TrNo', $request->input('pur_code'))->delete();


$cnt = $request->input('cnt');

$itemcodes=count($request->item_codes);

if($cnt>0)
{
$item_code='';
for($x=0;$x<$itemcodes;$x++) {
# code...

// $data2[]=array(

// 'pur_code' =>$request->input('pur_code'),
// 'pur_date' => $request->input('pur_date'),
// "bom_code"=>$bom_code,
// "bom_type"=> $bom_type,
// "class_id"=> $class_ids,
// 'Ac_code' => $request->input('Ac_code'),
// 'sales_order_no' => $request->sales_order_no[$x],
// 'item_code' => $request->input('item_codes')[$x], 
// 'hsn_code' => $request->hsn_code[$x],
// 'unit_id' => $request->unit_id[$x],
// 'item_qty' => $request->item_qtys[$x],
// 'item_rate' => $request->item_rates[$x],
// 'disc_per' => $request->disc_pers[$x],
// 'disc_amount' => $request->disc_amounts[$x],
// 'pur_cgst' => $request->pur_cgsts[$x],
// 'camt' => $request->camts[$x],
// 'pur_sgst' => $request->pur_sgsts[$x],
// 'samt' => $request->samts[$x],
// 'pur_igst' => $request->pur_igsts[$x],
// 'iamt' => $request->iamts[$x],
// 'amount' => $request->amounts[$x],
// 'freight_hsn' => $request->freight_hsn[$x],
// 'freight_amt' => $request->freight_amt[$x],
// 'total_amount' => $request->total_amounts[$x],

// 'conQty'=> $request->conQtys[$x], 
//  'unitIdM'=> $request->unitIdMs[$x],
//  'priUnitd'=> $request->priUnitds[$x],
//  'SecConQty'=> $request->SecConQtys[$x],
//  'secUnitId'=> $request->secUnitIds[$x],
//  'poQty'=> $request->poQtys[$x],
//  'poUnitId'=> $request->poUnitIds[$x],
//  'rateM'=> $request->rateMs[$x],
//  'totalQty'=> $request->totalQtys[$x],

// 'firm_id' => $request->firm_id);
 
 
$data2[]=array(

'pur_code' =>$request->input('pur_code'),
'pur_date' => $request->input('pur_date'),
"bom_code"=>$bom_code,
"bom_type"=> $bom_type,
"class_id"=> $class_ids,
'Ac_code' => $request->input('Ac_code'),
'sales_order_no' => $request->sales_order_no[$x],
'item_code' => $request->item_codes[$x],
'hsn_code' => $request->hsn_code[$x],
'unit_id' => $request->unit_id[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'disc_per' => $request->disc_pers[$x],
'disc_amount' => $request->disc_amounts[$x],
'pur_cgst' => $request->pur_cgsts[$x],
'camt' => $request->camts[$x],
'pur_sgst' => $request->pur_sgsts[$x],
'samt' => $request->samts[$x],
'pur_igst' => $request->pur_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'freight_hsn' => $request->freight_hsn[$x],
'freight_amt' => $request->freight_amt[$x],
'total_amount' => $request->total_amounts[$x],

 'conQty'=> $request->conQtys[$x], 
 'unitIdM'=> $request->unitIdMs[$x],
 'priUnitd'=> $request->priUnitds[$x],
 'SecConQty'=> $request->SecConQtys[$x],
 'secUnitId'=> $request->secUnitIds[$x],
 'poQty'=> $request->poQtys[$x],
 'poUnitId'=> $request->poUnitIds[$x],
 'rateM'=> $request->rateMs[$x],
 'totalQty'=> $request->totalQtys[$x],
 
'firm_id' => $request->firm_id);

 
 
$item_code=$item_code.$request->item_codes[$x].',';
  
}

PurchaseOrderDetailModel::insert($data2);
$item_code=rtrim($item_code,",");
$updateBOMItem = DB::select("update bom_fabric_details set usedFlag= 1   where   bom_code='".$bom_code."' and item_code in (".$item_code.")");  
 
}

  return redirect()->route('PurchaseOrder.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($pur_code)
    {
        
          $pur_code=base64_decode($pur_code);
          $item_code='';
          $master1 =PurchaseOrderDetailModel::where('pur_code',$pur_code)->get();
          foreach($master1 as $row)
          {
              $item_code=$item_code.$row->item_code.',';
          }
          
          $item_code=rtrim($item_code,",");
          
          if($item_code!='')
          {
            $updateBOMItem = DB::select("update bom_fabric_details set usedFlag=0   where   bom_code='".$master1[0]->bom_code."' and item_code in (".$item_code.")");  
          }
            $master =PurchaseOrderModel::where('pur_code',$pur_code)->delete();      
            $detail =PurchaseOrderDetailModel::where('pur_code',$pur_code)->delete();
            $detail =FabricTransactionModel::where('tr_code',$pur_code)->delete();
            Session::flash('delete', 'Deleted record successfully'); 
          
        
    }
    
     public function closestatus($id)
    {
              
          
        
    }
    
     public function GetPartyDetails(Request $request)
    {
        
            $ac_code= $request->input('ac_code');
            $PartyRecords = DB::select("select state_id ,gst_no from ledger_master where ac_code='".$ac_code."' and delflag=0");
            return json_encode($PartyRecords);
         
    }
    
    
    public function GetClassLists(Request $request)
{
     $bom_types=explode(',',$request->cat_id);
     
    $ClassList = DB::table('classification_master')->select('classification_master.class_id', 'class_name')
    ->whereIN('cat_id',$bom_types)->get();
    
    if (!$bom_types)
    {
        $html = '<option value="">--Classification--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Classification--</option>';
        
        foreach ($ClassList as $row) 
        {$html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}
    
    
    
    
     public function getBoMDetail(Request $request)
    {
         $html=''; $no=1;
        $bom_code=''; 
      // echo $request->bom_code;
        $bom_codeids=explode(",",$request->bom_code);
        $class_ids=$request->class_ids;
     //  echo $class_ids;
         
         $class_id_each=explode(",",$class_ids);
         
         
        $itemlist=DB::table('item_master')->where('delflag','=','0')->get();
        $unitlist=DB::table('unit_master')->where('delflag','=','0')->get();
        
foreach($class_id_each as $rowClass)
{
    //echo $rowClass;
    // DB::enableQueryLog();
    $cat_id=DB::table('classification_master')->select('cat_id')->where('class_id','=',$rowClass)->get();
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
   //echo $cat_id[0]->cat_id;  
}   
   
   if($cat_id[0]->cat_id=='1')
     {
         
        $table="bom_fabric_details"; 
       // $bom_codeids=explode(",",$request->bom_code);
            foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        $bom_code=rtrim($bom_code,",");
        // echo  $bom_code; exit;
        //  DB::enableQueryLog();
 
        // $data=DB::table($table)
        // ->leftJoin('item_master', 'item_master.item_code', '=', 'purchase_order.item_code')
        // ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
        // ->whereIn('item_master.class_id', $class_ids)->whereIn('bom_code',$bom_codeids)->where('usedFlag',0)->get();
        
        
        $data= DB::select('select * from bom_fabric_details
          inner join item_master on item_master.item_code=bom_fabric_details.item_code
          inner join classification_master on classification_master.class_id=item_master.class_id
          where usedFlag=0 and item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.')'); 
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        
        
         foreach ($data as $value) 
     {
           if($cat_id[0]->cat_id=='1')
           {
                $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
                (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
                ) as Stock"));
           }
        //   elseif($cat_id[0]->cat_id=='2' || $cat_id[0]->cat_id=='3')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
        //         (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
          
              
                        if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        $html .='<tr id="bomdis">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                    
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;" required readOnly/> </td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" required disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td>
                         <a href="https://ken.korbofx.org/images/'.$value->item_image_path.'" target="_blank"><img  src="https://ken.korbofx.org/thumbnail/'.$value->item_image_path.'"  id="item_image" name="item_image[]" class="imgs"> </a>
                         </td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;" required readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" required disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty){$max=$value->moq;}else{$max=$value->bom_qty;}
                        $html.='
                        
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.round($value->bom_qty).'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max).'"  value="" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="freight_hsn[]" readOnly class="" id="freight_hsn" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"  name="freight_amt[]" readOnly class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                        <input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                      ';
                        $html .='</tr>';
                        $no=$no+1;
    }
        
        
        
        
        
        //**************** For Trim Fabric Data From Table *************************
        
        
          $data= DB::select('select * from bom_trim_fabric_details
          inner join item_master on item_master.item_code=bom_trim_fabric_details.item_code
          inner join classification_master on classification_master.class_id=item_master.class_id
          where usedFlag=0 and item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.')'); 
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        
        
         foreach ($data as $value) 
     {
           if($cat_id[0]->cat_id=='1')
           {
                $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
                (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
                ) as Stock"));
           }
        //   elseif($cat_id[0]->cat_id=='2' || $cat_id[0]->cat_id=='3')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
        //         (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
          
              
                        if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        $html .='<tr id="bomdis">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                    
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;" required readOnly/> </td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" required disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td>
                         <a href="https://ken.korbofx.org/images/'.$value->item_image_path.'" target="_blank"><img  src="https://ken.korbofx.org/thumbnail/'.$value->item_image_path.'"  id="item_image" name="item_image[]" class="imgs"> </a>
                         </td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;" required readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" required disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty){$max=$value->moq;}else{$max=$value->bom_qty;}
                        $html.='
                        
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.round($value->bom_qty).'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max).'"  value="" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="freight_hsn[]" readOnly class="" id="freight_hsn" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"  name="freight_amt[]" readOnly class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                        <input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                      ';
                        $html .='</tr>';
                        $no=$no+1;
    }
        
        
        
        
        
        
        
        
        
        
        
        
        //*********************** End of Trim Fabric Data  ************************
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
     } 
     else if($cat_id[0]->cat_id=='2')
     { 
        $table="bom_sewing_trims_details"; 
         foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        $bom_code=rtrim($bom_code,",");
       // echo $bom_code;
        //   DB::enableQueryLog();
          $data= DB::select('select * from bom_sewing_trims_details
          inner join item_master on item_master.item_code=bom_sewing_trims_details.item_code
          inner join classification_master on classification_master.class_id=item_master.class_id
          where item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.')
          and bom_sewing_trims_details.item_code not in (select item_code from purchaseorder_detail where purchaseorder_detail.bom_code in  ('.$bom_code.'))'); 
        
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);   
         
          foreach ($data as $value) 
     {
        //   if($cat_id[0]->cat_id=='1')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
        //         (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
        //   else
           if($cat_id[0]->cat_id=='2')
           {
                $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                ) as Stock"));
           }
          
              
                        // if($request->tax_type_id==1)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                        //     from item_master where item_code='".$value->item_code."'"));
                        //     $Camt=($value->total_amount * ($value->cgst_per/100));
                        //     $Samt=($value->total_amount * ($value->sgst_per/100));
                        //     $Iamt=0;                 
                        //     $TAmount=$value->total_amount + $Camt+ $Samt;
                        //     $igst_per=0;
                        //     // print_r($value->item_code);
                        // } 
                        // elseif($request->tax_type_id==2)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                        //     from item_master where item_code='$value->item_code'"));
                        //     $Iamt=($value->total_amount * ($value->igst_per/100));
                        //     $Camt=0;
                        //     $Samt=0;
                        //     $TAmount=$value->total_amount + $Iamt ;
                        // } 
                      
                      
                      if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        $html .='<tr id="bomdis">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                       <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;" required readOnly/> </td>
                       
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" required disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td>
                         <a href="https://ken.korbofx.org/images/'.$value->item_image_path.'" target="_blank"><img  src="https://ken.korbofx.org/thumbnail/'.$value->item_image_path.'"  id="item_image" name="item_image[]" class="imgs"> </a>
                         </td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;" required readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" required  >
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty){$max=$value->moq;}else{$max=$value->bom_qty;}
                        $html.='
                        
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.round($value->bom_qty).'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max).'"  value="" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="freight_hsn[]" readOnly class="" id="freight_hsn" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"  name="freight_amt[]" readOnly class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                       <input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                        ';
                        $html .='</tr>';
                        $no=$no+1;
    }
             
     } 
     else if($cat_id[0]->cat_id=='3')
     { 
        $table="bom_packing_trims_details"; 
       // $data=DB::table($table)->whereIn('bom_code',$bom_codeids)->get();
        
        foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        
        $bom_code=rtrim($bom_code,",");
        $data= DB::select('select * from bom_packing_trims_details
        inner join item_master on item_master.item_code=bom_packing_trims_details.item_code
        inner join classification_master on classification_master.class_id=item_master.class_id
        where item_master.class_id in('.$class_ids.') and
        bom_code in ('.$bom_code.')
        and bom_packing_trims_details.item_code not in (select item_code from purchaseorder_detail where purchaseorder_detail.bom_code in  ('.$bom_code.'))'); 
         
          foreach ($data as $value) 
     {
        //   if($cat_id[0]->cat_id=='1')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
        //         (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
        //   else
           
           if( $cat_id[0]->cat_id=='3')
           {
                $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                ) as Stock"));
           }
          
              
                        // if($request->tax_type_id==1)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                        //     from item_master where item_code='".$value->item_code."'"));
                        //     $Camt=($value->total_amount * ($value->cgst_per/100));
                        //     $Samt=($value->total_amount * ($value->sgst_per/100));
                        //     $Iamt=0;                 
                        //     $TAmount=$value->total_amount + $Camt+ $Samt;
                        //     $igst_per=0;
                        //     // print_r($value->item_code);
                        // } 
                        // elseif($request->tax_type_id==2)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                        //     from item_master where item_code='$value->item_code'"));
                        //     $Iamt=($value->total_amount * ($value->igst_per/100));
                        //     $Camt=0;
                        //     $Samt=0;
                        //     $TAmount=$value->total_amount + $Iamt ;
                        // } 
                         if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        $html .='<tr id="bomdis">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                       <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;" required readOnly/> </td>
                       
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" required disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td>
                         <a href="https://ken.korbofx.org/images/'.$value->item_image_path.'" target="_blank"><img  src="https://ken.korbofx.org/thumbnail/'.$value->item_image_path.'"  id="item_image" name="item_image[]" class="imgs"> </a>
                         </td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;" required readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" required disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty){$max=$value->moq;}else{$max=$value->bom_qty;}
                        $html.='
                        
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.round($value->bom_qty).'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max).'"  value="" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="freight_hsn[]" readOnly class="" id="freight_hsn" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"  name="freight_amt[]" readOnly class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                         <input type="hidden" step="any"  name="conQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                        ';
                        $html .='</tr>';
                        $no=$no+1;
    }
     }
 
     
    
    
    return response()->json(['html' => $html]);
    }
    
    
}
