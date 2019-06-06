<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<title>@yield('title') - Bio Proctor</title>
		<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!--<title>{{ config('app.name', 'bioproc') }}</title>-->
		<!-- Styles -->
		<link rel='dns-prefetch' href='//fast.fonts.net' />
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/theme.min.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.print.css" media="print"/>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

		<!--
		<link rel="stylesheet" href="css/datepicker3.css">
		<script type="text/javascript" src="/bootstrap-datepicker.js"></script>
		!-->
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div id="app" class="hfeed site">
			@include('partials.header')
			<div class="wrapper custom-bg-wrapper" id="page-wrapper">
				<div id="content" class="container">
					<div class="row">
						<div class="col-xs-12">
							<div id="page-header-container">
								<header class="article-header">
									<h1 class="page-title text-white">
										@yield('title')
									</h1>
								</header>
							</div>
						</div>
					</div>
					<main id="main" class="site-main" role="main">
						@yield('content')
					</main>

					{{-- <div id="sidebar">
						@yield('some sidebar thing')
					</div> --}}

				</div>
			</div>
			@include('partials.footer')
		</div>

		<!-- Scripts -->
		<script src="{{ asset('js/manifest.js') }}"></script>
		<script src="{{ asset('js/vendor.js') }}"></script>
		<script src="{{ asset('js/app.js') }}"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
		@yield('scripts')

		<!--[if lte IE 8]>
		<script type="text/javascript">
			document.body.className = document.body.className.replace( /(^|\s)(no-)?customize-support(?=\s|$)/, '' ) + ' no-customize-support';
		</script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
		<script type="text/javascript">
			(function() {
				var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');

				request = true;

				b[c] = b[c].replace( rcs, ' ' );
				// The customizer requires postMessage and CORS (if the site is cross domain)
				b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;
			}());
		</script>
		<!--<![endif]-->

	</body>
</html>
