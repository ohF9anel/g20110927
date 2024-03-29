<?php

/**
 * Spoon Library
 *
 * This source file is part of the Spoon Library. More information,
 * documentation and tutorials can be found @ http://www.spoon-library.com
 *
 * @package		spoon
 * @subpackage	form
 *
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @author 		Tijs Verkoyen <tijs@spoon-library.com>
 * @author		Dave Lens <dave@spoon-library.com>
 * @since		0.1.1
 */


/**
 * Creates a list of html radiobuttons
 *
 * @package		spoon
 * @subpackage	form
 *
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		0.1.1
 */
class SpoonFormRadiobutton extends SpoonFormElement
{
	/**
	 * Should we allow external data
	 *
	 * @var	bool
	 */
	private $allowExternalData = false;


	/**
	 * Currently checked value
	 *
	 * @var	string
	 */
	private $checked;


	/**
	 * Errors stack
	 *
	 * @var	string
	 */
	private $errors;


	/**
	 * Name element
	 *
	 * @var	string
	 */
	private $name;


	/**
	 * List of labels and their values
	 *
	 * @var	string
	 */
	protected $values;


	/**
	 * List of variables
	 *
	 * @var	array
	 */
	private $variables;


	/**
	 * Class constructor.
	 *
	 * @return	void
	 * @param	string $name
	 * @param	string $values
	 * @param	string[optional] $checked
	 * @param	string[optional] $class
	 */
	public function __construct($name, array $values, $checked = null, $class = 'inputRadiobutton')
	{
		// obligated fields
		$this->name = (string) $name;
		$this->setValues($values, $class);

		// custom optional fields
		if($checked !== null) $this->setChecked($checked);
	}


	/**
	 * Adds an error to the error stack.
	 *
	 * @return	void
	 * @param	string $error
	 */
	public function addError($error)
	{
		$this->errors .= (string) $error;
	}


	/**
	 * Retrieves the custom attributes as HTML.
	 *
	 * @return	string
	 * @param	string $element
	 * @param	array $variables
	 */
	private function getAttributesHTML($element, array $variables)
	{
		// init var
		$html = '';

		// has attributes
		if(isset($this->attributes[(string) $element]))
		{
			// loop attributes
			foreach($this->attributes[(string) $element] as $key => $value)
			{
				$html .= ' '. $key .'="'. str_replace(array_keys($variables), array_values($variables), $value) .'"';
			}
		}

		return $html;
	}


	/**
	 * Retrieve the value of the checked item.
	 *
	 * @return	bool
	 */
	public function getChecked()
	{
		/**
		 * If we want to retrieve the checked status, we should first
		 * ensure that the value we return is correct, therefor we
		 * check the $_POST/$_GET array for the right value & ajust it if needed.
		 */

		// post/get data
		$data = $this->getMethod(true);

		// form submitted
		if($this->isSubmitted())
		{
			// currently field checked
			if(isset($data[$this->getName()]) && isset($this->values[(string) $data[$this->getName()]]))
			{
				// set this field as checked
				$this->setChecked($data[$this->getName()]);
			}
		}

		return $this->checked;
	}


	/**
	 * Retrieve the errors.
	 *
	 * @return	string
	 */
	public function getErrors()
	{
		return $this->errors;
	}


	/**
	 * Retrieves the name.
	 *
	 * @return	string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Retrieves the initial or submitted value.
	 *
	 * @return	string
	 */
	public function getValue()
	{
		// default value (may be null)
		$value = $this->getChecked();

		// post/get data
		$data = $this->getMethod(true);

		// form submitted
		if($this->isSubmitted())
		{
			// allow external data
			if($this->allowExternalData) $value = $data[$this->name];

			// external data NOT allowed
			else
			{
				// item is set
				if(isset($data[$this->name]) && isset($this->values[(string) $data[$this->name]])) $value = $data[$this->name];
			}
		}

		return $value;
	}


	/**
	 * Checks if this field was submitted & filled.
	 *
	 * @return	bool
	 * @param	string[optional] $error
	 */
	public function isFilled($error = null)
	{
		// form submitted
		if($this->isSubmitted())
		{
			// post/get data
			$data = $this->getMethod(true);

			// correct
			if(isset($data[$this->name]) && isset($this->values[$data[$this->name]])) return true;
		}

		// oh-oh
		if($error !== null) $this->setError($error);
		return false;
	}


