<?php
    namespace App\Domain\_mysql\System\Repository;

    use App\Domain\_mysql\System\Entity\User;
    use App\Domain\_mysql\System\Froms\UserSearch;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;

    class UserRepository extends ServiceEntityRepository {

        public function __construct(ManagerRegistry $registry){
            parent::__construct($registry, User::class);
        }

        public function findSearch(UserSearch $userSearch){
            $q = $this->createQueryBuilder('u');

            if($userSearch->getUser() != null){
                $search = [
                    "u.lastname LIKE :user",
                    "u.firstname LIKE :user",
                    "u.email LIKE :user"
                ];
                $q->andWhere(implode(" OR ", $search));
                $q->setParameter('user', '%'.$userSearch->getUser().'%');
            }

            if($userSearch->getLastname() != null){
                $q->andWhere("u.lastname LIKE :lastname");
                $q->setParameter('lastname', '%'.$userSearch->getLastname().'%');
            }
            if($userSearch->getFirstname() != null){
                $q->andWhere("u.firstname LIKE :firstname");
                $q->setParameter('firstname', '%'.$userSearch->getFirstname().'%');
            }

            $q->orderBy($userSearch->getSort(), $userSearch->getOrder());

            return $q->getQuery()->getResult();
        }

        public function search(UserSearch $userSearch){
            $q = $this->createQueryBuilder('u');

            /**
            if($userSearch->getUser() != null){
                $search = [
                    "u.lastname LIKE :user",
                    "u.firstname LIKE :user",
                    "u.email LIKE :user"
                ];
                $q->andWhere(implode(" OR ", $search));
                $q->setParameter('user', '%'.$userSearch->getUser().'%');
            }

            if($userSearch->getLastname() != null){
                $q->andWhere("u.lastname = :lastname");
                $q->setParameter('lastname', $userSearch->getLastname());
            }

            if($userSearch->getFirstname() != null){
                $q->andWhere("u.firstname = :firstname");
                $q->setParameter('firstname', $userSearch->getFirstname());
            }

            if($userSearch->getEmail() != null){
                $q->andWhere("u.email = :email");
                $q->setParameter('email', $userSearch->getEmail());
            }
             * */

            $q->orderBy($userSearch->getSort(), $userSearch->getOrder());
            //$q->orderBy('u.email', 'ASC');

            dump($q->getQuery());
            dump($q->getQuery()->getResult());

            return $q->getQuery()->getResult();
        }

    }