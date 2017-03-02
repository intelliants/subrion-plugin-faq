{if iaCore::ACTION_ADD == $pageAction || iaCore::ACTION_EDIT == $pageAction}
	<form method="post" enctype="multipart/form-data" class="sap-form form-horizontal">
		{preventCsrf}
		<div class="wrap-list">
			<div class="wrap-group">
				<div class="wrap-group-heading">
					<h4>{lang key='general'}</h4>
				</div>

				<div class="row">
					<label class="col col-lg-2 control-label" for="input-title">{lang key='title'} {lang key='field_required'}</label>
					<div class="col col-lg-4">
						<input type="text" name="title" value="{$item.title|escape:'html'}" id="input-title">
					</div>
				</div>

				<div class="row">
					<label class="col col-lg-2 control-label" for="description">{lang key='description'}</label>
					<div class="col col-lg-8">
						<textarea name="description" rows="5">{$item.description|escape:'html'}</textarea>
					</div>
				</div>

				<div class="row">
					<label class="col col-lg-2 control-label">{lang key='status'}</label>
					<div class="col col-lg-4">
						<select name="status">
							<option value="active" {if isset($item.status) && iaCore::STATUS_ACTIVE == $item.status}selected="selected"{/if}>{lang key='active'}</option>
							<option value="inactive" {if isset($item.status) && iaCore::STATUS_INACTIVE == $item.status}selected="selected"{/if}>{lang key='inactive'}</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions inline">
			<input type="submit"  name="save" class="btn btn-primary" value="{lang key='save_changes'}">
		</div>
	</form>
{else}
	{include file='grid.tpl'}
{/if}

{ia_print_js files='_IA_URL_modules/faq/js/admin/categories'}