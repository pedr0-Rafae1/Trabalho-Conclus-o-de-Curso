<?php

class Venda {
    public $id_venda;
    public $id_animal;
    public $comprador;
    public $valor_venda;
    public $data_venda;

    public $brinco;
    public $raca;

    public function __construct($id_animal, $comprador, $valor_venda, $data_venda) {
        $this->id_animal = $id_animal;
        $this->comprador = $comprador;
        $this->valor_venda = $valor_venda;
        $this->data_venda = $data_venda;
    }
}

?>