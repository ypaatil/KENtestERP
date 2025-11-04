<?php

    namespace App\Console\Commands;
    use Illuminate\Console\Command; 
    use DB;
    use Log;
    date_default_timezone_set("Asia/Kolkata");     
    
    class UpdateFabricCronJob extends Command
    {
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'UpdateFabricData:cron';
    
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Update Fabric Data';
    
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
    
        $dumpedData = DB::SELECT("SELECT * FROM dump_fabric_stock_data"); 
           
        foreach($dumpedData as $row)
        {
            $foutData = DB::select("select fout_date FROM fabric_outward_details WHERE fabric_outward_details.track_code='".$row->track_name ."' GROUP BY track_code");

            $fout_date = isset($foutData[0]->fout_date) ? $foutData[0]->fout_date : '';
            
            DB::SELECT("UPDATE dump_fabric_stock_data SET fout_date = '".$fout_date."' WHERE track_name='".$row->track_name."'");
        }
        
        $dumpedData = DB::SELECT("SELECT * FROM dump_fabric_stock_data"); 
           
        foreach($dumpedData as $row)
        {
            $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_no ."'");
                     
            if(count($salesOrderNo) > 0)
            {
                 $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                 INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                 where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                  
                    $buyer_name = isset($buyerData[0]->ac_name) ? $buyerData[0]->ac_name : "-"; 
            }
            else
            {
                $buyer_name = "-";
            }
            
           
            $job_status_name = isset($JobStatusList[0]->job_status_name) ? $JobStatusList[0]->job_status_name : '';
            
            DB::SELECT("UPDATE dump_fabric_stock_data SET buyer_name = '".$buyer_name."',status='".$job_status_name."' WHERE track_name='".$row->track_name."'");
        }
        
        echo json_encode('ok');
    }
}