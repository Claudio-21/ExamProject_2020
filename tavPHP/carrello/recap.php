<?php
  include '../utilityPHP/utilFunction.php';
  include '../utilityPHP/globalVar.php';
  include 'builderRecap.php';

  if(!isset($_SESSION['status']))
    session_start();

  global $ip, $porta;

  if(isset($_SESSION['status']) && $_SESSION['status'] == "true") {
    //Autorized
  }else {
    //NOT Autorized
    header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }

?>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel = "stylesheet" href = "../css/bootstrap.css">
    <link rel = "stylesheet" href = "../css/bootstrap-responsive.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </head>
  <body>

    <nav class="navbar fixed-top navbar-expand-md navbar-light bg-primary">
     <div class="container-fluid">
       <a class="navbar-brand text-light">Tavolo <?php echo getNumeroTavolo("192.168.2.3")?></a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarCollapse">
       <!-- left navigation links -->
       <ul class="navbar-nav mr-auto">
        <!-- active navigation link -->
        <li class="nav-item">
          <a class="nav-link text-light" href="../indexAutorized.php">Menù</a>
        </li>

        <li class="nav-item active">
          <a class="nav-link text-dark" href="">Carrello</a>
        </li>

       </ul>
       <div class="navbar-nav ml-auto">
         <a class="nav-item nav-link text-light" href="close.php">by Ferencz 5Q</a>
      </div>
      </div>
     </div>
    </nav>

    <div class="container">
      <br><br><br><br>
      <div class="row justify-content-center">
        <h5 class="text-black">RECAP</h5>
      </div><br class="bg-dark">
      <div class="row justify-content-center">
        <?php
          echo buildRecap();
        ?>

      </div>
      <div class="sticky-bottom text-center" style="padding-top:4%"><a href="../conferma/confOrd.php"><input class="btn btn-primary" type="submit" value="Conferma Ordine" ></a></div>
      <div class="fixed-bottom text-right font-weight-bolder" style="padding-right:5%;">
        <p>Totale: <?php echo getTot();?>€</p>
      </div>
    </div>
  </body>
</html>
