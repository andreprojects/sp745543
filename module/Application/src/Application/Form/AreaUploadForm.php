<?php
namespace Application\Form;

use Zend\Form\Form;
//use Application\Form\FaleConoscoFilter;

class AreaUploadForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('areaupload');

        $this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
	
        
        
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file'
            ),
            'options' => array(
                'label' => 'Enviar imagem',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Enviar',
                'id' => 'submitbutton',
            ),
        ));

    }
}
