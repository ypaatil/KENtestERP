<?php

namespace App\Http\Controllers;

use App\Models\RejectedPcsDeliveryChallanModel;
use App\Models\RejectedPcsDeliveryChallanDetailModel;
use App\Models\BuyerPurchaseOrderDetailModel;
use App\Models\LedgerModel;
use App\Models\ItemModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\FirmModel;
use App\Models\SizeDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Imports\ColorImport;
use Maatwebsite\Excel\Facades\Excel;
use Image;

class RejectedPcsDeliveryChallanController extends Controller
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
        ->where('form_id', '47')
        ->first();
 
        $RejectedPcsDeliveryChallanList = RejectedPcsDeliveryChallanModel::select('rejected_pcs_delivery_challan.*','ledger_master.ac_name as Ac_name')->where('rejected_pcs_delivery_challan.delflag','=', '0')
                                         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'rejected_pcs_delivery_challan.vendorId', 'left outer')
                                          ->get();
        return view('RejectedPcsDeliveryChallanList',compact( 'chekform','RejectedPcsDeliveryChallanList'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CartonPackingInhouse'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->get();
        return view('RejectedPcsDeliveryChallan',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList', 'BuyerPurchaseOrderList','BuyerList','Ledger', 'counter_number','FirmList'));

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
        ->where('type','=','RejectedPCSDeliveryChallan')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
 
        $sales_order_no=implode($request->sales_order_no,',');
         
        $data1=array
            (
                'rpdc_code'=>$TrNo, 
                'rpdc_date'=>$request->rpdc_date, 
                'firm_id'=>$request->firm_id,
                'sales_order_no'=>$sales_order_no,
                'vendorId'=>$request->vendorId, 
                'total_qty'=>$request->total_qty,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0'
             );
         
            RejectedPcsDeliveryChallanModel::insert($data1);
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='RejectedPCSDeliveryChallan'");
        
            $color_id= $request->input('color_id');
            
                for($x=0; $x<count($color_id); $x++) 
                {
                    # code...
                  if($request->size_qty_total[$x]>0)
                   {
                        $data2[]=array
                        (
    					'rpdc_code'=>$TrNo,
                        'rpdc_date'=>$request->rpdc_date,
                        'sales_order_no'=>$request->sales_order_nos[$x],
                        'vendorId'=>$request->vendorId, 
                        'color_id'=>$request->color_id[$x],
                        'size_array'=>$request->size_array[$x],
                        'size_qty_array'=>$request->size_qty_array[$x],
                        'size_qty_total'=>$request->size_qty_total[$x],
                       );
                  }
    
                }
                
                RejectedPcsDeliveryChallanDetailModel::insert($data2);
            return redirect()->route('RejectedPcsDeliveryChallan.index')->with('message', 'Data Saved Succesfully');  
      
  }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function show(RejectedPcsDeliveryChallanModel $RejectedPcsDeliveryChallanModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ColorModel  RejectedPcsDeliveryChallanModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
      $RejectedList = RejectedPcsDeliveryChallanModel::find($id);
        $RejectedDetailList = RejectedPcsDeliveryChallanDetailModel::Where('rpdc_code','=',$id)->get();
        $SalesOrderList=explode(",",$RejectedList->sales_order_no);
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('tr_code',$SalesOrderList[0])->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
      
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->whereIn('tr_code',$SalesOrderList)->DISTINCT()->get();
        
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CartonPackingInhouse'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->get();
        
        return view('RejectedPcsDeliveryChallanEdit',compact('RejectedList','RejectedDetailList','SizeDetailList','ColorList', 'ItemList', 'MainStyleList','SubStyleList','FGList', 'BuyerPurchaseOrderList','BuyerList','Ledger', 'counter_number','FirmList'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RejectedPcsDeliveryChallanModel  $colorModel
     * @return \Illuminate\Http\Response
     */
        public function update(Request $request, $soc_code)
        {
            
            $sales_order_no=implode($request->sales_order_no,',');
      
            $data1=array(
                   
                'rpdc_code'=>$request->rpdc_code, 
                'rpdc_date'=>$request->rpdc_date,
                'firm_id'=>$request->firm_id,
                'sales_order_no'=>$sales_order_no,
                'vendorId'=>$request->vendorId, 
                'total_qty'=>$request->total_qty,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0',
            );  

            $rejectedList = RejectedPcsDeliveryChallanModel::findOrFail($request->rpdc_code); 
      
            $rejectedList->fill($data1)->save();
        
         
        DB::table('rejected_pcs_delivery_challan_detail')->where('rpdc_code', $request->input('rpdc_code'))->delete();
         
        $color_id= $request->input('color_id');
        for($x=0; $x<count($color_id); $x++) 
        {
                $data2[]=array
                (
				'rpdc_code'=>$request->rpdc_code,
                'rpdc_date'=>$request->rpdc_date,
                'sales_order_no'=>$request->sales_order_nos[$x],
                'vendorId'=>$request->vendorId, 
                'color_id'=>$request->color_id[$x],
                'size_array'=>$request->size_array[$x],
                'size_qty_array'=>$request->size_qty_array[$x],
                'size_qty_total'=>$request->size_qty_total[$x],
               );

        }
                
     RejectedPcsDeliveryChallanDetailModel::insert($data2);
     return redirect()->route('RejectedPcsDeliveryChallan.index')->with('message', 'Data Updated Succesfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RejectedPcsDeliveryChallanModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        RejectedPcsDeliveryChallanModel::where('rpdc_code', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function Reject_GetOrdarQtyByRow(Request $request)
    {
          $SalesOrders=explode(',',$request->sales_order_no);
          
          $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->whereIn('buyer_purchse_order_master.tr_code',$SalesOrders)->first();
         
          
        //   $query = DB::getQueryLog();
        //     $query = end($query);
        //     dd($query);
          $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::whereIn('tr_code',$SalesOrders)->first();
          
          
          $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
          $sizes='';
          $nos= $request->srno + 1;
          $no= 1;
          foreach ($SizeDetailList as $sz) 
          {
              $sizes=$sizes.'s'.$no.',';
              $no=$no+1;
          }
          $sizes=rtrim($sizes,',');
            // DB::enableQueryLog();  
          $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$BuyerPurchaseOrderDetailList->tr_code."'");
    
    //  $query = DB::getQueryLog();
    //         $query = end($query);
    //         dd($query);
          $html = '';
         
              $no=1;
              
            
              $html .='<tr>';
              $html .='
              <td><input type="text" name="id" value="'.$nos.'" id="id" style="width:50px;height:30px;"></td>';
                    
             
          $html.=' <td>
          
            
            <select name="sales_order_nos[]" class="select2-select"  id="sales_order_nos0" style="width:150px; height:30px;" required>
            <option value="">--Sales Order No--</option>';
    
            foreach($SalesOrders as  $value)
            {
                $html.='<option value="'.$value.'"';
               
                $html.='>'.$value.'</option>';
            }
            
            $html.='</select></td>';
          
            $html.=' <td>
            <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
            
            <select name="color_id[]" class="select2-select"  id="color_id0" style="width:200px; height:30px;" required>
            <option value="">--Select Color--</option>';
            $html.='</select></td>';
        
          
              if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;"  min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
              if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
              $html.='<td>
            <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
            <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
            <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
              
              $html.='<td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>';
    
              
              
               $html.='</tr>';
    
            
             
    
          return response()->json(['html' => $html]);
             
    }
    
    public function Reject_PC_GetOrderQty(Request $request)
    {
       $SalesOrders=explode(',',$request->sales_order_no);
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->whereIn('buyer_purchse_order_master.tr_code',$SalesOrders)->first();
     
      
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::whereIn('tr_code',$SalesOrders)->first();
      
      
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        // DB::enableQueryLog();  
      $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$BuyerPurchaseOrderDetailList->tr_code."'");

//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
      $html = '';
      $html .= '  
      <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
              <thead>
              <tr>
              <th>SrNo</th>
              <th>Sales Order No</th>
              <th>Color</th>';
                 foreach ($SizeDetailList as $sz) 
                  {
                      $html.='<th>'.$sz->size_name.'</th>';
                  }
                  $html.=' 
                  <th>Total Qty</th>
                  <th>Add/Remove</th>
                  </tr>
              </thead>
              <tbody  id="CartonData">';
          $no=1;
          
        
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
                
         
      $html.=' <td>
      
        
        <select name="sales_order_nos[]" class="select2-select"  id="sales_order_nos0" style="width:150px; height:30px;" required>
        <option value="">--Sales Order No--</option>';

        foreach($SalesOrders as  $value)
        {
            $html.='<option value="'.$value.'"';
           
            $html.='>'.$value.'</option>';
        }
        
        $html.='</select></td>';
      
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:200px; height:30px;" required>
        <option value="">--Select Color--</option>';
        $html.='</select></td>';
    
      
          if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;"  name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;"  name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;"  name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;"  name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;"  name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;"  name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;"  name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;"  name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;"  name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;"name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;"  name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;"  name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;"  name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;"  name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
          $html.='<td>
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
          $html.='<td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>';

          
          
           $html.='</tr>';

          $no=$no+1;
        
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }
  
}
