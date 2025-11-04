      
   @extends('layouts.master') 

@section('content')   

 @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif


                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Position</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                            <li class="breadcrumb-item active">Position List</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('Position.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Position</th>
                                                <th>Username</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($PositionList as $row)    
                                            <tr>
                                                <td>{{ $row->pos_id }}</td>
                                              
                                                <td>{{ $row->position_name }}</td>
                                                
                                                <td>{{ $row->username }}</td>
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Position.edit', $row->pos_id)}}" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                </td>
                                                <td>
                                                <form action="{{route('Position.destroy', $row->pos_id)}}" method="POST">
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