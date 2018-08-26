<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 03:02
 */

namespace App\Http\GraphQL\Mutations\Crud\Update;



use App\CertificateCategory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdateCertificateCategoryMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updateCertificateCategory',
        'description' => 'Updates the values of an existing CertificateCategory',
    ];

    public function type()
    {
        return \GraphQL::type('CertificateCategory');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the CertificateCategory that you want to update.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:certificate_categories']
            ],
            'name' => [
                'description' => 'A new name for the CertificateCategory.',
                'type' => Type::string(),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'name_short' => [
                'description' => 'A new short version of the name of the CertificateCategory.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A new description for the CertificateCategory',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'default_expire_years' => [
                'description' => 'The mew default amount of years that a certificate of this category is valid.',
                'type' => Type::int(),
                'rules' => ['nullable','integer'],
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws ValidationError
     * @throws \Throwable
     */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        $category = CertificateCategory::findOrFail($id);

        $name = array_get($args, 'name');
        if($name !== null && $category->name !== $name && CertificateCategory::where('name','=',$name)->exists()) {
            throw new ValidationError('There already exists a CertificateCategory with the provided name.');
        }

        $category->fill($args);
        $category->saveOrFail();
        return $category;
    }

}