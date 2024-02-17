<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->model('Utilisateur');
    }		

    public function login_service(){
        $this->load->view('pages/login/service');
    }

    public function check_login_service() {
        $email= trim($this->input->post('email'));
        $mdp = trim($this->input->post('mdp'));
        $val = ($this->Utilisateur->test_login($email, $mdp));
        if ($val != null) {
            $detail = array( 
                'id_utilisateur' => $val[0]->id_utilisateur,
                'id_service' => $val[0]->type_status
            );
            $this->session->set_userdata('utilisateur_service', $detail);
            $response = array('success' => true, 'message' => 'Connexion réussie !', 'data' => $this->session->utilisateur_service);
        } else {
            $response = array('success' => false, 'message' => 'Échec de la connexion. Veuillez vérifier vos informations.');
        }
        echo json_encode($response);
    }
	
	public function logout_service(){
        $this->session->unset_userdata('utilisateur_service');
		redirect(base_url());
    }


    /**************** Fournisseur **********/
    public function login_fournisseur(){
        $this->load->view('pages/login/fournisseur');
    }

    public function logout_fournisseur(){
        $this->session->unset_userdata('fournisseur');
        $this->load->view('pages/login/fournisseur');
    }

    public function check_login_fournisseur() {
        $this->load->model('FournisseurModel');
        $email= trim($this->input->post('email'));
        $mdp = trim($this->input->post('mdp'));
        $val = ($this->FournisseurModel->test_login($email, $mdp));
        if ($val != null) {
            $detail = array( 
                'id_fournisseur' => $val[0]->id_fournisseur
            );
            $this->session->set_userdata('fournisseur', $detail);
            $response = array('success' => true, 'message' => 'Connexion réussie !', 'data' => $this->session->fournisseur);
        } else {
            $response = array('success' => false, 'message' => 'Échec de la connexion. Veuillez vérifier vos informations.');
        }
        echo json_encode($response);
    }

    /******* Magasin  *****/
    public function login_magasin(){
        $this->load->view('pages/login/magasin');
    }

    public function check_login_magasin() {
        $this->load->model('MagasinModel');
        $email= trim($this->input->post('email'));
        $mdp = trim($this->input->post('mdp'));
        $val = ($this->MagasinModel->test_login($email, $mdp));
        if ($val != null) {
            $detail = array( 
                'id_magasin' => $val[0]->id_magasin
            );
            $this->session->set_userdata('magasin', $detail);
            $response = array('success' => true, 'message' => 'Connexion réussie !', 'data' => $this->session->fournisseur);
        } else {
            $response = array('success' => false, 'message' => 'Échec de la connexion. Veuillez vérifier vos informations.');
        }
        echo json_encode($response);
    }

    public function logout_magasin(){
        $this->session->unset_userdata('magasin');
        $this->load->view('pages/login/magasin');
    }

    /***** Departement ******/
    public function login_departement(){
        $datas['title'] = "Departement";
        $this->load->view('pages/login/departement' , $datas);
    }

    public function logout_departement(){
        $this->session->unset_userdata('departement');
        $this->load->view('pages/login/departement');
    }

    public function check_login_departement() {
        $this->load->model('DepartementModel');
        $email= trim($this->input->post('email'));
        $mdp = trim($this->input->post('mdp'));
        $val = ($this->DepartementModel->test_login($email, $mdp));
        if ($val != null) {
            $detail = array( 
                'id_departement' => $val[0]->id_departement
            );
            $this->session->set_userdata('departement', $detail);
            $response = array('success' => true, 'message' => 'Connexion réussie !', 'data' => $this->session->departemnent);
        } else {
            $response = array('success' => false, 'message' => 'Échec de la connexion. Veuillez vérifier vos informations.');
        }
        echo json_encode($response);
    }


}
