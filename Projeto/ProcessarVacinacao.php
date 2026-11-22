<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ .  '/../app/Model/RegistroVacinacao.php'; 
require_once __DIR__ .  '/../app/Dao/RegistroVacinacaoDao.php';
require_once __DIR__ . '/../app/Dao/NotificacaoDao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$id_dono = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_animal = (int) ($_POST['id_animal'] ?? 0);
    $nome_vacina = $_POST['nome_vacina'];
    $data = $_POST['data_aplicacao'];
    $aplicador = $_POST['aplicador'];
    $dose = $_POST['dose'];

    $hoje = date('Y-m-d');
    if ($data > $hoje) {
        header("Location: RegistrarVacinacao.php?erro=data_futura");
        exit();
    }

    if (!is_numeric($dose) || (float) $dose <= 0) {
        header("Location: RegistrarVacinacao.php?erro=dose_invalida");
        exit();
    }

    // Regra de negócio: a vacinação não pode ser anterior ao nascimento do animal
    $animalDao = new AnimalDao();
    $animal = $animalDao->buscarPorIdEUsuario($id_animal, $id_dono);

    if (!$animal) {
        header("Location: RegistrarVacinacao.php?erro=animal_nao_encontrado");
        exit();
    }

    if (!empty($animal['data_nascimento']) && $data < $animal['data_nascimento']) {
        header("Location: RegistrarVacinacao.php?erro=data_anterior_nascimento");
        exit();
    }
    

    $cadastrarvacinacao = new RegistroVacinacao($id_animal, $nome_vacina, $data, $aplicador, $dose);

    $dao = new RegistroVacinacaoDAO();
    
    if ($dao->Cadastrar($cadastrarvacinacao)) {
        $dataReforco = date('Y-m-d', strtotime($data . ' +30 days'));
        $notificacaoDao = new NotificacaoDao();
        $notificacaoDao->Criar(
            $_SESSION['id_usuario'],
            'reforco_vacina',
            'Reforço de vacinação agendado',
            "A vacina {$nome_vacina} do animal {$id_animal} deve ser revisada em " . date('d/m/Y', strtotime($dataReforco)) . '.'
        );

        header("Location: ListaRegistroVacinacao.php?sucesso=cadastrado");
        exit();
    } else {
        header("Location: RegistrarVacinacao.php?erro=banco");
        exit();
    }
} else {
    
    header("Location: RegistrarVacinacao.php");
}

?>