<?php
declare(strict_types=1);

namespace Liberty;

use Cake\View\View;

class LibertyView extends View
{
    /**
     * @param string $ext
     * @return void
     */
    public function setExtension(string $ext): void
    {
        $this->_ext = $ext;
    }
}
