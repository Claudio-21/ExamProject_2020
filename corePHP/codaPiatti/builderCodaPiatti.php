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
?>
<?php
  function bulidCodaPiatti() {
    //Intestazione tabella
      $out = "<table class=\"table\">
        <thead class=\"thead bg-dark text-white\">
          <tr>
            <th scope=\"col\">Nome Ricetta</th>
            <th scope=\"col\">Quantità (piatti)</th>
            <th scope=\"col\">ADD</th>
          </tr>
        </thead>
      <tbody id=\"myTable\">";

      global $ip, $porta;
      try {
        $co = connect();
        $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $sql = "SELECT p.idProdotto, i.idIngrediente, i.quantitàInDispensa, u.quantitàUsata
          FROM prodotto p JOIN usato u ON(p.idProdotto = u.idProdotto)
            JOIN ingrediente i ON(i.idIngrediente = u.idIngrediente) WHERE p.tipo = \"ricetta\"
          ORDER BY p.idProdotto ASC";

        $result1 = $co->query($sql);
        $result2 = $co->query($sql);

        if ($result1->num_rows > 0) {
            // output data of each row
          $idScarto = array();
          $idProdottiOk = array();
          while($row1 = $result1->fetch_assoc()) {
            if(!in_array($row1['idProdotto'], $idScarto))
              if($row1['quantitàUsata'] > $row1['quantitàInDispensa']) {
                array_push($idScarto, $row1['idProdotto']);
              }
            //else gia scartatto
          }

          while($row2 = $result2->fetch_assoc()) {
            if(!in_array($row2['idProdotto'], $idScarto)) {
              array_push($idProdottiOk, $row2['idProdotto']);
            }

          }

        }else{
          $co->rollBack();
          $co->close();
          header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
          die();
        }

          $sql = "SELECT COUNT(pi.idPiatto) as numPiatti, p.*, pi.*
          FROM prodotto p JOIN piatto_pronto pi ON(p.idProdotto = pi.idProdotto)
          WHERE p.tipo=\"ricetta\"
          GROUP BY pi.idProdotto
          ORDER BY numPiatti ASC";
          $result = $co->query($sql);

          if ($result->num_rows > 0) {
            // output data of each row

            //Contenuto tabella
            while($row = $result->fetch_assoc()) {
              $app = false;
              foreach ($idProdottiOk as $idOK) {
                  if($idOK == $row['idProdotto']) {
                    //ok
                    $out .= "<tr class=\"bg-secondary text-white\">
                      <td>" . $row['nomeProdotto'] . "</td>
                      <td>" . $row['numPiatti'] . "</td>
                      <td><a href=\"addPiattoPronto.php?idProdotto=" . $row['idProdotto'] . "\"><img class=\"rounded\" width=\"30px\" height=\"30px\" src=\"../imgFolder/addIco.png\"></img></a></td>
                    </tr>";
                    $app = true;
                    break;
                  }else
                    $app = false;
                    //mancanza ingredienti
              }

              if(!$app) {
                $out .= "<tr class=\"bg-secondary text-white\">
                  <td>" . $row['nomeProdotto'] . "</td>
                  <td>" . $row['numPiatti'] . "</td>
                  <td>MANCANZA INGREDIENTI</td>
                </tr>";
              }

            }

          }

          //Aggiungo tutte le ricette tranne quelle che sono già pronte
          $sql = "SELECT p.*
          FROM prodotto p
          WHERE p.tipo=\"ricetta\" AND p.idProdotto NOT IN ( SELECT p.idProdotto FROM prodotto p JOIN piatto_pronto pi ON(pi.idProdotto = p.idProdotto) WHERE p.tipo=\"ricetta\")";
          $result = $co->query($sql);

          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              $app = false;
              $i = 0;
              foreach ($idProdottiOk as $idOK) {
                  if($idOK == $row['idProdotto']) {
                    //ok
                    $out .= "<tr class=\"bg-secondary text-white\">
                      <td>" . $row['nomeProdotto'] . "</td>
                      <td>0</td>
                      <td><a href=\"addPiattoPronto.php?idProdotto=" . $row['idProdotto'] . "\"><img class=\"rounded\" width=\"30px\" height=\"30px\" src=\"../imgFolder/addIco.png\"></img></a></td>
                    </tr>";
                    $app = true;
                    $i ++;
                    break;
                  }
                  //mancanza ingredienti
                  $i ++;
              }

              if(!$app && $i != 0) {
                $out .= "<tr class=\"bg-secondary text-white\">
                  <td>" . $row['nomeProdotto'] . "</td>
                  <td>0</td>
                  <td>MANCANZA INGREDIENTI</td>
                </tr>";
              }else if($i == 0){
                $out .= "<tr class=\"bg-secondary text-white\">
                  <td>" . $row['nomeProdotto'] . "</td>
                  <td>0</td>
                  <td><a href=\"addPiattoPronto.php?idProdotto=" . $row['idProdotto'] . "\"><img class=\"rounded\" width=\"30px\" height=\"30px\" src=\"../imgFolder/addIco.png\"></img></a></td>
                </tr>";
              }

            }

          }

          //Chiusura tabella
          $out .= "</tbody>
          </table>";



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

      return $out;
  }

?>
