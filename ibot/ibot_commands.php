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
 
 /*
    Este trait solo debe contener las funciones propias o comandos del bot, no
    debe definir ningú n otro tipo
 */

// funciones ejecutadas por los comandos.
trait ibotCOMMANDS
{
    private function command_chau($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
      {
        $this->response('Hasta luego.', $contexto['canal'], false);
        $this->quit();
        die();
      } else { $this->response($contexto['name'].' no tengo ganas', $contexto['canal']); }
    }
    
    private function command_ping($contexto, $comandos)
    {
      $this->next_message = 0;
      $this->response($contexto['name'].' PONG', $contexto['canal']);
    }
    
    private function command_info($contexto, $comandos)
    {
      $this->response('Ibot v'.$this->version.' programado en PHP por el Equipo UnderC0de++', $contexto['canal']);
    }
    
    private function command_help($contexto, $comandos)
    {
      // si se pide ayuda sobre un comando en especial
      if(isset($comandos[2]) && isset($this->commands[$comandos[2]]))
        $this->response(' !ibot '.$comandos[2].' '.$this->commands[$comandos[2]], $contexto['canal']);
      elseif(isset($comandos[2]) && !isset($this->commands[$comandos[2]]))
        $this->response('El comando del que solicitas informacion no existe', $contexto['canal']);
      else
      {
        $help = 'Comandos: ';
        foreach($this->commands AS $key => $value)
        {
          $help .= '"'.$key.'" ';
        }
        $this->response($help, $contexto['canal']);
      }
    }
    
    private function command_join($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
        {
          if(isset($comandos[2]) && !empty($comandos[2]))
            {
              $this->unir_canal($comandos[2]);
              $this->response('Hecho!', $contexto['canal'], false);
            }
          else
            $this->response('Tienes que especificar un canal', $contexto['canal']);
        } else $this->response('No tengo ganas', $contexto['canal']); 
    }
    
    private function command_leave($contexto, $comandos)
    {
          if(isset($comandos[2]) && !empty($comandos[2]))
            {
              $this->salir_canal($comandos[2]);
              $this->response('Hecho!', $contexto['canal'], false);
            }
          else
            $this->response('Debes especificar un canal', $contexto['canal']);
    }
    
    private function command_echo($contexto, $comandos)
    {
      if(isset($comandos[3]) && !empty($comandos[3]))
        {
          $canal = isset($comandos[2]) ? $comandos[2] : $contexto['canal'];
          $final_text = null;
          for($i = 3; $i<count($comandos); $i++)
            $final_text .= $comandos[$i].' ';
          $this->response(utf8_decode($final_text), $canal);
        }
      else
        $this->response('Tienes que especificar un texto', $contexto['canal']);
    }
    
    private function command_nick($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
      {
        if(isset($comandos[2]) && !empty($comandos[2]))
          {
            $nick = ($comandos[2] == 'me') ? $contexto['name'].'_' : $comandos[2]; 
            $this->cambiar_nick($nick);
            $this->response('Hecho!', $contexto['canal'], false);
          }
        else
          $this->response('Tienes que especificar un nuevo nick', $contexto['canal']);
      } else { $this->response($contexto['name'].' no tengo ganas', $contexto['canal']); }
    }
    
    private function command_me($contexto, $comandos)
    {
        if($this->is_admin($contexto['name']))
        {
            $this->response($contexto['name'].' eres mi dios, mi dueño, y yo soy tu esclavo', $contexto['canal']);
        }
        else
        {
            $this->response($contexto['name'].' a ti no te quiere ni tu mami', $contexto['canal']);
        }
    }
    
    private function command_add_adm($contexto, $comandos)
    {
        if($this->is_admin($contexto['name']))
        {
           if(isset($comandos[2]) && !empty($comandos[2]))
            {
              $this->admins[$comandos[2]] = 0;
              $this->response('Listo mi amo', $contexto['canal'], false);
            } else
          $this->response('Debes especificar el usuario', $contexto['canal']);
        } else { $this->response($contexto['name'].' no tengo ganas', $contexto['canal']); }
    }
    
