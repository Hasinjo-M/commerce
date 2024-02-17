<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."controllers/Session_Fournisseur.php");
class Fournisseur extends Session_Fournisseur{
    
    public function __construct() {
        parent::__construct();	
		$this->load->model('Entreprise');
		$this->load->model('FournisseurModel');
    }

    public function index()
	{
		$datas['contenu']= "pages/fournisseur/contenue/contenu_index";
		$datas['title'] = "Fournisseur";
		$datas['title_headers'] = "Fournisseur";
		$datas['entreprise'] = $this->Entreprise->entreprise();
		$fournisseur_id = $this->session->fournisseur['id_fournisseur'];
		$datas['emails'] = $this->FournisseurModel->liste_email($fournisseur_id);
		$this->load->view('pages/fournisseur/layout', $datas);
	}

	public function detail_email($groupe_numero){
		$this->load->model('Demande');
		$datas['contenu']= "pages/fournisseur/contenue/contenu_detail_email";
		$datas['title'] = "Fournisseur";
		$datas['title_headers'] = "Detail de l'email";
		$datas['details'] = $this->Demande->details_groupe_email($groupe_numero);
		$datas['groupe_numero'] = $groupe_numero;
		$detail = array( 
			'groupe_numero' => $groupe_numero
		);
		$this->session->set_userdata('groupe_numero', $detail);
		$this->load->view('pages/fournisseur/layout', $datas);
	}

	public function detail_creation_proforma(){
		$this->load->model('Materiel');
		$produit_id = $this->input->post('produit_id');
		$datas['detailsproduit'] = $this->Materiel->detail_produit($produit_id);
		$this->load->view('pages/fournisseur/partials/partial_detail_proforma', $datas);
	}

	public function creation_proforma(){
		$this->load->model('Proforma');
		$produit_id = $this->input->post('id_produit');
		$prix_unitaire = $this->input->post('prix_unitaire');
		$tax = $this->input->post('tax');
		$taux_tax = $this->input->post('taux_tax');
		$remise = $this->input->post('remise');
		$quantite = $this->input->post('quantite');
		$descrition = $this->input->post('description');
		$fournisseur_id = $this->session->fournisseur['id_fournisseur'];
		$groupe_numero = $this->session->groupe_numero['groupe_numero'];
		$data = array(
			'groupe_numero' => $groupe_numero,
			'fournisseur_id' => $fournisseur_id,
			'produit_id' => $produit_id,
			'prix_uniatire' => $prix_unitaire,
			'tax' => $tax,
			'taux_tax' => $taux_tax,
			'remise' => $remise,
			'quantite' => $quantite,
			'descriptions' => $descrition
		);
		try {
			$this->Proforma->insert_proforma($data);
			$response = array('success' => true , 'data' => $data);
		} catch (\Throwable $th) {
			$response = array('success' => false, 'data' => $data);
		}
		$this->session->unset_userdata('groupe_numero');
		echo json_encode($response);
	}


	/**** liste de bon_commande valider par fournisseur ****/
	public function list_bon_commande(){
		$datas['contenu']= "pages/fournisseur/contenue/contenu_bon_commande";
		$datas['title'] = "Fournisseur";
		$datas['title_headers'] = "Liste de bon de commande";
		$fournisseur_id = $this->session->fournisseur['id_fournisseur'];
		$this->load->model('Bon_commande');
		$datas['entreprise'] = $this->Entreprise->entreprise();
		$datas['bon_commandes'] = $this->Bon_commande->list_bon_commande($fournisseur_id);
		$this->load->view('pages/fournisseur/layout', $datas);
	}

	/**** detail d'un bon de commande  ****/
	public function detail_bon_commande($id_group, $numero_commande){
		$this->load->model('Proforma');
		$fournisseur_id = $this->session->fournisseur['id_fournisseur'];	
		$datas['contenu']= "pages/fournisseur/contenue/contenu_bon_commande_detail";
		$datas['title'] = "Fournisseur";
		$datas['numero_commande'] = $numero_commande;
		$datas['title_headers'] = "Details de bon de commande";
		$datas['entreprise'] = $this->Entreprise->entreprise();
		$datas['commandes'] = $this->Proforma->list_commande($id_group, $fournisseur_id);
		$this->load->view('pages/fournisseur/layout', $datas);
	}

	/***** modification proforma *****/
	public function modifProforma(){
		$this->load->model('Proforma');
		$quantite = $this->input->post('quantite');
        $prix_uniatire = $this->input->post('prix_uniatire');
        $tax = $this->input->post('tax');
   		$produit_id = $this->input->post('produit_id');
		$group_id = $this->input->post('group_id');
		$fournisseur_id = $this->session->fournisseur['id_fournisseur'];	
		$proforma_mere = $this->input->post('proforma_mere');
		$data = array(
			'quantite' => $quantite,
			'prix_uniatire' => $prix_uniatire,
			'tax' => 1,
			'taux_tax' => $tax,
			'produit_id' => $produit_id,
			'groupe_numero' => $group_id,
			'fournisseur_id' =>	$fournisseur_id,
			'proforma_mere' =>  $proforma_mere,
			'descriptions' => "Modifier"
		);
		$this->Proforma->insert_proforma_modif($data);
		$response = array('status' => 'success', 'message' => $data);
        echo json_encode($response);
	}
	public function livraison(){
		$this->load->model('Livraison');
		$quantite = $this->input->post('quantite');
        $prix_uniatire = $this->input->post('prix_uniatire');
        $tax = $this->input->post('tax');
        $produit_id = $this->input->post('produit_id');
        $group_id = $this->input->post('group_id');
        $proforma_mere = $this->input->post('proforma_mere');
        $numero_commande = $this->input->post('numero_commande');
		$fournisseur_id = $this->session->fournisseur['id_fournisseur'];	
		
        $data = array(
            'quantite' => $quantite,
            'prix_uniatire' => $prix_uniatire,
            'tax' => $tax,
            'produit_id' => $produit_id,
            'group_id' => $group_id,
            'proforma_mere' => $proforma_mere,
            'numero_commande' => $numero_commande,
            'fournisseur_id' => $fournisseur_id
        );
		$t=$this->Livraison->livraison($data);
		$response = array('status' => 'success', 'message' => $t);
        echo json_encode($response);
	}

	/**** validation livraison  *****/
	public function livraison_valider(){
		$lieu = $this->input->post('lieu');
		$date = $this->input->post('date');
		$group_id = $this->input->post('group_id');
		$fournisseur_id = $this->session->fournisseur['id_fournisseur'];	
		
		$donnees = array(
			 'lieu' => $lieu,
			 'date' => $date,
			 'group_id' => $group_id
		);
		$this->load->model('Livraison');
		$this->Livraison->validation_livraison($group_id, $fournisseur_id, $lieu, $date);
		$response = array('status' => 'success', 'message' => $donnees);
        echo json_encode($response);
	}

}