<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $paginatedChangesOutward->links() }}
        </div>
    </td>
</tr>
        
        <tr>
            <th nowrap>Action</th>   
            <th nowrap>Date / Time</th>  
            <th nowrap>Out Code</th>  
            <th nowrap>Track Code</th>    
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th nowrap>Changed By</th>  
        </tr>
    </thead>
    

@forelse($paginatedChangesOutward as $change)
    <tr>
          <td>{{ $change['action_type'] }}</td>
        <td data-order="{{ strtotime($change['action_timestamp']) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($change['action_timestamp'])) }}</td>
        <td>{{ $change['fout_code'] }}</td>
        <td>{{ $change['track_code'] }}</td> 
        <td>{{ $change['field'] }}</td>
        <td>{{ $change['old'] }}</td>
        <td>{{ $change['new'] }}</td>
        <td>{{ $change['userName'] }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6">No changes found.</td>
    </tr>
@endforelse

<tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $paginatedChangesOutward->links() }}
        </div>
    </td>
</tr>
