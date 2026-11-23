(function ($) {
	'use strict';

	$(function () {
		var list = $('[data-fuhao-order-list]');

		if (!list.length) {
			return;
		}

		var input = $('[data-fuhao-order-input]');

		function sync() {
			var tokens = [];

			list.find('li[data-token]').each(function () {
				tokens.push($(this).attr('data-token'));
			});

			input.val(tokens.join(','));
		}

		list.sortable({
			handle: '.fuhao-home-order__handle',
			axis: 'y',
			tolerance: 'pointer',
			placeholder: 'fuhao-home-order__placeholder',
			update: sync
		});

		list.closest('form').on('submit', sync);
	});
})(jQuery);
