        @extends('layouts.master') 

        @section('content')
        <div class="row">
        <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Transport Master</h4>

        <div class="page-title-right">
        <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
        <li class="breadcrumb-item active">Transport Master</li>
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
        <h4 class="card-title mb-4">Transport</h4>
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

        @if(isset($Transport))
        <form action="{{ route('Transport.update',$Transport) }}" method="POST">
        @method('put')

        @csrf 
        <div class="row">

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Transport Name</label>
        <input type="text" name="transport_name" class="form-control" id="formrow-email-input" value="{{ $Transport->transport_name }}">
        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
        <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $Transport->created_at }}">
        </div>
        </div>

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Transport Contact</label>
        <input type="text" name="transport_contact" class="form-control" id="formrow-email-input" value="{{ $Transport->transport_contact }}">
       
        </div>
        </div>

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Address</label>
        <input type="text" name="transport_address" class="form-control" id="formrow-email-input" value="{{ $Transport->transport_address }}">
       
        </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Email</label>
        <input type="text" name="transport_email" class="form-control" id="formrow-email-input" value="{{ $Transport->transport_email }}">
        
        </div>
        </div>

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">GST No.</label>
        <input type="text" name="gst_number" class="form-control" id="formrow-email-input" value="{{ $Transport->gst_number }}">
        </div>
        </div>

        </div>
        <div>
        <button type="submit" class="btn btn-primary w-md">Submit</button>
        </div>
        </form>


        @else
        <form action="{{route('Transport.store')}}" method="POST">
        @csrf 
        <div class="row">

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Transport Name</label>
        <input type="text" name="transport_name" class="form-control" id="formrow-email-input" value="">
        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
        </div>
        </div>

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Transport Contact</label>
        <input type="text" name="transport_contact" class="form-control" id="formrow-email-input" value="">
       
        </div>
        </div>

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Address</label>
        <input type="text" name="transport_address" class="form-control" id="formrow-email-input" value="">
       
        </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">Email</label>
        <input type="text" name="transport_email" class="form-control" id="formrow-email-input" value="">
        
        </div>
        </div>

        <div class="col-md-4">
        <div class="mb-3">
        <label for="formrow-email-input" class="form-label">GST No.</label>
        <input type="text" name="gst_number" class="form-control" id="formrow-email-input" value="">
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


        <!-- end col -->
        </div>
        <!-- end row -->


        <!-- end row -->


        <!-- end row -->
        @endsection