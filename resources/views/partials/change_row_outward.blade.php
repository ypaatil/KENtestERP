<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $paginatedChangesOUTWARD->links() }}
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
    

@forelse($paginatedChangesOUTWARD as $change)
    <tr>
          <td>{{ $change['action_type'] }}</td>
        <td data-order="{{ strtotime($change['action_timestamp']) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($change['action_timestamp'])) }}</td>
        <td>{{ $change['trCode'] }}</td>
        <td>{{ $change['item_code'] }}</td> 
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
            {{ $paginatedChangesOUTWARD->links() }}
        </div>
    </td>
</tr>
