<?php
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

  // Downloads files
  if (isset($_GET['idFile'])) {
      $id = $_GET['idFile'];
      // fetch file to download from database
      try {
        $co = connect();
        $co->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $sql = "SELECT file.* FROM file WHERE file.idFile=\"$id\" ";

        $result = $co->query($sql);
        if ($result->num_rows == 0) {
          //La query NON HA estrapolato righe ...
          $co->rollBack();
          $co->close();
          header("Location: http://" .$ip .":" .$porta ."/corePHP/errorePage/errore.php?msg=Il file da lei selezionato non è stato trovato");
          die();
        } else{
          //La query ha estrapolato delle righe ...

          $file = mysqli_fetch_assoc($result);
          $filepath = "../uploads/" . $file['nomeFile'];
          if (file_exists($filepath)) {
              header('Content-Description: File Transfer');
              header('Content-Type: application/octet-stream');
              header('Content-Disposition: attachment; filename=' . basename($filepath));
              header('Expires: 0');
              header('Cache-Control: must-revalidate');
              header('Pragma: public');
              header('Content-Length: ' . filesize('../uploads/' . $file['nomeFile']));
              readfile('../uploads/' . $file['nomeFile']);
          }else {
            $co->rollBack();
            $co->close();
            header("Location: http://" .$ip .":" .$porta ."/corePHP/errorePage/errore.php?msg=Il file da lei selezionato non è stato individuato sul server la preghiamo di contattare l'amministrazione");
            die();
          }
        }

        $co->commit();
        $co->close();
      } catch (Exception $e) {
        $co->rollBack();
        $co->close();
        header("Location: http://" .$ip .":" .$porta ."/corePHP/errorePage/errore.php?msg=Siamo spiacente si è verificato un imprevisto");
        die();
      }

  }else {
    header("Location: http://" .$ip .":" .$porta ."/corePHP/errorePage/errore.php?msg=Si è verificato un errore<br>La invitiamo a riprovare");
    die();
  }
?>
