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

abrirPagina("Carros");
echo '<div class="acoes">
    <a class="botao" href="cadastrar.php">+ Novo carro</a>
    <a class="botao secundario" href="../index.php">Voltar ao menu</a>
</div>';

$resultado = $conn->query("SELECT * FROM carros ORDER BY id DESC");

if ($resultado->num_rows === 0) {
    echo '<p class="vazio">Nenhum carro cadastrado.</p>';
} else {
    echo '<table>
        <tr><th>ID</th><th>Marca</th><th>Modelo</th><th>Ano</th><th>Placa</th><th>Diária</th><th>Status</th><th>Ações</th></tr>';
    while ($carro = $resultado->fetch_assoc()) {
        echo '<tr>
            <td>' . escapar($carro["id"]) . '</td>
            <td>' . escapar($carro["marca"]) . '</td>
            <td>' . escapar($carro["modelo"]) . '</td>
            <td>' . escapar($carro["ano"]) . '</td>
            <td>' . escapar($carro["placa"]) . '</td>
            <td>R$ ' . escapar($carro["valor_diaria"]) . '</td>
            <td>' . escapar($carro["status"]) . '</td>
            <td>
                <a class="botao" href="editar.php?id=' . (int)$carro["id"] . '">Editar</a>
                <a class="botao perigo" href="excluir.php?id=' . (int)$carro["id"] . '" onclick="return confirm(\'Deseja excluir este carro?\')">Excluir</a>
            </td>
        </tr>';
    }
    echo '</table>';
}
fecharPagina();
