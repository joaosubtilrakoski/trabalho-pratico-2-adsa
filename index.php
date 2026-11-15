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
Descritivo: Escreva um programa que calcule a soma de dois números
******************************************************************************/

require_once __DIR__ . "/config/conexao.php";

do {
    echo PHP_EOL . "===== LOCADORA DE CARROS =====" . PHP_EOL;
    echo "1 - Gerenciar clientes" . PHP_EOL;
    echo "2 - Gerenciar carros" . PHP_EOL;
    echo "3 - Gerenciar locações" . PHP_EOL;
    echo "0 - Sair" . PHP_EOL;
    $opcao = ler("Escolha uma opção: ");

    switch ($opcao) {
        case "1":
            $pasta = "clientes";
            break;
        case "2":
            $pasta = "carros";
            break;
        case "3":
            $pasta = "locacoes";
            break;
        case "0":
            echo "Programa encerrado." . PHP_EOL;
            continue 2;
        default:
            echo "Opção inválida." . PHP_EOL;
            pausar();
            continue 2;
    }

    do {
        echo PHP_EOL . "===== " . strtoupper($pasta) . " =====" . PHP_EOL;
        echo "1 - Cadastrar" . PHP_EOL;
        echo "2 - Listar" . PHP_EOL;
        echo "3 - Editar" . PHP_EOL;
        echo "4 - Excluir" . PHP_EOL;
        echo "0 - Voltar" . PHP_EOL;
        $acao = ler("Escolha uma opção: ");

        if ($acao === "0") {
            break;
        }

        $arquivos = [
            "1" => "cadastrar.php",
            "2" => "index.php",
            "3" => "editar.php",
            "4" => "excluir.php"
        ];

        if (isset($arquivos[$acao])) {
            require __DIR__ . "/" . $pasta . "/" . $arquivos[$acao];
        } else {
            echo "Opção inválida." . PHP_EOL;
        }
        pausar();
    } while (true);
} while (true);

$conn->close();
