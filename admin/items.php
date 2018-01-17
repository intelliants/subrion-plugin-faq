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

class iaBackendController extends iaAbstractControllerModuleBackend
{
    protected $_name = 'items';

    protected $_helperName = 'faq';

    protected $_gridColumns = ['question', 'answer', 'status', 'order'];
    protected $_gridFilters = ['status' => self::EQUAL];
    protected $_gridSorting = ['category_title' => ['title', 'c', 'faq_categories']];
    protected $_gridQueryMainTableAlias = 'f';

    protected $_phraseAddSuccess = 'faq_added';

    protected $_activityLog = ['item' => 'faq', 'title_field' => 'question'];

    private $_iaFaqCategory;


    public function init()
    {
        $this->_path = $this->_path = IA_ADMIN_URL . $this->getModuleName() . IA_URL_DELIMITER;

        $this->_iaFaqCategory = $this->_iaCore->factoryItem('faq_category');
    }

    protected function _setPageTitle(&$iaView, array $entryData, $action)
    {
        $iaView->title(iaLanguage::get($action . '_faq', $iaView->title()));
    }

    protected function _gridModifyParams(&$conditions, &$values, array $params)
    {
        if (!empty($params['text'])) {
            $langCode = $this->_iaCore->language['iso'];

            $conditions[] = "(f.question_{$langCode} LIKE :text OR f.answer{$langCode} LIKE :text)";
            $values['text'] = '%' . iaSanitize::sql($params['text']) . '%';
        }
    }

    public function _gridQuery($columns, $where, $order, $start, $limit)
    {
        $sql = 'SELECT '
                . ':columns, '
                . 'c.title_:lang category '
            . 'FROM :table_items f '
            . 'LEFT JOIN :table_categories c ON (f.category_id = c.id) '
            . 'WHERE :where :order '
            . 'LIMIT :start, :limit';

        $sql = iaDb::printf($sql, [
            'lang' => $this->_iaCore->language['iso'],
            'table_items' => $this->_iaDb->prefix . $this->getTable(),
            'table_categories' => $this->_iaDb->prefix . $this->_iaFaqCategory->getTable(),
            'columns' => $columns,
            'where' => $where,
            'order' => $order,
            'start' => (int)$start,
            'limit' => (int)$limit
        ]);

        return $this->_iaDb->getAll($sql);
    }

    protected function _setDefaultValues(array &$entry)
    {
        $entry = [
            'category_id' => 0,
            'status' => iaCore::STATUS_ACTIVE
        ];
    }

    protected function _preSaveEntry(array &$entry, array $data, $action)
    {
        parent::_preSaveEntry($entry, $data, $action);

        $entry['category_id'] = (int)$data['category_id'];
        $entry['status'] = $data['status'];

        return !$this->getMessages();
    }

    protected function _assignValues(&$iaView, array &$entryData)
    {
        parent::_assignValues($iaView, $entryData);

        $iaView->assign('categories', $this->_iaFaqCategory->getKeyValue());
    }
}
