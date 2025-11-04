      
   @extends('layouts.master') 

@section('content')   
 
                         
                        @if(session()->has('message'))
                        <div class="col-md-3">
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        </div>
                        @endif

                        @if(session()->has('messagedelete'))
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                {{ session()->get('messagedelete') }}
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                    <table id="tbl" class="table table-bordered dt-responsive nowrap w-100 ">
                                          <thead>
                                            <tr style="text-align:center;">
                                                <th>Date</th>
                                                <th>Supplier Name</th>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Color</th>
                                                <th>Item Description</th>
                                                <th>Total Rolls</th>
                                                <th>Total Meter</th>
                                                <th>Total Pass Meter</th>
                                                <th>Total Reject Meter</th>
                                                <th>Total Pass % </th>
                                                <th>Total Reject % </th>
                                                <th>Total Rolls Pass </th>
                                                <th>Total Rolls Reject </th>
                                             </tr>
                                            </thead>
        
                                            <tbody>

                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(function () {

  	 $('#tbl').DataTable().clear().destroy();
    
       var table = $('#tbl').DataTable({
        //processing: true,
       // serverSide: true,
       // "pageLength": 10,
        ajax: "{{ route('FabricCheckingDashboard') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        
      
        columns: [
          {data: 'chk_date', name: 'chk_date'},
           {data: 'Ac_name', name: 'ledger_master.Ac_name'},
          {data: 'item_code', name: 'fabric_checking_details.item_code'},
          {data: 'item_name', name: 'item_master.item_name'},
          {data: 'color_name', name: "item_master.color_name"},
          {data: 'item_description', name: 'item_master.item_description'},
          {data: 'totalRolls', name: 'totalRolls'},
          {data: 'totalMeter', name: 'totalMeter'},
          {data: 'totalPassMeter', name: 'totalPassMeter'},
          {data: 'totalRejectMeter', name: 'totalRejectMeter'},
          {data: 'totalPassPer', name: 'totalPassPer'},
          {data: 'totalRejectPer', name: 'totalRejectPer'},
          {data: 'totalRollPass', name: 'totalRollPass'},
          {data: 'totalRollReject', name: 'totalRollReject'},
            
        ]
    });
    
  });
</script> 
                        
                        @endsection