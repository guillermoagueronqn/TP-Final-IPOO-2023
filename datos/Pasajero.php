<?php
include_once("BaseDatos.php");
include_once("Viaje.php");
/**
 * CREATE TABLE pasajero (
 * pdocumento varchar(15),
 * pnombre varchar(150), 
 * papellido varchar(150), 
 * ptelefono int, 
 * idviaje bigint,
 * PRIMARY KEY (pasajero),
 * FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
 * )ENGINE=InnoDB DEFAULT CHARSET=utf8; 
 */

class Pasajero {
    private $pdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $idViaje;
    private $mensajeOperacion;

    public function __construct(){
        $this->pdocumento = "";
        $this->pnombre = "";
        $this->papellido = "";
        $this->ptelefono = 0;
        $this->idViaje = 0;
    }

    public function getPdocumento(){
        return $this->pdocumento;
    }
    public function getPnombre(){
        return $this->pnombre;
    }
    public function getPapellido(){
        return $this->papellido;
    }
    public function getPtelefono(){
        return $this->ptelefono;
    }
    public function getIdViaje(){
        return $this->idViaje;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    public function setPdocumento($nuevoDocumento){
        $this->pdocumento = $nuevoDocumento;
    }
    public function setPnombre($nuevoNombre){
        $this->pnombre = $nuevoNombre;
    }
    public function setPapellido($nuevoApellido){
        $this->papellido = $nuevoApellido;
    }
    public function setPtelefono($nuevoTelefono){
        $this->ptelefono = $nuevoTelefono;
    }
    public function setIdViaje($nuevoIdViaje){
        $this->idViaje = $nuevoIdViaje;
    }
    public function setMensajeOperacion($nuevoMensajeOperacion){
        $this->mensajeOperacion = $nuevoMensajeOperacion;
    }

    public function __toString(){
        return "NUMERO DE DOCUMENTO DEL PASAJERO: " . $this->getPdocumento() . "\n" . 
        "NOMBRE DEL PASAJERO: " . $this->getPnombre() . "\n" . 
        "APELLIDO DEL PASAJERO: " . $this->getPapellido() . "\n" . 
        "TELEFONO DEL PASAJERO: " . $this->getPtelefono() . "\n" . 
        "ID DEL VIAJE DEL PASAJERO: " . $this->getIdViaje() . "\n";
    }

    public function cargar($nroDocP, $nombreP, $apellidoP, $telefonoP, $idViajeP){
        $this->setPdocumento($nroDocP);
        $this->setPnombre($nombreP);
        $this->setPapellido($apellidoP);
        $this->setPtelefono($telefonoP);
        $this->setIdViaje($idViajeP);
    }


    public function Buscar($documento){
        $base = new BaseDatos();
        $consultaPasajero = "Select * from pasajero where pdocumento='" . $documento . "'";
        $resp = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaPasajero)){
                if($row = $base->Registro()){
                    $this->setPdocumento($row["pdocumento"]);
                    $this->setPnombre($row["pnombre"]);
                    $this->setPapellido($row["papellido"]);
                    $this->setPtelefono($row["ptelefono"]);
                    $this->setIdViaje($row["idviaje"]);
                    $resp = true;
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }


    public function listar($condicion=""){
        $arregloPasajeros = null;
        $base = new BaseDatos();
        $consulta = "Select * from pasajero ";
        if ($condicion!=""){
            $consulta = $consulta . " where ". $condicion;
        }
        $consulta = $consulta . " order by papellido ";
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $arregloPasajeros = array();
                while($row = $base->Registro()){
                    $obj = new Pasajero();
                    $nroDocumento = $row["pdocumento"];
                    $nombre = $row["pnombre"];
                    $apellido = $row["papellido"];
                    $telefono = $row["ptelefono"];
                    $idViaje = $row["idviaje"];
                    $obj->cargar($nroDocumento, $nombre, $apellido, $telefono, $idViaje);
                    //$obj->Buscar($row["pdocumento"]); MODIFICADO LUEGO DE LA EXPOSICION
                    array_push($arregloPasajeros, $obj);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloPasajeros;
    }


    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) 
        VALUES ('".$this->getPdocumento()."','".$this->getPnombre()."','".$this->getPapellido()."','".$this->getPtelefono()."', 
        '".$this->getIdViaje()."')";
        if($base->Iniciar()){
            if ($base->Ejecutar($consultaInsertar)){    
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }


    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE pasajero SET pnombre='".$this->getPnombre()."',papellido='".$this->getPapellido()."'
        ,ptelefono='".$this->getPtelefono()."',idviaje='".$this->getIdViaje()."' WHERE pdocumento='".$this->getPdocumento() . "'";
        if($base->Iniciar()){
            if($base->Ejecutar($consultaModifica)){
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }


    public function eliminar(){
        $base = new BaseDatos();
        $resp = false;
        if($base->Iniciar()){
            $consultaBorra = $consultaBorra="DELETE FROM pasajero WHERE pdocumento='".$this->getPdocumento() . "'";
            if($base->Ejecutar($consultaBorra)){
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }
} 