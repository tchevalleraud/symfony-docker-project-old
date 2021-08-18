<?php
    namespace App\Domain\_mysql\System\Repository;

    use App\Domain\_mysql\System\Entity\App;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;

    class AppRepository extends ServiceEntityRepository {

        public function __construct(ManagerRegistry $registry){
            parent::__construct($registry, App::class);
        }

    }