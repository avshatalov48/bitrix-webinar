(function ($) {
    $(document).ready(function () {

        // Дата рождения
        $.datepicker.setDefaults($.datepicker.regional["ru"]);
        var d = new Date();
        var yearRange = "1900:" + d.getFullYear();
        $("#DATE_BORN").datepicker({
            yearRange: yearRange,
            changeMonth: true,
            changeYear: true
        });

        // Телефон
        $("#PHONE").mask("+70000000000", {placeholder: "+70000000000"});

    });
})(jQuery);