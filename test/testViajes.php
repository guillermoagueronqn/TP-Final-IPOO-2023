<?php
include_once("../datos/BaseDatos.php");
include_once("../datos/Empresa.php");
include_once("../datos/Responsable.php");
include_once("../datos/Viaje.php");
include_once("../datos/Pasajero.php");
// TEST PARA PODER INSERTAR, MODIFICAR, BORRAR Y MOSTRAR LOS DATOS DE 
// EMPRESAS, PASAJEROS, RESPONSABLES, VIAJES

/**
 * FUNCION para corroborar que la seleccion de opciones sea valida
 */
function solicitarNumeroEntre($min, $max) {
    //int $numero
    $numero = (trim(fgets(STDIN)));
    while ((((int)($numero) != $numero)) || (!($numero >= $min && $numero <= $max))) {
        echo "Debe ingresar un número entre " . $min . " y " . $max . ": ";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}

function solicitarNumeroPositivo() {
    //int $numero
    $numero = (trim(fgets(STDIN)));
    while ((((int)($numero) != $numero)) || (!($numero > 0))) {
        echo "Debe ingresar un número entero positivo: ";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}

function solicitarNumero(){
    $numero = trim(fgets(STDIN));
    while((!(is_numeric($numero))) || (!($numero > 0))){
        echo "Debe ingresar un numero positivo: ";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}

// MENU

do {
    echo "BIENVENIDO AL MENU!!\n";
    echo "OPCIONES DISPONIBLES:\n" ;
    echo "-----------------------------------------------\n";
    echo "--------------OPCIONES DE INGRESO--------------\n";
    echo "
    1. Ingresar una nueva empresa.
    2. Ingresar un nuevo responsable.
    3. Ingresar un nuevo viaje.
    4. Ingresar un nuevo pasajero.\n
    --------------OPCIONES DE MODIFICACION--------------
    5. Modificar la empresa.
    6. Modificar un responsable.
    7. Modificar un viaje.
    8. Modificar un pasajero.\n
    --------------OPCIONES DE ELIMINACION--------------
    9. Eliminar la empresa.
    10. Eliminar un responsable.
    11. Eliminar un viaje.
    12. Eliminar un pasajero.\n
    --------------OPCIONES DE MUESTRA--------------
    13. Mostrar la empresa.
    14. Mostrar los responsables.
    15. Mostrar los viajes.
    16. Mostrar los pasajeros.\n
    --------------OPCION PARA SALIR DEL MENU--------------
    17. Salir del menu.\n";
    echo "Ingrese la opcion que desea ejecutar: ";
    $opcion = solicitarNumeroEntre(1, 17);
    switch ($opcion){
        case 1:
            $objEmpresa = new Empresa();
            $coleccionEmpresas = $objEmpresa->listar();
            if (count($coleccionEmpresas) < 1){
                echo "Ingrese el nombre de la empresa: ";
                $nombreEmpresa = trim(fgets(STDIN));
                echo "Ingrese la direccion de la empresa: ";
                $direccionEmpresa = trim(fgets(STDIN));
                $objEmpresa->cargar(0, $nombreEmpresa, $direccionEmpresa);
                $respuesta = $objEmpresa->insertar();
                if ($respuesta){
                    echo "LA EMPRESA HA SIDO INGRESADA CORRECTAMENTE!\n";
                } else {
                    echo "NO SE HA PODIDO INSERTAR LA EMPRESA EN LA BASE DE DATOS\n";
                }
            } else {
                echo "YA EXISTE UNA EMPRESA. PARA CREAR UNA NUEVA DEBES ELIMINAR LA EMPRESA QUE YA EXISTE\n";
            }
            
            break;
        case 2:
            $objResponsable = new Responsable();
            echo "Ingrese el nombre del responsable: ";
            $nombreResponsable = trim(fgets(STDIN));
            echo "Ingrese el apellido del responsable: ";
            $apellidoResponsable = trim(fgets(STDIN));
            echo "Ingrese el numero de licencia del responsable: ";
            $numeroLicenciaResponsable = solicitarNumeroPositivo();
            $objResponsable->cargar(0, $numeroLicenciaResponsable, $nombreResponsable, $apellidoResponsable);
            $respuesta = $objResponsable->insertar();
            if ($respuesta){
                echo "EL RESPONSABLE HA SIDO INGRESADO CORRECTAMENTE!\n";
            } else {
                echo "NO SE HA PODIDO INSERTAR EL RESPONSABLE EN LA BASE DE DATOS\n";
            }
            break;
        case 3:
            $objEmpresa = new Empresa();
            $objResponsable = new Responsable();
            $coleccionEmpresas = $objEmpresa->listar();
            $coleccionResponsables = $objResponsable->listar();
            if ($coleccionEmpresas == null || $coleccionResponsables == null){
                echo "ANTES DE INGRESAR UN PASAJERO DEBES TENER CREADA UNA EMPRESA Y UN RESPONSABLE!\n";
            } else {
                for ($i=0; $i < count($coleccionEmpresas); $i++){
                    $empresaAMostrar = $coleccionEmpresas[$i];
                    echo $empresaAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el id de una de las empresas listadas para tu viaje: ";
                $idEmpresaIngresado = trim(fgets(STDIN));
                for($i=0; $i < count($coleccionResponsables); $i++){
                    $responsableAMostrar = $coleccionResponsables[$i];
                    echo $responsableAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el numero de empleado de uno de los responsables listados para tu viaje: ";
                $numeroResponsableIngresado = trim(fgets(STDIN));
                $i = 0;
                $idEncontrado = false;
                while($i < count($coleccionEmpresas) && (!$idEncontrado)){
                    $idAComparar = $coleccionEmpresas[$i]->getIdEmpresa();
                    if ($idEmpresaIngresado == $idAComparar){
                        $idEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                $numeroResponsableEncontrado = false;
                $i = 0;
                while($i < count($coleccionResponsables) && (!$numeroResponsableEncontrado)){
                    $numeroAComparar = $coleccionResponsables[$i]->getRnumeroempleado();
                    if ($numeroResponsableIngresado == $numeroAComparar){
                        $numeroResponsableEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if ($idEncontrado){
                    if ($numeroResponsableEncontrado){
                        $objEmpresa->Buscar($idEmpresaIngresado);
                        $objResponsable->Buscar($numeroResponsableIngresado);
                        echo "Ingrese el destino del viaje: ";
                        $destinoViaje = trim(fgets(STDIN));
                        echo "Ingrese la cantidad maxima de pasajeros del viaje: ";
                        $cantMaximaPasajerosViaje = solicitarNumeroPositivo();
                        echo "Ingrese el importe del viaje: ";
                        $importeViaje = solicitarNumero();
                        $objViaje = new Viaje();
                        $objViaje->cargar(0, $destinoViaje, $cantMaximaPasajerosViaje, $objEmpresa, $objResponsable, $importeViaje);
                        $respuesta = $objViaje->insertar();
                        if ($respuesta){
                            echo "EL VIAJE HA SIDO INGRESADO EN LA BASE DE DATOS CORRECTAMENTE\n";
                        } else {
                            echo "NO SE HA PODIDO INSERTAR EL VIAJE A LA BASE DE DATOS\n";
                        }
                    } else {
                        echo "EL NUMERO DE EMPLEADO DEL RESPONSABLE INGRESADO NO COINCIDE CON NINGUN RESPONSABLE DE LA BASE DE DATOS\n";
                    }
                } else {
                    echo "EL ID DE LA EMPRESA INGRESADO NO COINCIDE CON NINGUNA EMPRESA DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 4:
            $objViaje = new Viaje();
            $coleccionViajes = $objViaje->listar();
            if ($coleccionViajes == null){
                echo "PARA CREAR UN PASAJERO, PRIMERO DEBE EXISTIR UN VIAJE\n";
            } else {
                for($i=0; $i < count($coleccionViajes); $i++){
                    $viajeAMostrar = $coleccionViajes[$i];
                    echo $viajeAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el id de uno de los viajes listados para el pasajero: ";
                $idViajeElegido = trim(fgets(STDIN));
                $i = 0;
                $viajeEncontrado = false;
                while($i < count($coleccionViajes) && (!$viajeEncontrado)){
                    $idViajeAComparar = $coleccionViajes[$i]->getIdviaje();
                    if($idViajeElegido == $idViajeAComparar){
                        $viajeEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if($viajeEncontrado){
                    $objViaje->Buscar($idViajeElegido);
                    $pasajerosConViajeSeleccionado = 0;
                    $objPasajero = new Pasajero();
                    $arregloPasajeros = $objPasajero->listar();
                    for($i = 0; $i <count($arregloPasajeros); $i++){
                        if($arregloPasajeros[$i]->getIdViaje() == $idViajeElegido){
                            $pasajerosConViajeSeleccionado = $pasajerosConViajeSeleccionado+1;
                        }
                    }
                    if ($objViaje->getVcantmaxpasajeros() > $pasajerosConViajeSeleccionado){
                        echo "Ingrese el dni del pasajero: ";
                        $dniPasajero = trim(fgets(STDIN));
                        $dniHabilitado = true;
                        $i=0;
                        while($i < count($arregloPasajeros) && $dniHabilitado){
                            if($dniPasajero == $arregloPasajeros[$i]->getPdocumento()){
                                $dniHabilitado = false;
                            } else {
                                $i++;
                            }
                        }
                        if ($dniHabilitado){
                            echo "Ingrese el nombre del pasajero: ";
                            $nombrePasajero = trim(fgets(STDIN));
                            echo "Ingrese el apellido del pasajero: ";
                            $apellidoPasajero = trim(fgets(STDIN));
                            echo "Ingrese el telefono del pasajero: ";
                            $telefonoPasajero = solicitarNumeroPositivo();
                            $objPasajero = new Pasajero();
                        $objPasajero->cargar($dniPasajero, $nombrePasajero, $apellidoPasajero, $telefonoPasajero, $idViajeElegido);
                            $respuesta = $objPasajero->insertar();
                            if($respuesta){
                                echo "EL PASAJERO HA SIDO INGRESADO CORRECTAMENTE A LA BASE DE DATOS!\n";
                            } else {
                                echo "NO SE HA PODIDO INGRESAR AL PASAJERO A LA BASE DE DATOS\n";
                            }
                        } else {
                            echo "YA EXISTE UN PASAJERO CON EL DNI INGRESADO.\n";
                        }   
                    } else {
                        echo "NO QUEDAN LUGARES DISPONIBLES PARA EL VIAJE SELECCIONADO\n";
                    }
                } else {
                    echo "EL ID DE VIAJE INGRESADO NO COINCIDE CON NINGUN VIAJE DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 5:
            $objEmpresa = new Empresa();
            $coleccionEmpresas = $objEmpresa->listar();
            if ($coleccionEmpresas == null){
                echo "NO EXISTEN EMPRESAS PARA MODIFICAR\n";
            } else {
                for ($i=0; $i < count($coleccionEmpresas); $i++){
                    $empresaAMostrar = $coleccionEmpresas[$i];
                    echo $empresaAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el id de una de las empresas listadas arriba para poder modificarla: ";
                $idEmpresaAModificar = trim(fgets(STDIN));
                $i = 0;
                $idEncontrado = false;
                while($i < count($coleccionEmpresas) && (!$idEncontrado)){
                    $idAComparar = $coleccionEmpresas[$i]->getIdEmpresa();
                    if ($idEmpresaAModificar == $idAComparar){
                        $idEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if ($idEncontrado){
                    $objEmpresa = new Empresa();
                    $objEmpresa->Buscar($idEmpresaAModificar);
                    echo "Que desea modificar de la empresa? (1.nombre / 2.direccion): ";
                    $atributoAModificar = solicitarNumeroEntre(1,2);
                    if ($atributoAModificar == 1){
                        echo "Ingrese el nuevo nombre de la empresa: ";
                        $nuevoNombreEmpresa = trim(fgets(STDIN));
                        $objEmpresa->setEnombre($nuevoNombreEmpresa);
                        
                    } elseif($atributoAModificar == 2){
                        echo "Ingrese la nueva direccion de la empresa: ";
                        $nuevaDireccionEmpresa = trim(fgets(STDIN));
                        $objEmpresa->setEdireccion($nuevaDireccionEmpresa);
                    }
                    $respuesta = $objEmpresa->modificar();
                    if($respuesta){
                        echo "LA MODIFICACION DE LA EMPRESA SE HA REALIZADO CORRECTAMENTE!\n";
                    } else {
                        echo "NO SE HA PODIDO REALIZAR LA MODIFICACION DE LA EMPRESA\n";
                    }
                } else {
                    echo "EL ID INGRESADO NO COINCIDE CON NINGUNA EMPRESA DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 6:
            $objResponsable = new Responsable();
            $coleccionResponsables = $objResponsable->listar();
            if ($coleccionResponsables == null){
                echo "NO EXISTEN RESPONSABLES PARA MODIFICAR.\n";
            } else {
                for($i=0; $i < count($coleccionResponsables); $i++){
                    $responsableAMostrar = $coleccionResponsables[$i];
                    echo $responsableAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige un numero de empleado de alguno de los responsables listados para modificar: ";
                $numEmpleadoAModificar = trim(fgets(STDIN));
                $i = 0;
                $numEmpleadoEncontrado = false;
                while($i < count($coleccionResponsables) && (!$numEmpleadoEncontrado)){
                    if ($coleccionResponsables[$i]->getRnumeroempleado() == $numEmpleadoAModificar){
                        $numEmpleadoEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if($numEmpleadoEncontrado){
                    echo "Que desea modificar del responsable? (1.nombre / 2.apellido / 3.numero de licencia): ";
                    $atributoAModificar = solicitarNumeroEntre(1, 3);
                    $objResponsable->Buscar($numEmpleadoAModificar);
                    if ($atributoAModificar == 1){
                        echo "Ingrese el nuevo nombre del responsable: ";
                        $nuevoNombreResponsable = trim(fgets(STDIN));
                        $objResponsable->setRnombre($nuevoNombreResponsable);
                    } elseif ($atributoAModificar == 2) {
                        echo "Ingrese el nuevo apellido del responsable: ";
                        $nuevoApellidoResponsable = trim(fgets(STDIN));
                        $objResponsable->setRapellido($nuevoApellidoResponsable);
                    } elseif ($atributoAModificar == 3){
                        echo "Ingrese el nuevo numero de licencia del responsable: ";
                        $nuevoNumeroLicenciaResponsable = solicitarNumeroPositivo();
                        $objResponsable->setRnumerolicencia($nuevoNumeroLicenciaResponsable);
                    }
                    $respuesta = $objResponsable->modificar();
                    if($respuesta){
                        echo "LA MODIFICACION DEL RESPONSABLE SE HA REALIZADO CORRECTAMENTE\n";
                    } else {
                        echo "NO SE HA PODIDO REALIZAR LA MODIFICACION\n";
                    }
                } else {
                    echo "EL NUMERO DE EMPLEADO NO COINCIDE CON NINGUN RESPONSABLE DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 7:
            $objViaje = new Viaje();
            $coleccionViajes = $objViaje->listar();
            if ($coleccionViajes == null){
                echo "NO EXISTEN VIAJES PARA MODIFICAR\n";
            } else {
                for($i=0; $i < count($coleccionViajes); $i++){
                    $viajeAMostrar = $coleccionViajes[$i];
                    echo $viajeAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige un id de los viajes listados para modificar: ";
                $idViajeAModificar = trim(fgets(STDIN));
                $i = 0;
                $viajeEncontrado = false;
                while($i < count($coleccionViajes) && (!$viajeEncontrado)){
                    if ($coleccionViajes[$i]->getIdviaje() == $idViajeAModificar){
                        $viajeEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if ($viajeEncontrado){
                    $objViaje->Buscar($idViajeAModificar);
                    echo "Que desea modificar? (1.destino / 2.cantidad maxima de pasajeros / 3.id de la empresa / 4.numero de empleado del responsable / 5.importe del viaje): ";
                    $atributoAModificar = solicitarNumeroEntre(1, 5);
                    if ($atributoAModificar == 1){
                        echo "Ingrese el nuevo destino del viaje: ";
                        $nuevoDestinoViaje = trim(fgets(STDIN));
                        $objViaje->setVdestino($nuevoDestinoViaje);
                        $respuesta = $objViaje->modificar();
                        if ($respuesta){
                            echo "LA MODIFICACION DEL VIAJE SE HA REALIZADO CORRECTAMENTE!\n";
                        } else {
                            echo "NO SE HA PODIDO REALIZAR LA MODIFICACION DEL VIAJE\n";
                        }
                    } elseif($atributoAModificar == 2){
                        echo "Ingrese la nueva cantidad maxima de pasajeros del viaje: ";
                        $nuevaCantMaximaPasajeros = solicitarNumeroPositivo();
                        $objPasajero = new Pasajero();
                        $coleccionPasajeros = $objPasajero->listar();
                        $cantidadPasajerosEnViaje = 0;
                        for($i=0; $i < count($coleccionPasajeros); $i++){
                            if ($coleccionPasajeros[$i]->getIdViaje() == $idViajeAModificar){
                                $cantidadPasajerosEnViaje = $cantidadPasajerosEnViaje + 1;
                            }
                        }
                        if($nuevaCantMaximaPasajeros >= $cantidadPasajerosEnViaje){
                            $objViaje->setVcantmaxpasajeros($nuevaCantMaximaPasajeros);
                            $respuesta = $objViaje->modificar();
                            if ($respuesta){
                                echo "LA MODIFICACION DEL VIAJE SE HA REALIZADO CORRECTAMENTE!\n";
                            } else {
                                echo "NO SE HA PODIDO REALIZAR LA MODIFICACION DEL VIAJE\n";
                            }
                        } else {
                            echo "NO SE HA PODIDO MODIFICAR LA CANTIDAD MAXIMA DE PASAJEROS PORQUE LA NUEVA CANTIDAD MAXIMA ES MENOR A LA CANTIDAD ACTUAL DE PASAJEROS DEL VIAJE\n";
                        }
                        
                    }elseif($atributoAModificar == 3){
                        $objEmpresa = new Empresa();
                        $coleccionEmpresas = $objEmpresa->listar();
                        for ($i=0; $i < count($coleccionEmpresas); $i++){
                            $empresaAMostrar = $coleccionEmpresas[$i];
                            echo $empresaAMostrar;
                            echo "-----------------------------------------\n";
                        }
                        echo "Elige el nuevo id de una de las empresas listadas para tu viaje: ";
                        $idEmpresaNuevo = trim(fgets(STDIN));
                        $i = 0;
                        $idEncontrado = false;
                        while($i < count($coleccionEmpresas) && (!$idEncontrado)){
                            $idAComparar = $coleccionEmpresas[$i]->getIdEmpresa();
                            if ($idEmpresaNuevo == $idAComparar){
                                $idEncontrado = true;
                            } else {
                                $i++;
                            }
                        }
                        if ($idEncontrado){
                            $objEmpresa->Buscar($idEmpresaNuevo);
                            $objViaje->setObjEmpresa($objEmpresa);
                            $respuesta = $objViaje->modificar();
                            if ($respuesta){
                                echo "LA MODIFICACION DEL VIAJE SE HA REALIZADO CORRECTAMENTE!\n";
                            } else {
                                echo "NO SE HA PODIDO REALIZAR LA MODIFICACION DEL VIAJE\n";
                            }
                        } else {
                            echo "EL ID DE EMPRESA INGRESADO NO COINCIDE CON NINGUNA EMPRESA DE LA BASE DE DATOS\n";
                        }
                    }elseif($atributoAModificar == 4){
                        $objResponsable = new Responsable();
                        $coleccionResponsables = $objResponsable->listar();
                        for($i=0; $i < count($coleccionResponsables); $i++){
                            $responsableAMostrar = $coleccionResponsables[$i];
                            echo $responsableAMostrar;
                            echo "-----------------------------------------\n";
                        }
                        echo "Elige el nuevo numero de empleado de uno de los responsables listados para tu viaje: ";
                        $numEmpleadoNuevoViaje = trim(fgets(STDIN));
                        $numeroResponsableEncontrado = false;
                        $i = 0;
                        while($i < count($coleccionResponsables) && (!$numeroResponsableEncontrado)){
                            $numeroAComparar = $coleccionResponsables[$i]->getRnumeroempleado();
                            if ($numEmpleadoNuevoViaje == $numeroAComparar){
                                $numeroResponsableEncontrado = true;
                            } else {
                                $i++;
                            }
                        }
                        if($numeroResponsableEncontrado){
                            $objResponsable->Buscar($numEmpleadoNuevoViaje);
                            $objViaje->setObjResponsable($objResponsable);
                            $respuesta = $objViaje->modificar();
                            if ($respuesta){
                                echo "LA MODIFICACION DEL VIAJE SE HA REALIZADO CORRECTAMENTE!\n";
                            } else {
                                echo "NO SE HA PODIDO REALIZAR LA MODIFICACION DEL VIAJE\n";
                            }
                        } else {
                            echo "EL NUMERO DE EMPLEADO NO COINCIDE CON NINGUN RESPONSABLE DE LA BASE DE DATOS\n";
                        }
                    }elseif($atributoAModificar == 5){
                        echo "Ingrese el nuevo importe del viaje: ";
                        $nuevoImporteViaje = solicitarNumero();
                        $objViaje->setVimporte($nuevoImporteViaje);
                        $respuesta = $objViaje->modificar();
                        if ($respuesta){
                            echo "LA MODIFICACION DEL VIAJE SE HA REALIZADO CORRECTAMENTE!\n";
                        } else {
                            echo "NO SE HA PODIDO REALIZAR LA MODIFICACION DEL VIAJE\n";
                        }
                    }
                    
                } else {
                    echo "EL ID DEL VIAJE INGRESADO NO COINCIDE CON NINGUN VIAJE DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 8:
            $objPasajero = new Pasajero();
            $coleccionPasajeros = $objPasajero->listar();
            if ($coleccionPasajeros == null){
                echo "NO EXISTEN PASAJEROS PARA MODIFICAR\n";
            } else {
                for($i=0; $i < count($coleccionPasajeros); $i++){
                    $pasajeroAMostrar = $coleccionPasajeros[$i];
                    echo $pasajeroAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el numero de documento de alguno de los pasajeros listados que desea modificar: ";
                $numDocumentoPasajero = trim(fgets(STDIN));
                $i = 0;
                $pasajeroEncontrado = false;
                while($i < count($coleccionPasajeros) && (!$pasajeroEncontrado)){
                    if ($coleccionPasajeros[$i]->getPdocumento() == $numDocumentoPasajero){
                        $pasajeroEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if ($pasajeroEncontrado){
                    $objPasajero->Buscar($numDocumentoPasajero);
                    echo "Que desea modificar? (1.nombre / 2.apellido / 3.telefono / 4.id viaje): ";
                    $atributoAModificar = solicitarNumeroEntre(1, 4);
                    if ($atributoAModificar == 1){
                        echo "Ingrese el nuevo nombre del pasajero: ";
                        $nuevoNombrePasajero = trim(fgets(STDIN));
                        $objPasajero->setPnombre($nuevoNombrePasajero);
                        $respuesta = $objPasajero->modificar();
                        if ($respuesta){
                            echo "LA MODIFICACION DEL PASAJERO SE HA REALIZADO CORRECTAMENTE!\n";
                        } else {
                            echo "NO SE HA PODIDO REALIZAR LA MODIFICACION\n";
                        }
                    } elseif($atributoAModificar == 2){
                        echo "Ingrese el nuevo apellido del pasajero: ";
                        $nuevoApellidoPasajero = trim(fgets(STDIN));
                        $objPasajero->setPapellido($nuevoApellidoPasajero);
                        $respuesta = $objPasajero->modificar();
                        if ($respuesta){
                            echo "LA MODIFICACION DEL PASAJERO SE HA REALIZADO CORRECTAMENTE!\n";
                        } else {
                            echo "NO SE HA PODIDO REALIZAR LA MODIFICACION\n";
                        }
                    }elseif($atributoAModificar == 3){
                        echo "Ingrese el nuevo telefono del pasajero: ";
                        $nuevoTelefonoPasajero = solicitarNumeroPositivo();
                        $objPasajero->setPtelefono($nuevoTelefonoPasajero);
                        $respuesta = $objPasajero->modificar();
                        if ($respuesta){
                            echo "LA MODIFICACION DEL PASAJERO SE HA REALIZADO CORRECTAMENTE!\n";
                        } else {
                            echo "NO SE HA PODIDO REALIZAR LA MODIFICACION\n";
                        }
                    }elseif($atributoAModificar == 4){
                        $objViaje = new Viaje();
                        $coleccionViajes = $objViaje->listar();
                        for($i=0; $i < count($coleccionViajes); $i++){
                            $viajeAMostrar = $coleccionViajes[$i];
                            echo $viajeAMostrar;
                            echo "-----------------------------------------\n";
                        }
                        echo "Elige un id de los viajes listados para su pasajero: ";
                        $idViajePasajero = trim(fgets(STDIN));
                        $i = 0;
                        $idViajeEncontrado = false;
                        while($i < count($coleccionViajes) && (!$idViajeEncontrado)){
                            if($coleccionViajes[$i]->getIdviaje() == $idViajePasajero){
                                $idViajeEncontrado = true;
                            } else {
                                $i++;
                            }
                        }
                        if ($idViajeEncontrado){
                            $objViaje->Buscar($idViajePasajero);
                            $cantidadPasajerosEnViaje = 0;
                            $coleccionPasajeros = $objPasajero->listar();
                            for($i=0; $i < count($coleccionPasajeros); $i++){
                                if($coleccionPasajeros[$i]->getIdViaje() == $idViajePasajero){
                                    $cantidadPasajerosEnViaje = $cantidadPasajerosEnViaje + 1;
                                }
                            }
                            if ($objViaje->getVcantmaxpasajeros() > $cantidadPasajerosEnViaje){
                                $objPasajero->setIdViaje($idViajePasajero);
                                $respuesta = $objPasajero->modificar();
                                if ($respuesta){
                                    echo "LA MODIFICACION DEL PASAJERO SE HA REALIZADO CORRECTAMENTE!\n";
                                } else {
                                    echo "NO SE HA PODIDO REALIZAR LA MODIFICACION\n";
                                }
                            } else {
                                echo "NO QUEDAN LUGARES EN EL VIAJE\n";
                            }
                        } else {
                            echo "EL ID DEL VIAJE INGRESADO NO COINCIDE CON NINGUN VIAJE DE LA BASE DE DATOS\n";
                        }
                    }
                } else {
                    echo "EL NUMERO DE DOCUMENTO DEL PASAJERO INGRESADO NO COINCIDE CON NINGUN PASAJERO DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 9:
            // eliminar una empresa
            $objEmpresa = new Empresa();
            $coleccionEmpresas = $objEmpresa->listar();
            if ($coleccionEmpresas == null){
                echo "NO EXISTEN EMPRESAS PARA ELIMINAR\n";
            } else {
                for($i=0; $i < count($coleccionEmpresas); $i++){
                    $empresaAMostrar = $coleccionEmpresas[$i];
                    echo $empresaAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el id de una de las empresas listadas que quiera eliminar: ";
                $idEmpresaAEliminar = trim(fgets(STDIN));
                $i = 0;
                $idEmpresaEncontrado = false;
                while($i < count($coleccionEmpresas) && (!$idEmpresaEncontrado)){
                    if ($coleccionEmpresas[$i]->getIdEmpresa() == $idEmpresaAEliminar){
                        $idEmpresaEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if ($idEmpresaEncontrado){
                    $viajesConIdSeleccionado = 0;
                    $objViaje = new Viaje();
                    $coleccionViajes = $objViaje->listar();
                    for($i=0; $i < count($coleccionViajes); $i++){
                        if ($coleccionViajes[$i]->getObjEmpresa()->getIdEmpresa() == $idEmpresaAEliminar){
                            $viajesConIdSeleccionado = $viajesConIdSeleccionado + 1;
                        }
                    }
                    if ($viajesConIdSeleccionado == 0){
                        $objEmpresa->Buscar($idEmpresaAEliminar);
                        $respuesta = $objEmpresa->eliminar();
                        if($respuesta){
                            echo "SE HA ELIMINADO LA EMPRESA CORRECTAMENTE!\n";
                        } else {
                            echo "NO SE HA PODIDO ELIMINAR LA EMPRESA\n";
                        }
                    } else {
                        echo "NO SE PUEDE ELIMINAR LA EMPRESA DEBIDO A QUE EXISTEN VIAJES CON SU ID\n";
                    }
                } else {
                    echo "EL ID DE LA EMPRESA INGRESADO NO COINCIDE CON NINGUNA EMPRESA DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 10:
            // eliminar un responsable
            $objResponsable = new Responsable();
            $coleccionResponsables = $objResponsable->listar();
            if ($coleccionResponsables == null){
                echo "NO EXISTEN RESPONSABLES PARA ELIMINAR\n";
            } else {
                for ($i=0; $i < count($coleccionResponsables); $i++){
                    $responsableAMostrar = $coleccionResponsables[$i];
                    echo $responsableAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige un numero de empleado de alguno de los responsables listados: ";
                $numEmpleadoAEliminar = trim(fgets(STDIN));
                $i = 0;
                $numEmpleadoEncontrado = false;
                while($i < count($coleccionResponsables) && (!$numEmpleadoEncontrado)){
                    if ($coleccionResponsables[$i]->getRnumeroempleado() == $numEmpleadoAEliminar){
                        $numEmpleadoEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if ($numEmpleadoEncontrado){
                    $viajesConResponsableSeleccionado = 0;
                    $objViaje = new Viaje();
                    $coleccionViajes = $objViaje->listar();
                    for($i=0; $i < count($coleccionViajes); $i++){
                        if ($coleccionViajes[$i]->getObjResponsable()->getRnumeroempleado() == $numEmpleadoAEliminar){
                            $viajesConResponsableSeleccionado = $viajesConResponsableSeleccionado + 1;
                        }
                    }
                    if ($viajesConResponsableSeleccionado == 0){
                        $objResponsable->Buscar($numEmpleadoAEliminar);
                        $respuesta = $objResponsable->eliminar();
                        if ($respuesta){
                            echo "SE HA REALIZADO LA ELIMINACION DEL RESPONSABLE CORRECTAMENTE\n";
                        } else {
                            echo "NO SE HA PODIDO ELIMINAR AL RESPONSABLE\n";
                        }
                    } else {
                        echo "NO SE HA PODIDO ELIMINAR EL RESPONSABLE DEBIDO A QUE EXISTEN VIAJES CON EL NUMERO DE EMPLEADO\n";
                    }
                } else {
                    echo "EL NUMERO DE EMPLEADO INGRESADO NO COINCIDE CON NINGUN RESPONSABLE\n";
                }
            }
            break;
        case 11:
            // eliminacion de un viaje
            $objViaje = new Viaje();
            $coleccionViajes = $objViaje->listar();
            if ($coleccionViajes == null){
                echo "NO EXISTEN VIAJES PARA ELIMINAR\n";
            } else {
                for($i=0; $i < count($coleccionViajes); $i++){
                    $viajeAMostrar = $coleccionViajes[$i];
                    echo $viajeAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el id del viaje que quiera eliminar: ";
                $idViajeAEliminar = trim(fgets(STDIN));
                $i = 0;
                $idViajeEncontrado = false;
                while($i < count($coleccionViajes) && (!$idViajeEncontrado)){
                    if ($coleccionViajes[$i]->getIdviaje() == $idViajeAEliminar){
                        $idViajeEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if ($idViajeEncontrado){
                    $pasajerosConViajeSeleccionado = 0;
                    $objPasajero = new Pasajero();
                    $coleccionPasajeros = $objPasajero->listar();
                    for($i=0; $i < count($coleccionPasajeros); $i++){
                        if ($coleccionPasajeros[$i]->getIdViaje() == $idViajeAEliminar){
                            $pasajerosConViajeSeleccionado = $pasajerosConViajeSeleccionado+1;
                        }
                    }
                    if ($pasajerosConViajeSeleccionado == 0){
                        $objViaje->Buscar($idViajeAEliminar);
                        $respuesta = $objViaje->eliminar();
                        if($respuesta){
                            echo "SE HA ELIMINADO EL VIAJE CORRECTAMENTE\n";
                        } else {
                            echo "NO SE HA PODIDO ELIMINAR EL VIAJE\n";
                        }
                    } else {
                        echo "NO SE HA PODIDO ELIMINAR EL VIAJE DEBIDO A QUE EXISTEN PASAJEROS CON EL ID DEL VIAJE\n";
                    }
                } else {
                    echo "EL ID INGRESADO NO COINCIDE CON NINGUN VIAJE DE LA BASE DE DATOS\n";
                }
            }
            break;
        case 12:
            // eliminar un pasajero
            $objPasajero = new Pasajero();
            $coleccionPasajeros =  $objPasajero->listar();
            if ($coleccionPasajeros == null){
                echo "NO EXISTEN PASAJEROS PARA ELIMINAR\n";
            } else {
                for($i=0; $i < count($coleccionPasajeros); $i++){
                    $pasajeroAMostrar = $coleccionPasajeros[$i];
                    echo $pasajeroAMostrar;
                    echo "-----------------------------------------\n";
                }
                echo "Elige el numero de documento de alguno de los pasajeros listados que quiera eliminar: ";
                $numDocumentoPasajeroAEliminar = trim(fgets(STDIN));
                $i = 0;
                $pasajeroEncontrado = false;
                while($i < count($coleccionPasajeros) && (!$pasajeroEncontrado)){
                    if($coleccionPasajeros[$i]->getPdocumento() == $numDocumentoPasajeroAEliminar){
                        $pasajeroEncontrado = true;
                    } else {
                        $i++;
                    }
                }
                if($pasajeroEncontrado){
                    $objPasajero->Buscar($numDocumentoPasajeroAEliminar);
                    $respuesta = $objPasajero->eliminar();
                    if($respuesta){
                        echo "SE HA ELIMINADO EL PASAJERO CORRECTAMENTE!\n";
                    } else {
                        echo "NO SE HA PODIDO ELIMINAR EL PASAJERO\n";
                    }
                } else {
                    echo "EL NUMERO DE DOCUMENTO INGRESADO NO COINCIDE CON NINGUN PASAJERO\n";
                }
            }
            break;
        case 13:
            // mostrar las empresas
            $objEmpresa = new Empresa();
            $coleccionEmpresas = $objEmpresa->listar();
            if ($coleccionEmpresas == null){
                echo "NO EXISTEN EMPRESAS PARA MOSTRAR\n";
            } else {
                for($i=0; $i < count($coleccionEmpresas); $i++){
                    $empresaAMostrar = $coleccionEmpresas[$i];
                    echo $empresaAMostrar;
                    echo "-----------------------------------------\n";
                }
            }
            echo "FIN DE LA VISUALIZACION DE EMPRESAS!\n";
            break;
        case 14:
            // mostrar los responsables
            $objResponsable = new Responsable();
            $coleccionResponsables = $objResponsable->listar();
            if ($coleccionResponsables == null){
                echo "NO EXISTEN RESPONSABLES PARA MOSTRAR\n";
            } else {
                for($i=0; $i < count($coleccionResponsables); $i++){
                    $responsableAMostrar = $coleccionResponsables[$i];
                    echo $responsableAMostrar;
                    echo "-----------------------------------------\n";
                }
            }
            echo "FIN DE LA VISUALIZACION DE RESPONSABLES!\n";
            break;
        case 15:
            // mostrar los viajes
            $objViaje = new Viaje();
            $coleccionViajes = $objViaje->listar();
            if($coleccionViajes == null){
                echo "NO EXISTEN VIAJES PARA MOSTRAR\n";
            } else {
                for($i=0; $i < count($coleccionViajes); $i++){
                    $viajeAMostrar = $coleccionViajes[$i];
                    echo $viajeAMostrar;
                    echo "===========================================\n";
                }
            }
            echo "FIN DE LA VISUALIZACION DE VIAJES!\n";
            break;
        case 16:
            // mostrar pasajeros
            $objPasajero = new Pasajero();
            $coleccionPasajeros = $objPasajero->listar();
            if($coleccionPasajeros == null){
                echo "NO EXISTEN PASAJEROS PARA MOSTRAR\n";
            } else {
                for($i=0; $i < count($coleccionPasajeros); $i++){
                    $pasajeroAMostrar = $coleccionPasajeros[$i];
                    echo $pasajeroAMostrar;
                    echo "-----------------------------------------\n";
                }
            }
            echo "FIN DE LA VISUALIZACION DE PASAJEROS!\n";
            break;
        case 17:
            echo "-----------------------------------\n";
            echo "------------FIN DEL MENU-----------\n";
            echo "-----------------------------------\n";
            break;
    }

} while ($opcion != 17);