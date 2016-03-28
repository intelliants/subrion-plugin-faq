<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2016 Intelliants, LLC <http://www.intelliants.com>
 *
 * This file is part of Subrion.
 *
 * Subrion is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Subrion is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Subrion. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link http://www.subrion.org/
 *
 ******************************************************************************/

class iaFaqCategories extends abstractPlugin
{
	protected static $_table = 'faq_categs';

	public function delete($id)
	{
		$result = false;

		$this->iaDb->setTable(self::getTable());

		// if item exists, then remove it
		if ($row = $this->iaDb->row_bind(array('title'), '`id` = :id', array('id' => $id)))
		{
			$result = (bool)$this->iaDb->delete(iaDb::convertIds($id), self::getTable());

			if ($result)
			{
				$this->iaCore->factory('log')->write(iaLog::ACTION_DELETE, array('module' => 'faq', 'item' => 'faq_categs', 'name' => $row['title'], 'id' => (int)$id));
			}
		}

		$this->iaDb->resetTable();

		return $result;
	}
}