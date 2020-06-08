<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

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
  function bulidMoreInfo($dataO, $oraO) {
    global $ip, $porta;
    //Contenuto
    $tmpGest = unserialize($_SESSION['gestoreOrdini']);
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT c.*, p.* FROM composto c JOIN prodotto p ON(c.idProdotto = p.idProdotto) WHERE c.dataOrdine=\"$dataO\" AND c.oraOrdine=\"$oraO\" AND p.tipo=\"ricetta\"";
      $result = $co->query($sql);
      $out = "<div class=\"text-center font-weight-bolder bg-secondary text-white\">Piatti Ordinati</div>
      <div class=\"row row-cols-1 row-cols-md-3\">";
      if($result->num_rows > 0){
        $ordine = new ordine($dataO,$oraO);
        while($row = $result->fetch_assoc()) {
          $out .= "<div class=\"col mb-4\">
                          <div class=\"card\">
                            <img src=\"../imgFolder/" . $row['img'] . "\" class=\"card-img-top\">
                            <div class=\"card-body\">
                              <h5 class=\"card-title\">" . $row['nomeProdotto'] . "</h5>
                              <p class=\"card-text\">Quantita Richiesta: " . $row['quantitàOrdinata'] . "</p>
                              <p class=\"card-link text-center\"><a href=\"../add_rmv/gestione.php?idP=" . $row['idProdotto'] . "&opz=R&dataO=" . $dataO . "&oraO=" . $oraO . "\"><img src=\"../imgFolder/check.png\" weight=\"50px\" height=\"50px\"></a></p>
                          </div>
                        </div>
                    </div>";
            $ordine->addOrdine($row['idProdotto']);
        }
        $out .= "</div>";

        $sql = "SELECT c.*, p.* FROM composto c JOIN prodotto p ON(c.idProdotto = p.idProdotto) WHERE c.dataOrdine=\"$dataO\" AND c.oraOrdine=\"$oraO\" AND p.tipo=\"articolo\"";
        $result = $co->query($sql);

        if($result->num_rows > 0){
          //ho anche articoli nell'ordine
          $out .= "<div class=\"text-center font-weight-bolder bg-secondary text-white\">Articoli Ordinati</div>
            <div class=\"row row-cols-1 row-cols-md-3\">";
          while($row = $result->fetch_assoc()) {
            $out .= "<div class=\"col mb-4\">
                            <div class=\"card\">
                              <img src=\"../imgFolder/" . $row['img'] . "\" class=\"card-img-top\">
                              <div class=\"card-body\">
                                <h5 class=\"card-title\">" . $row['nomeProdotto'] . "</h5>
                                <p class=\"card-text\">Quantita Richiesta: " . $row['quantitàOrdinata'] . "</p>
                                <p class=\"card-link text-center\"><a href=\"../add_rmv/gestione.php?idP=" . $row['idProdotto'] . "&opz=A&dataO=" . $dataO . "&oraO=" . $oraO . "\"><img src=\"../imgFolder/check.png\" weight=\"50px\" height=\"50px\"></a></p>
                            </div>
                          </div>
                      </div>";
              $ordine->addOrdine($row['idProdotto']);
          }
          $out .= "</div>";
        }//non ho articoli nell'ordine

        if($tmpGest->addOrdineToGestore($ordine)){
          //ok
        }else{
          //else gia inserito
        }

      }else{
        $co->rollBack();
        $co->close();
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

    $_SESSION['gestoreOrdini'] = serialize($tmpGest);
    return $out;
  }

  function buildMoreInfo1($dataO, $oraO) {
    global $ip, $porta;
    $tmpGest = unserialize($_SESSION['gestoreOrdini']);

    foreach ($tmpGest->getGestore() as $el) {
      if($el->dataOrdine == $dataO && $el->oraOrdine == $oraO) {
        $outR = "";
        $outA = "";
        foreach ($el->ordini as $prodotto) {
          try {
            $co = connect();
            $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $sql = "SELECT c.*, p.* FROM composto c JOIN prodotto p ON(c.idProdotto = p.idProdotto) WHERE c.dataOrdine=\"$dataO\" AND c.oraOrdine=\"$oraO\" AND p.idProdotto=\"$prodotto\"";
            $result = $co->query($sql);

            while($row = $result->fetch_assoc()) {
              if($row['tipo'] == "ricetta") {
                $outR .= "<div class=\"col mb-4\">
                                <div class=\"card\">
                                  <img src=\"../imgFolder/" . $row['img'] . "\" class=\"card-img-top\">
                                  <div class=\"card-body\">
                                    <h5 class=\"card-title\">" . $row['nomeProdotto'] . "</h5>
                                    <p class=\"card-text\">Quantita Richiesta: " . $row['quantitàOrdinata'] . "</p>
                                    <p class=\"card-link text-center\"><a href=\"../add_rmv/gestione.php?idP=" . $row['idProdotto'] . "&opz=R&dataO=" . $dataO . "&oraO=" . $oraO . "\"><img src=\"../imgFolder/check.png\" weight=\"50px\" height=\"50px\"></a></p>
                                </div>
                              </div>
                          </div>";
              }else if($row['tipo'] == "articolo") {
                $outA .= "<div class=\"col mb-4\">
                                <div class=\"card\">
                                  <img src=\"../imgFolder/" . $row['img'] . "\" class=\"card-img-top\">
                                  <div class=\"card-body\">
                                    <h5 class=\"card-title\">" . $row['nomeProdotto'] . "</h5>
                                    <p class=\"card-text\">Quantita Richiesta: " . $row['quantitàOrdinata'] . "</p>
                                    <p class=\"card-link text-center\"><a href=\"../add_rmv/gestione.php?idP=" . $row['idProdotto'] . "&opz=A&dataO=" . $dataO . "&oraO=" . $oraO . "\"><img src=\"../imgFolder/check.png\" weight=\"50px\" height=\"50px\"></a></p>
                                </div>
                              </div>
                          </div>";
              }

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
        }

        if($outA != "") {
          $out = "<div class=\"text-center font-weight-bolder bg-secondary text-white\">Piatti Ordinati</div>
          <div class=\"row row-cols-1 row-cols-md-3\">" . $outA . "</div>";
          if($outR != ""){
              $out .= "<div class=\"text-center font-weight-bolder bg-secondary text-white\">Articoli Ordinati</div>
                <div class=\"row row-cols-1 row-cols-md-3\">" . $outR . "</div>";
          }

        }else if($outR != "") {
          $out = "<div class=\"text-center font-weight-bolder bg-secondary text-white\">Articoli Ordinati</div>
            <div class=\"row row-cols-1 row-cols-md-3\">" . $outR . "</div>";
        }else{
          $out = "<p class=\"text-success text-center\">Ordine Completato!<p>";
        }

        break;
      }

    }

    return $out;
  }
?>
