<?php

use \Slim\Slim;

abstract class IApi {

    public $app;
    public $response;
    public $request;
    public $config = null;
    public static $isSecure;

    private $types = array(
        'isSecure',
        'callback',
        'path',
        'require'
    );

    public function __construct(){

        $this->app = Slim::getInstance();

        $this->response = $this->app->response;
        $this->request = $this->app->request;

        // get the child class
        $this->config = $this->getClassAnnotations(get_called_class());

        if($this->config) {
            self::$isSecure = $this->config->isSecure;
        }

        $this->beforeCall();

        
    }

    // Check if the connection has the credential to talk to the server
    public function checkCredentials(){
        $granted = false;

		if($email = $this->request->headers('email')){
			$granted = true;
		}

		if($senha = $this->request->headers('senha')){
			$granted = true;
		}

        if($granted){
            if(count(ORM::for_table('usuario')->where(array('email'=> $email, 'senha'=> md5($senha)))->find_array()) > 0){
                $granted = true;
            } else {
                $granted = false;
            }
        }

        return $granted;
    }

    public function getParamsAsObject() {
        return (object) $this->request->post();
    }

    public function getParamsAsArray() {
        return $this->request->post();
    }

	public function getQuery($query){
		return $this->query[$query];
	}

    public function getPost(){
        return $_POST;
    }

    public function __call($name, $arguments) {
        // Note: value of $name is case sensitive.
        echo "Calling object method '$name' " . implode(', ', $arguments). "\n";
    }

    public function xp($content){
        echo $content;
    }

    public function prepareObj($obj = array(), $onlyObject = false, $objName = false){
        if(!$onlyObject){
            if(is_array($obj) && count($obj) > 0){
                return json_encode(array("sucess" => true, "session" => session_id(), "data" => $obj));
            } else {
                return json_encode(array("sucess" => false, "session" => session_id(), "data" => $obj));
            }
        } else {
            if(is_array($obj) && count($obj) > 0){
                return ($objName ? json_encode(array($objName => $obj)) : json_encode($obj, true ));
            }
        }
    }

    private function beforeCall(){
        if(self::$isSecure){
            if (!self::checkCredentials()) {

                $this->app->halt(
                    403,
                    json_encode((object) array('responseType' => 'accepted',
                                'result' => 'error', 
                                'msg' => 'This is a REST Api, you may be trying to access it through the browser, or the user and password could not be valid')
                    )
                );

            }
        }
    }


    function getClassAnnotations($class) {

        $params = array();

        $r = new ReflectionClass($class);
        $doc = $r->getDocComment();
        preg_match_all('#@(.*?)\n#s', $doc, $annotations);

        for ($i=0; $i < count($annotations[1]); $i++) {

            $cfgString = '"' . str_replace(':','":',trim($annotations[1][$i])); 

            // For each type defined in *types* variable, test what was found to return as property
            foreach($this->types as $isProp) {
                if( strpos($cfgString, $isProp) !== false ){
                    $params[] = $cfgString;
                }
            }
        }

        return (object) json_decode('{'.join(',', $params).'}');
    }

}