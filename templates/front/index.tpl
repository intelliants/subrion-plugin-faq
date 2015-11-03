{if $faqs}
	<div class="well">
		<h4 class="m-t-0">{lang key='question_list'}</h4>
		<ol class="m-b-0">
			{foreach $faqs as $faq}
				<li><a href="{$smarty.const.IA_SELF}#faq-{$faq.id}">{$faq.question}</a></li>
			{/foreach}
		</ol>
	</div>

	<div class="answers-list">
		{foreach $faqs as $faq}
			<div class="answer">
				<a name="faq-{$faq.id}"></a>
				<h4>{$faq@iteration}. {$faq.question}</h4>
				<div class="answer_body">{$faq.answer}</div>
			</div>
			{if !$faq@last}<hr>{/if}
		{/foreach}
	</div>
{else}
	<div class="alert alert-info">{lang key='no_faq'}</div>
{/if}