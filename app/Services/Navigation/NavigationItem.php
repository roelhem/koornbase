<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 01-05-18
 * Time: 17:16
 */

namespace App\Services\Navigation;

use ArrayAccess;
use Illuminate\Http\Request;

/**
 * Class NavigationItem
 *
 * models an NavigationItem item in a navigation structure.
 *
 * @package App\Services\Navigation
 *
 * @property-read string $id
 *
 * @property string|null $name
 * @property string|null $title
 * @property string|null $route The name of the associated route
 *
 * @property string $emptyHref The value the href value has to have is no link was found
 * @property array $icons
 * @property string $defaultIconStyle The default icon style
 *
 * @property string|null $label
 * @property string|null $link
 * @property string $href
 * @property boolean $active
 */
class NavigationItem implements ArrayAccess
{

    /**
     * @var array Attributes that are always available.
     */
    public static $baseAttributes = [
        'name','title','route'
    ];

    /**
     * @var array Attributes that have a static default value.
     */
    public static $defaultAttributes = [
        'emptyHref' => 'javascript:void(0);',
        'icons' => [],
        'defaultIconStyle' => 'fe',
    ];

    /**
     * @var array Attributes where a default value can be derived.
     */
    public static $derivedAttributes = [
        'label','link','href','active'
    ];

    /**
     * @var array Attributes that are calculated based on other attributes.
     */
    public static $calculatedAttributes = [
    ];

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var NavigationItem[]
     */
    protected $children = [];

    /**
     * @var Request
     */
    protected $request;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALIZATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Item constructor.
     *
     * @param string $id
     * @param array $values
     * @param Request|null $request
     */
    public function __construct($id, $values = [], Request $request = null)
    {
        $this->request = $request;

        $this->id = $id;
        $this->attributes = $values;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @return string|null
     */
    protected function getLink()
    {
        $link = $this->get('link');
        if ($link !== null) {
            return $link;
        }

        $route = $this->get('route');
        if ($route !== null) {
            try {
                return route($route);
            } catch (\Exception $exception) {

            }
        }

        return null;
    }

    /**
     * Returns the class name of a specific icon.
     *
     * @param string|null $iconStyle
     * @return string|null
     */
    public function getIconClass($iconStyle = null)
    {
        if ($iconStyle === null) {
            $iconStyle = $this->defaultIconStyle;
        }

        if (!array_key_exists($iconStyle, $this->icons) || $this->icons[$iconStyle] === null) {
            return null;
        }

        $icon = $this->icons[$iconStyle];

        switch ($iconStyle)
        {
            case 'fa': return "fa fa-$icon";
            case 'fe': return "fe fe-$icon";
            default: return "$iconStyle $iconStyle-$icon";
        }
    }

    /**
     * Returns a tag to insert a icon.
     * @param array $styles
     * @return string
     */
    public function icon(...$styles)
    {
        if(count($styles) == 0) {
            $styles = [$this->defaultIconStyle];
        }

        $classes = [];

        foreach ($styles as $style) {
            if(is_array($style)) {
                $classes = $classes + $style;
            } elseif(is_string($style)) {
                $class = $this->getIconClass($style);
                if ($class !== null) {
                    $classes[] = $class;
                    $resultClass = implode(' ', $classes);
                    return "<i class=\"$resultClass\"></i>";
                }
            }
        }

        return '';
    }

    /**
     * Returns true if this navigation item matches the request.
     */
    public function matchesRequest() {
        $route = $this->route;
        $link = $this->link;

        if(!empty($route) && $this->request->route()->getName() == $route) {
            return true;
        }

        if(!empty($link) && $this->request->is($link)) {
            return true;
        }

        return false;
    }

    public function linkClass(...$classes)
    {
        if ($this->active) {
            $classes[] = 'active';
        }

        return implode(' ', $classes);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Tries to return the stored value of the attribute with the name of the first parameter. If it
     * wasn't able to find this value, it tries the next attribute. If none of the given names were
     * able to give a stored attribute value, null is returned.
     *
     * @param array ...$names
     * @return mixed
     */
    protected function get(...$names) {
        foreach ($names as $name) {
            if ($this->hasStoredAttribute($name)) {
                return $this->getStoredAttribute($name);
            }

            if (array_key_exists($name, self::$defaultAttributes)) {
                return self::$defaultAttributes[$name];
            }
        }
        return null;
    }

    public function matches($item) {
        if ($item instanceof NavigationItem) {
            $item = $item->id;
        }

        return $this->id = $item;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ATTRIBUTE METHODS ---------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns whether or not this NavigationItem has a attribute with the provided name stored in the
     * protected property $attributes. This method will only return true if the attribute
     * is specifically set for this NavigationItem.
     *
     * @param string $name
     * @return bool
     */
    public function hasStoredAttribute($name)
    {
        if($name == 'id') {
            return true;
        }

        return array_key_exists($name, $this->attributes);
    }

    /**
     * Returns whether or not this NavigationItem has a attribute with the provided name.
     *
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        if($this->hasStoredAttribute($name)) {
            return true;
        }

        if (in_array($name, self::$baseAttributes)) {
            return true;
        }

        if (array_key_exists($name, self::$defaultAttributes)) {
            return true;
        }

        if (in_array($name, self::$calculatedAttributes)) {
            return true;
        }

        if (in_array($name, self::$derivedAttributes)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the stored value of the attribute with the specified name if it exists. Returns the
     * value of $default otherwise
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getStoredAttribute($name, $default = null)
    {
        if($name == 'id') {
            return $this->id;
        }

        if($this->hasStoredAttribute($name)) {
            return $this->attributes[$name];
        } else {
            return $default;
        }
    }

    /**
     * Returns the value of the attribute with the specified name if it exists. Returns the
     * value of $default otherwise.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        if($name == 'id') {
            return $this->id;
        }

        if($this->hasAttribute($name)) {
            $value = $this->getAttributeValue($name);
            if($value === null) {
                return $default;
            }
            return $value;
        }
        return $default;
    }

    /**
     * Helps the getAttribute method.
     *
     * @param $name
     * @return mixed
     */
    private function getAttributeValue($name) {
        switch ($name) {
            case 'label': return $this->get('label','name','title');
            case 'link': return $this->getLink();
            case 'href': return $this->get('href') ?? $this->getLink() ?? $this->get('emptyHref');
            case 'active': return $this->get('active') ?? $this->matchesRequest();
            default: return $this->getStoredAttribute($name, array_get(self::$defaultAttributes, $name));
        }
    }

    /**
     * Stores the value of $value in an attribute with the specified $name.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        switch ($name) {
            case 'id': break;
            default: $this->attributes[$name] = $value;
                break;
        }
    }

    /**
     * Removes the stored value of the attribute with the specified name.
     *
     * @param string $name
     */
    public function unsetStoredAttribute($name)
    {
        if($name == 'id') {
            return;
        }

        if ($this->hasStoredAttribute($name)) {
            unset($this->attributes[$name]);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: Magical property access. ----------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if(!$this->hasAttribute($name)) {
            throw new \Exception("The property $name is not defined in this NavigationItem.");
        }

        return $this->getAttribute($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if($name == 'id') {
            throw new \Exception("You can't set the id of a NavigationItem after initialization.");
        }

        if(!$this->hasAttribute($name)) {
            throw new \Exception("The property $name is not defined in this NavigationItem.");
        }

        $this->setAttribute($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->hasAttribute($name);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        $this->unsetStoredAttribute($name);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: ArrayAccess -------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->hasAttribute($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->unsetStoredAttribute($offset);
    }


}