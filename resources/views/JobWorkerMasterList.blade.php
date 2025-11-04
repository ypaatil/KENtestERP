      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Job Worker Master</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                            <li class="breadcrumb-item active">Job Worker Master</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('JobWorker.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                                            <th>Worker ID</th>
														<th>Worker Name</th>
														<th>Contact No</th>
														<th>Address</th>
														<th>Particular</th>
														<th>Department</th>
														<th>Employee Group</th>
														<th>Basic Salary</th>
														<th>Payment Term</th>
														<th>Days</th>
														 
                                                        <th>User</th>
														<th>EDIT</th>
														<th>DELETE</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($WorkerList as $row)    
                                            <tr>
                                                <td>{{ $row->w_id }}</td>
                                                <td>{{ $row->w_name }}</td>
                                                <td>{{ $row->w_contact }}</td>
                                                <td>{{ $row->w_address }}</td>
                                                <td>{{ $row->w_particular }}</td>
                                                <td>{{ $row->dept_name }}</td>
                                                <td>{{ $row->egroup_name }}</td>
                                                <td>{{ $row->basic_pay }}</td>
                                                <td>{{ $row->ptm_name }}</td>
                                                <td>{{ $row->day_count }}</td>
                                                <td>{{ $row->username }}</td>
                                                
                                                
                                                  @if($chekform->edit_access==1)
                                                 <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('JobWorker.edit', $row->w_id)}}" title="Edit">
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
                                         <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->w_id }}"  data-route="{{route('JobWorker.destroy', $row->w_id )}}" title="Delete">
<i class="fas fa-trash"></i>
</button>                   
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
                                                                                  <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 

 $(document).on('click','#DeleteRecord',function(e) {

    var Route = $(this).attr("data-route");
    var id = $(this).data("id");
    var token = $(this).data("token");

   //alert(Route);
  
  //alert(data);
    if (confirm("Are you sure you want to Delete this Record?") == true) {
  $.ajax({
         url: Route,
         type: "DELETE",
          data: {
          "id": id,
          "_method": 'DELETE',
           "_token": token,
           },
         
         success: function(data){

            //alert(data);
         location.reload();

         }
});
}

 });
</script>        
                        
                        
                        @endsection