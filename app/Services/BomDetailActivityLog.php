<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class BomDetailActivityLog
{
   
    
public function logIfChangedBomDetail(string $table,$recordId,array $oldData, array $newData,string $action, string $AttendanceDate, string $module)
{


  logger('OLD DATA:', $oldData);
  logger('NEW DATA:', $newData);


    $oldSales = collect($oldData)->keyBy('sr_no')->toArray();
    $newSales = collect($newData)->keyBy('sr_no')->toArray();

    // 1. Handle updated entries
    foreach ($newSales as $orderNo => $newItem) {
        $oldItem = $oldSales[$orderNo] ?? null;

        if (!$oldItem) {
            // Optional: If it's a new addition, log as 'INSERT' if needed
            
            
                 if($module=='Fabric')
                {
                    
                        $OldDataInserted=[
                        'sr_no'=>$newItem['sr_no'],
                        'item_code'=>0, 
                        'consumption'=>0,
                        'description'=>0,
                        'rate_per_unit'=>0,
                        'wastage'=>0,
                        'bom_qty'=>0,
                        'total_amount'=>0
                        ]; 
                        
                        
                } else{
                    
                        $OldDataInserted=[
                        'sr_no'=>$newItem['sr_no'],
                        'class_id'=>0,
                        'item_code'=>0, 
                        'color_id'=>0,
                        'size_array'=>0,
                        'consumption'=>0,
                        'rate_per_unit'=>0,
                        'wastage'=>0,
                        'bom_qty'=>0,
                        'total_amount'=>0,
                        'remark'=>0 
                        ];    
                        
                }
                    
                         
            
            
            DB::table('bom_detail_activity_log')->insert([
            'action_type' => 'INSERT',
            'tr_code'=> $recordId,
            'sr_no'=>$newItem['sr_no'],   
            'old_data' => json_encode($OldDataInserted, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($newItem, JSON_UNESCAPED_UNICODE),
            'module_name'=>$module,
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'),
            ]);
            
            continue;
        }

        $normalizedNew = array_map('strval', $newItem);
        $normalizedOld = array_map('strval', $oldItem);
        $changedKeys = array_diff_assoc($normalizedNew, $normalizedOld);

        if (!empty($changedKeys)) {
            $newChanged = array_intersect_key($newItem, $changedKeys);
            $oldChanged = array_intersect_key($oldItem, $changedKeys);

            DB::table('bom_detail_activity_log')->insert([
                'action_type' => 'UPDATE',
                'tr_code'=> $recordId,
                'sr_no'=>$newItem['sr_no'],  
                'old_data' => json_encode($oldChanged, JSON_UNESCAPED_UNICODE),
                'new_data' => json_encode($newChanged, JSON_UNESCAPED_UNICODE),
                'module_name'=>$module, 
                'action_timestamp' => now(),
                'changed_by_user_id' => Session::get('userId'),
            ]);
        }
    }
    
    
    


    // 2. Handle deleted entries
    $deletedOrders = array_diff_key($oldSales, $newSales);

    foreach ($deletedOrders as $orderNo => $deletedItem) {
        
        
                if($module=='Fabric')
                {
                    
        
                   $NewDataDeleted=[
                        'sr_no'=>$deletedItem['sr_no'],
                        'item_code'=>$deletedItem['item_code'], 
                        'consumption'=>0,
                        'description'=>0,
                        'rate_per_unit'=>0,
                        'wastage'=>0,
                        'bom_qty'=>0,
                        'total_amount'=>0
                        ];   
                        
                        
                } else{
                    
                        $NewDataDeleted=[
                        'sr_no'=>$deletedItem['sr_no'],
                        'class_id'=>$deletedItem['class_id'],
                        'item_code'=>$deletedItem['item_code'], 
                        'color_id'=>0,
                        'size_array'=>0,
                        'consumption'=>0,
                        'rate_per_unit'=>0,
                        'wastage'=>0,
                        'bom_qty'=>0,
                        'total_amount'=>0,
                        'remark'=>0 
                        ];    
                        
                }            



                
        
        
        DB::table('bom_detail_activity_log')->insert([
            'action_type' => 'DELETE',
             'tr_code' => $recordId,
             'sr_no'=> $deletedItem['sr_no'],   
             'old_data' => json_encode($deletedItem, JSON_UNESCAPED_UNICODE),
             'new_data' => json_encode($NewDataDeleted, JSON_UNESCAPED_UNICODE),
             'module_name'=>$module,
             'action_timestamp' => now(),
             'changed_by_user_id' => Session::get('userId'),
        ]);
    }


}


}
