<?php

namespace App\Http\Controllers;

use App\Models\DepartmentTypeModel;
use App\Models\SampleCadDeptModel;
use App\Models\SampleCadDeptDetailModel; 
use App\Models\SampleIndentModel; 
use App\Models\SampleIndentOrderModel; 
use App\Models\SizeModel;
use App\Models\ItemModel;
use App\Models\SizeDetailModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SampleCadDeptController extends Controller
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
        ->where('form_id', '273')
        ->first();


        // $data = SampleCadDeptModel::join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sample_cad_dept_master.mainstyle_id')
        // ->join('ledger_master', 'ledger_master.ac_code', '=', 'sample_cad_dept_master.Ac_code')
        // ->where('sample_cad_dept_master.delflag','=', '0')
        // ->get(['sample_cad_dept_master.*','ledger_master.ac_name','main_style_master.mainstyle_name']);
//DB::enableQueryLog();
        $data = SampleCadDeptModel::leftjoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'sample_cad_dept_master.mainstyle_id')
            ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'sample_cad_dept_master.ac_code')
            ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'sample_cad_dept_master.brand_id')
            ->leftjoin('sub_style_master', 'sub_style_master.mainstyle_id', '=', 'sample_cad_dept_master.mainstyle_id')
            ->leftjoin('sample_type_master', 'sample_type_master.sample_type_id', '=', 'sample_cad_dept_master.sample_type_id')
            ->leftjoin('usermaster', 'usermaster.userId', '=', 'sample_cad_dept_master.userId')
            ->where('sample_cad_dept_master.delflag', '=', '0')
            ->select(
                'sample_cad_dept_master.*','brand_master.brand_name',
                'ledger_master.ac_short_name','sub_style_master.substyle_name',
                'main_style_master.mainstyle_name','sample_type_master.sample_type_name','usermaster.username',
                DB::raw('(SELECT sum(size_qty_total) FROM sample_indent_order 
                          WHERE sample_indent_order.sample_indent_code = sample_cad_dept_master.sample_indent_code GROUP BY sample_cad_dept_master.sample_cad_dept_id) as total_qty'),
                          
                DB::raw('(SELECT material_received_status.material_received_status_name FROM sample_cad_dept_detail INNER JOIN material_received_status ON material_received_status.material_received_status_id = sample_cad_dept_detail.material_received_status_id
                          WHERE sample_cad_dept_detail.sample_indent_code = sample_cad_dept_master.sample_indent_code AND sample_cad_dept_detail.bom_type_id = 1  GROUP BY sample_cad_dept_detail.sample_indent_code) as fabric_status'),
                          
                DB::raw('(SELECT material_received_status.material_received_status_name FROM sample_cad_dept_detail INNER JOIN material_received_status ON material_received_status.material_received_status_id = sample_cad_dept_detail.material_received_status_id
                          WHERE sample_cad_dept_detail.sample_indent_code = sample_cad_dept_master.sample_indent_code AND sample_cad_dept_detail.bom_type_id = 2 GROUP BY sample_cad_dept_detail.sample_indent_code) as sewing_status'),
                          
                DB::raw('(SELECT material_received_status.material_received_status_name FROM sample_cad_dept_detail INNER JOIN material_received_status ON material_received_status.material_received_status_id = sample_cad_dept_detail.material_received_status_id
                          WHERE sample_cad_dept_detail.sample_indent_code = sample_cad_dept_master.sample_indent_code AND sample_cad_dept_detail.bom_type_id = 3 GROUP BY sample_cad_dept_detail.sample_indent_code) as packing_status')
            ) 
            ->groupBy('sample_cad_dept_master.sample_cad_dept_id')  
            ->orderBy('sample_cad_dept_master.sample_cad_dept_id', 'DESC')
            ->get();

