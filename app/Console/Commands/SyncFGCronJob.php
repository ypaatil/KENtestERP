<?php

    namespace App\Console\Commands;
    use Illuminate\Console\Command; 
    use DB;
    use Log;
    date_default_timezone_set("Asia/Kolkata");     
    
    class SyncFGCronJob extends Command
    {
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'SyncFGData:cron';
    
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Sync FG Data';
    
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
    
        Log::info('Cron FG Job Started');
        DB::table('syncronization_time_mgmt')->where('stmt_type','=',3)->update(['end_time' => "", 'status' => 0]);

        $InsertSizeData = DB::select('call sp_FGStockDataByTwo()');
        
        echo json_encode('ok');
    }
}