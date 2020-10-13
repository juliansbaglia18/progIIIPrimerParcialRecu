<?php

class Usuario
{
    public $_email;
    public $_tipo;
    public $_clave;
    public $_foto;

    public function __construct($email, $tipo, $clave, $foto)
    {
        $this->_email = $email;
        $this->_tipo = $tipo;
        $this->_clave = $clave;
        $this->_foto = $foto;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    public function __toString()
    {
        return "Email: " . $this->_email . "<br>Tipo: " . $this->_tipo . "<br>Clave: " . $this->_clave . "<br>Foto: " . $this->_foto . "<br><br>";
    }
}
class Vehiculo
{
    public $_marca;
    public $_modelo;
    public $_patente;
    public $_precio;

    public function __construct($marca, $modelo, $patente, $precio)
    {
        $this->_marca = $marca;
        $this->_modelo = $modelo;
        $this->_patente = $patente;
        $this->_precio = $precio;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    public function __toString()
    {
        return "Marca: " . $this->_marca. "<br>Modelo: " . $this->_modelo . "<br>patente: " . $this->_patente . "<br>Precio: " . $this->_precio . "<br><br>";
    }
}
class Servicio
{
    public $_id;
    public $_tipo;
    public $_precio;
    public $_demora;

    public function __construct($id, $tipo, $precio, $demora)
    {
        $this->_id = $id;
        $this->_tipo = $tipo;
        $this->_precio = $precio;
        $this->_demora = $demora;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    public function __toString()
    {
        return "Id: " . $this->_id. "<br>Tipo: " . $this->_tipo . "<br>precio: " . $this->_precio . "<br>Demora: " . $this->_demora . "<br><br>";
    }
}
//fecha,patente, marca, modelo, precio y tipo de servicio.
class Turno
{
    public $_fecha;
    public $_patente;
    public $_marca;
    public $_modelo;
    public $_precio;
    public $_tipo;


    public function __construct($fecha, $patente, $marca, $modelo, $precio, $tipo)
    {
        $this->_fecha = $fecha;
        $this->_patente = $patente;
        $this->_marca = $marca;
        $this->_modelo = $modelo;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    public function __toString()
    {
        return "Fecha: " . $this->_fecha. "<br>Patente: " . $this->_patente . "<br>Marca: " . $this->_marca . 
        "<br>Modelo: " . $this->_modelo. "<br>Precio: ". $this->_precio.  "<br>Tipo: " . $this->_tipo  . "<br><br>";
    }
}