<?php
  session_start();
  include 'utilityPHP/globalVar.php';
  global $ip, $porta;

  if(isset($_SESSION['status']) && $_SESSION['status'] == "true") {
    //Autorized
    $_SESSION['status'] = "";
    $_SESSION['gestoreOrdini'] = "";
    $_SESSION['controlloQuat'] = "";
    session_destroy();
    header("location: https://www.google.it/");
    die();
  }else {
    //NOT Autorized
    header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }
?>
