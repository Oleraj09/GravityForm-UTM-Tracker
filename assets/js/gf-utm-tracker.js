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
                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
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

    storeUTMParams();
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
                    $form.append('<input type="hidden" name="' + key + '" value="' + value + '">');
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

    $('.gform_wrapper form').on('submit', function () {
        populateUTM($(this));
    });

    $(document).on('gform_confirmation_loaded', function (event, formId) {
        utmKeys.forEach(function (key) {
            localStorage.removeItem(key);
        });
        removeUTMFromURL();
    });

    $(window).on('load', function () {
        if ($('.gform_confirmation_message').length) {
            utmKeys.forEach(function (key) {
                localStorage.removeItem(key);
            });
            removeUTMFromURL();
        }
    });

})(jQuery);
