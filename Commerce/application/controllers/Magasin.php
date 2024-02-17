<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."controllers/Session_Magasin.php");
class Magasin extends Session_Magasin{
    public function __construct() {
        parent::__construct();	
		$this->load->model('Entreprise');
		$this->load->model('MagasinModel');
    }

    public function index()
	{
        $this->load->model('Reception');
		$datas['contenu']= "pages/magasin/contenue/contenu_index";
		$datas['title'] = "Magasin";
		$datas['title_headers'] = "Liste des bon de reception";
		$datas['entreprise'] = $this->Entreprise->entreprise();
        $datas['listes'] =  $this->Reception->list_bon_reception();
		//var_dump($datas['listes']);
        $this->load->view('pages/magasin/layout', $datas);
	}

    public function detail_reception($numero_reception){
        $this->load->model('Reception');
		$datas['contenu']= "pages/magasin/contenue/contenu_detail_reception";
		$datas['title'] = "Magasin";
		$datas['title_headers'] = "Detail de bon de reception";
		$datas['entreprise'] = $this->Entreprise->entreprise();
        $datas['details'] =  $this->Reception->detail_bon_reception($numero_reception);
		//var_dump($datas['listes']);
        $datas['numero_reception'] = $numero_reception;
        $this->load->view('pages/magasin/layout', $datas);
    }

    public function ajout_entrer(){
        $this->load->model('Entrer');
        $produitId = $this->input->post('produit_id');
        $numero_reception = $this->input->post('numero_reception');
        $quantite = $this->input->post('quantite');
        $tax = $this->input->post('tax');
        $prix_uniatire = $this->input->post('prix_uniatire');
        $data = array(
            'produit_id' => $produitId,
            'numero_reception' => $numero_reception,
            'quantite' => $quantite,
            'tax' => $tax,
            'prix_uniatire' => $prix_uniatire		
        );
        $this->Entrer->ajoute_entrer($data);
        echo json_encode($data);
    }

    public function validation_entrer(){
        $this->load->model('Entrer');
        $numero_reception = $this->input->post('numero_reception');
        $responsable = $this->input->post('responsable');
        $date = $this->input->post('date');
        $descriptions = $this->input->post('descriptions');
        $data = array(
            'numero_reception' => $numero_reception,
            'responsable' => $responsable,
            'date' => $date,
            'descriptions' => $descriptions
        );
        $val = $this->Entrer->validation_bon_entrer($responsable, $date, $numero_reception);
        
        echo json_encode(['status' => 'success', 'message' => $val]);
    }


    public function demande_sortie(){
        $this->load->model('Materiel');
        $datas['contenu']= "pages/magasin/contenue/contenu_sortie";
		$datas['title'] = "Magasin";
		$datas['title_headers'] = "Demande de sortie de Produit";
        $datas['materiels'] = $this->Materiel->liste_materiel();
        $this->load->view('pages/magasin/layout', $datas);
    }

    public function sortie(){
        $this->load->model('Sortie');
        $materiel = $this->input->post('materiel');
        $designation = $this->input->post('designation');
        $quantite = $this->input->post('quantite');
        $justificatif = $this->input->post('justificatif');
        $date = $this->input->post('date');

        $data = array(
            'materiel' => $materiel,
            'produit_id' => $designation,
            'quantite' => $quantite,
            'justificatif' => $justificatif,
            'date' => $date
        );
        try {
            $this->Sortie->sortie($data);
            $response = array('status' => true, 'message' => $data);
            echo json_encode($response);
        } catch (\Throwable $e) {
            $response = array('status' => false, 'message' =>  $e->getMessage());
            echo json_encode($response);
        }
       
    }

    public function list_sortie_non_valider(){
        $this->load->model('Sortie');
        $datas['contenu']= "pages/magasin/contenue/contenu_sortie_non_valider";
		$datas['title'] = "Magasin";
		$datas['title_headers'] = "Liste des sorties non valider";
        $datas['listes'] = $this->Sortie->list_non_valider();
        $this->load->view('pages/magasin/layout', $datas);
    }

