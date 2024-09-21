<?php

$timestamp = microtime(); // Retorna o timestamp atual com microsegundos
$timestamp = explode(" ", $timestamp); // tiro todos os espaços da variavel que contem os microsegundos
$timestamp = $timestamp[1] + $timestamp[0];
$start = $timestamp; // Starto o tempo atual

$timestamp = microtime();
$timestamp = explode(" ", $timestamp);
$timestamp = $timestamp[1] + $timestamp[0];
$finish = $timestamp;
$totaltime = ($finish - $start); // calculo a diferença entre o tempo inicial e até carregar a pagina por completo
printf ("Esta pagina demorou %f segundos para ser carregada!", $totaltime);
?>