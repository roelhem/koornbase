<?php

Rbac::group('me:', function() {


    Rbac::ability('view-me')->assignTo('ActiveUser');


    Rbac::gate('alwaysOpen', new \Roelhem\RbacGraph\Rules\StaticRule(true))
        ->assign('view-me');
    Rbac::gate('neverOpen', new \Roelhem\RbacGraph\Rules\StaticRule(false))
        ->assign('view-me');

    Rbac::task('bothGates')->assign('alwaysOpen','neverOpen');

    Rbac::gate('alwaysOpen2', new \Roelhem\RbacGraph\Rules\ModelBlockingRule([\App\User::class]))
        ->assign('bothGates');

});