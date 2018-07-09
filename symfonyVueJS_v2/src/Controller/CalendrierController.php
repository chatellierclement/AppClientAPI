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

    /**
    * @Route("/rendezvous/{date}", name="rendezvous")
    */
    public function getRendezVousByDate($date) {
        $repository = $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:RendezvousEntity')         
            ;

        $tab = $repository->getRendezVousByDate($date); 
        $tabArray = array();

        foreach ($tab as $obj) {
            $tabArray[] = ["id" => $obj->getId(), "description" => $obj->getDescription(), "dateDebut" => $obj->getHeureDebut(), "dateFin" => $obj->getHeureFin()];
        }

        return $this->respond($tabArray);
    }

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
    */
    public function periode(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $periodeJournee = $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:PeriodeEntity') 
              ->find(1)        
            ;

        $repository = $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:RendezvousEntity')         
            ;

        $tableauPlageHoraireDisponible = array();
        
        $rdvs = $repository->getRendezVousByDate($data);  

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
        return $this->respond($tableauPlageHoraireDisponible);
    }
}