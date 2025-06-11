<?php

session_start();
require_once 'conexao.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO
// SUPONDO QUE O PERFIL 1 SEJA O ADMINISTRADOR
if($_SESSION['perfil'] != 1) {
    echo "<script>alert('Você não tem permissão para acessar esta página.');</script>";
    header("Location: principal.php");
    exit();
}

if($_SERVER ["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
    $stmt->bindParam(":id_perfil", $id_perfil, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar usuário.');</script>";
    }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Cadastro de Usuário</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
</head>
<body> 
    <h2>Cadastrar Usuários</h2>
    <form action="cadastro_usuario.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <br>

        <label for="id_perfil">Perfil:</label>
        <select name="id_perfil" required>
            <?php
                // BUSCA OS PERFIS CADASTRADOS
                $sql = "SELECT * FROM perfil";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $perfis = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach($perfis as $perfil) {
                    echo "<option value='{$perfil['id_perfil']}'>{$perfil['nome_perfil']}</option>";
                }
            ?>
        </select>
        <br>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>