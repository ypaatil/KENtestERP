<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\SeasonModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\SizeModel;
use Illuminate\Support\Facades\DB;
use App\Models\MaterialTransferFromInwardModel;
use App\Models\MaterialTransferFromInwardDetailModel;
use App\Models\MaterialTransferFromModel;
use App\Models\MaterialTransferFromDetailModel;
use App\Models\SparePurchaseOrderModel;
use App\Models\LocationModel;
use Session;

class MaterialTransferFromInwardController extends Controller
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
        ->where('form_id', '200')
        ->first();
        
        $MaterialTransferFromInwardList = MaterialTransferFromInwardModel::join('usermaster', 'usermaster.userId', '=', 'materialTransferFromInward.userId')
                ->leftJoin('location_master as L1', 'L1.loc_id', '=', 'materialTransferFromInward.from_loc_id')
                ->leftJoin('location_master as L2', 'L2.loc_id', '=', 'materialTransferFromInward.to_loc_id')  
                ->orderBy('materialTransferFromInward.materialTransferFromInwardCode', 'DESC')
                ->get(['materialTransferFromInward.*', 'usermaster.username', 'L1.location as from_location', 'L2.location as to_location']);
                
        return view('materialTransferFromInwardList', compact('chekform', 'MaterialTransferFromInwardList'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $materialFromData = DB::table('materialTransferFromMaster')->where('delflag', '=', 0)->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get(); 
        return view('materialTransferFromInward',compact('LocationList', 'itemlist', 'materialFromData'));

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
              ->where('type','=','Material_Transfer_From_Inward')
               ->where('firm_id','=',1)
              ->first();
              
        $materialTransferFromInwardCode=$codefetch->code.'-'.$codefetch->tr_no; 
        
        $data1=array(
                    'materialTransferFromInwardCode'=>$materialTransferFromInwardCode,
                    'materialTransferFromInwardDate'=>$request->materialTransferFromInwardDate, 
                    'materialTransferFromCode'=>$request->materialTransferFromCode, 
                    'from_loc_id'=>$request->from_loc_id, 
                    'to_loc_id'=>$request->to_loc_id, 
                    'driver_name'=>$request->driver_name, 
                    'vehical_no'=>$request->vehical_no, 
                    'totalqty'=>$request->totalqty,  
                    'remark'=>$request->remark, 
                    'delflag'=>0,
                    'userId'=>$request->userId, 
                    'created_at'=>date("Y-m-d H:i:s"),  
                );
             
        MaterialTransferFromInwardModel::insert($data1);
            
        $spare_item_codes = $request->spare_item_codes;
                     
        for($x=0; $x<count($spare_item_codes); $x++) 
        {
                $data2=array(
                     'materialTransferFromInwardCode' =>$materialTransferFromInwardCode,
                     'materialTransferFromInwardDate' => $request->materialTransferFromInwardDate,
                     'materialTransferFromCode'=>$request->materialTransferFromCode, 
                     'materiralInwardCode'=>$request->materiralInwardCode[$x], 
                     'spare_item_code' => $request->spare_item_codes[$x],
                     'item_qty' => $request->item_qtys[$x],
                     'stock_qty' => $request->stock_qty[$x],
                     'from_loc_id'=>$request->from_loc_id,
                     'to_loc_id'=>$request->to_loc_id, 
                );
                
                MaterialTransferFromInwardDetailModel::insert($data2);
        }  
        
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='Material_Transfer_From_Inward'");
            
        $upload_attachment = $request->upload_attachment;
        if(!empty($upload_attachment)) 
        {
            foreach($upload_attachment as $index => $attachmentName) 
            {
                if ($request->hasFile('upload_attachment.' . $index)) {
                    $attachment = $request->file('upload_attachment')[$index];
                    $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                    $location = public_path('uploads/MaterialTransferFromInward/');
                    if (file_exists('uploads/MaterialTransferFromInward/'.$fileName))
                    {
                         $url = "uploads/MaterialTransferFromInward/".$fileName;
                         unlink($url);
                    }
                    $attachment->move($location,$fileName); 
                    DB::table('material_transfer_from_inward_attachment')->insert([ 
                        "materialTransferFromInwardCode"=>$materialTransferFromInwardCode, 
                        "materialTransferFromInwardDate"=>$request->materialTransferFromInwardDate,
                        "attachment_name"=>$request->attachment_name[$index],
                        "upload_attachment"=>$fileName
                    ]);
                }
            }
        } 
        
        return redirect()->route('MaterialTransferFromInward.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialTransferFromInwardModel  $MaterialTransferFromInwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialTransferFromInwardModel $MaterialTransferFromInwardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialTransferFromInwardModel  $MaterialTransferFromInwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $machineList= DB::table('machine_master')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get(); 
        $statusList= DB::table('spare_return_material_status')->where('delflag','=', '0')->get();
        $materialFromData = DB::table('materialTransferFromMaster')->where('delflag', '=', 0)->get(); 

        $materialTransferFromInwardCode=base64_decode($id);
        $MaterialTransferFromInwardMasterList = MaterialTransferFromInwardModel::find($materialTransferFromInwardCode); 

        $materialTransferFromInwardDetailslist = DB::table('materialTransferFromInwardDetails')
            ->select('materialTransferFromInwardDetails.*','spare_item_master.item_description', 'spare_item_master.spare_item_code')
            ->join('spare_item_master', 'spare_item_master.spare_item_code', '=', 'materialTransferFromInwardDetails.spare_item_code')
            ->where('materialTransferFromInwardCode','=',$materialTransferFromInwardCode)
            ->get();

 
        $MaterialTransferFromInwardAttachmentList = DB::table('material_transfer_from_inward_attachment')->where('materialTransferFromInwardCode','=',   $materialTransferFromInwardCode)->get();
        
        return view('materialTransferFromInwardEdit',compact('MaterialTransferFromInwardMasterList','machineList','LocationList','itemlist','materialTransferFromInwardDetailslist', 'statusList', 'materialFromData','MaterialTransferFromInwardAttachmentList'));    
 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialTransferFromInwardModel  $MaterialTransferFromInwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $materialTransferFromInwardCode)
    { 
            $materialTransferFromInwardCode= $request->materialTransferFromInwardCode;
            
            $data1=array(
                'materialTransferFromInwardCode'=>$request->materialTransferFromInwardCode,
                'materialTransferFromCode'=>$request->materialTransferFromCode, 
                'materialTransferFromInwardDate'=>$request->materialTransferFromInwardDate,
                'from_loc_id'=>$request->from_loc_id,
                'to_loc_id'=>$request->to_loc_id, 
                'totalqty'=>$request->totalqty, 
                'remark'=>$request->remark, 
                'delflag'=>0,
                'userId'=>$request->userId, 
                'updated_at'=>date("Y-m-d H:i:s"),  
            );
            
            $Return = MaterialTransferFromInwardModel::findOrFail($materialTransferFromInwardCode);  
            $Return->fill($data1)->save();
            
            
            DB::table('materialTransferFromInwardDetails')->where('materialTransferFromInwardCode',$materialTransferFromInwardCode)->delete(); 
            
           $spare_item_codes= $request->spare_item_codes;
                     
            for($x=0; $x<count($spare_item_codes); $x++) 
            {
                $data2=array(
                     'materialTransferFromInwardCode' =>$request->materialTransferFromInwardCode,
                     'materialTransferFromCode' =>$request->materialTransferFromCode,
                     'materialTransferFromInwardDate' => $request->materialTransferFromInwardDate, 
                     'materiralInwardCode'=>$request->materiralInwardCode[$x],
                     'spare_item_code' => $request->spare_item_codes[$x],
                     'item_qty' => $request->item_qtys[$x],
                     'stock_qty' => $request->stock_qty[$x],
                     'from_loc_id'=>$request->from_loc_id,
                     'to_loc_id'=>$request->to_loc_id, 
                     );
                    
                    MaterialTransferFromInwardDetailModel::insert($data2);
            }  
  
            $upload_attachments = $request->file('upload_attachment');

            if (is_array($upload_attachments)) {
                foreach ($upload_attachments as $index => $attachment) {
                    $attachment_id = $request->attachment_id[$index] ?? null;
            
                    if ($attachment && $attachment->isValid()) {
                        $fileName = time() . '_' . $attachment->getClientOriginalName();
                        $location = public_path('uploads/MaterialTransferFromInward/');
                        $attachment->move($location, $fileName);
            
                        if ($attachment_id) {
                            // Update existing attachment — delete old one
                            $existing = DB::table('material_transfer_from_inward_attachment')
                                ->where('id', $attachment_id)
                                ->first();
            
                            if ($existing) {
                                $oldFilePath = $location . $existing->upload_attachment;
                                if (file_exists($oldFilePath)) {
                                    unlink($oldFilePath); // Delete old file from disk
                                }
            
                                DB::table('material_transfer_from_inward_attachment')
                                    ->where('id', $attachment_id)
                                    ->delete();
                            }
                        }
            
                        // Insert new attachment
                        DB::table('material_transfer_from_inward_attachment')->insert([ 
                            "materialTransferFromInwardCode" => $request->materialTransferFromInwardCode, 
                            "materialTransferFromInwardDate" => $request->materialTransferFromInwardDate,
                            "attachment_name" => $request->attachment_name[$index] ?? 'Unnamed',
                            "upload_attachment" => $fileName
                        ]);
                    }
                }
            } else {
                \Log::warning("No valid files uploaded.");
            }

            
            return redirect()->route('MaterialTransferFromInward.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialTransferFromInwardModel  $MaterialTransferFromInwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($materialTransferFromCode)
    {  
        $materialTransferFromCode1=base64_decode($materialTransferFromCode);
        
        MaterialTransferFromInwardModel::where('materialTransferFromInwardCode',$materialTransferFromCode1)->delete();
        MaterialTransferFromInwardDetailModel::where('materialTransferFromInwardCode',$materialTransferFromCode1)->delete();
     
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function DeleteMaterialTransferFromInwardAttachment(Request $request)
    {
        $attachment = $request->upload_attachment;
    
        if (empty($attachment)) {
            return response()->json(['error' => 'Invalid file name'], 400);
        }
    

        DB::table('material_transfer_from_inward_attachment')
            ->where('materialTransferFromInwardCode', '=', $request->materialTransferFromInwardCode)
            ->where('upload_attachment', '=', $attachment)
            ->delete();
    
        $file_path = public_path('uploads/MaterialTransferFromInward/' . $attachment);
    
        if (is_file($file_path) && file_exists($file_path)) {
            unlink($file_path);
        }
    
        return response()->json(['success' => true], 200);
    }
    
    public function GetMachineDetails(Request $request)
    {
        $machine_id = $request->machine_id;
        $machineData = DB::table('machine_master')->where('MachineID', '=', $machine_id)->first();
        return $machineData;
    }
    
    public function GetItemDescriptionForMachine(Request $request)
    {
        $itemlist=DB::table('spare_item_master')->where('spare_item_code','=',$request->spare_item_code)->first();
        
        return response()->json(['spare_item_code'=> $itemlist->spare_item_code ,'item_description' => $itemlist->item_description]); 
    }
    
    public function GetPOListFromSpareItemCode(Request $request)
    { 
        
        $Data = DB::SELECT("SELECT pur_code FROM purchaseorder_detail WHERE spare_item_code=".$request->spare_item_code." GROUP BY pur_code");

        $html = '<option value="">--Select--</option>';
        foreach($Data as $row)
        {
            $html .='<option value="'.$row->pur_code.'">'.$row->pur_code.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 

    public function GetMaterialTransferFromData(Request $request)
    {
        $srno = 1;
        $html = '';
        $materialMasterData = DB::table('materialTransferFromMaster')->select('materialTransferFromMaster.*')
                        ->where('materialTransferFromCode', '=', $request->materialTransferFromCode)
                        ->first();
                        
        $materialData = DB::table('materialTransferFromDetails')->select('materialTransferFromDetails.*', 'spare_item_master.item_name', 'spare_item_master.item_description')
                        ->join('spare_item_master', 'spare_item_master.spare_item_code', '=', 'materialTransferFromDetails.spare_item_code')
                        ->where('materialTransferFromCode', '=', $request->materialTransferFromCode)
                        ->get();
                        
                                        
        foreach($materialData as $row)
        {
             
            // Fetch the total inward quantity
            $inwardQty = DB::table('materialTransferFromDetails')
                ->where('spare_item_code', $row->spare_item_code)
                ->where('materialTransferFromCode', $row->materialTransferFromCode)
                ->sum('item_qty');
        
            // Fetch the total outward quantity
            $outwardQty = DB::table('materialTransferFromInwardDetails')
                ->where('spare_item_code', $row->spare_item_code)
                ->where('materialTransferFromCode', $row->materialTransferFromCode)
                ->sum('item_qty');
        
        
            // Calculate stock
            $stock = $inwardQty - $outwardQty; 
        
            $html .= '<tr class="tr_clone">
                          <td><input type="text" name="id[]" value="'.($srno++).'" id="id" style="width:50px;" disabled /></td>
                          <td><input type="text" name="item_name[]" value="'.($row->item_name).'" id="item_name" style="width:200px;" disabled /></td>
                          <td class="spare_item_code"><input type="text" name="spare_item_codes[]" value="'.($row->spare_item_code).'" id="item_code" style="width:200px;" disabled /></td>
                          <td class="item_desc">'.$row->item_description.'</td> 
                          <td><input type="text" class="materiralInwardCode"  name="materiralInwardCode[]"   value="'.$row->materiralInwardCode.'" id="materiralInwardCode" style="width:160px;"  disabled  /></td>
                          <td><input type="number" step="any" class="stock"  name="stock_qty[]"   value="'.$stock.'" id="stock_qty" style="width:80px;"  disabled  /></td>
                          <td><input type="number" step="any" class="QTY"  name="item_qtys[]"  max="'.$stock.'"  value="0" id="item_qty" style="width:80px;" onkeyup="mycalc();CheckStock(this);"  />
                          </td>
                    </tr>';
        }
        return response()->json(['html' => $html, 'materialMasterData' => $materialMasterData]);
    }
    
}
