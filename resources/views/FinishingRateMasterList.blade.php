@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Finishing Rate Master List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Finishing Rate Master List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('FinishingRate.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                     <th>Sr No</th>
                     <th>Buyer</th> 
                     <th>Brand Name</th>
                     <th>Style Name</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1; 
                  @endphp
                  @foreach($finishingData as $row)    
                  <tr>
                     <td>{{ $srno++ }}</td>
                     <td>{{ $row->ac_short_name }}</td> 
                     <td>{{ $row->brand_name }}</td>
                     <td>{{ $row->substyle_name }}</td> 
                     <td>
                        @if($chekform->edit_access==1)  
                            <a class="btn btn-primary btn-icon btn-sm"  href="{{route('FinishingRate.edit', $row->finishing_rate_code)}}" >
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                        @else
                            <a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>   
                        @endif
                     </td> 
                     <td>
                        @if($chekform->delete_access==1)
                                <a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="{{ csrf_token() }}" data-id="{{ $row->finishing_rate_code }}"  data-route="{{route('FinishingRate.destroy', $row->finishing_rate_code )}}">
                                   <i class="fas fa-trash"></i>
                                </a>
                        @else
                                <a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>
                        @endif
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
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