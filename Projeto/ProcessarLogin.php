<?php
// "Lembrar de mim": sessão de 30 dias (precisa vir ANTES do session_start)
$lembrar = isset($_POST['lembrar']);
if ($lembrar) {
    session_set_cookie_params([
        'lifetime' => 60 * 60 * 24 * 30, // 30 dias
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

session_start();
session_unset();    
session_destroy();  
session_start();  

require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

$usuario = $usuarioDao->buscarPorEmail($email);

if (!$usuario) {
    header("Location: login.php?erro=usuario_nao_encontrado");
    exit();
}

// --- Trava contra força bruta ---
$agora = new DateTime();
$bloqueadoAte = !empty($usuario['bloqueado_ate']) ? new DateTime($usuario['bloqueado_ate']) : null;

if ($bloqueadoAte && $bloqueadoAte > $agora) {
    header("Location: login.php?erro=conta_bloqueada");
    exit();
}

// Bloqueio expirado: zera o contador antes de tentar
if ($bloqueadoAte && (int) $usuario['tentativas_login'] > 0) {
    $usuarioDao->ZerarTentativas($usuario['id_usuario']);
    $usuario['tentativas_login'] = 0;
}

if (true) {
    if (password_verify($senha, $usuario['senha'])) {

        // Acertou: zera contador e bloqueio
        $usuarioDao->ZerarTentativas($usuario['id_usuario']);

        // Exige e-mail verificado antes de liberar o acesso
        if (isset($usuario['email_verificado']) && (int) $usuario['email_verificado'] === 0) {
            header("Location: VerificarEmail.php?email=" . urlencode($email) . "&pendente=1");
            exit();
        }

        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'] ?? 'Pecuarista';
        $_SESSION['homologado'] = (int) ($usuario['homologado'] ?? 0);
        $_SESSION['admin'] = (int) ($usuario['admin'] ?? 0);
        $_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? null;
        
        if ($_SESSION['admin'] === 1) {
            $destino = 'PainelAdmin.php';
        } else {
            $destino = $_SESSION['tipo_usuario'] === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php';
        }
        header("Location: " . $destino);
        exit();
    }
}

// Errou a senha: incrementa e bloqueia por 1 minuto após 5 tentativas
$tentativas = (int) $usuario['tentativas_login'] + 1;
if ($tentativas >= 5) {
    $usuarioDao->BloquearConta($usuario['id_usuario']);
    header("Location: login.php?erro=conta_bloqueada");
    exit();
}

$usuarioDao->RegistrarTentativa($usuario['id_usuario'], $tentativas);
header("Location: login.php?erro=senha_incorreta");
exit();
?>