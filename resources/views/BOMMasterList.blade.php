@extends('layouts.master') 
@section('content')   
<style>
    .text-right
    {
        text-align:right;
    }
    .navbar-brand-box
    {
        width: 266px !important;
    }
</style>
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">BOM</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">BOM</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row"> 
    <div class="col-lg-4"> 
    @if($chekform->write_access==1)
            <a href="{{ Route('BOM.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
    @endif
    </div>
    <div class="col-lg-4 text-center">
       <h4><b>Note : </b> Showing last 3 month records. If you want to all click on <a href="javascript:void(0);"  onclick="showAll();">Show All Data</a> button</h4>
    </div>
    <div class="col-lg-4 text-right">
        <button type="button" class="btn btn-warning w-md float-right" onclick="showAll();">Show All Data</button> &nbsp; &nbsp; &nbsp;
        <button type="button" class="btn btn-danger w-md float-right" onclick="back();">Back</button> &nbsp; &nbsp; &nbsp;
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
            <table data-page-length='25' id="bom_table" class="table table-bordered dt-responsive nowrap w-100 footable_2">
               <thead>
                  <tr style="text-align:center;">
                     <th nowrap>Sr No.</th>
                     <th nowrap>BOM No.</th>
                     <th nowrap>Order No.</th>
                     <th nowrap>Created Date</th>
                     <th nowrap>Buyer Name</th> 
                     <th nowrap>Brand</th> 
                     <th nowrap>Style Category</th> 
                     <th nowrap>Style No.</th> 
                     <th nowrap>User Name</th>
                     <th nowrap>Updated Date</th>
                     <th nowrap>BOM Print</th>
                     <th nowrap>Budget Print</th>
                     <th nowrap>Repeat</th> 
                     <th nowrap>Edit</th>
                     <th nowrap>Delete</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript"> 
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
   
   
     $(function () 
    {
        var url = 'BOM?page=0';
        TableData(url);
    });
  
    function showAll()
    {
        var url = 'BOM?page=1';
        TableData(url);
    }
    
    function back()
    {
        var url = 'BOM?page=0';
        TableData(url);
    }

  
    function TableData(url)
    {
        $('#bom_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#bom_table').DataTable({
        processing: true,
        serverSide: false,
        ajax: url,
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excel', exportOptions: { modifier: { page: 'all', search: 'none' } } },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {
                data: null,  // no data from server
                render: function (data, type, row, meta) {
                    return meta.row + 1; // serial number
                },
                title: 'Sr No'
          },
          {data: 'bom_code', name: 'bom_code'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {
                data: 'bom_date',
                name: 'bom_date',
                render: function(data, type, row) 
                {
                    return moment(data).format('DD/MM/YYYY');
                }
          }, 
          {data: 'ac_short_name', name: 'ac_short_name'}, 
          {data: 'brand_name', name: 'brand_name'}, 
          {data: 'mainstyle_name', name: 'mainstyle_name'}, 
          {data: 'fg_name', name: 'fg_name'}, 
          {data: 'username', name: 'username'}, 
          {data: 'updated_at', name: 'updated_at'}, 
          {data: 'action1', name: 'action1',orderable: false, searchable: false, className: 'text-center'},
          {data: 'action2', name: 'action2',orderable: false, searchable: false, className: 'text-center'},
          {data: 'action8', name: 'action8',orderable: false, searchable: false, className: 'text-center'}, 
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action4', name: 'action4',orderable: false, searchable: false},
        ]
    });
    
  }
   
</script>  
@endsection