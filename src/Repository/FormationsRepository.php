<?php

namespace App\Repository;

use App\Entity\Formations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formations>
 */
class FormationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formations::class);
    }
    public function findNonInscrits($formation_id){
        // $em c'est l'entity manager, $sub correspond à la sous requête
        $em=$this->getEntityManager();
        $sub=$em->createQueryBuilder();
        // ici on declare $qd comme une sous requête
        $qd=$sub;
        // ici on ajoute la condition de la sous requête
        $qd->select('s')// on sélectionne la table stagiaires
            ->from('App\Entity\Stagiaires','s')
            ->leftJoin('s.formations', 'f')// on fait la jointure avec la table Formations
            ->where('f.id = :id');// on ajoute la condition de la jointure avec la table Formations

        $sub=$em->createQueryBuilder();// on recrée une nouvelle sous requête

        $sub->select('st')// on sélectionne la table stagiaires
            ->from('App\Entity\Stagiaires','st')// on choisi la table Stagiaires
            ->where($sub->expr()->notIn('st.id', $qd->getDQL()))// ici on définit la condition, qui signifie que les stagiaires qui ne sont pas inscrits dans la formation choisie ne seront pas affichés
            ->setParameter('id',$formation_id)//setParametre signifie que la valeur de id sera égale à la formation_id
            ->orderBy('st.nom');// on trie les stagiaires par ordre alphabétique
            
        $query = $sub->getQuery();
        return $query->getResult();
    }

    public function findProgrammesNonInscrits($formation_id){
        
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();
    
       
        $qd = $sub;
        
        $qd->select('m')
            ->from('App\Entity\Modules','m')
            ->LeftJoin('m.programmes', 'pr')
            ->where('pr.formation = :id');
    
        $sub = $em->createQueryBuilder();
    
       
        $sub->select('mo')
            ->from('App\Entity\Modules', 'mo')
            ->where($sub->expr()->notIn('mo.id', $qd->getDQL()))
            ->setParameter('id', $formation_id)
            ->orderBy('mo.nom');
    
        $query = $sub->getQuery();
        return $query->getResult();
    }
    


}

    //    /**
    //     * @return Formations[] Returns an array of Formations objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Formations
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

