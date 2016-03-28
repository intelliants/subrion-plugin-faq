<?php
//##copyright##

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