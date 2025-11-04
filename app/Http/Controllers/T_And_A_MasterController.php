<?php
namespace App\Http\Controllers;
use App\Models\T_And_A_MasterModel;
use App\Models\T_And_A_DetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use Illuminate\Http\Request;
use App\Models\DeliveryTermsModel;
use DB;
use Session;

class T_And_A_MasterController extends Controller
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
        ->where('form_id', '152')
        ->first();


        $data = T_And_A_MasterModel::join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','t_and_a_master.tr_code')
        ->join('ledger_master','ledger_master.ac_code', '=', 't_and_a_master.ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 't_and_a_master.userId')
        ->join('fg_master', 'fg_master.fg_id', '=', 't_and_a_master.fg_id')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 't_and_a_master.mainstyle_id', 'left outer')
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 't_and_a_master.substyle_id', 'left outer')
        ->where('t_and_a_master.delflag','=', '0')
        ->get(['t_and_a_master.*','usermaster.username','ledger_master.ac_name','fg_master.fg_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','buyer_purchse_order_master.style_description']);

        return view('T_And_A_Master_List', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        // $SalesOrderList = BuyerPurchaseOrderMasterModel::select('tr_code')->whereIn('job_status_id', [1,2])->whereNotIn('book_price', [100,200])->get();
        $SalesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE og_id !=4 AND tr_code NOT IN(SELECT  tr_code  FROM t_and_a_master)");
        $style_description = DB::table('buyer_purchse_order_master')->select('style_description','shipment_date')->where('delflag','=','0')->get();
        $ledgerlist = DB::table('ledger_master')->get();
        $FGList = DB::table('fg_master')->where('delflag','=', '0')->get();
        $StyleList = BuyerPurchaseOrderMasterModel::select('style_no')->where('delflag','=','0')->get();
        $MainStyleList = DB::table('main_style_master')->where('delflag','=','0')->get();
        $SubStyleList = DB::table('sub_style_master')->where('delflag','=','0')->get();
        $ActList = DB::table('activity_master')->where('delflag','=','0')->get();
        return view('T_And_A_Master',compact('SalesOrderList','ledgerlist','DeliveryTermsList','FGList','StyleList','MainStyleList','SubStyleList','ActList','style_description'));
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
      ->where('type','=','T_and_A')
      ->where('firm_id','=',1)
      ->first();

      $TrNo=$codefetch->code.'-'.$codefetch->tr_no;

      $data1=array(
        't_and_a_id'=>$TrNo,
        'tr_code'=>$request->tr_code,
        'dterm_id'=>$request->dterm_id,
        'Ac_code'=>$request->Ac_code,
        'order_received_date'=>$request->order_received_date,
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'shipment_date'=>$request->shipment_date,
        'userId'=>$request->userId,
        'delflag'=>0
    );

    T_And_A_MasterModel::insert($data1);
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='T_and_A'");

      $act_ids = $request->input('act_id');

      if(count($act_ids)>0)
      {     
        for($x=0; $x<count($act_ids); $x++)
        {
            $data2=array(
                'sr_no'=>$request->id[$x],
                't_and_a_id'=>$TrNo,
                'tr_code'=>$request->tr_code,
                'act_id'=>$request->act_id[$x],
                'target_date'=>$request->target_date[$x],
                'actual_date'=>$request->actual_date[$x]
            );
            T_and_A_DetailModel::insert($data2);
        } 
    }
    return redirect()->route('T_And_A_Master.index')->with('message', 'Added Record Succesfully');

}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
         $T_And_A_MasterList = T_And_A_MasterModel::find($id);
         $SalesOrderList = BuyerPurchaseOrderMasterModel::select('tr_code')->where('delflag','=','0')->get();
         $style_description = DB::table('buyer_purchse_order_master')->select('style_description','shipment_date')->where('delflag','=','0')->get();
         $ledgerlist = DB::table('ledger_master')->get();
         $FGList = DB::table('fg_master')->where('delflag','=', '0')->get();
         $StyleList = BuyerPurchaseOrderMasterModel::select('style_no')->where('delflag','=','0')->get();
         $MainStyleList = DB::table('main_style_master')->where('delflag','=','0')->get();
         $SubStyleList = DB::table('sub_style_master')->where('delflag','=','0')->get();
         $ActList = DB::table('activity_master')->where('delflag','=','0')->get();

         $T_And_A_Detailfetch = T_And_A_DetailModel::where('t_and_a_detail.t_and_a_id','=', $T_And_A_MasterList->t_and_a_id)->orderBy('t_and_a_detail.sr_no','ASC')->get(['t_and_a_detail.*']);
         return view('T_And_A_Master_Edit',compact('SalesOrderList','ledgerlist','DeliveryTermsList','FGList','StyleList','MainStyleList','SubStyleList','ActList','style_description','T_And_A_Detailfetch','T_And_A_MasterList'));
 }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data1=array(
            't_and_a_id'=>$request->t_and_a_id,
            'tr_code'=>$request->tr_code,
             'dterm_id'=>$request->dterm_id,
            'Ac_code'=>$request->Ac_code,
            'order_received_date'=>$request->order_received_date,
            'mainstyle_id'=>$request->mainstyle_id,
            'substyle_id'=>$request->substyle_id,
            'fg_id'=>$request->fg_id,
            'style_no'=>$request->style_no,
            'style_description'=>$request->style_description,
            'shipment_date'=>$request->shipment_date,
            'userId'=>$request->userId,
            'delflag'=>0
        );

        $t_and_a=T_And_A_MasterModel::findOrFail($id);
        $t_and_a->fill($data1)->save();
        DB::table('t_and_a_detail')->where('t_and_a_id', $request->input('t_and_a_id'))->delete();
        $act_id = $request->act_id;
        if(count($act_id)>0)
        {     
            for($x=0; $x<count($act_id); $x++)
            {
                $data2=array(
                    'sr_no'=>$request->id[$x],
                    't_and_a_id'=>$request->t_and_a_id,
                    'tr_code'=>$request->tr_code,
                    'act_id'=>$request->act_id[$x],
                    'target_date'=>$request->target_date[$x],
                    'actual_date'=>$request->actual_date[$x]
                );
            T_and_A_DetailModel::insert($data2);
            } 
        }
        return redirect()->route('T_And_A_Master.index')->with('message', 'Record Updated Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($t_and_a_id)
    {
        DB::table('t_and_a_master')->where('t_and_a_id', $t_and_a_id)->delete();
        DB::table('t_and_a_detail')->where('t_and_a_id', $t_and_a_id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
    }
    public function getSalesOrderDetail(Request $request)
    {

      $tr_code=$request->tr_code;

      $data=BuyerPurchaseOrderMasterModel::select('Ac_code','order_received_date','dterm_id','mainstyle_id','substyle_id','fg_id','style_no','style_description','shipment_date')
      ->where('tr_code','=',$tr_code)
      ->get();
      return $data;
  }

  public function Timeline(Request $request)
  {
        // DB::enableQueryLog();
        $dtermId = "8";
        if($request->dterm_id > 0)
        {
            $data = DB::table('t_and_a_master')
                ->distinct()
                ->select('t_and_a_master.*','usermaster.username','ledger_master.ac_name','fg_master.fg_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','buyer_purchse_order_master.style_description')
                ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','t_and_a_master.tr_code')
                ->join('ledger_master','ledger_master.ac_code', '=', 't_and_a_master.ac_code')
                ->join('usermaster', 'usermaster.userId', '=', 't_and_a_master.userId')
                ->join('fg_master', 'fg_master.fg_id', '=', 't_and_a_master.fg_id')
                ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 't_and_a_master.mainstyle_id', 'left outer')
                ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 't_and_a_master.substyle_id', 'left outer')
                ->where('t_and_a_master.delflag','=', '0')
                ->where('buyer_purchse_order_master.dterm_id','=', $request->dterm_id)
                ->get(['t_and_a_master.*','usermaster.username','ledger_master.ac_name','fg_master.fg_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','buyer_purchse_order_master.style_description','t_and_a_detail.*']);
            // dd(DB::getQueryLog());
            
            $dtermId = $request->dterm_id;
        }
        else
        {
              $data = DB::table('t_and_a_master')
                ->distinct()
                ->select('t_and_a_master.*','usermaster.username','ledger_master.ac_name','fg_master.fg_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','buyer_purchse_order_master.style_description')
                ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','t_and_a_master.tr_code')
                ->join('ledger_master','ledger_master.ac_code', '=', 't_and_a_master.ac_code')
                ->join('usermaster', 'usermaster.userId', '=', 't_and_a_master.userId')
                ->join('fg_master', 'fg_master.fg_id', '=', 't_and_a_master.fg_id')
                ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 't_and_a_master.mainstyle_id', 'left outer')
                ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 't_and_a_master.substyle_id', 'left outer')
                ->where('t_and_a_master.delflag','=', '0')
                ->where('buyer_purchse_order_master.dterm_id','=', 8)
                ->get(['t_and_a_master.*','usermaster.username','ledger_master.ac_name','fg_master.fg_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','buyer_purchse_order_master.style_description','t_and_a_detail.*']);
        }
        $id = $data->pluck('t_and_a_id');
        // DB::enableQueryLog();
        $details = DB::table('t_and_a_templatedetail')->distinct()->select('activity_master.act_name')
                        ->join('activity_master','activity_master.act_id','=','t_and_a_templatedetail.act_id')
                        ->join('t_and_a_detail','t_and_a_detail.act_id','=','t_and_a_templatedetail.act_id')
                        ->whereIn('t_and_a_detail.t_and_a_id', $id)
                        ->orderBy('t_and_a_templatedetail.sr_no','ASC')
                        ->get();
      // dd(DB::getQueryLog());
        $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        
        return view('Timeline', compact('data','details','DeliveryTermsList','dtermId'));
  }
  
  
  
  
  
  
    public function GetTNAMasterData(Request $request)
    { 
    
    
    $dterm_id= $request->input('dterm_id');
    $sales_order_no= $request->input('sales_order_no');
    $target_date_21 = "";
    $target_date_12 = "";
    $target_date_15 = "";
    $target_date_10 = "";
    $target_date_11 = "";
    $target_date_9 = "";
    $target_date_5 = "";
    $bom_code = "";
   
  
//   DB::enableQueryLog();
 
  $TimeandActionPlanList = DB::table('t_and_a_templatemaster')
  ->select('t_and_a_templatemaster.t_and_a_tid', 't_and_a_templatemaster.dterm_id','t_and_a_templatedetail.act_id', 'days', 'dact_id')
  ->leftJoin('t_and_a_templatedetail', 't_and_a_templatedetail.t_and_a_tid', '=', 't_and_a_templatemaster.t_and_a_tid')
  ->leftJoin('delivery_terms_master', 'delivery_terms_master.dterm_id', '=', 't_and_a_templatemaster.dterm_id')
  ->leftJoin('activity_master', 'activity_master.act_id', '=', 't_and_a_templatedetail.act_id')
  ->where('t_and_a_templatemaster.dterm_id','=',$dterm_id)  
  ->orderBy('activity_master.position', 'asc')
  ->get();
  
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
  
   
        $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        $ActList = DB::table('activity_master')->where('delflag','=','0')->get();
      $OrderDates=DB::select("select order_received_date, tr_date, plan_cut_date from buyer_purchse_order_master where tr_code='".$sales_order_no."'");
    
     $html = '';
     
     $target_date=date('Y-m-d');
     $actual_date=date('Y-m-d');
     $no=1;
   foreach($TimeandActionPlanList as  $TNA)  
{  
    
    
    
    $html .='<tr class="thisRow">';
       
    $html .='
    <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
    $html.=' 
     
     <td> <select name="act_id[]"  id="act_id'.$no.'" style="width:250px; height:30px;" required disabled>
    <option value="">--Activities--</option>';
    foreach($ActList as  $rowact)
    {
        $html.='<option value="'.$rowact->act_id.'"';
    
        $rowact->act_id == $TNA->act_id ? $html.='selected="selected"' : ''; 
        
        $html.='>'.$rowact->act_name.'</option>';
    }
     $html.='</select></td>';
     
    
     if($TNA->act_id==1 || $TNA->act_id==2 || $TNA->act_id==3 || $TNA->act_id==4 || $TNA->act_id==5 || $TNA->act_id==7 ||  $TNA->act_id==8)
     {
        $target_date=date('Y-m-d', strtotime($OrderDates[0]->order_received_date. ' + '.$TNA->days.' days'));
        $actual_date='';
         
        if($TNA->act_id==1){ $actual_date=$OrderDates[0]->order_received_date;}
        if($TNA->act_id==2){ $actual_date='';}
        if($TNA->act_id==3){ $actual_date=$OrderDates[0]->tr_date;} 
           
        if($TNA->act_id==4)
        { 
            $CostingDate=DB::select("select soc_date  from sales_order_costing_master where sales_order_no='".$sales_order_no."'");
            if(isset($CostingDate[0]->soc_date)){ $actual_date=$CostingDate[0]->soc_date;} else{$actual_date='';}
        } 
        
        if($TNA->act_id==5 || $TNA->act_id==8){ 
            $BOMDate=DB::select("select bom_code, bom_date  from bom_master where sales_order_no='".$sales_order_no."'");
            $actual_date=isset($BOMDate[0]->bom_date)? $BOMDate[0]->bom_date : '';
            if(isset($BOMDate[0]->bom_date)){ $actual_date=$BOMDate[0]->bom_date; $bom_code=$BOMDate[0]->bom_code;  } else{ $actual_date='';}
         }
           
        if($TNA->act_id==5){ $target_date_5=$target_date;}
           
     }
     elseif( $TNA->act_id==6 )
     {
          $target_date=date('Y-m-d', strtotime($target_date_5. ' + '.$TNA->days.' days'));
          $actual_date='';
     }
      elseif($TNA->act_id==9 || $TNA->act_id==11 || $TNA->act_id==15 || $TNA->act_id==16 || $TNA->act_id==17 || $TNA->act_id==19 || $TNA->act_id==20 || $TNA->act_id==21)
        {
            $target_date=date('Y-m-d', strtotime($OrderDates[0]->plan_cut_date. ' + '.$TNA->days.' days'));
            $actual_date=''; 
            if($TNA->act_id==9){$target_date=date('Y-m-d', strtotime($OrderDates[0]->plan_cut_date. ' '.$TNA->days.' days')); $target_date_9=$target_date;  $actual_date='';}
            if($TNA->act_id==11){$target_date=date('Y-m-d', strtotime($OrderDates[0]->plan_cut_date. ' '.$TNA->days.' days')); $target_date_11=$target_date; $actual_date='';}
            if($TNA->act_id==15){$target_date=date('Y-m-d', strtotime($OrderDates[0]->plan_cut_date. ' '.$TNA->days.' days')); $target_date_15=$target_date; $actual_date='';}
            if($TNA->act_id==16 || $TNA->act_id==17)
            {  
               $target_date=date('Y-m-d', strtotime($OrderDates[0]->plan_cut_date. ' '.$TNA->days.' days')); 
               $PODetailData=DB::select("select ifnull(sum(item_qty),0) as po_qty from purchaseorder_detail where sales_order_no='".$sales_order_no."' and bom_type!=1");
             if(isset($POData[0]->po_code))
           {
               $InQTY=DB::select("select ifnull(sum(item_qty),0) as in_qty from trimsInwardDetail where po_code='".$POData[0]->po_code."'");
               if($PODetailData[0]->po_qty <= $InQTY[0]->in_qty)
                { 
                  $EndBOM=DB::select("select distinct max(trimDate) as  end_date from trimsInwardMaster where trimsInwardMaster.po_code='".$POData[0]->po_code."'");
                  if(isset($EndBOM[0]->end_date)){$actual_date=$EndBOM[0]->end_date; } else {$actual_date='';}
                }
                else
                {
                   $actual_date='';
                }
           }
           else
           {
                 $actual_date='';
           }
                
                
            }
            if($TNA->act_id==19){$target_date=date('Y-m-d', strtotime($OrderDates[0]->plan_cut_date. ' '.$TNA->days.' days'));  $actual_date='';  }
            if($TNA->act_id==21){$target_date_21=$target_date; $actual_date='';} 
        }
        elseif($TNA->act_id==10)
        {
          $target_date=date('Y-m-d', strtotime($target_date_9. ' + '.$TNA->days.' days'));
          $actual_date='';
          if($TNA->act_id==10){$target_date_10=$target_date;}
        }
        elseif($TNA->act_id==12)
        {
          $target_date=date('Y-m-d', strtotime($target_date_11. ' + '.$TNA->days.' days'));
          $actual_date='';
          if($TNA->act_id==12){$target_date_12=$target_date;}
        }
         elseif($TNA->act_id==13)
        {
            $target_date=date('Y-m-d', strtotime($target_date_10. ' + '.$TNA->days.' days'));
           
            $POData=DB::select("select pur_code as po_code from purchase_order where FIND_IN_SET('".$bom_code."',purchase_order.bom_code)");
          
            if(isset($POData[0]->po_code))
           {
               DB::enableQueryLog();
               $StartBOM=DB::select("select in_date from inward_master where po_code='".$POData[0]->po_code."'");
              
               $po_code=$POData[0]->po_code;
               $actual_date=isset($StartBOM[0]->in_date) ? $StartBOM[0]->in_date : '';
           }
           else
           {
               $actual_date='';
           }
               
         }
         elseif($TNA->act_id==14)
         {
           
            $target_date=date('Y-m-d', strtotime($target_date_12. ' + '.$TNA->days.' days'));
             if(isset($POData[0]->po_code))
           {
                $PODetailData=DB::select("select sum(item_qty) as po_qty from purchaseorder_detail where sales_order_no='".$sales_order_no."' and bom_type=1");
                $InQTY=DB::select("select ifnull(sum(meter),0) as in_qty from inward_details where po_code='".$POData[0]->po_code."'");
               
                  if($PODetailData[0]->po_qty <= $InQTY[0]->in_qty)
                  { 
                      $EndBOM=DB::select("select distinct max(in_date) end_date from inward_master where po_code='".$POData[0]->po_code."'");
                      $actual_date=$EndBOM[0]->end_date;
                  }
                  else
                  {
                       $actual_date='';
                  }
          
          }
          else
          {
              $actual_date='';
          }
          
                 
     }
      elseif($TNA->act_id==18)
     {
          $target_date=date('Y-m-d', strtotime($target_date_15. ' + '.$TNA->days.' days'));
          $actual_date='';
           
     }
      elseif($TNA->act_id==22)
     {
          $target_date=date('Y-m-d', strtotime($target_date_21. ' + '.$TNA->days.' days'));
          $actual_date='';
           
     }
     
   
     $html.='<td><input type="date" name="target_date[]" value="'.$target_date.'" id="target_date" style="width:100px; height:30px;"   readOnly  /></td>'; 
     
     if($actual_date!='')
     {$html.='<td><input type="date" name="actual_date[]" value="'.$actual_date.'"  id="actual_date" style="width:100px; height:30px;"   /></td> ';}
     else{$html.='<td><input type="date" name="actual_date[]"   id="actual_date" style="width:100px; height:30px;"   /></td> ';}
     
    //  $html .=' <td>
    //                 <input type="button" onclick="insertcone(); " class="btn btn-warning pull-left" value="+">
    //                 <input type="button" class="btn btn-danger pull-right" onclick="deleteRowcone(this);" value="X" >
    //             </td> </tr>';

    
    $no=$no+1;
}      
    return response()->json(['html' => $html]);
     
    }  
     
     
  
  
  
  
}
