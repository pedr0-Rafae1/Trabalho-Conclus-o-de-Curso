<?php

class Duvida {
    public $id_duvida;
    public $id_usuario;
    public $id_veterinario;
    public $pergunta;
    public $resposta;
    public $data_pergunta;
    public $data_resposta;
    public $status;
    public $usuario_nome;
 
    public function __construct($id_usuario, $pergunta) {
        $this->id_usuario = $id_usuario;
        $this->pergunta = $pergunta;
    }
}

?>
 