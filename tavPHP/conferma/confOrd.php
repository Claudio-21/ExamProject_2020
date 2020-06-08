<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

  include '../utilityPHP/globalVar.php';
  include '../utilityPHP/utilFunction.php';
  include '../carrello/carrello.php';
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
    $pointerToC = unserialize($_SESSION['carrello']);
    $ordini = $pointerToC->getOrdini();
    $arrayNumb = array();
    $idPAr = noDoubleInArray($pointerToC->getOrdini(), $pointerToC);
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $data = date("Y-m-d");
      $ora = date("H:i:s");
      //  $ipTav = vero_ip();
      $ipTav = "192.168.2.3";
      $sql = "INSERT INTO ordine(oraOrdine,dataOrdine,idTavolo, stato) VALUES (\"$ora\", \"$data\", \"$ipTav\", \"in_esecuzione\")";

      if ($co->query($sql) === TRUE) {
        //ok
      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

      $sql = "INSERT INTO composto(dataOrdine,oraOrdine,quantitàOrdinata, idProdotto) VALUES ";
      for($i = 0; $i < sizeof($idPAr); $i++) {
        if($i != (sizeof($idPAr) - 1))
          $sql .= "(\"$data\", \"$ora\",\"" . $arrayNumb[$i] . "\",\"" . $idPAr[$i] . "\"),";
        else
          $sql .= "(\"$data\", \"$ora\",\"" . $arrayNumb[$i] . "\",\"" . $idPAr[$i] . "\");";
      }

      if ($co->query($sql) === TRUE) {
        //ok
      } else {
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

      $_SESSION['stsOrd'] = "conf";
      $_SESSION['carrello'] = "";
      header("location: ../indexAutorized.php");
      die();
    } catch (Exception $e) {
    $co->rollBack();
    $co->close();
    header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
    die();
    }


    function noDoubleInArray($ar, $objC) {
      global $arrayNumb;
      $outAr = array();

      foreach($ar as $el) {
        if(!in_array((int)$el, $outAr)) {
          //ancora non controllato
          array_push($outAr, (int)$el);

          $app = $objC->countQuantitaOrdinata((int)$el);
          array_push($arrayNumb, (int)$app);
        }

      }

      return $outAr;
    }
?>
