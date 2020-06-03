<?php
//Per vari redirect
  $ip = $_SERVER['SERVER_NAME'];  //server per vedere sei sei localhost o hai un ip
  $porta = $_SERVER['SERVER_PORT'];   //porta del serve, perchè c'è chi ha 80, chi 8080 etc...
//CONTROLLO IP
  $IPpt1 = "192";
  $IPpt2 = "168";
  $IPpt3 = "1";
//PARAMETRI CONNESSIONE DATABASE
  $servername = "localhost";
  $usernameDb = "root";
  $passwordDb = "";
  $dbNome = "ristorantecov";
//Quantita
  $quantitaMIN = 5000;
  $qm1 = 200;
  $qm2 = 500;
  $qm3 = 1000;
  $qm4 = 3000;
  $qm5 = 5000;
//PARAMETRI CONTROLLO input
  $erFormato = "erFormato";
  $erSize = "erSize";
  $erGen = "erGen";
  $erListaI = "erListaI";
  $erTempo = "erTempo";
  $newPOK = true;
?>
