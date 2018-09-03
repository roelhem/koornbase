<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 01:58
 */

namespace App\Events\GraphQL;


use App\OAuth\Token;
use App\Services\GraphQL\Operation;
use App\Services\GraphQL\QueryInput;
use App\User;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;

class OperationExecuted
{

    use SerializesModels;

    /** @var Carbon */
    public $emitDate;

    public $data;

    /**
     * OperationExecuted constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->emitDate = Carbon::now();
        $this->data = $data;
    }
}