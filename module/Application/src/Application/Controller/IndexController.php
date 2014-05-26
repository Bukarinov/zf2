<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\VacanciesFilter;

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

        return new ViewModel(array(
            'form' => $form,
        ));
    }
}
