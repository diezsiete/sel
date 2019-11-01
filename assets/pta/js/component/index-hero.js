import $ from 'jquery';

$(function () {
    var s = null
        , l = null
        , c = null
        , u = "init";
    function d(n, o) {
        if ("left-hero" === n[0].id ? clearTimeout(s) : clearTimeout(l),
            n && 0 !== n.length && !(0 < n.find(".side-container div.active." + o).length)) {
            var e = setTimeout(function () {
                if (0 === n.find(".side-container div.active." + o).length) {
                    var e = n.find(".side-container div.active:not(." + o + ")");
                    if (0 < e.length) {
                        var t = n.find("." + o);
                        e.removeClass("active"),
                            t.addClass("active")
                    }
                }
            }, 200);
            "left-hero" === n[0].id ? s = e : l = e
        }
    }
    function a(e, t) {
        //window.ga("send", "event", "Uncookied HP", e, t, 0, !1)
    }
    function p(e, t, n) {
        clearTimeout(c),
            c = setTimeout(function () {
                a(t, n),
                    u = e
            }, 50)
    }

    function f(e, t) {
        if ("personal" !== u) {
            var n = $("#left-hero")
                , o = $("#right-hero");
            n.removeClass("left-hero-collapsed"),
                o.removeClass("right-hero-expanded"),
                n.addClass("left-hero-expanded"),
                o.addClass("right-hero-collapsed"),
                n.find(".left-hero-tilt").addClass("left-hero-tilt-expanded"),
                n.find(".left-hero-tilt").removeClass("left-hero-tilt-collapsed"),
                o.find(".right-hero-tilt").removeClass("right-hero-tilt-expanded"),
                o.find(".right-hero-tilt").addClass("right-hero-tilt-collapsed"),
                d(n, "expanded"),
                d(o, "collapsed"),
                p("personal", t, e + " Personal " + ("init" === u ? "Primary" : "Collapsed"))
        }
    }
    function h(e, t) {
        if ("business" !== u) {
            var n = $("#left-hero")
                , o = $("#right-hero");
            o.removeClass("right-hero-collapsed"),
                n.removeClass("left-hero-expanded"),
                o.addClass("right-hero-expanded"),
                n.addClass("left-hero-collapsed"),
                n.find(".left-hero-tilt").removeClass("left-hero-tilt-expanded"),
                n.find(".left-hero-tilt").addClass("left-hero-tilt-collapsed"),
                o.find(".right-hero-tilt").addClass("right-hero-tilt-expanded"),
                o.find(".right-hero-tilt").removeClass("right-hero-tilt-collapsed"),
                d(n, "collapsed"),
                d(o, "expanded"),
                p("business", t, e + " Business " + ("init" === u ? "Primary" : "Collapsed"))
        }
    }
    function m(e, t) {
        if ("init" !== u) {
            var n = $("#left-hero")
                , o = $("#right-hero");
            n.removeClass("left-hero-collapsed"),
                o.removeClass("right-hero-expanded"),
                n.removeClass("left-hero-expanded"),
                o.removeClass("right-hero-collapsed"),
                n.find(".left-hero-tilt").removeClass("left-hero-tilt-collapsed"),
                o.find(".right-hero-tilt").removeClass("right-hero-tilt-expanded"),
                o.find(".right-hero-tilt").removeClass("right-hero-tilt-collapsed"),
                d(n, "initial"),
                d(o, "initial"),
                p("init", t, e + ("personal" === u ? " Personal" : " Business") + " Primary")
        }
    }

    "dedicated" !== $("html").attr("data-device-type") && ($("#left-hero a").click(function (e) {
        $("#left-hero .expanded").hasClass("active") || e.preventDefault()
    }),
        $("#right-hero a").click(function (e) {
            $("#right-hero .expanded").hasClass("active") || e.preventDefault()
        })),
        $("[data-ga-twin-label]").on("click", function (e) {
            var t = ("dedicated" === $("html").attr("data-device-type") ? "Desktop " : "Tablet ") + $(e.currentTarget).attr("data-ga-twin-label");
            a("Click", t)
        }),
        "dedicated" === $("html").attr("data-device-type") ? ($("#left-hero").on("mouseenter", function () {
            f("Desktop |", "Mouse Over")
        }),
            $("#left-hero").on("mouseleave", function () {
                m("Desktop |", "Mouse Out")
            }),
            $("#right-hero").on("mouseenter", function () {
                h("Desktop |", "Mouse Over")
            }),
            $("#right-hero").on("mouseleave", function () {
                m("Desktop |", "Mouse Out")
            })) : "portable" === $("html").attr("data-device-type") ? $("#body").mouseup(function (e) {
                var t = $("#left-hero")
                    , n = $("#right-hero");
                t.is(e.target) || t.has(e.target).length ? f("Tablet |", "Tap In") : n.is(e.target) || n.has(e.target).length ? h("Tablet |", "Tap In") : m("Tablet |", "Tap Out")
            }) : "mobile" === $("html").attr("data-device-type") && $("#body").mouseup(function (e) {
                var t = $("#left-hero")
                    , n = $("#right-hero");
                t.is(e.target) || t.has(e.target).length ? (f("Mobile |", "Tap In"),
                    doScroll(t, 0)) : n.is(e.target) || n.has(e.target).length ? (h("Mobile |", "Tap In"),
                        doScroll(t, 100)) : m("Mobile |", "Tap Out")
            }),
        $.extend($.expr[":"], {
            focusable: function (e) {
                var t = e.nodeName.toLowerCase()
                    , n = $.attr(e, "tabindex");
                return (/input|select|textarea|button|object/.test(t) ? !e.disabled : ("a" === t || "area" === t) && e.href || !isNaN(n)) && !$(e)["area" === t ? "parents" : "closest"](":hidden").length
            },
            tabbable: function (e) {
                var t = $.attr(e, "tabindex");
                return (isNaN(t) || 0 <= t) && $(e).is(":focusable")
            }
        });
});