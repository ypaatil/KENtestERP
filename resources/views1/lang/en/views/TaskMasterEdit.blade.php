@extends('layouts.master') 

@section('content')
 
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">

<h4 class="card-title mb-4">Table Task</h4>

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

@if(isset($TaskMasterList))
<form action="{{ route('Task.update',$TaskMasterList) }}" method="POST" enctype="multipart/form-data">
@method('put')

@csrf 
<div class="row">
  
<div class="col-md-2">
    <div class="mb-3">
        <label for="task_date" class="form-label">Task Date</label>
        <input type="date" name="task_date" class="form-control" id="task_date" value="{{date('Y-m-d')}}">
        
         <input type="hidden" name="task_id" class="form-control" id="task_id" value="{{ $TaskMasterList->task_id}}">
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $TaskMasterList->c_code }}">
 <input type="hidden" name="endflag" class="form-control" id="endflag" value="{{ $TaskMasterList->endflag }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>
 
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control" id="vendorId" required  onchange="getVendorPO(this.value);"  >
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}"
     {{ $rowvendor->ac_code == $TaskMasterList->vendorId ? 'selected="selected"' : '' }}
    >{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="job_code" class="form-label">Vendor PO</label>
        
            <select name="vpo_code[]" class="form-select select2" id="vpo_code" required  onChange="getBalanceCutingdata(this.value);" multiple  >
            <option value="">--PO No--</option> 
            @php $vpo_codes = explode(',', $TaskMasterList->vpo_code);   @endphp
            @foreach($VPOrderList as  $row)
            {
              <option value="'{{ $row->vpo_code }}'"
                @if(in_array($row->vpo_code, str_replace("'", '', $vpo_codes))) selected @endif  
                >{{ $row->vpo_code }}</option>
            }
            @endforeach
            </select>
         
    </div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" required disabled>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}"
      {{ $row->mainstyle_id == $TaskMasterList->mainstyle_id ? 'selected="selected"' : '' }} 
    >{{ $row->mainstyle_name }}</option>
}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" required disabled> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
     {{ $row->substyle_id == $TaskMasterList->substyle_id ? 'selected="selected"' : '' }} 
   >{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
     
    
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-control" id="fg_id" required disabled>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    {{ $row->fg_id == $TaskMasterList->fg_id ? 'selected="selected"' : '' }}   
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="{{$TaskMasterList->style_no}}" required readOnly>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="{{$TaskMasterList->style_description}}" required readOnly>

</div>
</div>



<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Table</label>
<select name="table_id" class="form-select" id="table_id" required>
<option value="">--Table--</option>
@foreach($TableList as  $row)
{
    <option value="{{ $row->table_id }}"
    {{ $row->table_id == $TaskMasterList->table_id ? 'selected="selected"' : '' }}   
    >{{ $row->table_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="table_avg" class="form-label">Table Average</label>
        <input type="text" name="table_avg" class="form-control" id="table_avg" value="{{ $TaskMasterList->table_avg }}" required>
    </div>
</div>


<div class="col-md-3">
<div class="mb-3">
<label for="part_id" class="form-label">Part</label>
<select name="part_id" class="form-select" id="part_id" required  onchange="getItemList();">
<option value="">--Select Part--</option>
@foreach($PartList as  $row)
{
    <option value="{{ $row->part_id }}"
    {{ $row->part_id == $TaskMasterList->part_id ? 'selected="selected"' : '' }}       
    >{{ $row->part_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="item_code" class="form-label">Item(Colors)</label>
<select name="item_code[]" class="form-select" id="item_code" onchange="getColorBalanceData(this.value);" multiple>
<option value="">--Item List--</option>
 @php $item_codes = explode(',', $TaskMasterList->item_code);   @endphp
            @foreach($ItemList as  $row)
            {
              <option value="{{ $row->item_code }}"
                @if(in_array($row->item_code, $item_codes)) selected @endif  
                >{{ $row->item_name }})</option>
            }
            @endforeach
</select>
</div>
</div>
 

<div class="col-md-2">
    <div class="mb-3">
        <label for="layers" class="form-label">Total Layers</label>
        <input type="text" name="layers" class="form-control" id="layers" value="{{ $TaskMasterList->layers }}" required>
    </div>
</div>





</div>

<div class="row">
<input type="number" value="{{ count($TaskDetaillist) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />

<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
<th> SrNo</th>
<th>Size</th>
<!--<th>Order Qty</th>-->
<!--<th>Cut Qty</th>-->
<!--<th>Balalnce Qty</th>-->
<th>Ratio</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</thead>
<tbody id="endData">
  
@if(count($TaskDetaillist)>0)

@php $no=1; @endphp
@foreach($TaskDetaillist as $List) 

<tr>
<td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>

<td> <select name="size_id[]"  id="size_id" style="width:100px;" required disabled>
<option value="">--Size--</option>
@foreach($SizeDetailList as  $row)
{
    <option value="{{ $row->size_id }}"
    {{ $row->size_id == $List->size_id ? 'selected="selected"' : '' }}      
    >{{ $row->size_name }}</option>
}
@endforeach
</select></td>
<!--<td><input type="text"     name="order_qty[]" value="{{ $List->order_qty }}" id="order_qty1" style="width:80px;" required /></td>-->
<!--<td><input type="text"     name="cut_qty[]" value="{{ $List->cut_qty }}" id="cut_qty1" style="width:80px;" required /></td>-->
<!--<td><input type="text"     name="balance_qty[]" value="{{ $List->balance_qty }}" id="balance_qty1" style="width:80px;" required /></td>-->
<td><input type="text"     name="ratio[]" value="{{ $List->ratio }}" id="ratio1" style="width:80px;" required /></td>
<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>

</tr>
@php $no=$no+1;  @endphp
@endforeach
 
 @endif
 
 </tbody>
<tfoot>
<tr>
<th>SrNo</th>
<th>Size</th>
<!--<th>Order Qty</th>-->
<!--<th>Cut Qty</th>-->
<!--<th>Balalnce Qty</th>-->
<th>Ratio</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
 
<!-- end row -->
<div class="row">
<div class="col-sm-10">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="{{ $TaskMasterList->narration }}" />
</div>
</div>
 
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md"  onclick="EnableFields();">Submit</button>
<a href="{{ Route('Task.index') }}" class="btn btn-warning w-md">Cancel</a>
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
 
  function getBalanceCutingdata()
{
    
     var vpo_code=$('#vpo_code').val().join("','");
  // var vpo_code=$("#vpo_code").val();
  alert(vpo_code);
  
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('VendorPurchaseOrderDetails') }}",
            data:{'vpo_code':vpo_code},
            success: function(data){
            
            $("#vendorId").val(data[0]['vendorId']);
            $("#season_id").val(data[0]['season_id']);
            $("#Ac_code").val(data[0]['Ac_code']);
            $("#mainstyle_id").val(data[0]['mainstyle_id']);
            $("#substyle_id").val(data[0]['substyle_id']);
            $("#style_no").val(data[0]['style_no']);
            $("#fg_id").val(data[0]['fg_id']);
            $("#style_description").val(data[0]['style_description']);
             document.getElementById('mainstyle_id').disabled=true;
             document.getElementById('substyle_id').disabled=true;
             document.getElementById('fg_id').disabled=true;
             document.getElementById('style_description').disabled=true;
             document.getElementById('style_no').disabled=true;
             document.getElementById('vendorId').disabled=true;
             
             
            //   $.ajax({
            //         dataType: "json",
            //     url: "{{ route('CuttingPOItemList') }}",
            //     data:{'vpo_code':vpo_code,'part_id':part_id},
            //     success: function(data){
            //     //    console.log(data);
            //      $("#item_code").html(data.html);
            //   }
            //     });
              
        }
    });
}



function getItemList()
{
    var vpo_code=$('#vpo_code').val().join("','");
     var part_id=$('#part_id').val();
     $.ajax({
                    dataType: "json",
                url: "{{ route('CuttingPOItemList') }}",
                data:{'vpo_code':vpo_code,'part_id':part_id},
                success: function(data){
                $("#item_code").html(data.html);
               }
                });
}





function getVendorPO(vendorId)
{
     $.ajax({
                    dataType: "json",
                url: "{{ route('getVendorPO') }}",
                data:{'vendorId':vendorId},
                success: function(data){
                $("#vpo_code").html(data.html);
               }
                });
}


function EnableFields()
{
            document.getElementById('mainstyle_id').disabled=false;
             document.getElementById('substyle_id').disabled=false;
             document.getElementById('fg_id').disabled=false;
             document.getElementById('style_description').disabled=false;
             document.getElementById('style_no').disabled=false;
             document.getElementById('vendorId').disabled=false;
             
             
                $("select").prop('disabled', false);
}


function getColorBalanceData()
{
    var part_id=$('#part_id').val();
    var vpo_code=$('#vpo_code').val().join("','");
    var item_code=$("#item_code").val().join("','");
  
    alert(vpo_code);
    $.ajax({
                type: "GET",
                url: "{{ route('SizeBalanceList') }}",
                data:{'vpo_code':vpo_code,'item_code':item_code,'part_id':part_id},
                success: function(response){
                $("#endData").html(response.html);
                recalcIdcone();
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
t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;
cell1.appendChild(t1);
  
var cell4 = row.insertCell(1);
var t4=document.createElement("select");
var x = $("#sz_code"),
y = x.clone();
y.attr("id","sz_code");
y.attr("name","sz_code[]");
y.width(100);
y.appendTo(cell4);
 
var cell5 = row.insertCell(2);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required="true";
t5.id = "ratio"+indexcone;
t5.name="ratio[]";
 
t5.value="0";
t5.setAttribute("onkeyup", "mycalc();");
cell5.appendChild(t5);

var cell17=row.insertCell(3);
var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell17.appendChild(btnRemove);


var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
recalcIdcone();
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
 

</script>

<!-- end row -->
@endsection