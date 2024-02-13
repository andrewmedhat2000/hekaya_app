(function(t) {
    function e(e) {
        for (var s, i, o = e[0], c = e[1], l = e[2], g = 0, d = []; g < o.length; g++) i = o[g], Object.prototype.hasOwnProperty.call(r, i) && r[i] && d.push(r[i][0]), r[i] = 0;
        for (s in c) Object.prototype.hasOwnProperty.call(c, s) && (t[s] = c[s]);
        u && u(e);
        while (d.length) d.shift()();
        return a.push.apply(a, l || []), n()
    }

    function n() {
        for (var t, e = 0; e < a.length; e++) {
            for (var n = a[e], s = !0, o = 1; o < n.length; o++) {
                var c = n[o];
                0 !== r[c] && (s = !1)
            }
            s && (a.splice(e--, 1), t = i(i.s = n[0]))
        }
        return t
    }
    var s = {},
        r = { app: 0 },
        a = [];

    function i(e) { if (s[e]) return s[e].exports; var n = s[e] = { i: e, l: !1, exports: {} }; return t[e].call(n.exports, n, n.exports, i), n.l = !0, n.exports }
    i.m = t, i.c = s, i.d = function(t, e, n) { i.o(t, e) || Object.defineProperty(t, e, { enumerable: !0, get: n }) }, i.r = function(t) { "undefined" !== typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(t, "__esModule", { value: !0 }) }, i.t = function(t, e) {
        if (1 & e && (t = i(t)), 8 & e) return t;
        if (4 & e && "object" === typeof t && t && t.__esModule) return t;
        var n = Object.create(null);
        if (i.r(n), Object.defineProperty(n, "default", { enumerable: !0, value: t }), 2 & e && "string" != typeof t)
            for (var s in t) i.d(n, s, function(e) { return t[e] }.bind(null, s));
        return n
    }, i.n = function(t) { var e = t && t.__esModule ? function() { return t["default"] } : function() { return t }; return i.d(e, "a", e), e }, i.o = function(t, e) { return Object.prototype.hasOwnProperty.call(t, e) }, i.p = "/";
    var o = window["webpackJsonp"] = window["webpackJsonp"] || [],
        c = o.push.bind(o);
    o.push = e, o = o.slice();
    for (var l = 0; l < o.length; l++) e(o[l]);
    var u = c;
    a.push([0, "chunk-vendors"]), n()
})({
    0: function(t, e, n) { t.exports = n("56d7") },
    "034f": function(t, e, n) {
        "use strict";
        n("85ec")
    },
    "56d7": function(t, e, n) {
        "use strict";
        n.r(e);
        n("e260"), n("e6cf"), n("cca6"), n("a79d");
        var s, r = n("2b0e"),
            a = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("v-app", [n("v-main", [t.$store.state.info ? n("HomePage") : n("div", { staticClass: "loading-page" }, [t.$store.state.loadingErrorMessage ? n("p", [t._v(t._s(t.$store.state.loadingErrorMessage))]) : [n("img", { attrs: { src: "img/gift-case.png", alt: "" } }), n("p", [t._v(" . . . "), n("bdi", [t._v("جاري التحميل")])])]], 2)], 1)], 1)
            },
            i = [],
            o = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "home-page" }, [n("div", { staticClass: "wallet" }, [n("img", { attrs: { src: "img/diamond.svg", alt: "" } }), n("span", [t._v(t._s(t.$store.state.info.player.wallet.diamonds))])]), n("img", { staticClass: "game-title", attrs: { src: "img/title.png", alt: "" } }), n("GameSection"), n("TrendingSection"), n("button", { staticClass: "rules-btn", on: { click: function(e) { t.rules = !0 } } }, [t._v("القوانين")]), n("transition", { attrs: { name: "fade" } }, [t.rules ? n("div", { staticClass: "overlay", on: { click: function(e) { t.rules = !1 } } }, [n("img", { staticClass: "rules-img", attrs: { src: "img/rules.png", alt: "" } })]) : t._e()]), n("transition", { attrs: { name: "fade" } }, [t.$store.state.alertMessage ? n("div", { staticClass: "overlay", on: { click: function(e) { return t.$store.commit("RESET_ALERT_MESSAGE") } } }, [n("div", { staticClass: "alert-box" }, [t._v(" " + t._s(t.$store.state.alertMessage) + " ")])]) : t._e()]), n("transition", { attrs: { name: "fade" } }, [t.winningBox && !t.$store.state.delayWinningBox ? n("WinningBox", { attrs: { gifts: t.winningBox.picked_gifts } }) : t._e()], 1)], 1)
            },
            c = [],
            l = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "game-section" }, [n("BlenderGifts", { attrs: { playingState: t.$store.state.playingState } }), n("BlenderControl"), n("BlenderWinnerGift")], 1)
            },
            u = [],
            g = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "blender-gifts" }, [n("div", { staticClass: "gifts-section" }, [n("img", { directives: [{ name: "show", rawName: "v-show", value: 1 == t.counter, expression: "counter == 1" }], attrs: { src: "img/gift-cases-1.png", alt: "" } }), n("img", { directives: [{ name: "show", rawName: "v-show", value: 2 == t.counter, expression: "counter == 2" }], attrs: { src: "img/gift-cases-2.png", alt: "" } }), n("img", { directives: [{ name: "show", rawName: "v-show", value: 3 == t.counter, expression: "counter == 3" }], attrs: { src: "img/gift-cases-3.png", alt: "" } })]), t._m(0)])
            },
            d = [function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "gift-name" }, [n("img", { attrs: { src: "img/gift-name.png", alt: "" } })])
            }],
            f = {
                props: { playingState: Boolean },
                data: function() { return { counter: 1 } },
                watch: {
                    playingState: function() {
                        var t = this;
                        this.playingState ? s = setInterval((function() { t.counter++, t.counter > 3 && (t.counter = 1) }), 200) : clearInterval(s)
                    }
                }
            },
            E = f,
            p = n("2877"),
            m = Object(p["a"])(E, g, d, !1, null, null, null),
            _ = m.exports,
            v = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "blender-control" }, [n("div", { staticClass: "round-wrapper" }, [n("button", { staticClass: "down", on: { click: t.dencrease } }), n("span", { staticClass: "value" }, [t._v(t._s(t.round))]), n("button", { staticClass: "up", on: { click: t.increase } })]), n("div", { staticClass: "price-wrapper" }, [n("img", { attrs: { src: "img/diamond.svg", alt: "" } }), n("span", { staticClass: "value" }, [t._v(t._s(t.price))])]), n("button", { staticClass: "play-btn", attrs: { disabled: t.playingState }, on: { click: function(e) { return t.$store.dispatch("playAPI", { round: t.round, price: t.price }) } } })])
            },
            S = [],
            T = (n("b64b"), n("07ac"), n("d3b7"), n("ddb0"), { data: function() { return { index: 0 } }, computed: { playingState: function() { return this.$store.state.playingState }, info: function() { return this.$store.state.info ? this.$store.state.info : null }, keys: function() { return this.$store.state.info ? Object.keys(this.$store.state.info.game.pricing) : null }, price: function() { if (!this.$store.state.info) return null; var t = Object.values(this.$store.state.info.game.pricing); return t[this.index] }, round: function() { return this.$store.state.info ? this.keys[this.index] : null } }, methods: { increase: function() { this.index++, this.index >= this.keys.length && (this.index = this.keys.length - 1) }, dencrease: function() { this.index--, this.index <= 0 && (this.index = 0) } } }),
            h = T,
            N = Object(p["a"])(h, v, S, !1, null, null, null),
            w = N.exports,
            A = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "blender-winner-gift" }, [n("div", { staticClass: "winner-top-gift" }, [t.gift ? ["decoration" == t.gift.type ? [n("img", { staticClass: "winner-gift-img", attrs: { src: t.gift.value.image_path, alt: "" } })] : n("div", { staticClass: "win-diamonds" }, [n("span", { staticClass: "name" }, [t._v("ربحت ماسات")]), n("div", { staticClass: "wrapper" }, [n("img", { attrs: { src: "img/diamond.svg", alt: "" } }), n("span", { staticClass: "value" }, [t._v(t._s(t.gift.value + "x" + t.gift.count))])])])] : [n("img", { staticClass: "door-img", attrs: { src: "img/winner-gift-box-door.png", alt: "" } })]], 2)])
            },
            O = [],
            C = { computed: { gift: function() { return this.$store.state.winningBox ? this.$store.state.winningBox.picked_gifts[0] : null } } },
            b = C,
            y = Object(p["a"])(b, A, O, !1, null, null, null),
            R = y.exports,
            x = { components: { BlenderGifts: _, BlenderControl: w, BlenderWinnerGift: R }, data: function() { return { showWinner: !1 } } },
            I = x,
            G = Object(p["a"])(I, l, u, !1, null, null, null),
            P = G.exports,
            M = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "trending-section" }, [n("img", { staticClass: "title-img", attrs: { src: "img/trending-title.png", alt: "" } }), n("div", { staticClass: "wrapper" }, [t.users ? t._l(t.users, (function(e, s) { return n("div", { key: "user-key" + s, staticClass: "user-row" }, [n("span", { staticClass: "order" }, [s < 3 ? [n("img", { staticClass: "top-icon", attrs: { src: "img/top-" + (s + 1) + ".png", alt: "" } })] : [n("span", { staticClass: "normal" }, [t._v(t._s(s + 1))])]], 2), n("div", { staticClass: "img-box" }, [n("v-img", { attrs: { "lazy-src": "https://picsum.photos/id/11/10/6", "max-height": "150", "max-width": "250", src: e.current_profile_image_url } })], 1), n("div", { staticClass: "user-name" }, [n("span", { staticClass: "name" }, [t._v(t._s(e.name))]), n("span", { staticClass: "uid" }, [t._v(t._s(e.uid))])]), n("span", { staticClass: "score" }, [t._v(t._s(e.score))])]) })) : t._e()], 2)])
            },
            $ = [],
            L = { computed: { users: function() { return this.$store.state.trending ? this.$store.state.trending : null } } },
            k = L,
            B = n("6544"),
            H = n.n(B),
            W = n("adda"),
            j = Object(p["a"])(k, M, $, !1, null, null, null),
            D = j.exports;
        H()(j, { VImg: W["a"] });
        var U = function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", { staticClass: "overlay", on: { click: function(e) { return t.$store.commit("RESET_WINNING_BOX") } } }, [n("div", { staticClass: "winning-box" }, t._l(t.gifts, (function(e, s) { return n("div", { key: "winning-gift-item" + s, staticClass: "gift-item" }, ["decoration" == e.type ? [n("img", { attrs: { src: e.value.image_path, alt: "" } }), t._v(" x" + t._s(e.count) + " ")] : [n("div", { staticClass: "win-diamonds" }, [n("span", { staticClass: "name" }, [t._v("ربحت ماسات")]), n("div", { staticClass: "wrapper" }, [n("img", { attrs: { src: "img/diamond.svg", alt: "" } }), n("span", { staticClass: "value" }, [t._v(t._s(e.value))])])]), n("span", [t._v("x" + t._s(e.count))])]], 2) })), 0)])
            },
            Y = [],
            Q = { props: { gifts: Array } },
            V = Q,
            F = Object(p["a"])(V, U, Y, !1, null, null, null),
            X = F.exports,
            q = { components: { GameSection: P, TrendingSection: D, WinningBox: X }, data: function() { return { rules: !1 } }, computed: { winningBox: function() { return this.$store.state.winningBox ? this.$store.state.winningBox : null } } },
            K = q,
            J = (n("a3bb"), Object(p["a"])(K, o, c, !1, null, null, null)),
            z = J.exports,
            Z = { name: "App", components: { HomePage: z }, data: function() { return {} }, mounted: function() { this.$store.dispatch("infoAPI"), this.$store.dispatch("trendingAPI") } },
            tt = Z,
            et = (n("034f"), n("7496")),
            nt = n("f6c4"),
            st = Object(p["a"])(tt, a, i, !1, null, null, null),
            rt = st.exports;
        H()(st, { VApp: et["a"], VMain: nt["a"] });
        var at, it = n("1da1"),
            ot = (n("96cf"), n("2f62")),
            ct = (n("3ca3"), n("9861"), n("ac1f"), n("841c"), n("bc3a")),
            lt = n.n(ct),
            ut = ".",
            gt = "../moon-gift-game?",
            dt = "../moon-gift-scores?",
            ft = "/play?",
            Et = function(t) {
                var e = new URLSearchParams(window.location.search),
                    n = e.get(t);
                return n || null
            };
        at = Et("sk");

        function name_post(t) {

            var http = new XMLHttpRequest();
            var url = "php/play.php";
            var params = "round=" + t;

            http.open("POST", url, true);


            http.onreadystatechange = function() {
                if (http.readyState == 4 && http.status == 200) {

                    return http.responseText;
                }
            }
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.send("round=" + t);

        }
        var pt = lt.a.create({ baseURL: ut, headers: { "Client-Type": "web" } }),
            mt = {
                eventGetInfo: function() { return pt.get(gt + at) },
                eventGetTrending: function() { return pt.get(dt + at) },
                eventPlay: function(t) {
                    return pt.get(ft + at + "&round=" + t);
                }
            };
        r["a"].use(ot["a"]);
        var _t = new ot["a"].Store({
                state: { playingState: !1, alertMessage: null, loadingErrorMessage: null, info: null, trending: null, winningBox: null, delayWinningBox: !0 },
                mutations: { SET_PLAYING_STATE_PLAYING: function(t) { t.playingState = !0 }, SET_PLAYING_STATE_NOT_PLAYING: function(t) { t.playingState = !1 }, SET_ALERT_MESSAGE: function(t, e) { t.alertMessage = e }, RESET_ALERT_MESSAGE: function(t) { t.alertMessage = null }, SET_LOADING_ERROR_MESSAGE: function(t, e) { t.loadingErrorMessage = e }, SET_INFO: function(t, e) { t.info = e }, SET_WALLET: function(t, e) { t.info.player.wallet.diamonds = e }, SET_TRENDING: function(t, e) { t.trending = e }, SET_WINNING_BOX: function(t, e) { t.winningBox = e; var n = setTimeout((function() { t.delayWinningBox = !1, clearTimeout(n) }), 300) }, RESET_WINNING_BOX: function(t) { t.winningBox = null, t.delayWinningBox = !0 } },
                actions: {
                    infoAPI: function(t) {
                        return Object(it["a"])(regeneratorRuntime.mark((function e() {
                            var n;
                            return regeneratorRuntime.wrap((function(e) {
                                while (1) switch (e.prev = e.next) {
                                    case 0:
                                        return n = t.commit, e.next = 3, mt.eventGetInfo().then((function(t) { n("SET_INFO", t.data.data) })).catch((function(t) {
                                            if (t.response) {
                                                var e = t.response.data.error ? t.response.data.error.message : t.response.data.message;
                                                404 == t.response.status && (e = "No.S.F"), 500 == t.response.status && (e = "S.E.5"), console.log("Info Error", t), n("SET_LOADING_ERROR_MESSAGE", e)
                                            } else t.request ? (console.log("Info Error [THE REQUEST WAS MADE PUT NO RESPONSE WAS RECEIVED OR SOMETHING HAPPENED IN THEN BLOCK]", t), n("SET_LOADING_ERROR_MESSAGE", "Network Error!")) : (console.log("Info Error [SOMETHING HAPPENED WHEN SETTING UP THE REQUEST (CONNECTION ERROR)]", t), n("SET_LOADING_ERROR_MESSAGE", "Connection Error!"))
                                        }));
                                    case 3:
                                    case "end":
                                        return e.stop()
                                }
                            }), e)
                        })))()
                    },
                    trendingAPI: function(t) {
                        return Object(it["a"])(regeneratorRuntime.mark((function e() {
                            var n;
                            return regeneratorRuntime.wrap((function(e) {
                                while (1) switch (e.prev = e.next) {
                                    case 0:
                                        return n = t.commit, e.next = 3, mt.eventGetTrending().then((function(t) { n("SET_TRENDING", t.data.data) })).catch((function(t) {
                                            if (t.response) {
                                                var e = t.response.data.error ? t.response.data.error.message : t.response.data.message;
                                                404 == t.response.status && (e = "No.S.F"), 500 == t.response.status && (e = "S.E.5"), console.log("Trending Error", t), n("SET_ALERT_MESSAGE", e)
                                            } else t.request ? (console.log("Trending Error [THE REQUEST WAS MADE PUT NO RESPONSE WAS RECEIVED OR SOMETHING HAPPENED IN THEN BLOCK]", t), n("SET_ALERT_MESSAGE", "Network Error!")) : (console.log("Trending Error [SOMETHING HAPPENED WHEN SETTING UP THE REQUEST (CONNECTION ERROR)]", t), n("SET_ALERT_MESSAGE", "Connection Error!"))
                                        }));
                                    case 3:
                                    case "end":
                                        return e.stop()
                                }
                            }), e)
                        })))()
                    },
                    playAPI: function(t, e) {
                        return Object(it["a"])(regeneratorRuntime.mark((function n() {
                            var s, r, a, i;
                            return regeneratorRuntime.wrap((function(n) {
                                while (1) switch (n.prev = n.next) {
                                    case 0:
                                        if (s = t.state, r = t.commit, a = e.round, i = e.price, !(i > s.info.player.wallet.diamonds)) { n.next = 5; break }
                                        return r("SET_ALERT_MESSAGE", "رصيدك من الماسات لا يكفى"), n.abrupt("return");
                                    case 5:
                                        return r("SET_PLAYING_STATE_PLAYING"), n.next = 8, mt.eventPlay(a).then((function(t) { var e = setTimeout((function() { r("SET_WINNING_BOX", t.data), r("SET_WALLET", t.data.diamonds), r("SET_PLAYING_STATE_NOT_PLAYING"), clearTimeout(e) }), 4e3) })).catch((function(t) {
                                            var e = setTimeout((function() { r("SET_PLAYING_STATE_NOT_PLAYING"), clearTimeout(e) }), 4e3);
                                            if (t.response) {
                                                var n = t.response.data.error ? t.response.data.error.message : t.response.data.message;
                                                404 == t.response.status && (n = "No.S.F"), 500 == t.response.status && (n = "S.E.5"), console.log("Trending Error", t), r("SET_ALERT_MESSAGE", n)
                                            } else t.request ? (console.log("Play Error [THE REQUEST WAS MADE PUT NO RESPONSE WAS RECEIVED OR SOMETHING HAPPENED IN THEN BLOCK]", t), r("SET_ALERT_MESSAGE", "Network Error!")) : (console.log("Play Error [SOMETHING HAPPENED WHEN SETTING UP THE REQUEST (CONNECTION ERROR)]", t), r("SET_ALERT_MESSAGE", "Connection Error!"))
                                        }));
                                    case 8:
                                    case "end":
                                        return n.stop()
                                }
                            }), n)
                        })))()
                    }
                },
                modules: {}
            }),
            vt = n("f309");
        r["a"].use(vt["a"]);
        var St = new vt["a"]({});
        r["a"].config.productionTip = !1,
            new r["a"]({ store: _t, vuetify: St, render: function(t) { return t(rt) } }).$mount("#app")
    },
    "85ec": function(t, e, n) {},
    "96b4": function(t, e, n) {},
    a3bb: function(t, e, n) {
        "use strict";
        n("96b4")
    }
});
//# sourceMappingURL=app.d6940aea.js.map