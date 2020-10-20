<?php

Class BaeckerPizzas{
    public $bestellungid;
    public $status;
    public $pizzas;
    
    public function __construct($ps,$bi,$st){
        $this->pizzas = $ps;
        $this->bestellungid = $bi;
        $this->status = $st;
    }
}