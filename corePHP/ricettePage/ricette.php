<?php
  session_start();
  include '../utilityPHP/globalVar.php';
  include 'builderCarouselRicette.php';
  global $ip, $porta;

  if(isset($_SESSION['status']) && $_SESSION['status'] == "true") {
    //Autorized
  }else {
    //NOT Autorized
    header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }
?>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel = "stylesheet" href = "../css/bootstrap.css">
    <link rel = "stylesheet" href = "../css/bootstrap-responsive.css">
    <script src="../js/bootstrap.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </head>
  <body>
    <nav class="navbar fixed-top navbar-expand-md navbar-light bg-primary">
     <div class="container-fluid">
       <a class="navbar-brand text-light">Cucina dopo COVID-19</a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarCollapse">
       <!-- left navigation links -->
       <ul class="navbar-nav mr-auto">
        <!-- active navigation link -->
        <li class="nav-item">
          <a class="nav-link text-light" href=".././indexAutorized.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../codaPiatti/codaPiattiPronti.php">Coda Piatti</a>
        </li>

        <li class="nav-item active">
          <a class="nav-link text-dark" href="">Ricette</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../statoDispensa/initStato.php">Stato dispensa</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../sezioneSpesa/spesa.php">Spesa</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../newProdotto/addProdotto.php">New Prodotto</a>
        </li>
       </ul>
       <div class="navbar-nav ml-auto">
         <a class="nav-item nav-link text-light" href="../close.php">by Ferencz 5Q</a>
      </div>
      </div>
     </div>
    </nav>

    <div class="container justify-content-center">
      <br><br><br><br><br>
     <h3 class="text-center">Qui troverai tutte le ricette</h3>
     <!-- nested columns -->
     <br>
      <div class="row justify-content-center">
       <!-- first nested column -->
         <div class="col-md-10">
           <?php
            echo bulidCarousel();
           ?>
         </div>
      </div>
    </div>
  </body>
</html>
