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

class iaBackendController extends iaAbstractControllerModuleBackend
{
    protected $_name = 'categories';

    protected $_helperName = 'faq_category';

    protected $_gridColumns = ['title', 'description', 'status'];
    protected $_gridFilters = ['status' => self::EQUAL];
    protected $_gridQueryMainTableAlias = 'c';

    protected $_phraseAddSuccess = 'faq_category_added';

    protected $_phraseGridEntryDeleted = 'faq_category_deleted';
    protected $_phraseGridEntriesDeleted = 'faq_categories_deleted';

    private $_iaFaq;


    public function init()
    {
        $this->_iaFaq = $this->_iaCore->factoryItem('faq');
    }

    protected function _setPageTitle(&$iaView, array $entryData, $action)
    {
        $iaView->title(iaLanguage::get($action . '_faq_category', $iaView->title()));

        iaBreadcrumb::insert(iaLanguage::get('page_title_faq_items'), IA_ADMIN_URL . 'faq/', iaBreadcrumb::POSITION_FIRST);
    }

    protected function _setDefaultValues(array &$entry)
    {
        $entry['title'] = $entry['description'] = '';
        $entry['status'] = iaCore::STATUS_ACTIVE;
    }

    public function _gridQuery($columns, $where, $order, $start, $limit)
    {
        $this->_iaCore->factoryItem('faq');

        $sql = 'SELECT '
                . ':columns, '
                . 'COUNT(f.id) items '
            . 'FROM :table_categories c '
            . 'LEFT JOIN :table_items f ON (f.category_id = c.id) '
            . 'WHERE :where :order '
            . 'GROUP BY c.id '
            . 'LIMIT :start, :limit';

        $sql = iaDb::printf($sql, [
            'lang' => $this->_iaCore->language['iso'],
            'table_categories' => $this->_iaDb->prefix . $this->getTable(),
            'table_items' => iaFaq::getTable(true),
            'columns' => $columns,
            'where' => $where,
            'order' => $order,
            'start' => (int)$start,
            'limit' => (int)$limit
        ]);

        return $this->_iaDb->getAll($sql);
    }

    protected function _delete($entryId)
    {
        if(parent::_delete($entryId)) {
            // un-assign faq items from
            $this->_iaDb->update(['category_id' => 0],
                iaDb::convertIds($entryId, 'category_id'), null, iaFaq::getTable());

            return true;
        }

        return false;
    }

    protected function _gridModifyOutput(array &$entries)
    {
        foreach ($entries as &$entry) {
            $entry['description'] = iaSanitize::tags($entry['description']);
        }
    }

    protected function _preSaveEntry(array &$entry, array $data, $action)
    {
        parent::_preSaveEntry($entry, $data, $action);

        $entry['status'] = $data['status'];

        return !$this->getMessages();
    }
}