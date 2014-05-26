<?php

namespace Application\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Language
 * @package Application\Model
 *
 * @ODM\Document
 */
class Language extends AbstractModel
{
    /**
     * @ODM\Id(strategy="NONE")
     * @var string
     */
    protected $id;

    /**
     * @ODM\String
     * @ODM\Index
     * @var string
     */
    protected $title;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
