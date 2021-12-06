<?php

// Utilisation d'une variable globale avec l'extension TWIG pour récupérer et afficher l'année en cours sur base.html

namespace App\GlobalVariable;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CurrentYear extends AbstractExtension
 {

     public function getFunctions(): array
     {
         return [
             new TwigFunction('date', [$this, 'currentYear']),           
         ];
     }

     public function currentYear() {

         $year = new DateTime('now');

         return $year;
     }

 }