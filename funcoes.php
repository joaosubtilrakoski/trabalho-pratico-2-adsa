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

require_once __DIR__ . "/conexao.php";

/**
 * Protege os textos exibidos no navegador contra HTML inserido pelo usuário.
 */
function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

/**
 * Mostra o início padrão de todas as páginas.
 */
function abrirPagina($titulo) {
    echo '<!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . escapar($titulo) . ' | Locadora</title>
        <style>
            * { box-sizing: border-box; }
            body { margin: 0; font-family: Arial, sans-serif; background: #f3f5f8; color: #263238; }
            header { background: #17365d; color: white; padding: 22px 16px; text-align: center; }
            header h1 { margin: 0 0 6px; }
            header p { margin: 0; opacity: .9; }
            main { width: min(1100px, 94%); margin: 28px auto; background: white; padding: 24px; border-radius: 10px; box-shadow: 0 2px 12px #00000010; }
            h2 { margin-top: 0; color: #17365d; }
            .acoes { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
            .botao, button { display: inline-block; border: 0; border-radius: 5px; padding: 10px 14px; background: #1769aa; color: white; text-decoration: none; cursor: pointer; font-size: 14px; }
            .secundario { background: #667085; }
            .perigo { background: #b42318; }
            .formulario { max-width: 650px; }
            label { display: block; font-weight: bold; margin: 13px 0 5px; }
            input, select { width: 100%; padding: 10px; border: 1px solid #cbd2d9; border-radius: 5px; font-size: 15px; }
            table { width: 100%; border-collapse: collapse; margin-top: 14px; }
            th, td { padding: 11px 9px; border-bottom: 1px solid #e3e7eb; text-align: left; vertical-align: top; }
            th { background: #edf2f7; }
            .mensagem { background: #e8f5e9; color: #205b2b; border-radius: 5px; padding: 12px; margin: 12px 0; }
            .erro { background: #fdecec; color: #8a1c1c; border-radius: 5px; padding: 12px; margin: 12px 0; }
            .cartoes { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
            .cartao { background: #edf4fb; border: 1px solid #dce8f5; padding: 20px; border-radius: 8px; }
            .cartao h3 { margin-top: 0; }
            .vazio { color: #667085; padding: 18px 0; }
            @media (max-width: 700px) { main { padding: 15px; overflow-x: auto; } table { min-width: 700px; } }
        </style>
    </head>
    <body>
        <header>
            <h1>Locadora de Carros</h1>
            <p>Sistema de gerenciamento de clientes, veículos e locações</p>
        </header>
        <main>
            <h2>' . escapar($titulo) . '</h2>';
}

function fecharPagina() {
    echo '</main></body></html>';
}

function mostrarMensagem($texto, $tipo = "sucesso") {
    $classe = ($tipo === "erro") ? "erro" : "mensagem";
    echo '<div class="' . $classe . '">' . escapar($texto) . '</div>';
}
