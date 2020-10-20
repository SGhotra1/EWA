<?php

Class BestelltePizzas{
    public $pizzaname;
    public $bestellstatus;
    public function __construct($pn,$bs){
        $this->pizzaname = $pn;
        $this->bestellstatus = $bs;
    }
}