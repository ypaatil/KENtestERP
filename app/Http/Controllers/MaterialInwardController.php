<?php

namespace App\Http\Controllers;

use App\Models\MaterialInwardMasterModel;
use App\Models\MaterialInwardDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\RackModel;
use App\Models\LocationModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\SparePurchaseOrderModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use App\Models\StockAssociationModel;
use Illuminate\Support\Facades\DB;
use Session;
use DataTables;
use Queue;
use DateTime;
date_default_timezone_set("Asia/Kolkata");

setlocale(LC_MONETARY, 'en_IN'); 

class MaterialInwardController extends Controller
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
            ->where('form_id', '100')
            ->first();
            
            
            $data = materialInwardMasterModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'materialInwardMaster.Ac_code')
                ->leftJoin('spare_purchase_order', 'spare_purchase_order.pur_code', '=', 'materialInwardMaster.po_code')
                ->leftJoin('location_master', 'location_master.loc_id', '=', 'materialInwardMaster.location_id')
                ->leftJoin('usermaster', 'usermaster.userId', '=', 'materialInwardMaster.userId')
                ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'materialInwardMaster.po_type_id')
                ->leftJoin(DB::raw('(SELECT po_code, tr_code, COUNT(*) as stock_count FROM stock_association GROUP BY po_code, tr_code) as sa'), function ($join) {
                    $join->on('sa.po_code', '=', 'materialInwardMaster.po_code')
                         ->on('sa.tr_code', '=', 'materialInwardMaster.materiralInwardCode');
                })
                ->where('materialInwardMaster.delflag', '=', '0')
                ->where('materialInwardMaster.invoice_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
                ->orderBy('materialInwardMaster.materiralInwardCode', 'DESC')
                ->get([
                    'materialInwardMaster.*',
                    'usermaster.username',
                    'ledger_master.ac_name',
                    'po_type_master.po_type_name',
                    'location_master.location',
                    DB::raw('COALESCE(sa.stock_count, 0) as stock_count')
                ]);


        return view('MaterialInwardList', compact('data','chekform'));
    }


    public function MaterialInwardShowAll()
    { 
        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '100')
            ->first();
            
       //DB::enableQueryLog();     
        $data = MaterialInwardMasterModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'materialInwardMaster.Ac_code')
                ->leftJoin('spare_purchase_order', 'spare_purchase_order.pur_code', '=', 'materialInwardMaster.po_code') 
                ->leftJoin('usermaster', 'usermaster.userId', '=', 'materialInwardMaster.userId')
                ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'materialInwardMaster.po_type_id')
                ->where('materialInwardMaster.delflag', '=', '0') 
                ->get([
                    'materialInwardMaster.*',
                    'usermaster.username',
                    'ledger_master.ac_name',
                    'po_type_master.po_type_name' 
        ]);
        //dd(DB::getQueryLog());
        return view('MaterialInwardList', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='MaterialMaster' and c_name='C1'"));
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $JobStatusList = JobStatusModel::where('job_status_master.delflag','=', '0')->get();
        $firmlist = DB::table('firm_master')->get();
         
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->where('spare_item_master.cat_id','!=','1')->where('spare_item_master.class_id','=','148')->get();
        $unitlist = DB::table('unit_master')->get();
     
        $POList = SparePurchaseOrderModel::where('delflag','=', '0')->get();
        return view('MaterialInward',compact('firmlist','RackList','ledgerlist','gstlist','itemlist','code','unitlist','POTypeList','JobStatusList' ,'POList','LocationList'));     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo '<pre>';print_r($_POST);exit;
        $firm_id=$request->input('firm_id');      
        $is_opening=isset($request->is_opening) ? 1 : 0;
        
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
          ->where('c_name','=','C1')
          ->where('type','=','MaterialInward')
           ->where('firm_id','=',1)
          ->first();
        
        $TrNo=$codefetch->code.''.$codefetch->tr_no;
        //echo $TrNo;exit; 
        $sr_no = materialInwardMasterModel::max('sr_no');
        
        if($is_opening == 1)
        {
            $po_code = 'OS/SP'.$codefetch->tr_no;
        }
        else
        {
            $po_code = $request->po_code;
        }
        
        $data = array('materiralInwardCode'=>$TrNo,
        "po_code"=> $po_code,  
        "materiralInwardDate"=> $request->input('materiralInwardDate'),
        "invoice_no"=> $request->input('invoice_no'),
        "invoice_date"=> $request->input('invoice_date'),
        "Ac_code"=> $request->input('Ac_code'),
        "po_type_id"=> $request->input('po_type_id'),
        "totalqty"=> $request->input('totalqty'),
        'total_amount' => $request->total_amount,
        'remark' => $request->remark,
        "delflag"=>0,
        'is_opening'=>$is_opening,
        'location_id'=>$request->location_id,
        "userId"=> $request->input('userId')
        );
        $itemcodes = 0; 
         
        $value = materialInwardMasterModel::insert($data);
        
        $update = DB::select("update counter_number set tr_no= tr_no + 1 where c_name ='C1' AND type='MaterialInward' AND firm_id=1");  
         
        $itemcodes=count($request->spare_item_codes);
       
        for($x=0;$x<$itemcodes; $x++) 
        {
            $data2=array(
                'materiralInwardCode' =>$TrNo,
                'materiralInwardDate' => $request->input('materiralInwardDate'),
                'Ac_code' => $request->input('Ac_code'),
                "po_code"=>  $po_code,   
                'spare_item_code' => isset($request->spare_item_codes[$x]) ? $request->spare_item_codes[$x] : 0,
                'hsn_code' => 0,
                'unit_id' => isset($request->unit_ids[$x]) ? $request->unit_ids[$x] : 0,
                'item_qty' => isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0,
                'item_rate' => isset($request->item_rates[$x]) ? $request->item_rates[$x] : 0,
                'amount' => isset($request->amounts[$x]) ? $request->amounts[$x] : 0, 
                'is_opening'=>$is_opening,
                'location_id'=>isset($request->location_id) ? $request->location_id : 0,
            );
            
            MaterialInwardDetailModel::insert($data2); 
            
        } 
            
            
        $upload_attachment = $request->upload_attachment;
        if(!empty($upload_attachment)) 
        {
            foreach($upload_attachment as $index => $attachmentName) 
            {
                if ($request->hasFile('upload_attachment.' . $index)) {
                    $attachment = $request->file('upload_attachment')[$index];
                    $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                    $location = public_path('uploads/MaterialInward/');
                    if (file_exists('uploads/MaterialInward/'.$fileName))
                    {
                         $url = "uploads/MaterialInward/".$fileName;
                         unlink($url);
                    }
                    $attachment->move($location,$fileName); 
                    DB::table('material_inward_attachment')->insert([ 
                        "materiralInwardCode"=>$TrNo, 
                        "materiralInwardDate"=>$request->materiralInwardDate,
                        "attachment_name"=>$request->attachment_name[$index],
                        "upload_attachment"=>$fileName
                    ]);
                }
            }
        } 
        
        return redirect()->route('MaterialInward.index')->with('message', 'Saved Record Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SparePurchaseOrderModel  $SparePurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '9')
        ->first();

        $data = SparePurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'spare_purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'spare_purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'spare_purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'spare_purchase_order.firm_id')    
        ->where('spare_purchase_order.delflag','=', '0')
         ->where('spare_purchase_order.approveFlag','=', '1')
        ->get(['spare_purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }
    
    
    
     public function Disapprovedshow()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '9')
        ->first();

        $data = SparePurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'spare_purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'spare_purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'spare_purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'spare_purchase_order.firm_id')    
        ->where('spare_purchase_order.delflag','=', '0')
         ->where('spare_purchase_order.approveFlag','=', '2')
        ->get(['spare_purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SparePurchaseOrderModel  $SparePurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $JobStatusList = JobStatusModel::where('job_status_master.delflag','=', '0')->get();
        $firmlist = DB::table('firm_master')->where('firm_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->where('spare_item_master.cat_id','!=','1')->where('spare_item_master.class_id','=','148')->get();
        $unitlist = DB::table('unit_master')->where('unit_master.delflag','=', '0')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        //DB::enableQueryLog();
        $materialInward = materialInwardMasterModel::find($id);
       // dd(DB::getQueryLog());
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $po_sr_no=DB::select("select sr_no from spare_purchase_order where pur_code='".$materialInward->po_code."'");
        $POList = SparePurchaseOrderModel::where('delflag','=', '0')->get(); 
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        // DB::enableQueryLog();
        $detailpurchase = MaterialInwardDetailModel::join('spare_item_master','spare_item_master.spare_item_code', '=', 'materialInwardDetail.spare_item_code')
        ->leftJoin('spare_purchase_order', 'spare_purchase_order.pur_code', '=', 'materialInwardDetail.po_code')
        ->leftJoin('classification_master', 'classification_master.class_id', '=', 'spare_item_master.class_id')
        ->where('materiralInwardCode','=', $materialInward->materiralInwardCode)
        ->get(['materialInwardDetail.*','spare_item_master.item_name','spare_item_master.item_description','spare_item_master.cat_id','spare_item_master.class_id','classification_master.class_name']);
        
        $MaterialInwardAttachmentList = DB::table('material_inward_attachment')->where('materiralInwardCode','=',   $materialInward->materiralInwardCode)->get();
        //dd(DB::getQueryLog()); 
        return view('MaterialInwardEdit',compact('POList','RackList','materialInward','po_sr_no','firmlist','ledgerlist','gstlist','itemlist','detailpurchase','unitlist','POTypeList','JobStatusList','LocationList','MaterialInwardAttachmentList'));
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SparePurchaseOrderModel  $SparePurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $materiralInwardCode)
    {  
       $is_opening=isset($request->is_opening) ? 1 : 0;  
       
       $data = array('materiralInwardCode'=>$request->materiralInwardCode,
        "po_code"=> $request->po_code,   
        "materiralInwardDate"=> $request->materiralInwardDate,
        "invoice_no"=> $request->invoice_no,
        "invoice_date"=> $request->invoice_date,
        "Ac_code"=> $request->Ac_code,
        "po_type_id"=> $request->po_type_id,
        "totalqty"=> $request->totalqty,
        'total_amount' => $request->total_amount,
        'remark' => $request->remark,
        "delflag"=>0,
        'is_opening'=>$is_opening,
        'location_id'=>$request->location_id,
        "userId"=> $request->userId
        );
 
 
        $materialInward = MaterialInwardMasterModel::findOrFail($materiralInwardCode);  
        
        $materialInward->fill($data)->save();
        
        DB::table('materialInwardDetail')->where('materiralInwardCode', $request->materiralInwardCode)->delete();
        $itemcodes=count($request->spare_item_codes);
        for($x=0;$x<$itemcodes;$x++) 
        {
            $data2=array( 
                'materiralInwardCode' =>$request->materiralInwardCode,
                'materiralInwardDate' => $request->materiralInwardDate,
                'Ac_code' => $request->Ac_code,
                "po_code"=> $request->po_code,  
                'spare_item_code' => isset($request->spare_item_codes[$x]) ? $request->spare_item_codes[$x] : 0,
                'hsn_code' => isset($request->hsn_codes[$x]) ? $request->hsn_codes[$x] : 0, 
                'unit_id' => isset($request->unit_ids[$x]) ? $request->unit_ids[$x] : 0,
                'item_qty' => isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0,
                'item_rate' => isset($request->item_rates[$x]) ? $request->item_rates[$x] : 0,
                'amount' => isset($request->amounts[$x]) ? $request->amounts[$x] : 0, 
                'is_opening'=>$is_opening,
                'location_id'=>isset($request->location_id) ? $request->location_id : 0,
             );
        
            MaterialInwardDetailModel::insert($data2);  
        }   
  
        $upload_attachment = $request->file('upload_attachment'); 
       
        if (!empty($upload_attachment)) 
        {
            foreach ($upload_attachment as $index => $attachment) {
                if ($attachment && $attachment->isValid()) {
                    $fileName = time() . '_' . $attachment->getClientOriginalName();
                    $location = public_path('uploads/MaterialInward/');
                    
                    // Log file name and location
                    \Log::info("Processing file: $fileName to $location");
        
                    // Move the new file to the specified location
                    $attachment->move($location, $fileName);
                    \Log::info("Moved file to: $location$fileName");
        
                    // Insert the file details into the database
                   DB::table('material_inward_attachment')->insert([ 
                        "materiralInwardCode"=>$request->materiralInwardCode, 
                        "materiralInwardDate"=>$request->materiralInwardDate,
                        "attachment_name"=>$request->attachment_name[$index],
                        "upload_attachment"=>$fileName
                    ]);
        
                    \Log::info("Inserted file details into the database: $fileName");
                } else {
                    \Log::warning("File at index $index is not valid or missing.");
                }
            }
        } else {
            \Log::warning("No files found in the request.");
        }
        
        return redirect()->route('MaterialInward.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SparePurchaseOrderModel  $SparePurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($materiralInwardCode)
    {
        
        $materiralInwardCode=base64_decode($materiralInwardCode);
        
        MaterialInwardMasterModel::where('materiralInwardCode',$materiralInwardCode)->delete();
        MaterialInwardDetailModel::where('materiralInwardCode',$materiralInwardCode)->delete();
     
        Session::flash('delete', 'Deleted record successfully'); 
          
        
    }
 
    public function DeleteMaterialInwardAttachment(Request $request)
    {
        $attachment = $request->upload_attachment;
    
        if (empty($attachment)) {
            return response()->json(['error' => 'Invalid file name'], 400);
        }
    
        DB::table('material_inward_attachment')
            ->where('materiralInwardCode', '=', $request->materiralInwardCode)
            ->where('upload_attachment', '=', $attachment)
            ->delete();
    
        $file_path = public_path('uploads/MaterialInward/' . $attachment);
    
        if (is_file($file_path) && file_exists($file_path)) {
            unlink($file_path);
        }
    
        return response()->json(['success' => true], 200);
    }

    
    public function GetPartyDetails(Request $request)
    { 
            $ac_code= $request->input('ac_code');
            $PartyRecords = DB::select("select state_id ,gst_no from ledger_master where ac_code='".$ac_code."' and delflag=0");
            return json_encode($PartyRecords); 
    }
    
    
    
    public function getBoMDetail(Request $request)
    {
    
  

         $itemlist=DB::table('spare_item_master')
           ->get();
        
            $unitlist=DB::table('unit_master')
           ->get();
           
               
             
        
        
        
             if($request->type==1)
             {
                 
              $table="bom_fabric_details"; 
        
              
             } 
             else if($request->type==2)
             {
                 
            $table="bom_sewing_Material_details"; 
                 
             } else if($request->type==3)
             {
                 
                  $table="bom_packing_Material_details"; 
                 
        
             }
        
        //DB::enableQueryLog();
        
          $bom_codeids=explode(',',$request->bom_code);
        
          $data=DB::table($table)
          ->whereIn('bom_code',$bom_codeids)
          ->get();
          
           //dd(DB::getQueryLog());
         
        
           $html='';
        
        $no=1;
        
             foreach ($data as $value) {
                  
        
          
        
        if($request->tax_type_id==1)
        {
            
        $datagst = DB::select(DB::raw("SELECT spare_item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
        from spare_item_master where spare_item_code='$value->spare_item_code'"));
        
        
                         $Camt=($value->total_amount * ($datagst[0]->cgst_per/100));
                         
                          $Samt=($value->total_amount * ($datagst[0]->sgst_per/100));
                          
                          $Iamt=0;                 
                      
                          $TAmount=$value->total_amount + $Camt+ $Samt + 0;
                          
                          $igst_per=0;
                          
                          
                        
        
        } else  if($request->tax_type_id==2)
        {
        
        $datagst = DB::select(DB::raw("SELECT spare_item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
        from spare_item_master where spare_item_code='$value->spare_item_code'"));
        
        
            $Iamt=($value->total_amount * ($datagst[0]->igst_per/100));
            
            $Camt=0;
            $Samt=0;
            
          $TAmount=$value->total_amount + $Iamt + 0;
        
        } 
           else if($request->tax_type_id==3)
        {
            
        $datagst = DB::select(DB::raw("SELECT spare_item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
        from spare_item_master where spare_item_code='$value->spare_item_code'"));
        
        }
                
            
           $html .='<tr id="bomdis">';
            
        $html .='
        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
         
        <td> <select name="spare_item_codes[]"  id="spare_item_code" style="width:100px;" required>
        <option value="">--Select Item--</option>';
        
        foreach($itemlist as  $row1)
        {
            $html.='<option value="'.$row1->spare_item_code.'"';
        
            $row1->spare_item_code == $value->spare_item_code ? $html.='selected="selected"' : ''; 
        
        
            $html.='>'.$row1->item_name.'</option>';
        }
         
        $html.='</select></td> 
        
        
        
        <td>
         <img  src="https://ken.korbofx.org/thumbnail/'.$datagst[0]->item_image_path.'"  id="item_image" name="item_image[]" class="imgs">
         
        <input type="hidden"  name="hsn_code[]" value="'.$datagst[0]->hsn_code.'" id="hsn_code" style="width:80px;" required/> </td>';
        
        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;" required>
        <option value="">--Select Unit--</option>';
        
        foreach($unitlist as  $rowunit)
        {
            $html.='<option value="'.$rowunit->unit_id.'"';
        
            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
        
        
            $html.='>'.$rowunit->unit_name.'</option>';
        }
         
        $html.='</select></td>';
        
         
        $html.='
        <td><input type="text"   name="item_qtys[]"   value="'.$value->bom_qty.'" id="item_qty" style="width:80px;" required/>
        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
        </td>
        <td><input type="text"   name="item_rates[]"  value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px;" required/></td>
        <td><input type="text"   name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px;" required/></td>
        <td><input type="text"   name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px;" required/></td>
        <td><input type="text"   name="pur_cgsts[]"  value="'.$datagst[0]->cgst_per.'" class="pur_cgsts"  id="pur_cgst" style="width:80px;" required/></td>
        <td><input type="text"   name="camts[]"  value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px;" required/></td>
        <td><input type="text"   name="pur_sgsts[]"  value="'.$datagst[0]->sgst_per.'" class=""  id="pur_sgst" style="width:80px;" required/></td>
        <td><input type="text"   name="samts[]"  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px;" required/></td>
        <td><input type="text"   name="pur_igsts[]"  value="'.$datagst[0]->igst_per.'" class=""  id="pur_igst" style="width:80px;" required/></td>
        <td><input type="text"   name="iamts[]"  value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px;" required/></td>
        <td><input type="text"   name="amounts[]"  value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px;" required/></td>
        <td><input type="text" name="freight_hsn[]" class="" id="freight_hsn" value="0" style="width:80px;"></td>
        
        <td><input type="text" name="freight_amt[]" class="FREIGHT" id="freight_amt" value="0" style="width:80px;"></td>
        
        
        <td><input type="text"   name="total_amounts[]"  class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px;" required/></td>
        
        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
        ';
          
            $html .='</tr>';
            $no=$no+1;
        
        }
        return response()->json(['html' => $html]);
    
    
    }
    
 
    
    
    public function getPoForMaterial(Request $request)
    {
        $po_code= base64_decode($request->po_code);
        $itemlist=DB::table('spare_item_master')->where('spare_item_master.cat_id','!=','1')->where('spare_item_master.delflag','0')->get();
        $unitlist=DB::table('unit_master')->where('unit_master.delflag','0')->get();
        $RackList=DB::table('rack_master')->where('rack_master.delflag','0')->get();
        //DB::enableQueryLog();
        $data=DB::select(DB::raw("SELECT classification_master.class_name,spare_purchase_order.sr_no, spare_purchase_order_detail.bom_code, spare_purchase_order_detail.pur_code, spare_purchase_order_detail.pur_date, spare_purchase_order_detail.Ac_code, 
         spare_purchase_order_detail.spare_item_code,spare_item_master.item_description, spare_item_master.cat_id,spare_item_master.class_id, spare_purchase_order_detail.hsn_code,
         spare_purchase_order_detail.unit_id,spare_purchase_order_detail.item_rate, sum(spare_purchase_order_detail.item_qty)  as totalQty,spare_purchase_order_detail.sales_order_no   FROM   spare_purchase_order_detail
         inner join spare_purchase_order on spare_purchase_order.pur_code=spare_purchase_order_detail.pur_code
         LEFT join spare_item_master on spare_item_master.spare_item_code=spare_purchase_order_detail.spare_item_code
         LEFT join bom_master ON bom_master.sales_order_no = spare_purchase_order_detail.sales_order_no
         LEFT join classification_master ON classification_master.class_id = spare_item_master.class_id
         where spare_purchase_order.pur_code='".$po_code."' GROUP BY spare_purchase_order_detail.spare_item_code"));
        //dd(DB::getQueryLog());     
         
        $html='';

        $html .='<div class="table-wrap" id="MaterialInward">
                <div class="table-responsive">
                       <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                <thead>
                <tr>
                    <th>SrNo</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Classification</th>
                    <th>UOM</th>
                    <th>To Be Received</th>
                    <th>Qty</th>
                    <th>Item Rate</th>
                    <th>Amount</th>
                    <th>Rack</th>
                    <th>Add/Remove</th>
                </tr>
                </thead>
                <tbody>';
                $no=1;
                foreach ($data as $value) 
                {
                    
                    $InwardMaterial = DB::select("SELECT   
                        MaterialInwardDetail.`spare_item_code`, unit_master.unit_name, spare_item_master.item_name, spare_item_master.item_description, class_name,
                        (select sum(item_qty) as po_item_qty from spare_purchase_order_detail where spare_purchase_order_detail.pur_code='".$value->pur_code."') as po_item_qty,
                        sum(MaterialInwardDetail.`item_qty`) as item_qty , MaterialInwardDetail.item_rate, MaterialInwardDetail.unit_id
                        FROM `MaterialInwardDetail` 
                        inner join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode 
                        inner join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
                        inner join unit_master on unit_master.unit_id=MaterialInwardDetail.unit_id
                        inner join classification_master on classification_master.class_id=spare_item_master.class_id
                        where materialInwardMaster.po_code='".$value->pur_code."' and MaterialInwardDetail.spare_item_code='".$value->spare_item_code."'
                        group by materialInwardMaster.po_code, MaterialInwardDetail.spare_item_code");
                
                   $toBeReceived = (isset($InwardMaterial[0]->po_item_qty) ?  $InwardMaterial[0]->po_item_qty : 0) - (isset($InwardMaterial[0]->item_qty) ?  $InwardMaterial[0]->item_qty : 0);
                   
                   $html .='<tr>';
                    
                    $html .='
                    <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
                     
                     
                      
                     <td> <span onclick="openmodal('.$value->sr_no.','.$value->spare_item_code.');" style="color:#556ee6; cursor: pointer;">'.$value->spare_item_code.'</span></td>
                    <td> <select name="spare_item_codes[]"  id="spare_item_codes" style="width:300px; height:30px;" required disabled >
                    <option value="">--Select Item--</option>';
                    
                    foreach($itemlist as  $row1)
                    {
                        $html.='<option value="'.$row1->spare_item_code.'"';
                    
                        $row1->spare_item_code == $value->spare_item_code ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$row1->item_name.'</option>';
                    }
                     
                    $html.='</select></td> ';
                     $html.='<td><input type="text" value="'.$value->class_name.'" style="width:250px;height:30px;" readOnly required/>';
                    $html .='<td> <select name="unit_ids[]"  id="unit_ids" style="width:80px; height:30px;" required disabled >
                    <option value="">--Select Unit--</option>';
                    
                    foreach($unitlist as  $rowunit)
                    {
                        $html.='<option value="'.$rowunit->unit_id.'"';
                    
                        $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$rowunit->unit_name.'</option>';
                    }
                     
                    $html.='</select></td>';
                    $html.='<td><input type="text" class="toBeReceived"  name="toBeReceived[]" value="'.round($toBeReceived,2).'" id="toBeReceived" style="width:80px;height:30px;" readOnly/>
                            </td>';
                    $html.='<td><input type="text" class="QTY"  name="item_qtys[]" onchange="SetQtyToBtn(this);" value="'.round($value->totalQty,2).'" id="item_qty" style="width:80px;height:30px;" required/>
                    <input type="hidden"  name="hsn_code[]" value="'.$value->hsn_code  .'" id="hsn_code" style="width:80px; height:30px;" readOnly required/>
                    </td>';
                    $html.='<td><input type="text"   name="item_rates[]" readOnly  value="'.round($value->item_rate,5).'" id="item_rates" style="width:80px;height:30px;" required/></td>';
                    
                    $html.='<td><input type="text" class="AMT"  name="amounts[]" readOnly  value="'.(round($value->totalQty,2)*round($value->item_rate,2)).'" id="amounts" style="width:80px;height:30px;" required/></td>';
                    
                    
                    
                    $html .='<td> <select name="rack_ids[]"  id="rack_ids" style="width:100px; height:30px;" required  >
                    <option value="">--Select Rack--</option>';
                    
                    foreach($RackList as  $rowrack)
                    {
                        $html.='<option value="'.$rowrack->rack_id.'"';
                     
                        $html.='>'.$rowrack->rack_name.'</option>';
                    }
                     
                    $html.='</select></td>
                    
                    
                    <td nowrap><button type="button" onclick=" mycalc(); " class="btn btn-warning pull-left Abutton">+</button> 
                    <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" >
                    <button type="button" name="allocate[]"  onclick="stockAllocate(this);" spare_item_code="'.$value->spare_item_code.'" isClick = "0" qty="'.$value->totalQty.'" bom_code="'.$value->bom_code.'" cat_id="'.$value->cat_id.'" class_id="'.$value->class_id.'" class="btn btn-success pull-left">Allocate</button> 
              
                    </td>
                    ';
                  
                    $html .='</tr>';
                    $no=$no+1;
                
                }
                
                   $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';
                   $html .='</table>
                   </div>
                </div>';
        return response()->json(['html' => $html]);
    }
    
    public function stockAllocate(Request $request)
    {
     
        $bom_code = $request->bom_code;
        
        $spare_item_code = $request->spare_item_code;
        $exist_Item_qty = $request->item_qty;
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        $item_name = $request->item_name;
        $is_opening = $request->is_opening; 
        $po_type_id = $request->po_type_id; 
        $total_avaliable_qty = 0;
        $bom_sewingData = "";
        $bomArray = explode(",", $bom_code);
        $qtyArr = [];
        $html = "";
        $totalQty= 0;
        $allocate_qty = 0;
        $bom_Total = 0;
          
        if($cat_id == 2)
        { 
           // DB::enableQueryLog();
            $bomData = DB::select("SELECT * FROM bom_sewing_Material_details  WHERE bom_code IN ('".str_replace(",", "','", $bom_code)."') AND spare_item_code =".$spare_item_code);
           //dd(DB::getQueryLog());
        }
        else if($cat_id == 3)
        { 
            $bomData = DB::select("SELECT * FROM bom_packing_Material_details  WHERE bom_code IN ('".str_replace(",", "','", $bom_code)."') AND spare_item_code =".$spare_item_code);
        }
        if($is_opening == 1 || $po_type_id == 2)
        {
            $allocate_qty =  $exist_Item_qty;
            $itemlist=DB::table('spare_item_master')->where('spare_item_master.spare_item_code','=',$spare_item_code)->where('spare_item_master.delflag','0')->get();
             $html .='<tr>
                    <td><input type="text" name="stock_bom_code[]" value="" class="form-control" style="width:100px;" readonly /></td>
                    <td><input type="text" name="sales_order_no[]" value="0" class="form-control" style="width:100px;" readonly/></td>
                    <td><input type="text" name="spare_item_code[]" value="'.$spare_item_code.'" class="form-control" style="width:100px;" readonly/></td>
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
                if($cat_id == 2)
                {
                    //DB::enableQueryLog();
                    $bom_Total = DB::select("SELECT sum(bom_qty) as totalQty FROM bom_sewing_Material_details  WHERE bom_code IN  ('".str_replace(",", "','", $bom_code)."') AND spare_item_code =".$spare_item_code);  
                    //dd(DB::getQueryLog());
                }
                else if($cat_id == 3)
                {
                    $bom_Total = DB::select("SELECT sum(bom_qty) as totalQty FROM bom_packing_Material_details  WHERE bom_code IN  ('".str_replace(",", "','", $bom_code)."') AND spare_item_code =".$spare_item_code); 
                }
                
                if($bom_Total != "") 
                {
                    $totalQty = $bom_Total[0]->totalQty;
                }
                else
                {
                    $totalQty = 0;
                }
                $itemlist=DB::table('spare_item_master')->where('spare_item_master.spare_item_code','=',$bom->spare_item_code)->where('spare_item_master.delflag','0')->get();
               
                $salesOrderData = DB::select("SELECT sales_order_no FROM bom_master WHERE bom_master.bom_code ='".$bom->bom_code."'");
                //dd(DB::getQueryLog());
              
                if(count($salesOrderData) > 0)
                {
                    $sales_order_no = $salesOrderData[0]->sales_order_no;
                   
                    // $bom_sewingData = DB::select("SELECT item_qty FROM bom_sewing_Material_details WHERE bom_code = '".$bom->bom_code."' AND  sales_order_no = '".$sales_order_no."' AND spare_item_code=".$spare_item_code);
                                
                    // //    DB::enableQueryLog();           
                    // $bom_packingData = DB::select("SELECT item_qty FROM bom_packing_Material_details WHERE bom_code = '".$bom->bom_code."' AND  sales_order_no = '".$sales_order_no."' AND spare_item_code=".$spare_item_code);
                    // // dd(DB::getQueryLog());
                }
                else
                {
                    $sales_order_no = "0";
                    // $bom_sewingData = [];
                    // $bom_packingData = [];
                }
                // //DB::enableQueryLog();
    
                // if($cat_id == 2)
                // {
                //     if(count($bom_sewingData) > 0)
                //     {
                //         $item_qty = $bom_sewingData[0]->item_qty;
                //     }
                //     else
                //     {
                //         $item_qty = 0;
                //     }
                // }
                // else if($cat_id == 3)
                // {
                //     if(count($bom_packingData) > 0)
                //     {
                //          $item_qty = $bom_packingData[0]->item_qty; 
                //     }
                //     else
                //     {
                //         $item_qty = 0;
                //     }
                // }  
                // else
                // {
                //     $item_qty = 0;
                // }
                
                // if($totalQty > 0 && $bom->bom_qty > 0)
                // {
                    //$allocate_qty = $bom->bom_qty;
                   // $allocate_qty = (($bom->bom_qty/($totalQty + ($totalQty * (3/100)))) * 100) * $exist_Item_qty;
                   //  $allocate_qty = (($bom->bom_qty/(round($totalQty) + (round($totalQty) * ($bom->wastage/100))))) * $exist_Item_qty;
                     $allocate_qty = (round($bom->bom_qty)/(round($totalQty)))  * $exist_Item_qty;
                // }
                // else
                // {
                //     $allocate_qty = 0;
                // }
                
                if($allocate_qty > 0)
                {
                    $html .='<tr>
                                <td><input type="text" name="stock_bom_code[]" value="'.$bom->bom_code.'" class="form-control" style="width:100px;" readonly /></td>
                                <td><input type="text" name="sales_order_no[]" value="'.$sales_order_no.'" class="form-control" style="width:100px;" readonly/></td>
                                <td><input type="text" name="spare_item_code[]" value="'.$spare_item_code.'" class="form-control" style="width:100px;" readonly/></td>
                                <td nowrap><input type="text" name="item_name[]" value="'.$itemlist[0]->item_name.'" class="form-control" style="width:300px;" readonly/></td>
                                <td nowrap><input type="text" name="allocate_qty[]" value="'.round($allocate_qty,2).'" class="form-control" style="width:100px;" readonly />
                                            <input type="hidden" name="cat_id[]" value="'.$cat_id.'" class="form-control" style="width:100px;" />
                                            <input type="hidden" name="class_id[]" value="'.$class_id.'" class="form-control" style="width:100px;" />
                                </td>
                            </tr>';
                }
                 $allocate_qty = 0;
            }
        }

   
        return response()->json(['html' => $html]);
    }
    
    public function getPoMasterDetailMaterial(Request $request)
    {


         $po_codee= base64_decode($request->po_code);

    $data=DB::table('spare_purchase_order')->where('pur_code','=',$po_codee)
   ->get(['spare_purchase_order.*']);

 
  return $data;


    }
    
    // public function MaterialGRNData()
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();
        
        
    //     $ItemList = ItemModel::where('spare_item_master.delflag','=', '0')->where('spare_item_master.cat_id','=', '1')->get();
    //     $POList = SparePurchaseOrderModel::where('spare_purchase_order.po_status','=', '1')->where('spare_purchase_order.bom_type','=', '1')->get();
    //     $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
    //     $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
    //     //DB::enableQueryLog();
    //     $MaterialInwardDetails = MaterialInwardDetailModel::
    //       leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'MaterialInwardDetail.Ac_code')
    //       ->leftJoin('spare_item_master', 'spare_item_master.spare_item_code', '=', 'MaterialInwardDetail.spare_item_code')
    //       ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'MaterialInwardDetail.rack_id')
    //       ->leftJoin('materialInwardMaster', 'materialInwardMaster.materiralInwardCode', '=', 'MaterialInwardDetail.materiralInwardCode')
    //       ->get(['MaterialInwardDetail.*', 'materialInwardMaster.is_opening', 'materialInwardMaster.invoice_no','materialInwardMaster.po_code',
    //       'materialInwardMaster.invoice_date',  'ledger_master.ac_name','spare_item_master.dimension', 'spare_item_master.item_name',
    //       'spare_item_master.color_name','spare_item_master.item_description', 'rack_master.rack_name']);
        

    //  // $query = DB::getQueryLog();
    //     // $query = end($query);
    //     // dd($query);
    //     return view('MaterialGRNData',compact('MaterialInwardDetails'));
    // }
    
    public function MaterialGRNData(Request $request)
    {
        if ($request->ajax()) 
        { 
           
            $MaterialInwardDetails = MaterialInwardDetailModel::
              leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'MaterialInwardDetail.Ac_code')
              ->leftJoin('spare_item_master', 'spare_item_master.spare_item_code', '=', 'MaterialInwardDetail.spare_item_code')
              ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'MaterialInwardDetail.rack_id')
              ->leftJoin('materialInwardMaster', 'materialInwardMaster.materiralInwardCode', '=', 'MaterialInwardDetail.materiralInwardCode')
              ->leftJoin('ledger_master as L1', 'L1.ac_code', '=', 'materialInwardMaster.Ac_code')
              ->where('spare_item_master.cat_id','!=',4)
              ->get(['MaterialInwardDetail.*', 'materialInwardMaster.is_opening', 'materialInwardMaster.invoice_no','materialInwardMaster.po_code','L1.Ac_name as BuyerName',
              'materialInwardMaster.invoice_date',  'ledger_master.ac_name','spare_item_master.dimension', 'spare_item_master.item_name',
              'spare_item_master.color_name','spare_item_master.item_description', 'rack_master.rack_name']);
          
            //dd(DB::getQueryLog());
            return Datatables::of($MaterialInwardDetails)
            
            ->addColumn('sales_order_no',function ($row) 
            {
                if($row->is_opening!=1)  
                {
                    $sales_order_no = isset($row->sales_order_no) ? $row->sales_order_no : "-";
                }
                else
                {
                    $sales_order_no = 'Opening Stock';
                }
                 return $sales_order_no;
            })
             
            ->addColumn('buyer',function ($row) 
            {
                // $BuyerData=DB::table('spare_purchase_order_detail')->select('L1.Ac_name as BuyerName')
                //         ->leftJoin('spare_item_master', 'spare_item_master.spare_item_code', '=','spare_purchase_order_detail.spare_item_code')
                //         ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=','spare_purchase_order_detail.sales_order_no')
                //         ->leftJoin('ledger_master as L1', 'L1.ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                //         ->Where('spare_purchase_order_detail.pur_code',$row->po_code)
                //         ->Where('spare_purchase_order_detail.spare_item_code',$row->spare_item_code)
                //         ->limit(1)->get();
                        
                if($row->is_opening!=1)  
                {
                    $buyer = isset($row->BuyerName) ? $row->BuyerName : "-";
                }
                else
                {
                    $buyer = 'N/A';
                }
                 return $buyer;
            })
             
            ->addColumn('item_value',function ($row) 
            {
                 $item_value = round(($row->item_qty * $row->item_rate),2);
                 return round($item_value,2);
            })
             ->rawColumns(['sales_order_no','buyer','item_value'])
             
             ->make(true);
    
            }
            
          return view('MaterialGRNData');
        
    }
    
    // public function MaterialGRNDataMD($DFilter)
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();
        
        
    //     $ItemList = ItemModel::where('spare_item_master.delflag','=', '0')->where('spare_item_master.cat_id','=', '1')->get();
    //     $POList = SparePurchaseOrderModel::where('spare_purchase_order.po_status','=', '1')->where('spare_purchase_order.bom_type','=', '1')->get();
    //     $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
    //     $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
    //     if($DFilter == 'd')
    //     {
    //         $filterDate = " AND MaterialInwardDetail.materiralInwardDate =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    //     }
    //     else if($DFilter == 'm')
    //     {
    //         $filterDate = ' AND MONTH(MaterialInwardDetail.materiralInwardDate) = MONTH(CURRENT_DATE()) and YEAR(MaterialInwardDetail.materiralInwardDate)=YEAR(CURRENT_DATE()) AND MaterialInwardDetail.materiralInwardDate !="'.date('Y-m-d').'"';
    //     }
    //     else if($DFilter == 'y')
    //     {
    //         $filterDate = ' AND MaterialInwardDetail.materiralInwardDate between (select fdate from financial_year_master 
    //                         where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
    //     }
    //     else
    //     {
    //         $filterDate = "";
    //     }
    //     //DB::enableQueryLog();
    //     $MaterialInwardDetails = DB::select("SELECT MaterialInwardDetail.*, materialInwardMaster.is_opening, materialInwardMaster.invoice_no,materialInwardMaster.po_code,
    //       materialInwardMaster.invoice_date,  ledger_master.ac_name,spare_item_master.dimension, spare_item_master.item_name,
    //       spare_item_master.color_name,spare_item_master.item_description, rack_master.rack_name FROM MaterialInwardDetail 
    //       LEFT JOIN ledger_master ON ledger_master.ac_code = MaterialInwardDetail.Ac_code
    //       LEFT JOIN spare_item_master ON spare_item_master.spare_item_code = MaterialInwardDetail.spare_item_code
    //       LEFT JOIN rack_master ON rack_master.rack_id = MaterialInwardDetail.rack_id
    //       LEFT JOIN materialInwardMaster ON materialInwardMaster.materiralInwardCode = MaterialInwardDetail.materiralInwardCode WHERE 1 ".$filterDate);
    //       //dd(DB::getQueryLog());
    //  // $query = DB::getQueryLog();
    //     // $query = end($query);
    //     // dd($query);
    //     return view('MaterialGRNData',compact('MaterialInwardDetails'));
    // }
    
    public function MaterialGRNDataMD(Request $request,$DFilter)
    {
        if ($request->ajax()) 
        { 
            if($DFilter == 'd')
            {
                $filterDate = " AND MaterialInwardDetail.materiralInwardDate =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            }
            else if($DFilter == 'm')
            {
                $filterDate = ' AND MONTH(MaterialInwardDetail.materiralInwardDate) = MONTH(CURRENT_DATE()) and YEAR(MaterialInwardDetail.materiralInwardDate)=YEAR(CURRENT_DATE()) AND MaterialInwardDetail.materiralInwardDate !="'.date('Y-m-d').'"';
            }
            else if($DFilter == 'y')
            {
                $filterDate = ' AND MaterialInwardDetail.materiralInwardDate between (select fdate from financial_year_master 
                                where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
            }
            else
            {
                $filterDate = "";
            }
            //DB::enableQueryLog();
            $MaterialInwardDetails = DB::select("SELECT MaterialInwardDetail.*, materialInwardMaster.is_opening, materialInwardMaster.invoice_no,materialInwardMaster.po_code,
              materialInwardMaster.invoice_date,  ledger_master.ac_name,spare_item_master.dimension, spare_item_master.item_name,
              spare_item_master.color_name,spare_item_master.item_description, rack_master.rack_name FROM MaterialInwardDetail 
              LEFT JOIN ledger_master ON ledger_master.ac_code = MaterialInwardDetail.Ac_code
              LEFT JOIN spare_item_master ON spare_item_master.spare_item_code = MaterialInwardDetail.spare_item_code
              LEFT JOIN rack_master ON rack_master.rack_id = MaterialInwardDetail.rack_id
              LEFT JOIN materialInwardMaster ON materialInwardMaster.materiralInwardCode = MaterialInwardDetail.materiralInwardCode WHERE 
              spare_item_master.cat_id != 4 ".$filterDate);
            //dd(DB::getQueryLog());
            return Datatables::of($MaterialInwardDetails)
            
            ->addColumn('sales_order_no',function ($row) 
            {
                if($row->is_opening!=1)  
                {
                    $sales_order_no = isset($row->sales_order_no) ? $row->sales_order_no : "-";
                }
                else
                {
                    $sales_order_no = 'Opening Stock';
                }
                 return $sales_order_no;
            })
             
            ->addColumn('buyer',function ($row) 
            {
                $BuyerData=DB::table('spare_purchase_order_detail')->select('L1.Ac_name as BuyerName')
                        ->leftJoin('spare_item_master', 'spare_item_master.spare_item_code', '=','spare_purchase_order_detail.spare_item_code')
                        ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=','spare_purchase_order_detail.sales_order_no')
                        ->leftJoin('ledger_master as L1', 'L1.ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                        ->Where('spare_purchase_order_detail.pur_code',$row->po_code)
                        ->Where('spare_purchase_order_detail.spare_item_code',$row->spare_item_code)
                        ->limit(1)->get();
                        
                if($row->is_opening!=1)  
                {
                    $buyer = isset($BuyerData[0]->BuyerName) ? $BuyerData[0]->BuyerName : "-";
                }
                else
                {
                    $buyer = 'N/A';
                }
                 return $buyer;
            })
             
            ->addColumn('item_value',function ($row) 
            {
                 $item_value = round($row->item_qty * $row->item_rate);
                 return $item_value;
            })
             ->rawColumns(['sales_order_no','buyer','item_value'])
             
             ->make(true);
    
            }
            
          return view('MaterialGRNData');
        
    }
    
    public function GetMaterialGRNReport()
    {
       $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
       return view('GetMaterialGRNReport',compact('LedgerList')); 
    }
    
    
    
    public function MaterialStockData()
    {
        // $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        // $FGList =  DB::table('fg_master')->get();
        // $ItemList = ItemModel::where('spare_item_master.delflag','=', '0')->where('spare_item_master.cat_id','=', '1')->get();
        // $POList = SparePurchaseOrderModel::where('spare_purchase_order.po_status','=', '1')->where('spare_purchase_order.bom_type','=', '1')->get();
        // $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        // $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
   
        //DB::enableQueryLog();
      
        // $MaterialInwardDetails1 = DB::select("select materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code,
        //     ledger_master.ac_name, sum(item_qty) as item_qty,MaterialInwardDetail.item_rate,
        //     (select ifnull(sum(item_qty),0)  from MaterialOutwardDetail 
        //     where MaterialOutwardDetail.po_code=MaterialInwardDetail.po_code and MaterialOutwardDetail.spare_item_code=MaterialInwardDetail.spare_item_code) as out_qty ,
        //     materialInwardMaster.po_code, 
        //     ledger_master.ac_name,spare_item_master.dimension,spare_item_master.item_name,
        //     spare_item_master.color_name,spare_item_master.item_description,rack_master.rack_name
        //     from MaterialInwardDetail
        //     left join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
        //     left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
        //     left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
        //     left join rack_master on rack_master.rack_id=MaterialInwardDetail.rack_id 
        //     WHERE spare_item_master.cat_id !=4
        //     group by materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code
        // ");  
        
         $MaterialInwardDetails1 = DB::select("SELECT 
                TIM.po_code,
                TID.spare_item_code,
                LM.ac_name,
                SUM(TID.item_qty) AS item_qty,
                TID.item_rate,
                IFNULL(SUM(TOD.item_qty), 0) AS out_qty,
                TIM.po_code,
                LM.ac_name,
                IM.dimension,
                IM.item_name,
                IM.color_name,
                IM.item_description,
                RM.rack_name
            FROM 
                MaterialInwardDetail TID
                LEFT JOIN materialInwardMaster TIM ON TIM.materiralInwardCode = TID.materiralInwardCode
                LEFT JOIN ledger_master LM ON LM.ac_code = TID.ac_code
                LEFT JOIN spare_item_master IM ON IM.spare_item_code = TID.spare_item_code AND IM.cat_id != 4
                LEFT JOIN rack_master RM ON RM.rack_id = TID.rack_id
                LEFT JOIN MaterialOutwardDetail TOD ON TOD.po_code = TID.po_code AND TOD.spare_item_code = TID.spare_item_code
            GROUP BY 
                TIM.po_code,
                TID.spare_item_code");   
       
        //dd(DB::getQueryLog()); 
        $isOpening = "";
        return view('MaterialStockData',compact('MaterialInwardDetails1','isOpening'));
    }
    public function MaterialStockData1(Request $request)
    {
        return view('MaterialStockData1');
    }
    public function loadDumpMaterialtockData(Request $request)
    {
        $MaterialInwardDetails = DB::select("SELECT * FROM dump_Material_stock_data");
        $totalAmountData = DB::select("SELECT sum(value) as overallAmt FROM dump_Material_stock_data");
        $html = "";
        foreach($MaterialInwardDetails as $row)
        {
            $html .='<tr>
                         <td style="text-align:center; white-space:nowrap">'.$row->suplier_name.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->buyer_name.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->po_status.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->po_no.'</td>
                         <td>'.$row->spare_item_code.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->item_name.'</td>
                         <td style="text-align:right;">'.$row->stock_qty.'</td>
                         <td style="text-align:right;">'.$row->rate.'</td>
                         <td style="text-align:right;">'.$row->value.'</td>
                         <td style="text-align:right;">'.$row->width.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->color.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->item_description.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->rack_name.'</td>
                      </tr>';
        }
        
        $overall = isset($totalAmountData[0]->overallAmt) ? $totalAmountData[0]->overallAmt : 0;
        return response()->json(['html1' => $html,'overall'=>round($overall)]);
    }
    
  
    // public function Materialtocks(Request $request)
    // { 
    //     $total_stock_qty = 0;
    //     $total_value = 0;
    //     $total_value1 = 0;
    //     $html1 = "";
    //     DB::table('dump_Material_stock_data')->delete();
    //       $MaterialInwardDetails1 = DB::select("select materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code,
    //         ledger_master.ac_name, sum(item_qty) as item_qty,MaterialInwardDetail.item_rate,
            
    //         (select ifnull(sum(item_qty),0)  from MaterialOutwardDetail 
    //         where MaterialOutwardDetail.po_code=MaterialInwardDetail.po_code and MaterialOutwardDetail.spare_item_code=MaterialInwardDetail.spare_item_code) as out_qty ,
            
    //         materialInwardMaster.po_code, 
    //         ledger_master.ac_name,spare_item_master.dimension,spare_item_master.item_name,
    //         spare_item_master.color_name,spare_item_master.item_description,rack_master.rack_name
    //         from MaterialInwardDetail
    //         left join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
    //         left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
    //         left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
    //         left join rack_master on rack_master.rack_id=MaterialInwardDetail.rack_id 
    //         WHERE spare_item_master.cat_id !=4
    //         group by materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code");
    //     //dd(DB::getQueryLog()); 
    //     $isOpening = "";
      
    //     foreach($MaterialInwardDetails1 as $rows)
    //     {
    //          if($isOpening == 1)
    //          {
    //             $po_status = ' AND po_status = 1';
    //          }
    //          else if($isOpening == 2)
    //          {
    //             $po_status = ' AND po_status = 2';
    //          }
    //          else
    //          {
    //             $po_status = "";
    //          }
             
    //          $StatusData = DB::select("select ifnull(spare_purchase_order.po_status,0) as po_status
    //          from spare_purchase_order WHERE spare_purchase_order.pur_code = '".$rows->po_code."'".$po_status);
    //          if(count($StatusData) > 0)
    //          {
    //          $po_status = $StatusData[0]->po_status;
    //          }
    //          else
    //          {
    //          $po_status = 0;
    //          }
    //          $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$po_status);
    //          if(count($JobStatusList) > 0)
    //          {
    //          $job_status_name = $JobStatusList[0]->job_status_name;
    //          }
    //          else
    //          {
    //          $job_status_name = "-";
    //          }
    //          $salesOrderNo=DB::select("select distinct sales_order_no from spare_purchase_order_detail where  pur_code='".$rows->po_code."'");
    //          if(count($salesOrderNo) > 0)
    //          {
    //          $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
    //          INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
    //          where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
    //          if(count($buyerData) > 0)
    //          {
    //          $buyer_name = $buyerData[0]->ac_name;
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }
             
    //          $values = ($rows->item_qty-$rows->out_qty)*$rows->item_rate;
    //             DB::table('dump_Material_stock_data')->insert(
    //                 array('suplier_name' => $rows->ac_name,
    //                       'buyer_name' => $buyer_name,
    //                       'po_status' => $job_status_name,
    //                       'po_no' => $rows->po_code,
    //                       'spare_item_code' => $rows->spare_item_code,
    //                       'item_name' => $rows->item_name,
    //                       'stock_qty' => number_format($rows->item_qty - $rows->out_qty),
    //                       'rate' => $rows->item_rate,
    //                       'value' => number_format(round($values)),
    //                       'width' => $rows->dimension,
    //                       'color' => $rows->color_name,
    //                       'item_description' => $rows->item_description,
    //                       'rack_name' => $rows->rack_name,
    //                 )
    //             );
          
    //           $total_value += $values;
    //     }
          
    //     if($isOpening == 2)
    //     {
                        
    //     $MaterialInwardDetails2 = DB::select("select materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code,
    //         ledger_master.ac_name, sum(item_qty) as item_qty,MaterialInwardDetail.item_rate,
    //         (select ifnull(sum(item_qty),0)  from MaterialOutwardDetail 
    //         where MaterialOutwardDetail.po_code=MaterialInwardDetail.po_code and MaterialOutwardDetail.spare_item_code=MaterialInwardDetail.spare_item_code) as out_qty ,
    //         materialInwardMaster.po_code, 
    //         ledger_master.ac_name,spare_item_master.dimension,spare_item_master.item_name,
    //         spare_item_master.color_name,spare_item_master.item_description,rack_master.rack_name
    //         from MaterialInwardDetail
    //         INNER join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
    //         INNER JOIN spare_purchase_order ON spare_purchase_order.pur_code = MaterialInwardDetail.po_code
    //         left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
    //         left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
    //         left join rack_master on rack_master.rack_id=MaterialInwardDetail.rack_id WHERE 1 AND materialInwardMaster.is_opening=1
    //         group by materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code");
        
    //     foreach($MaterialInwardDetails2 as $row)   
    //     {
    //          if($isOpening == 1)
    //          {
    //             $po_status = ' AND po_status = 1';
    //          }
    //          else if($isOpening == 2)
    //          {
    //             $po_status = ' AND po_status = 2';
    //          }
    //          else
    //          {
    //             $po_status = "";
    //          }
             
    //          $StatusData = DB::select("select ifnull(spare_purchase_order.po_status,0) as po_status
    //          from spare_purchase_order WHERE spare_purchase_order.pur_code = '".$row->po_code."'".$po_status);
    //          if(count($StatusData) > 0)
    //          {
    //          $po_status = $StatusData[0]->po_status;
    //          }
    //          else
    //          {
    //          $po_status = 0;
    //          }
    //          $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$po_status);
    //          if(count($JobStatusList) > 0)
    //          {
    //          $job_status_name = $JobStatusList[0]->job_status_name;
    //          }
    //          else
    //          {
    //          $job_status_name = "-";
    //          }
    //          $salesOrderNo=DB::select("select distinct sales_order_no from spare_purchase_order_detail where  pur_code='".$row->po_code."'");
    //          if(count($salesOrderNo) > 0)
    //          {
    //          $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
    //          INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
    //          where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
    //          if(count($buyerData) > 0)
    //          {
    //          $buyer_name = $buyerData[0]->ac_name;
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }   
             
    //          $values1 = ($row->item_qty-$row->out_qty)*$row->item_rate;
          
    //           DB::table('dump_Material_stock_data')->insert(
    //             array('suplier_name' => $row->ac_name,
    //                   'buyer_name' => $buyer_name,
    //                   'po_status' => $job_status_name,
    //                   'po_no' => $row->po_code,
    //                   'spare_item_code' => $row->spare_item_code,
    //                   'item_name' => $row->item_name,
    //                   'stock_qty' => number_format($row->item_qty - $row->out_qty),
    //                   'rate' => $row->item_rate,
    //                   'value' => number_format(round($values1)),
    //                   'width' => $row->dimension,
    //                   'color' => $row->color_name,
    //                   'item_description' => $row->item_description,
    //                   'rack_name' => $row->rack_name,
    //             )
    //         );
            
    //               $total_value1 += $values1;
    //     }
         
    //     }
          
        
    //     return 1;
    // }
    
    
    public function MaterialStockDataMD($isOpening,$DFilter)
    {
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
    
        if($DFilter == 'd')
        {
            $filterDate = " AND MaterialInwardDetail.materiralInwardDate < '".date('Y-m-d')."'";
            $filterDate1 = " AND MaterialOutwardDetail.tout_date < '".date('Y-m-d')."'";
        }
        else if($DFilter == 'm')
        {
            $filterDate = ' AND MaterialInwardDetail.materiralInwardDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate1 = ' AND MaterialOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
        }
        else if($DFilter == 'y')
        {
            $filterDate = ' AND MaterialInwardDetail.materiralInwardDate <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate1 = ' AND MaterialOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
        }
        else
        {
            $filterDate = "";
            $filterDate1 = "";
        }
         //
         
         if($isOpening==2)
         {
             $po_status=" AND spare_purchase_order.po_status !=1 ";
         }
         elseif($isOpening==1)
         {
             $po_status=" AND spare_purchase_order.po_status =1 ";
         }
         
        $MaterialInwardDetails1 = DB::select("select materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,MaterialInwardDetail.item_rate,
               
          (select ifnull(sum(item_qty),0)  from MaterialOutwardDetail 
            where MaterialOutwardDetail.po_code=MaterialInwardDetail.po_code and MaterialOutwardDetail.spare_item_code=MaterialInwardDetail.spare_item_code ".$filterDate1.") as out_qty ,
            spare_item_master.dimension,spare_item_master.item_name,
            spare_item_master.color_name,spare_item_master.item_description,rack_master.rack_name
            from MaterialInwardDetail
            inner join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
            INNER JOIN spare_purchase_order ON spare_purchase_order.pur_code = MaterialInwardDetail.po_code
            left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
            left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
            left join rack_master on rack_master.rack_id=MaterialInwardDetail.rack_id WHERE 1 ".$filterDate.$po_status."
            and spare_item_master.cat_id!=4
            group by materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code");
           //  DB::enableQueryLog();
        $MaterialInwardDetails2 = DB::select("select materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,MaterialInwardDetail.item_rate,
            (select ifnull(sum(item_qty),0)  from MaterialOutwardDetail 
            where MaterialOutwardDetail.po_code=MaterialInwardDetail.po_code and MaterialOutwardDetail.spare_item_code=MaterialInwardDetail.spare_item_code ".$filterDate1.") as out_qty ,
            spare_item_master.dimension,spare_item_master.item_name,
            spare_item_master.color_name,spare_item_master.item_description,rack_master.rack_name
            from MaterialInwardDetail
            inner join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
            left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
            left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
            left join rack_master on rack_master.rack_id=MaterialInwardDetail.rack_id WHERE 1 ".$filterDate." AND materialInwardMaster.is_opening=1 
            and spare_item_master.cat_id!=4  
            group by materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code
            "); 
            
       //   dd(DB::getQueryLog()); 
        
        return view('MaterialStockData',compact('MaterialInwardDetails1','MaterialInwardDetails2','isOpening'));
    }
    
    
    
    public function MaterialPOVsGRNDashboard(Request $request)
    {
       
     if ($request->ajax()) {
               
            //  DB::enableQueryLog();  
            //DB::enableQueryLog();
            $MaterialPOGRNList = DB::select("SELECT  spare_purchase_order.pur_code, spare_purchase_order.pur_date, materialInwardMaster.materiralInwardCode,
            materialInwardMaster.materiralInwardDate, materialInwardMaster.invoice_no, materialInwardMaster.invoice_date, spare_item_master.item_name,
            spare_item_master.item_description, unit_master.unit_name, MaterialInwardDetail.item_rate,MaterialInwardDetail.spare_item_code,
            (select sum(spare_purchase_order_detail.item_qty) 
            from spare_purchase_order_detail where spare_purchase_order_detail.pur_code=spare_purchase_order.pur_code and
            spare_purchase_order_detail.spare_item_code=MaterialInwardDetail.spare_item_code) as po_qty, sum(item_qty) as received_qty,
            (sum(item_qty)*MaterialInwardDetail.item_rate) as received_Value FROM `MaterialInwardDetail` 
            LEFT join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode 
            inner join spare_purchase_order on spare_purchase_order.pur_code=materialInwardMaster.po_code 
            LEFT join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code 
            LEFT join unit_master on unit_master.unit_id=spare_item_master.unit_id 
            GROUP by materialInwardMaster.po_code, MaterialInwardDetail.spare_item_code");
            //dd(DB::getQueryLog());
            //   $query = DB::getQueryLog();
            //   $query = end($query);
            //   dd($query);
            
            return Datatables::of($MaterialPOGRNList)
            ->addIndexColumn()
           ->addColumn('PO_value',function ($row) {
    
             $PO_value =round(($row->po_qty * $row->item_rate));
    
             return $PO_value;
           })
          ->addColumn('pending_qty',function ($row) {
    
             $pending_qty =($row->po_qty - $row->received_qty);
    
             return $pending_qty;
           })
           
             ->rawColumns(['PO_value','pending_qty'])
             
             ->make(true);
    
            }
            
          return view('MaterialPOVsGRNDashboard');
        
    }
    
    public function MaterialGRNPrint($materiralInwardCode)
    {
        
         $FirmDetail =  DB::table('firm_master')->first();
         
         $materiralInwardCode=base64_decode($materiralInwardCode);

         $MaterialInwardMaster = MaterialInwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'materialInwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'materialInwardMaster.Ac_code')
         ->where('materialInwardMaster.materiralInwardCode', $materiralInwardCode)
         ->get(['materialInwardMaster.*','usermaster.username','ledger_master.Ac_name', 'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address']);
         
         return view('MaterialGRNPrint', compact('FirmDetail','MaterialInwardMaster'));
      
    }
    
     
    
    public function GetOnPageMaterialtock()
    {
         
         
         $PODetails = DB::select("SELECT ifnull((select count(sr_no) from spare_purchase_order where bom_type in (2,3)),0)  as noOfPO,
         ifnull((select sum(Net_Amount) from spare_purchase_order where bom_type in (2,3)),0) as poTotal,
         
        ifnull((select sum(Net_Amount) from spare_purchase_order where po_status=2 and bom_type in (2,3)),0) as receivedTotal 
           ");
    
         $GRNTotal = DB::select(" SELECT  
         spare_purchase_order_detail.item_rate* ifnull((select sum(item_qty) from MaterialInwardDetail 
         where MaterialInwardDetail.spare_item_code=spare_purchase_order_detail.spare_item_code),0)  as received_qty_amt  
         FROM `spare_purchase_order_detail` 
         left outer join spare_item_master on spare_item_master.spare_item_code=spare_purchase_order_detail.spare_item_code
         ");
        $AmountGrn=0;
        foreach($GRNTotal as $row)
        {
            $AmountGrn=$AmountGrn + $row->received_qty_amt;
        }
       
         
         
            $InwardMaterial = DB::select("SELECT spare_purchase_order.sr_no, spare_purchase_order.pur_code,spare_purchase_order.pur_date, job_status_name,
            spare_purchase_order_detail.spare_item_code, item_name, item_image_path,
            
                                item_description, dimension,color_name, sum(spare_purchase_order_detail.item_qty) as item_qty ,
                                
                                ifnull((select sum(item_qty) from MaterialInwardDetail where MaterialInwardDetail.spare_item_code=spare_purchase_order_detail.spare_item_code
                                and MaterialInwardDetail.po_code=spare_purchase_order_detail.pur_code),0) as received_item_qty ,
                              
                              
                               
                                 ifnull((select sum(MaterialOutwardDetail.item_qty) from MaterialOutwardDetail 
                                 where MaterialOutwardDetail.spare_item_code=spare_purchase_order_detail.spare_item_code  
                                 and MaterialOutwardDetail.po_code=spare_purchase_order_detail.pur_code),0) as issue_item_qty,
                                 
                               
                                 (select ifnull((select sum(item_qty) from spare_purchase_order_detail as pod
                                 where pod.spare_item_code=spare_purchase_order_detail.spare_item_code 
                               and   pod.pur_code=spare_purchase_order_detail.pur_code and
                               spare_purchase_order_detail.pur_date > now() - INTERVAL 30 day),0)
                               
                                -
                                ifnull((select sum(item_qty) from MaterialInwardDetail 
                                where MaterialInwardDetail.spare_item_code=spare_purchase_order_detail.spare_item_code 
                               and   MaterialInwardDetail.po_code=spare_purchase_order_detail.pur_code 
                               and materiralInwardDate > now() - INTERVAL 30 day),0) )as t30_days_item_qty,
                               
                               
                                (select ifnull((select sum(item_qty) from spare_purchase_order_detail as pod 
                                where pod.spare_item_code=spare_purchase_order_detail.spare_item_code 
                                and   pod.pur_code=spare_purchase_order_detail.pur_code 
                                and datediff(current_date,date(pur_date)) BETWEEN  31 AND 60),0)
                               
                                -
                                ifnull((select sum(item_qty) from MaterialInwardDetail
                                where MaterialInwardDetail.spare_item_code=spare_purchase_order_detail.spare_item_code 
                                and     MaterialInwardDetail.po_code=spare_purchase_order_detail.pur_code 
                                and datediff(current_date,date(materiralInwardDate)) BETWEEN  31 AND 60),0) )as t60_days_item_qty,
                               
                               
                                (select ifnull((select sum(item_qty) from spare_purchase_order_detail as pod 
                                where pod.spare_item_code=spare_purchase_order_detail.spare_item_code 
                                and   pod.pur_code=spare_purchase_order_detail.pur_code and 
                                datediff(current_date,date(pur_date)) BETWEEN  61 AND 90),0)
                               
                                -
                                ifnull((select sum(item_qty) from MaterialInwardDetail 
                                where MaterialInwardDetail.spare_item_code=spare_purchase_order_detail.spare_item_code 
                                and MaterialInwardDetail.po_code=spare_purchase_order_detail.pur_code
                                and datediff(current_date,date(materiralInwardDate)) BETWEEN  61 AND 90),0) )as t90_days_item_qty 
                               
                               
                                FROM `spare_purchase_order_detail` 
                                
                                left outer join spare_item_master on spare_item_master.spare_item_code=spare_purchase_order_detail.spare_item_code
                                left outer join spare_purchase_order on spare_purchase_order.pur_code=spare_purchase_order_detail.pur_code
                                left outer  join job_status_master on job_status_master.job_status_id=spare_purchase_order.po_status
                                where spare_purchase_order.bom_type in (2,3)
                                group by  spare_purchase_order_detail.pur_code, spare_purchase_order_detail.spare_item_code
        ");
         
          return view('MaterialtockOnPage', compact('InwardMaterial','PODetails','AmountGrn'));
          
    }
    
    
    
    
    
    public function GetMaterialInwardList(Request $request)
    {
             $sr_no= $request->input('sr_no');
             $spare_item_code= $request->input('spare_item_code');
             $ItemList = ItemModel::where('spare_item_master.delflag','=', '0')->get();
            $POList = SparePurchaseOrderModel::where('sr_no','=', $sr_no)->first();  
            //  $query = DB::getQueryLog();
            //   $query = end($query);
            //   dd($query);
              // echo $sr_no;
          
        // DB::enableQueryLog();
        $InwardMaterial = DB::select("SELECT materialInwardMaster.`materiralInwardCode`, materialInwardMaster.`materiralInwardDate`,   
        MaterialInwardDetail.`spare_item_code`, unit_master.unit_name, spare_item_master.item_name, spare_item_master.item_description,
        sum(MaterialInwardDetail.`item_qty`) as item_qty , MaterialInwardDetail.item_rate, MaterialInwardDetail.unit_id
        FROM `MaterialInwardDetail` 
        inner join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode 
        inner join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
        inner join unit_master on unit_master.unit_id=MaterialInwardDetail.unit_id
        where materialInwardMaster.po_code='".$POList->pur_code."' and MaterialInwardDetail.spare_item_code='".$spare_item_code."'
        group by MaterialInwardDetail.`materiralInwardCode`  , MaterialInwardDetail.spare_item_code
        ");
        //   $query = DB::getQueryLog();
        //       $query = end($query);
        //       dd($query);
        
        $html ='';
        $html .= '<input type="number" value="'.count($InwardMaterial).'" name="cntrr" id="cntrr" readonly="" hidden="true"  />';
       
        
        $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
    <thead>
    <tr>
    <th>In Code</th>
    <th>In Date</th>
    <th>Item Name</th>
    <th>Description</th>
    <th>Unit</th>
    <th>Qty</th>
    </tr>
    </thead>
    <tbody>';
    $no=1;
    foreach ($InwardMaterial as $row) {
        $html .='<tr>';
        
    $html .='
    <td><input type="text"  value="'.$row->materiralInwardCode.'" id="id" style="width:150px;" readOnly/></td>
    <td><input type="date"  value="'.$row->materiralInwardDate.'" id="id" style="width:100px;" readOnly/></td>
    <td><input type="text"  value="'.$row->item_name.'"  style="width:200px;" required readOnly/></td>
    <td><input type="text"  value="'.$row->item_description.'"  style="width:200px;" required readOnly/></td>
    <td><input type="text"  value="'.$row->unit_name.'"  style="width:80px;" required readOnly/></td>
    <td><input type="text"  name="item_qty[]"    value="'.$row->item_qty.'" id="item_qty" style="width:80px;" required readOnly/></td>';
      
        $html .='</tr>';
        $no=$no+1;
        }
        
        $html .='</tbody>
        </table>';
    
        if(count($InwardMaterial)!=0)
        {
              return response()->json(['html' => $html]);
        }
      
         
    }
        
        
        
    public function GetComparePOInwardList(Request $request)
    {
        $sr_no= $request->input('sr_no');
        $spare_item_code= $request->input('spare_item_code');
        $ItemList = ItemModel::where('spare_item_master.delflag','=', '0')->get();
        $POList = SparePurchaseOrderModel::where('sr_no','=', $sr_no)->first();  
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        // echo $sr_no;
        //   DB::enableQueryLog();
        $InwardMaterial = DB::select("SELECT   
        MaterialInwardDetail.`spare_item_code`, unit_master.unit_name, spare_item_master.item_name, spare_item_master.item_description,
        (select sum(item_qty) as po_item_qty from spare_purchase_order_detail where spare_purchase_order_detail.pur_code='".$POList->pur_code."' and spare_purchase_order_detail.spare_item_code='".$spare_item_code."') as po_item_qty,
        sum(MaterialInwardDetail.`item_qty`) as item_qty , MaterialInwardDetail.item_rate, MaterialInwardDetail.unit_id
        FROM `MaterialInwardDetail` 
        inner join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode 
        inner join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
        inner join unit_master on unit_master.unit_id=MaterialInwardDetail.unit_id
        where materialInwardMaster.po_code='".$POList->pur_code."' and MaterialInwardDetail.spare_item_code='".$spare_item_code."'
        group by materialInwardMaster.po_code, MaterialInwardDetail.spare_item_code");
        //   $query = DB::getQueryLog();
        //       $query = end($query);
        //       dd($query);
        
        $html ='<b>PO No:'.$POList->pur_code.'</b> <br>';
        $html .= '<input type="number" value="'.count($InwardMaterial).'" name="cntrr" id="cntrr" readonly="" hidden="true"  />';
       
        
        $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
        <thead>
        <tr>
        
        <th>Item Name</th>
        <th>Description</th>
        <th>Unit</th>
        <th>PO Qty</th>
        <th>Received Qty</th>
        <th>To Be Received</th>
        </tr>
        </thead>
        <tbody>';
        $no=1;
        foreach ($InwardMaterial as $row) {
            $html .='<tr>';
            
        $html .='
         
        <td><input type="text"  value="'.$row->item_name.'"  style="width:200px;" required readOnly/></td>
        <td><input type="text"  value="'.$row->item_description.'"  style="width:200px;" required readOnly/></td>
        <td><input type="text"  value="'.$row->unit_name.'"  style="width:80px;" required readOnly/></td>
        <td><input type="text"  value="'.$row->po_item_qty.'" id="item_qty" style="width:80px;" required readOnly/></td> 
        <td><input type="text"  value="'.$row->item_qty.'" id="item_qty" style="width:80px;" required readOnly/></td>
        <td><input type="text"  value="'.($row->po_item_qty-$row->item_qty).'" id="item_qty" style="width:80px;" required readOnly/></td>
        ';
        
    
      
        $html .='</tr>';
        $no=$no+1;
        }
        
        $html .='</tbody>
        </table>';
    
        if(count($InwardMaterial)!=0)
        {
              return response()->json(['html' => $html]);
        }
      
         
    }    
        
    public function checkPOIsExist(Request $request)
    {   
       $MaterialOutwardData = DB::table('MaterialOutwardDetail')->where('po_code','=',$request->po_code)->get();
       return response()->json(['html' => count($MaterialOutwardData)]);
    }
    
    public function MaterialStockDataTrial(Request $request)
    {
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='MaterialStockDataTrial?currentDate=".date('Y-m-d')."';</script>";
        }
        if($currentDate != "")
        {
            $materiralInwardDate = " AND MaterialInwardDetail.materiralInwardDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
            $tout_date = " AND MaterialOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
        }
        else
        {
             $materiralInwardDate = "";
             $tout_date = "";
        }
        
        $MaterialInwardDetails1 = DB::select("select materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,MaterialInwardDetail.item_rate,
            (select ifnull(sum(item_qty),0)  from MaterialOutwardDetail 
            where MaterialOutwardDetail.po_code=MaterialInwardDetail.po_code and MaterialOutwardDetail.spare_item_code=MaterialInwardDetail.spare_item_code".$tout_date.") as out_qty ,
            materialInwardMaster.po_code, 
            ledger_master.ac_name,spare_item_master.dimension,spare_item_master.item_name,
            spare_item_master.color_name,spare_item_master.item_description,rack_master.rack_name
            from MaterialInwardDetail
            left join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
            left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
            left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
            left join rack_master on rack_master.rack_id=MaterialInwardDetail.rack_id 
            WHERE spare_item_master.cat_id !=4 ".$materiralInwardDate."
            group by materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code
        ");
        
        $isOpening = "";
        
        return view('MaterialStockDataTrial',compact('MaterialInwardDetails1','currentDate','isOpening'));
    }
    
    public function loadDateWiseMaterialtockData(Request $request)
    {
        $Amt = 0;
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
 
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='MaterialStockDataTrial?currentDate=".date('Y-m-d')."';</script>";
        }
        if($currentDate != "")
        {
            $materiralInwardDate = " AND MaterialInwardDetail.materiralInwardDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
            $tout_date = " AND MaterialOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
        }
        else
        {
             $materiralInwardDate = "";
             $tout_date = "";
        }
        
        $MaterialInwardDetails1 = DB::select("select materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,MaterialInwardDetail.item_rate,
            (select ifnull(sum(item_qty),0)  from MaterialOutwardDetail 
            where MaterialOutwardDetail.po_code=MaterialInwardDetail.po_code and MaterialOutwardDetail.spare_item_code=MaterialInwardDetail.spare_item_code".$tout_date.") as out_qty ,
            materialInwardMaster.po_code, 
            ledger_master.ac_name,spare_item_master.dimension,spare_item_master.item_name,
            spare_item_master.color_name,spare_item_master.item_description,rack_master.rack_name
            from MaterialInwardDetail
            left join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
            left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
            left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
            left join rack_master on rack_master.rack_id=MaterialInwardDetail.rack_id 
            WHERE spare_item_master.cat_id !=4 ".$materiralInwardDate."
            group by materialInwardMaster.po_code,MaterialInwardDetail.spare_item_code
        ");
        
         $isOpening = "";
         $total_stock_qty = 0;
         $total_value = 0;
         $total_value1 = 0;
         $html = "";
         foreach($MaterialInwardDetails1 as $row)   
         {
             if($isOpening == 1)
             {
                $po_status = ' AND po_status = 1';
             }
             else if($isOpening == 2)
             {
                $po_status = ' AND po_status = 2';
             }
             else
             {
                $po_status = "";
             }
         
         $StatusData = DB::select("select ifnull(spare_purchase_order.po_status,0) as po_status
         from spare_purchase_order WHERE spare_purchase_order.pur_code = '".$row->po_code."'".$po_status);
         
         if(count($StatusData) > 0)
         {
             $po_status = $StatusData[0]->po_status;
         }
         else
         {
             $po_status = 0;
         }
         $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$po_status);
         
         if(count($JobStatusList) > 0)
         {
            $job_status_name = $JobStatusList[0]->job_status_name;
         }
         else
         {
            $job_status_name = "-";
         }
         
         $salesOrderNo=DB::select("select distinct sales_order_no from spare_purchase_order_detail where  pur_code='".$row->po_code."'");
         
         if(count($salesOrderNo) > 0)
         {
            $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                 INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                 where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                 
         if(count($buyerData) > 0)
         {
            $buyer_name = $buyerData[0]->ac_name;
         }
         else
         {
            $buyer_name = "-";
         }
         }
         else
         {
            $buyer_name = "-";
         }
         
         $values = ($row->item_qty-$row->out_qty)*$row->item_rate;
        
         $html .= '<tr>
            <td style="text-align:center; white-space:nowrap">'.$row->ac_name.'</td>
            <td style="text-align:center; white-space:nowrap">'.$buyer_name.'</td>
            <td style="text-align:center; white-space:nowrap">'.$job_status_name.'</td>
            <td style="text-align:center; white-space:nowrap">'.$row->po_code.'</td>
            <td>'.$row->spare_item_code.'</td>
            <td style="text-align:center; white-space:nowrap">'.$row->item_name.'</td>
            <td style="text-align:right;">'.number_format($row->item_qty - $row->out_qty).'</td>
            <td style="text-align:right;">'.$row->item_rate.'</td>
            <td style="text-align:right;">'.number_format( round($values)).'</td>
            <td style="text-align:right;">'.$row->dimension.'</td>
            <td style="text-align:center; white-space:nowrap">'.$row->color_name.'</td>
            <td style="text-align:center; white-space:nowrap">'.$row->item_description.'</td>
            <td style="text-align:center; white-space:nowrap">'.$row->rack_name.'</td>
         </tr>'; 
          $Amt = $Amt + round(($row->item_qty-$row->out_qty)*$row->item_rate);
         }           
        $isOpening = "";
        return response()->json(['html' => $html,'currentDate'=>$currentDate,'Amt'=>money_format('%!i',round($Amt,2))]);
    }
    
    public function MaterialStockDataTrialCloned(Request $request)
    { 
        // $MaterialInwardDetails =DB::select("select * from dump_Material_stock_data");
        
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='MaterialStockDataTrialCloned?currentDate=".date('Y-m-d')."';</script>";
        }
        return view('MaterialStockDataTrialCloned',compact('currentDate'));
    }
    
    public function MaterialStocks1()
    {
         DB::table('dump_Material_stock_data')->delete();
         try 
         { 
             $MaterialData =  DB::SELECT("select materialInwardMaster.materiralInwardDate,materialInwardMaster.materiralInwardCode,materialInwardMaster.po_code as po_no,MaterialInwardDetail.spare_item_code,
                ledger_master.ac_name, sum(MaterialInwardDetail.item_qty) as grn_qty,MaterialInwardDetail.item_rate as rate,MaterialInwardDetail.rack_id,job_status_master.job_status_name,spare_purchase_order.po_status,
                materialInwardMaster.po_code,MaterialInwardDetail.amount as amount,
                ledger_master.ac_name as suplier_name,spare_item_master.dimension,spare_item_master.item_name,
                spare_item_master.color_name,spare_item_master.item_description
                from MaterialInwardDetail
                left join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
                left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
                left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
                left join spare_purchase_order ON spare_purchase_order.pur_code = MaterialInwardDetail.po_code
                left join job_status_master ON job_status_master.job_status_id = spare_purchase_order.po_status
                WHERE spare_item_master.cat_id !=4 group by MaterialInwardDetail.po_code,MaterialInwardDetail.spare_item_code,MaterialInwardDetail.materiralInwardCode");
                   
                 foreach($MaterialData as $row)
                 {  
                        $materiralInwardDate = str_replace('"', "", $row->materiralInwardDate);
                        $suplier_name = str_replace('"', "", $row->suplier_name); 
                        $po_no = str_replace('"', "", $row->po_no);
                        $materiralInwardCode = str_replace('"', "", $row->materiralInwardCode); 
                        $spare_item_code = str_replace('"', "", $row->spare_item_code); 
                        $item_name = str_replace('"', "", $row->item_name);
                        $color =  "";
                        $item_description = str_replace('"', "", $row->item_description); 
                        $grn_qty = str_replace('"', "", $row->grn_qty);
                        $rate = str_replace('"', "", $row->rate);
                        $rack_id = str_replace('"', "", $row->rack_id);
                        $ac_code = 0;
                        $suplier_id = 0;
                        $unit_id = 0;
                        $po_status = $row->job_status_name;
                        $job_status_id = $row->po_status;
                        $amount = str_replace('"', "", $row->amount); 
                        $ind_outward_qty1 = "";
                        $ind_outward_qty = 0;
                        $tout_date = "";
                        $outward_qty = 0;
                  
                       //DB::enableQueryLog(); 
                        
                         DB::SELECT('INSERT INTO dump_Material_stock_data(materiralInwardDate,tout_date,suplier_name,po_no,spare_item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,materiralInwardCode,grn_qty,outward_qty,ind_outward_qty,amount)
                                select "'.$materiralInwardDate.'","'.$tout_date.'","'.$suplier_name.'","'.$po_no.'","'.$spare_item_code.'","'.$item_name.'","'.$rate.'", "'.$color.'",
                                        "'.$item_description.'", "'.$po_status.'","'.$job_status_id.'", "'.$rack_id.'","'.$ac_code.'","'.$suplier_id.'","'.$unit_id.'","'.$materiralInwardCode.'","'.$grn_qty.'","'.$outward_qty.'","'.$ind_outward_qty.'","'.$amount.'"');
                        //dd(DB::getQueryLog());
                       
                  }
          
            } 
            catch (\Exception $e) 
            {
            
                DB::table('dump_Material_stock_data')->delete();
            }  
            return 1;exit;
    }
    
    public function UpdateFoutDumpData()
    {
         $MaterialData =  DB::SELECT("select *  from dump_Material_stock_data");
         $ind_outward_qty1 = 0;
         $ind_outward_qty = 0;
         $tout_date = "";
         $outward_qty = 0;
         
         foreach($MaterialData as $row)
         {
                $outwardData1 = DB::SELECT("select ifnull(item_qty,0) as outward_qty,tout_date FROM MaterialOutwardDetail WHERE po_code ='".$row->po_no."' AND spare_item_code ='".$row->spare_item_code."'");
                    
                foreach($outwardData1 as $OD)
                {
                    $outQty = isset($ind_outward_qty1) ? $ind_outward_qty1 : 0;
                    $ind_outward_qty1 = $OD->tout_date."=>".$OD->outward_qty.",".$outQty;
                } 
               
                $outwardData = DB::SELECT("select ifnull(sum(item_qty),0) as outward_qty,tout_date FROM MaterialOutwardDetail WHERE po_code ='".$row->po_no."' AND spare_item_code=".$row->spare_item_code);
    
                $tout_date = isset($outwardData[0]->tout_date) ? $outwardData[0]->tout_date : "";
                $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0; 
            
                DB::table('dump_Material_stock_data')->where('materiralInwardDate', $row->materiralInwardDate)->where('materiralInwardCode', $row->materiralInwardCode)->where('po_no', $row->po_no)->where('spare_item_code', $row->spare_item_code)->update(['ind_outward_qty' => $ind_outward_qty1,'tout_date' => $tout_date,'outward_qty' => $outward_qty]);
                $ind_outward_qty1 = 0;    
         }
    }
    
    public function LoadMaterialStockDataTrialCloned(Request $request)
    {  
                
        $currentDate = $request->currentDate ? $request->currentDate : "";
        $job_status_id = $request->job_status_id ? $request->job_status_id : 0;
        
        //   $fabricData = DB::SELECT("select inward_details.in_date,'',ledger_master.ac_name as suplier_name,inward_master.po_code as po_no,inward_details.in_code as grn_no,inward_master.invoice_no,spare_item_master.spare_item_code,spare_item_master.item_image_path as preview,shade_master.shade_name as shade_no,spare_item_master.item_name,
        //     quality_master.quality_name, spare_item_master.color_name as color,spare_item_master.item_description,ifnull(spare_purchase_order.po_status,0) as po_status,inward_details.track_code as track_name, inward_details.meter as grn_qty, 
        //     inward_details.item_rate as rate,inward_details.rack_id from inward_details 
        //     left join inward_master on inward_master.in_code=inward_details.in_code
        //     left JOIN spare_purchase_order ON spare_purchase_order.pur_code = inward_master.po_code
        //     left join ledger_master on ledger_master.ac_code=inward_details.Ac_code                    
        //     left join spare_item_master on spare_item_master.spare_item_code=inward_details.spare_item_code 
        //     left join quality_master on quality_master.quality_code=spare_item_master.quality_code  
        //     left join shade_master on shade_master.shade_id=inward_details.shade_id");
            
       
        // $MaterialInwardDetails =DB::select("SELECT dump_Material_stock_data.*, (SELECT sum(grn_qty) FROM dump_Material_stock_data AS df WHERE df.po_no = dump_Material_stock_data.po_no 
        //                         AND df.spare_item_code= dump_Material_stock_data.spare_item_code  AND df.materiralInwardDate = dump_Material_stock_data.materiralInwardDate
        //                         AND df.materiralInwardDate <= '".$currentDate."') as gq,
        //                         (SELECT sum(outward_qty) FROM dump_Material_stock_data as df1 WHERE df1.po_no = dump_Material_stock_data.po_no 
        //                         AND df1.spare_item_code= dump_Material_stock_data.spare_item_code AND df1.tout_date = dump_Material_stock_data.tout_date
        //                         AND df1.tout_date <= '".$currentDate."') as oq FROM dump_Material_stock_data GROUP BY po_no");
        //DB::enableQueryLog();  
        $filter = '';
        if($job_status_id > 0)
        {
           if($job_status_id == 1)
           {
                $filter .= " AND job_status_id ='1' OR closeDate > '".$currentDate."'";
           }
           else
           {
               $filter .= " AND job_status_id ='2' AND closeDate <= '".$currentDate."'";
           }
        } 
        
        $MaterialInwardDetails =DB::select("SELECT dump_Material_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_Material_stock_data 
                                        INNER JOIN spare_item_master ON spare_item_master.spare_item_code = dump_Material_stock_data.spare_item_code
                                        WHERE spare_item_master.class_id != 94 AND materiralInwardDate <='".$currentDate."' ".$filter." GROUP BY dump_Material_stock_data.po_no,dump_Material_stock_data.spare_item_code");
        //dd(DB::getQueryLog());
        $html = [];
        $total_value = 0;
        $total_stock = 0;
        $total_amount = 0;
        
        foreach($MaterialInwardDetails as $row)
        {
            $q_qty = 0; 
            $outward_qty = isset($row->oq) ? $row->oq : 0; 
            $grn_qty = isset($row->gq) ? $row->gq : 0; 
            $ind_outward1 = (explode(",",$row->ind_outward_qty));
            
         
            foreach($ind_outward1 as $indu)
            {
                
                 $ind_outward2 = (explode("=>",$indu));
                  
                 if($ind_outward2[0] <= $currentDate)
                 {
                    $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                    $q_qty = $q_qty + $ind_out;
                   
                 }
            } 
          
            $stocks =  $row->gq - $q_qty;
         
             
            if(Session::get('userId') == 1 || Session::get('userId') == 2)
            {  
                 $disbaled = "";
            }
            else
            {
                 $disbaled = "disabled";
            }
            $dis = '';
            if($row->job_status_id == 1 || $currentDate < $row->closeDate)
            {   
                $slider = "Moving"; 
                $chk = 'checked'; 
                if($currentDate < $row->closeDate)
                {
                    $dis = "disabled"; 
                }
            }
            else
            {
                $slider = "Non Moving"; 
                $dis = "disabled"; 
                $chk = ''; 
                
            }
            
            if($job_status_id == 1)
            {
                if($row->closeDate == '' || $row->closeDate == '0000-00-00')
                {
                    $action = '<label class="switch">
                            <input type="checkbox" '.$chk.' '.$disbaled.' '.$dis.' onchange="updateSliderState(this);" po_no='.$row->po_no.' spare_item_code='.$row->spare_item_code.'>
                            <span class="slider round" data-state="'.$slider.'"></span>
                        </label>';
                }
                else
                {
                    $action = '-';
                }
            }
            else
            {
                $action = '-';
            }
            
            $html[] =  array(
                        'Action'=>$action,
                        'suplier_name'=>$row->suplier_name,
                        'buyer_name'=>$row->buyer_name,
                        'po_status'=>$slider,
                        'closeDate'=>$row->closeDate,
                        'po_no'=>$row->po_no, 
                        'spare_item_code'=>$row->spare_item_code, 
                        'item_name'=>$row->item_name,
                        'width'=>$row->width, 
                        'color'=>$row->color,
                        'item_description'=>$row->item_description, 
                        'gq'=>round($row->gq,2),   
                        'q_qty'=>round($q_qty,2), 
                        'stocks'=>round($stocks,2), 
                        'rate'=>$row->rate,
                        'value'=>round($stocks * $row->rate,2), 
            );        
                    
            $total_value += ($stocks * $row->rate);  
            $total_stock +=  $stocks;
            $total_amount +=  $row->amount; 
        }
         
        $jsonData = json_encode($html);
    
        return response()->json(['html' => $jsonData,'total_stock'=>round($total_stock/100000,2),'currentDate'=>$currentDate,'total_value'=>round($total_value/100000,2),'total_amount'=>round($total_amount/100000,2)]);

    }
    
        
    
    public function GetMateriralInwardCodeWiseData(Request $request)
    { 
        //DB::enableQueryLog();
        $detailpurchase = MaterialInwardDetailModel::join('spare_item_master','spare_item_master.spare_item_code', '=', 'materialInwardDetail.spare_item_code','left')
            ->leftJoin('spare_purchase_order', 'spare_purchase_order.pur_code', '=', 'materialInwardDetail.po_code')
            ->leftJoin('classification_master', 'classification_master.class_id', '=', 'spare_item_master.class_id')
            ->where('materiralInwardCode','=', $request->materiralInwardCode)   
            ->get(['materialInwardDetail.*','spare_item_master.item_name','spare_item_master.item_description','spare_item_master.cat_id','spare_item_master.class_id','classification_master.class_name']);
        //dd(DB::getQueryLog());    
        $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->where('spare_item_master.cat_id','!=','1')->get();
        $unitlist = DB::table('unit_master')->where('unit_master.delflag','=', '0')->get();
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
        $html = '';
         
        $po_sr_no=DB::select("select sr_no from spare_purchase_order where pur_code='".$request->po_code."'");
        $no = 1;
        
        foreach($detailpurchase as $row)
        {
          $srno = isset($po_sr_no[0]->sr_no) ? $po_sr_no[0]->sr_no : 'Opening'; 
          $spare_item_code = $row->spare_item_code;
          
          
          $grnData = DB::SELECT("SELECT sum(item_qty) as inward_qty FROM materialInwardDetail WHERE po_code='".$row->po_code."' AND spare_item_code=".$spare_item_code);
        //   $outwardData = DB::SELECT("SELECT sum(item_qty) as outward_qty FROM MaterialOutwardDetail WHERE po_code='".$row->po_code."' AND spare_item_code=".$spare_item_code);
          $outwardData = '';
          $grn_qty = isset($grnData[0]->inward_qty) ? $grnData[0]->inward_qty: 0;      
          $out_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty: 0;      
          $max_qty = $grn_qty - $out_qty;
          $concated = $srno.",".$spare_item_code;
          
          $html .= '<tr>
              <td><input type="text" name="id" value="'.$no.'" id="id"  style="width:50px;" readonly /></td>
              <td>
                 <span onclick="openmodal('.$concated.')" style="color:#556ee6; cursor: pointer;">'.$row->spare_item_code.'</span>
              </td>
              <td>
                 <select name="spare_item_codes[]" class="select2" id="spare_item_codes"  class="select2" style="width:260px;height:30px;" onchange="GetUnit(this);" disabled>';
                    
                    $html .='<option value="'.$row->spare_item_code.'">'.$row->item_name.'-('.$row->spare_item_code.')</option>';
                    
                  $html .= '</select>
              </td>
              <td><input type="text"  value="'.$row->class_name.'" style="width:250px;height:30px;" readonly> </td>
              <td>
                 <select name="unit_ids[]"   id="unit_ids"   style="width:80px;height:30px;" disabled>
                    <option value="">--- Select Unit ---</option>';
                    foreach($unitlist as  $rowunit)
                    {
                        $html .= '<option value="'.$rowunit->unit_id.'" '.($rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '').' >'.$rowunit->unit_name.'</option>';
                    }
                 $html .= '</select>
              </td>
              <td><input   type="number" step="any" class="QTY" name="item_qtys[]" value="'.$row->item_qty.'" stock="'.$max_qty.'" max="'.($row->item_qty).'" style="width:80px;height:30px;" onkeyup="checkNumber(this);" id="item_qty">
                 <input type="hidden" name="hsn_codes[]"   value="'.$row->hsn_code.'" id="hsn_codes" style="width:80px;height:30px;" required/>
              </td>
              <td><input type="number" step="any"  name="item_rates[]"    value="'.$row->item_rate.'" id="item_rates" style="width:80px;height:30px;" disabled/>
              <td><input type="number" step="any" readOnly  name="amounts[]" class="AMT"  value="'.$row->amount.'" id="amounts" style="width:80px;height:30px;" disabled /> 
              <td style="display: flex;">
                  <input type="button" class="btn btn-danger pull-left ml-2" onclick="deleteRow(this);" value="X" style="margin-left: 10px;" >  
              </td>
           </tr>';
            $no=$no+1;  
        }
        
        return response()->json(['html' => $html]);
    }
    
    public function GetmateriralInwardCodeWiseStockData(Request $request)
    { 
        
        $stockData = DB::select("select stock_association.*,spare_item_master.item_name from stock_association 
                INNER JOIN spare_item_master ON spare_item_master.spare_item_code = stock_association.spare_item_code
                where tr_code ='".$request->materiralInwardCode."'"); 
      
        $html = '';
        if(count($stockData) > 0)
        {  
            foreach($stockData as $row)
            {
                $html ='<tr>
                    <td><input type="text" name="stock_bom_code[]" value="'.$row->bom_code.'" class="form-control" style="width:100px;" readonly=""></td>
                    <td><input type="text" name="sales_order_no[]" value="'.$row->sales_order_no.'" class="form-control" style="width:100px;" readonly=""></td>
                    <td><input type="text" name="spare_item_code[]" value="'.$row->spare_item_code.'" class="form-control" style="width:100px;" readonly=""></td>
                    <td><input type="text" name="item_name[]" value="'.$row->item_name.'" class="form-control" style="width:260px;" readonly=""></td>
                    <td>
                        <input type="text" name="allocate_qty[]" value="'.$row->qty.'" class="form-control" style="width:100px;" readonly="">
                        <input type="hidden" name="cat_id[]" value="'.$row->cat_id.'" class="form-control" style="width:100px;" >
                        <input type="hidden" name="class_id[]" value="'.$row->class_id.'"  class="form-control" style="width:100px;">
                    </td>
                </tr>';
            }
        }
               
        return response()->json(['html' => $html]);
    }
        
    public function GetMaterialInOutStockReportForm()
    {  
        return view('GetMaterialInOutStockReportForm');
    }
    
    public  function getBetweenDates($startDate, $endDate)
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
    
    public function MaterialInOutStockReport(Request $request)
    {
        
        $fdate= $request->fdate;
        $tdate= $request->tdate;
         
        if($tdate>date('Y-m-d')){$tdate=date('Y-m-d');}
         
         
        $period = $this->getBetweenDates($fdate, $tdate);
           
        $FirmDetail =  DB::table('firm_master')->first();
      
        return view('MaterialInOutStockReport', compact('period','fdate', 'tdate','FirmDetail'));
      
    }
    
        
    public function RunCronMaterialJob()
    { 
         date_default_timezone_set("Asia/Calcutta"); 
         $time = date("H:i", strtotime("+60 seconds"));
         
         DB::table('syncronization_time_mgmt')->update(['sync_table'=>0]);
         DB::table('syncronization_time_mgmt')->where('stmt_type','=',2)->update(['start_time' => $time, 'status' => 0,'sync_table'=>1]);
    }
    
    
    public function getMaterialPODetails(Request $request)
    { 
        $po_code= $request->po_code;
        //echo $po_code;
        //DB::enableQueryLog();
        $MasterdataList = DB::select("select pur_code, spare_purchase_order.Ac_code, ledger_master.ac_name, spare_purchase_order.po_type_id from spare_purchase_order 
        inner join ledger_master on ledger_master.ac_code=spare_purchase_order.Ac_code
        where spare_purchase_order.pur_code='". $po_code."'");
        //dd(DB::getQueryLog());
        return json_encode($MasterdataList);
    }
 
    public function MaterialInventoryAgingReport()
    {
        return view('MaterialInventoryAgingReport');
    }
    
    public function loadMaterialInventoryAgingReport(Request $request)
    {
       $currentDate = $request->current_date;
    
       $MaterialInwardDetails =DB::select("SELECT df.*,spare_item_master.item_name, (SELECT sum(grn_qty) FROM dump_Material_stock_data WHERE po_no = df.po_no AND spare_item_code = df.spare_item_code AND materiralInwardDate <='".$currentDate."') as gq  
                                        FROM dump_Material_stock_data as df 
                                        INNER JOIN spare_item_master ON spare_item_master.spare_item_code = df.spare_item_code
                                        WHERE spare_item_master.class_id != 94 AND df.materiralInwardDate <='".$currentDate."'  GROUP BY df.po_no,df.spare_item_code");
 
    
        $html = [];
    
         // Initialize arrays to store aggregated sums
        $aggregatedData = [];
    
        foreach ($MaterialInwardDetails as $row) {
            $spare_item_code = $row->spare_item_code;
    
            // Initialize if spare_item_code doesn't exist in aggregatedData
            if (!isset($aggregatedData[$spare_item_code])) {
                $aggregatedData[$spare_item_code] = [
                    'spare_item_code' => $row->spare_item_code,
                    'item_name' => $row->item_name,
                    'total_value30' => 0,
                    'total_value60' => 0,
                    'total_value90' => 0,
                    'total_value180' => 0,
                    'total_value365' => 0,
                    'previousYearValue' => 0,
                    'total_value' => 0,
                ];
            }
    
    
            $grn_qty = isset($row->gq) ? $row->gq : 0;
            $ind_outward1 = (explode(",", $row->ind_outward_qty));
            $q_qty = 0;
    
            foreach ($ind_outward1 as $indu) {
                $ind_outward2 = (explode("=>", $indu));
                $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
    
                if ($ind_outward2[0] <= $currentDate) {
                    $q_qty += $q_qty1;
                }
            }
            
            $stocks = $row->gq - $q_qty;
    
            // Assign stock values based on date ranges
            $stocks1 = ($row->materiralInwardDate >= date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
            $stocks2 = ($row->materiralInwardDate >= date('Y-m-d', strtotime('-60 days')) && $row->materiralInwardDate < date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
            $stocks3 = ($row->materiralInwardDate >= date('Y-m-d', strtotime('-90 days')) && $row->materiralInwardDate < date('Y-m-d', strtotime('-60 days'))) ? $stocks : 0;
            $stocks4 = ($row->materiralInwardDate >= date('Y-m-d', strtotime('-180 days')) && $row->materiralInwardDate < date('Y-m-d', strtotime('-90 days'))) ? $stocks : 0;
            $stocks5 = ($row->materiralInwardDate >= date('Y-m-d', strtotime('-365 days')) && $row->materiralInwardDate < date('Y-m-d', strtotime('-180 days'))) ? $stocks : 0;
            $stocks6 = ($row->materiralInwardDate <= date('Y-m-d', strtotime('-1 year'))) ? $stocks : 0;
    
            // Calculate total stock and total value
            $total_stock = $stocks1 + $stocks2 + $stocks3 + $stocks4 + $stocks5 + $stocks6;
            $total_value = ($stocks1 * $row->rate) + ($stocks2 * $row->rate) + ($stocks3 * $row->rate) + ($stocks4 * $row->rate) + ($stocks5 * $row->rate) + ($stocks6 * $row->rate);
    
            // Aggregate the sums into $aggregatedData
            $aggregatedData[$spare_item_code]['total_value30'] += round($stocks1 * $row->rate, 2);
            $aggregatedData[$spare_item_code]['total_value60'] += round($stocks2 * $row->rate, 2);
            $aggregatedData[$spare_item_code]['total_value90'] += round($stocks3 * $row->rate, 2);
            $aggregatedData[$spare_item_code]['total_value180'] += round($stocks4 * $row->rate, 2);
            $aggregatedData[$spare_item_code]['total_value365'] += round($stocks5 * $row->rate, 2);
            $aggregatedData[$spare_item_code]['previousYearValue'] += round($stocks6 * $row->rate, 2);
            $aggregatedData[$spare_item_code]['total_value'] += round($total_value, 2);
        }
    
        // Prepare final HTML output
        foreach ($aggregatedData as $data) 
        {
            if($data['total_value'] > 0)
            {
                $html[] = [
                    'srno' => count($html) + 1,
                    'spare_item_code' => $data['spare_item_code'],
                    'item_name' => $data['item_name'],
                    'total_value30' => money_format("%!.0n", $data['total_value30']),
                    'total_value60' => money_format("%!.0n", $data['total_value60']),
                    'total_value90' => money_format("%!.0n", $data['total_value90']),
                    'total_value180' => money_format("%!.0n", $data['total_value180']),
                    'total_value365' => money_format("%!.0n", $data['total_value365']),
                    'previousYearValue' => money_format("%!.0n", $data['previousYearValue']),
                    'total_value' => money_format("%!.0n", $data['total_value']),
                ];
            }
        }
    
        $jsonData = json_encode($html);
        return response()->json(['html' => $jsonData, 'currentDate' => $currentDate]);
    }
    
    public function SyncMaterialStock()
    {
    
         DB::table('dump_Material_stock_data')->delete();
      
         DB::table('syncronization_time_mgmt')->where('stmt_type','=',2)->update(['end_time' => "", 'status' => 0]);
         
         $MaterialData =  DB::SELECT("select materialInwardMaster.materiralInwardDate,materialInwardMaster.materiralInwardCode,materialInwardMaster.po_code as po_no,MaterialInwardDetail.spare_item_code,
            ledger_master.ac_name, sum(MaterialInwardDetail.item_qty) as grn_qty,MaterialInwardDetail.item_rate as rate,MaterialInwardDetail.rack_id,job_status_master.job_status_name,spare_purchase_order.po_status,
            materialInwardMaster.po_code,MaterialInwardDetail.amount as amount,
            ledger_master.ac_name as suplier_name,spare_item_master.dimension,spare_item_master.item_name,
            spare_item_master.color_name,spare_item_master.item_description
            from MaterialInwardDetail
            left join materialInwardMaster on materialInwardMaster.materiralInwardCode=MaterialInwardDetail.materiralInwardCode
            left join ledger_master on ledger_master.ac_code=MaterialInwardDetail.ac_code
            left join spare_item_master on spare_item_master.spare_item_code=MaterialInwardDetail.spare_item_code
            left join spare_purchase_order ON spare_purchase_order.pur_code = MaterialInwardDetail.po_code 
            left join job_status_master ON job_status_master.job_status_id = spare_purchase_order.po_status 
            WHERE spare_item_master.cat_id !=4 AND spare_item_master.class_id != 94 group by MaterialInwardDetail.po_code,MaterialInwardDetail.spare_item_code,MaterialInwardDetail.materiralInwardCode");
            
          foreach($MaterialData as $row)
          {  
                $buyerData = DB::SELECT("select LM1.ac_name as buyer_name,spare_purchase_order_detail.job_status_id,job_status_master.job_status_name FROM spare_purchase_order_detail 
                                                                    INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = spare_purchase_order_detail.sales_order_no 
                                                                    INNER JOIN ledger_master as LM1 ON LM1.ac_code = buyer_purchse_order_master.Ac_code 
                                                                    LEFT JOIN job_status_master ON job_status_master.job_status_id = spare_purchase_order_detail.job_status_id 
                                                                    WHERE spare_purchase_order_detail.pur_code = '". $row->po_no."' AND spare_purchase_order_detail.spare_item_code=".$row->spare_item_code."
                                                                    GROUP BY spare_purchase_order_detail.pur_code");
                                                                    
                $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
                if($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "")
                {
                    $job_status_id =  1;
                    $po_status = "Moving";
                }
                else
                {
                    $job_status_id = 2;
                    $po_status = "Non Moving";
                }
                
                $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
                $materiralInwardDate = str_replace('"', "", $row->materiralInwardDate);
                $suplier_name = str_replace('"', "", $row->suplier_name);  
                $po_no = str_replace('"', "", $row->po_no);
                $materiralInwardCode = str_replace('"', "", $row->materiralInwardCode); 
                $spare_item_code = str_replace('"', "", $row->spare_item_code); 
                $item_name = str_replace('"', "", $row->item_name);
                $color =  "";
                $item_description = str_replace('"', "", $row->item_description); 
                $grn_qty = str_replace('"', "", $row->grn_qty);
                $rate = str_replace('"', "", $row->rate);
                $rack_id = str_replace('"', "", $row->rack_id);
                $ac_code = 0;
                $suplier_id = 0;
                $unit_id = 0;
                $amount = str_replace('"', "", $row->amount);  

                $outwardData = DB::SELECT("select sum(item_qty) as outward_qty,tout_date FROM MaterialOutwardDetail WHERE po_code ='".$row->po_no."' AND spare_item_code=".$row->spare_item_code);
                $ind_outward_qty1 = "";
                $outwardData1 = DB::SELECT("select item_qty as outward_qty,tout_date FROM MaterialOutwardDetail WHERE po_code ='".$row->po_no."' AND spare_item_code=".$row->spare_item_code);
                
                foreach($outwardData1 as $OD)
                {
                    $ind_outward_qty1 = $OD->tout_date."=>".$OD->outward_qty.",".$ind_outward_qty1;
                }
                
                $tout_date = isset($outwardData[0]->tout_date) ? $outwardData[0]->tout_date : "";
                $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0; 
                $ind_outward_qty = rMaterial($ind_outward_qty1,","); 
                
               //DB::enableQueryLog(); 
               
                 DB::SELECT('INSERT INTO dump_Material_stock_data(materiralInwardDate,tout_date,suplier_name,buyer_name,po_no,spare_item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,materiralInwardCode,grn_qty,outward_qty,ind_outward_qty,amount)
                        select "'.$materiralInwardDate.'","'.$tout_date.'","'.$suplier_name.'","'.$buyerName.'","'.$po_no.'","'.$spare_item_code.'","'.addslashes($item_name).'","'.$rate.'", "'.$color.'",
                                "'.addslashes($item_description).'", "'.$po_status.'", "'.$job_status_id.'", "'.$rack_id.'","'.$ac_code.'","'.$suplier_id.'","'.$unit_id.'","'.$materiralInwardCode.'","'.$grn_qty.'","'.$outward_qty.'","'.$ind_outward_qty.'","'.$amount.'"');
                //dd(DB::getQueryLog());
          }
        
        date_default_timezone_set("Asia/Calcutta");
        DB::table('syncronization_time_mgmt')->where('stmt_type','=',2)->update(['end_time' => date("H:i", time()), 'status' => 1,'sync_table'=>0]);
        echo json_encode('ok');
    }
 
    public function getPoForMaterialInward(Request $request)
    {
        $po_code= base64_decode($request->po_code);
        $itemlist=DB::table('spare_item_master')->where('spare_item_master.cat_id','!=','1')->where('spare_item_master.delflag','0')->get();
        $unitlist=DB::table('unit_master')->where('unit_master.delflag','0')->get();
        $RackList=DB::table('rack_master')->where('rack_master.delflag','0')->get();
        //DB::enableQueryLog();
        $data=DB::select(DB::raw("SELECT classification_master.class_name,spare_purchase_order.sr_no,  spare_purchase_order_detail.pur_code, spare_purchase_order_detail.pur_date, spare_purchase_order_detail.Ac_code, 
         spare_purchase_order_detail.spare_item_code,spare_item_master.item_description, spare_item_master.cat_id,spare_item_master.class_id, spare_purchase_order_detail.hsn_code,
         spare_purchase_order_detail.unit_id,spare_purchase_order_detail.item_rate, sum(spare_purchase_order_detail.item_qty)  as totalQty FROM spare_purchase_order_detail
         inner join spare_purchase_order on spare_purchase_order.pur_code=spare_purchase_order_detail.pur_code
         LEFT join spare_item_master on spare_item_master.spare_item_code=spare_purchase_order_detail.spare_item_code 
         LEFT join classification_master ON classification_master.class_id = spare_item_master.class_id
         where spare_purchase_order.pur_code='".$po_code."' GROUP BY spare_purchase_order_detail.spare_item_code"));
        //dd(DB::getQueryLog());     
         
        $html='';

        $html .='<div class="table-wrap" id="trimInward">
                <div class="table-responsive">
                       <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                <thead>
                <tr>
                    <th>SrNo</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Classification</th>
                    <th>UOM</th>
                    <th>To Be Received</th>
                    <th>Qty</th>
                    <th>Item Rate</th>
                    <th>Amount</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody>';
                $no=1;
                foreach ($data as $value) 
                {
                    
                    $InwardTrims = DB::select("SELECT   
                        materialInwardDetail.spare_item_code, unit_master.unit_name, spare_item_master.item_name, spare_item_master.item_description, class_name,
                        (select sum(item_qty) as po_item_qty from spare_purchase_order_detail where spare_purchase_order_detail.pur_code='".$value->pur_code."') as po_item_qty,
                        sum(materialInwardDetail.`item_qty`) as item_qty , materialInwardDetail.item_rate, materialInwardDetail.unit_id
                        FROM `materialInwardDetail` 
                        inner join materialInwardMaster on materialInwardMaster.materiralInwardCode=materialInwardDetail.materiralInwardCode 
                        inner join spare_item_master on spare_item_master.spare_item_code=materialInwardDetail.spare_item_code
                        inner join unit_master on unit_master.unit_id=materialInwardDetail.unit_id
                        inner join classification_master on classification_master.class_id=spare_item_master.class_id
                        where materialInwardMaster.po_code='".$value->pur_code."' and materialInwardDetail.spare_item_code='".$value->spare_item_code."'
                        group by materialInwardMaster.po_code, materialInwardDetail.spare_item_code");
                
                   $toBeReceived = (isset($InwardTrims[0]->po_item_qty) ?  $InwardTrims[0]->po_item_qty : 0) - (isset($InwardTrims[0]->item_qty) ?  $InwardTrims[0]->item_qty : 0);
                   
                   $html .='<tr>';
                    
                    $html .='
                    <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
                     
                     
                      
                     <td> <span onclick="openmodal('.$value->sr_no.','.$value->spare_item_code.');" style="color:#556ee6; cursor: pointer;">'.$value->spare_item_code.'</span></td>
                    <td> <select name="spare_item_codes[]"  id="spare_item_codes" style="width:300px; height:30px;" disabled >
                    <option value="">--Select Item--</option>';
                    
                    foreach($itemlist as  $row1)
                    {
                        $html.='<option value="'.$row1->spare_item_code.'"';
                    
                        $row1->spare_item_code == $value->spare_item_code ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$row1->item_name.'</option>';
                    }
                     
                    $html.='</select></td> ';
                     $html.='<td><input type="text" value="'.$value->class_name.'" style="width:250px;height:30px;" readOnly >';
                    $html .='<td> <select name="unit_ids[]"  id="unit_ids" style="width:80px; height:30px;" disabled >
                    <option value="">--Select Unit--</option>';
                    
                    foreach($unitlist as  $rowunit)
                    {
                        $html.='<option value="'.$rowunit->unit_id.'"';
                    
                        $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$rowunit->unit_name.'</option>';
                    }
                     
                    $html.='</select></td>';
                    $html.='<td><input type="text" class="toBeReceived"  name="toBeReceived[]" value="'.round($toBeReceived,2).'" id="toBeReceived" style="width:80px;height:30px;" readOnly/>
                            </td>';
                    $html.='<td><input type="text" class="QTY"  name="item_qtys[]" onchange="SetQtyToBtn(this);" value="'.round($value->totalQty,2).'" id="item_qty" style="width:80px;height:30px;" required/>
                    <input type="hidden"  name="hsn_code[]" value="'.$value->hsn_code  .'" id="hsn_code" style="width:80px; height:30px;" readOnly required/>
                    </td>';
                    $html.='<td><input type="text"   name="item_rates[]" readOnly  value="'.round($value->item_rate,5).'" id="item_rates" style="width:80px;height:30px;" required/></td>';
                    
                    $html.='<td><input type="text" class="AMT"  name="amounts[]" readOnly  value="'.(round($value->totalQty,2)*round($value->item_rate,2)).'" id="amounts" style="width:80px;height:30px;" required/></td>
                    <td nowrap><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td> 
                    </tr>';
                    $no=$no+1;
                
                }
                
                   $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';
                   $html .='</table>
                   </div>
                </div>';
        return response()->json(['html' => $html]);
    }
    
    
    public function GetSpareItemList()
    {
        $itemlist=DB::table('spare_item_master')->where('spare_item_master.delflag','0')->get();
        //dd(DB::getQueryLog());     
         
        $html='<option value="">--Select--</option>';
       
        foreach($itemlist as  $row)
        {
            $html.='<option value="'.$row->spare_item_code.'">'.$row->item_name.' ('.$row->spare_item_code.')</option>';
        } 
        
        return response()->json(['html' => $html]);
    }
    
    public function GetSpareItemUnit(Request $request)
    {
        $unitData=DB::table('unit_master')->join('spare_item_master','spare_item_master.unit_id', '=', 'unit_master.unit_id')->where('spare_item_master.spare_item_code','=', $request->spare_item_code)->first();
        
        $unit_id = $unitData->unit_id;
        
        return response()->json(['unit_id' => $unit_id]);
    }
    
    public function LocationWiseSpareStockReport(Request $request)
    {
        
        $from_date = isset($request->from_date) ? $request-> from_date : '';
        $to_date = isset($request->to_date) ? $request-> to_date : '';
        $spare_item_code = isset($request->spare_item_code) ? $request-> spare_item_code : 0;
        $location_id = isset($request->location_id) ? $request-> location_id : 0;
        $materiralInwardCode = isset($request->materiralInwardCode) ? $request-> materiralInwardCode : "";
        
        $filter = '';
        
        if($from_date != '' && $to_date != '')
        {
            $filter .= ' AND materialInwardDetail.materiralInwardDate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        }
        
        if($spare_item_code > 0)
        {
            $filter .= ' AND materialInwardDetail.spare_item_code = '.$spare_item_code;
        }
        
        if($location_id > 0)
        {
            $filter .= ' AND materialInwardDetail.location_id = '.$location_id;
        }
        
        if($materiralInwardCode != '')
        {
            $filter .= ' AND materialInwardDetail.materiralInwardCode = "'.$materiralInwardCode.'"';
        }
        
        
        $classificationList = DB::SELECT("SELECT classification_master.class_id, classification_master.class_name FROM materialInwardDetail
                                LEFT JOIN spare_item_master ON spare_item_master.spare_item_code = materialInwardDetail.spare_item_code
                                LEFT JOIN classification_master ON classification_master.class_id = spare_item_master.class_id
                                WHERE 1 ".$filter." GROUP BY materialInwardDetail.materiralInwardCode");
 
                        
        $locationList = DB::SELECT("SELECT location_master.loc_id,location_master.location FROM materialInwardDetail 
                        INNER JOIN location_master ON location_master.loc_id = materialInwardDetail.location_id 
                        WHERE 1 ".$filter."  GROUP BY materialInwardDetail.location_id");                
                      
        $itemList = DB::SELECT("SELECT * FROM spare_item_master WHERE delflag=0");
        
        $GRNList = DB::SELECT("SELECT materiralInwardCode FROM materialInwardMaster WHERE delflag=0");
        
        return view('LocationWiseSpareStockReport',compact('classificationList', 'itemList', 'locationList', 'GRNList', 'from_date','to_date','spare_item_code','location_id','materiralInwardCode', 'filter')); 
    } 
    
    public function MaintenanceSparesGRNReport(Request $request)
    {
        $from_date = isset($request->fromDate) ? $request-> fromDate : date("Y-m-01");
        $to_date = isset($request->toDate) ? $request-> toDate : date("Y-m-d");
        $spare_item_code = isset($request->spare_item_code) ? $request-> spare_item_code : 0;
        $location_id = isset($request->location_id) ? $request-> location_id : 0;
        $materiralInwardCode = isset($request->materiralInwardCode) ? $request-> materiralInwardCode : "";
        $po_code = isset($request->po_code) ? $request-> po_code : "";
        
        $filter = '';
        
        if($from_date != '' && $to_date != '')
        {
            $filter .= ' AND materialInwardMaster.materiralInwardDate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        }
        
        if($spare_item_code > 0)
        {
            $filter .= ' AND materialInwardDetail.spare_item_code = '.$spare_item_code;
        }
        
        if($location_id > 0)
        {
            $filter .= ' AND materialInwardMaster.location_id = '.$location_id;
        }
        
        if($materiralInwardCode != '')
        {
            $filter .= ' AND materialInwardMaster.materiralInwardCode = "'.$materiralInwardCode.'"';
        }
        
        if($po_code != '')
        {
            $filter .= ' AND materialInwardMaster.po_code = "'.$po_code.'"';
        }
        
        $MaintenanceSparesItemData = DB::select("SELECT materialInwardDetail.spare_item_code, spare_item_master.item_name
                                        FROM materialInwardMaster 
                                        inner join materialInwardDetail on materialInwardDetail.materiralInwardCode=materialInwardMaster.materiralInwardCode 
                                        inner join spare_item_master on spare_item_master.spare_item_code=materialInwardDetail.spare_item_code
                                        WHERE materialInwardMaster.delflag=0 ".$filter."
                                        group by materialInwardDetail.spare_item_code");
        
        $MaterialInwardList = DB::table('materialInwardMaster')->where('materialInwardMaster.delflag','=', '0')->get(); 
        
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 

        $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->get();

        $POList = SparePurchaseOrderModel::join('materialInwardMaster','materialInwardMaster.po_code', '=', 'spare_purchase_order.pur_code')->where('spare_purchase_order.delflag','=', '0')->get();
        
        return view('MaintenanceSparesGRNReport', compact('MaterialInwardList','MaintenanceSparesItemData','LocationList','itemlist','POList','from_date','to_date','spare_item_code','location_id','materiralInwardCode','po_code'));
    }
    
    public function MaintenanceSpareIssueReport(Request $request)
    {
        $from_date = isset($request->fromDate) ? $request-> fromDate : date("Y-m-01");
        $to_date = isset($request->toDate) ? $request-> toDate : date("Y-m-d");
        $spare_item_code = isset($request->spare_item_code) ? $request-> spare_item_code : 0;
        $location_id = isset($request->location_id) ? $request-> location_id : 0;
        $materiralInwardCode = isset($request->materiralInwardCode) ? $request-> materiralInwardCode : "";
        $po_code = isset($request->po_code) ? $request-> po_code : "";
        
        $filter = '';
        $filter1 = '';
        
        if($from_date != '' && $to_date != '')
        {
            $filter .= ' AND materialoutwardmaster.materialOutwardDate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
            $filter1 .= ' AND materialTransferFromMaster.materialTransferFromDate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        }
        
        if($spare_item_code > 0)
        {
            $filter .= ' AND materialoutwarddetails.spare_item_code = '.$spare_item_code;
            $filter1 .= ' AND materialTransferFromDetails.spare_item_code = '.$spare_item_code;
        }
        
        if($location_id > 0)
        {
            $filter .= ' AND materialoutwardmaster.loc_id = '.$location_id;
            $filter1 .= ' AND materialTransferFromDetails.from_loc_id = '.$location_id;
        }
        
        if($materiralInwardCode != '')
        {
            $filter .= ' AND materialInwardDetail.materiralInwardCode = "'.$materiralInwardCode.'"';
            $filter1 .= ' AND materialInwardDetail.materiralInwardCode = "'.$materiralInwardCode.'"';
        }            
        
        if($po_code != '')
        {
            $filter .= ' AND materialInwardDetail.po_code = "'.$po_code.'"';
            $filter1 .= ' AND materialInwardDetail.po_code = "'.$po_code.'"';
        }
        
        $MaintenanceSparesItemData = DB::select("SELECT 
                                    materialoutwarddetails.spare_item_code, 
                                    spare_item_master.item_name
                                FROM 
                                    materialoutwardmaster 
                                INNER JOIN 
                                    materialoutwarddetails ON materialoutwarddetails.materialOutwardCode = materialoutwardmaster.materialOutwardCode 
                                LEFT JOIN 
                                    materialInwardDetail ON materialInwardDetail.materiralInwardCode = materialoutwarddetails.materiralInwardCode
                                INNER JOIN 
                                    spare_item_master ON spare_item_master.spare_item_code = materialoutwarddetails.spare_item_code
                                WHERE 
                                    materialoutwardmaster.delflag = 0 $filter
                                GROUP BY 
                                    materialoutwarddetails.spare_item_code
                            
                                UNION
                            
                                SELECT 
                                    materialTransferFromDetails.spare_item_code, 
                                    spare_item_master.item_name
                                FROM 
                                    materialTransferFromMaster 
                                INNER JOIN 
                                    materialTransferFromDetails ON materialTransferFromDetails.materialTransferFromCode = materialTransferFromMaster.materialTransferFromCode
                                LEFT JOIN 
                                    materialInwardDetail ON materialInwardDetail.materiralInwardCode = materialTransferFromDetails.materiralInwardCode
                                INNER JOIN 
                                    spare_item_master ON spare_item_master.spare_item_code = materialTransferFromDetails.spare_item_code
                                WHERE 
                                    materialTransferFromMaster.delflag = 0 $filter1
                                GROUP BY 
                                    materialTransferFromDetails.spare_item_code
                            ");

        
        $MaterialInwardList = DB::table('materialInwardMaster')->where('materialInwardMaster.delflag','=', '0')->get(); 
        
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 

        $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->get();

        $POList = SparePurchaseOrderModel::join('materialInwardMaster','materialInwardMaster.po_code', '=', 'spare_purchase_order.pur_code')->where('spare_purchase_order.delflag','=', '0')->get();
        
        return view('MaintenanceSpareIssueReport', compact('MaterialInwardList','MaintenanceSparesItemData','LocationList','itemlist','POList','from_date','to_date','spare_item_code','location_id','materiralInwardCode','po_code','filter','filter1'));
    }
    
    public function MaintenanceSparesStockReport(Request $request)
    {
        // $from_date = isset($request->fromDate) ? $request-> fromDate : '';
        // $to_date = isset($request->toDate) ? $request-> toDate : '';
        // $spare_item_code = isset($request->spare_item_code) ? $request-> spare_item_code : 0;
        // $location_id = isset($request->location_id) ? $request-> location_id : 0;
        // $materiralInwardCode = isset($request->materiralInwardCode) ? $request-> materiralInwardCode : "";
        // $po_code = isset($request->po_code) ? $request-> po_code : "";
        
        // $filter = '';
        
        // if($from_date != '' && $to_date != '')
        // {
        //     $filter .= ' AND materialInwardMaster.materiralInwardDate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        // }
        
        // if($spare_item_code > 0)
        // {
        //     $filter .= ' AND materialInwardDetail.spare_item_code = '.$spare_item_code;
        // }
        
        // if($location_id > 0)
        // {
        //     $filter .= ' AND materialInwardMaster.location_id = '.$location_id;
        // }
        
        // if($materiralInwardCode != '')
        // {
        //     $filter .= ' AND materialInwardMaster.materiralInwardCode = "'.$materiralInwardCode.'"';
        // }
        
        // if($po_code != '')
        // {
        //     $filter .= ' AND materialInwardMaster.po_code = "'.$po_code.'"';
        // }
        
        // $MaintenanceSparesData = DB::select("SELECT materialInwardMaster.*,materialInwardDetail.spare_item_code, unit_master.unit_name, spare_item_master.item_name, spare_item_master.item_description,spare_item_master.dimension, class_name,
        //                                 sum(materialInwardDetail.item_qty) as item_qty , materialInwardDetail.item_rate, materialInwardDetail.unit_id,location_master.location,ledger_master.ac_short_name as ac_short_name,
        //                                 materialoutwarddetails.materialOutwardCode,materialoutwarddetails.materialOutwardDate,sum(materialoutwarddetails.item_qty) as outward_qty,materialTransferFromDetails.materialTransferFromCode, 
        //                                 materialTransferFromDetails.materialTransferFromDate,sum(materialTransferFromDetails.item_qty) as transfer_from_qty,materialTransferFromInwardDetails.materialTransferFromInwardCode,materialTransferFromInwardDetails.materialTransferFromInwardDate,
        //                                 sum(materialTransferFromInwardDetails.item_qty) as transfer_from_inward_qty
        //                                 FROM materialInwardMaster 
        //                                 inner join materialInwardDetail on materialInwardDetail.materiralInwardCode=materialInwardMaster.materiralInwardCode 
        //                                 left join materialoutwarddetails on materialoutwarddetails.spare_item_code=materialInwardDetail.spare_item_code AND materialoutwarddetails.loc_id=materialInwardDetail.location_id
        //                                 left join materialTransferFromDetails on materialTransferFromDetails.spare_item_code=materialInwardDetail.spare_item_code AND materialTransferFromDetails.from_loc_id=materialInwardDetail.location_id
        //                                 left join materialTransferFromInwardDetails on materialTransferFromInwardDetails.spare_item_code=materialInwardDetail.spare_item_code AND materialTransferFromInwardDetails.from_loc_id=materialInwardDetail.location_id
        //                                 inner join spare_item_master on spare_item_master.spare_item_code=materialInwardDetail.spare_item_code
        //                                 inner join unit_master on unit_master.unit_id=materialInwardDetail.unit_id
        //                                 inner join classification_master on classification_master.class_id=spare_item_master.class_id
        //                                 inner join location_master on location_master.loc_id=materialInwardMaster.location_id
        //                                 inner join ledger_master on ledger_master.ac_code=materialInwardMaster.Ac_code 
        //                                 WHERE materialInwardMaster.delflag=0 ".$filter."
        //                                 group by materialInwardMaster.po_code, materialInwardDetail.spare_item_code");
        
        
        // $MaterialInwardList = DB::table('materialInwardMaster')->where('materialInwardMaster.delflag','=', '0')->get(); 
        
        // $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 

        // $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->get();

        // $POList = SparePurchaseOrderModel::join('materialInwardMaster','materialInwardMaster.po_code', '=', 'spare_purchase_order.pur_code')->where('spare_purchase_order.delflag','=', '0')->get();
        
        $from_date = isset($request->fromDate) ? $request-> fromDate : '';
        $to_date = isset($request->toDate) ? $request-> toDate : '';
        $spare_item_code = isset($request->spare_item_code) ? $request-> spare_item_code : 0;
        $location_id = isset($request->location_id) ? $request-> location_id : 0;
        $materiralInwardCode = isset($request->materiralInwardCode) ? $request-> materiralInwardCode : "";
        $po_code = isset($request->po_code) ? $request-> po_code : "";
        
        $filter = '';
        
        if($from_date != '' && $to_date != '')
        {
            $filter .= ' AND materialInwardMaster.materiralInwardDate BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        }
        
        if($spare_item_code > 0)
        {
            $filter .= ' AND materialInwardDetail.spare_item_code = '.$spare_item_code;
        }
        
        if($location_id > 0)
        {
            $filter .= ' AND materialInwardMaster.location_id = '.$location_id;
        }
        
        if($materiralInwardCode != '')
        {
            $filter .= ' AND materialInwardMaster.materiralInwardCode = "'.$materiralInwardCode.'"';
        }
        
        if($po_code != '')
        {
            $filter .= ' AND materialInwardMaster.po_code = "'.$po_code.'"';
        }
        
        $MaintenanceSparesData = DB::select("SELECT materialInwardDetail.spare_item_code, spare_item_master.item_name
                                        FROM materialInwardMaster 
                                        inner join materialInwardDetail on materialInwardDetail.materiralInwardCode=materialInwardMaster.materiralInwardCode 
                                        inner join spare_item_master on spare_item_master.spare_item_code=materialInwardDetail.spare_item_code
                                        WHERE materialInwardMaster.delflag=0 ".$filter."
                                        group by materialInwardDetail.spare_item_code");
        
        $MaterialInwardList = DB::table('materialInwardMaster')->where('materialInwardMaster.delflag','=', '0')->get(); 
        
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 

        $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->get();

        $POList = SparePurchaseOrderModel::join('materialInwardMaster','materialInwardMaster.po_code', '=', 'spare_purchase_order.pur_code')->where('spare_purchase_order.delflag','=', '0')->get();
        
        return view('MaintenanceSparesStockReport', compact('MaterialInwardList','MaintenanceSparesData','LocationList','itemlist','POList','from_date','to_date','spare_item_code','location_id','materiralInwardCode','po_code'));
        
       // return view('MaintenanceSparesStockReport', compact('MaterialInwardList','MaintenanceSparesData','LocationList','itemlist','POList','from_date','to_date','spare_item_code','location_id','materiralInwardCode','po_code'));
    }
    
    public function SpareItemLedgerReport(Request $request)
    {
        $from_date = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');
        $to_date = isset($request->toDate) ? $request->toDate : date('Y-m-d');
        $spare_item_code = isset($request->spare_item_code) ? $request->spare_item_code : 0;
        $location_id = isset($request->location_id) ? $request->location_id : 0;
        $materiralInwardCode = isset($request->materiralInwardCode) ? $request->materiralInwardCode : "";
        $po_code = isset($request->po_code) ? $request->po_code : "";
        $clear = isset($request->clear) ? $request->clear : 0;

        
        $MaterialInwardList = DB::table('materialInwardMaster')->where('materialInwardMaster.delflag','=', '0')->get(); 
        
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 

        $itemlist = DB::table('spare_item_master')->where('spare_item_master.delflag','0')->get();

        $POList = SparePurchaseOrderModel::join('materialInwardMaster','materialInwardMaster.po_code', '=', 'spare_purchase_order.pur_code')->where('spare_purchase_order.delflag','=', '0')->get();
        
        return view('SpareItemLedgerReport', compact('MaterialInwardList','LocationList','itemlist','POList','from_date','to_date','spare_item_code','location_id','materiralInwardCode','po_code','clear')); 
    }  
    public function SpareItemLedgerList(Request $request)
    { 
        return view('SpareItemLedgerList'); 
    }
}
