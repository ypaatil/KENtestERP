<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
        {{--    {{ $ActivityLogListDataSalesOrderList->links() }}  --}}
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
     'pki_date' =>'Date',  
     'class_id' =>'Item',
   
     // Add more fields as needed
    ];
        
         
                $ClassList = DB::table('classification_master')->pluck('class_name','class_id')->toArray();
            
        
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
        $class_name=$logSO->class_name; 
        
        



  $i = 0;
@endphp
     

    @foreach ($new as $key => $newValue) 
       @php $oldValue = $old[$key] ?? null; @endphp

        @if ($oldValue != $newValue) 
           
           @php
        
              if ($key === 'class_id') {
                $oldValue = $ClassList[$oldValue] ?? ($oldValue ?? 'N/A');
                $newValue = $ClassList[$newValue] ?? ($newValue ?? 'N/A');
            }  
            
            
            
            
            
              $fieldLabelSO = $fieldLabelSO[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesInward[] = [
                'field' => $fieldLabelSO,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'class_name'=>$class_name, 
                'tr_code'=>$tr_code, 
                'action_type'=>$action_type
                
            ];
            
            
            @endphp
                <tr>
          <td>{{ $action_type }}</td>
        <td data-order="{{ strtotime($action_timestamp) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($action_timestamp)) }}</td>
        <td>{{ $tr_code }}</td>
        <td>{{ $class_name }}</td> 
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
          {{--  {{ $ActivityLogListDataSalesOrderList->links() }}  --}}
        </div>
    </td>
</tr>
