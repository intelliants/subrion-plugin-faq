Ext.onReady(function(){
    if (Ext.get('js-grid-placeholder')){
        new IntelliGrid({
            columns:[
                'selection',
                'expander',
                {name: 'title', title: _t('title'), width: 1},
                {name: 'items', title: _t('items_num'), width: 90},
                'status',
                'update',
                'delete'
            ],
            expanderTemplate: '{description}',
            fields: ['description'],
            texts: {
                delete_multiple: _t('faq_are_you_sure_to_delete_selected_categories'),
                delete_single: _t('faq_are_you_sure_to_delete_selected_category')
            }
        });
    }
});