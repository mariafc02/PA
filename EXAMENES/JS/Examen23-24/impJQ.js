$(document).ready(function () {
    const tamañoNormal = parseFloat($("body").css("font-size"));

    $("#titulo").on("click", function (event) {
        if (event.which === 1) {
            $("body").animate({ fontSize: `${tamañoNormal * 2}px` }, "slow");
        }
    });

    $("#titulo").on("contextmenu", function (event) {
        event.preventDefault(); // Evita que se muestre el menú contextual
        $("body").animate({ fontSize: `${tamañoNormal}px` }, "slow");
    });
});

(function ($) {
    $.fn.contadorCaracteres = function (limite, contadorId) {

        if (!$(contadorId).length) {
            console.error("El elemento del contador no existe en el DOM.");
            return this;
        }

        this.on("input", function () {
            const caracteresUsados = $(this).val().length;
            const caracteresRestantes = limite - caracteresUsados;

            $(contadorId).text(caracteresRestantes);

            if (caracteresRestantes < 0) {
                $(contadorId).css("color", "red");
            } else {
                $(contadorId).css("color", "#555");
            }
        });

        return this;
    };
})(jQuery);

$(document).ready(function () {
    $("#description").contadorCaracteres(LIMITE_DE_CARACTERES, "#description");
});