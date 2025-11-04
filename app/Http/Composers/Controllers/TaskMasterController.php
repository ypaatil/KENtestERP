<?php

namespace App\Http\Controllers;

use App\Models\TaskMasterModel;
use Illuminate\Http\Request;
use App\Models\SizeModel;
use App\Models\LedgerModel;
use Illuminate\Support\Facades\DB;
use App\Models\TaskDetailModel;
use App\Models\PartModel;
use App\Models\ItemModel;
use App\Models\CuttingMasterModel;
use App\Models\FabricTrimCardMatchDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\VendorPurchaseOrderModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\SizeDetailModel;
use Session;



class TaskMasterController extends Controller
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
->where('form_id', '39')
->first();     
        
        
         //   DB::enableQueryLog();
         $TaskList = TaskMasterModel::join('usermaster', 'usermaster.userId', '=', 'task_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'task_master.vendorId')
         ->join('part_master', 'part_master.part_id', '=', 'task_master.part_id')
          ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'task_master.mainstyle_id')
        ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'task_master.substyle_id')
         ->whereNotIn('task_id',function($query){
               $query->select('table_task_code')->from('cutting_master');
            })->get(['task_master.*','usermaster.username','ledger_master.Ac_name','part_master.part_name', 'mainstyle_name','substyle_name']);
     // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('TaskMasterList', compact('TaskList','chekform'));
    }



 public function CompletedTaskList()
    {
        
          $chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '39')
->first();     
        
        
         //   DB::enableQueryLog();
         $TaskList = TaskMasterModel::join('usermaster', 'usermaster.userId', '=', 'task_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'task_master.vendorId')
         ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'task_master.mainstyle_id')
        ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'task_master.substyle_id')
         ->join('part_master', 'part_master.part_id', '=', 'task_master.part_id')
          ->whereIn('task_id',function($query){
               $query->select('table_task_code')->from('cutting_master');
            })->get(['task_master.*','usermaster.username','ledger_master.Ac_name','part_master.part_name', 'mainstyle_name','substyle_name']);
     // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('TaskMasterList', compact('TaskList','chekform'));
    }








    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='TABLE_TASK'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->get();
        $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();
        $PartList= PartModel::where('part_master.delflag','=', '0')->get();
        $TableList = DB::table('ctable_master')->get();
       
        //  DB::enableQueryLog();
        
        
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        // DB::enableQueryLog();
        $VPOrderList= VendorPurchaseOrderModel::select('vpo_code','sales_order_no')->where('vendorId','56')->whereNotIn('vendor_purchase_order_master.vpo_code',function($query){
              $query->select('vpo_code')->from('task_master');
        })->get();
        
        return view('TaskMaster',compact('Ledger', 'MainStyleList','SubStyleList','FGList', 'SizeList','TableList','counter_number', 'PartList','VPOrderList'));
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
             
            'task_id'=>'required',
            'task_date'=>'required',
           
             
            'userId'=>'required',
            'c_code'=>'required',

        ]);
        
               //DB::enableQueryLog();

  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','TABLE_TASK')
   ->where('firm_id','=',1)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
$TrNo=$codefetch->code.'-'.$codefetch->tr_no;      
         
$item_code = implode(',', $request->item_code);
$vpo_code=implode(',', $request->vpo_code);     
           
           
$data1=array(
       
        'task_id'=>$TrNo,
        'task_date'=>$request->task_date,
        'vendorId'=>$request->vendorId,
        'vpo_code'=>$vpo_code,
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'table_id'=>$request->table_id,
        'table_avg'=>$request->table_avg,
        'layers'=>$request->layers,
        'part_id'=>$request->part_id,
        'item_code'=>$item_code,
        'narration'=>$request->narration,
        'delflag'=>'0',
        'endflag'=>'0',
        'userId'=>$request->userId,
        'c_code'=>$request->c_code,
        'size_counter'=>'0'
);

TaskMasterModel::insert($data1);


 //   DB::enableQueryLog(); 
         DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='TABLE_TASK'");
