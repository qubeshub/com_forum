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

$this->css();
?>
	<header id="content-header">
		<h2><?php echo Lang::txt('COM_FORUM'); ?></h2>

		<div id="content-header-extra">
			<p>
				<a class="icon-folder categories btn" href="<?php echo Route::url('index.php?option=' . $this->option); ?>">
					<?php echo Lang::txt('COM_FORUM_ALL_CATEGORIES'); ?>
				</a>
			</p>
		</div>
	</header>

<?php foreach ($this->notifications as $notification) { ?>
	<p class="<?php echo $notification['type']; ?>">
		<?php echo $this->escape($notification['message']); ?>
	</p>
<?php } ?>

	<section class="main section">
		<form action="<?php echo Route::url('index.php?option=' . $this->option); ?>" method="post" id="hubForm">
			<div class="explaination">
				<p><strong><?php echo Lang::txt('COM_FORUM_WHAT_IS_LOCKING'); ?></strong><br />
				<?php echo Lang::txt('COM_FORUM_LOCKING_EXPLANATION'); ?></p>
			</div><!-- / .explaination -->
			<fieldset>
				<legend>
					<?php if ($this->category->exists()) { ?>
						<?php echo Lang::txt('COM_FORUM_EDIT_CATEGORY'); ?>
					<?php } else { ?>
						<?php echo Lang::txt('COM_FORUM_NEW_CATEGORY'); ?>
					<?php } ?>
				</legend>

				<div class="grid">
					<div class="col span6">
						<label for="field-closed" id="comment-anonymous-label">
							<input class="option" type="checkbox" name="fields[closed]" id="field-closed" value="3"<?php if ($this->category->get('closed')) { echo ' checked="checked"'; } ?> />
							<?php echo Lang::txt('COM_FORUM_FIELD_CLOSED'); ?>
						</label>
					</div>
					<div class="col span6 omega">
						<label for="field-access">
							<?php echo Lang::txt('COM_FORUM_FIELD_VIEW_ACCESS'); ?>
							<select name="fields[access]" id="field-access">
								<option value="0"<?php if ($this->category->get('access', 0) == 0) { echo ' selected="selected"'; } ?>><?php echo Lang::txt('COM_FORUM_FIELD_READ_ACCESS_OPTION_PUBLIC'); ?></option>
								<option value="1"<?php if ($this->category->get('access', 0) == 1) { echo ' selected="selected"'; } ?>><?php echo Lang::txt('COM_FORUM_FIELD_READ_ACCESS_OPTION_REGISTERED'); ?></option>
							</select>
						</label>
					</div>
				</div>

				<label for="field-section_id">
					<?php echo Lang::txt('COM_FORUM_FIELD_SECTION'); ?> <span class="required"><?php echo Lang::txt('COM_FORUM_REQUIRED'); ?></span>
					<select name="fields[section_id]" id="field-section_id">
					<?php foreach ($this->model->sections('list', array('state' => 1)) as $section) { ?>
						<option value="<?php echo $section->get('id'); ?>"<?php if ($this->category->get('section_id') == $section->get('id')) { echo ' selected="selected"'; } ?>><?php echo $this->escape(stripslashes($section->get('title'))); ?></option>
					<?php } ?>
					</select>
				</label>

				<label for="field-title">
					<?php echo Lang::txt('COM_FORUM_FIELD_TITLE'); ?> <span class="required"><?php echo Lang::txt('COM_FORUM_REQUIRED'); ?></span>
					<input type="text" name="fields[title]" id="field-title" value="<?php echo $this->escape(stripslashes($this->category->get('title'))); ?>" />
				</label>

				<label for="field-description">
					<?php echo Lang::txt('COM_FORUM_FIELD_DESCRIPTION'); ?>
					<textarea name="fields[description]" id="field-description" cols="35" rows="5"><?php echo $this->escape(stripslashes($this->category->get('description'))); ?></textarea>
				</label>
			</fieldset>
			<div class="clear"></div>

			<p class="submit">
				<input class="btn btn-success" type="submit" value="<?php echo Lang::txt('COM_FORUM_SUBMIT'); ?>" />

				<a class="btn btn-secondary" href="<?php echo Route::url('index.php?option=' . $this->option); ?>">
					<?php echo Lang::txt('COM_FORUM_CANCEL'); ?>
				</a>
			</p>

			<input type="hidden" name="fields[alias]" value="<?php echo $this->category->get('alias'); ?>" />
			<input type="hidden" name="fields[id]" value="<?php echo $this->category->get('id'); ?>" />
			<input type="hidden" name="fields[state]" value="1" />
			<input type="hidden" name="fields[scope]" value="site" />
			<input type="hidden" name="fields[scope_id]" value="0" />

			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="controller" value="categories" />
			<input type="hidden" name="task" value="save" />

			<?php echo Html::input('token'); ?>
		</form>
	</section><!-- / .below section -->
