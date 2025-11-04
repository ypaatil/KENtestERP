@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
    
    
    
@if(isset($FabricTrimCardMasterList))
<form action="{{route('FabricTrimCard.update',$FabricTrimCardMasterList)}}" method="POST" enctype="multipart/form-data">
@method('put')

@csrf 
    
<h4 class="card-title mb-4">Fabric Trim Card: {{ $FabricTrimCardMasterList->ftc_code }}</h4>
@if ($errors->any())

<div class="col-md-6">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    </div>
@endif
 



<div class="row">

 

<div class="col-md-2">
    <div class="mb-3">
        <label for="ftc_date" class="form-label"> Date</label>
        <input type="date" name="ftc_date" class="form-control" id="ftc_date" value="{{ $FabricTrimCardMasterList->ftc_date }}" required>
    </div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
 <select name="style_no" class="form-select select2" id="style_no" required onchange="getJobCardDetails(2);">
    <option value="">--Style No--</option>
    @foreach($StyleList as  $row)
    {
        <option value="{{ $row->style_no }}"
          {{ $row->style_no == $FabricTrimCardMasterList->style_no ? 'selected="selected"' : '' }}    
        >{{ $row->style_no }}</option>
    }
    @endforeach
    </select>
  
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FabricTrimCardMasterList->c_code }}">
  <input type="hidden" name="ftc_code" class="form-control" id="ftc_code" value="{{ $FabricTrimCardMasterList->ftc_code }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>


<div class="col-md-2">
    <div class="mb-3">
        <label for="job_code" class="form-label">Job Card No</label>
        <!--<input type="text" name="job_code" class="form-control" id="job_code" value="{{ $FabricTrimCardMasterList->job_code }}" onfocusout="getJobCardDetails(this.value);" required>-->
   
<select name="job_code" class="form-select select2 " id="job_code" onchange="getJobCardDetails(1);" required>
<option value="">--Job Code--</option>
@foreach($JobCodeList as  $row)
{
    <option value="{{ $row->job_code }}"
     {{ $row->job_code == $FabricTrimCardMasterList->job_code ? 'selected="selected"' : '' }}
    >{{ $row->job_code }}</option>
}
@endforeach
</select>
   
    </div>
</div>

 
<div class="col-md-1">
<div class="mb-3">
<label for="cp_id" class="form-label">Type</label>
<select name="cp_id" class="form-select" id="cp_id" required>
<option value="">Type</option>
@foreach($CPList as  $row)
{
    <option value="{{ $row->cp_id }}"
    {{ $row->cp_id == $FabricTrimCardMasterList->cp_id ? 'selected="selected"' : '' }}        
    >{{ $row->cp_name }}</option>
}
@endforeach
</select>
</div>
</div>




