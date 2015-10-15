<?php
/**
 * Default config header
 *
 * To replace this add a file tao/conf/header/SimpleAclWhitelist.conf.php
 */

return array(
    'tao_actions_Main' => array(
        'entry' => '*',
        'login' => '*',
        'logout' => '*',
    ),
    'tao_actions_AuthApi' => '*',
    'tao_actions_ClientConfig' => '*',
);
