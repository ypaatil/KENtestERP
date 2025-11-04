<?php

namespace App\Http\Controllers;
use App\Models\FabricOutwardModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
 
 
use App\Models\ItemModel;
use App\Models\PartModel;
use App\Models\FabricTransactionModel;
use App\Models\FabricTrimPartModel;
use App\Models\FabricOutwardDetailModel;
use App\Models\CounterNumberModel;
use Illuminate\Support\Facades\DB;

use App\Models\VendorPurchaseOrderModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;

use Session;



class FabricOutwardController extends Controller
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
->where('form_id', '52')
->first();
        
        
        
        
         //   DB::enableQueryLog();
         $FabricOutwardList = FabricOutwardModel::join('usermaster', 'usermaster.userId', '=', 'fabric_outward_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_outward_master.vendorId')
         ->where('fabric_outward_master.delflag','=', '0')
         ->get(['fabric_outward_master.*','usermaster.username','ledger_master.Ac_name']);
    
    //   DB::enableQueryLog(); // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('FabricOutwardMasterList', compact('FabricOutwardList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FABRIC_OUTWARD'");
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ShadeList = DB::table('shade_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        return view('FabricOutwardMaster',compact('Ledger','ShadeList', 'PartList','CPList','ItemList','counter_number','MainStyleList','SubStyleList','FGList'));

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
             
            'fout_code'=>'required',
            'fout_date'=>'required',
            'vendorId'=>'required',
            'vpo_code'=>'required',
            'mainstyle_id'=>'required',
            'style_no'=>'required',
            'total_meter'=>'required',
            'total_taga_qty'=>'required',
            'c_code'=>'required',
             ]);


$data1=array(

    'fout_code'=>$request->fout_code, 'fout_date'=>$request->fout_date,
    'vendorId'=>$request->vendorId, 'vpo_code'=>$request->vpo_code,
    'mainstyle_id' =>$request->mainstyle_id, 'substyle_id' =>$request->substyle_id,  'fg_id' =>$request->fg_id,
    'style_no' => $request->style_no,  'style_description' => $request->style_description,
    'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,
    'narration'=>$request->in_narration,  'c_code' => $request->c_code,
    'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1',
    
    
);

FabricOutwardModel::insert($data1);

$item_code = $request->input('item_code');
if(count($item_code)>0)
{
    
for($x=0; $x<count($item_code); $x++) {
    # code...
        
                $data2[]=array(
                'fout_code' =>$request->fout_code,
                'fout_date' => $request->fout_date,
                'vendorId'=>$request->vendorId,
                'vpo_code'=>$request->vpo_code,
                'mainstyle_id' =>$request->mainstyle_id,
                'substyle_id' =>$request->substyle_id,
                'fg_id' =>$request->fg_id,
                'style_no' => $request->style_no, 
                'style_description' => $request->style_description,
                'part_id' =>$request->part_ids[$x],
                'item_code' =>$request->item_code[$x],
                'meter' => $request->meters[$x],
                'width' => $request->widths[$x],
                'shade_id' =>$request->shade_ids[$x],
                'track_code' =>$request->track_codes[$x] ,
                 'item_rate' => $request->item_rate[$x],
                'usedflag' => '0',
                
                );
                
              
                                 
                                 
                                $data3[]=array(
                                    'tr_code' =>$request->fout_code,
                                    'tr_date' => $request->fout_date,
                                    'Ac_code' =>$request->vendorId,
                                    'cp_id' =>0,
                                    'job_code'=>$request->vpo_code, 
                                    'po_code'=>'',
                                    'invoice_no'=>0,
                                    'gp_no' =>0,
                                    'fg_id' =>$request->fg_id,
                                    'style_no' => $request->style_no,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_ids[$x],
                                    'shade_id' =>$request->shade_ids[$x],
                                    'track_code' => $request->track_codes[$x],
                                      
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meters[$x],
                                    'tr_type' => '3',
                                    'userId'=>$request->userId,
                                );
          
       
        }
        FabricOutwardDetailModel::insert($data2);
        FabricTransactionModel::insert($data3);
 
}

DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FABRIC_OUTWARD'");
return redirect()->route('FabricOutward.index');
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
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
       
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  DB::table('shade_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        
          $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
       
        $FabricOutwardMasterList = FabricOutwardModel::find($id);
        // DB::enableQueryLog();
        $FabricOutwardDetails = FabricOutwardDetailModel::join('main_style_master','main_style_master.mainstyle_id', '=', 'fabric_outward_details.mainstyle_id')
        ->join('item_master', 'item_master.item_code', '=', 'fabric_outward_details.item_code')
        ->where('fabric_outward_details.fout_code','=', $FabricOutwardMasterList->fout_code)->get(['fabric_outward_details.*','main_style_master.mainstyle_name', 'item_master.color_name', 'item_master.item_description', 'item_master.dimension']);
  
   // $VPOrderList= VendorPurchaseOrderModel::select('vpo_code','sales_order_no')->where('vendorId',$FabricOutwardMasterList->vendorId)->get();
           
   $S1= VendorPurchaseOrderModel::select('vpo_code')
        ->whereNotIn('vpo_code',function($query){
        $query->select('vpo_code')->from('fabric_outward_master');
        });
        $S2=FabricOutwardModel::select('vpo_code')->where('fout_code',$FabricOutwardMasterList->fout_code);
        $VPOrderList = $S1->union($S2)->get();
  
  
  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
  return view('FabricOutwardMasterEdit',compact('FabricOutwardMasterList', 'ShadeList', 'PartList', 'Ledger','CPList','MainStyleList','SubStyleList','FGList','ItemList',  'FabricOutwardDetails','VPOrderList'));
   
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
        $this->validate($request, [
             
            'fout_code'=>'required',
            'fout_date'=>'required',
            'vendorId'=>'required',
            'vpo_code'=>'required',
            'mainstyle_id'=>'required',
            'style_no'=>'required',
            'total_meter'=>'required',
            'total_taga_qty'=>'required',
            
            'c_code'=>'required',
             ]);

             $data1=array(

                'fout_code'=>$request->fout_code, 'fout_date'=>$request->fout_date,
             'vendorId'=>$request->vendorId, 'vpo_code'=>$request->vpo_code,
            'mainstyle_id' =>$request->mainstyle_id, 'substyle_id' =>$request->substyle_id,  'fg_id' =>$request->fg_id,
            'style_no' => $request->style_no,  'style_description' => $request->style_description, 
                'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,
                 'narration'=>$request->in_narration,  'c_code' => $request->c_code,
                'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1',
                
                
            );

            $FabricOutwardMasterList = FabricOutwardModel::findOrFail($request->input('fout_code'));  
   
            $FabricOutwardMasterList->fill($data1)->save();

            DB::table('fabric_outward_details')->where('fout_code', $request->input('fout_code'))->delete();
            DB::table('fabric_transaction')->where('tr_code', $request->input('fout_code'))->delete();

           $item_code = $request->input('item_code');
            if(count($item_code)>0)
            {
                  
            for($x=0; $x<count($item_code); $x++) 
            {
                # code...
                
                       // if($request->track_codes[$x]!=''){ $track_code=$request->track_codes[$x];}else{$track_code='P'.++$PBarcodes;}
                            $data2[]=array(
                            'fout_code' =>$request->fout_code,
                            'fout_date' => $request->fout_date,
                           'vendorId'=>$request->vendorId, 'vpo_code'=>$request->vpo_code,
                            'mainstyle_id' =>$request->mainstyle_id,
                            'substyle_id' =>$request->substyle_id, 
                            'fg_id' =>$request->fg_id,
                            'style_no' => $request->style_no,  
                            'style_description' => $request->style_description,
                            'part_id' =>$request->part_ids[$x],
                            'item_code' =>$request->item_code[$x],
                            'meter' => $request->meters[$x],
                            'width' => $request->widths[$x],
                            'shade_id' =>$request->shade_ids[$x],
                            'track_code' =>$request->track_codes[$x],
                              'item_rate' => $request->item_rate[$x],
                            'usedflag' => '0',
                            
                            );
                   
                  
                                // DB::enableQueryLog();
                                $Roll = DB::table('fabric_checking_details')->select('po_code','old_meter','reject_short_meter','meter')
                                ->where('track_code',$request->track_codes[$x])->first();    
                                
                                // $query = DB::getQueryLog();
                                // $query = end($query);
                                // dd($query);
                                
                                
                                $short_meter=$Roll->old_meter - $Roll->reject_short_meter - $Roll->meter;
                                
                                $data3[]=array(
                                    'tr_code' =>$request->fout_code,
                                    'tr_date' => $request->fout_date,
                                    'Ac_code' =>$request->vendorId,
                                    'cp_id' =>0,
                                    'job_code'=>$request->vpo_code, 
                                    'po_code'=>$Roll->po_code,
                                    'invoice_no'=>0,
                                    'gp_no' =>0,
                                    'fg_id' =>$request->mainstyle_id,
                                    'style_no' => $request->style_no,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_ids[$x],
                                    'shade_id' =>$request->shade_ids[$x],
                                    'track_code' => $request->track_codes[$x],
                                     
                                     'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meters[$x],
                                    'tr_type' => '3',
                                    'userId'=>$request->userId,
                                );
                    
                   
                    }
                     FabricOutwardDetailModel::insert($data2);
                     FabricTransactionModel::insert($data3);
                } 
                
               
    
                return redirect()->route('FabricOutward.index');
            }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        DB::table('fabric_outward_master')->where('fout_code', $id)->delete();
        DB::table('fabric_outward_details')->where('fout_code', $id)->delete();
        $detail =FabricTransactionModel::where('tr_code',$id)->delete();
          Session::flash('delete', 'Deleted record successfully'); 
        
    }

 
 
      public function getSalesOrderDetail2(Request $request)
    { 
        $vpo_codes= $request->input('vpo_code');
        
        
     if($sales_order_no!='')
        {
            $MasterdataList = DB::select("select   Ac_code, mainstyle_id, substyle_id, fg_id, style_no, style_description from vendor_purchse_order_master where  vpo_code in (". $vpo_codes.")");
        }
        
        return json_encode($MasterdataList);
    
    }
 
 
 
 
 
 
