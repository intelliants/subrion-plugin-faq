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

class iaBackendController extends iaAbstractControllerModuleBackend
{
	protected $_name = 'faq/categories';
	protected $_table = 'faq_categs';
	protected $_helperName = 'faqcategories';
	protected $_pluginName = 'faq';

	protected $_gridColumns = array('id', 'title', 'description', 'status');
	protected $_gridFilters = array('status' => 'equal');

	protected $_phraseAddSuccess = 'faq_categ_added';
	protected $_phraseGridEntryDeleted = 'faq_categ_deleted';
	protected $_phraseGridEntriesDeleted = 'faq_categs_deleted';


	public function __construct()
	{
		parent::__construct();
		$this->_template = 'categories';

		$iaFaqCateg = $this->_iaCore->factoryModule($this->getModuleName(), iaCore::ADMIN, $this->_helperName);
		$this->setHelper($iaFaqCateg);
	}

	protected function _indexPage(&$iaView)
	{
		$iaView->grid('_IA_URL_modules/faq/js/admin/categories');
	}

	protected function _setPageTitle(&$iaView)
	{
		if (in_array($iaView->get('action'), array(iaCore::ACTION_ADD, iaCore::ACTION_EDIT)))
		{
			$iaView->title(iaLanguage::get('faq_categ_' . $iaView->get('action')));
		}
	}

	protected function _setDefaultValues(array &$entry)
	{
		$entry['title'] = $entry['description'] = '';
		$entry['status'] = iaCore::STATUS_ACTIVE;
	}

	protected function _entryDelete($entryId)
	{
		return (bool)$this->getHelper()->delete($entryId);
	}

	protected function _preSaveEntry(array &$entry, array $data, $action)
	{
		parent::_preSaveEntry($entry, $data, $action);

		iaUtil::loadUTF8Functions('ascii', 'validation', 'bad', 'utf8_to_ascii');

		if (!utf8_is_valid($entry['title']))
		{
			$entry['title'] = utf8_bad_replace($entry['title']);
		}
		if (empty($entry['title']))
		{
			$this->addMessage('title_is_empty');
		}

		if (!utf8_is_valid($entry['description']))
		{
			$entry['description'] = utf8_bad_replace($entry['description']);
		}

		if ($this->getMessages())
		{
			return false;
		}

		$entry['status'] = $data['status'];

		return true;
	}
}