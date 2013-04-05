<?php
namespace Application\Form;

use Zend\InputFilter\InputFilter;


class IndicadoStep2Filter extends InputFilter{
        
    public function __construct()
    {

            $this->add(array(
                'name'       => 'id',
                'required'   => true,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            ));

            $this->add(array(
                'name'       => 'email',
                'required'   => true,
                'filters' => array(
                     array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
            ));

            

            $this->add(array(
                'name'     => 'cep',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 8,
                        )
                    ),
                    array(
                          'name' => 'Between',
                          'options' => array(
                              'min' => 1,
                              'max' => 99999999,
                          ),
                      ),
                ),
            ));
            
            $this->add(array(
                'name'     => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                        )
                    )
                ),
            ));
            
            $this->add(array(
                'name'     => 'senha',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 8,
                        )
                    ),
                ),
            ));
            
            $this->add(array(
                'name'     => 'contra_senha',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    
                ),
                'validators' => array(
                    array(
                        'name'    => 'Callback',
                        'options' => array(
                             'callback' => array($this, 'CheckPassword'),
                             'message' => 'Senha não confere'
                        )
                    ),
                ),
            ));
            
             $this->add(array(
                'name'     => 'confirm_term',
                'required' => true,
                'validators' => array(

                    array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Termos de Uso não Concordado' 
                            ),
                        ),
                    ),
                    /*array(
                        'name'    => 'InArray',
                        'options' => array(
                             'haystack' => array(1),
                             'message' => 'Termos de Uso não Concordado'
                        )
                    ),*/
                ),  
            ));


             $this->add(array(
                'name'     => 'opt_newsletter',
                'required' => false
            ));
        
    }
    
    public function CheckPassword($contra_senha){
        
        if($this->getRawValue('senha') != $contra_senha)
            return false;
        else
            return true;
    }
}
