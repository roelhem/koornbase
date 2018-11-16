<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-10-18
 * Time: 07:20
 */

namespace App\Actions\Models\Create;


use Roelhem\Actions\Actions\AbstractAction;
use App\Person;
use Roelhem\Actions\Contracts\ActionContextContract;

class CreatePersonAction extends AbstractAction
{

    protected $description = "Creates and saves a new Person.";

    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'name_first'    => ['required','string','max:255'],
            'name_middle'   => ['nullable','string','max:255'],
            'name_prefix'   => ['nullable','string','max:63'],
            'name_last'     => ['required','string','max:255'],
            'name_initials' => ['nullable','string','max:63'],
            'name_nickname' => ['nullable','string','max:63'],
            'birth_date'    => ['nullable','date','before:now'],
            'remarks'       => ['nullable','string'],
        ];
    }

    public function handle($validArgs = [], ?ActionContextContract $context = null)
    {
        return Person::create($validArgs);
    }

}