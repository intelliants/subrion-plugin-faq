<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2017 Intelliants, LLC <https://intelliants.com>
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
 * @link https://subrion.org/
 *
 ******************************************************************************/

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	$categories = $iaDb->all(array('id', 'title', 'description'), "`status` = 'active' ORDER BY `id` ASC", null, null, 'faq_categs');
	$total = 0;

	if ($categories)
	{
		foreach ($categories as $key => $category)
		{
			$categories[$key]['questions'] = $iaDb->all(iaDb::ALL_COLUMNS_SELECTION, "`status` = 'active' AND `cid` = '{$category['id']}' ORDER BY `order` ASC", null, null, 'faq');
			$categories[$key]['counter'] = $iaDb->foundRows();
			$total += $categories[$key]['counter'];
		}
	}

	$iaView->assign('total', $total);
	$iaView->assign('categories', $categories);

	$iaView->display('index');
}