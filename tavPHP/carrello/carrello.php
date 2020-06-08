<?php
  class Carrello {
    var $ordini;

    function __construct() {
      $this->ordini = array();
    }

    function getOrdini() {
      return $this->ordini;
    }

    function nextOrdine() {
      return next($this->ordini);
    }

    function prevOrdine() {
      return prev($this->ordini);
    }

    function currentOrdine() {
      return current($this->ordini);
    }

    function addOrdine($idProdotto) {
      array_push($this->ordini, $idProdotto);
    }

    function deleteOrdine($idProdotto) {
      $index = array_search($idProdotto, $this->ordini);
      if(is_int($index)) {
        array_splice($this->ordini, $index, 1);
        return true;
      }else
        return false;


    }

    function countQuantitaOrdinata($idProdotto) {

      $tmpCarrello = $this->ordini;
      $count = 0;

      do{
        $index = array_search($idProdotto, $tmpCarrello);
        if(is_int($index)) {
          array_splice($tmpCarrello, $index, 1);
          $app = true;
        }else
          $app = false;

        if($app)
          $count ++;

      }while($app);

      return $count;
    }

  }
?>
