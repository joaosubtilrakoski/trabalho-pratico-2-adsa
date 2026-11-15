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

$id = (int)($_GET["id"] ?? 0);
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();

if (!$cliente) {
    die("Cliente não encontrado.");
}

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
        $sql = "UPDATE clientes SET nome=?, cpf=?, telefone=?, email=?, endereco=? WHERE id=?";
        $atualizar = $conn->prepare($sql);
        $atualizar->bind_param("sssssi", $nome, $cpf, $telefone, $email, $endereco, $id);

        if ($atualizar->execute()) {
            header("Location: index.php");
            exit;
        }
        $erro = "Não foi possível atualizar o cliente.";
    }
}

abrirPagina("Editar cliente");
if ($erro !== "") mostrarMensagem($erro, "erro");

echo '<form class="formulario" method="post">
    <label>Nome *</label><input name="nome" required value="' . escapar($cliente["nome"]) . '">
    <label>CPF *</label><input name="cpf" required value="' . escapar($cliente["cpf"]) . '">
    <label>Telefone *</label><input name="telefone" required value="' . escapar($cliente["telefone"]) . '">
    <label>E-mail *</label><input type="email" name="email" required value="' . escapar($cliente["email"]) . '">
    <label>Endereço</label><input name="endereco" value="' . escapar($cliente["endereco"]) . '">
    <p><button type="submit">Atualizar cliente</button>
    <a class="botao secundario" href="index.php">Cancelar</a></p>
</form>';

fecharPagina();
