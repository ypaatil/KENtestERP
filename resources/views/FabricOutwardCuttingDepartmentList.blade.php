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
         <h4 class="mb-sm-0 font-size-18">Fabric Outward - Cutting Department List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Outward - Cutting Department List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-4">
      <a href="{{ Route('FabricOutwardCuttingDepartment.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                        <th>FOCD Code</th>
                        <th>FOCD Date</th>
                        <th>DC No</th>
                        <th>Outward Date</th>
                        <th>Vendor Name</th>
                        <th>Cutting PO No</th>
                        <th>Main Style Category</th>
                        <th>Sub Style Category</th>
                        <th>Style Name</th>
                        <th>Style No</th>
                        <th>Style Description</th>
                        <th>Total Challan Meter</th>
                        <th>Total Received Meter</th>
                        <th>Total Roll</th>
                        <th>Total Outward</th>
                        <th>User Name</th>
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
        var url = 'FabricOutwardCuttingDepartment?page=0';
        TableData(url);
    });
  
    function showAll()
    {
        var url = 'FabricOutwardCuttingDepartment?page=1';
        TableData(url);
    }
    
    function back()
    {
        var url = 'FabricOutwardCuttingDepartment?page=0';
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
          {data: 'focd_code', name: 'focd_code'},
          {data: 'focd_date', name: 'focd_date'},
          {data: 'dc_no', name: "dc_no"},
          {data: 'outward_date', name: 'outward_date'},
          {data: 'ac_short_name', name: 'ac_short_name'},
          {data: 'cutting_po_no', name: 'cutting_po_no'},
          {data: 'mainstyle_name', name: 'mainstyle_name'},
          {data: 'substyle_name', name: 'substyle_name'},
          {data: 'fg_name', name: 'fg_name'},
          {data: 'style_no', name: 'style_no'},
          {data: 'style_description', name: 'style_description'},
          {data: 'total_challan_meter', name: 'total_challan_meter'},
          {data: 'total_received_meter', name: 'total_received_meter'},
          {data: 'total_roll', name: 'total_roll'},
          {data: 'total_outward_meter', name: 'total_outward_meter'},
          {data: 'username', name: 'username'},
          {data: 'action1', name: 'action1'},
          {data: 'action2', name: 'action2'},
         
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