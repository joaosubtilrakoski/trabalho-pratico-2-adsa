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

abrirPagina("Clientes");
echo '<div class="acoes">
    <a class="botao" href="cadastrar.php">+ Novo cliente</a>
    <a class="botao secundario" href="../index.php">Voltar ao menu</a>
</div>';

$resultado = $conn->query("SELECT * FROM clientes ORDER BY id DESC");

if ($resultado->num_rows === 0) {
    echo '<p class="vazio">Nenhum cliente cadastrado.</p>';
} else {
    echo '<table>
        <tr><th>ID</th><th>Nome</th><th>CPF</th><th>Telefone</th><th>E-mail</th><th>Endereço</th><th>Ações</th></tr>';
    while ($cliente = $resultado->fetch_assoc()) {
        echo '<tr>
            <td>' . escapar($cliente["id"]) . '</td>
            <td>' . escapar($cliente["nome"]) . '</td>
            <td>' . escapar($cliente["cpf"]) . '</td>
            <td>' . escapar($cliente["telefone"]) . '</td>
            <td>' . escapar($cliente["email"]) . '</td>
            <td>' . escapar($cliente["endereco"]) . '</td>
            <td>
                <a class="botao" href="editar.php?id=' . (int)$cliente["id"] . '">Editar</a>
                <a class="botao perigo" href="excluir.php?id=' . (int)$cliente["id"] . '" onclick="return confirm(\'Deseja excluir este cliente?\')">Excluir</a>
            </td>
        </tr>';
    }
    echo '</table>';
}
fecharPagina();
