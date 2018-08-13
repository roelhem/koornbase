<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-07-18
 * Time: 21:48
 */

namespace App\ModelFilters;


use App\Enums\OAuthClientType;
use Illuminate\Database\Eloquent\Builder;
use MabeEnum\EnumSet;

class ClientFilter extends ModelFilter
{

    /**
     * Filters only the clients that are revoked.
     *
     * @param bool $state
     */
    public function revoked($state = true)
    {
        $this->where('revoked','=',boolval($state));
    }

    /**
     * Filters the clients of the provided type
     *
     * @param OAuthClientType|integer $type
     */
    public function type($type)
    {
        $type = OAuthClientType::get($type);

        switch ($type->getValue()) {
            case OAuthClientType::PERSONAL:
                $this->where('personal_access_client','=',true);
                break;
            case OAuthClientType::PASSWORD:
                $this->where('password_client','=',true);
                break;
            case OAuthClientType::CREDENTIALS:
                $this->where('personal_access_client', '=', false)
                    ->where('password_client','=',false)
                    ->where('redirect','=','');
                break;
            case OAuthClientType::AUTH_CODE:
                $this->where('personal_access_client', '=', false)
                    ->where('password_client','=',false)
                    ->where('redirect','<>','');
                break;
        }
    }

    /**
     * Filters the clients that have one of the types in the provided array
     *
     * @param array $types
     */
    public function anyType($types)
    {
        $set = new EnumSet(OAuthClientType::class);
        foreach ($types as $type) {
            $set->attach($type);
        }


        $this->where(function($query) use ($set) {
            /** @var Builder $query */
            $query->whereRaw('FALSE');

            if($set->contains(OAuthClientType::PERSONAL)) {
                $query->orWhere('personal_access_client','=',true);
            }

            if($set->contains(OAuthClientType::PASSWORD)) {
                $query->orWhere('password_client', '=', true);
            }

            if($set->contains(OAuthClientType::CREDENTIALS) || $set->contains(OAuthClientType::AUTH_CODE)) {
                $query->orWhere(function($query) use ($set) {
                    $query->where('personal_access_client', '=', false);
                    $query->where('password_client','=',false);

                    if(!$set->contains(OAuthClientType::CREDENTIALS)) {
                        $query->where('redirect','<>','');
                    }

                    if(!$set->contains(OAuthClientType::AUTH_CODE)) {
                        $query->where('redirect','=','');
                    }

                    return $query;
                });
            }

            return $query;
        });
    }

    /**
     * Filters the clients that have a name that contains the provided string.
     *
     * @param string $searchString
     */
    public function search($searchString)
    {
        if(!empty($searchString)) {
            $this->where('name','ILIKE','%'.$searchString.'%');
        }
    }

}