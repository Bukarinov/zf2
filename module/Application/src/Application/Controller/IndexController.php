<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\VacanciesFilter;
use Application\Service\Vacancy as VacancyService;

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
        /* @var VacanciesFilter $form */
        $form = $this->getServiceLocator()->get('VacanciesFilter');
        $form->setData($this->getRequest()->getQuery());

        /* @var VacancyService $departmentService */
        $vacancyService = $this->getServiceLocator()->get('VacancyService');
        $department = $this->getRequest()->getQuery('department', null);
        $vacancies = $vacancyService->findVacancies($department);

        return new ViewModel(array(
            'form'        => $form,
            'vacancies'   => $vacancies,
            'language'    => $this->getRequest()->getQuery('language', 'en'),
        ));
    }
}
