<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class SalesOrderDetailActivityLog
{
   
    
public function logIfChangedSalesOrderDetail(string $table,$recordId,array $oldData, array $newData,string $action, string $AttendanceDate, string $module)
{


  logger('OLD DATA:', $oldData);
  logger('NEW DATA:', $newData);


    $oldSales = collect($oldData)->keyBy('color_id')->toArray();
    $newSales = collect($newData)->keyBy('color_id')->toArray();

    // 1. Handle updated entries
    foreach ($newSales as $orderNo => $newItem) {
        $oldItem = $oldSales[$orderNo] ?? null;

        if (!$oldItem) {
            // Optional: If it's a new addition, log as 'INSERT' if needed
            
                 $OldDataInserted=[
                        'color_id'=>$newItem['color_id'],
                        'item_code'=>0,
                        's1'=>0,
                        's2'=>0,
                        's3'=>0,
                        's4'=>0,
                        's5'=>0,
                        's6'=>0,
                        's7'=>0,
                        's8'=>0,
                        's9'=>0,
                        's10'=>0,
                        's11'=>0,
                        's12'=>0,
                        's13'=>0,
                        's14'=>0,
                        's15'=>0,
                        's16'=>0,
                        's17'=>0,
                        's18'=>0,
                        's19'=>0,
                        's20'=>0,
                        'size_qty_total'=>0,
                        'shipment_allowance'=>0,
                        'garment_rejection_allowance'=>0,
                        'adjust_qty'=>0,
                        'remark'=>$newItem['remark'],
                        'size_array'=>$newItem['size_array']
                        ];  
            
            
            DB::table('sales_order_detail_activity_log')->insert([
            'action_type' => 'INSERT',
            'tr_code'=> $recordId,
            'color_id'=>$newItem['color_id'],
            'old_data' => json_encode($OldDataInserted, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($newItem, JSON_UNESCAPED_UNICODE),
             'size_array'=>$newItem['size_array'],
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

            DB::table('sales_order_detail_activity_log')->insert([
                'action_type' => 'UPDATE',
                'tr_code'=> $recordId,
                'color_id'=>$newItem['color_id'],
                'old_data' => json_encode($oldChanged, JSON_UNESCAPED_UNICODE),
                'new_data' => json_encode($newChanged, JSON_UNESCAPED_UNICODE),
                'size_array'=>$newItem['size_array'],
                'action_timestamp' => now(),
                'changed_by_user_id' => Session::get('userId'),
            ]);
        }
    }
    
    
    


    // 2. Handle deleted entries
    $deletedOrders = array_diff_key($oldSales, $newSales);

    foreach ($deletedOrders as $orderNo => $deletedItem) {
        
                $NewDataDeleted=[
                    'color_id'=>$deletedItem['color_id'],
                        'item_code'=>0,
                        's1'=>0,
                        's2'=>0,
                        's3'=>0,
                        's4'=>0,
                        's5'=>0,
                        's6'=>0,
                        's7'=>0,
                        's8'=>0,
                        's9'=>0,
                        's10'=>0,
                        's11'=>0,
                        's12'=>0,
                        's13'=>0,
                        's14'=>0,
                        's15'=>0,
                        's16'=>0,
                        's17'=>0,
                        's18'=>0,
                        's19'=>0,
                        's20'=>0,
                        'size_qty_total'=>0,
                        'shipment_allowance'=>0,
                        'garment_rejection_allowance'=>0,
                        'adjust_qty'=>0,
                        'remark'=>0,
                        'size_array'=>$deletedItem['size_array']
                        ]; 
        
        
        DB::table('sales_order_detail_activity_log')->insert([
            'action_type' => 'DELETE',
             'tr_code' => $recordId,
             'color_id'=>$deletedItem['color_id'],
            'old_data' => json_encode($deletedItem, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($NewDataDeleted, JSON_UNESCAPED_UNICODE),
            'size_array'=>$deletedItem['size_array'],
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'),
        ]);
    }


}


}
