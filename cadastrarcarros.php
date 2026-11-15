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
    $marca = trim($_POST["marca"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $ano = (int)($_POST["ano"] ?? 0);
    $placa = strtoupper(trim($_POST["placa"] ?? ""));
    $valor = (float)($_POST["valor_diaria"] ?? 0);

    if ($marca === "" || $modelo === "" || $ano < 1900 || $placa === "" || $valor <= 0) {
        $erro = "Confira os campos. O valor da diária deve ser maior que zero.";
    } else {
        $sql = "INSERT INTO carros (marca, modelo, ano, placa, valor_diaria, status) VALUES (?, ?, ?, ?, ?, 'Disponível')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisd", $marca, $modelo, $ano, $placa, $valor);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        }
        $erro = "Não foi possível cadastrar. Verifique se a placa já está cadastrada.";
    }
}

abrirPagina("Cadastrar carro");
if ($erro !== "") mostrarMensagem($erro, "erro");

echo '<form class="formulario" method="post">
    <label>Marca *</label><input name="marca" required>
    <label>Modelo *</label><input name="modelo" required>
    <label>Ano *</label><input type="number" name="ano" min="1900" max="2100" required>
    <label>Placa *</label><input name="placa" required>
    <label>Valor da diária (R$) *</label><input type="number" name="valor_diaria" min="0.01" step="0.01" required>
    <p><button type="submit">Salvar carro</button>
    <a class="botao secundario" href="index.php">Cancelar</a></p>
</form>';

fecharPagina();
