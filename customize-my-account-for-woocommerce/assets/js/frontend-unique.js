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