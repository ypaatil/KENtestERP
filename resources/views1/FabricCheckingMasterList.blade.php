      
@extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Fabric Checking</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                                            <li class="breadcrumb-item active">Fabric Checking</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                          @if($chekform->write_access==1)    
                        
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('FabricChecking.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
        
                                    <table  data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr> <th>Sr No</th>
                                                <th>QC No</th>
                                                <th>Date</th>
                                                
                                                <th>GRN No</th>
                                                <th>PO No</th>
                                                <th>Supplier</th>
                                                <th>Checked meter</th>
                                                <th>Total Taga</th>
                                                
                                                <th>Edit</th>     
                                                <th>Print</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($FabricCheckingList as $row)    
                                            <tr>
                                                <td>{{ substr($row->chk_code,4) }}</td>
                                                <td>{{ $row->chk_code }}</td>
                                                <td>{{ $row->chk_date }}</td>
                                              <td>{{ $row->in_code }}</td>
                                                <td>{{ $row->po_code }}</td>
                                                <td>{{ $row->Ac_name }}</td>
                                                <td>{{ $row->total_meter }}</td>
                                                <td>{{ $row->total_taga_qty }}</td>
                                                
                                                
                                                 @if($chekform->edit_access==1)
                                                 <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('FabricChecking.edit', $row->chk_code)}}" title="Edit">
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
                                                <a class="btn btn-outline-secondary btn-sm print" target="_blank" href="FabricCheckPrint/{{ $row->chk_code }}" title="print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                </td>
                                   
                                   
                                   
                                                
                                                  @if($chekform->delete_access==1)
                                                <td>
                             <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->chk_code }}"  data-route="{{route('FabricChecking.destroy', $row->chk_code )}}" title="Delete">
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