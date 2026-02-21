// ==============================
// Capture UTM params & store in localStorage
// ==============================
(function () {
    function getQueryParams() {
        let params = {};
        const query = window.location.search.substring(1);
        if (!query) return params;

        query.split("&").forEach(function (param) {
            let pair = param.split("=");
            if (pair.length === 2) {
                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1].replace(/\+/g, ' '));
            }
        });
        return params;
    }

    function storeUTMParams() {
        let utmParams = getQueryParams();
        let utmKeys = ["utm_id", "utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];
        utmKeys.forEach(function (key) {
            if (utmParams[key]) {
                localStorage.setItem(key, utmParams[key]);
            }
        });
    }

    // Check for reset param or cookie
    function checkReset() {
        const params = getQueryParams();
        const hasParam = params['utm_reset'] === '1';

        // Helper to get cookie
        function getCookie(name) {
            let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }

        const hasCookie = getCookie('mondoloz_gf_utm_reset') === '1';

        if (hasParam || hasCookie) {
            const utmKeys = ["utm_id", "utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];
            utmKeys.forEach(key => localStorage.removeItem(key));

            // Remove Cookie
            document.cookie = "mondoloz_gf_utm_reset=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";

            // Clean URL
            if (hasParam && window.history.replaceState) {
                const url = new URL(window.location);
                url.searchParams.delete('utm_reset');
                window.history.replaceState({}, document.title, url.toString());
            }
        } else {
            storeUTMParams();
        }
    }

    checkReset();
})();

// ==============================
// Gravity Forms Integration
// ==============================
(function ($) {
    var utmKeys = ["utm_id", "utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];

    function removeUTMFromURL() {
        if (window.history.replaceState) {
            const url = new URL(window.location);
            const params = url.searchParams;
            let changed = false;

            utmKeys.forEach(key => {
                if (params.has(key)) {
                    params.delete(key);
                    changed = true;
                }
            });

            if (changed) {
                url.search = params.toString();
                window.history.replaceState({}, document.title, url.toString());
            }
        }
    }

    function populateUTM($form) {
        utmKeys.forEach(function (key) {
            var value = localStorage.getItem(key);
            if (value) {
                var $field = $form.find('input[name^="input_"].' + key);
                if ($field.length) {
                    $field.val(value).trigger("change");
                }

                if ($form.find('input[name="' + key + '"]').length === 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: key,
                        value: value
                    }).appendTo($form);
                } else {
                    $form.find('input[name="' + key + '"]').val(value);
                }
            }
        });
    }

    $(document).on('gform_post_render', function (event, formId) {
        var $form = $('#gform_' + formId);
        if ($form.length) {
            populateUTM($form);
        }
    });

    // Populate on submit to ensure dynamic fields are caught
    $(document).on('submit', '.gform_wrapper form', function () {
        populateUTM($(this));
    });

    // Handle AJAX confirmation
    $(document).on('gform_confirmation_loaded', function (event, formId) {
        utmKeys.forEach(function (key) {
            localStorage.removeItem(key);
        });
        removeUTMFromURL();
    });

    // Handle Text Confirmation (Page Reload usually handles this via logic above if we wanted, but here we just clean on load if confirmation exists)
    // Actually, if it's a text confirmation on a new page (non-ajax), the previous page submission should have theoretically handled it? 
    // No, standard submission reloads page.
    $(window).on('load', function () {
        if ($('.gform_confirmation_message').length) {
            utmKeys.forEach(function (key) {
                localStorage.removeItem(key);
            });
            removeUTMFromURL();
        }
    });

})(jQuery);
