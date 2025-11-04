<?php

namespace App\Http\Controllers;
use App\Jobs\SyncFabricDataJob;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\FabricTransactionModel;
use App\Models\FabricInwardModel;
use App\Models\FabricInwardDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\PurchaseOrderModel; 
use App\Models\ColorModel;
use App\Models\QualityModel;
use App\Models\PartModel;
use App\Models\ItemModel;
use App\Models\RackModel;
use App\Models\LocationModel;
use App\Models\OCRModel;
use Image;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Printing;
use Session;
use App\Models\POTypeModel;
use DataTables;
use Illuminate\Support\Facades\Artisan; 


class OCRController extends Controller
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
                ->where('form_id', '36')
                ->first(); 
         
         $OCRMasterList = DB::table('ocr_mater')->select('ocr_master_id',DB::raw('sum(transport_qty) as total_transport_qty'), DB::raw('sum(testing_qty) as total_testing_qty'),'sales_order_no')->where('delflag', '0')->groupBy('sales_order_no')->get(); 
  
         return view('OCRMasterList', compact('OCRMasterList','chekform'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $SalesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE og_id != 4");
        
        return view('OCRMaster',compact('SalesOrderList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
                
        for($x=0; $x<count($request->ocr_date); $x++) 
        {
            $data1=array(
        
                'sales_order_no'=>$request->sales_order_no, 
                'ocr_date'=>$request->ocr_date[$x],
                'transport_qty'=>$request->transport_qty[$x],
                'transport_image'=>"",
                'testing_qty'=>$request->testing_qty[$x], 
                'testing_image'=>"",
                'delflag' =>0, 
                'userId' => $request->userId, 
                'created_at'=>date('Y-m-d'),
                'updated_at'=>date('Y-m-d'), 
                );
            
                OCRModel::insert($data1);
            
            if ($request->hasFile('transport_image')) {
                foreach ($request->file('transport_image') as $image) 
                {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/TransportOCRImages/'), $imageName); 
                    DB::table('ocr_mater')->where('sales_order_no',$request->sales_order_no)->update(['transport_image'=>$imageName]);
                } 
            }
    
    
            if ($request->hasFile('testing_image')) 
            {
                foreach ($request->file('testing_image') as $image) 
                {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/TestingOCRImages/'), $imageName); 
                    DB::table('ocr_mater')->where('sales_order_no',$request->sales_order_no)->update(['testing_image'=>$imageName]);
                } 
            }    
                
        } 
        
         return redirect()->route('OCR.index')->with('message', ' Record Created Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricInwardModel $fabricInwardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
  
        $SalesOrderList = DB::table('buyer_purchse_order_master')->where('delflag', '0')->get(); 
        $OCRList = DB::table('ocr_mater')->select('*')->where('ocr_master_id','=',$id)->get();

        return view('OCRMasterEdit',compact('OCRList','SalesOrderList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
         DB::table('ocr_mater')->where('sales_order_no', $request->sales_order_no)->delete();
         for($x=0; $x<count($request->ocr_date); $x++) 
         {
                $data1=array( 
                    'sales_order_no'=>$request->sales_order_no, 
                    'ocr_date'=>$request->ocr_date[$x],
                    'transport_qty'=>$request->transport_qty[$x],
                    'transport_image'=> "",
                    'testing_qty'=>$request->testing_qty[$x], 
                    'testing_image'=> "",
                    'delflag' =>0, 
                    'userId' => $request->userId, 
                    'created_at'=>date('Y-m-d'),
                    'updated_at'=>date('Y-m-d'), 
                );
        
            OCRModel::insert($data1); 
            
             
            if ($request->hasFile('transport_image')) {
                foreach ($request->file('transport_image') as $image) 
                {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/TransportOCRImages/'), $imageName); 
                    DB::table('ocr_mater')->where('sales_order_no',$request->sales_order_no)->update(['transport_image'=>$imageName]);
                } 
            }
     
            if ($request->hasFile('testing_image')) 
            {
                foreach ($request->file('testing_image') as $image) 
                {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/TestingOCRImages/'), $imageName); 
                    DB::table('ocr_mater')->where('sales_order_no',$request->sales_order_no)->update(['testing_image'=>$imageName]);
                } 
            }
        }
        return redirect()->route('OCR.index')->with('message', 'Update Record Succesfully');
    }

 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        DB::table('ocr_mater')->where('sales_order_no', $id)->delete(); 
        Session::flash('delete', 'Deleted record successfully'); 
    }
     
}
