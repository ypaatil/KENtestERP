@extends('layouts.master') 
@section('content')   
<!-- end page title --> 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Barcode Brand List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Barcode Brand List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
  
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('barcode_brand.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>

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
            <table data-page-length='25' id="barcode_brand" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr No</th>
                     <th nowrap>Brand</th>
                     <th nowrap>Style</th>
                     <th>User</th>
                     <th>Barcode</th>
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
        $('#barcode_brand').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#barcode_brand').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('barcode_brand.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'srno', name: 'srno'},
          {data: 'brand_name', name: 'brand_name'}, 
          {data: 'brand_name', name: 'brand_name'},    
          {data: 'username', name: 'username'},
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          
        ]
    });
    
  });
</script>  
@endsection