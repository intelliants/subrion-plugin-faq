{if $items}
    <div class="faq-wrapper">
		{foreach $items as $category => $faqs}
			<div class="faq-container">
				<h4 class="faq-cat-title" data-toggle="tooltip">{$category|escape}</h4>
				<ol class="faq-list">
					{foreach $faqs as $faq}
						<li>
							<a data-toggle="collapse" href="#faq{$faq.id}" aria-expanded="false">{$faq.question|escape}</a>
							<div class="collapse" id="faq{$faq.id}">{$faq.answer}</div>
						</li>
					{/foreach}
				</ol>
			</div>
		{/foreach}
	</div>
    {ia_add_media files='css: _IA_URL_modules/faq/templates/front/css/style'}
{else}
    <div class="alert alert-info">{lang key='no_faq'}</div>
{/if}