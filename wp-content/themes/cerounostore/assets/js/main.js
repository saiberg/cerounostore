(function () {
	var d = document;
	d.documentElement.classList.add('js');

	// Aparición al hacer scroll
	var els = [].slice.call(d.querySelectorAll('.rv'));
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (es) {
			es.forEach(function (e) {
				if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
			});
		}, { threshold: 0.12 });
		els.forEach(function (e) { io.observe(e); });
	} else {
		els.forEach(function (e) { e.classList.add('in'); });
	}

	// Cabecera compacta al desplazarse
	var h = d.querySelector('.cu-header');
	function onScroll() { if (h) { h.classList.toggle('is-stuck', window.scrollY > 8); } }
	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });

	// Palabra rotativa del hero
	var rot = d.querySelector('.cu-rot');
	if (rot && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		var w = rot.children, i = 0;
		setInterval(function () {
			w[i].classList.remove('on');
			i = (i + 1) % w.length;
			w[i].classList.add('on');
		}, 2400);
	}

	// Foco de luz que sigue al cursor
	var sp = d.querySelector('[data-spot]');
	if (sp) {
		sp.addEventListener('pointermove', function (e) {
			var b = sp.getBoundingClientRect();
			sp.style.setProperty('--mx', (e.clientX - b.left) + 'px');
			sp.style.setProperty('--my', (e.clientY - b.top) + 'px');
		});
	}

	// Cuenta regresiva
	d.querySelectorAll('[data-end]').forEach(function (el) {
		var end = parseInt(el.getAttribute('data-end'), 10) * 1000;
		var f = { d: el.querySelector('[data-d]'), h: el.querySelector('[data-h]'), m: el.querySelector('[data-m]'), s: el.querySelector('[data-s]') };
		function pad(n) { return (n < 10 ? '0' : '') + n; }
		function tick() {
			var t = Math.max(0, Math.floor((end - Date.now()) / 1000));
			f.d.textContent = pad(Math.floor(t / 86400));
			f.h.textContent = pad(Math.floor(t % 86400 / 3600));
			f.m.textContent = pad(Math.floor(t % 3600 / 60));
			f.s.textContent = pad(t % 60);
		}
		tick();
		setInterval(tick, 1000);
	});

	// Aviso al agregar al carrito
	var toastEl;
	function toast() {
		if (!toastEl) {
			toastEl = d.createElement('div');
			toastEl.className = 'cu-toast';
			toastEl.setAttribute('role', 'status');
			d.body.appendChild(toastEl);
		}
		toastEl.innerHTML = '<span>Producto agregado</span><a href="' + (window.cuData ? cuData.cart : '#') + '">Ver carrito</a>';
		toastEl.classList.add('show');
		clearTimeout(toastEl._t);
		toastEl._t = setTimeout(function () { toastEl.classList.remove('show'); }, 3500);
	}
	if (window.jQuery) { jQuery(d.body).on('added_to_cart', toast); }
})();
