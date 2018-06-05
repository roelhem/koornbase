<?php

namespace App\Http\Requests\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\Traits\FindsModels;
use App\Http\Requests\Api\Traits\HandlesValidation;
use App\Membership;
use App\Person;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MembershipUpdateRequest extends FormRequest
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
    public function afterValidation($validator)
    {
        $membership = $this->findFromUrl('membership');
        if(!($membership instanceof Membership)) { abort(404); }

        // Data parsen
        $data        = $validator->getData();
        $application = $this->parseDate(array_get($data, 'application', $membership->application));
        $start       = $this->parseDate(array_get($data, 'start',       $membership->start));
        $end         = $this->parseDate(array_get($data, 'end',         $membership->end));

        // Chronologie controleren
        $this->validateChronology($application, $start, $end, $validator);

        // Grootte van membership bepalen
        $lowerBound = $this->findLowerBound($application, $start, $end);
        $lowerBoundAttribute = $this->findLowerBoundAttribute($application, $start, $end);
        if($lowerBound === null) {
            $validator->errors()->add('application','Er is minstens één datum nodig in een lidmaatschap.');
            return;
        }
        $upperBound = $end;

        // Controlleren dat er geen overlap is met de andere memberships van deze persoon.
        foreach ($membership->person->memberships as $otherMembership) {
            if($otherMembership->id !== $membership->id) {

                $otherLowerBound = $this->findLowerBound(
                    $otherMembership->application,
                    $otherMembership->start,
                    $otherMembership->end
                );
                $otherUpperBound = $otherMembership->end;

                if(!$this->datesDistinct($lowerBound, $upperBound, $otherLowerBound, $otherUpperBound)) {
                    $validator->errors()->add(
                        $lowerBoundAttribute,
                        "Mag niet overlappen met de andere lidmaatschappen van deze persoon. (overlapt met {$otherMembership->id})"
                    );
                }
            }
        }

    }

}
