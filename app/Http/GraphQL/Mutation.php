<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 14:20
 */

namespace App\Http\GraphQL;


use App\Actions\Models\Create\CreateCertificateAction;
use App\Actions\Models\Create\CreateCertificateCategoryAction;
use App\Actions\Models\Create\CreateGroupAction;
use App\Actions\Models\Create\CreateGroupEmailAddressAction;
use App\Actions\Models\Create\CreateMembershipAction;
use App\Actions\Models\Create\CreateOAuthClientAction;
use App\Actions\Models\Create\CreatePersonAction;
use App\Actions\Models\Create\CreatePersonAddressAction;
use App\Actions\Models\Create\CreatePersonEmailAddressAction;
use App\Actions\Models\Create\CreatePersonPhoneNumberAction;
use App\Actions\Models\Delete\DeleteAction;
use App\Actions\Models\NewMembershipApplicationAction;
use App\Actions\Models\Restore\RestoreAction;
use App\Actions\Models\RevokeOAuthClientAction;
use App\Actions\Models\StartMembershipAction;
use App\Actions\Models\StopMembershipAction;
use App\Actions\Models\Update\UpdateCertificateAction;
use App\Actions\Models\Update\UpdateCertificateCategoryAction;
use App\Actions\Models\Update\UpdateGroupAction;
use App\Actions\Models\Update\UpdateGroupCategoryAction;
use App\Actions\Models\Update\UpdateGroupEmailAddressAction;
use App\Actions\Models\Update\UpdateMembershipAction;
use App\Actions\Models\Update\UpdateOAuthClientAction;
use App\Actions\Models\Update\UpdatePersonAction;
use App\Actions\Models\Update\UpdatePersonEmailAddressAction;
use App\Actions\Models\Update\UpdatePersonPhoneNumberAction;
use App\Actions\Models\Update\UpdateUserAction;
use App\Actions\Relations\Attach\AttachPersonToGroupAction;
use App\Actions\Relations\Detach\DetachPersonFromGroupAction;
use App\Actions\RequestPersonalAccessTokenAction;
use Roelhem\GraphQL\Types\MutationType;

class Mutation extends MutationType
{

    public function actions()
    {
        return [

            // CREATE MODEL ACTIONS
            CreatePersonAction::class,
            CreateGroupAction::class,
            CreateCertificateAction::class,
            CreateCertificateCategoryAction::class,
            CreateGroupEmailAddressAction::class,
            CreateOAuthClientAction::class,
            CreateMembershipAction::class,
            CreatePersonAddressAction::class,
            CreatePersonEmailAddressAction::class,
            CreatePersonPhoneNumberAction::class,
            //CreateAppAction::class,

            // DELETE MODEL ACTIONS
            new DeleteAction('CertificateCategory'),
            new DeleteAction('Certificate'),
            new DeleteAction('GroupCategory'),
            new DeleteAction('GroupEmailAddress'),
            new DeleteAction('Group'),
            new DeleteAction('Membership'),
            new DeleteAction('Person'),
            new DeleteAction('PersonAddress'),
            new DeleteAction('PersonEmailAddress'),
            new DeleteAction('PersonPhoneNumber'),

            // UPDATE MODEL ACTIONS
            UpdateCertificateAction::class,
            UpdateCertificateCategoryAction::class,
            UpdateGroupAction::class,
            UpdateGroupCategoryAction::class,
            UpdateGroupEmailAddressAction::class,
            UpdateMembershipAction::class,
            UpdateOAuthClientAction::class,
            UpdatePersonAction::class,
            UpdatePersonEmailAddressAction::class,
            UpdatePersonPhoneNumberAction::class,
            UpdateUserAction::class,


            // RESTORE MODEL ACTIONS
            new RestoreAction('CertificateCategory'),
            new RestoreAction('GroupCategory'),
            new RestoreAction('Group'),
            new RestoreAction('Person'),


            // OTHER MODEL ACTIONS
            NewMembershipApplicationAction::class,
            StartMembershipAction::class,
            StopMembershipAction::class,
            RevokeOAuthClientAction::class,

            // RELATION ATTACH ACTIONS
            AttachPersonToGroupAction::class,
            DetachPersonFromGroupAction::class,

            // OAUTH ACTIONS
            RequestPersonalAccessTokenAction::class,
        ];
    }
}