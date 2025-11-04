<?php

    namespace App\Console\Commands;
    use Illuminate\Console\Command; 
    use DB;
    use Log;
    date_default_timezone_set("Asia/Kolkata");     
    
    class SyncFabricCronJob extends Command
    {
        /**
        * The name and signature of the console command.
        *
        * @var string
        */
        protected $signature = 'SyncFabricData:cron';
        
        /**
        * The console command description.
        *
        * @var string
        */
        protected $description = 'Sync Fabric Data';
        
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
            Log::info('Cron Fabric Job Started');
            DB::table('dump_fabric_stock_data')->delete();
            
           // DB::table('syncronization_time_mgmt')->where('stmt_type','=',1)->update(['end_time' => "", 'status' => 0]);
            //DB::enableQueryLog();
            $fabricData = DB::SELECT("select inward_details.in_date,'',ledger_master.ac_short_name as suplier_name,LM1.ac_short_name as buyer_name,
                inward_master.po_code as po_no,inward_details.in_code as grn_no,inward_master.invoice_no,
                item_master.item_code,item_master.item_image_path as preview,shade_master.shade_name as shade_no,item_master.item_name,
                quality_master.quality_name, item_master.color_name as color,item_master.item_description,
                job_status_master.job_status_name as po_status,
                job_status_master.job_status_id,inward_details.track_code as track_name, inward_details.meter as grn_qty, 
                inward_details.item_rate as rate,inward_details.rack_id from inward_details 
                left join inward_master on inward_master.in_code=inward_details.in_code
                left JOIN purchase_order ON purchase_order.pur_code = inward_master.po_code
                left JOIN job_status_master ON job_status_master.job_status_id = purchase_order.po_status
                left join ledger_master on ledger_master.ac_code=inward_details.Ac_code    
                left join ledger_master as LM1 on LM1.ac_code=inward_details.buyer_id                      
                left join item_master on item_master.item_code=inward_details.item_code 
                left join quality_master on quality_master.quality_code=item_master.quality_code  
                left join shade_master on shade_master.shade_id=inward_details.shade_id");
           //dd(DB::getQueryLog());
               
            foreach($fabricData as $row)
            {  
                    $in_date = str_replace('"', "", $row->in_date);
                    $suplier_name = str_replace('"', "", $row->suplier_name);
                    $buyer_name = str_replace('"', "", $row->buyer_name);
                    $po_no = str_replace('"', "", $row->po_no);
                    $grn_no = str_replace('"', "", $row->grn_no);
                    $invoice_no = str_replace('"', "", $row->invoice_no);
                    $item_code = str_replace('"', "", $row->item_code);
                    $preview = str_replace('"', "", $row->preview);
                    $shade_no = str_replace('"', "", $row->shade_no);
                    $item_name = str_replace('"', "", $row->item_name);
                    $quality_name = str_replace('"', "", $row->quality_name);
                    $color = str_replace('"', "", $row->color);
                    $item_description = str_replace('"', "", $row->item_description);
                    $po_status = $row->po_status;
                    $job_status_id = $row->job_status_id;
                    $track_name = str_replace('"', "", $row->track_name);
                    $grn_qty = str_replace('"', "", $row->grn_qty);
                    $rate = str_replace('"', "", $row->rate);
                    $rack_id = str_replace('"', "", $row->rack_id);
                      
                    $checking_width =DB::select("select width,fabric_check_status_master.fcs_name, sum(meter + reject_short_meter) as QCQty FROM fabric_checking_details 
                         LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                         WHERE track_code = '".$row->track_name."'");
                         
                    $QCQty = isset($checking_width[0]->QCQty) ? $checking_width[0]->QCQty : 0;
                    $width = isset($checking_width[0]->width) ? $checking_width[0]->width : 0;
                    $fcs_name = isset($checking_width[0]->fcs_name) ? $checking_width[0]->fcs_name : '';
                
                    $outwardData = DB::SELECT("select sum(meter) as outward_qty,fout_date FROM fabric_outward_details WHERE track_code ='".$row->track_name."'");
                    $ind_outward_qty1 = "";
                    $outwardData1 = DB::SELECT("select sum(meter) as outward_qty,fout_date FROM fabric_outward_details WHERE track_code ='".$row->track_name."' group by fout_date");
                    
                    foreach($outwardData1 as $OD)
                    {
                        $ind_outward_qty1 = $OD->fout_date."=>".$OD->outward_qty.",".$ind_outward_qty1;
                    }
                    
                    $fout_date = isset($outwardData[0]->fout_date) ? $outwardData[0]->fout_date : "";
                    $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0; 
                    $ind_outward_qty = rtrim($ind_outward_qty1,","); 
                    
                   //DB::enableQueryLog();
    
                    $buyerData = DB::table('purchase_order')->select('LM1.ac_name as buyer_name')->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')->where('purchase_order.pur_code','=', $po_no)->get();
                                                
                    $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
    
                    DB::SELECT('INSERT INTO dump_fabric_stock_data(in_date,fout_date,suplier_name,buyer_name,po_no,grn_no,invoice_no,item_code,preview,shade_no,item_name,quality_name,
                            color,item_Description,po_status,job_status_id,track_name,grn_qty,qc_qty,outward_qty,rate,rack_name,tr_type,ind_outward_qty,width,fcs_name)
                            select "'.$in_date.'","'.$fout_date.'","'.$suplier_name.'","'.$buyerName.'","'.$po_no.'","'.$grn_no.'","'.$invoice_no.'","'.$item_code.'","'.$preview.'","'.$shade_no.'","'.$item_name.'",
                            "'.$quality_name.'","'.$color.'","'.$item_description.'","'.$po_status.'","'.$job_status_id.'","'.$track_name.'","'.$grn_qty.'","'.$QCQty.'","'.$outward_qty.'","'.$rate.'","'.$rack_id.'",1,"'.$ind_outward_qty.'","'.$width.'","'.$fcs_name.'"');
                    //dd(DB::getQueryLog());
            }
        
            // date_default_timezone_set("Asia/Calcutta");
            // DB::table('syncronization_time_mgmt')->where('stmt_type','=',1)->update(['end_time' => date("H:i", time()), 'status' => 1,'sync_table'=>0]);
            echo json_encode('ok');
        }
    }