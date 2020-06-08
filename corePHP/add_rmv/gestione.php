<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

  include '../utilityPHP/connessioneDB.php';
  include '../utilityPHP/globalVar.php';
  include '../classPHP/ordine.php';
  include '../classPHP/gestoreOrdine.php';

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
  $tipo = $_GET['opz'];
  if($tipo == "A"){
    //gestisco un articolo
    $tmpGest = unserialize($_SESSION['gestoreOrdini']);
    $dataO = $_GET['dataO'];
    $oraO = $_GET['oraO'];
    $idProdotto = $_GET['idP'];
    foreach($tmpGest->getGestore() as $el) {
      if($el->dataOrdine == $dataO && $el->oraOrdine == $oraO) {

        foreach($el->ordini as $prodotto) {
          if($idProdotto == $prodotto) {
            //eseguo operazioni e finisco per non eseguire operazioni inutili
            try {
              $co = connect();
              $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

              $sql = "SELECT c.*, a.* FROM composto c JOIN prodotto p ON(c.idProdotto=p.idProdotto) JOIN articolo a ON(a.idArticolo=p.idProdotto) WHERE c.idProdotto=\"$idProdotto\" AND c.dataOrdine=\"$dataO\" AND c.oraOrdine=\"$oraO\"";
              $result = $co->query($sql);

              if($result->num_rows <= 0) {
                //non posso continuare
                $_SESSION['controlloQuat'] = "notOK";
                $co->rollBack();
                $co->close();
                header("location: ../indexAutorized.php");
                die();
              }

              while($row = $result->fetch_assoc()) {
                  $quantitàOrd = $row['quantitàOrdinata'];
                  $quantitaDispensa = $row['quantità'];
              }

              if($quantitàOrd <= $quantitaDispensa) {
                //posso continuare
                if(!$el->deleteOrdine((int)$idProdotto))
                  die("Errore: riga 53 gestione.php");

                $newQ = (int)$quantitaDispensa - (int)$quantitàOrd;
                //elimino dal db la quantitaOrdinata
                $sql = "UPDATE articolo SET quantità=\"$newQ\" WHERE idArticolo=\"$idProdotto\"";
                if($co->query($sql) === TRUE) {
                  //ok
                } else {
                  $co->rollBack();
                  $co->close();
                  header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
                  die();
                }

                $_SESSION['controlloQuat'] = "ok";
              }else {
                //non posso continuare
                $_SESSION['controlloQuat'] = "notOK";
                $co->rollBack();
                $co->close();
                header("location: ../indexAutorized.php");
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
              //idem vedi riga->33
            break;
          }

        }
          //idem vedi riga->33
        break;
      }


    }

  }else if($tipo == "R") {
    //gestisco una ricetta->  piatto
    $tmpGest = unserialize($_SESSION['gestoreOrdini']);
    $dataO = $_GET['dataO'];
    $oraO = $_GET['oraO'];
    $idProdotto = $_GET['idP'];
    foreach ($tmpGest->getGestore() as $el) {
      if($el->dataOrdine == $dataO && $el->oraOrdine == $oraO) {
        foreach ($el->ordini as $prodotto) {
          if($idProdotto == $prodotto) {
            //eseguo operazioni e finisco per non eseguire operazioni inutili
            try {
              $co = connect();
              $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

              $sql = "SELECT count(pi.idProdotto) as numPP,c.* FROM piatto_pronto pi JOIN composto c ON(c.idProdotto=pi.idProdotto) WHERE pi.idProdotto=\"$idProdotto\" AND c.dataOrdine=\"$dataO\" AND c.oraOrdine=\"$oraO\" GROUP BY pi.idProdotto";
              $result = $co->query($sql);

              if($result->num_rows <= 0) {
                //non posso continuare
                $_SESSION['controlloQuat'] = "notOK";
                $co->rollBack();
                $co->close();
                header("location: ../indexAutorized.php");
                die();
              }

              while($row = $result->fetch_assoc()) {
                  $quantitaDispensa = $row['numPP'];
                  $quantitàOrd = $row['quantitàOrdinata'];
              }

              if($quantitàOrd <= $quantitaDispensa) {
                //posso continuare

                if(!$el->deleteOrdine((int)$idProdotto))
                  die("Errore: riga 131 gestione.php");

                //prendo dal db quantitaOrdinata righe da tabella piatto_pronto

                $sql = "SELECT pi.* FROM piatto_pronto pi WHERE pi.idProdotto=\"$idProdotto\" LIMIT $quantitàOrd";
                $result = $co->query($sql);

                if($result->num_rows < 0) {
                  die("Errore : riga 120 gestipne.php");
                }

                $appArr = array();
                while($row = $result->fetch_assoc()) {
                    array_push($appArr,  $row['idPiatto']);
                }
                //die("[Z]".print_r($appArr));
                //Elimino $quantitàOrd righe da piatto_pronto
                foreach ($appArr as $piatto) {
                  $sql = "DELETE FROM piatto_pronto WHERE idPiatto=$piatto";
                  if($co->query($sql) === TRUE) {
                    //ok
                  } else {
                    $co->rollBack();
                    $co->close();
                    header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
                    die();
                  }
                }

                $_SESSION['controlloQuat'] = "ok";
              }else {
                //non posso continuare
                $_SESSION['controlloQuat'] = "notOK";
                $co->rollBack();
                $co->close();
                header("location: ../indexAutorized.php");
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
            break;
          }
        }
        //idem vedi riga->33
        break;
      }
    }
  }else {
    header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }

  $_SESSION['gestoreOrdini'] = serialize($tmpGest);
  header("location: ../indexAutorized.php");
?>
