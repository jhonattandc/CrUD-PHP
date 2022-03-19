<?php

/* Recepción de datos del formulario */

$ID=(isset($_POST['ID']))?$_POST['ID']:"";
$Nombres=(isset($_POST['Nombres']))?$_POST['Nombres']:"";
$Apellidos=(isset($_POST['Apellidos']))?$_POST['Apellidos']:"";
$Correo=(isset($_POST['Correo']))?$_POST['Correo']:"";
$Telefono=(isset($_POST['Telefono']))?$_POST['Telefono']:"";
$Foto=(isset($_FILES['Foto']["name"]))?$_FILES['Foto']["name"]:NULL;

$Accion=(isset($_POST['Accion']))?$_POST['Accion']:"";



//Variables para habilitar los botones del modal

$AccionAgregar="";
$AccionModificar=$AccionElminiar=$AccionCancelar = "disabled";
$mostrarModal= false;

/* Llamado del archivo de conexxión */

include ("../conexion/conexion.php");

switch ($Accion) {
    
    /* Sentencia para guardar la información en la base de datos */

    case "Agregar":

        $sentencia= $pdo -> prepare("INSERT INTO empleados( Nombres, Apellidos, Correo, Telefono, Foto )
        VALUES( :Nombres, :Apellidos, :Correo, :Telefono, :Foto ) ");

        $sentencia -> bindParam('Nombres', $Nombres);
        $sentencia -> bindParam('Apellidos', $Apellidos);
        $sentencia -> bindParam('Correo', $Correo);
        $sentencia -> bindParam('Telefono', $Telefono);

        $Fecha = new DateTime(); //Se recoge la fecha
        $NombreArchivo = ($Foto!= NULL)?$Fecha->getTimestamp()."_".$_FILES["Foto"]["name"]:"user.png"; //Si la variable foto es diferente que "NULL", usando la varible fecha usamos la funcion getmistamp para concatenar la fecha con el nombre del documento, en caso que sea NULL usaremos la imgane por defecto "user.png"

        $tmpFoto = $_FILES["Foto"]["tmp_name"]; //Se guarda en la variable el nombre temporal de la foto

        if ($tmpFoto != NULL ){ //Si condicional que se aplica solo si la variable es diferente que NULL
            move_uploaded_file($tmpFoto,"../img/".$NombreArchivo); //Movemos ls foto selecionada a la carpeta correspondiente
        }

        $sentencia -> bindParam('Foto', $NombreArchivo); 
        $sentencia -> execute();
        header('Location: index.php');

    break;

/* Sentencia para modificar la información en la base de datos */

    case "Modificar":

        $sentencia= $pdo -> prepare("UPDATE empleados SET
        Nombres = :Nombres,
        Apellidos = :Apellidos,
        Correo = :Correo,
        Telefono = :Telefono
        WHERE
        ID = :ID");

        $sentencia -> bindParam('Nombres', $Nombres);
        $sentencia -> bindParam('Apellidos', $Apellidos);
        $sentencia -> bindParam('Correo', $Correo);
        $sentencia -> bindParam('Telefono', $Telefono);
        $sentencia -> bindParam('ID', $ID);
        $sentencia -> execute();

    //Validación para saber si el usuario esta seleccionando una foto para actuaizar

        $Fecha = new DateTime(); //Se recoge la fecha
        $NombreArchivo = ($Foto!= NULL)?$Fecha->getTimestamp()."_".$_FILES["Foto"]["name"]:"user.png"; //Si la variable foto es diferente que "NULL", usando la varible fecha usamos la funcion getmistamp para concatenar la fecha con el nombre del documento, en caso que sea NULL usaremos la imgane por defecto "user.png"

        $tmpFoto = $_FILES["Foto"]["tmp_name"]; //Se guarda en la variable el nombre temporal de la foto

        if ($tmpFoto != NULL ){ //Si condicional que se aplica solo si la variable es diferente que NULL
            move_uploaded_file($tmpFoto,"../img/".$NombreArchivo); //Movemos ls foto selecionada a la carpeta correspondiente

            $sentencia= $pdo -> prepare("SELECT Foto FROM empleados WHERE ID = :ID"); //Sentencia de busque en la base datos para saber el nombre de la foto antigua
            $sentencia -> bindParam('ID', $ID);
            $sentencia -> execute();
            $empleado = $sentencia -> fetch(PDO::FETCH_LAZY);

            if(isset($empleado["Foto"])){ //validamos si existe una foto con ese nombre, si existe la borramos
                if(file_exists("../img/".$empleado["Foto"])){
                    if($empleado['Foto']!="user.png"){
                    unlink("../img/".$empleado["Foto"]);
                    }
                }
            }

                /*Sentencia para actualizar la foto*/

            $sentencia= $pdo -> prepare("UPDATE empleados SET
            Foto = :Foto
            WHERE
            ID = :ID");
            $sentencia -> bindParam('Foto', $NombreArchivo);
            $sentencia -> bindParam('ID', $ID);
            $sentencia -> execute();
        }



        header('Location: index.php');

    break;

    /* Sentencia para eliminar la información en la base de datos */

    case "Eliminar":

// En el siguiente bloque de codigo hacemos busqueda en la base de datos para averiguar el nombre 
// de la imagen y revisar si existe esa imagen en la dirrecion para poder borrar la foto antigua
// al momento de eliminar el usuario

        $sentencia= $pdo -> prepare("SELECT Foto FROM empleados WHERE ID = :ID");
        $sentencia -> bindParam('ID', $ID);
        $sentencia -> execute();
        $empleado = $sentencia -> fetch(PDO::FETCH_LAZY);

        if(isset($empleado["Foto"])&&($empleado['Foto']!="user.png")){
            if(file_exists("../img/".$empleado["Foto"])){
                unlink("../img/".$empleado["Foto"]);
            }
        }
        
        $sentencia= $pdo -> prepare("DELETE FROM empleados WHERE ID = :ID");

        $sentencia -> bindParam('ID', $ID);
        $sentencia -> execute();
    
        header('Location: index.php');
    
        break;

        case "Seleccionar":
            $AccionAgregar="disabled";
            $AccionModificar = $AccionElminiar = $AccionCancelar = "";
            $mostrarModal=true;

            $sentencia= $pdo -> prepare("SELECT * FROM empleados WHERE ID = :ID");
            $sentencia -> bindParam('ID', $ID);
            $sentencia -> execute();
            $empleado = $sentencia -> fetch(PDO::FETCH_LAZY);

            $Nombres = $empleado['Nombres'];
            $Apellidos = $empleado['Apellidos'];
            $Correo = $empleado['Correo'];
            $Telefono = $empleado['Telefono'];
            $Foto = $empleado['Foto'];

        break;

        case "Cancelar":
            header('Location: index.php');
        break;
    }



/* Mostrar la infomración de la base de datos */

$sentencia = $pdo -> prepare("SELECT * FROM `empleados` where 1") ;
$sentencia -> execute();
$listaEmpleados = $sentencia -> fetchALL(PDO::FETCH_ASSOC);

?>