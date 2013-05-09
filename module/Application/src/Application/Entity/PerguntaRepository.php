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

	public function findByCustom(array $fields = array('id'=>1), array $orderBy = array('id' => 'ASC')){
		$records = $this->findBy($fields,$orderBy);
		return $records;
	}

	public function findPerguntaPublica($id_ads){
		
		$qb = $this->createQueryBuilder('n');
		$qb ->where("n.id_anuncio = :id_anuncio AND n.msg_resposta != '' AND n.data_resposta is not null AND n.status = 1")
			->setParameter('id_anuncio', $id_ads);
		$query = $qb->getQuery();
		/*
		print_r(array(
		    'sql' => $query->getSQL(),
		    'parameters' => $query->getParameters(),
		));
		exit;*/

		$records = $query->getResult();

		return $records;
	}

	public function findByDenuncia($col_order = 'id',$type_order = 'ASC'){

		$qb = $this->createQueryBuilder('n'); 
		$qb->where('n.denuncia = 1',$qb->expr()->in('n.status', array(1,4)));
		$qb->orderBy("n.".$col_order,$type_order);
		$query = $qb->getQuery();
		$records = $query->getResult();
		return $records;
	}

	public function findByEmail($email)
	{ 
		$records = $this->findOneBy(array('email'=>$email));
		return $records;
	}

}
