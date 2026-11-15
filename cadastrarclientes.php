<?php
/******************************************************************************
Curso: Tecnologia em Análise e Desenvolvimento de Sistemas
Disciplina: Linguagem e Técnicas de Programacão
Professor: Flores
Turma: ADS2A
Componentes:
 26007982-2 - joao gabriel subtil rakoski
 26010149-2 - jean carlos
 26013478-2 - davi ferreira da costa
 26008617-2 - fernando cesar cravo
 26008668-2 - gabriel eduardo consorte
Data: 15 de Novembro de 2026
Descritivo: Sistema CRUD para gerenciamento de uma locadora de carros
******************************************************************************/

require_once __DIR__ . "/../config/funcoes.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $cpf = trim($_POST["cpf"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");

    if ($nome === "" || $cpf === "" || $telefone === "" || $email === "") {
        $erro = "Preencha todos os campos obrigatórios.";
    } else {
        $sql = "INSERT INTO clientes (nome, cpf, telefone, email, endereco) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $cpf, $telefone, $email, $endereco);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        }
        $erro = "Não foi possível cadastrar. Verifique os dados informados.";
    }
}

abrirPagina("Cadastrar cliente");
if ($erro !== "") mostrarMensagem($erro, "erro");

echo '<form class="formulario" method="post">
    <label>Nome *</label><input type="text" name="nome" required>
    <label>CPF *</label><input type="text" name="cpf" required>
    <label>Telefone *</label><input type="text" name="telefone" required>
    <label>E-mail *</label><input type="email" name="email" required>
    <label>Endereço</label><input type="text" name="endereco">
    <p><button type="submit">Salvar cliente</button>
    <a class="botao secundario" href="index.php">Cancelar</a></p>
</form>';

fecharPagina();
