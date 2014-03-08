<?php if(!defined('STDIN')) die('ONLY EXECUTE COMMAND-LINE-INTERFACE');

/** IBOT SOCKET PHP5
 *  @designer: Alexander171294
 *  @Proyect Name: PHPIBot
 *  @contact: Alexander171294@gmail.com
 *  @Status: Prototype
 *  @Date: 05/11/13  
 *  @info: proyecto desarrollado por el equipo UnderC0de++ 
 */           
 
 // definimos saltos de linea
 define('NL', "\r\n");
 define('DS', "\\"); // directory separator
 
 // FUNCIONES EXTENSIBLES
/*
 * Si es necesario cambiar la salida, solo modifcamos esta función.
*/
// standard out
function std_out($string)
{
  echo utf8_decode(utf8_encode($string));
}
// standard includes
function std_inc($file)
{
  require(dirname(__FILE__).'/'.$file);
}

 
 // datos de la conexión
 $server = 'irc.freenode.net';
 $port = 6667;

  // incluimos el property
  std_inc('property.php');
  // incluimos el control de sockets
  std_inc('sockets/interface.php');
  std_inc('sockets/sockets.php');
  // incluimos el control del irc
  std_inc('irc/irc_data.php'); // canal y datos del usuario a conectarse
  std_inc('irc/interface.php');
  std_inc('irc/ircprotocol.php');
  // incluimos los archivos principales del bot
  // funciones que puede realizar el bot
  std_inc('ibot/ibot_commands.php');
  // lista de funciones con detalles y lista de admins por defecto
  std_inc('ibot/ibot_data.php');
  std_inc('ibot/interface.php');
  std_inc('ibot/ibot.php');
  // manejo de componentes
  // librería standard de componentes
  std_inc('COM/comlibInterface.php');
  std_inc('COM/comlib.php');
  // clase gestora de componentes válidos
  std_inc('COM/ComLoaderInterface.php');
  std_inc('COM/ComLoader.php');

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

// creación de objetos y llamada a función principal
// instanciamos la clase
$bot = new ibot();
// iniciamos el hilo paralelo
#$prueba->start();
// iniciamos el proceso principal
$bot->main($server, $port);