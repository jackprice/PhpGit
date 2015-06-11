<?php namespace JackPrice\PhpGit\Objects;

use Datetime;
use JackPrice\PhpGit\Contracts\Commit as CommitInterface;
use JackPrice\PhpGit\Contracts\Tree;

/**
 *
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Commit extends Object implements CommitInterface
{
    /**
     * Overload our parents constructor with our object type.
     *
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct(Object::TYPE_COMMIT, $data);
    }

    /**
     * Get the timestamp of this commit.
     *
     * @return DateTime
     */
    public function getTimestamp()
    {
        // TODO: Implement getTimestamp() method.
    }

    /**
     * Get the message of this commit.
     *
     * @return string
     */
    public function getMessage()
    {
        return explode("\n\n", $this->data)[1];
    }

    /**
     * Get the tree that this commit points to.
     *
     * @return Tree
     */
    public function getTree()
    {
        // TODO: Implement getTree() method.
    }

    /**
     * Get the parents of this commit.
     *
     * @return CommitInterface[]
     */
    public function getParents()
    {
        // TODO: Implement getParents() method.
    }

    /**
     * Get the commit object header.
     *
     * @return mixed
     */
    private function getHeader()
    {
        return explode("\n\n", $this->data)[0];
    }
}