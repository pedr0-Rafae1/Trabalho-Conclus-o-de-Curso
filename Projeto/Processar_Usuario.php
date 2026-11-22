<?php

require_once __DIR__ .  '/../app/Model/Usuario.php'; 
require_once __DIR__ .  '/../app/Dao/UsuarioDao.php';
require_once __DIR__ .  '/../app/Servico/EmailService.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome   = trim($_POST['nome'] ?? '');
    $idade  = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
    $email  = trim($_POST['email'] ?? '');
    $senha  = $_POST['senha'] ?? '';
    $senha_confirmacao = $_POST['senha_confirmacao'] ?? '';
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'Pecuarista';

    if ($nome === '' || $senha === '') {
        header("Location: CadastroUsuario.php?erro=campos_vazios");
        exit();
    }

    // Nome: apenas letras, espaços e acentos, mínimo 3 caracteres
    if (!preg_match('/^[\p{L} ]{3,}$/u', $nome)) {
        header("Location: CadastroUsuario.php?erro=nome_invalido");
        exit();
    }

    // Confirmação de senha
    if ($senha !== $senha_confirmacao) {
        header("Location: CadastroUsuario.php?erro=senhas_diferentes");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: CadastroUsuario.php?erro=email_invalido");
        exit();
    }

    if ($idade === false || $idade === null || $idade <= 0) {
        header("Location: CadastroUsuario.php?erro=idade_invalida");
        exit();
    }

    $dao = new UsuarioDAO();

    if ($dao->buscarPorEmail($email)) {
        header("Location: CadastroUsuario.php?erro=email_duplicado");
        exit();
    }

    $codigoVerificacao = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $novoUsuario = new Usuario($nome, $idade, $email, $senha, $tipo_usuario);

    if ($dao->Cadastrar($novoUsuario)) {

        // Gera código de 6 dígitos, salva e envia por e-mail via Brevo
        $usuarioCriado = $dao->buscarPorEmail($email);
        $dao->SalvarCodigoVerificacao($usuarioCriado['id_usuario'], $codigoVerificacao);

        // Rollback: se o e-mail falhar, remove a conta para não prender o usuário
        if (!EmailService::enviarCodigoCadastro($email, $nome, $codigoVerificacao)) {
            $dao->Remover($usuarioCriado['id_usuario']);
            header("Location: CadastroUsuario.php?erro=email_falhou");
            exit();
        }

        header("Location: VerificarEmail.php?email=" . urlencode($email) . "&pendente=1");
        exit();
    } else {

        echo "Erro ao salvar no banco de dados.";
    }
} else {
    
    header("Location: CadastroUsuario.php");
}

?>