    private function command_del_adm($contexto, $comandos)
    {
        if($this->is_admin($contexto['name']))
        {
           if(isset($comandos[2]) && !empty($comandos[2]) && $comandos[2]!='Alex171294')
            {
              unset($this->admins[$comandos[2]]);
              $this->response('Listo mi amo', $contexto['canal'], false);
            } else
          $this->response('Debes especificar el usuario', $contexto['canal']);
        } else { $this->response($contexto['name'].' no tengo ganas', $contexto['canal']); }
    }
    
    private function command_history($contexto, $comandos)
    {
      if($comandos[2]== 'on')
      {
        $this->history = true;
        $this->response('History Active', $contexto['canal']);
      }
      elseif($comandos[2]== 'off')
      {
        $this->history = false;
        $this->response('History Disabled', $contexto['canal']);
      }
      elseif($comandos[2]== 'get' && isset($this->history_dump[$comandos[3]]))
      {
        $result = $this->pastebin($this->history_dump[$comandos[3]],'History IRC - IBOT v'.$this->version);
        $this->response($result, $contexto['canal']);
      } else $this->response('No entiendo', $contexto['canal']);
    }
    

    private function command_insultar($contexto, $comandos)
    {
        $insultos = array(' forro', ' idiota', ' imbecil', ' estupido', ' pelotudo', ' cabezon', 'a puta');
           if(isset($comandos[2]) && !empty($comandos[2]))
            {
              $random = mt_rand(0,count($insultos)-1);
              $this->response($comandos[2].' eres un'.$insultos[$random], $contexto['canal']);
            } else
          $this->response('Debes especificar el usuario', $contexto['canal']);
    }
    
    private function command_adorar($contexto, $comandos)
    {
       if($this->is_admin($contexto['name']))
        {
          $insultos = array(' groso', ' genio', ' ídolo', ' dios', 'a eminencia!', 'a genialidad');
           if(isset($comandos[2]) && !empty($comandos[2]))
            {
              $random = mt_rand(0,count($insultos)-1);
              $this->response($comandos[2].' eres un'.$insultos[$random], $contexto['canal'], false);
            } else
          $this->response('Debes especificar el usuario', $contexto['canal'], false);
        } else { $this->response($contexto['name'].' no tengo ganas', $contexto['canal']); }
    }
    
    private function command_game($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
        {
          if(isset($comandos[2]) && !empty($comandos[2]) && ($comandos[2]=='on' || $comandos[2]=='off'))
            {
              $this->asking = ($comandos[2]=='on');
              if($this->asking)
              {
                $this->preguntas = json_decode(file_get_contents(__DIR__.'\preguntas.json')); 
                $this->respuestas = json_decode(file_get_contents(__DIR__.'\respuestas.json')); 
                //$this->array_pistas = get_object_vars(json_decode(file_get_contents(__DIR__.'\pistas.json'))); 
                $this->response($contexto['name'].' Juego activado', $contexto['canal'], false);
                $this->next_pista = time()+20;
                $this->ask_game();
                
              } else { $this->next_ask = 0; $this->response($contexto['name'].' Juego desactivado', $contexto['canal'], false); }
            } else $this->response('Debes especificar estado valido', $contexto['canal'], false);
        } else $this->response($contexto['name'].' No tengo ganas', $contexto['canal']);
    }
    
    private function ask_game()
    {
      // si ya pasó más de 2:30 desde la última pregunta
      if(time()>$this->next_ask)
      {
        $minutos = 2*60; //dos minutos
        $segundos = 30; // 30 segundos
        // proxima pregunta
        $this->next_ask = time()+$minutos+$segundos;
        // nueva pregunta
        // pregunta al azar
        $random = mt_rand(1,count($this->preguntas));
        $this->current_ask = $random;
        $this->pistas = 0;
        $this->response($this->preguntas[$this->current_ask], $this->current, false);
      }
      // TODO: implementar pistas...
    }
    
