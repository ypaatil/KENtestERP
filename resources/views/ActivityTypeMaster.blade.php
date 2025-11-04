@extends('layouts.master') 

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Activity Type Master</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Activity Type Master</li>
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
                <h4 class="card-title mb-4">Activity Type Master</h4>
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

                @if(isset($ActivityTypeMasterEdit))
                <form action="{{ route('ActivityTypeMaster.update',$ActivityTypeMasterEdit) }}" method="POST" id="frmData">
                    @method('put')

                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Activity Type Name</label>
                                <input type="text" name="act_type_name" class="form-control" id="formrow-email-input" value="{{ $ActivityTypeMasterEdit->act_type_name }}">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="delflag" value="0" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Update</button>
                    </div>
                </form>
                @else
                <form action="{{route('ActivityTypeMaster.store')}}" method="POST">
                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Activity Type Name</label>
                                <input type="text" name="act_type_name" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="delflag" value="0" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md" id="Submit">Submit</button>
                    </div>
                </form>
                @endif
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });   
</script>
@endsection