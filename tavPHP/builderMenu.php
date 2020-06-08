<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

  include 'utilityPHP/globalVar.php';
  global $ip, $porta;

  if(isset($_SESSION['status']) && $_SESSION['status'] == "true") {
    //Autorized
  }else {
    //NOT Autorized
    header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }
?>
<?php
  function buildMenu() {

      global $ip, $porta;

      $out = "";
      try {
        $co = connect();
        $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        //Prendo tutte le ricette
        $sql = "SELECT p.*, i.*
        FROM prodotto p JOIN usato u ON(u.idProdotto = p.idProdotto) JOIN ingrediente i ON(i.idIngrediente = u.idIngrediente) WHERE p.tipo = \"ricetta\"";

        $result1 = $co->query($sql);
        //Alg per costruire l'output
        $out = "";
        $idPScartati = array();
        $idPScarto = array();

        if ($result1->num_rows > 0) {
          while($row1 = $result1->fetch_assoc()) {
            if(!in_array($row1['idProdotto'], $idPScartati)){
              array_push($idPScartati, $row1['idProdotto']);
              $out .= "<div class=\"col mb-4\">
                <div class=\"card\">
                  <img src=\"../corePHP/imgFolder/" . $row1['img'] . "\" class=\"card-img-top\">
                  <div class=\"card-body\">
                    <h5 class=\"card-title\">" . $row1['nomeProdotto'] . "</h5>
                    <p class=\"card-text\">Contiene:</p>";
              $result2 = $co->query($sql);
              while($row2 = $result2->fetch_assoc()) {
                if(!in_array($row2['idProdotto'], $idPScarto)){
                  //ingrediente giusto costruisco card
                  if(in_array($row2['idProdotto'], $idPScartati)){
                    $out .= "<p class=\"card-text\">-" . $row2['nomeIngrediente'] . "</p>";
                  }

                }//else ingrediente di un altro prodotto

              }

              array_push($idPScarto, $row1['idProdotto']);
              if(isPronto($row1['idProdotto'])){
                //ho piatti pronti per il prodotto
                $out .= "<p class=\"card-text\">Piatti Pronti: " . getNumPiattoPronto($row1['idProdotto']) . "</p>";
              }else {
                //non ho piatti pronti per il prodotto metto il tempo di preparazione
                $out .="<p class=\"card-text\">Tempo: " . $row1['tempoRichiesto'] . " hour</p>";
              }

              $out .= "<p class=\"card-text font-weight-bolder text-right\">Costo: " . $row1['prezzo'] . "€</p>
                  <div class=\"row justify-content-between\"><p class=\"card-link text-left\"><a href=\"add_rmv/gestione.php?idP=" . $row1['idProdotto'] . "&opz=R\">- REMOVE</a></p>
                  <p class=\"card-link text-right\"><a href=\"add_rmv/gestione.php?idP=" . $row1['idProdotto'] . "&opz=A\">+ ADD</a></p>
                </div></div>
              </div>
            </div>";
            }//else card gia' costruita

          }

        }else{
          $co->rollBack();
          $co->close();
          header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
          die();
        }
        $co->commit();

        if(!closeConn($co)){
          $co->rollBack();
          header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
          die();
        }

      } catch (Exception $e) {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

      return $out;
  }

  function isPronto($idProdotto) {
    global $ip, $porta;

    $out = false;
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT pi.idProdotto FROM piatto_pronto pi GROUP BY pi.idProdotto";
      $result = $co->query($sql);

      if($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              if($row['idProdotto'] == $idProdotto) {
                $out = true;
                break;
              }

          }

      }

      $co->commit();

      if(!closeConn($co)){
        $co->rollBack();
        header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

    } catch (Exception $e) {
      $co->rollBack();
      $co->close();
      header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
      die();
    }

    return $out;
  }

  function getNumPiattoPronto($idProdotto) {
    global $ip, $porta;

    $out = "/";
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT count(pi.idProdotto) as num, p.idProdotto FROM prodotto p JOIN piatto_pronto pi ON(pi.idProdotto = p.idProdotto) WHERE p.idProdotto=\"$idProdotto\"GROUP BY pi.idProdotto";
      $result = $co->query($sql);

      if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
          $out = (int)$row['num'];
        }

      }else{
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

      $co->commit();

      if(!closeConn($co)){
        $co->rollBack();
        header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

    } catch (Exception $e) {
      $co->rollBack();
      $co->close();
      header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
      die();
    }

    return $out;
  }

  function buildMenuArt() {

      global $ip, $porta;

      $out = "";
      try {
        $co = connect();
        $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        //Prendo tutte le ricette
        $sql = "SELECT p.*, a.*
        FROM prodotto p JOIN articolo a ON(p.idProdotto = a.idArticolo) WHERE p.tipo = \"articolo\"";

        $result1 = $co->query($sql);
        //Alg per costruire l'output
        $out = "";
        $idPScartati = array();

        if ($result1->num_rows > 0) {
          while($row1 = $result1->fetch_assoc()) {
            if(!in_array($row1['idProdotto'], $idPScartati)){
              array_push($idPScartati, $row1['idProdotto']);
              $out .= "<div class=\"col mb-4\">
                <div class=\"card\">
                  <img src=\"../corePHP/imgFolder/" . $row1['img'] . "\" class=\"card-img-top\">
                  <div class=\"card-body\">
                    <h5 class=\"card-title\">" . $row1['nomeProdotto'] . "</h5>
                    <p class=\"card-text font-weight-bolder text-right\">Costo: " . $row1['prezzo'] . "€</p>
                    <div class=\"row justify-content-between\"><p class=\"card-link text-left\"><a href=\"add_rmv/gestione.php?idP=" . $row1['idProdotto'] . "&opz=R\">- REMOVE</a></p>
                    <p class=\"card-link text-right\"><a href=\"add_rmv/gestione.php?idP=" . $row1['idProdotto'] . "&opz=A\">+ ADD</a></p>
                    </div>
                  </div>
                </div>
              </div>";
            }//else card gia' costruita

          }

        }else{
          $co->rollBack();
          $co->close();
          header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
          die();
        }
        $co->commit();

        if(!closeConn($co)){
          $co->rollBack();
          header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
          die();
        }

      } catch (Exception $e) {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

      return $out;
  }
?>
