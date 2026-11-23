<?php

date_default_timezone_set('America/Sao_Paulo');
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

include_once 'Sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: CanalDuvidas.php');
    exit();
}

if (($_SESSION['tipo_usuario'] ?? '') !== 'Pecuarista') {
    header('Location: CanalDuvidas.php?erro=perfil_invalido');
    exit();
}

$perguntaRecebida = $_POST['pergunta'] ?? '';
if (!is_string($perguntaRecebida) || trim($perguntaRecebida) === '') {
    header('Location: CanalDuvidas.php?erro=vazio');
    exit();
}

$assuntoRecebido = $_POST['assunto'] ?? '';
if (!is_string($assuntoRecebido) || trim($assuntoRecebido) === '' || mb_strlen(trim($assuntoRecebido), 'UTF-8') > 150) {
    header('Location: CanalDuvidas.php?erro=assunto_invalido');
    exit();
}

$idUsuario = (int) ($_SESSION['id_usuario'] ?? 0);
if ($idUsuario <= 0) {
    header('Location: login.php');
    exit();
}

$idAnimalRecebido = $_POST['id_animal'] ?? '';
if (!is_string($idAnimalRecebido) || ($idAnimalRecebido !== '' && !ctype_digit($idAnimalRecebido))) {
    header('Location: CanalDuvidas.php?erro=animal_invalido');
    exit();
}
$idAnimal = $idAnimalRecebido === '' ? null : (int) $idAnimalRecebido;

try {
    require_once __DIR__ . '/../app/Dao/DuvidaDao.php';
    require_once __DIR__ . '/../app/Model/Duvida.php';
    require_once __DIR__ . '/../app/Dao/AnimalDao.php';

    if ($idAnimal !== null) {
        $animalDao = new AnimalDao();
        if (!$animalDao->buscarPorIdEUsuario($idAnimal, $idUsuario)) {
            header('Location: CanalDuvidas.php?erro=animal_invalido');
            exit();
        }
    }

    $duvida = new Duvida($idUsuario, trim($perguntaRecebida), trim($assuntoRecebido), $idAnimal);
    $dao = new DuvidaDao();
    $idDuvida = $dao->Cadastrar($duvida);

    if (!$idDuvida) {
        throw new RuntimeException('O banco de dados não confirmou o cadastro da dúvida.');
    }
} catch (Throwable $erro) {
    error_log('ProcessarDuvida: falha ao salvar dúvida: ' . $erro->getMessage());
    header('Location: CanalDuvidas.php?erro=envio_falhou');
    exit();
}

try {
    require_once __DIR__ . '/../app/Dao/NotificacaoDao.php';

    $notificacaoDao = new NotificacaoDao();
    foreach ($dao->ListarIdsVeterinariosHomologados() as $idVeterinario) {
        if (!$notificacaoDao->Criar(
            $idVeterinario,
            'duvida_nova',
            'Nova dúvida no Canal de Dúvidas',
            ($_SESSION['usuario_nome'] ?? 'Um pecuarista') . ' enviou uma nova dúvida.',
            $idDuvida
        )) {
            error_log('ProcessarDuvida: falha ao notificar veterinário ' . $idVeterinario . '.');
        }
    }
} catch (Throwable $erro) {
    error_log('ProcessarDuvida: falha ao criar notificações: ' . $erro->getMessage());
}

header('Location: CanalDuvidas.php?sucesso=1&acao=duvida');
exit();
