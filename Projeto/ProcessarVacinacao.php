<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ .  '/../app/Model/RegistroVacinacao.php'; 
require_once __DIR__ .  '/../app/Dao/RegistroVacinacaoDao.php';
require_once __DIR__ . '/../app/Dao/NotificacaoDao.php';

$id_dono = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_animal = $_POST['id_animal'];
    $nome_vacina = $_POST['nome_vacina'];
    $data = $_POST['data_aplicacao'];
    $aplicador = $_POST['aplicador'];
    $dose = $_POST['dose'];

    $hoje = date('Y-m-d');
    if ($data > $hoje) {
        echo "Não pode cadastrar uma data futura";
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

        echo "<script>
                alert('registro realizado com sucesso!');
                window.location.href = 'ListaRegistroVacinacao.php';
              </script>";
    } else {

        echo "Erro ao salvar no banco de dados.";
    }
} else {
    
    header("Location: RegistrarVacinacao.php");
}

?>