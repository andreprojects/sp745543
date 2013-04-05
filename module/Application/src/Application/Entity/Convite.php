<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="convite")
 * @ORM\Entity(repositoryClass="Application\Entity\ConviteRepository")
 * 
 * @property int      $id
 * @property int      $id_usuario
 * @property string   $nome
 * @property string   $email
 * @property string   $token
 * @property datetime $data_cadastro
 * @property datetime $data_alteracao
 */

class Convite {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
     /**
     * @ORM\Column(type="integer")
     */  
     
    protected $id_usuario;
    
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
     
    protected $token;
    
    /**
        * @ORM\Column(type="datetime")
        */
    protected $data_cadastro;
    
    /**
        * @ORM\Column(type="datetime")
        */
    protected $data_alteracao;
    
    
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
		$this->id_usuario         = $data['id_usuario'];
        $this->nome               = $data['nome'];
		$this->email              = $data['email'];
        $this->token              = $data['token'];
		$date                     = new \DateTime("now America/Sao_Paulo");
        $this->data_cadastro      = $date;
        $this->data_alteracao     = !empty($data['id']) ? $date : null;
  
    }
}