<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer/Party</label>
<select name="Ac_code" class="form-select" id="Ac_code" required>
<option value="">--Select Buyer--</option>
@foreach($Ledger as  $row)
{
    <option value="{{ $row->ac_code }}"
    {{ $row->ac_code == $FabricTrimCardMasterList->Ac_code ? 'selected="selected"' : '' }}     
    >{{ $row->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Job Style</label>
<select name="fg_id" class="form-select" id="fg_id" required>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    {{ $row->fg_id == $FabricTrimCardMasterList->fg_id ? 'selected="selected"' : '' }}         
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
</div>
 
<div class="row">
     <label class="form-label">Body Fabric</label>
<input type="number" value="{{ count($FabricTrimbCardDetailsList) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
<th>SrNo</th>
<th>Color</th>

<th>Part</th>
<th>Width</th>
<th>Average</th>
<th>Required Meter</th>
<th>Received Meter</th>
<th>Difference Meter</th>
<th>Attach Picture</th>
<th>Preview</th>
 <th>Remark</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody>

@if(count($FabricTrimbCardDetailsList)>0)

@php $no1=1; @endphp
@foreach($FabricTrimbCardDetailsList as $List) 
 
<tr>
<td><input type="text" name="id[]" value="@php echo $no1; @endphp" id="id" style="width:50px;"/></td>
<td> <select name="color_id[]"  id="color_id" style="width:100px;" required>
<option value="">--Color--</option>
@foreach($ColorList as  $row)
 {
    <option value="{{ $row->color_id }}"
    {{ $row->color_id == $List->color_id ? 'selected="selected"' : '' }}   
    >{{ $row->color_name }}</option>
 }
@endforeach
</select></td>

<td> <select name="part_id[]"  id="part_id" style="width:100px;" required>
<option value="">--Part--</option>
@foreach($PartList as  $row)
 { 
    <option value="{{ $row->part_id }}"
    {{ $row->part_id == $List->part_id ? 'selected="selected"' : '' }}       
    >{{ $row->part_name }}</option>
 }
@endforeach
</select></td>
<td><input type="text"  name="width[]" value="{{ $List->width }}" id="width" style="width:80px;" required /></td>
<td><input type="text"  name="average[]" value="{{ $List->average }}" id="average" style="width:80px;" required /></td>

@php
        $job_card_no= $List->job_code;
        $color_id= $List->color_id;
        $array=\App\Http\Controllers\FabricTrimCardMasterController::getColorAverages($job_card_no,$color_id,$List->average,$List->part_id);
        $someArray = json_decode($array);
      
@endphp
 
  
<td><input type="text"  name="required_meter[]" value="{{ $someArray[0]->required_meter}}" id="required_meter" style="width:80px;" required /></td>
<td><input type="text"  name="received_meter[]" value="{{ $someArray[0]->received_meter}}" id="received_meter" style="width:80px;" required /></td>
<td><input type="text"  name="difference_meter[]" value="{{ $someArray[0]->difference_meter}}" id="difference_meter" style="width:80px;" required /></td>
 

<td><input type="file"  name="fabric_image[]" value="" id="fabric_image" style="width:80px;"  value="0" />
<input type="hidden" name="fabric_img_path_empty[]"  id="fabric_img_path_empty" style="width:80px;" value="{{ $List->fabric_image }}" />
</td>

@if($List->fabric_image!='')
<td><a href="{{url('images/'.$List->fabric_image)}}" target="_blank"><img src="{{url('thumbnail/'.$List->fabric_image)}}" alt="Fabric Image" ></a> </td>
@else
<td>No Image </td>
@endif


<td><input type="text"  name="remark[]" value="{{ $List->remark }}"id="remark" style="width:80px;"   /></td>
<td><button type="button" onclick="insertcone(); " class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

@php $no1=$no1+1; @endphp
@endforeach

@else

<tr>
<td><input type="text" name="id" value="1" id="id" style="width:50px;"/></td>
<td> <select name="color_id[]"  id="color_id" style="width:100px;" required>
<option value="">--Color--</option>
@foreach($ColorList as  $row)
 
    <option value="{{ $row->color_id }}">{{ $row->color_name }}</option>
 
@endforeach
</select></td>

<td> <select name="quality_code[]"  id="quality_code" style="width:100px;" required>
<option value="">--Quality--</option>
@foreach($QualityList as  $row)
 
    <option value="{{ $row->quality_code }}">{{ $row->quality_name }}</option>
 
@endforeach
</select></td>
<td> <select name="part_id[]"  id="part_id" style="width:100px;" required>
<option value="">--Part--</option>
@foreach($PartList as  $row)
 
    <option value="{{ $row->part_id }}">{{ $row->part_name }}</option>
 
@endforeach
</select></td>
<td><input type="text"  name="width[]" value="0" id="width" style="width:80px;" required /></td>
<td><input type="text"  name="average[]" value="0" id="average" style="width:80px;" required /></td>
<td><input type="text"  name="required_meter[]" value="0" id="required_meter" style="width:80px;" required /></td>
<td><input type="text"  name="received_meter[]" value="0" id="received_meter" style="width:80px;" required /></td>
<td><input type="text"  name="difference_meter[]" value="0" id="difference_meter" style="width:80px;" required /></td>
<td><input type="file"  name="fabric_image[]" value="0" id="fabric_image" style="width:80px;"  required value="0" />
<input type="hidden" name="fabric_img_path_empty[]"  id="fabric_img_path_empty" style="width:80px;" value="" />
</td>
<td>No Image </td>
<td><input type="text"  name="remark[]" value=" "id="remark" style="width:80px;"   /></td>
<td><button type="button" onclick="insertcone(); " class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>
@endif

 </tbody>
<tfoot>
<tr>
    <th>SrNo</th>
    <th>Color</th>
    <th>Part</th>
    <th>Width</th>
    <th>Average</th>
    <th>Required Meter</th>
    <th>Received Meter</th>
    <th>Difference Meter</th>
    <th>Attach Picture</th>
    <th>Preview</th>
      <th>Remark</th>
    <th>Add/Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
 
</div>
 
 
 
 <div class="row">
     <label class="form-label">Trim Fabric For Body</label>
<input type="number" value="{{ count($FabricTrimbCardMatchDetailsList) }}" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>SrNo</th>
<th>Body Color</th>
<th>Trim Color</th>
<th>Part</th>
<th>Width</th>
<th>Average</th>
<th>Required Meter</th>
<th>Received Meter</th>
<th>Difference Meter</th>
<th>Attach Picture</th>
<th>Preview</th>
 <th>Remark</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody>

@if(count($FabricTrimbCardMatchDetailsList)>0)

@php $no=1; @endphp
@foreach($FabricTrimbCardMatchDetailsList as $TList) 
 
<tr>
<td><input type="text" name="ids[]" value="@php echo $no; @endphp" id="ids" style="width:50px;"/></td>
<td> <select name="body_color_id[]"  id="body_color_id" style="width:100px;" required>
<option value="">--Body Color--</option>
@foreach($ColorList as  $row)
 {
    <option value="{{ $row->color_id }}"
    {{ $row->color_id == $TList->body_color_id ? 'selected="selected"' : '' }}   
    >{{ $row->color_name }}</option>
 }
@endforeach
</select></td>

<td> <select name="trim_color_id[]"  id="trim_color_id" style="width:100px;" required>
<option value="">--Trim Color--</option>
@foreach($ColorList as  $row)
 {
    <option value="{{ $row->color_id }}"
    {{ $row->color_id == $TList->trim_color_id ? 'selected="selected"' : '' }}   
    >{{ $row->color_name }}</option>
 }
@endforeach
</select></td>

<td> <select name="part_ids[]"  id="part_ids" style="width:100px;" required>
<option value="">--Part--</option>
@foreach($PartList as  $row)
 { 
    <option value="{{ $row->part_id }}"
    {{ $row->part_id == $TList->part_id ? 'selected="selected"' : '' }}       
    >{{ $row->part_name }}</option>
 }
@endforeach
</select></td>
<td><input type="text"  name="widths[]" value="{{ $TList->width }}" id="widths" style="width:80px;" required /></td>
<td><input type="text"  name="averages[]" value="{{ $TList->average }}" id="averages" style="width:80px;" required /></td>

@php
        $job_card_no= $TList->job_code;
        $body_color_id= $TList->body_color_id;
        $trim_color_id= $TList->trim_color_id;
        $array=\App\Http\Controllers\FabricTrimCardMasterController::getColorAveragesTrim( $job_card_no,$body_color_id,$trim_color_id,$TList->average,$TList->part_id);
        $someArray = json_decode($array);
      
@endphp
 
  
<td><input type="text"  name="required_meters[]" value="{{ $someArray[0]->required_meter}}" id="required_meters" style="width:80px;" required /></td>
<td><input type="text"  name="received_meters[]" value="{{ $someArray[0]->received_meter}}" id="received_meters" style="width:80px;" required /></td>
<td><input type="text"  name="difference_meters[]" value="{{ $someArray[0]->difference_meter}}" id="difference_meters" style="width:80px;" required /></td>
 

<td><input type="file"  name="fabric_images[]" value="" id="fabric_images" style="width:80px;"  value="0" />
<input type="hidden" name="fabric_img_path_emptys[]"  id="fabric_img_path_emptys" style="width:80px;" value="{{ $TList->fabric_image }}" />
</td>

@if($TList->fabric_image!='')
<td><a href="{{url('images/'.$TList->fabric_image)}}" target="_blank"><img src="{{url('thumbnail/'.$TList->fabric_image)}}" alt="Fabric Image" ></a> </td>
@else
<td>No Image </td>
@endif


<td><input type="text"  name="remarks[]" value="{{ $TList->remark }}"id="remarks" style="width:80px;"   /></td>
<td><button type="button" onclick="insertcone2(); " class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
</tr>

@php $no=$no+1; @endphp

@endforeach

@else

<tr>
<td><input type="text" name="ids" value="1" id="ids" style="width:50px;"/></td>
<td> <select name="body_color_id[]"  id="body_color_id" style="width:100px;" required>
<option value="">--Body Color--</option>
@foreach($ColorList as  $row)
 
    <option value="{{ $row->color_id }}">{{ $row->color_name }}</option>
 
@endforeach
</select></td>

<td> <select name="trim_color_id[]"  id="trim_color_id" style="width:100px;" required>
<option value="">--Trim Color--</option>
@foreach($ColorList as  $row)
 
    <option value="{{ $row->color_id }}">{{ $row->color_name }}</option>
 
@endforeach
</select></td>
 
<td> <select name="part_ids[]"  id="part_ids" style="width:100px;" required>
<option value="">--Part--</option>
@foreach($PartList as  $row)
 
    <option value="{{ $row->part_id }}">{{ $row->part_name }}</option>
 
@endforeach
</select></td>
<td><input type="text"  name="widths[]" value="0" id="widths" style="width:80px;" required /></td>
<td><input type="text"  name="averages[]" value="0" id="averages" style="width:80px;" required /></td>
<td><input type="text"  name="required_meters[]" value="0" id="required_meters" style="width:80px;" required /></td>
<td><input type="text"  name="received_meters[]" value="0" id="received_meters" style="width:80px;" required /></td>
<td><input type="text"  name="difference_meters[]" value="0" id="difference_meters" style="width:80px;" required /></td>
<td><input type="file"  name="fabric_images[]" value="0" id="fabric_images" style="width:80px;"  required value="0" />
<input type="hidden" name="fabric_img_path_emptys[]"  id="fabric_img_path_emptys" style="width:80px;" value="" />
</td>
<td>No Image </td>
<td><input type="text"  name="remarks[]" value="" id="remarks" style="width:80px;"   /></td>
<td><button type="button" onclick="insertcone2(); " class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
</tr>
@endif

 </tbody>
<tfoot>
<tr>
    <th>SrNo</th>
    <th>Body Color</th>
<th>Trim Color</th>
    <th>Part</th>
    <th>Width</th>
    <th>Average</th>
    <th>Required Meter</th>
    <th>Received Meter</th>
    <th>Difference Meter</th>
    <th>Attach Picture</th>
    <th>Preview</th>
      <th>Remark</th>
    <th>Add/Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
 
</div>
 
 
 
 
<div class="row">
<div class="col-sm-10">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="{{ $FabricTrimCardMasterList->job_code }}" />
</div>
</div>
 
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label">Do You want to Send Mail of Updated Fabric Trim Card?</label>
<input type="checkbox" name="SendMail" class="form-check_input" id="SendMail" value="1"  >
</br>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('FabricTrimCard.index') }}" class="btn btn-warning w-md">Cancel</a>
</div>
</div>

</div>

</form>
 
@endif

</div>
<!-- end card body -->
</div>
<!-- end card -->
</div>
<!-- end col -->


<!-- end col -->
</div>
<!-- end row -->

           
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
 

//  function getJobCardDetails()
// {
//     var job_card_no=$("#job_code").val();
//      $.ajax({
//             type: "GET",
//             dataType:"json",
//             url: "{{ route('JobCardDetail') }}",
//             data:{'job_card_no':job_card_no},
//             success: function(data){
//             $("#cp_id").val(data[0]['cp_id']);
//             $("#style_no").val(data[0]['style_no']);
//             $("#Ac_code").val(data[0]['Ac_code']);
//             $("#fg_id").val(data[0]['fg_id']);
//                 $.ajax({
//                     type: "GET",
//                     url: "{{ route('ColorDetails') }}",
//                     data:{'job_card_no':job_card_no},
//                     success: function(response){
//                     $("#endData").prepend(response.html);
//                     recalcIdcone();
//                     }
//                     });
                    
//                       $.ajax({
//                     type: "GET",
//                     url: "{{ route('TrimColorDetails') }}",
//                     data:{'job_card_no':job_card_no},
//                     success: function(response){
//                     $("#endTrimData").prepend(response.html);
//                     recalcIdcone2();
//                     }
//                     });
                    
//         }
//         });
        
// }


function getJobCardDetails(code)
{
     
       if(code==1)
   {
    var job_card_no=$("#job_code").val();
    var style_no='';
    
   }
   else
   {
       var style_no=$("#style_no").val();
       var job_card_no='';
   }
    
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('JobCardDetail') }}",
            data:{'job_card_no':job_card_no, 'style_no':style_no},
            success: function(data){
            
            $("#cp_id").val(data[0]['cp_id']);
            $("#Ac_code").val(data[0]['Ac_code']);
            $("#fg_id").val(data[0]['fg_id']);
           
            $("#job_code").val(data[0]["po_code"]);
            var q=data[0]["po_code"];
            $('#select2-job_code-container').html(q);
            
            $("#style_no").val(data[0]["style_no"]);
            var q=data[0]["style_no"];
            $('#select2-style_no-container').html(q);  
                
            $.ajax({
                    type: "GET",
                    url: "{{ route('ColorDetails') }}",
                    data:{'job_card_no':data[0]["po_code"]},
                    success: function(response){
                    $("#endData").prepend(response.html);
                    recalcIdcone();
                    }
                    });
                    
                      $.ajax({
                    type: "GET",
                    url: "{{ route('TrimColorDetails') }}",
                    data:{'job_card_no':data[0]["po_code"]},
                    success: function(response){
                    $("#endTrimData").prepend(response.html);
                    recalcIdcone2();
                    }
                    });
                   
        }
        });
}

 

 $("table.footable_3").on('change', 'input[name^="average"]', function (event)   
	{ 
         
    var row=$(this).closest("tr");
    var job_card_no=$("#job_code").val();
    var color_id=+row.find('select[name^="color_id"]').val();
    var average=+row.find('input[name^="average"]').val();
    var part_id=+row.find('select[name^="part_id"]').val();
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('Average') }}",
            data:{'job_card_no':job_card_no,'color_id':color_id,'average':average, 'part_id':part_id},
            success: function(data){
                
                row.find('input[name^="required_meter[]"]').val(parseFloat(data[0]['required_meter']));
                row.find('input[name^="received_meter[]"]').val(parseFloat(data[0]['received_meter']));
                row.find('input[name^="difference_meter[]"]').val(parseFloat(data[0]['difference_meter']));

           
        }
        });
    });



