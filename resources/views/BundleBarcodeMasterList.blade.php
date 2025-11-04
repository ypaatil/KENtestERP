@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Bundle Barcode</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Bundle Barcode</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->  
@if($chekform->write_access==1)   
<div class="row">
   <div class="col-md-4">
      <a href="{{ Route('BundleBarcode.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
   <div class="col-lg-4 text-center">
      <h4><b>Note : </b> Showing last 2 month records. If you want to all click on <a href="BundleBarcodeShowAll">Show All Data</a> button</h4>
   </div>
   <div class="col-lg-4 text-right">
      <a href="BundleBarcodeShowAll"><button type="button" class="btn btn-warning w-md float-right">Show All Data</button></a> &nbsp; &nbsp; &nbsp;
      <a href="BundleBarcode"><button type="button" class="btn btn-danger w-md float-right">Back</button></a> &nbsp; &nbsp; &nbsp;
   </div>
</div>
@endif
@if(session()->has('message'))
<div class="col-md-3">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('delete'))
<div class="col-md-3">
   <div class="alert alert-danger">
      {{ session()->get('delete') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="dt" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th nowrap>BB No</th>
                     <th>Date</th>
                     <th>Task Id</th>
                     <th>VPO Code</th>
                     <th>Sales Order No.</th>
                     <th>Vendor Name</th>
                     <th>Main Style</th>
                     <th>Sub Style</th>
                     <th>Style Name</th>
                     <th>Style No</th>
                     <th>Total Piece</th>
                     <th>Narration</th>
                     <th>User</th>
                     <th>Created At</th>
                     <th>Updated At</th>
                     <th>Edit</th>
                     <th>Print</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($BundleBarcodeList as $row)    
                  <tr>
                     <td nowrap>{{ $row->bb_code }}</td>
                     <td>{{ $row->bb_date }}</td>
                     <td>{{ $row->task_id }}</td>
                     <td>{{ $row->vpo_code }}</td>
                     <td>{{ $row->sales_order_no }}</td>
                     <td>{{ $row->Ac_name }}</td>
                     <td>{{ $row->mainstyle_name }}</td>
                     <td>{{ $row->substyle_name }}</td>
                     <td>{{ $row->fg_name }}</td>
                     <td>{{ $row->style_no }}</td>
                     <td>{{ $row->total_piece }}</td>
                     <td>{{ $row->narration }}</td>
                     <td>{{ $row->username }}</td>
                     <td>{{ $row->created_at }}</td>
                     <td>{{ $row->updated_at }}</td>
                     @if($chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('BundleBarcode.edit', $row->bb_code)}}" title="Edit">
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
                     <td> 
                        <a class="btn btn-outline-secondary btn-sm print" href="{{route('BundlePrint', ['bb_code'=>$row->bb_code])}}" title="Print">
                        <i class="fas fa-print"></i>
                        </a>
                     </td>
                     @if($chekform->delete_access==1)
                     <td>
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->bb_code }}" 
                           data-route="{{route('BundleBarcode.destroy', $row->bb_code )}}" title="Delete">
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
<script type="text/javascript"> 

     $(document).ready(function() {
        if ( $.fn.DataTable.isDataTable('#dt') ) {
            $('#dt').DataTable().destroy();
            $('#dt').empty(); // remove old table content
        }
    
        $('#dt').DataTable({
            "order": [], // no initial ordering
            "ordering": true, // allow ordering later
            "columnDefs": [
                { "orderable": true, "targets": "_all" } // allow ordering on all columns
            ],
            "initComplete": function(settings, json) {
                // Disable ordering initially by removing the sort classes
                $('#dt thead th').removeClass('sorting sorting_asc sorting_desc');
            }
        });
    });


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