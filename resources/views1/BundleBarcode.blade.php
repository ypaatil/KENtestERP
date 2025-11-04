@extends('layouts.master') 

@section('content')
 

<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Bundle Barcode</h4>
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
 
<form action="{{route('BundleBarcode.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="bb_date" class="form-label">In Date</label>
        <input type="date" name="bb_date" class="form-control" id="bb_date" value="{{date('Y-m-d')}}" required>
        
@foreach($counter_number as  $row)
     <input type="hidden" name="bb_code" class="form-control" id="bb_code" value="{{ 'BB'.'-'.$row->tr_no }}">
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
@endforeach
 
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
        
        
    </div>
</div>
 

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Task List</label>
<select name="task_id" class="form-select" id="task_id" required onchange="getCheckMasterData(this.value)">
<option value="">--Task--</option>
 @foreach($TaskList as  $rowtask)
{
    <option value="{{ $rowtask->task_id }}"
   @php if(isset($task_id)){ $rowtask->task_id == $task_id ? 'selected="selected"' : ''; } @endphp   
    >{{ $rowtask->task_id }}</option>
}
@endforeach
</select>
</div>
</div> 



<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control" id="vendorId" required  onchange="getVendorPO(this.value);">
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" required> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
   >{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
     
    
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-control" id="fg_id" required>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="" required>
<input type="hidden" name="vpo_code" class="form-control" id="vpo_code" value="" required>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="" required>

</div>
</div>
 
 
</div>
<div class="row">
   
<input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>Roll No</th>
<th>Color</th>
<th>Meter</th>
<th>Bal Meter</th>
<th>Total Pieces</th>
<th>Pieces</th>
<th>Size</th>
<th>TrackCode</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
  
 </tbody>
<tfoot>
<tr>
<th>Roll No</th>
<th>Color</th>
<th>Meter</th>
<th>Bal Meter</th>
<th>Total Pieces</th>
<th>Pieces</th>
<th>Size</th>
<th>TrackCode</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
 
</div>

<div class="row">
  <div class="col-md-2">
    <div class="mb-3">
        <label for="total_piece" class="form-label">Total Piece</label>
        <input type="number"  name="total_piece" class="form-control" id="total_piece" value="0">
    </div>
</div>
 
<div class="col-sm-8">
    <div class="mb-3">
        <label for="narration" class="form-label">Narration</label>
        <input type="text" name="narration" class="form-control" id="narration"  value=""   />
    </div>
</div>
</div>

<div class="row">
    
<div class="col-sm-6">
    <div class="mb-3">
        <label for="jpart_id" class="form-label">For Which Job Parts Do You want Barcode Print..?</label>
       <select name="jpart_id[]" class="form-select" id="jpart_id" required multiple>
        <option value="0">--All Part--</option>
        @foreach($JobPartList as  $row)
        {
            <option value="{{ $row->srNo }}">{{ $row->jpart_name }}</option>
        }
        @endforeach
        </select>
    </div>
</div>
    
    
<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="setFinalSizeArray(); EnableFields();">Submit</button>
<a href="{{ Route('BundleBarcode.index') }}" class="btn btn-warning w-md">Cancel</a>
</div>
</div>


</div>
</form>
 


</div>
<!-- end card body -->
</div>
<!-- end card -->
</div>
<!-- end col -->


<!-- end col -->
</div>
<!-- end row -->

 <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
 
 $(document).on('keyup','input[name^="layers[]"]', function(event) {  
    
    mycalc();

});



function EnableFields()
{
             
             document.getElementById('mainstyle_id').disabled=false;
             document.getElementById('substyle_id').disabled=false;
             document.getElementById('fg_id').disabled=false;
             document.getElementById('style_description').disabled=false;
             document.getElementById('style_no').disabled=false;
                document.getElementById('vendorId').disabled=false;
}





function getDetails(str)
{
    var job_code=$("#job_code").val();
     
    $.ajax({
        dataType: "json",
    url: "{{ route('BundleList') }}",
    data:{'task_id':str,'job_code':job_code},
    success: function(data){
    $("#footable_2").html(data.html);
    
     mycalc();
    }
    });
}


function getCheckMasterData(table_task_code)
{ 
$.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('CheckingMasterList') }}",
            //data:'table_id='+table_id,
            data:{table_task_code:table_task_code},
            success: function(data){
                
            console.log(data);     
              $("#table_avg").val(data[0]['table_avg']);
           $("#vendorId").val(data[0]['vendorId']);
           
            $("#Ac_code").val(data[0]['Ac_code']);
            $("#mainstyle_id").val(data[0]['mainstyle_id']);
            $("#substyle_id").val(data[0]['substyle_id']);
            $("#style_no").val(data[0]['style_no']);
            $("#fg_id").val(data[0]['fg_id']);
            $("#vpo_code").val(data[0]['vpo_code']);
            $("#style_description").val(data[0]['style_description']);
             document.getElementById('mainstyle_id').disabled=true;
             document.getElementById('substyle_id').disabled=true;
             document.getElementById('fg_id').disabled=true;
             document.getElementById('style_description').disabled=true;
             document.getElementById('style_no').disabled=true;
             document.getElementById('vendorId').disabled=true;
          getDetails(table_task_code);
          
          
             $.ajax({
        dataType: "json",
    type: "GET",
    url: "{{ route('GetJobPartList') }}",
    data:{'fg_id':data[0]['fg_id']},
    success: function(data){
        console.log(data);
    $("#jpart_id").html(data.html);
    }
    });
      
    setTimeout(function() {
        
        
      var sizes=document.getElementById('sz_codes').value; 
      setDynmc(sizes);
      
     setTimeout(function() { var values = [];
       var size_array = sizes.split(',');
      $("#footable_2 tr td  input[name='bundleno[]']").each(function() {
      values.push($(this).val());
      if(values.length==size_array.length)
      {
          // alert(values);
        $(this).closest("tr").find('input[name="bundles[]"]').val(values);
        
            values = [];
      }
       
       
         });
    
     },4000);
      
      alert('Bundle No Arranged Successfully..!!');
      
    }, 4000);
      
     
        
          
          
          
          
        }
        });
    }