$("table.footable_2").on('change', 'input[name^="averages"]', function (event)   
	{ 
         
    var row=$(this).closest("tr");
    var job_card_no=$("#job_code").val();
    var body_color_id=+row.find('select[name^="body_color_id"]').val();
    var trim_color_id=+row.find('select[name^="trim_color_id"]').val();
    var average=+row.find('input[name^="averages"]').val();
    var part_id=+row.find('select[name^="part_ids"]').val();
    
//alert(job_card_no+' '+body_color_id+' '+trim_color_id+' '+average );
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('AverageTrim') }}",
            data:{'job_card_no':job_card_no,'body_color_id':body_color_id,'trim_color_id':trim_color_id,'average':average,'part_id':part_id},
            success: function(data){
                
                row.find('input[name^="required_meters[]"]').val(parseFloat(data[0]['required_meter']).toFixed(2));
                row.find('input[name^="received_meters[]"]').val(parseFloat(data[0]['received_meter']).toFixed(2));
                row.find('input[name^="difference_meters[]"]').val(parseFloat(data[0]['difference_meter']).toFixed(2));

           
        }
        });
    });





    $("table.footable_3").on('change', 'select[name^="color_id"]', function (event)   
	{ 
         
    var row=$(this).closest("tr");
    var job_card_no=$("#job_code").val();
    var color_id=+row.find('select[name^="color_id"]').val();
  	var part_id=+row.find('input[name^="part_id[]"]').val();

    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('Average') }}",
            data:{'job_card_no':job_card_no,'color_id':color_id,'part_id':part_id},
            success: function(data){

                row.find('input[name^="average[]"]').val(parseFloat(data[0]['piece_avg']));
                row.find('input[name^="required_meter[]"]').val(parseFloat(data[0]['required_meter']));
                row.find('input[name^="received_meter[]"]').val(parseFloat(data[0]['received_meter']));
                row.find('input[name^="difference_meter[]"]').val(parseFloat(data[0]['difference_meter']));

           
        }
        });
    });