    private function command_responder($contexto, $comandos)
    {
      if($this->asking) // si el juego esta activo
      {
          $finishing = $comandos[2];
          for($i = 3; $i<count($comandos); $i++)
            $finishing .= ' '.$comandos[$i];
         // si la respuesta coincide
         if($finishing == $this->respuestas[$this->current_ask])
         {
            $this->response('Felicitaciones '.$contexto['name'].' respuesta correcta', $contexto['canal'], false);
            if(isset($this->tabla_puntos[$contexto['name']]))
              $this->tabla_puntos[$contexto['name']]++;
            else
              $this->tabla_puntos[$contexto['name']] = 1;
            $this->next_ask = 0;
            $this->next_pista = time() + 20;
            $this->pistas = 0;
            $this->ask_game();
         } else { $this->response('Lo siento '.$contexto['name'].' respuesta incorrecta', $contexto['canal']); }
      } else $this->response('El juego no está activo', $contexto['canal']);
    }
    
    private function command_siguiente($contexto, $comandos)
    {
      if($this->asking) // si el juego esta activo
      {
          $this->response('Respuesta anterior: '.$this->respuestas[$this->current_ask], $contexto['canal'], false);
          $this->next_ask = 0;
          $this->ask_game();
      } else $this->response('El juego no está activo', $contexto['canal']);
    }
    
    private function command_repetir($contexto, $comandos)
    {
      if($this->asking) // si el juego esta activo
      {
          $this->response($this->preguntas[$this->current_ask], $this->current, false);
      } else $this->response('El juego no está activo', $contexto['canal']);
    }
    
    private function command_puntajes($contexto, $comandos)
    {
      if($this->asking) // si el juego esta activo
      {
          $tabla = null;
          foreach($this->tabla_puntos AS $key => $value)
            $tabla .= $key.'('.$value.') ';
          $this->response($tabla, $this->current, false);
      } else $this->response('El juego no está activo', $contexto['canal']);
    }
    
    private function command_puntaje($contexto, $comandos)
    {
      if($this->asking) // si el juego esta activo
      {
          $tabla = 0;
          if(isset($this->tabla_puntos[$contexto['name']]))
            $tabla = $this->tabla_puntos[$contexto['name']];
          $this->response($contexto['name'].' tus puntos: '.$tabla, $this->current, false);
      } else $this->response('El juego no está activo', $contexto['canal']);
    }

    private function command_reset($contexto, $comandos)
    {
      if($this->asking) // si el juego esta activo
      {
        if($this->is_admin($contexto['name']))
        {
          $this->tabla_puntos = null;
          $this->response($contexto['name'].' la tabla fue reseteada', $this->current, false);
        } else $this->response($contexto['name'].'no tengo ganas!!', $contexto['canal']);
      } else $this->response('El juego no está activo', $contexto['canal']);
    }
    
    private function command_credenciales($contexto, $comandos)
    {
      $this->response($contexto['name'].' yo soy '.$this->nick, $this->current, false);
    }
    
    private function command_voice($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
      {
        if(isset($comandos[4]) && !empty($comandos[4]))
        {
          $modificador = ($comandos[2]=='add') ? '+' : '-';
          $this->dar_voice($modificador, $comandos[3], $comandos[4]);
        } else $this->response('Deben especificarse todos los parametros', $contexto['canal']);
      }
    }
    
    private function command_op($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
      {
        if(isset($comandos[4]) && !empty($comandos[4]))
        {
          $modificador = ($comandos[2]=='add') ? '+' : '-';
          $this->dar_op($modificador, $comandos[3], $comandos[4]);
        } else $this->response('Deben especificarse todos los parametros', $contexto['canal']);
      } else $this->response($contexto['name'].' No tengo ganas', $contexto['canal']);
    }
    
    private function command_kick($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
      {
        if(isset($comandos[3]) && !empty($comandos[3]))
        {
          $this->kick($comandos[2], $comandos[3]);
        } else $this->response('Deben especificarse todos los parametros', $contexto['canal']);
      } else $this->response($contexto['name'].' No tengo ganas', $contexto['canal']);
    }
    
    private function command_local($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
      {
        if($comandos[2]=='shutdown')
        {
          $this->component_executor('shutdown'); // esta es la llamada a las funciones de la interface ComLoaderInterface
          $this->response('Hecho', $contexto['canal']);
        }
        elseif($comandos[2]=='note')
        {
          $final_text = null;
          for($i = 3; $i<count($comandos); $i++)
            $final_text .= $comandos[$i].' ';
          $this->component_executor('note', $final_text);
          $this->response('Hecho', $contexto['canal']);
          
        }
          
      } else $this->response($contexto['name'].' No tengo ganas', $contexto['canal']);
    }
    
