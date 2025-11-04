@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Outward For Packing</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Outward For Packing</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('OutwardForPacking.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
@endif
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
            <table data-order='[[ 1, "desc" ]]' data-page-length='25' id="Outward_For_Packing_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th><a href="javascript:void(0);" class="btn-sm btn-warning"  onclick="GetCheckBox(this);" >Print</a></th>
                     <th>SrNo</th>
                     <th>Issue Code</th>
                     <th>Sales Order No</th>
                     <th>Entry Date</th>
                     <th>Buyer Name</th>
                     <th>Vendor Name</th>
                     <th>Total Qty</th>
                     <th>User</th>
                     <th>Print</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 

    function GetCheckBox() 
    {
        // Initialize an empty array to store selected ofp_code values
        var chk_arr = [];
        
        // Iterate through all checkboxes with the class 'chk'
        $('.chk').each(function() {
            if ($(this).prop('checked')) {
                // Push the 'ofp_code' attribute of the checked checkbox into the array
                chk_arr.push($(this).attr('ofp_code'));
            }
        });
    
        // If there are selected items, construct the URL and redirect
        if (chk_arr.length > 0) {
            const baseUrl = "OutwardForPackingMergedPrint";
            
            // Convert array to a comma-separated string for better URL handling
            const queryString = `ofp_codes=${chk_arr.join(',')}`;
            
            // Redirect to the URL with the constructed query string
            window.location.href = `${baseUrl}?${queryString}`;
        } else {
            alert("No items selected!");
        }
    }


        
   $(document).on('click','.DeleteRecord',function(e) 
   {
   
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
      if (confirm("Are you sure you want to Delete this Record?") == true) 
      {
            $.ajax({
               url: Route,
               type: "DELETE",
                data: {
                "id": id,
                "_method": 'DELETE',
                 "_token": token,
                 },
               
               success: function(data)
               {
                     location.reload();
               }
           });
       }
   
   });
   
    $(function () 
    {
        $('#Outward_For_Packing_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#Outward_For_Packing_table').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('OutwardForPacking.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'chk', name: 'chk', 'className': 'text-center'},
          {data: 'ofp_code1', name: 'ofp_code1'},
          {data: 'ofp_code', name: 'ofp_code'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'ofp_date', name: "ofp_date"},
          {data: 'Ac_name', name: 'Ac_name'},
          {data: 'vendor_name', name: 'vendor_name'},
          {data: 'total_qty', name: 'total_qty'},
          {data: 'username', name: 'username'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false}
        ]
    });
    
  });
</script>  
@endsection