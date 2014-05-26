<?php

namespace Application\Service;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use Application\Model\Vacancy as VacancyModel;

/**
 * Class Vacancy
 * @package Application\Service
 */
class Vacancy implements ObjectManagerAwareInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return ObjectManager
     * @throws \RuntimeException
     */
    public function getObjectManager()
    {
        if ($this->objectManager === null) {
            throw new \RuntimeException('No object manager was set');
        }

        return $this->objectManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->getObjectManager()->getRepository('\Application\Model\Vacancy');
    }

    /**
     * @param string $departmentId
     * @return VacancyModel[]
     */
    public function findVacancies($departmentId = null)
    {
        if (!empty($departmentId)) {
            return $this->getRepository()->findBy(array(
                'department' => $departmentId
            ));
        }

        return $this->getRepository()->findAll();
    }
}
