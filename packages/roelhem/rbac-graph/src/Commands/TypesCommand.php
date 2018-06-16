<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 01:50
 */

namespace Roelhem\RbacGraph\Commands;


use Illuminate\Console\Command;
use Roelhem\RbacGraph\Enums\NodeType;
use Symfony\Component\Yaml\Yaml;

class TypesCommand extends Command
{

    protected $signature = 'rbac-graph:types';

    protected $description = 'Shows the GraphTypes';

    public function handle()
    {
        // table headers
        $headers = ['Name','Value','Label','Allowed Children', 'Allowed Parents'];

        // table rows
        $types = collect(NodeType::getEnumerators());
        $types->sortBy(function(NodeType $type) {
            return $type->getValue();
        });
        $rows = $types->map(function(NodeType $type) {
            return [
                $type->getName(),
                $type->getValue(),
                $type->getLabel(),
                implode(',', $type->getAllowedChildTypes()->getNames()),
                implode(',', $type->getAllowedParentTypes()->getNames())
            ];
        });

        // show the table.
        $this->table($headers, $rows);

    }
}