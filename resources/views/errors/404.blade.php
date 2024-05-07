<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ url('public/customer/images/favicon.png') }}" type="image/x-icon">
	<title>404</title>
	@include('includes.style')
	<style type="text/css">
		.page_404{ padding:40px 0; background:#fff;}
		.page_404  img{ width:100%;}

		.four_zero_four_bg h1{
			font-size:80px;
		}
		.four_zero_four_bg h3{
			font-size:80px;
		}
		.link_404{			 
			color: #fff!important;
			padding: 10px 20px;
			background: #39ac31;
			margin: 20px 0;
			display: inline-block;
		}
		.contant_box_404{ 
			/*margin-top:-50px;*/
		}
	</style>

</head>
<body>
	<main>
		<section class="page_404">
			<div class="container">
				<div class="row">	
					<div class="col-sm-12">
						{{-- <div class="col-sm-10 col-sm-offset-1 "> --}}
							<div class="four_zero_four_bg  text-center">
								<h1 class="text-center">404</h1>
							</div>
							<div class="contant_box_404 text-center">
								<h3 class="h2">
									Page not found.
								</h3>
								<p>the page you are looking for not avaible!</p>
								<a href="{{url('/')}}" class="btn">Go to Home</a>
							</div>
						{{-- </div> --}}
					</div>
				</div>
			</div>
		</section>
	</main>
</body>
</html>