<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\RendezvousEntity;
use App\Entity\PeriodeEntity;
use Symfony\Component\HttpFoundation\Request;

class RendezVousController extends ApiController
{
    
    /**
    * @Route("/ajouter")
    * @Method("POST")
    */
    public function create(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $em = $this
              ->getDoctrine()
              ->getManager()      
        ;

        $dateDebut = $data["dateEnCoursForm"]." ".$data["dateDebut"];
        $dateFin = $data["dateEnCoursForm"]." ".$data["dateFin"];

        $rdv = new RendezvousEntity;
        $rdv->setDateDebut(new \DateTime($dateDebut));
        $rdv->setDateFin(new \DateTime($dateFin));
        $rdv->setDescription($data["description"]);
        $em->persist($rdv);
        $em->flush();

        $url = $this->generateUrl(
            'rendezvous',
            array('date' => $rdv->getDateDebut()->format('Y-m-d'))
        );
        return $this->redirect($url);
    }

     /**
    * @Route("/delete")
    * @Method("POST")
    */
    public function delete(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $em = $this
              ->getDoctrine()
              ->getManager()      
        ;

        $rendezvous = $em->getRepository('App:RendezvousEntity')->find($data);
        $em->remove($rendezvous);
        $em->flush();

        $url = $this->generateUrl(
            'rendezvous',
            array('date' => $rendezvous->getDateDebut()->format('Y-m-d'))
        );
        
        return $this->redirect($url);
    }

    /**
    * @Route("/periode")
    * @Method("POST")
    * 
    */
    public function periode(Request $request)
    {      
        $data = $request->getContent();
        $data = json_decode($data, true); 
        return $this->respond(self::getPeriode($data));
    }


    /**
    * @Route("/rendezvous")
    * @Method("POST")
    */
    public function rendezVousByDate(Request $request) {     

       $date = $request->getContent();
       $date = json_decode($date, true); 

       if ($date == "") {
          $date = date("Y-m-d", mktime(0,0,0,date("m"),date("j"),date("Y")));
       }
       $repository = $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:RendezvousEntity')       
            ;   

       $periodesJournee = self::getPeriodeJournee(); 
       $rendezvousMatin = self::getRendezVousMatin($date, $repository);       
       $rendezvousApresMidi = self::getRendezVousApresMidi($date, $repository);
       $periodesMatin = self::getPeriodeMatin($date, $repository);
       $periodesApresMidi = self::getPeriodeApresMidi($date, $repository);

       //varier la couleur 
       $c = "couleur1";
       foreach ($rendezvousMatin as $key => $rdv) {
          $datetime = new \DateTime($rdv["dateDebut"]);
          $tableauRendezVousMatin[$datetime->getTimestamp()] = ["date" => $rdv["dateDebut"], "description" => $rdv["description"], "interval" => strval(intval($rdv["interval"])*(7/60))."em", "reserver" => true, "couleur" => $c];
          if ($c == "couleur1") {
            $c = "couleur2";
          } else {            
            $c = "couleur1";
          }
       }
       foreach ($rendezvousApresMidi as $key => $rdv) {
          $datetime = new \DateTime($rdv["dateDebut"]);
          $tableauRendezVousApresMidi[$datetime->getTimestamp()] = ["date" => $rdv["dateDebut"], "description" => $rdv["description"], "interval" => strval(intval($rdv["interval"])*(7/60))."em", "reserver" => true, "couleur" => $c];
          if ($c == "couleur1") {
            $c = "couleur2";
          } else {            
            $c = "couleur1";
          }
       }
       foreach ($periodesMatin as $key => $periode) {            
          $datetime = new \DateTime($periode["plageDispoDebut"]);
          $tableauRendezVousMatin[$datetime->getTimestamp()] = ["date" => "", "description" => "", "interval" => strval(intval($periode["interval"])*(7/60))."em", "reserver" => false, "couleur" => ""];
       }
       foreach ($periodesApresMidi as $key => $periode) {            
          $datetime = new \DateTime($periode["plageDispoDebut"]);
          $tableauRendezVousApresMidi[$datetime->getTimestamp()] = ["date" => "", "description" => "", "interval" => strval(intval($periode["interval"])*(7/60))."em", "reserver" => false, "couleur" => ""];
       }
     /*  asort($tableauRendezVousMatin);
       asort($tableauRendezVousApresMidi);*/

       $tableauFinaleRendezVous[] = ["matin" => $tableauRendezVousMatin,"apresmidi" =>  $tableauRendezVousApresMidi];
       
       $tabPeriodeJourneeFinale = array();

       foreach ($periodesJournee as $key => $periodeJournee) {
         $tabPeriodeJournee = array();

         $d = explode(":", $periodeJournee->getDebutJournee());
         $d = (int)$d[0];

         $f = explode(":", $periodeJournee->getFinJournee());
         $f = (int)$f[0];

         for ($i = $d; $i <= $f; $i++) {
              $tabPeriodeJournee[] = $i;
         }
         $tabPeriodeJourneeFinale[] = $tabPeriodeJournee;
       }
       
       $finale = ["rendezvous" => $tableauFinaleRendezVous, "periode" => $tabPeriodeJourneeFinale];

       return $this->respond($finale);
    }

