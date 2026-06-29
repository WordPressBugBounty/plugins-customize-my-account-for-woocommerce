/* jshint esversion: 5 */
(function ($) {
    'use strict';

    var TOTAL = 5;
    var current = 1;

    /* live settings collected from user choices */
    var cfg = {
        nav_style:                          '02',
        spending_layout_override:           '01',
        dashlink_layout_override:           '01',
        order_template_override:            '01',
        navigationwidget_layout_override:   '01',
        widget_menu_location:               ''
    };

    /* ── helpers ─────────────────────────────────────────────── */

    function pct(step) {
        return Math.round(((step - 1) / (TOTAL - 1)) * 100);
    }

    function showToast(msg, ok) {
        var $t = $('.wcmamtx-ob__toast');
        $t.find('.wcmamtx-ob__toast-msg').text(msg || 'Saving…');
        $t.removeClass('wcmamtx-ob__toast--ok wcmamtx-ob__toast--show');
        if (ok) $t.addClass('wcmamtx-ob__toast--ok');
        $t.addClass('wcmamtx-ob__toast--show');
    }

    function hideToast(delay) {
        setTimeout(function () {
            $('.wcmamtx-ob__toast').removeClass('wcmamtx-ob__toast--show wcmamtx-ob__toast--ok');
        }, delay || 1200);
    }

    function ajaxSave(done) {
        showToast('Saving your choices…');
        $.post(wcmamtx_ob.ajax_url, {
            action:   'wcmamtx_onboarding_save',
            nonce:    wcmamtx_ob.nonce,
            settings: JSON.stringify(cfg)
        }, function (res) {
            showToast('Saved!', true);
            hideToast(1000);
            if (done) done(res && res.success);
        }).fail(function () {
            hideToast(100);
            if (done) done(false);
        });
    }

    function ajaxComplete(done) {
        $.post(wcmamtx_ob.ajax_url, {
            action: 'wcmamtx_onboarding_complete',
            nonce:  wcmamtx_ob.nonce
        }, function () {
            if (done) done();
        }).fail(function () {
            if (done) done();
        });
    }

    /* ── confetti ────────────────────────────────────────────── */
    function launchConfetti() {
        var $c = $('.wcmamtx-ob__confetti');
        if (!$c.length) return;
        var colors = ['#7c3aed','#a855f7','#10b981','#f59e0b','#3b82f6','#ec4899'];
        for (var i = 0; i < 80; i++) {
            var size  = Math.random() * 8 + 5;
            var color = colors[Math.floor(Math.random() * colors.length)];
            var dur   = Math.random() * 2 + 2;
            var delay = Math.random() * 0.8;
            $('<div class="wcmamtx-ob__dot"></div>').css({
                left:             (Math.random() * 100) + '%',
                width:            size + 'px',
                height:           size + 'px',
                background:       color,
                opacity:          Math.random() * 0.7 + 0.3,
                animationDuration: dur + 's',
                animationDelay:   delay + 's'
            }).appendTo($c);
        }
        /* clean up after last dot falls */
        setTimeout(function () { $c.empty(); }, 3500);
    }

    /* ── step navigation ─────────────────────────────────────── */

    function goTo(next, direction) {
        if (next < 1 || next > TOTAL) return;
        direction = direction || 'forward';

        var $from = $('[data-panel="' + current + '"]');
        var $to   = $('[data-panel="' + next  + '"]');

        /* exit animation for current panel */
        $from
            .css('transition', 'opacity 0.3s ease, transform 0.3s ease')
            .css('transform', direction === 'forward' ? 'translateX(-32px)' : 'translateX(32px)')
            .css('opacity', '0');

        setTimeout(function () {
            $from
                .removeClass('wcmamtx-ob__panel--active wcmamtx-ob__panel--exit')
                .css({ transition: '', transform: '', opacity: '' });
        }, 320);

        /* enter animation for next panel */
        $to
            .css('transform', direction === 'forward' ? 'translateX(32px)' : 'translateX(-32px)')
            .css('opacity', '0')
            .addClass('wcmamtx-ob__panel--active');

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                $to.css({
                    transition: 'opacity 0.38s ease, transform 0.38s cubic-bezier(0.4,0,0.2,1)',
                    transform:  'translateX(0)',
                    opacity:    '1'
                });
                setTimeout(function () {
                    $to.css({ transition: '', transform: '', opacity: '' });
                }, 420);
            });
        });

        /* sidebar step indicators */
        $('[data-step]').each(function () {
            var s = parseInt($(this).data('step'), 10);
            $(this)
                .toggleClass('wcmamtx-ob__step--active', s === next)
                .toggleClass('wcmamtx-ob__step--done',   s < next);

            var $num = $(this).find('.wcmamtx-ob__step-num');
            $num.html(s < next ? '✓' : s);
        });

        /* progress bar */
        $('.wcmamtx-ob__bar-fill').css('width', pct(next) + '%');

        /* footer label */
        $('.wcmamtx-ob__foot-label').text('Step ' + next + ' of ' + TOTAL);

        /* back button */
        $('#wcmamtx-ob-prev').toggle(next > 1 && next < TOTAL);

        /* next button label */
        var $nxt = $('#wcmamtx-ob-next');
        if (next === TOTAL) {
            $('.wcmamtx-ob__foot').hide();
        } else if (next === TOTAL - 1) {
            $nxt.html('Finish Setup ✓').show();
        } else {
            $nxt.html('Next &rarr;').show();
        }

        /* launch confetti when landing on done step */
        if (next === TOTAL) {
            setTimeout(launchConfetti, 200);
        }

        current = next;
    }

    function advance() {
        var next = current + 1;

        /* steps 2-4 require a save before moving forward */
        if (current >= 2 && current <= 4) {
            var $btn = $('#wcmamtx-ob-next');
            $btn.prop('disabled', true);

            if (next === TOTAL) {
                /* last real step — save then mark complete */
                ajaxSave(function () {
                    ajaxComplete(function () {
                        $btn.prop('disabled', false);
                        goTo(next);
                    });
                });
            } else {
                ajaxSave(function () {
                    $btn.prop('disabled', false);
                    goTo(next);
                });
            }
        } else {
            goTo(next);
        }
    }

    function retreat() {
        goTo(current - 1, 'back');
    }

    /* ── init ────────────────────────────────────────────────── */

    $(function () {

        /* Activate step 1 directly — calling goTo(1) would apply exit
           and enter animations to the same element and the 320 ms cleanup
           setTimeout would strip --active before the fade-in could finish. */
        $('[data-panel="1"]').addClass('wcmamtx-ob__panel--active');
        $('[data-step="1"]').addClass('wcmamtx-ob__step--active');
        $('.wcmamtx-ob__bar-fill').css('width', '0%');
        $('.wcmamtx-ob__foot-label').text('Step 1 of 5');
        $('#wcmamtx-ob-prev').hide();

        /* welcome "Get Started" button */
        $('#wcmamtx-ob-start').on('click', function () { goTo(2); });

        /* footer navigation */
        $('#wcmamtx-ob-next').on('click', advance);
        $('#wcmamtx-ob-prev').on('click', retreat);

        /* skip link */
        $(document).on('click', '.wcmamtx-ob__skip', function (e) {
            e.preventDefault();
            if (!confirm('Skip setup? You can run it again from the plugin settings.')) return;
            ajaxComplete(function () {
                window.location.href = wcmamtx_ob.customizer_url;
            });
        });

        /* ── Nav style card picker ── */
        $(document).on('click', '.wcmamtx-ob__nav-card:not(.wcmamtx-ob__nav-card--disabled)', function () {
            $('.wcmamtx-ob__nav-card').removeClass('wcmamtx-ob__nav-card--selected');
            $(this).addClass('wcmamtx-ob__nav-card--selected');
            cfg.nav_style = String($(this).data('value'));
        });

        /* ── Toggle switches ── */
        $(document).on('change', '.wcmamtx-ob__sw input[data-key]', function () {
            var key = $(this).data('key');
            var on  = this.checked;
            var $card = $(this).closest('.wcmamtx-ob__toggle-card');
            $card.toggleClass('wcmamtx-ob__toggle-card--on', on);

            switch (key) {
                case 'spending': cfg.spending_layout_override = on ? '01' : '02'; break;
                case 'dashlinks': cfg.dashlink_layout_override = on ? '01' : '02'; break;
                case 'avatar': break;
                case 'orders': cfg.order_template_override = on ? '01' : '02'; break;
                case 'nav_widget':
                    cfg.navigationwidget_layout_override = on ? '01' : '02';
                    $('#wcmamtx-ob-navwidget-opts').toggle(on);
                    $('#wcmamtx-ob-navwidget-main').toggleClass('wcmamtx-ob__toggle-card--on', on);
                    break;
            }
        });

        /* ── Menu location select ── */
        $(document).on('change', '#wcmamtx-ob-menu-location', function () {
            cfg.widget_menu_location = $(this).val();
        });
        /* seed initial value from whatever is pre-selected */
        var $loc = $('#wcmamtx-ob-menu-location');
        if ($loc.length) { cfg.widget_menu_location = $loc.val(); }

        /* keyboard navigation */
        $(document).on('keydown', function (e) {
            if (e.key === 'Enter' && $(e.target).is('body, .wcmamtx-ob')) {
                if (current < TOTAL) advance();
            }
        });
    });

}(jQuery));
