 public function DailyProductionDetailDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '116')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
            // DB::enableQueryLog();
       $ProductionOrderDetailList = DB::select("SELECT buyer_purchse_order_master.tr_code ,buyer_purchse_order_master.po_code, 
       buyer_purchse_order_master.mainstyle_id,mainstyle_name,buyer_purchse_order_master.style_no  , merchant_master.merchant_name ,
       buyer_purchse_order_master.Ac_code, ac_name, username, 
    buyer_purchase_order_detail.color_id,color_name, sum(size_qty_total) as order_qty, 
    (select ifnull(sum(size_qty_total),0)  from cut_panel_grn_detail where cut_panel_grn_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_grn_detail.sales_order_no=buyer_purchse_order_master.tr_code and cut_panel_grn_detail.cpg_date='".date('Y-m-d',strtotime("-1 days"))."') as today_cutting_qty,
   (select  ifnull(sum(size_qty_total),0)  from cut_panel_grn_detail where cut_panel_grn_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_grn_detail.sales_order_no=buyer_purchse_order_master.tr_code) as total_cutting_qty,
    (select ifnull(sum(size_qty_total),0)  from cut_panel_issue_detail where cut_panel_issue_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_issue_detail.sales_order_no=buyer_purchse_order_master.tr_code and cut_panel_issue_detail.cpi_date='".date('Y-m-d',strtotime("-1 days"))."') as today_cut_panel_issue,
    (select ifnull(sum(size_qty_total),0)  from cut_panel_issue_detail where cut_panel_issue_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_issue_detail.sales_order_no=buyer_purchse_order_master.tr_code) as total_cut_panel_issue,
    (select ifnull(sum(size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and stitching_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code and stitching_inhouse_detail.sti_date='".date('Y-m-d',strtotime("-1 days"))."')  as today_stitching_qty,
    (select ifnull(sum(size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and stitching_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code)  as total_stitching_qty,
   
    (select ifnull(sum(size_qty_total),0)  from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.color_id=buyer_purchase_order_detail.color_id and qcstitching_inhouse_reject_detail.sales_order_no=buyer_purchse_order_master.tr_code and qcstitching_inhouse_reject_detail.qcsti_date='".date('Y-m-d',strtotime("-1 days"))."')  as today_qcstitching_reject_qty,
    (select ifnull(sum(size_qty_total),0)  from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.color_id=buyer_purchase_order_detail.color_id and qcstitching_inhouse_reject_detail.sales_order_no=buyer_purchse_order_master.tr_code)  as total_qcstitching_reject_qty,
   
    
    (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail
    inner join packing_inhouse_master on packing_inhouse_master.pki_code= packing_inhouse_detail.pki_code
    where packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id 
    and packing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code and 
    packing_inhouse_detail.pki_date='".date('Y-m-d' ,strtotime("-1 days"))."') as today_packing_qty,
    
    (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail
    inner join packing_inhouse_master on packing_inhouse_master.pki_code= packing_inhouse_detail.pki_code
    where   packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
    and packing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code)  as total_packing_qty ,
    
    (select ifnull(sum(size_qty_total),0) from carton_packing_inhouse_detail
    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code= carton_packing_inhouse_detail.cpki_code
    where carton_packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id 
    and carton_packing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code and 
    carton_packing_inhouse_detail.cpki_date='".date('Y-m-d' ,strtotime("-1 days"))."') as today_shipment_qty,
    
    
    (select ifnull(sum(size_qty_total),0)  from carton_packing_inhouse_detail
    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
    where   carton_packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
    and carton_packing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code
    and carton_packing_inhouse_master.endflag=1
    )  as total_shipment_qty
    
    FROM `buyer_purchse_order_master` 
    inner join buyer_purchase_order_detail on buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code
    left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
    inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
    left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
    
    left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
    left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
    where buyer_purchse_order_master.job_status_id=1
    group by main_style_master.mainstyle_id, buyer_purchse_order_master.Ac_code, buyer_purchase_order_detail.color_id ,buyer_purchse_order_master.userId
    ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query); 
      return view('DailyProductionDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id'));
          
    //       (select ifnull(sum(size_qty_total),0)  from finishing_inhouse_detail where finishing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and finishing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code and finishing_inhouse_detail.fns_date='".date('Y-m-d',strtotime("-1 days"))."')  as today_finishing_qty,
    // (select ifnull(sum(size_qty_total),0)  from finishing_inhouse_detail where finishing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and finishing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code)   as total_finishing_qty,
  
        
     }