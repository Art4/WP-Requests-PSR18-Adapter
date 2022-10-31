<?php

if (! class_exists('WpOrg\Requests\Requests') && class_exists('Requests')) {
    class_alias('Requests',                     'WpOrg\Requests\Requests');
    class_alias('Requests_Exception',           'WpOrg\Requests\Exception');
    class_alias('Requests_Exception_Transport', 'WpOrg\Requests\Exception\Transport');
    class_alias('Requests_IRI',                 'WpOrg\Requests\Iri');
    class_alias('Requests_Response',            'WpOrg\Requests\Response');
    class_alias('Requests_Transport',           'WpOrg\Requests\Transport');

    require_once(dirname(__FILE__) . '/InvalidArgument.php');
    require_once(dirname(__FILE__) . '/Port.php');
}
