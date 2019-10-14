window.onload = function () {
    var $nextButton = $('.js-next-button');
    var $questions = $('.js-question');
    var actQuestion = 0;
    var $statusBar = $('.js-status-width');

    // start
    $('.js-start').on('click', function () {
        $('.js-gastroburner-module').show();
        $('.js-question').first().show();
        $(this).hide();
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

    function calculateResults() {
        var result_points = { COOK: 0, RESTAURANT: 0, HOTELCLEANER: 0, HOTELMANAGER: 0, GASTRO: 0 };
        var max_results = { COOK: 24, RESTAURANT: 24, HOTELCLEANER: 24, HOTELMANAGER: 21, GASTRO: 14 };

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
            console.log(result_points);
        });

        // Werte setzen
        $.each(result_points, function (index, val) {
            var procent = parseInt(result_points[index] * 100 / max_results[index]);
            var $div = $('.js-' + index);
            $div.find('.result__indicator').width(procent + "%");
            $div.find('.js-value').text(procent + "%");

            console.log(procent);
        })
        // console.log(type);
        // console.log(point);
    }
};