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

// detalles del usuario y del canal 
trait IRCDATA
{
    // canal actual
    private $current = '#uc++'; // canal por defecto que conectarse al principio
    // nick
    private $nick = 'The_Agent-Smith'; // nombre por defecto
    // password del user si es que tiene
    private $upass = 'alex1234';
}