$(document).on('keyup','input[name^="trim_color_id[]"]', function(event) {  
    
    CalculateRow2($(this).closest("tr"));

});

 
function CalculateRow(row)
	{ 
		var color_id=+row.find('input[name^="body_color_id[]"]').val();
        var job_card_no=$("#job_code").val();
        var part_id=+row.find('input[name^="part_ids[]"]').val();
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('JobCardDetail') }}",
            data:{'job_card_no':job_card_no, 'color_id':color_id,'part_id':part_id},
            success: function(data){
            row.find('input[name^="required_meters[]"]').val(parseFloat(required_meter));  
        }
        });
		  	 
}



 
 $(document).on('keyup','input[name^="color_id[]"]', function(event) {  
    
    CalculateRow($(this).closest("tr"));

});

 
function CalculateRow(row)
	{ 
		var color_id=+row.find('input[name^="color_id[]"]').val();
        var job_card_no=$("#job_code").val();
        var part_id=+row.find('input[name^="part_id[]"]').val();
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('JobCardDetail') }}",
            data:{'job_card_no':job_card_no, 'color_id':color_id,'part_id':part_id},
            success: function(data){
            row.find('input[name^="required_meter[]"]').val(parseFloat(required_meter));  
        }
        });
		  	 
}


var indexcone = 2;
function insertcone(){

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;

cell1.appendChild(t1);
  var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
y.attr("required","required");
y.width(100);
y.appendTo(cell3);
 

 
var cell4 = row.insertCell(2);
var t4=document.createElement("select");
var x = $("#part_id"),
y = x.clone();
y.attr("id","part_id");
y.attr("name","part_id[]");
y.attr("required","required");
y.width(100);
y.appendTo(cell4);
 
 var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "width"+indexcone;
t5.name="width[]";
t5.value="0";
cell5.appendChild(t5);
 
var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "average"+indexcone;
t5.name="average[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(5);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "required_meter"+indexcone;
t5.name="required_meter[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "received_meter"+indexcone;
t5.name="received_meter[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "difference_meter"+indexcone;
t5.name="difference_meter[]";
cell5.appendChild(t5);

var cell6 = row.insertCell(8);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="file";
 
t6.id = "fabric_image"+indexcone;
t6.name="fabric_image[]";
cell6.appendChild(t6);

var t10=document.createElement("input");
t10.style="display: table-cell; width:100px;";
t10.type="hidden";
t10.id = "fabric_img_path_empty"+indexcone;
t10.name="fabric_img_path_empty[]";
t10.value ='';
cell6.appendChild(t10);
 
 
var cell5 = row.insertCell(9);
var t5=document.createElement("h6");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.innerText ="No Image";
cell5.appendChild(t5);
 
 
  var cell5 = row.insertCell(10);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "remark"+indexcone;
t5.name="remark[]";
t5.value="";
cell5.appendChild(t5);
 
 
var cell6=row.insertCell(11);
var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
$("#no_cones").val("");
$("#bags").val("");
$("#fr_weights").val("");

recalcIdcone();
}





