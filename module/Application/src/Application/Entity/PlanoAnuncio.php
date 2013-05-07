<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="plano_anuncio")
 * @ORM\Entity(repositoryClass="Application\Entity\PlanoAnuncioRepository")
 * 
 * @property int      $id
 * @property int      $id_anuncio
 * @property int      $id_plano
 * @property int      $status
 * @property datetime $data_inicio
 * @property datetime $data_fim
 * @property int      $media_clique
 * @property int      $posicao_media
 * @property int      $impressao
 * @property int      $tipo
 * @property string   $url_site
 * @property datetime $data_cadastro
 * @property datetime $data_alteracao
 */

class PlanoAnuncio {

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
     * @ORM\Column(type="integer")
     */  
     
    protected $id_plano;

             /**
     * @ORM\Column(type="integer")
     */  
     
    protected $status;

    /**
    * @ORM\Column(type="datetime")
    */

    protected $data_inicio;
    /**
    * @ORM\Column(type="datetime")
    */

    protected $data_fim;

    /**
     * @ORM\Column(type="integer")
     */  
     
    protected $media_clique;


        /**
     * @ORM\Column(type="integer")
     */  
     
    protected $posicao_media;



        /**
     * @ORM\Column(type="integer")
     */  
     
    protected $impressao;


        /**
     * @ORM\Column(type="integer")
     */  
     
    protected $tipo;


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
		$this->id_anuncio      = $data['id_anuncio'];
        $this->id_plano        = $data['id_plano'];
        $this->status          = $data['status'];
        $this->data_inicio     = $data['data_inicio'];
        $this->data_fim        = $data['data_fim'];
		$this->media_clique    = $data['media_clique'];
        $this->posicao_media   = $data['posicao_media'];
		$this->tipo            = $data['tipo'];
		$date = new \DateTime("now America/Sao_Paulo");
        $this->data_cadastro    = $date;
        $this->data_alteracao   = $data['data_alteracao'];
  
    }
}