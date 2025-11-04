@extends('layouts.master') 
@section('content')   
<!-- end page title --> 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">FG Location Transfer Inward List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">FG Location Transfer Inward List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('FGOutletOpening.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
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
            <table data-page-length='25' id="Transfer_Packing_Inhouse_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr No</th>
                     <th>Code</th>
                     <th>Total Qty</th>
                     <th>Total Value</th>
                     <th>User</th>
                     <th>Barcode</th>
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
<!--<div class="barcode-container">-->
<!--    <div class="barcode-info">-->
<!--        <b>Ken Global Design Pvt. Ltd.</b></br>-->
<!--        <svg id="barcode"></svg></br>-->
<!--        <b>Style: </b> Mens Boxer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>Size: </b> XL</br>-->
<!--        <b>Rate: </b> ₹&nbsp;305 &nbsp;&nbsp;<b>(Inc. of all taxes)</b>-->
<!--    </div>-->
<!--</div>-->
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

<script type="text/javascript"> 
    
    // function GenerateBarcode()
    // {
    //     const barcodeData = "S_16_8925"; // Simplified product code

    //     JsBarcode("#barcode", barcodeData, {
    //       format: "CODE128",       // CODE128 format
    //       lineColor: "#000",       // Color of the barcode lines
    //       width: 2,                // Width of the lines
    //       height: 80,              // Height of the barcode
    //       displayValue: true,      // Whether to display the value under the barcode
    //       fontSize: 14,            // Size of the text under the barcode
    //       textAlign: "center",     // Align text in the center
    //       textMargin: 5,           // Margin between the barcode and text
    //       margin: 10,              // Margin around the entire barcode
    //       background: "#fff",      // Background color of the barcode area
    //       marginTop: 10,           // Margin on top of the barcode
    //       marginBottom: 10         // Margin below the barcode
    //     });
    // }
    
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
        $('#Transfer_Packing_Inhouse_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#Transfer_Packing_Inhouse_table').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('FGOutletOpening.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'srno', name: 'srno'},
          {data: 'fgo_code', name: 'fgo_code'}, 
          {data: 'total_qty', name: 'total_qty'},
          {data: 'total_value', name: 'total_value'},
          {data: 'username', name: 'username'},
          {data: 'action0', name: 'action0',orderable: false, searchable: false},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false}
        ]
    });
    
  });
</script>  
@endsection