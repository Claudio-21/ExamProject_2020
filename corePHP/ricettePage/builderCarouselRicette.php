<?php
//Controllo nel caso ci sia qualche hacker
  include '../utilityPHP/connessioneDB.php';
  include '../utilityPHP/globalVar.php';
  global $ip, $porta;

  if(isset($_SESSION['status']) && $_SESSION['status'] == "true") {
    //Autorized
  }else {
    //NOT Autorized
    header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }
?>

<?php
  function bulidCarousel() {
    global $ip, $porta;
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT * FROM prodotto WHERE tipo=\"Ricetta\"";
      $result = $co->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        //intestazione carousel
        $out = "
        <div id=\"carouselExampleIndicators\" class=\"carousel slide\" data-ride=\"carousel\">
          <ol class=\"carousel-indicators\">";
        for($i = 0; $i < mysqli_num_rows($result); $i++) {
          if($i == 0){
            $out .= "<li data-target=\"#carouselExampleIndicators\" data-slide-to=\"$i\" class=\"active\"></li>";
          }else{
            $out .= "<li data-target=\"#carouselExampleIndicators\" data-slide-to=\"$i\"></li>";
          }
        }
        $out .= "</ol>";

        $i = 0;
        $out .= "<div class=\"carousel-inner\">";
        while($row = $result->fetch_assoc()) {
          if($i == 0) {
            $out .= "
              <div class=\"carousel-item active\">
                <a href=\"moreInfo.php?idProdotto=" . $row['idProdotto'] . "\"><img class=\"d-block w-100\" src=\"../imgFolder/" . $row['img'] . "\" alt=\"" . $row['nomeProdotto'] . "\"></a>
                <div class=\"carousel-caption d-none d-md-block\">
                  <p>" . $row['nomeProdotto'] . "</p>
                </div>
              </div>";
          }else{
            $out .= "
              <div class=\"carousel-item\">
                <a href=\"moreInfo.php?idProdotto=" . $row['idProdotto'] . "\"><img class=\"d-block w-100\" src=\"../imgFolder/" . $row['img'] . "\" alt=\"" . $row['nomeProdotto'] . "\"></a>
                <div class=\"carousel-caption d-none d-md-block\">
                  <p>" . $row['nomeProdotto'] . "</p>
                </div>
              </div>";
          }
          $i++;
        }
        
        $out .= "
          </div>
          <a class=\"carousel-control-prev\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"prev\">
            <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
            <span class=\"sr-only\">Previous</span>
          </a>
          <a class=\"carousel-control-next\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"next\">
            <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
            <span class=\"sr-only\">Next</span>
          </a>
        </div>";

      } else {
        $out = "<p class=\"text-center\">Nessuna ricetta trovata!</p>";
      }

      $co->commit();

      if(!closeConn($co)){
        $co->rollBack();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

    } catch (Exception $e) {
      $co->rollBack();
      $co->close();
      header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
      die();
    }

    return $out;
  }

  function bulidInfoRicetta($idProdotto) {
    global $ip, $porta;
//PARTE 1
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
      //Prendo info della ricetta e del file
      $sql = "SELECT p.*, f.*
        FROM prodotto p JOIN file f ON(f.idFile=p.idFile)
        WHERE p.tipo=\"Ricetta\" AND p.idProdotto=$idProdotto";

      $result = $co->query($sql);
      if ($result->num_rows > 0) {
        // output data of each row
        //intestazione carousel

        $out = "<div class=\"jumbotron\">";
        while($row = $result->fetch_assoc()) {
          $out .= "<h3 class=\"display-4\">". $row['nomeProdotto'] . "      ";
          $out .="
            <figure class=\"figure\">
              <img class=\"figure-img img-fluid rounded\" width=\"50px\" height=\"50px\"src=\"../imgFolder/time.png\"> " . $row['tempoRichiesto'] . " minuti</img>
            </figure>
          </h3>
          <p class=\"lead\"><a href=\"../downloadFile/download.php?idFile=" . $row['idFile'] . "\">Istruzioni ...<a></p>";//se clicchi scarica il file con i passaggi per fare la ricetta
        }

      } else {
        $co->rollBack();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

      $co->commit();

      if(!closeConn($co)){
        $co->rollBack();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

    } catch (Exception $e) {
      $co->rollBack();
      $co->close();
      header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
      die();
    }
//PARTE 2
    try {
      global $quantitaMIN;
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
      //Prendo info della lista degli ingredienti
      $sql = "SELECT i.*, u.*
        FROM prodotto p JOIN usato u ON(p.idProdotto=u.idProdotto)
          JOIN ingrediente i ON(i.idIngrediente=u.idIngrediente)
        WHERE p.tipo=\"Ricetta\" AND p.idProdotto=$idProdotto";

      $result = $co->query($sql);
      if ($result->num_rows > 0) {
        //Intestazione
         $out .= "<p class=\"lead\">Lista ingredienti:</p>
           <hr class=\"my-4\">
           <div class=\"row mb-3\">";
        //Contenuto dinamico
        while($row = $result->fetch_assoc()) {
          if($row['quantitàInDispensa'] <= $row['quantitàUsata']) {
            //Ingrediente scarseggia
            $out .= "<div class=\"col-sm-4 themed-grid-col text-danger\">" . $row['nomeIngrediente'] . " --> " . $row['quantitàUsata'] . " g</div>";
          }else {
            //Ingrediente abbondante
            $out .= "<div class=\"col-sm-4 themed-grid-col text-success\">" . $row['nomeIngrediente'] . " --> " . $row['quantitàUsata'] . " g</div>";
          }

        }
        $out .= "</div></div>";
      } else {
        $co->rollBack();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

      $co->commit();

      if(!closeConn($co)){
        $co->rollBack();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

    } catch (Exception $e) {
      $co->rollBack();
      $co->close();
      header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
      die();
    }

    return $out;
  }

?>
