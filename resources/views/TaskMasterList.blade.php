      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Table Task</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                                            <li class="breadcrumb-item active">Table Task</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('Task.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a> 
                        </div>
                        </div>
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
                                                <th>Sr No</th>
                                                <th>Task</th>
                                                <th>Task Date</th>
                                                 <th>Cutting PO</th>
                                                 <th>Sales Order No</th>
                                                <th>Vendor Name</th>
                                                <th>Main Style </th>
                                                <th>Sub Style </th>
                                                <th>Style/Design No</th>
                                                <th>Table</th>
                                                <th>Table Average</th>
                                                <th>Layers</th>
                                                
                                                
                                                <th>Edit</th>     
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                           <tbody>

                                            @foreach($TaskList as $row)    
                                            
                                             @php 
                                            // DB::enableQueryLog();
                                             $List = DB::select("SELECT sales_order_no from vendor_purchase_order_master
                                              where vendor_purchase_order_master.vpo_code='".$row->vpo_code."'");
                                         // dd(DB::getQueryLog());
                                          
                                            @endphp
                                            
                                            <tr>
                                                <td> {{ substr($row->task_id,4,10) }} </td>
                                                <td> {{ $row->task_id  }} </td>
                                                <td> {{ $row->task_date  }} </td>
                                                <td> {{ $row->vpo_code  }} </td>
                                                <td> {{ isset($List[0]->sales_order_no) ? $List[0]->sales_order_no:0   }} </td>
                                                <td> {{ $row->Ac_name  }} </td>
                                                 <td> {{ $row->mainstyle_name  }} </td>
                                                  <td> {{ $row->substyle_name  }} </td>
                                                <td> {{ $row->style_no  }} </td>
                                                 
                                                <td> {{ $row->table_id  }} </td>
                                                <td> {{ $row->table_avg  }} </td>
                                                <td> {{ $row->layers  }} </td>
                                                 
                                               
                                                 <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Task.edit', $row->task_id)}}" title="Edit">
                                                                <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                </td>
                                                <td>
                                              <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->task_id }}"  data-route="{{route('Task.destroy', $row->task_id)}}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                            </button>        
                                                
                                                
                                                
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