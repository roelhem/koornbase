<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 00:39
 */

namespace Tests\Feature\Rbac;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Roelhem\RbacGraph\Rules\StaticRule;
use Tests\TestCase;

class GateNodeTest extends TestCase
{

    use RefreshDatabase;

    public function testStaticRuleGate() {

        // Building the structure
        \Rbac::task('taskA')
            ->assign(
                \Rbac::ability('abilityA')
            );

        \Rbac::task('taskB')
            ->assign(
                \Rbac::ability('abilityB')
            );

        \Rbac::task('userTask')
            ->assign(
                \Rbac::gate('gateA', new StaticRule(false))->assign('taskA'),
                \Rbac::gate('gateB', new StaticRule(true))->assign('taskB')
            );

        \Rbac::role('adminRole')->assign('taskA','taskB');
        \Rbac::role('userRole')->assign('userTask');

        // Create the users
        $admin = factory(User::class)->create();
        $user = factory(User::class)->create();

        // Assign to roles
        $admin->assignNode('adminRole');
        $user->assignNode('userRole');


        // Test the access to ability.
        $this->assertTrue(\Gate::forUser($admin)->allows('abilityA'));
        $this->assertFalse(\Gate::forUser($user)->allows('abilityA'));
        $this->assertTrue(\Gate::forUser($admin)->allows('abilityB'));
        $this->assertTrue(\Gate::forUser($user)->allows('abilityB'));

    }

}