<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $ActivityLogListDataPurchaseOrderList->links() }}
        </div>
    </td>
</tr>
        
        <tr>
            <th nowrap>Action</th>   
            <th nowrap>Date / Time</th>  
            <th nowrap>TR Code</th>  
            <th nowrap>Item Code</th>    
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th nowrap>Changed By</th>  
        </tr>
    </thead>
    
    @php
         $fieldLabelSO = [
     'camt' =>'CGST Amount',  
     'samt' =>'SGST Amount',
     'amount'=>'Amount',
     'item_qty'=>'Item Quantity',
     'disc_amount'=>'Discount Amount',
     'total_amount'=>'Total Amount'
    ];
        


     
     @endphp

@forelse ($ActivityLogListDataPurchaseOrderList as $logSO) 
  
  @php
    $old = json_decode($logSO->old_data, true);
    $new = json_decode($logSO->new_data, true);
    
        $userName=$logSO->username;
        $action_timestamp=$logSO->action_timestamp;
        $tr_code=$logSO->tr_code; 
        $action_type=$logSO->action_type; 
        $item_code=$logSO->item_code; 
        $sales_order_no=$logSO->sales_order_no;

         
         



  $i = 0;
@endphp
     

    @foreach ($new as $key => $newValue) 
       @php $oldValue = $old[$key] ?? null; @endphp
       

        @if($oldValue != $newValue) 
           
           @php $fieldLabelSO = $fieldLabelSO[$key] ?? ucfirst(str_replace('_', ' ', $key)); @endphp
            
                <tr>
          <td>{{ $action_type }}</td>
        <td data-order="{{ strtotime($action_timestamp) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($action_timestamp)) }}</td>
        <td>{{ $sales_order_no }}</td>
        <td>{{ $item_code }}</td> 
        <td>{{ $fieldLabelSO }}</td>
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
            {{ $ActivityLogListDataPurchaseOrderList->links() }}
        </div>
    </td>
</tr>
