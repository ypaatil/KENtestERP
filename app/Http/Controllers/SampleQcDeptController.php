<?php

namespace App\Http\Controllers;

use App\Models\DepartmentTypeModel;
use App\Models\SampleQcDeptModel;
use App\Models\SampleQcDeptDetailModel; 
use App\Models\SampleIndentModel; 
use App\Models\SampleIndentOrderModel; 
use App\Models\SampleQcDeptStitchingModel; 
use App\Models\SampleQcDeptStitchingSizeDetailModel; 
use App\Models\SizeModel;
use App\Models\ItemModel;
use App\Models\SizeDetailModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class SampleQcDeptController extends Controller
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
        ->where('form_id', '274')
        ->first();


        $data = SampleQcDeptModel::join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sample_qc_dept_master.mainstyle_id')
            ->join('ledger_master', 'ledger_master.ac_code', '=', 'sample_qc_dept_master.ac_code')
            ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'sample_qc_dept_master.brand_id')
            ->join('sub_style_master', 'sub_style_master.mainstyle_id', '=', 'sample_qc_dept_master.mainstyle_id')
            ->join('sample_type_master', 'sample_type_master.sample_type_id', '=', 'sample_qc_dept_master.sample_type_id')
            ->join('sample_cad_dept_master', 'sample_cad_dept_master.sample_indent_code', '=', 'sample_qc_dept_master.sample_indent_code')
            ->join('usermaster', 'usermaster.userId', '=', 'sample_cad_dept_master.userId')
            ->where('sample_qc_dept_master.delflag', '=', '0')
            ->select(
                'sample_qc_dept_master.*','sample_cad_dept_master.delivery_date','sample_cad_dept_master.material_avaliable_date','usermaster.username',
                'ledger_master.ac_short_name','sub_style_master.substyle_name','brand_master.brand_name',
                'main_style_master.mainstyle_name','sample_type_master.sample_type_name',
                DB::raw('(SELECT sum( DISTINCT size_qty_total) FROM sample_qc_dept_stitching_size_detail2 
                          WHERE sample_qc_dept_stitching_size_detail2.sample_qc_dept_id = sample_qc_dept_master.sample_qc_dept_id) as total_qty')
            )
            ->groupBy('sample_qc_dept_master.sample_qc_dept_id')
            ->orderBy('sample_qc_dept_master.sample_qc_dept_id', 'DESC')
            ->get();
            
        return view('SampleQcDeptMasterList', compact('data','chekform'));
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
                    ->join('sample_cad_dept_master', 'sample_cad_dept_master.sample_indent_code', '=', 'sample_indent_master.sample_indent_code')
                    ->join('sample_cad_dept_detail', 'sample_cad_dept_detail.sample_cad_dept_id', '=', 'sample_cad_dept_master.sample_cad_dept_id')
                    ->where('sample_indent_master.delflag', '=', '0')
                    ->whereIn('sample_cad_dept_detail.bom_type_id', [1, 2, 3])
                    ->groupBy('sample_indent_master.sample_indent_code')
                    ->havingRaw('COUNT(CASE WHEN sample_cad_dept_detail.material_received_status_id = 1 THEN 1 END) = 3') 
                    ->get();


        $BrandList = DB::table('brand_master')->where('delflag','=', '0')->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        
        return view('SampleQcDeptMaster',compact('Buyerlist','MainStylelist','SubStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist','SampleIndentMasterList','MaterialReceivedList','BrandList'));
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
                'sample_qc_dept_date'=>$request->sample_qc_dept_date, 
                'Ac_code'=>$request->Ac_code,
                'brand_id'=>$request->brand_id,
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id, 
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,
                'sample_type_id'=>$request->sample_type_id,
                'dept_type_id'=>$request->dept_type_id,
                'sz_code'=>$request->sz_code,
                'userId'=>Session::get('userId'),
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'actual_etd'=>$request->actual_etd
             );
         
            SampleQcDeptModel::insert($data1);
            $maxId = SampleQcDeptModel::max('sample_qc_dept_id');

 
            $color = $request->color;
            
            if(!empty($color)) 
            {    
                for($x=0; $x<count($color); $x++) 
                {
                    if($request->order_qty[$x] > 0)
                    {
                        $data2 =array
                        ( 
                        'sample_qc_dept_id'=>$maxId, 
                        'sample_indent_code'=>$request->sample_indent_code,
                        'sample_qc_dept_date'=>$request->sample_qc_dept_date,
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
                       
                            'sample_qc_dept_id'=>$maxId, 
                            'sample_indent_code'=>$request->sample_indent_code,
                            'sample_qc_dept_date'=>$request->sample_qc_dept_date,
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
                  
                          SampleQcDeptStitchingModel::insert($data2);
                          SampleQcDeptStitchingSizeDetailModel::insert($data3);
                    }  
                } 
            }
                
            $InsertSizeData=DB::select('call AddSizeQtyFromSampleStitchingOrder('.$maxId.')');
            
            $upload_attachment = $request->upload_attachment;
            if(!empty($upload_attachment)) 
            {
                foreach($upload_attachment as $index => $attachmentName) 
                {
                    if ($request->hasFile('upload_attachment.' . $index)) {
                        $attachment = $request->file('upload_attachment')[$index];
                        $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                        $location = public_path('uploads/Sample/');
                        if (file_exists('uploads/Sample/'.$fileName))
                        {
                             $url = "uploads/Sample/".$fileName;
                             unlink($url);
                        }
                        $attachment->move($location,$fileName);
                        DB::table('sample_qc_dept_attachment')->insert([
                            "sample_indent_code"=>$request->sample_indent_code,
                            "sample_qc_dept_id"=>$maxId,
                            "sample_qc_dept_date"=>$request->sample_qc_dept_date,
                            "attachment_name"=>$request->attachment_name[$index],
                            "upload_attachment"=>$fileName
                        ]);
                    }
                }
            }   
                
        
        return redirect()->route('SampleQcDept.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SampleQcDept  $SampleQcDept
     * @return \Illuminate\Http\Response
     */
    public function show(SampleQcDept $SampleQcDept)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SampleQcDept  $SampleQcDept
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $SampleQcDept = SampleQcDeptModel::find($id);  
        
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $SampleQcDept->sz_code)->get();
        $Buyerlist = DB::table('ledger_master')->where('bt_id','=', 2)->where('delflag','=', '0')->get();
        $MainStylelist = DB::table('main_style_master')->where('delflag','=', '0')->get();
        $SubStylelist = DB::table('sub_style_master')->where('delflag','=', '0')->get();
        $SampleTypelist = DB::table('sample_type_master')->where('delflag','=', '0')->get();
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        $SizeGroupList = SizeModel::where('size_master.delflag','=', '0')->get();
        $BOMTypelist = DB::table('bom_types')->where('bom_type_id','!=', 4)->get();
        $MaterialReceivedList = DB::table('material_received_status')->where('delflag','=', '0')->get();
        $SampleIndentList = SampleIndentModel::join('sample_cad_dept_master', 'sample_cad_dept_master.sample_indent_code', '=', 'sample_indent_master.sample_indent_code')->where('sample_indent_master.sample_indent_code','=', $SampleQcDept->sample_indent_code)->groupBy('sample_indent_master.sample_indent_code')->get();
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $SampleQcDept->sample_indent_code)->get();
        $SampleStitchingDetailList = SampleQcDeptStitchingModel::where('sample_qc_dept_id','=', $SampleQcDept->sample_qc_dept_id)->get();
        $SampleStitchingAttachmentList = DB::table('sample_qc_dept_attachment')->where('sample_qc_dept_id','=',  $SampleQcDept->sample_qc_dept_id)->get();
        $BrandList = DB::table('brand_master')->where('delflag','=', '0')->get();
        
        return view('SampleQcDeptMasterEdit',compact('SampleQcDept', 'SizeDetailList', 'SampleIndentList', 'SampleIndentDetailList', 'Buyerlist','MainStylelist','SubStylelist','SampleTypelist','SizeGroupList','BOMTypelist','DepartmentTypelist','MaterialReceivedList','SampleStitchingDetailList','SampleStitchingAttachmentList', 'BrandList')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SampleQcDept  $SampleQcDept
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
        $data1=array
        ( 
            'sample_indent_code'=>$request->sample_indent_code, 
            'sample_qc_dept_date'=>$request->sample_qc_dept_date, 
            'Ac_code'=>$request->Ac_code,
             'brand_id'=>$request->brand_id,
            'mainstyle_id'=>$request->mainstyle_id,
            'substyle_id'=>$request->substyle_id, 
            'style_description'=>$request->style_description,
            'sam'=>$request->sam,
            'sample_type_id'=>$request->sample_type_id,
            'dept_type_id'=>$request->dept_type_id,
            'sz_code'=>$request->sz_code,
            'userId'=>Session::get('userId'),
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
        );
        
        $SampleQcDeptList = SampleQcDeptModel::findOrFail($request->sample_qc_dept_id);
        $SampleQcDeptList->fill($data1)->save(); 
        
        DB::table('sample_qc_dept_master')
        ->where('sample_indent_code', $request->sample_indent_code)
        ->update([
            'actual_etd'=>$request->actual_etd
        ]);
    
        $color = $request->color;
        
        if(!empty($color)) 
        {    
            SampleQcDeptStitchingModel::where('sample_qc_dept_id', $request->sample_qc_dept_id)->delete();
            SampleQcDeptStitchingSizeDetailModel::where('sample_qc_dept_id', $request->sample_qc_dept_id)->delete();  

            for($x=0; $x<count($color); $x++) 
            {
                if($request->order_qty[$x] > 0)
                {
                    $data2 =array
                    ( 
                    'sample_qc_dept_id'=>$request->sample_qc_dept_id, 
                    'sample_indent_code'=>$request->sample_indent_code,
                    'sample_qc_dept_date'=>$request->sample_qc_dept_date,
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
                   
                        'sample_qc_dept_id'=>$request->sample_qc_dept_id, 
                        'sample_indent_code'=>$request->sample_indent_code,
                        'sample_qc_dept_date'=>$request->sample_qc_dept_date,
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
              
                      SampleQcDeptStitchingModel::insert($data2);
                      SampleQcDeptStitchingSizeDetailModel::insert($data3);
                }
            } 
        }
        
        $InsertSizeData=DB::select('call AddSizeQtyFromSampleStitchingOrder('.$request->sample_qc_dept_id.')');
            
        $upload_attachment = $request->file('upload_attachment'); 
       
        if (!empty($upload_attachment)) {
            foreach ($upload_attachment as $index => $attachment) {
                if ($attachment && $attachment->isValid()) {
                    $fileName = time() . '_' . $attachment->getClientOriginalName();
                    $location = public_path('uploads/Sample/');
                    
                    // Log file name and location
                    \Log::info("Processing file: $fileName to $location");
        
                    // Move the new file to the specified location
                    $attachment->move($location, $fileName);
                    \Log::info("Moved file to: $location$fileName");
        
                    // Insert the file details into the database
                    DB::table('sample_qc_dept_attachment')->insert([
                        "sample_indent_code" => $request->sample_indent_code,
                        "sample_qc_dept_id" => $request->sample_qc_dept_id,
                        "sample_qc_dept_date" => $request->sample_qc_dept_date,
                        "attachment_name"=>$request->attachment_name[$index],
                        "upload_attachment" => $fileName
                    ]);
        
                    \Log::info("Inserted file details into the database: $fileName");
                } else {
                    \Log::warning("File at index $index is not valid or missing.");
                }
            }
        } else {
            \Log::warning("No files found in the request.");
        }

        return redirect()->route('SampleQcDept.index')->with('message', 'Update Record Succesfully');
    }
    
    public function DeleteSampleQcAttachment(Request $request)
    {
        
        DB::table('sample_qc_dept_attachment')->where('sample_qc_dept_id', '=', $request->sample_qc_dept_id)->where('upload_attachment', '=', $request->upload_attachment)->delete();
        $file_path = public_path('uploads/Sample/'.$request->upload_attachment);
        if (file_exists($file_path))
        {
            unlink($file_path);
        }
        return 1;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SampleQcDept  $SampleQcDept
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        SampleQcDeptModel::where('sample_qc_dept_id', $id)->delete();  
        SampleQcDeptStitchingModel::where('sample_qc_dept_id', $id)->delete(); 
        SampleQcDeptStitchingSizeDetailModel::where('sample_qc_dept_id', $id)->delete();  
        DB::table('sample_qc_dept_attachment')->where('sample_qc_dept_id', $id)->delete();  
        DB::table('sample_qc_dept_stitching_size_detail2')->where('sample_qc_dept_id', $id)->delete();  

        return redirect()->route('SampleQcDept.index')->with('message', 'Deleted Record Succesfully');
    }
    
    public function GetSampleIndentMasterQCData(Request $request)
    {
        $sample_indent_code = $request->sample_indent_code;
        $MasterData = DB::table('sample_indent_master')->select('*')->where('sample_indent_code', '=', $sample_indent_code)->first();
        $SampleIndentDetailList = SampleIndentOrderModel::where('sample_indent_code','=', $MasterData->sample_indent_code)->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $MasterData->sz_code)->get();
        
        $qcData = DB::table('sample_qc_dept_master')->select('actual_etd')->where('sample_indent_code', '=', $sample_indent_code)->get();
        
        $actual_etd = isset($qcData[0]->actual_etd) ? $qcData[0]->actual_etd : "";
        
        $existingData = DB::SELECT("SELECT sum(DISTINCT size_qty_total) as existing_qty FROM sample_qc_dept_stitching_size_detail2 WHERE sample_indent_code='".$MasterData->sample_indent_code."'");
        
        $existing_qty = isset($existingData[0]->existing_qty) ? $existingData[0]->existing_qty : 0;
        
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
                    if(count($SampleIndentDetailList) > 0)
                    {
                        $no = 1; 
                        $n = 1;
                        foreach($SampleIndentDetailList as $List)
                        {
                            if($existing_qty > $List->size_qty_total)
                            {
                                $max_Qty = $existing_qty - $List->size_qty_total;
                            }
                            else
                            {
                                $max_Qty = $List->size_qty_total - $existing_qty;
                            }
                            
                            $n = 1;  
                            $SizeQtyList = explode(',', $List->size_qty_array); 
                            $html1 .= '<tr>
                                <td><input type="text" name="id[]" value="'.$no.'" id="id'.$no.'" style="width:50px;" readonly /></td>
                                <td><input type="text" name="color[]" class="color" value="'.$List->color.'" id="color'.$no.'" style="width:150px; height:30px;" readonly /></td>';
                                foreach($SizeQtyList as $key => $szQty)
                                {
                                    $html1 .= '<td><input type="number" name="s'.$n.'[]" class="size_id" value="0" max="'.($max_Qty).'" id="size_id'.$no.'_'.$n.'" style="width:80px; height:30px;" onkeyup="GetSizeQtyArray(this);" /></td>';
                                     $n++;
                                }
                                $html1 .= '<td>
                                    <input type="number" name="order_qty[]" class="QTY" value="0" id="size_qty_total'.$no.'" min="1" max="'.($max_Qty).'" style="width:80px; height:30px;" readonly />
                                    <input type="hidden" name="size_array[]" class="size_array" value="'.$sizes.'" id="size_array'.$no.'" />
                                    <input type="hidden" name="size_qty_array[]" class="size_qty_array" value="" id="size_qty_array'.$no.'" />
                                </td>
                            </tr>';
                            $no++; 
                        }
                    }
                $html1 .= '</tbody>
            </table>';
            
        return response()->json(['MasterData' => $MasterData, 'DetailHtml'=>$html, 'StitchingHtml'=>$html1, 'actual_etd'=>$actual_etd]);
    }
    
}
