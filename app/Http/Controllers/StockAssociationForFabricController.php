<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BOMMasterModel;
use App\Models\LedgerModel;
use App\Models\BOMSewingTrimsDetailModel;
use App\Models\SeasonModel;
use App\Models\SizeDetailModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ClassificationModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\CurrencyModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\QualityModel;
use App\Models\BOMFabricDetailModel;
use Illuminate\Support\Facades\DB;
use App\Models\BOMPackingTrimsDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\BOMTrimFabricDetailModel;
use App\Models\FabricSummaryGRNMasterModel;
use App\Models\StockAssociationForFabricModel;
use App\Models\CategoryModel;
use Session;
use DataTables;


date_default_timezone_set("Asia/Kolkata");

setlocale(LC_MONETARY, 'en_IN'); 

class StockAssociationForFabricController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '90')
        ->first();
       
      
       if ($request->ajax()) 
       {
            // DB::enableQueryLog();  
                $FabricGRNList = FabricSummaryGRNMasterModel::select('fabric_summary_grn_master.*','usermaster.username',
                'fabric_summary_grn_detail.item_code','item_master.item_name','item_master.item_description',
                DB::raw('sum(fabric_summary_grn_detail.item_qty) as item_qty'))
                 ->join('usermaster', 'usermaster.userId', '=', 'fabric_summary_grn_master.userId')
                 ->leftjoin('fabric_summary_grn_detail', 'fabric_summary_grn_detail.fsg_code', '=', 'fabric_summary_grn_master.fsg_code')
                 ->leftjoin('item_master', 'item_master.item_code', '=', 'fabric_summary_grn_detail.item_code')
                 ->where('fabric_summary_grn_master.delflag','=', '0')
                 ->where('fabric_summary_grn_detail.item_code',$request->item_code)
                 ->groupby('fabric_summary_grn_master.po_code')
                 ->get();
          // dd(DB::getQueryLog());
            if($request->bom_code != "")
            {
                $bom_code = " bom_code =".$request->bom_code;
            }
            else
            {
                $bom_code = "";  
            }
        
            return Datatables::of($FabricGRNList)
            ->addIndexColumn()
            ->addColumn('avaliable_Stock', function ($row) use ($chekform)
            {
                $trimed_bom_code = substr($row->bom_code, 0, 3);
                $trimed_po_code = substr($row->po_code, 0, 2);
                
                // $allocatedStockData = DB::select("
                //         SELECT 
                //             COALESCE(
                //                 SUM(CASE WHEN tr_type = 1 THEN qty ELSE 0 END), 0
                //             ) - 
                //             CASE 
                //                 WHEN COALESCE(SUM(CASE WHEN tr_type = 1 THEN qty ELSE 0 END), 0) = 0 
                //                 THEN 
                //                     COALESCE(
                //                         (SELECT 
                //                             SUM(qty) 
                //                          FROM 
                //                             stock_association_for_fabric 
                //                          WHERE 
                //                             po_code = ? 
                //                             AND item_code = ? 
                //                             AND tr_type = 1), 0
                //                     ) - 
                //                     COALESCE(
                //                         (SELECT 
                //                             SUM(qty) 
                //                          FROM 
                //                             stock_association_for_fabric 
                //                          WHERE 
                //                             po_code = ? 
                //                             AND item_code = ? 
                //                             AND tr_type = 2), 0
                //                     )
                //                 ELSE 
                //                     COALESCE(
                //                         (SELECT  
                //                             SUM(fabric_outward_details.meter) 
                //                          FROM 
                //                             fabric_outward_details
                //                          INNER JOIN 
                //                             fabric_checking_details 
                //                             ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                //                          INNER JOIN 
                //                             vendor_purchase_order_master 
                //                             ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                //                          WHERE 
                //                             fabric_checking_details.po_code = ? 
                //                             AND fabric_outward_details.item_code = ?), 0
                //                     )
                //             END AS allocated_qty
                //         FROM 
                //             stock_association_for_fabric 
                //         WHERE 
                //             po_code = ? 
                //             AND item_code = ?
                //     ", [
                //         $row->po_code, $row->item_code,  
                //         $row->po_code, $row->item_code,  
                //         $row->po_code, $row->item_code, 
                //         $row->po_code, $row->item_code   
                //     ]);

                $data=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code 
                    FROM stock_association_for_fabric as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
                    WHERE sta.po_code='".$row->po_code."' AND sta.item_code='".$row->item_code."'  
                    GROUP BY sta.item_code,sta.bom_code order by stockAssociationForFabricId asc");
                   
                $total_avaliable_stock = 0;
                if($trimed_bom_code === 'SIN')
                {
                    foreach ($data as $row) 
                    {
                        
                        $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                        
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                         
                        $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                        $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                    
                       
                      //  $avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
                       
                         $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter),0) as outward_qty FROM fabric_outward_details
                                    LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                                    LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                                    WHERE  fabric_outward_details.item_code='".$row->item_code."' and fabric_checking_details.po_code='".$row->po_code."' 
                                    AND (fabric_outward_details.sample_indent_code='".$row->bom_code."' OR vendor_purchase_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."') GROUP BY fabric_outward_details.item_code"); 
                                    
                        //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                        $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                       
                    
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                        $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                 
                        $remainStock = $allocated_qty - $eachAvaliableQty;
                       
                        $avilable_stock = $remainStock - $fabricOutwardStock;
                        
                        if($row->sales_order_no == '' || $row->sales_order_no == NULL)
                        {
                            $blankData = DB::SELECT("SELECT sum(qty) as item_qty FROM stock_association_for_fabric WHERE po_code = '".$row->po_code."' AND item_code = '".$row->item_code."' AND sales_order_no = ''");
                             
                            $remainStock = isset($blankData[0]->item_qty) ? $blankData[0]->item_qty : 0;
                            $blankQty = isset($blankData[0]->item_qty) ? $blankData[0]->item_qty : 0;
                            $totalAssoc = 0;
                        }
                        else
                        {
                            $totalAssoc = $totalAssoc;
                            $remainStock = $remainStock;
                            $blankQty = 0;
                        } 
                        
                        $total_avaliable_stock += $remainStock - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty);
                        $eachAvaliableQty = 0;  
                        $avilable_stock = 0;
                    }
                     
                }
                else
                {
                    foreach ($data as $row) 
                    {
                        
                        $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                        
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                        
                        $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                        $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                    
                        //$avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
                       
                        $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter), 0) as outward_qty FROM fabric_outward_details 
                                            LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code  
                                            LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                                            WHERE  fabric_outward_details.item_code='".$row->item_code."' and fabric_checking_details.po_code='".$row->po_code."'  
                                            AND vendor_purchase_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' GROUP BY fabric_outward_details.item_code"); 
                       
                        //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                        $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                       
                    
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."' AND tr_type = 2  AND tr_code IS NULL"); 
                        $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                 
                    
                       
                       $bomData = DB::SELECT("SELECT sales_order_no FROM bom_master WHERE bom_code ='".$row->bom_code."'");
                       
                       $main_sales_order_no = isset($bomData[0]->sales_order_no) ? $bomData[0]->sales_order_no : 0;
                       
                       if($row->sales_order_no == '' || $row->sales_order_no == $main_sales_order_no)
                       { 
                            $sales_order_no = $main_sales_order_no;
                       }
                       else
                       { 
                           $sales_order_no = $row->sales_order_no;
                       }
                       
                        if($sales_order_no != '0' && $trimed_po_code == 'OS')
                        {
                            $remainStock = abs($eachAvaliableQty);
                            $totalAssoc = 0;
                        }
                        else
                        {
                            $remainStock = abs($allocated_qty - $eachAvaliableQty);
                        }
                         
                         
                  
                     $eachAvaliableQty = 0;  
                     $avilable_stock = 0;
                     $total_avaliable_stock += (($remainStock) - $fabricOutwardStock);
                    }
                      
                }
                
                $available_qty  = $total_avaliable_stock;
                
                return money_format('%!.2n', round(($available_qty), 2));
            })
            ->addColumn('action', function ($row) use ($chekform,$bom_code)
            {
                $btn4 = '<a class="btn btn-danger btn-icon btn-sm" '.$bom_code.' tr_code ="'.$row->fsg_code.'" tr_date ="'.$row->fsg_date.'" item_code ="'.$row->item_code.'" po_code="'.$row->po_code.'" onclick="allocatedStock(this);" data-toggle="modal" data-target="#largeModal" >Allocate</a>'; 
                return $btn4;
            })
            ->rawColumns(['avaliable_Stock','action'])
            ->make(true);
        }
        $Categorylist = CategoryModel::where('delflag','=', '0')->where('cat_id','=', 1)->get();
        $BomData = DB::select('SELECT bom_code AS bom_code, sales_order_no AS sales_order_no FROM bom_master WHERE delflag = 0
            UNION
            SELECT sample_indent_code AS bom_code, sample_indent_code AS sales_order_no FROM sample_indent_master');
        $sales_order_no = '';
        $item_code = $request->item_code;
        $class_id = '';
        
        return view('Stock_Association_For_Fabric', compact('chekform','BomData','sales_order_no','item_code','class_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     
        
        return view('BOMMaster');

         
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //DB::enableQueryLog();
        $master_bom_Data = DB::table('bom_master')->where('bom_code','=', $request->master_bom_code)->first();
        //dd(DB::getQueryLog());
        $master_item_Data = DB::table('item_master')->where('item_code','=', $request->master_item_code)->first();
        $bom_codes=count($request->bom_code);
        
        $total_Qty = 0;
        
        for($x=0;$x<$bom_codes; $x++) 
        {
                $data3 = array(
                    "po_code"=> $request->po_code,  
                    "po_date"=> $request->po_date, 
                    "tr_code"=> $request->tr_code,  
                    "tr_date"=> $request->tr_date,
                    'bom_code'=>$request->bom_code[$x], 
                    'sales_order_no'=>$request->sales_order_no[$x], 
                    'cat_id'=>1,
                    'class_id'=>0,
                    "item_code"=> $request->item_code[$x],
                    'unit_id' => 0,
                    'qty' => $request->qty[$x],
                    "tr_type"=> 2,
                );
                 StockAssociationForFabricModel::insert($data3);
          $total_Qty = $total_Qty + $request->qty[$x];
        }   
        
        $data4 = array(
            "po_code"=> $request->po_code,  
            "po_date"=> $request->po_date, 
            "tr_code"=> $request->tr_code,  
            "tr_date"=> $request->tr_date,
            'bom_code'=>$request->master_bom_code, 
            'sales_order_no'=>  isset($request->master_sales_order_no) ? $request->master_sales_order_no : '', 
            'cat_id'=>$master_item_Data->cat_id,
            'class_id'=>$master_item_Data->class_id,
            "item_code"=> $request->master_item_code,
            'unit_id' => 0,
            'qty' => $total_Qty,
            "tr_type"=> 1,
        );
        StockAssociationForFabricModel::insert($data4);
        $fabricAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                    FROM stock_association_for_fabric as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  sta.item_code = ". $request->master_item_code." 
                    AND sta.po_code='".$request->po_code."' AND sta.bom_code='".$request->master_bom_code."' GROUP BY tr_type");
          // dd(DB::getQueryLog());                 
        foreach($fabricAssocData as $row)
        { 
            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
           
            $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
             
            $fabricOutwardData2 = DB::select("select sum(fabric_outward_details.meter) as qty  FROM fabric_outward_details INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  fabric_outward_details.vpo_code
                                        WHERE vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND fabric_outward_details.item_code = ".$row->item_code);
                                        
            $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0;
            $fabricOutwardStock = isset($fabricOutwardData2[0]->qty) ? $fabricOutwardData2[0]->qty : 0; 
         
            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
            
            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
            $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
            
            $tempData = DB::table("dump_fabric_stock_association")->where('po_code','=',$row->po_code)->where('item_code','=',$row->item_code)->where('sales_order_no','=',$row->sales_order_no)->get();
            if(count($tempData) == 0)
            {
                DB::table('dump_fabric_stock_association')->insert(
                    array(
                      'item_name' => $row->item_name,
                      'po_code' => $row->po_code,
                      'po_date' => $row->po_date,
                      'bom_code' => $row->bom_code,
                      'sales_order_no' => $row->sales_order_no,
                      'item_code' => $row->item_code,
                      'allocated_qty' => $allocated_qty,
                      'totalAssoc' => $totalAssoc,
                      'otherAvaliableStock' => $otherAvaliableStock,
                      'fabricOutwardStock' => $fabricOutwardStock,
                      'eachAvaliableQty' =>  $eachAvaliableQty,
                    )
                );
            }
            else
            {
                DB::table('dump_fabric_stock_association')
                    ->where('po_code', '=', $row->po_code)
                    ->where('bom_code', '=', $row->bom_code)
                    ->where('item_code', '=', $row->item_code)
                    ->where('sales_order_no', '=', $row->sales_order_no)
                    ->update(['allocated_qty' => $allocated_qty, 'totalAssoc' => $totalAssoc, 'otherAvaliableStock' => $otherAvaliableStock,'fabricOutwardStock' => $fabricOutwardStock,'eachAvaliableQty' => $eachAvaliableQty]); 
            }
        }
        $sales_order_no = $request->master_bom_code;
        $item_code = $request->master_item_code;
        $class_id = $master_item_Data->class_id;
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '90')
        ->first();
       
        $BomData = DB::select('SELECT bom_code AS bom_code, sales_order_no AS sales_order_no FROM bom_master WHERE delflag = 0
            UNION
            SELECT sample_indent_code AS bom_code, sample_indent_code AS sales_order_no FROM sample_indent_master');

        return view('Stock_Association_For_Fabric', compact('chekform','BomData','sales_order_no','item_code','class_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $soc_code)
    {
     return redirect()->route('BOM.index')->with('message', 'Data Saved Succesfully');  
    }
   
    public function destroy($id)
    {
        
    }
    public function StoreFabricPopupData(Request $request)
    {
        //DB::enableQueryLog();
        $master_bom_Data = DB::table('bom_master')->where('bom_code','=', $request->master_bom_code)->first();
        //dd(DB::getQueryLog());
        $master_item_Data = DB::table('item_master')->where('item_code','=', $request->master_item_code)->first();
        $bom_codes=count($request->bom_code);
        
        $total_Qty = 0;
        
        for($x=0;$x<$bom_codes; $x++) 
        {
                $data3 = array(
                    "po_code"=> $request->po_code,  
                    "po_date"=> $request->po_date, 
                    "tr_code"=> $request->tr_code,  
                    "tr_date"=> $request->tr_date,
                    'bom_code'=>$request->bom_code[$x], 
                    'sales_order_no'=>$request->sales_order_no[$x], 
                    'cat_id'=>1,
                    'class_id'=>0,
                    "item_code"=> $request->item_code[$x],
                    'unit_id' => 0,
                    'qty' => $request->qty[$x],
                    "tr_type"=> 2,
                );
                 StockAssociationForFabricModel::insert($data3);
          $total_Qty = $total_Qty + $request->qty[$x];
        }   
        
        $data4 = array(
            "po_code"=> $request->po_code,  
            "po_date"=> $request->po_date, 
            "tr_code"=> $request->tr_code,  
            "tr_date"=> $request->tr_date,
            'bom_code'=>$request->master_bom_code, 
            'sales_order_no'=>  isset($request->master_sales_order_no) ? $request->master_sales_order_no : '', 
            'cat_id'=>$master_item_Data->cat_id,
            'class_id'=>$master_item_Data->class_id,
            "item_code"=> $request->master_item_code,
            'unit_id' => 0,
            'qty' => $total_Qty,
            "tr_type"=> 1,
        );
        StockAssociationForFabricModel::insert($data4);
        $fabricAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                    FROM stock_association_for_fabric as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  sta.item_code = ". $request->master_item_code." 
                    AND sta.po_code='".$request->po_code."' AND sta.bom_code='".$request->master_bom_code."' GROUP BY tr_type");
          // dd(DB::getQueryLog());                 
        foreach($fabricAssocData as $row)
        { 
            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
           
            $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
             
            $fabricOutwardData2 = DB::select("select sum(fabric_outward_details.meter) as qty  FROM fabric_outward_details INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  fabric_outward_details.vpo_code
                                        WHERE vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND fabric_outward_details.item_code = ".$row->item_code);
                                        
            $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0;
            $fabricOutwardStock = isset($fabricOutwardData2[0]->qty) ? $fabricOutwardData2[0]->qty : 0; 
         
            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
            
            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
            $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
            
            $tempData = DB::table("dump_fabric_stock_association")->where('po_code','=',$row->po_code)->where('item_code','=',$row->item_code)->where('sales_order_no','=',$row->sales_order_no)->get();
            if(count($tempData) == 0)
            {
                DB::table('dump_fabric_stock_association')->insert(
                    array(
                      'item_name' => $row->item_name,
                      'po_code' => $row->po_code,
                      'po_date' => $row->po_date,
                      'bom_code' => $row->bom_code,
                      'sales_order_no' => $row->sales_order_no,
                      'item_code' => $row->item_code,
                      'allocated_qty' => $allocated_qty,
                      'totalAssoc' => $totalAssoc,
                      'otherAvaliableStock' => $otherAvaliableStock,
                      'fabricOutwardStock' => $fabricOutwardStock,
                      'eachAvaliableQty' =>  $eachAvaliableQty,
                    )
                );
            }
            else
            {
                DB::table('dump_fabric_stock_association')
                    ->where('po_code', '=', $row->po_code)
                    ->where('bom_code', '=', $row->bom_code)
                    ->where('item_code', '=', $row->item_code)
                    ->where('sales_order_no', '=', $row->sales_order_no)
                    ->update(['allocated_qty' => $allocated_qty, 'totalAssoc' => $totalAssoc, 'otherAvaliableStock' => $otherAvaliableStock,'fabricOutwardStock' => $fabricOutwardStock,'eachAvaliableQty' => $eachAvaliableQty]); 
            }
        }
        $sales_order_no = $request->master_bom_code;
        $item_code = $request->master_item_code;
        $class_id = $master_item_Data->class_id;
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '90')
        ->first();
       
        $BomData = DB::select('SELECT bom_code AS bom_code, sales_order_no AS sales_order_no FROM bom_master WHERE delflag = 0
            UNION
            SELECT sample_indent_code AS bom_code, sample_indent_code AS sales_order_no FROM sample_indent_master');

        return 1;
    }
    
    public function GetAllocatedFabricStockData(Request $request)
    {
        $data=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code 
            FROM stock_association_for_fabric as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
            WHERE sta.po_code='".$request->po_code."' AND sta.item_code='".$request->item_code."'  
            GROUP BY sta.item_code,sta.bom_code order by stockAssociationForFabricId asc");
          
         // DB::enableQueryLog();
        //dd(DB::getQueryLog());
        $html='';
        $trimed_bom_code = substr($request->bom_code, 0, 3);
        $trimed_po_code = substr($request->po_code, 0, 2);
        $total_avaliable_stock = 0;
        if($trimed_bom_code === 'SIN')
        {
           
            foreach ($data as $row) 
            {
                
                $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$request->po_code."' AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$request->po_code."'  AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                 
                $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
               
              //  $avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association_for_fabric WHERE po_code='".$request->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
               
                 $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter),0) as outward_qty FROM fabric_outward_details
                            LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                            LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                            WHERE  fabric_outward_details.item_code='".$request->item_code."' and fabric_checking_details.po_code='".$request->po_code."' 
                            AND (fabric_outward_details.sample_indent_code='".$row->bom_code."' OR vendor_purchase_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."') GROUP BY fabric_outward_details.item_code"); 
                            
                //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
               
            
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$request->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
         
                $remainStock = $allocated_qty - $eachAvaliableQty;
               
                $avilable_stock = $remainStock - $fabricOutwardStock;
                
                if($request->bom_code == $row->bom_code)
                {
                    $disabledTxt = 'readonly';
                }
                else
                {
                   $disabledTxt = '';
                }
                
                if($row->sales_order_no == '' || $row->sales_order_no == NULL)
                {
                    $blankData = DB::SELECT("SELECT sum(qty) as item_qty FROM stock_association_for_fabric WHERE po_code = '".$request->po_code."' AND item_code = '".$request->item_code."' AND sales_order_no = ''");
                     
                    $remainStock = isset($blankData[0]->item_qty) ? $blankData[0]->item_qty : 0;
                    $blankQty = isset($blankData[0]->item_qty) ? $blankData[0]->item_qty : 0;
                    $totalAssoc = 0;
                }
                else
                {
                    $totalAssoc = $totalAssoc;
                    $remainStock = $remainStock;
                    $blankQty = 0;
                }
                
                // if($totalAssoc != 0 || $remainStock != 0 || $fabricOutwardStock != 0)
                // {
                        $html .='<tr> 
                             <td><input type="hidden" class="form-control" name="bom_code[]" value="'.$row->bom_code.'" style="width: 92px;" readonly/><input type="text" class="form-control" value="'.$request->po_code.'" style="width: 150px;" readonly/></td>
                             <td><input type="text" class="form-control"  name="sales_order_no[]" value="'.($row->sales_order_no ? $row->sales_order_no : $row->bom_code).'" style="width: 92px;" readonly/></td>
                             <td><input type="text" class="form-control" name="item_code[]" value="'.$row->item_code.'" style="width: 92px;" readonly /></td>
                             <td style="width: 250px;">'.$row->item_name.'</td>
                             <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", $totalAssoc)).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", $remainStock)).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($fabricOutwardStock ? $fabricOutwardStock : 0))).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", $remainStock - ($fabricOutwardStock ? $fabricOutwardStock : 0))).'</td> 
                             <td>
                                <input type="number" class="form-control" min="0" max="'.sprintf ("%.2f", ($remainStock - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty))).'" step="any" name="qty[]" value="" style="width: 100px;" onkeyup="checkQty(this);" '.$disabledTxt.' />
                                <input type="hidden" class="form-control" name="po_code" value="'.$request->po_code.'" />
                                <input type="hidden" class="form-control" name="po_date" value="'.$row->po_date.'" />
                                <input type="hidden" class="form-control" name="master_bom_code" value="'.$request->bom_code.'" />
                                <input type="hidden" class="form-control" name="master_item_code" value="'.$row->item_code.'" />
                             </td> 
                        </tr>';
               // }
                $total_avaliable_stock += $remainStock - ($fabricOutwardStock ? $fabricOutwardStock : 0);
                $eachAvaliableQty = 0;  
                $avilable_stock = 0;
            }
            
            $html .='<tr> 
                         <th colspan="7" class="text-right">Total</th>
                         <th class="text-right">'.money_format('%!.2n', sprintf ("%.2f", $total_avaliable_stock)).'</th>  
                    </tr>'; 
        }
        else
        {
            //  echo "hii";exit;
            foreach ($data as $row) 
            {
                
                $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$request->po_code."' AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$request->po_code."'  AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                
                $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
                //$avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association_for_fabric WHERE po_code='".$request->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
               
                $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter), 0) as outward_qty FROM fabric_outward_details 
                                    LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code  
                                    LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                                    WHERE  fabric_outward_details.item_code='".$request->item_code."' and fabric_checking_details.po_code='".$request->po_code."'  
                                    AND vendor_purchase_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' GROUP BY fabric_outward_details.item_code"); 
               
                //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
               
            
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$request->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no = '".($row->sales_order_no ? $row->sales_order_no : '0')."'  AND tr_type = 2  AND tr_code IS NULL"); 
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
         
            
               
               $bomData = DB::SELECT("SELECT sales_order_no FROM bom_master WHERE bom_code ='".$request->bom_code."'");
               
               $main_sales_order_no = isset($bomData[0]->sales_order_no) ? $bomData[0]->sales_order_no : 0;
               
               if($row->sales_order_no == '' || $row->sales_order_no == $main_sales_order_no)
               {
                    $disabledTxt = 'readonly';
                    $sales_order_no = $main_sales_order_no;
               }
               else
               {
                   $disabledTxt = '';
                   $sales_order_no = $row->sales_order_no;
               }
               
                // if($sales_order_no != '0' && $trimed_po_code == 'OS')
                // {
                //     $remainStock = abs($eachAvaliableQty);
                //     $totalAssoc = 0;
                // }
                // else
                // {
                //     $remainStock = abs($allocated_qty - $eachAvaliableQty);
                // }
                
                
                if($row->sales_order_no == '')
                {
                    $blankData = DB::SELECT("SELECT sum(qty) as item_qty FROM stock_association WHERE po_code = '".$request->po_code."' AND item_code = '".$request->item_code."' AND sales_order_no = ''");
                     
                    $remainStock = isset($blankData[0]->item_qty) ? $blankData[0]->item_qty : 0;
                    $blankQty = isset($blankData[0]->item_qty) ? $blankData[0]->item_qty : 0;
                    $totalAssoc = 0;
                }
                else
                {
                   $remainStock = abs($allocated_qty - $eachAvaliableQty);
                   $blankQty = 0;
                }
                
                 
                 $html .='<tr>';
            
                  $html .='<td><input type="hidden" class="form-control" name="bom_code[]" value="'.$row->bom_code.'" style="width: 92px;" readonly/><input type="text" class="form-control" value="'.$request->po_code.'" style="width: 150px;" readonly/></td>
                         <td><input type="hidden" class="form-control"  name="master_sales_order_no" value="'.$main_sales_order_no.'" style="width: 92px;" readonly/>
                         <input type="text" class="form-control"  name="sales_order_no[]" value="'.$row->sales_order_no.'" style="width: 92px;" readonly/></td>
                         <td><input type="text" class="form-control" name="item_code[]" value="'.$row->item_code.'" style="width: 92px;" readonly /></td>
                         <td style="width: 250px;">'.$row->item_name.'</td>
                         <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", $totalAssoc)).'</td> 
                         <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", $remainStock)).'</td> 
                         <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($fabricOutwardStock ? $fabricOutwardStock : $blankQty) )).'</td> 
                         <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($remainStock) - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty) )).'</td>
                         <td>
                            <input type="number" class="form-control" min="0" max="'.(sprintf("%.2f", (($remainStock) - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty)))).'" step="any" name="qty[]" value="" style="width: 100px;" onkeyup="checkQty(this);"  '.$disabledTxt.' />
                            <input type="hidden" class="form-control" name="po_code" value="'.$request->po_code.'" />
                            <input type="hidden" class="form-control" name="po_date" value="'.$row->po_date.'" />
                            <input type="hidden" class="form-control" name="master_bom_code" value="'.$request->bom_code.'" />
                            <input type="hidden" class="form-control" name="master_item_code" value="'.$row->item_code.'" />
                         </td>';
                $html .='</tr>';
          
             $eachAvaliableQty = 0;  
             $avilable_stock = 0;
             $total_avaliable_stock += (($remainStock) - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty));
            }
            
            
            $html .='<tr> 
                         <th colspan="7" class="text-right">Total</th>
                         <th class="text-right">'.money_format('%!.2n',sprintf ("%.2f", $total_avaliable_stock)).'</th>  
                    </tr>';  
        }
        return response()->json(['html' => $html]);
    }
    
    public function GetItemFabricDataFromDetail(Request $request)
    {
    
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        $bom_code = $request->bom_code;
        $sales_order_no = $request->sales_order_no;
        
        //DB::enableQueryLog();
        if($class_id == 1 || $class_id == 2)
        {
            if (strpos($bom_code, 'SIN-') === 0) 
            { 
                $Itemlist = DB::select("SELECT sample_indent_fabric.fabric_item_code as item_code,item_master.item_name FROM sample_indent_fabric 
                        INNER JOIN item_master ON item_master.item_code = sample_indent_fabric.fabric_item_code WHERE delflag=0 AND sample_indent_fabric.sample_indent_code='".$bom_code."' 
                        AND item_master.class_id='".$class_id."'");
            }
            else
            {   
                $Itemlist = DB::select("SELECT bom_fabric_details.item_code,item_master.item_name FROM bom_fabric_details 
                    INNER JOIN item_master ON item_master.item_code = bom_fabric_details.item_code WHERE delflag=0 AND bom_fabric_details.bom_code='".$bom_code."' 
                    AND item_master.class_id='".$class_id."' AND item_master.cat_id=".$cat_id." AND bom_fabric_details.sales_order_no='".$sales_order_no."'");
            }
        }
        else if($class_id == 7)
        { 
            if (strpos($bom_code, 'SIN-') === 0) 
            { 
                 $Itemlist = DB::select("SELECT sample_indent_fabric.fabric_item_code as item_code,item_master.item_name FROM sample_indent_fabric 
                        INNER JOIN item_master ON item_master.item_code = sample_indent_fabric.fabric_item_code WHERE delflag=0 AND sample_indent_fabric.sample_indent_code='".$bom_code."' 
                        AND item_master.class_id='".$class_id."'");
            
            }
            else
            {   
                $Itemlist = DB::select("SELECT bom_trim_fabric_details.item_code,item_master.item_name FROM bom_trim_fabric_details 
                    INNER JOIN item_master ON item_master.item_code = bom_trim_fabric_details.item_code WHERE delflag=0 AND bom_trim_fabric_details.bom_code='".$bom_code."' 
                    AND item_master.class_id='".$class_id."' AND item_master.cat_id=".$cat_id." AND bom_trim_fabric_details.sales_order_no='".$sales_order_no."'");
            }
        }
        else
        {
             $Itemlist = DB::select("SELECT item_master.item_code,item_master.item_name FROM item_master WHERE delflag=0  AND item_master.class_id='".$class_id."' AND item_master.cat_id=".$cat_id);
        }
        //dd(DB::getQueryLog());
        $html = '<option value="All">--All--</option>';
        
        foreach($Itemlist as $row)
        {
            $html .= '<option value="'.$row->item_code .'">'.$row->item_name .'-('.$row->item_code .')</option>';
        }
        return response()->json(['html' => $html]);
    
    } 
    
        
    
    public function DumpFabricStockAssocation()
    { 
        
        $trimsAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                            FROM stock_association_for_fabric as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code 
                            WHERE  1  GROUP BY sta.po_code, sta.sales_order_no,sta.item_code");
        DB::table('dump_fabric_stock_association')->delete();
        foreach($trimsAssocData as $row)
        { 
            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
           
            $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
            
             
            $fabricOutwardData1 = DB::select("select sum(fabric_outward_details.meter) as qty  FROM fabric_outward_details 
                                        INNER JOIN  fabric_checking_details ON  fabric_checking_details.track_code =  fabric_outward_details.track_code
                                        INNER JOIN  vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  fabric_outward_details.vpo_code
                                        WHERE fabric_checking_details.po_code='".$row->po_code."' AND vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND fabric_outward_details.item_code = ".$row->item_code);
                                        
            $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0; 
            $fabricOutwardStock = isset($fabricOutwardData1[0]->qty) ? $fabricOutwardData1[0]->qty : 0;
             
         
            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
            
            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
            $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
            
            DB::table('dump_fabric_stock_association')->insert(
            array(
              'item_name' => $row->item_name,
              'po_code' => $row->po_code,
              'po_date' => $row->po_date,
              'bom_code' => $row->bom_code,
              'sales_order_no' => $row->sales_order_no,
              'item_code' => $row->item_code,
              'allocated_qty' => $allocated_qty,
              'totalAssoc' => $totalAssoc,
              'otherAvaliableStock' => $otherAvaliableStock,
              'fabricOutwardStock' => $fabricOutwardStock,
              'eachAvaliableQty' =>  $eachAvaliableQty,
            )
          );
        }
        return 1;
    }  

          
    public function rptFabricAssocation(Request $request)
    { 
         
        ini_set('memory_limit', '10G');
        if ($request->ajax()) 
        { 
            
            $srno = 1;
            //DB::enableQueryLog();
            $trimsAssocData = DB::select("SELECT dump_fabric_stock_association.*,item_category.cat_name,
                   (SELECT ac_short_name FROM ledger_master WHERE ledger_master.ac_code = purchase_order.Ac_code LIMIT 1) as supplier_name,(SELECT trade_name FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to LIMIT 1) as trade_name,
                   (SELECT site_code FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to LIMIT 1) as site_code  FROM dump_fabric_stock_association
                   INNER JOIN item_master ON item_master.item_code = dump_fabric_stock_association.item_code 
                   INNER JOIN item_category ON item_category.cat_id = item_master.cat_id
                   LEFT JOIN purchase_order ON purchase_order.pur_code = dump_fabric_stock_association.po_code");
         
      //dd(DB::getQueryLog());
            return Datatables::of($trimsAssocData) 

            ->addColumn('srno', function ($row) use (&$srno) {
                return $srno++;
            })
            ->addColumn('avilable_stock',function ($row) 
            {
              if($row->totalAssoc <= 0 && $row->bom_code != "")
               { 
                    $remainStock = $row->allocated_qty - $row->eachAvaliableQty;
               }
               else
               {
                    
                    $remainStock = $row->allocated_qty - $row->otherAvaliableStock;
               }
               
               $avilable_stock = $remainStock - $row->fabricOutwardStock;
               return money_format('%!.2n', sprintf("%.2f", $avilable_stock));
            }) 
            ->addColumn('remainStock',function ($row) 
            {
                if($row->totalAssoc <= 0 && $row->bom_code != "")
               { 
                    $remainStock = $row->allocated_qty - $row->eachAvaliableQty;
               }
               else
               {
                    
                    $remainStock = $row->allocated_qty - $row->otherAvaliableStock;
               }
                  
               return money_format('%!.2n', sprintf("%.2f", $remainStock));
            }) 
            ->addColumn('totalAssoc',function ($row) 
            { 
               return money_format('%!.2n', sprintf("%.2f", $row->totalAssoc));
            }) 
            ->addColumn('fabricOutwardStock',function ($row) 
            { 
               return money_format('%!.2n', sprintf("%.2f", $row->fabricOutwardStock));
            }) 
            ->addColumn('supplier_name',function ($row) 
            { 
                if($row->supplier_name == '')
                {  
                    // DB::enableQueryLog();
                    $supplierData = DB::SELECT("SELECT ac_short_name FROM ledger_master INNER JOIN bom_master ON bom_master.Ac_code = ledger_master.ac_code WHERE bom_master.bom_code='".$row->bom_code."' LIMIT 1");
                    //  dd(DB::getQueryLog());
                    $supplier_name = isset($supplierData[0]->ac_short_name) ? $supplierData[0]->ac_short_name : '';
                }
                else
                {
                    $supplier_name = $row->supplier_name;
                }
                
               return $supplier_name;
            })   
            ->addColumn('bill_to',function ($row) 
            { 
                if($row->site_code != '')
                {
                    $bill_to = $row->trade_name.'('.$row->site_code.')';
                }
                else
                {
                    $bill_to = $row->trade_name;
                } 
                
                if($bill_to =='')
                {
                    
                     $supplierData = DB::SELECT("SELECT ac_short_name FROM ledger_master INNER JOIN bom_master ON bom_master.Ac_code = ledger_master.ac_code WHERE bom_master.bom_code='".$row->bom_code."' LIMIT 1");
                     $supplier_name = isset($supplierData[0]->ac_short_name) ? $supplierData[0]->ac_short_name : '';
                     $tradeData =DB::select("SELECT ledger_details.trade_name FROM ledger_details LEFT JOIN ledger_master ON ledger_master.ac_code = ledger_details.ac_code WHERE ac_short_name LIKE '%".$supplier_name."%' LIMIT 1");
                  
                     $tn = isset($tradeData[0]->trade_name) ? $tradeData[0]->trade_name : "";
                     $sc = isset($tradeData[0]->site_code) ? $tradeData[0]->site_code : "";
                     
                     if($sc != '')
                     {
                            $bill_to = $tn.'('.$sc.')';
                     }
                }
                
                
                return $bill_to;
            }) 
             ->rawColumns(['srno','avilable_stock','remainStock','totalAssoc','fabricOutwardStock','bill_to','supplier_name'])
             
             ->make(true);
    
            }    
          return view('rptFabricAssocation'); 
    } 
}
