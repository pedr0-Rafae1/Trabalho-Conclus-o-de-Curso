<?php

class RegistroPeso{
    public $id_peso;
    public $id_animal;
    public $peso_anterior;
    public $peso_atual;
    public $data_pessagem;

    public function __construct($id_animal, $peso_anterior, $peso_atual, $data_pessagem = null) {
        $this->id_animal = $id_animal;
        $this->peso_anterior = $peso_anterior;
        $this->peso_atual = $peso_atual;
        $this->data_pessagem = $data_pessagem;
    }
}
?>
