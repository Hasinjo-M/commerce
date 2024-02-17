<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."controllers/Session_Service.php");
class Admin extends Session_Service{
    public function __construct() {
        parent::__construct();	
		$this->load->model('Materiel');
		$this->load->model('DepartementModel' , 'Departement');
    }
	
	public function index()
	{
		$datas['contenu']= "pages/admin/contenue/contenu_index";
		$datas['title'] = "Admin";
		$datas['title_headers'] = "Admin";
		$this->load->view('pages/admin/layout', $datas);
	}		

	public function demande(){
		$datas['contenu']= "pages/admin/contenue/contenu_demande";
		$datas['title'] = "Demande";
		$datas['title_headers'] = "Demande";
		$datas['departements'] = $this->Departement->liste_departement();
		$datas['materiels'] = $this->Materiel->liste_materiel();
		$this->load->view('pages/admin/layout', $datas);
	}

	public function getProduitsCategorie() {
        $idCategorie = $this->input->post('idCategorie');
		$produits = $this->Materiel->liste_produits($idCategorie);
  	 	echo json_encode($produits);
    }

	public function validation_demande(){
		$this->load->model('Demande');
		$id_departement = $this->input->post('departement'); 
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

	public function demande_non_groupe(){
		$this->load->model('Demande');
		$datas['contenu']= "pages/admin/contenue/contenu_demande_ngroupe";
		$datas['title'] = "Demande";
		$datas['title_headers'] = "Liste des demandes non groupés";
		$datas['demandes'] = $this->Demande->demande_non_groupe();
		$data['detailsGroupe'] =  [];
		$this->load->view('pages/admin/layout', $datas);
	}

	public function demande_groupe(){
		$this->load->model('Demande');
		$datas['contenu']= "pages/admin/contenue/contenu_demande_groupe";
		$datas['title'] = "Demande";
		$datas['title_headers'] = "Liste des demandes  groupés";
		$datas['demandes'] = $this->Demande->demande_groupe();
		$this->load->view('pages/admin/layout', $datas);
	}

	public function groupe_demande(){
		$this->load->model('Demande');
		try {
			$demandes = $this->input->post('demandes'); 
			$numero = $this->Demande->update_numero_group($demandes);
			$response = array('success' => true );
		} catch (\Throwable $th) {
			$response = array('success' => false, 'message' => 'Échec de l enregistrement . Veuillez vérifier vos informations.' );
		}
		echo json_encode($response);
	}

	public function details_groupe(){
		$this->load->model('Demande');
		$numeroGroupe = $this->input->post('numero_groupe');
		$detailsGroupe = $this->Demande->details_produit($numeroGroupe);
		$datas['detailsGroupe'] = $detailsGroupe;
		$datas['contenu']= "pages/admin/contenue/contenu_demande_groupe";
		$datas['title'] = "Demande";
		$datas['title_headers'] = "Liste des demandes  groupés";
		$datas['demandes'] = $this->Demande->demande_groupe();
		$this->load->view('pages/admin/partials/partial_details_groupe', $datas);
	}

	public function detail_envoye_email_groupe(){
		$this->load->model('Demande');
		$this->load->model('FournisseurModel');
		$numeroGroupe = $this->input->post('numero_groupe');
		$detailsGroupe = $this->Demande->details_groupe_email($numeroGroupe);
		$datas['detailsGroupe'] = $detailsGroupe;
		$datas['contenu']= "pages/admin/contenue/contenu_demande_groupe";
		$datas['title'] = "Demande";
		$datas['title_headers'] = "Liste des demandes  groupés";
		$datas['demandes'] = $this->Demande->demande_groupe();
		$datas['fournisseurs'] = $this->FournisseurModel->liste_fournisseurs();
		$this->load->view('pages/admin/partials/partial_envoye_email', $datas);
	}

	public function detail_proformats($id_group){
		$this->load->model('Proforma');
		$datas['contenu']= "pages/admin/contenue/contenu_proformats";
		$datas['title'] = "Proformats";
		$datas['title_headers'] = "Proformats";
		$datas['proforma_groups'] = $this->Proforma->list_proforma_group($id_group);
		$datas['proformas'] = $this->Proforma->list_proforma_admin($id_group, $datas['proforma_groups']);
		$datas['numero'] = $id_group;
		$datas['detailsGroupe'] = $this->Proforma->details_groupe($id_group);
		$this->load->view('pages/admin/layout', $datas);
	}

	public function envoye_email(){
		$this->load->model('FournisseurModel');
		$numero_group = $this->input->post('numero_group');
		$fournisseurs = $this->input->post('fournisseurs'); 
		$object = $this->input->post('object');
		$description = $this->input->post('description');
		if (count($fournisseurs) >= 3) {
			$this->FournisseurModel->insert_email($numero_group, $object, $description, $fournisseurs);
			echo "Formulaire traité avec succès !";
		} else {
			echo "Erreur : Sélectionnez au moins 3 fournisseurs.";
		}
	}

	public function valider_proforma($proforma_id, $id_group, $produit_id){
		$this->load->model('Bon_commande');
		$this->Bon_commande->insert($proforma_id, $id_group, $produit_id);
		$this->detail_proformats($id_group);
	}

	public function bon_commande($id_group, $fournisseur_id){
		$this->load->model('Proforma');
		$this->load->model('Entreprise');
		$this->load->model('FournisseurModel');
		$datas['contenu']= "pages/admin/contenue/contenu_bon_commande";
		$datas['title'] = "Bon_de_commande";
		$datas['title_headers'] = "Bon de commande";
		$datas['commandes'] = $this->Proforma->list_commande($id_group, $fournisseur_id);
		$datas['entreprise'] = $this->Entreprise->entreprise();
		$datas['fournisseur'] = $this->FournisseurModel->fournisseur($fournisseur_id);
		$this->load->view('pages/admin/contenue/contenu_bon_commande', $datas);
	}

	public function de_groupement(){
		$this->load->model('Demande');
		$this->load->model('Proforma');
		$demande_id = $this->input->post('id_demande');
		$numero_groupe = $this->input->post('numero_groupe');
		$data = array(
			'demande_id' => $demande_id,
			'numero'  => $numero_groupe
		);
		try {
			$res = $this->Proforma->list_proforma_group($numero_groupe);
			if($res == null){
				$this->Demande->de_groupement($demande_id);
				$detailsGroupe = $this->Demande->details_produit($numeroGroupe);
				$datas['detailsGroupe'] = $detailsGroupe;
				$datas['contenu']= "pages/admin/contenue/contenu_demande_groupe";
				$datas['title'] = "Demande";
				$datas['title_headers'] = "Liste des demandes  groupés";
				$datas['demandes'] = $this->Demande->demande_groupe();
				$response = array('success' => true, 'data' =>  $detailsGroupe);
			}else{
				$response = array('success' => false , 'data' => $data);
			}
		} catch (\Throwable $th) {
			$response = array('success' => false ,'data' => $data);
		}
		echo json_encode($response);
	}

	public function Degroup($numero_group){
		$this->load->model('Demande');
		$data['Nongroup'] = $this->Demande->mise_a_jour_demande($numero_group);
		$this->demande_non_groupe();
	}

	public function Viewvalidation(){
		$datas['departements'] = $this->Departement->liste_departement();
		$datas['contenu']= "pages/admin/contenue/contenu_demande_non_valide";
		$datas['title'] = "Validation";
		$datas['title_headers'] = "Validation des commandes";
		$this->load->view('pages/admin/layout', $datas);
	}

	public function getDemande_dept() {
		$id_dept = $this->input->post('idDept');
		$this->load->model('Demande_dept');
		$demande = $this->Demande_dept->demande_non_valid_par_dept($id_dept);
		echo json_encode($demande);
	}

	public function validation_commande(){
		$this->load->model('Demande');
		$this->load->model('Demande_dept');
	
		// Récupération des détails de la demande à valider
		$demande_a_valider = $this->Demande_dept->demande_valider($this->input->get('id'));
		echo $demande_a_valider[0]->departement_id;
		if ($demande_a_valider) {
			$data = array(
				'departement_id' => $demande_a_valider[0]->departement_id,
				'produit_id' => $demande_a_valider[0]->produit_id,
				'quantite' => $demande_a_valider[0]->quantite,
				'justificatif' => $demande_a_valider[0]->justificatif
				// Assurez-vous que ces clés correspondent aux colonnes de votre table 'Demande'
			);
	
			try {
				// Insertion des données dans la table 'Demande'
				$this->Demande->insert($data);
	
				// Mise à jour du statut de validation dans le modèle 'Demande_dept'
				$this->Demande_dept->mise_a_jour_validation($this->input->get('id'));
	
				$response = array('success' => true);
			} catch (\Throwable $th) {
				$response = array('success' => false, 'message' => 'Échec de l\'enregistrement. Veuillez vérifier vos informations.');
			}
		} else {
			$response = array('success' => false, 'message' => 'Demande non trouvée.');
		}
		$this->demande_non_groupe();
		
	}

	/******* annullation bon de commande  ******/
	public function bon_commande_anuller($id_group, $bon_commande_mere_id){
		$this->load->model('Bon_commande');
		$this->Bon_commande->annulation_bon_commande($bon_commande_mere_id);
		$this->detail_proformats($id_group);
	}


	/*** Liste de livraison  ***/
	public function list_livraison(){
		$this->load->model('Livraison');
		$datas['contenu']= "pages/admin/contenue/contenu_list_livraison";
		$datas['title'] = "Liste_livraison";
		$datas['title_headers'] = "Liste des livraisons";
		$datas['livraisons'] = $this->Livraison->list_bon_livraison();
		$this->load->view('pages/admin/layout', $datas);
	}

	/******  Detail livraison *****/
	public function detail_livraison($fournisseur_id, $numero_livraison){
		$this->load->model('Livraison');
		$datas['contenu']= "pages/admin/contenue/contenu_de_livraison";
		$datas['title'] = "Livraison";
		$datas['title_headers'] = "Detail livraison";
		$datas['details']  = $this->Livraison->detail_livraison($numero_livraison, $fournisseur_id);
		$this->load->view('pages/admin/layout', $datas);
	}

	/****** Ajout de reception  ******/
	public function ajout_reception(){
		$this->load->model('Reception'); 
		$quantite = $this->input->post('quantite');
        $numero_livraison = $this->input->post('numero_livraison');
        $produit_id = $this->input->post('produit_id');
        $tax = $this->input->post('tax');
        $prix_uniatire = $this->input->post('prix_uniatire');

        $data = array(
            'quantite' => $quantite,
            'numero_livraison' => $numero_livraison,
            'produit_id' => $produit_id,
            'tax' => $tax,
            'prix_uniatire' => $prix_uniatire		
        );
		
		$this->Reception->reception_produit($data);
		echo json_encode($data);
	}

	/**** validation reception ****/
	public function validation_reception(){
		 $livraisonNumero = $this->input->post('livraison_numero');
		 $responsable = $this->input->post('responsable');
		 $date = $this->input->post('date');
		 $descriptions = $this->input->post('descriptions');
		$data = array(
			 'livraison_numero' => $livraisonNumero,
			 'responsable' => $responsable,
			 'date_reception' => $date,
			 'descriptions' => $descriptions
		 );
		 $this->load->model('Reception'); 
		 $this->Reception->validation_reception($date, $descriptions, $responsable, $livraisonNumero);
		 echo json_encode($data);
	}
	public function list_bon_reception(){
		$this->load->model('Reception');
		$datas['contenu']= "pages/admin/contenue/contenu_list_reception";
		$datas['title'] = "Livraison";
		$datas['title_headers'] = "Lites de bon de receptions";
		$datas['listes']  = $this->Reception->list_bon_reception_valider();
		$this->load->view('pages/admin/layout', $datas);	
	}

}
