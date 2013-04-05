<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

class ConviteRepository extends EntityRepository {

    public function fetchPairs() {
        $entities = $this->findAll();
        return $entities;
    }
	
	public function countAll(){
		
		$entities = $this->findAll();
		return count($entities);
	}
	
	public function findByUser($userId)
	{ 
		$records = $this->findBy(array('id_user'=>$userId));
		return $records;
	}

	public function findByEmail($email)
	{ 
		$records = $this->findOneBy(array('email'=>$email));
		return $records;
	}

	public function findByTokenAndId($token,$id)
	{
		$records = $this->findOneBy(array('token'=>$token,'id'=>$id));
		return $records;
	}

    
}
