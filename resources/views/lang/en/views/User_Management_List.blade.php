      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                            <li class="breadcrumb-item active">User Management List</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                     @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif


 @if($chekform->write_access==1)
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('User_Management.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                                                         <th>USER ID</th>
                                                        <th>USER NAME</th>
                                                        
                                                        <th>DESIGNATION</th>
                                                        <th>CONTACT</th>
                                                        <th>ADDRESS</th>
                                                        <th>EDIT</th>
                                                        <th>DELETE</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($userlist as $row)    
                                            <tr>
                                                <td>{{ $row->userId  }}</td>
                                                <td>{{ $row->username }}</td>
                                                
                                                <td>{{ $row->user_type }}</td>
                                                <td>{{ $row->contact }}</td>
                                                <td>{{ $row->address }}</td>

 @if($chekform->edit_access==1)
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('User_Management.edit', $row->userId)}}" title="Edit">
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
                                                <form action="{{route('User_Management.destroy', $row->userId)}}" method="POST">
<input type="hidden" name="_method" value="DELETE">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
<i class="fas fa-trash"></i>
</button>
</form>
                                                </td>

   @else
      <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                                                                <i class="fas fa-lock"></i>
                                                            </a>
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