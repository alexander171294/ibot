<?php


// clase del bot
class ibot extends  ircProtocolClient implements Iibot
{
  use ibotDATA, ibotCOMMANDS;

    // online?
    private $online = false;
    // cantidad de transacciones
    private $transactions = 0;
    
    private $version = '1.4.2';
    private $flooders = array();
    
    // pregunta actual
    private $current_ask = 0;
    // proxima vez a tirar pista
    private $next_pista = 0;
    // proxima vez a actualizar pregunta
    private $next_ask = 0;
    // cantidad de pistas dadas
    private $pistas = 0;
    // ask game on?
    private $asking = false;
    // identify step
    private $step = 0;
    
    // puntajes de las preguntas
    private $tabla_puntos = array();
    private $preguntas = array();
    private $respuestas = array();
    private $array_pistas = array();
    
    // Component Manager
    private $com = null;
    
    // history
    private $history = false;
    private $history_dump = null;
    

    // CUANDO OCURRE ERROR
    protected function on_error($error_message)
    {
      std_out('>> ocurrió un error'.NL);
      std_out($error_message.NL);
    }
    
    // AL CONECTARSE
    protected function on_connect()
    {
      $this->headers();
      std_out('** esperando eventos...'.NL);
      $this->wait_event();
    }
    
    // AL DESCONECTARSE
    protected function on_disconnect()
    {
      std_out('>> desconectado <<'.NL);
      die();
    }
    
    // AL obtener un dato
    protected function on_getdata($message, $len_message)
    {
      #std_out($message);
      $this->control_message($message);
      $this->transactions++;
    }
    
    ///////////////////////////////////////////
    // empezamos el control de mensajes acá //
    /////////////////////////////////////////
    
    private function is_admin($user)
    {
      // solicitamos info del user
      if($this->is_online($user) == true) // si esta online ahora (y obviamente registrado)
        return isset($this->admins[$user]); // si es admin
      else
        return false;
    }
    
    public function control_message($message)
    {
      // si aún no estamos totalmente conectados al IRC
      if(!$this->online)
      {
        $spliteado = explode(NL,$message);
        $max = count($spliteado)-2; // obtenemos el ultimo mensaje
        // verificamos si ya terminó el connect
        //echo $spliteado[$max];
        if($spliteado[$max] == ':'.$this->nick.' MODE '.$this->nick.' :+i')
        {
          std_out('** CONECCIÓN ACEPTADA SATISFACTORIAMENTE ;)'.NL);
          $this->online = true;
          // activamos el antiflood
          $this->min_flood = 1; // MINIMO PARA EL FLOOD
          // nos unimos al canal por defecto
          $this->unir_canal($this->current); // notese que current podría llegar a cambiar
        }
        elseif(strpos($message, 'NickServ')!==false) // si nos solicita identificarnos
        {
          if($this->step == 0)
          {
            $this->step++;
            std_out('<< solicitud de identificación'.NL);
            $this->identify();
          } else {
            std_out('** CONECCIÓN ACEPTADA SATISFACTORIAMENTE ;)'.NL);
            $this->online = true;
            // activamos el antiflood
            $this->min_flood = 1; // MINIMO PARA EL FLOOD
            // nos unimos al canal por defecto
            $this->unir_canal($this->current); // notese que current podría llegar a cambiar
          }
        }
      }
      else
      {
        //ya estamos online
        $contexto = $this->message_parser($message);
        $this->revisar_comandos($contexto);
      }
    }
    
    private function revisar_comandos($contexto)
    {
      if($contexto['tipo'] == 'PRIVMSG') // si es un mensaje
      {
        if($this->history)
        {
          @$this->history_dump[$contexto['canal']] .= $contexto['name'].': '.$contexto['real_message'].NL;
        }
        // separamos espacios
        $commands = explode(' ', $contexto['real_message']);
        $commands[1] = isset($commands[1]) ? strtolower($commands[1]) : null;
        if($commands[0]=='!ibot' || $commands[0]=='!'.$this->nick)  // incorporamos doblecomando
        {
            std_out('>> ORDEN:: '.$commands[1].NL);
            if(isset($this->commands[$commands[1]]))
              call_user_func(array($this,'command_'.$commands[1]), $contexto, $commands);
            else
              $this->response('@'.$contexto['name'].' lo que me pides no está entre mis funciones, usa !ibot help', $contexto['canal']);
        }
      }
      elseif($contexto['tipo'] == 'PING')
      {
        std_out('>> PING'.NL);
        $this->ping_response($contexto['original']);
      }
      elseif($contexto['tipo'] == 'KICK')
      {
        std_out('>> KICK'.NL);
        // nos unimos de nuevo al canal donde nos hecharon JAJAJAJA
        $this->unir_canal($contexto['canal']);
      }
      elseif($contexto['tipo'] == 'JOIN')
        std_out('>> JOINED TO CHANNEL!'.NL);
      // lo del juego
      if($this->asking) $this->ask_game();
    }
    
    private function component_executor($function, $ext = null)
    {
      std_out('** USE COMPONENT **'.NL);
      if(empty($this->com)) $this->com = new ComLoader();
      call_user_func(array($this->com, $function), $ext);
    }
    
    private function pastebin($data, $name)
    {
          $api_dev_key = 'dfc2e0418ee5e9add6e4679f3c650b5e'; // your api_developer_key 
          $api_paste_code = utf8_encode($data); // your paste text 
          $api_paste_private = '1'; // 0=public 1=unlisted 2=private 
          $api_paste_name = $name; // name or title of your paste 
          $api_paste_expire_date = '10M'; 
          $api_paste_format = 'text'; 
          $api_user_key = ''; // if an invalid api_user_key or no key is used, the paste will be create as a guest 
          $api_paste_name = urlencode($api_paste_name); 
          $api_paste_code = urlencode($api_paste_code); 
          $url = 'http://pastebin.com/api/api_post.php';
          $ch = curl_init($url); 
          curl_setopt($ch, CURLOPT_POST, true); 
          curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=paste&api_user_key='.$api_user_key.'&api_paste_private='.$api_paste_private.'&api_paste_name='.$api_paste_name.'&api_paste_expire_date='.$api_paste_expire_date.'&api_paste_format='.$api_paste_format.'&api_dev_key='.$api_dev_key.'&api_paste_code='.$api_paste_code.''); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
          curl_setopt($ch, CURLOPT_VERBOSE, 1); 
          curl_setopt($ch, CURLOPT_NOBODY, 0); 
          $response = curl_exec($ch); 
          unset($ch);
          return $response;
    }
}