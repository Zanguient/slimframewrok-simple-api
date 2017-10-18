<?php


/**
 * @isSecure: false
 */
class IApiLoginService {

    public $app;

    public function __construct(){
        $this->app = Slim::getInstance();

    }

    public function checkToken() {

		//$email = $this->request->headers('email');
		//$senha = $this->request->headers('senha');

		//echo json_encode(ORM::for_table('usuario')->where(array('email'=> $email, 'senha'=> $senha))->find_array());

        echo 'hello inside the class';
    }
}

?>