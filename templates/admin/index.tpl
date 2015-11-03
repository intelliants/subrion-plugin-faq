<form action="" method="post" enctype="multipart/form-data" class="sap-form form-horizontal">
	{preventCsrf}
	<div class="wrap-list">
		<div class="wrap-group">
			<div class="wrap-group-heading">
				<h4>{lang key='options'}</h4>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="input-language">{lang key='language'}</label>
				<div class="col col-lg-4">
					<select name="lang" id="input-language"{if count($core.languages) == 1} disabled="disabled"{/if}>
						{foreach $core.languages as $code => $language}
							<option value="{$code}"{if $faq.lang == $code} selected="selected"{/if}>{$language.title}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="input-question">{lang key='question'}</label>
				<div class="col col-lg-4">
					<input type="text" name="question" value="{$faq.question}" id="input-question">
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="answer">{lang key='answer'}</label>
				<div class="col col-lg-8">
					{ia_wysiwyg name="answer" value=$faq.answer}
				</div>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="input-status">{lang key='status'}</label>
				<div class="col col-lg-4">
					<select name="status" id="input-status">
						<option value="active"{if iaCore::STATUS_ACTIVE == $faq.status} selected="selected"{/if}>{lang key='active'}</option>
						<option value="inactive"{if iaCore::STATUS_INACTIVE == $faq.status} selected="selected"{/if}>{lang key='inactive'}</option>
					</select>
				</div>
			</div>

		</div>
		<div class="form-actions inline">
			<input type="submit" name="save" class="btn btn-primary" value="{if iaCore::ACTION_EDIT == $pageAction}{lang key='save_changes'}{else}{lang key='add'}{/if}">
			{include file='goto.tpl'}
		</div>
	</div>
</form>