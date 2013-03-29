<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Application\Entity\UsuarioRepository")
 * 
 * @property string $email
 * @property string $nome
 * @property int $cep
 * @property int $opt_newsletter
 * @property string $token
 * @property datetime $data_cadastro
 * @property datetime $data_alteracao
 * @property string $diretorio
 * @property string $senha
 * @property int $id
 * @property int $status
 */
class Usuario
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $nome;
    
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $cep;
    
     /**
     * @ORM\Column(type="integer")
     */
    protected $opt_newsletter;
    
    
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
     * @ORM\Column(type="string")
     */
    protected $diretorio;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $senha;
	
	 /**
	 * @ORM\Column(type="float")
	 */
    protected $credito;
	
	/**
	 * @ORM\Column(type="integer")
	 */
    protected $status;
    
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
        $this->email          = $data['email'];
        $this->token          = $data['token'];
        $this->nome           = $data['nome'];
        $this->cep            = $data['cep'];
        $this->opt_newsletter = $data['opt_newsletter'];
        $this->data_alteracao = $data['data_alteracao'];
        $this->diretorio      = $data['diretorio'];
        $this->senha          = $data['senha'];
		$this->credito        = $data['credito'];
		$this->status         = !empty($data['status']) ? $data['status'] : 0;
        
        //$d = new DateTime( "2010-01-15 10:41 $tzid" );
        $date = new \DateTime("now America/Sao_Paulo");
        //$date->setTimeZone( new \DateTimeZone( 'America/Sao_Paulo' ) );
        $this->data_cadastro = $date;
        //$this->livros = new ArrayCollection();
  
    }
    

    

    
    
}
