<?php

/**
 * Spoon Library
 *
 * This source file is part of the Spoon Library. More information,
 * documentation and tutorials can be found @ http://www.spoon-library.com
 *
 * @package		spoon
 * @subpackage	datagrid
 *
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @author 		Tijs Verkoyen <tijs@spoon-library.com>
 * @author		Dave Lens <dave@spoon-library.com>
 * @since		1.0.0
 */


/**
 * This class is the base class used to generate datagrids from a range of sources.
 *
 * @package			spoon
 * @subpackage		datagrid
 *
 *
 * @author			Davy Hellemans <davy@spoon-library.com>
 * @since			1.0.0
 */
class SpoonDatagrid
{
	/**
	 * List of columns that may be sorted in, based on the query results
	 *
	 * @var	array
	 */
	private $allowedSortingColumns = array();


	/**
	 * Html attributes
	 *
	 * @var	array
	 */
	private $attributes = array('datagrid' => array(),
								'row' => array(),
								'row_even' => array(),
								'row_odd' => array(),
								'footer' => array());


	/**
	 * Table caption/description
	 *
	 * @var	string
	 */
	private $caption;


	/**
	 * Array of column functions
	 *
	 * @var	array
	 */
	private $columnFunctions = array();


	/**
	 * Array of column objects
	 *
	 * @var	array
	 */
	private $columns = array();


	/**
	 * Default compile directory
	 *
	 * @var	string
	 */
	private $compileDirectory;


	/**
	 * Final output
	 *
	 * @var	string
	 */
	private $content;


	/**
	 * Debug status
	 *
	 * @var	bool
	 */
	private $debug = false;


	/**
	 * Offset start value
	 *
	 * @var	int
	 */
	private $offsetParameter;


	/**
	 * Order start value
	 *
	 * @var	string
	 */
	private $orderParameter;


	/**
	 * Separate results with pages
	 *
	 * @var	bool
	 */
	private $paging = true;


	/**
	 * Default number of results per page
	 *
	 * @var	int
	 */
	private $pagingLimit = 30;


	/**
	 * Class used to define paging
	 *
	 * @var	SpoonDatagridPaging
	 */
	private $pagingClass = 'SpoonDatagridPaging';


	/**
	 * Parse status
	 *
	 * @var	bool
	 */
	private $parsed = false;


	/**
	 * Default sorting column
	 *
	 * @var	string
	 */
	private $sortingColumn;


	/**
	 * Sorting columns (cached when requested)
	 *
	 * @var array
	 */
	private $sortingColumns = array();


	/**
	 * Sorting icons
	 *
	 * @var	array
	 */
	private $sortingIcons = array(	'asc' => null,
									'ascSelected' => null,
									'desc' => null,
									'descSelected' => null);


	/**
	 * Sorting Labels
	 *
	 * @var	array
	 */
	private $sortingLabels = array(	'asc' => 'Sort ascending',
									'ascSelected' => 'Sorted ascending',
									'desc' => 'Sort descending',
									'descSelected' => 'Sorted descending');


	/**
	 * Default sorting method
	 *
	 * @var	string
	 */
	private $sortParameter;


	/**
	 * Source of the datagrid
	 *
	 * @var	SpoonDatagridSource
	 */
	private $source;


	/**
	 * Datagrid summary
	 *
	 * @var	string
	 */
	private $summary;


	/**
	 * Default or custom template
	 *
	 * @var	string
	 */
	private $template;


	/**
	 * Template instance
	 *
	 * @var	SpoonTemplate
	 */
	protected $tpl;


	/**
	 * Basic URL
	 *
	 * @var	string
	 */
	private $URL;


	/**
	 * Class constructor.
	 *
	 * @return	void
	 * @param	SpoonDatagridSource $source
	 * @param	string[optional] $template
	 */
	public function __construct(SpoonDatagridSource $source, $template = null)
	{
		// set source
		$this->setSource($source);

		// set template
		if($template !== null) $this->setTemplate($template);

		// load template
		$this->tpl = new SpoonTemplate();

		// create default columns
		$this->createColumns();
	}


	/**
	 * Adds a new column with a couple of options such as title, URL, image, ...
	 *
	 * @return	void
	 * @param	string $name				The name for this column, later used to refer to this column.
	 * @param	string[optional] $label		The label that will be displayed in the header cell.
	 * @param	string[optional] $value		The value you wish to display.
	 * @param	string[optional] $URL		The URL to refer to.
	 * @param	string[optional] $title		The title tag, in case you've provided a URL.
	 * @param	string[optional] $image		The location to an image, used to fill the column.
	 * @param	int[optional] $sequence		The sequence for this column, by default it will be added at the back.
	 */
	public function addColumn($name, $label = null, $value = null, $URL = null, $title = null, $image = null, $sequence = null)
	{
		// redefine name
		$name = (string) $name;

		// column already exists
		if(isset($this->columns[$name])) throw new SpoonDatagridException('A column with the name "'. $name .'" already exists.');

		// redefine sequence
		if($sequence === null) $sequence = count($this->columns) + 1;

		// new column
		$this->columns[$name] = new SpoonDatagridColumn($name, $label, $value, $URL, $title, $image, $sequence);

		// add the class as an attribute to this column
		$this->columns[$name]->setAttributes(array('class' => $name));
	}


