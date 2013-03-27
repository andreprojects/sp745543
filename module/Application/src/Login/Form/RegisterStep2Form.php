<?php
namespace Login\Form;


use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;
use Login\Form\RegisterFilter;


class RegisterStep2Form extends Form
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('register_step2');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new RegisterStep2Filter);

        $this->setUseInputFilterDefaults(false);
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'span4'
            ),
            'options' => array(
                'label' => 'Nome Completo*',
            ),
        ));
        
        $this->add(array(
            'name' => 'cep',
            'attributes' => array(
                'type'  => 'text',
                'class' => ''
            ),
            'options' => array(
                'label' => 'Cep: (Opcional)',
            ),
        ));
        
         $this->add(array(
            'name' => 'senha',
            'attributes' => array(
                'type'  => 'password',
                'class' => ''
            ),
            'options' => array(
                'label' => 'Senha:',
            ),
        ));
         
         $this->add(array(
            'name' => 'contra_senha',
            'attributes' => array(
                'type'  => 'password',
                'class' => ''
            ),
            'options' => array(
                'label' => 'Confirme sua senha:',
            ),
        ));
         
         $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'confirm_term',
            'options' => array(
                'label' => 'Li e concordo com os Termos de Uso acima',
                'use_hidden_element' => false,
                'checked_value'   => '1',
                'unchecked_value' => '0',
                 
            ),

            //'attributes' => array('disabled' => 'disabled')
        	'attributes' => array(
				'class' => ''
			)
		));
         
         $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'opt_newsletter',
            'options' => array(
                'label' => 'Desejo receber ofertas',
                'use_hidden_element' => false,
                'checked_value'   => '1',
                'unchecked_value' => '0',
            ),
            'attributes' => array(
                //'value' => '1', //set selected to '1'
                'class' => ''
            )
            
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
