<?php
namespace Liberty;

use Cake\Utility\Inflector;
use Cake\View\ViewBuilder;
use InvalidArgumentException;

class Liberty
{
    /**
     * get the result of rendering
     *
     * @access public
     * @author kozo
     * @author ito
     */
    public static function __callStatic ($name, $arguments)
    {
        if (count($arguments) == 0) {
            throw new InvalidArgumentException();
        }
        if (!is_string($arguments[0]) || (isset($arguments[1]) && !is_array($arguments[1]))) {
            throw new InvalidArgumentException();
        }

        $fullFileName = $arguments[0];
        $params = isset($arguments[1]) ? $arguments[1] : [];

        $ext = isset($params['ext']) ? $params['ext'] : $name;
        $helpers = isset($params['helpers']) ? $params['helpers'] : [];
        unset($params['ext']);
        unset($params['helpers']);

        list($plugin, $fileName) = pluginSplit($fullFileName);

        $builder = new ViewBuilder();
        if (!is_null($plugin)) {
            $builder->plugin($plugin);
        }

        $view = $builder
            ->setClassName('Cake\View\View')
            ->setTemplatePath(Inflector::camelize($name))
            ->setLayout(false)
            ->setHelpers($helpers)
            ->build();

        $view->_ext = '.' . $ext;
        $view->viewVars = $params;

        return $view->render($fileName);
    }
}

