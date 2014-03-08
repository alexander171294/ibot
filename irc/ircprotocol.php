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

// clase de gestión de protocolo IRC
Class ircProtocolClient extends XSocketPlus implements IircProtocolClient
{
  use IRCDATA; // usamos el trait IRCDATA
  
    // canales en los que estoy
    private $canales = array();
    // minimo para el flood
    private $min_flood = -1; // minimo de segundos posibles para no conciderarse flood entre mensajes
    // cuando es posible enviar otro mensaje
    private $next_message = 0;

    // función principal
    final public function main($server, $port)
    {
      std_out('######################################'.NL);
      std_out('##        UnderC0de++ Team          ##'.NL);
      std_out('##  444  444     44   44444         ##'.NL);
      std_out('##   4   4  4   4  4    4           ##'.NL);
      std_out('##   4   444    4  4    4    v1.4   ##'.NL);
      std_out('##   4   4  4   4  4    4           ##'.NL);
      std_out('##  444  444     44     4           ##'.NL);
      std_out('######################################'.NL);
      std_out('** iniciamos...'.NL);
      $this->connect($server, $port);
    }
    
    // función no reescribible que envía un comando al irc
    final private function msg($comando, $parametros, $antiflood = true)
    {
      if(time()>$this->next_message || $this->min_flood == -1 || !$antiflood)
      {
        $this->next_message = $antiflood ? time()+$this->min_flood : time();
        $this->send($comando.' '.$parametros.NL);
        std_out('<< COMMAND OUT:: '.$comando.NL);
      } else { std_out('** FLOOD detection '.NL); }
    }
    
    // envio de cabeceras
    final public function headers()
    {
      std_out('** conectado correctamente'.NL);
      std_out('<< Enviando cabeceras...'.NL);
      $this->cambiar_nick($this->nick);
      $this->msg('USER','thex experimental irc.freenode.net :John Doe');
    }
    
    // función que inicia identificación
    final public function identify()
    {
      $this->msg('PRIVMSG','NickServ :Identify '.$this->upass);
    }
    
    // funcion para verificar si esta logueado y registrado un usuario
    final public function is_online($user)
    {
      $this->msg('PRIVMSG','NickServ :Info '.$user, false);
      $resultado = $this->wait_special_event(); // esperamos la respuesta
      std_out('** WAIT-STOP'.NL);
      if(strpos($resultado,'is not registered.')===false)
      {
          $resultado = $this->wait_special_event(); // fecha de registro
          std_out('** WAIT-STOP'.NL);
          if(strpos($resultado,': now')!==false)
            return true;
          else
            return false;
      }
      else
        return false;
    }
    
    // función para cambiar nick
    final public function cambiar_nick($nick)
    {
      $this->msg('NICK',$nick, false);
      $this->nick = $nick;
    }
    
    // función para unirse a un canal
    final public function unir_canal($canal)
    {
      $this->msg('JOIN', $canal, false);
    }
    
    // funcion para dar voice momentaneo a un usuario
    final public function dar_voice($modificador, $usuario, $canal)
    {
      //$this->msg('PRIVMSG','ChanServ :FLAGS '.$canal.' '.$usuario.' '.$modificador.'V');
      $this->msg('MODE', $canal.' '.$modificador.'v '.$usuario, false);
    }
    
    // función para dar op momentaneo a un usuario
    final public function dar_op($modificador, $usuario, $canal)
    {
      $this->msg('MODE', $canal.' '.$modificador.'o '.$usuario, false);
    }
    
    // función para solicitar kick a un usuario
    final public function kick($usuario, $canal)
    {
      $this->msg('KICK', $canal.' '.$usuario);
    }
    
    final public function salir_canal($canal)
    {
      $this->msg('PART', $canal, false);
    }
    
    final public function response($message, $canal, $flood = true)
    {
      $this->msg('PRIVMSG', $canal.' :'.$message, $flood);
    }
    
    final public function quit()
    {
      $this->msg('QUIT', null, false); 
    }
    
    // parseamos mensajes, función reescribible
    protected function message_parser($message)
    {
      // obtener usuario
      $username = strpos($message, '!');
      $name = null;
      if($username!==false) // si ubicamos el fin del nombre
      {
        $name = substr ( $message , 1 , $username-1 ); //obtenemos el nombre :)
      }
      $tipo = null;
      // obtenemos el tipo de comando
      if(strpos($message, 'PRIVMSG') !== false) $tipo = 'PRIVMSG';
      elseif(strpos($message, 'PING') !== false) $tipo = 'PING';
      elseif(strpos($message, 'KICK') !== false) $tipo = 'KICK';
      elseif(strpos($message, 'JOIN') !== false) $tipo = 'JOIN';
      // obtener canal
      if($tipo != 'PRIVMSG')
        $canal = strpos($message, '#');
      else 
        $canal = strpos($message, 'PRIVMSG')+8;
      $canal_name = null;
      $canal_fin = 0;
      if($canal!==false)
      {
        // final canal
        $canal_fin = strpos($message, ':', $canal); $canal_fin--;
        $canal_name = substr ( $message , $canal , $canal_fin-$canal );
        $this->current = $canal_name;
      }
      // si el canal soy yo mismo, tengo que cambiar el canal por el nombre del user que me envia el mensaje
      if($canal_name == $this->nick) $canal_name = $name;
      
      $canal_fin += 2;
      $real_message = substr ($message, $canal_fin, (strlen($message)-$canal_fin)-2);
      
      return array('name' => $name, 'canal' => $canal_name, 'tipo' => $tipo, 'real_message' => $real_message, 'original' => $message);
    }
    
    // iniciar respuesta al ping
    final public function ping_response($message)
    {
      $msg = explode(':',$message);
      $this->msg('PONG',':'.$msg[1]); // ENVIAMOS PONG
    }
    
    // GETTERS Y SETTERS
    
    public function get_nick() { return $this->nick; }
    public function set_nick($value) { $this->nick = $value; }
    
    public function get_current() { return $this->current; }
    public function set_current($value) { $this->current = $value; }
    
    public function get_canales() { return $this->canales; }
    public function set_canales($value) { $this->canales = $value; }
    
    public function get_min_flood() { return $this->min_flood; }
    public function set_min_flood($value) { $this->min_flood = $value; }
    
    public function get_next_message() { return $this->next_message; }
    public function set_next_message($value) { $this->next_message = $value; }
}