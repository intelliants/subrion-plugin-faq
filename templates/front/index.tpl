{if $categories && $total > 0}
	<div class="faq-wrapper">
		{foreach $categories as $category}
			{if $category.counter > 0}
				<div class="faq-container">
					<h4 class="faq-cat-title" data-toggle="tooltip"title="{$category.description|strip_tags}">{$category.title}</h4>

					<ol class="faq-list">
						{foreach $category.questions as $faq}
							<li>
								<a data-toggle="collapse" href="#faq{$faq.id}" aria-expanded="false">{$faq.question}</a>
								<div class="collapse" id="faq{$faq.id}">{$faq.answer}</div>
							</li>
						{/foreach}
					</ol>
				</div>
			{/if}
		{/foreach}
	</div>

	{ia_add_media files='css: _IA_URL_modules/faq/templates/front/css/style'}
{else}
	<div class="alert alert-info">{lang key='no_faq'}</div>
{/if}