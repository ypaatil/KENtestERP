@extends('layouts.master')
<style>
input[type="time"]::-webkit-datetime-edit-ampm-field {
    display: none;
}
</style>
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Machine Maintance</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Machine Maintance</li>
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

                @if(isset($machinerymaintance))
                <form action="{{ route('MachineryMaintance.update',$machinerymaintance) }}"
                    enctype="multipart/form-data" method="POST">
                    @method('put')

                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" id="formrow-email-input"
                                    value="{{ $machinerymaintance->date }}">
                            </div>
                        </div>
                      <!-- new 28-11-2024 -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Code</label>
                                <select class="form-control" name="machineCode" id="machineCode">
                                    @if($MachineCodeData)
                                    <option value="{{ $MachineCodeData->machineCode }}" selected>
                                        {{ $MachineCodeData->machineCode }}
                                    </option>
                                    @else
                                    <option>--No Data Available--</option>
                                    @endif
                                </select>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Name</label>
                                <select class="form-control" name="MachineID" id="MachineID">
                                    <option>--Select--</option>
                                    @foreach($MachineNameData as $mcname)
                                    <option value="{{$mcname->MachineID}}"
                                        {{ $mcname->MachineID == $machinerymaintance->MachineID ? 'selected="selected"' : '' }}>
                                        {{$mcname->MachineName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Location Name</label>
                                <select class="form-control" name="mc_loc_Id" id="mc_loc_Id">
                                    <option>--Select--</option>
                                    @foreach($MachineLocData as $mcloc)
                                    <option value="{{$mcloc->mc_loc_Id}}"
                                        {{ $mcloc->mc_loc_Id == $machinerymaintance->mc_loc_Id ? 'selected="selected"' : '' }}>
                                        {{$mcloc->machine_location_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Purpose</label>
                                <input type="text" name="purpose" class="form-control" id="formrow-email-input"
                                    value="{{ $machinerymaintance->purpose }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Problem Addressed</label>
                                <input type="text" name="proAddress" class="form-control" id="formrow-email-input"
                                    value="{{ $machinerymaintance->proAddress }}">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Issue Solved</label>
                                <select class="form-control" name="rentedId" id="rentedId">
                                    <option>--Select--</option>
                                    @foreach($RentedData as $rent)
                                    <option value="{{$rent->rentedId}}"
                                        {{ $rent->rentedId == $machinerymaintance->rentedId ? 'selected="selected"' : '' }}>
                                        {{$rent->rentedName}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Total Down Time</label>
                                <input type="time" id="timepicker" name="totalDownTime" class="form-control"
                                    id="formrow-email-input" value="{{ $machinerymaintance->totalDownTime }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Remark</label>
                                <input type="text" name="remark" class="form-control" id="formrow-email-input"
                                    value="{{ $machinerymaintance->remark }}">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MachineryMaintance.index') }}" class="btn btn-warning w-md">Cancel</a>
                    </div>
                </form>


                @else
                <form action="{{route('MachineryMaintance.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" id="formrow-email-input" value="">
                            </div>
                        </div>
                        <!-- new 28-11-2024 -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Code</label>
                                <select class="form-control" name="machineCode" id="machineCode"
                                    onChange="getMacCode(this.value)">
                                    <option>--Select--</option>
                                    @foreach($MachineCodeData as $mccode)
                                    <option value="{{$mccode->machineCode}}">
                                        {{$mccode->machineCode}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Name</label>
                                <select class="form-control" name="MachineID" id="MachineID">
                                    <option>--Select--</option>
                                    @foreach($MachineNameData as $mcname)
                                    <option value="{{$mcname->MachineID}}">
                                        {{$mcname->MachineName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Location Name</label>
                                <select class="form-control" name="mc_loc_Id" id="mc_loc_Id">
                                    <option>--Select--</option>
                                    @foreach($MachineLocData as $mcloc)
                                    <option value="{{$mcloc->mc_loc_Id}}">
                                        {{$mcloc->machine_location_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Purpose</label>
                                <input type="text" name="purpose" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Problem Addressed</label>
                                <input type="text" name="proAddress" class="form-control" id="formrow-email-input"
                                    value="">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Issue Solved</label>
                                <select class="form-control" name="rentedId" id="rentedId">
                                    <option>--Select--</option>
                                    @foreach($RentedData as $rent)
                                    <option value="{{$rent->rentedId}}">
                                        {{$rent->rentedName}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Total Down Time</label>
                                <input type="time" id="timepicker" name="totalDownTime" class="form-control"
                                    id="formrow-email-input" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Remark</label>
                                <input type="text" name="remark" class="form-control" id="formrow-email-input" value="">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MachineryMaintance.index') }}" class="btn btn-warning w-md">Cancel</a>
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
 <!-- new 28-11-2024 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function getMacCode(machineCode) {


    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('getmachinerycode') }}",
        data: {
            machineCode: machineCode,
            "_token": "{{ csrf_token(); }}"
        },
        success: function(data) {
            //console.log(data);
            $('#MachineID').val(data.MachineID);
            $('#mc_loc_Id').val(data.mc_loc_Id);

        }

    });

}
</script>
@endsection