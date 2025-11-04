<?php

    namespace App\Console\Commands;
    use Illuminate\Console\Command; 
    use DB;
    use Log;
    date_default_timezone_set("Asia/Kolkata");     
    
    class SyncTrimsCronJob extends Command
    {
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'SyncTrimsData:cron';
    
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Sync Trims Data';
    
    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
    parent::__construct();
    }
    
    /**
    * Execute the console command.
    *
    * @return int
    */
    public function handle()
    {
    
         Log::info('Cron Trims Job Started');
         DB::table('dump_trim_stock_data')->delete();
      
        //  DB::table('syncronization_time_mgmt')->where('stmt_type','=',2)->update(['end_time' => "", 'status' => 0]);
         
         $trimData =  DB::SELECT("select trimsInwardMaster.trimDate,trimsInwardMaster.trimCode,trimsInwardMaster.po_code as po_no,trimsInwardDetail.item_code,
            ledger_master.ac_name, sum(trimsInwardDetail.item_qty) as grn_qty,trimsInwardDetail.item_rate as rate,trimsInwardDetail.rack_id,job_status_master.job_status_name,purchase_order.po_status,
            trimsInwardMaster.po_code,trimsInwardDetail.amount as amount,
            ledger_master.ac_short_name as suplier_name,item_master.dimension,item_master.item_name,
            item_master.color_name,item_master.item_description
            from trimsInwardDetail
            left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            left join purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code 
            left join job_status_master ON job_status_master.job_status_id = purchase_order.po_status 
            WHERE item_master.cat_id !=4 AND item_master.class_id != 94 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code,trimsInwardDetail.trimCode");
            
          foreach($trimData as $row)
          {  
                $buyerData = DB::SELECT("select LM1.ac_short_name as buyer_name,purchaseorder_detail.job_status_id,job_status_master.job_status_name FROM purchaseorder_detail 
                                                                    INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = purchaseorder_detail.sales_order_no 
                                                                    INNER JOIN purchase_order ON purchase_order.pur_code = purchaseorder_detail.pur_code 
                                                                    INNER JOIN ledger_master as LM1 ON LM1.ac_code = purchase_order.buyer_id 
                                                                    LEFT JOIN job_status_master ON job_status_master.job_status_id = purchaseorder_detail.job_status_id 
                                                                    WHERE purchaseorder_detail.pur_code = '". $row->po_no."' AND purchaseorder_detail.item_code=".$row->item_code."
                                                                    GROUP BY purchaseorder_detail.pur_code");
                                                                    
                $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
                if($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "")
                {
                    $job_status_id =  1;
                    $po_status = "Moving";
                }
                else
                {
                    $job_status_id = 2;
                    $po_status = "Non Moving";
                }
                
                $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
                $trimDate = str_replace('"', "", $row->trimDate);
                $suplier_name = str_replace('"', "", $row->suplier_name);  
                $po_no = str_replace('"', "", $row->po_no);
                $trimCode = str_replace('"', "", $row->trimCode); 
                $item_code = str_replace('"', "", $row->item_code); 
                $item_name = str_replace('"', "", $row->item_name);
                $color =  "";
                $item_description = str_replace('"', "", $row->item_description); 
                $grn_qty = str_replace('"', "", $row->grn_qty);
                $rate = str_replace('"', "", $row->rate);
                $rack_id = str_replace('"', "", $row->rack_id);
                $ac_code = 0;
                $suplier_id = 0;
                $unit_id = 0;
                $amount = str_replace('"', "", $row->amount);  

                $outwardData = DB::SELECT("select sum(item_qty) as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='".$row->po_no."' AND item_code=".$row->item_code);
                $ind_outward_qty1 = "";
                $outwardData1 = DB::SELECT("select item_qty as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='".$row->po_no."' AND item_code=".$row->item_code);
                
                foreach($outwardData1 as $OD)
                {
                    $ind_outward_qty1 = $OD->tout_date."=>".$OD->outward_qty.",".$ind_outward_qty1;
                }
                
                $tout_date = isset($outwardData[0]->tout_date) ? $outwardData[0]->tout_date : "";
                $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0; 
                $ind_outward_qty = rtrim($ind_outward_qty1,","); 
                 
                $buyerData = DB::table('purchase_order')->select('LM1.ac_name as buyer_name')->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')->where('purchase_order.pur_code','=', $po_no)->get();
                                            
                $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
    
                 DB::SELECT('INSERT INTO dump_trim_stock_data(trimDate,tout_date,suplier_name,buyer_name,po_no,item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,trimCode,grn_qty,outward_qty,ind_outward_qty,amount)
                        select "'.$trimDate.'","'.$tout_date.'","'.$suplier_name.'","'.$buyerName.'","'.$po_no.'","'.$item_code.'","'.addslashes($item_name).'","'.$rate.'", "'.$color.'",
                                "'.addslashes($item_description).'", "'.$po_status.'", "'.$job_status_id.'", "'.$rack_id.'","'.$ac_code.'","'.$suplier_id.'","'.$unit_id.'","'.$trimCode.'","'.$grn_qty.'","'.$outward_qty.'","'.$ind_outward_qty.'","'.$amount.'"');
                //dd(DB::getQueryLog());
          }
        
        // date_default_timezone_set("Asia/Calcutta");
        // DB::table('syncronization_time_mgmt')->where('stmt_type','=',2)->update(['end_time' => date("H:i", time()), 'status' => 1,'sync_table'=>0]);
        echo json_encode('ok');
    }
}