<?php
require_once './clases.php';
require __DIR__.'/vendor/autoload.php';
use \Firebase\JWT\JWT;


//JSON
class Archivos{
    //Guardar en JSON
    static function guardarJson($name, $obj)
    {
        $array = Archivos::obtenerJson($name);

        if(isset($array)){
            $file = fopen("./".$name, "w");
            array_push($array, $obj);
    
            fwrite($file, json_encode($array));
            fclose($file);
        }else{
            $array2 = array();
            $file = fopen("./".$name, "w");
            array_push($array2, $obj);
            
            fwrite($file, json_encode($array2));
            fclose($file);
        }
    }
    
    //Traer archivo tipo JSON
    static function obtenerJson($ruta){
        if(file_exists($ruta)){
            $ar = fopen($ruta, "r");
            $lista = json_decode(fgets($ar));
            fclose($ar);
            return $lista;
        }
    }
    
    static function actualizarJson($list, $ruta){
        $retorno = unlink($ruta);

        if($retorno == true){
            foreach($list as $a){
                Archivos::guardarJson($ruta, $a);
            }
        }

    }

    static function guardarSerializado($obj, $name)
    {
        $oldArray = Archivos::obtenerSerializado($name);

        $flag = false;
        foreach($oldArray as $a){
            if($a == $obj){
                $flag = true;
            }
        }
        if($flag == false){
            $file = fopen("./" . $name, "a");
    
            fwrite($file, serialize($obj).PHP_EOL);
    
            fclose($file);
        }else{
            echo "El objeto ya existe.";
            jump();
        }
    }

    static function obtenerSerializado($name)
    {
        $list = array();
        $file = fopen("./".$name, "r");
        
        while(!feof($file)){
            $obj = unserialize(fgets($file));
            
            if($obj != null)
            {
                array_push($list, $obj);
            }
        }
        
        fclose($file);

        return $list;
    }
    
    static function guardar($obj, $ruta)
    {
        $file = fopen("./" . $ruta, "a");
        
        fwrite($file, $obj.PHP_EOL);
        
        fclose($file);
    }

    static function obtener($name)
    {
        $list = array();
        $file = fopen("./".$name, "r");
    
        while(!feof($file)){
            $obj = fgets($file);
    
            if($obj != null)
            {
                array_push($list, $obj);
            }
        }
    
        fclose($file);
    
        return $list;
    }
    /***
     * en saveFile[0] devuelve si funciono.
     * en saveFile[1] guarda el nuevo nombre.
     */
    static function saveFile($file, $ruta, $type){
        $random ="-". rand(1000,9999);
        $random = "";//
        $origin = $file["tmp_name"];
        $destination = (explode('.',$file['name']));
        $ext = array_pop($destination);

        $newName = implode($destination).$random.".".$ext;

        if($ext == $type){
            
            $sePudo = move_uploaded_file($origin, $ruta.$newName);
            if($sePudo == 1){
                return array ($sePudo, $newName);
            }
        }else{
            echo "El tipo de archivo no coincide";
        }
    }

    /**
     * true si pudo,
     * false si no
     */
    static function deleteFile($file, $ruta, $dest){
        $retorno = false;
        $sePudo = copy($file, "./".$dest ."/delete-".$file);
        if($sePudo == true){
            $retorno = unlink($ruta.$file);
        }else{
            echo "no encontro el archivo";
        }
        return $retorno;
    }


    static function waterMark($dest, $mark){
        $marca = imagecreatefromjpeg($mark);
        $im = imagecreatefromjpeg($dest);
        $retorno = imagecopymerge($im, $marca, 0,0, 0,0, imagesx($marca),imagesy($marca), 30);
        
        imagejpeg($im, $dest);
        imagedestroy($im);

        return $retorno;

        }    
}

class Token{
    /**
     * devuelve string con el token.
     */
    static function crearToken($payload, $key){

        $retorno = "";
        if(!empty($key) && !empty($payload)){
            $retorno = JWT::encode($payload, $key);
        }else{
            $retorno = "ERROR!!";
        }

        return $retorno;
    }

    static function obtenerToken($key){
        try{
            $token = $_SERVER['HTTP_TOKEN'];

            $decoded = JWT::decode($token,$key, array('HS256'));
            
            return $decoded;  
       
        }
        catch(\Throwable $th){
            return 'error';
        }
    }

    /***
     * 
     */
    static function autenticarToken($key, $ok, $fail){
        if(Token::obtenerToken($key) == 'error')
        {
            echo $fail;
            return false;
        }else{
            echo $ok;
            return true;
        }
    }
}
function jump($count=1){
        $i = 0;
        while($i <= $count){
            echo "<br>";
            $i ++;
        }   
}

function extDeFile($file){
    $array = (explode('.',$file['name']));
    return array_pop($array);
}

function object_sorter($clave,$orden=null) {
    return function ($a, $b) use ($clave,$orden) {
          $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
          return $result;
    };
}