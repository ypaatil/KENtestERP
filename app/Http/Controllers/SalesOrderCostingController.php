<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\SalesOrderFabricCostingDetailModel;
use App\Models\SeasonModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ClassificationModel;
use App\Models\CurrencyModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\QualityModel;
use App\Models\BrandModel;
use App\Models\PDMerchantMasterModel;

use App\Models\SalesOrderSewingTrimsCostingDetailModel;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrderCostingMasterModel;
use App\Models\SalesOrderPackingTrimsCostingDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use Session;
use DataTables;
use Log;

setlocale(LC_MONETARY, 'en_IN');
date_default_timezone_set('Asia/Calcutta');

use App\Services\SalesOrderFabricCostingDetailActivityLog;
use App\Services\SalesOrderCostingMasterActivityLog;



class SalesOrderCostingController extends Controller
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
            ->where('form_id', '88')
            ->first();

        $userId = Session::get('userId');

        if ($request->page == 1) {
            // $SalesOrderCostingList = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId', 'left outer')
            //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code', 'left outer')
            //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id', 'left outer')
            //     ->join('fg_master', 'fg_master.fg_id', '=', 'sales_order_costing_master.fg_id', 'left outer')
            //     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no', 'left outer')
            //     ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
            //     ->where('sales_order_costing_master.delflag','=', '0')
            //     ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.ac_short_name',  'fg_master.fg_name','main_style_master.mainstyle_name' , 'job_status_master.job_status_name']);

            $SalesOrderCostingList = DB::SELECT("SELECT sales_order_costing_master.*,usermaster.username,ledger_master.ac_short_name, fg_master.fg_name, buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO, 
                                     main_style_master.mainstyle_name, job_status_master.job_status_name,approve_master.approve_yes_no,brand_master.brand_name
                                     FROM sales_order_costing_master 
                                     LEFT JOIN usermaster ON usermaster.userId = sales_order_costing_master.userId 
                                     LEFT JOIN ledger_master ON ledger_master.Ac_code = sales_order_costing_master.Ac_code
                                     LEFT JOIN main_style_master ON main_style_master.mainstyle_id = sales_order_costing_master.mainstyle_id
                                     LEFT JOIN fg_master ON fg_master.fg_id = sales_order_costing_master.fg_id
                                     LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                     LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                                     LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                                     LEFT JOIN approve_master ON approve_master.approve_id = sales_order_costing_master.is_approved
                                     WHERE sales_order_costing_master.delflag = 0 AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=" . $userId . ")
                                     order by sales_order_costing_master.soc_date DESC");
        } else {
            //   $SalesOrderCostingList = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId', 'left outer')
            //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code', 'left outer')
            //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id', 'left outer')
            //     ->join('fg_master', 'fg_master.fg_id', '=', 'sales_order_costing_master.fg_id', 'left outer')
            //     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no', 'left outer')
            //     ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
            //     ->where('sales_order_costing_master.delflag','=', '0') 
            //     ->where('sales_order_costing_master.soc_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
            //     ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.ac_short_name',  'fg_master.fg_name','main_style_master.mainstyle_name' , 'job_status_master.job_status_name']);

            $SalesOrderCostingList = DB::SELECT("SELECT sales_order_costing_master.*,usermaster.username,ledger_master.ac_short_name, buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
                     fg_master.fg_name, main_style_master.mainstyle_name, job_status_master.job_status_name,approve_master.approve_yes_no,brand_master.brand_name
                     FROM sales_order_costing_master
                     LEFT JOIN usermaster ON usermaster.userId = sales_order_costing_master.userId 
                     LEFT JOIN ledger_master ON ledger_master.Ac_code = sales_order_costing_master.Ac_code
                     LEFT JOIN main_style_master ON main_style_master.mainstyle_id = sales_order_costing_master.mainstyle_id
                     LEFT JOIN fg_master ON fg_master.fg_id = sales_order_costing_master.fg_id
                     LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                     LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                     LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                     LEFT JOIN approve_master ON approve_master.approve_id = sales_order_costing_master.is_approved
                     WHERE sales_order_costing_master.delflag = 0 OR sales_order_costing_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=" . $userId . ") 
                     AND sales_order_costing_master.soc_date > LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)  order by sales_order_costing_master.soc_date DESC");
        }


        if ($request->ajax()) {
            return Datatables::of($SalesOrderCostingList)
                ->addIndexColumn()
                ->addColumn('soc_code1', function ($row) {
                    static $serial = 0;
                    $serial++;
                    return $serial;
                })
                ->addColumn('entry_date', function ($row) {

                    $entry_date = date("d/m/Y", strtotime($row->soc_date));

                    return $entry_date;
                })
                ->addColumn('order_rate', function ($row) {

                    $order_rate = sprintf('%.2f', $row->order_rate);

                    return $order_rate;
                })
                ->addColumn('action1', function ($row) {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="GetCostingData/' . $row->soc_code . '" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                    return $btn1;
                })
                ->addColumn('updated_at', function ($row) {

                    $updated_at = date("d/m/Y h:i:s", strtotime($row->updated_at));

                    return $updated_at;
                })
                ->addColumn('action2', function ($row) use ($chekform) {
                    if ($chekform->edit_access == 1 or Session::get('user_type') == 1) {
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="' . route('SalesOrderCosting.edit', $row->soc_code) . '" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                    } else {
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform) {
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm edit"  href="' . route('RepeatSalesOrderCosting', $row->soc_code) . '"  title="Edit">
                                  <i class="fas fa-plus"></i>
                           </a>';
                    return $btn3;
                })
                ->addColumn('action4', function ($row) use ($chekform) {

                    if ($chekform->delete_access == 1 && $row->username == Session::get('username') or Session::get('user_type') == 1) {

                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="' . csrf_token() . '" data-id="' . $row->soc_code . '"  data-route="' . route('SalesOrderCosting.destroy', $row->soc_code) . '"><i class="fas fa-trash"></i></a>';
                    } else {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>';
                    }
                    return $btn4;
                })
                ->addColumn('isMarketing', function ($row) {

                    $isMarketing = $row->isMarketing;
                    if ($isMarketing == 1) {
                        $status = 'Approved';
                    } else {
                        $status = 'Not Approved';
                    }
                    return $status;
                })
                ->addColumn('isCEO', function ($row) {

                    $isCEO = $row->isCEO;
                    if ($isCEO == 1) {
                        $status = 'Approved';
                    } else {
                        $status = 'Not Approved';
                    }
                    return $status;
                })
                ->rawColumns(['action1', 'action2', 'action3', 'action4', 'updated_at', 'entry_date', 'isMarketing', 'order_rate', 'isCEO'])

                ->make(true);
        }
        return view('SalesOrderCostingMasterList', compact('chekform'));
    }

    public function SalesOrderCostingDuplicate(Request $request)
    {

        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '88')
            ->first();

        if ($request->page == 1) {
            $SalesOrderCostingList = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId', 'left outer')
                ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code', 'left outer')
                ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id', 'left outer')
                ->join('fg_master', 'fg_master.fg_id', '=', 'sales_order_costing_master.fg_id', 'left outer')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no', 'left outer')
                ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
                ->where('sales_order_costing_master.delflag', '=', '0')
                ->get(['sales_order_costing_master.*', 'usermaster.username', 'ledger_master.Ac_name',  'fg_master.fg_name', 'main_style_master.mainstyle_name', 'job_status_master.job_status_name']);
        } else {
            $SalesOrderCostingList = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId', 'left outer')
                ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code', 'left outer')
                ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id', 'left outer')
                ->join('fg_master', 'fg_master.fg_id', '=', 'sales_order_costing_master.fg_id', 'left outer')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no', 'left outer')
                ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
                ->where('sales_order_costing_master.delflag', '=', '0')
                ->where('sales_order_costing_master.soc_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
                ->get(['sales_order_costing_master.*', 'usermaster.username', 'ledger_master.Ac_name',  'fg_master.fg_name', 'main_style_master.mainstyle_name', 'job_status_master.job_status_name']);
        }


        if ($request->ajax()) {
            return Datatables::of($SalesOrderCostingList)
                ->addIndexColumn()
                ->addColumn('soc_code1', function ($row) {

                    $sale_codeData = substr($row->soc_code, 4, 15);

                    return $sale_codeData;
                })
                ->addColumn('action1', function ($row) {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="GetCostingData/' . $row->soc_code . '" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform) {
                    if ($chekform->edit_access == 1) {
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="' . route('SalesOrderCosting.edit', $row->soc_code) . '" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                    } else {
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform) {
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm edit"  href="' . route('RepeatSalesOrderCosting', $row->soc_code) . '"  title="Edit">
                                  <i class="fas fa-plus"></i>
                           </a>';
                    return $btn3;
                })
                ->addColumn('action4', function ($row) use ($chekform) {

                    if ($chekform->delete_access == 1) {

                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="' . csrf_token() . '" data-id="' . $row->soc_code . '"  data-route="' . route('SalesOrderCosting.destroy', $row->soc_code) . '"><i class="fas fa-trash"></i></a>';
                    } else {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>';
                    }
                    return $btn4;
                })
                ->rawColumns(['action1', 'action2', 'action3', 'action4'])

                ->make(true);
        }
        return view('SalesOrderCostingDuplicate', compact('chekform'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BUYER_JOB_CARD'");
        $ColorList = ColorModel::where('color_master.delflag', '=', '0')->get();
        $CPList = DB::table('cp_master')->get();
        $CostTypeList = DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag', '=', '0')->where('ledger_master.ac_code', '>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag', '=', '0')->get();
        $ClassList = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '1')->orderBy('class_name', 'ASC')->get();
        $ClassList2 = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '2')->orderBy('class_name', 'ASC')->get();
        $ClassList3 = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '3')->orderBy('class_name', 'ASC')->get();
        $BrandList = BrandModel::select('*')->get();
        $QualityList = QualityModel::where('quality_master.delflag', '=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag', '=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag', '=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag', '=', '0')->get();
        $PDMerchantList = PDMerchantMasterModel::where('PDMerchant_master.delflag', '=', '0')->get();
        $OrderTypeList = DB::table('order_type_master')->where('delflag', '=', '0')->get();
        $OrderGroupList = DB::table('order_group_master')->where('delflag', '=', '0')->get();
        //DB::enableQueryLog();
        // $SalesOrderList= BuyerPurchaseOrderMasterModel::whereNotIn('buyer_purchse_order_master.tr_code',function($query){
        //       $query->select('sales_order_no')->from('sales_order_costing_master');
        //     })->get();
        $SalesOrderList = DB::SELECT("SELECT * FROM buyer_purchse_order_master WHERE tr_code NOT IN (SELECT sales_order_no FROM sales_order_costing_master WHERE sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code) AND job_status_id = 1");
        // $SalesOrderList = DB::SELECT("SELECT * FROM sales_order_costing_master WHERE sales_order_no IN (SELECT tr_code FROM buyer_purchse_order_master WHERE job_status_id = 1)"); 
        //dd(DB::getQueryLog());
        return view('SalesOrderCostingMaster', compact('OrderTypeList', 'OrderGroupList', 'ClassList', 'PDMerchantList', 'ClassList2', 'ClassList3', 'MainStyleList', 'SubStyleList', 'FGList', 'CostTypeList', 'SalesOrderList', 'Ledger', 'QualityList', 'CPList', 'CurrencyList', 'BrandList', 'ColorList', 'counter_number'));
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
            ->where('c_name', '=', 'C1')
            ->where('type', '=', 'SALES_ORDER_COSTING')
            ->where('firm_id', '=', 1)
            ->first();
        $TrNo = $codefetch->code . '-' . $codefetch->tr_no;



        $this->validate($request, [

            'soc_date' => 'required',
            'Ac_code' => 'required',
            'agent_commission_value' => 'required',
            'total_cost_value' => 'required',
            'other_value' => 'required',
            'production_value' => 'required',
            'fabric_value' => 'required',
            'sewing_trims_value' => 'required',
            'packing_trims_value' => 'required',

        ]);

        if (strpos($request->sales_order_no, 's') !== false) {
            $approve = 2;
        } else {
            $approve = 0;
        }

        $data1 = array(

            'soc_code' => $TrNo,
            'soc_date' => $request->soc_date,
            'cost_type_id' => $request->cost_type_id,
            'sales_order_no' => $request->sales_order_no,
            'Ac_code' => $request->Ac_code,
            'season_id' => $request->season_id,
            'brand_id' => $request->brand_id,
            'currency_id' => $request->currency_id,
            'mainstyle_id' => $request->mainstyle_id,
            'substyle_id' => $request->substyle_id,
            'fg_id' => $request->fg_id,
            'style_no' => $request->style_no,
            'style_description' => $request->style_description,
            'order_rate' => $request->order_rate,
            'exchange_rate' => $request->exchange_rate,
            'inr_rate' => $request->inr_rate,
            'sam' => $request->sam,
            'transport_ocr_cost' => $request->transport_ocr_cost,
            'testing_ocr_cost' => $request->testing_ocr_cost,
            'fabric_value' => $request->fabric_value,
            'sewing_trims_value' => $request->sewing_trims_value,
            'packing_trims_value' => $request->packing_trims_value,
            'production_value' => $request->production_value,
            'other_value' => $request->other_value,
            'transaport_value' => $request->transport_value,
            'agent_commision_value' => $request->agent_commission_value,
            'dbk_value' => $request->dbk_value,
            'dbk_value1' => $request->dbk_value1,
            'printing_value' => $request->printing_value,
            'embroidery_value' => $request->embroidery_value,
            'ixd_value' => $request->ixd_value,
            'total_making_value' => $request->total_making_value,
            'total_making_per' => $request->total_making_per,
            'garment_reject_value' => $request->garment_reject_value,
            'testing_charges_value' => $request->testing_charges_value,
            'finance_cost_value' => $request->finance_cost_value,
            'extra_value' => $request->extra_value,
            'total_cost_value' => $request->total_cost_value,
            'narration' => $request->narration,
            'is_approved' => $approve,
            'userId' => $request->userId,
            'delflag' => '0',
            'c_code' => $request->c_code,
            'PDMerchant_id' => $request->PDMerchant_id

        );

        SalesOrderCostingMasterModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='SALES_ORDER_COSTING'");

        $class_id = $request->input('class_id');
        if (count($class_id) > 0) {

            for ($x = 0; $x < count($class_id); $x++) {
                # code...
                $data2[] = array(

                    'soc_code' => $TrNo,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_id[$x],
                    'description' => $request->description[$x],
                    'consumption' => $request->consumption[$x],
                    'rate_per_unit' => $request->rate_per_unit[$x],
                    'wastage' => $request->wastage[$x],
                    'bom_qty' => $request->bom_qty[$x],
                    'total_amount' => $request->total_amount[$x],

                );
            }
            SalesOrderFabricCostingDetailModel::insert($data2);
        }

        $class_ids = $request->input('class_ids');
        if (count($class_ids) > 0) {
            for ($x = 0; $x < count($class_ids); $x++) {
                # code...
                $data3[] = array(

                    'soc_code' => $TrNo,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_ids[$x],
                    'description' => $request->descriptions[$x],
                    'consumption' => $request->consumptions[$x],
                    'rate_per_unit' => $request->rate_per_units[$x],
                    'wastage' => $request->wastages[$x],
                    'bom_qty' => $request->bom_qtys[$x],
                    'total_amount' => $request->total_amounts[$x],

                );
            }
            SalesOrderSewingTrimsCostingDetailModel::insert($data3);
        }

        $class_idss = $request->input('class_idss');
        if (count($class_idss) > 0) {
            for ($x = 0; $x < count($class_idss); $x++) {
                # code...
                $data4[] = array(

                    'soc_code' => $TrNo,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_idss[$x],
                    'description' => $request->descriptionss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x],
                    'bom_qty' => $request->bom_qtyss[$x],
                    'total_amount' => $request->total_amountss[$x],

                );
            }
            SalesOrderPackingTrimsCostingDetailModel::insert($data4);
        }


        return redirect()->route('SalesOrderCosting.index')->with('message', 'Data Saved Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(SalesOrderCostingMasterModel $SalesOrderCostingMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ApproveMasterList = DB::table('approve_master')->get();
        $CPList = DB::table('cp_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag', '=', '0')->where('ledger_master.ac_code', '>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag', '=', '0')->get();
        $ClassList = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '1')->orderBy('class_name', 'ASC')->get();
        $ClassList2 = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '2')->orderBy('class_name', 'ASC')->get();
        $ClassList3 = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '3')->orderBy('class_name', 'ASC')->get();
        $BrandList = BrandModel::select('*')->get();
        $QualityList = QualityModel::where('quality_master.delflag', '=', '0')->get();
        $CostTypeList = DB::table('costing_type_master')->get();
        //DB::enableQueryLog();
        $SalesOrderCostingMasterList = SalesOrderCostingMasterModel::find($id);
        //dd(DB::getQueryLog());
        $FabricList = SalesOrderFabricCostingDetailModel::where('sales_order_fabric_costing_details.soc_code', '=', $SalesOrderCostingMasterList->soc_code)->get();
        $SewingTrimsList = SalesOrderSewingTrimsCostingDetailModel::where('sales_order_sewing_trims_costing_details.soc_code', '=', $SalesOrderCostingMasterList->soc_code)->get();
        $PackingTrimsList = SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code', '=', $SalesOrderCostingMasterList->soc_code)->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag', '=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag', '=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag', '=', '0')->get();
        $PDMerchantList = PDMerchantMasterModel::where('PDMerchant_master.delflag', '=', '0')->get();
        $sales_order_no = $SalesOrderCostingMasterList->sales_order_no;

        $S1 = BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->whereNotIn('buyer_purchse_order_master.tr_code', function ($query) {
            $query->select('sales_order_no')->from('sales_order_costing_master');
        });


        $S2 = SalesOrderCostingMasterModel::select('sales_order_no')->where('sales_order_no', $sales_order_no);
        //    
        //DB::enableQueryLog();
        $SalesOrderList = $S1->union($S2)->get();
        //dd(DB::getQueryLog());

        $is_approved = 0;
        if ($SalesOrderCostingMasterList->is_approved == 1) {
            $is_approved = 1;
        } else {
            $is_approved = 2;
        }

        if ($SalesOrderCostingMasterList->is_approved == 2) {
            $disableClass = 'disabled';
        } else {
            $disableClass = '';
        }

        $OrderTypeList = DB::table('order_type_master')->where('delflag', '=', '0')->get();
        $OrderGroupList = DB::table('order_group_master')->where('delflag', '=', '0')->get();

        return view('SalesOrderCostingMasterEdit', compact('OrderTypeList', 'OrderGroupList', 'disableClass', 'is_approved', 'ApproveMasterList', 'PDMerchantList', 'ClassList', 'ClassList2', 'ClassList3', 'MainStyleList', 'SubStyleList', 'FGList', 'CostTypeList', 'SalesOrderList', 'SalesOrderCostingMasterList', 'FabricList', 'SewingTrimsList', 'PackingTrimsList', 'Ledger', 'QualityList', 'CPList', 'CurrencyList', 'BrandList'));
    }

    public function RepeatSalesOrderCosting($id)
    {
        $ApproveMasterList = DB::table('approve_master')->get();
        $CPList = DB::table('cp_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag', '=', '0')->where('ledger_master.ac_code', '>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag', '=', '0')->get();
        $ClassList = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '1')->orderBy('class_name', 'ASC')->get();
        $ClassList2 = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '2')->orderBy('class_name', 'ASC')->get();
        $ClassList3 = ClassificationModel::where('delflag', '=', '0')->where('cat_id', '=', '3')->orderBy('class_name', 'ASC')->get();
        $SeasonList = SeasonModel::where('season_master.delflag', '=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag', '=', '0')->get();
        $CostTypeList = DB::table('costing_type_master')->get();
        // DB::enableQueryLog();

        $SalesOrderCostingMasterList = SalesOrderCostingMasterModel::select("sales_order_costing_master.*", "buyer_purchse_order_master.og_id", "buyer_purchse_order_master.order_type", "buyer_purchse_order_master.brand_id", "buyer_purchse_order_master.total_qty")->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')->find($id);

        // dd(DB::getQueryLog());
        $FabricList = SalesOrderFabricCostingDetailModel::where('sales_order_fabric_costing_details.soc_code', '=', $SalesOrderCostingMasterList->soc_code)->get();
        $SewingTrimsList = SalesOrderSewingTrimsCostingDetailModel::where('sales_order_sewing_trims_costing_details.soc_code', '=', $SalesOrderCostingMasterList->soc_code)->get();
        $PackingTrimsList = SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code', '=', $SalesOrderCostingMasterList->soc_code)->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag', '=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag', '=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag', '=', '0')->get();

        $BrandList = BrandModel::select('*')->get();
        $sales_order_no = $SalesOrderCostingMasterList->sales_order_no;
        //   DB::enableQueryLog();
        $SalesOrderList = DB::SELECT("SELECT * FROM buyer_purchse_order_master WHERE tr_code NOT IN (SELECT sales_order_no FROM sales_order_costing_master WHERE sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code) AND job_status_id = 1");

        //  $S1= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->whereNotIn('buyer_purchse_order_master.tr_code',function($query){
        //       $query->select('sales_order_no')->from('sales_order_costing_master');
        //     });
        //     $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        // 

        // $S2=SalesOrderCostingMasterModel::select('sales_order_no')->where('sales_order_no',$sales_order_no);
        //    

        //  $SalesOrderList = $S1->get();


        $is_approved = 0;
        if ($SalesOrderCostingMasterList->is_approved == 1) {
            $is_approved = 1;
        } else {
            $is_approved = 2;
        }
        //echo $is_approved;

        // exit;

        $OrderTypeList = DB::table('order_type_master')->where('delflag', '=', '0')->get();
        $OrderGroupList = DB::table('order_group_master')->where('delflag', '=', '0')->get();

        return view('RepeatSalesOrderCostingMasterEdit', compact('OrderTypeList', 'OrderGroupList', 'BrandList', 'is_approved', 'ApproveMasterList', 'ClassList', 'ClassList2', 'ClassList3', 'MainStyleList', 'SubStyleList', 'FGList', 'CostTypeList', 'SalesOrderList', 'SalesOrderCostingMasterList', 'FabricList', 'SewingTrimsList', 'PackingTrimsList', 'Ledger', 'QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $soc_code, SalesOrderFabricCostingDetailActivityLog $loggerDetail, SalesOrderCostingMasterActivityLog $loggerMaster)
    {


        $SalesOrderCostingList = SalesOrderCostingMasterModel::findOrFail($soc_code);

        //echo '<pre>';print_r($_POST);exit;
        $this->validate($request, [

            'soc_date' => 'required',
            'Ac_code' => 'required',

            'agent_commission_value' => 'required',
            'total_cost_value' => 'required',
            'other_value' => 'required',
            'production_value' => 'required',
            'fabric_value' => 'required',
            'sewing_trims_value' => 'required',
            'packing_trims_value' => 'required',

        ]);

        if (strpos($request->sales_order_no, 's') !== false) {
            $approve = 2;
        } else {
            $approve = $request->is_approved;
        }

        $data = array(
            'soc_code' => $soc_code,
            'soc_date' => $request->soc_date,
            'cost_type_id' => $request->cost_type_id,
            'sales_order_no' => $request->sales_order_no,
            'Ac_code' => $request->Ac_code,
            'season_id' => $request->season_id,
            'brand_id' => $request->brand_id,
            'currency_id' => $request->currency_id,
            'mainstyle_id' => $request->mainstyle_id,
            'substyle_id' => $request->substyle_id,
            'fg_id' => $request->fg_id,
            'style_no' => $request->style_no,
            'style_description' => $request->style_description,
            'order_rate' => $request->order_rate,
            'exchange_rate' => $request->exchange_rate,
            'inr_rate' => $request->inr_rate,
            'sam' => $request->sam,
            'transport_ocr_cost' => $request->transport_ocr_cost,
            'testing_ocr_cost' => $request->testing_ocr_cost,
            'fabric_value' => isset($request->fabric_value) && !is_nan((float)$request->fabric_value) ? $request->fabric_value : 0,
            'sewing_trims_value' => isset($request->sewing_trims_value) && !is_nan((float)$request->sewing_trims_value) ? $request->sewing_trims_value : 0,
            'packing_trims_value' => isset($request->packing_trims_value) && !is_nan((float)$request->packing_trims_value) ? $request->packing_trims_value : 0,
            'production_value' => isset($request->production_value) && !is_nan((float)$request->production_value) ? $request->production_value : 0,
            'other_value' => isset($request->other_value) && !is_nan((float)$request->other_value) ? $request->other_value : 0,
            'transaport_value' => isset($request->transport_value) && !is_nan((float)$request->transport_value) ? $request->transport_value : 0,
            'agent_commision_value' => isset($request->agent_commission_value) && !is_nan((float)$request->agent_commission_value) ? $request->agent_commission_value : 0,
            'dbk_value' => isset($request->dbk_value) && !is_nan((float)$request->dbk_value) ? $request->dbk_value : 0,
            'dbk_value1' => isset($request->dbk_value1) && !is_nan((float)$request->dbk_value1) ? $request->dbk_value1 : 0,
            'total_making_value' => isset($request->total_making_value) && !is_nan((float)$request->total_making_value) ? $request->total_making_value : 0,
            'total_making_per' => isset($request->total_making_per) && !is_nan((float)$request->total_making_per) ? $request->total_making_per : 0,
            'printing_value' => isset($request->printing_value) && !is_nan((float)$request->printing_value) ? $request->printing_value : 0,
            'embroidery_value' => isset($request->embroidery_value) && !is_nan((float)$request->embroidery_value) ? $request->embroidery_value : 0,
            'ixd_value' => isset($request->ixd_value) && !is_nan((float)$request->ixd_value) ? $request->ixd_value : 0,
            'garment_reject_value' => isset($request->garment_reject_value) && !is_nan((float)$request->garment_reject_value) ? $request->garment_reject_value : 0,
            'testing_charges_value' => isset($request->testing_charges_value) && !is_nan((float)$request->testing_charges_value) ? $request->testing_charges_value : 0,
            'finance_cost_value' => isset($request->finance_cost_value) && !is_nan((float)$request->finance_cost_value) ? $request->finance_cost_value : 0,
            'extra_value' => isset($request->extra_value) && !is_nan((float)$request->extra_value) ? $request->extra_value : 0,
            'total_cost_value' => isset($request->total_cost_value) && !is_nan((float)$request->total_cost_value) ? $request->total_cost_value : 0,
            'narration' => $request->narration,
            'is_approved' => $approve,
            'userId' => $request->userId,
            'delflag' => 0,
            'c_code' => $request->c_code,
            'PDMerchant_id' => $request->PDMerchant_id
        );



        //Master Activity


        $MasterOldFetch = DB::table('sales_order_costing_master')
            ->select(
                'fabric_value',
                'sewing_trims_value',
                'packing_trims_value',
                'production_value',
                'transaport_value',
                'other_value',
                'agent_commision_value',
                'dbk_value',
                'printing_value',
                'embroidery_value',
                'ixd_value',
                'garment_reject_value',
                'testing_charges_value',
                'finance_cost_value',
                'extra_value',
                'total_cost_value',
                'narration',
                'is_approved'
            )
            ->where('soc_code', $request->soc_code)
            ->first();



        $MasterOld = (array) $MasterOldFetch;



        $MasterNew = [
            'fabric_value' => isset($request->fabric_value) && !is_nan((float)$request->fabric_value) ? $request->fabric_value : 0,
            'sewing_trims_value' => isset($request->sewing_trims_value) && !is_nan((float)$request->sewing_trims_value) ? $request->sewing_trims_value : 0,
            'packing_trims_value' => isset($request->packing_trims_value) && !is_nan((float)$request->packing_trims_value) ? $request->packing_trims_value : 0,
            'production_value' => isset($request->production_value) && !is_nan((float)$request->production_value) ? $request->production_value : 0,
            'other_value' => isset($request->other_value) && !is_nan((float)$request->other_value) ? $request->other_value : 0,
            'transaport_value' => isset($request->transport_value) && !is_nan((float)$request->transport_value) ? $request->transport_value : 0,
            'agent_commision_value' => isset($request->agent_commission_value) && !is_nan((float)$request->agent_commission_value) ? $request->agent_commission_value : 0,
            'dbk_value' => isset($request->dbk_value) && !is_nan((float)$request->dbk_value) ? $request->dbk_value : 0,
            'dbk_value1' => isset($request->dbk_value1) && !is_nan((float)$request->dbk_value1) ? $request->dbk_value1 : 0,
            'total_making_value' => isset($request->total_making_value) && !is_nan((float)$request->total_making_value) ? $request->total_making_value : 0,
            'total_making_per' => isset($request->total_making_per) && !is_nan((float)$request->total_making_per) ? $request->total_making_per : 0,
            'printing_value' => isset($request->printing_value) && !is_nan((float)$request->printing_value) ? $request->printing_value : 0,
            'embroidery_value' => isset($request->embroidery_value) && !is_nan((float)$request->embroidery_value) ? $request->embroidery_value : 0,
            'ixd_value' => isset($request->ixd_value) && !is_nan((float)$request->ixd_value) ? $request->ixd_value : 0,
            'garment_reject_value' => isset($request->garment_reject_value) && !is_nan((float)$request->garment_reject_value) ? $request->garment_reject_value : 0,
            'testing_charges_value' => isset($request->testing_charges_value) && !is_nan((float)$request->testing_charges_value) ? $request->testing_charges_value : 0,
            'finance_cost_value' => isset($request->finance_cost_value) && !is_nan((float)$request->finance_cost_value) ? $request->finance_cost_value : 0,
            'extra_value' => isset($request->extra_value) && !is_nan((float)$request->extra_value) ? $request->extra_value : 0,
            'total_cost_value' => isset($request->total_cost_value) && !is_nan((float)$request->total_cost_value) ? $request->total_cost_value : 0,
            'narration' => $request->narration,
            'is_approved' => $approve
        ];



        try {
            $loggerMaster->logIfChangedSalesOrderCostingMaster(
                'sales_order_costing_master',
                $request->soc_code,
                $MasterOld,
                $MasterNew,
                'UPDATE',
                $request->soc_date,
                'Master'
            );
            // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
            //   $newDataDetail
            // ]);
        } catch (\Exception $e) {
            Log::error('Logger failed for sales_order_costing_master.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'soc_code' => $request->soc_code,
                'data' => $MasterNew
            ]);
        }






        $olddata1 = DB::table('sales_order_fabric_costing_details')
            ->select(
                'sr_no',
                'class_id',
                'description',
                'consumption',
                'rate_per_unit',
                'wastage'
            )
            ->where('soc_code', $request->input('soc_code'))
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $combinedOldData = $olddata1;




        $olddata2 = DB::table('sales_order_sewing_trims_costing_details')
            ->select('sr_no', 'class_id', 'description', 'consumption', 'rate_per_unit', 'wastage')
            ->where('soc_code', $request->input('soc_code'))
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $combinedOldData2 = $olddata2;



        $olddata3 = DB::table('sales_order_packing_trims_costing_details')
            ->select('sr_no', 'class_id', 'description', 'consumption', 'rate_per_unit', 'wastage')
            ->where('soc_code', $request->input('soc_code'))
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $combinedOldData3 = $olddata3;



        //DB::enableQueryLog();
        $SalesOrderCostingList->fill($data)->save();
        //dd(DB::getQueryLog());




        DB::table('sales_order_fabric_costing_details')->where('soc_code', $request->input('soc_code'))->delete();
        DB::table('sales_order_packing_trims_costing_details')->where('soc_code', $request->input('soc_code'))->delete();
        DB::table('sales_order_sewing_trims_costing_details')->where('soc_code', $request->input('soc_code'))->delete();

        $class_id = $request->input('class_id');

        $newDataDetail2 = [];

        if (count($class_id) > 0) {

            for ($x = 0; $x < count($class_id); $x++) {
                # code...
                $data2[] = array(
                    'soc_code' => $request->soc_code,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_id[$x],
                    'description' => $request->description[$x],
                    'consumption' => $request->consumption[$x],
                    'rate_per_unit' => $request->rate_per_unit[$x],
                    'wastage' => $request->wastage[$x],
                    'bom_qty' => $request->bom_qty[$x],
                    'total_amount' => $request->total_amount[$x],
                );


                $newDataDetail2[] = [
                    'sr_no' => $request->sr_no[$x] ?? 0,
                    'class_id' => $request->class_id[$x],
                    'description' => $request->description[$x],
                    'consumption' => $request->consumption[$x],
                    'rate_per_unit' => $request->rate_per_unit[$x],
                    'wastage' => $request->wastage[$x]
                ];
            }
            SalesOrderFabricCostingDetailModel::insert($data2);
        }



        $combinedNewData = $newDataDetail2;

        try {
            $loggerDetail->logIfChangedSalesOrderFabricCostDetail(
                'sales_order_fabric_costing_details',
                $request->soc_code,
                $combinedOldData,
                $combinedNewData,
                'UPDATE',
                $request->input('soc_date'),
                'Fabric'
            );
            // Log::info('Logger called successfully for sales_order_detail.', [
            //   $newDataDetail
            // ]);
        } catch (\Exception $e) {
            Log::error('Logger failed for sales_order_fabric_costing_details.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'soc_code' => $request->soc_code,
                'data' => $combinedNewData
            ]);
        }



        $class_ids = $request->input('class_ids');
        if (count($class_ids) > 0) {

            $newDataDetail3 = [];

            for ($x = 0; $x < count($class_ids); $x++) {
                # code...
                $data3[] = array(
                    'soc_code' => $request->soc_code,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_ids[$x],
                    'description' => $request->descriptions[$x],
                    'consumption' => $request->consumptions[$x],
                    'rate_per_unit' => $request->rate_per_units[$x],
                    'wastage' => $request->wastages[$x],
                    'bom_qty' => $request->bom_qtys[$x],
                    'total_amount' => $request->total_amounts[$x],
                );


                $newDataDetail3[] = [
                    'sr_no' => $request->sr_no_trim[$x] ?? 0,
                    'class_id' => $request->class_ids[$x],
                    'description' => $request->descriptions[$x],
                    'consumption' => $request->consumptions[$x],
                    'rate_per_unit' => $request->rate_per_units[$x],
                    'wastage' => $request->wastages[$x]
                ];
            }
            SalesOrderSewingTrimsCostingDetailModel::insert($data3);
        }


        $combinedNewData2 = $newDataDetail3;

        try {
            $loggerDetail->logIfChangedSalesOrderFabricCostDetail(
                'sales_order_sewing_trims_costing_details',
                $request->soc_code,
                $combinedOldData2,
                $combinedNewData2,
                'UPDATE',
                $request->input('soc_date'),
                'Sewing'
            );
            // Log::info('Logger called successfully for sales_order_detail.', [
            //   $newDataDetail
            // ]);
        } catch (\Exception $e) {
            Log::error('Logger failed for sales_order_sewing_trims_costing_details.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'soc_code' => $request->soc_code,
                'data' => $combinedNewData2
            ]);
        }




        $class_idss = $request->input('class_idss');
        if (count($class_idss) > 0) {

            $newDataDetail4 = [];

            for ($x = 0; $x < count($class_idss); $x++) {
                # code...
                $data4[] = array(
                    'soc_code' => $request->soc_code,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_idss[$x],
                    'description' => $request->descriptionss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x],
                    'bom_qty' => $request->bom_qtyss[$x],
                    'total_amount' => $request->total_amountss[$x],
                );


                $newDataDetail4[] = [
                    'sr_no' => $request->sr_no_packing[$x] ?? 0,
                    'class_id' => $request->class_idss[$x],
                    'description' => $request->descriptionss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x]
                ];
            }
            SalesOrderPackingTrimsCostingDetailModel::insert($data4);
        }


        $combinedNewData4 = $newDataDetail4;

        try {
            $loggerDetail->logIfChangedSalesOrderFabricCostDetail(
                'sales_order_packing_trims_costing_details',
                $request->soc_code,
                $combinedOldData3,
                $combinedNewData4,
                'UPDATE',
                $request->input('soc_date'),
                'Packing'
            );
            // Log::info('Logger called successfully for sales_order_detail.', [
            //   $newDataDetail
            // ]);
        } catch (\Exception $e) {
            Log::error('Logger failed for sales_order_packing_trims_costing_details.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'soc_code' => $request->soc_code,
                'data' => $combinedNewData4
            ]);
        }



        return redirect()->route('SalesOrderCosting.index')->with('message', 'Data Saved Succesfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */



    public function getSalesOrderDetails(Request $request)
    {
        $sales_order_no = $request->input('sales_order_no');
        $InsertSizeData = DB::select('call AddSizeQtyFromSalesOrder("' . $sales_order_no . '")');
        $MasterdataList = DB::select("select *,(select sales_order_costing_master.production_value from sales_order_costing_master where sales_order_no='" . $sales_order_no . "') as production_value from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code='" . $sales_order_no . "'");
        return json_encode($MasterdataList);
    }

    public function GetItemData(Request $request)
    {
        $item_code = $request->item_code;
        $data = DB::select(DB::raw("SELECT item_code, hsn_code, unit_id, item_image_path , item_description, quality_code
        from item_master where item_code='$request->item_code'"));
        echo json_encode($data);
    }


    public function checkCostingStatus(Request $request)
    {
        $sales_order_no = $request->sales_order_no;
        $data = DB::select(DB::raw("select count(*) as count from bom_master where sales_order_no in 
                (select sales_order_no from sales_order_costing_master 
                INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no 
                where sales_order_costing_master.is_approved=2 OR buyer_purchse_order_master.og_id = 4) AND sales_order_no='" . $request->sales_order_no . "'"));

        echo json_encode($data);
    }

    public function GetCostingData($soc_code)
    {

        //DB::enableQueryLog();
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
            ->leftJoin('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
            ->leftJoin('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
            ->leftJoin('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
            ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
            ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
            ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
            ->where('sales_order_costing_master.delflag', '=', '0')
            ->where('sales_order_costing_master.soc_code', '=', $soc_code)
            ->get([
                'sales_order_costing_master.*',
                'buyer_purchse_order_master.sam',
                'usermaster.username',
                'ledger_master.Ac_name',
                'buyer_purchse_order_master.total_qty',
                'costing_type_master.cost_type_name',
                'season_master.season_name',
                'currency_master.currency_name',
                'sub_style_master.substyle_name',
                'main_style_master.mainstyle_id',
                'main_style_master.mainstyle_name',
                'buyer_purchse_order_master.style_img_path',
                'buyer_purchse_order_master.total_qty',
                'buyer_purchse_order_master.order_received_date'
            ]);
        //    $query = DB::getQueryLog();
        //  $query = end($query);
        //   dd($query);


        return view('saleCostingSheet', compact('SalesOrderCostingMaster'));
    }

    public function GetCostingDataPrintView($soc_code)
    {
        //DB::enableQueryLog();
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
            ->leftJoin('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
            ->leftJoin('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
            ->leftJoin('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
            ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
            ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')

            ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
            ->leftJoin('payment_term', 'payment_term.ptm_id', '=', 'buyer_purchse_order_master.ptm_id')
            ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
            // ->leftJoin('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
            ->leftJoin('order_type_master', 'order_type_master.orderTypeId', '=', 'buyer_purchse_order_master.order_type')
            ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
            ->leftJoin('PDMerchant_master', 'PDMerchant_master.PDMerchant_id', '=', 'buyer_purchse_order_master.PDMerchant_id')
            ->join('shipment_mode_master', 'shipment_mode_master.ship_id', '=', 'buyer_purchse_order_master.ship_id', 'left outer')
            ->join('country_master', 'country_master.c_id', '=', 'buyer_purchse_order_master.country_id')
            ->leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id', 'left outer')
            ->join('warehouse_master', 'warehouse_master.warehouse_id', '=', 'buyer_purchse_order_master.warehouse_id', 'left outer')
            ->join('delivery_terms_master', 'delivery_terms_master.dterm_id', '=', 'buyer_purchse_order_master.dterm_id', 'left outer')
            ->leftJoin('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
            ->where('sales_order_costing_master.delflag', '=', '0')
            ->where('sales_order_costing_master.soc_code', '=', $soc_code)
            ->get([
                'sales_order_costing_master.*',
                'buyer_purchse_order_master.order_value',
                'buyer_purchse_order_master.sam',
                'buyer_purchse_order_master.order_rate',
                'buyer_purchse_order_master.inspection_date',
                'buyer_purchse_order_master.shipment_date',
                'buyer_purchse_order_master.plan_cut_date',
                'usermaster.username',
                'ledger_master.Ac_name',
                'buyer_purchse_order_master.total_qty',
                'costing_type_master.cost_type_name',
                'season_master.season_name',
                'currency_master.currency_name',
                'brand_master.brand_name',
                'sub_style_master.substyle_name',
                'main_style_master.mainstyle_id',
                'main_style_master.mainstyle_name',
                'buyer_purchse_order_master.style_img_path',
                'buyer_purchse_order_master.total_qty',
                'buyer_purchse_order_master.order_received_date',
                'buyer_purchse_order_master.po_code',
                'buyer_purchse_order_master.in_out_id',
                'payment_term.ptm_name',
                'delivery_terms_master.delivery_term_name',
                'shipment_mode_master.ship_mode_name',
                'warehouse_master.warehouse_name',
                'country_master.c_name',
                'merchant_master.merchant_name',
                'PDMerchant_master.PDMerchant_name',
                'order_group_master.order_group_name',
                'order_type_master.order_type',
                'fg_master.fg_name'
            ]);
        //    $query = DB::getQueryLog();
        //  $query = end($query);
        //   dd($query);


        return view('SalesCostingPrintView', compact('SalesOrderCostingMaster'));
    }

    public function GetCostingSalesOrderWiseData($sales_order_no)
    {

        //DB::enableQueryLog();
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
            ->leftJoin('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
            ->leftJoin('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
            ->leftJoin('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
            ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
            ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
            ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
            ->where('sales_order_costing_master.delflag', '=', '0')
            ->where('sales_order_costing_master.sales_order_no', '=', $sales_order_no)
            ->get([
                'sales_order_costing_master.*',
                'buyer_purchse_order_master.sam',
                'usermaster.username',
                'ledger_master.Ac_name',
                'costing_type_master.cost_type_name',
                'season_master.season_name',
                'currency_master.currency_name',
                'buyer_purchse_order_master.order_type',
                'sub_style_master.substyle_name',
                'main_style_master.mainstyle_id',
                'main_style_master.mainstyle_name',
                'buyer_purchse_order_master.style_img_path',
                'buyer_purchse_order_master.total_qty',
                'buyer_purchse_order_master.order_received_date'
            ]);
        //    $query = DB::getQueryLog();
        //  $query = end($query);
        //   dd($query);


        return view('saleCostingSheet', compact('SalesOrderCostingMaster'));
    }

    public function costingProfitSheet(Request $request)
    {

        $receivedFromDate = isset($request->receivedFromDate) ? $request->receivedFromDate : date('Y-m-01');
        $receivedToDate = isset($request->receivedToDate) ? $request->receivedToDate : date('Y-m-d');

        $sales_order_no = $request->sales_order_no;
        $job_status_id = $request->job_status_id;
        $orderTypeId = $request->orderTypeId;
        $Ac_code = $request->ac_code;
        $is_approved = $request->is_approved;
        $brand_id = $request->brand_id;
        $mainstyle_id = $request->mainstyle_id;
        $style_no = $request->style_no;

        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4");
        $jobStatusList = DB::SELECT("SELECT job_status_id,job_status_name FROM job_status_master WHERE delflag = 0");
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $styleList = DB::SELECT("SELECT fg_id,fg_name FROM fg_master WHERE delflag = 0");
        $mainStyleList = DB::SELECT("SELECT mainstyle_id,mainstyle_name FROM main_style_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");
        $orderTypeList = DB::SELECT("SELECT orderTypeId,order_type FROM order_type_master WHERE delflag = 0");
        $styleNoList = DB::SELECT("SELECT style_no FROM sales_order_costing_master WHERE delflag = 0");



        // $SalesOrderCostingMaster = SalesOrderCostingMasterModel::leftJoin('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
        // ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
        // ->leftJoin('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
        // ->leftJoin('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
        // ->leftJoin('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id') 
        // ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
        // ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
        // ->leftJoin('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
        // ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->leftJoin('sales_order_fabric_costing_details', 'sales_order_fabric_costing_details.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        // ->where('sales_order_costing_master.delflag','=', '0')
        // ->where('buyer_purchse_order_master.og_id','!=', '4')
        // ->where('sales_order_fabric_costing_details.class_id','!=', '7')
        // ->groupBy('sales_order_fabric_costing_details.sales_order_no') 
        // ->get(['sales_order_costing_master.*','buyer_purchse_order_master.sam','buyer_purchse_order_master.order_type','usermaster.username','ledger_master.Ac_name','job_status_master.job_status_name',
        // 'costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_img_path',
        // 'brand_master.brand_name','buyer_purchse_order_master.total_qty as order_qty','buyer_purchse_order_master.order_received_date',
        //  DB::raw("(select consumption FROM sales_order_fabric_costing_details WHERE sales_order_fabric_costing_details.sales_order_no = buyer_purchse_order_master.tr_code GROUP BY sales_order_fabric_costing_details.sales_order_no) as consumption")]);


        $filter = "";

        if ($receivedFromDate != "" && $receivedToDate != "") {
            $filter .= " AND buyer_purchse_order_master.order_received_date BETWEEN '" . $receivedFromDate . "' AND '" . $receivedToDate . "'";
        }


        if ($Ac_code != "") {
            $filter .= " AND buyer_purchse_order_master.Ac_code='" . $Ac_code . "'";
        }

        if ($sales_order_no != "") {
            $filter .= " AND buyer_purchse_order_master.tr_code='" . $sales_order_no . "'";
        }

        if ($job_status_id != "") {
            $filter .= " AND buyer_purchse_order_master.job_status_id='" . $job_status_id . "'";
        }

        if ($brand_id != "") {
            $filter .= " AND buyer_purchse_order_master.brand_id='" . $brand_id . "'";
        }

        if ($mainstyle_id != "") {
            $filter .= " AND buyer_purchse_order_master.mainstyle_id='" . $mainstyle_id . "'";
        }

        if ($orderTypeId != "") {
            $filter .= " AND buyer_purchse_order_master.order_type='" . $orderTypeId . "'";
        }

        if ($style_no != "") {
            $filter .= " AND sales_order_costing_master.style_no='" . $style_no . "'";
        }

        if ($is_approved != "") {
            $filter .= " AND sales_order_costing_master.is_approved='" . $is_approved . "'";
        }
        //  DB::enableQueryLog();
        $SalesOrderCostingMaster = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,buyer_purchse_order_master.order_type,usermaster.username,ledger_master.ac_short_name,job_status_master.job_status_name,
                    costing_type_master.cost_type_name,season_master.season_name,currency_master.currency_name,main_style_master.mainstyle_name,buyer_purchse_order_master.style_img_path,
                    brand_master.brand_name,buyer_purchse_order_master.total_qty as order_qty,buyer_purchse_order_master.order_received_date,
                    (select consumption FROM sales_order_fabric_costing_details WHERE sales_order_fabric_costing_details.sales_order_no = buyer_purchse_order_master.tr_code GROUP BY sales_order_fabric_costing_details.sales_order_no) as consumption
                    FROM sales_order_costing_master 
                    LEFT JOIN usermaster ON usermaster.userId = sales_order_costing_master.userId
                    LEFT JOIN ledger_master ON ledger_master.Ac_code = sales_order_costing_master.Ac_code
                    LEFT JOIN season_master ON season_master.season_id = sales_order_costing_master.season_id
                    LEFT JOIN currency_master ON currency_master.cur_id = sales_order_costing_master.currency_id
                    LEFT JOIN costing_type_master ON costing_type_master.cost_type_id = sales_order_costing_master.cost_type_id
                    LEFT JOIN main_style_master ON main_style_master.mainstyle_id = sales_order_costing_master.mainstyle_id
                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                    LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                    LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                    LEFT JOIN sales_order_fabric_costing_details ON sales_order_fabric_costing_details.sales_order_no = buyer_purchse_order_master.tr_code
                    WHERE sales_order_costing_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND sales_order_fabric_costing_details.class_id != 7 " . $filter . "
                    GROUP BY sales_order_fabric_costing_details.sales_order_no");
        // dd(DB::getQueryLog());

        return view('SalesCostingProfitSheet', compact('SalesOrderCostingMaster', 'salesOrderList', 'jobStatusList', 'brandList', 'styleList', 'mainStyleList', 'buyerList', 'orderTypeList', 'styleNoList', 'receivedFromDate', 'receivedToDate', 'sales_order_no', 'job_status_id', 'orderTypeId', 'Ac_code', 'is_approved', 'brand_id', 'mainstyle_id', 'style_no'));
    }


    public function costingProfitSheet2()
    {
        //  DB::enableQueryLog();
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
            ->join('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
            ->join('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
            ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
            ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
            ->where('sales_order_costing_master.delflag', '=', '0')
            ->get([
                'sales_order_costing_master.*',
                'usermaster.username',
                'ledger_master.Ac_name',
                'costing_type_master.cost_type_name',
                'season_master.season_name',
                'currency_master.currency_name',
                'sub_style_master.substyle_name',
                'main_style_master.mainstyle_name',
                'buyer_purchse_order_master.style_img_path',
                'buyer_purchse_order_master.order_received_date'
            ]);
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('SalesCostingProfitSheet2', compact('SalesOrderCostingMaster'));
    }


    public function GetCostingProfitByFilter()
    {
        $FinYearList = DB::select('select YEAR(fdate) as fdate ,fin_year_name from financial_year_master where delflag=0');
        $BuyerList = DB::select('select Ac_code ,Ac_name from ledger_master where delflag=0  and Ac_code > 39');
        $MainStyleList = DB::select('select mainstyle_id ,mainstyle_name from main_style_master where delflag=0');

        return view('GetCostingProfitByFilter', compact('BuyerList', 'MainStyleList', 'FinYearList'));
    }


    public function costingProfitSheet3(Request $request)
    {


        //   DB::enableQueryLog();
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
            ->join('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
            ->join('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
            ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
            ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
            ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
            ->where('sales_order_costing_master.delflag', '=', '0')
            ->where('buyer_purchse_order_master.brand_id', $request->brand_id)
            ->where('buyer_purchse_order_master.Ac_code', $request->Ac_code)
            ->where('buyer_purchse_order_master.mainstyle_id', $request->mainstyle_id)
            ->where(DB::raw('YEAR(buyer_purchse_order_master.order_received_date)'), '=', $request->fin_year_id)
            ->get([
                'sales_order_costing_master.*',
                'usermaster.username',
                'ledger_master.Ac_name',
                'costing_type_master.cost_type_name',
                'season_master.season_name',
                'currency_master.currency_name',
                'sub_style_master.substyle_name',
                'main_style_master.mainstyle_name',
                'buyer_purchse_order_master.style_img_path',
                'buyer_purchse_order_master.order_received_date',
                'brand_master.brand_name'
            ]);
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('SalesCostingProfit3', compact('SalesOrderCostingMaster'));
    }


    public function repeatstore(Request $request)
    {

        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
            ->where('c_name', '=', 'C1')
            ->where('type', '=', 'SALES_ORDER_COSTING')
            ->where('firm_id', '=', 1)
            ->first();
        $TrNo = $codefetch->code . '-' . $codefetch->tr_no;

        $this->validate($request, [

            'soc_date' => 'required',
            'Ac_code' => 'required',
            'sales_order_no' => 'required',
            'agent_commission_value' => 'required',
            'total_cost_value' => 'required',
            'other_value' => 'required',
            'production_value' => 'required',
            'fabric_value' => 'required',
            'sewing_trims_value' => 'required',
            'packing_trims_value' => 'required',

        ]);


        $data1 = array(

            'soc_code' => $TrNo,
            'soc_date' => $request->soc_date,
            'cost_type_id' => $request->cost_type_id,
            'sales_order_no' => $request->sales_order_no,
            'Ac_code' => $request->Ac_code,
            'season_id' => $request->season_id,
            'brand_id' => $request->brand_id,
            'currency_id' => $request->currency_id,
            'mainstyle_id' => $request->mainstyle_id,
            'substyle_id' => $request->substyle_id,
            'fg_id' => $request->fg_id,
            'style_no' => $request->style_no,
            'style_description' => $request->style_description,
            'order_rate' => $request->order_rate,
            'exchange_rate' => $request->exchange_rate,
            'inr_rate' => $request->inr_rate,
            'sam' => $request->sam,
            'fabric_value' => $request->fabric_value,
            'sewing_trims_value' => $request->sewing_trims_value,
            'packing_trims_value' => $request->packing_trims_value,
            'production_value' => $request->production_value,
            'other_value' => $request->other_value,
            'transaport_value' => $request->transport_value,
            'agent_commision_value' => $request->agent_commission_value,
            'dbk_value' => $request->dbk_value,
            'printing_value' => $request->printing_value,
            'embroidery_value' => $request->embroidery_value,
            'ixd_value' => $request->ixd_value,
            'garment_reject_value' => $request->garment_reject_value,
            'testing_charges_value' => $request->testing_charges_value,
            'finance_cost_value' => $request->finance_cost_value,
            'extra_value' => $request->extra_value,
            'total_cost_value' => $request->total_cost_value,
            'narration' => $request->narration,
            'is_approved' => '0',
            'userId' => $request->userId,
            'delflag' => '0',
            'c_code' => $request->c_code,

        );

        SalesOrderCostingMasterModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='SALES_ORDER_COSTING'");

        $class_id = $request->input('class_id');
        if (count($class_id) > 0) {

            for ($x = 0; $x < count($class_id); $x++) {
                # code...
                $data2[] = array(

                    'soc_code' => $TrNo,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_id[$x],
                    'description' => $request->description[$x],
                    'consumption' => $request->consumption[$x],
                    'rate_per_unit' => $request->rate_per_unit[$x],
                    'wastage' => $request->wastage[$x],
                    'bom_qty' => $request->bom_qty[$x],
                    'total_amount' => $request->total_amount[$x],

                );
            }
            SalesOrderFabricCostingDetailModel::insert($data2);
        }

        $class_ids = $request->input('class_ids');
        if (count($class_ids) > 0) {
            for ($x = 0; $x < count($class_ids); $x++) {
                # code...
                $data3[] = array(

                    'soc_code' => $TrNo,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_ids[$x],
                    'description' => $request->descriptions[$x],
                    'consumption' => $request->consumptions[$x],
                    'rate_per_unit' => $request->rate_per_units[$x],
                    'wastage' => $request->wastages[$x],
                    'bom_qty' => $request->bom_qtys[$x],
                    'total_amount' => $request->total_amounts[$x],

                );
            }
            SalesOrderSewingTrimsCostingDetailModel::insert($data3);
        }

        $class_idss = $request->input('class_idss');
        if (count($class_idss) > 0) {
            for ($x = 0; $x < count($class_idss); $x++) {
                # code...
                $data4[] = array(

                    'soc_code' => $TrNo,
                    'soc_date' => $request->soc_date,
                    'cost_type_id' => $request->cost_type_id,
                    'Ac_code' => $request->Ac_code,
                    'sales_order_no' => $request->sales_order_no,
                    'season_id' => $request->season_id,
                    'currency_id' => $request->currency_id,
                    'class_id' => $request->class_idss[$x],
                    'description' => $request->descriptionss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x],
                    'bom_qty' => $request->bom_qtyss[$x],
                    'total_amount' => $request->total_amountss[$x],

                );
            }
            SalesOrderPackingTrimsCostingDetailModel::insert($data4);
        }


        return redirect()->route('SalesOrderCosting.index')->with('message', 'Data Saved Succesfully');
    }


    // public function destroy($id)
    // {
    //       DB::table('sales_order_costing_master')->where('soc_code', $id)->delete();
    //     DB::table('sales_order_fabric_costing_details')->where('soc_code',$id)->delete();
    //     DB::table('sales_order_packing_trims_costing_details')->where('soc_code', $id)->delete();
    //     DB::table('sales_order_sewing_trims_costing_details')->where('soc_code', $id)->delete();


    //     $counts=1;
    //     $SalesOrderNo=DB::select("select sales_order_no from sales_order_costing_master where soc_code='".$id."'");

    //   $Records=DB::select("select  
    //   (select count(bom_code)  from bom_master where sales_order_no='".$SalesOrderNo[0]->sales_order_no."') as boms, 
    //   (select count(vw_code)   from vendor_work_order_master where sales_order_no='".$SalesOrderNo[0]->sales_order_no."') as workorders");


    //   $counts=($Records[0]->boms + $Records[0]->workorders);
    //  //echo $counts;   

    //     if($counts==0)
    //     {


    //      DB::table('sales_order_costing_master')->where('soc_code', $id)->delete();
    //     DB::table('sales_order_fabric_costing_details')->where('soc_code',$id)->delete();
    //     DB::table('sales_order_packing_trims_costing_details')->where('soc_code', $id)->delete();
    //     DB::table('sales_order_sewing_trims_costing_details')->where('soc_code', $id)->delete();

    //         Session::flash('error', "Deleted record successfully"); 


    //     }
    //     else
    //     {
    //         Session::flash('error', "Costing Record can not be deleted, Remove References of BOM, Purchase Orders, Work Orders.!"); 

    //     }

    //     // return $counts.$Records[0]->boms.$Records[0]->workorders;




    // }



    public function destroy($id)
    {

        $counts = 1;
        $SalesOrderNo = DB::select("select sales_order_no from sales_order_costing_master where soc_code='" . $id . "'");

        $Records = DB::select("select  
      (select count(bom_code)  from bom_master where sales_order_no='" . $SalesOrderNo[0]->sales_order_no . "') as boms, 
      (select count(vw_code)   from vendor_work_order_master where sales_order_no='" . $SalesOrderNo[0]->sales_order_no . "') as workorders");

        $counts = ($Records[0]->boms + $Records[0]->workorders);


        $RecordList = DB::select("select  
     (select GROUP_CONCAT(bom_code)  from bom_master where sales_order_no='" . $SalesOrderNo[0]->sales_order_no . "') as bomList, 
     (select GROUP_CONCAT(vw_code)   from vendor_work_order_master where sales_order_no='" . $SalesOrderNo[0]->sales_order_no . "') as workorderList,
     (select GROUP_CONCAT(vpo_code)   from vendor_purchase_order_master where sales_order_no='" . $SalesOrderNo[0]->sales_order_no . "') as processorderList
     ");


        $Message = "BOM List :" . $RecordList[0]->bomList . " --------WorkOrder List : " . $RecordList[0]->workorderList . "---------- Process Orders: " . $RecordList[0]->processorderList;

        if ($counts == 0) {

            DB::table('sales_order_costing_master')->where('soc_code', $id)->delete();
            DB::table('sales_order_fabric_costing_details')->where('soc_code', $id)->delete();
            DB::table('sales_order_packing_trims_costing_details')->where('soc_code', $id)->delete();
            DB::table('sales_order_sewing_trims_costing_details')->where('soc_code', $id)->delete();

            Session::flash('error', "Deleted record successfully");
        } else {
            Session::flash('error', "Costing can't be deleted, Remove References -> " . $Message);
        }

        // return $counts.$Records[0]->boms.$Records[0]->workorders;




    }


    public function GetMainStyleImage(Request $request)
    {
        $mainstyle_id = $request->input('mainstyle_id');
        $mainstyle_image = DB::table('main_style_master')->select('mainstyle_image')->where('mainstyle_id', $request->mainstyle_id)->first();
        $imagePath = $mainstyle_image->mainstyle_image;
        return $imagePath;
    }

    public function FiltersPage(Request $request)
    {
        return view('FiltersPage');
    }


    public function SalesOrderCostingStatus(Request $request)
    {
        $sales_order_no = $request->sales_order_no;
        $salesOrderCostingData = DB::table('sales_order_costing_master')->select('is_approved')->where('sales_order_no', $request->sales_order_no)->first();
        $status = $salesOrderCostingData->is_approved ?? 0;

        return response()->json(['status' => $status]);
    }
}
