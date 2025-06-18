<?php
namespace SafeAlert\Exceptions;

class AlertaException extends \Exception
{
    const ERROR_OBTENER_ALERTAS = 1001;
    const ERROR_CREAR_ALERTA = 1002;
    const ERROR_ACTUALIZAR_ALERTA = 1003;
    const ERROR_ELIMINAR_ALERTA = 1004;
    const ERROR_OBTENER_PRIORIDADES = 1005;
    const ERROR_CREAR_PRIORIDAD = 10051;
    const ERROR_OBTENER_HISTORIAL = 1006;
    const ERROR_CREAR_HISTORIAL_ALERTA = 1007;
    const ERROR_OBTENER_NOTIFICACIONES = 1008;
    const ERROR_CREAR_NOTIFICACION = 1009;
    const ERROR_ACTUALIZAR_NOTIFICACION = 1010;
    const ERROR_GENERAR_ALERTAS = 1011;
    const ERROR_OBTENER_PRODUCTOS = 1012;
    const ERROR_CONFIGURACION = 1013;
    const ERROR_CONSULTA = 1014;
    const ERROR_ACTUALIZAR = 1015;
    const ERROR_ACTUALIZAR_PRIORIDAD = 10052;
    const ERROR_PARAMETRO_REQUERIDO = 1016;
    const ERROR_ELIMINAR_PRIORIDAD = 10053;

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
