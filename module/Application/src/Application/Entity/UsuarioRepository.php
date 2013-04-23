<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

class UsuarioRepository extends EntityRepository {


  public function findByToken($token){
    
    $records_users = $this->findOneByToken($token);
    return $records_users;
  }
  
  
  public function findByEmailAdmin($email){
  	
	$find_fields = array('email'=>$email,'status'=>'10');
	$records_users = $this->findOneBy($find_fields);
    return $records_users;
	
  }
  
  public function findByEmail($email){
    
    $find_fields = array('email'=>$email);
	
    $records_users = $this->findOneBy($find_fields);
    return $records_users;
  }
  
  public function findById($id){
    
    $records_users = $this->findOneById($id);
    return $records_users;
  }

  public function findByUsername($username){
    
    $records = $this->findOneBy(array('username'=>$username));
    return $records;
  }
  
  public function findByTokenAndEmail($token,$email){
    
    $records_users = $this->findOneBy(array('email'=>$email,'token'=>$token));
    return $records_users;
  }
  
  public function findAllUser(){
  	$entities = $this->findAll();
	return $entities;
  }
  
  
  
/*
    public function fetchPairs() {
        $entities = $this->findAll();
        
        $array = array();
        
        foreach($entities as $entity) {
            $array[$entity->getId()] = $entity->getNome();
        }
        
        return $array;
    }
    */
}
