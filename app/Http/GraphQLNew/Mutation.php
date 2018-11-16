<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 14:20
 */

namespace App\Http\GraphQLNew;


use App\Actions\Models\Create\CreateAppAction;
use App\Actions\Models\Create\CreateCertificateAction;
use App\Actions\Models\Create\CreateCertificateCategoryAction;
use App\Actions\Models\Create\CreateGroupAction;
use App\Actions\Models\Create\CreateGroupEmailAddressAction;
use App\Actions\Models\Create\CreateMembershipAction;
use App\Actions\Models\Create\CreatePersonAction;
use App\Actions\Models\Create\CreatePersonAddressAction;
use App\Actions\Models\Create\CreatePersonEmailAddressAction;
use App\Actions\Models\Create\CreatePersonPhoneNumberAction;
use App\Actions\Models\Delete\DeleteAction;
use App\Actions\Models\Update\UpdateCertificateCategoryAction;
use App\Actions\Models\Update\UpdatePersonAction;
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
            UpdatePersonAction::class,
            UpdateCertificateCategoryAction::class,
        ];
    }
}