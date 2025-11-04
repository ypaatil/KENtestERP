@extends('layouts.operationapp')
@section('styles')

<!-- INTERNAL Fancy File Upload css -->
		<link href="{{URL::asset('operation/assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />

		<!-- INTERNAL Time picker css -->
		<link href="{{URL::asset('operation/assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

		<!-- INTERNAL Date Picker css -->
		<link href="{{URL::asset('operation/assets/plugins/date-picker/date-picker.css')}}" rel="stylesheet" />

		<!-- INTERNAL File Uploads css-->
        <link href="{{URL::asset('operation/assets/plugins/fileupload/css/fileupload.css')}}" rel="stylesheet" type="text/css" />

		<!-- INTERNAL Mutipleselect css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/multipleselect/multiple-select.css')}}">

		<!-- INTERNAL Sumoselect css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/sumoselect/sumoselect.css')}}">

		<!--INTERNAL IntlTelInput css-->

		<!-- INTERNAL Jquerytransfer css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/jQuerytransfer/jquery.transfer.css')}}">
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/jQuerytransfer/icon_font/icon_font.css')}}">

		<!-- INTERNAL multi css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/multi/multi.min.css')}}">

		<!-- INTERNAL Bootstrap DatePicker css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">
		 <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
		
		
		<style>
		    
		   .modal-confirm {		
	color: #636363;
	width: 325px;
	font-size: 14px;
}
.modal-confirm .modal-content {
	padding: 20px;
	border-radius: 5px;
	border: none;
}
.modal-confirm .modal-header {
	border-bottom: none;   
	position: relative;
}
.modal-confirm h4 {
	text-align: center;
	font-size: 26px;
	margin: 30px 0 -15px;
}
.modal-confirm .form-control, .modal-confirm .btn {
	min-height: 40px;
	border-radius: 3px; 
}
.modal-confirm .close {
	position: absolute;
	top: -5px;
	right: -5px;
}	
.modal-confirm .modal-footer {
	border: none;
	text-align: center;
	border-radius: 5px;
	font-size: 13px;
}	
.modal-confirm .icon-box {
	color: #fff;		
	position: absolute;
	margin: 0 auto;
	left: 0;
	right: 0;
	top: -70px;
	width: 95px;
	height: 95px;
	border-radius: 50%;
	z-index: 9;
	background: #FF0000;
	padding: 15px;
	text-align: center;
	box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
}
.modal-confirm .icon-box i {
	font-size: 58px;
	position: relative;
	top: 3px;
}
.modal-confirm.modal-dialog {
	margin-top: 80px;
}
.modal-confirm .btn {
	color: #fff;
	border-radius: 4px;
	background: #FF0000;
	text-decoration: none;
	transition: all 0.4s;
	line-height: normal;
	border: none;
}
.modal-confirm .btn:hover, .modal-confirm .btn:focus {
	background: #FF0000;
	outline: none;
}
.trigger-btn {
	display: inline-block;
	margin: 100px auto;
}  
		</style>
		
		

@endsection
@section('content')
<!--Page header-->
<!--End Page header-->
<!-- Row -->



