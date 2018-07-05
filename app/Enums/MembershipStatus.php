<?php

namespace App\Enums;

use App\Enums\Traits\HasConfigFile;
use App\Membership;
use App\Person;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use MabeEnum\Enum;
use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Rules\DynamicRole;
use Roelhem\RbacGraph\Enums\NodeType;
use Symfony\Component\Yaml\Yaml;

/**
 * Class MembershipStatus
 *
 * The different membership status that a Person can have.
 *
 * @package App\Enums
 *
 * @method static MembershipStatus OUTSIDER()
 * @method static MembershipStatus NOVICE()
 * @method static MembershipStatus MEMBER()
 * @method static MembershipStatus FORMER_MEMBER()
 *
 * @property-read string $label
 * @property-read string|null $description
 * @property-read array $style
 * @property-read integer $value
 */
final class MembershipStatus extends Enum implements DynamicRole, \JsonSerializable
{

    use HasConfigFile;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ENUM-VALUE DEFINITIONS  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    const OUTSIDER = 0;
    const NOVICE = 1;
    const MEMBER = 2;
    const FORMER_MEMBER = 3;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  USING THE CONFIG-FILE  ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Returns the parsed content of the config file.
     *
     * @return array
     */
    protected static function parseFile()
    {
        $filename = str_replace('.php','.yaml',__FILE__);
        return Yaml::parseFile($filename);
    }

    /**
     * Returns the default config-file. This is used for the implementation of the magic methods.
     *
     * @return array
     */
    protected function defaultConfig()
    {
        return [
            'title' => $this->getCamelCaseName(),
            'description' => null,
            'label' => $this->getCamelCaseName(),
            'dynamicRole' => [
                'name' => 'MembershipStatus.'.$this->getCamelCaseName(),
                'title' => 'Lidstatus: '.$this->getCamelCaseName(),
                'description' => null
            ],
            'style' => [
                'bootstrap' => 'gray',
            ]
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  MEMBERSHIP HELPERS  ---------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the timestamp of this element if it was the status of the provided $membership.
     *
     * @param Membership $membership
     * @return Carbon|null
     */
    public function getTimestamp(Membership $membership) {
        switch ($this->value) {
            case MembershipStatus::FORMER_MEMBER: return $membership->end;
            case MembershipStatus::MEMBER: return $membership->start;
            case MembershipStatus::NOVICE: return $membership->application;
            case MembershipStatus::OUTSIDER:
            default: return null;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  FORMATTING GETTER-METHODS  --------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns a bootstrap background-class of this MembershipStatus.
     *
     * @return string
     */
    public function getBackgroundClass() {
        return "bg-{$this->val('style.bootstrap')}";
    }

    /**
     * Returns dynamic role of this MembershipStatus.
     *
     * @return \Roelhem\RbacGraph\Contracts\Nodes\Node
     */
    public function getNode() {
        return \Rbac::get($this->defaultNodeName())->getNode();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  IMPLEMENTS Arrayable  -------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'title' => $this->val('title'),
            'label' => $this->val('label'),
            'value' => $this->getValue(),
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  IMPLEMENTS DynamicRole  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function constructor()
    {
        return self::class.'::'.$this->name;
    }

    /** @inheritdoc */
    public function constructorArguments()
    {
        return null;
    }

    /** @inheritdoc */
    public function defaultNodeName()
    {
        return $this->val('dynamicRole.name');
    }

    /** @inheritdoc */
    public function defaultNodeTitle()
    {
        return $this->val('dynamicRole.title');
    }

    /** @inheritdoc */
    public function defaultNodeDescription()
    {
        return $this->val('dynamicRole.description');
    }

    /** @inheritdoc */
    public function forAuthorizableTypes()
    {
        return [Person::class];
    }

    /**
     * Returns if the dynamic role should be assigned to the provided authorizable object.
     *
     * @param Authorizable $authorizable
     * @return boolean
     */
    public function shouldAssignTo($authorizable)
    {
        return $authorizable instanceof Person && $authorizable->membership_status === $this;
    }
}
