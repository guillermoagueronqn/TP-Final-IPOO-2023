<?php
include_once("BaseDatos.php");
include_once("Empresa.php");
include_once("Responsable.php");
include_once("Pasajero.php");
/**
 * CREATE TABLE viaje (
 *  idviaje bigint AUTO_INCREMENT, codigo de viaje*//*
 *	vdestino varchar(150),
 *  vcantmaxpasajeros int,
 *	idempresa bigint,
 *  rnumeroempleado bigint,
 *  vimporte float,
 *  PRIMARY KEY (idviaje),
 *  FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
 *	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
 *  ON UPDATE CASCADE
 *  ON DELETE CASCADE
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
 */

class Viaje {
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $objEmpresa;
    private $objResponsable;
    private $vimporte;
    private $coleccionObjPasajeros;
    private $mensajeOperacion;

    public function __construct(){
        $this->idviaje = 0;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = 0;
        $this->objEmpresa = new Empresa();
        $this->objResponsable = new Responsable();
        $this->vimporte = 0;
        $this->coleccionObjPasajeros = [];
    }

    public function getIdviaje(){
        return $this->idviaje;
    }
    public function getVdestino(){
        return $this->vdestino;
    }
    public function getVcantmaxpasajeros(){
        return $this->vcantmaxpasajeros;
    }
    public function getObjEmpresa(){
        return $this->objEmpresa;
    }
    public function getObjResponsable(){
        return $this->objResponsable;
    }
    public function getVimporte(){
        return $this->vimporte;
    }
    public function getColeccionObjPasajeros(){
        return $this->coleccionObjPasajeros;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    public function setIdviaje($nuevoId){
        $this->idviaje = $nuevoId;
    }
    public function setVdestino($nuevoDestino){
        $this->vdestino = $nuevoDestino;
    }
    public function setVcantmaxpasajeros($nuevaCantMaxPasajeros){
        $this->vcantmaxpasajeros = $nuevaCantMaxPasajeros;
    }
    public function setObjEmpresa($nuevaEmpresa){
        $this->objEmpresa = $nuevaEmpresa;
    }
    public function setObjResponsable($nuevoResponsable){
        $this->objResponsable = $nuevoResponsable;
    }
    public function setVimporte($nuevoImporte){
        $this->vimporte = $nuevoImporte;
    }
    public function setColeccionObjPasajeros($nuevaColeccion){
        $this->coleccionObjPasajeros = $nuevaColeccion;
    }
    public function setMensajeOperacion($nuevoMensajeOperacion){
        $this->mensajeOperacion = $nuevoMensajeOperacion;
    }


    public function retornarInfoPasajeros(){
        $informacionPasajeros = "";
        $coleccionActualPasajeros = $this->getColeccionObjPasajeros();
        for($i=0; $i < count($coleccionActualPasajeros); $i++){
            $pasajeroAMostrar = $coleccionActualPasajeros[$i];
            $informacionPasajeros = $informacionPasajeros . $pasajeroAMostrar . "\n";
        }
        return $informacionPasajeros;
    }
    public function __toString(){
        return "ID DEL VIAJE: " . $this->getIdviaje() . "\n" . 
        "DESTINO DEL VIAJE: " . $this->getVdestino() . "\n" . 
        "CANTIDAD MAXIMA DE PASAJEROS: " . $this->getVcantmaxpasajeros() . "\n" . 
        "INFORMACION DE LA EMPRESA: \n" . $this->getObjEmpresa() . "\n" . 
        "INFORMACION DEL RESPONSABLE DEL VIAJE: \n" . $this->getObjResponsable() . "\n" . 
        "IMPORTE DEL VIAJE: $" . $this->getVimporte() . "\n" .
        "-----------------------------------------------\n" . 
        "INFORMACION DE LOS PASAJEROS DEL VIAJE:\n" . $this->retornarInfoPasajeros() . "\n";
    }

    public function cargar($idViaje, $destinoViaje, $cantMaxPasViaje, $empresa, $responsable, $importeViaje){
        $this->setIdviaje($idViaje);
        $this->setVdestino($destinoViaje);
        $this->setVcantmaxpasajeros($cantMaxPasViaje);
        $this->setObjEmpresa($empresa);
        $this->setObjResponsable($responsable);
        $this->setVimporte($importeViaje);
    }


    public function Buscar($idViaje){
        $base = new BaseDatos();
        $consulta = "Select * from viaje where idviaje=". $idViaje;
        $resp = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                if($row=$base->Registro()){
                    $this->setIdviaje($row["idviaje"]);
                    $this->setVdestino($row["vdestino"]);
                    $this->setVcantmaxpasajeros($row["vcantmaxpasajeros"]);
                    $objEmpresa = new Empresa();
                    $objEmpresa->Buscar($row["idempresa"]);
                    $this->setObjEmpresa($objEmpresa);
                    $objResp = new Responsable();
                    $objResp->Buscar($row["rnumeroempleado"]);
                    $this->setObjResponsable($objResp);
                    $this->setVimporte($row["vimporte"]);
                    $objPasajero = new Pasajero();
                    $coleccionPasajeros = $objPasajero->listar("idviaje=" . $this->getIdviaje());
                    $this->setColeccionObjPasajeros($coleccionPasajeros);
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
        $arregloViajes = null;
        $base= new BaseDatos();
        $consulta = "Select * from viaje ";
        if($condicion!=""){
            $consulta = $consulta . " where ". $condicion;
        }
        $consulta = $consulta . " order by idviaje ";
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $arregloViajes = array();
                while($row=$base->Registro()){
                    $obj = new Viaje();
                    $idViaje = $row["idviaje"];
                    $destino = $row["vdestino"];
                    $cantMaximaPasajeros = $row["vcantmaxpasajeros"];
                    $objEmpresa = new Empresa();
                    $objEmpresa->Buscar($row["idempresa"]);
                    $objResponsable = new Responsable();
                    $objResponsable->Buscar($row["rnumeroempleado"]);
                    $importe = $row["vimporte"];
                    $obj->cargar($idViaje, $destino, $cantMaximaPasajeros, $objEmpresa, $objResponsable, $importe);
                    //$obj->Buscar($row["idviaje"]); MODIFICADO LUEGO DE LA EXPOSICION
                    array_push($arregloViajes, $obj);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloViajes;
    }


    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsercion = "INSERT INTO viaje (idviaje, vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
        VALUES (".$this->getIdviaje().",'".$this->getVdestino()."','".$this->getVcantmaxpasajeros()."','".$this->getObjEmpresa()->getIdEmpresa()."', 
        '".$this->getObjResponsable()->getRnumeroempleado()."','".$this->getVimporte()."')";
        if($base->Iniciar()){
            if($idViaje = $base->devuelveIDInsercion($consultaInsercion)){
                $this->setIdviaje($idViaje);
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
        $base= new BaseDatos();
        $consultaModifica = "UPDATE viaje SET vdestino='".$this->getVdestino()."',vcantmaxpasajeros='".$this->getVcantmaxpasajeros()."'
        ,idempresa='".$this->getObjEmpresa()->getIdEmpresa()."',rnumeroempleado='".$this->getObjResponsable()->getRnumeroempleado()."'
        ,vimporte='".$this->getVimporte()."' WHERE idviaje=".$this->getIdviaje();
        if($base->Iniciar()){
            if($base->Ejecutar($consultaModifica)){
                $resp= true;
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
            $consultaBorra = "DELETE FROM viaje WHERE idviaje=".$this->getIdviaje();
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