<?php
session_start();
require_once 'conexao.php';

//GARANTE QUE O USUÁRIO ESTEJA LOGADO
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    echo "<script>alert('Você precisa estar logado para acessar esta página.');</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if($nova_senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem.');</script>";
    } elseif(strlen($nova_senha) < 8) {
        echo "<script>alert('A senha deve ter pelo menos 8 caracteres.');</script>";
    } elseif($nova_senha === "temp123") {
        echo "<script>alert('A senha não pode ser temporária.');</script>";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // ATUALIZA A SENHA E REMOVE O STATUS DE SENHA TEMPORÁRIA
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = FALSE WHERE id_usuario = :id_usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":id_usuario", $id_usuario);

        if ($stmt->execute()) {
            session_destroy();
            echo "<script>alert('Senha alterada com sucesso!');</script>";
            header("Location: principal.php");
            exit();
        } else {
            echo "<script>alert('Erro ao alterar a senha.');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar senha</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <form action="alterar_senha.php" method="POST">
        <h2>Recuperar Senha</h2>
        <p>Olá, <strong><?php echo $_SESSION['usuario']; ?></strong>. Digite sua nova senha abaixo:</p>
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" id="nova_senha" required>
        <br>
        
        <label for="confirmar_senha">Confirmar Senha:</label>
        <input type="password" name="confirmar_senha" id="confirmar_senha" required>
        
        <label>
            <input type="checkbox" name="mostrar_senha" id="mostrar_senha" onclick="mostrarSenha()"> Mostrar Senha
        </label>
        <br>
        <button type="submit">Alterar Senha</button>
    </form>

<script>
    function mostrarSenha() {
        var senha = document.getElementById("nova_senha");
        var confirmarSenha = document.getElementById("confirmar_senha");
        if (senha.type === "password") {
            senha.type = "text";
            confirmarSenha.type = "text";
        } else {
            senha.type = "password";
            confirmarSenha.type = "password";
        }
    }
</script>
</body>
</html>