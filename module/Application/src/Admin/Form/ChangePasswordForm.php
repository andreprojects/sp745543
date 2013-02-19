<?php
namespace Login\Form;


use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;
use Login\Form\ChangePasswordFilter;


class ChangePasswordForm extends Form
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('change_password');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new ChangePasswordFilter);
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        
         $this->add(array(
            'name' => 'senha',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Nova Senha:',
            ),
        ));
         
         $this->add(array(
            'name' => 'contra_senha',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Confirme sua senha:',
            ),
        ));
         
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));

    }
}
