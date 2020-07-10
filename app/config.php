<?php

class ssConfig
{
    private static $parameters;

    public static function get_parameter($_parameter)
    {
        if (!self::$parameters) {
            self::$parameters = parse_ini_file('config.ini');
        }
        
        if (isset(self::$parameters[$_parameter])) {
            return 	self::$parameters[$_parameter];
        } else {
            return "";
        }
    }
}
