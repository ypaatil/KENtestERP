<?php

namespace App\Http\Controllers;

use App\Models\DepartmentTypeModel;
use App\Models\SampleCustomerFeedbackModel;
use App\Models\SampleCustomerFeedbackDetailModel; 
use App\Models\SampleIndentModel; 
use App\Models\SampleIndentOrderModel;  
use App\Models\SampleQcDeptStitchingModel; 
use App\Models\SampleCustomerFeedbackStitchingSizeDetailModel; 
use App\Models\SampleCustomerFeedbackStitchingModel; 
use App\Models\SizeModel;
use App\Models\ItemModel;
use App\Models\SizeDetailModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class SampleCustomerFeedbackController extends Controller
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
        ->where('form_id', '275')
        ->first();


        $data = SampleCustomerFeedbackModel::join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sample_customer_feedback_master.mainstyle_id')
            ->join('ledger_master', 'ledger_master.ac_code', '=', 'sample_customer_feedback_master.ac_code')
            ->join('sub_style_master', 'sub_style_master.mainstyle_id', '=', 'sample_customer_feedback_master.mainstyle_id')
            ->join('sample_type_master', 'sample_type_master.sample_type_id', '=', 'sample_customer_feedback_master.sample_type_id')
            ->join('sample_cad_dept_master', 'sample_cad_dept_master.sample_indent_code', '=', 'sample_customer_feedback_master.sample_indent_code')
            ->join('customer_feedback_status', 'customer_feedback_status.cust_feed_status_id', '=', 'sample_customer_feedback_master.cust_feed_status_id')
            ->join('usermaster', 'usermaster.userId', '=', 'sample_customer_feedback_master.userId')
            ->where('sample_customer_feedback_master.delflag', '=', '0')
            ->select(
                'sample_customer_feedback_master.*','sample_cad_dept_master.delivery_date','usermaster.username',
                'ledger_master.ac_short_name','sub_style_master.substyle_name','customer_feedback_status.cust_feed_status_name',
                'main_style_master.mainstyle_name','sample_type_master.sample_type_name',
                DB::raw('(SELECT sum(size_qty_total) FROM sample_indent_order 
                          WHERE sample_indent_order.sample_indent_code = sample_customer_feedback_master.sample_indent_code) as total_qty'),
                          
                DB::raw('(SELECT sum(size_qty_total) FROM sample_qc_stitching_detail 
                          WHERE sample_qc_stitching_detail.sample_indent_code = sample_customer_feedback_master.sample_indent_code) as stitching_qty')
            )
            ->groupBy('sample_customer_feedback_master.sample_qc_dept_id')
            ->orderBy('sample_customer_feedback_master.sample_qc_dept_id', 'DESC')
            ->get();
            
        return view('SampleCustomerFeedbackMasterList', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Buyerlist = DB::table('ledger_master')->where('bt_id','=', 2)->where('delflag','=', '0')->get();
        $MainStylelist = DB::table('main_style_master')->where('delflag','=', '0')->get();
        $SubStylelist = DB::table('sub_style_master')->where('delflag','=', '0')->get();
        $SampleTypelist = DB::table('sample_type_master')->where('delflag','=', '0')->get();
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        $SizeGroupList = SizeModel::where('size_master.delflag','=', '0')->get();
        $BOMTypelist = DB::table('bom_types')->where('bom_type_id','!=', 4)->get();
        $SampleIndentMasterList = DB::table('sample_qc_dept_master')->select('sample_indent_code')->groupBy('sample_indent_code')->where('delflag','=', '0')->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $CustFeedList = DB::table('customer_feedback_status')->where('delflag','=', '0')->get();
        return view('SampleCustomerFeedbackMaster',compact('Buyerlist','MainStylelist','SubStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist','SampleIndentMasterList','MaterialReceivedList','CustFeedList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
            $otherMasterData = DB::SELECT("SELECT sample_cad_dept_master.sample_cad_dept_id, sample_qc_dept_master.sample_qc_dept_id FROM sample_cad_dept_master 
                        LEFT JOIN sample_qc_dept_master ON sample_qc_dept_master.sample_indent_code = sample_cad_dept_master.sample_indent_code
                        WHERE sample_cad_dept_master.sample_indent_code='".$request->sample_indent_code."'");
                        
            $sample_cad_dept_id = isset($otherMasterData[0]->sample_cad_dept_id) ? $otherMasterData[0]->sample_cad_dept_id : 0; 
            $sample_qc_dept_id = isset($otherMasterData[0]->sample_qc_dept_id) ? $otherMasterData[0]->sample_qc_dept_id : 0; 
            
            $data1=array
            ( 
                'sample_indent_code'=>$request->sample_indent_code, 
                'sample_cust_feed_date'=>$request->sample_cust_feed_date, 
                'Ac_code'=>$request->Ac_code,
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id, 
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,
                'sample_type_id'=>$request->sample_type_id,
                'dept_type_id'=>$request->dept_type_id,
                'sz_code'=>$request->sz_code,
                'userId'=>$request->userId,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'sample_cad_dept_id'=>$sample_cad_dept_id,
                'sample_qc_dept_id'=>$sample_qc_dept_id,
                'cust_feed_status_id'=>$request->cust_feed_status_id,
                'cust_comments'=>$request->cust_comments
             );
    
            SampleCustomerFeedbackModel::insert($data1);
            return redirect()->route('SampleCustomerFeedback.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SampleCustomerFeedback  $SampleCustomerFeedback
     * @return \Illuminate\Http\Response
     */
    public function show(SampleCustomerFeedback $SampleCustomerFeedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SampleCustomerFeedback  $SampleCustomerFeedback
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //DB::enableQueryLog();
        $SampleCustomerFeedback = SampleCustomerFeedbackModel::find($id);  
        //dd(DB::getQueryLog());
        $Buyerlist = DB::table('ledger_master')->where('bt_id','=', 2)->where('delflag','=', '0')->get();
        $MainStylelist = DB::table('main_style_master')->where('delflag','=', '0')->get();
        $SubStylelist = DB::table('sub_style_master')->where('delflag','=', '0')->get();
        $SampleTypelist = DB::table('sample_type_master')->where('delflag','=', '0')->get();
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        $SizeGroupList = SizeModel::where('size_master.delflag','=', '0')->get();
        $BOMTypelist = DB::table('bom_types')->where('bom_type_id','!=', 4)->get();
        $SampleIndentMasterList = DB::table('sample_indent_master')->where('delflag','=', '0')->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $CustFeedList = DB::table('customer_feedback_status')->where('delflag','=', '0')->get();
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $SampleCustomerFeedback->sample_indent_code)->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $SampleCustomerFeedback->sz_code)->get();
        // $SampleStitchingDetailList = SampleQcDeptStitchingModel::where('sample_qc_dept_id','=', $SampleCustomerFeedback->sample_qc_dept_id)->get();
        $SampleStitchingDetailList =  DB::table('sample_qc_stitching_detail')->select('sample_qc_stitching_detail.*', DB::raw("sum(size_qty_total) as total_qty"))->where('sample_indent_code', '=',  $SampleCustomerFeedback->sample_indent_code)->groupBy('sample_qc_stitching_detail.sample_indent_code')->get();
        $SampleStitchingAttachmentList = DB::table('sample_qc_dept_attachment')->where('sample_qc_dept_id','=',  $SampleCustomerFeedback->sample_qc_dept_id)->get();
        $SampleIndentList = SampleIndentModel::where('sample_indent_code','=', $SampleCustomerFeedback->sample_indent_code)->get();
    
        return view('SampleCustomerFeedbackMasterEdit',compact('SampleCustomerFeedback', 'SizeDetailList', 'SampleIndentDetailList', 
                    'Buyerlist','MainStylelist','SubStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist',
                    'MaterialReceivedList','SampleStitchingDetailList','SampleStitchingAttachmentList','SampleIndentList','CustFeedList')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SampleCustomerFeedback  $SampleCustomerFeedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
        $data1=array
        ( 
            'sample_indent_code'=>$request->sample_indent_code, 
            'sample_cust_feed_date'=>$request->sample_cust_feed_date, 
            'Ac_code'=>$request->Ac_code,
            'mainstyle_id'=>$request->mainstyle_id,
            'substyle_id'=>$request->substyle_id, 
            'style_description'=>$request->style_description,
            'sam'=>$request->sam,
            'sample_type_id'=>$request->sample_type_id,
            'dept_type_id'=>$request->dept_type_id,
            'sz_code'=>$request->sz_code,
            'userId'=>$request->userId,
            'updated_at'=>date("Y-m-d H:i:s"),
            'sample_cad_dept_id'=>$request->sample_cad_dept_id,
            'sample_qc_dept_id'=>$request->sample_qc_dept_id,
            'cust_feed_status_id'=>$request->cust_feed_status_id,
            'cust_comments'=>$request->cust_comments
         );
        
        $SampleCustomerFeedbackList = SampleCustomerFeedbackModel::findOrFail($request->sample_cust_feed_id);
        $SampleCustomerFeedbackList->fill($data1)->save(); 
        
        return redirect()->route('SampleCustomerFeedback.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SampleCustomerFeedback  $SampleCustomerFeedback
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        SampleCustomerFeedbackModel::where('sample_cust_feed_id', $id)->delete();  

        return redirect()->route('SampleCustomerFeedback.index')->with('message', 'Deleted Record Succesfully');
    }
    
    public function GetSampleIndentMasterCustomerData(Request $request)
    {
        $sample_indent_code = $request->sample_indent_code;
        $MasterData = DB::table('sample_indent_master')->select('*')->where('sample_indent_code', '=', $sample_indent_code)->first();
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $MasterData->sample_indent_code)->get();
        $SampleIndentQcDetailList =  DB::table('sample_qc_stitching_detail')->select('*')->where('sample_indent_code', '=', $sample_indent_code)->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $MasterData->sz_code)->get();
        $AttachData = DB::table('sample_qc_dept_attachment')->select('*')->where('sample_indent_code', '=', $sample_indent_code)->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $sizes='';
        
        foreach ($SizeDetailList as $sz) 
        {
            
            $sizes=$sizes.$sz->size_id.',';
        }
        $sizes=rtrim($sizes,',');
            
        $html = '<table id="footable_1" class="table table-bordered table-striped m-b-0 footable_1">
                <thead>
                    <tr>
                        <th>SrNo</th>
                        <th>Color</th>';
                        foreach ($SizeDetailList as $sz)
                        {
                            $html .= '<th>'.$sz->size_name.'</th>';
                        }
                        $html .= '<th>Total Qty</th>
                    </tr>
                </thead>
                <tbody id="SampleIndent">';
                    if(count($SampleIndentDetailList) > 0)
                    {
                        $no = 1; 
                        $n = 1;
                        foreach($SampleIndentDetailList as $List)
                        {
                            $n = 1;  
                            $SizeQtyList = explode(',', $List->size_qty_array); 
                            $html .= '<tr>
                                <td>'.$no.'</td>
                                <td>'.$List->color.'</td>';
                                foreach($SizeQtyList as $key => $szQty)
                                {
                                    $html .= '<td>'.$szQty.'</td>';
                                     $n++;
                                }
                                $html .= '<td>'.$List->size_qty_total.'</td>
                            </tr>';
                            $no++; 
                        }
                    }
                $html .= '</tbody>
            </table>';
            
            
        $html1 = '<table id="footable_2" class="table table-bordered table-striped m-b-0 footable_2">
                <thead>
                    <tr>
                        <th>SrNo</th>
                        <th>Color</th>';
                        foreach ($SizeDetailList as $sz)
                        {
                            $html1 .= '<th>'.$sz->size_name.'</th>';
                        }
                        $html1 .= '<th>Total Qty</th>
                    </tr>
                </thead>
                <tbody>';
                    if(count($SampleIndentQcDetailList) > 0)
                    {
                        $no = 1; 
                        $n = 1;
                        foreach($SampleIndentQcDetailList as $List)
                        {
                            $n = 1;  
                            $SizeQtyList = explode(',', $List->size_qty_array); 
                            $html1 .= '<tr>
                                <td><input type="text" name="id[]" value="'.$no.'" id="id'.$no.'" style="width:50px;" /></td>
                                <td><input type="text" name="color[]" class="color" value="'.$List->color.'" id="color'.$no.'" style="width:150px; height:30px;" readonly /></td>';
                                foreach($SizeQtyList as $key => $szQty)
                                {
                                    $html1 .= '<td><input type="number" name="s'.$n.'[]" class="size_id" value="'.$szQty.'" id="size_id'.$no.'_'.$n.'" style="width:80px; height:30px;" onchange="GetSizeQtyArray(this);"  readonly /></td>';
                                     $n++;
                                }
                                $html1 .= '<td>
                                    <input type="number" name="order_qty[]" class="QTY" value="'.$List->size_qty_total.'" id="size_qty_total'.$no.'" style="width:80px; height:30px;" readonly />
                                    <input type="hidden" name="size_array[]" class="size_array" value="'.$sizes.'" id="size_array'.$no.'" />
                                    <input type="hidden" name="size_qty_array[]" class="size_qty_array" value="" id="size_qty_array'.$no.'" />
                                </td>
                            </tr>';
                            $no++; 
                        }
                    } 
                $html1 .= '</tbody>
            </table>';
            
             
            $html2 = '<table id="footable_3" class="table table-bordered table-striped m-b-0 footable_3">
                <thead>
                    <tr>
                        <th style="text-align: center;">Sr.No.</th>
                        <th style="text-align: center;">Attachment</th>
                    </tr>
                </thead>
                <tbody>';
                    if(count($AttachData) > 0)
                    {
                        $no = 1; 
                        foreach($AttachData as $List1)
                        {
                            $upload_attachment = '../uploads/Sample/'.$List1->upload_attachment;
                            $html2 .= '<tr>
                                <td style="vertical-align: middle;text-align: center;">'.$no.'</td>
                                <td style="text-align: center;"><img src="'.$upload_attachment.'" width="100" height="80" alt=""></td>
                            </tr>';
                            $no++; 
                        }
                    }
                $html2 .= '</tbody>
            </table>';
            
             
            $html3 = '<table id="footable_4" class="table table-bordered table-striped m-b-0 footable_4">
                <thead>
                    <tr>
                        <th>Bom Type</th>
                        <th>Material Received Status</th>
                    </tr>
                </thead>
                <tbody>
                        <tr> 
                           <td>
                               Fabric
                           </td>
                           <td>';
                           
                                    $detail1 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 1 AND sample_indent_code='".$sample_indent_code."'");
                                    $m1 = isset($detail1[0]->material_received_status_id) ? $detail1[0]->material_received_status_id : 0;
                              
                                $html3 .= '<select name="material_received_status_id[]" class="form-select " id="material_received_status_id"  style="width:250px;" disabled>
                                   <option value="">-- Select --</option>';
                                   foreach($MaterialReceivedList as  $row)
                                   {
                                         $selected1 = '';
                                         if($row->material_received_status_id == $m1)
                                         {
                                             $selected1 = 'selected';
                                         }
                                         $html3 .= '<option value="'.$row->material_received_status_id.'"  '.$selected1.' >'.$row->material_received_status_name.'</option>';
                                   }
                                 $html3 .= '</select>
                           </td>
                        </tr>
                        <tr> 
                           <td>
                               Sewing Trims 
                           </td>
                           <td>';
                                    $detail2 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 2 AND sample_indent_code='".$sample_indent_code."'");
                                    
                                    $m2 = isset($detail2[0]->material_received_status_id) ? $detail2[0]->material_received_status_id : 0;
                               
                                $html3 .= ' <select name="material_received_status_id[]" class="form-select " id="material_received_status_id"  style="width:250px;" disabled>
                                   <option value="">-- Select --</option>';
                                   foreach($MaterialReceivedList as  $row)
                                   {
                                         $selected2 = '';
                                         if($row->material_received_status_id == $m2)
                                         {
                                             $selected2 = 'selected';
                                         }
                                         
                                         $html3 .= '<option value="'.$row->material_received_status_id.'"  '.$selected2.'  >'.$row->material_received_status_name.'</option>';
                                   }
                                $html3 .= '</select>
                           </td>
                        </tr>
                        <tr> 
                           <td>
                               Packing Trims
                               <input type="hidden" name="bom_type_id[]" value="3" id="bom_type_id" style="width:50px;"/>
                           </td>
                           <td>';
                                    $detail3 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 3 AND sample_indent_code='".$sample_indent_code."'");
                                    
                                    $m3 = isset($detail3[0]->material_received_status_id) ? $detail3[0]->material_received_status_id : 0;
                               
                               $html3 .= '<select name="material_received_status_id[]" class="form-select " id="material_received_status_id"  style="width:250px;" disabled>
                                   <option value="">-- Select --</option>';
                                    foreach($MaterialReceivedList as  $row)
                                    {
                                         $selected3 = '';
                                         if($row->material_received_status_id == $m3)
                                         {
                                             $selected3 = 'selected';
                                         }
                                         
                                        $html3 .= '<option value="'.$row->material_received_status_id.'"  '.$selected3.' >'.$row->material_received_status_name.'</option>';
                                    }
                                $html3 .= '</select>
                           </td>
                        </tr>
                    </tbody>
                </table>';
            
        return response()->json(['MasterData' => $MasterData, 'DetailHtml'=>$html, 'StitchingHtml'=>$html1, 'AttachmentHtml'=>$html2, 'BOMHtml'=>$html3]);
    }
    
    public function GetSINCodeList(Request $request)
    {
        $Ac_code = $request->Ac_code;
        
        $masterData = DB::SELECT("SELECT sample_indent_code FROM sample_qc_dept_master 
                        where  sample_qc_dept_master.Ac_code=".$Ac_code." AND sample_indent_code NOT IN (SELECT sample_indent_code FROM sample_customer_feedback_master WHERE sample_customer_feedback_master.Ac_code = sample_qc_dept_master.Ac_code) 
                        GROUP BY sample_qc_dept_master.sample_indent_code");

        $html = '<option value="">--Select--</option>';
        foreach($masterData as $row)
        {
            $html .='<option value="'.$row->sample_indent_code.'">'.$row->sample_indent_code.'</option>';
        }
        
        return response()->json(['html' => $html]);
    }
    
    
    
    public function SampleCustomerFeedbackPrint($sample_indent_code)
    {
        
       $SampleList = SampleCustomerFeedbackModel::join('usermaster', 'usermaster.userId', '=', 'sample_customer_feedback_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sample_customer_feedback_master.Ac_code', 'left outer')
        ->join('sample_indent_master', 'sample_indent_master.sample_indent_code', '=', 'sample_customer_feedback_master.sample_indent_code', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sample_customer_feedback_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sample_customer_feedback_master.substyle_id', 'left outer')  
        ->join('brand_master', 'brand_master.brand_id', '=', 'sample_indent_master.brand_id', 'left outer')  
        ->join('sample_type_master', 'sample_type_master.sample_type_id', '=', 'sample_customer_feedback_master.sample_type_id', 'left outer')     
        ->join('department_type', 'department_type.dept_type_id', '=', 'sample_customer_feedback_master.dept_type_id', 'left outer')     
        ->where('sample_customer_feedback_master.delflag','=', '0')
        ->where('sample_customer_feedback_master.sample_indent_code','=', $sample_indent_code)
        ->get(['sample_customer_feedback_master.*','usermaster.username','ledger_master.Ac_name','main_style_master.mainstyle_name',
                'sub_style_master.substyle_name', 'sample_type_master.sample_type_name','department_type.dept_type_name', 'brand_master.brand_name']);

    
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $sample_indent_code)->get();
        $SampleStitchingDetailList =  DB::table('sample_qc_stitching_detail')->select('sample_qc_stitching_detail.*', DB::raw("sum(size_qty_total) as total_qty"))->where('sample_indent_code', '=',  $sample_indent_code)->groupBy('sample_qc_stitching_detail.sample_indent_code')->get();
       
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $CustFeedList = DB::table('customer_feedback_status')->where('delflag','=', '0')->get();
        
        return view('SampleCustomerFeedbackPrint', compact('SampleList', 'sample_indent_code','SampleIndentDetailList','SampleStitchingDetailList','MaterialReceivedList','CustFeedList'));     
    }
}
