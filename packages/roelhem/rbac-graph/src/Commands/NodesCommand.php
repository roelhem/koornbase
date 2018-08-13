<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 16:17
 */

namespace Roelhem\RbacGraph\Commands;


use Illuminate\Console\Command;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Graphs\Tools\Iterators\DepthFirstGraphIterator;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class NodesCommand extends Command
{

    protected $signature = 'rbac-graph:nodes';

    protected $description = 'Shows a list of all nodes in the RBAC-graph.';


    /**
     * @throws \Roelhem\RbacGraph\Exceptions\NodeNotFoundException
     */
    public function handle() {


        $graph = \Rbac::graph();

        $iterator = new DepthFirstGraphIterator($graph);


        $nodeTypes = collect(NodeType::getEnumerators());
        NodeType::registerConsoleStyles($this, 'reverse');
        $maxLabelLen = $nodeTypes->max(function(NodeType $nodeType) {
            return strlen($nodeType->getLabel());
        });
        $typeLen = $maxLabelLen + 4;

        $totalLen = 140;

        $idStyle = new OutputFormatterStyle('black',null, ['underscore']);
        $this->output->getFormatter()->setStyle('id', $idStyle);

        $descriptionStyle = new OutputFormatterStyle('white');
        $this->output->getFormatter()->setStyle('description', $descriptionStyle);

        $keyStyle = new OutputFormatterStyle('magenta', null, ['bold']);
        $this->output->getFormatter()->setStyle('key', $keyStyle);
        $valueStyle = new OutputFormatterStyle('blue');
        $this->output->getFormatter()->setStyle('value', $valueStyle);

        $titleStyle = new OutputFormatterStyle('black',null, ['bold','underscore']);
        $this->output->getFormatter()->setStyle('title', $titleStyle);

        $nameStyle = new OutputFormatterStyle('white', null, ['reverse']);
        $this->output->getFormatter()->setStyle('name', $nameStyle);

        foreach ($iterator as $node) {

            $this->line(str_repeat('_',$totalLen));


            $idLen = 6;
            $id = $node->getId();
            $idTag = '<id>'.str_pad($id,$idLen,' ', STR_PAD_LEFT).'</id>';

            $nodeType = $node->getType();
            $label = str_pad(' ['.$nodeType->getLabel().'] ',$typeLen, ' ',STR_PAD_BOTH);
            $tag = $nodeType->getConsoleStyleTags($label);

            $name = $node->getName();
            $nameLen = strlen($name) + 4;

            $titleLen = $totalLen - $typeLen - $nameLen - $idLen - 4;
            $title = str_pad($node->getTitle(), $titleLen);

            $this->line("{$tag}{$idTag}<title>  {$title}  </title><name>  $name  </name>");


            $this->line("<description>{$node->getDescription()}</description>");

            // OPTIONS
            $options = $node->getOptions();
            if(is_iterable($options) && count($options) > 0) {
                $this->line('<info>Options:</info>');


                $options = collect($options);
                $len = $options->keys()->max(function($val) { return strlen($val); });


                foreach ($options as $key => $value) {
                    if(is_array($value)) {
                        $value = '[ <value>'.implode('</value>, <value>',$value).'</value> ]';
                    } else {
                        $value = "<value>$value</value>";
                    }
                    $keyLabel = str_pad('  '.$key.':',$len + 4);
                    $this->line("<key>$keyLabel </key>{$value}");
                }

                $this->line('');
            }



            // PARENTS
            $parents = $graph->getParents($node);
            $parentsCount = $parents->count();
            if($parentsCount > 0) {
                $this->line('<info>Parents</info> ('.$parentsCount.')<info>:</info>');
                $this->output->listing($parents->map(function (Node $parent) {
                    $id = str_pad($parent->getId(),6, ' ',STR_PAD_BOTH);
                    return '<comment>[' . $parent->getType()->getName() . ']</comment> ' .
                        "<id>$id</id>".'<title>'.$parent->getTitle().'</title>  '
                        . $parent->getName();
                })->values()->all());
            }


            // CHILDREN
            $children = $graph->getChildren($node);
            $childrenCount = $children->count();
            if($childrenCount > 0) {
                $this->line('<info>Children</info> ('.$childrenCount.')<info>:</info>');
                $this->output->listing($children->map(function (Node $child) {
                    $id = str_pad($child->getId(),6, ' ',STR_PAD_BOTH);
                    return '<comment>[' . $child->getType()->getName() . ']</comment> '.
                        "<id>$id</id>".'<title>'.$child->getTitle().'</title>  '
                        . $child->getName();
                })->values()->all());
            }



        }

    }

}