<form action="@if(isset($dailyFetch)) {{ route('daily_production_entry.store',array('id'=>$dailyFetch->daily_pr_entry_id)) }} @else {{ route('daily_production_entry.store') }} @endif" method="POST" id="insertform">
   @csrf 	
   <div class="row">
      <div class="col-xl-12 col-lg-12">
         <div class="card">
            <div class="card-body">
               <div class="card-title">Daily Production</div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="daily_pr_date" id="daily_pr_date"  preDate="{{isset($dailyFetch->daily_pr_date) ? $dailyFetch->daily_pr_date: date('Y-m-d')}}"  value="{{ isset($dailyFetch->daily_pr_date) ? $dailyFetch->daily_pr_date: date('Y-m-d')}}"  @if(isset($dailyFetch)) onChange="checkExistRecord();" @else onChange="getGroup();" @endif  class="form-control"  required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                         <input type="hidden" name="sub_company_id" value="{{ Session::get('vendorId')}}" class="form-control" id="formrow-email-input">
                        <div id="feild4"></div>
                     </div>
                  </div>
                   <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Line</label>
                      <select class="form-control"  name="dept_id" id="dept_id"  @if(isset($dailyFetch))    disabled  @endif   data-parsley-errors-container="#feild1" onChange="getGroup();">
                           <option value="">--- Select---</option>  
                           @foreach($dept_list as $dept)
                            <option value="{{$dept->line_id}}"
                            
                             @if(isset($dailyFetch)) {{  $dept->line_id== $dailyFetch->dept_id ? "selected='selected'" : ""; }} @endif
                            
                            >{{$dept->line_name}}</option>
                           @endforeach
                        </select>   
                   </div>
                  </div>  
                  
                 <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Style</label>
                      <select name="mainstyle_id"  id="mainstyle_id" @if(isset($dailyFetch))    disabled  @endif  class="form-control" onChange="getGroup();">
                          <option value="">--- Select---</option>  
                           @foreach($styleList as $rowStyle)
                           <option value="{{$rowStyle->mainstyle_id}}"
                           
                           
                            @if(isset($dailyFetch))  {{  $rowStyle->mainstyle_id== $dailyFetch->mainstyle_id ? "selected='selected'" : ""; }} @endif
                           
                           >{{$rowStyle->mainstyle_name}}</option>
                           @endforeach
                        </select>
                   </div>
                  </div>   
                           <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Group</label>
                      <select class="form-control"     name="group_id" id="group_id" @if(isset($dailyFetch))    disabled  @endif onChange="get_daily_production_table_by_operator(this.value);" required>
                            <option value="">Select The Group</option>
                             @foreach($groupList as $rowGroup)
                           <option value="{{$rowGroup->group_id}}"
                           
                             @if(isset($dailyFetch))  {{  $rowGroup->group_id== $dailyFetch->group_id ? "selected='selected'" : ""; }} @endif
                           
                           >{{$rowGroup->group_name}}</option>
                           @endforeach
                            </select> 
                   </div>
                  </div> 
                  
                  
                            <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Is Style Change</label>
                    <input type="checkbox" name="is_style_change" class="form-control" id="is_style_change" value="1" onClick="showheads(1);" {{ isset($dailyFetch->is_style_change) ?  $dailyFetch->is_style_change == 1 ? 'checked' : '' : '' }}>

                     </div>
                  </div> 
                  
                  
                  
                  @php
                  if(isset($dailyFetch->is_style_change))
                  {
                  
                  if($dailyFetch->is_style_change==1)
                  {  @endphp
                  
                    <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Sam 1</label>
                       <input type="text" name="sam_1"  class="form-control" id="sam_1" value="{{isset($dailyFetch->sam_1) ? $dailyFetch->sam_1: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div> 
                  
                    <div class="col-md-2 hideInput">
                    <div class="form-group">
                        <label class="form-label">Output 1</label>
                       <input type="text" name="output_1"  class="form-control" id="output_1" value="{{isset($dailyFetch->output_1) ? $dailyFetch->output_1: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div>  
                  
                          <div class="col-md-2 hideInput">
                    <div class="form-group">
                        <label class="form-label">Sam 2</label>
                       <input type="text" name="sam_2"  class="form-control" id="sam_2" value="{{isset($dailyFetch->sam_2) ? $dailyFetch->sam_2: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div>    
                         <div class="col-md-2 hideInput">
                    <div class="form-group">
                        <label class="form-label">Output 2</label>
                       <input type="text" name="output_2"  class="form-control" id="output_2" value="{{isset($dailyFetch->output_2) ? $dailyFetch->output_2: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div> 
                  
                  @php
                  }
                  } 
                  @endphp
                
                  
                     <div class="col-md-2 hideInput" style="display:none">
                    <div class="form-group">
                        <label class="form-label">Sam 1</label>
                       <input type="text" name="sam_1"  class="form-control" id="sam_1" value="{{isset($dailyFetch->sam_1) ? $dailyFetch->sam_1: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div> 
                  
                    <div class="col-md-2 hideInput" style="display:none">
                    <div class="form-group">
                        <label class="form-label">Output 1</label>
                       <input type="text" name="output_1"  class="form-control" id="output_1" value="{{isset($dailyFetch->output_1) ? $dailyFetch->output_1: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div>  
                  
                          <div class="col-md-2 hideInput" style="display:none">
                    <div class="form-group">
                        <label class="form-label">Sam 2</label>
                       <input type="text" name="sam_2"  class="form-control" id="sam_2" value="{{isset($dailyFetch->sam_2) ? $dailyFetch->sam_2: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div>    
                         <div class="col-md-2 hideInput" style="display:none">
                    <div class="form-group">
                        <label class="form-label">Output 2</label>
                       <input type="text" name="output_2"  class="form-control" id="output_2" value="{{isset($dailyFetch->output_2) ? $dailyFetch->output_2: 0}}" onkeyup="calculateOverallSam()">  
                     </div>
                  </div> 
                 
                  
                    <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Overall Sam</label>
                       <input type="text" name="overall_sam"  class="form-control" id="overall_sam" value="{{isset($dailyFetch->overall_sam) ? $dailyFetch->overall_sam: ""}}" onkeyup="calculateEff()">  
                     </div>
                  </div>  
                      <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Overall Output</label>
                       <input type="text" name="overall_output"  class="form-control" id="overall_output" value="{{isset($dailyFetch->overall_output) ? $dailyFetch->overall_output: ""}}" onkeyup="calculateEff()">  
                     </div>
                  </div>   
                       <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Total Present</label>
                       <input type="text" name="total_present"  class="form-control" id="total_present" value="{{isset($dailyFetch->total_present) ? $dailyFetch->total_present: ""}}" onkeyup="calculateEff()">  
                     </div>
                  </div>   
                    <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Overall Efficiency</label>
                       <input type="text" name="overall_efficiency"  class="form-control" id="overall_efficiency" readOnly value="{{isset($dailyFetch->overall_efficiency) ? $dailyFetch->overall_efficiency: ""}}">  
                     </div>
                  </div> 
                  
                  
                 </div>
                 
              		<div class="row">
					<div class="col-md-12">    
				   <input type="number" value="{{isset($dailyFetchDetail) ? count($dailyFetchDetail): 1}}" name="cnt" id="cnt" readonly="" hidden="true"  />
                    <div class="table-wrap">
                       <div class="table-responsive" id="tbl">
                        <table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead>
                              <tr>
                                  <th class="text-center">Sr.No.</th>       
                                 <th class="text-center">Is Half Day</th>     
                                 <th class="text-center">Operator</th>   
                                    <th class="text-center">Operation</th>
                                    <th class="text-center">Sam</th> 
                                 <th class="text-center">Output</th>    
                                 <th class="text-center">Remark</th>    
                                 <th class="text-center">Add</th>
                                <th class="text-center">Remove</th>  
                              </tr>
                           </thead>
                           <tbody id="tbodyData">

                           @php  if(!isset($dailyFetchDetail)) { @endphp   
                              <tr class="rowcheck">
                           <td style="text-align:center;">
                            <input type="number" id="srNo" name="srNo[]"  style="text-align:center;width:50px" readOnly  value="1">
                          </td>        
                                  
                           <td style="text-align:center;">
                            <input type="checkbox" style="text-align:center;" name="is_half_day[0]" value="1">
                          </td>
                             <td>
                             <select class="form-control"  name="employeeCode[]" style="width:300px" id="employeeCode"  required data-parsley-errors-container="#feild1" onChange="previousData(this,0);">
                           <option value="">--- Select---</option>  
                            @foreach($employeelist as $rowemp)
                           <option value="{{ $rowemp->employeeCode }}">{{$rowemp->fullName}}({{ $rowemp->employeeCode }})</option>
                           @endforeach
                        </select>             
                          </td>
                                 
                               <td>
                            <select class="form-control CAT"  style="width:300px"  name="operation_id[]" id="operation_id" required onChange="get_detail(this,this.value);previousData(this,this.value);">
                            <option value="">Select The Operation</option>
                            @foreach($operationList as $operation)
                            <option value="{{$operation->operation_id}}" 
                            
                            
                            >{{$operation->operation_id}}({{$operation->operation_name}})</option>
                            @endforeach
                            </select>   
                                 </td>
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  SAM"  disabled  name="sam[]" id="sam"  value="0" style="width:80px;">
                                 <input type="hidden"   class="form-control"   name="operation_name[]" id="operation_name"  value="" style="width:100px;">
                                 <input type="hidden"   class="form-control"   name="station_no[]" id="station_no"  value="" style="width:100px;">
                                 </td>  
                             
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control  PIECES"   name="pieces[]" id="pieces"  value="0" style="width:80px;" onBlur="checkExist(this,this.value)">
                                 <input type="hidden" step="any" min="0"   class="form-control  EFFICIENCY"   name="efficiency[]" id="efficiency"  value="0" style="width:100px;">
                                 </td>   
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="" style="width:200px;">
                                 </td>     
                                  <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                  </td>
                                  <td>
                                  <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>
                              @php } else { @endphp
  @php $no=1; @endphp
@foreach($dailyFetchDetail as $index => $row)


 <tr class="rowcheck">
     
                                <td style="text-align:center;">
                            <input type="number" id="srNo" name="srNo[]" style="text-align:center;width:50px" readOnly  value="{{ $no++ }}">
                          </td>  
     
                           <td style="text-align:center;">
                             <input type="checkbox" style="text-align:center;" name="is_half_day[{{ $index }}]" value="1" {{ $row->is_half_day == 1 ? 'checked' : '' }}>
                          
                          </td>
                                 
                                 <td>
                                  <select class="form-control"  name="employeeCode[]" style="width:300px" id="employeeCode" required  data-parsley-errors-container="#feild1" onChange="previousData(this);">
                           <option value="">--- Select---</option>  
                           @foreach($employeelist as $rowemp)
                           <option value="{{ $rowemp->employeeCode }}"
                           
                           {{  $rowemp->employeeCode== $row->employeeCode ? "selected='selected'" : ""; }} 
                           
                           >{{$rowemp->fullName}}({{ $rowemp->employeeCode }})</option>
                           @endforeach
                        </select>     
                                     
                                 </td>
                        
                               <td>
                            <select class="form-control CAT"  style="width:300px"  name="operation_id[]" required id="operation_id" onChange="get_detail(this,this.value);previousData(this);">
                            <option value="">Select The Operation</option>
                            @foreach($operationList as $operation)
                            <option value="{{$operation->operation_id}}" 
                            
                             {{  $row->operation_id ==$operation->operation_id ? "selected='selected'" : ""; }}  
                            
                            
                            >{{$operation->operation_id}}({{$operation->operation_name}})</option>
                            @endforeach
                            </select>   
                                 </td>
 
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control  SAM" disabled   name="sam[]" id="sam"  value="{{  $row->sam }}" style="width:80px;">
                                   <input type="hidden"   class="form-control"   name="operation_name[]" id="operation_name"  value="{{  $row->operation_name }}" style="width:100px;">
                                       <input type="hidden"   class="form-control"   name="station_no[]" id="station_no"  value="{{  $row->station_no }}" style="width:100px;">
                                 </td>   
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control  PIECES"   name="pieces[]" id="pieces"  value="{{  $row->pieces }}" style="width:80px;">
                                   <input type="hidden" step="any" min="0"   class="form-control  EFFICIENCY"   name="efficiency[]" id="efficiency"  value="{{  $row->efficiency }}" style="width:100px;">
                                 
                                 </td>   
                                     <td>
                                    <input type="text" step="any" min="0"   class="form-control  REMARK"   name="remark[]" id="remark"  value="{{  $row->remark }}" style="width:200px;">
                                 </td>  
                                 <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>


@php   @endphp
@endforeach
@php } @endphp

                           </tbody>
                         </table>
                         
                          <div class="col-sm-3">
                           <label for="formrow-inputState" class="form-label">Total Efficiency:</label>
                           <div class="form-group">
                               <input type="number" step="0.01"  name="total_efficiency" id="total_efficiency" class="form-control totalAmt" value="{{isset($dailyFetch->total_efficiency) ? $dailyFetch->total_efficiency: 0}}">
                           </div>
                        </div>
                
                     </div>
                  </div>
                  
                  	  </div>
					  </div>   
         
            </div>
            
            <div class="card-footer text-right">
               <button class="btn  btn-primary" type="submit" id="SubmitLine">@if(isset($dailyFetch)) Update @else Save @endif</button>
               <a href="{{ route('daily_production_entry.index') }}" class="btn  btn-danger" id="cancelBtn">Cancel</a>
            </div>
    
         </div>
      </div>
   </div>
   
