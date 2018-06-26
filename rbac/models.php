<?php


Rbac::group('models:', function() {


    require __DIR__.'/models/users.php';
    require __DIR__.'/models/groups.php';
    require __DIR__.'/models/persons.php';
    require __DIR__.'/models/certificates.php';
    require __DIR__.'/models/memberships.php';

});