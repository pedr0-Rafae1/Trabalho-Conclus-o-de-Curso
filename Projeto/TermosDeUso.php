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
    <title>Termos de Uso - Pecuária em Rede</title>
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
    <h1>Termos de Uso</h1>
    <p class="termos-atualizado">Última atualização: setembro de 2026</p>

    <h2>1. Sobre o sistema</h2>
    <p>O Pecuária em Rede é uma plataforma web de gestão pecuária desenvolvida como Trabalho de Conclusão de Curso (TCC) no IFRN — Campus Nova Cruz. O sistema permite o registro e acompanhamento de animais, pesagens, vacinações, vendas e atendimentos veterinários.</p>

    <h2>2. Cadastro e responsabilidades do usuário</h2>
    <ul>
        <li>O usuário deve fornecer informações verdadeiras no cadastro (nome, e-mail e idade).</li>
        <li>A senha é pessoal e intransferível. Não compartilhe suas credenciais de acesso.</li>
        <li>O usuário é responsável pelos dados que registra sobre seu rebanho.</li>
    </ul>

    <h2>3. Conta de veterinário</h2>
    <p>Usuários cadastrados como Veterinário passam por homologação manual da equipe antes de acessar as ferramentas profissionais (responder dúvidas e registrar atendimentos). A homologação pode ser negada ou revogada em caso de uso indevido.</p>

    <h2>4. Privacidade e dados</h2>
    <p>Os dados registrados ficam vinculados à conta do usuário e são utilizados para operar os recursos da plataforma. Senhas são armazenadas como hash. Consulte a <a href="PoliticaPrivacidade.php">Política de Privacidade</a> para mais informações.</p>

    <h2>5. Natureza do projeto</h2>
    <p>Por se tratar de um projeto acadêmico, o sistema é oferecido gratuitamente, sem garantias de disponibilidade contínua. As informações registradas não substituem a orientação presencial de um médico veterinário.</p>

    <h2>6. Exclusão de conta</h2>
    <p>O usuário pode excluir sua conta a qualquer momento pela página "Meu Perfil". A exclusão é permanente e remove os dados vinculados à conta.</p>

    <h2>7. Contato</h2>
    <p>Em caso de dúvidas sobre estes termos, entre em contato pelo e-mail informado no rodapé do sistema.</p>
</main>

<?php include 'Rodape.php'; ?>
<?php if (!$usuarioLogado): ?>
    <script src="../Javascript/ThemeToggle.js" defer></script>
    <script>document.addEventListener('DOMContentLoaded', function () { inicializarThemeToggle('themeToggle'); });</script>
<?php endif; ?>
</body>
</html>
