<?php

namespace Tests\Feature\Finders;

use App\Contracts\Finders\FinderCollection;
use App\Exceptions\Finders\ModelNotFoundException;
use App\GroupEmailAddress;
use App\KoornbeursCard;
use App\Services\Finders\ModelByIdOrSlugFinder;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinderTest extends TestCase
{
    /**
     * Tests if all the modelFinders can always identify by id
     *
     * @return void
     * @throws
     */
    public function testShared()
    {
        $finders = resolve(FinderCollection::class);
        foreach($finders->list() as $name => $finder) {
            $model = factory($finder->modelClass())->create();

            $this->assertFalse($finder->accepts(null));
            $this->assertTrue($finder->accepts($model));
            $this->assertTrue($finder->accepts($model->id));
            $this->assertTrue($finder->accepts(strval($model->id)));

            if($finder instanceof ModelByIdOrSlugFinder) {
                $this->assertTrue($finder->accepts($model->slug));
            }

            $foundByInstance = $finder->find($model);
            $foundById       = $finder->find($model->id);
            $foundByStringId = $finder->find(strval($model->id));

            $this->assertEquals($model->id, $foundByInstance->id,"Finder $name via instance");
            $this->assertEquals($model->id, $foundById->id, "Finder $name via id");
            $this->assertEquals($model->id, $foundByStringId->id, "Finder $name via string value of id");

            if($finder instanceof ModelByIdOrSlugFinder) {
                $this->assertEquals($model->id, $finder->find($model->slug)->id,"Finder $name via slug");
            }

            $model->forceDelete();

            try {
                $finder->find($model->id);
                $this->assertTrue(false, "Finder $name found the id {$model->id} after deletion");
            } catch (\Exception $exception) {
                $this->assertInstanceOf(ModelNotFoundException::class, $exception);
            }

            try {
                $finder->find(strval($model->id));
                $this->assertTrue(false, "Finder $name found the string value of id {$model->id} after deletion");
            } catch (\Exception $exception) {
                $this->assertInstanceOf(ModelNotFoundException::class, $exception);
            }

            if($finder instanceof ModelByIdOrSlugFinder) {
                try {
                    $finder->find(strval($model->slug));
                    $this->assertTrue(false, "Finder $name found the slug {$model->slug} after deletion");
                } catch (\Exception $exception) {
                    $this->assertInstanceOf(ModelNotFoundException::class, $exception);
                }
            }
        }
    }

    public function testUser()
    {
        $user = factory(User::class)->create();
        $finders = resolve(FinderCollection::class);

        $this->assertTrue($finders->accepts($user->email, 'user'));
        $this->assertTrue($finders->accepts($user->name, 'user'));

        $userByEmail = $finders->find($user->email, 'user');
        $userByName  = $finders->find($user->name,  'user');

        $this->assertEquals($user->id, $userByEmail->id);
        $this->assertEquals($user->id, $userByName->id);
    }

    public function testGroupEmailAddress()
    {
        $email = factory(GroupEmailAddress::class)->create();
        $finders = resolve(FinderCollection::class);

        $this->assertTrue($finders->accepts($email->email_address, 'group_email_address'));
        $this->assertFalse($finders->accepts('bla', 'group_email_address'));

        $foundByEmail = $finders->find($email->email_address, 'group_email_address');

        $this->assertEquals($email->id, $foundByEmail->id);
    }

    public function testKoornbeursCard()
    {
        $card = factory(KoornbeursCard::class)->create();
        $finders = resolve(FinderCollection::class);

        $this->assertTrue($finders->accepts('543543', 'koornbeurs_card'));
        $this->assertTrue($finders->accepts('_34325426', 'koornbeurs_card'));
        $this->assertTrue($finders->accepts('fda_3d4325426', 'koornbeurs_card'));
        $this->assertFalse($finders->accepts('fdsagds5', 'koornbeurs_card'));

        $this->assertTrue($finders->accepts('_'.$card->ref, 'koornbeurs_card'));
        $this->assertTrue($finders->accepts($card->version.'_'.$card->ref, 'koornbeurs_card'));

        $foundByRef           = $finders->find('_'.$card->ref, 'koornbeurs_card');
        $foundByRefAndVersion = $finders->find($card->version.'_'.$card->ref, 'koornbeurs_card');

        $this->assertEquals($card->id, $foundByRef->id);
        $this->assertEquals($card->id, $foundByRefAndVersion->id);
    }
}
