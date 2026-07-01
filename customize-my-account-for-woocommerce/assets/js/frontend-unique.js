var $laz = jQuery.noConflict();
(function( $laz ) {
    'use strict';

    $laz(function() {

        $laz("li.wcmamtx_menu").hover(
          function () {
            $laz(this).addClass("current-dropdown");
        },
        function () {
            $laz(this).removeClass("current-dropdown");
        }
        );

    });
})( jQuery );

/*

document.addEventListener('DOMContentLoaded', function () {

    const widget = document.querySelector('.sb-account-widget');

    if (!widget) {
        return;
    }

    const trigger = widget.querySelector('.sb-account-trigger');

    trigger.addEventListener('click', function (e) {

        e.preventDefault();

        widget.classList.toggle('active');

    });

    document.addEventListener('click', function (e) {

        if (!widget.contains(e.target)) {
            widget.classList.remove('active');
        }

    });

});

*/

function wcmamtxBindAjaxLogin(overlay, ajaxUrl) {
    var spinner = '<svg style="vertical-align:middle;animation:wcmamtx-spin .7s linear infinite" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>';

    if (!document.getElementById('wcmamtx-spin-kf')) {
        var kf = document.createElement('style');
        kf.id = 'wcmamtx-spin-kf';
        kf.textContent = '@keyframes wcmamtx-spin{to{transform:rotate(360deg)}}';
        document.head.appendChild(kf);
    }

    function submitForm(form, action, onSuccess) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var errorEl = form.querySelector('.wcmamtx-form-error');
            if (!errorEl) {
                errorEl = document.createElement('ul');
                errorEl.className = 'woocommerce-error wcmamtx-form-error';
                errorEl.setAttribute('role', 'alert');
                form.insertBefore(errorEl, form.firstChild);
            }
            errorEl.innerHTML = '';
            errorEl.style.display = 'none';

            var btn = form.querySelector('[type="submit"]');
            var btnOrig = btn ? btn.innerHTML : '';
            if (btn) { btn.disabled = true; btn.innerHTML = spinner; }

            var data = new FormData(form);
            data.append('action', action);

            fetch(ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        onSuccess(res, form, btn, btnOrig);
                    } else {
                        errorEl.innerHTML = '<li>' + res.data.message + '</li>';
                        errorEl.style.display = '';
                        if (btn) { btn.disabled = false; btn.innerHTML = btnOrig; }
                    }
                })
                .catch(function () {
                    errorEl.innerHTML = '<li>Something went wrong. Please try again.</li>';
                    errorEl.style.display = '';
                    if (btn) { btn.disabled = false; btn.innerHTML = btnOrig; }
                });
        });
    }

    var loginForm = overlay.querySelector('form.woocommerce-form-login');
    if (loginForm) {
        submitForm(loginForm, 'wcmamtx_ajax_login', function (res) {
            window.location.href = res.data.redirect;
        });
    }

    var registerForm = overlay.querySelector('form.woocommerce-form-register');
    if (registerForm) {
        submitForm(registerForm, 'wcmamtx_ajax_register', function (res) {
            window.location.href = res.data.redirect;
        });
    }

    var lostForm = overlay.querySelector('form.woocommerce-ResetPassword');
    if (lostForm) {
        submitForm(lostForm, 'wcmamtx_ajax_lost_password', function (res, form, btn, btnOrig) {
            var successEl = form.querySelector('.wcmamtx-form-success');
            if (!successEl) {
                successEl = document.createElement('div');
                successEl.className = 'woocommerce-message wcmamtx-form-success';
                form.insertBefore(successEl, form.firstChild);
            }
            successEl.textContent = res.data.message;
            successEl.style.display = '';
            if (btn) { btn.disabled = false; btn.innerHTML = btnOrig; }
        });
    }
}

function wcmamtxAutoInputIcons() {
    var SVG = {
        person:   '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/>',
        email:    '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
        lock:     '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        phone:    '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.14 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3 2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16.92z"/>',
        location: '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        globe:    '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>',
        building: '<rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
        calendar: '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
        hash:     '<line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/>',
        list:     '<line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/>',
    };

    function pickIcon(el) {
        var isSelect = el.tagName === 'SELECT';
        var type = isSelect ? 'select' : (el.type || 'text');
        var hint = ((el.name || '') + ' ' + (el.id || '') + ' ' + (el.placeholder || '')).toLowerCase();

        if (type === 'password')                                         return 'lock';
        if (type === 'email'   || /email/.test(hint))                   return 'email';
        if (type === 'tel'     || /phone|mobile|tel/.test(hint))        return 'phone';
        if (type === 'date'    || /\bdate\b|birth|dob/.test(hint))      return 'calendar';
        if (type === 'number'  || /\b(qty|quantity|age|count)\b/.test(hint)) return 'hash';
        if (/address|street|city|suburb|postcode|zip/.test(hint))       return 'location';
        if (/country|nation/.test(hint))                                 return 'globe';
        if (/company|organi[sz]ation|business/.test(hint))              return 'building';
        if (type === 'url'     || /website|url/.test(hint))             return 'globe';
        if (type === 'select')                                           return 'list';
        if (/\b(name|user|first|last)\b/.test(hint))                    return 'person';
        return 'person';
    }

    function makeSvg(key) {
        return '<svg class="wcmamtx-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + SVG[key] + '</svg>';
    }

    var selector = '.wcmamtx_custom_login_register input[type="text"],'
        + '.wcmamtx_custom_login_register input[type="email"],'
        + '.wcmamtx_custom_login_register input[type="password"],'
        + '.wcmamtx_custom_login_register input[type="tel"],'
        + '.wcmamtx_custom_login_register input[type="number"],'
        + '.wcmamtx_custom_login_register input[type="url"],'
        + '.wcmamtx_custom_login_register input[type="date"],'
        + '.wcmamtx_custom_login_register select';

    document.querySelectorAll(selector).forEach(function (el) {
        if (el.closest('.wcmamtx-input-wrap')) return;
        var wrap = document.createElement('span');
        wrap.className = 'wcmamtx-input-wrap';
        wrap.innerHTML = makeSvg(pickIcon(el));
        el.parentNode.insertBefore(wrap, el);
        wrap.appendChild(el);
    });
}

document.addEventListener("DOMContentLoaded", function () {

    wcmamtxAutoInputIcons();

    const tabs = document.querySelectorAll(".wc-tab-btn");
    const contents = document.querySelectorAll(".wc-tab-content");

    tabs.forEach(tab => {

        tab.addEventListener("click", function () {

            tabs.forEach(btn =>
                btn.classList.remove("active")
            );

            contents.forEach(content =>
                content.classList.remove("active")
            );

            this.classList.add("active");

            document
                .getElementById(this.dataset.tab)
                .classList.add("active");

        });

    });

    document
        .querySelectorAll(".toggle-password")
        .forEach(toggle => {

            toggle.addEventListener("click", function () {

                const input =
                    this.parentElement.querySelector("input");

                if (input.type === "password") {
                    input.type = "text";
                } else {
                    input.type = "password";
                }

            });

        });

});