<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class SaleTransactionActivityLog
{
   
    
public function logIfChangedSaleTransaction(string $table, $recordId,$sales_order_no,array $oldData, array $newData,string $action, string $AttendanceDate, string $module)
{



//   logger('OLD DATA:', $oldData);
//   logger('NEW DATA:', $newData);


    $oldSales = collect($oldData)->keyBy('sales_order_no')->toArray();
    $newSales = collect($newData)->keyBy('sales_order_no')->toArray();

    // 1. Handle updated entries
    foreach ($newSales as $orderNo => $newItem) {
        $oldItem = $oldSales[$orderNo] ?? null;

        if (!$oldItem) {
            // Optional: If it's a new addition, log as 'INSERT' if needed
            continue;
        }

        $normalizedNew = array_map('strval', $newItem);
        $normalizedOld = array_map('strval', $oldItem);
        $changedKeys = array_diff_assoc($normalizedNew, $normalizedOld);

        if (!empty($changedKeys)) {
            $newChanged = array_intersect_key($newItem, $changedKeys);
            $oldChanged = array_intersect_key($oldItem, $changedKeys);

            DB::table('sale_transaction_activity_log')->insert([
                'action_type' => 'UPDATE',
                'sale_code' => $recordId,
                'sales_order_no' => $newItem['sales_order_no'],
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
        "camt"=>0,
        "iamt"=> 0,
        "samt"=> 0,
        "amount"=> 0,
        "Ac_code"=> 0,
        "unit_id"=> 0,
        "disc_per"=> 0,
        "hsn_code"=> 0,
        "order_qty"=> 0,
        "sale_cgst"=> 0, 
        "sale_code"=> $recordId,  
         "sale_date"=> 0, 
        "sale_igst"=> 0,
        "sale_sgst"=> 0,
        "order_rate"=> 0,
        "disc_amount"=>0,
        "total_amount"=>0,
        "sales_order_no"=> $deletedItem['sales_order_no']];
        
        
        DB::table('sale_transaction_activity_log')->insert([
            'action_type' => 'DELETE',
            'sale_code' => $recordId,
            'sales_order_no' => $deletedItem['sales_order_no'],
            'old_data' => json_encode($deletedItem, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($NewDataDeleted, JSON_UNESCAPED_UNICODE),
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'),
        ]);
    }


}


}
