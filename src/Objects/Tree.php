<?php namespace JackPrice\PhpGit\Objects;

use JackPrice\PhpGit\Contracts\Tree as TreeInterface;

/**
 * Class Tree
 *
 * @author Jack Price <jack@wearenifty.co.uk>
 */
class Tree extends Object implements TreeInterface
{
    /**
     * Overload our parents constructor with our object type.
     *
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct(Object::TYPE_TREE, $data);
    }
}