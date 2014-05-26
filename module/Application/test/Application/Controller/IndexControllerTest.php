<?php

namespace Application\Test\Controller;

use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

use Doctrine\ODM\MongoDB\DocumentManager;

use Application\Test\Bootstrap;
use Application\Model\Vacancy;
use Application\Model\Department;

class IndexControllerTest extends AbstractControllerTestCase
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
                'en' => 'Vacancy 2',
            ),
            'description' => array(
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


        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertContains($vacancy1->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy1->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy2->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy2->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy3->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy3->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
    }

    /**
     *
     */
    public function testIndexActionFilteredByDepartment()
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
                'en' => 'Vacancy 2',
            ),
            'description' => array(
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


        $this->dispatch('/', 'GET', array(
            'department' => $department1->getId()
        ));

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertContains($vacancy1->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy1->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy2->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy2->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
        $this->assertNotContains($vacancy3->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertNotContains($vacancy3->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
    }

    /**
     *
     */
    public function testIndexActionFilteredByDepartmentAndLanguage()
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
                'en' => 'Vacancy 2',
            ),
            'description' => array(
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


        $this->dispatch('/', 'GET', array(
            'department' => $department1->getId(),
            'language'   => 'ru',
        ));

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');

        $this->assertContains($vacancy1->getTitleByLanguage('ru'), $this->getResponse()->getContent());
        $this->assertContains($vacancy1->getDescriptionByLanguage('ru'), $this->getResponse()->getContent());
        $this->assertContains($vacancy2->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertContains($vacancy2->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
        $this->assertNotContains($vacancy3->getTitleByLanguage('en'), $this->getResponse()->getContent());
        $this->assertNotContains($vacancy3->getDescriptionByLanguage('en'), $this->getResponse()->getContent());
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