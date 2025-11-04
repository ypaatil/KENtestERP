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

		
@endsection
@section('content')
<!--Page header-->
<!--End Page header-->
<!-- Row -->

<form action="@if(isset($lineFetch)) {{ route('line_plan.store',array('id'=>$lineFetch->line_plan_id)) }} @else {{ route('line_plan.store') }} @endif" method="POST" id="insertform">
   @csrf 	
   <div class="row">
      <div class="col-xl-12 col-lg-12">
         <div class="card">
            <div class="card-body">
               <div class="card-title">Line Plan</div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="line_date" id="line_date" value="{{isset($lineFetch->line_date) ? $lineFetch->line_date: date('Y-m-d')}}" class="form-control"  required  {{ isset($lineFetch) ? 'disabled' : '' }}>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                        <div id="feild4"></div>
                     </div>
                  </div>
                  <div class="col-sm-3 col-md-2">
                     <div class="form-group">
                        <label class="form-label">Line</label>
                         @if(isset($lineFetch))
                        <select class="form-control"  name="dept_id" id="dept_id" disabled required data-parsley-errors-container="#feild1" >
                           <option value="">--- Select---</option>  
                           @foreach($dept_list as $dept)
                            <option value="{{$dept->line_id}}"
                            
                          {{  $lineFetch->dept_id== $dept->line_id ? "selected='selected'" : ""; }}    
                            
                            >{{$dept->line_name}}</option>
                           @endforeach
                        </select>
                        @else
                             <select class="form-control"  name="dept_id" id="dept_id" required data-parsley-errors-container="#feild1" >
                           <option value="">--- Select---</option>  
                           @foreach($dept_list as $dept)
                            <option value="{{$dept->line_id}}">{{$dept->line_name}}</option>
                           @endforeach
                        </select>   
                        
                        @endif
                        <div id="feild1"></div>
                     </div>
                  </div>
                  
                     <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Style</label>
                          @if(isset($lineFetch))
                        <select name="mainstyle_id" id="mainstyle_id" disabled  class="form-control" onChange="get_operation_ids(this.value)">
                         <option value="">--- Select---</option>  
                           @foreach($styleList as $rowStyle)
                           <option value="{{$rowStyle->mainstyle_id }}"
                           
                           {{  $lineFetch->mainstyle_id== $rowStyle->mainstyle_id ? "selected='selected'" : ""; }}
                           
                           >{{$rowStyle->mainstyle_name}}</option>
                           @endforeach
                        </select>
                          @else
                       <select name="mainstyle_id" id="mainstyle_id"  class="form-control" onChange="get_operation_ids(this.value)">
                          <option value="">--- Select---</option>  
                           @foreach($styleList as $rowStyle)
                           <option value="{{$rowStyle->mainstyle_id}}">{{$rowStyle->mainstyle_name}}</option>
                           @endforeach
                        </select>
                        @endif
                     </div>
                  </div>  
                  
              
                       <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Station No.</label>
                       <input type="text"   class="form-control"   readOnly  value="{{isset($lineFetch->station_no) ? $lineFetch->station_no: ""}}">  
                     </div>
                  </div> 
                            <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Target Efficiency</label>
                       <input type="text"   class="form-control" name="target_efficiency"     value="{{isset($lineFetch->target_efficiency) ? $lineFetch->target_efficiency: ""}}" {{ isset($lineFetch) ? 'disabled' : '' }}>  
                      <input type="hidden" name="sub_company_id" value="{{ Session::get('vendorId')}}" class="form-control" id="formrow-email-input">
                     </div>
                  </div> 
                  
                 </div>
                 
              		<div class="row">
					<div class="col-md-12">    
				   <input type="number" value="{{isset($ReceiptDetailFetch) ? count($ReceiptDetailFetch): 1}}" name="cnt" id="cnt" readonly="" hidden="true"  />
                    <div class="table-wrap">
                       <div class="table-responsive">
                        <table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead>
                              <tr>
                                 <th class="text-center">Operation ID</th>
                                <th class="text-center">Group</th> 
                                 <th class="text-center">Machine Type</th>
                                <th class="text-center">SAM</th>    
                                <th class="text-center">Required Skill Set</th>     
                                 <th class="text-center">Operator</th>   
                                 <th class="text-center">Add</th>
                                <th class="text-center">Remove</th>  
                              </tr>
                           </thead>
                           <tbody>

                           @php  if(!isset($lineFetchDetail)) { @endphp   
                              <tr class="rowcheck">
                                 <td>
                            <select class="form-control CAT required" required style="width:300px"  name="operation_id[]" id="operation_id" onChange="get_detail(this,this.value);">
                            <option value="">Select The Operation</option>
                         
                            </select>  
                            
                                 </td>
                             
                                            <td>
                            <select class="form-control  required noSelect2" disabled required style="width:150px"  name="group_id[]" id="group_id">
                            <option value="">Select The Group</option>
                             @foreach($groupList as $rowGroup)
                           <option value="{{$rowGroup->group_id}}">{{$rowGroup->group_name}}</option>
                           @endforeach
                            </select> 
                               <input type="hidden" step="any"  class="form-control"   name="operation_name[]" id="operation_name"  value="" style="width:100px;">
                                 </td>     
                               <td>
                            <select class="form-control  required noSelect2" readOnly required style="width:150"  name="machine_type_id[]" id="machine_type_id">
                            <option value="">Select The Machine Type</option>
                            @foreach($machineTypeList as $rowMachine)
                           <option value="{{ $rowMachine->machine_type_id }}">{{$rowMachine->machine_type_name}}</option>
                           @endforeach
                            </select>   
                                 </td>   
                                 
                                 <td>
                                    <input type="text" step="any" min="0"  disabled  class="form-control required" required  name="sam[]" id="sam"  value="0" style="width:100px;">
                                 </td>  
                                 
                                 
                                  <td>
                                    <input type="text" step="any" min="0" disabled  class="form-control"   name="required_skill_set[]" id="required_skill_set"  value="0" style="width:100px;">
                                 </td>  
                                 
                                 <td>
                          <select class="form-control  required" required style="width:300px"  name="employeeCode[]" id="employeeCode">
                            <option value="">Select The Operator</option>
                         @foreach($employeelist as $rowemp)
                           <option value="{{ $rowemp->employeeCode }}">{{$rowemp->fullName}}({{ $rowemp->employeeCode }})</option>
                           @endforeach
                            </select>      
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
@foreach($lineFetchDetail as $row)

 <tr class="rowcheck">
                                 <td>
                            <select class="form-control CAT required" required style="width:300px"   name="operation_id[]" id="operation_id" onChange="get_detail(this,this.value);">
                            <option value="">Select The Operation</option>
                             @php  $chunkSizeOP = 200; @endphp
                            @foreach (array_chunk($operationListMap, $chunkSizeOP) as $chunkOP) 
                            @foreach ($chunkOP as $operation) 
                            <option value="{{  $operation['operation_id'] }}" 
                            
                             {{  $row->operation_id== $operation['operation_id'] ? "selected='selected'" : ""; }}
                            
                            >{{ $operation['operation_id'] }}({{ $operation['operation_name'] }})</option>
                            @endforeach
                             @endforeach
                            </select>  
                                 </td>
                         
                                            <td>
                            <select class="form-control  required noSelect2"  disabled  required style="width:150px"  name="group_id[]" id="group_id">
                            <option value="">Select The Group</option>
                             @foreach($groupList as $rowGroup)
                           <option value="{{$rowGroup->group_id}}"
                           
                           {{  $row->group_id== $rowGroup->group_id ? "selected='selected'" : ""; }}    
                           
                           >{{$rowGroup->group_name}}</option>
                           @endforeach
                            </select>  
                              <input type="hidden" step="any"  class="form-control"   name="operation_name[]" id="operation_name"  value="{{ $row->operation_name }}" style="width:100px;"> 
                                 </td>     
                               <td>
                            <select class="form-control  required noSelect2"   required style="width:150"  disabled name="machine_type_id[]" id="machine_type_id">
                            <option value="">Select The Machine Type</option>
                            @foreach($machineTypeList as $rowMachine)
                           <option value="{{ $rowMachine->machine_type_id }}"
                           
                            {{  $row->machine_type_id== $rowMachine->machine_type_id ? "selected='selected'" : ""; }}    
                           
                           >{{$rowMachine->machine_type_name}}</option>
                           @endforeach
                            </select>   
                                 </td>        
                                 <td>
                                    <input type="text" step="any" min="0"    class="form-control required" disabled required  name="sam[]" id="sam"  value="{{  $row->sam }}" style="width:100px;">
                                 </td>   
                                  <td> 
                                    <input type="text" step="any" min="0" disabled  class="form-control"   name="required_skill_set[]" id="required_skill_set"  value="{{  $row->required_skill_set }}" style="width:100px;">
                                 </td>  
                                 
                                 <td>
                          <select class="form-control  required" required  style="width:300px"  name="employeeCode[]" id="employeeCode">
                            <option value="">Select The Operator</option>
                           
                          @php  $chunkSize = 200; @endphp
                        <option value="">--Select--</option>
                         @foreach (array_chunk($employeeMap, $chunkSize) as $chunk) 
                         @foreach ($chunk as $rowemp) 
                        <option value="{{ $rowemp['employeeCode'] }}" {{ $rowemp['employeeCode']  == $row->employeeCode ? 'selected="selected"' : '' }} >({{$rowemp['employeeCode'] }}) {{  $rowemp['fullName']  }}</option>
                        @endforeach
                        @endforeach
                           
                           
                            </select>      
                                 </td>
                                
                                 <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>


@php $no=$no+1;  @endphp
@endforeach
@php } @endphp

                           </tbody>
                         </table>
                
                     </div>
                  </div>
                  
                  	  </div>
					  </div>   
                 
                 
               
         
            </div>
            
            <div class="card-footer text-right">
               <button class="btn  btn-primary" type="submit" id="SubmitLine">@if(isset($lineFetch)) Update @else Save @endif</button>
               <a href="{{ route('line_plan.index') }}" class="btn  btn-danger" id="cancelBtn">Cancel</a>
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
   
    $(row).closest('tr').find('input[name^="operation_name[]"]').val(response.operation_name);
    $(row).closest('tr').find('select[name^="group_id[]"]').val(response.group_id);
    $(row).closest('tr').find('select[name^="machine_type_id[]"]').val(response.machine_type_id);
    $(row).closest('tr').find('input[name^="sam[]"]').val(response.sam);
    $(row).closest('tr').find('input[name^="required_skill_set[]"]').val(response.required_skill_set);
 
   
   }
   });    
      
  }
  
    function get_operation_ids(mainstyle_id){
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_operation_ids') }}",
   //dataType:"json",
   data:{mainstyle_id:mainstyle_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   
    $('#operation_id').html(response.html);
   
   }
   });      
        
        
    }
  
 
  
  
      $(document).on("mouseover", 'select', function (event) {
          
            $(this).not('.noSelect2').select2('');
          
      });
      
      
  


    // Function to delete the table row
    function deleteRow(btn) {
        var row = $(btn).closest('tr');
        row.remove();
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
        
        
        
        
       $trNew.find('select[name^="operation_id[]"]').prop('disabled', false);
       $trNew.find('select[name^="employeeCode[]"]').prop('disabled', false);
       
       
        	$trNew.find('select[name="operation_id[]"]').val("");
        	$trNew.find('select[name="employeeCode[]"]').val("");	
        	$trNew.find('select[name="group_id[]"]').val("");		
        	$trNew.find('select[name="machine_type_id[]"]').val("");	
       	 	$trNew.find('input[name="sam[]"]').val("");	
       	 	$trNew.find('input[name="required_skill_set[]"]').val("");	
		
    $trLast.after($trNew);
    
    
    
    });
    
  
});



$('#SubmitLine').on("click", function() {
    // Check required fields before submission
    if (checkRequiredFields()) {
        $("#SubmitLine").text('Please wait...');
        $('#SubmitLine').prop('disabled', true); 
        $('#SubmitLine').prop('disabled', true); 
        $('#cancelBtn').prop('disabled', true);  
        $('input,select').prop('disabled', false); 
        $("#insertform").submit();
    } else {
        // Show lightbox popup indicating missing fields
        alert('select neccessary fields');
    }
});


function checkRequiredFields() {
    var allFieldsValid = true;
    // Reset border colors
    $('#insertform .required').css('border-color', '');

    // Check required fields in the form
    $('#insertform .required').each(function() {
        if ($(this).is('input,select') && $(this).val().trim() === '') {
            allFieldsValid = false;
            $(this).css('border-color', 'red'); // Add red border to empty input or textarea
            return false; // Exit loop if any required field is empty
        }
        if ($(this).is('select') && $(this).val() === '') {
            allFieldsValid = false;
            $(this).css('border-color', 'red'); // Add red border to empty select box
            return false; // Exit loop if any required field is empty
        }
    });
    return allFieldsValid;
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
    
  
});

</script>


@endsection