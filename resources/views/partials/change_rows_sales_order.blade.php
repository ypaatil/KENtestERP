<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $ActivityLogListDataSalesOrderList->links() }}
        </div>
    </td>
</tr>
        
        <tr>
            <th nowrap>Action</th>   
            <th nowrap>Date / Time</th>  
            <th nowrap>TR Code</th>  
            <th nowrap>Color Code</th>    
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th nowrap>Changed By</th>  
        </tr>
    </thead>
    
    @php
         $fieldLabelSO = [
     'pki_date' =>'Date',  
     'color_id' =>'Color',
     'in_out_id'=>'Inhouse/Outsource',
     'brand_id'=>'Brand',
     'season_id'=>'Season',
     'mainstyle_id'=>'Main Style Category',
     'substyle_id'=>'Sub Style Category',
     'fg_id'=>'Style Name',
     'ptm_id'=>'Payment Terms',
     'dterm_id'=>'Delivery Terms/TNA Template',
     'ship_id'=>'Shipment Mode',
      'warehouse_id'=>'Destination',
      'sz_code'=>'Size Group',
      'merchant_id'=>'Merchant',
      'unit_id'=>'UOM',
      'PDMerchant_id'=>'PD Merchant',
      'job_status_id'=>'PO Status'
     // Add more fields as needed
    ];
        
            $colors = DB::table('color_master')->pluck('color_name','color_id')->toArray();
           
            $InhouseOutSource=["1"=>"Fresh","2"=>"Stock","3"=>"Job Work"]; 
            
            $brand=DB::table('brand_master')->pluck('brand_name','brand_id')->where('delflag',0)->toArray();
            
            $SeasonList = DB::table('season_master')->pluck('season_name','season_id')->where('season_master.delflag','=', '0')->toArray();
            
            $MainStyleList = DB::table('main_style_master')->pluck('mainstyle_name','mainstyle_id')->where('main_style_master.delflag','=', '0')->toArray();
            
            $SubStyleList = DB::table('sub_style_master')->pluck('substyle_name','substyle_id')->where('sub_style_master.delflag','=', '0')->toArray();
           
            $FGList = DB::table('fg_master')->pluck('fg_name','fg_id')->where('fg_master.delflag','=', '0')->toArray();
            
            $PaymentTermsList = DB::table('payment_term')->pluck('ptm_name','ptm_id')->where('payment_term.delflag','=', '0')->toArray();
            
            $DeliveryTermsList = DB::table('delivery_terms_master')->pluck('delivery_term_name','dterm_id')->where('delivery_terms_master.delflag','=', '0')->toArray();
           
           
             $ShipmentList = DB::table('shipment_mode_master')->pluck('ship_mode_name','ship_id')->where('shipment_mode_master.delflag','=', '0')->toArray();
           
           
             $CountryList = DB::table('country_master')->pluck('c_name','c_id')->where('country_master.delflag','=', '0')->toArray();
            
            
             $WarehouseList = DB::table('warehouse_master')->pluck('warehouse_name','warehouse_id')->where('warehouse_master.delflag','=', '0')->toArray();
            
             $SizeList = DB::table('size_master')->pluck('sz_name','sz_code')->where('size_master.delflag','=', '0')->toArray();
             
             
              $MerchantList = DB::table('merchant_master')->pluck('merchant_name','merchant_id')->where('merchant_master.delflag','=', '0')->toArray();
             
     
              $UnitList = DB::table('unit_master')->pluck('unit_name','unit_id')->where('unit_master.delflag','=', '0')->toArray();
              
              
               $PDMerchantList = DB::table('PDMerchant_master')->pluck('PDMerchant_name','PDMerchant_id')->where('PDMerchant_master.delflag','=', '0')->toArray();
               
               
              $jobStatusList = DB::table('job_status_master')->pluck('job_status_name','job_status_id')->where('job_status_master.delflag','=', '0')->toArray();
             

           

        
     $formattedChangesInward = [];
     
     @endphp

