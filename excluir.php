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
$consulta = $conn->prepare("SELECT carro_id FROM locacoes WHERE id=?");
$consulta->bind_param("i", $id);
$consulta->execute();
$locacao = $consulta->get_result()->fetch_assoc();

if ($locacao) {
    $conn->begin_transaction();
    try {
        $excluir = $conn->prepare("DELETE FROM locacoes WHERE id=?");
        $excluir->bind_param("i", $id);
        $excluir->execute();

        // Libera o carro somente se ele não possuir outra locação ativa.
        $verificar = $conn->prepare("SELECT COUNT(*) AS total FROM locacoes WHERE carro_id=? AND status='Ativa'");
        $verificar->bind_param("i", $locacao["carro_id"]);
        $verificar->execute();
        $resultado = $verificar->get_result()->fetch_assoc();

        if ((int)$resultado["total"] === 0) {
            $liberar = $conn->prepare("UPDATE carros SET status='Disponível' WHERE id=?");
            $liberar->bind_param("i", $locacao["carro_id"]);
            $liberar->execute();
        }

        $conn->commit();
    } catch (Throwable $erro) {
        $conn->rollback();
        die("Não foi possível excluir a locação.");
    }
}

header("Location: index.php");
exit;
