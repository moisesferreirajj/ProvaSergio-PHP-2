<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php';

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // VERIFICA SE O EMAIL É VÁLIDO
    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // GERA UMA SENHA TEMPORÁRIA ALEATÓRIA
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        // ATUALIZA A SENHA NO BANCO DE DADOS
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // SIMULA O ENVIO DO EMAIL (GRAVA EM TXT)
        simularEnvioEmail($email, $senha_temporaria);

        $mensagem = 'Uma senha temporária foi gerada e enviada para seu email. Verifique também o arquivo emails_simulados.txt para fins de teste.';
        $tipo_mensagem = 'success';
    } else {
        $mensagem = 'Email não encontrado em nosso sistema. Verifique se digitou corretamente.';
        $tipo_mensagem = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Sistema de Gestão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style-dashboard.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-background-animation"></div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <!-- Logo/Título -->
                    <div class="login-brand fade-in">
                        <div class="login-logo">
                            <i class="bi bi-key"></i>
                        </div>
                        <h1 class="login-title">Recuperar Senha</h1>
                        <p class="login-subtitle">Digite seu email para receber uma nova senha</p>
                    </div>

                    <!-- Card de Recuperação -->
                    <div class="login-card fade-in-delay">
                        <div class="login-card-body">
                            <?php if (!empty($mensagem)): ?>
                                <div class="custom-alert alert-<?= $tipo_mensagem ?>">
                                    <i class="bi <?= $tipo_mensagem === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle' ?> me-2"></i>
                                    <?= htmlspecialchars($mensagem) ?>
                                </div>
                            <?php endif; ?>

                            <form action="recuperar_senha.php" method="POST" class="login-form">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-2"></i>E-mail cadastrado
                                    </label>
                                    <input type="email" 
                                           class="form-control login-input" 
                                           id="email" 
                                           name="email" 
                                           placeholder="Digite seu e-mail"
                                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                                           required>
                                    <div class="form-text text-muted mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Uma senha temporária será enviada para este email
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary login-btn w-100">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Recuperar Senha
                                    </button>
                                </div>

                                <div class="login-links text-center">
                                    <a href="index.php" class="back-to-login-link">
                                        <i class="bi bi-arrow-left me-1"></i>
                                        Voltar ao Login
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="login-footer fade-in-delay-2">
                        <p class="text-muted text-center">
                            <i class="bi bi-shield-lock me-1"></i>
                            Sistema seguro e confiável <br>Moises João Ferreira | TDESN V3
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script-recuperar-senha.js"></script>
</body>
</html>