<?php

namespace App\Http\Controllers;
 
use App\Models\ItemModel; 
use App\Models\OpportunityModel;
use App\Models\OpportunityDetailModel;
use App\Models\PerticularModel;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\MainStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class OpportunityController extends Controller
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

        $OpportunityData = OpportunityModel::select('opportunity_master.*','c1.buyer_name', 'c2.buyer_brand','usermaster.username')
                            ->leftjoin('crm_master as c1', 'c1.crm_id', '=', 'opportunity_master.Ac_code') 
                            ->leftjoin('crm_master as c2', 'c2.crm_id', '=', 'opportunity_master.brand_id') 
                            ->leftjoin('usermaster', 'usermaster.userId', '=', 'opportunity_master.userId') 
                            ->where('opportunity_master.delflag', '=', '0')
                            ->orderBy('opportunity_master.opportunity_id', 'DESC')
                            ->get();

        $BuyerList = DB::SELECT("SELECT crm_id, buyer_name, buyer_brand FROM crm_master WHERE delflag=0");  
        $StatgeList = DB::SELECT("SELECT * FROM  opportunity_stage WHERE delflag=0"); 
        $GenderList = DB::SELECT("SELECT * FROM gender_master WHERE delflag=0"); 
        $MainStyleList = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0"); 
        $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0"); 
        
        return view('OpportunityMasterList', compact('OpportunityData','chekform','BuyerList','StatgeList', 'GenderList', 'MainStyleList', 'CurrencyList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $BuyerList = DB::SELECT("SELECT crm_id, buyer_name, buyer_brand FROM crm_master WHERE delflag=0");  
        $StatgeList = DB::SELECT("SELECT * FROM  opportunity_stage WHERE delflag=0"); 
        $GenderList = DB::SELECT("SELECT * FROM gender_master WHERE delflag=0"); 
        $MainStyleList = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0"); 
        $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0"); 
        
        return view('OpportunityMaster',compact('BuyerList','StatgeList', 'GenderList', 'MainStyleList', 'CurrencyList'));
    
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
                'opportunity_date'=>$request->opportunity_date, 
                'Ac_code'=>$request->Ac_code, 
                'brand_id'=>$request->brand_id, 
                'userId'=>Session::get('userId'), 
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'delflag'=>0
             );
         
            OpportunityModel::insert($data1);  
            $maxId = OpportunityModel::max('opportunity_id');
            $main_style_id = $request->main_style_id;
            if(count($main_style_id)>0)
            {    
                    
                for($x=0; $x<count($main_style_id); $x++) 
                {
                   
                    $data2 =array
                    ( 
                    'opportunity_id'=>$maxId, 
                    'main_style_id'=>$request->main_style_id[$x],
                    'style_name'=>$request->style_name[$x],
                    'style_description'=>$request->style_description[$x],   
                    'product_url'=>$request->product_url[$x],  
                    'gender_id'=>$request->gender_id[$x],  
                    'fabric_details'=>$request->fabric_details[$x],  
                    'size_range'=>$request->size_range[$x],  
                    'sam'=>$request->sam[$x],  
                    'quantity'=>$request->quantity[$x],  
                    'cur_id'=>$request->cur_id[$x],  
                    'fob_rate'=>$request->fob_rate[$x],  
                    'exchange_rate'=>$request->exchange_rate[$x],  
                    'fob_rate_inr'=>$request->fob_rate_inr[$x],  
                    'CM'=>$request->CM[$x],  
                    'OH'=>$request->OH[$x],  
                    'P'=>$request->P[$x],  
                    'CMOHP_value'=>$request->CMOHP_value[$x],  
                    'CMOHP_min'=>$request->CMOHP_min[$x],  
                    'total_amount_inr'=>$request->total_amount_inr[$x],  
                    'total_minutes'=>$request->total_minutes[$x],  
                    'opportunity_stage_id'=>$request->opportunity_stage_id[$x],  
                    'remark'=>$request->remark[$x]
                   );
                  //DB::enableQueryLog();
                    OpportunityDetailModel::insert($data2); 
                 //dd(DB::getQueryLog());
                } 
            }
            
            
            $product_image = $request->product_image;
            if(!empty($product_image)) 
            {
                foreach($product_image as $index => $attachmentName) 
                {
                    if ($request->hasFile('product_image.' . $index)) {
                        $attachment = $request->file('product_image')[$index];
                        $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                        $location = public_path('uploads/Opportunity/');
                        if (file_exists('uploads/Opportunity/'.$fileName))
                        {
                             $url = "uploads/Opportunity/".$fileName;
                             unlink($url);
                        }
                        $attachment->move($location,$fileName);
                        DB::table('opportunity_details')->where('opportunity_id', $maxId)->update(['product_image' => $fileName]); 
                    }
                }
            }  
        
        return redirect()->route('Opportunity.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Opportunity  $Opportunity
     * @return \Illuminate\Http\Response
     */
    public function show(Opportunity $Opportunity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Opportunity  $Opportunity
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $OpportunityMaster = OpportunityModel::find($id);   
        $OpportunityDetails = OpportunityDetailModel::select('opportunity_details.*','main_style_master.mainstyle_name')->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'opportunity_details.main_style_id')->where('opportunity_id', $id)->get();   
       
        $BuyerList = DB::SELECT("SELECT crm_id, buyer_name, buyer_brand FROM crm_master WHERE delflag=0");  
        $StatgeList = DB::SELECT("SELECT * FROM  opportunity_stage WHERE delflag=0"); 
        $GenderList = DB::SELECT("SELECT * FROM gender_master WHERE delflag=0"); 
        $MainStyleList = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0"); 
        $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0"); 
        
        return view('OpportunityMasterEdit',compact('OpportunityMaster', 'BuyerList','StatgeList', 'GenderList','MainStyleList', 'CurrencyList','OpportunityDetails')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Opportunity  $Opportunity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $data1=array
        (   
            'opportunity_id'=>$request->opportunity_id, 
            'opportunity_date'=>$request->opportunity_date, 
            'Ac_code'=>$request->Ac_code, 
            'brand_id'=>$request->brand_id, 
            'userId'=>Session::get('userId'), 
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'delflag'=>0
         );
         
        $OpportunityList = OpportunityModel::findOrFail($request->opportunity_id);
        $OpportunityList->fill($data1)->save();
        
        $contactName = $request->contactName;
        
        DB::table('opportunity_details')->where('opportunity_id', $request->opportunity_id)->delete();
        
        $main_style_id = $request->main_style_id;
        if(count($main_style_id)>0)
        {    
                
            for($x=0; $x<count($main_style_id); $x++) 
            {
               
                $data2 =array
                ( 
                    'opportunity_id'=>$request->opportunity_id, 
                    'main_style_id'=>$request->main_style_id[$x],
                    'style_name'=>$request->style_name[$x],
                    'style_description'=>$request->style_description[$x],  
                    'product_url'=>$request->product_url[$x],  
                    'gender_id'=>$request->gender_id[$x],  
                    'fabric_details'=>$request->fabric_details[$x],  
                    'size_range'=>$request->size_range[$x],  
                    'sam'=>$request->sam[$x],  
                    'quantity'=>$request->quantity[$x],  
                    'cur_id'=>$request->cur_id[$x],  
                    'fob_rate'=>$request->fob_rate[$x],  
                    'exchange_rate'=>$request->exchange_rate[$x],  
                    'fob_rate_inr'=>$request->fob_rate_inr[$x],  
                    'CM'=>$request->CM[$x],  
                    'OH'=>$request->OH[$x],  
                    'P'=>$request->P[$x],  
                    'CMOHP_value'=>$request->CMOHP_value[$x],  
                    'CMOHP_min'=>$request->CMOHP_min[$x],  
                    'total_amount_inr'=>$request->total_amount_inr[$x],  
                    'total_minutes'=>$request->total_minutes[$x],  
                    'opportunity_stage_id'=>$request->opportunity_stage_id[$x],  
                    'remark'=>$request->remark[$x]
               );
              
                OpportunityDetailModel::insert($data2); 
            } 
        }
        
         
        $product_image = $request->product_image;
        if(!empty($product_image)) 
        {
               
            $attachment = $request->file('product_image');
            $fileName = time() . '_' . $attachment->getClientOriginalName(); 
            $location = public_path('uploads/Opportunity/');
            if (file_exists('uploads/Opportunity/'.$fileName))
            {
                 $url = "uploads/Opportunity/".$fileName;
                 unlink($url);
            }
            $attachment->move($location,$fileName);
            DB::table('opportunity_details')->where('opportunity_id', $request->opportunity_id)->update(['product_image' => $fileName]); 
           
        }  
        
        return redirect()->route('Opportunity.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Opportunity  $Opportunity
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        OpportunityModel::where('opportunity_id', $id)->delete();  
        OpportunityDetailModel::where('opportunity_id', $id)->delete();  
        return 1;
    } 
   
    public function OpportunityPrint($opportunity_id)
    {
        
        $OpportunityMaster = OpportunityModel::leftjoin('perticular_master', 'perticular_master.perticular_id', '=', 'opportunity_master.perticular_id') 
            ->select('opportunity_master.*', 'perticular_master.perticular_name') 
            ->where('opportunity_master.delflag', '=', '0')
            ->where('opportunity_master.opportunity_id', '=', $opportunity_id)
            ->first();
            
        $OpportunityDetail = OpportunityDetailModel::leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'opportunity_details.Ac_code') 
                                ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'opportunity_details.brand_id') 
                                ->select('opportunity_details.*', 'brand_master.brand_name', 'ledger_master.ac_short_name')  
                                ->where('opportunity_details.opportunity_id', '=', $opportunity_id)
                                ->get();
            
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
       return view('OpportunityPrint', compact('OpportunityMaster','OpportunityDetail','FirmDetail'));
      
    }
    
    public function OpportunitySave(Request $request)
    { 
        DB::table('opportunity_details')->where('opportunity_id', $request->opportunity_id)
            ->where('opportunity_detail_id', $request->opportunity_detail_id)
            ->update(['Ac_code' => $request->Ac_code,
            'brand_id' => $request->brand_id,
            'main_style_id' => $request->main_style_id,
            'style_name' => $request->style_name,
            'style_description' => $request->style_description,
            'product_image' => $request->product_image,
            'product_url' => $request->product_url,
            'gender_id' => $request->gender_id,
            'fabric_details' => $request->fabric_details,
            'size_range' => $request->size_range,
            'sam' => $request->sam,
            'quantity' => $request->quantity,
            'cur_id' => $request->cur_id,
            'fob_rate' => $request->fob_rate,
            'exchange_rate' => $request->exchange_rate,
            'fob_rate_inr' => $request->fob_rate_inr,
            'cm' => $request->cm,
            'OH' => $request->OH,
            'P' => $request->P,
            'CMOHP_value' => $request->CMOHP_value,
            'CMOHP_min' => $request->CMOHP_min,
            'total_amount_inr' => $request->total_amount_inr,
            'total_minute' => $request->total_minute,
            'stage_id' => $request->stage_id,
            'remarks' => $request->remarks
        ]); 
        return 1;
    }
    // public function OpportunityEdit(Request $request)
    // { 
     
    //     $BuyerList = DB::SELECT("SELECT crm_id, buyer_name, buyer_brand FROM crm_master WHERE delflag=0");
    //     $StatgeList = DB::SELECT("SELECT * FROM opportunity_stage WHERE delflag=0");
    //     $GenderList = DB::SELECT("SELECT * FROM gender_master WHERE delflag=0");
    //     $MainStyleList = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
    //     $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0");
     
    //     $html = ''; 
    //     if($request->opportunity_id != 0)
    //     {
    //         $OpportunityMaster = OpportunityModel::find($request->opportunity_id);
    //         $OpportunityDetails = OpportunityDetailModel::where('opportunity_id', $request->opportunity_id)->get();
            
    //         if (isset($OpportunityMaster)) 
    //         {
    //             $html .= '<form action="' . route('Opportunity.update', $OpportunityMaster) . '" method="POST" enctype="multipart/form-data" id="frmData">
    //                         <input type="hidden" name="_method" value="PUT">
    //                         <input type="hidden" name="_token" value="' . csrf_token() . '"> 
    //                         <input type="hidden" name="opportunity_id" class="form-control" id="opportunity_id" value="' . $OpportunityMaster->opportunity_id . '">
    //                         <div class="row"> 
    //                           <div class="col-md-2">
    //                              <div class="mb-3">
    //                                 <label for="opportunity_date" class="form-label">Date<span class="required_label">*</span></label>
    //                                 <input type="date" name="opportunity_date" class="form-control" id="opportunity_date" value="' . $OpportunityMaster->opportunity_date . '" required>
    //                              </div>
    //                           </div>
    //                           <div class="col-md-4">
    //                              <div class="mb-3">
    //                                 <label for="Ac_code" class="form-label">Buyer Name  <span class="required_label">*</span></label>
    //                                 <select name="Ac_code" class="form-select" id="Ac_code" required>
    //                                   <option value="">--Select--</option>';
    //                                     foreach ($BuyerList as $row) {
    //                                         $html .= '<option value="' . $row->crm_id . '" ' . ($row->crm_id == $OpportunityMaster->Ac_code ? 'selected="selected"' : '') . '>' . $row->buyer_name . '</option>';
    //                                     }
    //             $html .= '  </select>
    //                              </div>
    //                           </div>
    //                           <div class="col-md-4">
    //                              <div class="mb-3">
    //                                 <label for="brand_id" class="form-label">Buyer Brand  <span class="required_label">*</span></label>
    //                                 <select name="brand_id" class="form-select" id="brand_id" required>
    //                                   <option value="">--Select--</option>';
    //                                     foreach ($BuyerList as $row) {
    //                                         $html .= '<option value="' . $row->crm_id . '" ' . ($row->crm_id == $OpportunityMaster->brand_id ? 'selected="selected"' : '') . '>' . $row->buyer_brand . '</option>';
    //                                     }
    //             $html .= '  </select>
    //                              </div>
    //                           </div> 
    //                       </div>
    //                       <div class="row"> 
    //                           <div class="table-responsive">
    //                               <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
    //                                  <thead> 
    //                                     <tr>
    //                                       <th>Sr No</th>
    //                                       <th>Style Category</th>
    //                                       <th>Style Name</th>
    //                                       <th>Style Descriptions</th> 
    //                                       <th>Product Image</th> 
    //                                       <th>Product Link if Any</th> 
    //                                       <th>Gender</th> 
    //                                       <th>Fabric Details</th> 
    //                                       <th>Size Range</th> 
    //                                       <th>SAM</th> 
    //                                       <th>Quantity</th> 
    //                                       <th>Currency</th> 
    //                                       <th>FOB</th> 
    //                                       <th>Exchange Rate</th> 
    //                                       <th>FOB Rate (INR)</th> 
    //                                       <th>CM</th> 
    //                                       <th>OH</th> 
    //                                       <th>P</th> 
    //                                       <th>CMOHP Value</th> 
    //                                       <th>CMOHP/Min</th> 
    //                                       <th>Total Amount (INR)</th> 
    //                                       <th>Total Minute</th> 
    //                                       <th>Stage</th> 
    //                                       <th>Remark</th>
    //                                       <th>Add/Remove</th>
    //                                     </tr>
    //                                  </thead>
    //                                  <tbody>';
        
    //                                     $sr_no = 1;
    //                                     foreach ($OpportunityDetails as $rows) {
    //                                         $html .= '<tr>
    //                                                   <td><input type="text" name="sr_no[]" value="' . $sr_no++ . '" class="form-control" id="id0" style="width:50px;" readonly/></td>
    //                                                   <td>
    //                                                       <select name="main_style_id[]" class="form-select" id="main_style_id" style="width:250px;"> 
    //                                                           <option value="">--Select--</option>';
    //                                                             foreach ($MainStyleList as $row) {
    //                                                                 $html .= '<option value="' . $row->mainstyle_id . '" ' . ($row->mainstyle_id == $rows->main_style_id ? 'selected="selected"' : '') . '>' . $row->mainstyle_name . '</option>';
    //                                                             }
    //                                         $html .= '</select>
    //                                                   </td>
    //                                                   <td><input type="text" name="style_name[]" class="style_name form-control" value="' . $rows->style_name . '" id="style_name" style="width:250px;" /></td>
    //                                                   <td><input type="text" name="style_description[]" class="style_description form-control" value="' . $rows->style_description . '" id="style_description" style="width:300px;" /></td>
    //                                                   <td><input type="file" name="product_image[]" class="product_image form-control" id="product_image" style="width:250px;" /></td>
    //                                                   <td><input type="text" name="product_url[]" class="product_url form-control" value="' . $rows->product_url . '" id="product_url" style="width:300px;" /></td>
    //                                                   <td>
    //                                                       <select name="gender_id[]" class="form-select" id="gender_id" style="width:250px;">
    //                                                           <option value="">--Select--</option>';
    //                                                             foreach ($GenderList as $row) {
    //                                                                 $html .= '<option value="' . $row->gender_id . '" ' . ($row->gender_id == $rows->gender_id ? 'selected="selected"' : '') . '>' . $row->gender_name . '</option>';
    //                                                             }
    //                                         $html .= '</select>
    //                                                   </td>
    //                                                   <td><input type="text" name="fabric_details[]" class="fabric_details form-control" value="' . $rows->fabric_details . '" id="fabric_details" style="width:300px;" /></td>
    //                                                   <td><input type="text" name="size_range[]" class="size_range form-control" value="' . $rows->size_range . '" id="size_range" style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="sam[]" class="sam form-control" value="' . $rows->sam . '" onchange="CalculateCMOHP(this);CalculateMinutes(this);" id="sam" style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="quantity[]" class="quantity form-control" value="' . $rows->quantity . '" id="quantity" onchange="CalculateCMOHP(this); CalculateAmount(this);CalculateMinutes(this);" style="width:300px;" /></td>
    //                                                   <td>
    //                                                       <select name="cur_id[]" class="form-select" id="cur_id" style="width:250px;">
    //                                                           <option value="">--Select--</option>';
    //                                                             foreach ($CurrencyList as $row) {
    //                                                                 $html .= '<option value="' . $row->cur_id . '" ' . ($row->cur_id == $rows->cur_id ? 'selected="selected"' : '') . '>' . $row->currency_name . '</option>';
    //                                                             }
    //                                         $html .= '</select>
    //                                                   </td>
    //                                                   <td><input type="number" step="any" name="fob_rate[]" class="fob_rate form-control" value="' . $rows->fob_rate . '" id="fob_rate" onchange="CalculateFOB(this);" style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="exchange_rate[]" class="exchange_rate form-control" value="' . $rows->exchange_rate . '" id="exchange_rate" onchange="CalculateFOB(this);" style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="fob_rate_inr[]" class="fob_rate_inr form-control" value="' . $rows->fob_rate_inr . '" id="fob_rate_inr" style="width:300px;" readonly/></td>
    //                                                   <td><input type="number" step="any" name="cm[]" class="cm form-control" value="' . $rows->cm . '" id="cm" onchange="CalculateCMOHP(this);" style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="OH[]" class="OH form-control" value="' . $rows->OH . '" id="OH" onchange="CalculateCMOHP(this);" style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="P[]" class="P form-control" value="' . $rows->P . '" id="P" onchange="CalculateCMOHP(this);" style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="CMOHP_value[]" class="CMOHP_value form-control" value="' . $rows->CMOHP_value . '" id="CMOHP_value" style="width:300px;" readonly/></td>
    //                                                   <td><input type="number" step="any" name="CMOHP_min[]" class="CMOHP_min form-control" value="' . $rows->CMOHP_min . '" id="CMOHP_min" style="width:300px;" readonly/></td>
    //                                                   <td><input type="number" step="any" name="total_amount_inr[]" class="total_amount_inr form-control" value="' . $rows->total_amount_inr . '" id="total_amount_inr" readonly style="width:300px;" /></td>
    //                                                   <td><input type="number" step="any" name="total_minute[]" class="total_minute form-control" value="' . $rows->total_minute . '" id="total_minute" readonly style="width:300px;" /></td>
    //                                                   <td>
    //                                                       <select name="stage_id[]" class="form-select" id="stage_id" style="width:250px;">
    //                                                           <option value="">--Select--</option>';
    //                                                             foreach ($StatgeList as $row) {
    //                                                                 $html .= '<option value="' . $row->opportunity_stage_id . '" ' . ($row->opportunity_stage_id == $rows->opportunity_stage_id ? 'selected="selected"' : '') . '>' . $row->opportunity_stage_name . '</option>';
    //                                                             }
    //                                         $html .= '</select>
    //                                                   </td>
    //                                                   <td><textarea class="form-control" name="remarks[]" rows="2">' . $rows->remarks . '</textarea></td>
    //                                                   <td><button class="btn btn-danger">Remove</button></td>
    //                                               </tr>';
    //                                     }
        
    //             $html .= '  </tbody>
    //                         </table>
    //                       </div>
    //                     </div>';
        
    //             return response()->json(['html' => $html]);
    //         }
    //     }
    //     else
    //     {
    //         $html .= '<form action="' . route('Opportunity.store') . '" method="POST" enctype="multipart/form-data" id="frmData">
    //                 @csrf
    //                 <div class="row"> 
    //                   <div class="col-md-2">
    //                      <div class="mb-3">
    //                         <label for="opportunity_date" class="form-label">Date<span class="required_label">*</span></label>
    //                         <input type="date" name="opportunity_date" class="form-control" id="opportunity_date" value="' .date("Y-m-d"). '" required>
    //                      </div>
    //                   </div>
    //                   <div class="col-md-4">
    //                      <div class="mb-3">
    //                         <label for="Ac_code" class="form-label">Buyer Name  <span class="required_label">*</span></label>
    //                         <select name="Ac_code" class="form-select" id="Ac_code" required>
    //                           <option value="">--Select--</option>';
    //                             foreach ($BuyerList as $row) {
    //                                 $html .= '<option value="' . $row->crm_id . '">' . $row->buyer_name . '</option>';
    //                             }
    //         $html .= '  </select>
    //                          </div>
    //                       </div>
    //                       <div class="col-md-4">
    //                          <div class="mb-3">
    //                             <label for="brand_id" class="form-label">Buyer Brand  <span class="required_label">*</span></label>
    //                             <select name="brand_id" class="form-select" id="brand_id" required>
    //                               <option value="">--Select--</option>';
    //                                 foreach ($BuyerList as $row) {
    //                                     $html .= '<option value="' . $row->crm_id . '">' . $row->buyer_brand . '</option>';
    //                                 }
    //         $html .= '  </select>
    //                          </div>
    //                       </div> 
    //                   </div>
    //                   <div class="row"> 
    //                       <div class="table-responsive">
    //                           <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
    //                              <thead> 
    //                                 <tr>
    //                                   <th>Sr No</th>
    //                                   <th>Style Category</th>
    //                                   <th>Style Name</th>
    //                                   <th>Style Descriptions</th> 
    //                                   <th>Product Image</th> 
    //                                   <th>Product Link if Any</th> 
    //                                   <th>Gender</th> 
    //                                   <th>Fabric Details</th> 
    //                                   <th>Size Range</th> 
    //                                   <th>SAM</th> 
    //                                   <th>Quantity</th> 
    //                                   <th>Currency</th> 
    //                                   <th>FOB</th> 
    //                                   <th>Exchange Rate</th> 
    //                                   <th>FOB Rate (INR)</th> 
    //                                   <th>CM</th> 
    //                                   <th>OH</th> 
    //                                   <th>P</th> 
    //                                   <th>CMOHP Value</th> 
    //                                   <th>CMOHP/Min</th> 
    //                                   <th>Total Amount (INR)</th> 
    //                                   <th>Total Minute</th> 
    //                                   <th>Stage</th> 
    //                                   <th>Remark</th>
    //                                   <th>Add/Remove</th>
    //                                 </tr>
    //                              </thead>
    //                              <tbody>';
    
    //                                 $sr_no = 1;
    //                                     $html .= '<tr>
    //                                               <td><input type="text" name="sr_no[]" value="' . $sr_no . '" class="form-control" id="id0" style="width:50px;" readonly/></td>
    //                                               <td>
    //                                                   <select name="main_style_id[]" class="form-select" id="main_style_id" style="width:250px;"> 
    //                                                       <option value="">--Select--</option>';
    //                                                         foreach ($MainStyleList as $row) {
    //                                                             $html .= '<option value="' . $row->mainstyle_id . '">' . $row->mainstyle_name . '</option>';
    //                                                         }
    //                                     $html .= '</select>
    //                                               </td>
    //                                               <td><input type="text" name="style_name[]" class="style_name form-control" value="" id="style_name" style="width:250px;" /></td>
    //                                               <td><input type="text" name="style_description[]" class="style_description form-control" value="" id="style_description" style="width:300px;" /></td>
    //                                               <td><input type="file" name="product_image[]" class="product_image form-control" id="product_image" style="width:250px;" /></td>
    //                                               <td><input type="text" name="product_url[]" class="product_url form-control" value="" id="product_url" style="width:300px;" /></td>
    //                                               <td>
    //                                                   <select name="gender_id[]" class="form-select" id="gender_id" style="width:250px;">
    //                                                       <option value="">--Select--</option>';
    //                                                         foreach ($GenderList as $row) {
    //                                                             $html .= '<option value="' . $row->gender_id . '">' . $row->gender_name . '</option>';
    //                                                         }
    //                                     $html .= '</select>
    //                                               </td>
    //                                               <td><input type="text" name="fabric_details[]" class="fabric_details form-control" value="" id="fabric_details" style="width:300px;" /></td>
    //                                               <td><input type="text" name="size_range[]" class="size_range form-control" value="" id="size_range" style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="sam[]" class="sam form-control" value="0" onchange="CalculateCMOHP(this);CalculateMinutes(this);" id="sam" style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="quantity[]" class="quantity form-control" value="0" id="quantity" onchange="CalculateCMOHP(this); CalculateAmount(this);CalculateMinutes(this);" style="width:300px;" /></td>
    //                                               <td>
    //                                                   <select name="cur_id[]" class="form-select" id="cur_id" style="width:250px;">
    //                                                       <option value="">--Select--</option>';
    //                                                         foreach ($CurrencyList as $row) {
    //                                                             $html .= '<option value="' . $row->cur_id . '">' . $row->currency_name . '</option>';
    //                                                         }
    //                                     $html .= '</select>
    //                                               </td>
    //                                               <td><input type="number" step="any" name="fob_rate[]" class="fob_rate form-control" value="0" id="fob_rate" onchange="CalculateFOB(this);" style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="exchange_rate[]" class="exchange_rate form-control" value="0" id="exchange_rate" onchange="CalculateFOB(this);" style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="fob_rate_inr[]" class="fob_rate_inr form-control" value="0" id="fob_rate_inr" style="width:300px;" readonly/></td>
    //                                               <td><input type="number" step="any" name="cm[]" class="cm form-control" value="0" id="cm" onchange="CalculateCMOHP(this);" style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="OH[]" class="OH form-control" value="0" id="OH" onchange="CalculateCMOHP(this);" style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="P[]" class="P form-control" value="0" id="P" onchange="CalculateCMOHP(this);" style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="CMOHP_value[]" class="CMOHP_value form-control" value="0" id="CMOHP_value" style="width:300px;" readonly/></td>
    //                                               <td><input type="number" step="any" name="CMOHP_min[]" class="CMOHP_min form-control" value="0" id="CMOHP_min" style="width:300px;" readonly/></td>
    //                                               <td><input type="number" step="any" name="total_amount_inr[]" class="total_amount_inr form-control" value="0" id="total_amount_inr" readonly style="width:300px;" /></td>
    //                                               <td><input type="number" step="any" name="total_minute[]" class="total_minute form-control" value="0" id="total_minute" readonly style="width:300px;" /></td>
    //                                               <td>
    //                                                   <select name="stage_id[]" class="form-select" id="stage_id" style="width:250px;">
    //                                                       <option value="">--Select--</option>';
    //                                                         foreach ($StatgeList as $row) {
    //                                                             $html .= '<option value="' . $row->opportunity_stage_id . '">' . $row->opportunity_stage_name . '</option>';
    //                                                         }
    //                                     $html .= '</select>
    //                                               </td>
    //                                               <td><textarea class="form-control" name="remarks[]" rows="2"></textarea></td>
    //                                               <td><button class="btn btn-danger">Remove</button></td>
    //                                           </tr>';
    
    //         $html .= '      </tbody>
    //                     </table>
    //                   </div>
    //                 </div>';
    //         return response()->json(['html' => $html]);
    //     }
    //     return response()->json(['error' => 'Opportunity not found.']);
    // } 
    public function OpportunityEdit(Request $request)
    { 
     
        $BuyerList = DB::SELECT("SELECT * FROM crm_master WHERE delflag=0");
        $StatgeList = DB::SELECT("SELECT * FROM opportunity_stage WHERE delflag=0");
        $GenderList = DB::SELECT("SELECT * FROM gender_master WHERE delflag=0");
        $MainStyleList = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
        $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0");
     
        $html = ''; 
        $OpportunityMaster = OpportunityModel::find($request->opportunity_id);
        $OpportunityDetails = OpportunityDetailModel::where('opportunity_id', $request->opportunity_id)->where('opportunity_detail_id', $request->opportunity_detail_id)->first();
        if($request->opportunity_detail_id > 0)
        {
           $form = route('OpportunityDetailUpdate');
        }
        else
        {
           $form = route('OpportunityDetailStore');  
        }
        
        $html .= '<form action="'.$form.'" method="POST" enctype="multipart/form-data" id="frmData">
                            <input type="hidden" name="_token" value="'.csrf_token().'"> 
                            <input type="hidden" name="opportunity_id" class="form-control" id="opportunity_id" value="'.$request->opportunity_id.'">
                            <input type="hidden" name="opportunity_detail_id" class="form-control" id="opportunity_detail_id" value="'.$request->opportunity_detail_id.'">
                             <table> 
                                <tbody>
                                  <tr>
                                    <td> 
                                        <label for="opportunity_date" class="form-label">Date</label><br/>
                                        <input type="date" name="opportunity_date" class="form-control" id="opportunity_date" value="'.$OpportunityMaster->opportunity_date.'"  readonly> 
                                    </td> 
                                    <td>
                                        <label for="Ac_code" class="form-label">Buyer Name</label>
                                        <select name="Ac_code" class="form-select" id="Ac_code" disabled>
                                           <option value="">--Select--</option>';
                                            foreach ($BuyerList as $row) 
                                            {
                                                if($row->crm_id == $OpportunityMaster->Ac_code)
                                                {
                                                    $isBuyer = 'selected';
                                                }
                                                else
                                                {
                                                    $isBuyer = '';
                                                }
                                                
                                                $html .= '<option value="' . $row->crm_id . '" '.$isBuyer.'>' . $row->buyer_name . '</option>';
                                            }
                                        $html .= '  </select>
                                    </td> 
                                    <td>
                                        <label for="brand_id" class="form-label" >Buyer Brand</label>
                                        <select name="brand_id" class="form-select" id="brand_id" disabled>
                                                   <option value="">--Select--</option>';
                                                    foreach ($BuyerList as $row) 
                                                    {
                                                        if($row->crm_id == $OpportunityMaster->brand_id)
                                                        {
                                                            $isBrand = 'selected';
                                                        }
                                                        else
                                                        {
                                                            $isBrand = '';
                                                        }
                                                
                                                        $html .= '<option value="' . $row->crm_id . '"  '.$isBrand.' >' . $row->buyer_brand . '</option>';
                                                    }
                                        $html .= '</select> 
                                    </td>
                                    <td> 
                                       <label for="opportunity_detail_id" class="form-label">Opportunity Detail Id</label><br/>
                                        <input type="text"  class="form-control" value="OP'.$request->opportunity_id.'/'.$request->opportunity_detail_id.'"  style="width: 195px;" readonly>  
                                    </td> 
                                  </tr>
                                  <tr><th colspan="4" class="text-center"><h4><b>Product Details</b></h4></th></tr>
                                  <tr> 
                                    <td><label for="main_style_id" class="form-label">Style Category <span class="required_label">*</span></label>
                                        <select name="main_style_id" class="form-select" id="main_style_id" required> 
                                          <option value="">--Select--</option>';
                                            foreach ($MainStyleList as $row) 
                                            {
                                                if($row->mainstyle_id == (isset($OpportunityDetails->main_style_id) ? $OpportunityDetails->main_style_id : 0))
                                                {
                                                    $isMainStyle = 'selected';
                                                }
                                                else
                                                {
                                                    $isMainStyle = '';
                                                }
                                                
                                                $html .= '<option value="' . $row->mainstyle_id . '" '.$isMainStyle.'>' . $row->mainstyle_name . '</option>';
                                            }
                                        $html .= '</select> 
                                    </td> 
                                    <td> <label for="style_name" class="form-label">Style Name <span class="required_label">*</span></label><input type="text" name="style_name" class="style_name form-control" value="'.(isset($OpportunityDetails->style_name) ? $OpportunityDetails->style_name : "").'" id="style_name" required/></td>
                                    <td>  <label for="style_description" class="form-label">Style Descriptions</label><input type="text" name="style_description" class="style_description form-control" value="'.(isset($OpportunityDetails->style_description) ? $OpportunityDetails->style_description : "").'" id="style_description" /></td>
                                    <td><label for="product_image" class="form-label">Product Image</label><input type="file" name="product_image" class="product_image form-control" id="product_image"  style="width: 195px;" /></td>
                                 </tr>
                                  <tr>
                                    <td><label for="product_url" class="form-label">Product Link if Any</label><input type="text" name="product_url" class="product_url form-control" value="'.(isset($OpportunityDetails->product_url) ? $OpportunityDetails->product_url : "").'" id="product_url"  /></td>
                                    <td> <label for="gender_id" class="form-label">Gender <span class="required_label">*</span></label><select name="gender_id" class="form-select" id="gender_id" required>
                                              <option value="">--Select--</option>';
                                                foreach ($GenderList as $row) 
                                                {
                                                        if($row->gender_id == (isset($OpportunityDetails->gender_id) ? $OpportunityDetails->gender_id : 0))
                                                        {
                                                            $isGender = 'selected';
                                                        }
                                                        else
                                                        {
                                                            $isGender = '';
                                                        }
                                                        $html .= '<option value="' . $row->gender_id . '" '.$isGender.'>' . $row->gender_name . '</option>';
                                                }
                                    $html .= '</select>
                                    </td>  
                                    <td><label for="fabric_details" class="form-label">Fabric Details <span class="required_label">*</span></label><input type="text" name="fabric_details" class="fabric_details form-control" value="'.(isset($OpportunityDetails->fabric_details) ? $OpportunityDetails->fabric_details : 0).'" id="fabric_details" required /></td>
                                    <td><label for="size_range" class="form-label">Size Range <span class="required_label">*</span></label><input type="text" name="size_range" class="size_range form-control" value="'.(isset($OpportunityDetails->size_range) ? $OpportunityDetails->size_range : "").'" id="size_range"  style="width: 195px;" required /></td>
                                  </tr>
                                  <tr><th colspan="4" class="text-center"><h4><b>Opportunity Details</b></h4></th></tr>
                                  <tr>
                                    <td><label for="sam" class="form-label">SAM <span class="required_label">*</span></label><input type="number" step="any" name="sam" class="sam form-control" value="'.(isset($OpportunityDetails->sam) ? $OpportunityDetails->sam : "").'" onchange="CalculateCMOHP(this);CalculateMinutes(this);" id="sam" required/></td>
                                    <td><label for="quantity" class="form-label">Quantity <span class="required_label">*</span></label><input type="number" step="any" name="quantity" class="quantity form-control" value="'.(isset($OpportunityDetails->quantity) ? $OpportunityDetails->quantity : "").'" id="quantity" onchange="CalculateCMOHP(this); CalculateAmount(this);CalculateMinutes(this);" required /></td>
                                    <td><label for="cur_id" class="form-label">Currency <span class="required_label">*</span></label><select name="cur_id" class="form-select" id="cur_id"  required>
                                              <option value="">--Select--</option>';
                                                foreach ($CurrencyList as $row)
                                                {
                                                        if($row->cur_id == (isset($OpportunityDetails->cur_id) ? $OpportunityDetails->cur_id : 0))
                                                        {
                                                            $isCurrancy = 'selected';
                                                        }
                                                        else
                                                        {
                                                            $isCurrancy = '';
                                                        }
                                                        
                                                    $html .= '<option value="' . $row->cur_id . '" '.$isCurrancy.'>' . $row->currency_name . '</option>';
                                                }
                                    $html .= '</select>
                                    </td>
                                    <td><label for="fob_rate" class="form-label">FOB <span class="required_label">*</span></label><input type="number" step="any" name="fob_rate" class="fob_rate form-control" style="width: 195px;" value="'.(isset($OpportunityDetails->fob_rate) ? $OpportunityDetails->fob_rate : 0).'" id="fob_rate" onchange="CalculateFOB(this);"  required /></td>
                                  </tr>
                                  <tr> 
                                    <td><label for="sam" class="form-label">Exchange Rate <span class="required_label">*</span></label><input type="number" step="any" name="exchange_rate" class="exchange_rate form-control" value="'.(isset($OpportunityDetails->exchange_rate) ? $OpportunityDetails->exchange_rate : 0).'" id="exchange_rate" onchange="CalculateFOB(this);" required/></td>
                                    <td><label for="fob_rate_inr" class="form-label">FOB Rate (INR) <span class="required_label">*</span></label><input type="number" step="any" name="fob_rate_inr" class="fob_rate_inr form-control" value="'.(isset($OpportunityDetails->fob_rate_inr) ? $OpportunityDetails->fob_rate_inr : 0).'" id="fob_rate_inr" readonly/></td>
                                    <td><label for="cm" class="form-label">CM <span class="required_label">*</span></label><input type="number" step="any" name="CM" class="CM form-control" value="'.(isset($OpportunityDetails->CM) ? $OpportunityDetails->CM : 0).'" id="CM" onchange="CalculateCMOHP(this);" required /></td>
                                    <td><label for="OH" class="form-label">OH <span class="required_label">*</span></label><input type="number" step="any" name="OH" class="OH form-control"  style="width: 195px;" value="'.(isset($OpportunityDetails->OH) ? $OpportunityDetails->OH : 0).'" id="OH" onchange="CalculateCMOHP(this);" required /></td>
                                  </tr>
                                  <tr> 
                                    <td><label for="P" class="form-label">P <span class="required_label">*</span></label><input type="number" step="any" name="P" class="P form-control" value="'.(isset($OpportunityDetails->P) ? $OpportunityDetails->P : 0).'" id="P" onchange="CalculateCMOHP(this);" required /></td>
                                    <td><label for="CMOHP_value" class="form-label">CMOHP Value <span class="required_label">*</span></label><input type="number" step="any" name="CMOHP_value" class="CMOHP_value form-control" value="'.(isset($OpportunityDetails->CMOHP_value) ? $OpportunityDetails->CMOHP_value : 0).'" id="CMOHP_value" readonly/></td>
                                    <td><label for="sam" class="form-label">CMOHP/Min <span class="required_label">*</span></label><input type="number" step="any" name="CMOHP_min" class="CMOHP_min form-control" value="'.(isset($OpportunityDetails->CMOHP_min) ? $OpportunityDetails->CMOHP_min : 0).'" id="CMOHP_min" readonly/></td>
                                    <td><label for="sam" class="form-label">Total Amount (INR) <span class="required_label">*</span></label><input type="number" step="any" name="total_amount_inr"  style="width: 195px;" class="total_amount_inr form-control" value="'.(isset($OpportunityDetails->total_amount_inr) ? $OpportunityDetails->total_amount_inr : 0).'" id="total_amount_inr" readonly  /></td>
                                  </tr>
                                  <tr>    
                                    <td><label for="sam" class="form-label">Total Minute <span class="required_label">*</span></label><input type="number" step="any" name="total_minute" class="total_minute form-control" value="'.(isset($OpportunityDetails->total_minutes) ? $OpportunityDetails->total_minutes : 0).'" id="total_minute" readonly /></td>
                                    <td><label for="sam" class="form-label">Stage <span class="required_label">*</span></label><select name="stage_id" class="form-select" id="stage_id" required>
                                                      <option value="">--Select--</option>';
                                                        foreach ($StatgeList as $row) 
                                                        {
                                                            if( $row->opportunity_stage_id == (isset($OpportunityDetails->opportunity_stage_id) ? $OpportunityDetails->opportunity_stage_id : 0))
                                                            {
                                                                $stages = 'selected';
                                                            }
                                                            else
                                                            {
                                                                $stages = '';
                                                            }
                                                            
                                                            $html .= '<option value="' . $row->opportunity_stage_id . '" '.$stages.'>' . $row->opportunity_stage_name . '</option>';
                                                        }
                                        $html .= '</select>
                                    </td>
                                    <td><label for="remarks" class="form-label">Remark</label><textarea class="form-control" name="remarks" rows="2">'.(isset($OpportunityDetails->remark) ? $OpportunityDetails->remark : "").'</textarea></td>  
                                  </tr>
                                  <tr>   
                                    <td colspan="4" class="text-center"><button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFeilds();">Update</button>
                                    <a href="'.route('Opportunity.edit', $request->opportunity_id).'" class="btn btn-warning w-md" style="margin-left:10px;">Cancel</a></td>
                                  </tr>
                                </tbody>
                              </table>
                              </form>';  
            return response()->json(['html' => $html]);
    } 
    public function OpportunityCreate(Request $request)
    { 
     
        $BuyerList = DB::SELECT("SELECT crm_id, buyer_name, buyer_brand FROM crm_master WHERE delflag=0");
        $StatgeList = DB::SELECT("SELECT * FROM opportunity_stage WHERE delflag=0");
        $GenderList = DB::SELECT("SELECT * FROM gender_master WHERE delflag=0");
        $MainStyleList = DB::SELECT("SELECT * FROM main_style_master WHERE delflag=0");
        $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0");
     
        $html = ''; 
           
        $html .= '<form action="'. route('OpportunityStore').'" method="POST" enctype="multipart/form-data" id="frmData"> 
                    <input type="hidden" name="_token" value="'.csrf_token().'"> 
                       <div class="row">
                          <div class="col-md-6">
                             <div class="mb-3 text-left">
                                <label for="opportunity_date" class="form-label">Date<span class="required_label">*</span></label>
                                <input type="date" name="opportunity_date" class="form-control" id="opportunity_date" value="'.date('Y-m-d').'" required>
                             </div>
                          </div> 
                          <div class="col-md-6">
                             <div class="mb-3 text-left">
                                <label for="opportunity_name" class="form-label">Opportunity Name<span class="required_label">*</span></label>
                                <input type="text" name="opportunity_name" class="form-control" id="opportunity_name" value="" required>
                             </div>
                          </div>
                          <div class="col-md-6">
                             <div class="mb-3 text-left">
                                <label for="Ac_code" class="form-label">Buyer Name  <span class="required_label">*</span></label>
                                <select name="Ac_code" class="form-select" id="Ac_code" required>
                                   <option value="">--Select--</option>';
                                   foreach($BuyerList as  $row) 
                                   {
                                         $html .= '<option value="'.$row->crm_id.'">'.$row->buyer_name.'</option>'; 
                                   }
                                 $html .= '</select>
                             </div>
                          </div>
                          <div class="col-md-6">
                             <div class="mb-3 text-left">
                                <label for="brand_id" class="form-label">Buyer Brand  <span class="required_label">*</span></label>
                                <select name="brand_id" class="form-select" id="brand_id" required>
                                   <option value="">--Select--</option>';
                                   foreach($BuyerList as  $row) 
                                   {
                                         $html .= '<option value="'.$row->crm_id.'">'.$row->buyer_brand.'</option>'; 
                                   }
                                 $html .= '</select>
                             </div>
                          </div> 
                           <div class="col-sm-12">
                              <label for="formrow-inputState" class="form-label"></label>
                              <div class="form-group">
                                 <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFields();">Submit</button>
                                 <a href="'. route('Opportunity.index').'" class="btn btn-warning w-md">Cancel</a>
                              </div>
                           </div> 
                       </div>
                      </form>';  
                     
            return response()->json(['html' => $html]);
            
        return response()->json(['error' => 'Opportunity not found.']);
    } 
   
    public function OpportunityStore(Request $request)
    { 
        DB::table('opportunity_master')
        ->insert([
            'opportunity_name' => $request->opportunity_name,
            'opportunity_date' => $request->opportunity_date,
            'Ac_code' => $request->Ac_code,
            'brand_id' => $request->brand_id,
            'delflag' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'userId' =>  Session::get('userId')
        ]);
        
        return redirect()->route('Opportunity.index');
    }
    
    
    public function OpportunityMasterUpdate(Request $request)
    {  
        DB::table('opportunity_master') 
        ->where('opportunity_id',  $request->opportunity_id)
        ->update([
            'opportunity_date' => $request->opportunity_date,
            'Ac_code' => $request->Ac_code,
            'brand_id' => $request->brand_id,
            'delflag' => 0,
            'updated_at' => date("Y-m-d H:i:s"),
            'userId' =>  Session::get('userId')
        ]);
        
        return redirect()->route('Opportunity.edit', $request->opportunity_id);
    }
    
    public function OpportunityDetailStore(Request $request)
    { 
        DB::table('opportunity_details')
        ->insert([
            'opportunity_id' => $request->opportunity_id,
            'opportunity_date' => $request->opportunity_date,
            'Ac_code' => $request->Ac_code,
            'brand_id' => $request->brand_id,
            'main_style_id' => $request->main_style_id,
            'style_name' => $request->style_name,
            'style_description' => $request->style_description, 
            'product_url' => $request->product_url,
            'gender_id' => $request->gender_id,
            'fabric_details' => $request->fabric_details,
            'size_range' => $request->size_range,
            'sam' => $request->sam,
            'quantity' => $request->quantity,
            'cur_id' => $request->cur_id,
            'fob_rate' => $request->fob_rate,
            'exchange_rate' => $request->exchange_rate,
            'fob_rate_inr' => $request->fob_rate_inr,
            'cm' => $request->cm,
            'OH' => $request->OH,
            'P' => $request->P,
            'CMOHP_value' => $request->CMOHP_value,
            'CMOHP_min' => $request->CMOHP_min,
            'total_amount_inr' => $request->total_amount_inr,
            'total_minutes' => $request->total_minute,
            'opportunity_stage_id' => $request->stage_id,
            'remark' => $request->remarks
        ]);
        
           
        $product_image = $request->product_image;
        if(!empty($product_image)) 
        {
            foreach($product_image as $index => $attachmentName) 
            {
                if ($request->hasFile('product_image.' . $index)) {
                    $attachment = $request->file('product_image')[$index];
                    $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                    $location = public_path('uploads/Opportunity/');
                    if (file_exists('uploads/Opportunity/'.$fileName))
                    {
                         $url = "uploads/Opportunity/".$fileName;
                         unlink($url);
                    }
                    $attachment->move($location,$fileName);
                    DB::table('opportunity_details')->where('opportunity_id', $request->opportunity_id)->update(['product_image' => $fileName]); 
                }
            }
        }   
        
        return redirect()->route('Opportunity.edit', $request->opportunity_id);
    }
    
    public function OpportunityDetailUpdate(Request $request)
    {  
        DB::table('opportunity_details')
        ->where('opportunity_detail_id', $request->opportunity_detail_id)
        ->where('opportunity_id',  $request->opportunity_id)
        ->update([
            'main_style_id' => $request->main_style_id,
            'style_name' => $request->style_name,
            'style_description' => $request->style_description, 
            'product_url' => $request->product_url,
            'gender_id' => $request->gender_id,
            'fabric_details' => $request->fabric_details,
            'size_range' => $request->size_range,
            'sam' => $request->sam,
            'quantity' => $request->quantity,
            'cur_id' => $request->cur_id,
            'fob_rate' => $request->fob_rate,
            'exchange_rate' => $request->exchange_rate,
            'fob_rate_inr' => $request->fob_rate_inr,
            'cm' => $request->cm,
            'OH' => $request->OH,
            'P' => $request->P,
            'CMOHP_value' => $request->CMOHP_value,
            'CMOHP_min' => $request->CMOHP_min,
            'total_amount_inr' => $request->total_amount_inr,
            'total_minutes' => $request->total_minute,
            'opportunity_stage_id' => $request->stage_id,
            'remark' => $request->remarks
        ]);
        
          
        $product_image = $request->product_image;
        if(!empty($product_image)) 
        {
                $attachment = $request->file('product_image');
                $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                $location = public_path('uploads/Opportunity/');
                if (file_exists('uploads/Opportunity/'.$fileName))
                {
                     $url = "uploads/Opportunity/".$fileName;
                     unlink($url);
                }
                $attachment->move($location,$fileName);
                DB::table('opportunity_details')->where('opportunity_detail_id', $request->opportunity_detail_id)->where('opportunity_id', $request->opportunity_id)->update(['product_image' => $fileName]); 
        }   
        
        return redirect()->route('Opportunity.edit', $request->opportunity_id);
    }
    
    public function DeleteOpportunityDetail(Request $request)
    { 
        DB::table('opportunity_details')->where('opportunity_id',  $request->opportunity_id)->delete(); 
        
        return 1;
    }
    
    
}



                       
                        