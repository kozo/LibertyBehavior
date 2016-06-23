<?php

namespace Liberty;

use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Inflector;
use Cake\View\ViewBuilder;

class Liberty
{
    /**
     * get the result of rendering
     *
     * @access public
     * @author ito
     */
    public static function __callStatic ($name, $arguments)
    {
        if (
            (!isset($name) || !is_string($name)) ||
            (!isset($arguments[0]) || !is_string($arguments[0]))
        ) {
            throw new InternalErrorException();
        }

        $fileName = $arguments[0];
        $data     = (isset($arguments[1]) && is_array($arguments[1]))? $arguments[1]: [];

        $builder = new ViewBuilder();
        $view = $builder
            ->className('Cake\View\View')
            ->templatePath(Inflector::camelize($name))
            ->layout(false)
            ->build();

        $view->_ext = '.' . $name;
        $view->viewVars = $data;

        return $view->render($fileName);
    }
}

