<?php

namespace App\Http\Controllers\Api;


use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\UserStoreRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Person;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{

    public $eagerLoadForIndex = ['person'];
    public $eagerLoadForShow = [
        'person','person.groups','person.certificates','person.emailAddresses',
        'person.phoneNumbers','accounts'
    ];

    /**
     * @param UserStoreRequest $request
     * @param FinderCollection $finders
     * @return JsonResource
     * @throws
     */
    public function store(UserStoreRequest $request, FinderCollection $finders)
    {
        $data = $request->validated();

        $user = new User(array_except($data,'person'));

        $personInput = array_get($data, 'person');
        if($personInput !== null) {
            /** @var Person $person */
            $person = $finders->find($personInput, 'person');
            $user->person()->associate($person);
        }

        $user->saveOrFail();

        $user->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($user);
    }

    /**
     * @param UserUpdateRequest $request
     * @param FinderCollection $finders
     * @param User $user
     * @return JsonResource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     * @throws \Throwable
     */
    public function update(UserUpdateRequest $request, FinderCollection $finders, User $user)
    {
        $data = $request->validated();

        $personInput = array_get($data,'person',false);
        if($personInput !== false) {
            if($personInput === null) {
                $user->person()->dissociate();
            } else {
                /** @var Person $person */
                $person = $finders->find($personInput, 'person');
                $user->person()->associate($person);
            }
        }

        $user->fill(array_except($data, 'person'));

        $user->saveOrFail();

        $user->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($user);
    }
}
