<?php

class ComponentLoader implements iComponentLoader
{
    static public $instances = array();  
    static public $error = false;
    static public $emessage = null;
       
    static public function get_instance($component, $id)
    {
      if(!class_exists('COM')) trigger_error('Debe configurar el archivo php.ini correctamente',E_USER_ERROR);
      else
      {
        self::$instances[$component] = new COM($component.'.'.$id);
        if(!self::$instances[$component])
        {
          self::$error = true;
          self::$emessage = 'Unable to instantiate Component '.$component;
          return false;
        } else { return self::$instances[$component]; }
      }
      return null;
    }
       
    static public function get_old_instance($component)
    {
      return self::$instances[$component];
    }
}
     
