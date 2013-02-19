<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="plano")
 * @ORM\Entity(repositoryClass="Application\Entity\PlanoRepository")
 * 
 * @property int      $id
 * @property int      $id_servico
 * @property string   $nome
 * @property string   $descricao
 * @property float    $preco
 * @property int      $dia_publicacao
 * @property datetime $data_cadastro
 * @property datetime $data_alteracao
 */

class Plano {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	      /**
     * @ORM\Column(type="integer")
     */  
     
    protected $id_servico;
    
      /**
     * @ORM\Column(type="string")
     */  
     
    protected $nome;
	
	    
      /**
     * @ORM\Column(type="string")
     */  
     
    protected $descricao;
	
	      /**
     * @ORM\Column(type="float")
     */  
     
    protected $preco;
	
	
		      /**
     * @ORM\Column(type="integer")
     */  
     
    protected $dia_publicao;

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
        $this->id              = $data['id'];
		$this->id_servico      = $data['id_servico'];
        $this->nome            = $data['nome'];
		$this->descricao       = $data['descricao'];
		$this->preco           = $data['preco'];
		$this->dia_publicao    = $data['dia_publicao'];
        $date = new \DateTime("now America/Sao_Paulo");
        $this->data_cadastro    = $date;
        $this->data_alteracao   = $data['data_alteracao'];
  
    }
}
