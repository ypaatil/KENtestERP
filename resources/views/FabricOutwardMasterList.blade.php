@extends('layouts.master') 
@section('content')   
<style>
    .text-right
    {
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Outward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Outward</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-4">
      <a href="{{ Route('FabricOutward.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
    <div class="col-lg-4 text-center">
       <h4><b>Note : </b> Showing last 3 month records. If you want to all click on <a href="javascript:void(0);"  onclick="showAll();">Show All Data</a> button</h4>
    </div>
    <div class="col-lg-4 text-right">
        <button type="button" class="btn btn-warning w-md float-right" onclick="showAll();">Show All Data</button> &nbsp; &nbsp; &nbsp;
        <button type="button" class="btn btn-danger w-md float-right" onclick="back();">Back</button> &nbsp; &nbsp; &nbsp;
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
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="fabric_outward_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>SRNO</th>
                     <th>DC No</th>
                     <th>Issue Date</th>
                     <th>Vendor Name</th>
                     <th>Cutting PO No</th>
                     <th>Sales Order No</th>
                     <th>Total Meter</th>
                     <th>Total Amount</th>
                     <th>Total Taga</th>
                     <th>GST Print</th>
                     <th>Rolls Print</th>
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
 $(function () 
    {
        var url = 'FabricOutward?page=0';
        TableData(url);
    });
  
    function showAll()
    {
        var url = 'FabricOutward?page=1';
        TableData(url);
    }
    
    function back()
    {
        var url = 'FabricOutward?page=0';
        TableData(url);
    }

  
  function TableData(url)
  {
        $('#fabric_outward_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#fabric_outward_table').DataTable({
        processing: true,
        serverSide: false,
        ajax: url,
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'fout_code1', name: 'fout_code1'},
          {data: 'fout_code', name: 'fout_code'},
          {data: 'fout_date', name: 'fout_date'},
          {data: 'Ac_name', name: "Ac_name"},
          {data: 'vpo_code', name: 'vpo_code'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'total_meter', name: 'total_meter'},
          {data: 'total_amount', name: 'total_amount'},
          {data: 'total_taga_qty', name: 'total_taga_qty'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action4', name: 'action3',orderable: false, searchable: false},
        ]
    });
    
  }
  
   $(document).on('click','.DeleteRecord',function(e) {
   
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
     //alert(Route);
    
    //alert(data);
      if (confirm("Are you sure you want to Delete this Record?") == true) {
    $.ajax({
           url: Route,
           type: "DELETE",
            data: {
            "id": id,
            "_method": 'DELETE',
             "_token": token,
             },
           
           success: function(data){
   
              //alert(data);
           location.reload();
   
           }
   });
   }
   
   });
</script> 
@endsection