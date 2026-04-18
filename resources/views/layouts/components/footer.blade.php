<footer
	class="footer app-main-footer w-full shrink-0 font-normal leading-normal text-sm py-4 text-center border-t border-defaultborder/10 backdrop-blur-sm bg-white/95 dark:bg-bodybg/95">
	<div class="container">
		<span class="text-textmuted">
			{{ __('Copyright') }} © <span id="year">{{ date('Y') }}</span>
			<a href="{{ url('/') }}"
				class="text-primary font-semibold hover:underline">{{ config('app.name', 'WhatsAppLeadCRM') }}</a>.
			{{ __('Efficient lead management for growing teams.') }}
		</span>
	</div>
</footer>