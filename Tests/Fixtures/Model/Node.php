<?php

namespace DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Model;

/**
 * Class Node
 * @package DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Model
 * @author  Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class Node
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Node
     */
    protected $parent;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        if ($id > 1) {
            $parent = new Node();
            $parent->setId($id - 1);
            $this->setParent($parent);
        }

        return $this;
    }

    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Node $parent
     *
     * @return $this
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;

        return $this;
    }
}