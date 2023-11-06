<?php
class RegistroController{
    //Adjuntando los modelo s autilizar
    public function __construct() {
        require_once "Models/CarreraModel.php";
        require_once "Models/ProcesoModel.php";
        require_once "Models/RegistroModel.php";
    }

    public function index() {
        $carrera = new Carrera();
        $result = $carrera->get_carreras();

        $proceso = new Proceso();
        $result = $proceso->get_procesos();
        require_once "views/Escolares/carga.php";
    }

    public function index2() {
        $proceso = new Proceso();
        $result = $proceso->get_procesos();
        require_once "views/ptc/ptc.php";
    }

    public function estatus_editado() {
        if (isset($_GET['matricula'])) {
            $matricula = $_GET['matricula'];
            $alumnos = new registro();
            $alumnos->estatus_editado($matricula);
            require_once "views/Escolares/carga.php";
        }
    }

    public function estatus_documento() {
        if (isset($_GET['idDocumento'])) {
            $documento = $_GET['idDocumento'];
            $documento = new registro();
            $documento->estatus_documento($documento);
            require_once "views/ptc/ptc.php";
        }
    }
}
?>