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
            <h4 class="mb-sm-0 font-size-18">Machinery Preventive Maintance</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Machinery Preventive Maintance</li>
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

                @if(isset($machinerypreventive))
                <form action="{{ route('MachineryPreventive.update',$machinerypreventive) }}"
                    enctype="multipart/form-data" method="POST">
                    @method('put')

                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Code</label>
                                <select class="form-control" name="purId" id="purId">
                                    <option>--Select--</option>
                                    @foreach($MachineCodeData as $mccode)
                                    <option value="{{$mccode->purId}}"
                                        {{ $mccode->purId == $machinerypreventive->purId ? 'selected="selected"' : '' }}>
                                        {{$mccode->machineCode}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label"> Preventive Date</label>
                                <input type="date" name="preDate" class="form-control" id="formrow-email-input"
                                    value="{{ $machinerypreventive->preDate }}">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label"> Preventive Name</label>
                                <input type="text" name="preName" class="form-control" id="formrow-email-input"
                                    value="{{ $machinerypreventive->preName }}">
                            </div>
                        </div> -->

                        <!-- new 28-11-2024 -->

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Preventive Name</label>
                                <select class="form-control" name="preventName_ID" id="preventName_ID">
                                    <!-- <option value="">--Select--</option> Default option -->

                                    @if ($PreventiveNameData)
                                    <!-- Check if $PreventiveNameData is not null -->
                                    <option value="{{ $PreventiveNameData->preventive_Id }}"
                                        {{ $PreventiveNameData->preventive_Id == $machinerypreventive->preventive_name ? 'selected="selected"' : '' }}>
                                        {{ $PreventiveNameData->preventive_name }}
                                    </option>
                                    @endif
                                </select>
                            </div>
                        </div>




                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Preventive Duration</label>
                                <input type="text" name="preDuration" class="form-control" id="formrow-email-input"
                                    value="{{ $machinerypreventive->preDuration }}">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="custom-controls-stacked d-md-flex">
                                <label class="form-label me-4"> Status :</label>
                                <label class="custom-control custom-radio success me-4">
                                    <input type="radio" id="complete" value="3" name="status"
                                        class="custom-control-input"
                                        {{ $machinerypreventive->status == 3 ? 'checked="checked"' : '' }}>
                                    <span class="custom-control-label">Completed</span>
                                </label>
                                <label class="custom-control custom-radio success me-4">
                                    <input type="radio" id="onhold" value="2" name="status" class="custom-control-input"
                                        {{ $machinerypreventive->status == 2 ? 'checked="checked"' : '' }}>
                                    <span class="custom-control-label">On hold</span>
                                </label>
                                <label class="custom-control custom-radio success">
                                    <input type="radio" id="onprogress" value="1" name="status"
                                        class="custom-control-input"
                                        {{ $machinerypreventive->status == 1 ? 'checked="checked"' : '' }}>
                                    <span class="custom-control-label">On Progress</span>
                                </label>
                                <label class="custom-control custom-radio success"
                                    style="margin-left:20px;margin-top:2px">
                                    <input type="radio" class="custom-control-input" name="status" value="0"
                                        {{ $machinerypreventive->status == 0 ? 'checked="checked"' : '' }}>
                                    <span class="custom-control-label">Pending</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MachineryPreventive.index') }}" class="btn btn-warning w-md">Cancel</a>
                    </div>
                </form>


                @else
                <form action="{{route('MachineryPreventive.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Code</label>
                                <select class="form-control" name="purId" id="purId">
                                    <option>--Select--</option>
                                    @foreach($MachineCodeData as $mccode)
                                    <option value="{{$mccode->purId}}">
                                        {{$mccode->machineCode}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label"> Preventive Date</label>
                                <input type="date" name="preDate" class="form-control" id="formrow-email-input"
                                    value="">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label"> Preventive Name</label>
                                <input type="text" name="preName" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div> -->

                        <!-- new 28-11-2024 -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Preventive Name</label>
                                <select class="form-control" name="preventName_ID" id="preventName_ID">
                                    <option>--Select--</option>
                                    @foreach($PreventiveNameData as $mcname)
                                    <option value="{{$mcname->preventive_name}}">
                                        {{$mcname->preventive_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Preventive Duration</label>
                                <input type="text" name="preDuration" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="custom-controls-stacked d-md-flex">
                                <label class="form-label me-4">Status :</label>
                                <label class="custom-control custom-radio success me-4">
                                    <input type="radio" for="complete" value="3" name="status"
                                        class="custom-control-input">
                                    <span class="custom-control-label">Completed</span>
                                </label>
                                <label class="custom-control custom-radio success me-4">
                                    <input type="radio" for="onhold" value="2" name="status"
                                        class="custom-control-input">
                                    <span class="custom-control-label">On hold</span>
                                </label>
                                <label class="custom-control custom-radio success">
                                    <input type="radio" for="onprogress" value="1" name="status"
                                        class="custom-control-input">
                                    <span class="custom-control-label">On Progress</span>
                                </label>
                                <label class="custom-control custom-radio success"
                                    style="margin-left:20px;margin-top:2px">
                                    <input type="radio" value="0" class="custom-control-input" for="pending"
                                        name="status">
                                    <span class="custom-control-label">Pending</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MachineryPreventive.index') }}" class="btn btn-warning w-md">Cancel</a>
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
function getMacCode(purId) {


    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('getmachinerycode') }}",
        data: {
            purId: purId,
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