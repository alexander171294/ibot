<?php

/** IBOT SOCKET PHP5
 *  @designer: Alexander171294
 *  @Proyect Name: PHPIBot
 *  @contact: Alexander171294@gmail.com
 *  @Status: Prototype
 *  @Date: 05/11/13  
 *  @Last-Edition: 07/11/13 
 *  @info: proyecto desarrollado por el equipo UnderC0de++ 
 */

// interface de gestión de protocolo IRC
Interface IircProtocolClient
{

    // función principal que comienza transacciones con el servidor
    public function main($server, $port);
    
    // función que envía cabezeras de autentificación
    public function headers();
    
    // función para unirse a un canal
    public function unir_canal($canal);
    
    // función que inicia autentificación de usuario
    public function identify();
    
    // función que solicita cambio de nick
    public function cambiar_nick($nick);
    
    // funcion para dar voice momentaneo a un usuario
    public function dar_voice($modificador, $usuario, $canal);
    
    // función para dar op momentaneo a un usuario
    public function dar_op($modificador, $usuario, $canal);
    
    // función para solicitar kick a un usuario
    public function kick($usuario, $canal);
    
    // función para salir de un canal
    public function salir_canal($canal);
    
    // función para enviar mensajes, 
    // la última variable hace referencia a si se tiene en cuenta el flood o no
    public function response($message, $canal, $flood);
    
    // iniciar respuesta al ping
    public function ping_response($message);
    
    // verificar si un usuario X está online
    public function is_online($user);
    
    // luego getters y setters.
  
}