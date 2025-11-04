@extends('layouts.operationapp')

@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{URL::asset('operation/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('operation/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('operation/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />

		<link href="{{URL::asset('operation/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('operation/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		
		 <link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">
        <link href="{{URL::asset('operation/assets/plugins/date-picker/date-picker.css')}}" rel="stylesheet" />
          <meta name="csrf-token" content="{{ csrf_token() }}">
        
@endsection

@section('content')

						<!--Page header-->
						<div class="page-header d-xl-flex d-block">
							<div class="page-leftheader">
								<h4 class="page-title">Attendance</h4>
							</div>
							<div class="page-rightheader ml-md-auto">
								<div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
									<div class="btn-list">
									<!-- 	<a href="{{Route('Employee.create')}}" class="btn btn-primary mr-3">Add New Employee</a> -->
										<button  class="btn btn-light" data-toggle="tooltip" data-placement="top" title="E-mail"> <i class="feather feather-mail"></i> </button>
										<button  class="btn btn-light" data-placement="top" data-toggle="tooltip" title="Contact"> <i class="feather feather-phone-call"></i> </button>
										<button  class="btn btn-primary" data-placement="top" data-toggle="tooltip" title="Info"> <i class="feather feather-info"></i> </button>
									</div>
								</div>
							</div>
						</div>
						<!--End Page header-->

						<!-- Row-->
			
						<!-- End Row -->

						<!-- Row -->
						<!-- <div class="row">
							<div class="col-xl-4 col-lg-6 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<a class="col-7" href=" Route('PresentE') ">
												<div class="mt-0 text-left"> <span class="font-weight-semibold">Yesterday Present</span>
													<h3 class="mb-0 mt-1 text-success">$todayspresent </h3>
												</div>
											</a>
											<div class="col-5">
												<div class="icon1 bg-success-transparent my-auto  float-right"> <i class="las la-users"></i> </div>
											</div>
										</div>
									</div>
								</div>
							</div>
								<div class="col-xl-4 col-lg-6 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<a class="col-7" href="  Route('PresentNoOutPunch') ">
												<div class="mt-0 text-left"> <span class="font-weight-semibold"> Present  (No OutPunch)</span>
												
													<h3 class="mb-0 mt-1 text-secondary"> $todayspresentnooutpunch </h3>
												</div>
											</a>
											<div class="col-5">
												<div class="icon1 bg-secondary-transparent my-auto  float-right"> <i class="las la-users"></i> </div>
											</div>
										</div>
									</div>
								</div>
							</div>
					
								<div class="col-xl-4 col-lg-6 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<a class="col-7" href=" Route('AbsentE') ">
												<div class="mt-0 text-left"> <span class="font-weight-semibold"> Yesterday Absent</span>
												
													<h3 class="mb-0 mt-1 text-danger"> $todaysabsent </h3>
												</div>
											</a>
											<div class="col-5">
												<div class="icon1 bg-danger-transparent my-auto  float-right"> <i class="las la-users"></i> </div>
											</div>
										</div>
									</div>
								</div>
							</div>
				
						</div>	 -->
        
     

			 <div class="row">
							<div class="col-xl-6 col-md-6 col-lg-6">
								<div class="card">
									<div class="card-header  border-0">
										<h4 class="card-title">Import Excel</h4>
									</div>
									<div class="card-body">
<div class="col-md-6">

					<form action="{{ route('attendance_import') }}" method="POST" enctype="multipart/form-data">
                @csrf
    <div class="input-group file-browser">
                                 <input type="text" class="form-control border-right-0 browse-file" placeholder="choose" readonly>
                                 <label class="input-group-append mb-0">
                                 <span class="btn ripple btn-primary">
                                 Browse <input type="file"  tabindex="33" class="file-browserinput" name="attendancefile" id="attendancefile"  style="display: none;" >
                                 </span>
                                 </label> 
                              </div>     <br> <br>

							<button class="btn btn-success">Import Attendance Data</button>
							</form>
       						 </div> 
 
									</div>
								</div>
							</div>
							
						 <!-- <div class="col-xl-6 col-md-6 col-lg-6">
								<div class="card">
									<div class="card-header  border-0">
										 <h4 class="card-title">Get Attendance Data</h4> 
									</div>
									<div class="card-body"> -->
<!-- <div class="col-md-6">

				
                                <div class="form-group">
                                       <label class="form-label">Attendance Date</label>
                                       <input type="text" name="attendanceDate" class="form-control datepickercls" placeholder="Attendance Date" id="attendanceDate">
                                    </div>  <br>
                                    
                                     <span id='loadingmessage' style="color:#60d313;font-weight:bold;font-size:22px;">

                                     <button class="btn btn-success" onclick="InsertAttendanceDetails()">Get Attendance Data</button>
                                     
                                     

        </div> -->




<!-- <input type="hidden" class="form-control" id="rediretUrl" value=" route('Attendance.index') "> -->
									<!-- </div>
								</div>
							</div>-->
						</div> 





						
						
												<div class="row">
												  
							<div class="col-xl-12 col-md-12 col-lg-12">
							     
								<div class="card"><br>
									<div class="card-header  border-0">
										<h4 class="card-title">Attendance  List</h4>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
												<thead>
													<tr>
												      <th class="border-bottom-0 w-10">Attendance Date</th>
													  <th class="border-bottom-0 w-10">View</th>
													   <th class="border-bottom-0 w-10">Delete</th>
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
						<!-- End Row-->

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

		<!-- INTERNAL Index js-->
<!-- 		<script src="{{URL::asset('operation/assets/js/hr/hr-emp.js')}}"></script> -->


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
   <script src="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
      <script src="{{URL::asset('operation/assets/plugins/date-picker/date-picker.js')}}"></script>
      <script src="{{URL::asset('operation/assets/plugins/date-picker/jquery-ui.js')}}"></script>

      <script src="{{URL::asset('operation/assets/plugins/date-picker/date-picker.js')}}"></script>
      
<script type="text/javascript">

  $(function () {
      
      
     

  	 $('#hr-table').DataTable().clear().destroy();
    
       var table = $('#hr-table').DataTable({
        processing: true,
        serverSide: true,
        serverMethod: 'get',
           "order": [[ 0, "desc" ]],
        
           ajax: {
                  url:"{{ route('get_attendance') }}",
         		  dataSrc: 'data'
               },
        
        "columnDefs": [{
      //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY HH:mm' )
      "render": function(data) {
        return moment(data).isValid() ? moment(data).format('DD-MM-YYYY') : "";
      },
      "targets": 0
    }],
        
        columns: [
           {data: 'lineAttendanceDate', name: 'lineAttendanceDate'},
           {data: 'action1', name: 'action1',orderable: false, searchable: false},
            {data: 'action2', name: 'action2',orderable: false, searchable: false},
          
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
         type: "POST",
          data: {
          "id": id,
          "_method": 'POST',
           "_token": token,
           },
         
         success: function(data){

         }
});

	swal({
				title: "Success",
				text: "Attendance has been deleted",
				icon: "success",
			});

          
	setTimeout(function() {location.reload()}, 1000);
          } else {
            
          }

        });
    });
}); 
  
  

    jQuery(function ()
    {
         $(".datepickercls").attr("readonly", true);
         
         jQuery('.datepickercls').datepicker({ dateFormat: 'yy-mm-dd',constrainInput: false, changeMonth: true,changeYear: true,yearRange: "1950:2100"});
      
        
   });
   
   
   
  


  
</script>



@endsection
