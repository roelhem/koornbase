<?php

namespace App;

use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacRoleAssignable;
use App\Services\Rbac\Traits\DefaultRbacAuthorizable;
use App\Traits\Rbac\HasChildRoles;
use App\Types\AvatarType;
use App\Enums\OAuthProviders;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

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
 * @property integer|null $person_id
 *
 * @property Person|null $person
 *
 * @property-read string|null $name_display
 * @property-read string|null $name_short
 * @property-read string|null $avatar_letters
 * @property-read AvatarType $avatar
 *
 * @inheritdoc
 */
class User extends Authenticatable implements RbacAuthorizable, RbacRoleAssignable
{
    use Notifiable;
    use HasApiTokens;
    use HasChildRoles, DefaultRbacAuthorizable;

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

        $query = $this->accounts()->whereNotNull('avatar');

        foreach (OAuthProviders::ordeningAvatar() as $provider) {
            $query->orderByRaw('"provider" = ? DESC', [$provider]);
        }
        $account = $query->first();
        if($account) {
            $res->image = $account->avatar;
        }

        return $res;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: RbacAuthorizable --------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    public function childRoles() {
        $select = [
            'roles.id',
            'roles.name',
            'roles.description',
            'roles.is_required',
            'roles.is_visible',
            'roles.created_at',
            'roles.updated_at',
            'roles.created_by',
            'roles.updated_by'
        ];

        $query = Role::query()->where('id','=', 'user')->select($select);
        $query->union($this->assignedRoles()->select($select));
        if($this->person) {
            $query->union($this->person->childRoles()->select($select));
        }
        return $query;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Person where this User belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

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


}
