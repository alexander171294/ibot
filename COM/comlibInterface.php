<?php

interface iComponentLoader
{
    static public function get_instance($component, $id);
    static public function get_old_instance($component);
}