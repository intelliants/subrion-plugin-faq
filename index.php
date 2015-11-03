<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	$entries = $iaDb->all(iaDb::ALL_COLUMNS_SELECTION, "`status` = 'active' ORDER BY `order` ASC", null, null, 'faq');
	$iaView->assign('faqs', $entries);

	$iaView->display('index');
}