<?php

namespace DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Model;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class Node
{
	protected $id;

	protected $parent;

	public function getId()
	{
		return $this->id;
	}

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

	public function getParent()
	{
		return $this->parent;
	}

	public function setParent(Node $parent)
	{
		$this->parent = $parent;

		return $this;
	}
}