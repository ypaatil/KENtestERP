@extends('layouts.operationapp')

@section('styles')

		<!-- INTERNAL Fancy File Upload css -->





@endsection

@section('content')



@php 

     $mainstyle_id=   $obFetch->mainstyle_id ?? 0;

$styleAssigned = DB::SELECT("SELECT  MAX(sales_order_no) as sales_order_no  FROM assigned_to_orders WHERE mainstyle_id_operation='".$mainstyle_id."'");


           $sales_order_no= $styleAssigned[0]->sales_order_no ?? 0;
     

$Data1 = DB::SELECT("SELECT count(*) as total_count2 FROM daily_production_entry_details WHERE sales_order_no='".$sales_order_no."'");



$Data = DB::SELECT("SELECT count(*) as total_count FROM daily_production_entry_masters WHERE mainstyle_id='".$mainstyle_id."'");


$total_count = isset($Data[0]->total_count) ? $Data[0]->total_count : 0;
$total_count2 = isset($Data1[0]->total_count2) ? $Data1[0]->total_count2 : 0;




if(Session::get('user_type')==1)
{

  $dis = '';
  $samDis='';

} else{

if(Session::get('vendorId')==56)
{


if($total_count > 0 || $total_count2 > 0)
{
  $dis = 'disabled';
 $samDis='';
}
else
{
 $dis ='';
 $samDis='';
}

} else{

  $dis = 'disabled';
  $samDis='disabled';
}
}




