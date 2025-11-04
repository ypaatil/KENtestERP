      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                            <li class="breadcrumb-item active">Ledger Master</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('Ledger.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                                                <th>Account Code</th>
                                                <th>Account Name</th>
                                                <th>Short Name</th>
                                                <th>Pan No</th>
                                                <th>GST No</th>
                                                <th>State</th>
                                                <th>District</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th>Business Type</th> 
                                                <th>EDIT</th>
                                                <th>DELETE</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($Ledger as $row)    
                                            <tr>
                                                <td>{{ $row->ac_code }}</td>
                                                <td>{{ $row->ac_name }}</td>
                                                <td>{{ $row->ac_short_name }}</td>
                                                <td>{{ $row->pan_no }}</td>
                                                <td>{{ $row->gst_no }}</td>
                                                <td>{{ $row->state_name }}</td>
                                                <td>{{ $row->d_name }}</td>
                                                <td>{{ $row->address }}</td>
                                                <td>{{ $row->city_name }}</td>
                                                <td>{{ $row->Bt_name }}</td>
                                                 @if($chekform->edit_access==1)
                                                <td>
                                                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Ledger.edit', $row->ac_code)}}" title="Edit">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                </td>
                                                @else
                                                <td>
                                                        <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                                                            <i class="fas fa-lock"></i>
                                                        </a>
                                                </td>
                                                @endif
                                                @if($chekform->delete_access==1)
                                                <td>
                                                    <form action="{{route('Ledger.destroy', $row->ac_code)}}" method="POST">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="fas fa-lock"></i>
                                                    </button>
                                                </td>
                                                @endif

                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        @endsection