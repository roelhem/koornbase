<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Resources\Api\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $query = User::query();
        $query->with($this->getAskedRelations($request));

        return UserResource::collection($query->paginate());
    }

    /**
     * Store a new User in the database.
     *
     * @param Request $request
     * @param FinderCollection $finders
     * @return UserResource
     * @throws \Throwable
     */
    public function store(Request $request, FinderCollection $finders)
    {
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

    /**
     * Display the specified resource.
     *
     * @param  Request    $request
     * @param  \App\User  $user
     * @return UserResource
     */
    public function show(Request $request, User $user)
    {
        $user->load($this->getAskedRelations($request));
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @param  FinderCollection $finders
     * @return UserResource
     * @throws
     */
    public function update(Request $request, User $user, FinderCollection $finders)
    {
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

    /**
     * @param User $user
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
