<?php

namespace Application\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Vacancy
 * @package Application\Model
 *
 * @ODM\Document
 */
class Vacancy extends AbstractModel
{
    /**
     * @ODM\Hash
     * @var array
     */
    protected $title;

    /**
     * @ODM\Hash
     * @var array
     */
    protected $description;

    /**
     * @ODM\ReferenceOne(targetDocument="Application\Model\Department", simple=true)
     * @ODM\Index
     * @var Department
     */
    protected $department;

    /**
     * @param array $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param Department $department
     */
    public function setDepartment(Department $department)
    {
        $this->department = $department;
    }

    /**
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $language
     * @return string
     */
    public function getTitleByLanguage($language = 'en')
    {
        if (isset($this->title[$language])) {
            return $this->title[$language];
        }

        return $this->title['en'];
    }

    /**
     * @param string $language
     * @return string
     */
    public function getDescriptionByLanguage($language = 'en')
    {
        if (isset($this->description[$language])) {
            return $this->description[$language];
        }

        return $this->description['en'];
    }
}
