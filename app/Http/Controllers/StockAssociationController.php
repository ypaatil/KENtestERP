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
use App\Models\SalesOrderCostingMasterModel;
use App\Models\StockAssociationModel;
use App\Models\CategoryModel;
use Session;
use DataTables;
setlocale(LC_MONETARY, 'en_IN');


class StockAssociationController extends Controller
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
            //DB::enableQueryLog();  
            
            if($request->item_code != "")
            {
                $item_data = " WHERE trimsInwardDetail.item_code =".$request->item_code;
            }
            else
            {
                $item_data = "";
            }
           
            $TrimPOGRNList = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,trimsInwardDetail.trimCode,trimsInwardDetail.trimDate, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
                
                (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
                
                trimsInwardMaster.po_code,item_master.dimension,item_master.item_name,
                item_master.color_name,item_master.item_description
                from trimsInwardDetail
                left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
                left join item_master on item_master.item_code=trimsInwardDetail.item_code ".$item_data." 
                group by trimsInwardMaster.po_code,trimsInwardDetail.item_code");
           
            if($request->bom_code != "")
            {
                $bom_code = " bom_code =".$request->bom_code;
            }
            else
            {
                $bom_code = "";
            }
            return Datatables::of($TrimPOGRNList)
            ->addIndexColumn()
            ->addColumn('avaliable_Stock', function ($rows) use ($request) 
            {
                    $data=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code 
                        FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
                        WHERE sta.po_code='".$rows->po_code."' AND sta.item_code='".$rows->item_code."'  
                        GROUP BY sta.item_code,sta.sales_order_no order by stockAssociationId asc");
                      
                     // DB::enableQueryLog();
                    $data1 = DB::SELECT("SELECT 
                                        trimsOutwardDetail.*, item_master.item_name, sum(trimsOutwardDetail.item_qty) as item_qty,
                                        vendor_work_order_master.vw_code,  
                                        vendor_purchase_order_master.vpo_code,
                                        COALESCE(vendor_work_order_master.sales_order_no, vendor_purchase_order_master.sales_order_no) AS sales_order_no 
                                    FROM trimsOutwardDetail 
                                    LEFT JOIN vendor_work_order_master 
                                        ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                    LEFT JOIN vendor_purchase_order_master 
                                        ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                    INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code 
                                    WHERE trimsOutwardDetail.item_code = '".$rows->item_code."' 
                                        AND trimsOutwardDetail.po_code = '".$rows->po_code."' 
                                        AND (
                                            COALESCE(vendor_work_order_master.sales_order_no, vendor_purchase_order_master.sales_order_no) NOT IN (
                                                SELECT sales_order_no 
                                                FROM stock_association 
                                                WHERE po_code = trimsOutwardDetail.po_code 
                                                AND item_code = trimsOutwardDetail.item_code
                                            )) GROUP BY vendor_purchase_order_master.sales_order_no,vendor_work_order_master.sales_order_no");
            
                    //dd(DB::getQueryLog());
                    $html='';
                    $trimed_bom_code = substr($request->bom_code, 0, 3);
                    $total_avaliable_stock = 0;
                    if($trimed_bom_code === 'SIN')
                    {
                                foreach ($data as $row) 
                                {
                                    
                                    $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$rows->po_code."' AND item_code='".$rows->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                                    
                                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$rows->po_code."'  AND item_code='".$rows->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                                     
                                    $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                                    $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                                
                                   
                                  //  $avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association WHERE po_code='".$rows->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
                                   
                                    $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail 
                                                        WHERE  trimsOutwardDetail.item_code='".$rows->item_code."' and trimsOutwardDetail.po_code='".$rows->po_code."' AND trimsOutwardDetail.sample_indent_code='".($row->sales_order_no ? $row->sales_order_no : 0)."' GROUP BY trimsOutwardDetail.item_code"); 
                                   
                                    //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                                    $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                                   
                                
                                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$rows->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                                    $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                             
                                    $remainStock = $allocated_qty - $eachAvaliableQty;
                                   
                                    $avilable_stock = $remainStock - $fabricOutwardStock;
                                    
                                    
                        $total_avaliable_stock += $remainStock - $fabricOutwardStock;
                        $eachAvaliableQty = 0;  
                        $avilable_stock = 0;
                    }
                    
                    
                    foreach ($data1 as $row) 
                    {
                        
                        $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$rows->po_code."' AND item_code='".$rows->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                        
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$rows->po_code."'  AND item_code='".$rows->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                         
                        $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                        $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                    
                       
                        //$avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association WHERE po_code='".$rows->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
                       
                        $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail 
                                            WHERE  trimsOutwardDetail.item_code='".$rows->item_code."' and trimsOutwardDetail.po_code='".$rows->po_code."' AND trimsOutwardDetail.sample_indent_code='".($row->sales_order_no ? $row->sales_order_no : 0)."' GROUP BY trimsOutwardDetail.item_code"); 
                       
                        //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                        $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                       
                    
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$rows->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                        $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                 
                            
                        $remainStock = $allocated_qty - $eachAvaliableQty;
                    
                       
                       $avilable_stock = $remainStock - $fabricOutwardStock;
                        
                        $total_avaliable_stock += $remainStock - $fabricOutwardStock;
                        $eachAvaliableQty = 0;  
                        $avilable_stock = 0;
                    } 
                }
                else
                {
                    foreach ($data as $row) 
                    {
                        
                        $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                        
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                        
                        $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                        $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                    
                        //$avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association WHERE po_code='".$row->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
                       
                        $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(trimsOutwardDetail.item_qty), 0) as outward_qty FROM trimsOutwardDetail
                                            LEFT JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code  
                                            LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code  
                                            WHERE  trimsOutwardDetail.item_code='".$row->item_code."' and trimsOutwardDetail.po_code='".$row->po_code."' 
                                            AND (vendor_work_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' 
                                            OR vendor_purchase_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."') GROUP BY trimsOutwardDetail.item_code"); 
                       
                        //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                        $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                       
                    
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                        $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                 
                            
                        $remainStock = $allocated_qty - $eachAvaliableQty;
                    
                       
                       $avilable_stock = $remainStock - $fabricOutwardStock;
                       $bomData = DB::SELECT("SELECT sales_order_no FROM bom_master WHERE bom_code ='".$row->bom_code."'");
                       
                       $main_sales_order_no = isset($bomData[0]->sales_order_no) ? $bomData[0]->sales_order_no : 0;
                       
                       
                        if($row->sales_order_no == '')
                        {
                            $blankData = DB::SELECT("SELECT sum(qty) as item_qty FROM stock_association WHERE po_code = '".$row->po_code."' AND item_code = '".$row->item_code."' AND sales_order_no = ''");
                             
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
                      
                        
                  
                     $eachAvaliableQty = 0;  
                     $avilable_stock = 0;
                     $total_avaliable_stock += ($remainStock) - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty);
                    }
                    
                    
                    foreach ($data1 as $row1) 
                    {
                        $total_avaliable_stock += 0 - $row1->item_qty;
                    } 
                }
                    
                return money_format('%!.2n', round(($total_avaliable_stock), 2));
            })
            ->addColumn('action', function ($row) use ($chekform,$bom_code)
            {
                $btn4 = '<a class="btn btn-danger btn-icon btn-sm" '.$bom_code.' tr_code ="'.$row->trimCode.'" tr_date ="'.$row->trimDate.'" item_code ="'.$row->item_code.'" po_code="'.$row->po_code.'" onclick="allocatedStock(this);" data-toggle="modal" data-target="#largeModal" >Allocate</a>'; 
                return $btn4;
            })
            ->rawColumns(['avaliable_Stock','action'])
            ->make(true);
        }
        $Categorylist = CategoryModel::where('delflag','=', '0')->whereNotIn('cat_id', array(1, 4))->get();
        //DB::enableQueryLog();
        $BomData = DB::select('
            SELECT bom_code AS bom_code, sales_order_no AS sales_order_no FROM bom_master WHERE delflag = 0
            UNION
            SELECT sample_indent_code AS bom_code, sample_indent_code AS sales_order_no FROM sample_indent_master');

        //dd(DB::getQueryLog());     
        $sales_order_no = '';
        $item_code = $request->item_code;
        $class_id = '';
        $cat_id =  '';
        
        return view('Stock_Association', compact('chekform','BomData','sales_order_no','item_code','class_id','Categorylist','cat_id')); 
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
        $master_item_Data = DB::table('item_master')->where('item_code','=', $request->master_item_code)->first();
        $bom_codes=count($request->bom_code);
        
        $sales_order_no = $request->master_bom_code;
        $item_code = $request->master_item_code;
        $class_id = $master_item_Data->class_id;
        $cat_id =  $master_item_Data->cat_id;
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '90')
        ->first();
       
        $Categorylist = CategoryModel::where('delflag','=', '0')->whereNotIn('cat_id', array(1, 4))->get();
        
        $BomData = DB::select('SELECT bom_code AS bom_code, sales_order_no AS sales_order_no FROM bom_master WHERE delflag = 0
            UNION
            SELECT sample_indent_code AS bom_code, sample_indent_code AS sales_order_no FROM sample_indent_master');

        return view('Stock_Association', compact('chekform','BomData','sales_order_no','item_code','class_id','cat_id','Categorylist'));
    }

    public function StorePopupData(Request $request)
    {
        //echo "hii";exit;
       //echo '<pre>'; print_R($_POST);exit;
        $master_bom_Data = DB::table('bom_master')->where('bom_code','=', $request->master_bom_code)->first();
        $master_item_Data = DB::table('item_master')->where('item_code','=', $request->master_item_code)->first();
        $item_codes=count($request->item_code); 
        $master_sales_order_no = $request->master_bom_code;
        $master_item_cat = isset($itemData[0]->cat_id) ? $itemData[0]->cat_id : 0;
        $master_item_class = isset($itemData[0]->class_id) ? $itemData[0]->class_id : 0;
            
        $total_Qty = 0;
        
        for($x=0;$x<$item_codes; $x++) 
        {
                $data3 = array(
                    "po_code"=> $request->po_code,  
                    "po_date"=> $request->po_date, 
                    "tr_code"=> $request->tr_code,  
                    "tr_date"=> $request->tr_date,
                    'bom_code'=>$request->bom_code[$x], 
                    'sales_order_no'=>$request->sales_order_no[$x], 
                    'cat_id'=>$master_item_Data->cat_id,
                    'class_id'=>$master_item_Data->class_id,
                    "item_code"=> $request->item_code[$x],
                    'unit_id' => 0,
                    'qty' => $request->qty[$x],
                    "tr_type"=> 2,
                );
                 StockAssociationModel::insert($data3);
          $total_Qty = $total_Qty + $request->qty[$x];
        }   
        
        if (strpos($request->master_bom_code, 'SIN-') === 0) 
        { 
            $itemData = DB::SELECT("SELECT cat_id,class_id,item_code FROM item_master WHERE item_code=".$request->master_item_code);
            $master_sales_order_no = $request->master_bom_code;
            $master_item_cat = isset($itemData[0]->cat_id) ? $itemData[0]->cat_id : 0;
            $master_item_class = isset($itemData[0]->class_id) ? $itemData[0]->class_id : 0;
        }
        else
        {
            $master_sales_order_no = $master_bom_Data->sales_order_no;
        }
                
        $data4 = array(
            "po_code"=> $request->po_code,  
            "po_date"=> $request->po_date, 
            "tr_code"=> $request->tr_code,  
            "tr_date"=> $request->tr_date,
            'bom_code'=>$request->master_bom_code, 
            'sales_order_no'=>$master_sales_order_no, 
            'cat_id'=>$master_item_cat,
            'class_id'=>$master_item_class,
            "item_code"=> $request->master_item_code,
            'unit_id' => 0,
            'qty' => $total_Qty,
            "tr_type"=> 1,
        );
      
        StockAssociationModel::insert($data4);
         
        $trimsAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                            FROM stock_association as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  sta.item_code = ". $request->master_item_code." 
                            AND sta.po_code='".$request->po_code."' AND sta.sales_order_no='".$master_sales_order_no."' GROUP BY tr_type");
                  
        foreach($trimsAssocData as $row)
        { 
            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
           
            $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
            
            $trimsOutwardData1 = DB::select("select sum(trimsOutwardDetail.item_qty) as qty  FROM trimsOutwardDetail INNER JOIN  vendor_work_order_master ON  vendor_work_order_master.vw_code =  trimsOutwardDetail.vw_code
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND vendor_work_order_master.sales_order_no = '".$row->sales_order_no."' AND trimsOutwardDetail.item_code = ".$row->item_code);
                                        
            $trimsOutwardData2 = DB::select("select sum(trimsOutwardDetail.item_qty) as qty  FROM trimsOutwardDetail INNER JOIN  vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  trimsOutwardDetail.vpo_code
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND trimsOutwardDetail.item_code = ".$row->item_code);
                                        
            $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0;
            $trimsOutwardStock1 = isset($trimsOutwardData1[0]->qty) ? $trimsOutwardData1[0]->qty : 0;
            $trimsOutwardStock2 = isset($trimsOutwardData2[0]->qty) ? $trimsOutwardData2[0]->qty : 0;
            $trimsOutwardStock = $trimsOutwardStock1 + $trimsOutwardStock2;
         
            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
            
            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
            $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
            
            $tempData = DB::table("dump_trims_stock_association")->where('po_code','=',$row->po_code)->where('item_code','=',$row->item_code)->where('sales_order_no','=',$row->sales_order_no)->get();
            if(count($tempData) == 0)
            {
                DB::table('dump_trims_stock_association')->insert(
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
                      'trimOutwardStock' => $trimsOutwardStock,
                      'eachAvaliableQty' =>  $eachAvaliableQty,
                    )
                );
            }
            else
            {
                DB::table('dump_trims_stock_association')
                    ->where('po_code', '=', $row->po_code)
                    ->where('bom_code', '=', $row->bom_code)
                    ->where('item_code', '=', $row->item_code)
                    ->where('sales_order_no', '=', $row->sales_order_no)
                    ->update(['allocated_qty' => $allocated_qty, 'totalAssoc' => $totalAssoc, 'otherAvaliableStock' => $otherAvaliableStock,'trimOutwardStock' => $trimsOutwardStock,'eachAvaliableQty' => $eachAvaliableQty]); 
            }
        } 

        $master_item_Data = DB::table('item_master')->where('item_code','=', $request->master_item_code)->first();
        $bom_codes=count($request->bom_code);
        
        $sales_order_no = $request->master_bom_code;
        $item_code = $request->master_item_code;
        $class_id = $master_item_Data->class_id;
        $cat_id =  $master_item_Data->cat_id;
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '90')
        ->first();
       
        $Categorylist = CategoryModel::where('delflag','=', '0')->whereNotIn('cat_id', array(1, 4))->get();
        
        $BomData = DB::select('SELECT bom_code AS bom_code, sales_order_no AS sales_order_no FROM bom_master WHERE delflag = 0
            UNION
            SELECT sample_indent_code AS bom_code, sample_indent_code AS sales_order_no FROM sample_indent_master');

        return 1;
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
    
    public function GetAllocatedStockData(Request $request)
    {
        $data=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code 
            FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
            WHERE sta.po_code='".$request->po_code."' AND sta.item_code='".$request->item_code."'  
            GROUP BY sta.item_code,sta.sales_order_no order by stockAssociationId asc");
          
         // DB::enableQueryLog();
        $data1 = DB::SELECT("SELECT 
                            trimsOutwardDetail.*, item_master.item_name, sum(trimsOutwardDetail.item_qty) as item_qty,
                            vendor_work_order_master.vw_code,  
                            vendor_purchase_order_master.vpo_code,
                            COALESCE(vendor_work_order_master.sales_order_no, vendor_purchase_order_master.sales_order_no) AS sales_order_no 
                        FROM trimsOutwardDetail 
                        LEFT JOIN vendor_work_order_master 
                            ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                        LEFT JOIN vendor_purchase_order_master 
                            ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                        INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code 
                        WHERE trimsOutwardDetail.item_code = '".$request->item_code."' 
                            AND trimsOutwardDetail.po_code = '".$request->po_code."' 
                            AND (
                                COALESCE(vendor_work_order_master.sales_order_no, vendor_purchase_order_master.sales_order_no) NOT IN (
                                    SELECT sales_order_no 
                                    FROM stock_association 
                                    WHERE po_code = trimsOutwardDetail.po_code 
                                    AND item_code = trimsOutwardDetail.item_code
                                )) GROUP BY vendor_purchase_order_master.sales_order_no,vendor_work_order_master.sales_order_no");

        //dd(DB::getQueryLog());
        $html='';
        $trimed_bom_code = substr($request->bom_code, 0, 3);
        $total_avaliable_stock = 0;
        if($trimed_bom_code === 'SIN')
        {
            foreach ($data as $row) 
            {
                
                $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$request->po_code."' AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$request->po_code."'  AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                 
                $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
               
              //  $avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association WHERE po_code='".$request->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
               
                $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail 
                                    WHERE  trimsOutwardDetail.item_code='".$request->item_code."' and trimsOutwardDetail.po_code='".$request->po_code."' AND trimsOutwardDetail.sample_indent_code='".($row->sales_order_no ? $row->sales_order_no : 0)."' GROUP BY trimsOutwardDetail.item_code"); 
               
                //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
               
            
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$request->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
         
                $remainStock = $allocated_qty - $eachAvaliableQty;
               
                $avilable_stock = $remainStock - $fabricOutwardStock;
                
                if($request->bom_code == $row->sales_order_no)
                {
                    $disabledTxt = 'readonly';
                }
                else
                {
                   $disabledTxt = '';
                }
               
                if($totalAssoc != 0 || $remainStock != 0 || $fabricOutwardStock != 0)
                {
                        $html .='<tr> 
                             <td><input type="hidden" class="form-control" name="bom_code[]" value="'.$row->bom_code.'" style="width: 92px;" readonly/><input type="text" class="form-control" value="'.$request->po_code.'" style="width: 150px;" readonly/></td>
                             <td><input type="text" class="form-control"  name="sales_order_no[]" value="'.$row->sales_order_no.'" style="width: 92px;" readonly/></td>
                             <td><input type="text" class="form-control" name="item_code[]" value="'.$row->item_code.'" style="width: 92px;" readonly /></td>
                             <td style="width: 250px;">'.$row->item_name.'</td>
                             <td class="text-right">'.money_format('%!.2n', sprintf("%.2f",($totalAssoc))).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($remainStock))).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($fabricOutwardStock))).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($remainStock - $fabricOutwardStock))).'</td> 
                             <td>
                                <input type="number" class="form-control" min="0" max="'.sprintf ("%.2f", ($remainStock - $fabricOutwardStock)).'" step="any" name="qty[]" value=""  onkeyup="checkQty(this);"  style="width: 100px;" '.$disabledTxt.' />
                                <input type="hidden" class="form-control" name="po_code" value="'.$request->po_code.'" />
                                <input type="hidden" class="form-control" name="po_date" value="'.$row->po_date.'" />
                                <input type="hidden" class="form-control" name="master_bom_code" value="'.$request->bom_code.'" />
                                <input type="hidden" class="form-control" name="master_item_code" value="'.$row->item_code.'" />
                             </td> 
                        </tr>';
                }
                $total_avaliable_stock += $remainStock - $fabricOutwardStock;
                $eachAvaliableQty = 0;  
                $avilable_stock = 0;
            }
            
            
            foreach ($data1 as $row) 
            {
                
                $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$request->po_code."' AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$request->po_code."'  AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                 
                $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
               
                //$avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association WHERE po_code='".$request->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
               
                $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail 
                                    WHERE  trimsOutwardDetail.item_code='".$request->item_code."' and trimsOutwardDetail.po_code='".$request->po_code."' AND trimsOutwardDetail.sample_indent_code='".($row->sales_order_no ? $row->sales_order_no : 0)."' GROUP BY trimsOutwardDetail.item_code"); 
               
                //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
               
            
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$request->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
         
                    
                $remainStock = $allocated_qty - $eachAvaliableQty;
            
               
               $avilable_stock = $remainStock - $fabricOutwardStock;
                
               if($request->bom_code == $row->sales_order_no)
               {
                    $disabledTxt = 'readonly';
               }
               else
               {
                   $disabledTxt = '';
               }
               
                if($totalAssoc != 0 || $remainStock != 0 || $fabricOutwardStock != 0)
                {
                        $html .='<tr> 
                             <td><input type="hidden" class="form-control" name="bom_code[]" value="'.$row->bom_code.'" style="width: 92px;" readonly/><input type="text" class="form-control" value="'.$request->po_code.'" style="width: 150px;" readonly/></td>
                             <td><input type="text" class="form-control"  name="sales_order_no[]" value="'.$row->sales_order_no.'" style="width: 92px;" readonly/></td>
                             <td><input type="text" class="form-control" name="item_code[]" value="'.$row->item_code.'" style="width: 92px;" readonly /></td>
                             <td style="width: 250px;">'.$row->item_name.'</td>
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($totalAssoc))).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($remainStock))).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($fabricOutwardStock))).'</td> 
                             <td class="text-right">'.money_format('%!.2n', sprintf ("%.2f", ($remainStock - $fabricOutwardStock))).'</td> 
                             <td>
                                <input type="number" class="form-control" min="0" max="'.sprintf("%.2f", ($remainStock - $fabricOutwardStock)).'" step="any" name="qty[]"  onkeyup="checkQty(this);" value="" style="width: 100px;" '.$disabledTxt.' />
                                <input type="hidden" class="form-control" name="po_code" value="'.$request->po_code.'" />
                                <input type="hidden" class="form-control" name="po_date" value="'.$row->po_date.'" />
                                <input type="hidden" class="form-control" name="master_bom_code" value="'.$request->bom_code.'" />
                                <input type="hidden" class="form-control" name="master_item_code" value="'.$row->item_code.'" />
                             </td> 
                        </tr>';
                }
                $total_avaliable_stock += $remainStock - $fabricOutwardStock;
                $eachAvaliableQty = 0;  
                $avilable_stock = 0;
            }
            
            $html .='<tr> 
                         <th colspan="7" class="text-right">Total</th>
                         <th class="text-right">'.money_format('%!.2n', sprintf("%.2f", $total_avaliable_stock)).'</th>  
                    </tr>'; 
        }
        else
        {
            foreach ($data as $row) 
            {
                
                $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$request->po_code."' AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$request->po_code."'  AND item_code='".$request->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type=1");
                
                $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
                //$avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association WHERE po_code='".$request->po_code."' AND item_code='".$row->item_code."' AND sales_order_no!='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 1"); 
               
                $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(trimsOutwardDetail.item_qty), 0) as outward_qty FROM trimsOutwardDetail
                                    LEFT JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code  
                                    LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code  
                                    WHERE  trimsOutwardDetail.item_code='".$request->item_code."' and trimsOutwardDetail.po_code='".$request->po_code."' 
                                    AND (vendor_work_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' 
                                    OR vendor_purchase_order_master.sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."') GROUP BY trimsOutwardDetail.item_code"); 
               
                //$otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
               
            
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$request->po_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".($row->sales_order_no ? $row->sales_order_no : 0)."' AND tr_type = 2  AND tr_code IS NULL"); 
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
         
                    
                $remainStock = $allocated_qty - $eachAvaliableQty;
            
               
               $avilable_stock = $remainStock - $fabricOutwardStock;
               $bomData = DB::SELECT("SELECT sales_order_no FROM bom_master WHERE bom_code ='".$request->bom_code."'");
               
               $main_sales_order_no = isset($bomData[0]->sales_order_no) ? $bomData[0]->sales_order_no : 0;
               
               if($main_sales_order_no == $row->sales_order_no)
               {
                    $disabledTxt = 'readonly';
               }
               else
               {
                   $disabledTxt = '';
               }
               
                if($row->sales_order_no == '')
                {
                    $blankData = DB::SELECT("SELECT sum(qty) as item_qty FROM stock_association WHERE po_code = '".$request->po_code."' AND item_code = '".$request->item_code."' AND sales_order_no = ''");
                     
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
              
                 $html .='<tr>';
            
                  $html .='<td><input type="hidden" class="form-control" name="bom_code[]" value="'.$row->bom_code.'" style="width: 92px;" readonly/><input type="text" class="form-control" value="'.$request->po_code.'" style="width: 150px;" readonly/></td>
                         <td><input type="text" class="form-control"  name="sales_order_no[]" value="'.$row->sales_order_no.'" style="width: 92px;" readonly/></td>
                         <td><input type="text" class="form-control" name="item_code[]" value="'.$row->item_code.'" style="width: 92px;" readonly /></td>
                         <td style="width: 250px;">'.$row->item_name.'</td>
                         <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", ($totalAssoc))).'</td> 
                         <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", ($remainStock))).'</td> 
                         <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", ($fabricOutwardStock ? $fabricOutwardStock : $blankQty))).'</td> 
                         <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", (($remainStock) - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty)))).'</td>
                         <td>
                            <input type="number" class="form-control" min="0" max="'.sprintf ("%.2f",(($remainStock) - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty))).'" step="any" name="qty[]"  onkeyup="checkQty(this);"  value="" style="width: 100px;" '.$disabledTxt.' />
                            <input type="hidden" class="form-control" name="po_code" value="'.$request->po_code.'" />
                            <input type="hidden" class="form-control" name="po_date" value="'.$row->po_date.'" />
                            <input type="hidden" class="form-control" name="master_bom_code" value="'.$request->bom_code.'" />
                            <input type="hidden" class="form-control" name="master_item_code" value="'.$row->item_code.'" />
                         </td>';
                $html .='</tr>';
          
             $eachAvaliableQty = 0;  
             $avilable_stock = 0;
             $total_avaliable_stock += ($remainStock) - ($fabricOutwardStock ? $fabricOutwardStock : $blankQty);
            }
            
            
            
            foreach ($data1 as $row1) 
            {
               if($request->bom_code == $row1->sales_order_no)
               {
                    $disabledTxt = 'readonly';
               }
               else
               {
                   $disabledTxt = '';
               }
               
                
                $html .='<tr> 
                     <td><input type="hidden" class="form-control" name="bom_code[]" value="'.$request->bom_code.'" style="width: 92px;" readonly/><input type="text" class="form-control" value="'.$request->po_code.'" style="width: 150px;" readonly/></td>
                     <td><input type="text" class="form-control"  name="sales_order_no[]" value="'.$row1->sales_order_no.'" style="width: 92px;" readonly/></td>
                     <td><input type="text" class="form-control" name="item_code[]" value="'.$row1->item_code.'" style="width: 92px;" readonly /></td>
                     <td style="width: 250px;">'.$row1->item_name.'</td>
                     <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", 0)).'</td> 
                     <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", 2)).'</td> 
                     <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", $row1->item_qty)).'</td> 
                     <td class="text-right">'.money_format('%!.2n', sprintf("%.2f", (0 - $row1->item_qty))).'</td> 
                     <td>
                        <input type="number" class="form-control" min="0" max="'.sprintf ("%.2f", (0 - $row1->item_qty)).'" step="any" name="qty[]" value="" style="width: 100px;" onkeyup="checkQty(this);" '.$disabledTxt.' />
                        <input type="hidden" class="form-control" name="po_code" value="'.$request->po_code.'" />
                        <input type="hidden" class="form-control" name="po_date" value="" />
                        <input type="hidden" class="form-control" name="master_bom_code" value="'.$request->bom_code.'" />
                        <input type="hidden" class="form-control" name="master_item_code" value="'.$row1->item_code.'" />
                     </td> 
                </tr>';
                $total_avaliable_stock += 0 - $row1->item_qty;
            }
            
            $html .='<tr> 
                         <th colspan="7" class="text-right">Total</th>
                         <th class="text-right">'.money_format('%!.2n',sprintf ("%.2f", $total_avaliable_stock)).'</th>  
                    </tr>';  
        }
        return response()->json(['html' => $html]);
    }
    
    public function GetItemDataFromDetail(Request $request)
    {
    
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        $bom_code = $request->bom_code;
    
    
        if($cat_id == 2)
        {
            
            if (strpos($bom_code, 'SIN-') === 0) 
            { 
                 $Itemlist = DB::select("SELECT sample_indent_sewing_trims.sewing_trims_item_code as item_code,item_master.item_name FROM sample_indent_sewing_trims 
                        INNER JOIN item_master ON item_master.item_code = sample_indent_sewing_trims.sewing_trims_item_code WHERE delflag=0 AND sample_indent_sewing_trims.sample_indent_code='".$bom_code."' 
                        AND item_master.class_id='".$class_id."'");
            
            }
            else
            {   
                $Itemlist = DB::select("SELECT bom_sewing_trims_details.item_code,item_master.item_name FROM bom_sewing_trims_details 
                INNER JOIN item_master ON item_master.item_code = bom_sewing_trims_details.item_code WHERE delflag=0 AND bom_sewing_trims_details.bom_code='".$bom_code."' 
                AND bom_sewing_trims_details.class_id='".$class_id."'");
            }
            
          
        }
        else if($cat_id == 3)
        {  
            if (strpos($bom_code, 'SIN-') === 0) 
            { 
                 $Itemlist = DB::select("SELECT sample_indent_packing_trims.packing_trims_item_code as item_code,item_master.item_name FROM sample_indent_packing_trims 
                        INNER JOIN item_master ON item_master.item_code = sample_indent_packing_trims.packing_trims_item_code WHERE delflag=0 AND sample_indent_packing_trims.sample_indent_code='".$bom_code."' 
                        AND item_master.class_id='".$class_id."'");
            
            }
            else
            {   
                $Itemlist = DB::select("SELECT bom_packing_trims_details.item_code,item_master.item_name FROM bom_packing_trims_details 
                    INNER JOIN item_master ON item_master.item_code = bom_packing_trims_details.item_code WHERE delflag=0 AND bom_packing_trims_details.bom_code='".$bom_code."' 
                    AND bom_packing_trims_details.class_id='".$class_id."'");
            } 
        }
        else
        {
            $Itemlist = DB::select("SELECT bom_packing_trims_details.item_code,item_master.item_name FROM bom_packing_trims_details 
            INNER JOIN item_master ON item_master.item_code = bom_packing_trims_details.item_code WHERE delflag=0 AND bom_packing_trims_details.bom_code='".$bom_code."'");
        }
       // dd(DB::getQueryLog());
        $html = '<option value="All">--All--</option>';
        
        foreach($Itemlist as $row)
        {
            $html .= '<option value="'.$row->item_code .'">'.$row->item_name .'-('.$row->item_code .')</option>';
        }
        return response()->json(['html' => $html]);
    
    }
    
    
    public function DumpTrimsStockAssocation()
    { 
        
        $trimsAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                            FROM stock_association as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  1  GROUP BY sta.po_code, sta.sales_order_no,sta.item_code");
        DB::table('dump_trims_stock_association')->delete();
        foreach($trimsAssocData as $row)
        { 
            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
           
            $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
            
            $trimsOutwardData1 = DB::select("select sum(trimsOutwardDetail.item_qty) as qty  FROM trimsOutwardDetail INNER JOIN  vendor_work_order_master ON  vendor_work_order_master.vw_code =  trimsOutwardDetail.vw_code
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND vendor_work_order_master.sales_order_no = '".$row->sales_order_no."' AND trimsOutwardDetail.item_code = ".$row->item_code);
                                        
            $trimsOutwardData2 = DB::select("select sum(trimsOutwardDetail.item_qty) as qty  FROM trimsOutwardDetail INNER JOIN  vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  trimsOutwardDetail.vpo_code
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND trimsOutwardDetail.item_code = ".$row->item_code);
                                        
            $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0;
            $trimsOutwardStock1 = isset($trimsOutwardData1[0]->qty) ? $trimsOutwardData1[0]->qty : 0;
            $trimsOutwardStock2 = isset($trimsOutwardData2[0]->qty) ? $trimsOutwardData2[0]->qty : 0;
            $trimsOutwardStock = $trimsOutwardStock1 + $trimsOutwardStock2;
         
            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
            //dd(DB::getQueryLog());
            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
            $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            

             $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
            DB::table('dump_trims_stock_association')->insert(
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
                  'trimOutwardStock' => $trimsOutwardStock,
                  'eachAvaliableQty' =>  $eachAvaliableQty,
                )
            );
        }
        return 1;
    }  


    public function rptTrimsAssocation(Request $request)
{       ini_set('memory_limit', '10G'); 

        if ($request->ajax()) {

    $trimsAssocData = DB::table('dump_trims_stock_association as dts')
        ->select(
            'dts.*',
            'item_category.cat_name',
            'ledger_master.ac_short_name as supplier_name',
            'ledger_details.trade_name',
            'ledger_details.site_code'
        )
        ->join('item_master', 'item_master.item_code', '=', 'dts.item_code')
        ->join('item_category', 'item_category.cat_id', '=', 'item_master.cat_id')
        ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'dts.po_code')
        ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'purchase_order.Ac_code')
        ->leftJoin('ledger_details', 'ledger_details.sr_no', '=', 'purchase_order.bill_to')
        ->get();

    return Datatables::of($trimsAssocData)
        ->addColumn('srno', function ($row) {
            static $i = 1;
            return $i++;
        })
        ->addColumn('avilable_stock', function ($row) {
            $remainStock = ($row->totalAssoc <= 0 && $row->bom_code != "")
                ? $row->allocated_qty - $row->eachAvaliableQty
                : $row->allocated_qty - $row->otherAvaliableStock;

            return number_format(round($remainStock - $row->trimOutwardStock, 2), 2, '.', ',');
        })
        ->addColumn('remainStock', function ($row) {
            $remainStock = ($row->totalAssoc <= 0 && $row->bom_code != "")
                ? $row->allocated_qty - $row->eachAvaliableQty
                : $row->allocated_qty - $row->otherAvaliableStock;

            return number_format(round($remainStock, 2), 2, '.', ',');
        })
        ->addColumn('totalAssoc', fn($row) => number_format(round($row->totalAssoc, 2), 2, '.', ','))
        ->addColumn('bill_to', fn($row) => $row->site_code ? "{$row->trade_name}({$row->site_code})" : $row->trade_name)
        ->addColumn('trimOutwardStock', fn($row) => number_format(round($row->trimOutwardStock, 2), 2, '.', ','))
        ->rawColumns(['srno','avilable_stock','remainStock','totalAssoc','trimOutwardStock','bill_to'])
        ->make(true);
}


    return view('rptTrimsAssocation');
}

        
    /*public function rptTrimsAssocation(Request $request)
    {  
        ini_set('memory_limit', '10G'); 

        if ($request->ajax()) 
        { 
            
        //DB::enableQueryLog();
        $trimsAssocData = DB::select("SELECT dump_trims_stock_association.*,item_category.cat_name,(SELECT ac_short_name FROM ledger_master WHERE ledger_master.ac_code = purchase_order.Ac_code LIMIT 1) as supplier_name,(SELECT trade_name FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to LIMIT 1) as trade_name,
                   (SELECT site_code FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to LIMIT 1) as site_code  FROM dump_trims_stock_association INNER JOIN item_master ON item_master.item_code = dump_trims_stock_association.item_code 
                 INNER JOIN item_category ON item_category.cat_id = item_master.cat_id 
                 LEFT JOIN purchase_order ON purchase_order.pur_code = dump_trims_stock_association.po_code ");
      //dd(DB::getQueryLog());
            return Datatables::of($trimsAssocData) 
            ->addColumn('srno', function ($row) {
                static $i = 1;
                return $i++;
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
               
               $avilable_stock = number_format(round($remainStock - $row->trimOutwardStock, 2), 2, '.', ',');
               return $avilable_stock;
            }) 
            ->addColumn('remainStock',function ($row) 
            {
               if($row->totalAssoc <= 0 && $row->bom_code != "")
               { 
                    $remainStock = $row->allocated_qty - $row->eachAvaliableQty;
               }
               else
               {
                    
                    $remainStock =  $row->allocated_qty - $row->otherAvaliableStock;
               }
                
               return  number_format(round($remainStock, 2), 2, '.', ',');
            }) 
            ->addColumn('totalAssoc',function ($row) 
            { 
               $totalAssoc = number_format(round($row->totalAssoc, 2), 2, '.', ',');
               return $totalAssoc;
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
               
               return $bill_to;
            }) 
            ->addColumn('trimOutwardStock',function ($row) 
            { 
               $trimOutwardStock = number_format(round($row->trimOutwardStock, 2), 2, '.', ',');
               return number_format(round($trimOutwardStock, 2), 2, '.', ',');
            }) 
             ->rawColumns(['srno','avilable_stock','remainStock','totalAssoc','trimOutwardStock','bill_to'])
             
             ->make(true);
    
            }    
          return view('rptTrimsAssocation'); 
    }  */
}
