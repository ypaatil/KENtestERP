<?php

namespace App\Http\Controllers;

use App\Models\BuyerJobCardModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\SeasonModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\SizeModel;
use Image;
use Illuminate\Support\Facades\DB;
use App\Models\BuyerJobCardDetail;
use App\Models\BuyerJobCardSampleDetail;

use Session;

class BuyerJobCardController extends Controller
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
        ->where('form_id', '35')
        ->first();
        

        //   DB::enableQueryLog();
        $BJobCardList = BuyerJobCardModel::join('usermaster', 'usermaster.userId', '=', 'job_card_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'job_card_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'job_card_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'job_card_master.job_status_id')
        ->join('brand_master', 'brand_master.brand_id', '=', 'job_card_master.brand_id')
        ->join('season_master', 'season_master.season_id', '=', 'job_card_master.season_id')
        ->join('cp_master', 'cp_master.cp_id', '=', 'job_card_master.cp_id')
        ->where('job_card_master.delflag','=', '0')
        ->get(['job_card_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name','fg_master.fg_name','job_status_master.job_status_name','brand_master.brand_name', 'season_master.season_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('BuyerJobCardMasterList', compact('BJobCardList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BUYER_JOB_CARD'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
       $JobStatusList= DB::table('job_status_master')->get();
       $SampleList= DB::table('samples_master')->get();
       $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();

        return view('BuyerJobCardMaster',compact('Ledger','SampleList', 'CPList',  'FGList','SizeList','BrandList','SeasonList','ColorList','counter_number', 'JobStatusList'));

         
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
             
                'Ac_code'=> 'required', 
                'fg_id'=> 'required',
                'cp_id'=> 'required',
                'style_no'=> 'required',
                'start_date'=> 'required',
                'end_date'=> 'required',
                'job_status_id'=> 'required',
                'brand_id'=> 'required',
                'season_id'=> 'required',
                'ppk_ratio'=> 'required',
                'rate_per_piece'=> 'required',
                'prod_qty'=> 'required',
                'total_amount'=> 'required',
              
    ]);
 
   // Upload style_pic_path
    $style_pic_path=$request->file('style_pic_path');
    if($style_pic_path) 
    {
   
    $image = $request->file('style_pic_path');
    $input['imagename'] = time().'I1.'.$image->getClientOriginalExtension();
 
    $destinationPath = public_path('/thumbnail');
    $img = Image::make($image->getRealPath());
    $img->resize(100, 100, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
    $StyleImageName=$input['imagename'];
}
else
{
    $StyleImageName='';
}

// Upload style_pic_path End
 
// Upload File1
if($request->hasFile('doc_path1')) 
{
    $fileName1 = time().'F1.'.$request->doc_path1->extension();  
    $request->doc_path1->move(public_path('uploads'), $fileName1);
    $fullTempFilePath= public_path('uploads/');
    $output = shell_exec("shrink " . $fullTempFilePath  . $fullTempFilePath . "-compressed ");
    shell_exec("mv " . $fullTempFilePath . "-compressed " . $fullTempFilePath);
    $fullTempFilePath=$fileName1;
    // Compress and Save  File1 End
}
 else
{
    $fullTempFilePath='';
}

// Upload File1
if($request->hasFile('doc_path2')) 
{
    $fileName2 = time().'F2.'.$request->doc_path2->extension();  
    $request->doc_path2->move(public_path('uploads'), $fileName2);
    $fullTempFilePath2= public_path('uploads/');
    $output = shell_exec("shrink " . $fullTempFilePath2  . $fullTempFilePath2 . "-compressed ");
    shell_exec("mv " . $fullTempFilePath2 . "-compressed " . $fullTempFilePath2);
    $fullTempFilePath2=$fileName2;
}
 else
{
    $fullTempFilePath2='';
}

    // Compress and Save  File1 End
     
// Samples Required
// $development_sample=isset($request->development_sample) ? 1 : 0;
// $fit_sample=isset($request->fit_sample) ? 1 : 0;
// $production_sample=isset($request->production_sample) ? 1 : 0;
// $fpt_sample=isset($request->fpt_sample) ? 1 : 0;
// $gpt_sample=isset($request->gpt_sample) ? 1 : 0;
// $sealer=isset($request->sealer) ? 1 : 0;
// $shipment=isset($request->shipment) ? 1 : 0;
// $photoshoot=isset($request->photoshoot) ? 1 : 0;


// 'development_sample'=>$development_sample,
// 'fit_sample'=>$fit_sample,
// 'production_sample'=>$production_sample,
// 'fpt_sample'=>$fpt_sample,
// 'gpt_sample'=>$gpt_sample,
// 'sealer'=>$sealer, 
// 'shipment'=>$shipment,
// 'photoshoot'=>$photoshoot,
 // End Samples Required
 
  if(substr_count($request->color_ids,",")>0)
  {
     $color_ids = implode(',', $request->color_ids);
  }
  else
  {
      $color_ids = $request->color_ids;
  }
  
  
$data1=array(
           
        'po_code'=>$request->po_code, 
        'po_date'=>$request->po_date, 
        'cp_id'=>$request->cp_id,
        'Ac_code'=>$request->Ac_code, 
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no, 
        'style_pic_path'=>$StyleImageName,
        'doc_path1'=>$fullTempFilePath,
        'doc_path_2'=>$fullTempFilePath2, 
        'comment_guidance'=>$request->comment_guidance, 
        'start_date'=>$request->start_date,
        'end_date'=>$request->end_date, 
        'job_status_id'=>$request->job_status_id, 
        'brand_id'=>$request->brand_id,
        'season_id'=>$request->season_id,   
        'prod_qty'=>$request->prod_qty, 
        'rate_per_piece'=>$request->rate_per_piece,
        'total_amount'=>$request->total_amount,
        'ppk_ratio'=>$request->ppk_ratio,
        'color_id'=> $color_ids ,
        'piece_avg'=>$request->piece_avg,
        'narration'=>$request->narration,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
        'size_counter'=>'0',
    );
 
    BuyerJobCardModel::insert($data1);
 

    $color_id = $request->input('color_id');
  //  print_r(count($color_id));
  
 
    if(count($color_id)>0)
    {
    
    for($x=0; $x<count($color_id); $x++) {
        # code...

 
            $data2=array(
                
            'po_code' =>$request->po_code,
            'po_date' => $request->po_date,
            'Ac_code'=>$request->Ac_code, 
            'fg_id'=>$request->fg_id,
            'style_no'=>$request->style_no,
            'color_id' => $request->color_id[$x],
            'sz_code' => $request->sz_code[$x],
            'qty' => $request->production_qty[$x],
             );
             
             BuyerJobCardDetail::insert($data2);
             
         }
         
         
         
      //DB::enableQueryLog();  
             
             
            //   $query = DB::getQueryLog();
            //  $query = end($query);
            //  dd($query);

             
             
    }

   
    $samples = $request->input('sample');
    if(count($samples)>0)
    { 
            for($x=0; $x<count($samples); $x++) 
            {
                $sample_id = isset($samples[$x]) ? 1 : 0;
            if($sample_id)
            {            
                # code...
                     $data3=array(
                        'po_code' =>$request->po_code,
                        'po_date' => $request->po_date,
                        'sample_id' => $samples[$x],
                        'sample_comp_date' => $request->sample_comp_date[$x],
                        'sample_tentative_date' => $request->sample_tentative_date[$x],
                         );
            }
               BuyerJobCardSampleDetail::insert($data3);
            }
           
    }
 

      //   DB::enableQueryLog(); 
             DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='BUYER_JOB_CARD'");
    //  $query = DB::getQueryLog();
    //          $query = end($query);
    //          dd($query);

    return redirect()->route('BuyerJobCard.index');








    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BuyerJobCardModel  $buyerJobCardModel
     * @return \Illuminate\Http\Response
     */
    public function show(BuyerJobCardModel $buyerJobCardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BuyerJobCardModel  $buyerJobCardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $JobStatusList= DB::table('job_status_master')->get();
        $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();
        $SampleList= DB::table('samples_master')->get();
        $BuyerJobCardMasterList = BuyerJobCardModel::find($id);
        // DB::enableQueryLog();
        $job_card_detailslist = BuyerJobCardDetail::join('color_master','color_master.color_id', '=', 'job_card_details.color_id')
        ->where('job_card_details.po_code','=', $BuyerJobCardMasterList->po_code)->get(['job_card_details.*','color_master.color_name']);
        

        $SampleSetList = BuyerJobCardSampleDetail::join('samples_master','samples_master.sample_id', '=', 'job_card_sample_details.sample_id')
        ->where('job_card_sample_details.po_code','=', $BuyerJobCardMasterList->po_code)->get(['job_card_sample_details.*','samples_master.sample_name']);
    
    $color_ids = explode(',', $BuyerJobCardMasterList->color_id);  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
  return view('BuyerJobCardMasterEdit',compact('BuyerJobCardMasterList','CPList', 'SizeList','SampleList','SampleSetList', 'Ledger','FGList','BrandList','SeasonList','ColorList','color_ids', 'JobStatusList','job_card_detailslist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BuyerJobCardModel  $buyerJobCardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $po_code)
    {
        $this->validate($request, [
             
            'po_code'=> 'required',
            'po_date'=> 'required',
            'cp_id'=> 'required',
            'Ac_code'=> 'required', 
            'fg_id'=> 'required',
            'style_no'=> 'required',
           'start_date'=> 'required',
            'end_date'=> 'required',
            'job_status_id'=> 'required',
            'brand_id'=> 'required',
            'season_id'=> 'required',
            'ppk_ratio'=> 'required',
            'rate_per_piece'=> 'required',
            'prod_qty'=> 'required',
            'total_amount'=> 'required',
            'c_code'=> 'required',
              
]);

 // Upload style_pic_path
    $style_pic_path=$request->file('style_pic_path');
    if($style_pic_path) 
    {
   
    $image = $request->file('style_pic_path');
    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
 
    $destinationPath = public_path('/thumbnail');
    $img = Image::make($image->getRealPath());
    $img->resize(100, 100, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
    $StyleImageName=$input['imagename'];
    
    unlink('thumbnail/'.$request->style_pic_pathold);
    unlink('images/'.$request->style_pic_pathold);
    
}
else
{
    $StyleImageName=$request->style_pic_pathold;
}

// Upload style_pic_path End
 
// Upload File1
if($request->hasFile('doc_path1')) 
{
    $fileName = time().'.'.$request->doc_path1->extension();  
    $request->doc_path1->move(public_path('uploads'), $fileName);
    $fullTempFilePath= public_path('uploads/');
    $output = shell_exec("shrink " . $fullTempFilePath  . $fullTempFilePath . "-compressed ");
    shell_exec("mv " . $fullTempFilePath . "-compressed " . $fullTempFilePath);
    $fullTempFilePath=$fileName2;
    // Compress and Save  File1 End
   
        unlink('uploads/'.$request->doc_path1old);
     
}
 else
{
    $fullTempFilePath=$request->doc_path1old;
}

// Upload File1
if($request->hasFile('doc_path2')) 
{
    $fileName2 = time().'.'.$request->doc_path2->extension();  
    $request->doc_path2->move(public_path('uploads'), $fileName2);
    $fullTempFilePath2= public_path('uploads/');
    $output = shell_exec("shrink " . $fullTempFilePath2  . $fullTempFilePath2 . "-compressed ");
    shell_exec("mv " . $fullTempFilePath2 . "-compressed " . $fullTempFilePath2);
    $fullTempFilePath2=$fileName2;
    
    unlink('uploads/'.$request->doc_path2old);
     
}
 else
{
    $fullTempFilePath2=$request->doc_path2old;
}
// Samples Required
$development_sample=isset($request->development_sample) ? 1 : 0;
$fit_sample=isset($request->fit_sample) ? 1 : 0;
$production_sample=isset($request->production_sample) ? 1 : 0;
$fpt_sample=isset($request->fpt_sample) ? 1 : 0;
$gpt_sample=isset($request->gpt_sample) ? 1 : 0;
$sealer=isset($request->sealer) ? 1 : 0;
$shipment=isset($request->shipment) ? 1 : 0;
$photoshoot=isset($request->photoshoot) ? 1 : 0;
// End Samples Required
 
$color_ids = implode(',', $request->color_ids);

$data1=array(
       
    'po_code'=>$request->po_code, 'po_date'=>$request->po_date,
    'cp_id'=>$request->cp_id,
    'Ac_code'=>$request->Ac_code, 
    'fg_id'=>$request->fg_id, 'style_no'=>$request->style_no,'style_pic_path'=>$StyleImageName,
    'doc_path1'=>$fullTempFilePath,'doc_path_2'=>$fullTempFilePath2,
    'comment_guidance'=>$request->comment_guidance, 'start_date'=>$request->start_date,
     'end_date'=>$request->end_date, 'job_status_id'=>$request->job_status_id, 'brand_id'=>$request->brand_id,
    'season_id'=>$request->season_id, 'prod_qty'=>$request->prod_qty,  'rate_per_piece'=>$request->rate_per_piece, 
    'total_amount'=>$request->total_amount,
    'ppk_ratio'=>$request->ppk_ratio, 
    'color_id'=>$color_ids,
    'piece_avg'=>$request->piece_avg,
    'narration'=>$request->narration,
    'userId'=>$request->userId,
    'delflag'=>'0',
    'c_code'=>$request->c_code,
    'created_at'=>$request->created_at,
    'size_counter'=>$request->size_counter,
);

   
$BJobCardList = BuyerJobCardModel::findOrFail($request->input('po_code'));  
$BJobCardList->fill($data1)->save();


DB::table('job_card_details')->where('po_code', $request->input('po_code'))->delete();
DB::table('job_card_sample_details')->where('po_code', $request->input('po_code'))->delete();
 

$color_id = $request->input('color_id');
if(count($color_id)>0)
{

for($x=0; $x<count($color_id); $x++) {
    # code...
  
$data2=array(
            
            'po_code' =>$request->po_code,
            'po_date' => $request->po_date,
            'Ac_code'=>$request->Ac_code, 
            'fg_id'=>$request->fg_id,
            'style_no'=>$request->style_no,
            'color_id' => $request->color_id[$x],
            'sz_code' => $request->sz_code[$x],
             'qty' => $request->production_qty[$x],
             );
        
         BuyerJobCardDetail::insert($data2);
        


        }
    }

    
    $samples = $request->input('sample');
    if(count($samples)>0)
    { 
            for($x=0; $x<count($samples); $x++) 
            {
                $sample_id = isset($samples[$x]) ? 1 : 0;
            if($sample_id)
            {            
                # code...
                     $data3=array(
                        'po_code' =>$request->po_code,
                        'po_date' => $request->po_date,
                        'sample_id' => $samples[$x],
                        'sample_comp_date' => $request->sample_comp_date[$x],
                        'sample_tentative_date' => $request->sample_tentative_date[$x],
                         );
            }
               BuyerJobCardSampleDetail::insert($data3);
            }
           
    }
 

return redirect()->route('BuyerJobCard.index')->with('message', 'Update Record Succesfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BuyerJobCardModel  $BuyerJobCardModel
     * @return \Illuminate\Http\Response
     */
      
     
    public function destroy($id)
    {
        DB::table('job_card_master')->where('po_code', $id)->delete();
        DB::table('job_card_details')->where('po_code', $id)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
}
