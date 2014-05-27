<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\VacanciesFilter;
use Application\Service\Vacancy as VacancyService;
use Application\Model\Vacancy;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $department = null;
        $language = Vacancy::DEFAULT_LANGUAGE;
        $vacancies = array();

        /* @var VacanciesFilter $form */
        $form = $this->getServiceLocator()->get('VacanciesFilter');
        $form->setData(array_merge(
            array('language' => $language),
            $this->getRequest()->getQuery()->toArray()
        ));

        if ($form->isValid()) {
            $formData = $form->getData();
            $department = $formData['department'];
            $language = $formData['language'];

            /* @var VacancyService $vacancyService */
            $vacancyService = $this->getServiceLocator()->get('VacancyService');
            $vacancies = $vacancyService->findVacancies($department);
        }

        return new ViewModel(array(
            'form'        => $form,
            'vacancies'   => $vacancies,
            'language'    => $language,
        ));
    }
}
