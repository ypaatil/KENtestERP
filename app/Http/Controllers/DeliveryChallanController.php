<?php

namespace App\Http\Controllers;

use App\Models\DeliveryChallanMasterModel;
use App\Models\DeliveryChallanDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class DeliveryChallanController extends Controller
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
        ->where('form_id', '199')
        ->first();
       // echo  Session::get('user_type');exit;
        // DB::enableQueryLog();
        if(Session::get('user_type') == 1)
        {
            $DeliveryChallanList = DeliveryChallanMasterModel::leftjoin('usermaster', 'usermaster.userId', '=', 'delivery_challan_master.userId')
            ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'delivery_challan_master.ac_code')
            ->leftjoin('department_master', 'department_master.dept_id', '=', 'delivery_challan_master.dept_id')
            ->where('delivery_challan_master.delflag','=', '0')
            ->orderBy('delivery_challan_master.dc_id', 'desc')
            ->get(['delivery_challan_master.*','usermaster.username', 'ledger_master.ac_name','department_master.dept_name']);
        }
        else
        {
            
            $DeliveryChallanList = DeliveryChallanMasterModel::leftjoin('usermaster', 'usermaster.userId', '=', 'delivery_challan_master.userId')
            ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'delivery_challan_master.ac_code')
            ->leftjoin('department_master', 'department_master.dept_id', '=', 'delivery_challan_master.dept_id')
            ->where('delivery_challan_master.delflag','=', '0')
            ->where('delivery_challan_master.userId','=', Session::get('userId'))
            ->orderBy('delivery_challan_master.dc_id', 'desc')
            ->get(['delivery_challan_master.*','usermaster.username', 'ledger_master.ac_name','department_master.dept_name']);
            
        }
        // dd(DB::getQueryLog());

        return view('DeliveryChallanMasterList', compact('DeliveryChallanList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Ledger = DB::table('ledger_master')->whereIn('ledger_master.bt_id', [1,2,4])->where('ledger_master.delflag','=', '0')->get();

        $TaxListing=DB::table('tax_type_master')->get();

        $departmentlist=DB::table('department_master')->select('dept_id','dept_name')->where('delflag',0)->get();   

        $unitlist=DB::table('unit_master')->select('unit_id','unit_name')->where('delflag',0)->get();
        
        $salesOrderList=DB::table('buyer_purchse_order_master')->select('tr_code')->where('delflag',0)->get();
        
        $item_category_list=DB::table('item_category')->select('cat_id','cat_name')->where('delflag',0)->get();

        // DB::enableQueryLog();
        $IssueList = DB::select("SELECT dc.issue_no FROM delivery_challan_master as dc WHERE dc.dc_case_id = 1 and dc.issue_case_id = 1 and dc.total_qty!= (select ifnull(sum(delivery_challan_master.total_qty),0) FROM delivery_challan_master WHERE delivery_challan_master.return_issue_no = dc.issue_no and delivery_challan_master.issue_case_id = 2 )");
 
        $WashTypeList = DB::table('wash_type_master')->select('*')->where('delflag',0)->get();
        
        // dd(DB::getQueryLog());

        return view('DeliveryChallanMaster',compact('Ledger','departmentlist','unitlist','IssueList','TaxListing','item_category_list', 'salesOrderList', 'WashTypeList'));
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
            'dc_case_id' => 'required|in:1,2',
            'issue_case_id'=>'required',
            'issue_date'=>'required',
            'product_type'=>'required',
            'reciever_type'=>'required',
            'dept_id'=>'required',
            'tax_type_id'=>'required',
            'to_location'=>'required',
            'total_qty'=>'required',
            'GrossAmount'=>'required',
            'GstAmount'=>'required',
            'NetAmount'=>'required',
            'userId'=>'required',
        ]);

        // DB::enableQueryLog();

        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'issue_no',c_code,code"))
        ->where('c_name','=','C1')
        ->where('type','=','Delivery_Challan')
        ->where('firm_id','=',1)
        ->first();

        // dd(DB::getQueryLog());

        $issue_no = $codefetch->code.'-'.$codefetch->issue_no;
        $return_date = "";
        if($request->return_date != "")
        { 
            $return_date = date('Y-m-d',strtotime($request->return_date));
        }
        $data1=array(
            'issue_no'=>$issue_no,
            'return_issue_no'=>$request->return_issue_no ? $request->return_issue_no : '',
            'dc_case_id'=>$request->dc_case_id,
            'issue_case_id'=>$request->issue_case_id,
            'issue_date'=>isset($request->issue_date) ? $request->issue_date : '',
            'return_date'=> $return_date,
            'product_type'=>$request->product_type,
            'sales_order_no'=>$request->sales_order_no,
            'reciever_type'=>$request->reciever_type,
            'ac_code'=>$request->ac_code  ? $request->ac_code : 0,
            'otherBuyerorVendor'=>$request->otherBuyerorVendor ? $request->otherBuyerorVendor : '',
            'sent_through'=>$request->sent_through,
            'dept_id'=>$request->dept_id,
            'to_location'=>$request->to_location,
            'tax_type_id'=>$request->tax_type_id,
            'WashTypeId'=>$request->WashTypeId,
            'total_qty'=>$request->total_qty,
            'GrossAmount'=>$request->GrossAmount,
            'GstAmount'=>$request->GstAmount,
            'NetAmount'=>$request->NetAmount,
            'narration'=>$request->narration,
            'delflag'=>'0',
            'userId'=>$request->userId,
        );

        DeliveryChallanMasterModel::insert($data1);

        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='Delivery_Challan'");


        $unit_id = $request->input('unit_id');
        if(count($unit_id)>0)
        {
            for($x=0; $x<count($unit_id); $x++) 
            {

                if($request->return_quantity!="") 
                {
                    $return_quantity = $request->return_quantity[$x];
                }
                else
                {
                    $return_quantity = 0;
                }
                $data2=array(
                    'issue_no'=>$issue_no,
                    'return_issue_no'=>$request->return_issue_no ? $request->return_issue_no : '',
                    'item_description'=>$request->item_description[$x],
                    'unit_id'=>$request->unit_id[$x],
                    'gst_per'=>$request->gst_per[$x],
                    'quantity'=>$request->quantity[$x],
                    'return_quantity'=>$return_quantity,
                    'rate'=>$request->rate[$x],
                    'amount'=>$request->amount[$x],
                    'gst_amt'=>$request->gst_amt[$x],
                    'total_amount'=>$request->total_amount[$x],
                    'remark'=>$request->remark[$x],
                );
                
                DeliveryChallanDetailModel::insert($data2);
            }
        }

        return redirect()->route('DeliveryChallan.index')->with('message', 'New Record Saved Succesfully..!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function show($issue_no)
    {
        $DeliveryChallanMasterList = DeliveryChallanMasterModel::find($issue_no);
        
        // DB::enableQueryLog();     
        $DeliveryChallanMasterData =  DB::table('delivery_challan_master')
        ->select('delivery_challan_master.*','ledger_master.gst_no','ledger_master.ac_name','LM1.ac_short_name as vendorName',
          'LM1.address as vendorAddress','ledger_master.ac_code', 'department_master.dept_id','department_master.dept_name')
        ->leftjoin('usermaster', 'usermaster.userId', '=', 'delivery_challan_master.userId')
        ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'delivery_challan_master.ac_code')
        ->leftjoin('ledger_master as LM1', 'LM1.ac_code', '=', 'usermaster.vendorId')
        ->leftjoin('department_master', 'department_master.dept_id', '=', 'delivery_challan_master.dept_id')
        ->where('delivery_challan_master.issue_no','=', $DeliveryChallanMasterList->issue_no)
        ->first();

         // dd(DB::getQueryLog());
        $FirmDetail = DB::table('firm_master')->select('firm_master.*','state_master.state_name')
        ->leftjoin('state_master','state_master.state_id','=','firm_master.state_id')
        ->where('firm_master.delflag','=', '0')->first();
        
        $DeliveryChallanDetailList = DB::table('delivery_challan_detail')
        ->select('delivery_challan_detail.*','unit_master.unit_name')
        ->leftjoin('unit_master', 'unit_master.unit_id', '=' , 'delivery_challan_detail.unit_id')
        ->where('issue_no','=', $DeliveryChallanMasterList->issue_no)->get();

        return view('DeliveryChallanPrint', compact('DeliveryChallanMasterList','DeliveryChallanMasterData','FirmDetail',
            'DeliveryChallanDetailList'));  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Ledger = DB::table('ledger_master')->whereIn('ledger_master.bt_id', [1,2,4])->where('ledger_master.delflag','=', '0')->get();

        $TaxListing=DB::table('tax_type_master')->get();

        $departmentlist=DB::table('department_master')->select('dept_id','dept_name')->where('delflag',0)->get();   

        $unitlist=DB::table('unit_master')->select('unit_id','unit_name')->where('delflag',0)->get();

        $item_category_list=DB::table('item_category')->select('cat_id','cat_name')->where('delflag',0)->get();
        
        $DeliveryChallanMasterList = DeliveryChallanMasterModel::find($id);

        // DB::enableQueryLog();

        $IssueList = DB::select("SELECT dc.issue_no FROM delivery_challan_master as dc WHERE dc.dc_case_id = 1 and dc.issue_case_id = 1 and dc.total_qty!= (select ifnull(sum(delivery_challan_master.total_qty),0) FROM delivery_challan_master WHERE delivery_challan_master.return_issue_no = dc.issue_no and delivery_challan_master.issue_case_id = 2 ) UNION select return_issue_no from delivery_challan_master where issue_no ='".$id."'");

        // dd(DB::getQueryLog());

        $DeliveryChallanDetailList = DeliveryChallanDetailModel::leftjoin('unit_master','unit_master.unit_id', '=', 'delivery_challan_detail.unit_id')

        ->where('delivery_challan_detail.issue_no','=', $DeliveryChallanMasterList->issue_no)->get(['delivery_challan_detail.*','unit_master.unit_name']);
        
        $salesOrderList=DB::table('buyer_purchse_order_master')->select('tr_code')->where('delflag',0)->get();
        
        $WashTypeList = DB::table('wash_type_master')->select('*')->where('delflag',0)->get();

        return view('DeliveryChallanMasterEdit',compact('Ledger','TaxListing','departmentlist','unitlist','IssueList','DeliveryChallanMasterList','DeliveryChallanDetailList','item_category_list', 'salesOrderList','WashTypeList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
 
        $this->validate($request, [
            'issue_no'=>'required',
            'dc_case_id' => 'required|in:1,2',
            'issue_case_id'=>'required',
            'issue_date'=>'required',
            'product_type'=>'required',
            'reciever_type'=>'required',
            'dept_id'=>'required',
            'to_location'=>'required',
            'tax_type_id'=>'required',
            'total_qty'=>'required',
            'GrossAmount'=>'required',
            'GstAmount'=>'required',
            'NetAmount'=>'required',
            'userId'=>'required',
        ]);
        $return_date = "";
        if($request->return_date != "")
        { 
            $return_date = date('Y-m-d',strtotime($request->return_date));
        }
        $data1=array(
            'issue_no'=>$request->issue_no,
            'return_issue_no'=>$request->return_issue_no ? $request->return_issue_no : '',
            'dc_case_id'=>$request->dc_case_id,
            'issue_case_id'=>$request->issue_case_id,
            'issue_date'=>isset($request->issue_date) ? $request->issue_date : '',
            'return_date'=> $return_date,
            'product_type'=>$request->product_type,
            'reciever_type'=>$request->reciever_type,
            'sales_order_no'=>$request->sales_order_no,
            'ac_code'=>$request->ac_code  ? $request->ac_code : 0,
            'otherBuyerorVendor'=>$request->otherBuyerorVendor ? $request->otherBuyerorVendor : '',
            'sent_through'=>$request->sent_through,
            'dept_id'=>$request->dept_id,
            'to_location'=>$request->to_location,
            'tax_type_id'=>$request->tax_type_id,
            'WashTypeId'=>$request->WashTypeId,
            'total_qty'=>$request->total_qty,
            'GrossAmount'=>$request->GrossAmount,
            'GstAmount'=>$request->GstAmount,
            'NetAmount'=>$request->NetAmount,
            'narration'=>$request->narration,
            'delflag'=>'0',
            'userId'=>$request->userId,
            'created_at'=>$request->created_at,

        );

        $DeliveryChallanMasterList = DeliveryChallanMasterModel::findOrFail($request->input('issue_no'));  
        $DeliveryChallanMasterList->fill($data1)->save();

        DB::table('delivery_challan_detail')->where('issue_no', $request->input('issue_no'))->delete();

        $unit_id = $request->input('unit_id');
        if(count($unit_id)>0)
        {
            for($x=0; $x<count($unit_id); $x++) {

                if($request->return_quantity!="") 
                {
                    $return_quantity = $request->return_quantity[$x] ? $request->return_quantity[$x] : 0;
                }
                else{
                    $return_quantity = 0;
                }
                $data2=array(
                    'issue_no'=>$request->issue_no,
                    'return_issue_no'=>$request->return_issue_no ? $request->return_issue_no : '',
                    'item_description'=>$request->item_description[$x],
                    'unit_id'=>$request->unit_id[$x],
                    'gst_per'=>$request->gst_per[$x],
                    'quantity'=>$request->quantity[$x],
                    'return_quantity'=>$return_quantity,
                    'rate'=>$request->rate[$x],
                    'amount'=>$request->amount[$x],
                    'gst_amt'=>$request->gst_amt[$x],
                    'total_amount'=>$request->total_amount[$x],
                    'remark'=>$request->remark[$x],
                );
                
                DeliveryChallanDetailModel::insert($data2);
            }
        }

        return redirect()->route('DeliveryChallan.index')->with('message', 'Update Record Succesfully..!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('delivery_challan_master')->where('issue_no',$id)->delete();
        DB::table('delivery_challan_detail')->where('issue_no',$id)->delete();

        Session::flash('delete', 'Deleted record successfully'); 
    }
    public function getAddressForDC(Request $request)
    {
        $Address = DB::select('select address from ledger_master where ac_code='.$request->ac_code.'');
        return json_encode($Address);
    }

    public function getDeliveryChallan(Request $request)
    {

        // DB::enableQueryLog();

        $DeliveryChallan = DB::select("select *, ac_name, dept_name from delivery_challan_master left join ledger_master on ledger_master.ac_code = delivery_challan_master.ac_code left join tax_type_master on tax_type_master.tax_type_id = delivery_challan_master.tax_type_id left join department_master on department_master.dept_id = delivery_challan_master.dept_id where issue_no='".$request->issue_no."' ");

        // dd(DB::getQueryLog());

        return json_encode($DeliveryChallan);
    }

    public function getDeliveryChallanDetailsData(Request $request)
    {
         //DB::enableQueryLog();

        $detail = DeliveryChallanDetailModel::leftjoin('unit_master', 'unit_master.unit_id', '=', 'delivery_challan_detail.unit_id')    
        ->where('delivery_challan_detail.issue_no','=',$request->issue_no)
        ->get(['delivery_challan_detail.*',DB::raw("(select sum(return_quantity) from delivery_challan_detail as dc where dc.return_issue_no='".$request->issue_no."' and dc.item_description= delivery_challan_detail.item_description) as received_qty"),'unit_master.unit_name']);

        // dd(DB::getQueryLog());

        $unitlist=DB::table('unit_master')->select('unit_id','unit_name')->where('delflag',0)->get();
        

        if($request->issue_no != '')
        {
            //
            $html='';

            $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
            <thead>
            <tr>
            <th>Sr No</th>
            <th>Item Description</th>
            <th>Unit</th>
            <th>GST %</th>
            <th>Quantity</th>
            <th>Return Quantity</th>
            <th>Recieved Quantity</th>
            <th>Base Rate</th>
            <th>Amount</th>
            <th>GST AMT</th>
            <th>TAMOUNT</th>
            <th>Remark</th>
            <th>Add/Remove</th>
            </tr>
            </thead>
            <tbody>';
            $no=1;

            foreach ($detail as $value) 
            {
                $html .='<tr class="delivery">';

                $html .='
                <td><input type="text" class="form-control" name="id[]" value="'.$no.'" id="id" style="width:50px;" readonly/></td>

                <td><input type="text" name="item_description[]" id="item_description"  tabindex="17" class="form-control" value="'.$value->item_description.'" required="required" style="width:120px;" readonly></td>

                <td><select class="form-control select2" data-placeholder="Choose one" name="unit_id[]" style="width:140px;" tabindex="18" id="unit_id" required data-parsley-errors-container="#field1" disabled>
                <option value="">--- Select Unit ---</option>';
                foreach($unitlist as  $rowunit)
                {
                    $html.='<option value="'.$rowunit->unit_id.'"';

                    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 

                    $html.='>'.$rowunit->unit_name.'</option>';

                }
                $html.='</select></td>';

                // $html.='<td>
                // <select name="tax_type_id[]" class="form-select  " style="width:140px;" id="tax_type_id">
                // <option value="">--Tax Type--</option>';
                // foreach($TaxListing as  $row)
                // {
                //     $html.='<option value="'.$row->tax_type_id.'"';
                //     $row->tax_type_id == $value->tax_type_id ? $html.='selected="selected"' : ''; 
                //     $html.='>'.$row->tax_type_name.'</option>';
                // }
                // $html.='</select>
                // </td>';
                $html.='
                <td>
                    <select name="gst_per[]" class="form-control" style="width:140px;"  id="gst_per" onchange="setGST(this);">
                        <option value="">--GST %--</option>
                        <option value="5"'; 
                        5 == $value->gst_per ? $html.='selected="selected"' : '';
                        $html.='>5 %</option>';
                        $html.='<option value="12"';
                        12 == $value->gst_per ? $html.='selected="selected"' : '' ;
                        $html.='>12 %</option>';
                        $html.='<option value="18"';
                        18 == $value->gst_per ? $html.='selected="selected"' : '' ;
                        $html.='>18 %</option>';
                        $html.='<option value="28"';
                        28 ==$value->gst_per ? $html.='selected="selected"' : '' ;
                        $html.='>28 %</option>';
                $html.='</select>
                </td>';

                $html.='
                <td><input type="number" name="quantity[]" id="quantity1" class="form-control QTY" tabindex="19" step="any" onkeyup="mycalc();" value="'.$value->quantity.'" required="required" style="width:120px;" readonly></td>
                <td><input type="number" step="any" name="return_quantity[]" id="return_quantity" class="form-control RTQTY" onkeyup="mycalc();" min="0" max="'.($value->quantity-$value->received_qty).'" tabindex="19"  required="required" style="width:120px;"></td>
                <td><input type="text" readOnly onkeyup="mycalc();" name="recived_quantity[]" id="recived_quantity" value="'.$value->received_qty.'" class="form-control RCQTY" tabindex="19" step="any" style="width:120px;"></td>
                <td><input type="number" name="rate[]" onkeyup="mycalc();" id="rate" class="form-control" tabindex="20"  step="any" required="required" value="'.$value->rate.'" style="width:120px;" readonly></td>
                <td><input type="number" step="any" class="form-control AMT"  name="amount[]" onkeyup="mycalc();" value="'.$value->amount.'" id="amount1" style="width:80px;" required readonly/><input type="hidden"   name="cgst_per[]" onkeyup="mycalc();" value="0" id="cgst_per" style="width:80px;" required/><input type="hidden"   name="cgst_amt[]" onkeyup="mycalc();" value="0" id="cgst_amt1" style="width:80px;" required/><input type="hidden"   name="sgst_per[]" onkeyup="mycalc();" value="0" id="sgst_per" style="width:80px;" required/><input type="hidden"   name="sgst_amt[]" onkeyup="mycalc();" value="0" id="sgst_amt1" style="width:80px;" required/><input type="hidden"   name="igst_per[]" onkeyup="mycalc();" value="0" id="igst_per" style="width:80px;" required/><input type="hidden"   name="igst_amt[]" onkeyup="mycalc();" value="0" id="igst_amt1" style="width:80px;" required/>
                </td>
                <td>
                <input type="number" step="any" readOnly class="form-control GST" name="gst_amt[]"  value="'.$value->gst_amt.'"  id="gst_amt1" style="width:80px;" required />
                </td>

                <td><input type="number"  step="any"  class="form-control TAMT"  name="total_amount[]" onkeyup="mycalc();" value="'.$value->total_amount.'" id="total_amount1" style="width:80px;" required readonly/></td>

                <td><input type="text" name="remark[]" id="remark" class="form-control" tabindex="21" value="'.$value->remark.'" style="width:120px;" readonly></td>

                <td><input type="button" style="width:40px; margin-right: 5px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left " onclick="deleteRow(this);" value="X" ></td>
                ';

                $html .='</tr>';

                $no=$no+1;

            }

            $html .='</tbody>

            <input type="number" value="'.count($detail).'" name="cnt" id="cnt" readonly="" hidden="true"  />
            </table>';
        }
        else
        {

            $html='';

            $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
            <thead>
            <tr>
            <th>Sr No</th>
            <th>Item Description</th>
            <th>Unit</th>
            <th>GST %</th>
            <th>Quantity</th>
            <th>Base Rate</th>
            <th>Amount</th>
            <th>GST AMT</th>
            <th>TAMOUNT</th>
            <th>Remark</th>
            <th>Add/Remove</th>
            </tr>
            </thead>
            <tbody>';
            $no=1;

            $html .='<tr>';

            $html .='
            <td><input type="text" class="form-control" name="id[]" value="'.$no.'" id="id" style="width:50px;" readonly/></td>

            <td><input type="text" name="item_description[]" id="item_description"  tabindex="17" class="form-control"  required="required" style="width:120px;"></td>

            <td><select class="form-control select2" data-placeholder="Choose one" name="unit_id[]" style="width:140px;" tabindex="18" id="unit_id" required data-parsley-errors-container="#field1">
            <option value="">--- Select Unit ---</option>';
            foreach($unitlist as  $rowunit)
            {
                $html.='<option value="'.$rowunit->unit_id.'"'; 

                $html.='>'.$rowunit->unit_name.'</option>';

            }
            $html.='</select></td>';
            $html.='</select></td>';

            // $html.='<td>
            // <select name="tax_type_id[]" class="form-select  " style="width:140px;" id="tax_type_id">
            // <option value="">--Tax Type--</option>';
            // foreach($TaxListing as  $row)
            // {
            //     $html.='<option value="'.$row->tax_type_id.'">'.$row->tax_type_name.'</option>';
            // }
            // $html.='</select>
            // </td>';
            $html.='
                <td>
                    <select name="gst_per[]" class="form-control" style="width:140px;"  id="gst_per" onchange="setGST(this);">
                        <option value="">--GST %--</option>
                        <option value="5">5 %</option>
                       <option value="12">12 %</option>
                       <option value="18">18 %</option>
                       <option value="28">28 %</option>
                    </select>
                </td>';

            $html.='
            <td><input type="number"  name="quantity[]" id="quantity1" onkeyup="mycalc();" class="form-control QTY" tabindex="19" step="any"  required="required" style="width:120px;"></td>

            <td><input type="number" name="rate[]" id="rate" onkeyup="mycalc();" class="form-control" tabindex="20"  step="any" required="required"  style="width:120px;"></td>
            <td><input type="number" step="any" class="form-control AMT"  name="amount[]" onkeyup="mycalc();" value="0" id="amount1" style="width:80px;" required readonly/><input type="hidden"   name="cgst_per[]" onkeyup="mycalc();" value="0" id="cgst_per" style="width:80px;" required/><input type="hidden"   name="cgst_amt[]" onkeyup="mycalc();" value="0" id="cgst_amt1" style="width:80px;" required/><input type="hidden"   name="sgst_per[]" onkeyup="mycalc();" value="0" id="sgst_per" style="width:80px;" required/><input type="hidden"   name="sgst_amt[]" onkeyup="mycalc();" value="0" id="sgst_amt1" style="width:80px;" required/><input type="hidden"   name="igst_per[]" onkeyup="mycalc();" value="0" id="igst_per" style="width:80px;" required/><input type="hidden"   name="igst_amt[]" onkeyup="mycalc();" value="0" id="igst_amt1" style="width:80px;" required/>
            </td>
            <td>
            <input type="number" step="any" readOnly class="form-control GST" name="gst_amt[]"   value="0" id="gst_amt1" style="width:80px;" required />
            </td>

            <td><input type="number" step="any"  class="form-control TAMT"  name="total_amount[]" onkeyup="mycalc();"  id="total_amount1" style="width:80px;" required readonly/></td>

            <td><input type="text" name="remark[]" id="remark" class="form-control" tabindex="21" style="width:120px;"></td>

            <td><input type="button" onclick="insertRow(this);" style="width:40px; margin-right: 5px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left " onclick="deleteRow(this);" value="X" ></td>
            ';

            $html .='</tr>';

            $no=$no+1;

            $html .='</tbody>

            <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
            </table>';

        }

        return response()->json(['html' => $html]);

    }
    
    public function GetVendorBuyerWiseData(Request $request)
    {
        
        $legerList = DB::table('ledger_master')->select('ac_code','ac_name')->where('bt_id','=',$request->type)->where('delflag','=',0)->get();
        
        $html='<option value="0">--Select--</option>';

        foreach($legerList as  $row)
        {
            $html.='<option value="'.$row->ac_code.'">'.$row->ac_name.'</option>';
        }

        return response()->json(['html' => $html]);

    }
    

    public function validations(){
        return view('JSFormValidation');
    } 
        
        
    public function Get_Gate_Pass1()
    {   
        $DeliveryChallanMasterData = DB::table('delivery_challan_master')->select('delivery_challan_master.*')->get();
 
        return view('Get_Gate_Pass1', compact('DeliveryChallanMasterData'));  
    }
    
    public function rptGatePass1(Request $request)
    { 
        //DB::enableQueryLog();
         $DeliveryChallanMasterData = DB::table('delivery_challan_detail')
                ->select('delivery_challan_master.*','delivery_challan_detail.*','ledger_master.ac_name','usermaster.username',
                'material_type_master.material_type_name','department_master.dept_name','unit_master.unit_name')
                ->join('delivery_challan_master','delivery_challan_master.issue_no','=','delivery_challan_detail.issue_no')
                ->leftjoin('ledger_master','ledger_master.ac_code','=','delivery_challan_master.ac_code')
                ->leftjoin('material_type_master','material_type_master.material_type_id','=','delivery_challan_master.product_type')
                ->leftjoin('department_master','department_master.dept_id','=','delivery_challan_master.dept_id')
                ->leftjoin('unit_master','unit_master.unit_id','=','delivery_challan_detail.unit_id')
                ->leftjoin('usermaster','usermaster.userId','=','delivery_challan_master.userId')
                ->whereBetween('delivery_challan_master.issue_date', [$request->fromDate, $request->toDate]) 
                ->get();
         
         
        $fromDate =  $request->fromDate;
        $toDate =  $request->toDate;
         
        // dd(DB::getQueryLog());
         return view('rptGatePass1',compact('DeliveryChallanMasterData','fromDate','toDate')); 
    }
    
    public function Get_Gate_Pass2()
    {   
        $DeliveryChallanMasterData = DB::table('delivery_challan_master')->select('delivery_challan_master.*')->get();
 
        return view('Get_Gate_Pass2', compact('DeliveryChallanMasterData'));  
    }
    
    public function rptGatePass2(Request $request)
    { 
        //DB::enableQueryLog();
         $DeliveryChallanMasterData = DB::table('delivery_challan_detail')
                ->select('delivery_challan_master.*','delivery_challan_detail.*','ledger_master.ac_name','usermaster.username',
                'material_type_master.material_type_name','department_master.dept_name','unit_master.unit_name')
                ->join('delivery_challan_master','delivery_challan_master.issue_no','=','delivery_challan_detail.issue_no')
                ->leftjoin('ledger_master','ledger_master.ac_code','=','delivery_challan_master.ac_code')
                ->leftjoin('material_type_master','material_type_master.material_type_id','=','delivery_challan_master.product_type')
                ->leftjoin('department_master','department_master.dept_id','=','delivery_challan_master.dept_id')
                ->leftjoin('unit_master','unit_master.unit_id','=','delivery_challan_detail.unit_id')
                ->leftjoin('usermaster','usermaster.userId','=','delivery_challan_master.userId')
                ->whereBetween('delivery_challan_master.issue_date', [$request->fromDate, $request->toDate]) 
                ->get();
                
        $fromDate =  $request->fromDate;
        $toDate =  $request->toDate;
         
        // dd(DB::getQueryLog());
         return view('rptGatePass2',compact('DeliveryChallanMasterData','fromDate','toDate')); 
    }
    
        
    public function Get_Gate_Pass3()
    {   
        $DeliveryChallanMasterData = DB::table('delivery_challan_master')->select('delivery_challan_master.*')->get();
 
        return view('Get_Gate_Pass3', compact('DeliveryChallanMasterData'));  
    }
    
    public function rptGatePass3(Request $request)
    { 
        //DB::enableQueryLog();
         $DeliveryChallanMasterData = DB::table('delivery_challan_detail')
                ->select('delivery_challan_master.*','delivery_challan_detail.*','ledger_master.ac_name','usermaster.username',
                'material_type_master.material_type_name','department_master.dept_name','unit_master.unit_name')
                ->join('delivery_challan_master','delivery_challan_master.issue_no','=','delivery_challan_detail.issue_no')
                ->leftjoin('ledger_master','ledger_master.ac_code','=','delivery_challan_master.ac_code')
                ->leftjoin('material_type_master','material_type_master.material_type_id','=','delivery_challan_master.product_type')
                ->leftjoin('department_master','department_master.dept_id','=','delivery_challan_master.dept_id')
                ->leftjoin('unit_master','unit_master.unit_id','=','delivery_challan_detail.unit_id')
                ->leftjoin('usermaster','usermaster.userId','=','delivery_challan_master.userId')
                ->whereBetween('delivery_challan_master.issue_date', [$request->fromDate, $request->toDate]) 
                ->get();
         
         
        $fromDate =  $request->fromDate;
        $toDate =  $request->toDate;
         
        // dd(DB::getQueryLog());
         return view('rptGatePass3',compact('DeliveryChallanMasterData','fromDate','toDate')); 
    }
}
