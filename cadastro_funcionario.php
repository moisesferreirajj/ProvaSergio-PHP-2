<?php
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO (APENAS ADM)
if($_SESSION['perfil'] != 1) {
    echo "<script>alert('Você não tem permissão para acessar esta página.');</script>";
    header("Location: principal.php");
    exit();
}

$sucesso = false;
$erro = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_funcionario = trim($_POST['nome_funcionario']);
    $email = trim($_POST['email']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);

    // Validação básica
    if(empty($nome_funcionario) || empty($email)) {
        $erro = "Nome e e-mail são obrigatórios!";
    } else {
        $sql = "INSERT INTO funcionario (nome_funcionario, email, endereco, telefone) VALUES (:nome_funcionario, :email, :endereco, :telefone)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":nome_funcionario", $nome_funcionario, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":endereco", $endereco, PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $telefone, PDO::PARAM_STR);

        if($stmt->execute()) {
            $sucesso = "Funcionário cadastrado com sucesso!";
            // Limpa os campos após o sucesso
            $nome_funcionario = $email = $endereco = $telefone = '';
        } else {
            $erro = "Erro ao cadastrar funcionário. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Cadastro de Funcionário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-container">
        <!-- Cabeçalho -->
        <div class="custom-header fade-in">
            <h2><i class="bi bi-person-plus me-3"></i>Cadastrar Funcionário</h2>
        </div>

        <!-- Alertas de Sucesso/Erro -->
        <?php if($sucesso): ?>
            <div class="custom-card fade-in">
                <div class="custom-card-body">
                    <div class="custom-alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Sucesso!</strong> <?= htmlspecialchars($sucesso) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($erro): ?>
            <div class="custom-card fade-in">
                <div class="custom-card-body">
                    <div class="custom-alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Erro!</strong> <?= htmlspecialchars($erro) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulário de Cadastro -->
        <div class="custom-card fade-in">
            <div class="custom-card-header">
                <h4><i class="bi bi-file-earmark-person me-2"></i>Dados do Novo Funcionário</h4>
            </div>
            <div class="custom-card-body">
                <form action="cadastro_funcionario.php" method="POST" class="row g-3" id="formCadastro">
                    <div class="col-md-6">
                        <label for="nome_funcionario" class="form-label">
                            <i class="bi bi-person me-1"></i>Nome Completo *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="nome_funcionario" 
                               name="nome_funcionario" 
                               placeholder="Ex: João Silva Santos"
                               value="<?= isset($nome_funcionario) ? htmlspecialchars($nome_funcionario) : '' ?>"
                               required>
                        <div class="invalid-feedback">
                            Por favor, informe o nome completo do funcionário.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>E-mail *
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               placeholder="Ex: joao.silva@empresa.com"
                               value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                               required>
                        <div class="invalid-feedback">
                            Por favor, informe um e-mail válido.
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label for="endereco" class="form-label">
                            <i class="bi bi-house me-1"></i>Endereço Completo
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="endereco" 
                               name="endereco" 
                               placeholder="Ex: Rua das Flores, 123 - Centro - Joinville/SC"
                               value="<?= isset($endereco) ? htmlspecialchars($endereco) : '' ?>">
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Informe o endereço completo incluindo cidade e estado
                        </small>
                    </div>

                    <div class="col-md-4">
                        <label for="telefone" class="form-label">
                            <i class="bi bi-telephone me-1"></i>Telefone/Celular
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="telefone" 
                               name="telefone" 
                               placeholder="(00) 00000-0000"
                               value="<?= isset($telefone) ? htmlspecialchars($telefone) : '' ?>">
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Formato automático aplicado
                        </small>
                    </div>

                    <div class="col-12">
                        <div class="custom-alert alert-info">
                            <i class="bi bi-asterisk text-danger me-1"></i>
                            <strong>Campos obrigatórios:</strong> Nome completo e e-mail são necessários para o cadastro.
                        </div>
                    </div>

                    <div class="col-12">
                        <hr class="my-4">
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Cadastrar Funcionário
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpar Campos
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card de Dicas -->
        <div class="custom-card fade-in">
            <div class="custom-card-header">
                <h4><i class="bi bi-lightbulb me-2"></i>Dicas Importantes</h4>
            </div>
            <div class="custom-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                            <div>
                                <strong>Nome Completo:</strong>
                                <small class="d-block text-muted">Informe o nome completo do funcionário para facilitar a identificação</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                            <div>
                                <strong>E-mail Válido:</strong>
                                <small class="d-block text-muted">Certifique-se de que o e-mail está correto para futuras comunicações</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                            <div>
                                <strong>Endereço Completo:</strong>
                                <small class="d-block text-muted">Inclua rua, número, bairro, cidade e estado</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                            <div>
                                <strong>Telefone:</strong>
                                <small class="d-block text-muted">O formato será aplicado automaticamente durante a digitação</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="login-footer fade-in-delay-2">
            <p class="text-muted text-center">
                <i class="bi bi-shield-lock me-1"></i>
                    Sistema seguro e confiável <br>Moises João Ferreira | TDESN V3
            </p>
        </div>

        <!-- Navegação -->
        <div class="nav-links">
            <a href="buscar_funcionario.php">
                <i class="bi bi-list-ul me-2"></i>Ver Todos os Funcionários
            </a>
            <a href="alterar_funcionario.php">
                <i class="bi bi-person-gear me-2"></i>Alterar Funcionário
            </a>
            <a href="principal.php">
                <i class="bi bi-house-door me-2"></i>Voltar ao Painel
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara para telefone
            const telefoneInput = document.getElementById('telefone');
            
            if (telefoneInput) {
                telefoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    
                    if (value.length <= 11) {
                        if (value.length <= 10) {
                            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                        } else {
                            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                        }
                    }
                    
                    e.target.value = value;
                });
            }

            // Validação do formulário
            const form = document.getElementById('formCadastro');
            
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // Validação em tempo real
            const inputs = form.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                });
            });

            // Animação de entrada
            const cards = document.querySelectorAll('.custom-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    
                    requestAnimationFrame(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                }, index * 200);
            });

            // Auto-focus no primeiro campo
            document.getElementById('nome_funcionario').focus();
        });
    </script>
</body>
</html>