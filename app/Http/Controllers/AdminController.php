<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\BuyerPurchaseOrderDetailModel;
use App\Models\SaleTransactionMasterModel;
use App\Models\SaleTransactionDetailModel;
use Session;
use DataTables;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;

setlocale(LC_MONETARY, 'en_IN'); 

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
  
        $Authicateuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
        ->where('form_auth.emp_id','=',Session::get('userId'))
        ->get(['form_master.form_code','form_master.form_label','form_master.form_name', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);
       
            $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: select('buyer_purchse_order_master.*','sales_order_costing_master.sam',DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
            , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty')
            )
            ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
            ->where('buyer_purchse_order_master.delflag','=', '0')
            ->where('buyer_purchse_order_master.og_id','!=', '4')
             ->where('buyer_purchse_order_master.job_status_id','=', '1')
            ->get();
         
            $total_valuec=0;
            $total_qtyc=0;
            $total_order_min=0;
            $total_shipped_qtyc= 0;
            $total_shipped_min = 0;
            $total_balance_qty = 0;
            $total_balance_min = 0;
            $total_produce_qty = 0;
            $total_produce_min = 0;
            $FGStock = 0;
            $total_fabric_value = 0;
            $total_trim_value = 0;
            $total_FGStock = 0;
            
            foreach($Buyer_Purchase_Order_List as $row)
            {
                $total_valuec=$total_valuec + $row->order_value; 
                $total_qtyc=$total_qtyc+$row->total_qty; 
                $total_order_min= $total_order_min + (($row->sam) * ($row->total_qty)); 
                $total_shipped_qtyc=$total_shipped_qtyc+$row->shipped_qty;
                
                $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."'
                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                     
                $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                
                $total_shipped_min = $total_shipped_min + ($Ship * $row->sam);
                $total_balance_qty = $total_balance_qty + $row->balance_qty;
                $total_balance_min = $total_balance_min + ($row->balance_qty * $row->sam);
                
                $FGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty' FROM `packing_inhouse_size_detail2` WHERE  packing_inhouse_size_detail2.sales_order_no = '". $row->tr_code."' GROUP by packing_inhouse_size_detail2.sales_order_no");
                    // dd(DB::getQueryLog());    
                 
                if($FGStockData != null)
                { 
                    $FGStock = $FGStockData[0]->packing_grn_qty - $FGStockData[0]->carton_pack_qty -  $FGStockData[0]->transfer_qty;
                }
                 
                $total_produce_qty = $total_produce_qty + ($row->balance_qty - $FGStock);
                $total_produce_min = $total_produce_min + ($row->sam * ($row->balance_qty - $FGStock)); 
                
                
            }
            
            $FabricInwardDetails =DB::select("select inward_details.*, (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter from inward_details");
            
            foreach($FabricInwardDetails as $row)
            {
                $total_fabric_value = $total_fabric_value + ($row->meter-$row->out_meter) * $row->item_rate;
            }
            
            
            
            $TrimsInwardDetails = DB::select("select trimsInwardDetail.*,(select ifnull(sum(item_qty),0) as item_qty  from trimsOutwardDetail 
                where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty 
                from trimsInwardDetail inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
                inner join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
                inner join item_master on item_master.item_code=trimsInwardDetail.item_code
                inner join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id");
                
            foreach($TrimsInwardDetails as $rows)
            {
                $total_trim_value = $total_trim_value + ((($rows->out_qty) - ($rows->item_qty)) * $rows->item_rate);
            }
            
            $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
                        DB::raw('sum(Gst_amount) as TotalGst'),
                        DB::raw('sum(Net_amount) as TotalNet'),
                        DB::raw('sum(total_qty) as TotalQty'))
                        ->where('sale_transaction_master.delflag','=', '0')
                        ->whereBetween('sale_date',array('2022-04-01','2023-04-01'))
                        ->get();
        
            $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
                from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
             
            $FGStockData1 = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                     (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty',
                    
                    
                    order_rate
                    FROM `packing_inhouse_size_detail2`
                    LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
                    LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
                    LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
                    GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
                // dd(DB::getQueryLog());    
                    
            foreach($FGStockData1 as $row1)
            {
                $total_FGStock = $total_FGStock + (($row1->packing_grn_qty - $row1->carton_pack_qty -  $row1->transfer_qty)) * ($row1->order_rate);
            }
            return view('dashboard',compact('Authicateuser','total_qtyc','total_order_min','total_shipped_qtyc','total_shipped_min','total_balance_qty','total_balance_min','total_produce_qty','total_produce_min','total_fabric_value','total_trim_value','SaleTotal','MonthList','total_FGStock'));

    }
    
    public function Exhibition()
    {
        return view('ken-b2b');
    }
    
    public function TestAnimation()
    {
        return view('TestAnimation');
    }
    
    
            public function activity_log(Request $request)
    {
        
        
     $ActivityLogListData = DB::table('packing_inhouse_activity_log')
     ->join('usermaster','usermaster.userId','=','packing_inhouse_activity_log.changed_by_user_id')
     ->leftJoin('color_master','color_master.color_id','=','packing_inhouse_activity_log.color_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListData->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
     
      $ActivityLogListData->orderBy('action_timestamp','DESC');
      $ActivityLogList=$ActivityLogListData->get();
     
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabels = [
     'pki_date' =>'Date',  
     'location_id' =>'Location'
     // Add more fields as needed
    ];
        
        
     $formattedChanges = [];

foreach ($ActivityLogList as $log) {
    $old = json_decode($log->old_data, true);
    $new = json_decode($log->new_data, true);
    
        $userName=$log->username;
        $action_timestamp=$log->action_timestamp;
        $pki_code=$log->pki_code;
        $color_name=$log->color_name;
        $size_array=$log->size_array; 
        
               
        $sizes = array_map('intval', explode(',', $size_array));
        
             
         
        
        $i = 0;

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            if ($key === 'location_id') {
                $oldValue = $location[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $location[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
    
              $sizeFetch=DB::table('size_detail')->where('size_id',$sizes[$i])->get();
            
            
              $fieldLabel = $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChanges[] = [
                'field' => $sizeFetch[0]->size_name ?? $fieldLabel,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'pki_code'=>$pki_code,
                'color_name'=>$color_name
                
            ];
        }
        
        $i++;
    }
}




  $currentPage = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPage = 100;

    // Convert array to Laravel Collection
    $collection = collect($formattedChanges);

    // Slice the data for the current page
    $currentPageResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

    // Create LengthAwarePaginator instance
    $paginatedChanges = new LengthAwarePaginator(
        $currentPageResults,
        $collection->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_rows', compact('paginatedChanges'))->render();
    }


//End PackingInhouse Detail


      //Carton Packing Inhouse 
      
      
      
           $ActivityLogListDataCarton = DB::table('carton_packing_inhouse_activity_log')
     ->join('usermaster','usermaster.userId','=','carton_packing_inhouse_activity_log.changed_by_user_id')
     ->leftJoin('color_master','color_master.color_id','=','carton_packing_inhouse_activity_log.color_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataCarton->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
     
      $ActivityLogListDataCarton->orderBy('action_timestamp','DESC');
      $ActivityLogListCarton=$ActivityLogListDataCarton->get();
     
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabelsCarton = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesCarton = [];

foreach ($ActivityLogListCarton as $logCarton) {
    $old = json_decode($logCarton->old_data, true);
    $new = json_decode($logCarton->new_data, true);
    
        $userName=$logCarton->username;
        $action_timestamp=$logCarton->action_timestamp;
        $cpki_code=$logCarton->cpki_code;
        $color_name=$logCarton->color_name;
        $size_array=$logCarton->size_array; 
        $sales_order_no=$logCarton->sales_order_no; 
        $action_type=$logCarton->action_type; 
       
               
        $sizes = array_map('intval', explode(',', $size_array));
        
             
         
        if($new!="")
        {
        $i = 0;
     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            if ($key === 'color_id') {
                $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
    
              $sizeFetchcarton=DB::table('size_detail')->where('size_id',$sizes[$i])->get();
            
            
              $fieldLabelCarton = $fieldLabelsCarton[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesCarton[] = [
                'field' => $sizeFetchcarton[0]->size_name ?? $fieldLabelCarton,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'cpki_code'=>$cpki_code,
                'color_name'=>$color_name,
                'sales_order_no'=>$sales_order_no, 
                'action_type'=>$action_type
                
            ];
        }
        
        $i++;
    }
        } else{
            
            
              $formattedChangesCarton[] = [
                'field' => 'DELETE',
                'old' => '',
                'new' => '',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'cpki_code'=>$cpki_code,
                'color_name'=>$color_name,
                'sales_order_no'=>$sales_order_no,
                 'action_type'=>$action_type
                
            ];     
            
            
            
            
        }
    
    
    
    
    
}




  $currentPageCarton = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageCarton = 100;

    // Convert array to Laravel Collection
    $collectionCarton = collect($formattedChangesCarton);

    // Slice the data for the current page
    $currentPageResults = $collectionCarton->slice(($currentPageCarton - 1) * $perPageCarton, $perPageCarton)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesCarton = new LengthAwarePaginator(
        $currentPageResults,
        $collectionCarton->count(),
        $perPageCarton,
        $currentPageCarton,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_rows_carton', compact('paginatedChangesCarton'))->render();
    }

      //Carton Packing Inhouse End
      
      //Sale Transaction Start
     
      
         $ActivityLogListDataSaleTra = DB::table('sale_transaction_activity_log')
     ->join('usermaster','usermaster.userId','=','sale_transaction_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataSaleTra->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
     
      $ActivityLogListDataSaleTra->orderBy('action_timestamp','DESC');
      $ActivityLogListSaleTra=$ActivityLogListDataSaleTra->get();
     
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabelSaleTransaction = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesST = [];

foreach ($ActivityLogListSaleTra as $logST) {
    $old = json_decode($logST->old_data, true);
    $new = json_decode($logST->new_data, true);
    
        $userName=$logST->username;
        $action_timestamp=$logST->action_timestamp;
        $sales_order_no=$logST->sales_order_no; 
        $action_type=$logST->action_type; 
        $sale_code=$logST->sale_code; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelSaleTransaction = $fieldLabelSaleTransaction[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesST[] = [
                'field' => $fieldLabelSaleTransaction,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'sale_code'=>$sale_code, 
                'sales_order_no'=>$sales_order_no, 
                'action_type'=>$action_type
                
            ];
        }
        
       
    }

    
    
}




  $currentPageST = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageST = 100;

    // Convert array to Laravel Collection
    $collectionST = collect($formattedChangesST);

    // Slice the data for the current page
    $currentPageResultsST = $collectionST->slice(($currentPageST - 1) * $perPageST, $perPageST)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesST = new LengthAwarePaginator(
        $currentPageResultsST,
        $collectionST->count(),
        $perPageST,
        $currentPageST,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_row_st', compact('paginatedChangesST'))->render();
    }
      
      
      //Sale Transaction End
      
         // TRIMS INWARD 
     
             
      
         $ActivityLogListDataTrimsIO = DB::table('trims_inward_outward_activity_log')
     ->join('usermaster','usermaster.userId','=','trims_inward_outward_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataTrimsIO->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataTrimsIO->orderBy('action_timestamp','DESC');
      $ActivityLogListTrimsIO=$ActivityLogListDataTrimsIO->get();
      
      
      $TrimsINOUTMAP=[];
      
      foreach($ActivityLogListTrimsIO as $rowTIO)
      {
          
          $TrimsINOUTMAP[$rowTIO->module_name][]=$rowTIO;
          
      }
      
      
      
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabelTIO = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesTIO = [];

foreach ($TrimsINOUTMAP['TRIMS_INWARD'] as $logTIO) {
    $old = json_decode($logTIO->old_data, true);
    $new = json_decode($logTIO->new_data, true);
    
        $userName=$logTIO->username;
        $action_timestamp=$logTIO->action_timestamp;
        $item_code=$logTIO->item_code; 
        $action_type=$logTIO->action_type; 
        $trCode=$logTIO->trCode; 
        $module_name= $logTIO->module_name; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelTIO = $fieldLabelTIO[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesTIO[] = [
                'field' => $fieldLabelTIO,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'trCode'=>$trCode, 
                'item_code'=>$item_code, 
                'action_type'=>$action_type,
                'module_name'=>$module_name
                
            ];
        }
        
       
    }

    
    
}



  $currentPageTIO = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageTIO = 100;

    // Convert array to Laravel Collection
    $collectionTIO = collect($formattedChangesTIO);

    // Slice the data for the current page
    $currentPageResultsTIO = $collectionTIO->slice(($currentPageTIO - 1) * $perPageTIO, $perPageTIO)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesTIO = new LengthAwarePaginator(
        $currentPageResultsTIO,
        $collectionTIO->count(),
        $perPageTIO,
        $currentPageTIO,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_row_TIO', compact('paginatedChangesTIO'))->render();
    }
      
         // TRIMS INWARD  END


      // TRIMS OUTWARD  START

     $fieldLabelOutward = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesOutward = [];

foreach ($TrimsINOUTMAP['TRIMS_OUTWARD'] as $logOutward) {
    $old = json_decode($logOutward->old_data, true);
    $new = json_decode($logOutward->new_data, true);
    
        $userName=$logOutward->username;
        $action_timestamp=$logOutward->action_timestamp;
        $item_code=$logOutward->item_code; 
        $action_type=$logOutward->action_type; 
        $trCode=$logOutward->trCode; 
        $module_name= $logOutward->module_name; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelOutward = $fieldLabelOutward[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesOutward[] = [
                'field' => $fieldLabelOutward,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'trCode'=>$trCode, 
                'item_code'=>$item_code, 
                'action_type'=>$action_type,
                'module_name'=>$module_name
                
            ];
        }
        
       
    }

    
    
}



  $currentPageOUTWARD = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageOUTWARD = 100;

    // Convert array to Laravel Collection
    $collectionOUTWARD = collect($formattedChangesOutward);

    // Slice the data for the current page
    $currentPageResultsOUTWARD = $collectionOUTWARD->slice(($currentPageOUTWARD - 1) * $perPageOUTWARD, $perPageOUTWARD)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesOUTWARD = new LengthAwarePaginator(
        $currentPageResultsOUTWARD,
        $collectionOUTWARD->count(),
        $perPageOUTWARD,
        $currentPageOUTWARD,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_row_outward', compact('paginatedChangesOUTWARD'))->render();
    }
      
         // TRIMS OUTWARD  END
         
         
         //Fabric Checking Start
         
         $ActivityLogListDataFabricCheking = DB::table('fabric_checking_activity_log')
     ->join('usermaster','usermaster.userId','=','fabric_checking_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataFabricCheking->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataFabricCheking->orderBy('action_timestamp','DESC');
      $ActivityLogListFabricChecking=$ActivityLogListDataFabricCheking->get();
      
      
      
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabelFC = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesFC = [];

foreach ($ActivityLogListFabricChecking as $logFC) {
    $old = json_decode($logFC->old_data, true);
    $new = json_decode($logFC->new_data, true);
    
        $userName=$logFC->username;
        $action_timestamp=$logFC->action_timestamp;
        $chk_code=$logFC->chk_code; 
        $action_type=$logFC->action_type; 
        $track_code=$logFC->track_code; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelChecking = $fieldLabelChecking[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesFC[] = [
                'field' => $fieldLabelChecking,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'track_code'=>$track_code, 
                'chk_code'=>$chk_code, 
                'action_type'=>$action_type,
                'module_name'=>$module_name
                
            ];
        }
       
    }

}


  $currentPageFC = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageFC = 100;

    // Convert array to Laravel Collection
    $collectionFC = collect($formattedChangesFC);

    // Slice the data for the current page
    $currentPageResultsFC = $collectionFC->slice(($currentPageFC - 1) * $perPageFC, $perPageFC)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesFC = new LengthAwarePaginator(
        $currentPageResultsFC,
        $collectionFC->count(),
        $perPageFC,
        $currentPageFC,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_rows_fc', compact('paginatedChangesFC'))->render();
    }
      
         
         //Fabric Checking End
         
         
         
           //Fabric Inward Start
         
         $ActivityLogListDataFabricInward = DB::table('fabric_inward_activity_log')
     ->join('usermaster','usermaster.userId','=','fabric_inward_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataFabricInward->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataFabricInward->orderBy('action_timestamp','DESC');
      $ActivityLogListDataFabricInward=$ActivityLogListDataFabricInward->get();
      
      
      
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabelInward = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesInward = [];

foreach ($ActivityLogListDataFabricInward as $logInward) {
    $old = json_decode($logInward->old_data, true);
    $new = json_decode($logInward->new_data, true);
    
        $userName=$logInward->username;
        $action_timestamp=$logInward->action_timestamp;
        $in_code=$logInward->in_code; 
        $action_type=$logInward->action_type; 
        $track_code=$logInward->track_code; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelInward = $fieldLabelInward[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesInward[] = [
                'field' => $fieldLabelInward,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'track_code'=>$track_code, 
                'in_code'=>$in_code, 
                'action_type'=>$action_type,
                'module_name'=>$module_name
                
            ];
        }
       
    }

}


  $currentPageInward = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageInward = 100;

    // Convert array to Laravel Collection
    $collectionInward = collect($formattedChangesInward);

    // Slice the data for the current page
    $currentPageResultsInward = $collectionInward->slice(($currentPageInward - 1) * $perPageInward, $perPageInward)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesInward = new LengthAwarePaginator(
        $currentPageResultsInward,
        $collectionInward->count(),
        $perPageInward,
        $currentPageInward,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_rows_inward', compact('paginatedChangesInward'))->render();
    }
      
         
         //Fabric Inward End
         
         
         
         
                    //Fabric Outward Start
         
         $ActivityLogListDataFabricOutward = DB::table('fabric_outward_activity_log')
     ->join('usermaster','usermaster.userId','=','fabric_outward_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataFabricOutward->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataFabricOutward->orderBy('action_timestamp','DESC');
      $ActivityLogListDataFabricOutward=$ActivityLogListDataFabricOutward->get();
      
      
      
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabelOutward = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesOutward = [];

foreach ($ActivityLogListDataFabricOutward as $logOutward) {
    $old = json_decode($logOutward->old_data, true);
    $new = json_decode($logOutward->new_data, true);
    
        $userName=$logOutward->username;
        $action_timestamp=$logOutward->action_timestamp;
        $fout_code=$logOutward->fout_code; 
        $action_type=$logOutward->action_type; 
        $track_code=$logOutward->track_code; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelOutward = $fieldLabelOutward[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesOutward[] = [
                'field' => $fieldLabelOutward,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'track_code'=>$track_code, 
                'fout_code'=>$fout_code, 
                'action_type'=>$action_type,
                'module_name'=>$module_name
                
            ];
        }
       
    }

}


  $currentPageOutward = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageOutward = 100;

    // Convert array to Laravel Collection
    $collectionOutward = collect($formattedChangesOutward);

    // Slice the data for the current page
    $currentPageResultsOutward = $collectionOutward->slice(($currentPageOutward - 1) * $perPageOutward, $perPageOutward)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesOutward = new LengthAwarePaginator(
        $currentPageResultsOutward,
        $collectionOutward->count(),
        $perPageOutward,
        $currentPageOutward,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_rows_outward', compact('paginatedChangesOutward'))->render();
    }
      
         
         //Fabric Outward End
         
         


        return view('activityLog',compact('paginatedChanges','paginatedChangesCarton','paginatedChangesST','paginatedChangesTIO','paginatedChangesOUTWARD','paginatedChangesFC','paginatedChangesInward','paginatedChangesOutward'));

    }
    
    
    
    
                public function activity_inward_log(Request $request)
            {
        

           //Fabric Inward Start
         
         $ActivityLogListDataFabricInward = DB::table('fabric_inward_activity_log')
     ->join('usermaster','usermaster.userId','=','fabric_inward_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataFabricInward->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataFabricInward->orderBy('action_timestamp','DESC');
      $ActivityLogListDataFabricInward=$ActivityLogListDataFabricInward->paginate(100);
      
      
      
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     


    
     if ($request->ajax()) {
        return view('partials.change_rows_inward_new', compact('ActivityLogListDataFabricInward'))->render();
    }
      
         
         //Fabric Inward End
         
         
         
                             //Fabric Outward Start
         
         $ActivityLogListDataFabricOutward = DB::table('fabric_outward_activity_log')
     ->join('usermaster','usermaster.userId','=','fabric_outward_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataFabricOutward->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataFabricOutward->orderBy('action_timestamp','DESC');
      $ActivityLogListDataFabricOutward=$ActivityLogListDataFabricOutward->get();
      
      
      
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
     
     $fieldLabelOutward = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesOutward = [];

foreach ($ActivityLogListDataFabricOutward as $logOutward) {
    $old = json_decode($logOutward->old_data, true);
    $new = json_decode($logOutward->new_data, true);
    
        $userName=$logOutward->username;
        $action_timestamp=$logOutward->action_timestamp;
        $fout_code=$logOutward->fout_code; 
        $action_type=$logOutward->action_type; 
        $track_code=$logOutward->track_code; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelOutward = $fieldLabelOutward[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesOutward[] = [
                'field' => $fieldLabelOutward,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'track_code'=>$track_code, 
                'fout_code'=>$fout_code, 
                'action_type'=>$action_type
               
                
            ];
        }
       
    }

}


  $currentPageOutward = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageOutward = 100;

    // Convert array to Laravel Collection
    $collectionOutward = collect($formattedChangesOutward);

    // Slice the data for the current page
    $currentPageResultsOutward = $collectionOutward->slice(($currentPageOutward - 1) * $perPageOutward, $perPageOutward)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesOutward = new LengthAwarePaginator(
        $currentPageResultsOutward,
        $collectionOutward->count(),
        $perPageOutward,
        $currentPageOutward,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_rows_outward', compact('paginatedChangesOutward'))->render();
    }
      
         
         //Fabric Outward End
         
         
         
                  // TRIMS INWARD 
     
             
      
         $ActivityLogListDataTrimsIO = DB::table('trims_inward_outward_activity_log')
     ->join('usermaster','usermaster.userId','=','trims_inward_outward_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataTrimsIO->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataTrimsIO->orderBy('action_timestamp','DESC');
      $ActivityLogListTrimsIO=$ActivityLogListDataTrimsIO->paginate(2);
      
          $TrimsINOUTMAP=[];
      
      foreach($ActivityLogListTrimsIO as $rowTIO)
      {
          
          $TrimsINOUTMAP[$rowTIO->module_name][]=$rowTIO;
          
      }
  
      
      
      
     
     $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
     
     $location = DB::table('location_master')->pluck('location','loc_id')->toArray();
     
     
     
     $UserChange= DB::table('usermaster')->pluck('username', 'userId')->toArray();  
      
    
     if ($request->ajax()) {
        return view('partials.change_row_trim_inward', compact('ActivityLogListTrimsIO'))->render();
    }
      
         // TRIMS INWARD  END


      // TRIMS OUTWARD  START

     $fieldLabelOutward = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesOutward = [];

foreach ($TrimsINOUTMAP['TRIMS_OUTWARD'] as $logOutward) {
    $old = json_decode($logOutward->old_data, true);
    $new = json_decode($logOutward->new_data, true);
    
        $userName=$logOutward->username;
        $action_timestamp=$logOutward->action_timestamp;
        $item_code=$logOutward->item_code; 
        $action_type=$logOutward->action_type; 
        $trCode=$logOutward->trCode; 
        $module_name= $logOutward->module_name; 
               

     

    foreach ($new as $key => $newValue) {
        $oldValue = $old[$key] ?? null;

        if ($oldValue != $newValue) {
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelOutward = $fieldLabelOutward[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesOutward[] = [
                'field' => $fieldLabelOutward,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'trCode'=>$trCode, 
                'item_code'=>$item_code, 
                'action_type'=>$action_type,
                'module_name'=>$module_name
                
            ];
        }
        
       
    }

    
    
}



  $currentPageOUTWARD = LengthAwarePaginator::resolveCurrentPage();

    // Define how many items per page
    $perPageOUTWARD = 2;

    // Convert array to Laravel Collection
    $collectionOUTWARD = collect($formattedChangesOutward);

    // Slice the data for the current page
    $currentPageResultsOUTWARD = $collectionOUTWARD->slice(($currentPageOUTWARD - 1) * $perPageOUTWARD, $perPageOUTWARD)->values();

    // Create LengthAwarePaginator instance
    $paginatedChangesOUTWARD = new LengthAwarePaginator(
        $currentPageResultsOUTWARD,
        $collectionOUTWARD->count(),
        $perPageOUTWARD,
        $currentPageOUTWARD,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    
     if ($request->ajax()) {
        return view('partials.change_row_outward', compact('paginatedChangesOUTWARD'))->render();
    }
      
         // TRIMS OUTWARD  END
         
         
         
         
         
         
         
         

        return view('activityLogInward',compact('ActivityLogListDataFabricInward','paginatedChangesOutward','ActivityLogListTrimsIO','paginatedChangesOUTWARD'));

    }
    
    
    
    
        
           public function activity_sales_order_log(Request $request)
            {
  
         
         $ActivityLogListDataSalesOrder = DB::table('sales_order_detail_activity_log')
     ->join('usermaster','usermaster.userId','=','sales_order_detail_activity_log.changed_by_user_id')
      ->leftJoin('color_master','color_master.color_id','=','sales_order_detail_activity_log.color_id');
    
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataSalesOrder->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataSalesOrder->orderBy('action_timestamp','DESC');
      $ActivityLogListDataSalesOrderList=$ActivityLogListDataSalesOrder->paginate(100);
      
     
     

    
     if ($request->ajax()) {
        return view('partials.change_rows_sales_order', compact('ActivityLogListDataSalesOrderList'))->render();
    }
      
         
         
        return view('activityLogSalesOrder',compact('ActivityLogListDataSalesOrderList'));

    }
    
    
    
               public function activity_purchase_order_log(Request $request)
            {
  
         
        $ActivityLogListDataPurchaseOrder = DB::table('purchase_order_detail_activity_log')
       ->join('usermaster','usermaster.userId','=','purchase_order_detail_activity_log.changed_by_user_id');
    
     
      if($request->fromDate!="" && $request->toDate!="")
      {
      $ActivityLogListDataPurchaseOrder->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate, $request->toDate]);
      } else{
          
          
      }
    
      $ActivityLogListDataPurchaseOrder->orderBy('action_timestamp','DESC');
      $ActivityLogListDataPurchaseOrderList=$ActivityLogListDataPurchaseOrder->paginate(100);
      
     
     

    
     if ($request->ajax()) {
        return view('partials.change_purchase_order', compact('ActivityLogListDataPurchaseOrderList'))->render();
    }
      
         
         
        return view('activityLogPurchaseOrder',compact('ActivityLogListDataPurchaseOrderList'));

    }
    
    
    
    
    
               public function activity_sales_order_costing_log(Request $request)
            {
                
               
          
         
         $ActivityLogListDataSalesOrder = DB::table('sales_order_fabric_costing_activity_log')
     ->join('usermaster','usermaster.userId','=','sales_order_fabric_costing_activity_log.changed_by_user_id')
      ->leftJoin('classification_master','classification_master.class_id','=','sales_order_fabric_costing_activity_log.class_id');
      
      
      
 if ($request->type != "" || $request->typesecond != "") {
    $ActivityLogListDataSalesOrder->where(function ($query) use ($request) {
        if ($request->type != "") {
            $query->where('module_name', $request->type);
        }

        if ($request->typesecond != "") {
            $query->orWhere('module_name', $request->typesecond);
        }
    });
}

      
      
         if($request->fromDate!="" && $request->toDate!="")
      {
          
      $ActivityLogListDataSalesOrder->whereBetween(DB::raw('DATE(action_timestamp)'), [$request->fromDate,$request->toDate]);
      } else{
          
          
      }

      
   
      

      $ActivityLogListDataSalesOrder->orderBy('action_timestamp','DESC');
      
//       dd(
//     $ActivityLogListDataSalesOrder->toSql(),
//     $ActivityLogListDataSalesOrder->getBindings()
// );
      
      $ActivityLogListDataSalesOrderList=$ActivityLogListDataSalesOrder->get();
      
     

    
     if ($request->ajax()) {
        return view('partials.change_rows_sales_order_costing', compact('ActivityLogListDataSalesOrderList'))->render();
    }
      
           $type=$request->type;
           
           
        return view('activityLogSalesOrderCosting',compact('ActivityLogListDataSalesOrderList','type'));

    }
    
    
    
    public function ProductMaster()
    { 
        $productList = DB::SELECT("SELECT * FROM exhibition_product");
        $productCatList = DB::SELECT("SELECT * FROM main_product_category");
        $productTypeList = DB::SELECT("SELECT * FROM product_filters WHERE main_product_cat_id = 1");
        $productQualityList = DB::SELECT("SELECT * FROM product_filters WHERE main_product_cat_id = 2");
        $productWidthList = DB::SELECT("SELECT * FROM product_filters WHERE main_product_cat_id = 3");
        $productWeaveList = DB::SELECT("SELECT * FROM product_filters WHERE main_product_cat_id = 4");
        $productGSMList = DB::SELECT("SELECT * FROM product_filters WHERE main_product_cat_id = 5");
        $productContentList = DB::SELECT("SELECT * FROM product_filters WHERE main_product_cat_id = 6");
        $productEndUseList = DB::SELECT("SELECT * FROM product_filters WHERE main_product_cat_id = 7");
        return view('ProductMaster', compact('productList', 'productCatList','productTypeList','productQualityList','productWidthList','productWeaveList','productGSMList','productContentList','productEndUseList'));
    }
    
    
    public function ProductFilterCategoryList()
    { 
        $filterList = DB::SELECT("SELECT product_filters.*,main_product_category.main_product_cat_name FROM product_filters INNER JOIN main_product_category ON main_product_category.main_product_cat_id = product_filters.main_product_cat_id");
        $productCatList = DB::SELECT("SELECT * FROM main_product_category");
        return view('ProductFilterCategoryList', compact('filterList', 'productCatList'));
    }
    
    public function NewProductStore(Request $request)
    { 
       DB::table('product_filters')->insert([
            "filter_name"=>$request->filter_name,
            "main_product_cat_id"=>$request->main_product_cat_id,
            "delflag"=>0,
            "created_at"=>date("Y-m-d H:i:s"),
        ]);
        
        return 1;
    }
    
    public function UpdateProductFilter(Request $request)
    { 
        $updated = DB::table('product_filters')->where('filter_id', $request->filter_id)->update([
            "filter_name"=>$request->filter_name,
            "main_product_cat_id"=>$request->main_product_cat_id,
            "delflag"=>0,
            "updated_at"=>date("Y-m-d H:i:s"),
        ]);
        
        return 1;
    }
    
    public function UpdateExProductDetails(Request $request)
    {  
        if($request->ex_product_id > 0)
        {
             //DB::enableQueryLog();
            $updated = DB::table('exhibition_product')->where('ex_product_id', $request->ex_product_id)->update([
                'type' => $request->type,
                'sort_no' => $request->sort_no,
                'quality' => $request->quality,
                'width' => $request->width,
                'width_range' => $request->width_range,
                'OT_OL' => $request->OT_OL,
                'weave' => $request->weave,
                'weave_id' => $request->weave_id,
                'gsm' => $request->gsm,
                'gsm_range' => $request->gsm_range,
                'content' => $request->content,
                'content_id' => $request->content_id,
                'rate' => $request->rate,
                'quantity' => $request->quantity,
                'end_use' => $request->end_use,
                "updated_at"=>date("Y-m-d H:i:s"),
            ]);
             //dd(DB::getQueryLog());
        }
        else
        {
            DB::table('exhibition_product')->insert([
               'type' => $request->type,
                'sort_no' => $request->sort_no,
                'quality' => $request->quality,
                'width' => $request->width,
                'width_range' => $request->width_range,
                'OT_OL' => $request->OT_OL,
                'weave' => $request->weave,
                'weave_id' => $request->weave_id,
                'gsm' => $request->gsm,
                'gsm_range' => $request->gsm_range,
                'content' => $request->content, 
                'content_id' => $request->content_id,
                'rate' => $request->rate,
                'quantity' => $request->quantity,
                'end_use' => $request->end_use,
                "created_at"=>date("Y-m-d H:i:s"),
            ]);
        }
        
        return 1;
    }
    
    public function DeleteProduct($id)
    {
       
        DB::table('exhibition_product')->where('ex_product_id', $id)->delete();
       
        Session::flash('delete', 'Deleted record successfully'); 
    } 
    
    public function DeleteProductSubFilter($id)
    {
        DB::table('product_filters')->where('filter_id', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
    } 
    
    public function ProductUploadImage(Request $request)
    {
        // Validate the file input
        // $request->validate([
        //     'attachment' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
        // ]);
    
        $id = $request->input('ex_product_id');
        $attachment = $request->file('attachment');
        
        if($attachment != '')
        {
            // Generate a unique file name
            $fileName = time() . '_' . $attachment->getClientOriginalName();
        
            // Define the upload directory
            $location = public_path('uploads/Exhibition/');
        
            // Check if a record exists in the attachment table for this ID
            $existingAttachment = DB::table('exhibition_product')->where('ex_product_id', $id)->value('attachment');
        
            // Delete the old file if it exists
            if ($existingAttachment && file_exists($location . $existingAttachment)) {
                unlink($location . $existingAttachment);
            }
        
            // Move the uploaded file to the target directory
            $attachment->move($location, $fileName);
        
            // Update the database with the new file name
            $updated = DB::table('exhibition_product')->where('ex_product_id', $id)->update([
                "attachment" => $fileName,
            ]);
        
            if ($updated) {
                return redirect()->route('ProductMaster')->with('success', 'Image uploaded and updated successfully.');
            }
        }
        return redirect()->route('ProductMaster')->with('error', 'Failed to update the database.');
    }


    public function ExhibitionProduct($id)
    {
        $type = $id;
        
        // Process the results into separate category lists for the view
        $categoryList1 = DB::SELECT("SELECT product_filters.* FROM product_filters WHERE main_product_cat_id = 7 GROUP BY filter_id");
        $categoryList2 = DB::SELECT("SELECT product_filters.* FROM product_filters WHERE main_product_cat_id = 4 GROUP BY filter_id");
        $categoryList3 = DB::SELECT("SELECT product_filters.* FROM product_filters WHERE main_product_cat_id = 6 GROUP BY filter_id");
        $categoryList4 = DB::SELECT("SELECT product_filters.* FROM product_filters WHERE main_product_cat_id = 5 GROUP BY filter_id");
        $categoryList5 = DB::SELECT("SELECT product_filters.* FROM product_filters WHERE main_product_cat_id = 3 GROUP BY filter_id");
                            
    
        return view('ken-b2bProduct', compact('categoryList1', 'categoryList2', 'categoryList3', 'categoryList4', 'categoryList5', 'type'));
    }

   

    public function ExhibitionProductList()
    { 
        // Process the results into separate category lists for the view
        $productList = DB::SELECT("SELECT 
                pf1.filter_name AS content_range, 
                pf2.filter_name AS width_range, 
                pf3.filter_name AS weave_range, 
                pf4.filter_name AS gsm_range, 
                pf5.filter_name AS end_use_name,
                pf6.filter_name AS type_name,
                pf3.filter_name AS weave, 
                ep.sort_no, 
                ep.quality, 
                ep.attachment, 
                ep.quantity,
                ep.rate, 
                ep.OT_OL, 
                ep.content,
                ep.width, 
                ep.gsm
          FROM exhibition_product AS ep
          LEFT JOIN product_filters AS pf1 ON pf1.filter_name = ep.content_id AND pf1.main_product_cat_id = 6
          LEFT JOIN product_filters AS pf2 ON pf2.filter_name = ep.width_range AND pf2.main_product_cat_id = 3
          LEFT JOIN product_filters AS pf3 ON pf3.filter_name = ep.weave_id AND pf3.main_product_cat_id = 4
          LEFT JOIN product_filters AS pf4 ON pf4.filter_name = ep.gsm_range AND pf4.main_product_cat_id = 5
          LEFT JOIN product_filters AS pf5 ON pf5.filter_name = ep.end_use AND pf5.main_product_cat_id = 7
          LEFT JOIN product_filters AS pf6 ON pf6.filter_name = ep.type AND pf6.main_product_cat_id = 1");
                            
    
        return view('ExhibitionProductList', compact('productList'));
    }
    
    public function LoadExhibitionProducts(Request $request)
    {
        
        $end_use_filter = $request->end_use_filter ? $request->end_use_filter : '';
        $weave_filter = $request->weave_filter ? $request->weave_filter : '';
        $content_filter = $request->content_filter ? $request->content_filter : '';
        $gsm_filter = $request->gsm_filter ? $request->gsm_filter : '';
        $width_filter = $request->width_filter ? $request->width_filter : '';
        //DB::enableQueryLog();
        // Initialize the base query
        $type = '';
        if($request->type == 1)
        {
            $type="Ready Product Offerings";
        }
        else if($request->type == 2)
        {
            $type="New Developments";
        }
        $query = "SELECT 
                ep.sort_no, 
                ep.quality, 
                ep.attachment, 
                ep.quantity, 
                ep.end_use, 
                ep.rate, 
                ep.OT_OL, 
                ep.width, 
                ep.gsm, 
                ep.weave, 
                ep.content,
                ep.content_id,
                ep.weave_id
          FROM exhibition_product AS ep
          WHERE 1"; // Using a placeholder for type
        //dd(DB::getQueryLog());
        // Initialize an array to hold the parameters
        $params = ['type' => $request->type]; // Initialize with type filter
        
        // Conditionally add 'AND' conditions for each filter if they are not empty
        if (!empty($end_use_filter)) {
            $end_use_values = explode(',', $end_use_filter);
            $placeholders = implode(',', array_fill(0, count($end_use_values), '?'));
            $query .= " AND ep.end_use IN ($placeholders)";
            $params = array_merge($params, $end_use_values);
        }
        
        if (!empty($weave_filter)) {
            $weave_values = explode(',', $weave_filter);
            $placeholders = implode(',', array_fill(0, count($weave_values), '?'));
            $query .= " AND ep.weave_id IN ($placeholders)";
            $params = array_merge($params, $weave_values);
        }
        
        if (!empty($content_filter)) {
            $content_values = explode(',', $content_filter);
            $placeholders = implode(',', array_fill(0, count($content_values), '?'));
            $query .= " AND ep.content_id IN ($placeholders)";
            $params = array_merge($params, $content_values);
        }
        
        if (!empty($gsm_filter)) {
            $gsm_values = explode(',', $gsm_filter);
            $placeholders = implode(',', array_fill(0, count($gsm_values), '?'));
            $query .= " AND ep.gsm_range IN ($placeholders)";
            $params = array_merge($params, $gsm_values);
        }
        
        if (!empty($width_filter)) {
            $width_values = explode(',', $width_filter);
            $placeholders = implode(',', array_fill(0, count($width_values), '?'));
            $query .= " AND ep.width_range IN ($placeholders)";
            $params = array_merge($params, $width_values);
        }
       //DB::enableQueryLog();
        // Perform the database query
        $productList = DB::select($query, $params);

       //dd(DB::getQueryLog());
        // Convert the result to a collection and then chunk
        $productChunks = collect($productList)->chunk(3);
    
    
        $query1 = "SELECT  
                    GROUP_CONCAT(DISTINCT ep.end_use) AS end_use,  
                    GROUP_CONCAT(DISTINCT ep.content_id) AS content_ids,  
                    GROUP_CONCAT(DISTINCT ep.width_range) AS width_ids,    
                    GROUP_CONCAT(DISTINCT ep.gsm_range) AS gsm_ids,  
                    GROUP_CONCAT(DISTINCT ep.weave_id) AS weave_ids,  
                    CONCAT_WS('_', 
                        COALESCE(ep.end_use, ''), 
                        COALESCE(ep.content_id, ''), 
                        COALESCE(ep.width_range, ''), 
                        COALESCE(ep.weave_id, ''), 
                        COALESCE(ep.gsm_range, '')
                    ) AS group_id
                FROM exhibition_product AS ep 
                WHERE ep.type = ?";
        
        $params1 = [$request->type]; 
         
        // Conditionally add 'AND' conditions for each filter if they are not empty
        if (!empty($end_use_filter)) {
            $end_use_values = explode(',', $end_use_filter);
            $placeholders = implode(',', array_fill(0, count($end_use_values), '?'));
            $query1 .= " AND ep.end_use IN ($placeholders)";
            $params1 = array_merge($params1, $end_use_values);
        }
        
        if (!empty($weave_filter)) {
            $weave_values = explode(',', $weave_filter);
            $placeholders = implode(',', array_fill(0, count($weave_values), '?'));
            $query1 .= " AND ep.weave_id IN ($placeholders)";
            $params1 = array_merge($params1, $weave_values);
        }
        
        if (!empty($content_filter)) {
            $content_values = explode(',', $content_filter);
            $placeholders = implode(',', array_fill(0, count($content_values), '?'));
            $query1 .= " AND ep.content_id IN ($placeholders)";
            $params1 = array_merge($params1, $content_values);
        }
        
        if (!empty($gsm_filter)) {
            $gsm_values = explode(',', $gsm_filter);
            $placeholders = implode(',', array_fill(0, count($gsm_values), '?'));
            $query1 .= " AND ep.gsm_range IN ($placeholders)";
            $params1 = array_merge($params1, $gsm_values);
        }
        
        if (!empty($width_filter)) {
            $width_values = explode(',', $width_filter);
            $placeholders = implode(',', array_fill(0, count($width_values), '?'));
            $query1 .= " AND ep.width_range IN ($placeholders)";
            $params1 = array_merge($params1, $width_values);
        }
        
        // Grouping by `group_id`
        $query1 .= " GROUP BY group_id";
        
        // Perform the query
        $productList1 = DB::select($query1, $params1);
        
        $weave_ids = collect($productList1)
            ->pluck('weave_ids') // Extract weave_ids
            ->filter() // Remove null or empty values
            ->map(function($item) { 
                return is_string($item) ? explode(',', $item) : []; 
            }) // Ensure only strings are processed
            ->flatten() // Convert nested arrays into a single array
            ->unique() // Keep only unique values
            ->implode(','); // Convert back to a comma-separated string
         
        $content_ids = collect($productList1)
            ->pluck('content_ids') // Extract content_ids
            ->filter() // Remove null or empty values
            ->map(function($item) { 
                return is_string($item) ? explode(',', $item) : []; 
            }) // Ensure only strings are processed
            ->flatten() // Convert nested arrays into a single array
            ->unique() // Keep only unique values
            ->implode(','); // Convert back to a comma-separated string
         
        $gsm_ids = collect($productList1)
            ->pluck('gsm_ids') // Extract gsm_ids
            ->filter() // Remove null or empty values
            ->map(function($item) { 
                return is_string($item) ? explode(',', $item) : []; 
            }) // Ensure only strings are processed
            ->flatten() // Convert nested arrays into a single array
            ->unique() // Keep only unique values
            ->implode(','); // Convert back to a comma-separated string
         
        $width_ids = collect($productList1)
            ->pluck('width_ids') // Extract width_ids
            ->filter() // Remove null or empty values
            ->map(function($item) { 
                return is_string($item) ? explode(',', $item) : []; 
            }) // Ensure only strings are processed
            ->flatten() // Convert nested arrays into a single array
            ->unique() // Keep only unique values
            ->implode(','); // Convert back to a comma-separated string 
        
        $html = '';
        foreach($productChunks as $index => $chunk)
        {
            foreach($chunk as $row)
            {  
                $html .= '<div class="product-card" weave_ids="'.$weave_ids.'" content_ids="'.$content_ids.'"  gsm_ids="'.$gsm_ids.'"  width_ids="'.$width_ids.'" data-weave_id="'.$row->weave.'" rate="'.$row->rate.'"  data-image="'.$row->attachment.'" data-sort_no ="'.$row->sort_no.'" data-quality ="'.$row->quality.'" data-content ="'.$row->content.'" data-quantity ="'.$row->quantity.'" data-end_use="'.$row->end_use.'" data-rate="'.$row->rate.'" data-content="'.$row->content_id.'" data-width="'.$row->width.'" data-weave="'.$row->weave.'" data-gsm="'.$row->gsm.'">
                        <div class="product-image gallery">'; 
                            if($row->attachment != '')
                            {
                                $html .= '<img src='.$row->attachment.' alt="Product" class="zoomable" onclick="zoomImage(this);" >';
                            }
                            else
                            {
                                $html .= '<img src="https://kenerp.com/uploads/Exhibition/no_image.jpg" alt="Product" class="zoomable" >';
                            }
                        $html .= '</div>
                        <div class="product-details">
                            <h3 class="product-title">'.$row->sort_no.'</h3>
                            <p class="product-specs"><b>End Use : </b>'.$row->end_use.'</p>
                            <p class="product-specs"><b>Quality : </b>'.$row->quality.'</p>';
                            
                            if($request->type == 1)
                            {
                                $html .= '<p class="product-specs"><b>Width : </b>'.$row->width.'" '.$row->OT_OL.'</p>';
                            }
                            $html .= '<p class="product-specs"><b>Weave : </b>'.$row->weave.'</p>
                            <p class="product-specs"><b>GSM : </b>'.$row->gsm.'</p> 
                            <p class="product-material"><b>Content : </b> '.$row->content_id.'</p>
                            <p class="product-specs"><b>Quantity : </b>'.money_format("%!.0n",($row->quantity)).' Mtrs</p>';
                            
                            if($request->type == 1)
                            {
                                $html .= '<p class="product-specs"><b>Price : </b> ₹ '.money_format("%!.2n",($row->rate)).'</p>';
                            }
                            
                            $html .= '<label class="checkbox-label">
                                <input type="checkbox" name="selected_product[]" onchange="checkedProduct(this);" value="0" class="form-control selected_product" />
                                <span>Add this Product to your Email List!</span>
                            </label>
                        </div>
                    </div>';
            }
        }                
        return response()->json(['html'=>$html]); 
    }
    
    public function ExhibitionProductImport(Request $request)
    { 
        $data = file($request->productfile);
        
        // Split the data into chunks of 3000 rows each
        $chunks = array_chunk($data, 3000);
        
        foreach ($chunks as $chunk) {
            // Remove the first row (header) of the chunk
            array_shift($chunk);
    
            // Map the remaining rows
            $chunk = array_map('str_getcsv', $chunk);
            
            $db_data = [];
            
            foreach($chunk as $row) 
            { 
            
                $db_data[] = [
                    'type' => $row[1],
                    'sort_no' => $row[2],
                    'end_use' => $row[3],
                    'quality' => $row[4],
                    'width' => $row[5],
                    'width_range' => $row[6],
                    'OT_OL' => $row[7],
                    'weave' => $row[8],
                    'weave_id' => $row[9],
                    'gsm' => $row[10],
                    'gsm_range' => $row[11],
                    'content' => $row[12],
                    'content_id' => $row[13],
                    'rate' => $row[14],
                    'quantity' => $row[15],
                    'attachment' => $row[16],
                ];
            }

            
            // Uncomment to test the output
            //echo '<pre>'; print_r($db_data); exit;
    
            // Insert the data into the database
            DB::table('exhibition_product')->insert($db_data);
        }
    
        return view('/ProductMaster');
    }
    
    public function DeleteAllExhibitionProducts(Request $request)
    {
        DB::table('exhibition_product')->delete();
     
        return 1;
    }

    
    public function dashboard2nd()
    {
        
        //DB::enableQueryLog();
          
           $Authicateuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
        ->where('form_auth.emp_id','=',Session::get('userId'))
        ->get(['form_master.form_code','form_master.form_label','form_master.form_name', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);
        
        /*$query = DB::getQueryLog();
        $query = end($query);
        dd($query);*/

        return view('dashboard2nd',compact('Authicateuser'));

    }
    
    
    public function mis_dashboard_pbi()
    {
       $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '298')
        ->first();
        
        return view('mis_dashboard_power_bi', compact('chekform'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
      
    }

    public function WorkInProgressStatusList(Request $request)
    {
       $WorkProgressDate = isset($request->WorkProgressDate) ? $request->WorkProgressDate : date("Y-m-d");
       $html = '';
       
       $html .='<html>
                <head>';
                     setlocale(LC_MONETARY, "en_IN");
                	$html .='<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
                	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="https://www.datatables.net/rss.xml">
                	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.jqueryui.min.css">
                	<style type="text/css" class="init">
                    .text-center
                	{
                	    text-align:center;
                	}
                	th
                	{
                        background: #152d9f!important;
                        color: #fff!important;
                	}
                	.text-right
                	{
                	    text-align: end;
                	}
                	</style>
                	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js?v=1"></script>
 
                    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
                    <script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
 
                      
                </head>
                  
                 <body class="wide comments example dt-example-jqueryui">';
                     
                         $no = 1;
                          
                    
                         $JobWorkers=DB::select('SELECT DISTINCT COALESCE(vwom.vendorId, pim.vendorId) AS vendorId, ledger_master.ac_name
                                        FROM buyer_purchse_order_master bpm
                                        LEFT JOIN vendor_work_order_master vwom ON bpm.tr_code = vwom.sales_order_no
                                        LEFT JOIN packing_inhouse_master pim ON bpm.tr_code = pim.sales_order_no
                                        LEFT JOIN ledger_master ON (
                                            ledger_master.ac_code = vwom.vendorId 
                                            OR (vwom.vendorId IS NULL AND ledger_master.ac_code = pim.vendorId)
                                        )
                                        WHERE (
                                            (bpm.order_received_date <= "'.$WorkProgressDate.'" AND bpm.job_status_id = 1 AND bpm.og_id != 4)
                                            OR
                                            (
                                                bpm.order_close_date = "'.$WorkProgressDate.'"
                                                AND bpm.og_id != 4
                                                AND bpm.order_type IN (1, 3)
                                                AND bpm.delflag = 0
                                                AND bpm.job_status_id IN (1, 2, 4, 5)
                                            )
                                        )
                                        AND (vwom.sales_order_no IS NOT NULL OR pim.sales_order_no IS NOT NULL)');
                    
                        $totalWorkOrderQty = 0;
                        $totalOrderQty = 0;
                        $totalWIPQty = 0;
                      
                   $html .='<table id="example" class="display" cellspacing="0" width="100%">
                		<thead>
                			<tr>
                                <th>Sr. No</th>
                                <th>Vendor Name</th>
                                <th>Work Order Qty</th>
                                <th>Garment  Inward </th>
                                <th>WIP</th>
                             </tr>
                             <tr>
                                <th></th>
                                <th></th>
                                <th>PCS</th>
                                <th>PCS</th>
                                <th>PCS</th>
                             </tr>
                		</thead>
                		<tbody>';
                            foreach($JobWorkers as $row)
                            {
                                $combinedData = DB::SELECT("SELECT  
                                     sum((SELECT IFNULL(SUM(final_bom_qty),0) FROM vendor_work_order_master WHERE vendor_work_order_master.sales_order_no = bpm.tr_code AND vw_date <= '".$WorkProgressDate."' AND vendorId=".$row->vendorId.")) AS work_order_qty,
                                     sum((SELECT IFNULL(SUM(total_qty),0) FROM packing_inhouse_master WHERE packing_inhouse_master.sales_order_no = bpm.tr_code AND pki_date <= '".$WorkProgressDate."' AND vendorId=".$row->vendorId.")) AS total_qty,
                                     sum((SELECT IFNULL(SUM(size_qty_total),0) FROM qcstitching_inhouse_reject_detail WHERE qcstitching_inhouse_reject_detail.sales_order_no = bpm.tr_code AND qcsti_date <=  '".$WorkProgressDate."' AND vendorId=".$row->vendorId.")) AS total_reject_qty,
                                     sum((SELECT IFNULL(SUM(total_qty),0) FROM WIP_Adjustable_Qty WHERE WIP_Adjustable_Qty.sales_order_no = bpm.tr_code AND vendorId=".$row->vendorId.")) AS total_adjustable_qty
                                    
                                    FROM 
                                        buyer_purchse_order_master as bpm 
                                    WHERE 
                                            (
                                                (bpm.order_received_date <= '".$WorkProgressDate."' AND bpm.job_status_id = 1 AND bpm.og_id != 4)
                                                OR
                                                (
                                                    bpm.order_close_date = '".$WorkProgressDate."'
                                                    AND bpm.og_id != 4
                                                    AND bpm.order_type IN (1, 3)
                                                    AND bpm.delflag = 0
                                                    AND bpm.job_status_id IN (1, 2, 4, 5)
                                                )
                                    )");
                                $pack_order_qty = isset($combinedData[0]->total_qty) ? $combinedData[0]->total_qty : 0; 
                                $work_order_qty = isset($combinedData[0]->work_order_qty) ? $combinedData[0]->work_order_qty : 0; 
                                $total_reject_qty = isset($combinedData[0]->total_reject_qty) ? $combinedData[0]->total_reject_qty : 0; 
                                $total_adjustable_qty = isset($combinedData[0]->total_adjustable_qty) ? $combinedData[0]->total_adjustable_qty : 0; 
                                 
                               if(($work_order_qty - $pack_order_qty - $total_adjustable_qty - $total_reject_qty) > 0)
                               {
                    		     $html .='<tr>
                                    <td class="text-center">'.$no++.'</td>
                                    <td>'.$row->ac_name.'</td>
                                    <td class="text-right">'.money_format("%!.0n",($work_order_qty - $total_adjustable_qty)).'</td>
                                    <td class="text-right">'.money_format("%!.0n",($pack_order_qty)).'</td> 
                                    <td class="text-right"><a href="'.url('WIPDetailReport', [0]).'" target="_blank">'.money_format("%!.0n",($work_order_qty - $pack_order_qty - $total_adjustable_qty - $total_reject_qty)).'</a></td>
                                </tr>';
                               }
                                $totalWorkOrderQty += $work_order_qty - $total_adjustable_qty;
                                $totalOrderQty += $pack_order_qty;
                                $totalWIPQty += ($work_order_qty - $pack_order_qty - $total_adjustable_qty - $total_reject_qty);
                                
                                $pack_order_qty = 0;
                                $work_order_qty = 0;
                             
                            }
                	 $html .='</tbody>
                		
                		<tfoot>
                			<tr>
                				<th></th>
                				<th>Total :</th>
                				<th class="text-right">'.money_format("%!.0n",($totalWorkOrderQty)).'</th>
                				<th class="text-right">'.money_format("%!.0n",($totalOrderQty)).'</th>
                				<th class="text-right">'.money_format("%!.0n",($totalWIPQty)).'</th> 
                			</tr>
                		</tfoot>
                	</table>
                	
                	<script type="text/javascript" class="init">
                	  $(document).ready(function() 
                	  {
                             var dataTable = $("#example").DataTable({
                                "order": [[4, "desc"]],  
                                "iDisplayStart ": 14,  
                                "iDisplayLength": 14  
                            });
                            
                             dataTable.on("order.dt search.dt", function () {
                                dataTable.column(0, { search: "applied", order: "applied" }).nodes().each(function (cell, i) {
                                    cell.innerHTML = i + 1;
                                });
                            }).draw();
                       });
                                    
                	</script>
                </body>
                </html>';
          return response()->json(['html'=>$html,'WorkProgressDate'=>$WorkProgressDate]); 
    }
     
    public function OrderStatusListDashboard()
    {
       return view('OrderStatusListDashboard');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    function logout(){
        if(session()->has('ADMIN_LOGIN')){
            session()->pull('ADMIN_LOGIN');
            return redirect('login');
        }
    }
    
    public function SpeededDashboard()
    {
        $Authicateuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
        ->where('form_auth.emp_id','=',Session::get('userId'))
        ->get(['form_master.form_code','form_master.form_label','form_master.form_name', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);
  
        return view('SpeededDashboard');
    }
    
    public function MDDashboard()
    {
        return view('MDDashboard');
    }
    
    public function MDDashboard1(Request $request)
    { 
        $chekform = DB::table('form_auth')
         ->where('emp_id', Session::get('userId'))
         ->where('form_id', '239')
         ->first();     
        
        $fdate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');   
        $tdate = isset($request->toDate) ? $request->toDate : date('Y-m-d');      
        $slide_no = isset($request->slide_no) ? $request->slide_no : 0;   
        
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
         
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        

        $fDate = $Financial_Year[0]->fdate; 
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
            $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e'); 
        
        return view('MDDashboard1',compact('chekform','fdate','tdate','slide_no','period','colorArr','Financial_Year','fin_year_id','Financial_Year1'));
    } 
    
    public function AllDataMDDashboard()
    { 
        $html = '';
        // $style = ''; $head='';
        $table_head_data = DB::select("select distinct table_head from temp_order_sales_dashboard  order by table_head ASC");
        foreach($table_head_data as $row)
        {  
             
            
            if($row->table_head == 1)
            {
                $head = '1. Order Booking';
                $style = '';
            }
            else if($row->table_head == 2)
            {
                $head = '2. Sales';      
                $style = 'style="background: goldenrod;"';
            }
            else if($row->table_head == 3)
            {
                $head = '3. OCR';        
                $style = 'style="background: #da2076c7;"';        
            }
            else if($row->table_head == 4)
            {
                $head = '4. Fabric';     
                $style = 'style="background: #70da20c7;"';           
            }
            else if($row->table_head == 5)
            {
                $head = '5. Trims';   
                $style = 'style="background: #ed6e13c7;"';             
            }
            else if($row->table_head == 6)
            {
                $head = '6. Operations'; 
                $style = 'style="background: #523ccfc7;"';               
            }
            else if($row->table_head == 7)
            {
                $head = '7. Open Order Status'; 
                $style = 'style="background: #cfa23cc7;"';               
            }
            else if($row->table_head == 8)
            {
                $head = '8. HR';     
                $style = 'style="background: #cf3c65c7;"';           
            }
            else if($row->table_head == 9)
            {
                $head = '8. Inventory Status'; 
                $style = 'style="background: #3c92cfc7;"';  
                
                
                
            //     $html ='<table width="100%" border="1" id="tbl1">
            //  <caption '.$style.'> '.$head.'</caption>
            //   <thead>
            //       <tr class="row1">
            //         <th nowrap>Key Indicators</th>
            //         <th class="col2">UOM</th>
            //         <th>Today</th>
            //         <th>Last Month End</th>
            //         <th>Last Year End</th>
            //       </tr>
            //   </thead>';
               
            }
            
            
             
             $html .='<table width="100%" border="1" id="tbl1">
             <caption '.$style.'> '.$head.'</caption>
              <thead>
                  <tr class="row1">
                    <th nowrap>Key Indicators</th>
                    <th class="col2">UOM</th>
                    <th>Today</th>
                    <th>Month To Date</th>
                    <th>Year To Date</th>
                  </tr>
              </thead>';
             
              $temp_table_data = DB::select("select * from temp_order_sales_dashboard WHERE table_head=".$row->table_head);
              $html .='<tbody>';
              $temp = '';
              foreach($temp_table_data as $row1)
              {     
                    if($row1->company_name != $temp)
                    {
                        $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">'.$row1->company_name.'</td></tr>';
                    }
                    $html .='<tr>
                        <td>'.$row1->key_Indicators.'</td>
                        <td class="col2"> '.$row1->uom.' </td>';
                       
                       
                        // <td class="text-right">'.money_format("%!i",round($row1->today,2)).'</td>
                        // <td class="text-right">'.money_format("%!i",round($row1->month_to_date,2)).'</td>
                        // <td class="text-right">'.money_format("%!i",round($row1->year_to_date,2)).'</td>
                    
                  if($row1->uom=='Pcs')   
                  {   $html .='   
                        <td class="text-right">'.money_format("%!.0n",round($row1->today)).'</td>
                        <td class="text-right">'.money_format("%!.0n",round($row1->month_to_date)).'</td>
                        <td class="text-right">'.money_format("%!.0n",round($row1->year_to_date)).'</td>
                     </tr>';
                  }
                  else
                  {
                      $html .='   
                        <td class="text-right">'.money_format("%!i",round($row1->today,2)).'</td>
                        <td class="text-right">'.money_format("%!i",round($row1->month_to_date,2)).'</td>
                        <td class="text-right">'.money_format("%!i",round($row1->year_to_date,2)).'</td>
                     </tr>';
                      
                  }
                    
                      
                      
                      $temp = $row1->company_name;
              }
             $html .='</tbody></table>';
        }
          return response()->json(['html' => $html]);
    }
    
    public function AllDataMDDashboard1()
    { 
        $html = '';
        $today_stock_value = 0;
        $month_stock_value = 0;
        $year_stock_value = 0;
        
        $table_head_data = DB::select("select distinct table_head from temp_order_sales_dashboard  order by table_head ASC");
        foreach($table_head_data as $key=>$row)
        {  
            if($row->table_head == 1)
            {
                $head = 'Order Booking';
                $head1 = 'Order Booking';
                $style = 'style="color: #3d0cd5;"';
            }
            else if($row->table_head == 2)
            {
                $head = 'Sales';    
                $head1 = 'Sales';       
                $style = 'style="color: goldenrod;"';
            }
            else if($row->table_head == 3)
            {
                $head = 'OCR'; 
                $head1 = 'OCR';        
                $style = 'style="color: #da2076c7;"';        
            }
            else if($row->table_head == 4)
            {
                $head = 'Fabric';  
                $head1 = 'Fabric';     
                $style = 'style="color: #70da20c7;"';           
            }
            else if($row->table_head == 5)
            {
                $head = 'Trims'; 
                $head1 = 'Trims';   
                $style = 'style="color: #ed6e13c7;"';             
            }
            else if($row->table_head == 6)
            {
                $head = 'Stitching'; 
                $head1 = 'Cutting-Inhouse'; 
                $style = 'style="color: #523ccfc7;"';               
            }
            else if($row->table_head == 8)
            {
                $head = 'Open Order Status'; 
                $head1 = 'Open Order Status'; 
                $style = 'style="color: #cfa23cc7;"';               
            }
            else if($row->table_head == 10)
            {
                $head = 'Inventory Status'; 
                $head1 = 'Inventory Status';
                $style = 'style="color: #3c92cfc7;"';               
            }
            else if($row->table_head == 7)
            {
                $head = 'Packing'; 
                $head1 = 'Packing';
                $style = 'style="color: #3c92cfc7;"';               
            }
            
            $html .='';
                 
            $temp_table_data = DB::select("select *  from temp_order_sales_dashboard WHERE table_head=".$row->table_head." GROUP BY key_Indicators");
            $totalCount = count($temp_table_data)/2;
           
            $tc = explode(".", $totalCount + 1);
            $temp = '';
            $cnt = 1;
            
            if($head1=='Inventory Status')
             { 
                $html .='<table width="100%" id="tbl1">
                 <tr  class="head" style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#000080; " ><td >'.$head1.'</td><td></td><td></td><td class="text-right"><a href="javascript:void(0);" class="minus expand" onclick="collapseDiv(this);" data-toggle="collapse"  >-</a></td></tr>';
             }
             else
             {
                 $html .='<table width="100%" id="tbl1">
                 <tr  class="head" style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#000080;"><td>'.$head1.'</td><td></td><td></td><td class="text-right"><a href="javascript:void(0);" class="minus expand" onclick="collapseDiv(this);" data-toggle="collapse"  >-</a></td></tr>';
             }
            foreach($temp_table_data as $row1)
            { 
                // if($row1->company_name != $temp)
                // {
                //     $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">'.$row1->company_name.'</td></tr>';
                // }
                if($row1->company_name != $temp)
                {
                    $last ='style="border-bottom: 4px solid;"';
                }
                else
                {
                    $last = '';
                }
                if(count($temp_table_data) == $cnt )
                {
                    $border ='style=" "';
                }
                else
                {
                    $border ='';
                }
                
                if($row1->company_name != $temp)
                {
                    $html .='<tr class="head" style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#000080;"><td colspan="5"  >'.$head.'-'.$row1->company_name.'</td></tr>';
                }
                   
                $tbHeader = preg_replace('/\s+/', '', $head); 
            
                $html .='<tr '.$border.' class="tblHead_'.$tbHeader.'">';
                
                if($row1->uom=='Pcs')   
                {
                   $pcs = '(Pcs)';
                }
                else
                {
                    $pcs = '';
                }
                
                if($row1->uom=='Rs' )   
                {
                   $rs = '(Rs)';
                }
                else
                {
                    $rs = '';
                }
                
                if($row1->uom=='%')   
                {
                   $per = '(%)';
                }
                else
                {
                    $per = '';
                } 
                
                if($row1->uom=='Mtr')   
                {
                   $meter = '(Mtr)';
                }
                else
                {
                    $meter = '';
                }
                
                if($row1->key_Indicators === "WIP -  Quantity")   
                {
                   $pcs = ' - FOB & Job work (Pcs)';
                }
                
                if($row1->key_Indicators === "WIP -  Value")   
                {
                   $pcs = ' - FOB';
                }
                 
                      
                $html .=' 
                         <td style="font-weight:bold;">'.$row1->key_Indicators.''.$pcs.''.$rs.''.$per.''.$meter.'</td>';
                
                  if($row1->today == 0 || $row1->today == 0.00)
                  {
                        $tunit = '';
                  }
                  else
                  {
                        $tunit = 'L';
                  }
                     
                  if($row1->month_to_date == 0 || $row1->month_to_date == 0.00)
                  {
                     $munit = '';
                  }
                  else
                  {
                        $munit = 'L';
                  }
                    
                  if($row1->year_to_date == 0 || $row1->year_to_date == 0.00)
                  {
                        $yunit = '';
                  }
                  else
                  {
                        $yunit = 'L';
                  }
                      
                  if($row->table_head == 3)
                  {
                      $tunit = '';
                      $munit = '';
                      $yunit = '';
                  }
                  if($row1->uom=='Pcs')   
                  {  
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4  || $row->table_head == 5 || $row->table_head == 6)
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                      if($row->table_head == 1)
                      {
                          $redirect = '/TotalSalesOrderDetailMDDashboard';
                      }
                      else if($row->table_head == 2)
                      {
                          $redirect = '/SaleFilterReportMD';
                      }
                      else if($row->table_head == 3)
                      {
                          $redirect = '/TotalSalesOrderPendingForOCR';
                      }
                      else if($row->table_head == 4)
                      {
                          if($row1->key_Indicators === "Issued Quantity-Meter" || $row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/FabricOutwardDataMD';
                          }
                          else
                          {
                            $redirect = '/FabricGRNDataMD';
                          }
                      }
                      else if($row->table_head == 5)
                      {
                          if($row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/TrimsOutwardDataMD';
                          }
                          else
                          {
                               $redirect = '/TrimsGRNDataMD';
                          }
                      }
                      else if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailMDDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT - 1")
                      {
                          $redirect = '/StitchingGRNDashboardMD/56';
                      }
                      if($row1->company_name === "ANSH APPAREL")
                      {
                          $redirect = '/StitchingGRNDashboardMD/69';
                      }
                      if($row1->company_name === "NANDINI FASHION")
                      {
                          $redirect = '/StitchingGRNDashboardMD/110';
                      }
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT  - 2")
                      {
                          $redirect = '/StitchingGRNDashboardMD/115';
                      }
                      
                      if($row1->company_name === "Outsource")
                      {
                          $redirect = '/PackingGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value")
                          {
                              $redirect = '/FabricStockDataMD/1';
                          }
                          if($row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                          {
                               $redirect = '/FabricStockDataMD/2';
                          }
                      } 
                      
                      if($row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "Trims - Moving Value")
                          {
                            $redirect = '/TrimsStockDataMD/1';
                          }
                          
                          if($row1->key_Indicators === "Trims - Non - Moving Value")
                          {
                               $redirect = '/TrimsStockDataMD/2';
                          }
                      } 
                      
                      if($row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                          $redirect = '/GetVendorWorkOrderStock';
                      } 
                      
                      if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value")
                          {
                              $redirect = '/FGStockReportMD/1';
                          }
                          
                          if($row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                          {
                              $redirect = '/FGStockReportMD/2';
                          }
                      }
                      
                      if($row1->key_Indicators === "Cutting Quantity Pcs")
                      {
                          $redirect = '/CutPanelGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Cut Panel Issue Quantity Pcs")
                      {
                          $redirect = '/CutPanelIssueReportMD';
                      }
                      
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value" || $row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value" || $row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value" || $row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                        $html .='   
                            <td class="text-right">'.$tobunit.'</td>
                            <td class="text-right">'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</td>
                            <td class="text-right">'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</td>
                         </tr>';
                      }
                      else
                      {
                          $html .='   
                            <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.$tobunit.'</a></td>
                            <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</a></td>
                            <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</a></td>
                         </tr>';
                      }
                      
                   
                  }
                  else if($row1->uom!='%')
                  {
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4 || $row->table_head == 5 || $row->table_head == 6 )
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                      if($row->table_head == 1)
                      {
                          $redirect = '/TotalSalesOrderDetailMDDashboard';
                      }
                      else if($row->table_head == 2)
                      {
                          $redirect = '/SaleFilterReportMD';
                      }
                      else if($row->table_head == 3)
                      {
                          $redirect = '/TotalSalesOrderPendingForOCR';
                      }
                      else if($row->table_head == 4)
                      {
                        
                          if($row1->key_Indicators === "Issued Quantity-Meter" || $row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/FabricOutwardDataMD';
                          }
                          else
                          {
                            $redirect = '/FabricGRNDataMD';
                          }
                      }
                      else if($row->table_head == 5)
                      {
                          if($row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/TrimsOutwardDataMD';
                          }
                          else
                          {
                               $redirect = '/TrimsGRNDataMD';
                          }
                      }
                      else if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailMDDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT - 1")
                      {
                          $redirect = '/StitchingGRNDashboardMD/56';
                      }
                      if($row1->company_name === "ANSH APPAREL")
                      {
                          $redirect = '/StitchingGRNDashboardMD/69';
                      }
                      if($row1->company_name === "NANDINI FASHION")
                      {
                          $redirect = '/StitchingGRNDashboardMD/110';
                      }
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT  - 2")
                      {
                          $redirect = '/StitchingGRNDashboardMD/115';
                      }
                      
                      if($row1->company_name === "Outsource" || $row->table_head == 7)
                      {
                          $redirect = '/PackingGRNReportMD';
                      }
                                            
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value")
                          {
                            //   $redirect = '/FabricStockDataMD/1';
                              $redirect = 'javascript:void(0);';
                          }
                          if($row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                          {
                            //   $redirect = '/FabricStockDataMD/2';
                              $redirect = 'javascript:void(0);';
                          }
                      } 
                      
                      if($row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "Trims - Moving Value")
                          {
                            // $redirect = '/TrimsStockDataMD/1';
                              $redirect = 'javascript:void(0);';
                          }
                          
                          if($row1->key_Indicators === "Trims - Non - Moving Value")
                          {
                            //   $redirect = '/TrimsStockDataMD/2';
                              $redirect = 'javascript:void(0);';
                          }
                      } 
                      
                      if($row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                        //   $redirect = '/GetVendorWorkOrderStock';
                              $redirect = 'javascript:void(0);';
                      } 
                      
                      if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value")
                          {
                            //   $redirect = '/FGStockReportMD/1';
                              $redirect = 'javascript:void(0);';
                          }
                          
                          if($row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                          {
                            //   $redirect = '/FGStockReportMD/2';
                              $redirect = 'javascript:void(0);';
                          }
                      }
                      
                      if($row1->key_Indicators === "Cutting Quantity Pcs")
                      {
                          $redirect = '/CutPanelGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Cut Panel Issue Quantity Pcs")
                      {
                          $redirect = '/CutPanelIssueReportMD';
                      }
                      
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value" || $row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value" || $row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value" || $row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                        $html .='   
                            <td class="text-right">'.$tobunit.'</td>
                            <td class="text-right">'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</td>
                            <td class="text-right">'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</td>
                         </tr>';
                      }
                      else
                      {
                          $html .='   
                            <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.$tobunit.'</a></td>
                            <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</a></td>
                            <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</a></td>
                         </tr>';
                      }
                      
                     
                  }
                  else if($row1->uom='%')
                  { 
                      
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4 || $row->table_head == 5 || $row->table_head == 6 )
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                      if($row->table_head == 1)
                      {
                          $redirect = '/TotalSalesOrderDetailMDDashboard';
                      }
                      else if($row->table_head == 2)
                      {
                          $redirect = '/SaleFilterReportMD';
                      }
                      else if($row->table_head == 3)
                      {
                          $redirect = '/TotalSalesOrderPendingForOCR';
                      }
                      else if($row->table_head == 4)
                      {
                          if($row1->key_Indicators === "Issued Quantity-Meter" || $row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/FabricOutwardDataMD';
                          }
                          else
                          {
                            $redirect = '/FabricGRNDataMD';
                          }
                      }
                      else if($row->table_head == 5)
                      {
                          if($row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/TrimsOutwardDataMD';
                          }
                          else
                          {
                               $redirect = '/TrimsGRNDataMD';
                          }
                      }
                      else if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT - 1")
                      {
                          $redirect = '/StitchingGRNDashboardMD/56';
                      }
                      if($row1->company_name === "ANSH APPAREL")
                      {
                          $redirect = '/StitchingGRNDashboardMD/69';
                      }
                      if($row1->company_name === "NANDINI FASHION")
                      {
                          $redirect = '/StitchingGRNDashboardMD/110';
                      }
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT  - 2")
                      {
                          $redirect = '/StitchingGRNDashboardMD/115';
                      }
                      
                      if($row1->company_name === "Outsource")
                      {
                          $redirect = '/PackingGRNReportMD';
                      }
                                           
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value")
                          {
                              //$redirect = '/FabricStockDataMD/1';
                              $redirect = 'javascript:void(0);';
                          }
                          if($row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                          {
                               //$redirect = '/FabricStockDataMD/2';
                              $redirect = 'javascript:void(0);';
                          }
                          
                      } 
                      
                      if($row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "Trims - Moving Value")
                          {
                              //$redirect = '/TrimsStockDataMD/1';
                              $redirect = 'javascript:void(0);';
                          }
                          
                          if($row1->key_Indicators === "Trims - Non - Moving Value")
                          {
                              // $redirect = '/TrimsStockDataMD/2';
                               $redirect = 'javascript:void(0);';
                          }
                      } 
                      
                      if($row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                          //$redirect = '/GetVendorWorkOrderStock';
                              $redirect = 'javascript:void(0);';
                      } 
                      
                      if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                      {
                          if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value")
                          {
                              //$redirect = '/FGStockReportMD/1';
                              $redirect = 'javascript:void(0);';
                          }
                          
                          if($row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                          {
                              //$redirect = '/FGStockReportMD/2';
                              $redirect = 'javascript:void(0);';
                          }
                      }
                      
                      if($row1->key_Indicators === "Cutting Quantity Pcs")
                      {
                          $redirect = '/CutPanelGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Cut Panel Issue Quantity Pcs")
                      {
                          $redirect = '/CutPanelIssueReportMD';
                      }
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value" || $row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value" || $row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value" || $row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                           $html .='   
                            <td class="text-right">'.money_format('%!i',round($row1->today,2)).' '.$tunit.'</td>
                            <td class="text-right">'.money_format("%!i",round($row1->month_to_date,2)).'</td>
                            <td class="text-right">'.money_format("%!i",round($row1->year_to_date,2)).'</td>
                         </tr>';
                      }
                      else
                      {
                          $html .='   
                            <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.money_format('%!i',round($row1->today,2)).' '.$tunit.'</a></td>
                            <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).'</a></td>
                            <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).'</a></td>
                         </tr>';
                      }
                  }
                  else 
                  {
                        
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4 ||    $row->table_head == 5 || $row->table_head == 6 )
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                       
                      if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailMDDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                        
                      $html .='   
                        <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.$tobunit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</a></td>
                     </tr>';
                      
                  }
                  
                  if($row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Value" || $row1->key_Indicators === "Trims - Moving Value" 
                      || $row1->key_Indicators === "Trims - Non - Moving Value" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Value" 
                      || $row1->key_Indicators === "WIP -  Value")
                  {
                      $today_stock_value = $today_stock_value + $row1->today;
                      $month_stock_value = $month_stock_value + $row1->month_to_date;
                      $year_stock_value = $year_stock_value + $row1->year_to_date;
                  }
                  $temp = $row1->company_name;
                  $cnt++;
            } 
          
        }
         $html .='<tr class="head" style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#f90808;"> 
                     <td><strong>Stock Value</strong></td>
                     <td class="text-right">'.money_format("%!i",round($today_stock_value,2)).' L</td>
                     <td class="text-right">'.money_format("%!i",round($month_stock_value,2)).' L</td>
                     <td class="text-right">'.money_format("%!i",round($year_stock_value,2)).' L</td>
                 </tr>';  
        
        
          return response()->json(['html' => $html]);
    }
    public function refreshData()
    {
        //DB::enableQueryLog();
       
        //dd(DB::getQueryLog());
       
         date_default_timezone_set("Asia/Calcutta"); 
         $time = date("H:i", strtotime("+60 seconds"));
         
         DB::table('syncronization_time_mgmt')->update(['sync_table'=>0]);
         DB::table('syncronization_time_mgmt')->where('stmt_type','=',4)->update(['start_time' => $time, 'status' => 0,'sync_table'=>1]);
        DB::table('temp_order_sales_dashboard')->where('table_head', 1)->delete();
        $this->orderBookingDashboard();
        DB::table('temp_order_sales_dashboard')->where('table_head', 2)->delete();
        $this->salesMDDashboard();
        DB::table('temp_order_sales_dashboard')->where('table_head', 3)->delete();
        $this->ocrMDDashboard();
        DB::table('temp_order_sales_dashboard')->where('table_head', 4)->delete();
        $this->fabricMDDashboard();
        DB::table('temp_order_sales_dashboard')->where('table_head', 5)->delete();
        $this->trimsMDDashboard(); 
        DB::table('temp_order_sales_dashboard')->where('table_head', 6)->delete();
        DB::table('temp_order_sales_dashboard')->where('table_head', 7)->delete();
        $this->operationMDDashboard();
        DB::table('temp_order_sales_dashboard')->where('table_head', 8)->delete();
        $this->openOrderMDDashboard();
        return 1; 
        
    }
    public function CheckTimeDiff()
    {
        DB::table('temp_order_sales_dashboard')->select('created_at')->where('table_head', 1)->first();
         
        $time1 = "2024-01-10 12:30:00";
        $time2 = "2024-01-10 15:45:30";
         
        $datetime1 = new DateTime($time1);
        $datetime2 = new DateTime($time2);
         
        $interval = $datetime1->diff($datetime2);
         
        return $interval->format('%H');
    }
    public function orderBookingDashboard()
    {
         
        $Buyer_Purchase_Order_List = DB::select("select buyer_purchse_order_master.tr_code, buyer_purchse_order_master.order_rate, buyer_purchse_order_master.sam as sam
                from buyer_purchse_order_master WHERE job_status_id!=3 AND buyer_purchse_order_master.og_id != 4");
       
        
        $html = "";
        $order_qty=0; $order_value=0; $order_min=0;
        $tOrder_qty=0; $tOrder_value=0; $tOrder_min=0;
        $yOrder_qty=0; $yOrder_value=0; $yOrder_min=0;
        
         $yearMinData = DB::select("select sum((buyer_purchase_order_detail.size_qty_total * buyer_purchse_order_master.sam)) as total_min
            from buyer_purchase_order_detail  
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code
            where  job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and buyer_purchse_order_master.order_received_date 
            between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)");
            
        $monthMinData = DB::select("select sum((buyer_purchase_order_detail.size_qty_total * buyer_purchse_order_master.sam)) as total_min
            from buyer_purchase_order_detail  
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code
            where  MONTH(buyer_purchse_order_master.order_received_date)=MONTH(CURRENT_DATE()) 
            and YEAR(buyer_purchse_order_master.order_received_date)=YEAR(CURRENT_DATE()) AND buyer_purchse_order_master.og_id != 4");  
            
        $todayMinData = DB::select("select sum((buyer_purchase_order_detail.size_qty_total * buyer_purchse_order_master.sam)) as total_min
            from buyer_purchase_order_detail  
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code
            where buyer_purchse_order_master.order_received_date =DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND buyer_purchse_order_master.og_id != 4");          
         
         
        foreach($Buyer_Purchase_Order_List as $row)
        {
            
              $monthData = DB::select("select ifnull(sum(total_qty),0) as order_qty from buyer_purchse_order_master  
                where MONTH(buyer_purchse_order_master.order_received_date)=MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_received_date)=YEAR(CURRENT_DATE()) 
                AND job_status_id!=3 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.tr_code='".$row->tr_code."'");
                 
              $todayData = DB::select("select ifnull(sum(total_qty),0) as order_qty,order_rate from buyer_purchse_order_master 
                where job_status_id!=3 AND buyer_purchse_order_master.order_received_date =DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND buyer_purchse_order_master.og_id != 4");
            
            //DB::enableQueryLog();
              $yearData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(order_value),0) as total_order_value from buyer_purchse_order_master 
                  where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 
                  and order_received_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
                  and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)");
              // dd(DB::getQueryLog());
        
              
              $tOrder_qty = $todayData[0]->order_qty;
              $tOrder_value =  ($todayData[0]->order_qty* $todayData[0]->order_rate);
              
              $order_qty = $order_qty + ($monthData[0]->order_qty/100000);
              $order_value = $order_value + ($monthData[0]->order_qty* $row->order_rate)/100000;
              
              $yOrder_qty =  ($yearData[0]->order_qty/100000);
              $yOrder_value =   ($yearData[0]->total_order_value)/100000;
              
              
        }
              $tOrder_min = $todayMinData[0]->total_min;
              $order_min =  $monthMinData[0]->total_min/100000;
              $yOrder_min = $yearMinData[0]->total_min/100000;
              
        //echo $tOrder_qty;exit;
            $html .='<tr>
                        <td> Quantity </td>
                        <td>'.money_format('%!.0n', round($tOrder_qty)).'</td>
                        <td>'.money_format('%!.0n', round($order_qty,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_qty,2)).'</td>
                      </tr>
                      <tr>
                        <td> Value </td>
                        <td>'.money_format('%!.0n', round($tOrder_value)).'</td>
                        <td>'.money_format('%!.0n', round($order_value,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_value,2)).'</td>
                      </tr>
                      <tr>
                        <td> Minutes </td>
                        <td>'.money_format('%!.0n', round($tOrder_min)).'</td>
                        <td>'.money_format('%!.0n', round($order_min,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_min,2)).'</td>
                      </tr>';
        
            $orderBookingArr = array(
                array('Quantity','Pcs',round($tOrder_qty),round($order_qty,2),round($yOrder_qty,2),1,""),
                array('Value','Rs',round($tOrder_value),round($order_value,2),round($yOrder_value,2),1,""),
                array('Minutes','',round($tOrder_min),round($order_min,2),round($yOrder_min,2),1,"")
            );      
            $this->tempInsertData($orderBookingArr);
        
        
        return response()->json(['html' => $html]);
    }
    
    public function salesMDDashboard()
    {
        
        $Sales_List = DB::select("select sale_transaction_detail.sales_order_no, sale_transaction_detail.order_rate, buyer_purchse_order_master.sam as sam
                from  sale_transaction_master INNER JOIN sale_transaction_detail ON sale_transaction_detail.sale_code = sale_transaction_master.sale_code
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no");
       
        
        $html = "";
        $order_qty=0; $order_value=0; $order_min=0;
        $tOrder_qty=0; $tOrder_value=0; $tOrder_min=0;
        $yOrder_qty=0; $yOrder_value=0; $yOrder_min=0;
        
        
        $yearMinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
            from sale_transaction_detail  
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
            where  sale_transaction_master.sale_date between (select fdate from financial_year_master 
            where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)");
            
        $monthMinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
            from sale_transaction_detail  
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
            where  MONTH(sale_transaction_detail.sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_transaction_detail.sale_date)=YEAR(CURRENT_DATE())");  
            
        $todayMinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
            from sale_transaction_detail  
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
            where sale_transaction_detail.sale_date =DATE_SUB(CURDATE(), INTERVAL 1 DAY)");          
         
        foreach($Sales_List as $row)
        {
             
              
              //$monthData = DB::select("select ifnull(sum(total_qty),0) as order_qty from sale_transaction_master 
               // where MONTH(sale_transaction_master.sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_transaction_master.sale_date)=YEAR(CURRENT_DATE())  ");
              
              $monthData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(Gross_amount),0) as total_sale_value
                from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
        
             
              $todayData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(Gross_amount),0) as total_sale_value from sale_transaction_master 
                where sale_transaction_master.sale_date =DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
                
            //   $yearData = DB::select("select ifnull(sum(total_qty),0) as order_qty from sale_transaction_master 
            //     where YEAR(sale_transaction_master.sale_date)=YEAR(CURRENT_DATE())  ");
              $yearData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(Gross_amount),0) as total_sale_value 
                from sale_transaction_master where sale_date between (select fdate from financial_year_master 
                where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)");
        
           
                //DB::enableQueryLog();
              //dd(DB::getQueryLog());
              
              $tOrder_qty =  ($todayData[0]->order_qty);
              $tOrder_value = $todayData[0]->total_sale_value;
              
              
              $order_qty = $monthData[0]->order_qty/100000;
              $order_value = $monthData[0]->total_sale_value/100000;
              
              $yOrder_qty = $yearData[0]->order_qty/100000;
              $yOrder_value = $yearData[0]->total_sale_value/100000;
              
              //echo $yOrder_min;exit;
             
        }
    
              $tOrder_min = $todayMinData[0]->total_min;
              $order_min = $monthMinData[0]->total_min/100000;
              $yOrder_min = $yearMinData[0]->total_min/100000;

            $html .='<tr>
                        <td> Quantity </td>
                        <td>'.money_format('%!.0n', round($tOrder_qty)).'</td>
                        <td>'.money_format('%!.0n', round($order_qty,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_qty,2)).'</td>
                      </tr>
                      <tr>
                        <td> Value </td>
                        <td>'.money_format('%!.0n', round($tOrder_value)).'</td>
                        <td>'.money_format('%!.0n', round($order_value,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_value,2)).'</td>
                      </tr>
                      <tr>
                        <td> Minutes </td>
                        <td>'.money_format('%!.0n', round($tOrder_min)).'</td>
                        <td>'.money_format('%!.0n', round($order_min,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_min,2)).'</td>
                      </tr>';
        
            $salesArr = array(
                array('Quantity','Pcs',round($tOrder_qty),round($order_qty,2),round($yOrder_qty,2),2,""),
                array('Value','Rs',round($tOrder_value),round($order_value,2),round($yOrder_value,2),2,""),
                array('Minutes','',round($tOrder_min),round($order_min,2),round($yOrder_min,2),2,"")
            );  
           // DB::enableQueryLog();
            $this->tempInsertData($salesArr);
            //dd(D::getQueryLog());
        return response()->json(['html' => $html]);
    }
    
    public function ocrMDDashboard()
    {
        
          $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at
         FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
       
        
        $html = "";
        $cut_to_ship=0; $order_to_ship=0; $order_min=0;
        $tcut_to_ship=0; $torder_to_ship=0; $torder_min=0;
        $ycut_to_ship=0; $yorder_to_ship=0; $yorder_min=0;
    
        $torder_min=DB::table('buyer_purchse_order_master')->where('job_status_id',5)->count();
        
    
    
    
        $overAllDataY = DB::select("select ifnull(sum(size_qty_total),0) as size_qty_total  from buyer_purchase_order_detail  
        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code WHERE buyer_purchse_order_master.og_id != 4
        AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL  
        and buyer_purchse_order_master.order_close_date    between '".$Financial_Year[0]->fdate."' and NOW()
        and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
        ");
 
        $overAllDataM = DB::select("select ifnull(sum(size_qty_total),0) as size_qty_total  from buyer_purchase_order_detail  
        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code WHERE buyer_purchse_order_master.og_id != 4
        AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL 
        and MONTH(buyer_purchse_order_master.order_close_date)= MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_close_date)=YEAR(CURRENT_DATE())
        and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
        ");
        
        $overAllDataD = DB::select("select ifnull(sum(size_qty_total),0) as size_qty_total  from buyer_purchase_order_detail  
        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code WHERE buyer_purchse_order_master.og_id != 4
        AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL 
         and buyer_purchse_order_master.order_close_date =DATE_SUB(CURDATE(), INTERVAL 1 DAY)
         and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
        ");
 
 
 
        $monthData = DB::select("select ifnull(sum(size_qty_total),0) as cut_order_qty  from cut_panel_grn_detail 
          inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=cut_panel_grn_detail.sales_order_no
            where MONTH(buyer_purchse_order_master.order_close_date)= MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_close_date)=YEAR(CURRENT_DATE())
            and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
             AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL
            ");
        
      
        $todayData = DB::select("select ifnull(sum(size_qty_total),0) as cut_order_qty from cut_panel_grn_detail 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=cut_panel_grn_detail.sales_order_no
            where buyer_purchse_order_master.order_close_date =DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL
            ");

        $yearData = DB::select("select ifnull(sum(size_qty_total),0) as cut_order_qty from cut_panel_grn_detail 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=cut_panel_grn_detail.sales_order_no
            where buyer_purchse_order_master.order_close_date    between '".$Financial_Year[0]->fdate."' and NOW() and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
            AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL
            ");

        $monthInvoiceData = DB::select("select ifnull(sum(size_qty_total),0) as invoice_qty from carton_packing_inhouse_detail 
            INNER JOIN carton_packing_inhouse_master ON carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
            inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=carton_packing_inhouse_detail.sales_order_no
            where MONTH(buyer_purchse_order_master.order_close_date)=MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_close_date)=YEAR(CURRENT_DATE())
            AND carton_packing_inhouse_master.endflag=1  and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
             AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL
            ");
     
        $todayInvoiceData = DB::select("select ifnull(sum(size_qty_total),0) as invoice_qty from carton_packing_inhouse_detail 
            INNER JOIN carton_packing_inhouse_master ON carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
            inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=carton_packing_inhouse_detail.sales_order_no
            where buyer_purchse_order_master.order_close_date =DATE_SUB(CURDATE(), INTERVAL 1 DAY)  AND carton_packing_inhouse_master.endflag=1
            AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL
            and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
            ");
            
        $yearInvoiceData = DB::select("select ifnull(sum(size_qty_total),0) as invoice_qty from carton_packing_inhouse_detail  
            INNER JOIN carton_packing_inhouse_master ON carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
            inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=carton_packing_inhouse_detail.sales_order_no
            where buyer_purchse_order_master.order_close_date  between '".$Financial_Year[0]->fdate."' and NOW()   AND carton_packing_inhouse_master.endflag=1
            AND buyer_purchse_order_master.job_status_id =2 and   buyer_purchse_order_master.order_close_date is NOT NULL
            and buyer_purchse_order_master.order_close_date !='".date('Y-m-d')."'
            ");
    
       if($todayInvoiceData[0]->invoice_qty >0 && $overAllDataD[0]->size_qty_total>0)
       {
           $torder_to_ship = ((($todayInvoiceData[0]->invoice_qty/$overAllDataD[0]->size_qty_total)*100));
       }
       else
       {
           $torder_to_ship=0;
       }
       
       if($monthInvoiceData[0]->invoice_qty >0 && $overAllDataM[0]->size_qty_total>0)
       {
        $order_to_ship = ((($monthInvoiceData[0]->invoice_qty/$overAllDataM[0]->size_qty_total)*100));
       }
       else
       {
           $order_to_ship=0;
       }
       
       if($yearInvoiceData[0]->invoice_qty >0 && $overAllDataY[0]->size_qty_total>0)
       {
        $yorder_to_ship = ((($yearInvoiceData[0]->invoice_qty/$overAllDataY[0]->size_qty_total)*100));
       }
       else
       {
           $yorder_to_ship=0;
       }
        
        
        
        if($monthData[0]->cut_order_qty > 0)
        {
            $cut_to_ship = ((($monthInvoiceData[0]->invoice_qty/$monthData[0]->cut_order_qty)*100));
        }
        else
        {
             $cut_to_ship = 0;
        }
          
        if($todayData[0]->cut_order_qty > 0)
        {
            $tcut_to_ship = ((($todayInvoiceData[0]->invoice_qty/$todayData[0]->cut_order_qty)*100));
        }
        else
        {
            $tcut_to_ship = 0;
        }
         
        if($yearData[0]->cut_order_qty > 0)
        {
            $ycut_to_ship =  ((($yearInvoiceData[0]->invoice_qty/$yearData[0]->cut_order_qty)*100));
        }
        
        
        else
        {
             $ycut_to_ship = 0;
        } 
              
        $html .='<tr>
                    <td> Cut to Ship </td>
                    <td>'.money_format('%!i',  round($tcut_to_ship,2)).'</td>
                    <td>'.money_format('%!i', round($cut_to_ship,2)).'</td>
                    <td>'.money_format('%!i', round($ycut_to_ship,2)).'</td>
                  </tr>
                  <tr>
                    <td> Order to Ship </td>
                    <td>'.money_format('%!i',  round($torder_to_ship,2)).'</td>
                    <td>'.money_format('%!i', round($order_to_ship,2)).'</td>
                    <td>'.money_format('%!i', round($yorder_to_ship,2)).'</td>
                  </tr>
                  <tr>
                    <td> No. of Orders Pending for OCR </td>
                    <td>'.money_format('%!.0n', round($torder_min,2)).'</td>
                    <td>'.money_format('%!.0n', round($order_min,2)).'</td>
                    <td>'.money_format('%!.0n', round($yorder_min,2)).'</td>
                  </tr>';
        
            $ocrArr = array(
                array('Cut to Ship','%',round($tcut_to_ship,2),round($cut_to_ship,2),round($ycut_to_ship,2),3,""),
                array('Order to Ship','%',round($torder_to_ship,2),round($order_to_ship,2),round($yorder_to_ship,2),3,""),
                array(' No. of Orders Pending for OCR','',round($torder_min),round($order_min),round($yorder_min),3,"")
            );      
            $this->tempInsertData($ocrArr);
        
        return response()->json(['html' => $html]);
    }
    
    public function fabricMDDashboard()
    {
        $html = '';
        $todayData = DB::select("select ifnull(sum(meter * item_rate),0) as total_value,sum(meter) as meter from inward_details 
         WHERE in_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
        
        $monthData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from inward_details 
        WHERE MONTH(inward_details.in_date) = MONTH(CURRENT_DATE()) and YEAR(inward_details.in_date)=YEAR(CURRENT_DATE())
            and inward_details.in_date !='".date('Y-m-d')."'
        ");
        
        $yearData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from inward_details 
        WHERE in_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)
           and inward_details.in_date !='".date('Y-m-d')."'
        ");
        
        
        $tvalue = ($todayData[0]->total_value);
        $mvalue = ($monthData[0]->total_value)/100000;
        $yvalue = ($yearData[0]->total_value)/100000;
        
        $todayOutData = DB::select("select ifnull(sum(meter * item_rate),0) as total_value,sum(meter) as meter from fabric_outward_details 
         WHERE fout_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
        
        $monthOutData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from fabric_outward_details 
        WHERE MONTH(fabric_outward_details.fout_date) = MONTH(CURRENT_DATE()) and YEAR(fabric_outward_details.fout_date)=YEAR(CURRENT_DATE())
        and fabric_outward_details.fout_date !='".date('Y-m-d')."'
        
        ");
        
        $yearOutData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from fabric_outward_details 
        where fout_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)
           and fabric_outward_details.fout_date !='".date('Y-m-d')."'
        ");
        
        $tOutValue = ($todayOutData[0]->total_value);
        $mOutValue = ($monthOutData[0]->total_value)/100000;
        $yOutValue = ($yearOutData[0]->total_value)/100000;
        
        $tGrnMeterQty = $todayData[0]->meter;
        $mGrnMeterQty = $monthData[0]->meter/100000;
        $yGrnMeterQty = $yearData[0]->meter/100000;
        
        $tIssueMeterQty = $todayOutData[0]->meter;
        $mIssueMeterQty = $monthOutData[0]->meter/100000;
        $yIssueMeterQty = $yearOutData[0]->meter/100000;
        
        $html .='<tr>
                <td> GRN Quantity-Meter</td>
                <td>'.money_format('%!.0n', round($tGrnMeterQty)).'</td>
                <td>'.money_format('%!.0n', round($mGrnMeterQty,2)).'</td>
                <td>'.money_format('%!.0n', round($yGrnMeterQty,2)).'</td>
              </tr>
              <tr>
                <td> GRN Value </td>
                <td>'.money_format('%!.0n', round($tvalue)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td>  Issued Quantity-Meter </td>
                 <td>'.money_format('%!.0n', round($tIssueMeterQty)).'</td>
                <td>'.money_format('%!.0n', round($mIssueMeterQty,2)).'</td>
                <td>'.money_format('%!.0n', round($yIssueMeterQty,2)).'</td>
              </tr>
              <tr>
                <td>  Issued Value   </td>
                <td>'.money_format('%!.0n', round($tOutValue)).'</td>
                <td>'.money_format('%!.0n', round($mOutValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yOutValue,2)).'</td>
              </tr>';
        
            $fabricArr = array(
                array('GRN Quantity-Meter','',round($tGrnMeterQty),round($mGrnMeterQty,2),round($yGrnMeterQty,2),4,""),
                array('GRN Value','Rs',round($tvalue),round($mvalue,2),round($yvalue,2),4,""),
                array('Issued Quantity-Meter','',round($tIssueMeterQty),round($mIssueMeterQty,2),round($yIssueMeterQty,2),4,""),
                array('Issued Value','Rs',round($tOutValue),round($mOutValue,2),round($yOutValue,2),4,""),
            );      
            $this->tempInsertData($fabricArr); 
            
        return response()->json(['html' => $html]);
    }
    
    public function trimsMDDashboard()
    {
        $html = '';
        $todayData = DB::select("select sum(trimsInwardDetail.item_qty * trimsInwardDetail.item_rate) as total_value, sum(trimsInwardDetail.item_qty) as item_qty from trimsInwardDetail
        LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
        WHERE trimDate = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND item_master.cat_id != 4");
        
        $monthData = DB::select("select sum(trimsInwardDetail.item_qty * trimsInwardDetail.item_rate) as total_value, sum(trimsInwardDetail.item_qty) as item_qty from trimsInwardDetail 
        LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
        WHERE MONTH(trimsInwardDetail.trimDate) = MONTH(CURRENT_DATE()) and YEAR(trimsInwardDetail.trimDate)=YEAR(CURRENT_DATE()) AND item_master.cat_id != 4");
        
        $yearData = DB::select("select sum(trimsInwardDetail.item_qty * trimsInwardDetail.item_rate) as total_value, sum(trimsInwardDetail.item_qty) as item_qty from trimsInwardDetail  
        LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
        WHERE trimDate between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) AND item_master.cat_id != 4");
        
        $tvalue = ($todayData[0]->total_value);
        $mvalue = ($monthData[0]->total_value)/100000;
        $yvalue = ($yearData[0]->total_value)/100000;
        
        $todayOutData = DB::select("select sum(trimsOutwardDetail.item_qty * trimsOutwardDetail.item_rate) as total_value, sum(trimsOutwardDetail.item_qty) as item_qty from trimsOutwardDetail 
                LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
                WHERE tout_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND item_master.cat_id != 4");
        
        $monthOutData = DB::select("select sum(trimsOutwardDetail.item_qty * trimsOutwardDetail.item_rate) as total_value, sum(trimsOutwardDetail.item_qty) as item_qty from trimsOutwardDetail 
                LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
                WHERE MONTH(trimsOutwardDetail.tout_date) = MONTH(CURRENT_DATE()) and YEAR(trimsOutwardDetail.tout_date)=YEAR(CURRENT_DATE()) AND item_master.cat_id != 4");
        
        $yearOutData = DB::select("select sum(trimsOutwardDetail.item_qty * trimsOutwardDetail.item_rate) as total_value, sum(trimsOutwardDetail.item_qty) as item_qty from trimsOutwardDetail 
                        LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
                        WHERE tout_date between (select fdate from financial_year_master 
                        where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) AND item_master.cat_id != 4");
        
        $tOutValue = ($todayOutData[0]->total_value);
        $mOutValue = ($monthOutData[0]->total_value)/100000;
        $yOutValue = ($yearOutData[0]->total_value)/100000;
        
        $html .='<tr>
                <td> GRN Value </td>
                <td>'.money_format('%!.0n', round($tvalue)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td> Issued Value </td>
                <td>'.money_format('%!.0n', round($tOutValue)).'</td>
                <td>'.money_format('%!.0n', round($mOutValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yOutValue,2)).'</td>
              </tr>';
              
            $trimsArr = array(
                array(' GRN Value','Rs',round($tvalue),round($mvalue,2),round($yvalue,2),5,""),
                array('Issued Value','Rs',round($tOutValue),round($mOutValue,2),round($yOutValue,2),5,"")
            );      
            $this->tempInsertData($trimsArr); 
            
        return response()->json(['html' => $html]);
    }
    
    public function operationMDDashboard()
    {
         $html = '';
         $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at
         FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
       
        
       
        $todayData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_grn_size_detail2 
         WHERE vendorId=56 and cpg_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
        
        $monthData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_grn_size_detail2 
        WHERE vendorId=56 and MONTH(cut_panel_grn_size_detail2.cpg_date) = MONTH(CURRENT_DATE()) and YEAR(cut_panel_grn_size_detail2.cpg_date)=YEAR(CURRENT_DATE())");
        
        $yearData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_grn_size_detail2 
        WHERE vendorId=56 and cut_panel_grn_size_detail2.cpg_date between '".$Financial_Year[0]->fdate."' and NOW()");
        
        $tvalue = ($todayData[0]->size_qty);
        $mvalue = ($monthData[0]->size_qty)/100000;
        $yvalue = ($yearData[0]->size_qty)/100000;
        
        $todayIssueData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
         WHERE vendorId=56 and cpi_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
        
        $monthIssueData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
        WHERE vendorId=56 and MONTH(cut_panel_issue_size_detail2.cpi_date) = MONTH(CURRENT_DATE()) and YEAR(cut_panel_issue_size_detail2.cpi_date)=YEAR(CURRENT_DATE())");
        
        $yearIssueData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
        WHERE vendorId=56 and cut_panel_issue_size_detail2.cpi_date between '".$Financial_Year[0]->fdate."' and NOW()");
        
        $tIssueValue = ($todayIssueData[0]->size_qty);
        $mIssueValue = ($monthIssueData[0]->size_qty)/100000;
        $yIssueValue = ($yearIssueData[0]->size_qty)/100000;
        
         //---------------------------------------------Over All-----------------------------------------
         
         
                
                $todayKen_1DataOverAll = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  
                buyer_purchse_order_master.tr_code=stitching_inhouse_master.sales_order_no) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where 
                qcstitching_inhouse_reject_detail.qcsti_date= DATE_SUB(CURDATE(), INTERVAL 1 DAY) and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code 
                WHERE stitching_inhouse_size_detail2.sti_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY) and stitching_inhouse_size_detail2.vendorId in (56,115,69,110)
                group by stitching_inhouse_master.sales_order_no");
        
                $monthKen_1DataOverAll = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  
                buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                MONTH(qcstitching_inhouse_reject_detail.qcsti_date)=MONTH(CURRENT_DATE()) 
                and YEAR(qcstitching_inhouse_reject_detail.qcsti_date)=YEAR(CURRENT_DATE()) and 
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE   stitching_inhouse_size_detail2.vendorId in (56,115,69,110) and MONTH(stitching_inhouse_size_detail2.sti_date) = MONTH(CURRENT_DATE()) and YEAR(stitching_inhouse_size_detail2.sti_date)=YEAR(CURRENT_DATE())  
                group by stitching_inhouse_master.sales_order_no");
                
                $yearKen_1DataOverAll = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where 
                buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                qcstitching_inhouse_reject_detail.qcsti_date  between '".$Financial_Year[0]->fdate."'  and NOW() and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE   stitching_inhouse_size_detail2.vendorId in (56,115,69,110) and  stitching_inhouse_size_detail2.sti_date between '".$Financial_Year[0]->fdate."'  and NOW()
                group by stitching_inhouse_master.sales_order_no");
                
                $todayKen_1MinOverAll=0;
                $todayKen_1Plant_EffiOverAll=0;
                $todayWorkerMinOverAll=0;
                $todaySizeQtyOverAll=0;
                $todaysRejectQtyOverAll=0;
                $todaysRejPerOverAll=0;
                $notOverAll=1;
                foreach($todayKen_1DataOverAll as $row1)
                {
                    $todaySizeQtyOverAll = $todaySizeQtyOverAll + $row1->size_qty;
                    if(($row1->size_qty * $row1->sam) !=0 &&  $row1->total_workers!=0)
                    {   
                        $todayWorkerMinOverAll= $todayWorkerMinOverAll + $row1->total_workers * 480;
                        $todayKen_1MinOverAll =$todayKen_1MinOverAll + $row1->size_qty * $row1->sam;
                        $todayKen_1Plant_EffiOverAll = $todayKen_1Plant_EffiOverAll + ((($row1->size_qty * $row1->sam)/($row1->total_workers * 480))) * 100;
                        $todaysRejectQtyOverAll = $todaysRejectQtyOverAll + $row1->rejectionQty;
                        $notOverAll++;
                    }
                    else
                    {
                        $todayKen_1Plant_EffiOverAll = $todayKen_1Plant_EffiOverAll + 0;
                    }
                 
                }
                
                if($todaysRejectQtyOverAll!=0 && $todaySizeQtyOverAll!=0)
               { $todaysRejPerOverAll =  round((($todaysRejectQtyOverAll/ $todaySizeQtyOverAll)*100),2);}else{$todaysRejPerOverAll=0;}
                
                 if($todayKen_1Plant_EffiOverAll!=0 && ($notOverAll-1)!=0)
                {
                    $todayKen_1Plant_EffiOverAll = $todayKen_1Plant_EffiOverAll / ($notOverAll-1);
                }
                else
                {
                    $todayKen_1Plant_EffiOverAll=0;
                }
                
                
                $monthKen_1MinOverAll=0;
                $monthSizeQtyOverAll=0;
                $monthworkersMinOverAll=0;
                $monthRejectQtyOverAll=0;
                $monthRejPerOverAll=0;
                $monthKen_1Plant_EffiOverAll=0; $noOverAll=1;
                foreach($monthKen_1DataOverAll as $row2)
                {
                    $monthSizeQtyOverAll = $monthSizeQtyOverAll + $row2->size_qty;
                    if(($row2->size_qty * $row2->sam) !=0 &&  $row2->total_workers!=0)
                    {   $monthRejectQtyOverAll= $monthRejectQtyOverAll + $row2->rejectionQty;
                        $monthKen_1MinOverAll =$monthKen_1MinOverAll +  $row2->size_qty * $row2->sam;
                        $monthworkersMinOverAll= $monthworkersMinOverAll + ($row2->total_workers * 480);
                        $monthKen_1Plant_EffiOverAll = $monthKen_1Plant_EffiOverAll + ((($row2->size_qty * $row2->sam)/($row2->total_workers * 480))) * 100;
                         
                        $noOverAll++;
                    }
                    else
                    {
                        $monthKen_1Plant_EffiOverAll = $monthKen_1Plant_EffiOverAll + 0;
                    }
                  
                }
                
                
                 if($monthRejectQtyOverAll!=0 && $monthSizeQtyOverAll!=0)
               { $monthRejPerOverAll=  round((($monthRejectQtyOverAll/ $monthSizeQtyOverAll)*100),2);}else{$$monthRejPerOverAll=0;}
                
                //echo $monthRejPerOverAll;
                if($monthKen_1Plant_EffiOverAll!=0 && ($noOverAll-1)!=0)
                {
                     $monthKen_1Plant_EffiOverAll=  $monthKen_1Plant_EffiOverAll/ ($noOverAll-1);
                }
                else
                {
                    $monthKen_1Plant_EffiOverAll=0;
                }
               
                 $yearKen_1MinOverAll=0;
                 $yearSizeQtyOverAll=0;
                 $yearWorkerMinOverAll=0;
                 $yearKen_1Plant_EffiOverAll=0;
                 $noyOverAll=1;
                 $yearRejectQtyOverAll=0;
                 $yearRejPerOverAll=0;
                foreach($yearKen_1DataOverAll as $row3)
                {
                    $yearSizeQtyOverAll = $yearSizeQtyOverAll + $row3->size_qty;
                    if(($row3->size_qty * $row3->sam) !=0 &&  $row3->total_workers!=0)
                    {   
                        $yearRejectQtyOverAll = $yearRejectQtyOverAll + $row3->rejectionQty;
                        $yearKen_1MinOverAll = $yearKen_1MinOverAll +  $row3->size_qty * $row3->sam;
                        $yearWorkerMinOverAll = $yearWorkerMinOverAll + ($row3->total_workers * 480);
                        $yearKen_1Plant_EffiOverAll = $monthKen_1Plant_EffiOverAll + ((($row3->size_qty * $row3->sam)/($row3->total_workers * 480))) * 100;
                        $noyOverAll++;
                    }
                    else
                    {
                        $yearKen_1Plant_EffiOverAll = $yearKen_1Plant_EffiOverAll + 0;
                    }
                  
                }
                
                if($yearRejectQtyOverAll!=0 && $yearSizeQtyOverAll!=0)
               { $yearRejPerOverAll= round((($yearRejectQtyOverAll/ $yearSizeQtyOverAll)*100),2);}else{$yearRejPer=0;}
                
                
                if($yearKen_1Plant_EffiOverAll!=0 && ($noOverAll-1)!=0)
                {
                     $yearKen_1Plant_EffiOverAll=  $yearKen_1Plant_EffiOverAll/ ($noOverAll-1);
                }
                else
                {
                    $yearKen_1Plant_EffiOverAll=0;
                }
                
                  
           // echo $yearKen_1Plant_Effi; exit;
           
            
            $html .='<tr>
                <td>Cutting Quantity Pcs</td>
                <td>'.money_format('%!.0n', round($tvalue)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td>Cut Panel Issue Quantity Pcs</td>
                <td>'.money_format('%!.0n', round($tIssueValue,2)).'</td>
                <td>'.money_format('%!.0n', round($mIssueValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yIssueValue,2)).'</td>
              </tr>';
            //   <tr>
            //     <td>Cutting Room Efficiency</td>
            //     <td>-</td>
            //     <td>-</td>
            //     <td>-</td>
            //   </tr>
              
            
             $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">OverAll</td></tr>
              <tr>
                <td>Produced Pcs</td>
                <td class="tpcs">'.money_format('%!.0n', $todaySizeQtyOverAll).'</td>
                <td class="mpcs">'.money_format('%!.0n', $monthSizeQtyOverAll).'</td>
                <td class="ypcs">'.money_format('%!.0n', $yearSizeQtyOverAll).'</td>
              </tr> 
              <tr>
                <td>Produced Minutes</td>
                <td class="tmin">'.money_format('%!.0n', round($todayKen_1MinOverAll,2)).'</td>
                <td class="mmin">'.money_format('%!.0n', round($monthKen_1MinOverAll,2)).'</td>
                <td class="ymin">'.money_format('%!.0n', round($yearKen_1MinOverAll,2)).'</td>
              </tr>
              <tr>
                <td>Plant Efficiency</td>';
                if($todayKen_1Plant_EffiOverAll !=0 && count($todayKen_1DataOverAll)!=0)
                { 
                   $html .='<td class="teff">'.money_format('%!.0n', round((($todayKen_1Plant_EffiOverAll)/count($todayKen_1DataOverAll)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                
                 if($monthKen_1Plant_EffiOverAll !=0 && count($monthKen_1DataOverAll)!=0)
                {
                    $html .='<td class="meff">'.money_format('%!.0n', round((($monthKen_1Plant_EffiOverAll)/count($monthKen_1DataOverAll)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                 
                 if($yearKen_1Plant_EffiOverAll !=0 && count($yearKen_1DataOverAll)!=0)
                { 
                    $html .='<td class="yeff">'.money_format('%!.0n', round((($yearKen_1Plant_EffiOverAll)/count($yearKen_1DataOverAll)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                } 
                
              $html .='</tr>
              <tr>
                <td>CPM</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>DHU</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Rejection</td>
                <td>'.$todaysRejPerOverAll.'</td>
                 <td>'.$monthRejPerOverAll.'</td>
                  <td>'.$yearRejPerOverAll.'</td>
              </tr>';
                          
                // $operationArr = array(
                //     array('Produced Pcs','',round($todaySizeQty,2),round($monthSizeQty,2),round($yearSizeQty,2),6, $vendor->ac_name),
                //     array('Produced Minutes','',round($todayKen_1Min,2),round($monthKen_1Min,2),round($yearKen_1Min,2),6,$vendor->ac_name),
                //     array('Plant Efficiency','%',round($todayKen_1Plant_Effi,2),round($monthKen_1Plant_Effi,2),round($yearKen_1Plant_Effi,2),6,$vendor->ac_name),
                //     array('CPM','Rs/Min',"-","-","-",6,$vendor->ac_name),
                //     array('DHU','%',"-","-","-",6,$vendor->ac_name),
                //     array('Rejection','%',$todaysRejPer,$monthRejPer,$yearRejPer,6,$vendor->ac_name)
                // );      
                // $this->tempInsertData($operationArr);  
        
        // Over All End -------------------------------------------
        
             
                          
            $operationArr2 = array(
                array('Cutting Quantity Pcs','',round($tvalue),round($mvalue,2),round($yvalue,2),6,""),
                array('Cut Panel Issue Quantity Pcs','',round($tIssueValue),round($mIssueValue,2),round($yIssueValue,2),6,""),
               
                array('Produced Pcs','',round($todaySizeQtyOverAll,2),round($monthSizeQtyOverAll/100000,2),round($yearSizeQtyOverAll/100000,2),6,"Overall"),
                array('Produced Minutes','',round($todayKen_1MinOverAll,2),round($monthKen_1MinOverAll/100000,2),round($yearKen_1MinOverAll/100000,2),6,"Overall"),
                array('Plant Efficiency','%',round($todayKen_1Plant_EffiOverAll,2),round($monthKen_1Plant_EffiOverAll,2),round($yearKen_1Plant_EffiOverAll,2),6,"Overall"),
                array('CPM','Rs/Min',"-","-","-",6,"Overall"),
                array('DHU','%',"-","-","-",6,"Overall"),
                array('Rejection','%',$todaysRejPerOverAll,$monthRejPerOverAll,$yearRejPerOverAll,6,"Overall")
            );      
            $this->tempInsertData($operationArr2); 
            
             //array('Cutting Room Efficiency','%',"-","-","-",6,""),
            
             $vendorData = DB::select("select ac_code,ac_name from ledger_master WHERE ac_code IN (56,115,69,110)");

             foreach($vendorData as $vendor)
             {
                $todayKen_1Data = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  
                buyer_purchse_order_master.tr_code=stitching_inhouse_master.sales_order_no) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where 
                qcstitching_inhouse_reject_detail.vendorId ='".$vendor->ac_code."'and 
                qcstitching_inhouse_reject_detail.qcsti_date= DATE_SUB(CURDATE(), INTERVAL 1 DAY) and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code 
                WHERE stitching_inhouse_size_detail2.sti_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY) 
                AND stitching_inhouse_size_detail2.vendorId ='".$vendor->ac_code."' group by stitching_inhouse_master.sales_order_no");
        
                $monthKen_1Data = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                qcstitching_inhouse_reject_detail.vendorId ='".$vendor->ac_code."'and 
                MONTH(qcstitching_inhouse_reject_detail.qcsti_date)=MONTH(CURRENT_DATE()) and YEAR(qcstitching_inhouse_reject_detail.qcsti_date)=YEAR(CURRENT_DATE()) and 
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE MONTH(stitching_inhouse_size_detail2.sti_date) = MONTH(CURRENT_DATE()) and YEAR(stitching_inhouse_size_detail2.sti_date)=YEAR(CURRENT_DATE())  
                AND stitching_inhouse_size_detail2.vendorId ='".$vendor->ac_code."' group by stitching_inhouse_master.sales_order_no");
                
                $yearKen_1Data = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where 
                buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                qcstitching_inhouse_reject_detail.vendorId ='".$vendor->ac_code."'and 
                qcstitching_inhouse_reject_detail.qcsti_date  between '".$Financial_Year[0]->fdate."'  and NOW() and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE stitching_inhouse_size_detail2.sti_date between '".$Financial_Year[0]->fdate."'  and NOW() AND stitching_inhouse_size_detail2.vendorId ='".$vendor->ac_code."' group by stitching_inhouse_master.sales_order_no");
                $todayKen_1Min=0;
                $todayKen_1Plant_Effi=0;
                $todayWorkerMin=0;
                $todaySizeQty=0;
                $todaysRejectQty=0;
                $todaysRejPer=0;
                $not=1;
                foreach($todayKen_1Data as $row1)
                {
                     $todaySizeQty = $todaySizeQty + $row1->size_qty;
                       $todayKen_1Min =$todayKen_1Min + $row1->size_qty * $row1->sam;
                       $todaysRejectQty = $todaysRejectQty + $row1->rejectionQty;
                     if(($row1->size_qty * $row1->sam) !=0 &&  $row1->total_workers!=0)
                    {   
                        $todayWorkerMin= $todayWorkerMin + $row1->total_workers * 480;
                      
                        $todayKen_1Plant_Effi =$todayKen_1Plant_Effi + ((($row1->size_qty * $row1->sam)/($row1->total_workers  * 480))) * 100;
                       
                        $not++;
                    } 
                    else
                    {
                        $todayKen_1Plant_Effi = $todayKen_1Plant_Effi + 0;
                    }
                 
                }
                
                if($todaysRejectQty!=0 && $todaySizeQty!=0)
               { $todaysRejPer=   round((($todaysRejectQty/ $todaySizeQty)*100),2);}else{$todaysRejPer=0;}
                
                
                
                if($todayKen_1Plant_Effi!=0 && ($not-1) !=0)
                {
                    $todayKen_1Plant_Effi = $todayKen_1Plant_Effi / ($not-1);
                }
                else
                {
                    $todayKen_1Plant_Effi=0;
                }
                
                
                $monthKen_1Min=0;
                $monthSizeQty=0;
                $monthworkersMin=0;
                $monthRejectQty=0;
                $monthRejPer=0;
                $monthKen_1Plant_Effi=0; $no=1;
                foreach($monthKen_1Data as $row2)
                {
                    $monthSizeQty = $monthSizeQty + $row2->size_qty;
                    $monthRejectQty= $monthRejectQty +   $row2->rejectionQty;
                    $monthKen_1Min =$monthKen_1Min +  $row2->size_qty * $row2->sam;
                    if(($row2->size_qty * $row2->sam) !=0 &&  $row2->total_workers!=0)
                    {  
                        $monthworkersMin= $monthworkersMin + ($row2->total_workers * 480);
                        $monthKen_1Plant_Effi = $monthKen_1Plant_Effi + ((($row2->size_qty * $row2->sam)/($row2->total_workers * 480))) * 100;
                        $no++;
                    }
                    else
                    {
                        $monthKen_1Plant_Effi = $monthKen_1Plant_Effi + 0;
                    }
                  
                }
                
                
                 if($monthRejectQty!=0 && $monthSizeQty!=0)
               { $monthRejPer=  round((($monthRejectQty/ $monthSizeQty)*100),2);}else{$$monthRejPer=0;}
                
                //echo $monthRejPer;
                if($monthKen_1Plant_Effi!=0 && ($no-1)!=0)
                {
                     $monthKen_1Plant_Effi=  $monthKen_1Plant_Effi/ ($no-1);
                }
                else
                {
                    $monthKen_1Plant_Effi=0;
                }
               
                 
                
                 $yearKen_1Min=0;
                 $yearSizeQty=0;
                 $yearWorkerMin=0;
                 $yearKen_1Plant_Effi=0;
                 $noy=1;
                 $yearRejectQty=0;
                $yearRejPer=0;
                foreach($yearKen_1Data as $row3)
                {
                    $yearSizeQty = $yearSizeQty + $row3->size_qty; 
                    $yearRejectQty = $yearRejectQty + $row3->rejectionQty;
                    $yearKen_1Min = $yearKen_1Min +  $row3->size_qty * $row3->sam;
                    if(($row3->size_qty * $row3->sam) !=0 &&  $row3->total_workers!=0)
                    {   
                      
                        $yearWorkerMin = $yearWorkerMin + ($row3->total_workers * 480);
                        $yearKen_1Plant_Effi = $monthKen_1Plant_Effi + ((($row3->size_qty * $row3->sam)/($row3->total_workers * 480))) * 100;
                        $noy++;
                    }
                    else
                    {
                        $yearKen_1Plant_Effi = $yearKen_1Plant_Effi + 0;
                    }
                  
                }
                
                if($yearRejectQty!=0 && $yearSizeQty!=0)
               { $yearRejPer= round((($yearRejectQty/ $yearSizeQty)*100),2);}else{$yearRejPer=0;}
                
                
                if($yearKen_1Plant_Effi!=0 && ($no-1)!=0)
                {
                     $yearKen_1Plant_Effi=  $yearKen_1Plant_Effi/ ($no-1);
                }
                else
                {
                    $yearKen_1Plant_Effi=0;
                }
                
                  
           // echo $yearKen_1Plant_Effi; exit;
        
             $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">'.$vendor->ac_name.'</td></tr>
              <tr>
                <td>Produced Pcs</td>
                <td class="tpcs">'.money_format('%!.0n', $todaySizeQty).'</td>
                <td class="mpcs">'.money_format('%!.0n', $monthSizeQty).'</td>
                <td class="ypcs">'.money_format('%!.0n', $yearSizeQty).'</td>
              </tr> 
              <tr>
                <td>Produced Minutes</td>
                <td class="tmin">'.money_format('%!.0n', round($todayKen_1Min,2)).'</td>
                <td class="mmin">'.money_format('%!.0n', round($monthKen_1Min,2)).'</td>
                <td class="ymin">'.money_format('%!.0n', round($yearKen_1Min,2)).'</td>
              </tr>
              <tr>
                <td>Plant Efficiency</td>';
                if($todayKen_1Plant_Effi !=0 && count($todayKen_1Data)!=0)
                { 
                   $html .='<td class="teff">'.money_format('%!.0n', round((($todayKen_1Plant_Effi)/count($todayKen_1Data)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                
                 if($monthKen_1Plant_Effi !=0 && count($monthKen_1Data)!=0)
                {
                    $html .='<td class="meff">'.money_format('%!.0n', round((($monthKen_1Plant_Effi)/count($monthKen_1Data)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                 
                 if($yearKen_1Plant_Effi !=0 && count($yearKen_1Data)!=0)
                { 
                    $html .='<td class="yeff">'.money_format('%!.0n', round((($yearKen_1Plant_Effi)/count($yearKen_1Data)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                } 
                
              $html .='</tr>
              <tr>
                <td>CPM</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>DHU</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Rejection</td>
                <td>'.$todaysRejPer.'</td>
                 <td>'.$monthRejPer.'</td>
                  <td>'.$yearRejPer.'</td>
              </tr>';
                          
                $operationArr = array(
                    array('Produced Pcs','',round($todaySizeQty,2),round($monthSizeQty/100000,2),round($yearSizeQty/100000,2),6, $vendor->ac_name),
                    array('Produced Minutes','',round($todayKen_1Min,2),round($monthKen_1Min/100000,2),round($yearKen_1Min/100000,2),6,$vendor->ac_name),
                    array('Plant Efficiency','%',round($todayKen_1Plant_Effi,2),round($monthKen_1Plant_Effi,2),round($yearKen_1Plant_Effi,2),6,$vendor->ac_name),
                    array('CPM','Rs/Min',"-","-","-",6,$vendor->ac_name),
                    array('DHU','%',"-","-","-",6,$vendor->ac_name),
                    array('Rejection','%',$todaysRejPer,$monthRejPer,$yearRejPer,6,$vendor->ac_name)
                );      
                $this->tempInsertData($operationArr);  
             }
          
            $tOutSourceData = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
                sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as TodayInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId NOT IN (56,115,69,110) AND packing_inhouse_size_detail2.pki_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
               
                ");
                
                
            $mOutSourceData = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
              sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as MonthInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId NOT IN (56,115,69,110) AND 
                MONTH(packing_inhouse_size_detail2.pki_date) = MONTH(CURRENT_DATE()) and YEAR(packing_inhouse_size_detail2.pki_date)=YEAR(CURRENT_DATE())
                 AND packing_inhouse_size_detail2.pki_date !='".date('Y-m-d')."'
                ");
            
            $yOutSourceData = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
              sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as YearInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId NOT IN (56,115,69,110) AND
                packing_inhouse_size_detail2.pki_date between '".$Financial_Year[0]->fdate."'  and NOW()
                 AND packing_inhouse_size_detail2.pki_date !='".date('Y-m-d')."'
                ");
                     
            
            // $tOutSourceMin = $tOutSourceData[0]->size_qty * $tOutSourceData[0]->sam;
            // $mOutSourceMin = $mOutSourceData[0]->size_qty * $mOutSourceData[0]->sam;
            // $yOutSourceMin = $yOutSourceData[0]->size_qty * $yOutSourceData[0]->sam;
            
            
            
            $tOutSourceMin = $tOutSourceData[0]->TodayInwardMins;
            $mOutSourceMin =  $mOutSourceData[0]->MonthInwardMins;
            $yOutSourceMin =  $yOutSourceData[0]->YearInwardMins;
            
            
            
             $tOutSourceData2 = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
                sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as TodayInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId =56 AND packing_inhouse_size_detail2.pki_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
               
                ");
                
                
            $mOutSourceData2 = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
              sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as MonthInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId =56 AND 
                MONTH(packing_inhouse_size_detail2.pki_date) = MONTH(CURRENT_DATE()) and YEAR(packing_inhouse_size_detail2.pki_date)=YEAR(CURRENT_DATE())
                 AND packing_inhouse_size_detail2.pki_date !='".date('Y-m-d')."'
                ");
            
            $yOutSourceData2 = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
              sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as YearInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId =56 AND
                packing_inhouse_size_detail2.pki_date between '".$Financial_Year[0]->fdate."'  and NOW()
                 AND packing_inhouse_size_detail2.pki_date !='".date('Y-m-d')."'
                ");
            
            
            $tPkgQty = ($tOutSourceData2[0]->size_qty)/100000;
            $mPkgQty = ($mOutSourceData2[0]->size_qty)/100000;
            $yPkgQty = ($yOutSourceData2[0]->size_qty)/100000;
            
              
            $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">Outsource</td></tr>
              <tr>
                <td>Inward Pcs</td>
                <td class="tpcs">'.money_format('%!.0n', $tOutSourceData[0]->size_qty).'</td>
                <td class="mpcs">'.money_format('%!.0n', $mOutSourceData[0]->size_qty).'</td>
                <td class="ypcs">'.money_format('%!.0n', $yOutSourceData[0]->size_qty).'</td>
              </tr> 
              <tr>
                <td>Inward Minutes</td>
                <td class="tmin">'.money_format('%!.0n', $tOutSourceData[0]->TodayInwardMins).'</td>
                <td class="mmin">'.money_format('%!.0n', $mOutSourceData[0]->MonthInwardMins).'</td>
                <td class="ymin">'.money_format('%!.0n', $yOutSourceData[0]->YearInwardMins).'</td>
              </tr>
              <tr>
                <td>CPM</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Rejection</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td> Packing Quantity Pcs </td>
                <td>'.money_format('%!.0n',$tPkgQty).'</td>
                <td>'.money_format('%!.0n',$mPkgQty).'</td>
                <td>'.money_format('%!.0n',$yPkgQty).'</td>
              </tr>';
            
            $operationArr7 = array(
                array('Inward Pcs','',round($tOutSourceData[0]->size_qty,2),round($mOutSourceData[0]->size_qty/100000,2),round($yOutSourceData[0]->size_qty/100000,2),6,"Outsource"),
                array('Inward Minutes','',round($tOutSourceMin,2),round($mOutSourceMin/100000,2),round($yOutSourceMin/100000,2),6,"Outsource"),
                array('CPM','Rs/Min',"-","-","-",6,"Outsource"),
                array('Rejection','',"-","-","-",6,"Outsource"),
                array('Packing Quantity Pcs','',round($tPkgQty,2),round($mPkgQty,2),round($yPkgQty,2),7,""),
            );      
            $this->tempInsertData($operationArr7);  
        return response()->json(['html' => $html]);
    }
    
    public function openOrderMDDashboard()
    {         
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        //DB::enableQueryLog();
        // dd(DB::getQueryLog());
        $html = '';
        $FGStock= 0;
        
        //DB::enableQueryLog();
            $todayData = DB::select("select ifnull(sum(total_qty),0) as total_qty,
            (select ifnull(sum(buyer_purchase_order_detail.adjust_qty),0) from buyer_purchase_order_detail where buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code )as total_adjust_qty,
            (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code
            and packing_inhouse_master.pki_date <= '".date('Y-m-d')."'
            ) as shipped_qty,
            (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code
            and cut_panel_grn_master.cpg_date <= '".date('Y-m-d')."'
            ) as cut_qty,
             (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code
             and stitching_inhouse_master.sti_date <= '".date('Y-m-d')."'
             ) as prod_qty,
             (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.sales_order_no=buyer_purchse_order_master.tr_code
              and qcstitching_inhouse_reject_detail.qcsti_date <= '".date('Y-m-d')."'
             ) as reject_qty,
            buyer_purchse_order_master.sam,
            ifnull(sum(balance_qty),0) as balance_qty,
            sum(balance_qty * buyer_purchse_order_master.sam) as balance_value 
            from buyer_purchse_order_master 
            INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
            WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 
            AND buyer_purchse_order_master.job_status_id = 1  and
            buyer_purchse_order_master.order_received_date <= '".date('Y-m-d')."'
            group by buyer_purchse_order_master.tr_code
        ");
        
        
        // dd(DB::getQueryLog());
        $monthData = DB::select("select ifnull(sum(total_qty),0) as total_qty,  
        (select ifnull(sum(buyer_purchase_order_detail.adjust_qty),0) from buyer_purchase_order_detail where buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code )as total_adjust_qty,
        (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code
        and packing_inhouse_master.pki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as shipped_qty,
        (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code
        and cut_panel_grn_master.cpg_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        
        ) as cut_qty,
        (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.sales_order_no=buyer_purchse_order_master.tr_code
       and  qcstitching_inhouse_reject_detail.qcsti_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as reject_qty,
        (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code
         and  stitching_inhouse_master.sti_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as prod_qty,
        buyer_purchse_order_master.sam,  
        sum(balance_qty * buyer_purchse_order_master.sam) as balance_value,
        ifnull(sum(balance_qty),0) as balance_qty from buyer_purchse_order_master 
        INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
        WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 
        AND buyer_purchse_order_master.job_status_id = 1  and
        buyer_purchse_order_master.order_received_date  <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') 
        group by buyer_purchse_order_master.tr_code");
        
        
        
        $yearData = DB::select("select ifnull(sum(total_qty),0) as total_qty,  
        (select ifnull(sum(buyer_purchase_order_detail.adjust_qty),0) from buyer_purchase_order_detail 
        where buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code )as total_adjust_qty,
        (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code
        and packing_inhouse_master.pki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH),'%Y-%m-%d')
          ) as shipped_qty,
        (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code
         and cut_panel_grn_master.cpg_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH),'%Y-%m-%d')
        ) as cut_qty,
        (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code
        and stitching_inhouse_master.sti_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH),'%Y-%m-%d')
        ) as prod_qty,
        (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.sales_order_no=buyer_purchse_order_master.tr_code
         and qcstitching_inhouse_reject_detail.qcsti_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH),'%Y-%m-%d')
        ) as reject_qty,
        
       
        buyer_purchse_order_master.sam, 
        sum(balance_qty * buyer_purchse_order_master.sam) as balance_value,ifnull(sum(balance_qty),0) as balance_qty 
        from buyer_purchse_order_master 
        INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
        WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4
        AND buyer_purchse_order_master.job_status_id = 1 AND    
        buyer_purchse_order_master.order_received_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH),
        '%Y-%m-%d') group by buyer_purchse_order_master.tr_code");
        
        $tProduceMinValue=0;
        $mProduceMinValue=0;
        $yProduceMinValue=0;
       $tvalue=0; 
       $tMinValue=0;
       $tBalValue=0;
       $tBalMinValue=0;
       $tshippedQty=0;
       $Ttotal_adjust_qty=0;
       $Ttotal_excess_qty=0;
       $excessData=0;$excess_qty=0;
       
       $tBalToProduceQty=0;
       $tBalToProduceMins=0;
       $tBalShipMinValue=0;
       
       foreach($todayData as $td)
       {
           
            $excessData = $td->cut_qty - $td->total_qty;
                     if($excessData < 0)
                     {
                         $excess_qty = 0;
                     }
                     else
                     {
                        $excess_qty = $excessData;
                     }
           
           
           
            $tvalue = $tvalue + ($td->total_qty)/100000;
            $tshippedQty =$tshippedQty +  ($td->shipped_qty/100000);
            $Ttotal_adjust_qty=$Ttotal_adjust_qty + $td->total_adjust_qty;
            $Ttotal_excess_qty=$Ttotal_excess_qty + $excess_qty;
            $tMinValue = $tMinValue + ($td->sam * $td->total_qty)/100000;
            $tBalValue = $tBalValue + ($td->total_qty - $td->shipped_qty - $td->total_adjust_qty + $excess_qty- $td->reject_qty) ;
            
            
            
            
            // $tBalMinValue = $tBalMinValue + ($td->balance_qty * $td->sam)/100000;
            $tBalShipMinValue=$tBalShipMinValue + (($td->total_qty - $td->shipped_qty - $td->total_adjust_qty + $excess_qty - $td->reject_qty)*$td->sam);
            $tBalToProduceQty= $tBalToProduceQty +  ($td->total_qty - $td->prod_qty- $td->total_adjust_qty + $excess_qty);  
            $tBalToProduceMins=$tBalToProduceMins + (($td->total_qty - $td->prod_qty- $td->total_adjust_qty + $excess_qty)*$td->sam);
            //  echo '1';
      }

      
        
        $mvalue=0;
        $mMinVvalue=0;
        $mBalMinValue=0;
        $mBalValue=0;
        $mshippedQty=0;
        $Mtotal_adjust_qty=0;
        $mexcessData=0;
        $mexcess_qty=0;
        $mBalToProduceQty=0;
        $mBalToProduceMins=0;
        $Mtotal_excess_qty=0;
        $mBalShipMinValue=0;
        foreach($monthData as $md)
           {
               
               
               
                $mexcessData = $md->cut_qty - $md->total_qty;
                     if($mexcessData < 0)
                     {
                         $mexcess_qty = 0;
                     }
                     else
                     {
                        $mexcess_qty = $mexcessData;
                     }
           
                $mvalue =$mvalue + ($md->total_qty)/100000;
                $mshippedQty =$mshippedQty +  ($md->shipped_qty/100000);
                $Mtotal_adjust_qty=$Mtotal_adjust_qty + $md->total_adjust_qty;
                $Mtotal_excess_qty=$Mtotal_excess_qty + $mexcess_qty;
                $mMinVvalue = $mMinVvalue +  (($md->sam * $md->total_qty))/100000;
                $mBalMinValue = $mBalMinValue + ($md->balance_value)/100000;
                $mBalValue = $mBalValue + ($md->total_qty - $md->shipped_qty - $md->total_adjust_qty + $mexcess_qty - $md->reject_qty);
               // $mProduceMinValue=$mProduceMinValue + (($md->total_qty - $md->shipped_qty - $md->total_adjust_qty + $mexcess_qty)*$md->sam)/100000;
                  $mBalShipMinValue=$mBalShipMinValue + (($md->total_qty - $md->shipped_qty - $md->total_adjust_qty + $mexcess_qty - $md->reject_qty)*$md->sam);
                $mBalToProduceQty= $tBalToProduceQty +  ($md->total_qty - $md->prod_qty- $md->total_adjust_qty+ $mexcess_qty);  
                $mBalToProduceMins=$tBalToProduceMins + (($md->total_qty - $md->prod_qty- $md->total_adjust_qty+ $mexcess_qty)*$md->sam);
                //echo '2';
           }
       
       
     
       
       
       
       $yMinVvalue=0; 
       $yBalValue=0; 
       $yBalMinValue=0;  
       $yvalue=0;
       $yshippedQty=0;
       $Ytotal_adjust_qty=0;
       $yBalToProduceQty=0;
       $yBalToProduceMins=0;
       $yBalShipMinValue=0;
       $Ytotal_excess_qty=0;
         foreach($yearData as $yd)
       {
           
             
                $yexcessData = $yd->cut_qty - $yd->total_qty;
                     if($yexcessData < 0)
                     {
                         $yexcess_qty = 0;
                     }
                     else
                     {
                        $yexcess_qty = $yexcessData;
                     }
           
           
           
           
            $yshippedQty =$yshippedQty +  ($yd->shipped_qty/100000);
            $Ytotal_adjust_qty=$Ytotal_adjust_qty + $yd->total_adjust_qty;
            $Ytotal_excess_qty=$Ytotal_excess_qty + $yexcess_qty;
            $yMinVvalue =$yMinVvalue +  (($yd->sam * $yd->total_qty))/100000;
            $yBalValue = $yBalValue + ($yd->total_qty - $yd->shipped_qty - $yd->total_adjust_qty + $yexcess_qty - $yd->reject_qty);
            $yBalMinValue = $yBalMinValue + ($yd->balance_value)/100000;
            $yvalue = $yvalue +  ($yd->total_qty)/100000;
            //$yProduceMinValue=$yProduceMinValue + (($yd->total_qty - $yd->shipped_qty - $yd->total_adjust_qty + $yexcess_qty)*$yd->sam)/100000;
             $yBalShipMinValue=$yBalShipMinValue + (($yd->total_qty - $yd->shipped_qty - $yd->total_adjust_qty + $yexcess_qty - $yd->reject_qty)*$yd->sam);
            
            $yBalToProduceQty= $tBalToProduceQty +  ($yd->total_qty - $yd->prod_qty- $yd->total_adjust_qty+ $yexcess_qty);  
            $yBalToProduceMins=$tBalToProduceMins + (($yd->total_qty - $yd->prod_qty- $yd->total_adjust_qty+ $yexcess_qty)*$yd->sam);
           // echo '3';
       }
              
        $mFGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
        (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
        where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1 AND 
         carton_packing_inhouse_size_detail2.cpki_date  <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'carton_pack_qty',
            
              
        (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
        inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        and transfer_packing_inhouse_size_detail2.usedFlag=1 AND  transfer_packing_inhouse_size_detail2.tpki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'transfer_qty' 
        
        FROM `packing_inhouse_size_detail2` 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        WHERE buyer_purchse_order_master.job_status_id=1 and   buyer_purchse_order_master.og_id!=4 and
        packing_inhouse_size_detail2.pki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
    
    
    
    
    
        $tFGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
        (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
        where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1
        AND carton_packing_inhouse_size_detail2.cpki_date <= '".date('Y-m-d')."'
        ) as 'carton_pack_qty',
        
        (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
        inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        and transfer_packing_inhouse_size_detail2.usedFlag=1 AND  transfer_packing_inhouse_size_detail2.tpki_date <= '".date('Y-m-d')."'
        ) as 'transfer_qty' 
        FROM `packing_inhouse_size_detail2` 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        WHERE buyer_purchse_order_master.job_status_id=1 and buyer_purchse_order_master.og_id!=4 
        and packing_inhouse_size_detail2.pki_date <= '".date('Y-m-d')."'");
    
    
    
        $yFGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
        (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
        where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1 AND
        carton_packing_inhouse_size_detail2.cpki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'carton_pack_qty',
          
        (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
        inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        and transfer_packing_inhouse_size_detail2.usedFlag=1 AND
        transfer_packing_inhouse_size_detail2.tpki_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'transfer_qty' 
        
        FROM `packing_inhouse_size_detail2` 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        WHERE buyer_purchse_order_master.job_status_id=1 and buyer_purchse_order_master.og_id!=4 and packing_inhouse_size_detail2.pki_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
        // dd(DB::getQueryLog());    

        $tFGStock = $tFGStockData[0]->packing_grn_qty - $tFGStockData[0]->carton_pack_qty -  $tFGStockData[0]->transfer_qty;
        $mFGStock = $mFGStockData[0]->packing_grn_qty - $mFGStockData[0]->carton_pack_qty -  $mFGStockData[0]->transfer_qty;
        $yFGStock = $yFGStockData[0]->packing_grn_qty - $yFGStockData[0]->carton_pack_qty -  $yFGStockData[0]->transfer_qty;
       
        // $tProduceValue = $tvalue-($tFGStock - $todayData[0]->balance_qty)/100000;
        // $mProduceValue = $mvalue-($mFGStock - $monthData[0]->balance_qty)/100000;
        // $yProduceValue = $yvalue-($yFGStock - $yearData[0]->balance_qty)/100000;
        
        
        // echo $tFGStock.'  '.$mFGStock.'   '.$yFGStock;
        // exit;
         
        $tProduceValue = $tvalue - $tshippedQty   ;
        $mProduceValue = $mvalue- $mshippedQty  ;
        $yProduceValue = $yvalue- $yshippedQty ;
        
        
        
        
        
        // $tProduceMinValue = (($todayData[0]->sam) * ($tvalue-($todayData[0]->balance_qty - $tFGStock))/100000);
        // $mProduceMinValue = (($monthData[0]->sam) * ($mvalue-($monthData[0]->balance_qty - $mFGStock))/100000);
        
        //($monthData[0]->balance_value - $mFGStock)/100000;
        
        
        
        // $yProduceMinValue = (($yearData[0]->sam) * ($yvalue-($yearData[0]->balance_qty - $mFGStock))/100000);
        
      //  ($yearData[0]->balance_value - $yFGStock)/100000;
        
        
        if($tProduceValue == '-0')
        {
            $tProduceValue = 0;
        }
        if($mProduceValue == '-0')
        {
            $mProduceValue = 0;
        }
        if($yProduceValue == '-0')
        {
            $yProduceValue = 0;
        }
        
        $html .='<tr>
                <td> Total Open Orders Pcs </td>
                <td>'.money_format('%!.0n', round($tvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td> Total Open Orders Min </td>
                <td>'.money_format('%!.0n', round($tMinValue,2)).'</td>
                <td>'.money_format('%!.0n', round($mMinVvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yMinVvalue,2)).'</td>
              </tr>
              

              
              
              
              <tr>
                <td> Balance To Ship Pcs </td>
                <td>'.money_format('%!.0n', round($tBalValue/100000,2)).'</td>
                <td>'.money_format('%!.0n', round($mBalValue/100000,2)).'</td>
                <td>'.money_format('%!.0n', round($yBalValue/100000,2)).'</td>
              </tr>
              <tr>
                <td> Balance To Ship Min </td>
                <td>'.money_format('%!.0n', round($tBalShipMinValue/100000,2)).'</td>
                <td>'.money_format('%!.0n', round($mBalShipMinValue/100000,2)).'</td>
                <td>'.money_format('%!.0n', round($yBalShipMinValue/100000,2)).'</td>
              </tr>
              
              
             
              
              
              <tr>
                <td> Balance To Produce Pcs </td>
                <td>'.money_format('%!.0n', round($tBalToProduceQty ? $tBalToProduceQty/100000 : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($mBalToProduceQty ? $mBalToProduceQty/100000 : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($yBalToProduceQty ? $yBalToProduceQty/100000 : 0,2)).'</td>
              </tr>
              <tr>
                <td> Balance To Produce Min </td>
                <td>'.money_format('%!.0n', round($tBalToProduceMins ? $tBalToProduceMins/100000 : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($mBalToProduceMins ? $mBalToProduceMins/100000 : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($yBalToProduceMins ? $yBalToProduceMins/100000 : 0,2)).'</td>
              </tr>';
                
             
             
//                           $tBalValue
// $tBalShipMinValue
// $mBalValue
// $mBalShipMinValue
// $yBalShipMinValue
// $yBalValue
             
             
             
            $openOrdersArr = array(
                array('Total Open Orders Pcs','',round($tvalue,2),round($mvalue,2),round($yvalue,2),8,""),
                array('Total Open Orders Min','',round($tMinValue,2),round($mMinVvalue,2),round($yMinVvalue,2),8,""),
                array('Balance To Ship Pcs','',round($tBalValue/100000,2),round($mBalValue/100000,2),round($yBalValue/100000,2),8,""),
                array('Balance To Ship Min','',round($tBalShipMinValue/100000,2),round($mBalShipMinValue/100000,2),round($yBalShipMinValue/100000,2),8,""),
                array('Balance To Produce Pcs','',round($tBalToProduceQty/100000,2),round($mBalToProduceQty/100000,2),round($yBalToProduceQty/100000,2),8,""),
                array('Balance To Produce Min','',round($tBalToProduceMins/100000,2),round($mBalToProduceMins/100000,2),round($yBalToProduceMins/100000,2),8,"")
            );
            
           
            $this->tempInsertData($openOrdersArr);
            
        return response()->json(['html' => $html]);
    }
    
    // public function inventoryStatusMDDashboard()
    // {
    //     setlocale(LC_MONETARY, 'en_IN');  
    //   $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
    //     DB::table('temp_order_sales_dashboard')->where('table_head', 10)->delete();
    //     $html = '';
      
        
    //         $today_Qty = 0;
    //         $today_Value = 0;
           
    //         $TOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date < '".date('Y-m-d')."') -
             
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStock");
            
    //         $TOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter) * inward_details.item_rate)),0)  
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date < '".date('Y-m-d')."') -
             
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStockValue");
            
            
    //         $TodayIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, 
    //         ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date = '".date('Y-m-d')."'");
            
    //         $TodayOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
            
    //         $today_Qty=$TOpeningStock[0]->OpeningStock + $TodayIn[0]->Inward - $TodayOut[0]->Outward;
    //         $today_Value=$TOpeningStockValue[0]->OpeningStockValue + $TodayIn[0]->InwardValue - $TodayOut[0]->OutwardValue;
            
    //      /**********************************************************/
       
    //      $MOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStock");
            
    //         $MOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStockValue");
            
            
    //         $MonthIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
    //         $TMonthOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
    //         $month_Qty=$MOpeningStock[0]->OpeningStock + $MonthIn[0]->Inward - $TMonthOut[0]->Outward;
    //         $month_Value=$MOpeningStockValue[0]->OpeningStockValue + $MonthIn[0]->InwardValue - $TMonthOut[0]->OutwardValue;
       
    //         /********************************************************************************/ 
            
            
    //      $year_Qty = 0;
    //      $year_Value = 0;
        
    //      $YOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStock");
            
            
    //       //  dd(DB::getQueryLog()); 
    //         $YOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStockValue");
            
            
    //         $YearIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward,
    //         ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
    //         from inward_details
    //         left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
    //         $YearInOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
    //         $year_Qty=$YOpeningStock[0]->OpeningStock + $YearIn[0]->Inward - $YearInOut[0]->Outward;
    //         $year_Value=$YOpeningStockValue[0]->OpeningStockValue + $YearIn[0]->InwardValue - $YearInOut[0]->OutwardValue;
        
    //     /**********************************************************************************/
    //       $today_non_Qty = 0;
    //       $today_non_Value = 0;
       
    //         $TnonOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  inward_details.in_date < '".date('Y-m-d')."') 
          
    //           +
          
    //         (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date < '".date('Y-m-d')."') 
            
    //         -
             
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')-
            
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStock");
            
    //         $TnonOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  inward_details.in_date < '".date('Y-m-d')."')
            
    //           +
            
    //         (select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date < '".date('Y-m-d')."') 
            
    //         -
             
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')
            
    //          -
             
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //          where inward_master.is_opening=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')
            
    //         )  as OpeningStockValue");
            
            
    //         $TodaynonIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  inward_details.in_date = '".date('Y-m-d')."'");
            
    //         $TodaynonInOp=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inwardop, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValueop
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where  inward_master.is_opening=1 and  inward_details.in_date = '".date('Y-m-d')."'");
            
    //          $TodaynonOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, 
    //         ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
            
            
    //          $TodaynonOutop=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
    //          ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where  inward_master.is_opening=1 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
                
    //         $today_non_Qty=$TnonOpeningStock[0]->OpeningStock +  $TodaynonInOp[0]->Inwardop + $TodaynonIn[0]->Inward - $TodaynonOut[0]->Outward- $TodaynonOutop[0]->Outwardop;
    //         $today_non_Value=$TnonOpeningStockValue[0]->OpeningStockValue + $TodaynonInOp[0]->InwardValueop + $TodaynonIn[0]->InwardValue - $TodaynonOut[0]->OutwardValue- $TodaynonOutop[0]->OutwardValueop;
            
    //         /***********************************************/
          
    //         $MnonOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))  
              
    //             +
    //         (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) 
    //          -
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
    //         -
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
               
    //         )  as OpeningStock");
            
    //         $MnonOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
    //         +
    //         (select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            
            
    //         -
             
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
    //         -
            
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and    fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
               
    //         )  as OpeningStockValue");
            
            
    //         $MonthnonIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
    //         $MonthnonInOp=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inwardop, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValueop
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
    //         $TMonthnonOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, 
    //         ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status= 2 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
    //         $TMonthnonOutOp=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
    //         ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"); 
            
    //         $month_non_Qty=$MnonOpeningStock[0]->OpeningStock + $MonthnonIn[0]->Inward + $MonthnonInOp[0]->Inwardop - $TMonthnonOut[0]->Outward-$TMonthnonOutOp[0]->Outwardop;
    //         $month_non_Value=$MnonOpeningStockValue[0]->OpeningStockValue + $MonthnonIn[0]->InwardValue + $MonthnonInOp[0]->InwardValueop - $TMonthnonOut[0]->OutwardValue - $TMonthnonOutOp[0]->OutwardValueop;
            
    //             /*********************************************************/
    //       $year_non_Qty = 0;
    //       $year_non_Value = 0;
        
    //       $YnonOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
            
    //         +
    //         (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
            
                    
    //         -
             
    //         (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
    //         -
            
    //          (select ifnull(sum(fabric_outward_details.meter),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            
    //         )  as OpeningStock");
            
            
        
    //         $YnonOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
           
    //         +
            
    //         (select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
              
    //           -
             
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
    //         -
            
    //         (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                
    //         )  as OpeningStockValue");
            
            
    //         $YearnonIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, 
    //         ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=2 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
    //         $YearnonInOp=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inwardop,
    //         ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValueop
    //         from inward_details
    //          left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
    //         $YearnonInOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         inner join purchase_order on purchase_order.pur_code=inward_master.po_code
    //         where purchase_order.po_status=2 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
    //          $YearnonInOutOp=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
    //          ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
    //         from fabric_outward_details
    //         inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
    //         inner join   inward_master on inward_master.in_code=inward_details.in_code
    //         where inward_master.is_opening=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
    //         $year_non_Qty=$YnonOpeningStock[0]->OpeningStock + $YearnonIn[0]->Inward + $YearnonInOp[0]->Inwardop - $YearnonInOut[0]->Outward - $YearnonInOutOp[0]->Outwardop;
    //         $year_non_Value=$YnonOpeningStockValue[0]->OpeningStockValue + $YearnonIn[0]->InwardValue + $YearnonInOp[0]->InwardValueop - $YearnonInOut[0]->OutwardValue - $YearnonInOutOp[0]->OutwardValueop;
             
     
     
     
     
    //  ///*****************************************************************************
     
     
    //                     // $TrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                            
                            
    //                     //     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                     //     where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and 
    //                     //     trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                     //     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code)
                            
    //                     //     as out_qty  
    //                     //     from trimsInwardDetail
    //                     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //                     //     where  purchase_order.po_status=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
    //                     //     group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     //     ");
                            
    //                      $TrimsInwardDetailsIn = DB::select("select  sum(trimsInwardDetail.item_qty) as item_qty  ,
    //                      trimsInwardDetail.item_rate   
    //                      from trimsInwardDetail
    //                      inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //                      LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //                      where item_master.cat_id != 4 AND purchase_order.po_status=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
    //                      group by  trimsInwardDetail.po_code,trimsInwardDetail.item_code");
    //                               ;
    //                      // left join trimsInwardDetail on trimsInwardDetail.item_code=trimsOutwardDetail.item_code and  trimsInwardDetail.po_code= trimsOutwardDetail.po_code
    //                      $TrimsInwardDetailsOut = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty,
    //                      trimsOutwardDetail.item_rate   from trimsOutwardDetail 
    //                      LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //                      inner JOIN purchase_order ON purchase_order.pur_code =trimsOutwardDetail.po_code
    //                      where item_master.cat_id != 4 AND trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and 
    //                      purchase_order.po_status=1  group by  trimsOutwardDetail.po_code, trimsOutwardDetail.item_code");  
                        
    //                      $today_Trims_Value=0; 
                            
    //                     $today_Trims_In_Value=0;                        
    //                     foreach($TrimsInwardDetailsIn as $row)  
    //                     {
    //                       $today_Trims_In_Value=$today_Trims_In_Value + round(($row->item_qty ) * $row->item_rate);
    //                     } 
                            
                             
    //                     $today_Trims_Out_Value=0;                        
    //                     foreach($TrimsInwardDetailsOut as $row)  
    //                     {
    //                       $today_Trims_Out_Value=$today_Trims_Out_Value + round(($row->item_qty ) * $row->item_rate);
    //                     }
     
    //                         $today_Trims_Value= $today_Trims_In_Value - $today_Trims_Out_Value ;
     
     
     
    //                     //     $TrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                     //     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                     //     where trimsOutwardDetail.tout_date < '".date('Y-m-d')."' and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                     //     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
    //                     //     from trimsInwardDetail
    //                     //     where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
    //                     //     group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     //     ");
     
     
                                              
    //                     // foreach($TrimsInwardDetails2 as $row)  
    //                     // {
    //                     //   $today_Trims_Value=$today_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                     // }
     
     
     
    //     // $TrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //     //  from trimsInwardDetail
    //     //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //     //  WHERE purchase_order.po_status = 1 and
    //     //  trimsInwardDetail.trimDate < '".date('Y-m-d')."')
                 
    //     //     -
    //     //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //     //     where   purchase_order.po_status = 1  
    //     //     and trimsOutwardDetail.tout_date < '".date('Y-m-d')."'))  as OpeningStockValue
    //     //   ");
        
        
        
    //     //--------------------------------------------
        
    //     //     $TrimsInwardToday = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate     
    //     //     from trimsInwardDetail
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //     //     WHERE purchase_order.po_status = 1 and
    //     //     trimsInwardDetail.trimDate <= '".date('Y-m-d')."' group by trimsInwardDetail.item_code");
         
    //     //      $tinval=0;
    //     //      foreach($TrimsInwardToday as $ttt)
    //     //      {
    //     //          $tinval = $tinval + ($ttt->item_rate * $ttt->item_qty);
    //     //      }
         
         
        
    //     //     $TrimsOutwardToday = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty , trimsOutwardDetail.item_rate   
    //     //     from trimsOutwardDetail 
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //     //     where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
    //     //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' group by trimsOutwardDetail.item_code");
        
    //     // $toutval=0;
    //     //  foreach($TrimsOutwardToday as $ttto)
    //     //  {
    //     //      $toutval = $toutval + ($ttto->item_rate * $ttto->item_qty);
    //     //  }
         
         
         
    //     //     $TrimsInwardTodayOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate     
    //     //     from trimsInwardDetail
    //     //     WHERE  trimsInwardDetail.is_opening = 1 and
    //     //     trimsInwardDetail.trimDate <= '".date('Y-m-d')."' group by trimsInwardDetail.item_code");
    //     //     $tinvalop=0;
    //     //     foreach($TrimsInwardTodayOp as $ttt)
    //     //     {
    //     //       $tinvalop = $tinvalop + ($ttt->item_rate * $ttt->item_qty);
    //     //     }
         
    //     //     $TrimsOutwardTodayOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty , trimsOutwardDetail.item_rate   
    //     //     from trimsOutwardDetail 
    //     //     inner JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code
    //     //     where  trimsInwardDetail.is_opening=1
    //     //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' group by trimsOutwardDetail.item_code");
         
    //     //     $toutvalop=0;
    //     //     foreach($TrimsOutwardTodayOp as $ttto)
    //     //     {
    //     //      $toutval = $toutvalop + ($ttto->item_rate * $ttto->item_qty);
    //     //     }
        
    //     //     $today_Trims_Value=  $tinvalop +  $tinval - $toutval -$toutvalop;
        
        
    //     //--------------------------------------------------------------
        
    //         // $TrimsInwardToday = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //         // from trimsInwardDetail
    //         // left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //         // left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
    //         // WHERE trimsInwardMaster.is_opening = 1 
    //         // trimsInwardDetail.trimDate = '".date('Y-m-d')."'");      
  
         
    //     //$today_Trims_Value = $TrimsOpeningStock[0]->OpeningStockValue + ($TrimsInwardToday[0]->TodaysInValue) - $TrimsOutwardToday[0]->TodaysOutValue;
        
        
    //     // $MTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //     //  from trimsInwardDetail
    //     //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //     //  WHERE purchase_order.po_status = 1 and
    //     //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
    //     //     -
    //     //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //     //     where purchase_order.po_status = 1  
    //     //     and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') ))  as MOpeningStockValue
    //     //   ");
        
        
    //                 $MTrimsInward = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                 (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                 LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //                 where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                 and trimsOutwardDetail.item_code=trimsInwardDetail.item_code AND item_master.cat_id != 4) as out_qty  
    //                 from trimsInwardDetail
    //                 inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //                 LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //                 where item_master.cat_id != 4 AND purchase_order.po_status=1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     ");
    
    //                 $month_Trims_Value=0;                        
    //                 foreach($MTrimsInward as $row)  
    //                 {
    //                   $month_Trims_Value=$month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                 }
      
     
    //                 // $MTrimsInward2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                 // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                 // where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                 // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
    //                 // from trimsInwardDetail
    //                 // where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                 // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                 // ");
     
     
                                              
    //                 // foreach($TrimsInwardDetails2 as $row)  
    //                 // {
    //                 //   $month_Trims_Value=$month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                 // }
     
         
        
        
        
    //         // $MTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate 
    //         // from trimsInwardDetail
    //         // inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //         // WHERE purchase_order.po_status = 1 and
    //         // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.item_code");
         
    //         //  $tinval=0;
    //         //  foreach($MTrimsInward as $ttt)
    //         //  {
    //         //      $tinval = $tinval + ($ttt->item_rate * $ttt->item_qty);
    //         //  }
         
         
         
         
    //         // $MTrimsInwardop = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate 
    //         // from trimsInwardDetail
    //         // WHERE trimsInwardDetail.is_opening = 1 and
    //         // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.item_code");
         
    //         // $tinvalop=0;
    //         //  foreach($MTrimsInwardop as $ttt)
    //         //  {
    //         //      $tinvalop = $tinvalop + ($ttt->item_rate * $ttt->item_qty);
    //         //  }
         
         
         
         
        
    //         // $MTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty   ,trimsOutwardDetail.item_rate 
    //         // from trimsOutwardDetail 
    //         // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //         // where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
    //         // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsOutwardDetail.item_code");
        
    //         // $toutval=0;
    //         //  foreach($MTrimsOutward as $ttt)
    //         //  {
    //         //      $toutval = $toutval + ($ttt->item_rate * $ttt->item_qty);
    //         //  }
        
        
        
    //         // $MTrimsOutwardop = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty   ,trimsOutwardDetail.item_rate 
    //         // from trimsOutwardDetail 
    //         // inner JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code
    //         // where  trimsInwardDetail.is_opening = 1
    //         // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsOutwardDetail.item_code");
    //         //  $toutvalop=0;
    //         //  foreach($MTrimsOutwardop as $ttt)
    //         //  {
    //         //      $toutvalop = $toutvalop + ($ttt->item_rate * $ttt->item_qty);
    //         //  }
         
        
          
         
           
         
    //         //   $month_Trims_Value  =$tinvalop + $tinval - $toutval -$toutvalop;
    //         //$month_Trims_Value = ($MTrimsOpeningStock[0]->MOpeningStockValue + ($MTrimsInward[0]->MonthInValue - $MTrimsOutward[0]->MonthOutValue))  ;
       
      
    //         // $YTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //         //  from trimsInwardDetail
    //         //  inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //         //  WHERE purchase_order.po_status = 1 and
    //         //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
    //         // -
    //         // (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //         // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //         // where   purchase_order.po_status = 1  
    //         // and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') ))  as YOpeningStockValue
    //         // ");
            
            
            
    //                 $YTrimsInward = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                 (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                 LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //                 where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                 and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                 and trimsOutwardDetail.item_code=trimsInwardDetail.item_code AND item_master.cat_id != 4) as out_qty  
    //                 from trimsInwardDetail
    //                 inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //                 LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //                 where item_master.cat_id != 4 AND purchase_order.po_status=1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     ");
    
    //                 $year_Trims_Value=0;                        
    //                 foreach($YTrimsInward as $row)  
    //                 {
    //                   $year_Trims_Value=$year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                 }
      
     
    //                 // $YTrimsInward2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                 // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                 // where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                 // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
    //                 // from trimsInwardDetail
    //                 // where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                 // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                 // ");
     
     
                                              
    //                 // foreach($YTrimsInward2 as $row)  
    //                 // {
    //                 //   $year_Trims_Value=$year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                 // }
            
            
            
            
            
            
            
            
            
            
        
    // //         $YTrimsOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as  item_qty, trimsInwardDetail.item_rate 
    // //         from trimsInwardDetail 
    // //          WHERE trimsInwardDetail.is_opening = 1 and
    // //         trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.item_code");
          
    // //         $YTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty,   trimsInwardDetail.item_rate 
    // //         from trimsInwardDetail
    // //         inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    // //         WHERE purchase_order.po_status = 1 and
    // //         trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.po_code, trimsInwardDetail.item_code");
         
        
    // //         $YTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty, trimsOutwardDetail.item_rate 
    // //         from trimsOutwardDetail 
    // //         inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    // //         where   purchase_order.po_status = 1  
    // //         and trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsOutwardDetail.item_code");
      
      
    // //         $YTrimsOutwardop = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty , trimsOutwardDetail.item_rate 
    // //         from trimsOutwardDetail 
    // //         inner JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code
    // //         where    trimsInwardDetail.is_opening = 1
    // //         and trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.po_code,trimsOutwardDetail.item_code");
    // //     //$year_Trims_Value = ($YTrimsOpeningStock[0]->YOpeningStockValue + ($YTrimsInward[0]->YearInValue - $YTrimsOutward[0]->YearOutValue))  ;
         
        
    // //      $tinvalop=0;
    // //      foreach($YTrimsOp as $ttt)
    // //      {
    // //          $tinvalop = $tinvalop + ($ttt->item_rate * $ttt->item_qty);
    // //      }
        
        
        
    // //      $tinval=0;
    // //      foreach($YTrimsInward as $ttt)
    // //      {
    // //          $tinval = $tinval + ($ttt->item_rate * $ttt->item_qty);
    // //      }
         
         
    // //       $toutval=0;
    // //      foreach($YTrimsOutward as $ttt)
    // //      {
    // //          $toutval = $toutval + ($ttt->item_rate * $ttt->item_qty);
    // //      }
        
    // //      $toutvalop=0;
    // //      foreach($YTrimsOutwardop as $ttt)
    // //      {
    // //          $toutvalop = $toutvalop + ($ttt->item_rate * $ttt->item_qty);
    // //      }
         
    // //   $year_Trims_Value =$tinvalop +  $tinval - $toutval - $toutvalop;
    //     //*************************************************************
   
    //     //  $NonTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //     //  from trimsInwardDetail
    //     //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //     //  WHERE purchase_order.po_status = 1 and
    //     //  trimsInwardDetail.trimDate < '".date('Y-m-d')."')
       
    //     //  +
         
    //     //  (select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //     //  from trimsInwardDetail
    //     //  WHERE trimsInwardDetail.is_opening = 1 and
    //     //  trimsInwardDetail.trimDate < '".date('Y-m-d')."')
                 
    //     //     -
    //     //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //     //     where   purchase_order.po_status = 1  
    //     //     and trimsOutwardDetail.tout_date < '".date('Y-m-d')."')
    //     //     -
            
    //     //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //     //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
    //     //     where   trimsInwardMaster.is_opening = 1   
    //     //     and trimsOutwardDetail.tout_date < '".date('Y-m-d')."')
    //      //           )  as OpeningStockValue
    //     //   ");
        
        
        
        
        
        
        
        
    //             // $NonTrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //             // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //             // where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //             // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
    //             // from trimsInwardDetail
    //             // inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //             // where purchase_order.po_status!=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
    //             // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //             // ");

    //             // $non_today_Trims_Value=0;                        
    //             // foreach($NonTrimsInwardDetails as $row)  
    //             // {
    //             //   $non_today_Trims_Value=$non_today_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //             // }


    //             // $NonTrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //             // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //             // where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //             // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
    //             // from trimsInwardDetail
    //             // where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
    //             // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //             // ");

                       
    //             // foreach($NonTrimsInwardDetails2 as $row)  
    //             // {
    //             //   $non_today_Trims_Value=$non_today_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //             // }


    //             $non_today_Trims_Value=0; 
    //             $NonTrimsInwardDetailsIn = DB::select("select  sum(trimsInwardDetail.item_qty) as item_qty  ,
    //             trimsInwardDetail.item_rate   
    //             from trimsInwardDetail
    //             inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //             LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //             where  item_master.cat_id != 4 AND purchase_order.po_status!=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
    //             group by  trimsInwardDetail.po_code,trimsInwardDetail.item_code");
                        
    //             $non_today_Trims_In_Value=0;                        
    //             foreach($NonTrimsInwardDetailsIn as $row)  
    //             {
    //               $non_today_Trims_In_Value=$non_today_Trims_In_Value + round(($row->item_qty ) * $row->item_rate);
    //             } 
                  
    //             $NonTrimsInwardDetailsInOpeing = DB::select("select  sum(trimsInwardDetail.item_qty) as item_qty  ,
    //             trimsInwardDetail.item_rate   
    //             from trimsInwardDetail
    //             LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //             where  item_master.cat_id != 4 AND trimsInwardDetail.is_opening=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
    //             group by  trimsInwardDetail.po_code,trimsInwardDetail.item_code");
                  
    //             $non_today_Trims_OpeningIN_Value=0;                        
    //             foreach($NonTrimsInwardDetailsInOpeing as $row)  
    //             {
    //               $non_today_Trims_OpeningIN_Value=$non_today_Trims_OpeningIN_Value + round(($row->item_qty ) * $row->item_rate);
    //             }
                
    //             $non_today_Trims_ValueTotalIN=$non_today_Trims_In_Value + $non_today_Trims_OpeningIN_Value;
                
    //           // echo 'Close PO In Value'.$non_today_Trims_In_Value . ' & Opening Stock In Value'. $non_today_Trims_OpeningIN_Value;
                
    //             //   left join trimsInwardDetail on trimsInwardDetail.item_code=trimsOutwardDetail.item_code and  trimsInwardDetail.po_code= trimsOutwardDetail.po_code
    //             $NonTrimsInwardDetailsOut = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty,
    //             trimsOutwardDetail.item_rate   from trimsOutwardDetail 
    //             inner JOIN purchase_order ON purchase_order.pur_code =trimsOutwardDetail.po_code
    //             LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //             where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and 
    //             purchase_order.po_status!=1  AND item_master.cat_id != 4 group by  trimsOutwardDetail.po_code, trimsOutwardDetail.item_code");  
                
    //             $non_today_Trims_Out_Value=0;                        
    //             foreach($NonTrimsInwardDetailsOut as $row)  
    //             {
    //               $non_today_Trims_Out_Value=$non_today_Trims_Out_Value + round(($row->item_qty ) * $row->item_rate);
    //             }

    //             $NonTrimsInwardDetailsOutOpening = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty,
    //             trimsInwardDetail.item_rate   from trimsOutwardDetail 
    //             left JOIN trimsInwardDetail ON trimsInwardDetail.po_code =trimsOutwardDetail.po_code and trimsInwardDetail.item_code =trimsOutwardDetail.item_code 
    //             LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //             and trimsInwardDetail.item_code = trimsOutwardDetail.item_code
    //             where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and trimsInwardDetail.is_opening=1 AND item_master.cat_id != 4
    //             group by  trimsOutwardDetail.po_code, trimsOutwardDetail.item_code");  
                
    //             $non_today_Trims_Out_ValueOpening=0;                        
    //             foreach($NonTrimsInwardDetailsOutOpening as $row)  
    //             {
    //               $non_today_Trims_Out_ValueOpening=$non_today_Trims_Out_ValueOpening + round(($row->item_qty ) * $row->item_rate);
    //             }

    //           // echo 'Close PO Out Value: '.$non_today_Trims_Out_Value . ' & Opening Stock Out Value: '. $non_today_Trims_Out_ValueOpening;
                
    //             $non_today_Trims_ValueTotalOut= $non_today_Trims_Out_Value + $non_today_Trims_Out_ValueOpening;
                
    //             // echo 'Total IN Value: '.$non_today_Trims_ValueTotalIN.' & Total Out Value: '.$non_today_Trims_ValueTotalOut;
                

    //             $non_today_Trims_Value= $non_today_Trims_ValueTotalIN - $non_today_Trims_ValueTotalOut ;

    //             // echo   'Total non Moving Value:'.$non_today_Trims_Value;  
               
    //           // exit;
        
        
        
        
        
        
        
        
        
        
    //     //     $NonTrimsInwardToday = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as TodaysInValue
    //     //     from trimsInwardDetail
    //     //     left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //     //     WHERE purchase_order.po_status = 1 and
    //     //     trimsInwardDetail.trimDate  <= '".date('Y-m-d')."' group by trimsInwardDetail.po_code,trimsInwardDetail.item_code");
             
             
    //     //     $NonTrimsInwardTodayOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as TodaysInValue
    //     //     from trimsInwardDetail
    //     //     WHERE trimsInwardDetail.is_opening = 1 and
    //     //     trimsInwardDetail.trimDate  <= '".date('Y-m-d')."' group by trimsInwardDetail.item_code ");
          
    //     //     $NonTrimsOutwardToday = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as TodaysOutValue
    //     //     from trimsOutwardDetail 
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //     //     where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
    //     //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."'");
        
    //     //     $NonTrimsOutwardTodayOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as TodaysOutValue
    //     //     from trimsOutwardDetail 
    //     //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
    //     //     where   trimsInwardMaster.is_opening = 1 
    //     //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."'");
        
           
    //     // $non_today_Trims_Value =  ($NonTrimsInwardToday[0]->TodaysInValue) + ($NonTrimsInwardTodayOp[0]->TodaysInValue) - $NonTrimsOutwardToday[0]->TodaysOutValue - $NonTrimsOutwardTodayOp[0]->TodaysOutValue;
        
        
        
        
        
    //                     $NonMTrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                     LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //                     where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code  AND item_master.cat_id != 4) as out_qty  
    //                     from trimsInwardDetail
    //                     inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //                     LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //                     where purchase_order.po_status!=1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                     AND item_master.cat_id != 4 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     ");
        
    //                     $non_month_Trims_Value=0;                        
    //                     foreach($NonMTrimsInwardDetails as $row)  
    //                     {
    //                       $non_month_Trims_Value=$non_month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                     }
     
      
    //                     $NonMTrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                     LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //                     where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code AND item_master.cat_id != 4) as out_qty  
    //                     from trimsInwardDetail
    //                     LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //                     where trimsInwardDetail.is_opening =1 AND item_master.cat_id != 4 and trimsInwardDetail.trimDate <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                     group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     ");
     
                               
    //                     foreach($NonMTrimsInwardDetails2 as $row)  
    //                     {
    //                       $non_month_Trims_Value=$non_month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                     }
        
        
    //     // $NonMTrimsOpeningStock =  DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //     //  from trimsInwardDetail
    //     //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //     //  WHERE purchase_order.po_status = 1 and
    //     //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
       
    //     //  +
         
    //     //  (select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //     //  from trimsInwardDetail
    //     //  WHERE trimsInwardDetail.is_opening = 1 and
    //     //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
    //     //     -
    //     //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //     //     where   purchase_order.po_status = 1  
    //     //     and trimsOutwardDetail.tout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
    //     //     -
            
    //     //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //     //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
    //     //     where   trimsInwardMaster.is_opening = 1   
    //     //     and trimsOutwardDetail.tout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
    //     //           )  as OpeningStockValue
    //     //   ");
        
        
    //     //     $NonMTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as MonthInValue
    //     //     from trimsInwardDetail
    //     //     left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //     //     WHERE purchase_order.po_status = 1 and
    //     //     trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
         
    //     //     $NonMTrimsInwardOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as MonthInValue
    //     //     from trimsInwardDetail
    //     //      WHERE trimsInwardDetail.is_opening = 1 and
    //     //     trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
    //     //     $NonTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as MonthOutValue
    //     //     from trimsOutwardDetail 
    //     //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //     //     where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
    //     //     and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
    //     //     $NonTrimsOutwardOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as MonthOutValue
    //     //     from trimsOutwardDetail 
    //     //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
    //     //     where   trimsInwardMaster.is_opening = 1 
    //     //     and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
        
              
    //     //     $non_month_Trims_Value = ($NonMTrimsOpeningStock[0]->OpeningStockValue  + $NonMTrimsInward[0]->MonthInValue + $NonMTrimsInwardOp[0]->MonthInValue - $NonTrimsOutward[0]->MonthOutValue - $NonTrimsOutwardOp[0]->MonthOutValue);
      
      
      
      
      
    //                     $NonYTrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                     LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //                     where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code  AND item_master.cat_id != 4) as out_qty  
    //                     from trimsInwardDetail
    //                     inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //                     LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //                     where purchase_order.po_status!=1  AND item_master.cat_id != 4 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                     group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     ");
        
    //                     $non_year_Trims_Value=0;                        
    //                     foreach($NonYTrimsInwardDetails as $row)  
    //                     {
    //                       $non_year_Trims_Value=$non_year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                     }
     
      
    //                     $NonYTrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
    //                     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //                     LEFT JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code
    //                     where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
    //                     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code AND item_master.cat_id != 4) as out_qty  
    //                     from trimsInwardDetail
    //                     LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //                     where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //                     AND item_master.cat_id != 4 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
    //                     ");
     
                               
    //                     foreach($NonYTrimsInwardDetails2 as $row)  
    //                     {
    //                       $non_year_Trims_Value=$non_year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
    //                     }
      
      
      
    //         // $NonYTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //         //  from trimsInwardDetail
    //         //  inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //         //  WHERE purchase_order.po_status = 1 and
    //         //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
    //         //      +
                 
    //         //      (select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
    //         //  from trimsInwardDetail
    //         //  WHERE  trimsInwardDetail.is_opening = 1  and
    //         //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
                  
    //         // -
    //         // (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //         // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //         // where   purchase_order.po_status = 1  
    //         // and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') )
            
    //         // -
            
    //         //  (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
    //         // inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
    //         // where   trimsInwardMaster.is_opening = 1 
    //         // and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') )
            
            
    //         // )  as YOpeningStockValue
    //         // ");
        
         
        
    //         // $NonYTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as YearInValue
    //         // from trimsInwardDetail
    //         // left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //         // WHERE purchase_order.po_status = 1 and
    //         // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
    //         // $NonYTrimsInwardOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as YearInValue
    //         // from trimsInwardDetail
    //         //  WHERE trimsInwardDetail.is_opening = 1 and
    //         // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
       
    //         //   $NonYTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as YearOutValue
    //         // from trimsOutwardDetail 
    //         // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //         // where   purchase_order.po_status = 1  
    //         // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
      
      
      
    //         // $NonYTrimsOutwardOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as YearOutValue
    //         // from trimsOutwardDetail 
    //         // inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
    //         // where   trimsInwardMaster.is_opening = 1     
    //         // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
        
            
    //         // $non_year_Trims_Value = ($NonYTrimsOpeningStock[0]->YOpeningStockValue + ($NonYTrimsInward[0]->YearInValue + $NonYTrimsInwardOp[0]->YearInValue - $NonYTrimsOutward[0]->YearOutValue - $NonYTrimsOutwardOp[0]->YearOutValue))  ;
        
        
    //      //****************************************************************************
         
         
   
        
    //     $today_WIP_value = 0;
    //     $month_WIP_value = 0;
    //     $year_WIP_value = 0;
        
    //     $todayFGStock = 0;
    //     $todayFGValue = 0;
        
    //     $TodayFinishedGoodsStock = DB::select("SELECT FG.entry_date,ifnull(sum(FG.`size_qty`),0)  as packing_qty ,sales_order_costing_master.total_cost_value,  ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
    //             where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
    //             ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
    //             and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
    //             inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
    //               left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
    //             where FG.data_type_id=1 AND buyer_purchse_order_master.job_status_id = 1 AND FG.entry_date <= '".date('Y-m-d')."'
    //             group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
                    
    //     foreach($TodayFinishedGoodsStock as $row)
    //     {
    //          if($row->total_cost_value == 0)
    //             {
    //                 $fob_rate =  round($row->order_rate,4);
    //             }
    //             else
    //             {
    //                 $fob_rate = round($row->total_cost_value,4);
    //             }
    //         $todayFGStock = $todayFGStock + ($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    //         $todayFGValue = $todayFGValue + ((($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($fob_rate))/100000);
    //     }
           
    //     $monthFGStock = 0;
    //     $monthFGValue = 0;
        
    //     $MonthFinishedGoodsStock = DB::select("SELECT FG.entry_date,ifnull(sum(FG.`size_qty`),0)  as packing_qty , sales_order_costing_master.total_cost_value, ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
    //             where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
    //             ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
    //             and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
    //             inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
    //              left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
    //             where FG.data_type_id=1 AND buyer_purchse_order_master.job_status_id = 1 AND FG.entry_date <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //             group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
        
    //     foreach($MonthFinishedGoodsStock as $row)
    //     {
    //         if($row->total_cost_value == 0)
    //             {
    //                 $fob_rate =  round($row->order_rate,4);
    //             }
    //             else
    //             {
    //                 $fob_rate = round($row->total_cost_value,4);
    //             }
    //         $monthFGStock = $monthFGStock + ($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    //         $monthFGValue = $monthFGValue + ((($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($fob_rate))/100000);
    //     }
        
        
        
      
        
        
        
    //     $yearFGStock = 0;
    //     $yearFGValue = 0;
        
    //     $YearFinishedGoodsStock = DB::select("SELECT FG.entry_date,ifnull(sum(FG.`size_qty`),0)  as packing_qty ,sales_order_costing_master.total_cost_value, ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
    //             where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
    //             ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
    //             and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
    //             inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
    //             left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
    //             where FG.data_type_id=1 AND buyer_purchse_order_master.job_status_id = 1 AND FG.entry_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
    //             group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");

        
    //     foreach($YearFinishedGoodsStock as $row)
    //     {
    //         if($row->total_cost_value == 0)
    //             {
    //                 $fob_rate =  round($row->order_rate,4);
    //             }
    //             else
    //             {
    //                 $fob_rate = round($row->total_cost_value,4);
    //             }
    //         $yearFGStock = $yearFGStock + ($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    //         $yearFGValue = $yearFGValue + ((($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($fob_rate))/100000);
    //     }
        
        
    //     $todayNonFGStock = 0;
    //     $todayNonFGValue = 0;
        
    //     $TodayNonFinishedGoodsStock = DB::select("SELECT FG.entry_date,ifnull(sum(FG.`size_qty`),0)  as packing_qty ,sales_order_costing_master.total_cost_value, ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
    //             where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
    //             ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
    //             and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
    //             inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
    //              left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
    //             where FG.data_type_id=1 AND buyer_purchse_order_master.job_status_id!=1 AND FG.entry_date <= '".date('Y-m-d')."'
    //             group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
    //     $monthNonFGStock = 0;
    //     $monthNonFGValue = 0;
        
    //     $MonthNonFinishedGoodsStock = DB::select("SELECT FG.entry_date,ifnull(sum(FG.`size_qty`),0)  as packing_qty , sales_order_costing_master.total_cost_value,ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
    //             where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
    //             ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
    //             and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
    //             inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
    //              left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
    //             where FG.data_type_id=1 AND buyer_purchse_order_master.job_status_id!=1 AND FG.entry_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
    //             group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
    //     $yearNonFGStock = 0;
    //     $yearNonFGValue = 0;
        
    //     $YearNonFinishedGoodsStock = DB::select("SELECT FG.entry_date,ifnull(sum(FG.`size_qty`),0)  as packing_qty ,sales_order_costing_master.total_cost_value, ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
    //             where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
    //             ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
    //             and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
    //             inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
    //              left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
    //             where FG.data_type_id=1 AND buyer_purchse_order_master.job_status_id !=1 AND FG.entry_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
    //             group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
             
            
    //     foreach($TodayNonFinishedGoodsStock as $row)
    //     {
    //         if($row->total_cost_value == 0)
    //             {
    //                 $fob_rate =  round($row->order_rate,4);
    //             }
    //             else
    //             {
    //                 $fob_rate = round($row->total_cost_value,4);
    //             }
    //         $todayNonFGStock = $todayNonFGStock + ($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    //         $todayNonFGValue =  $todayNonFGValue + ((($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($fob_rate))/100000);
    //     }
        
    //     foreach($MonthNonFinishedGoodsStock as $row)
    //     {
    //         if($row->total_cost_value == 0)
    //             {
    //                 $fob_rate =  round($row->order_rate,4);
    //             }
    //             else
    //             {
    //                 $fob_rate = round($row->total_cost_value,4);
    //             }
    //         $monthNonFGStock = $monthNonFGStock + ($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    //         $monthNonFGValue =  $monthNonFGValue + ((($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($fob_rate))/100000);
    //     }
        
    //     foreach($YearNonFinishedGoodsStock as $row)
    //     {
    //         if($row->total_cost_value == 0)
    //             {
    //                 $fob_rate =  round($row->order_rate,4);
    //             }
    //             else
    //             {
    //                 $fob_rate = round($row->total_cost_value,4);
    //             }
    //         $yearNonFGStock = $yearNonFGStock + ($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    //         $yearNonFGValue = $yearNonFGValue + ((($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($fob_rate))/100000);
    //     }
    
    //     $inventoryStatusArr = array(
        
    //         array('Fabric - Moving Quantity','Mtr',round($today_Qty/100000,2),round($month_Qty/100000,2),round($year_Qty/100000,2),10,""),
    //         array('Fabric - Moving Value','Rs',round($today_Value/100000,2),round($month_Value/100000,2),round($year_Value/100000,2),10,""),
    //         array('Fabric - Non - Moving Quantity','Mtr',round($today_non_Qty/100000,2),round($month_non_Qty/100000,2),round($year_non_Qty/100000,2),10,""),
    //         array('Fabric - Non - Moving Value','Rs',round($today_non_Value/100000,2),round($month_non_Value/100000,2),round($year_non_Value/100000,2),10,""),
    //         array('Trims - Moving Value','Rs',round($today_Trims_Value/100000,2),round($month_Trims_Value/100000,2),round($year_Trims_Value/100000,2),10,""),
    //         array('Trims - Non - Moving Value','Rs',round($non_today_Trims_Value/100000,2),round($non_month_Trims_Value/100000,2),round($non_year_Trims_Value/100000,2),10,""),
           
    //         array('FG - Moving Quantity','Pcs',round($todayFGStock/100000,2),round($monthFGStock/100000,2),round($yearFGStock/100000,2),10,""),
    //         array('FG - Moving Value','Rs',round($todayFGValue,2),round($monthFGValue,2),round($yearFGValue,2),10,""),
    //         array('FG - Non - Moving Quantity','Pcs',round($todayNonFGStock/100000,2),round($monthNonFGStock/100000,2),round($yearNonFGStock/100000,2),10,""),
    //         array('FG - Non - Moving Value','Rs',round($todayNonFGValue,2),round($monthNonFGValue,2),round($yearNonFGValue,2),10,"")

    //     );     
            
    //     $this->tempInsertData($inventoryStatusArr);   
    //     return response()->json(['html' => $html]);
    // } 
    
    
    public function inventoryStatusMDDashboard()
    {
        // $html = '';
      
        
        //     $today_Qty = 0;
        //     $today_Value = 0;
           
        //     $TOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date < '".date('Y-m-d')."') -
             
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStock");
            
        //     $TOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter) * inward_details.item_rate)),0)  
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date < '".date('Y-m-d')."') -
             
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStockValue");
            
            
        //     $TodayIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, 
        //     ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date = '".date('Y-m-d')."'");
            
        //     $TodayOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
            
        //     $today_Qty=$TOpeningStock[0]->OpeningStock + $TodayIn[0]->Inward - $TodayOut[0]->Outward;
        //     $today_Value=$TOpeningStockValue[0]->OpeningStockValue + $TodayIn[0]->InwardValue - $TodayOut[0]->OutwardValue;
            
        //  /**********************************************************/
       
        //  $MOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStock");
            
        //     $MOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStockValue");
            
            
        //     $MonthIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
        //     $TMonthOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
        //     $month_Qty=$MOpeningStock[0]->OpeningStock + $MonthIn[0]->Inward - $TMonthOut[0]->Outward;
        //     $month_Value=$MOpeningStockValue[0]->OpeningStockValue + $MonthIn[0]->InwardValue - $TMonthOut[0]->OutwardValue;
       
        //     /********************************************************************************/ 
            
            
        //  $year_Qty = 0;
        //  $year_Value = 0;
        
        //  $YOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStock");
            
            
        //   //  dd(DB::getQueryLog()); 
        //     $YOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStockValue");
            
            
        //     $YearIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward,
        //     ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
        //     from inward_details
        //     left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
        //     $YearInOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
        //     $year_Qty=$YOpeningStock[0]->OpeningStock + $YearIn[0]->Inward - $YearInOut[0]->Outward;
        //     $year_Value=$YOpeningStockValue[0]->OpeningStockValue + $YearIn[0]->InwardValue - $YearInOut[0]->OutwardValue;
        
        // /**********************************************************************************/
        //   $today_non_Qty = 0;
        //   $today_non_Value = 0;
       
        //     $TnonOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  inward_details.in_date < '".date('Y-m-d')."') 
          
        //       +
          
        //     (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date < '".date('Y-m-d')."') 
            
        //     -
             
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')-
            
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStock");
            
        //     $TnonOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  inward_details.in_date < '".date('Y-m-d')."')
            
        //       +
            
        //     (select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date < '".date('Y-m-d')."') 
            
        //     -
             
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')
            
        //      -
             
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //      where inward_master.is_opening=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')
            
        //     )  as OpeningStockValue");
            
            
        //     $TodaynonIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  inward_details.in_date = '".date('Y-m-d')."'");
            
        //     $TodaynonInOp=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inwardop, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValueop
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where  inward_master.is_opening=1 and  inward_details.in_date = '".date('Y-m-d')."'");
            
        //      $TodaynonOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, 
        //     ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
            
            
        //      $TodaynonOutop=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
        //      ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where  inward_master.is_opening=1 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
                
        //     $today_non_Qty=$TnonOpeningStock[0]->OpeningStock +  $TodaynonInOp[0]->Inwardop + $TodaynonIn[0]->Inward - $TodaynonOut[0]->Outward- $TodaynonOutop[0]->Outwardop;
        //     $today_non_Value=$TnonOpeningStockValue[0]->OpeningStockValue + $TodaynonInOp[0]->InwardValueop + $TodaynonIn[0]->InwardValue - $TodaynonOut[0]->OutwardValue- $TodaynonOutop[0]->OutwardValueop;
            
        //     /***********************************************/
          
        //     $MnonOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))  
              
        //         +
        //     (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) 
        //      -
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
        //     -
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
               
        //     )  as OpeningStock");
            
        //     $MnonOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
        //     +
        //     (select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            
            
        //     -
             
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
        //     -
            
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and    fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
               
        //     )  as OpeningStockValue");
            
            
        //     $MonthnonIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
        //     $MonthnonInOp=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inwardop, ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValueop
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
        //     $TMonthnonOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, 
        //     ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status= 2 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
        //     $TMonthnonOutOp=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
        //     ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"); 
            
        //     $month_non_Qty=$MnonOpeningStock[0]->OpeningStock + $MonthnonIn[0]->Inward + $MonthnonInOp[0]->Inwardop - $TMonthnonOut[0]->Outward-$TMonthnonOutOp[0]->Outwardop;
        //     $month_non_Value=$MnonOpeningStockValue[0]->OpeningStockValue + $MonthnonIn[0]->InwardValue + $MonthnonInOp[0]->InwardValueop - $TMonthnonOut[0]->OutwardValue - $TMonthnonOutOp[0]->OutwardValueop;
            
        //         /*********************************************************/
        //   $year_non_Qty = 0;
        //   $year_non_Value = 0;
        
        //   $YnonOpeningStock=DB::select("select ( (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
            
        //     +
        //     (select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
            
                    
        //     -
             
        //     (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
        //     -
            
        //      (select ifnull(sum(fabric_outward_details.meter),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            
        //     )  as OpeningStock");
            
            
        
        //     $YnonOpeningStockValue=DB::select("select ((select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
           
        //     +
            
        //     (select ifnull((sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate)),0)  
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
              
        //       -
             
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
        //     -
            
        //     (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                
        //     )  as OpeningStockValue");
            
            
        //     $YearnonIn=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inward, 
        //     ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValue
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=2 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
        //     $YearnonInOp=DB::select("select ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)),0)  as Inwardop,
        //     ifnull(sum(ifnull(fabric_checking_details.meter,inward_details.meter)*inward_details.item_rate),0) as InwardValueop
        //     from inward_details
        //      left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
        //     $YearnonInOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     inner join purchase_order on purchase_order.pur_code=inward_master.po_code
        //     where purchase_order.po_status=2 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
        //      $YearnonInOutOp=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
        //      ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
        //     from fabric_outward_details
        //     inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
        //     inner join   inward_master on inward_master.in_code=inward_details.in_code
        //     where inward_master.is_opening=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
        //     $year_non_Qty=$YnonOpeningStock[0]->OpeningStock + $YearnonIn[0]->Inward + $YearnonInOp[0]->Inwardop - $YearnonInOut[0]->Outward - $YearnonInOutOp[0]->Outwardop;
        //     $year_non_Value=$YnonOpeningStockValue[0]->OpeningStockValue + $YearnonIn[0]->InwardValue + $YearnonInOp[0]->InwardValueop - $YearnonInOut[0]->OutwardValue - $YearnonInOutOp[0]->OutwardValueop;
             
     
     
      
            
            setlocale(LC_MONETARY, 'en_IN');  
            $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
         DB::table('temp_order_sales_dashboard')->where('table_head', 10)->delete(); 
     
            
        $tempTblData = DB::SELECT("SELECT *  FROM temp_order_sales_dashboard");
        if(count($tempTblData) <=70)
        {
            $html='';
            $currentDate = date('Y-m-d'); 
            
            $monthDateData = DB::select("Select DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') as month_date");
            $monthCurDate = isset($monthDateData[0]->month_date) ? $monthDateData[0]->month_date : "";
            
            $yearDateData = DB::select("Select DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') as year_date");
            $yearCurDate = isset($yearDateData[0]->year_date) ? $yearDateData[0]->year_date : "";

        //DB::enableQueryLog();
        $FabricTodayMoving =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.job_status_id = 1 AND df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.job_status_id = 1 AND df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$currentDate."') as oq 
                                FROM dump_fabric_stock_data WHERE job_status_id = 1 AND in_date <='".$currentDate."'");
        //dd(DB::getQueryLog());
      
            $todayFabricMovingQty = 0;
            $todayFabricMoving = 0; 
            foreach($FabricTodayMoving as $row7)
            {
                $grn_qty7 = isset($row7->gq) ? $row7->gq : 0; 
                $ind_outward17 = (explode(",",$row7->ind_outward_qty));
                $q_qty7 = 0; 
                
               
                foreach($ind_outward17 as $indu7)
                {
                    
                     $ind_outward7 = (explode("=>",$indu7));
                     $q_qty77 = isset($ind_outward7[1]) ? $ind_outward7[1] : 0;
                     if($ind_outward7[0] <= $currentDate)
                     {
                         $q_qty7 = $q_qty7 + $q_qty77;
                     }
                     else
                     {
                          $q_qty7 =  0;
                     }
                }
                // echo '<pre>';print_r($ind_outward1);exit;
                if($row7->qc_qty > 0 )
                {
                    $stocks7 =  $row7->qc_qty- $q_qty7;
                } 
                else
                {
                     $stocks7 =  $row7->gq - $q_qty7;
                }
         
                $todayFabricMovingQty +=  $stocks7;
    
                $todayFabricMoving += ($stocks7) * $row7->rate;  
            }
        
                           
            $FabricTodayNonMoving =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.job_status_id != 1 AND df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.job_status_id != 1 AND df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$currentDate."' ) as oq 
                                FROM dump_fabric_stock_data WHERE job_status_id != 1 AND in_date <='".$currentDate."'");
            $todayFabricNonMoving = 0;        
            $todayFabricNonMovingQty = 0;
            
            foreach($FabricTodayNonMoving as $row8)
            {
                $grn_qty8 = isset($row8->gq) ? $row8->gq : 0; 
                $ind_outward18 = (explode(",",$row8->ind_outward_qty));
                $q_qty8 = 0; 
                
               
                foreach($ind_outward18 as $indu8)
                {
                    
                     $ind_outward8 = (explode("=>",$indu8));
                     $q_qty88 = isset($ind_outward8[1]) ? $ind_outward8[1] : 0;
                     if($ind_outward8[0] <= $currentDate)
                     {
                         $q_qty8 = $q_qty8 + $q_qty88;
                     }
                     else
                     {
                          $q_qty8 =  0;
                     }
                }
                // echo '<pre>';print_r($ind_outward1);exit;
                if($row8->qc_qty > 0 )
                {
                    $stocks8 =  $row8->qc_qty- $q_qty8;
                } 
                else
                {
                     $stocks8 =  $row8->gq - $q_qty8;
                }
         
                $todayFabricNonMoving += ($stocks8) * $row8->rate;  
                $todayFabricNonMovingQty +=  $stocks8;
    
            }
            
            $FabricMonthMoving =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.job_status_id = 1 AND df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$monthCurDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.job_status_id = 1 AND df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$monthCurDate."' ) as oq 
                                FROM dump_fabric_stock_data WHERE job_status_id = 1 AND in_date <='".$monthCurDate."'");
            $monthFabricMovingQty = 0;        
            $monthFabricMoving = 0;
            
            foreach($FabricMonthMoving as $row9)
            {
                $grn_qty89 = isset($row9->gq) ? $row9->gq : 0; 
                $ind_outward19 = (explode(",",$row9->ind_outward_qty));
                $q_qty9 = 0; 
                
               
                foreach($ind_outward19 as $indu9)
                {
                    
                     $ind_outward9 = (explode("=>",$indu9));
                     $q_qty99 = isset($ind_outward9[1]) ? $ind_outward9[1] : 0;
                     if($ind_outward9[0] <= $monthCurDate)
                     {
                         $q_qty9 = $q_qty9 + $q_qty99;
                     }
                     else
                     {
                          $q_qty9 =  0;
                     }
                } 
                
                if($row9->qc_qty > 0 )
                {
                    $stocks9 =  $row9->qc_qty- $q_qty9;
                } 
                else
                {
                     $stocks9 =  $row9->gq - $q_qty9;
                }
         
                $monthFabricMovingQty += $stocks9;  
                $monthFabricMoving +=  ($stocks9) * $row9->rate;
    
            }
           
            $FabricMonthNonMoving =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.job_status_id != 1 AND df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$monthCurDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.job_status_id != 1 AND df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$monthCurDate."' ) as oq 
                                FROM dump_fabric_stock_data WHERE job_status_id != 1 AND in_date <='".$monthCurDate."'");
            $monthFabricNonMovingQty = 0;        
            $monthFabricNonMoving = 0;
            
            foreach($FabricMonthNonMoving as $row10)
            {
                $grn_qty10 = isset($row10->gq) ? $row10->gq : 0; 
                $ind_outward100 = (explode(",",$row10->ind_outward_qty));
                $q_qty10 = 0; 
                
               
                foreach($ind_outward100 as $indu10)
                {
                    
                     $ind_outward10 = (explode("=>",$indu10));
                     $q_qty100 = isset($ind_outward10[1]) ? $ind_outward10[1] : 0;
                     if($ind_outward10[0] <= $monthCurDate)
                     {
                         $q_qty10 = $q_qty10 + $q_qty100;
                     }
                     else
                     {
                          $q_qty10 =  0;
                     }
                } 
                
                if($row10->qc_qty > 0 )
                {
                    $stocks10 =  $row10->qc_qty- $q_qty10;
                } 
                else
                {
                     $stocks10 =  $row10->gq - $q_qty10;
                }
         
                $monthFabricNonMovingQty += $stocks10;  
                $monthFabricNonMoving +=  ($stocks10) * $row10->rate;
    
            }
          
            $FabricYearMoving =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.job_status_id = 1 AND df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$yearCurDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.job_status_id = 1 AND df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$yearCurDate."' ) as oq 
                                FROM dump_fabric_stock_data WHERE job_status_id = 1 AND in_date <='".$yearCurDate."'");
            $yearFabricMovingQty = 0;        
            $yearFabricMoving = 0;
            
            foreach($FabricYearMoving as $row11)
            {
                $grn_qty11 = isset($row11->gq) ? $row11->gq : 0; 
                $ind_outward111 = (explode(",",$row11->ind_outward_qty));
                $q_qty11 = 0; 
                
               
                foreach($ind_outward111 as $indu11)
                {
                    
                     $ind_outward11 = (explode("=>",$indu11));
                     $q_qty111 = isset($ind_outward11[1]) ? $ind_outward11[1] : 0;
                     if($ind_outward11[0] <= $yearCurDate)
                     {
                         $q_qty11 = $q_qty11 + $q_qty111;
                     }
                     else
                     {
                          $q_qty11 =  0;
                     }
                } 
                
                if($row11->qc_qty > 0 )
                {
                    $stocks11 =  $row11->qc_qty- $q_qty11;
                } 
                else
                {
                     $stocks11 =  $row11->gq - $q_qty11;
                }
         
                $yearFabricMovingQty += $stocks11;  
                $yearFabricMoving +=  ($stocks11) * $row11->rate;
    
            }
      
            
            $FabricYearNonMoving =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df 
                                WHERE df.job_status_id != 1 AND df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$yearCurDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.job_status_id != 1 
                                AND df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$yearCurDate."' ) as oq 
                                FROM dump_fabric_stock_data WHERE job_status_id != 1 AND in_date <='".$yearCurDate."'");
            
            $yearFabricNonMoving = 0;        
            $yearFabricNonMovingQty = 0;
            
            foreach($FabricYearNonMoving as $row12)
            {
                $grn_qty12 = isset($row12->gq) ? $row12->gq : 0; 
                $ind_outward122 = (explode(",",$row12->ind_outward_qty));
                $q_qty12 = 0; 
                
               
                foreach($ind_outward122 as $indu12)
                {
                     $ind_outward12 = (explode("=>",$indu12));
                     $q_qty122 = isset($ind_outward12[1]) ? $ind_outward12[1] : 0;
                     if($ind_outward12[0] <= $yearCurDate)
                     {
                         $q_qty12 = $q_qty12 + $q_qty122;
                     }
                     else
                     {
                          $q_qty12 =  0;
                     }
                } 
                
                if($row12->qc_qty > 0 )
                {
                    $stocks12 =  $row12->qc_qty- $q_qty12;
                } 
                else
                {
                     $stocks12 =  $row12->gq - $q_qty12;
                }
         
                $yearFabricNonMoving +=  ($stocks12) * $row12->rate;  
                $yearFabricNonMovingQty += $stocks12;
    
            }
            
            $TrimTodayMoving =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data WHERE job_status_id = 1 AND trimDate <= '".$currentDate."' GROUP BY po_no,item_code");     
                            
            $todayTirmsMoving = 0;
            
            foreach($TrimTodayMoving as $row)
            {
                $q_qty = 0;   
                $ind_outward1 = (explode(",",$row->ind_outward_qty));
                
             
                foreach($ind_outward1 as $indu)
                {
                    
                     $ind_outward2 = (explode("=>",$indu));
                      
                     if($ind_outward2[0] <= $currentDate)
                     {
                        $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                        $q_qty = $q_qty + $ind_out;
                       
                     }
                } 
              
                $stocks =  $row->gq - $q_qty; 
                $todayTirmsMoving += ($stocks * $row->rate);
            }
            
                        
            $TrimTodayNonMoving =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data  WHERE job_status_id != 1 AND trimDate <= '".$currentDate."' GROUP BY po_no,item_code");     
                            
            $todayTirmsNonMoving = 0;
            
            foreach($TrimTodayNonMoving as $row)
            {
                $q_qty = 0;   
                $ind_outward1 = (explode(",",$row->ind_outward_qty));
                
             
                foreach($ind_outward1 as $indu)
                {
                    
                     $ind_outward2 = (explode("=>",$indu));
                      
                     if($ind_outward2[0] <= $currentDate)
                     {
                        $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                        $q_qty = $q_qty + $ind_out;
                       
                     }
                } 
              
                $stocks =  $row->gq - $q_qty; 
                $todayTirmsNonMoving += ($stocks * $row->rate);
            }
            
            
             
            $TrimMonthMoving =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data WHERE trimDate <= '".$monthCurDate."' GROUP BY po_no,item_code");     
                            
            $monthTirmsMoving = 0;
            
            foreach($TrimMonthMoving as $row3)
            {
                $q_qty3 = 0;   
                $ind_outward13 = (explode(",",$row3->ind_outward_qty));
                
             
                foreach($ind_outward13 as $indu3)
                {
                    
                     $ind_outward3 = (explode("=>",$indu3));
                      
                     if($ind_outward3[0] <= $monthCurDate)
                     {
                        $ind_out3 = isset($ind_outward3[1]) ? $ind_outward3[1] : 0; 
                        $q_qty3 = $q_qty3 + $ind_out3;
                       
                     }
                } 
              
                $stocks3 =  $row3->gq - $q_qty3; 
                $monthTirmsMoving += ($stocks3 * $row3->rate);
            }
            
                        
            $TrimMonthNonMoving =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data  WHERE job_status_id != 1 AND trimDate <= '".$monthCurDate."' GROUP BY po_no,item_code");     
                            
            $monthTirmsNonMoving = 0;
            
            foreach($TrimMonthNonMoving as $row4)
            {
                $q_qty4 = 0;   
                $ind_outward14 = (explode(",",$row4->ind_outward_qty));
                
             
                foreach($ind_outward14 as $indu4)
                {
                    
                     $ind_outward4 = (explode("=>",$indu4));
                      
                     if($ind_outward4[0] <= $monthCurDate)
                     {
                        $ind_out4 = isset($ind_outward4[1]) ? $ind_outward4[1] : 0; 
                        $q_qty4 = $q_qty4 + $ind_out4;
                       
                     }
                } 
              
                $stocks4 =  $row4->gq - $q_qty4; 
                $monthTirmsNonMoving += ($stocks4 * $row4->rate);
            }
            
            
            $TrimYearMoving =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data WHERE job_status_id = 1 AND trimDate <= '".$yearCurDate."' GROUP BY po_no,item_code");     
                            
            $yearTirmsMoving = 0;
            
            foreach($TrimYearMoving as $row5)
            {
                $q_qty5 = 0;   
                $ind_outward15 = (explode(",",$row5->ind_outward_qty));
                
             
                foreach($ind_outward15 as $indu5)
                {
                    
                     $ind_outward5 = (explode("=>",$indu5));
                      
                     if($ind_outward5[0] <= $yearCurDate)
                     {
                        $ind_out5 = isset($ind_outward5[1]) ? $ind_outward5[1] : 0; 
                        $q_qty5 = $q_qty5 + $ind_out5;
                       
                     }
                } 
              
                $stocks5 =  $row5->gq - $q_qty5; 
                $yearTirmsMoving += ($stocks5 * $row5->rate);
            }
            
                        
            $TrimYearNonMoving =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data  WHERE job_status_id != 1 AND trimDate <= '".$yearCurDate."' GROUP BY po_no,item_code");     
                            
            $yearTirmsNonMoving = 0;
           
            
            foreach($TrimYearNonMoving as $row6)
            {
                $q_qty6 = 0;   
                $ind_outward16 = (explode(",",$row6->ind_outward_qty));
                 
                foreach($ind_outward16 as $indu6)
                {
                    
                     $ind_outward6 = (explode("=>",$indu6));
                      
                     if($ind_outward6[0] <= $yearCurDate)
                     {
                        $ind_out6 = isset($ind_outward6[1]) ? $ind_outward6[1] : 0; 
                        $q_qty6 = $q_qty6 + $ind_out6;
                       
                     }
                } 
              
                $stocks6 =  $row6->gq - $q_qty6; 
                $yearTirmsNonMoving += ($stocks6 * $row6->rate);
            }
             
            // DB::enableQueryLog();
            $FinishedGoodsStock = DB::table('FGStockDataByTwo as FG')
                             ->select("FG.code","FG.data_type_id","FG.ac_name","FG.sales_order_no","FG.mainstyle_name","FG.color_name","FG.size_name","FG.color_id","FG.size_id",'job_status_master.job_status_id',
                                "sales_order_costing_master.total_cost_value","buyer_purchse_order_master.order_rate","brand_master.brand_name","job_status_master.job_status_name","buyer_purchse_order_master.sam")
                             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                             ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                             ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
                             ->leftjoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'FG.sales_order_no') 
                             ->whereIn('FG.data_type_id',[1,2,3])
                             ->groupBy('sales_order_no','color_id', 'size_id')
                             ->orderBy('FG.entry_date','asc') 
                             ->get(); 
                             
             //dd(DB::getQueryLog());                 
          
            $total_moving_stockT = 0; 
            $total_moving_valueT = 0; 
            $total_non_moving_stockT = 0;
            $total_non_moving_valueT =0;
             
            $total_moving_stockM = 0; 
            $total_moving_valueM = 0; 
            $total_non_moving_stockM = 0;
            $total_non_moving_valueM =0;
                    
            $total_moving_stockY = 0; 
            $total_moving_valueY = 0; 
            $total_non_moving_stockY = 0;
            $total_non_moving_valueY =0;
            
            foreach($FinishedGoodsStock as $row)
            { 
                    $TpackingMovingData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no') 
                         ->where('FG.data_type_id','=',1)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)  
                         ->where('buyer_purchse_order_master.job_status_id','=',1)   
                         ->groupBy('FG.size_id') 
                         ->get();
                        
                    $TcartonMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',2)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',$currentDate)  
                                 ->where('buyer_purchse_order_master.job_status_id','=',1)    
                                 ->groupBy('FG.size_id')  
                                 ->get();
                                 
                    $TtramsferMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',3)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',$currentDate)   
                                 ->where('buyer_purchse_order_master.job_status_id','=',1)   
                                 ->groupBy('FG.size_id')  
                                 ->get();
                                 
                    $TpackingNonMovingData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no') 
                         ->where('FG.data_type_id','=',1)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)  
                         ->where('buyer_purchse_order_master.job_status_id','!=',1)   
                         ->groupBy('FG.size_id') 
                         ->get();
                        
                    $TcartonNonMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',2)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',$currentDate)  
                                 ->where('buyer_purchse_order_master.job_status_id','!=',1)    
                                 ->groupBy('FG.size_id')  
                                 ->get();
                                 
                    $TtramsferNonMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',3)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',$currentDate)   
                                 ->where('buyer_purchse_order_master.job_status_id','!=',1)   
                                 ->groupBy('FG.size_id')  
                                 ->get();  
                                 
                $TpackingMoving_qty = isset($TpackingMovingData[0]->size_qty) ? $TpackingMovingData[0]->size_qty : 0; 
                $Tcarton_packMoving_qty = isset($TcartonMovingData[0]->size_qty) ? $TcartonMovingData[0]->size_qty : 0; 
                $TtransferMoving_qty = isset($TtramsferMovingData[0]->size_qty) ? $TtramsferMovingData[0]->size_qty : 0; 
                
                
                $TpackingNonMoving_qty = isset($TpackingNonMovingData[0]->size_qty) ? $TpackingNonMovingData[0]->size_qty : 0; 
                $Tcarton_packNonMoving_qty = isset($TcartonNonMovingData[0]->size_qty) ? $TcartonNonMovingData[0]->size_qty : 0; 
                $TtransferNonMoving_qty = isset($TtramsferNonMovingData[0]->size_qty) ? $TtramsferNonMovingData[0]->size_qty : 0; 
                
                $TstockMoving  = $TpackingMoving_qty - $Tcarton_packMoving_qty - $TtransferMoving_qty;
                $TstockNonMoving =  $TpackingNonMoving_qty - $Tcarton_packNonMoving_qty - $TtransferNonMoving_qty;
                
                if($row->total_cost_value == 0)
                {
                     $fob_rate =  number_format($row->order_rate,4);
                     $fob_rate1 =  $row->order_rate;
                }
                else
                {
                    $fob_rate = number_format($row->total_cost_value,4);
                    $fob_rate1 = $row->total_cost_value;
                }  
               
                $total_moving_stockT += $TstockMoving; 
                $total_moving_valueT += ($TstockMoving*$fob_rate1); 
                
                $total_non_moving_stockT += $TstockNonMoving; 
                $total_non_moving_valueT += ($TstockNonMoving*$fob_rate1); 
                
                
                
                /************************************************************/
                
                 
            
                    $MpackingMovingData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no') 
                         ->where('FG.data_type_id','=',1)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"))   
                         ->where('buyer_purchse_order_master.job_status_id','=',1)   
                         ->groupBy('FG.size_id') 
                         ->get();
                        
                    $McartonMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',2)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"))   
                                 ->where('buyer_purchse_order_master.job_status_id','=',1)    
                                 ->groupBy('FG.size_id')  
                                 ->get();
                                 
                    $MtramsferMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',3)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"))    
                                 ->where('buyer_purchse_order_master.job_status_id','=',1)   
                                 ->groupBy('FG.size_id')  
                                 ->get();
                                 
                    $MpackingNonMovingData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no') 
                         ->where('FG.data_type_id','=',1)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"))   
                         ->where('buyer_purchse_order_master.job_status_id','!=',1)   
                         ->groupBy('FG.size_id') 
                         ->get();
                        
                    $McartonNonMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',2)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"))   
                                 ->where('buyer_purchse_order_master.job_status_id','!=',1)    
                                 ->groupBy('FG.size_id')  
                                 ->get();
                                 
                    $MtramsferNonMovingData = DB::table('FGStockDataByTwo as FG')
                                 ->select(DB::raw("sum(size_qty) as size_qty"))
                                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                                 ->where('FG.data_type_id','=',3)
                                 ->where('FG.sales_order_no','=',$row->sales_order_no)
                                 ->where('FG.size_id','=',$row->size_id)
                                 ->where('FG.color_id','=',$row->color_id)
                                 ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"))   
                                 ->where('buyer_purchse_order_master.job_status_id','!=',1)   
                                 ->groupBy('FG.size_id')  
                                 ->get();  
                                 
                $MpackingMoving_qty = isset($MpackingMovingData[0]->size_qty) ? $MpackingMovingData[0]->size_qty : 0; 
                $Mcarton_packMoving_qty = isset($McartonMovingData[0]->size_qty) ? $McartonMovingData[0]->size_qty : 0; 
                $MtransferMoving_qty = isset($MtramsferMovingData[0]->size_qty) ? $MtramsferMovingData[0]->size_qty : 0; 
                
                
                $MpackingNonMoving_qty = isset($MpackingNonMovingData[0]->size_qty) ? $MpackingNonMovingData[0]->size_qty : 0; 
                $Mcarton_packNonMoving_qty = isset($McartonNonMovingData[0]->size_qty) ? $McartonNonMovingData[0]->size_qty : 0; 
                $MtransferNonMoving_qty = isset($MtramsferNonMovingData[0]->size_qty) ? $MtramsferNonMovingData[0]->size_qty : 0; 
                
                $MstockMoving  = $MpackingMoving_qty - $Mcarton_packMoving_qty - $MtransferMoving_qty;
                $MstockNonMoving =  $MpackingNonMoving_qty - $Mcarton_packNonMoving_qty - $MtransferNonMoving_qty;
                
                if($row->total_cost_value == 0)
                {
                     $fob_rate =  number_format($row->order_rate,4);
                     $fob_rate1 =  $row->order_rate;
                }
                else
                {
                    $fob_rate = number_format($row->total_cost_value,4);
                    $fob_rate1 = $row->total_cost_value;
                }  
               
                $total_moving_stockM += $MstockMoving; 
                $total_moving_valueM += ($MstockMoving*$fob_rate1); 
                
                $total_non_moving_stockM += $MstockNonMoving; 
                $total_non_moving_valueM += ($MstockNonMoving*$fob_rate1); 
                
                
             /***************************************************/   
                 
        
                $YpackingMovingData = DB::table('FGStockDataByTwo as FG')
                     ->select(DB::raw("sum(size_qty) as size_qty"))
                     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no') 
                     ->where('FG.data_type_id','=',1)
                     ->where('FG.sales_order_no','=',$row->sales_order_no)
                     ->where('FG.size_id','=',$row->size_id)
                     ->where('FG.color_id','=',$row->color_id)
                     ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')"))  
                     ->where('buyer_purchse_order_master.job_status_id','=',1)   
                     ->groupBy('FG.size_id') 
                     ->get();
                    
                $YcartonMovingData = DB::table('FGStockDataByTwo as FG')
                             ->select(DB::raw("sum(size_qty) as size_qty"))
                             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                             ->where('FG.data_type_id','=',2)
                             ->where('FG.sales_order_no','=',$row->sales_order_no)
                             ->where('FG.size_id','=',$row->size_id)
                             ->where('FG.color_id','=',$row->color_id)
                             ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')"))  
                             ->where('buyer_purchse_order_master.job_status_id','=',1)    
                             ->groupBy('FG.size_id')  
                             ->get();
                             
                $YtramsferMovingData = DB::table('FGStockDataByTwo as FG')
                             ->select(DB::raw("sum(size_qty) as size_qty"))
                             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                             ->where('FG.data_type_id','=',3)
                             ->where('FG.sales_order_no','=',$row->sales_order_no)
                             ->where('FG.size_id','=',$row->size_id)
                             ->where('FG.color_id','=',$row->color_id)
                             ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')"))   
                             ->where('buyer_purchse_order_master.job_status_id','=',1)   
                             ->groupBy('FG.size_id')  
                             ->get();
                             
                $YpackingNonMovingData = DB::table('FGStockDataByTwo as FG')
                     ->select(DB::raw("sum(size_qty) as size_qty"))
                     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no') 
                     ->where('FG.data_type_id','=',1)
                     ->where('FG.sales_order_no','=',$row->sales_order_no)
                     ->where('FG.size_id','=',$row->size_id)
                     ->where('FG.color_id','=',$row->color_id)
                     ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')"))  
                     ->where('buyer_purchse_order_master.job_status_id','!=',1)   
                     ->groupBy('FG.size_id') 
                     ->get();
                    
                $YcartonNonMovingData = DB::table('FGStockDataByTwo as FG')
                             ->select(DB::raw("sum(size_qty) as size_qty"))
                             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                             ->where('FG.data_type_id','=',2)
                             ->where('FG.sales_order_no','=',$row->sales_order_no)
                             ->where('FG.size_id','=',$row->size_id)
                             ->where('FG.color_id','=',$row->color_id)
                             ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')"))  
                             ->where('buyer_purchse_order_master.job_status_id','!=',1)    
                             ->groupBy('FG.size_id')  
                             ->get();
                             
                $YtramsferNonMovingData = DB::table('FGStockDataByTwo as FG')
                             ->select(DB::raw("sum(size_qty) as size_qty"))
                             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                             ->where('FG.data_type_id','=',3)
                             ->where('FG.sales_order_no','=',$row->sales_order_no)
                             ->where('FG.size_id','=',$row->size_id)
                             ->where('FG.color_id','=',$row->color_id)
                             ->where('FG.entry_date','<=',DB::raw("DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')"))   
                             ->where('buyer_purchse_order_master.job_status_id','!=',1)   
                             ->groupBy('FG.size_id')  
                             ->get();  
                                 
                $YpackingMoving_qty = isset($YpackingMovingData[0]->size_qty) ? $YpackingMovingData[0]->size_qty : 0; 
                $Ycarton_packMoving_qty = isset($YcartonMovingData[0]->size_qty) ? $YcartonMovingData[0]->size_qty : 0; 
                $YtransferMoving_qty = isset($YtramsferMovingData[0]->size_qty) ? $YtramsferMovingData[0]->size_qty : 0; 
                
                
                $YpackingNonMoving_qty = isset($YpackingNonMovingData[0]->size_qty) ? $YpackingNonMovingData[0]->size_qty : 0; 
                $Ycarton_packNonMoving_qty = isset($YcartonNonMovingData[0]->size_qty) ? $YcartonNonMovingData[0]->size_qty : 0; 
                $YtransferNonMoving_qty = isset($YtramsferNonMovingData[0]->size_qty) ? $YtramsferNonMovingData[0]->size_qty : 0; 
                
                $YstockMoving  = $YpackingMoving_qty - $Ycarton_packMoving_qty - $YtransferMoving_qty;
                $YstockNonMoving =  $YpackingNonMoving_qty - $Ycarton_packNonMoving_qty - $YtransferNonMoving_qty;
                
                if($row->total_cost_value == 0)
                {
                     $fob_rate =  number_format($row->order_rate,4);
                     $fob_rate1 =  $row->order_rate;
                }
                else
                {
                    $fob_rate = number_format($row->total_cost_value,4);
                    $fob_rate1 = $row->total_cost_value;
                } 
               
                $total_moving_stockY += $YstockMoving; 
                $total_moving_valueY += ($YstockMoving*$fob_rate1); 
                
                $total_non_moving_stockY += $YstockNonMoving; 
                $total_non_moving_valueY += ($YstockNonMoving*$fob_rate1); 
        }  
        
 
            $inventoryStatusArr = array(
                array('Fabric - Moving Quantity','Mtr',round($todayFabricMovingQty/100000,2),round($monthFabricMovingQty/100000,2),round($yearFabricMovingQty/100000,2),10,""),
                array('Fabric - Moving Value','Rs',round($todayFabricMoving/100000,2),round($monthFabricMoving/100000,2),round($yearFabricMoving/100000,2),10,""),
                array('Fabric - Non - Moving Quantity','Mtr',round($todayFabricNonMovingQty/100000,2),round($monthFabricNonMovingQty/100000,2),round($yearFabricNonMovingQty/100000,2),10,""),
                array('Fabric - Non - Moving Value','Rs',round($todayFabricNonMoving/100000,2),round($monthFabricNonMoving/100000,2),round($yearFabricNonMoving/100000,2),10,""),
                array('Trims - Moving Value','Rs',round($todayTirmsMoving/100000,2),round($monthTirmsMoving/100000,2),round($yearTirmsMoving/100000,2),10,""),
                array('Trims - Non - Moving Value','Rs',round($todayTirmsNonMoving/100000,2),round($monthTirmsNonMoving/100000,2),round($yearTirmsNonMoving/100000,2),10,""),
                array('FG - Moving Quantity','Pcs',round($total_moving_stockT/100000,2),round($total_moving_stockM/100000,2),round($total_moving_stockY/100000,2),10,""),
                array('FG - Moving Value','Rs',round($total_moving_valueT/100000,2),round($total_moving_valueM/100000,2),round($total_moving_valueY/100000,2),10,""),
                array('FG - Non - Moving Quantity','Pcs',round($total_non_moving_stockT/100000,2),round($total_non_moving_stockM/100000,2),round($total_non_moving_stockY/100000,2),10,""),
                array('FG - Non - Moving Value','Rs',round($total_non_moving_valueT/100000,2),round($total_non_moving_valueM/100000,2),round($total_non_moving_valueY/100000,2),10,"")
    
            );     
            
            $this->tempInsertData($inventoryStatusArr);   
            
            $this->InventoryWIPValue();
        }
        
        return response()->json(['html' => $html]); exit;
    } 
 
  
    public function InventoryWIPValue()
    {
           
           // setlocale(LC_MONETARY, 'en_IN');  
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
       // DB::table('temp_order_sales_dashboard')->where('table_head', 10)->delete();
           
        $currentDate = date('Y-m-d'); 
        
        $monthDateData = DB::select("Select DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') as month_date");
        $monthCurDate = isset($monthDateData[0]->month_date) ? $monthDateData[0]->month_date : "";
        
        $yearDateData = DB::select("Select DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') as year_date");
        $yearCurDate = isset($yearDateData[0]->year_date) ? $yearDateData[0]->year_date : "";
        
        $today_total_WIP=0;
        $month_total_WIP=0;
        $year_total_WIP=0;
        
        $today_WIP_value=0;
        
        $month_WIP_value=0;
        
        $year_WIP_value=0;
        $today_total_WIP1 = 0;
        $month_total_WIP1 = 0;
        $year_total_WIP1 = 0;

        $Buyer_Purchase_Order_List = DB::select("SELECT * FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4 AND job_status_id = 1 AND order_type!=2");
      
        foreach($Buyer_Purchase_Order_List as $row)  
        {      
                $VendorDataToday = DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where sales_order_no='".$row->tr_code."' AND vw_date <='".$currentDate."'");
                
                
                $CutPanelDataToday = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <='".$currentDate."'");
                
                $cutPanelIssueQtyToday = isset($CutPanelDataToday[0]->total_qty) ? $CutPanelDataToday[0]->total_qty : 0;
                $work_order_qtyToday = isset($VendorDataToday[0]->work_order_qty) ? $VendorDataToday[0]->work_order_qty : 0;
                
                $StichingDataToday=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row->tr_code."' AND sti_date <='".$currentDate."'");
                 
                $stichingQtyToday = isset($StichingDataToday[0]->stiching_qty) ? $StichingDataToday[0]->stiching_qty : 0;
                
                $PackingDataToday = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$currentDate."'");
 
                $pack_order_qty_today = isset($PackingDataToday[0]->total_qty) ? $PackingDataToday[0]->total_qty : 0;
               
        
                $sewingToday = $cutPanelIssueQtyToday - $stichingQtyToday;
                  
                $SalesCostingDataToday = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
                  
                $fabric_value_today = isset($SalesCostingDataToday[0]->fabric_value) ? $SalesCostingDataToday[0]->fabric_value : 0;  
                $sewing_trims_value_today = isset($SalesCostingDataToday[0]->sewing_trims_value) ? $SalesCostingDataToday[0]->sewing_trims_value : 0;
                $packing_trims_value_today = isset($SalesCostingDataToday[0]->packing_trims_value) ? $SalesCostingDataToday[0]->packing_trims_value : 0;            
    
                $today_total_WIP += ($work_order_qtyToday - $cutPanelIssueQtyToday) + $sewingToday + ($stichingQtyToday - $pack_order_qty_today);
                
                
                $VendorDataMonth = DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where sales_order_no='".$row->tr_code."' AND vw_date <='".$monthCurDate."'");
                
                
                $CutPanelDataMonth = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <='".$monthCurDate."'");
                
                $cutPanelIssueQtyMonth = isset($CutPanelDataMonth[0]->total_qty) ? $CutPanelDataMonth[0]->total_qty : 0;
                $work_order_qtyMonth = isset($VendorDataMonth[0]->work_order_qty) ? $VendorDataMonth[0]->work_order_qty : 0;
                
                $StichingDataMonth=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row->tr_code."' AND sti_date <='".$monthCurDate."'");
                 
                $stichingQtyMonth = isset($StichingDataMonth[0]->stiching_qty) ? $StichingDataMonth[0]->stiching_qty : 0;
                
                $PackingDataMonth = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$monthCurDate."'");
 
                $pack_order_qty_Month = isset($PackingDataMonth[0]->total_qty) ? $PackingDataMonth[0]->total_qty : 0;
               
        
                $sewingMonth = $cutPanelIssueQtyMonth - $stichingQtyMonth;
                  
                $SalesCostingDataMonth = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
                  
                $fabric_value_Month = isset($SalesCostingDataMonth[0]->fabric_value) ? $SalesCostingDataMonth[0]->fabric_value : 0;  
                $sewing_trims_value_Month = isset($SalesCostingDataMonth[0]->sewing_trims_value) ? $SalesCostingDataMonth[0]->sewing_trims_value : 0;
                $packing_trims_value_Month = isset($SalesCostingDataMonth[0]->packing_trims_value) ? $SalesCostingDataMonth[0]->packing_trims_value : 0;            
    
                $month_total_WIP += ($work_order_qtyMonth - $cutPanelIssueQtyMonth) + $sewingMonth + ($stichingQtyMonth - $pack_order_qty_Month);
                
                
                $VendorDataYear = DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where sales_order_no='".$row->tr_code."' AND vw_date <='".$yearCurDate."'");
                
                
                $CutPanelDataYear = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <='".$yearCurDate."'");
                
                $cutPanelIssueQtyYear = isset($CutPanelDataYear[0]->total_qty) ? $CutPanelDataYear[0]->total_qty : 0;
                $work_order_qtyYear = isset($VendorDataYear[0]->work_order_qty) ? $VendorDataYear[0]->work_order_qty : 0;
                
                $StichingDataMonth=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row->tr_code."' AND sti_date <='".$yearCurDate."'");
                 
                $stichingQtyYear = isset($StichingDataYear[0]->stiching_qty) ? $StichingDataYear[0]->stiching_qty : 0;
                
                $PackingDataYear = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$yearCurDate."'");
 
                $pack_order_qty_Year = isset($PackingDataYear[0]->total_qty) ? $PackingDataYear[0]->total_qty : 0;
               
        
                $sewingYear = $cutPanelIssueQtyYear - $stichingQtyYear;
                  
                $SalesCostingDataYear = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
                  
                $fabric_value_Year = isset($SalesCostingDataYear[0]->fabric_value) ? $SalesCostingDataYear[0]->fabric_value : 0;  
                $sewing_trims_value_Year = isset($SalesCostingDataYear[0]->sewing_trims_value) ? $SalesCostingDataYear[0]->sewing_trims_value : 0;
                $packing_trims_value_Year = isset($SalesCostingDataYear[0]->packing_trims_value) ? $SalesCostingDataYear[0]->packing_trims_value : 0;            
    
                $year_total_WIP += ($work_order_qtyYear - $cutPanelIssueQtyYear) + $sewingYear + ($stichingQtyYear - $pack_order_qty_Year);
        }
        
        $Buyer_Purchase_Order_List1 = DB::select("SELECT * FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4 AND job_status_id = 1 AND order_type=1");
      
        foreach($Buyer_Purchase_Order_List1 as $row1)  
        {      
                $VendorDataToday1 = DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where sales_order_no='".$row1->tr_code."' AND vw_date <='".$currentDate."'");
                
                
                $CutPanelDataToday1 = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row1->tr_code."' AND cpg_date <='".$currentDate."'");
                
                $cutPanelIssueQtyToday1 = isset($CutPanelDataToday1[0]->total_qty) ? $CutPanelDataToday1[0]->total_qty : 0;
                $work_order_qtyToday1 = isset($VendorDataToday1[0]->work_order_qty) ? $VendorDataToday1[0]->work_order_qty : 0;
                
                $StichingDataToday1=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row1->tr_code."' AND sti_date <='".$currentDate."'");
                 
                $stichingQtyToday1 = isset($StichingDataToday1[0]->stiching_qty) ? $StichingDataToday1[0]->stiching_qty : 0;
                
                $PackingDataToday1 = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row1->tr_code."' AND pki_date <='".$currentDate."'");
 
                $pack_order_qty_today1 = isset($PackingDataToday1[0]->total_qty) ? $PackingDataToday1[0]->total_qty : 0;
               
        
                $sewingToday1 = $cutPanelIssueQtyToday1 - $stichingQtyToday1;
                  
                $SalesCostingDataToday1 = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row1->tr_code."'");
                  
                $fabric_value_today1 = isset($SalesCostingDataToday1[0]->fabric_value) ? $SalesCostingDataToday1[0]->fabric_value : 0;  
                $sewing_trims_value_today1 = isset($SalesCostingDataToday1[0]->sewing_trims_value) ? $SalesCostingDataToday1[0]->sewing_trims_value : 0;
                $packing_trims_value_today1 = isset($SalesCostingDataToday1[0]->packing_trims_value) ? $SalesCostingDataToday1[0]->packing_trims_value : 0;            
    
                $today_WIP_value += (($fabric_value_today1 +  $sewing_trims_value_today1 + $packing_trims_value_today1) * (($VendorDataToday1[0]->work_order_qty - $cutPanelIssueQtyToday1) + $sewingToday1 +($stichingQtyToday1 - $pack_order_qty_today1)));
               
                $VendorDataMonth = DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where sales_order_no='".$row1->tr_code."' AND vw_date <='".$monthCurDate."'");
                
                
                $CutPanelDataMonth = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row1->tr_code."' AND cpg_date <='".$monthCurDate."'");
                
                $cutPanelIssueQtyMonth = isset($CutPanelDataMonth[0]->total_qty) ? $CutPanelDataMonth[0]->total_qty : 0;
                $work_order_qtyMonth = isset($VendorDataMonth[0]->work_order_qty) ? $VendorDataMonth[0]->work_order_qty : 0;
                
                $StichingDataMonth=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row1->tr_code."' AND sti_date <='".$monthCurDate."'");
                 
                $stichingQtyMonth = isset($StichingDataMonth[0]->stiching_qty) ? $StichingDataMonth[0]->stiching_qty : 0;
                
                $PackingDataMonth = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row1->tr_code."' AND pki_date <='".$monthCurDate."'");
 
                $pack_order_qty_Month = isset($PackingDataMonth[0]->total_qty) ? $PackingDataMonth[0]->total_qty : 0;
               
        
                $sewingMonth = $cutPanelIssueQtyMonth - $stichingQtyMonth;
                  
                $SalesCostingDataMonth = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row1->tr_code."'");
                  
                $fabric_value_Month = isset($SalesCostingDataMonth[0]->fabric_value) ? $SalesCostingDataMonth[0]->fabric_value : 0;  
                $sewing_trims_value_Month = isset($SalesCostingDataMonth[0]->sewing_trims_value) ? $SalesCostingDataMonth[0]->sewing_trims_value : 0;
                $packing_trims_value_Month = isset($SalesCostingDataMonth[0]->packing_trims_value) ? $SalesCostingDataMonth[0]->packing_trims_value : 0;            
    
                $month_WIP_value += (($fabric_value_Month +  $sewing_trims_value_Month + $packing_trims_value_Month) * (($VendorDataMonth[0]->work_order_qty - $cutPanelIssueQtyMonth) + $sewingMonth +($stichingQtyMonth - $pack_order_qty_Month)));
                
                $VendorDataYear = DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where sales_order_no='".$row1->tr_code."' AND vw_date <='".$yearCurDate."'");
                
                
                $CutPanelDataYear = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row1->tr_code."' AND cpg_date <='".$yearCurDate."'");
                
                $cutPanelIssueQtyYear = isset($CutPanelDataYear[0]->total_qty) ? $CutPanelDataYear[0]->total_qty : 0;
                $work_order_qtyYear = isset($VendorDataYear[0]->work_order_qty) ? $VendorDataYear[0]->work_order_qty : 0;
                
                $StichingDataYear=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row1->tr_code."' AND sti_date <='".$yearCurDate."'");
                 
                $stichingQtyYear = isset($StichingDataYear[0]->stiching_qty) ? $StichingDataYear[0]->stiching_qty : 0;
                
                $PackingDataYear = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row1->tr_code."' AND pki_date <='".$yearCurDate."'");
 
                $pack_order_qty_Year = isset($PackingDataYear[0]->total_qty) ? $PackingDataYear[0]->total_qty : 0;
               
        
                $sewingYear = $cutPanelIssueQtyYear - $stichingQtyYear;
                  
                $SalesCostingDataYear = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row1->tr_code."'");
                  
                $fabric_value_Year = isset($SalesCostingDataYear[0]->fabric_value) ? $SalesCostingDataYear[0]->fabric_value : 0;  
                $sewing_trims_value_Year = isset($SalesCostingDataYear[0]->sewing_trims_value) ? $SalesCostingDataYear[0]->sewing_trims_value : 0;
                $packing_trims_value_Year = isset($SalesCostingDataYear[0]->packing_trims_value) ? $SalesCostingDataYear[0]->packing_trims_value : 0;            
    
                $year_WIP_value += (($fabric_value_Year +  $sewing_trims_value_Year + $packing_trims_value_Year) *  (($VendorDataYear[0]->work_order_qty - $cutPanelIssueQtyYear) + $sewingYear +($stichingQtyYear - $pack_order_qty_Year)));
        }
        
        
        $html=''; 
		$inventoryStatusArr = array(
                                 array('WIP -  Quantity','Pcs',round($today_total_WIP/100000,2),round($month_total_WIP/100000,2),round($year_total_WIP/100000,2),10,""),
                                 array('WIP -  Value','Rs',round($today_WIP_value/100000,2),round($month_WIP_value/100000,2),round($year_WIP_value/100000,2),10,""),
                              );      
        $this->tempInsertData($inventoryStatusArr);   
        return response()->json(['html' => $html]);
        
    }
    
    public function tempInsertData($dataArr)
    {
        foreach($dataArr as $key => $value)
        {
            DB::table('temp_order_sales_dashboard')->insert([
                
                "key_Indicators"=>$dataArr[$key][0],
                "uom"=>$dataArr[$key][1],
                "today"=>$dataArr[$key][2],
                "month_to_date"=>$dataArr[$key][3],
                "year_to_date"=>$dataArr[$key][4],
                "table_head"=>$dataArr[$key][5],
                "company_name"=>$dataArr[$key][6],
                ]);
        }
        return 1;
    }
    public function SalesOrderDetailDashboard()
    {
          $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: select('buyer_purchse_order_master.*','sales_order_costing_master.sam',DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
            , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty')
            )
            ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
            ->where('buyer_purchse_order_master.delflag','=', '0')
            ->where('buyer_purchse_order_master.og_id','!=', '4')
             ->where('buyer_purchse_order_master.job_status_id','=', '1')
            ->get();
         
            $total_valuec=0;
            $total_qtyc=0;
            $total_order_min=0;
            $total_shipped_qtyc= 0;
            $total_shipped_min = 0;
            $total_balance_qty = 0;
            $total_balance_min = 0;
            $total_produce_qty = 0;
            $total_produce_min = 0;
            $FGStock = 0;
            $total_fabric_value = 0;
            $total_trim_value = 0;
            $total_FGStock = 0;
            
            foreach($Buyer_Purchase_Order_List as $row)
            {
                $total_valuec=$total_valuec + $row->order_value; 
                $total_qtyc=$total_qtyc+$row->total_qty; 
                $total_order_min= $total_order_min + (($row->sam) * ($row->total_qty)); 
                $total_shipped_qtyc=$total_shipped_qtyc+$row->shipped_qty;
                
                $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."'
                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                     
                $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                
                $total_shipped_min = $total_shipped_min + ($Ship * $row->sam);
                $total_balance_qty = $total_balance_qty + $row->balance_qty;
                $total_balance_min = $total_balance_min + ($row->balance_qty * $row->sam);
                
                $FGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty' FROM `packing_inhouse_size_detail2` WHERE  packing_inhouse_size_detail2.sales_order_no = '". $row->tr_code."' GROUP by packing_inhouse_size_detail2.sales_order_no");
                    // dd(DB::getQueryLog());    
                 
                if($FGStockData != null)
                { 
                    $FGStock = $FGStockData[0]->packing_grn_qty - $FGStockData[0]->carton_pack_qty -  $FGStockData[0]->transfer_qty;
                }
                 
                $total_produce_qty = $total_produce_qty + ($row->balance_qty - $FGStock);
                $total_produce_min = $total_produce_min + ($row->sam * ($row->balance_qty - $FGStock)); 
                
                
            }
            
            $FabricInwardDetails =DB::select("select inward_details.*, (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter from inward_details");
            
            foreach($FabricInwardDetails as $row)
            {
                $total_fabric_value = $total_fabric_value + ($row->meter-$row->out_meter) * $row->item_rate;
            }
            
            
            
            $TrimsInwardDetails = DB::select("select trimsInwardDetail.*,(select ifnull(sum(item_qty),0) as item_qty  from trimsOutwardDetail 
                where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty 
                from trimsInwardDetail inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
                inner join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
                inner join item_master on item_master.item_code=trimsInwardDetail.item_code
                inner join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id");
                
            foreach($TrimsInwardDetails as $rows)
            {
                $total_trim_value = $total_trim_value + ((($rows->out_qty) - ($rows->item_qty)) * $rows->item_rate);
            }
            
            $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
                        DB::raw('sum(Gst_amount) as TotalGst'),
                        DB::raw('sum(Net_amount) as TotalNet'),
                        DB::raw('sum(total_qty) as TotalQty'))
                        ->where('sale_transaction_master.delflag','=', '0')
                        ->whereBetween('sale_date',array('2022-04-01','2023-04-01'))
                        ->get();
        
            $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
                from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
             
            $FGStockData1 = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                     (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty',
                    
                    
                    order_rate
                    FROM `packing_inhouse_size_detail2`
                    LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
                    LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
                    LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
                    GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
                // dd(DB::getQueryLog());    
                    
            foreach($FGStockData1 as $row1)
            {
                $total_FGStock = $total_FGStock + (($row1->packing_grn_qty - $row1->carton_pack_qty -  $row1->transfer_qty)) * ($row1->order_rate);
            }
             $html = '';
             $html .='<tr>
                    <td class="text-center">Total Live Orders</td>
                    <td class="text-center">'.number_format((double)($total_qtyc/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_order_min/100000), 2, '.', '').'</a></td>
                 </tr>
                 <tr>
                    <td class="text-center">Delivered</td>
                    <td class="text-center">'.number_format((double)($total_shipped_qtyc/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_shipped_min/100000), 2, '.', '').'</a></td>
                 </tr>
                 <tr>
                    <td class="text-center">Balance To Ship</td>
                    <td class="text-center">'.number_format((double)($total_balance_qty/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_balance_min/100000), 2, '.', '').'</a></td>
                 </tr>
                 <tr>
                    <td class="text-center">Balance To Produce</td>
                    <td class="text-center">'.number_format((double)($total_produce_qty/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_produce_min/100000), 2, '.', '').'</a></td>
                 </tr>';
                 
         return response()->json(['html' => $html]);
    }
    
    public function SalesDashboard()
    {
        setlocale(LC_MONETARY, 'en_IN');
        
         $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");

        $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
                    DB::raw('sum(Gst_amount) as TotalGst'),
                    DB::raw('sum(Net_amount) as TotalNet'),
                    DB::raw('sum(total_qty) as TotalQty'))
                    ->where('sale_transaction_master.delflag','=', '0')
                    ->whereBetween('sale_date',array($Financial[0]->fdate, $Financial[0]->tdate))
                    ->get();
    
        $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
            from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
        
        $html = '';
        $html .='<tr>
            <td class="text-center">Monthly</td> 
            <td class="text-center">400.00</td>
            <td class="text-center"><a href="/SaleFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.number_format((double)($MonthList[0]->total_sale_value/100000), 2, '.', '').'</a></td>
            <td class="text-center">'.number_format((double)((($MonthList[0]->total_sale_value/400) * 100)/100000), 2, '.', '').'%</td>
         </tr>
         <tr>
            <td class="text-center">Yearly</td>
            <td class="text-center">5,000.00</td>
            <td class="text-center"><a href="/SaleFilterReport?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',$SaleTotal[0]->TotalGross/100000).'</a></td>
            <td class="text-center">'.number_format((double)((($SaleTotal[0]->TotalGross/5000) * 100)/100000), 2, '.', '').'%</td>
         </tr>';       
         
        return response()->json(['html' => $html]);
    }
    
    public function RawMaterialDashboard()
    {  
        $total_fabric_value = 0;
        $total_trim_value = 0;
        
        $FabricInwardDetails =DB::select("select inward_details.*, (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter from inward_details");
            
        foreach($FabricInwardDetails as $row)
        {
            $total_fabric_value = $total_fabric_value + ($row->meter-$row->out_meter) * $row->item_rate;
        }
        
        $TrimsInwardDetails = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
       ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
       (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
       where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
       trimsInwardMaster.po_code, 
       ledger_master.ac_name,item_master.dimension,item_master.item_name,
       item_master.color_name,item_master.item_description,rack_master.rack_name
       from trimsInwardDetail
       left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
       left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
       left join item_master on item_master.item_code=trimsInwardDetail.item_code
       left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id
       group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
       ");
       
       if(count($TrimsInwardDetails)>0)
       {
           foreach($TrimsInwardDetails as $yrow) 
           {
                $total_trim_value=$total_trim_value + (($yrow->item_qty-$yrow->out_qty) *$yrow->item_rate);
           }
       }     
        
        $html = '';
        $html .='<tr>
                <td class="text-center">Fabric</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center"><a href="/FabricStockSummaryData">'.number_format((double)($total_fabric_value/100000), 2, '.', '').'</a></td>
             </tr>
             <tr>
                <td class="text-center">Trims</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center"><a href="/TrimsStockData">'.number_format((double)($total_trim_value/100000), 2, '.', '').'</a></td>
             </tr>';
        return response()->json(['html' => $html]);
    }
    
    public function Finishing()
    {  
        $total_FGStock = 0;
        
        $FGStockData1 = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
            (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
            carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
            and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
            and carton_packing_inhouse_master.endflag=1
            ) as 'carton_pack_qty',
            
             (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
            transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
            and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
            and transfer_packing_inhouse_size_detail2.usedFlag=1
            ) as 'transfer_qty',
            
            
            order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
            LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
            GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
            // dd(DB::getQueryLog());    
                
        foreach($FGStockData1 as $row1)
        {
            $total_FGStock = $total_FGStock + (($row1->packing_grn_qty - $row1->carton_pack_qty -  $row1->transfer_qty)) * ($row1->order_rate);
        }
        
        $html = '';
        $html .=' <tr>
                    <td class="text-center">Garments</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">'.number_format((double)($total_FGStock/100000), 2, '.', '').'</td>
                 </tr>';
                 
        return response()->json(['html' => $html]);
    }
    
    public function OrderStatus()
    {  
        
         setlocale(LC_MONETARY, 'en_IN');
        
        $total_FGStock = 0;
        
        $DBoard = DB::select("SELECT db_id, BK_VOL_TD_P, BK_VOL_M_TO_Dt_P, BK_VOL_Yr_TO_Dt_P, 
            BK_VAL_TD_P, BK_VAL_M_TO_Dt_P, BK_VAL_Yr_TO_Dt_P, SAL_VOL_TD_P,
            SAL_VOL_M_TO_Dt_P, SAL_VOL_Yr_TO_Dt_P, SAL_VAL_TD_P,
            SAL_VAL_M_TO_Dt_P, SAL_VAL_Yr_TO_Dt_P, BOK_SAH_TD_P,
            BOK_SAH_M_TO_Dt_P, BOK_SAH_Y_TO_Dt_P, SAL_SAH_TD_P,
            SAL_SAH_M_TO_Dt_P, SAL_SAH_Yr_TO_Dt_P  FROM dashboard_master");
            
            $TodayList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and `order_received_date`=CURRENT_DATE()");
            
            $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and 
            MONTH(order_received_date)=MONTH(CURRENT_DATE()) and YEAR(order_received_date)=YEAR(CURRENT_DATE())");
            
            $YearList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value 
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and 
            order_received_date between (select fdate from financial_year_master 
            where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)");
        
        $html = '';
        $html .=' <tr style="color:black; text-align:right;   border: black 0.5px solid;">
                        <td style="border: black 0.5px solid;"><b>Booking Volume (Pcs) In Lakh</b>	</td>
                        <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VOL_TD_P).'</td>';
                        if($TodayList[0]->total_order_qty!=0)
                        {
                            $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_order_qty/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_TD_P) * (100)),2)).'%</td>';
                        }
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                        $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VOL_M_TO_Dt_P).'</td>';
                        if($MonthList[0]->total_order_qty!=0)
                        {
                            $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',(round((($MonthList[0]->total_order_qty/100000)),2))).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_M_TO_Dt_P) * (100)),2)).'%</td>';
                        } 
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;"> 0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                       
                        $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");

                        $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VOL_Yr_TO_Dt_P).'</td>';
                        if($YearList[0]->total_order_qty!=0)
                        {
                             $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($YearList[0]->total_order_qty/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($YearList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_Yr_TO_Dt_P) * (100)),2)).'% </td>';
                        }
                        else
                        {
                        $html .='<td style="border: black 0.5px solid;">0.00</td>
                                <td style="border: black 0.5px solid;"> 0.00</td>';
                        }
                     $html .='</tr>';
                     $html .='<tr style="color:black; text-align:right;   border: black 0.5px solid;">
                        <td style="border: black 0.5px solid;"><b>Booking Value in In Lakh</b></td>
                        <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VAL_TD_P).'</td>';
                        if($TodayList[0]->total_order_qty!=0)
                        {
                           $html .=' <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_order_value/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_TD_P) *(100)),2)).' % </td>';
                        }
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                        $html .='<td style="border: black 0.5px solid;" >'.money_format('%!i',$DBoard[0]->BK_VAL_M_TO_Dt_P).'</td>';
                        if($MonthList[0]->total_order_qty!=0)
                        {
                            $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',round(($MonthList[0]->total_order_value/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_M_TO_Dt_P) *(100)),2)).'%</td>';
                        }
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                        $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VAL_Yr_TO_Dt_P).'</td>';
                        if($YearList[0]->total_order_qty!=0)
                        {
                           $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($YearList[0]->total_order_value/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($YearList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_Yr_TO_Dt_P) *(100)),2)).'%</td>';
                        }
                        else
                        {
                             $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                      $html .='</tr>';
                 
        return response()->json(['html' => $html]);
    }
    
    public function SaleStatus()
    {  
        
      setlocale(LC_MONETARY, 'en_IN');   
        
        $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
            DB::raw('sum(Gst_amount) as TotalGst'),
            DB::raw('sum(Net_amount) as TotalNet'),
            DB::raw('sum(total_qty) as TotalQty'))
            ->where('sale_transaction_master.delflag','=', '0')
            ->whereBetween('sale_date',array('2022-04-01','2023-04-01'))
            ->get();
        
        $DBoard = DB::select("SELECT db_id, BK_VOL_TD_P, BK_VOL_M_TO_Dt_P, BK_VOL_Yr_TO_Dt_P, 
            BK_VAL_TD_P, BK_VAL_M_TO_Dt_P, BK_VAL_Yr_TO_Dt_P, SAL_VOL_TD_P,
            SAL_VOL_M_TO_Dt_P, SAL_VOL_Yr_TO_Dt_P, SAL_VAL_TD_P,
            SAL_VAL_M_TO_Dt_P, SAL_VAL_Yr_TO_Dt_P, BOK_SAH_TD_P,
            BOK_SAH_M_TO_Dt_P, BOK_SAH_Y_TO_Dt_P, SAL_SAH_TD_P,
            SAL_SAH_M_TO_Dt_P, SAL_SAH_Yr_TO_Dt_P  FROM dashboard_master");
        
        $TodayList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
        from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and `order_received_date`=CURRENT_DATE()");
        
        $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
        from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and MONTH(order_received_date)=MONTH(CURRENT_DATE()) and YEAR(order_received_date)=YEAR(CURRENT_DATE())");
        
        $YearList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value 
        from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and order_received_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=1) and (select tdate from financial_year_master where financial_year_master.fin_year_id=1)");
        
        $TodayList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
        from sale_transaction_master where `sale_date`=CURRENT_DATE()");
        
        $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
        from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
        
        $YearList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value 
        from sale_transaction_master where sale_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)");
        
        $html = '';
        
        $html .='<tr style="color:black; text-align:right;">
            <td style="border: black 0.5px solid;"><b>Sale Volume (Pcs) In Lakh</b>	</td>
            <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VOL_TD_P).'</td>';
            if($TodayList[0]->total_sale_qty!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_sale_qty/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_TD_P) * (100)),2)).' %</td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
             $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VOL_M_TO_Dt_P).'</td>';
            if($MonthList[0]->total_sale_qty!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',round(($MonthList[0]->total_sale_qty/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_M_TO_Dt_P) * (100)),2)).' %</td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
            $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VOL_Yr_TO_Dt_P).'</td>';
            
            $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
            
            if($YearList[0]->total_sale_qty!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($YearList[0]->total_sale_qty/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($YearList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_Yr_TO_Dt_P) * (100)),2)).' % </td>';
            }
            else
            {
                 $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;"> 0.00</td>';
            }
        $html .='</tr>';
         
        $html .='<tr style="color:black;text-align:right; ">
         
            <td style="border: black 0.5px solid;"><b>Sale Value in In Lakh</b></td>
            <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VAL_TD_P).'</td>';
            if($TodayList[0]->total_sale_value!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_sale_value/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_sale_value/100000)/$DBoard[0]->SAL_VAL_TD_P) * (100)),2)).' % </td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
            
            $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VAL_M_TO_Dt_P).'</td>';
            if($MonthList[0]->total_sale_value!=0)
            {
                 $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',round(($MonthList[0]->total_sale_value/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_sale_value/100000)/$DBoard[0]->SAL_VAL_M_TO_Dt_P) * (100)),2)).'%</td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
            $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VAL_Yr_TO_Dt_P).'</td>';
            if($YearList[0]->total_sale_value!=0)
            {
                $html .='<td style="border: black 0.5px solid;"> <a href="/SaleFilterReport?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($SaleTotal[0]->TotalGross/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round((((($SaleTotal[0]->TotalGross/5000) * 100)/100000) * (100)),2)).'%</td>';  
            }
            else   
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
        $html .='</tr>';
       return response()->json(['html' => $html]);
    }
    
    public function FabricStatus()
    {  
        
     setlocale(LC_MONETARY, 'en_IN');    
        
        $today_meter=0; 
        $today_amount=0;
        $month_meter=0; 
        $month_amount=0;
        $year_meter=0; 
        $year_amount=0;
        
        $TodayDetails =DB::select("select inward_details.meter,inward_details.item_rate,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
        (inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
        from inward_details where in_date=CURRENT_DATE()");
        
        if(count($TodayDetails)>0)
        {
            foreach($TodayDetails as $trow) 
            {
                $today_meter=$today_meter + $trow->meter;
                $today_amount=$today_amount + ($trow->meter *$trow->item_rate);
            }
        }   
        
        $MonthDetails =DB::select("select inward_details.meter,inward_details.item_rate,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
        (inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
        from inward_details where MONTH(in_date)=MONTH(CURRENT_DATE()) and YEAR(in_date)=YEAR(CURRENT_DATE())");
        
        if(count($MonthDetails)>0)
        {
            foreach($MonthDetails as $mrow) 
            {
                $month_meter=$month_meter + $mrow->meter;
                $month_amount=$month_amount + ($mrow->meter *$mrow->item_rate);
                }
        } 
        
        $year_meter=0;
        
        $YearDetails =DB::select("select sum(inward_details.meter) as in_meter,inward_details.item_rate,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code) as out_meter,
        (sum(inward_details.meter) - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
        from inward_details group by inward_details.track_code");
        
        if(count($YearDetails)>0)
        {
            foreach($YearDetails as $yrow) 
            {
                $year_meter=$year_meter + ($yrow->in_meter - $yrow->out_meter);
                $year_amount=$year_amount + ($yrow->StockMeter *$yrow->item_rate);
            }
        }   

        $html ='';
        $html .='<tr style="color:black;">
                    <td><b>Inward Today</b>	</td>
                    <td style="text-align:right;color:black; ">  <a href="/FabricGRNFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',($today_meter/100000 )).'</a> </td>
                    <td style="color:black;text-align:right;"  <a href="/FabricGRNFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',($today_amount/100000)).'</a></td>
                 </tr>
                 <tr style="color:black;">
                    <td><b>Inward MTD	</b></td>
                    <td style="text-align:right;color:black;">
                       <a href="/FabricGRNFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">
                          '.money_format('%!i',($month_meter/100000 )).'
                    </td>
                    <td style="text-align:right;color:black;">  <a href="/FabricGRNFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',($month_amount/100000)).'</td>
                 </tr >
                 <tr style="color:black;">
                 <td><b>Fabric Stock</b>	</td>
                 <td style="text-align:right;color:black;"><a href="FabricInOutStockReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'" >'.money_format('%!i',($year_meter/100000)).'</a> </td>
                 <td style="text-align:right;color:black;"><a href="FabricStockSummaryData" >'.money_format('%!i',($year_amount/100000)).'</a></td>
                 </tr>';
        return response()->json(['html' => $html]);
    }
    
    public function FinishingGoodsStatus()
    {       
        
       setlocale(LC_MONETARY, 'en_IN');  
        
       $today_packed=0; 
       $today_amount=0;
       $month_packed=0; 
       $month_amount=0;
       $year_packed=0; 
       $year_amount=0;
       
       $TodayDetails =DB::select("SELECT sales_order_no, `order_rate`, 
       ifnull(sum(packing_inhouse_master.total_qty),0) as Packed_Qty
       from packing_inhouse_master
       inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_master.sales_order_no
       where pki_date=CURRENT_DATE() group by sales_order_no");
       
       if(count($TodayDetails)>0)
       {
           foreach($TodayDetails as $trow) 
           {
               $today_packed=$today_packed + $trow->Packed_Qty;
               $today_amount=$today_amount + ($trow->Packed_Qty *$trow->order_rate);
           }
       }   
       
       $MonthDetails =DB::select("SELECT sales_order_no, `order_rate`, 
       ifnull(sum(packing_inhouse_master.total_qty),0) as Packed_Qty
       from packing_inhouse_master
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_master.sales_order_no
       where   MONTH(pki_date)=MONTH(CURRENT_DATE()) and YEAR(pki_date)=YEAR(CURRENT_DATE()) group by sales_order_no ");
       
       if(count($MonthDetails)>0)
       {
           foreach($MonthDetails as $mrow) 
           {
               $month_packed=$month_packed + $mrow->Packed_Qty;
               $month_amount=$month_amount + ($mrow->Packed_Qty *$mrow->order_rate);
           }
       }   
       
       $YearDetails =DB::select("SELECT sales_order_no, `order_rate`, 
           ifnull(sum(packing_inhouse_detail.size_qty_total),0) as Packed_Qty, (select ifnull(sum(sale_transaction_detail.order_qty),0) 
           from sale_transaction_detail where sales_order_no=packing_inhouse_detail.sales_order_no) as 'sold' 
           ,(SELECT ifnull(sum(transfer_packing_inhouse_detail.size_qty_total),0)
           from transfer_packing_inhouse_detail where transfer_packing_inhouse_detail.usedFlag=1 and  main_sales_order_no=packing_inhouse_detail.sales_order_no) as TransferQty
           FROM `packing_inhouse_detail`
           inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_detail.sales_order_no
           group by sales_order_no ");
       
       $Totalstock=0;
       if(count($YearDetails)>0)
       {
           foreach($YearDetails as $yrow) 
           {
               $year_packed=$yrow->Packed_Qty - $yrow->sold - $yrow->TransferQty;
               $Totalstock=$Totalstock+$year_packed;
               $year_amount=round(($year_amount + ($yrow->Packed_Qty * $yrow->order_rate)),2);
           }
       }  
       $html ='';
       $html .='<tr style="color:black;">
                        <td><b>Inward Today</b>	</td>
                        <td style="text-align:right;">'.money_format('%!i',($today_packed/100000 )).'</td>
                        <td style="text-align:right;">'.money_format('%!i',($today_amount/100000)).'</td>
                     </tr>
                     <tr style="color:black;">
                        <td><b>Inward MTD</b>	</td>
                        <td style="text-align:right;">'.money_format('%!i',($month_packed/100000 )).'</td>
                        <td style="text-align:right;">'.money_format('%!i',($month_amount/100000)).'</td>
                     </tr >
                     <tr style="color:black;">
                        <td><b>FG Stock</b>	</td>
                        <td style="text-align:right;"><a href="/FGStockSummaryReport">'.money_format('%!i',($Totalstock/100000)).'</a> </td>
                        <td style="text-align:right;"><a href="/FGStockReport">'.money_format('%!i',($year_amount/100000)).'</a></td>
                     </tr>';
       return response()->json(['html' => $html]);
    }
    
    public function TrimStatus()
    {   
        
     setlocale(LC_MONETARY, 'en_IN');    
        
       $today_qty=0; 
       $today_amount=0;
       $month_qty=0; 
       $month_amount=0;
       $year_qty=0; 
       $year_amount=0;
       
       $TodayDetails =DB::select("select ifnull((trimsInwardDetail.item_qty),0) as item_qty,trimsInwardDetail.item_rate 
       from trimsInwardDetail   where trimDate=CURRENT_DATE()");
       
       if(count($TodayDetails)>0)
       {  
           foreach($TodayDetails as $trow) 
           {
               $today_qty=$today_qty + $trow->item_qty;
               $today_amount=$today_amount + ($trow->item_qty * $trow->item_rate);
           }
       } 
       
       $MonthDetails =DB::select("select ifnull( (trimsInwardDetail.item_qty),0) as item_qty,trimsInwardDetail.item_rate
       from trimsInwardDetail where MONTH(trimDate)=MONTH(CURRENT_DATE()) and YEAR(trimDate)=YEAR(CURRENT_DATE())");
       
       if(count($MonthDetails)>0)
       {
           foreach($MonthDetails as $mrow) 
           {
                $month_amount=$month_amount + ($mrow->item_qty * $mrow->item_rate);
           }
       }   

       $TrimsInwardDetails = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
       ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
       (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
       where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
       trimsInwardMaster.po_code, 
       ledger_master.ac_name,item_master.dimension,item_master.item_name,
       item_master.color_name,item_master.item_description,rack_master.rack_name
       from trimsInwardDetail
       left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
       left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
       left join item_master on item_master.item_code=trimsInwardDetail.item_code
       left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id
       group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
       ");
       
       if(count($TrimsInwardDetails)>0)
       {
           foreach($TrimsInwardDetails as $yrow) 
           {
                $year_amount=$year_amount + (($yrow->item_qty-$yrow->out_qty) *$yrow->item_rate);
           }
       }     
       
       $html ='';
       $html .='<tr style="color:black;">
                <td><b>Inward Today</b>	</td>
                <td style="text-align:right;">  <a href="/TrimsGRNReportPrint?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',($today_amount/100000)).'</a></td>
             </tr>
             <tr style="color:black;">
                <td><b>Inward MTD</b>	</td>
                <td style="text-align:right;">  <a href="/TrimsGRNReportPrint?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',($month_amount/100000)).'</a></td>
             </tr>
             <tr style="color:black;">
                <td><b>Trim Stock</b>	</td>
                <td style="text-align:right;"><a href="TrimsStockData">'.money_format('%!i',($year_amount/100000)).'</a></td>
             </tr>';
       return response()->json(['html' => $html]);
    }
    
    public function WorkInProgressStatus()
    {   
        
       setlocale(LC_MONETARY, 'en_IN');  
        
           $no = 1;
           $total_WQty=0; 
           $total_cut_panel_issue=0; 
           $total_packing_qty=0; 
           $total_WIP=0; 
           $total_issue_meter=0;
           
           $JobWorkers=DB::select('select distinct(vendorId) , Ac_name  from vendor_work_order_master 
           left join ledger_master on ledger_master.Ac_code=vendor_work_order_master.vendorId');
       
           $html1 ='';
           $html2 ='';
           
            foreach($JobWorkers as $rowJW)
            {
                 $orders=DB::select("select ifnull(sum(vendor_work_order_detail.size_qty_total),0) as wqty,
                 (select ifnull(sum(meter),0) from fabric_outward_details
                 inner join vendor_purchase_order_master on  vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code
                 where fabric_outward_details.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1)   as issue_meter ,
                 (select ifnull(sum(size_qty_total),0) from cut_panel_issue_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=cut_panel_issue_detail.vw_code
                 where cut_panel_issue_detail.vendorId='".$rowJW->vendorId."' and vendor_work_order_master.endflag=1)   as cut_panel_issue_qty ,
                 (select ifnull(sum(size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on  vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1 AND vendor_work_order_master.vendorId != '177')  as packing_grn_qty ,
                 (ifnull(sum(vendor_work_order_detail.size_qty_total),0) - (select ifnull(sum(packing_inhouse_detail.size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1 AND vendor_work_order_master.vendorId != '177') ) as WIP
                 from vendor_work_order_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                 where vendor_work_order_master.vendorId='".$rowJW->vendorId."' and vendor_work_order_master.endflag=1
                 AND vendor_work_order_master.vendorId != '177'
                 order by WIP");
            
                 foreach($orders as $ow)
                 {
                         $total_WQty=$total_WQty + $ow->wqty;
                         $total_cut_panel_issue=$total_cut_panel_issue + $ow->cut_panel_issue_qty;
                         $total_packing_qty=$total_packing_qty + $ow->packing_grn_qty;
                         $total_WIP=$total_WIP + $ow->WIP;
                         $total_issue_meter=$total_issue_meter+$ow->issue_meter;
                         if((($ow->wqty) + ($ow->cut_panel_issue_qty)  + ($ow->packing_grn_qty)  + ($ow->WIP))!=0)
                         {
                		     $html1 .='<tr>
                                            <td class="text-center">'.$no++.'</td>
                                            <td>'.$rowJW->Ac_name.'</td>
                                            <td class="text-center">'.number_format($ow->wqty).'</td>
                                            <td class="text-center">'.number_format($ow->cut_panel_issue_qty).'</td>
                                            <td class="text-center">'.number_format($ow->packing_grn_qty).'</td>
                                            <td class="text-center"><a href="'.url("WIPDetailReport", [$rowJW->vendorId]).'" target="_blank">'.number_format($ow->WIP).'</a></td>
                                       </tr>';
                        }
                 
                }
            }
                
    		$html2 .='<tr>                
        				<th></th>            
        				<th>Total :</th>
        				<th>'.money_format('%!.0n',round($total_WQty)).'</th>
        				<th>'.money_format('%!.0n',round($total_cut_panel_issue)).'</th>
        				<th>'.money_format('%!.0n',round($total_packing_qty)).'</th>
        				<th>'.money_format('%!.0n',round($total_WIP)).'</th>
        			</tr>';
    			
            return response()->json(['html1' => $html1,'html2' => $html2]);
    }
    
    public function GarmentSale()
    {     
        
     setlocale(LC_MONETARY, 'en_IN');    
        
         $MonthSale =DB::select("SELECT DATE_FORMAT(`sale_date`, '%b-%Y') AS SaleDate, sale_date,
         ifnull(sum(`total_qty`),0) as soldQty,     (sum(Gross_amount)/ifnull(sum(`total_qty`),0)) as Rate,
         sum(Gross_amount) as Taxable_Amount FROM sale_transaction_master where
         sale_transaction_master.sale_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master))
         and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         GROUP BY SaleDate ORDER BY sale_date ASC");
         
         $totalAmt=0;
         $AvrRate=0;
         $TotalPcs=0;
         $no=0;
         $html1 ='';
         $html2 ='';
         
        if(count($MonthSale)>0)
        {
            foreach($MonthSale as $mrow) 
            {
                $month=date('m', strtotime($mrow->sale_date));
             
                $html1 .='<tr style="color:black;">
                    <td><b>'.$mrow->SaleDate.'</b>	</td>
                    <td style="text-align:right;"><a href="/SaleFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->sale_date)).'">'.money_format('%!i',($mrow->soldQty/100000 )).'</a></td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->Rate )).'</td>
                    <td style="text-align:right;"><a href="/SaleFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->sale_date)).'">'.money_format('%!i',($mrow->Taxable_Amount/100000)).'</a></td>
                    </tr>';
             
             $totalAmt=$totalAmt + ($mrow->Taxable_Amount/100000);
             $TotalPcs=$TotalPcs + ($mrow->soldQty/100000);
             $AvrRate=$AvrRate + $mrow->Rate;
             $no=$no+1;
            }
            
            $html2 .='<tr>
                        <td><b>Total</b></td>
                        <td style="text-align:right;">'.money_format('%!i',$TotalPcs).'</td>
                        <td style="text-align:right;">'.money_format('%!i',($AvrRate/$no)).'</td>
                        <td style="text-align:right;">'.money_format('%!i',$totalAmt).'</td>
                     </tr>';
        }
       
       return response()->json(['html1' => $html1, 'html2' => $html2]);
    }
    
    public function GarmentPurchase()
    {   
        
     setlocale(LC_MONETARY, 'en_IN');    
        
        $totalAmt=0;
        $AvrRate=0;
        $TotalPcs=0;
        $html1 ='';
        $html2 =''; 
        
        $MonthInward =DB::select("SELECT DATE_FORMAT(`in_date`, '%b-%Y') AS INDate, in_date ,  
         ifnull(sum(total_meter),0) as meter ,(ifnull(sum(total_amount),0)/ifnull(sum(total_meter),0)) as Rate,
         ifnull(sum(total_amount),0) as Taxable_Amount FROM inward_master 
         where inward_master.in_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         GROUP BY INDate ORDER BY inward_master.in_date ASC");
         
         if(count($MonthInward)>0)
         {
             $no=0;
             foreach($MonthInward as $mrow) 
             {
                 $month=date('m', strtotime($mrow->in_date));
             
                $html1 .='<tr style="color:black;">
                            <td><b>'.$mrow->INDate.'</b></td>
                            <td style="text-align:right;"><a href="/FabricGRNFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->in_date)).'">'.money_format('%!i',($mrow->meter/100000 )).'</a></td>
                            <td style="text-align:right;">'.money_format('%!i',($mrow->Rate )).'</td>
                            <td style="text-align:right;"> <a href="/FabricGRNFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->in_date)).'">'.money_format('%!i',($mrow->Taxable_Amount/100000)).'</a></td>
                         </tr>';
                 
                 $TotalPcs = $TotalPcs + ($mrow->meter/100000);
                 $totalAmt = $totalAmt + ($mrow->Taxable_Amount/100000);
                 $AvrRate = $AvrRate + $mrow->Rate;
                 $no=$no+1;
             }
              $html2 .='<tr>                           
                            <td><b>Total</b></td>  
                            <td style="text-align:right;">'.money_format('%!i',round($TotalPcs)).'</td>
                            <td style="text-align:right;">'.money_format('%!i',round($AvrRate/$no)).'</td>
                            <td style="text-align:right;">'.money_format('%!i',round($totalAmt)).'</td>
                         </tr>';
             
         }
      
                    
        return response()->json(['html1' => $html1, 'html2' => $html2]);
    }
    
    public function FinishedGoodsInward()
    {  
        
      setlocale(LC_MONETARY, 'en_IN');   
        
         $MonthInward =DB::select("SELECT DATE_FORMAT(`pki_date`, '%b-%Y') AS PKIDate, 
         ifnull(sum(total_qty),0) as TotalQty ,AVG(rate) as Rate,(ifnull(sum(total_qty),0)*AVG(rate)) as Taxable_Amount FROM packing_inhouse_master 
         where packing_inhouse_master.pki_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master))
         and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         GROUP BY PKIDate ORDER BY packing_inhouse_master.pki_date ASC");
         
         $html1 = '';
         $html2 = '';
         $no = 1;
         
         if(count($MonthInward)>0)
         {
             $totalAmt=0;
             $AvrRate=0;
             $TotalPcs=0;
             foreach($MonthInward as $mrow) 
             {
                 $html1 .='<tr style="color:black;">
                    <td><b>'.$mrow->PKIDate.'</b>	</td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->TotalQty/100000)).'</td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->Taxable_Amount/$mrow->TotalQty)).'</td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->Taxable_Amount/100000)).'</td>
                 </tr>';
                 
                 $TotalPcs = $TotalPcs + ($mrow->TotalQty/100000);
                 $totalAmt = $totalAmt + ($mrow->Taxable_Amount/100000);
                 $AvrRate = $AvrRate +  ($mrow->Taxable_Amount/$mrow->TotalQty);
                 $no=$no+1;
             } 
             $html2 .='<tr>
                <td><b>Total</b></td>
                <td style="text-align:right;">'.round($TotalPcs,2).'</td>
                <td style="text-align:right;">'.round(($AvrRate/$no),2).'</td>
                <td style="text-align:right;">'.round($totalAmt,2).'</td>
             </tr>';
         }
        return response()->json(['html1' => $html1, 'html2' => $html2]);
    }
    
    public function GraphicalDashboardOriginalCode()
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '299')
        ->first();
        
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        return view('GraphicalDashboardOriginalCode',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1', 'chekform'));
         
    }
    public function GraphicalDashboard()
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '299')
        ->first();
        
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        return view('GraphicalDashboard',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1', 'chekform'));
         
    }
    
    
    public function AnalysisQualityControl()
    {
        return view('AnalysisQualityControl');
    }
    
    public function OutsourceDashboard()
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '299')
        ->first();
        
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        return view('OutsourceDashboard',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1', 'chekform'));
         
    }
    
     public function operation_dashboard()
    {
        
        
         $chekform = DB::table('form_auth')
    ->where('emp_id', Session::get('userId'))
    ->where('form_id', '298')
    ->first();
    
        return view('operation_dashboard', compact('chekform'));
        
        //return view('operation_dashboard');
    }
    
    
    
    public function GraphicalSaleDashboard()
    {
        
        $Buyer_Purchase_Order_List = DB::select("SELECT * FROM buyer_purchse_order_master WHERE delflag = 0");
        
        $dateArr  = [];
        $QtyArr  = [];
        $min  = 0;
        $max  = 0;
        foreach($Buyer_Purchase_Order_List as $order)
        {
            $SaleTransactionDetails =
            DB::select("SELECT sale_transaction_master.sale_date,sum(order_qty) as order_qty,min(order_qty) as minQty, max(order_qty) as maxQty FROM sale_transaction_detail 
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            WHERE sales_order_no ='".$order->tr_code."' GROUP BY sale_transaction_master.sale_code");
           
            foreach($SaleTransactionDetails as $sales)
            {
                $dateArr[] = $sales->sale_date;
                
                $QtyArr[] = $sales->order_qty;
                
            }
            
        } 
        $min = min($QtyArr);
        $max = max($QtyArr);
       
        print_r($QtyArr); exit;
        
        //print_r($max);exit;
         $tar = array(74, 83, 15, 97, 86, 65, 93, 10, 94);
         $amt = array(46, 57, 59, 54, 62, 58, 64, 60, 66);
         
         $target = json_encode($tar);
         $amount = json_encode($amt);
         
         return response()->json(['target' => $target, 'amount'=>$amount]);
    }
    
    public function DumpFGStockReport(Request $request)
    {
        //DB::enableQueryLog();
            $BuyerData = DB::table('buyer_purchse_order_master')->select('buyer_purchse_order_master.Ac_code','buyer_purchse_order_master.brand_id','tr_code','order_rate','job_status_master.job_status_name','buyer_purchse_order_master.job_status_id','brand_master.brand_name')
                ->join('job_status_master','job_status_master.job_status_id','=','buyer_purchse_order_master.job_status_id')
                ->join('brand_master','brand_master.brand_id','=','buyer_purchse_order_master.brand_id')
                ->skip($request->start)
                ->take($request->end)
                ->orderBy('tr_code', 'DESC')
                ->get();
              
        //dd(DB::getQueryLog());
              foreach($BuyerData as $row)
              {
                       
                    $packingData = DB::table('packing_inhouse_size_detail2')->select(DB::raw('sum(packing_inhouse_size_detail2.size_qty) as packing_grn_qty'),
                        'packing_inhouse_size_detail2.pki_date','Ac_name','packing_inhouse_size_detail2.color_id',
                        'packing_inhouse_size_detail2.style_no', 'packing_inhouse_size_detail2.size_id','Ac_name','mainstyle_name', 'color_master.color_name')
                    ->join('ledger_master','ledger_master.ac_code','=','packing_inhouse_size_detail2.Ac_code')
                    ->join('color_master','color_master.color_id','=','packing_inhouse_size_detail2.color_id')
                    ->join('main_style_master','main_style_master.mainstyle_id','=','packing_inhouse_size_detail2.mainstyle_id')
                    ->where('packing_inhouse_size_detail2.sales_order_no','=', $row->tr_code)
                    ->where('packing_inhouse_size_detail2.pki_date','<=', date('Y-m-d'))
                    ->GROUPBY('packing_inhouse_size_detail2.sales_order_no')
                    ->GROUPBY('packing_inhouse_size_detail2.color_id')
                    ->GROUPBY('packing_inhouse_size_detail2.size_id')
                    ->get();
                    
                    if(count($packingData) > 0)
                    {
                        foreach($packingData as $row1)
                        {
                               
                               $cpk = DB::table('carton_packing_inhouse_size_detail2')->select(DB::raw('sum(carton_packing_inhouse_size_detail2.size_qty) as carton_pack_qty'))
                               ->join('carton_packing_inhouse_master','carton_packing_inhouse_master.cpki_code','=','carton_packing_inhouse_size_detail2.cpki_code')
                               ->where('carton_packing_inhouse_size_detail2.sales_order_no','=', $row->tr_code)
                               ->where('carton_packing_inhouse_size_detail2.color_id','=', $row1->color_id)
                               ->where('carton_packing_inhouse_master.endflag','=', 1)
                               ->where('carton_packing_inhouse_size_detail2.size_id','=', $row1->size_id)
                               ->where('carton_packing_inhouse_size_detail2.cpki_date','<=', date('Y-m-d'))
                               ->get();
                               
                               $tpk = DB::table('transfer_packing_inhouse_size_detail2')->select(DB::raw('sum(transfer_packing_inhouse_size_detail2.size_qty) as transfer_qty'))
                               ->where('transfer_packing_inhouse_size_detail2.sales_order_no','=', $row->tr_code)
                               ->where('transfer_packing_inhouse_size_detail2.color_id','=', $row1->color_id)
                               ->where('transfer_packing_inhouse_size_detail2.size_id','=', $row1->size_id)
                               ->where('usedFlag','=', 1)
                               ->where('tpki_date','<=', date('Y-m-d'))
                               ->get();
                               
                               
                               $ltk = DB::table('loc_transfer_packing_inhouse_size_detail2')->select(DB::raw('sum(loc_transfer_packing_inhouse_size_detail2.size_qty) as loc_transfer_qty'))
                               ->where('sales_order_no','=', $row->tr_code)
                               ->where('color_id','=', $row1->color_id)
                               ->where('size_id','=', $row1->size_id)
                              ->where('ltpki_date','<=', date('Y-m-d'))
                               ->get();
              
               
                           $salesCostingData = DB::table('sales_order_costing_master')->select('*')->where('sales_order_no', $row->tr_code)->first();
                           
                           $profit_value=  ($salesCostingData->order_rate - $salesCostingData->total_cost_value);
                            
                           $final_rate = $salesCostingData->inr_rate - $profit_value;
                           
                           $sizeData = DB::table('size_detail')->select('size_name')->where('size_id', $row1->size_id)->first();
                           
                           $pck_qty = isset($row1->packing_grn_qty) ? $row1->packing_grn_qty : 0;
                           $cpk_qty = isset($cpk[0]->carton_pack_qty) ? $cpk[0]->carton_pack_qty : 0;
                           $tpk_qty = isset($tpk[0]->transfer_qty) ? $tpk[0]->transfer_qty : 0;
                           $ltk_qty = isset($ltk[0]->loc_transfer_qty) ? $ltk[0]->loc_transfer_qty : 0;
                           
                           $stockQty =($pck_qty - $cpk_qty - $tpk_qty);
                           $Value = $stockQty * ($row->order_rate);
                           if($stockQty > 0)
                           {
                            DB::table('temp_fg_stock_report_data')->insert([
                                    "pki_date"=>$row1->pki_date,
                                    "cpki_date"=>$row1->pki_date,
                                    "tpki_date"=>$row1->pki_date,
                                    "ltpki_date"=>$row1->pki_date,
                                    "Ac_name"=>$row1->Ac_name,
                                    "sales_order_no"=>$row->tr_code,
                                    "job_status_name"=>$row->job_status_name,
                                    "job_status_id"=>$row->job_status_id,
                                    "brand_name"=>$row->brand_name,
                                    "mainstyle_name"=>$row1->mainstyle_name,
                                    "style_no"=>$row1->style_no,
                                    "color_name"=>$row1->color_name,
                                    "imagePath"=>"",
                                    "size_name"=>isset($sizeData->size_name) ? $sizeData->size_name : "",
                                    "packing_grn_qty"=>$row1->packing_grn_qty,
                                    "carton_pack_qty"=>$cpk_qty,
                                    "transfer_qty"=>$tpk_qty,
                                    "loc_transfer_qty"=>$ltk_qty,
                                    "loc_rec_transfer_qty"=>"",
                                    "stockQty"=>$stockQty,
                                    "order_rate"=>$final_rate,
                                    "Value"=>$Value, 
                                    "limit_count" => $request->start."-".$request->end
                                ]);
                                
                                $insertDataCount1 = DB::select("SELECT count(*) as total_count FROM temp_fg_stock_report_data WHERE limit_count ='".$request->start."-".$request->end."'");
                                $insertDataCount = DB::select("SELECT count(*) as total_count FROM temp_fg_stock_log WHERE limit_count ='".$request->start."-".$request->end."'");
                                if($insertDataCount[0]->total_count == 0)
                                {
                                    DB::table('temp_fg_stock_log')->insert([
                                        "limit_count"=>$request->start."-".$request->end,
                                        "no_of_records"=>$insertDataCount1[0]->total_count
                                    ]); 
                                }
                           }
                        }
                    }
                    
              }
              return response()->json(['loop' => $request->loop, 'start'=>$request->start,'noOfRecords'=>count($BuyerData)]);  
            
    }
    
    public function DumpFGStockReport1(Request $request)
    {
        $InsertSizeData = DB::select('call sp_FGStockDataByTwo');
        return 1;   
    }
    
    public function TempLastRecord()
    {
        
        $tempLastRecordsData = DB::select("SELECT limit_count FROM temp_fg_stock_log WHERE logId =  (SELECT MAX(logId) FROM temp_fg_stock_log)");
        if(count($tempLastRecordsData) > 0)
        {
            $limit_count = $tempLastRecordsData[0]->limit_count;
        }
        else
        {
            $limit_count = "";
        }
        return response()->json(['limit_count' => $limit_count]);  
    }
    
    public function DeleteTempLastRecord(Request $request)
    {
       DB::table('temp_fg_stock_report_data')->where('limit_count', $request->limit_count)->delete();
       DB::table('temp_fg_stock_log')->where('limit_count', $request->limit_count)->delete();
       return 1;
    }
    
    public function TempFGStockReport(Request $request)
    {
       
        if ($request->ajax()) 
        { 
           
            $FinishedGoodsStock = DB::select("SELECT FG.entry_date, FG.`ac_name`,job_status_master.job_status_name, brand_master.brand_name, FG.`sales_order_no`, FG.`mainstyle_name`, FG.`substyle_name`, FG.`fg_name`, FG.`style_no`, 
                FG.`style_description`, FG.`color_name`, FG.`size_name`,ifnull(sum(FG.`size_qty`),0)  as packing_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
                inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                inner join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                inner join brand_master On brand_master.brand_id = buyer_purchse_order_master.brand_id
                where FG.data_type_id=1 group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
   
            
            return Datatables::of($FinishedGoodsStock)
          
          ->addColumn('fob_rate',function ($row) {
              //DB::enableQueryLog();
               $salesCostingData = DB::table('sales_order_costing_master')->select('*')->where('sales_order_no', $row->sales_order_no)->first();
              // dd(DB::getQueryLog());
               $profit_value =  ($salesCostingData->order_rate - $salesCostingData->total_cost_value);
               $order_rate1 = $salesCostingData->inr_rate - $profit_value;
    
                return $order_rate1;
           })
          ->addColumn('stock',function ($row) {
    
             $stock =($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    
             return $stock;
           })
          ->addColumn('Value',function ($row) {
              
               $salesCostingData = DB::table('sales_order_costing_master')->select('*')->where('sales_order_no', $row->sales_order_no)->first();
               $profit_value=  ($salesCostingData->order_rate - $salesCostingData->total_cost_value);
               $final_rate = $salesCostingData->inr_rate - $profit_value;
               
               $Value =($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($final_rate);
    
             return $Value;
           })
           
             ->rawColumns(['fob_rate','stock','Value'])
             
             ->make(true);
    
            }
            
          return view('FGStockReport');
        
    }
    
    public function loadERPDashboardData(Request $request)
    {
            if($request->table_head == 1)
            {
                $head = 'Order Booking';
                $head1 = 'Order Booking';
                $style = 'style="color: #3d0cd5;"';
            }
            else if($request->table_head == 2)
            {
                $head = 'Sales';    
                $head1 = 'Sales';       
                $style = 'style="color: goldenrod;"';
            }
            else if($request->table_head == 3)
            {
                $head = 'OCR'; 
                $head1 = 'OCR';        
                $style = 'style="color: #da2076c7;"';        
            }
            else if($request->table_head == 4)
            {
                $head = 'Fabric';  
                $head1 = 'Fabric';     
                $style = 'style="color: #70da20c7;"';           
            }
            else if($request->table_head == 5)
            {
                $head = 'Trims'; 
                $head1 = 'Trims';   
                $style = 'style="color: #ed6e13c7;"';             
            }
            else if($request->table_head == 6)
            {
                $head = 'Stitching'; 
                $head1 = 'Cutting-Inhouse'; 
                $style = 'style="color: #523ccfc7;"';               
            }
            else if($request->table_head == 8)
            {
                $head = 'Open Order Status'; 
                $head1 = 'Open Order Status'; 
                $style = 'style="color: #cfa23cc7;"';               
            }
            else if($request->table_head == 10)
            {
                $key1 = 'Fabric - Moving Value'; 
                $key2 = 'Fabric - Non - Moving Quantity';  
                
                $key3 = 'Trims - Moving Value'; 
                $key4 = 'Trims - Non - Moving Value';  
                
                $MovingFabRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key1."'");
                $NonMovingFabRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key2."'");
        
                $MovingTrimRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key3."'");
                $NonMovingTrimRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key4."'");
        
                
            }
            else if($request->table_head == 7)
            {
                $head = 'Packing'; 
                $head1 = 'Packing';
                $style = 'style="color: #3c92cfc7;"';               
            }
            else
            {
                $key1 = ''; 
                $key2 = '';  
            }
            
        $MovingRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key1."'");
        $NonMovingRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key2."'");
        
        $moving = isset($MovingRecordsData[0]->today) ? $MovingRecordsData[0]->today : 0;
        $non_moving = isset($NonMovingRecordsData[0]->today) ? $NonMovingRecordsData[0]->today : 0;
        $total = $moving + $non_moving;
        return response()->json(['moving' => $moving,'non_moving'=>$non_moving,'total'=>$total]);  
    }
         
    public function loadERPInventoryData(Request $request)
    {
       
        $key1 = 'Fabric - Moving Value'; 
        $key2 = 'Fabric - Non - Moving Value';  
        
        $key3 = 'Trims - Moving Value'; 
        $key4 = 'Trims - Non - Moving Value';  
        
        $key5 = 'FG - Moving Value'; 
        $key6 = 'FG - Non - Moving Value'; 
        
        $key7 = 'WIP -  Value'; 
        
        $key8 = 'Balance To Produce Pcs'; 
        $key9 = 'Balance To Produce Min'; 
        
        $key10 = 'Quantity'; 
        $key11 = 'Minutes'; 
        $key12 = 'Value'; 
        
        
        $MovingFabRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key1."'");
        $NonMovingFabRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key2."'");

        $MovingTrimRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key3."'");
        $NonMovingTrimRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key4."'");
   
        $MovingFGRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key5."'");
        $NonMovingFGRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key6."'");
   
        $MovingWIPRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head='$request->table_head' AND key_Indicators='".$key7."'");


        $openOrderPCSRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=8 AND key_Indicators='".$key8."'");
        $openOrderMinRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=8 AND key_Indicators='".$key9."'");
   

        $orderBookingQtyRecordsData = DB::select("SELECT month_to_date FROM  temp_order_sales_dashboard WHERE table_head=1 AND key_Indicators='Quantity'");
        $orderBookingMinRecordsData = DB::select("SELECT month_to_date FROM  temp_order_sales_dashboard WHERE table_head=1 AND key_Indicators='Minutes'");
        $orderBookingValueRecordsData = DB::select("SELECT month_to_date FROM  temp_order_sales_dashboard WHERE table_head=1 AND key_Indicators='Value'");
    
        $salesQtyRecordsData = DB::select("SELECT month_to_date FROM  temp_order_sales_dashboard WHERE table_head=2 AND key_Indicators='Quantity'");
        $salesBookingMinRecordsData = DB::select("SELECT month_to_date FROM  temp_order_sales_dashboard WHERE table_head=2 AND key_Indicators='Minutes'");
        $salesBookingValueRecordsData = DB::select("SELECT month_to_date FROM  temp_order_sales_dashboard WHERE table_head=2 AND key_Indicators='Value'");
   
        $fabmoving = isset($MovingFabRecordsData[0]->today) ? $MovingFabRecordsData[0]->today : 0;
        $fabnon_moving = isset($NonMovingFabRecordsData[0]->today) ? $NonMovingFabRecordsData[0]->today : 0;
        $fabtotal = $fabmoving + $fabnon_moving;
        
        $trimmoving = isset($MovingTrimRecordsData[0]->today) ? $MovingTrimRecordsData[0]->today : 0;
        $trimnon_moving = isset($NonMovingTrimRecordsData[0]->today) ? $NonMovingTrimRecordsData[0]->today : 0;
        $trimtotal = $trimmoving + $trimnon_moving;
        
        $fgmoving = isset($MovingFGRecordsData[0]->today) ? $MovingFGRecordsData[0]->today : 0;
        $fgnon_moving = isset($NonMovingFGRecordsData[0]->today) ? $NonMovingFGRecordsData[0]->today : 0;
        $fgtotal = $fgmoving + $fgnon_moving;
        
        $WIPmoving = isset($MovingWIPRecordsData[0]->today) ? $MovingWIPRecordsData[0]->today : 0;
        $WIPtotal = $WIPmoving;
        
        
        $openOrderPCS = isset($openOrderPCSRecordsData[0]->today) ? $openOrderPCSRecordsData[0]->today : 0;
        $openOrderMin = isset($openOrderMinRecordsData[0]->today) ? $openOrderMinRecordsData[0]->today : 0;
        $openOrdertotal = 0.00;
        
        $bookingQty = isset($orderBookingQtyRecordsData[0]->month_to_date) ? $orderBookingQtyRecordsData[0]->month_to_date : 0;
        $bookingMin = isset($orderBookingMinRecordsData[0]->month_to_date) ? $orderBookingMinRecordsData[0]->month_to_date : 0;
        $bookingValue = isset($orderBookingValueRecordsData[0]->month_to_date) ? $orderBookingValueRecordsData[0]->month_to_date : 0;
         
        $salesBookingQty = isset($salesQtyRecordsData[0]->month_to_date) ? $salesQtyRecordsData[0]->month_to_date : 0;
        $salesBookingMin = isset($salesBookingMinRecordsData[0]->month_to_date) ? $salesBookingMinRecordsData[0]->month_to_date : 0;
        $salesBookingValue = isset($salesBookingValueRecordsData[0]->month_to_date) ? $salesBookingValueRecordsData[0]->month_to_date : 0;
        
        $totalOpenOrderData = DB::select("SELECT count(*) as total_order FROM  buyer_purchse_order_master WHERE job_status_id=1 AND delflag=0 AND og_id !=4");
        
        $totalOrder = isset($totalOpenOrderData[0]->total_order) ? $totalOpenOrderData[0]->total_order : 0;
        
        return response()->json(['salesBookingQty'=>$salesBookingQty,'salesBookingMin'=>$salesBookingMin,'salesBookingValue'=>$salesBookingValue,'totalOrder'=>$totalOrder,'fabmoving' => $fabmoving,'fabnon_moving'=>$fabnon_moving,'fabtotal'=>$fabtotal,'trimmoving'=>$trimmoving,'trimnon_moving'=>$trimnon_moving,'trimtotal'=>$trimtotal,'fgmoving'=>$fgmoving,'fgnon_moving'=>$fgnon_moving,'fgtotal'=>$fgtotal,'WIPmoving'=>$WIPmoving,'WIPnon_moving'=>"-",'WIPtotal'=>$WIPtotal,'openOrderPCS'=>$openOrderPCS,'openOrderMin'=>$openOrderMin,'openOrdertotal'=>$openOrdertotal,'bookingQty'=>$bookingQty,'bookingMin'=>$bookingMin,'bookingValue'=>$bookingValue]);  
    }
    
    public function loadERPInventoryData1()
    {
        
        $inventoryRecordsData = DB::select("SELECT * FROM  ledger_master WHERE ac_code IN(56,115,69)");
        $html = "";
        foreach($inventoryRecordsData as $row)
        {
            $inventoryData = DB::select("SELECT (sum(meter)/100000) as fabric_meter  FROM  fabric_outward_details WHERE vendorId=".$row->ac_code);
            $fabric_meter = isset($inventoryData[0]->fabric_meter) ? $inventoryData[0]->fabric_meter: 0;
            $html .= '<tr>
                         <td><b>'.$row->ac_name.'</b></td>
                         <td>'.round($fabric_meter,4).'</td>
                         <td></td>
                         <td></td>
                     </tr>';
        }
         
        return response()->json(['html' => $html]);  
    }
    
    public function loadERPProductionData(Request $request)
    {
        
        $vendorData = DB::select("SELECT * FROM  ledger_master WHERE ac_code IN(56,69,115) order BY ac_code DESC");
        $html = "";
        foreach($vendorData as $row)
        {
            $LineList=DB::select("select line_id,line_name from line_master where Ac_code='".$row->ac_code."'");
            
            $colspan = count($LineList);
            $html .= '<table class="table">
                <thead style="background: antiquewhite;">
                    <tr>
                        <th></th>
                        <th colspan="'.$colspan.'" class="text-center">'.$row->ac_name.'</th>  
                        <th></th>
                    </tr>
                    <tr>
                      <th></th>';
                    foreach($LineList as $lines)
                    {
                        $html .= '<th class="text-right">'.$lines->line_name.'</th>';
                    }
                $html .= '<th class="text-right"><b>Total</b></th>
                        </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td><b>Pieces</b></td>';
                        $totalPieces = 0;
                        foreach($LineList as $lines)
                        {
                            $piecesData = DB::select("select sum(total_qty) as  qty FROM stitching_inhouse_master 
                            WHERE vendorId=".$row->ac_code." AND line_id=".$lines->line_id." 
                            AND sti_date='".$request->curDate."'");
                            $pieces = isset($piecesData[0]->qty) ? $piecesData[0]->qty : 0;
                            $html .='<td class="text-right">'.money_format('%!.0n',$pieces).'</td>';
                            
                            $totalPieces += $pieces;
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$totalPieces).'</b></td>
                    </tr>
                    <tr>
                        <td><b>SAM</b></td>';
                        $overallSAM = 0;
                        foreach($LineList as $lines)
                        {
                             $qtyData = DB::select("select sum(total_qty) as  qty FROM stitching_inhouse_master WHERE vendorId=".$row->ac_code." 
                             AND line_id=".$lines->line_id." AND sti_date = '".$request->curDate."'");
                             $StichingData = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min
                                from stitching_inhouse_size_detail2
                                INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                where stitching_inhouse_size_detail2.vendorId='".$row->ac_code."' and stitching_inhouse_size_detail2.line_id='".$lines->line_id."' and 
                                stitching_inhouse_size_detail2.sti_date = '".$request->curDate."'");
                           // dd(DB::getQueryLog());
                           
                            $qty = isset($qtyData[0]->qty) ? $qtyData[0]->qty : 0;
                            if(count($StichingData) > 0)
                            {
                                $totalPMin = $StichingData[0]->total_min;
                                
                            }
                            else
                            {
                                $totalPMin = 0;
                                
                            }
                            if($totalPMin > 0 && $qty > 0)
                            {
                                $avgSAM = $totalPMin/$qty;
                            }
                            else
                            {
                                $avgSAM = 0;
                            }
                            $html .='<td class="text-right">'.money_format('%!.0n',round($avgSAM,4)).'</td>';
                           
                            $overallSAM += $avgSAM;
                        }
                        if($overallSAM > 0 && count($LineList) > 0)
                        { 
                            $totalSAM = round($overallSAM/count($LineList),2);
                        }
                        else
                        {
                            $totalSAM = 0;
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$totalSAM).'</b></td>
                    </tr>
                    <tr>
                        <td><b>Operators</b></td>';
                        $overallWorker = 0;
                        foreach($LineList as $lines)
                        {
                            $workerData = DB::select("select sum(total_workers) as total_workers FROM stitching_inhouse_master 
                            WHERE vendorId=".$row->ac_code." AND line_id=".$lines->line_id." AND sti_date = '".$request->curDate."'");
                            $total_worker = isset($workerData[0]->total_workers) ? $workerData[0]->total_workers : 0;
                            $html .='<td class="text-right">'.money_format('%!.0n',$total_worker).'</td>';
                            
                            $overallWorker += $total_worker;
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$overallWorker).'</b></td>
                    </tr>
                    <tr>
                        <td><b>Total Min Prod</b></td>';
                        $overallPMin = 0;
                        foreach($LineList as $lines)
                        { 
                            $minData = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                from stitching_inhouse_size_detail2
                                INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                where stitching_inhouse_size_detail2.vendorId='".$row->ac_code."' and stitching_inhouse_size_detail2.line_id='".$lines->line_id."' and 
                                stitching_inhouse_size_detail2.sti_date = '".$request->curDate."'");
                          
                            if(count($minData) > 0)
                            {
                                $totalPMin = $minData[0]->total_min;
                                
                            }
                            else
                            {
                                $totalPMin = 0;
                                
                            }
                            $overallPMin += $totalPMin;
                            $html .='<td class="text-right">'.money_format('%!.0n',$totalPMin).'</td>';
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$overallPMin).'</b></td>
                    </tr>
                    <tr>
                        <td><b>Min Available</b></td>';
                        $overallMinAvaliable = 0;
                        foreach($LineList as $lines)
                        {
                            $minAvalData = DB::select("select sum(total_workers) as total_workers FROM stitching_inhouse_master 
                            WHERE vendorId=".$row->ac_code." AND line_id=".$lines->line_id." AND sti_date = '".$request->curDate."'");
                            $avaliable_min = isset($minAvalData[0]->total_workers) ? $minAvalData[0]->total_workers : 0;
                            $html .='<td class="text-right">'.money_format('%!.0n',($avaliable_min*480)).'</td>';
                            
                            $overallMinAvaliable += ($avaliable_min*480);
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$overallMinAvaliable).'</b></td>
                    </tr>
                    <tr>
                        <td><b>Efficiency%</b></td>';
                        $overEffi = 0;
                        foreach($LineList as $lines)
                        {
                            $minData = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                from stitching_inhouse_size_detail2
                                INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                where stitching_inhouse_size_detail2.vendorId='".$row->ac_code."' and 
                                stitching_inhouse_size_detail2.line_id='".$lines->line_id."' and stitching_inhouse_size_detail2.sti_date = '".$request->curDate."'");
                          
                            $workerData = DB::select("select sum(total_workers) as total_workers FROM stitching_inhouse_master 
                            WHERE vendorId=".$row->ac_code." AND line_id=".$lines->line_id." AND sti_date = '".$request->curDate."'");
                            
                            $total_worker = isset($workerData[0]->total_workers) ? $workerData[0]->total_workers : 0;
                            $totalPMin = isset($minData[0]->total_min) ? $minData[0]->total_min : 0;
                            
                            if($total_worker > 0 && $totalPMin > 0)
                            {
                                $TotalOperator = money_format('%!.0n',round((($totalPMin)/($total_worker * 480)),2) * 100);
                            }
                            else
                            {
                                $TotalOperator = 0;
                                
                            }
                            $overEffi += $TotalOperator;
                            $html .='<td class="text-right">'.money_format('%!.0n',$TotalOperator).'</td>';
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$overEffi).'</b></td>
                    </tr>
                    <tr>
                        <td><b>DHU%</b></td>';
                        $overallDHU = 0;
                        foreach($LineList as $lines)
                        {
                            
                            $defectQtyData = DB::select("select ifnull(sum(dhu_details.defect_qty),0) as defect_qty FROM dhu_details 
                                LEFT JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code
                                WHERE dhu_master.vendorId=".$row->ac_code." AND dhu_master.line_no = ".$lines->line_id."
                                AND dhu_master.dhu_date = '".$request->curDate."'");
                                
                            $passData = DB::select("select ifnull(SUM(size_qty_total),0) as pass_qty FROM qcstitching_inhouse_detail 
                                                    WHERE qcstitching_inhouse_detail.vendorId=".$row->ac_code." 
                                                    AND qcsti_date='".$request->curDate."' AND line_id=".$lines->line_id);    
                            
                            $rejectData = DB::select("select ifnull(SUM(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail 
                                            WHERE qcstitching_inhouse_reject_detail.vendorId=".$row->ac_code." 
                                            AND qcsti_date='".$request->curDate."' AND line_id=".$lines->line_id);    
                                 
                           
                            $reje =  isset($rejectData[0]->reject_qty) ? $rejectData[0]->reject_qty : 0; 
                            $pass =  isset($passData[0]->pass_qty) ? $passData[0]->pass_qty : 0;
                            $deft =  isset($defectQtyData[0]->defect_qty) ? $defectQtyData[0]->defect_qty : 0; 
                            if(($deft + $reje) > 0 && ($pass + $deft + $reje) > 0)
                            {
                                $dhu = round(($deft + $reje)/($pass + $deft + $reje) * 100,2);   
                            }
                            else
                            {
                                $dhu = 0;
                            }
                            
                            $overallDHU += $dhu;        
                            $html .='<td class="text-right">'.money_format('%!.0n',$dhu).'</td>';
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$overallDHU).'</b></td>
                    </tr>
                    <tr>
                        <td><b>Rejection%</b></td>';
                        $overallrejectDHU = 0;
                        foreach($LineList as $lines)
                        {
                            $defectQtyData = DB::select("select ifnull(sum(dhu_details.defect_qty),0) as defect_qty FROM dhu_details 
                                LEFT JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code
                                WHERE dhu_master.vendorId=".$row->ac_code." AND dhu_master.line_no = ".$lines->line_id."
                                AND dhu_master.dhu_date = '".$request->curDate."'");
                                
                            $passData = DB::select("select ifnull(SUM(size_qty_total),0) as pass_qty FROM qcstitching_inhouse_detail 
                                                    WHERE qcstitching_inhouse_detail.vendorId=".$row->ac_code." 
                                                    AND qcsti_date='".$request->curDate."' AND line_id=".$lines->line_id);    
                            
                            $rejectData = DB::select("select ifnull(SUM(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail 
                                            WHERE qcstitching_inhouse_reject_detail.vendorId=".$row->ac_code." 
                                            AND qcsti_date='".$request->curDate."' AND line_id=".$lines->line_id);    
                                 
                           
                            $reje =  isset($rejectData[0]->reject_qty) ? $rejectData[0]->reject_qty : 0; 
                            $pass =  isset($passData[0]->pass_qty) ? $passData[0]->pass_qty : 0;
                            $deft =  isset($defectQtyData[0]->defect_qty) ? $defectQtyData[0]->defect_qty : 0; 
                            if(($reje) > 0 && ($pass + $deft + $reje) > 0)
                            {
                                $rejected_dhu = round(($reje)/($pass + $deft + $reje) * 100,2);   
                            }
                            else
                            {
                                $rejected_dhu = 0;
                            } 
                            $html .='<td class="text-right">'.money_format('%!.0n',$rejected_dhu).'</td>';
                            
                            $overallrejectDHU += $rejected_dhu;
                        }
                        $html .='<td class="text-right"><b>'.money_format('%!.0n',$overallrejectDHU).'</b></td>
                    </tr>'; 
                $html .='</tbody>
             </table></br></br>';
        }
         
        return response()->json(['html' => $html]);  
    }
    
    public function loadBookingSummary(Request $request)
    {
          $job_status_id = $request->job_status_id;
          
          $fromDate = date('Y-m-01');
          $toDate = date('Y-m-t');
     
          $MonthData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty, order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(1,3) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(1,2) 
            AND buyer_purchse_order_master.order_received_date BETWEEN '".$fromDate."' AND '".$toDate."' order by buyer_purchse_order_master.tr_date DESC");
            
             
                    
            //dd(DB::getQueryLog());
            $html = ""; 
            $month_countKDPL = 0;
            $month_totalOrderQty = 0;
            $month_totalLakhMin = 0;
            $month_totalOrderValue = 0;  
            $month_totalCMOHP = 0;
            $month_totalCMOHPFOB = 0;
            $monthTotalSam = 0;
            $month_CMOHP_value = 0;
            $month_CMOHP_per = 0;
            $monthOrderRate = 0;
            $monthTotalcmohpValue1 = 0;
            
            foreach($MonthData as $row)    
            {
                 if($row->production_value > 0 && $row->sam > 0)
                 {
                    $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
                 }
                 else
                 {
                    $cm_sam = 0;
                 }
                 
                 $profit_value=0.0;
                 $profit_value=  ($row->order_rate - $row->total_cost_value);
                 $cmd = $row->production_value+$row->other_value+$profit_value;
                 
                 if($cmd > 0 && $row->total_qty > 0)
                 {
                    $CMOHP_per = (($cmd)/$row->total_qty)*100;
                 }
                 else
                 {
                    $CMOHP_per = 0;
                 }
                $month_CMOHP_per += $CMOHP_per;
                $month_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
                $month_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
                $month_totalOrderQty += $row->total_qty;
                $month_totalLakhMin += round(($row->total_qty * $row->sam)/100000,2);
                $month_totalOrderValue += $row->order_value;
                $monthOrderRate += $row->order_rate;
                $monthTotalSam += $row->sam; 
                $monthTotalcmohpValue1 += round(((($month_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
                $month_totalCMOHPFOB += (round(((($month_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
                $month_countKDPL++;
        }
        if($monthTotalcmohpValue1 > 0 && $month_totalLakhMin > 0)
        { 
            $month_totalCMOHP = $monthTotalcmohpValue1/$month_totalLakhMin; 
        }
        else
        {
            $month_totalCMOHP = 0;
        }
        
        $YearData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(1) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(1,2) AND buyer_purchse_order_master.order_received_date  between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) order by buyer_purchse_order_master.tr_date DESC");
            
            //dd(DB::getQueryLog());
            $html = ""; 
            $year_countKDPL = 0;
            $year_totalOrderQty = 0;
            $year_totalLakhMin = 0;
            $year_totalOrderValue = 0;  
            $year_totalCMOHP = 0;
            $year_totalCMOHPFOB = 0;
            $yearTotalSam = 0;
            $year_CMOHP_value = 0;
            $year_CMOHP_per = 0;
            $yearOrderRate = 0;
            $yearTotalcmohpValue1 = 0;
            
            foreach($YearData as $row)    
            {
                 if($row->production_value > 0 && $row->sam > 0)
                 {
                    $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
                 }
                 else
                 {
                    $cm_sam = 0;
                 }
                 
                 $profit_value=0.0;
                 $profit_value=  ($row->order_rate - $row->total_cost_value);
                 $cmd = $row->production_value+$row->other_value+$profit_value;
                 
                 if($cmd > 0 && $row->total_qty > 0)
                 {
                    $CMOHP_per = (($cmd)/$row->total_qty)*100;
                 }
                 else
                 {
                    $CMOHP_per = 0;
                 }
                 
                $year_totalOrderQty += $row->total_qty;
                $year_totalLakhMin += round(($row->total_qty * $row->sam)/100000,2);
                $year_totalOrderValue += $row->order_value; 
                $yearOrderRate += $row->order_rate;
                
                $year_CMOHP_per += $CMOHP_per;
                $year_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
                $year_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
                $yearTotalcmohpValue1 += round(((($year_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
                $year_totalCMOHPFOB += (round(((($year_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
                $yearTotalSam += $row->sam; 
                $year_countKDPL++;
        }
        
        if($yearTotalcmohpValue1 > 0 && $year_totalLakhMin > 0)
        { 
            $year_totalCMOHP = $yearTotalcmohpValue1/$year_totalLakhMin;
        }
        else
        {
            $year_totalCMOHP = 0;
        }  
        
        $openYearData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(1) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(1) AND buyer_purchse_order_master.order_received_date  between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) order by buyer_purchse_order_master.tr_date DESC");
            
        $closeYearData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(1) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(2) AND buyer_purchse_order_master.order_received_date  between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) order by buyer_purchse_order_master.tr_date DESC");
       
        $yearOpenStatusOrderQty = 0;
        $yearOpenStatusLakhMin = 0;
        $yearOpenStatusOrderValue = 0;
        $yearOpenStatusCMOHP = 0;
        $year_OpenStatus_CMOHP_value = 0;
        $yearOpenStatusTotalSam = 0;
        $yearOpenStatusOrderRate = 0; 
        $yearOpenStatus_CMOHP_per = 0;
        $year_OpenStatusCMOHPFOB = 0;
        $yearOpenStatus_CMOHPValue1 = 0;
        
        foreach($openYearData as $row)
        {
             if($row->production_value > 0 && $row->sam > 0)
             {
                $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
             }
             else
             {
                $cm_sam = 0;
             }
             
             $profit_value=0.0;
             $profit_value=  ($row->order_rate - $row->total_cost_value);
             $cmd = $row->production_value+$row->other_value+$profit_value;
             
             if($cmd > 0 && $row->total_qty > 0)
             {
                $CMOHP_per = (($cmd)/$row->total_qty)*100;
             }
             else
             {
                $CMOHP_per = 0;
             }
             
            $yearOpenStatusOrderRate += $row->order_rate;
            
            $yearOpenStatus_CMOHP_per += $CMOHP_per;
            $year_OpenStatus_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
            $year_OpenStatus_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
            $yearOpenStatusTotalSam += $row->sam; 
                
            $yearOpenStatusOrderQty += $row->total_qty;
            $yearOpenStatusLakhMin += round(($row->total_qty * $row->sam)/100000,2);
            $yearOpenStatusOrderValue += $row->order_value;
            $yearOpenStatus_CMOHPValue1 += round(((($year_OpenStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
            $year_OpenStatusCMOHPFOB += (round(((($year_OpenStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
                
        } 
        
        $open_count = count($openYearData);  
        $close_count = count($closeYearData); 
        
         
        if($yearOpenStatus_CMOHPValue1 > 0 && $yearOpenStatusLakhMin > 0)
        { 
            $yearOpenStatusCMOHP = $yearOpenStatus_CMOHPValue1/$yearOpenStatusLakhMin;
        }
        else
        {
            $yearOpenStatusCMOHP = 0;
        } 
         
               
        $yearCloseStatusOrderQty = 0;
        $yearCloseStatusLakhMin = 0;
        $yearCloseStatusOrderValue = 0;
        $yearCloseStatusCMOHP = 0;
        $year_CloseStatus_CMOHP_value = 0;
        $yearCloseStatusTotalSam = 0;
        $yearCloseStatusOrderRate = 0; 
        $yearCloseStatus_CMOHP_per = 0;
        $year_CloseStatusCMOHPFOB = 0;
        $yearCloseStatusCMOHPValue1 = 0;
        
        foreach($closeYearData as $row)
        {
             if($row->production_value > 0 && $row->sam > 0)
             {
                $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
             }
             else
             {
                $cm_sam = 0;
             }
             
             $profit_value=0.0;
             $profit_value=  ($row->order_rate - $row->total_cost_value);
             $cmd = $row->production_value+$row->other_value+$profit_value;
             
             if($cmd > 0 && $row->total_qty > 0)
             {
                $CMOHP_per = (($cmd)/$row->total_qty)*100;
             }
             else
             {
                $CMOHP_per = 0;
             }
              
            $yearCloseStatusOrderRate += $row->order_rate;
            
            $yearCloseStatus_CMOHP_per += $CMOHP_per;
            $year_CloseStatus_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
            $year_CloseStatus_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
            $yearCloseStatusTotalSam += $row->sam; 
                
            $yearCloseStatusOrderQty += $row->total_qty;
            $yearCloseStatusLakhMin += round(($row->total_qty * $row->sam)/100000,2);
            $yearCloseStatusOrderValue += $row->order_value;
            $yearCloseStatusCMOHPValue1 += round(((($year_CloseStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
            $year_CloseStatusCMOHPFOB += (round(((($year_CloseStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
        }
           
        
        if($year_CloseStatus_CMOHP_value > 0 && $yearCloseStatusLakhMin > 0)
        { 
            $yearCloseStatusCMOHP = $year_CloseStatus_CMOHP_value/$yearCloseStatusLakhMin;
        }
        else
        {
            $yearCloseStatusCMOHP = 0;
        } 
         
        
        $html .='<tr>
                    <th style="white-space:nowrap;text-align:center;">1</th>
                    <th style="white-space:nowrap">No. Of Orders</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/1" target="_blank">'.$month_countKDPL.'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/1" target="_blank">'.$year_countKDPL.'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/1" target="_blank">'.$open_count.'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/1" target="_blank">'.$close_count.'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">2</th>
                    <th style="white-space:nowrap">Order Qty (Lakh)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/1" target="_blank">'.(number_format((float)($month_totalOrderQty/100000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/1" target="_blank">'.(number_format((float)($year_totalOrderQty/100000), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/1" target="_blank">'.(number_format((float)($yearOpenStatusOrderQty/100000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/1" target="_blank">'.(number_format((float)($yearCloseStatusOrderQty/100000), 2, '.', '')).'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">3</th>
                    <th style="white-space:nowrap">Order Value (Cr.)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/1" target="_blank">'.(number_format((float)($month_totalOrderValue/10000000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/1" target="_blank">'.(number_format((float)($year_totalOrderValue/10000000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/1" target="_blank">'.(number_format((float)($yearOpenStatusOrderValue/10000000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/1" target="_blank">'.(number_format((float)($yearCloseStatusOrderValue/10000000), 2, '.', '')).'</a></td>  
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">4</th>
                    <th style="white-space:nowrap">Min (Lakh)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/1" target="_blank">'.(number_format((float)($month_totalLakhMin), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/1" target="_blank">'.(number_format((float)($year_totalLakhMin), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/1" target="_blank">'.(number_format((float)($yearOpenStatusLakhMin), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/1" target="_blank">'.(number_format((float)($yearCloseStatusLakhMin), 2, '.', '')).'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">5</th>
                    <th style="white-space:nowrap">CMOHP (₹/min)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/1" target="_blank">'.(number_format((float)($month_totalCMOHP), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/1" target="_blank">'.(number_format((float)($year_totalCMOHP), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/1" target="_blank">'.(number_format((float)($yearOpenStatusCMOHP), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/1" target="_blank">'.(number_format((float)($yearCloseStatusCMOHP), 2, '.', '')).'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">6</th>
                    <th style="white-space:nowrap">CMOHP/FOB (%)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/1" target="_blank">'.(number_format((float)($month_totalCMOHPFOB), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/1" target="_blank">'.(number_format((float)($year_totalCMOHPFOB), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/1" target="_blank">'.(number_format((float)($year_OpenStatusCMOHPFOB), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/1" target="_blank">'.(number_format((float)($year_CloseStatusCMOHPFOB), 2, '.', '')).'</a></td> 
                 </tr>';
                 
        return response()->json(['html' => $html]);  
    }
    
        
    public function loadJobWorkBookingSummary(Request $request)
    {
          $job_status_id = $request->job_status_id;
          
          $MonthData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(3) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(1,2) AND MONTH(buyer_purchse_order_master.order_received_date)=MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_received_date)=YEAR(CURRENT_DATE()) order by buyer_purchse_order_master.tr_date DESC");
            
            //dd(DB::getQueryLog());
            $html = ""; 
            $month_countKDPL = 0;
            $month_totalOrderQty = 0;
            $month_totalLakhMin = 0;
            $month_totalOrderValue = 0;  
            $month_totalCMOHP = 0;
            $month_totalCMOHPFOB = 0;
            $monthTotalSam = 0;
            $month_CMOHP_value = 0;
            $month_CMOHP_per = 0;
            $monthOrderRate = 0;
            $monthTotalcmohpValue1 = 0;
            
            foreach($MonthData as $row)    
            {
                 if($row->production_value > 0 && $row->sam > 0)
                 {
                    $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
                 }
                 else
                 {
                    $cm_sam = 0;
                 }
                 
                 $profit_value=0.0;
                 $profit_value=  ($row->order_rate - $row->total_cost_value);
                 $cmd = $row->production_value+$row->other_value+$profit_value;
                 
                 if($cmd > 0 && $row->total_qty > 0)
                 {
                    $CMOHP_per = (($cmd)/$row->total_qty)*100;
                 }
                 else
                 {
                    $CMOHP_per = 0;
                 }
                $month_CMOHP_per += $CMOHP_per;
                $month_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
                $month_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
                $month_totalOrderQty += $row->total_qty;
                $month_totalLakhMin += round(($row->total_qty * $row->sam)/100000,2);
                $month_totalOrderValue += $row->order_value;
                $monthOrderRate += $row->order_rate;
                $monthTotalSam += $row->sam; 
                $monthTotalcmohpValue1 += round(((($month_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
                $month_totalCMOHPFOB += (round(((($month_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
                $month_countKDPL++;
        }
        if($monthTotalcmohpValue1 > 0 && $month_totalLakhMin > 0)
        { 
            $month_totalCMOHP = $monthTotalcmohpValue1/$month_totalLakhMin; 
        }
        else
        {
            $month_totalCMOHP = 0;
        }
        
        $YearData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(3) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(1,2) AND buyer_purchse_order_master.order_received_date  between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) order by buyer_purchse_order_master.tr_date DESC");
            
            //dd(DB::getQueryLog());
            $html = ""; 
            $year_countKDPL = 0;
            $year_totalOrderQty = 0;
            $year_totalLakhMin = 0;
            $year_totalOrderValue = 0;  
            $year_totalCMOHP = 0;
            $year_totalCMOHPFOB = 0;
            $yearTotalSam = 0;
            $year_CMOHP_value = 0;
            $year_CMOHP_per = 0;
            $yearOrderRate = 0;
            $yearTotalcmohpValue1 = 0;
            
            foreach($YearData as $row)    
            {
                 if($row->production_value > 0 && $row->sam > 0)
                 {
                    $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
                 }
                 else
                 {
                    $cm_sam = 0;
                 }
                 
                 $profit_value=0.0;
                 $profit_value=  ($row->order_rate - $row->total_cost_value);
                 $cmd = $row->production_value+$row->other_value+$profit_value;
                 
                 if($cmd > 0 && $row->total_qty > 0)
                 {
                    $CMOHP_per = (($cmd)/$row->total_qty)*100;
                 }
                 else
                 {
                    $CMOHP_per = 0;
                 }
                 
                $year_totalOrderQty += $row->total_qty;
                $year_totalLakhMin += round(($row->total_qty * $row->sam)/100000,2);
                $year_totalOrderValue += $row->order_value; 
                $yearOrderRate += $row->order_rate;
                
                $year_CMOHP_per += $CMOHP_per;
                $year_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
                $year_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
                $yearTotalcmohpValue1 += round(((($year_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
                $year_totalCMOHPFOB += (round(((($year_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
                $yearTotalSam += $row->sam; 
                $year_countKDPL++;
        }
        
        if($yearTotalcmohpValue1 > 0 && $year_totalLakhMin > 0)
        { 
            $year_totalCMOHP = $yearTotalcmohpValue1/$year_totalLakhMin;
        }
        else
        {
            $year_totalCMOHP = 0;
        }  
        
        $openYearData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(3) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(1) AND buyer_purchse_order_master.order_received_date  between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) order by buyer_purchse_order_master.tr_date DESC");
            
        $closeYearData = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where order_type IN(3) AND isCEO=1 AND isMarketing=1 AND og_id !=4  AND buyer_purchse_order_master.job_status_id IN(2) AND buyer_purchse_order_master.order_received_date  between (select fdate from financial_year_master where financial_year_master.fin_year_id=4) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=4) order by buyer_purchse_order_master.tr_date DESC");
       
        $yearOpenStatusOrderQty = 0;
        $yearOpenStatusLakhMin = 0;
        $yearOpenStatusOrderValue = 0;
        $yearOpenStatusCMOHP = 0;
        $year_OpenStatus_CMOHP_value = 0;
        $yearOpenStatusTotalSam = 0;
        $yearOpenStatusOrderRate = 0; 
        $yearOpenStatus_CMOHP_per = 0;
        $year_OpenStatusCMOHPFOB = 0;
        $yearOpenStatus_CMOHPValue1 = 0;
        
        foreach($openYearData as $row)
        {
             if($row->production_value > 0 && $row->sam > 0)
             {
                $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
             }
             else
             {
                $cm_sam = 0;
             }
             
             $profit_value=0.0;
             $profit_value=  ($row->order_rate - $row->total_cost_value);
             $cmd = $row->production_value+$row->other_value+$profit_value;
             
             if($cmd > 0 && $row->total_qty > 0)
             {
                $CMOHP_per = (($cmd)/$row->total_qty)*100;
             }
             else
             {
                $CMOHP_per = 0;
             }
             
            $yearOpenStatusOrderRate += $row->order_rate;
            
            $yearOpenStatus_CMOHP_per += $CMOHP_per;
            $year_OpenStatus_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
            $year_OpenStatus_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
            $yearOpenStatusTotalSam += $row->sam; 
                
            $yearOpenStatusOrderQty += $row->total_qty;
            $yearOpenStatusLakhMin += round(($row->total_qty * $row->sam)/100000,2);
            $yearOpenStatusOrderValue += $row->order_value;
            $yearOpenStatus_CMOHPValue1 += round(((($year_OpenStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
            $year_OpenStatusCMOHPFOB += (round(((($year_OpenStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
                
        } 
        
        $open_count = count($openYearData);  
        $close_count = count($closeYearData); 
        
         
        if($yearOpenStatus_CMOHPValue1 > 0 && $yearOpenStatusLakhMin > 0)
        { 
            $yearOpenStatusCMOHP = $yearOpenStatus_CMOHPValue1/$yearOpenStatusLakhMin;
        }
        else
        {
            $yearOpenStatusCMOHP = 0;
        } 
         
               
        $yearCloseStatusOrderQty = 0;
        $yearCloseStatusLakhMin = 0;
        $yearCloseStatusOrderValue = 0;
        $yearCloseStatusCMOHP = 0;
        $year_CloseStatus_CMOHP_value = 0;
        $yearCloseStatusTotalSam = 0;
        $yearCloseStatusOrderRate = 0; 
        $yearCloseStatus_CMOHP_per = 0;
        $year_CloseStatusCMOHPFOB = 0;
        $yearCloseStatusCMOHPValue1 = 0;
        
        foreach($closeYearData as $row)
        {
             if($row->production_value > 0 && $row->sam > 0)
             {
                $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
             }
             else
             {
                $cm_sam = 0;
             }
             
             $profit_value=0.0;
             $profit_value=  ($row->order_rate - $row->total_cost_value);
             $cmd = $row->production_value+$row->other_value+$profit_value;
             
             if($cmd > 0 && $row->total_qty > 0)
             {
                $CMOHP_per = (($cmd)/$row->total_qty)*100;
             }
             else
             {
                $CMOHP_per = 0;
             }
              
            $yearCloseStatusOrderRate += $row->order_rate;
            
            $yearCloseStatus_CMOHP_per += $CMOHP_per;
            $year_CloseStatus_CMOHP_value += ($row->production_value + $profit_value + $row->other_value);
            $year_CloseStatus_CMOHP_value1 = ($row->production_value + $profit_value + $row->other_value);
            $yearCloseStatusTotalSam += $row->sam; 
                
            $yearCloseStatusOrderQty += $row->total_qty;
            $yearCloseStatusLakhMin += round(($row->total_qty * $row->sam)/100000,2);
            $yearCloseStatusOrderValue += $row->order_value;
            $yearCloseStatusCMOHPValue1 += round(((($year_CloseStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2);
            $year_CloseStatusCMOHPFOB += (round(((($year_CloseStatus_CMOHP_value1/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
        }
           
        
        if($year_CloseStatus_CMOHP_value > 0 && $yearCloseStatusLakhMin > 0)
        { 
            $yearCloseStatusCMOHP = $year_CloseStatus_CMOHP_value/$yearCloseStatusLakhMin;
        }
        else
        {
            $yearCloseStatusCMOHP = 0;
        } 
         $html .='<tr>
                    <th style="white-space:nowrap;text-align:center;">1</th>
                    <th style="white-space:nowrap">No. Of Orders</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/3" target="_blank">'.$month_countKDPL.'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/3" target="_blank">'.$year_countKDPL.'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/3" target="_blank">'.$open_count.'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/3" target="_blank">'.$close_count.'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">2</th>
                    <th style="white-space:nowrap">Order Qty (Lakh)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/3" target="_blank">'.(number_format((float)($month_totalOrderQty/100000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/3" target="_blank">'.(number_format((float)($year_totalOrderQty/100000), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/3" target="_blank">'.(number_format((float)($yearOpenStatusOrderQty/100000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/3" target="_blank">'.(number_format((float)($yearCloseStatusOrderQty/100000), 2, '.', '')).'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">3</th>
                    <th style="white-space:nowrap">Order Value (Cr.)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/3" target="_blank">'.(number_format((float)($month_totalOrderValue/10000000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/3" target="_blank">'.(number_format((float)($year_totalOrderValue/10000000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/3" target="_blank">'.(number_format((float)($yearOpenStatusOrderValue/10000000), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/3" target="_blank">'.(number_format((float)($yearCloseStatusOrderValue/10000000), 2, '.', '')).'</a></td>  
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">4</th>
                    <th style="white-space:nowrap">Min (Lakh)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/3" target="_blank">'.(number_format((float)($month_totalLakhMin), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/3" target="_blank">'.(number_format((float)($year_totalLakhMin), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/3" target="_blank">'.(number_format((float)($yearOpenStatusLakhMin), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/3" target="_blank">'.(number_format((float)($yearCloseStatusLakhMin), 2, '.', '')).'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">5</th>
                    <th style="white-space:nowrap">CMOHP (₹/min)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/3" target="_blank">'.(number_format((float)($month_totalCMOHP), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/3" target="_blank">'.(number_format((float)($year_totalCMOHP), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/3" target="_blank">'.(number_format((float)($yearOpenStatusCMOHP), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/3" target="_blank">'.(number_format((float)($yearCloseStatusCMOHP), 2, '.', '')).'</a></td> 
                 </tr>
                 <tr>
                    <th style="white-space:nowrap;text-align:center;">6</th>
                    <th style="white-space:nowrap">CMOHP/FOB (%)</th>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/m/3" target="_blank">'.(number_format((float)($month_totalCMOHPFOB), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/y/3" target="_blank">'.(number_format((float)($year_totalCMOHPFOB), 2, '.', '')).'</a></td> 
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/o/3" target="_blank">'.(number_format((float)($year_OpenStatusCMOHPFOB), 2, '.', '')).'</a></td>
                    <td style="white-space:nowrap;text-align:right;"><a href="/DashboardCostingOHPDashboard/c/3" target="_blank">'.(number_format((float)($year_CloseStatusCMOHPFOB), 2, '.', '')).'</a></td> 
                 </tr>';
                 
                 
        return response()->json(['html' => $html]);  
    }
    
    public function CheckTodayBirthdayHRMS()
    {  
    //     config(['database.default' => 'hrms_database']);
 
    // //   ->select('employeemaster.employeeCode','fullName','maincompany_id','sub_company_id','branch_id','employee_status_id','emp_cat_id','misRate','rate')   
    // //DB::enableQueryLog();

    //     $data = DB::connection('hrms_database')->table('employeemaster')->select('employeemaster.dob','employeemaster.firstName','employeemaster.userProfile','employeemaster.lastName','employeemaster.fullName','department_master.dept_name') 
    //             ->leftjoin('department_master','department_master.dept_id','=','employeemaster.dept_id')
    //             ->where('dob', 'like', '%'.date('-m-d').'%')
    //             ->where('emp_cat_id', '=',2)
    //             ->get();
    //       //     dd(DB::getQueryLog());
    //     config(['database.hrms_database' => 'mysql']);
       
    //   $fullName = $data[0]->firstName." ".$data[0]->lastName;
    //   $dob_date = date('d M Y', strtotime($data[0]->dob));
    //   $profileImg = "https://hrms.kenerp.com/Employeeimages/".$data[0]->userProfile;
    //       // return response()->json($data);
    //     return response()->json(['fullName' => $fullName,'firstName' => $data[0]->firstName,'dept'=> $data[0]->dept_name,'dob_date'=>$dob_date,'profileImg'=>$profileImg]);  
    }
    
    public function GetQuntitiveInventoryReport(Request $request)
    {
        
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id = 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        

        $fDate = $Financial_Year[0]->fdate;
        $tDate = date('Y-m-d');
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $html = '<table id="tbl" class="table-condensed table-striped nowrap w-100">
                      <thead class="tablehead"> 
                      <tr style="text-align:center; white-space:nowrap">
						    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col first-col"></th>
						    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col second-col">MONTHS</th>
						    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col third-col">Units</th>'; 
						    
					        $colorCtr = 0;
                            foreach($period as $dates)
                            {  
                              $yrdata= strtotime($dates."-01");
                              $monthName = date('F', $yrdata);  
                             
						    $html .= '<th colspan="2" style="background:'.$colorArr[$colorCtr].';border-top: 3px solid black;">'.$monthName.'(₹ in lakhs)</th>';
						    
						    $colorCtr++;
                            }
                         $html .= '</tr>
                        <tr style="text-align:center; white-space:nowrap"> 
						    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col first-col">ITEMS</th>
						    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col second-col">Headers</th>
						    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col third-col"></th>';
						    
						      $colorCtr1 = 0;
                                foreach($period as $dates)
                                {   
                           
						    $html .= '<th style="background:'.$colorArr[$colorCtr1].';border-bottom: 3px solid black;" class="sticky_row">Quantity</th> 
						    <th style="background:'.$colorArr[$colorCtr1].';border-bottom: 3px solid black;" class="sticky_row">Value</th>';
						     
						      $colorCtr1++;
                               }   
                            
                        $html .= '</tr>
                        </thead>
                       <tbody class="tablebody"> 
                            <tr>
                                <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Opening Stock</td>
                                <td style="background: antiquewhite;" nowrap  class="sticky-col third-col">meters</td>';
                            
                                $OpeningFabricQtyArr = [];
                                $InwardFabricQtyArr = [];
                                $OutwardFabricQtyArr = [];
                                $ClosingFabricQtyArr = [];
                                
                                
                                $OpeningFabricValueArr = [];
                                $InwardFabricValueArr = [];
                                $OutwardFabricValueArr = [];
                                $ClosingFabricValueArr = [];
                                
                                
                                $OpeningTrimsQtyArr = [];
                                $InwardTrimsQtyArr = [];
                                $OutwardTrimsQtyArr = [];
                                $ClosingTrimsQtyArr = [];
                                
                                
                                $OpeningTrimsValueArr = [];
                                $InwardTrimsValueArr = [];
                                $OutwardTrimsValueArr = [];
                                $ClosingTrimsValueArr = [];
                                
                                $closingStock = 0;
                                $openingStock1 = 0;
                                $cntr1 = 0;
                                foreach($period as $dates)
                                {  
                                    $firstDate = $dates."-01";
                                    $lastDate = date("Y-m-t", strtotime( $dates."-01"));
                                  
                                    $total_value = 0;
                                    $total_stock = 0; 
                                    
                                    $FabricInwardDetails1 =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date < '".$firstDate."' ) as gq,
                                            (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date < '".$firstDate."') as oq 
                                            FROM dump_fabric_stock_data WHERE in_date < '".$firstDate."'");
                                        
                                    foreach($FabricInwardDetails1 as $row1)
                                    {
                                        $outward_qty1 = isset($row1->oq) ? $row1->oq : 0; 
                                        $grn_qty1 = isset($row1->gq) ? $row1->gq : 0; 
                                        $ind_outward2 = (explode(",",$row1->ind_outward_qty));
                                        $q_qty1 = 0; 
                                        
                                       
                                        foreach($ind_outward2 as $indu1)
                                        {
                                            
                                             $ind_outward3 = (explode("=>",$indu1));
                                             $q_qty2 = isset($ind_outward3[1]) ? $ind_outward3[1] : 0;
                                             if($ind_outward3[0] < $firstDate)
                                             {
                                                 $q_qty1 = $q_qty1 + $q_qty2;
                                             }
                                             else
                                             {
                                                  $q_qty1 =  0;
                                             } 
                                             
                                        }
                                        
                                        
                                        if($row1->qc_qty > 0 )
                                        {
                                            $stocks =  $row1->qc_qty - $q_qty1;
                                        } 
                                        else
                                        {
                                             $stocks =  $row1->gq - $q_qty1;
                                        }
                                        
                                              
                                        $total_stock += $stocks;  
                                        $total_value += $stocks * $row1->rate;  
                                       
                                    }
                                        
                                    
                                    $FabricopeningStockQty = $total_stock; 
                                    
                                    $FabricopeningStockValue = $total_value; 
                              
                                    
                                    $FabInOutStockList1=DB::select("select   
                                        (select ifnull(sum(inward_details.meter),sum(fabric_checking_details.meter)) as meter from inward_details
                                        left join fabric_checking_details on fabric_checking_details.track_code=inward_details.track_code where in_date BETWEEN '".$firstDate."' AND '".$lastDate."') as InwardQty,
                                        
                                        (select ifnull(sum(inward_details.meter * inward_details.item_rate),sum(fabric_checking_details.meter * inward_details.item_rate)) as meter from inward_details
                                        left join fabric_checking_details on fabric_checking_details.track_code=inward_details.track_code where in_date BETWEEN '".$firstDate."' AND '".$lastDate."') as InwardValue");
                                       
                                     
                                    $FabricInwardQty = isset($FabInOutStockList1[0]->InwardQty) ? $FabInOutStockList1[0]->InwardQty : 0; 
                                    $FabricInwardValue = isset($FabInOutStockList1[0]->InwardValue) ? $FabInOutStockList1[0]->InwardValue : 0; 
                                    
                                    $InwardFabricQtyArr[] = $FabricInwardQty;
                                    $InwardFabricValueArr[] = $FabricInwardValue;
                                    
                                        
                                    
                                    
                                    $FabInOutStockList2=DB::select("select (select ifnull(sum(meter),0) as meter from fabric_outward_details where fout_date BETWEEN '".$firstDate."' AND '".$lastDate."') as OutwardQty,
                                        (select ifnull(sum(meter * fabric_outward_details.item_rate),0) as meter from fabric_outward_details where fout_date BETWEEN '".$firstDate."' AND '".$lastDate."') as OutwardValue");
                                    
                                    $FabricOutwardQty = isset($FabInOutStockList2[0]->OutwardQty) ? $FabInOutStockList2[0]->OutwardQty : 0; 
                                    $FabricOutwardValue = isset($FabInOutStockList2[0]->OutwardValue) ? $FabInOutStockList2[0]->OutwardValue : 0;  
                                    

                                    $OutwardFabricQtyArr[] = $FabricOutwardQty;
                                    $OutwardFabricValueArr[] = $FabricOutwardValue;
                                    
                               
                                    if($firstDate == date('Y-04-01'))
                                    {
                                       $OpeningsQty  = $FabricopeningStockQty;
                                       $OpeningsValue  = $FabricopeningStockValue; 
                                    }
                                    else
                                    {
                                       $OpeningsQty = $FabricopeningStockQty + $FabricInwardQty - $FabricOutwardQty;
                                       $OpeningsValue = $FabricopeningStockValue + $FabricInwardValue - $FabricOutwardValue;
                                       
                                       
                                    }
                                    
                                    
                                    if($cntr1 == 0)
                                    {
                                        $openingStockQty = $OpeningsQty;
                                        $openingStockValue = $OpeningsValue;
                                          
                                    }
                                    else
                                    {
                                        $openingStockQty = $FabricopeningStockQty;
                                        $openingStockValue = $FabricopeningStockValue;
                                         
                                    }
                                    
                                    $ClosingFabricQtyArr[] = $openingStockQty + $FabricInwardQty - $FabricOutwardQty;
                                    $ClosingFabricValueArr[] = $openingStockValue + $FabricInwardValue - $FabricOutwardValue;
                                 
                              
                                    if($cntr1 == 0)
                                    {
                                        $OpeningFabricQtyArr[] = $openingStockQty;
                                        $OpeningFabricValueArr[] = $openingStockValue; 
                                    }
                                    else
                                    { 
                                        $OpeningFabricQtyArr[] = $ClosingFabricQtyArr[$cntr1-1];
                                        $OpeningFabricValueArr[] = $ClosingFabricValueArr[$cntr1-1];
                                         
                                    }
                                    
                               
                                    $TrimOpeningData =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data WHERE trimDate < '".$firstDate."' GROUP BY po_no,item_code");     
                                    
                                    $total_opening_value = 0;
                                  
                                    
                                    foreach($TrimOpeningData as $row)
                                    {
                                        $q_qty = 0;   
                                        $ind_outward1 = (explode(",",$row->ind_outward_qty));
                                        
                                        foreach($ind_outward1 as $indu)
                                        {
                                            
                                             $ind_outward2 = (explode("=>",$indu));
                                              
                                             if($ind_outward2[0] < $firstDate)
                                             {
                                                $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                                                $q_qty = $q_qty + $ind_out;
                                               
                                             }
                                        } 
                                      
                                        $stocks =  $row->gq - $q_qty; 
                                        $total_opening_value += ($stocks * $row->rate);
                                    }
                
                                    $TrimInwardData = DB::select("select sum(trimsInwardDetail.item_qty * trimsInwardDetail.item_rate)  as Inward from trimsInwardDetail INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
                                            where item_master.cat_id !=4 AND trimDate BETWEEN '".$firstDate."' AND '".$lastDate."'");
                                            
                                    $TrimsOutwardData = DB::SELECT("SELECT trimsOutwardDetail.item_qty,trimsOutwardDetail.item_code,trimsOutwardDetail.po_code FROM trimsOutwardDetail 
                                            INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code 
                                            WHERE item_master.cat_id != 4 AND trimsOutwardDetail.tout_date BETWEEN '".$firstDate."' AND '".$lastDate."'");
                                    
                                            
                                    $outward_qty = 0;
                                    foreach($TrimsOutwardData as $row)
                                    {
                                        $TrimsInwardData = DB::SELECT("SELECT trimsInwardDetail.item_rate FROM trimsInwardDetail  
                                            INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
                                            WHERE item_master.cat_id != 4 AND trimsInwardDetail.item_code = '".$row->item_code."' AND po_code='".$row->po_code."'");
                                        
                                        $item_rate = isset($TrimsInwardData[0]->item_rate) ? $TrimsInwardData[0]->item_rate: 0;  
                                        $outward_qty += ($row->item_qty * $item_rate);
                                    }
                                         
                                  
                                    $openingStock = $total_opening_value; 
                                    $inwardQty = isset($TrimInwardData[0]->Inward) ? $TrimInwardData[0]->Inward: 0;   
                                    $outwardQty = $outward_qty;       
                             
                                    
                                    $ClosingTrimsValueArr[] = $openingStock + $inwardQty - $outwardQty;
                                    if($cntr1 == 0)
                                    { 
                                        $OpeningTrimsValueArr[] = $openingStock; 
                                    }
                                    else
                                    {  
                                        $OpeningTrimsValueArr[] = $ClosingTrimsValueArr[$cntr1-1];
                                         
                                    }
                                    
                                    
                                    $InwardTrimsValueArr[] = $inwardQty;
                                    $OutwardTrimsValueArr[] = $outwardQty;


                               
                                    $html .= '<td class="text-right" style="background:'.$colorArr[$cntr1].';">'.round($OpeningFabricQtyArr[$cntr1]/100000,2).'</td> 
                                    <td class="text-right" style="background:'.$colorArr[$cntr1].';">'.round(($OpeningFabricValueArr[$cntr1]/100000),2).'</td>';
                                
                                    $cntr1++;
                                }
                              
                              
                            $html .= '</tr>
                            <tr>
                                <td style="background: antiquewhite;" nowrap class="sticky-col first-col">FABRIC</td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Purchase</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>';
                                
                                for($i = 0; $i< count($period);$i++)
                                {  
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($InwardFabricQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round(($InwardFabricValueArr[$i]/100000),2).'</td>';
                                } 
                            $html .= '</tr>
                            <tr>
                                <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Issued for Production</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>';
                                 
                                for($i = 0; $i< count($period);$i++)
                                {  
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($OutwardFabricQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round(($OutwardFabricValueArr[$i]/100000),2).'</td>';
                            
                                }
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">Closing Stock</td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col third-col">meters</td>';
                            
                                    for($i = 0; $i< count($period);$i++)
                                    {
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round($ClosingFabricQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round(($ClosingFabricValueArr[$i]/100000),2).'</td>';
                                
                                }
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: #87ceeba1;" class="sticky-col first-col"></td>
                                <td style="background: #87ceeba1;" nowrap class="sticky-col second-col">Opening Stock</td> 
                                <td style="background: #87ceeba1;" class="sticky-col third-col">meters</td>'; 
                                
                                for($i = 0; $i< count($period);$i++)
                                {   
                                 
                               $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">-</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round($OpeningTrimsValueArr[$i]/100000,2).'</td>';
                               
                                }
                               
                            $html .= '</tr>
                            <tr>
                                <td style="background: antiquewhite;" nowrap class="sticky-col first-col">TRIMS</td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Purchase</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>';
                                 
                                for($i = 0; $i< count($period);$i++)
                                {  
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">-</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round($InwardTrimsValueArr[$i]/100000,2).'</td>';
                                
                                }
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Issued for Production</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>';
                                
                                for($i = 0; $i< count($period);$i++)
                                {  
                                 
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">-</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round($OutwardTrimsValueArr[$i]/100000,2).'</td>';
                                 
                                }
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">Closing Stock</td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col third-col">meters</td>';
                                 
                                    for($i = 0; $i< count($period);$i++)
                                    {
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">-</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round($ClosingTrimsValueArr[$i]/100000,2).'</td>';
                                 
                                }
                                
                            $html .= '</tr> 
                            <tr>
                                <td style="background: #87ceeba1;" class="sticky-col first-col"></td>
                                <td style="background: #87ceeba1;" nowrap class="sticky-col second-col">Opening Stock</td> 
                                <td style="background: #87ceeba1;" class="sticky-col third-col">meters</td>';
                                for($i = 0; $i< count($period);$i++)
                                {   
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($OpeningFabricQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round(($OpeningFabricValueArr[$i]/100000),2).'</td>';
                               
                                }
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: #87ceeba1;" class="sticky-col first-col">WIP</td>
                                <td style="background: #87ceeba1;" class="sticky-col second-col">Inwards</td>
                                <td style="background: #87ceeba1;" class="sticky-col third-col">meters</td>';
                                 
                                
                                for($i = 0; $i< count($period);$i++)
                                {   
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($InwardFabricQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round(($InwardFabricValueArr[$i]/100000),2).'</td>';
                                }
                                 
                            $html .= '</tr>
                            <tr>
                                <td style="background: #87ceeba1;" class="sticky-col first-col"></td>
                                <td style="background: #87ceeba1;" nowrap class="sticky-col second-col">Production completed</td>
                                <td style="background: #87ceeba1;" class="sticky-col third-col">meters</td>';
                                 
                                for($i = 0; $i< count($period);$i++)
                                {    
                                 
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($OutwardFabricQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round($OutwardFabricValueArr[$i]/100000,2).'</td>';
                                
                                } 
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: #87ceeba1;" class="sticky-col first-col"></td>
                                <td style="background: #87ceeba1;" nowrap class="sticky-col second-col">Production completed</td>
                                <td style="background: #87ceeba1;" class="sticky-col third-col">piece</td>';
                                
                                $cntr5 = 0;
                                foreach($period as $dates)
                                {  
                                
                                    $firstDate = $dates."-01";
                                    $lastDate = date("Y-m-t", strtotime( $dates."-01"));
                                    
                                    $WIPpackingData = DB::select("SELECT sum(total_qty) as inward ,sales_order_no FROM packing_inhouse_master WHERE is_opening = 0 AND pki_date BETWEEN '".$firstDate."' AND '".$lastDate."' GROUP BY sales_order_no");
                                    
                                           
                                    $productionCompletedPieces = 0;
                                    $productionCompletedPiecesValue = 0;
                                    
                                    foreach($WIPpackingData as $WIP)
                                    {
                                        $fgData = DB::select("SELECT sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate FROM buyer_purchse_order_master    
                                              left join sales_order_costing_master On sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                                              where buyer_purchse_order_master.tr_code='".$WIP->sales_order_no."' group by buyer_purchse_order_master.tr_code");
                                         
                                        
                                        $stockData = isset($fgData[0]->total_cost_value) ? $fgData[0]->total_cost_value : 0;
                                        if($stockData == 0)
                                        {
                                            $fob_rate = isset($fgData[0]->order_rate) ? $fgData[0]->order_rate : 0;
                                        }
                                        else
                                        {
                                            $fob_rate = $fgData[0]->total_cost_value;
                                        } 
                                        
                                        $productionCompletedPieces +=  $WIP->inward;
                                        $productionCompletedPiecesValue +=  $WIP->inward * $fob_rate;    
                                
                                    }  
                                    
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$cntr5].';">'.round($productionCompletedPieces/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$cntr5].';">'.round($productionCompletedPiecesValue/100000,2).'</td>';
                                
                                $cntr5++;
                                }
                               
                            $html .= '</tr>
                            <tr>
                                <td style="background: #87ceeba1;" class="sticky-col first-col"></td>
                                <td style="background: #87ceeba1;" nowrap class="sticky-col second-col">Closing Stock</td>
                                <td style="background: #87ceeba1;" class="sticky-col third-col">meters</td>';
                                
                                for($i = 0; $i< count($period);$i++)
                                {    
                                    $WIPClosingStockQty = $OpeningFabricQtyArr[$i] + $InwardFabricQtyArr[$i] - $OutwardFabricQtyArr[$i];
                                    $WIPClosingStockValue = $OpeningFabricValueArr[$i] + $InwardFabricValueArr[$i] - $OutwardFabricValueArr[$i];
                                    
                               
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($WIPClosingStockQty/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round($WIPClosingStockValue/100000,2).'</td>';
                                
                                }
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: #87ceeba1;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #87ceeba1;border-bottom: 3px solid black;" class="sticky-col second-col">Consumption</td>
                                <td style="background: #87ceeba1;border-bottom: 3px solid black;" nowrap class="sticky-col third-col">meters/piece</td>';
                                
                                $cntr4 = 0;
                            
                                foreach($period as $dates)
                                {  
                                
                                    $firstDate = $dates."-01";
                                    $lastDate = date("Y-m-t", strtotime( $dates."-01"));
                    
                                    $WIPpackingData = DB::select("SELECT sum(total_qty) as inward ,sales_order_no FROM packing_inhouse_master WHERE is_opening = 0 AND pki_date BETWEEN '".$firstDate."' AND '".$lastDate."' GROUP BY sales_order_no");
                                    
                                    $WIPpackingData1 = DB::select("SELECT sum(packing_inhouse_master.total_qty * sales_order_fabric_costing_details.consumption) as meter FROM packing_inhouse_master 
                                    LEFT JOIN sales_order_fabric_costing_details ON sales_order_fabric_costing_details.sales_order_no = packing_inhouse_master.sales_order_no 
                                    WHERE packing_inhouse_master.is_opening = 0 AND packing_inhouse_master.pki_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND sales_order_fabric_costing_details.class_id IN(1,2)");
                                 
                                     
                                    $productionCompletedMeter = isset($WIPpackingData1[0]->meter) ? $WIPpackingData1[0]->meter : 0;    
                                    $WIPInwards = 0; 
                                    $WIPInwardsValue = 0;
                                    foreach($WIPpackingData as $WIP1)
                                    {
                                        $fgData = DB::select("SELECT sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate FROM buyer_purchse_order_master    
                                              left join sales_order_costing_master On sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                                              where buyer_purchse_order_master.tr_code='".$WIP1->sales_order_no."' group by buyer_purchse_order_master.tr_code");
                                         
                                        
                                        $stockData = isset($fgData[0]->total_cost_value) ? $fgData[0]->total_cost_value : 0;
                                        if($stockData == 0)
                                        {
                                            $fob_rate = isset($fgData[0]->order_rate) ? $fgData[0]->order_rate : 0;
                                        }
                                        else
                                        {
                                            $fob_rate = $fgData[0]->total_cost_value;
                                        } 
                                        
                                        $WIPInwards +=  $WIP1->inward;
                                        $WIPInwardsValue +=  $WIP1->inward * $fob_rate;    
                                
                                    }  
                               
                                $html .= '<td class="text-right" style="background:'.$colorArr[$cntr4].';border-bottom: 3px solid black;">'.round(($WIPInwards/$productionCompletedMeter)/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$cntr4].';border-bottom: 3px solid black;">'.round(($WIPInwards * ($WIPInwards/$productionCompletedMeter))/100000,2).'</td>';
                                
                                $cntr4++;
                                }
                               
                            $html .= '</tr>
                            
                            <tr>
                                <td style="background: #7fff0073;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;" nowrap class="sticky-col second-col">Opening Stock</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col">piece</td>';
                                
                                
                                $OpeningFGQtyArr = [];
                                $InwardFGQtyArr = [];
                                $OutwardFGQtyArr = [];
                                $TransferFGQtyArr = [];
                                $ClosingFGQtyArr = [];
                                
                                
                                $OpeningFGValueArr = [];
                                $InwardFGValueArr = [];
                                $OutwardFGValueArr = [];
                                $TransferFGValueArr = [];
                                $ClosingFGValueArr = [];
                                
                                $OpeningFGQtyArr1 = [];
                                $OpeningFGValueArr1 = [];
                                
                                $cntr2 = 0;
                                
                                foreach($period as $dates)
                                {  
                                
                                    $firstDate = date($dates."-01");
                                    $lastDate = date("Y-m-t", strtotime( $dates."-01"));
                                    
                                    $packingData = DB::select("SELECT sum(size_qty_total) as packing,sales_order_no FROM packing_inhouse_detail WHERE pki_date < '".$firstDate."' GROUP BY sales_order_no");
                                    
                                    $transferData1 = DB::select("SELECT sum(size_qty_total) as transfer,sales_order_no  FROM transfer_packing_inhouse_detail WHERE tpki_date < '".$firstDate."'  GROUP BY sales_order_no"); 
                                    
                                     
                                    $OpeingPackingQty = 0;
                                    $OpeingPackingValue = 0;
                                    
                                    foreach($packingData as $packing)
                                    {
                                        $fgData = DB::select("SELECT sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate FROM buyer_purchse_order_master    
                                              left join sales_order_costing_master On sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                                              where buyer_purchse_order_master.tr_code='".$packing->sales_order_no."' group by buyer_purchse_order_master.tr_code");
                                         
                                        
                                        $stockData = isset($fgData[0]->total_cost_value) ? $fgData[0]->total_cost_value : 0;
                                        if($stockData == 0)
                                        {
                                            $fob_rate = isset($fgData[0]->order_rate) ? $fgData[0]->order_rate : 0;
                                        }
                                        else
                                        {
                                            $fob_rate = $fgData[0]->total_cost_value;
                                        } 
                                        
                                        $OpeingPackingQty +=  $packing->packing;
                                        $OpeingPackingValue +=  $packing->packing * $fob_rate;    
                                
                                    } 
                                    
                                    $salesData1 = DB::select("SELECT sum(order_qty) as outward, sum(order_qty * order_rate) as outwardValue FROM sale_transaction_detail WHERE sale_date < '".$firstDate."'"); 
                                    
                                    $OutwardQty1 = isset($salesData1[0]->outward) ? $salesData1[0]->outward : 0; 
                                    $OutwardValue1 = isset($salesData1[0]->outwardValue) ? $salesData1[0]->outwardValue : 0; 

                          
                                    $TransferQty1 = 0;
                                    $TransferValue1 = 0;
                                    
                                    foreach($transferData1 as $transfer1)
                                    {
                                         $fgData = DB::select("SELECT sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate FROM buyer_purchse_order_master    
                                             left join sales_order_costing_master On sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                                              where buyer_purchse_order_master.tr_code='".$transfer1->sales_order_no."' group by buyer_purchse_order_master.tr_code");
                                         
                                        
                                        $stockData = isset($fgData[0]->total_cost_value) ? $fgData[0]->total_cost_value : 0;
                                        if($stockData == 0)
                                        {
                                            $fob_rate = isset($fgData[0]->order_rate) ? $fgData[0]->order_rate : 0;
                                        }
                                        else
                                        {
                                            $fob_rate = $fgData[0]->total_cost_value;
                                        }
                                        
                                        $TransferQty1 +=  $transfer1->transfer;
                                        $TransferValue1 +=  $transfer1->transfer * $fob_rate;    
                                
                                    }
                                    
                                    $FGOpeningQty = $OpeingPackingQty;
                                    $FGOpeningValue = $OpeingPackingValue;  
                                    $FGOutwardQty1 = $OutwardQty1;    
                                    $FGOutwardValue1 = $OutwardValue1;  
                                    $FGtransferQty1 = $TransferQty1; 
                                    $FGtransferValue = $TransferValue1;
 
                             
                                      
                                    $InwardPackingQty = 0;
                                    $InwardPackingValue = 0;
                                   
                                    $InwardFGQtyArr[] = $InwardPackingQty;
                                    $InwardFGValueArr[] = $InwardPackingValue;
                                    
                                    $TransferQty = 0;
                                    $TransferValue = 0;
                                    
                                    
                                    $TransferFGQtyArr[] = $TransferQty;  
                                    $TransferFGValueArr[] = $TransferValue;  
                                     
                                    $OutwardQty = 0; 
                                    $OutwardValue = 0; 
                              
                                    
                                    
                                    $OutwardFGQtyArr[] = $OutwardQty;   
                                    $OutwardFGValueArr[] = $OutwardValue;  
                                      
                                    if($firstDate == date('Y-04-01'))
                                    {
                                       $OpeningsFGQty  = $OpeingPackingQty - $OutwardQty1-$TransferQty1;
                                       $OpeningsFGValue  = $FGOpeningValue-$FGOutwardValue1-$TransferValue1; 
                                    }
                                    else
                                    {
                                      $OpeningsFGQty = ($OpeingPackingQty - $OutwardQty1-$TransferQty1) - $OutwardQty;
                                      $OpeningsFGValue = ($OpeingPackingValue - $OutwardValue1-$TransferValue1) - $OutwardValue;
                                    }
                                    
                                    if($cntr2 == 0)
                                    {
                                        $openingStockQty = $OpeningsFGQty;
                                        $openingStockValue = $OpeningsFGValue;
                                    }
                                    else
                                    {  
                                       // $openingStockQty = $OpeningFGQtyArr1[$cntr2-1];
                                        $openingStockValue = $OpeningFGValueArr1[$cntr2-1];  
                                    }
                                    
                                    $ClosingFGQtyArr[] = $OpeningsFGQty + $InwardPackingQty - $TransferQty - $OutwardQty;
                                       
                                    $ClosingFGValueArr[] = $openingStockValue + $InwardPackingValue - $OutwardValue - $TransferValue;
                         
                                    if($cntr2 == 0)
                                    {
                                        $OpeningFGQtyArr1[] = $openingStockQty;
                                        $OpeningFGValueArr1[] = $openingStockValue;
                                    }
                                    else
                                    {
                                    
                                        $OpeningFGQtyArr1[] = $ClosingFGQtyArr[$cntr2-1];
                                        $OpeningFGValueArr1[] = $ClosingFGValueArr[$cntr2-1];
                                    }
                                    
                                    
                                    $cntr2++;
                                 } 
                                 
                                 $OpeningFGQtyArr = [];
                                 $OpeningFGValueArr = [];
                                 for($i = 0; $i< count($period);$i++)
                                 {  
                                    if($i == 0)
                                    {
                                        $OpeningFGQtyArr[] = $OpeningFGQtyArr1[$i];
                                        $OpeningFGValueArr[] = $OpeningFGValueArr1[$i];
                                    }
                                    else
                                    { 
                                        $OpeningFGQtyArr[] = $OpeningFGQtyArr[$i-1] + $InwardFGQtyArr[$i-1] - $TransferFGQtyArr[$i-1] - $OutwardFGQtyArr[$i-1];
                                        $OpeningFGValueArr[] = $OpeningFGValueArr[$i-1] + $InwardFGValueArr[$i-1] - $TransferFGValueArr[$i-1] - $OutwardFGValueArr[$i-1];
                                    }
                                    
                                 }
                                
                                 for($i = 0; $i< count($period);$i++)  
                                 { 
                                 
                                     if($i == 0)
                                     {
                                        $openingFGQty = $OpeningFGQtyArr[$i];
                                        $openingFGValue = $OpeningFGValueArr[$i];
                                     }
                                     else
                                     {
                                        $openingFGQty = $OpeningFGQtyArr[$i-1] + $InwardFGQtyArr[$i-1] - $TransferFGQtyArr[$i-1] - $OutwardFGQtyArr[$i-1];
                                        $openingFGValue = $OpeningFGValueArr[$i-1] + $InwardFGValueArr[$i-1] - $TransferFGValueArr[$i-1] - $OutwardFGValueArr[$i-1];
                                     }
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($openingFGQty/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round($openingFGValue/100000,2).'</td>';
                                 
                                } 
                                
                            $html .= '</tr>
                            <tr>
                                <td style="background: #7fff0073;" class="sticky-col first-col">FG</td>
                                <td style="background: #7fff0073;" class="sticky-col second-col">Production</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col">piece</td>';
                                
                                for($i = 0; $i< count($period);$i++)
                                {     
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($InwardFGQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round(($InwardFGValueArr[$i]/100000),2).'</td>';
                                 
                                }
                                 
                            $html .= '</tr>
                            <tr>
                                <td style="background: #7fff0073;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;" class="sticky-col second-col">Transfer</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col">piece</td>';
                                 
                                for($i = 0; $i< count($period);$i++)
                                {   
                                 
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($TransferFGQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round(($TransferFGValueArr[$i]/100000),2).'</td>';
                                
                                }
                                 
                            $html .= '</tr>
                            <tr>
                                <td style="background: #7fff0073;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;" class="sticky-col second-col">Sales</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col">piece</td>';
                                
                                for($i = 0; $i< count($period);$i++)
                                {  
                                    
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';">'.round($OutwardFGQtyArr[$i]/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';">'.round(($OutwardFGValueArr[$i]/100000),2).'</td>';
                                
                                }
                                 
                            $html .= '</tr> 
                            <tr>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">Closing Stock</td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col third-col">piece</td>';
                                 
                                for($i=0;$i< count($period); $i++)
                                {    
                                       if($i == 0)
                                       {
                                            $closingFG1Qty = $OpeningFGQtyArr[0] + $InwardFGQtyArr[0] - $TransferFGQtyArr[0] - $OutwardFGQtyArr[0];
                                            $closingFG1Value = $OpeningFGValueArr[0] + $InwardFGValueArr[0] - $TransferFGValueArr[0] - $OutwardFGValueArr[0];
                                            
                                            $closingFGQty = $closingFG1Qty;
                                            $closingFGValue = $closingFG1Value;
                                       }
                                       else
                                       {
                                            $closingFG1Qty = $OpeningFGQtyArr[$i-1] + $InwardFGQtyArr[$i-1] - $TransferFGQtyArr[$i-1] - $OutwardFGQtyArr[$i-1];
                                            $closingFG1Value = $OpeningFGValueArr[$i-1] + $InwardFGValueArr[$i-1] - $TransferFGValueArr[$i-1] - $OutwardFGValueArr[$i-1];
                                            
                                            $closingFGQty = $closingFG1Qty + $InwardFGQtyArr[$i] - $TransferFGQtyArr[$i] - $OutwardFGQtyArr[$i];
                                            $closingFGValue = $closingFG1Value + $InwardFGValueArr[$i] - $TransferFGValueArr[$i] - $OutwardFGValueArr[$i];
                                       }
                                        
                                     
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round(($closingFGQty/100000),2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round(($closingFGValue/100000),2).'</td>';
                                  
                                }
                                 
                            $html .= '</tr> 
                            <tr>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;" nowrap class="sticky-col second-col">TOTAL (Fabric + WIP)</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col"></td>';
                                
                                for($i=0;$i< count($period); $i++)
                                {    
                                     $WIPClosingStockQty = $OpeningFabricQtyArr[$i] + $InwardFabricQtyArr[$i] - $OutwardFabricQtyArr[$i];
                                     $WIPClosingStockValue = $OpeningFabricValueArr[$i] + $InwardFabricValueArr[$i] - $OutwardFabricValueArr[$i];
                                   
                                    
                                     $totalFabricFGQty = $ClosingFabricQtyArr[$i] + $WIPClosingStockQty;
                                     $totalFabricFGValue = $ClosingFabricValueArr[$i] + $WIPClosingStockValue;
                                     
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round($totalFabricFGQty/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round($totalFabricFGValue/100000,2).'</td>';
                                 
                                }
                                 
                            $html .= '</tr> 
                            <tr>
                                <td style="background: #7fff0073;border-top: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">TOTAL FG</td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col third-col"></td>';
                                
                                for($i=0;$i< count($period); $i++)
                                {   
                                       
                                       if($i == 0)
                                       {
                                            $closingFG1Qty = $OpeningFGQtyArr[0] + $InwardFGQtyArr[0] - $TransferFGQtyArr[0] - $OutwardFGQtyArr[0];
                                            $closingFG1Value = $OpeningFGValueArr[0] + $InwardFGValueArr[0] - $TransferFGValueArr[0] - $OutwardFGValueArr[0];
                                            
                                            $closingFGQty = $closingFG1Qty;
                                            $closingFGValue = $closingFG1Value;
                                       }
                                       else
                                       {
                                            $closingFG1Qty = $OpeningFGQtyArr[$i-1] + $InwardFGQtyArr[$i-1] - $TransferFGQtyArr[$i-1] - $OutwardFGQtyArr[$i-1];
                                            $closingFG1Value = $OpeningFGValueArr[$i-1] + $InwardFGValueArr[$i-1] - $TransferFGValueArr[$i-1] - $OutwardFGValueArr[$i-1];
                                            
                                            $closingFGQty = $closingFG1Qty + $InwardFGQtyArr[$i] - $TransferFGQtyArr[$i] - $OutwardFGQtyArr[$i];
                                            $closingFGValue = $closingFG1Value + $InwardFGValueArr[$i] - $TransferFGValueArr[$i] - $OutwardFGValueArr[$i];
                                       }
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round($closingFGQty/100000,2).'</td> 
                                <td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round($closingFGValue/100000,2).'</td>';
                                
                                }
                               
                            $html .= '</tr> 
                            <tr>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" nowrap  class="sticky-col second-col">GRAND TOTAL</td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col third-col"></td>';
                                 
                                
                                for($i=0;$i< count($period); $i++)
                                {   
                                    
                                     $WIPClosingStockQty = $OpeningFabricQtyArr[$i] + $InwardFabricQtyArr[$i] - $OutwardFabricQtyArr[$i];
                                     $WIPClosingStockValue = $OpeningFabricValueArr[$i] + $InwardFabricValueArr[$i] - $OutwardFabricValueArr[$i];
                                   
                                    
                                     $totalFabricFGQty = $ClosingFabricQtyArr[$i] + $WIPClosingStockQty;
                                     $totalFabricFGValue = $ClosingFabricValueArr[$i] + $WIPClosingStockValue;
                                     
                                     if($i == 0)
                                     {
                                        $closingFG1Qty = $OpeningFGQtyArr[0] + $InwardFGQtyArr[0] - $TransferFGQtyArr[0] - $OutwardFGQtyArr[0];
                                        $closingFG1Value = $OpeningFGValueArr[0] + $InwardFGValueArr[0] - $TransferFGValueArr[0] - $OutwardFGValueArr[0];
                                        
                                        $closingFGQty = $closingFG1Qty;
                                        $closingFGValue = $closingFG1Value;
                                     }
                                     else
                                     {
                                        $closingFG1Qty = $OpeningFGQtyArr[$i-1] + $InwardFGQtyArr[$i-1] - $TransferFGQtyArr[$i-1] - $OutwardFGQtyArr[$i-1];
                                        $closingFG1Value = $OpeningFGValueArr[$i-1] + $InwardFGValueArr[$i-1] - $TransferFGValueArr[$i-1] - $OutwardFGValueArr[$i-1];
                                        
                                        $closingFGQty = $closingFG1Qty + $InwardFGQtyArr[$i] - $TransferFGQtyArr[$i] - $OutwardFGQtyArr[$i];
                                        $closingFGValue = $closingFG1Value + $InwardFGValueArr[$i] - $TransferFGValueArr[$i] - $OutwardFGValueArr[$i];
                                     }
                                       
                                
                                $html .= '<td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round(($totalFabricFGQty+$closingFGQty)/100000,2).'</td>
                                <td class="text-right" style="background:'.$colorArr[$i].';border-bottom: 3px solid black;">'.round(($totalFabricFGValue+$closingFGValue)/100000,2).'</td>';
                                  
                                }
                                 
                            $html .= '</tr> 
                        </tbody>
                    </table>';
        return response()->json(['html' => $html]); 
        
    }
    
    public function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];
    
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
    
        for (
            $currentDate = $startDate;
            $currentDate <= $endDate;
            $currentDate += (86400)
        ) {
    
            $date = date('Y-m', $currentDate);
            $rangArray[] = $date;
        }
    
        return $rangArray;
    }
    
    
    public function GetTotalOrderBookingSummary(Request $request)
    {
        $OrderFromDate = $request->OrderFromDate;
        $OrderToDate = $request->OrderToDate; 
        $html = '';
        
        $orderTypeData = DB::SELECT("SELECT * FROM order_type_master WHERE delflag=0 order By order_type ASC");
        
        foreach($orderTypeData as $types)
        {
            $srno = 1; 
            $totalBuyer = 0;
            $totalOrderQty = 0;
            $totalOrderMins = 0;
            $totalOrderValue = 0;
            $totalOrderValue1 = 0;
            $totalCostingApproved = 0;
            $totalDifference = 0;
            $totalCMOHP_per = 0;
            $totalCMD = 0;
            $totalSAM = 0;
            
             $buyerData = DB::SELECT("SELECT buyer_purchse_order_master.sam,buyer_purchse_order_master.og_id,buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.tr_code,ledger_master.ac_short_name,count(*) as buyer_count,
                    sum(buyer_purchse_order_master.total_qty) as order_qty,buyer_purchse_order_master.order_rate AS order_rate,
                    sum(buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam) as order_min,sum(buyer_purchse_order_master.total_qty * buyer_purchse_order_master.order_rate) as order_value,
                    (SELECT count(sales_order_costing_master.Ac_code) FROM sales_order_costing_master  
                        INNER JOIN ledger_master ON ledger_master.ac_code = sales_order_costing_master.Ac_code
                        INNER JOIN buyer_purchse_order_master as B1 ON B1.tr_code = sales_order_costing_master.sales_order_no
                        WHERE is_approved = 2 AND og_id !=4 AND buyer_purchse_order_master.delflag = 0 
                        AND B1.Ac_code = buyer_purchse_order_master.Ac_code
                        AND B1.order_received_date BETWEEN '".$OrderFromDate."' AND '".$OrderToDate."') as costing_approved,
                        SUM(((sales_order_costing_master.production_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value) + sales_order_costing_master.other_value)/buyer_purchse_order_master.sam) * (buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam)) / sum(buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam)  as cmohp, 
                        SUM(((sales_order_costing_master.production_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value) + sales_order_costing_master.other_value)/buyer_purchse_order_master.sam) * (buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam)) as order_value1  
                    FROM buyer_purchse_order_master
                    INNER JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code   
                    LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code   
                    WHERE buyer_purchse_order_master.delflag=0 AND og_id !=4 AND order_received_date BETWEEN '".$OrderFromDate."' AND '".$OrderToDate."' AND order_type = ".$types->orderTypeId." GROUP BY ledger_master.Ac_code ORDER BY order_min DESC");
                    
            $html .=' <div class="col-lg-2"></div>
                   <div class="col-lg-8">
                   <div class="row">
                   <div class="col-md-9 text-center">
                        <label class="mb-4" style="font-size: 25px;color: black;">Order Booking - '.$types->order_type.'</label> 
                    </div>
                    <div class="col-md-3">
                            <button class="btn btn-warning" onclick="html_table_to_excel(`xlsx`,'.$types->orderTypeId.');">Export</button>
                        </div>
                   </div>
                      <div class="card">
                         <div class="card-body"style="background: #00ffff30;"> 
                            <div class="col-md-12">  
                               <div class="table-responsive"  style="overflow-y:auto;height:50vh;"> 
                                <table class="table table-bordered nowrap w-100" id="tab_'.$types->orderTypeId.'">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th class="text-center">Sr.No.</th>
                                        <th class="text-center">Buyer</th>
                                        <th class="text-center">No. of Orders</th>
                                        <th class="text-center">Order Qty</th>
                                        <th class="text-center">Order Min</th> 
                                        <th class="text-center">Order Value</th>';
                                        if($types->orderTypeId !=2)
                                        {
                                         $html .='<th class="text-center hide">Costing</th> 
                                                 <th class="text-center hide">Diff.</th>';
                                        }
                                        
                                      $html .='<th class="text-center">CMOHP</th></tr>
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th class="text-center"></th>
                                        <th class="text-center"></th>
                                        <th class="text-center"></th>
                                        <th class="text-center">L Pcs</th>
                                        <th class="text-center">L Min</th> 
                                        <th class="text-center">Rs. Cr.</th>';
                                        if($types->orderTypeId !=2)
                                        {
                                         $html .='<th class="text-center hide">Approved</th> 
                                                  <th class="text-center hide"></th>';
                                        }
                                        
                                      $html .='<th class="text-center">%</th></tr>
                                  </thead>
                                  <tbody>';
                                            
                                    foreach($buyerData as $row)
                                    {         
             
                                        
                                            $difference = $row->buyer_count - $row->costing_approved;
                                            $html .= '<tr>
                                                        <td>'.$srno++.'</td>
                                                        <td>'.$row->ac_short_name.'</td>
                                                        <td class="text-right">'.$row->buyer_count.'</td>
                                                        <td class="text-right">'.(number_format((float)($row->order_qty/100000), 2, '.', '')).'</td>
                                                        <td class="text-right">'.(number_format((float)($row->order_min/100000), 2, '.', '')).'</td>
                                                        <td class="text-right">'.(number_format((float)($row->order_value/10000000), 2, '.', '')).'</td>';
                                                        if($types->orderTypeId !=2)
                                                        {
                                                            $html .= '<td class="text-right hide">'.$row->costing_approved.'</td>
                                                                  <td class="text-right hide">'.($difference ? $difference : 0).'</td>';
                                                        }
                                            $html .= '<td class="text-right"><a href="/CostingOHPDashboard?Ac_code='.$row->Ac_code.'" target="_blank">'.(round($row->cmohp,2)).'</a></td></tr>';
                                            
                                            $totalBuyer += $row->buyer_count;
                                            $totalOrderQty += $row->order_qty;
                                            $totalOrderMins += $row->order_min;
                                            $totalOrderValue += $row->order_value; 
                                            $totalOrderValue1 += $row->order_value1;
                                            $totalCostingApproved += $row->costing_approved;
                                            $totalDifference += $difference ? $difference : 0;
                                            
                                    }
                                    
                                     if($totalOrderValue1 > 0 && $totalOrderMins > 0)
                                     {
                                          $totalCMOHP_per = $totalOrderValue1/$totalOrderMins;
                                     }
                                     $html .= '<tr>
                                                    <th></th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-right">'.$totalBuyer.'</th>
                                                    <th class="text-right">'.(number_format((float)($totalOrderQty/100000), 2, '.', '')).'</th>
                                                    <th class="text-right">'.(number_format((float)($totalOrderMins/100000), 2, '.', '')).'</th>
                                                    <th class="text-right">'.(number_format((float)($totalOrderValue/10000000), 2, '.', '')).'</th>';
                                                    if($types->orderTypeId !=2)
                                                    {
                                                        $html .= '<th class="text-right hide">'.$totalCostingApproved.'</th>
                                                            <th class="text-right hide">'.$totalDifference.'</th>';
                                                    }
                                            $html .= '<th class="text-right">'.(round($totalCMOHP_per,2)).'</th></tr>
                                    </tbody>
                                </table>
                </div>
                </div> 
             </div>
            </div>
           </div> 
           <div class="col-lg-2"></div>';
        }
        
        return response()->json(['html'=>$html,'OrderFromDate'=>$OrderFromDate,'OrderToDate'=>$OrderToDate]);  
    }
    
    public function QuaititativeInventoryReportList()
    {
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id = 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        

        $fDate = $Financial_Year[0]->fdate;
        $tDate = date('Y-m-d');
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        return view('QuaititativeInventoryReportList',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1')); 
    }
    
    public function chatgpt()
    {
        return view('chatgpt');
    }
} 





