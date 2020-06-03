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
?>

<?php

  function bulidStatoDispensa($q_min) {
    global $ip, $porta;
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT i.* FROM ingrediente i join dispensa d ON(i.idDispensa = d.idDispensa)";
      $result = $co->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        //Intestazione tabella
        $out = "<table class=\"table\">
          <thead class=\"thead bg-secondary text-white\">
            <tr>
              <th scope=\"col\">Nome Ingrediente</th>
              <th scope=\"col\">Descrizione</th>
              <th scope=\"col\">Quantità (grammi)</th>
            </tr>
          </thead>
          <tbody id=\"myTable\">";

        while($row = $result->fetch_assoc()) {
          if($row['quantitàInDispensa'] == 0) {
            //Quantità scarsa
            $out .= "<tr class=\"bg-danger text-white\">
              <td>" . $row['nomeIngrediente'] . "</td>
              <td>" . $row['descrizioneIngrediente'] . "</td>
              <td>" . $row['quantitàInDispensa'] . "</td
            </tr>";
          } else if($row['quantitàInDispensa'] <= $q_min) {
            //Quantità assente
            $out .= "<tr class=\"bg-warning text-dark\">
              <td >" . $row['nomeIngrediente'] . "</td>
              <td>" . $row['descrizioneIngrediente'] . "</td>
              <td>" . $row['quantitàInDispensa'] . "</td
            </tr>";
          } else {
            //Quantità apposto
            $out .= "<tr class=\"bg-success text-white\">
              <td >" . $row['nomeIngrediente'] . "</td>
              <td>" . $row['descrizioneIngrediente'] . "</td>
              <td>" . $row['quantitàInDispensa'] . "</td
            </tr>";
          }

        }

        $out .= "
          </tbody>
        </table>";
      } else {
          $out = "<h1 class=\"text-danger\">NESSUN INGREDIENTE TROVATO IN DISPENSA</h1>";
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

    return $out;
  }
?>
