<?php

namespace App\Http\Controllers;
use App\Models\FabricTransactionModel;
use App\Models\FabricInwardModel;
use App\Models\FabricInwardDetailModel;
use Illuminate\Http\Request;
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

 
class FabricInwardController extends Controller
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
        ->where('form_id', '36')
        ->first(); 
        
        
         //   DB::enableQueryLog();
         $FabricInwardList = FabricInwardModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
         ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
         ->where('inward_master.delflag','=', '0')
         ->get(['inward_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
     // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('FabricInwardMasterList', compact('FabricInwardList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no',PBarcode,CBarcode
        
        from counter_number where c_name ='C1' AND type='FABRIC_INWARD'");
         
         
        //  (SELECT max(substr(`track_code`,2,15))  FROM `inward_details` WHERE `track_code` like 'P%') as PBarcode,
        // (SELECT max(substr(`track_code`,2,15))  FROM `inward_details` WHERE `track_code` like 'I%') as CBarcode
         
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code','sales_order_no')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        $CPList =  DB::table('cp_master')->get();
        
        return view('FabricInwardMaster',compact('Ledger','RackMasterList', 'POList', 'PartList','FGList','CPList', 'counter_number','ItemList','POTypeList','gstlist','BOMLIST'));

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
             
                'in_code'=>'required',
                'in_date'=>'required',
                'cp_id'=>'required',
                'Ac_code'=>'required',
                'total_meter'=>'required',
                'total_kg'=>'required',
                'total_taga_qty'=>'required',
                'c_code'=>'required',
                 ]);
                 
    $sr_no = FabricInwardModel::max('sr_no');            
    $is_opening=isset($request->is_opening) ? 1 : 0;
                
    if($is_opening==1){$po_code='OSF'.($sr_no+1);}else{ $po_code= $request->input('po_code');}             
                
    $data1=array(

        'in_code'=>$request->in_code, 'in_date'=>$request->in_date,'invoice_date'=>$request->invoice_date,'cp_id'=>$request->cp_id, 
        'Ac_code'=>$request->Ac_code,'po_code'=>$po_code, 'invoice_no'=>$request->invoice_no,
        'po_type_id' =>$request->po_type_id, 'total_kg' => $request->total_kg, 'is_opening'=>$is_opening,
        'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,'total_amount'=>$request->total_amount,
         'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
        'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1',
        
        
    );
    
    FabricInwardModel::insert($data1);
 
  
    $item_code = $request->input('item_code');
    if(count($item_code)>0)
    {
        DB::select("update counter_number set tr_no=tr_no + 1, PBarcode='".$request->PBarcode."', CBarcode='".$request->CBarcode."'   where c_name ='C1' AND type='FABRIC_INWARD'"); 
    
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->where('type','=','FABRIC_INWARD' )->first();  
       
        $PBarcodes= $track_code->PBarcode;
        $CBarcodes= $track_code->CBarcode;
        
        for($x=0; $x<count($item_code); $x++) {
        # code...
        if($request->cp_id==1)
        {
                 if($request->track_code[$x]==''){ $PBarcodeFinal='P'.++$PBarcodes; }else{$PBarcodeFinal=$request->track_code[$x];}
                 
                 
                    $data2=array(
                    'in_code' =>$request->in_code,
                    'in_date' => $request->in_date,
                    'po_code'=>$po_code,
                    'cp_id' =>$request->cp_id,
                    'Ac_code' =>$request->Ac_code,
                    'item_code'=>$request->item_code[$x], 
                    'part_id' =>$request->part_id[$x],
                    'roll_no' => $request->id[$x],
                    'meter' => $request->meter[$x],
                    'gram_per_meter' => $request->gram_per_meter[$x],
                    'kg' => $request->kg[$x],
                    'item_rate' => $request->item_rates[$x],
                    'amount' => $request->amounts[$x],
                    'shade_id' =>'1',
                    'track_code' => $PBarcodeFinal,
                     'is_opening'=>$is_opening,
                    'usedflag' => '0',
                    );
                   
                       $data3=array(
                                    'tr_code' =>$request->in_code,
                                    'tr_date' => $request->in_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'po_code'=>$request->po_code,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>'1',
                                    'track_code' => $PBarcodeFinal,
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '1',
                                    'rack_id' => 0,
                                    'is_opening'=>$is_opening,
                                    'userId'=>$request->userId,
                                );
                    
                     
            }
            else
            {

                 if($request->track_code[$x]==''){ $CBarcodeFinal='I'.++$CBarcodes; }else{$CBarcodeFinal=$request->track_code[$x];}
                $data2=array(
                
                    'in_code' =>$request->in_code,
                    'in_date' => $request->in_date,
                    'po_code'=>$request->po_code,
                    'cp_id' =>$request->cp_id,
                    'Ac_code' =>$request->Ac_code,
                    'item_code'=>$request->item_code[$x], 
                    'part_id' =>$request->part_id[$x],
                    'roll_no' => $request->id[$x],
                    'meter' => $request->meter[$x],
                    'gram_per_meter' => $request->gram_per_meter[$x],
                    'kg' => $request->kg[$x],
                    'item_rate' => $request->item_rates[$x],
                    'amount' => $request->amounts[$x],
                    'shade_id' =>'1',
                    'track_code' => $CBarcodeFinal,
                    'usedflag' => '0',
                    'is_opening'=>$is_opening,
                    );
                    
                    
                   $data3=array(
                                    'tr_code' =>$request->in_code,
                                    'tr_date' => $request->in_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'po_code'=>$request->po_code,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>'1',
                                    'track_code' =>$CBarcodeFinal,
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '1',
                                    'rack_id' => 0,
                                    'userId'=>$request->userId,
                                    'is_opening'=>$is_opening,
                                );
                    
            }
            
            FabricInwardDetailModel::insert($data2);
            FabricTransactionModel::insert($data3);
            }
    
     
    
    }
 
   return redirect()->route('FabricInward.index')->with('message', ' Record Created Succesfully');

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
      
        from counter_number where c_name ='C1' AND type='FABRIC_INWARD'");
        
        //   (SELECT max(substr(`track_code`,2,15))  FROM `inward_details` WHERE `track_code` like 'P%') as PBarcode,
        // (SELECT max(substr(`track_code`,2,15))  FROM `inward_details` WHERE `track_code` like 'I%') as CBarcode
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        //  DB::enableQueryLog();
        $FabricInwardMasterList = FabricInwardModel::where('sr_no',$id)->first();
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        $FabricInwardDetails = FabricInwardDetailModel::where('inward_details.in_code','=', $FabricInwardMasterList->in_code)->get(['inward_details.*']);
  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('FabricInwardMasterEdit',compact('FabricInwardMasterList','POList',  'RackMasterList', 'PartList', 'Ledger','CPList','FGList', 'FabricInwardDetails','counter_number','ItemList','POTypeList','gstlist','BOMLIST'));
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
             
            'in_code'=>'required',
            'in_date'=>'required',
            'cp_id'=>'required',
            'Ac_code'=>'required',
            'total_meter'=>'required',
            'total_kg'=>'required',
            'total_taga_qty'=>'required',
             
            'c_code'=>'required',
             ]);


 $is_opening=isset($request->is_opening) ? 1 : 0;
 
   
    //  DB::enableQueryLog();
 // $sr_no = FabricInwardModel::select('sr_no')->where('in_code','=',$in_code)->get();
