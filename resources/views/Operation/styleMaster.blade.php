@extends('layouts.operationapp')

@section('styles')



@endsection

@section('content')

						<!--Page header-->
		
						<!--End Page header-->

						<!-- Row -->

@if(isset($stylefetch))
				<form action="{{ route('Style.update',$stylefetch) }}" method="POST" id="updateform">

				@method('put')

@csrf 		
						<div class="row">
			
							<div class="col-xl-12 col-lg-12">
								<div class="card">
									<div class="card-body">
										<div class="card-title">Style Master:</div>
										<div class="row">
										<div class="col-md-4">
												<div class="form-group">
													<label class="form-label">Style Name</label>
		<input type="text" name="mainstyle_name" class="form-control" placeholder="Style Name" required="required" value="{{ $stylefetch->mainstyle_name }}">
										<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
												</div>
										</div>
										
										
										<div class="col-md-4">
										    <div class="form-group">
										  	<label class="form-label">Category</label>      
                                            <select name="cat_id"  class="form-control" onchange="getSubStyle(this.value)">
                                            <option value="">--- Select---</option>  
                                            
                                             @foreach($MainStyleList as  $rowCategory)
                                            {
                                            <option value="{{ $rowCategory->mainstyle_id }}"
                                            
                                               {{  $stylefetch->cat_id== $rowCategory->mainstyle_id ? "selected='selected'" : ""; }}  
                                            
                                            >{{ $rowCategory->mainstyle_name }}</option>
                                            }
                                            @endforeach
                                            
                                             </select>
											</div>
											</div>		
											
										  <div class="col-md-4">
										 <div class="form-group">
										<label class="form-label">Sub Category</label>	     
                                        <select name="sub_cat_id"  class="form-control">
                                        <option value="">--- Select---</option>  
                                        @foreach($SubStyleList as $subCategory)
                                        <option value="{{$subCategory->substyle_id}}"
                                        
                                          {{  $stylefetch->sub_cat_id== $subCategory->substyle_id ? "selected='selected'" : ""; }}
                                        
                                        >{{$subCategory->substyle_name}}</option>
                                        @endforeach
                                        </select>
											</div>
											</div>	
										
										</div>									
								
									</div>
									<div class="card-footer text-right">
										<button class="btn  btn-primary" type="submit">Save</button>
										<a href="{{ route('Style.index') }}" class="btn  btn-danger">Cancel</a>
									</div>
								</div>
							</div>
						</div>

</form>


@else

	<form action="{{route('Style.store')}}" method="POST" id="insertform">

			@csrf 				
						<div class="row">
							<div class="col-xl-12 col-lg-12">
								<div class="card">
									<div class="card-body">
										<div class="card-title">Style Master:</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="form-label">Style Name</label>
<input type="text" name="mainstyle_name" class="form-control" placeholder="Style Name"  required="required">
										<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
												</div>
											</div>
											
									    	<div class="col-md-4">
										    <div class="form-group">
										  	<label class="form-label">Category</label>      
                                            <select name="cat_id"  class="form-control" onchange="getSubStyle(this.value)">
                                            <option value="">--- Select---</option>  
                                            @foreach($MainStyleList as  $row)
                                            {
                                            <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                                            }
                                            @endforeach
                                             </select>
											</div>
											</div>		
											
										  <div class="col-md-4">
										 <div class="form-group">
										<label class="form-label">Sub Category</label>	     
                                        <select name="sub_cat_id" id="substyle_id"  class="form-control">
                                        <option value="">--- Select---</option>  
                                      
                                        </select>
											</div>
											</div>		    
										</div>									
									</div>
									<div class="card-footer text-right">
								<button class="btn  btn-primary" type="submit">Save</button>
										<a href="{{ route('Style.index') }}" class="btn  btn-danger">Cancel</a>
									</div>
								</div>
							</div>
						</div>

</form>

@endif


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

   
         $(document).on("mouseover", 'select', function (event) {
          
             $(this).focus();
            $(this).select2('');
          
      });
      
      $(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
  });


       function getSubStyle(val) 
   {	
     
       
       $.ajax({
       type: "GET",
       url: "{{ route('SubStyleList') }}",
       data:'mainstyle_id='+val,
       success: function(data)
       {
           $("#substyle_id").select2("destroy");
           $("#fg_id").select2("destroy");
           $("#fg_id").html("");
           $("#substyle_id").html(data.html);
           $("#substyle_id").select2();
           $("#fg_id").val("");
       }
       });
   }     
</script>


@endsection
