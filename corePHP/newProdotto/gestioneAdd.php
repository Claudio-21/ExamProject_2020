<?php
//Controllo nel caso ci sia qualche hacker
  session_start();
  include '../utilityPHP/connessioneDB.php';
  include '../utilityPHP/globalVar.php';
  global $ip, $porta, $erFormato, $erFormato, $erListaI, $erTempo, $newPOK;

  if(isset($_SESSION['status']) && $_SESSION['status'] == "true") {
    //Autorized

  }else {
    //NOT Autorized
    header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Acesso Negato<br>Contattre l'amministratore ...");
    die();
  }

  $nomeProdotto = $_POST['nomeProdotto'];
  $prezzoProdotto = $_POST['prezzoProdotto'];
  $tipoPodotto = $_POST['tipoProdotto'];
  //Controllo se devo aggiungere al db una ricetta o un articolo
  if($tipoPodotto == "Ricetta") {
    //Aggiungo una ricetta
    $listaI = $_POST['list'];
    $tempo = $_POST['tempoRichiesto'];
    if(!controllaInRicetta($listaI)) {
      $_SESSION['listaOK'] = $erListaI;
      header("location: addProdotto.php");
      die();
    }

    if(!controllaTempo($tempo)) {
      $_SESSION['tempoOK'] = $erTempo;
      header("location: addProdotto.php");
      die();
    }
    //Info dei file inseriti
    $ricettaName = $_FILES['ricetta']['name'];
    $ricettaSize = $_FILES['ricetta']['size'];
    $imgName = $_FILES['img']['name'];
    //inserimento nel db
    addRicetta($ip, $porta, $nomeProdotto, $prezzoProdotto, $listaI, $ricettaName, $ricettaSize, $imgName, $tempo);
    //Carico file sul server
    $filename = $_FILES['ricetta']['name'];

    // destination of the file on the server
    $destination = '../uploads/' . $filename;
    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['ricetta']['tmp_name'];
    $size = $_FILES['ricetta']['size'];

    if (!in_array($extension, ['pdf', 'docx', 'txt'])) {
      $_SESSION['ricettaOK'] = $erFormato;
       header("location: addProdotto.php");
       die();
    } elseif ($_FILES['ricetta']['size'] >30000000) { // file shouldn't be larger than 1Megabyte
       $_SESSION['ricettaOK'] = $erFormato;
       header("location: addProdotto.php");
       die();
    } else {
     // move the uploaded (temporary) file to the specified destination
    if (move_uploaded_file($file, $destination)) {
      //Query per inserire il file in allegato al prodotto di tipo Ricetta

     } else {
       $_SESSION['ricettaOK'] = $erGen;
       header("location: addProdotto.php");
       die();
     }
    }

    $filename = $_FILES['img']['name'];

    // destination of the file on the server
    $destination = '../imgFolder/' . $filename;
    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['img']['tmp_name'];
    $size = $_FILES['img']['size'];
    if (!in_array($extension, ['jpg', 'png', 'jpeg'])) {
      $_SESSION['imgOK'] = $erFormato;
       header("location: addProdotto.php");
       die();
    } elseif ($_FILES['img']['size'] >30000000) { // file shouldn't be larger than 1Megabyte
       $_SESSION['imgOK'] = $erFormato;
       header("location: addProdotto.php");
       die();
    } else {
     // move the uploaded (temporary) file to the specified destination
    if (move_uploaded_file($file, $destination)) {
      //Query per inserire il file in allegato al prodotto di tipo Ricetta

     } else {
       $_SESSION['imgOK'] = $erGen;
       header("location: addProdotto.php");
       die();
     }
    }

    $_SESSION['newProdottoOK'] = $newPOK;
    header("location: addProdotto.php");
    die();
  } else {
    //Aggiungo un prodotto
    //Per inserire i dati nel db
    $nomeImgArt = $_FILES['img']['name'];
    $sizeImgArt = $_FILES['img']['size'];
    //Inserisco i dati nel db
    addArticolo($ip, $porta, $nomeProdotto, $prezzoProdotto, $nomeImgArt, $sizeImgArt);
    //Metto il file img sul server
    $filename = $_FILES['img']['name'];

    // destination of the file on the server
    $destination = '../imgFolder/' . $filename;
    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['img']['tmp_name'];
    $size = $_FILES['img']['size'];
    if (!in_array($extension, ['jpg', 'png', 'jpeg'])) {
      $_SESSION['imgOK'] = $erFormato;
       header("location: addProdotto.php");
       die();
    } elseif ($_FILES['img']['size'] >30000000) { // file shouldn't be larger than 1Megabyte
       $_SESSION['imgOK'] = $erFormato;
       header("location: addProdotto.php");
       die();
    } else {
     // move the uploaded (temporary) file to the specified destination
    if (move_uploaded_file($file, $destination)) {
      //Query per inserire il file in allegato al prodotto di tipo Ricetta

     } else {
       $_SESSION['imgOK'] = $erGen;
       header("location: addProdotto.php");
       die();
     }
    }

    $_SESSION['newProdottoOK'] = $newPOK;
    header("location: addProdotto.php");
    die();
  }

  function addArticolo($ip, $porta, $nomeProdotto, $prezzoProdotto, $nomeImgArt, $sizeImgArt) {
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
//Inserisco nella tabella file il link all'articolo
      $sql = "INSERT INTO file (nomeFile, sizeFile)
      VALUES ('$nomeImgArt', '$sizeImgArt')";
      //eseguo query
      if ($co->query($sql) === TRUE) {
        //ok
      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Recupero id file per inserire dopo nella tabella prodotto
      $sql = "SELECT * FROM file WHERE nomeFile = \"$nomeImgArt\"";
      $result = $co->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          $idFile = $row['idFile'];
        }

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Inserisco nella tabella prodotto il nuovo articolo
      $sql = "INSERT INTO prodotto (tipo,nomeProdotto,prezzo, idFile)
      VALUES ('Articolo', '$nomeProdotto', '$prezzoProdotto', '$idFile')";

      if ($co->query($sql) === TRUE) {

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Recupero idArticolo appena inserita
      $sql = "SELECT * FROM prodotto WHERE nomeProdotto = \"$nomeProdotto\"";
      $result = $co->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          $idArticolo = $row['idProdotto'];
        }

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Inserisco dentro tabella articolo
      $sql = "INSERT INTO articolo (idArticolo,quantità)
      VALUES ('$idArticolo', '0')";

      if ($co->query($sql) === TRUE) {

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Se arrivo qui fine transazione faccio la commit tutto ok
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

  }

  function addRicetta($ip, $porta, $nomeProdotto, $prezzoProdotto, $ingredienti, $nomeF, $sizeF, $nomeImg, $tempo) {
    try {
      $co = connect();
      $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
//Inserisco dentro tabella file
      $sql = "INSERT INTO file (nomeFile, sizeFile)
      VALUES ('$nomeF', '$sizeF')";

      if ($co->query($sql) === TRUE) {

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Recupero idFile appena inserito
      $sql = "SELECT * FROM file WHERE nomeFile = \"$nomeF\"";
      $result = $co->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          $idFile = $row['idFile'];
        }

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

//Inserisco dentro tabella prodotto
      $sql = "INSERT INTO prodotto (tipo,nomeProdotto,prezzo,img, tempoRichiesto, idFile)
      VALUES ('Ricetta', '$nomeProdotto', '$prezzoProdotto', '$nomeImg', '$tempo', '$idFile')";

      if ($co->query($sql) === TRUE) {

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Recupero idRicetta appena inserita
      $sql = "SELECT * FROM prodotto WHERE nomeProdotto = \"$nomeProdotto\"";
      $result = $co->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          $idRicetta = $row['idProdotto'];
        }

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }
//Inserisco ingredienti dentro tabella ingrediente
      $sql = "INSERT INTO ingrediente (idDispensa, quantitàInDispensa, nomeIngrediente, descrizioneIngrediente) VALUES ";
      $arrayQuantitàUsata = array();
      $arrayNomi = array();
      $arrayI = explode(".", $ingredienti);
      $lastElem = count($arrayI);
      $z = 1;
      foreach($arrayI as $el) {
        $app = explode(";", $el);/*Nome, quantitàUsata, Descrizione*/
        if($z == $lastElem){
          $sql .= "('1', '0'";
          $i = 1;
          foreach ($app as $value) {
            switch($i){
              case 1:
                $sql .= ",'$value'";
                array_push($arrayNomi,"". $value);
              break;

              case 2:
                array_push($arrayQuantitàUsata,"". $value);
              break;

              case 3:
                $sql .= ",'$value');";
              break;
            }
            $i ++;
          }
        }else{
          $sql .= "('1', '0'";
          $i = 1;
          foreach ($app as $value) {
            switch($i){
              case 1:
                $sql .= ",'$value'";
                array_push($arrayNomi,"". $value);
              break;

              case 2:
                array_push($arrayQuantitàUsata,"". $value);
              break;

              case 3:
                $sql .= ",'$value'),";
              break;
            }
            $i ++;
          }
        }
        $z++;
      }

      if ($co->query($sql) === TRUE) {

      } else {
        $co->rollBack();
        $co->close();
        header("location: http://" . $ip . ":" . $porta . "/corePHP/errorePage/errore.php?msg=Si è verificato un imprevisto<br>Contattre l'amministratore ...");
        die();
      }

//Recupero gli id degli ingredienti appena inseriti
      $arrayIdIng = array();
      foreach ($arrayNomi as $nomeIng) {
        $sql = "SELECT * FROM ingrediente WHERE nomeIngrediente = \"$nomeIng\"";
        $result = $co->query($sql);

        while($row = $result->fetch_assoc()) {
            array_push($arrayIdIng,"". $row['idIngrediente']);
        }

      }

//Inserisco dentro tabella usato
      $sql = "INSERT INTO usato (idIngrediente,idProdotto,quantitàUsata) VALUES";

      for($i = 0; $i < count($arrayIdIng); $i++) {
          if($i == count($arrayIdIng) -1){
            //ultimo value
            $sql .= "(\"" . $arrayIdIng[$i] . "\",\"" . $idRicetta . "\",\"" . $arrayQuantitàUsata[$i] . "\");";
          }else{
            $sql .= "(\"" . $arrayIdIng[$i] . "\",\"" . $idRicetta . "\",\"" . $arrayQuantitàUsata[$i] . "\"),";
          }

      }

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
  }

  function controllaInRicetta($listaI) {
    if($listaI == "") {
      return false;
    }else {
      $arrayI = explode(".", $listaI);
      foreach($arrayI as $el) {
        $app = explode(";", $el);
        if($app == false)
          return false;
        else if(count($app) != 3)
          return false;

      }
    }

    return true;
  }

    function controllaTempo($tempo) {
      $arrayT = explode(":", $tempo);
      if(count($arrayT) != 3)
        return false;
      else
        return true;
    }
?>
