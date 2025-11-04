<?php

namespace App\Http\Controllers;

use App\Models\DepartmentTypeModel;
use App\Models\SampleIndentModel;
use App\Models\SampleIndentOrderModel;
use App\Models\SampleIndentOrderSizeDetailModel;
use App\Models\SampleIndentFabricModel;
use App\Models\SampleIndentSewingTrimsModel;
use App\Models\SampleIndentPackingTrimsModel;
use App\Models\SizeModel;
use App\Models\ItemModel;
use App\Models\SizeDetailModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SampleIndentController extends Controller
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
        ->where('form_id', '272')
        ->first();

        $data = SampleIndentModel::join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sample_indent_master.mainstyle_id')
            ->join('ledger_master', 'ledger_master.ac_code', '=', 'sample_indent_master.ac_code')
            ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'sample_indent_master.brand_id')
            ->leftjoin('department_type', 'department_type.dept_type_id', '=', 'sample_indent_master.dept_type_id')
            ->join('sub_style_master', 'sub_style_master.mainstyle_id', '=', 'sample_indent_master.mainstyle_id')
            ->join('sample_type_master', 'sample_type_master.sample_type_id', '=', 'sample_indent_master.sample_type_id')
            ->join('usermaster', 'usermaster.userId', '=', 'sample_indent_master.userId')
            ->where('sample_indent_master.delflag', '=', '0')
            ->select('department_type.*','sample_indent_master.*','usermaster.username','brand_master.brand_name',
                'ledger_master.ac_short_name','sub_style_master.substyle_name',
                'main_style_master.mainstyle_name','sample_type_master.sample_type_name',
                DB::raw('(SELECT sum(size_qty_total) FROM sample_indent_order 
                          WHERE sample_indent_order.sample_indent_code = sample_indent_master.sample_indent_code) as total_qty')
            )
            ->groupBy('sample_indent_master.sample_indent_code')
            ->orderBy('sample_indent_master.sample_indent_id', 'DESC')
            ->get();


        return view('SampleIndentMasterList', compact('data','chekform'));
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
        $SampleTypelist = DB::table('sample_type_master')->where('delflag','=', '0')->get();
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        $SizeGroupList = SizeModel::where('size_master.delflag','=', '0')->get();
        $BOMTypelist = DB::table('bom_types')->where('bom_type_id','!=', 4)->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $CustomerFeedbackStatusList = DB::table('customer_feedback_status')->where('delflag','=', '0')->get();
        return view('SampleIndentMaster',compact('Buyerlist','MainStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist','MaterialReceivedList','CustomerFeedbackStatusList'));
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
            ->where('type','=','SampleIndent')
            ->where('firm_id','=',1)
            ->first();
            $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
     
 
            $Ac_code = isset($request->Ac_code) ? $request->Ac_code: 0;
            
            $data1=array
            (
                'sample_indent_code'=>$TrNo, 
                'sample_indent_date'=>$request->sample_indent_date, 
                'Ac_code'=>$request->Ac_code,
                'brand_id'=>$request->brand_id,
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id, 
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,
                'sample_type_id'=>$request->sample_type_id,
                'dept_type_id'=>$request->dept_type_id,
                'sz_code'=>$request->sz_code,
                'sample_required_date'=>$request->sample_required_date,
                'userId'=>$request->userId,
                'remark'=>$request->remark,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
             );
         
            SampleIndentModel::insert($data1);
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='SampleIndent'");
        
            $color = $request->color;
            if(count($color)>0)
            {   
                    
                for($x=0; $x<count($color); $x++) 
                {
                  
                //   if($request->order_qty[$x]>0)
                //   {
                        $data2 =array
                        ( 
                        'sample_indent_id'=>$codefetch->tr_no, 
                        'sample_indent_code'=>$TrNo,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'color'=>$request->color[$x], 
                        'size_array'=>$request->size_array[$x],
                        'size_qty_array'=>$request->size_qty_array[$x],
                        'size_qty_total'=>$request->order_qty[$x]
                       );
                  
                          $s1=isset($request->s1[$x]) ? $request->s1[$x] : 0; $s11=isset($request->s11[$x]) ? $request->s11[$x] : 0;
                          $s2=isset($request->s2[$x]) ? $request->s2[$x] : 0; $s12=isset($request->s12[$x]) ? $request->s12[$x] : 0;
                          $s3=isset($request->s3[$x]) ? $request->s3[$x] : 0; $s13=isset($request->s13[$x]) ? $request->s13[$x] : 0;
                          $s4=isset($request->s4[$x]) ? $request->s4[$x] : 0; $s14=isset($request->s14[$x]) ? $request->s14[$x] : 0;
                          $s5=isset($request->s5[$x]) ? $request->s5[$x] : 0; $s15=isset($request->s15[$x]) ? $request->s15[$x] : 0;
                          $s6=isset($request->s6[$x]) ? $request->s6[$x] : 0; $s16=isset($request->s16[$x]) ? $request->s16[$x] : 0;
                          $s7=isset($request->s7[$x]) ? $request->s7[$x] : 0; $s17=isset($request->s17[$x]) ? $request->s17[$x] : 0;
                          $s8=isset($request->s8[$x]) ? $request->s8[$x] : 0; $s18=isset($request->s18[$x]) ? $request->s18[$x] : 0;
                          $s9=isset($request->s9[$x]) ? $request->s9[$x] : 0; $s19=isset($request->s19[$x]) ? $request->s19[$x] : 0;
                          $s10=isset($request->s10[$x]) ? $request->s10[$x] : 0; $s20=isset($request->s20[$x]) ? $request->s20[$x] : 0;
     
                          $data3 =array(
                       
                            'sample_indent_id'=>$codefetch->tr_no, 
                            'sample_indent_code'=>$TrNo,
                            'sample_indent_date'=>$request->sample_indent_date,
                            'color'=>$request->color[$x], 
                            'size_array'=>$request->size_array[$x], 
                            's1'=>$s1,
                            's2'=>$s2,
                            's3'=>$s3,
                            's4'=>$s4,
                            's5'=>$s5,
                            's6'=>$s6,
                            's7'=>$s7,
                            's8'=>$s8,
                            's9'=>$s9,
                            's10'=>$s10,
                            's11'=>$s11,
                            's12'=>$s12,
                            's13'=>$s13,
                            's14'=>$s14,
                            's15'=>$s15,
                            's16'=>$s16,
                            's17'=>$s17,
                            's18'=>$s18,
                            's19'=>$s19,
                            's20'=>$s20,
                            'size_qty_total'=>$request->order_qty[$x],
                             );
                  
                          SampleIndentOrderModel::insert($data2);
                          SampleIndentOrderSizeDetailModel::insert($data3);
                   //   }  
                      
                    } 
            }
            
            $fabric_item_code = $request->fabric_item_code;
            if(count($fabric_item_code)>0)
            {   
                for($x=0; $x<count($fabric_item_code); $x++) 
                {
                    
                    $data4 =array
                    ( 
                        'sample_indent_id'=>$codefetch->tr_no, 
                        'sample_indent_code'=>$TrNo,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'fabric_item_code'=>$request->fabric_item_code[$x], 
                        'fabric_qty'=>$request->fabric_qty[$x]
                    );
                   
                    SampleIndentFabricModel::insert($data4);
                }
            }
    
            
            $sewing_trims_item_code = $request->sewing_trims_item_code;
            if(count($sewing_trims_item_code)>0)
            {   
                for($x=0; $x<count($sewing_trims_item_code); $x++) 
                {
                    
                    $data5 =array
                    ( 
                        'sample_indent_id'=>$codefetch->tr_no, 
                        'sample_indent_code'=>$TrNo,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'sewing_trims_item_code'=>$request->sewing_trims_item_code[$x], 
                        'sewing_trims_qty'=>$request->sewing_trims_qty[$x]
                   );
                   
                    SampleIndentSewingTrimsModel::insert($data5);
                }
            }
            
            $packing_trims_item_code = $request->packing_trims_item_code;
            if(count($packing_trims_item_code)>0)
            {   
                for($x=0; $x<count($packing_trims_item_code); $x++) 
                {
                    
                    $data5 =array
                    ( 
                        'sample_indent_id'=>$codefetch->tr_no, 
                        'sample_indent_code'=>$TrNo,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'packing_trims_item_code'=>$request->packing_trims_item_code[$x], 
                        'packing_trims_qty'=>$request->packing_trims_qty[$x]
                   );
                   
                    SampleIndentPackingTrimsModel::insert($data5);
                }
            }
            
        $InsertSizeData=DB::select('call AddSizeQtyFromSampleIndentOrder("'.$TrNo.'")');
        
        return redirect()->route('SampleIndent.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SampleIndent  $SampleIndent
     * @return \Illuminate\Http\Response
     */
    public function show(SampleIndent $SampleIndent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SampleIndent  $SampleIndent
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $SampleIndent = SampleIndentModel::find($id);  
        
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $SampleIndent->sample_indent_code)->get();
        $SampleIndentFabricList = SampleIndentFabricModel::where('sample_indent_code','=', $SampleIndent->sample_indent_code)->get();
        $SampleIndentSewingList = SampleIndentSewingTrimsModel::where('sample_indent_code','=', $SampleIndent->sample_indent_code)->get();
        $SampleIndentPackingList = SampleIndentPackingTrimsModel::where('sample_indent_code','=', $SampleIndent->sample_indent_code)->get();
        
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $SampleIndent->sz_code)->get();
        $Buyerlist = DB::table('ledger_master')->where('bt_id','=', 2)->where('delflag','=', '0')->get();
        $MainStylelist = DB::table('main_style_master')->where('delflag','=', '0')->get();
        $SubStylelist = DB::table('sub_style_master')->where('mainstyle_id','=', $SampleIndent->mainstyle_id)->where('delflag','=', '0')->get();
        $SampleTypelist = DB::table('sample_type_master')->where('delflag','=', '0')->get();
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        $SizeGroupList = SizeModel::where('size_master.delflag','=', '0')->get();
        $BOMTypelist = DB::table('bom_types')->where('bom_type_id','!=', 4)->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $CustomerFeedbackStatusList = DB::table('customer_feedback_status')->where('delflag','=', '0')->get();
        $BrandList = DB::table('brand_master')->where('Ac_code','=', $SampleIndent->Ac_code)->where('delflag','=', '0')->get(); 
        
        return view('SampleIndentMasterEdit',compact('SampleIndent', 'SampleIndentFabricList', 'SampleIndentSewingList', 'SampleIndentPackingList',  'SizeDetailList', 'SampleIndentDetailList', 'Buyerlist','MainStylelist','SubStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist','MaterialReceivedList','CustomerFeedbackStatusList','BrandList')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SampleIndent  $SampleIndent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $data1=array( 
            'sample_indent_code'=>$request->sample_indent_code, 
            'sample_indent_date'=>$request->sample_indent_date, 
            'Ac_code'=>$request->Ac_code,
            'brand_id'=>$request->brand_id,
            'mainstyle_id'=>$request->mainstyle_id,
            'substyle_id'=>$request->substyle_id, 
            'style_description'=>$request->style_description,
            'sam'=>$request->sam,
            'sample_type_id'=>$request->sample_type_id,
            'dept_type_id'=>$request->dept_type_id,
            'userId'=>$request->userId,
            'remark'=>$request->remark, 
            'sz_code'=>$request->sz_code,
            'sample_required_date'=>$request->sample_required_date,
            'updated_at'=>date("Y-m-d H:i:s")
        );
        
        $SampleIndentList = SampleIndentModel::findOrFail($request->sample_indent_id);
        $SampleIndentList->fill($data1)->save(); 
        
        $color = $request->color;
        if(!empty($color)) 
        {   
            DB::table('sample_indent_order')->where('sample_indent_code', $request->sample_indent_code)->delete();
            DB::table('sample_indent_order_size_detail')->where('sample_indent_code', $request->sample_indent_code)->delete();  
            
            for($x=0; $x<count($color); $x++) 
            {
              
            //   if($request->order_qty[$x]>0)
            //   {
                    $data2 =array
                    ( 
                    'sample_indent_id'=>$request->sample_indent_id, 
                    'sample_indent_code'=>$request->sample_indent_code,
                    'sample_indent_date'=>$request->sample_indent_date,
                    'color'=>$request->color[$x], 
                    'size_array'=>$request->size_array[$x],
                    'size_qty_array'=>$request->size_qty_array[$x],
                    'size_qty_total'=>$request->order_qty[$x]
                   );
              
                      $s1=isset($request->s1[$x]) ? $request->s1[$x] : 0; $s11=isset($request->s11[$x]) ? $request->s11[$x] : 0;
                      $s2=isset($request->s2[$x]) ? $request->s2[$x] : 0; $s12=isset($request->s12[$x]) ? $request->s12[$x] : 0;
                      $s3=isset($request->s3[$x]) ? $request->s3[$x] : 0; $s13=isset($request->s13[$x]) ? $request->s13[$x] : 0;
                      $s4=isset($request->s4[$x]) ? $request->s4[$x] : 0; $s14=isset($request->s14[$x]) ? $request->s14[$x] : 0;
                      $s5=isset($request->s5[$x]) ? $request->s5[$x] : 0; $s15=isset($request->s15[$x]) ? $request->s15[$x] : 0;
                      $s6=isset($request->s6[$x]) ? $request->s6[$x] : 0; $s16=isset($request->s16[$x]) ? $request->s16[$x] : 0;
                      $s7=isset($request->s7[$x]) ? $request->s7[$x] : 0; $s17=isset($request->s17[$x]) ? $request->s17[$x] : 0;
                      $s8=isset($request->s8[$x]) ? $request->s8[$x] : 0; $s18=isset($request->s18[$x]) ? $request->s18[$x] : 0;
                      $s9=isset($request->s9[$x]) ? $request->s9[$x] : 0; $s19=isset($request->s19[$x]) ? $request->s19[$x] : 0;
                      $s10=isset($request->s10[$x]) ? $request->s10[$x] : 0; $s20=isset($request->s20[$x]) ? $request->s20[$x] : 0;
 
                      $data3 =array(
                   
                        'sample_indent_id'=>$request->sample_indent_id, 
                        'sample_indent_code'=>$request->sample_indent_code,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'color'=>$request->color[$x], 
                        'size_array'=>$request->size_array[$x], 
                        's1'=>$s1,
                        's2'=>$s2,
                        's3'=>$s3,
                        's4'=>$s4,
                        's5'=>$s5,
                        's6'=>$s6,
                        's7'=>$s7,
                        's8'=>$s8,
                        's9'=>$s9,
                        's10'=>$s10,
                        's11'=>$s11,
                        's12'=>$s12,
                        's13'=>$s13,
                        's14'=>$s14,
                        's15'=>$s15,
                        's16'=>$s16,
                        's17'=>$s17,
                        's18'=>$s18,
                        's19'=>$s19,
                        's20'=>$s20,
                        'size_qty_total'=>$request->order_qty[$x],
                         );
              
                      SampleIndentOrderModel::insert($data2);
                      SampleIndentOrderSizeDetailModel::insert($data3);
                  }  
                  
                // } 
            }
            
            $fabric_item_code = $request->fabric_item_code;
            if(!empty($fabric_item_code)) 
            {    
                DB::table('sample_indent_fabric')->where('sample_indent_code', $request->sample_indent_code)->delete();
                
                for($x=0; $x<count($fabric_item_code); $x++) 
                {
                    
                    $data4 =array
                    ( 
                        'sample_indent_id'=>$request->sample_indent_id, 
                        'sample_indent_code'=>$request->sample_indent_code,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'fabric_item_code'=>$request->fabric_item_code[$x], 
                        'fabric_qty'=>$request->fabric_qty[$x]
                   );
                   
                   SampleIndentFabricModel::insert($data4);
                }
            }
    
            
            $sewing_trims_item_code = $request->sewing_trims_item_code;
            if(!empty($sewing_trims_item_code)) 
            {   
                DB::table('sample_indent_sewing_trims')->where('sample_indent_code', $request->sample_indent_code)->delete(); 
                
                for($x=0; $x<count($sewing_trims_item_code); $x++) 
                {
                    
                    $data5 =array
                    ( 
                        'sample_indent_id'=>$request->sample_indent_id, 
                        'sample_indent_code'=>$request->sample_indent_code,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'sewing_trims_item_code'=>$request->sewing_trims_item_code[$x], 
                        'sewing_trims_qty'=>$request->sewing_trims_qty[$x]
                   );
                   
                    SampleIndentSewingTrimsModel::insert($data5);
                }
            }
            
            $packing_trims_item_code = $request->packing_trims_item_code;
            if(!empty($packing_trims_item_code)) 
            {   
                DB::table('sample_indent_packing_trims')->where('sample_indent_code', $request->sample_indent_code)->delete();  
                
                for($x=0; $x<count($packing_trims_item_code); $x++) 
                {
                    $data5 =array
                    ( 
                        'sample_indent_id'=>$request->sample_indent_id, 
                        'sample_indent_code'=>$request->sample_indent_code,
                        'sample_indent_date'=>$request->sample_indent_date,
                        'packing_trims_item_code'=>$request->packing_trims_item_code[$x], 
                        'packing_trims_qty'=>$request->packing_trims_qty[$x]
                   );
                   
                    SampleIndentPackingTrimsModel::insert($data5);
                }
            } 
         
        $InsertSizeData=DB::select('call AddSizeQtyFromSampleIndentOrder("'.$request->sample_indent_code.'")');
        
        return redirect()->route('SampleIndent.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SampleIndent  $SampleIndent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        SampleIndentModel::where('sample_indent_id', $id)->delete(); 
        SampleIndentOrderModel::where('sample_indent_id', $id)->delete(); 
        SampleIndentOrderSizeDetailModel::where('sample_indent_id', $id)->delete(); 
        DB::table('sample_indent_fabric')->where('sample_indent_id', $id)->delete(); 
        DB::table('sample_indent_sewing_trims')->where('sample_indent_id', $id)->delete();
        DB::table('sample_indent_packing_trims')->where('sample_indent_id', $id)->delete();

        return redirect()->route('SampleIndent.index')->with('message', 'Deleted Record Succesfully');
    }
    
    
    public function SizeSampleIndentList(Request $request)
    { 
      
            ini_set('memory_limit', '10G');  
            $sz_code= $request->input('sz_code');
            
            $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $sz_code)->get();
            
            $sizes='';
            
            foreach ($SizeDetailList as $sz) 
            {
                
                $sizes=$sizes.$sz->size_id.',';
            }
            $sizes=rtrim($sizes,',');
            
            $html1 = ''; 
            $html2 = ''; 
            
            $html1 .= '<div class="col-md-12"><h4><b>Order Qty</h4></b></div>
            <div class="table-responsive">
             <table id="footable_1" class="table  table-bordered table-striped m-b-0  footable_1">
                    <thead>
                    <tr>
                        <th>Sr No</th>
                        <th>Color</th>';  
                           foreach ($SizeDetailList as $sz) 
                            {
                                $html1.='<th>'.$sz->size_name.'</th>';
                                 
                            }
                            $html1.=' 
                        <th>Total Qty</th>  
                        <th>Add/Remove</th>
                    </tr>
                    </thead>
                    <tbody>';
                $no=1;
                
                $html1 .='<tr>';
                $html1 .='
                <td><input type="text" name="id[]" value="'.$no.'" id="id0" style="width:50px;"/></td>
                <td>
                    <input type="text" name="color[]" class="color" value="" id="color" style="width:250px; height:30px;" onchange="CheckColorExist(this);" required /> 
                </td>';
                $n=1;
                foreach ($SizeDetailList as $row) 
                {
                    $html1.='<td><input type="number" name="s'.$n.'[]" class="size_id"  value="0" id="size_id0" style="width:80px; height:30px;" onchange="GetSizeQtyArray(this);" /></td>';
                    $n=$n+1;
                }
                $html1.='
                 <td>
                    <input type="number" name="order_qty[]" class="QTY" value="0" id="size_qty_total" style="width:80px; height:30px;""  /> 
                    <input type="hidden" name="size_array[]" class="size_array" value="'.$sizes.'" id="size_array" style="width:80px; height:30px;""  /> 
                    <input type="hidden" name="size_qty_array[]" class="size_qty_array" value="0" id="size_qty_array" style="width:80px; height:30px;""  /> 
                </td>
                <td>
                    <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                    <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" ></td>';
             $html1 .='</tr>
              </tbody>
            </table> 
            </div>
            </div>'; 
            
            $html2 .= '<div class="col-md-12"><h4><b>Stitching Qty</h4></b></div>
            <div class="table-responsive">
             <table id="footable_1" class="table  table-bordered table-striped m-b-0  footable_1">
                    <thead>
                    <tr>
                        <th>Sr No</th>
                        <th>Color</th>';  
                           foreach ($SizeDetailList as $sz) 
                            {
                                $html2.='<th>'.$sz->size_name.'</th>';
                            }
                            $html2.=' 
                        <th>Total Qty</th>   
                    </tr>
                    </thead>
                    <tbody>';
                $no=1;
                
                $html2 .='<tr>';
                $html2 .='
                <td><input type="text" name="id[]" value="'.$no.'" id="id0" style="width:50px;"/></td>
                <td>
                    <input type="text" name="stitching_color[]" class="stitching_color" value="" id="stitching_color" style="width:150px; height:30px;""  /> 
                </td>';
                $n=1;
                foreach ($SizeDetailList as $row) 
                {
                    $html2.='<td><input type="number" name="s'.$n.'stitching_[]" class="stitching_qty"  value="0" id="stitching_qty" style="width:80px; height:30px;" /></td>';
                    $n=$n+1;
                }
                $html2.='
                 <td>
                    <input type="number" name="order_stitching_qty[]" class="QTY" value="0" id="size_qty_total" style="width:80px; height:30px;""  /> 
                    <input type="hidden" name="size_stitching_array[]" class="size_stitching_array" value="'.$sizes.'" id="size_stitching_array" style="width:80px; height:30px;""  /> 
                    <input type="hidden" name="size_stitching_qty_array[]" class="size_stitching_qty_array" value="0" id="size_stitching_qty_array" style="width:80px; height:30px;""  /> 
             </tr>
              </tbody>
            </table> 
            </div> 
            </div>'; 
            
        return response()->json(['html1' => $html1, 'html2' => $html2]);
            
    }
    
    public function GetDepartmentType(Request $request)
    {
        $sample_type_id = $request->sample_type_id;
        $Data = DB::table('sample_type_master')->select('dept_type_id')->where('sample_type_id', '=', $sample_type_id)->first();
        return response()->json(['dept_type_id' => $Data->dept_type_id]);
    }
    
    public function rptSamplingStatus(Request $request)
    { 
        $Buyerlist = DB::table('ledger_master')->where('bt_id','=', 2)->where('delflag','=', '0')->get();
        
        $filter = '';
        
        $Ac_code = isset($request->Ac_code) ? $request->Ac_code : 0;
        $from_date = isset($request->from_date) ? $request->from_date : date('Y-m-01');
        $to_date = isset($request->to_date) ? $request->to_date : date('Y-m-d');
        
        if($request->Ac_code > 0)
        {
            $filter .= " AND sample_indent_master.Ac_code=".$request->Ac_code;
        }
        
        if($request->from_date != '' && $request->to_date != '')
        {
            $filter .= " AND sample_indent_master.sample_required_date BETWEEN '".$request->from_date."' AND '".$request->to_date."'";
        }
       // DB::enableQueryLog();
        $SampleDetailList = DB::SELECT("SELECT 
                    ledger_master.ac_short_name,
                    main_style_master.mainstyle_name,
                    sub_style_master.substyle_name,
                    SUM(size_qty_total) AS no_of_sample,
                    sample_type_master.sample_type_name,
                    usermaster.username,
                    department_type.dept_type_name,
                    sample_cad_dept_master.delivery_date,
                    sample_customer_feedback_master.cust_comments, 
                    sample_indent_master.*,sample_qc_dept_master.actual_etd,sample_cad_dept_master.material_avaliable_date,
                    DATEDIFF(sample_qc_dept_master.actual_etd, sample_cad_dept_master.material_avaliable_date) AS TAT,
                    (SELECT material_received_status.material_received_status_name 
                     FROM sample_cad_dept_detail 
                     LEFT JOIN material_received_status 
                     ON material_received_status.material_received_status_id = sample_cad_dept_detail.material_received_status_id
                     WHERE sample_cad_dept_detail.sample_indent_code = sample_indent_master.sample_indent_code
                     AND sample_cad_dept_detail.bom_type_id = 1
                     LIMIT 1) AS fabric_material_received_status,
            
                    (SELECT material_received_status.material_received_status_name 
                     FROM sample_cad_dept_detail 
                     LEFT JOIN material_received_status 
                     ON material_received_status.material_received_status_id = sample_cad_dept_detail.material_received_status_id
                     WHERE sample_cad_dept_detail.sample_indent_code = sample_indent_master.sample_indent_code
                     AND sample_cad_dept_detail.bom_type_id = 2
                     LIMIT 1) AS sewing_trims_material_received_status,
            
                    (SELECT material_received_status.material_received_status_name 
                     FROM sample_cad_dept_detail 
                     LEFT JOIN material_received_status 
                     ON material_received_status.material_received_status_id = sample_cad_dept_detail.material_received_status_id
                     WHERE sample_cad_dept_detail.sample_indent_code = sample_indent_master.sample_indent_code
                     AND sample_cad_dept_detail.bom_type_id = 3
                     LIMIT 1) AS packing_trims_material_received_status
                FROM 
                    sample_indent_master 
                LEFT JOIN 
                    ledger_master ON ledger_master.ac_code = sample_indent_master.Ac_code 
                LEFT JOIN 
                    main_style_master ON main_style_master.mainstyle_id = sample_indent_master.mainstyle_id 
                LEFT JOIN 
                    sub_style_master ON sub_style_master.substyle_id = sample_indent_master.substyle_id 
                LEFT JOIN 
                    sample_indent_order ON sample_indent_order.sample_indent_code = sample_indent_master.sample_indent_code 
                LEFT JOIN 
                    sample_type_master ON sample_type_master.sample_type_id = sample_indent_master.sample_type_id 
                LEFT JOIN 
                    usermaster ON usermaster.userId = sample_indent_master.userId 
                LEFT JOIN 
                    department_type ON department_type.dept_type_id = sample_indent_master.dept_type_id 
                LEFT JOIN 
                    sample_cad_dept_master ON sample_cad_dept_master.sample_indent_code = sample_indent_master.sample_indent_code 
                LEFT JOIN 
                    sample_qc_dept_master ON sample_qc_dept_master.sample_indent_code = sample_indent_master.sample_indent_code 
                LEFT JOIN 
                    sample_customer_feedback_master ON sample_customer_feedback_master.sample_indent_code = sample_indent_master.sample_indent_code  
                WHERE 
                    sample_indent_master.delflag = 0 ".$filter."
                GROUP BY 
                    sample_indent_master.sample_indent_code");



//dd(DB::getQueryLog());
                          
        return view('rptSamplingStatus',compact('SampleDetailList', 'Buyerlist', 'Ac_code','from_date','to_date')); 
    }
    
    
    
    public function SampleIndentPrint($sample_indent_code)
    {
        
       $SampleList = SampleIndentModel::join('usermaster', 'usermaster.userId', '=', 'sample_indent_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sample_indent_master.Ac_code', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sample_indent_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sample_indent_master.substyle_id', 'left outer')  
        ->join('brand_master', 'brand_master.brand_id', '=', 'sample_indent_master.brand_id', 'left outer')  
        ->join('sample_type_master', 'sample_type_master.sample_type_id', '=', 'sample_indent_master.sample_type_id', 'left outer')     
        ->join('department_type', 'department_type.dept_type_id', '=', 'sample_indent_master.dept_type_id', 'left outer')     
        ->where('sample_indent_master.delflag','=', '0')
        ->where('sample_indent_master.sample_indent_code','=', $sample_indent_code)
        ->get(['sample_indent_master.*','usermaster.username','ledger_master.Ac_name','main_style_master.mainstyle_name',
                'sub_style_master.substyle_name', 'sample_type_master.sample_type_name','department_type.dept_type_name', 'brand_master.brand_name']);

    
        return view('SampleIndentPrint', compact('SampleList'));     
    }

}
