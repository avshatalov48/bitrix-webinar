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

        // Очистка полей по кнопке "reset"
        $("#form__button-reset").click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var i, fields = ["USER_NAME", "DATE_BORN", "PHONE", "TOWN_LIST"];
            for (i = 0; i < fields.length; i++) {
                $("#" + fields[i]).val("");
            }
        });

    });
})(jQuery);