@forelse ($ActivityLogListDataSalesOrderList as $logSO) 
  
  @php
    $old = json_decode($logSO->old_data, true);
    $new = json_decode($logSO->new_data, true);
    
        $userName=$logSO->username;
        $action_timestamp=$logSO->action_timestamp;
        $tr_code=$logSO->tr_code; 
        $action_type=$logSO->action_type; 
        $color_name=$logSO->color_name; 
        
        
         $size_array=$logSO->size_array; 
         
         $sizes = array_map('intval', explode(',', $size_array));
         
         



  $i = 0;
@endphp
     

    @foreach ($new as $key => $newValue) 
       @php $oldValue = $old[$key] ?? null; @endphp

        @if ($oldValue != $newValue) 
           
           @php
        
              if ($key === 'color_id') {
                $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
               if ($key === 'in_out_id') {
                $oldValue = $InhouseOutSource[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $InhouseOutSource[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
                if ($key === 'brand_id') {
                $oldValue = $brand[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $brand[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
                   if ($key === 'season_id') {
                $oldValue = $SeasonList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $SeasonList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
                if ($key === 'mainstyle_id') {
                $oldValue = $MainStyleList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $MainStyleList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
              if ($key === 'substyle_id') {
                $oldValue = $SubStyleList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $SubStyleList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
            
            if ($key === 'fg_id') {
                $oldValue = $FGList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $FGList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
              
            if ($key === 'ptm_id') {
                $oldValue = $PaymentTermsList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $PaymentTermsList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
               if ($key === 'dterm_id') {
                $oldValue = $DeliveryTermsList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $DeliveryTermsList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
                  if ($key === 'ship_id') {
                $oldValue = $ShipmentList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $ShipmentList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
            if ($key === 'c_id') {
                $oldValue = $CountryList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $CountryList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
            
             if ($key === 'warehouse_id') {
                $oldValue = $WarehouseList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $WarehouseList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
            
               if($key === 'sz_code') {
                $oldValue = $SizeList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $SizeList[$newValue] ?? ($newValue ?? 'N/A');
               }  
               
               
               if($key === 'merchant_id') {
                $oldValue = $MerchantList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $MerchantList[$newValue] ?? ($newValue ?? 'N/A');
                
               }  
               
                if($key === 'unit_id') {
                $oldValue = $UnitList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $UnitList[$newValue] ?? ($newValue ?? 'N/A');
                
               }  
               
               if($key === 'PDMerchant_id') {
                $oldValue = $PDMerchantList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $PDMerchantList[$newValue] ?? ($newValue ?? 'N/A');
                
               } 
               
               if($key === 'job_status_id') {
                $oldValue = $jobStatusList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $jobStatusList[$newValue] ?? ($newValue ?? 'N/A');
                } 
            
            
            
                      $sizeFetchSalesOrder = null;

    // Check if key is like "s1", "s2", etc.
    if (preg_match('/^s(\d+)$/', $key, $matches)) {
        $sizeIndex = (int)$matches[1] - 1; // s1 -> index 0

        if (isset($sizes[$sizeIndex])) {
            $sizeFetchSalesOrder = DB::table('size_detail')->where('size_id', $sizes[$sizeIndex])->first();
        }
    }
             
            
            
            
              $fieldLabelSO = $fieldLabelSO[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesInward[] = [
                'field' => $fieldLabelSO,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'color_name'=>$color_name, 
                'tr_code'=>$tr_code, 
                'action_type'=>$action_type
                
            ];
            
            
            @endphp
                <tr>
          <td>{{ $action_type }}</td>
        <td data-order="{{ strtotime($action_timestamp) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($action_timestamp)) }}</td>
        <td>{{ $tr_code }}</td>
        <td>{{ $color_name }}</td> 
        <td>{{  $sizeFetchSalesOrder->size_name ?? $fieldLabelSO }}</td>
        <td>{{ $oldValue ?? 'N/A', }}</td>
        <td>{{ $newValue ?? 'N/A' }}</td>
        <td>{{ $userName }}</td>
    </tr>
            
            
       @endif
       @php   $i++; @endphp
   @endforeach

@empty
    
        <tr>
        <td colspan="6">No changes found.</td>
    </tr>
@endforelse
    

<tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $ActivityLogListDataSalesOrderList->links() }}
        </div>
    </td>
</tr>
