<?php
include_once("BaseDatos.php");
/**
 * CREATE TABLE responsable (
 * rnumeroempleado bigint AUTO_INCREMENT,
 * rnumerolicencia bigint,
 * rnombre varchar(150), 
 * rapellido  varchar(150), 
 * PRIMARY KEY (rnumeroempleado)
 * )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;;
 */

class Responsable {
    private $rnumeroempleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;
    private $mensajeOperacion;

    public function __construct(){
        $this->rnumeroempleado = 0;
        $this->rnumerolicencia = 0;
        $this->rnombre = "";
        $this->rapellido = "";
    }

    public function getRnumeroempleado(){
        return $this->rnumeroempleado;
    }
    public function getRnumerolicencia(){
        return $this->rnumerolicencia;
    }
    public function getRnombre(){
        return $this->rnombre;
    }
    public function getRapellido(){
        return $this->rapellido;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    public function setRnumeroempleado($nuevoNumeroEmpleado){
        $this->rnumeroempleado = $nuevoNumeroEmpleado;
    }
    public function setRnumerolicencia($nuevoNumeroLicencia){
        $this->rnumerolicencia = $nuevoNumeroLicencia;
    }
    public function setRnombre($nuevoNombre){
        $this->rnombre = $nuevoNombre;
    }
    public function setRapellido($nuevoApellido){
        $this->rapellido = $nuevoApellido;
    }
    public function setMensajeOperacion($nuevoMensajeOperacion){
        $this->mensajeOperacion = $nuevoMensajeOperacion;
    }

    public function __toString(){
        return "NUMERO DE EMPLEADO: " . $this->getRnumeroempleado() . "\n" . 
        "NUMERO DE LICENCIA: " . $this->getRnumerolicencia() . "\n" . 
        "NOMBRE DEL RESPONSABLE: " . $this->getRnombre() . "\n" . 
        "APELLIDO DEL RESPONSABLE: " . $this->getRapellido() . "\n";
    }

    public function cargar($numeroEmpleado ,$numLicencia, $nom, $ape){
        $this->setRnumeroempleado($numeroEmpleado);
        $this->setRnumerolicencia($numLicencia);
        $this->setRnombre($nom);
        $this->setRapellido($ape);
    }

    /**
     * Recupera los datos del responsable dado el numero de empleado
     * @param int $numeroEmpleado
     * @return true en caso de encontrar los datos, false en caso contrario
     */
    public function Buscar($numeroEmpleado){
        $base = new BaseDatos();
        $consultaResponsable = "Select * from responsable where rnumeroempleado=". $numeroEmpleado;
        $resp = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaResponsable)){
                if($row=$base->Registro()){
                    $this->setRnumeroempleado($numeroEmpleado);
                    $this->setRnumerolicencia($row["rnumerolicencia"]);
                    $this->setRnombre($row["rnombre"]);
                    $this->setRapellido($row["rapellido"]);
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
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "Select * from responsable ";
        if($condicion!=""){
            $consultaResponsable = $consultaResponsable . " where ". $condicion;
        }
        $consultaResponsable = $consultaResponsable . " order by rapellido ";
        if($base->Iniciar()){
            if($base->Ejecutar($consultaResponsable)){
                $arregloResponsable = array();
                while($row=$base->Registro()){
                    $obj = new Responsable();
                    $numeroEmpleado = $row["rnumeroempleado"];
                    $numeroLicencia = $row["rnumerolicencia"];
                    $nombre = $row["rnombre"];
                    $apellido = $row["rapellido"];
                    $obj->cargar($numeroEmpleado, $numeroLicencia, $nombre, $apellido);
                    //$obj->Buscar($row["rnumeroempleado"]); MODIFICADO LUEGO DE LA EXPOSICION
                    array_push($arregloResponsable, $obj);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloResponsable;
    }


    public function insertar(){
        $base= new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO responsable (rnumeroempleado, rnumerolicencia, rnombre, rapellido) 
        VALUES (".$this->getRnumeroempleado().",'".$this->getRnumerolicencia()."','".$this->getRnombre()."','".$this->getRapellido()."')";
        if($base->Iniciar()){
            if($numeroEmpleado = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setRnumeroempleado($numeroEmpleado);
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
        $consultaModifica = "UPDATE responsable SET rnumerolicencia='".$this->getRnumerolicencia()."',rnombre='".$this->getRnombre()."'
        ,rapellido='".$this->getRapellido()."' WHERE rnumeroempleado=".$this->getRnumeroempleado();
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
            $consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado=".$this->getRnumeroempleado();
            if ($base->Ejecutar($consultaBorra)){
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