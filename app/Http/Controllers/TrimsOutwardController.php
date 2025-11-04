<?php

namespace App\Http\Controllers;

use App\Models\TrimsOutwardMasterModel;
use App\Models\TrimsOutwardDetailModel;
use App\Models\LedgerModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\ItemModel;
use App\Models\StockAssociationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use DataTables;
setlocale(LC_MONETARY, 'en_IN');
ini_set('memory_limit', '10G');
use App\Services\TrimsInOutActivityLog;
use App\Services\TrimsInOutMasterActivityLog;



class TrimsOutwardController extends Controller
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
        ->where('form_id', '107')
        ->first();
        
        if( $request->page == 1)
        {
            $FabricOutwardList = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
             ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
             ->join('trimsOutwardDetail', 'trimsOutwardDetail.trimOutCode', '=', 'trimOutwardMaster.trimOutCode')
             ->leftJoin('outward_type_master', 'outward_type_master.out_type_id', '=', 'trimOutwardMaster.out_type_id')
             ->where('trimOutwardMaster.delflag','=', '0')
             ->groupBy('trimsOutwardDetail.trimOutCode')
             ->get(['trimOutwardMaster.*',DB::Raw('sum(trimsOutwardDetail.item_qty * trimsOutwardDetail.item_rate) as outAmount'),'usermaster.username','ledger_master.Ac_name','outward_type_master.out_type_name' ]);
        }
        else
        {
            $FabricOutwardList = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
             ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
             ->join('trimsOutwardDetail', 'trimsOutwardDetail.trimOutCode', '=', 'trimOutwardMaster.trimOutCode')
             ->leftJoin('outward_type_master', 'outward_type_master.out_type_id', '=', 'trimOutwardMaster.out_type_id')
             ->where('trimOutwardMaster.delflag','=', '0')
             ->where('trimOutwardMaster.tout_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
             ->groupBy('trimsOutwardDetail.trimOutCode')
             ->get(['trimOutwardMaster.*',DB::Raw('sum(trimsOutwardDetail.item_qty * trimsOutwardDetail.item_rate) as outAmount'),'usermaster.username','ledger_master.Ac_name','outward_type_master.out_type_name' ]);
        }
 
        if ($request->ajax()) 
        {
            return Datatables::of($FabricOutwardList)
            ->addIndexColumn()
            ->addColumn('trimOutCode1',function ($row) {
        
                 $trimOutData =substr($row->trimOutCode,2,15);
        
                 return $trimOutData;
            }) 
            ->addColumn('vw_code',function ($row) {
        
                if($row->out_type_id==3)
                {
                    $vw_codeData = isset($row->vw_code) ? $row->vw_code : $row->vpo_code ;
                }
                else if($row->out_type_id==7)
                {
                     $vw_codeData = isset($row->sample_indent_code) ? $row->sample_indent_code : '';
                }
                else
                {
                    $vw_codeData = '';
                }
        
                return $vw_codeData;
            }) 
            ->addColumn('Trim_Type',function ($row) {
        
                 $Trim_TypeData = '';
                 if($row->trim_type==1)
                 { 
                     $Trim_TypeData = 'Sewing Trims';
                 }
                 else if($row->trim_type==2)
                 {
                     $Trim_TypeData = 'Packing Trims';
                 }
                 else
                 {
                     $Trim_TypeData = 'All';
                 }
                 return $Trim_TypeData;
            }) 
            ->addColumn('sales_order_no',function ($row) {
        
                 $sales_order_noData = '';
                 if($row->trim_type==1)
                 { 
                     $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
                                        where vw_code='".$row->vw_code."'");
                     
                     $sales_order_noData = isset($VWList[0]->sales_order_no) ? $VWList[0]->sales_order_no :0;
                 }
                 else if($row->trim_type==2)
                 {
                      $VWList=DB::select("select distinct vendor_purchase_order_master.sales_order_no  as sales_order_no, ledger_master.ac_name  from vendor_purchase_order_master inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
                                    where vpo_code='".$row->vpo_code."'");
                      $sales_order_noData = isset($VWList[0]->sales_order_no) ? $VWList[0]->sales_order_no :0;
                 }
                 else
                 {
                     $sales_order_noData = '-';
                 }
                 return $sales_order_noData;
            }) 
            ->addColumn('vendorName',function ($row) {
        
                 $sales_order_noData = '';
                 if($row->trim_type==1)
                 { 
                     $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
                                        where vw_code='".$row->vw_code."'");
                     
                     $vendorNameData = isset($VWList[0]->ac_name) ? $VWList[0]->ac_name :'';
                 }
                 else if($row->trim_type==2)
                 {
                      $VWList=DB::select("select distinct vendor_purchase_order_master.sales_order_no  as sales_order_no, ledger_master.ac_name  from vendor_purchase_order_master inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
                                    where vpo_code='".$row->vpo_code."'");
                      $vendorNameData = isset($VWList[0]->ac_name) ? $VWList[0]->ac_name :'';
                 }
                 else if($row->trim_type==0)
                 {
                      $VWList=DB::select("select distinct  ledger_master.ac_name from sample_indent_master inner join ledger_master on ledger_master.ac_code=sample_indent_master.Ac_code
                                    where sample_indent_code='".$row->sample_indent_code."'");
                      $vendorNameData = isset($VWList[0]->ac_name) ? $VWList[0]->ac_name :'';
                 }
                 else
                 {
                     $vendorNameData = '';
                 }
                 return $vendorNameData;
            })
            ->addColumn('action1', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="TrimOutwardStandardPrint2/'.$row->trimOutCode.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            })
            ->addColumn('action2', function ($row) 
            {
                $btn2 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="TrimOutwardStandardPrint/'.$row->trimOutCode.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn2;
            })
            ->addColumn('action3', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('TrimsOutward.edit', $row->trimOutCode).'" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                }
                else
                { 
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';   
                }
                return $btn3;
            })
            ->addColumn('action4', function ($row) use ($chekform){
         
                if($chekform->delete_access==1 || Session::get('user_type') == 1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->trimOutCode.'"  data-route="'.route('TrimsOutward.destroy', $row->trimOutCode).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['action1','action2','action3','action4'])
    
            ->make(true);
        }
        return view('trimOutwardList', compact('FabricOutwardList','chekform'));   
         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='TRIMOUTWARD'");
      
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $OutTypeList = DB::table('outward_type_master')->where('outward_type_master.delflag','=', '0')->whereIN('outward_type_master.out_type_id', [3,4,5,7])->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $vendorcodeList = DB::table('vendor_work_order_master')->select('vw_code')->where('vendor_work_order_master.delflag','=', '0')->get();
        $itemlist = ItemModel::where('item_master.delflag','=', '0')->get();
        $unitlist = DB::table('unit_master')->get();
        $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
        return view('TrimsOutward',compact('Ledger','POList','counter_number','MainStyleList','SubStyleList','vendorcodeList','FGList','itemlist','unitlist','OutTypeList'));
     
    }
 
    public function getTrimsItemRate(Request $request)
    { 
        $item_code= $request->input('item_code');
        $po_code= base64_decode($request->input('po_code'));
        $vw_code= $request->vw_code;
  
       // $vendorlist = DB::table('vendor_work_order_master')->select('sales_order_no')->where('delflag',0)->where('vw_code',$request->vw_code)->first();
        //$sales_order_no =  $vendorlist->sales_order_no;
        
        $Rate = DB::select("select  item_rate from trimsInwardDetail
        inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
        where trimsInwardMaster.po_code='".$po_code."' and trimsInwardDetail.item_code='".$item_code."'");
        
       //DB::enableQueryLog();
        $StockData = DB::select("select sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
            
            (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
            where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty 
            
            from trimsInwardDetail
            left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            WHERE item_master.cat_id !=4 AND trimsInwardMaster.po_code ='".$po_code."'  and trimsInwardDetail.item_code='".$item_code."'
            group by trimsInwardMaster.po_code,trimsInwardDetail.item_code");
            
        //$stockAssociatedData = DB::SELECT("SELECT qty FROM stock_association WHERE po_code='".$po_code."' AND item_code=".$item_code." AND sales_order_no='".$sales_order_no."'");    
       // $already_allocated_qty = isset($stockAssociatedData[0]->qty) ? $stockAssociatedData[0]->qty : 0;
        //dd(DB::getQueryLog());
        
        $ActualStock = 0;
        foreach($StockData as $row)
        {
            $ActualStock = ($row->item_qty - $row->out_qty);
        }
       
        $dataArr = array($Rate,$ActualStock);
        return response()->json(['dataArr' => $dataArr,'rate'=>$Rate, 'already_allocated_qty' => 0]); 
    }

    public function getVendorCode(Request $request)
    {
    
    	    $html = '';
            $html .= '<option value="">--Select--</option>';
           
            $vendorlist = DB::table('vendor_work_order_master')
            ->select('vw_code','sales_order_no')->where('delflag',0)->where('vendorId',$request->vendorId)
            ->get();
            
            
            // ->whereRaw("vendor_work_order_master.vw_code  NOT IN(select trimOutwardMaster.vw_code from trimOutwardMaster where trimOutwardMaster.vw_code= vendor_work_order_master.vw_code)")
            
            
            foreach ($vendorlist as $rowvendor) {
            $html .= '<option value="'.$rowvendor->vw_code.'">'.$rowvendor->vw_code.' ('.$rowvendor->sales_order_no.')</option>';
                  }
           
            return response()->json(['html' => $html]);
    }


public function getVendorProcessOrder(Request $request)
{

	    $html = '';
        $html .= '<option value="">--Select--</option>';
       
        $vendorlist = DB::table('vendor_purchase_order_master')
        ->select('vpo_code','sales_order_no')->where('delflag',0)->where('process_id',3)->where('vendorId',$request->vendorId)
        
        ->get();
      //  ->whereRaw("vendor_purchase_order_master.vpo_code  NOT IN(select trimOutwardMaster.vpo_code from trimOutwardMaster where trimOutwardMaster.vpo_code= vendor_purchase_order_master.vpo_code)")
        foreach ($vendorlist as $rowvendor) {
        $html .= '<option value="'.$rowvendor->vpo_code.'">'.$rowvendor->vpo_code.' ('.$rowvendor->sales_order_no.')</option>';
              }
       
        return response()->json(['html' => $html]);
}



public function getVendorMasterDetail(Request $request)
{
    
    
    $fetchdata=DB::table('vendor_work_order_master')
    ->select('mainstyle_id','substyle_id','fg_id','style_no','style_description')->where('delflag',0)->where('vw_code',$request->vw_code)->first();
    
    
    echo json_encode($fetchdata);
    
    
}

public function get_associated_stock(Request $request)
{
        $workOrderData = DB::SELECT("SELECT sales_order_no FROM vendor_work_order_master WHERE vw_code='".$request->vw_code."'");    
        $sales_order_no = isset($workOrderData[0]->sales_order_no) ? $workOrderData[0]->sales_order_no : NULL;
       // DB::enableQueryLog();

        $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
            FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
            WHERE sta.sales_order_no='".$sales_order_no."' AND sta.po_code='".$request->po_code."' AND sta.item_code='".$request->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
         // dd(DB::getQueryLog());
        $assoc_stock = 0;
        $remainStock=0;
         
        foreach ($data1 as $row) 
        {
            if($row->po_type_id == 2 || $row->is_opening ==1)
            { 
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
            }
            else
            {     
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            }
            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
          
           if($row->cat_id == 2)
           {
               $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                    INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                    WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_work_order_master.sales_order_no='".$row->sales_order_no."'"); 
           }
           else
           { 
               $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                    INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                    WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
           }
           $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
           
           // DB::enableQueryLog();
           if($row->po_type_id == 2 || $row->is_opening ==1)
           {
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
           }
           else
           {
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
           }
            //dd(DB::getQueryLog());
            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
           
            $remainStock = $allocated_qty - $eachAvaliableQty;
            
               
           $assoc_stock += ($remainStock - $trimsOutwardStock);
       
        }
        
        return $assoc_stock;  
}


public function get_associated_stock_sample(Request $request)
{ 
         //DB::enableQueryLog();
        $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
            FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
            WHERE sta.sales_order_no='".$request->sample_indent_code."' AND sta.po_code='".$request->po_code."' AND sta.item_code='".$request->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
          
         //dd(DB::getQueryLog());
        $assoc_stock = 0;
        $remainStock=0;
         
        foreach ($data1 as $row) 
        {
            if($row->po_type_id == 2 || $row->is_opening ==1)
            { 
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
            }
            else
            {     
                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
            }
            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
          
           if($row->cat_id == 2)
           {
               $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                    INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                    WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_work_order_master.sales_order_no='".$row->sales_order_no."'"); 
           }
           else
           { 
               $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                    INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                    WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
           }
           
             
           if (strpos($row->sales_order_no, 'SIN-') === 0) 
           {
                $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail 
                                    WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND sample_indent_code='".$row->sales_order_no."'"); 
           }
               
           $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
           
           // DB::enableQueryLog();
           if($row->po_type_id == 2 || $row->is_opening ==1)
           {
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
           }
           else
           {
                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
           }
            //dd(DB::getQueryLog());
            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
           
            $remainStock = $allocated_qty - $eachAvaliableQty;
            
               
           $assoc_stock += ($remainStock - $trimsOutwardStock);
       
        } 
        
        return response()->json(['assoc_stock' => $assoc_stock]);
         
}


    public function get_associated_stock_packing(Request $request)
    {
            $workPurchaseData = DB::SELECT("SELECT sales_order_no FROM vendor_purchase_order_master WHERE vpo_code='".$request->vpo_code."'");    
            $sales_order_no = $workPurchaseData[0]->sales_order_no;
            
            $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                WHERE sta.sales_order_no='".$sales_order_no."' AND sta.po_code='".$request->po_code."' AND sta.item_code='".$request->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
              
            $assoc_stock = 0;
            $remainStock=0;
             
            foreach ($data1 as $row) 
            {
                if($row->po_type_id == 2 || $row->is_opening ==1)
                { 
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                }
                else
                {     
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                }
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                
              
               if($row->cat_id == 2)
               {
                   $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                        INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_work_order_master.sales_order_no='".$row->sales_order_no."'"); 
               }
               else
               { 
                   $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                        INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
               }
               $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
               
               // DB::enableQueryLog();
               if($row->po_type_id == 2 || $row->is_opening ==1)
               {
                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
               }
               else
               {
                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
               }
                //dd(DB::getQueryLog());
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
               
                $remainStock = $allocated_qty - $eachAvaliableQty;
                
                   
               $assoc_stock += ($remainStock - $trimsOutwardStock);
           
            }
                        
            return $assoc_stock;  
    }



public function getvendortablenew(Request $request)
{
    $itemlist=DB::table('vendor_work_order_sewing_trims_details')->select('item_master.item_code','item_master.item_name')->Join('item_master', 'item_master.item_code', '=', 'vendor_work_order_sewing_trims_details.item_code')->where('vw_code','=',$request->vw_code)->distinct()->get();

    $unitlist=DB::table('unit_master')->get();

    $data = DB::select(DB::raw("SELECT vendor_work_order_sewing_trims_details.`item_code`,sum(`bom_qty`)  as totalQty,vendor_work_order_sewing_trims_details.unit_id, item_master.item_description,vendor_work_order_sewing_trims_details.sales_order_no  FROM `vendor_work_order_sewing_trims_details`
    inner join item_master on item_master.item_code=vendor_work_order_sewing_trims_details.item_code
    where vw_code='".$request->vw_code."' group by item_code"));
      
    $html ='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';

       $html .= '
       <div class="table-wrap" id="trimInward">
        <div class="table-responsive">
               <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
        <thead>
        <tr>
        <th>SrNo</th>
        <th>PO NO</th>
        <th>Item Code</th>
        <th>Item Name</th>
        <th>Description</th> 
        <th>Unit</th>
        <th>Associated Stock</th>
        <th>Order Qty</th>
        <th>Stock</th>
        <th>Actual Stock</th>
        <th>Quantity</th>
        <th>Add/Remove</th>
        </tr>
        </thead>
        <tbody>';
        $no=1;
      foreach ($data as $value) 
      {
          
        $POList=DB::table('stock_association')->select('po_code')->where('item_code', '=', $value->item_code)->distinct()->get();
        
        $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                ) as Stock"));
                
        $StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
                    where item_code='".$value->item_code."' and vw_code='".$request->vw_code."'"));
        
                    
        // $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
        //     FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
        //     WHERE sta.sales_order_no='".$value->sales_order_no."' AND sta.item_code='".$value->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
          
        $assoc_stock = 0;
        // $remainStock=0;
        
        // foreach ($data1 as $row) 
        // {
        //     if($row->po_type_id == 2 || $row->is_opening ==1)
        //     { 
        //         $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
        //     }
        //     else
        //     {     
        //         $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
        //     }
        //     $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
            
          
        //   if($row->cat_id == 2)
        //   {
        //       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
        //                             INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
        //                             WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_work_order_master.sales_order_no='".$row->sales_order_no."'"); 
        //   }
        //   else
        //   { 
        //       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
        //                             INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
        //                             WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
        //   }
        //   $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
           
        //   // DB::enableQueryLog();
        //   if($row->po_type_id == 2 || $row->is_opening ==1)
        //   {
        //         $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
        //   }
        //   else
        //   {
        //         $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
        //   }
        //     //dd(DB::getQueryLog());
        //     $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
           
        //     $remainStock = $allocated_qty - $eachAvaliableQty;
            
               
        //   $assoc_stock += ($remainStock - $trimsOutwardStock);
       
        // }
         
        $html .='<tr class="tr_clone">';
    
        $html .='<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
                 <td> <select name="po_code[]" class="select2 po_code"  id="po_code" style="width:250px; height:30px;"  onchange="GetTrimsItemList(this);getAssociatedStock(this);" >
                        <option value="">--PO NO--</option>';
                        foreach($POList as  $rowpo)
                        {
                            $html.='<option value="'.$rowpo->po_code.'"';
                            $html.='>'.$rowpo->po_code.'</option>';
                        }
  
        $html.='</select></td>
                <td class="i_codes">'.$value->item_code.'</td>
                <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" onchange="GetItemDescription(this);checkDuplicates(this);" required disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'('.$row1->item_code.')</option>';
                        }
 
        $html.='</select></td> 
                <td>'.$value->item_description.'</td>';
                
                $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
                <option value="">--Select Unit--</option>';
                 
                foreach($unitlist as  $rowunit)
                {
                    $html.='<option value="'.$rowunit->unit_id.'"';
                    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowunit->unit_name.'</option>';
                }
         
        $html.='</select></td>';
        $html.='<td><input type="text" class="assoc_qty" value="'.(round($assoc_stock)).'" style="width:80px;" readOnly/></td>';
        $html.='<td><input type="text" class="order_qty"  value="'.(round($value->totalQty-$StockOut[0]->StockOut)).'" style="width:80px;" readOnly/></td>';
        $html.='<td><input type="text" class="stock"  value="0" style="width:80px;" readOnly/></td>';
        
        $html.='<td><input type="text" class="actual_stock_qty" onclick="stockPopup(this);" value="'.(round($stock[0]->Stock)).'" style="width:80px;" readOnly/></td>
                <td><input type="number" step="any" class="QTY" name="item_qtys[]" value="0" id="item_qty" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);"  />
                <input type="hidden" name="item_rate[]"   value="0" id="item_rate" style="width:80px;" required/>
                </td> 
                <td><button type="button" onclick="insertRow(this);mycalc();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>';
      
        $html .='</tr>';
        $no=$no+1;


    }
        $html .='</table></div></div>';
        return response()->json(['html' => $html]); 
    }

    public function getvendortablenewTrial(Request $request)
    {
        $itemlist=DB::table('vendor_work_order_sewing_trims_details')->select('item_master.item_code','item_master.item_name')->Join('item_master', 'item_master.item_code', '=', 'vendor_work_order_sewing_trims_details.item_code')->where('vw_code','=',$request->vw_code)->distinct()->get();
    
        $unitlist=DB::table('unit_master')->get();
    
        $data = DB::select(DB::raw("SELECT vendor_work_order_sewing_trims_details.`item_code`,sum(`bom_qty`)  as totalQty,vendor_work_order_sewing_trims_details.unit_id, item_master.item_description,vendor_work_order_sewing_trims_details.sales_order_no  FROM `vendor_work_order_sewing_trims_details`
        inner join item_master on item_master.item_code=vendor_work_order_sewing_trims_details.item_code
        where vw_code='".$request->vw_code."' group by item_code"));
          
        $html ='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';
    
        $html .= '<div class="table-wrap" id="trimInward">
            <div class="table-responsive">
                   <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
            <thead>
            <tr>
            <th>SrNo</th>
            <th>PO NO</th>
            <th>Item Code</th>
            <th>Item Name</th>
            <th>Description</th> 
            <th>Unit</th>
            <th>Associated Stock</th>
            <th>Order Qty</th>
            <th>Stock</th>
            <th>Actual Stock</th>
            <th>Quantity</th>
            <th>Add/Remove</th>
            </tr>
            </thead>
            <tbody>';
            $no=1;
          foreach ($data as $value) 
          {
              
            $POList=DB::table('stock_association')->select('po_code')->where('item_code', '=', $value->item_code)->distinct()->get();
           
            $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                    (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                    ) as Stock"));
                    
            $StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
                        where item_code='".$value->item_code."' and vw_code='".$request->vw_code."'"));
            
            $POList1 = DB::SELECT("
                            SELECT po_code 
                            FROM stock_association 
                            WHERE tr_type = 1 
                                AND item_code = ".$value->item_code." 
                                AND sales_order_no = '".$value->sales_order_no."' 
                            GROUP BY po_code
                            HAVING SUM(qty) = (
                                SELECT MAX(total_qty) 
                                FROM (
                                    SELECT SUM(qty) as total_qty 
                                    FROM stock_association 
                                    WHERE tr_type = 1 
                                        AND item_code = ".$value->item_code." 
                                        AND sales_order_no = '".$value->sales_order_no."' 
                                    GROUP BY po_code
                                ) as subquery
                            )
                        ");
            
            $selectPO_Code =  isset($POList1[0]->po_code) ? $POList1[0]->po_code : '';  
            
            $workOrderData = DB::SELECT("SELECT sales_order_no FROM vendor_work_order_master WHERE vw_code='".$request->vw_code."'");    
            $sales_order_no = $workOrderData[0]->sales_order_no;
            
            $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                WHERE sta.sales_order_no='".$sales_order_no."' AND sta.po_code='".$selectPO_Code."' AND sta.item_code='".$value->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
              
            $assoc_stock = 0;
            $remainStock=0;
             
            foreach ($data1 as $row) 
            {
                if($row->po_type_id == 2 || $row->is_opening ==1)
                { 
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                }
                else
                {     
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                }
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                
              
               if($row->cat_id == 2)
               {
                   $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                        INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_work_order_master.sales_order_no='".$row->sales_order_no."'"); 
               }
               else
               { 
                   $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                        INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
               }
               $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
               
               // DB::enableQueryLog();
               if($row->po_type_id == 2 || $row->is_opening ==1)
               {
                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
               }
               else
               {
                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
               }
                //dd(DB::getQueryLog());
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
               
                $remainStock = $allocated_qty - $eachAvaliableQty;
                
                   
               $assoc_stock += ($remainStock - $trimsOutwardStock);
           
            }
            
            if($assoc_stock == 0)
            {
                    $POList2 = DB::SELECT("
                        SELECT po_code 
                        FROM stock_association 
                        WHERE tr_type = 1 
                            AND item_code = ".$value->item_code." 
                            AND sales_order_no = '".$value->sales_order_no."' 
                            AND po_code != '".$selectPO_Code."'
                        GROUP BY po_code
                        HAVING SUM(qty) = (
                            SELECT MAX(total_qty) 
                            FROM (
                                SELECT SUM(qty) as total_qty 
                                FROM stock_association 
                                WHERE tr_type = 1 
                                    AND item_code = ".$value->item_code." 
                                    AND sales_order_no = '".$value->sales_order_no."' 
                                    AND po_code != '".$selectPO_Code."'
                                GROUP BY po_code
                            ) as subquery
                        )
                    ");
                            
                $selectPO_Code =  isset($POList2[0]->po_code) ? $POList2[0]->po_code : '';  
            
                $workOrderData = DB::SELECT("SELECT sales_order_no FROM vendor_work_order_master WHERE vw_code='".$request->vw_code."'");    
                $sales_order_no = $workOrderData[0]->sales_order_no;
                
                $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                    FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                    WHERE sta.sales_order_no='".$sales_order_no."' AND sta.po_code='".$selectPO_Code."' AND sta.item_code='".$value->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
                  
                $assoc_stock = 0;
                $remainStock=0;
                 
                foreach ($data1 as $row) 
                {
                    if($row->po_type_id == 2 || $row->is_opening ==1)
                    { 
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                    }
                    else
                    {     
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                    }
                    $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                    
                  
                   if($row->cat_id == 2)
                   {
                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                            INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_work_order_master.sales_order_no='".$row->sales_order_no."'"); 
                   }
                   else
                   { 
                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                   }
                   $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
                   
                   // DB::enableQueryLog();
                   if($row->po_type_id == 2 || $row->is_opening ==1)
                   {
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
                   }
                   else
                   {
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                   }
                    //dd(DB::getQueryLog());
                    $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                   
                    $remainStock = $allocated_qty - $eachAvaliableQty;
                    
                       
                   $assoc_stock += ($remainStock - $trimsOutwardStock);
               
                }
            }
    
            $stockData = DB::SELECT("SELECT (SELECT IFNULL(SUM(item_qty),0) FROM `trimsInwardDetail` WHERE po_code = '".$selectPO_Code."' AND item_code = '".$value->item_code."') 
                        - (SELECT IFNULL(SUM(item_qty),0) FROM `trimsOutwardDetail` WHERE po_code = '".$selectPO_Code."' AND item_code = '".$value->item_code."') as stock");
             //   dd(DB::getQueryLog());  
            $stock_qty = isset($stockData[0]->stock) ? $stockData[0]->stock : 0;
            
            $html .='<tr class="tr_clone">';
        
            $html .='<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
                     <td> <select name="po_code[]" class="select2 po_code"  id="po_code" style="width:250px; height:30px;"  onchange="GetTrimsItemList(this);getAssociatedStock(this);" >';
                            foreach($POList as  $rowpo)
                            {
                                $html.='<option value="'.$rowpo->po_code.'"';
                                $rowpo->po_code == $selectPO_Code ? $html.='selected="selected"' : ''; 
                                $html.='>'.$rowpo->po_code.'</option>';
                            }
      
            $html.='</select></td>
                    <td class="i_codes">'.$value->item_code.'</td>
                    <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" onchange="GetItemDescription(this);checkDuplicates(this);" required disabled>
                            <option value="">--Select Item--</option>';
                            foreach($itemlist as  $row1)
                            {
                                $html.='<option value="'.$row1->item_code.'"';
                                $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                                $html.='>'.$row1->item_name.'('.$row1->item_code.')</option>';
                            }
     
            $html.='</select></td> 
                    <td>'.$value->item_description.'</td>';
                    
                    $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
                    <option value="">--Select Unit--</option>';
                     
                    foreach($unitlist as  $rowunit)
                    {
                        $html.='<option value="'.$rowunit->unit_id.'"';
                        $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                        $html.='>'.$rowunit->unit_name.'</option>';
                    }
             
            $html.='</select></td>';
            $html.='<td><input type="text" class="assoc_qty" value="'.(round($assoc_stock)).'" style="width:80px;" readOnly/></td>';
            $html.='<td><input type="text" class="order_qty"  value="'.(round($value->totalQty-$StockOut[0]->StockOut)).'" style="width:80px;" readOnly/></td>';
            $html.='<td><input type="text" class="stock"  value="'.$stock_qty.'" style="width:80px;" readOnly/></td>';
            
            $assoc_stock = isset($assoc_stock) ? round($assoc_stock) : PHP_INT_MAX;
            $totalQty = isset($value->totalQty) ? $value->totalQty : 0;
            $stockOut = isset($StockOut[0]->StockOut) ? $StockOut[0]->StockOut : 0;
            $stockQty = isset($stock_qty) ? round($stock_qty) : PHP_INT_MAX;
            
            $minItemQty = min($assoc_stock, round($totalQty - $stockOut), $stockQty);
    
            $html.='<td><input type="text" class="actual_stock_qty" onclick="stockPopup(this);" value="'.(round($stock[0]->Stock)).'" style="width:80px;" readOnly/></td>
                    <td><input type="number" step="any" class="QTY" name="item_qtys[]" value="'.$minItemQty.'" id="item_qty" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);"  />
                    <input type="hidden" name="item_rate[]"   value="0" id="item_rate" style="width:80px;" required/>
                    </td> 
                    <td><button type="button" onclick="insertRow(this);mycalc();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>';
          
            $html .='</tr>';
            $no=$no+1;
    
    
        }
        $html .='</table></div></div>';
        return response()->json(['html' => $html]); 
    }
    
    public function getProcessTrimDataTrial(Request $request)
    {
       

        $itemlist=DB::table('vendor_purchase_order_packing_trims_details')->select('item_master.item_code','item_master.item_name')->Join('item_master', 'item_master.item_code', '=', 'vendor_purchase_order_packing_trims_details.item_code')->where('vpo_code','=',$request->vpo_code)->distinct()->get();
  
 
      
        $unitlist=DB::table('unit_master')->get();
        // DB::enableQueryLog();
        $data = DB::select(DB::raw("SELECT vendor_purchase_order_packing_trims_details.`item_code`,sum(`bom_qty`)  as totalQty,item_master.item_description, vendor_purchase_order_packing_trims_details.unit_id,vendor_purchase_order_packing_trims_details.sales_order_no  FROM `vendor_purchase_order_packing_trims_details`
        inner join item_master on item_master.item_code=vendor_purchase_order_packing_trims_details.item_code
        where vpo_code='".$request->vpo_code."'  group by item_code"));
            // dd(DB::getQueryLog());
        $html='';
    
        $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  /> 
           <div class="table-wrap" id="trimInward">
        <div class="table-responsive">
               <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
        <thead>
        <tr>
        <th>SrNo</th>
        <th>PO NO</th>
        <th>Item Code</th>
        <th>Item Name</th>
        <th>Description</th>
        <th>Unit</th>
        <th>Associated Stock</th>
        <th>Order Qty</th>
        <th>Stock</th>
        <th>Actual Stock</th>
        <th>Quantity</th>
        <th>Add/Remove</th>
        </tr>
        </thead>
        <tbody>';
        $no=1;
        foreach ($data as $value)
        {
            
           $POList=DB::table('stock_association')->select('po_code')->where('item_code', '=', $value->item_code)->distinct()->get();
           $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                    (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                    ) as Stock"));
                        
            $StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
            where item_code='".$value->item_code."' and vpo_code='".$request->vpo_code."'"));
                
            $POList1 = DB::SELECT("
                            SELECT po_code 
                            FROM stock_association 
                            WHERE tr_type = 1 
                                AND item_code = ".$value->item_code." 
                                AND sales_order_no = '".$value->sales_order_no."' 
                            GROUP BY po_code
                            HAVING SUM(qty) = (
                                SELECT MAX(total_qty) 
                                FROM (
                                    SELECT SUM(qty) as total_qty 
                                    FROM stock_association 
                                    WHERE tr_type = 1 
                                        AND item_code = ".$value->item_code." 
                                        AND sales_order_no = '".$value->sales_order_no."' 
                                    GROUP BY po_code
                                ) as subquery
                            )
                        ");

            
            $selectPO_Code =  isset($POList1[0]->po_code) ? $POList1[0]->po_code : '';  
            
            $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                WHERE sta.sales_order_no='".$value->sales_order_no."' AND sta.po_code='".$selectPO_Code."' AND sta.item_code='".$value->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
              
            $assoc_stock = 0;
            $remainStock=0;
             
            foreach ($data1 as $row) 
            {
                if($row->po_type_id == 2 || $row->is_opening ==1)
                { 
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                }
                else
                {     
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                }
                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                
              
               if($row->cat_id == 2)
               {
                   $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                        INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
               }
               else
               { 
                   $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                        INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                        WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."'
                                        AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
               }
               $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
               
               // DB::enableQueryLog();
               if($row->po_type_id == 2 || $row->is_opening ==1)
               {
                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
               }
               else
               {
                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' 
                    AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
               }
                //dd(DB::getQueryLog());
                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
               
                $remainStock = $allocated_qty - $eachAvaliableQty;
                
                   
               $assoc_stock += ($remainStock - $trimsOutwardStock);
           
            }
            
            if($assoc_stock == 0 )
            {
                //DB::enableQueryLog();
                $POList2 = DB::SELECT("
                        SELECT po_code 
                        FROM stock_association 
                        WHERE tr_type = 1 
                            AND item_code = ".$value->item_code." 
                            AND sales_order_no = '".$value->sales_order_no."' 
                            AND po_code != '".$selectPO_Code."'
                        GROUP BY po_code
                        HAVING SUM(qty) = (
                            SELECT MAX(total_qty) 
                            FROM (
                                SELECT SUM(qty) as total_qty 
                                FROM stock_association 
                                WHERE tr_type = 1 
                                    AND item_code = ".$value->item_code." 
                                    AND sales_order_no = '".$value->sales_order_no."' 
                                    AND po_code != '".$selectPO_Code."'
                                GROUP BY po_code
                            ) as subquery
                        )
                    ");

                //dd(DB::getQueryLog());         
                $selectPO_Code =  isset($POList2[0]->po_code) ? $POList2[0]->po_code : '';  
            
                $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                    FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                    WHERE sta.sales_order_no='".$value->sales_order_no."' AND sta.po_code='".$selectPO_Code."' AND sta.item_code='".$value->item_code."' GROUP BY sta.sales_order_no,sta.item_code");
                  
                $assoc_stock = 0;
                $remainStock=0;
                 
                foreach ($data1 as $row) 
                {
                    if($row->po_type_id == 2 || $row->is_opening ==1)
                    { 
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                    }
                    else
                    {     
                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' 
                                                        AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                    }
                    $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                    
                  
                   if($row->cat_id == 2)
                   {
                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                   }
                   else
                   { 
                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                   }
                   $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
                   
                   // DB::enableQueryLog();
                   if($row->po_type_id == 2 || $row->is_opening ==1)
                   {
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
                   }
                   else
                   {
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                   }
                    //dd(DB::getQueryLog());
                    $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                   
                    $remainStock = $allocated_qty - $eachAvaliableQty;
                    
                       
                   $assoc_stock += ($remainStock - $trimsOutwardStock);
               
                }
            }
    
            $stockData = DB::SELECT("SELECT (SELECT IFNULL(SUM(item_qty),0) FROM `trimsInwardDetail` WHERE po_code = '".$selectPO_Code."' AND item_code = '".$value->item_code."') 
                        - (SELECT IFNULL(SUM(item_qty),0) FROM `trimsOutwardDetail` WHERE po_code = '".$selectPO_Code."' AND item_code = '".$value->item_code."') as stock");
             //   dd(DB::getQueryLog());  
            $stock_qty = isset($stockData[0]->stock) ? $stockData[0]->stock : 0;
            
            $html .='<tr>';
                
            $html .='
            <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
             <td> <select name="po_code[]" class="select2 po_code"  id="po_code" style="width:250px; height:30px;"  onchange="getAssociatedStockPacking(this);GetTrimsItemList(this);" >
            <option value="">--PO NO--</option>';
            
            foreach($POList as  $rowpo)
            {
                $html.='<option value="'.$rowpo->po_code.'"';
                $rowpo->po_code == $selectPO_Code ? $html.='selected="selected"' : '';
                $html.='>'.$rowpo->po_code.'</option>';
            }
             
            $html.='</select></td> 
            
            <td class="i_codes">'.$value->item_code.' </td>
            <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" onchange="GetItemDescription(this);checkDuplicates(this);" required disabled>
            <option value="">--Select Item--</option>';
            
            foreach($itemlist as  $row1)
            {
                $html.='<option value="'.$row1->item_code.'"';
            
                $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
            
            
                $html.='>'.$row1->item_name.' ('.$row1->item_code.')</option>';
            }
             
            $html.='</select></td> 
            <td>'.$value->item_description.'</td>';
            
            $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
            <option value="">--Select Unit--</option>';
            
            foreach($unitlist as  $rowunit)
            {  
                $html.='<option value="'.$rowunit->unit_id.'"';
                $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                $html.='>'.$rowunit->unit_name.'</option>';
            }
            $html.='</select></td>';
            $html.='<td><input type="text" class="assoc_qty" value="'.(round($assoc_stock)).'" style="width:80px;" readOnly/></td>';
            $html.='<td><input type="text" class="order_qty"  value="'.(round($value->totalQty-$StockOut[0]->StockOut)).'" style="width:80px;" readOnly/></td>';
            $html.='<td><input type="text" class="stock"  value="'.$stock_qty.'" style="width:80px;" readOnly/></td>';
            
            $assoc_stock = isset($assoc_stock) ? round($assoc_stock) : PHP_INT_MAX;
            $totalQty = isset($value->totalQty) ? $value->totalQty : 0;
            $stockOut = isset($StockOut[0]->StockOut) ? $StockOut[0]->StockOut : 0;
            $stockQty = isset($stock_qty) ? round($stock_qty) : PHP_INT_MAX;
            
            $minItemQty = min($assoc_stock, round($totalQty - $stockOut), $stockQty);
            
           $html.='<td><input type="text" class="actual_stock_qty" onclick="stockPopup(this);" value="'.(round($stock[0]->Stock)).'" style="width:80px;" readOnly/></td>
                    <td><input type="number" step="any" class="QTY" name="item_qtys[]" value="'.$minItemQty.'" id="item_qty" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);"  />
                    <input type="hidden" name="item_rate[]"   value="0" id="item_rate" style="width:80px;" required/>
                    </td> 
                    <td><button type="button" onclick="insertRow(this);mycalc();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>';
              
                $html .='</tr>';
                $no=$no+1;
            
            
         }  
               $html .='</table>
               </div>
        </div>';
    
        
        return response()->json(['html' => $html]); 
            
    }


    public function getProcessTrimData(Request $request)
    {
        
        
        $itemlist=DB::table('vendor_purchase_order_packing_trims_details')->select('item_master.item_code','item_master.item_name')->Join('item_master', 'item_master.item_code', '=', 'vendor_purchase_order_packing_trims_details.item_code')->where('vpo_code','=',$request->vpo_code)->distinct()->get();
    
        $unitlist=DB::table('unit_master')->get();
    
        $data = DB::select(DB::raw("SELECT vendor_purchase_order_packing_trims_details.`item_code`,sum(`bom_qty`)  as totalQty,item_master.item_description, vendor_purchase_order_packing_trims_details.unit_id  FROM `vendor_purchase_order_packing_trims_details`
        inner join item_master on item_master.item_code=vendor_purchase_order_packing_trims_details.item_code
        where vpo_code='".$request->vpo_code."'  group by item_code"));
          
        $html='';
    
        $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  /> 
           <div class="table-wrap" id="trimInward">
        <div class="table-responsive">
               <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
        <thead>
        <tr>
        <th>SrNo</th>
        <th>PO NO</th>
        <th>Item Code</th>
        <th>Item Name</th>
        <th>Description</th>
        <th>Unit</th>
        <th>Associated Stock</th>
        <th>Order Qty</th>
        <th>Stock</th>
        <th>Actual Stock</th>
        <th>Quantity</th>
        <th>Add/Remove</th>
        </tr>
        </thead>
        <tbody>';
        $no=1;
              foreach ($data as $value) {
            
               $POList=DB::table('stock_association')->select('po_code')->where('item_code', '=', $value->item_code)->distinct()->get();
               $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                        (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                        ) as Stock"));
                        
        $StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
        where item_code='".$value->item_code."' and vpo_code='".$request->vpo_code."'"));
            
            
           $html .='<tr>';
            
        $html .='
        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
         <td> <select name="po_code[]" class="select2 po_code"  id="po_code" style="width:250px; height:30px;"  onchange="getAssociatedStockPacking(this);GetTrimsItemList(this);" >
        <option value="">--PO NO--</option>';
        
        foreach($POList as  $rowpo)
        {
            $html.='<option value="'.$rowpo->po_code.'"';
            $html.='>'.$rowpo->po_code.'</option>';
        }
         
        $html.='</select></td> 
        
        <td class="i_codes">'.$value->item_code.' </td>
        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;" onchange="GetItemDescription(this);checkDuplicates(this);" required disabled>
        <option value="">--Select Item--</option>';
        
        foreach($itemlist as  $row1)
        {
            $html.='<option value="'.$row1->item_code.'"';
        
            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
        
        
            $html.='>'.$row1->item_name.' ('.$row1->item_code.')</option>';
        }
         
        $html.='</select></td> 
        <td>'.$value->item_description.'</td>';
        
        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
        <option value="">--Select Unit--</option>';
        
        foreach($unitlist as  $rowunit)
        {  
            $html.='<option value="'.$rowunit->unit_id.'"';
            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
            $html.='>'.$rowunit->unit_name.'</option>';
        }
        $html.='</select></td>';
        
        $html.='<td><input type="text" class="assoc_qty" value="0" style="width:80px;" readOnly/></td>';
        $html.='<td><input type="text" class="order_qty"  value="'.(round($value->totalQty-$StockOut[0]->StockOut)).'" style="width:80px;" readOnly/></td>';
        $html.='<td><input type="text" class="stock"  value="0" style="width:80px;" readOnly/></td>';
        
        $html.='<td><input type="text" class="actual_stock_qty" onclick="stockPopup(this);" value="'.(round($stock[0]->Stock)).'" style="width:80px;" readOnly/></td>
                <td><input type="number" step="any" class="QTY" name="item_qtys[]" value="0" id="item_qty" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);"  />
                <input type="hidden" name="item_rate[]"  value="0" id="item_rate" style="width:80px;" required/>
                </td> 
                        
        <td><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
        ';
          
            $html .='</tr>';
            $no=$no+1;
        
        
              }
        
               
               $html .='</table>
               </div>
        </div>';
    
    
    return response()->json(['html' => $html]); 
        
}



public function getItemDescription(Request $request)
{
    $itemlist=DB::table('item_master')->where('item_code','=',$request->item_code)->first();

    $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$request->item_code."')-
    (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$request->item_code."')) as Stock"));
 
 
    return response()->json(['item_code'=> $itemlist->item_code ,'item_description' => $itemlist->item_description, 'unit_id' => $itemlist->unit_id, 'stock'=>$stock[0]->Stock]); 
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
          ->where('type','=','TRIMOUTWARD')
           ->where('firm_id','=',1)
          ->first();
          
        $TrNo=$codefetch->code.''.$codefetch->tr_no;
        
     if($request->out_type_id!=4 && $request->out_type_id!=7 )
     { 
        if($request->vw_code != "")
        {
            $sales_order_Data = DB::table('vendor_work_order_master')
            ->select('sales_order_no')->where('delflag',0)->where('vw_code',$request->vw_code)
            ->first(); 
        }
        else if($request->vpo_code != "")
        {
             $sales_order_Data = DB::table('vendor_purchase_order_master')
            ->select('sales_order_no')->where('delflag',0)->where('vpo_code',$request->vpo_code)
            ->first(); 
        } 
        
        $bom_Data = DB::table('bom_master')->select('bom_code')->where('sales_order_no','=',$sales_order_Data->sales_order_no)->first();
        $bom_code = $bom_Data->bom_code;
        $sales_order_no = $sales_order_Data->sales_order_no;
        
     }
     elseif($request->out_type_id==4)
     {
         
        $bom_code = 'BOM-177';
        $sales_order_no = 'SKDPL-1';
       
     }
     else
     {
        $bom_code = '';
        $sales_order_no = '';
     }
        
        //print_R($sales_order_Data);exit;
      
   
        $data = array('trimOutCode'=>$TrNo,
            "tout_date"=> $request->input('trimDate'), 
             "out_type_id"=> $request->input('out_type_id'),
            "vendorId"=> $request->input('vendorId'),
            "trim_type"=> $request->input('trim_type'),
            "vpo_code"=> $request->input('vpo_code'),
            "vw_code"=> $request->input('vw_code'),
            "sample_indent_code"=> $request->input('sample_indent_code'),
            "mainstyle_id"=> $request->input('mainstyle_id'),
            "substyle_id"=> $request->input('substyle_id'),
            "fg_id"=> $request->input('fg_id'),
            "style_no"=> $request->input('style_no'),
            "style_description"=> $request->input('style_description'),
            "total_qty"=> $request->input('totalqty'),
            "c_code"=> $codefetch->c_code,
            "userId"=> $request->input('userId'),
            "ship_to"=> $request->ship_to,
            "delflag"=>0
        );
        
        // Insert
        $value = TrimsOutwardMasterModel::insert($data);
        
    
        if($value)
        {
            Session::flash('message','Insert successfully.');
        }
        else
        {
            Session::flash('message','Username already exists.');
        }
        $update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='TRIMOUTWARD' AND firm_id=1");  
        $itemcodes=count($request->item_codes);
        for($x=0;$x<$itemcodes; $x++) 
        {
            if($request->input('out_type_id') == 7)
            {
                $data2=array(
                
                    'trimOutCode' =>$TrNo,
                    'tout_date' => $request->input('trimDate'),
                    "out_type_id"=> $request->input('out_type_id'),
                    'vendorId' => $request->input('vendorId'),
                    "vpo_code"=> $request->input('vpo_code'),
                    "vw_code"=> $request->input('vw_code'),
                    "ship_to"=> $request->ship_to,
                    "sample_indent_code"=> $request->input('sample_indent_code'),
                    'po_code' => $request->po_code[$x],
                    "trim_type"=> $request->trim_type_id[$x],
                    'item_code' => $request->item_codes[$x],
                    'hsn_code' => $request->hsn_code[$x],
                    'unit_id' => $request->unit_id[$x],
                    'item_qty' => $request->item_qtys[$x],
                    'item_rate' => $request->item_rate[$x]
                );
                 
                TrimsOutwardDetailModel::insert($data2);
            }
            else
            {
                $data2=array(
                
                    'trimOutCode' =>$TrNo,
                    'tout_date' => $request->input('trimDate'),
                    "out_type_id"=> $request->input('out_type_id'),
                    'vendorId' => $request->input('vendorId'),
                    "trim_type"=> $request->input('trim_type'),
                    "vpo_code"=> $request->input('vpo_code'),
                    "vw_code"=> $request->input('vw_code'),
                    "ship_to"=> $request->ship_to,
                    'po_code' => $request->po_code[$x],
                    'item_code' => $request->item_codes[$x],
                    'hsn_code' => $request->hsn_code[$x],
                    'unit_id' => $request->unit_id[$x],
                    'item_qty' => $request->item_qtys[$x],
                    'item_rate' => $request->item_rate[$x]
                );
                 
                TrimsOutwardDetailModel::insert($data2);
            }
            
        
            $purchaseData = DB::table('purchase_order')->where('pur_code', $request->po_code[$x])->first();
        
            $buyer_id = isset($purchaseData->buyer_id) ? $purchaseData->buyer_id : 50;   
    
            DB::select("update inward_master set buyer_id='".$buyer_id."' where po_code ='".$request->po_code[$x]."'"); 
        
            $item_code = isset($request->item_codes[$x]) ? $request->item_codes[$x] : 0;
            $item_qty = isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0;
            $po_code = isset($request->po_code[$x]) ? $request->po_code[$x] : ""; 
          
            $purchaseData = DB::table('purchase_order')->where('pur_code', $request->po_code[$x])->first();
     
            $buyer_id = isset($purchaseData->buyer_id) ? $purchaseData->buyer_id : 50;    
         
            DB::select("update trimsOutwardDetail set buyer_id='".$buyer_id."' where po_code ='".$request->po_code[$x]."'"); 
                   
            $item_code = $request->item_codes[$x];
            $po_code = $request->po_code[$x];
            $trim_date = $request->input('trimDate');
            $item_qty = $request->item_qtys[$x];
            
            $existingData = DB::table('trimsOutwardDetail')->select('tout_date','item_qty')->where('po_code', '=', $po_code)->where('item_code', '=', $item_code)->get();
            $updated_string = '';
            $totalOutQty = 0;
            foreach($existingData as $outwards)
            {
                $updated_string .=  $outwards->tout_date.'=>'.$outwards->item_qty.","; 
                $totalOutQty += $outwards->item_qty;
            }
            DB::table('dump_trim_stock_data')
                    ->where('item_code', '=', $item_code)
                    ->where('po_no', '=', $po_code)
                    ->update([
                        'tout_date' =>  $trim_date,
                        'outward_qty' => $totalOutQty,
                        'ind_outward_qty' => '',
                    ]);
            DB::table('dump_trim_stock_data')
                ->where('item_code', '=', $item_code) 
                ->where('po_no', '=', $po_code)
                ->update([
                    'tout_date' =>  $trim_date,
                    'outward_qty' => $totalOutQty,
                    'ind_outward_qty' => $updated_string
            ]);
            
            DB::select("DELETE FROM  dump_trim_stock_data WHERE po_no = '".$po_code."' AND item_code = '".$item_code."' AND item_code NOT IN (SELECT item_code FROM trimsOutwardDetail WHERE  po_code = '".$po_code."' AND item_code = '".$item_code."')");
      
            if($request->po_code[$x] != "")
            {
                $data3 = array(
                    "po_code"=> $request->po_code[$x],  
                    "po_date"=> $request->input('trimDate'),
                    "tr_code"=> $TrNo,  
                    "tr_date"=> $request->input('trimDate'),
                    'bom_code'=> $bom_code,
                    'sales_order_no'=>$sales_order_no,
                    'cat_id'=>0,
                    'class_id'=>0,
                    "item_code"=> $request->item_codes[$x],
                    'unit_id' => $request->unit_id[$x],
                    'qty' => $request->item_qtys[$x],
                    "tr_type"=> 2,
                );
                 StockAssociationModel::insert($data3);
            }
            
            $outwardData = DB::SELECT("SELECT sum(trimsOutwardDetail.item_qty) as trimOutwardStock FROM trimsOutwardDetail INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                        WHERE trimsOutwardDetail.po_code = '".$request->po_code[$x]."' AND trimsOutwardDetail.item_code='".$request->item_codes[$x]."' AND sales_order_no = '".$sales_order_no."'");
                                        
            $trimOutwardStock = isset($outwardData[0]->trimOutwardStock) ? $outwardData[0]->trimOutwardStock : 0;
            $total_outward = ($trimOutwardStock);
            $tempData = DB::table("dump_trims_stock_association")->where('po_code','=',$request->po_code[$x])->where('item_code','=',$request->item_codes[$x])->where('sales_order_no','=',$sales_order_no)->get();
            if(count($tempData) == 0)
            {
                $trimsAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                            FROM stock_association as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  sta.item_code = ".$request->item_codes[$x]." 
                            AND sta.po_code='".$request->po_code[$x]."' AND sta.sales_order_no='".$sales_order_no."' GROUP BY sta.item_code");
                              
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
            }
            else
            {
                DB::table('dump_trims_stock_association')
                        ->where('po_code', '=', $request->po_code[$x])
                        ->where('bom_code', '=', $bom_code)
                        ->where('item_code', '=', $request->item_codes[$x])
                        ->where('sales_order_no', '=', $sales_order_no)
                        ->update(['trimOutwardStock' => $total_outward]);
            }
                    
        }
        return redirect()->route('TrimsOutward.index')->with('message', 'Add Record Succesfully');
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($trimOutCode)
    {
        
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '52')
        ->first();
        
        
        
         //   DB::enableQueryLog();
        $datafetch = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
         ->where('trimOutwardMaster.delflag','=', '0')
           ->where('trimOutwardMaster.trimOutCode',  $trimOutCode)
         ->get(['trimOutwardMaster.*','usermaster.username','ledger_master.Ac_name']);
    
    //   DB::enableQueryLog(); // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('printTrimOutward', compact('datafetch'));   
        
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
      //$POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
       
          
        $purchasefetch = TrimsOutwardMasterModel::find($id);
        
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->where('ledger_master.ac_code','=', $purchasefetch->vendorId)->get();
       
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  DB::table('shade_master')->get();
        $OutTypeList = DB::table('outward_type_master')->where('outward_type_master.delflag','=', '0')->whereIN('outward_type_master.out_type_id', [3,4,5,7])->get();
        
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->where('main_style_master.mainstyle_id','=', $purchasefetch->mainstyle_id)->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->where('sub_style_master.substyle_id','=', $purchasefetch->substyle_id)->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->where('fg_master.fg_id','=', $purchasefetch->fg_id)->get();
        $vendorcodeList = DB::table('vendor_work_order_master')->select('vw_code','sales_order_no')->where('vendor_work_order_master.delflag','=', '0')->where('vw_code','=', $purchasefetch->vw_code)->get();
        $itemlist = ItemModel::where('item_master.delflag','=', '0')->get();
        $unitlist = DB::table('unit_master')->get();
        
        //DB::enableQueryLog();
        $detailpurchase = TrimsOutwardDetailModel::where('trimOutCode','=', $purchasefetch->trimOutCode) 
                            -> leftJoin('item_master', 'item_master.item_code', '=', 'trimsOutwardDetail.item_code')
                            ->groupBy('trimsOutwardDetail.item_code','trimsOutwardDetail.po_code')
                            ->get(['trimsOutwardDetail.*','item_master.item_description','item_master.item_name',DB::raw("sum(item_qty) as total_qty")]);
      // dd(DB::getQueryLog());   
        
        $vendorProcessList = DB::table('vendor_purchase_order_master')->select('vpo_code','sales_order_no')->where('vendor_purchase_order_master.vendorId','=', $purchasefetch->vendorId)->get();
  
        $SINCodeList = DB::SELECT("SELECT sample_indent_code FROM sample_indent_master");
        
        
        $ledgerDetails = DB::table('ledger_details')->where('ac_code','=', $purchasefetch->vendorId)->get();
        
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('TrimsOutwardEdit',compact('purchasefetch','vendorProcessList' ,'ledgerDetails', 'ShadeList','Ledger','CPList','MainStyleList','SubStyleList','FGList','ItemList','detailpurchase','vendorcodeList','itemlist','unitlist','OutTypeList','SINCodeList'));
  
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code,TrimsInOutActivityLog $loggerDetail,TrimsInOutMasterActivityLog $loggerMaster)
    {
     
     
        $data = array('trimOutCode'=>$request->input('trimOutCode'),
            "tout_date"=> $request->input('trimDate'),  
            "out_type_id"=> $request->input('out_type_id'),
            "vendorId"=> $request->input('vendorId'),
            "trim_type"=> $request->input('trim_type'),
            "vpo_code"=> $request->input('vpo_code'),
            "vw_code"=> $request->input('vw_code'),
            "sample_indent_code"=> $request->input('sample_indent_code'),
            "mainstyle_id"=> $request->input('mainstyle_id'),
            "substyle_id"=> $request->input('substyle_id'),
            "fg_id"=> $request->input('fg_id'),
            "style_no"=> $request->input('style_no'),
            "style_description"=> $request->input('style_description'),
            "total_qty"=> $request->input('totalqty'),
            "c_code"=> $request->input('c_code'),
            "userId"=> $request->input('userId'),
            "ship_to"=> $request->ship_to,
            "delflag"=>0
        );
     
     
     
     
      
                     $MasterOldFetch = DB::table('trimOutwardMaster')
                    ->select('tout_date','total_qty')  
                    ->where('trimOutCode',$request->trimOutCode)
                    ->first();
        
             $MasterOld = (array) $MasterOldFetch;
        
             $MasterNew=['tout_date'=>$request->trimDate,'total_qty'=>$request->totalqty];

          
               try {
            $loggerMaster->logIfChangedTrimsInOutMaster(
            'trimOutwardMaster',
            $request->trimOutCode,
            $MasterOld,
            $MasterNew,
            'UPDATE',
            $request->trimDate,
            'TRIMS_OUTWARD'
            );
            // Log::info('Logger called successfully for trimOutwardMaster.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for trimOutwardMaster.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'trimOutCode' =>  $request->trimOutCode,
            'data' => $MasterNew
            ]);
            }  
     
     
     
        // Insert
        $purchase = TrimsOutwardMasterModel::findOrFail($pur_code);  
        $purchase->fill($data)->save();
    
        // if($request->vw_code != "")
        // {
        //     $sales_order_Data = DB::table('vendor_work_order_master')
        //     ->select('sales_order_no')->where('delflag',0)->where('vw_code',$request->vw_code)
        //     ->first(); 
        // }
        // else if($request->vpo_code != "")
        // {
        //      $sales_order_Data = DB::table('vendor_purchase_order_master')
        //     ->select('sales_order_no')->where('delflag',0)->where('vpo_code',$request->vpo_code)
        //     ->first(); 
        // } 
        
        // //print_R($sales_order_Data);exit;
        // $bom_Data = DB::table('bom_master')->select('bom_code')->where('sales_order_no','=',$sales_order_Data->sales_order_no)->first();
         
        // $bom_code = $bom_Data->bom_code;
        // $sales_order_no = $sales_order_Data->sales_order_no;
   
   
     if($request->out_type_id!=4 || $request->out_type_id!=7)
     { 
        if($request->vw_code != "")
        {
            $sales_order_Data = DB::table('vendor_work_order_master')
            ->select('sales_order_no')->where('delflag',0)->where('vw_code',$request->vw_code)
            ->first(); 
            
            $bom_Data = DB::table('bom_master')->select('bom_code')->where('sales_order_no','=',$sales_order_Data->sales_order_no)->first();
            $bom_code = $bom_Data->bom_code;
            $sales_order_no = $sales_order_Data->sales_order_no;
        }
        else if($request->vpo_code != "")
        {
             $sales_order_Data = DB::table('vendor_purchase_order_master')
            ->select('sales_order_no')->where('delflag',0)->where('vpo_code',$request->vpo_code)
            ->first(); 
            
            $bom_Data = DB::table('bom_master')->select('bom_code')->where('sales_order_no','=',$sales_order_Data->sales_order_no)->first();
            $bom_code = $bom_Data->bom_code;
            $sales_order_no = $sales_order_Data->sales_order_no;
        } 
        else
        {
            $bom_code = '';
            $sales_order_no = '';
        }
        
        
     }
     elseif($request->out_type_id==4)
     {
         
        $bom_code = 'BOM-177';
        $sales_order_no = 'SKDPL-1';
       
     }
     elseif($request->out_type_id==7)
     {
         
        $bom_code = '';
        $sales_order_no = '';
       
     }
    
             $olddata1 = DB::table('trimsOutwardDetail')
            ->select('item_code','item_qty')  
            ->where('trimOutCode',$request->input('trimOutCode'))
            ->get()
            ->map(function ($item) {
            return (array) $item;
            })
            ->toArray();
            
            $combinedOldData = $olddata1;
            
            
    
    
        DB::table('trimsOutwardDetail')->where('trimOutCode', $request->input('trimOutCode'))->delete();
        DB::table('stock_association')->where('tr_code', $request->input('trimOutCode'))->delete();
    
        $itemcodes=count($request->item_codes);
    
        for($x=0;$x<$itemcodes;$x++) 
        {
            
            if($request->out_type_id == 7)
            {
                $data2=array(
                
                    'trimOutCode' =>$request->input('trimOutCode'),
                    'tout_date' => $request->input('trimDate'),
                    "out_type_id"=> $request->out_type_id,
                    "ship_to"=> $request->ship_to,
                    'vendorId' => $request->input('vendorId'),
                    "vpo_code"=> $request->input('vpo_code'),
                    "vw_code"=> $request->input('vw_code'),
                    "sample_indent_code"=> $request->input('sample_indent_code'),
                    'po_code' => $request->po_code[$x],
                    "trim_type"=> $request->trim_type_id[$x],
                    'item_code' => $request->item_codes[$x],
                    'hsn_code' => isset($request->hsn_code[$x]) ? $request->hsn_code[$x] : "",
                    'unit_id' => $request->unit_id[$x],
                    'item_qty' => $request->item_qtys[$x],
                    'item_rate' => $request->item_rate[$x]
                );
                 
                TrimsOutwardDetailModel::insert($data2);
            }
            else
            {
                $data2=array(
                
                    'trimOutCode' =>$request->input('trimOutCode'),
                    'tout_date' => $request->input('trimDate'),
                    "out_type_id"=> $request->input('out_type_id'),
                    'vendorId' => $request->input('vendorId'),
                    "ship_to"=> $request->ship_to,
                    "trim_type"=> $request->input('trim_type'),
                    "vpo_code"=> $request->input('vpo_code'),
                    "vw_code"=> $request->input('vw_code'),
                    'po_code' => $request->po_code[$x],
                    'item_code' => $request->item_codes[$x],
                    'hsn_code' => isset($request->hsn_code[$x]) ? $request->hsn_code[$x] : "",
                    'unit_id' => $request->unit_id[$x],
                    'item_qty' => $request->item_qtys[$x],
                    'item_rate' => $request->item_rate[$x]
                );
                 
                TrimsOutwardDetailModel::insert($data2);
            }
            
            
            $purchaseData = DB::table('purchase_order')->where('pur_code', $request->po_code[$x])->first();
     
            $buyer_id = isset($purchaseData->buyer_id) ? $purchaseData->buyer_id : 50;    
         
            DB::select("update trimsOutwardDetail set buyer_id='".$buyer_id."' where po_code ='".$request->po_code[$x]."'"); 
             
            $item_code = $request->item_codes[$x];
            $po_code = $request->po_code[$x];
            $trim_date = $request->input('trimDate');
            $item_qty = $request->item_qtys[$x];
            $new_entry = $trim_date . '=>' . $item_qty;
            
                                    
            // $existingData = DB::table('dump_trim_stock_data')
            //                 ->where('item_code', '=', $item_code)
            //                 ->where('po_no', '=', $po_code)
            //                 ->first();
     
            // if ($existingData) 
            // {  
               
            //     // Check if both tout_date and item_qty mismatch
            //     if ($existingData->tout_date !== $trim_date || $existingData->ind_outward_qty != $item_qty) 
            //     { 
            //         // Split the existing data into an array
            //         $existingEntries = explode(',', $existingData->ind_outward_qty);
           
            //         // Check if the new entry already exists in the array
            //         if (!in_array($new_entry, $existingEntries)) 
            //         {
            //             // If the new entry does not exist, add it to the array
            //             $existingEntries[] = $new_entry;
            
            //             // Join the array back into a comma-separated string
            //             $updatedData = implode(',', $existingEntries);

            //             // Update the database with the new value
            //             DB::table('dump_trim_stock_data')
            //                 ->where('item_code', '=', $item_code)
            //                 ->where('po_no', '=', $po_code)
            //                 ->update([
            //                     'tout_date' => $trim_date,
            //                     'outward_qty' => $item_qty,
            //                     'ind_outward_qty' => $updatedData,
            //                 ]);
                           
            //         }
            //         else
            //         {
                      
            //                 DB::table('dump_trim_stock_data')
            //                 ->where('item_code', '=', $item_code)
            //                 ->where('po_no', '=', $po_code)
            //                 ->update([
            //                     'tout_date' => $trim_date,
            //                     'outward_qty' => $item_qty,
            //                     'ind_outward_qty' => DB::raw("REPLACE(ind_outward_qty, '$new_entry', '$new_entry')")
            //                 ]);
                  
            //         }
            //     }
            // } 
            // else 
            // { 
                
            //     DB::table('dump_trim_stock_data')
            //     ->where('item_code', '=', $item_code) 
            //     ->where('po_no', '=', $po_code)
            //     ->update([
            //         'tout_date' =>  $trim_date,
            //         'outward_qty' => $item_qty,
            //         'ind_outward_qty' => $new_entry
            //     ]);
                 
            // }

                $existingData = DB::table('trimsOutwardDetail')->select('tout_date','item_qty')->where('po_code', '=', $po_code)->where('item_code', '=', $item_code)->get();
                $updated_string = '';
                $totalOutQty = 0;
                foreach($existingData as $outwards)
                {
                    $updated_string .=  $outwards->tout_date.'=>'.$outwards->item_qty.","; 
                    $totalOutQty += $outwards->item_qty;
                }
                DB::table('dump_trim_stock_data')
                        ->where('item_code', '=', $item_code)
                        ->where('po_no', '=', $po_code)
                        ->update([
                            'tout_date' =>  $trim_date,
                            'outward_qty' => $totalOutQty,
                            'ind_outward_qty' => '',
                        ]);
                DB::table('dump_trim_stock_data')
                    ->where('item_code', '=', $item_code) 
                    ->where('po_no', '=', $po_code)
                    ->update([
                        'tout_date' =>  $trim_date,
                        'outward_qty' => $totalOutQty,
                        'ind_outward_qty' => $updated_string
                ]);
                
                DB::select("DELETE FROM  dump_trim_stock_data WHERE po_no = '".$po_code."' AND item_code = '".$item_code."' AND item_code NOT IN (SELECT item_code FROM trimsOutwardDetail WHERE  po_code = '".$po_code."' AND item_code = '".$item_code."')");
            
            if($request->po_code[$x] != "")
            {
                $data3 = array(
                    "po_code"=> $request->po_code[$x],  
                    "po_date"=> $request->input('trimDate'),
                    "tr_code"=> $request->input('trimOutCode'), 
                    "tr_date"=> $request->input('trimDate'),
                    'bom_code'=> $bom_code,
                    'sales_order_no'=>$sales_order_no,
                    'cat_id'=>0,
                    'class_id'=>0,
                    "item_code"=> $request->item_codes[$x],
                    'unit_id' => $request->unit_id[$x],
                    'qty' => $request->item_qtys[$x],
                    "tr_type"=> 2,
                );
                 StockAssociationModel::insert($data3);
            }
            
            $outwardData = DB::SELECT("SELECT sum(trimsOutwardDetail.item_qty) as trimOutwardStock FROM trimsOutwardDetail INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                                        WHERE trimsOutwardDetail.po_code = '".$request->po_code[$x]."' AND trimsOutwardDetail.item_code='".$request->item_codes[$x]."' AND sales_order_no = '".$sales_order_no."'");
                                        
            $trimOutwardStock = isset($outwardData[0]->trimOutwardStock) ? $outwardData[0]->trimOutwardStock : 0;
            $total_outward = ($trimOutwardStock);
            $tempData = DB::table("dump_trims_stock_association")->where('po_code','=',$request->po_code[$x])->where('item_code','=',$request->item_codes[$x])->where('sales_order_no','=',$sales_order_no)->get();
            if(count($tempData) == 0)
            {
                 $trimsAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                            FROM stock_association as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  sta.item_code = ".$request->item_codes[$x]." 
                            AND sta.po_code='".$request->po_code[$x]."' AND sta.sales_order_no='".$sales_order_no."' GROUP BY sta.item_code");
                  // dd(DB::getQueryLog());                 
                foreach($trimsAssocData as $row)
                { 
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                    
        
                    $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                   
                    $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
                    
                    $trimsOutwardData1 = DB::select("select sum(trimsOutwardDetail.item_qty) as qty FROM trimsOutwardDetail INNER JOIN  vendor_work_order_master ON  vendor_work_order_master.vw_code =  trimsOutwardDetail.vw_code
                                                WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND vendor_work_order_master.sales_order_no = '".$row->sales_order_no."' AND trimsOutwardDetail.item_code = ".$row->item_code);
                                                
                    $trimsOutwardData2 = DB::select("select sum(trimsOutwardDetail.item_qty) as qty  FROM trimsOutwardDetail INNER JOIN  vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  trimsOutwardDetail.vpo_code
                                                WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND trimsOutwardDetail.item_code = ".$row->item_code);
                                                
                    $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0;
                    $trimsOutwardStock1 = isset($trimsOutwardData1[0]->qty) ? $trimsOutwardData1[0]->qty : 0;
                    $trimsOutwardStock2 = isset($trimsOutwardData2[0]->qty) ? $trimsOutwardData2[0]->qty : 0;
                    $trimsOutwardStock = $trimsOutwardStock1 + $trimsOutwardStock2;
                 
                    $eachData = DB::SELECT("SELECT ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                    
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
            }
            else
            {
                DB::table('dump_trims_stock_association')
                        ->where('po_code', '=', $request->po_code[$x])
                        ->where('bom_code', '=', $bom_code)
                        ->where('item_code', '=', $request->item_codes[$x])
                        ->where('sales_order_no', '=', $sales_order_no)
                        ->update(['trimOutwardStock' => $total_outward]);
            }
            
            
            
                                $newDataDetail2[]=[
                                'item_code' => $request->item_codes[$x],
                                'item_qty' => $request->item_qtys[$x]
                                ];    
            
            
                    
        }
        
              $combinedNewData = $newDataDetail2;       
           
            try {
            $loggerDetail->logIfChangedTrimsInOutDetail(
            'trimOutwardMaster',
            $request->trimOutCode,
            $combinedOldData,
            $combinedNewData,
            'UPDATE',
            $request->input('trimDate'),
            'TRIMS_OUTWARD'
            );
            // Log::info('Logger called successfully for trimOutwardMaster.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for trimOutwardMaster.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'trCode' => $request->trimOutCode,
            'data' => $combinedNewData
            ]);
            }  
        
        
        
        
        return redirect()->route('TrimsOutward.index')->with('message', 'Update Record Succesfully');

     }


    // public function TrimsOutwardData()
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();
        
        
    //     $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        
        
    //     //  DB::enableQueryLog();
    //     $TrimsOutwardDetails = TrimsOutwardDetailModel::
    //         leftJoin('trimOutwardMaster', 'trimOutwardMaster.trimOutCode', '=', 'trimsOutwardDetail.trimOutCode')
    //      -> leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimOutwardMaster.vendorId')
    //       ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsOutwardDetail.item_code')
    //         ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
    //       ->get(['trimsOutwardDetail.*',   'ledger_master.ac_name','item_master.dimension','quality_master.quality_name', 'item_master.item_name','item_master.color_name','item_master.item_description' ]);
    // //   $query = DB::getQueryLog();
    // //      $query = end($query);
    // //       dd($query);
    //     return view('TrimsOutwardData',compact('TrimsOutwardDetails'));
    // }
    
    public function TrimsOutwardData(Request $request)
    {
        $fromDate =  isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate =  isset($request->toDate) ? $request->toDate : date("Y-m-d");
            
        if ($request->ajax()) 
        { 
           // DB::enableQueryLog();
            //     $TrimsOutwardDetails = TrimsOutwardDetailModel::join('trimOutwardMaster', 'trimOutwardMaster.trimOutCode', '=', 'trimsOutwardDetail.trimOutCode')
            //   ->join('ledger_master', 'ledger_master.ac_code', '=', 'trimOutwardMaster.vendorId')
            //   ->join('item_master', 'item_master.item_code', '=', 'trimsOutwardDetail.item_code')
            //   ->join('trimsInwardDetail', function ($join) {
            //       $join->on('trimsOutwardDetail.item_code', '=', 'trimsInwardDetail.item_code');
            //       $join->on('trimsOutwardDetail.po_code', '=', 'trimsInwardDetail.po_code');    })
            //   ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
            //   ->where('item_master.cat_id','!=',4)
            //   ->groupby('trimsOutwardDetail.trimOutCode')
            //   ->groupby('trimsOutwardDetail.po_code')
            // //   ->groupby('trimsOutwardDetail.item_code')
            //   ->get(['trimsOutwardDetail.*','trimsInwardDetail.item_rate as item_inward_rate',DB::raw('sum(trimsOutwardDetail.item_qty) as outward_qty'), DB::raw('ifnull(ledger_master.ac_name,"") as ac_name'),'item_master.dimension','quality_master.quality_name', 'item_master.item_name','item_master.color_name','item_master.item_description' ]);
        // DB::enableQueryLog();
  
            // $TrimsOutwardDetails = DB:: SELECT('SELECT  trimsOutwardDetail.*,item_master.dimension,quality_master.quality_name, 
            //                         item_master.item_name,item_master.color_name,(SELECT trimsInwardDetail.item_rate FROM trimsInwardDetail WHERE trimsInwardDetail.po_code = trimsOutwardDetail.po_code 
            //                         AND trimsOutwardDetail.item_code = trimsOutwardDetail.item_code) as item_inward_rate,ifnull(sum(trimsOutwardDetail.item_qty),0)  as out_qty,ifnull(ledger_master.ac_name,"") as ac_name,
            //                         item_master.item_description  FROM trimsOutwardDetail 
            //                         INNER JOIN trimOutwardMaster ON trimOutwardMaster.trimOutCode=trimsOutwardDetail.trimOutCode
            //                         INNER JOIN ledger_master ON ledger_master.ac_code = trimOutwardMaster.vendorId
            //                         INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
            //                         LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code 
            //                         WHERE item_master.cat_id != 4  GROUP BY trimsOutwardDetail.trimOutCode,trimsOutwardDetail.po_code,trimsOutwardDetail.item_code');
              
        $TrimsOutwardDetails = DB:: SELECT('SELECT DISTINCT trimsOutwardDetail.*,trimsOutwardDetail.sample_indent_code, trimsInwardDetail.item_rate as item_inward_rate,item_master.dimension,quality_master.quality_name, 
                          item_master.item_name,item_master.color_name,ifnull(sum(DISTINCT trimsOutwardDetail.item_qty),0)  as out_qty,ifnull(ledger_master.ac_name,"") as ac_name,LM1.ac_short_name as buyer,
                          (SELECT trade_name FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to OR ledger_details.ac_code = trimsInwardDetail.Ac_code LIMIT 1) as trade_name,
                          (SELECT site_code FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to OR ledger_details.ac_code = trimsInwardDetail.Ac_code LIMIT 1) as site_code,
                          (SELECT ledger_master.ac_name FROM ledger_master WHERE ledger_master.ac_code = purchase_order.Ac_code OR ledger_master.ac_code = trimsInwardDetail.Ac_code LIMIT 1) as supplier,
                          item_master.item_description FROM trimsOutwardDetail 
                          LEFT JOIN trimOutwardMaster ON trimOutwardMaster.trimOutCode=trimsOutwardDetail.trimOutCode
                          LEFT JOIN ledger_master ON ledger_master.ac_code = trimOutwardMaster.vendorId
                          LEFT JOIN ledger_master as LM1  ON LM1.ac_code = trimsOutwardDetail.buyer_id
                          LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
                          LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code 
                          LEFT JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code AND trimsInwardDetail.item_code = trimsOutwardDetail.item_code
                          LEFT JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code 
                          AND trimsInwardDetail.item_code = trimsOutwardDetail.item_code
                          WHERE trimsOutwardDetail.tout_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"
                          GROUP BY trimsOutwardDetail.trimOutCode,trimsOutwardDetail.po_code,trimsOutwardDetail.item_code');
                                    
            //   $TrimsOutwardDetails = TrimsOutwardDetailModel::join('trimOutwardMaster', 'trimOutwardMaster.trimOutCode', '=', 'trimsOutwardDetail.trimOutCode')
            //   ->join('ledger_master', 'ledger_master.ac_code', '=', 'trimOutwardMaster.vendorId')
            //   ->join('item_master', 'item_master.item_code', '=', 'trimsOutwardDetail.item_code') 
            //   ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
            //   ->join('trimsInwardDetail', 'trimsInwardDetail.po_code', '=', 'trimsOutwardDetail.po_code') 
            //   ->distinct('trimsOutwardDetail.po_code')
            //   ->groupby('trimsOutwardDetail.trimOutCode')
            // //   ->groupby('trimsOutwardDetail.po_code')
            // //   ->groupby('trimsOutwardDetail.item_code')
            //   ->get(['trimsOutwardDetail.*','trimsInwardDetail.item_rate as item_inward_rate',DB::raw('sum(trimsOutwardDetail.item_qty) as outward_qty'), DB::raw('ifnull(ledger_master.ac_name,"") as ac_name'),'item_master.dimension','quality_master.quality_name', 'item_master.item_name','item_master.color_name','item_master.item_description' ]);
           //  dd(DB::getQueryLog());
          
          
          
          // dd(DB::getQueryLog());
            return Datatables::of($TrimsOutwardDetails)
            
            ->addColumn('typeName',function ($row) 
            {
                if($row->trim_type==1)
                { 
                    // $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
                    // where vw_code='".$row->vw_code."'");
                    
                    $typeName = 'Sewing Trims';
                }
                elseif($row->trim_type==2)
                {
                    // $VWList=DB::select("select distinct vendor_purchase_order_master.sales_order_no  as sales_order_no, ledger_master.ac_name  from vendor_purchase_order_master inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
                    // where vpo_code='".$row->vpo_code."'");
                    
                    $typeName = 'Packing Trims';
                }
                else
                {
                    $typeName = '';
                }
                return $typeName;
            })
            ->addColumn('sales_order_no',function ($row) 
            {
                if($row->trim_type==1)
                { 
                    $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
                    where vw_code='".$row->vw_code."'");
                    
                    $sales_order_no = isset($VWList[0]->sales_order_no) ? $VWList[0]->sales_order_no : "-";
                }
                elseif($row->trim_type==2)
                {
                    $VWList=DB::select("select distinct vendor_purchase_order_master.sales_order_no  as sales_order_no, ledger_master.ac_name  from vendor_purchase_order_master inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
                    where vpo_code='".$row->vpo_code."'");
                    
                    $sales_order_no = isset($VWList[0]->sales_order_no) ? $VWList[0]->sales_order_no : "-";
                }
                else
                {
                    $sales_order_no = '';
                }
                return $sales_order_no;
            })
            ->addColumn('vw_ac_name',function ($row) 
            {
                // $vw_ac_name = '';
                // if($row->trim_type!=0)
                // {  
                //     $VWList=DB::select("select ledger_master.ac_short_name from purchase_order inner join ledger_master on ledger_master.ac_code=purchase_order.buyer_id
                //     where purchase_order.pur_code='".$row->po_code."'");
                //     $vw_ac_name = isset($VWList[0]->ac_short_name) ? $VWList[0]->ac_short_name : "-";
                    
                // }
                // elseif($row->trim_type==0)
                // {
                //     $VWList=DB::select("select distinct  ledger_master.ac_name from sample_indent_master inner join ledger_master on ledger_master.ac_code=sample_indent_master.Ac_code
                //                     where sample_indent_code='".$row->sample_indent_code."'");
                //     $vw_ac_name = isset($VWList[0]->ac_name) ? $VWList[0]->ac_name :'';
                // } 
                // else
                // {
                //     $VWList=DB::select("select ledger_master.ac_short_name from trimsOutwardDetail inner join ledger_master on ledger_master.ac_code=trimsOutwardDetail.buyer_id
                //     where trimsOutwardDetail.po_code='".$row->po_code."'");
                //     $vw_ac_name = isset($VWList[0]->ac_short_name) ? $VWList[0]->ac_short_name : "-";
                // }
                 
                    
                $vw_ac_name = $row->buyer;
                    
                return $vw_ac_name;
            })
            // ->addColumn('out_qty',function ($row) 
            // {
            //     //  $outData = DB::select("SELECT sum(item_qty) as out_qty FROM trimsOutwardDetail WHERE  trimOutCode='".$row->trimOutCode."'");
            //     //  $out_qty = isset($outData[0]->out_qty) ? $outData[0]->out_qty: 0;
            //      return 0;
            // })
            ->addColumn('item_inward_rate',function ($row) 
            { 
                $item_inward_rate =  number_format(round($row->item_inward_rate, 2), 2, '.', ',');
                return $item_inward_rate; 
            })
            ->addColumn('out_qty',function ($row) 
            { 
                $out_qty =  number_format(round($row->out_qty, 2), 2, '.', ',');
                return $out_qty; 
            })
            ->addColumn('item_value',function ($row) 
            {
            //   $itemData = DB::SELECT("SELECT trimsInwardDetail.item_rate FROM trimsOutwardDetail
            //          INNER JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code 
            //          AND trimsInwardDetail.item_code = trimsOutwardDetail.item_code
            //          WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND trimsOutwardDetail.item_code='".$row->item_code."'");
                      
            //     $item_inward_rate  = isset($itemData[0]->item_rate) ? $itemData[0]->item_rate: 0; 
                $item_value =  number_format(round($row->out_qty * $row->item_inward_rate, 2), 2, '.', ',');
                return $item_value; 
            })
            // ->addColumn('item_inward_rate',function ($row) 
            // {
            //      $itemData = DB::SELECT("SELECT trimsInwardDetail.item_rate FROM trimsOutwardDetail
            //          INNER JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code 
            //          AND trimsInwardDetail.item_code = trimsOutwardDetail.item_code
            //          WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND trimsOutwardDetail.item_code='".$row->item_code."'");
                      
            //     $item_inward_rate  = isset($itemData[0]->item_rate) ? $itemData[0]->item_rate: 0; 
            //     // $item_value =  ($row->out_qty * $item_inward_rate);
                
            //     return $item_inward_rate;
            // })
            
            ->addColumn('tout_date',function ($row) 
            { 
                $tout_date = date("d-M-Y", strtotime($row->tout_date));
                return $tout_date;
            })
            ->addColumn('supplier',function ($row) 
            { 
                $supplier =  $row->supplier;
                return $supplier; 
            })
            ->addColumn('bill_name',function ($row) 
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

            ->rawColumns(['typeName','sales_order_no','vw_ac_name','item_value','item_inward_rate','out_qty','tout_date','supplier','bill_name'])
             
            ->make(true);
    
        }
        return view('TrimsOutwardData');
        
    }
    
    // public function TrimsOutwardDataMD($DFilter)
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();
        
        
    //     $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        
                
    //     if($DFilter == 'd')
    //     {
    //         $filterDate = " AND trimsOutwardDetail.tout_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    //     }
    //     else if($DFilter == 'm')
    //     {
    //         $filterDate = ' AND MONTH(trimsOutwardDetail.tout_date) = MONTH(CURRENT_DATE()) and YEAR(trimsOutwardDetail.tout_date)=YEAR(CURRENT_DATE())';
    //     }
    //     else if($DFilter == 'y')
    //     {
    //         $filterDate = ' AND trimsOutwardDetail.tout_date between (select fdate from financial_year_master 
    //                         where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
    //     }
    //     else
    //     {
    //         $filterDate = "";
    //     }
        
    //     //  DB::enableQueryLog();
   
    //       $TrimsOutwardDetails = DB::select("SELECT trimsOutwardDetail.*,ledger_master.ac_name, item_master.dimension,quality_master.quality_name, 
    //         item_master.item_name,item_master.color_name,item_master.item_description
    //         FROM trimsOutwardDetail LEFT JOIN trimOutwardMaster ON trimOutwardMaster.trimOutCode = trimsOutwardDetail.trimOutCode
    //         LEFT JOIN ledger_master ON ledger_master.ac_code = trimOutwardMaster.vendorId
    //         LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //         LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code WHERE 1 ".$filterDate);
    // //   $query = DB::getQueryLog();
    // //      $query = end($query);
    // //       dd($query);
    //     return view('TrimsOutwardData',compact('TrimsOutwardDetails'));
    // }


    public function TrimsOutwardDataMD(Request $request,$DFilter)
    { 
        if ($request->ajax()) 
        { 
           
            if($DFilter == 'd')
            {
                $filterDate = " AND trimsOutwardDetail.tout_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            }
            else if($DFilter == 'm')
            {
                $filterDate = ' AND MONTH(trimsOutwardDetail.tout_date) = MONTH(CURRENT_DATE()) and YEAR(trimsOutwardDetail.tout_date)=YEAR(CURRENT_DATE())';
            }
            else if($DFilter == 'y')
            {
                $filterDate = ' AND trimsOutwardDetail.tout_date between (select fdate from financial_year_master 
                                where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
            }
            else
            {
                $filterDate = "";
            }
        
           $TrimsOutwardDetails = DB::select("SELECT trimsOutwardDetail.*,trimsOutwardDetail.item_rate,ledger_master.ac_name, item_master.dimension,quality_master.quality_name, 
                item_master.item_name,item_master.color_name,item_master.item_description
                FROM trimsOutwardDetail LEFT JOIN trimOutwardMaster ON trimOutwardMaster.trimOutCode = trimsOutwardDetail.trimOutCode
                LEFT JOIN ledger_master ON ledger_master.ac_code = trimOutwardMaster.vendorId
                LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
                LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code WHERE 1 ".$filterDate);
          
            //dd(DB::getQueryLog());
            return Datatables::of($TrimsOutwardDetails)
            
            ->addColumn('typeName',function ($row) 
            {
                if($row->trim_type==1)
                { 
                    $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
                    where vw_code='".$row->vw_code."'");
                    
                    $typeName = 'Sewing Trims';
                }
                else if($row->trim_type==2)
                {
                    $VWList=DB::select("select distinct vendor_purchase_order_master.sales_order_no  as sales_order_no, ledger_master.ac_name  from vendor_purchase_order_master inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
                    where vpo_code='".$row->vpo_code."'");
                    
                    $typeName = 'Packing Trims';
                }
                else
                {
                    $typeName = '';
                }
                return $typeName;
            })
            ->addColumn('sales_order_no',function ($row) 
            {
                if($row->trim_type==1)
                { 
                    $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
                    where vw_code='".$row->vw_code."'");
                    
                    $sales_order_no = isset($VWList[0]->sales_order_no) ? $VWList[0]->sales_order_no : "-";
                }
                else if($row->trim_type==2)
                {
                    $VWList=DB::select("select distinct vendor_purchase_order_master.sales_order_no  as sales_order_no, ledger_master.ac_name  from vendor_purchase_order_master inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
                    where vpo_code='".$row->vpo_code."'");
                    
                    $sales_order_no = isset($VWList[0]->sales_order_no) ? $VWList[0]->sales_order_no : "-";
                }
                else
                {
                    $sales_order_no = '';
                }
                return $sales_order_no;
            })
            ->addColumn('vw_ac_name',function ($row) 
            {
                if($row->trim_type==1)
                { 
                    $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
                    where vw_code='".$row->vw_code."'");
                    
                    $vw_ac_name = isset($VWList[0]->ac_name) ? $VWList[0]->ac_name : "-";
                }
                else if($row->trim_type==2)
                {
                    $VWList=DB::select("select distinct vendor_purchase_order_master.sales_order_no  as sales_order_no, ledger_master.ac_name  from vendor_purchase_order_master inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
                    where vpo_code='".$row->vpo_code."'");
                    
                    $vw_ac_name = isset($VWList[0]->ac_name) ? $VWList[0]->ac_name : "-";
                }
                else
                {
                    $vw_ac_name = '';
                }
                return $vw_ac_name;
            })
            ->addColumn('item_value',function ($row) 
            {
                 $item_value = money_format('%!i',round($row->item_qty * $row->item_rate));
                 return $item_value;
            })
             ->rawColumns(['typeName','sales_order_no','vw_ac_name','item_value'])
             
             ->make(true);
    
            }
            
          return view('TrimsOutwardData');
        
    }
    

    public function TrimOutwardStandardPrint($trimCode)
    {
        
         $FirmDetail =  DB::table('firm_master')->first();
         $TrimsOutwardMaster = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
    
         ->where('trimOutwardMaster.trimOutCode', $trimCode)
         ->get(['trimOutwardMaster.*','usermaster.username','ledger_master.Ac_name', 'ledger_master.state_id','ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address']);
         return view('TrimsOutwardStandardPrint', compact('TrimsOutwardMaster','FirmDetail'));
      
    }

    public function TrimOutwardStandardPrint2($trimCode)
    {
        
         $FirmDetail =  DB::table('firm_master')->first();
         $TrimsOutwardMaster = TrimsOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimOutwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimOutwardMaster.vendorId')
    
         ->where('trimOutwardMaster.trimOutCode', $trimCode)
         ->get(['trimOutwardMaster.*','usermaster.username','ledger_master.Ac_name', 'ledger_master.state_id','ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address']);
         return view('TrimsOutwardStandardPrint2', compact('TrimsOutwardMaster','FirmDetail'));
      
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrimsOutwardMasterModel  $trimsOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($pur_code)
    {
        
            $pur_codes=$pur_code;
            
            $outwardData = DB::SELECT("SELECT 
                    sum(item_qty) as item_qty,
                    trimsOutwardDetail.*,
                    CASE 
                        WHEN trimsOutwardDetail.vpo_code IS NULL THEN vendor_work_order_master.sales_order_no
                        ELSE vendor_purchase_order_master.sales_order_no 
                    END AS sales_order_no
                FROM 
                    trimsOutwardDetail 
                LEFT JOIN 
                    vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code 
                LEFT JOIN 
                    vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                WHERE 
                    trimsOutwardDetail.trimOutCode = '".$pur_code."'");
            
             
            //DB::SELECT("UPDATE dump_trim_stock_data SET outward_qty='0',ind_outward_qty='' WHERE po_no = '".$outwardData[0]->po_code."' AND item_code = ".$outwardData[0]->item_code);
            
            // Fetch the existing comma-separated string
            $outwardData1 = DB::SELECT("SELECT ind_outward_qty FROM dump_trim_stock_data WHERE po_no = '".$outwardData[0]->po_code."' AND item_code = ".$outwardData[0]->item_code);
            
            // Assuming outward_qty contains the string like '2024-10-10=>20,2024-10-11=>25,2024-10-05=>5'
            $qtyString = isset($outwardData1[0]->ind_outward_qty) ? $outwardData1[0]->ind_outward_qty : '';
            
            $item_qty = isset($outwardData[0]->item_qty) ? $outwardData[0]->item_qty : 0;
            // Define the quantity part to remove (e.g., '=>20')
            $quantityToRemove = '=>'.$item_qty;
            
            // Remove the entry that ends with the specific quantity
            $modifiedQtyString = implode(',', array_filter(explode(',', $qtyString), function($item) use ($quantityToRemove) {
                // Return false if the item ends with '=>20', to remove it
                return substr(trim($item), -strlen($quantityToRemove)) !== $quantityToRemove;
            }));
            
            // Update the modified string back into the database
            DB::UPDATE("UPDATE dump_trim_stock_data SET outward_qty='0', ind_outward_qty = '".$modifiedQtyString."' WHERE po_no = '".$outwardData[0]->po_code."' AND item_code = ".$outwardData[0]->item_code);

            $trimsOutwardStock=0;
       
            $trimsOutwardData = DB::SELECT("SELECT sum(trimOutwardStock) as outward_qty FROM dump_trims_stock_association  
                                            WHERE  po_code='".$outwardData[0]->po_code."' AND  item_code='".$outwardData[0]->item_code."' AND sales_order_no='".$outwardData[0]->sales_order_no."'"); 
  
            $outward_qty = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
            
            $trimsOutwardStock = $outward_qty - $item_qty;
            
            DB::table('dump_trims_stock_association')
                    ->where('po_code', '=', $outwardData[0]->po_code)
                    ->where('item_code', '=', $outwardData[0]->item_code)
                    ->where('sales_order_no', '=', $outwardData[0]->sales_order_no)
                    ->update(['trimOutwardStock' => $trimsOutwardStock]);
            //dd(DB::getQueryLog());    
            $master =TrimsOutwardMasterModel::where('trimOutCode',$pur_code)->delete();      
            $detail =TrimsOutwardDetailModel::where('trimOutCode',$pur_code)->delete();
            $stockData = StockAssociationModel::where('tr_code',$pur_code)->delete();
               
      
            Session::flash('delete', 'Deleted record successfully');     
    }
    
    public function GetSINCodeForTrimOutwardList(Request $request)
    { 
        
        $masterData = DB::SELECT("SELECT sample_indent_code FROM sample_indent_master");

        $html = '<option value="">--Select--</option>';
        foreach($masterData as $row)
        {
            $html .='<option value="'.$row->sample_indent_code.'">'.$row->sample_indent_code.'</option>';
        }
        
        return response()->json(['html' => $html]);
    }
   
    public function GetSINCodeWiseSampleData(Request $request)
    { 
        //DB::enableQueryLog();
        $sewingData = DB::SELECT("SELECT sample_indent_sewing_trims.*,item_master.item_name,item_master.unit_id,item_master.item_description,
                                item_master.item_description,item_master.hsn_code,item_master.item_rate, sum(sewing_trims_qty) as total_qty FROM sample_indent_sewing_trims 
                                INNER JOIN item_master ON item_master.item_code = sample_indent_sewing_trims.sewing_trims_item_code  
                                WHERE sample_indent_code='".$request->sample_indent_code."' GROUP BY sample_indent_sewing_trims.sewing_trims_item_code");
         //   dd(DB::getQueryLog());                       
        $packingData = DB::SELECT("SELECT sample_indent_packing_trims.*,item_master.item_name,item_master.unit_id,item_master.item_description,
                                item_master.item_description,item_master.hsn_code,item_master.item_rate, sum(packing_trims_qty) as total_qty FROM sample_indent_packing_trims 
                                INNER JOIN item_master ON item_master.item_code = sample_indent_packing_trims.packing_trims_item_code  
                                WHERE sample_indent_code='".$request->sample_indent_code."' GROUP BY sample_indent_packing_trims.packing_trims_item_code");
     
        $unitlist=DB::table('unit_master')->get();
    
        $html = '';
        
        $html .= '<div class="table-responsive">
                    <div class="col-md-12"><h4><strong>Sewing</strong></h4></div>
                    <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                              <th>PO NO</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Description</th> 
                              <th>Unit</th>
                              <th>Associated Stock</th>
                              <th>Order Qty</th>
                              <th>Stock</th>
                              <th>Actual Stock</th>
                              <th>Quantity</th> 
                           </tr>
                        </thead>
                        <tbody>';
                            $srno = 1;
                            foreach($sewingData as $sewing)
                            {
                                
                                $POList = DB::select(DB::raw("
                                            SELECT 
                                                tn.po_code,
                                                ledger_master.ac_name,
                                                rack_master.rack_name,
                                                COALESCE(inward.total_inward_qty, 0) - COALESCE(outward.total_outward_qty, 0) AS stock
                                            FROM 
                                                trimsInwardDetail AS tn
                                            LEFT JOIN 
                                                (SELECT 
                                                    item_code, 
                                                    po_code, 
                                                    SUM(item_qty) AS total_inward_qty 
                                                FROM 
                                                    trimsInwardDetail 
                                                GROUP BY 
                                                    item_code, po_code
                                                ) AS inward 
                                            ON 
                                                tn.item_code = inward.item_code 
                                                AND tn.po_code = inward.po_code
                                            LEFT JOIN 
                                                (SELECT 
                                                    item_code, 
                                                    po_code, 
                                                    SUM(item_qty) AS total_outward_qty 
                                                FROM 
                                                    trimsOutwardDetail 
                                                GROUP BY 
                                                    item_code, po_code
                                                ) AS outward 
                                            ON 
                                                tn.item_code = outward.item_code 
                                                AND tn.po_code = outward.po_code
                                            LEFT JOIN 
                                                ledger_master 
                                            ON 
                                                ledger_master.ac_code = tn.ac_code
                                            LEFT JOIN 
                                                rack_master 
                                            ON 
                                                rack_master.rack_id = tn.rack_id
                                            WHERE 
                                                tn.item_code = '".$sewing->sewing_trims_item_code."'
                                            GROUP BY 
                                                tn.ac_code, tn.po_code, tn.item_code, ledger_master.ac_name, rack_master.rack_name
                                        "));
                                        
                                $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$sewing->sewing_trims_item_code."')-
                                                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$sewing->sewing_trims_item_code."')
                                                ) as Stock"));
                                                 
                               
                                $StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
                                            where item_code='".$sewing->sewing_trims_item_code."' and sample_indent_code='".$sewing->sample_indent_code."'"));

                                $assoc_stock = 0;
                                $POList1 = DB::SELECT("
                                                SELECT po_code 
                                                FROM stock_association 
                                                WHERE tr_type = 1 
                                                    AND item_code = ".$sewing->sewing_trims_item_code." 
                                                    AND sales_order_no = '".$sewing->sample_indent_code."' 
                                                GROUP BY po_code
                                                HAVING SUM(qty) = (
                                                    SELECT MAX(total_qty) 
                                                    FROM (
                                                        SELECT SUM(qty) as total_qty 
                                                        FROM stock_association 
                                                        WHERE tr_type = 1 
                                                            AND item_code = ".$sewing->sewing_trims_item_code." 
                                                            AND sales_order_no = '".$sewing->sample_indent_code."' 
                                                        GROUP BY po_code
                                                    ) as subquery
                                                )
                                            ");
                    
                                
                                $selectPO_Code =  isset($POList1[0]->po_code) ? $POList1[0]->po_code : ''; 
                                
                                $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                                    FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                                    WHERE sta.sales_order_no='".$sewing->sample_indent_code."' AND sta.po_code='".$selectPO_Code."' AND sta.item_code='".$sewing->sewing_trims_item_code."' GROUP BY sta.sales_order_no,sta.item_code");
                                  
                                $assoc_stock = 0;
                                $remainStock=0;
                                 
                                foreach ($data1 as $row) 
                                {
                                    if($row->po_type_id == 2 || $row->is_opening ==1)
                                    { 
                                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                                    }
                                    else
                                    {     
                                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                                    }
                                    $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                                    
                                  
                                   if($row->cat_id == 2)
                                   {
                                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                   }
                                   else
                                   { 
                                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."'
                                                            AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                   }
                                   $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
                                   
                                   // DB::enableQueryLog();
                                   if($row->po_type_id == 2 || $row->is_opening ==1)
                                   {
                                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
                                   }
                                   else
                                   {
                                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' 
                                        AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                                   }
                                    //dd(DB::getQueryLog());
                                    $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                                   
                                    $remainStock = $allocated_qty - $eachAvaliableQty;
                                    
                                       
                                   $assoc_stock += ($remainStock - $trimsOutwardStock);
                               
                                }
                                
                                if($assoc_stock == 0 )
                                {
                                    //DB::enableQueryLog();
                                    $POList2 = DB::SELECT("
                                            SELECT po_code 
                                            FROM stock_association 
                                            WHERE tr_type = 1 
                                                AND item_code = ".$sewing->sewing_trims_item_code." 
                                                AND sales_order_no = '".$sewing->sales_order_no."' 
                                                AND po_code != '".$selectPO_Code."'
                                            GROUP BY po_code
                                            HAVING SUM(qty) = (
                                                SELECT MAX(total_qty) 
                                                FROM (
                                                    SELECT SUM(qty) as total_qty 
                                                    FROM stock_association 
                                                    WHERE tr_type = 1 
                                                        AND item_code = ".$sewing->sewing_trims_item_code." 
                                                        AND sales_order_no = '".$sewing->sales_order_no."' 
                                                        AND po_code != '".$selectPO_Code."'
                                                    GROUP BY po_code
                                                ) as subquery
                                            )
                                        ");
                    
                                    //dd(DB::getQueryLog());         
                                    $selectPO_Code =  isset($POList2[0]->po_code) ? $POList2[0]->po_code : '';  
                                
                                    $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                                        FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                                        WHERE sta.sales_order_no='".$sewing->sales_order_no."' AND sta.po_code='".$selectPO_Code."' AND sta.item_code='".$sewing->sewing_trims_item_code."' GROUP BY sta.sales_order_no,sta.item_code");
                                      
                                    $assoc_stock = 0;
                                    $remainStock=0;
                                    foreach ($data1 as $row) 
                                    {
                                        if($row->po_type_id == 2 || $row->is_opening ==1)
                                        { 
                                            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                                        }
                                        else
                                        {     
                                            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' 
                                                                            AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                                        }
                                        $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                                        
                                      
                                       if($row->cat_id == 2)
                                       {
                                           $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                                WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                       }
                                       else
                                       { 
                                           $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                                WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                       }
                                       $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
                                       
                                       // DB::enableQueryLog();
                                       if($row->po_type_id == 2 || $row->is_opening ==1)
                                       {
                                            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
                                       }
                                       else
                                       {
                                            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                                       }
                                        //dd(DB::getQueryLog());
                                        $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                                       
                                        $remainStock = $allocated_qty - $eachAvaliableQty;
                                        
                                           
                                       $assoc_stock += ($remainStock - $trimsOutwardStock);
                                   
                                    }
                                }
                                
                                $stockData = DB::SELECT("SELECT (SELECT IFNULL(SUM(item_qty),0) FROM `trimsInwardDetail` WHERE po_code = '".$selectPO_Code."' AND item_code = '".$sewing->sewing_trims_item_code."') 
                                                - (SELECT IFNULL(SUM(item_qty),0) FROM `trimsOutwardDetail` WHERE po_code = '".$selectPO_Code."' AND item_code = '".$sewing->sewing_trims_item_code."') as stock");
                                     //   dd(DB::getQueryLog());  
                                $stock_qty = isset($stockData[0]->stock) ? $stockData[0]->stock : 0;
                                    

                                $html .= '<tr class="tr_clone">
                                  <td><input type="text" name="id[]" value="'.($srno++).'" id="id" style="width:50px;"/></td>
                                  <td>
                                     <select name="po_code[]" class="select2" id="po_code" style="width:250px; height:30px;" onchange="GetTrimsItemList(this);getAssociatedStockSample(this);" >
                                        <option value="">--PO NO--</option>';
                                        foreach($POList as  $rowpo)
                                        { 
                                            if($rowpo->stock > 0)
                                            {
                                                $html .= '<option value="'.$rowpo->po_code.'"';
                                                
                                                $rowpo->po_code == $selectPO_Code ? $html.='selected="selected"' : '';

                                                $html .= '>'.$rowpo->po_code.'</option>';
                                            }
                                        }
                                     $html .= '</select>
                                  </td> 
                                  <td> 
                                     '.$sewing->sewing_trims_item_code.' 
                                  </td>
                                  <td>
                                      <select name="item_codes[]"  id="item_codes" style="width:250px;height:30px;" required disabled>
                                            <option value="'.$sewing->sewing_trims_item_code.'">'.$sewing->item_name.'</option>
                                       </select> 
                                  </td>
                                  <td>'.$sewing->item_description.'</td> 
                                  <td>
                                     <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
                                        <option value="">--Select Unit--</option>';
                                        foreach($unitlist as  $rowunit)
                                        {
                                            $selected = '';
                                            
                                            if($rowunit->unit_id == $sewing->unit_id)
                                            {
                                                $selected = 'selected';
                                            }
                                            $html .= '<option value="'.$rowunit->unit_id.'" '.$selected.'>'.$rowunit->unit_name.'</option>';
                                        }
                                     $html .= '</select>
                                  </td>
                                   <td><input type="text" class="assoc_qty" value="'.$assoc_stock.'" style="width:80px;" readOnly/></td>
                                   <td><input type="text" class="order_qty"  value="'.(round($sewing->total_qty - $StockOut[0]->StockOut)).'" style="width:80px;" readOnly/></td>';
                                    $html.='<td><input type="text" class="stock"  value="'.$stock_qty.'" style="width:80px;" readOnly/></td>';
                                    
                                    $assoc_stock = isset($assoc_stock) ? round($assoc_stock) : PHP_INT_MAX;
                                    $totalQty = isset($sewing->total_qty) ? $sewing->total_qty : 0;
                                    $stockOut = isset($StockOut[0]->StockOut) ? $StockOut[0]->StockOut : 0;
                                    $stockQty = isset($stock_qty) ? round($stock_qty) : PHP_INT_MAX;
                                    
                                    $minItemQty = min($assoc_stock, round($totalQty - $stockOut), $stockQty);
                                   //$minItemQty = $totalQty;
                                    $html.='<td><input type="text" class="actual_stock_qty" onclick="stockPopup(this);" value="'.(round($stock[0]->Stock)).'" style="width:80px;" readOnly/></td>
                                    <td>
                                        <input type="number" step="any" class="QTY" name="item_qtys[]" value="'.$minItemQty.'" id="item_qty" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);"  />
                                        <input type="hidden"   name="item_rate[]"   value="'.$sewing->item_rate.'" id="item_rate" style="width:80px;height:30px;" />
                                        <input type="hidden" name="trim_type_id[]" value="1" id="trim_type_id" />
                                  </td>
                               </tr>';
                            }
                         $html .= '</tbody>
                     </table>
                  </div>';
        
        $html1 = '';
        
        $html1 .= '<div class="table-responsive">
                    <div class="col-md-12"><h4><strong>Packing</strong></h4></div>
                    <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                              <th>PO NO</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Description</th> 
                              <th>Unit</th>
                              <th>Associated Stock</th>
                              <th>Order Qty</th>
                              <th>Stock</th>
                              <th>Actual Stock</th>
                              <th>Quantity</th> 
                           </tr>
                        </thead>
                        <tbody>';
                        
                            $srno1 = 1;
                            foreach($packingData as $packing)
                            { 
                                $POList = DB::select(DB::raw("
                                            SELECT 
                                                tn.po_code,
                                                ledger_master.ac_name,
                                                rack_master.rack_name,
                                                COALESCE(inward.total_inward_qty, 0) - COALESCE(outward.total_outward_qty, 0) AS stock
                                            FROM 
                                                trimsInwardDetail AS tn
                                            LEFT JOIN 
                                                (SELECT 
                                                    item_code, 
                                                    po_code, 
                                                    SUM(item_qty) AS total_inward_qty 
                                                FROM 
                                                    trimsInwardDetail 
                                                GROUP BY 
                                                    item_code, po_code
                                                ) AS inward 
                                            ON 
                                                tn.item_code = inward.item_code 
                                                AND tn.po_code = inward.po_code
                                            LEFT JOIN 
                                                (SELECT 
                                                    item_code, 
                                                    po_code, 
                                                    SUM(item_qty) AS total_outward_qty 
                                                FROM 
                                                    trimsOutwardDetail 
                                                GROUP BY 
                                                    item_code, po_code
                                                ) AS outward 
                                            ON 
                                                tn.item_code = outward.item_code 
                                                AND tn.po_code = outward.po_code
                                            LEFT JOIN 
                                                ledger_master 
                                            ON 
                                                ledger_master.ac_code = tn.ac_code
                                            LEFT JOIN 
                                                rack_master 
                                            ON 
                                                rack_master.rack_id = tn.rack_id
                                            WHERE 
                                                tn.item_code = '".$packing->packing_trims_item_code."'
                                            GROUP BY 
                                                tn.ac_code, tn.po_code, tn.item_code, ledger_master.ac_name, rack_master.rack_name
                                        ")); 

                                 //   DB::enableQueryLog(); 
                                $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$packing->packing_trims_item_code."')-
                                                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$packing->packing_trims_item_code."')
                                                ) as Stock"));
                                  
                                $StockOut=DB::select(DB::raw("select ifnull(sum(item_qty),0) as StockOut from trimsOutwardDetail 
                                            where item_code='".$packing->packing_trims_item_code."' and sample_indent_code='".$packing->sample_indent_code."'"));
                                            
                                $assoc_stock = 0;
                                
                                $POList1 = DB::SELECT("
                                                SELECT po_code 
                                                FROM stock_association 
                                                WHERE tr_type = 1 
                                                    AND item_code = ".$packing->packing_trims_item_code." 
                                                    AND sales_order_no = '".$packing->sample_indent_code."' 
                                                GROUP BY po_code
                                                HAVING SUM(qty) = (
                                                    SELECT MAX(total_qty) 
                                                    FROM (
                                                        SELECT SUM(qty) as total_qty 
                                                        FROM stock_association 
                                                        WHERE tr_type = 1 
                                                            AND item_code = ".$packing->packing_trims_item_code." 
                                                            AND sales_order_no = '".$packing->sample_indent_code."' 
                                                        GROUP BY po_code
                                                    ) as subquery
                                                )
                                            ");
                    
                                
                                $selectPO_Code1 =  isset($POList1[0]->po_code) ? $POList1[0]->po_code : '';  

                                $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                                    FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code  LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                                    WHERE sta.sales_order_no='".$packing->sample_indent_code."' AND sta.po_code='".$selectPO_Code1."' AND sta.item_code='".$packing->packing_trims_item_code."' GROUP BY sta.sales_order_no,sta.item_code");
                                  
                                $assoc_stock = 0;
                                $remainStock=0;
                                 
                                foreach ($data1 as $row) 
                                {
                                    if($row->po_type_id == 2 || $row->is_opening ==1)
                                    { 
                                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                                    }
                                    else
                                    {     
                                        $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                                    }
                                    $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                                    
                                  
                                   if($row->cat_id == 2)
                                   {
                                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                   }
                                   else
                                   { 
                                       $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                            WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."'
                                                            AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                   }
                                   $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
                                   
                                   // DB::enableQueryLog();
                                   if($row->po_type_id == 2 || $row->is_opening ==1)
                                   {
                                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
                                   }
                                   else
                                   {
                                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' 
                                        AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                                   }
                                    //dd(DB::getQueryLog());
                                    $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                                   
                                    $remainStock = $allocated_qty - $eachAvaliableQty;
                                    
                                       
                                   $assoc_stock += ($remainStock - $trimsOutwardStock);
                               
                                }
                                
                                if($assoc_stock == 0 )
                                {
                                    //DB::enableQueryLog();
                                    $POList2 = DB::SELECT("
                                            SELECT po_code 
                                            FROM stock_association 
                                            WHERE tr_type = 1 
                                                AND item_code = ".$packing->packing_trims_item_code." 
                                                AND sales_order_no = '".$packing->sample_indent_code."' 
                                                AND po_code != '".$selectPO_Code."'
                                            GROUP BY po_code
                                            HAVING SUM(qty) = (
                                                SELECT MAX(total_qty) 
                                                FROM (
                                                    SELECT SUM(qty) as total_qty 
                                                    FROM stock_association 
                                                    WHERE tr_type = 1 
                                                        AND item_code = ".$packing->packing_trims_item_code." 
                                                        AND sales_order_no = '".$packing->sample_indent_code."' 
                                                        AND po_code != '".$selectPO_Code."'
                                                    GROUP BY po_code
                                                ) as subquery
                                            )
                                        ");
                    
                                    //dd(DB::getQueryLog());         
                                    $selectPO_Code1 =  isset($POList2[0]->po_code) ? $POList2[0]->po_code : '';  
                                
                                    $data1=DB::select("SELECT item_master.*,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,trimsInwardMaster.is_opening,trimsInwardMaster.po_type_id
                                        FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = sta.tr_code 
                                        WHERE sta.sales_order_no='".$packing->sample_indent_code."' AND sta.po_code='".$selectPO_Code1."' AND sta.item_code='".$packing->packing_trims_item_code."' GROUP BY sta.sales_order_no,sta.item_code");
                                      
                                    $assoc_stock = 0;
                                    $remainStock=0;
                                     
                                    foreach ($data1 as $row) 
                                    {
                                        if($row->po_type_id == 2 || $row->is_opening ==1)
                                        { 
                                            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type=1");
                                        }
                                        else
                                        {     
                                            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' 
                                                                            AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                                        }
                                        $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                                        
                                      
                                       if($row->cat_id == 2)
                                       {
                                           $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                                WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                       }
                                       else
                                       { 
                                           $trimsOutwardData = DB::SELECT("SELECT  ifnull(sum(trimsOutwardDetail.item_qty),0) as outward_qty FROM trimsOutwardDetail
                                                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code 
                                                                WHERE trimsOutwardDetail.po_code='".$row->po_code."' AND  trimsOutwardDetail.item_code='".$row->item_code."' AND vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'"); 
                                       }
                                       $trimsOutwardStock = isset($trimsOutwardData[0]->outward_qty) ? $trimsOutwardData[0]->outward_qty : 0;
                                       
                                       // DB::enableQueryLog();
                                       if($row->po_type_id == 2 || $row->is_opening ==1)
                                       {
                                            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."'  AND item_code='".$row->item_code."'  AND tr_type = 2  AND tr_code IS NULL"); 
                                       }
                                       else
                                       {
                                            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                                       }
                                        //dd(DB::getQueryLog());
                                        $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                                       
                                        $remainStock = $allocated_qty - $eachAvaliableQty;
                                        
                                           
                                       $assoc_stock += ($remainStock - $trimsOutwardStock);
                                   
                                    }
                                }

                                
                                $stockData = DB::SELECT("SELECT (SELECT IFNULL(SUM(item_qty),0) FROM `trimsInwardDetail` WHERE po_code = '".$selectPO_Code1."' AND item_code = '".$packing->packing_trims_item_code."') 
                                                - (SELECT IFNULL(SUM(item_qty),0) FROM `trimsOutwardDetail` WHERE po_code = '".$selectPO_Code1."' AND item_code = '".$packing->packing_trims_item_code."') as stock");
                                     //   dd(DB::getQueryLog());  
                                $stock_qty = isset($stockData[0]->stock) ? $stockData[0]->stock : 0;
                                    
                                   
                                $html1 .= '<tr class="tr_clone">
                                  <td><input type="text" name="id[]" value="'.($srno1++).'" id="id" style="width:50px;"/></td>
                                  <td>
                                     <select name="po_code[]" class="select2" id="po_code" style="width:250px; height:30px;" onchange="GetTrimsItemList(this);getAssociatedStockSample(this);" >
                                        <option value="">--PO NO--</option>';
                                        foreach($POList as  $rowpo)
                                        {
                                            if($rowpo->stock > 0)
                                            {
                                              $html1 .= '<option value="'.$rowpo->po_code.'"';
                                              $rowpo->po_code == $selectPO_Code1 ? $html1.='selected="selected"' : '';
                                              $html1 .= '>'.$rowpo->po_code.'</option>';
                                            }
                                        }
                                     $html1 .= '</select>
                                  </td>
                                  <td> 
                                     '.$packing->packing_trims_item_code.' 
                                  </td>
                                  <td>
                                      <select name="item_codes[]"  id="item_codes" style="width:250px;height:30px;" required disabled>
                                            <option value="'.$packing->packing_trims_item_code.'">'.$packing->item_name.'</option>
                                       </select> 
                                  </td>
                                  <td>'.$packing->item_description.'</td> 
                                  <td>
                                     <select name="unit_id[]"  id="unit_id" style="width:100px;height:30px;" required disabled>
                                        <option value="">--Select Unit--</option>';
                                        foreach($unitlist as  $rowunit)
                                        {
                                            $selected = '';
                                            
                                            if($rowunit->unit_id == $packing->unit_id)
                                            {
                                                $selected = 'selected';
                                            }
                                            $html1 .= '<option value="'.$rowunit->unit_id.'" '.$selected.'>'.$rowunit->unit_name.'</option>';
                                        }
                                     $html1 .= '</select>
                                  </td>
                                   <td><input type="text" class="assoc_qty" value="'.$assoc_stock.'" style="width:80px;" readOnly/></td>
                                   <td><input type="text" class="order_qty"  value="'.(round($packing->total_qty - $StockOut[0]->StockOut)).'" style="width:80px;" readOnly/></td>';
                                    $html1.='<td><input type="text" class="stock"  value="'.$stock_qty.'" style="width:80px;" readOnly/></td>';
                                    
                                    $assoc_stock = isset($assoc_stock) ? round($assoc_stock) : PHP_INT_MAX;
                                    $totalQty = isset($packing->total_qty) ? $packing->total_qty : 0;
                                    $stockOut = isset($StockOut[0]->StockOut) ? $StockOut[0]->StockOut : 0;
                                    $stockQty = isset($stock_qty) ? round($stock_qty) : PHP_INT_MAX;
                                    
                                    $minItemQty = min($assoc_stock, round($totalQty - $stockOut), $stockQty);

                                    
                                    $html1 .= '<td><input type="text" class="actual_stock_qty" onclick="stockPopup(this);" value="'.(round($stock[0]->Stock)).'" style="width:80px;" readOnly/></td>
                                    <td>
                                        <input type="number" step="any" class="QTY" name="item_qtys[]" value="'.$minItemQty.'" id="item_qty" style="width:80px;" onchange="mycalc();qtyCheck(this);setAssocQty(this);"  />
                                        <input type="hidden"   name="item_rate[]"   value="'.$packing->item_rate.'" id="item_rate" style="width:80px;height:30px;" />
                                        <input type="hidden" name="trim_type_id[]" value="2" id="trim_type_id" />
                                  </td> 
                               </tr>';
                            }
                         $html1 .= '</tbody>
                     </table>
                  </div>';
                  
        return response()->json(['html' => $html, 'html1' => $html1]);
    }
   
    public function GetStockDetailPopupForTrims(Request $request)
    { 
        //DB::enableQueryLog();
          $TrimsInwardDetails = DB::select(DB::raw("
            SELECT 
                tn.po_code,
                ledger_master.ac_name,
                COALESCE(inward.total_inward_qty, 0) - COALESCE(outward.total_outward_qty, 0) AS stock
            FROM 
                trimsInwardDetail AS tn
            LEFT JOIN 
                (SELECT 
                    item_code, 
                    po_code, 
                    SUM(item_qty) AS total_inward_qty 
                FROM 
                    trimsInwardDetail 
                GROUP BY 
                    item_code, po_code
                ) AS inward 
            ON 
                tn.item_code = inward.item_code 
                AND tn.po_code = inward.po_code
            LEFT JOIN 
                (SELECT 
                    item_code, 
                    po_code, 
                    SUM(item_qty) AS total_outward_qty 
                FROM 
                    trimsOutwardDetail 
                GROUP BY 
                    item_code, po_code
                ) AS outward 
            ON 
                tn.item_code = outward.item_code 
                AND tn.po_code = outward.po_code
            LEFT JOIN 
                ledger_master 
            ON 
                ledger_master.ac_code = tn.ac_code
            WHERE 
                tn.item_code = '".$request->item_code."'
            GROUP BY 
                tn.ac_code, tn.po_code, tn.item_code, ledger_master.ac_name
        ")); 
        //dd(DB::getQueryLog());
        $html = ''; 
        $html1 = ''; 
        $total = 0;
        foreach($TrimsInwardDetails as $row)
        { 
            if($row->stock > 0)
            {
                $html .='<tr>
                            <td>'.$row->ac_name.'</td>
                            <td>'.$row->po_code.'</td> 
                            <td style="text-align:end;">'.(money_format('%!.2n', sprintf("%.2f", ($row->stock)))).'</td> 
                        </tr>';
                        
                $total += (round($row->stock));
            }
        }
        
        $html1 .='<tr>
                    <th></th>
                    <th>Total</th> 
                    <th style="text-align:end;">'.(money_format('%!.2n', sprintf("%.2f", $total))).'</th> 
                </tr>';
        
        return response()->json(['html' => $html, 'html1' => $html1]);
    }
    
    public function GetPOListFromItemCode(Request $request)
    { 
        
        $Data = DB::SELECT("SELECT po_code,qty FROM stock_association WHERE item_code=".$request->item_code." GROUP BY po_code");

        $html = '<option value="">--Select--</option>';
        foreach($Data as $row)
        {
            if($row->qty > 0)
            {
                $html .='<option value="'.$row->po_code.'">'.$row->po_code.'</option>';
            }
        } 
        return response()->json(['html' => $html]);
    } 
    
    public function getItemPORate(Request $request)
    { 
        $po_code = base64_decode($request->po_code);
      
        $purchaseOrder = DB::select("select item_rate from purchaseorder_detail 
                    where item_code='".$request->item_code."' and pur_code='".$po_code."'");

     
        $item_rate = isset($purchaseOrder[0]->item_rate) ? $purchaseOrder[0]->item_rate : 0; 
        return $item_rate;
    } 
    
    public function TrimOutwardStockQty(Request $request)
    {
        
        $po_code= base64_decode($request->input('po_code'));
         // DB::enableQueryLog();
        $stockData = DB::SELECT("SELECT (SELECT IFNULL(SUM(item_qty),0) FROM `trimsInwardDetail` WHERE po_code = '".$po_code."' AND item_code = '".$request->item_code."') 
                    - (SELECT IFNULL(SUM(item_qty),0) FROM `trimsOutwardDetail` WHERE po_code = '".$po_code."' AND item_code = '".$request->item_code."') as stock");
         //   dd(DB::getQueryLog());  
        $stock_qty = isset($stockData[0]->stock) ? $stockData[0]->stock : 0;
        
        return response()->json(['stock_qty' => $stock_qty]); 
    }
    
    public function TrimsOutwardTrial()
    {
     
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='TRIMOUTWARD'");
      
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $OutTypeList = DB::table('outward_type_master')->where('outward_type_master.delflag','=', '0')->whereIN('outward_type_master.out_type_id', [3,4,5,7])->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $vendorcodeList = DB::table('vendor_work_order_master')->select('vw_code')->where('vendor_work_order_master.delflag','=', '0')->get();
        $itemlist = ItemModel::where('item_master.delflag','=', '0')->get();
        $unitlist = DB::table('unit_master')->get();
        $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
        return view('TrimsOutwardTrial',compact('Ledger','POList','counter_number','MainStyleList','SubStyleList','vendorcodeList','FGList','itemlist','unitlist','OutTypeList'));
     
    }

}
      
