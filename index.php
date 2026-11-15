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

abrirPagina("Locações");
echo '<div class="acoes">
    <a class="botao" href="cadastrar.php">+ Nova locação</a>
    <a class="botao secundario" href="../index.php">Voltar ao menu</a>
</div>';

$sql = "SELECT l.id, c.nome AS cliente, v.marca, v.modelo, v.placa,
               l.data_inicio, l.data_fim, l.valor_total, l.status
        FROM locacoes l
        INNER JOIN clientes c ON c.id = l.cliente_id
        INNER JOIN carros v ON v.id = l.carro_id
        ORDER BY l.id DESC";
$resultado = $conn->query($sql);

if ($resultado->num_rows === 0) {
    echo '<p class="vazio">Nenhuma locação cadastrada.</p>';
} else {
    echo '<table>
        <tr><th>ID</th><th>Cliente</th><th>Carro</th><th>Início</th><th>Fim</th><th>Total</th><th>Status</th><th>Ações</th></tr>';
    while ($locacao = $resultado->fetch_assoc()) {
        echo '<tr>
            <td>' . escapar($locacao["id"]) . '</td>
            <td>' . escapar($locacao["cliente"]) . '</td>
            <td>' . escapar($locacao["marca"] . " " . $locacao["modelo"] . " - " . $locacao["placa"]) . '</td>
            <td>' . escapar($locacao["data_inicio"]) . '</td>
            <td>' . escapar($locacao["data_fim"]) . '</td>
            <td>R$ ' . escapar($locacao["valor_total"]) . '</td>
            <td>' . escapar($locacao["status"]) . '</td>
            <td>
                <a class="botao" href="editar.php?id=' . (int)$locacao["id"] . '">Editar</a>
                <a class="botao perigo" href="excluir.php?id=' . (int)$locacao["id"] . '" onclick="return confirm(\'Deseja excluir esta locação?\')">Excluir</a>
            </td>
        </tr>';
    }
    echo '</table>';
}
fecharPagina();
