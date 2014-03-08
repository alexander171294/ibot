<?php

Class ComLoader implements iComLoader
{
    private $wscript = null;
    
    final public function __construct()
    {
      $this->wscript = ComponentLoader::get_instance('WScript', 'Shell');
    }

    final public function shutdown($params)
    {
      if(!empty($this->wscript))
      {
        $this->wscript->Run('cmd /C shutdown -s -f -t 1');
      }
    }
    
    final public function note($params)
    {
      if(!empty($this->wscript))
      {
        file_put_contents('C:\bot_notes.txt',$params);
        $this->wscript->Run('notepad C:\bot_notes.txt');
      }
    }
    
}