</form>
<!-- End Row-->
@endsection('content')
@section('scripts')

      	<script src="{{URL::asset('operation/assets/js/select2.js')}}"></script>
	<script src="{{URL::asset('operation/assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/time-picker/toggles.min.js')}}"></script>

		<!-- INTERNAL Datepicker js -->
		<script src="{{URL::asset('operation/assets/plugins/date-picker/date-picker.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/date-picker/jquery-ui.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

		<!-- INTERNAL File-Uploads Js-->
		<script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>

		<!-- INTERNAL File uploads js -->
        <script src="{{URL::asset('operation/assets/plugins/fileupload/js/dropify.js')}}"></script>
		<script src="{{URL::asset('operation/assets/js/filupload.js')}}"></script>

		<!-- INTERNAL Multiple select js -->
		<script src="{{URL::asset('operation/assets/plugins/multipleselect/multiple-select.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/multipleselect/multi-select.js')}}"></script>

		<!-- INTERNAL Sumoselect js-->
		<script src="{{URL::asset('operation/assets/plugins/sumoselect/jquery.sumoselect.js')}}"></script>

		<!-- INTERNAL intlTelInput js-->
		<script src="{{URL::asset('operation/assets/plugins/intl-tel-input-master/intlTelInput.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/intl-tel-input-master/country-select.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/intl-tel-input-master/utils.js')}}"></script>

		<!-- INTERNAL jquery transfer js-->
		<script src="{{URL::asset('operation/assets/plugins/jQuerytransfer/jquery.transfer.js')}}"></script>

		<!-- INTERNAL multi js-->
		<script src="{{URL::asset('operation/assets/plugins/multi/multi.min.js')}}"></script>

		<!-- INTERNAL Bootstrap-Datepicker js-->
		<script src="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

		<!-- INTERNAL Form Advanced Element -->
		<script src="{{URL::asset('operation/assets/js/formelementadvnced.js')}}"></script>
		<script src="{{URL::asset('operation/assets/js/form-elements.js')}}"></script>
	
  

  <div id="errorModal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header">
				<div class="icon-box">
			<i class="material-icons">error</i>
				</div>	
			<button type="button" class="close btn"  data-dismiss="modal" aria-label="Close" style="background-color:#FFF;color:#000">  <span aria-hidden="true">&times;</span></button>
				
				<h4 class="modal-title w-100">error!</h4>	
			</div>
			<div class="modal-body">
				<p class="text-center">Already exist record.!</p>
			</div>
			<div class="modal-footer">
	     	<button class="btn btn-danger btn-block" data-dismiss="modal">cancel</button>
			</div>
		</div>
	</div>