    //retourne les périodes avec rendez vous du matin
    private function getRendezVousMatin($data, $repository) {
      return self::getRendezVousByDate($data, 1, $repository);
    }

    //retourne les périodes avec rendez vous de l'apres midi
    private function getRendezVousApresMidi($data, $repository) {
      return self::getRendezVousByDate($data, 2, $repository);
    }    

    //retourne les périodes sans rendez vous du matin
    private function getPeriodeMatin($data, $repository) {
      return self::getPeriode($data, 1, $repository);
    }

    //retourne les périodes sans rendez vous de l'apres midi
    private function getPeriodeApresMidi($data, $repository) {
      return self::getPeriode($data, 2, $repository);
    }

    //retourne les rendez vous
    private function getRendezVousByDate($date, $id, $repository) {             

        $tab = $repository->getRendezVousByDate($date, $id); 
        $tabArray = array();

        foreach ($tab as $obj) {
            $interval = strtotime($obj->getDateFin()->format('Y-m-d H:i:s')) - strtotime($obj->getDateDebut()->format('Y-m-d H:i:s'));
            $tabArray[] = ["id" => $obj->getId(), "description" => $obj->getDescription(), "dateDebut" => $obj->getHeureDebut(), "dateFin" => $obj->getHeureFin(), "interval" => $interval/60];
        }

        return $tabArray;
    }

    private function getPeriode($data, $id, $repository) {       

        $periodeJournee = self::getPeriodeJourneeById($id);
        
        $rdvs = $repository->getRendezVousByDate($data, $id);  

        $heureDebut = $periodeJournee->getDebutJournee();
        $heureFin = $periodeJournee->getFinJournee();
        $plageHoraireDebut = new \DateTime($data." ".$periodeJournee->getDebutJournee());
        $plageHoraireFinDeJournée = new \DateTime($data." ".$periodeJournee->getFinJournee());
        foreach ($rdvs as $value) {  
            $plageHoraireFin = $value->getDateDebut();

            $interval = strtotime($plageHoraireFin->format('Y-m-d H:i:s')) - strtotime($plageHoraireDebut->format('Y-m-d H:i:s'));

            //si l'interval est supérieur à 0 => la période est supérieure a 0mn
            if ($interval > 0) {
                $tableauPlageHoraireDisponible[] = array("plageDispoDebut" => $heureDebut, "plageDispoFin" => $value->getHeureDebut(), "interval" => $interval/60);
            }
            $plageHoraireDebut = $value->getDateFin(); 
            $heureDebut = $value->getHeureFin();
        }

        //prendre en compte la fin de journée et une prise de RVD égale aux limites des journées
        if ($plageHoraireDebut != $plageHoraireFinDeJournée) {            
            $interval = strtotime($plageHoraireFinDeJournée->format('Y-m-d H:i:s')) - strtotime($plageHoraireDebut->format('Y-m-d H:i:s'));
            $tableauPlageHoraireDisponible[] = array("plageDispoDebut" => $plageHoraireDebut->format('H:i'), "plageDispoFin" => $heureFin, "interval" => $interval/60);
        }

        return $tableauPlageHoraireDisponible;        
    }

    private function getPeriodeJourneeById($id) {
      return $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:PeriodeEntity') 
              ->find($id)        
            ;
    }

    private function getPeriodeJournee() {
      return $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:PeriodeEntity') 
              ->findAll()        
            ;
    }
}