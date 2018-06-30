<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 05:01
 */

namespace Roelhem\RbacGraph\Enums;


use Illuminate\Database\Eloquent\Model;
use MabeEnum\Enum;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Roelhem\RbacGraph\Exceptions\PathEmptyException;
use Roelhem\RbacGraph\Exceptions\RuleAttributeEmptyException;

final class RuleAttribute extends Enum
{

    /** The authorizer that performs the authorization. */
    const AUTHORIZER = 'authorizer';

    /** The graph on which the authorization is performed. */
    const GRAPH = 'graph';

    /** The authorizable object that was used in this authorization. */
    const AUTHORIZABLE = 'authorizable';

    /** A list of all the entry nodes available for the current authorizable object. */
    const ENTRY_NODES = 'entry_nodes';




    /** Returns the user on which the authorization is called. */
    const USER = 'user';

    /** The ability that was tried to authorize. */
    const CALL_ABILITY = 'call_ability';

    /** A list of all the nodes that matched the authorization call. */
    const CALL_MATCHES = 'call_matches';

    /** The arguments that were passed to the laravel gate. */
    const ARGUMENTS = 'arguments';



    /** The class of the model. */
    const MODEL_CLASS = 'model_class';

    /** The main model of the authorization. */
    const MODEL = 'model';





    /** The Node on which the rule was defined. */
    const RULE_NODE = 'rule_node';

    /** The path that is authorized. */
    const PATH = 'path';

    /** The first node of the path that is authorized. */
    const ENTRY_NODE = 'entry_node';

    /** The last node of the path that is authorized. (The node that was requested to authorize.) */
    const AUTHORIZED_NODE = 'authorized_node';



    /** A list of all the rules that need to be checked before the path is fully authorized. */
    const PATH_RULES = 'path_rules';

    /** A collection of all te parallel paths of the currently authorized path (Including itself.) */
    const POSSIBLE_PATHS = 'possible_paths';




    /**
     * Returns the default value of the provided attribute based on the explicit values in the attribute bag.
     *
     * @param RuleAttributeBag $bag
     * @throws RuleAttributeEmptyException
     * @return mixed
     */
    public function getDefault($bag) {
        $val = $this->getName();
        switch ($val) {

            case self::GRAPH:
                if($bag->hasExplicit(self::AUTHORIZER)) {
                    return $bag->authorizer->getGraph();
                }
                break;

            case self::AUTHORIZABLE:
                if($bag->hasExplicit(self::AUTHORIZER)) {
                    return $bag->authorizer->getAutorizable();
                }
                break;




            case self::MODEL_CLASS:
                if($bag->hasExplicit(self::MODEL)) {
                    return get_class($bag->model);
                }
                break;

            case self::MODEL:
                if($bag->hasExplicit(self::ARGUMENTS)) {
                    $arguments = $bag->arguments;

                    if($bag->hasExplicit(self::MODEL_CLASS) && $bag->model_class !== null) {
                        $modelClass = $bag->model_class;
                        foreach ($arguments as $argument) {
                            if(is_a($argument, $modelClass)) {
                                return $argument;
                            }
                        }
                    } else {
                        foreach ($arguments as $argument) {
                            if ($argument instanceof Model) {
                                return $argument;
                            }
                        }
                    }
                    return null;
                }
                break;


            case self::ENTRY_NODE:
                if($bag->hasExplicit(self::PATH)) {
                    try {
                        return $bag->path->getFirstNode();
                    } catch (PathEmptyException $exception) {
                        return null;
                    }
                }
                break;

            case self::AUTHORIZED_NODE:
                if($bag->hasExplicit(self::PATH)) {
                    try {
                        return $bag->path->getLastNode();
                    } catch (PathEmptyException $exception) {
                        return null;
                    }
                }
                break;


        }
        throw new RuleAttributeEmptyException("Can't find the default attribute for '$val'.");
    }

    /**
     * Returns if this attribute has a default value for the provided RuleAttributeBag.
     *
     * @param RuleAttributeBag $bag
     * @return boolean
     */
    public function hasDefault($bag) {
        try {
            $this->getDefault($bag);
            return true;
        } catch (RuleAttributeEmptyException $exception) {
            return false;
        }
    }



}