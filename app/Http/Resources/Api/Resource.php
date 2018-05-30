<?php

namespace App\Http\Resources\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MergeValue;
use Illuminate\Http\Resources\MissingValue;

class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * Returns an array that should be added to the end of every array.
     *
     * @param $request
     * @return array
     */
    public function tailArray($request) {
        if($request->query('metaFieldsGrouped', false) !== false) {
            return [
                '_meta' => $this->getMetaFields($request),
            ];
        } else {
            return $this->getMetaFields($request);
        }
    }

    /**
     * Returns an array with all the fields that were requested by the metaFields parameter.
     *
     * @param $request
     * @return array
     */
    protected function getMetaFields($request) {
        $metaFields = $request->query('metaFields', []);

        if(is_string($metaFields)) {
            $metaFields = explode(',', $metaFields);
        }

        $resource = $this->resource;
        $res = [];

        if(in_array('_className', $metaFields)) {
            $res['_className'] = get_class($resource);
        }

        if($resource instanceof Model) {
            if(in_array('_tableName', $metaFields)) {
                $res['_tableName'] = $resource->getTable();
            }
            if(in_array('_singularName', $metaFields)) {
                $res['_singularName'] = str_singular($resource->getTable());
            }
            if(in_array('_primaryKey', $metaFields) || in_array('_primaryKeyName', $metaFields)) {
                $res['_primaryKeyName'] = $resource->getKeyName();
            }
            if(in_array('_primaryKey', $metaFields) || in_array('_primaryKeyType', $metaFields)) {
                $res['_primaryKeyType'] = $resource->getKeyType();
            }
        }

        if(in_array('_created', $metaFields) || in_array('_created_at', $metaFields)) {
            $res['_created_at'] = $this->created_at;
        }
        if(in_array('_created', $metaFields) || in_array('_created_by', $metaFields)) {
            $res['_created_by'] = $this->created_by;
        }
        if(in_array('_updated', $metaFields) || in_array('_updated_at', $metaFields)) {
            $res['_updated_at'] = $this->updated_at;
        }
        if(in_array('_updated', $metaFields) || in_array('_updated_by', $metaFields)) {
            $res['_updated_by'] = $this->updated_by;
        }

        return $res;
    }
}
