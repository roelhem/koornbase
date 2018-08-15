<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\Membership;
use App\Person;
use App\Services\Validators\AfterValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MembershipStoreRequest extends FormRequest
{
    use MembershipCommonMethods, HandlesValidation, FindsModels;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Membership::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'person' => 'required|finds:person',
            'application' => 'nullable|date',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'remarks' => 'nullable|string'
        ];
    }

    /**
     * Called after the validation, to add some more
     *
     * @param Validator $validator
     */
    public function afterValidation($validator) {

        $after = new AfterValidation($validator);
        $after->ensureChronology(['application','start','end']);

        // Collect and parse the input data
        $application = \Parse::try()->date($after->getValue('application'));
        $start       = \Parse::try()->date($after->getValue('start'));
        $end         = \Parse::try()->date($after->getValue('end'));

        /** @var Person $person */
        $person = $this->findFromInput('person', $validator->getData());

        // Determine the lower- and upper bounds of the whole membership
        $lowerBound = $this->findLowerBound($application, $start, $end);
        $lowerBoundAttribute = $this->findLowerBoundAttribute($application, $start, $end);
        if($lowerBound === null) {
            $validator->errors()->add('application','Er moet minstens één van de 3 datums worden ingevuld.');
            return;
        }

        $upperBound = $end;

        // Check if this membership does not overlap another existing membership of the person
        $otherMemberships = $person->memberships()->get();
        foreach($otherMemberships as $otherMembership) {

            $otherLowerBound = $this->findLowerBound(
                $otherMembership->application,
                $otherMembership->start,
                $otherMembership->end
            );
            $otherUpperBound = $otherMembership->end;

            if(!$this->datesDistinct($lowerBound, $upperBound, $otherLowerBound, $otherUpperBound)) {
                $validator->errors()->add(
                    $lowerBoundAttribute,
                    "De opgegeven datum van dit lidmaatschap overlappen met een ander lidmaatschap [{$otherMembership->id}] van deze persoon."
                );
            }
        }
    }
}
