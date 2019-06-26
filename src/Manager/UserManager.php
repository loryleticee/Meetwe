<?php

namespace App\Manager;

use App\Repository\UserRepository;

class UserManager
{
    /**
     * @param string $bet
     * @return string
     */
    public function getMise(?string $bet = null) : string
    {
        $data = strstr($bet, '-', true);

        return $data ? $data.',' : '';
    }

    /**
     * @param string $bet
     * @return string
     */
    public function getNumber(?string $bet = null) : string
    {
        $data = strstr($bet, '-', false);
        $data = $data ? substr($data, 1, strlen($data)) : '';

        return $data ? $data.',' : '';
    }

    public function tableGain(UserRepository $userRepository) : int
    {
        $aPlayer =  $userRepository->nextPlayers();
        $total   = 0 ;

        if (is_null($aPlayer)) {
            return $total;
        }
        foreach ($aPlayer as $player) {
            $miseStr = $this->getNumber($player->getNextBet());
            $miseStr = array_filter(explode(',', $miseStr));

            foreach ($miseStr as $row) {
                $total  += (int)$row;
            }
        }

        return $total;
    }
}
