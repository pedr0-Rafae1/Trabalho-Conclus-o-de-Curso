<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuarioLogado = isset($_SESSION['id_usuario']);
$rodapeCssCarregado = true;
if ($usuarioLogado) {
    $cabecalhoHeadExtras = '<link rel="stylesheet" href="../CSS/Institucional.css?v=1.0"><link rel="stylesheet" href="../CSS/Rodape.css?v=1.1">';
    include 'Cabecalho.php';
} else {
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - Pecuária em Rede</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function () {
            const tema = localStorage.getItem('rpp-tema') === 'dark' ? 'dark' : 'light';
            document.documentElement.dataset.theme = tema;
        })();
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Variaveis.css?v=2.2">
    <link rel="stylesheet" href="../CSS/index.css?v=3.0">
    <link rel="stylesheet" href="../CSS/Institucional.css?v=1.0">
    <link rel="stylesheet" href="../CSS/Rodape.css?v=1.1">
</head>
<body class="pagina-institucional">
    <?php include 'CabecalhoPublico.php'; ?>
<?php } ?>

<main class="termos-container">
    <h1>Política de Privacidade</h1>
    <p class="termos-atualizado">Última atualização: novembro de 2026</p>

    <h2>1. Dados tratados</h2>
    <p>Para criar e manter uma conta, o sistema utiliza os dados informados no cadastro, como nome, idade, e-mail e senha. O usuário também pode inserir informações sobre animais, pesagens, vacinação, vendas e atendimentos.</p>

    <h2>2. Finalidade</h2>
    <p>Essas informações são utilizadas para autenticar o usuário e disponibilizar os recursos de gestão pecuária e comunicação entre pecuaristas e veterinários.</p>

    <h2>3. Segurança e acesso</h2>
    <p>O acesso às informações é limitado aos recursos autorizados para cada tipo de conta. As senhas são armazenadas em formato de hash, e não como texto legível. O usuário deve manter suas credenciais em sigilo.</p>

    <h2>4. Retenção e exclusão</h2>
    <p>Os dados permanecem associados à conta enquanto ela estiver ativa. Ao excluir a conta, os dados vinculados são removidos conforme o funcionamento do sistema.</p>

    <h2>5. Contato</h2>
    <p>Para dúvidas sobre esta política, escreva para <a href="mailto:contato@pecuariaemrede.com">contato@pecuariaemrede.com</a>.</p>

    <p><a href="TermosDeUso.php">Leia também os Termos de Uso.</a></p>
</main>

<?php include 'Rodape.php'; ?>
<?php if (!$usuarioLogado): ?>
    <script src="../Javascript/ThemeToggle.js" defer></script>
    <script>document.addEventListener('DOMContentLoaded', function () { inicializarThemeToggle('themeToggle'); });</script>
<?php endif; ?>
</body>
</html>