	/**
	 * Parse the html for this button.
	 *
	 * @return	array
	 * @param	SpoonTemplate[optional] $template
	 */
	public function parse(SpoonTemplate $template = null)
	{
		// name required
		if($this->name == '') throw new SpoonFormException('A name is required for a radiobutton. Please provide a name.');

		// loop values
		foreach($this->values as $value => $label)
		{
			// init vars
			$name = 'rbt'. SpoonFilter::toCamelCase($this->name);
			$element = array();
			$element[$name] = '<input type="radio" name="'. $this->name .'" value="'. $value .'"';

			// checked status
			if($value == $this->getChecked()) $element[$name] .= ' checked="checked"';

			// add attributes
			$element[$name] .= $this->getAttributesHTML($value, array('[id]' => $this->variables[$value]['id'], '[value]' => $value));

			// add variables to this element
			foreach($this->variables[$value] as $variableKey => $variableValue) $element[$variableKey] = $variableValue;

			// end input tag
			$element[$name] .= ' />';

			// add checkbox
			$radioButtons[] = $element;
		}

		// template
		if($template !== null)
		{
			$template->assign($this->name, $radioButtons);
			$template->assign('rbt'. SpoonFilter::toCamelCase($this->name) .'Error', ($this->errors!= '') ? '<span class="formError">'. $this->errors .'</span>' : '');
		}

		return $radioButtons;
	}


	/**
	 * Set the checked value.
	 *
	 * @return	void
	 * @param	string $checked
	 */
	public function setChecked($checked)
	{
		// doesnt exist
		if(!isset($this->values[(string) $checked])) throw new SpoonFormException('This value "'. (string) $checked .'" is not among the list of values.');

		// exists
		$this->checked = (string) $checked;
	}


	/**
	 * Overwrites the error stack.
	 *
	 * @return	void
	 * @param	string $error
	 */
	public function setError($error)
	{
		$this->errors = (string) $error;
	}


	/**
	 * Set the labels and their values.
	 *
	 * @return	void
	 * @param	array $values
	 */
	private function setValues(array $values, $defaultClass = 'inputRadiobutton')
	{
		// empty values not allowed
		if(empty($values)) throw new SpoonFormException('The list with values should not be empty.');

		// loop values
		foreach($values as $value)
		{
			// label is not set
			if(!isset($value['label'])) throw new SpoonFormException('Each element in this array should contain a key "label".');

			// value is not set
			if(!isset($value['value'])) throw new SpoonFormException('Each element in this array should contain a key "value".');

			// set value
			$this->values[(string) $value['value']] = (string) $value['label'];

			// attributes?
			if(isset($value['attributes']) && is_array($value['attributes']))
			{
				foreach($value['attributes'] as $attributeKey => $attributeValue) $this->attributes[$value['value']][(string) $attributeKey] = (string) $attributeValue;
			}

			// add default class
			if(!isset($this->attributes[$value['value']]['class'])) $this->attributes[$value['value']]['class'] = (string) $defaultClass;

			// variables
			if(isset($value['variables']) && is_array($value['variables']))
			{
				foreach($value['variables'] as $variableKey => $variableValue) $this->variables[$value['value']][(string) $variableKey] = (string) $variableValue;
			}

			// custom id
			if(!isset($this->variables[$value['value']]['id']))
			{
				if(isset($this->attributes[$value['value']]['id'])) $this->variables[$value['value']]['id'] = $this->attributes[$value['value']]['id'];
				else $this->variables[$value['value']]['id'] = SpoonFilter::toCamelCase($this->name . '_'. $value['value'], '_', true);
			}

			// add some custom vars
			if(!isset($this->variables[$value['value']]['label'])) $this->variables[$value['value']]['label'] = $value['label'];
			if(!isset($this->variables[$value['value']]['value'])) $this->variables[$value['value']]['value'] = $value['value'];

			// add id
			if(!isset($this->attributes[$value['value']]['id'])) $this->attributes[$value['value']]['id'] = $this->variables[$value['value']]['id'];
		}
	}
}

?>