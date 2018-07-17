<?php

namespace mvc\libs;

/**
 * Класс содержит мелкие вспомогательные методы...
 */
class Simple
{
    public static function dump($variable){
        print('<pre>');
        var_dump($variable);
        print('</pre>');
    }
}