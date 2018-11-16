<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 13:41
 */

namespace App\Actions\Models\Create;


use Roelhem\Actions\Actions\AbstractAction;
use App\GroupCategory;
use Roelhem\Actions\Contracts\ActionContextContract;

class CreateGroupAction extends AbstractAction
{

    /** @inheritdoc */
    public function handle($validArgs = [], ?ActionContextContract $context = null)
    {
        $category_id = array_get($validArgs, 'category_id');
        /** @var GroupCategory $category */
        $category = GroupCategory::findOrFail($category_id);

        return $category->groups()->create(array_except($validArgs, ['category_id']));
    }


    public function authorize()
    {
        return false;
    }

    /**
     * Returns an array of all the validation rules of this action.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => ['required','exists:group_categories,id'],
            'name' => ['required','string','max:255','unique:groups'],
            'name_short' => ['nullable','string','max:63'],
            'member_name' => ['nullable','string','max:255'],
            'description' => ['nullable','string']
        ];
    }
}