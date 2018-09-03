<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 04:19
 */

namespace App\Logs;


use App\Enums\GraphQLOperationType;
use App\Events\GraphQL\OperationExecuted;
use App\OAuth\Client;
use App\OAuth\Token;
use App\Services\GraphQL\Operation;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogGraphQLOperation
 * @package App\Logs
 *
 * @property integer $id
 * @property string $schema
 * @property string $type_name
 * @property GraphQLOperationType $type
 * @property string|null $operation_name
 * @property string $query
 * @property array $variables
 *
 * @property integer|null $user_id
 * @property integer|null $client_id
 * @property string|null $access_token_id
 *
 * @property Carbon $requested_at
 * @property-read Carbon|null $created_at
 *
 * @property-read User|null $user
 * @property-read Client|null $client
 * @property-read Token|null $token
 *
 * @method static LogGraphQLOperation create(array $attributes)
 */
class LogGraphQLOperation extends Model
{
    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'log_graphql_operations';

    protected $dates = ['requested_at','created_at'];

    protected $fillable = [
        'schema','type','operation_name','query','variables','user_id','client_id','access_token_id','requested_at'
    ];

    protected $casts = [
        'variables' => 'array'
    ];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the name of the type of this logged operation.
     *
     * @return string
     */
    public function getTypeName()
    {
        return array_get($this->attributes,'type', GraphQLOperationType::getDefault()->getValue());
    }

    /**
     * Returns the type of this logged operation.
     *
     * @return GraphQLOperationType
     */
    public function getType()
    {
        return GraphQLOperationType::get($this->getTypeName());
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the name of the type of this logged operation.
     *
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return $this->getTypeName();
    }

    /**
     * Gives the type of this logged operation.
     *
     * @return GraphQLOperationType
     */
    public function getTypeAttribute()
    {
        return $this->getType();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Sets the type of this logged operation.
     *
     * @param GraphQLOperationType|string $newType
     */
    public function setType($newType)
    {
        $type = GraphQLOperationType::get($newType);
        $this->attributes['type'] = $type->getValue();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Sets the name of the type of this logged operation.
     *
     * @param GraphQLOperationType|string $newValue
     */
    public function setTypeNameAttribute($newValue)
    {
        $this->setType($newValue);
    }

    /**
     * Sets the type of this logged operation.
     *
     * @param GraphQLOperationType|string $newValue
     */
    public function setTypeAttribute($newValue)
    {
        $this->setType($newValue);
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the user that requested this logged operation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * Gives the OAuth-client trough which the user requested this logged operation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    /**
     * Gives the OAuth-token trough which the user requested this logged operation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function token()
    {
        return $this->belongsTo(Token::class, 'token_id');
    }
}