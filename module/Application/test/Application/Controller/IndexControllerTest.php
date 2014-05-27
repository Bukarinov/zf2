<?php

namespace Application\Test\Controller;

use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

use Doctrine\ODM\MongoDB\DocumentManager;

use Application\Test\Bootstrap;
use Application\Model\Vacancy;
use Application\Model\Department;
use Application\Model\Language;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
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
     * @return Language
     */
    protected static function createLanguage(array $data = array())
    {
        $language = new Language($data);
        /* @var DocumentManager $documentManager */
        $documentManager = Bootstrap::getServiceManager()->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($language);
        $documentManager->flush();

        return $language;
    }

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
        self::createLanguage(array(
            'id'    => 'en',
            'title' => 'En',
        ));
        self::createLanguage(array(
            'id'    => 'ru',
            'title' => 'Ru',
        ));

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
     *
     */
    protected function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());

        parent::setUp();
    }

    /**
     * Should find all vacancies of all departments
     * Description and title of all vacancies should be on english
     */
    public function testIndexAction()
    {
        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertQueryContentContains('.vacancies dl dt', self::$vacancies[0]->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', self::$vacancies[0]->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', self::$vacancies[1]->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', self::$vacancies[1]->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', self::$vacancies[2]->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', self::$vacancies[2]->getDescription('en'));
    }

    /**
     * Should find all vacancies of one department
     * Description and title of all vacancies should be on english
     */
    public function testIndexActionFilteredByDepartment()
    {
        $this->dispatch('/', 'GET', array(
            'department' => self::$departments[0]->getId()
        ));

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertQueryContentContains('.vacancies dl dt', self::$vacancies[0]->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', self::$vacancies[0]->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', self::$vacancies[1]->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', self::$vacancies[1]->getDescription('en'));
        $this->assertNotQueryContentContains('.vacancies dl dt', self::$vacancies[2]->getTitle('en'));
        $this->assertNotQueryContentContains('.vacancies dl dd', self::$vacancies[2]->getDescription('en'));
    }

    /**
     * Should find all vacancies of one department
     * Description and title of two vacancies should be on russian and one on english
     */
    public function testIndexActionFilteredByDepartmentAndLanguage()
    {
        $this->dispatch('/', 'GET', array(
            'department' => self::$departments[0]->getId(),
            'language'   => 'ru',
        ));

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertQueryContentContains('.vacancies dl dt', self::$vacancies[0]->getTitle('ru'));
        $this->assertQueryContentContains('.vacancies dl dd', self::$vacancies[0]->getDescription('ru'));
        $this->assertNotContains('.vacancies dl dt', self::$vacancies[0]->getTitle('en'));
        $this->assertNotContains('.vacancies dl dd', self::$vacancies[0]->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', self::$vacancies[1]->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', self::$vacancies[1]->getDescription('en'));
        $this->assertNotContains('.vacancies dl dt', self::$vacancies[2]->getTitle('en'));
        $this->assertNotContains('.vacancies dl dd', self::$vacancies[2]->getDescription('en'));
    }
}