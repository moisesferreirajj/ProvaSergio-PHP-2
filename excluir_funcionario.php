<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variáveis
$funcionarios = [];
$mensagem_sucesso = '';
$mensagem_erro = '';

// Se um ID for passado via GET, exclui o funcionário
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_funcionario = $_GET['id'];

    // Busca o nome do funcionário antes de excluir
    $sql_nome = "SELECT nome_funcionario FROM funcionario WHERE id_funcionario = :id";
    $stmt_nome = $pdo->prepare($sql_nome);
    $stmt_nome->bindParam(':id', $id_funcionario, PDO::PARAM_INT);
    $stmt_nome->execute();
    $funcionario_nome = $stmt_nome->fetch(PDO::FETCH_ASSOC);

    if ($funcionario_nome) {
        // Exclui o funcionário do banco de dados
        $sql = "DELETE FROM funcionario WHERE id_funcionario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id_funcionario, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $mensagem_sucesso = "Funcionário '{$funcionario_nome['nome_funcionario']}' excluído com sucesso!";
        } else {
            $mensagem_erro = "Erro ao excluir funcionário. Tente novamente.";
        }
    } else {
        $mensagem_erro = "Funcionário não encontrado.";
    }
}

// Busca todos os funcionários cadastrados em ordem alfabética
$sql = "SELECT * FROM funcionario ORDER BY nome_funcionario ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Excluir Funcionário</title>
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
            <h2><i class="bi bi-person-x me-3"></i>Excluir Funcionário</h2>
        </div>

        <!-- Alertas de Sucesso/Erro -->
        <?php if($mensagem_sucesso): ?>
            <div class="custom-card fade-in">
                <div class="custom-card-body">
                    <div class="custom-alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Sucesso!</strong> <?= htmlspecialchars($mensagem_sucesso) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($mensagem_erro): ?>
            <div class="custom-card fade-in">
                <div class="custom-card-body">
                    <div class="custom-alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Erro!</strong> <?= htmlspecialchars($mensagem_erro) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Aviso Importante -->
        <div class="custom-card fade-in">
            <div class="custom-card-body">
                <div class="custom-alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Atenção!</strong> A exclusão de funcionários é uma ação irreversível. Certifique-se de que realmente deseja remover o funcionário do sistema antes de confirmar.
                </div>
            </div>
        </div>

        <!-- Lista de Funcionários -->
        <?php if (!empty($funcionarios)): ?>
            <div class="custom-card fade-in">
                <div class="custom-card-header d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-list-ul me-2"></i>Funcionários Cadastrados</h4>
                    <span class="badge badge-primary">
                        <?= count($funcionarios) ?> funcionário<?= count($funcionarios) != 1 ? 's' : '' ?>
                    </span>
                </div>
                <div class="custom-card-body p-0">
                    <div class="table-responsive">
                        <table class="custom-table mb-0">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>ID</th>
                                    <th><i class="bi bi-person me-1"></i>Nome</th>
                                    <th><i class="bi bi-envelope me-1"></i>Email</th>
                                    <th><i class="bi bi-house me-1"></i>Endereço</th>
                                    <th><i class="bi bi-telephone me-1"></i>Telefone</th>
                                    <th><i class="bi bi-trash me-1"></i>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($funcionarios as $funcionario): ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary"><?= htmlspecialchars($funcionario['id_funcionario']) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
                                                <strong><?= htmlspecialchars($funcionario['nome_funcionario']) ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($funcionario['email']): ?>
                                                <a href="mailto:<?= htmlspecialchars($funcionario['email']) ?>" class="text-decoration-none">
                                                    <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($funcionario['email']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="bi bi-dash"></i> Não informado
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($funcionario['endereco']): ?>
                                                <i class="bi bi-geo-alt text-muted me-1"></i><?= htmlspecialchars($funcionario['endereco']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="bi bi-dash"></i> Não informado
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($funcionario['telefone']): ?>
                                                <a href="tel:<?= htmlspecialchars($funcionario['telefone']) ?>" class="text-decoration-none">
                                                    <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($funcionario['telefone']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="bi bi-dash"></i> Não informado
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmarExclusao(<?= htmlspecialchars($funcionario['id_funcionario']) ?>, '<?= htmlspecialchars($funcionario['nome_funcionario']) ?>')"
                                                    title="Excluir funcionário">
                                                <i class="bi bi-trash me-1"></i>Excluir
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="custom-card">
                        <div class="custom-card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="bi bi-people-fill text-primary me-2" style="font-size: 1.5rem;"></i>
                                <h3 class="mb-0 text-primary"><?= count($funcionarios) ?></h3>
                            </div>
                            <p class="text-muted mb-0">Total de Funcionários</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-card">
                        <div class="custom-card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="bi bi-envelope-check-fill text-success me-2" style="font-size: 1.5rem;"></i>
                                <h3 class="mb-0 text-success">
                                    <?= count(array_filter($funcionarios, function($f) { return !empty($f['email']); })) ?>
                                </h3>
                            </div>
                            <p class="text-muted mb-0">Com Email Cadastrado</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-card">
                        <div class="custom-card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="bi bi-telephone-fill text-warning me-2" style="font-size: 1.5rem;"></i>
                                <h3 class="mb-0 text-warning">
                                    <?= count(array_filter($funcionarios, function($f) { return !empty($f['telefone']); })) ?>
                                </h3>
                            </div>
                            <p class="text-muted mb-0">Com Telefone Cadastrado</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="custom-card fade-in">
                <div class="custom-card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-people" style="font-size: 4rem; color: var(--blue-300);"></i>
                    </div>
                    <div class="custom-alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nenhum funcionário cadastrado</strong> no sistema.
                    </div>
                    <p class="text-muted mb-4">
                        <i class="bi bi-person-plus me-1"></i>
                        Não há funcionários para excluir. Cadastre funcionários primeiro.
                    </p>
                    <a href="cadastro_funcionario.php" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Cadastrar Primeiro Funcionário
                    </a>
                </div>
            </div>
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

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-labelledby="modalConfirmacaoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalConfirmacaoLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-person-x" style="font-size: 3rem; color: var(--danger); margin-bottom: 1rem;"></i>
                        <h5>Tem certeza que deseja excluir?</h5>
                        <p class="text-muted mb-3">
                            Esta ação não pode ser desfeita. O funcionário será removido permanentemente do sistema.
                        </p>
                        <div class="custom-alert alert-warning">
                            <strong>Funcionário:</strong> <span id="nomeFuncionario"></span><br>
                            <strong>ID:</strong> <span id="idFuncionario"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <a href="#" id="linkExclusao" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Sim, Excluir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Função para confirmar exclusão
        function confirmarExclusao(id, nome) {
            document.getElementById('nomeFuncionario').textContent = nome;
            document.getElementById('idFuncionario').textContent = id;
            document.getElementById('linkExclusao').href = `excluir_funcionario.php?id=${id}`;
            
            const modal = new bootstrap.Modal(document.getElementById('modalConfirmacao'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Animação para as linhas da tabela
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(20px)';
                    row.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    
                    requestAnimationFrame(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    });
                }, index * 100);
            });

            // Animação de entrada nos cards
            const cards = document.querySelectorAll('.custom-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    
                    requestAnimationFrame(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                }, index * 200);
            });
        });
    </script>
</body>