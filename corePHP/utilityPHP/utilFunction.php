<?php
  require_once 'globalVar.php';

//Funzione per scoprire l'indirizzo ip dell'host ...
  function vero_ip() {
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else{
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }

//Funzione per controllare se l'ip dell'host scoperto coincide con quelli approvati
  function controllIP($arrIP) {
    global $IPpt1, $IPpt2, $IPpt3;
    $iter = 0;
    $result = true;
    foreach ($arrIP as $value) {
      if($value == $IPpt1 && $iter == 0) {
        $iter ++;
      }else if($value == $IPpt2 && $iter == 1) {
        $iter ++;
      }else if($value == $IPpt3 && $iter == 2) {
        $iter ++;
        $result = true;
        break;
      }else {
        $result = false;
        break;
      }

    }

    return $result;
  }
?>
