<?php

namespace App;


use App\Contracts\OwnedByPerson;
use App\Notifications\ResetPasswordNotification;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\BelongsToPerson;
use App\Types\AvatarType;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Traits\HasMorphedRbacAssignments;
use Wildside\Userstamps\Userstamps;

/**
 * Class User
 *
 * @package App
 *
 * @property integer|null $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property string|null $remember_token
 *
 *
 * @property-read string|null $name_display
 * @property-read string|null $name_short
 * @property-read string|null $avatar_letters
 * @property-read AvatarType $avatar
 *
 * @property-read Carbon $created_at
 * @property-read integer|null $created_by
 * @property-read Carbon $updated_at
 * @property-read integer|null $updated_by
 *
 * @method static User|null find(integer $id)
 *
 * @inheritdoc
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
        'name', 'email', 'password',
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
     * Returns two letters that can be used as a placeholder avatar of this user.
     *
     * @return string
     */
    public function getAvatarLettersAttribute() {
        if($this->person) {
            return $this->person->avatar_letters;
        } else {
            return mb_strtolower(substr(trim($this->name), 0, 2)).'.';
        }
    }

    /**
     * Returns an AvatarType that gives an avatar that can represent this user.
     *
     * @return AvatarType
     */
    public function getAvatarAttribute() {

        $res = new AvatarType;
        $res->letters = $this->avatar_letters;

        $account = $this->accounts()->whereNotNull('avatar')->get()
            ->sortByDesc(function(UserAccount $userAccount) {
                return $userAccount->provider->conf('ranking.avatar', 0);
            })->first();

        if($account) {
            $res->image = $account->avatar;
        }
        return $res;
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


}