public function FabricOutwardData()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        // $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        // $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        // $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
        // DB::enableQueryLog();
        $FabricOutwardDetails = FabricOutwardDetailModel::
          leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'fabric_outward_details.vendorId')
          ->leftJoin('item_master', 'item_master.item_code', '=', 'fabric_outward_details.item_code')
          ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
          ->leftJoin('part_master', 'part_master.part_id', '=', 'fabric_outward_details.part_id')
      
          ->get(['fabric_outward_details.*', 'part_master.part_name','ledger_master.ac_name','item_master.dimension', 'item_master.item_name','item_master.color_name','item_master.item_description','quality_master.quality_name' ]);
     // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('FabricOutwardData',compact('FabricOutwardDetails'));
    }
 
 
    public function getFabricRecord(Request $request)
    { 
        $track_code= $request->input('track_code');
        
      
        $CBD = DB::table('fabric_outward_details')->select(DB::raw("ifnull(SUM(meter),0) as meter"))->where('track_code',$track_code)->first();
              
                      
    // $CBD = DB::table('cutting_balance_details')->select('balance_meter')
    // ->where('track_code',$track_code)
    // ->where('sr_no', \DB::raw("(select max(sr_no) from cutting_balance_details where `track_code` = '$track_code')"))->first();
   /*  Note: 
    //checked that roll has taken before for cutting if yes, balance meter will move ahead.
   // if not, meter from checking_details table will move ahead     */                    
     $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
   
    $PartList = FabricTrimPartModel::where('part_master.delflag','=', '0')->get();
    $ShadeList = DB::table('shade_master')->get();
  
 //  DB::enableQueryLog();
    $Roll = DB::table('fabric_checking_details')->select('track_code', 'fabric_checking_details.item_code','fabric_checking_details.item_rate','dimension','item_description','color_name','part_id' ,'shade_id','meter')
    ->join('item_master', 'item_master.item_code', '=', 'fabric_checking_details.item_code')
    ->where('track_code',$track_code)->first();
    
    // $query = DB::getQueryLog();
    //                             $query = end($query);
    //                             dd($query);  
    
    
    
    if(!empty($CBD))
    {
        $meter =   $Roll->meter - $CBD->meter ;
  // echo $CBD->meter;
    }
    else
    {
        $meter = $CBD->meter;
    }

//echo $meter;
          if($meter>0){   
        
        $html = '';
                        
                        $no=1;
                    
                        $html .='<tr class="thisRow">';
                    
                    $html .='
                    <td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                    $html.='<td>
                    <input type="text" name="track_codes[]" class="track_code" id="track_codes'.$no.'" value="'.$track_code.'" style="width:80px;" required readOnly/>
                    <input type="hidden" name="item_rate[]"   id="item_rate'.$no.'" value="'.$Roll->item_rate.'" style="width:80px;" required readOnly/>
                    </td> 
                   
                    <td> <select name="item_code[]"  id="item_code" style="width:100px;" required disabled>
                    <option value="">--Item--</option>';
                    
                    foreach($ItemList as  $row1)
                    {
                        $html.='<option value="'.$row1->item_code.'"';
                    
                        $row1->item_code == $Roll->item_code ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$row1->item_name.'</option>';
                    }
                     
                    $html.='</select></td>  
                    <td>'.$Roll->color_name.'</td>
                     <td>'.$Roll->item_description.'</td>
                    <td> <select name="part_ids[]"  id="part_ids'.$no.'" style="width:100px;" required disabled>
                    <option value="">--Part--</option>';
                    foreach($PartList as  $rowP)
                    {
                        $html.='<option value="'.$rowP->part_id.'"';

                        $rowP->part_id == $Roll->part_id ? $html.='selected="selected"' : ''; 
                        
                        $html.='>'.$rowP->part_name.'</option>';
                    }
                    $html.='</select></td> 
                   
                    <td> <select name="shade_ids[]"  id="shade_ids'.$no.'" style="width:100px;" required disabled>
                    <option value="">--Shade--</option>';
                    foreach($ShadeList as  $rowP)
                    {
                        $html.='<option value="'.$rowP->shade_id.'"';

                        $rowP->shade_id == $Roll->shade_id ? $html.='selected="selected"' : ''; 
                        
                        $html.='>'.$rowP->shade_name.'</option>';
                    }
                    $html.='</select></td>';
                    
                    
                      $html.='<td>
                    <input type="text" name="widths[]"  id="widths'.$no.'" value="'.$Roll->dimension.'" style="width:80px;" required  /> </td> ';
                    $html.='<td>
                    <input type="text" name="meters[]" class="METER" id="meters'.$no.'" value="'.$meter.'" style="width:80px;" required onkeyup="mycalc();" /> </td> 
                    <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>';
                    
                        $html .='</tr>';
                        $no=$no+1;
                   return response()->json(['html' => $html]);
                }
                else
                {
                         return response()->json(['html' => 'Zero']);  
                }
                

    }

 

}
