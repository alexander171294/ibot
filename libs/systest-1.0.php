<?php

function sys_test()
{
	std_out('[*] Inicializando Test del sistema');
	$system = php_uname('s');
	std_out('[i] System: '.$system);
	
	if($system != 'Linux') 
	{
		std_out('[!] Este script solo funciona en linux :(');
		return false;
	} else std_out('[+] System ok.');
	
	if(!class_exists('thread'))
	{
		std_out('[!] Debe configurar php.ini para aceptar threads...');
		return false;
	} else std_out('[+] Multi Threads ok.');
	
	return true;
}