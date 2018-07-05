<?php

namespace App\Http\Resources\Api;

use App\Types\OptionsType;
use Carbon\Carbon;
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
            'slug' => $this->when($this->slug, $this->slug),
        ];
    }

    /**
     * Returns the options attribute in the format asked by in the url parameter.
     *
     * @param $request
     * @return array|mixed|\stdClass
     */
    public function getOptions($request) {
        if ($this->options instanceof OptionsType) {
            if($this->queryHas('optionDefaults', $request) &&
                !in_array($request->query('optionDefaults'), ['hide','hidden','false','f'])
            ) {
                $res = $this->options->getAll();
            } else {
                $res = $this->options->getExplicit();
            }

            if(count($res) === 0) {
                return new \stdClass();
            } else {
                return $res;
            }
        }
        return $this->options;
    }

    /**
     * Returns if the query has a specific parameter.
     *
     * @param $param
     * @param $request
     * @return bool
     */
    public function queryHas($param, $request) {
        return $request->query($param, false) !== false;
    }

    /**
     * Returns an array that should be added to the end of every array.
     *
     * @param $request
     * @return array
     */
    public function tailArray($request) {
        $res = [];
        $res = $res + $this->getOptionalFields($request);

        $res['remarks'] = $this->when($this->remarks !== null, $this->remarks);
        $res['is_required'] = $this->when($this->is_required, true);

        $res['creator'] = new UserResource($this->whenLoaded('creator'));
        $res['editor'] = new UserResource($this->whenLoaded('editor'));

        if($this->queryHas('metaFieldsGrouped', $request)) {
            $res = $res + [
                '_meta' => $this->getMetaFields($request),
            ];
        } else {
            $res = $res + $this->getMetaFields($request);
        }

        return $res;
    }

    /**
     * Returns an array of optional fields that are activated by the fields parameter.
     *
     * @param Request $request
     * @return array
     * @throws
     */
    protected function getOptionalFields($request) {
        $fields = $request->query('fields', []);

        // Return every optional fields
        if($fields === '*') {
            $res = [];
            $reflection = new \ReflectionClass($this);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                $shortName = $method->getShortName();
                if(starts_with($shortName,'field' )) {
                    $attributeName = snake_case(str_after($shortName, 'field'));
                    $res[$attributeName] = $method->invoke($this, $request);
                }
            }
            return $res;
        }


        // Return the fields that were specifically asked for.
        if(is_string($fields)) {
            $fields = explode(',', $fields);
        }

        $res = [];
        foreach ($fields as $field) {
            $method = 'field'.ucfirst(camel_case($field));
            if(method_exists($this, $method)) {
                $res[snake_case($field)] = $this->$method($request);
            }
        }
        return $res;
    }

    public function fieldCreatedAt() {
        return $this->created_at;
    }

    public function fieldCreatedBy() {
        return $this->created_by;
    }

    public function fieldUpdatedAt() {
        return $this->updated_at;
    }

    public function fieldUpdatedBy() {
        return $this->updated_by;
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



            if(in_array('_canView', $metaFields)) {
                $res['_canView'] = \Gate::allows('view', $resource);
            }
            if(in_array('_canUpdate', $metaFields)) {
                $res['_canUpdate'] = \Gate::allows('update', $resource);
            }
            if(in_array('_canDelete', $metaFields)) {
                $res['_canDelete'] = \Gate::allows('delete', $resource);
            }

        }

        return $res;
    }

    /**
     * Formats a date in the right format
     *
     * @param Carbon|null $input
     * @param Request $request
     * @return string|null
     */
    protected function formatDate($input, $request) {
        if($input instanceof Carbon) {
            return $input->toDateString();
        }
        return null;
    }
}
