Ext.onReady(function()
{
	var pageUrl = intelli.config.admin_url + '/faq/';

	if (Ext.get('js-grid-placeholder'))
	{
		var urlParam = intelli.urlVal('status');

		intelli.faq =
		{
			columns: [
				'selection',
				'expander',
				{name: 'question', title: _t('question'), width: 2},
				'status',
				{name: 'order', title: _t('order'), width: 50, editor: 'number'},
				'update',
				'delete'
			],
			expanderTemplate: '{answer}',
			fields: ['answer'],
			sorters: [{property: 'order', direction: 'ASC'}],
			storeParams: urlParam ? {status: urlParam} : null,
			url: pageUrl
		};

		intelli.faq = new IntelliGrid(intelli.faq, false);

		intelli.faq.toolbar = Ext.create('Ext.Toolbar', {items:[
			{
				emptyText: _t('text'),
				name: 'text',
				listeners: intelli.gridHelper.listener.specialKey,
				width: 275,
				xtype: 'textfield'
			}, {
				displayField: 'title',
				editable: false,
				emptyText: _t('status'),
				id: 'fltStatus',
				name: 'status',
				store: intelli.faq.stores.statuses,
				typeAhead: true,
				valueField: 'value',
				xtype: 'combo'
			}, {
				handler: function(){intelli.gridHelper.search(intelli.faq);},
				id: 'fltBtn',
				text: '<i class="i-search"></i> ' + _t('search')
			}, {
				handler: function(){intelli.gridHelper.search(intelli.faq, true);},
				text: '<i class="i-close"></i> ' + _t('reset')
			}]
		});

		if (urlParam)
		{
			Ext.getCmp('fltStatus').setValue(urlParam);
		}

		intelli.faq.init();
	}
});