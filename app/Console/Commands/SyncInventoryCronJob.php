<?php

    namespace App\Console\Commands;
    use Illuminate\Console\Command; 
    use DB;
    use Log;
    date_default_timezone_set("Asia/Kolkata");     
    
    class SyncInventoryCronJob extends Command
    {
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'SyncInventoryCronJob:cron';
    
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Sync Inventory Data';
    
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
    
        Log::info('Cron Inventory Job Started');
        //DB::table('temp_order_sales_dashboard')->where('table_head', 10)->delete(); 
       
        DB::table('syncronization_time_mgmt')->where('stmt_type','=',4)->update(['end_time' => "", 'status' => 0]);
         
            
        setlocale(LC_MONETARY, 'en_IN');  
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
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
         
        date_default_timezone_set("Asia/Calcutta");
        DB::table('syncronization_time_mgmt')->where('stmt_type','=',4)->update(['end_time' => date("H:i", time()), 'status' => 1,'sync_table'=>0]);
        echo json_encode('ok');
    }
}