<form method="post" enctype="multipart/form-data" class="sap-form form-horizontal">
    {preventCsrf}

    {capture name='systems' append='fieldset_before'}
        <div class="row">
            <label class="col col-lg-2 control-label">{lang key='category'}</label>
            <div class="col col-lg-4">
                <select name="category_id">
                    <option value="">{lang key='_select_'}</option>
                    {html_options options=$categories selected=$item.category_id}
                </select>
            </div>
        </div>
    {/capture}

    {ia_hooker name='smartyAdminSubmitItemBeforeFields'}

    {include 'field-type-content-fieldset.tpl' isSystem=true}
</form>