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
     *
     */
    protected function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());

        parent::setUp();
    }

    /**
     * @TODO Move it to some plugin or service
     */
    protected function tearDown()
    {
        /* @var DocumentManager $documentManager */
        $documentManager = $this->getApplicationServiceLocator()->get('doctrine.documentmanager.odm_default');
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
    public function testIndexAction()
    {
        $this->createLanguage(array(
            'id'    => 'en',
            'title' => 'En',
        ));
        $this->createLanguage(array(
            'id'    => 'ru',
            'title' => 'Ru',
        ));

        $department1 = $this->createDepartment(array(
            'title' => 'Department 1',
        ));
        $department2 = $this->createDepartment(array(
            'title' => 'Department 2',
        ));

        $vacancy1 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en'
            ),
            'department' => $department1,
        ));
        $vacancy2 = $this->createVacancy(array(
            'title' => array(
                'en' => 'Vacancy 2, en',
            ),
            'description' => array(
                'en' => 'Vacancy 2 description, en'
            ),
            'department' => $department1,
        ));
        $vacancy3 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 3, ru',
                'en' => 'Vacancy 3, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 3 description, ru',
                'en' => 'Vacancy 3 description, en'
            ),
            'department' => $department2,
        ));


        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertQueryContentContains('.vacancies dl dt', $vacancy1->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', $vacancy1->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', $vacancy2->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', $vacancy2->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', $vacancy3->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', $vacancy3->getDescription('en'));
    }

    /**
     *
     */
    public function testIndexActionFilteredByDepartment()
    {
        $this->createLanguage(array(
            'id'    => 'en',
            'title' => 'En',
        ));
        $this->createLanguage(array(
            'id'    => 'ru',
            'title' => 'Ru',
        ));

        $department1 = $this->createDepartment(array(
            'title' => 'Department 1',
        ));
        $department2 = $this->createDepartment(array(
            'title' => 'Department 2',
        ));

        $vacancy1 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en'
            ),
            'department' => $department1,
        ));
        $vacancy2 = $this->createVacancy(array(
            'title' => array(
                'en' => 'Vacancy 2, en',
            ),
            'description' => array(
                'en' => 'Vacancy 2 description, en'
            ),
            'department' => $department1,
        ));
        $vacancy3 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 3, ru',
                'en' => 'Vacancy 3 description, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 3, ru',
                'en' => 'Vacancy 3 description, en'
            ),
            'department' => $department2,
        ));


        $this->dispatch('/', 'GET', array(
            'department' => $department1->getId()
        ));

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertQueryContentContains('.vacancies dl dt', $vacancy1->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', $vacancy1->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', $vacancy2->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', $vacancy2->getDescription('en'));
        $this->assertNotQueryContentContains('.vacancies dl dt', $vacancy3->getTitle('en'));
        $this->assertNotQueryContentContains('.vacancies dl dd', $vacancy3->getDescription('en'));
    }

    /**
     *
     */
    public function testIndexActionFilteredByDepartmentAndLanguage()
    {
        $this->createLanguage(array(
            'id'    => 'en',
            'title' => 'En',
        ));
        $this->createLanguage(array(
            'id'    => 'ru',
            'title' => 'Ru',
        ));

        $department1 = $this->createDepartment(array(
            'title' => 'Department 1',
        ));
        $department2 = $this->createDepartment(array(
            'title' => 'Department 2',
        ));

        $vacancy1 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 1, ru',
                'en' => 'Vacancy 1 description, en'
            ),
            'department' => $department1,
        ));
        $vacancy2 = $this->createVacancy(array(
            'title' => array(
                'en' => 'Vacancy 2, en',
            ),
            'description' => array(
                'en' => 'Vacancy 2 description, en'
            ),
            'department' => $department1,
        ));
        $vacancy3 = $this->createVacancy(array(
            'title' => array(
                'ru' => 'Vacancy 3, ru',
                'en' => 'Vacancy 3 description, en',
            ),
            'description' => array(
                'ru' => 'Vacancy 3, ru',
                'en' => 'Vacancy 3 description, en'
            ),
            'department' => $department2,
        ));


        $this->dispatch('/', 'GET', array(
            'department' => $department1->getId(),
            'language'   => 'ru',
        ));

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertQueryContentContains('.vacancies dl dt', $vacancy1->getTitle('ru'));
        $this->assertQueryContentContains('.vacancies dl dd', $vacancy1->getDescription('ru'));
        $this->assertNotContains('.vacancies dl dt', $vacancy1->getTitle('en'));
        $this->assertNotContains('.vacancies dl dd', $vacancy1->getDescription('en'));
        $this->assertQueryContentContains('.vacancies dl dt', $vacancy2->getTitle('en'));
        $this->assertQueryContentContains('.vacancies dl dd', $vacancy2->getDescription('en'));
        $this->assertNotContains('.vacancies dl dt', $vacancy3->getTitle('en'));
        $this->assertNotContains('.vacancies dl dd', $vacancy3->getDescription('en'));
    }

    /**
     * @TODO Move it to some plugin or service
     *
     * @param array $data
     * @return Language
     */
    protected function createLanguage(array $data = array())
    {
        $language = new Language($data);
        /* @var DocumentManager $documentManager */
        $documentManager = $this->getApplicationServiceLocator()->get('doctrine.documentmanager.odm_default');
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
    protected function createDepartment(array $data = array())
    {
        $department = new Department($data);
        /* @var DocumentManager $documentManager */
        $documentManager = $this->getApplicationServiceLocator()->get('doctrine.documentmanager.odm_default');
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
        $documentManager = $this->getApplicationServiceLocator()->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($vacancy);
        $documentManager->flush();

        return $vacancy;
    }
}