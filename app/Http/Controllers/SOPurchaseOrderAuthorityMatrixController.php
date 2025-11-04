<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\SOPOAuthorityMatrixModel;
use App\Models\NewJobOpeningDetailModel;
use App\Models\DailyProductionEntryDetailOperationModel;
use App\Models\OBMasterModel;
use Illuminate\Http\Request;
use DataTables;
use Session;
use DB;
use App\Traits\EmployeeTrait;
use DatePeriod;
use DateTime;
use DateInterval;
use App\Models\BrandModel;
use App\Models\SalesOrderCostingMasterModel;
use App\Models\ItemModel;
use Log;
use Str;
date_default_timezone_set('Asia/Kolkata');


class SOPurchaseOrderAuthorityMatrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      use EmployeeTrait;  
     
     
    public function index(Request $request)
    {
        
            $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '31')
            ->first();
      
        
       if($request->ajax()) 
        {
            $data=SOPOAuthorityMatrixModel::select("so_po_authority_matrix.so_po_authority_id",
            "so_po_authority_matrix.so_po_authority_date","usermaster.username","so_po_authority_matrix.ac_code",
            'so_po_authority_matrix.bom_qty','so_po_authority_matrix.brand_id','so_po_authority_matrix.sales_order_no',
            'so_po_authority_matrix.cat_id','so_po_authority_matrix.item_code','so_po_authority_matrix.class_id',
            'level1_percentage','level1_po_qty','level2_percentage','level2_po_qty','level3_percentage',
            'level3_po_qty','ledger_master.ac_name','brand_master.brand_name','item_master.item_name','classification_master.class_name',DB::raw("CASE 
            WHEN so_po_authority_matrix.cat_id = 1 THEN 'Packing Trims'
            WHEN so_po_authority_matrix.cat_id = 2 THEN 'Sewing Trims'
            ELSE 'Other' END AS cat_name"))
            ->join('usermaster','usermaster.userId','=','so_po_authority_matrix.userId') 
            ->join('brand_master','brand_master.brand_id','=','so_po_authority_matrix.brand_id')
            ->join('ledger_master','ledger_master.Ac_code','=','so_po_authority_matrix.ac_code')
            ->join('item_master','item_master.item_code','=','so_po_authority_matrix.item_code') 
            ->join('classification_master','classification_master.class_id','=','so_po_authority_matrix.class_id');   
            
            return Datatables::of($data)
            ->addIndexColumn()
  
            ->addColumn('action1', function($row)
            {
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('so_po_authority_matrix.edit', $row['so_po_authority_id']).'" >  <i class="fas fa-pencil-alt"></i></a>';
                return $btn;
            })
            ->addColumn('action2', function($row)
            {
                
                  if(Session::get('user_type')==1)
            {
                
                $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['so_po_authority_id'].'"  data-route="'.route('so_po_authority_matrix.destroy', $row['so_po_authority_id']).'"><i class="fas fa-trash"></i></a>';
                return $btn3;
                
            } else{
                
               $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete" ><i class="feather feather-lock-2"></i></a>';
                return $btn3;   
                
            }
                
                
            })
            ->rawColumns(['action1','action2'])
            ->make(true);
        }
          
        
            return view('SOPOAuthorityMatrixList',compact('chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function load_data()
    {
        
        
        $ReportDate = isset($request->ReportDate) ? $request->ReportDate : date('Y-m-d'); 
        $fob = isset($request->fob) ? $request->fob : 0; 
        $job_work = isset($request->job_work) ? $request->job_work : 0; 
        
        $filter = '';
        if($fob > 0)
        {
            $filter .= ' AND buyer_purchse_order_master.order_type = 1';
        }
        
        if($job_work > 0)
        {
            $filter .= ' AND buyer_purchse_order_master.order_type = 3';
        }
          
          
             
        $Buyer_Purchase_Order_List = DB::select("
                SELECT 
                    og.order_group_name,
                    bpom.*,
                    u.username,
                    lm.ac_short_name, 
                    socm.total_cost_value,
                    socm.order_rate,
                    socm.production_value,
                    socm.other_value,
                    cm.color_name,
                    fg.fg_name,
                    mm.merchant_name,
                    bm.brand_name,
                    msm.mainstyle_name,
                    sod.color_id,
                    im.item_name,
                    sod.item_code,
                    bpom.sam,
                    bpom.userId,
                    bpom.brand_id,
                      COALESCE(colorqtytbl.size_qty, 0) AS size_qty,
                    -- Shipped Qty
                    COALESCE(st.shipped_qty, 0) AS shipped_qty,
                    COALESCE(pid.shipped_qty2, 0) AS shipped_qty2,
                    COALESCE(bpod.adjust_qty, 0) AS adjust_qty,
                    COALESCE(fom.fabric_issued, 0) AS fabric_issued,
                    bpod.remark,
                    COALESCE(cpg.cut_qty, 0) AS cut_qty,
                    COALESCE(sim.prod_qty, 0) AS prod_qty,
                    COALESCE(sod.size_qty_total, 0) AS total_qty,
                        CASE 
                        WHEN COALESCE(pid.shipped_qty2, 0) > 0 THEN 'Packing'
                        WHEN COALESCE(sim.prod_qty, 0) > 0 THEN 'Production'
                        WHEN COALESCE(cpg.cut_qty, 0) > 0 THEN 'Cutting'
                        ELSE 'Not Started'
                        END AS current_status
                    
                    
                FROM buyer_purchse_order_master bpom
            
                INNER JOIN usermaster u 
                    ON u.userId = bpom.userId
            
                INNER JOIN ledger_master lm 
                    ON lm.Ac_code = bpom.Ac_code
            
                LEFT JOIN brand_master bm 
                    ON bm.brand_id = bpom.brand_id
            
                LEFT JOIN main_style_master msm 
                    ON msm.mainstyle_id = bpom.mainstyle_id
            
                INNER JOIN fg_master fg 
                    ON fg.fg_id = bpom.fg_id
            
                LEFT JOIN merchant_master mm 
                    ON mm.merchant_id = bpom.merchant_id
            
                LEFT JOIN sales_order_costing_master socm 
                    ON socm.sales_order_no = bpom.tr_code
            
                LEFT JOIN order_group_master og 
                    ON og.og_id = bpom.og_id
            
                INNER JOIN sales_order_detail sod
                    ON sod.tr_code = bpom.tr_code   
            
                INNER JOIN color_master cm 
                    ON cm.color_id = sod.color_id
                    
                INNER JOIN item_master im 
                    ON im.item_code = sod.item_code     
                    
            
                LEFT JOIN (
                    SELECT 
                        std.sales_order_no,
                        cpihd.color_id,
                        SUM(cpihd.size_qty_total) AS shipped_qty,
                        SUM(std.order_rate) AS order_rate
                    FROM sale_transaction_detail std
                    INNER JOIN sale_transaction_master stm 
                        ON stm.sale_code = std.sale_code
                    INNER JOIN (
                        SELECT sale_code, SUBSTRING_INDEX(SUBSTRING_INDEX(carton_packing_nos, ',', n.n), ',', -1) AS cpki_code
                        FROM sale_transaction_master
                        JOIN numbers n ON CHAR_LENGTH(carton_packing_nos) - CHAR_LENGTH(REPLACE(carton_packing_nos, ',', '')) >= n.n-1
                    ) stc ON stc.sale_code = stm.sale_code
                    INNER JOIN carton_packing_inhouse_detail cpihd
                        ON cpihd.cpki_code = stc.cpki_code
                    WHERE std.sale_date <= '$ReportDate'
                    GROUP BY std.sales_order_no, cpihd.color_id
                ) st 
                    ON st.sales_order_no = bpom.tr_code  
                   AND st.color_id = sod.color_id
                
                            
                LEFT JOIN (
                    SELECT sales_order_no, color_id, SUM(size_qty_total) AS shipped_qty2
                    FROM packing_inhouse_detail
                    WHERE pki_date <= '$ReportDate'
                    GROUP BY sales_order_no, color_id
                ) pid 
                    ON pid.sales_order_no = bpom.tr_code 
                   AND pid.color_id = sod.color_id

            
                LEFT JOIN (
                    SELECT tr_code,color_id, SUM(adjust_qty) AS adjust_qty, MAX(remark) AS remark
                    FROM buyer_purchase_order_detail
                    WHERE tr_date <= '$ReportDate'
                    GROUP BY tr_code, color_id
                ) bpod ON bpod.tr_code = bpom.tr_code
                   AND bpod.color_id = sod.color_id
            
                LEFT JOIN (
                    SELECT vpm.sales_order_no, SUM(fom.total_meter) AS fabric_issued
                    FROM fabric_outward_master fom
                    INNER JOIN vendor_purchase_order_master vpm
                        ON vpm.vpo_code = fom.vpo_code
                    WHERE fom.fout_date <= '$ReportDate'
                    GROUP BY vpm.sales_order_no
                ) fom ON fom.sales_order_no = bpom.tr_code
            
                LEFT JOIN (
                    SELECT sales_order_no, color_id, SUM(size_qty_total) AS cut_qty
                    FROM cut_panel_grn_detail
                    WHERE cpg_date <= '$ReportDate'
                    GROUP BY sales_order_no,color_id
                ) cpg ON cpg.sales_order_no = bpom.tr_code
                   AND cpg.color_id = sod.color_id
            
                LEFT JOIN (
                    SELECT sales_order_no, color_id, SUM(size_qty_total) AS prod_qty
                    FROM stitching_inhouse_detail
                    WHERE sti_date <= '$ReportDate'
                    GROUP BY sales_order_no, color_id
                ) sim ON sim.sales_order_no = bpom.tr_code
                       AND sim.color_id = sod.color_id
                       
              LEFT JOIN (
                    SELECT tr_code,color_id,sum(size_qty) as size_qty
                    FROM buyer_purchase_order_size_detail
                    GROUP BY tr_code, color_id
                ) colorqtytbl ON colorqtytbl.tr_code = bpom.tr_code
                       AND colorqtytbl.color_id = sod.color_id           

                WHERE  
                    bpom.delflag = 0 
                    AND bpom.og_id != 4
                    AND bpom.order_type != 2
                    AND bpom.order_received_date <= '$ReportDate'
                    AND (bpom.order_close_date > '$ReportDate' OR bpom.order_close_date IS NULL)
                    $filter
                GROUP BY bpom.tr_code, bpom.style_no, sod.color_id
            ");

  $insertData = [];


    $dataStock = DB::table('stock_association')
    ->join('classification_master','classification_master.class_id','=','stock_association.class_id')
    ->select('stock_association.sales_order_no',DB::raw('ROUND(SUM(stock_association.qty),2) as qty,classification_master.class_name'))
    ->groupBy(
        'stock_association.class_id',
        'stock_association.sales_order_no')
    ->get();
    
    
    $stockMap=[];
    
    foreach ($dataStock as $rowRecords)
    {    
         
        
     $stockMap[$rowRecords->sales_order_no][ucfirst(Str::slug($rowRecords->class_name, '_'))][]=
     ["qty"=>$rowRecords->qty];   
        
    }


        $orderArray = $Buyer_Purchase_Order_List instanceof \Illuminate\Support\Collection ? $Buyer_Purchase_Order_List->toArray() : $Buyer_Purchase_Order_List;
    
        $kdplArr = array_column($orderArray, 'tr_code');


            
            $classList = collect([
            DB::table('bom_packing_trims_details')
            ->whereIn('sales_order_no', $kdplArr)
            ->pluck('class_id'),
            
            DB::table('bom_sewing_trims_details')
            ->whereIn('sales_order_no', $kdplArr)
            ->pluck('class_id')
            ])->flatten()->unique()->values()->toArray();
            
  
            $classificationNamesArray = DB::table('classification_master')
            ->whereIn('class_id', $classList)
            ->pluck('class_name', 'class_id')
           ->mapWithKeys(function ($name, $classId) {
            return [$classId => ucfirst(Str::slug($name, '_'))];
             })
            ->toArray();
            
            
            
           $table='merchant_follow_up_report';
           $columns = $classificationNamesArray; // Dynamic fields
        
        foreach ($columns as $column) {
        // Convert to snake_case for DB compatibility
        $columnName = $column;
        
        // Escape column name properly
        $quotedColumn = DB::getPdo()->quote($columnName);
        
        // Run raw SQL without parameter binding
        $exists = DB::select("SHOW COLUMNS FROM `$table` LIKE $quotedColumn");
        
        if (empty($exists)) {
        // Add the column (example: VARCHAR(255), nullable)
        DB::statement("ALTER TABLE `$table` ADD `$columnName` TEXT NULL");
        }
        }         
        
        
    




foreach ($Buyer_Purchase_Order_List as $row) {
    
    
                    $profit_value=0.0;
                    $profit_value=  ($row->order_rate - $row->total_cost_value);    
    
                   $cmohp1 = $row->production_value + $profit_value + $row->other_value;
                    $cmohp2 = $row->sam;
                    if($cmohp1 && $cmohp2)
                    {
                        $cmohp = round($cmohp1/$cmohp2,2);
                    }
                    else
                    {
                        $cmohp = 0;
                    }
    

         //DB::enableQueryLog();
         $rejectionData = DB::select("SELECT ifnull(sum(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail WHERE color_id = '". $row->color_id."' AND sales_order_no = '". $row->tr_code."' AND qcstitching_inhouse_reject_detail.qcsti_date <= '".$ReportDate."'");
         //dd(DB::getQueryLog());
         //$Ship=isset($row->shipped_qty2) ? $row->shipped_qty2 : 0;
         
         $reject_qty = isset($rejectionData[0]->reject_qty) ? $rejectionData[0]->reject_qty : 0;
         
         


    
    
    $rowData = [
        'userId'=>$row->userId,
        'order_no' => $row->tr_code,
        'buyer' => $row->ac_short_name,
        'merchant' => $row->merchant_name,
        'brand' => $row->brand_name,
        'brand_id'=>$row->brand_id,
        'main_style_category' => $row->mainstyle_name,
         'style' => $row->style_no,
         'garment_color'=>$row->color_name,
        'fabric_color' => $row->item_name . ' (' . $row->item_code . ')',
         'item_code'=>$row->item_code, 
         'order_qty'=>$row->total_qty,
         'sam'=>$row->sam,  
         'total_sale_taxable_amount'=>($row->size_qty),
         'cmohp'=>$cmohp,  
         'rate'=>$row->order_rate, 
         'po_no'=>$row->po_code,
         'cut_qty'=>$row->cut_qty,
         'current_status'=>$row->current_status,
         'shipment_date'=> date('d-M-Y', strtotime($row->shipment_date)),
         'shipment_month'=> date('M-Y', strtotime($row->shipment_date)),
         'rejection_pcs'=>$reject_qty,  
         'shipment_qty'=>$row->shipped_qty,  
         'bal_to_ship_qty'=>$row->adjust_qty,
         'updated_at'=>now()
         
    ];
    
    
    //  foreach ($classificationNamesArray as $className) {
    //     $rowData[$className] = $stockMap[$row->tr_code][$className][0]['qty'] ?? null;
    // }
    
    
        // foreach ($classificationNamesArray as $className) {
        // if (isset($stockMap[$row->tr_code][$className])) {
        // // Class name exists
        // $qty = $stockMap[$row->tr_code][$className][0]['qty'] ?? null;
        // $rowData[$className] = !empty($qty) ? round($qty) : 'Pending';
        // } else {
        // // Class name does not exist
        // $rowData[$className] = 'NA';
        // }
        // }
        
        
              $packingClasses = DB::table('bom_packing_trims_details')
            ->where('sales_order_no', $row->tr_code)
            ->pluck('class_id')
            ->toArray();
            
            $sewingClasses = DB::table('bom_sewing_trims_details')
            ->where('sales_order_no', $row->tr_code)
            ->pluck('class_id')
            ->toArray();
            
            foreach ($classificationNamesArray as $classId => $className) {
            $existsInPacking = in_array($classId, $packingClasses);
            $existsInSewing = in_array($classId, $sewingClasses);
            
            if (!$existsInPacking && !$existsInSewing) {
                // Not in either table
                $rowData[$className] = 'NA';
            } elseif (isset($stockMap[$row->tr_code][$className])) {
                $qty = $stockMap[$row->tr_code][$className][0]['qty'] ?? null;
                $rowData[$className] = !empty($qty) ? round($qty) : 'Pending';
            } else {
                // Exists in packing or sewing, but no stock
                $rowData[$className] = 'Pending';
            }
            }

    
    

    $insertData[] = $rowData;   
    
}

         
        

        $chunks = array_chunk($insertData, 500); 
        
        // foreach ($chunks as $chunk) {
        // DB::table('merchant_follow_up_report')->insertOrIgnore($chunk);
        // }
        
        
       // $columnsToUpdate = array_diff(array_keys($chunks['']), ['created_at']);
        
        foreach ($chunks as $chunk) {
        DB::table('merchant_follow_up_report')->upsert(
        $chunk,
        ['order_no', 'garment_color'],
        array_keys($chunk[0])
        );
        }

        
    }
     
     
     
    public function get_report_data()
    {
        
        
        
  $Buyer_Purchase_Order_List = DB::SELECT("SELECT merchant_follow_up_report.* from merchant_follow_up_report 
  where  merchant_follow_up_report.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId').")");

$data = [
     'id' => [],
    'KDPL' => [],
    'BUYER' => [],
    'Merchant' => [],
    'Brand' => [],
    'Main Style Category' => [],
    'Style Name'=>[],
    'Garment Color'=>[], 
    'Fabric Color'=>[],
    'Order Qty'=>[],  
    'Sam'=>[],
    'Taxable Amount'=>[],
    'CMOHP'=>[],
    'Rate'=>[],   
    'PO No.'=>[],
    'Embroidery'=>[],
    'Washing'=>[],
    'Print'=>[],
    'Fit Sample Approval Plan'=>[],
    'Fit Sample Approval Plan Actual'=>[],
    'fit_sample_approval_plan_date'=>[],
     'fit_sample_approval_actual_date'=>[],
     'TOP'=>[],
     'top_date'=>[],
     'Fabric Inhouse Date Plan'=>[],
     'fabric_inhouse_date_plan_date'=>[],
     'Fabric Inhouse Date Plan Actual'=>[], 
     'fabric_inhouse_date_plan_actual_date'=>[],
     'Fabric Inhouse Qty'=>[],
     'FPT Status'=>[],
     'GPT Status'=>[],
     'Production File Release Date Plan'=>[],
     'Production File Release Date Actual'=>[],
     'Cut Qty'=>[],
     'Current Status'=>[],
     'Shipment Date'=>[],
     'Shipment Month'=>[],
     'Rejection Pcs'=>[],
     'Shipment Qty'=>[],
     'Balance To Ship Qty'=>[]
     
     
];



    $dataStock = DB::table('stock_association_for_fabric')
    ->join('item_master', 'item_master.item_code', '=', 'stock_association_for_fabric.item_code')
    ->select('stock_association_for_fabric.sales_order_no','stock_association_for_fabric.item_code',DB::raw('ROUND(SUM(stock_association_for_fabric.qty),2) as qty'))
    ->where('tr_type',1)
    ->groupBy(
        'stock_association_for_fabric.item_code',
        'stock_association_for_fabric.sales_order_no',
        'stock_association_for_fabric.bom_code')
    ->get();
    
    
    $stockMap=[];
    
    foreach ($dataStock as $rowRecords)
    {
        
     $stockMap[$rowRecords->sales_order_no][$rowRecords->item_code][]=["qty"=>$rowRecords->qty];   
        
    }


         $orderList = DB::table('merchant_follow_up_report')
            ->whereRaw(" merchant_follow_up_report.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId').")")
            ->pluck('order_no')
            ->toArray();
            
            $classList = collect([
            DB::table('bom_packing_trims_details')
            ->whereIn('sales_order_no', $orderList)
            ->pluck('class_id'),
            
            DB::table('bom_sewing_trims_details')
            ->whereIn('sales_order_no', $orderList)
            ->pluck('class_id')
            ])->flatten()->unique()->values()->toArray();
            
  
            $classificationNamesArray = DB::table('classification_master')
            ->whereIn('class_id', $classList)
            ->pluck('class_name')
            ->map(function ($name) {
            // Slugify and then capitalize the first letter
            return ucfirst(Str::slug($name, '_'));
            })
            ->toArray();
   
            
            


foreach ($Buyer_Purchase_Order_List as $row) {
    
    
          $totalQty=0;
        if(isset($stockMap[$row->order_no][$row->item_code])) {
        foreach($stockMap[$row->order_no][$row->item_code] as $record) {
        $totalQty+=$record['qty'];
      
        }
        }
    

    
    $data['id'][]=$row->id;
    $data['KDPL'][] = $row->order_no;
    $data['BUYER'][] = $row->buyer; // Convert to string if needed
    $data['Merchant'][] = $row->merchant; // Adjust this if you want other pricing
    $data['Brand'][] = $row->brand; // Adjust to other level if needed
    $data['Main Style Category'][] = $row->main_style_category; // Format date
    $data['Style Name'][] = $row->style;
    $data['Garment Color'][] = $row->garment_color;  
    $data['Fabric Color'][] = $row->fabric_color;   
    $data['Order Qty'][] = $row->order_qty; 
    $data['Sam'][] = $row->sam;  
    $data['Taxable Amount'][]=$row->total_sale_taxable_amount;  
    $data['CMOHP'][] = $row->cmohp;   
    $data['Rate'][] = $row->rate;   
    $data['PO No.'][] = $row->po_no;     
    $data['Embroidery'][] = $row->embroidery;      
    $data['Washing'][] = $row->WashTypeId;       
    $data['Print'][] = $row->print;    
    $data['Fit Sample Approval Plan'][] = $row->fit_sample_approval_plan;  
    $data['Fit Sample Approval Plan Actual'][] = $row->fit_sample_approval_actual;   
    $data['fit_sample_approval_plan_date'][] = $row->fit_sample_approval_plan_date;    
    $data['fit_sample_approval_actual_date'][] = $row->fit_sample_approval_actual_date;      
    $data['TOP'][] = $row->top;
    $data['top_date'][]=$row->top_date;
    $data['Fabric Inhouse Date Plan'][]=$row->fabric_inhouse_date_plan; 
    $data['fabric_inhouse_date_plan_date'][]=$row->fabric_inhouse_date_plan_date;  
    $data['Fabric Inhouse Date Plan Actual'][]=$row->fabric_inhouse_date_plan_actual;  
    $data['fabric_inhouse_date_plan_actual_date'][]=$row->fabric_inhouse_date_plan_actual_date;   
    $data['Fabric Inhouse Qty'][]=$totalQty;  
    $data['FPT Status'][]=$row->fpt_status;   
    $data['fpt_status_date'][]=$row->fpt_status_date;     
    $data['GPT Status'][]=$row->gpt_status;  
    $data['gpt_status_date'][]=$row->gpt_status_date;    
    $data['Production File Release Date Plan'][]=$row->production_file_release_date_plan;    
    $data['Production File Release Date Actual'][]=$row->production_file_release_date_plan_actual;  
    $data['production_file_release_date_plan_date'][]=$row->production_file_release_date_plan_date;    
    $data['production_file_release_date_plan_actual_date'][]=$row->production_file_release_date_plan_actual_date;     
    $data['Cut Qty'][]=$row->cut_qty;      
    $data['Current Status'][]=$row->current_status;
    $data['Shipment Date'][]=$row->shipment_date;
    $data['Shipment Month'][]=$row->shipment_month;
    $data['Rejection Pcs'][]=$row->rejection_pcs;  
    $data['Shipment Qty'][]=$row->shipment_qty;  
    $data['Balance To Ship Qty'][]=$row->bal_to_ship_qty;   
  
  
        foreach ($classificationNamesArray as $classification) {

        $data[$classification][] = $row->{$classification} ?? '';
    }
    
   
}
     
        
        
//       $data = [
//     'name' => ["Updated Ashish", "Updated B", "Updated C", "Product D", "Product E", "Product F", "Product G", "Product H", "Product I", "Product J", "Product K", "Product L", "Product M", "Product N", "Product O"],
//     'category' => ["1", "2", "1", "1", "2", "1", "2", "1", "2", "1", "2", "1", "2", "1", "2"],
//     'price' => [110, 60, 210, 30, 25, 150, 75, 40, 35, 180, 60, 45, 30, 220, 55],
//     'quantity' => [15, 30, 20, 40, 20, 30, 35, 10, 45, 5, 50, 15, 25, 20, 30],
//     'date' => ["2025-01-20", "2025-02-25", "2025-03-15", "2025-01-25", "2025-04-05", "2025-02-14", "2025-03-22", "2025-04-18", "2025-01-08", "2025-08-28", "2025-03-15", "2025-04-10", "2025-01-30", "2025-02-05", "2025-03-28"]
// ];



return response()->json($data);

      
    }  
     
      public function handsontable()
    {
        
        
        
         $buyerList=DB::table('ledger_master')->select('ac_code','ac_short_name')->get()->toArray();
         
         $washTypeList=DB::table('wash_type_master')->select('WashTypeId','WashTypeName')->get()->toArray();  
         
         
         
         
            $orderList = DB::table('merchant_follow_up_report')
            ->whereRaw(" merchant_follow_up_report.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId').")")
            ->pluck('order_no')
            ->toArray();
            
            
             $totalRows=count($orderList); 
            
            
            
            $classList = collect([
            DB::table('bom_packing_trims_details')
            ->whereIn('sales_order_no', $orderList)
            ->pluck('class_id'),
            
            DB::table('bom_sewing_trims_details')
            ->whereIn('sales_order_no', $orderList)
            ->pluck('class_id')
            ])->flatten()->unique()->values()->toArray();
            
       
            $classificationNamesArray = DB::table('classification_master')
            ->whereIn('class_id', $classList)
            ->pluck('class_name')
            ->map(function ($name) {
            // Slugify and then capitalize the first letter
            return ucfirst(Str::slug($name, '_'));
            })
            ->toArray();
            
            
          // $testArray= ['test1','test2', 'test3'];
          
          
      /*  $table='merchant_follow_up_report';
        $columns = $classificationNamesArray; // Dynamic fields
        
        foreach ($columns as $column) {
        // Convert to snake_case for DB compatibility
        $columnName = $column;
        
        // Escape column name properly
        $quotedColumn = DB::getPdo()->quote($columnName);
        
        // Run raw SQL without parameter binding
        $exists = DB::select("SHOW COLUMNS FROM `$table` LIKE $quotedColumn");
        
        if (empty($exists)) {
        // Add the column (example: VARCHAR(255), nullable)
        DB::statement("ALTER TABLE `$table` ADD `$columnName` TEXT NULL");
        }
        }*/
     
        
        return view('excelReport',compact('buyerList','washTypeList','classificationNamesArray','totalRows'));
    }
    
    
            public function saveExcelData(Request $request)
            {
            // Get the JSON content
            $changes = $request->input('changes');
            
            // If changes is null, try to get from raw JSON
            if (is_null($changes)) {
                $input = json_decode($request->getContent(), true);
                $changes = $input['changes'] ?? [];
            }
            
            try {
                foreach ($changes as $change) {
                    // Debug output
                     \Log::debug('Processing change', [
                'row' => $change['row'] ?? 'N/A',
                'column' => $change['column'] ?? 'N/A',
                'value' => $change['value'] ?? 'N/A',
                'old_value' => $change['old_value'] ?? 'N/A'
            ]);
                    
                    // Update your database here
                    // Example:
                    DB::table('merchant_follow_up_report')->where('id', $change['row_id'])
                             ->update([$change['column'] => $change['value']]);
                }
                
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false, 
                    'message' => $e->getMessage()
                ], 500);
            }
            }
     
     
     
      public function bulkUpdateExcelData(Request $request)
    {
        
    }
     
    public function create()
    {
        

          
       // $operationList=DB::table('ob_masters')->select('operation_id','operation_name')->get();  
        
  
         
          $brandList = BrandModel::where('brand_master.delflag','=', '0')->get();
         
        
         $BuyerList=DB::table('brand_master')
         ->join('ledger_master','ledger_master.ac_code','=','brand_master.Ac_code')
         ->select('brand_master.brand_id','brand_master.Ac_code','ledger_master.ac_name','ledger_master.ac_short_name')->groupBy('brand_master.Ac_code')->get(); 
         
          $SalesOrderList= DB::table('buyer_purchse_order_master')
         ->whereIn('job_status_id',[1,5])
          ->get();
        
         
         
             $ClassList2 = DB::table('classification_master')->select('class_id', 'class_name')->get();
             $ItemList2 = ItemModel::where('delflag','=', '0')->get();
        
        return view('SOPOAuthorityMatrix',compact('brandList','BuyerList','SalesOrderList','ClassList2','ItemList2'));

    }
    
  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
       try {  
           
             
          $data = $request->all();
          
          //dd($data); exit;
          
          DB::beginTransaction();
           
           $id = $data['id'] ?? null;
           
           
           $data['so_po_authority_date']=date('Y-m-d');
     
        
            $IODetails = SOPOAuthorityMatrixModel::updateOrCreate(
                ['so_po_authority_id'=> $id],
                $data);
                
                
           
            DB::commit();
            $msg = "";

            if($id == null){
                $msg = 'Purchase order authority matrix saved successfully';
            } else {
                $msg = 'Purchase order authority matrix updated successfully';
            }


         return redirect()->route('so_po_authority_matrix.index')->with('message', $msg);
         
         } catch (\Exception $e) {
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
  
      DB::rollBack();
  
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
 
    }
    }
    
    
    
    
     public function po_matrix_detail(Request $request)
    {
        
        $ac_code=$request->ac_code;
        $brand_id=$request->brand_id;
        
         $fetch=DB::table('po_authority_matrix')->where('ac_code',$ac_code)->where('brand_id',$brand_id)->first();
       
       return response()->json($fetch);  
         
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SOPOAuthorityMatrixModel  $SOPOAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function show(SOPOAuthorityMatrixModel $SOPOAuthorityMatrixModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SOPOAuthorityMatrixModel  $SOPOAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         
        $POAMFetch =SOPOAuthorityMatrixModel::find($id);
        
        $brandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        
        $BuyerList=DB::table('brand_master')
         ->join('ledger_master','ledger_master.ac_code','=','brand_master.Ac_code')
         ->select('brand_master.brand_id','brand_master.Ac_code','ledger_master.ac_name','ledger_master.ac_short_name')->groupBy('brand_master.Ac_code')->get(); 
         
        $SalesOrderList= DB::table('buyer_purchse_order_master')->whereIn('job_status_id',[1,5])->get();
        
        $ClassList2 = DB::table('classification_master')->select('class_id', 'class_name')->get();
             
             
        if($POAMFetch->cat_id==1)
        {
            $table='bom_packing_trims_details';
            $PoMatrixData = DB::select("SELECT level1_packing_trim_extra_order as level1, level2_packing_trim_extra_order as level2, level3_packing_trim_extra_order as level3  FROM po_authority_matrix  WHERE ac_code = ? AND brand_id = ? ", [$POAMFetch->ac_code, $POAMFetch->brand_id]);

        } 
        else
        {
            $table='bom_sewing_trims_details';
            $PoMatrixData = DB::select("SELECT level1_sewing_trim_extra_order as level1, level2_sewing_trim_extra_order as level2, level3_sewing_trim_extra_order as level3 FROM po_authority_matrix WHERE ac_code = ? AND brand_id = ?", [$POAMFetch->ac_code, $POAMFetch->brand_id]);
        }
             
        //  DB::enableQueryLog();
    
        $itemList2 = DB::table($table)
          ->join('item_master','item_master.item_code','=',$table.'.'.'item_code')
          ->where('sales_order_no', $POAMFetch->sales_order_no)->get();
             
        //  dd(DB::getQueryLog());      
        return view('SOPOAuthorityMatrix',compact('brandList','BuyerList','SalesOrderList','ClassList2','itemList2','POAMFetch','PoMatrixData'));
    }
    
    
    public function get_item_codes(Request $request)
    { 
        
        if($request->cat_id==1)
        {
            $table='bom_packing_trims_details';
        } else{
            
             $table='bom_sewing_trims_details';
        }
        
        
        $html = '';
        if (!$request->sales_order_no) {
        $html = '<option value="">--Item List--</option>';
        } else {
       
         $html = '<option value="">--Item List--</option>';
         
        
         
        $itemList = DB::table($table)
        ->join('item_master','item_master.item_code','=',$table.'.'.'item_code')
        ->where('sales_order_no', $request->sales_order_no)->get();
        
     
        
        
        foreach ($itemList as $rowItem) {
                $html .= '<option value="'.$rowItem->item_code.'">'.$rowItem->item_name.' ('.$rowItem->item_code.')</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
    } 


        public function get_item_details(Request $request)
    { 
        
        if($request->cat_id==1)
        {
            $table='bom_packing_trims_details';
        } else{
            
             $table='bom_sewing_trims_details';
        }
        
        
    
         
        $itemList = DB::table($table)
        ->join('item_master','item_master.item_code','=',$table.'.'.'item_code')
        ->where('sales_order_no', $request->sales_order_no)->where($table.'.item_code',$request->item_code)->first();
        
        
        return response()->json($itemList);
    }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SOPOAuthorityMatrixModel  $SOPOAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
   
   


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SOPOAuthorityMatrixModel  $SOPOAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        SOPOAuthorityMatrixModel::where('so_po_authority_id',$id)->delete();
      
       // return redirect()->route('daily_production_entry.index')->with('message', 'Delete Record Succesfully');


    }

    
}
