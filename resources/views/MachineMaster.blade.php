@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Machine Master</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Machine Master</li>
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
                <h4 class="card-title mb-4">Machine Master</h4>
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

                @if(isset($machinemaster))
                <form action="{{ route('MacMaster.update',$machinemaster) }}" enctype="multipart/form-data" method="POST">
                    @method('put')

                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Maintance Type</label>
                                <select name="machine_Id" class="form-control custom-select select2"
                                    data-placeholder="Select Machine Maintance">
                                    <option label="Select Machine Maintance"></option>
                                    @foreach($MachineMaintData as $mcmaint)
                                    <option value="{{$mcmaint->machine_Id}}"
                                        {{ $mcmaint->machine_Id == $machinemaster->machine_Id ? 'selected="selected"' : '' }}>
                                        {{$mcmaint->machine_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Name</label>
                                <input type="text" name="MachineName" class="form-control" id="formrow-email-input"
                                    value="{{ $machinemaster->MachineName }}">
                                <input type="hidden" name="userId" value="{{ Session::get('userId') }}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Make</label>
                                <select name="mc_make_Id" class="form-control custom-select select2"
                                    data-placeholder="Select Machine Make">
                                    <option label="Select Machine Make"></option>
                                    @foreach($MachineMakeData as $mcmake)
                                    <option value="{{$mcmake->mc_make_Id}}"
                                        {{ $mcmake->mc_make_Id == $machinemaster->mc_make_Id ? 'selected="selected"' : '' }}>
                                        {{$mcmake->machine_make_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Model Number</label>
                                <input type="text" name="ModelNumber" class="form-control" id="formrow-email-input"
                                    value="{{ $machinemaster->ModelNumber }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Type</label>
                                <select name="machinetype_id" class="form-control custom-select select2"
                                    data-placeholder="Select Machine Type">
                                    <option label="Select Machine Type"></option>
                                    @foreach($MachineTypeData as $mctype)
                                    <option value="{{$mctype->machinetype_id}}"
                                        {{ $mctype->machinetype_id == $machinemaster->machinetype_id ? 'selected="selected"' : '' }}>
                                        {{$mctype->machinetype_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">M/c Description</label>
                                <textarea class="summernote"
                                    name="McDescription">{{$machinemaster->McDescription}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Sr No</label>
                                <input type="text" name="MachineSrNo" class="form-control" id="MachineSrNo"  value="{{ $machinemaster->MachineSrNo }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pur_date" class="form-label">Purchase Date</label>
                                <input type="date" name="pur_date" class="form-control" id="pur_date"  value="{{ $machinemaster->pur_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Photo</label>
                                <input type="file" name="MachinePhoto" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MacMaster.index') }}" class="btn btn-warning w-md">Cancel</a>
                    </div>
                </form>


                @else
                <form action="{{route('MacMaster.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Maintance Type</label>
                                <select name="machine_Id" class="form-control custom-select select2"
                                    data-placeholder="Select Machine Maintance">
                                    <option label="Select Machine Maintance"></option>
                                    @foreach($MachineMaintData as $mcmaint)
                                    <option value="{{$mcmaint->machine_Id}}">{{$mcmaint->machine_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Name</label>
                                <input type="text" name="MachineName" class="form-control" id="formrow-email-input"
                                    value="">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}"
                                    class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Make</label>
                                <select name="mc_make_Id" class="form-control custom-select select2"
                                    data-placeholder="Select Machine Make">
                                    <option label="Select Machine Make"></option>
                                    @foreach($MachineMakeData as $mcmake)
                                    <option value="{{$mcmake->mc_make_Id}}">{{$mcmake->machine_make_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Model Number</label>
                                <input type="text" name="ModelNumber" class="form-control" id="formrow-email-input"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Type</label>
                                <select name="machinetype_id" class="form-control custom-select select2"
                                    data-placeholder="Select Machine Type">
                                    <option label="Select Machine Type"></option>
                                    @foreach($MachineTypeData as $mctype)
                                    <option value="{{$mctype->machinetype_id}}">{{$mctype->machinetype_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">M/c Description</label>
                                <textarea class="summernote" name="McDescription"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="MachineSrNo" class="form-label">Machine Sr No</label>
                                <input type="text" name="MachineSrNo" class="form-control" id="MachineSrNo"  value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pur_date" class="form-label">Purchase Date</label>
                                <input type="date" name="pur_date" class="form-control" id="pur_date"  value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Machine Photo</label>
                                <input type="file" name="MachinePhoto" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a href="{{ Route('MacMaster.index') }}" class="btn btn-warning w-md">Cancel</a>
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
@endsection