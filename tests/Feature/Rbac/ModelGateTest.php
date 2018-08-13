<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 09:22
 */

namespace Tests\Feature\Rbac;


use App\AuthRules\OwnedModelRule;
use App\Person;
use App\PersonEmailAddress;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelGateTest extends TestCase
{

    use RefreshDatabase;

    public function testWithOwnedRule() {

        \Rbac::modelAbility('view', PersonEmailAddress::class,'person-email-address:view');

        \Rbac::task('TaskA')->assign('person-email-address:view');
        \Rbac::gate('TaskA|owned', new OwnedModelRule())->assign('TaskA');
        \Rbac::task('TaskB')->assign('TaskA|owned');

        \Rbac::role('RoleA')->assign('TaskA');
        \Rbac::role('RoleB')->assign('TaskB');

        $persons = factory(Person::class, 3)->create()->each(function(Person $person) {
            factory(User::class)->create(['person_id' => $person->id]);
            factory(PersonEmailAddress::class)->create(['person_id' => $person->id]);
        });

        $persons[0]->assignNode('RoleA');
        $persons[1]->assignNode('RoleB');

        $userA = $persons[0]->users()->first();
        $userB1 = $persons[1]->users()->first();
        $userB2 = $persons[2]->users()->first();
        $userB2->assignNode('RoleB');

        $this->assertInstanceOf(User::class, $userA);
        $this->assertInstanceOf(User::class, $userB1);
        $this->assertInstanceOf(User::class, $userB2);

        $emailA = $persons[0]->emailAddresses()->first();
        $emailB1 = $persons[1]->emailAddresses()->first();
        $emailB2 = $persons[2]->emailAddresses()->first();

        $this->assertInstanceOf(PersonEmailAddress::class, $emailA);
        $this->assertInstanceOf(PersonEmailAddress::class, $emailB1);
        $this->assertInstanceOf(PersonEmailAddress::class, $emailB2);

        $this->assertTrue(\Gate::forUser($userA)->allows('view', $emailA));
        $this->assertTrue(\Gate::forUser($userA)->allows('view', $emailB1));
        $this->assertTrue(\Gate::forUser($userA)->allows('view', $emailB2));

        $this->assertFalse(\Gate::forUser($userB1)->allows('view', $emailA));
        $this->assertTrue(\Gate::forUser($userB1)->allows('view', $emailB1));
        $this->assertFalse(\Gate::forUser($userB1)->allows('view', $emailB2));

        $this->assertFalse(\Gate::forUser($userB2)->allows('view', $emailA));
        $this->assertFalse(\Gate::forUser($userB2)->allows('view', $emailB1));
        $this->assertTrue(\Gate::forUser($userB2)->allows('view', $emailB2));

    }

}