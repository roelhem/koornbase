<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\KoornbeursCardStoreRequest;
use App\Http\Requests\Api\KoornbeursCardUpdateRequest;
use App\KoornbeursCard;
use App\Person;
use Illuminate\Http\Resources\Json\JsonResource;

class KoornbeursCardController extends Controller
{

    protected $eagerLoadForShow = ['owner'];

    /**
     * Action to store a new KoornbeursCard
     *
     * @param KoornbeursCardStoreRequest $request
     * @param FinderCollection $finders
     * @return JsonResource
     * @throws
     */
    public function store(KoornbeursCardStoreRequest $request, FinderCollection $finders) {

        $data = $request->validated();
        $values = array_except($data, ['person']);

        $personInput = array_get($data, 'person');

        if($personInput === null) {
            $card = new KoornbeursCard($values);
        } else {
            /** @var Person $person */
            $person = $finders->find($personInput, 'person');
            $card = $person->cards()->make($values);
        }

        $card->saveOrFail();

        $card->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($card);
    }

    /**
     * Action to update the values one specific KoornbeursCard
     *
     * @param KoornbeursCardUpdateRequest $request
     * @param KoornbeursCard $card
     * @param FinderCollection $finders
     * @return JsonResource
     * @throws
     */
    public function update(KoornbeursCardUpdateRequest $request, KoornbeursCard $card, FinderCollection $finders) {

        $data = $request->validated();
        $values = array_except($data, ['person']);

        $card->fill($values);

        $personInput = array_get($data, 'person', false);
        if($personInput !== false) {
            if($personInput === null) {
                $card->owner()->dissociate();
            } else {
                /** @var Person $person */
                $person = $finders->find($personInput, 'person');
                $card->owner()->associate($person);
            }
        }

        $card->saveOrFail();

        $card->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($card);
    }


}
