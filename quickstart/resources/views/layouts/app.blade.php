<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Laravel test --advance</title>
	<!-- CSS and Javascript insert here -->
		<!-- Link Npm BootStrap@v3 -->
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <!-- Link Npm Jquery@3.2.1 -->
    	<script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
	
</head>
<body>
	<div class="container">
	   <div class="navbar navbar-default">
	    <!-- Navbar Contents -->
	   </div>
	</div>
	@yield('content')
</body>
</html>