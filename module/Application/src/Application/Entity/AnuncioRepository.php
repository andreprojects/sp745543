<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

class AnuncioRepository extends EntityRepository {

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
		$records = $this->findBy(array('id_usuario'=>$userId));
		return $records;
	}
	
	public function findByArea($p_left,$p_top,$p_right,$p_btn)
	{ 
		$records = $this->findOneBy(array('p_left' => $p_left,'p_top' => $p_top,'p_right' => $p_right,'p_btn' =>$p_btn));
		return $records;
	}

	public function findByAnuncio($id)
	{ 
		$records = $this->findOneBy(array('id'=>$id));
		return $records;
	}

    
}
