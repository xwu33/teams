<header class="header" role="banner">
	<div class="masthead">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="help-links">
						<a href="http://ua.edu" class="ua-crumb">
							<img src="/images/ua-header.png" alt="The University of Alabama" class="ua-header" />
						</a>
						<span class="hidden-xs hidden-sm hidden-md">

							<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
							<a href="http://as.ua.edu">Arts &amp; Sciences</a>
							<div class="current-crumb">
								<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
								<a href="">{{ config('app.name', 'bioproc') }}</a>
							</div>

						</span>
					</div>
				</div>
			</div>
		</div><!-- .container -->
	</div><!-- #masthead -->
	<div id="wrapper-navbar">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<a href="">
						<img src="/images/as_wordmark.png" alt="College of Arts & Sciences" class="college-wordmark">
					</a>
				</div>
				<div class="col-md-6">

				</div>
			</div><!--end row-->

			<a class="sr-only" href="#content">Skip to content</a>

			<nav class="site-navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
				<div class="navbar navbar-default">

					<div class="row">

						<div class="col-xs-12">

							<div class="navbar-header">

								<!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>



							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div class="collapse navbar-collapse navbar-responsive-collapse" id="app-navbar-collapse">
									<div class="menu-main-navigation-container">
										<ul class="nav navbar-nav">
											<!-- Authentication Links -->
											<li class="menu-item nav-item"><a href="/" class="nav-link">Home</a></li>
											@if (Auth::guest())
												<li><a href="{{ route('login') }}">Login</a></li>
											@else
												@hasanyrole(Role::all())
												<li class="menu-item nav-item"><a href="/calendar" class="nav-link">Calendar</a></li>
												<li class="menu-item menu-item-has-children nav-item dropdown">
													<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Exams<span class="glyphicon glyphicon-menu-down"></span></a>
													<ul class="dropdown-menu">
														<li class="menu-item nav-item">
															<a href="{{ route('exams.index') }}" class="nav-link">List Exams</a>
														</li>
														@can('Signup Self')
															<li class="menu-item nav-item">
																<a href="{{ route('signups.index') }}" class="nav-link">My Signups</a>
															</li>
														@endcan
														@hasanyrole('Admin|Faculty')
														<li class="menu-item nav-item">
															<a href="{{ route('exams.index') }}" class="nav-link">My Exams</a>
														</li>
														<li class="menu-item nav-item">
															<a href="{{ route('exams.create') }}" class="nav-link">Create Exam</a>
														</li>
														@endhasanyrole
													</ul>
												</li>
												@role('Admin')
												<li class="menu-item nav-item"><a href="/users" class="nav-link">User Management</a></li>
												<li class="menu-item nav-item"><a href="/settings" class="nav-link">Control Panel</a></li>
												@endrole('Admin')
												@endhasanyrole
												<li>
													<a href="{{ route('logout') }}"
													onclick="event.preventDefault();
													document.getElementById('logout-form').submit();">
													Logout
												</a>
												<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
													{{ csrf_field() }}
												</form>
											</li>
										@endif
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</div>
</div>
</header>
