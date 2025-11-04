<?php
namespace App\Http\Controllers;
use App\Models\FabricTrimCardMasterModel;
use App\Models\FabricTrimCardDetailModel;
use App\Models\FabricTrimCardMatchDetailModel; 
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\QualityModel;
use App\Models\PartModel;
use App\Models\BuyerJobCardModel;
use Image;
use Illuminate\Support\Facades\DB;
use PDF;
use Mail;
use Session;
class FabricTrimCardMasterController extends Controller
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
        ->where('form_id', '33')
        ->first();
        

        //   DB::enableQueryLog();
        $FabricTrimbCardList = FabricTrimCardMasterModel::join('usermaster', 'usermaster.userId', '=', 'fabric_trim_card_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_trim_card_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'fabric_trim_card_master.fg_id')
        
        ->where('fabric_trim_card_master.delflag','=', '0')
        ->get(['fabric_trim_card_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name' ]);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('FabricTrimCardMasterList', compact('FabricTrimbCardList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FABRIC_TRIM_CARD'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $PartList =DB::table('part_master')->get();
        
        //$JobCodeList = BuyerJobCardModel::select('po_code as job_code')->where('job_status_id','=','1')->distinct()->get();
         $JobCodeList = DB::select(DB::raw("SELECT tr_code   FROM `buyer_purchse_order_master` WHERE `job_status_id`=1 
         and `style_no` NOT IN (select style_no  from fabric_trim_card_master)")); 
        
      
        //$StyleList = BuyerJobCardModel::select('style_no')->where('job_status_id','=','1')->get();
        
        $StyleList = DB::select(DB::raw("SELECT * FROM `buyer_purchse_order_master` WHERE `job_status_id`=1 and `style_no` NOT IN (select style_no  from fabric_trim_card_master)")); 
        
        
        
        return view('FabricTrimCardMaster',compact('Ledger','QualityList', 'CPList',  'FGList', 'PartList' ,'ColorList','counter_number','JobCodeList', 'StyleList' ));

         
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    $SessionValue=Session::get('FabricTrimCard');
    if($SessionValue==1)
    {
       session()->put('FabricTrimCard','0');
       $this->validate($request, [
             
            'ftc_code'=>'required',
            'ftc_date'=>'required',
            'job_code'=>'required',
            'style_no'=>'required',
            
            'Ac_code'=>'required',
            'fg_id'=>'required',
            'userId'=>'required',
            'c_code'=>'required',

]);


             //DB::enableQueryLog();

  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','FABRIC_TRIM_CARD')
   ->where('firm_id','=',1)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;   





 
$data1=array(

    'ftc_code'=>$TrNo,
    'ftc_date'=>$request->ftc_date,
    'style_no'=>$request->style_no,
    'Ac_code'=>$request->Ac_code,
    
    'fg_id'=>$request->fg_id,
    'job_code'=>$request->job_code,
    'narration'=>$request->narration,
    'delflag'=>'0',
    'userId'=>$request->userId,
    'c_code'=>$request->c_code,
);

FabricTrimCardMasterModel::insert($data1);


//   DB::enableQueryLog(); 
         DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FABRIC_TRIM_CARD'");
//  $query = DB::getQueryLog();
//          $query = end($query);
//          dd($query);




$color_id = $request->input('color_id');
if(count($color_id)>0)
{



    $file = $request->file('fabric_image');       

 for($x=0; $x<count($color_id); $x++)
 {
  //print_r($file[$x]->getClientOriginalName());
 if(isset($file[$x]))
 { 
      $ImageName  = time().$x.'Fab.'.$file[$x]->getClientOriginalExtension();
    $destinationPath3 = public_path('/thumbnail');
    $img = Image::make($file[$x]->getRealPath());
    $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath3.'/'.$ImageName);
    $destinationPath3 = public_path('/images/');
    // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $file[$x]->move($destinationPath3, $ImageName);
   // $ImageName1= $input['imagename'];
 }
 else
{
    $ImageName='';
}

 if($ImageName!='')
{
        // Compress and Save  File1 End
       //print_r($ImageName.'-'.$x.',');

        $data2=array(
          
            'ftc_code'=>$TrNo,
            'ftc_date'=>$request->ftc_date,
            'Ac_code'=>$request->Ac_code,
            'job_code'=>$request->job_code,
            
            'style_no'=>$request->style_no,
            'fg_id'=>$request->fg_id, 
            'color_id'=>$request->color_id[$x],
            'part_id'=>$request->part_id[$x],
            'width'=>$request->width[$x],
            'average'=>$request->average[$x],
            'fabric_image'=>$ImageName,
            'remark'=>$request->remark[$x],
        );

        $ImageName='';
}
else
{
    $data2=array(
          
        'ftc_code'=>$TrNo,
        'ftc_date'=>$request->ftc_date,
        'Ac_code'=>$request->Ac_code,
        'job_code'=>$request->job_code,
       
        'style_no'=>$request->style_no,
        'fg_id'=>$request->fg_id, 
        'color_id'=>$request->color_id[$x],
        
        'part_id'=>$request->part_id[$x],
        'width'=>$request->width[$x],
        'average'=>$request->average[$x],
        'fabric_image'=>'',
        'remark'=>$request->remark[$x],
        
);
} 
        

FabricTrimCardDetailModel::insert($data2);
        
        } 
 
 
}



$trim_color_id = $request->input('trim_color_id');
if(count($trim_color_id)>0)
{
 
    $file = $request->file('fabric_images');       

 for($x=0; $x<count($trim_color_id); $x++)
 {
  //print_r($file[$x]->getClientOriginalName());
 if(isset($file[$x]))
 { 
      $ImageName  = time().$x.'TrimFab.'.$file[$x]->getClientOriginalExtension();
    $destinationPath3 = public_path('/thumbnail');
    $img = Image::make($file[$x]->getRealPath());
    $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath3.'/'.$ImageName);
    $destinationPath3 = public_path('/images/');
    // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $file[$x]->move($destinationPath3, $ImageName);
   // $ImageName1= $input['imagename'];
 }
 else
{
    $ImageName='';
}

 if($ImageName!='')
{
        // Compress and Save  File1 End
       //print_r($ImageName.'-'.$x.',');

        $data3=array(
          
            'ftc_code'=>$TrNo,
            'ftc_date'=>$request->ftc_date,
            'Ac_code'=>$request->Ac_code,
            'job_code'=>$request->job_code,
            
            'style_no'=>$request->style_no,
            'fg_id'=>$request->fg_id, 
            'body_color_id'=>$request->body_color_id[$x],
            'trim_color_id'=>$request->trim_color_id[$x],
            'part_id'=>$request->part_ids[$x],
            'width'=>$request->widths[$x],
            'average'=>$request->averages[$x],
            'fabric_image'=>$ImageName,
            'remark'=>$request->remarks[$x],
        );

        $ImageName='';
}
else
{
    $data3=array(
          
        'ftc_code'=>$TrNo,
        'ftc_date'=>$request->ftc_date,
        'Ac_code'=>$request->Ac_code,
        'job_code'=>$request->job_code,
        
        'style_no'=>$request->style_no,
        'fg_id'=>$request->fg_id, 
        'body_color_id'=>$request->body_color_id[$x],
        'trim_color_id'=>$request->trim_color_id[$x],
        
        'part_id'=>$request->part_ids[$x],
        'width'=>$request->widths[$x],
        'average'=>$request->averages[$x],
        'fabric_image'=>'',
        'remark'=>$request->remarks[$x],
        
);
} 
        

FabricTrimCardMatchDetailModel::insert($data3);
        
        } 
 
 

 
}

  

        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $PartList =DB::table('part_master')->get();
       
          $FabricTrimCardMasterList = FabricTrimCardMasterModel::find($request->ftc_code);
        
        // // DB::enableQueryLog();
        $FabricTrimbCardDetailsList = FabricTrimCardDetailModel::join('color_master','color_master.color_id', '=', 'fabric_trim_card_details.color_id')
        ->where('fabric_trim_card_details.ftc_code','=', $FabricTrimCardMasterList->ftc_code)->get(['fabric_trim_card_details.*','color_master.color_name']);
  
         $FabricTrimbCardMatchDetailsList = FabricTrimCardMatchDetailModel::join('color_master as c1','c1.color_id', '=', 'fabric_trim_card_match_details.body_color_id')
        ->join('color_master as c2','c2.color_id', '=', 'fabric_trim_card_match_details.trim_color_id')
        ->where('fabric_trim_card_match_details.ftc_code','=', $FabricTrimCardMasterList->ftc_code)->get(['fabric_trim_card_match_details.*','c1.color_name as color1' ,'c2.color_name as color2']);
   
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        
      $FabricTrimCardMasterList = FabricTrimCardMasterModel::join('usermaster', 'usermaster.userId', '=', 'fabric_trim_card_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_trim_card_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'fabric_trim_card_master.fg_id')
        ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_trim_card_master.cp_id')
        ->where('fabric_trim_card_master.ftc_code','=', $request->ftc_code)
        ->get(['fabric_trim_card_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','cp_master.cp_name']);
        
        $FabricTrimCardDetailsList = FabricTrimCardDetailModel::join('color_master','color_master.color_id', '=', 'fabric_trim_card_details.color_id')
        ->join('part_master','part_master.part_id', '=', 'fabric_trim_card_details.part_id')
        ->where('fabric_trim_card_details.ftc_code','=', $request->ftc_code)->get(['fabric_trim_card_details.*','color_master.color_name', 'part_master.part_name']);
  
        $FabricTrimCardMatchDetailsList = FabricTrimCardMatchDetailModel::join('color_master as c1','c1.color_id', '=', 'fabric_trim_card_match_details.body_color_id')
        ->join('color_master as c2','c2.color_id', '=', 'fabric_trim_card_match_details.trim_color_id')
       ->join('part_master','part_master.part_id', '=', 'fabric_trim_card_match_details.part_id')
        ->where('fabric_trim_card_match_details.ftc_code','=', $request->ftc_code)->get(['fabric_trim_card_match_details.*','c1.color_name as color1' , 'part_master.part_name','c2.color_name as color2']);
   
     
        
         $dataEmail["email"] = array(
            
            "seaquidtechnology@gmail.com" 
                    );
                    
       $dataEmail["title"] = "Fabric Trim Card Created for Style No:".$request->style_no; 
      $dataEmail["body"] = "";
      
      //$pdf = PDF::loadView('BundleBarcodePrint', compact('BundleList','BundleBarcodeDetailList','JobPartList') );
      $pdf = PDF::loadView('rptSingleFabricTrimCard', compact('FabricTrimCardMasterList','Ledger', 'QualityList',    'FGList', 'PartList' ,'ColorList','FabricTrimCardDetailsList','FabricTrimCardMatchDetailsList'));
  
   //return $pdf->download('sample.pdf');
      Mail::send('rptSingleFabricTrimCard', compact('FabricTrimCardMasterList','Ledger', 'QualityList','FGList', 'PartList' ,'ColorList','FabricTrimCardDetailsList','FabricTrimCardMatchDetailsList'), function($message)use($dataEmail, $pdf) {
          $message->to($dataEmail["email"], $dataEmail["email"])
                  ->subject($dataEmail["title"])
                  ->attachData($pdf->output(), "FabricTrimCard.pdf");
      });
           
         
    return view('rptSingleFabricTrimCard',compact('FabricTrimCardMasterList', 'FabricTrimCardDetailsList','FabricTrimCardMatchDetailsList'));
      
  //return view('rptSingleFabricTrimCard',compact('FabricTrimCardMasterList','Ledger', 'QualityList', 'CPList',  'FGList', 'PartList' ,'ColorList','FabricTrimbCardDetailsList','FabricTrimbCardMatchDetailsList'));
          

}
else
{
     return redirect()->route('FabricTrimCard.index')->with('message', 'Record Already Saved..!!');
}
 
 
//return redirect()->route('FabricTrimCard.index')->with('message', 'New Record Saved Succesfully..!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricTrimCardMasterModel  $fabricTrimCardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricTrimCardMasterModel $fabricTrimCardMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricTrimCardMasterModel  $fabricTrimCardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $id=base64_decode($id);
        
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $PartList =DB::table('part_master')->get();
          $CPList= DB::table('cp_master')->get();
        $FabricTrimCardMasterList = FabricTrimCardMasterModel::find($id);
        
        $FabricTrimbCardDetailsList = FabricTrimCardDetailModel::join('color_master','color_master.color_id', '=', 'fabric_trim_card_details.color_id')
        ->where('fabric_trim_card_details.ftc_code','=', $FabricTrimCardMasterList->ftc_code)
        ->get(['fabric_trim_card_details.*','color_master.color_name']);
   
        //  DB::enableQueryLog();
        $JobCodeList = DB::select(DB::raw("SELECT tr_code as job_code FROM `buyer_purchse_order_master` WHERE
        `job_status_id`=1 and `tr_code` NOT IN (select job_code  from fabric_trim_card_master) 
        union (select '".$FabricTrimCardMasterList->job_code."')")); 
        //   $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $StyleList = DB::select(DB::raw("SELECT style_no FROM `buyer_purchse_order_master` WHERE `job_status_id`=1
        and `style_no` NOT IN (select style_no  from fabric_trim_card_master)
        union (select '".$FabricTrimCardMasterList->style_no."')")); 
       
        $FabricTrimbCardMatchDetailsList = FabricTrimCardMatchDetailModel::where('fabric_trim_card_match_details.ftc_code','=', $FabricTrimCardMasterList->ftc_code)->get(['fabric_trim_card_match_details.*']);
        return view('FabricTrimCardMasterEdit',compact('FabricTrimCardMasterList','Ledger', 'QualityList','JobCodeList','StyleList', 'FGList','CPList', 'PartList' ,'ColorList','FabricTrimbCardDetailsList','FabricTrimbCardMatchDetailsList'));
        
        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricTrimCardMasterModel  $fabricTrimCardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FabricTrimCardMasterModel $fabricTrimCardMasterModel)
    {
       

        $this->validate($request, [
             
            'ftc_code'=>'required',
            'ftc_date'=>'required',
            'job_code'=>'required',
            'style_no'=>'required',
            'Ac_code'=>'required',
            'fg_id'=>'required',
            'userId'=>'required',
            'c_code'=>'required',

]);
 
$data1=array(
       
    'ftc_code'=>$request->ftc_code,
    'ftc_date'=>$request->ftc_date,
    'style_no'=>$request->style_no,
    'Ac_code'=>$request->Ac_code,
    'fg_id'=>$request->fg_id,
    'job_code'=>$request->job_code,
    'narration'=>$request->narration,
    'delflag'=>'0',
    'userId'=>$request->userId,
    'c_code'=>$request->c_code,
    
);

 

$FabricTrimCardList = FabricTrimCardMasterModel::findOrFail($request->input('ftc_code'));  
$FabricTrimCardList->fill($data1)->save();


DB::table('fabric_trim_card_details')->where('ftc_code', $request->input('ftc_code'))->delete();
DB::table('fabric_trim_card_match_details')->where('ftc_code', $request->input('ftc_code'))->delete();


$color_id = $request->input('color_id');
if(count($color_id)>0)
{ 
 $file = $request->file('fabric_image');       

 for($x=0; $x<count($color_id); $x++)
 {
  //print_r($file[$x]->getClientOriginalName());
 if(isset($file[$x]))
 { 
      $ImageName  = time().$x.'Fab.'.$file[$x]->getClientOriginalExtension();
    $destinationPath3 = public_path('/thumbnail');
    $img = Image::make($file[$x]->getRealPath());
    $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath3.'/'.$ImageName);
    $destinationPath3 = public_path('/images/');
    // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $file[$x]->move($destinationPath3, $ImageName);
   // $ImageName1= $input['imagename'];
 }
 else
{
    $ImageName='';
}

 if($ImageName!='')
{
        // Compress and Save  File1 End
       //print_r($ImageName.'-'.$x.',');

        $data2=array(
          
            'ftc_code'=>$request->ftc_code,
            'ftc_date'=>$request->ftc_date,
            'Ac_code'=>$request->Ac_code,
            'job_code'=>$request->job_code,
            'style_no'=>$request->style_no,
            'fg_id'=>$request->fg_id, 
            'color_id'=>$request->color_id[$x],
            'part_id'=>$request->part_id[$x],
            'width'=>$request->width[$x],
            'average'=>$request->average[$x],
            'fabric_image'=>$ImageName,
            'remark'=>$request->remark[$x],
        );

        $ImageName='';
}
else
{
    $data2=array(
          
        'ftc_code'=>$request->ftc_code,
        'ftc_date'=>$request->ftc_date,
        'Ac_code'=>$request->Ac_code,
        'job_code'=>$request->job_code,
        'style_no'=>$request->style_no,
        'fg_id'=>$request->fg_id, 
        'color_id'=>$request->color_id[$x],
        'part_id'=>$request->part_id[$x],
        'width'=>$request->width[$x],
        'average'=>$request->average[$x],
        'fabric_image'=>$request->fabric_img_path_empty[$x],
        'remark'=>$request->remark[$x],
);
} 
       
FabricTrimCardDetailModel::insert($data2);
        
        } 
    }



$trim_color_id = $request->input('trim_color_id');
if(count($trim_color_id)>0)
{
 
    $file = $request->file('fabric_images');       

 for($x=0; $x<count($trim_color_id); $x++)
 {
  //print_r($file[$x]->getClientOriginalName());
 if(isset($file[$x]))
 { 
      $ImageName  = time().$x.'TrimFab.'.$file[$x]->getClientOriginalExtension();
    $destinationPath3 = public_path('/thumbnail');
    $img = Image::make($file[$x]->getRealPath());
    $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath3.'/'.$ImageName);
    $destinationPath3 = public_path('/images/');
    // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $file[$x]->move($destinationPath3, $ImageName);
   // $ImageName1= $input['imagename'];
 }
 else
{
    $ImageName='';
}

 if($ImageName!='')
{
        // Compress and Save  File1 End
       //print_r($ImageName.'-'.$x.',');

        $data3=array(
          
            'ftc_code'=>$request->ftc_code,
            'ftc_date'=>$request->ftc_date,
            'Ac_code'=>$request->Ac_code,
            'job_code'=>$request->job_code,
            'style_no'=>$request->style_no,
            'fg_id'=>$request->fg_id, 
            'body_color_id'=>$request->body_color_id[$x],
            'trim_color_id'=>$request->trim_color_id[$x],
            'part_id'=>$request->part_ids[$x],
            'width'=>$request->widths[$x],
            'average'=>$request->averages[$x],
            'fabric_image'=>$ImageName,
            'remark'=>$request->remarks[$x],
        );

        $ImageName='';
}
else
{
    $data3=array(
          
        'ftc_code'=>$request->ftc_code,
        'ftc_date'=>$request->ftc_date,
        'Ac_code'=>$request->Ac_code,
        'job_code'=>$request->job_code,
        'style_no'=>$request->style_no,
        'fg_id'=>$request->fg_id, 
        'body_color_id'=>$request->body_color_id[$x],
        'trim_color_id'=>$request->trim_color_id[$x],
        'part_id'=>$request->part_ids[$x],
        'width'=>$request->widths[$x],
        'average'=>$request->averages[$x],
        'fabric_image'=>'',
        'remark'=>$request->remarks[$x],
        
);
} 
         
FabricTrimCardMatchDetailModel::insert($data3);
        
        } 
  
}
 
        $FabricTrimCardMasterList = FabricTrimCardMasterModel::join('usermaster', 'usermaster.userId', '=', 'fabric_trim_card_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_trim_card_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'fabric_trim_card_master.fg_id')
        
        ->where('fabric_trim_card_master.ftc_code','=', $request->ftc_code)
        ->get(['fabric_trim_card_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name' ]);
        
        $FabricTrimCardDetailsList = FabricTrimCardDetailModel::join('color_master','color_master.color_id', '=', 'fabric_trim_card_details.color_id')
        ->join('part_master','part_master.part_id', '=', 'fabric_trim_card_details.part_id')
        ->where('fabric_trim_card_details.ftc_code','=', $request->ftc_code)->get(['fabric_trim_card_details.*','color_master.color_name', 'part_master.part_name']);
  
        $FabricTrimCardMatchDetailsList = FabricTrimCardMatchDetailModel::join('color_master as c1','c1.color_id', '=', 'fabric_trim_card_match_details.body_color_id')
        ->join('color_master as c2','c2.color_id', '=', 'fabric_trim_card_match_details.trim_color_id')
        ->join('part_master','part_master.part_id', '=', 'fabric_trim_card_match_details.part_id')
        ->where('fabric_trim_card_match_details.ftc_code','=', $request->ftc_code)->get(['fabric_trim_card_match_details.*','c1.color_name as color1', 'part_master.part_name','c2.color_name as color2']);
       
    if(isset($request->SendMail)){ 
     
     $data["email"] = array(
            "seaquidtechnology@gmail.com",
            
                    );
      $data["title"] = "Attention..! Fabric Trim Card Created for Style No:".$request->style_no." is Updated"; 
    $data["body"] = "";
    $pdf = PDF::loadView('rptSingleFabricTrimCard', compact('FabricTrimCardMasterList', 'FabricTrimCardDetailsList','FabricTrimCardMatchDetailsList'));
    Mail::send('rptSingleFabricTrimCard', compact('FabricTrimCardMasterList', 'FabricTrimCardDetailsList','FabricTrimCardMatchDetailsList'), function($message)use($data, $pdf) {
          $message->to($data["email"], $data["email"])
                  ->subject($data["title"])
                  ->attachData($pdf->output(),'Fabric Trim Card.pdf');
      });
     
     
      }
        return view('rptSingleFabricTrimCard',compact('FabricTrimCardMasterList', 'FabricTrimCardDetailsList','FabricTrimCardMatchDetailsList'));
     }

     public function getJobCardDetails(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
         $style_no= $request->input('style_no');
        
        if($style_no!='')
        {
            $MasterdataList = DB::select("select tr_code as po_code, Ac_code, style_no,fg_id from buyer_purchse_order_master where buyer_purchse_order_master.job_status_id=1 and style_no='". $style_no."'");
        }
        elseif($sales_order_no!='')
        {
            $MasterdataList = DB::select("select tr_code as po_code, Ac_code, style_no,fg_id from buyer_purchse_order_master where buyer_purchse_order_master.job_status_id=1 and tr_code='". $sales_order_no."'");
        }
        
        return json_encode($MasterdataList);
    
    }
 
    public static function getColorAverage(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
        $color_id= $request->input('color_id');
        $part_id= $request->input('part_id');
        $average=$request->input('average');
        $total_qty = DB::table('buyer_purchase_order_size_detail')
        ->where('buyer_purchase_order_size_detail.color_id', '=', $color_id)
        ->where('buyer_purchase_order_size_detail.tr_code', '=', $sales_order_no)
        ->sum('size_qty');

        $array[0]['required_meter']= ($average * $total_qty);

        $received_meter = DB::table('inward_details')
        ->where('inward_details.job_code', '=', $sales_order_no)
        ->where('inward_details.color_id', '=', $color_id)
        ->where('inward_details.part_id', '=', $part_id)
        ->sum('meter');
        $array[0]['received_meter']= ($received_meter);
        $array[0]['difference_meter']= (($average * $total_qty)-$received_meter);
        return json_encode($array);
    
    }


 public static function getColorAverageTrim(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
        $body_color_id= $request->input('body_color_id');
        $trim_color_id= $request->input('trim_color_id');
        $average=$request->input('average');
        $part_id= $request->input('part_id');
        $total_qty = DB::table('buyer_purchase_order_size_detail')
        ->where('buyer_purchase_order_size_detail.color_id', '=', $body_color_id)
        ->where('buyer_purchase_order_size_detail.tr_code', '=', $sales_order_no)
        ->sum('size_qty');

        $array[0]['required_meter']= ($average * $total_qty);

        $received_meter = DB::table('inward_details')
        ->where('inward_details.job_code', '=', $sales_order_no)
        ->where('inward_details.color_id', '=', $trim_color_id)
        ->where('inward_details.part_id', '=', $part_id)
        ->sum('meter');
        
        $array[0]['received_meter']= ($received_meter);
        $array[0]['difference_meter']= (($average * $total_qty)-$received_meter);
        return json_encode($array);
    
    }




    public static function getColorAveragesTrim($sales_order_no,$body_color_id,$trim_color_id,$average,$part_id)
    { 
         
         
        $total_qty = DB::table('buyer_purchase_order_size_detail')
        ->where('buyer_purchase_order_size_detail.color_id', '=', $body_color_id)
        ->where('buyer_purchase_order_size_detail.tr_code', '=', $sales_order_no)
        ->sum('size_qty');

        $array[0]['required_meter']= ($average * $total_qty);

        $received_meter = DB::table('inward_details')
        ->where('inward_details.job_code', '=', $sales_order_no)
        ->where('inward_details.color_id', '=', $trim_color_id)
        ->where('inward_details.part_id', '=', $part_id)
        ->sum('meter');
        $array[0]['received_meter']= ($received_meter);
        $array[0]['difference_meter']= (($average * $total_qty)-$received_meter);
        return json_encode($array);
    
    }

public static function getColorAverages($job_code,$color_id,$average,$part_id)
    { 
        $sales_order_no= $job_code;
        $color_id= $color_id;
        $average=$average;
        $total_qty = DB::table('buyer_purchase_order_size_detail')
        ->where('buyer_purchase_order_size_detail.color_id', '=', $color_id)
        ->where('buyer_purchase_order_size_detail.tr_code', '=', $sales_order_no)
        ->sum('size_qty');

        $array[0]['required_meter']= ($average * $total_qty);

        $received_meter = DB::table('inward_details')
        ->where('inward_details.job_code', '=', $sales_order_no)
        ->where('inward_details.color_id', '=', $color_id)
        ->where('inward_details.part_id', '=', $part_id)
        ->sum('meter');
        $array[0]['received_meter']= ($received_meter);
        $array[0]['difference_meter']= (($average * $total_qty)-$received_meter);
        return json_encode($array);
    
    }


    public function getColorDetails(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
      
        $InwardFabric = DB::select("SELECT DISTINCT color_id
        FROM `buyer_purchase_order_size_detail` 
        where buyer_purchase_order_size_detail.tr_code='". $sales_order_no."'");
        $html = '';
   
    
    
$no=1;
foreach ($InwardFabric as $row) {
    $html .='<tr>';
   
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
$html.='<td> <select name="color_id[]"  id="color_id" style="width:100px;" required>
<option value="">--Select Color--</option>';

foreach($ColorList as  $row1)
{
    $html.='<option value="'.$row1->color_id.'"';
    $row1->color_id == $row->color_id ? $html.='selected="selected"' : ''; 
    $html.='>'.$row1->color_name.'</option>';
}
 
$html.='</select></td>';

$html.='<td> <select name="part_id[]"  id="part_id" style="width:100px;" required>
<option value="">--Select Part--</option>';
foreach($PartList as  $row1)
{
  $html.='<option value="'.$row1->part_id.'"';
  $row1->part_id == 1 ? $html.='selected="selected"' : ''; 
  $html.='>'.$row1->part_name.'</option>';
}
 
$html.='</select></td>';
$html.=' 
<td><input type="text"  name="width[]" value="0" id="width" style="width:80px;" required /></td>
<td><input type="text"  name="average[]" value="0" id="average" style="width:80px;" required /></td>
<td><input type="text"  name="required_meter[]" value="0" id="required_meter" style="width:80px;" required /></td>
<td><input type="text"  name="received_meter[]" value="0" id="received_meter" style="width:80px;" required /></td>
<td><input type="text"  name="difference_meter[]" value="0" id="difference_meter" style="width:80px;" required /></td>
<td><input type="file"  name="fabric_image[]" value="0" id="fabric_image" style="width:80px;"  />
<input type="hidden" name="fabric_img_path_empty[]"  id="fabric_img_path_empty" style="width:80px;" value="" />
<td><input type="text"  name="remark[]" value="" id="remark" style="width:80px;"   /></td>
</td>

<td><button type="button" onclick="insertcone(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;
    }
  return response()->json(['html' => $html]);
     }


  public function getTrimColorDetails(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
       
        $InwardFabric = DB::select("SELECT DISTINCT color_id
        FROM `buyer_purchase_order_size_detail` 
        where buyer_purchase_order_size_detail.tr_code='". $sales_order_no."'");
    
        $html = '';
   
    
    
$no=1;
foreach ($InwardFabric as $row) {
    $html .='<tr>';
   
$html .='
<td><input type="text" name="ids[]" value="'.$no.'" id="ids" style="width:50px;"/></td>';
$html.='<td> <select name="body_color_id[]"  id="body_color_id" style="width:100px;" required>
<option value="">--Select Body Color--</option>';

foreach($ColorList as  $row1)
{
    $html.='<option value="'.$row1->color_id.'"';

    $row1->color_id == $row->color_id ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->color_name.'</option>';
}
 
$html.='</select></td>';


$html.='<td> <select name="trim_color_id[]"  id="trim_color_id" style="width:100px;" required>
<option value="">--Select Trim Color--</option>';
foreach($ColorList as  $row1)
{
    $html.='<option value="'.$row1->color_id.'"';
    $html.='>'.$row1->color_name.'</option>';
}
$html.='</select></td>';

$html.='<td> <select name="part_ids[]"  id="part_ids" style="width:100px;" required>
<option value="">--Select Part--</option>';

foreach($PartList as  $row1)
{
    $html.='<option value="'.$row1->part_id.'"';
 $row1->part_id == 2 ? $html.='selected="selected"' : ''; 
    $html.='>'.$row1->part_name.'</option>';
}
 
$html.='</select></td>';
  
$html.=' 
<td><input type="text"  name="widths[]" value="0" id="widths" style="width:80px;" required /></td>
<td><input type="text"  name="averages[]" value="0" id="averages" style="width:80px;" required /></td>
<td><input type="text"  name="required_meters[]" value="0" id="required_meters" style="width:80px;" required /></td>
<td><input type="text"  name="received_meters[]" value="0" id="received_meters" style="width:80px;" required /></td>
<td><input type="text"  name="difference_meters[]" value="0" id="difference_meters" style="width:80px;" required /></td>
<td><input type="file"  name="fabric_images[]" value="0" id="fabric_images" style="width:80px;"  />
<input type="hidden" name="fabric_img_path_emptys[]"  id="fabric_img_path_emptys" style="width:80px;" value="" />
<td><input type="text"  name="remarks[]" value="" id="remarks" style="width:80px;"   /></td>
</td>

<td><button type="button" onclick="insertcone2(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;
    }
    
    return response()->json(['html' => $html]);
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricTrimCardMasterModel  $fabricTrimCardMasterModel
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        
        $id=base64_decode($id);
        
        DB::table('fabric_trim_card_master')->where('ftc_code', $id)->delete();
        DB::table('fabric_trim_card_details')->where('ftc_code', $id)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
}
