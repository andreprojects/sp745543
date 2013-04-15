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
	
	public function findByIdAds($id_ads)
	{ 
		$records = $this->findBy(array('id_anuncio'=>$id_ads));
		return $records;
	}

	public function findByEmail($email)
	{ 
		$records = $this->findOneBy(array('email'=>$email));
		return $records;
	}

}
