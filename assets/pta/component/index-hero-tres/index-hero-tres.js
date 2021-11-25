import $ from 'jquery';
import './index-hero-tres.scss';

const indexHeroTres = {
    init() {
        this.$heros = {
            left: $("#left-hero"),
            right: $("#right-hero")
        }
        this._leftHeroTimeout = null // s
        this._rightHeroTimeout = null // l
        this._cTimeout = null // c
        this._currentHeroName = "init" // u

        const self = this;
        this.$heros.left.on("mouseenter", function () {
            self.enterLeft()
        })
        this.$heros.left.on("mouseleave", function () {
            self.leave()
        })
        this.$heros.right.on("mouseenter", function () {
            self.enterRight()
        })
        this.$heros.right.on("mouseleave", function () {
            self.leave()
        })
    },

    enterLeft() {
        const heroKey = 'left'
        if (heroKey !== this._currentHeroName) {
            const otherKey = 'right'
            this.$heros[heroKey].removeClass("left-hero-collapsed")
            this.$heros[otherKey].removeClass("right-hero-expanded")
            this.$heros[heroKey].addClass("left-hero-expanded")
            this.$heros[otherKey].addClass("right-hero-collapsed")
            this.$heros[heroKey].find(".left-hero-tilt").addClass("left-hero-tilt-expanded")
            this.$heros[heroKey].find(".left-hero-tilt").removeClass("left-hero-tilt-collapsed")
            this.$heros[otherKey].addClass("right-hero-collapsed")
            this.$heros[otherKey].find(".right-hero-tilt").removeClass("right-hero-tilt-expanded")
            this.$heros[otherKey].find(".right-hero-tilt").addClass("right-hero-tilt-collapsed")

            this._modifyInnerClasses(this.$heros[heroKey], "expanded")
            this._modifyInnerClasses(this.$heros[otherKey], "collapsed")
            this._currentHeroName = heroKey
            // this._p(heroKey)
        }
    },
    // h
    enterRight() {
        const heroKey = 'right'
        const otherKey = 'left'
        if (heroKey !== this._currentHeroName) {
            this.$heros[heroKey].removeClass("right-hero-collapsed")
            this.$heros[otherKey].removeClass("left-hero-expanded")
            this.$heros[heroKey].addClass("right-hero-expanded")
            this.$heros[otherKey].addClass("left-hero-collapsed")
            this.$heros[otherKey].find(".left-hero-tilt").removeClass("left-hero-tilt-expanded")
            this.$heros[otherKey].find(".left-hero-tilt").addClass("left-hero-tilt-collapsed")
            this.$heros[heroKey].find(".right-hero-tilt").addClass("right-hero-tilt-expanded")
            this.$heros[heroKey].find(".right-hero-tilt").removeClass("right-hero-tilt-collapsed")
            this._modifyInnerClasses(this.$heros[otherKey], "collapsed")
            this._modifyInnerClasses(this.$heros[heroKey], "expanded")
            this._currentHeroName = heroKey
            // this._p(heroKey)
        }
    },
    // m
    leave() {
        if ("init" !== this._currentHeroName) {
            this.$heros.left.removeClass("left-hero-collapsed")
            this.$heros.right.removeClass("right-hero-expanded")
            this.$heros.left.removeClass("left-hero-expanded")
            this.$heros.right.removeClass("right-hero-collapsed")
            this.$heros.left.find(".left-hero-tilt").removeClass("left-hero-tilt-collapsed")
            this.$heros.right.find(".right-hero-tilt").removeClass("right-hero-tilt-expanded")
            this.$heros.right.find(".right-hero-tilt").removeClass("right-hero-tilt-collapsed")
            this._modifyInnerClasses(this.$heros.left, "initial")
            this._modifyInnerClasses(this.$heros.right, "initial")
            this._currentHeroName = 'init'
        }
    },

    _modifyInnerClasses($hero, action) {
        "left-hero" === $hero[0].id ? clearTimeout(this._leftHeroTimeout) : clearTimeout(this._rightHeroTimeout)
        if ($hero && 0 !== $hero.length && !(0 < $hero.find(".side-container div.active." + action).length)) {
            const e = setTimeout(function () {
                if (0 === $hero.find(".side-container div.active." + action).length) {
                    const e = $hero.find(".side-container div.active:not(." + action + ")");
                    if (0 < e.length) {
                        const t = $hero.find("." + action);
                        e.removeClass("active")
                        t.addClass("active")
                    }
                }
            }, 200);
            "left-hero" === $hero[0].id ? this._leftHeroTimeout = e : this._rightHeroTimeout = e
        }
    },

    _p(heroKey) {
        clearTimeout(this._cTimeout)
        this._cTimeout = setTimeout(function () {
            this._currentHeroName = heroKey
        }, 50)
    }

}

