<?php

namespace Application\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Department
 * @package Application\Model
 *
 * @ODM\Document
 */
class Department extends AbstractModel
{
    /**
     * @ODM\String
     * @ODM\Index
     * @var string
     */
    protected $title;

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
