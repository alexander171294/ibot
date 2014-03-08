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

// clase de socket
Class XSocketPlus implements IXSocketPlus
{
  use Property; // requerimos el property
  
  // socket
  private $socket = null;
  // maximo de buffer que se recive
  private $maxBufferSize = 2048;
  
  // metodo constructor
  final public function __construct($bufferLength = 2048, $socket = null) 
  {
    $this->maxBufferSize = $bufferLength;
    if(empty($socket))
    {
      $this->create_socket();
    } else { $this->socket = $socket; }
  }
  
  // no debe ser utilizado aún extendiendose
  final private function create_socket()
  {
    $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if($this->socket === false)
      $this->on_error('XSocketPlus::Create Socket Failed!');
    $result = socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
    if(!$result)
      $this->on_error('XSocketPlus::OPTION SOCKET FAILED!');
  }
  
  // seteamos los valores de bindeado de address y puerto
  final public function bind($addr, $port)
  {
    $result = socket_bind($this->socket, $addr, $port);
    if(!$result)
      $this->on_error('XSocketPlus::BIND SOCKET FAILED!');
  }
  
  // poner a la escucha el socket
  final public function listen($i = 20)
  {
    $result = socket_listen($this->socket,$i);
    if(!$result)
      $this->on_error('XSocketPlus::LISTEN SOCKET FAILED!');
  }
  
  // enviar mensaje
  final public function send($message)
  {
    $result = socket_write($this->socket,$message,strlen($message));
    if(!$result)
     $this->on_error('XSocketPlus::SOCKET SEND(); FAILED!');
  }
  
  // conectar a una dirección con un puerto
  final public function connect($address, $port)
  {
    $result = socket_connect($this->socket, $address, $port);
    if(!$result)
      $this->on_error('XSocketPlus::SOCKET connect(); FAILED!');
    else
      $this->on_connect();
  }
  
  // esperar la ocurrencia de un evento.
  final public function wait_event()
  {
    $var = true;
    while($var)
    {
      $read = array($this->socket);
      $write = $except = null;
      socket_select($read, $write, $except, null);
      $buffer = null;
      $len_buffer = socket_recv($this->socket, $buffer, $this->maxBufferSize, 0);
      if($len_buffer == 0) 
        $this->on_disconnect();
      else
        $this->on_getdata(utf8_encode($buffer), $len_buffer);
    }
  }
  
  // esperar un evento especial y devolver la llegada del proximo valor
  final public function wait_special_event()
  {
      $read = array($this->socket);
      $write = $except = null;
      socket_select($read, $write, $except, null);
      $buffer = null;
      $len_buffer = socket_recv($this->socket, $buffer, $this->maxBufferSize, 0);
      if($len_buffer == 0) 
        $this->on_disconnect();
      else
        return $buffer;
  }
  
  // si la clase es destruida, cerrar conexión
  final public function __destruct()
  {
    @socket_close($this->socket);
    $this->on_disconnect();
  }
  
  // getters y setters
  final public function get_socket() { return $this->socket; }
  final public function set_socket($value) { ; }


  //         FUNCIONES REESCRIBIBLES                         //
  protected function on_error($error_message) { ; }
  protected function on_getdata($message, $len_message) { ; }
  protected function on_connect() { ; }
  protected function on_disconnect() { ; }
  /////////////////////////////////////////////////////////////
}