<footer
	class="footer mt-auto xl:ps-[15rem] font-normal leading-normal text-sm bg-white dark:bg-bodybg py-4 text-center border-t border-defaultborder/10">
	<div class="container">
		<span class="text-textmuted">
			{{ __('Copyright') }} © <span id="year">{{ date('Y') }}</span>
			<a href="{{ url('/') }}"
				class="text-primary font-semibold hover:underline">{{ config('app.name', 'WhatsAppLeadCRM') }}</a>.
			{{ __('Efficient lead management for growing teams.') }}
		</span>
	</div>
</footer>