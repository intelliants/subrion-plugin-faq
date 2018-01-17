Ext.onReady(function(){
    if (Ext.get('js-grid-placeholder')) {
        var grid = new IntelliGrid({
            columns: [
                'selection',
                'expander',
                {name: 'question', title: _t('field_faq_question'), width: 1},
                {name: 'category', title: _t('category'), width: 150},
                'status',
                {name: 'order', title: _t('order'), width: 60, editor: 'number'},
                'update',
                'delete'
            ],
            expanderTemplate: '{answer}',
            fields: ['answer'],
            sorters: [{property: 'order', direction: 'ASC'}],
        }, false);

        grid.toolbar = Ext.create('Ext.Toolbar', {items:[
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
                store: grid.stores.statuses,
                typeAhead: true,
                valueField: 'value',
                xtype: 'combo'
            }, {
                handler: function(){intelli.gridHelper.search(grid);},
                id: 'fltBtn',
                text: '<i class="i-search"></i> ' + _t('search')
            }, {
                handler: function(){intelli.gridHelper.search(grid, true);},
                text: '<i class="i-close"></i> ' + _t('reset')
            }]
        });

        grid.init();
    }
});