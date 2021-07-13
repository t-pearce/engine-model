<?php

namespace Engine\Model;

abstract class Model implements \Stringable, \ArrayAccess
{
	use \Engine\Traits\Creatable;

	public function __call($name, $arguments)
	{
		if(preg_match("/^get[A-Z]/", $name) && count($arguments) === 0)
		{
			$property = lcfirst(substr($name, 3));
			return $this->$property;
		}
		if(preg_match("/^set[A-Z]/", $name) && count($arguments) === 1)
		{
			$property = lcfirst(substr($name, 3));
			$this->$property = $arguments[0];

			return $this;
		}
	}

	public function __get($property)
	{
		if(!\property_exists($this, $property))
			throw new \Exception("{$property} is not a property of " . static::class);
			
		return $this->$property;

	}

	public function __set($property, $value)
	{
		if(!\property_exists($this, $property))
			throw new \Exception("{$property} is not a property of " . static::class);

		$this->$property = $value;

		return $this;
	}

	protected function debugArrayValue($array, $property)
	{
		$vals = [];

		foreach($array as $element)
		{
			$vals[] = $element->$property;
		}

		return implode(", ", $vals);
	}

	public function __toString()
	{
		return var_export($this, true);
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->container[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
}