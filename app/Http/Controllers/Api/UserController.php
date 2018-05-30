<?php

namespace App\Http\Controllers\Api;

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
     * @return UserResource
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:users|string|max:255',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|string|min:8',
            'person_id' => 'sometimes|nullable|integer|exists:persons,id',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);

        if(array_key_exists('person_id', $validatedData)) {
            $user->person_id = $validatedData['person_id'];
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
     * @return UserResource
     * @throws
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => ['sometimes','required','string','max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['sometimes','required','email','max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|required|string|min:8',
            'person_id' => 'sometimes|nullable|integer|exists:person,id'
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

        if(array_has($validatedData, 'person_id')) {
            $user->person_id = $validatedData['person_id'];
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
