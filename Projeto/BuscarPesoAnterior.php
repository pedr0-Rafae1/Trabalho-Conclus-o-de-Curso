<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    http_response_code(403);
    echo json_encode(['erro' => 'Acesso permitido somente para pecuaristas.']);
    exit();
}

require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Conexao/ConexaoBD.php';

header('Content-Type: application/json');

if (isset($_GET['id_animal'])) {
    $id_animal = intval($_GET['id_animal']);
    
    $animalDao = new AnimalDao();
    $peso = $animalDao->ObterPesoAnterior($id_animal);
    
    echo json_encode(['peso_anterior' => $peso]);
    exit();
}

echo json_encode(['peso_anterior' => 0]);