(function () {
	'use strict';

	document.addEventListener('click', function (event) {
		var selectButton = event.target.closest('[data-fuhao-media-select]');
		var removeButton = event.target.closest('[data-fuhao-media-remove]');

		if (selectButton) {
			event.preventDefault();

			if (typeof window.wp === 'undefined' || !window.wp.media) {
				return;
			}

			var target = document.querySelector(selectButton.getAttribute('data-target'));
			var nameBox = target ? target.parentNode.querySelector('[data-fuhao-media-name]') : null;
			var mediaType = selectButton.getAttribute('data-type') || 'image';
			var frame = window.wp.media({
				title: selectButton.getAttribute('data-title') || '选择媒体',
				library: { type: mediaType },
				button: { text: '使用此文件' },
				multiple: false
			});

			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();

				if (!target) {
					return;
				}

				target.value = attachment.id;

				if (nameBox) {
					nameBox.textContent = attachment.filename || attachment.title || '已选择';
				}

				selectButton.focus();
			});

			frame.open();
			return;
		}

		if (removeButton) {
			event.preventDefault();

			var removeTarget = document.querySelector(removeButton.getAttribute('data-target'));

			if (!removeTarget) {
				return;
			}

			removeTarget.value = '';
			var removeNameBox = removeTarget.parentNode.querySelector('[data-fuhao-media-name]');

			if (removeNameBox) {
				removeNameBox.textContent = '未选择';
			}
		}
	});
})();