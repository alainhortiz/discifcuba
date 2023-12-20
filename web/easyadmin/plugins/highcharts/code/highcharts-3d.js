/*
 Highcharts JS v7.0.3 (2019-02-06)

 3D features for Highcharts JS

 @license: www.highcharts.com/license
*/
(function (z) {
    "object" === typeof module && module.exports ? (z["default"] = z, module.exports = z) : "function" === typeof define && define.amd ? define(function () {
        return z
    }) : z("undefined" !== typeof Highcharts ? Highcharts : void 0)
})(function (z) {
    (function (b) {
        var x = b.deg2rad, y = b.pick;
        b.perspective3D = function (b, t, u) {
            t = 0 < u && u < Number.POSITIVE_INFINITY ? u / (b.z + t.z + u) : 1;
            return {x: b.x * t, y: b.y * t}
        };
        b.perspective = function (r, t, u) {
            var q = t.options.chart.options3d, h = u ? t.inverted : !1, v = {
                    x: t.plotWidth / 2, y: t.plotHeight / 2, z: q.depth / 2, vd: y(q.depth,
                    1) * y(q.viewDistance, 0)
                }, w = t.scale3d || 1, g = x * q.beta * (h ? -1 : 1), q = x * q.alpha * (h ? -1 : 1), a = Math.cos(q),
                k = Math.cos(-g), n = Math.sin(q), l = Math.sin(-g);
            u || (v.x += t.plotLeft, v.y += t.plotTop);
            return r.map(function (g) {
                var c;
                c = (h ? g.y : g.x) - v.x;
                var e = (h ? g.x : g.y) - v.y;
                g = (g.z || 0) - v.z;
                c = {x: k * c - l * g, y: -n * l * c + a * e - k * n * g, z: a * l * c + n * e + a * k * g};
                e = b.perspective3D(c, v, v.vd);
                e.x = e.x * w + v.x;
                e.y = e.y * w + v.y;
                e.z = c.z * w + v.z;
                return {x: h ? e.y : e.x, y: h ? e.x : e.y, z: e.z}
            })
        };
        b.pointCameraDistance = function (b, t) {
            var u = t.options.chart.options3d, q = t.plotWidth /
                2;
            t = t.plotHeight / 2;
            u = y(u.depth, 1) * y(u.viewDistance, 0) + u.depth;
            return Math.sqrt(Math.pow(q - b.plotX, 2) + Math.pow(t - b.plotY, 2) + Math.pow(u - b.plotZ, 2))
        };
        b.shapeArea = function (b) {
            var t = 0, u, q;
            for (u = 0; u < b.length; u++) q = (u + 1) % b.length, t += b[u].x * b[q].y - b[q].x * b[u].y;
            return t / 2
        };
        b.shapeArea3d = function (r, t, u) {
            return b.shapeArea(b.perspective(r, t, u))
        }
    })(z);
    (function (b) {
        function x(a, e, d, b, B, A, l, g) {
            var f = [], F = A - B;
            return A > B && A - B > Math.PI / 2 + .0001 ? (f = f.concat(x(a, e, d, b, B, B + Math.PI / 2, l, g)), f = f.concat(x(a, e, d, b, B + Math.PI /
                2, A, l, g))) : A < B && B - A > Math.PI / 2 + .0001 ? (f = f.concat(x(a, e, d, b, B, B - Math.PI / 2, l, g)), f = f.concat(x(a, e, d, b, B - Math.PI / 2, A, l, g))) : ["C", a + d * Math.cos(B) - d * c * F * Math.sin(B) + l, e + b * Math.sin(B) + b * c * F * Math.cos(B) + g, a + d * Math.cos(A) + d * c * F * Math.sin(A) + l, e + b * Math.sin(A) - b * c * F * Math.cos(A) + g, a + d * Math.cos(A) + l, e + b * Math.sin(A) + g]
        }

        var y = Math.cos, r = Math.PI, t = Math.sin, u = b.animObject, q = b.charts, h = b.color, v = b.defined,
            w = b.deg2rad, g = b.extend, a = b.merge, k = b.perspective, n = b.pick, l = b.SVGElement,
            p = b.SVGRenderer, c, e, m;
        c = 4 * (Math.sqrt(2) -
            1) / 3 / (r / 2);
        p.prototype.toLinePath = function (a, e) {
            var d = [];
            a.forEach(function (a) {
                d.push("L", a.x, a.y)
            });
            a.length && (d[0] = "M", e && d.push("Z"));
            return d
        };
        p.prototype.toLineSegments = function (a) {
            var f = [], d = !0;
            a.forEach(function (a) {
                f.push(d ? "M" : "L", a.x, a.y);
                d = !d
            });
            return f
        };
        p.prototype.face3d = function (a) {
            var f = this, d = this.createElement("path");
            d.vertexes = [];
            d.insidePlotArea = !1;
            d.enabled = !0;
            d.attr = function (a) {
                if ("object" === typeof a && (v(a.enabled) || v(a.vertexes) || v(a.insidePlotArea))) {
                    this.enabled = n(a.enabled,
                        this.enabled);
                    this.vertexes = n(a.vertexes, this.vertexes);
                    this.insidePlotArea = n(a.insidePlotArea, this.insidePlotArea);
                    delete a.enabled;
                    delete a.vertexes;
                    delete a.insidePlotArea;
                    var d = k(this.vertexes, q[f.chartIndex], this.insidePlotArea), e = f.toLinePath(d, !0),
                        d = b.shapeArea(d), d = this.enabled && 0 < d ? "visible" : "hidden";
                    a.d = e;
                    a.visibility = d
                }
                return l.prototype.attr.apply(this, arguments)
            };
            d.animate = function (a) {
                if ("object" === typeof a && (v(a.enabled) || v(a.vertexes) || v(a.insidePlotArea))) {
                    this.enabled = n(a.enabled,
                        this.enabled);
                    this.vertexes = n(a.vertexes, this.vertexes);
                    this.insidePlotArea = n(a.insidePlotArea, this.insidePlotArea);
                    delete a.enabled;
                    delete a.vertexes;
                    delete a.insidePlotArea;
                    var d = k(this.vertexes, q[f.chartIndex], this.insidePlotArea), e = f.toLinePath(d, !0),
                        d = b.shapeArea(d), d = this.enabled && 0 < d ? "visible" : "hidden";
                    a.d = e;
                    this.attr("visibility", d)
                }
                return l.prototype.animate.apply(this, arguments)
            };
            return d.attr(a)
        };
        p.prototype.polyhedron = function (a) {
            var f = this, d = this.g(), e = d.destroy;
            this.styledMode || d.attr({"stroke-linejoin": "round"});
            d.faces = [];
            d.destroy = function () {
                for (var a = 0; a < d.faces.length; a++) d.faces[a].destroy();
                return e.call(this)
            };
            d.attr = function (a, e, c, b) {
                if ("object" === typeof a && v(a.faces)) {
                    for (; d.faces.length > a.faces.length;) d.faces.pop().destroy();
                    for (; d.faces.length < a.faces.length;) d.faces.push(f.face3d().add(d));
                    for (var A = 0; A < a.faces.length; A++) f.styledMode && delete a.faces[A].fill, d.faces[A].attr(a.faces[A], null, c, b);
                    delete a.faces
                }
                return l.prototype.attr.apply(this, arguments)
            };
            d.animate = function (a, e, c) {
                if (a && a.faces) {
                    for (; d.faces.length >
                           a.faces.length;) d.faces.pop().destroy();
                    for (; d.faces.length < a.faces.length;) d.faces.push(f.face3d().add(d));
                    for (var b = 0; b < a.faces.length; b++) d.faces[b].animate(a.faces[b], e, c);
                    delete a.faces
                }
                return l.prototype.animate.apply(this, arguments)
            };
            return d.attr(a)
        };
        e = {
            initArgs: function (a) {
                var f = this, d = f.renderer, e = d[f.pathType + "Path"](a), c = e.zIndexes;
                f.parts.forEach(function (a) {
                    f[a] = d.path(e[a]).attr({"class": "highcharts-3d-" + a, zIndex: c[a] || 0}).add(f)
                });
                f.attr({"stroke-linejoin": "round", zIndex: c.group});
                f.originalDestroy = f.destroy;
                f.destroy = f.destroyParts
            }, singleSetterForParts: function (a, e, d, c, l, A) {
                var f = {};
                c = [null, null, c || "attr", l, A];
                var g = d && d.zIndexes;
                d ? (b.objectEach(d, function (e, c) {
                    f[c] = {};
                    f[c][a] = e;
                    g && (f[c].zIndex = d.zIndexes[c] || 0)
                }), c[1] = f) : (f[a] = e, c[0] = f);
                return this.processParts.apply(this, c)
            }, processParts: function (a, e, d, c, l) {
                var f = this;
                f.parts.forEach(function (g) {
                    e && (a = b.pick(e[g], !1));
                    if (!1 !== a) f[g][d](a, c, l)
                });
                return f
            }, destroyParts: function () {
                this.processParts(null, null, "destroy");
                return this.originalDestroy()
            }
        };
        m = b.merge(e, {
            parts: ["front", "top", "side"], pathType: "cuboid", attr: function (a, e, d, c) {
                if ("string" === typeof a && "undefined" !== typeof e) {
                    var f = a;
                    a = {};
                    a[f] = e
                }
                return a.shapeArgs || v(a.x) ? this.singleSetterForParts("d", null, this.renderer[this.pathType + "Path"](a.shapeArgs || a)) : l.prototype.attr.call(this, a, void 0, d, c)
            }, animate: function (a, e, d) {
                v(a.x) && v(a.y) ? (a = this.renderer[this.pathType + "Path"](a), this.singleSetterForParts("d", null, a, "animate", e, d), this.attr({zIndex: a.zIndexes.group})) : a.opacity ? this.processParts(a,
                    null, "animate", e, d) : l.prototype.animate.call(this, a, e, d);
                return this
            }, fillSetter: function (a) {
                this.singleSetterForParts("fill", null, {
                    front: a,
                    top: h(a).brighten(.1).get(),
                    side: h(a).brighten(-.1).get()
                });
                this.color = this.fill = a;
                return this
            }, opacitySetter: function (a) {
                return this.singleSetterForParts("opacity", a)
            }
        });
        p.prototype.elements3d = {base: e, cuboid: m};
        p.prototype.element3d = function (a, e) {
            var d = this.g();
            b.extend(d, this.elements3d[a]);
            d.initArgs(e);
            return d
        };
        p.prototype.cuboid = function (a) {
            return this.element3d("cuboid",
                a)
        };
        b.SVGRenderer.prototype.cuboidPath = function (a) {
            function e(a) {
                return h[a]
            }

            var d = a.x, f = a.y, c = a.z, g = a.height, l = a.width, w = a.depth, n = q[this.chartIndex], m, p,
                u = n.options.chart.options3d.alpha, t = 0,
                h = [{x: d, y: f, z: c}, {x: d + l, y: f, z: c}, {x: d + l, y: f + g, z: c}, {
                    x: d,
                    y: f + g,
                    z: c
                }, {x: d, y: f + g, z: c + w}, {x: d + l, y: f + g, z: c + w}, {x: d + l, y: f, z: c + w}, {
                    x: d,
                    y: f,
                    z: c + w
                }], h = k(h, n, a.insidePlotArea);
            p = function (a, d) {
                var f = [[], -1];
                a = a.map(e);
                d = d.map(e);
                0 > b.shapeArea(a) ? f = [a, 0] : 0 > b.shapeArea(d) && (f = [d, 1]);
                return f
            };
            m = p([3, 2, 1, 0], [7, 6, 5, 4]);
            a =
                m[0];
            l = m[1];
            m = p([1, 6, 7, 0], [4, 5, 2, 3]);
            g = m[0];
            w = m[1];
            m = p([1, 2, 5, 6], [0, 7, 4, 3]);
            p = m[0];
            m = m[1];
            1 === m ? t += 1E4 * (1E3 - d) : m || (t += 1E4 * d);
            t += 10 * (!w || 0 <= u && 180 >= u || 360 > u && 357.5 < u ? n.plotHeight - f : 10 + f);
            1 === l ? t += 100 * c : l || (t += 100 * (1E3 - c));
            return {
                front: this.toLinePath(a, !0),
                top: this.toLinePath(g, !0),
                side: this.toLinePath(p, !0),
                zIndexes: {group: Math.round(t)},
                isFront: l,
                isTop: w
            }
        };
        b.SVGRenderer.prototype.arc3d = function (e) {
            function c(d) {
                var e = !1, f = {}, c;
                d = a(d);
                for (c in d) -1 !== k.indexOf(c) && (f[c] = d[c], delete d[c], e = !0);
                return e ?
                    f : !1
            }

            var d = this.g(), f = d.renderer, k = "x y r innerR start end".split(" ");
            e = a(e);
            e.alpha = (e.alpha || 0) * w;
            e.beta = (e.beta || 0) * w;
            d.top = f.path();
            d.side1 = f.path();
            d.side2 = f.path();
            d.inn = f.path();
            d.out = f.path();
            d.onAdd = function () {
                var a = d.parentGroup, e = d.attr("class");
                d.top.add(d);
                ["out", "inn", "side1", "side2"].forEach(function (c) {
                    d[c].attr({"class": e + " highcharts-3d-side"}).add(a)
                })
            };
            ["addClass", "removeClass"].forEach(function (a) {
                d[a] = function () {
                    var e = arguments;
                    ["top", "out", "inn", "side1", "side2"].forEach(function (c) {
                        d[c][a].apply(d[c],
                            e)
                    })
                }
            });
            d.setPaths = function (a) {
                var e = d.renderer.arc3dPath(a), c = 100 * e.zTop;
                d.attribs = a;
                d.top.attr({d: e.top, zIndex: e.zTop});
                d.inn.attr({d: e.inn, zIndex: e.zInn});
                d.out.attr({d: e.out, zIndex: e.zOut});
                d.side1.attr({d: e.side1, zIndex: e.zSide1});
                d.side2.attr({d: e.side2, zIndex: e.zSide2});
                d.zIndex = c;
                d.attr({zIndex: c});
                a.center && (d.top.setRadialReference(a.center), delete a.center)
            };
            d.setPaths(e);
            d.fillSetter = function (a) {
                var d = h(a).brighten(-.1).get();
                this.fill = a;
                this.side1.attr({fill: d});
                this.side2.attr({fill: d});
                this.inn.attr({fill: d});
                this.out.attr({fill: d});
                this.top.attr({fill: a});
                return this
            };
            ["opacity", "translateX", "translateY", "visibility"].forEach(function (a) {
                d[a + "Setter"] = function (a, e) {
                    d[e] = a;
                    ["out", "inn", "side1", "side2", "top"].forEach(function (c) {
                        d[c].attr(e, a)
                    })
                }
            });
            d.attr = function (a) {
                var e;
                "object" === typeof a && (e = c(a)) && (g(d.attribs, e), d.setPaths(d.attribs));
                return l.prototype.attr.apply(d, arguments)
            };
            d.animate = function (e, f, g) {
                var w, k = this.attribs, m, p = "data-" + Math.random().toString(26).substring(2,
                    9);
                delete e.center;
                delete e.z;
                delete e.depth;
                delete e.alpha;
                delete e.beta;
                m = u(n(f, this.renderer.globalAnimation));
                m.duration && (w = c(e), d[p] = 0, e[p] = 1, d[p + "Setter"] = b.noop, w && (m.step = function (d, e) {
                    function c(a) {
                        return k[a] + (n(w[a], k[a]) - k[a]) * e.pos
                    }

                    e.prop === p && e.elem.setPaths(a(k, {
                        x: c("x"),
                        y: c("y"),
                        r: c("r"),
                        innerR: c("innerR"),
                        start: c("start"),
                        end: c("end")
                    }))
                }), f = m);
                return l.prototype.animate.call(this, e, f, g)
            };
            d.destroy = function () {
                this.top.destroy();
                this.out.destroy();
                this.inn.destroy();
                this.side1.destroy();
                this.side2.destroy();
                l.prototype.destroy.call(this)
            };
            d.hide = function () {
                this.top.hide();
                this.out.hide();
                this.inn.hide();
                this.side1.hide();
                this.side2.hide()
            };
            d.show = function (a) {
                this.top.show(a);
                this.out.show(a);
                this.inn.show(a);
                this.side1.show(a);
                this.side2.show(a)
            };
            return d
        };
        p.prototype.arc3dPath = function (a) {
            function e(a) {
                a %= 2 * Math.PI;
                a > Math.PI && (a = 2 * Math.PI - a);
                return a
            }

            var d = a.x, c = a.y, f = a.start, b = a.end - .00001, g = a.r, l = a.innerR || 0, w = a.depth || 0,
                k = a.alpha, m = a.beta, n = Math.cos(f), p = Math.sin(f);
            a = Math.cos(b);
            var u = Math.sin(b), h = g * Math.cos(m), g = g * Math.cos(k), q = l * Math.cos(m), v = l * Math.cos(k),
                l = w * Math.sin(m), C = w * Math.sin(k), w = ["M", d + h * n, c + g * p],
                w = w.concat(x(d, c, h, g, f, b, 0, 0)), w = w.concat(["L", d + q * a, c + v * u]),
                w = w.concat(x(d, c, q, v, b, f, 0, 0)), w = w.concat(["Z"]), z = 0 < m ? Math.PI / 2 : 0,
                m = 0 < k ? 0 : Math.PI / 2, z = f > -z ? f : b > -z ? -z : f,
                D = b < r - m ? b : f < r - m ? r - m : b, E = 2 * r - m, k = ["M", d + h * y(z), c + g * t(z)],
                k = k.concat(x(d, c, h, g, z, D, 0, 0));
            b > E && f < E ? (k = k.concat(["L", d + h * y(D) + l, c + g * t(D) + C]), k = k.concat(x(d, c, h, g, D, E, l, C)), k = k.concat(["L", d + h * y(E), c + g * t(E)]),
                k = k.concat(x(d, c, h, g, E, b, 0, 0)), k = k.concat(["L", d + h * y(b) + l, c + g * t(b) + C]), k = k.concat(x(d, c, h, g, b, E, l, C)), k = k.concat(["L", d + h * y(E), c + g * t(E)]), k = k.concat(x(d, c, h, g, E, D, 0, 0))) : b > r - m && f < r - m && (k = k.concat(["L", d + h * Math.cos(D) + l, c + g * Math.sin(D) + C]), k = k.concat(x(d, c, h, g, D, b, l, C)), k = k.concat(["L", d + h * Math.cos(b), c + g * Math.sin(b)]), k = k.concat(x(d, c, h, g, b, D, 0, 0)));
            k = k.concat(["L", d + h * Math.cos(D) + l, c + g * Math.sin(D) + C]);
            k = k.concat(x(d, c, h, g, D, z, l, C));
            k = k.concat(["Z"]);
            m = ["M", d + q * n, c + v * p];
            m = m.concat(x(d, c, q, v, f, b, 0,
                0));
            m = m.concat(["L", d + q * Math.cos(b) + l, c + v * Math.sin(b) + C]);
            m = m.concat(x(d, c, q, v, b, f, l, C));
            m = m.concat(["Z"]);
            n = ["M", d + h * n, c + g * p, "L", d + h * n + l, c + g * p + C, "L", d + q * n + l, c + v * p + C, "L", d + q * n, c + v * p, "Z"];
            d = ["M", d + h * a, c + g * u, "L", d + h * a + l, c + g * u + C, "L", d + q * a + l, c + v * u + C, "L", d + q * a, c + v * u, "Z"];
            u = Math.atan2(C, -l);
            c = Math.abs(b + u);
            a = Math.abs(f + u);
            f = Math.abs((f + b) / 2 + u);
            c = e(c);
            a = e(a);
            f = e(f);
            f *= 1E5;
            b = 1E5 * a;
            c *= 1E5;
            return {
                top: w,
                zTop: 1E5 * Math.PI + 1,
                out: k,
                zOut: Math.max(f, b, c),
                inn: m,
                zInn: Math.max(f, b, c),
                side1: n,
                zSide1: .99 * c,
                side2: d,
                zSide2: .99 *
                    b
            }
        }
    })(z);
    (function (b) {
        function x(b, g) {
            var a = b.plotLeft, k = b.plotWidth + a, w = b.plotTop, l = b.plotHeight + w, p = a + b.plotWidth / 2,
                c = w + b.plotHeight / 2, e = Number.MAX_VALUE, m = -Number.MAX_VALUE, f = Number.MAX_VALUE,
                h = -Number.MAX_VALUE, d, q = 1;
            d = [{x: a, y: w, z: 0}, {x: a, y: w, z: g}];
            [0, 1].forEach(function (a) {
                d.push({x: k, y: d[a].y, z: d[a].z})
            });
            [0, 1, 2, 3].forEach(function (a) {
                d.push({x: d[a].x, y: l, z: d[a].z})
            });
            d = u(d, b, !1);
            d.forEach(function (a) {
                e = Math.min(e, a.x);
                m = Math.max(m, a.x);
                f = Math.min(f, a.y);
                h = Math.max(h, a.y)
            });
            a > e && (q = Math.min(q,
                1 - Math.abs((a + p) / (e + p)) % 1));
            k < m && (q = Math.min(q, (k - p) / (m - p)));
            w > f && (q = 0 > f ? Math.min(q, (w + c) / (-f + w + c)) : Math.min(q, 1 - (w + c) / (f + c) % 1));
            l < h && (q = Math.min(q, Math.abs((l - c) / (h - c))));
            return q
        }

        var y = b.addEvent, r = b.Chart, t = b.merge, u = b.perspective, q = b.pick, h = b.wrap;
        r.prototype.is3d = function () {
            return this.options.chart.options3d && this.options.chart.options3d.enabled
        };
        r.prototype.propsRequireDirtyBox.push("chart.options3d");
        r.prototype.propsRequireUpdateSeries.push("chart.options3d");
        y(r, "afterInit", function () {
            var b =
                this.options;
            this.is3d() && (b.series || []).forEach(function (g) {
                "scatter" === (g.type || b.chart.type || b.chart.defaultSeriesType) && (g.type = "scatter3d")
            })
        });
        y(r, "addSeries", function (b) {
            this.is3d() && "scatter" === b.options.type && (b.options.type = "scatter3d")
        });
        b.wrap(b.Chart.prototype, "isInsidePlot", function (b) {
            return this.is3d() || b.apply(this, [].slice.call(arguments, 1))
        });
        var v = b.getOptions();
        t(!0, v, {
            chart: {
                options3d: {
                    enabled: !1,
                    alpha: 0,
                    beta: 0,
                    depth: 100,
                    fitToPlot: !0,
                    viewDistance: 25,
                    axisLabelPosition: null,
                    frame: {
                        visible: "default",
                        size: 1, bottom: {}, top: {}, left: {}, right: {}, back: {}, front: {}
                    }
                }
            }
        });
        y(r, "afterGetContainer", function () {
            this.styledMode && (this.renderer.definition({
                tagName: "style",
                textContent: ".highcharts-3d-top{filter: url(#highcharts-brighter)}\n.highcharts-3d-side{filter: url(#highcharts-darker)}\n"
            }), [{name: "darker", slope: .6}, {name: "brighter", slope: 1.4}].forEach(function (b) {
                this.renderer.definition({
                    tagName: "filter", id: "highcharts-" + b.name, children: [{
                        tagName: "feComponentTransfer", children: [{
                            tagName: "feFuncR", type: "linear",
                            slope: b.slope
                        }, {tagName: "feFuncG", type: "linear", slope: b.slope}, {
                            tagName: "feFuncB",
                            type: "linear",
                            slope: b.slope
                        }]
                    }]
                })
            }, this))
        });
        h(r.prototype, "setClassName", function (b) {
            b.apply(this, [].slice.call(arguments, 1));
            this.is3d() && (this.container.className += " highcharts-3d-chart")
        });
        y(b.Chart, "afterSetChartSize", function () {
            var b = this.options.chart.options3d;
            if (this.is3d()) {
                var g = this.inverted, a = this.clipBox, k = this.margin;
                a[g ? "y" : "x"] = -(k[3] || 0);
                a[g ? "x" : "y"] = -(k[0] || 0);
                a[g ? "height" : "width"] = this.chartWidth +
                    (k[3] || 0) + (k[1] || 0);
                a[g ? "width" : "height"] = this.chartHeight + (k[0] || 0) + (k[2] || 0);
                this.scale3d = 1;
                !0 === b.fitToPlot && (this.scale3d = x(this, b.depth));
                this.frame3d = this.get3dFrame()
            }
        });
        y(r, "beforeRedraw", function () {
            this.is3d() && (this.isDirtyBox = !0)
        });
        y(r, "beforeRender", function () {
            this.is3d() && (this.frame3d = this.get3dFrame())
        });
        h(r.prototype, "renderSeries", function (b) {
            var g = this.series.length;
            if (this.is3d()) for (; g--;) b = this.series[g], b.translate(), b.render(); else b.call(this)
        });
        y(r, "afterDrawChartBox", function () {
            if (this.is3d()) {
                var w =
                        this.renderer, g = this.options.chart.options3d, a = this.get3dFrame(), k = this.plotLeft,
                    n = this.plotLeft + this.plotWidth, l = this.plotTop, p = this.plotTop + this.plotHeight,
                    g = g.depth, c = k - (a.left.visible ? a.left.size : 0),
                    e = n + (a.right.visible ? a.right.size : 0), m = l - (a.top.visible ? a.top.size : 0),
                    f = p + (a.bottom.visible ? a.bottom.size : 0), h = 0 - (a.front.visible ? a.front.size : 0),
                    d = g + (a.back.visible ? a.back.size : 0), q = this.hasRendered ? "animate" : "attr";
                this.frame3d = a;
                this.frameShapes || (this.frameShapes = {
                    bottom: w.polyhedron().add(),
                    top: w.polyhedron().add(),
                    left: w.polyhedron().add(),
                    right: w.polyhedron().add(),
                    back: w.polyhedron().add(),
                    front: w.polyhedron().add()
                });
                this.frameShapes.bottom[q]({
                    "class": "highcharts-3d-frame highcharts-3d-frame-bottom",
                    zIndex: a.bottom.frontFacing ? -1E3 : 1E3,
                    faces: [{
                        fill: b.color(a.bottom.color).brighten(.1).get(),
                        vertexes: [{x: c, y: f, z: h}, {x: e, y: f, z: h}, {x: e, y: f, z: d}, {x: c, y: f, z: d}],
                        enabled: a.bottom.visible
                    }, {
                        fill: b.color(a.bottom.color).brighten(.1).get(),
                        vertexes: [{x: k, y: p, z: g}, {x: n, y: p, z: g}, {x: n, y: p, z: 0}, {x: k, y: p, z: 0}],
                        enabled: a.bottom.visible
                    },
                        {
                            fill: b.color(a.bottom.color).brighten(-.1).get(),
                            vertexes: [{x: c, y: f, z: h}, {x: c, y: f, z: d}, {x: k, y: p, z: g}, {x: k, y: p, z: 0}],
                            enabled: a.bottom.visible && !a.left.visible
                        }, {
                            fill: b.color(a.bottom.color).brighten(-.1).get(),
                            vertexes: [{x: e, y: f, z: d}, {x: e, y: f, z: h}, {x: n, y: p, z: 0}, {x: n, y: p, z: g}],
                            enabled: a.bottom.visible && !a.right.visible
                        }, {
                            fill: b.color(a.bottom.color).get(),
                            vertexes: [{x: e, y: f, z: h}, {x: c, y: f, z: h}, {x: k, y: p, z: 0}, {x: n, y: p, z: 0}],
                            enabled: a.bottom.visible && !a.front.visible
                        }, {
                            fill: b.color(a.bottom.color).get(),
                            vertexes: [{x: c, y: f, z: d}, {x: e, y: f, z: d}, {x: n, y: p, z: g}, {x: k, y: p, z: g}],
                            enabled: a.bottom.visible && !a.back.visible
                        }]
                });
                this.frameShapes.top[q]({
                    "class": "highcharts-3d-frame highcharts-3d-frame-top",
                    zIndex: a.top.frontFacing ? -1E3 : 1E3,
                    faces: [{
                        fill: b.color(a.top.color).brighten(.1).get(),
                        vertexes: [{x: c, y: m, z: d}, {x: e, y: m, z: d}, {x: e, y: m, z: h}, {x: c, y: m, z: h}],
                        enabled: a.top.visible
                    }, {
                        fill: b.color(a.top.color).brighten(.1).get(),
                        vertexes: [{x: k, y: l, z: 0}, {x: n, y: l, z: 0}, {x: n, y: l, z: g}, {x: k, y: l, z: g}],
                        enabled: a.top.visible
                    },
                        {
                            fill: b.color(a.top.color).brighten(-.1).get(),
                            vertexes: [{x: c, y: m, z: d}, {x: c, y: m, z: h}, {x: k, y: l, z: 0}, {x: k, y: l, z: g}],
                            enabled: a.top.visible && !a.left.visible
                        }, {
                            fill: b.color(a.top.color).brighten(-.1).get(),
                            vertexes: [{x: e, y: m, z: h}, {x: e, y: m, z: d}, {x: n, y: l, z: g}, {x: n, y: l, z: 0}],
                            enabled: a.top.visible && !a.right.visible
                        }, {
                            fill: b.color(a.top.color).get(),
                            vertexes: [{x: c, y: m, z: h}, {x: e, y: m, z: h}, {x: n, y: l, z: 0}, {x: k, y: l, z: 0}],
                            enabled: a.top.visible && !a.front.visible
                        }, {
                            fill: b.color(a.top.color).get(),
                            vertexes: [{
                                x: e, y: m,
                                z: d
                            }, {x: c, y: m, z: d}, {x: k, y: l, z: g}, {x: n, y: l, z: g}],
                            enabled: a.top.visible && !a.back.visible
                        }]
                });
                this.frameShapes.left[q]({
                    "class": "highcharts-3d-frame highcharts-3d-frame-left",
                    zIndex: a.left.frontFacing ? -1E3 : 1E3,
                    faces: [{
                        fill: b.color(a.left.color).brighten(.1).get(),
                        vertexes: [{x: c, y: f, z: h}, {x: k, y: p, z: 0}, {x: k, y: p, z: g}, {x: c, y: f, z: d}],
                        enabled: a.left.visible && !a.bottom.visible
                    }, {
                        fill: b.color(a.left.color).brighten(.1).get(),
                        vertexes: [{x: c, y: m, z: d}, {x: k, y: l, z: g}, {x: k, y: l, z: 0}, {x: c, y: m, z: h}],
                        enabled: a.left.visible &&
                            !a.top.visible
                    }, {
                        fill: b.color(a.left.color).brighten(-.1).get(),
                        vertexes: [{x: c, y: f, z: d}, {x: c, y: m, z: d}, {x: c, y: m, z: h}, {x: c, y: f, z: h}],
                        enabled: a.left.visible
                    }, {
                        fill: b.color(a.left.color).brighten(-.1).get(),
                        vertexes: [{x: k, y: l, z: g}, {x: k, y: p, z: g}, {x: k, y: p, z: 0}, {x: k, y: l, z: 0}],
                        enabled: a.left.visible
                    }, {
                        fill: b.color(a.left.color).get(),
                        vertexes: [{x: c, y: f, z: h}, {x: c, y: m, z: h}, {x: k, y: l, z: 0}, {x: k, y: p, z: 0}],
                        enabled: a.left.visible && !a.front.visible
                    }, {
                        fill: b.color(a.left.color).get(), vertexes: [{x: c, y: m, z: d}, {
                            x: c, y: f,
                            z: d
                        }, {x: k, y: p, z: g}, {x: k, y: l, z: g}], enabled: a.left.visible && !a.back.visible
                    }]
                });
                this.frameShapes.right[q]({
                    "class": "highcharts-3d-frame highcharts-3d-frame-right",
                    zIndex: a.right.frontFacing ? -1E3 : 1E3,
                    faces: [{
                        fill: b.color(a.right.color).brighten(.1).get(),
                        vertexes: [{x: e, y: f, z: d}, {x: n, y: p, z: g}, {x: n, y: p, z: 0}, {x: e, y: f, z: h}],
                        enabled: a.right.visible && !a.bottom.visible
                    }, {
                        fill: b.color(a.right.color).brighten(.1).get(),
                        vertexes: [{x: e, y: m, z: h}, {x: n, y: l, z: 0}, {x: n, y: l, z: g}, {x: e, y: m, z: d}],
                        enabled: a.right.visible &&
                            !a.top.visible
                    }, {
                        fill: b.color(a.right.color).brighten(-.1).get(),
                        vertexes: [{x: n, y: l, z: 0}, {x: n, y: p, z: 0}, {x: n, y: p, z: g}, {x: n, y: l, z: g}],
                        enabled: a.right.visible
                    }, {
                        fill: b.color(a.right.color).brighten(-.1).get(),
                        vertexes: [{x: e, y: f, z: h}, {x: e, y: m, z: h}, {x: e, y: m, z: d}, {x: e, y: f, z: d}],
                        enabled: a.right.visible
                    }, {
                        fill: b.color(a.right.color).get(),
                        vertexes: [{x: e, y: m, z: h}, {x: e, y: f, z: h}, {x: n, y: p, z: 0}, {x: n, y: l, z: 0}],
                        enabled: a.right.visible && !a.front.visible
                    }, {
                        fill: b.color(a.right.color).get(),
                        vertexes: [{x: e, y: f, z: d},
                            {x: e, y: m, z: d}, {x: n, y: l, z: g}, {x: n, y: p, z: g}],
                        enabled: a.right.visible && !a.back.visible
                    }]
                });
                this.frameShapes.back[q]({
                    "class": "highcharts-3d-frame highcharts-3d-frame-back",
                    zIndex: a.back.frontFacing ? -1E3 : 1E3,
                    faces: [{
                        fill: b.color(a.back.color).brighten(.1).get(),
                        vertexes: [{x: e, y: f, z: d}, {x: c, y: f, z: d}, {x: k, y: p, z: g}, {x: n, y: p, z: g}],
                        enabled: a.back.visible && !a.bottom.visible
                    }, {
                        fill: b.color(a.back.color).brighten(.1).get(),
                        vertexes: [{x: c, y: m, z: d}, {x: e, y: m, z: d}, {x: n, y: l, z: g}, {x: k, y: l, z: g}],
                        enabled: a.back.visible &&
                            !a.top.visible
                    }, {
                        fill: b.color(a.back.color).brighten(-.1).get(),
                        vertexes: [{x: c, y: f, z: d}, {x: c, y: m, z: d}, {x: k, y: l, z: g}, {x: k, y: p, z: g}],
                        enabled: a.back.visible && !a.left.visible
                    }, {
                        fill: b.color(a.back.color).brighten(-.1).get(),
                        vertexes: [{x: e, y: m, z: d}, {x: e, y: f, z: d}, {x: n, y: p, z: g}, {x: n, y: l, z: g}],
                        enabled: a.back.visible && !a.right.visible
                    }, {
                        fill: b.color(a.back.color).get(),
                        vertexes: [{x: k, y: l, z: g}, {x: n, y: l, z: g}, {x: n, y: p, z: g}, {x: k, y: p, z: g}],
                        enabled: a.back.visible
                    }, {
                        fill: b.color(a.back.color).get(), vertexes: [{
                            x: c,
                            y: f, z: d
                        }, {x: e, y: f, z: d}, {x: e, y: m, z: d}, {x: c, y: m, z: d}], enabled: a.back.visible
                    }]
                });
                this.frameShapes.front[q]({
                    "class": "highcharts-3d-frame highcharts-3d-frame-front",
                    zIndex: a.front.frontFacing ? -1E3 : 1E3,
                    faces: [{
                        fill: b.color(a.front.color).brighten(.1).get(),
                        vertexes: [{x: c, y: f, z: h}, {x: e, y: f, z: h}, {x: n, y: p, z: 0}, {x: k, y: p, z: 0}],
                        enabled: a.front.visible && !a.bottom.visible
                    }, {
                        fill: b.color(a.front.color).brighten(.1).get(),
                        vertexes: [{x: e, y: m, z: h}, {x: c, y: m, z: h}, {x: k, y: l, z: 0}, {x: n, y: l, z: 0}],
                        enabled: a.front.visible &&
                            !a.top.visible
                    }, {
                        fill: b.color(a.front.color).brighten(-.1).get(),
                        vertexes: [{x: c, y: m, z: h}, {x: c, y: f, z: h}, {x: k, y: p, z: 0}, {x: k, y: l, z: 0}],
                        enabled: a.front.visible && !a.left.visible
                    }, {
                        fill: b.color(a.front.color).brighten(-.1).get(),
                        vertexes: [{x: e, y: f, z: h}, {x: e, y: m, z: h}, {x: n, y: l, z: 0}, {x: n, y: p, z: 0}],
                        enabled: a.front.visible && !a.right.visible
                    }, {
                        fill: b.color(a.front.color).get(),
                        vertexes: [{x: n, y: l, z: 0}, {x: k, y: l, z: 0}, {x: k, y: p, z: 0}, {x: n, y: p, z: 0}],
                        enabled: a.front.visible
                    }, {
                        fill: b.color(a.front.color).get(), vertexes: [{
                            x: e,
                            y: f, z: h
                        }, {x: c, y: f, z: h}, {x: c, y: m, z: h}, {x: e, y: m, z: h}], enabled: a.front.visible
                    }]
                })
            }
        });
        r.prototype.retrieveStacks = function (b) {
            var g = this.series, a = {}, k, h = 1;
            this.series.forEach(function (l) {
                k = q(l.options.stack, b ? 0 : g.length - 1 - l.index);
                a[k] ? a[k].series.push(l) : (a[k] = {series: [l], position: h}, h++)
            });
            a.totalStacks = h + 1;
            return a
        };
        r.prototype.get3dFrame = function () {
            var h = this, g = h.options.chart.options3d, a = g.frame, k = h.plotLeft, n = h.plotLeft + h.plotWidth,
                l = h.plotTop, p = h.plotTop + h.plotHeight, c = g.depth, e = function (a) {
                    a =
                        b.shapeArea3d(a, h);
                    return .5 < a ? 1 : -.5 > a ? -1 : 0
                }, m = e([{x: k, y: p, z: c}, {x: n, y: p, z: c}, {x: n, y: p, z: 0}, {x: k, y: p, z: 0}]),
                f = e([{x: k, y: l, z: 0}, {x: n, y: l, z: 0}, {x: n, y: l, z: c}, {x: k, y: l, z: c}]),
                t = e([{x: k, y: l, z: 0}, {x: k, y: l, z: c}, {x: k, y: p, z: c}, {x: k, y: p, z: 0}]),
                d = e([{x: n, y: l, z: c}, {x: n, y: l, z: 0}, {x: n, y: p, z: 0}, {x: n, y: p, z: c}]),
                v = e([{x: k, y: p, z: 0}, {x: n, y: p, z: 0}, {x: n, y: l, z: 0}, {x: k, y: l, z: 0}]),
                e = e([{x: k, y: l, z: c}, {x: n, y: l, z: c}, {x: n, y: p, z: c}, {x: k, y: p, z: c}]), r = !1, A = !1,
                x = !1, y = !1;
            [].concat(h.xAxis, h.yAxis, h.zAxis).forEach(function (a) {
                a && (a.horiz ?
                    a.opposite ? A = !0 : r = !0 : a.opposite ? y = !0 : x = !0)
            });
            var z = function (a, c, e) {
                for (var d = ["size", "color", "visible"], b = {}, f = 0; f < d.length; f++) for (var g = d[f], k = 0; k < a.length; k++) if ("object" === typeof a[k]) {
                    var l = a[k][g];
                    if (void 0 !== l && null !== l) {
                        b[g] = l;
                        break
                    }
                }
                a = e;
                !0 === b.visible || !1 === b.visible ? a = b.visible : "auto" === b.visible && (a = 0 < c);
                return {size: q(b.size, 1), color: q(b.color, "none"), frontFacing: 0 < c, visible: a}
            }, a = {
                bottom: z([a.bottom, a.top, a], m, r),
                top: z([a.top, a.bottom, a], f, A),
                left: z([a.left, a.right, a.side, a], t, x),
                right: z([a.right,
                    a.left, a.side, a], d, y),
                back: z([a.back, a.front, a], e, !0),
                front: z([a.front, a.back, a], v, !1)
            };
            "auto" === g.axisLabelPosition ? (d = function (a, c) {
                return a.visible !== c.visible || a.visible && c.visible && a.frontFacing !== c.frontFacing
            }, g = [], d(a.left, a.front) && g.push({
                y: (l + p) / 2,
                x: k,
                z: 0,
                xDir: {x: 1, y: 0, z: 0}
            }), d(a.left, a.back) && g.push({
                y: (l + p) / 2,
                x: k,
                z: c,
                xDir: {x: 0, y: 0, z: -1}
            }), d(a.right, a.front) && g.push({
                y: (l + p) / 2,
                x: n,
                z: 0,
                xDir: {x: 0, y: 0, z: 1}
            }), d(a.right, a.back) && g.push({
                y: (l + p) / 2,
                x: n,
                z: c,
                xDir: {x: -1, y: 0, z: 0}
            }), m = [], d(a.bottom,
                a.front) && m.push({
                x: (k + n) / 2,
                y: p,
                z: 0,
                xDir: {x: 1, y: 0, z: 0}
            }), d(a.bottom, a.back) && m.push({
                x: (k + n) / 2,
                y: p,
                z: c,
                xDir: {x: -1, y: 0, z: 0}
            }), f = [], d(a.top, a.front) && f.push({
                x: (k + n) / 2,
                y: l,
                z: 0,
                xDir: {x: 1, y: 0, z: 0}
            }), d(a.top, a.back) && f.push({
                x: (k + n) / 2,
                y: l,
                z: c,
                xDir: {x: -1, y: 0, z: 0}
            }), t = [], d(a.bottom, a.left) && t.push({
                z: (0 + c) / 2,
                y: p,
                x: k,
                xDir: {x: 0, y: 0, z: -1}
            }), d(a.bottom, a.right) && t.push({
                z: (0 + c) / 2,
                y: p,
                x: n,
                xDir: {x: 0, y: 0, z: 1}
            }), p = [], d(a.top, a.left) && p.push({
                z: (0 + c) / 2,
                y: l,
                x: k,
                xDir: {x: 0, y: 0, z: -1}
            }), d(a.top, a.right) && p.push({
                z: (0 +
                    c) / 2, y: l, x: n, xDir: {x: 0, y: 0, z: 1}
            }), k = function (a, c, e) {
                if (0 === a.length) return null;
                if (1 === a.length) return a[0];
                for (var d = 0, b = u(a, h, !1), f = 1; f < b.length; f++) e * b[f][c] > e * b[d][c] ? d = f : e * b[f][c] === e * b[d][c] && b[f].z < b[d].z && (d = f);
                return a[d]
            }, a.axes = {
                y: {left: k(g, "x", -1), right: k(g, "x", 1)},
                x: {top: k(f, "y", -1), bottom: k(m, "y", 1)},
                z: {top: k(p, "y", -1), bottom: k(t, "y", 1)}
            }) : a.axes = {
                y: {left: {x: k, z: 0, xDir: {x: 1, y: 0, z: 0}}, right: {x: n, z: 0, xDir: {x: 0, y: 0, z: 1}}},
                x: {
                    top: {y: l, z: 0, xDir: {x: 1, y: 0, z: 0}}, bottom: {
                        y: p, z: 0, xDir: {
                            x: 1, y: 0,
                            z: 0
                        }
                    }
                },
                z: {
                    top: {x: x ? n : k, y: l, xDir: x ? {x: 0, y: 0, z: 1} : {x: 0, y: 0, z: -1}},
                    bottom: {x: x ? n : k, y: p, xDir: x ? {x: 0, y: 0, z: 1} : {x: 0, y: 0, z: -1}}
                }
            };
            return a
        };
        b.Fx.prototype.matrixSetter = function () {
            var h;
            if (1 > this.pos && (b.isArray(this.start) || b.isArray(this.end))) {
                var g = this.start || [1, 0, 0, 1, 0, 0], a = this.end || [1, 0, 0, 1, 0, 0];
                h = [];
                for (var k = 0; 6 > k; k++) h.push(this.pos * a[k] + (1 - this.pos) * g[k])
            } else h = this.end;
            this.elem.attr(this.prop, h, null, !0)
        }
    })(z);
    (function (b) {
        function x(c, e, b) {
            if (!c.chart.is3d() || "colorAxis" === c.coll) return e;
            var f = c.chart, g = q * f.options.chart.options3d.alpha, d = q * f.options.chart.options3d.beta,
                l = a(b && c.options.title.position3d, c.options.labels.position3d);
            b = a(b && c.options.title.skew3d, c.options.labels.skew3d);
            var h = f.frame3d, m = f.plotLeft, n = f.plotWidth + m, p = f.plotTop, u = f.plotHeight + p, f = !1, t = 0,
                v = 0, r = {x: 0, y: 1, z: 0};
            e = c.swapZ({x: e.x, y: e.y, z: 0});
            if (c.isZAxis) if (c.opposite) {
                if (null === h.axes.z.top) return {};
                v = e.y - p;
                e.x = h.axes.z.top.x;
                e.y = h.axes.z.top.y;
                m = h.axes.z.top.xDir;
                f = !h.top.frontFacing
            } else {
                if (null === h.axes.z.bottom) return {};
                v = e.y - u;
                e.x = h.axes.z.bottom.x;
                e.y = h.axes.z.bottom.y;
                m = h.axes.z.bottom.xDir;
                f = !h.bottom.frontFacing
            } else if (c.horiz) if (c.opposite) {
                if (null === h.axes.x.top) return {};
                v = e.y - p;
                e.y = h.axes.x.top.y;
                e.z = h.axes.x.top.z;
                m = h.axes.x.top.xDir;
                f = !h.top.frontFacing
            } else {
                if (null === h.axes.x.bottom) return {};
                v = e.y - u;
                e.y = h.axes.x.bottom.y;
                e.z = h.axes.x.bottom.z;
                m = h.axes.x.bottom.xDir;
                f = !h.bottom.frontFacing
            } else if (c.opposite) {
                if (null === h.axes.y.right) return {};
                t = e.x - n;
                e.x = h.axes.y.right.x;
                e.z = h.axes.y.right.z;
                m = h.axes.y.right.xDir;
                m = {x: m.z, y: m.y, z: -m.x}
            } else {
                if (null === h.axes.y.left) return {};
                t = e.x - m;
                e.x = h.axes.y.left.x;
                e.z = h.axes.y.left.z;
                m = h.axes.y.left.xDir
            }
            "chart" !== l && ("flap" === l ? c.horiz ? (d = Math.sin(g), g = Math.cos(g), c.opposite && (d = -d), f && (d = -d), r = {
                x: m.z * d,
                y: g,
                z: -m.x * d
            }) : m = {
                x: Math.cos(d),
                y: 0,
                z: Math.sin(d)
            } : "ortho" === l ? c.horiz ? (r = Math.cos(g), l = Math.sin(d) * r, g = -Math.sin(g), d = -r * Math.cos(d), r = {
                x: m.y * d - m.z * g,
                y: m.z * l - m.x * d,
                z: m.x * g - m.y * l
            }, g = 1 / Math.sqrt(r.x * r.x + r.y * r.y + r.z * r.z), f && (g = -g), r = {
                x: g * r.x,
                y: g * r.y,
                z: g * r.z
            }) : m = {
                x: Math.cos(d),
                y: 0, z: Math.sin(d)
            } : c.horiz ? r = {
                x: Math.sin(d) * Math.sin(g),
                y: Math.cos(g),
                z: -Math.cos(d) * Math.sin(g)
            } : m = {x: Math.cos(d), y: 0, z: Math.sin(d)});
            e.x += t * m.x + v * r.x;
            e.y += t * m.y + v * r.y;
            e.z += t * m.z + v * r.z;
            f = w([e], c.chart)[0];
            b && (0 > k(w([e, {x: e.x + m.x, y: e.y + m.y, z: e.z + m.z}, {
                x: e.x + r.x,
                y: e.y + r.y,
                z: e.z + r.z
            }], c.chart)) && (m = {x: -m.x, y: -m.y, z: -m.z}), c = w([{x: e.x, y: e.y, z: e.z}, {
                x: e.x + m.x,
                y: e.y + m.y,
                z: e.z + m.z
            }, {
                x: e.x + r.x,
                y: e.y + r.y,
                z: e.z + r.z
            }], c.chart), f.matrix = [c[1].x - c[0].x, c[1].y - c[0].y, c[2].x - c[0].x, c[2].y - c[0].y, f.x, f.y], f.matrix[4] -=
                f.x * f.matrix[0] + f.y * f.matrix[2], f.matrix[5] -= f.x * f.matrix[1] + f.y * f.matrix[3]);
            return f
        }

        var y, r = b.addEvent, t = b.Axis, u = b.Chart, q = b.deg2rad, h = b.extend, v = b.merge, w = b.perspective,
            g = b.perspective3D, a = b.pick, k = b.shapeArea, n = b.splat, l = b.Tick, p = b.wrap;
        v(!0, t.prototype.defaultOptions, {
            labels: {position3d: "offset", skew3d: !1},
            title: {position3d: null, skew3d: null}
        });
        r(t, "afterSetOptions", function () {
            var c;
            this.chart.is3d && this.chart.is3d() && "colorAxis" !== this.coll && (c = this.options, c.tickWidth = a(c.tickWidth, 0), c.gridLineWidth =
                a(c.gridLineWidth, 1))
        });
        p(t.prototype, "getPlotLinePath", function (a) {
            var c = a.apply(this, [].slice.call(arguments, 1));
            if (!this.chart.is3d() || "colorAxis" === this.coll || null === c) return c;
            var b = this.chart, f = b.options.chart.options3d, f = this.isZAxis ? b.plotWidth : f.depth, b = b.frame3d,
                c = [this.swapZ({x: c[1], y: c[2], z: 0}), this.swapZ({x: c[1], y: c[2], z: f}), this.swapZ({
                    x: c[4],
                    y: c[5],
                    z: 0
                }), this.swapZ({x: c[4], y: c[5], z: f})], f = [];
            this.horiz ? (this.isZAxis ? (b.left.visible && f.push(c[0], c[2]), b.right.visible && f.push(c[1], c[3])) :
                (b.front.visible && f.push(c[0], c[2]), b.back.visible && f.push(c[1], c[3])), b.top.visible && f.push(c[0], c[1]), b.bottom.visible && f.push(c[2], c[3])) : (b.front.visible && f.push(c[0], c[2]), b.back.visible && f.push(c[1], c[3]), b.left.visible && f.push(c[0], c[1]), b.right.visible && f.push(c[2], c[3]));
            f = w(f, this.chart, !1);
            return this.chart.renderer.toLineSegments(f)
        });
        p(t.prototype, "getLinePath", function (a) {
            return this.chart.is3d() && "colorAxis" !== this.coll ? [] : a.apply(this, [].slice.call(arguments, 1))
        });
        p(t.prototype, "getPlotBandPath",
            function (a) {
                if (!this.chart.is3d() || "colorAxis" === this.coll) return a.apply(this, [].slice.call(arguments, 1));
                var c = arguments, b = c[2], f = [], c = this.getPlotLinePath(c[1]), b = this.getPlotLinePath(b);
                if (c && b) for (var g = 0; g < c.length; g += 6) f.push("M", c[g + 1], c[g + 2], "L", c[g + 4], c[g + 5], "L", b[g + 4], b[g + 5], "L", b[g + 1], b[g + 2], "Z");
                return f
            });
        p(l.prototype, "getMarkPath", function (a) {
            var c = a.apply(this, [].slice.call(arguments, 1)),
                c = [x(this.axis, {x: c[1], y: c[2], z: 0}), x(this.axis, {x: c[4], y: c[5], z: 0})];
            return this.axis.chart.renderer.toLineSegments(c)
        });
        r(l, "afterGetLabelPosition", function (a) {
            h(a.pos, x(this.axis, a.pos))
        });
        p(t.prototype, "getTitlePosition", function (a) {
            var c = a.apply(this, [].slice.call(arguments, 1));
            return x(this, c, !0)
        });
        r(t, "drawCrosshair", function (a) {
            this.chart.is3d() && "colorAxis" !== this.coll && a.point && (a.point.crosshairPos = this.isXAxis ? a.point.axisXpos : this.len - a.point.axisYpos)
        });
        r(t, "destroy", function () {
            ["backFrame", "bottomFrame", "sideFrame"].forEach(function (a) {
                this[a] && (this[a] = this[a].destroy())
            }, this)
        });
        t.prototype.swapZ = function (a,
                                      b) {
            return this.isZAxis ? (b = b ? 0 : this.chart.plotLeft, {x: b + a.z, y: a.y, z: a.x - b}) : a
        };
        y = b.ZAxis = function () {
            this.init.apply(this, arguments)
        };
        h(y.prototype, t.prototype);
        h(y.prototype, {
            isZAxis: !0, setOptions: function (a) {
                a = v({offset: 0, lineWidth: 0}, a);
                t.prototype.setOptions.call(this, a);
                this.coll = "zAxis"
            }, setAxisSize: function () {
                t.prototype.setAxisSize.call(this);
                this.width = this.len = this.chart.options.chart.options3d.depth;
                this.right = this.chart.chartWidth - this.width - this.left
            }, getSeriesExtremes: function () {
                var c =
                    this, b = c.chart;
                c.hasVisibleSeries = !1;
                c.dataMin = c.dataMax = c.ignoreMinPadding = c.ignoreMaxPadding = null;
                c.buildStacks && c.buildStacks();
                c.series.forEach(function (e) {
                    if (e.visible || !b.options.chart.ignoreHiddenSeries) c.hasVisibleSeries = !0, e = e.zData, e.length && (c.dataMin = Math.min(a(c.dataMin, e[0]), Math.min.apply(null, e)), c.dataMax = Math.max(a(c.dataMax, e[0]), Math.max.apply(null, e)))
                })
            }
        });
        r(u, "afterGetAxes", function () {
            var a = this, b = this.options, b = b.zAxis = n(b.zAxis || {});
            a.is3d() && (this.zAxis = [], b.forEach(function (c,
                                                              b) {
                c.index = b;
                c.isX = !0;
                (new y(a, c)).setScale()
            }))
        });
        p(t.prototype, "getSlotWidth", function (c, b) {
            if (this.chart.is3d() && b && b.label && this.categories && this.chart.frameShapes) {
                var e = this.chart, f = this.ticks, l = this.gridGroup.element.childNodes[0].getBBox(),
                    d = e.frameShapes.left.getBBox(), h = e.options.chart.options3d, e = {
                        x: e.plotWidth / 2,
                        y: e.plotHeight / 2,
                        z: h.depth / 2,
                        vd: a(h.depth, 1) * a(h.viewDistance, 0)
                    }, k, n, h = b.pos, p = f[h - 1], f = f[h + 1];
                0 !== h && p && p.label.xy && (k = g({x: p.label.xy.x, y: p.label.xy.y, z: null}, e, e.vd));
                f && f.label.xy &&
                (n = g({x: f.label.xy.x, y: f.label.xy.y, z: null}, e, e.vd));
                f = {x: b.label.xy.x, y: b.label.xy.y, z: null};
                f = g(f, e, e.vd);
                return Math.abs(k ? f.x - k.x : n ? n.x - f.x : l.x - d.x)
            }
            return c.apply(this, [].slice.call(arguments, 1))
        })
    })(z);
    (function (b) {
        var x = b.addEvent, y = b.perspective, r = b.pick;
        x(b.Series, "afterTranslate", function () {
            this.chart.is3d() && this.translate3dPoints()
        });
        b.Series.prototype.translate3dPoints = function () {
            var b = this.chart, u = r(this.zAxis, b.options.zAxis[0]), q = [], h, v, w;
            for (w = 0; w < this.data.length; w++) h = this.data[w],
                u && u.translate ? (v = u.isLog && u.val2lin ? u.val2lin(h.z) : h.z, h.plotZ = u.translate(v), h.isInside = h.isInside ? v >= u.min && v <= u.max : !1) : h.plotZ = 0, h.axisXpos = h.plotX, h.axisYpos = h.plotY, h.axisZpos = h.plotZ, q.push({
                x: h.plotX,
                y: h.plotY,
                z: h.plotZ
            });
            b = y(q, b, !0);
            for (w = 0; w < this.data.length; w++) h = this.data[w], u = b[w], h.plotX = u.x, h.plotY = u.y, h.plotZ = u.z
        }
    })(z);
    (function (b) {
        function x(b) {
            var g = b.apply(this, [].slice.call(arguments, 1));
            this.chart.is3d && this.chart.is3d() && (g.stroke = this.options.edgeColor || g.fill, g["stroke-width"] =
                t(this.options.edgeWidth, 1));
            return g
        }

        var y = b.addEvent, r = b.perspective, t = b.pick, u = b.Series, q = b.seriesTypes, h = b.svg, v = b.wrap;
        v(q.column.prototype, "translate", function (b) {
            b.apply(this, [].slice.call(arguments, 1));
            this.chart.is3d() && this.translate3dShapes()
        });
        v(b.Series.prototype, "alignDataLabel", function (b) {
            arguments[3].outside3dPlot = arguments[1].outside3dPlot;
            b.apply(this, [].slice.call(arguments, 1))
        });
        v(b.Series.prototype, "justifyDataLabel", function (b) {
            return arguments[2].outside3dPlot ? !1 : b.apply(this,
                [].slice.call(arguments, 1))
        });
        q.column.prototype.translate3dPoints = function () {
        };
        q.column.prototype.translate3dShapes = function () {
            var b = this, g = b.chart, a = b.options, h = a.depth || 25,
                n = (a.stacking ? a.stack || 0 : b.index) * (h + (a.groupZPadding || 1)),
                l = b.borderWidth % 2 ? .5 : 0;
            g.inverted && !b.yAxis.reversed && (l *= -1);
            !1 !== a.grouping && (n = 0);
            n += a.groupZPadding || 1;
            b.data.forEach(function (a) {
                a.outside3dPlot = null;
                if (null !== a.y) {
                    var c = a.shapeArgs, e = a.tooltipPos, k;
                    [["x", "width"], ["y", "height"]].forEach(function (e) {
                        k = c[e[0]] - l;
                        0 > k && (c[e[1]] += c[e[0]] + l, c[e[0]] = -l, k = 0);
                        k + c[e[1]] > b[e[0] + "Axis"].len && 0 !== c[e[1]] && (c[e[1]] = b[e[0] + "Axis"].len - c[e[0]]);
                        if (0 !== c[e[1]] && (c[e[0]] >= b[e[0] + "Axis"].len || c[e[0]] + c[e[1]] <= l)) {
                            for (var f in c) c[f] = 0;
                            a.outside3dPlot = !0
                        }
                    });
                    "rect" === a.shapeType && (a.shapeType = "cuboid");
                    c.z = n;
                    c.depth = h;
                    c.insidePlotArea = !0;
                    e = r([{x: e[0], y: e[1], z: n}], g, !0)[0];
                    a.tooltipPos = [e.x, e.y]
                }
            });
            b.z = n
        };
        v(q.column.prototype, "animate", function (b) {
            if (this.chart.is3d()) {
                var g = arguments[1], a = this.yAxis, k = this, n = this.yAxis.reversed;
                h && (g ? k.data.forEach(function (b) {
                    null !== b.y && (b.height = b.shapeArgs.height, b.shapey = b.shapeArgs.y, b.shapeArgs.height = 1, n || (b.shapeArgs.y = b.stackY ? b.plotY + a.translate(b.stackY) : b.plotY + (b.negative ? -b.height : b.height)))
                }) : (k.data.forEach(function (a) {
                    null !== a.y && (a.shapeArgs.height = a.height, a.shapeArgs.y = a.shapey, a.graphic && a.graphic.animate(a.shapeArgs, k.options.animation))
                }), this.drawDataLabels(), k.animate = null))
            } else b.apply(this, [].slice.call(arguments, 1))
        });
        v(q.column.prototype, "plotGroup", function (b,
                                                     g, a, h, n, l) {
            this.chart.is3d() && l && !this[g] && (this.chart.columnGroup || (this.chart.columnGroup = this.chart.renderer.g("columnGroup").add(l)), this[g] = this.chart.columnGroup, this.chart.columnGroup.attr(this.getPlotBox()), this[g].survive = !0);
            return b.apply(this, Array.prototype.slice.call(arguments, 1))
        });
        v(q.column.prototype, "setVisible", function (b, g) {
            var a = this, h;
            a.chart.is3d() && a.data.forEach(function (b) {
                h = (b.visible = b.options.visible = g = void 0 === g ? !b.visible : g) ? "visible" : "hidden";
                a.options.data[a.data.indexOf(b)] =
                    b.options;
                b.graphic && b.graphic.attr({visibility: h})
            });
            b.apply(this, Array.prototype.slice.call(arguments, 1))
        });
        q.column.prototype.handle3dGrouping = !0;
        y(u, "afterInit", function () {
            if (this.chart.is3d() && this.handle3dGrouping) {
                var b = this.options, g = b.grouping, a = b.stacking, h = t(this.yAxis.options.reversedStacks, !0),
                    n = 0;
                if (void 0 === g || g) {
                    g = this.chart.retrieveStacks(a);
                    n = b.stack || 0;
                    for (a = 0; a < g[n].series.length && g[n].series[a] !== this; a++) ;
                    n = 10 * (g.totalStacks - g[n].position) + (h ? a : -a);
                    this.xAxis.reversed || (n = 10 *
                        g.totalStacks - n)
                }
                b.zIndex = n
            }
        });
        v(q.column.prototype, "pointAttribs", x);
        q.columnrange && (v(q.columnrange.prototype, "pointAttribs", x), q.columnrange.prototype.plotGroup = q.column.prototype.plotGroup, q.columnrange.prototype.setVisible = q.column.prototype.setVisible);
        v(u.prototype, "alignDataLabel", function (b) {
            if (this.chart.is3d() && this instanceof q.column) {
                var g = arguments, a = g[4], g = g[1], h = {x: a.x, y: a.y, z: this.z}, h = r([h], this.chart, !0)[0];
                a.x = h.x;
                a.y = g.outside3dPlot ? -9E9 : h.y
            }
            b.apply(this, [].slice.call(arguments,
                1))
        });
        v(b.StackItem.prototype, "getStackBox", function (h, g) {
            var a = h.apply(this, [].slice.call(arguments, 1));
            if (g.is3d()) {
                var k = {x: a.x, y: a.y, z: 0}, k = b.perspective([k], g, !0)[0];
                a.x = k.x;
                a.y = k.y
            }
            return a
        })
    })(z);
    (function (b) {
        var x = b.deg2rad, y = b.pick, r = b.seriesTypes, t = b.svg;
        b = b.wrap;
        b(r.pie.prototype, "translate", function (b) {
            b.apply(this, [].slice.call(arguments, 1));
            if (this.chart.is3d()) {
                var q = this, h = q.options, u = h.depth || 0, r = q.chart.options.chart.options3d, g = r.alpha,
                    a = r.beta, k = h.stacking ? (h.stack || 0) * u : q._i *
                    u, k = k + u / 2;
                !1 !== h.grouping && (k = 0);
                q.data.forEach(function (b) {
                    var l = b.shapeArgs;
                    b.shapeType = "arc3d";
                    l.z = k;
                    l.depth = .75 * u;
                    l.alpha = g;
                    l.beta = a;
                    l.center = q.center;
                    l = (l.end + l.start) / 2;
                    b.slicedTranslation = {
                        translateX: Math.round(Math.cos(l) * h.slicedOffset * Math.cos(g * x)),
                        translateY: Math.round(Math.sin(l) * h.slicedOffset * Math.cos(g * x))
                    }
                })
            }
        });
        b(r.pie.prototype.pointClass.prototype, "haloPath", function (b) {
            var q = arguments;
            return this.series.chart.is3d() ? [] : b.call(this, q[1])
        });
        b(r.pie.prototype, "pointAttribs", function (b,
                                                     q, h) {
            b = b.call(this, q, h);
            h = this.options;
            this.chart.is3d() && !this.chart.styledMode && (b.stroke = h.edgeColor || q.color || this.color, b["stroke-width"] = y(h.edgeWidth, 1));
            return b
        });
        b(r.pie.prototype, "drawDataLabels", function (b) {
            if (this.chart.is3d()) {
                var q = this.chart.options.chart.options3d;
                this.data.forEach(function (b) {
                    var h = b.shapeArgs, r = h.r, g = (h.start + h.end) / 2;
                    b = b.labelPosition;
                    var a = b.connectorPosition, k = -r * (1 - Math.cos((h.alpha || q.alpha) * x)) * Math.sin(g),
                        n = r * (Math.cos((h.beta || q.beta) * x) - 1) * Math.cos(g);
                    [b.natural, a.breakAt, a.touchingSliceAt].forEach(function (a) {
                        a.x += n;
                        a.y += k
                    })
                })
            }
            b.apply(this, [].slice.call(arguments, 1))
        });
        b(r.pie.prototype, "addPoint", function (b) {
            b.apply(this, [].slice.call(arguments, 1));
            this.chart.is3d() && this.update(this.userOptions, !0)
        });
        b(r.pie.prototype, "animate", function (b) {
            if (this.chart.is3d()) {
                var q = arguments[1], h = this.options.animation, r = this.center, u = this.group, g = this.markerGroup;
                t && (!0 === h && (h = {}), q ? (u.oldtranslateX = u.translateX, u.oldtranslateY = u.translateY, q = {
                    translateX: r[0],
                    translateY: r[1], scaleX: .001, scaleY: .001
                }, u.attr(q), g && (g.attrSetters = u.attrSetters, g.attr(q))) : (q = {
                    translateX: u.oldtranslateX,
                    translateY: u.oldtranslateY,
                    scaleX: 1,
                    scaleY: 1
                }, u.animate(q, h), g && g.animate(q, h), this.animate = null))
            } else b.apply(this, [].slice.call(arguments, 1))
        })
    })(z);
    (function (b) {
        var x = b.Point, y = b.seriesType, r = b.seriesTypes;
        y("scatter3d", "scatter", {tooltip: {pointFormat: "x: \x3cb\x3e{point.x}\x3c/b\x3e\x3cbr/\x3ey: \x3cb\x3e{point.y}\x3c/b\x3e\x3cbr/\x3ez: \x3cb\x3e{point.z}\x3c/b\x3e\x3cbr/\x3e"}},
            {
                pointAttribs: function (t) {
                    var u = r.scatter.prototype.pointAttribs.apply(this, arguments);
                    this.chart.is3d() && t && (u.zIndex = b.pointCameraDistance(t, this.chart));
                    return u
                },
                axisTypes: ["xAxis", "yAxis", "zAxis"],
                pointArrayMap: ["x", "y", "z"],
                parallelArrays: ["x", "y", "z"],
                directTouch: !0
            }, {
                applyOptions: function () {
                    x.prototype.applyOptions.apply(this, arguments);
                    void 0 === this.z && (this.z = 0);
                    return this
                }
            })
    })(z);
    (function (b) {
        var x = b.addEvent, y = b.Axis, r = b.SVGRenderer, t = b.VMLRenderer;
        t && (b.setOptions({animate: !1}), t.prototype.face3d =
            r.prototype.face3d, t.prototype.polyhedron = r.prototype.polyhedron, t.prototype.elements3d = r.prototype.elements3d, t.prototype.element3d = r.prototype.element3d, t.prototype.cuboid = r.prototype.cuboid, t.prototype.cuboidPath = r.prototype.cuboidPath, t.prototype.toLinePath = r.prototype.toLinePath, t.prototype.toLineSegments = r.prototype.toLineSegments, t.prototype.arc3d = function (b) {
            b = r.prototype.arc3d.call(this, b);
            b.css({zIndex: b.zIndex});
            return b
        }, b.VMLRenderer.prototype.arc3dPath = b.SVGRenderer.prototype.arc3dPath,
            x(y, "render", function () {
                this.sideFrame && (this.sideFrame.css({zIndex: 0}), this.sideFrame.front.attr({fill: this.sideFrame.color}));
                this.bottomFrame && (this.bottomFrame.css({zIndex: 1}), this.bottomFrame.front.attr({fill: this.bottomFrame.color}));
                this.backFrame && (this.backFrame.css({zIndex: 0}), this.backFrame.front.attr({fill: this.backFrame.color}))
            }))
    })(z)
});
//# sourceMappingURL=highcharts-3d.js.map
