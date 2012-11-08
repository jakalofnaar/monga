<?php
/**
 * Monga is a swift MongoDB Abstraction for PHP 5.3+
 *
 * @package    Monga
 * @version    1.0
 * @author     Frank de Jonge
 * @license    MIT License
 * @copyright  2011 - 2012 Frank de Jonge
 * @link       http://github.com/FrenkyNet/Monga
 */

namespace Monga;

use MongoGridFS;
use Closure;

class Filesystem extends Collection
{
	/**
	 * @var  object  MongoCollection instance
	 */
	protected $collection;

	/**
	 * Sets the MongoGridFS.
	 *
	 * @param  object  $filestem  MongoGridFS object.
	 */
	public function __construct(MongoGridFS $filesystem)
	{
		$this->collection = $filesystem;
	}

	/**
	 * Store a file.
	 *
	 * @param   string  $filename  file name
	 * @param   array   $metadata  metadata
	 * @return  object  MongoId object
	 */
	public function store($filename, $metadata = array())
	{
		return $this->collection->put($filename, $metadata);
	}

	/**
	 * Store a set of bytes.
	 *
	 * @param   string  $filename  file name
	 * @param   array   $metadata  metadata
	 * @return  object  MongoId object
	 */
	public function storeBytes($bytes, $metadata = array(), $options = array())
	{
		return $this->collection->storeBytes($bytes, $metadata, $options);
	}

	/**
	 * Store a file.
	 *
	 * @param   string  $filename  file name
	 * @param   array   $metadata  metadata
	 * @return  object  MongoId object
	 */
	public function storeFile($filename, $metadata = array(), $options = array())
	{
		return $this->collection->storeFile($filename, $metadata, $options);
	}

	/**
	 * Stores an upload.
	 *
	 * @param   string  $name      $_FILES index
	 * @param   array   $metadata  file metadata
	 * @return  object  MongoId object
	 */
	public function storeUpload($name, $metadata = array())
	{
		return $this->collection->storeBytes($name, $metadata);
	}

	/**
	 * Extracts a file from the database,
	 * writes it to disc, and removes it from
	 * the database.
	 *
	 * @param   string   $name         file name
	 * @param   string   $destination  new location, optional
	 * @return  boolean                success boolean
	 */
	public function extract($name, $destination = null)
	{
		$file = $this->findOne($name);

		if ( ! $file or ! $file->write($destination))
		{
			return false;
		}

		$this->collection->remove(array('_id' => $file->file['_id']));

		return true;
	}

	/**
	 * Finds one file.
	 *
	 * @param   mixed        $query   filename or search query
	 * @param   array        $fields  metadata fields select/excule statement
	 * @return  object|null           MongoGridFSFile instance or null when not found.
	 */
	public function findOne($query = array(), $fields = array())
	{
		if (is_string($query))
		{
			return $this->collection->findOne($query, $fields = array());
		}

		return parent::findOne($query, $fields);
	}
}