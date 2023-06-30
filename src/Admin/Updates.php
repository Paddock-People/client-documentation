<?php

namespace Atlas\Documentation\Admin;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use YahnisElsts\PluginUpdateChecker\v5p1\Vcs\GitHubApi;

class Updates
{
    public function __construct()
    {
        $update_checker = PucFactory::buildUpdateChecker(
            'https://github.com/Paddock-People/client-documentation/',
            __FILE__,
            'client-documentation'
        );

        /**
 * @var GitHubApi $api 
*/
        $api = $update_checker->getVcsApi();

        $api->enableReleaseAssets('client-documentation.zip');
    }
}
