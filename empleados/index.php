<?php
require 'empelados.php';
?>

<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Agregar - CRUD con PHP</title>
            <link rel ="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"> </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/poppers.js/1.12.9/udm/popper.min.js" > </script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"> </script>
        </head>
        <body>
        <div class="col-12 col-md-12"> 
            <form method="POST" id="crudform" name="crudform" data-toggle="validator" class="popup-form" enctype="multipart/form-data">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" style="margin-top:25px;">
                Agregar registro +
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">

                        <!-- Formulario del CRUD -->

                        <!-- id -->
                        <label for="">Id:</label>
                        <input type="text" name="ID" value="<?php echo $ID;?>" placeholder="" id="ID" class="form-control" readonly></input>

                        <!-- Nombres -->    
                        <label for="">Nombres:</label>
                        <input type="text" name="Nombres" value="<?php echo $Nombres;?>" placeholder="Ingresa tus nombres" id="Nombres" class="form-control" require="Por favor ingresa tus nombres" required></input>

                        <!-- Apellidos -->     
                        <label for="">Apellidos:</label>
                        <input type="text" name="Apellidos" value="<?php echo $Apellidos;?>" placeholder="Ingresa tus apellidos" id="Apellidos" class="form-control" require="Por favor ingresa tus apellidos" required></input>

                        <!-- Correo --> 
                        <label for="">Correo:</label>
                        <input type="email" name="Correo" value="<?php echo $Correo;?>" placeholder="Ingresa tu correo" id="Correo" class="form-control" require="Por favor ingresa tu correo" required></input>

                        <!-- Telefono --> 
                        <label for="">Telefono:</label>
                        <input type="number" name="Telefono" value="<?php echo $Telefono;?>" placeholder="Ingresa tu teléfono" id="Telefono" class="form-control" require="Por favor ingresa tu teledono" required></input>

                        <!-- Foto -->
                        <label for="">Foto:</label><br>
                        <?php if($Foto!=""){?>
                        <img class="img-thumbail rounded mx-auto d-block" width="100px" style="margin:10px;" src="../img/<?php echo $Foto?>" />
                        <?php }?>
                        <input type="file" accept="image/*" name="Foto" value="<?php echo $Foto ;?>" placeholder="Tu foto" id="Foto"  class="form-control" requiere="" ></input>

                </div>
                    </div>
                    <div class="modal-footer">
                                            <button class="btn btn-primary" value="Agregar" type="sumit" name="Accion" <?php echo $AccionAgregar?> >Agregar</button>
                                            <button class="btn btn-primary" value="Modificar" type="sumit" name="Accion" <?php echo $AccionModificar?> >Modificar</button>
                                            <button class="btn btn-danger" value="Eliminar" type="sumit" name="Accion"  <?php echo $AccionElminiar?> >Eliminar</button>
                                            <button class="btn btn-danger" value="Cancelar" type="sumit" name="Accion" <?php echo $AccionCancelar?> >Cancelar</button>
                    </div>
                    </div>
                </div>
                </div>
            </form>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-12 col-md-12"> 
                    <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
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


                                <input  class="btn btn-info" type="submit" value="Seleccionar" name="Accion">
                                <button class="btn btn-danger" value="Eliminar" type="sumit" name="Accion">Eliminar</button>
                            </form>

                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php if($mostrarModal){ ?>
                <script>
                    $('#exampleModal').modal('show')
                </script>
            <?php } ?>
        </div> 
        </body>
</html>