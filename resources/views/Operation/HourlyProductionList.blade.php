@extends('layouts.operationapp')
@section('styles')
<!-- INTERNAL Data table css -->

<link href="{{URL::asset('operation/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('operation/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
<link href="{{URL::asset('operation/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('operation/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('operation/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
@endsection
@section('content')
<!--Page header-->
 <style>
     
         @media (min-width: 768px) {
      .modal-xl {
        width: 90%;
       max-width:1200px;
      }
    }
    
    
   
    
    #stickyHeader {
   position: sticky;
    top: 0;
    z-index: 10;
}

 </style>

<div class="page-header d-lg-flex d-block">
   <div class="page-leftheader">
      <h4 class="page-title">Hourly Production Entry Master List</h4>
   </div>
   @if(session()->has('message'))
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
   @endif
   <div class="page-rightheader ml-md-auto">
      <div class=" btn-list">
         <button  class="btn btn-light" data-toggle="tooltip" data-placement="top" title="E-mail"> <i class="feather feather-mail"></i> </button>
         <button  class="btn btn-light" data-placement="top" data-toggle="tooltip" title="Contact"> <i class="feather feather-phone-call"></i> </button>
         <button  class="btn btn-primary" data-placement="top" data-toggle="tooltip" title="Info"> <i class="feather feather-info"></i> </button>
      </div>
   </div>
</div>
<!--End Page header-->
<!-- Row -->
<div class="row">
   <div class="col-12">
      <!--/div-->
      <!--div-->
      <!--/div-->
      <!--div-->
      <div class="card">

         <div class="card-header border-bottom-0">
            <a href="{{ Route('hourly_production.create') }}"><input type='button' class="btn btn-info mt-2" value='Add New Record' ></a>
         </div>

       <div class="row">
                 <div class="col-md-2 col-lg-2 py-4 ml-4">
                        <div class="form-group">
                        <label class="form-label">Date:</label>
                        <input type="date" id="hourlyEntryDate" value="" class="form-control">
                        </div>
                        </div>
          
                        
                        <div class="col-md-2 col-lg-2 py-4 ml-4">
                        <div class="form-group">
                        <label class="form-label">Line:</label>
                        
                        <select name="dept_id" id="dept_id"  class="form-control" autocomplete="off" data-placeholder="Select" tabindex="26">
                        <option label="Select" value="">Any</option>
                        @foreach($dept_list as $rowDL)
                        <option value="{{ $rowDL->line_id }}">{{ $rowDL->line_name }}</option>
                        @endforeach
                        </select>
                        </div>
                        </div>
                        
                        
                        <div class="col-md-1 col-lg-1 py-4 ml-4">
                        <div class="form-group mt-5">
                        <a id="search" class="btn btn-primary btn-block">Search</a>
                        </div>
                        </div>  
                      <div class="col-md-1 col-lg-1 py-4 ml-4">
                        <div class="form-group mt-5">
                        <a id="clear" class="btn btn-warning btn-block">Clear</a>
                        </div>
                        </div>       
           
           
           
 
           
   <div class="col-12">
      <div class="card">
      <div class="card-body">
            <div class="table-responsive">
               <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="task-list">
                  <thead>
                     <tr>
                         <th class="border-bottom-0">Date</th>
                         <th class="border-bottom-0">Style</th>
                          <th class="border-bottom-0">Unit</th>
                          <th class="border-bottom-0">Line</th>
                           <th class="border-bottom-0">Total Production</th>    
                        <th class="border-bottom-0 text-center">Edit</th>
                        <th class="border-bottom-0 text-center">Delete</th>
                     </tr>
                  </thead>
                  <tbody>
                 
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
         
      </div>
      <!--/div-->
      <!--div-->
      <!--/div-->
      <!--div-->
      <!--div-->
   </div>
</div>
<!-- /Row -->
@endsection('content')
@section('modals')
<!--Change password Modal -->
<div class="modal fade"  id="changepasswordnmodal">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Password</h5>
            <button  class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="form-group">
               <label class="form-label">New Password</label>
               <input type="password" class="form-control" placeholder="password" value="">
            </div>
            <div class="form-group">
               <label class="form-label">Confirm New Password</label>
               <input type="password" class="form-control" placeholder="password" value="">
            </div>
         </div>
         <div class="modal-footer">
            <a href="#" class="btn btn-outline-primary" data-dismiss="modal">Close</a>
            <a href="#" class="btn btn-primary">Confirm</a>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"> <!-- Changed modal-dialog class to modal-xl for extra-large width -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body showDetail">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- End Change password Modal  -->
@endsection('modals')
@section('scripts')
<!-- INTERNAL Data tables -->

<script src="{{URL::asset('operation/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/js/datatables.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>





<script type="text/javascript"> 


    $(document).on("mouseover", 'select', function (event) {
          
            $(this).select2('');
          
      });
      





  $(function () {
      
       load_data();     
      
   function load_data(hourlyEntryDate,dept_id)
{   
      
    var url = window.location.href;  
  	 $('#task-list').DataTable().clear().destroy();
  	 
  	
    
       var table = $('#task-list').DataTable({
        processing: true,
        serverSide: true,
        
           ajax: {
                  url:url,
         		  data:  {hourlyEntryDate:hourlyEntryDate,dept_id:dept_id},
         		  dataSrc: 'data'
               },
      
        // "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        //         $("td:first", nRow).html(iDisplayIndex +1);
        //       return nRow;
        //     },
              "columnDefs": [{
      //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY HH:mm' )
      "render": function(data) {
        return moment(data).isValid() ? moment(data).format('DD-MM-YYYY') : "";
      },
      "targets": 0
    }], 
        columns: [
             {data: 'hourlyEntryDate', name: 'hourlyEntryDate'},
              {data: 'mainstyle_name', name: 'main_style_master_operation.mainstyle_name'},  
             {data: 'sub_company_name', name: 'sub_company_master.sub_company_name'},  
             {data: 'line_name', name: 'line_master.line_name'},
            {data: 'total_production', name: 'total_production'},  
             {data: 'action2', name: 'action2',orderable: false, searchable: false},
             {data: 'action3', name: 'action3',orderable: false, searchable: false},  
             
             
           
        ] 
    });
}
    
            
            $('#search').click(function(e){
            
            e.preventDefault();
            
            var hourlyEntryDate = $("#hourlyEntryDate").val();
            var dept_id=$('#dept_id').val();
           
      
            load_data(hourlyEntryDate,dept_id);
            
            });

          $('#clear').click(function(e){
            
            e.preventDefault();
            
            $("#hourlyEntryDate").val(" ");
            $('#dept_id').val(" ");
      
            load_data();
            
            });
    
    
    
  });







   $(document).ready(function(){
     $(document).on('click','.DeleteRecord',function(e) {
   
        var Route = $(this).attr("data-route");
       var id = $(this).data("id");
       var token = $(this).data("token");
       
       //alert('helo');
   
   
           //pop up
           swal({
               title: "Are you sure?",
               text: "It will permanently deleted !", 
               icon: "warning",
               buttons: true,
               dangerMode: true,
           })
           .then((willDelete) => {
   
        if (willDelete) {
   
     $.ajax({
            url: Route,
            type: "DELETE",
             data: {
             "id": id,
             "_method": 'DELETE',
              "_token": token,
              },
            
            success: function(data){
   
            }
   });
   
   	swal({
   				title: "Success",
   				text: "Daily production has been deleted",
   				icon: "success",
   			});
   
             
   	setTimeout(function() {location.reload()}, 1000);
             } else {
               
             }
   
           });
       });
   });
   
   

</script>
@endsection