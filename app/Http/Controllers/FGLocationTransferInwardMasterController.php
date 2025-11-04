<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BOMMasterModel;
use App\Models\LedgerModel; 
use App\Models\SeasonModel;
use App\Models\SizeDetailModel;
use App\Models\FinishedGoodModel; 
use App\Models\ClassificationModel;
use App\Models\ItemModel;
use App\Models\UnitModel;  
use App\Models\LocationModel;
use App\Models\MainStyleModel; 
use App\Models\SubStyleModel; 
use Illuminate\Support\Facades\DB; 
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\SalesOrderCostingMasterModel; 
use App\Models\BuyerPurchaseOrderDetailModel;
use App\Models\VendorWorkOrderTrimFabricDetailModel;
use App\Models\LedgerDetailModel; 
use App\Models\FGLocationTransferInwardMasterModel;
use App\Models\FGLocationTransferInwardDetailModel;
use App\Models\FGLocationTransferInwardSizeDetailModel;
use App\Models\LocTransferPackingInhouseMasterModel;
use Session;
use DataTables;
require_once '/home/kenerp/public_html/app/Libraries/TCPDF/tcpdf.php';

use TCPDF; 


class FGLocationTransferInwardMasterController extends Controller
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
        ->where('form_id', '201')
        ->first();
        
         $LocationList = LocationModel::where('location_master.delflag','=', '0')->get();
        
        
        $FGLocationTransferInwardMasterList = FGLocationTransferInwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'fg_location_transfer_inward_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'fg_location_transfer_inward_master.Ac_code', 'left outer')
                    ->join('location_master as Loc1', 'Loc1.loc_id', '=', 'fg_location_transfer_inward_master.from_loc_id', 'left outer')
                    ->join('location_master as Loc2', 'Loc2.loc_id', '=', 'fg_location_transfer_inward_master.to_loc_id', 'left outer')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'fg_location_transfer_inward_master.sales_order_no', 'left outer')
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                    ->where('fg_location_transfer_inward_master.delflag','=', '0')
                    ->orderBy(DB::raw('CAST(SUBSTRING(fg_location_transfer_inward_master.fglti_code, LOCATE("-", fg_location_transfer_inward_master.fglti_code) + 1) AS UNSIGNED)'), 'DESC')
                    ->get(['fg_location_transfer_inward_master.*','usermaster.username','L1.Ac_name', 'Loc1.location as from_location', 'Loc2.location as to_location','brand_master.brand_name' ]);
        
        if ($request->ajax()) 
        {
            return Datatables::of($FGLocationTransferInwardMasterList)
            ->addIndexColumn()
            ->addColumn('srno',function ($row) { 
                static $srno = 1;   
                return $srno++;
            }) 
            ->addColumn('total_value',function ($row) 
            { 
                $FGData = DB::SELECT("SELECT SUM(size_qty * size_rate) as total_value FROM fg_location_transfer_inward_size_detail2 WHERE fglti_code='".$row->fglti_code."'");  
                $total_value = isset($FGData[0]->total_value) ? $FGData[0]->total_value: 0;
                
                return number_format($total_value,2);
            }) 
            ->addColumn('action0', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-warning btn-sm print" target="_blank" href="/FGLocationTransferInwardBarcode/'.$row->fglti_code.'" title="barcode">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            }) 
            ->addColumn('action1', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="FGLocationTransferInwardPrint/'.$row->fglti_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            })
            ->addColumn('action2', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('FGLocationTransferInward.edit', $row->fglti_code).'" >
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
            ->addColumn('action3', function ($row) use ($chekform){
         
                if($chekform->delete_access==1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->fglti_code.'"  data-route="'.route('FGLocationTransferInward.destroy', $row->fglti_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['action0', 'action1','action2','action3', 'srno', 'total_value'])
    
            ->make(true);
        }
        return view('FGLocationTransferInwardMasterList', compact('FGLocationTransferInwardMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FGLocationTransferInward'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get();
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->get();
        $OutwardList = LocTransferPackingInhouseMasterModel::where('loc_transfer_packing_inhouse_master.delflag','=', '0')->get();
        return view('FGLocationTransferInwardMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList','LocationList', 'BuyerPurchaseOrderList','Ledger', 'OutwardList', 'counter_number'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // echo '<pre>'; print_r($_POST);exit;
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
        ->where('c_name','=','C1')
        ->where('type','=','FGLocationTransferInward')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
           
   
    $data1=array
    (
        'fglti_code'=>$TrNo, 
        'fglti_date'=>$request->fglti_date, 
        'ltpki_code'=>$request->ltpki_code, 
        'sales_order_no'=>$request->main_sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'total_qty'=>$request->total_qty,  
        'narration'=>$request->narration,
        'from_loc_id'=>$request->from_loc_id,
        'to_loc_id'=>$request->to_loc_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
     );
 
    FGLocationTransferInwardMasterModel::insert($data1);
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FGLocationTransferInward'");

    $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
        for($x=0; $x<count($color_id); $x++) 
        { 
            if($request->size_qty_total[$x]>0)
            {
                
                      $s1=isset($request->s1[$x]) ? $request->s1[$x] : 0; $s11=isset($request->s11[$x]) ? $request->s11[$x] : 0;
                      $s2=isset($request->s2[$x]) ? $request->s2[$x] : 0; $s12=isset($request->s12[$x]) ? $request->s12[$x] : 0;
                      $s3=isset($request->s3[$x]) ? $request->s3[$x] : 0; $s13=isset($request->s13[$x]) ? $request->s13[$x] : 0;
                      $s4=isset($request->s4[$x]) ? $request->s4[$x] : 0; $s14=isset($request->s14[$x]) ? $request->s14[$x] : 0;
                      $s5=isset($request->s5[$x]) ? $request->s5[$x] : 0; $s15=isset($request->s15[$x]) ? $request->s15[$x] : 0;
                      $s6=isset($request->s6[$x]) ? $request->s6[$x] : 0; $s16=isset($request->s16[$x]) ? $request->s16[$x] : 0;
                      $s7=isset($request->s7[$x]) ? $request->s7[$x] : 0; $s17=isset($request->s17[$x]) ? $request->s17[$x] : 0;
                      $s8=isset($request->s8[$x]) ? $request->s8[$x] : 0; $s18=isset($request->s18[$x]) ? $request->s18[$x] : 0;
                      $s9=isset($request->s9[$x]) ? $request->s9[$x] : 0; $s19=isset($request->s19[$x]) ? $request->s19[$x] : 0;
                      $s10=isset($request->s10[$x]) ? $request->s10[$x] : 0; $s20=isset($request->s20[$x]) ? $request->s20[$x] : 0;
 
                      $s1_rate=isset($request->s1_rate[$x]) ? $request->s1_rate[$x] : 0; $s11_rate=isset($request->s11_rate[$x]) ? $request->s11_rate[$x] : 0;
                      $s2_rate=isset($request->s2_rate[$x]) ? $request->s2_rate[$x] : 0; $s12_rate=isset($request->s12_rate[$x]) ? $request->s12_rate[$x] : 0;
                      $s3_rate=isset($request->s3_rate[$x]) ? $request->s3_rate[$x] : 0; $s13_rate=isset($request->s13_rate[$x]) ? $request->s13_rate[$x] : 0;
                      $s4_rate=isset($request->s4_rate[$x]) ? $request->s4_rate[$x] : 0; $s14_rate=isset($request->s14_rate[$x]) ? $request->s14_rate[$x] : 0;
                      $s5_rate=isset($request->s5_rate[$x]) ? $request->s5_rate[$x] : 0; $s15_rate=isset($request->s15_rate[$x]) ? $request->s15_rate[$x] : 0;
                      $s6_rate=isset($request->s6_rate[$x]) ? $request->s6_rate[$x] : 0; $s16_rate=isset($request->s16_rate[$x]) ? $request->s16_rate[$x] : 0;
                      $s7_rate=isset($request->s7_rate[$x]) ? $request->s7_rate[$x] : 0; $s17_rate=isset($request->s17_rate[$x]) ? $request->s17_rate[$x] : 0;
                      $s8_rate=isset($request->s8_rate[$x]) ? $request->s8_rate[$x] : 0; $s18_rate=isset($request->s18_rate[$x]) ? $request->s18_rate[$x] : 0;
                      $s9_rate=isset($request->s9_rate[$x]) ? $request->s9_rate[$x] : 0; $s19_rate=isset($request->s19_rate[$x]) ? $request->s19_rate[$x] : 0;
                      $s10_rate=isset($request->s10_rate[$x]) ? $request->s10_rate[$x] : 0; $s20_rate=isset($request->s20_rate[$x]) ? $request->s20_rate[$x] : 0;
                      
                      
                      $s1_barcode=isset($request->s1_barcode[$x]) ? $request->s1_barcode[$x] : ''; $s11_barcode=isset($request->s11_barcode[$x]) ? $request->s11_barcode[$x] : '';
                      $s2_barcode=isset($request->s2_barcode[$x]) ? $request->s2_barcode[$x] : ''; $s12_barcode=isset($request->s12_barcode[$x]) ? $request->s12_barcode[$x] : '';
                      $s3_barcode=isset($request->s3_barcode[$x]) ? $request->s3_barcode[$x] : ''; $s13_barcode=isset($request->s13_barcode[$x]) ? $request->s13_barcode[$x] : '';
                      $s4_barcode=isset($request->s4_barcode[$x]) ? $request->s4_barcode[$x] : ''; $s14_barcode=isset($request->s14_barcode[$x]) ? $request->s14_barcode[$x] : '';
                      $s5_barcode=isset($request->s5_barcode[$x]) ? $request->s5_barcode[$x] : ''; $s15_barcode=isset($request->s15_barcode[$x]) ? $request->s15_barcode[$x] : '';
                      $s6_barcode=isset($request->s6_barcode[$x]) ? $request->s6_barcode[$x] : ''; $s16_barcode=isset($request->s16_barcode[$x]) ? $request->s16_barcode[$x] : '';
                      $s7_barcode=isset($request->s7_barcode[$x]) ? $request->s7_barcode[$x] : ''; $s17_barcode=isset($request->s17_barcode[$x]) ? $request->s17_barcode[$x] : '';
                      $s8_barcode=isset($request->s8_barcode[$x]) ? $request->s8_barcode[$x] : ''; $s18_barcode=isset($request->s18_barcode[$x]) ? $request->s18_barcode[$x] : '';
                      $s9_barcode=isset($request->s9_barcode[$x]) ? $request->s9_barcode[$x] : ''; $s19_barcode=isset($request->s19_barcode[$x]) ? $request->s19_barcode[$x] : '';
                      $s10_barcode=isset($request->s10_barcode[$x]) ? $request->s10_barcode[$x] : ''; $s20_barcode=isset($request->s20_barcode[$x]) ? $request->s20_barcode[$x] : '';
                     
                      $sb1 ='';
                      $sb2 = '';
                      $sb3 = '';
                      $sb4 = '';
                      $sb5 = '';
                      $sb6 = '';
                      $sb7 = '';
                      $sb8 = '';
                      $sb9 = '';
                      $sb10 = '';
                      $sb11 = '';
                      $sb12 = '';
                      $sb13 = '';
                      $sb14 = '';
                      $sb15 = '';
                      $sb16 = '';
                      $sb17 = '';
                      $sb18 = '';
                      $sb19 = '';
                      $sb20 = '';
                      
                      if($s1_barcode != '')
                      {
                          $sb1 = $s1_barcode.''.$codefetch->tr_no.','; 
                      } 
                        
                      if($s2_barcode !='')
                      {
                          $sb2 = $s2_barcode.''.$codefetch->tr_no.',';
                      } 
                        
                      if($s3_barcode !='')
                      {
                          $sb3 = $s3_barcode.''.$codefetch->tr_no.',';
                      } 
                        
                      if($s4_barcode !='')
                      {
                          $sb4 = $s4_barcode.''.$codefetch->tr_no.',';
                      } 
                        
                      if($s5_barcode !='')
                      {
                          $sb5 = $s5_barcode.''.$codefetch->tr_no.',';
                      } 
                        
                      if($s6_barcode !='')
                      {
                          $sb6 = $s6_barcode.''.$codefetch->tr_no.',';
                      } 
                      if($s7_barcode !='')
                      {
                          $sb7 = $s7_barcode.''.$codefetch->tr_no.',';
                      }
                       
                      if($s8_barcode !='')
                      {
                          $sb8 = $s8_barcode.''.$codefetch->tr_no.',';
                      }
                       
                      if($s9_barcode !='')
                      {
                          $sb9 = $s9_barcode.''.$codefetch->tr_no.',';
                      }  
                      if($s10_barcode !='')
                      {
                          $sb10 = $s10_barcode.''.$codefetch->tr_no.',';
                      } 
                      if($s11_barcode != '')
                      {
                          $sb11 = $s11_barcode.''.$codefetch->tr_no.',';
                      } 
                      
                      if($s12_barcode != '')
                      {
                          $sb12 = $s12_barcode.''.$codefetch->tr_no.',';
                      } 
                      
                      if($s13_barcode != '')
                      {
                          $sb13 = $s13_barcode.''.$codefetch->tr_no.',';
                      } 
                      if($s14_barcode != '')
                      {
                          $sb14 = $s14_barcode.''.$codefetch->tr_no.',';
                      } 
                      if($s15_barcode != '')
                      {
                          $sb15 = $s15_barcode.''.$codefetch->tr_no.',';
                      } 
                      if($s16_barcode != '')
                      {
                          $sb16 = $s16_barcode.''.$codefetch->tr_no.',';
                      } 
                      if($s17_barcode != '')
                      {
                          $sb17 = $s17_barcode.''.$codefetch->tr_no.',';
                      } 
                      
                      if($s18_barcode != '')
                      {
                          $sb18 = $s18_barcode.''.$codefetch->tr_no.',';
                      } 
                      
                      if($s19_barcode != '')
                      {
                          $sb19 = $s19_barcode.''.$codefetch->tr_no.',';
                      }  
                      
                      if($s20_barcode != '')
                      {
                          $sb20 = $s20_barcode.''.$codefetch->tr_no.',';
                      } 
                    
                    $barcode_array = $sb1.''.$sb2.''.$sb3.''.$sb4.''.$sb5.''.$sb6.''.$sb7.''.$sb8.''.$sb9.''.$sb10.''.$sb11.''.$sb12.''.$sb13.''.$sb14.''.$sb15.''.$sb16.''.$sb17.''.$sb18.''.$sb19.''.$sb20;
                      
                    $data2=array
                    (
    					'fglti_code'=>$TrNo,
                        'fglti_date'=>$request->fglti_date,
                        'ltpki_code'=>$request->ltpki_code, 
    					'sales_order_no'=>$request->main_sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'rate_array'=>$request->rate_array[$x],
                        'size_array'=>$request->size_array[$x],
                        'barcode_array'=> rtrim($barcode_array, ','),
                        'size_qty_array'=>$request->size_qty_array[$x],
                        'size_qty_total'=>$request->size_qty_total[$x],
                        'from_loc_id'=>$request->from_loc_id,
                        'to_loc_id'=>$request->to_loc_id,
                   );
               
                      
                    $data3=array(
                        'fglti_code'=>$TrNo, 
                        'fglti_date'=>$request->fglti_date, 
                        'ltpki_code'=>$request->ltpki_code, 
						'sales_order_no'=>$request->main_sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'barcode_array'=>rtrim($barcode_array, ','),
                        'size_array'=>$request->size_array[$x],
                        'rate_array'=>$request->rate_array[$x],
                        's1'=>$s1,
                        's2'=>$s2,
                        's3'=>$s3,
                        's4'=>$s4,
                        's5'=>$s5,
                        's6'=>$s6,
                        's7'=>$s7,
                        's8'=>$s8,
                        's9'=>$s9,
                        's10'=>$s10,
                        's11'=>$s11,
                        's12'=>$s12,
                        's13'=>$s13,
                        's14'=>$s14,
                        's15'=>$s15,
                        's16'=>$s16,
                        's17'=>$s17,
                        's18'=>$s18,
                        's19'=>$s19,
                        's20'=>$s20,
                        
                        's1_barcode'=>$sb1,
                        's2_barcode'=>$sb2,
                        's3_barcode'=>$sb3,
                        's4_barcode'=>$sb4,
                        's5_barcode'=>$sb5,
                        's6_barcode'=>$sb6,
                        's7_barcode'=>$sb7,
                        's8_barcode'=>$sb8,
                        's9_barcode'=>$sb9,
                        's10_barcode'=>$sb10,
                        's11_barcode'=>$sb11,
                        's12_barcode'=>$sb12,
                        's13_barcode'=>$sb13,
                        's14_barcode'=>$sb14,
                        's15_barcode'=>$sb15,
                        's16_barcode'=>$sb16,
                        's17_barcode'=>$sb17,
                        's18_barcode'=>$sb18,
                        's19_barcode'=>$sb19,
                        's20_barcode'=>$sb20,
                        
                        's1_rate'=>$s1_rate,
                        's2_rate'=>$s2_rate,
                        's3_rate'=>$s3_rate,
                        's4_rate'=>$s4_rate,
                        's5_rate'=>$s5_rate,
                        's6_rate'=>$s6_rate,
                        's7_rate'=>$s7_rate,
                        's8_rate'=>$s8_rate,
                        's9_rate'=>$s9_rate,
                        's10_rate'=>$s10_rate,
                        's11_rate'=>$s11_rate,
                        's12_rate'=>$s12_rate,
                        's13_rate'=>$s13_rate,
                        's14_rate'=>$s14_rate,
                        's15_rate'=>$s15_rate,
                        's16_rate'=>$s16_rate,
                        's17_rate'=>$s17_rate,
                        's18_rate'=>$s18_rate,
                        's19_rate'=>$s19_rate,
                        's20_rate'=>$s20_rate,
                        'size_qty_total'=>$request->size_qty_total[$x],
                        'from_loc_id'=>$request->from_loc_id,
                        'to_loc_id'=>$request->to_loc_id,
                    );
                FGLocationTransferInwardDetailModel::insert($data2);
                FGLocationTransferInwardSizeDetailModel::insert($data3);
              } // if loop avoid zero qty
        }
        
          
    }
    
        
    $InsertSizeData=DB::select('call AddSizeQtyFromFGLocationTransferInward("'.$TrNo.'")');
           
    return redirect()->route('FGLocationTransferInward.index')->with('message', 'Data Saved Succesfully');  
      
  }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($bom_code)
    {
        
         $BOMList = BOMMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'bom_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'bom_master.cost_type_id', 'left outer')
        ->where('bom_master.delflag','=', '0')
        ->where('bom_master.bom_code','=', $bom_code)
        
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('budgetPrint', compact('BOMList'));  
      
    }

    public function VPPrint($vpo_code)
    {
       $BOMList = VendorPurchaseOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_purchase_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_purchase_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_purchase_order_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_purchase_order_master.fg_id', 'left outer')   
        ->where('vendor_purchase_order_master.delflag','=', '0')
        ->where('vendor_purchase_order_master.vpo_code','=', $vpo_code)
        ->get(['vendor_purchase_order_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('cuttingOrderPrint', compact('BOMList'));     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
          
        $FGLocationTransferInwardMasterList = FGLocationTransferInwardMasterModel::find($id);
         
        $LedgerDetail  = LedgerDetailModel::where('ledger_details.ac_code',$FGLocationTransferInwardMasterList->Ac_code)->get();
         
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->where('Ac_code',$FGLocationTransferInwardMasterList->Ac_code)->get();
        
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$FGLocationTransferInwardMasterList->sales_order_no)->distinct()->get();
        
      
       $LocationList = LocationModel::where('location_master.delflag','=', '0')->get();
	 
        
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code',$FGLocationTransferInwardMasterList->sales_order_no)->DISTINCT()->get();
        
        $FGLocationTransferInwardDetailList =FGLocationTransferInwardDetailModel::where('fg_location_transfer_inward_detail.fglti_code','=', $FGLocationTransferInwardMasterList->fglti_code)->get();
        
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('tr_code',$FGLocationTransferInwardMasterList->sales_order_no)->get();
 
    
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
      
        $OutwardList = LocTransferPackingInhouseMasterModel::where('loc_transfer_packing_inhouse_master.delflag','=', '0')->get();
        
         return view('FGLocationTransferInwardMasterEdit',compact('FGLocationTransferInwardDetailList','ColorList' ,'LocationList','BuyerPurchaseOrderList', 'LedgerDetail',  'SizeDetailList','FGLocationTransferInwardMasterList', 'OutwardList',  'ItemList',  'MainStyleList','SubStyleList','FGList', 'Ledger' ));
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
          $this->validate($request, [
             
                'fglti_date'=> 'required', 
                'Ac_code'=> 'required', 
                'main_sales_order_no'=> 'required', 
               
    ]);
 
   
  
$data1=array(
           
        'fglti_code'=>$request->fglti_code, 
        'ltpki_code'=>$request->ltpki_code, 
        'fglti_date'=>$request->fglti_date,
        'firm_id'=>$request->firm_id,
        'sales_order_no'=>$request->main_sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'total_qty'=>$request->total_qty,
        'narration'=>$request->narration,
        'from_loc_id'=>$request->from_loc_id,
        'to_loc_id'=>$request->to_loc_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
         
    );
//   DB::enableQueryLog();   
$PackingInhouseList = FGLocationTransferInwardMasterModel::findOrFail($request->fglti_code); 
//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
$PackingInhouseList->fill($data1)->save();

 
DB::table('fg_location_transfer_inward_size_detail')->where('fglti_code', $request->input('fglti_code'))->delete();
DB::table('fg_location_transfer_inward_size_detail2')->where('fglti_code', $request->input('fglti_code'))->delete();
DB::table('fg_location_transfer_inward_detail')->where('fglti_code', $request->input('fglti_code'))->delete();
 
 $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
        for($x=0; $x<count($color_id); $x++) 
        { 
            if($request->size_qty_total[$x]>0)
            {
                      $s1=isset($request->s1[$x]) ? $request->s1[$x] : 0; $s11=isset($request->s11[$x]) ? $request->s11[$x] : 0;
                      $s2=isset($request->s2[$x]) ? $request->s2[$x] : 0; $s12=isset($request->s12[$x]) ? $request->s12[$x] : 0;
                      $s3=isset($request->s3[$x]) ? $request->s3[$x] : 0; $s13=isset($request->s13[$x]) ? $request->s13[$x] : 0;
                      $s4=isset($request->s4[$x]) ? $request->s4[$x] : 0; $s14=isset($request->s14[$x]) ? $request->s14[$x] : 0;
                      $s5=isset($request->s5[$x]) ? $request->s5[$x] : 0; $s15=isset($request->s15[$x]) ? $request->s15[$x] : 0;
                      $s6=isset($request->s6[$x]) ? $request->s6[$x] : 0; $s16=isset($request->s16[$x]) ? $request->s16[$x] : 0;
                      $s7=isset($request->s7[$x]) ? $request->s7[$x] : 0; $s17=isset($request->s17[$x]) ? $request->s17[$x] : 0;
                      $s8=isset($request->s8[$x]) ? $request->s8[$x] : 0; $s18=isset($request->s18[$x]) ? $request->s18[$x] : 0;
                      $s9=isset($request->s9[$x]) ? $request->s9[$x] : 0; $s19=isset($request->s19[$x]) ? $request->s19[$x] : 0;
                      $s10=isset($request->s10[$x]) ? $request->s10[$x] : 0; $s20=isset($request->s20[$x]) ? $request->s20[$x] : 0;
 
                      $s1_rate=isset($request->s1_rate[$x]) ? $request->s1_rate[$x] : 0; $s11_rate=isset($request->s11_rate[$x]) ? $request->s11_rate[$x] : 0;
                      $s2_rate=isset($request->s2_rate[$x]) ? $request->s2_rate[$x] : 0; $s12_rate=isset($request->s12_rate[$x]) ? $request->s12_rate[$x] : 0;
                      $s3_rate=isset($request->s3_rate[$x]) ? $request->s3_rate[$x] : 0; $s13_rate=isset($request->s13_rate[$x]) ? $request->s13_rate[$x] : 0;
                      $s4_rate=isset($request->s4_rate[$x]) ? $request->s4_rate[$x] : 0; $s14_rate=isset($request->s14_rate[$x]) ? $request->s14_rate[$x] : 0;
                      $s5_rate=isset($request->s5_rate[$x]) ? $request->s5_rate[$x] : 0; $s15_rate=isset($request->s15_rate[$x]) ? $request->s15_rate[$x] : 0;
                      $s6_rate=isset($request->s6_rate[$x]) ? $request->s6_rate[$x] : 0; $s16_rate=isset($request->s16_rate[$x]) ? $request->s16_rate[$x] : 0;
                      $s7_rate=isset($request->s7_rate[$x]) ? $request->s7_rate[$x] : 0; $s17_rate=isset($request->s17_rate[$x]) ? $request->s17_rate[$x] : 0;
                      $s8_rate=isset($request->s8_rate[$x]) ? $request->s8_rate[$x] : 0; $s18_rate=isset($request->s18_rate[$x]) ? $request->s18_rate[$x] : 0;
                      $s9_rate=isset($request->s9_rate[$x]) ? $request->s9_rate[$x] : 0; $s19_rate=isset($request->s19_rate[$x]) ? $request->s19_rate[$x] : 0;
                      $s10_rate=isset($request->s10_rate[$x]) ? $request->s10_rate[$x] : 0; $s20_rate=isset($request->s20_rate[$x]) ? $request->s20_rate[$x] : 0;
                       
                      $s1_barcode=isset($request->s1_barcode[$x]) ? $request->s1_barcode[$x] : ''; $s11_barcode=isset($request->s11_barcode[$x]) ? $request->s11_barcode[$x] : '';
                      $s2_barcode=isset($request->s2_barcode[$x]) ? $request->s2_barcode[$x] : ''; $s12_barcode=isset($request->s12_barcode[$x]) ? $request->s12_barcode[$x] : '';
                      $s3_barcode=isset($request->s3_barcode[$x]) ? $request->s3_barcode[$x] : ''; $s13_barcode=isset($request->s13_barcode[$x]) ? $request->s13_barcode[$x] : '';
                      $s4_barcode=isset($request->s4_barcode[$x]) ? $request->s4_barcode[$x] : ''; $s14_barcode=isset($request->s14_barcode[$x]) ? $request->s14_barcode[$x] : '';
                      $s5_barcode=isset($request->s5_barcode[$x]) ? $request->s5_barcode[$x] : ''; $s15_barcode=isset($request->s15_barcode[$x]) ? $request->s15_barcode[$x] : '';
                      $s6_barcode=isset($request->s6_barcode[$x]) ? $request->s6_barcode[$x] : ''; $s16_barcode=isset($request->s16_barcode[$x]) ? $request->s16_barcode[$x] : '';
                      $s7_barcode=isset($request->s7_barcode[$x]) ? $request->s7_barcode[$x] : ''; $s17_barcode=isset($request->s17_barcode[$x]) ? $request->s17_barcode[$x] : '';
                      $s8_barcode=isset($request->s8_barcode[$x]) ? $request->s8_barcode[$x] : ''; $s18_barcode=isset($request->s18_barcode[$x]) ? $request->s18_barcode[$x] : '';
                      $s9_barcode=isset($request->s9_barcode[$x]) ? $request->s9_barcode[$x] : ''; $s19_barcode=isset($request->s19_barcode[$x]) ? $request->s19_barcode[$x] : '';
                      $s10_barcode=isset($request->s10_barcode[$x]) ? $request->s10_barcode[$x] : ''; $s20_barcode=isset($request->s20_barcode[$x]) ? $request->s20_barcode[$x] : '';
                       
                      $sb1 ='';
                      $sb2 = '';
                      $sb3 = '';
                      $sb4 = '';
                      $sb5 = '';
                      $sb6 = '';
                      $sb7 = '';
                      $sb8 = '';
                      $sb9 = '';
                      $sb10 = '';
                      $sb11 = '';
                      $sb12 = '';
                      $sb13 = '';
                      $sb14 = '';
                      $sb15 = '';
                      $sb16 = '';
                      $sb17 = '';
                      $sb18 = '';
                      $sb19 = '';
                      $sb20 = '';
                      $fglti_explode = explode("-",$request->fglti_code);
                      $tr_no = $fglti_explode[1];
                      if($s1_barcode != '')
                      {
                          $sb1 = $s1_barcode.''.$tr_no.','; 
                      } 
                        
                      if($s2_barcode !='')
                      {
                          $sb2 = $s2_barcode.''.$tr_no.',';
                      } 
                        
                      if($s3_barcode !='')
                      {
                          $sb3 = $s3_barcode.''.$tr_no.',';
                      } 
                        
                      if($s4_barcode !='')
                      {
                          $sb4 = $s4_barcode.''.$tr_no.',';
                      } 
                        
                      if($s5_barcode !='')
                      {
                          $sb5 = $s5_barcode.''.$tr_no.',';
                      } 
                        
                      if($s6_barcode !='')
                      {
                          $sb6 = $s6_barcode.''.$tr_no.',';
                      } 
                      if($s7_barcode !='')
                      {
                          $sb7 = $s7_barcode.''.$tr_no.',';
                      }
                       
                      if($s8_barcode !='')
                      {
                          $sb8 = $s8_barcode.''.$tr_no.',';
                      }
                       
                      if($s9_barcode !='')
                      {
                          $sb9 = $s9_barcode.''.$tr_no.',';
                      }  
                      if($s10_barcode !='')
                      {
                          $sb10 = $s10_barcode.''.$tr_no.',';
                      } 
                      if($s11_barcode != '')
                      {
                          $sb11 = $s11_barcode.''.$tr_no.',';
                      } 
                      
                      if($s12_barcode != '')
                      {
                          $sb12 = $s12_barcode.''.$tr_no.',';
                      } 
                      
                      if($s13_barcode != '')
                      {
                          $sb13 = $s13_barcode.''.$tr_no.',';
                      } 
                      if($s14_barcode != '')
                      {
                          $sb14 = $s14_barcode.''.$tr_no.',';
                      } 
                      if($s15_barcode != '')
                      {
                          $sb15 = $s15_barcode.''.$tr_no.',';
                      } 
                      if($s16_barcode != '')
                      {
                          $sb16 = $s16_barcode.''.$tr_no.',';
                      } 
                      if($s17_barcode != '')
                      {
                          $sb17 = $s17_barcode.''.$tr_no.',';
                      } 
                      
                      if($s18_barcode != '')
                      {
                          $sb18 = $s18_barcode.''.$tr_no.',';
                      } 
                      
                      if($s19_barcode != '')
                      {
                          $sb19 = $s19_barcode.''.$tr_no.',';
                      }  
                      
                      if($s20_barcode != '')
                      {
                          $sb20 = $s20_barcode.''.$tr_no.',';
                      } 
                      
                    $data2=array
                    (
    					'fglti_code'=>$request->fglti_code,
                        'fglti_date'=>$request->fglti_date,
                        'ltpki_code'=>$request->ltpki_code, 
    					'sales_order_no'=>$request->main_sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'rate_array'=>$request->rate_array[$x],
                        'barcode_array'=>$request->barcode_array[$x],
                        'size_array'=>$request->size_array[$x],
                        'size_qty_array'=>$request->size_qty_array[$x],
                        'size_qty_total'=>$request->size_qty_total[$x],
                        'from_loc_id'=>$request->from_loc_id,
                        'to_loc_id'=>$request->to_loc_id,
                   );
               
                    $data3=array(
                        'fglti_code'=>$request->fglti_code, 
                        'fglti_date'=>$request->fglti_date, 
                        'ltpki_code'=>$request->ltpki_code, 
						'sales_order_no'=>$request->main_sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'size_array'=>$request->size_array[$x],
                        'barcode_array'=>$request->barcode_array[$x],
                        'rate_array'=>$request->rate_array[$x],
                        's1'=>$s1,
                        's2'=>$s2,
                        's3'=>$s3,
                        's4'=>$s4,
                        's5'=>$s5,
                        's6'=>$s6,
                        's7'=>$s7,
                        's8'=>$s8,
                        's9'=>$s9,
                        's10'=>$s10,
                        's11'=>$s11,
                        's12'=>$s12,
                        's13'=>$s13,
                        's14'=>$s14,
                        's15'=>$s15,
                        's16'=>$s16,
                        's17'=>$s17,
                        's18'=>$s18,
                        's19'=>$s19,
                        's20'=>$s20,
                        
                        's1_barcode'=>$sb1,
                        's2_barcode'=>$sb2,
                        's3_barcode'=>$sb3,
                        's4_barcode'=>$sb4,
                        's5_barcode'=>$sb5,
                        's6_barcode'=>$sb6,
                        's7_barcode'=>$sb7,
                        's8_barcode'=>$sb8,
                        's9_barcode'=>$sb9,
                        's10_barcode'=>$sb10,
                        's11_barcode'=>$sb11,
                        's12_barcode'=>$sb12,
                        's13_barcode'=>$sb13,
                        's14_barcode'=>$sb14,
                        's15_barcode'=>$sb15,
                        's16_barcode'=>$sb16,
                        's17_barcode'=>$sb17,
                        's18_barcode'=>$sb18,
                        's19_barcode'=>$sb19,
                        's20_barcode'=>$sb20,
                        
                        's1_rate'=>$s1_rate,
                        's2_rate'=>$s2_rate,
                        's3_rate'=>$s3_rate,
                        's4_rate'=>$s4_rate,
                        's5_rate'=>$s5_rate,
                        's6_rate'=>$s6_rate,
                        's7_rate'=>$s7_rate,
                        's8_rate'=>$s8_rate,
                        's9_rate'=>$s9_rate,
                        's10_rate'=>$s10_rate,
                        's11_rate'=>$s11_rate,
                        's12_rate'=>$s12_rate,
                        's13_rate'=>$s13_rate,
                        's14_rate'=>$s14_rate,
                        's15_rate'=>$s15_rate,
                        's16_rate'=>$s16_rate,
                        's17_rate'=>$s17_rate,
                        's18_rate'=>$s18_rate,
                        's19_rate'=>$s19_rate,
                        's20_rate'=>$s20_rate,
                        'size_qty_total'=>$request->size_qty_total[$x],
                        'from_loc_id'=>$request->from_loc_id,
                        'to_loc_id'=>$request->to_loc_id,
                    );
                FGLocationTransferInwardDetailModel::insert($data2);
                FGLocationTransferInwardSizeDetailModel::insert($data3);
              } // if loop avoid zero qty
        }
          
    }
     
           
        $InsertSizeData=DB::select('call AddSizeQtyFromFGLocationTransferInward("'.$request->fglti_code.'")');
           
        return redirect()->route('FGLocationTransferInward.index')->with('message', 'Data Saved Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
   public function FGPackingInhouseDetails(Request $request)
    { 
       
        $SalesOrders=$request->sales_order_no;
        $MasterdataList = DB::select("select Ac_code,tr_code, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code ='".$SalesOrders."'");
        return json_encode($MasterdataList);
    }   
      



 public function LTPKI_GetMaxMinvalueList(Request $request)
    { 
         $color_id=$request->input('color_id');
         $from_loc_id=$request->from_loc_id;
         $main_sales_order_no=$request->main_sales_order_no;
         
         $sizeList='';
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$main_sales_order_no)->first();
     $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
       $sizes='';
      $no=1;
         foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'ifnull(sum(s'.$no.'),0) as s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        
        
        // DB::enableQueryLog();
       $LocRecList = DB::select("SELECT ifnull(loc_transfer_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from loc_transfer_packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=loc_transfer_packing_inhouse_size_detail.color_id where 
      sales_order_no='".$main_sales_order_no."' and to_loc_id='".$from_loc_id."' and
      loc_transfer_packing_inhouse_size_detail.color_id='".$color_id."'");  

      
      $CompareList = DB::select("SELECT ifnull(fg_location_transfer_inward_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from fg_location_transfer_inward_size_detail inner join color_master on 
      color_master.color_id=fg_location_transfer_inward_size_detail.color_id where 
      sales_order_no='".$main_sales_order_no."' and from_loc_id='".$from_loc_id."' and
      fg_location_transfer_inward_size_detail.color_id='".$color_id."'");

  
       $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
        // DB::enableQueryLog(); 
    
     
      $List = DB::select("SELECT  ifnull(packing_inhouse_size_detail.item_code,0) as item_code,
      ifnull(packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizex.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=packing_inhouse_size_detail.color_id where 
      packing_inhouse_size_detail.sales_order_no ='".$main_sales_order_no."' and   location_id='".$from_loc_id."' and
      packing_inhouse_size_detail.color_id='".$color_id."'");
       
    //  $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
      
       
   if(isset($List[0]->s_1)) { $s1=((intval($List[0]->s_1)) +   (intval($LocRecList[0]->s1))-(intval($CompareList[0]->s1))); $sizeList=$sizeList.$s1.' as s1, ';}
//   echo '   '.$List[0]->s_1;
//   echo '   '.$CompareList[0]->s1;
//   echo '   '.$CompareList2[0]->s1;
//   echo '    '.$CartonList[0]->s1;
//   echo '     '.(($List[0]->color_id > 0) ? intval($CartonList[0]->s1) : 0);
//   exit;
   if(isset($List[0]->s_2)) { $s2=((intval($List[0]->s_2)) +   (intval($LocRecList[0]->s2))   - (intval($CompareList[0]->s2))); $sizeList=$sizeList.$s2.' as s2, ';}
   if(isset($List[0]->s_3)) { $s3=((intval($List[0]->s_3))+   (intval($LocRecList[0]->s3))-(intval($CompareList[0]->s3))); $sizeList=$sizeList.$s3.' as s3, ';}
   if(isset($List[0]->s_4)) { $s4=((intval($List[0]->s_4))+   (intval($LocRecList[0]->s4))-(intval($CompareList[0]->s4))); $sizeList=$sizeList.$s4.' as s4, ';}
   if(isset($List[0]->s_5)) { $s5=((intval($List[0]->s_5))+   (intval($LocRecList[0]->s5))-(intval($CompareList[0]->s5))); $sizeList=$sizeList.$s5.' as s5, ';}
   if(isset($List[0]->s_6)) { $s6=((intval($List[0]->s_6))+   (intval($LocRecList[0]->s6))-(intval($CompareList[0]->s6))); $sizeList=$sizeList.$s6.' as s6, ';}
   if(isset($List[0]->s_7)) { $s7=((intval($List[0]->s_7))+   (intval($LocRecList[0]->s7))-(intval($CompareList[0]->s7))); $sizeList=$sizeList.$s7.' as s7, ';}
   if(isset($List[0]->s_8)) { $s8=((intval($List[0]->s_8))+   (intval($LocRecList[0]->s8))-(intval($CompareList[0]->s8))); $sizeList=$sizeList.$s8.' as s8, ';}
   if(isset($List[0]->s_9)) { $s9=((intval($List[0]->s_9))+   (intval($LocRecList[0]->s9))-(intval($CompareList[0]->s9))); $sizeList=$sizeList.$s9.' as s9, ';}
   if(isset($List[0]->s_10)) { $s10=((intval($List[0]->s_10))+   (intval($LocRecList[0]->s10))-(intval($CompareList[0]->s10))); $sizeList=$sizeList.$s10.' as s10, ';}
   if(isset($List[0]->s_11)) { $s11=((intval($List[0]->s_11))+   (intval($LocRecList[0]->s11))-(intval($CompareList[0]->s11))); $sizeList=$sizeList.$s11.' as s11, ';}
   if(isset($List[0]->s_12)) { $s12=((intval($List[0]->s_12))+   (intval($LocRecList[0]->s12))-(intval($CompareList[0]->s12))); $sizeList=$sizeList.$s12.' as s12, ';}
   if(isset($List[0]->s_13)) { $s13=((intval($List[0]->s_13))+   (intval($LocRecList[0]->s13))-(intval($CompareList[0]->s13))); $sizeList=$sizeList.$s13.' as s13, ';}
   if(isset($List[0]->s_14)) { $s14=((intval($List[0]->s_14))+   (intval($LocRecList[0]->s14))-(intval($CompareList[0]->s14))); $sizeList=$sizeList.$s14.' as s14, ';}
   if(isset($List[0]->s_15)) { $s15=((intval($List[0]->s_15))+   (intval($LocRecList[0]->s15))-(intval($CompareList[0]->s15))); $sizeList=$sizeList.$s15.' as s15, ';}
   if(isset($List[0]->s_16)) { $s16=((intval($List[0]->s_16))+   (intval($LocRecList[0]->s16))-(intval($CompareList[0]->s16))); $sizeList=$sizeList.$s16.' as s16, ';}
   if(isset($List[0]->s_17)) { $s17=((intval($List[0]->s_17))+   (intval($LocRecList[0]->s17))-(intval($CompareList[0]->s17))); $sizeList=$sizeList.$s17.' as s17, ';}
   if(isset($List[0]->s_18)) { $s18=((intval($List[0]->s_18))+   (intval($LocRecList[0]->s18))-(intval($CompareList[0]->s18))); $sizeList=$sizeList.$s18.' as s18, ';}
   if(isset($List[0]->s_19)) { $s19=((intval($List[0]->s_19))+   (intval($LocRecList[0]->s19))-(intval($CompareList[0]->s19))); $sizeList=$sizeList.$s19.' as s19, ';}
   if(isset($List[0]->s_20)) { $s20=((intval($List[0]->s_20))+   (intval($LocRecList[0]->s20))-(intval($CompareList[0]->s20))); $sizeList=$sizeList.$s20.' as s20, ';}
       
     // exit; 
      // echo $s1;
       
    //   DB::enableQueryLog(); 
       $MasterdataList=DB::select("select ".$List[0]->item_code." as item_code,".$sizeList.($nox-1)." as size_count");
    //          $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
       return json_encode($MasterdataList);
    } 

 

public function GetItemWiseSalesOrderCosting(Request $request)
{
    $item_code= $request->item_code;
    $size_id= $request->size_id;
    $color_id= $request->color_id;
    $sales_order_no= $request->sales_order_no;
//print_r($item_code);
    $codefetch = DB::table('item_master')->select("class_id","unit_id")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;

 
    // DB::enableQueryLog();
    $data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`, ".$Unit_id." as unit_id, ".$Class_id." as class_id,
     (select sum(size_qty) from buyer_purchase_order_size_detail where   
     tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))*bom_qty as bom_qty,
     `rate_per_unit`, `wastage`, `total_amount` from sales_order_sewing_trims_costing_details
    where  class_id='$Class_id' and sales_order_no='$sales_order_no'")); 
    //  $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
    echo json_encode($data);

}


public function GetFabricWiseSalesOrderCosting(Request $request)
{
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 //print_r($item_code);
    $codefetch = DB::table('item_master')->select("class_id","unit_id")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;
    $data = DB::select(DB::raw("SELECT distinct class_id ,   `description`, `consumption`,".$Unit_id." as unit_id,".$Class_id." as class_id,
     (select sum(size_qty_total) from buyer_purchase_order_detail where item_code=$item_code and 
     tr_code='$sales_order_no') as bom_qty , 
    `rate_per_unit`, `wastage`, `total_amount` from sales_order_fabric_costing_details
    where  class_id=$Class_id and sales_order_no='$sales_order_no'")); 



    echo json_encode($data);

}

public function GetPackingWiseSalesOrderCosting(Request $request)
{
 

$item_code= $request->item_code;
$size_id= $request->size_id;
$color_id= $request->color_id;
$sales_order_no= $request->sales_order_no;
//print_r($item_code);
$codefetch = DB::table('item_master')->select("class_id","unit_id")
->where('item_code','=',$request->item_code)
->first();
$Class_id=$codefetch->class_id;
$Unit_id=$codefetch->unit_id;
//    DB::enableQueryLog();
$data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`,".$Unit_id." as unit_id,".$Class_id." as class_id,
 ((select sum(size_qty) from buyer_purchase_order_size_detail where   
 tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))*bom_qty) as bom_qty,
 `rate_per_unit`, `wastage`, `total_amount` from sales_order_packing_trims_costing_details
where  class_id='$Class_id' and sales_order_no='$sales_order_no'")); 
 
//  $query = DB::getQueryLog();
//  $query = end($query);
//  dd($query);
echo json_encode($data);
 
}


public function getBuyerLocationList(Request $request)
{
    $LocationList = DB::table('ledger_details')->select('sr_no','consignee_address','site_code')
    ->where('ac_code','=',$request->Ac_code)->get();
    
    
    
    if (!$request->Ac_code)
    {
        $html = '<option value="">--Location List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Location List--</option>';
        
        foreach ($LocationList as $row) 
        {$html .= '<option value="'.$row->sr_no.'">('.$row->site_code.') '.$row->consignee_address.'</option>';}
    }
      return response()->json(['html' => $html]);
}



 
public function getSalesOrderList(Request $request)
{
    $BuyerPurchaseOrderList = BuyerPurchaseOrderMasterModel::select('tr_code')
    ->where('Ac_code','=',$request->Ac_code)->get();
    
    if (!$request->Ac_code)
    {
        $html = '<option value="">--Sales Order List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Sales Order List--</option>';
        
        foreach ($BuyerPurchaseOrderList as $row) 
        {$html .= '<option value="'.$row->tr_code.'">'.$row->tr_code.'</option>';}
    }
      return response()->json(['html' => $html]);
}



    function FGStockSizeValue(Request $request)
    { 
        $FGStockData = DB::select("SELECT ifnull(sum(FG.`size_qty`),0)  as packing_grn_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                
                ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, 
                
                ifnull((SELECT  sum(d3.size_qty)from FGStockDataByOne as d3 where d3.data_type_id=4 and d3.sales_order_no=FG.sales_order_no and d3.color_id=FG.color_id 
                and d3.size_id=FG.size_id),0)  as loc_transfer_qty, 
                
                FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
                inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                where FG.data_type_id=1 AND FG.sales_order_no ='".$request->sales_order_no."' AND FG.size_id = ".$request->size_id." AND  FG.color_id = ".$request->color_id."
                group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
                
        $FGStock = $FGStockData[0]->packing_grn_qty - $FGStockData[0]->carton_pack_qty- $FGStockData[0]->transfer_qty- $FGStockData[0]->loc_transfer_qty;
        
        return $FGStock;
    }



  public function LTPKI_GetTransferQtyByRow(Request $request)
  {
       
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$request->sales_order_no)->first();
     
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::where('tr_code',$request->sales_order_no)->first();
      
      $BuyerPurchaseOrderList = BuyerPurchaseOrderMasterModel::select('tr_code as  sales_order_no')->get();
       $colorList=DB::select("select buyer_purchase_order_detail.color_id, color_name from buyer_purchase_order_detail
        inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
        where tr_code='".$request->sales_order_no."'");
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
       
                
      $sizes='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        // DB::enableQueryLog();  
      $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$BuyerPurchaseOrderMasterList->sales_order_no."'");
 
      $html = '';
     
          $no=1;
          
        
          $html .='<tr>';
          $html .='
          <td><input type="text" name="id" value="'.$no.'" id="id" style="width:50px;height:30px;"/></td>';
                 
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2"  id="color_id0" style="width:250px; height:30px;"  onchange="setFGLimit(this);" required>
        <option value="">--Select Color--</option>';
            foreach ($colorList as $color) 
                  {
                     $html.='<option value="'.$color->color_id.'"';
           
                    $html.='>'.$color->color_name.'</option>';
                  }
        
        $html.='</select></td>';
    
      
          if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s1[]" class="size_id" onchange="checkNumber(this);"  type="number" id="s1" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s2[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s2" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s3[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s3" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s4[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s4" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s5[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s5" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s6[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s6" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s7[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s7" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s8[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s8" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s9[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s9" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s10[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s10" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s11[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s11" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s12[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s12" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s13[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s13" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;" max="0" min="0" name="s14[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s14" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s15[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s15" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s16[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s16" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s17[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s17" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s18[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s18" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s19[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s19" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s20[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s20" value="0" required /></td>';}
          $html.='<td>
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
          $html.='<td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>';

          
          
           $html.='</tr>';

        
         

      return response()->json(['html' => $html]);
         
  }

  
  
  
  public function LocFGStockData(Request $request)
  {
 
     
     $html = '';
     
     
        //   DB::enableQueryLog();  


    //   $FinishedGoodsStock = DB::select("SELECT    buyer_purchse_order_master.tr_code as sales_order_no,  
    //   color_master.color_name,color_master.style_img_path, brand_master.brand_name, 
    //     size_detail.size_name, 
        
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from packing_inhouse_size_detail2 
    //     inner join packing_inhouse_master on packing_inhouse_master.pki_code=packing_inhouse_size_detail2.pki_code
    //     where packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     packing_inhouse_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and   packing_inhouse_size_detail2.location_id='".$request->from_loc_id."'
    //      ),0) as 'packing_grn_qty',
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
    //     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
    //     where carton_packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     carton_packing_inhouse_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and carton_packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and carton_packing_inhouse_master.endflag=1
    //     ),0)  as 'carton_pack_qty',
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from fg_location_transfer_inward_size_detail2 
    //     inner join fg_location_transfer_inward_master on fg_location_transfer_inward_master.fglti_code=fg_location_transfer_inward_size_detail2.fglti_code
    //     where fg_location_transfer_inward_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     fg_location_transfer_inward_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and fg_location_transfer_inward_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and   fg_location_transfer_inward_size_detail2.from_loc_id='".$request->from_loc_id."'
    //      ),0) as 'loc_transfer_qty',
        
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from fg_location_transfer_inward_size_detail2 
    //     inner join fg_location_transfer_inward_master on fg_location_transfer_inward_master.fglti_code=fg_location_transfer_inward_size_detail2.fglti_code
    //     where fg_location_transfer_inward_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     fg_location_transfer_inward_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and fg_location_transfer_inward_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and   fg_location_transfer_inward_size_detail2.to_loc_id='".$request->from_loc_id."'
    //      ),0) as 'loc_rec_transfer_qty',
        
          
    //       ifnull((SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
    //     inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
    //     where transfer_packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     transfer_packing_inhouse_size_detail2.main_sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and transfer_packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and transfer_packing_inhouse_size_detail2.usedFlag=1
    //     ),0) as 'transfer_qty' 
         
        
    //     FROM `buyer_purchase_order_size_detail`
        
    //     LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=buyer_purchase_order_size_detail.tr_code
    //     LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
    //   LEFT JOIN color_master on color_master.color_id=buyer_purchase_order_size_detail.color_id
    //     LEFT JOIN size_detail on size_detail.size_id = buyer_purchase_order_size_detail.size_id
    //   where buyer_purchse_order_master.tr_code='".$request->sales_order_no."'
    //     GROUP by buyer_purchase_order_size_detail.tr_code, buyer_purchase_order_size_detail.color_id, buyer_purchase_order_size_detail.size_id");


        $FinishedGoodsStock = DB::select("SELECT FG.entry_date, FG.`ac_name`,job_status_master.job_status_name,sales_order_costing_master.total_cost_value,
                brand_master.brand_name, FG.`sales_order_no`, FG.`mainstyle_name`, FG.`substyle_name`, FG.`fg_name`, FG.`style_no`, 
                FG.`style_description`, FG.`color_name`, FG.`size_name`,ifnull(sum(FG.`size_qty`),0)  as packing_grn_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                
                ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, 
                
                ifnull((SELECT  sum(d3.size_qty)from FGStockDataByOne as d3 where d3.data_type_id=4 and d3.sales_order_no=FG.sales_order_no and d3.color_id=FG.color_id 
                and d3.size_id=FG.size_id),0)  as loc_transfer_qty, 
                
                FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
                inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                inner join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                inner join brand_master On brand_master.brand_id = buyer_purchse_order_master.brand_id
                left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
                where FG.data_type_id=1 AND FG.sales_order_no ='".$request->sales_order_no."' group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
                
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     
     $html.=' 
                                    <table id="tbl" class="table table-bordered   nowrap w-100">
                                          <thead>
                                            <tr style="text-align:center; white-space:nowrap">
											     
											    <th nowrap>Sales Order No</th>
												<th nowrap>Buyer Brand</th>
											    <th nowrap>Garment Color</th> 
                                                <th nowrap>Size</th> 
                                                <th nowrap>Packing GRN Qty</th> 
                                                <th nowrap>Carton Paking Qty</th>
                                                <th nowrap>O2O Transfer Qty</th>
                                                <th nowrap> Location Transfer Qty</th>
                                                <th nowrap>FG Stock</th>
                                              
                                            </tr>
                                            </thead>';
                                            
                                         foreach($FinishedGoodsStock as $FG)
                                            {
                                                $html.='<tr>';
                                                 $html.='<td nowrap>'.$FG->sales_order_no.'</td>';
                                                 $html.='<td nowrap>'.$FG->brand_name.'</td>';
                                                 $html.='<td nowrap>'.$FG->color_name.'</td>';
                                                 $html.='<td nowrap>'.$FG->size_name.'</td>';
                                                 $html.='<td nowrap>'.$FG->packing_grn_qty.'</td>';
                                                 $html.='<td nowrap>'.$FG->carton_pack_qty.'</td>';
                                                 $html.='<td nowrap>'.$FG->transfer_qty.'</td>';
                                                 $html.='<td nowrap>'.$FG->loc_transfer_qty.'</td>';
                                                //  $html.='<td>'.($FG->packing_grn_qty - $FG->carton_pack_qty- $FG->transfer_qty- $FG->loc_transfer_qty + $FG->loc_rec_transfer_qty ).'</td>';
                                                 $html.='<td nowrap>'.($FG->packing_grn_qty - $FG->carton_pack_qty- $FG->transfer_qty- $FG->loc_transfer_qty).'</td>';
                                               $html.='</tr>';
                                            }
        
                                            $html.='<tbody>
                                         
                                            </tbody>
                                        </table>
                                       ';
                        
                        
                          return response()->json(['html' => $html]);
  }
  
  
  function FGLocationTransferInwardPrint($fglti_code)
  {
      
       //   DB::enableQueryLog();
       
         $FGLocationTransferInwardMaster = FGLocationTransferInwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'fg_location_transfer_inward_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fg_location_transfer_inward_master.Ac_code')
         ->join('location_master as loc1', 'loc1.loc_id', '=', 'fg_location_transfer_inward_master.from_loc_id')
         ->join('location_master as loc2', 'loc2.loc_id', '=', 'fg_location_transfer_inward_master.to_loc_id')
        ->where('fg_location_transfer_inward_master.fglti_code', $fglti_code)
         ->get(['fg_location_transfer_inward_master.*','usermaster.username','ledger_master.Ac_name','fg_location_transfer_inward_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address','loc1.location as fromlocation','loc2.location as tolocation' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
       
       $SalesOrderList=explode(",", $FGLocationTransferInwardMaster[0]->sales_order_no);
       $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
       $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::whereIn('tr_code',$SalesOrderList)->get();
                   
        
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $barcodes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $barcodes=$barcodes.'s'.$no.'_barcode,';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        $barcodes=rtrim($barcodes,',');
       // DB::enableQueryLog();  
          $FGLocationTransferInwardDetailList = DB::select("SELECT fg_location_transfer_inward_size_detail.sales_order_no,
            fg_location_transfer_inward_size_detail.color_id, color_master.color_name, ".$sizes.", ".$barcodes.",  
            sum(size_qty_total) as size_qty_total  from  fg_location_transfer_inward_size_detail 
            inner join color_master on color_master.color_id=fg_location_transfer_inward_size_detail.color_id 
            where fglti_code='".$FGLocationTransferInwardMaster[0]->fglti_code."' 
            group by fg_location_transfer_inward_size_detail.sales_order_no,fg_location_transfer_inward_size_detail.color_id");
       // dd(DB::getQueryLog());
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
        return view('FGLocationTransferInwardPrint', compact('FGLocationTransferInwardMaster','FGLocationTransferInwardDetailList','SizeDetailList','FirmDetail','LocationList'));
  }
  
  public function LTFG_GetRawData(Request $request)
  {
    //   $SalesOrder=$request->sales_order_no;
    //   echo $SalesOrder;
    // DB::enableQueryLog();  
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')
      ->where('buyer_purchse_order_master.tr_code',$request->sales_order_no)->first();
     
     
       $SalesOrderList = BuyerPurchaseOrderMasterModel::select('tr_code as sales_order_no')->get();
    
    // DB::enableQueryLog();  
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::select('tr_code as sales_order_no', 'size_array')
      ->where('buyer_purchase_order_detail.tr_code',$request->sales_order_no)->first();
        //  $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $sizes_id = '';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'s'.$no.',';
          $sizes_id =$sizes_id.$sz->size_id.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
      $sizes_Arr=rtrim($sizes_id,',');
        // DB::enableQueryLog();  
        $colorList=DB::select("select buyer_purchase_order_detail.color_id, color_name from buyer_purchase_order_detail
        inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
        where tr_code='".$request->sales_order_no."'");
      $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$request->sales_order_no."'");

//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
       $html = '';
      $html .= '   
      <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
              <thead>
              <tr>
              <th>SrNo</th>
               
                
              <th>Color</th>';
                 foreach ($SizeDetailList as $sz) 
                  {
                      $html.='<th>'.$sz->size_name.'</th>';
                  }
                  $html.=' 
                  <th>Total Qty</th>
                  <th>Add/Remove</th>
                  </tr>
              </thead>
              <tbody  id="CartonData">';
          $no=1;
          
        
          $html .='<tr>';
          $html .='
          <td><input type="text" name="id" value="'.$no.'" id="id" style="width:50px;height:30px;"/></td>';
                
      
      
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" />
        
        <select name="color_id[]" class="select2-select select2"  id="color_id0" style="width:250px; height:30px;" onchange="setFGLimit(this);" sales_order_no="'.$request->sales_order_no.'" size_array="'.$sizes_Arr.'" required>
        <option value="">--Select Color--</option>';
         foreach ($colorList as $color) 
                  {
                     $html.='<option value="'.$color->color_id.'"';
           
                    $html.='>'.$color->color_name.'</option>';
                  }
        
        
        $html.='</select></td>';
    
      
          if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="0" name="s1[]" class="size_id" onchange="checkNumber(this);" type="number" id="s1" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;"   min="0" max="0"  name="s2[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s2" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="0"   name="s3[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s3" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="0"  name="s4[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s4" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;" min="0" max="0"  name="s5[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s5" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="0"   name="s6[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s6" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s7[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s7" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s8[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s8" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s9[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s9" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s10[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s10" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s11[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s11" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s12[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s12" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s13[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s13" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;"  min="0" max="0"  name="s14[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s14" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s15[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s15" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s16[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s16" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s17[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s17" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s18[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s18" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" min="0" max="0"   name="s19[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s19" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="0"  name="s20[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s20" value="0" required /></td>';}
          $html.='<td>
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
          $html.='<td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>';

          
          
           $html.='</tr>';

          $no=$no+1;
        
          $html.=' 
            </tbody>
            </table>';
    
      $size_array = $BuyerPurchaseOrderDetailList->size_array;

      return response()->json(['html' => $html,'size_array' => $size_array]);
         
  }
  
  public function VPO_GetSizeList(Request $request)
  {
  
    $codefetch = DB::table('buyer_purchse_order_master')->select("sz_code")
    ->where('tr_code','=',$request->tr_code)
    ->first();
    $sz_code=$codefetch->sz_code;
//print_r($sz_code);
    $SizeList= SizeDetailModel::select('size_id','size_name')->where('sz_code',$sz_code)->get();

    if (!$request->tr_code) {
        $html = '<option value="">--Size List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Size List--</option>';
        
        foreach ($SizeList as $row) {
                $html .= '<option value="'.$row->size_id.'">'.$row->size_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);

  }
     
public function FG_GetColorList(Request $request)
{
    $sales_order_no=$request->sales_order_no;
    $main_sales_order_no=$request->main_sales_order_no;
    
    //  DB::enableQueryLog();  
      

    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id','color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)
    ->whereRaw("buyer_purchase_order_detail.color_id in (select distinct color_id from buyer_purchase_order_detail where tr_code='".$request->main_sales_order_no."')")
    ->DISTINCT()->get();
   
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    
    
    if (!$request->sales_order_no)
    {
        $html = '<option value="">--Color List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Color List--</option>';
        
        foreach ($ColorList as $row) 
        {$html .= '<option value="'.$row->color_id.'">'.$row->color_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}


public function VPO_GetItemList(Request $request)
{
    $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
    ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
    ->where('tr_code','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Item List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.$row->item_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}

public function VPO_GetClassList(Request $request)
{
    $ClassList = DB::table('sales_order_fabric_costing_details')->select('sales_order_fabric_costing_details.class_id', 'class_name')
    ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_fabric_costing_details.class_id', 'left outer')
    ->where('sales_order_no','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Classification--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Classification--</option>';
        
        foreach ($ClassList as $row) 
        {$html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}


     
    public function GetCostingData($soc_code)
    {
            $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
            ->join('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
            ->join('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
            ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
            ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
            ->where('sales_order_costing_master.delflag','=', '0')
            ->where('sales_order_costing_master.soc_code','=', $soc_code)
            ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.Ac_name',
            'costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name',
            'sub_style_master.substyle_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_img_path']);
        
            return view('saleCostingSheet',compact('SalesOrderCostingMaster'));  
    } 
    
     
     
     
     
       public function GetFabricConsumptionPO(Request $request)
    { 
    
    $no= $request->input('no');
    $color_id= $request->input('color_id');
    $size_qty_total= $request->input('size_qty_total');
    $sales_order_no= $request->input('sales_order_no');
   
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 //print_r($item_code);
//   DB::enableQueryLog();
 
  $tr_code = DB::table('buyer_purchase_order_detail')->select("item_code")
  ->where('color_id','=',$color_id) ->where('tr_code','=',$sales_order_no)
  ->distinct()
  ->first();
  
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
  
  
//   DB::enableQueryLog();

  $codefetch = DB::table('bom_fabric_details')->select("item_code","consumption","description","wastage","class_id","unit_id","rate_per_unit")
  ->where('item_code','=',$tr_code->item_code) ->where('sales_order_no','=',$sales_order_no)
  ->first();
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     $UnitList = UnitModel::where('delflag','=', '0')->get();
     $ItemList = ItemModel::where('delflag','=', '0')->where('class_id','=', $codefetch->class_id)->get();
     $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
     $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
     
     $total_consumption= ($codefetch->consumption)+(($codefetch->consumption)*($codefetch->wastage/100));
     $bom_qty=round(($total_consumption*$size_qty_total),2);
                
    
     $html = '';
     
    $html .='<tr class="thisRow">';
   
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
$html.=' 
 
 <td> <select name="item_code[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required>
<option value="">--Item List--</option>';
foreach($ItemList as  $rowitem)
{
    $html.='<option value="'.$rowitem->item_code.'"';

    $rowitem->item_code == $codefetch->item_code ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowitem->item_name.'</option>';
}
$html.='</select></td>
 
 <td> <select name="class_id[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required>
<option value="">--Classification--</option>';
foreach($ClassList as  $rowclass)
{
    $html.='<option value="'.$rowclass->class_id.'"';
    $rowclass->class_id == $codefetch->class_id ? $html.='selected="selected"' : ''; 
    $html.='>'.$rowclass->class_name.'</option>';
}
$html.='</select></td> 
 
 <td><input type="text"    name="description[]" value="'.$codefetch->description.'" id="description" style="width:200px; height:30px;" required /></td> 
 
<td><input type="number" step="0.01"    name="consumption[]" value="'.$codefetch->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
  
<td> <select name="unit_id[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
<option value="">--Unit--</option>';
foreach($UnitList as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $codefetch->unit_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowunit->unit_name.'</option>';
}
$html.='</select></td> 

<td><input type="number" step="0.01"   name="wastage[]" value="'.$codefetch->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
<td><input type="text"  name="bom_qty[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
<input type="hidden"  name="final_cons[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
<input type="hidden"  name="size_qty[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
 
</td> 

';
     $html .='</tr>';
      
    return response()->json(['html' => $html]);
     
    }  
     
     
   public function GetSewingConsumption(Request $request)
    {
         
    $size_qty_array= $request->input('size_qty_array');
    $size_array= $request->input('size_array');
    $SizeList=explode(',', $size_array);
    $SizeQty=explode(',', $size_qty_array);
    //print_r($size_qty_array); 
   //  print_r($size_qty_array);
    $no= $request->input('no');
    $color_id= $request->input('color_id');
    $size_qty_total= $request->input('size_qty_total');
    $sales_order_no= $request->input('sales_order_no');
   
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 
    $UnitList = UnitModel::where('delflag','=', '0')->get();
    
    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
    
  $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
  //DB::enableQueryLog(); 
  
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
   $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', 2)->get();
 // print_r(count($codefetch));
  $html = '';

     $x=0;$qty=0;
     
foreach($SizeList as $sz)
 { 
     
     
        $qty=$SizeQty[$x++];
        if($qty!=0)
        {
            
            // DB::enableQueryLog();
           $codefetch = DB::table('bom_sewing_trims_details')->select("item_code","consumption","description","wastage","class_id","unit_id")
          ->whereRaw('FIND_IN_SET('.$color_id.',color_id)')->whereRaw('FIND_IN_SET('.$sz.',size_array)')->where('sales_order_no','=',$sales_order_no)
          ->get(); 
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
           
            foreach($codefetch as $rowsew)
            {   
                $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                $bom_qty=round(($total_consumption*$qty),2);
            
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
                $html.=' 
                <td> <select name="item_codes[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_ids[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text"    name="descriptions[]" value="'.$rowsew->description.'" id="description" style="width:200px; height:30px;" required /></td> 
                <td><input type="number" step="any"    name="consumptions[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_ids[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="any"   name="wastages[]" value="'.$rowsew->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtys[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="final_conss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="size_qtys[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                </td> 
                ';
                $html .='</tr>';
            } //main foreach
    }  // if loop for size qty 0
     
 }  // Size Array Foreach
    return response()->json(['html' => $html]);
     
    }    
     
     
     public function GetPackingConsumption(Request $request)
    {
         
    $size_qty_array= $request->input('size_qty_array');
    $size_array= $request->input('size_array');
    $SizeList=explode(',', $size_array);
    $SizeQty=explode(',', $size_qty_array);
    //print_r($size_qty_array); 
   //  print_r($size_qty_array);
    $no= $request->input('no');
    $color_id= $request->input('color_id');
    $size_qty_total= $request->input('size_qty_total');
    $sales_order_no= $request->input('sales_order_no');
   
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 
    $UnitList = UnitModel::where('delflag','=', '0')->get();
    
    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
    
  $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
  //DB::enableQueryLog(); 
  
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
   $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', 3)->get();
 // print_r(count($codefetch));
  $html = '';

     $x=0;$qty=0;
     
foreach($SizeList as $sz)
 { 
     
     
        $qty=$SizeQty[$x++];
        if($qty!=0)
        {
            
        // DB::enableQueryLog();
        
           $codefetch = DB::table('bom_packing_trims_details')->select("item_code","consumption","description","wastage","class_id","unit_id")
          ->whereRaw('FIND_IN_SET('.$color_id.',color_id)')->whereRaw('FIND_IN_SET('.$sz.',size_array)')->where('sales_order_no','=',$sales_order_no)
          ->get(); 
          
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
           
            foreach($codefetch as $rowsew)
            {   
                $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                $bom_qty=round(($total_consumption*$qty),2);
            
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
                $html.=' 
                <td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text"    name="descriptionss[]" value="'.$rowsew->description.'" id="description" style="width:200px; height:30px;" required /></td> 
                <td><input type="number" step="0.01"    name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="0.01"   name="wastagess[]" value="'.$rowsew->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtyss[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                </td> 
                ';
                $html .='</tr>';
            } //main foreach
    }  // if loop for size qty 0
     
 }  // Size Array Foreach
    return response()->json(['html' => $html]);
     
    }    
     
     
       public function getVendorPurchaseOrderDetails(Request $request)
    { 
        $vpo_code= $request->vpo_code;
        $MasterdataList = DB::select("select distinct sales_order_no,mainstyle_id,substyle_id,fg_id,style_no,style_description, vendorId  from vendor_purchase_order_master where vpo_code in (".$vpo_code.")");
        return json_encode($MasterdataList);
    }   
     
     
     
     public function getVendorPO(Request $request)
{
    //   DB::enableQueryLog();
         
     $POList = DB::select("select vpo_code,sales_order_no from vendor_purchase_order_master where vendorId ='".$request->vendorId."'");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    
    if (!$request->vendorId)
    {
        $html = '<option value="">--PO List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--PO List--</option>';
        
        foreach ($POList as $row)  
        $vpo_code="'".$row->vpo_code."'";
        {$html .= '<option value="'.$vpo_code.'">'.$row->vpo_code.'('.$row->sales_order_no.')</option>';}
    }
      return response()->json(['html' => $html]);
}
     
     
        public function GetCuttingPOItemList(Request $request)
        {
            $html='';
             if($request->part_id==1)
           { 
                $ItemList = DB::select("select distinct item_code from vendor_purchase_order_size_detail2 as f1
                where f1.vpo_code in (".$request->vpo_code.")");
           }
           else
           {
                $ItemList = DB::select("select distinct item_code from vendor_purchase_order_trim_fabric_details
                as f2 where f2.vpo_code in (".$request->vpo_code.")");
           }
            
            foreach ($ItemList as $row) 
            {
                $item = ItemModel::where('item_code','=', $row->item_code)->first(); 
                $html .= '<option value="'.$row->item_code.'">'.$item->item_name.'</option>';
            }    
   
             return response()->json(['html' => $html]);
        }    
     
      
      
    public function FGStockReport(Request $request)
    {
        $fglti_code='';
        $CPKIList=DB::select("select
          `sale_code`,
           SUBSTRING_INDEX(SUBSTRING_INDEX(sale_transaction_master.transfer_packing_nos, ',', numbers.n), ',', -1) as fglti_code
        from
          numbers inner join sale_transaction_master
          on CHAR_LENGTH(sale_transaction_master.transfer_packing_nos)
             -CHAR_LENGTH(REPLACE(sale_transaction_master.transfer_packing_nos, ',', ''))>=numbers.n-1
        order by
          sale_code, n");    
                foreach($CPKIList as $codes)
                {
                    $fglti_code=$fglti_code."'".$codes->fglti_code."',";
                }
                $fglti_code=rtrim($fglti_code,",");
          //echo $fglti_code;;
         if ($request->ajax()) {
                    
                
            
                
                
               // $fglti_codes=explode(",",$CPKIList->fglti_code);
              //  DB::enableQueryLog();  
              
            $FinishedGoodsStock = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, color_master.color_name,color_master.style_img_path, brand_master.brand_name, 
            size_detail.size_name, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty', mainstyle_name,
            (SELECT ifnull(sum(size_qty),0) from fg_location_transfer_inward_size_detail2 
            where color_id=packing_inhouse_size_detail2.color_id and 
            sales_order_no=packing_inhouse_size_detail2.sales_order_no 
            and size_id=packing_inhouse_size_detail2.size_id
            and  fg_location_transfer_inward_size_detail2.fglti_code in ($fglti_code)) as 'carton_pack_qty', order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
            LEFT JOIN color_master on color_master.color_id=packing_inhouse_size_detail2.color_id
            LEFT JOIN size_detail on size_detail.size_id = packing_inhouse_size_detail2.size_id
            LEFT JOIN main_style_master on main_style_master.mainstyle_id=packing_inhouse_size_detail2.mainstyle_id
            GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
    
            //   $query = DB::getQueryLog();
            //   $query = end($query);
            //   dd($query);
            
            return Datatables::of($FinishedGoodsStock)
            ->addIndexColumn()
            ->addColumn('Carton_Paking_Qty',function ($row) {
    
             $TransferPackingQty =($row->packing_grn_qty - $row->carton_pack_qty);
    
             return $TransferPackingQty;
           })
          ->addColumn('Value',function ($row) {
    
             $Value =($row->packing_grn_qty - $row->carton_pack_qty) * ($row->order_rate);
    
             return $Value;
           })
           
             ->rawColumns(['Carton_Paking_Qty','Value'])
             
             ->make(true);
    
            }
            
          return view('FGStockReport');
            
    }
     
     
    public function destroy($id)
    {
        DB::table('fg_location_transfer_inward_master')->where('fglti_code', $id)->delete();
        DB::table('fg_location_transfer_inward_size_detail2')->where('fglti_code', $id)->delete();
        DB::table('fg_location_transfer_inward_size_detail')->where('fglti_code', $id)->delete();
        DB::table('fg_location_transfer_inward_detail')->where('fglti_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function GetFGLocOutwardData(Request $request)
    {
        $html = ''; 
        $no1=1; 
        
        $masterOutward = LocTransferPackingInhouseMasterModel::select('*')->where('ltpki_code','=', $request->ltpki_code)->first();
        
        $OutwardData = DB::SELECT("SELECT loc_transfer_packing_inhouse_size_detail.*, color_master.color_name
                        FROM loc_transfer_packing_inhouse_size_detail
                        INNER JOIN color_master ON color_master.color_id = loc_transfer_packing_inhouse_size_detail.color_id
                        WHERE ltpki_code='".$request->ltpki_code."'");
        
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')
          ->where('buyer_purchse_order_master.tr_code',$OutwardData[0]->sales_order_no)->first();
         
       
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
        $sizes='';
        $sizes_id = '';
        
        foreach ($SizeDetailList as $sz) 
        {
              $sizes=$sizes.'s'.$no1.',';
              $sizes_id =$sizes_id.$sz->size_id.',';
              $no1++;
        }
        $sizes=rtrim($sizes,',');
        $sizes_Arr=rtrim($sizes_id,',');
      
        $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$OutwardData[0]->sales_order_no."'");
        
        
        $no=1;
        
        $html .='<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Color</th>';
                        foreach ($SizeDetailList as $sz) 
                        {
                              $html.='<th class="sth" >'.$sz->size_name.'</th>';
                        }
                        $html.='<th>Total Qty</th>
                    </tr>
                </thead>
           <tbody>';
          
        foreach($OutwardData as $row)
        {

            $MasterdataList1 = DB::select("select ifnull(sum(s1),0) as s1,ifnull(sum(s2),0) as s2,ifnull(sum(s3),0) as s3,ifnull(sum(s4),0) as s4,ifnull(sum(s5),0) as s5,ifnull(sum(s6),0) as s6,
                            ifnull(sum(s7),0) as s7,ifnull(sum(s8),0) as s8,ifnull(sum(s9),0) as s9,ifnull(sum(s10),0) as s10,ifnull(sum(s11),0) as s11,ifnull(sum(s12),0) as s12,ifnull(sum(s13),0) as s13,
                            ifnull(sum(s14),0) as s14,ifnull(sum(s15),0) as s15,ifnull(sum(s16),0) as s16,ifnull(sum(s17),0) as s17,ifnull(sum(s18),0) as s18,ifnull(sum(s19),0) as s19,ifnull(sum(s20),0) as s20,
                            ifnull(sum(size_qty_total),0) as size_qty_total 
                            from fg_location_transfer_inward_size_detail where ltpki_code='".$request->ltpki_code."' AND color_id=".$row->color_id);
                            
            $s1 = isset($MasterdataList1[0]->s1) ? $MasterdataList1[0]->s1 : 0;
            $s2 = isset($MasterdataList1[0]->s2) ? $MasterdataList1[0]->s2 : 0;
            $s3 = isset($MasterdataList1[0]->s3) ? $MasterdataList1[0]->s3 : 0;
            $s4 = isset($MasterdataList1[0]->s4) ? $MasterdataList1[0]->s4 : 0;
            $s5 = isset($MasterdataList1[0]->s5) ? $MasterdataList1[0]->s5 : 0;
            $s6 = isset($MasterdataList1[0]->s6) ? $MasterdataList1[0]->s6 : 0;
            $s7 = isset($MasterdataList1[0]->s7) ? $MasterdataList1[0]->s7 : 0;
            $s8 = isset($MasterdataList1[0]->s8) ? $MasterdataList1[0]->s8 : 0;
            $s9 = isset($MasterdataList1[0]->s9) ? $MasterdataList1[0]->s9 : 0;
            $s10 = isset($MasterdataList1[0]->s10) ? $MasterdataList1[0]->s10 : 0;
            $s11 = isset($MasterdataList1[0]->s11) ? $MasterdataList1[0]->s11 : 0;
            $s12 = isset($MasterdataList1[0]->s12) ? $MasterdataList1[0]->s12 : 0;
            $s13 = isset($MasterdataList1[0]->s13) ? $MasterdataList1[0]->s13 : 0;
            $s14 = isset($MasterdataList1[0]->s14) ? $MasterdataList1[0]->s14 : 0;
            $s15 = isset($MasterdataList1[0]->s15) ? $MasterdataList1[0]->s15 : 0;
            $s16 = isset($MasterdataList1[0]->s16) ? $MasterdataList1[0]->s16 : 0;
            $s17 = isset($MasterdataList1[0]->s17) ? $MasterdataList1[0]->s17 : 0;
            $s18 = isset($MasterdataList1[0]->s18) ? $MasterdataList1[0]->s18 : 0;
            $s19 = isset($MasterdataList1[0]->s19) ? $MasterdataList1[0]->s19 : 0;
            $s20 = isset($MasterdataList1[0]->s20) ? $MasterdataList1[0]->s20 : 0;
            $size_qty_total = isset($MasterdataList1[0]->size_qty_total) ? $MasterdataList1[0]->size_qty_total : 0;
            
            $html .='<tr>';
            $html .='<td><input type="text" name="id" value="'.$no.'" id="id" style="width:50px;height:30px;"/></td>';
            $html.='<td><input  name="item_codef[]"  type="hidden" id="item_code" value="" />
                        <input  name="color_id[]"  type="hidden" id="color_id" value="'.$row->color_id.'" />
                        <input  name="color_name[]" style="width:300px;" type="hidden" id="color_id" value="'.$row->color_name.'"  />'.$row->color_name.'
                   </td>';
          
              if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="'.($row->s1 - $s1).'" name="s1[]" class="size_id" onchange="checkNumber(this);" type="number" id="s1" value="'.($row->s1 - $s1).'" required /> '.($row->s1 - $s1).' <br/><br/><input type="number" step="any" name="s1_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s1_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;"   min="0" max="'.($row->s2 - $s2).'"  name="s2[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s2" value="'.($row->s2 - $s2).'" required /> '.($row->s2 - $s2).' <br/><br/><input type="number" step="any" name="s2_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s2_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';} 
              if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="'.($row->s3 - $s3).'"   name="s3[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s3" value="'.($row->s3 - $s3).'" required /> '.($row->s3 - $s3).' <br/><br/><input type="number" step="any" name="s3_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s3_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="'.($row->s4 - $s4).'"  name="s4[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s4" value="'.($row->s4 - $s4).'" required /> '.($row->s4 - $s4).' <br/><br/><input type="number" step="any" name="s4_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s4_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;" min="0" max="'.($row->s5 - $s5).'"  name="s5[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s5" value="'.($row->s5 - $s5).'" required /> '.($row->s5 - $s5).' <br/><br/><input type="number" step="any" name="s5_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s5_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" max="'.($row->s6 - $s6).'"   name="s6[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s6" value="'.($row->s6 - $s6).'" required /> '.($row->s6 - $s6).' <br/><br/><input type="number" step="any" name="s6_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s6_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s7 - $s7).'"  name="s7[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s7" value="'.($row->s7 - $s7).'" required /> '.($row->s7 - $s7).' <br/><br/><input type="number" step="any" name="s7_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s7_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s8 - $s8).'"  name="s8[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s8" value="'.($row->s8 - $s8).'" required /> '.($row->s8 - $s8).' <br/><br/><input type="number" step="any" name="s8_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s8_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s9 - $s9).'"  name="s9[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s9" value="'.($row->s9 - $s9).'" required /> '.($row->s9 - $s9).' <br/><br/><input type="number" step="any" name="s9_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s9_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s10 - $s10).'"  name="s10[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s10" value="'.($row->s10 - $s10).'" required /> '.($row->s10 - $s10).' <br/><br/><input type="number" step="any" name="s10_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s10_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s11 - $s11).'"  name="s11[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s11" value="'.($row->s11 - $s11).'" required /> '.($row->s11 - $s11).' <br/><br/><input type="number" step="any" name="s11_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s11_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s12 - $s12).'"  name="s12[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s12" value="'.($row->s12 - $s12).'" required /> '.($row->s12 - $s12).' <br/><br/><input type="number" step="any" name="s12_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s12_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s13 - $s13).'"  name="s13[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s13" value="'.($row->s13 - $s13).'" required /> '.($row->s13 - $s13).' <br/><br/><input type="number" step="any" name="s13_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s13_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;"  min="0" max="'.($row->s14 - $s14).'"  name="s14[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s14" value="'.($row->s14 - $s14).'" required /> '.($row->s14 - $s14).' <br/><br/><input type="number" step="any" name="s14_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s14_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s15 - $s15).'"  name="s15[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s15" value="'.($row->s15 - $s15).'" required /> '.($row->s15 - $s15).' <br/><br/><input type="number" step="any" name="s15_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s15_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s16 - $s16).'"  name="s16[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s16" value="'.($row->s16 - $s16).'" required /> '.($row->s16 - $s16).' <br/><br/><input type="number" step="any" name="s16_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s16_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s17 - $s17).'"  name="s17[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s17" value="'.($row->s17 - $s17).'" required /> '.($row->s17 - $s17).' <br/><br/><input type="number" step="any" name="s17_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s17_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s18 - $s18).'"  name="s18[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s18" value="'.($row->s18 - $s18).'" required /> '.($row->s18 - $s18).' <br/><br/><input type="number" step="any" name="s18_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s18_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" min="0" max="'.($row->s19 - $s19).'"   name="s19[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s19" value="'.($row->s19 - $s19).'" required /> '.($row->s19 - $s19).' <br/><br/><input type="number" step="any" name="s19_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s19_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;"  min="0" max="'.($row->s20 - $s20).'"  name="s20[]" type="number" class="size_id" onchange="checkNumber(this);"  id="s20" value="'.($row->s20 - $s20).'" required /> '.($row->s20 - $s20).' <br/><br/><input type="number" step="any" name="s20_rate[]" class="size_rate" placeholder="Rate" value="0"  style="width:80px;background: #ff000045;" required /><input type="hidden"  name="s20_barcode[]" class="barcode" value="'.$row->color_id.'" /></td>';}
              $html.='<td>
            <input type="number" name="size_qty_total[]" class="size_qty_total" value="'.($row->size_qty_total - $size_qty_total).'" id="size_qty_total" style="width:80px; height:30px; float:left;" readonly />
            <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
            <input type="hidden" name="size_array[]"  value="'.$row->size_array.'" id="size_array" style="width:80px;  float:left;"  />
            <input type="hidden" name="rate_array[]"  value="" id="rate_array" style="width:80px;  float:left;"  />'.($row->size_qty_total - $size_qty_total).'</td>';
            $html.='</tr>';
            $no=$no+1;
        }
        $html .= '</tbody>
                </table>';
        return response()->json(['html' => $html, 'sales_order_no' => $OutwardData[0]->sales_order_no,'total_qty'=> $masterOutward->total_qty,'from_loc_id'=> $masterOutward->from_loc_id,'to_loc_id'=> $masterOutward->to_loc_id]);
    }
    
    // protected $barcodeService;

    // public function __construct(BarcodeService $barcodeService)
    // {
    //     $this->barcodeService = $barcodeService;
    // }

    // public function generateBarcodeLabel($id)
    // {
    //     // Fetch data from the database
    //     $FGLocationTransferInwardData = DB::select("
    //         SELECT size_detail.size_name, sub_style_master.substyle_name, fg_location_transfer_inward_size_detail2.* 
    //         FROM fg_location_transfer_inward_size_detail2
    //         LEFT JOIN sub_style_master ON sub_style_master.substyle_id = fg_location_transfer_inward_size_detail2.substyle_id
    //         LEFT JOIN size_detail ON size_detail.size_id = fg_location_transfer_inward_size_detail2.size_id 
    //         WHERE fg_location_transfer_inward_size_detail2.fglti_code = ?
    //         LIMIT 1
    //     ", [$id]);
    
    //     if (empty($FGLocationTransferInwardData)) {
    //         return response('Data not found', 404);
    //     }
    
    //     $row = $FGLocationTransferInwardData[0];
    
    //     // Generate barcode
    //     $barcode = $this->barcodeService->getBarcode($id, $this->barcodeService::TYPE_CODE_128);
    
    //     // Create PDF or any output for barcode and label
    //     $pdf = app('dompdf.wrapper');
    //     $pdf->loadView('FGLocationTransferInwardBarcode', [
    //         'barcode' => $barcode,
    //         'substyle_name' => $row->substyle_name,
    //         'size_name' => $row->size_name,
    //         'size_qty' => sprintf("%.2f", $row->size_qty)
    //     ]);
    
    //     return $pdf->stream('barcode_label.pdf');
    // }


    // public function FGLocationTransferInwardBarcode($id)
    // {
    //     // Fetch data from the database (uncomment and adjust as needed)
    //     // $FGLocationTransferInwardData = DB::SELECT("SELECT size_detail.size_name, sub_style_master.substyle_name, fg_location_transfer_inward_size_detail2.* FROM fg_location_transfer_inward_size_detail2 
    //     //     LEFT JOIN sub_style_master ON sub_style_master.substyle_id = fg_location_transfer_inward_size_detail2.substyle_id
    //     //     LEFT JOIN size_detail ON size_detail.size_id = fg_location_transfer_inward_size_detail2.size_id 
    //     //     WHERE fg_location_transfer_inward_size_detail2.fglti_code='".$id."' LIMIT 1");
    
    //     // Generate barcode and save as PDF
    //     $filename = 'barcode.pdf';
    //     $this->generateBarcode("MS", $filename);
    
    //     // Return the view
    //     return view('FGLocationTransferInwardBarcode', ['filename' => $filename]);
    // }
    
    public function FGLocationTransferInwardBarcode($id)
    {
        $DetailData = DB::SELECT("SELECT fg_location_transfer_inward_size_detail2.*, main_style_master.mainstyle_name, size_detail.size_name
                        FROM fg_location_transfer_inward_size_detail2 
                        INNER JOIN main_style_master ON main_style_master.mainstyle_id = fg_location_transfer_inward_size_detail2.mainstyle_id 
                        INNER JOIN size_detail ON size_detail.size_id = fg_location_transfer_inward_size_detail2.size_id
                        WHERE fg_location_transfer_inward_size_detail2.fglti_code='".$id."' order by fg_location_transfer_inward_size_detail2.size_id,fg_location_transfer_inward_size_detail2.color_id");
    
        // Create a new PDF document with custom page size
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(38.1, 25.4), true, 'UTF-8', false);
        $pdf->SetMargins(0, 0, 0, 0);
        $pdf->SetAutoPageBreak(false);
    
        foreach($DetailData as $row)
        {
            for($i=0; $i < $row->size_qty; $i++)
            {
                // Add a new page for each barcode
                $pdf->AddPage('L', array(38.1, 25.4));
                
                // Generate individual barcode and add it directly to the PDF
                $this->addBarcodeToPDF($pdf, $row->mainstyle_name, $row->size_rate . "  Size: " . $row->size_name, "C39", $row->barcode);
                    
            }
        }
    
        // Output the final PDF
        $output = $pdf->Output('', 'S');
    
        // Encode the combined PDF to base64
        $base64Barcode = base64_encode($output);
        return view('FGLocationTransferInwardBarcode', ['generateBarcode' => $base64Barcode]);
    }
    
    public function addBarcodeToPDF($pdf, $styleNo, $rate, $barcodeType = 'C39', $barcode)
    {
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 0,
            'vpadding' => 0,
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false,
            'text' => true, // Display the barcode text below the barcode
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 1
        );
    
        try {
            $barcodeX = 2;  // Position the barcode with a slight margin from the left
            $barcodeY = 3.5;  // Position the barcode with a slight margin from the top
            $barcodeWidth = 36; // Fit barcode width to the page size
            $barcodeHeight = 12; // Adjust height to fit within the page
            
            // Add the barcode to the PDF
            $pdf->write1DBarcode($barcode, $barcodeType, $barcodeX, $barcodeY, $barcodeWidth, $barcodeHeight, 0.4, $style, 'N');
    
            // Set font for additional text
            $pdf->SetFont('helvetica', '', 6); // Use a smaller font size to fit
    
            // $detailsTop = "KEN GLOBAL DESIGNS PVT. LTD.";
            $detailsBottom = [
                "$styleNo",
                "Rate: $rate"
            ];
    
            // Add text above the barcode
            $textYTop = $barcodeY - 29; // Adjust the top text position
            $pdf->SetXY($barcodeX, $textYTop);
            // $pdf->Cell(0, 0, $detailsTop, 0, 1, 'C');
    
            $pdf->SetFont('helvetica', '', 8.5); // Use a smaller font size to fit
            // Add text below the barcode
            $textYBottom = $barcodeY + $barcodeHeight + 2; // Adjust the bottom text position
            foreach ($detailsBottom as $i => $line) {
                $pdf->SetXY($barcodeX, $textYBottom + ($i * 3)); // Position each line of text below the barcode
                $pdf->Cell(0, 0, $line, 0, 1, 'C');
            }
    
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
    
    public function OutletInwardReport(Request $request)
    {
        
        $filter = '';
        $filter1 = '';
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate : date('Y-m-d');
        
        if($fromDate != '' && $toDate != '')
        {
            $filter .= " AND fg_location_transfer_inward_size_detail2.fglti_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        if($fromDate != '' && $toDate != '')
        {
            $filter1 .= " AND fg_outlet_opening_size_detail2.fgo_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
       $OutletInwardData = DB::SELECT("SELECT fg_location_transfer_inward_size_detail2.fglti_date,
                                       fg_location_transfer_inward_size_detail2.fglti_code,
                                       fg_location_transfer_inward_size_detail2.sales_order_no,
                                       brand_master.brand_name,
                                       fg_location_transfer_inward_size_detail2.style_no,
                                       fg_location_transfer_inward_size_detail2.style_description,
                                       fg_location_transfer_inward_size_detail2.size_rate,
                                       sub_style_master.substyle_name,
                                       fg_master.fg_name,
                                       main_style_master.mainstyle_name,
                                       size_detail.size_name,
                                       loc1.location AS from_loc,
                                       loc2.location AS to_loc,
                                       color_master.color_name,
                                       ledger_master.ac_short_name,
                                       SUM(fg_location_transfer_inward_size_detail2.size_qty) AS size_qty 
                                FROM fg_location_transfer_inward_size_detail2 
                                LEFT JOIN main_style_master ON main_style_master.mainstyle_id = fg_location_transfer_inward_size_detail2.mainstyle_id 
                                LEFT JOIN sub_style_master ON sub_style_master.substyle_id = fg_location_transfer_inward_size_detail2.substyle_id 
                                LEFT JOIN fg_master ON fg_master.fg_id = fg_location_transfer_inward_size_detail2.fg_id 
                                LEFT JOIN ledger_master ON ledger_master.ac_code = fg_location_transfer_inward_size_detail2.Ac_code
                                LEFT JOIN size_detail ON size_detail.size_id = fg_location_transfer_inward_size_detail2.size_id
                                LEFT JOIN location_master AS loc1 ON loc1.loc_id = fg_location_transfer_inward_size_detail2.from_loc_id
                                LEFT JOIN location_master AS loc2 ON loc2.loc_id = fg_location_transfer_inward_size_detail2.to_loc_id
                                LEFT JOIN color_master ON color_master.color_id = fg_location_transfer_inward_size_detail2.color_id 
                                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = fg_location_transfer_inward_size_detail2.sales_order_no 
                                LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
                                WHERE 1 " . $filter . " 
                                GROUP BY fg_location_transfer_inward_size_detail2.fglti_code, 
                                         fg_location_transfer_inward_size_detail2.size_id, 
                                         fg_location_transfer_inward_size_detail2.color_id
                                
                                UNION ALL
                                
                                SELECT fg_outlet_opening_size_detail2.fgo_date AS fglti_date,
                                       fg_outlet_opening_size_detail2.fgo_code AS fglti_code,
                                       '-' AS sales_order_no,
                                       '-' AS brand_name,
                                       '-' AS style_no,
                                       '-' AS style_description,
                                       fg_outlet_opening_size_detail2.size_rate,
                                       '-' AS substyle_name,
                                       '-' AS fg_name,
                                       main_style_master.mainstyle_name,
                                       size_detail.size_name,
                                       '-' AS from_loc,
                                       '-' AS to_loc,
                                       color_master.color_name,
                                       ledger_master.ac_short_name,
                                       SUM(fg_outlet_opening_size_detail2.size_qty) AS size_qty 
                                FROM fg_outlet_opening_size_detail2 
                                LEFT JOIN main_style_master ON main_style_master.mainstyle_id = fg_outlet_opening_size_detail2.mainstyle_id 
                                LEFT JOIN ledger_master ON ledger_master.ac_code = fg_outlet_opening_size_detail2.Ac_code
                                LEFT JOIN size_detail ON size_detail.size_id = fg_outlet_opening_size_detail2.size_id
                                LEFT JOIN color_master ON color_master.color_id = fg_outlet_opening_size_detail2.color_id 
                                WHERE 1 " .$filter1. " 
                                GROUP BY fg_outlet_opening_size_detail2.fgo_code, 
                                         fg_outlet_opening_size_detail2.size_id, 
                                         fg_outlet_opening_size_detail2.color_id");

                        
        return view('OutletInwardReport', compact('OutletInwardData', 'fromDate', 'toDate'));
    }

    public function OutletStockReport(Request $request)
    {
        
        $filter = '';
        $filter1 = '';
        
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate : date('Y-m-d');
        $mainstyle_id = isset($request->mainstyle_id) ? $request->mainstyle_id : 0;
        if($fromDate != '' && $toDate != '')
        {
            $filter .= " AND fg_location_transfer_inward_size_detail2.fglti_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        
        if($mainstyle_id > 0)
        {
            $filter .= " AND fg_location_transfer_inward_size_detail2.mainstyle_id ='".$mainstyle_id."'";
        }
        
        if($fromDate != '' && $toDate != '')
        {
            $filter1 .= " AND fg_outlet_opening_size_detail2.fgo_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        if($mainstyle_id > 0)
        {
            $filter1 .= " AND fg_outlet_opening_size_detail2.mainstyle_id ='".$mainstyle_id."'";
        }
        
       $OutletInwardData = DB::SELECT("SELECT fg_location_transfer_inward_size_detail2.size_id, fg_location_transfer_inward_size_detail2.color_id, 
                                       fg_location_transfer_inward_size_detail2.Ac_code, fg_location_transfer_inward_size_detail2.fglti_date,
                                       fg_location_transfer_inward_size_detail2.barcode,
                                       fg_location_transfer_inward_size_detail2.fglti_code,
                                       fg_location_transfer_inward_size_detail2.size_rate,
                                       sub_style_master.substyle_name,
                                       main_style_master.mainstyle_name,
                                       size_detail.size_name,
                                       color_master.color_name,
                                       ledger_master.ac_short_name,
                                       SUM(outlet_sale_detail.qty) AS outward_qty
                                FROM fg_location_transfer_inward_size_detail2 
                                LEFT JOIN main_style_master ON main_style_master.mainstyle_id = fg_location_transfer_inward_size_detail2.mainstyle_id 
                                LEFT JOIN sub_style_master ON sub_style_master.substyle_id = fg_location_transfer_inward_size_detail2.substyle_id 
                                LEFT JOIN ledger_master ON ledger_master.ac_code = fg_location_transfer_inward_size_detail2.Ac_code
                                LEFT JOIN size_detail ON size_detail.size_id = fg_location_transfer_inward_size_detail2.size_id
                                LEFT JOIN color_master ON color_master.color_id = fg_location_transfer_inward_size_detail2.color_id 
                                LEFT JOIN outlet_sale_detail ON outlet_sale_detail.scan_barcode = fg_location_transfer_inward_size_detail2.barcode 
                                WHERE 1 " . $filter . " 
                                GROUP BY fg_location_transfer_inward_size_detail2.fglti_code, 
                                         fg_location_transfer_inward_size_detail2.size_id, 
                                         fg_location_transfer_inward_size_detail2.color_id
                                
                                UNION ALL
                                
                                SELECT fg_outlet_opening_size_detail2.size_id, fg_outlet_opening_size_detail2.color_id, 
                                       fg_outlet_opening_size_detail2.Ac_code, fg_outlet_opening_size_detail2.fgo_date AS fglti_date,
                                       fg_outlet_opening_size_detail2.barcode,
                                       fg_outlet_opening_size_detail2.fgo_code AS fglti_code,
                                       fg_outlet_opening_size_detail2.size_rate,
                                       '-' AS substyle_name,
                                       main_style_master.mainstyle_name,
                                       size_detail.size_name,
                                       color_master.color_name,
                                       ledger_master.ac_short_name, 
                                       SUM(outlet_sale_detail.qty) AS outward_qty
                                FROM fg_outlet_opening_size_detail2 
                                LEFT JOIN main_style_master ON main_style_master.mainstyle_id = fg_outlet_opening_size_detail2.mainstyle_id 
                                LEFT JOIN ledger_master ON ledger_master.ac_code = fg_outlet_opening_size_detail2.Ac_code
                                LEFT JOIN size_detail ON size_detail.size_id = fg_outlet_opening_size_detail2.size_id
                                LEFT JOIN color_master ON color_master.color_id = fg_outlet_opening_size_detail2.color_id 
                                LEFT JOIN outlet_sale_detail ON outlet_sale_detail.scan_barcode = fg_outlet_opening_size_detail2.barcode
                                WHERE 1 " .$filter1. " 
                                GROUP BY fg_outlet_opening_size_detail2.fgo_code, 
                                         fg_outlet_opening_size_detail2.size_id, 
                                         fg_outlet_opening_size_detail2.color_id");

                        
        return view('OutletStockReport', compact('OutletInwardData', 'MainStyleList', 'fromDate', 'toDate', 'mainstyle_id'));
    }
    
}
