<?php
namespace Admin\Form;


use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;
use Admin\Form\LoginFilter;


class LoginForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('login');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new LoginFilter);

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'class'	=> 'campotxt',
                'placeholder'=>"Email",
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'senha',
            'attributes' => array(
                'type'  => 'password',
                'class'	=> 'campotxt',
                'placeholder'=>"Password",
            ),
            'options' => array(
                'label' => 'Senha',
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
