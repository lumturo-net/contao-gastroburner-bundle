window.onload = function () {

    var $nextButton = $('.js-next-button');
    var $questions = $('.js-question');
    var actQuestion = 0;
    var $statusBar = $('.js-status-width');

    // start
    $('.js-start').on('click', function () {
        $('.gastromatlist,.gastromat_steps').hide();
        $('.js-question').first().show();
        $(this).hide();
        $('.js-gastroburner-module').show();
        $(document).scrollTop($('.js-gastroburner-module').offset().top);
    })

    // antwort
    $('.js-question__item').on('click', function () {
        var $this = $(this);
        $this.parents('.js-question').find('.js-question__item').removeClass('active');
        $this.addClass('active');
        $nextButton.removeClass('btn--disable');
    });

    // nächste
    $('.js-next-button').on('click', function () {
        if ($nextButton.hasClass('btn--disable')) {
            return;
        }
        $questions.eq(actQuestion++).hide();
        $questions.eq(actQuestion).show();
        $statusBar.width(actQuestion / 7 * 100 + '%');
        $nextButton.addClass('btn--disable');

        if (actQuestion == 7) {
            $('.js-questions').hide();
            $('.js-gastroburner-result').show();
            calculateResults();
        }
    });

    // click auf einen job
    $('.result__checkbox,.result__copy').on('click', function () {
        var $this = $(this);
        var $parent = $this.parents('.result__item');
        var type = $parent.data('type');
        $parent.toggleClass('active');
        if ($parent.hasClass('active')) {
            $('input[type="hidden"][name="' + type + '"').val('1');
        } else {
            $('input[type="hidden"][name="' + type + '"').val('0');
        }

        // absenden-button enable/disable
        if ($('input.hidden_job[value="1"]').length) {
            $('.js-submit-button').removeClass('btn--disable');
        } else {
            $('.js-submit-button').addClass('btn--disable');
        }
    });

    // form nur schicken, wenn auch ein Job angehakt
    $('#js_start_apply_form').on('submit', function (e) {
        if ($('input.hidden_job[value="1"]').length) {
            return true;
        }
        e.preventDefault();
        return false;
    });



    function calculateResults() {
        var result_points = { COOK: 0, RESTAURANT: 0, HOTELCLEANER: 0, HOTELMANAGER: 0, GASTRO: 0 };
        // var max_results = { COOK: 24, RESTAURANT: 24, HOTELCLEANER: 24, HOTELMANAGER: 21, GASTRO: 14 };
        var max_results = { COOK: 0, RESTAURANT: 0, HOTELCLEANER: 0, HOTELMANAGER: 0, GASTRO: 0 };

        // berechne max_results anhand der vorhandenen Fragen
        var jobs = ['COOK', 'RESTAURANT', 'HOTELCLEANER', 'HOTELMANAGER', 'GASTRO'];
        $('.js-question').each(function (index, question) {
            var $question = $(question);
            var type = $question.data('type');
            $(jobs).each(function (index, job) {
                max_results[job] += points[job][type];
            });
        });
        // console.log('max_results=' + max_results);

        $questions.each(function (index, question) {
            var $question = $(question);
            var type = $question.data('type');
            var $active = $question.find('.active');
            var point = $active.data('value');

            $.each(result_points, function (index, val) {
                if (points[index][type] == point) {
                    result_points[index] += point;
                }
            });
            // console.log(result_points);
        });

        // Werte setzen
        $.each(result_points, function (index, val) {
            var procent = parseInt(result_points[index] * 100 / max_results[index]);
            var $div = $('.js-' + index);
            if (procent == 0) {
                $div.find('.result__indicator').remove();
            } else {
                $div.find('.result__indicator').width(procent + "%");
            }
            $div.find('.js-value').text(procent + "%");

            // console.log(procent);
        })
    }

    // Formular
    $('.js-toggle-label').on('click', function () {
        $(this).toggleClass('active');
    });

    $('.js-toggle-checkbox').on('click', function () {
        $(this).find('.gastroform_inputcheckbox').toggleClass('active');
    });
    /* Gastroburner-Bewerb-Form (2. Seite)
    --------------------------- */
    if ($('p.error').length) {
        $(document).scrollTop($('#formularancor').offset().top);
    }

    $('#apply_form').on('submit', function (e) {
        if($('.company-checkbox:checked').length > 0) {
            return true;
        }

        if($('.js-toggle-checkbox.active').length > 0) {
            return true;
        }

        e.preventDefault();
        $('.js-dataprivacy').addClass('error');
        return false;
    })
};
