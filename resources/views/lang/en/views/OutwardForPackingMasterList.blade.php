      
   @extends('layouts.master') 

@section('content')   
   
                        <!-- end page title -->
 
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Outward For Packing</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Outward For Packing</li>
            </ol>
        </div>

    </div>
</div>
</div>

 
                          @if($chekform->write_access==1)    
                        <div class="row">
                        <div class="col-md-12">
                        <a href="{{ Route('OutwardForPacking.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
                        
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
        
                                    <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr>
                                                <th>SrNo</th>
                                                <th>Sales Order No</th>
                                                <th>Entry Date</th>
                                               <th>Buyer Name</th>
                                                 <th>Vendor Name</th> 
                                                 <th>Total Qty</th> 
                                                <th>User</th>     
                                                <th>Print</th>
                                                <th>Edit</th>     
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>
 
                                            @foreach($OutwardForPackingMasterList as $row)    
                                            <tr>
                                                <td > {{ substr($row->ofp_code,4,15)  }} </td>
                                                <td> {{ $row->sales_order_no  }} </td>
                                                <td> {{ $row->ofp_date  }} </td>
                                                <td> {{ $row->Ac_name  }} </td>
                                                <td> {{ $row->vendor_name  }} </td>
                                                 <td> {{ $row->total_qty  }} </td>
                                                <td> {{ $row->username  }} </td>
                                                @if($chekform->edit_access==1)
                                                  
                                                
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm print" href="VPPrint/{{ $row->ofp_code }}" title="print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                </td>
                                                
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('OutwardForPacking.edit', $row->ofp_code)}}" title="Edit">
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
                                                
												<button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->ofp_code }}"  data-route="{{route('OutwardForPacking.destroy', $row->ofp_code )}}" title="Delete">
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