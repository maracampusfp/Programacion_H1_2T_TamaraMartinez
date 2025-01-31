<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
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

    // Se incluye cabecera.php y conexion.php
    include_once 'cabecera.php';
    include_once 'conexion.php';

    // Se comprueba la conexión a la base de datos
    if ($conexion->connect_error) {
        die('Error de Conexión (' . $conexion->connect_errno . ') ' . $conexion->connect_error);
        $resultadoPack->close();
    }

    // Se comprueba si se ha enviado el formulario con el método GET y si se han recibido los datos necesarios
    if ($_SERVER["REQUEST_METHOD"] == "GET" &&
    isset($_GET['nombre'], $_GET['apellido'], $_GET['correo'], $_GET['edad'], $_GET['plan'], $_GET['suscripcion'])) {
        
        // Se guardan en variables los datos del formulario
        $nombre = $_GET['nombre'];
        $apellido = $_GET['apellido'];
        $correo = $_GET['correo'];
        $edad = $_GET['edad'];
        $plan = $_GET['plan'];
        $suscripcion = $_GET['suscripcion'];
        
        // RECUPERAR PRECIO PLAN BASE MENSUAL DE LA BASE DE DATOS
        $sqlPlan = "SELECT precioPlanBase FROM planesBase WHERE nombrePlanBase = '$plan'";
        $resultadoPlan = $conexion->query($sqlPlan);

        if ($resultadoPlan->num_rows > 0) {
            $filaPlan = $resultadoPlan->fetch_assoc();
            $precioTotal = $filaPlan['precioPlanBase'];
        } else {
            die("Error: No se encontró el plan base en la base de datos.");
        }
                
        // RECUPERAR PRECIO PACKS ADICIONALES DE LA BASE DE DATOS
        $precioPacks = 0; // Para acumular los precios de los packs adicionales
        $contadorPacks = 0; // Para controlar que solo se contrate un pack adicional con el plan básico

        // PRECIO PACK DEPORTE
        if (isset($_GET['packDeporte'])) {
            $sqlPack = "SELECT precioPackAdicional FROM packsAdicionales WHERE nombrePackAdicional = 'Deporte'";
            $resultadoPack = $conexion->query($sqlPack);
            if ($resultadoPack->num_rows > 0) {
                $filaPack = $resultadoPack->fetch_assoc();
                $precioPacks += $filaPack['precioPackAdicional'];
            }
            $packDeporte = 1;
            $contadorPacks++;
        } else {
            $packDeporte = 0;
        }

        // PRECIO PACK CINE
        if (isset($_GET['packCine'])) {
            $slqPack = "SELECT precioPackAdicional FROM packsAdicionales WHERE nombrePackAdicional = 'Cine'";
            $resultadoPack = $conexion->query($slqPack);
            if ($resultadoPack->num_rows > 0) {
                $filaPack = $resultadoPack->fetch_assoc();
                $precioPacks += $filaPack['precioPackAdicional'];
            }
            $packCine = 1;
            $contadorPacks++;
        } else {
            $packCine = 0;
        }

        // PRECIO PACK INFANTIL
        if (isset($_GET['packInfantil'])) {
            $sqlPack = "SELECT precioPackAdicional FROM packsAdicionales WHERE nombrePackAdicional = 'Infantil'";
            $resultadoPack = $conexion->query($sqlPack);
            if ($resultadoPack->num_rows > 0) {
                $filaPack = $resultadoPack->fetch_assoc();
                $precioPacks += $filaPack['precioPackAdicional'];
            }
            $packInfantil = 1;
            $contadorPacks++;
        } else {
            $packInfantil = 0;
        }

        // SUMA PRECIO PLAN BASE + PRECIOS ACUMULADS DE LOS PACKS
        $precioTotal += $precioPacks;

        // SI LA SUSCRIPCIÓN ES ANUAL, MULTIPLICAR POR 12 MESES
        if ($suscripcion == "Anual") {
            $precioTotal = $precioTotal * 12;
        }   

        // VALIDACIONES (MAYOR DE EDAD O SI CONTRATA PLAN BASE)
        $error=FALSE;
        $mensajeEdad="";
        if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['plan'] == "Básico" && $contadorPacks>1) {
            $mensajeBasico = "<div class='alert alert-danger mt-3'>Con Plan Básico puede contratarse un solo Pack Adiccional.</div>";
            $error=TRUE;
        }

        // VALIDACIÓN DE EDAD PARA PACKS DE DEPORTE Y CINE
        if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['edad'] < 18 && (isset($_GET['packDeporte']) || isset($_GET['packCine']))) {
            $mensajeEdad = "<div class='alert alert-danger mt-3'>No se pueden suscribir menores de 18 años a Deportes o Cine.</div>";
            $error=TRUE;
        }

        // VALIDACIÓN DE PACK DE DEPORTE SEGÚN SUSCRIPCIÓN
        if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['suscripcion'] == "Mensual" && isset($_GET['packDeporte'])) {
            $mensajeDeporte = "<div class='alert alert-danger mt-3'>No se puede contratar el Pack Deporte con suscripción mensual.</div>";
            $error=TRUE;
        }
        
        // SI NO HAY NINGÚN INTENTO DE ALTA DE USUARIO ERRÓNEO, SE INSERTA EL USUARIO EN LA BASE DE DATOS 
        if (!$error) { 

                $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', edad=$edad, tipoPlanBase='$plan',
                suscripcion='$suscripcion', packDeporte=$packDeporte, packCine=$packCine, packInfantil=$packInfantil, 
                precioTotal=$precioTotal WHERE correo='$correo';";
                $resultado = $conexion->query($sql);
            
                if ($resultado) {
                    $mensaje = "<div class='alert alert-success mt-3'>Se ha modificado el usuario con correo <strong>$correo</strong></div>";
                }
        }//end if !error
    }//end if request method GET y isset datos formulario 

    // Se comprueba si se ha recibido el correo del usuario a modificar correctamente
    if (!empty($_GET['correo'])) {  
        $correo = $_GET['correo'];
        $sql = "SELECT * FROM clientes WHERE correo='$correo';";
        $resultado = $conexion->query($sql);

        // Se comprueba si se ha encontrado el usuario en la base de datos
        if ($resultado) {
        // Se recoge el registro del usuario a modificar    
        $row = $resultado->fetch_assoc();
        ?>
        <!--Se muestra el formulario con los datos del usuario a modificar-->
        <div class="container mt-4 custom-form d-grip">
            <h1>Modificar datos del usuario</h1>
            <h3><?php echo $row['correo'];?></h3><br><br>
            <form action='modificar.php' method='GET'>
                <div class='mb-3'>
                    <label for='nombre' class='form-label'>Nombre</label>
                    <input type='text' class='form-control' id='nombre' name='nombre' value="<?php echo $row['nombre']; ?>">
                </div>
                <div class='mb-3'>
                    <label for='apellido' class='form-label'>Apellido</label>
                    <input type='text' class='form-control' id='apellido' name='apellido' value="<?php echo $row['apellido']; ?>">
                </div>
                <div class='mb-3'>
                    <label for='edad' class='form-label'>Edad</label>
                    <input type='number' class='form-control' id='edad' name='edad' value="<?php echo $row['edad']; ?>">
                </div>
                <div class='mb-3'>
                    <label for='plan' class='form-label'>Plan</label>
                    <select class='form-select' id='plan' name='plan'>
                        <option value='Básico' <?php if ($row['tipoPlanBase'] == 'Básico') echo 'selected'; ?>>Básico</option>
                        <option value='Estándar' <?php if ($row['tipoPlanBase'] == 'Estándar') echo 'selected'; ?>>Estándar</option>
                        <option value='Premium' <?php if ($row['tipoPlanBase'] == 'Premium') echo 'selected'; ?>>Premium</option>
                    </select>
                </div>
                <div class='mb-3'>
                    <label for='suscripcion' class='form-label'>Suscripción</label>
                    <select class='form-select' id='suscripcion' name='suscripcion'>
                        <option value='Mensual' <?php if ($row['suscripcion'] == 'Mensual') echo 'selected'; ?>>Mensual</option>
                        <option value='Anual' <?php if ($row['suscripcion'] == 'Anual') echo 'selected'; ?>>Anual</option>
                </div>
                <div class="mb-3">
                    <label class='form-label'>Packs Adicionales</label>
                    <div class="form-check mb-2">
                        <input type='checkbox' class='form-check-input' id='packDeporte' name='packDeporte' 
                        <?php if ($row['packDeporte']) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='packDeporte'>Pack Deporte</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type='checkbox' class='form-check-input' id='packCine' name='packCine' 
                        <?php if ($row['packCine']) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='packCine'>Pack Cine</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type='checkbox' class='form-check-input' id='packInfantil' name='packInfantil' 
                        <?php if ($row['packInfantil']) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='packInfantil'>Pack Infantil</label>
                    </div>

                    <!-- Se añade un campo oculto con el correo del usuario a modificar para poder enviarlo de vuelta-->
                    <input type='hidden' name='correo' value='<?php echo $correo; ?>'>
                    
                <button type='submit' class='btn btn-primary custom-delete'>Modificar</button>
            </form>
            <?php

            // Se muestran al usuario los mensajes de error o confirmación tras el envío del formulario
            if (isset($mensaje)){
                echo $mensaje;
            }

            if (isset($mensajeEdad)){
                echo $mensajeEdad;  
            }

            if(isset($mensajeBasico)){
                echo $mensajeBasico;
            }

            if(isset($mensajeDeporte)){
                echo $mensajeDeporte;
            }

            ?>
                </div>      
            </form>
        </div>
    <?php     
        } else {
            echo "<div class='alert alert-danger mt-3'>ERROR: No se ha podido cargar el usuario</div>";
        }  
    }else{
        echo "<div class='alert alert-danger mt-3'>ERROR: No se ha podido cargar el usuario</div>";
    }

    $conexion->close();   
    ?>
</body>
<?php include_once 'pie.php'; ?>
</html>