	/**
	 * Builds the requested URL.
	 *
	 * @return	string
	 * @param	int $offset
	 * @param	string $order
	 * @param	string $sort
	 */
	private function buildURL($offset, $order, $sort)
	{
		return str_replace(array('[offset]', '[order]', '[sort]'), array($offset, $order, $sort), $this->URL);
	}


	/**
	 * Clears the attributes.
	 *
	 * @return	void
	 */
	public function clearAttributes()
	{
		$this->attributes['datagrid'] = array();
	}


	/**
	 * Clears the attributes for a specific column.
	 *
	 * @return	void
	 * @param	string $column		The name of the column you want to clear the column attributes from.
	 */
	public function clearColumnAttributes($column)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesn't exist
			if(!isset($this->columns[(string) $column])) throw new SpoonDatagridException('The column "'. (string) $column .'" doesn\'t exist and therefor no attributes can be removed.');

			// exists
			$this->columns[(string) $column]->clearAttributes();
		}
	}


	/**
	 * Clears the even row attributes.
	 *
	 * @return	void
	 */
	public function clearEvenRowAttributes()
	{
		$this->attributes['row_even'] = array();
	}


	/**
	 * clears the odd row attributes.
	 *
	 * @return	void
	 */
	public function clearOddRowAttributes()
	{
		$this->attributes['row_odd'] = array();
	}


	/**
	 * Clears the row attributes.
	 *
	 * @return	void
	 */
	public function clearRowAttributes()
	{
		$this->attributes['row'] = array();
	}


	/**
	 * Creates the default columns, based on the initially provided source.
	 *
	 * @return	void
	 */
	private function createColumns()
	{
		// has results
		if($this->source->getNumResults() != 0)
		{
			// fetch the column names
			foreach($this->source->getColumns() as $column)
			{
				// add column
				$this->addColumn($column, $column, '['. $column .']', null, null, null, (count($this->columns) +1));

				// by default the column name will be added as a class
				$this->columns[$column]->setAttributes(array('class' => $column));

				// may be sorted on
				$this->allowedSortingColumns[] = $column;
			}
		}
	}


	/**
	 * Shows the output & stops script execution.
	 *
	 * @return	void
	 */
	public function display()
	{
		echo $this->getContent();
		exit;
	}


	/**
	 * Generates the array with the order columns.
	 *
	 * @return	void
	 */
	private function generateOrder()
	{
		// delete current cache of sortable columns
		$this->sortingColumns = array();

		// columns present
		if(count($this->columns) != 0)
		{
			// loop columns
			foreach($this->columns as $column)
			{
				// allowed sorting, sorting enabled specifically
				if(in_array($column->getName(), $this->allowedSortingColumns) && $column->getSorting())
				{
					// add to the list
					$this->sortingColumns[] = $column->getName();

					// default
					$default = $column->getName();
				}
			}

			// no default defined
			if($this->sortingColumn === null && isset($default)) $this->sortingColumn = $default;
		}
	}


	/**
	 * Retrieve all the datagrid attributes.
	 *
	 * @return	array
	 */
	public function getAttributes()
	{
		return $this->attributes['datagrid'];
	}


	/**
	 * Fetch the column object for a specific column.
	 *
	 * @return	SpoonDatagridColumn
	 * @param	string $column
	 */
	public function getColumn($column)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesnt exist
			if(!isset($this->columns[$column])) throw new SpoonDatagridException('The column "'. $column .'" doesn\'t exist.');

			// exists
			return $this->columns[$column];
		}
	}


	/**
 	 * Fetch the list of columns in this datagrid.
 	 *
 	 * @return	array
	 */
	public function getColumns()
	{
		return $this->getColumnsSequence();
	}


	/**
	 * Retrieve the columns sequence.
	 *
	 * @return	array
	 */
	private function getColumnsSequence()
	{
		// init var
		$columns = array();

		// loop all the columns
		foreach($this->columns as $column) $columns[$column->getSequence()] = $column->getName();

		// reindex
		return (!empty($columns)) ? SpoonFilter::arraySortKeys($columns) : $columns;
	}


	/**
	 * Retrieve the parsed output.
	 *
	 * @return	string
	 */
	public function getContent()
	{
		// parse if needed
		if(!$this->parsed) $this->parse();

		// fetch content
		return $this->content;
	}


	/**
	 * Fetch the debug status.
	 *
	 * @return	bool
	 */
	public function getDebug()
	{
		return $this->debug;
	}


	/**
	 * Returns the html attributes based on an array.
	 *
	 * @return	string
	 * @param	array[optional] $array
	 */
	private function getHTMLAttributes(array $array = array())
	{
		// output
		$html = '';

		// loop elements
		foreach($array as $label => $value) $html .= ' '. $label .'="'. $value .'"';
		return $html;
	}


	/**
	 * Retrieve the offset value.
	 *
	 * @return	int
	 */
	public function getOffset()
	{
		// default offset
		$offset = null;

		// paging enabled
		if($this->paging)
		{
			// has results
			if($this->source->getNumResults() != 0)
			{
				// offset parameter defined
				if($this->offsetParameter !== null) $offset = $this->offsetParameter;

				// use default
				else $offset = (isset($_GET['offset'])) ? (int) $_GET['offset'] : 0;

				// offset cant be bigger than the number of results
				if($offset >= $this->source->getNumResults()) $offset = (int) $this->source->getNumResults() - $this->pagingLimit;

				// offset divided by the per page limit should have no rest
				if(($offset % $this->pagingLimit) != 0) $offset = 0;

				// offset minus the pagina limit may not go below zero
				if(($offset - $this->pagingLimit) < 0) $offset = 0;
			}

			// no results
			else $offset = 0;
		}

		return $offset;
	}


	/**
	 * Retrieves the column that's currently being sorted on.
	 *
	 * @return	string
	 */
	public function getOrder()
	{
		// default value
		$order = null;

		// sorting enabled
		if($this->getSorting())
		{
			/**
			 * First the list of columns that can be ordered on,
			 * must be re-generated
			 */
			$this->generateOrder();

			// order parameter defined
			if($this->orderParameter !== null) $order = $this->orderParameter;

			// defaut order
			else $order = (isset($_GET['order'])) ? (string) $_GET['order'] : null;

			// retrieve order
			$order = SpoonFilter::getValue($order, $this->sortingColumns, $this->sortingColumn);
		}

		return $order;
	}


	/**
	 * Retrieve the number of results for this datagrids' source.
	 *
	 * @return	int
	 */
	public function getNumResults()
	{
		return $this->source->getNumResults();
	}


	/**
	 * Is paging enabled?
	 *
	 * @return	bool
	 */
	public function getPaging()
	{
		return $this->paging;
	}


	/**
	 * Fetch name of the class that will be used to parse the paging.
	 *
	 * @return	string
	 */
	public function getPagingClass()
	{
		return $this->pagingClass;
	}


	/**
	 * Fetch the number of items that will be shown on each page.
	 *
	 * @return	int
	 */
	public function getPagingLimit()
	{
		return ($this->paging) ? $this->pagingLimit : null;
	}


	/**
	 * Retrieve the sorting method.
	 *
	 * @return	string		The sorting method, either asc or desc.
	 */
	public function getSort()
	{
		// default sort
		$sort = ($this->sortParameter !== null) ? $this->sortParameter : null;

		// redefine
		$sort = (isset($_GET['sort'])) ? (string) $_GET['sort'] : null;

		// retrieve sort
		return SpoonFilter::getValue($sort, array('asc', 'desc'), 'asc');
	}


	/**
	 * Retrieve the sorting status.
	 *
	 * @return	bool
	 */
	public function getSorting()
	{
		// generate order
		$this->generateOrder();

		// sorting columns exist?
		return (count($this->sortingColumns) != 0) ? true : false;
	}


	/**
	 * Fetch the template instance
	 *
	 * @return	SpoonTemplate
	 */
	public function getTemplate()
	{
		return $this->tpl;
	}


	/**
	 * Retrieve the location of the template that will be used.
	 *
	 * @return	string
	 */
	private function getTemplatePath()
	{
		return ($this->template != null) ? $this->template : dirname(__FILE__) .'/datagrid.tpl';
	}


	/**
	 * Retrieve the URL.
	 *
	 * @return	string	The URL that will be used, may contain variables with square brackets.
	 */
	public function getURL()
	{
		return $this->URL;
	}


	/**
	 * Parse the datagrid.
	 *
	 * @return	void
	 */
	private function parse()
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// fetch records
			$aRecords = $this->source->getData($this->getOffset(), $this->getPagingLimit(), $this->getOrder(), $this->getSort());

			// has results
			if(count($aRecords) != 0)
			{
				// compile directory
				$compileDirectory = ($this->compileDirectory !== null) ? $this->compileDirectory : dirname(realpath(__FILE__));
				$this->tpl->setCompileDirectory($compileDirectory);

				// only force compiling when debug is enabled
				if($this->debug) $this->tpl->setForceCompile(true);

				// table attributes
				$this->parseAttributes();

				// table summary
				$this->parseSummary();

				// caption/description
				$this->parseCaption();

				// header row
				$this->parseHeader();

				// actual rows
				$this->parseBody($aRecords);

				// pagination
				$this->parseFooter();

				// parse to buffer
				ob_start();
				$this->tpl->display($this->getTemplatePath());
				$this->content = ob_get_clean();
			}
		}

		// update parsed status
		$this->parsed = true;
	}


	/**
	 * Parses the datagrid attributes.
	 *
	 * @return	void
	 */
	private function parseAttributes()
	{
		$this->tpl->assign('attributes', $this->getHtmlAttributes($this->attributes['datagrid']));
	}


	/**
	 * Parses the body.
	 *
	 * @return	void
	 * @param	array $records
	 */
	private function parseBody(array $records)
	{
		// init var
		$rows = array();

		// columns sequence
		$sequence = $this->getColumnsSequence();

		// loop records
		foreach($records as $i => &$record)
		{
			// replace possible variables
			$record = $this->parseRecord($record);

			// parse column functions
			$record = $this->parseColumnFunctions($record);

			// reset row
			$row = array('attributes' => '', 'oddAttributes' => '', 'evenAttributes' => '', 'columns' => array());

			// row attributes
			$row['attributes'] = str_replace($record['labels'], $record['values'], $this->getHtmlAttributes($this->attributes['row']));

			// odd row attributes (reversed since the first $i = 0)
			if(!SpoonFilter::isOdd($i)) $row['oddAttributes'] = str_replace($record['labels'], $record['values'], $this->getHtmlAttributes($this->attributes['row_odd']));

			// even row attributes
			else $row['evenAttributes'] = str_replace($record['labels'], $record['values'], $this->getHtmlAttributes($this->attributes['row_even']));

			// define the columns
			$columns = array();

			// loop columns
			foreach($sequence as $name)
			{
				// column
				$column = $this->columns[$name];

				// has overwrite enabled
				if($column->getOverwrite())
				{
					// fetch key
					$iKey = array_search('['. $column->getName() .']', $record['labels']);

					// parse the actual value
					$columnValue = $record['values'][$iKey];
				}

				// no overwrite status
				else
				{
					// default value
					$columnValue = '';

					// has an url
					if($column->getURL() !== null)
					{
						// open url tag
						$columnValue .= '<a href="'. str_replace($record['labels'], $record['values'], $column->getURL()) .'"';

						// add title
						$columnValue .= ' title="'. str_replace($record['labels'], $record['values'], $column->getURLTitle()) .'"';

						// confirm
						if($column->getConfirm() && $column->getConfirmMessage() !== null)
						{
							// default confirm
							if($column->getConfirmCustom() == '') $columnValue .= ' onclick="return confirm(\''. str_replace($record['labels'], $record['values'], $column->getConfirmMessage()) .'\');"';

							// custom confirm
							else
							{
								// replace the message
								$tmpValue = str_replace('%message%', $column->getConfirmMessage(), $column->getConfirmCustom());

								// make vars available
								$tmpValue = str_replace($record['labels'], $record['values'], $tmpValue);

								// add id
								$columnValue .= ' '. $tmpValue;
							}
						}

						// close start tag
						$columnValue .= '>';
					}

					// has an image
					if($column->getImage() !== null)
					{
						// open img tag
						$columnValue .= '<img src="'. str_replace($record['labels'], $record['values'], $column->getImage()) .'"';

						// add title & alt
						$columnValue .= ' alt="'. str_replace($record['labels'], $record['values'], $column->getImageTitle()) .'"';
						$columnValue .= ' title="'. str_replace($record['labels'], $record['values'], $column->getImageTitle()) .'"';

						// close img tag
						$columnValue .= ' />';
					}

					// regular value
					else
					{
						// fetch key
						$iKey = array_search('['. $column->getName() .']', $record['labels']);

						// parse value
						$columnValue .= $record['values'][$iKey];
					}

					// has an url (close the tag)
					if($column->getURL() !== null) $columnValue .= '</a>';
				}

				// fetch column attributes
				$columnAttributes = $this->getHtmlAttributes($column->getAttributes());

				// visible & iteration
				if(!$column->getHidden())
				{
					// add this column
					$columns[] = array('attributes' => $columnAttributes, 'value' => $columnValue);

					// add to custom list
					$row['column'][$name] = $columnValue;
				}
			}

			// add the columns to the rows
			$row['columns'] = $columns;

			// add the row
			$rows[] = $row;
		}

		// assign body
		$this->tpl->assign('rows', $rows);

		// assign the number of columns
		$this->tpl->assign('numColumns', count($rows[0]['columns']));
	}


	/**
	 * Parses the caption tag.
	 *
	 * @return	void
	 */
	private function parseCaption()
	{
		if($this->caption !== null) $this->tpl->assign('caption', $this->caption);
	}


	/**
	 * Parses the column functions.
	 *
	 * @return	array
	 * @param	array $record
	 */
	private function parseColumnFunctions($record)
	{
		// store old error reporting settings
		$currentErrorReporting = ini_get('error_reporting');

		// ignore warnings and notices
		error_reporting(E_WARNING | E_NOTICE);

		// loop functions
		foreach($this->columnFunctions as $function)
		{
			// no arguments given
			if($function['arguments'] == null) $value = call_user_func($function['function']);

			// array
			elseif(is_array($function['arguments']))
			{
				// replace arguments
				$function['arguments'] = str_replace($record['labels'], $record['values'], $function['arguments']);

				// execute function
				$value = call_user_func_array($function['function'], $function['arguments']);
			}

			// no null/array
			else $value = call_user_func($function['function'], str_replace($record['labels'], $record['values'], $function['arguments']));

			/**
			 * Now that we have the return value of this method, we can
			 * do the actual writeback to the column(s). If overwrite was
			 * true, we're going to enable the overwrite of the writeback column(s)
			 */

			// one column, that exists
			if(is_string($function['columns']) && isset($this->columns[$function['columns']]))
			{
				// fetch key
				$iKey = array_search('['. $function['columns'] .']', $record['labels']);

				// value was set
				if($iKey !== false)
				{
					// update value
					$record['values'][$iKey] = $value;

					// update overwrite
					if($function['overwrite']) $this->columns[$function['columns']]->setOverwrite(true);
				}
			}

			// write to multiple columns
			elseif(is_array($function['columns']) && count($function['columns']) != 0)
			{
				// loop target columns
				foreach($function['columns'] as $column)
				{
					// fetch key
					$iKey = array_search('['. $column .']', $record['labels']);

					// value was set
					if($iKey !== false)
					{
						// update value
						$record['values'][$iKey] = $value;

						// update overwrite
						if($function['overwrite']) $this->columns[$column]->setOverwrite(true);
					}
				}
			}
		}

		// restore error reporting
		error_reporting($currentErrorReporting);

		return $record;
	}


	/**
	 * Parses the footer.
	 *
	 * @return	void
	 */
	private function parseFooter()
	{
		// attributes
		$this->tpl->assign('footerAttributes', $this->getHtmlAttributes($this->attributes['footer']));

		// parse paging
		$this->parsePaging();
	}


	/**
	 * Parses the header row.
	 *
	 * @return	void
	 */
	private function parseHeader()
	{
		// init vars
		$header = array();

		// sequence
		$sequence = $this->getColumnsSequence();

		// sorting enabled?
		$sorting = $this->getSorting();

		// sortable columns
		$sortingColumns = array();
		foreach($sequence as $oColumn) if($this->columns[$oColumn]->getSorting()) $sortingColumns[] = $oColumn;

		// loop columns
		foreach($sequence as $name)
		{
			// define column
			$column = array();

			// column
			$oColumn = $this->columns[$name];

			// visible
			if(!$oColumn->getHidden())
			{
				// sorting globally enabled AND for this column
				if($sorting && in_array($name, $sortingColumns))
				{
					// init var
					$column['sorting'] = true;
					$column['noSorting'] = false;

					// sorted on this column?
					if($this->getOrder() == $name)
					{
						// sorted
						$column['sorted'] = true;
						$column['notSorted'] = false;

						// asc
						if($this->getSort() == 'asc')
						{
							$column['sortedAsc'] = true;
							$column['sortedDesc'] = false;
						}

						// desc
						else
						{
							$column['sortedAsc'] = false;
							$column['sortedDesc'] = true;
						}
					}

					/**
					 * This column is sortable, but there's currently not being
					 * sorted on this column.
					 */
					elseif(in_array($name, $sortingColumns))
					{
						$column['sorted'] = false;
						$column['notSorted'] = true;
					}

					/**
					 * URL's are parsed for the opposite column, as for the asc & desc version
					 * for this column. If the sorting is currently not on this column
					 * the default sorting method (mostly asc) will be used to define the opposite/default
					 * sorting method.
					 */

					// currently not sorting on this column
					if($this->getOrder() != $name) $sortingMethod = $this->columns[$name]->getSortingMethod();

					// sorted on this column ascending
					elseif($this->getSort() == 'asc') $sortingMethod = 'desc';

					// sorting on this column descending
					else $sortingMethod = 'asc';

					// build actual urls
					$column['sortingURL'] = $this->buildURL($this->getOffset(), $name, $sortingMethod);
					$column['sortingURLAsc'] = $this->buildURL($this->getOffset(), $name, 'asc');
					$column['sortingURLDesc'] = $this->buildURL($this->getOffset(), $name, 'desc');

					/**
					 * There's no point in parsing the icon for this column if there's
					 * not being sorted on this column.
					 */

					/**
					 * To define the default icon for sorting, we need to apply
					 * the same rules as with the default url. See those comments for
					 * the necessary details.
					 */
					if($this->getOrder() != $name) $sortingIcon = $this->sortingIcons[$this->columns[$name]->getSortingMethod()];

					// sorted on this column asc/desc
					elseif($this->getSort() == 'asc') $sortingIcon = $this->sortingIcons['ascSelected'];
					else $sortingIcon = $this->sortingIcons['descSelected'];

					// asc & desc icons
					$column['sortingIcon'] = $sortingIcon;
					$column['sortingIconAsc'] = ($this->getSort() == 'asc') ? $this->sortingIcons['ascSelected'] : $this->sortingIcons['asc'];
					$column['sortingIconDesc'] = ($this->getSort() == 'desc') ? $this->sortingIcons['descSelected'] : $this->sortingIcons['desc'];

					// not sorted on this column
					if($this->getOrder() != $name) $sortingLabel = $this->sortingLabels[$this->columns[$name]->getSortingMethod()];

					// sorted on this column asc/desc
					elseif($this->getSort() == 'asc') $sortingLabel = $this->sortingLabels['ascSelected'];
					else $sortingLabel = $this->sortingLabels['descSelected'];

					// add sorting labels
					$column['sortingLabel'] = $sortingLabel;
					$column['sortingLabelAsc'] = $this->sortingLabels['asc'];
					$column['sortingLabelDesc'] = $this->sortingLabels['desc'];
				}

				// no sorting enabled for this column
				else
				{
					$column['sorting'] = false;
					$column['noSorting'] = true;
				}

				// parse vars
				$column['label'] = $oColumn->getLabel();

				// add attributes
				$column['attributes'] = $this->getHTMLAttributes($oColumn->getHeaderAttributes());

				// add to array
				$header[] = $column;
			}
		}

		// default headers
		$this->tpl->assign('headers', $header);
	}


	/**
	 * Parses the paging.
	 *
	 * @return	void
	 */
	private function parsePaging()
	{
		// enabled
		if($this->paging)
		{
			// offset, order & sort
			$this->tpl->assign(array('offset', 'order', 'sort'), array($this->getOffset(), $this->getOrder(), $this->getSort()));

			// number of results
			$this->tpl->assign('iResults', $this->source->getNumResults());

			// number of pages
			$this->tpl->assign('iPages', ceil($this->source->getNumResults() / $this->pagingLimit));

			// current page
			$this->tpl->assign('iCurrentPage', ceil($this->getOffset() / $this->pagingLimit) + 1);

			// number of items per page
			$this->tpl->assign('iPerPage', $this->pagingLimit);

			// parse paging
			$content = call_user_func(array($this->pagingClass, 'getContent'), $this->URL, $this->getOffset(), $this->getOrder(), $this->getSort(), $this->source->getNumResults(), $this->pagingLimit, $this->debug, $this->compileDirectory);

			// asign content
			$this->tpl->assign('paging', $content);
		}
	}


	/**
	 * Parses the record.
	 *
	 * @return	array
	 * @param	array $record
	 */
	private function parseRecord(array $record)
	{
		// init var
		$array = array('labels' => array(), 'values' => array());

		// create labels/values array
		foreach($record as $label => $value)
		{
			$array['labels'][] = '['. $label .']';
			$array['values'][] = $value;
		}

		// add offset?
		if($this->paging)
		{
			$array['labels'][] = '[offset]';
			$array['values'][] = $this->getOffset();
		}

		// sorting
		if(count($this->sortingColumns) != 0)
		{
			$array['labels'][] = '[order]';
			$array['labels'][] = '[sort]';
			// --
			$array['values'][] = $this->getOrder();
			$array['values'][] = $this->getSort();
		}

		// loop the record fields
		foreach($this->columns as $column)
		{
			// this column is an extra field, added in the datagrid
			if(!in_array('['. $column->getName() .']', $array['labels']))
			{

				$array['values'][] = str_replace($array['labels'], $array['values'], $column->getValue());
				$array['labels'][] = '['. $column->getName() .']';
			}
		}

		return $array;
	}


	/**
	 * Parses the summary.
	 *
	 * @return	void
	 */
	private function parseSummary()
	{
		if($this->summary !== null) $this->tpl->assign('summary', $this->summary);
	}


	/**
	 * Set main datagrid attributes.
	 *
	 * @return	void
	 * @param	array $attributes
	 */
	public function setAttributes(array $attributes)
	{
		foreach($attributes as $key => $value) $this->attributes['datagrid'][(string) $key] = (string) $value;
	}


	/**
	 * Sets the table caption or main description.
	 *
	 * @return	void
	 * @param	string $value
	 */
	public function setCaption($value)
	{
		$this->caption = (string) $value;
	}


	/**
	 * Set one or more attributes for a specific column.
	 *
	 * @return	void
	 * @param	string $column
	 * @param	array $attributes
	 */
	public function setColumnAttributes($column, array $attributes)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesnt exist
			if(!isset($this->columns[$column])) throw new SpoonDatagridException('The column "'. $column .'" doesn\'t exist, therefor no attributes can be added.');

			// exists
			else $this->columns[$column]->setAttributes($attributes);
		}
	}


	/**
	 * Set a custom column confirm message.
	 *
	 * @return	void
	 * @param	string $column
	 * @param	string $message
	 * @param	string[optional] $custom
	 */
	public function setColumnConfirm($column, $message, $custom = null)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesnt exist
			if(!isset($this->columns[$column])) throw new SpoonDatagridException('The column "'. $column .'" doesn\'t exist, therefor no confirm message/script can be added.');

			// exists
			else $this->columns[$column]->setConfirm($message, $custom);
		}
	}


	/**
	 * Sets the column function to be executed for every row.
	 *
	 * @return	void
	 * @param	mixed $function
	 * @param	mixed[optional] $arguments
	 * @param	mixed $columns
	 * @param	bool[optional] $overwrite
	 */
	public function setColumnFunction($function, $arguments = null, $columns, $overwrite = false)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// regular function
			if(!is_array($function))
			{
				// function checks
				if(!function_exists((string) $function)) throw new SpoonDatagridException('The function "'. (string) $function .'" doesn\'t exist.');
			}

			// class method
			else
			{
				// method checks
				if(count($function) != 2) throw new SpoonDatagridException('When providing a method for a column function it must be like array(\'class\', \'method\')');

				// method doesn't exist
				elseif(!method_exists($function[0], $function[1])) throw new SpoonDatagridException('The method '. (string) $function[0] .'::'. (string) $function[1] .' does not exist.');
			}

			// add to function stack
			$this->columnFunctions[] = array('function' => $function, 'arguments' => $arguments, 'columns' => $columns, 'overwrite' => (bool) $overwrite);
		}
	}


	/**
	 * Set one or more attributes for a columns' header.
	 *
	 * @return	void
	 * @param	string $column
	 * @param	array $attributes
	 */
	public function setColumnHeaderAttributes($column, array $attributes)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesnt exist
			if(!isset($this->columns[$column])) throw new SpoonDatagridException('The column "'. $column .'" doesn\'t exist, therefor no attributes can be added to its header.');

			// exists
			else $this->columns[$column]->setHeaderAttributes($attributes);
		}
	}


	/**
	 * Sets a single column hidden.
	 *
	 * @return	void
	 * @param	string $column
	 * @param	bool[optional] $on
	 */
	public function setColumnHidden($column, $on = true)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesn't exist
			if(!isset($this->columns[(string) $column])) throw new SpoonDatagridException('The column "'. (string) $column .'" doesn\'t exist and therefor can\'t be set hidden.');

			// exists
			$this->columns[(string) $column]->setHidden($on);
		}
	}


	/**
	 * Sets one or more columns hidden.
	 *
	 * @return	void
	 * @param	array $columns
	 */
	public function setColumnsHidden($columns)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// array
			if(is_array($columns)) foreach($columns as $column) $this->setColumnHidden((string) $column);

			// multiple arguments
			else
			{
				// set columns hidden
				foreach(func_get_args() as $column) $this->setColumnHidden($column);
			}
		}
	}


	/**
	 * Sets the columns sequence.
	 *
	 * @return	void
	 * @param	array $columns
	 */
	public function setColumnsSequence($columns)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// array
			if(is_array($columns)) call_user_method_array('setColumnsSequence', $this, $columns);

			// multiple arguments
			else
			{
				// current sequence
				$sequences = $this->getColumnsSequence();

				// fetch arguments
				$arguments = func_get_args();

				// build columns
				$columns = (is_array($arguments[0])) ? $arguments[0] : $arguments;

				// counter
				$i = 1;

				// loop colums
				foreach($columns as $column)
				{
					// column exists
					if(!isset($this->columns[(string) $column])) throw new SpoonDatagridException('The column "'. (string) $column .'" doesn\'t exist. Therefor its sequence can\'t be altered.');

					// update sequence
					$this->columns[(string) $column]->setSequence($i);

					// remove from the original list
					$iKey = (int) array_search((string) $column, $sequences);
					unset($sequences[$iKey]);

					// update counter
					$i++;
				}

				// reset counter
				$i = 1;

				// add remaining columns
				foreach($sequences as $sequence)
				{
					// update sequence
					$this->columns[$sequence]->setSequence(count($columns) + $i);

					// update counter
					$i++;
				}
			}
		}
	}


	/**
	 * Set the default sorting method for a column.
	 *
	 * @return	void
	 * @param	string $column
	 * @param	string[optional] $sort
	 */
	public function setColumnSortingMethod($column, $sort = 'asc')
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesn't exist
			if(!isset($this->columns[(string) $column])) throw new SpoonDatagridException('The column "'. (string) $column .'" doesn\'t exist and therefor no default sorting method can be applied to it.');

			// exists
			$this->columns[(string) $column]->setSortingMethod($sort);
		}
	}


	/**
	 * Set the URL for a column.
	 *
	 * @return	void
	 * @param	string $column
	 * @param	string $URL
	 * @param	string[optional] $title
	 */
	public function setColumnURL($column, $URL, $title = null)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// column doesn't exist
			if(!isset($this->columns[(string) $column])) throw new SpoonDatagridException('The column "'. (string) $column .'" doesn\'t exist and therefor no URL can be applied.');

			// exists
			$this->columns[(string) $column]->setURL($URL, $title);
		}
	}


	/**
	 * Sets the compile directory.
	 *
	 * @return	void
	 * @param	string $path
	 */
	public function setCompileDirectory($path)
	{
		$this->compileDirectory = (string) $path;
	}


	/**
	 * Adjust the debug setting.
	 *
	 * @return	void
	 * @param	bool[optional] $on
	 */
	public function setDebug($on = true)
	{
		$this->debug = (bool) $on;
	}


	/**
	 * Set the even row attributes.
	 *
	 * @return	void
	 * @param	array $attributes
	 */
	public function setEvenRowAttributes(array $attributes)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// add to the list
			foreach($attributes as $key => $value) $this->attributes['row_even'][(string) $key] = (string) $value;
		}
	}


	/**
	 * Set the header labels.
	 *
	 * @return	void
	 * @param	array $labels
	 */
	public function setHeaderLabels(array $labels)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// loop the keys
			foreach($labels as $column => $label)
			{
				// column doesn't exist
				if(!isset($this->columns[$column])) throw new SpoonDatagridException('The column "'. $column .'" doesn\'t exist, therefor no label can be assigned to it.');

				// exists
				else $this->columns[$column]->setLabel($label);
			}
		}
	}


	/**
	 * Set the odd row attributes.
	 *
	 * @return	void
	 * @param	array $attributes
	 */
	public function setOddRowAttributes(array $attributes)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// add to the list
			foreach($attributes as $key => $value) $this->attributes['row_odd'][(string) $key] = (string) $value;
		}
	}


	/**
	 * Sets the value for offset. eg from the URL.
	 *
	 * @return	void
	 * @param	int[optional] $value
	 */
	public function setOffsetParameter($value = null)
	{
		$this->offsetParameter = (int) $value;
	}


	/**
	 * Sets the value for the order. eg from the URL.
	 *
	 * @return	void
	 * @param	string[optional] $value
	 */
	public function setOrderParameter($value = null)
	{
		$this->orderParameter = (string) $value;
	}


	/**
	 * Allow/disallow showing the results on multiple pages.
	 *
	 * @return	void
	 * @param	bool[optional] $on
	 */
	public function setPaging($on = false)
	{
		$this->paging = (bool) $on;
	}


	/**
	 * Sets an alternative paging class.
	 *
	 * @return	void
	 * @param	string $class
	 */
	public function setPagingClass($class)
	{
		// class cant be found
		if(!class_exists((string) $class)) throw new SpoonDatagridException('The class "'. (string) $class .'" you provided for the alternative paging can not be found.');

		// class exists
		else
		{
			// does not impmlement the interface
			if(!in_array('iSpoonDatagridPaging', class_implements($class))) throw new SpoonDatagridException('The paging class you provided does not implement the "iSpoonDatagridPaging" interface');

			// all is fine
			else $this->pagingClass = $class;
		}
	}


	/**
	 * Sets the number of results per page.
	 *
	 * @return	void
	 * @param	int[optional] $limit
	 */
	public function setPagingLimit($limit = 30)
	{
		$this->pagingLimit = abs((int) $limit);
	}


	/**
	 * Sets the row attributes.
	 *
	 * @return	void
	 * @param	array $attributes
	 */
	public function setRowAttributes(array $attributes)
	{
		foreach($attributes as $key => $value) $this->attributes['row'][(string) $key] = (string) $value;
	}


	/**
	 * Sets the columns that may be sorted on.
	 *
	 * @return	void
	 * @param	array $columns
	 * @param	string[optional] $default
	 */
	public function setSortingColumns(array $columns, $default = null)
	{
		// has results
		if($this->source->getNumResults() > 0)
		{
			// loop columns
			foreach($columns as $column)
			{
				// column doesn't exist
				if(!isset($this->columns[(string) $column])) throw new SpoonDatagridException('The column "'. (string) $column .'" doesn\'t exist and therefor can\'t be sorted on.');

				// column exists
				else
				{
					// not sortable
					if(!in_array((string) $column, $this->allowedSortingColumns)) throw new SpoonDatagridException('The column "'. (string) $column .'" can\'t be sorted on.');

					// sortable
					else
					{
						// enable sorting
						$this->columns[(string) $column]->setSorting(true);

						// set default sorting
						if(!isset($defaultColumn)) $defaultColumn = (string) $column;
					}
				}
			}

			// default column set
			if($default !== null && in_array($defaultColumn, $columns)) $defaultColumn = (string) $default;

			// default column is not good
			if(!in_array($defaultColumn, $this->allowedSortingColumns)) throw new SpoonDatagridException('The column "'. $defaultColumn .'" can\'t be set as the default sorting column, because it doesn\'t exist or may not be sorted on.');

			// set default column
			$this->sortingColumn = $defaultColumn;
		}
	}


	/**
	 * Sets the sorting icons.
	 *
	 * @return	void
	 * @param	string[optional] $asc
	 * @param	string[optional] $ascSelected
	 * @param	string[optional] $desc
	 * @param	string[optional] $descSelected
	 */
	public function setSortingIcons($asc = null, $ascSelected = null, $desc = null, $descSelected)
	{
		if($asc !== null) $this->sortingIcons['asc'] = (string) $asc;
		if($ascSelected !== null) $this->sortingIcons['ascSelected'] = (string) $ascSelected;
		if($desc !== null) $this->sortingIcons['desc'] = (string) $desc;
		if($descSelected !== null) $this->sortingIcons['descSelected'] = (string) $descSelected;
	}


	/**
	 * Sets the sorting labels.
	 *
	 * @return	void
	 * @param	string[optional] $asc
	 * @param	string[optional] $ascSelected
	 * @param	string[optional] $desc
	 * @param	string[optional] $descSelected
	 */
	public function setSortingLabels($asc = null, $ascSelected = null, $desc = null, $descSelected = null)
	{
		if($asc !== null) $this->sortingLabels['asc'] = (string) $asc;
		if($ascSelected !== null) $this->sortingLabels['ascSelected'] = (string) $ascSelected;
		if($desc !== null) $this->sortingLabels['desc'] = (string) $desc;
		if($descSelected !== null) $this->sortingLabels['descSelected'] = (string) $descSelected;
	}


	/**
	 * Sets the value to sort.
	 *
	 * @return	void
	 * @param	string[optional] $value
	 */
	public function setSortParameter($value = 'desc')
	{
		$this->sortParameter = SpoonFilter::getValue($value, array('asc', 'desc'), 'asc');
	}


	/**
	 * Sets the source for this datagrid.
	 *
	 * @return	void
	 * @param	SpoonDatagridSource $source
	 */
	private function setSource(SpoonDatagridSource $source)
	{
		$this->source = $source;
	}


	/**
	 * Sets the table summary.
	 *
	 * @return	void
	 * @param	string $value
	 */
	public function setSummary($value)
	{
		$this->summary = (string) $value;
	}


	/**
	 * Sets the path to the template file.
	 *
	 * @return	void
	 * @param	string $template
	 */
	public function setTemplate($template)
	{
		$this->template = (string) $template;
	}


	/**
	 * Defines the default URL.
	 *
	 * @return	void
	 * @param	string $URL
	 */
	public function setURL($URL)
	{
		$this->URL = (string) $URL;
	}
}


/**
 * This exception is used to handle datagrid related exceptions.
 *
 * @package		spoon
 * @subpackage	datagrid
 *
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		0.1.1
 */
class SpoonDatagridException extends SpoonException {}

?>