<?php
include_once 'Sessao.php';
require_once __DIR__ .  '/../app/Model/RegistroVacinacao.php'; 
require_once __DIR__ .  '/../app/Dao/RegistroVacinacaoDao.php';

$id_dono = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_animal = $_POST['id_animal'];
    $nome_vacina = $_POST['nome_vacina'];
    $data = $_POST['data_aplicacao'];
    $aplicador = $_POST['aplicador'];
    $dose = $_POST['dose'];
    

    $cadastrarvacinacao = new RegistroVacinacao($id_animal, $nome_vacina, $data, $aplicador, $dose);

    $dao = new RegistroVacinacaoDAO();
    
    if ($dao->Cadastrar($cadastrarvacinacao)) {

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