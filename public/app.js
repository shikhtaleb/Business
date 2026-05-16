/* Retont Business — app logic */
(function () {
  'use strict';

  var LANGS = ['en', 'ar', 'nl', 'de'];
  var RTL = { ar: true };
  var DICT = window.I18N || {};

  // ---------- THEME ----------
  var themeBtn = document.getElementById('themeToggle');
  var savedTheme = localStorage.getItem('rt_theme') || 'light';
  applyTheme(savedTheme);

  function applyTheme(t) {
    document.documentElement.setAttribute('data-theme', t);
    if (themeBtn) themeBtn.setAttribute('aria-pressed', t === 'dark');
  }

  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      applyTheme(cur);
      localStorage.setItem('rt_theme', cur);
    });
  }

  // ---------- PRICING ----------
  var currentBilling = 'monthly';

  var PRICES = {
    basic: { monthly: null, yearly: null },
    pro:   { monthly: null, yearly: null }
  };

  function loadPrices(lang) {
    var dict = DICT[lang] || DICT['ar'] || {};
    PRICES.basic.monthly = dict['pr.price.basic.monthly'] || '199';
    PRICES.basic.yearly  = dict['pr.price.basic.yearly']  || '159';
    PRICES.pro.monthly   = dict['pr.price.pro.monthly']   || '499';
    PRICES.pro.yearly    = dict['pr.price.pro.yearly']    || '399';
  }

  function updatePriceDisplay() {
    var basicEl = document.getElementById('price-basic');
    var proEl   = document.getElementById('price-pro');
    if (basicEl) {
      basicEl.textContent = PRICES.basic[currentBilling];
      basicEl.style.transform = 'scale(1.08)';
      setTimeout(function () { basicEl.style.transform = ''; }, 200);
    }
    if (proEl) {
      proEl.textContent = PRICES.pro[currentBilling];
      proEl.style.transform = 'scale(1.08)';
      setTimeout(function () { proEl.style.transform = ''; }, 200);
    }
  }

  var ptBtns = document.querySelectorAll('.pt button');
  ptBtns.forEach(function (b, i) {
    b.addEventListener('click', function () {
      ptBtns.forEach(function (x) { x.classList.remove('on'); });
      b.classList.add('on');
      currentBilling = i === 0 ? 'monthly' : 'yearly';
      updatePriceDisplay();
    });
  });

  // ---------- I18N ----------
  function applyLang(lang) {
    if (!DICT[lang]) lang = 'ar';
    var dict = DICT[lang];
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', RTL[lang] ? 'rtl' : 'ltr');

    if (dict['title']) document.title = dict['title'];

    document.querySelectorAll('[data-i18n]').forEach(function (el) {
      var key = el.getAttribute('data-i18n');
      if (dict[key] != null) el.textContent = dict[key];
    });
    document.querySelectorAll('[data-i18n-html]').forEach(function (el) {
      var key = el.getAttribute('data-i18n-html');
      if (dict[key] != null) el.innerHTML = dict[key];
    });

    var pop = document.querySelector('.pc.pop');
    if (pop && dict['pr.popular']) pop.setAttribute('data-popular', dict['pr.popular']);

    document.querySelectorAll('#langMenu button').forEach(function (b) {
      b.classList.toggle('on', b.getAttribute('data-lang') === lang);
    });

    document.body.style.fontFamily = lang === 'ar'
      ? '"IBM Plex Sans Arabic", system-ui, sans-serif'
      : '"Inter", "IBM Plex Sans Arabic", system-ui, sans-serif';

    loadPrices(lang);
    updatePriceDisplay();
  }

  // Always trust the server-rendered language to avoid localStorage/server mismatch
  var serverLang = document.documentElement.getAttribute('lang') || 'ar';
  var savedLang = serverLang;
  localStorage.setItem('rt_lang', serverLang);
  applyLang(savedLang);

  // ---------- LANGUAGE MENU ----------
  var langBtn  = document.getElementById('langBtn');
  var langMenu = document.getElementById('langMenu');

  if (langBtn && langMenu) {
    langBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = langMenu.classList.toggle('open');
      langBtn.setAttribute('aria-expanded', open);
    });
    langMenu.addEventListener('click', function (e) {
      var btn = e.target.closest('button[data-lang]');
      if (!btn) return;
      var lang = btn.getAttribute('data-lang');
      applyLang(lang);
      localStorage.setItem('rt_lang', lang);
      langMenu.classList.remove('open');
      langBtn.setAttribute('aria-expanded', 'false');
    });
    document.addEventListener('click', function (e) {
      if (!langMenu.contains(e.target) && !langBtn.contains(e.target)) {
        langMenu.classList.remove('open');
        langBtn.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        langMenu.classList.remove('open');
        langBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ---------- MOBILE NAV ----------
  var navToggle = document.getElementById('navToggle');
  var navLinks  = document.querySelector('.nav-links');

  if (navToggle && navLinks) {
    navToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = navLinks.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', open);
    });
    navLinks.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') {
        navLinks.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('click', function (e) {
      if (!navLinks.contains(e.target) && !navToggle.contains(e.target)) {
        navLinks.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ---------- SMOOTH SCROLL for nav links ----------
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href').slice(1);
      var target = document.getElementById(id);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

})();
