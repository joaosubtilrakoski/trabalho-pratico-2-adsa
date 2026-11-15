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
$stmt = $conn->prepare("SELECT * FROM carros WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$carro = $stmt->get_result()->fetch_assoc();

if (!$carro) die("Carro não encontrado.");

$erro = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $marca = trim($_POST["marca"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $ano = (int)($_POST["ano"] ?? 0);
    $placa = strtoupper(trim($_POST["placa"] ?? ""));
    $valor = (float)($_POST["valor_diaria"] ?? 0);
    $status = $_POST["status"] ?? "Disponível";

    if ($marca === "" || $modelo === "" || $ano < 1900 || $placa === "" || $valor <= 0) {
        $erro = "Confira os dados informados.";
    } else {
        $sql = "UPDATE carros SET marca=?, modelo=?, ano=?, placa=?, valor_diaria=?, status=? WHERE id=?";
        $atualizar = $conn->prepare($sql);
        $atualizar->bind_param("ssisdsi", $marca, $modelo, $ano, $placa, $valor, $status, $id);

        if ($atualizar->execute()) {
            header("Location: index.php");
            exit;
        }
        $erro = "Não foi possível atualizar. Verifique se a placa já existe.";
    }
}

abrirPagina("Editar carro");
if ($erro !== "") mostrarMensagem($erro, "erro");

echo '<form class="formulario" method="post">
    <label>Marca *</label><input name="marca" required value="' . escapar($carro["marca"]) . '">
    <label>Modelo *</label><input name="modelo" required value="' . escapar($carro["modelo"]) . '">
    <label>Ano *</label><input type="number" name="ano" min="1900" max="2100" required value="' . escapar($carro["ano"]) . '">
    <label>Placa *</label><input name="placa" required value="' . escapar($carro["placa"]) . '">
    <label>Valor da diária *</label><input type="number" name="valor_diaria" min="0.01" step="0.01" required value="' . escapar($carro["valor_diaria"]) . '">
    <label>Status</label><select name="status">';
foreach (["Disponível", "Alugado", "Manutenção"] as $opcao) {
    $selecionado = ($carro["status"] === $opcao) ? "selected" : "";
    echo '<option value="' . escapar($opcao) . '" ' . $selecionado . '>' . escapar($opcao) . '</option>';
}
echo '</select><p><button type="submit">Atualizar carro</button>
    <a class="botao secundario" href="index.php">Cancelar</a></p>
</form>';

fecharPagina();
