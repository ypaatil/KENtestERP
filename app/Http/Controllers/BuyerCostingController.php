<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Taluka;
use Illuminate\Http\Request;
use App\Models\BuyerCostingMasterModel;
 use App\Models\BuyerFabricCostingDetailModel;
use App\Models\BuyerSewingCostingDetailModel;
use App\Models\BuyerPackingCostingDetailModel;
use App\Models\BuyerCostingAttachementModel;
use App\Models\OrderGroupModel;
use App\Models\CurrencyModel;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use Session;

class BuyerCostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $BuyerCostingData = BuyerCostingMasterModel::leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_costing_master.og_id')
            ->leftJoin('usermaster', 'usermaster.userId', '=', 'buyer_costing_master.userId')
            ->leftJoin('currency_master', 'currency_master.cur_id', '=', 'buyer_costing_master.cur_id')
            ->where('buyer_costing_master.delflag', '=', '0')
            ->orderByRaw("CASE 
                              WHEN buyer_costing_master.sr_no LIKE '%-REV-%' THEN 1  -- Matches any '-REV-'
                              ELSE 2 
                          END")
            ->orderBy('buyer_costing_master.sr_no', 'asc')
            ->get(['buyer_costing_master.*', 'order_group_master.*', 'currency_master.*', 'usermaster.username']);
                
        return view('BuyerCostingList', compact('BuyerCostingData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $OrderGroupList = OrderGroupModel::join('order_group_auth', "order_group_auth.og_id","=","order_group_master.og_id")->where('order_group_auth.username','=',Session::get('username'))->WhereIn("order_group_master.og_id",[1,2])->where('order_group_master.delflag','=', '0')->get();

        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        
        return view('BuyerCostingMaster', compact('OrderGroupList', 'CurrencyList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        
            $data1=array( 
                'entry_date'=>$request->entry_date, 
                'buyer_name'=>$request->buyer_name,
                'brand_name'=>$request->brand_name,
                'inr_rate'=>$request->inr_rate, 
                'exchange_rate'=>$request->exchange_rate, 
                'fob_rate'=>$request->fob_rate, 
                'total_qty'=>$request->total_qty,
                'total_value'=>$request->total_value, 
                'style_name'=>$request->style_name,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,   
                'fabric_value'=>$request->fabric_value,
                'cur_id'=>$request->cur_id,
                'og_id'=>$request->og_id, 
                'fabric_per'=>$request->fabric_per,
                'sewing_trims_value'=>$request->sewing_trims_value,
                'sewing_trims_per'=>$request->sewing_trims_per,
                'packing_trims_value'=>$request->packing_trims_value, 
                'packing_trims_per'=>$request->packing_trims_per, 
                'production_value'=>$request->production_value,
                'production_per'=>$request->production_per,
                'other_value'=>$request->other_value,
                'other_per'=>$request->other_per,      
                'transport_value'=>$request->transport_value,      
                'transport_per'=>$request->transport_per,      
                'agent_commission_value'=>$request->agent_commission_value,
                'agent_commission_per'=>$request->agent_commission_per,
                'dbk_value'=>$request->dbk_value, 
                'dbk_per'=>$request->dbk_per, 
                'dbk_value1'=>$request->dbk_value1,
                'dbk_per1'=>$request->dbk_per1, 
                'printing_value'=>$request->printing_value,
                'printing_per'=>$request->printing_per,
                'embroidery_value'=>$request->embroidery_value,
                'embroidery_per'=>$request->embroidery_per,
                'ixd_value'=>$request->ixd_value,
                'ixd_per'=>$request->ixd_per,
                'total_making_value'=>$request->total_making_value,
                'total_making_per'=>$request->total_making_per,
                'garment_reject_value'=>$request->garment_reject_value,  
                'garment_reject_per'=>$request->garment_reject_per,   
                'testing_charges_value'=>$request->testing_charges_value, 
                'testing_charges_per'=>$request->testing_charges_per,    
                'finance_cost_value'=>$request->finance_cost_value,   
                'finance_cost_per'=>$request->finance_cost_per,   
                'extra_value'=>$request->extra_value,   
                'extra_per'=>$request->extra_per,   
                'total_cost_value'=>isset($request->total_cost_value) ? ($request->total_cost_value) : 0,
                'total_cost_per'=>isset($request->total_cost_per) ? ($request->total_cost_per) : 0,
                'profit_value'=>isset($request->profit_value) ? ($request->profit_value) : 0,
                'profit_per'=>isset($request->profit_per) ? ($request->profit_per) : 0,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0', 
                'created_at'=>date("Y-m-d H:i:s")
            );
         
            BuyerCostingMasterModel::insert($data1);
            
            $retId = DB::table('buyer_costing_master')->max('sr_no');
            $item_name = $request->item_name;
            if(count($item_name)>0)
            {
            
                for($x=0; $x<count($item_name); $x++) 
                { 
                    $data2 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_name[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumption[$x], 
                        'rate_per_unit'=>$request->rate_per_unit[$x],
                        'wastage'=>$request->wastage[$x],
                        'total_amount'=>$request->total_amount[$x] 
                    );
                    BuyerFabricCostingDetailModel::insert($data2);
                } 
            }
            
            $item_names = $request->item_names;
            if(count($item_names)>0)
            {
            
                for($x=0; $x<count($item_names); $x++) 
                { 
                    $data3 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_names[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptions[$x], 
                        'rate_per_unit'=>$request->rate_per_units[$x],
                        'wastage'=>$request->wastages[$x],
                        'total_amount'=>$request->total_amounts[$x] 
                    );
                    BuyerSewingCostingDetailModel::insert($data3);
                } 
            }
            
            $item_namess = $request->item_namess;
            if(count($item_namess)>0)
            {
            
                for($x=0; $x<count($item_namess); $x++) 
                { 
                    $data4 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_namess[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptionss[$x], 
                        'rate_per_unit'=>$request->rate_per_unitss[$x],
                        'wastage'=>$request->wastagess[$x],
                        'total_amount'=>$request->total_amountss[$x] 
                    );
                    BuyerPackingCostingDetailModel::insert($data4);
                } 
            }
            
            $attachmentNames = $request->attachment_name;
            if (count($attachmentNames) > 0) 
            {
                foreach ($attachmentNames as $index => $attachmentName) 
                {
                    if ($request->hasFile('attachment_image.' . $index)) {
                        $attachment = $request->file('attachment_image')[$index];
                        $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                        $location = public_path('uploads/BuyerCosting/');
                        if (file_exists('uploads/BuyerCosting/'.$fileName))
                        {
                             $url = "uploads/BuyerCosting/".$fileName;
                             unlink($url);
                        }
                        $attachment->move($location,$fileName);
                 
                        if($fileName != '')
                        {  
                            $files = new File();
                            $files->sr_no = $retId;
                            $files->attachment_name =  $request->attachment_name[$index];
                            $files->attachment_image = $fileName;
                            $files->save(); 
                        }
                    }
                }
            }
  
           $fileName1 = '';
    
            if ($request->hasFile('style_image')) 
            {
                $file = $request->file('style_image');
                $fileName1 = $file->getClientOriginalName();

                $file->move(public_path('uploads/BuyerCosting/'), $fileName1);
            } 
            else 
            {
                $fileName1 = $request->input('style_image');
            }
    
            DB::table('buyer_costing_master')->where('sr_no', '=', $retId)->update(['style_image' => $fileName1]);
                    
            return redirect()->route('BuyerCosting.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function show(Taluka $taluka)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 

        $OrderGroupList = OrderGroupModel::join('order_group_auth', "order_group_auth.og_id","=","order_group_master.og_id")->where('order_group_auth.username','=',Session::get('username'))->WhereIn("order_group_master.og_id",[1,2])->where('order_group_master.delflag','=', '0')->get();

        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        
        $BuyerCostingMasterList = BuyerCostingMasterModel::find($id);
        $BuyerCostingFabricList = BuyerFabricCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingSewingList = BuyerSewingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingPackingList = BuyerPackingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingAttachementList = BuyerCostingAttachementModel::where('sr_no','=',$id)->get();
        return view('BuyerCostingMasterEdit', compact('BuyerCostingMasterList', 'BuyerCostingFabricList', 'BuyerCostingSewingList', 'BuyerCostingPackingList','OrderGroupList','CurrencyList','BuyerCostingAttachementList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
             
            $BuyerCostingList = BuyerCostingMasterModel::findOrFail($id); 
    
            $data1=array( 
                    'entry_date'=>$request->entry_date, 
                    'buyer_name'=>$request->buyer_name,
                    'brand_name'=>$request->brand_name,
                    'inr_rate'=>$request->inr_rate, 
                    'exchange_rate'=>$request->exchange_rate, 
                    'fob_rate'=>$request->fob_rate, 
                    'total_qty'=>$request->total_qty,
                    'total_value'=>$request->total_value, 
                    'style_name'=>$request->style_name,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'sam'=>$request->sam,   
                    'cur_id'=>$request->cur_id,
                    'og_id'=>$request->og_id, 
                    'fabric_value'=>$request->fabric_value, 
                    'fabric_per'=>$request->fabric_per,
                    'sewing_trims_value'=>$request->sewing_trims_value,
                    'sewing_trims_per'=>$request->sewing_trims_per,
                    'packing_trims_value'=>$request->packing_trims_value, 
                    'packing_trims_per'=>$request->packing_trims_per, 
                    'production_value'=>$request->production_value,
                    'production_per'=>$request->production_per,
                    'other_value'=>$request->other_value,
                    'other_per'=>$request->other_per,      
                    'transport_value'=>$request->transport_value,      
                    'transport_per'=>$request->transport_per,      
                    'agent_commission_value'=>$request->agent_commission_value,
                    'agent_commission_per'=>$request->agent_commission_per,
                    'dbk_value'=>$request->dbk_value, 
                    'dbk_per'=>$request->dbk_per, 
                    'dbk_value1'=>$request->dbk_value1,
                    'dbk_per1'=>$request->dbk_per1, 
                    'printing_value'=>$request->printing_value,
                    'printing_per'=>$request->printing_per,
                    'embroidery_value'=>$request->embroidery_value,
                    'embroidery_per'=>$request->embroidery_per,
                    'ixd_value'=>$request->ixd_value,
                    'ixd_per'=>$request->ixd_per,
                    'total_making_value'=>$request->total_making_value,
                    'total_making_per'=>$request->total_making_per,
                    'garment_reject_value'=>$request->garment_reject_value,  
                    'garment_reject_per'=>$request->garment_reject_per,   
                    'testing_charges_value'=>$request->testing_charges_value, 
                    'testing_charges_per'=>$request->testing_charges_per,    
                    'finance_cost_value'=>$request->finance_cost_value,   
                    'finance_cost_per'=>$request->finance_cost_per,   
                    'extra_value'=>$request->extra_value,   
                    'extra_per'=>$request->extra_per,   
                    'total_cost_value'=>$request->total_cost_value,
                    'total_cost_per'=>$request->total_cost_per,
                    'profit_value'=>$request->profit_value,
                    'profit_per'=>$request->profit_per,
                    'narration'=>$request->narration,
                    'userId'=>$request->userId,
                    'delflag'=>'0', 
                    'created_at'=>date("Y-m-d H:i:s")
                );
            $BuyerCostingList->fill($data1)->save();
            //dd(DB::getQueryLog()); 
     
            
            $item_name = $request->item_name;
            if(count($item_name)>0)
            {
                DB::table('fabric_buyer_costing_details')->where('sr_no',$id)->delete();
            
                for($x=0; $x<count($item_name); $x++) 
                { 
                    $data2 = array(
                        'sr_no'=>$id, 
                        'item_name'=>$request->item_name[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumption[$x], 
                        'rate_per_unit'=>$request->rate_per_unit[$x],
                        'wastage'=>$request->wastage[$x],
                        'total_amount'=>$request->total_amount[$x] 
                    );
                    BuyerFabricCostingDetailModel::insert($data2);
                } 
            }
            
            $item_names = $request->item_names;
            if(count($item_names)>0)
            {
            
                DB::table('sewing_buyer_costing_details')->where('sr_no', $id)->delete();
                for($x=0; $x<count($item_names); $x++) 
                { 
                    $data3 = array(
                        'sr_no'=>$id, 
                        'item_name'=>$request->item_names[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptions[$x], 
                        'rate_per_unit'=>$request->rate_per_units[$x],
                        'wastage'=>$request->wastages[$x],
                        'total_amount'=>$request->total_amounts[$x] 
                    );
                    BuyerSewingCostingDetailModel::insert($data3);
                } 
            }
            
            $item_namess = $request->item_namess;
            if(count($item_namess)>0)
            {
                DB::table('packing_buyer_costing_details')->where('sr_no', $id)->delete();
                for($x=0; $x<count($item_namess); $x++) 
                { 
                    $data4 = array(
                        'sr_no'=>$id, 
                        'item_name'=>$request->item_namess[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptionss[$x], 
                        'rate_per_unit'=>$request->rate_per_unitss[$x],
                        'wastage'=>$request->wastagess[$x],
                        'total_amount'=>$request->total_amountss[$x] 
                    );
                    BuyerPackingCostingDetailModel::insert($data4);
                } 
            } 
            
            $style_image = $request->style_image;
            
            if ($style_image != "") 
            {
                $fileName1 = '';
        
                if ($request->hasFile('style_image')) 
                {
                    $file = $request->file('style_image');
                    $fileName1 = $file->getClientOriginalName();
                    $file->move(public_path('uploads/BuyerCosting/'), $fileName1);
                } 
                else 
                {
                    $fileName1 = $request->input('style_image') ?? '';
                }
                
                DB::table('buyer_costing_master')->where('sr_no', '=', $id)->update(['style_image' => $fileName1]);
            }
            
            $attachmentNames = $request->attachment_name;
            
            if (count($attachmentNames) > 0) {
                // Delete existing attachments for the given sr_no
                DB::table('buyer_costing_attachments')->where('sr_no', $id)->delete();
            
                for ($x = 0; $x < count($attachmentNames); $x++) {
                    // Initialize fileName as an empty string
                    $fileName = '';
            
                    // Check if the file exists and is valid at the current index
                    if ($request->hasFile('attachment_image') && isset($request->file('attachment_image')[$x]) && $request->file('attachment_image')[$x]->isValid()) {
                        $file = $request->file('attachment_image')[$x];
                        $fileName = $file->getClientOriginalName();
                        // Move uploaded file to public/uploads/BuyerCosting/
                        $file->move(public_path('uploads/BuyerCosting/'), $fileName);
                    } else {
                        // Use existing file name if the new file is not uploaded
                        $fileName = $request->input('attachment_image')[$x] ?? '';
                    }
            
                    // Prepare data to be inserted into the database
                    $data = [
                        'sr_no' => $id,
                        'attachment_name' => $attachmentNames[$x],
                        'attachment_image' => $fileName, // Store file name in the database
                    ];
                    
                    if($fileName != '')
                    { 
                        BuyerCostingAttachementModel::insert($data);
                    }
                  
                }
            }



            
            return redirect()->route('BuyerCosting.index')->with('message', 'Update Record Succesfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('buyer_costing_master')->where('sr_no', $id)->delete();
        DB::table('fabric_buyer_costing_details')->where('sr_no',$id)->delete();
        DB::table('sewing_buyer_costing_details')->where('sr_no', $id)->delete();
        DB::table('packing_buyer_costing_details')->where('sr_no', $id)->delete();
        DB::table('buyer_costing_attachments')->where('sr_no', $id)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
      
    public function BuyerCostingPrint($sr_no)
    {
        
          //DB::enableQueryLog();
        $BuyerCostingMaster = BuyerCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_costing_master.userId')
            ->join('currency_master', 'currency_master.cur_id', '=', 'buyer_costing_master.cur_id')
            ->join('order_group_master', 'order_group_master.og_id', '=', 'buyer_costing_master.og_id')
            ->where('buyer_costing_master.delflag','=', '0')
            ->where('buyer_costing_master.sr_no','=', $sr_no)
            ->get(['buyer_costing_master.*', 'usermaster.username','currency_master.currency_name','order_group_master.*']);
          //    $query = DB::getQueryLog();
            //  $query = end($query);
            //   dd($query);
            
            
        return view('BuyerCostingPrint',compact('BuyerCostingMaster'));  
            
    }
    
    public function RepeatBuyerCostingEdit(Request $request)
    { 
        $id = $request->srno;
        $OrderGroupList = OrderGroupModel::join('order_group_auth', "order_group_auth.og_id","=","order_group_master.og_id")->where('order_group_auth.username','=',Session::get('username'))->WhereIn("order_group_master.og_id",[1,2])->where('order_group_master.delflag','=', '0')->get();

        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        
        $BuyerCostingMasterList = BuyerCostingMasterModel::find($id);
        $BuyerCostingFabricList = BuyerFabricCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingSewingList = BuyerSewingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingPackingList = BuyerPackingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingAttachementList = BuyerCostingAttachementModel::where('sr_no','=',$id)->get();
        return view('RepeatBuyerCostingEdit', compact('BuyerCostingMasterList', 'BuyerCostingFabricList', 'BuyerCostingSewingList', 'BuyerCostingPackingList','OrderGroupList','CurrencyList','BuyerCostingAttachementList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function RepeatBuyerCostingUpdate(Request $request)
    {  
        
            $data1=array( 
                'entry_date'=>$request->entry_date, 
                'buyer_name'=>$request->buyer_name,
                'brand_name'=>$request->brand_name,
                'inr_rate'=>$request->inr_rate, 
                'exchange_rate'=>$request->exchange_rate, 
                'fob_rate'=>$request->fob_rate, 
                'total_qty'=>$request->total_qty,
                'total_value'=>$request->total_value, 
                'style_name'=>$request->style_name,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,   
                'fabric_value'=>$request->fabric_value,
                'cur_id'=>$request->cur_id,
                'og_id'=>$request->og_id, 
                'fabric_per'=>$request->fabric_per,
                'sewing_trims_value'=>$request->sewing_trims_value,
                'sewing_trims_per'=>$request->sewing_trims_per,
                'packing_trims_value'=>$request->packing_trims_value, 
                'packing_trims_per'=>$request->packing_trims_per, 
                'production_value'=>$request->production_value,
                'production_per'=>$request->production_per,
                'other_value'=>$request->other_value,
                'other_per'=>$request->other_per,      
                'transport_value'=>$request->transport_value,      
                'transport_per'=>$request->transport_per,      
                'agent_commission_value'=>$request->agent_commission_value,
                'agent_commission_per'=>$request->agent_commission_per,
                'dbk_value'=>$request->dbk_value, 
                'dbk_per'=>$request->dbk_per, 
                'dbk_value1'=>$request->dbk_value1,
                'dbk_per1'=>$request->dbk_per1, 
                'printing_value'=>$request->printing_value,
                'printing_per'=>$request->printing_per,
                'embroidery_value'=>$request->embroidery_value,
                'embroidery_per'=>$request->embroidery_per,
                'ixd_value'=>$request->ixd_value,
                'ixd_per'=>$request->ixd_per,
                'total_making_value'=>$request->total_making_value,
                'total_making_per'=>$request->total_making_per,
                'garment_reject_value'=>$request->garment_reject_value,  
                'garment_reject_per'=>$request->garment_reject_per,   
                'testing_charges_value'=>$request->testing_charges_value, 
                'testing_charges_per'=>$request->testing_charges_per,    
                'finance_cost_value'=>$request->finance_cost_value,   
                'finance_cost_per'=>$request->finance_cost_per,   
                'extra_value'=>$request->extra_value,   
                'extra_per'=>$request->extra_per,   
                'total_cost_value'=>isset($request->total_cost_value) ? ($request->total_cost_value) : 0,
                'total_cost_per'=>isset($request->total_cost_per) ? ($request->total_cost_per) : 0,
                'profit_value'=>isset($request->profit_value) ? ($request->profit_value) : 0,
                'profit_per'=>isset($request->profit_per) ? ($request->profit_per) : 0,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0', 
                'created_at'=>date("Y-m-d H:i:s")
            );
         
            BuyerCostingMasterModel::insert($data1);
            
            $retId = DB::table('buyer_costing_master')->max('sr_no');
            $item_name = $request->item_name;
            if(count($item_name)>0)
            {
            
                for($x=0; $x<count($item_name); $x++) 
                { 
                    $data2 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_name[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumption[$x], 
                        'rate_per_unit'=>$request->rate_per_unit[$x],
                        'wastage'=>$request->wastage[$x],
                        'total_amount'=>$request->total_amount[$x] 
                    );
                    BuyerFabricCostingDetailModel::insert($data2);
                } 
            }
            
            $item_names = $request->item_names;
            if(count($item_names)>0)
            {
            
                for($x=0; $x<count($item_names); $x++) 
                { 
                    $data3 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_names[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptions[$x], 
                        'rate_per_unit'=>$request->rate_per_units[$x],
                        'wastage'=>$request->wastages[$x],
                        'total_amount'=>$request->total_amounts[$x] 
                    );
                    BuyerSewingCostingDetailModel::insert($data3);
                } 
            }
            
            $item_namess = $request->item_namess;
            if(count($item_namess)>0)
            {
            
                for($x=0; $x<count($item_namess); $x++) 
                { 
                    $data4 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_namess[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptionss[$x], 
                        'rate_per_unit'=>$request->rate_per_unitss[$x],
                        'wastage'=>$request->wastagess[$x],
                        'total_amount'=>$request->total_amountss[$x] 
                    );
                    BuyerPackingCostingDetailModel::insert($data4);
                } 
            }
            
            $style_image = $request->style_image;
            
            if ($style_image != "") 
            {
                $fileName1 = '';
        
                if ($request->hasFile('style_image')) 
                {
                    $file = $request->file('style_image');
                    $fileName1 = $file->getClientOriginalName();
                    $file->move(public_path('uploads/BuyerCosting/'), $fileName1);
                } 
                else 
                {
                    $fileName1 = $request->input('style_image') ?? '';
                }
                
                DB::table('buyer_costing_master')->where('sr_no', '=', $retId)->update(['style_image' => $fileName1]);
            }
            
            $attachmentNames = $request->attachment_name;
            if (count($attachmentNames) > 0) 
            {
                foreach ($attachmentNames as $index => $attachmentName) 
                {
                    if ($request->hasFile('attachment_image.' . $index)) {
                        $attachment = $request->file('attachment_image')[$index];
                        $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                        $location = public_path('uploads/BuyerCosting/');
                        if (file_exists('uploads/BuyerCosting/'.$fileName))
                        {
                             $url = "uploads/BuyerCosting/".$fileName;
                             unlink($url);
                        }
                        $attachment->move($location,$fileName);
                  
                        if($fileName != '')
                        {  
                            $files = new File();
                            $files->sr_no = $retId;
                            $files->attachment_name =  $request->attachment_name[$index];
                            $files->attachment_image = $fileName;
                            $files->save(); 
                        }
                    }
                }
            }
  
            return redirect()->route('BuyerCosting.index');
    }

   
    public function ReviseBuyerCostingEdit(Request $request)
    { 
        $id = $request->srno;
        $OrderGroupList = OrderGroupModel::join('order_group_auth', "order_group_auth.og_id","=","order_group_master.og_id")->where('order_group_auth.username','=',Session::get('username'))->WhereIn("order_group_master.og_id",[1,2])->where('order_group_master.delflag','=', '0')->get();

        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        
        $BuyerCostingMasterList = BuyerCostingMasterModel::find($id);
        $BuyerCostingFabricList = BuyerFabricCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingSewingList = BuyerSewingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingPackingList = BuyerPackingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingAttachementList = BuyerCostingAttachementModel::where('sr_no','=',$id)->get();
        return view('ReviseBuyerCostingEdit', compact('BuyerCostingMasterList', 'BuyerCostingFabricList', 'BuyerCostingSewingList', 'BuyerCostingPackingList','OrderGroupList','CurrencyList','BuyerCostingAttachementList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function ReviseBuyerCostingUpdate(Request $request)
    {  
            if($request->repeat_id == '')
            {
                $repeat = $request->sr_no;
            }
            else
            {
                $repeat = $request->repeat_id;
            }
            $data1=array( 
                'entry_date'=>$request->entry_date, 
                'buyer_name'=>$request->buyer_name,
                'brand_name'=>$request->brand_name,
                'inr_rate'=>$request->inr_rate, 
                'exchange_rate'=>$request->exchange_rate, 
                'fob_rate'=>$request->fob_rate, 
                'total_qty'=>$request->total_qty,
                'total_value'=>$request->total_value, 
                'style_name'=>$request->style_name,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,   
                'fabric_value'=>$request->fabric_value,
                'cur_id'=>$request->cur_id,
                'og_id'=>$request->og_id, 
                'fabric_per'=>$request->fabric_per,
                'sewing_trims_value'=>$request->sewing_trims_value,
                'sewing_trims_per'=>$request->sewing_trims_per,
                'packing_trims_value'=>$request->packing_trims_value, 
                'packing_trims_per'=>$request->packing_trims_per, 
                'production_value'=>$request->production_value,
                'production_per'=>$request->production_per,
                'other_value'=>$request->other_value,
                'other_per'=>$request->other_per,      
                'transport_value'=>$request->transport_value,      
                'transport_per'=>$request->transport_per,      
                'agent_commission_value'=>$request->agent_commission_value,
                'agent_commission_per'=>$request->agent_commission_per,
                'dbk_value'=>$request->dbk_value, 
                'dbk_per'=>$request->dbk_per, 
                'dbk_value1'=>$request->dbk_value1,
                'dbk_per1'=>$request->dbk_per1, 
                'printing_value'=>$request->printing_value,
                'printing_per'=>$request->printing_per,
                'embroidery_value'=>$request->embroidery_value,
                'embroidery_per'=>$request->embroidery_per,
                'ixd_value'=>$request->ixd_value,
                'ixd_per'=>$request->ixd_per,
                'total_making_value'=>$request->total_making_value,
                'total_making_per'=>$request->total_making_per,
                'garment_reject_value'=>$request->garment_reject_value,  
                'garment_reject_per'=>$request->garment_reject_per,   
                'testing_charges_value'=>$request->testing_charges_value, 
                'testing_charges_per'=>$request->testing_charges_per,    
                'finance_cost_value'=>$request->finance_cost_value,   
                'finance_cost_per'=>$request->finance_cost_per,   
                'extra_value'=>$request->extra_value,   
                'extra_per'=>$request->extra_per,   
                'total_cost_value'=>isset($request->total_cost_value) ? ($request->total_cost_value) : 0,
                'total_cost_per'=>isset($request->total_cost_per) ? ($request->total_cost_per) : 0,
                'profit_value'=>isset($request->profit_value) ? ($request->profit_value) : 0,
                'profit_per'=>isset($request->profit_per) ? ($request->profit_per) : 0,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0', 
                'repeat_id'=>$repeat,
                'revised_id'=>$request->revised_id,
                'created_at'=>date("Y-m-d H:i:s"), 
            );
         
            BuyerCostingMasterModel::insert($data1); 

            $retId = DB::table('buyer_costing_master')->max('sr_no');
            DB::table('buyer_costing_master')->where('repeat_id', '=', $repeat)->where('sr_no', '!=', $retId)->update(['isDisabled' => 1]);
            DB::table('buyer_costing_master')->where('sr_no', '=', $repeat)->where('sr_no', '!=', $retId)->update(['isDisabled' => 1]);
            $countData = DB::SELECT("SELECT count(*) as total_count FROM buyer_costing_master WHERE repeat_id=".$repeat);
            
            $total_count = isset($countData[0]->total_count) ? $countData[0]->total_count : 0;
            DB::table('buyer_costing_master')->where('sr_no','=',$retId)->update(['revised_id'=> $request->revised_id."-".$total_count]); 
            $item_name = $request->item_name;
            if(count($item_name)>0)
            {
            
                for($x=0; $x<count($item_name); $x++) 
                { 
                    $data2 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_name[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumption[$x], 
                        'rate_per_unit'=>$request->rate_per_unit[$x],
                        'wastage'=>$request->wastage[$x],
                        'total_amount'=>$request->total_amount[$x] 
                    );
                    BuyerFabricCostingDetailModel::insert($data2);
                } 
            }
            
            $item_names = $request->item_names;
            if(count($item_names)>0)
            {
            
                for($x=0; $x<count($item_names); $x++) 
                { 
                    $data3 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_names[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptions[$x], 
                        'rate_per_unit'=>$request->rate_per_units[$x],
                        'wastage'=>$request->wastages[$x],
                        'total_amount'=>$request->total_amounts[$x] 
                    );
                    BuyerSewingCostingDetailModel::insert($data3);
                } 
            }
            
            $item_namess = $request->item_namess;
            if(count($item_namess)>0)
            {
            
                for($x=0; $x<count($item_namess); $x++) 
                { 
                    $data4 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_namess[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptionss[$x], 
                        'rate_per_unit'=>$request->rate_per_unitss[$x],
                        'wastage'=>$request->wastagess[$x],
                        'total_amount'=>$request->total_amountss[$x] 
                    );
                    BuyerPackingCostingDetailModel::insert($data4);
                } 
            }
            
             $style_image = $request->file('style_image');
            
            if ($style_image) {
                $fileName1 = '';
            
                $style_image = $request->file('style_image');
    
                if ($style_image && $style_image->isValid()) {
                    // Move the file to the target directory
                    $fileName = time() . '_' . $style_image->getClientOriginalName();
                    $style_image->move(public_path('uploads/BuyerCosting/'), $fileName);
                } else {
                    // Handle error if file is invalid or not uploaded
                    return back()->withErrors(['style_image' => 'Invalid file uploaded']);
                }
                
                DB::table('buyer_costing_master')->where('sr_no', '=', $retId)->update(['style_image' => $style_image]);
            } else {
                $style_image = $request->input('style_image') ?? '';
                DB::table('buyer_costing_master')->where('sr_no', '=', $retId)->update(['style_image' => $style_image]);
            }

            
            $attachmentNames = $request->attachment_name;
            if (count($attachmentNames) > 0) 
            {
                foreach ($attachmentNames as $index => $attachmentName) 
                {
                    if ($request->hasFile('attachment_image.' . $index)) {
                        $attachment = $request->file('attachment_image')[$index];
                        $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                        $location = public_path('uploads/BuyerCosting/');
                        if (file_exists('uploads/BuyerCosting/'.$fileName))
                        {
                             $url = "uploads/BuyerCosting/".$fileName;
                             unlink($url);
                        }
                        $attachment->move($location,$fileName);
                 
                        if($fileName != '')
                        {  
                            $files = new File();
                            $files->sr_no = $retId;
                            $files->attachment_name =  $request->attachment_name[$index];
                            $files->attachment_image = $fileName;
                            $files->save();
                        }
                    }
                }
            }
  
            return redirect()->route('BuyerCosting.index');
    }
}
