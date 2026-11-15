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
$stmt = $conn->prepare("DELETE FROM carros WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Não foi possível excluir. Este carro pode estar vinculado a uma locação. <a href='index.php'>Voltar</a>");
}

header("Location: index.php");
exit;
