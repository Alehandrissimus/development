<?php
/**
 * User Controller
 *
 * @author Serhii Shkrabak
 * @global object $CORE
 * @package Controller\Main
 */
namespace Controller;
class Main
{
	use \Library\Shared;

	private $model;

	public function exec():?array {
		$result = null;
		$url = $this->getVar('REQUEST_URI', 'e');

		//path получает массив из пути url и разделяет в массив
		//od.ua/form/submitAmbassador => path ['form', 'submitAmbassador]

		$path = explode('/', $url);

		if (isset($path[2]) && !strpos($path[1], '.')) { // Disallow directory changing
			$file = ROOT . 'model/config/methods/' . $path[1] . '.php';

			//$path[2] = substr($path[2], 0, strpos($path[2], "?"));  //ОБНОВ отрезаю квери от урлы                    ОБНОВ НУЖНО РАЗОБРАТЬ КВЕРИ, они находятся в $path[2]
			
			if (file_exists($file)) {
				include $file;
				
																												// ОБНОВ, не регает урлу ибо она с кверями
				//если существует path[2] (submitAmbassador)    // с параметрами в постмане не выполняется if
				if (isset($methods[$path[2]])) {
					//details = submitAmbassador форма form
					$details = $methods[$path[2]];                // ОБНОВ $methods неадекватно воспринимает submitAmbassador с кверями    все работетает кроме этого
					//request - изначально пустой
					$request = [];
				
					//для каждого параметра из form переноси в массив
					foreach ($details['params'] as $param) {
						$var = $this->getVar($param['name'], $param['source']);


						// !!!!!!! проблема - var пустой !!!!!!!!  // в var методе должно возвращатся значение
						if ($var) {
							//throw new \Exception('works:  ');
							$request[$param['name']] = $var;
						}
					}

					/*
					$request = array(
						'firstname' => 'Kostya',
						'secondname' => 'Dobr',
						'position' => 'asda',
						'phone' => '012412414',
					);
					*/

					if (method_exists($this->model, $path[1] . $path[2])) {
						$method = [$this->model, $path[1] . $path[2]];
						$result = $method($request);
						
					}
				}

			}
		}

		return $result;
	}

	public function __construct() {
		// CORS configuration
		$origin = $this -> getVar('HTTP_ORIGIN', 'e');
		$front = $this -> getVar('FRONT', 'e');

		foreach ( [$front] as $allowed )
			if ( $origin == "https://$allowed") {
				header( "Access-Control-Allow-Origin: $origin" );
				header( 'Access-Control-Allow-Credentials: true' );
			}
		$this->model = new \Model\Main;
	}
}