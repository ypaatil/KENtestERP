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
<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/intl-tel-input-master/intlTelInput.css')}}">

<!-- INTERNAL Jquerytransfer css-->
<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/jQuerytransfer/jquery.transfer.css')}}">
<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/jQuerytransfer/icon_font/icon_font.css')}}">

<!-- INTERNAL multi css-->
<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/multi/multi.min.css')}}">

<!-- INTERNAL Bootstrap DatePicker css-->
<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">

<link rel="stylesheet" href="{{URL::asset('css/parsly.css')}}">

<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">

<meta name="csrf-token" content="{{ csrf_token() }}">
<style>

    .spanclr{
        color:red;
    }
</style>
@endsection

@section('content')

<!--Page header-->

<!--End Page header-->

<!-- Row -->


<form action="{{ route('show_hourly_operation_production_detail') }}" method="POST" id="insertform">

    @csrf
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Hourly Operation Production Report</div>
                    <div class="row">
                                     <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Unit<span class="spanclr">*</span></label>
                               <select name="sub_company_id" id="sub_company_id"
                                    class="form-control select2-show-search custom-select" autocomplete="off"
                                    data-placeholder="Select" tabindex="26" onChange="getLines(this.value);" required>
                                     <option value="0">Select</option>
                                     <option value="56">Ken Global Unit 1</option>
                                    <option value="115">Ken Global Unit 2</option>  
                                    <option value="628">Ken - Padamavti</option>      
                                    <option value="110">Ken Global Unit 3</option>
                                </select>
                            </div>
                        </div>     
                        
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Line <span class="spanclr">*</span></label>
                                <select name="dept_id" id="dept_id"
                                    class="form-control select2-show-search custom-select" autocomplete="off"
                                    data-placeholder="Select" tabindex="26"   required>
                                   <option value="">All</option>
                                    @foreach($deptlist as $rowDept)
                                    <option value="{{ $rowDept->line_id }}">
                                        {{ $rowDept->line_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Date <span class="spanclr">*</span></label>

                                <input type="text" name="fromDate" class="form-control datepickercls"
                                    placeholder="From Date" value="{{ date('Y-m-01') }}" id="fdate"
                                     required>
                            </div>
                        </div>
                        
                        
                        {{--
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">To Date <span class="spanclr">*</span></label>

                                <input type="text" name="toDate" class="form-control datepickercls"
                                    placeholder="To Date" value="{{ date('Y-m-t') }}" id="tdate"
                                required >
                            </div>
                        </div> --}}
                        
                        
                        <label class="form-label"></label>
                        <div class="card-footer text-right">
                            <button class="btn  btn-primary"  type="submit"
                                id="gen">Show</button>
                            <a href="" class="btn  btn-danger" id="cancel">Cancel</a>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <div id='loadingmessage' style='display:none'>
                    <img src="{{URL::asset('operation/assets/images/wait.gif')}}" />
                </div>

            </div>
        </div>
    </div>


</form>



<!-- End Row-->

@endsection('content')

@section('modals')

<!--Change password Modal -->
<div class="modal fade" id="changepasswordnmodal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
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




<input type="hidden" id="user_type_id" value="{{Session::get('user_type')}}">
@endsection('modals')



@section('scripts')
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
<script src="{{URL::asset('operation/assets/js/select2.js')}}"></script>


<script src="{{URL::asset('operation/assets/plugins/date-picker/date-picker.js')}}"></script>
<script src="{{ URL::asset('operation/assets/js/parsly.js')}}"></script>




<script>
 $('#insertform').parsley();
$('#updateform').parsley();

$(function() {
    if ($("#user_type_id").val() == 1) {
        $("#sub_company_id").val(4).trigger('change');
    }
});




jQuery(function() {
    $(".datepickercls").attr("readonly", true);

    jQuery('.datepickercls').datepicker({
        dateFormat: 'yy-mm-dd',
        constrainInput: false,
        changeMonth: true,
        changeYear: true,
        yearRange: "1950:2100"
    });


});




function mycalc() {
    var earndeduct = document.getElementsByClassName('EARNDEDUCT');
    //alert("value="+earndeduct[0].value);
    sum2 = 0.0;
    sum1 = 0.0;
    var amounts = document.getElementsByClassName('AMOUNT');
    //alert("value="+amounts[0].value);
    for (var i = 0; i < amounts.length; i++) {
        if (earndeduct[i].value == 1) {
            var a = +amounts[i].value;
            sum1 += parseInt(a) || 0;
        } else {
            var a = +amounts[i].value;
            sum2 += parseInt(a) || 0;
        }

    }
    document.getElementById("SalaryEarn").value = sum1;
    document.getElementById("SalaryDeduct").value = sum2;

    document.getElementById("SalaryAmount").value = parseFloat(document.getElementById("SalaryEarn").value) -
        parseFloat(document.getElementById("SalaryDeduct").value);


}

$("table.footable_2").on("keyup", 'input[name^="Amounts[]"]', function(event) {
    mycalc();
});









function deleteRow(btn) {


    if (document.getElementById('cnt').value > 0) {
        var row = btn.parentNode.parentNode;

        row.parentNode.removeChild(row);

        document.getElementById('cnt').value = document.getElementById('cnt').value - 1;

        recalcId();

        if ($("#cnt").val() <= 0) {
            document.getElementById('Submit').disabled = true;
            document.getElementById("SalaryEarn").value = 0;
            document.getElementById("SalaryDeduct").value = 0;
            document.getElementById("SalaryAmount").value = 0;

        } else {
            mycalc();

        }


    }
}

function recalcId() {
    $.each($("#footable_2 tr"), function(i, el) {
        $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
    })
}







function getemployee(sub_company_id) {

    $.ajax({
        type: "POST",
        url: "{{ route('get_employee_sub_company') }}",
        //dataType:"json",
        data: {
            sub_company_id: sub_company_id,
            "_token": "{{ csrf_token() }}"
        },
        success: function(response) {

            console.log(response);


            $('#employeeCode').html(response.html);


        }
    });
}



function checkDate(tdate) {
    var fromDate = $('#fdate').val();
    var ToDate = $('#tdate').val();

    if (ToDate < fromDate) {

        alert('Please select correct date');
        $('#tdate').val("");

    } else if (ToDate > ToDate && ToDate != "") {

        alert('Please select correct date');
        $('#fdate').val("");

    }
    var selectedDate = new Date($('#fdate').val());
        var lastDateOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
        var formattedLastDate = lastDateOfMonth.getFullYear() + '-' + ('0' + (lastDateOfMonth.getMonth() + 1)).slice(-2) + '-' + ('0' + lastDateOfMonth.getDate()).slice(-2);
        $('#tdate').val(formattedLastDate);



}


function getLines(sub_company_id) {
    
    
    
    $.ajax({
        type: "POST",
        url: "{{ route('get_line_list') }}",
        //dataType:"json",
        data: {
            sub_company_id:sub_company_id,
            "_token": "{{ csrf_token() }}"
        },
        success: function(response) {

            console.log(response);
            $('#dept_id').html(response.html);


        }
    });
}
</script>


@endsection