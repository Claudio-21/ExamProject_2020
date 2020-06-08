<?php
  include 'utilityPHP/utilFunction.php';
  include 'utilityPHP/globalVar.php';
  include 'builderMenu.php';
  include 'carrello/carrello.php';

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

  if($_SESSION['carrello'] == "")
      $_SESSION['carrello'] = serialize(new Carrello());

?>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="refresh" content="15">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/bootstrap-responsive.css">
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
        <li class="nav-item active">
          <a class="nav-link text-dark" href="">Menù</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="carrello/recap.php">Carrello</a>
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
      <div class="row justify-content-center bg-dark">
        <h5 class="text-white">MENÙ</h5>
      </div><br>
      <div class="row row-cols-1 row-cols-md-3">
        <?php
          echo buildMenu();
        ?>
      </div>
      <br>
      <div class="row justify-content-center bg-dark">
        <h5 class="text-white">ARTICOLI</h5>
      </div><br class="bg-dark">
      <div class="row row-cols-1 row-cols-md-3">
        <?php
          echo buildMenuArt();
        ?>
      </div>
      <div class="fixed-bottom text-right font-weight-bolder" style="padding-right:5%;">
        <?php
          if($_SESSION['stsOrd'] != "") {
            if($_SESSION['stsOrd'] == "add") {
              //Successo add
              echo "<p class=\"text-success\">Prodotto aggiunto con successo!</p>";
              $_SESSION['stsOrd'] = "";

            }else if($_SESSION['stsOrd'] == "rmvOk"){
              //Successo elim
              echo "<p class=\"text-danger\">Prodotto eliminato con successo!</p>";
              $_SESSION['stsOrd'] = "";

            }else if($_SESSION['stsOrd'] == "rmvNOTok"){
              //Non eliminato perchè non presente nel Carrello
              echo "<p class=\"text-danger\">Prodotto non presente nel carrello!</p>";
              $_SESSION['stsOrd'] = "";

            }else if($_SESSION['stsOrd'] == "conf") {
              echo "<p class=\"text-success\">Ordine eseguito con sucesso!</p>";
              $_SESSION['stsOrd'] = "";
            }
          }

        ?>
      </div>
    </div>
  </body>
</html>
