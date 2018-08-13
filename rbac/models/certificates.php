<?php


Rbac::group('certificate-categories:', function() {

    Rbac::crudAbilities(\App\CertificateCategory::class, 'crud');

    createTrashedAbilities(\App\CertificateCategory::class);

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});


Rbac::group('certificates:', function() {

    Rbac::crudAbilities(\App\Certificate::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});

