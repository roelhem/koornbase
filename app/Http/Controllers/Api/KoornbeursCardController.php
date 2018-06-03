<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\KoornbeursCardResource;
use App\KoornbeursCard;
use App\Services\Finders\PersonFinder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KoornbeursCardController extends Controller
{

    /**
     * Prepares a KoornbeurCard instance to be send as a Json Resource.
     *
     * @param KoornbeursCard $card
     * @param Request $request
     * @return KoornbeursCardResource
     */
    public function prepare(KoornbeursCard $card, Request $request) {
        $card->load($this->getAskedRelations($request));
        return new KoornbeursCardResource($card);
    }

    /**
     * Action to show a list of KoornbeursCards
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request) {
        $query = KoornbeursCard::query();
        $query->with($this->getAskedRelations($request));

        return KoornbeursCardResource::collection($query->paginate());
    }

    /**
     * Action to store a new KoornbeursCard
     *
     * @param Request $request
     * @param PersonFinder $personFinder
     * @return KoornbeursCardResource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     * @throws \Throwable
     */
    public function store(Request $request, PersonFinder $personFinder) {
        $validatedData = $request->validate([
            'owner' => 'nullable|finds:App\Person',
            'ref' => 'required|string|unique:koornbeurs_cards|max:63',
            'version' => 'required|string|max:63',
            'remarks' => 'nullable|string',
            'activated_at' => 'nullable|date',
            'deactivated_at' => 'nullable|date'
        ]);

        $inputData = array_except($validatedData, ['owner']);

        if(array_get($validatedData, 'owner') !== null) {
            $owner = $personFinder->find($validatedData['owner']);
            $inputData['owner_id'] = $owner->id;
        } else {
            $inputData['owner_id'] = null;
        }

        $card = new KoornbeursCard($inputData);
        $card->saveOrFail();

        return $this->prepare($card, $request);
    }

    /**
     * Action to show one specific KoornbeursCard
     *
     * @param Request $request
     * @param KoornbeursCard $card
     * @return KoornbeursCardResource
     */
    public function show(Request $request, KoornbeursCard $card)
    {
        return $this->prepare($card, $request);
    }

    /**
     * Action to update the values one specific KoornbeursCard
     *
     * @param Request $request
     * @param KoornbeursCard $koornbeursCard
     * @param PersonFinder $personFinder
     * @return KoornbeursCardResource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     * @throws \Throwable
     */
    public function update(Request $request, KoornbeursCard $koornbeursCard, PersonFinder $personFinder) {
        $validatedData = $request->validate([
            'owner' => 'nullable|finds:App\Person',
            'ref' => ['sometimes','required','string','max:63',
                Rule::unique('koornbeurs_cards')->ignore($koornbeursCard->id)
            ],
            'version' => 'sometimes|required|string|max:63',
            'remarks' => 'nullable|string',
            'activated_at' => 'nullable|date',
            'deactivated_at' => 'nullable|date'
        ]);

        $inputData = array_except($validatedData, ['owner']);

        if(array_has($validatedData, 'owner')) {
            if (array_get($validatedData, 'owner') !== null) {
                $owner = $personFinder->find($validatedData['owner']);
                $inputData['owner_id'] = $owner->id;
            } else {
                $inputData['owner_id'] = null;
            }
        }

        $koornbeursCard->fill($inputData);
        $koornbeursCard->saveOrFail();

        return $this->prepare($koornbeursCard, $request);
    }

    /**
     * Deletes a KoornbeursCard
     *
     * @param KoornbeursCard $koornbeursCard
     * @throws \Exception
     */
    public function destroy(KoornbeursCard $koornbeursCard)
    {
        $koornbeursCard->delete();
    }


}
