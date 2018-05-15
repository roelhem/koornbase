<?php

namespace App;

use App\Enums\OAuthProviders;
use App\Traits\HasAssignedRoles;
use Illuminate\Database\Eloquent\Builder;
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
 * @property-read string|null $avatar
 *
 * @inheritdoc
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasAssignedRoles;
    use HasApiTokens;

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
     * Returns an url to an image that can be used as an avatar picture of this user.
     *
     * @return string
     */
    public function getAvatarAttribute() {
        $query = $this->accounts()->whereNotNull('avatar');

        foreach (OAuthProviders::ordeningAvatar() as $provider) {
            $query->orderByRaw('"provider" = ? DESC', [$provider]);
        }

        $account = $query->first();

        if($account) {
            return $account->avatar;
        } else {
            return null;
        }
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
     * Gives the Roles that were directly assigned to this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignedRoles()
    {
        return $this->belongsToMany(Role::class, 'user_role',
            'user_id','role_id');
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
        return $this->hasOne(UserAccount::class, 'user_id')->where('provider','=','facebook');
    }

    /**
     * Gives the github account of this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function githubAccount() {
        return $this->hasOne(UserAccount::class, 'user_id')->where('provider','=','github');
    }

    /**
     * Gives the google account of this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function googleAccount() {
        return $this->hasOne(UserAccount::class, 'user_id')->where('provider','=','google');
    }

    /**
     * Gives the twitter account of this User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function twitterAccount() {
        return $this->hasOne(UserAccount::class, 'user_id')->where('provider','=','twitter');
    }
}
