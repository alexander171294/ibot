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

// listado de comandos con su descripción
trait ibotDATA
{
  // definimos los comandos
    private $commands = array('chau' => 'obligar al bot a salir',
                              'ping' => 'el bot saluda',
                              'info' => 'información sobre el bot',
                              'help' => 'ayuda del bot, opcional comando a preguntar funcion',
                              'join' => 'unir el bot a otro canal',
                              'echo' => 'hacer que el bot repita lo que dices, primero el canal y luego el texto',
                              'nick' => 'hacer que el bot cambie su nick',
                              'me' => 'informa sobre la disposició de ibot con respecto a tu usuario',
                              'add_adm' => 'dar privilegio de administrador a usuario',
                              'del_adm' => 'quitar privilegio de administrador a usuario',
                              'history' => 'guardar historial de conversaciones on/off/get',
                              'insultar' => 'insultar a usuario especificado',
                              'adorar' => 'adorar a usuario especificado',
                              'game' => 'cambiar estado del juego on/of',
                              'responder' => 'responder pregunta (si el modo juego está activado)',
                              'siguiente' => 'siguiente pregunta (si el modo juego está activado)',
                              'repetir' => 'repetir pregunta (si el modo juego está activado)',
                              'puntajes' => 'listado de puntajes (si el modo juego está activado)',
                              'puntaje' => 'mis puntos (si el modo juego está activado)',
                              'reset' => 'resetear puntos (si el modo juego está activado)',
                              'leave' => 'salir de un canal',
                              'credenciales' => 'obtiene el nick interno del bot',
                              'voice' => 'add/del usuario #canal',
                              'op' => 'add/del usuario #canal',
                              'kick' => 'usuario #canal',
                              'local' => 'Realizar acción sobre pc que ejecuta el bot <shutdown/note>',
                              'explorer' => 'Explorar archivos del pc',
                              'encriptar' => '<md2/md4/md5/sha1/sha224/sha256/sha384/sha512/ripemd128/ripemd160/ripemd256/ripemd320/whirlpool/tiger128,3/tiger160,3/tiger192,3/tiger128,4/tiger160,4/tiger192,4/snefru/snefru256/gost/adler32/crc32/crc32b/salsa10/salsa20/haval128,3/haval160,3/haval192,3/haval224,3/haval256,3/haval128,4/haval160,4/haval192,4/haval224,4/haval256,4/haval128,5/haval160,5/haval192,5/haval224,5/haval256,5/base64> texto',
                              'desencriptar' => '<base64> texto',
                              'date' => 'obtener fecha y hora de argentina',
                              );
    private $admins = array(
                            'Alex171294' => 0
                            );
}