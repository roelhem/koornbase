<?php



Rbac::superRole()->title('Super')
    ->description('Role that bypasses all rules.');

// --------------------------------------------------------------------------------------------------------------- //
// ------ STANDARD WEBSITE ROLES --------------------------------------------------------------------------------- //
// --------------------------------------------------------------------------------------------------------------- //

Rbac::role('admin')->title('Admin');

Rbac::role('moderator')->title('Moderator')
    ->assignTo('admin');

Rbac::role('webmaster')->title('Webmaster')
    ->assignTo('admin');

// --------------------------------------------------------------------------------------------------------------- //
// ------ GROUPS ------------------------------------------------------------------------------------------------- //
// --------------------------------------------------------------------------------------------------------------- //

Rbac::dynamicRole('group.primary');

Rbac::dynamicRole('group.secondary');


// --------------------------------------------------------------------------------------------------------------- //
// ------ DEVELOPER RELATED -------------------------------------------------------------------------------------- //
// --------------------------------------------------------------------------------------------------------------- //

Rbac::abstractRole('dev')->assignTo([

    Rbac::abstractRole('dev-testing')->assignTo([
        Rbac::role('tester')->title('Tester'),
        Rbac::role('code-reviewer')->title('Code-Reviewer')
    ]),

    Rbac::abstractRole('dev-frontend')->assignTo([
        Rbac::role('designer')->title('Designer'),
        Rbac::role('developer-frontend')->title('Front-end Developer')
    ]),

    Rbac::abstractRole('dev-backend')->assignTo([
        Rbac::role('developer-auth')->title('Authentication/Authorization Developer'),
        Rbac::role('developer-db')->title('Database Developer'),
        Rbac::role('developer-api')->title('API-Developer'),
    ]),

    Rbac::role('developer-master')->title('Master Developer')->assign(
        'tester','code-reviewer','designer',
        'developer-frontend','developer-auth', 'developer-db', 'developer-api'
    ),
]);
