$(function () {
    if ("undefined" == typeof PAYPAL) {
        window.PAYPAL = { Marketing: {} }
    }
    if ("undefined" == typeof PP_GLOBAL_JS_STRINGS) {
        PP_GLOBAL_JS_STRINGS = {
            "CLOSE": "Close"
        }
    }

    var i, r, a, s, l, c, t;
    function n() {
        $.each(l, function (e, t) {
            $(t).next(".menu-wrapper").removeClass("open")
        })
    }
    function o() {
        $.each(l, function (e, t) {
            $(t).removeClass("is-open")
        })
    }
    function u(e) {
        e || a.addClass("menu-close"),
            a.removeClass("menu-open"),
            s.removeClass("pp-header-open"),
            n(),
            o()
    }
    function d() {
        t = $(window).width(),
            $(window).on("resize", function () {
                var e = $(window).width();
                e !== t && (t = e,
                    u(!0))
            }),
            a.on("keydown", function (e) {
                27 === e.keyCode && u()
            }),
            r.on("click", function (e) {
                e.preventDefault(),
                    a.toggleClass("menu-open"),
                    s.toggleClass("pp-header-open"),
                    a.removeClass("menu-close"),
                    i.toggleClass("menu-open"),
                    o(),
                    n()
            }),
            $(".menu-wrapper").find("a").attr("tabindex", -1),
            l.on("click", function (e) {
                e.preventDefault(),
                    n();
                var t = $(e.target);
                t.hasClass("is-open") ? (t.removeClass("is-open"),
                    t.attr("aria-expanded", "false"),
                    t.next(".menu-wrapper").removeClass("open"),
                    t.next(".menu-wrapper").find("a").attr("tabindex", -1)) : (o(),
                        t.addClass("is-open"),
                        t.attr("aria-expanded", "true"),
                        t.next(".menu-wrapper").addClass("open"),
                        t.next(".menu-wrapper").find("a").attr("tabindex", 0)),
                    t.parent().parent().find(".menu-wrapper.open").length && t.hasClass("is-open") ? (a.addClass("menu-open"),
                        a.removeClass("menu-close"),
                        s.addClass("pp-header-open")) : (a.addClass("menu-close"),
                            a.removeClass("menu-open"),
                            s.removeClass("pp-header-open"))
            }),
            c.on("click", function (e) {
                e.preventDefault(),
                    o(),
                    $(e.target).closest(".menu-wrapper").toggleClass("open"),
                    $(e.target).closest(".menu-wrapper").find("a").attr("tabindex", -1)
            })
    }
    PAYPAL.Marketing.HeaderMenu = function (e, t) {
        var n, o;
        i = t,
            r = e,
            a = $("#body"),
            s = $(document.body),
            $("header.pp-header"),
            l = i.find('li > a[rel="menuitem"]'),
            c = i.find('.subnav > a, .menu-wrapper > a[rel="menuitem"]'),
            d(),
            n = i,
            (o = $('<a><span class="accessAid">' + PP_GLOBAL_JS_STRINGS.CLOSE + "</span></a>")).attr("href", "#" + PP_GLOBAL_JS_STRINGS.CLOSE),
            o.addClass("closer"),
            o.attr("role", "button"),
            o.attr("title", PP_GLOBAL_JS_STRINGS.CLOSE),
            o.attr("data-pa-click", "header|close"),
            o.on("click", function (e) {
                e.preventDefault(),
                    u()
            }),
            n.find(".menu-wrapper:not(.not-close)").append(o),
            function () {
                var e, t = window.location.pathname;
                if ("/" !== t && (e = i.find("a[href*='" + t + "'][data-highlight]")).length) {
                    var n = e.data("highlight");
                    $("#" + n).addClass("highlight")
                }
            }(),
            $(".menu-wrapper").attr("role", "region"),
            l.attr("aria-expanded", "false")
    }

    $(function () {
        var e = $("#signup-button")
            , t = []
            , n = 0
            , o = "";
        if (void 0 !== $("#header-send-menu input[type=text]").placeholder) {
            t = [$("#header-send-menu input[type=text]"), $("#header-send-menu input[type=number]"), $("#header-req-menu input[type=email]"), $("#header-req-menu input[type=number]")];
            for (var i = 0; i < t.length; i += 1)
                t[i].placeholder(),
                    $(t[i]).css("top", "0").css("left", "0")
        }
        function r(e, t) {
            var n = $("#fixed-top")
                , o = $("#fixed-top .hero")
                , i = $(".nav-simple #fixed-top .hero")
                , r = $("#fixed-top .hero-home")
                , a = $(".nav-simple #fixed-top .hero-home")
                , s = $(document.documentElement).data("device-type")
                , l = $("header[role=banner]").height()
                , c = $(".hugger-row").height()
                , u = $(window).height() - (e || 0 + t || 0)
                , d = u - l - c
                , p = u - c
                , f = u - 225
                , h = u - 157;
            "mobile" !== s && (n.css("height", u + "px"),
                o.css("height", d + "px"),
                i.css("height", p + "px"),
                $(".hero").hasClass("hero-home") && (n.css("height", "auto"),
                    r.css("height", f + "px"),
                    a.css("height", h + "px")))
        }
        e.on("mousedown", function () {
            "undefined" != typeof ga && null !== ga && ga("send", "event", "MPP Header Sign-Up Button", "Outer")
        }),
            $(window).on("mousedown keydown", function (e) {
                "mousedown" === e.type ? $(document.documentElement).hasClass("focus-off") || $(document.documentElement).addClass("focus-off") : $(document.documentElement).hasClass("focus-off") && $(document.documentElement).removeClass("focus-off")
            }),
            $(".hero").hasClass("hero-fixed") && $("#fixed-top,#fixed-top .table-row, #fixed-top header[role=banner] > div, #fixed-top .hugger-row > div").css("display", "block"),
            $("#cookie-alert-close").on("click", function () {
                $(".cookie-notification").css("display", "none"),
                    n = 0,
                    "undefined" != typeof Storage && localStorage.setItem("eu-cookie", "true"),
                    r(0, n)
            }),
            "undefined" != typeof Storage && (o = JSON.parse(localStorage.getItem("eu-cookie"))),
            o ? ($(".cookie-notification").hide(),
                r(0, n)) : ($(".cookie-notification").show(),
                    r(0, n = $(".cookie-notification").height())),
            $(".paypal-img-logo").on("keyup", function (e) {
                9 === parseInt(e.which, 10) && $(this).addClass("on-tab")
            }),
            $(window).on("resize", function () {
                r(0, n)
            }),
            $("#body").hasClass("nav-simple") ? new PAYPAL.Marketing.HeaderMenu($("#menu-button"), $("#main-nav")) : new PAYPAL.Marketing.HeaderMenu($("#menu-button"), $("#main-menu")),
            $(window).triggerHandler("heroheightadjust")
    })
})