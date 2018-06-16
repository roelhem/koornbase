<?php

namespace Roelhem\RbacGraph\Enums\Traits;


use Illuminate\Console\Command;
use Roelhem\RbacGraph\Enums\NodeType;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

trait HasConsoleFormatStyle
{


    public function getConsoleStyle(...$options) {
        $res = new OutputFormatterStyle(
            $this->conf('style.console.fg-color'),
            $this->conf('style.console.bg-color'),
            array_merge($this->conf('style.console.options', []), $options)
        );
        return $res;
    }

    public function getConsoleStyleTagName() {
        return 'ntype'.mb_strtolower(str_replace('_', '',$this->getName()));
    }

    public function getConsoleStyleTags($content = null) {
        $openTag = '<'.$this->getConsoleStyleTagName().'>';
        $closeTag = '</'.$this->getConsoleStyleTagName().'>';
        return $openTag.$content.$closeTag;
    }

    public function registerConsoleStyle($target, ...$options) {

        if($target instanceof Command) {
            $target = $target->getOutput();
        }

        if($target instanceof OutputInterface) {
            $target = $target->getFormatter();
        }

        if($target instanceof OutputFormatterInterface) {
            $target->setStyle($this->getConsoleStyleTagName(), $this->getConsoleStyle(...$options));
        }
    }

    public static function registerConsoleStyles($target, ...$options) {
        foreach (self::getEnumerators() as $nodeType) {
            if($nodeType instanceof NodeType) {
                $nodeType->registerConsoleStyle($target, ...$options);
            }
        }
    }

}