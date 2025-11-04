@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Inward Rented Machine</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Inward Rented Machine</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="card-title mb-4">Machine Master</h4> -->
                @if ($errors->any())

                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @if(isset($inwardrented))
                <form action="{{ route('InwardRentedMachine.update',$inwardrented) }}" enctype="multipart/form-data"
                    method="POST">
                    @method('put')

                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Entry Date</label>
                                <input type="date" name="pureDate" class="form-control" id="formrow-email-input"
                                    value="{{ $inwardrented->pureDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Inward Type</label>
                                <select name="inwardtypeId" class="form-control custom-select select2"
                                    data-placeholder="Select Inward Type">
                                    <option label="Select Inward Type"></option>
                                    @foreach($RentedData as $rent)
                                    <option value="{{$rent->inwardtypeId}}"
                                        {{ $rent->inwardtypeId == $inwardrented->inwardtypeId ? 'selected="selected"' : '' }}>
                                        {{$rent->inwardtypeName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Vendor Name</label>
                                <select name="ac_code" class="form-control custom-select select2"
                                    data-placeholder="Select Vendor Name">
                                    <option label="Select Vendor Name"></option>
                                    @foreach($VendorData as $ven)
                                    <option value="{{$ven->ac_code}}"
                                        {{ $ven->ac_code == $inwardrented->ac_code ? 'selected="selected"' : '' }}>
                                        {{$ven->ac_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Purchase/Rented Date</label>
                                <input type="date" name="rentedDate" class="form-control" id="date1"
                                    value="{{ $inwardrented->rentedDate }}">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Photo</label>
                                <input type="file" name="machineimage" class="form-control" id="formrow-email-input">
                                <input type="hidden" id="lastcode" value="{{$lastCode->machineCode}}">
                            </div>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2"
                                    id="footable_2">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Machine Name</th>
                                            <th>Machine Code</th>
                                            <th>Machine Make</th>
                                            <th>Machine Type</th>
                                            <th>Purchase/Rented Rate</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                            <th>Location</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $no=1;
                                        @endphp
                                        @foreach($DetailList as $details)
                                        <tr>
                                            <td><input type="text" class="form-control" name="id[]" value="{{$no}}"
                                                    id="id{{$no}}" style="width:50px;" />
                                            </td>
                                            <td>
                                                <select class="form-control" name="MachineID[]" id="MachineID"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineNameData as $mcname)
                                                    <option value="{{$mcname->MachineID}}"
                                                        {{ $mcname->MachineID == $details->MachineID ? 'selected="selected"' : '' }}>
                                                        {{$mcname->MachineName}}</option>
                                                    @endforeach
                                                </select>
                                            </td>



                                            <td>
                                                <input type="number" name="machineCode[]" id="machineCode"
                                                    class="form-control" style="width:150px;"
                                                    value="{{$details->machineCode}}" readonly>
                                            </td>
                                            <td>
                                                <select class="form-control " name="mc_make_Id[]" id="mc_make_Id"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineMakeData as $mcmake)
                                                    <option value="{{$mcmake->mc_make_Id}}"
                                                        {{ $mcmake->mc_make_Id == $details->mc_make_Id ? 'selected="selected"' : '' }}>
                                                        {{$mcmake->machine_make_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="machinetype_id[]" id="machinetype_id"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineTypeData as $mctype)
                                                    <option value="{{$mctype->machinetype_id}}"
                                                        {{ $mctype->machinetype_id == $details->machinetype_id ? 'selected="selected"' : '' }}>
                                                        {{$mctype->machinetype_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="purchaseRate[]" id="purchaseRate"
                                                    class="form-control purchaseRate" value="{{$details->purchaseRate}}"
                                                    style="width:150px;">
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="Qty[]" id="Qty"
                                                    class="form-control qty" value="{{$details->Qty}}"
                                                    style="width:150px;">
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="amount[]" id="amount"
                                                    class="form-control amount" value="{{$details->amount}}"
                                                    style="width:150px;" readonly>
                                            </td>
                                            <td>
                                                <select class="form-control" name="mc_loc_Id[]" id="mc_loc_Id"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineLocData as $mcloc)
                                                    <option value="{{$mcloc->mc_loc_Id}}"
                                                        {{ $mcloc->mc_loc_Id == $details->mc_loc_Id ? 'selected="selected"' : '' }}>
                                                        {{$mcloc->machine_location_name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="button" style="width:40px;" id="Abutton" name="Abutton[]"
                                                    value="+" class="btn btn-warning pull-left"
                                                    onclick="insertRow(this)">
                                                <input type="button" class="btn btn-danger pull-left"
                                                    onclick="deleteRow(this);" value="X">
                                            </td>
                                        </tr>
                                        @php
                                        $no++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Total Amount</label>
                                <input type="number" step="any" name="totalAmount" id="totalAmount" class="form-control"
                                    id="formrow-email-input" value="{{ $inwardrented->totalAmount }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('InwardRentedMachine.index') }}" class="btn btn-warning w-md">Cancel</a>
                    </div>
                </form>


                @else
                <form action="{{route('InwardRentedMachine.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Entry Date</label>
                                <input type="date" name="pureDate" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Inward Type</label>
                                <select name="inwardtypeId" id="inwardTypeDropdown" class="form-control custom-select select2"
                                    data-placeholder="Select Inward Type">
                                    <option label="Select Inward Type"></option>
                                    @foreach($RentedData as $rent)
                                    <option value="{{$rent->inwardtypeId}}">{{$rent->inwardtypeName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Vendor Name</label>
                                <select name="ac_code" class="form-control custom-select select2"
                                    data-placeholder="Select Vendor Name">
                                    <option label="Select Vendor Name"></option>
                                    @foreach($VendorData as $ven)
                                    <option value="{{$ven->ac_code}}">{{$ven->ac_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" >
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label" id="dateLabel">Purchase/Rented Date</label>
                                <input type="date" name="rentedDate" class="form-control" id="date1"
                                    value="" >
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Photo</label>
                                <input type="file" name="machineimage" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2"
                                    id="footable_2">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Machine Name</th>
                                            <th>Machine Code</th>
                                            <th>Machine Make</th>
                                            <th>Machine Type</th>
                                            <th>Purchase/Rented Rate</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                            <th>Location</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- @php
                                        $machineCode=1000;
                                        @endphp -->

                                        <tr>
                                            <td><input type="text" class="form-control" name="id[]" value="1" id="id"
                                                    style="width:50px;" /></td>
                                            <td>
                                                <select class="form-control" name="MachineID[]" id="MachineID"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineNameData as $mcname)
                                                    <option value="{{$mcname->MachineID}}">
                                                        {{$mcname->MachineName}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="machineCode[]" id="machineCode"
                                                    class="form-control" style="width:150px;"
                                                    value="{{$DetailList->machineCode}}" readonly>
                                            </td>
                                            <td>
                                                <select class=" form-control " name=" mc_make_Id[]" id="mc_make_Id"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineMakeData as $mcmake)
                                                    <option value="{{$mcmake->mc_make_Id}}">
                                                        {{$mcmake->machine_make_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="machinetype_id[]" id="machinetype_id"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineTypeData as $mctype)
                                                    <option value="{{$mctype->machinetype_id}}">
                                                        {{$mctype->machinetype_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="purchaseRate[]" id="purchaseRate"
                                                    class="form-control purchaseRate" style="width:150px;">
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="Qty[]" id="Qty"
                                                    class="form-control qty" style="width:150px;">
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="amount[]" id="amount"
                                                    class="form-control amount" style="width:150px;" readonly>
                                            </td>
                                            <td>
                                                <select class="form-control" name="mc_loc_Id[]" id="mc_loc_Id"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineLocData as $mcloc)
                                                    <option value="{{$mcloc->mc_loc_Id}}">
                                                        {{$mcloc->machine_location_name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="button" style="width:40px;" id="Abutton" name="Abutton[]"
                                                    value="+" class="btn btn-warning pull-left"
                                                    onclick="insertRow(this)">
                                                <input type="button" class="btn btn-danger pull-left"
                                                    onclick="deleteRow(this);" value="X">
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Total Amount</label>
                                <input type="number" step="any" name="totalAmount" id="totalAmount" class="form-control"
                                    id="formrow-email-input" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('InwardRentedMachine.index') }}" class="btn btn-warning w-md">Cancel</a>
                    </div>
                </form>
                @endif


            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->


    <!-- end col -->
</div>
<!-- end row -->


<!-- end row -->


<!-- end row -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function insertRow(row) {
    var tr = $(row).parent().parent('tr');
    var clone = tr.clone();

    // Generate a new machineCode value
    var lastCode = $('#lastcode').val();

    if (lastCode > 0) {
        var newMachineCode = parseInt(lastCode);

    } else {
        var newMachineCode = parseInt(tr.find("td input[name='machineCode[]']").val()) + 1;
    }


    clone.find("td input[name='machineCode[]']").val(newMachineCode);

    tr.after(clone);
    recalcId();
    calculateTotals();
    $('#lastcode').val(0);
    
}
function calculateTotals() {
        var total = 0;
        $('.amount').each(function() {
            var amount = parseFloat($(this).val()) || 0;
            total += amount;
        });

        console.log('called');
        $('#totalAmount').val(total.toFixed(2));
    }


function deleteRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
    recalcId();
    calculateTotals();
}

function recalcId() {
    $.each($("#footable_2 tr"), function(i, el) {
        $(this).find("td:first input").val(i);
    });
}
$(document).ready(function() {
    $('.footable_2').on('input', '.purchaseRate, .qty', calculateAmounts);

    function calculateAmounts() {
        var row = $(this).closest('tr');
        var purchaseRate = parseFloat(row.find('.purchaseRate').val()) || 0;
        var qty = parseFloat(row.find('.qty').val()) || 0;

        var amount = purchaseRate * qty;
        row.find('.amount').val(amount.toFixed(2));

        calculateTotal();
    }

    function calculateTotal() {
        var total = 0;
        $('.amount').each(function() {
            var amount = parseFloat($(this).val()) || 0;
            total += amount;
        });

        console.log('called');
        $('#totalAmount').val(total.toFixed(2));
    }
});
$(document).ready(function() {
        var currentDate = new Date().toISOString().split('T')[0];
        $("#date1").attr("value", currentDate);
        $("#date1").attr("max", currentDate);
    });
</script>
@endsection