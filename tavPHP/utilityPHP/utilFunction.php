<?php
  include 'globalVar.php';
  include 'connessioneDB.php';
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

  function getNumeroTavolo($deprecated) {
    global $ip, $porta;
    //$ip = vero_ip();
    $ipS = $deprecated;
    $out = "";
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT t.*
      FROM tavolo t WHERE t.idTavolo = \"$ipS\"";

      $result1 = $co->query($sql);
      //Creo le varie update degli ingredienti
      if ($result1->num_rows > 0) {

        while($row1 = $result1->fetch_assoc()) {
          $app = explode(".",$row1['idTavolo']);
          $out = "" . (int)$app[3] - 1;
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
