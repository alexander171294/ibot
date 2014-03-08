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

// interface de gesti�n de protocolo IRC
Interface IircProtocolClient
{

    // funci�n principal que comienza transacciones con el servidor
    public function main($server, $port);
    
    // funci�n que env�a cabezeras de autentificaci�n
    public function headers();
    
    // funci�n para unirse a un canal
    public function unir_canal($canal);
    
    // funci�n que inicia autentificaci�n de usuario
    public function identify();
    
    // funci�n que solicita cambio de nick
    public function cambiar_nick($nick);
    
    // funcion para dar voice momentaneo a un usuario
    public function dar_voice($modificador, $usuario, $canal);
    
    // funci�n para dar op momentaneo a un usuario
    public function dar_op($modificador, $usuario, $canal);
    
    // funci�n para solicitar kick a un usuario
    public function kick($usuario, $canal);
    
    // funci�n para salir de un canal
    public function salir_canal($canal);
    
    // funci�n para enviar mensajes, 
    // la �ltima variable hace referencia a si se tiene en cuenta el flood o no
    public function response($message, $canal, $flood);
    
    // iniciar respuesta al ping
    public function ping_response($message);
    
    // verificar si un usuario X est� online
    public function is_online($user);
    
    // luego getters y setters.
  
}