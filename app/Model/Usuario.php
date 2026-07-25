<?php

class Usuario{
    public $id_usuario;
    public $nome;
    public $idade;
    public $email;
    public $senha;
    public $tipo_usuario;

    public function __construct($nome, $idade, $email, $senha = null, $tipo_usuario = 'Pecuarista',) {
        $this->nome = $nome;
        $this->idade = $idade;
        $this->email = $email;
        $this->senha = $senha;
        $this->tipo_usuario = $tipo_usuario;
    }
}

?>