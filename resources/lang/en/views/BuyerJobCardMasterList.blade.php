      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Job Cards</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                                            <li class="breadcrumb-item active">Buyer's Job Cards</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                    @if($chekform->write_access==1)    
                        <div class="row">
                        <div class="col-md-6">
                        <a href="{{ Route('BuyerJobCard.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
        
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr>
                                            
                                                <th>JC No</th>
                                                <th>Date</th>
                                                <th>Buyer Name</th>
                                                <th>Final Product</th> 
                                                <th>Style/Design No</th>
                                                <th>Style Picture</th>
                                                <th>Download1</th> 
                                                <th>Download2</th>
                                               <th>Start Date</th> 
                                                <th>End Date</th> 
                                                <th>Job status</th>
                                                <th>Brand</th>
                                                <th>Season</th>
                                                <th>Total meter</th>
                                                <th>Total Qty</th>
                                                <th>Rate/Piece</th>
                                                <th>Total Amount</th>
                                                <th>Development Sample</th>
                                                <th>Fit Sample</th> 
                                                <th>production Sample</th> 
                                                <th>FPT Sample</th> 
                                                <th>GPT Sample</th>
                                                <th>Sealer</th>
                                                <th>Shipment</th>
                                                <th>Photoshoot</th> 
                                                <th>User</th>
                                                <th>Created At</th>
                                                <th>Updated At</th> 
                                                     <th>Edit</th>     
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($BJobCardList as $row)    
                                            <tr>
                                               
                                                <td>{{ $row->po_code }}</td>
                                                <td>{{ $row->po_date }}</td>
                                                <td>{{ $row->Ac_name }}</td>
                                                <td>{{ $row->fg_name }}</td>
                                                <td>{{ $row->style_no }}</td>
                                                <td><a href="{{url('images/'.$row->style_pic_path)}}" target="_blank"><img src="{{url('thumbnail/'.$row->style_pic_path)}}" alt="{{ $row->style_no }}" ></a></td>
                                                <td><a href="{{ url('uploads/'.$row->doc_path1) }}" target="_blank"> Document-1</a></td>
                                                <td><a href="{{ url('uploads/'.$row->doc_path_2) }}" target="_blank"> Document-2</a></td>
                                                <td>{{ $row->start_date }}</td>
                                                <td>{{ $row->end_date }}</td>
                                                <td>{{ $row->job_status_name }}</td>
                                                <td>{{ $row->brand_name }}</td>
                                                <td>{{ $row->season_name }}</td>
                                                <td>{{ $row->total_meter }}</td>
                                                <td>{{ $row->total_qty }}</td>
                                                <td>{{ $row->rate_per_piece }}</td>
                                                <td>{{ $row->total_amount }}</td>
                                                @php 
                                                if($row->development_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($row->fit_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($row->production_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($row->fpt_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($row->gpt_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($row->sealer==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($row->shipment==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($row->photoshoot==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                @endphp
                                                <td>{{ $row->username }}</td>
                                                <td>{{ $row->created_at }}</td>
                                                <td>{{ $row->updated_at }}</td>
 @if($chekform->edit_access==1)
                                                 <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('BuyerJobCard.edit', $row->po_code)}}" title="Edit">
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
  <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->po_code }}"  data-route="{{route('BuyerJobCard.destroy', $row->po_code )}}" title="Delete">
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