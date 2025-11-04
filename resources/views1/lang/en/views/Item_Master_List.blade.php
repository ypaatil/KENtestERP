      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                            <li class="breadcrumb-item active">Item List</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
  @if($chekform->write_access==1)
        <div class="row">
      <div class="col-lg-6">
    <a href="{{ Route('Item.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a> &nbsp; &nbsp; &nbsp;
 
<a href="list/{{ 0 }}"><button type="button" class="btn btn-success w-md float-right">Active</button></a>&nbsp; &nbsp; &nbsp;
 
<a href="list/{{ 1 }}"><button type="button" class="btn btn-danger w-md float-right">InActive</button></a>
</div>  
        
   <div class="col-md-3">
 <form action="{{ route('itemimport') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="itemfile" class="form-control">
                <br>
                <button class="btn btn-success">Import Item Data</button>
           
            </form>
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
                                                <th>Code</th>
                                                <th>Category Name</th>
                                                <th>Classification</th>
                                                <th>Item Name</th>
                                                <th>Item Description</th>
                                                <th>Item Rate</th>
                                                <th>Item MRP</th>
                                                <th>CGST %</th>
                                                <th>SGST %</th>
                                                <th>IGST %</th>
                                                <th>HSN Code</th>
                                                <th>Purchase Rate</th>
                                                <th>Sale Rate</th>
                                                <th>Preview</th>
                                                <th>Username</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($data as $row)    
                                            <tr>
                                                
                                                <th>{{ $row->item_code }}</th>
                                                <td>{{ $row->cat_name }}</td>
                                                <td>{{ $row->class_name }}</td>
                                                <td>{{ $row->item_name }}</td>
                                                <td>{{ $row->item_description }}</td>
                                                <td>{{ $row->item_rate }}</td>
                                                <td>{{ $row->item_mrp }}</td>
                                                <td>{{ $row->cgst_per }}</td>
                                                <td>{{ $row->sgst_per }}</td>
                                                <td>{{ $row->igst_per }}</td>
                                                <td>{{ $row->hsn_code }}</td>  
                                                <td>{{ $row->pur_rate }}</td>  
                                                <td>{{ $row->sale_rate }}</td>  
                                                @if($row->item_image_path!='')
                                                <td><a href="{{url('images/'.$row->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$row->item_image_path)}}" alt="{{ $row->style_no }}"></a></td>
                                                  @else
                                                   <td>No Image</td>
                                                 @endif
                                                <td>{{ $row->username }}</td>
                                                @if($chekform->edit_access==1)
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Item.edit', $row->item_code )}}" title="Edit">
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
  <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->item_code }}"  data-route="{{route('Item.destroy', $row->item_code )}}" title="Delete">
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