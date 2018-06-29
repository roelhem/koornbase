<?php

namespace Tests\Feature\Rbac;

use App\Group;
use App\Person;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Roelhem\RbacGraph\Database\Tools\DatabaseAuthorizer;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Node;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GateTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = factory(User::class)->create();

        \Auth::login($user);

        $this->assertFalse(\Gate::allows('test'));
        $this->assertFalse($user->can('test2'));
    }

    /**
     * @throws
     */
    public function testSimpleUsage()
    {

        $b = \Rbac::builder();

        $b->role('RoleA');
        $b->role('RoleB');

        $b->task('TaskA')->assignTo('RoleA');
        $b->task('TaskB')->assignTo('RoleB');
        $b->task('TaskC')->assignTo('RoleB');

        $b->crudAbilities(Group::class, 'group')->assignTo('TaskA');
        $b->crudAbilities(Person::class, 'person')->assignTo('TaskB');

        $b->task('TaskC')->assign('group.view');

        $userA = factory(User::class)->create();
        if(!($userA instanceof User)) {
            $this->assertFalse(true);
        }
        $userA->assignNode('RoleA');
        $userA->load('assignedNodes');
        $gateA = \Gate::forUser($userA);
        if(!($gateA instanceof Gate)) {
            $this->assertFalse(true);
        }

        $this->assertTrue($gateA->allows('view', Group::class));
        $this->assertTrue($gateA->allows('delete', Group::class));
        $this->assertFalse($gateA->allows('view', Person::class));
        $this->assertFalse($gateA->allows('view'));


        $userB = factory(User::class)->create();
        if(!($userB instanceof User)) {
            $this->assertFalse(true);
        }
        $userB->assignNode('RoleB');
        $userB->load('assignedNodes');
        $gateB = \Gate::forUser($userB);
        if(!($gateB instanceof Gate)) {
            $this->assertFalse(true);
        }

        $this->assertTrue($gateB->allows('view', Group::class));
        $this->assertFalse($gateB->allows('delete', Group::class));
        $this->assertTrue($gateB->allows('view', Person::class));
        $this->assertFalse($gateB->allows('view'));


    }







    /**
     * Tests the abilities in the gate.
     */
    public function testAbilities()
    {
        $users = factory(User::class, 3)->create();

        $group = factory(Group::class)->create();
        $person = factory(Person::class)->create();

        $b = \Rbac::builder();

        $b->modelAbility('view',Group::class,'model_ability.view')
            ->assignTo($b->role('R0'));
        $b->ability('view','ability.view')
            ->assignTo($b->role('R1'));
        $b->permission('view')
            ->assignTo($b->role('R2'));
        $b->permission('unique.view')
            ->assignTo($b->role('R2'));

        $b->modelAbility('view',Person::class,'person.view');

        $modelAbility = Node::query()->where('name','=','model_ability.view')->first();

        dump($modelAbility->options);

        echo PHP_EOL.'JsonHasView'.PHP_EOL;
        Node::query()->whereJsonContains('options',['ability' => 'view', 'modelClass' => Group::class])->each(function(Node $node) {
            echo '  - '.$node.PHP_EOL;
        });

        $users->each(function(User $user, $key) {
            $user->assignNode("R{$key}");
            $user->load('assignedNodes');

            $authorizer = new DatabaseAuthorizer($user);
            $this->assertTrue($authorizer->allows("R{$key}"));
        });


        $expected = [
            'GROUP_INSTANCE'     => [true,  false, false],
            'GROUP_CLASS'        => [true,  false, false],
            'PERSON_INSTANCE'    => [false, false, false],
            'PERSON_CLASS'       => [false, false, false],
            'NO_ATTRIBUTES'      => [false, true,  false],
            'ABILITY_NAME'       => [false, true,  false],
            'MODEL_ABILITY_NAME' => [true,  false, false],
            'UNIQUE_NAME'        => [false, false, true]
        ];

        $users->each(function(User $user, $key) use ($expected, $group, $person) {
            $this->assertEquals(
                $expected['GROUP_INSTANCE'][$key],
                $user->can('view', $group),
                "key: {$key}"
            );

            $this->assertEquals(
                $expected['GROUP_CLASS'][$key],
                $user->can('view', Group::class),
                "key: {$key}"
            );

            $this->assertEquals(
                $expected['PERSON_INSTANCE'][$key],
                $user->can('view', $person),
                "key: {$key}"
            );

            $this->assertEquals(
                $expected['PERSON_CLASS'][$key],
                $user->can('view', Person::class),
                "key: {$key}"
            );

            $this->assertEquals(
                $expected['NO_ATTRIBUTES'][$key],
                $user->can('view'),
                "key: {$key}"
            );

            $this->assertEquals(
                $expected['ABILITY_NAME'][$key],
                $user->can('ability.view', $group),
                "key: {$key}"
            );

            $this->assertEquals(
                $expected['MODEL_ABILITY_NAME'][$key],
                $user->can('model_ability.view'),
                "key: {$key}"
            );

            $this->assertEquals(
                $expected['UNIQUE_NAME'][$key],
                $user->can('unique.view'),
                "key: {$key}"
            );
        });
    }


    /**
     * Tests the behaviour of the super role.
     */
    public function testSuper()
    {
        \Rbac::superRole('Super');

        \Rbac::role('role')->assign(
            \Rbac::task('task')
        );

        \Rbac::task('unused');


        $user = factory(User::class)->create();
        $graph = resolve(DatabaseGraph::class);

        if(!($graph instanceof DatabaseGraph)) {
            die();
        }

        $this->assertFalse($user->can('test'));
        $this->assertFalse($user->can('role'));
        $this->assertFalse($user->can('task'));
        $this->assertFalse($user->can('unused'));

        $user->assignNode('role');
        $user->load('assignedNodes');

        $this->assertCount(1, $graph->getAssignedNodes($user));

        $this->assertFalse($user->can('test'));
        $this->assertTrue($user->can('role'));
        $this->assertTrue($user->can('task'));
        $this->assertFalse($user->can('unused'));

        $user->assignNode('Super');
        $user->load('assignedNodes');

        $this->assertTrue($user->can('test'));
        $this->assertTrue($user->can('role'));
        $this->assertTrue($user->can('task'));
        $this->assertTrue($user->can('unused'));

    }
}
