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
	
	public function findByPlanoAnuncio($idAds)
	{ 
		$records = $this->findBy(array('id_anuncio'=>$idAds));
		return $records;
	}

	public function findByPlano($id)
	{ 
		$records = $this->findOneBy(array('id'=>$id));
		return $records;
	}

	public function findBySolicitacao($sort = "pa.id",$order = "DESC"){

		$qb = $this->createQueryBuilder('pa');
		$qb ->select(array('u','a','pa'))
			->innerJoin('Application\Entity\Anuncio', 'a', 'WITH', 'a.id = pa.id_anuncio')
			->innerJoin('Application\Entity\Usuario', 'u', 'WITH', 'u.id = a.id_usuario')
			->orderBy($sort,$order);
			//->where('a.id = :id_anuncio')
			//->setParameter('id_anuncio', $id_anuncio);
		$query = $qb->getQuery();

		$records = $query->getScalarResult();
		return $records;
	}

    
}
