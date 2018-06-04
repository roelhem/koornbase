<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Resources\Api\KoornbeursCardResource;
use App\KoornbeursCard;
use App\Services\Sorters\KoornbeursCardSorter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KoornbeursCardController extends Controller
{

    protected $modelClass = KoornbeursCard::class;
    protected $resourceClass = KoornbeursCardResource::class;
    protected $sorterClass = KoornbeursCardSorter::class;

    /**
     * Action to store a new KoornbeursCard
     *
     * @param Request $request
     * @param FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     * @throws \Throwable
     */
    public function store(Request $request, FinderCollection $finders) {
        $validatedData = $request->validate([
            'owner' => 'nullable|finds:person',
            'ref' => 'required|string|unique:koornbeurs_cards|max:63',
            'version' => 'required|string|max:63',
            'remarks' => 'nullable|string',
            'activated_at' => 'nullable|date',
            'deactivated_at' => 'nullable|date'
        ]);

        $inputData = array_except($validatedData, ['owner']);

        if(array_get($validatedData, 'owner') !== null) {
            $owner = $finders->find($validatedData['owner'],'person');
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
     * @return Resource
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
     * @param FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     * @throws \Throwable
     */
    public function update(Request $request, KoornbeursCard $koornbeursCard, FinderCollection $finders) {
        $validatedData = $request->validate([
            'owner' => 'nullable|finds:person',
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
                $owner = $finders->find($validatedData['owner'],'person');
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
