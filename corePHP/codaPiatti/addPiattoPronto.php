<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
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

  global $ip, $porta;
  try {
    $co = connect();
    $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    $idP = $_GET['idProdotto'];

    $sql = "SELECT i.idIngrediente, i.quantitàInDispensa, u.quantitàUsata
      FROM prodotto p JOIN usato u ON(p.idProdotto = u.idProdotto)
        JOIN ingrediente i ON(i.idIngrediente = u.idIngrediente) WHERE p.idProdotto = \"$idP\"
      ORDER BY i.idIngrediente ASC";

    $result1 = $co->query($sql);
    //Creo le varie update degli ingredienti
    $sql = "";

    if ($result1->num_rows > 0) {

      while($row1 = $result1->fetch_assoc()) {
        $qNew = (int)$row1['quantitàInDispensa'] - (int)$row1['quantitàUsata'];
        $sql .= "UPDATE ingrediente SET quantitàInDispensa=\"$qNew\" WHERE idIngrediente=".$row1['idIngrediente'].";";
      }

    }else{
      $co->rollBack();
      $co->close();
      header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
      die();
    }


    //Eseguo le update multiple
    $app = explode(";", $sql);
    foreach ($app as $qry) {
      if($qry != ""){
        if ($co->query($qry) === TRUE) {
          //ok
        } else {
          die("Not funny");
          $co->rollBack();
          $co->close();
          header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
          die();
        }
      }
    }

    //Inserisco il piatto pronto
    $sql = "INSERT INTO piatto_pronto (idProdotto)
    VALUES ('$idP')";
    //eseguo query
    if ($co->query($sql) === TRUE) {
      //ok
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

    header("location: codaPiattiPronti.php");
    die();
?>