@endphp   




 
<!-- Row -->
<form action="@if(isset($obFetch)) {{ route('ob.store',array('id'=>$obFetch->ob_id)) }} @else {{ route('ob.store') }} @endif" method="POST" id="insertform">
   @csrf 				
   <div class="row">
      <div class="col-xl-12 col-lg-12">
         <div class="card">
            <div class="card-body">
               <div class="row">
                               <div class="col-md-3">
               <div class="form-group">
                        <label class="form-label">Style</label>
                          @if(isset($obFetch))
                        <select name="mainstyle_id"  id="mainstyle_id" class="form-control"  onChange="getCatSubCat(this.value);" required {{ $dis }}>
                           @foreach($styleList as $rowStyle)
                           <option value="{{$rowStyle->mainstyle_id }}"
                           
                           {{  $obFetch->mainstyle_id== $rowStyle->mainstyle_id ? "selected='selected'" : ""; }}
                           
                           >{{$rowStyle->mainstyle_name}}</option>
                           @endforeach
                        </select>
                          @else
                       <select name="mainstyle_id"  id="mainstyle_id" class="form-control"  onChange="getCatSubCat(this.value);" required>
                          <option value="">--- Select---</option>  
                           @foreach($styleList as $rowStyle)
                           <option value="{{$rowStyle->mainstyle_id}}">{{$rowStyle->mainstyle_name}}</option>
                           @endforeach
                        </select>
                        @endif
                     </div>
                     <input type="hidden" name="sub_company_id" value="{{ Session::get('vendorId')}}" class="form-control" id="formrow-email-input">
                     <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input"> 
                     
                  </div>      
                   
                  <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Total Sam</label>
                      <input type="text" name="total_sam" id="total_sam" disabled value="{{ $obFetch->total_sam ?? 0 }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div> 
                        <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Total Rate</label>
                 
                         @if(Session::get('vendorId')==115)
                      <input type="text" name="total_rate" id="total_rate" disabled value="{{ $obFetch->total_rate ?? 0 }}" class="form-control" >
                        @endif
                        
                         @if(Session::get('vendorId')==110)
                      <input type="text" name="total_rate3" id="total_rate3" disabled value="{{ $obFetch->total_rate3 ?? 0 }}" class="form-control">
                        @endif
                        
                         @if(Session::get('vendorId')==628)
                      <input type="text" name="total_rate4" id="total_rate4" disabled value="{{ $obFetch->total_rate4 ?? 0 }}" class="form-control" >
                        @endif
                        
                         @if(Session::get('vendorId')==686)
                      <input type="text" name="total_rate5" id="total_rate5" disabled value="{{ $obFetch->total_rate5 ?? 0 }}" class="form-control">
                        @endif
                        
                         @if(Session::get('vendorId')==113)
                      <input type="text" name="total_rate6" id="total_rate6" disabled value="{{ $obFetch->total_rate6 ?? 0 }}" class="form-control">
                        @endif
                      
                     </div>
                  </div> 
                  
               </div>
					
				<div class="row">
					<div class="col-md-12">    
				   <input type="number" value="{{isset($obFetchDetail) ? count($obFetchDetail): 1}}" name="cnt" id="cnt" readonly="" hidden="true"  />
                    <div class="table-wrap">
                       <div class="table-responsive">
                        <table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead>
                              <tr>
                               <th class="text-center">Sr.No.</th>    
                                 <th class="text-center">Operation ID</th>
                                 <th class="text-center">Operation Name</th>  
                                <th class="text-center">Group</th> 
                                 <th class="text-center">Machine Type</th>
                                <th class="text-center">SAM</th>    
                                
                                
                                 @if(Session::get('user_type')==1)
                                <th class="text-center">Rate 2</th>   
                                <th class="text-center">Rate 3</th>   
                                <th class="text-center">Rate 4</th>   
                                <th class="text-center">Rate 5</th>   
                                <th class="text-center">Rate 6</th>   
                                @else
                                 @if(Session::get('vendorId')==115)
                                <th class="text-center">Rate 2</th>   
                                @endif
                                 @if(Session::get('vendorId')==110)
                                   <th class="text-center">Rate 3</th>   
                                 @endif
                                 
                              @if(Session::get('vendorId')==628)
                                   <th class="text-center">Rate 4</th>   
                                 @endif
                                
                               @if(Session::get('vendorId')==686)
                                   <th class="text-center">Rate 5</th>   
                                 @endif
                                   @if(Session::get('vendorId')==113)
                                   <th class="text-center">Rate 6</th>   
                                 @endif
                                 @endif
                                 
                                <th class="text-center">Required Skill Set</th>     
                                 <th class="text-center">Add</th>
                                <th class="text-center">Remove</th>  
                              </tr>
                           </thead>
                           <tbody>
                         
                           
              
                           @php   
                           if(!isset($obFetchDetail)) { @endphp   
                              <tr class="rowcheck">
                                    <td>
                                <input class="form-control" type="text" step="any" name="sr_no[]" disabled  value="1" style="width:50px;">
                            
                                 </td>  
                                 <td>
                            <input class="form-control" type="text" step="any" name="operation_id[]" placeholder="Operation ID" value=""  onChange="trim(this);previousData(this);">
                           <input class="form-control" type="hidden" step="any" name="auto_id[]"  value="0" >
                            
                                 </td>
                                        <td>
                            <input class="form-control" type="text" step="any" name="operation_name[]" placeholder="Operation Name" value="" >
                       
                            
                                 </td>
                             
                                            <td>
                            <select class="form-control  required noSelect2"   required style="width:200px"  name="group_id[]" id="group_id">
                            <option value="">Select The Group</option>
                             @foreach($groupList as $rowGroup)
                           <option value="{{$rowGroup->group_id}}">{{$rowGroup->group_name}}</option>
                           @endforeach
                            </select> 
                              
                                 </td>     
                               <td>
                            <select class="form-control  required noSelect2"  required style="width:180"  name="machine_type_id[]" id="machine_type_id">
                            <option value="">Select The Machine Type</option>
                            @foreach($machineTypeList as $rowMachine)
                           <option value="{{ $rowMachine->machine_type_id }}">{{$rowMachine->machine_type_name}}</option>
                           @endforeach
                            </select>   
                                 </td>        
                                 <td>
                                    <input type="number" step="any" min="0"   class="form-control required SAM" required  name="sam[]" id="sam"  value="0" {{ $samDis }} style="width:100px;">
                                 </td>
                                 
                                 
                                 @if(Session::get('user_type')==1)
                                 
                                  <td>
                                    <input type="number" step="any" min="0" class="form-control required RATE"  name="rate[]" id="rate" value="0" style="width:100px;">
                                    </td>
                                  
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate3[]" id="rate3"  value="0" style="width:100px;">
                                     </td>   
                                   
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate4[]" id="rate4"  value="0" style="width:100px;">
                                     </td>   

                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate5[]" id="rate5"  value="0" style="width:100px;">
                                     </td>   
                                  
                                      <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate6[]" id="rate6"  value="0" style="width:100px;">
                                     </td>   
                                 
                                 @else
                                    @if(Session::get('vendorId')==115)
                                    <td>
                                    <input type="number" step="any" min="0" class="form-control required RATE"  name="rate[]" id="rate" value="0" style="width:100px;">
                                    </td>
                                    @endif
                                    @if(Session::get('vendorId')==110)
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate3[]" id="rate3"  value="0" style="width:100px;">
                                     </td>   
                                     @endif
                                    
                                    @if(Session::get('vendorId')==628)
                                         
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate4[]" id="rate4"  value="0" style="width:100px;">
                                     </td>   
                                     @endif
                                    @if(Session::get('vendorId')==686)

                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate5[]" id="rate5"  value="0" style="width:100px;">
                                     </td>   
                                       @endif
                                   @if(Session::get('vendorId')==113)
                              
                                      <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate6[]" id="rate6"  value="0" style="width:100px;">
                                     </td>   
                                    
                                    @endif
                                  @endif
                                  
                                  <td>
                                    <input type="text" step="any" min="0"   class="form-control"   name="required_skill_set[]" id="required_skill_set"  value="0" style="width:100px;">
                                 </td>  
                       
                                
                                 <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>
                              @php } else { @endphp
  @php $no=1; @endphp
@foreach($obFetchDetail as $row)



 <tr class="rowcheck">
     
                             <td>
                                <input class="form-control" type="text" step="any" name="sr_no[]" disabled  value="{{ $no++ }}" style="width:50px;">
                            
                                 </td>  
                                  <td>
                            <input class="form-control" type="text" step="any" name="operation_id[]" placeholder="Operation ID" value="{{ $row->operation_id }}"  {{ $dis }} onChange="trim(this);previousData(this,this.value);">
                            <input class="form-control" type="hidden" step="any" name="auto_id[]"  value="{{ $row->sr_no }}" >
                            
                                 </td>
                                        <td>
                            <input class="form-control" type="text" step="any" name="operation_name[]" placeholder="Operation Name" value="{{ $row->operation_name }}"  {{ $dis }} onChange="trim(this);">
                       
                            
                                 </td>
                         
                                            <td>
                            <select class="form-control  required noSelect2"  required style="width:200px"   name="group_id[]" id="group_id" {{ $dis }}>
                            <option value="">Select The Group</option>
                             @foreach($groupList as $rowGroup)
                           <option value="{{$rowGroup->group_id}}"
                           
                           {{  $row->group_id== $rowGroup->group_id ? "selected='selected'" : ""; }}    
                           
                           >{{$rowGroup->group_name}}</option>
                           @endforeach
                            </select>  
                                 </td>     
                               <td>
                            <select class="form-control  required noSelect2"  required style="width:180"  name="machine_type_id[]" id="machine_type_id" {{ $dis }}>
                            <option value="">Select The Machine Type</option>
                            @foreach($machineTypeList as $rowMachine)
                           <option value="{{ $rowMachine->machine_type_id }}"
                           
                            {{  $row->machine_type_id== $rowMachine->machine_type_id ? "selected='selected'" : ""; }}    
                           
                           >{{$rowMachine->machine_type_name}}</option>
                           @endforeach
                            </select>   
                                 </td>        
                                 <td>
                                    <input type="number" step="any" min="0"   class="form-control required SAM" required  name="sam[]" id="sam"  value="{{  $row->sam }}" {{ $samDis }} style="width:100px;">
                                 </td>   
                                 
                                  @if(Session::get('user_type')==1)
                                  
                                  <td>
                                    <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate[]" id="rate"  value="{{  $row->rate }}" style="width:100px;">
                                 </td>   
                                
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE3"   name="rate3[]" id="rate3"  value="{{  $row->rate3 }}" style="width:100px;">
                                     </td>   
                                         
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE4"   name="rate4[]" id="rate4"  value="{{  $row->rate4 }}" style="width:100px;">
                                     </td>   

                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE5"   name="rate5[]" id="rate5"  value="{{  $row->rate5 }}" style="width:100px;">
                                     </td>   
                                      <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE6"   name="rate6[]" id="rate6"  value="{{  $row->rate6 }}" style="width:100px;">
                                     </td>     
                                  
                                  @else
                                 
                                   @if(Session::get('vendorId')==115)
                                  <td>
                                    <input type="number" step="any" min="0"   class="form-control required RATE"   name="rate[]" id="rate"  value="{{  $row->rate }}" style="width:100px;">
                                 </td>   
                                  @endif
                                 @if(Session::get('vendorId')==110)
                                    
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE3"   name="rate3[]" id="rate3"  value="{{  $row->rate3 }}" style="width:100px;">
                                     </td>   
                                     @endif
                                    
                                    @if(Session::get('vendorId')==628)
                                         
                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE4"   name="rate4[]" id="rate4"  value="{{  $row->rate4 }}" style="width:100px;">
                                     </td>   
                                     @endif
                                    @if(Session::get('vendorId')==686)

                                       <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE5"   name="rate5[]" id="rate5"  value="{{  $row->rate5 }}" style="width:100px;">
                                     </td>   
                                       @endif
                                   @if(Session::get('vendorId')==113)
                              
                                      <td>
                                     <input type="number" step="any" min="0"   class="form-control required RATE6"   name="rate6[]" id="rate6"  value="{{  $row->rate6 }}" style="width:100px;">
                                     </td>   
                                    @endif
                                  @endif
                                 
                                 
                                  <td> 
                                    <input type="text" step="any" min="0"   class="form-control"   name="required_skill_set[]" id="required_skill_set"  value="{{  $row->required_skill_set }}" style="width:100px;" {{ $dis }}>
                                 </td>  
                              
                                
                                 <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" {{ $samDis }} class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger" data-id="{{ $row->sr_no }}" onclick="deleteRow(this);" value="X" {{ $dis }}>
                                 </td> 
                                 
                              </tr>



@endforeach
@php } @endphp

                           </tbody>
                         </table>
                
                     </div>
                  </div>
                  
                  	  </div>
					  </div>   
					
					
					
					
            </div>
            <div class="modal-footer">
               <a href="{{ route('ob.index') }}" class="btn btn-danger closebtn">Close</a>
               <button class="btn btn-success projectnotify" id="submitBtn">@if(isset($obFetch)) Update @else Save @endif</button>
            </div>
         </div>
      </div>
   </div>
