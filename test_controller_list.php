<?php
chdir(__DIR__ . '/controller');
// Simular petición GET
$_GET['boton'] = 'listar';
require 'usuario.php';
