<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\RendezvousEntity;
use App\Entity\PeriodeEntity;
use Symfony\Component\HttpFoundation\Request;

class CalendrierController extends ApiController
{
    /**
    * @Route("/calendrier/{id}", defaults={"id" = 0})
    */
    public function indexAction($id)
    {
        $i = intval($id);
        $repository = $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:RendezvousEntity')         
            ;

        $dateDuJour = date("Y-m-d", mktime(0,0,0,date("m"),date("j"),date("Y")));
        $dernierJourNumero = date("j", mktime(0,0,0,date("m")+ $i + 1,0,date("Y")));
        $calendrier = array();
        $nouvelleSemaine = array();

        //ajouter des cases vides au début
        $jourSemaine = date("N", mktime(0,0,0,date("m") + $i,1,date("Y"))); 
        for($compteur=1; $compteur<$jourSemaine; $compteur++) {
            $nouvelleSemaine[] = array("*", strval($compteur));
        }

        for ($date=1; $date<=$dernierJourNumero; $date++) { 
            $jourSemaine = date("N", mktime(0,0,0,date("m") + $i,$date,date("Y"))); 
            $dateAvecMoisAnnee = date("Y-m-d", mktime(0,0,0,date("m") + $i,$date,date("Y"))); 

            $dateComplete = date("Y-m-d", mktime(0,0,0,date("m") + $i,$date,date("Y")));
            $nombreRDV = $repository->countRendezVousByDate($dateComplete);
            
            $actif = "";
            if ($dateDuJour == $dateAvecMoisAnnee) {
                $actif = "actif";
            }

            if ($jourSemaine == 1) {
                $nouvelleSemaine = array();
            }

            $nouvelleSemaine[] = array($date, $actif, $nombreRDV, $dateComplete);

            if ($jourSemaine == 7 || $date == $dernierJourNumero) {
                $calendrier[] = $nouvelleSemaine;
            }
        }
       
        $calendrierMois = date("M", mktime(0,0,0,date("m") + $i,1,date("Y")))." ". date("Y", mktime(0,0,0,date("m") + $i,1,date("Y")));
        
        return $this->respond(array("calendrierMois" => $calendrierMois, "calendrier" => $calendrier));
    }
}