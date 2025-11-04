@extends('layouts.operationapp')
@section('styles')
<!-- INTERNAL Data table css -->

			<link href="{{URL::asset('operation/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('operation/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('operation/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('operation/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('operation/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
@endsection
@section('content')
<!--Page header-->
<div class="page-header d-lg-flex d-block">
   <div class="page-leftheader">
      <h4 class="page-title">Assign To Order Master List</h4>
   </div>
   @if(session()->has('message'))
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
   @endif
   <div class="page-rightheader ml-md-auto">
      <div class=" btn-list">
         <button  class="btn btn-light" data-toggle="tooltip" data-placement="top" title="E-mail"> <i class="feather feather-mail"></i> </button>
         <button  class="btn btn-light" data-placement="top" data-toggle="tooltip" title="Contact"> <i class="feather feather-phone-call"></i> </button>
         <button  class="btn btn-primary" data-placement="top" data-toggle="tooltip" title="Info"> <i class="feather feather-info"></i> </button>
      </div>
   </div>
</div>
<!--End Page header-->
<!-- Row -->
<div class="row">
   <div class="col-12">
      <!--/div-->
      <!--div-->
      <!--/div-->
      <!--div-->
      <div class="card">

         <div class="card-header border-bottom-0">
            <a href="{{ Route('assign_to_order.create') }}"><input type='button' class="btn btn-info mt-2" value='Add New Record' ></a>
         </div>

       <div class="row">
   <div class="col-12">
      <div class="card">
      <div class="card-body">
            <div class="table-responsive">
               <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="task-list">
                  <thead>
                     <tr>
                        <th class="border-bottom-0 text-center">Sr.No.</th>
                         <th class="border-bottom-0">Style</th>
                         <th class="border-bottom-0">Order</th>
                         <th class="border-bottom-0 text-center">Delete</th>
                     </tr>
                  </thead>
                <tbody>
               </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
         
      </div>
      <!--/div-->
      <!--div-->
      <!--/div-->
      <!--div-->
      <!--div-->
   </div>
</div>
<!-- /Row -->
@endsection('content')
@section('modals')
<!--Change password Modal -->
<div class="modal fade"  id="changepasswordnmodal">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Password</h5>
            <button  class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="form-group">
               <label class="form-label">New Password</label>
               <input type="password" class="form-control" placeholder="password" value="">
            </div>
            <div class="form-group">
               <label class="form-label">Confirm New Password</label>
               <input type="password" class="form-control" placeholder="password" value="">
            </div>
         </div>
         <div class="modal-footer">
            <a href="#" class="btn btn-outline-primary" data-dismiss="modal">Close</a>
            <a href="#" class="btn btn-primary">Confirm</a>
         </div>
      </div>
   </div>
</div>
<!-- End Change password Modal  -->
@endsection('modals')
@section('scripts')
<!-- INTERNAL Data tables -->

	
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/jszip.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/pdfmake/pdfmake.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/pdfmake/vfs_fonts.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/js/datatables.js')}}"></script>
    	<script src="{{URL::asset('operation/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>


<script type="text/javascript"> 



  $(function () {
    var url = window.location.href;  
  	 $('#task-list').DataTable().clear().destroy();
    
       var table = $('#task-list').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,        
       "order": [[ 0, "desc" ]],
      dom: 'lBfrtip',
           buttons: [
       'copy', 'csv', 'excel', 'pdf','print'
    ],
         "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50,100, "All"] ],    
        columns: [
                  {data: null, name: 'serial_no', searchable: false, orderable: false, render: function (data, type, row, meta) {
                // Calculate the serial number globally
                return meta.settings._iDisplayStart + meta.row + 1; // Start from the correct serial number based on the current page
            }}, 
              {data: 'mainstyle_name', name: "main_style_master_operation.mainstyle_name"},
              {data: 'sales_order_no', name: 'sales_order_no'},
             {data: 'action2', name: 'action2',orderable: false, searchable: false},
         
           
        ]
    });
    
  });


   $(document).ready(function(){
     $(document).on('click','.DeleteRecord',function(e) {
   
        var Route = $(this).attr("data-route");
       var id = $(this).data("id");
       var token = $(this).data("token");
       
       //alert('helo');
   
   
           //pop up
           swal({
               title: "Are you sure?",
               text: "It will permanently deleted !", 
               icon: "warning",
               buttons: true,
               dangerMode: true,
           })
           .then((willDelete) => {
   
        if (willDelete) {
   
     $.ajax({
            url: Route,
            type: "DELETE",
             data: {
             "id": id,
             "_method": 'DELETE',
              "_token": token,
              },
            
            success: function(data){
   
            }
   });
   
   	swal({
   				title: "Success",
   				text: "Record has been deleted",
   				icon: "success",
   			});
   
             
   	setTimeout(function() {location.reload()}, 1000);
             } else {
               
             }
   
           });
       });
   });
   
   
   
</script>
@endsection