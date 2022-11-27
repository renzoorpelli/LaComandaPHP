<?php

class FileManager
{
    private $path;


    public function __construct($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 077, true);
        }
        $this->path = $path;
    }

    //getter
    public function getPath()
    {
        return $this->path;
    }

    //setter
    public function setPath($nuevoPath)
    {
        $this->path = $nuevoPath;
    }

    public function combinePath($nombreArchivo)
    {
        return $this->getPath() . $nombreArchivo;
    }
    public function MoverArchivoCSV($nombreArchivo)
    {
        $retorno = "error al guardar el archivo";
        if (isset($_FILES['csv'])) {

            try {
                move_uploaded_file($_FILES['csv']['tmp_name'], $this->combinePath($nombreArchivo));
                $retorno = $this->combinePath($nombreArchivo);
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }

        }
        return $retorno;

    }

    public static function LeerUsuariosCSV($rutaArchivo)
    {
        $retorno = "Los usuarios ya existen";
        if (file_exists($rutaArchivo)) {
            $file = fopen($rutaArchivo, "r");
            $flag = 0;
            //obtengo cada linea del archivo csv, mientras fget no sea false, significa que no estoy al final del archivo
            while (($lineaArchivo = fgetcsv($file, 1000, ",")) !== false) {

                $usuario = new Usuario();
                $usuario->id = $lineaArchivo[0];
                $usuario->nombre = $lineaArchivo[1];
                $usuario->clave = $lineaArchivo[2];
                $usuario->id_rol = $lineaArchivo[3];
                if(Usuario::verificarUsuario($usuario->id)){
                    $flag = 1;
                }else{
                    $flag = 0;
                    $usuario->crearUsuario();
                }
            }

            if($flag == 0){
                $retorno = "Los usuarios no existentes han sido creados";
            }
        }

        return $retorno;
    }

}
?>