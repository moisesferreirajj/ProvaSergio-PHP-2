<?php
session_start();

if (isset($_SESSION['usuario']) || isset($_SESSION['perfil']) || isset($_SESSION['id_usuario'])) {
    header("Location: principal.php");
    exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // LOGIN BEM SUCEDIDO DEFINE VARIÁVEIS DE SESSÃO
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['perfil'] = $usuario['id_perfil'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];

        // VERIFICA SE A SENHA É TEMPORÁRIA
        if ($usuario['senha_temporaria']) {
            // REDIRECIONA PARA A TROCA DE SENHA
            header("Location: alterar_senha.php");
            exit();
        } else {
            // REDIRECIONA PARA A PÁGINA PRINCIPAL
            header("Location: principal.php");
            exit();
        }
    } else {
        $erro = "Email ou senha inválidos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
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
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h1 class="login-title">Sistema de Gestão</h1>
                        <p class="login-subtitle">Faça login para continuar</p>
                    </div>

                    <!-- Card de Login -->
                    <div class="login-card fade-in-delay">
                        <div class="login-card-body">
                            <?php if (isset($erro)): ?>
                                <div class="custom-alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <?= htmlspecialchars($erro) ?>
                                </div>
                            <?php endif; ?>

                            <form action="index.php" method="POST" class="login-form">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-2"></i>E-mail
                                    </label>
                                    <input type="email" 
                                           class="form-control login-input" 
                                           id="email" 
                                           name="email" 
                                           placeholder="Digite seu e-mail"
                                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label for="senha" class="form-label">
                                        <i class="bi bi-lock me-2"></i>Senha
                                    </label>
                                    <div class="password-input-container">
                                        <input type="password" 
                                               class="form-control login-input" 
                                               id="senha" 
                                               name="senha" 
                                               placeholder="Digite sua senha"
                                               required>
                                        <button type="button" 
                                                class="password-toggle" 
                                                onclick="togglePassword('senha')">
                                            <i class="bi bi-eye" id="senha-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary login-btn w-100">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Entrar
                                    </button>
                                </div>

                                <div class="login-links">
                                    <a href="recuperar_senha.php" class="forgot-password-link">
                                        <i class="bi bi-question-circle me-1"></i>
                                        Esqueci minha senha
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts customizados -->
    <script>
        // Função para mostrar/ocultar senha
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            // Focus automático no primeiro input
            document.getElementById('email').focus();
            
            // Animação dos elementos
            const elements = document.querySelectorAll('.fade-in, .fade-in-delay, .fade-in-delay-2');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Validação em tempo real
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>