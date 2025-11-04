      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">City List</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                            <li class="breadcrumb-item active">City List</li>
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
       <a href="{{ Route('City.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a>
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
                                                <th>City Name</th>
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
                                                <td>{{ $row->city_name }}</td>
                                                <td>{{ $row->username }}</td>


                                    @if($chekform->edit_access==1)
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('City.edit', $row->city_id )}}" title="Edit">
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
    <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->city_id }}"  data-route="{{route('City.destroy', $row->city_id )}}" title="Delete">
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