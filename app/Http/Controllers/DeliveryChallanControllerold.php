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
        ->where('form_id', '118')
        ->first();
        
        // DB::enableQueryLog();
        $DeliveryChallanList = DeliveryChallanMasterModel::leftjoin('usermaster', 'usermaster.userId', '=', 'delivery_challan_master.userId')
        ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'delivery_challan_master.ac_code')
        ->leftjoin('department_master', 'department_master.dept_id', '=', 'delivery_challan_master.dept_id')
        ->where('delivery_challan_master.delflag','=', '0')
        ->get(['delivery_challan_master.*','usermaster.username', 'ledger_master.ac_name','department_master.dept_name']);
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
        $Ledger = DB::table('ledger_master')->get();

        $departmentlist=DB::table('department_master')->select('dept_id','dept_name')->where('delflag',0)->get();   

        $unitlist=DB::table('unit_master')->select('unit_id','unit_name')->where('delflag',0)->get();

        // DB::enableQueryLog();
        $IssueList = DB::select("SELECT dc.issue_no FROM delivery_challan_master as dc WHERE dc.dc_case_id = 1 and dc.issue_case_id = 1 and dc.total_qty!= (select ifnull(sum(delivery_challan_master.total_qty),0) FROM delivery_challan_master WHERE delivery_challan_master.return_issue_no = dc.issue_no and delivery_challan_master.issue_case_id = 2 )");


        // dd(DB::getQueryLog());

        return view('DeliveryChallanMaster',compact('Ledger','departmentlist','unitlist','IssueList'));
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
            'sent_through'=>'required',
            'dept_id'=>'required',
            'to_location'=>'required',
            'total_qty'=>'required',
            'NetAmount'=>'required',
            'narration'=>'required',
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


        $data1=array(
            'issue_no'=>$issue_no,
            'return_issue_no'=>$request->return_issue_no ? $request->return_issue_no : '',
            'dc_case_id'=>$request->dc_case_id,
            'issue_case_id'=>$request->issue_case_id,
            'issue_date'=>$request->issue_date,
            'return_date'=>$request->return_date ? $request->return_date : '',
            'product_type'=>$request->product_type,
            'reciever_type'=>$request->reciever_type,
            'ac_code'=>$request->ac_code  ? $request->ac_code : '',
            'otherBuyerorVendor'=>$request->otherBuyerorVendor ? $request->otherBuyerorVendor : '',
            'sent_through'=>$request->sent_through,
            'dept_id'=>$request->dept_id,
            'to_location'=>$request->to_location,
            'total_qty'=>$request->total_qty,
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
                    'quantity'=>$request->quantity[$x],
                    'return_quantity'=>$return_quantity,
                    'rate'=>$request->rate[$x],
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
        ->select('delivery_challan_master.*','ledger_master.ac_name','ledger_master.ac_code', 'department_master.dept_id','department_master.dept_name')
        ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'delivery_challan_master.ac_code')
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
        $Ledger = DB::table('ledger_master')->get();

        $departmentlist=DB::table('department_master')->select('dept_id','dept_name')->where('delflag',0)->get();   

        $unitlist=DB::table('unit_master')->select('unit_id','unit_name')->where('delflag',0)->get();


        $DeliveryChallanMasterList = DeliveryChallanMasterModel::find($id);

 // DB::enableQueryLog();

         $IssueList = DB::select("SELECT dc.issue_no FROM delivery_challan_master as dc WHERE dc.dc_case_id = 1 and dc.issue_case_id = 1 and dc.total_qty!= (select ifnull(sum(delivery_challan_master.total_qty),0) FROM delivery_challan_master WHERE delivery_challan_master.return_issue_no = dc.issue_no and delivery_challan_master.issue_case_id = 2 ) UNION select return_issue_no from delivery_challan_master where issue_no ='".$id."'");

 // dd(DB::getQueryLog());

        $DeliveryChallanDetailList = DeliveryChallanDetailModel::join('unit_master','unit_master.unit_id', '=', 'delivery_challan_detail.unit_id')
        ->where('delivery_challan_detail.issue_no','=', $DeliveryChallanMasterList->issue_no)->get(['delivery_challan_detail.*','unit_master.unit_name']);

        return view('DeliveryChallanMasterEdit',compact('Ledger','departmentlist','unitlist','IssueList','DeliveryChallanMasterList','DeliveryChallanDetailList'));
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
            'sent_through'=>'required',
            'dept_id'=>'required',
            'to_location'=>'required',
            'total_qty'=>'required',
            'NetAmount'=>'required',
            'narration'=>'required',
            'userId'=>'required',
        ]);

        $data1=array(
            'issue_no'=>$request->issue_no,
            'return_issue_no'=>$request->return_issue_no ? $request->return_issue_no : '',
            'dc_case_id'=>$request->dc_case_id,
            'issue_case_id'=>$request->issue_case_id,
            'issue_date'=>$request->issue_date,
            'return_date'=>$request->return_date ? $request->return_date : '',
            'product_type'=>$request->product_type,
            'reciever_type'=>$request->reciever_type,
            'ac_code'=>$request->ac_code  ? $request->ac_code : '',
            'otherBuyerorVendor'=>$request->otherBuyerorVendor ? $request->otherBuyerorVendor : '',
            'sent_through'=>$request->sent_through,
            'dept_id'=>$request->dept_id,
            'to_location'=>$request->to_location,
            'total_qty'=>$request->total_qty,
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
                    $return_quantity = $request->return_quantity[$x];
                }
                else{
                    $return_quantity = 0;
                }
                $data2=array(
                    'issue_no'=>$request->issue_no,
                    'return_issue_no'=>$request->return_issue_no ? $request->return_issue_no : '',
                    'item_description'=>$request->item_description[$x],
                    'unit_id'=>$request->unit_id[$x],
                    'quantity'=>$request->quantity[$x],
                    'return_quantity'=>$return_quantity,
                    'rate'=>$request->rate[$x],
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

        $DeliveryChallan = DB::select("select *, ac_name, dept_name from delivery_challan_master left join ledger_master on ledger_master.ac_code = delivery_challan_master.ac_code left join department_master on department_master.dept_id = delivery_challan_master.dept_id where issue_no='".$request->issue_no."' ");

        // dd(DB::getQueryLog());

        return json_encode($DeliveryChallan);
    }

    public function getDeliveryChallanDetailsData(Request $request)
    {
        // DB::enableQueryLog();

        $detail = DeliveryChallanDetailModel::leftjoin('unit_master', 'unit_master.unit_id', '=', 'delivery_challan_detail.unit_id')    
        ->where('delivery_challan_detail.issue_no','=',$request->issue_no)
        ->get(['delivery_challan_detail.*',DB::raw("(select sum(return_quantity) from delivery_challan_detail as dc where dc.return_issue_no='".$request->issue_no."' and dc.item_description= delivery_challan_detail.item_description) as received_qty"),'unit_master.unit_name']);
//dd(DB::getQueryLog());
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
            <th>Quantity</th>
            <th>Return Quantity</th>
            <th>Recieved Quantity</th>
            <th>Rate</th>
            <th>Total Amount</th>
            <th>Remark</th>
            <th>Add/Remove</th>
            </tr>
            </thead>
            <tbody>';
            $no=1;

            foreach ($detail as $value) 
            {
                $html .='<tr>';

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

                $html.='
                <td><input type="number" name="quantity[]" id="quantity1" class="form-control QTY" tabindex="19" step="any" value="'.$value->quantity.'" required="required" style="width:120px;" readonly></td>
                <td><input type="number" step="any" name="return_quantity[]" id="return_quantity" class="form-control RTQTY" min="0" max="'.($value->quantity-$value->received_qty).'" tabindex="19"   required="required" style="width:120px;"></td>
                <td><input type="text" readOnly name="reecived_quantity[]" id="reecived_quantity" value="'.$value->received_qty.'" class="form-control RCQTY" tabindex="19" step="any" required="required" style="width:120px;"></td>
                <td><input type="number" name="rate[]" id="rate" class="form-control" tabindex="20"  step="any" required="required" value="'.$value->rate.'" style="width:120px;" readonly></td>

                <td><input type="number" class="form-control TAMT"  name="total_amount[]" onkeyup="mycalc();" value="'.$value->total_amount.'" id="total_amount1" style="width:80px;" required readonly/></td>

                <td><input type="text" name="remark[]" id="remark" class="form-control" tabindex="21" required="required" value="'.$value->remark.'" style="width:120px;" readonly></td>

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
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total Amount</th>
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

            $html.='
            <td><input type="number" name="quantity[]" id="quantity1" class="form-control QTY" tabindex="19" step="any"  required="required" style="width:120px;"></td>

            <td><input type="number" name="rate[]" id="rate" class="form-control" tabindex="20"  step="any" required="required"  style="width:120px;"></td>

            <td><input type="number" class="form-control TAMT"  name="total_amount[]" onkeyup="mycalc();"  id="total_amount1" style="width:80px;" required readonly/></td>

            <td><input type="text" name="remark[]" id="remark" class="form-control" tabindex="21" required="required"  style="width:120px;"></td>

            <td><input type="button" style="width:40px; margin-right: 5px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left " onclick="deleteRow(this);" value="X" ></td>
            ';

            $html .='</tr>';

            $no=$no+1;

            $html .='</tbody>

            <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
            </table>';

        }

        return response()->json(['html' => $html]);

    }
}
