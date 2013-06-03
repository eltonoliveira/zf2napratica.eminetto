<?php

namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Core\Model\Entity;

/**
* Entidade Post
*
* @category Application
* @package  Model
*
*/
class Post extends Entity
{
	/**
	* Nome da tabela. Campo obrigatório.
	* @var string
	*/
	protected $tableName = 'posts';
	/**
	* @var int
	*/
	protected $id;
	/**
	* @var string
	*/
	protected $title;
	/**
	* @var string
	*/
	protected $description;
	/**
	* @var datetime
	*/
	protected $post_date;

	/**
	* Configura os filtros dos campos da entidade
	*
	* @return Zend\InputFilter\InputFilter
	*/
	public function getInputFilter()
	{
		if(!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
			$factory 	 = new InputFactory();

			$inputFilter->add($factory->createInput(array(
				'name' => 'id',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'title',
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

			$inputFilter->add($factory->createInput(array(
				'name' => 'description',
				'required' => TRUE,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'post_date',
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