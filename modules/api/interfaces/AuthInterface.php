<?php

namespace app\modules\api\interfaces;

interface AuthInterface
{

    public function canAccess($action, $model = null, $params = []);
}
