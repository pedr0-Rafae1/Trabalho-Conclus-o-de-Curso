<?php

class Duvida {
    public ?int $id_duvida = null;
    public int $id_usuario;
    public ?int $id_veterinario = null;
    public ?int $id_animal = null;
    public ?string $animal_brinco = null;
    public string $assunto;
    public string $pergunta;
    public ?string $resposta = null;
    public ?string $data_pergunta = null;
    public ?string $data_resposta = null;
    public ?string $status = null;
    public ?string $usuario_nome = null;
    public ?string $veterinario_nome = null;
 
    public function __construct(int $id_usuario, string $pergunta, string $assunto = 'Dúvida', ?int $id_animal = null) {
        $this->id_usuario = $id_usuario;
        $this->pergunta = $pergunta;
        $this->assunto = $assunto;
        $this->id_animal = $id_animal;
    }
}

?>
 