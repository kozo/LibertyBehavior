<?php
namespace Liberty;

use Cake\View\View;

class LibertyView extends View
{
    public function setExtension(string $ext)
    {
        $this->_ext = $ext;
    }
}
