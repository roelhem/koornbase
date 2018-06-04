<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 07:30
 */

namespace App\Services\Finders;


use App\Exceptions\Finders\ModelNotFoundException;
use App\KoornbeursCard;

class KoornbeursCardFinder extends ModelByIdFinder
{

    /**
     * GroupEmailAddressFinder constructor.
     */
    public function __construct()
    {
        parent::__construct('koornbeurs_card', KoornbeursCard::class);
    }

    /**
     * @inheritdoc
     */
    public function accepts($input): bool
    {
        if(parent::accepts($input)) {
            return true;
        }

        if(str_is('_*', $input) || str_is('*_*', $input)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function find($input)
    {
        if(parent::accepts($input)) {
            return parent::find($input);
        }

        $version = str_before($input, '_');
        $ref = str_after($input, '_');

        $query = KoornbeursCard::query()->where('ref','=',$ref);
        if(!empty($version)) {
            $query->where('version', '=', $version);
        }
        $model = $query->first();

        if($model instanceof KoornbeursCard) {
            return $model;
        } else {
            throw new ModelNotFoundException();
        }
    }
}