$(document).on("click", 'input[name^="add[]"]', function (event) 
{
       
    var track_code= $(this).closest("tr").find('input[name="track_code[]"]').val();
    var sizes= $(this).closest("tr").find('input[name="sz_codes[]"]').val();
    var job_code=$("#job_code").val();
    var task_id=$("#task_id").val();
    rownumber=$(this).closest('td').parent()[0].sectionRowIndex;
     var size_array = sizes.split(',');
 //alert(task_id);
    $.ajax({
    dataType: "json",
    url: "{{ route('BundleSplitList') }}",
    data:{'task_id':task_id, 'track_code':track_code},
    success: function(data){
 
    $('#footable_2 > tbody > tr').eq(rownumber).after(data.html);

     
 setTimeout(
  function() 
  { 
     setDynmc(sizes);
         
  }, 2000);
   
    setTimeout(
  function() 
  { 
     var values = [];
      
      $("#footable_2 tr td  input[name='bundleno[]']").each(function() {
      values.push($(this).val());
      if(values.length==size_array.length)
      {
          // alert(values);
        $(this).closest("tr").find('input[name="bundles[]"]').val(values);
        
            values = [];
      }
       
       
         });
    
 
  }, 2000);
    
    
         
//          $("#footable_2 tr").each(function() {
//   var values = $(this).find("input[name='bundleno[]']").map((i, e) => this.value);
//   $(this).find('input[name="bundles[]"]').val(values.join(","));
// });

           
         
         
          mycalc();
  }
     
     
  
    
    });
    
    });


  
function setDynmc(sizes) 
{
 
    var table = document.getElementById("footable_2");
    var table_len = (table.rows.length) - 1;
    var tr = table.getElementsByTagName("tr");
    var id = 0;
    //var colctr = $('#tbl_taka').columnCount();
    var colctr = $(document).find('tr')[0].cells.length;
    var size_array = sizes.split(',');
  
  var flag=1; // flag=1 means new entry 2 means  edit
   var task_id=$("#task_id").val();
  $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('SessionValue') }}",
        data:{'flag':flag, 'task_id':task_id},
        success: function(data){
        
         var no=data['size_counter'];
         //alert(data['size_counter']);
         
      
             var col_id=7;
             var arraysize='';
             for(let x=1;x<=size_array.length;x++)
             {
               $.each($('#footable_2 tr'),function (i,el){
               $(this).find("td:nth-child("+col_id+") input").val(no); 
               ++no;
               });
               no=no-1;
               col_id=col_id+1;
             }
            
            
            
        }
        });
  
   
     
}

 



// function getRowDetails(element)
// {  
//    var track_code= $(this).closest("tr").find('input[name="track_code[]"]').val();
//     var job_code=$("#job_code").val();
//     var task_id=$("#task_id").val();

// alert(track_code);

//     $.ajax({
//         dataType: "json",
//     url: "{{ route('BundleSplitList') }}",
//     data:{'task_id':task_id,'job_code':job_code,'track_code':track_code},
//     success: function(data){
 
//     $('#footable_2 > tbody > tr').eq(row-1).after(data.html);

//     }
//     });
// }



$(document).ready(function(){

//   var job_code=document.getElementById('job_code').value;  
  var task_id=document.getElementById('task_id').value;  
  

//getMasterdata(job_code);

//  $.ajax({
//         dataType: "json",
//     url: "{{ route('BundleList') }}",
//     data:{'task_id':task_id,'job_code':job_code},
//     success: function(data){
//     $("#footable_2").html(data.html);
    
//      mycalc();
//     }
//     });



});


