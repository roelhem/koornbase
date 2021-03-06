<?php

namespace App;


use App\Contracts\OwnedByPerson;
use App\Notifications\ResetPasswordNotification;
use App\OAuth\Token;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\BelongsToPerson;
use App\Types\Avatar;
use App\Types\AvatarType;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Traits\HasMorphedRbacAssignments;
use App\Traits\Userstamps;
use App\Enums\AvatarType as AvatarTypeEnum;

/**
 * Class User
 *
 * @package App
 * @property integer|null $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property-read string|null $name_display
 * @property-read string|null $name_short
 * @property-read string|null $avatar_letters
 * @property-read AvatarType $avatar
 * @property-read Carbon $created_at
 * @property-read integer|null $created_by
 * @property-read Carbon $updated_at
 * @property-read integer|null $updated_by
 * @method static User|null find(integer $id)
 * @inheritdoc 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserAccount[] $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Node[] $assignedNodes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Assignment[] $assignments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OAuth\Client[] $clients
 * @property-read \App\UserAccount $facebookAccount
 * @property-read \App\UserAccount $githubAccount
 * @property-read \App\UserAccount $googleAccount
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Person $owner
 * @property-read \App\Person $person
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OAuth\Token[] $tokens
 * @property-read \App\UserAccount $twitterAccount
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLike($column, $value, $boolean = 'and')
 * @mixin \Eloquent
 */
class User extends Authenticatable implements RbacDatabaseAssignable, OwnedByPerson
{
    use Notifiable;
    use HasApiTokens;
    use HasMorphedRbacAssignments;
    use BelongsToPerson;
    use Filterable, Sortable, Searchable;
    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','person_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $eagerLoad = ['person'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Gives a string that can be displayed. It contains a description of the name of this user.
     *
     * @return string
     */
    public function getNameDisplayAttribute() {
        if($this->person) {
            return $this->person->name;
        } else {
            return $this->name;
        }
    }

    /**
     * Gives a short string that can be displayed. It contains a description of the name of this user.
     *
     * @return string
     */
    public function getNameShortAttribute() {
        if($this->person) {
            return $this->person->name_short;
        } else {
            return $this->name;
        }
    }

    /**
     * Returns an AvatarType that gives an avatar that can represent this user.
     *
     * @return Avatar
     */
    public function getAvatarAttribute()
    {
        return Avatar::createForUser($this);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Sets the password of the user to a new value. It will be encrypted right away.
     *
     * @param string $newValue
     */
    public function setPasswordAttribute($newValue) {
        $this->attributes['password'] = bcrypt($newValue);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the UserAccounts that belong to this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(UserAccount::class, 'user_id');
    }

    /**
     * Gives the Tokens (OAuth) that were issued to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_id');
    }

    /**
     * Gives the facebook account of this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function facebookAccount() {
        return $this->hasOne(UserAccount::class, 'user_id')
                    ->where('provider','=','facebook');
    }

    /**
     * Gives the github account of this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function githubAccount() {
        return $this->hasOne(UserAccount::class, 'user_id')
                    ->where('provider','=','github');
    }

    /**
     * Gives the google account of this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function googleAccount() {
        return $this->hasOne(UserAccount::class, 'user_id')
                    ->where('provider','=','google');
    }

    /**
     * Gives the twitter account of this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function twitterAccount() {
        return $this->hasOne(UserAccount::class, 'user_id')
                    ->where('provider','=','twitter');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: Authorizable ----------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getAuthorizableGroups()
    {
        if($this->person === null) {
            return collect([]);
        } else {
            return collect([$this->person]);
        }
    }

    /**
     * @inheritdoc
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOUT SEARCHABLE CONFIGURATION --------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function toSearchableArray()
    {
        return $this->only([
            'id',
            'name',
            'email',
            'name_display'
        ]);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- NOTIFICATIONS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/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';
    }

}
