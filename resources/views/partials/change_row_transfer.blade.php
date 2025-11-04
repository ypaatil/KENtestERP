<thead>
          <tr>
    <td colspan="6">
        <div class="pagination-wrapper">
            {{ $paginatedChanges->links() }}
        </div>
    </td>
</tr>
        
        <tr>
            <th nowrap>Date / Time</th>  
            <th nowrap>TPKI Code</th>  
            <th nowrap>Color Name</th>    
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th nowrap>Changed By</th>  
        </tr>
    </thead>

@forelse($paginatedChanges as $change)
    <tr>
        <td data-order="{{ strtotime($change['action_timestamp']) }}" nowrap>{{ date('d-m-Y h:i:s A', strtotime($change['action_timestamp'])) }}</td>
        <td>{{ $change['pki_code'] }}</td>
          <td>{{ $change['color_name'] }}</td>
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
            {{ $paginatedChanges->links() }}
        </div>
    </td>
</tr>
