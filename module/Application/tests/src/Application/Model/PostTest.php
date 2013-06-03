<?php
namespace Application\Model;

use Core\Test\ModelTestCase;
use Application\Model\Post;
use Zend\InputFilter\InputFilterInterface;

/**
* @group Model
*/
class PostTest extends ModelTestCase
{
	public function testGetInputFilter()
	{
		$post 			= new Post();
		$inputFilter 	= $post->getInputFilter();
		self::assertInstanceOf('Zend\InputFilter\InputFilter', $inputFilter);
		return $inputFilter;
	}

    /**
    * @depends testGetInputFilter
    */
    public function testInputFilterValid($inputFilter)
    {
        self::assertEquals(4, $inputFilter->count());

        self::assertTrue($inputFilter->has('id'));
        self::assertTrue($inputFilter->has('title'));
        self::assertTrue($inputFilter->has('description'));
        self::assertTrue($inputFilter->has('post_date'));
    }

    /**
    * @expectedException Core\Model\EntityException
    */
    public function testInputFilterInvalido()
    {
        $post = new Post();
        $post->title = 'Lorem Ipsum e simplesmente uma simulacao de texto da industria tipografica e de impressos. 
        Lorem Ipsum é simplesmente uma simulacao de texto da industria tipografica e de impressos';
    }

    /**
    * Teste de inserção de um post válido
    */
    public function testInsert()
    {
        $post  = self::_addPost();
        $saved = self::getTable('Application\Model\Post')->save($post);
        
        // Testa o filtro de tags e espaços
        self::assertEquals('A Apple compra a Coderockr', $saved->description);

        // Testa o autoincrement da chave primária
        self::assertEquals(1, $saved->id);
    }

    /**
    * @expectedException        Core\Model\EntityException
    * @expectedExceptionMessage Input inválido: description =
    */
    public function testInsertInvalido()
    {
        $post = new Post();

        $post->title = 'teste';
        $post->description  = '';

        $saved = self::getTable('Application\Model\Post')->save($post);
    }

    /**
    * Teste de update válido
    */
    public function testUpdate()
    {
        $tableGateway   = self::getTable('Application\Model\Post');
        $post           = self::_addPost();

        $saved  = $tableGateway->save($post);
        $id     = $saved->id;

        self::assertEquals(1, $id);

        $post = $tableGateway->get($id);
        self::assertEquals('A Apple compra a Coderockr', $post->title);

        $post->title    = 'Coderockr compra a Apple';
        $update         = $tableGateway->save($post);

        $post = $tableGateway->get($id);
        self::assertEquals('Coderockr compra a Apple', $post->title);
    }

    /**
    * @expectedException        Core\Model\EntityException
    * @expectedExceptionMessage Could not find row 1
    */
    public function testDelete()
    {
        $tableGateway = self::getTable('Application\Model\Post');
        $post = self::_addPost();

        $saved = $tableGateway->save($post);
        $id = $saved->id;

        $deleted = $tableGateway->delete($id);
        self::assertEquals(1, $deleted);

        $post = $tableGateway->get($id);
    }

    /**
    * Método para criar uma instância da classe Post
    */
    protected function _addPost()
    {
        $post = new Post();
        $post->title = 'A Apple compra a Coderockr';
        $post->description = 'A Apple compra a <b>Coderockr</b><br> ';
        $post->post_date = date('Y-m-d H:i:s');

        return $post;
    }
}