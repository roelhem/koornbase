<?php

Rbac::permission('model.persons.all.list')->assignPermission('route.api.persons.index');
Rbac::permission('model.persons.all.show')->assignPermission('route.api.persons.show');
Rbac::permission('model.persons.all.store')->assignPermission('route.api.persons.store');
Rbac::permission('model.persons.all.update')->assignPermission('route.api.persons.update');
Rbac::permission('model.persons.all.destroy')->assignPermission('route.api.persons.destroy');

Rbac::permission('model.persons.all.manage')->assignPermissions([
    'model.persons.all.list','model.persons.all.show','model.persons.all.store','model.persons.all.update',
    'model.persons.all.destroy'
]);

Rbac::role('guest')->assignPermission('route.all');