//dd(DB::getQueryLog());
        return view('SampleCadDeptMasterList', compact('data','chekform'));
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
        $SampleIndentMasterList = DB::table('sample_indent_master')
            ->whereNotIn('sample_indent_code', function($query) {
                $query->select('sample_indent_code')
                      ->from('sample_cad_dept_master');
            })
            ->where('delflag', '=', '0')
            ->get();

        $BrandList = DB::table('brand_master')->where('delflag','=', '0')->get(); 
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        
        return view('SampleCadDeptMaster',compact('Buyerlist','MainStylelist','SubStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist','SampleIndentMasterList','MaterialReceivedList', 'BrandList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
            $data1=array
            (
                'sample_indent_code'=>$request->sample_indent_code, 
                'sample_cad_dept_date'=>$request->sample_cad_dept_date, 
                'Ac_code'=>$request->Ac_code,
                'brand_id'=>$request->brand_id,
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id, 
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,
                'sample_type_id'=>$request->sample_type_id,
                'dept_type_id'=>$request->dept_type_id,
                'sz_code'=>$request->sz_code,
                'userId'=>$request->userId,
                'remark'=>$request->remark,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'delivery_date'=>$request->delivery_date,
                'material_avaliable_date'=>$request->material_avaliable_date
             );
         
            SampleCadDeptModel::insert($data1);
            $maxId = SampleCadDeptModel::max('sample_cad_dept_id');


            $material_received_status_id = $request->material_received_status_id;
            if (is_array($material_received_status_id) || $material_received_status_id instanceof Countable)
            {
                if(count($material_received_status_id) > 0)
                {   
                    for($x=0; $x<count($material_received_status_id); $x++) 
                    { 
                        $data2=array
                        ( 
                            'sample_cad_dept_id'=>$maxId,
                            'sample_indent_code'=>$request->sample_indent_code,
                            'sample_cad_dept_date'=>$request->sample_cad_dept_date,
                            'delivery_date'=>$request->delivery_date, 
                            'bom_type_id'=>$request->bom_type_id[$x],
                            'material_received_status_id'=>$request->material_received_status_id[$x]
                        );
                       
                        SampleCadDeptDetailModel::insert($data2);
                    }
                } 
            }
        
        return redirect()->route('SampleCadDept.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SampleCadDept  $SampleCadDept
     * @return \Illuminate\Http\Response
     */
    public function show(SampleCadDept $SampleCadDept)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SampleCadDept  $SampleCadDept
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $SampleCadDept = SampleCadDeptModel::find($id);  
        
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $SampleCadDept->sz_code)->get();
        $Buyerlist = DB::table('ledger_master')->where('bt_id','=', 2)->where('delflag','=', '0')->get();
        $MainStylelist = DB::table('main_style_master')->where('delflag','=', '0')->get();
        $SubStylelist = DB::table('sub_style_master')->where('delflag','=', '0')->get();
        $SampleTypelist = DB::table('sample_type_master')->where('delflag','=', '0')->get();
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        $SizeGroupList = SizeModel::where('size_master.delflag','=', '0')->get();
        $BOMTypelist = DB::table('bom_types')->where('bom_type_id','!=', 4)->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $SampleIndentList = SampleIndentModel::where('sample_indent_code','=', $SampleCadDept->sample_indent_code)->get();
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $SampleCadDept->sample_indent_code)->get();
        $BrandList = DB::table('brand_master')->where('delflag','=', '0')->get(); 
        
        return view('SampleCadDeptMasterEdit',compact('SampleCadDept', 'SizeDetailList', 'SampleIndentList', 'SampleIndentDetailList', 'Buyerlist','MainStylelist','SubStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist','MaterialReceivedList','BrandList')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SampleCadDept  $SampleCadDept
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
        $data1=array( 
            'sample_indent_code'=>$request->sample_indent_code, 
            'sample_cad_dept_date'=>$request->sample_cad_dept_date, 
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
            'delivery_date'=>$request->delivery_date,
            'material_avaliable_date'=>$request->material_avaliable_date,
            'updated_at'=>date("Y-m-d H:i:s")
        );
        
        $SampleCadDeptList = SampleCadDeptModel::findOrFail($request->sample_cad_dept_id);
        $SampleCadDeptList->fill($data1)->save(); 
        
        $material_received_status_id = $request->material_received_status_id;
        if (is_array($material_received_status_id) || $material_received_status_id instanceof Countable)
        { 
            
            DB::table('sample_cad_dept_detail')->where('sample_cad_dept_id', $request->sample_cad_dept_id)->delete();
        
            for($x=0; $x<count($material_received_status_id); $x++) 
            { 
                $data2=array
                ( 
                    'sample_cad_dept_id'=>$request->sample_cad_dept_id,
                    'sample_indent_code'=>$request->sample_indent_code,
                    'sample_cad_dept_date'=>$request->sample_cad_dept_date,
                    'delivery_date'=>$request->delivery_date, 
                    'bom_type_id'=>$request->bom_type_id[$x],
                    'material_received_status_id'=>$request->material_received_status_id[$x]
                );
               
                SampleCadDeptDetailModel::insert($data2);
            }
        } 
        
        return redirect()->route('SampleCadDept.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SampleCadDept  $SampleCadDept
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        SampleCadDeptModel::where('sample_cad_dept_id', $id)->delete(); 
        SampleCadDeptDetailModel::where('sample_cad_dept_id', $id)->delete();  

        return redirect()->route('SampleCadDept.index')->with('message', 'Deleted Record Succesfully');
    }
    
    public function GetSampleIndentMasterData(Request $request)
    {
        $sample_indent_code = $request->sample_indent_code;
        $MasterData = DB::table('sample_indent_master')->select('*')->where('sample_indent_code', '=', $sample_indent_code)->first();
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $MasterData->sample_indent_code)->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $MasterData->sz_code)->get();
        
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
                                <td><input type="text" name="id[]" value="'.$no.'" id="id'.$no.'" style="width:50px;" readonly /></td>
                                <td><input type="text" name="color[]" class="color" value="'.$List->color.'" id="color'.$no.'" style="width:150px; height:30px;" readonly/></td>';
                                foreach($SizeQtyList as $key => $szQty)
                                {
                                    $html .= '<td><input type="number" name="s'.$n.'[]" class="size_id" value="'.$szQty.'" id="size_id'.$no.'_'.$n.'" style="width:80px; height:30px;" onchange="GetSizeQtyArray(this);" readonly /></td>';
                                     $n++;
                                }
                                $html .= '<td>
                                    <input type="number" name="order_qty[]" class="QTY" value="'.$List->size_qty_total.'" id="size_qty_total'.$no.'" style="width:80px; height:30px;" readonly />
                                    <input type="hidden" name="size_array[]" class="size_array" value="'.$List->size_array.'" id="size_array'.$no.'" />
                                    <input type="hidden" name="size_qty_array[]" class="size_qty_array" value="'.$List->size_qty_array.'" id="size_qty_array'.$no.'" />
                                </td>
                            </tr>';
                            $no++; 
                        }
                    }
                $html .= '</tbody>
            </table>';
                        
        return response()->json(['MasterData' => $MasterData, 'DetailHtml'=>$html]);
    }
}
