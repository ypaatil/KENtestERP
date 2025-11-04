<?php

namespace App\Http\Controllers;

use App\Models\BundleModel;
use Illuminate\Http\Request;
use App\Models\ColorModel;
use App\Models\SizeModel;
use App\Models\JobPartModel;
use App\Models\LedgerModel;
use App\Models\ItemModel;
use App\Models\TaskMasterModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\SizeDetailModel;
use App\Models\TaskDetailModel;
 
use App\Models\BuyerJobCardModel;
use App\Models\BundleBarcodeDetailModel;
use App\Models\BundleBarcodeSerialDetailModel;
use Session;
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
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
        ->where('form_id', '94')
        ->first();
        

        //  DB::enableQueryLog();
        $BundleBarcodeList = BundleModel::join('usermaster', 'usermaster.userId', '=', 'bundle_barcode_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bundle_barcode_master.vendorId')
        ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'bundle_barcode_master.mainstyle_id')
        ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'bundle_barcode_master.substyle_id')
        ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'bundle_barcode_master.fg_id')
        ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'bundle_barcode_master.vpo_code')
        ->where('bundle_barcode_master.delflag','=', '0')
        ->where('bundle_barcode_master.bb_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)')) 
        ->orderByRaw("STR_TO_DATE(bundle_barcode_master.created_at, '%Y-%m-%d %H:%i:%s') DESC")
        ->get(['bundle_barcode_master.*','usermaster.username','ledger_master.Ac_name', 'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name','vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no']);
       //   dd(DB::getQueryLog());
        return view('BundleBarcodeMasterList', compact('BundleBarcodeList','chekform'));
     
    }


    public function BundleBarcodeShowAll()
    { 
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '94')
        ->first();
        

        //   DB::enableQueryLog();
        $BundleBarcodeList = BundleModel::join('usermaster', 'usermaster.userId', '=', 'bundle_barcode_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bundle_barcode_master.vendorId')
        ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'bundle_barcode_master.mainstyle_id')
        ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'bundle_barcode_master.substyle_id')
        ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'bundle_barcode_master.fg_id')
        ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'bundle_barcode_master.vpo_code')
        ->where('bundle_barcode_master.delflag','=', '0') 
        ->orderByRaw("STR_TO_DATE(bundle_barcode_master.created_at, '%Y-%m-%d %H:%i:%s') DESC")
        ->get(['bundle_barcode_master.*','usermaster.username','ledger_master.Ac_name', 'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name','vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('BundleBarcodeMasterList', compact('BundleBarcodeList','chekform'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     
     
     
     
    
     
         public function AddBundleBarcode($jobcode,$taskid)
         {
             
            $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BUNDLE_BARCODE'");
            $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
            $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
            $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
             $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
            $CPList= DB::table('cp_master')->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
            $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
            $JobPartList= DB::table('job_part_master')->join('job_part_detail', 'job_part_detail.jpart_id','=','job_part_master.jpart_id')->get();
            $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();
            return view('BundleBarcode',compact('Ledger',  'CPList',  'FGList','SizeList', 'ColorList','counter_number','JobPartList','jobcode','taskid','MainStyleList','SubStyleList','FGList'));
             
         }
     
     
     
     
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BUNDLE_BARCODE'");
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        
        $JobPartList= DB::table('job_part_master')->where('delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
           
        $TaskList = DB::SELECT("SELECT * FROM task_master WHERE delflag=0 AND task_id NOT IN (SELECT task_id FROM bundle_barcode_master where delflag=0)");
        
        return view('BundleBarcode',compact('Ledger',  'CPList',  'FGList', 'TaskList', 'ItemList','counter_number','JobPartList','MainStyleList','SubStyleList','FGList' ));

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
             
            'bb_code'=>'required',
            'bb_date'=>'required',
            
            'task_id'=>'required',
            'fg_id'=>'required',
            'style_no'=>'required',
            'total_piece'=>'required',
            
            'c_code'=>'required',
             ]);




        //   DB::enableQueryLog();

  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','BUNDLE_BARCODE')
   ->where('firm_id','=',1)
  ->first();
//  $query = DB::getQueryLog();
// $query = end($query);
// dd($query); 
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no; 
 
                $serial=array();
                $size_serial_array='';
                $serial=$request->input('SizeSerialNo');
                for($y=0; $y<count($serial); $y++) 
                {
                    $size_serial_array=$size_serial_array.$request->SizeSerialNo[$y].',';
                }
                $size_serial_array=rtrim($size_serial_array,',');


                $data1=array(

                    'bb_code'=>$TrNo, 'bb_date'=>$request->bb_date,  
                     'vpo_code'=>$request->vpo_code, 'sales_order_no'=>$request->sales_order_no, 'vendorId'=>$request->vendorId,     'mainstyle_id'=>$request->mainstyle_id,    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,    'style_no'=>$request->style_no,    'style_description'=>$request->style_description,
                    
                       'sizes_array' => $request->sz_codes[0],
                      'task_id'=>$request->task_id,
                    'total_piece'=>$request->total_piece, 'narration'=>$request->narration,  'c_code' => $request->c_code,
                    'userId'=>$request->userId, 'delflag'=>'0',  'size_serial_array'=>$size_serial_array,
                    
                    
                );

               BundleModel::insert($data1);
               
                     
               DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='BUNDLE_BARCODE'");  
               $bno=Session::get('size_counter'); 
               DB::select("update task_master set size_counter='".$bno."' where  task_id='".$request->task_id."'");  
             
                $item_code = $request->input('item_code');
                if(count($item_code)>0)
                { 
                 
                for($x=0; $x<count($item_code); $x++) 
                {
                    # code...
                     
                      
                                $data2[]=array(
                                'bb_code' =>$TrNo,
                                'bb_date' => $request->bb_date,
                                'vpo_code'=>$request->vpo_code, 
                                'sales_order_no'=>$request->sales_order_no, 
                                'vendorId'=>$request->vendorId, 
                                'mainstyle_id'=>$request->mainstyle_id,
                                'substyle_id'=>$request->substyle_id,
                                'fg_id'=>$request->fg_id,
                                'style_no'=>$request->style_no,
                                'style_description'=>$request->style_description,
                                'task_id' =>$request->task_id,
                                'fg_id' =>$request->fg_id,
                                'bundle_no' =>$request->bundles[$x],
                                'roll_track_code' => $request->track_code[$x],
                                'item_code' => $request->item_code[$x],
                                'meter' => $request->meter[$x],
                                'bal_meter' => $request->bal_meter[$x],
                                'total_piece' => $request->totalpiece[$x],
                                'layers' => $request->layers[$x],
                                'sizes_array' => $request->sz_codes[$x],
                                                         
                                );

                    //   DB::enableQueryLog();
                  
                    //   $query = DB::getQueryLog();
                    //   $query = end($query);
                    //   dd($query);
                }  
                        
                         BundleBarcodeDetailModel::insert($data2);
                
                }

                 
                    $bb_code=$TrNo;
                    
                    
        $InsertSizeSerialData=DB::select('call AddBundleSerialBarcode("'.$bb_code.'")');
        //   return redirect()->route('BundleBarcode.index');
              
              
         
         $BundleList = BundleModel::join('usermaster', 'usermaster.userId', '=', 'bundle_barcode_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bundle_barcode_master.vendorId')
        ->where('bundle_barcode_master.bb_code', $bb_code)
         ->get(['bundle_barcode_master.*','usermaster.username','ledger_master.Ac_name']);
     
         foreach ($BundleList as $rowfetch){ 
             
                      
                      $fg_id=$rowfetch->fg_id;
                 }
                // DB::enableQueryLog(); 
            // $BundleBarcodeSerialDetailList = BundleBarcodeSerialDetailModel::join('fg_master','fg_master.fg_id', '=', 'bundle_barcode_serial_details.fg_id')
            // ->join('item_master','item_master.item_code', '=', 'bundle_barcode_serial_details.item_code')
            // ->where('bundle_barcode_serial_details.bb_code','=', $bb_code)
            // ->orderBy('bundle_barcode_serial_details.size_serial_no', 'ASC')
            // ->orderBy('bundle_barcode_serial_details.bundle_id', 'ASC')
            // ->get(['bundle_barcode_serial_details.*','fg_master.fg_name','item_master.item_name']);
            
           $BundleBarcodeSerialDetailList = BundleBarcodeSerialDetailModel::join('fg_master', 'fg_master.fg_id', '=', 'bundle_barcode_serial_details.fg_id')
                ->join('item_master', 'item_master.item_code', '=', 'bundle_barcode_serial_details.item_code')
                ->where('bundle_barcode_serial_details.bb_code', '=', $bb_code)
                ->orderByRaw('CONVERT(bundle_barcode_serial_details.size_serial_no, SIGNED) ASC')
                ->orderByRaw('CONVERT(bundle_barcode_serial_details.bundle_id, SIGNED) ASC')
                ->get([
                    'bundle_barcode_serial_details.*',
                    'fg_master.fg_name',
                    'item_master.item_name',
                    DB::raw('LEFT(bundle_barcode_serial_details.style_no, 15) as style_no'),
                ]);
       
           
            //   $query = DB::getQueryLog();
            //             $query = end($query);
            //             dd($query);  
            
           // $JobPartList= DB::table('job_part_master')->join('job_part_detail', 'job_part_detail.jpart_id','=','job_part_master.jpart_id')->where('fg_id','=',$fg_id)->whereIN('job_part_master.jpart_id',$request->jpart_id)->get();
          
    //   DB::enableQueryLog();
      $jpart_id = $request->input('jpart_id');
      if($jpart_id!=''){
      $JobPartList= DB::table('job_part_master')->whereIN('jpart_id',$request->jpart_id)->where('delflag','=',0)->get();
      }
      else
      {
          $JobPartList= DB::table('job_part_master')->whereIN('jpart_id',$request->jpart_id)->where('delflag','=',0)->get();
      }
    //   $query = DB::getQueryLog();
    //       $query = end($query);
    //       dd($query);
         return view('BundleBarcodePrint', compact('BundleList','BundleBarcodeSerialDetailList','JobPartList'));
    
             
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BundleModel  $bundleModel
     * @return \Illuminate\Http\Response
     */
    public function show(BundleModel $bundleModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BundleModel  $bundleModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
       
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
            
        
       // DB::enableQueryLog();
       
        $BundleBarcodeList = BundleModel::SELECT('vendor_purchase_order_master.vpo_code','bundle_barcode_master.*')->leftjoin('vendor_purchase_order_master','vendor_purchase_order_master.vpo_code', '=', 'bundle_barcode_master.vpo_code')->find($id);
     
        //dd(DB::getQueryLog());
        
        
          $TaskList = DB::select(DB::raw("SELECT task_id FROM `task_master` WHERE `vendorId`='".$BundleBarcodeList->vendorId."' and task_master.endflag=1 union (select '".$BundleBarcodeList->task_id."')")); 
  
        
        $BundleBarcodeDetail = BundleBarcodeDetailModel::join('item_master','item_master.item_code', '=', 'bundle_barcode_details.item_code')
        ->where('bundle_barcode_details.bb_code','=', $BundleBarcodeList->bb_code)->get(['bundle_barcode_details.*','item_master.item_name']);
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        $SizeList = DB::select("SELECT size_id, size_name from size_detail where size_id in (".$BundleBarcodeList->sizes_array.") order by size_id asc");
         
        $JobPartList= DB::table('job_part_master')->where('delflag','=', '0')->get();
        return view('BundleBarcodeMasterEdit',compact('BundleBarcodeList',  'SizeList', 'TaskList', 'Ledger','MainStyleList','SubStyleList', 'FGList','ItemList','BundleBarcodeDetail','JobPartList'));
    


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BundleModel  $bundleModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BundleModel $bundleModel)
    {
        $this->validate($request, [
             
            'bb_code'=>'required',
            'bb_date'=>'required',
          
            'task_id'=>'required',
            
            'total_piece'=>'required',
           
            'c_code'=>'required',
             ]);


                $serial=array();
                $size_serial_array='';
                $serial=$request->input('SizeSerialNo');
                for($y=0; $y<count($serial); $y++) 
                {
                    $size_serial_array=$size_serial_array.$request->SizeSerialNo[$y].',';
                }
                $size_serial_array=rtrim($size_serial_array,',');


                $data1=array(

                    'bb_code'=>$request->bb_code, 'bb_date'=>$request->bb_date,  
                    'vpo_code'=>$request->vpo_code, 'sales_order_no'=>$request->sales_order_no, 
                                'vendorId'=>$request->vendorId, 
                                'mainstyle_id'=>$request->mainstyle_id,
                                'substyle_id'=>$request->substyle_id,
                                'fg_id'=>$request->fg_id,
                                'style_no'=>$request->style_no,
                                'style_description'=>$request->style_description,
                                
                    'task_id'=>$request->task_id,
                    'total_piece'=>$request->total_piece, 'narration'=>$request->narration,  'c_code' => $request->c_code,
                    'userId'=>$request->userId, 'delflag'=>'0',   'size_serial_array'=>$size_serial_array,
                    
                    
                );
 
                $BundleBarcodeList = BundleModel::findOrFail($request->input('bb_code'));  
                $BundleBarcodeList->fill($data1)->save();
                    
                DB::table('bundle_barcode_details')->where('bb_code', $request->input('bb_code'))->delete();
                
                 $item_code = $request->input('item_code');
                if(count($item_code)>0)
                { 
                    
                
                for($x=0; $x<count($item_code); $x++) 
                {
                    # code...
                    
                                $data2[]=array(
                                'bb_code' =>$request->bb_code,
                                'bb_date' => $request->bb_date,
                                'vpo_code'=>$request->vpo_code, 
                                'sales_order_no'=>$request->sales_order_no, 
                                'vendorId'=>$request->vendorId, 
                                'mainstyle_id'=>$request->mainstyle_id,
                                'substyle_id'=>$request->substyle_id,
                                'fg_id'=>$request->fg_id,
                                'style_no'=>$request->style_no,
                                'style_description'=>$request->style_description,
                                'task_id' =>$request->task_id,
                                'fg_id' =>$request->fg_id,
                                'bundle_no' =>$request->bundles[$x],
                                'roll_track_code' => $request->track_code[$x],
                                'item_code' => $request->item_code[$x],
                                'meter' => $request->meter[$x],
                                'bal_meter' => $request->bal_meter[$x],
                                'total_piece' => $request->totalpiece[$x],
                                'layers' => $request->layers[$x],
                                'sizes_array' => $request->sz_codes[$x],
                                );

                    //   DB::enableQueryLog();
                    //   $query = DB::getQueryLog();
                    //   $query = end($query);
                    //   dd($query);
                }  
                         
                  BundleBarcodeDetailModel::insert($data2);
                }

              $bno=Session::get('size_counter'); 
              if($bno!='')
              {
                 DB::select("update job_card_master set size_counter='".$bno."' where  po_code='".$request->job_code."'");  
              }
                
                $InsertSizeSerialData=DB::select('call AddBundleSerialBarcode("'.$request->bb_code.'")');
        //   return redirect()->route('BundleBarcode.index');
              
              $bb_code=$request->bb_code;
         
         $BundleList = BundleModel::join('usermaster', 'usermaster.userId', '=', 'bundle_barcode_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bundle_barcode_master.vendorId')
        ->where('bundle_barcode_master.bb_code', $request->bb_code)
         ->get(['bundle_barcode_master.*','usermaster.username','ledger_master.Ac_name']);
         $bb_code=0;
         foreach ($BundleList as $rowfetch){ 
             
                      $bb_code=$rowfetch->bb_code;
                      $fg_id=$rowfetch->fg_id;
                 }
             //   DB::enableQueryLog(); 
            //   $BundleBarcodeSerialDetailList = BundleBarcodeSerialDetailModel::join('fg_master','fg_master.fg_id', '=', 'bundle_barcode_serial_details.fg_id')
            // ->join('item_master','item_master.item_code', '=', 'bundle_barcode_serial_details.item_code')
            // ->where('bundle_barcode_serial_details.bb_code','=', $bb_code)->orderByRaw('CONVERT(bundle_barcode_serial_details.size_serial_no,SIGNED) ASC')->orderByRaw('CONVERT(bundle_barcode_serial_details.bundle_id,SIGNED) ASC')->get(['bundle_barcode_serial_details.*','fg_master.fg_name','item_master.item_name']);
           $BundleBarcodeSerialDetailList = BundleBarcodeSerialDetailModel::join('fg_master', 'fg_master.fg_id', '=', 'bundle_barcode_serial_details.fg_id')
                ->join('item_master', 'item_master.item_code', '=', 'bundle_barcode_serial_details.item_code')
                ->where('bundle_barcode_serial_details.bb_code', '=', $bb_code)
                ->orderByRaw('CONVERT(bundle_barcode_serial_details.size_serial_no, SIGNED) ASC')
                ->orderByRaw('CONVERT(bundle_barcode_serial_details.bundle_id, SIGNED) ASC')
                ->get([
                    'bundle_barcode_serial_details.*',
                    'fg_master.fg_name',
                    'item_master.item_name',
                    DB::raw('LEFT(bundle_barcode_serial_details.style_no, 15) as style_no'),
                ]);
            //   $query = DB::getQueryLog();
            //             $query = end($query);
            //             dd($query);  
            
           // $JobPartList= DB::table('job_part_master')->join('job_part_detail', 'job_part_detail.jpart_id','=','job_part_master.jpart_id')->where('fg_id','=',$fg_id)->whereIN('job_part_master.jpart_id',$request->jpart_id)->get();
          
      //DB::enableQueryLog();
      $jpart_id = $request->input('jpart_id');
      if($jpart_id!=''){
      $JobPartList= DB::table('job_part_master')->whereIN('jpart_id',$request->jpart_id)->where('delflag','=',0)->get();
      }
      else
      {
          $JobPartList= DB::table('job_part_master')->where('delflag','=',0)->get();
      }
    //   $query = DB::getQueryLog();
    //       $query = end($query);
    //       dd($query);
         return view('BundleBarcodePrint', compact('BundleList','BundleBarcodeSerialDetailList','JobPartList'));
             
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BundleModel  $bundleModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     
       DB::table('bundle_barcode_master')->where('bb_code', $id)->delete();
       DB::table('bundle_barcode_details')->where('bb_code', $id)->delete();
       DB::table('bundle_barcode_serial_details')->where('bb_code', $id)->delete();
       Session::flash('delete', 'Deleted record successfully'); 
     
     
    }



public function GetJobPartList(Request $request)
{
    if (!$request->fg_id) {
        $html = '<option value="">--Job Part--</option>';
        } else {
        $html = '';
        
      
        //DB::enableQueryLog(); 
                 $JobPartList = JobPartModel::get();
                // $query = DB::getQueryLog();
                //         $query = end($query);
                //         dd($query);
        foreach ($JobPartList as $row) {
                $html .= '<option value="'.$row->srNo.'">'.$row->jpart_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
}



    public function getDetails(Request $request)
    { 
   // $job_code= $request->input('job_code');
    $task_id= $request->input('task_id');
    $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id',  '1')->get();
     // DB::enableQueryLog();      
    $BundleList = DB::select("SELECT cutting_details.sr_no,ifnull(sum(qty),0) as totalpiece, 
    (select  balance_meter from cutting_balance_details where track_code=cutting_details.track_code and cutting_master.table_task_code='". $task_id."' 
    and cutting_balance_details.cu_code=cutting_master.cu_code group by cutting_balance_details.cu_code) as balance_meter, 
    item_code, meter, track_code,layers, GROUP_CONCAT(size_id) as size_id , GROUP_CONCAT(qty) as qty, GROUP_CONCAT(ratio) as ratio_qty   from cutting_details 
    inner join cutting_master on cutting_master.cu_code=cutting_details.cu_code
    where cutting_master.table_task_code='". $task_id."' 
    GROUP BY cutting_details.track_code,cutting_master.table_task_code,cutting_master.cu_code,item_code,meter, cutting_details.layers 
    order by cutting_details.sr_no
    ");
        // dd(DB::getQueryLog());
    foreach ($BundleList as $row1) 
    {
        $size_ids=$row1->size_id;
        $ratio_qtys=$row1->ratio_qty;
    }
    
    $cnt = TaskMasterModel::select('size_counter')->where('task_id','=', $task_id)->first(); 
    $bno=$cnt->size_counter+1;

 
  $html = '';
 if(count($BundleList)>0)
    {
    $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
            <thead>
            <tr>
                <th>Roll No</th>
                <th>Color</th>
                <th>Meter</th>
                <th>Bal Meter</th>
                <th>Total Pieces</th>
                <th>Layer</th>';
                //   DB::enableQueryLog(); 
             
                // $query = DB::getQueryLog();
                //         $query = end($query);
                //         dd($query);
                
                
                $size_ids1='';
                $ratio_qty=[];
                
                
              $nj=0;  
                
                $rt=0;
              
                $TaskDetailList = TaskDetailModel::where('task_details.task_id','=', $task_id)->get();     
               
                 foreach($TaskDetailList as $td)
               { 
                       
                      $SizeList = DB::select("SELECT size_id,size_name from size_detail where size_id =".$td->size_id); 
                     
                           for($i=0;$i<($td->ratio);$i++)
                        {
                            
                            $html.='<th>'.$SizeList[0]->size_name.'</br> <input id="SizeSerialNo" name="SizeSerialNo[]" value="" style="width:70px;" required/></th>';
                            $size_ids1=$size_ids1.$SizeList[0]->size_id.',';
                            
                        } 
                        $nj=$nj+1;
                     }
                $size_ids1=rtrim($size_ids1,',');
               
                $html.='<th>TrackCode</th>
            </tr>
            </thead>
            <tbody>';
        $no=$bno;
         $new=$bno;
        foreach ($BundleList as $row) {
            $html .='<tr>';
            
        $html .='
        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
        
        <td> <select name="item_code[]"  id="item_code" style="width:100px;" required>
        <option value="">--Item--</option>';

        foreach($ItemList as  $row1)
        {
            $html.='<option value="'.$row1->item_code.'"';

            $row1->item_code == $row->item_code ? $html.='selected="selected"' : ''; 
            $html.='>'.$row1->item_name.'</option>';
        }
        
        $html.='</select></td>';
  
        $html.='<td><input type="text" name="meter[]" id="width1" value="'.$row->meter.'" style="width:80px;" required/></td>
        <td><input type="text" name="bal_meter[]" id="bal_meter" value="'.round($row->balance_meter,2).'" style="width:80px;" required/></td>
        <td><input type="text" name="totalpiece[]" id="totalpiece" value="'.$row->totalpiece.'" style="width:80px;" required/></td>
        <td><input type="number" name="layers[]" id="layers" class="PIECE" value="'.$row->layers.'" style="width:80px;" required/> ';
        $bundles='';
         
                 
       foreach($TaskDetailList as $td)
        {
            
                    $SizeList = DB::select("SELECT size_id,size_name from size_detail where size_id =".$td->size_id); 
               
                    for($m=0;$m<$td->ratio;$m++)
                    { 
                      
                        $html.='<td class="bdl"><input type="text" class="bundlenos"  name="bundleno[]"  onchange="RearrangeBarcodes(this);"  value="'.$new.'" id="bundleno'.$bno.'" style="width:80px;"    /></td>';
                        $bundles=$bundles.$new.',';
                         session()->put('size_counter',$new );
                        $new=$new+count($SizeList);
                           
                    }   
          
        }
        ++$bno;
        $bundles=rtrim($bundles,',');
        $html.='
        
        <td class="track">
        <input type="text" name="track_code[]"  value="'.$row->track_code.'" id="track_code" style="width:80px;"  /></td>
        <td><input type="button" class="btn btn-warning pull-left" name="add[]"   value="+"  ><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
        <input type="hidden" name="bundles[]"  value="'.$bundles.'" id="bundles" style="width:80px;" /> 
        <input type="hidden" name="sz_codes[]"  value="'.$size_ids1.'" id="sz_codes" style="width:80px;" />';

            $html .='</tr>  ';
            $no=$no+1;
            
            $new=$no;
            }
    } // if loop, if data exist in cutting details table
           if($html==''){     $html="No Data Found in The Cutting";}
            
           return response()->json(['html' => $html]);
            
    }




    public function getRowDetails(Request $request)
    { 
   
    $task_id= $request->input('task_id');
    $track_code= $request->input('track_code');
    
     $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id',  '1')->get();
   
    // DB::enableQueryLog(); 

    $BundleList = DB::select("SELECT  ifnull(sum(qty),0) as totalpiece, 
    
    (select  balance_meter from cutting_balance_details where track_code='".$track_code."' and cutting_master.table_task_code='".$task_id."' 
    and cutting_balance_details.sr_no=(select max(cutting_balance_details.sr_no) from cutting_balance_details where track_code='".$track_code."' 
    and cutting_master.table_task_code='".$task_id."')) as balance_meter,
     item_code, meter, track_code,layers, GROUP_CONCAT(size_id) as size_id , GROUP_CONCAT(qty) as qty, GROUP_CONCAT(ratio) as ratio_qty from cutting_details 
     inner join cutting_master on cutting_master.cu_code=cutting_details.cu_code
    where cutting_master.table_task_code='".$task_id."' and cutting_details.track_code='".$track_code."' GROUP BY  
    cutting_details.track_code,cutting_master.table_task_code,item_code,meter, cutting_details.layers  ");
       
    //   $query = DB::getQueryLog();
    //   $query = end($query);
    //   dd($query);


    foreach ($BundleList as $row1) 
    {
        $size_ids=$row1->size_id;
         $ratio_qtys=$row1->ratio_qty;
    }

    
    $bno=Session::get('size_counter');
   
              

     $html = '';
    
    $no=$bno+1;
    
    $new=$bno+1;
        foreach ($BundleList as $row) {
            $html .='<tr>';
            
        $html .='
        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
        
        <td> <select name="item_code[]"  id="item_code" style="width:100px;" required>
        <option value="">--Item--</option>';

        foreach($ItemList as  $row1)
        {
            $html.='<option value="'.$row1->item_code.'"';

            $row1->item_code == $row->item_code ? $html.='selected="selected"' : ''; 
            $html.='>'.$row1->item_name.'</option>';
        }
        
        $html.='</select></td>';
  
        $html.='<td><input type="text" name="meter[]" id="width1" value="'.$row->meter.'" style="width:80px;" required/></td>
        <td><input type="text" name="bal_meter[]" id="bal_meter" value="'.$row->balance_meter.'" style="width:80px;" required/></td>
        <td><input type="text" name="totalpiece[]" id="totalpiece" value="'.$row->totalpiece.'" style="width:80px;" required/></td>
        <td><input type="number" name="layers[]" id="layers" class="PIECE" value="'.round(($row->layers)/2).'"  style="width:80px;" required/></td>     
        ';
        
        $TaskDetailList = TaskDetailModel::where('task_details.task_id','=', $task_id)->get();    
        $bundles='';
                $size_ids1='';
                 $ratio_qty=[];
               foreach($TaskDetailList as $td)
        {
            
             
                    $SizeList = DB::select("SELECT size_id,size_name from size_detail where size_id =".$td->size_id); 
                 
                    
                         for($i=0;$i<$td->ratio;$i++)
                        { 
                   
                   
                   
                    $html.='<td class="bdl"><input type="text" class="bundlenos" name="bundleno[]"  value="'.$new.'" id="bundleno'.$bno.'" style="width:80px;"  /></td>';
                    $bundles=$bundles.$new.',';
                    $size_ids1=$size_ids1.$SizeList[0]->size_id.',';
                    $new=$new+count($SizeList);
                    
                        }
             
                
                ++$bno;
                } 
                $bundles=rtrim($bundles,',');
                $size_ids1=rtrim($size_ids1,',');
                
       
                $html.='
                <td class="track"><input type="text" name="track_code[]"  value="'.$row->track_code.'" id="track_code" style="width:80px;"  /></td>
                <td>
                <input type="hidden" name="bundles[]"  value="'.$bundles.'" id="bundles" style="width:80px;"  />
                <input type="hidden" name="sz_codes[]"  value="'.$size_ids1.'" id="sz_codes" style="width:80px;" />
                <input type="button" class="btn btn-warning pull-left" name="add[]"   value="+"  ><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>';
                $html .='</tr>';
            $no=$no+1;
           
            $new=$no;
            }
           
          
            return response()->json(['html' => $html]);
            
    }



    public function BundlePrinting(Request $request)
    {
         $bb_code=$request->bb_code;
           
         $InsertSizeSerialData=DB::select('call AddBundleSerialBarcode("'.$request->bb_code.'")');
        //   return redirect()->route('BundleBarcode.index');
           
         $BundleList = BundleModel::join('usermaster', 'usermaster.userId', '=', 'bundle_barcode_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bundle_barcode_master.vendorId')
        ->where('bundle_barcode_master.bb_code', $request->bb_code)
         ->get(['bundle_barcode_master.*','usermaster.username','ledger_master.Ac_name']);
         $bb_code=0;
         foreach ($BundleList as $rowfetch){ 
             
                      $bb_code=$rowfetch->bb_code;
                      $fg_id=$rowfetch->fg_id;
                 }
           // DB::enableQueryLog(); 
        $BundleBarcodeSerialDetailList = DB::table('bundle_barcode_serial_details')->join('item_master', 'item_master.item_code', '=', 'bundle_barcode_serial_details.item_code')
                    ->join('bundle_barcode_details', 'bundle_barcode_details.bb_code', '=', 'bundle_barcode_serial_details.bb_code')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'bundle_barcode_details.sales_order_no')
                    ->join('buyer_purchase_order_detail', 'buyer_purchase_order_detail.tr_code', '=', 'bundle_barcode_details.sales_order_no')
                    ->join('style_no_master', 'style_no_master.style_no_id', '=', 'buyer_purchase_order_detail.style_no_id')
                    ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
                    ->where('bundle_barcode_serial_details.bb_code', '=', $bb_code)
                    ->orderByRaw('CONVERT(bundle_barcode_serial_details.size_serial_no, SIGNED) ASC')
                    ->orderByRaw('CONVERT(bundle_barcode_serial_details.bundle_id, SIGNED) ASC')
                    ->get([
                        'bundle_barcode_serial_details.*',
                        'fg_master.fg_name',
                        'item_master.item_name','fg_master.fg_name','style_no_master.style_no' 
                    ]);
 //   $query = DB::getQueryLog();
            //             $query = end($query);
            //             dd($query);  
            
           // $JobPartList= DB::table('job_part_master')->join('job_part_detail', 'job_part_detail.jpart_id','=','job_part_master.jpart_id')->where('fg_id','=',$fg_id)->whereIN('job_part_master.jpart_id',$request->jpart_id)->get();
          
      //DB::enableQueryLog();
      $jpart_id = $request->input('jpart_id');
      if($jpart_id!=''){
      $JobPartList= DB::table('job_part_master')->whereIN('jpart_id',$request->jpart_id)->where('delflag','=',0)->get();
      }
      else
      {
          $JobPartList= DB::table('job_part_master')->where('delflag','=',0)->get();
      }
    //   $query = DB::getQueryLog();
    //       $query = end($query);
    //       dd($query);
         return view('BundleBarcodePrint', compact('BundleList','BundleBarcodeSerialDetailList','JobPartList'));
        
        
        
        
        
        
        
        
        
        
        
        
         
    //      $BundleList = BundleModel::join('usermaster', 'usermaster.userId', '=', 'bundle_barcode_master.userId')
    //      ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bundle_barcode_master.Ac_code')
    //      ->join('cp_master', 'cp_master.cp_id', '=', 'bundle_barcode_master.cp_id')
    //     ->where('bundle_barcode_master.bb_code', $request->bb_code)
    //      ->get(['bundle_barcode_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
    //      $bb_code=0;
    //      foreach ($BundleList as $rowfetch){ 
             
    //                   $bb_code=$rowfetch->bb_code;
    //                   $fg_id=$rowfetch->fg_id;
    //              }
    //         $BundleBarcodeDetailList = BundleBarcodeDetailModel::join('fg_master','fg_master.fg_id', '=', 'bundle_barcode_details.fg_id')
    //         ->join('color_master','color_master.color_id', '=', 'bundle_barcode_details.color_id')
    //         ->where('bundle_barcode_details.bb_code','=', $bb_code)->orderBy('bundle_barcode_details.sr_no')->get(['bundle_barcode_details.*','fg_master.fg_name','color_master.color_name']);
            
            
    //       // print_r($request->jpart_id);
    //       //  $jpart_id = is_array($request->jpart_id) ? $request->jpart_id : [$request->jpart_id];
    //         if(isset($request->srNo))
    //      {$jpart_id=  implode (",",$request->srNo);
    //                 DB::enableQueryLog();
    //           $JobPartList= DB::table('job_part_master')->join('job_part_detail', 'job_part_detail.jpart_id','=','job_part_master.jpart_id')->where('fg_id','=',$fg_id)->whereIN('job_part_detail.srNo',(array)$jpart_id)->get();
    //           $query = DB::getQueryLog();
    //       $query = end($query);
    //       dd($query);
    //      }
    //      else
    //      {
    //           $JobPartList= DB::table('job_part_master')->join('job_part_detail', 'job_part_detail.jpart_id','=','job_part_master.jpart_id')->where('fg_id','=',$fg_id)->get();
    //      }
            
            
    // //   DB::enableQueryLog();
       
    // //   $query = DB::getQueryLog();
    // //       $query = end($query);
    // //       dd($query);
    //      return view('BundleBarcodePrint', compact('BundleList','BundleBarcodeDetailList','JobPartList'));
   }


public function getSessionValue(Request $request)
{
     
  
  if($request->flag==2)
  { 
    $size_counter=Session::get('size_counter');
     $data= array('size_counter' =>$size_counter );
     return json_encode($data);
  } 
  elseif($request->flag==1)
  {
        $cnt = TaskMasterModel::select('size_counter')->where('task_id','=', $request->task_id)->first(); 
        $bno=$cnt->size_counter;
        $data= array('size_counter' =>$bno );
        return json_encode($data);
  }
    
  

    
    
    
    
}



}