</form>


						<!-- End Row-->

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
			<!-- End Change password Modal  -->

@endsection('modals')



@section('scripts')




  <script>




     $(".footable_2").on("keyup",'input[name^="rate[]"],input[name^="rate3[]"],input[name^="rate4[]"],input[name^="rate5[]"],input[name^="rate6[]"],input[name^="sam[]"]', function (event) {

        mycalc();
        
    });
   


   
  function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('RATE');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}


sum2 = 0.0;
var amountsOD = document.getElementsByClassName('SAM');
//alert("value="+amounts[0].value);
for(var i=0; i<amountsOD .length; i++)
{ 
var a1 = +amountsOD[i].value;
sum2 += parseFloat(a1);
}




sum3 = 0.0;
var amounts3 = document.getElementsByClassName('RATE3');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts3 .length; i++)
{ 
var a3 = +amounts3[i].value;
sum3 += parseFloat(a3);
}

sum4 = 0.0;
var amounts4 = document.getElementsByClassName('RATE4');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts4 .length; i++)
{ 
var a4 = +amounts4[i].value;
sum4 += parseFloat(a4);
}

sum5 = 0.0;
var amounts5 = document.getElementsByClassName('RATE5');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts5 .length; i++)
{ 
var a5 = +amounts5[i].value;
sum5 += parseFloat(a5);
}

