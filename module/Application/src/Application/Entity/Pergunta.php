<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="pergunta")
 * @ORM\Entity(repositoryClass="Application\Entity\PerguntaRepository")
 * 
 * @property int      $id
 * @property int      $id_anuncio
 * @property string   $nome
 * @property string   $email
 * @property string   $msg_pergunta
 * @property string   $msg_resposta
 * @property int      $status
  * @property int      $denuncia
 * @property datetime $data_cadastro
 * @property datetime $data_resposta
 */

class Pergunta {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
     /**
     * @ORM\Column(type="integer")
     */  
     
    protected $id_anuncio;
    
      /**
     * @ORM\Column(type="string")
     */  
     
    protected $nome;
	

    /**
     * @ORM\Column(type="string")
     */  
     
    protected $email;

    /**
     * @ORM\Column(type="string")
     */  
     
    protected $msg_pergunta;


    /**
     * @ORM\Column(type="string")
     */  
     
    protected $msg_resposta;


        /**
     * @ORM\Column(type="integer")
     */  
     
    protected $status;


            /**
     * @ORM\Column(type="integer")
     */  
     
    protected $denuncia;
    
    /**
        * @ORM\Column(type="datetime")
        */
    protected $data_cadastro;
    
    /**
        * @ORM\Column(type="datetime")
        */
    protected $data_resposta;
    
    
    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->id                 = $data['id'];
		$this->id_anuncio         = $data['id_anuncio'];
        $this->nome               = $data['nome'];
		$this->email              = $data['email'];
        $this->msg_pergunta       = $data['msg_pergunta'];
		$this->msg_resposta       = $data['msg_resposta'];
        $this->status             = !empty($data['status']) ? $data['status'] : 0;
        $this->denuncia             = !empty($data['denuncia']) ? $data['denuncia'] : 0;
        $date                     = new \DateTime("now America/Sao_Paulo");
        $this->data_cadastro      = $date;
        $this->data_resposta     = !empty($data['id']) ? $date : null;
  
    }
}
