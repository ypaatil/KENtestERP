<!-- Jquery js-->
<script src="{{URL::asset('operation/assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{URL::asset('operation/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script> 
  $(document).ready(function(){

	@if(session()->has('message'))
	swal({
		title: "Success",
		text: "{{ session()->get('message') }}",
		icon: "success",
	}); 
	@endif

    $('.alert-success').fadeIn().delay(5000).fadeOut();
  });
  
  </script>		
  <!--Footer-->
			<footer class="footer">
				<div class="container">
					<div class="row align-items-center flex-row-reverse">
						<div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
						<a href="#"></a> <a href="#"></a> 
						</div>
					</div>
				</div>
			</footer>
			<!-- End Footer-->
