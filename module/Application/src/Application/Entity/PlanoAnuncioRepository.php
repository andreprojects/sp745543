<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

class PlanoAnuncioRepository extends EntityRepository {

    public function fetchPairs() {
        $entities = $this->findAll();
        return $entities;
    }
	
	public function countAll(){
		
		$entities = $this->findAll();
		return count($entities);
	}
	
	public function findByAnuncio($idAds,array $status = array('status' => '0'))
	{ 
		$records = $this->findBy(array('id_anuncio'=>$idAds));
		return $records;
	}

	public function findByPlano($id)
	{ 
		$records = $this->findOneBy(array('id'=>$id));
		return $records;
	}

    
}