sum6 = 0.0;
var amounts6 = document.getElementsByClassName('RATE6');
//alert("value="+amounts6[0].value);
for(var i=0; i<amounts6 .length; i++)
{ 
var a6 = +amounts6[i].value;
sum6 += parseFloat(a6);
}


 @if(Session::get('vendorId')==115)

document.getElementById("total_rate").value = sum1 || 0;

 @endif

 @if(Session::get('vendorId')==110)
document.getElementById("total_rate3").value = sum3 || 0;

 @endif
 
  @if(Session::get('vendorId')==628)
document.getElementById("total_rate4").value = sum4 || 0;

 @endif
 
   @if(Session::get('vendorId')==686)
document.getElementById("total_rate5").value = sum5 || 0;

 @endif
 
   @if(Session::get('vendorId')==113)
document.getElementById("total_rate6").value = sum6 || 0;
 @endif


document.getElementById("total_sam").value = (sum2);


}


    $(document).on("mouseover", 'select', function (event) {
        
    
          
            $(this).select2('');
          
      });
      
      
      
      
      
      
      function getCatSubCat(mainstyle_id){
          

          $('.select2-hidden-accessible').select2('destroy');
  
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_cat_sub_cat_by_style') }}",
   dataType:"json",
   data:{mainstyle_id:mainstyle_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response.cat_id);  
 
      $('#cat_id').val(response.cat_id);
      $('#sub_cat_id').val(response.sub_cat_id);
   
   }
   });      
        
        
    }  
    
    
    
     function deleteRow(btn) {
        
        var RowCount= $('#footable_2 tr').length;
         var row = $(btn).closest('tr');
         
 
            var dataId = $(btn).attr('data-id');
            
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
            url:'{{ route("delete_operation") }}',
            type: "POST",
             data: {
             "dataId": dataId,
              "_token": "{{ csrf_token(); }}"
              },
              
            success: function(data){
                
                   	swal({
   				title: "Success",
   				text: "Operation has been deleted",
   				icon: "success",
   			});
   
            }
   });    
        } else {
               
             }
             
           });
             
             
            
        row.remove();
         mycalc();
         
    }
    
    
    $(function(){
    $("#footable_2").on('click', '.Abutton', function() {
        
      var select2 = $(this).closest("tr").find('select').select2();


if (select2) {
 
$(this).closest("tr").find('select').select2('destroy');
} else {
  
}
   
        
        
       var $tableBody = $('#footable_2').find("tbody"),
        $trLast = $tableBody.find("tr:last"),
        
        $trNew = $trLast.clone();
        
         var currentSrNo = 0;
        $tableBody.find('input[name="sr_no[]"]').each(function() {
            var srNo = parseInt($(this).val(), 10);
            if (srNo > currentSrNo) {
                currentSrNo = srNo;
            }
        });
        
        
        $trNew.find('input,select').prop('disabled',false);

        // Increment the sr_no for the new row
        $trNew.find('input[name="sr_no[]"]').val(currentSrNo + 1);

         	$trNew.find('select[name="machine_type_id[]"]').val("--- Select---");		
          	$trNew.find('input[name="operation_id[]"]').val(" ");	
           	$trNew.find('input[name="operation_name[]"]').val(" ");	
           	$trNew.find('select[name="group_id[]"]').val(" ");
        	$trNew.find('input[name="sam[]"]').val("");
  	        $trNew.find('input[name="required_skill_set[]"]').val("");
            $trNew.find('input[name="auto_id[]"]').val("");
        	
        	
		
    $trLast.after($trNew);
     recalcId();
      mycalc();
    });
    
 
});
    
   function recalcId(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   
   
$('#submitBtn').on("click", function() {
    // Check required fields before submission
    if ($('#mainstyle_id').val()!="") {
        $(".projectnotify").text('Please wait...');
        $('.projectnotify').prop('disabled', true); 
        $('.closebtn').prop('disabled', true); 
        
         $('input,select').prop('disabled', false); 
        
        $("#insertform").submit();
    } else {
        // Show lightbox popup indicating missing fields
        alert('select neccessary fields');
    }
});


function trim(el) {
    
  
    el.value = el.value.
    replace(/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
    replace(/[ ]{2,}/gi, " "). // replaces multiple spaces with one space 
    replace(/\n +/, "\n"); // Removes spaces after newlines
    return;
}





function previousData(obj) {
    let cnt = 0;
    

    // Get the selected employee code and operation from the current row
  
    const selectedOperation = $(obj).closest('tr').find("input[name='operation_id[]']").val();  // The current operation selected in this row 

    // Loop through all the rows and check for duplicate employee code and operation combinations
    $("#footable_2 tr").each(function() {
      
        const operation = $(this).find("input[name='operation_id[]']").val();
        
      

        // Check if both the employee code and operation match the selected ones
        if (selectedOperation === operation) {
            cnt++;
        }
    });

    // If the same combination of employee code and operation is selected more than once
    if (cnt > 1) {
        alert("This combination  operation is already exist, you should enter another.");
        $(obj).val("");  // Clear the currently selected operation
        
   
        $(obj).closest('tr').find("input[name='operation_id[]']").val("");  
  
    }
}

</script>


@endsection
