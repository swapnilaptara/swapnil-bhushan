<?php
/**
 * Auth config
 *
 * @author Open Assessment Technologies SA
 * @package generis
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 */

/* Default AuthAdapter
array(
    'driver' => 'oat\\generis\\model\\user\\AuthAdapter',
)
*/
return array(
    0 => array(
        'driver' => 'oat\\generis\\model\\user\\AuthAdapter',
        'hash' => array(
            'algorithm' => 'sha256',
            'salt' => 10,
        ),
    ),
);
