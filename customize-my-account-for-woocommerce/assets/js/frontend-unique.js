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

document.addEventListener("DOMContentLoaded", function () {

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