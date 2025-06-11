<?php

session_start();
require_once 'conexao.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO
// SUPONDO QUE O PERFIL 1 OU 2 SEJA O ADMINISTRADOR OU SECRETARIA
if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Você não tem permissão para acessar esta página.');</script>";
    header("Location: principal.php");
    exit();
}

$usuario = []; // INICIALIZA A VARIÁVEL COMO UM ARRAY VAZIO PARA EVITAR ERROS

// SE O FORMULÁRIO FOR ENVIADO, BUSCA O USUÁRIO NO BANCO DE DADOS PELO ID OU NOME
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty ($_POST['busca'])) {
    $busca = trim($_POST['busca']);

//VERIFICA SE A BUSCA É UM NÚMERO (id) OU UM (nome)
    if (is_numeric($busca)) {
        $sql="SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':busca',$busca, PDO::PARAM_INT);
    } else {
        $sql="SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindValue(':busca_nome',"%$busca%", PDO::PARAM_STR);
    }
} else {
    $sql="SELECT * FROM usuario ORDER BY id_usuario ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Buscar Usuários</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
</head>
<body>
    <h2>Lista de Usuários</h2>

    <!-- FORMULÁRIO DE BUSCA -->
    <form action="buscar_usuario.php" method="POST">
        <label for="busca">Buscar Usuário (ID ou Nome):</label>
        <input type="text" name="busca" required>
        <button type="submit">Buscar</button>
    </form>

    <!-- TABELA DE RESULTADOS -->
    <?php if(!empty($usuario)): ?>
        <h3>Resultados da Busca:</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuario as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($user['nome']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['id_perfil']); ?></td>
                        <td>
                            <!-- AÇÕES -->
                            <a href="alterar_usuario.php?id=<?php echo htmlspecialchars($user['id_usuario']); ?>" onclick="return confirm ('Tem certeza que deseja alterar o usuário?')">Alterar</a> |
                            <a href="excluir_usuario.php?id=<?php echo htmlspecialchars($user['id_usuario']); ?>" onclick="return confirm ('Tem certeza que deseja excluir o usuário?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>
    <br>
    <a href="cadastro_usuario.php">Cadastrar Novo Usuário</a>
    <br>
    <a href="principal.php">Voltar para o Painel Principal</a>
    <br>
</body>
</html>