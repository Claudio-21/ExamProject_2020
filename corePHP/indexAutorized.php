<?php
  session_start();
  include 'utilityPHP/globalVar.php';
  include 'builderHomeAutorized.php';
  include 'classPHP/gestoreOrdine.php';
  global $ip, $porta;

  if(isset($_SESSION['status']) && $_SESSION['status'] == "true") {
    //Autorized
    if($_SESSION['gestoreOrdini'] == ""){
      $_SESSION['gestoreOrdini'] = serialize(new gestoreOrdine());
    }

  }else {
    //NOT Autorized
    header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }
?>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="refresh" content="15">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/searchBarCSS.css">
    <script src="js/searchBar.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/searchUtil.js" charset="utf-8"></script>

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
          <a class="nav-link text-light" href="codaPiatti/codaPiattiPronti.php">Coda Piatti</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="ricettePage/ricette.php">Ricette</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="statoDispensa/initStato.php">Stato dispensa</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="sezioneSpesa/spesa.php">Spesa</a>
        </li>

        <li class="nav-item active">
          <a class="nav-link text-light" href="newProdotto/addProdotto.php">New Prodotto</a>
        </li>
       </ul>
       <div class="navbar-nav ml-auto">
         <a class="nav-item nav-link text-light" href="close.php">by Ferencz 5Q</a>
      </div>
      </div>
     </div>
    </nav>

    <div class="jumbotron">
      <br>
     <h1 class="display-2"><p class="text-center">Ordinazioni</p></h1>
    </div>

     <!-- nested columns -->
    <div class="container">
      <div class="d-flex justify-content-start">
         <label class="search" for="inpt_search">
           <input class="p-2" id="myInput" type="text"></input>
         </label>
      </div>
      <div class="row">
       <!-- first nested column -->
         <div class="col-md ">
          <?php
            echo buildHomeAutorized();
          ?>
         </div>
      </div>
      <div class="fixed-bottom text-right font-weight-bolder" style="padding-right:5%;">
        <?php
          if($_SESSION['updSts'] != "") {
            if($_SESSION['updSts'] == "ok") {
              $_SESSION['updSts'] = "";
              echo "<p class=\"text-success\">Ordine eseguito con successo!</p>";
            }else if($_SESSION['updSts'] == "rimandata") {
              $_SESSION['updSts'] = "";
              echo "<p class=\"text-danger\">Ordine rimandato a più tardi!</p>";
            }else if($_SESSION['updSts'] == "illegal"){
              $_SESSION['updSts'] = "";
              echo "<p class=\"text-danger\">Ordine non completato al 100%!</p>";
            }

          }

          if($_SESSION['controlloQuat'] != "") {
            if($_SESSION['controlloQuat'] == "notOK") {
              $_SESSION['controlloQuat'] = "";
              echo "<p class=\"text-danger\">Quantià in Dispensa insufficiente per soddisfare l'ordine!</p>";
            }else if($_SESSION['controlloQuat'] == "ok"){
              $_SESSION['controlloQuat'] = "";
              echo "<p class=\"text-success\">Ordine aggiornato con successo!</p>";
            }
          }
        ?>
      </div>
    </div>
  </body>
</html>