// var indexcone2 = 2;
// function insertcone2(){

// var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
// var row=table.insertRow(table.rows.length);

// var cell1=row.insertCell(0);
// var t1=document.createElement("input");
// t1.style="display: table-cell; width:50px;";
// //t1.className="form-control col-sm-1";

// t1.id = "id"+indexcone2;
// t1.name= "id[]";
// t1.value=indexcone2;

// cell1.appendChild(t1);
  
// var cell3 = row.insertCell(1);
// var t3=document.createElement("select");
// var x = $("#body_color_id"),
// y = x.clone();
// y.attr("id","body_color_id");
// y.attr("name","body_color_id[]");
// y.attr("required","required");
// y.width(100);
// y.appendTo(cell3);
 
 
// var cell3 = row.insertCell(2);
// var t3=document.createElement("select");
// var x = $("#trim_color_id"),
// y = x.clone();
// y.attr("id","trim_color_id");
// y.attr("name","trim_color_id[]");
// y.attr("required","required");
// y.width(100);
// y.appendTo(cell3); 
 
 

// var cell4 = row.insertCell(3);
// var t4=document.createElement("select");
// var x = $("#part_id"),
// y = x.clone();
// y.attr("id","part_id");
// y.attr("name","part_id[]");
// y.attr("required","required");
// y.width(100);
// y.appendTo(cell4);
 
