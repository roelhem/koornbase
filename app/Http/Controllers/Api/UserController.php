<?php

namespace App\Http\Controllers\Api;


class UserController extends Controller
{

    public $eagerLoadForIndex = ['person'];
    public $eagerLoadForShow = [
        'person','person.groups','person.certificates','person.emailAddresses',
        'person.phoneNumbers','accounts'
    ];


    /*
    protected $modelClass = User::class;
    protected $resourceClass = UserResource::class;

    /**
     * Store a new User in the database.
     *
     * @param Request $request
     * @param FinderCollection $finders
     * @return UserResource
     * @throws \Throwable
     *
    public function store(Request $request, FinderCollection $finders)
    {
        $this->authorize('store', User::class);

        $validatedData = $request->validate([
            'name' => 'required|unique:users|string|max:255',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|string|min:8',
            'person' => 'nullable|finds:person',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);

        $personInput = array_get($validatedData, 'person');
        if($personInput !== null) {
            $person = $finders->find($personInput, 'person');
            $user->person()->associate($person);
        }

        $user->saveOrFail();

        $user->load($this->getAskedRelations($request));
        return new UserResource($user);
    }


    public function update(Request $request, User $user, FinderCollection $finders)
    {
        $this->authorize('update', $user);

        $validatedData = $request->validate([
            'name' => ['sometimes','required','string','max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['sometimes','required','email','max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|required|string|min:8',
            'person' => 'nullable|finds:person'
        ]);

        if(array_has($validatedData, 'name')) {
            $user->name = $validatedData['name'];
        }

        if(array_has($validatedData, 'email')) {
            $user->email = $validatedData['email'];
        }

        if(array_has($validatedData, 'password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        if(array_has($validatedData, 'person')) {
            $personInput = array_get($validatedData, 'person');
            if($personInput === null) {
                $user->person_id = null;
            } else {
                $person = $finders->find($personInput, 'person');
                $user->person()->associate($person);
            }
        }

        $user->saveOrFail();

        $user->load($this->getAskedRelations($request));
        return new UserResource($user);
    }


    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
    }
    */
}
