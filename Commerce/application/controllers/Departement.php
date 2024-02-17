<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."controllers/Session_Departement.php");
class Departement extends Session_Departement{
    
    public function __construct() {
        parent::__construct();	
		$this->load->model('Entreprise');
		$this->load->model('Materiel');
		$this->load->model('DepartementModel');
    }

    public function index()
	{
		$datas['contenu']= "pages/departement/contenu/contenu_index";
		$datas['title'] = "Departement";
		$datas['title_headers'] = "Departement";
		$datas['entreprise'] = $this->Entreprise->entreprise();
		$departement_id = $this->session->departement['id_departement'];
		$datas['emails'] = $this->DepartementModel->liste_departement($departement_id);
		$this->load->view('pages/departement/layout', $datas);
	}

	public function validation_demande(){
		$this->load->model('Demande');
		$id_departement = $this->session->departement['id_departement'];; 
		$id_categorie = $this->input->post('materiel');
		$id_produit = $this->input->post('designation');
		$quantite = $this->input->post('quantite');
		$justificatif = $this->input->post('justificatif');
		$data = array(
			'departement_id' => $id_departement,
			'produit_id' => $id_produit,
			'quantite' => $quantite,
			'justificatif' => $justificatif
		);
		try {
			$this->Demande->insert($data);
			$response = array('success' => true);
		} catch (\Throwable $th) {
			$response = array('success' => false, 'message' => 'Échec de l enregistrement . Veuillez vérifier vos informations.');
		}
		echo json_encode($response);
	}

    public function demande(){
		$datas['contenu']= "pages/departement/contenu/contenu_demande";
		$datas['title'] = "Demande";
		$datas['title_headers'] = "Demande";
		$datas['departements'] = $this->DepartementModel->liste_departement();
		$datas['materiels'] = $this->Materiel->liste_materiel();
		$this->load->view('pages/departement/layout', $datas);
	}

    public function getProduitsCategorie() {
        $idCategorie = $this->input->post('idCategorie');
		$produits = $this->Materiel->liste_produits($idCategorie);
  	 	echo json_encode($produits);
    }


}