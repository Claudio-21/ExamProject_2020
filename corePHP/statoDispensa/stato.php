<?php
  if(!isset($_SESSION['status']))
    session_start();
  include '../utilityPHP/globalVar.php';
  include 'builderStatoDispensa.php';
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
    <link rel="stylesheet" href="../css/searchBarCSS.css">
    <script src="../js/bootstrap.js" charset="utf-8"></script>
    <script src="../js/searchBar.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="../js/searchUtil.js" charset="utf-8"></script>

    <script>
      $(document).ready(function(){
        $("#myInput").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
    </script>

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
          <a class="nav-link text-light" href="../indexAutorized.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../codaPiatti/codaPiattiPronti.php">Coda Piatti</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="../ricettePage/ricette.php">Ricette</a>
        </li>

        <li class="nav-item active">
          <a class="nav-link text-dark" href="initStato.php">Stato dispensa</a>
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

    <div class="container text-center">
       <br><br><br>
       <h3 >Dispensa/Inventario</h3>
       <hr class="my-4">
       <!-- nested columns -->
       <div class="d-flex justify-content-start">
          <label class="search" for="inpt_search">
            <input class="p-2" id="myInput" type="text"></input>
          </label>
       </div>
        <?php
          echo bulidStatoDispensa($_POST['quantitàMIN']);
        ?>

    </div>

  </body>
</html>
