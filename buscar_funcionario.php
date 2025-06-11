<?php
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO (ADM OU SECRETARIA)
if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Você não tem permissão para acessar esta página.');</script>";
    header("Location: principal.php");
    exit();
}

$funcionarios = []; // INICIALIZA COMO ARRAY VAZIO
$termo_busca = '';

// SE O FORMULÁRIO FOR ENVIADO, BUSCA O FUNCIONÁRIO PELO ID OU NOME
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);
    $termo_busca = $busca;

    //VERIFICA SE A BUSCA É UM NÚMERO (id) OU UM (nome)
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM funcionario ORDER BY id_funcionario ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Buscar Funcionários</title>
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
            <h2><i class="bi bi-people me-3"></i>Lista de Funcionários</h2>
        </div>

        <!-- Card de Busca -->
        <div class="custom-card fade-in">
            <div class="custom-card-header">
                <h4><i class="bi bi-search me-2"></i>Buscar Funcionário</h4>
            </div>
            <div class="custom-card-body">
                <form action="buscar_funcionario.php" method="POST" class="row g-3">
                    <div class="col-md-8">
                        <label for="busca" class="form-label">
                            <i class="bi bi-person-search me-1"></i>Buscar por ID ou Nome:
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="busca"
                               name="busca" 
                               placeholder="Ex: João Silva ou 123"
                               value="<?= htmlspecialchars($termo_busca) ?>"
                               required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                            <a href="buscar_funcionario.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resultados -->
        <?php if(!empty($funcionarios)): ?>
            <div class="custom-card fade-in">
                <div class="custom-card-header d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-list-check me-2"></i>
                        <?= $termo_busca ? 'Resultados da Busca' : 'Todos os Funcionários' ?>
                    </h4>
                    <span class="badge badge-primary">
                        <?= count($funcionarios) ?> funcionário<?= count($funcionarios) != 1 ? 's' : '' ?> encontrado<?= count($funcionarios) != 1 ? 's' : '' ?>
                    </span>
                </div>
                <div class="custom-card-body p-0">
                    <?php if ($termo_busca): ?>
                        <div class="p-3 border-bottom bg-light">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Mostrando resultados para: <strong>"<?= htmlspecialchars($termo_busca) ?>"</strong>
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="custom-table mb-0">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>ID</th>
                                    <th><i class="bi bi-person me-1"></i>Nome</th>
                                    <th><i class="bi bi-envelope me-1"></i>Email</th>
                                    <th><i class="bi bi-house me-1"></i>Endereço</th>
                                    <th><i class="bi bi-telephone me-1"></i>Telefone</th>
                                    <th><i class="bi bi-gear me-1"></i>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($funcionarios as $func): ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary"><?php echo htmlspecialchars($func['id_funcionario']); ?></span>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($func['nome_funcionario']); ?></strong>
                                        </td>
                                        <td>
                                            <a href="mailto:<?php echo htmlspecialchars($func['email']); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($func['email']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo $func['endereco'] ? htmlspecialchars($func['endereco']) : '<span class="text-muted">Não informado</span>'; ?>
                                        </td>
                                        <td>
                                            <?php if($func['telefone']): ?>
                                                <a href="tel:<?php echo htmlspecialchars($func['telefone']); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($func['telefone']); ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-links">
                                                <?php if($_SESSION['perfil'] == 1): ?>
                                                    <a href="alterar_funcionario.php" 
                                                       class="action-edit"
                                                       title="Alterar funcionário">
                                                        <i class="bi bi-pencil-square me-1"></i>Alterar
                                                    </a>
                                                    <a href="excluir_funcionario.php?id=<?php echo htmlspecialchars($func['id_funcionario']); ?>" 
                                                       class="action-delete"
                                                       onclick="return confirm('Tem certeza que deseja excluir o funcionário <?= htmlspecialchars($func['nome_funcionario']) ?>?')"
                                                       title="Excluir funcionário">
                                                        <i class="bi bi-trash me-1"></i>Excluir
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">
                                                        <i class="bi bi-eye me-1"></i>Visualizar
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="custom-card fade-in">
                <div class="custom-card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-people" style="font-size: 4rem; color: var(--blue-300);"></i>
                    </div>
                    <?php if ($termo_busca): ?>
                        <div class="custom-alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Nenhum funcionário encontrado</strong> com o termo: "<?= htmlspecialchars($termo_busca) ?>"
                        </div>
                        <p class="text-muted">
                            <i class="bi bi-lightbulb me-1"></i>
                            Tente buscar pelo nome completo ou ID do funcionário.
                        </p>
                    <?php else: ?>
                        <div class="custom-alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Nenhum funcionário cadastrado</strong> no sistema.
                        </div>
                        <p class="text-muted">
                            <i class="bi bi-person-plus me-1"></i>
                            Comece cadastrando o primeiro funcionário.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Estatísticas rápidas -->
        <?php if(!empty($funcionarios) && !$termo_busca): ?>
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
                            <p class="text-muted mb-0">Com Email</p>
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
                            <p class="text-muted mb-0">Com Telefone</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Navegação -->
        <div class="nav-links">
            <?php if($_SESSION['perfil'] == 1): ?>
                <a href="cadastro_funcionario.php">
                    <i class="bi bi-person-plus me-2"></i>Cadastrar Novo Funcionário
                </a>
            <?php endif; ?>
            <a href="principal.php">
                <i class="bi bi-house-door me-2"></i>Voltar para o Painel Principal
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animação para as linhas da tabela
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    row.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    
                    requestAnimationFrame(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateX(0)';
                    });
                }, index * 100);
            });

            // Auto-focus no campo de busca
            const buscaInput = document.getElementById('busca');
            if (buscaInput && buscaInput.value === '') {
                buscaInput.focus();
            }

            // Highlight do termo buscado
            const termoBusca = '<?= $termo_busca ?>';
            if (termoBusca) {
                const regex = new RegExp(`(${termoBusca})`, 'gi');
                const cells = document.querySelectorAll('tbody td');
                
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(termoBusca.toLowerCase())) {
                        cell.innerHTML = cell.innerHTML.replace(regex, '<mark style="background-color: var(--blue-100); color: var(--blue-800);">$1</mark>');
                    }
                });
            }
        });
    </script>
</body>
</html>