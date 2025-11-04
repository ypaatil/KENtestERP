<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\SeasonModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\SizeModel;
use Illuminate\Support\Facades\DB;
use App\Models\MaterialTransferFromModel;
use App\Models\MaterialTransferFromDetailModel;
use App\Models\SparePurchaseOrderModel;
use App\Models\LocationModel;
use Session;

class MaterialTransferFromController extends Controller
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
        ->where('form_id', '307')
        ->first();
        
        $MaterialTransferFromList = MaterialTransferFromModel::join('usermaster', 'usermaster.userId', '=', 'materialTransferFromMaster.userId')
            ->leftJoin('location_master as L1', 'L1.loc_id', '=', 'materialTransferFromMaster.from_loc_id')
            ->leftJoin('location_master as L2', 'L2.loc_id', '=', 'materialTransferFromMaster.to_loc_id')
            ->get(['materialTransferFromMaster.*', 'usermaster.username', 'L1.location as from_location', 'L2.location as to_location']);
        
        $MaterialTransferFromList = $MaterialTransferFromList->sortByDesc(function ($item) {
            return (int) substr($item->materialTransferFromCode, 14); // Extract numeric part
        });



        return view('materialTransferFromList', compact('chekform', 'MaterialTransferFromList'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get(); 
        $POList = SparePurchaseOrderModel::where('spare_purchase_order.class_id','=', '148')->get(); 
        return view('materialTransferFrom',compact('LocationList', 'itemlist', 'POList'));

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
              ->where('type','=','Material_Transfer_From')
               ->where('firm_id','=',1)
              ->first();
              
        $materialTransferFromCode=$codefetch->code.'-'.$codefetch->tr_no; 
        
        $data1=array(
                    'materialTransferFromCode'=>$materialTransferFromCode,
                    'materialTransferFromDate'=>$request->materialTransferFromDate, 
                    'from_loc_id'=>$request->from_loc_id, 
                    'to_loc_id'=>$request->to_loc_id, 
                    'driver_name'=>$request->driver_name, 
                    'vehical_no'=>$request->vehical_no, 
                    'totalqty'=>$request->totalqty, 
                    'remark'=>$request->remark, 
                    'delflag'=>0,
                    'userId'=>$request->userId, 
                    'created_at'=>date("Y-m-d H:i:s"),  
                );
             
        MaterialTransferFromModel::insert($data1);
            
        $spare_item_codes= $request->spare_item_codes;
                     
        for($x=0; $x<count($spare_item_codes); $x++) 
        {
           if($request->spare_item_codes[$x]!=0)
           {
        
                $data2=array(
                         'materialTransferFromCode' =>$materialTransferFromCode,
                         'materialTransferFromDate' => $request->materialTransferFromDate,
                         'spare_item_code' => $request->spare_item_codes[$x],
                         'materiralInwardCode' => $request->materiralInwardCode[$x],
                         'item_qty' => $request->item_qtys[$x],
                         'stock_qty' => $request->stock_qty[$x],
                         'from_loc_id'=>$request->from_loc_id,
                         'to_loc_id'=>$request->to_loc_id, 
                     );
                    
                    MaterialTransferFromDetailModel::insert($data2);
            }
        }  
        
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='Material_Transfer_From'");
        
        return redirect()->route('MaterialTransferFrom.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialTransferFromModel  $materialTransferFromModel
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialTransferFromModel $materialTransferFromModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialTransferFromModel  $materialTransferFromModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $machineList= DB::table('machine_master')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get(); 
        $POList = SparePurchaseOrderModel::where('spare_purchase_order.class_id','=', '148')->get();
        $statusList= DB::table('spare_return_material_status')->where('delflag','=', '0')->get();

        $materialTransferFromCode=base64_decode($id);
        $MaterialTransferFromMasterList = MaterialTransferFromModel::find($materialTransferFromCode); 

        $MateriralInwardData = DB::SELECT("SELECT materiralInwardCode, item_qty FROM materialInwardDetail GROUP BY materiralInwardCode");
 
        $MateriralInwardData = DB::SELECT("SELECT materiralInwardCode, item_qty  FROM materialInwardDetail  GROUP BY materiralInwardCode, item_qty UNION SELECT materialReturnCode AS materiralInwardCode, item_qty FROM materialReturnDetails GROUP BY materialReturnCode, item_qty");
            
        $materialTransferFromDetailslist = DB::table('materialTransferFromDetails')
            ->select('materialTransferFromDetails.*','spare_item_master.item_description', 'spare_item_master.spare_item_code')
            ->join('spare_item_master', 'spare_item_master.spare_item_code', '=', 'materialTransferFromDetails.spare_item_code')
            ->where('materialTransferFromCode','=',$materialTransferFromCode)
            ->get();

 
        return view('materialTransferFromEdit',compact('MaterialTransferFromMasterList','machineList','LocationList','itemlist','materialTransferFromDetailslist', 'POList', 'statusList', 'MateriralInwardData'));    
 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialTransferFromModel  $materialTransferFromModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $materialTransferFromCode)
    { 
            $materialTransferFromCode= $request->materialTransferFromCode;
            
            $data1=array(
                'materialTransferFromCode'=>$materialTransferFromCode,
                'materialTransferFromDate'=>$request->materialTransferFromDate, 
                'from_loc_id'=>$request->from_loc_id,
                'to_loc_id'=>$request->to_loc_id, 
                'driver_name'=>$request->driver_name, 
                'vehical_no'=>$request->vehical_no, 
                'totalqty'=>$request->totalqty, 
                'remark'=>$request->remark, 
                'delflag'=>0,
                'userId'=>$request->userId, 
                'updated_at'=>date("Y-m-d H:i:s"),  
            );
            
            $Return = MaterialTransferFromModel::findOrFail($materialTransferFromCode);  
            $Return->fill($data1)->save();
            
            
            DB::table('materialTransferFromDetails')->where('materialTransferFromCode',$materialTransferFromCode)->delete(); 
            
           $spare_item_codes= $request->spare_item_codes;
                     
            for($x=0; $x<count($spare_item_codes); $x++) 
            {
               if($request->spare_item_codes[$x]!=0)
               {
            
                    $data2=array(
                         'materialTransferFromCode' =>$materialTransferFromCode,
                         'materialTransferFromDate' => $request->materialTransferFromDate, 
                         'spare_item_code' => $request->spare_item_codes[$x],
                         'materiralInwardCode' => $request->materiralInwardCode[$x],
                         'item_qty' => $request->item_qtys[$x],
                         'stock_qty' => $request->stock_qty[$x],
                         'from_loc_id'=>$request->from_loc_id,
                         'to_loc_id'=>$request->to_loc_id, 
                         );
                        
                        MaterialTransferFromDetailModel::insert($data2);
                }
            }  
            return redirect()->route('MaterialTransferFrom.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialTransferFromModel  $materialTransferFromModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($materialTransferFromCode)
    {  
        $materialTransferFromCode=base64_decode($materialTransferFromCode);
        
        MaterialTransferFromModel::where('materialTransferFromCode',$materialTransferFromCode)->delete();
        MaterialTransferFromDetailModel::where('materialTransferFromCode',$materialTransferFromCode)->delete();
     
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function GetMachineDetails(Request $request)
    {
        $machine_id = $request->machine_id;
        $machineData = DB::table('machine_master')->where('MachineID', '=', $machine_id)->first();
        return $machineData;
    }
    
    public function GetItemDescriptionForMachine(Request $request)
    {
        $itemlist=DB::table('spare_item_master')->where('spare_item_code','=',$request->spare_item_code)->first();
        
        return response()->json(['spare_item_code'=> $itemlist->spare_item_code ,'item_description' => $itemlist->item_description]); 
    }
    
    public function GetPOListFromSpareItemCode(Request $request)
    { 
        
        $Data = DB::SELECT("SELECT pur_code FROM purchaseorder_detail WHERE spare_item_code=".$request->spare_item_code." GROUP BY pur_code");

        $html = '<option value="">--Select--</option>';
        foreach($Data as $row)
        {
            $html .='<option value="'.$row->pur_code.'">'.$row->pur_code.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 

    public function GetMaterialInwardOutwardStock(Request $request)
    {
        // Fetch the total inward quantity
        $inwardQty = DB::table('materialInwardDetail')
            ->where('spare_item_code', $request->spare_item_code)
            ->where('materiralInwardCode', $request->materiralInwardCode)
            ->sum('item_qty');
    
        // Fetch the total outward quantity
        $outwardQty = DB::table('materialoutwarddetails')
            ->where('spare_item_code', $request->spare_item_code)
            ->where('materiralInwardCode', $request->materiralInwardCode)
            ->sum('item_qty');
    
        // Calculate stock
        $stock = $inwardQty - $outwardQty;
    
        return response()->json(['stock' => $stock ?? 0]);
    }
    
    
    public function GetSpareMaterialTransferFromStock(Request $request)
    {
        $spare_item_code = $request->spare_item_code;
        $materiralInwardCode = $request->materiralInwardCode;
        $loc_id = $request->loc_id;
        $to_loc_id = $request->to_loc_id;
       // DB::enableQueryLog();
 
           $stockData = DB::SELECT("SELECT
                COALESCE(SUM(CASE WHEN source = 'inward' THEN item_qty ELSE 0 END), 0) AS inward_qty,
                COALESCE(SUM(CASE WHEN source = 'outward' THEN item_qty ELSE 0 END), 0) AS outward_qty,
                COALESCE(SUM(CASE WHEN source = 'outward_transfer' THEN item_qty ELSE 0 END), 0) AS outward_transfer,
                COALESCE(SUM(CASE WHEN source = 'transfer_inward' THEN item_qty ELSE 0 END), 0) AS transfer_inward,
                COALESCE(SUM(CASE WHEN source = 'return_transfer' THEN item_qty ELSE 0 END), 0) AS return_transfer
            FROM (
                SELECT 'inward' AS source, item_qty 
                FROM materialInwardDetail  
                WHERE location_id = ? AND materiralInwardCode = ?  AND spare_item_code = ? 
                  
                UNION ALL
                
                SELECT 'outward' AS source, item_qty 
                FROM materialoutwarddetails 
                WHERE loc_id = ? AND materiralInwardCode = ? AND spare_item_code = ?  
                
                UNION ALL
                
                SELECT 'outward_transfer' AS source, item_qty 
                FROM materialTransferFromDetails 
                WHERE from_loc_id = ? AND materiralInwardCode = ? AND spare_item_code = ? 
                
                UNION ALL
                
                SELECT 'transfer_inward' AS source, item_qty 
                FROM materialTransferFromInwardDetails 
                WHERE to_loc_id = ? AND spare_item_code = ? 
                
                UNION ALL
                
                SELECT 'return_transfer' AS source, item_qty 
                FROM materialReturnDetails 
                WHERE loc_id = ? AND materialReturnCode= ? AND spare_item_code = ? 
                
            ) AS stock_union", [$loc_id, $materiralInwardCode, $spare_item_code, $loc_id, $materiralInwardCode, $spare_item_code, $loc_id, $materiralInwardCode, $spare_item_code, $loc_id, $spare_item_code, $loc_id, $materiralInwardCode, $spare_item_code]);
    
       // dd(DB::getQueryLog());
        $stock_qty = ($stockData[0]->transfer_inward ?? 0) + ($stockData[0]->return_transfer ?? 0)  + ($stockData[0]->inward_qty ?? 0) - ($stockData[0]->outward_qty ?? 0) - ($stockData[0]->outward_transfer ?? 0);
    
        return response()->json(['stock_qty' => $stock_qty]);
    }

    public function MaterialTransferPrint($id)
    { 
        $materialTransferFromCode=base64_decode($id);
        $FirmDetail = DB::table("firm_master")->where('delflag', '=', 0)->first(); 
        //DB::enableQueryLog();
        $MaterialTransferList = MaterialTransferFromModel::join('location_master as lm1','lm1.loc_id', '=', 'materialTransferFromMaster.from_loc_id')
         ->leftjoin('location_master as lm2','lm2.loc_id', '=', 'materialTransferFromMaster.to_loc_id')
         ->leftjoin('usermaster', 'usermaster.userId', '=', 'materialTransferFromMaster.userId')  
         ->where('materialTransferFromMaster.delflag','=', '0')
         ->where('materialTransferFromMaster.materialTransferFromCode','=', $materialTransferFromCode)
         ->get(['materialTransferFromMaster.materialTransferFromCode','materialTransferFromMaster.materialTransferFromDate','materialTransferFromMaster.remark','materialTransferFromMaster.driver_name','materialTransferFromMaster.vehical_no','usermaster.username','lm1.location as from_loc','lm2.location as to_loc']);
        //dd(DB::getQueryLog());
        return view('MaterialTransferPrint', compact('MaterialTransferList', 'FirmDetail'));
    }
    
    
    
}
