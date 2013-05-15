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
	
	public function findByUserListAll($userId,$sort = "id",$order = "DESC")
	{
		$qb = $this->createQueryBuilder('a');
        $qb ->select(array('a','pa'))
            ->leftJoin('Application\Entity\PlanoAnuncio', 'pa', 'WITH', 'pa.id_anuncio = a.id')
		    ->where('a.id_usuario = '.$userId,$qb->expr()->in('a.status', array(0,1,2,3)))
            ->orderBy("a.".$sort,$order);
		$query = $qb->getQuery();
		$records = $query->getScalarResult();

		return $records;
	}

	public function findbyAllWithUser($sort = "id",$order = "DESC"){
		$qb = $this->createQueryBuilder('a');
		$qb ->select(array('u','a'))
			->innerJoin('Application\Entity\Usuario', 'u', 'WITH', 'u.id = a.id_usuario')
			->orderBy("a.".$sort,$order);

		$query = $qb->getQuery();
		$records = $query->getScalarResult();
		return $records;
	}
	
	public function findByUserWithAds($id_anuncio)
	{ 
		$qb = $this->createQueryBuilder('a');
		$qb ->select(array('u','a'))
			->innerJoin('Application\Entity\Usuario', 'u', 'WITH', 'u.id = a.id_usuario')
			->where('a.id = :id_anuncio')
			->setParameter('id_anuncio', $id_anuncio);
		$query = $qb->getQuery();
		
		/*print_r(array(
		    'sql' => $query->getSQL(),
		    'parameters' => $query->getParameters(),
		));
		exit;*/

		$records = $query->getResult();
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

	public function findByWord($word,$sort = "id",$order = "DESC"){
		$qb = $this->createQueryBuilder('a');
		$qb ->select(array('u','a'))
			->innerJoin('Application\Entity\Usuario', 'u', 'WITH', 'u.id = a.id_usuario');
		$qb->where($qb->expr()->like('a.titulo', $qb->expr()->literal('%'.$word.'%')));
		$qb->orderBy("a.".$sort,$order);
		$query = $qb->getQuery();
		$records = $query->getScalarResult();
		return $records;
	}

    
}
