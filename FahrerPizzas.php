<?php

Class FahrerPizzas{
    public $pizzas;
    public $adresse;
    public $status;
    public $kundename;
    public $plz;
    public $bestellungid;
    
    public function __construct($bi,$ad,$pl,$st,$kn,$ps){
        $this->pizzas = $ps;
        $this->adresse = $ad;
        $this->plz = $pl;
        $this->status = $st;
        $this->kundename = $kn;
        $this->bestellungid = $bi;
    }
}