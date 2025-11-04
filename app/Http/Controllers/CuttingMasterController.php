<?php

namespace App\Http\Controllers;
 
use App\Models\CuttingMasterModel;
use Illuminate\Http\Request;
use App\Models\SizeModel;
use App\Models\LedgerModel;
use App\Models\ColorModel;
use App\Models\TaskMasterModel;
use Illuminate\Support\Facades\DB;
use App\Models\CuttingBalanceDetailModel;
use App\Models\CuttingDetailModel;
use App\Models\BundleModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\SizeDetailModel;
use App\Models\VendorPurchaseOrderModel;
use App\Models\ItemModel; 
use App\Models\ShadeModel; 
use App\Models\TaskDetailModel;
use Session;
 
class CuttingMasterController extends Controller
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
        ->where('form_id', '93')
        ->first();     
        
        //   DB::enableQueryLog();
        $CuttingMasterList = CuttingMasterModel::join('usermaster', 'usermaster.userId', '=', 'cutting_master.userId')
        ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'cutting_master.vendorId')
        ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'cutting_master.mainstyle_id')
        ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'cutting_master.substyle_id')
        ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'cutting_master.fg_id')
        ->leftJoin('task_master', 'task_master.task_id', '=', 'cutting_master.table_task_code')
        ->join('ctable_master', 'ctable_master.table_id', '=', 'cutting_master.table_id')
        // ->whereIn('cutting_master.table_task_code',function($query){
        //       $query->select('task_id')->from('bundle_barcode_master');
        //     })->latest('created_at')
        ->get(['cutting_master.*','usermaster.username','ledger_master.Ac_name','ctable_master.table_name','mainstyle_name','substyle_name','fg_name','task_master.vpo_code']);
   
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('FabricCuttingMasterList', compact('CuttingMasterList','chekform'));
        
        
        $users = DB::table("users")->select('*')
            ->whereIn('id',function($query){
               $query->select('user_id')->from('invite_users');
            })
            ->get();
         
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CUTTING'");
        
      //  $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $SizeList = SizeModel::where('size_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
       
        $TableList =  DB::table('ctable_master')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        // DB::enableQueryLog();
        $VPOrderList= TaskMasterModel::select('vpo_code' )->whereNotIn('task_master.vpo_code',function($query){
               $query->select(DB::raw('ifnull(vpo_code,"")'))->from('cutting_master');
        })->get();
        
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        
        
        $ItemList = DB::select("select item_code, item_name from item_master where item_master.cat_id=1");
        
        return view('CuttingMaster',compact('Ledger','FGList', 'ItemList','TableList','counter_number', 'SizeList','VPOrderList','MainStyleList','SubStyleList','FGList'));

    }



 public function CompletedCutting()
    {
            $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '93')
            ->first();     
        
        //   DB::enableQueryLog();
        $CuttingMasterList = CuttingMasterModel::join('usermaster', 'usermaster.userId', '=', 'cutting_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'cutting_master.vendorId')
        ->join('ctable_master', 'ctable_master.table_id', '=', 'cutting_master.table_id')
       ->whereIn('cutting_master.table_task_code',function($query){
               $query->select('task_id')->from('bundle_barcode_master');
            })
        ->get(['cutting_master.*','usermaster.username','ledger_master.Ac_name','ctable_master.table_name']);
   
     // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('FabricCuttingMasterList', compact('CuttingMasterList','chekform'));
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
             
        'cu_code'=>'required',
        'cu_date'=>'required',
        'lot_no'=>'required',
        'vendorId'=>'required',  
        'mainstyle_id'=>'required', 
        'substyle_id'=>'required', 
        'fg_id'=>'required', 
        'style_no'=>'required', 
        'style_description'=>'required', 
        'table_id'=>'required',
        'table_task_code'=>'required',
        'table_avg'=>'required',
        'total_pieces'=>'required',
        'total_layers'=>'required',
        'total_used_meter'=>'required',
        'total_cutpiece_meter'=>'required',
        'total_damage_meter'=>'required',
        'total_short_meter'=>'required',
        'total_extra_meter'=>'required',
        'userId'=>'required', 
        'c_code' =>'required',
  
]);



             //DB::enableQueryLog();

  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','CUTTING')
   ->where('firm_id','=',1)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;    
 
