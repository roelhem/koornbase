<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 14:55
 */

namespace App\Traits\PersonContactEntry;

/**
 * Class HasIndex
 * @package App\Traits\PersonContactEntry
 *
 * @property integer $index
 */
trait OrderableWithIndex
{

    /**
     * Defines some event handlers.
     */
    public static function bootOrderableWithIndex() {

        static::creating(function($model) {
            if($model->index === null) {
                $model->index = static::query()->where('person_id', $model->person_id)->count();
            }
        });

        static::deleted(function($model) {
            static::refreshModelIndexes($model->person_id);
        });
    }

    /**
     * Re-calculates the indexes off all the models that belong to the person with $person_id.
     *
     * @param $person_id
     * @throws
     */
    public static function refreshModelIndexes($person_id) {
        $models = static::query()->where('person_id', $person_id)->orderBy('index')->get();

        if(count($models) > 0) {
            \DB::transaction(function () use ($models) {
                foreach ($models->values() as $index => $model) {
                    $model->index = $index;
                    $model->saveOrFail();
                }
            });
        }
    }


    /**
     * Swaps the position of this model with the model at the given $otherIndex.
     *
     * @param int $otherIndex
     * @throws
     */
    public function swapWithIndex(int $otherIndex) {

        $thisModel = $this;
        $otherModel = static::query()->where('person_id',$this->person_id)->where('index', $otherIndex)->firstOrFail();

        \DB::transaction(function() use ($thisModel, $otherModel, $otherIndex) {
            $thisIndex = $this->index;
            $thisModel->index = $otherIndex;
            $otherModel->index = $thisIndex;
            $thisModel->saveOrFail();
            $otherModel->saveOrFail();
        });
    }

    /**
     * Moves the model to the position of the given index.
     *
     * @param int $toIndex
     * @throws \Exception
     * @throws \Throwable
     */
    public function moveToIndex(int $toIndex) {

        // Get the models.
        $models = static::query()->where('person_id', $this->person_id)->orderBy('index')->get();

        if($toIndex < count($models)) {
            $models->splice($this->index, 1);
            $models->splice($toIndex, 0, [$this]);

            \DB::transaction(function() use ($models) {
                foreach ($models->values() as $index => $model) {
                    $model->index = $index;
                    $model->saveOrFail();
                }
            });
        }
    }

}