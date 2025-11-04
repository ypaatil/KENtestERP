<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    // protected $commands = [
    //       Commands\SyncFabricCronJob::class,
    //       Commands\SyncTrimsCronJob::class,
    //       Commands\SyncFGCronJob::class,
    // ];
    protected $commands = [
        \App\Console\Commands\SyncFabricCronJob::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
     
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('SyncFabricData:cron')
        //         ->dailyAt('20:30')
        //         ->timezone('Asia/Kolkata')
        //         ->appendOutputTo(storage_path('logs/sync_fabric.log'));
        
        //   $schedule->command('SyncTrimsData:cron')
        //         ->dailyAt('14:19')
        //         ->timezone('Asia/Kolkata')
        //         ->appendOutputTo(storage_path('logs/sync_fabric.log'));
    }


    // protected function schedule(Schedule $schedule)
    // {      
            // Log::info("Cron Job Started..");
           // $schedule->command('SyncTrimsData:cron')->dailyAt('11:24')->timezone('Asia/Kolkata');
            // // $schedule->command('SyncFGData:cron')->dailyAt('22:00')->timezone('Asia/Kolkata');
            // // $schedule->command('SyncFabricData:cron')->dailyAt('22:00')->timezone('Asia/Kolkata');
            // //DB::enableQueryLog();
            // $syncData = DB::SELECT('SELECT stmt_type,start_time FROM syncronization_time_mgmt WHERE sync_table=1');
            // //dd(DB::getQueryLog());
            // $stmt_type =  isset($syncData[0]->stmt_type) ?  $syncData[0]->stmt_type : 0;
            
            // if($stmt_type == 1)
            // { 
            //     Log::info("Fabric..");
            //     $sync_time = $syncData[0]->start_time ?  $syncData[0]->start_time : date("H:i"); 
            //     $schedule->command('SyncFabricData:cron')->dailyAt($sync_time)->timezone('Asia/Kolkata'); 
            // }   
            // else if($stmt_type == 2)
            // {  
            //     Log::info("Trims..");
                // $sync_time =  $syncData[0]->start_time ?  $syncData[0]->start_time : date("H:i"); 
                // $schedule->command('SyncTrimsData:cron')->dailyAt("11:15")->timezone('Asia/Kolkata'); 
            // }
            // else if($stmt_type == 3)
            // {
            //     Log::info("FG..");
            //     $sync_time =  $syncData[0]->start_time ?  $syncData[0]->start_time : date("H:i"); 
            //     $schedule->command('SyncFGData:cron')->dailyAt($sync_time)->timezone('Asia/Kolkata');  
            // }
            // else
            // {
            //      Log::info("Not..");
            // }
         
        
        // $schedule->command('inspire')->hourly();
        //  $schedule->command('SyncFabricData:cron')->hourly(); 
          //$schedule->command('UpdateFabricData:cron')->everyFiveMinutes();
          
    // }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
