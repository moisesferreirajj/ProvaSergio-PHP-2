<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variáveis
$funcionario = null;

// Se o formulário for enviado, busca o funcionário pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_funcionario'])) {
        $busca = trim($_POST['busca_funcionario']);

        // Verifica se a busca é um número (ID) ou um nome
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o funcionário não for encontrado, exibe um alerta
        if (!$funcionario) {
            echo "<script>alert('Funcionário não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Alterar Funcionário</title>
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
            <h2><i class="bi bi-person-gear me-3"></i>Alterar Funcionário</h2>
        </div>

        <!-- Card de Busca -->
        <div class="custom-card fade-in">
            <div class="custom-card-header">
                <h4><i class="bi bi-search me-2"></i>Buscar Funcionário</h4>
            </div>
            <div class="custom-card-body">
                <form action="alterar_funcionario.php" method="POST" class="row g-3">
                    <div class="col-md-8">
                        <label for="busca_funcionario" class="form-label">
                            <i class="bi bi-person-search me-1"></i>Digite o ID ou Nome do funcionário:
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="busca_funcionario" 
                               name="busca_funcionario" 
                               placeholder="Ex: João Silva ou 123"
                               value="<?= isset($_POST['busca_funcionario']) ? htmlspecialchars($_POST['busca_funcionario']) : '' ?>"
                               required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($funcionario): ?>
            <!-- Card de Alteração -->
            <div class="custom-card fade-in">
                <div class="custom-card-header">
                    <h4><i class="bi bi-person-check me-2"></i>Dados do Funcionário</h4>
                </div>
                <div class="custom-card-body">
                    <!-- Informações atuais -->
                    <div class="custom-alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Funcionário encontrado:</strong> <?= htmlspecialchars($funcionario['nome_funcionario']) ?> (ID: <?= htmlspecialchars($funcionario['id_funcionario']) ?>)
                    </div>

                    <!-- Formulário de alteração -->
                    <form action="processa_alteracao_funcionario.php" method="POST" class="row g-3">
                        <input type="hidden" name="id_funcionario" value="<?= htmlspecialchars($funcionario['id_funcionario']) ?>">

                        <div class="col-md-6">
                            <label for="nome_funcionario" class="form-label">
                                <i class="bi bi-person me-1"></i>Nome Completo *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nome_funcionario" 
                                   name="nome_funcionario" 
                                   value="<?= htmlspecialchars($funcionario['nome_funcionario']) ?>" 
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>E-mail *
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($funcionario['email']) ?>" 
                                   required>
                        </div>

                        <div class="col-md-8">
                            <label for="endereco" class="form-label">
                                <i class="bi bi-house me-1"></i>Endereço
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="endereco" 
                                   name="endereco" 
                                   value="<?= htmlspecialchars($funcionario['endereco']) ?>"
                                   placeholder="Ex: Rua das Flores, 123 - Centro">
                        </div>

                        <div class="col-md-4">
                            <label for="telefone" class="form-label">
                                <i class="bi bi-telephone me-1"></i>Telefone
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="telefone" 
                                   name="telefone" 
                                   value="<?= htmlspecialchars($funcionario['telefone']) ?>"
                                   placeholder="(00) 00000-0000">
                        </div>

                        <div class="col-12">
                            <small class="text-muted">
                                <i class="bi bi-asterisk text-danger"></i> Campos obrigatórios
                            </small>
                        </div>

                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Salvar Alterações
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Resetar Campos
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca_funcionario'])): ?>
                <!-- Mensagem quando não encontra funcionário -->
                <div class="custom-card fade-in">
                    <div class="custom-card-body text-center">
                        <div class="custom-alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Nenhum funcionário encontrado</strong> com o termo pesquisado: "<?= htmlspecialchars($_POST['busca_funcionario']) ?>"
                        </div>
                        <p class="text-muted mb-0">
                            <i class="bi bi-lightbulb me-1"></i>
                            Tente buscar pelo nome completo ou ID do funcionário.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

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
            <a href="cadastro_funcionario.php">
                <i class="bi bi-person-plus me-2"></i>Cadastrar Novo Funcionário
            </a>
            <a href="principal.php">
                <i class="bi bi-house-door me-2"></i>Voltar ao Painel
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para mascarar telefone -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Animação de entrada nos cards
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
        });
    </script>
</body>
</html>