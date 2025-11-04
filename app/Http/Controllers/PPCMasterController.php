<?php

namespace App\Http\Controllers;

use App\Models\PPCMasterModel;
use App\Models\SAHPPCMasterModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\OpenOrderPPCDetailModel;
use App\Models\DeviationPPCMasterModel;
use App\Models\DeviationPPCDateWiseMCModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\LineModel;
use Illuminate\Support\Facades\DB; 
use DataTables;
use Session;
 
class PPCMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
         $chekform = DB::table('form_auth')
         ->where('emp_id', Session::get('userId'))
         ->where('form_id', '149')
         ->first();     
                
         $Ledger = LedgerModel::where('ledger_master.delflag', '=', '0')
            ->where('ledger_master.Ac_code', '>', '39')
            ->where('ledger_master.bt_id', '>', '3')
            ->get()
            ->sortBy(function ($ledger) { 
                preg_match('/\d+/', $ledger->ac_short_name, $matches);
                return $matches ? (int)$matches[0] : PHP_INT_MAX;  
         });
         $Ledger1 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->get();
         $LineList = LineModel::where('line_master.delflag','=', '0')->get();
            //   DB::enableQueryLog();
         $PPCList = PPCMasterModel::
                select('ppc_master.sr_no','ppc_master.vendorId', 'ppc_master.line_id', 'ppc_master.machine_count', 'ppc_master.available_mins', 'ppc_master.line_efficiency',
             'ppc_master.sam', 'ppc_master.production_capacity', 'ppc_master.target', 'ppc_master.start_date', 'ppc_master.end_date','ppc_master.color_order_qty',
             'ppc_master.userId', 'ppc_master.endFlag', 'ppc_master.sales_order_no', 'ppc_master.color_id','ppc_master.color_order_qty')->
                join('usermaster', 'usermaster.userId', '=', 'ppc_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'ppc_master.vendorId')
            ->join('line_master', 'line_master.line_id', '=', 'ppc_master.line_id')
            ->where('ppc_master.endFlag','=' , '0')
            ->get();
         
         $ColorList = DB::table('buyer_purchase_order_detail')->select('color_master.color_id','color_master.color_name')
                    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')->DISTINCT()->get();
                    
         $SalesList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code as sales_order_no') 
                    ->where('buyer_purchse_order_master.job_status_id','=' , 1)
                    ->where('buyer_purchse_order_master.og_id','!=' , 4)
                    ->where('buyer_purchse_order_master.order_type','!=' , 2)
                    ->DISTINCT()
                    ->get();
                 
         $EndDate = DB::table('ppc_master')->select('end_date')->latest('sr_no')->first();
         $searchVendorId = isset($request->searchVendorId) ? $request->searchVendorId : '';
         $searchLineId =  isset($request->searchLineId) ? $request->searchLineId : '';
         
         return view('PPCMaster', compact('PPCList','chekform','Ledger','Ledger1','LineList','EndDate','searchVendorId','searchLineId', 'ColorList', 'SalesList'));
    }

 



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //echo '<pre>'; print_r($_POST);exit;
       
        $vendorId = $request->input('vendorId');
        $searchVendorId  = $request->searchVendorId;
        $searchLineId  = $request->searchLineId;
        
        DB::table('ppc_master')->WHERE('vendorId', '=', $searchVendorId)->WHERE('line_id', '=', $searchLineId)->delete();
        if($vendorId)
        {
       
        for($x=0; $x<count($vendorId); $x++) 
        {
            # code...
        
       
        // if($request->sr_no[$x]==0)
        //         {       
                    
                    $data2=array(
                        'vendorId'=>$request->vendorId[$x],
                        'line_id'=>$request->line_id[$x],
                        'sales_order_no'=>$request->sales_order_no[$x],
                        'color_id'=>$request->color_id[$x],
                        'color_order_qty'=>$request->color_order_qty[$x],
                        'machine_count'=>$request->machine_count[$x],
                        'available_mins'=>$request->available_mins[$x],
                        'line_efficiency'=>$request->line_efficiency[$x],
                        'sam'=>$request->sam[$x],
                        'production_capacity'=>$request->production_capacity[$x],
                        'target'=>$request->target[$x],
                        'start_date'=>$request->start_date[$x],
                        'end_date'=>$request->end_date[$x],
                        'userId'=>$request->userId,
                        'endFlag'=>0,
           
                 );
                 PPCMasterModel::insert($data2);
                // }
                // else
                // {
                //       PPCMasterModel::where('sr_no', $request->sr_no[$x])->update(array(
                //           'vendorId' => $request->vendorId[$x],
                //             'line_id'=>$request->line_id[$x],
                //             'sales_order_no'=>$request->sales_order_no[$x],
                //             'color_id'=>$request->color_id[$x],
                //             'machine_count'=>$request->machine_count[$x],
                //             'available_mins'=>$request->available_mins[$x],
                //             'line_efficiency'=>$request->line_efficiency[$x],
                //             'sam'=>$request->sam[$x],
                //             'production_capacity'=>$request->production_capacity[$x],
                //             'target'=>$request->target[$x],
                //             'start_date'=>$request->start_date[$x],
                //             'end_date'=>$request->end_date[$x],
                //             'userId'=>$request->userId,
                //             'endFlag'=>0,
                //       ));
                       
                                     
                // }
                
               } 
          }
          $searchVendorId = $request->input('searchVendorId');
          $searchLineId = $request->input('searchLineId');
          if($searchVendorId !="")
          {
              $chekform = DB::table('form_auth')
             ->where('emp_id', Session::get('userId'))
             ->where('form_id', '149')
             ->first();     
                    
             $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->get();
             $Ledger1 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->where('ledger_master.Ac_code','=', $searchVendorId)->get();
             $LineList = LineModel::where('line_master.delflag','=', '0')->get();
             //DB::enableQueryLog();
                $PPCList = PPCMasterModel::
                    select('ppc_master.sr_no','ppc_master.vendorId', 'ppc_master.line_id', 'ppc_master.machine_count', 'ppc_master.available_mins', 'ppc_master.line_efficiency',
                 'ppc_master.sam', 'ppc_master.production_capacity', 'ppc_master.target', 'ppc_master.start_date', 'ppc_master.end_date','ppc_master.color_order_qty',
                 'ppc_master.userId', 'ppc_master.endFlag', 'ppc_master.sales_order_no', 'ppc_master.color_id')->
                    join('usermaster', 'usermaster.userId', '=', 'ppc_master.userId')
                ->join('ledger_master', 'ledger_master.Ac_code', '=', 'ppc_master.vendorId')
                ->join('line_master', 'line_master.line_id', '=', 'ppc_master.line_id')
                ->where('ppc_master.endFlag','=' , '0')
                ->where('ppc_master.vendorId','=' ,  $searchVendorId)
                ->where('ppc_master.line_id','=' , $searchLineId)
                ->get();
                //dd(DB::getQueryLog());
             $ColorList = DB::table('buyer_purchase_order_detail')->select('color_master.color_id','color_master.color_name')
                    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')->DISTINCT()->get();
             $SalesList = DB::table('vendor_work_order_master')->select('sales_order_no')->DISTINCT()->get();
             
             $EndDate = DB::table('ppc_master')->select('end_date')->where('vendorId','=' ,  $searchVendorId)->where('line_id','=' , $searchLineId)->latest('sr_no')->first();
                
                //return redirect()->route('PPCMaster.index');
             return redirect()->route('PPCMaster.index',compact('searchVendorId','searchLineId'))->with('message', 'New Record Saved Succesfully..!');  
          }
          else
          {
             return redirect()->route('PPCMaster.index',compact('searchVendorId','searchLineId'))->with('message', 'New Record Saved Succesfully..!');  
          }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(PPCMasterModel $PPCMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//       $PPCMasterList = PPCMasterModel::find($id);
//     //   DB::enableQueryLog();
    
//       $vpo_codes=explode(',',$PPCMasterList->vpo_code);
//       $countVPOCode= count($vpo_codes);  
//       $ItemList = DB::select("select item_code, item_name from item_master where  
//       item_master.item_code in ($PPCMasterList->item_code)");
        
//         $PartList= PartModel::where('part_master.delflag','=', '0')->get();
//         // DB::enableQueryLog();
//         $TaskDetaillist = TaskDetailModel::join('size_detail','size_detail.size_id', '=', 'task_details.size_id')
//         ->where('task_details.task_id','=', $PPCMasterList->task_id)->get(['task_details.*','size_detail.size_name']);
//         $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
//         $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
//         $TableList = DB::table('ctable_master')->get();
//         $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
//         $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
//         $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
          
//         // DB::enableQueryLog();
//         if($countVPOCode==1)
//         {
//                 $SizeDetailList = DB::select("select distinct vendor_purchase_order_size_detail2.size_id, size_name from vendor_purchase_order_size_detail2
//                 inner join size_detail on size_detail.size_id=vendor_purchase_order_size_detail2.size_id
//                 where vendor_purchase_order_size_detail2.vpo_code in ('".$PPCMasterList->vpo_code."')");
//                 // $S2=PPCMasterModel::select('vpo_code','sales_order_no')->where('vpo_code',$PPCMasterList->vpo_code)->get();
              
//         }
//         else
//         {
//                 foreach($vpo_codes as $vpo){ $vpo_code=$vpo_code."'".$vpo."',"; } $vpo_code=rtrim($vpo_code,",");
//                 $SizeDetailList = DB::select('select distinct vendor_purchase_order_size_detail2.size_id, size_name from vendor_purchase_order_size_detail2
//                 inner join size_detail on size_detail.size_id=vendor_purchase_order_size_detail2.size_id
//                 where vendor_purchase_order_size_detail2.vpo_code in ('.$vpo_code.')');
//                 //   $S2=PPCMasterModel::select('vpo_code')->whereNotIn('vpo_code',$vpo_code)->get();
                 
                 
//         }
//             //   $query = DB::getQueryLog();
//             // $query = end($query);
//             // dd($query);
        
//         $VPOrderList= VendorPurchaseOrderModel::select('vpo_code','sales_order_no')->get();
           
           
         
      
           
            
//   return view('PPCMasterEdit',compact('PPCMasterList','PartList','SizeDetailList', 'ItemList', 'Ledger','MainStyleList','SubStyleList','FGList','TableList', 'TaskDetaillist','VPOrderList'));
    
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//         $this->validate($request, [
             
//             'task_id'=>'required',
//             'task_date'=>'required',
//             'userId'=>'required',
//             'c_code'=>'required',

//         ]);
 
 
//  $item_code = implode(',', $request->item_code);
// $vpo_code=implode(',', $request->vpo_code); 
// $data1=array(
       
//         'task_date'=>$request->task_date,
//         'vendorId'=>$request->vendorId,
//         'vpo_code'=>$vpo_code,
//         'mainstyle_id'=>$request->mainstyle_id,
//         'substyle_id'=>$request->substyle_id,
//         'fg_id'=>$request->fg_id,
//         'style_no'=>$request->style_no,
//         'style_description'=>$request->style_description,
//         'table_id'=>$request->table_id,
//         'table_avg'=>$request->table_avg,
//         'layers'=>$request->layers,
//         'part_id'=>$request->part_id,
//         'item_code'=>$item_code,
//         'narration'=>$request->narration,
//         'delflag'=>'0',
//         'endflag'=>$request->endflag,
//         'userId'=>$request->userId,
//         'c_code'=>$request->c_code,
        
// );

// $PPCMasterList = PPCMasterModel::findOrFail($request->input('task_id'));  
// $PPCMasterList->fill($data1)->save();

// DB::table('task_details')->where('task_id', $request->input('task_id'))->delete();
 
// $size_id = $request->input('size_id');
// if(count($size_id)>0)
// {

// for($x=0; $x<count($size_id); $x++) {
//     # code...
//       $data2=array(
          
//                     'task_id'=>$request->task_id,
//                     'task_date'=>$request->task_date,
//                     'vendorId'=>$request->vendorId,
//                     'vpo_code'=>$vpo_code,
//                     'mainstyle_id'=>$request->mainstyle_id,
//                     'substyle_id'=>$request->substyle_id,
//                     'fg_id'=>$request->fg_id,
//                     'style_no'=>$request->style_no,
//                     'style_description'=>$request->style_description,
//                     'table_id'=>$request->table_id,
//                     'table_avg'=>$request->table_avg,
//                     'part_id'=>$request->part_id,
//                      'item_code'=>$item_code,
//                     'size_id'=>$request->size_id[$x],
//                     'order_qty'=>$request->order_qty[$x],
//                     'cut_qty'=>$request->cut_qty[$x],
//                     'balance_qty'=>$request->balance_qty[$x],
//                     'ratio'=>$request->ratio[$x], 
//         );
        
//          TaskDetailModel::insert($data2);
         
//         }
    // }
 
    // return redirect()->route('Task.index')->with('message', 'Update Record Succesfully..!');
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
     
     
    public function destroy($id)
      {
    //     $Task = CuttingMasterModel::where('table_task_code','=', $id)->count(); 
          
    //     if($Task==0)
    //       {
    //         DB::table('task_master')->where('task_id', $id)->delete();
    //         DB::table('task_details')->where('task_id', $id)->delete();
            
    //         Session::flash('delete', 'Deleted record successfully'.$id); 
    //     }
    //     else
    //     {
    //         Session::flash('delete', "This Records Can't be deleted, As Cutting Against this Task is in Record..!"); 
    //     }
        
        
        
    }


    public function getCommanDetails(Request $request)
    { 
        // $sales_order_no= $request->input('sales_order_no');
        // $MasterdataList = DB::select("select Ac_code,    style_no from buyer_purchse_order_master
        
        // where buyer_purchse_order_master.delflag=0 and tr_code='".$sales_order_no."'");
        // return json_encode($MasterdataList);
    
    }
 
 
 public function GetColorList(Request $request)
{
//   // if (!$request->job_code) {
//         $html = '<option value="">--Color--</option>';
//       // } else {
//         //$html = '';
//       //  DB::enableQueryLog();
//         $ColorList = DB::select("select body_color_id as color_id from fabric_trim_card_match_details as f1
//         where f1.job_code='".$request->job_code."' union select trim_color_id as color_id from fabric_trim_card_match_details
//         as f2 where f2.job_code='".$request->job_code."'");
//         foreach ($ColorList as $row) {
//             $color = ColorModel::where('color_id','=', $row->color_id)->first(); 
//                 $html .= '<option value="'.$row->color_id.'">'.$color->color_name.'</option>';
              
//         }
//          //}
        
//         return response()->json(['html' => $html]);
}
 
 
 
    public function getBalanceDetails(Request $request)
    { 
//         $part_id= $request->input('part_id');
//         $vpo_code= $request->input('vpo_code');
//         $item_code= $request->input('item_code');
//           // $SizeDetailList2 = DB::table('vendor_purchase_order_size_detail2')->select('size_id')->whereIn('vpo_code',$vpo_code)->DISTINCT();
//       $vpo_code = explode(',', $request->vpo_code);
//         // DB::enableQueryLog();
//         $SizeDetailList = DB::select("select distinct vendor_purchase_order_size_detail2.size_id, size_name from vendor_purchase_order_size_detail2
//         inner join size_detail on size_detail.size_id=vendor_purchase_order_size_detail2.size_id
//         where vendor_purchase_order_size_detail2.vpo_code in ('".$request->vpo_code."')");
     
     
    
//     $html = '';
    
// $no=1;
// foreach ($SizeDetailList as $row) 
// {
//     $html .='<tr class="thisRow">';
   
// $html .='
// <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
// $html.='<td>
//   <select name="size_id[]"  id="size_id'.$no.'" style="width:100px;" required disabled>
// <option value="">--Size--</option>';
// foreach($SizeDetailList as  $rowfg)
// {
//     $html.='<option value="'.$rowfg->size_id.'"';
//  $rowfg->size_id == $row->size_id ? $html.='selected="selected"' : ''; 
//     $html.='>'.$rowfg->size_name.'</option>';
// }
// $html.='</select></td>';
// // $html.='<td>
// // <input type="text" name="order_qty[]" id="order_qty'.$no.'" value="'.$row->size_qty.'" style="width:80px;" required/> </td>';

// // $html.='<td>
// // <input type="text" name="cut_qty[]" id="cut_qty'.$no.'" value="'.$row->cutQty.'" style="width:80px;" required/> </td>';
// // $html.='<td>
// // <input type="text" name="balance_qty[]" id="balance_qty'.$no.'" value="'.$row->Balance.'" style="width:80px;" required/> </td>';
//   $html.='<td>
// <input type="number" name="ratio[]" id="ratio'.$no.'" value="" style="width:80px;" required/> </td>';
// $html.='<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
// ';
//     $html .='</tr>';
//     $no=$no+1;
//   }
    
//     return response()->json(['html' => $html]);
     
    }
    
    function getMonthName($monthNumber)
    {
        return date("F", mktime(0, 0, 0, $monthNumber, 1));
    } 
    
    public function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];
    
        $startDate = strtotime($startDate); 
        $endDate = strtotime($endDate); 

        for($currentDate = $startDate;$currentDate <= $endDate;$currentDate += (86400)) 
        {
           
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
       
        return $rangArray;
    }
    
    public function search(Request $request)
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '149')
        ->first();     
                
            //   DB::enableQueryLog();
        $PPCList = PPCMasterModel::select('ppc_master.sr_no','sales_order_no','ppc_master.vendorId', 'ppc_master.line_id', 'ppc_master.machine_count', 'ppc_master.available_mins', 'ppc_master.line_efficiency',
             'ppc_master.sam', 'ppc_master.production_capacity', 'ppc_master.target', 'ppc_master.start_date', 'ppc_master.end_date','color_master.color_name','ppc_master.color_order_qty','ppc_master.color_id',
             'ppc_master.userId', 'ppc_master.endFlag')
             ->join('usermaster', 'usermaster.userId', '=', 'ppc_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'ppc_master.vendorId')
            ->join('line_master', 'line_master.line_id', '=', 'ppc_master.line_id')
            ->join('color_master', 'color_master.color_id', '=', 'ppc_master.color_id')
            ->where('ppc_master.endFlag','=' , '0')
            ->where('ppc_master.vendorId','=' ,  $request->searchVendorId)
            ->where('ppc_master.line_id','=' ,  $request->searchLineId)
            ->get();
            
        $ColorList = DB::table('ppc_master')->select('color_master.color_id','color_master.color_name')
                    ->join('color_master', 'color_master.color_id', '=', 'ppc_master.color_id')->DISTINCT()->get();
        $SalesList = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.tr_code as sales_order_no') 
                    ->where('buyer_purchse_order_master.job_status_id','=' , 1)
                    ->where('buyer_purchse_order_master.og_id','!=' , 4)
                    ->where('buyer_purchse_order_master.order_type','!=' , 2)
                    ->DISTINCT()
                    ->get();
                    
         //DB::enableQueryLog();  
        $EndDate = DB::table('ppc_master')->select('end_date')->latest('sr_no')->first();
         //dd(DB::getQueryLog());
        $searchVendorId = $request->searchVendorId;
        $searchLineId = $request->searchLineId;
           
        $Ledger = LedgerModel::where('ledger_master.delflag', '=', '0')
            ->where('ledger_master.Ac_code', '>', '39')
            ->where('ledger_master.bt_id', '>', '3')
            ->get()
            ->sortBy(function ($ledger) { 
                preg_match('/\d+/', $ledger->ac_short_name, $matches);
                return $matches ? (int)$matches[0] : PHP_INT_MAX;  
        });
         
        $Ledger1 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->where('ledger_master.Ac_code','=', $searchVendorId)->get();
        $LineList = LineModel::where('line_master.delflag','=', '0')->where('Ac_code', '=', $searchVendorId)->get();
            
        return view('PPCMaster', compact('PPCList','chekform','Ledger','Ledger1','LineList','EndDate','searchVendorId','searchLineId','ColorList','SalesList'));
    }

    public function GetSalesOrderList(Request $request)
    {
     
         $SalesList = DB::table('vendor_work_order_master')->select('sales_order_no')->where('vendorId','=',$request->vendorId)->DISTINCT()->get();
      
        if (!$request->vendorId)
        {
            $html = '<option value="">--Sales Order List--</option>';
            } else {
            $html = '';
            $html = '<option value="">--Sales Order List--</option>';
            
            foreach ($SalesList as $rowSale) 
            {$html .= '<option value="'.$rowSale->sales_order_no.'">'.$rowSale->sales_order_no.'</option>';}
        }
          return response()->json(['html' => $html]);
    }
    
    public function GetSaleOrderWiseColorList(Request $request)
    {
       // DB::enableQueryLog();
         $ColorList = DB::table('buyer_purchase_order_detail')->select('color_master.color_id','color_master.color_name')
                    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
                    ->where('tr_code','=',$request->tr_code)->DISTINCT()->get();
       // dd(DB::getQueryLog());
        if (!$request->tr_code)
        {
            $html = '<option value="">--Color List--</option>';
            } else {
            $html = '';
            $html = '<option value="">--Color List--</option>';
            
            foreach ($ColorList as $rowColor) 
            {$html .= '<option value="'.$rowColor->color_id.'">'.$rowColor->color_name.'</option>';}
        }
          return response()->json(['html' => $html]);
    }
    
    public function SAH_PPCMaster()
    {
        
         $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();
         $LineList = LineModel::where('line_master.delflag','=', '0')->get();
            //   DB::enableQueryLog();
         $PPCList = PPCMasterModel::
                select('ppc_master.sr_no','ppc_master.vendorId', 'ppc_master.line_id', 'ppc_master.machine_count', 'ppc_master.available_mins', 'ppc_master.line_efficiency',
             'ppc_master.sam', 'ppc_master.production_capacity', 'ppc_master.target', 'ppc_master.start_date', 'ppc_master.end_date',
             'ppc_master.userId', 'ppc_master.endFlag', 'ppc_master.sales_order_no', 'ppc_master.color_id')->
                join('usermaster', 'usermaster.userId', '=', 'ppc_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'ppc_master.vendorId')
            ->join('line_master', 'line_master.line_id', '=', 'ppc_master.line_id')
            ->where('ppc_master.endFlag','=' , '0')
            ->LIMIT(1)
            ->get();
         $getMonth = [];
         foreach (range(1, 12) as $m) {
            $getMonth[] = date('F', mktime(0, 0, 0, $m, 1));
         }

         $SalesList = DB::table('vendor_work_order_master')->select('sales_order_no')->DISTINCT()->get();
         
         $SAHPPCList =  DB::table('sah_ppc_master')->select('sah_ppc_master.sah_ppc_master_id','sah_ppc_master.sam','sah_ppc_master.sales_order_no','sah_ppc_master.totalAvaliableMin','sah_ppc_master.monthValue','sah_ppc_master.month','sah_ppc_master.bookedMin','sah_ppc_master.openMin','ledger_master.ac_name','line_master.line_name')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'sah_ppc_master.vendorId')
        ->join('line_master', 'line_master.line_id', '=', 'sah_ppc_master.line_id')
        ->groupby('sah_ppc_master.sah_ppc_master_id')->get();
         
         return view('SAH_PPCMaster', compact('PPCList','Ledger','LineList', 'SalesList','getMonth','SAHPPCList'));
    }
    
    public function GetPPCData(Request $request)
    {
       
         $ppcData = DB::table('ppc_master')->select('*')
         ->where('vendorId','=',$request->vendorId)
         ->where('line_id','=',$request->line_id)
         ->where('sales_order_no','=',$request->sales_order_no)
         ->DISTINCT()->get();
      
          return response()->json(['ppc' => $ppcData]);
    }
    
    public function SAHPPC(Request $request)
    {
       // echo '<pre>';print_r($_POST);exit;
        $vendorId = $request->input('vendorId');
        
        for($x=0; $x<count($vendorId); $x++) 
        {    
                $data3=array(
                    'vendorId'=>$request->vendorId[$x],
                    'line_id'=>$request->line_id[$x],
                    'sales_order_no'=>$request->sales_order_no[$x],
                    'sam'=>$request->sam[$x],
                    'noOfDays'=>$request->noOfDays[$x],
                    'totalAvaliableMin'=>$request->totalAvaliableMin[$x],
                    'month'=>$request->month[$x],
                    'monthValue'=>$request->monthValue[$x],
                    'bookedMin'=>$request->bookedMin[$x],
                    'openMin'=>$request->openMin[$x],
                    'userId'=>$request->userId
                );
                SAHPPCMasterModel::insert($data3);
        }
        return redirect()->route('SAH_PPCMaster');
    }
    
    public function SAHPPCEdit($id)
    {
        $SAHPPCEditData = SAHPPCMasterModel::find($id);
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();
        $LineList = LineModel::where('line_master.delflag','=', '0')->get();
            //   DB::enableQueryLog();
        $PPCList = PPCMasterModel::
                select('ppc_master.sr_no','ppc_master.vendorId', 'ppc_master.line_id', 'ppc_master.machine_count', 'ppc_master.available_mins', 'ppc_master.line_efficiency',
             'ppc_master.sam', 'ppc_master.production_capacity', 'ppc_master.target', 'ppc_master.start_date', 'ppc_master.end_date',
             'ppc_master.userId', 'ppc_master.endFlag', 'ppc_master.sales_order_no', 'ppc_master.color_id')->
                join('usermaster', 'usermaster.userId', '=', 'ppc_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'ppc_master.vendorId')
            ->join('line_master', 'line_master.line_id', '=', 'ppc_master.line_id')
            ->where('ppc_master.endFlag','=' , '0')
            ->LIMIT(1)
            ->get();
         $getMonth = [];
         foreach (range(1, 12) as $m) {
            $getMonth[] = date('F', mktime(0, 0, 0, $m, 1));
         }

         $SalesList = DB::table('vendor_work_order_master')->select('sales_order_no')->DISTINCT()->get();
         
         $SAHPPCList =  DB::table('sah_ppc_master')->select('sah_ppc_master.sah_ppc_master_id','sah_ppc_master.sam','sah_ppc_master.sales_order_no','sah_ppc_master.totalAvaliableMin','sah_ppc_master.monthValue','sah_ppc_master.month','sah_ppc_master.bookedMin','sah_ppc_master.openMin','ledger_master.ac_name','line_master.line_name')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'sah_ppc_master.vendorId')
        ->join('line_master', 'line_master.line_id', '=', 'sah_ppc_master.line_id')
        ->groupby('sah_ppc_master.sah_ppc_master_id')->get();
         
        return view('SAH_PPCMaster', compact('SAHPPCList', 'Ledger','LineList','PPCList','SalesList','getMonth','SAHPPCEditData'));
    }
    
    public function SAH_PPCMasterUpdate(Request $request, $sah_ppc_master_id)
    {  
        $vendorId = $request->input('vendorId');
        
        for($x=0; $x<count($vendorId); $x++) 
        {    
            $data3=array(
                'vendorId'=>$request->vendorId[$x],
                'line_id'=>$request->line_id[$x],
                'sales_order_no'=>$request->sales_order_no[$x],
                'sam'=>$request->sam[$x],
                'noOfDays'=>$request->noOfDays[$x],
                'totalAvaliableMin'=>$request->totalAvaliableMin[$x],
                'month'=>$request->month[$x],
                'monthValue'=>$request->monthValue[$x],
                'bookedMin'=>$request->bookedMin[$x],
                'openMin'=>$request->openMin[$x],
                'userId'=>$request->userId
            );
            $List = SAHPPCMasterModel::findOrFail($sah_ppc_master_id); 
            $List->fill($data3)->save();
        }
        return redirect()->route('SAH_PPCMaster');
    }
    public function SAHPPCDelete($id)
    {
        SAHPPCMasterModel::where('sah_ppc_master_id', $id)->delete();
        Session::flash('messagedelete', 'Deleted record successfully'); 
        // return redirect()->route('SAH_PPCMaster');
    }
    
    public function rptSAHMonthWise_PPC()
    {
        //DB::enableQueryLog();
        $SAHPPCList =  DB::table('sah_ppc_master')->select('sah_ppc_master.line_id','sah_ppc_master.sah_ppc_master_id',
        'sah_ppc_master.vendorId','sah_ppc_master.sam','sah_ppc_master.sales_order_no','sah_ppc_master.totalAvaliableMin',
        'sah_ppc_master.monthValue','sah_ppc_master.month','sah_ppc_master.bookedMin','sah_ppc_master.openMin',
        'ledger_master.ac_name','line_master.line_name','buyer_purchse_order_master.style_no')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'sah_ppc_master.vendorId')
        ->join('line_master', 'line_master.line_id', '=', 'sah_ppc_master.line_id')
        ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sah_ppc_master.sales_order_no')
        ->get();
        //dd(DB::getQueryLog());
        $monthWise = DB::select("SELECT * FROM sah_ppc_master GROUP BY month"); 
        return view('rptSAHMonthWise_PPC', compact('SAHPPCList','monthWise'));
    }
    
    public function rptSAH_PPC()
    {
  
        $SAHPPCList =  DB::table('sah_ppc_master')->select('line_master.line_id','sah_ppc_master.sam','sah_ppc_master.sales_order_no','sah_ppc_master.totalAvaliableMin','sah_ppc_master.monthValue','sah_ppc_master.month','sah_ppc_master.bookedMin','sah_ppc_master.openMin','ledger_master.ac_name','line_master.line_name')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'sah_ppc_master.vendorId')
        ->join('line_master', 'line_master.line_id', '=', 'sah_ppc_master.line_id')
        ->groupby('sah_ppc_master.sah_ppc_master_id')->get();

        return view('rptSAH_PPC', compact('SAHPPCList'));
    }
    
    public function GetPlannedVsActual()
    {   
         $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();
         $LineList = LineModel::where('line_master.delflag','=', '0')->get();
        
        return view('GetPlannedVsActual', compact('Ledger','LineList'));
    }
    public function rptPlannedVsActual(Request $request)
    {
        
        $vendorId = $request->vendorId;
        $line_id = $request->line_id;
        
      
        $CutPanelIssueDetails = DB::select("SELECT cpi_code, cpi_date,cut_panel_issue_size_detail2.color_id, sales_order_no, vw_code, Ac_name, mainstyle_name,cut_panel_issue_size_detail2.style_no, 
                color_master.color_name,line_master.line_name,color_master.style_img_path, brand_master.brand_name, 
                size_detail.size_name,cut_panel_issue_size_detail2.size_id, cut_panel_issue_size_detail2.size_qty as 'qty'
                FROM `cut_panel_issue_size_detail2`
                LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=cut_panel_issue_size_detail2.sales_order_no
                LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
                LEFT JOIN ledger_master on ledger_master.ac_code=cut_panel_issue_size_detail2.Ac_code
                LEFT JOIN color_master on color_master.color_id=cut_panel_issue_size_detail2.color_id
                LEFT JOIN size_detail on size_detail.size_id = cut_panel_issue_size_detail2.size_id
                LEFT JOIN line_master on line_master.line_id = cut_panel_issue_size_detail2.line_id
                LEFT JOIN main_style_master on main_style_master.mainstyle_id=cut_panel_issue_size_detail2.mainstyle_id
                where cut_panel_issue_size_detail2.size_qty!=0 AND cut_panel_issue_size_detail2.vendorId = '".$vendorId."' 
                AND cut_panel_issue_size_detail2.line_id = '".$line_id."' group by cut_panel_issue_size_detail2.color_id order by cut_panel_issue_size_detail2.cpi_date ASC");
                
        //DB::enableQueryLog();
        $stitchingInhouseSizeDetails1 = DB::select("SELECT sti_date,ifnull(sum(stitching_inhouse_size_detail2.size_qty),0) as 'qty'
                      FROM `stitching_inhouse_size_detail2`
                      LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                      LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
                      LEFT JOIN ledger_master on ledger_master.ac_code=stitching_inhouse_size_detail2.Ac_code
                      LEFT JOIN color_master on color_master.color_id=stitching_inhouse_size_detail2.color_id
                      LEFT JOIN size_detail on size_detail.size_id = stitching_inhouse_size_detail2.size_id
                      LEFT JOIN line_master on line_master.line_id = stitching_inhouse_size_detail2.line_id
                      LEFT JOIN main_style_master on main_style_master.mainstyle_id=stitching_inhouse_size_detail2.mainstyle_id
                      where stitching_inhouse_size_detail2.size_qty!=0 AND stitching_inhouse_size_detail2.vendorId = '".$vendorId."'
                      AND stitching_inhouse_size_detail2.line_id = '".$line_id."' group by stitching_inhouse_size_detail2.sti_date  
                      order by stitching_inhouse_size_detail2.sti_date ASC");
        //dd(DB::getQueryLog());       
        $LineData = LineModel::where('line_master.delflag','=', '0')->where('line_id', '=',$line_id)->first();

       
        return view('rptPlannedVsActual', compact('CutPanelIssueDetails','stitchingInhouseSizeDetails1','LineData','vendorId','line_id'));
    }
    
    public function GetPlanLineList(Request $request)
    {
        $LineList =  LineModel::where('line_master.delflag','=', '0')->where('Ac_code','=',$request->Ac_code)->get();
        
        if (!$request->Ac_code)
        {
            $html = '<option value="">--Line List--</option>';
            } else {
            $html = '';
            $html = '<option value="">--Line List--</option>';
            
            foreach ($LineList as $rowline) 
            {$html .= '<option value="'.$rowline->line_id.'">'.$rowline->line_name.'</option>';}
        }
          return response()->json(['html' => $html]);
    }
   
    public function DeviationPPCList()
    {
        $deviation_ppc_master_list =DB::table('deviation_ppc_master')->select('deviation_ppc_master.*','ledger_master.ac_name','line_master.line_name')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'deviation_ppc_master.vendorId')
        ->join('line_master', 'line_master.line_id', '=', 'deviation_ppc_master.line_id')
        ->get();
        return view('DeviationPPCList',compact('deviation_ppc_master_list'));
    }
    
    public function deviationPPCMaster()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->get();
        return view('deviationPPCMaster',compact('Ledger'));
    }
    
    public function deviationPPCMasterStore(Request $request)
    {   
        
        $data=array(
            'vendorId'=>$request->vendorId,
            'line_id'=>$request->line_id,
            'noOfMC'=>0,
            'efficiency'=>0,
            'monthlyPlan'=>"",
            'day_Count'=>$request->day_Count,
            'userId'=>$request->userId,
            'updated_at'=>date('Y-m-d'),
        );
        DeviationPPCMasterModel::insert($data);
        
        $noOfMC = $request->noOfMC;
        $deviation_PPC_Master_Id = DB::table('deviation_ppc_master')->max('deviation_PPC_Master_Id');
        foreach($noOfMC as $key=>$val)
        {
            $details = array(
                'deviation_PPC_Master_Id'=>$deviation_PPC_Master_Id,
                'vendorId'=>$request->vendorId,
                'line_id'=>$request->line_id,
                'noOfMC'=>$request->noOfMC[$key],
                'efficiency'=>$request->efficiency[$key],
                'monthDate'=>$request->monthDate[$key],
            ); 
            DeviationPPCDateWiseMCModel::insert($details);
        }
        return redirect()->route('DeviationPPCList');
    }
    
    public function deviationPPCMasterEdit($id)
    {
        $DeviationList = DeviationPPCMasterModel::find($id); 
        $DetailDeviation = DeviationPPCDateWiseMCModel::where('deviation_PPC_Master_Id','=', $id)->get();
        $datasets = DeviationPPCDateWiseMCModel::select(DB::raw('MAX(monthDate) as max_date'),  DB::raw('MIN(monthDate) as min_date'))
                    ->where('deviation_PPC_Master_Id', $id)
                    ->orderBy('monthDate','desc')
                    ->get();
        $min_date = $datasets[0]->min_date;
        $max_date = $datasets[0]->max_date; 
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->get();
        
        return view('deviationPPCMaster', compact('DeviationList','Ledger','DetailDeviation','min_date', 'max_date'));
    }
    
    public function deviationPPCMasterUpdate(Request $request,  $id)
    {
        $DeviationPPCList = DeviationPPCMasterModel::findOrFail($id);
        $input = $request->all();
        $DeviationPPCList->fill($input)->save();
        
        $noOfMC = $request->noOfMC; 
        DB::table('deviation_ppc_date_wise_mc')->where('deviation_PPC_Master_Id', $id)->delete();
        foreach($noOfMC as $key=>$val)
        {
            $details = array(
                'deviation_PPC_Master_Id'=>$id,
                'vendorId'=>$request->vendorId,
                'line_id'=>$request->line_id,
                'noOfMC'=>$request->noOfMC[$key],
                'efficiency'=>$request->efficiency[$key],
                'monthDate'=>$request->monthDate[$key],
            ); 
            DeviationPPCDateWiseMCModel::insert($details);
        }
        return redirect()->route('DeviationPPCList')->with('message', 'Update Record Succesfully');
    }
    
    public function deviationPPCMasterDelete($deviation_PPC_Master_Id)
    {
         DB::table('deviation_ppc_master')->where('deviation_PPC_Master_Id', $deviation_PPC_Master_Id)->delete();
         DB::table('deviation_ppc_date_wise_mc')->where('deviation_PPC_Master_Id', $deviation_PPC_Master_Id)->delete();
         return redirect()->route('DeviationPPCList')->with('delete', 'Delete Record Succesfully');  
    }
    
    public function GetDeviationPPC()
    {
        $monthArr = array();
        for ($m=1; $m<=12; $m++) 
        {
           $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
           $monthArr[] = $month;
         }
     
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','>', '3')->get();
        
        return view('GetDeviationPPC', compact('Ledger','monthArr'));
    }
    
    public function rptDeviationPPC(Request $request)
    { 
        $vendorId = $request->vendorId;
        $m1 = explode("-",$request->month);
        $monthId = $m1[1];
        $monthArr = array();
        for ($m=1; $m<=12; $m++) 
        {
           $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
           $monthArr[] = $month;
        }
        //DB::enableQueryLog();

        $deviationList = DB::table('deviation_ppc_master')
                        ->select('deviation_ppc_master.line_id','efficiency','noOfMC','monthlyPlan','day_count','deviation_ppc_master.vendorId','line_master.line_name')
                        ->join('line_master', 'line_master.line_id', '=', 'deviation_ppc_master.line_id')
                        ->where('vendorId', $vendorId)
                        ->GROUPBY('deviation_ppc_master.line_id')
                        ->get(); 
                        
        //dd(DB::getQueryLog());
        $monthName = date("F", mktime(0, 0, 0, $monthId, 10));
        
        $days = cal_days_in_month(CAL_GREGORIAN, $monthId, date('Y'));
        
        $year = date('Y');
        
        $m = $monthId;
        
        
        $y = $year;
        
        $d = cal_days_in_month(CAL_GREGORIAN,$m,$y);
         
        $dateSun = $this->getSunday($y.'-'.$m.'-01', $y.'-'.$m.'-'.$d, 0);
        $lineList = DB::table('deviation_ppc_master')
                        ->select('deviation_ppc_master.line_id','line_master.line_name')
                        ->join('line_master', 'line_master.line_id', '=', 'deviation_ppc_master.line_id') 
                        ->where('vendorId', $vendorId)
                        ->GROUPBY('deviation_ppc_master.line_id')
                        ->get();  
        $lineNos = "";
        foreach($lineList as $row)
        {
            $lineNos .= $row->line_id.",";
        }
       
        $linesArr =  rtrim($lineNos,",");
        return view('rptDeviationPPC', compact('deviationList','monthArr', 'monthName', 'days','monthId','year','vendorId','dateSun','d','lineList','linesArr'));
    }
    
    function getSunday($startDt, $endDt, $weekNum)
    {
        $startDt = strtotime($startDt);
        $endDt = strtotime($endDt);
    
        $dateSun = array();
    
        do
        {
            if(date("w", $startDt) != $weekNum)
            {
                $startDt += (24 * 3600); // add 1 day
            }
        } while(date("w", $startDt) != $weekNum);
    
    
        while($startDt <= $endDt)
        {
            $dateSun[] = date('d', $startDt);
            $startDt += (7 * 24 * 3600); // add 7 days
        }
    
        return($dateSun);
    }
    
    public function Get_Cut_Panel_Issue_VS_Production()
    {         
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();
         
        return view('Get_Cut_Panel_Issue_VS_Production', compact('Ledger'));
    }
    
    public function rptCutPanelIssueVsProduction(Request $request)
    {
        $fdate = $request->fdate;
        $tdate = $request->tdate;
        $vendorId = $request->vendorId;
        $line_id = $request->line_id;
        
        if($vendorId > 0)
        {
            $CutPanelIssueDetails = DB::select("SELECT cpi_code, cpi_date,cut_panel_issue_size_detail2.color_id, sales_order_no,  cut_panel_issue_size_detail2.vendorId,
                    vw_code, ledger_master.ac_name, mainstyle_name,cut_panel_issue_size_detail2.style_no, 
                    color_master.color_name,line_master.line_name,color_master.style_img_path, brand_master.brand_name, 
                    size_detail.size_name,cut_panel_issue_size_detail2.size_id, cut_panel_issue_size_detail2.size_qty as 'qty'
                    FROM `cut_panel_issue_size_detail2`
                    INNER JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=cut_panel_issue_size_detail2.sales_order_no
                    INNER JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
                    INNER JOIN ledger_master on ledger_master.ac_code=cut_panel_issue_size_detail2.vendorId
                    INNER JOIN color_master on color_master.color_id=cut_panel_issue_size_detail2.color_id
                    INNER JOIN size_detail on size_detail.size_id = cut_panel_issue_size_detail2.size_id
                    INNER JOIN line_master on line_master.line_id = cut_panel_issue_size_detail2.line_id
                    INNER JOIN main_style_master on main_style_master.mainstyle_id=cut_panel_issue_size_detail2.mainstyle_id
                    where cut_panel_issue_size_detail2.size_qty!=0 AND cut_panel_issue_size_detail2.vendorId = '".$vendorId."' 
                    AND cut_panel_issue_size_detail2.line_id = '".$line_id."' AND cut_panel_issue_size_detail2.cpi_date between '".$fdate."' AND '".$tdate."'
                    group by cut_panel_issue_size_detail2.color_id order by cut_panel_issue_size_detail2.cpi_date ASC");
        }
        else
        {
            $CutPanelIssueDetails = DB::select("SELECT cpi_code, cpi_date,cut_panel_issue_size_detail2.color_id, sales_order_no, cut_panel_issue_size_detail2.vendorId,
                    vw_code, ledger_master.ac_name, mainstyle_name,cut_panel_issue_size_detail2.style_no, 
                    color_master.color_name,line_master.line_name,color_master.style_img_path, brand_master.brand_name, 
                    size_detail.size_name,cut_panel_issue_size_detail2.size_id, cut_panel_issue_size_detail2.size_qty as 'qty'
                    FROM `cut_panel_issue_size_detail2`
                    INNER JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=cut_panel_issue_size_detail2.sales_order_no
                    INNER JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
                    INNER JOIN ledger_master on ledger_master.ac_code=cut_panel_issue_size_detail2.vendorId
                    INNER JOIN color_master on color_master.color_id=cut_panel_issue_size_detail2.color_id
                    INNER JOIN size_detail on size_detail.size_id = cut_panel_issue_size_detail2.size_id
                    INNER JOIN line_master on line_master.line_id = cut_panel_issue_size_detail2.line_id
                    INNER JOIN main_style_master on main_style_master.mainstyle_id=cut_panel_issue_size_detail2.mainstyle_id
                    where cut_panel_issue_size_detail2.size_qty!=0 AND  cut_panel_issue_size_detail2.cpi_date between '".$fdate."' AND '".$tdate."'
                    group by cut_panel_issue_size_detail2.color_id order by cut_panel_issue_size_detail2.cpi_date ASC");
            
        }
        return view('rptCutPanelIssueVsProduction', compact('fdate','tdate','vendorId','line_id','CutPanelIssueDetails'));
    }
    
    public function Get_WIP_Report()
    {         
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();
         
        return view('Get_WIP_Report', compact('Ledger'));
    }
    
    public function rptWIPReport(Request $request)
    {
          $fdate = $request->fdate;
          $tdate = $request->tdate;
          $vendorId = $request->vendorId;
       
         if($vendorId > 0)
         {
              $MasterdataList = DB::select("SELECT cut_panel_grn_size_detail2.cpg_code, cut_panel_grn_size_detail2.vpo_code,cut_panel_grn_size_detail2.cpg_date,ledger_master.ac_name,
                  ifnull(sum(size_qty),0) as size_qty,cut_panel_grn_size_detail2.vendorId  from cut_panel_grn_size_detail2 
                  left join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code = cut_panel_grn_size_detail2.vpo_code
                  inner join job_status_master on job_status_master.job_status_id=vendor_purchase_order_master.endflag
                  inner join ledger_master on ledger_master.Ac_code=cut_panel_grn_size_detail2.vendorId 
                  inner join size_detail on size_detail.size_id=cut_panel_grn_size_detail2.size_id
                  where cut_panel_grn_size_detail2.sales_order_no in (select distinct(vendor_purchase_order_master.sales_order_no) as sales_order_no 
                  from vendor_purchase_order_master) 
                  AND cut_panel_grn_size_detail2.vendorId = '".$vendorId."' AND cut_panel_grn_size_detail2.cpg_date Between '".$fdate."' AND '".$tdate."'
                  group by cpg_date");
         }
         else
         {
              $MasterdataList = DB::select("SELECT cut_panel_grn_size_detail2.cpg_code, cut_panel_grn_size_detail2.vpo_code,cut_panel_grn_size_detail2.cpg_date,ledger_master.ac_name,
                  ifnull(sum(size_qty),0) as size_qty,cut_panel_grn_size_detail2.vendorId  from cut_panel_grn_size_detail2 
                  left join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code = cut_panel_grn_size_detail2.vpo_code
                  inner join job_status_master on job_status_master.job_status_id=vendor_purchase_order_master.endflag
                  inner join ledger_master on ledger_master.Ac_code=cut_panel_grn_size_detail2.vendorId 
                  inner join size_detail on size_detail.size_id=cut_panel_grn_size_detail2.size_id
                  where cut_panel_grn_size_detail2.sales_order_no in (select distinct(vendor_purchase_order_master.sales_order_no) as sales_order_no 
                  from vendor_purchase_order_master) 
                  AND cut_panel_grn_size_detail2.cpg_date Between '".$fdate."' AND '".$tdate."'
                  group by cpg_date");
         }
        return view('rptWIPReport', compact('fdate','tdate','vendorId','MasterdataList'));
    }
    
    public function Get_WIP_Report1()
    {         
        return view('Get_WIP_Report1');
    }
    
    public function rptWIPReportss(Request $request)
    {
        // dd($request->job_status_id);
    //     $job_status_ids=explode(", ", $request->job_status_id);
        $Status='';
        foreach($request->job_status_id as $st)
        {
            $Status=$Status."".$st.",";
            
        }
        $Status=rtrim($Status,",");
        
        if(count($request->job_status_id) > 0)
        {
            $jobStatus = " AND buyer_purchse_order_master.job_status_id IN (".$Status.")";
        }
        else
        {
            $jobStatus = "";
        }
        
        if($request->order_type > 0)
        {
            $orderType = " AND buyer_purchse_order_master.order_type =".$request->order_type;
        }
        else
        {
            $orderType = "";
        }
        
        
        if($request->tr_date != "")
        {
            $currentDate = " AND buyer_purchse_order_master.created_at ='".$request->tr_date."'";
        }
        else
        {
            $currentDate = "";
        }
        
        // if(count($request->job_status_id) > 0)
        // {
        //   // DB::enableQueryLog();
        //     $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        //         select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name',
        //         'buyer_purchse_order_master.sam','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
        //         ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
        //         , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
        //         ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        //         ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        //         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        //         ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        //         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        //         ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        //         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        //         ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        //         ->where('buyer_purchse_order_master.delflag','=', '0')
        //         ->where('buyer_purchse_order_master.og_id','!=', '4')
        //         ->whereIN('buyer_purchse_order_master.job_status_id', $request->job_status_id)
        //         ->where('buyer_purchse_order_master.order_type','=',$order_type)
        //         ->orderBy('buyer_purchse_order_master.tr_code')
        //         ->get();
               
             // DB::enableQueryLog();    
            $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.Ac_name,fg_master.fg_name,
                buyer_purchse_order_master.sam,merchant_master.merchant_name,brand_master.brand_name,job_status_master.job_status_name,main_style_master.mainstyle_name,
                (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
                (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty
                FROM buyer_purchse_order_master
                INNER JOIN usermaster ON usermaster.userId=buyer_purchse_order_master.userId 
                LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code
                LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id
                LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
                INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                WHERE  buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4
                ".$jobStatus." ".$orderType." ORDER BY buyer_purchse_order_master.tr_code");
               
               // dd(DB::getQueryLog()); 
        // }
        // else
        // {
        //      $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        //         select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','buyer_purchse_order_master.sam','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
        //         ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
        //         , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
        //         ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        //         ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        //         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        //         ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        //         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        //         ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        //         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        //         ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        //         ->where('buyer_purchse_order_master.delflag','=', '0')
        //         ->where('buyer_purchse_order_master.og_id','!=', '4')
        //         ->get();
        // }
        
        $currentDate = isset($request->tr_date) ? $request->tr_date : ""; 
        return view('rptWIPReportss', compact('Buyer_Purchase_Order_List','Status','currentDate'));
    }
    
      
    public function Get_Total_WIP_Report()
    {         
        return view('Get_Total_WIP_Report');
    }
    
    public function rptTotalWIPReport(Request $request)
    {
        $fromDate = $request->fromDate ? $request->fromDate : date('Y-m-01');   
        $toDate = $request->toDate ? $request->toDate : date('Y-m-d');     
        $job_status_id =$request->job_status_id;
        $orderTypeId = $request->orderTypeId; 
        $vendorId = $request->vendorId; 
        $Status=''; 
        $orderType='';
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        //     select('buyer_purchse_order_master.*', 'buyer_purchse_order_master.sam','brand_master.brand_name','main_style_master.mainstyle_name'
        //     ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
        //     , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
        //     ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        //     ->where('buyer_purchse_order_master.delflag','=', '0')
        //     ->where('buyer_purchse_order_master.og_id','!=', '4')
        //     ->where('buyer_purchse_order_master.order_type','=',$order_type)
        //     ->where('buyer_purchse_order_master.job_status_id','=',$job_status_id)
        //     ->orderBy('buyer_purchse_order_master.tr_code')
        //     ->get();
        
        $jobStatusList = DB::SELECT("SELECT job_status_id, job_status_name FROM job_status_master where delflag=0");
        $orderTypeList = DB::SELECT("SELECT orderTypeId, order_type FROM order_type_master where delflag=0");
        $vendorList = DB::SELECT("SELECT ac_code, ac_name FROM ledger_master where bt_id = 4 AND delflag=0");
        
        $filter = "";
        $filter1 = "";
        if($request->job_status_id != "")
        {   
            foreach($request->job_status_id as $st)
            {
                $Status=$Status."".$st.",";
                
            }
            $Status=rtrim($Status,",");
            $filter .= " AND buyer_purchse_order_master.job_status_id IN(".$Status.")";
        }
        else 
        {
            $job_status_id = [1, 2, 4, 5];
            $Status = implode(',', $job_status_id);
            $filter .= " AND buyer_purchse_order_master.job_status_id IN (" . $Status . ")";
        }
        if($request->orderTypeId != "")
        {
            foreach($request->orderTypeId as $ot)
            {
                $orderType=$orderType."".$ot.",";
                
            }
            $orderType=rtrim($orderType,",");
            $filter1 .= " AND buyer_purchse_order_master.order_type IN(".$orderType.")";
        }
        else 
        {
            $orderType = [1, 3];
            $orderType = implode(',', $orderType);
            $filter1 .= " AND buyer_purchse_order_master.order_type IN (".$orderType.")";
        }
      
      
        // $Buyer_Purchase_Order_List = DB::select("SELECT buyer_purchse_order_master.*,brand_master.brand_name,main_style_master.mainstyle_name,job_status_master.job_status_name,
        //     (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
            
        //     (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty
        //     FROM buyer_purchse_order_master LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
        //     LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
        //     LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
        //     WHERE buyer_purchse_order_master.delflag = 0 ".$filter1."
        //     AND  buyer_purchse_order_master.og_id != 4 ".$filter." AND order_close_date BETWEEN '".$fromDate."' AND '".$toDate."'  OR buyer_purchse_order_master.tr_date <= '".$toDate."' AND buyer_purchse_order_master.delflag = 0 
        //     AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id IN (1,2) ".$filter1." ORDER BY buyer_purchse_order_master.tr_code");
         
           //DB::enableQueryLog();
            $Buyer_Purchase_Order_List = DB::select("
                SELECT 
                    buyer_purchse_order_master.*, 
                    brand_master.brand_name,
                    main_style_master.mainstyle_name,
                    job_status_master.job_status_name,
                    (SELECT IFNULL(SUM(total_qty),0) FROM cut_panel_grn_master WHERE cut_panel_grn_master.sales_order_no = buyer_purchse_order_master.tr_code) AS cut_qty,
                    (SELECT IFNULL(SUM(total_qty),0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) AS prod_qty
                FROM 
                    buyer_purchse_order_master 
                    LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
                    LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                    LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
                    
                      WHERE 
                    buyer_purchse_order_master.delflag = 0 
                    AND buyer_purchse_order_master.og_id != 4
                    AND buyer_purchse_order_master.order_type != 2
                    AND buyer_purchse_order_master.order_received_date <= '$toDate'
                    AND (
                        buyer_purchse_order_master.order_close_date > '$toDate' 
                        OR buyer_purchse_order_master.order_close_date IS NULL
                    )
                    $filter1 
                    
                ORDER BY buyer_purchse_order_master.tr_code
            ");
            // dd(DB::getQueryLog());
         
         
         
         
             
        return view('rptTotalWIPReport', compact('Buyer_Purchase_Order_List','fromDate','toDate','Status','orderType','job_status_id','orderTypeId','jobStatusList','orderTypeList', 'vendorList', 'vendorId'));
    }
    
    public function StageWiseWIPReport(Request $request)
    {
        $fromDate = $request->fromDate ? $request->fromDate : date('Y-m-01');   
        $toDate = $request->toDate ? $request->toDate : date('Y-m-d');     
        $job_status_id =$request->job_status_id;
        $orderTypeId = $request->orderTypeId; 
        $vendorId = $request->vendorId; 
        $Status=''; 
        $orderType=''; 
        
        $jobStatusList = DB::SELECT("SELECT job_status_id, job_status_name FROM job_status_master where delflag=0");
        $orderTypeList = DB::SELECT("SELECT orderTypeId, order_type FROM order_type_master where delflag=0");
        $vendorList = DB::SELECT("SELECT ac_code, ac_name FROM ledger_master where bt_id = 4 AND delflag=0");
        
        $filter = "";
        $filter1 = "";
        if($request->job_status_id != "")
        {   
            foreach($request->job_status_id as $st)
            {
                $Status=$Status."".$st.",";
                
            }
            $Status=rtrim($Status,",");
            $filter .= " AND buyer_purchse_order_master.job_status_id IN(".$Status.")";
        }
        else 
        {
            $job_status_id = [1, 2, 4, 5];
            $Status = implode(',', $job_status_id);
            $filter .= " AND buyer_purchse_order_master.job_status_id IN (" . $Status . ")";
        }
        if($request->orderTypeId != "")
        {
            foreach($request->orderTypeId as $ot)
            {
                $orderType=$orderType."".$ot.",";
                
            }
            $orderType=rtrim($orderType,",");
            $filter1 .= " AND buyer_purchse_order_master.order_type IN(".$orderType.")";
        }
        else 
        {
            $orderType = [1, 3];
            $orderType = implode(',', $orderType);
            $filter1 .= " AND buyer_purchse_order_master.order_type IN (".$orderType.")";
        }
       
           //DB::enableQueryLog();
            $Buyer_Purchase_Order_List = DB::select("
                SELECT 
                    buyer_purchse_order_master.*, 
                    brand_master.brand_name,
                    main_style_master.mainstyle_name,
                    job_status_master.job_status_name,
                    (SELECT IFNULL(SUM(total_qty),0) FROM cut_panel_grn_master WHERE cut_panel_grn_master.sales_order_no = buyer_purchse_order_master.tr_code) AS cut_qty,
                    (SELECT IFNULL(SUM(total_qty),0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) AS prod_qty
                FROM 
                    buyer_purchse_order_master 
                    LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
                    LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                    LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
          
                WHERE 
                    buyer_purchse_order_master.delflag = 0 
                    AND buyer_purchse_order_master.og_id != 4
                    AND buyer_purchse_order_master.order_type != 2
                    AND buyer_purchse_order_master.order_received_date <= '$toDate'
                    AND (
                        buyer_purchse_order_master.order_close_date > '$toDate' 
                        OR buyer_purchse_order_master.order_close_date IS NULL
                    )
                    $filter1
                    
                ORDER BY buyer_purchse_order_master.tr_code
            ");
            // dd(DB::getQueryLog());
          
        return view('StageWiseWIPReport', compact('Buyer_Purchase_Order_List','fromDate','toDate','Status','orderType','job_status_id','orderTypeId','jobStatusList','orderTypeList', 'vendorList', 'vendorId'));
    }
    
    public function VendorWiseWIPReport(Request $request)
    { 
        // $toDate = date('Y-m-d');     
      
        // $Buyer_Purchase_Order_List = DB::select("
        //     SELECT 
        //         buyer_purchse_order_master.*, 
        //         brand_master.brand_name,
        //         main_style_master.mainstyle_name,
        //         job_status_master.job_status_name,
        //         (SELECT IFNULL(SUM(total_qty),0) FROM cut_panel_grn_master WHERE cut_panel_grn_master.sales_order_no = buyer_purchse_order_master.tr_code) AS cut_qty,
        //         (SELECT IFNULL(SUM(total_qty),0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) AS prod_qty
        //     FROM 
        //         buyer_purchse_order_master 
        //         LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
        //         LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
        //         LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
        //     WHERE 
        //         (
        //             order_received_date <= '".$toDate."' 
        //             AND buyer_purchse_order_master.job_status_id = 1 
        //             AND og_id != 4
        //             AND order_type != 2
        //         ) 
        //         OR 
        //             (
        //                 order_close_date = '".$toDate."'
        //                 AND og_id != 4  
        //                 AND buyer_purchse_order_master.delflag = 0  
        //             )  
        //          AND order_type != 2   
        //     ORDER BY buyer_purchse_order_master.tr_code");
            // dd(DB::getQueryLog());
         
        $vendorList = DB::SELECT("SELECT 
                            lm.ac_short_name AS vendor_name,
                            wom.sales_order_no, wom.vendorId,
                            lm1.ac_short_name AS buyer_name,
                            COALESCE(vwod.work_order_qty, 0) AS work_order_qty,
                            COALESCE(pid.packing_qty, 0) AS packing_qty,
                            COALESCE(sid.stitching_qty, 0) AS stitching_qty,
                            COALESCE(qcid.reject_qty, 0) AS reject_qty,
                            COALESCE(soc.fabric_value, 0) AS fabric_value,
                            COALESCE(soc.packing_trims_value, 0) AS packing_trims_value,
                            COALESCE(soc.sewing_trims_value, 0) AS sewing_trims_value,
                            COALESCE(cpg.cut_panel_grn_qty, 0) AS cut_panel_grn_qty,
                            COALESCE(waq.wip_adjust_qty, 0) AS wip_adjust_qty
                        FROM vendor_work_order_master wom
                        INNER JOIN buyer_purchse_order_master bpom 
                            ON bpom.tr_code = wom.sales_order_no
                        LEFT JOIN ledger_master lm 
                            ON lm.ac_code = wom.vendorId 
                        LEFT JOIN ledger_master lm1 
                            ON lm1.ac_code = wom.ac_code 
                        LEFT JOIN (
                            SELECT sales_order_no,vendorId, SUM(final_bom_qty) AS work_order_qty
                            FROM vendor_work_order_master
                            GROUP BY sales_order_no,vendorId
                        ) vwod ON vwod.sales_order_no = wom.sales_order_no AND vwod.vendorId = wom.vendorId
                        LEFT JOIN (
                            SELECT vendorId,sales_order_no, SUM(size_qty_total) AS packing_qty
                            FROM packing_inhouse_detail
                            GROUP BY sales_order_no,vendorId
                        ) pid ON pid.sales_order_no = wom.sales_order_no AND pid.vendorId = wom.vendorId
                        LEFT JOIN (
                            SELECT vendorId,sales_order_no, SUM(size_qty_total) AS stitching_qty
                            FROM stitching_inhouse_detail
                            GROUP BY sales_order_no,vendorId
                        ) sid ON sid.sales_order_no = wom.sales_order_no AND sid.vendorId = wom.vendorId
                        LEFT JOIN (
                            SELECT vendorId,sales_order_no, SUM(size_qty_total) AS reject_qty
                            FROM qcstitching_inhouse_reject_detail
                            GROUP BY sales_order_no,vendorId
                        ) qcid ON qcid.sales_order_no = wom.sales_order_no AND qcid.vendorId = wom.vendorId
                        LEFT JOIN (
                            SELECT sales_order_no,fabric_value,sewing_trims_value,packing_trims_value
                            FROM sales_order_costing_master
                            GROUP BY sales_order_no
                        ) soc ON soc.sales_order_no = wom.sales_order_no
                        LEFT JOIN (
                            SELECT vendorId,sales_order_no,SUM(size_qty_total) AS cut_panel_grn_qty
                            FROM cut_panel_grn_detail
                            GROUP BY sales_order_no,vendorId
                        ) cpg ON cpg.sales_order_no = wom.sales_order_no AND cpg.vendorId = wom.vendorId
                        LEFT JOIN (
                            SELECT vendorId,sales_order_no,SUM(size_qty_total) AS wip_adjust_qty
                            FROM WIP_Adjustable_Qty_detail
                            GROUP BY sales_order_no,vendorId
                        ) waq ON waq.sales_order_no = wom.sales_order_no  AND waq.vendorId = wom.vendorId
                        WHERE bpom.og_id != 4 AND bpom.job_status_id = 1 
                            AND bpom.delflag = 0 Group by wom.sales_order_no,wom.vendorId"); 
             
        return view('VendorWiseWIPReport', compact('vendorList'));
    }
    
    public function rptFGMovingReport(Request $request)
    {
        $fromDate = $request->fromDate ? $request->fromDate : date('Y-m-01');   
        $toDate = $request->toDate ? $request->toDate : date('Y-m-d');     
        $job_status_id =$request->job_status_id;
        $orderTypeId = $request->orderTypeId; 
        $Status=''; 
        $orderType='';
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        //     select('buyer_purchse_order_master.*', 'buyer_purchse_order_master.sam','brand_master.brand_name','main_style_master.mainstyle_name'
        //     ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
        //     , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
        //     ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        //     ->where('buyer_purchse_order_master.delflag','=', '0')
        //     ->where('buyer_purchse_order_master.og_id','!=', '4')
        //     ->where('buyer_purchse_order_master.order_type','=',$order_type)
        //     ->where('buyer_purchse_order_master.job_status_id','=',$job_status_id)
        //     ->orderBy('buyer_purchse_order_master.tr_code')
        //     ->get();
        
        $jobStatusList = DB::SELECT("SELECT job_status_id, job_status_name FROM job_status_master where delflag=0");
        $orderTypeList = DB::SELECT("SELECT orderTypeId, order_type FROM order_type_master where delflag=0");
        
        $filter = "";
        $filter1 = "";
        if($request->job_status_id != "")
        {   
            foreach($request->job_status_id as $st)
            {
                $Status=$Status."".$st.",";
                
            }
            $Status=rtrim($Status,",");
            $filter .= " AND buyer_purchse_order_master.job_status_id IN(".$Status.")";
        }
        else 
        {
            $job_status_id = [1, 2, 4, 5];
            $Status = implode(',', $job_status_id);
            $filter .= " AND buyer_purchse_order_master.job_status_id IN (" . $Status . ")";
        }
        if($request->orderTypeId != "")
        {
            foreach($request->orderTypeId as $ot)
            {
                $orderType=$orderType."".$ot.",";
                
            }
            $orderType=rtrim($orderType,",");
            $filter1 .= " AND buyer_purchse_order_master.order_type IN(".$orderType.")";
        }
        else 
        {
            $orderType = [1, 3];
            $orderType = implode(',', $orderType);
            $filter1 .= " AND buyer_purchse_order_master.order_type IN (".$orderType.")";
        }
      
      
        // $Buyer_Purchase_Order_List = DB::select("SELECT buyer_purchse_order_master.*,brand_master.brand_name,main_style_master.mainstyle_name,job_status_master.job_status_name,
        //     (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
            
        //     (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty
        //     FROM buyer_purchse_order_master LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
        //     LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
        //     LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
        //     WHERE buyer_purchse_order_master.delflag = 0 ".$filter1."
        //     AND  buyer_purchse_order_master.og_id != 4 ".$filter." AND order_close_date BETWEEN '".$fromDate."' AND '".$toDate."'  OR buyer_purchse_order_master.tr_date <= '".$toDate."' AND buyer_purchse_order_master.delflag = 0 
        //     AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id IN (1,2) ".$filter1." ORDER BY buyer_purchse_order_master.tr_code");
         
           //DB::enableQueryLog();
            $Buyer_Purchase_Order_List = DB::select("
                SELECT 
                    buyer_purchse_order_master.*, 
                    brand_master.brand_name,
                    main_style_master.mainstyle_name,
                    job_status_master.job_status_name,sales_order_costing_master.total_cost_value,
                    (SELECT IFNULL(SUM(total_qty),0) FROM cut_panel_grn_master WHERE cut_panel_grn_master.sales_order_no = buyer_purchse_order_master.tr_code) AS cut_qty,
                    (SELECT IFNULL(SUM(total_qty),0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) AS prod_qty
                FROM 
                    buyer_purchse_order_master 
                    LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
                    LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                    LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
                    LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code 
                WHERE 
                    (
                        order_received_date <= '".$toDate."' 
                        AND buyer_purchse_order_master.job_status_id = 1 
                        AND og_id != 4
                        AND order_type != 2
                    ) 
                    OR 
                        (
                            order_close_date = '".$toDate."'
                            AND og_id != 4 
                            ".$filter1." 
                            AND buyer_purchse_order_master.delflag = 0  
                            ".$filter."
                        )  
                     AND order_type != 2  ".$filter1." 
                ORDER BY buyer_purchse_order_master.tr_code
            ");
            // dd(DB::getQueryLog());
         
         
         
         
             
        return view('rptFGMovingReport', compact('Buyer_Purchase_Order_List','fromDate','toDate','Status','orderType','job_status_id','orderTypeId','jobStatusList','orderTypeList'));
    }
    
    public function Get_Daily_WIP_Tracking_Report()
    {         
        return view('Get_Daily_WIP_Tracking_Report');
    }
    
    public function rptDailyWIPTracking(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $dateArr = $this->getBetweenDates($fromDate, $toDate); 
        
        $lineUnit1Data = DB::select("SELECT stitching_inhouse_size_detail2.line_id,line_master.line_name  
                from stitching_inhouse_size_detail2 
                INNER JOIN line_master ON line_master.line_id = stitching_inhouse_size_detail2.line_id
                where vendorId=56 GROUP BY stitching_inhouse_size_detail2.line_id order by stitching_inhouse_size_detail2.line_id asc");
        
        $lineUnit2Data = DB::select("SELECT stitching_inhouse_size_detail2.line_id,line_master.line_name  
                from stitching_inhouse_size_detail2 
                INNER JOIN line_master ON line_master.line_id = stitching_inhouse_size_detail2.line_id
                where vendorId=115 GROUP BY stitching_inhouse_size_detail2.line_id order by stitching_inhouse_size_detail2.line_id asc");       
         
        $lineUnit3Data = DB::select("SELECT stitching_inhouse_size_detail2.line_id,line_master.line_name  
                from stitching_inhouse_size_detail2 
                INNER JOIN line_master ON line_master.line_id = stitching_inhouse_size_detail2.line_id
                where vendorId=69 GROUP BY stitching_inhouse_size_detail2.line_id order by stitching_inhouse_size_detail2.line_id asc");       
                       
        return view('rptDailyWIPTracking',compact('fromDate','toDate','dateArr','lineUnit1Data','lineUnit2Data','lineUnit3Data'));
    }
    
    public function Get_Finishing_WIP_Report()
    {  
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();       
        return view('Get_Finishing_WIP_Report',compact('Ledger'));
    }
    
    public function rptFinishingWIP(Request $request)
    {
         if ($request->ajax()) 
        { 
            $job_status_id =  str_replace(array('"','"'),'',str_replace(array('[',']'),'',json_encode($request->job_status_id))); 
            $order_type = str_replace(array('"','"'),'',str_replace(array('[',']'),'',json_encode($request->order_type)));
            $vendorId = $request->vendorId;    
            $sales_order_no = $request->sales_order_no;    
            
            $vendorData = DB::table('vendor_work_order_master')
             ->select('vendor_work_order_master.sales_order_no')
             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no')
             ->where('vendor_work_order_master.vendorId','=',$request->vendorId)
             ->whereIn('buyer_purchse_order_master.job_status_id', $request->job_status_id)
             ->whereIn('buyer_purchse_order_master.order_type', $request->order_type)
             ->DISTINCT('vendor_work_order_master.sales_order_no')
             ->get();
            $sales_order_no = "";
            foreach($vendorData as $row)
            {
                $sales_order_no .= "'".$row->sales_order_no."',";
            }
            
            $sales_order_no = rtrim($sales_order_no,","); 
            
              
            $ProductionOrderDetailList = DB::select("SELECT buyer_purchse_order_master.tr_code ,buyer_purchse_order_master.po_code, 
                buyer_purchse_order_master.mainstyle_id,mainstyle_name,buyer_purchse_order_master.style_no,
                buyer_purchse_order_master.Ac_code, ac_name,buyer_purchse_order_master.order_rate,  
                buyer_purchase_order_detail.color_id,color_name, sum(size_qty_total) as order_qty  
                FROM `buyer_purchse_order_master` 
                inner join buyer_purchase_order_detail on buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code
                inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
                left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
                left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
                where buyer_purchse_order_master.job_status_id IN(".$job_status_id.") 
                AND  buyer_purchse_order_master.og_id!=4
                AND buyer_purchse_order_master.order_type IN (".$order_type.")
                AND buyer_purchse_order_master.tr_code IN (".$sales_order_no.")
                group by main_style_master.mainstyle_id, buyer_purchse_order_master.Ac_code, buyer_purchase_order_detail.color_id,
                buyer_purchse_order_master.userId,buyer_purchse_order_master.tr_code");
            
            return Datatables::of($ProductionOrderDetailList)
            ->addColumn('vendorName',function ($row) use($vendorId) 
            {
                $vendorData = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','=', $vendorId)->first();   
                $vendorName = $vendorData->ac_name;
                return $vendorName;
           })
           ->addColumn('Cut_Qty',function ($row) use($vendorId) 
           {
    
                 $CutGrnList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 
                                    where sales_order_no='".$row->tr_code."' 
                                    AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                                    AND vendorId ='".$vendorId."'");
                 
                 $Cut_Qty = isset($CutGrnList[0]->size_qty) ? $CutGrnList[0]->size_qty : 0;
                 return round($Cut_Qty,2);
           })  
          ->addColumn('sew_Qty',function ($row) use($vendorId)
          {
    
             $sewingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from stitching_inhouse_size_detail2 
                    where sales_order_no='".$row->tr_code."' 
                    AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                    AND vendorId ='".$vendorId."'");
             
             $sew_Qty = isset($sewingList[0]->size_qty) ? $sewingList[0]->size_qty : 0;
    
             return round($sew_Qty,2);
           })
          ->addColumn('pack_Qty',function ($row) use($vendorId) 
          {
              
                $packingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from packing_inhouse_size_detail2 
                        where sales_order_no='".$row->tr_code."' 
                        AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                        AND vendorId ='".$vendorId."'");
                 
                $pack_Qty = isset($packingList[0]->size_qty) ? $packingList[0]->size_qty : 0;
                             
    
             return round($pack_Qty,2);
           })
           ->addColumn('Ship',function ($row) 
           {
    
             $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."' 
                 AND carton_packing_inhouse_size_detail2.mainstyle_id='".$row->mainstyle_id."' 
                 AND carton_packing_inhouse_size_detail2.color_id ='".$row->color_id."' 
                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                 
             $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
    
             return round($Ship,2);
             
           })
           ->addColumn('sew_pack',function ($row) use($vendorId) 
           {
    
                $sewingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from stitching_inhouse_size_detail2 
                        where sales_order_no='".$row->tr_code."' 
                        AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                        AND vendorId ='".$vendorId."'");
                 
                $sew_Qty = isset($sewingList[0]->size_qty) ? $sewingList[0]->size_qty : 0;
                     
                $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                  
                $packingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from packing_inhouse_size_detail2 
                        where sales_order_no='".$row->tr_code."' 
                        AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                        AND vendorId ='".$vendorId."'");
                 
                $pack_Qty = isset($packingList[0]->size_qty) ? $packingList[0]->size_qty : 0;
                $sew_pack = $sew_Qty - $pack_Qty;
                return round($sew_pack,2);
             
           })
           ->addColumn('sew_pack_value',function ($row) use($vendorId) 
           {
    
                $sewingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from stitching_inhouse_size_detail2 
                        where sales_order_no='".$row->tr_code."' 
                        AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                        AND vendorId ='".$vendorId."'");
                 
                $sew_Qty = isset($sewingList[0]->size_qty) ? $sewingList[0]->size_qty : 0;
                     
                $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                  
                $packingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from packing_inhouse_size_detail2 
                        where sales_order_no='".$row->tr_code."' 
                        AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                        AND vendorId ='".$vendorId."'");
                 
                $pack_Qty = isset($packingList[0]->size_qty) ? $packingList[0]->size_qty : 0;
                $sew_pack_value = $sew_Qty - $pack_Qty  * $row->order_rate;
                return round($sew_pack_value,2);
             
           })
           ->addColumn('Cut_To_Pack',function ($row) use($vendorId) 
           {
    
                $CutGrnList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 
                                    where sales_order_no='".$row->tr_code."' 
                                    AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                                    AND vendorId ='".$vendorId."'");
                 
                $Cut_Qty = isset($CutGrnList[0]->size_qty) ? $CutGrnList[0]->size_qty : 0;
                 
                $packingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from packing_inhouse_size_detail2 
                        where sales_order_no='".$row->tr_code."' 
                        AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                        AND vendorId ='".$vendorId."'");
                 
                $pack_Qty = isset($packingList[0]->size_qty) ? $packingList[0]->size_qty : 0;
                         
                if($pack_Qty > 0 && $Cut_Qty > 0)
                {
                    $Cut_To_Pack = ($pack_Qty/$Cut_Qty) * 100;
                }
                else
                {
                    $Cut_To_Pack = 0;
                }
                             
    
             return round($Cut_To_Pack,2);
           })
           ->addColumn('Cut_to_Ship',function ($row) use($vendorId)  
           { 
                $CutGrnList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 
                                    where sales_order_no='".$row->tr_code."' 
                                    AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                                    AND vendorId ='".$vendorId."'");
                 
                $Cut_Qty = isset($CutGrnList[0]->size_qty) ? $CutGrnList[0]->size_qty : 0;
                 
             
                $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                     where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."' 
                     AND carton_packing_inhouse_size_detail2.mainstyle_id='".$row->mainstyle_id."' 
                     AND carton_packing_inhouse_size_detail2.color_id ='".$row->color_id."' 
                     and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                 
                $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
    
                if($Ship > 0 && $Cut_Qty > 0)
                {
                    $Cut_to_Ship = ($Ship/$Cut_Qty) * 100;
                }
                else
                {
                    $Cut_to_Ship = 0;
                }
    
                return  round($Cut_to_Ship,2);
           })
             ->rawColumns(['Cut_Qty','sew_Qty','pack_Qty','Ship','Cut_To_Pack','Cut_to_Ship'])
             
             ->make(true);
    
            }
             
                
        return view('rptFinishingWIP');
    }
    
    public function GetSalesOrderNoList(Request $request)
    {
        //echo(json_encode($request->job_status_id));exit;
        
        $vendorData = DB::table('vendor_work_order_master')
             ->select('vendor_work_order_master.sales_order_no')
             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no')
             ->where('vendor_work_order_master.vendorId','=',$request->vendorId)
             ->whereIn('buyer_purchse_order_master.job_status_id', $request->job_status_id)
             ->whereIn('buyer_purchse_order_master.order_type', $request->order_type)
             ->DISTINCT('vendor_work_order_master.sales_order_no')
             ->get();
        
        $html = "<option value='all'>--All--</option>";
        
        foreach($vendorData as $row)
        {
            $html .= '<option value="'.$row->sales_order_no.'">'.$row->sales_order_no.'</option>';
        }
         return response()->json(['html' => $html]);
    }
    
    public function GetColorWiseQty(Request $request)
    {
         //DB::enableQueryLog();
         $buyerData = DB::table('buyer_purchase_order_detail')
                     ->select('size_qty_total','sam')
                     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'buyer_purchase_order_detail.tr_code')
                     ->where('buyer_purchase_order_detail.tr_code','=',$request->tr_code)
                     ->where('buyer_purchase_order_detail.color_id','=',$request->color_id)
                     ->get();
          // dd(DB::getQueryLog());       
        $color_order_qty = isset($buyerData[0]->size_qty_total) ? $buyerData[0]->size_qty_total : 0;
        $sam = isset($buyerData[0]->sam) ? $buyerData[0]->sam : 0;
        
        return response()->json(['qty' => $color_order_qty, 'sam' => $sam]);
    }
     public function Get_Produced_Minutes_report()
    {         
        return view('Get_Produced_Minutes_report');
    }
    
    public function rptProducedMinutes(Request $request)
    { 
        $fdate = $request->fromDate;
        $tdate = $request->toDate;
        
        $Stitching=DB::select("select stitching_inhouse_master.sti_date,stitching_inhouse_master.vendorId
            from stitching_inhouse_master where stitching_inhouse_master.sti_date between '".$fdate."' and '".$tdate."'  
            GROUP By stitching_inhouse_master.sti_date order by stitching_inhouse_master.sti_date");  
            
        return view('rptProducedMinutes',compact('fdate','tdate','Stitching'));
    }
    
    public function Get_WIP_Report2()
    {  
        
        $vendorList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '4')->get();
        $salesOrderList = BuyerPurchaseOrderMasterModel::where('buyer_purchse_order_master.delflag','=', '0')->get();
        
        return view('Get_WIP_Report2',compact('vendorList','salesOrderList'));
    }
    
    public function rptWIPReport2(Request $request)
    {
        $order_type = $request->order_type; 
        $job_status_id = $request->job_status_id; 
        $sales_order_no = $request->sales_order_no; 
        $date = $request->date;  
        $vendorId = $request->vendorId;   
        
       
        if($vendorId > 0)
        {
            $vendorData = " AND buyer_purchse_order_master.Ac_code ='".$vendorId."'";
        }
        else
        {
            $vendorData = "";
        }
        
        if($sales_order_no != "")
        {
            $salesData = " AND buyer_purchse_order_master.tr_code ='".$sales_order_no."'";
        }
        else
        {
            $salesData = "";
        }
        
        
        if($order_type > 0)
        {
            $orderTypeData = " AND buyer_purchse_order_master.order_type ='".$order_type."'";
        }
        else
        {
            $orderTypeData = "";
        }
        
        if($job_status_id > 0)
        {
             $job_statusData = " AND buyer_purchse_order_master.job_status_id ='".$job_status_id."'";
        }
        else
        {
            $job_statusData = "";
        }
        
        $Buyer_Purchase_Order_List = DB::select("SELECT buyer_purchse_order_master.*,ledger_master.ac_name as vendorName,color_master.color_name,
            stitching_inhouse_size_detail2.color_id,(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
            (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty
            FROM buyer_purchse_order_master LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
            INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
            INNER JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code 
            INNER JOIN stitching_inhouse_size_detail2 ON stitching_inhouse_size_detail2.sales_order_no = buyer_purchse_order_master.tr_code 
            INNER JOIN color_master ON color_master.color_id = stitching_inhouse_size_detail2.color_id 
            WHERE buyer_purchse_order_master.delflag = 0 
            AND buyer_purchse_order_master.og_id != 4 ".$orderTypeData." ".$job_statusData."  ".$vendorData."  ".$salesData." GROUP BY stitching_inhouse_size_detail2.sales_order_no");
                
        return view('rptWIPReport2', compact('Buyer_Purchase_Order_List','order_type','job_status_id','sales_order_no','date','vendorId'));
    }
    
    public function Get_WIP_Report3()
    {         
        return view('Get_WIP_Report3');
    }
    
    public function rptWIPReportss3(Request $request)
    {   
        $job_status_id = $request->job_status_id ? $request->job_status_id : "";
        $order_type = $request->order_type ? $request->order_type : "";
        $tr_date = $request->tr_date ? $request->tr_date : "";
       
        $selectedValue = str_replace('"', '', str_replace(array('[', ']'), '', htmlspecialchars(json_encode($job_status_id), ENT_NOQUOTES)));
        
        $selectedValueOrderType = str_replace('"', '', str_replace(array('[', ']'), '', htmlspecialchars(json_encode($order_type), ENT_NOQUOTES)));  
       
        if($job_status_id == "" && $order_type == "" && $tr_date=="")
        { 
            echo "<script>location.href='rptWIPReportss3?job_status_id=1&order_type=1&tr_date=".date('Y-m-d')."';</script>";
        }
       
        $Status='';
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
        if($request->job_status_id !="")
        { 
            if (is_array($job_status_id)) 
            {
               foreach($job_status_id as $st)
               {
                    $Status=$Status."".$st.","; 
               } 
            } 
            else 
            {
                $job_status_id = explode(' ', $job_status_id);
                foreach($job_status_id as $st)
                {
                    $Status=$Status."".$st.",";
                    
                } 
            }  
        }
        $Status=rtrim($Status,",");
        
        if($request->job_status_id != "")
        {
            $jobStatus = " AND buyer_purchse_order_master.job_status_id IN (".$Status.")";
        }
        else
        {
            $jobStatus = "";
        }
        $OrderT = "";
        if($request->order_type !="")
        { 
            if (is_array($order_type)) 
            {
               foreach($order_type as $order)
               {
                    $OrderT=$OrderT."".$order.","; 
               } 
            } 
            else 
            {
                $order_type = explode(' ', $order_type);
                foreach($order_type as $order)
                {
                    $OrderT=$OrderT."".$order.",";
                    
                } 
            }  
        }
        $OrderT=rtrim($OrderT,",");
        
        if($request->order_type > 0)
        {
            $orderType = " AND buyer_purchse_order_master.order_type IN (".$OrderT.")";
        }
        else
        {
            $orderType = "";
        }
        
        $currentDate = $request->tr_date ? $request->tr_date : "";
        
        if($request->tr_date != "")
        {
            $cpgDate = " AND cut_panel_grn_master.cpg_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
            $stiDate = " AND stitching_inhouse_master.sti_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
        }
        else
        {
            $cpgDate = "";
            $stiDate= "";
        }
     
             // DB::enableQueryLog();    
        $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.Ac_name,fg_master.fg_name,
            buyer_purchse_order_master.sam,merchant_master.merchant_name,brand_master.brand_name,job_status_master.job_status_name,main_style_master.mainstyle_name,
            (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
            (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty
            FROM buyer_purchse_order_master
            LEFT JOIN usermaster ON usermaster.userId=buyer_purchse_order_master.userId 
            LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
            LEFT JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
            LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id
            LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
            LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
            WHERE  buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 
            ".$jobStatus." ".$orderType." ORDER BY buyer_purchse_order_master.tr_code");
             // dd(DB::getQueryLog()); 
             
        $orderTypeData = DB::SELECT("SELECT order_type FROM order_type_master WHERE orderTypeId IN(".$OrderT.")");
        $order_type = "";  
        foreach($orderTypeData as $row)
        {
            $order_type = $order_type."".$row->order_type.",";
        }
        
        return view('rptWIPReportss3', compact('Buyer_Purchase_Order_List','Status','currentDate','Financial_Year','tr_date','order_type','selectedValue','selectedValueOrderType'));
    }
    
    public function GetWIPInOutStockReportForm()
    {  
        return view('GetWIPInOutStockReportForm');
    }
    
    
    public function WIPInOutStockReport(Request $request)
    {
        
        $fdate= $request->fdate;
        $tdate= $request->tdate;
         
        if($tdate>date('Y-m-d')){$tdate=date('Y-m-d');}
         
         
        $period = $this->getBetweenDates($fdate, $tdate);
           
        $FirmDetail =  DB::table('firm_master')->first();
      
        return view('WIPInOutStockReport', compact('period','fdate', 'tdate','FirmDetail'));
      
    }
    
    public function WIPInOutStockReportList(Request $request)
    {
        
        $fdate = $request->query('fdate');
        $tdate = $request->query('tdate');
         
        if($tdate>date('Y-m-d')){$tdate=date('Y-m-d');}
         
         
        $period = $this->getBetweenDates($fdate, $tdate);
           
        $FirmDetail =  DB::table('firm_master')->first();
      
        return view('WIPInOutStockReportList', compact('period','fdate', 'tdate','FirmDetail'));
      
    }
    
    public function rptWIPReportss4(Request $request)
    {   
        $job_status_id = $request->job_status_id ? $request->job_status_id : "";
        $order_type = $request->order_type ? $request->order_type : "";
        $tr_date = $request->tr_date ? $request->tr_date : "";
       
        $selectedValue = str_replace('"', '', str_replace(array('[', ']'), '', htmlspecialchars(json_encode($job_status_id), ENT_NOQUOTES)));
        
        $selectedValueOrderType = str_replace('"', '', str_replace(array('[', ']'), '', htmlspecialchars(json_encode($order_type), ENT_NOQUOTES)));  
       
        if($job_status_id == "" && $order_type == "" && $tr_date=="")
        { 
            echo "<script>location.href='rptWIPReportss4?job_status_id=1&order_type=1&tr_date=".date('Y-m-d')."';</script>";
        }
       
        $Status='';
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
        if($request->job_status_id !="")
        { 
            if (is_array($job_status_id)) 
            {
               foreach($job_status_id as $st)
               {
                    $Status=$Status."".$st.","; 
               } 
            } 
            else 
            {
                $job_status_id = explode(' ', $job_status_id);
                foreach($job_status_id as $st)
                {
                    $Status=$Status."".$st.",";
                    
                } 
            }  
        }
        $Status=rtrim($Status,",");
        
        if($request->job_status_id != "")
        {
            $jobStatus = " AND buyer_purchse_order_master.job_status_id IN (".$Status.")";
        }
        else
        {
            $jobStatus = "";
        }
        $OrderT = "";
        if($request->order_type !="")
        { 
            if (is_array($order_type)) 
            {
               foreach($order_type as $order)
               {
                    $OrderT=$OrderT."".$order.","; 
               } 
            } 
            else 
            {
                $order_type = explode(' ', $order_type);
                foreach($order_type as $order)
                {
                    $OrderT=$OrderT."".$order.",";
                    
                } 
            }  
        }
        $OrderT=rtrim($OrderT,",");
        
        if($request->order_type > 0)
        {
            $orderType = " AND buyer_purchse_order_master.order_type IN (".$OrderT.")";
        }
        else
        {
            $orderType = "";
        }
        
        $currentDate = $request->tr_date ? $request->tr_date : "";
        
        if($request->tr_date != "")
        {
            $cpgDate = " AND cut_panel_grn_master.cpg_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
            $stiDate = " AND stitching_inhouse_master.sti_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
        }
        else
        {
            $cpgDate = "";
            $stiDate= "";
        }
     
             // DB::enableQueryLog();    
        $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.Ac_name,fg_master.fg_name,
            buyer_purchse_order_master.sam,merchant_master.merchant_name,brand_master.brand_name,job_status_master.job_status_name,main_style_master.mainstyle_name,
            (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
            (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty
            FROM buyer_purchse_order_master
            LEFT JOIN usermaster ON usermaster.userId=buyer_purchse_order_master.userId 
            LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
            LEFT JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
            LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id
            LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
            LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
            WHERE  buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 
            ".$jobStatus." ".$orderType." ORDER BY buyer_purchse_order_master.tr_code");
             // dd(DB::getQueryLog()); 
             
        $orderTypeData = DB::SELECT("SELECT order_type FROM order_type_master WHERE orderTypeId IN(".$OrderT.")");
        $order_type = "";  
        foreach($orderTypeData as $row)
        {
            $order_type = $order_type."".$row->order_type.",";
        }
        
        return view('rptWIPReportss4', compact('Buyer_Purchase_Order_List','Status','currentDate','Financial_Year','tr_date','order_type','selectedValue','selectedValueOrderType'));
    }
    
    public function PPCWIPReport()
    {  
        $chekform = DB::table('form_auth')
         ->where('emp_id', Session::get('userId'))
         ->where('form_id', '149')
         ->first();     
        
        $UnitMasterData = DB::SELECT("SELECT ppc_wip_report.*,ledger_master.ac_code as Ac_code,ac_short_name,line_master.line_id, line_master.line_name,
        
                            (SELECT COUNT(*) FROM line_master WHERE Ac_code = ledger_master.ac_code AND delflag = 0) AS total_line_count,
                            
                            ifnull((SELECT ifnull(SUM(cut_panel_issue_master.total_qty),0) FROM cut_panel_issue_master
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = cut_panel_issue_master.sales_order_no
                            WHERE cut_panel_issue_master.vendorId = line_master.Ac_code AND cut_panel_issue_master.line_id = line_master.line_id 
                            AND cut_panel_issue_master.sales_order_no = buyer_purchse_order_master.tr_code
                            AND buyer_purchse_order_master.job_status_id = 1) 
                            - ((SELECT ifnull(SUM(stitching_inhouse_master.total_qty),0) FROM stitching_inhouse_master 
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no
                            WHERE stitching_inhouse_master.vendorId = line_master.Ac_code AND line_id = line_master.line_id 
                            AND stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code AND buyer_purchse_order_master.job_status_id = 1)),0) as line_wip,
                            
                            ifnull((SELECT ifnull(SUM(cut_panel_grn_master.total_qty),0) FROM cut_panel_grn_master INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = cut_panel_grn_master.vpo_code 
                            WHERE vendor_purchase_order_master.vendorId = line_master.Ac_code AND vendor_purchase_order_master.line_id = line_master.line_id) 
                            - ((SELECT ifnull(SUM(cut_panel_issue_master.total_qty),0) FROM cut_panel_issue_master INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = cut_panel_issue_master.sales_order_no 
                            WHERE vendorId = line_master.Ac_code AND line_id = line_master.line_id AND cut_panel_issue_master.sales_order_no = buyer_purchse_order_master.tr_code AND buyer_purchse_order_master.job_status_id = 1)),0) as cutting_stock,
                            
                            
                            ifnull((SELECT SUM(cut_panel_issue_master.total_qty) FROM cut_panel_issue_master INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = cut_panel_issue_master.sales_order_no WHERE buyer_purchse_order_master.job_status_id = 1 AND cut_panel_issue_master.vendorId = line_master.Ac_code AND cut_panel_issue_master.line_id = line_master.line_id AND cut_panel_issue_master.cpi_date = CURDATE() - INTERVAL 1 DAY),0) as cutting,
                            
                            ifnull((SELECT SUM(stitching_inhouse_master.total_qty) FROM stitching_inhouse_master INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no WHERE buyer_purchse_order_master.job_status_id = 1 AND stitching_inhouse_master.vendorId = line_master.Ac_code AND stitching_inhouse_master.line_id = line_master.line_id AND stitching_inhouse_master.sti_date = CURDATE() - INTERVAL 1 DAY),0) as production,
                            
                            ifnull((SELECT SUM(vendor_purchase_order_master.final_bom_qty) FROM vendor_purchase_order_master INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = vendor_purchase_order_master.sales_order_no WHERE buyer_purchse_order_master.job_status_id = 1 AND  vendor_purchase_order_master.vendorId = line_master.Ac_code AND vendor_purchase_order_master.line_id = line_master.line_id AND vendor_purchase_order_master.process_id=1),0) as po_qty,
                            
                            ifnull((SELECT SUM(capacity) FROM ppc_wip_report WHERE Ac_code = line_master.Ac_code AND line_id = line_master.line_id),0) as capacity
                            
                            FROM ledger_master
                            INNER JOIN line_master ON line_master.Ac_code = ledger_master.ac_code 
                            LEFT JOIN ppc_wip_report ON ppc_wip_report.Ac_code = ledger_master.ac_code AND ppc_wip_report.line_id = line_master.line_id 
                            WHERE ledger_master.ac_code IN (56,115,110,686,113) AND line_master.delflag=0 GROUP BY line_master.line_id
                            ORDER BY CAST(REGEXP_SUBSTR(ac_short_name, '[0-9]+') AS UNSIGNED) ASC");
        
        return view('PPCWIPReport', compact('chekform', 'UnitMasterData'));
    }
    
    public function StorePPCWIPData(Request $request)
    {       
        $capacity = !empty($request->capacity) ? $request->capacity : 0;
        $days = !empty($request->days) ? $request->days : 0;

        DB::table('ppc_wip_report')->where('Ac_code', $request->Ac_code)->where('line_id', $request->line_id)->delete(); 
        
        DB::SELECT('INSERT INTO ppc_wip_report(Ac_code,line_id,line_wip,cutting_stock,bal_to_cut,cutting,production,total,capacity,machine_count,available_mins,line_efficiency,sam,days,userId,created_at)
                    SELECT "'.$request->Ac_code.'", "'.$request->line_id.'", "'.$request->line_wip.'", "'.$request->cutting_stock.'", "'.$request->bal_to_cut.'", 
                    "'.$request->cutting.'", "'.$request->production.'", "'.$request->total.'", "'.$capacity.'","'.$request->machine_count.'","'.$request->available_mins.'","'.$request->line_efficiency.'","'.$request->sam.'",
                    "'.$days.'", "'.Session::get('userId').'", "'.date("Y-m-d H:i:s").'"');

        
        return 1;
    }
    
    public function PPCHolidayCalendar()
    {    
        $unitMaster = DB::table('ledger_master')->whereIn('ac_code',[56,115,110,686,113])->where('delflag', 0)->get();       
        return view('PPCHolidayCalendar', compact('unitMaster'));
    }
    
    public function PPCHolidayCalendarList()
    {    
        $PPCHolidayMaster = DB::table('ppc_holiday')->select('ppc_holiday.*','ledger_master.ac_short_name','usermaster.username')
                            ->join('ledger_master', 'ledger_master.ac_code', '=', 'ppc_holiday.unit_id')
                            ->join('usermaster', 'usermaster.userId', '=', 'ppc_holiday.userId')
                            ->where('ppc_holiday.delflag', 0)->get();       
        return view('PPCHolidayCalendarList', compact('PPCHolidayMaster'));
    }
    
    public function StorePPCHolidayCalendar(Request $request)
    {       
        DB::SELECT('INSERT INTO ppc_holiday(unit_id,holiday_date,userId,created_at)
                    SELECT "'.$request->unit_id.'", "'.$request->holiday_date.'", "'.Session::get('userId').'", "'.date("Y-m-d H:i:s").'"');
        
        return redirect()->route('PPCHolidayCalendarList')->with('success', 'Holiday added successfully.');
    }
    
    public function PPCHolidayDelete($id)
    {
        DB::table('ppc_holiday')->where('ppc_holiday_id', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'.$id); 
    }
    
    public function  PPCCalendarReport($startDate, $end_date,$searchVendorId="",$searchLineId="")
    {
        
        if($searchVendorId !="")
        {
            //DB::enableQueryLog();
            $PPCList = PPCMasterModel::select('ppc_master.sr_no','sales_order_no','ppc_master.vendorId', 'ppc_master.line_id', 'ppc_master.machine_count', 'ppc_master.available_mins', 'ppc_master.line_efficiency',
                 'ppc_master.sam', 'ppc_master.production_capacity', 'ppc_master.target', 'ppc_master.start_date', 'ppc_master.end_date',
                 'ppc_master.userId', 'ppc_master.endFlag', 'ledger_master.Ac_name', 'line_master.line_name','color_master.color_name')
                 ->join('usermaster', 'usermaster.userId', '=', 'ppc_master.userId')
                 ->join('color_master', 'color_master.color_id', '=', 'ppc_master.color_id')
                 ->join('ledger_master', 'ledger_master.Ac_code', '=', 'ppc_master.vendorId')
                 ->join('line_master', 'line_master.line_id', '=', 'ppc_master.line_id')
                 ->where('ppc_master.endFlag','=' , '0')
                 ->where('ppc_master.vendorId','=' , $searchVendorId)
                 ->groupBy('ppc_master.vendorId','ppc_master.line_id')
                 ->get();
                 // dd(DB::getQueryLog());    
        }
        else
        {
            //DB::enableQueryLog();
             $PPCList = PPCMasterModel::select('ppc_master.sr_no','sales_order_no','ppc_master.vendorId', 'ppc_master.line_id', 'ppc_master.machine_count', 'ppc_master.available_mins', 'ppc_master.line_efficiency',
                 'ppc_master.sam', 'ppc_master.production_capacity', 'ppc_master.target', 'ppc_master.start_date', 'ppc_master.end_date',
                 'ppc_master.userId', 'ppc_master.endFlag', 'ledger_master.Ac_name', 'line_master.line_name','color_master.color_name')
                 ->join('usermaster', 'usermaster.userId', '=', 'ppc_master.userId')
                 ->join('color_master', 'color_master.color_id', '=', 'ppc_master.color_id')
                 ->join('ledger_master', 'ledger_master.Ac_code', '=', 'ppc_master.vendorId')
                 ->join('line_master', 'line_master.line_id', '=', 'ppc_master.line_id')
                 ->where('ppc_master.endFlag','=' , '0')
                 ->groupBy('ppc_master.vendorId','ppc_master.line_id')
                 ->get();
             //     dd(DB::getQueryLog());  
        }
       
        
        $period = $this->getBetweenDates($startDate, $end_date);
        return view('PPCCalendarReport', compact('period', 'PPCList','searchVendorId','searchLineId'));
    }
    
    
}

     
