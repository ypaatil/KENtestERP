@extends('layouts.operationapp')

@section('styles')

		<!-- INTERNAL Fancy File Upload css -->
		<link href="{{URL::asset('Operation/assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />

		<!-- INTERNAL Time picker css -->
		<link href="{{URL::asset('Operation/assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

		<!-- INTERNAL Date Picker css -->
		<link href="{{URL::asset('Operation/assets/plugins/date-picker/date-picker.css')}}" rel="stylesheet" />

		<!-- INTERNAL File Uploads css-->
        <link href="{{URL::asset('Operation/assets/plugins/fileupload/css/fileupload.css')}}" rel="stylesheet" type="text/css" />

		<!-- INTERNAL Mutipleselect css-->
		<link rel="stylesheet" href="{{URL::asset('Operation/assets/plugins/multipleselect/multiple-select.css')}}">

		<!-- INTERNAL Sumoselect css-->
		<link rel="stylesheet" href="{{URL::asset('Operation/assets/plugins/sumoselect/sumoselect.css')}}">

		<!--INTERNAL IntlTelInput css-->
		<link rel="stylesheet" href="{{URL::asset('Operation/assets/plugins/intl-tel-input-master/intlTelInput.css')}}">

		<!-- INTERNAL Jquerytransfer css-->
		<link rel="stylesheet" href="{{URL::asset('Operation/assets/plugins/jQuerytransfer/jquery.transfer.css')}}">
		<link rel="stylesheet" href="{{URL::asset('Operation/assets/plugins/jQuerytransfer/icon_font/icon_font.css')}}">

		<!-- INTERNAL multi css-->
		<link rel="stylesheet" href="{{URL::asset('Operation/assets/plugins/multi/multi.min.css')}}">

		<!-- INTERNAL Bootstrap DatePicker css-->
		<link rel="stylesheet" href="{{URL::asset('Operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">




@endsection

@section('content')
 
<!-- Row -->
<form action="{{ route('assign_to_order.store') }}" method="POST" id="insertform">
   @csrf 				
   <div class="row">
      <div class="col-xl-12 col-lg-12">
         <div class="card">
            <div class="card-body">
               <div class="row">
                               <div class="col-md-3">
               <div class="form-group">
                        <label class="form-label">Style</label>
                       <select name="mainstyle_id"  id="mainstyle_id" class="form-control"  onChange="getSalesorderNo(this.value)"  required>
                          <option value="">--- Select---</option>  
                           @foreach($styleList as $rowStyle)
                           <option value="{{$rowStyle->mainstyle_id}}">{{$rowStyle->mainstyle_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>      
                   
                        <div class="col-md-3">
               <div class="form-group">
                        <label class="form-label">Sales Order</label>
                       <select name="sales_order_no"  id="sales_order_no"  class="form-control" required>
                        <option value="">--- Select---</option>  
                      
                        </select>
                    
                     </div>
                  </div>       
                   

               </div>
	
				
					
            </div>
            <div class="modal-footer">
               <button class="btn btn-success projectnotify">Save</button>
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
		<script src="{{URL::asset('Operation/assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/plugins/time-picker/toggles.min.js')}}"></script>

		<!-- INTERNAL Datepicker js -->
		<script src="{{URL::asset('Operation/assets/plugins/date-picker/date-picker.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/plugins/date-picker/jquery-ui.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

		<!-- INTERNAL File-Uploads Js-->
		<script src="{{URL::asset('Operation/assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
        <script src="{{URL::asset('Operation/assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
        <script src="{{URL::asset('Operation/assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
        <script src="{{URL::asset('Operation/assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
        <script src="{{URL::asset('Operation/assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>

		<!-- INTERNAL File uploads js -->
        <script src="{{URL::asset('Operation/assets/plugins/fileupload/js/dropify.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/js/filupload.js')}}"></script>

		<!-- INTERNAL Multiple select js -->
		<script src="{{URL::asset('Operation/assets/plugins/multipleselect/multiple-select.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/plugins/multipleselect/multi-select.js')}}"></script>

		<!-- INTERNAL Sumoselect js-->
		<script src="{{URL::asset('Operation/assets/plugins/sumoselect/jquery.sumoselect.js')}}"></script>

		<!-- INTERNAL intlTelInput js-->
		<script src="{{URL::asset('Operation/assets/plugins/intl-tel-input-master/intlTelInput.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/plugins/intl-tel-input-master/country-select.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/plugins/intl-tel-input-master/utils.js')}}"></script>

		<!-- INTERNAL jquery transfer js-->
		<script src="{{URL::asset('Operation/assets/plugins/jQuerytransfer/jquery.transfer.js')}}"></script>

		<!-- INTERNAL multi js-->
		<script src="{{URL::asset('Operation/assets/plugins/multi/multi.min.js')}}"></script>

		<!-- INTERNAL Bootstrap-Datepicker js-->
		<script src="{{URL::asset('Operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

		<!-- INTERNAL Form Advanced Element -->
		<script src="{{URL::asset('Operation/assets/js/formelementadvnced.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/js/form-elements.js')}}"></script>
		<script src="{{URL::asset('Operation/assets/js/select2.js')}}"></script>



  <script>



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

        // Increment the sr_no for the new row
        $trNew.find('input[name="sr_no[]"]').val(currentSrNo + 1);

         	$trNew.find('select[name="machine_type_id[]"]').val("--- Select---");		
          	$trNew.find('input[name="operation_id[]"]').val(" ");	
           	$trNew.find('input[name="operation_name[]"]').val(" ");	
           	$trNew.find('select[name="group_id[]"]').val(" ");
        	$trNew.find('input[name="sam[]"]').val("");
  	       $trNew.find('input[name="required_skill_set[]"]').val("");
 
        	
        	
		
    $trLast.after($trNew);
     recalcId();
    });
    
 
});
    
   function recalcId(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   
   
$('#insertform').on("click", function() {
    // Check required fields before submission
    if (checkRequiredFields()) {
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

    function getSalesorderNo(mainstyle_id)
    {
        
        
    $.ajax({
    type:"POST",
    url:"{{ route('get_sales_order_by_style') }}",
    dataType:"json",
    data:{mainstyle_id:mainstyle_id,"_token":"{{ csrf_token() }}"},
    success:function(response){
    
    $('#sales_order_no').html(response.html);
  
    
    }
    });     
    
    }

</script>


@endsection
