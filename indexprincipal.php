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

require_once __DIR__ . "/config/funcoes.php";

abrirPagina("Menu principal");

echo '<div class="cartoes">
    <section class="cartao">
        <h3>Clientes</h3>
        <p>Cadastre, consulte, edite e exclua clientes.</p>
        <a class="botao" href="clientes/index.php">Acessar clientes</a>
    </section>
    <section class="cartao">
        <h3>Carros</h3>
        <p>Gerencie os veículos disponíveis na locadora.</p>
        <a class="botao" href="carros/index.php">Acessar carros</a>
    </section>
    <section class="cartao">
        <h3>Locações</h3>
        <p>Registre aluguéis e acompanhe os períodos.</p>
        <a class="botao" href="locacoes/index.php">Acessar locações</a>
    </section>
</div>';

fecharPagina();
