<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Forum\Admin\Models;

jimport('joomla.application.component.modeladmin');

/**
 * Category model.
 *
 * NOTE: This is used purely for interfacing with
 * the permissions system.
 */
class AdminCategory extends \JModelAdmin
{
	/**
	 * Stock method to auto-populate the model state.
	 *
	 * @return  void
	 */
	protected function populateState()
	{
		// Initialise variables.
		$table = $this->getTable();
		$key = $table->getKeyName();

		// Get the pk of the record from the request.
		$pk = \Request::getVar($key, array());
		if (!empty($pk))
		{
			$pk = intval($pk[0]);
		}
		$this->setState('category.id', $pk); //$this->getName() . '.id'

		// Load the parameters.
		$value = \Component::params($this->option);
		$this->setState('params', $value);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @return  mixed    A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_forum.category', 'category', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 * @return  object  A database object
	 */
	public function getTable($type = 'Category', $prefix = 'ForumTable', $config = array())
	{
		$db = \App::get('db');
		return new \Components\Forum\Tables\Category($db); //JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array  The default data is an empty array.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = \User::getState('com_forum.edit.category.data', array());
		if (empty($data))
		{
			$data = $this->getItem();
		}
		return $data;
	}
}
