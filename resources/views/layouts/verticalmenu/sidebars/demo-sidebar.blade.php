				<!--aside open-->
				<aside class="app-sidebar">
					<div class="app-sidebar__logo">
						<a class="header-brand" href="{{URL::asset('UserDashboard')}}">
							<img src="{{URL::asset('assets/images/ken_logo.png')}}" class="header-brand-img desktop-lgo" alt="ken_logo.png">
						</a>
					</div>
					<div class="app-sidebar3">
						
						<ul class="side-menu">
							
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-home sidemenu_icon"></i>
									<span class="side-menu__label">Employee Dashboard</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Masters</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('UserTicketRaise')}} ">Ticket Raise List</a></li>
											<li><a class="sub-slide-item" href="{{url('ExitInterview')}} ">Exit Interview</a></li>
											<li><a class="sub-slide-item" href="{{url('QFE')}} ">Query From Employee</a></li>
											<li><a class="sub-slide-item" href="{{url('TCMList')}} ">Training Chapter List</a></li>
											<li><a class="sub-slide-item" href="{{url('UserAssetRequisition')}} ">Asset Requisition List</a></li>	
											
											
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</aside>
				<!--aside closed-->
