<?php
//##copyright##

class iaFaq extends abstractPlugin
{
	protected static $_table = 'faq';


	public function delete($id)
	{
		$result = false;

		$this->iaDb->setTable(self::getTable());

		// if item exists, then remove it
		if ($row = $this->iaDb->row_bind(array('question'), '`id` = :id', array('id' => $id)))
		{
			$result = (bool)$this->iaDb->delete('`id` = :id', self::getTable(), array('id' => (int)$id));

			if ($result)
			{
				$this->iaCore->factory('log')->write(iaLog::ACTION_DELETE, array('item' => 'faqs', 'name' => $row['question'], 'id' => (int)$id));
			}
		}

		$this->iaDb->resetTable();

		return $result;
	}
}