//  $query = DB::getQueryLog();
//          $query = end($query);
//          dd($query);


$size_id = $request->input('size_id');
if(count($size_id)>0)
{

for($x=0; $x<count($size_id); $x++) {
    # code...

$data2[]=array(
          
                    'task_id'=>$TrNo,
                    'task_date'=>$request->task_date,
                    'vendorId'=>$request->vendorId,
                    'vpo_code'=>$vpo_code,
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'table_id'=>$request->table_id,
                    'table_avg'=>$request->table_avg,
                    'part_id'=>$request->part_id,
                     'item_code'=>$item_code,
                    'size_id'=>$request->size_id[$x],
                    'order_qty'=>$request->order_qty[$x],
                    'cut_qty'=>$request->cut_qty[$x],
                    'balance_qty'=>$request->balance_qty[$x],
                    'ratio'=>$request->ratio[$x], 
     
         );
        
       }
TaskDetailModel::insert($data2);

}

return redirect()->route('Task.index')->with('message', 'New Record Saved Succesfully..!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskMasterModel  $taskMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(TaskMasterModel $taskMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskMasterModel  $taskMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $TaskMasterList = TaskMasterModel::find($id);
    //   DB::enableQueryLog();
    
       $vpo_codes=explode(',',$TaskMasterList->vpo_code);
       $countVPOCode= count($vpo_codes);  
       $ItemList = DB::select("select item_code, item_name from item_master where  
       item_master.item_code in ($TaskMasterList->item_code)");
        
        $PartList= PartModel::where('part_master.delflag','=', '0')->get();
        // DB::enableQueryLog();
        $TaskDetaillist = TaskDetailModel::join('size_detail','size_detail.size_id', '=', 'task_details.size_id')
        ->where('task_details.task_id','=', $TaskMasterList->task_id)->get(['task_details.*','size_detail.size_name']);
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
        $TableList = DB::table('ctable_master')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
          
        // DB::enableQueryLog();
        if($countVPOCode==1)
        {
                $SizeDetailList = DB::select("select distinct vendor_purchase_order_size_detail2.size_id, size_name from vendor_purchase_order_size_detail2
                inner join size_detail on size_detail.size_id=vendor_purchase_order_size_detail2.size_id
                where vendor_purchase_order_size_detail2.vpo_code in ('".$TaskMasterList->vpo_code."')");
                // $S2=TaskMasterModel::select('vpo_code','sales_order_no')->where('vpo_code',$TaskMasterList->vpo_code)->get();
              
        }
        else
        {
                foreach($vpo_codes as $vpo){ $vpo_code=$vpo_code."'".$vpo."',"; } $vpo_code=rtrim($vpo_code,",");
                $SizeDetailList = DB::select('select distinct vendor_purchase_order_size_detail2.size_id, size_name from vendor_purchase_order_size_detail2
                inner join size_detail on size_detail.size_id=vendor_purchase_order_size_detail2.size_id
                where vendor_purchase_order_size_detail2.vpo_code in ('.$vpo_code.')');
                //   $S2=TaskMasterModel::select('vpo_code')->whereNotIn('vpo_code',$vpo_code)->get();
                 
                 
        }
            //   $query = DB::getQueryLog();
            // $query = end($query);
            // dd($query);
        
        $VPOrderList= VendorPurchaseOrderModel::select('vpo_code','sales_order_no')->get();
           
           
         
      
           
            
  return view('TaskMasterEdit',compact('TaskMasterList','PartList','SizeDetailList', 'ItemList', 'Ledger','MainStyleList','SubStyleList','FGList','TableList', 'TaskDetaillist','VPOrderList'));
    
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskMasterModel  $taskMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
             
            'task_id'=>'required',
            'task_date'=>'required',
            'userId'=>'required',
            'c_code'=>'required',

        ]);
 
 
 $item_code = implode(',', $request->item_code);
$vpo_code=implode(',', $request->vpo_code); 
$data1=array(
       
        'task_date'=>$request->task_date,
        'vendorId'=>$request->vendorId,
        'vpo_code'=>$vpo_code,
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'table_id'=>$request->table_id,
        'table_avg'=>$request->table_avg,
        'layers'=>$request->layers,
        'part_id'=>$request->part_id,
        'item_code'=>$item_code,
        'narration'=>$request->narration,
        'delflag'=>'0',
        'endflag'=>$request->endflag,
        'userId'=>$request->userId,
        'c_code'=>$request->c_code,
        
);

$TaskMasterList = TaskMasterModel::findOrFail($request->input('task_id'));  
$TaskMasterList->fill($data1)->save();

DB::table('task_details')->where('task_id', $request->input('task_id'))->delete();
 
$size_id = $request->input('size_id');
if(count($size_id)>0)
{

for($x=0; $x<count($size_id); $x++) {
    # code...
       $data2=array(
          
                    'task_id'=>$request->task_id,
                    'task_date'=>$request->task_date,
                    'vendorId'=>$request->vendorId,
                    'vpo_code'=>$vpo_code,
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'table_id'=>$request->table_id,
                    'table_avg'=>$request->table_avg,
                    'part_id'=>$request->part_id,
                     'item_code'=>$item_code,
                    'size_id'=>$request->size_id[$x],
                    'order_qty'=>$request->order_qty[$x],
                    'cut_qty'=>$request->cut_qty[$x],
                    'balance_qty'=>$request->balance_qty[$x],
                    'ratio'=>$request->ratio[$x], 
        );
        
         TaskDetailModel::insert($data2);
         
        }
    }
 
    return redirect()->route('Task.index')->with('message', 'Update Record Succesfully..!');
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskMasterModel  $taskMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Task = CuttingMasterModel::where('table_task_code','=', $id)->count(); 
          
        if($Task==0)
          {
            DB::table('task_master')->where('task_id', $id)->delete();
            DB::table('task_details')->where('task_id', $id)->delete();
            
            Session::flash('delete', 'Deleted record successfully'.$id); 
        }
        else
        {
            Session::flash('delete', "This Records Can't be deleted, As Cutting Against this Task is in Record..!"); 
        }
        
        
        
    }


    public function getCommanDetails(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
        $MasterdataList = DB::select("select Ac_code,    style_no from buyer_purchse_order_master
        
        where buyer_purchse_order_master.delflag=0 and tr_code='".$sales_order_no."'");
        return json_encode($MasterdataList);
    
    }
 
 
 public function GetColorList(Request $request)
{
   // if (!$request->job_code) {
        $html = '<option value="">--Color--</option>';
       // } else {
        //$html = '';
      //  DB::enableQueryLog();
        $ColorList = DB::select("select body_color_id as color_id from fabric_trim_card_match_details as f1
        where f1.job_code='".$request->job_code."' union select trim_color_id as color_id from fabric_trim_card_match_details
        as f2 where f2.job_code='".$request->job_code."'");
        foreach ($ColorList as $row) {
            $color = ColorModel::where('color_id','=', $row->color_id)->first(); 
                $html .= '<option value="'.$row->color_id.'">'.$color->color_name.'</option>';
              
        }
         //}
        
        return response()->json(['html' => $html]);
}
 
 
 
    public function getBalanceDetails(Request $request)
    { 
        $part_id= $request->input('part_id');
        $vpo_code= $request->input('vpo_code');
        $item_code= $request->input('item_code');
          // $SizeDetailList2 = DB::table('vendor_purchase_order_size_detail2')->select('size_id')->whereIn('vpo_code',$vpo_code)->DISTINCT();
      $vpo_code = explode(',', $request->vpo_code);
        // DB::enableQueryLog();
        $SizeDetailList = DB::select("select distinct vendor_purchase_order_size_detail2.size_id, size_name from vendor_purchase_order_size_detail2
        inner join size_detail on size_detail.size_id=vendor_purchase_order_size_detail2.size_id
        where vendor_purchase_order_size_detail2.vpo_code in ('".$request->vpo_code."')");
     
    //   $query = DB::getQueryLog();
    //         $query = end($query);
    //         dd($query);
        // if($part_id==1)
        // {
        //     $CuttingRatio = DB::select("SELECT size_id, size_qty, 
        //     ifnull((select sum(qty) from cutting_details where cutting_details.vpo_code=vendor_purchase_order_size_detail2.vpo_code
        //     and cutting_details.size_id=vendor_purchase_order_size_detail2.size_id and cutting_details.part_id=1),0) as cutQty, 
        //     (size_qty- ifnull((select sum(qty) from cutting_details where cutting_details.job_code=vendor_purchase_order_size_detail2.po_code
        //     and cutting_details.size_id=vendor_purchase_order_size_detail2.size_id and cutting_details.part_id=1),0)) as Balance
        //     FROM `vendor_purchase_order_size_detail2` WHERE vendor_purchase_order_size_detail2.vpo_code in (".$request->vpo_code.")
        //     and vendor_purchase_order_size_detail2.item_code in (".$item_code.")");
        // }
        // else
        // {
        //   // DB::enableQueryLog();
        //   $Trim=DB::select("select body_color_id from fabric_trim_card_match_details where job_code='".$sales_order_no."' and trim_color_id=".$color_id);
        //   //FabricTrimCardMatchDetailModel::where('job_code',"'".$job_code."'")->where('trim_color_id',$color_id)->first();
        //   // print_r($body_color_id->body_color_id);
        //   foreach($Trim as $bcid){ $body_color_id=$bcid->body_color_id;}
        //     // $query = DB::getQueryLog();
        //     // $query = end($query);
        //     // dd($query);
        //     $CuttingRatio = DB::select("SELECT size_id, size_qty, 
        //     ifnull((select sum(qty) from cutting_details where cutting_details.vpo_code=buyer_purchase_order_size_detail.vpo_code
        //     and cutting_details.size_id=buyer_purchase_order_size_detail.size_id and cutting_details.part_id=1),0) as cutQty, 
        //     (size_qty- ifnull((select sum(qty) from cutting_details where cutting_details.job_code=buyer_purchase_order_size_detail.tr_code
        //     and cutting_details.size_id=buyer_purchase_order_size_detail.size_id and cutting_details.part_id=1),0)) as Balance
        //     FROM `buyer_purchase_order_size_detail` WHERE buyer_purchase_order_size_detail.vpo_code in (".$request->vpo_code.")
        //     and buyer_purchase_order_size_detail.color_id='".$body_color_id."'");
        // }
    
    $html = '';
    
$no=1;
foreach ($SizeDetailList as $row) 
{
    $html .='<tr class="thisRow">';
   
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
$html.='<td>
  <select name="size_id[]"  id="size_id'.$no.'" style="width:100px;" required disabled>
<option value="">--Size--</option>';
foreach($SizeDetailList as  $rowfg)
{
    $html.='<option value="'.$rowfg->size_id.'"';
 $rowfg->size_id == $row->size_id ? $html.='selected="selected"' : ''; 
    $html.='>'.$rowfg->size_name.'</option>';
}
$html.='</select></td>';
// $html.='<td>
// <input type="text" name="order_qty[]" id="order_qty'.$no.'" value="'.$row->size_qty.'" style="width:80px;" required/> </td>';

// $html.='<td>
// <input type="text" name="cut_qty[]" id="cut_qty'.$no.'" value="'.$row->cutQty.'" style="width:80px;" required/> </td>';
// $html.='<td>
// <input type="text" name="balance_qty[]" id="balance_qty'.$no.'" value="'.$row->Balance.'" style="width:80px;" required/> </td>';
  $html.='<td>
<input type="number" name="ratio[]" id="ratio'.$no.'" value="" style="width:80px;" required/> </td>';
$html.='<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
';
    $html .='</tr>';
    $no=$no+1;
  }
    
    return response()->json(['html' => $html]);
     
    }
   

}
