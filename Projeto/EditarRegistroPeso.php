<?php

include 'Sessao.php';
include 'Cabecalho.php';
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Model/RegistroPeso.php';
require_once __DIR__ . '/../app/Conexao/ConexaoBD.php';

$id_peso = $_GET['id']; 
$dao = new RegistroPesoDao();
$registropeso = $dao->BuscarPorId($id_peso);
$hoje = date('Y-m-d');

if (!$registropeso) {
    die("Seu Registro da pesagem não foi encontrado ou não pertence à sua conta.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/Formularios.css">
</head>
<body>

  <main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-plus-circle me-2"></i>Registro Peso</h2>
        <form action="ProcessarAtualizarRegistroPeso.php" method="POST">
            <input type="hidden" name="id_peso" value="<?= $registropeso->id_peso ?>">

            <label >ID do Animal:</label>
            <input type="text" name="id_animal" class="form-control" value="<?= $registropeso->id_animal ?>" required>

            <label >Peso Anterior (kg):</label>
            <input type="text" name="peso_anterior" class="form-control" value="<?= $registropeso->peso_anterior ?>" required>

            <label >Peso Atual (Kg):</label>
            <input type="text" name="peso_atual" class="form-control" value="<?= $registropeso->peso_atual ?>" required>

            <label >Data da Pesagem:</label>
            <input type="date" name="data_pessagem" class="form-control" value="<?= $registropeso->data_pessagem ?>" max="<?= $hoje ?>" required>

            <button type="submit">Salvar Alterações</button>
            <a href="ListaRegistroPeso.php" >Cancelar</a>
        </form>
    </div>
</main>
    
</body>
</html>