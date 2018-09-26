<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 21:14
 */

namespace Roelhem\GraphQL\Paginators;




use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;


class CursorPattern extends Fluent
{

    /**
     * Creates a new Cursor based on the provided model, and according to this pattern.
     *
     * @param Model $model
     * @return Cursor
     */
    public function createFromModel($model)
    {
        $attributes = $model->only(array_keys($this->attributes));
        return new Cursor($attributes);
    }


    /**
     * Packs all the data of a cursor into one string according to this pattern.
     *
     * @param Cursor $cursor
     * @return string
     */
    public function pack(Cursor $cursor) {
        $data = [];
        $pattern = '';
        foreach ($this->attributes as $field => $type) {
            if($type === 'datetime') {
                $pattern .= 'i';
                $value = $cursor->get($field);
                if(!($value instanceof Carbon)) {
                    throw new \UnexpectedValueException("Expected the value of a DateTime type to be an instance of ".Carbon::class);
                }
                $data[] = $value->timestamp;
            } else {
                $pattern .= $type;
                $data[]   = $cursor->get($field);
            }
        }
        return pack($pattern, ...$data);
    }

    /**
     * Gives a string that represent the cursor and can be shown in the API.
     *
     * @param Cursor $cursor
     * @return string
     */
    public function serialize(Cursor $cursor) {
        $packedString = $this->pack($cursor);
        return base64_encode($packedString);
    }

    /**
     * Turns the serialized string back into an Cursor.
     *
     * @param string $string
     * @return Cursor
     */
    public function deserialize(string $string) {
        $packedString = base64_decode($string);
        return $this->unpack($packedString);
    }

    /**
     * Unpacks a packed string back into a Cursor.
     *
     * @param string $string
     * @return Cursor
     */
    public function unpack(string $string) {
        // Finding the right pattern
        $pieces = [];
        foreach ($this->attributes as $field => $type) {
            if($type === 'datetime') {
                $pieces[] = 'i'.$field;
            } else {
                $pieces[] = $type.$field;
            }
        }
        $pattern = implode('/', $pieces);

        // Unpacking the data-string
        $data = unpack($pattern, $string);

        // Cleaning up the data.
        foreach ($this->attributes as $field => $type) {
            if($type === 'datetime') {
                $timestamp = $data[$field];
                $data[$field] = Carbon::createFromTimestamp($timestamp);
            }
        }

        // Returning the response
        return new Cursor($data);
    }
}