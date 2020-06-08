<?php
//Controllo nel caso ci sia qualche hacker
  if(!isset($_SESSION['status']))
    session_start();

  include '../utilityPHP/globalVar.php';
  include 'carrello.php';
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
  $arrayNumb = array();
  $total = 0;

  function buildRecap(){
    $pointerToC = unserialize($_SESSION['carrello']);
    $idPAr = noDoubleInArray($pointerToC->getOrdini(), $pointerToC);
    global $ip, $porta, $arrayNumb, $total;

    $out = "";
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
      //Intestazione
      $out = "<table class=\"table\">
        <thead class=\"thead-dark\">
          <tr>
            <th scope=\"col\">Nome Prodotto</th>
            <th scope=\"col\">Prezzo</th>
            <th scope=\"col\">Quantità</th>
          </tr>
        </thead>
        <tbody>";
      //body
      for($i = 0; $i < sizeof($idPAr); $i ++) {
        $sql = "SELECT p.* FROM prodotto p WHERE p.idProdotto=\"$idPAr[$i]\"";
        $result = $co->query($sql);

        while($row = $result->fetch_assoc()) {
          $app = floatval($row['prezzo']) * floatval($arrayNumb[$i]);
          $out .= "<tr>
            <td>" . $row['nomeProdotto'] . "</th>
            <td class=\"font-weight-bolder\">" . $app . "€</td>
            <td>" . $arrayNumb[$i] . "</td>
          </tr>";
          $total += $app;
        }
      }
      //Chiusura
      $out .= "</tbody>
      </table>";

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

  function noDoubleInArray($ar, $objC) {
    global $arrayNumb;
    $outAr = array();

    foreach($ar as $el) {
      if(!in_array((int)$el, $outAr)) {
        //ancora non controllato
        array_push($outAr, (int)$el);

        $app = $objC->countQuantitaOrdinata((int)$el);
        array_push($arrayNumb, (int)$app);
      }

    }

    return $outAr;
  }

  function getTot() {
    global $total;
    return $total;
  }
?>
