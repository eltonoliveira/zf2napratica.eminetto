<?php

use Core\Test\ControllerTestCase;
use Application\Controller\IndexController;
use Application\Model\Post;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
* @group Controller
*/
class IndexControllerTest extends ControllerTestCase
{
	/**
	* Namespace completa do Controller
	* @var string
	*/
	protected $controllerFQDN = 'Application\Controller\IndexController';
	/**
	* Nome da rota. Geralmente o nome do módulo.
	* @var string
	*/
	protected $controllerRoute = 'application';

	/**
	* Testa o acesso a uma action que não existe.
	*/
	public function test404()
	{
		$this->routeMatch->setParam('action', 'action_nao_existente');
		$result   = $this->controller->dispatch($this->request);
		$response = $this->controller->getResponse();
		self::assertEquals(404, $response->getStatusCode());
	}

	/**
	* Testa a página inicial, que deve mostrar os posts
	*/
	public function testIndexAction()
	{
		$postA = self::_addPost();
		$postB = self::_addPost();

		// invoca a rota index
		$this->routeMatch->setParam('action', 'index');
		$result = $this->controller->dispatch($this->request, $this->response);

		//Verifica o response
		$response = $this->controller->getResponse();
		self::assertEquals(200, $response->getStatusCode());

		// Testa se um ViewModel foi retornado
		self::assertInstanceOf('Zend\View\Model\ViewModel', $result);

		//Testa os dados da view
		$variables = $result->getVariables();
		self::assertArrayHasKey('posts', $variables);

		// Faz a comparação dos dados
		$controllerData = $variables['posts'];
		self::assertEquals($postA->title, $controllerData[0]['title']);
		self::assertEquals($postB->title, $controllerData[1]['title']);
	}

	protected function _addPost()
	{
		$post = new Post();
		$post->title = 'Apple compra a Coderockr';
		$post->description = 'A Apple compra a <b>Coderockr</b><br> ';
		$post->post_date = date('Y-m-d H:i:s');

		return self::getTable('Application\Model\Post')->save($post);
	}


}