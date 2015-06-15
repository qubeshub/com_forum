<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2015 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

namespace Components\Forum\Site;

use Hubzero\Component\Router\Base;

/**
 * Routing class for the component
 */
class Router extends Base
{
	/**
	 * Build the route for the component.
	 *
	 * @param   array  &$query  An array of URL arguments
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 */
	public function build(&$query)
	{
		$segments = array();

		if (!empty($query['section']))
		{
			$segments[] = $query['section'];
			unset($query['section']);
		}
		if (!empty($query['category']))
		{
			$segments[] = $query['category'];
			unset($query['category']);
		}
		if (!empty($query['thread']))
		{
			$segments[] = $query['thread'];
			unset($query['thread']);
		}
		if (!empty($query['post']))
		{
			$segments[] = $query['post'];
			unset($query['post']);
		}
		if (!empty($query['task']))
		{
			$segments[] = $query['task'];
			unset($query['task']);
		}
		if (!empty($query['file']))
		{
			$segments[] = $query['file'];
			unset($query['file']);
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 * @return  array  The URL attributes to be used by the application.
	 */
	public function parse(&$segments)
	{
		$vars = array();

		if (empty($segments))
		{
			return $vars;
		}

		if (isset($segments[0]))
		{
			$vars['controller'] = 'sections';
			$vars['task'] = 'display';
			$vars['section'] = $segments[0];

			if ($segments[0] == 'latest.rss')
			{
				$vars['controller'] = 'threads';
				$vars['task'] = 'latest';
				return $vars;
			}
		}

		if (isset($segments[1]))
		{
			switch ($segments[1])
			{
				case 'new':
					$vars['task'] = $segments[1];
					$vars['controller'] = 'categories';
				break;

				case 'edit':
				case 'save':
				case 'delete':
					$vars['task'] = $segments[1];
					$vars['controller'] = 'sections';
				break;

				default:
					$vars['controller'] = 'categories';
					$vars['task'] = 'display';
					$vars['category'] = $segments[1];
				break;
			}
		}

		if (isset($segments[2]))
		{
			switch ($segments[2])
			{
				case 'new':
					$vars['task'] = $segments[2];
					$vars['controller'] = 'threads';
				break;

				case 'edit':
				case 'save':
				case 'delete':
					$vars['task'] = $segments[2];
					$vars['controller'] = 'categories';
				break;

				default:
					$vars['controller'] = 'threads';
					$vars['task'] = 'display';
					$vars['thread'] = $segments[2];
				break;
			}
		}

		if (isset($segments[3]))
		{
			switch ($segments[3])
			{
				case 'new':
					$vars['task'] = $segments[3];
					$vars['controller'] = 'threads';
				break;

				case 'edit':
				case 'save':
				case 'delete':
					$vars['task'] = $segments[3];
					$vars['controller'] = 'threads';
				break;

				default:
					$vars['controller'] = 'threads';
					$vars['task'] = 'display';
					$vars['post'] = $segments[3];
				break;
			}
		}

		if (isset($segments[4]))
		{
			$vars['task'] = 'download';
			$vars['file'] = $segments[4];
		}

		return $vars;
	}
}