    public function validation_sortie(){
       try {
            $this->load->model('Sortie');
            $dateEtHeure = $this->input->post('date');
            $id_sortie_mere = $this->input->post('id_sortie_mere');
        
            $data = array(
                'status_cloture' => 20,
                'date_validation' => $dateEtHeure
            );
            $this->Sortie->validation_mere($data, $id_sortie_mere);
            echo json_encode(['status' => true, 'message' => $data]);
       } catch (\Throwable $th) {
            echo json_encode(['status' => false, 'message' => "erreur sur la validation"]);
       }
    }
    public function annulation_sortie($id_sortie_mere){
        $data = array(
            'status_cloture' => 0
        );
        $this->load->model('Sortie');
        $this->Sortie->update_sortie_mere($data, $id_sortie_mere);
        $this->list_sortie_non_valider();
    }

    public function etat_stock(){
        $datas['contenu']= "pages/magasin/contenue/contenu_etat_stock";
		$datas['title'] = "Magasin";
		$datas['title_headers'] = "Etat de stock";
        $this->load->view('pages/magasin/layout', $datas);
    }

    public function reponse_etat_stock(){
        $this->load->model('Etat_stock');
        $debut = $this->input->post('debut');
        $fin = $this->input->post('fin');
        $data =  $this->Etat_stock->etat_stock($debut, $fin);
        echo json_encode($data);
    }

    public function list_bon_entrer_valider(){
        $this->load->model('Entrer');
        $datas['contenu']= "pages/magasin/contenue/contenu_list_entrer";
		$datas['title'] = "Magasin";
		$datas['listes'] =  $this->Entrer->listes_bon_entrer();
		$datas['title_headers'] = "Liste Bon entrer";
        $this->load->view('pages/magasin/layout', $datas);
    }



    /****** Hitorique Entrant  *****/
    public function historique_entrant(){
        $this->load->model('Materiel');
        $datas['contenu']= "pages/magasin/contenue/contenu_histo_entrant";
		$datas['title'] = "Magasin";
		$datas['title_headers'] = "Demande  Hitsorique Entré";
        $datas['materiels'] =  $this->Materiel->liste_materiel();
        $this->load->view('pages/magasin/layout', $datas);
    }

    public function rechercher_materiel() {
        $this->load->model('Historique');
        $materiel = $this->input->post('materiel');
        $designation = $this->input->post('designation');
        $result = array();
        if($designation == "0"){
            $result = $this->Historique->historique_categorie($materiel);
        }else{
            $result = $this->Historique->historique_produit($designation);
        }  

        echo json_encode($result);
    }

    public function historique_sortie(){
        $this->load->model('Materiel');
        $datas['contenu']= "pages/magasin/contenue/contenu_histo_sortie";
		$datas['title'] = "Magasin";
		$datas['title_headers'] = "Demande  Hitsorique Sortie";
        $datas['materiels'] =  $this->Materiel->liste_materiel();
        $this->load->view('pages/magasin/layout', $datas);
    }

    public function getProduitsCategorie() {
        $this->load->model('Materiel');
        $idCategorie = $this->input->post('idCategorie');
		$produits = $this->Materiel->liste_produits($idCategorie);
  	 	echo json_encode($produits);
    }

    public function histo_sortie() {
        $this->load->model('Historique');
        $materiel = $this->input->post('materiel');
        $designation = $this->input->post('designation');
        $result = $this->Historique->historique_sortie($materiel, $designation);
        echo json_encode($result);
    }

    public function getDetailSortie(){
       
            $this->load->model('Historique');
            $idSortieMere = $this->input->post('idSortieMere');
            $datas['detailsSortie'] = $this->Historique->historique_detail_sortie($idSortieMere);
		    $this->load->view('pages/magasin/partials/partial_detail_sortie', $datas); 
       
    }
    public function getDataChart(){
        $this->load->model('Historique');
        $categorie_id = $this->input->post('categorie_id');
        $data = $this->Historique->chart($categorie_id);
        echo json_encode($data);
    }


}