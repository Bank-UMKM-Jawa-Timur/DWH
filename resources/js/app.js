// $(".layout-sidebar").perfectScrollbar();

$(".datepicker").datepicker({ dateFormat: "dd-mm-yy" }).val("dd/mm/yyyy");
var sidebar = $(".layout-sidebar");
var form = $(".layout-form");

// tab function
$(".tab-wrapping .tab-button").click(function (e) {
    e.preventDefault();
    var tabId = $(this).data("tab");

    $(".tab-content").addClass("hidden");
    $(".tab-wrapping .tab-button").removeClass(
        "bg-white border-b border-theme-primary"
    );
    $(".tab-wrapping .tab-button").removeClass("text-gray-400");
    $(".tab-wrapping .tab-button").removeClass("text-theme-primary");

    $(".tab-wrapping .tab-button").addClass("text-gray-400");
    $(".tab-wrapping .tab-button").addClass("border-b-2");

    $(this).addClass("bg-white border-b-2 border-theme-primary");
    $(this).addClass("text-theme-primary");

    if (tabId) {
        $(this).removeClass("text-gray-400");
        $(this).removeClass("bg-gray-100");
    }

    $("#" + tabId).removeClass("hidden");
});

$("ul.flex a:first").trigger("click");

$(".dropdown-toggle").click(function () {
    $(this).nextAll(".dropdown-menu-link:first").toggleClass("hidden");
    $(this).children(".dropdown-arrow").toggleClass("rotate-180");
    $(".dropdown-toggle").toggleClass("active-hover");
});

// notification toggle
$(".toggle-notification").click(function (e) {
    $(".notification-list").toggleClass("hidden");
    e.stopPropagation();
});

$(document).click(function (e) {
    if (e.target.closest(".notification-list")) return;
    $(".notification-list").addClass("hidden");
});

$(".dropdown-account-toggle").click(function (e) {
    $(".dropdown-account").toggleClass("hidden");
    e.stopPropagation();
});

$(document).click(function (e) {
    if (e.target.closest(".dropdown-account")) return;
    $(".dropdown-account").addClass("hidden");
});

$("#form-toggle").click(function () {
    form.toggleClass("layout-form-collapse");
    toggleForm();
});
$("#form-close").click(function () {
    form.toggleClass("layout-form-collapse");
    toggleForm();
});

$(".toggle-sidebar").click(function () {
    // sidebar.toggleClass("-left-96");
    sidebar.toggleClass("layout-collapse");
    toggleSidebar();
    // $(".layout-overlay").toggleClass("hidden");
});

function toggleForm() {
    if (form.hasClass("layout-form-collapse")) {
        form.removeClass("hidden");
        $(".layout-overlay-form").removeClass("hidden");
    } else {
        form.addClass("hidden");
        $(".layout-overlay-form").addClass("hidden");
    }
}

function toggleSidebar() {
    if (sidebar.hasClass("layout-collapse")) {
        sidebar.removeClass("hidden");
        $(".layout-overlay").removeClass("hidden");
    } else {
        sidebar.addClass("hidden");
        $(".layout-overlay").addClass("hidden");
    }
}

// overlay action
$(".layout-overlay").click(function () {
    sidebar.toggleClass("layout-collapse");
    toggleSidebar();
});

$(".layout-overlay-form").click(function () {
    form.addClass("hidden");
    $(".layout-overlay-form").addClass("hidden");
    // toggleForm();
});
$(".layout-overlay-edit-form").click(function () {
    element.toggleClass("layout-form-collapse");
});

// toggle form edit
$(".toggle-form-edit").on("click", function () {
    const formId = $(this).data("form-id");
    $("#" + formId).removeClass("hidden");
    $(".layout-overlay-edit-form").removeClass("hidden");
});

$(".close-form-edit").on("click", function () {
    const formId = $(this).data("form-id");
    $("#" + formId).addClass("hidden");
    $(".layout-overlay-edit-form").addClass("hidden");
});

$(".toggle-modal").on("click", function () {
    const targetId = $(this).data("target-id");
    $("#" + targetId).removeClass("hidden");
    form.addClass("layout-form-collapse");
    if (targetId.slice(0, 5) !== "modal") {
        $(".layout-overlay-form").removeClass("hidden");
    }
});

$("[data-dismiss-id]").on("click", function () {
    const dismissId = $(this).data("dismiss-id");
    $("#" + dismissId).addClass("hidden");
    if (dismissId.slice(0, 5) !== "modal") {
        $(".layout-overlay-form").addClass("hidden");
    }
});

$(".toggle-fullscreen").click(function () {
    $(".toggle-fullscreen").toggleClass("is-fullscreen");
    if ($(".toggle-fullscreen").hasClass("is-fullscreen")) {
        fullscreen(true);
        $(".unfullscreen").removeClass("hidden");
        $(".fullscreen").addClass("hidden");
    } else {
        fullscreen(false);
        $(".unfullscreen").addClass("hidden");
        $(".fullscreen").removeClass("hidden");
    }
});

var elem = document.documentElement;

function fullscreen(isFullscreen) {
    if (isFullscreen) {
        openFullscreen();
    } else {
        closeFullscreen();
    }
}

function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) {
        /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) {
        /* IE11 */
        elem.msRequestFullscreen();
    }
}

function closeFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        /* Safari */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        /* IE11 */
        document.msExitFullscreen();
    }
}

