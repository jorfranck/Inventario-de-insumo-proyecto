<?php

function conectarDB(){
    $db = mysqli_connect('localhost','root','30346174','inventaio_insumos');

    if(!$db){
        echo 'Error no se pudo conectar';
        exit;
    }

    return $db;
}