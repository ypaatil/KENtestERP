@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">PDMerchant Master </h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">PDMerchant Master</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('PDMerchantMaster.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                     <th>PDMerchant Name</th>
                     <th>Contact</th>
                     <th>Email</th>
                     <th>Username</th>
                     <th>Status </th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($PDMerchantMasterList as $row)    
                  <tr>
                      @php
                      
                        if($row->status == 1)
                        {
                            $status = 'Active';
                            $color = 'success';
                        }
                        else
                        {
                            $status = 'In Active';
                            $color = 'danger';
                        }
                      @endphp
                     <td>{{ $row->PDMerchant_name }}</td>
                     <td>{{ $row->contact }}</td>
                     <td>{{ $row->email }}</td>
                     <td>{{ $row->username }}</td>
                     <td>
                         <a class="btn btn-{{$color}} btn-sm" href="javascript:void(0);" PDMerchant_id="{{$row->PDMerchant_id}}" status="{{$row->status}}" onclick="ChangeStatus(this);" title="status">
                            {{$status}}
                        </a>
                    </td>
                     @if($chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('PDMerchantMaster.edit', $row->PDMerchant_id)}}" title="Edit">
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
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->PDMerchant_id }}"  data-route="{{route('PDMerchantMaster.destroy', $row->PDMerchant_id )}}" title="Delete">
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
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript"> 

   function ChangeStatus(row)
   {  
          var PDMerchant_id = $(row).attr('PDMerchant_id');
          var status1 = $(row).attr('status');
          if(status1 == 1)
          {
              var status = 0;
          }
          else
          {
              var status = 1;
          }
          
          Swal.fire({
            title: 'Are you sure?',
            text: "This status will change",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
          }).then((result) => {
            if (result.isConfirmed) 
            {
                 $.ajax({
                 type: "GET", 
                 url: "{{ route('changePDMerchantStatus') }}",
                 data:{'PDMerchant_id':PDMerchant_id,'status':status},
                 success: function(data)
                 {
                     Swal.fire(
                        'Closed!',
                        'Your PD Merchant status has been changed.',
                        'success'
                      )
                      
                      if(status == 1)
                      { 
                        $(row).removeClass('btn-danger').addClass('btn-success').html('Active');
                      }
                      else
                      {
                        $(row).removeClass('btn-success').addClass('btn-danger').html('In Active');
                      }
                      
                      $(row).attr('status', status);
                  }
                  }); 
            }
          })
    }
    
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