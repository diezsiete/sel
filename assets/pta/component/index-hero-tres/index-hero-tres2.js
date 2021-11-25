import $ from 'jquery';
import './index-hero-tres2.scss';

const indexHeroTres = {
    init() {
        this.$heros = {
            'left-hero': $("#left-hero"),
            'center-hero': $("#center-hero"),
            'right-hero': $("#right-hero")
        }
        this._timeouts = {
            'left-hero': null,
            'center-hero': null,
            'right-hero': null
        }
        this._currentHeroName = "init" // u

        const self = this;
        this.$heros['left-hero'].on("mouseenter", function () {
            self.enter('left-hero')
        })
        this.$heros['left-hero'].on("mouseleave", function () {
            self.leave()
        })
        this.$heros['center-hero'].on("mouseenter", function () {
            self.enter('center-hero')
        })
        this.$heros['center-hero'].on("mouseleave", function () {
            self.leave()
        })
        this.$heros['right-hero'].on("mouseenter", function () {
            self.enter('right-hero')
        })
        this.$heros['right-hero'].on("mouseleave", function () {
            self.leave()
        })
    },

    enter(heroKey) {
        if (heroKey !== this._currentHeroName) {
            const otherHeros = Object.keys(this.$heros).filter(otherKey => otherKey !== heroKey)
            this.$heros[heroKey].removeClass("hero-collapsed")
            for (const otherKey of otherHeros) {
                this.$heros[otherKey].removeClass("hero-expanded")
            }
            this.$heros[heroKey].addClass("hero-expanded")
            for (const otherKey of otherHeros) {
                this.$heros[otherKey].addClass("hero-collapsed")
            }

            this.$heros[heroKey].find(".tilt").addClass("tilt-expanded")
            this.$heros[heroKey].find(".tilt").removeClass("tilt-collapsed")
            for (const otherKey of otherHeros) {
                this.$heros[otherKey].find(".tilt").removeClass("tilt-expanded")
                this.$heros[otherKey].find(".tilt").addClass("tilt-collapsed")
            }

            this._modifyInnerClasses(this.$heros[heroKey], "expanded")
            // for (const otherKey of otherHeros) {
            //     this._modifyInnerClasses(this.$heros[otherKey], "collapsed")
            // }
            this._currentHeroName = heroKey
            // this._p(heroKey)
        }
    },

    leave() {
        if ("init" !== this._currentHeroName) {
            for (const heroKey of Object.keys(this.$heros)) {
                this.$heros[heroKey].removeClass("hero-collapsed")
                this.$heros[heroKey].removeClass("hero-expanded")
                this.$heros[heroKey].find(".tilt").removeClass("tilt-collapsed")
                this.$heros[heroKey].find(".tilt").removeClass("tilt-expanded")
                this._modifyInnerClasses(this.$heros[heroKey], "initial")
                this._currentHeroName = 'init'
            }
        }
    },


    _modifyInnerClasses($hero, action) {
        const heroKey = $hero[0].id
        clearTimeout(this._timeouts[heroKey])

        if ($hero && 0 !== $hero.length && !$hero.find("div.active." + action).length) {
            this._timeouts[heroKey] = setTimeout(function () {
                const t = $hero.find("." + action);
                const e = $hero.find("div.active:not(." + action + ")");
                if (0 < e.length) {
                    e.removeClass("active")
                }
                if (!t.hasClass('active')) {
                    t.addClass("active")
                }
            }, 200);
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