<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasRemarks;
use App\Traits\Person\HasAddresses;
use App\Traits\Person\HasEmailAddresses;
use App\Traits\Person\HasGroups;
use App\Traits\Person\HasMemberships;
use App\Traits\Person\HasName;
use App\Traits\Person\HasPhoneNumbers;
use App\Types\Avatar;
use App\Types\AvatarType;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Roelhem\RbacGraph\Contracts\Models\AuthorizableGroup;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Traits\HasMorphedRbacAssignments;
use App\Traits\Userstamps;

/**
 * Class Person
 *
 * @package App
 * @property integer $id
 * @property Carbon|null $birth_date
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 * @property Carbon|null $deleted_at
 * @property integer|null $deleted_by
 * @property string $name_initials
 * @property-read string $avatar_letters
 * @property-read AvatarType $avatar
 * @property-read integer|null $age
 * @property-read Collection $users
 * @property-read Collection $debtors
 * @property-read Collection $cards
 * @property-read Collection $certificates
 * @property-read \App\PersonAddress $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PersonAddress[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Node[] $assignedNodes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Assignment[] $assignments
 * @property-read \App\PersonEmailAddress $emailAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PersonEmailAddress[] $emailAddresses
 * @property-read \MembershipStatus $membership_status
 * @property-read \Carbon|null $membership_status_since
 * @property-read string $name
 * @property-read string $name_formal
 * @property-read string $name_full
 * @property-read string $name_short
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Helpers\MembershipStatusChange[] $membershipStatusChanges
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Membership[] $memberships
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Person $owner
 * @property-read \App\PersonPhoneNumber $phoneNumber
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PersonPhoneNumber[] $phoneNumbers
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person filter($input = array(), $filter = null)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person formerMember($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person member($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person membershipStatus($status, $at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person novice($at = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Person onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person outsider($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder|\App\Person withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Person withoutTrashed()
 * @mixin \Eloquent
 */
class Person extends Model implements RbacDatabaseAssignable, AuthorizableGroup, OwnedByPerson
{

    use SoftDeletes;
    use Userstamps;
    use Filterable, Sortable, Searchable;

    use HasRemarks;

    use Notifiable;

    use HasName, HasMemberships, HasAddresses, HasPhoneNumbers, HasEmailAddresses, HasGroups;
    use HasMorphedRbacAssignments;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'persons';

    protected $dates = ['birth_date','created_at','updated_at','deleted_at'];

    protected $fillable = ['name_first','name_middle','name_prefix','name_last','name_initials','name_nickname', 'birth_date','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the day when this person turns the given age.
     *
     * When $age is omitted, the next birthday of this person is returned. If no birth_date was found in this
     * Person, null will be returned.
     *
     * @param integer|null $age
     * @return Carbon|null
     */
    public function getBirthDay($age = null) {
        if($this->birth_date === null) {
            return null;
        }

        if($age === null) {
            $age = $this->birth_date->age + 1;
        }

        $result = clone $this->birth_date;
        $result->addYears($age);

        return $result;
    }

    /**
     * Returns the age of this person at the given moment.
     *
     * @param Carbon|string|null $at
     * @return integer|null
     */
    public function getAge($at = null) {
        $birth_date = $this->birth_date;
        if($birth_date === null) {
            return null;
        } else {
            return $birth_date->diffInYears($at, false);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns an Avatar object for this Person
     *
     * @return Avatar
     */
    public function getAvatarAttribute() {
        return Avatar::createForPerson($this);
    }

    /**
     * Returns the age of this Person if the birth date is known.
     *
     * @return integer|null
     */
    public function getAgeAttribute() {
        return $this->getAge();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the accounts of this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->hasMany(User::class, 'person_id');
    }

    /**
     * Gives all the Debtors that were assigned to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debtors() {
        return $this->hasMany(Debtor::class,'person_id');
    }

    /**
     * Gives the primary KoornbeursCard that belong to this Person.
     */
    public function activeCards() {
        return $this->cards()->active();
    }

    /**
     * Gives all the KoornbeursCards that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards() {
        return $this->hasMany(KoornbeursCard::class, 'owner_id')
            ->orderByDesc('deactivated_at')
            ->orderByDesc('activated_at')
            ->orderByDesc('created_at');
    }

    /**
     * Gives all the Certificates that belong to this CertificateCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certificates() {
        return $this->hasMany(Certificate::class, 'person_id');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: AuthorizableGroup ------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function getAuthorizables()
    {
        return $this->users;
    }

    /** @inheritdoc */
    public function getAuthorizableGroups()
    {
        return $this->groups;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTS: OwnedByPerson -------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function owner()
    {
        return $this->belongsTo(Person::class, 'id','id');
    }

    /** @inheritdoc */
    public function getOwner()
    {
        return $this;
    }

    /** @inheritdoc */
    public function getOwnerId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     * @param Builder $query
     */
    public function scopeOwnedBy($query, $person_id)
    {
        return $query->where('id','=',$person_id);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOUT SEARCHABLE CONFIGURATION --------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function toSearchableArray()
    {
        return $this->only([
            'id',
            'name_first',
            'name_middle',
            'name_prefix',
            'name_last',
            'name_initials',
            'name_nickname'
        ]);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- NOTIFIABLE CONFIGURATION --------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function routeNotificationForMail($notification = null)
    {
        $personEmailAddress = $this->emailAddress;
        if($personEmailAddress !== null) {
            return $personEmailAddress->email_address;
        }

        $user = $this->users()->first();
        if($user instanceof User) {
            return $user->email;
        }

        return 'unknown@koornbase.test';
    }

}
