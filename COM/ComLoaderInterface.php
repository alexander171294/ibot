<?php

Interface iComLoader
{
    // solicitar apagado de pc
    public function shutdown($params);
    // crear nota en el escritorio
    public function note($params);
}