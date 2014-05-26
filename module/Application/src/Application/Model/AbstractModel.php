<?php

namespace Application\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class AbstractModel
 * @package Application\Model
 *
 * @ODM\Document
 * @ODM\InheritanceType("COLLECTION_PER_CLASS")
 */
abstract class AbstractModel
{
    /**
     * @ODM\Id(strategy="AUTO")
     * @var string
     */
    protected $id;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
