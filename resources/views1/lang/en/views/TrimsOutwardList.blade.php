
@extends('layouts.master') 

@section('content')   

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                            <li class="breadcrumb-item active">Trim Inward List</li>
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

 @if(session()->has('delete'))
    <div class="alert alert-danger">
        {{ session()->get('delete') }}
    </div>
@endif



@if($chekform->write_access==1)

<div class="row">
<div class="col-lg-6">
<a href="{{ Route('TrimsOutward.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New</button></a> 
</div>
</div>
@endif
</br>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                            <tr>
                                 <th>SrNo</th>
                                <th>Trim No</th>
                                <th>Trim Date</th>
                                <th>Supplier</th>
                                <th>Total Quantity</th> 
                                <th>Username</th>
                                 <th>Action</th>
                              
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($data as $row)    
                            <tr>
                                 <th>{{ $row->sr_no }}</th>
                                <td>{{ $row->trimCode }}</td>
                                <td>{{ $row->pur_date }}</td>
                                <td>{{ $row->ac_name }}</td>
                                <td>{{ $row->totalqty }}</td>
                                <td>{{ $row->username }}</td>

                            <td>
                                
                                 
                                @if($chekform->edit_access==1)
                                
                                <a class="btn   btn-sm edit" href="{{route('TrimsOutward.edit',$row->trimCode)}}" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                
                                @else
                           
                               
                                            <a class="btn   btn-sm edit" href="" title="Edit">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                

                                @endif

                                @if($chekform->delete_access==1)
                                                                
                                        <button  class="btn   btn-sm delete" 
                                        data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ base64_encode($row->trimCode) }}"
                                        data-route="{{ route('TrimsOutward.destroy',base64_encode($row->trimCode) )}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        </button>
                                 
                                     @else
                                
                                                           
                                    <button class="btn   btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                                    <i class="fas fa-lock"></i>
                                    </button>
                               
                                
                                @endif
                                 
                               
                                
                                 </td>
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