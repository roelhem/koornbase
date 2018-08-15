<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 06:49
 */

namespace App\Http\Resources\Api\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

trait HasStamps
{

    /**
     * Function that returns an array of the stamp-fields that were asked for by the stamps parameter.
     *
     * @param Request $request
     * @return array
     */
    protected function getAskedStampFields(Request $request) {
        $res = [
            'created_at' => false,
            'created_by' => false,
            'updated_at' => false,
            'updated_by' => false,
            'deleted_at' => false,
            'deleted_by' => false
        ];

        $stamps = $request->get('stamps', false);

        if($stamps === false || $stamps === 'false') {
            return $res;
        }

        if($stamps === null || $stamps === 'all' || $stamps === 'true') {
            return [
                'created_at' => true,
                'created_by' => true,
                'updated_at' => true,
                'updated_by' => true,
                'deleted_at' => true,
                'deleted_by' => true
            ];
        };

        $parts = explode(',',$stamps);

        foreach ($parts as $part) {
            if(array_key_exists($part, $res)) {
                $res[$part] = true;
            } elseif (starts_with($part, 'time')) {
                $res['created_at'] = true;
                $res['updated_at'] = true;
                $res['deleted_at'] = true;
            } elseif (starts_with($part, 'user')) {
                $res['created_by'] = true;
                $res['updated_by'] = true;
                $res['deleted_by'] = true;
            } elseif ($part === 'create' || $part === 'created') {
                $res['created_at'] = true;
                $res['created_by'] = true;
            } elseif ($part === 'update' || $part === 'updated') {
                $res['updated_at'] = true;
                $res['updated_by'] = true;
            } elseif ($part === 'delete' || $part === 'deleted') {
                $res['deleted_at'] = true;
                $res['deleted_by'] = true;
            }
        }

        return $res;
    }

    /**
     * Function that returns an array of the stamp-fields that were asked for by the stamps parameter.
     *
     * @param Request $request
     * @return array
     */
    protected function getShownStampFields(Request $request) {
        $res = $this->getAskedStampFields($request);

        if(!method_exists($this->resource, 'trashed') || !$this->resource->trashed()) {
            $res['deleted_at'] = false;
            $res['deleted_by'] = false;
        }

        return $res;
    }

    /**
     * Returns the names of the stamp fields as a ordered array.
     *
     * @param Request $request
     * @return array
     */
    protected function getShownStampFieldList(Request $request) {
        $shownStampFields = $this->getShownStampFields($request);
        $res = [];
        foreach ($shownStampFields as $fieldName => $shown) {
            if($shown) {
                $res[] = $fieldName;
            }
        }
        return $res;
    }

    /**
     * Returns the stamp fields as asked in the request.
     *
     * @param Request $request
     * @return mixed
     */
    protected function getStampFields(Request $request) {
        $res = [];

        foreach ($this->getShownStampFieldList($request) as $fieldName) {
            $res[$fieldName] = $this->resource->$fieldName;
        }

        return $this->mergeWhen(count($res) > 0, $res);
    }

}