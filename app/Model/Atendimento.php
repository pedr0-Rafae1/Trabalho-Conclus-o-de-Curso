<?php

class Atendimento {
    public $id_atendimento;
    public $id_animal;
    public $id_veterinario;
    public $data_atendimento;
    public $descricao;
    public $diagnostico;
    public $recomendacao;
 
    public $brinco;
    public $raca;
    public $veterinario_nome;
    public $dono_nome;
 
    public function __construct($id_animal, $data_atendimento, $descricao, $diagnostico = null, $recomendacao = null) {
        $this->id_animal = $id_animal;
        $this->data_atendimento = $data_atendimento;
        $this->descricao = $descricao;
        $this->diagnostico = $diagnostico;
        $this->recomendacao = $recomendacao;
    }
}

?>