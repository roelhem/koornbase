<?php

namespace App\Http\Requests\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\Person;
use Carbon\Carbon;
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
        return true;
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

        // Collect and parse the input data
        $data        = $validator->getData();
        $application = $this->parseDate(array_get($data,'application'));
        $start       = $this->parseDate(array_get($data, 'start'));
        $end         = $this->parseDate(array_get($data, 'end'));

        $person = $this->findFromInput('person', $data);
        if(!($person instanceof Person)) { return; }

        $this->validateChronology($application, $start, $end, $validator);

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
