(function () {
	'use strict';

	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var mobileHeroMedia = window.matchMedia('(max-width: 920px)');
	var header = document.getElementById('site-header');
	var nav = document.getElementById('primary-nav');
	var toggle = document.querySelector('.nav-toggle');
	var toTop = document.querySelector('.to-top');

	function closeNav() {
		if (!nav || !toggle) {
			return;
		}
		nav.classList.remove('is-open');
		toggle.setAttribute('aria-expanded', 'false');
		toggle.setAttribute('aria-label', '打开导航菜单');
		nav.querySelectorAll('.menu-item-has-children.is-open').forEach(function (item) {
			item.classList.remove('is-open');
			var subToggle = item.querySelector('.submenu-toggle');
			if (subToggle) {
				subToggle.setAttribute('aria-expanded', 'false');
				subToggle.setAttribute('aria-label', '展开子菜单');
			}
		});
	}

	if (nav && toggle) {
		Array.prototype.forEach.call(nav.querySelectorAll('.menu-item-has-children'), function (item) {
			var subToggle = document.createElement('button');
			subToggle.type = 'button';
			subToggle.className = 'submenu-toggle';
			subToggle.setAttribute('aria-expanded', 'false');
			subToggle.setAttribute('aria-label', '展开子菜单');
			subToggle.addEventListener('click', function () {
				var isOpen = item.classList.toggle('is-open');
				subToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
				subToggle.setAttribute('aria-label', isOpen ? '收起子菜单' : '展开子菜单');
			});
			item.appendChild(subToggle);
		});

		toggle.addEventListener('click', function () {
			var isOpen = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			toggle.setAttribute('aria-label', isOpen ? '关闭导航菜单' : '打开导航菜单');
		});

		nav.addEventListener('click', function (event) {
			if (event.target.closest('a')) {
				closeNav();
			}
		});

		document.addEventListener('click', function (event) {
			if (!nav.classList.contains('is-open')) {
				return;
			}
			if (!nav.contains(event.target) && !toggle.contains(event.target)) {
				closeNav();
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				closeNav();
				toggle.focus();
			}
		});

		var desktopQuery = window.matchMedia('(min-width: 921px)');
		var onDesktop = function (event) {
			if (event.matches) {
				closeNav();
			}
		};
		if (typeof desktopQuery.addEventListener === 'function') {
			desktopQuery.addEventListener('change', onDesktop);
		} else if (typeof desktopQuery.addListener === 'function') {
			desktopQuery.addListener(onDesktop);
		}
	}

	function updateScrollUI() {
		var scrolled = window.scrollY > 8;

		if (header) {
			header.classList.toggle('is-scrolled', scrolled);
		}
		if (toTop) {
			toTop.classList.toggle('is-visible', window.scrollY > 600);
		}
	}

	updateScrollUI();
	window.addEventListener('scroll', updateScrollUI, { passive: true });

	if (toTop) {
		toTop.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
		});
	}

	var revealItems = document.querySelectorAll('.reveal');

	if (!reduceMotion && 'IntersectionObserver' in window && revealItems.length) {
		document.documentElement.classList.add('js-reveal');

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						observer.unobserve(entry.target);
					}
				});
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
		);

		revealItems.forEach(function (item) {
			observer.observe(item);
		});
	}

	var galleryMain = document.querySelector('.product-gallery__image');
	var galleryThumbs = document.querySelectorAll('.product-gallery__thumb');

	if (galleryMain && galleryThumbs.length > 1) {
		Array.prototype.forEach.call(galleryThumbs, function (thumb) {
			thumb.addEventListener('click', function () {
				var full = thumb.getAttribute('data-full');

				if (!full) {
					return;
				}

				galleryMain.src = full;
				galleryMain.removeAttribute('srcset');
				galleryMain.removeAttribute('sizes');

				Array.prototype.forEach.call(galleryThumbs, function (item) {
					var active = item === thumb;
					item.classList.toggle('is-active', active);
					item.setAttribute('aria-pressed', active ? 'true' : 'false');
				});
			});
		});
	}

	if (typeof window.qrcode === 'function') {
		Array.prototype.forEach.call(document.querySelectorAll('[data-fuhao-qr]'), function (box) {
			var value = box.getAttribute('data-fuhao-qr');

			if (!value) {
				return;
			}

			try {
				var qr = window.qrcode(0, 'M');
				qr.addData(value);
				qr.make();
				box.innerHTML = qr.createImgTag(4, 4);
				var image = box.querySelector('img');
				if (image) {
					image.className = 'find-card__qr-img';
					image.alt = box.getAttribute('data-fuhao-qr-alt') || '二维码';
					image.loading = 'lazy';
				}
			} catch (error) {
				box.textContent = '';
			}
		});
	}

	var carousel = document.querySelector('[data-hero-carousel]');

	if (carousel) {
		var slides = Array.prototype.slice.call(carousel.querySelectorAll('[data-hero-slide]'));
		var dots = Array.prototype.slice.call(carousel.querySelectorAll('[data-hero-dot]'));
		var prevBtn = carousel.querySelector('[data-hero-prev]');
		var nextBtn = carousel.querySelector('[data-hero-next]');
		var current = 0;
		var timer = null;
		var paused = false;
		var seconds = parseInt(carousel.getAttribute('data-interval'), 10) || 3;
		var autoplay = slides.length > 1 && !reduceMotion;

		function syncHeroVideo(video) {
			var source = mobileHeroMedia.matches && video.getAttribute('data-mobile-src')
				? video.getAttribute('data-mobile-src')
				: video.getAttribute('data-desktop-src');

			if (source && video.getAttribute('src') !== source) {
				video.setAttribute('src', source);
				video.load();
			}
		}

		function playHeroVideo(video) {
			if (reduceMotion) {
				return;
			}

			video.muted = true;
			var playback = video.play();

			if (playback && typeof playback.catch === 'function') {
				playback.catch(function () {});
			}
		}

		function updateHeroVideos() {
			slides.forEach(function (slide, i) {
				var video = slide.querySelector('[data-hero-video]');

				if (!video) {
					return;
				}

				syncHeroVideo(video);

				if (i === current && !reduceMotion) {
					playHeroVideo(video);
				} else {
					video.pause();
				}
			});
		}

		function show(index) {
			if (!slides.length) {
				return;
			}

			current = (index + slides.length) % slides.length;

			slides.forEach(function (slide, i) {
				var active = i === current;
				slide.classList.toggle('is-active', active);
				if (active) {
					slide.removeAttribute('aria-hidden');
				} else {
					slide.setAttribute('aria-hidden', 'true');
				}
			});

			dots.forEach(function (dot, i) {
				var active = i === current;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-current', active ? 'true' : 'false');
			});

			updateHeroVideos();
		}

		function stop() {
			if (timer) {
				window.clearInterval(timer);
				timer = null;
			}
		}

		function start() {
			stop();
			if (!autoplay || paused || document.hidden) {
				return;
			}
			timer = window.setInterval(function () {
				show(current + 1);
			}, Math.max(2, Math.min(20, seconds)) * 1000);
		}

		function go(index) {
			show(index);
			start();
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				go(current - 1);
			});
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				go(current + 1);
			});
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				go(parseInt(dot.getAttribute('data-hero-dot'), 10) || 0);
			});
		});

		carousel.addEventListener('mouseenter', function () {
			paused = true;
			stop();
		});

		carousel.addEventListener('mouseleave', function () {
			paused = false;
			start();
		});

		carousel.addEventListener('focusin', function () {
			paused = true;
			stop();
		});

		carousel.addEventListener('focusout', function () {
			paused = false;
			start();
		});

		carousel.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft') {
				go(current - 1);
			} else if (event.key === 'ArrowRight') {
				go(current + 1);
			}
		});

		var touchStart = 0;
		var swiped = false;

		carousel.addEventListener('touchstart', function (event) {
			touchStart = event.changedTouches[0].clientX;
			swiped = false;
		}, { passive: true });

		carousel.addEventListener('touchend', function (event) {
			var delta = event.changedTouches[0].clientX - touchStart;

			if (Math.abs(delta) > 45) {
				swiped = true;
				go(delta < 0 ? current + 1 : current - 1);
				window.setTimeout(function () {
					swiped = false;
				}, 400);
			}
		}, { passive: true });

		carousel.addEventListener('click', function (event) {
			if (swiped) {
				event.preventDefault();
				event.stopPropagation();
			}
		}, true);

		document.addEventListener('visibilitychange', function () {
			if (document.hidden) {
				stop();
				slides.forEach(function (slide) {
					var video = slide.querySelector('[data-hero-video]');
					if (video) {
						video.pause();
					}
				});
			} else {
				updateHeroVideos();
				start();
			}
		});

		var onHeroMediaChange = function () {
			updateHeroVideos();
		};

		if (typeof mobileHeroMedia.addEventListener === 'function') {
			mobileHeroMedia.addEventListener('change', onHeroMediaChange);
		} else if (typeof mobileHeroMedia.addListener === 'function') {
			mobileHeroMedia.addListener(onHeroMediaChange);
		}

		updateHeroVideos();
		start();
	}

	function copyText(text) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text);
		}

		return new Promise(function (resolve, reject) {
			var field = document.createElement('textarea');
			field.value = text;
			field.setAttribute('readonly', '');
			field.style.position = 'fixed';
			field.style.opacity = '0';
			document.body.appendChild(field);
			field.select();

			try {
				document.execCommand('copy') ? resolve() : reject();
			} catch (error) {
				reject(error);
			} finally {
				document.body.removeChild(field);
			}
		});
	}

	Array.prototype.forEach.call(document.querySelectorAll('[data-fuhao-copy]'), function (button) {
		button.addEventListener('click', function () {
			var link = button.getAttribute('data-fuhao-copy') || window.location.href;
			var hint = button.parentNode.querySelector('.find-card__hint');
			var original = button.textContent;

			copyText(link)
				.then(function () {
					button.textContent = '已复制';
					if (hint) {
						hint.textContent = '链接已复制，可粘贴发送。';
					}
				})
				.catch(function () {
					if (hint) {
						hint.textContent = '复制失败，请长按链接手动复制。';
					}
				})
				.finally(function () {
					window.setTimeout(function () {
						button.textContent = original;
					}, 2200);
				});
		});
	});
})();
