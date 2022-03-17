<?php

/* Recepción de datos del formulario */

$ID=(isset($_POST['ID']))?$_POST['ID']:"";
$Nombres=(isset($_POST['Nombres']))?$_POST['Nombres']:"";
$Apellidos=(isset($_POST['Apellidos']))?$_POST['Apellidos']:"";
$Correo=(isset($_POST['Correo']))?$_POST['Correo']:"";
$Telefono=(isset($_POST['Telefono']))?$_POST['Telefono']:"";
$Foto=(isset($_FILES['Foto']["name"]))?$_FILES['Foto']["name"]:NULL;

$Accion=(isset($_POST['Accion']))?$_POST['Accion']:"";

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

    break;

    case "Regresar":
        echo"Presionaste Regresar";
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
                unlink("../img/".$empleado["Foto"]);
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

        if(isset($empleado["Foto"])){
            if(file_exists("../img/".$empleado["Foto"])){
                unlink("../img/".$empleado["Foto"]);
            }
        }
        
        $sentencia= $pdo -> prepare("DELETE FROM empleados WHERE ID = :ID");

        $sentencia -> bindParam('ID', $ID);
        $sentencia -> execute();
    
        header('Location: index.php');
    
        break;
    }



/* Mostrar la infomración de la base de datos */

$sentencia = $pdo -> prepare("SELECT * FROM `empleados` where 1") ;
$sentencia -> execute();
$listaEmpleados = $sentencia -> fetchALL(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Agregar - CRUD con PHP</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        </head>
        <body>
        <div class="col-12 col-md-12"> 
            <form method="POST" id="crudform" name="crudform" data-toggle="validator" class="popup-form" enctype="multipart/form-data">
				<div class="row">
					<div id="msgContactSubmit" class="hidden"></div>	

                    <div class="form-group col-sm-6">
						<div class="help-block with-errors"></div>
                            <label for="">Id:</label>
                            <input type="text" name="ID" value="<?php echo $ID;?>" placeholder="" id="ID" class="form-control" readonly></input>
							<div class="input-group-icon"><i class="fa fa-user"></i></div>
					</div>
                    
                    <div class="form-group col-sm-6">
						<div class="help-block with-errors"></div>
                            <label for="">Nombres:</label>
                            <input type="text" name="Nombres" value="<?php echo $Nombres;?>" placeholder="Ingresa tus nombres" id="Nombres" class="form-control" require="Por favor ingresa tus nombres" required></input>
							<div class="input-group-icon"><i class="fa fa-user"></i></div>
					</div>

                    <div class="form-group col-sm-6">
						<div class="help-block with-errors"></div>
                            <label for="">Apellidos:</label>
                            <input type="text" name="Apellidos" value="<?php echo $Apellidos;?>" placeholder="Ingresa tus apellidos" id="Apellidos" class="form-control" require="Por favor ingresa tus apellidos" required></input>
							<div class="input-group-icon"><i class="fa fa-user"></i></div>
					</div>
                    
                    <div class="form-group col-sm-6">
						<div class="help-block with-errors"></div>
                            <label for="">Correo:</label>
                            <input type="email" name="Correo" value="<?php echo $Correo;?>" placeholder="Ingresa tu correo" id="Correo" class="form-control" require="Por favor ingresa tu correo" required></input>
							<div class="input-group-icon"><i class="fa fa-user"></i></div>
					</div>

                    <div class="form-group col-sm-6">
						<div class="help-block with-errors"></div>
                            <label for="">Telefono:</label>
                            <input type="number" name="Telefono" value="<?php echo $Telefono;?>" placeholder="Ingresa tu teléfono" id="Telefono" class="form-control" require="Por favor ingresa tu teledono" required></input>
							<div class="input-group-icon"><i class="fa fa-user"></i></div>
					</div>

                    <div class="form-group col-sm-6">
						<div class="help-block with-errors"></div>
                            <label for="">Foto:</label><br>
                            <input type="file" accept="image/*" name="Foto" value="<?php echo $Foto ;?>" placeholder="Tu foto" id="Foto"  requiere="" required></input>
                            <div class="input-group-icon"><i class="fa fa-user"></i></div>
					</div>          

                    <div class="form-group col-sm-6">
                            <div class="help-block with-errors"></div>
                            <br>
                            <button value="Agregar" type="sumit" name="Accion">Agregar</button>
                            <button value="Modificar" type="sumit" name="Accion">Modificar</button>
                            <button value="Cancelar" type="sumit" name="Accion">Cancelar</button>
                            <br>
                        </div>

                </div>
            </form>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-12 col-md-12"> 
                    <table class="table table-bordered table-striped">
                    <thead>
                            <tr>
                                <th width="20%">Foto</th>
                                <th width="20%">Nombre Completo</th>
                                <th width="20%">Correo</th>
                                <th width="20%">Telefono</th>
                                <th width="20%">Acciones</th>
                            </tr>
                        </thead>
                        <?php foreach($listaEmpleados as $empleado){  ?>
                        <tr>
                            <td><img class="rounded mx-auto d-block" width="100px" src="../img/<?php echo $empleado['Foto'];?>"/></td>
                            <td><?php echo $empleado['Nombres'];?> <?php echo $empleado['Apellidos']; ?></td>
                            <td><?php echo $empleado['Correo'];?></td>
                            <td><?php echo $empleado['Telefono'];?></td>
                            <td>

                            <form action="" method="post">

                                <input type="hidden" name="ID" value="<?php echo $empleado['ID'] ?>" placeholder="" id="ID" require="">
                                <input type="hidden" name="Nombres" value="<?php echo $empleado['Nombres'];?>">
                                <input type="hidden" name="Apellidos" value="<?php echo $empleado['Apellidos'];?>">
                                <input type="hidden" name="Correo" value="<?php echo $empleado['Correo'];?>">
                                <input type="hidden" name="Telefono" value="<?php echo $empleado['Telefono'];?>">
                                <input type="hidden" name="Foto" value="<?php echo $empleado['Foto'];?>">

                                <input type="submit" value="Selecionar" name="Accion">
                                <button value="Eliminar" type="sumit" name="Accion">Eliminar</button>
                            </form>

                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div> 
        </body>
</html>