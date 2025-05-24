<?php
require_once "main.php";

/*== Almacenando id ==*/
$product_id=limpiar_cadena($_POST['img_del_id']);

/*== Verificando producto==*/
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id'");

if($check_producto->rowCount()==1){
    $datos=$check_producto->fetch();
}else{
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La imagen del producto no existe en el sistema
    </div>
';
exit();
}
$check_producto=null;

/* Directorios de imagenes */
$img_dir='../img/producto/';

/* Cambiando permisos al directorio */
chmod($img_dir, 0777);

if(is_file($img_dir.$datos['producto_foto'])){
    chmod($img_dir.$datos['producto_foto'], 0777);

    if(!unlink($img_dir.$datos['producto_foto'])){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Error al intentar eliminar la imagen del producto, por favor intente nuevamente
        </div>
    ';
    exit();
    }
}
  /*== Actualizar datos ==*/
  $actualizar_producto=conexion();
  $actualizar_producto=$actualizar_producto->prepare("UPDATE producto SET 
  producto_foto=:foto  WHERE producto_id=:id");

 $marcadores=[
    ":foto"=>"",
    ":id"=>$product_id
];
if($actualizar_producto->execute($marcadores)){
    echo '
        <div class="notification is-info is-light">
            <strong>¡IMAGEN ELIMINADA!</strong><br>
            La imagen del producto ha sido eliminada con exito, pulse aceptar para recargar los cambios

            <p class="has-text-centered pt-5 pb-5">
            <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">
            Aceptar</a>
            <p/>
        </div>
    ';
}else{
    echo '
        <div class="notification is-warning is-light">
            <strong>¡IMAGEN ELIMINADA!</strong><br>
            Ocurrieron unos incovenientes, sin embargo la imagen del producto a sido 
            eliminado, pulse aceptar para recargar los cambios

            <p class="has-text-centered pt-5 pb-5">
            <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">
            Aceptar</a>
            <p/>
        </div>
    ';
}
$actualizar_producto=null;
?>