    private function command_explorer($contexto, $comandos)
    {
      if($this->is_admin($contexto['name']))
      {
        $comandos[2] = strtolower($comandos[2]);
        if($comandos[2]=='help')
        {
          $this->response('help:ayuda, u:ubicacion_actual, ls:lista_de_archivos, cd:cambiar_directorio, get:obtener_documento', $contexto['canal']);
        }
        elseif($comandos[2]=='u')
        {
          $this->response(getcwd(), $contexto['canal']);
        }
        elseif($comandos[2]=='ls')
        {
          $obj = dir(getcwd());
          $list = null;
          while (false !== ($file = $obj->read())) 
          {
             $list .= ' <'.$file.'> ';
          }
          $this->response($list, $contexto['canal']);
        }
        elseif($comandos[2]=='cd' && !empty($comandos[3]))
        {
          $final_text = null;
          for($i = 3; $i<count($comandos); $i++)
            $final_text .= $comandos[$i].' ';
          chdir($final_text);
          $this->response(getcwd(), $contexto['canal']);
        }
        elseif($comandos[2]=='get' && !empty($comandos[3]))
        {
          $final_text = null;
          for($i = 3; $i<count($comandos); $i++)
            $final_text .= $comandos[$i].' ';
          $response = $this->pastebin(file_get_contents(getcwd().DS.$final_text),$final_text);
          $this->response($response, $contexto['canal']);
        } else {
          $this->response($contexto['name'].' formato de petición invalido', $contexto['canal']);
        }
      } else $this->response($contexto['name'].' No tengo ganas', $contexto['canal']);
      
    }
    
    	
            /**
              * Código de encriptar y desencriptar
              * Programado por Fermino
              * Contact: http://underc0de.org/foro/profile/fermino/
              */
            private function command_encriptar($contexto, $comandos)
            {
              //Se crea la cadena y se quitan los espacios iniciales y finales.
              $frase = null;
          for($i = 3; $i<count($comandos); $i++)
              { $frase .= $comandos[$i].' '; }
              $frase = trim($frase);
             
              if (empty($frase))
              { $this->response('El texto no debe estar vacío', $contexto['canal']); }
              else
              {
                //Se encripta la cadena y se envía.
                if($comandos[2] == 'base64')
                { $this->response($frase.' ('.$comandos[2].') --> '.base64_encode($frase), $contexto['canal']); }
                else if($frase_final = hash($comandos[2], $frase))
                { $this->response($frase.' ('.$comandos[2].') --> '.$frase_final, $contexto['canal']); }
                else
                { $this->response('Sintaxis o Tipo de encriptación inválido.', $contexto['canal']); }
              }
            }
           
           /**
              * Código de encriptar y desencriptar
              * Programado por Fermino
              * Contact: http://underc0de.org/foro/profile/fermino/
              */
            private function command_desencriptar($contexto, $comandos)
            {
              //Se crea la cadena y se quitan los espacios iniciales y finales.
              $frase = null;
              for($i = 3; $i<count($comandos); $i++)
              { $frase .= $comandos[$i].' '; }
              $frase = trim($frase);
             
              if (empty($frase))
              { $this->response('El texto no debe estar vacío', $contexto['canal']); }
              else
              {
                //Se desencripta la cadena y se envía.
                if ($comandos[2] == 'base64')
                { $this->response($frase.' ('.$comandos[2].') --> '.base64_decode($frase), $contexto['canal']); }
                else
                { $this->response('Sintaxis o tipo de encriptación inválido', $contexto['canal']); }
              }
            }
            
            /**
              * Código de obtener fecha y hora
              * Programado por Fermino
              * Contact: http://underc0de.org/foro/profile/fermino/
              */
            private function command_date($contexto, $comandos)
            {
              //Se define la zona horaria.
              $timezone = 'America/Argentina/Buenos_Aires';
              date_default_timezone_set($timezone);
              //Se muestra la hora y la fecha.
              $this->response('Son las '.date("H:i").', del día '.date("d/m/Y").' ('.$timezone.')', $contexto['canal']);
            }
}