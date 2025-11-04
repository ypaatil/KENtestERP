<?php

namespace App\Http\Controllers;

use App\Models\MaterialInwardMasterModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\SeasonModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\SizeModel;
use Illuminate\Support\Facades\DB;
use App\Models\MaterialInwardDetailModel;
use Session;


class MaterialInwardController extends Controller
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
->where('form_id', '42')
->first();
        
       $InwardList = MaterialInwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'materialinwardmaster.user_id')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'materialinwardmaster.ac_code')
        ->join('transaction_type', 'transaction_type.transactionId', '=', 'materialinwardmaster.transactionId')
        ->get(['materialinwardmaster.*','usermaster.username','ledger_master.ac_name','transaction_type.transactionType']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('MaterialInwardList', compact('InwardList','chekform'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
       $JobStatusList= DB::table('job_status_master')->get();
       $SizeList= SizeModel::where('size_master.delflag','=', '0')->get(); 
        $Trtype= DB::table('transaction_type')->get();

        return view('MaterialInward',compact('Ledger','FGList','SizeList','BrandList','SeasonList','ColorList','ItemList','JobStatusList','Trtype'));

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
  ->where('type','=','Material_Inward')
   ->where('firm_id','=',1)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $InCode=$codefetch->code.'-'.$codefetch->tr_no;

        

$data1=array(
        'InCode'=>$InCode,
        'inward_date'=>$request->inward_date, 
        'job_code'=>$request->job_code, 
        'lot_no'=>$request->lot_no,
        'transactionId'=>$request->transactionId, 
        'ac_code'=>$request->ac_code, 
        'totalQty'=>$request->total_qty, 
        'totalMeter'=>$request->total_meter,
         'user_id'=>$request->userId,
        'narration'=>$request->narration,
    );
 
    MaterialInwardMasterModel::insert($data1);
 

    $color_id= $request->color_id;
     
    for($x=0; $x<count($color_id); $x++) {
        # code...

   if($request->color_id[$x]!=0)
   {

    $data2=array(
             'InCode' =>$InCode,
             'inward_date' => $request->inward_date,
             'transactionId' => $request->transactionId,
             'item_code' => $request->item_code[$x],
             'color_id' => $request->color_id[$x],
             'sz_code' => $request->sz_code[$x],
             'qty' => $request->production_qty[$x],
             'style_no' => $request->style_code[$x]
             );
            
             MaterialInwardDetailModel::insert($data2);


DB::table('materialinouttransaction')->insert([
'tr_no' => $InCode,
'tr_type' => 2,
'tr_date' => $request->input('inward_date'),
'job_code' => $request->job_code,
'lot_no' => $request->lot_no,
'transactionId' => $request->input('transactionId'), 
'ac_code' => $request->input('ac_code'), 
'item_code' => $request->item_code[$x],
'color_id' => $request->color_id[$x],
'sz_code' => $request->sz_code[$x],
'qty' => $request->production_qty[$x],
'style_no' => $request->style_code[$x],
'meter' => 0
]);

            
    }
            }


    
   
     $meter= $request->meter;
    for($i=0; $i<count($meter);$i++) {

if($request->meter[$i]!=0)
   {


 DB::table('inwardfabricdetails')->insert([
'InCode' => $InCode,
'inward_date' => $request->input('inward_date'),
'transactionId' => $request->input('transactionId'), 
'item_code' => $request->item_codefabric[$i],
'color_id' => $request->color_idfabric[$i],
'style_no' => $request->style_codefabric[$i],
'meter' => $request->meter[$i]
]);

 
DB::table('materialinouttransaction')->insert([
'tr_no' => $InCode,
'tr_type' => 2,
'tr_date' => $request->input('inward_date'),
'job_code' => $request->job_code,
'lot_no' => $request->lot_no,
'transactionId' => $request->input('transactionId'), 
'ac_code' => $request->input('ac_code'), 
'item_code' => $request->item_codefabric[$i],
'color_id' => $request->color_idfabric[$i],
'sz_code' => 0,
'qty' => 0,
'style_no' => $request->style_codefabric[$i],
'meter' => $request->meter[$i]
]);


}

}
       

     //   DB::enableQueryLog(); 
             DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='Material_Inward'");
    //  $query = DB::getQueryLog();
    //          $query = end($query);
    //          dd($query);
 
 
    return redirect()->route('MaterialInward.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialOutwardModel  $materialOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialOutwardModel $materialOutwardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialOutwardModel  $materialOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
       $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
       $JobStatusList= DB::table('job_status_master')->get();
       $SizeList= SizeModel::where('size_master.delflag','=', '0')->get(); 
        $Trtype= DB::table('transaction_type')->get();


    $MaterialOutwardMasterList = MaterialInwardMasterModel::find($id);
        // DB::enableQueryLog();


    // $MaterialOutwarddetailslist = MaterialOutwardDetailModel::where('matrialsizedetails.MoCode','=', $MaterialOutwardMasterList->MoCode)->get(['matrialsizedetails.*']);

       $MaterialOutwarddetailslist = DB::table('materialinwardsizedetails')
       ->where('materialinwardsizedetails.InCode','=',$MaterialOutwardMasterList->InCode)
       ->get();


       $fabricdetails = DB::table('inwardfabricdetails')
       ->where('inwardfabricdetails.InCode','=',$MaterialOutwardMasterList->InCode)
       ->get();


        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
  return view('MaterialInwardEdit',compact('MaterialOutwardMasterList','Ledger','FGList','SizeList','BrandList','SeasonList','ColorList','ItemList','JobStatusList','Trtype','MaterialOutwarddetailslist','fabricdetails'));    



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialOutwardModel  $materialOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $outcode)
    {
       


$data1=array(
        'InCode'=>$request->InCode,
        'inward_date'=>$request->inward_date, 
        'job_code'=>$request->job_code, 
        'lot_no'=>$request->lot_no,
        'transactionId'=>$request->transactionId, 
        'ac_code'=>$request->ac_code, 
        'totalQty'=>$request->total_qty, 
        'totalMeter'=>$request->total_meter,
         'user_id'=>$request->userId,
        'narration'=>$request->narration,
    );

 $outward = MaterialInwardMasterModel::findOrFail($outcode);  
 $outward->fill($data1)->save();


DB::table('materialinwardsizedetails')->where('InCode', $request->input('InCode'))->delete();
DB::table('inwardfabricdetails')->where('InCode', $request->input('InCode'))->delete();
DB::table('materialinouttransaction')->where('tr_no', $request->input('InCode'))->delete();
 

    $color_id = $request->input('color_id');
   
    for($x=0; $x<count($color_id); $x++) {
        # code...
  if($request->color_id[$x]!=0)
   {

    $data2=array(
             'InCode' =>$request->InCode,
             'inward_date' => $request->inward_date,
             'transactionId' => $request->transactionId,
             'item_code' => $request->item_code[$x],
             'color_id' => $request->color_id[$x],
             'sz_code' => $request->sz_code[$x],
             'qty' => $request->production_qty[$x],
             'style_no' => $request->style_code[$x]
             );
            
             MaterialInwardDetailModel::insert($data2);


       
DB::table('materialinouttransaction')->insert([
'tr_no' => $request->InCode,
'tr_type' => 2,
'tr_date' => $request->input('inward_date'),
'job_code' => $request->job_code,
'lot_no' => $request->lot_no,
'transactionId' => $request->input('transactionId'), 
'ac_code' => $request->input('ac_code'), 
'item_code' => $request->item_code[$x],
'color_id' => $request->color_id[$x],
'sz_code' => $request->sz_code[$x],
'qty' => $request->production_qty[$x],
'style_no' => $request->style_code[$x],
'meter' => 0
]);



            
    }
            }
        

    $meter = $request->input('meter');


  
    
    for($i=0; $i<count($meter);$i++) {

   if($request->meter[$i]!=0)
   {     

 DB::table('inwardfabricdetails')->insert([
'InCode' => $request->InCode,
'inward_date' => $request->input('inward_date'),
'transactionId' => $request->input('transactionId'), 
'item_code' => $request->item_codefabric[$i],
'color_id' => $request->color_idfabric[$i],
'style_no' => $request->style_codefabric[$i],
'meter' => $request->meter[$i]
]);


 DB::table('materialinouttransaction')->insert([
'tr_no' => $request->InCode,
'tr_type' => 2,
'tr_date' => $request->input('inward_date'),
'job_code' => $request->job_code,
'lot_no' => $request->lot_no,
'transactionId' => $request->input('transactionId'), 
'ac_code' => $request->input('ac_code'), 
'item_code' => $request->item_codefabric[$i],
'color_id' => $request->color_idfabric[$i],
'sz_code' => 0,
'qty' => 0,
'style_no' => $request->style_codefabric[$i],
'meter' => $request->meter[$i]
]);

 
}
}
       
    return redirect()->route('MaterialInward.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialInwardMasterModel  $materialInwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialInwardMasterModel $materialInwardMasterModel)
    {
        //
    }



}
