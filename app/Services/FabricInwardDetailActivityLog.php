<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class FabricInwardDetailActivityLog
{
   
    
public function logIfChangedFabricInwardDetail(string $table,$recordId,array $oldData, array $newData,string $action, string $AttendanceDate, string $module)
{



//   logger('OLD DATA:', $oldData);
//   logger('NEW DATA:', $newData);



    $oldSales = collect($oldData)->keyBy('track_code')->toArray();
    $newSales = collect($newData)->keyBy('track_code')->toArray();

    // 1. Handle updated entries
    foreach ($newSales as $orderNo => $newItem) {
        $oldItem = $oldSales[$orderNo] ?? null;

        if (!$oldItem) {
            // Optional: If it's a new addition, log as 'INSERT' if needed
            
                 $OldDataInserted=[
                'track_code' => $newItem['track_code'], 
                'item_code'=>0, 
                'part_id' =>0,
                'roll_no' => 0,
                'meter' => 0,
                'gram_per_meter' => 0,
                'kg' => 0,
                'item_rate' => 0,
                'amount' =>0,
                'shade_id' =>'1',
                'suplier_roll_no' =>0];  
            
            
            DB::table('fabric_inward_activity_log')->insert([
            'action_type' => 'INSERT',
            'in_code'=> $recordId,
            'track_code'=>$newItem['track_code'],
            'old_data' => json_encode($OldDataInserted, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($newItem, JSON_UNESCAPED_UNICODE),
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

            DB::table('fabric_inward_activity_log')->insert([
                'action_type' => 'UPDATE',
                'in_code'=> $recordId,
                'track_code'=>$newItem['track_code'],
                'old_data' => json_encode($oldChanged, JSON_UNESCAPED_UNICODE),
                'new_data' => json_encode($newChanged, JSON_UNESCAPED_UNICODE),
                'action_timestamp' => now(),
                'changed_by_user_id' => Session::get('userId'),
            ]);
        }
    }
    
    
    
    
    	



    // 2. Handle deleted entries
    $deletedOrders = array_diff_key($oldSales, $newSales);

    foreach ($deletedOrders as $orderNo => $deletedItem) {
        
                $NewDataDeleted=[
                'track_code' => $deletedItem['track_code'], 
                'item_code'=>0, 
                'part_id' =>0,
                'roll_no' => 0,
                'meter' => 0,
                'gram_per_meter' => 0,
                'kg' => 0,
                'item_rate' => 0,
                'amount' =>0,
                'shade_id' =>'1',
                'suplier_roll_no' =>0];  
        
        
        DB::table('fabric_inward_activity_log')->insert([
            'action_type' => 'DELETE',
            'in_code' => $recordId,
             'track_code'=>$deletedItem['track_code'],
            'old_data' => json_encode($deletedItem, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($NewDataDeleted, JSON_UNESCAPED_UNICODE),
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'),
        ]);
    }


}


}
