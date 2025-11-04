				<!--aside open-->
				<aside class="app-sidebar">
					<div class="app-sidebar__logo">
						<a class="header-brand" href="{{url('index')}}">
							<img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-lgo" alt="Dayonelogo">
							<img src="{{URL::asset('assets/images/brand/logo-white.png')}}" class="header-brand-img dark-logo" alt="Dayonelogo">
							<img src="{{URL::asset('assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo" alt="Dayonelogo">
							<img src="{{URL::asset('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="Dayonelogo">
						</a>
					</div>
					<div class="app-sidebar3">
						<div class="app-sidebar__user">
							<div class="dropdown user-pro-body text-center">
								<div class="user-pic">
									<img src="{{URL::asset('assets/images/users/16.jpg')}}" alt="user-img" class="avatar-xxl rounded-circle mb-1">
								</div>
								<div class="user-info">
									<h5 class=" mb-2">Abigali kelly</h5>
									<span class="text-muted app-sidebar__user-name text-sm">App Developer</span>
								</div>
							</div>
						</div>
						<ul class="side-menu">
							<li class="side-item side-item-category mt-4">Dashboards</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-home sidemenu_icon"></i>
									<span class="side-menu__label">HR Dashboard</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('index')}} " class="slide-item">Dashboard</a></li>
									<li><a href="{{url('hr-department')}} " class="slide-item">Department</a></li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Employees</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('hr-emplist')}} ">Employees List</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-empview')}} ">View Employee</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-addemployee')}} ">Add Employee</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Attendance</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('hr-attlist')}} ">Attendance List</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-attuser')}} ">Attendance By User</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-attview')}} ">Attendance View</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-overviewcldr')}} ">Overview Calender</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-attmark')}} ">Attendance Mark </a></li>
											<li><a class="sub-slide-item" href="{{url('hr-leaves')}} ">Leave Settings</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-leavesapplication')}} ">Leave Applications</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-recentleaves')}} ">Recent Leaves </a></li>
										</ul>
									</li>
									<li><a href="{{url('hr-award')}} " class="slide-item">Awards</a></li>
									<li><a href="{{url('hr-holiday')}} " class="slide-item">Holidays</a></li>
									<li><a href="{{url('hr-notice')}} " class="slide-item">Notice Board</a></li>
									<li><a href="{{url('hr-expenses')}} " class="slide-item">Expenses</a></li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Payroll</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('hr-empsalary')}} ">Employee Salary</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-addpayroll')}} ">Add Payroll</a></li>
											<li><a class="sub-slide-item" href="{{url('hr-editpayroll')}} ">Edit Payroll</a></li>
										</ul>
									</li>
									<li><a href="{{url('hr-events')}} " class="slide-item">Events</a></li>
									<li><a href="{{url('hr-settings')}} " class="slide-item">Settings</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather  feather-users sidemenu_icon"></i>
									<span class="side-menu__label">Employee Dashboard</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('index2')}} " class="slide-item">Dashboard</a></li>
									<li><a href="{{url('employee-attendance')}} " class="slide-item">Attendance</a></li>
									<li><a href="{{url('employee-leaves')}} " class="slide-item">Apply Leaves </a></li>
									<li><a href="{{url('employee-myleaves')}} " class="slide-item">My Leaves </a></li>
									<li><a href="{{url('employee-payslips')}} " class="slide-item">Payslips </a></li>
									<li><a href="{{url('employee-expenses')}} " class="slide-item">Expenses</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-clipboard sidemenu_icon"></i>
									<span class="side-menu__label">Task Dashboard</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('index3')}} " class="slide-item">Dashboard</a></li>
									<li><a href="{{url('task-list')}} " class="slide-item">Task List</a></li>
									<li><a href="{{url('task-running')}} " class="slide-item">Running Tasks</a></li>
									<li><a href="{{url('task-hold')}} " class="slide-item">OnHold Tasks</a></li>
									<li><a href="{{url('task-complete')}} " class="slide-item">Completed Tasks</a></li>
									<li><a href="{{url('task-view')}} " class="slide-item">View Task</a></li>
									<li><a href="{{url('task-overclndr')}} " class="slide-item">Overview Calendar</a></li>
									<li><a href="{{url('task-board')}} " class="slide-item">Task Board</a></li>
									<li><a href="{{url('task-new')}} " class="slide-item">New Task</a></li>
									<li><a href="{{url('task-profile')}} " class="slide-item">User Profile</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-book sidemenu_icon"></i>
									<span class="side-menu__label">Project Dashboard</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('index4')}} " class="slide-item">Dashboard</a></li>
									<li><a href="{{url('project-list')}} " class="slide-item">Project List</a></li>
									<li><a href="{{url('project-view')}} " class="slide-item">View Project</a></li>
									<li><a href="{{url('project-overclndr')}} " class="slide-item">Overview Calendar</a></li>
									<li><a href="{{url('project-new')}} " class="slide-item">New Project</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-user sidemenu_icon"></i>
									<span class="side-menu__label">Client Dashboard</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('index5')}} " class="slide-item">Dashboard</a></li>
									<li><a href="{{url('client-list')}} " class="slide-item">Client List</a></li>
									<li><a href="{{url('client-view')}} " class="slide-item">View Client</a></li>
									<li><a href="{{url('client-new')}} " class="slide-item">New Client</a></li>
									<li><a href="{{url('client-profile')}} " class="slide-item">User Profile</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-briefcase sidemenu_icon"></i>
									<span class="side-menu__label">Job Dashboard</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('index6')}} " class="slide-item">Dashboard</a></li>
									<li><a href="{{url('job-list')}} " class="slide-item">Job Lists</a></li>
									<li><a href="{{url('job-view')}} " class="slide-item">Job View</a></li>
									<li><a href="{{url('job-applictaion')}} " class="slide-item">Job Applications</a></li>
									<li><a href="{{url('job-apply')}} " class="slide-item">Apply Job</a></li>
									<li><a href="{{url('job-new')}} " class="slide-item">New Job</a></li>
									<li><a href="{{url('job-user')}} " class="slide-item">User Profile</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-headphones sidemenu_icon"></i>
									<span class="side-menu__label">Support System</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Landing Pages</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('support-landing')}} ">Landing Page</a></li>
											<li><a class="sub-slide-item" href="{{url('support-knowledge')}} ">Knowledge Page</a></li>
											<li><a class="sub-slide-item" href="{{url('support-knowledgeview')}} ">Knowledge View</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">User Pages</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('support-userdash')}} ">Dashboard</a></li>
											<li><a class="sub-slide-item" href="{{url('support-editprofile')}} ">Profile</a></li>
											<li class="sub-slide2">
												<a class="sub-side-menu__item2" href="#" data-toggle="sub-slide2"><span class="sub-side-menu__label2">Tickets</span><i class="sub-angle2 fa fa-angle-right"></i></a>
												<ul class="sub-slide-menu2">
													<li><a href="{{url('support-ticketlist')}} " class="sub-slide-item2">Ticket List</a></li>
													<li><a href="{{url('support-ticketactive')}} " class="sub-slide-item2">Active Tickets</a></li>
													<li><a href="{{url('support-ticketclosed')}} " class="sub-slide-item2">Closed Tickets</a></li>
													<li><a href="{{url('support-ticketcreate')}} " class="sub-slide-item2">Create Ticket</a></li>
													<li><a href="{{url('support-ticketview')}} " class="sub-slide-item2">View Ticket</a></li>
												</ul>
											</li>
										</ul>
									</li>
									<li class="sub-slide">
										<a href="#" class="sub-side-menu__item" data-toggle="sub-slide"><span class="sub-side-menu__label">Admin</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('support-admindash')}} ">Dashboard</a></li>
											<li><a class="sub-slide-item" href="{{url('support-admineditprofile')}} ">Edit Profile</a></li>
											<li class="sub-slide2">
												<a class="sub-side-menu__item2" href="#" data-toggle="sub-slide2"><span class="sub-side-menu__label2">Tickets</span><i class="sub-angle2 fa fa-angle-right"></i></a>
												<ul class="sub-slide-menu2">
													<li><a href="{{url('support-adminticketlist')}} " class="sub-slide-item2">Ticket List</a></li>
													<li><a href="{{url('support-adminticketactive')}} " class="sub-slide-item2">Active Tickets</a></li>
													<li><a href="{{url('support-adminticketclosed')}} " class="sub-slide-item2">Closed Tickets</a></li>
													<li><a href="{{url('support-adminticketnew')}} " class="sub-slide-item2">New Ticket</a></li>
													<li><a href="{{url('support-adminticketview')}} " class="sub-slide-item2">View Ticket</a></li>
												</ul>
											</li>
											<li><a class="sub-slide-item" href="{{url('support-admincustomer')}} ">Customers</a></li>
											<li><a class="sub-slide-item" href="{{url('support-admincategories')}} ">Categories</a></li>
											<li><a class="sub-slide-item" href="{{url('support-adminarticles')}} ">Articles</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a href="#" class="sub-side-menu__item" data-toggle="sub-slide"><span class="sub-side-menu__label">Agent</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('support-agentdash')}} ">Dashboard</a></li>
											<li class="sub-slide2">
												<a class="sub-side-menu__item2" href="#" data-toggle="sub-slide2"><span class="sub-side-menu__label2">Tickets</span><i class="sub-angle2 fa fa-angle-right"></i></a>
												<ul class="sub-slide-menu2">
													<li><a href="{{url('support-agentticketlist')}} " class="sub-slide-item2">Ticket List</a></li>
													<li><a href="{{url('support-agentticketactive')}} " class="sub-slide-item2">Active Tickets</a></li>
													<li><a href="{{url('support-agentticketclosed')}} " class="sub-slide-item2">Closed Tickets</a></li>
													<li><a href="{{url('support-agentticketview')}} " class="sub-slide-item2">View Ticket</a></li>
												</ul>
											</li>
											<li><a class="sub-slide-item" href="{{url('support-agentassign')}} ">Assigned Categories</a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item"  href="{{url('chat-livechat')}} ">
									<i class="feather feather-message-square sidemenu_icon"></i>
									<span class="side-menu__label">Chat</span>
								</a>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-airplay sidemenu_icon"></i>
									<span class="side-menu__label">Admin</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('admin-general')}} " class="slide-item">General Settings</a></li>
									<li><a href="{{url('admin-api')}} " class="slide-item">API Settings</a></li>
									<li><a href="{{url('admin-role')}} " class="slide-item">Role Access</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<i class="feather feather-slack sidemenu_icon"></i>
									<span class="side-menu__label">Super Admin</span><i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu">
									<li><a href="{{url('index7')}} " class="slide-item">Dashboard</a></li>
									<li><a href="{{url('superadmin-company')}} " class="slide-item">Companies</a></li>
									<li><a href="{{url('superadmin-subscription')}} " class="slide-item">Subscription Plans</a></li>
									<li><a href="{{url('superadmin-invoices')}} " class="slide-item">Invoices</a></li>
									<li><a href="{{url('superadmin-admins')}} " class="slide-item">Super Admins</a></li>
									<li><a href="{{url('superadmin-settings')}} " class="slide-item">Settings</a></li>
									<li><a href="{{url('superadmin-role')}} " class="slide-item">Role Access</a></li>
								</ul>
							</li>
							<li class="side-item side-item-category">Apps</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-server sidemenu_icon"></i>
								<span class="side-menu__label">Components</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">

									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Chat</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('chat')}} ">Chat</a></li>
											<li><a class="sub-slide-item" href="{{url('chat2')}} ">Chat 02</a></li>
											<li><a class="sub-slide-item" href="{{url('chat3')}} ">Chat 03</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Contact</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('contact-list')}} ">Contact list</a></li>
											<li><a class="sub-slide-item" href="{{url('contact-list2')}} ">Contact list 02</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">File Manager</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('file-manager')}} ">File Manager</a></li>
											<li><a class="sub-slide-item" href="{{url('file-manager-list')}} ">File Manager 02</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Todo List</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('todo-list')}} ">Todo List</a></li>
											<li><a class="sub-slide-item" href="{{url('todo-list2')}} ">Todo List 02</a></li>
											<li><a class="sub-slide-item" href="{{url('todo-list3')}} ">Todo List 03</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">User List</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('users-list1')}} ">User List 01</a></li>
											<li><a class="sub-slide-item" href="{{url('users-list2')}} ">User List 02</a></li>
											<li><a class="sub-slide-item" href="{{url('users-list3')}} ">User List 03</a></li>
											<li><a class="sub-slide-item" href="{{url('users-list4')}} ">User List 04</a></li>
										</ul>
									</li>
									<li><a href="{{url('calendar')}} " class="slide-item"> Calendar</a></li>
									<li><a href="{{url('dragula')}} " class="slide-item"> Dragula Card</a></li>
									<li><a href="{{url('cookies')}} " class="slide-item"> Cookies</a></li>
									<li><a href="{{url('image-comparison')}} " class="slide-item"> Image Comparison</a></li>
									<li><a href="{{url('page-sessiontimeout')}} " class="slide-item"> Page-sessiontimeout</a></li>
									<li><a href="{{url('notify')}} " class="slide-item"> Notifications</a></li>
									<li><a href="{{url('sweetalert')}} " class="slide-item"> Sweet alerts</a></li>
									<li><a href="{{url('rangeslider')}} " class="slide-item"> Range slider</a></li>
									<li><a href="{{url('counters')}} " class="slide-item"> Counters</a></li>
									<li><a href="{{url('loaders')}} " class="slide-item"> Loaders</a></li>
									<li><a href="{{url('time-line')}} " class="slide-item"> Time Line</a></li>
									<li><a href="{{url('rating')}} " class="slide-item"> Rating</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-layers sidemenu_icon"></i>
								<span class="side-menu__label">Elements</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('accordion')}} " class="slide-item"> Accordion</a></li>
									<li><a href="{{url('alerts')}} " class="slide-item"> Alerts</a></li>
									<li><a href="{{url('avatars')}} " class="slide-item"> Avatars</a></li>
									<li><a href="{{url('badge')}} " class="slide-item"> Badges</a></li>
									<li><a href="{{url('breadcrumbs')}} " class="slide-item"> Breadcrumb</a></li>
									<li><a href="{{url('buttons')}} " class="slide-item"> Buttons</a></li>
									<li><a href="{{url('cards')}} " class="slide-item"> Cards</a></li>
									<li><a href="{{url('cards-image')}} " class="slide-item"> Card Images</a></li>
									<li><a href="{{url('carousel')}} " class="slide-item"> Carousel</a></li>
									<li><a href="{{url('dropdown')}} " class="slide-item"> Dropdown</a></li>
									<li><a href="{{url('footers')}} " class="slide-item"> Footers</a></li>
									<li><a href="{{url('headers')}} " class="slide-item"> Headers</a></li>
									<li><a href="{{url('jumbotron')}} " class="slide-item"> Jumbotron</a></li>
									<li><a href="{{url('list-listgroup')}} " class="slide-item"> Lists & List Group</a></li>
									<li><a href="{{url('media-object')}} " class="slide-item"> Media Obejct</a></li>
									<li><a href="{{url('modal')}} " class="slide-item"> Modal</a></li>
									<li><a href="{{url('navigation')}} " class="slide-item"> Navigation</a></li>
									<li><a href="{{url('pagination')}} " class="slide-item"> Pagination</a></li>
									<li><a href="{{url('panels')}} " class="slide-item"> Panel</a></li>
									<li><a href="{{url('popover')}} " class="slide-item"> Popover</a></li>
									<li><a href="{{url('progress')}} " class="slide-item"> Progress</a></li>
									<li><a href="{{url('tabs')}} " class="slide-item"> Tabs</a></li>
									<li><a href="{{url('tags')}} " class="slide-item"> Tags</a></li>
									<li><a href="{{url('tooltip')}} " class="slide-item"> Tooltips</a></li>
								</ul>
							</li>
							<li class="side-item side-item-category">Forms & Charts</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-file sidemenu_icon"></i>
								<span class="side-menu__label">Forms</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('form-elements')}} " class="slide-item"> Form Elements</a></li>
									<li><a href="{{url('advanced-forms')}} " class="slide-item"> Advanced Forms</a></li>
									<li><a href="{{url('form-wizard')}} " class="slide-item"> Form Wizard</a></li>
									<li><a href="{{url('form-editor')}} " class="slide-item"> Form Editor</a></li>
									<li><a href="{{url('form-sizes')}} " class="slide-item"> Form Element Sizes</a></li>
									<li><a href="{{url('form-treeview')}} " class="slide-item"> Form Treeview</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-bar-chart sidemenu_icon"></i>
								<span class="side-menu__label">Charts</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('chart-chartist')}} " class="slide-item">Chartjs Charts</a></li>
									<li><a href="{{url('chart-morris')}} " class="slide-item"> Morris Charts</a></li>
									<li><a href="{{url('chart-apex')}} " class="slide-item"> Apex Charts</a></li>
									<li><a href="{{url('chart-peity')}} " class="slide-item"> Pie Charts</a></li>
									<li><a href="{{url('chart-echart')}} " class="slide-item"> Echart Charts</a></li>
									<li><a href="{{url('chart-flot')}} " class="slide-item"> Flot Charts</a></li>
									<li><a href="{{url('chart-c3')}} " class="slide-item">C3 Charts</a></li>
								</ul>
							</li>
							<li class="side-item side-item-category">Widgets & Maps </li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-codepen sidemenu_icon"></i>
								<span class="side-menu__label">Widgets</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('widgets1')}} " class="slide-item">Widgets</a></li>
									<li><a href="{{url('widgets2')}} " class="slide-item">Chart Widgets</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-map-pin sidemenu_icon"></i>
								<span class="side-menu__label">Maps</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('maps')}} " class="slide-item">Vector Maps</a></li>
									<li><a href="{{url('maps2')}} " class="slide-item">Leaflet Maps</a></li>
									<li><a href="{{url('maps3')}} " class="slide-item">Mapel Maps</a></li>
								</ul>
							</li>
							<li class="side-item side-item-category">Icons & Tables</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-layout sidemenu_icon"></i>
								<span class="side-menu__label">Tables</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('tables')}} " class="slide-item">Default table</a></li>
									<li><a href="{{url('datatable')}} " class="slide-item">Data Table</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-compass sidemenu_icon"></i>
								<span class="side-menu__label">Icons</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('icons')}} " class="slide-item"> Font Awesome</a></li>
									<li><a href="{{url('icons2')}} " class="slide-item"> Material Design Icons</a></li>
									<li><a href="{{url('icons3')}} " class="slide-item"> Simple Line Icons</a></li>
									<li><a href="{{url('icons4')}} " class="slide-item"> Feather Icons</a></li>
									<li><a href="{{url('icons5')}} " class="slide-item"> Ionic Icons</a></li>
									<li><a href="{{url('icons6')}} " class="slide-item"> Flag Icons</a></li>
									<li><a href="{{url('icons7')}} " class="slide-item"> pe7 Icons</a></li>
									<li><a href="{{url('icons8')}} " class="slide-item"> Themify Icons</a></li>
									<li><a href="{{url('icons9')}} " class="slide-item">Typicons Icons</a></li>
									<li><a href="{{url('icons10')}} " class="slide-item">Weather Icons</a></li>
									<li><a href="{{url('icons11')}} " class="slide-item">Material Icons</a></li>
								</ul>
							</li>
							<li class="side-item side-item-category">Pages</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-copy sidemenu_icon"></i>
								<span class="side-menu__label">Pages</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Profile</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('profile1')}} ">Profile 01</a></li>
											<li><a class="sub-slide-item" href="{{url('profile2')}} ">Profile 02</a></li>
											<li><a class="sub-slide-item" href="{{url('profile3')}} ">Profile 03</a></li>
										</ul>
									</li>
									<li><a href="{{url('editprofile')}} " class="slide-item"> Edit Profile</a></li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Email</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('email-compose')}} ">Email Compose</a></li>
											<li><a class="sub-slide-item" href="{{url('email-inbox')}} ">Email Inbox</a></li>
											<li><a class="sub-slide-item" href="{{url('email-read')}} ">Email Read</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Pricing</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('pricing')}} ">Pricing 01</a></li>
											<li><a class="sub-slide-item" href="{{url('pricing2')}} ">Pricing 02</a></li>
											<li><a class="sub-slide-item" href="{{url('pricing3')}} ">Pricing 03</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Invoice</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('invoice-list')}} ">Invoice list</a></li>
											<li><a class="sub-slide-item" href="{{url('invoice1')}} ">Invoice 01</a></li>
											<li><a class="sub-slide-item" href="{{url('invoice2')}} ">Invoice 02</a></li>
											<li><a class="sub-slide-item" href="{{url('invoice3')}} ">Invoice 03</a></li>
											<li><a class="sub-slide-item" href="{{url('invoice-add')}} ">Add Invoice</a></li>
											<li><a class="sub-slide-item" href="{{url('invoice-edit')}} ">Edit Invoice</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Blog</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('blog')}} ">Blog 01</a></li>
											<li><a class="sub-slide-item" href="{{url('blog2')}} ">Blog 02</a></li>
											<li><a class="sub-slide-item" href="{{url('blog3')}} ">Blog 03</a></li>
											<li><a class="sub-slide-item" href="{{url('blog-styles')}} ">Blog Styles</a></li>
										</ul>
									</li>
									<li><a href="{{url('gallery')}} " class="slide-item"> Gallery</a></li>
									<li><a href="{{url('faq')}} " class="slide-item"> FAQS</a></li>
									<li><a href="{{url('terms')}} " class="slide-item"> Terms</a></li>
									<li><a href="{{url('emptypage')}} " class="slide-item"> Empty Page</a></li>
									<li><a href="{{url('search')}} " class="slide-item"> Search</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-shopping-cart sidemenu_icon"></i>
								<span class="side-menu__label">E-commerce</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('products')}} " class="slide-item"> Products</a></li>
									<li><a href="{{url('product-details')}} " class="slide-item"> Products Details</a></li>
									<li><a href="{{url('cart')}} " class="slide-item"> Shopping Cart</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-aperture sidemenu_icon"></i>
								<span class="side-menu__label">Utils</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('element-colors')}} " class="slide-item"> Colors</a></li>
									<li><a href="{{url('element-flex')}} " class="slide-item"> Flex Items</a></li>
									<li><a href="{{url('element-height')}} " class="slide-item"> Height</a></li>
									<li><a href="{{url('elements-border')}} " class="slide-item"> Border</a></li>
									<li><a href="{{url('elements-display')}} " class="slide-item"> Display</a></li>
									<li><a href="{{url('elements-margin')}} " class="slide-item"> Margin</a></li>
									<li><a href="{{url('elements-paddning')}} " class="slide-item"> Padding</a></li>
									<li><a href="{{url('element-typography')}} " class="slide-item"> Typhography</a></li>
									<li><a href="{{url('element-width')}} " class="slide-item"> Width</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-lock sidemenu_icon"></i>
								<span class="side-menu__label">Account</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Login</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('login1')}} ">Login 01</a></li>
											<li><a class="sub-slide-item" href="{{url('login2')}} ">Login 02</a></li>
											<li><a class="sub-slide-item" href="{{url('login3')}} ">Login 03</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Register</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('register1')}} ">Register 01</a></li>
											<li><a class="sub-slide-item" href="{{url('register2')}} ">Register 02</a></li>
											<li><a class="sub-slide-item" href="{{url('register3')}} ">Register 03</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Forgot Password</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('forgot-password1')}} ">Forgot Password 01</a></li>
											<li><a class="sub-slide-item" href="{{url('forgot-password2')}} ">Forgot Password 02</a></li>
											<li><a class="sub-slide-item" href="{{url('forgot-password3')}} ">Forgot Password 03</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Reset Password</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('reset-password1')}} ">Reset Password 01</a></li>
											<li><a class="sub-slide-item" href="{{url('reset-password2')}} ">Reset Password 02</a></li>
											<li><a class="sub-slide-item" href="{{url('reset-password3')}} ">Reset Password 03</a></li>
										</ul>
									</li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Lock Screen</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="{{url('lockscreen1')}} ">Lock Screen 01</a></li>
											<li><a class="sub-slide-item" href="{{url('lockscreen2')}} ">Lock Screen 02</a></li>
											<li><a class="sub-slide-item" href="{{url('lockscreen3')}} ">Lock Screen 03</a></li>
										</ul>
									</li>
									<li><a href="{{url('construction')}} " class="slide-item"> Under Construction</a></li>
									<li><a href="{{url('coming')}} " class="slide-item"> Coming Soon</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-alert-octagon sidemenu_icon"></i>
								<span class="side-menu__label">Alert Messages</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('message-success')}} " class="slide-item">Success Message</a></li>
									<li><a href="{{url('message-warning')}} " class="slide-item">Warning Message</a></li>
									<li><a href="{{url('message-danger')}} " class="slide-item">Danger Message</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-alert-triangle sidemenu_icon"></i>
								<span class="side-menu__label">Error Pages</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="{{url('error400')}} " class="slide-item"> 400</a></li>
									<li><a href="{{url('error401')}} " class="slide-item"> 401</a></li>
									<li><a href="{{url('error403')}} " class="slide-item"> 403</a></li>
									<li><a href="{{url('error404')}} " class="slide-item"> 404</a></li>
									<li><a href="{{url('error500')}} " class="slide-item"> 500</a></li>
									<li><a href="{{url('error503')}} " class="slide-item"> 503</a></li>
								</ul>
							</li>
							<li class="slide ">
								<a class="side-menu__item" data-toggle="slide" href="#">
								<i class="feather feather-sliders sidemenu_icon"></i>
								<span class="side-menu__label">Submenus</span><i class="angle fa fa-angle-right"></i></a>
								<ul class="slide-menu">
									<li><a href="#" class="slide-item">Level-1</a></li>
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Level-2</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a class="sub-slide-item" href="#">Level-2.1</a></li>
											<li><a class="sub-slide-item" href="#">Level-2.2</a></li>
											<li class="sub-slide2">
												<a class="sub-side-menu__item2" href="#" data-toggle="sub-slide2">Level-2.3<i class="sub-angle2 fa fa-angle-right"></i></a>
												<ul class="sub-slide-menu2">
													<li><a href="#" class="sub-slide-item2">Level-2.3.1</a></li>
													<li><a href="#" class="sub-slide-item2">Level-2.3.2</a></li>
													<li><a href="#" class="sub-slide-item2">Level-2.3.3</a></li>
												</ul>
											</li>
											<li><a class="sub-slide-item" href="#">Level-2.4</a></li>
											<li><a class="sub-slide-item" href="#">Level-2.5</a></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
						<div class="Annoucement_card">
							<div class="card-header p-0 border-bottom-0">
								<h4 class="card-title text-white font-weight-normal">Task Report</h4>
								<div class="ml-auto">
									<a href="" class="mr-0  fs-20 option-dots text-white-80" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="feather feather-more-vertical"></span>
									</a>
									<ul class="dropdown-menu dropdown-menu-right" role="menu">
										<li><a href="#"><i class="feather feather-eye mr-2"></i>View</a></li>
										<li><a href="#"><i class="feather feather-plus-circle mr-2"></i>Add</a></li>
										<li><a href="#"><i class="feather feather-trash-2 mr-2"></i>Remove</a></li>
										<li><a href="#"><i class="feather feather-download-cloud mr-2"></i>Download</a></li>
										<li><a href="#"><i class="feather feather-settings mr-2"></i>More</a></li>
									</ul>
								</div>
							</div>
							<div class="mt-3 d-flex">
								<div class="chart-circle chart-circle-sm chart-circle-primary" data-value="0.67" data-thickness="4" data-color="#3366ff">
									<div class="chart-circle-value "><i class="feather feather-folder text-white fs-20"></i></div>
								</div>
								<div class="ml-2 mt-1 text-left text-white">
									<h3 class="mb-0 mt-1  mb-2 fs-20">72%</h3>
									<span class="fs-14 font-weight-normal">Tasks Complete</span>
								</div>
							</div>
						</div>
					</div>
				</aside>
				<!--aside closed-->
