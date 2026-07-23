<?php

class Animal{
    public $id_animal;
    public $brinco;
    public $idade;
    public $especie;
    public $raca;
    public $data_nascimento;
    public $peso;
    public $altura;

    public function __construct($brinco, $idade, $especie, $raca, $data_nascimento, $peso, $altura = null) {
        $this->brinco = $brinco;
        $this->idade = $idade;
        $this->especie = $especie;
        $this->raca = $raca;
        $this->data_nascimento = $data_nascimento;
        $this->peso = $peso;
        $this->altura = $altura;
    }
}

?>