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
$stmt = $conn->prepare("SELECT * FROM locacoes WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$locacao = $stmt->get_result()->fetch_assoc();

if (!$locacao) die("Locação não encontrada.");

$erro = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data_inicio = $_POST["data_inicio"] ?? "";
    $data_fim = $_POST["data_fim"] ?? "";
    $status = $_POST["status"] ?? "Ativa";

    if ($data_inicio === "" || $data_fim === "" || $data_fim <= $data_inicio) {
        $erro = "A data final deve ser posterior à data inicial.";
    } else {
        $consulta = $conn->prepare("SELECT valor_diaria FROM carros WHERE id=?");
        $consulta->bind_param("i", $locacao["carro_id"]);
        $consulta->execute();
        $carro = $consulta->get_result()->fetch_assoc();

        $inicio = new DateTime($data_inicio);
        $fim = new DateTime($data_fim);
        $dias = max(1, $inicio->diff($fim)->days);
        $valor_total = $dias * (float)$carro["valor_diaria"];

        $conn->begin_transaction();
        try {
            $sql = "UPDATE locacoes SET data_inicio=?, data_fim=?, valor_total=?, status=? WHERE id=?";
            $atualizar = $conn->prepare($sql);
            $atualizar->bind_param("ssdsi", $data_inicio, $data_fim, $valor_total, $status, $id);
            $atualizar->execute();

            $statusCarro = ($status === "Ativa") ? "Alugado" : "Disponível";
            $carroAtualizar = $conn->prepare("UPDATE carros SET status=? WHERE id=?");
            $carroAtualizar->bind_param("si", $statusCarro, $locacao["carro_id"]);
            $carroAtualizar->execute();

            $conn->commit();
            header("Location: index.php");
            exit;
        } catch (Throwable $erroExecucao) {
            $conn->rollback();
            $erro = "Não foi possível atualizar a locação.";
        }
    }
}

abrirPagina("Editar locação");
if ($erro !== "") mostrarMensagem($erro, "erro");

echo '<form class="formulario" method="post">
    <label>Data de início *</label><input type="date" name="data_inicio" required value="' . escapar($locacao["data_inicio"]) . '">
    <label>Data de fim *</label><input type="date" name="data_fim" required value="' . escapar($locacao["data_fim"]) . '">
    <label>Status</label><select name="status">';
foreach (["Ativa", "Finalizada", "Cancelada"] as $opcao) {
    $selecionado = ($locacao["status"] === $opcao) ? "selected" : "";
    echo '<option value="' . escapar($opcao) . '" ' . $selecionado . '>' . escapar($opcao) . '</option>';
}
echo '</select><p><button type="submit">Atualizar locação</button>
    <a class="botao secundario" href="index.php">Cancelar</a></p>
</form>';

fecharPagina();
