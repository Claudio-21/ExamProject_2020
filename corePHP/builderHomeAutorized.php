<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

  include 'utilityPHP/connessioneDB.php';
  include 'utilityPHP/globalVar.php';

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
  function buildHomeAutorized() {
    global $ip, $porta;
    //Intestazione
    $out = "<table class=\"table\">
    <thead class=\"thead-dark\">
      <tr>
        <th scope=\"col\">Tavolo</th>
        <th scope=\"col\">Ora Ordine</th>
        <th scope=\"col\">Lista Ordine</th>
        <th scope=\"col\">Stato</th>
      </tr>
    </thead>
    <tbody id=\"myTable\">";
    //Contenuto
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
      $dataOggi = date("Y-m-d");
      $sql = "SELECT o.*, t.* FROM ordine o JOIN tavolo t ON(o.idTavolo = t.idTavolo) WHERE o.dataOrdine=\"$dataOggi\" AND o.stato=\"in_esecuzione\" ORDER BY o.oraOrdine ASC";
      $result = $co->query($sql);
      if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
          $out .= "<tr>
            <td>" . getNTavolo($row['idTavolo']) . "</td>
            <td>" . $row['oraOrdine'] . "</td>
            <td><a href=\"moreInfoOrdine/moreInfoOrdine.php?dataOrdine=" . $row['dataOrdine'] . "&oraOrdine=" . $row['oraOrdine'] . "\">Visualizza</a></td>
            <td><a href=\"modificaStatoOrdine/dbCheckMdOrdine.php?dataOrdine=" . $row['dataOrdine'] . "&oraOrdine=" . $row['oraOrdine'] . "\">" . $row['stato'] . "</a></td>
          </tr>";
        }

      }else{
        $out = "<h4 class=\"font-weight-bolder text-primary text-center\">Nessun ordine effettuato fino a ora!<h4>";
        $co->rollBack();
        $co->close();
        return $out;
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
    //End table
    $out .= "</tbody>
    </table>";

    return $out;
  }

  function getNTavolo($idTavolo) {
    $tmp = explode(".", $idTavolo);
    return (int)$tmp[3] - 1;
  }
?>
