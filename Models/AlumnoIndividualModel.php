<?php
use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;

class AlumnoIndividualModel{
    private $mysqli;


    public function insertarAlumnoIndividual($matricula, $nombre, $apellidoP, $apellidoM, $telefono, $correo, $carrera, $proceso)
    {

        // Generar la contraseña a partir de las iniciales del nombre y los últimos dígitos de la matrícula
        $inicialesNombre = substr($nombre, 0, 2); // Tomar las primeras 2 letras del nombre
        $ultimosDigitosMatricula = substr($matricula, -4); // Tomar los últimos 2 dígitos de la matrícula

        $password = $inicialesNombre . $ultimosDigitosMatricula;

        // Encriptar la contraseña usando MD5
        $passwordHash = md5($password);

        // IdRol del estudiante
        $idRol = 4;

        //valida que la matricula no se encuentre para poder realizar un nuevo registro
        $check_sql = "SELECT Matricula FROM alumnos WHERE Matricula = '$matricula'";
        $check_result = $this->mysqli->query($check_sql);

        if ($check_result && $check_result->num_rows > 0) {
            // Registro duplicado encontrado
            return false;
        } else {
            // Realizar la inserción en la tabla de usuarios
            $usuarioSql = "INSERT INTO usuarios (IdUsuario, CorreoE, Contraseña, IdRol, NombreU, APaternoU, AMaternoU) 
            VALUES ('$matricula', '$correo', '$passwordHash', '$idRol', '$nombre', '$apellidoP', '$apellidoM')";

            if ($this->mysqli->query($usuarioSql)) {
                // Realizar la inserción en la tabla de alumnos
                $alumnoSql = "INSERT INTO alumnos (Matricula, NombreA, ApellidoP, ApellidoM, Telefono, CorreoE, Carrera, idProceso) 
                    VALUES ('$matricula', '$nombre', '$apellidoP', '$apellidoM', '$telefono', '$correo', $carrera, $proceso)";

                if ($this->mysqli->query($alumnoSql)) {
                    // Envía un correo al alumno
                    $asunto = "Información de cuenta";
                    $mensaje = "Hola $nombre,\n\nTu cuenta ha sido creada con éxito.\n\nTu Usuario es: $correo\nTu Contraseña es: $password\n";
                    require "config/EmailSender.php";
                    // Asume que tienes un método para enviar correos en la clase EmailSender
                    /*$emailSender = new EmailSender();
                    if ($emailSender->enviarCorreoUsuario($correo, $asunto, $mensaje)) {
                        // Éxito al enviar el correo
                        return true;
                    } else {
                        // Error al enviar el correo
                        return false;
                    }*/
                } else {
                    // Manejo de errores si la inserción en la tabla de alumnos falla
                    return false;
                }
            } else {
                // Manejo de errores si la inserción en la tabla de usuarios falla
                return false;
            }
        }
    }
}
?>