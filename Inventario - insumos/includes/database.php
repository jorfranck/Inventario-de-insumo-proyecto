<?php

function conectarDB(){
    $db = mysqli_connect('localhost','','','inventaio_insumos');

    if(!$db){
        echo 'Error no se pudo conectar';
        exit;
    }

    return $db;
}