$data1=array(
       
    'cu_code'=>$TrNo,
    'cu_date'=>$request->cu_date,
    'lot_no'=>$request->lot_no,
    'vpo_code'=>$request->vpo_code, 
    'vendorId'=>$request->vendorId, 
    'mainstyle_id'=>$request->mainstyle_id,
    'substyle_id'=>$request->substyle_id,
    'fg_id'=>$request->fg_id,
    'style_no'=>$request->style_no,
    'style_description'=>$request->style_description,
    'table_task_code'=>$request->table_task_code,
    'table_id'=>$request->table_id,
    'table_avg'=>$request->table_avg,
    'total_pieces'=>$request->total_pieces,
    'total_layers'=>$request->total_layers,
    'total_used_meter'=>$request->total_used_meter,
    'total_cutpiece_meter'=>$request->total_cutpiece_meter,
    'total_actual_balance'=>$request->total_actual_balance,
    'total_damage_meter'=>$request->total_damage_meter,
    'total_short_meter'=>$request->total_short_meter,
    'total_extra_meter'=>$request->total_extra_meter,
    'narration'=>$request->narration, 
    'userId'=>$request->userId, 
    'c_code' =>$request->c_code,
    'delflag'=>'0',
    'layer_date' =>$request->layer_date,
    'layer_start_time' =>$request->layer_start_time,
    'layer_end_time' =>$request->layer_end_time,
    'cutting_date' =>$request->cutting_date,
    'cutting_start_time' =>$request->cutting_start_time,
    'cutting_end_time' =>$request->cutting_end_time
);

CuttingMasterModel::insert($data1);
 
  DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='CUTTING'");
  DB::select("update ctable_master set tr_no=tr_no + 1 where table_id='$request->table_id'");
  DB::select("update task_master set endflag=1 where task_id='$request->table_task_code'"); 
 
$color_id = $request->input('color_id');
$track_codes = $request->input('track_codes');
$track_codess = $request->input('track_codess');
if(count($track_codes)>0)
{

for($x=0; $x<count($track_codes); $x++) {
    # code...
   
$data2=array(
           
    'cu_code'=>$TrNo, 
    'cu_date'=>$request->cu_date,
    'lot_no'=>$request->lot_no,
    'vpo_code'=>$request->vpo_code, 
    'vendorId'=>$request->vendorId, 
    'mainstyle_id'=>$request->mainstyle_id,
    'substyle_id'=>$request->substyle_id,
    'fg_id'=>$request->fg_id,
    'style_no'=>$request->style_no,
    'style_description'=>$request->style_description,
    'table_id'=>$request->table_id, 
    'table_avg'=>$request->table_avg,
    'track_code'=>$request->track_codes[$x],
    'part_id'=>$request->part_ids[$x],
    'item_code'=>$request->item_code[$x], 
    'width'=>$request->width[$x], 
    'meter'=>$request->meter[$x],
     'shade_id'=>$request->shade_id[$x],
    'size_id'=>$request->size_id[$x],
    'ratio'=>$request->ratio[$x],
    'layers'=>$request->layers[$x],
    'qty'=>$request->qty[$x],
      
         );
        
         CuttingDetailModel::insert($data2);
         
        }

       
        for($x=0; $x<count($track_codess); $x++) {
            # code...
           
        $data3=array(
                   
            'cu_code'=>$TrNo,
            'cu_date'=>$request->cu_date,
            'lot_no'=>$request->lot_no,
           'vpo_code'=>$request->vpo_code, 
            'vendorId'=>$request->vendorId, 
            'mainstyle_id'=>$request->mainstyle_id,
            'substyle_id'=>$request->substyle_id,
            'fg_id'=>$request->fg_id,
            'style_no'=>$request->style_no,
            'style_description'=>$request->style_description,
            'table_id'=>$request->table_id,
            'table_avg'=>$request->table_avg,
            'track_code'=>$request->track_codess[$x],
            'part_id'=>$request->part_idss[$x],
            'item_code'=>$request->item_codes[$x],
            'width'=>$request->widths[$x],
            'meter'=>$request->meters[$x]      ,
            'shade_id'=>$request->shade_ids[$x],
            'layers'=>$request->layerss[$x],
            'used_meter'=>$request->used_meters[$x],
            'balance_meter'=>$request->bpiece_meters[$x],
            'cpiece_meter'=>$request->cpiece_meters[$x],  
            'actual_balance'=>$request->actual_balances[$x],     
            'dpiece_meter'=>$request->dpiece_meters[$x],
            'short_meter'=>$request->short_meters[$x],       
            'extra_meter'=>$request->extra_meters[$x],
                 );
                


                 CuttingBalanceDetailModel::insert($data3);
                 


                }
                

 
 
}

