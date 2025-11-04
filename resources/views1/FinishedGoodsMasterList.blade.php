@extends('layouts.master') 
@section('content')   
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Data Tables</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Finished Goods List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('FinishedGood.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                     <th>ID</th>
                     <th>Main Style</th>
                     <th>Sub Style</th>
                     <th>Style Name</th>
                     <th>Average Meter</th>
                     <th>Username</th>
                     <th class="text-center">Risk Assessment</th>
                     <th class="text-center">Style Feasibility</th>
                     <th class="text-center">Skill Mapping</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($FGList as $row)
                  @php
                    $riskPath = 'https://ken.korbofx.com/uploads/FGImages/'.$row->risk_assessment;
                    $stylePath = 'https://ken.korbofx.com/uploads/FGImages/'.$row->style_feasibility;
                    $skillPath = 'https://ken.korbofx.com/uploads/FGImages/'.$row->skill_mapping;
                    
                    
                    $riskPath1 = 'uploads/FGImages/'.$row->risk_assessment;
                    $stylePath1 = 'uploads/FGImages/'.$row->style_feasibility;
                    $skillPath1 = 'uploads/FGImages/'.$row->skill_mapping;
                  @endphp
                  <tr>
                     <td>{{ $row->fg_id }}</td>
                     <td>{{ $row->mainstyle_name }}</td>
                     <td>{{ $row->substyle_name }}</td>
                     <td>{{ $row->fg_name }}</td>
                     <td>{{ $row->avg_mtr }}</td>
                     <td>{{ $row->username }}</td>
                     <td><input type="file" name="risk_assessment" class="risk" id="ra_{{$row->fg_id}}" onchange="fileUpload(this);" />@php if($row->risk_assessment != ""){ @endphp<img src="{{$riskPath}}" ondblclick="upImages(this)" data-token="{{ csrf_token() }}" data-id="{{ $row->fg_id }}-{{$riskPath1}}"  data-route="{{route('deleteFGImage', $row->fg_id )}}"  alt="{{$row->fg_id}}" width="30" height="30"/>@php } @endphp</td>
                     <td><input type="file" name="style_feasibility" class="style" id="sf_{{$row->fg_id}}"  onchange="fileUpload(this);"/>@php if($row->style_feasibility != ""){ @endphp<img src="{{$stylePath}}" ondblclick="upImages(this)"  data-token="{{ csrf_token() }}" data-id="{{ $row->fg_id }}-{{$stylePath1}}"  data-route="{{route('deleteFGImage', $row->fg_id )}}"  alt="{{$row->fg_id}}" width="30" height="30"/>@php } @endphp</td>
                     <td><input type="file" name="skill_mapping" class="skill" id="sm_{{$row->fg_id}}"  onchange="fileUpload(this);"/>@php if($row->skill_mapping != ""){ @endphp<img src="{{$skillPath}}" ondblclick="upImages(this)"  data-token="{{ csrf_token() }}" data-id="{{ $row->fg_id }}-{{$skillPath1}}"  data-route="{{route('deleteFGImage', $row->fg_id )}}"  alt="{{$row->fg_id}}" width="30" height="30"/>@php } @endphp</td>
                     @if($chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('FinishedGood.edit', $row->fg_id)}}" title="Edit">
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
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->fg_id }}"  data-route="{{route('FinishedGood.destroy', $row->fg_id )}}" title="Delete">
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
     
     

    function upImages(row)
    {
        
        var Route = $(row).attr("data-route");
        var id = $(row).data("id");
        var token = $(row).data("token");
       
        if (confirm("Are you sure you want to Delete this Record?") == true) 
        {
            $.ajax({
                   url: Route,
                   type: "DELETE",
                    data: {
                    "id": id,
                    "_method": 'DELETE',
                     "_token": token,
                     },
                   
                   success: function(data)
                   {
                        $(row).attr('src','');
                   }
            });
       }
    }

   var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

   function fileUpload(row)
   {
      var str = $(row).attr('id').split("_");
      var fg_id = str[1];
      var files = $(row)[0].files;
      var type = $(row).attr('class');
      
      if(files.length > 0)
      {
         var fd = new FormData();
         // Append data 
         fd.append('file',files[0]);
         fd.append('_token',CSRF_TOKEN);
         fd.append('fg_id',fg_id);
         fd.append('type',type);

         console.log(fd);
         // AJAX request 
         $.ajax({
           url: "{{route('uploadFileForFG')}}",
           method: 'post',
           data: fd,
           contentType: false,
           processData: false,
           dataType: 'json',
           success: function(response)
           {
               var imagpath =  $(row).parent().find('img')[0];
               console.log($(row).parent().find('img')[0]);
               console.log(response.path);
               $(imagpath).attr('src',response.path);
           
           },
           error: function(response){
              var src = JSON.stringify(response) 
           }
         });
      }
      else{
         alert("Please select a file.");
      }

    //}
    
        // var filepath = $(row).val()
        // var up_file = filepath[2];
        // var str = $(row).attr('id').split("_");
        // var fg_id = str[1];
        
        // $.ajax({
        //     type: "POST",
        //     dataType: 'JSON',
        //     contentType: false,
        //     cache: false,
        //     processData: false,
        //     url: "{{ route('uploadFileForFG') }}",
        //     data: {'up_file':filepath,'fg_id':fg_id}, 
        //     success: function(data)
        //     {
            
        //     }
        // });
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