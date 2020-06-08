<?php
  session_start();
  include '../utilityPHP/globalVar.php';
  include '../builderHomeAutorized.php';
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
/*#93a1a7*/
      .btn {
        color: white;
        font-family: Helvetica, Arial, Sans-Serif;
        font-size: 20px;
        text-decoration: none;
        text-shadow: -1px -1px 1px #616161;
        position: relative;
        padding: 15px 30px;
        box-shadow: 5px 5px 0 #666;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        -ms-transition: all 0.3s ease;
         transition: all 0.3s ease;
        margin: 10px;
          background: #343a40;
          display: inline-block;
          border-radius: 8px;
      }

      .btn:hover {
        box-shadow: 0px 0px 0 #666;
        top: 5px;
        left: 5px;
          background: #36454f;
      }

      .viewMoreInfo {
        color: #36bf0b;
      }

    </style>
  </head>
  <body>
    <nav class="navbar fixed-top navbar-expand-md navbar-light bg-primary">
     <div class="container-fluid">
       <a class="navbar-brand text-light">Tavolo dopo COVID-19</a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarCollapse">
       <!-- left navigation links -->
       <ul class="navbar-nav mr-auto">
        <!-- active navigation link -->
        <li class="nav-item active">
          <a class="nav-link text-dark" href="#">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../codaPiatti/codaPiattiPronti.php">Coda Piatti</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../ricettePage/ricette.php">Ricette</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../statoDispensa/initStato.php">Stato dispensa</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../sezioneSpesa/spesa.php">Spesa</a>
        </li>

        <li class="nav-item active">
          <a class="nav-link text-light" href="../newProdotto/addProdotto.php">New Prodotto</a>
        </li>
       </ul>
       <div class="navbar-nav ml-auto">
         <a class="nav-item nav-link text-light" href="../close.php">by Ferencz 5Q</a>
      </div>
      </div>
     </div>
    </nav>

    <div class="container justify-content-center" style="padding-top: 15%;">
      <div class="row">
        <div class="col-md text-center">
          <h1>Si stanno per verificare eventi non ripristinabili</h1>
          <br>
          <div class="text-center">
            <h3>Si vuole procedere comunque?</h3>
            <a href="modificaStatoOrdine.php?oraOrdine=<?php echo $_GET['oraOrdine'];?>&dataOrdine=<?php echo $_GET['dataOrdine'];?>" class="btn">SI</a>
            <a href="../indexAutorized.php" class="btn">NO</a>
          </div>
        </div>
      </div>
    </div>
    <?php
      $_SESSION['updSts'] = "rimandata";
    ?>
  </body>
</html>
