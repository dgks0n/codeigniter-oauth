<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of facebook
 *
 * @author macos
 */
class Facebook extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $facebook_config = array(
            'client_id' 	=> $this->config->item('facebook_app_id'),
            'client_secret'	=> $this->config->item('facebook_secret'),
            'callback_url'	=> 'http://127.0.0.1:8888/facebook',
            'access_token'	=> NULL
        );

        $this->load->library('FacebookOAuth', $facebook_config);
    }
    
    public function index() {
        if (isset($_GET['code'])) {
            $this->data['token'] = $this->facebookoauth->getAccessToken($_GET['code']);
            $this->data['me'] = $this->facebookoauth->get('/me');
            $this->data['friends'] = $this->facebookoauth->get('/me/friends');

            $parameters = array(
                "message" => "This post came from my app!"
            );

            $this->data['post'] = $this->facebookoauth->post('/me/feed', $parameters);
            $this->load->vars('data', $this->data);
            $this->load->view('facebook_success', $this->data);
        } else {
            $scope = "publish_stream,offline_access,publish_stream";

            $this->data['auth_url'] = $this->facebookoauth->getAuthorizeUrl($scope);
            $this->load->vars('data', $this->data);
            $this->load->view('facebook_oauth', $this->data);
        }
    }
}

?>
