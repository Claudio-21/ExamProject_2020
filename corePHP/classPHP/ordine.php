<?php
  class ordine{
    var $ordini;
    var $dataOrdine;
    var $oraOrdine;
    
    function __construct($dataOrdine, $oraOrdine) {
      $this->ordini = array();
      $this->dataOrdine = $dataOrdine;
      $this->oraOrdine = $oraOrdine;
    }

    function __destruct() {

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

  }
?>
