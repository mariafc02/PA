$(document).ready(function () {
    const tamañoNormal = parseFloat($("body").css("font-size"));

    $("#titulo").on("click", function (event) {
        if (event.which === 1) {
            $("body").animate({ fontSize: `${tamañoNormal * 2}px` }, "slow");
        }
    });

    // Restaurar tamaño al hacer clic con el botón derecho
    $("#titulo").on("contextmenu", function (event) {
        event.preventDefault(); // Evita que se muestre el menú contextual
        $("body").animate({ fontSize: `${tamañoNormal}px` }, "slow");
    });
});

(function ($) {
    $.fn.contadorCaracteres = function (limite, contadorId) {
        // Verificar si el elemento del contador existe
        if (!$(contadorId).length) {
            console.error("El elemento del contador no existe en el DOM.");
            return this;
        }

        // Lógica para actualizar el contador
        this.on("input", function () {
            const caracteresUsados = $(this).val().length;
            const caracteresRestantes = limite - caracteresUsados;

            // Actualizar el contador en la página
            $(contadorId).text(caracteresRestantes);

            // Cambiar el estilo si se excede el límite
            if (caracteresRestantes < 0) {
                $(contadorId).css("color", "red");
            } else {
                $(contadorId).css("color", "#555");
            }
        });

        // Permitir encadenamiento
        return this;
    };
})(jQuery);

// Usar el plugin
$(document).ready(function () {
    $("#description").contadorCaracteres(LIMITE_DE_CARACTERES, "#description");
});