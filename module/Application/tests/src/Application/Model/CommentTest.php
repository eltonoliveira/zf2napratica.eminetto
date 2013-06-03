<?php
namespace Application\Model;

use Core\Test\ModelTestCase;
use Application\Model\Post;
use Application\Model\Comment;
use Zend\InputFilter\InputFilterInterface;

/**
* @group Model
*/
class CommentTest extends ModelTestCase
{
	public function testGetinputFilter()
	{
		$comment 	 = new Comment();
		$inputFilter = $comment->getInputfilter();

		self::assertInstanceOf('Zend\InputFilter\InputFilter', $inputFilter);
		return $inputFilter;
	}

	/**
	* @depends testGetinputFilter
	*/
	public function testInputFilterValid($inputFilter)
	{
		self::assertEquals(7, $inputFilter->count());

		self::assertTrue($inputFilter->has('id'));
		self::assertTrue($inputFilter->has('post_id'));
		self::assertTrue($inputFilter->has('description'));
		self::assertTrue($inputFilter->has('name'));
		self::assertTrue($inputFilter->has('email'));
		self::assertTrue($inputFilter->has('webpage'));
		self::assertTrue($inputFilter->has('comment_date'));
	}

	/**
	* @expectedException 		Core\Model\EntityException
	* @expectedExceptionMessage Input inválido: email =
	*/
	public function testInputFilterInvalido()
	{
		$comment = new Comment();
		$comment->email = 'email_invalido';
	}

	/**
	* Teste de inserção de um comment válido
	*/
	public function testInsert()
	{
		$comment = self::_addComment();
		$saved   = self::getTable('Application\Model\Comment')->save($comment);
		
		self::assertEquals('Comentário importante alert("ok");', $saved->description);
		self::assertEquals(1, $saved->id);
	}

	/**
	* @expectedException Zend\Db\Adapter\Exception\InvalidQueryException
	*/
	public function testInsertInvalido()
	{
		$comment = new Comment();
		$comment->description = 'teste';
		$comment->post_id = 0;

		$saved = self::getTable('Application\Model\Comment')->save($comment);
	}

	/**
	* Teste de update de um comment válido
	*/
	public function testUpdate()
	{
		$tableGateway = self::getTable('Application\Model\Comment');
		$comment = self::_addComment();
		$saved = $tableGateway->save($comment);
		$id = $saved->id;

		self::assertEquals(1, $id);

		$comment = $tableGateway->get($id);
		self::assertEquals('eminetto@coderockr.com', $comment->email);

		$comment->email = 'eminetto@gmail.com';
		$update = $tableGateway->save($comment);

		$comment = $tableGateway->get($id);
		self::assertEquals('eminetto@gmail.com', $comment->email);
	}

	/**
	* @expectedException 		Zend\Db\Adapter\Exception\InvalidQueryException
	* @expectedExceptionMessage Statement could not be executed
	*/
	public function testUpdateInvalido()
	{
		$tableGateway = self::getTable('Application\Model\Comment');
		$comment = self::_addComment();
		$saved = $tableGateway->save($comment);

		$id = $saved->id;
		$comment = $tableGateway->get($id);
		$comment->post_id = 10;
		$update = $tableGateway->save($comment);
	}

	/**
	* @expectedException 		Core\Model\EntityException
	* @expectedExceptionMessage Could not find row 1
	*/
	public function testDelete()
	{
		$tableGateway = self::getTable('Application\Model\Comment');
		$comment = self::_addComment();
		$saved = $tableGateway->save($comment);
		$id = $saved->id;

		$deleted = $tableGateway->delete($id);
		// Número de linhas exlcuídas
		self::assertEquals(1, $deleted);

		$comment = $tableGateway->get($id);
	}

	/**
	* Método que instancia um novo objeto Comment
	*/
	protected function _addComment()
	{
		$post 	 = self::_addPost();
		$comment = new Comment();
		$comment->post_id = $post->id;
		$comment->description = 'Comentário importante <script>alert("ok");</script> <br> ';
		$comment->name = 'Elton Minetto';
		$comment->email = 'eminetto@coderockr.com';
		$comment->webpage = 'http://www.eltonminetto.net';
		$comment->comment_date = date('Y-m-d H:i:s');

		return $comment;
	}

	/**
	* Método que instancia um novo objeto Post
	*/
	protected function _addPost()
	{
		$post = new Post();
		$post->title = 'Apple compra a Coderockr';
		$post->description = 'A Apple compra a <b>Coderockr</b><br> ';
		$post->post_date = date('Y-m-d H:i:s');

		$saved = self::getTable('Application\Model\Post')->save($post);
		return $saved;
	}


}