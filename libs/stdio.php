<?php

/** IBOT SOCKET PHP5
 *  @designer: Alexander171294
*  @Proyect Name: PHPIBot 2
*  @contact: Alexander171294@gmail.com
*  @Status: Prototype
*  @Date: 02/12/13
*  @info: proyecto desarrollado por el equipo UnderC0de++
*/

// FUNCIONES EXTENSIBLES
/*
 * Si es necesario cambiar la salida, solo modifcamos esta función.
*/


// si no existe definimos directory separator
if(!defined('DS')) define('DS', "\\");

// standard out
function std_out($string)
{
	echo utf8_decode(utf8_encode($string)).NL;
}

// standard input
function std_in()
{
	$line = trim(fgets(STDIN)); // reads one line from STDIN
	return $line;
}
// standard includes
function std_inc($file)
{
	std_out('[*] LOAD module='.$file);
	require(dirname(__FILE__).DS.'..'.DS.$file);
}
// standard file_exists
function std_exists($file)
{
	return file_exists(dirname(__FILE__).DS.'..'.DS.$file);
}
// standard input
function std_open($file)
{
	return file_get_contents(dirname(__FILE__).DS.'..'.DS.$file);
}

function std_put($file, $content)
{
	file_put_contents(dirname(__FILE__).DS.'..'.DS.$file, $content);
}