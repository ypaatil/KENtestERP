@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">PPC Holiday List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">PPC Holiday List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title --> 
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('PPCHolidayCalendar') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div> 
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr. No.</th>
                     <th>Unit</th>
                     <th>Holiday Date</th>
                     <th>User Name</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1;
                  @endphp
                  @foreach($PPCHolidayMaster as $row)    
                  <tr>
                     <td>{{ $srno++ }}</td>
                     <td>{{ $row->ac_short_name }}</td>
                     <td>{{ $row->holiday_date }}</td>  
                     <td>{{ $row->username }}</td>   
                     <td>
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->ppc_holiday_id }}"  data-route="{{route('PPCHolidayDelete', $row->ppc_holiday_id )}}" title="Delete">
                        <i class="fas fa-trash"></i>
                        </button> 
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
           
           success: function(data)
           {
              //alert(data);
                location.reload();
            }
      });
   }
   
   });
</script>  
@endsection