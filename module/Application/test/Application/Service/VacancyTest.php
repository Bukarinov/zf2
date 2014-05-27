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
    protected static $serviceManager;

    /**
     * @var Department[]
     */
    protected static $departments = array();

    /**
     * @var Vacancy[]
     */
    protected static $vacancies = array();

    /**
     * @TODO Move it to some plugin or service
     *
     * @param array $data
     * @return Department
     */
    protected static function createDepartment(array $data = array())
    {
        $department = new Department($data);
        /* @var DocumentManager $documentManager */
        $documentManager = Bootstrap::getServiceManager()->get('doctrine.documentmanager.odm_default');
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
    protected static function createVacancy(array $data = array())
    {
        $vacancy = new Vacancy($data);
        /* @var DocumentManager $documentManager */
        $documentManager = Bootstrap::getServiceManager()->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($vacancy);
        $documentManager->flush();

        return $vacancy;
    }

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        self::$serviceManager = Bootstrap::getServiceManager();

        self::$departments[] = self::createDepartment(array(
            'title' => 'Department 1',
        ));
        self::$departments[] = self::createDepartment(array(
            'title' => 'Department 2',
        ));

        self::$vacancies[] = self::createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en'
            ),
            'department' => self::$departments[0],
        ));
        self::$vacancies[] = self::createVacancy(array(
            'title' => array(
                'en' => 'Vacancy 2, en',
            ),
            'description' => array(
                'en' => 'Vacancy 2 description, en'
            ),
            'department' => self::$departments[0],
        ));
        self::$vacancies[] = self::createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 3, ru',
                'en' => 'Vacancy 3, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 3 description, ru',
                'en' => 'Vacancy 3 description, en'
            ),
            'department' => self::$departments[1],
        ));
    }

    /**
     * @TODO Move it to some plugin or service
     */
    public static function tearDownAfterClass()
    {
        /* @var DocumentManager $documentManager */
        $documentManager = Bootstrap::getServiceManager()->get('doctrine.documentmanager.odm_default');
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
     * Should find all vacancies of all departments
     */
    public function testFindAllVacancies()
    {
        /* @var VacancyService $vacancyService */
        $vacancyService = self::$serviceManager->get('VacancyService');
        $vacancies = $vacancyService->findVacancies();

        $this->assertEquals(self::$vacancies, $vacancies);
    }

    /**
     * Should find all vacancies of one department
     */
    public function testFindAllVacanciesByDepartment()
    {
        /* @var VacancyService $vacancyService */
        $vacancyService = self::$serviceManager->get('VacancyService');
        $vacancies = $vacancyService->findVacancies(self::$departments[0]->getId());

        $this->assertEquals(array(self::$vacancies[0], self::$vacancies[1]), $vacancies);
    }
}