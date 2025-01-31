<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>StreamWeb</title>
        <!-- Enlace a Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Enlace a Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Enlace a fuente Bebas Neue, de Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <!-- Enlace a mi hoja de estilos CSS personalizados -->
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
    </head>
    <body>
    <?php 
    
    // Incluir cabecera y conexión a la base de datos
    include_once 'cabecera.php';
    include_once 'conexion.php';


    // Si se ha pulsado el botón de eliminar usuario se ejecuta el siguiente código
    if(!empty($_GET['eliminar'])){
        $correo=$_GET['eliminar'];

        // Se elimina el usuario con el correo indicado
        $sql = "DELETE FROM clientes WHERE correo=\"$correo\";";
        $resultado = $conexion->query($sql);
        
        // Se guarda un mensaje de confirmación o error para mostrarlo después de la tabla de usuarios
        if ($resultado) {
            $mensaje = "<div class='alert alert-danger mt-3'>Se ha eliminado el usuario con correo <strong>$correo</strong></div>";
        } else {
            $mensaje = "<div class='alert alert-danger mt-3'>No se ha podido eliminar el usuario con correo <strong>$correo</strong></div>";
        }
    }

    // Si la conexión falla se muestra un mensaje de error
    if ($conexion->connect_error) {
        die('Error de Conexión (' . $conexion->connect_errno . ')' . $conexion->connect_error);
    }else{
        // Sino se ejecuta la consulta para obtener los datos de los usuarios
        $sql = "SELECT * FROM clientes;";
        $resultado = $conexion->query($sql);
    }

        
    // Si se han obtenido resultados de la consulta se muestra la tabla de usuarios
    if ($resultado) {
    ?>
    <!-- Contenido de la Tabla de Usuarios -->
    <div class="table-content d-grip">
        <h1>Usuarios suscritos a StreamWeB</h1>
        <div class="custom-container text-white">
            <table class="table table-striped">
                <thead>
                    <tr> 
                        <th>Nombre</th> 
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th class='center'>Edad</th>
                        <th class='center'>Plan Base</th>
                        <th class='center'>Pack Deporte</th>
                        <th class='center'>Pack Cine</th>
                        <th class='center'>Pack Infantil</th>
                        <th class='center'>Suscripción</th>
                        <th class='center'>Cuota Total</th>
                        <th colspan="4"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Se recorren los registros de la tabla clientes guardada en la matriz $resultado
                    $row = $resultado->fetch_assoc();
                    while ($row) {
                    // Determinar si el usuario tiene el pack de deporte
                    if ($row['packDeporte']==1) {
                        $mostrarDeporte="✓";
                    } else {
                        $mostrarDeporte="";
                    }
                    // Determinar si el usuario tiene el pack de cine
                    if ($row['packCine']==1) {
                        $mostrarCine="✓";
                    } else {
                        $mostrarCine="";
                    }
                    // Determinar si el usuario tiene el pack infantil
                    if ($row['packInfantil']==1) {
                        $mostrarInfantil="✓";
                    } else {
                        $mostrarInfantil="";
                    }
                        echo "<tr>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['apellido'] . "</td>";
                        echo "<td>" . $row['correo'] . "</td>";
                        echo "<td class='center'>" . $row['edad'] . "</td>";
                        echo "<td class='center'>" . $row['tipoPlanBase'] . "</td>";
                        echo "<td class='center'>" .$mostrarDeporte."</td>";
                        echo "<td class='center'>" .$mostrarCine."</td>";
                        echo "<td class='center'>" .$mostrarInfantil."</td>";
                        echo "<td class='center'>" . $row['suscripcion'] . "</td>";
                        echo "<td class='center'>" . $row['precioTotal'] . "€ </td>";
                        echo "<td class='center'><a href=\"modificar.php?correo=".$row['correo']."\" class=\"btn btn-secondary btn-sm\">Editar</a></td>";
                        echo "<td class='center'><a href=\"index.php?detallescorreo=".$row['correo']."\" class=\"btn btn-secondary btn-sm\">Ver detalles</a></td>";
                        echo "<td class='center'><a href=\"index.php?eliminar=".$row['correo']."\" class=\"btn btn-secondary btn-sm custom-delete\">Eliminar</a></td>";
                        $row = $resultado->fetch_assoc();
                    }
                    ?>
                </tbody>
            </table>
            <?php
            
            // Si se ha pulsado el botón de ver detalles de un usuario se ejecuta el siguiente código
            if(!empty($_GET['detallescorreo'])){

                $correo=$_GET['detallescorreo'];

                // Se obtienen los datos del usuario y los detalles de su suscripción de la base de datos
                $sql = "SELECT c.nombre, c.apellido, c.edad, c.correo, c.tipoPlanBase, c.packDeporte, c.packCine, 
                        c.packInfantil, c.suscripcion, c.precioTotal, p.precioPLanBase  
                            FROM clientes c	JOIN planesBase p
                                WHERE c.tipoPlanBase = p.nombrePlanBase and c.correo = \"$correo\";";
                $resultado = $conexion->query($sql);
                $row = $resultado->fetch_assoc();               
                ?>
                    <br>
                    <h2>Detalles de la suscripción</h2>
                    <div class="custom-container text-white">
                        <table class="table table-striped">
                            <tr>
                                <th>Cuenta de usuario</th>
                                <th class="center">Plan<?php echo $row['tipoPlanBase']; ?></th>
                                <th class="center">Dispositivos</th>    
                                <th class="center">Pack Deporte</th>
                                <th class="center">Pack Cine</th>
                                <th class="center">Pack Infantil</th>
                                <th class="center">Precio Mensual</th>
                                <th class="center">Suscripción</th>
                                <th class="center">Precio Total</th>
                            </tr>
                            <tr>
                                <td class="name"><?php echo $row['correo']; ?></td>
                                <td class="center"><?php echo $row['precioPLanBase']; ?>€</td>
                                
                                <?php 
                                    // Se obtiene de la base de datos el nº de dispositivos que le corresponden poder usar al usuario, según el Plan Base contratado.
                                    $sqlDispositivos = "SELECT dispositivos FROM planesBase WHERE nombrePlanBase = '".$row['tipoPlanBase']."'";
                                    $resultadoDispositivos = $conexion->query($sqlDispositivos);
                                    $rowDispositivos = $resultadoDispositivos->fetch_assoc();                                 
                                ?>
                                <td class="center"><?php echo $rowDispositivos['dispositivos']; ?></td>
                                <?php 
                                    // Se obtiene de la base de datos el precio de los packs adicionales contratados
                                    if ($row['packDeporte']==1) {
                                        $sqlPack = "SELECT precioPackAdicional FROM packsAdicionales WHERE nombrePackAdicional = 'Deporte'";
                                        $resultadoPack = $conexion->query($sqlPack);
                                        $rowPack = $resultadoPack->fetch_assoc();
                                        $precioDeporte=$rowPack['precioPackAdicional'];
                                        echo "<td class='center'>$precioDeporte €</td>";
                                    }else{
                                        echo "<td class='center'>-</td>";
                                    }
                                    if ($row['packCine']==1) {
                                        $sqlPack = "SELECT precioPackAdicional FROM packsAdicionales WHERE nombrePackAdicional = 'Cine'";
                                        $resultadoPack = $conexion->query($sqlPack);
                                        $rowPack = $resultadoPack->fetch_assoc();
                                        $precioCine=$rowPack['precioPackAdicional'];
                                        echo "<td class='center'>$precioCine €</td>";
                                    }else{
                                        echo "<td class='center'>-</td>";
                                    }
                                    if ($row['packInfantil']==1) {
                                        $sqlPack = "SELECT precioPackAdicional FROM packsAdicionales WHERE nombrePackAdicional = 'Infantil'";
                                        $resultadoPack = $conexion->query($sqlPack);
                                        $rowPack = $resultadoPack->fetch_assoc();
                                        $precioInfantil=$rowPack['precioPackAdicional'];
                                        echo "<td class='center'>$precioInfantil €</td>";
                                    }else{
                                        echo "<td class='center'>-</td>";
                                    }
                                ?>
                                <?php
                                if ($row['suscripcion']=="Anual") {
                                    $precioMensual=round($row['precioTotal']/12, 2);
                                }
                                if ($row['suscripcion']=="Mensual") {
                                    $precioMensual=round($row['precioTotal'], 2);
                                }
                                ?>
                                <td class="center"><?php echo $precioMensual; ?>€</td>
                                <td class="center"><?php echo $row['suscripcion']; ?></td>
                                <td class="center"><?php echo $row['precioTotal']; ?>€</td>
                            </tr>
                        </table>
                <?php
            }
            ?>
        </div>
        <?php
        if(!empty($_GET['eliminar'])){
            echo $mensaje;
        }
        ?>
    <?php
    } else {
        echo "<p>No constan usuarios registrados.</p>";
    }
    // Cerrar la conexión con la base de datos
    $resultado->close();
    $conexion->close();
    ?>
    </div>
    </body>
    <?php 
    // Incluir pie de página
    include_once 'pie.php'; 
    ?>
</html>