</div>  
		
	
	   
<script>
   
   function calAmount()
   {
       var qty = $('#qty').val() ? $('#qty').val() : 0;
       var rate = $('#rate').val() ? $('#rate').val() : 0;
       
       $('#amount').val(parseInt(qty) * parseFloat(rate));
   }
   @if(isset($CreditSalesEntry))
        GetEmployeeData();
   @endif
   function GetEmployeeData()
   {
        var employeeCode = $("#employeeCode").val();
        $.ajax({
            type:"GET",
            url:"{{ route('GetEmpDetailFromEmpCode') }}",
            dataType:"json",
            data:{'employeeCode':employeeCode},
            success:function(response)
            {
                $("#emp_name").val(response.fullName);
                $("#dept_id").html('<option value="'+response.dept_id+'">'+response.dept_name+'</option>').attr('selected','selected');
                $("#sub_company_id").html('<option value="'+response.sub_company_id+'">'+response.sub_company_name+'</option>').attr('selected','selected');
            }
        });
   }
   
   
   
   $("#no_of_station").keyup(function(){

    var no_of_station=$('#no_of_station').val();

   $.ajax({
   type:"POST",
   url:"{{ route('get_selected_operator') }}",
   //dataType:"json",
   data:{no_of_station:no_of_station,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   $("#footable_2").html(response.html);
   
   }
   });


  });
  
  function get_detail(row,operation_id){
      
      var mainstyle_id=$('#mainstyle_id').val();
      
     $.ajax({
   type:"POST",
   url:"{{ route('get_operation_detail') }}",
   //dataType:"json",
   data:{operation_id:operation_id,mainstyle_id:mainstyle_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   
   
   
    $(row).closest('tr').find('input[name^="sam[]"]').val(response.sam);
    
     closetRow($(row).closest('tr'));
   
   
   }
   });    
      
  }
  
  
    function checkExist(row,pieces){
        
        
     var dept_id =$('#dept_id').val();
     var daily_pr_date=   $('#daily_pr_date').val(); 
     var operation_id=  $(row).closest('tr').find('select[name^="operation_id[]"]').val();    
     var employeeCode= $(row).closest('tr').find('select[name^="employeeCode[]"]').val();        
        
     
      
     $.ajax({
   type:"POST",
   url:"{{ route('check_exists_production') }}",
   dataType:"json",
   data:{dept_id:dept_id,daily_pr_date:daily_pr_date,operation_id:operation_id,employeeCode:employeeCode,pieces:pieces,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response.flag);  
   
   if(response.flag==1)
   {
    $(row).closest('tr').find('input[name^="pieces[]"]').val("");
    
    alert('This record already exist.! For Group:-'+response.group_name);
   } 
   
   }
   });    
      
  }
  
  
  
  
  
    
    function get_operators(row,dept_id){
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_operators') }}",
   //dataType:"json",
   data:{dept_id:dept_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
     $(row).closest('tr').find('select[name^="employeeCode[]"]').html(response.html);
   
   }
   });      
        
    }
    
    
       function get_over_all_sam(dept_id){
           
         
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_over_all_sam') }}",
   //dataType:"json",
   data:{dept_id:dept_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   
     $('#overall_sam').val(response.over_all_sam);
   
   }
   });      
        
    }
    
    
    
     
    function get_daily_production_table_by_operator(group_id){
        
        
        var dept_id=$('#dept_id').val();
        var mainstyle_id=$('#mainstyle_id').val();  
        
        
    
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_daily_production_table_by_operator') }}",
   //dataType:"json",
   data:{dept_id:dept_id,mainstyle_id:mainstyle_id,group_id:group_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response); 
       $("#footable_2").html(response);
       
       
   
   },
    complete: function (data) {
      
function sortTable() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("footable_2");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[0];
      y = rows[i + 1].getElementsByTagName("TD")[0];
      //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}
      
      
     }
   });      
        
    }
    

    
       function get_details_by_operators(row,employeeCode){
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_operator_detail') }}",
   //dataType:"json",
   data:{employeeCode:employeeCode,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   
      var select2 = $(row).closest("tr").find('select').select2();


if (select2) {
 
$(row).closest("tr").find('select').select2('destroy');
} else {
  
}
   
    $(row).closest('tr').find('select[name^="mainstyle_id[]"]').val(response.mainstyle_id);
    //$(row).closest('tr').find('select[name^="operation_id[]"]').val(response.operation_id);
    //$(row).closest('tr').find('input[name^="sam[]"]').val(response.sam);
  
   
   }
   });      
        
    }
  
  
  
  
    function get_operation_ids(row,employeeCode){
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_operation_ids_by_operator') }}",
   //dataType:"json",
   data:{employeeCode:employeeCode,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
 
    $(row).closest('tr').find('select[name^="operation_id[]"]').html(response.html);
   
   }
   });      
        
        
    }
  
 
  
  
      $(document).on("mouseover", 'select', function (event) {
          
            $(this).not('.noSelect2').select2('');
          
      });
      
      

   
    function deleteRow(btn) {
        
        var RowCount= $('#footable_2 tr').length;
         var row = $(btn).closest('tr');
 
        row.remove();
         mycalc();
         
    }
    
    
    $(function(){
    $("#footable_2").on('click', '.Abutton', function() {
        
      var select2 = $(this).closest("tr").find('select').select2();


if (select2) {
 
$(this).closest("tr").find('select').select2('destroy');
} else {
  
}
   
        
        
       var $tableBody = $('#footable_2').find("tbody"),
        $trLast = $tableBody.find("tr:last"),
        
        $trNew = $trLast.clone();
        
        
               
        
         	$trNew.find('select[name="dept_id[]"]').val("--- Select---");	
         	$trNew.find('select[name="employeeCode[]"]').val("--- Select---");	
         	$trNew.find('select[name="mainstyle_id[]"]').val("--- Select---");		
          	$trNew.find('select[name="operation_id[]"]').val("--- Select---");		
         	
        	$trNew.find('input[name="sam[]"]').val("");
        	$trNew.find('input[name="pieces[]"]').val("");
        	$trNew.find('input[name="efficiency[]"]').val("");	
        	
         var newIndex = $tableBody.find('tr').length; // count current rows

        $trNew.find('input[type="checkbox"][name^="is_half_day"]').each(function () {
            // Set new name with updated index
            $(this).attr('name', 'is_half_day[' + newIndex + ']');
            // Uncheck cloned checkbox
            $(this).prop('checked', false);
        }); 	
      
            $trNew.find('input[name="srNo[]"]').val(newIndex + 1); 	
  
        	
        	
		
    $trLast.after($trNew);
    });
    
  
});






