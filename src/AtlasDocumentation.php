<?php

namespace Atlas\Documentation;

use Atlas\Documentation\Admin\Admin;
use Atlas\Documentation\Admin\ImportDocumentation;
use Atlas\Documentation\Admin\Updates;
use Atlas\Documentation\Front\Frontend;
use Atlas\Documentation\Installation\Installation;
use Atlas\Documentation\PostType\ClientDocumentation;

class AtlasDocumentation
{
    public function __construct()
    {
        new Admin();
        new Updates();
        new Frontend();
        new Installation();
        new ClientDocumentation();
    }
}
