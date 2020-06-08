<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

  include '../utilityPHP/connessioneDB.php';
  include '../utilityPHP/globalVar.php';
  include '../classPHP/gestoreOrdine.php';
  include '../classPHP/ordine.php';
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
  $dataO = $_GET['dataOrdine'];
  $oraO = $_GET['oraOrdine'];
  $tmpG = unserialize($_SESSION['gestoreOrdini']);

  foreach($tmpG->getGestore() as $el) {
    if($el->dataOrdine == $dataO && $el->oraOrdine == $oraO) {
      if(sizeof($el->ordini) > 0) {
        $_SESSION['updSts'] = "illegal";
        header("location: ../indexAutorized.php");
        die();
      }
      $rs = true;
      break;
    }else{
      $rs = false;
    }
  }

  if(!$rs) {
    $_SESSION['updSts'] = "illegal";
    header("location: ../indexAutorized.php");
    die();
  }

  try {
    $co = connect();
    $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

    $sql = "UPDATE ordine SET stato=\"fatto\" WHERE oraOrdine=\"$oraO\" AND dataOrdine=\"$dataO\"";

    if($co->query($sql) === TRUE) {
      //ok
      $_SESSION['updSts'] = "ok";
    } else {
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

  header("location: ../indexAutorized.php");
  die();
?>
