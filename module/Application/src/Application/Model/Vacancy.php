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
     *
     */
    const DEFAULT_LANGUAGE = 'en';

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
     * @param string $description
     * @param string $language
     */
    public function setDescription($description, $language = self::DEFAULT_LANGUAGE)
    {
        $this->description[$language] = $description;
    }

    /**
     * @param string $language
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDescription($language = self::DEFAULT_LANGUAGE)
    {
        if (isset($this->description[$language])) {
            return $this->description[$language];
        }

        if (isset($this->description[self::DEFAULT_LANGUAGE])) {
            return $this->description[self::DEFAULT_LANGUAGE];
        }

        // @TODO Custom exception for models
        throw new \InvalidArgumentException();
    }

    /**
     * @param string $title
     * @param string $language
     */
    public function setTitle($title, $language = self::DEFAULT_LANGUAGE)
    {
        $this->title[$language] = $title;
    }

    /**
     * @param string $language
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle($language = self::DEFAULT_LANGUAGE)
    {
        if (isset($this->title[$language])) {
            return $this->title[$language];
        }

        if (isset($this->title[self::DEFAULT_LANGUAGE])) {
            return $this->title[self::DEFAULT_LANGUAGE];
        }

        // @TODO Custom exception for models
        throw new \InvalidArgumentException();
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
}
