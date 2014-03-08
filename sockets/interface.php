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

// interface de sockets
Interface IXSocketPlus
{
    // bindear conexi�n
    public function bind($addr, $port); // bindear conexi�n
    // poner a la escucha
    public function listen($i); // escuchar puerto bindeado
    // enviar mensaje
    public function send($message); // enviar mensaje
    // realizar conexi�n a servidor
    public function connect($address, $port); // conectarse a otro pc
    // esperar recepci�n de mensaje, error, o evento.
    public function wait_event(); // esperar que ocurra evento
    // esperar recepci�n de mensaje y devolver el mensaje sin llamar a on_get()
    public function wait_special_event();
}