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
    //Codificamos las arrays para pasarlas por el post.
    $arraySerializado = base64_encode(serialize($arrayNumeros));

    ?>

    <!--Creamos la estructura mediante html e imprimimos las tablas con la función mediante php -->
    <div class="container">

        <form action="script2.php" method="post">

            <div class="tableContainer">

                <div class="tabla">
                    <?php createTable("facil", $arrayNumeros, $arrayNumeros); ?>
                </div>

                <div class="tabla">
                    <?php createTable("medio", $arrayNumeros, $arrayNumeros); ?>
                </div>

                <div class="tabla">
                    <?php createTable("dificil", $arrayNumeros, $arrayNumeros); ?>
                </div>

            </div>

            <div class="input">
                <div>
                    <input type="radio" name="dificultad" value="facil" checked>
                    <label>Facil</label>
                </div>

                <div>
                    <input type="radio" name="dificultad" value="medio">
                    <label>Medio</label>

                </div>

                <div>
                    <input type="radio" name="dificultad" value="dificil">
                    <label>Dificil</label>

                </div>

                <input type="hidden" name="arraySerializado" value=<?php echo $arraySerializado ?>>
                <input class="button" type="submit" name="enviar">

            </div>

        </form>

    </div>

</body>

</html>