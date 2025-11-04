@extends('layouts.master') 

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Present Days Master</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Present Days Master</li>
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
                <h4 class="card-title mb-4">Present Days Master</h4>
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

                @if(isset($PresentEmployee))
                <form action="{{ route('PresentEmployees.update',$PresentEmployee) }}" method="POST">
                    @method('put')

                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Date</label>
                                <input type="date" name="pe_date" class="form-control" id="formrow-email-input" value="{{ $PresentEmployee->pe_date }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Operators</label>
                                <input type="number" name="operators" class="form-control" id="formrow-email-input" value="{{ $PresentEmployee->operators }}">
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
                <form action="{{route('PresentEmployees.store')}}" method="POST">
                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Date</label>
                                <input type="date" name="pe_date" class="form-control" id="pe_date" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="formrow-email-input" class="form-label">Operators</label>
                                <input type="number" name="operators" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="delflag" value="0" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
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

@endsection