$(function () {
    indexHeroTres.init()

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

    function enter(device, eventName) {
        if ("personal" !== u) {

            const $leftHero = $("#left-hero")
            const $rightHero = $("#right-hero");
            $leftHero.removeClass("left-hero-collapsed"),
            $rightHero.removeClass("right-hero-expanded"),
            $leftHero.addClass("left-hero-expanded"),
            $rightHero.addClass("right-hero-collapsed"),
            $leftHero.find(".left-hero-tilt").addClass("left-hero-tilt-expanded"),
            $leftHero.find(".left-hero-tilt").removeClass("left-hero-tilt-collapsed"),
            $rightHero.find(".right-hero-tilt").removeClass("right-hero-tilt-expanded"),
            $rightHero.find(".right-hero-tilt").addClass("right-hero-tilt-collapsed"),
            d($leftHero, "expanded"),
            d($rightHero, "collapsed"),
            p("personal", eventName, device + " Personal " + ("init" === u ? "Primary" : "Collapsed"))
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

    // "dedicated" !== $("html").attr("data-device-type") && ($("#left-hero a").click(function (e) {
    //     $("#left-hero .expanded").hasClass("active") || e.preventDefault()
    // }),
    //     $("#right-hero a").click(function (e) {
    //         $("#right-hero .expanded").hasClass("active") || e.preventDefault()
    //     })),
    //     $("[data-ga-twin-label]").on("click", function (e) {
    //         var t = ("dedicated" === $("html").attr("data-device-type") ? "Desktop " : "Tablet ") + $(e.currentTarget).attr("data-ga-twin-label");
    //         a("Click", t)
    //     }),
    //     "dedicated" === $("html").attr("data-device-type") ? ($("#left-hero").on("mouseenter", function () {
    //         enter("Desktop |", "Mouse Over")
    //     }),
    //         $("#left-hero").on("mouseleave", function () {
    //             m("Desktop |", "Mouse Out")
    //         }),
    //         $("#right-hero").on("mouseenter", function () {
    //             h("Desktop |", "Mouse Over")
    //         }),
    //         $("#right-hero").on("mouseleave", function () {
    //             m("Desktop |", "Mouse Out")
    //         })) : "portable" === $("html").attr("data-device-type") ? $("#body").mouseup(function (e) {
    //             var t = $("#left-hero")
    //                 , n = $("#right-hero");
    //             t.is(e.target) || t.has(e.target).length ? enter("Tablet |", "Tap In") : n.is(e.target) || n.has(e.target).length ? h("Tablet |", "Tap In") : m("Tablet |", "Tap Out")
    //         }) : "mobile" === $("html").attr("data-device-type") && $("#body").mouseup(function (e) {
    //             var t = $("#left-hero")
    //                 , n = $("#right-hero");
    //             t.is(e.target) || t.has(e.target).length ? (enter("Mobile |", "Tap In"),
    //                 doScroll(t, 0)) : n.is(e.target) || n.has(e.target).length ? (h("Mobile |", "Tap In"),
    //                     doScroll(t, 100)) : m("Mobile |", "Tap Out")
    //         }),
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