      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                            <li class="breadcrumb-item active">District List</li>
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
       <a href="{{ Route('District.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a>
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
                                                <th>Country Name</th>
                                                <th>State Name</th>
                                                <th>District Name</th>
                                                <th>Username</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($data as $row)    
                                            <tr>
                                                <td>{{ $row->c_name }}</td>
                                                <td>{{ $row->state_name }}</td>
                                                <td>{{ $row->d_name }}</td>
                                                <td>{{ $row->username }}</td>


                                    @if($chekform->edit_access==1)
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('District.edit', $row->d_id )}}" title="Edit">
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
                                                <form action="{{route('District.destroy', $row->d_id )}}" method="POST">
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