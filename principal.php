<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    echo "<script>alert('Você precisa estar logado para acessar esta página.');</script>";
    exit();
}

// OBTENDO O NOME DO PERFIL DO USUÁRIO LOGADO
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(":id_perfil", $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// DEFINIÇÃO DAS PERMISSÕES POR PERFIL
$permissoes = [
    // ADMINISTRADOR
    '1' => [
        'Cadastrar' => [
            ['arquivo' => 'cadastro_usuario.php', 'titulo' => 'Usuário', 'icone' => 'bi-person-plus'],
            ['arquivo' => 'cadastro_perfil.php', 'titulo' => 'Perfil', 'icone' => 'bi-shield-plus'],
            ['arquivo' => 'cadastro_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'cadastro_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'cadastro_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box'],
            ['arquivo' => 'cadastro_funcionario.php', 'titulo' => 'Funcionário', 'icone' => 'bi-person-badge']
        ],
        'Buscar' => [
            ['arquivo' => 'buscar_usuario.php', 'titulo' => 'Usuário', 'icone' => 'bi-person-x'],
            ['arquivo' => 'buscar_perfil.php', 'titulo' => 'Perfil', 'icone' => 'bi-shield-check'],
            ['arquivo' => 'buscar_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'buscar_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'buscar_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box'],
            ['arquivo' => 'buscar_funcionario.php', 'titulo' => 'Funcionário', 'icone' => 'bi-person-badge']
        ],
        'Alterar' => [
            ['arquivo' => 'alterar_usuario.php', 'titulo' => 'Usuário', 'icone' => 'bi-person-gear'],
            ['arquivo' => 'alterar_perfil.php', 'titulo' => 'Perfil', 'icone' => 'bi-shield-exclamation'],
            ['arquivo' => 'alterar_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'alterar_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'alterar_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box'],
            ['arquivo' => 'alterar_funcionario.php', 'titulo' => 'Funcionário', 'icone' => 'bi-person-gear']
        ],
        'Excluir' => [
            ['arquivo' => 'excluir_usuario.php', 'titulo' => 'Usuário', 'icone' => 'bi-person-x'],
            ['arquivo' => 'excluir_perfil.php', 'titulo' => 'Perfil', 'icone' => 'bi-shield-x'],
            ['arquivo' => 'excluir_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'excluir_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'excluir_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box'],
            ['arquivo' => 'excluir_funcionario.php', 'titulo' => 'Funcionário', 'icone' => 'bi-person-x']
        ]
    ],

    // SECRETARIA
    '2' => [
        'Cadastrar' => [
            ['arquivo' => 'cadastro_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people']
        ],
        'Buscar' => [
            ['arquivo' => 'buscar_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'buscar_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'buscar_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box']
        ],
        'Alterar' => [
            ['arquivo' => 'alterar_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'alterar_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck']
        ]
    ],

    // ALMOXARIFADO
    '3' => [
        'Cadastrar' => [
            ['arquivo' => 'cadastro_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'cadastro_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box']
        ],
        'Buscar' => [
            ['arquivo' => 'buscar_usuario.php', 'titulo' => 'Usuário', 'icone' => 'bi-person-search'],
            ['arquivo' => 'buscar_perfil.php', 'titulo' => 'Perfil', 'icone' => 'bi-shield-check'],
            ['arquivo' => 'buscar_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people']
        ],
        'Alterar' => [
            ['arquivo' => 'alterar_usuario.php', 'titulo' => 'Usuário', 'icone' => 'bi-person-gear'],
            ['arquivo' => 'alterar_perfil.php', 'titulo' => 'Perfil', 'icone' => 'bi-shield-exclamation'],
            ['arquivo' => 'alterar_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'alterar_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'alterar_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box'],
            ['arquivo' => 'alterar_funcionario.php', 'titulo' => 'Funcionário', 'icone' => 'bi-person-gear']
        ],
        'Excluir' => [
            ['arquivo' => 'excluir_usuario.php', 'titulo' => 'Usuário', 'icone' => 'bi-person-x'],
            ['arquivo' => 'excluir_perfil.php', 'titulo' => 'Perfil', 'icone' => 'bi-shield-x'],
            ['arquivo' => 'excluir_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people'],
            ['arquivo' => 'excluir_fornecedor.php', 'titulo' => 'Fornecedor', 'icone' => 'bi-truck'],
            ['arquivo' => 'excluir_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box'],
            ['arquivo' => 'excluir_funcionario.php', 'titulo' => 'Funcionário', 'icone' => 'bi-person-x']
        ]
    ],

    // OUTRO PERFIL (EXEMPLO)
    '4' => [
        'Cadastrar' => [
            ['arquivo' => 'cadastro_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people']
        ],
        'Buscar' => [
            ['arquivo' => 'buscar_produto.php', 'titulo' => 'Produto', 'icone' => 'bi-box']
        ],
        'Alterar' => [
            ['arquivo' => 'alterar_cliente.php', 'titulo' => 'Cliente', 'icone' => 'bi-people']
        ]
    ]
];

// OBTENDO AS OPÇÕES DISPONÍVEIS PARA O PERFIL DO USUÁRIO LOGADO
$opcoes_menu = $permissoes[$id_perfil] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style-dashboard.css">
</head>
<body class="dashboard-body">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="dashboard-brand">
                        <i class="bi bi-grid-3x3-gap me-2"></i>
                        <span>Sistema de Gestão</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-user-info">
                        <div class="user-details">
                            <span class="user-name">
                                <i class="bi bi-person-circle me-2"></i>
                                <?= htmlspecialchars($_SESSION['usuario']) ?>
                            </span>
                            <span class="user-role">
                                <i class="bi bi-shield-check me-1"></i>
                                <?= htmlspecialchars($nome_perfil) ?>
                            </span>
                        </div>
                        <form action="logout.php" method="POST" class="logout-form">
                            <button type="submit" class="btn btn-outline-light btn-sm logout-btn">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="container-fluid">
            <!-- Welcome Section -->
            <div class="welcome-section fade-in">
                <div class="row">
                    <div class="col-12">
                        <div class="welcome-card">
                            <div class="welcome-content">
                                <h1 class="welcome-title">
                                    Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']) ?>!
                                </h1>
                                <p class="welcome-subtitle">
                                    Gerencie seu sistema de forma eficiente através do painel de controle
                                </p>
                            </div>
                            <div class="welcome-icon">
                                <i class="bi bi-house-heart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Sections -->
            <div class="menu-sections">
                <?php foreach ($opcoes_menu as $categoria => $itens): ?>
                    <div class="menu-section fade-in-delay">
                        <div class="section-header">
                            <h3 class="section-title">
                                <?php 
                                $icones_categoria = [
                                    'Cadastrar' => 'bi-plus-circle',
                                    'Buscar' => 'bi-search',
                                    'Alterar' => 'bi-pencil-square',
                                    'Excluir' => 'bi-trash'
                                ];
                                $icone = $icones_categoria[$categoria] ?? 'bi-gear';
                                ?>
                                <i class="<?= $icone ?> me-2"></i>
                                <?= htmlspecialchars($categoria) ?>
                            </h3>
                        </div>
                        
                        <div class="row">
                            <?php foreach ($itens as $item): ?>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 mb-3">
                                    <a href="<?= htmlspecialchars($item['arquivo']) ?>" class="menu-card">
                                        <div class="menu-card-icon">
                                            <i class="<?= htmlspecialchars($item['icone']) ?>"></i>
                                        </div>
                                        <div class="menu-card-content">
                                            <h5 class="menu-card-title"><?= htmlspecialchars($item['titulo']) ?></h5>
                                            <p class="menu-card-description">
                                                <?= htmlspecialchars($categoria) ?> <?= htmlspecialchars($item['titulo']) ?>
                                            </p>
                                        </div>
                                        <div class="menu-card-arrow">
                                            <i class="bi bi-arrow-right"></i>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($opcoes_menu)): ?>
                <!-- Mensagem quando não há permissões -->
                <div class="no-permissions fade-in">
                    <div class="custom-card">
                        <div class="custom-card-body text-center">
                            <div class="custom-alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Nenhuma permissão encontrada</strong>
                            </div>
                            <p class="text-muted">
                                Seu perfil não possui permissões configuradas. Entre em contato com o administrador.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="footer-text">
                        <i class="bi bi-shield-lock me-1"></i>
                        Sistema de Gestão © 2025 - Moises João Ferreira | TDESN V3
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="footer-text">
                        <i class="bi bi-person-badge me-1"></i>
                        Logado como: <?= htmlspecialchars($nome_perfil) ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script-principal.js"></script>
</body>
</html>