//  var cell5 = row.insertCell(4);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="text";
// t5.required=true;
// t5.id = "width"+indexcone2;
// t5.name="width[]";
// t5.value="0";
// cell5.appendChild(t5);
 
// var cell5 = row.insertCell(5);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="text";
// t5.required=true;
// t5.id = "average"+indexcone2;
// t5.name="average[]";
// cell5.appendChild(t5);


// var cell5 = row.insertCell(6);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="text";
// t5.required=true;
// t5.id = "required_meter"+indexcone2;
// t5.name="required_meter[]";
// cell5.appendChild(t5);

// var cell5 = row.insertCell(7);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="text";
// t5.required=true;
// t5.id = "received_meter"+indexcone2;
// t5.name="received_meter[]";
// cell5.appendChild(t5);

// var cell5 = row.insertCell(8);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="text";
// t5.required=true;
// t5.id = "difference_meter"+indexcone2;
// t5.name="difference_meter[]";
// cell5.appendChild(t5);

// var cell6 = row.insertCell(9);
// var t6=document.createElement("input");
// t6.style="display: table-cell; width:80px;";
// t6.type="file";
 
// t6.id = "fabric_image"+indexcone2;
// t6.name="fabric_image[]";
// cell6.appendChild(t6);
 
// var t10=document.createElement("input");
// t10.style="display: table-cell; width:100px;";
// t10.type="hidden";
// t10.id = "fabric_img_path_empty"+indexcone2;
// t10.name="fabric_img_path_empty[]";
// t10.value ='';
// cell6.appendChild(t10);
 
