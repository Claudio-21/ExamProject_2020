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
  function bulidSpesaPage() {
    $out = "
      <p class=\"font-weight-bold text-center text-dark\">Articoli</p>
      <hr class=\"my-4 bg-dark\">
      <div class=\"row justify-content-center\">" . buildArticoli() . "</div>";
    $out .= "<br><p class=\"font-weight-bold text-center text-dark\">Ingredienti</p>
    <hr class=\"my-4 bg-dark\">
    <div class=\"row justify-content-center\">" . buildIngredienti() . "</div>";
    return $out;
  }

  function buildArticoli() {
    global $ip, $porta;
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT a.*, p.*, f.* FROM prodotto p JOIN articolo a ON(p.idProdotto = a.idArticolo) JOIN file f ON(p.idFile = f.idFile) WHERE tipo=\"articolo\"";
      $result = $co->query($sql);

      $out = "";
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $out .= "
              <div class=\"col-sm\">
                <div class=\"card\" style=\"width: 10rem;\">
                  <img src=\"../imgFolder/articolo.jpg\" class=\"card-img-top\">
                  <div class=\"card-body text-center\">
                    <h5 class=\"card-title\">" . $row['nomeProdotto'] . "</h5>
                    <a href=\"addToDispensa.php?idProdotto=" . $row['idProdotto'] . "&type=A\" ><img src=\"../imgFolder/add.png\" width=\"75px\" height=\"50px\"></a>
                  </div>
                </div>
              </div>";
        }
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

    return $out;
  }

  function buildIngredienti() {
    global $ip, $porta;
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT i.* FROM ingrediente i JOIN dispensa d ON(i.idDispensa = d.idDispensa)";
      $result = $co->query($sql);

      $out = "";
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $out .= "
              <div class=\"col-sm\">
                <div class=\"card\" style=\"width: 10rem;\">
                  <img src=\"../imgFolder/ingrediente.jpg\" class=\"card-img-top\">
                  <div class=\"card-body text-center\">
                    <h5 class=\"card-title\">" . $row['nomeIngrediente'] . "</h5>
                    <a href=\"addToDispensa.php?idProdotto=" . $row['idIngrediente'] . "&type=I\"><img src=\"../imgFolder/add.png\" width=\"75px\" height=\"50px\"></a>
                  </div>
                </div>
              </div>";
        }
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

    return $out;
  }

  function buildAddToDispensaV($idProdotto, $type) {
    if($type == "I")
      return buildMI($idProdotto);
    else if($type == "A")
      return buildMA($idProdotto);

  }

  function buildMI($idIngrediente){
    global $ip, $porta;
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT i.* FROM ingrediente i WHERE i.idIngrediente=$idIngrediente";
      $result = $co->query($sql);

      $out = "";
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $out .= "
              <div class=\"col-sm-2\">
                <div class=\"card\" style=\"width: 10rem;\">
                  <img src=\"../imgFolder/ingrediente.jpg\" class=\"card-img-top\">
                  <div class=\"card-body text-center\">
                    <h5 class=\"card-title\">" . $row['nomeIngrediente'] . "</h5>
                  </div>
                </div>
              </div>";
            $out .= "<div class=\"col-sm-3\">
              <form class=\"myForm\" method=\"POST\" action=\"elaboraAggiunta.php\">
                 <div class=\"form-group\">
                   <label for=\"nRic\">Aggiungi quantità</label>
                   <input type=\"number\" class=\"form-control\" name=\"quantitàAdd\" id=\"nRic\" min=\"10\" placeholder=\"Inserisci quantita in gr\" required>
                 </div>
                 <div class=\"form-group\">
                   <input class=\"btn btn-primary\" type=\"submit\" placeholder=\"ADD\">
                 </div>
                 <input type=\"hidden\" name=\"oldQ\" value=\"" . $row['quantitàInDispensa'] . "\">
                 <input type=\"hidden\" name=\"id\" value=\"" . $idIngrediente . "\">
                 <input type=\"hidden\" name=\"type\" value=\"I\">
              </form>
            </div>";
        }
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

    return $out;
  }

  function buildMA($idArticolo){
    global $ip, $porta;
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

      $sql = "SELECT p.*, a.*, f.* FROM prodotto p JOIN articolo a ON(p.idProdotto = a.idArticolo) JOIN file f ON(f.idFile = p.idFile) WHERE p.idProdotto=$idArticolo";
      $result = $co->query($sql);

      $out = "";
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $out .= "
              <div class=\"col-sm-2\">
                <div class=\"card\" style=\"width: 10rem;\">
                  <img src=\"../imgFolder/" . $row['nomeFile'] . "\" class=\"card-img-top\">
                  <div class=\"card-body text-center\">
                    <h5 class=\"card-title\">" . $row['nomeProdotto'] . "</h5>
                  </div>
                </div>
              </div>";
            $out .= "<div class=\"col-sm-3\">
              <form class=\"myForm\" method=\"POST\" action=\"elaboraAggiunta.php\">
                 <div class=\"form-group\">
                   <label for=\"nRic\">Aggiungi quantità</label>
                   <input type=\"number\" class=\"form-control\" name=\"quantitàAdd\" id=\"nRic\" min=\"1\" placeholder=\"Inserisci numero di prodotti \" required>
                 </div>
                 <div class=\"form-group\">
                   <input class=\"btn btn-primary\" type=\"submit\" placeholder=\"ADD\">
                 </div>
                 <input type=\"hidden\" name=\"oldQ\" value=\"" . $row['quantità'] . "\">
                 <input type=\"hidden\" name=\"id\" value=\"" . $idArticolo . "\">
                 <input type=\"hidden\" name=\"type\" value=\"A\">
              </form>
            </div>";
        }
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

    return $out;
  }
?>
