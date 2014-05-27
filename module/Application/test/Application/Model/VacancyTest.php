<?php

namespace Application\Test\Controller;

use Zend\ServiceManager\ServiceManager;

use Doctrine\ODM\MongoDB\DocumentManager;

use Application\Test\Bootstrap;
use Application\Model\Vacancy;

/**
 * Class VacancyTest
 * @package Application\Test\Controller
 */
class VacancyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected static $serviceManager;

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        self::$serviceManager = Bootstrap::getServiceManager();
    }

    /**
     *
     */
    protected function tearDown()
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
     * Vacancy should has title and description in one default language
     */
    public function testCreateVacancyWithDefaultLanguage()
    {
        $vacancy = new Vacancy();
        $vacancy->setTitle('Foo');
        $vacancy->setDescription('Bar');

        /* @var DocumentManager $documentManager */
        $documentManager = self::$serviceManager->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($vacancy);
        $documentManager->flush();

        $this->assertEquals('Foo', $vacancy->getTitle(Vacancy::DEFAULT_LANGUAGE));
        $this->assertEquals('Bar', $vacancy->getDescription(Vacancy::DEFAULT_LANGUAGE));
    }

    /**
     * Vacancy should has title and description in one not default language
     */
    public function testCreateVacancyWithSpecifiedLanguage()
    {
        $vacancy = new Vacancy();
        $vacancy->setTitle('Foo', 'ru');
        $vacancy->setDescription('Bar', 'ru');

        /* @var DocumentManager $documentManager */
        $documentManager = self::$serviceManager->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($vacancy);
        $documentManager->flush();

        $this->assertEquals('Foo', $vacancy->getTitle('ru'));
        $this->assertEquals('Bar', $vacancy->getDescription('ru'));
    }

    /**
     * Vacancy should has titles and descriptions in two different languages
     */
    public function testCreateVacancyWithSeveralLanguages()
    {
        $vacancy = new Vacancy();
        $vacancy->setTitle('Foo');
        $vacancy->setDescription('Bar');
        $vacancy->setTitle('Baz', 'ru');
        $vacancy->setDescription('Qux', 'ru');

        /* @var DocumentManager $documentManager */
        $documentManager = self::$serviceManager->get('doctrine.documentmanager.odm_default');
        $documentManager->persist($vacancy);
        $documentManager->flush();

        $this->assertEquals('Foo', $vacancy->getTitle());
        $this->assertEquals('Bar', $vacancy->getDescription());
        $this->assertEquals('Baz', $vacancy->getTitle('ru'));
        $this->assertEquals('Qux', $vacancy->getDescription('ru'));
    }
}