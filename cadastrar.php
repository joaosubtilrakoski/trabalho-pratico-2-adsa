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
    $cliente_id = (int)($_POST["cliente_id"] ?? 0);
    $carro_id = (int)($_POST["carro_id"] ?? 0);
    $data_inicio = $_POST["data_inicio"] ?? "";
    $data_fim = $_POST["data_fim"] ?? "";

    if ($data_inicio === "" || $data_fim === "" || $data_fim <= $data_inicio) {
        $erro = "A data final deve ser posterior à data inicial.";
    } else {
        $consulta = $conn->prepare("SELECT valor_diaria FROM carros WHERE id=? AND status='Disponível'");
        $consulta->bind_param("i", $carro_id);
        $consulta->execute();
        $carro = $consulta->get_result()->fetch_assoc();

        $clienteConsulta = $conn->prepare("SELECT id FROM clientes WHERE id=?");
        $clienteConsulta->bind_param("i", $cliente_id);
        $clienteConsulta->execute();
        $clienteExiste = $clienteConsulta->get_result()->num_rows > 0;

        if (!$clienteExiste) {
            $erro = "Selecione um cliente válido.";
        } elseif (!$carro) {
            $erro = "Selecione um carro que esteja disponível.";
        } else {
            $inicio = new DateTime($data_inicio);
            $fim = new DateTime($data_fim);
            $dias = max(1, $inicio->diff($fim)->days);
            $valor_total = $dias * (float)$carro["valor_diaria"];

            $conn->begin_transaction();
            try {
                $sql = "INSERT INTO locacoes (cliente_id, carro_id, data_inicio, data_fim, valor_total, status)
                        VALUES (?, ?, ?, ?, ?, 'Ativa')";
                $inserir = $conn->prepare($sql);
                $inserir->bind_param("iissd", $cliente_id, $carro_id, $data_inicio, $data_fim, $valor_total);
                $inserir->execute();

                $atualizar = $conn->prepare("UPDATE carros SET status='Alugado' WHERE id=?");
                $atualizar->bind_param("i", $carro_id);
                $atualizar->execute();

                $conn->commit();
                header("Location: index.php");
                exit;
            } catch (Throwable $erroExecucao) {
                $conn->rollback();
                $erro = "Não foi possível registrar a locação. Tente novamente.";
            }
        }
    }
}

$clientes = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");
$carros = $conn->query("SELECT id, marca, modelo, placa, valor_diaria FROM carros WHERE status='Disponível' ORDER BY marca");

abrirPagina("Cadastrar locação");
if ($erro !== "") mostrarMensagem($erro, "erro");

echo '<form class="formulario" method="post">
    <label>Cliente *</label><select name="cliente_id" required><option value="">Selecione um cliente</option>';
while ($cliente = $clientes->fetch_assoc()) {
    echo '<option value="' . (int)$cliente["id"] . '">' . escapar($cliente["nome"]) . '</option>';
}
echo '</select><label>Carro disponível *</label><select name="carro_id" required><option value="">Selecione um carro</option>';
while ($carro = $carros->fetch_assoc()) {
    $descricao = $carro["marca"] . " " . $carro["modelo"] . " - " . $carro["placa"] . " (R$ " . $carro["valor_diaria"] . "/dia)";
    echo '<option value="' . (int)$carro["id"] . '">' . escapar($descricao) . '</option>';
}
echo '</select>
    <label>Data de início *</label><input type="date" name="data_inicio" required>
    <label>Data de fim *</label><input type="date" name="data_fim" required>
    <p><button type="submit">Registrar locação</button>
    <a class="botao secundario" href="index.php">Cancelar</a></p>
</form>';

fecharPagina();
