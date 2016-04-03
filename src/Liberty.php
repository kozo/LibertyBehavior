<?php

namespace Liberty;

use Cake\View\ViewBuilder;

class Liberty
{
    /**
     * get the SQL
     *
     * @access public
     * @author sakuragawa
     */
    public static function sql($fileName, array $data = [])
    {
        $builder = new ViewBuilder();
        $view = $builder
            ->className('Cake\View\View')
            ->templatePath('Sql')
            ->layout(false)
            ->build();

        $view->_ext = '.sql';
        $view->viewVars = $data;

        return $view->render($fileName);
    }
}
