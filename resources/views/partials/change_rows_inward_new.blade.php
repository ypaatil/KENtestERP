<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $ActivityLogListDataFabricInward->links() }}
        </div>
    </td>
</tr>
        
        <tr>
            <th nowrap>Action</th>   
            <th nowrap>Date / Time</th>  
            <th nowrap>In Code</th>  
            <th nowrap>Track Code</th>    
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th nowrap>Changed By</th>  
        </tr>
    </thead>
    
    @php
         $fieldLabelInward = [
     'pki_date' =>'Date',  
     'color_id' =>'Color'
     // Add more fields as needed
    ];
        
        
     $formattedChangesInward = [];
     
     @endphp

@forelse ($ActivityLogListDataFabricInward as $logInward) 
  
  @php
    $old = json_decode($logInward->old_data, true);
    $new = json_decode($logInward->new_data, true);
    
        $userName=$logInward->username;
        $action_timestamp=$logInward->action_timestamp;
        $in_code=$logInward->in_code; 
        $action_type=$logInward->action_type; 
        $track_code=$logInward->track_code; 
               

@endphp
     

    @foreach ($new as $key => $newValue) 
       @php $oldValue = $old[$key] ?? null; @endphp

        @if ($oldValue != $newValue) 
           
           @php
            // Replace IDs with names for specific fields
            // if ($key === 'color_id') {
            //     $oldValue = $colors[$oldValue] ?? ($oldValue ?? 'N/A');
            //     $newValue = $colors[$newValue] ?? ($newValue ?? 'N/A');
            // }  
            
            
              $fieldLabelInward = $fieldLabelInward[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $formattedChangesInward[] = [
                'field' => $fieldLabelInward,
                'old' => $oldValue ?? 'N/A',
                'new' => $newValue ?? 'N/A',
                'userName'=>$userName,
                'action_timestamp'=>$action_timestamp,
                'track_code'=>$track_code, 
                'in_code'=>$in_code, 
                'action_type'=>$action_type
                
            ];
            
            
            @endphp
                <tr>
          <td>{{ $action_type }}</td>
        <td data-order="{{ strtotime($action_timestamp) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($action_timestamp)) }}</td>
        <td>{{ $in_code }}</td>
        <td>{{ $track_code }}</td> 
        <td>{{ $fieldLabelInward }}</td>
        <td>{{ $oldValue ?? 'N/A', }}</td>
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
            {{ $ActivityLogListDataFabricInward->links() }}
        </div>
    </td>
</tr>
