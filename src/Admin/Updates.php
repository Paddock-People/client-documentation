<?php

namespace Atlas\Documentation\Admin;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Updates
{
    public function __construct()
    {
        $update_checker = PucFactory::buildUpdateChecker(
            'https://github.com/Paddock-People/client-documentation/',
            __FILE__,
            'client-documentation'
        );
        $update_checker->getVcsApi()->enableReleaseAssets('client-documentation.*\.zip');
    }
}
