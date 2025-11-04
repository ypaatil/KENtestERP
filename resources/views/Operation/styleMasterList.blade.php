@extends('layouts.operationapp')

@section('styles')

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
								<h4 class="page-title">Data Table</h4>
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

								<div class="card">
									<div class="card-header border-bottom-0">
									     @if($chekform->write_access==1)
										<a href="{{ Route('Style.create') }}"><input type='button' class="btn btn-info mt-2" value='Add New Record' ></a>
										@endif
									</div>
									<div class="card-body">
										<div class="">
											<div class="table-responsive">
												<table id="file-datatable1" class="table table-bordered text-nowrap key-buttons border-bottom">
													<thead>
														<tr>
                                                            <th class="border-bottom-0">Sr.No.</th>
															<th class="border-bottom-0">Style</th>
															<th class="border-bottom-0">Category</th>	
															<th class="border-bottom-0">Sub Category</th>		
                                             				<th class="border-bottom-0">Edit</th>
                                             				<th class="border-bottom-0">Print</th>	
                                              				<!--<th class="border-bottom-0">Delete</th>-->

														</tr>
													</thead>
													<tbody>
                             	    
													    
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
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

  	 $('#file-datatable1').DataTable().clear().destroy();
    
    var table = $('#file-datatable1').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('Style.index') }}",
          "order": [[ 0, "DESC" ]],
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
            {data: 'mainstyle_name', name: 'main_style_master_operation.mainstyle_name'},
             {data: 'cat_name', name: 'item_category.cat_name'},
             {data: 'sub_cat_name', name: 'sub_category_masters.sub_cat_name'}, 
             {data: 'action1', name: 'action1',orderable: false, searchable: false},
              {data: 'action3', name: 'action3',orderable: false, searchable: false}, 
            // {data: 'action2', name: 'action2',orderable: false, searchable: false},
          
        ]
    });
    
  });






$(document).ready(function(){
  $(document).on('click','.DeleteRecord',function(e) {

     var Route = $(this).attr("data-route");
    var id = $(this).data("id");
    var token = $(this).data("token");


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
				text: "Style has been deleted",
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
