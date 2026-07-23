<?php

class Usuario{
    public $id_usuario;
    public $nome;
    public $idade;
    public $email;
    public $senha;

    public function __construct($nome, $idade, $email, $senha = null) {
        $this->nome = $nome;
        $this->idade = $idade;
        $this->email = $email;
        $this->senha = $senha;
    }
}

?>