return redirect()->route('FabricCutting.index')->with('message', 'New Record Saved Succesfully..!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(CuttingMasterModel $cuttingMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id', 1)->get();
       
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $TableList =  DB::table('ctable_master')->get();
        $CuttingMasterList = CuttingMasterModel::find($id);
    //   
    //  DB::enableQueryLog();
        $SizeList = TaskDetailModel::select('task_details.size_id','size_name')
            ->join('size_detail', 'size_detail.size_id', '=', 'task_details.size_id')
            ->get();
   // dd(DB::getQueryLog());
    
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
         $ShadeList = ShadeModel::where('shade_master.delflag','=', '0')->get();
       

        $CuttingDetailList = CuttingDetailModel::where('cutting_details.cu_code','=', $CuttingMasterList->cu_code)->get(['cutting_details.*']);

        $CuttingBalanceDetailList = CuttingBalanceDetailModel::join('fabric_checking_details','fabric_checking_details.track_code','=','cutting_balance_details.track_code')->where('cutting_balance_details.cu_code','=', $CuttingMasterList->cu_code)->get(['cutting_balance_details.*','fabric_checking_details.width']);
        $TaskList =  TaskMasterModel::where('task_master.table_id','=', $CuttingMasterList->table_id)->get();
        
  return view('CuttingMasterEdit',compact('CuttingMasterList','Ledger','TaskList','ShadeList', 'ItemList','TableList', 'SizeList', 'CuttingDetailList','CuttingBalanceDetailList','MainStyleList','SubStyleList','FGList'));
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CuttingMasterModel $cuttingMasterModel)
    {
        $this->validate($request, [
             
        'cu_code'=>'required',
        'cu_date'=>'required',
        'lot_no'=>'required',
        'vendorId'=>'required', 
        'mainstyle_id'=>'required', 
        'substyle_id'=>'required', 
        'fg_id'=>'required', 
        'style_no'=>'required', 
        'style_description'=>'required', 
        'table_id'=>'required',
        'table_task_code'=>'required',
        'table_avg'=>'required',
        'total_pieces'=>'required',
        'total_layers'=>'required',
        'total_used_meter'=>'required',
        'total_cutpiece_meter'=>'required',
        'total_damage_meter'=>'required',
        'total_short_meter'=>'required',
        'total_extra_meter'=>'required',
        'userId'=>'required', 
        'c_code' =>'required',
      
    ]);
     
    $data1=array(
           
        'cu_code'=>$request->cu_code,
        'cu_date'=>$request->cu_date,
        'lot_no'=>$request->lot_no,
        'vpo_code'=>$request->vpo_code, 
        'vendorId'=>$request->vendorId, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'table_id'=>$request->table_id,
        'table_task_code'=>$request->table_task_code,
        'table_avg'=>$request->table_avg,
        'total_pieces'=>$request->total_pieces,
        'total_layers'=>$request->total_layers,
        'total_used_meter'=>$request->total_used_meter,
        'total_cutpiece_meter'=>$request->total_cutpiece_meter,
        'total_actual_balance'=>$request->total_actual_balance,
        'total_damage_meter'=>$request->total_damage_meter,
        'total_short_meter'=>$request->total_short_meter,
        'total_extra_meter'=>$request->total_extra_meter,
        'narration'=>$request->narration, 
        'userId'=>$request->userId, 
        'c_code' =>$request->c_code,
        'delflag'=>'0',
        'layer_date' =>$request->layer_date,
        'layer_start_time' =>$request->layer_start_time,
        'layer_end_time' =>$request->layer_end_time,
        'cutting_date' =>$request->cutting_date,
        'cutting_start_time' =>$request->cutting_start_time,
        'cutting_end_time' =>$request->cutting_end_time 
    );
    
    $CuttingMasterList = CuttingMasterModel::findOrFail($request->input('cu_code'));  
    $CuttingMasterList->fill($data1)->save();
   
   
    DB::select("update task_master set endflag=1 where task_id='$request->table_task_code'"); 
   
   
    DB::table('cutting_details')->where('cu_code', $request->input('cu_code'))->delete();
    DB::table('cutting_balance_details')->where('cu_code', $request->input('cu_code'))->delete();
    
    $color_id = $request->input('color_id');
    $track_codes = $request->input('track_codes');
    $track_codess = $request->input('track_codess');
    if(count($track_codes)>0)
    {
    
    for($x=0; $x<count($track_codes); $x++) {
        # code...
       
    $data2=array(
               
        'cu_code'=>$request->cu_code, 
        'cu_date'=>$request->cu_date,
        'lot_no'=>$request->lot_no,
        'vpo_code'=>$request->vpo_code, 
        'vendorId'=>$request->vendorId, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'table_id'=>$request->table_id, 
        'table_avg'=>$request->table_avg,
        'track_code'=>$request->track_codes[$x],
        'part_id'=>$request->part_ids[$x],
        'item_code'=>$request->item_code[$x],
        'width'=>$request->width[$x], 
        'meter'=>$request->meter[$x],
        'shade_id'=>$request->shade_id[$x],
        'size_id'=>$request->size_id[$x],
        'ratio'=>$request->ratio[$x],
        'layers'=>$request->layers[$x],
        'qty'=>$request->qty[$x],
          
             );
            
             CuttingDetailModel::insert($data2);
             
            }
    
           
            for($x=0; $x<count($track_codess); $x++) {
                # code...
               
            $data3=array(
                       
                'cu_code'=>$request->cu_code,
                'cu_date'=>$request->cu_date,
                'lot_no'=>$request->lot_no,
                'vpo_code'=>$request->vpo_code, 
                'vendorId'=>$request->vendorId, 
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id,
                'fg_id'=>$request->fg_id,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'table_id'=>$request->table_id,
                'table_avg'=>$request->table_avg,
                'track_code'=>$request->track_codess[$x],
                'part_id'=>$request->part_idss[$x],
                'item_code'=>$request->item_codes[$x],
                'width'=>$request->widths[$x],
                'meter'=>$request->meters[$x]      ,
                'shade_id'=>$request->shade_ids[$x],
                'layers'=>$request->layerss[$x],
                'used_meter'=>$request->used_meters[$x],
                'balance_meter'=>$request->bpiece_meters[$x],
                'cpiece_meter'=>$request->cpiece_meters[$x],   
                'actual_balance'=>$request->actual_balances[$x],     
                'dpiece_meter'=>$request->dpiece_meters[$x],
                'short_meter'=>$request->short_meters[$x],       
                'extra_meter'=>$request->extra_meters[$x],
                     );
                    
                     CuttingBalanceDetailModel::insert($data3);
                     
                    }
      
    }
      
    return redirect()->route('FabricCutting.index')->with('message', 'Record Updated Succesfully..!');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
       $Task = BundleModel::where('task_id','=', $code)->count(); 
      
       $cu_code = CuttingMasterModel::select('cu_code')->where('table_task_code','=', $code)->first();  

       if($Task==0)
          {
            DB::select("update task_master set endflag=0 where task_id='$code'"); 
            DB::table('cutting_master')->where('cu_code', $cu_code->cu_code)->delete();
            DB::table('cutting_balance_details')->where('cu_code', $cu_code->cu_code)->delete();
            DB::table('cutting_details')->where('cu_code', $cu_code->cu_code)->delete();
            Session::flash('delete', 'Deleted record successfully '.$cu_code->cu_code); 
        }
        else
        {
           Session::flash('delete', "This Records Can't be deleted, As Bundling Against this Cutting Task ".$cu_code->cu_code." is in Record..!"); 
        }
       
    }
    
    
    // public function destroyMany(Request $request)
    // {
    //     //  DB::enableQueryLog();

    //     $Task = BundleModel::where('task_id','=', $request->table_task_code)->count(); 
         
    //     // $query = DB::getQueryLog();
    //     // $query = end($query);
    //     // dd($query);
       
          
    //     if($Task==0)
    //       {
    //         // DB::table('cutting_master')->where('cu_code', $id)->delete();
    //         // DB::table('cutting_balance_details')->where('cu_code', $id)->delete();
    //         // DB::table('cutting_details')->where('cu_code', $id)->delete();
    //         Session::flash('delete', 'Deleted record successfully '.$Task); 
    //       // echo Session::get('delete');
    //     }
    //     else
    //     {
    //         Session::flash('delete', "This Records Can't be deleted, As Cutting Against this Task is in Record..!".$Task); 
    //         //  echo Session::get('delete');
    //     }
        
        
       
    // }
    
    
 
  public function GetTaskList(Request $request)
    { 
      //  $table_id= $request->input('table_id');
         
    if (!$request->table_id) {
        $html = '<option value="">--Task--</option>';
        } else {
       
        $html = '';
       $MasterdataList = TaskMasterModel::where('table_id', $request->table_id)->where('delflag', '0')->where('endflag', '0')->get();
           $html .= '<option value="">Task List</option>';
           foreach ($MasterdataList as $row) 
           {
               
                $html .= ' <option value="'.$row->task_id.'">'.$row->task_id.'</option>';
              
            }
        }
        
        return response()->json(['html' => $html]);
     
    }
 
 
 
    public function getCheckingMasterdata(Request $request)
    { 
        $table_task_code= $request->input('table_task_code');
        $MasterdataList = DB::select("select task_master.vendorId, task_master.table_id,concat('LOT-',tr_no+1) as 'lot_no',task_master.fg_id, task_master.mainstyle_id, task_master.substyle_id, task_master.style_no,task_master.vpo_code, 
                                    task_master.table_avg ,task_master.style_description,vendor_purchase_order_master.sales_order_no from task_master 
                                    inner join ctable_master on ctable_master.table_id=task_master.table_id 
                                    LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = task_master.vpo_code 
                                    where task_master.task_id='".$table_task_code."' and task_master.delflag=0");
        return json_encode($MasterdataList);
    
    }
 
    public function getCheckingFabricdata(Request $request)
    { 
        $track_code= $request->input('track_code');
        $table_avg= $request->input('table_avg');
         
       $Count = CuttingBalanceDetailModel::where('track_code','=', $track_code)->count(); 
      
      if($Count==0)
        {
     //  echo '1';
        $MasterdataList = DB::select("SELECT track_code, item_code, width, meter, floor((meter/". $table_avg.")) as Layers from fabric_outward_details where track_code='".$track_code."'");
            
       
        }
        else
        {
            $sr_no = CuttingBalanceDetailModel::where('track_code','=', $track_code)->max('sr_no');
            $Roll = CuttingBalanceDetailModel::where('sr_no', $sr_no)->first();
             
          if($Roll->balance_meter!=0)
              {
                    
              $MasterdataList = DB::select(" SELECT track_code, item_code, width, balance_meter as meter,cpiece_meter , floor((balance_meter/". $table_avg.")) as Layers from cutting_balance_details where  sr_no = (select max(sr_no) from cutting_balance_details where track_code='".$track_code."')");    
            // echo '2';
              }
              elseif($Roll->cpiece_meter!=0) 
              {
                  
                  $MasterdataList = DB::select(" SELECT track_code, item_code, width,   balance_meter,cpiece_meter as meter , floor((cpiece_meter/". $table_avg.")) as Layers from cutting_balance_details where  sr_no = (select max(sr_no) from cutting_balance_details where track_code='".$track_code."')");
           //   echo '3';
              }
                else
              {
                 
                  $MasterdataList = DB::select(" SELECT track_code, item_code, width, '0' as meter,'0' as balance_meter,'0' as cpiece_meter , '0' as Layers from fabric_outward_details where track_code='".$track_code."'");    
                  //   echo '4';
              }
            
       
        }
       
          return json_encode($MasterdataList);
    
    }
 
    public function getRatioDetails(Request $request)
    { 
        $table_avg= $request->input('table_avg');
        $track_code= $request->input('track_code');
        // $job_code= $request->input('job_code');
        $layers= $request->input('layers');
        $task_id= $request->input('task_id');
        $table_id=$request->input('table_id');
        $vpo_code= $request->input('vpo_code');
       // DB::enableQueryLog();

         $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
            
        $SizeList = TaskDetailModel::select('task_details.size_id','size_detail.size_name')->join('size_detail','size_detail.size_id','=','task_details.size_id')->where('task_details.task_id', $task_id)->get();
        // $query = DB::getQueryLog();
        //     $query = end($query);
        //     dd($query);
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        
           $Count = CuttingBalanceDetailModel::where('track_code','=', $track_code)->count(); 
     $sr_no = CuttingBalanceDetailModel::where('track_code','=', $track_code)->max('sr_no');
      if($Count==0)
        {
            $Roll = DB::table('fabric_checking_details')->select('track_code','part_id', 'item_code', 'width', 'meter', 'shade_id')->where('track_code',$track_code)->first();
        }
        else
        {
           // $Roll = DB::table('cutting_balance_details')->select('track_code', 'color_id', 'width', 'meter', 'balance_meter','cpiece_meter' )->where('track_code',$track_code)->whereRaw('sr_no = (select max(sr_no) from cutting_balance_details where track_code="'.$track_code.'")')->get();
         $Roll = CuttingBalanceDetailModel::where('sr_no', $sr_no)->first();
       
        }
     
         
         if($layers==0 )
        {
            if($Count!=0)
            {
               if($Roll->balance_meter!=0)
               {
                   $meter= $Roll->balance_meter;
                   $layers= floor($Roll->balance_meter/$table_avg);
               }
               elseif($Roll->cpiece_meter!=0)
               {
                   $meter= $Roll->cpiece_meter;
                   $layers= floor($Roll->cpiece_meter/$table_avg);
               }
            //   else
            //   {
            //       $meter=0;
            //         $layers=0;
            //   }
               
            }
            else
            {
                 $meter= $Roll->meter;
                 $layers= floor($Roll->meter/$table_avg);
            }
        }
        else
        {

            $meter=floatval($table_avg*$layers);
          
        }
 
        $CuttingRatio = DB::select(" SELECT `task_id`, `task_date`, `vendorId`, `vpo_code`, `table_id`, `style_no`,
         `table_avg`, `size_id`, `ratio` FROM `task_details`
          where table_id='". $table_id."' and task_id='".$task_id."'");
    
        $html = '';
        $fabricData  = DB::table('fabric_outward_details')->select('*')->where('vpo_code','=',$vpo_code)->where('track_code','=',$track_code)->get();
        $isExist = count($fabricData);
    
        if($isExist > 0)
        { 
            $no=1;
            foreach ($CuttingRatio as $row) {
                $html .='<tr class="thisRow">';
               
            $html .='
            <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;" readonly required/></td>';
            $html.='<td>
            <input type="text" name="track_codes[]" class="track_code" id="track_code'.$no.'" value="'.$track_code.'" style="width:80px; height:30px;" readonly required/> 
            <input type="hidden" name="part_ids[]"   id="part_ids'.$no.'" value="'.$Roll->part_id.'" style="width:80px; height:30px;" readonly required/> 
            </td> 
             
            <td> <select name="item_code[]"  id="item_code'.$no.'" style="width:200px; height:30px;" required disabled>
            <option value="">--Item List--</option>';
            foreach($ItemList as  $rowitem)
            {
                $html.='<option value="'.$rowitem->item_code.'"';
            
                $rowitem->item_code == $Roll->item_code ? $html.='selected="selected"' : ''; 
                
                $html.='>'.$rowitem->item_name.'</option>';
            }
            $html.='</select></td>';
            
          
            $html.='<td><input type="hidden" name="width[]" id="width'.$no.'" value="'.$Roll->width.'" style="width:80px; height:30px;" />
            <input type="hidden" name="meter[]" id="meter'.$no.'" value="'.$Roll->meter.'" style="width:80px; height:30px;"  />
            <input type="hidden" name="shade_id[]" id="shade_id'.$no.'" value="'.$Roll->shade_id.'" style="width:80px; height:30px;" />
              <select name="size_id[]"  id="size_id'.$no.'" style="width:100px; height:30px;" required disabled>
            <option value="">--Size--</option>';
            foreach($SizeList as  $rowfg)
            {
                $html.='<option value="'.$rowfg->size_id.'"';
            
                $rowfg->size_id == $row->size_id ? $html.='selected="selected"' : ''; 
                
                $html.='>'.$rowfg->size_name.'</option>';
            }
            $html.='</select></td>';
            $html.='<td>
            <input type="text" name="ratio[]" id="ratio'.$no.'" value="'.$row->ratio.'" style="width:80px; height:30px;" required readonly/> </td>';
            
            $html.='<td>
            <input type="text" name="layers[]" id="layers'.$no.'" value="'.$layers.'" style="width:80px; height:30px;" required readonly/> </td>';
            
            $html.='<td><input type="text" class="QTY" onkeyup="mycalc();"  name="qty[]" id="qty'.$no.'" value="'.intval(($layers)*$row->ratio).'" style="width:80px; height:30px;" readonly required/></td>';
          
            $html .='</tr>';
            $no=$no+1;
            }
            
       }
       return response()->json(['html' => $html, 'isExist'=>$isExist]); 
     
    }
 
    public function getEndDataDetails(Request $request)
    {  
        $task_id= $request->input('task_id');
        $table_avg= $request->input('table_avg');
        $track_code= $request->input('track_code');
        $vpo_code= $request->input('vpo_code');
        
        $layers= $request->input('layers');
        $SizeList = TaskDetailModel::select('task_details.size_id','size_name')->join('size_detail','size_detail.size_id','=','task_details.size_id')->where('task_details.task_id', $task_id)->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
     
        
        $table_id=$request->input('table_id');
         
        $ColorList = ItemModel::where('item_master.delflag','=', '0')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
         
        $Count = CuttingBalanceDetailModel::where('track_code','=', $track_code)->count(); 
        $sr_no = CuttingBalanceDetailModel::where('track_code','=', $track_code)->max('sr_no');
           //print_r($Count);
        if($Count==0)
        {
            

             $Roll = DB::table('fabric_outward_details')
                    ->select('fabric_outward_details.track_code','fabric_outward_details.part_id', 'fabric_outward_details.item_code', 'fabric_checking_details.width', DB::raw('sum(fabric_outward_details.meter) as meter'), 'fabric_outward_details.shade_id')
                    ->join('fabric_checking_details','fabric_checking_details.track_code','=','fabric_outward_details.track_code')->where('fabric_outward_details.track_code',$track_code)->where('fabric_outward_details.vpo_code',$vpo_code)->get();
             
         
            
        }
        else
        {
            
                //   DB::enableQueryLog();

            //  $Roll = CuttingBalanceDetailModel::select('cutting_balance_details.*', 'fabric_checking_details.width', DB::raw("sum(cutting_balance_details.balance_meter) as balance_meter"),DB::raw("sum(cutting_balance_details.cpiece_meter) as cpiece_meter"))
            //         ->join('fabric_checking_details','fabric_checking_details.track_code','=','cutting_balance_details.track_code')->where('cutting_balance_details.sr_no', $sr_no)->get();
             
            // DB::table('cutting_balance_details')->select('track_code', 'color_id', 'width', 'meter', 'balance_meter','cpiece_meter')->whereRaw('sr_no = (select max(`sr_no`) from cutting_balance_details where track_code="'.$track_code.'")')->get();
        //DB::enableQueryLog();
            $Roll = DB::SELECT("SELECT fabric_outward_details.*, ifnull(sum(meter),0) - (SELECT ifnull(sum(used_meter),0) FROM `cutting_balance_details` WHERE vpo_code='".$vpo_code."' AND track_code= '".$track_code."') as meter  FROM `fabric_outward_details` where vpo_code = '".$vpo_code."' AND track_code = '".$track_code."'");
            
      
        //dd(DB::getQueryLog());
            // $query = DB::getQueryLog();
            // $query = end($query);
            // dd($query);
           
           
        }
     
        $balance_meter = isset($Roll[0]->balance_meter) ? $Roll[0]->balance_meter : 0;
        $cpiece_meter = isset($Roll[0]->cpiece_meter) ? $Roll[0]->cpiece_meter : 0;
        $meters = isset($Roll[0]->meter) ? $Roll[0]->meter : 0; 
    // print_r($Count);
        if($layers==0 )
        {
            // if($Count!=0)
            // {
            //   if($balance_meter!=0)
            //   {
            //       $meter= $balance_meter;
            //       $layers= floor($balance_meter/$table_avg);
            //   }
            //   elseif($cpiece_meter!=0)
            //   {
            //       $meter= $cpiece_meter;
            //       $layers= floor($cpiece_meter/$table_avg);
            //   }
            // //   else
            // //   {
            // //       $meter=0;
            // //         $layers=0;
            // //   }
               
            // }
            // else
            // {
            //      $layers= floor($meters/$table_avg);
            // }
            $layers= floor($meters/$table_avg);
            $meter= round($meters,2);
        }
        else
        {

            //$meter=$Roll->meter;
           // floatval($table_avg*$layers);
           
        //   if($Roll->balance_meter!=0)
        //       {
        //           $meter= $Roll->balance_meter;
        //         //   $layers= floor($Roll->balance_meter/$table_avg);
        //       }
        //       elseif($Roll->cpiece_meter!=0)
        //       {
        //           $meter= $Roll->cpiece_meter;
        //         //   $layers= floor($Roll->cpiece_meter/$table_avg);
        //       }
               
            //   if($Count!=0)
            // {
            //   if($balance_meter!=0)
            //   {
            //       $meter= $balance_meter;
            //      //  $layers= floor($Roll->balance_meter/$table_avg);
            //   }
            //   elseif($cpiece_meter!=0)
            //   {
            //       $meter= $cpiece_meter;
            //       // $layers= floor($Roll->cpiece_meter/$table_avg);
            //   }
            // //   else
            // //   {
            // //       $meter=0;
            // //         $layers=0;
            // //   }
               
            // }
            // else
            // {
            //      $meter= round($meters,2);
            //     // $layers= floor($Roll->meter/$table_avg);
            // }
            
               $meter= round($meters,2);
          
        }
 
        $fabricData  = DB::table('fabric_outward_details')->select('*')->where('vpo_code','=',$vpo_code)->where('track_code','=',$track_code)->get();
        $isExist = count($fabricData);
        $html = '';
        
        if($isExist > 0)
        { 
            $no=1;
            $html .='<tr class="thisRow">';
           
            $html .='
            <td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;" readOnly required /></td>';
            $html.='<td>
            <input type="text" name="track_codess[]" class="track_code" id="track_codes'.$no.'" value="'.$track_code.'" style="width:80px; height:30px;" readonly required/>
            <input type="hidden" name="part_idss[]"   id="part_idss'.$no.'" value="'.$Roll[0]->part_id.'" style="width:80px;" readonly required/> 
            
            </td> 
             
            <td> <select name="item_codes[]"  id="item_codes'.$no.'" style="width:200px; height:30px;" required disabled>
            <option value="">--Item List--</option>';
            foreach($ItemList as  $rowitem)
            {
                $html.='<option value="'.$rowitem->item_code.'"';
            
                $rowitem->item_code == $Roll[0]->item_code ? $html.='selected="selected"' : ''; 
                
                $html.='>'.$rowitem->item_name.'</option>';
            }
            $html.='</select></td>';
            
            $html.='<td>
            <input type="text" name="widths[]" id="widths'.$no.'" readonly value="'.$Roll[0]->width.'" style="width:80px;height:30px;" readOnly required/> </td>';
            $html.='<td>
            <input type="text" name="meters[]" id="meters'.$no.'" readonly value="'.$meter.'" style="width:80px;height:30px;" readOnly required/> </td> ';
             $html.='<td>
              <select name="shade_ids[]"  id="shade_ids'.$no.'" style="width:100px; height:30px;" required disabled>
            <option value="">--Shade--</option>';
            foreach($ShadeList as  $rowshd)
            {
                $html.='<option value="'.$rowshd->shade_id.'"';
            
                $rowshd->shade_id == $Roll[0]->shade_id ? $html.='selected="selected"' : ''; 
                
                $html.='>'.$rowshd->shade_name.'</option>';
            }
            $html.='</select></td>';
            
            $html.='<td>
            <input type="text" name="layerss[]" class="Layers"   id="layerss'.$no.'" value="'.$layers.'" style="width:80px;height:30px;" required/> </td>
            <td> 
            <input type="text" name="used_meters[]" class="UMETER" id="used_meters'.$no.'" value="'.($table_avg*$layers).'" readonly style="width:80px;height:30px;" required/> </td> ';
            if(($meter -($table_avg*$layers))>$table_avg)
            {
                $html.='
                <td><input type="text" onkeyup="mycalc();" name="bpiece_meters[]" id="bpiece_meters'.$no.'" value="'.number_format((float)($meter -($table_avg*$layers)), 2, '.', '').'" style="width:80px;" required/> </td>
                <td><input type="text" name="cpiece_meters[]" onkeyup="mycalc();" class="cPiece" id="cpiece_meters'.$no.'" value="0" style="width:80px;" required/> </td>';
            
            }
            elseif(($meter -($table_avg*$layers))<$table_avg && ($meter -($table_avg*$layers))>=0)
            {
            
                $html.='
                <td><input type="text" name="bpiece_meters[]"  id="bpiece_meters'.$no.'"  value="0" style="width:80px;height:30px;" required/> </td>
                <td><input type="text" name="cpiece_meters[]" onkeyup="mycalc();"  class="cPiece" id="cpiece_meters'.$no.'"  value="'.number_format((float)($meter-($table_avg*$layers)), 2, '.', '').'" style="width:80px;height:30px;" required/> </td>';
            
            }
            else
            {
            
                $html.='
                <td><input type="text" name="bpiece_meters[]"  id="bpiece_meters'.$no.'"  value="0" style="width:80px;height:30px;" required/> </td>
                <td><input type="text" name="cpiece_meters[]" onkeyup="mycalc();"  class="cPiece" id="cpiece_meters'.$no.'"  value="0" style="width:80px;height:30px;" required/> </td>';
            
            }
            $track_code1 = "'$track_code'";
            $html.='  <td><input type="text" name="actual_balances[]"  class="aBalance" id="actual_balances'.$no.'" onchange="calculateShortMeter(this);" value="0" style="width:80px;height:30px;" required/> </td>
            <td><input type="text" name="dpiece_meters[]" onkeyup="mycalc();"  class="dPiece" id="dpiece_meters'.$no.'" value="0" style="width:80px;height:30px;" required/> </td> 
            <td><input type="text" name="short_meters[]"  id="short_meters'.$no.'" class="SPiece"  value="0" style="width:80px;height:30px;" required/> </td>';
            if(($meter -($table_avg*$layers))<0){ $extra_meter=abs($meter-($table_avg*$layers));}else{ $extra_meter=0;}
             $html.='<td><input type="text" name="extra_meters[]" onkeyup="mycalc();"  class="EPiece" id="extra_meters'.$no.'"  value="'.$extra_meter.'" style="width:80px;height:30px;" required/> </td>
                  <td><input type="button" class="btn btn-danger pull-left" onclick="deleteEndDataRow2('.$track_code1.'); delete_Row2('.$track_code1.');" value="X" ></td>';
          
                                 
            $html .='</tr>';
            $no=$no+1;
       
        }
        return response()->json(['html' => $html, 'isExist'=>$isExist]);
         
    } 
    
    public function FabricCuttingPrint($cu_code)
    {
        $CuttingMasterList = DB::table('cutting_master')->select('cutting_master.*','ledger_master.ac_name as vendor_name','fg_master.fg_name','main_style_master.mainstyle_name','sub_style_master.substyle_name',
                            'vendor_purchase_order_master.sales_order_no')
                            ->join('ledger_master', 'ledger_master.ac_code', '=', 'cutting_master.vendorId')
                            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'cutting_master.mainstyle_id')
                            ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'cutting_master.substyle_id')
                            ->join('fg_master', 'fg_master.fg_id', '=', 'cutting_master.fg_id') 
                            ->join('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'cutting_master.vpo_code')
                            ->where('cutting_master.cu_code','=',$cu_code)
                            ->first();
                            
        $CuttingBalanceDetailList = DB::table('cutting_balance_details')->select('cutting_balance_details.*','item_master.item_name','shade_master.shade_name','fabric_checking_details.width')
                            ->join('fabric_checking_details','fabric_checking_details.track_code','=','cutting_balance_details.track_code')
                            ->join('item_master', 'item_master.item_code', '=', 'cutting_balance_details.item_code')
                            ->join('shade_master', 'shade_master.shade_id', '=', 'cutting_balance_details.shade_id')
                            ->where('cutting_balance_details.cu_code','=',$cu_code)
                            ->get();
       
        $CuttingDetailList = DB::table('cutting_details')->select(DB::raw('sum(cutting_details.qty) as cut_qty'),'cutting_details.ratio','size_detail.size_name')
                    ->join('size_detail', 'size_detail.size_id', '=', 'cutting_details.size_id') 
                    ->where('cutting_details.cu_code','=',$cu_code)
                    ->groupBy('cutting_details.size_id')
                    ->get();
                     
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
        
        return view('FabricCuttingPrint',compact('CuttingMasterList','CuttingBalanceDetailList','CuttingDetailList','FirmDetail'));
    }

}
