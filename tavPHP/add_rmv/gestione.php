<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

  include '../utilityPHP/globalVar.php';
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
  $idProdotto = $_GET['idP'];
  $pointToC = unserialize($_SESSION['carrello']);

  if($_GET['opz'] == "A") {
    //add
    $pointToC->addOrdine((int)$idProdotto);
    $_SESSION['stsOrd'] = "add";
  }else if($_GET['opz'] == "R") {
    //remove
    if($pointToC->deleteOrdine((int)$idProdotto))
      $_SESSION['stsOrd'] = "rmvOk";
    else
      $_SESSION['stsOrd'] = "rmvNOTok";
  }

  $_SESSION['carrello'] = serialize($pointToC);
  header("location: ../indexAutorized.php");
  die();
?>
