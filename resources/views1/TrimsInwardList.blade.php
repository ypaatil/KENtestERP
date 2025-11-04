@extends('layouts.master') 
@section('content')   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.1/sweetalert2.css" />
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
      <a href="{{ Route('TrimsInward.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New</button></a> 
   </div>
</div>
@endif
</br>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                  <tr>
                     <th>SrNo</th>
                     <th>GRN No</th>
                     <th>GRN Date</th>
                     <th>PO No</th>
                     <th>Supplier</th>
                     <th>Total Quantity</th>
                     <th>Supplier Invoice No</th>
                     <th>Edit</th>
                     <th>Print</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($data as $row)    
                  <tr>
                     <th>{{ $row->sr_no }}</th>
                     <td>{{ $row->trimCode }}</td>
                     <td>{{ $row->trimDate }}</td>
                     <td>{{ $row->po_code }}</td>
                     <td>{{ $row->ac_name }}</td>
                     <td>{{ $row->totalqty }}</td>
                     <td>{{ $row->invoice_no }}</td>
                     @if($chekform->edit_access==1)
                     <td>   <a class="btn   btn-sm edit" href="{{route('TrimsInward.edit',$row->sr_no)}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td>
                     @else
                     <td>    <a class="btn   btn-sm edit" href="" title="Edit">
                        <i class="fas fa-lock"></i>
                        </a>
                     </td>
                     @endif
                     <td>   <a class="btn btn-outline-secondary btn-sm print" target="_blank" href="TrimsGRNPrint/{{ base64_encode($row->trimCode) }}" title="print">
                        <i class="fas fa-print"></i>
                        </a>
                     </td>
                     @if($chekform->delete_access==1)
                     <td>    
                        <button  class="btn btn-sm delete DeleteRecord" data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" po_code="{{$row->po_code}}" data-id="{{ base64_encode($row->trimCode) }}"
                            data-route="{{ route('TrimsInward.destroy',base64_encode($row->trimCode) )}}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                     </td>
                     @else
                     <td>    <button class="btn   btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.1/sweetalert2.min.js"></script>
<script type="text/javascript"> 

   $(document).on('click','.DeleteRecord',function(e) 
   {
        var po_code = $(this).attr("po_code");
        var Route = $(this).attr("data-route");
        var id = $(this).data("id");
        var token = $(this).data("token");
        
        $.ajax({
            type: "GET",
            url: "{{ route('checkPOIsExist') }}",
            data:{'po_code':po_code, },
            success: function(data)
            {
                if(data.html > 0)
                {
                    Swal.fire({
                        title: 'Not Deleted!',
                        text: 'Outward under this PO is done!',
                        icon: 'warning',
                    })
                }
                else
                {
                    Swal.fire({
                        title: 'Are you sure?',
                        icon:'warning',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                  url: Route,
                                  type: "DELETE",
                                    data: {
                                    "id": id,
                                    "_method": 'DELETE',
                                     "_token": token,
                                     },
                                   
                                  success: function(data){
                           
                                    Swal.fire({
                                            title: 'Deleted!',
                                            icon: 'success',
                                     });
                                    location.reload();
                           
                                  }
                            });
                        }
                    });
                }
            }
        });

        
//       var Route = $(this).attr("data-route");
//       var id = $(this).data("id");
//       var token = $(this).data("token");
   
//      //alert(Route);
    
//     //alert(data);
//       if (confirm("Are you sure you want to Delete this Record?") == true) {
//     $.ajax({
//           url: Route,
//           type: "DELETE",
//             data: {
//             "id": id,
//             "_method": 'DELETE',
//              "_token": token,
//              },
           
//           success: function(data){
   
//               //alert(data);
//           location.reload();
   
//           }
//   });
//   }
   
   });
</script>
@endsection