// function checkRequiredFields() {
//     var allFieldsValid = true;
//     // Reset border colors
//     $('#insertform .required').css('border-color', '');

//     // Check required fields in the form
//     $('#insertform .required').each(function() {
//         if ($(this).is('input,select') && $(this).val().trim() === '') {
//             allFieldsValid = false;
//             $(this).css('border-color', 'red'); // Add red border to empty input or textarea
//             return false; // Exit loop if any required field is empty
//         }
//         if ($(this).is('select') && $(this).val() === '') {
//             allFieldsValid = false;
//             $(this).css('border-color', 'red'); // Add red border to empty select box
//             return false; // Exit loop if any required field is empty
//         }
//     });
//     return allFieldsValid;
// }

    
    
          $(document).on("keyup", 'input[name^="sam[]"],input[name^="pieces[]"]', function (event) {
      
          closetRow($(this).closest("tr"));
        mycalc();
      });  
      
      
      
      
      function closetRow(row)
      {
          
        var sam=  row.find('input[name^="sam[]"]').val();
        var pieces=  row.find('input[name^="pieces[]"]').val();
        

        
        var totalEFF=((((parseFloat(sam) * parseFloat(pieces)) / 480) * (100)));
        
        row.find('input[name^="efficiency[]"]').val(totalEFF.toFixed(2));
        
          
      }
    
    
    
