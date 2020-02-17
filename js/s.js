$(function () {
    $("#searchInput").keydown(function (event) {
        if (event.which == 13) {
            window.location.href = "s.php?q=" + $(this).val();
        }
    });

    $("#searchInput").focus();
});