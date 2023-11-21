<?php
declare(strict_types=1);

namespace Liberty;

use Cake\Utility\Inflector;
use Cake\View\ViewBuilder;
use InvalidArgumentException;

class Liberty
{
    /**
     * @param string $name
     * @param array $arguments
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function __callStatic(string $name, array $arguments): string
    {
        if (count($arguments) == 0) {
            throw new InvalidArgumentException();
        }
        if (!is_string($arguments[0]) || (isset($arguments[1]) && !is_array($arguments[1]))) {
            throw new InvalidArgumentException();
        }

        $fullFileName = $arguments[0];
        $params = $arguments[1] ?? [];

        $ext = $params['ext'] ?? $name;
        $helpers = $params['helpers'] ?? [];
        unset($params['ext']);
        unset($params['helpers']);

        [$plugin, $fileName] = pluginSplit($fullFileName);

        $builder = new ViewBuilder();
        if (!is_null($plugin)) {
            $builder->setPlugin($plugin);
        }

        $view = $builder
            ->setClassName('Liberty\LibertyView')
            ->setTemplatePath(Inflector::camelize($name))
            ->addHelpers($helpers)
            ->build();

        $view->setExtension('.' . $ext);
        $view->set($params);
        $view->disableAutoLayout();

        return $view->render($fileName);
    }
}
