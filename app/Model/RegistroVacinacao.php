<?php
class RegistroVacinacao{
    public $id_vacinacao;
    public $id_animal;
    public $nome_vacina;
    public $data_aplicacao;
    public $aplicador;
    public $dose;

    public function __construct($id_animal, $nome_vacina, $data_aplicacao, $aplicador, $dose = null) {
        $this->id_animal = $id_animal;
        $this->nome_vacina = $nome_vacina;
        $this->data_aplicacao = $data_aplicacao;
        $this->aplicador = $aplicador;
        $this->dose = $dose;
    }
}

?>
