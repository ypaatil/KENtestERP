<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class PurchaseOrderDetailActivityLog
{
   
    
public function logIfChangedPurchaseOrderDetail(string $table,$recordId,array $oldData, array $newData,string $action, string $AttendanceDate, string $module)
{


  logger('OLD DATA:', $oldData);
  logger('NEW DATA:', $newData);
  
  
  
            function compositeKey($item) {
            return $item['sales_order_no'] . '|' . $item['item_code'];
            }
            
            $oldSales = collect($oldData)->keyBy(function ($item) {
            return compositeKey($item);
            })->toArray();
            
            $newSales = collect($newData)->keyBy(function ($item) {
            return compositeKey($item);
            })->toArray();
  
  


    // $oldSales = collect($oldData)->keyBy('sr_no')->toArray();
    // $newSales = collect($newData)->keyBy('sr_no')->toArray();

    // 1. Handle updated entries
    foreach ($newSales as $orderNo => $newItem) {
        $oldItem = $oldSales[$orderNo] ?? null;

        if (!$oldItem) {
            // Optional: If it's a new addition, log as 'INSERT' if needed
            
            
            
                 $OldDataInserted=[
                        'sales_order_no'=>$newItem['sales_order_no'],
                        'item_code'=>$newItem['item_code'], 
                        'item_qty'=>0,
                        'item_rate'=>0,
                        'disc_per'=>0,
                        'disc_amount'=>0,
                        'pur_cgst'=>0,
                        'camt'=>0,
                        'pur_sgst'=>0,
                        'samt'=>0, 
                        'pur_igst'=>0,  
                        'iamt'=>0,
                        'amount'=>0,
                        'freight_amt'=>0,
                        'total_amount'=>0 
                        ];  
            
            
            DB::table('purchase_order_detail_activity_log')->insert([
            'action_type' => 'INSERT',
            'tr_code'=> $recordId,
            'sales_order_no'=>$newItem['sales_order_no'],
            'item_code'=>$newItem['item_code'],
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

            DB::table('purchase_order_detail_activity_log')->insert([
                'action_type' => 'UPDATE',
                'tr_code'=> $recordId,
                 'sales_order_no'=>$newItem['sales_order_no'], 
                'item_code'=>$newItem['item_code'],
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
                        'sales_order_no'=>$deletedItem['sales_order_no'],
                        'item_code'=>$deletedItem['item_code'],
                         'item_qty'=>0,
                        'item_rate'=>0,
                        'disc_per'=>0,
                        'disc_amount'=>0,
                        'pur_cgst'=>0,
                        'camt'=>0,
                        'pur_sgst'=>0,
                        'samt'=>0, 
                        'pur_igst'=>0,  
                        'iamt'=>0,
                        'amount'=>0,
                        'freight_amt'=>0,
                        'total_amount'=>0
                        ];  
        
        
        DB::table('purchase_order_detail_activity_log')->insert([
             'action_type' => 'DELETE',
             'tr_code' => $recordId,
             'sales_order_no'=>$deletedItem['sales_order_no'], 
             'item_code'=>$deletedItem['item_code'],
             'old_data' => json_encode($deletedItem, JSON_UNESCAPED_UNICODE),
             'new_data' => json_encode($NewDataDeleted, JSON_UNESCAPED_UNICODE),
             'action_timestamp' => now(),
             'changed_by_user_id' => Session::get('userId'),
        ]);
    }


}


}
