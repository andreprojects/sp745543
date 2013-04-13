<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="anuncio")
 * @ORM\Entity(repositoryClass="Application\Entity\AnuncioRepository")
 * 
 * @property int      $id
 * @property int      $id_usuario
 * @property string   $titulo
 * @property string   $descricao
 * @property string   $url
 * @property int      $status
 * @property int      $pageviews
 * @property int      $unique_pageviews
 * @property datetime $data_cadastro
 * @property datetime $data_alteracao
 */

class Anuncio {

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
     
    protected $titulo;
	

    /**
     * @ORM\Column(type="string")
     */  
     
    protected $descricao;

     /**
     * @ORM\Column(type="string")
     */  
     
    protected $url;
    

	    
      /**
     * @ORM\Column(type="integer")
     */  
     
    protected $status;

	
	
		      /**
     * @ORM\Column(type="integer")
     */  
     
    protected $pageviews;

          /**
     * @ORM\Column(type="integer")
     */  
     
    protected $unique_pageviews;

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
        $this->titulo             = $data['titulo'];
		$this->descricao          = $data['descricao'];
        $this->url                = $data['url'];
		$this->status             = empty($data['status']) ? 0 : $data['status'];
		$this->pageviews          = empty($data['pageviews']) ? 0 : $data['status'];
        $this->unique_pageviews   = empty($data['pageviews']) ? 0 : $data['status'];
        $date = new \DateTime("now America/Sao_Paulo");
        $this->data_cadastro      = $date;
        $this->data_alteracao     = !empty($data['id']) ? $data['data_alteracao'] : null;
  
    }
}
