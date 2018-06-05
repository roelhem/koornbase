<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\PersonPhoneNumberResource;
use App\Http\Resources\Api\Resource;
use App\PersonPhoneNumber;
use Illuminate\Http\Request;

class PersonPhoneNumberController extends Controller
{

    protected $modelClass = PersonPhoneNumber::class;
    protected $resourceClass = PersonPhoneNumberResource::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PersonPhoneNumber  $personPhoneNumber
     * @return Resource
     */
    public function show(Request $request, PersonPhoneNumber $personPhoneNumber)
    {
        return $this->prepare($personPhoneNumber, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PersonPhoneNumber  $personPhoneNumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PersonPhoneNumber $personPhoneNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PersonPhoneNumber  $personPhoneNumber
     * @throws
     */
    public function destroy(PersonPhoneNumber $personPhoneNumber)
    {
        $personPhoneNumber->delete();
    }
}
