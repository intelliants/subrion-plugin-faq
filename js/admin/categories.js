Ext.onReady(function()
{
	var pageUrl = intelli.config.admin_url + '/faq/categories/';

	if (Ext.get('js-grid-placeholder'))
	{
		intelli.cart_categs = new IntelliGrid({
			columns:[
				'selection',
				'expander',
				{name: 'title', title: _t('title'), width: 2},
				'status',
				'update',
				'delete'
			],
			expanderTemplate: '{description}',
			fields: ['description', 'default'],
			url: pageUrl,
			texts: {
				delete_multiple: _t('faq_are_you_sure_to_delete_selected_categs'),
				delete_single: _t('faq_are_you_sure_to_delete_selected_categ')
			}
		}, false);

		intelli.cart_categs.init();
	}
});