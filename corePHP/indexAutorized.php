<?php
  session_start();
  include 'utilityPHP/connessioneDB.php';
  include 'utilityPHP/globalVar.php';
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
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/bootstrap-responsive.css">
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
        <li class="nav-item active">
          <a class="nav-link text-dark" href="#">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="#">Coda Piatti</a>
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
     <h1 class="display-2"><p class="text-center">Qui troverai tutti gli ordini</p></h1>
   </div>

   <!-- nested columns -->
    <div class="row">
     <!-- first nested column -->
       <div class="col-md ">
        <table class="table">
         <thead>
           <tr>
             <th scope="col">#</th>
             <th scope="col">First</th>
             <th scope="col">Last</th>
             <th scope="col">Handle</th>
              <th scope="col">Handle</th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <th scope="row">1</th>
             <td>Mark</td>
             <td>Otto</td>
             <td>@mdo</td>
             <td>@mdo</td>
           </tr>
           <tr>
             <th scope="row">1</th>
             <td>Mark</td>
             <td>Otto</td>
             <td>@mdo</td>
             <td>@mdo</td>
           </tr>
           <tr>
             <th scope="row">1</th>
             <td>Mark</td>
             <td>Otto</td>
             <td>@mdo</td>
             <td>@mdo</td>
           </tr>
           <tr>
             <th scope="row">1</th>
             <td>Mark</td>
             <td>Otto</td>
             <td>@mdo</td>
             <td>@mdo</td>
           </tr>
           <tr>
             <th scope="row">1</th>
             <td>Mark</td>
             <td>Otto</td>
             <td>@mdo</td>
             <td>@mdo</td>
           </tr>
         </tbody>
       </table>
       </div>
    </div>

<?php
  $conn = connect();
  if(closeConn($conn)) {
    echo "Chiusa";
  }else {
    echo "Non chiusa";
  }
?>
<!--
    <footer class = "container mt-4">
    <div class = "row">
    <div class = "col">
      <p class = "text-center">Design by Ferencz 5Q</p>
    </div>
    </div>
    </footer>
-->
  </body>
</html>
