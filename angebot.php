<?php

Class Angebot{
    public $pizzaname;
    public $pfad;
    public $preis;
    
    public function __construct($pn,$pf,$pr){
        $this->pizzaname = $pn;
        $this->pfad = $pf;
        $this->preis = $pr;
    }
}