function getMasterdata(job_code)
{
     
$.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('InwardMasterList') }}",
        data:'job_code='+job_code,
        success: function(data){
            
       $("#vendorId").val(data[0]['Ac_code']);
       $("#gp_no").val(data[0]['gp_no']);
       $("#fg_id").val(data[0]['fg_id']);
       $("#style_no").val(data[0]['style_no']);
       $("#total_taga_qty").val(data[0]['total_taga_qty']);
       $("#total_meter").val(data[0]['total_meter']);
      
      
    $.ajax({
        dataType: "json",
    type: "GET",
    url: "{{ route('GetJobPartList') }}",
    data:{'fg_id':data[0]['fg_id']},
    success: function(data){
        console.log(data);
    $("#jpart_id").html(data.html);
    }
    });
      
    setTimeout(function() {
        
        
      var sizes=document.getElementById('sz_codes').value; 
      setDynmc(sizes);
      
     setTimeout(function() { var values = [];
       var size_array = sizes.split(',');
      $("#footable_2 tr td  input[name='bundleno[]']").each(function() {
      values.push($(this).val());
      if(values.length==size_array.length)
      {
          // alert(values);
        $(this).closest("tr").find('input[name="bundles[]"]').val(values);
        
            values = [];
      }
       
       
         });
    
     },2000);
      
      alert('Bundle No Arranged Successfully..!!');
      
    }, 2000);
      
     
        }
        });
    }


function setFinalSizeArray()
{
     var sizes=document.getElementById('sz_codes').value; 
      setDynmc(sizes);
      
     var values = [];
       var size_array = sizes.split(',');
      $("#footable_2 tr td  input[name='bundleno[]']").each(function() {
      values.push($(this).val());
      if(values.length==size_array.length)
      {
          // alert(values);
        $(this).closest("tr").find('input[name="bundles[]"]').val(values);
        
            values = [];
      }
       
           });
 

}

var indexcone = 2;
function insertcone(){

var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;

cell1.appendChild(t1);
  

var cell5 = row.insertCell(1);
var t5=document.createElement("select");
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
y.width(100);
y.appendTo(cell5);



var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#part_id"),
y = x.clone();
y.attr("id","part_id");
y.attr("name","part_id[]");
y.width(100);
y.appendTo(cell3);
 

var cell3 = row.insertCell(3);
var t3=document.createElement("select");
var x = $("#quality_code"),
y = x.clone();
y.attr("id","quality_code");
y.attr("name","quality_code[]");
y.width(100);
y.appendTo(cell3);

var cell6 = row.insertCell(4);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="text";
t6.required="true";
t6.id = "width"+indexcone;
t6.name="width[]";
t6.onkeyup=mycalc();
t6.value="0";
cell6.appendChild(t6);
 
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px;";
t7.type="hidden";
t7.className="TAGAQTY";
t7.required="true";
t7.id = "taga_qty"+indexcone;
t7.name="taga_qty[]";
t7.onkeyup=mycalc();
t7.value="1";
cell6.appendChild(t7);


var cell7 = row.insertCell(5);
var t8=document.createElement("input");
t8.style="display: table-cell; width:80px;";
t8.type="text";
t8.id = "oldmeter"+indexcone;
t8.name="oldmeter[]";
t8.onkeyup=mycalc();
cell7.appendChild(t8);

var cell9 = row.insertCell(6);
var t9=document.createElement("input");
t9.style="display: table-cell; width:80px;";
t9.type="text";
t9.className="METER";
t9.id = "meter"+indexcone;
t9.name="meter[]";
t9.onkeyup=mycalc();
cell9.appendChild(t9);

var cell3 = row.insertCell(7);
var t3=document.createElement("select");
var x = $("#shade_id"),
y = x.clone();
y.attr("id","shade_id");
y.attr("name","shade_id[]");
y.width(100);
y.appendTo(cell3);

var cell10 = row.insertCell(8);
var t10=document.createElement("input");
t10.style="display: table-cell; width:80px;";
t10.type="text";
t10.id = "track_code"+indexcone;
t10.name="track_code[]";
cell10.appendChild(t10);
  

var cell11=row.insertCell(9);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone()");
cell11.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell11.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
mycalc();
recalcIdcone();
recalcIdcone2();
}

function mycalc()
{  
  
sum1 = 0.0;
var amounts = document.getElementsByClassName('PIECE');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_piece").value = sum1.toFixed(2);

 
}



function deleteRowcone(btn) {
if(document.getElementById('cntrr').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
mycalc();
recalcIdcone();

if($("#cntrr").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}

function recalcIdcone2(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:nth-child(6) input").val(i); // Simply couse the first "prototype" is not counted in the list

})
}

function recalcIdcone(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}
 
</script>

<!-- end row -->
@endsection