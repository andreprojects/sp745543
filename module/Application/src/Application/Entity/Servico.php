<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="servico")
 * @ORM\Entity(repositoryClass="Application\Entity\ServicoRepository")
 * 
 * @property int      $id
 * @property string   $nome
 * @property datetime $data_cadastro
 * @property datetime $data_alteracao
 */

class Servico {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
      /**
     * @ORM\Column(type="string")
     */  
     
    protected $nome;

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
        $this->id             = $data['id'];
        $this->nome           = $data['nome'];
        $date = new \DateTime("now America/Sao_Paulo");
        $this->data_cadastro    = $date;
        $this->data_alteracao   = $data['data_alteracao'];
  
    }
}
