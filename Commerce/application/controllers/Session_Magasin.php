<?php ?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_Magasin extends CI_Controller {

	public function __construct(){
        parent::__construct();
        if ($this->session->has_userdata('magasin') == false){
            redirect(site_url('Login/login_magasin'));
        }        
    }
}
