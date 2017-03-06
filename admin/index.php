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

$iaFaq = $iaCore->factoryPlugin('faq', iaCore::ADMIN);

$iaDb->setTable($iaFaq::getTable());

if (iaView::REQUEST_JSON == $iaView->getRequestType())
{
	switch ($pageAction)
	{
		case iaCore::ACTION_READ:

			$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
			$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
			$order = isset($_GET['sort']) ? " ORDER BY `{$_GET['sort']}` {$_GET['dir']}" : '';
			$where = $values = array();

			if (isset($_GET['status']) && in_array($_GET['status'], array(iaCore::STATUS_ACTIVE, iaCore::STATUS_INACTIVE)))
			{
				$where[] = '`status` = :status';
				$values['status'] = $_GET['status'];
			}

			if (isset($_GET['text']) && $_GET['text'])
			{
				$where[] = '(`question` LIKE :text OR `answer` LIKE :text)';
				$values['text'] = '%' . $_GET['text'] . '%';
			}

			$where || $where[] = iaDb::EMPTY_CONDITION;

			$where = implode(' OR ', $where);
			$iaDb->bind($where, $values);

			$output = array(
				'total' => $iaDb->one(iaDb::STMT_COUNT_ROWS, $where),
				'data' => $iaDb->all(array('id', 'question', 'answer', 'status', 'lang', 'order', 'update' => 1, 'delete' => 1), $where . $order, $start, $limit)
			);

			break;

		case iaCore::ACTION_EDIT:
			$output = $iaFaq->gridUpdate($_POST);

			break;

		case iaCore::ACTION_DELETE:
			$output = $iaFaq->gridDelete($_POST);
	}

	$iaView->assign($output);
}

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	if (iaCore::ACTION_ADD == $pageAction || iaCore::ACTION_EDIT == $pageAction)
	{
		$faq = array(
			'lang' => IA_LANGUAGE,
			'status' => iaCore::STATUS_ACTIVE
		);

		if (iaCore::ACTION_EDIT == $pageAction)
		{
			if (!isset($iaCore->requestPath[0]))
			{
				return iaView::errorPage(iaView::ERROR_NOT_FOUND);
			}

			$id = (int)$iaCore->requestPath[0];
			$faq = $iaDb->row(iaDb::ALL_COLUMNS_SELECTION, iaDb::convertIds($id));
		}

		$faq = array(
			'id' => isset($id) ? $id : 0,
			'cid' => iaUtil::checkPostParam('cid', $faq),
			'lang' => iaUtil::checkPostParam('lang', $faq),
			'status' => iaUtil::checkPostParam('status', $faq),
			'question' => iaUtil::checkPostParam('question', $faq),
			'answer' => iaUtil::checkPostParam('answer', $faq),
		);

		$categs = $iaDb->all(array('id', 'title'), '1=1 ORDER BY `id`', 0, 0, 'faq_categs');
		$iaView->assign('categs', $categs);

		if (isset($_POST['save']))
		{
			$error = false;
			$messages = array();

			iaUtil::loadUTF8Functions('ascii', 'validation', 'bad', 'utf8_to_ascii');

			$faq['status'] = isset($_POST['status']) && !empty($_POST['status']) && in_array($_POST['status'], array(iaCore::STATUS_ACTIVE, iaCore::STATUS_INACTIVE)) ? $_POST['status'] : iaCore::STATUS_INACTIVE;
			$faq['answer'] = iaUtil::safeHTML($faq['answer']);

			if (!array_key_exists($faq['lang'], $iaCore->languages))
			{
				$faq['lang'] = IA_LANGUAGE;
			}

			if (!utf8_is_valid($faq['question']))
			{
				$faq['question'] = utf8_bad_replace($faq['question']);
			}

			if (!utf8_is_valid($faq['answer']))
			{
				$faq['answer'] = utf8_bad_replace($faq['answer']);
			}

			if (empty($faq['question']))
			{
				$error = true;
				$messages[] = iaLanguage::get('error_question');
			}

			if (empty($faq['answer']))
			{
				$error = true;
				$messages[] = iaLanguage::get('error_answer');
			}

			if (!$error)
			{
				if (iaCore::ACTION_EDIT == $pageAction)
				{
					$id = $faq['id'] = (int)$iaCore->requestPath[0];
					$result = $iaDb->update($faq);
					$messages[] = iaLanguage::get('saved');

					if ($result)
					{
						$iaCore->factory('log')->write(iaLog::ACTION_UPDATE, array('item' => 'faq', 'name' => $faq['question'], 'id' => $id));
					}
				}
				else
				{
					$faq['order'] = $iaDb->getMaxOrder() + 1;

					$id = $iaDb->insert($faq);
					$messages[] = iaLanguage::get('faq_added');

					if ($id)
					{
						$iaCore->factory('log')->write(iaLog::ACTION_CREATE, array('item' => 'faq', 'name' => $faq['question'], 'id' => $id));
					}
				}

				$iaView->setMessages($messages, $error ? iaView::ERROR : iaView::SUCCESS);

				if (isset($_POST['goto']))
				{
					$url = IA_ADMIN_URL . 'faq/';
					iaUtil::post_goto(array(
						'add' => $url . 'add/',
						'list' => $url,
						'stay' => $url . 'edit/' . $faq['id'] . '/',
					));
				}
				else
				{
					iaUtil::go_to(IA_ADMIN_URL . 'faq/edit/' . $faq['id'] . '/');
				}
			}
			else
			{
				$iaView->setMessages($messages, $error ? iaView::ERROR : iaView::SUCCESS);
			}
		}

		$iaView->assign('goto', array('list' => 'go_to_list', 'add' => 'add_another_one', 'stay' => 'stay_here'));

		$iaView->assign('faq', $faq);

		$iaView->display('index');
	}
	else
	{
		$iaView->grid('_IA_URL_modules/faq/js/admin/index');
	}
}

$iaDb->resetTable();