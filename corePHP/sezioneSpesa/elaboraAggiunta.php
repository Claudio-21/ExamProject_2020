<?php
//Controllo nel caso ci sia qualche hacker
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

  $newQuantita = $_POST['oldQ'] + $_POST['quantitàAdd'];
  if($_POST['type'] == "A") {
    //Articolo
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
      $id = $_POST['id'];
      $sql = "UPDATE articolo SET quantità=$newQuantita WHERE idArticolo=$id";

      if ($co->query($sql) === TRUE) {

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
    
    $_SESSION['addOK'] = true;
    header("location: spesa.php");
    die();
  }else if($_POST['type'] == "I"){
    //Ingrediente
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
      $id = $_POST['id'];
      $sql = "UPDATE ingrediente SET quantitàInDispensa=$newQuantita WHERE idIngrediente=$id";

      if ($co->query($sql) === TRUE) {

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

    $_SESSION['addOK'] = true;
    header("location: spesa.php");
    die();
  }


?>