// $query = DB::getQueryLog();
// $query = end($query);
// dd($query);
    
    
       if($is_opening==1){$po_code='OSF'.($id);}else{ $po_code= $request->input('po_code');} 
 
 
 $in_code=base64_decode($request->in_code);
 
 $data1=array(

        'in_code'=>$in_code, 'in_date'=>$request->in_date,'cp_id'=>$request->cp_id, 
        'Ac_code'=>$request->Ac_code,'po_code'=>$po_code, 'invoice_no'=>$request->invoice_no,'invoice_date'=>$request->invoice_date,
        'po_type_id' =>$request->po_type_id, 'total_kg' => $request->total_kg, 'total_amount'=>$request->total_amount,
        'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty, 'is_opening'=>$is_opening,
         'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
        'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','created_at'=>$request->created_at,
        
        
    );




//print_r($data1);
// DB::enableQueryLog();

        $FabricInwardMasterList = FabricInwardModel::findOrFail($id);  
   
        $FabricInwardMasterList->fill($data1)->save();
        //  $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
 
    

        DB::table('inward_details')->where('in_code', $in_code)->delete();
        DB::table('fabric_transaction')->where('tr_code', $in_code)->delete();
       
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->where('type','=','FABRIC_INWARD' )->first(); 
        $CBarcodes = $track_code->CBarcode;
        $PBarcodes = $track_code->PBarcode;




  
        $item_code = $request->input('item_code');
    if(count($item_code)>0)
    {
        
          DB::select("update counter_number set tr_no=tr_no + 1, PBarcode='".$request->PBarcode."', CBarcode='".$request->CBarcode."'   where c_name ='C1' AND type='FABRIC_INWARD'"); 
        
        
        
                for($x=0; $x<count($item_code); $x++) 
                {
   
                    if($request->cp_id==1)
                    {

                        if($request->track_code[$x]==''){ $PBarcodeFinal='P'.++$PBarcodes; }else{$PBarcodeFinal=$request->track_code[$x];}
                        $data2=array(
                        'in_code' =>$in_code,
                        'in_date' => $request->in_date,
                         'po_code'=>$po_code,
                        'cp_id' =>$request->cp_id,
                        'Ac_code' =>$request->Ac_code,
                        'item_code'=>$request->item_code[$x], 
                        'part_id' =>$request->part_id[$x],
                        'roll_no' => $request->id[$x],
                        'meter' => $request->meter[$x],
                        'gram_per_meter' => $request->gram_per_meter[$x],
                        'kg' => $request->kg[$x],
                        'item_rate' => $request->item_rates[$x],
                        'amount' => $request->amounts[$x],
                        'shade_id' =>'1',
                        'track_code' => $PBarcodeFinal,
                        'usedflag' => '0',
                    'is_opening'=>$is_opening,
                        );
                        
                           $data3=array(
                                    'tr_code' =>$in_code,
                                    'tr_date' => $request->in_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'po_code'=>$request->po_code,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>'1',
                                    'track_code' => $PBarcodeFinal,
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '1',
                                      'rack_id' =>0,
                                    'userId'=>$request->userId,
                                    'is_opening'=>$is_opening,
                                );
                        
                       }
                    else
                    {
                        if($request->track_code[$x]==''){ $CBarcodeFinal='I'.++$CBarcodes; }else{$CBarcodeFinal=$request->track_code[$x];}
                        $data2=array(
                        
                            'in_code' =>$in_code,
                            'in_date' => $request->in_date,
                             'po_code'=>$request->po_code,
                            'cp_id' =>$request->cp_id,
                            'Ac_code' =>$request->Ac_code,
                            'item_code'=>$request->item_code[$x], 
                            'part_id' =>$request->part_id[$x],
                            'roll_no' => $request->id[$x],
                            'meter' => $request->meter[$x],
                            'gram_per_meter' => $request->gram_per_meter[$x],
                            'kg' => $request->kg[$x],
                            'item_rate' => $request->item_rates[$x],
                            'amount' => $request->amounts[$x],
                            'shade_id' =>'1',
                             'is_opening'=>$is_opening,  
                            'track_code' => $CBarcodeFinal,
                            'usedflag' => '0',
                            );
                            
                               $data3=array(
                                    'tr_code' =>$in_code,
                                    'tr_date' => $request->in_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'po_code'=>$request->po_code,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>'1',
                                    'track_code' => $CBarcodeFinal,
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '1',
                                     'rack_id' => 0,
                                     'is_opening'=>$is_opening,
                                    'userId'=>$request->userId,
                                );
                            
                     }
            
                    FabricInwardDetailModel::insert($data2);
                      FabricTransactionModel::insert($data3);
                }
      
      
     
     
     
      //  $query = DB::getQueryLog();
            //  $query = end($query);
            //  dd($query);
                
                
                
        }
            return redirect()->route('FabricInward.index')->with('message', 'Update Record Succesfully');
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
        $FabricInwardDetails =DB::select("select inward_details.* ,inward_master.po_code as po_codes,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
        cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $id=base64_decode($id);
        
        
        DB::table('inward_master')->where('in_code', $id)->delete();
        DB::table('inward_details')->where('in_code', $id)->delete();
        $detail =FabricTransactionModel::where('tr_code',$id)->delete();
        
         Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
}
