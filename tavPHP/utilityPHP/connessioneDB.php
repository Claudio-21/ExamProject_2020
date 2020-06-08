<?php
  include 'globalVar.php';

  function connect(){
    global $servername, $usernameDb, $passwordDb, $dbNome, $ip, $porta;
		// Create connection
		$conn = new mysqli($servername, $usernameDb, $passwordDb,$dbNome);
		// Check connection
		if ($conn->connect_error) {
			header("location: http://" . $ip . ":" . $porta . "/tavPHP/errorePage/errore.php?msg=Connsessione al DATABASE fallita<br>Contattre l'amministratore ...");
      die();
		}
  
		return $conn;
	}

  function closeConn($conn) {
    try {
        $conn->close();
    }catch(Exception $e) {
      return false;
    }
    return true;
  }
?>
