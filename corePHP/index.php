<?php
  include 'utilityPHP\utilFunction.php';

  $arrIP = explode(".", vero_ip());
  /*if(controllIP($arrIP)){
    //Indirizzo IP approvato*/
    session_start();
    //Varibaili per vari controlli !important
    $_SESSION['status'] = "true";
    $_SESSION['ricettaOK'] = "";
    $_SESSION['imgOK'] = "";
    $_SESSION['listaOK'] = "";
    $_SESSION['tempoOK'] = "";
    $_SESSION['newProdottoOK'] = false;
    $_SESSION['addOK'] = false;
    header("location: indexAutorized.php");
    die();
  /*}else{
    //Indirizzo IP respinto
    die("Servizio non disponibile ...");
  }*/
?>
