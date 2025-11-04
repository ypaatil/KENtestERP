      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Firm Master</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                            <li class="breadcrumb-item active">Firm Master</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('Firm.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
                        </div>
                        </div>
                        @if(session()->has('message'))
                        <div class="col-md-3">
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        </div>
                        @endif

                        @if(session()->has('messagedelete'))
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                {{ session()->get('messagedelete') }}
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr>
                                                <th>Firm Code</th>
                                                <th>Firm Name</th>
                                                <th>Pan No</th>
                                                <th>GST No</th>
                                                <th>State</th>
                                                <th>District</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th>Mobile</th>
                                                <th>Email ID</th>
                                                <th>Owner Name</th>
                                                <th>EDIT</th>
                                                <th>DELETE</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($FirmList as $row)    
                                            <tr>
                                                <td>{{ $row->firm_id }}</td>
                                                <td>{{ $row->firm_name }}</td>
                                                <td>{{ $row->pan_no }}</td>
                                                <td>{{ $row->gst_no }}</td>
                                                <td>{{ $row->state_name }}</td>
                                                <td>{{ $row->d_name }}</td>
                                                <td>{{ $row->Address }}</td>
                                                <td>{{ $row->city_name }}</td>
                                                <td>{{ $row->mobile_no }}</td>
                                                <td>{{ $row->email_id }}</td>
                                                <td>{{ $row->owner_name }}</td>
                                                
                                                 <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Firm.edit', $row->firm_id)}}" title="Edit">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                </td>
                                                <td>
                                                <form action="{{route('Firm.destroy', $row->firm_id)}}" method="POST">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash"></i>
                                                </button>
                                                </form>
                                                </td>
                                                

                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        @endsection