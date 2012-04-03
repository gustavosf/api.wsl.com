<?php

class Collection implements ArrayAccess, Countable, IteratorAggregate {
	
	protected $collection;

	public function __construct($data)
	{
		if (!is_array($data))
		{
			throw new Exception("Collection input must be an Array");
		}
		$this->collection = $data;
	}

	/**
	 * Retrieves first element of this collection
	 */
	public function first()
	{
		return array_shift($this->collection);
	}

	/**
	 * Retrieves last element of this collection
	 */
	public function last()
	{
		return array_pop($this->collection);
	}

	/**
	 * Returns a new collection of filtered elements
	 * @param  mixed $filter callback or array
	 * @return Collection
	 */
	public function filter($filter)
	{
		if (is_callable($filter))
		{
			$filtered_collection = array_filter($this->collection, $filter);
		}
		elseif (is_array($filter))
		{
			$filtered_collection = array();
			foreach ($this->collection as $item)
			{
				$mantain = true;
				foreach ($filter as $key => $value)
				{
					if (@$item->$key !== $value) $mantain = false;
				}
				if ($mantain) $filtered_collection[] = $item;
			}
		}
		else
		{
			throw new InvalidArgumentException;
		}
		return new Collection($filtered_collection);
	}

	/*== Simple implementations of ArrayAccess, Countable e IteratorAggregate ==*/
	   
	public function offsetSet($offset, $value) {
		if (is_null($offset))
		{
			$this->collection[] = $value;
		}
		else
		{
			$this->collection[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->collection[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->collection[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
	}

	public function count() {
		return sizeof($this->collection);
	}

	public function getIterator() {
		return new ArrayIterator($this->collection);
	}

}