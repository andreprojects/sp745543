<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

class PerguntaRepository extends EntityRepository {

    public function fetchPairs() {
        $entities = $this->findAll();
        return $entities;
    }
	
	public function countAll(){
		
		$entities = $this->findAll();
		return count($entities);
	}

	public function findById($id_pergunta)
	{ 
		$records = $this->findBy(array('id'=>$id_pergunta));
		return $records;
	}
	
	public function findByIdAds($id_ads, array $orderBy = array('id' => 'ASC'))
	{ 
		$records = $this->findBy(array('id_anuncio'=>$id_ads),$orderBy);
		return $records;
	}

	public function findByEmail($email)
	{ 
		$records = $this->findOneBy(array('email'=>$email));
		return $records;
	}

}
