<?php
require_once "./archivos.php";
require_once "./clases.php";
date_default_timezone_set('America/Argentina/Buenos_Aires');

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];

$path = (explode('/',$path));

switch($path[1])
{
    case 'registro':
        pathRegistro($method);
    break;
    case 'login':
        pathLogin($method);
    break;
    case 'vehiculo':
        pathVehiculo($method);
    break;
    case 'patente':
        pathPatente($method, $path[2]);
    break;
    case 'servicio':
        pathServicio($method);
    break;
    case 'turno':
        pathTurno($method);
    break;
    case 'stats':
        pathStats($method);
    break;
    default:
    echo "Path erroneofsdfdsfsd";
    break;
}
die();

function pathRegistro($method){
    if($method == 'POST'){
        echo "Entro por POST.";
        $email = $_POST['email']??"Falta email";
        $tipo = $_POST['tipo']??"Falta tipo";
        $clave = $_POST['password']??"Falta password";
        $foto = $_FILES['imagen']['name']??"";
        $ruta =  "registro.txt";
        
        $flag = false;
        
        if($tipo != "admin" && $tipo != "user"){
            $tipo = "Invalido";
        }
        
        //verifico con el mail que no haya otro igual, sino no carga
        $list = Archivos::obtenerJson($ruta);
        if(isset($list)>0){
            foreach($list as $a){
                if($a->_email == $email){
                    echo "Email cargado previamente.";
                    $flag = true;
                }
            }
        }
        if($flag == false){
            //Verifico que en la foto haya algo porque sino falla
            if(!empty($foto)){
                $fotoNew = Archivos::saveFile($_FILES['imagen'],"", "jpg")[1];
            }else{
                $fotoNew = "Falta foto.";
            }
            $usuario = new Usuario($email, $tipo ,$clave, $fotoNew);
            echo $usuario;
            Archivos::guardarJson($ruta, $usuario);
        }
        
    }else if($method == 'GET'){
        
        echo "Entro por GET.";
    }else{
        echo "Metodo no permitido";
    }
}
function pathLogin($method){
    if($method == 'POST'){
        $email = $_POST['email']??"Error";
        $clave = $_POST['password']??"Error";
        
        $key = "primerparcial";
        $ruta =  "registro.txt";        
        $flag = false;
        
        $list = Archivos::obtenerJson($ruta);
        
        if(isset($list)>0){
            foreach($list as $a){
                if($a->_email == $email && $a->_clave == $clave){
                    echo "Login de ".$a->_email . " correcto.";
                    jump();

                    $auxArray = array();
                    array_push($auxArray, $a->_email);
                    array_push($auxArray, $a->_tipo);

                    echo "Clave:<br>".Token::crearToken($auxArray, $key);
                    $flag = true;
                }
            }
            if(!$flag){
                echo "No se encontro a nadie con el usuario y clave indicada.";
            }
        }else{
            echo "No se cargo nada.";
        }

    }else if($method == 'GET'){

        echo "Entro por GET.";
    }else{
        echo "Metodo no permitido";
    }
}
function pathVehiculo($method){
    if($method == 'POST'){
        $ruta =  "vehiculos.txt";
        $key = "primerparcial";


        if (Token::autenticarToken($key, "", "Token incorrecto")==false){
            return;
        }

        $marca = $_POST['marca']??"Falta marca";
        $modelo = $_POST['modelo']??"Falta modelo";
        $patente = $_POST['patente']??"Falta patente";
        $precio = $_POST['precio']??"Falta precio";

        $flag = false;
        
        //verifico con la patente que no haya otra igual, sino no carga
        $list = Archivos::obtenerJson($ruta);
        if(isset($list)>0){
            foreach($list as $a){
                if($a->_patente == $patente){
                    echo "Patente cargado previamente.";
                    $flag = true;
                }
            }
        }
        if($flag == false){
            $usuario = new Vehiculo($marca, $modelo ,$patente, $precio);
            echo $usuario;
            Archivos::guardarJson($ruta, $usuario);
        }


    }else if($method == 'GET'){

        echo "Entro por GET.";
    }else{
        echo "Metodo no permitido";
    }
}

