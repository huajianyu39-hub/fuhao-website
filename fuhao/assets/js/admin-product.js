(function () {
	'use strict';

	var box = document.getElementById('fuhao_product_fields');
	var media = window.wp && window.wp.media;

	if (!box || !media) {
		return;
	}

	var maxGallery = 6;
	var cardInput = box.querySelector('input[name="fuhao_card_image"]');
	var cardPreview = box.querySelector('[data-fuhao-card-preview]');
	var galleryInput = box.querySelector('input[name="fuhao_gallery"]');
	var galleryList = box.querySelector('[data-fuhao-gallery]');
	var galleryItems = [];

	try {
		galleryItems = JSON.parse(galleryList.getAttribute('data-items') || '[]');
	} catch (error) {
		galleryItems = [];
	}

	function syncGallery() {
		renderGallery();
		galleryInput.value = galleryItems
			.map(function (item) {
				return item.id;
			})
			.join(',');
	}

	function renderGallery() {
		galleryList.innerHTML = '';

		galleryItems.forEach(function (item) {
			var li = document.createElement('li');
			var img = document.createElement('img');
			var remove = document.createElement('button');

			img.src = item.url;
			img.alt = item.alt || '';
			remove.type = 'button';
			remove.className = 'fuhao-gallery__remove';
			remove.setAttribute('aria-label', '移除这张图片');
			remove.textContent = '×';
			remove.addEventListener('click', function () {
				galleryItems = galleryItems.filter(function (entry) {
					return entry.id !== item.id;
				});
				syncGallery();
			});

			li.appendChild(img);
			li.appendChild(remove);
			galleryList.appendChild(li);
		});

		if (!galleryItems.length) {
			var empty = document.createElement('li');
			empty.className = 'fuhao-gallery__empty';
			empty.textContent = '还没有图集图片';
			galleryList.appendChild(empty);
		}
	}

	if (cardInput && cardPreview) {
		box.querySelector('[data-fuhao-pick-card]').addEventListener('click', function () {
			var frame = media({
				title: '选择列表预览图',
				button: { text: '使用这张图' },
				library: { type: 'image' },
				multiple: false
			});

			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

				cardInput.value = attachment.id;
				cardPreview.innerHTML = '';
				var img = document.createElement('img');
				img.src = url;
				img.alt = attachment.alt || '';
				cardPreview.appendChild(img);
			});

			frame.open();
		});

		box.querySelector('[data-fuhao-clear-card]').addEventListener('click', function () {
			cardInput.value = '';
			cardPreview.innerHTML = '<span class="fuhao-field__empty">未选择，使用主图</span>';
		});
	}

	if (galleryInput && galleryList) {
		renderGallery();

		box.querySelector('[data-fuhao-pick-gallery]').addEventListener('click', function () {
			var frame = media({
				title: '选择产品图集',
				button: { text: '加入图集' },
				library: { type: 'image' },
				multiple: 'add'
			});

			frame.on('select', function () {
				frame.state().get('selection').each(function (model) {
					var attachment = model.toJSON();
					var exists = galleryItems.some(function (item) {
						return item.id === attachment.id;
					});

					if (exists || galleryItems.length >= maxGallery) {
						return;
					}

					var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
					galleryItems.push({
						id: attachment.id,
						url: url,
						alt: attachment.alt || attachment.title || ''
					});
				});

				if (galleryItems.length >= maxGallery) {
					window.alert('图集最多 ' + maxGallery + ' 张。');
				}

				syncGallery();
			});

			frame.open();
		});

		box.querySelector('[data-fuhao-clear-gallery]').addEventListener('click', function () {
			galleryItems = [];
			syncGallery();
		});
	}
})();
