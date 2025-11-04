      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Fabric Cutting Lots</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                                            <li class="breadcrumb-item active">Fabric Cutting Lots</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        
                           @if($chekform->write_access==1)
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('FabricCutting.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
                         <a href="{{ Route('CompletedCutting') }}"><button type="buuton" class="btn btn-success w-md">Completed</button></a>
                         <a href="{{ Route('FabricCutting.index') }}"><button type="buuton" class="btn btn-danger w-md">Pending</button></a>
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
        
                                    <table id="datatable-buttons" class="example table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr>
                                            <th>SrNo</th>
                                                <th>TrCode</th>
                                                <th>TrDate</th>
                                                <th>Vendor</th>
                                                <th>Main Style</th>
                                                <th>Sub Style</th>
                                                <th>Style</th>
                                                <th>Style No</th>
                                                <th>StyleDescription</th>
                                                <th>Table No</th>
                                                 <th>Task No</th>
                                                <th>Table Average</th>
                                                <th>Total Qty</th>
                                                <th>Total Layers</th>
                                                <th>Total Used Meter</th>
                                                <th>Total Cutpiece Meter</th>
                                               <th>Total Damaged Meter</th>
                                                <th>Edit</th>     
                                                <th>Delete</th>
                                                 <th>Add</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($CuttingMasterList as $row)    
                                            <tr>
                                                
                                                <td> {{ substr($row->cu_code,3,10) }} </td>
                                                <td> {{ $row->cu_code  }} </td>
                                                <td> {{ $row->cu_date  }} </td>
                                                <td> {{ $row->Ac_name  }} </td>
                                                <td> {{ $row->mainstyle_name  }} </td>
                                                <td> {{ $row->substyle_name  }} </td>
                                                <td> {{ $row->fg_name  }} </td>
                                                <td> {{ $row->style_no  }} </td>
                                                <td> {{ $row->style_description  }} </td>
                                                <td> {{ $row->table_name  }} </td>
                                                <td> {{ $row->table_task_code  }} </td>
                                                <td> {{ $row->table_avg  }} </td>
                                                <td> {{ $row->total_pieces  }} </td>
                                                <td> {{ $row->total_layers  }} </td>
                                                <td> {{ $row->total_used_meter  }} </td>
                                                <td> {{ $row->total_cutpiece_meter  }} </td>
                                                <td> {{ $row->total_damage_meter  }} </td>
                           
                                                
                                                   @if($chekform->edit_access==1)
                                                 <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('FabricCutting.edit', $row->cu_code)}}" title="Edit">
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
<button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->table_task_code }}" 
data-route="{{route('FabricCutting.destroy',$row->table_task_code )}}" title="Delete">
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
                                                
                                                
             <td>
                               
<a href="AddBundleBarcode/{{ $row->job_code }}/{{ $row->table_task_code }}"><button class="btn btn-outline-secondary btn-sm add" data-toggle="tooltip"  data-placement="top" title="Add">
<i class="fas fa-plus"></i>
</button></a>
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
 
 
//  $(document).ready(function() {
//     $('#datatable-buttons').DataTable( {
//         "order": [[ 16, "desc" ]]
//     } );
// } );
 
 
</script>            
                        @endsection