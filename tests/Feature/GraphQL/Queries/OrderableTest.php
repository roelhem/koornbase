<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 18:26
 */

namespace Tests\Feature\GraphQL\Queries;


use App\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderableTest extends TestCase
{

    use RefreshDatabase;

    public function testSimpleQueryOrdering()
    {
        $this->asSuper();

        $factory = factory(Person::class);
        /** @var Person $a */
        $a = $factory->create(['name_first' => 'A', 'name_last' => 'F']);
        /** @var Person $b */
        $b = $factory->create(['name_first' => 'B', 'name_last' => 'E']);
        /** @var Person $c */
        $c = $factory->create(['name_first' => 'B', 'name_last' => 'D']);

        $A = ['id' => strval($a->id), 'name_first' => $a->name_first, 'name_last' => $a->name_last];
        $B = ['id' => strval($b->id), 'name_first' => $b->name_first, 'name_last' => $b->name_last];
        $C = ['id' => strval($c->id), 'name_first' => $c->name_first, 'name_last' => $c->name_last];

        $query = /** @lang GraphQL */'
            query testSimpleQueryOrdering($orderBy:[Person_orderRule]) {
                persons(orderBy:$orderBy) {
                    data {
                        id name_first name_last
                    }
                }
            }
        ';

        $attrs = ['id','name_first','name_last'];

        $this->graphql($query)->assertNoErrors()->assertData([
            'persons' => [
                'data' => [$A, $B, $C]
            ]
        ]);

        $this->graphql($query, ['orderBy' => [['by' => 'id']]])->assertNoErrors()->assertExactJson([
            'data' => [
                'persons' => [
                    'data' => [$A, $B, $C]
                ]
            ]
        ]);

        $this->graphql($query, ['orderBy' => [['by' => 'name_last']]])->assertNoErrors()->assertExactJson([
            'data' => [
                'persons' => [
                    'data' => [$C, $B, $A]
                ]
            ]
        ]);

        $this->graphql($query, [
            'orderBy' => [['by' => 'name_first','dir' => 'DESC'], ['by' => 'id']]
        ])->assertNoErrors()->assertExactJson([
            'data' => [
                'persons' => [
                    'data' => [$B, $C, $A]
                ]
            ]
        ]);

        $this->graphql($query, [
            'orderBy' => [['by' => 'id', 'dir' => 'ASC'], ['by' => 'name_first','dir' => 'DESC']]
        ])->assertNoErrors()->assertExactJson([
            'data' => [
                'persons' => [
                    'data' => [$A, $B, $C]
                ]
            ]
        ]);

        $this->graphql($query, ['orderBy' => []])->assertNoErrors();
        $this->graphql($query, ['orderBy' => [[]]])->assertErrors();
        $this->graphql($query, ['orderBy' => null])->assertNoErrors();
        $this->graphql($query, ['orderBy' => [['dir' => 'ASC']]])->assertErrors();
        $this->graphql($query, ['orderBy' => [['boe' => 'id']]])->assertErrors();
        $this->graphql($query, ['orderBy' => 'id'])->assertErrors();
        $this->graphql($query, ['orderBy' => ['by' => 'id']])->assertErrors();
    }

}