function pathPatente($method, $dato){
    $key = "primerparcial";

    $dato = strtolower($dato);

    if($method == 'POST'){
        echo "Entro por POST.";
    }else if($method == 'GET'){
        $ruta =  "vehiculos.txt";
        $flag = false;

        if (Token::autenticarToken($key, "", "Token incorrecto")==false){
            return;
        }
        $list = Archivos::obtenerJson($ruta);

        foreach($list as $a){
            if($a->_marca == $dato || $a->_modelo == $dato || $a->_patente == $dato){
                echo "Marca: " . $a->_marca ."<br>Modelo: ". $a->_modelo . "<br>Patente: ".$a->_patente . "<br>Precio: ". $a->_precio;
                jump();
                $flag = true;
            }
        }  

        if($flag == false){
            echo "No existe " . $dato;
        }

    }else{
        echo "Metodo no permitido";
    }
}
function pathServicio($method){
    if($method == 'POST'){
        if($method == 'POST'){
            $ruta =  "tiposServicio.txt";
            $key = "primerparcial";    
    
            if (Token::autenticarToken($key, "", "Token incorrecto")==false){
                return;
            }
            
            $id = $_POST['id']??"Falta id";
            $tipo = $_POST['tipo']??"Falta tipo";
            $precio = $_POST['precio']??"Falta precio";
            $demora = $_POST['demora']??"Falta demora";
            $list = Archivos::obtenerJson($ruta);
            if(isset($list)>0){
                foreach($list as $a){
                    if($a->_id == $id){
                        echo "Id cargado previamente.";
                        return;
                    }
                }
            }
    
            if($tipo != 10000 && $tipo != 20000 && $tipo != 50000 && 
            $tipo != "10000km" && $tipo != "20000km" && $tipo != "50000km"){
                $tipo = 0;
                echo "Dato del tipo invalido";
            }

            $servicio = new Servicio($id, $tipo ,$precio, $demora);
            echo $servicio;
            Archivos::guardarJson($ruta, $servicio);
        }
    }else if($method == 'GET'){
        echo "Entro por GET.";
    }else{
        echo "Metodo no permitido";
    }
}
function pathTurno($method){
    if($method == 'POST'){
        echo "Entro por POST.";

    }else if($method == 'GET'){
        $key = "primerparcial";
        $ruta = "turno.txt";
        $rutaSer = "tiposServicio.txt";
        $rutaVeh = "vehiculos.txt";
        $flagV = false;
        $flagS = false;

        if (Token::autenticarToken($key, "", "Token incorrecto")==false){
            return;
        }

        $patente = $_GET['patente']??'';
        $dia = $_GET['fecha']??'';
        $id = $_GET['id']??'';
        $marca = '';
        $modelo = '';
        $precio = '';
        $tipo = '';
        
        $listVeh = Archivos::obtenerJson($rutaVeh);
        if(isset($listVeh)>0){
            foreach($listVeh as $a){
                if($a->_patente == $patente){
                    echo $a->_patente;
                    jump();
                    $marca = $a->_marca;
                    $modelo = $a->_modelo;
                    $flagV = true;
                }
            }
        }else{
            echo "Lista de vehiculos vacia.";
            return;
        }
        
        $listSer = Archivos::obtenerJson($rutaSer);
        if(isset($listSer)>0){
            foreach($listSer as $a){
                if($a->_id == $id){
                    $precio = $a->_precio;
                    $tipo = $a->_tipo;
                    $flagS = true;                    
                }
            }
        }else{
            echo "Lista de servicios vacia.";
            return;
        }

        if($flagV == false){
            echo "No se encontro la patente.<br>";
        }
        if($flagS == false){
            echo "No se encontro el id del servicio.<br>";
        }

        if($flagV == true && $flagS == true){
            $turno = new Turno($dia, $patente, $marca, $modelo, $precio, $tipo);
            echo $turno;
            Archivos::guardarJson($ruta, $turno);
        }else{
            return;
        }

        
    }else{
        echo "Metodo no permitido";
    }
}
function pathStats($method){
    if($method == 'POST'){
        echo "Entro por POST.";

    }else if($method == 'GET'){
        $key = "primerparcial";
        $ruta = "turno.txt";

        if (Token::autenticarToken($key, "", "Token incorrecto")==false){
            return;
        }

        $array = Token::obtenerToken($key);
        
        if($array[1] != "admin"){
            echo "Tipo de usuario invalido.";
            return;
        }
        $tipo = $_GET['tipo']??'';
        if($tipo == "50000km"){
            $tipo = 50000;
        }
        if($tipo == "10000km"){
            $tipo = 10000;
        }
        if($tipo == "20000km"){
            $tipo = 50000;
        }
        $list = Archivos::obtenerJson($ruta);

        if($tipo != 10000 && $tipo != 20000 && $tipo != 50000){
            foreach($list as $a){
                echo "El dia " . $a->_fecha . " tiene turno el auto " . $a->_patente . "para el servicio de " . $a->_tipo;
                jump();
            }
        }else{
            foreach($list as $a){
                
                if($a->_tipo == $tipo){
                    echo "El dia " . $a->_fecha . " tiene turno el auto " . $a->_patente;
                }
            }
        }

    }else{
        echo "Metodo no permitido";
    }
}