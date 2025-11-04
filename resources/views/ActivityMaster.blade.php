@extends('layouts.master') 

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Activity Master</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Activity Master</li>
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
                <h4 class="card-title mb-4">Activity Master</h4>
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

                @if(isset($ActivityMasterEdit))
                <form action="{{ route('ActivityMaster.update',$ActivityMasterEdit) }}" method="POST" id="frmData">
                    @method('put')

                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Activity Name</label>
                                <input type="text" name="act_name" class="form-control" id="formrow-email-input" value="{{ $ActivityMasterEdit->act_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Activity Type</label>
                                <select name="act_type_id" class="form-select" id="act_type_id" required>
                                    <option value="0">--- Select Activity Type ---</option>
                                    @foreach($act_type_list as  $rowacttype)
                                    {
                                        <option value="{{ $rowacttype->act_type_id  }}" {{ $rowacttype->act_type_id == $ActivityMasterEdit->act_type_id ? 'selected="selected"' : '' }}>{{ $rowacttype->act_type_name}}</option>
                                    }
                                    @endforeach
                                </select>
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="delflag" value="0" class="form-control" id="formrow-email-input">
                            </div>
                             </div>
                             
                             <div class="col-md-4">
                        <div class="mb-3">
                        <label for="dept_id" class="form-label">Department</label>
                        <select name="dept_id" class="form-select" id="dept_id">
                        <option value="">--Type--</option>
                        @foreach($DeptList as  $row)
                        {
                        <option value="{{ $row->dept_id }}" {{ $row->dept_id == $ActivityMasterEdit->dept_id ? 'selected="selected"' : '' }}>{{ $row->dept_name }}</option>
                        
                        }
                        @endforeach
                        </select>
                        </div>
                        </div>
                             
                             
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md" id="Submit">Update</button>
                    </div>
                </form>
                @else
                <form action="{{route('ActivityMaster.store')}}" method="POST">
                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Acitivity 
                                Name</label>
                                <input type="text" name="act_name" class="form-control" id="act_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Operators</label>
                                <select name="act_type_id" class="form-select" id="act_type_id" required>
                                    <option value="0">--- Select Activity Type ---</option>
                                    @foreach($act_type_list as  $rowacttype)
                                    {
                                        <option value="{{ $rowacttype->act_type_id }}">{{ $rowacttype->act_type_name }}</option>
                                    }
                                    @endforeach
                                </select>
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="delflag" value="0" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                        <div class="mb-3">
                        <label for="dept_id" class="form-label">Department</label>
                        <select name="dept_id" class="form-select" id="dept_id">
                        <option value="">--Type--</option>
                        @foreach($DeptList as  $row)
                        {
                        <option value="{{ $row->dept_id }}">{{ $row->dept_name }}</option>
                        
                        }
                        @endforeach
                        </select>
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