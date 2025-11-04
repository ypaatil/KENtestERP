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







<div class="row">
<div class="col-xl-12 col-md-12 col-lg-12">


				<div class="card">
									<div class="card-header border-bottom-0">
										<div class="card-title">Attendance Detail</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table id="details-datatable" class="table table-striped table-bordered border-bottom text-nowrap">
												<thead>
													<tr>
													<th class="border-bottom-0 w-10">AttendanceDate </th>
<th class="border-bottom-0 w-10">Employee Code </th>
<th class="border-bottom-0 w-10">Employee Name </th>
<th class="border-bottom-0 w-10">Company </th>
<th class="border-bottom-0 w-10">Department </th>
<th class="border-bottom-0 w-10">InTime </th>
<th class="border-bottom-0 w-10">OutTime </th>
<th class="border-bottom-0 w-10">Duration </th>
<th class="border-bottom-0 w-10">Status</th>
<th class="border-bottom-0 w-10">Category </th>
<th class="border-bottom-0 w-10">Degination </th>
<th class="border-bottom-0 w-10">Grade </th>
<th class="border-bottom-0 w-10">Team </th>
<th class="border-bottom-0 w-10">Shift </th>
<th class="border-bottom-0 w-10">LateBy </th>
<th class="border-bottom-0 w-10">EarlyBy</th>
<th class="border-bottom-0 w-10">Punch Records</th>
<th class="border-bottom-0 w-10">OverTime</th>
													</tr>
												</thead>
												<tbody>
													@foreach($details as $row)    

<tr>
<td> {{ date('d-m-Y',strtotime($row->lineAttendanceDate)) }} </td>    
<td> {{ $row->EmployeeCode }} </td>  
<td> {{ $row->employeeName }} </td> 
<td> {{ $row->Company }} </td> 
<td> {{ $row->Department }} </td> 
<td> {{ $row->InTime }} </td>  
<td> {{ $row->OutTime }} </td>  
<td> {{ $row->Duration }} </td>  
<td> {{ $row->Status }} </td>
<td> {{ $row->Category }} </td> 
<td> {{ $row->Degination }} </td> 
<td> {{ $row->Grade }} </td> 
<td> {{ $row->Team }} </td> 
<td> {{ $row->Shift }} </td> 
<td> {{ $row->LateBy }} </td>  
<td> {{ $row->EarlyBy }} </td>  
<td> {{ $row->Punch_Records }} </td>  
<td> {{ $row->OverTime }} </td>  
</tr>

@endforeach
													
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







@endsection