// var cell5 = row.insertCell(10);
// var t5=document.createElement("h6");
// t5.style="display: table-cell; width:80px;";
// t5.type="text";
// t5.required=true;
// t5.innerText ="No Image";
// cell5.appendChild(t5);

//   var cell5 = row.insertCell(11);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="text";
// t5.required=true;
// t5.id = "remark"+indexcone2;
// t5.name="remark[]";
// t5.value="";
// cell5.appendChild(t5);
 
 
 
// var cell6=row.insertCell(12);
// var btnAdd = document.createElement("INPUT");
// btnAdd.id = "Abutton";
// btnAdd.type = "button";
// btnAdd.className="btn btn-warning pull-left";
// btnAdd.value = "+";
// btnAdd.setAttribute("onclick", "insertcone()");
// cell6.appendChild(btnAdd);


// var btnRemove = document.createElement("INPUT");
// btnRemove.id = "Dbutton";
// btnRemove.type = "button";
// btnRemove.className="btn btn-danger pull-left";
// btnRemove.value = "X";
// btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
// cell6.appendChild(btnRemove);

// var w = $(window);
// var row = $('#footable_2').find('tr').eq(indexcone2);

// if (row.length){
// $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
// }

// document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;

// indexcone2++;
// $("#no_cones").val("");
// $("#bags").val("");
// $("#fr_weights").val("");

