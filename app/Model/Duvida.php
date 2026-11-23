<?php

class Duvida {
    public $id_duvida;
    public $id_usuario;
    public $id_veterinario;
    public $id_animal;
    public $animal_brinco;
    public $assunto;
    public $pergunta;
    public $resposta;
    public $data_pergunta;
    public $data_resposta;
    public $status;
    public $usuario_nome;
 
    public function __construct($id_usuario, $pergunta, $assunto = 'Dúvida', $id_animal = null) {
        $this->id_usuario = $id_usuario;
        $this->pergunta = $pergunta;
        $this->assunto = $assunto;
        $this->id_animal = $id_animal;
    }
}

?>
 