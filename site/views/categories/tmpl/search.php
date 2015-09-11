<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
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
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

defined('_HZEXEC_') or die();

$this->css()
     ->js();
?>
<header id="content-header">
	<h2><?php echo $this->escape($this->title); ?></h2>

	<div id="content-header-extra">
		<p>
			<a class="icon-folder categories btn" href="<?php echo Route::url('index.php?option=' . $this->option); ?>">
				<?php echo Lang::txt('COM_FORUM_ALL_CATEGORIES'); ?>
			</a>
		</p>
	</div>
</header>

<section class="main section">
	<div class="section-inner">
		<div class="subject">
			<?php foreach ($this->notifications as $notification) { ?>
				<p class="<?php echo $notification['type']; ?>">
					<?php echo $this->escape($notification['message']); ?>
				</p>
			<?php } ?>

			<form action="<?php echo Route::url('index.php?option=' . $this->option . '&controller=categories&task=search'); ?>" method="get">
				<div class="container data-entry">
					<input class="entry-search-submit" type="submit" value="<?php echo Lang::txt('COM_FORUM_SEARCH'); ?>" />
					<fieldset class="entry-search">
						<legend><span><?php echo Lang::txt('COM_FORUM_SEARCH_LEGEND'); ?></span></legend>

						<label for="entry-search-field"><?php echo Lang::txt('COM_FORUM_SEARCH_LABEL'); ?></label>
						<input type="text" name="q" id="entry-search-field" value="<?php echo $this->escape($this->filters['search']); ?>" placeholder="<?php echo Lang::txt('COM_FORUM_SEARCH_PLACEHOLDER'); ?>" />
					</fieldset>
				</div><!-- / .container -->

				<div class="container">
					<table class="entries">
						<caption>
							<?php echo Lang::txt('COM_FORUM_SEARCH_FOR', $this->escape($this->filters['search'])); ?>
						</caption>
						<tbody>
							<?php
							if ($this->filters['search'] && $this->thread->posts('list', $this->filters)->total() > 0)
							{
								foreach ($this->thread->posts() as $row)
								{
									$title = $this->escape(stripslashes($row->get('title')));
									$title = preg_replace('#' . $this->filters['search'] . '#i', "<span class=\"highlight\">\\0</span>", $title);

									$name = Lang::txt('COM_FORUM_ANONYMOUS');
									if (!$row->get('anonymous'))
									{
										$name = ($row->creator('public') ? '<a href="' . Route::url($row->creator()->getLink()) . '">' : '') . $this->escape(stripslashes($row->creator('name'))) . ($row->creator('public') ? '</a>' : '');
									}
									$cls = array();
									if ($row->get('closed'))
									{
										$cls[] = 'closed';
									}
									if ($row->get('sticky'))
									{
										$cls[] = 'sticky';
									}
									?>
									<tr<?php if (count($cls) > 0) { echo ' class="' . implode(' ', $cls) . '"'; } ?>>
										<th class="priority-5" scope="row">
											<span class="entry-id"><?php echo $this->escape($row->get('id')); ?></span>
										</th>
										<td>
											<a class="entry-title" href="<?php echo Route::url('index.php?option=' . $this->option . '&section=' . $this->sections[$this->categories[$row->get('category_id')]->get('section_id')]->get('alias') . '&category=' . $this->categories[$row->get('category_id')]->get('alias') . '&thread=' . $row->get('thread') . '&q=' . $this->filters['search']); ?>">
												<span><?php echo $title; ?></span>
											</a>
											<span class="entry-details">
												<span class="entry-date">
													<?php echo $row->created('date'); ?>
												</span>
												<?php echo Lang::txt('COM_FORUM_BY_USER', '<span class="entry-author">' . $name . '</span>'); ?>
											</span>
										</td>
										<td class="priority-4">
											<span><?php echo Lang::txt('COM_FORUM_SECTION'); ?></span>
											<span class="entry-details section-name">
												<?php echo $this->escape(\Hubzero\Utility\String::truncate($this->sections[$this->categories[$row->get('category_id')]->get('section_id')]->get('title'), 100, array('exact' => true))); ?>
											</span>
										</td>
										<td class="priority-3">
											<span><?php echo Lang::txt('COM_FORUM_CATEGORY'); ?></span>
											<span class="entry-details category-name">
												<?php echo $this->escape(\Hubzero\Utility\String::truncate($this->categories[$row->get('category_id')]->get('title'), 100, array('exact' => true))); ?>
											</span>
										</td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr>
									<td><?php echo Lang::txt('COM_FORUM_CATEGORY_EMPTY'); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php
						$pageNav = $this->pagination(
							$this->thread->posts('count', $this->filters),
							$this->filters['start'],
							$this->filters['limit']
						);
						$pageNav->setAdditionalUrlParam('q', $this->filters['search']);
						echo $pageNav->render();
					?>
					<div class="clearfix"></div>
				</div><!-- / .container -->
			</form>
		</div><!-- /.subject -->
		<aside class="aside">
			<?php if ($this->config->get('access-create-thread')) { ?>
				<div class="container">
					<h3><?php echo Lang::txt('COM_FORUM_CREATE_YOUR_OWN'); ?></h3>
					<?php if (!$this->category->isClosed()) { ?>
						<p>
							<?php echo Lang::txt('COM_FORUM_CREATE_YOUR_OWN_DISCUSSION'); ?>
						</p>
						<p>
							<a class="icon-add add btn" href="<?php echo Route::url($this->category->link('newthread')); ?>"><?php echo Lang::txt('COM_FORUM_NEW_DISCUSSION'); ?></a>
						</p>
					<?php } else { ?>
						<p class="warning">
							<?php echo Lang::txt('COM_FORUM_CATEGORY_CLOSED'); ?>
						</p>
					<?php } ?>
				</div><!-- / .container -->
			<?php } ?>
		</aside><!-- / .aside -->
	</div>
</section><!-- /.main -->