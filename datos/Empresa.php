<?php
include_once("BaseDatos.php");
/**
 * CREATE TABLE empresa(
 * idempresa bigint AUTO_INCREMENT,
 * enombre varchar(150),
 * edireccion varchar(150),
 * PRIMARY KEY (idempresa)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
 */

class Empresa {
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeOperacion;

    public function __construct(){
        $this->idempresa = 0;
        $this->enombre = "";
        $this->edireccion = "";
    }

    public function getIdEmpresa() {
        return $this->idempresa;
    }
    public function getEnombre() {
        return $this->enombre;
    }
    public function getEdireccion() {
        return $this->edireccion;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    public function setIdEmpresa($nuevoId){
        $this->idempresa = $nuevoId;
    }
    public function setEnombre($nuevoNombre){
        $this->enombre = $nuevoNombre;
    }
    public function setEdireccion($nuevaDireccion){
        $this->edireccion = $nuevaDireccion;
    }
    public function setMensajeOperacion($nuevoMensaje){
        $this->mensajeOperacion = $nuevoMensaje;
    }

    public function cargar($idEmpresa, $nombreEmpresa, $direccionEmpresa){
        $this->setIdEmpresa($idEmpresa);
        $this->setEnombre($nombreEmpresa);
        $this->setEdireccion($direccionEmpresa);
    }

    public function __toString(){
        return "ID DE LA EMPRESA: " . $this->getIdEmpresa() . "\n" . 
        "NOMBRE DE LA EMPRESA: " . $this->getEnombre() . "\n" . 
        "DIRECCION DE LA EMPRESA: " . $this->getEdireccion() . "\n";
    }


    /**
     * Recupera los datos de la empresa dado el id
     * @param int $id
     * @return true en caso de encontrar los datos, false en caso contrario
     */
    public function Buscar($id){
        $base = new BaseDatos();
        $consultaEmpresa = "Select * from empresa where idempresa=".$id;
        $resp = false;
        if ($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresa)){
                if($row=$base->Registro()){
                    $this->setIdEmpresa($id);
                    $this->setEnombre($row["enombre"]);
                    $this->setEdireccion($row["edireccion"]);
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
        $arregloEmpresas = null;
        $base = new BaseDatos();
        $consultaEmpresas = "Select * from empresa ";
        if ($condicion != ""){
            $consultaEmpresas = $consultaEmpresas . " where " . $condicion;
        }
        $consultaEmpresas = $consultaEmpresas . " order by enombre ";

        if ($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresas)){
                $arregloEmpresas = array();
                while($row= $base->Registro()){
                    $obj = new Empresa();
                    $idEmpresa = $row["idempresa"];
                    $nombreEmpresa = $row["enombre"];
                    $direccionEmpresa = $row["edireccion"];
                    $obj->cargar($idEmpresa, $nombreEmpresa, $direccionEmpresa);
                    //$obj->Buscar($row["idempresa"]); MODIFICADO LUEGO DE LA EXPOSICION
                    array_push($arregloEmpresas, $obj);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloEmpresas;
    }


    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(idempresa, enombre, edireccion) VALUES 
        (".$this->getIdEmpresa().",'".$this->getEnombre()."','".$this->getEdireccion()."')";
        if($base->Iniciar()){
            if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdEmpresa($id);
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
        $consultaModifica="UPDATE empresa SET enombre='".$this->getEnombre()."',edireccion='".$this->getEdireccion()."' WHERE idempresa=". $this->getIdEmpresa();
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
            $consultaBorra = "DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
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