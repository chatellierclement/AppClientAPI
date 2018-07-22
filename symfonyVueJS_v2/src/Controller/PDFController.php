<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\PDFEntity;
use Symfony\Component\HttpFoundation\Request;

class PDFController extends ApiController
{
    
    /**
    * @Route("/pdf")
    */
    public function index(Request $request)
    {
       $mpdf = new \Mpdf\Mpdf();

       $test = "10 000";

       $enTeteGauche = "<div style='width:50%;float:left'>
                            <ul style='padding:0'>
                                <li style='list-style-type:none;'>Numéro de factures</li>
                                <li style='list-style-type:none;'>Emetteur : Entreprise</li>
                                <li style='list-style-type:none;'>Date : 07-07-2018</li>
                            </ul>
                        </div>";

       $enTeteDroite = "<div style='width:50%;float:right;text-align:right;'>Droite</div>";
        
       $objetDuDocument = "<div style='width:100%;margin-top: 5em'><h4>Objet : Facturation décomptes</h4></div>";

       $corps = "<div style='width:100%;margin-bottom: 5em'>
                    <div>Madame, Monsieur,</div>
                    <br>
                    <div style='line-height:1.5em'>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vestibulum neque vel ultricies condimentum. Suspendisse vitae lorem fringilla, fermentum nisi in, laoreet velit. Donec dignissim placerat lobortis. Quisque ex turpis, mollis a blandit sed, congue tincidunt eros. Fusce augue massa, interdum at luctus in, feugiat convallis mauris. Mauris varius rutrum mauris. Praesent at euismod magna. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                    </div>
                    <div>Cordialement,</div>
                </div>";

       $netAPayer = "<div style='width:23%;padding:1em;float:right;border:1px solid black'>Net A Payer : ".$test." €</div>";

       $signature = "<div style='width:50%;left:40%;position:absolute;bottom:5%;text-align:right;'>Signature</div>";


       $affichage = $enTeteGauche.
                    $enTeteDroite.
                    $objetDuDocument.
                    $corps.
                    $netAPayer.
                    $signature;

       $mpdf->WriteHtml($affichage);

       // $mpdf->Output("C:\Users\Clement\Desktop\monPDF.pdf", "F");

        $mpdf->Output();
        var_dump($mpdf);

    }

   /**
    * @Route("/enregistrer")
    * @Method("POST")
    */
    public function update(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $em = $this
              ->getDoctrine()
              ->getManager()
              ;

        $modele = $em->getRepository('App:PDFEntity')->find($data["id"]);        

        $modele->setTexte($data["content"]);        
        $em->persist($modele);
        $em->flush();

        return $this->respond($modele);
    }

    /**
    * @Route("/modele")
    */
    public function modele()
    {        

        $modele = $this
              ->getDoctrine()
              ->getManager()
              ->getRepository('App:PDFEntity') 
              ->find(1)        
            ;       

        $tabModele = ["id" => $modele->getId(), "texte" => $modele->getTexte() ];

        return $this->respond($tabModele);
    }
}