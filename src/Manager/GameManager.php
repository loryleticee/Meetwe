<?php

namespace App\Manager;

class GameManager
{
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
