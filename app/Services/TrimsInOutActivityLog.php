<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class TrimsInOutActivityLog
{
   
    
public function logIfChangedTrimsInOutDetail(string $table,$recordId,array $oldData, array $newData,string $action, string $AttendanceDate, string $module)
{


//   logger('OLD DATA:', $oldData);
//   logger('NEW DATA:', $newData);


    $oldSales = collect($oldData)->keyBy('item_code')->toArray();
    $newSales = collect($newData)->keyBy('item_code')->toArray();

    // 1. Handle updated entries
    foreach ($newSales as $orderNo => $newItem) {
        $oldItem = $oldSales[$orderNo] ?? null;

        if (!$oldItem) {
            // Optional: If it's a new addition, log as 'INSERT' if needed
            
                 $OldDataInserted=[
                'item_code' => $newItem['item_code'], 
                'width'=>0, 
                'meter' => 0];  
            
            
            DB::table('trims_inward_outward_activity_log')->insert([
            'action_type' => 'INSERT',
            'trCode'=> $recordId,
            'item_code'=>$newItem['item_code'],
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

            DB::table('trims_inward_outward_activity_log')->insert([
                'action_type' => 'UPDATE',
                'trCode'=> $recordId,
                'item_code'=>$newItem['item_code'],
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
        
                $NewDataDeleted=[
                'item_code' => $deletedItem['item_code'], 
                'width'=>0, 
                'meter'=> 0]; 
        
        
        DB::table('trims_inward_outward_activity_log')->insert([
            'action_type' => 'DELETE',
            'trCode' => $recordId,
             'item_code'=>$deletedItem['item_code'],
            'old_data' => json_encode($deletedItem, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($NewDataDeleted, JSON_UNESCAPED_UNICODE),
             'module_name'=>$module,
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'),
        ]);
    }


}


}
