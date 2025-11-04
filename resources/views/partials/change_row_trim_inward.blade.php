<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $ActivityLogListTrimsIO->links() }}
        </div>
    </td>
</tr>
        <tr>
            <th nowrap>Action</th>   
            <th nowrap>Date / Time</th>  
            <th nowrap>TR. Code</th>  
            <th nowrap>Item Code</th>    
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th nowrap>Changed By</th>  
        </tr>
    </thead>
    
    
    @php
    
      $fieldLabelTIO = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
         $formattedChangesTIO = [];
     
         $TrimsINOUTMAP=[];
      
      foreach($ActivityLogListTrimsIO as $rowTIO)
      {
          
          $TrimsINOUTMAP[$rowTIO->module_name][]=$rowTIO;
          
      }
     
     @endphp

  @forelse($ActivityLogListTrimsIO as $logTIO) 
  
  @php
    $old = json_decode($logTIO->old_data, true);
    $new = json_decode($logTIO->new_data, true);
    
        $userName=$logTIO->username;
        $action_timestamp=$logTIO->action_timestamp;
        $item_code=$logTIO->item_code; 
        $action_type=$logTIO->action_type; 
        $trCode=$logTIO->trCode; 
        $module_name= $logTIO->module_name; 
               
     @endphp
     

    @foreach ($new as $key => $newValue) 
      
      
      @php
      
        $oldValue = $old[$key] ?? null; @endphp

        @if($oldValue != $newValue) 
          
          @php
          
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelTIO = $fieldLabelTIO[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesTIO[] = [
                'field' => $fieldLabelTIO,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'trCode'=>$trCode, 
                'item_code'=>$item_code, 
                'action_type'=>$action_type,
                'module_name'=>$module_name
                
            ];
            
            @endphp
            
            
              <tr>
          <td>{{ $action_type }}</td>
        <td data-order="{{ strtotime($action_timestamp) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($action_timestamp)) }}</td>
        <td>{{ $trCode }}</td>
        <td>{{ $item_code }}</td> 
        <td> test </td>
        <td>{{  $oldValue ?? 'N/A' }}</td>
        <td>{{ $newValue ?? 'N/A' }}</td>
        <td>{{ $userName }}</td>
    </tr>
        
        @endif
        
       
       
   @endforeach

    
    
@empty
    <tr>
        <td colspan="6">No changes found.</td>
    </tr>
@endforelse

    

<tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $ActivityLogListTrimsIO->links() }}
        </div>
    </td>
</tr>