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
use App\Models\MaterialOutwardModel;
use App\Models\MaterialOutwardDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\LocationModel;
use Session;

class MaterialOutwardController extends Controller
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
        ->where('form_id', '300')
        ->first();
        
        $OutwardList = MaterialOutwardModel::join('usermaster', 'usermaster.userId', '=', 'materialoutwardmaster.userId')
                ->leftJoin('location_master', 'location_master.loc_id', '=', 'materialoutwardmaster.loc_id')
                ->orderBy('materialoutwardmaster.materialOutwardCode', 'DESC')
                ->get(['materialoutwardmaster.*', 'usermaster.username', 'location_master.location']);
                
        return view('materialOutwardList', compact('chekform', 'OutwardList'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $machineList= DB::table('machine_master')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get();  
        return view('materialOutward',compact('machineList', 'LocationList', 'itemlist'));

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
              ->where('type','=','Material_Outward')
               ->where('firm_id','=',1)
              ->first();
              
        $materialOutwardCode=$codefetch->code.'-'.$codefetch->tr_no; 
        
        $data1=array(
                    'materialOutwardCode'=>$materialOutwardCode,
                    'materialOutwardDate'=>$request->materialOutwardDate, 
                    'loc_id'=>$request->loc_id, 
                    'totalqty'=>$request->totalqty, 
                    'remark'=>$request->remark, 
                    'delflag'=>0,
                    'userId'=>$request->userId, 
                    'created_at'=>date("Y-m-d H:i:s"),  
                );
             
        MaterialOutwardModel::insert($data1);
            
        $spare_item_codes = $request->spare_item_codes;
                     
        for($x=0; $x<count($spare_item_codes); $x++) 
        {
           if($request->spare_item_codes[$x]!=0)
           {
        
                $data2=array(
                         'materialOutwardCode' =>$materialOutwardCode,
                         'materialOutwardDate' => $request->materialOutwardDate,
                         'machine_id' => $request->machine_id[$x],
                         'spare_item_code' => $request->spare_item_codes[$x],
                         'materiralInwardCode' => $request->materiralInwardCode[$x],
                         'item_qty' => $request->item_qtys[$x],
                         'stock' => $request->stock[$x],
                         'loc_id'=>$request->loc_id, 
                     );
                    
                    MaterialOutwardDetailModel::insert($data2);
            }
        }  
        
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='Material_Outward'");
        
        return redirect()->route('MaterialOutward.index');

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
       
        $machineList= DB::table('machine_master')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get(); 
      
        // $MaterialInwardList = DB::table('materialInwardMaster')->get();
        $MaterialInwardList = DB::SELECT("SELECT materiralInwardCode, item_qty  FROM materialInwardDetail  GROUP BY materiralInwardCode, item_qty UNION SELECT materialReturnCode AS materiralInwardCode, item_qty FROM materialReturnDetails GROUP BY materialReturnCode, item_qty");
            
        $materialOutwardCode=base64_decode($id);
        $MaterialOutwardMasterList = MaterialOutwardModel::find($materialOutwardCode); 
    
        $MaterialOutwarddetailslist = DB::table('materialoutwarddetails')
            ->select('materialoutwarddetails.*','spare_item_master.item_description', 'spare_item_master.spare_item_code')
            ->join('spare_item_master', 'spare_item_master.spare_item_code', '=', 'materialoutwarddetails.spare_item_code')
            ->where('materialOutwardCode','=',$materialOutwardCode)
            ->get();
       
        return view('materialOutwardEdit',compact('MaterialOutwardMasterList','machineList','LocationList','itemlist','MaterialOutwarddetailslist', 'MaterialInwardList'));    
 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialOutwardModel  $materialOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $materialOutwardCode)
    { 
            $materialOutwardCode= $request->materialOutwardCode;
            
            $data1=array(
                'materialOutwardCode'=>$materialOutwardCode,
                'materialOutwardDate'=>$request->materialOutwardDate, 
                'loc_id'=>$request->loc_id, 
                'totalqty'=>$request->totalqty, 
                'remark'=>$request->remark, 
                'delflag'=>0,
                'userId'=>$request->userId, 
                'updated_at'=>date("Y-m-d H:i:s"),  
            );
            
            $outward = MaterialOutwardModel::findOrFail($materialOutwardCode);  
            $outward->fill($data1)->save();
            
            
            DB::table('materialoutwarddetails')->where('materialOutwardCode',$materialOutwardCode)->delete(); 
            
           $spare_item_codes= $request->spare_item_codes;
                     
            for($x=0; $x<count($spare_item_codes); $x++) 
            {
               if($request->spare_item_codes[$x]!=0)
               {
            
                    $data2=array(
                         'materialOutwardCode' =>$materialOutwardCode,
                         'materialOutwardDate' => $request->materialOutwardDate,
                         'machine_id' => $request->machine_id[$x],
                         'spare_item_code' => $request->spare_item_codes[$x],
                         'materiralInwardCode' => $request->materiralInwardCode[$x],
                         'item_qty' => $request->item_qtys[$x],
                         'stock' => $request->stock[$x],
                         'loc_id'=>$request->loc_id, 
                         );
                        
                        MaterialOutwardDetailModel::insert($data2);
                }
            }  
            return redirect()->route('MaterialOutward.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialOutwardModel  $materialOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($materialOutwardCode)
    {  
        $materialOutwardCode=base64_decode($materialOutwardCode);
        
        MaterialOutwardModel::where('materialOutwardCode',$materialOutwardCode)->delete();
        MaterialOutwardDetailModel::where('materialOutwardCode',$materialOutwardCode)->delete();
     
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
    
    public function GetGRNListFromSpareItemCode(Request $request)
    { 
        
        $Data = DB::SELECT("SELECT materiralInwardCode, materialInwardDetail.location_id as loc_id, materialInwardDetail.spare_item_code 
                FROM materialInwardDetail 
                WHERE spare_item_code = ".$request->spare_item_code." 
                GROUP BY materiralInwardCode
            
                UNION
            
                SELECT materialReturnCode AS materiralInwardCode, materialReturnDetails.loc_id, materialReturnDetails.spare_item_code  
                FROM materialReturnDetails 
                WHERE spare_item_code = ".$request->spare_item_code."
                GROUP BY materialReturnCode");
 
        $html = '<option value="">--Select--</option>';
        foreach($Data as $row)
        {
            $spare_item_code = $row->spare_item_code;
            $materiralInwardCode = $row->materiralInwardCode;
            $loc_id = $row->loc_id; 
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
    
    
            $html .='<option value="'.$row->materiralInwardCode.'">'.$row->materiralInwardCode.'('.$stock_qty.')</option>';
        } 
        return response()->json(['html' => $html]);
    } 

    public function GetSpareStock(Request $request)
    {
        $spare_item_code = $request->spare_item_code;
        $materiralInwardCode = $request->materiralInwardCode;
        $loc_id = $request->loc_id;
        //DB::enableQueryLog();
 
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
                WHERE loc_id = ? AND spare_item_code = ? 
                
            ) AS stock_union", [$loc_id, $materiralInwardCode, $spare_item_code, $loc_id, $materiralInwardCode, $spare_item_code, $loc_id, $materiralInwardCode, $spare_item_code, $loc_id, $spare_item_code, $loc_id, $spare_item_code]);
    
        //dd(DB::getQueryLog());
        $stock_qty = ($stockData[0]->transfer_inward ?? 0) + ($stockData[0]->return_transfer ?? 0)  + ($stockData[0]->inward_qty ?? 0) - ($stockData[0]->outward_qty ?? 0) - ($stockData[0]->outward_transfer ?? 0);
    
        return response()->json(['stock_qty' => $stock_qty]);
    }

}
