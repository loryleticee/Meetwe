<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Conference;
use Doctrine\ORM\EntityManager;

class GameManager
{
    /* @var Doctrine\ORM\EntityManager $em */
    protected $em;

    /**
     * @param EntityManager $em
     * @return EntityManager
     */
    public function make(EntityManager $em)
    {
        return $em;
    }
    /**
     * @param User $user
     * @param int|null $gain
     */
    public function payed(User $user, int $gain = null) :void
    {
        $initAmount     =  (int)$user->getAmount();
        $user->setAmount(($gain + $initAmount));
    }

    public function getNote(string $sNote)
    {
        $aRefs          = [
          'one'        => 1,
          'two'        => 2,
          'three'      => 3,
          'four'       => 4,
          'five'       => 5,
        ];

        return $aRefs[strtolower($sNote)];
    }
}
