<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Core\Model\Entity;

/**
* Entidade Comment
*
* @category Application
* @package  Model
*/
class Comment extends Entity
{	
	/**
	* Nome da tabela. Campo obrigatório
	* @var string
	*/
	protected $tableName = 'comments';
	/**
	* @var int
	*/
	protected $id;
	/**
	* @var int
	*/
	protected $post_id;
	/**
	* @var string
	*/
	protected $description;
	/**
	* @var string
	*/
	protected $name;
	/**
	* @var string
	*/
	protected $email;
	/**
	* @var string
	*/
	protected $webpage;
	/**
	* @var datetime
	*/
	protected $comment_date;

	/**
	* Configura os filtros dos campos da entidade
	*
	* @return Zend\InputFilter\InputFilter
	*/
	public function getInputFilter()
	{
		if(!$this->inputFilter)
		{
			$inputFilter 	= new InputFilter();
			$inputFactory 	= new InputFactory();

			$inputFilter->add($inputFactory->createInput(array(
				'name' => 'id',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($inputFactory->createInput(array(
				'name' => 'post_id',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($inputFactory->createInput(array(
				'name' => 'description',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));

			$inputFilter->add($inputFactory->createInput(array(
				'name' => 'email',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'EmailAddress',
					),
				),
			)));

			$inputFilter->add($inputFactory->createInput(array(
				'name' => 'name',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 100,
						),
					),
				),
			)));

			$inputFilter->add($inputFactory->createInput(array(
				'name' => 'webpage',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 100,
						),
					),
				),
			)));

			$inputFilter->add($inputFactory->createInput(array(
				'name' => 'comment_date',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}