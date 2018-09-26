<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 22-05-18
 * Time: 07:53
 */

namespace App\Types;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class AvatarType
 *
 * This type models all the information needed to display an avatar at the frontend.
 *
 * @package App\Types
 */
class AvatarType implements Arrayable, Jsonable
{

    public $type;

    public $image;

    public $letters;

    public $icon;

    public $placeholder = false;

    public $color;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: Arrayable ---------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $res = [];

        if($this->type !== null) {
            $res['type'] = \App\Enums\AvatarType::get($this->type);
        }

        if($this->image !== null) {
            $res['image'] = strval($this->image);
        }

        if($this->letters !== null) {
            $res['letters'] = strval($this->letters);
        }

        if($this->icon !== null) {
            $res['icon'] = strval($this->icon);
        }

        if($this->placeholder) {
            $res['placeholder'] = true;
        }

        if($this->color) {
            $res['color'] = $this->color;
        }

        return $res;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: Jsonable ----------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }


}