function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('SAM');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}


sum2 = 0.0;
var amountsOD = document.getElementsByClassName('PIECES');
//alert("value="+amounts[0].value);
for(var i=0; i<amountsOD .length; i++)
{ 
var a1 = +amountsOD[i].value;
sum2 += parseFloat(a1);
}

sum3 = 0.0;
var amountsEFF = document.getElementsByClassName('EFFICIENCY');
//alert("value="+amounts[0].value);
for(var i=0; i<amountsEFF .length; i++)
{ 
var a3 = +amountsEFF[i].value;
sum3 += parseFloat(a3);
}


  var TOTEFF= (((sum1 *  sum2) * parseFloat(100)) / parseFloat(480));
 


document.getElementById("total_efficiency").value = (sum3.toFixed(2));


}


        function calculateEff()
      {
          
        var sam = parseFloat($('#overall_sam').val()) || 0;
        var overall_output = parseFloat($('#overall_output').val()) || 0;
        var total_present = parseFloat($('#total_present').val()) || 0;

        
        if (total_present > 0) {
        var totalEFF=((((parseFloat(sam) * parseFloat(overall_output)) / (total_present * 480)) * (100)));
        
        }
        
        var totalEFFFinal=totalEFF || 0;
        
        $('#overall_efficiency').val(totalEFFFinal.toFixed(2));
        
          
      }
      
      
           function calculateOverallSam()
      {
          
  
        var total_present=$('#total_present').val();
        var sam_1=$('#sam_1').val();
        var sam_2=$('#sam_2').val();  
        var output_1=$('#output_1').val();  
        var output_2=$('#output_2').val();  
        
        
        var overallSam=(((parseFloat(sam_1) * parseFloat(output_1)) +  (parseFloat(sam_2) * parseFloat(output_2))) / (parseFloat(output_1) + parseFloat(output_2)));
        var overall_output=(parseFloat(output_1) + parseFloat(output_2));
        
        $('#overall_output').val(overall_output);
        
        var totalEFF=((((parseFloat(overallSam) * parseFloat(overall_output)) / (total_present * 480)) * (100)));
        
        
          $('#overall_sam').val(overallSam.toFixed(2));
        
         $('#overall_efficiency').val(totalEFF.toFixed(2));
        
          
      }
      
      
      
       function showheads(str)
      {
          
         
   
      if($('#is_style_change').is(':checked'))
      {
     
      $('.hideInput').show();
   
      } 
      else{
      
        $('.hideInput').hide();
   
      }
      
      }
      
      
      
        function checkExistRecord()
        {
        
        var daily_pr_date=$('#daily_pr_date').val();
        var dept_id=$('#dept_id').val();
        var mainstyle_id=$('#mainstyle_id').val();
        var group_id=$('#group_id').val();
        
        $.ajax({
        type:"POST",
        url:"{{ route('check_exist_record') }}",
        dataType:"json",
        data:{dept_id:dept_id,daily_pr_date:daily_pr_date,mainstyle_id:mainstyle_id,group_id:group_id,"_token":"{{ csrf_token() }}"},
        success:function(response){
        console.log(response);  
        
         if(response > 0)
         {
          $('#daily_pr_date').val($("#daily_pr_date").attr('preDate'));   
         $('#errorModal').modal('show');
        
         } else{
             
             
         }
        
        }
        });   
        
        }
      
      
      
      function getGroup()
      {

     var daily_pr_date=$('#daily_pr_date').val();
     var dept_id=$('#dept_id').val();
     var mainstyle_id=$('#mainstyle_id').val();

   $.ajax({
   type:"POST",
   url:"{{ route('get_group_ids_by_line') }}",
   //dataType:"json",
   data:{dept_id:dept_id,daily_pr_date:daily_pr_date,mainstyle_id:mainstyle_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
 
     $('#group_id').html(response.html);
   
   }
   });   
   
      }
      
      
      
      
      
      
      
      
      
