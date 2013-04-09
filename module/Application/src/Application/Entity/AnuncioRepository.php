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
	
	public function findByUserListAll($userId)
	{
		$qb = $this->createQueryBuilder('n'); 
		$records = $qb->where('n.id_usuario = '.$userId,$qb->expr()->in('n.status', array(0,1,2,3)))->getQuery()->getResult();
		//$records = $this->findBy(array('id_usuario'=>$userId,'status'=>'0','status'=>'1','status'=>'2','status'=>'3'));
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
	
	public function findByAnuncioAndUser($id_anuncio,$id)
	{ 
		$records = $this->findOneBy(array('id'=>$id_anuncio,'id_usuario'=>$id));
		return $records;
	}

    
}
