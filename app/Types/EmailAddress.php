<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 22-09-18
 * Time: 00:18
 */

namespace App\Types;


use App\Enums\PersonNameFormat;

class EmailAddress
{

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * EmailAddress constructor.
     * @param string $email
     * @param string|Name|null $name
     */
    public function __construct($email, $name)
    {
        $this->email = trim($email);
        if($name instanceof Name) {
            $this->name = $name->format(PersonNameFormat::NORMAL());
        } else {
            $this->name = trim($name);
        }
    }

    public function combined()
    {
        return $this->name.' <'.$this->email.'>';
    }

    public function link()
    {
        return 'mailto:'.$this->combined();
    }

    public function encodedLink()
    {
        return 'mailto:'.urlencode($this->name.' ').'<'.$this->email.'>';
    }

}