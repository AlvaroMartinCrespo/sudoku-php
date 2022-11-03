<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php require_once 'function.php'; ?>
    <?php require_once 'definicion_arrays.php'; ?>
    <?php
    $arrayOriginal = $arrayNumeros;


    if ($_POST['dificultad'] === 'medio') {

        /**
         * Si es la primera vez que el usuario accede a la página entrará por el else, ya que no esta creado insertar, eliminar ni candidatosAImprimir,
         * al entrar por el else se deserializa la array ya que la tenemos que imprimir y se serializa para posteriormente mandarla por POST..
         * Una vez el usuario haya activado alguno de los botones, entrará por el if y deserializará la array serializada que se ha mandado por POST.
         */
        if (isset($_POST['insertar']) || isset($_POST['eliminar']) || isset($_POST['candidatosAImprimir'])) {

            $arrayDeserializada = unserialize(base64_decode($_POST['arrayNow']));
        } else {

            $arrayDeserializado = unserialize(base64_decode($_POST['arraySerializado']));

            $arraySerializado = base64_encode(serialize($arrayDeserializado));
        }


        //Este if sirve para la selección de insertar, eliminar y candidatosAImprimir. 
        //La primera vez que el usuario entra a la página no pasa por aqui, va directamente al else que pinta el array sin ningun cambio.
        //Insertar
        if (isset($_POST['insertar'])) {

            /**
             * Para insertar un número en la array, utilizamos el array deserializado que obtenemos del código de arriba, obtenemos los valores de fila y columna 
             * que el usuario ha introducido y comprobamos que en esa fila y esa columna esta vacio, si esta vacio, en esa misma posicion se introduce el número que el 
             * usuario haya intruducido, sino no se introduce.
             */
            if ($_POST['numeroAIntroducir'] < 1 || $_POST['numeroAIntroducir'] > 9) {
                createTable("medio", $arrayDeserializada, $arrayOriginal);
            } else {

                $arrayDeserializada = insertarNumero('medio', $_POST['fila'], $_POST['columna'], $_POST['numeroAIntroducir'], $arrayDeserializada);

                createTable("medio", $arrayDeserializada, $arrayOriginal);
            }

            //Se vuelve a serializar la array para mandarla por POST.
            $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));

            //print_r($nuevaArraySerializada);

            //Eliminar
        } else if (isset($_POST['eliminar'])) {

            /**
             * Para eliminar un número de la array primero obtenemos acceso a la array original, con un require_once introducimos las array.
             * Comprobamos que el número que se quiere eliminar no esta en la array original, ya que si estuviera no se puede eliminar.
             * Y por último comprobamos que el número que ha introducido el usuario es igual al número que quiere eliminar de la array, si son iguales se eliminará
             * y sino no se eliminará.
             */

            if (!empty($_POST['numeroAIntroducir'])) {

                if ($arrayDeserializada['medio'][($_POST['fila'] - 1)][($_POST['columna']) - 1] !== $arrayOriginal['medio'][($_POST['fila'] - 1)][($_POST['columna']) - 1]) {

                    $arrayDeserializada = eliminarNumero('medio', $_POST['fila'], $_POST['columna'], $_POST['numeroAIntroducir'], $arrayDeserializada);
                }
            }
            //Se vuelve a pintar la array con o sin los cambios.
            createTable("medio", $arrayDeserializada, $arrayOriginal);

            //print_r($arrayDeserializada);

            //Se vuelve a serializar la array para posteriormente ser mandada por POST.
            $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));

            //Candidatos
        } else if (isset($_POST['candidatosAImprimir'])) {

            /**
             * Si la fila o la columna no ha sido introducido no se intentan mostrar los candidatosAImprimir
             * ya que sino va a dar un error.
             */
            if (empty($_POST['fila']) || empty($_POST['columna'])) {

                createTable("medio", $arrayDeserializada, $arrayOriginal);
                $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
            } else {

                if ($arrayDeserializada['medio'][($_POST['fila']) - 1][$_POST['columna'] - 1] !== 0) {
                    createTable("medio", $arrayDeserializada, $arrayOriginal);
                    $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
                } else {

                    /**
                     * Se crean 3 arrays, que van a almacenar los números de la fila, de la columna y del cuadro respectivamente
                     * , una vez almacenados, las arrays se unen en una sola con el método array_merge, quedando asi 
                     * $candidatosTotales, con los numeros de la fila, la columna y el cuadro.
                     * Se recorre la array de candidatosAImprimir , como hay hasta 9 en el sudoku, recorremos mientras
                     * i sea menor que 10. Creamos un contador a parte, guardamos en la array candidatosAImprimir los candidatosTotalees 
                     * correctos y lo imprimimos en el form.
                     */
                    $candidatosFila = array();
                    $candidatosColumna = array();
                    $candidatosCuadro = array();
                    $candidatosTotales = array();
                    $candidatosAImprimir = array();

                    //Candidatos de fila
                    $candidatosFila = candidatosFila('medio', $arrayDeserializada, $_POST['fila']);

                    //Candidatos de columna
                    $candidatosColumna = candidatosColumna('medio', $arrayDeserializada, $_POST['columna']);

                    //Candidatos de cuadrado
                    $indiceCuadro = indiceCuadro(($_POST['fila'] - 1), ($_POST['columna'] - 1));

                    $filaInicial = filaInicialCuadro($indiceCuadro);
                    $filaFinal = filaFinalCuadro($indiceCuadro);
                    $columnaInicial = columnaInicial($indiceCuadro);
                    $columnaFinal = columnaFinal($indiceCuadro);

                    $contadorCuadro = 0;

                    for ($i = $filaInicial; $i <= $filaFinal; $i++) {

                        for ($j = $columnaInicial; $j <= $columnaFinal; $j++) {

                            $candidatosCuadro[$contadorCuadro] = $arrayDeserializada['medio'][$i][$j];
                            $contadorCuadro += 1;
                        }
                    }

                    //print_r($candidatosCuadro);

                    $candidatosTotales = array_merge($candidatosFila, $candidatosColumna, $candidatosCuadro);

                    //print_r($candidatosTotales);

                    $contadorCandidatos = 0;

                    //Candidatos a imprimir
                    for ($i = 1; $i < count($candidatosTotales); $i++) {

                        if ($i < 10) {

                            if (!in_array($i, $candidatosTotales)) {

                                $candidatosAImprimir[$contadorCandidatos] = $i;
                                $contadorCandidatos += 1;
                            }
                        }
                    }

                    //print_r($candidatosAImprimir);
                    //print_r($candidatosFila);
                    //print_r($candidatosColumna);

                    createTable("medio", $arrayDeserializada, $arrayOriginal);
                    $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
                }
            }
        } else {
            createTable("medio", $arrayDeserializado, $arrayOriginal);
        }

        //A partir de aqui el código se reutiliza y es exactamente lo mismo para los otros dos modos de dificultad
    } else if ($_POST['dificultad'] === 'facil') {

        if (isset($_POST['insertar']) || isset($_POST['eliminar']) || isset($_POST['candidatosAImprimir'])) {

            $arrayDeserializada = unserialize(base64_decode($_POST['arrayNow']));
            //print_r($arrayDeserializada);
        } else {

            $arrayDeserializado = unserialize(base64_decode($_POST['arraySerializado']));
            //print_r($arrayDeserializado);
            $arraySerializado = base64_encode(serialize($arrayDeserializado));
            //$arraySerializado = $_POST['arraySerializado'];
            //print_r($arraySerializado);
        }


        if (isset($_POST['insertar'])) {

            if ($_POST['numeroAIntroducir'] < 1 || $_POST['numeroAIntroducir'] > 9) {
                createTable("facil", $arrayDeserializada, $arrayOriginal);
            } else {

                $arrayDeserializada = insertarNumero('facil', $_POST['fila'], $_POST['columna'], $_POST['numeroAIntroducir'], $arrayDeserializada);
                createTable("facil", $arrayDeserializada, $arrayOriginal);
            }

            //print_r($arrayDeserializada);

            $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));

            //print_r($nuevaArraySerializada);

        } else if (isset($_POST['eliminar'])) {

            if (!empty($_POST['numeroAIntroducir'])) {

                if ($arrayDeserializada['facil'][($_POST['fila'] - 1)][($_POST['columna']) - 1] !== $arrayOriginal['facil'][($_POST['fila'] - 1)][($_POST['columna']) - 1]) {

                    $arrayDeserializada = eliminarNumero('facil', $_POST['fila'], $_POST['columna'], $_POST['numeroAIntroducir'], $arrayDeserializada);
                }
            }


            createTable("facil", $arrayDeserializada, $arrayOriginal);

            //print_r($arrayDeserializada);

            $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
        } else if (isset($_POST['candidatosAImprimir'])) {

            if (empty($_POST['fila']) || empty($_POST['columna'])) {

                createTable("facil", $arrayDeserializada, $arrayOriginal);
                $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
            } else {

                if ($arrayDeserializada['facil'][($_POST['fila']) - 1][$_POST['columna'] - 1] !== 0) {
                    createTable("facil", $arrayDeserializada, $arrayOriginal);
                    $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
                } else {


                    $candidatosFila = array();
                    $candidatosColumna = array();
                    $candidatosCuadro = array();
                    $candidatosTotales = array();
                    $candidatosAImprimir = array();

                    //Candidatos de fila
                    $candidatosFila = candidatosFila('facil', $arrayDeserializada, $_POST['fila']);

                    //Candidatos de columna
                    $candidatosColumna = candidatosColumna('facil', $arrayDeserializada, $_POST['columna']);

                    //Candidatos de cuadrado
                    $indiceCuadro = indiceCuadro(($_POST['fila'] - 1), ($_POST['columna'] - 1));

                    $filaInicial = filaInicialCuadro($indiceCuadro);
                    $filaFinal = filaFinalCuadro($indiceCuadro);
                    $columnaInicial = columnaInicial($indiceCuadro);
                    $columnaFinal = columnaFinal($indiceCuadro);

                    $contadorCuadro = 0;

                    for ($i = $filaInicial; $i <= $filaFinal; $i++) {

                        for ($j = $columnaInicial; $j <= $columnaFinal; $j++) {

                            $candidatosCuadro[$contadorCuadro] = $arrayDeserializada['facil'][$i][$j];
                            $contadorCuadro += 1;
                        }
                    }

                    //print_r($candidatosCuadro);

                    $candidatosTotales = array_merge($candidatosFila, $candidatosColumna, $candidatosCuadro);

                    //print_r($candidatosTotales);

                    $contadorCandidatos = 0;

                    for ($i = 1; $i < count($candidatosTotales); $i++) {

                        if ($i < 10) {

                            if (!in_array($i, $candidatosTotales)) {

                                $candidatosAImprimir[$contadorCandidatos] = $i;
                                $contadorCandidatos += 1;
                            }
                        }
                    }

                    //print_r($candidatosAImprimir);
                    //print_r($candidatosFila);
                    //print_r($candidatosColumna);

                    createTable("facil", $arrayDeserializada, $arrayOriginal);
                    $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
                }
            }
        } else {
            createTable("facil", $arrayDeserializado, $arrayOriginal);
        }
    } else {


        if (isset($_POST['insertar']) || isset($_POST['eliminar']) || isset($_POST['candidatosAImprimir'])) {

            $arrayDeserializada = unserialize(base64_decode($_POST['arrayNow']));
            //print_r($arrayDeserializada);

        } else {

            $arrayDeserializado = unserialize(base64_decode($_POST['arraySerializado']));
            //print_r($arrayDeserializado);
            $arraySerializado = base64_encode(serialize($arrayDeserializado));
            //$arraySerializado = $_POST['arraySerializado'];
            //print_r($arraySerializado);
        }


        if (isset($_POST['insertar'])) {

            if ($_POST['numeroAIntroducir'] < 1 || $_POST['numeroAIntroducir'] > 9) {
                createTable("dificil", $arrayDeserializada, $arrayOriginal);
            } else {

                $arrayDeserializada = insertarNumero('dificil', $_POST['fila'], $_POST['columna'], $_POST['numeroAIntroducir'], $arrayDeserializada);
                createTable("dificil", $arrayDeserializada, $arrayOriginal);
            }

            //print_r($arrayDeserializada);

            $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));

            //print_r($nuevaArraySerializada);

        } else if (isset($_POST['eliminar'])) {

            if (!empty($_POST['numeroAIntroducir'])) {

                if ($arrayDeserializada['dificil'][($_POST['fila'] - 1)][($_POST['columna']) - 1] !== $arrayOriginal['dificil'][($_POST['fila'] - 1)][($_POST['columna']) - 1]) {

                    $arrayDeserializada = eliminarNumero('dificil', $_POST['fila'], $_POST['columna'], $_POST['numeroAIntroducir'], $arrayDeserializada);
                    /*if ($arrayDeserializada['dificil'][($_POST['fila'] - 1)][($_POST['columna']) - 1] === $_POST['numeroAIntroducir']) {
                        $arrayDeserializada['dificil'][($_POST['fila'] - 1)][($_POST['columna']) - 1] = 0;
                    }*/
                }
            }
            createTable("dificil", $arrayDeserializada, $arrayOriginal);

            //print_r($arrayDeserializada);

            $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
        } else if (isset($_POST['candidatosAImprimir'])) {
            if (empty($_POST['fila']) || empty($_POST['columna'])) {

                createTable("dificil", $arrayDeserializada, $arrayOriginal);
                $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
            } else {

                if ($arrayDeserializada['dificil'][($_POST['fila']) - 1][$_POST['columna'] - 1] !== 0) {
                    createTable("dificil", $arrayDeserializada, $arrayOriginal);
                    $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
                } else {


                    $candidatosFila = array();
                    $candidatosColumna = array();
                    $candidatosCuadro = array();
                    $candidatosTotales = array();
                    $candidatosAImprimir = array();

                    //Candidatos de fila
                    $candidatosFila = candidatosFila('dificil', $arrayDeserializada, $_POST['fila']);

                    //Candidatos de columna
                    $candidatosColumna = candidatosColumna('dificil', $arrayDeserializada, $_POST['columna']);

                    //Candidatos de cuadrado
                    $indiceCuadro = indiceCuadro(($_POST['fila'] - 1), ($_POST['columna'] - 1));

                    $filaInicial = filaInicialCuadro($indiceCuadro);
                    $filaFinal = filaFinalCuadro($indiceCuadro);
                    $columnaInicial = columnaInicial($indiceCuadro);
                    $columnaFinal = columnaFinal($indiceCuadro);

                    $contadorCuadro = 0;

                    for ($i = $filaInicial; $i <= $filaFinal; $i++) {

                        for ($j = $columnaInicial; $j <= $columnaFinal; $j++) {

                            $candidatosCuadro[$contadorCuadro] = $arrayDeserializada['dificil'][$i][$j];
                            $contadorCuadro += 1;
                        }
                    }

                    //print_r($candidatosCuadro);

                    $candidatosTotales = array_merge($candidatosFila, $candidatosColumna, $candidatosCuadro);

                    //print_r($candidatosTotales);

                    $contadorCandidatos = 0;

                    for ($i = 1; $i < count($candidatosTotales); $i++) {

                        if ($i < 10) {

                            if (!in_array($i, $candidatosTotales)) {

                                $candidatosAImprimir[$contadorCandidatos] = $i;
                                $contadorCandidatos += 1;
                            }
                        }
                    }

                    //print_r($candidatosAImprimir);
                    //print_r($candidatosFila);
                    //print_r($candidatosColumna);

                    createTable("dificil", $arrayDeserializada, $arrayOriginal);
                    $nuevaArraySerializada = base64_encode(serialize($arrayDeserializada));
                }
            }
        } else {
            createTable("dificil", $arrayDeserializado, $arrayOriginal);
        }
    }

    ?>

    <form class="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

        <div> <label for="numero">Número</label>
            <input type="number" name="numeroAIntroducir" value=<?php if (!empty($_POST['numeroAIntroducir'])) {
                                                                    echo $_POST['numeroAIntroducir'];
                                                                } ?>>
        </div>

        <div> <label for="fila">Fila</label>
            <input type="number" name="fila" value=<?php if (!empty($_POST['fila'])) {
                                                        echo $_POST['fila'];
                                                    } ?>>
        </div>

        <div> <label for="columna">Columna</label>
            <input type="number" name="columna" value=<?php if (!empty($_POST['columna'])) {
                                                            echo $_POST['columna'];
                                                        } ?>>
        </div>

        <?php
        /**
         * Este if se utiliza para la primera vez que el usuario accede a la página. 
         * La primera vez que accede se muestra la array sin ningun cambio, al hacer el cambio se manda el array, se deserializa , se le hacen los cambios
         * se vuelve a serializar y se manda pero esta vez por el if.
         *  */
        if (isset($_POST['insertar']) || isset($_POST['eliminar']) || isset($_POST['candidatosAImprimir'])) {

        ?>
            <input type="hidden" name="arrayNow" value=<?php echo $nuevaArraySerializada ?>>
        <?php
        } else {
        ?>
            <!--Primera vez que el cliente le da a cualquier botón-->
            <input type="hidden" name="arrayNow" value=<?php echo $arraySerializado ?>>



        <?php
        }

        ?>
        <input type="hidden" name="dificultad" value=<?php echo $_POST['dificultad'] ?>>
        <input type="submit" value="Insertar" name="insertar">
        <input type="submit" value="Eliminar" name="eliminar">



        <p>
            <input type="submit" name="candidatosAImprimir" value="Candidatos">
            <?php
            //Imprimimos la array de candidatosAImprimir.
            if (isset($_POST['candidatosAImprimir'])) {

                if (isset($candidatosAImprimir)) {
                    for ($i = 0; $i < count($candidatosAImprimir); $i++) {
                        echo $candidatosAImprimir[$i] . " ";
                    }
                }
            }
            ?>
        </p>

    </form>
</body>

</html>