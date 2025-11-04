<?php

namespace App\Http\Controllers;

use App\Models\Taluka;
use Illuminate\Http\Request; 
use App\Models\MaterialIssueMasterModel;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use Session;

class MaterialIssueController extends Controller
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
        ->where('form_id', '80')
        ->first();

        $issueStatusList = DB::table('issue_status_master')->where('delflag','=', '0')->get();
        
        $status_id = isset($request->status_id) ? $request->status_id : '3';
        $packing_type = isset($request->packing_type) ? $request->packing_type : '0';
       
        if($status_id == 0)
        {
            $status_id = '1,2,3';
        }
        
        if(Session::get('user_type') == 1 || Session::get('user_type') == 3)
        {
            // Query for Vendor Purchase Orders
            $VendorPurchaseOrderList = DB::table('vendor_purchase_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_purchase_order_master.vpo_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_purchase_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->orWhereNull('material_issue_master.issue_status_id')
                ->where(function ($query) use ($status_id) {
                    if ($status_id == 3) {
                        $query->where('material_issue_master.issue_status_id', '=', 3);
                    } else {
                        $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id));
                    }
                })
                ->select([
                    'vendor_purchase_order_master.vpo_code as process_no', 
                    'vendor_purchase_order_master.vpo_date as process_date', 
                    'final_bom_qty', 
                    'vendor_purchase_order_master.sales_order_no',
                    DB::raw('"purchase" as order_type'),  
                    'L2.Ac_name as vendorName', 
                    'vendor_purchase_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'),
                    'issue_status_master.issue_status_name', 
                    'material_issue_master.remark',
                    'ledger_master.Ac_name', 
                    'vendor_purchase_order_master.process_id as process_id','buyer_purchse_order_master.job_status_id'
                ]);
        
            // Query for Vendor Work Orders
            $VendorWorkOrderList = DB::table('vendor_work_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_work_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_work_order_master.vw_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_work_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->orWhereNull('material_issue_master.issue_status_id')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->where(function ($query) use ($status_id) {
                    if ($status_id == 3) {
                        $query->where('material_issue_master.issue_status_id', '=', 3);
                    } else {
                        $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id));
                    }
                })
                ->select([
                    'vendor_work_order_master.vw_code as process_no', 
                    'vendor_work_order_master.vw_date as process_date', 
                    'final_bom_qty', 
                    'vendor_work_order_master.sales_order_no',
                    DB::raw('"work" as order_type'),  
                    'L2.Ac_name as vendorName', 
                    'vendor_work_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'),
                    'issue_status_master.issue_status_name', 
                    'material_issue_master.remark',
                    'ledger_master.Ac_name', 
                    DB::raw('0 as blank_column'),'buyer_purchse_order_master.job_status_id'
                ]);
        
            $combinedQuery = $VendorPurchaseOrderList->union($VendorWorkOrderList);
        
            // Use the combined query as a subquery and apply groupBy on the necessary fields
            $VendorOrderList = DB::table(DB::raw("({$combinedQuery->toSql()}) as sub"))
                ->mergeBindings($combinedQuery)
                ->groupBy('process_no', 'sales_order_no')
                ->select('process_no', 'process_date', 'final_bom_qty', 'sales_order_no', 'order_type', 'vendorName', 'vendorId', 'issue_status_id', 'issue_status_name', 'remark', 'Ac_name', 'process_id','job_status_id')
                ->get();
        
            // Separate lists by issue status id and process_id
            $VF3 = $VendorOrderList->where('issue_status_id', 3)->where('process_id', 1)->where('job_status_id', 1);
            $VF2 = $VendorOrderList->where('issue_status_id', 2)->where('process_id', 1)->where('job_status_id', 1);
            $VF1 = $VendorOrderList->where('issue_status_id', 1)->where('process_id', 1)->where('job_status_id', 1);
             
            $VP3 = $VendorOrderList->where('issue_status_id', 3)->where('process_id', 3)->where('job_status_id', 1);
            $VP2 = $VendorOrderList->where('issue_status_id', 2)->where('process_id', 3)->where('job_status_id', 1);
            $VP1 = $VendorOrderList->where('issue_status_id', 1)->where('process_id', 3)->where('job_status_id', 1);
             
            $VS3 = $VendorOrderList->where('issue_status_id', 3)->where('process_id', 0)->where('job_status_id', 1);
            $VS2 = $VendorOrderList->where('issue_status_id', 2)->where('process_id', 0)->where('job_status_id', 1);
            $VS1 = $VendorOrderList->where('issue_status_id', 1)->where('process_id', 0)->where('job_status_id', 1);
             
            $FPendingCount = $VF3->count();
            $FPartial_count = $VF2->count();
            $Fcompleted_count = $VF1->count();
            
            $PPending_count = $VP3->count();
            $PPartial_count = $VP2->count();
            $PCompleted_count = $VP1->count();
              
            $SPending_count = $VS3->count();
            $SPartial_count = $VS2->count();
            $SCompleted_count = $VS1->count();
        }
    
        else if(Session::get('user_type') == 10)
        { 
                
             // Construct the initial query
            $VendorPurchaseOrderList = DB::table('vendor_purchase_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_purchase_order_master.vpo_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_purchase_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->whereIn('vendor_purchase_order_master.process_id', [1])
                ->where(function ($query) use ($status_id) {
                    if ($status_id == 3) {
                        // Show all data if status_id is 3
                        $query->where('material_issue_master.issue_status_id', '=', 3)
                              ->orWhereNull('material_issue_master.issue_status_id');
                    } else {
                        // Show only matching records for status_id 1 and 2
                        $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id));
                    }
                })
                ->select([
                    'vendor_purchase_order_master.vpo_code as process_no', 
                    'vendor_purchase_order_master.vpo_date as process_date', 
                    'final_bom_qty', 
                    'vendor_purchase_order_master.sales_order_no',
                    DB::raw('"purchase" as order_type'),  // Adding a column to identify the order type
                    'L2.Ac_name as vendorName', 
                    'vendor_purchase_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'), // Set default status_id to 3 if null
                    'issue_status_master.issue_status_name', 
                    'material_issue_master.remark',
                    'ledger_master.Ac_name', 
                    'vendor_purchase_order_master.process_id as process_id','buyer_purchse_order_master.job_status_id'
                ]);
            
            // Extract the SQL and bindings from the initial query
            $sql = $VendorPurchaseOrderList->toSql();
            $bindings = $VendorPurchaseOrderList->getBindings();
            
            // Use the extracted SQL and bindings in the subquery
            $VendorOrderList = DB::table(DB::raw("({$sql}) as sub"))
                ->mergeBindings($VendorPurchaseOrderList)
                ->groupBy('process_no', 'sales_order_no')
                ->select('process_no', 'process_date', 'final_bom_qty', 'sales_order_no', 'order_type', 'vendorName', 'vendorId', 'issue_status_id', 'issue_status_name', 'remark', 'Ac_name', 'process_id','job_status_id')
                ->get();
    
                
                 $status_id1 = '1,2,3';
            // Query for Vendor Purchase Orders
            $VendorPurchaseOrderList1 = DB::table('vendor_purchase_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_purchase_order_master.vpo_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_purchase_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->whereIn('vendor_purchase_order_master.process_id', [1])
                ->where(function ($query) use ($status_id1) 
                {
                    $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id1))->orWhereNull('material_issue_master.issue_status_id');
                })
                ->select(['vendor_purchase_order_master.vpo_code as process_no', 'vendor_purchase_order_master.vpo_date as process_date', 'final_bom_qty', 'vendor_purchase_order_master.sales_order_no',
                    DB::raw('"purchase" as order_type'),
                    'L2.Ac_name as vendorName', 'vendor_purchase_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'),
                    'issue_status_master.issue_status_name', 'material_issue_master.remark',
                    'ledger_master.Ac_name', 'vendor_purchase_order_master.process_id as process_id','buyer_purchse_order_master.job_status_id'
                ]);
             
            $sql1 = $VendorPurchaseOrderList1->toSql();
            $bindings1 = $VendorPurchaseOrderList1->getBindings();
            
            // Use the extracted SQL and bindings in the subquery
            $VendorOrderList1 = DB::table(DB::raw("({$sql1}) as sub"))
                ->mergeBindings($VendorPurchaseOrderList1)
                ->groupBy('process_no', 'sales_order_no')
                ->select('process_no', 'process_date', 'final_bom_qty', 'sales_order_no', 'order_type', 'vendorName', 'vendorId', 'issue_status_id', 'issue_status_name', 'remark', 'Ac_name', 'process_id','job_status_id')
                ->get(); 
                   // Separate lists by issue status id and process_id
            $VF3 = $VendorOrderList1->where('issue_status_id', 3)->where('process_id', 1)->where('job_status_id', 1);
            $VF2 = $VendorOrderList1->where('issue_status_id', 2)->where('process_id', 1)->where('job_status_id', 1);
            $VF1 = $VendorOrderList1->where('issue_status_id', 1)->where('process_id', 1)->where('job_status_id', 1);
             
            $VP3 = $VendorOrderList1->where('issue_status_id', 3)->where('process_id', 3)->where('job_status_id', 1);
            $VP2 = $VendorOrderList1->where('issue_status_id', 2)->where('process_id', 3)->where('job_status_id', 1)->where('job_status_id', 1);
            $VP1 = $VendorOrderList1->where('issue_status_id', 1)->where('process_id', 3)->where('job_status_id', 1);
             
            $VS3 = $VendorOrderList1->where('issue_status_id', 3)->where('process_id', 0)->where('job_status_id', 1);
            $VS2 = $VendorOrderList1->where('issue_status_id', 2)->where('process_id', 0)->where('job_status_id', 1);
            $VS1 = $VendorOrderList1->where('issue_status_id', 1)->where('process_id', 0)->where('job_status_id', 1);
             
            $FPendingCount = $VF3->count();
            $FPartial_count = $VF2->count();
            $Fcompleted_count = $VF1->count();
            
            $PPending_count = $VP3->count();
            $PPartial_count = $VP2->count();
            $PCompleted_count = $VP1->count();
              
            $SPending_count = $VS3->count();
            $SPartial_count = $VS2->count();
            $SCompleted_count = $VS1->count();
            
        } 
        else if(Session::get('user_type') == 11)
        {
                
            $VendorPurchaseOrderList = DB::table('vendor_purchase_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_purchase_order_master.vpo_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_purchase_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->whereIn('vendor_purchase_order_master.process_id', [3])
                ->where(function ($query) use ($status_id) {
                    if ($status_id == 3) {
                        // Show all data if status_id is 3
                        $query->where('material_issue_master.issue_status_id', '=', 3)
                              ->orWhereNull('material_issue_master.issue_status_id');
                    } else {
                        // Show only matching records for status_id 1 and 2
                        $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id));
                    }
                })
                ->select([
                    'vendor_purchase_order_master.vpo_code as process_no', 'vendor_purchase_order_master.vpo_date as process_date', 'final_bom_qty', 'vendor_purchase_order_master.sales_order_no',
                    DB::raw('"purchase" as order_type'),  // Adding a column to identify the order type
                    'L2.Ac_name as vendorName', 'vendor_purchase_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'), // Set default status_id to 3 if null
                    'issue_status_master.issue_status_name', 'material_issue_master.remark',
                    'ledger_master.Ac_name', 'vendor_purchase_order_master.process_id as process_id','buyer_purchse_order_master.job_status_id'
                ]); 
            
             
             $VendorWorkOrderList = DB::table('vendor_work_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_work_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_work_order_master.vw_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_work_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->where(function ($query) use ($status_id) {
                    if ($status_id == 3) {
                        // Show all data if status_id is 3
                        $query->where('material_issue_master.issue_status_id', '=', 3)
                              ->orWhereNull('material_issue_master.issue_status_id');
                    } else {
                        // Show only matching records for status_id 1 and 2
                        $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id));
                    }
                })
                ->select([
                    'vendor_work_order_master.vw_code as process_no', 'vendor_work_order_master.vw_date as process_date', 'final_bom_qty', 'vendor_work_order_master.sales_order_no',
                    DB::raw('"work" as order_type'),  // Adding a column to identify the order type
                    'L2.Ac_name as vendorName', 'vendor_work_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'), // Set default status_id to 3 if null
                    'issue_status_master.issue_status_name', 'material_issue_master.remark',
                    'ledger_master.Ac_name', DB::raw('0 as blank_column'),'buyer_purchse_order_master.job_status_id'
                ]);
                
            $combinedQuery = $VendorPurchaseOrderList->union($VendorWorkOrderList);
            
            // Use the combined query as a subquery and apply groupBy on the necessary fields
            $VendorOrderList = DB::table(DB::raw("({$combinedQuery->toSql()}) as sub"))
                ->mergeBindings($combinedQuery)
                ->groupBy('process_no', 'sales_order_no')
                ->select('process_no', 'process_date', 'final_bom_qty', 'sales_order_no', 'order_type', 'vendorName', 'vendorId', 'issue_status_id', 'issue_status_name', 'remark', 'Ac_name', 'process_id','job_status_id')
                ->get();
            
            
         
            $status_id1 = '1,2,3';
            // Query for Vendor Purchase Orders
            $VendorPurchaseOrderList1 = DB::table('vendor_purchase_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_purchase_order_master.vpo_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_purchase_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->whereIn('vendor_purchase_order_master.process_id', [3])
                ->where(function ($query) use ($status_id1) 
                {
                    $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id1))->orWhereNull('material_issue_master.issue_status_id');
                })
                ->select(['vendor_purchase_order_master.vpo_code as process_no', 'vendor_purchase_order_master.vpo_date as process_date', 'final_bom_qty', 'vendor_purchase_order_master.sales_order_no',
                    DB::raw('"purchase" as order_type'),
                    'L2.Ac_name as vendorName', 'vendor_purchase_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'),
                    'issue_status_master.issue_status_name', 'material_issue_master.remark',
                    'ledger_master.Ac_name', 'vendor_purchase_order_master.process_id as process_id','buyer_purchse_order_master.job_status_id'
                ]);
            
            // Query for Vendor Work Orders
            $VendorWorkOrderList1 = DB::table('vendor_work_order_master')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.Ac_code')
                ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_work_order_master.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no')
                ->leftJoin('material_issue_master', 'material_issue_master.process_no', '=', 'vendor_work_order_master.vw_code')
                ->leftJoin('issue_status_master', 'issue_status_master.issue_status_id', '=', 'material_issue_master.issue_status_id')
                ->where('vendor_work_order_master.delflag', '=', '0')
                ->where('buyer_purchse_order_master.og_id', '!=', '4')
                ->where('buyer_purchse_order_master.job_status_id', '=', '1')
                ->where(function ($query) use ($status_id1) {
                        $query->whereIn('material_issue_master.issue_status_id', explode(',', $status_id1))->orWhereNull('material_issue_master.issue_status_id');
                })
                ->select([
                    'vendor_work_order_master.vw_code as process_no', 'vendor_work_order_master.vw_date as process_date', 'final_bom_qty', 'vendor_work_order_master.sales_order_no',
                    DB::raw('"work" as order_type'),
                    'L2.Ac_name as vendorName', 'vendor_work_order_master.vendorId', 
                    DB::raw('COALESCE(material_issue_master.issue_status_id, 3) as issue_status_id'),
                    'issue_status_master.issue_status_name', 'material_issue_master.remark',
                    'ledger_master.Ac_name', DB::raw('0 as blank_column'),'buyer_purchse_order_master.job_status_id'
                ]);
            
            $combinedQuery1 = $VendorPurchaseOrderList1->union($VendorWorkOrderList1);
            
            $VendorOrderList1 = DB::table(DB::raw("({$combinedQuery1->toSql()}) as sub"))
                ->mergeBindings($combinedQuery1)
                ->groupBy('process_no', 'sales_order_no', 'process_date', 'final_bom_qty', 'order_type', 'vendorName', 'vendorId', 'issue_status_id', 'issue_status_name', 'remark', 'Ac_name', 'process_id')
                ->select('process_no', 'process_date', 'final_bom_qty', 'sales_order_no', 'order_type', 'vendorName', 'vendorId', 'issue_status_id', 'issue_status_name', 'remark', 'Ac_name', 'process_id','job_status_id')
                ->get();
            
          
                   // Separate lists by issue status id and process_id
            $VF3 = $VendorOrderList1->where('issue_status_id', 3)->where('process_id', 1)->where('job_status_id', 1);
            $VF2 = $VendorOrderList1->where('issue_status_id', 2)->where('process_id', 1)->where('job_status_id', 1);
            $VF1 = $VendorOrderList1->where('issue_status_id', 1)->where('process_id', 1)->where('job_status_id', 1);
             
            $VP3 = $VendorOrderList1->where('issue_status_id', 3)->where('process_id', 3)->where('job_status_id', 1);
            $VP2 = $VendorOrderList1->where('issue_status_id', 2)->where('process_id', 3)->where('job_status_id', 1);
            $VP1 = $VendorOrderList1->where('issue_status_id', 1)->where('process_id', 3)->where('job_status_id', 1);
             
            $VS3 = $VendorOrderList1->where('issue_status_id', 3)->where('process_id', 0)->where('job_status_id', 1);
            $VS2 = $VendorOrderList1->where('issue_status_id', 2)->where('process_id', 0)->where('job_status_id', 1);
            $VS1 = $VendorOrderList1->where('issue_status_id', 1)->where('process_id', 0)->where('job_status_id', 1);
             
            $FPendingCount = $VF3->count();
            $FPartial_count = $VF2->count();
            $Fcompleted_count = $VF1->count();
            
            $PPending_count = $VP3->count();
            $PPartial_count = $VP2->count();
            $PCompleted_count = $VP1->count();
              
            $SPending_count = $VS3->count();
            $SPartial_count = $VS2->count();
            $SCompleted_count = $VS1->count();
            
            
        }   
        else
        {
            $VendorOrderList = ""; 
            
            $FPendingCount = ""; 
            $FPartial_count = ""; 
            $Fcompleted_count = ""; 
            $SPending_count = ""; 
            $SPartial_count = ""; 
            $SCompleted_count = ""; 
            $PPending_count = ""; 
            $PPartial_count = ""; 
            $PCompleted_count = ""; 
    
        }
            
        
      
        
        return view('MaterialIssueMaster', compact('VendorOrderList','issueStatusList', 'chekform', 'FPendingCount', 
                    'FPartial_count', 'Fcompleted_count','PPending_count','PPartial_count','PCompleted_count',
                    'SPending_count','SPartial_count','SCompleted_count', 'packing_type', 'status_id'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $districtlist = DistrictModel::where('delflag','=', '0')->get();
        // $Countrylist = Country::where('delflag','=', '0')->get();
        // $statelist = DB::table('state_master')->where('delflag','=', '0')->get();
        // $talukalist = DB::table('taluka_master')->where('delflag','=', '0')->get();

        // return view('CityMaster',compact('Countrylist','statelist','districtlist', 'talukalist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        // echo '<pre>';print_R($_POST);exit;
        // $input = $request->all();

        // MaterialIssueMasterModel::create($input);

        return 1;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function show(Taluka $taluka)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $statelist = DB::table('state_master')->where('delflag','=', '0')->get();
        // $Countrylist = Country::where('delflag','=', '0')->get();
        // $districtlist = DistrictModel::where('delflag','=', '0')->get();
        // $talukalist = DB::table('taluka_master')->where('delflag','=', '0')->get();
        // $CityList = CityModel::find($id);

        // return view('CityMaster', compact('CityList','Countrylist','statelist','districtlist','talukalist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        // $CityList = CityModel::findOrFail($id);

        // $this->validate($request, [
        //     'country_id' => 'required',
        //       'state_id' => 'required',
        //     'dist_id' => 'required',
        //      'taluka_id' => 'required',
        //       'city_name' => 'required',
        // ]);

        // $input = $request->all();

        // $CityList->fill($input)->save();

        return redirect()->route('City.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    //   CityModel::where('city_id', $id)->update(array('delflag' => 1));
    //   Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function SaveMaterialIssue(Request $request)
    {
        $materialData = DB::table('material_issue_master')->where('process_no','=', $request->process_no)->where('sales_order_no','=', $request->sales_order_no)->where('vendorId','=', $request->vendorId)->delete();
        
        DB::table('material_issue_master')->insert([
                'vendorId' => $request->vendorId,
                'process_date' => $request->process_date, 
                'sales_order_no' => $request->sales_order_no, 
                'material_type_name' => $request->material_type_name, 
                'process_no' => $request->process_no, 
                'order_qty' => $request->order_qty,
                'issue_status_id' => $request->issue_status_id, 
                'remark' => $request->remark, 
                'userId' =>  Session::get('userId'),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
        ]);
        
        return 1;
      
    }
}
