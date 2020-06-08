<?php
  session_start();
  include '../utilityPHP/globalVar.php';

  global $ip, $porta, $erSize, $erFormato;

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
          <a class="nav-link text-light" href="../indexAutorized.php">Home</a>
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
          <a class="nav-link text-dark" href="">New Prodotto</a>
        </li>
       </ul>
       <div class="navbar-nav ml-auto">
         <a class="nav-item nav-link text-light" href="../close.php">by Ferencz 5Q</a>
      </div>
      </div>
     </div>
    </nav>

    <div class="col-sm text-center">
    <br><br><br>
     <h3>Aggiungi Ricetta</h3>
   </div>

   <!-- nested columns -->
    <div class="container justify-content-center">
      <hr class="my-4">
      <form class="myForm" method="POST" action="gestioneAdd.php" enctype="multipart/form-data">
         <div class="form-group">
           <label for="nRic">Nome Prodotto</label>
           <input type="text" class="form-control" name="nomeProdotto" id="nRic" placeholder="Inserisci nome prodotto" required>
         </div>

         <div class="form-group">
           <label for="tipo">Tipo Prodotto</label>
           <select class="form-control" name="tipoProdotto" id="tipo">
             <option value="Ricetta" selected>Ricetta</option>
             <option value="Articolo">Articolo</option>
           </select>
         </div>

         <div class="form-group">
           <label for="prez">Prezzo Prodotto</label>
           <input type="number" min="1" step="0.01" class="form-control" name="prezzoProdotto" id="prez" placeholder="Inserisci prezzo prodotto" required>
         </div>

         <div class="form-group">
           <label for="tempo">Tempo Richiesto</label>
           <input type="text" class="form-control" name="tempoRichiesto" id="tempo" placeholder="Inserisci tempo di preparazione ES-> Hour:Min:Sec" required>
           <?php
             if($_SESSION['tempoOK'] == $erListaI) {
               echo "<br><p class=\"text-danger\">Formato Non Rispettato.Formato valido-> Hour:Min:Sec</p>";
               $_SESSION['tempoOK'] = "";
             }

               $_SESSION['tempoOK'] = "";
           ?>
         </div>

        <div class="form-group">
             <label for="list">Lista ingredienti</label>
             <textarea class="form-control" id="list" placeholder="Es-> nomeIngrediente;quantitàUsata;descrizioneIngrediente.
nuova riga"rows="3" name="list"></textarea>
            <?php
              if($_SESSION['listaOK'] == $erListaI) {
                echo "<br><p class=\"text-danger\">Formato Non Rispettato o non inserito.Formato valido Es-> nomeIngrediente;quantitàUsata;descrizioneIngrediente.</p>
                <p class=\"text-danger\">nuova riga</p>";
                $_SESSION['listaOK'] = "";
              }

                $_SESSION['listaOK'] = "";
            ?>
        </div>

        <div class="form-group">
          <div class="custom-file">
            <input class="custom-file-input" id="customFile1" type="file" name="ricetta" >
            <label class="custom-file-label" for="customFile1">Allega ricetta</label>
          </div>

          <?php
            if($_SESSION['ricettaOK'] == $erFormato) {
              echo "<br><p class=\"text-danger\">Formato Non Valido. Formati Validi-> .pdf, .txt, .docx</p>";
              $_SESSION['ricettaOK'] = "";
            }else if($_SESSION['ricettaOK'] == $erSize) {
              echo "<br><p class=\"text-danger\">Dimensione file troppo grande.</p>";
              $_SESSION['ricettaOK'] = "";
            }else if($_SESSION['ricettaOK'] == $erGen) {
              echo "<br><p class=\"text-danger\">Caricamento file fallito.</p>";
              $_SESSION['ricettaOK'] = "";
            }

            $_SESSION['ricettaOK'] = "";
          ?>
        </div>

        <div class="form-group">
          <div class="custom-file">
            <input class="custom-file-input" id="customFile2" type="file" name="img" >
            <label class="custom-file-label" for="customFile2">Immagine ricetta</label>
          </div>

          <?php
            if($_SESSION['imgOK'] == $erFormato) {
              echo "<br><p class=\"text-danger\">Formato Non Valido. Formati Validi[.pdf, .txt, .docx]</p>";
              $_SESSION['imgOK'] = "";
            }else if($_SESSION['imgOK'] == $erSize) {
              echo "<br><p class=\"text-danger\">Dimensione file troppo grande.</p>";
              $_SESSION['imgOK'] = "";
            }else if($_SESSION['imgOK'] == $erGen) {
              echo "<br><p class=\"text-danger\">Caricamento file fallito.</p>";
              $_SESSION['imgOK'] = "";
            }

            $_SESSION['imgOK'] = "";
          ?>
        </div>

        <div class="form-group">
          <input class="btn btn-primary" type="submit" placeholder="Invia Prodotto">
          <?php
            if($_SESSION['newProdottoOK']) {
              echo "<br><p class=\"text-success\">Prodotto aggiunto con successo.</p>";
              $_SESSION['newProdottoOK'] = false;
            }
          ?>
        </div>
     </form>
    </div>

  </body>
</html>
