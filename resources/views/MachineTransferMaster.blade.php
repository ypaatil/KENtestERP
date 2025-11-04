@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Machine Transfer</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Machine Transfer</li>
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

                @if(isset($machinetransfer))
                <form action="{{ route('MachineTransfer.update',$machinetransfer) }}" enctype="multipart/form-data"
                    method="POST">
                    @method('put')

                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Transfer Date</label>
                                <input type="date" name="transDate" class="form-control" id="formrow-email-input"
                                    value="{{ $machinetransfer->transDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">From Location Name</label>
                                <input type="text" name="fromLocName" class="form-control" id="formrow-email-input"
                                    value="{{ $machinetransfer->fromLocName }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">To Location Name</label>
                                <input type="text" name="toLocName" class="form-control" id="formrow-email-input"
                                    value="{{ $machinetransfer->toLocName }}">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Vehicle Number</label>
                                <input type="text" name="vehicleNumber" class="form-control" id="formrow-email-input"
                                    value="{{ $machinetransfer->vehicleNumber }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Drive Name</label>
                                <input type="text" name="driveName" class="form-control" id="formrow-email-input"
                                    value="{{ $machinetransfer->driveName }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Remark</label>
                                <input type="text" name="remark" class="form-control" id="formrow-email-input"
                                    value="{{ $machinetransfer->remark }}">
                            </div>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2"
                                    id="footable_2">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Machine Code</th>
                                            <th>Machine Name</th>
                                            <th>Machine Make</th>
                                            <th>Model Number</th>
                                            <th>Machine Type</th>
                                            <th>Quantity</th>
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
                                                <select class="form-control" name="purId[]" id="purId"
                                                    style="width:150px;"  onChange="getMacCode(this,this.value)">
                                                    <option>--Select--</option>
                                                    @foreach($MachineCodeData as $mccode)
                                                    <option value="{{$mccode->purId}}"
                                                        {{ $mccode->purId == $details->purId ? 'selected="selected"' : '' }}>
                                                        {{$mccode->machineCode}}</option>
                                                    @endforeach
                                                </select>
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
                                                <input type="text" name="modelNumber[]" id="modelNumber"
                                                    class="form-control" style="width:150px;"
                                                    value="{{$details->modelNumber}}">
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
                                                <input type="number" step="any" name="Qty[]" id="Qty"
                                                    class="form-control qty" style="width:150px;"
                                                    value="{{$details->Qty}}">
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
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MachineTransfer.index') }}" class="btn btn-warning w-md">Cancel</a>
                    </div>
                </form>


                @else
                <form action="{{route('MachineTransfer.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Transfer Date</label>
                                <input type="date" name="transDate" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">From Location Name</label>
                                <input type="text" name="fromLocName" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">To Location Name</label>
                                <input type="text" name="toLocName" class="form-control" id="formrow-email-input"
                                    value="">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Vehicle Number</label>
                                <input type="text" name="vehicleNumber" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Drive Name</label>
                                <input type="text" name="driveName" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Remark</label>
                                <input type="text" name="remark" class="form-control" id="formrow-email-input" value="">
                            </div>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2"
                                    id="footable_2">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Machine Code</th>
                                            <th>Machine Name</th>
                                            <th>Machine Make</th>
                                            <th>Model Number</th>
                                            <th>Machine Type</th>
                                            <th>Quantity</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" name="id[]" value="1" id="id"
                                                    style="width:50px;" />
                                            </td>
                                            <td>
                                                <select class="form-control" name="purId[]" id="purId"
                                                    style="width:150px;" onChange="getMacCode(this,this.value)">
                                                    <option>--Select--</option>
                                                    @foreach($MachineCodeData as $mccode)
                                                    <option value="{{$mccode->purId}}">
                                                        {{$mccode->machineCode}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="MachineID[]" id="MachineID"
                                                    style="width:150px;" >
                                                    <option>--Select--</option>
                                                    @foreach($MachineNameData as $mcname)
                                                    <option value="{{$mcname->MachineID}}">
                                                        {{$mcname->MachineName}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control " name="mc_make_Id[]" id="mc_make_Id"
                                                    style="width:150px;">
                                                    <option>--Select--</option>
                                                    @foreach($MachineMakeData as $mcmake)
                                                    <option value="{{$mcmake->mc_make_Id}}">
                                                        {{$mcmake->machine_make_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="modelNumber[]" id="modelNumber"
                                                    class="form-control" style="width:150px;">
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
                                                <input type="number" step="any" name="Qty[]" id="Qty"
                                                    class="form-control qty" style="width:150px;">
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
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MachineTransfer.index') }}" class="btn btn-warning w-md">Cancel</a>
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
    tr.after(clone);
    recalcId();

}

function deleteRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
    recalcId();

}

function recalcId() {
    $.each($("#footable_2 tr"), function(i, el) {
        $(this).find("td:first input").val(i);
    })
}

function getMacCode(row, purId) {
var datarow = $(row).closest("tr");
$.ajax({
    type: "POST",
    dataType: "json",
    url: "{{ route('getmachinecode') }}",
    data: {
        purId: purId,
         "_token": "{{ csrf_token(); }}"
    },
    success: function(data) {
        //console.log(data);
        datarow.find('select[name^="MachineID[]"]').val(data.MachineID);
        datarow.find('select[name^="mc_make_Id[]"]').val(data.mc_make_Id);
        datarow.find('select[name^="machinetype_id[]"]').val(data.machinetype_id);

       getMake(row,data.MachineID);
    }
    
});

}

function getMake(row,MachineID) {

    var datarow = $(row).closest("tr");

$.ajax({
    type: "POST",
    dataType: "json",
    url: "{{ route('getmake') }}",
    data: {
        MachineID: MachineID,
         "_token": "{{ csrf_token(); }}"
    },
    success: function(data) {
        console.log(data);
        datarow.find('input[name^="modelNumber[]"]').val(data.ModelNumber);
       
    }
});
getMacCode();
}
</script>
@endsection