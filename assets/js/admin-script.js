jQuery(document).ready(function ($) {
  $(".add-horario").on("click", function (e) {
    e.preventDefault();
    var index = $("#cinema-horarios-wrapper .cinema-horario-group").length;
    $("#cinema-horarios-wrapper").append(
      '<div class="cinema-horario-group">' +
        '<input type="text" name="cinema_horarios[' +
        index +
        '][hora]" placeholder="Hora" />' +
        '<input type="text" name="cinema_horarios[' +
        index +
        '][formato]" placeholder="Formato" />' +
        '<input type="text" name="cinema_horarios[' +
        index +
        '][doblaje]" placeholder="Doblaje" />' +
        '<button class="button remove-horario">Eliminar</button>' +
        "</div>"
    );
  });
  $(document).on("click", ".remove-horario", function (e) {
    e.preventDefault();
    $(this).parent(".cinema-horario-group").remove();
  });
});
