<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2018 Intelliants, LLC <https://intelliants.com>
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

class iaFaq extends abstractModuleFront
{
    protected static $_table = 'faq';

    protected $_itemName = 'faq';


    public function get()
    {
        $this->iaCore->factoryItem('faq_category');

        $sql = 'SELECT '
                . 'f.*, '
                . 'c.title_:lang category '
            . 'FROM :table_faq f '
            . 'LEFT JOIN :table_categories c ON (f.category_id = c.id) '
            . "WHERE f.status = ':status' "
            . 'GROUP BY f.id '
            . 'ORDER BY f.category_id, f.order, f.question_:lang';

        $sql = iaDb::printf($sql, [
            'table_faq' => self::getTable(true),
            'table_categories' => iaFaqCategory::getTable(true),
            'lang' => $this->iaCore->language['iso'],
            'status' => iaCore::STATUS_ACTIVE
        ]);

        $rows = $this->iaDb->getAll($sql);

        $this->_processValues($rows);

        $result = [];

        if ($rows) {
            foreach ($rows as $row)
                $result[$row['category']][] = $row;
        }

        return $result;
    }
}