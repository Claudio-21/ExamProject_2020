<!DOCTYPE html>
<html lang="en">

<head>
	<meta name = "viewport" content = "larghezza = larghezza dispositivo, scala iniziale = 1,0">
  <link rel = "stylesheet" href = "../css/bootstrap.css">
  <link rel = "stylesheet" href = "../css/bootstrap-responsive.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<title>404</title>
	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="style.css" />

</head>

<body>
  <div class="jumbotron">
   <p class="display-2">
     <div id="notfound">
  		<div class="notfound">
  			<div class="notfound-404">
  				<h1>Oops!</h1>
  			</div>
  			<h2>404 - Page not found</h2>
  			<p><?php echo $_GET['msg'];   ?></p>
  		</div>
  	 </div>
    </p>
  </div>
</body>

</html>