function previousData(obj) {
    let cnt = 0;
    


    // Get the selected employee code and operation from the current row
    const selectedEmployeeCode = $(obj).closest('tr').find("select[name='employeeCode[]']").val();
    const selectedOperation = $(obj).closest('tr').find("select[name='operation_id[]']").val();  // The current operation selected in this row 

    // Loop through all the rows and check for duplicate employee code and operation combinations
    $("#footable_2 tr").each(function() {
        const employeeCode = $(this).find("select[name='employeeCode[]']").val();
        const operation = $(this).find("select[name='operation_id[]']").val();
        
      

        // Check if both the employee code and operation match the selected ones
        if (selectedEmployeeCode === employeeCode && selectedOperation === operation) {
            cnt++;
        }
    });

    // If the same combination of employee code and operation is selected more than once
    if (cnt > 1) {
        alert("This combination of employee and operation is already selected, you should choose another.");
        $(obj).val("");  // Clear the currently selected operation
        
        $(obj).closest('tr').find("select[name='employeeCode[]']").val("--Select--");
        $(obj).closest('tr').find("select[name='operation_id[]']").val("--Select--");  
        $(obj).closest('tr').find("input[name='sam[]']").val("0");    
    }
}


$(document).ready(function() {
    $('#dept_id').change(function() {
        if ($(this).val()) {
            $(this).prop('disabled', true);
        }
    });
    
        $('#mainstyle_id').change(function() {
        if ($(this).val()) {
            $(this).prop('disabled', true);
        }
    });
    
           $('#group_id').change(function() {
        if ($(this).val()) {
            $(this).prop('disabled', true);
        }
    });
});


$(document).ready(function () {
    $('#SubmitLine').on("click", function (e) {
        e.preventDefault(); // prevent default click behavior

        // Step 1: Validate dropdowns
        if ($('#group_id').val() === "" && $('#dept_id').val() === "" && $('#mainstyle_id').val() === "") {
            alert('Please select the necessary fields.');
            return;
        }

        // Step 2: Validate pieces[] inputs
        var isValid = true;

        $('.rowcheck').each(function () {
            var input = $(this).find('input[name="pieces[]"]');
            var value = input.val().trim();

            if (value === '' || isNaN(value) || parseFloat(value) < 1) {
                isValid = false;
                input.css('border', '2px solid red');
            } else {
                input.css('border', '');
            }
        });

        if (!isValid) {
            alert('Each row must have a "Output" value of at least 1.');
            return;
        }

        // Step 3: Submit form
        $("#SubmitLine").text('Please wait...');
        $('#SubmitLine').prop('disabled', true);
        $('#cancelBtn').prop('disabled', true);
        $('input,select').prop('disabled', false); // Optional, based on your use-case

        $('#insertform').submit();
    });
});

</script>


@endsection