<?php
include 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ . '/../app/Model/Animal.php'; 
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$id_dono = $_SESSION['id_usuario']; 

$hoje = date('Y-m-d');

if ($data_nascimento > $hoje) {
    echo "Não pode cadastrar uma data futura";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $brinco          = $_POST['brinco'];
    $idade           = $_POST['idade'];
    $especie         = $_POST['especie'];
    $raca            = $_POST['raca'];
    $data_nascimento = $_POST['data_nascimento'];
    $peso            = $_POST['peso'];
    $altura          = $_POST['altura'];

    $novoanimal = new Animal($brinco, $idade, $especie, $raca, $data_nascimento, $peso, $altura);

    $daoanimal = new AnimalDao();

    if ($daoanimal->Cadastrar($novoanimal, $id_dono)) {

        echo "<script>
                alert('Cadastro realizado com sucesso!');
                window.location.href = 'ListaAnimal.php'; 
              </script>";
    } else {
        echo "Erro ao salvar no banco de dados.";
    }
    
} else {

    header("Location: CadastroAnimal.php");
    exit();
}