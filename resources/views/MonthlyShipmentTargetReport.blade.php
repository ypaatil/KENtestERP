@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Monthly Shipment Target Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Monthly Shipment Target Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table  id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                  <thead style="border-top: 0.5px solid;border-bottom: 0.5px solid;">
                     <tr>
                        <th style="text-align: center;background:#0000ff59;color:#fff;" nowrap >Sr. No.</th>
                        <th style="text-align: center;background:#3bc3907a;" nowrap >Buyer Name</th>
                        <th style="text-align: center;background:#f1b44c66;" colspan="3" >B2P</th>
                        @foreach($years as $row)
                            <th style="text-align: center;background:#343a4082;color:#fff;">{{$row}}</th>
                        @endforeach
                        <th style="text-align: center;background:#f1b44c66;" colspan="3" nowrap>Total Planned B2P Qty</th>
                        <th style="text-align: center;background:#ff00006e" colspan="3" nowrap>Total Unplanned B2P Qty</th>
                     </tr>
                     <tr>
                        <th style="text-align: center;background:#0000ff59;color:#fff;"></th>
                        <th style="text-align: center;background:#3bc3907a;" ></th>
                        <th style="text-align: center;background:#f1b44c66;" nowrap >L PCS</th>
                        <th style="text-align: center;background:#f1b44c66;" nowrap >L Min</th>
                        <th style="text-align: center;background:#f1b44c66;" nowrap >Rs. Cr</th>
                        @foreach($months as $row)
                            <th style="text-align: center;background:#343a4082;color:#fff;" >{{$row}}</th>
                        @endforeach
                        <th style="text-align: center;background:#f1b44c66;" nowrap >L PCS</th>
                        <th style="text-align: center;background:#f1b44c66;" nowrap >L Min</th>
                        <th style="text-align: center;background:#f1b44c66;" nowrap >Rs. Cr</th>
                        <th style="text-align: center;background:#ff00006e;" nowrap >L PCS</th>
                        <th style="text-align: center;background:#ff00006e;" nowrap >L Min</th>
                        <th style="text-align: center;background:#ff00006e;" nowrap >Rs. Cr</th>
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
      $(function()
      {
        $.ajax({
            dataType: "json",
            url: "{{ route('LoadMonthlyShipmentTargetReport') }}", 
            success: function(data)
            {
          
                if ($.fn.DataTable.isDataTable('#datatable-buttons')) 
                {
                    $('#datatable-buttons').DataTable().destroy();
                }
                $("tbody").html(data.html); 
                $('#datatable-buttons').DataTable({
                    "ordering": false,
                    "paging": false
                });
                
                let columnSums = [];
                
                // Iterate over each row in the tbody
                $('#datatable-buttons tbody tr').each(function() {
                    $(this).find('td').each(function(index) {
                        let value = parseFloat($(this).text());
                        if (!isNaN(value)) {
                            columnSums[index] = (columnSums[index] || 0) + value;
                        }
                    });
                });
                
                // Create a new row for the sums
                let sumRow = '<tr style="background: #ffff0066;">';
                sumRow += '<td></td><td style="border-right: 0.5px solid;"><b>Total</b></td>'; // First cell can be "Total" or empty if you want to keep it for values only
                for (let i = 2; i < columnSums.length; i++) {
                    let formattedSum = columnSums[i] ? columnSums[i].toFixed(2) : '0.00';
                    sumRow += '<td style="border-right: 0.5px solid;"><b>' + formattedSum + '</b></td>';
                }
                sumRow += '</tr>';
                
                // Append the sum row to the table
                $('#datatable-buttons').append('<tfoot>' + sumRow + '</tfoot>');
            
            }
        });
      });
</script>
@endsection