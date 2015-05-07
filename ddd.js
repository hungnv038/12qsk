var sf = {
    lng: null,
    linksListToken: "",
    jsonToken: "",
    requestTimer: 0,
    requestTimeout: 4E4,
    extension: {id: null, version: null},
    setLanguage: function (a) {
        sf.lng = a
    },
    getElement: function (a) {
        var b = document.getElementById(a);
        b || alert(sf.lng.error20.replace(/%s/g, a));
        return b
    },
    createElement: function (a, b, d, c) {
        a = document.createElement(a);
        if (!a)return null;
        var e = $(a);
        c && e.html(c);
        b && e.attr(b);
        d && e.css(d);
        return a
    },
    append: function (a, b, d, c, e) {
        return a && b ? a.appendChild(sf.createElement(b, d, c, e)) : null
    },
    showHideElement: function (a,
                               b, d, c) {
        a && (a = document.getElementById(a)) && ("none" == a.style.display ? ($(a).slideDown(function () {
            this.style.display = ""
        }), b && ("A" == b.tagName ? b.innerHTML = c : "INPUT" == b.tagName && (b.value = c))) : ($(a).slideUp(function () {
            this.style.display = "none"
        }), b && ("A" == b.tagName ? b.innerHTML = d : "INPUT" == b.tagName && (b.value = d))))
    },
    expandScrollBox: function (a, b, d, c) {
        if (a) {
            var e = $(a);
            if (e[0])if (e.hasClass("expanded")) {
                a = e.data("height");
                var f = e.height() - a;
                $("html, body").scrollTop($("html, body").scrollTop() - f);
                e.animate({
                    height: a +
                    "px"
                }, 500, function () {
                    e.removeClass("expanded");
                    b && $(b).html(d)
                })
            } else e.data("height", e.height()).animate({height: e[0].scrollHeight + "px"}, 500, function () {
                e.addClass("expanded");
                b && $(b).html(c)
            })
        }
    },
    openWindow: function (a, b) {
        var d = window.open("about:blank");
        d.document.write('<html><body><script type="text/javascript">location.href="' + a + '";\x3c/script></body></html>');
        b && b.call(null, d)
    },
    simplifyUrl: function (a) {
        return a.replace(/^(https?:\/\/)?(www\.)?/i, "")
    },
    pluralize: function (a, b) {
        if ("object" != typeof b)if (sf.lng[b])b =
            sf.lng[b]; else return b;
        a = Math.abs(a);
        if ("id" == sf.lng.lang)return b[0];
        if ("fr" == sf.lng.lang)return b[1 < a ? 1 : 0];
        if ("ru" == sf.lng.lang || "uk" == sf.lng.lang) {
            var d = [2, 0, 1, 1, 1, 2];
            return b[4 < a % 100 && 20 > a % 100 ? 2 : d[Math.min(a % 10, 5)]]
        }
        return b[1 == a ? 0 : 1]
    },
    sizeHuman: function (a, b) {
        if (void 0 == b || null == b)b = 2;
        var d = !1;
        0 > a && (d = !0, a = Math.abs(a));
        for (var c = "B KB MB GB TB PB".split(" "), e = 0; 1024 <= a && e < c.length - 1;)a /= 1024, ++e;
        d && (a *= -1);
        return [a.toFixed(b), c[e]]
    },
    sizeHumanToString: function (a) {
        if ("object" !== typeof a)return a.toString();
        var b = a[0];
        a[1] && (sf.lng[a[1]] && (a[1] = sf.lng[a[1]]), b = a[0] + " " + a[1]);
        return b
    },
    autoSubmitForm: function (a) {
        var b = document.getElementById(a);
        b && setTimeout(function () {
            b.click()
        }, 1E3)
    },
    blockExtensionUrl: function (a) {
        return !sf.extension.id || 1.72 <= sf.extension.version ? !1 : -1 < a.search(/^https?:\/\/([\w+\-]+\.)?(vk\.com|vkontakte\.ru)\//i) ? !0 : !1
    },
    checkForm: function () {
        var a = sf.getElement("sf_url");
        if (!a)return !1;
        var b = sf.getElement("sf_submit");
        if (!b)return !1;
        if (!a.value || $(a).hasClass("placeholder"))return alert(sf.lng.error1),
            !1;
        var d = $.trim(a.value);
        -1 == d.search(/\s/) && -1 == d.search(/^https?:\/\//i) && (d = "http://" + d);
        sf.blockExtensionUrl(d) ? $("#sf_form").attr("data-extension-disabled", 1) : $("#sf_form").removeAttr("data-extension-disabled");
        a.value = d;
        return sf.preSubmitForm(b, !0, !0)
    },
    createSubmitFrame: function (a) {
        sf.append(a, "iframe", {
            name: "sf_frame",
            id: "sf_frame",
            width: "0",
            height: "0",
            frameborder: "0",
            scrolling: "no"
        })
    },
    preSubmitForm: function (a, b, d, c) {
        clearTimeout(this.requestTimer);
        var e = sf.getElement("sf_result");
        if (!e)return !1;
        d && ($("#sf_multi_result").empty(), $("#sf_premium_result").empty());
        var f = sf.getElement("sf_indicator_box");
        if (!f)return !1;
        var h = !1;
        if (!document.getElementById("sf_indicator")) {
            c || $(e).empty();
            var h = !0, g = sf.createElement("span", {id: "sf_indicator"});
            $(f).empty().append(g).show()
        }
        c || sf.createSubmitFrame(e);
        a && (setTimeout(function () {
            a.disabled = !0
        }, 10), b && setTimeout(function () {
            a.disabled = !1
        }, 1E4));
        h && sf.busy();
        d && (this.requestTimer = setTimeout(function () {
                $("#sf_indicator_box").html(sf.lng.request_err1)
            },
            this.requestTimeout));
        return !0
    },
    finishRequest: function (a) {
        clearTimeout(this.requestTimer);
        a && this.removeBusyIndicator()
    },
    busy: function (a, b) {
        a || (a = "sf_indicator");
        var d = document.getElementById(a);
        if (d) {
            var c = document.createElement("img");
            b ? (c.src = "../img/busy.gif", c.style.margin = "2px 0") : c.src = "../img/busy.gif";
            c.alt = "...";
            $(d).empty();
            d.appendChild(c)
        }
    },
    removeBusyIndicator: function () {
        $("#sf_indicator_box").empty().css("display", "none")
    },
    enableElement: function (a, b) {
        $("#" + a).attr("disabled", !b)
    },
    checkCode: function () {
        var a =
            sf.getElement("sf_imgcode");
        if (!a)return !1;
        if (!a.value)return alert(sf.lng.error3), !1;
        (a = sf.getElement("sf_result")) && sf.createSubmitFrame(a);
        return !0
    },
    wait: function (a, b, d) {
        var c = $("#" + b);
        if (c[0]) {
            var e = function () {
                0 >= a ? (c.empty().attr("disabled", !1), d && d()) : (c.html(a), a--, setTimeout(e, 1E3, a))
            };
            e(a)
        }
    },
    waitSeconds: function (a, b, d) {
        var c = $("#" + b);
        if (c[0]) {
            var e = function () {
                0 >= a ? (c.empty().attr("disabled", !1), d && d()) : (c.html(a + " " + sf.pluralize(a, "pluralSeconds")), a--, setTimeout(e, 1E3, a))
            };
            e(a)
        }
    },
    parseQuery: function (a) {
        a ||
        (a = window.location.search);
        if (!a)return null;
        a = a.replace(/^[#\?]/, "");
        for (var b = [], d = /(?:^|&)([^=]+)(?:=([^&]*))?/g, c = ""; c = d.exec(a);)c[1] && c[2] ? b[c[1]] = c[2] : c[1] && (b[c[1]] = "");
        return b
    },
    processLink: function (a) {
        var b = sf.getElement("sf_url");
        if (b) {
            var d = sf.getElement("sf_submit");
            d && (b.value = a, $(d.form).submit())
        }
    },
    showModal: function (a, b, d, c) {
        b && "object" == typeof b || (b = {OK: !0});
        var e = {minWidth: 240, maxWidth: 640, persistent: !0, show: "show"};
        c && "object" == typeof c && $.extend(e, c);
        e.buttons = b;
        d && (e.callback =
            d);
        $.prompt(a, e)
    },
    showDebugInfo: function (a) {
        var b = $("#debug_info");
        b[0] ? b.append("<p>" + a + "</p>") : $("footer").after('<div id="debug_info" class="wrapper"><p>' + a + "</p></div>")
    },
    modifyTitle: function (a) {
        return a ? a.replace(/(\\u0027|\\u0022|\")/ig, "'").replace(/\</g, "&lt;").replace(/\\u0028/ig, "(").replace(/\\u0029/ig, ")") : a
    },
    getTitle: function (a, b, d, c, e) {
        b = b ? b : 80;
        a.length < b && (b = a.length);
        16 > b && (b = 16);
        void 0 === c && (c = !0);
        void 0 === e && (e = !0);
        return '<div><input type="text" class="' + (d ? d : "file-name") + '"' + (c ?
                " readonly" : "") + ' value="' + (e ? sf.modifyTitle(a) : a) + '" size="' + b + '"></div>'
    },
    cookie: {
        set: function (a, b, d, c) {
            a = encodeURIComponent(a);
            b = b ? encodeURIComponent(b) : "";
            c = c ? c : "";
            if (d && "number" == typeof d) {
                var e = d, f = d = new Date;
                f.setDate(f.getDate() + e)
            } else d = new Date;
            document.cookie = a + "=" + b + "; domain=" + c + "; path=/; expires=" + d.toUTCString()
        }, get: function (a) {
            a += "=";
            var b = document.cookie.indexOf(a);
            if (-1 == b)return null;
            var d = document.cookie.indexOf(";", b + a.length);
            -1 == d && (d = document.cookie.length);
            return unescape(document.cookie.substring(b +
            a.length, d))
        }, del: function (a, b) {
            document.cookie = a + "=; domain=" + (b ? b : "") + "; path=/; expires=" + (new Date((new Date).getTime() - 1E11)).toGMTString()
        }
    },
    result: {
        createBox: function (a, b) {
            var d = $(a ? "#sf_multi_result" : "#sf_result");
            if (!d[0])return null;
            var c = sf.createElement("div", {"class": b || "media-result"});
            d.append(c);
            return c
        }, appendHeader: function (a, b) {
            b = b || {
                columns: [{cls: "info-col", html: sf.lng.tblHdrInformation}, {
                    cls: "link-col",
                    html: sf.lng.tblHdrDownload
                }]
            };
            $(a).append($("#tplResultHeader").render(b))
        },
        appendLink: function (a, b, d, c, e, f) {
            if (!a.name && !a.text_url)return null;
            d = sf.append(d, "div", {"class": "link"});
            a.attr || (a.attr = {});
            a.url && c && !a.attr.download && !a.no_download && -1 == a.url.search(/savefrom\.net\//i) && (a.attr.download = a.ext ? sf.modifyTitle(c) + "." + a.ext : sf.modifyTitle(c));
            var h = a.name;
            if (a.name && (b = sf.append(d, a.url ? "a" : "span", a.attr, null, a.name), a.url && (-1 < a.url.indexOf(sf.linksListToken) ? (h = "", b.href = "#", $(b).click(function (b) {
                    b.preventDefault();
                    b.stopPropagation();
                    sf.processLink(a.url)
                })) :
                    (b.href = a.url, $(b).addClass("link-download"))), a.subname && (b.appendChild(document.createTextNode(" ")), sf.append(b, "span", {"class": "subname"}, null, a.subname), h && (h += " " + a.subname)), f && $(b).addClass("ga_track_events").attr("data-ga-event", "send;event;result;click;" + f), a.bind && "object" == typeof a.bind))for (var g in a.bind)a.bind[g].fn && $(b).bind(g, function (b) {
                !1 !== a.bind[g].preventDefault && b.preventDefault();
                !1 !== a.bind[g].stopPropagation && b.stopPropagation();
                (new Function(a.bind[g].fn))()
            });
            a.text_url &&
            (g = sf.append(d, "div", {"class": "text-url-box"}), g = sf.append(g, "input", {
                type: "text",
                "class": "file-name",
                readonly: 1,
                value: a.text_url
            }), a.attr && $(g).attr(a.attr), $(g).click(function () {
                this.value && this.select && this.select()
            }));
            h && "" !== a.info_url && null !== a.info_url ? (a.info_url || (a.info_url = a.url), a.type || (a.type = a.name), f = sf.createElement("i", {
                "class": "file-info-btn tooltip",
                title: sf.lng.fileInfoTitle
            }), $(f).click(function () {
                sf.fileInfo.get(a.info_url, a.type, h, this, a.info_token, e, a.info_header)
            }), d.insertBefore(f,
                d.firstChild)) : $(d).addClass("no-file-info");
            return d
        }, appendAction: function (a, b, d, c) {
            if (!a.name || !a.attr && !a.bind)return null;
            b = sf.append(b, "div", {"class": "link"});
            a.attr ? a.attr.href || (a.attr.href = "#") : a.attr = null;
            var e = a.name, f = sf.append(b, "a", a.attr, null, a.name), h = $(f);
            h.addClass("btn");
            h.attr("data-copy") && (h.addClass("link-download"), c && h.addClass("ga_track_events").attr("data-ga-event", "send;event;result;click;" + c));
            a.subname && (f.appendChild(document.createTextNode(" ")), sf.append(f, "span",
                {"class": "subname"}, null, a.subname), e += " " + a.subname);
            if (a.bind && "object" == typeof a.bind)for (var g in a.bind)a.bind[g].fn && h.bind(g, function (b) {
                !1 !== a.bind[g].preventDefault && b.preventDefault();
                !1 !== a.bind[g].stopPropagation && b.stopPropagation();
                (new Function(a.bind[g].fn)).call(this)
            });
            a.info_url ? (a.type || (a.type = a.name), c = sf.createElement("i", {
                "class": "file-info-btn",
                title: sf.lng.fileInfoTitle
            }), $(c).click(function () {
                sf.fileInfo.get(a.info_url, a.type, e, this, a.info_token, d, a.info_header)
            }), b.insertBefore(c,
                b.firstChild)) : a.info_data && "object" == typeof a.info_data ? (c = sf.createElement("i", {
                "class": "file-info-btn",
                title: a.info_data.title ? a.info_data.title : ""
            }), a.info_data.click && $(c).click(function (b) {
                b.preventDefault();
                b.stopPropagation();
                (new Function(a.info_data.click)).call(this)
            }), $(b).addClass("qm").prepend(c)) : $(b).addClass("no-file-info");
            return b
        }, appendAllLinks: function (a, b, d, c, e) {
            if (a && "object" == typeof a && 0 != a.length && d) {
                for (var f = 0; f < a.length; f++)(a[f].name || a[f].text_url) && sf.result.appendLink(a[f],
                    f, d, c, !1, e);
                if (b)for (f = 0; f < b.length; f++)sf.result.appendAction(b[f], d, !1, e)
            }
        }, appendMetaInfo: function (a, b, d) {
            if (a && "object" == typeof a && b) {
                var c = {cls: d ? d : "", meta: []}, e = $("tbody", b)[0];
                e || (e = sf.append(b, "table"), e = sf.append(e, "tbody"));
                for (var f = "link title size duration source hosting".split(" "), h = 0; h < f.length; h++)if (k = f[h], a[k]) {
                    var g = {name: sf.lng[k] ? sf.lng[k] : k, html: ""};
                    if ("title" == k || "link" == k) {
                        var l = "title" == k;
                        if ("object" == typeof a[k])if (a[k].length)for (var m = !1, n = 0; n < a[k].length; n++)a[k][n] &&
                        (m && (g.html += "<br>"), g.html += sf.getTitle(a[k][n], 60, "file-name", !0, l), m = !0); else for (n in a[k])a[k][n] && (m && (g.html += "<br>"), g.html += n + "<br>", g.html += sf.getTitle(a[k][n], 60, "file-name", !0, l), m = !0); else g.html = sf.getTitle(a[k], 60, "file-name", !0, l)
                    } else g.html = "source" == k ? a[k].name ? a[k].url ? '<a href="' + a[k].url + '" target="_blank">' + a[k].name + "</a>" : "<span>" + a[k].name + "</span>" : '<a href="' + a[k] + '" target="_blank">' + sf.simplifyUrl(a[k]) + "</a>" : "hosting" == k ? a[k].url && a[k].name ? '<a href="' + a[k].url + '" target="_blank">' +
                    a[k].name + "</a>" : a[k] : a[k];
                    c.meta.push(g);
                    delete a[k]
                }
                if ("media" == d)for (var k in a)a[k] && c.meta.push({
                    name: sf.lng[k] ? sf.lng[k] : k,
                    html: a[k]
                }), delete a[k];
                c.meta && ($(e).append($("#tplResultMeta").render(c)), c.meta = []);
                for (k in a)c.meta.push({html: a[k]});
                c.meta && $(b).append($("#tplResultMeta").render(c))
            }
        }, showHiddenLinks: function (a) {
            $box = $(a).closest(".link-box");
            $box.hasClass("show-all") ? $box.children(".link-group.hidden").children(".link").slideUp(function () {
                $box.removeClass("show-all")
            }) : $box.children(".link-group.hidden").children(".link").slideDown(function () {
                $box.addClass("show-all")
            })
        },
        ajax: function (a, b, d, c, e, f, h) {
            "string" == typeof f && (f = $(f)[0]);
            if (!a || !b)return $(f).replaceWith(sf.lng.linkNotFound), !1;
            var g = $(f).closest(".result-box"), l = "busy_box_" + (new Date).getTime() + "_" + Math.round(1E4 * Math.random()), m = f.parentNode;
            $(m).empty();
            var m = sf.append(m, "span", {
                id: l,
                "class": "no-file-info"
            }, {display: "block"}), n = $(m);
            sf.busy(l);
            var k = setTimeout(function () {
                n.html(sf.lng.linkNotFound)
            }, sf.multi.timeout);
            $.post("/savefrom.php", {sf_url: a, sf_token: b, sf_ts: d, sf_ajax: 1}, function (a) {
                clearTimeout(k);
                h && h(m);
                if (a) {
                    var b = a.indexOf(sf.jsonToken);
                    -1 != b && (a = a.substring(b + sf.jsonToken.length), b = a.indexOf(sf.jsonToken), 0 < b && (a = a.substring(0, b)));
                    if (sf.result.setAjaxResult(a, c, e, n, g))return
                }
                n.html(sf.lng.linkNotFound)
            })
        }, setAjaxResult: function (a, b, d, c, e) {
            if ("string" == typeof a)try {
                a = $.parseJSON(a)
            } catch (f) {
                return !1
            }
            if (a && "object" == typeof a) {
                if (a.response && a.message)return $(c).html(a.message), !0;
                c = null;
                b ? (a.length && sf.videoResult.showRows(a), c = sf.videoResult.show(a, d, !1, !0)) : (a.length && sf.audioResult.showRows(a),
                    c = sf.audioResult.show(a, d, !1, !0));
                if (c && e)return $(e).replaceWith(c), !0
            }
            return !1
        }, replaceAjaxResult: function (a, b, d, c) {
            if (c) {
                d && !$(c).closest("#sf_result_multi")[0] && (d = !1);
                var e = $(c).closest(".result-box");
                if (!e)return $(c).replaceWith(sf.lng.linkNotFound), !1;
                c = $(c.parentNode);
                sf.result.setAjaxResult(a, b, d, c, e) || c.html(sf.lng.linkNotFound)
            }
        }, handleCopyLinks: function (a) {
            "undefined" === typeof sfApp ? $("*[data-copy]", a).not(".zcopy").each(function () {
                $(this).addClass("zcopy")
            }) : sf.sfApp = !0
        }, appendMoreBtn: function (a,
                                    b, d) {
            if (!d || !d.url || !d.id)return null;
            var c = b ? "#sf_multi_result" : "#sf_result", e = sf.createElement("div", {"class": "link-plus"}), f = sf.append(e, "a", {
                href: "#",
                id: d.id,
                "class": "action"
            }, null, sf.lng.multiMore);
            $(f).click(function (c) {
                sf.result.ajax(d.url, d.token, d.ts, a, b, f, function (a) {
                    a.parentNode.removeChild(a)
                });
                return !1
            });
            $(c + " .media-result").append(e);
            return f
        }, execFunctions: function (a, b) {
            if (a && a.length)for (var d = 0; d < a.length; d++)(new Function(a[d])).call(window, b)
        }, sendStats: function (a, b, d) {
            window.ga &&
            (d && (a += " (m)"), window.ga("send", "event", "result", b ? "success" : "fail", a))
        }
    },
    videoResult: {
        showRows: function (a, b) {
            if (a && "object" == typeof a && 0 != a.length) {
                var d = a[a.length - 1];
                d.more ? (d = d.more, a.pop()) : d = null;
                for (var c = 0; c < a.length; c++)b && !a[c].hosting && (a[c].hosting = b), this.show(a[c], !1, !1);
                d && d.url && d.id && sf.result.appendMoreBtn(!0, !1, d)
            }
        }, show: function (a, b, d, c) {
            if (!a || "object" != typeof a)return null;
            void 0 === d && (d = !0);
            var e = b ? $("#sf_multi_result .media-result") : $("#sf_result .media-result");
            e[0] ? e = e[0] :
                (e = sf.result.createBox(b), sf.result.appendHeader(e));
            if (!e)return null;
            b = $($("#tplVideoBox").render({
                thumb: a.thumb ? a.thumb : "",
                player: a.sd ? !0 : !1,
                playerId: "video_player_" + (new Date).getTime() + "_" + Math.round(1E4 * Math.random()),
                extra: a.extra ? a.extra : ""
            }));
            this.appendLinks(a.url, a.action, a.meta && a.meta.title ? a.meta.title : "", $(".link-box", b)[0], a.hosting);
            sf.result.appendMetaInfo(a.meta, $(".meta", b)[0], "");
            a.thumb && $(".thumb-box img", b).load(function () {
                var a = $(this), b = a.height();
                if (b) {
                    var c = a.closest(".clip").height();
                    c || (c = 90);
                    a.css("top", Math.floor((c - b) / 2).toString() + "px")
                }
            });
            a.sd && !sf.sfApp && $(".thumb-box .clip", b).click(function () {
                var b = $(this).data("player-id");
                sf.videoPlayer.toggle(a.sd, a.hd, "", b, this);
                return !1
            });
            sf.result.execFunctions(a.fn, a);
            if (c)return b[0];
            d ? (b.css("display", "none"), b.appendTo(e), b.slideDown()) : b.appendTo(e);
            sf.result.handleCopyLinks(b);
            return b[0]
        }, appendLinks: function (a, b, d, c, e) {
            if (a && "object" == typeof a && 0 != a.length && c) {
                var f = this.groupLinks(a), h = !1, g;
                for (g in f) {
                    var l = sf.append(c,
                        "div", {"class": "link-group"});
                    f[g].visible || (h = !0, $(l).addClass("hidden"));
                    for (var m = 0; m < f[g].index.length; m++)sf.result.appendLink(a[f[g].index[m]], m, l, d, !0, e);
                    sf.append(l, "div", {"class": "clearfix"})
                }
                h && $(c).append('<div class="link-group clearfix more-links"><div class="link no-file-info"><a href="#" class="action">' + sf.lng.more + "</a></div></div>");
                if (b)for (l = sf.append(c, "div", {"class": "link-group clearfix"}), g = 0; g < b.length; g++)sf.result.appendAction(b[g], l, !0, e)
            }
        }, groupLinks: function (a) {
            for (var b =
            {}, d = !1, c = 0; c < a.length; c++)if (a[c].name) {
                var e = "0";
                a[c].group ? e = a[c].group.toLowerCase() : a[c].type && (e = a[c].type.toString().toLowerCase());
                if (b[e])b[e].index.push(c); else {
                    var f = !1 !== a[c].group_visible;
                    f && (d = !0);
                    b[e] = {index: [c], visible: f}
                }
            }
            if (-1 < navigator.userAgent.search(/midp|ucweb|uc ?browser|opera mini/i))return b;
            if (6 < a.length && b.mp4 && b.mp4.visible)for (c in b)"mp4" != c && "ummy" != c && (b[c].visible = !1);
            if (!d)for (c in b) {
                b[c].visible = !0;
                break
            }
            return b
        }
    },
    audioResult: {
        showRows: function (a, b) {
            if (a && "object" == typeof a && 0 != a.length) {
                var d = a[a.length - 1];
                d.more ? (d = d.more, a.pop()) : d = null;
                for (var c = 0; c < a.length; c++)b && !a[c].hosting && (a[c].hosting = b), this.show(a[c], !1, !1);
                d && d.url && d.id && sf.result.appendMoreBtn(!1, !1, d)
            }
        }, show: function (a, b, d, c) {
            if (!a || "object" != typeof a)return null;
            void 0 === d && (d = !0);
            var e = b ? $("#sf_multi_result .media-result") : $("#sf_result .media-result");
            e[0] ? e = e[0] : (e = sf.result.createBox(b), sf.result.appendHeader(e));
            if (!e)return null;
            b = $($("#tplAudioBox").render({
                player: a.player && a.player.url ?
                    !0 : !1,
                playerBtnCls: sf.audioPlayer.btnClass,
                playerBtnTitle: sf.lng.playerPlay,
                extra: a.extra ? a.extra : ""
            }));
            sf.result.appendAllLinks(a.url, a.action, $(".link-box", b)[0], a.meta && a.meta.title ? a.meta.title : "", a.hosting);
            sf.result.appendMetaInfo(a.meta, $(".meta", b)[0], "");
            if (a.player && a.player.url) {
                var f = "audio_player_" + (new Date).getTime() + "_" + Math.round(1E4 * Math.random()) + "_" + $(".result-box").length;
                $("." + sf.audioPlayer.btnClass, b).click(function (b) {
                    sf.audioPlayer.toggle(a.player, f, this)
                })
            }
            sf.result.execFunctions(a.fn,
                a);
            if (c)return b[0];
            d ? (b.css("display", "none"), b.appendTo(e), b.slideDown()) : b.appendTo(e);
            sf.result.handleCopyLinks(b);
            return b[0]
        }
    },
    multi: {
        links: null,
        validCount: 0,
        queueSize: 5,
        queueCount: 0,
        timer: 0,
        timeout: 3E4,
        limit: null,
        set: function (a, b) {
            clearTimeout(this.timer);
            a && (this.links = a, this.limit = b ? b : null, this.processNextLink())
        },
        processNextLink: function (a) {
            clearTimeout(this.timer);
            a && a.parentNode.removeChild(a);
            if (!this.links || 0 >= this.links.length) {
                if (this.limit && this.limit.message) {
                    if (0 < this.queueCount) {
                        this.endQueue();
                        return
                    }
                    this.showLimitMessage()
                }
                this.endProcessing()
            } else if (this.queueCount == this.queueSize)this.endQueue(); else if (a = $("#sf_multi_form_box"), a[0]) {
                a.empty();
                var b = sf.createElement("form", {
                    action: "/savefrom.php",
                    method: "POST",
                    target: "sf_frame"
                });
                sf.append(b, "input", {
                    type: "hidden",
                    id: "sf_url",
                    name: "sf_url",
                    value: this.links.shift()
                });
                sf.append(b, "input", {
                    type: "hidden",
                    id: "sf_submit",
                    name: "sf_submit",
                    value: "Download"
                });
                sf.append(b, "input", {type: "hidden", name: "sf_multi", value: "1"});
                a.append(b);
                sf.preSubmitForm(null,
                    !1, !1) && (b.submit(), this.timer = setTimeout(function () {
                    sf.multi.show(null)
                }, this.timeout))
            }
        },
        endQueue: function () {
            this.queueCount = 0;
            sf.removeBusyIndicator();
            $('#sf_multi_form input[name="sf_url"]').val("");
            $("#sf_multi_result .media-result").append('<div class="link-plus"><a href="#" class="action" onclick="sf.multi.processNextLink(this); return false;">' + sf.lng.multiMore + "</a></div>")
        },
        showLimitMessage: function () {
            this.limit.message && $("#sf_multi_result .media-result").append('<div class="center medium spacer1">' +
            this.limit.message + "</div>");
            this.limit = null
        },
        endProcessing: function () {
            if (1 > this.validCount) {
                var a = sf.getElement("sf_result");
                a && $(a).empty().append(sf.createElement("div", {"class": "center"}, null, sf.lng.linkNotFound))
            }
            this.queueCount = this.validCount = 0;
            this.limit = null;
            sf.removeBusyIndicator();
            $('#sf_multi_form input[name="sf_url"]').val("")
        },
        show: function (a, b, d) {
            clearTimeout(this.timer);
            var c = null;
            if (a)if ("object" == typeof a)c = a; else {
                try {
                    c = $.parseJSON(a)
                } catch (e) {
                }
                c && c.url || (c = eval("(" + a + ")"))
            }
            c && c.url &&
            (this.validCount++, d || this.queueCount++, b ? sf.audioResult.show(c, !0) : sf.videoResult.show(c, !0));
            this.processNextLink()
        }
    },
    fileResult: {
        showError: function (a) {
            a || (a = "linkNotFound");
            var b = sf.getElement("sf_result");
            b && $(b).empty().append(sf.createElement("div", {"class": "center"}, null, sf.lng[a] ? sf.lng[a] : a))
        }, getServiceClass: function (a) {
            return "icon-" + a.replace(/[^\w\-]/g, "_")
        }, show: function (a, b, d) {
            if (a.error)return this.showError(a.error), null;
            if (!a || "object" != typeof a)return this.showError(), null;
            void 0 ===
            b && (b = !0);
            var c = $("#sf_result .file-result");
            c[0] ? c = c[0] : (c = sf.result.createBox(!1, "file-result"), sf.result.appendHeader(c, {
                columns: [{
                    cls: "file-col",
                    html: sf.lng.tblHdrFile
                }, {cls: "size-col", html: sf.lng.tblHdrSize}]
            }));
            if (!c)return null;
            var e = $($("#tplFileBox").render({
                size: a.size ? a.size : "?",
                meta: a.meta ? !0 : !1,
                extra: a.extra ? a.extra : ""
            }));
            sf.result.appendAllLinks(a.url, a.action, $(".link-box", e)[0], "", a.hosting);
            a.meta && sf.result.appendMetaInfo(a.meta, $(".meta", e)[0], "");
            a.service && $(".link-box .link a, .link-box .link .text-url-box",
                e).before('<span class="favicon ' + this.getServiceClass(a.service) + '"></span>');
            sf.result.execFunctions(a.fn, a);
            if (d)return e[0];
            b ? (e.css("display", "none"), e.appendTo(c), e.slideDown()) : e.appendTo(c);
            return e[0]
        }
    },
    premiumResult: {
        queue: null,
        links: null,
        nodes: null,
        mvarch: null,
        length: {
            "rapidshare.com": 100,
            "depositfiles.com": 1,
            "letitbit.net": 1,
            "vip-file.com": 1
        },
        clip: null,
        checkTimeout: 3E4,
        set: function (a, b, d, c) {
            b && sf.premiumUser.setBalance(b);
            this.finish(!0);
            if (a && "object" == typeof a) {
                sf.removeBusyIndicator();
                this.queue = a;
                this.links = {};
                this.nodes = {};
                this.mvarch = null;
                for (var e in a)a[e].mvarch && this.fillMVArchs(a[e].mvarch, a[e].host, e);
                this.mvarch && this.sortMVArchs();
                var f = $("#sf_premium_result");
                b = sf.createElement("div");
                f.empty().append(b);
                sf.result.appendHeader(b, {
                    columns: [{
                        cls: "checkbox",
                        html: '<input type="checkbox" class="checkall">'
                    }, {cls: "file-col", html: sf.lng.tblHdrFile}, {
                        cls: "size-col",
                        html: sf.lng.tblHdrSize
                    }]
                });
                f = 1;
                for (e in a) {
                    var h = this.getMVArch(a[e]);
                    if (h) {
                        if (!h.show) {
                            h.token = "mvarch_" + f;
                            h.show = !0;
                            this.appendRow("", {
                                name: a[e].mvarch.name,
                                host: a[e].host,
                                length: h.parts.length,
                                token: h.token
                            }, b, f);
                            for (var g = 0; g < h.parts.length; g++) {
                                f++;
                                var l = h.parts[g];
                                this.appendRow(l.link, a[l.link], b, f, h.token)
                            }
                        }
                    } else this.appendRow(e, a[e], b, f);
                    f++
                }
                $(b).append($("#tplPremiumFooter").render());
                d && ($(b).append('<div class="link-plus"><a href="#" class="action">' + sf.lng.findMediaLinks + "</a></div>"), $(".link-plus a", b).click(function () {
                    $("#sf_indicator_box").html('<span id="sf_indicator"></span>').show();
                    sf.busy();
                    sf.multi.set(d, c);
                    this.parentNode.removeChild(this);
                    return !1
                }));
                this.processQueue();
                this.processQueue();
                this.processQueue()
            } else sf.multi.set(d, c)
        },
        getHostClass: function (a) {
            return "icon-" + a.replace(/[^\w\-]/g, "_")
        },
        appendRow: function (a, b, d, c, e) {
            c = {
                id: "pr_" + b.token + "_" + c,
                cls: "",
                host: this.getHostClass(b.host),
                title: a,
                source: ""
            };
            a ? (this.queue[a].nodeid = c.id, this.nodes[c.id] = a, c.source = a, e && (c.cls += " mvarch " + e)) : (c.mvarch = !0, c.id = b.token, c.cls += " mvarch-hdr", c.title = b.name, c.partsNum = b.length, c.partsPlural =
                sf.pluralize(b.length, "pluralParts"));
            $(d).append($("#tplPremiumBox").render(c))
        },
        finish: function (a) {
            this.queue = null;
            a && (this.mvarch = this.nodes = this.links = null, this.clip && (this.clip.destroy(), this.clip = null))
        },
        getMVArch: function (a) {
            if (!a.mvarch || !a.mvarch.name)return null;
            var b = a.mvarch.name;
            return this.mvarch[b] && this.mvarch[b][a.host] ? this.mvarch[b][a.host] : null
        },
        fillMVArchs: function (a, b, d) {
            a.name && (this.mvarch || (this.mvarch = {}), this.mvarch[a.name] || (this.mvarch[a.name] = {}), this.mvarch[a.name][b] ||
            (this.mvarch[a.name][b] = {
                parts: [],
                show: !1,
                valid: 0,
                invalid: 0,
                checked: 0
            }), this.mvarch[a.name][b].parts.push({part: a.part, link: d}))
        },
        sortMVArchs: function () {
            if (this.mvarch)for (var a in this.mvarch)for (var b in this.mvarch[a])2 > this.mvarch[a][b].parts.length ? delete this.mvarch[a][b] : this.mvarch[a][b].parts.sort(function (a, b) {
                return a.part - b.part
            })
        },
        processQueue: function () {
            if (this.queue) {
                var a = "", b = 0, d = 1, c = {}, e;
                for (e in this.queue) {
                    if (!a && (a = this.queue[e].host, !a)) {
                        this.finish();
                        return
                    }
                    if (a && this.queue[e].host ==
                        a && (this.links[e] = this.queue[e], c[e] = this.queue[e], delete this.queue[e], $("#" + this.links[e].nodeid + " .size-box .size").html("..."), 0 == b && (d = this.length[a], !d || 1 > d) && (d = 1), b++, b >= d))break
                }
                if (0 < b)return this.checkLinks(a, c)
            }
            this.finish()
        },
        getDownloadLink: function (a, b) {
            return "/get.php?url=" + encodeURIComponent(a) + "&key=" + b.key + "&lang=" + sf.lng.lang
        },
        checkLinks: function (a, b, d) {
            var c = this;
            d || (d = 0);
            var e = 0;
            d || (e = setTimeout(function () {
                c.checkLinks(a, b, !0)
            }, this.checkTimeout));
            $.post("/tools/check_links.php",
                {host: a, links: $.toJSON(b), skip: d}, function (a) {
                    clearTimeout(e);
                    c.processCheckedLinks(a, d);
                    c.processQueue()
                }, "json")
        },
        processCheckedLinks: function (a, b) {
            if (a.status && a.links)for (var d in a.links) {
                if (!b || !this.links[d].size) {
                    this.links[d] = a.links[d];
                    var c = a.links[d], e = this.getMVArch(c), f = $("#" + c.nodeid), h = $(".file-box", f), g = $(".size-box .size", f);
                    if (c.status) {
                        e && e.valid++;
                        var l = this.getDownloadLink(d, c);
                        this.links[d].download_url = l;
                        f.addClass("valid");
                        $("span.title", h).replaceWith('<a href="' + l + '" class="title" target="_blank">' +
                        (c.name ? c.name : d) + "</a>");
                        c.can_download || (e && (e.undownloadable = !0), f.addClass("disabled"), h.append('<div class="info">' + sf.lng.notEnoughPoints1 + "</div>"));
                        $("input:checkbox", f).attr("disabled", !1)
                    } else e && e.invalid++, f.addClass("invalid"), $("span.title", h).replaceWith('<a href="' + d + '" class="title" target="_blank">' + (c.name ? c.name : d) + "</a>"), $("input:checkbox", f).attr({
                        checked: !1,
                        disabled: !0
                    }), c.error && h.append('<div class="info">' + c.error + "</div>");
                    c.human_size ? g.html(c.human_size) : c.size ? g.html(c.size) :
                        g.html("?");
                    if (e && (f = $("#" + e.token), e.checked++, 0 < e.valid && $("input:checkbox", f).attr("disabled", !1), 0 < e.invalid && $(".parts .err", f).html("(" + e.invalid + ")"), e.checked == e.parts.length)) {
                        0 < e.invalid ? f.addClass("invalid") : (f.addClass("valid"), $(".parts .err", f).empty(), e.undownloadable && (f.addClass("disabled"), $(".file-box", f).append('<div class="info">' + sf.lng.notEnoughPoints1 + "</div>")));
                        for (h = c = 0; h < e.parts.length; h++)if (this.links[e.parts[h].link].size)c += parseInt(this.links[e.parts[h].link].size);
                        else {
                            c = "?";
                            break
                        }
                        "?" != c && (c = sf.sizeHuman(c, 2), c = sf.sizeHumanToString(c));
                        $(".size-box .size", f).text(c)
                    }
                }
            } else this.processQueue()
        },
        showSelectedFilesInfo: function (a, b, d, c) {
            var e = this;
            this.clip && (this.clip.destroy(), this.clip = null);
            b && 0 < b.length && (ZeroClipboard.setMoviePath("/embed/zeroclipboard10.swf"), this.clip = new ZeroClipboard.Client, this.clip.setHandCursor(!0), this.clip.setText(b.join("\r\n")), this.clip.glue($("#pr_copy")[0]), this.clip.addEventListener("onComplete", function (a, b) {
                sf.premiumUser.updateBalance(function (a) {
                    sf.premiumUser.balance <
                    c ? sf.showModal(sf.lng.notEnoughPoints2) : jAlert(sf.lng.copyLinksSuccess)
                })
            }), $("#pr_copy").click(function () {
                jAlertErr(sf.lng.copyLinksFail + " " + sf.lng.flashPlayerRequired);
                return !1
            }).mouseenter(function () {
                e.clip.reposition()
            }));
            sf.premiumUser.balance < c ? $("#pr_selected_files .info").html(sf.lng.notEnoughPoints2).show() : $("#pr_selected_files .info").empty().hide();
            0 < d && (d = sf.sizeHuman(d, 2), d = sf.sizeHumanToString(d));
            $("#pr_files_count").html(a ? a : 0);
            $("#pr_files_size").html(d ? d : 0)
        },
        getSelectedCheckBoxes: function () {
            return $("#sf_premium_result .result-box input:checked")
        },
        getSelectedFilesInfo: function (a) {
            var b = this, d = 0, c = [], e = 0, f = 0;
            this.getSelectedCheckBoxes().each(function () {
                var a = $(this).closest(".result-box"), g = a.attr("id");
                g && !a.hasClass("mvarch-hdr") && (d++, (a = b.nodes[g]) && b.links[a] && (g = b.links[a].download_url, -1 == g.search(/^https?:\/\//i) && (g = location.protocol + "//" + location.host + g), c.push(g), g = parseInt(b.links[a].size), isNaN(g) || (e += g), a = parseFloat(b.links[a].cost), isNaN(a) || (f += a)))
            });
            a ? sf.premiumUser.updateBalance(function (h) {
                b.showSelectedFilesInfo(d, c, e,
                    f);
                a.call(null, d, c, e, f, h)
            }) : this.showSelectedFilesInfo(d, c, e, f)
        }
    },
    download: {
        queueId: null,
        lastNode: null,
        timer: 0,
        timeout: 3E4,
        checkTimeout: 5E3,
        begin: function () {
            this.reset();
            this.queueId = (new Date).getTime() + Math.random();
            this.disableBtn(!0);
            this.processNextLink()
        },
        disableBtn: function (a) {
            a ? $("#pr_download").addClass("disabled") : $("#pr_download").removeClass("disabled")
        },
        getNextCheckBox: function () {
            var a = $("#sf_premium_result .result-box:not(.mvarch-hdr) input:checked");
            return a[0] ? a[0] : null
        },
        processNextLink: function () {
            var a =
                this, b = this.getNextCheckBox();
            if (b) {
                b = $(b);
                b.attr("checked", !1);
                var d = b.closest(".result-box").attr("id");
                if (d) {
                    var b = sf.premiumResult.nodes[d], c = null;
                    b && sf.premiumResult.links[b] && (c = sf.premiumResult.links[b]);
                    b && c ? ($("#" + d).addClass("attempt"), b = sf.premiumResult.getDownloadLink(b, c) + "&queue_id=" + this.queueId, this.lastNode = d, this.timer = setTimeout(function () {
                        a.processNextLink()
                    }, this.timeout), location.href = b, setTimeout(function () {
                        sf.append(document.body, "iframe", {
                            id: d + "_frame",
                            src: "/tools/check_download_queue.php?key=" +
                            c.key + "&queue_id=" + a.queueId + "&nodeid=" + a.lastNode,
                            width: 0,
                            height: 0,
                            frameborder: 0,
                            scrolling: "no"
                        })
                    }, this.checkTimeout)) : this.processNextLink()
                } else this.reset()
            } else this.reset()
        },
        setLinkInfo: function (a, b, d) {
            var c = this;
            a == this.queueId && (b == this.lastNode && clearTimeout(this.timer), a = $("#" + b).removeClass("attempt"), "object" == typeof d && void 0 !== d.header ? (a.addClass("attempt-fail"), a = "", (b = sf.premiumResult.nodes[b]) && sf.premiumResult.links[b] && (a = sf.premiumResult.links[b]), b = "<h3>" + sf.lng.dQueueError +
            '</h3><span class="favicon ' + sf.premiumResult.getHostClass(a.host) + '"></span><a href="' + b + '" target="_blank">' + a.name + "</a><br><br>", d.header && (b += d.header + "<br><br>"), d.content && (b += d.content + "<br><br>"), d = null, this.getNextCheckBox() && (b += sf.lng.dQueueContinue, d = {}, d[sf.lng.dQueueYes] = !0, d[sf.lng.dQueueNo] = !1), sf.showModal(b, d, function (a) {
                a ? c.processNextLink() : c.reset()
            })) : b == this.lastNode && (a.addClass("attempt-success"), this.processNextLink()))
        },
        reset: function () {
            this.lastNode = this.queueId = null;
            this.disableBtn(!1)
        }
    },
    premiumUser: {
        balance: null, setBalance: function (a) {
            this.balance = a;
            $(".profile .balance .text").html(null === this.balance ? "?" : this.balance)
        }, updateBalance: function (a) {
            $.post("/p/ajax.php", {action: "get_user_balance"}, function (b) {
                b.balance || 0 === b.balance ? sf.premiumUser.setBalance(b.balance) : sf.premiumUser.setBalance(null);
                a && a.call(null, sf.premiumUser.balance)
            }, "json")
        }
    },
    bookmarklet: {
        show: function (a) {
            var b = sf.getElement("sf_result");
            if (b)if ($(b).empty(), a && "object" === typeof a) {
                var d =
                    sf.createElement("div");
                $(b).append(d);
                a.html && sf.append(d, "div", {"class": "center"}, null, a.html);
                a.audio && sf.audioResult.showRows(a.audio);
                a.video && sf.videoResult.showRows(a.video)
            } else $(b).append(sf.createElement("div", {"class": "center"}, null, sf.lng.linkNotFound))
        }
    },
    audioPlayer: {
        id: "audio_player",
        player: null,
        currentBoxId: null,
        currentBtn: null,
        btnClass: "play-btn",
        continuousMode: !0,
        width: 200,
        height: 16,
        sendEvent: function (a, b) {
            if (this.player)try {
                this.player.sendEvent(a, b)
            } catch (d) {
            }
        },
        urlPrepare: function (a) {
            return a.replace(/\?/g,
                "%3F").replace(/=/g, "%3D").replace(/&/g, "%26")
        },
        toggle: function (a, b, d) {
            this.currentBoxId ? b != this.currentBoxId ? (this.switchOff(), this.switchOn(a, b, d)) : this.switchOff() : this.switchOn(a, b, d)
        },
        switchOn: function (a, b, d) {
            if (a && a.url && b) {
                this.currentBoxId = b;
                var c = document.getElementById(b);
                if (!c)if (d)$parent = $(d).closest(".result-box").find(".meta"), $parent[0] && (c = sf.append($parent[0], "div", {
                    id: b,
                    "class": "audio-player-box"
                })); else return;
                if (c) {
                    $(c).hide();
                    var e = document.createElement("span");
                    e.id = b + "_tmp";
                    c.appendChild(e);
                    this.player = e;
                    $(d).addClass("active");
                    this.currentBtn = d;
                    b = a.type ? a.type : "sound";
                    d = a.streamer ? a.streamer : "";
                    b = {
                        id: this.id,
                        file: this.urlPrepare(a.url),
                        type: b,
                        autostart: !0,
                        streamer: d,
                        controlbar: "bottom",
                        skin: "/embed/skin/audio.zip"
                    };
                    d = {id: b.id, name: b.id};
                    var f = "/embed/player.swf";
                    -1 < a.url.search(/^http:\/\/m\d+\.li.ru\//i) && (f = "/flash/mjupl4li.swf?123");
                    swfobject.embedSWF(f, e.id, this.width, this.height, "9", "", b, {
                        allowscriptaccess: "always",
                        wmode: "transparent",
                        bgcolor: "#FFFFFF"
                    }, d);
                    window.playerReady =
                        function (a) {
                            sf.audioPlayer.player = document.getElementById(a.id);
                            sf.audioPlayer.continuousMode && sf.audioPlayer.player.addModelListener("STATE", "function(obj){sf.audioPlayer.stateTracker(obj);}")
                        };
                    $(c).slideDown()
                }
            }
        },
        stateTracker: function (a) {
            if (this.continuousMode && this.currentBtn) {
                var b = {PLAYING: 1, BUFFERING: 1, IDLE: 1, PAUSED: 1};
                "COMPLETED" == a.newstate && b[a.oldstate] && (a = $(this.currentBtn).closest(".result-box").next(".result-box"), a[0] && (a = $("." + this.btnClass, a), a[0] && a.click()))
            }
        },
        switchOff: function () {
            this.sendEvent("STOP");
            this.player = null;
            this.currentBoxId && ($("#" + this.currentBoxId).empty().hide(), this.currentBoxId = null);
            this.currentBtn && ($(this.currentBtn).removeClass("active"), this.currentBtn = null)
        }
    },
    videoPlayer: {
        id: "video_player",
        player: null,
        hdmode: !1,
        widthCorrection: 0,
        heightCorrection: 0,
        currentBoxId: null,
        currentBtn: null,
        sd: null,
        hd: null,
        sdDefaultWidth: 640,
        sdDefaultHeight: 360,
        hdDefaultWidth: 854,
        hdDefaultHeight: 480,
        sendEvent: function (a, b) {
            if (this.player)try {
                this.player.sendEvent(a, b)
            } catch (d) {
            }
        },
        checkIntValue: function (a) {
            a &&
            "string" == typeof a && (a = parseInt(a), isNaN(a) && (a = 0));
            return a
        },
        urlPrepare: function (a) {
            return a.replace(/\?/g, "%3F").replace(/=/g, "%3D").replace(/&/g, "%26")
        },
        getFullscreenState: function () {
            return this.player ? this.player.getConfig().fullscreen : !1
        },
        getHdState: function () {
            return this.player ? this.player.getPluginConfig("hd").state : !1
        },
        toggle: function (a, b, d, c, e, f, h) {
            a && (a.width && (a.width = this.checkIntValue(a.width)), a.height && (a.height = this.checkIntValue(a.height)), a.width && a.height || (a.width = this.sdDefaultWidth,
                a.height = this.sdDefaultHeight, b || (a.width = this.hdDefaultWidth, a.height = this.hdDefaultHeight)));
            b && (b.width && (b.width = this.checkIntValue(b.width)), b.height && (b.height = this.checkIntValue(b.height)), b.width && b.height || (b.width = this.hdDefaultWidth, b.height = this.hdDefaultHeight));
            "string" == typeof e && (e = document.getElementById(e));
            this.currentBoxId ? c != this.currentBoxId ? (this.switchOff(), this.switchOn(a, b, d, c, e, f, h)) : this.switchOff() : this.switchOn(a, b, d, c, e, f, h)
        },
        createPlayer: function (a, b, d, c) {
            this.currentBoxId =
                d;
            var e = document.getElementById(d);
            if (!e.firstChild) {
                d += "_tmp";
                sf.append(e, "div", {id: d});
                $(c).addClass("active").attr({alt: sf.lng.playerStop, title: sf.lng.playerStop});
                this.currentBtn = c;
                this.sd = a;
                this.hd = b;
                c = a.url;
                var f = a.width, h = a.height, g = {
                    id: this.id,
                    autostart: !0,
                    controlbar: "over",
                    start: 0,
                    skin: "/embed/skin/darkrv5.zip"
                }, l = {id: g.id, name: g.id}, m = "/embed/player.swf", n = !1;
                if (-1 != c.search(/^http:\/\/([a-z]+\.)?youtube\.com\/watch\?v=/i))n = !0, g.file = this.urlPrepare(c), g.type = "youtube", b && b.url && (g.plugins =
                    "/embed/hd.swf", g["hd.state"] = "false", g["hd.autoswitch"] = "false"), f += this.widthCorrection, h += this.heightCorrection; else if (-1 != c.search(/^http:\/\/flv\.video\.yandex\.ru\//i))m = c, g.file = ""; else {
                    var k = a.type ? a.type : "video", q = a.streamer ? a.streamer : "";
                    g.file = this.urlPrepare(c);
                    g.type = k;
                    g.streamer = q;
                    b && (g.plugins = "/embed/hd.swf", g["hd.state"] = !1, g["hd.autoswitch"] = !1, g["hd.file"] = this.urlPrepare(b.url));
                    f += this.widthCorrection;
                    h += this.heightCorrection
                }
                var p = Math.floor($(e).width());
                f > p && (f = p, h = Math.floor(.5625 *
                p));
                swfobject.embedSWF(m, d, f, h, "9", "", g, {
                    allowscriptaccess: "always",
                    allowfullscreen: "true",
                    wmode: "opaque",
                    bgcolor: "#000000"
                }, l);
                window.playerReady = function (c) {
                    c = document.getElementById(c.id);
                    sf.videoPlayer.player = c;
                    $(c).css({position: "relative", zIndex: 10});
                    n && c.addModelListener("ERROR", "function(event){sf.videoPlayer.errorHandler(event);}");
                    b && f > p && (b.width != a.width || b.height != a.height) && c.addModelListener("STATE", "function(event){sf.videoPlayer.stateTracker(event);}")
                }
            }
        },
        errorHandler: function (a) {
            a.message &&
            (void 0 == this.sd.url2 ? (a = $(this.player).closest(".video-player-box"), a[0] && !a.hasClass("error") && (a.addClass("error"), sf.append(a[0], "div", {"class": "video-player-msg"}, null, sf.lng.player_err1))) : this.sd.url2 && (this.sd.url = this.sd.url2, this.sd.url2 = null, this.hd && this.hd.url2 && (this.hd.url = this.hd.url2, this.hd.url2 = null), this.player && (this.player.parentNode.removeChild(this.player), this.player = null, this.currentBoxId && this.currentBtn && this.createPlayer(this.sd, this.hd, this.currentBoxId, this.currentBtn))))
        },
        stateTracker: function (a) {
            if ("PLAYING" == a.newstate && (a = this.getHdState(), a != this.hdmode && (this.hdmode = a, !this.getFullscreenState()))) {
                var b = this.sd.width, d = this.sd.height;
                a && (b = this.hd.width, d = this.hd.height);
                this.player && (this.player.width = parseInt(b), this.player.height = parseInt(d))
            }
        },
        switchOn: function (a, b, d, c, e, f, h) {
            if ((a && a.url || d) && c) {
                var g = document.getElementById(c);
                if (!g)if (e) {
                    var l = $(e).closest(".result-box");
                    l[0] && (g = sf.append(l[0], "div", {id: c, "class": "video-player-box"}))
                } else return;
                g && ($(g).hide().css("height",
                    a.height + "px"), a && a.url ? $("html, body").animate({scrollTop: $(e).offset().top - 10}, "normal", function () {
                    $(g).slideDown("fast", function () {
                        sf.videoPlayer.createPlayer(a, b, c, e);
                        this.style.height = "auto"
                    })
                }) : d && (l = $("#" + d + " a"), l[0] && (l = l[0], l.href && (-1 != l.href.search(/^https?:\/\//i) ? (a.url = l.href, $("html, body").animate({scrollTop: $(e).offset().top - 10}, "normal", function () {
                    $(g).slideDown("fast", function () {
                        sf.videoPlayer.createPlayer(a, b, c, e);
                        this.style.height = "auto"
                    })
                })) : -1 != l.href.search(/javascript/i) &&
                f && h && sf.ajaxGetInfo(f, h, function () {
                    sf.videoPlayer.switchOn(a, b, d, c, e)
                })))))
            }
        },
        switchOff: function () {
            this.sendEvent("STOP");
            $("#" + this.currentBoxId).empty().hide().removeClass("error");
            this.hd = this.sd = this.currentBoxId = this.player = null;
            this.currentBtn && ($(this.currentBtn).removeClass("active").attr({
                alt: sf.lng.playerPlay,
                title: sf.lng.playerPlay
            }), this.currentBtn = null)
        }
    },
    fileInfo: {
        get: function (a, b, d, c, e, f, h) {
            if (a) {
                $btn = $(c);
                $btn.unbind("click").removeAttr("onclick").addClass("active");
                c.onclick && (c.onclick =
                    null);
                var g = $btn.closest(".result-box").find(".meta")[0];
                if (g) {
                    var l = "file_info" + (new Date).getTime() + "_" + Math.round(1E4 * Math.random()) + "_" + b, m = sf.append(g, "div", {
                        id: l,
                        "class": "media-info"
                    });
                    f = f ? "/video_info.php" : "/mp3_info.php";
                    var n = sf.append(m, "span", {id: l + "_busy"});
                    sf.busy(l + "_busy", !0);
                    $.post(f, {
                        sf_url: a,
                        ajax: 1,
                        lang: sf.lng.lang,
                        filetype: b,
                        token: e,
                        header: h ? h : ""
                    }, function (a) {
                        $(n).slideUp();
                        try {
                            a = $.parseJSON(a)
                        } catch (b) {
                        }
                        a && "object" == typeof a || (a = -1 < a.indexOf("\x3c!--error--\x3e") ? {err: a} : {information: {value: a}});
                        sf.fileInfo.show(a, d, c, m)
                    })
                }
            }
        }, toggle: function (a, b) {
            $(b).slideToggle(function () {
                "none" == this.style.display ? $(a).removeClass("active") : $(a).addClass("active")
            })
        }, show: function (a, b, d, c) {
            $(d).click(function () {
                sf.fileInfo.toggle(d, c)
            });
            if (c) {
                var e = sf.append(c, "div", null, {display: "none"});
                if (a.err)sf.append(e, "span", {"class": "error"}, {"margin-top": "10px"}, a.err); else if (b && sf.append(e, "div", {"class": "title"}, null, b), a && "object" == typeof a) {
                    var f = {}, h;
                    for (h in a)if (a[h].value) {
                        b = a[h].name ? a[h].name.trans :
                            h;
                        b = b.replace(/^./, function (a) {
                            return a.toUpperCase()
                        });
                        var g = "";
                        if ("object" == typeof a[h].value)for (var l = !1, m = 0; m < a[h].value.length; m++)l && (g += "<br>"), g += sf.fileInfo.appendTagValue(a[h].value[m], a[h].type), l = !0; else g += sf.fileInfo.appendTagValue(a[h].value, a[h].type);
                        if (a[h].info && a[h].info.value)if (g += "<br>", 0 < a[h].info.length)for (m = 0; m < a[h].info.length; m++)a[h].info[m].value && (g += sf.fileInfo.appendTagValue(a[h].info[m].value, a[h].info[m].type)); else g += sf.fileInfo.appendTagValue(a[h].info.value,
                            a[h].info.type);
                        g && (f[b] = g)
                    }
                    sf.result.appendMetaInfo(f, e, "media")
                }
                $(e).slideDown()
            }
        }, appendTagValue: function (a, b) {
            return a || 0 === a ? "textarea" == b ? '<textarea class="id3tag-value" cols="60" rows="2">' + a + "</textarea>" : "input" == b ? (a = a.replace(/\x22/g, "&quot;").replace(/[\r\n]/g, ""), '<input class="id3tag-value" type="text" size="60" value="' + a + '"/>') : a : ""
        }
    },
    ajaxGetInfo: function (a, b, d, c, e) {
        if (a && b) {
            var f = $("#" + b);
            if (f[0]) {
                var h = document.createElement("span");
                h.id = "busy_box_" + b;
                f.append(h);
                sf.busy("busy_box_" +
                b, !0);
                a = "sf_url=" + encodeURIComponent(a) + "&ajax=1&ajaxid=" + encodeURIComponent(b);
                c && (a += "&" + c);
                $.post("http://" + window.location.host + (e ? e : "/savefrom.php"), a, function (a) {
                    f.empty();
                    if (a)try {
                        -1 != a.search(/error/i) && alert(a);
                        var b = null;
                        try {
                            b = $.parseJSON(a)
                        } catch (c) {
                        }
                        if (!b || "object" != typeof b) {
                            var e = eval(a);
                            -1 != e.search(/string.fromcharcode/i) && (e = eval(e));
                            f.html(e)
                        }
                        d && "function" == typeof d && d(b)
                    } catch (h) {
                    }
                })
            }
        }
    },
    ajaxGetCaptchaInfo: function (a, b, d, c, e, f, h) {
        if (e = document.getElementById(e))e.value ? (c = "sf_cookie=" +
        d + "&sf_post=" + c + "&sf_imgcode=" + escape(e.value) + "&sf_submit=OK", h && (c += "&" + h), sf.ajaxGetInfo(a, b, f, c)) : alert(sf.lng.error3)
    },
    youtubeSubtitles: function (a, b, d, c, e) {
        "string" == typeof d && (d = $(d)[0]);
        var f = $("#" + b);
        if (!f[0]) {
            if (!d)return !1;
            var h = $(d).closest(".result-box").find(".meta")[0];
            if (!h)return !1;
            f = sf.append(h, "div", {id: b, "class": "subtitles"});
            f = $(f)
        }
        f.empty();
        sf.busy(b, !0);
        d && ($(d).unbind("click"), d.onclick = null, d.removeAttribute("onclick", !1));
        $.get("/youtube_subtitles.php", {
            action: "list", v: a,
            token: c, title: e, lang: sf.lng.lang
        }, function (a) {
            f.empty();
            a && f.hide().html(a).slideDown();
            d && $(d).click(function () {
                f.slideToggle()
            })
        });
        return !0
    },
    clipboard: {
        node: null,
        btn: null,
        clip: null,
        timer: 0,
        delayShow: 300,
        delayHide: 2500,
        destroy: function () {
            clearTimeout(this.timer);
            this.node && ($(this.node).unbind("mouseout").unbind("mouseover"), this.node = null);
            this.btn && (this.btn.parentNode.removeChild(this.btn), this.btn = null);
            this.clip && (this.clip.destroy(), this.clip = null)
        },
        attach: function (a) {
            this.node = a;
            $(a).mouseout(function () {
                sf.clipboard.timer =
                    setTimeout(function () {
                        sf.clipboard.destroy()
                    }, sf.clipboard.delayHide)
            }).mouseover(function () {
                clearTimeout(sf.clipboard.timer)
            });
            var b = sf.createElement("span", {"class": "flat-transp-btn"}, {visibility: "hidden"}, sf.lng.copy), b = a.nextSibling ? a.parentNode.insertBefore(b, a.nextSibling) : a.parentNode.appendChild(b);
            "DIV" == a.parentNode.tagName ? (a.parentNode.style.position = "relative", $(b).css({
                position: "absolute",
                top: "0px",
                right: "0px",
                "float": "none",
                display: "block"
            })) : (a.style.display = "inline", b.className = "middle",
                b.style.verticalAlign = "middle", b.style.marginLeft = "10px");
            b.style.visibility = "visible";
            this.clip = new ZeroClipboard.Client;
            this.clip.setHandCursor(!0);
            this.clip.setText("INPUT" == a.tagName ? a.value : a.innerHTML);
            this.clip.glue(b);
            this.clip.receiveEvent("mouseover", function () {
                sf.clipboard.clip.setText("INPUT" == a.tagName ? a.value : a.innerHTML)
            });
            this.clip.receiveEvent("mouseout", null);
            this.btn = b
        },
        init: function () {
            ZeroClipboard.setMoviePath("/embed/zeroclipboard10.swf");
            $("body").on("click", ".file-name, .file-name-inline",
                function () {
                    sf.clipboard.node != this && (clearTimeout(sf.clipboard.timer), sf.clipboard.destroy(), sf.clipboard.attach(this))
                })
        }
    }
};
