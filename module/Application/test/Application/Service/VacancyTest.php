<?php

namespace Application\Test\Controller;

use Zend\ServiceManager\ServiceManager;

use Doctrine\ODM\MongoDB\DocumentManager;

use Application\Test\Bootstrap;
use Application\Model\Vacancy;
use Application\Model\Department;
use Application\Service\Vacancy as VacancyService;


class VacancyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     *
     */
    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
    }

    /**
     * @TODO Move it to some plugin or service
     */
    protected function tearDown()
    {
        /* @var DocumentManager $documentManager */
        $documentManager = $this->serviceManager->get('doctrine.documentmanager.odm_default');
        $databases = $documentManager->getDocumentDatabases();
        foreach ($databases as $database) {
            /* @var $database \Doctrine\MongoDB\Database */
            $collections = $database->listCollections();
            foreach ($collections as $collection) {
                /* @var \MongoCollection $collection */
                $collection->drop();
            }
        }
    }

    /**
     *
     */
    public function testFindAllVacancies()
    {
        $department1 = $this->createDepartment(array(
            'title' => 'Department 1',
        ));
        $department2 = $this->createDepartment(array(
            'title' => 'Department 2',
        ));

        $vacancy1 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Вакансия 1',
                'en' => 'Vacancy 1',
            ),
            'description' => array(
                'ru' => 'Вакансия 1',
                'en' => 'Vacancy 1'
            ),
            'department' => $department1,
        ));
        $vacancy2 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Вакансия 2',
                'en' => 'Vacancy 2',
            ),
            'description' => array(
                'ru' => 'Вакансия 2',
                'en' => 'Vacancy 2'
            ),
            'department' => $department1,
        ));
        $vacancy3 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Вакансия 3',
                'en' => 'Vacancy 3',
            ),
            'description' => array(
                'ru' => 'Вакансия 3',
                'en' => 'Vacancy 3'
            ),
            'department' => $department2,
        ));

        /* @var VacancyService $vacancyService */
        $vacancyService = $this->serviceManager->get('VacancyService');
        $vacancies = $vacancyService->findVacancies();

        $this->assertEquals(array($vacancy1, $vacancy2, $vacancy3), $vacancies);
    }

    /**
     *
     */
    public function testFindAllVacanciesByDepartment()
    {
        $department1 = $this->createDepartment(array(
            'title' => 'Department 1',
        ));
        $department2 = $this->createDepartment(array(
            'title' => 'Department 2',
        ));

        $vacancy1 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Вакансия 1',
                'en' => 'Vacancy 1',
            ),
            'description' => array(
                'ru' => 'Вакансия 1',
                'en' => 'Vacancy 1'
            ),
            'department' => $department1,
        ));
        $vacancy2 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Вакансия 2',
                'en' => 'Vacancy 2',
            ),
            'description' => array(
                'ru' => 'Вакансия 2',
                'en' => 'Vacancy 2'
            ),
            'department' => $department1,
        ));
        $vacancy3 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Вакансия 3',
                'en' => 'Vacancy 3',
            ),
            'description' => array(
                'ru' => 'Вакансия 3',
                'en' => 'Vacancy 3'
            ),
            'department' => $department2,
        ));

        /* @var VacancyService $vacancyService */
        $vacancyService = $this->serviceManager->get('VacancyService');
        $vacancies = $vacancyService->findVacancies($department1->getId());

        $this->assertEquals(array($vacancy1, $vacancy2), $vacancies);
    }

    /**
     * @TODO Move it to some plugin or service
     *
     * @param array $data
     * @return Department
     */
    protected function createDepartment(array $data = array())
    {
        $department = new Department($data);
        /* @var DocumentManager $documentManager */
        $documentManager = $this->serviceManager->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($department);
        $documentManager->flush();

        return $department;
    }

    /**
     * @TODO Move it to some plugin or service
     *
     * @param array $data
     * @return Vacancy
     */
    protected function createVacancy(array $data = array())
    {
        $vacancy = new Vacancy($data);
        /* @var DocumentManager $documentManager */
        $documentManager = $this->serviceManager->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($vacancy);
        $documentManager->flush();

        return $vacancy;
    }
}