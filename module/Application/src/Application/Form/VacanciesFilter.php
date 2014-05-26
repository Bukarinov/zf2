<?php

namespace Application\Form;

use Zend\Form\Form;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ForgottenPasswordForm
 * @package Application\Form
 */
class VacanciesFilter extends Form implements ObjectManagerAwareInterface
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
     *
     */
    public function init()
    {
        $this->setAttributes(array(
            'method' => 'get',
            'class'  => 'navbar-form form-inline navbar-default',
        ));

        $this->add(array(
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'department',
            'options' => array(
                'object_manager'     => $this->getObjectManager(),
                'target_class'       => 'Application\Model\Department',
                'property'           => 'title',
                'display_empty_item' => true,
                'empty_item_label'   => 'All',
                // @TODO Sort doesn't work
                /*'is_method'          => true,
                'find_method'        => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'sort'  => array('title' => 'ASC'),
                    ),
                ),*/
            ),
        ));

        $this->add(array(
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'language',
            'options' => array(
                'required'           => true,
                'object_manager'     => $this->getObjectManager(),
                'target_class'       => 'Application\Model\Language',
                'property'           => 'title',
                // @TODO Sort doesn't work
                /*'is_method'          => true,
                'find_method'        => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'sort'  => array('title' => 'ASC'),
                    ),
                ),*/
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'class' => 'btn btn-default',
            ),
        ));

        $this->getInputFilter()->get('department')->setRequired(false);
        $this->getInputFilter()->get('language')->setRequired(false);
    }
}
