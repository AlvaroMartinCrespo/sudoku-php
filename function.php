
<?php
//Creamos una funcion para imprimir una de las 3 arrays que queremos.
function createTable($nombre, $tabla, $tablaOriginal)
{

    echo "<h2>$nombre</h2>";
    echo '<table>';

    for ($i = 0; $i < 9; $i++) {

        echo '<tr>';

        for ($j = 0; $j < 9; $j++) {

            if (empty($tabla[$nombre][$i][$j])) {
                echo '<td class="blue">.</td>';
            } else {

                if($tabla[$nombre][$i][$j] === $tablaOriginal[$nombre][$i][$j]){

                    echo '<td class="red" >' . $tabla[$nombre][$i][$j] . '</td>';

                }else{

                    echo '<td class="blue" >' . $tabla[$nombre][$i][$j] . '</td>';

                }

            }
        }
        echo '</tr>';
    }

    echo '</table>';
}


function insertarNumero($nombre, $fila, $columna, $numeroAIntroducir, $array){

    if (empty($array[$nombre][($fila - 1)][($columna - 1)])) {
        $array[$nombre][($fila - 1)][($columna - 1)] = $numeroAIntroducir;
    }
    
    return $array;

}

function eliminarNumero($nombre, $fila, $columna, $numeroAIntroducir, $array){

    if ($array[$nombre][($fila - 1)][($columna - 1)] === $numeroAIntroducir) {
        $array[$nombre][($fila - 1)][($columna - 1)] = 0;
    }

    return $array;
}

function numeroIsInFila($nombre ,$numero, $fila, $array){

    for ($i = 0; $i < 9; $i++) {

        if($array[$nombre][$fila - 1][$i] === $numero){
            return true;
        }  
    }

}

function numeroIsInColumna($nombre ,$numero, $columna, $array){

    for ($i = 0; $i < 9; $i++) {

        if($array[$nombre][$i][$columna - 1] === $numero){
            return true;
        }  
    }

}

function candidatosFila($nombre, $array, $fila){


    for ($i = 0; $i < 9; $i++) {
        $candidatos[$i] = $array[$nombre][($fila - 1)][$i];
    }

    return $candidatos;

}

function candidatosColumna($nombre, $array, $columna){

    for ($i = 0; $i < 9; $i++) {
        $candidatos[$i] = $array[$nombre][$i][($columna - 1)];
    }

    return $candidatos;

}

function indiceCuadro($fila, $columna){

    if($fila >= 0 && $fila <= 2 && $columna >= 0 && $columna <= 2 ){
        return 0;
    }else if($fila >= 0 && $fila <= 2 && $columna >= 3 && $columna <= 5 ){
        return 1;
    }else if($fila >= 0 && $fila <= 2 && $columna >= 6 && $columna <= 8 ){
        return 2;
    }else if($fila >= 3 && $fila <= 5 && $columna >= 0 && $columna <= 2 ){
        return 3;
    }else if($fila >= 3 && $fila <= 5 && $columna >= 3 && $columna <= 5 ){
        return 4;
    }else if($fila >= 3 && $fila <= 5 && $columna >= 6 && $columna <= 8 ){
        return 5;
    }else if($fila >= 6 && $fila <= 8 && $columna >= 0 && $columna <= 2 ){
        return 6;
    }else if($fila >= 6 && $fila <= 8 && $columna >= 3 && $columna <= 5 ){
        return 7;
    }else if($fila >= 6 && $fila <= 8 && $columna >= 6 && $columna <= 8 ){
        return 8;
    }else{
        return -1;
    }

}

function filaInicialCuadro($indiceCuadro){

    if($indiceCuadro === 0 || $indiceCuadro === 1 || $indiceCuadro === 2){
        return 0;
    }else if($indiceCuadro === 3 || $indiceCuadro === 4 || $indiceCuadro === 5){
        return 3;
    }else if($indiceCuadro === 6 || $indiceCuadro === 7 || $indiceCuadro === 8){
        return 6;
    } else{
        return -1;
    }
    
}

function filaFinalCuadro($indiceCuadro){

    if($indiceCuadro === 0 || $indiceCuadro === 1 || $indiceCuadro === 2){
        return 2;
    }else if($indiceCuadro === 3 || $indiceCuadro === 4 || $indiceCuadro === 5){
        return 5;
    }else if($indiceCuadro === 6 || $indiceCuadro === 7 || $indiceCuadro === 8){
        return 8;
    } else{
        return -1;
    }

}

function columnaInicial($indiceCuadro){

    if($indiceCuadro === 0 || $indiceCuadro === 3 || $indiceCuadro === 6){
        return 0;
    }else if($indiceCuadro === 1 || $indiceCuadro === 4 || $indiceCuadro === 7){
        return 3;
    }else if($indiceCuadro === 2 || $indiceCuadro === 5 || $indiceCuadro === 8){
        return 6;
    } else{
        return -1;
    }

}

function columnaFinal($indiceCuadro){

    if($indiceCuadro === 0 || $indiceCuadro === 3 || $indiceCuadro === 6){
        return 2;
    }else if($indiceCuadro === 1 || $indiceCuadro === 4 || $indiceCuadro === 7){
        return 5;
    }else if($indiceCuadro === 2 || $indiceCuadro === 5 || $indiceCuadro === 8){
        return 8;
    } else{
        return -1;
    }

}

