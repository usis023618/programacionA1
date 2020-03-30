<?php
include('../../Config/Config.php');
$docente = new docente($Conexion);

$proceso ='';
if( isset($_GET['proceso']) && strlen($_GET['proceso'])>0 ){
 $proceso =$_GET['proceso'];   
}
$docente->$proceso($_GET['docente'])
print_r(json_encode($docente->respuesta));

class docente{
    private $datos= array(), $db;
    public $respuesta = ['msg'=>'correcto'];

    public function recibirDatos($docente){
        $this->datos = json_decode($docente, true);
        $this->validar_datos();
    }
    private funtion validar_datos(){
        if( empty($this->datos['codigo']) ){
            $this->respuesta['msg'] = 'Por favor ingrese el codigo del docente';
        }
        if( empty($this->datos['nombre']) ){
            $this->respuesta['msg'] = 'Por favor ingrese el nombre del docente';
        }
        if( empty($this->datos['nit']) ){
            $this->respuesta['msg'] = 'Por favor ingrese el NIT del docente';
        }
        if( empty($this->datos['direccion']) ){
            $this->respuesta['msg'] = 'Por favor ingrese la direccion del docente';
        }
        if( empty($this->datos['telefono']) ){
            $this->respuesta['msg'] = 'Por favor ingrese el telefono del docente'
        }
        $this->almacenar_docente();
    }
    private function almacenar_docente(){
        if( $this->respuesta['msg']==='correcto'){
            if($this->datos['accion']==='nuevo'){
                $this->db->consultas('
                INSERT INTO docentes (codigo, nombre, direccion, telefono) VALUES(
                    "'. $this->datos['codigo'] .'",
                    "'. $this->datos['nombre'] .'",
                    "'. $this->datos['nit'] .'",
                    "'. $this->datos['direccion'] .'",
                    "'. $this->datos['telefono'] .'"
                    )
                ');
                $this->respuesta['msg'] = 'Registro insertado correctamente';
            }else if($this->datos['accion']==='modificar'){
                $this->db->consultas('
                UPDATE docentes SET
                codigo    = "'. $this->datos['codigo'] .'",
                nombre    = "'. $this->datos['nombre'] .'",
                nit       = "'. $this->datos['nit'] .'",
                direccion = "'. $this->datos['direccion'] .'",
                telefono  = "'. $this->datos['telefono'] .'"
                WHERE idDocente = "'. $this->datos['idDocente'] .'"
                ');
                $this->respuesta['mgs'] = 'Registro actualizado correctamente';
            }
        }
    }

    public function buscarDocente($valor=''){
        $this->db->consultas('
         SELECT docentes.idDocente, docentes.codigo, docentes.nombre, docentes.nit, docentes.direccion, docentes.telefono
         FROM docentes
         WHERE docentes.codigo like "%'.$valor.'%" or docentes.nombre like "%' .$valor. '%" or docentes.nit like "%' .$valor. '%"
        ');
        return $this->respuesta = $this->db->obtener_datos();
    }
    public function eliminarDocente($idDocente=''){
        $this->db->consultas('
        delete docentes
        FROM docentes
        WHERE docentes.idDocente = "'.$idDocente.'"
        ');
        $this->respuesta['msg'] = 'Registro eliminado correctamente';
    }
}
?>