<?php
  class gestoreOrdine {
    var $gestore;

    function __construct() {
      $this->gestore = array();
    }

    function __destruct() {

    }

    function addOrdineToGestore($ordine) {
      $out = true;
      if(isset($this->gestore)){
        foreach($this->gestore as $el){
          if($ordine->dataOrdine == $el->dataOrdine && $ordine->oraOrdine == $el->oraOrdine)
              $out = false;

        }

      }else {
        die("non settato riga 18");
      }

      if($out)
        array_push($this->gestore, $ordine);

      return $out;
    }

    function getGestore() {
      return $this->gestore;
    }

  }
?>