// recalcIdcone2();
// }








var indexcone2 = 2;
function insertcone2(){

var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone2;
t1.name= "id[]";
t1.value=indexcone2;

cell1.appendChild(t1);
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#body_color_id"),
y = x.clone();
y.attr("id","body_color_id");
y.attr("name","body_color_id[]");
y.attr("required","required");
y.width(100);
y.appendTo(cell3);
 
 
var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#trim_color_id"),
y = x.clone();
y.attr("id","trim_color_id");
y.attr("name","trim_color_id[]");
y.attr("required","required");
y.width(100);
y.appendTo(cell3); 
 
 

var cell4 = row.insertCell(3);
var t4=document.createElement("select");
var x = $("#part_id"),
y = x.clone();
y.attr("id","part_ids");
y.attr("name","part_ids[]");
y.attr("required","required");
y.width(100);
y.appendTo(cell4);
 
 var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "widths"+indexcone2;
t5.name="widths[]";
t5.value="0";
cell5.appendChild(t5);
 
var cell5 = row.insertCell(5);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "averages"+indexcone2;
t5.name="averages[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "required_meters"+indexcone2;
t5.name="required_meters[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "received_meters"+indexcone2;
t5.name="received_meters[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(8);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "difference_meters"+indexcone2;
t5.name="difference_meters[]";
cell5.appendChild(t5);

var cell6 = row.insertCell(9);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="file";
 
t6.id = "fabric_image"+indexcone2;
t6.name="fabric_image[]";
cell6.appendChild(t6);

var t10=document.createElement("input");
t10.style="display: table-cell; width:100px;";
t10.type="hidden";
t10.id = "fabric_img_path_empty"+indexcone2;
t10.name="fabric_img_path_empty[]";
t10.value ='';
cell6.appendChild(t10);
 
    
var cell5 = row.insertCell(10);
var t5=document.createElement("h6");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.innerText ="No Image";
cell5.appendChild(t5);

  var cell5 = row.insertCell(11);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "remarks"+indexcone2;
t5.name="remarks[]";
t5.value="";
cell5.appendChild(t5);
 
 
 
var cell6=row.insertCell(12);
var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone2()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_2').find('tr').eq(indexcone2);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;

indexcone2++;
$("#no_cones").val("");
$("#bags").val("");
$("#fr_weights").val("");

recalcIdcone2();
}


function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('PRODQTY');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("prod_qty").value = sum1.toFixed(2);
}


function calculateamount()
{
    
    
var prod_qty=document.getElementById('prod_qty').value;
var rate_per_piece=document.getElementById('rate_per_piece').value;


var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
$('#total_amount').val(total_amount.toFixed(2));
}






function deleteRowcone(btn) {
if(document.getElementById('cntrr').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;

recalcIdcone();

if($("#cntrr").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}



function recalcIdcone(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}




function deleteRowcone2(btn) {
if(document.getElementById('cntrr2').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr2').value = document.getElementById('cntrr2').value-1;

recalcIdcone();

if($("#cntrr2").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}

function recalcIdcone2(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}

function getTotal()
{

}



</script>

<!-- end row -->
@endsection