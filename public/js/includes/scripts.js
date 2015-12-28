!function (a) {
    a.fn.hoverIntent = function (b, c, d) {
        var e = { interval: 100, sensitivity: 6, timeout: 0 };
        e = "object" == typeof b ? a.extend(e, b) : a.isFunction(c) ? a.extend(e, { over: b, out: c, selector: d }) : a.extend(e, { over: b, out: b, selector: c });
        var f, g, h, i, j = function (a) {
            f = a.pageX, g = a.pageY;
        }, k = function (b, c) {
            return c.hoverIntent_t = clearTimeout(c.hoverIntent_t), Math.sqrt((h - f) * (h - f) + (i - g) * (i - g)) < e.sensitivity ? (a(c).off("mousemove.hoverIntent", j), c.hoverIntent_s = !0, e.over.apply(c, [b])) : (h = f, i = g, c.hoverIntent_t = setTimeout(function () {
                k(b, c);
            }, e.interval), void 0);
        }, l = function (a, b) {
            return b.hoverIntent_t = clearTimeout(b.hoverIntent_t), b.hoverIntent_s = !1, e.out.apply(b, [a]);
        }, m = function (b) {
            var c = a.extend({}, b), d = this;
            d.hoverIntent_t && (d.hoverIntent_t = clearTimeout(d.hoverIntent_t)), "mouseenter" === b.type ? (h = c.pageX, i = c.pageY, a(d).on("mousemove.hoverIntent", j), d.hoverIntent_s || (d.hoverIntent_t = setTimeout(function () {
                k(c, d);
            }, e.interval))) : (a(d).off("mousemove.hoverIntent", j), d.hoverIntent_s && (d.hoverIntent_t = setTimeout(function () {
                l(c, d);
            }, e.timeout)));
        };
        return this.on({ "mouseenter.hoverIntent": m, "mouseleave.hoverIntent": m }, e.selector);
    };
}(jQuery);
var showNotice, adminMenu, columns, validateForm, screenMeta;
!function (a, b, c) {
    var d = a(document), e = a(b), f = a(document.body);
    adminMenu = { init: function () {
    }, fold: function () {
    }, restoreMenuState: function () {
    }, toggle: function () {
    }, favorites: function () {
    } }, columns = { init: function () {
        var b = this;
        a(".hide-column-tog", "#adv-settings").click(function () {
            var c = a(this), d = c.val();
            c.prop("checked") ? b.checked(d) : b.unchecked(d), columns.saveManageColumnsState();
        });
    }, saveManageColumnsState: function () {
        var b = this.hidden();
        a.post(ajaxurl, { action: "hidden-columns", hidden: b, screenoptionnonce: a("#screenoptionnonce").val(), page: pagenow });
    }, checked: function (b) {
        a(".column-" + b).removeClass("hidden"), this.colSpanChange(1);
    }, unchecked: function (b) {
        a(".column-" + b).addClass("hidden"), this.colSpanChange(-1);
    }, hidden: function () {
        return a(".manage-column[id]").filter(":hidden").map(function () {
            return this.id;
        }).get().join(",");
    }, useCheckboxesForHidden: function () {
        this.hidden = function () {
            return a(".hide-column-tog").not(":checked").map(function () {
                var a = this.id;
                return a.substring(a, a.length - 5);
            }).get().join(",");
        };
    }, colSpanChange: function (b) {
        var c, d = a("table").find(".colspanchange");
        d.length && (c = parseInt(d.attr("colspan"), 10) + b, d.attr("colspan", c.toString()));
    } }, d.ready(function () {
        columns.init();
    }), validateForm = function (b) {
        return !a(b).find(".form-required").filter(function () {
            return "" === a("input:visible", this).val();
        }).addClass("form-invalid").find("input:visible").change(function () {
            a(this).closest(".form-invalid").removeClass("form-invalid");
        }).size();
    }, showNotice = { warn: function () {
        var a = commonL10n.warnDelete || "";
        return confirm(a) ? !0 : !1;
    }, note: function (a) {
        alert(a);
    } }, screenMeta = { element: null, toggles: null, page: null, init: function () {
        this.element = a("#screen-meta"), this.toggles = a("#screen-meta-links").find(".show-settings"), this.page = a("#wpcontent"), this.toggles.click(this.toggleEvent);
    }, toggleEvent: function () {
        var b = a("#" + a(this).attr("aria-controls"));
        b.length && (b.is(":visible") ? screenMeta.close(b, a(this)) : screenMeta.open(b, a(this)));
    }, open: function (b, c) {
        a("#screen-meta-links").find(".screen-meta-toggle").not(c.parent()).css("visibility", "hidden"), b.parent().show(), b.slideDown("fast", function () {
            b.focus(), c.addClass("screen-meta-active").attr("aria-expanded", !0);
        }), d.trigger("screen:options:open");
    }, close: function (b, c) {
        b.slideUp("fast", function () {
            c.removeClass("screen-meta-active").attr("aria-expanded", !1), a(".screen-meta-toggle").css("visibility", ""), b.parent().hide();
        }), d.trigger("screen:options:close");
    } }, a(".contextual-help-tabs").delegate("a", "click", function (b) {
        var c, d = a(this);
        return b.preventDefault(), d.is(".active a") ? !1 : (a(".contextual-help-tabs .active").removeClass("active"), d.parent("li").addClass("active"), c = a(d.attr("href")), a(".help-tab-content").not(c).removeClass("active").hide(), void c.addClass("active").show());
    }), d.ready(function () {
        function c() {
            var c, d = a("a.wp-has-current-submenu");
            c = b.innerWidth ? Math.max(b.innerWidth, document.documentElement.clientWidth) : 961, f.hasClass("folded") || f.hasClass("auto-fold") && c && 960 >= c && c > 782 ? d.attr("aria-haspopup", "true") : d.attr("aria-haspopup", "false");
        }
        function g(a) {
            var b, c, d, f, g, h, i, j = a.find(".wp-submenu");
            g = a.offset().top, h = e.scrollTop(), i = g - h - 30, b = g + j.height() + 1, c = B.height(), d = 60 + b - c, f = e.height() + h - 50, b - d > f && (d = b - f), d > i && (d = i), d > 1 ? j.css("margin-top", "-" + d + "px") : j.css("margin-top", "");
        }
        function h() {
            a(".notice.is-dismissible").each(function () {
                var b = a(this), c = a('<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>'), d = commonL10n.dismiss || "";
                c.find(".screen-reader-text").text(d), c.on("click.wp-dismiss-notice", function (a) {
                    a.preventDefault(), b.fadeTo(100, 0, function () {
                        b.slideUp(100, function () {
                            b.remove();
                        });
                    });
                }), b.append(c);
            });
        }
        function i(a) {
            var b = e.scrollTop(), c = !a || "scroll" !== a.type;
            if (!(x || z || C.data("wp-responsive"))) {
                if (O.menu + O.adminbar < O.window || O.menu + O.adminbar + 20 > O.wpwrap)
                    return void k();
                if (N = !0, O.menu + O.adminbar > O.window) {
                    if (0 > b)
                        return void (K || (K = !0, L = !1, A.css({ position: "fixed", top: "", bottom: "" })));
                    if (b + O.window > d.height() - 1)
                        return void (L || (L = !0, K = !1, A.css({ position: "fixed", top: "", bottom: 0 })));
                    b > J ? K ? (K = !1, M = A.offset().top - O.adminbar - (b - J), M + O.menu + O.adminbar < b + O.window && (M = b + O.window - O.menu - O.adminbar), A.css({ position: "absolute", top: M, bottom: "" })) : !L && A.offset().top + O.menu < b + O.window && (L = !0, A.css({ position: "fixed", top: "", bottom: 0 })) : J > b ? L ? (L = !1, M = A.offset().top - O.adminbar + (J - b), M + O.menu > b + O.window && (M = b), A.css({ position: "absolute", top: M, bottom: "" })) : !K && A.offset().top >= b + O.adminbar && (K = !0, A.css({ position: "fixed", top: "", bottom: "" })) : c && (K = L = !1, M = b + O.window - O.menu - O.adminbar - 1, M > 0 ? A.css({ position: "absolute", top: M, bottom: "" }) : k());
                }
                J = b;
            }
        }
        function j() {
            O = { window: e.height(), wpwrap: B.height(), adminbar: I.height(), menu: A.height() };
        }
        function k() {
            !x && N && (K = L = N = !1, A.css({ position: "", top: "", bottom: "" }));
        }
        function l() {
            j(), C.data("wp-responsive") ? (f.removeClass("sticky-menu"), k()) : O.menu + O.adminbar > O.window ? (i(), f.removeClass("sticky-menu")) : (f.addClass("sticky-menu"), k());
        }
        var m, n, o, p, q, r, s, t, u = !1, v = a("input.current-page"), w = v.val(), x = /iPhone|iPad|iPod/.test(navigator.userAgent), y = -1 !== navigator.userAgent.indexOf("Android"), z = a(document.documentElement).hasClass("ie8"), A = a("#adminmenuwrap"), B = a("#wpwrap"), C = a("#adminmenu"), D = a("#wp-responsive-overlay"), E = a("#wp-toolbar"), F = E.find('a[aria-haspopup="true"]'), G = a(".meta-box-sortables"), H = !1, I = a("#wpadminbar"), J = 0, K = !1, L = !1, M = 0, N = !1, O = { window: e.height(), wpwrap: B.height(), adminbar: I.height(), menu: A.height() };
        C.on("click.wp-submenu-head", ".wp-submenu-head", function (b) {
            a(b.target).parent().siblings("a").get(0).click();
        }), a("#collapse-menu").on("click.collapse-menu", function () {
            var e, g;
            a("#adminmenu div.wp-submenu").css("margin-top", ""), e = b.innerWidth ? Math.max(b.innerWidth, document.documentElement.clientWidth) : 961, e && 960 > e ? f.hasClass("auto-fold") ? (f.removeClass("auto-fold").removeClass("folded"), setUserSetting("unfold", 1), setUserSetting("mfold", "o"), g = "open") : (f.addClass("auto-fold"), setUserSetting("unfold", 0), g = "folded") : f.hasClass("folded") ? (f.removeClass("folded"), setUserSetting("mfold", "o"), g = "open") : (f.addClass("folded"), setUserSetting("mfold", "f"), g = "folded"), c(), d.trigger("wp-collapse-menu", { state: g });
        }), d.on("wp-window-resized wp-responsive-activate wp-responsive-deactivate", c), ("ontouchstart" in b || /IEMobile\/[1-9]/.test(navigator.userAgent)) && (r = x ? "touchstart" : "click", f.on(r + ".wp-mobile-hover", function (b) {
            C.data("wp-responsive") || a(b.target).closest("#adminmenu").length || C.find("li.opensub").removeClass("opensub");
        }), C.find("a.wp-has-submenu").on(r + ".wp-mobile-hover", function (b) {
            var c = a(this).parent();
            C.data("wp-responsive") || c.hasClass("opensub") || c.hasClass("wp-menu-open") && !(c.width() < 40) || (b.preventDefault(), g(c), C.find("li.opensub").removeClass("opensub"), c.addClass("opensub"));
        })), x || y || (C.find("li.wp-has-submenu").hoverIntent({ over: function () {
            var b = a(this), c = b.find(".wp-submenu"), d = parseInt(c.css("top"), 10);
            isNaN(d) || d > -5 || C.data("wp-responsive") || (g(b), C.find("li.opensub").removeClass("opensub"), b.addClass("opensub"));
        }, out: function () {
            C.data("wp-responsive") || a(this).removeClass("opensub").find(".wp-submenu").css("margin-top", "");
        }, timeout: 200, sensitivity: 7, interval: 90 }), C.on("focus.adminmenu", ".wp-submenu a", function (b) {
            C.data("wp-responsive") || a(b.target).closest("li.menu-top").addClass("opensub");
        }).on("blur.adminmenu", ".wp-submenu a", function (b) {
            C.data("wp-responsive") || a(b.target).closest("li.menu-top").removeClass("opensub");
        }).find("li.wp-has-submenu.wp-not-current-submenu").on("focusin.adminmenu", function () {
            g(a(this));
        })), a("div.updated, div.error, div.notice").not(".inline, .below-h2").insertAfter(a(".wrap").children(":header").first()), d.on("wp-plugin-update-error", function () {
            h();
        }), screenMeta.init(), a("tbody").children().children(".check-column").find(":checkbox").click(function (b) {
            if ("undefined" == b.shiftKey)
                return !0;
            if (b.shiftKey) {
                if (!u)
                    return !0;
                m = a(u).closest("form").find(":checkbox").filter(":visible:enabled"), n = m.index(u), o = m.index(this), p = a(this).prop("checked"), n > 0 && o > 0 && n != o && (q = o > n ? m.slice(n, o) : m.slice(o, n), q.prop("checked", function () {
                    return a(this).closest("tr").is(":visible") ? p : !1;
                }));
            }
            u = this;
            var c = a(this).closest("tbody").find(":checkbox").filter(":visible:enabled").not(":checked");
            return a(this).closest("table").children("thead, tfoot").find(":checkbox").prop("checked", function () {
                return 0 === c.length;
            }), !0;
        }), a("thead, tfoot").find(".check-column :checkbox").on("click.wp-toggle-checkboxes", function (b) {
            var c = a(this), d = c.closest("table"), e = c.prop("checked"), f = b.shiftKey || c.data("wp-toggle");
            d.children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
                return a(this).is(":hidden,:disabled") ? !1 : f ? !a(this).prop("checked") : e ? !0 : !1;
            }), d.children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
                return f ? !1 : e ? !0 : !1;
            });
        }), a("#wpbody-content").on({ focusin: function () {
            clearTimeout(s), t = a(this).find(".row-actions"), a(".row-actions").not(this).removeClass("visible"), t.addClass("visible");
        }, focusout: function () {
            s = setTimeout(function () {
                t.removeClass("visible");
            }, 30);
        } }, ".has-row-actions"), a("tbody").on("click", ".toggle-row", function () {
            a(this).closest("tr").toggleClass("is-expanded");
        }), a("#default-password-nag-no").click(function () {
            return setUserSetting("default_password_nag", "hide"), a("div.default-password-nag").hide(), !1;
        }), a("#newcontent").bind("keydown.wpevent_InsertTab", function (b) {
            var c, d, e, f, g, h = b.target;
            if (27 == b.keyCode)
                return b.preventDefault(), void a(h).data("tab-out", !0);
            if (!(9 != b.keyCode || b.ctrlKey || b.altKey || b.shiftKey)) {
                if (a(h).data("tab-out"))
                    return void a(h).data("tab-out", !1);
                c = h.selectionStart, d = h.selectionEnd, e = h.value, document.selection ? (h.focus(), g = document.selection.createRange(), g.text = "	") : c >= 0 && (f = this.scrollTop, h.value = e.substring(0, c).concat("	", e.substring(d)), h.selectionStart = h.selectionEnd = c + 1, this.scrollTop = f), b.stopPropagation && b.stopPropagation(), b.preventDefault && b.preventDefault();
            }
        }), v.length && v.closest("form").submit(function () {
            -1 == a('select[name="action"]').val() && -1 == a('select[name="action2"]').val() && v.val() == w && v.val("1");
        }), a('.search-box input[type="search"], .search-box input[type="submit"]').mousedown(function () {
            a('select[name^="action"]').val("-1");
        }), a("#contextual-help-link, #show-settings-link").on("focus.scroll-into-view", function (a) {
            a.target.scrollIntoView && a.target.scrollIntoView(!1);
        }), function () {
            function b() {
                c.prop("disabled", "" === d.map(function () {
                    return a(this).val();
                }).get().join(""));
            }
            var c, d, e = a("form.wp-upload-form");
            e.length && (c = e.find('input[type="submit"]'), d = e.find('input[type="file"]'), b(), d.on("change", b));
        }(), x || (e.on("scroll.pin-menu", i), d.on("tinymce-editor-init.pin-menu", function (a, b) {
            b.on("wp-autoresize", j);
        })), b.wpResponsive = { init: function () {
            var c = this;
            d.on("wp-responsive-activate.wp-responsive", function () {
                c.activate();
            }).on("wp-responsive-deactivate.wp-responsive", function () {
                c.deactivate();
            }), a("#wp-admin-bar-menu-toggle a").attr("aria-expanded", "false"), a("#wp-admin-bar-menu-toggle").on("click.wp-responsive", function (b) {
                b.preventDefault(), I.find(".hover").removeClass("hover"), B.toggleClass("wp-responsive-open"), B.hasClass("wp-responsive-open") ? (a(this).find("a").attr("aria-expanded", "true"), a("#adminmenu a:first").focus()) : a(this).find("a").attr("aria-expanded", "false");
            }), C.on("click.wp-responsive", "li.wp-has-submenu > a", function (b) {
                C.data("wp-responsive") && (a(this).parent("li").toggleClass("selected"), b.preventDefault());
            }), c.trigger(), d.on("wp-window-resized.wp-responsive", a.proxy(this.trigger, this)), e.on("load.wp-responsive", function () {
                var a = navigator.userAgent.indexOf("AppleWebKit/") > -1 ? e.width() : b.innerWidth;
                782 >= a && c.disableSortables();
            });
        }, activate: function () {
            l(), f.hasClass("auto-fold") || f.addClass("auto-fold"), C.data("wp-responsive", 1), this.disableSortables();
        }, deactivate: function () {
            l(), C.removeData("wp-responsive"), this.enableSortables();
        }, trigger: function () {
            var a;
            b.innerWidth && (a = Math.max(b.innerWidth, document.documentElement.clientWidth), 782 >= a ? H || (d.trigger("wp-responsive-activate"), H = !0) : H && (d.trigger("wp-responsive-deactivate"), H = !1), 480 >= a ? this.enableOverlay() : this.disableOverlay());
        }, enableOverlay: function () {
            0 === D.length && (D = a('<div id="wp-responsive-overlay"></div>').insertAfter("#wpcontent").hide().on("click.wp-responsive", function () {
                E.find(".menupop.hover").removeClass("hover"), a(this).hide();
            })), F.on("click.wp-responsive", function () {
                D.show();
            });
        }, disableOverlay: function () {
            F.off("click.wp-responsive"), D.hide();
        }, disableSortables: function () {
            if (G.length)
                try {
                    G.sortable("disable");
                }
                catch (a) {
                }
        }, enableSortables: function () {
            if (G.length)
                try {
                    G.sortable("enable");
                }
                catch (a) {
                }
        } }, b.wpResponsive.init(), l(), c(), h(), d.on("wp-pin-menu wp-window-resized.pin-menu postboxes-columnchange.pin-menu postbox-toggled.pin-menu wp-collapse-menu.pin-menu wp-scroll-start.pin-menu", l);
    }), function () {
        function a() {
            d.trigger("wp-window-resized");
        }
        function c() {
            b.clearTimeout(f), f = b.setTimeout(a, 200);
        }
        var f;
        e.on("resize.wp-fire-once", c);
    }(), function () {
        if ("-ms-user-select" in document.documentElement.style && navigator.userAgent.match(/IEMobile\/10\.0/)) {
            var a = document.createElement("style");
            a.appendChild(document.createTextNode("@-ms-viewport{width:auto!important}")), document.getElementsByTagName("head")[0].appendChild(a);
        }
    }();
}(jQuery, window);
"undefined" != typeof jQuery ? ("undefined" == typeof jQuery.fn.hoverIntent && !function (a) {
    a.fn.hoverIntent = function (b, c, d) {
        var e = { interval: 100, sensitivity: 6, timeout: 0 };
        e = "object" == typeof b ? a.extend(e, b) : a.isFunction(c) ? a.extend(e, { over: b, out: c, selector: d }) : a.extend(e, { over: b, out: b, selector: c });
        var f, g, h, i, j = function (a) {
            f = a.pageX, g = a.pageY;
        }, k = function (b, c) {
            return c.hoverIntent_t = clearTimeout(c.hoverIntent_t), Math.sqrt((h - f) * (h - f) + (i - g) * (i - g)) < e.sensitivity ? (a(c).off("mousemove.hoverIntent", j), c.hoverIntent_s = !0, e.over.apply(c, [b])) : (h = f, i = g, void (c.hoverIntent_t = setTimeout(function () {
                k(b, c);
            }, e.interval)));
        }, l = function (a, b) {
            return b.hoverIntent_t = clearTimeout(b.hoverIntent_t), b.hoverIntent_s = !1, e.out.apply(b, [a]);
        }, m = function (b) {
            var c = a.extend({}, b), d = this;
            d.hoverIntent_t && (d.hoverIntent_t = clearTimeout(d.hoverIntent_t)), "mouseenter" === b.type ? (h = c.pageX, i = c.pageY, a(d).on("mousemove.hoverIntent", j), d.hoverIntent_s || (d.hoverIntent_t = setTimeout(function () {
                k(c, d);
            }, e.interval))) : (a(d).off("mousemove.hoverIntent", j), d.hoverIntent_s && (d.hoverIntent_t = setTimeout(function () {
                l(c, d);
            }, e.timeout)));
        };
        return this.on({ "mouseenter.hoverIntent": m, "mouseleave.hoverIntent": m }, e.selector);
    };
}(jQuery), jQuery(document).ready(function (a) {
    var b, c, d, e = a("#wpadminbar"), f = !1;
    b = function (b, c) {
        var d = a(c), e = d.attr("tabindex");
        e && d.attr("tabindex", "0").attr("tabindex", e);
    }, c = function (b) {
        e.find("li.menupop").on("click.wp-mobile-hover", function (c) {
            var d = a(this);
            d.parent().is("#wp-admin-bar-root-default") && !d.hasClass("hover") ? (c.preventDefault(), e.find("li.menupop.hover").removeClass("hover"), d.addClass("hover")) : d.hasClass("hover") ? a(c.target).closest("div").hasClass("ab-sub-wrapper") || (c.stopPropagation(), c.preventDefault(), d.removeClass("hover")) : (c.stopPropagation(), c.preventDefault(), d.addClass("hover")), b && (a("li.menupop").off("click.wp-mobile-hover"), f = !1);
        });
    }, d = function () {
        var b = /Mobile\/.+Safari/.test(navigator.userAgent) ? "touchstart" : "click";
        a(document.body).on(b + ".wp-mobile-hover", function (b) {
            a(b.target).closest("#wpadminbar").length || e.find("li.menupop.hover").removeClass("hover");
        });
    }, e.removeClass("nojq").removeClass("nojs"), "ontouchstart" in window ? (e.on("touchstart", function () {
        c(!0), f = !0;
    }), d()) : /IEMobile\/[1-9]/.test(navigator.userAgent) && (c(), d()), e.find("li.menupop").hoverIntent({ over: function () {
        f || a(this).addClass("hover");
    }, out: function () {
        f || a(this).removeClass("hover");
    }, timeout: 180, sensitivity: 7, interval: 100 }), window.location.hash && window.scrollBy(0, -32), a("#wp-admin-bar-get-shortlink").click(function (b) {
        b.preventDefault(), a(this).addClass("selected").children(".shortlink-input").blur(function () {
            a(this).parents("#wp-admin-bar-get-shortlink").removeClass("selected");
        }).focus().select();
    }), a("#wpadminbar li.menupop > .ab-item").bind("keydown.adminbar", function (c) {
        if (13 == c.which) {
            var d = a(c.target), e = d.closest(".ab-sub-wrapper"), f = d.parent().hasClass("hover");
            c.stopPropagation(), c.preventDefault(), e.length || (e = a("#wpadminbar .quicklinks")), e.find(".menupop").removeClass("hover"), f || d.parent().toggleClass("hover"), d.siblings(".ab-sub-wrapper").find(".ab-item").each(b);
        }
    }).each(b), a("#wpadminbar .ab-item").bind("keydown.adminbar", function (c) {
        if (27 == c.which) {
            var d = a(c.target);
            c.stopPropagation(), c.preventDefault(), d.closest(".hover").removeClass("hover").children(".ab-item").focus(), d.siblings(".ab-sub-wrapper").find(".ab-item").each(b);
        }
    }), e.click(function (b) {
        ("wpadminbar" == b.target.id || "wp-admin-bar-top-secondary" == b.target.id) && (e.find("li.menupop.hover").removeClass("hover"), a("html, body").animate({ scrollTop: 0 }, "fast"), b.preventDefault());
    }), a(".screen-reader-shortcut").keydown(function (b) {
        var c, d;
        13 == b.which && (c = a(this).attr("href"), d = navigator.userAgent.toLowerCase(), -1 != d.indexOf("applewebkit") && c && "#" == c.charAt(0) && setTimeout(function () {
            a(c).focus();
        }, 100));
    }), a("#adminbar-search").on({ focus: function () {
        a("#adminbarsearch").addClass("adminbar-focused");
    }, blur: function () {
        a("#adminbarsearch").removeClass("adminbar-focused");
    } }), "sessionStorage" in window && a("#wp-admin-bar-logout a").click(function () {
        try {
            for (var a in sessionStorage)
                -1 != a.indexOf("wp-autosave-") && sessionStorage.removeItem(a);
        }
        catch (b) {
        }
    }), navigator.userAgent && -1 === document.body.className.indexOf("no-font-face") && /Android (1.0|1.1|1.5|1.6|2.0|2.1)|Nokia|Opera Mini|w(eb)?OSBrowser|webOS|UCWEB|Windows Phone OS 7|XBLWP7|ZuneWP7|MSIE 7/.test(navigator.userAgent) && (document.body.className += " no-font-face");
})) : !function (a, b) {
    var c, d = function (a, b, c) {
        a.addEventListener ? a.addEventListener(b, c, !1) : a.attachEvent && a.attachEvent("on" + b, function () {
            return c.call(a, window.event);
        });
    }, e = new RegExp("\\bhover\\b", "g"), f = [], g = new RegExp("\\bselected\\b", "g"), h = function (a) {
        for (var b = f.length; b--;)
            if (f[b] && a == f[b][1])
                return f[b][0];
        return !1;
    }, i = function (b) {
        for (var d, i, j, k, l, m, n = [], o = 0; b && b != c && b != a;)
            "LI" == b.nodeName.toUpperCase() && (n[n.length] = b, i = h(b), i && clearTimeout(i), b.className = b.className ? b.className.replace(e, "") + " hover" : "hover", k = b), b = b.parentNode;
        if (k && k.parentNode && (l = k.parentNode, l && "UL" == l.nodeName.toUpperCase()))
            for (d = l.childNodes.length; d--;)
                m = l.childNodes[d], m != k && (m.className = m.className ? m.className.replace(g, "") : "");
        for (d = f.length; d--;) {
            for (j = !1, o = n.length; o--;)
                n[o] == f[d][1] && (j = !0);
            j || (f[d][1].className = f[d][1].className ? f[d][1].className.replace(e, "") : "");
        }
    }, j = function (b) {
        for (; b && b != c && b != a;)
            "LI" == b.nodeName.toUpperCase() && !function (a) {
                var b = setTimeout(function () {
                    a.className = a.className ? a.className.replace(e, "") : "";
                }, 500);
                f[f.length] = [b, a];
            }(b), b = b.parentNode;
    }, k = function (b) {
        for (var d, e, f, h = b.target || b.srcElement;;) {
            if (!h || h == a || h == c)
                return;
            if (h.id && "wp-admin-bar-get-shortlink" == h.id)
                break;
            h = h.parentNode;
        }
        for (b.preventDefault && b.preventDefault(), b.returnValue = !1, -1 == h.className.indexOf("selected") && (h.className += " selected"), d = 0, e = h.childNodes.length; e > d; d++)
            if (f = h.childNodes[d], f.className && -1 != f.className.indexOf("shortlink-input")) {
                f.focus(), f.select(), f.onblur = function () {
                    h.className = h.className ? h.className.replace(g, "") : "";
                };
                break;
            }
        return !1;
    }, l = function (a) {
        var b, c, d, e, f, g;
        if (!("wpadminbar" != a.id && "wp-admin-bar-top-secondary" != a.id || (b = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0, 1 > b)))
            for (g = b > 800 ? 130 : 100, c = Math.min(12, Math.round(b / g)), d = b > 800 ? Math.round(b / 30) : Math.round(b / 20), e = [], f = 0; b;)
                b -= d, 0 > b && (b = 0), e.push(b), setTimeout(function () {
                    window.scrollTo(0, e.shift());
                }, f * c), f++;
    };
    d(b, "load", function () {
        c = a.getElementById("wpadminbar"), a.body && c && (a.body.appendChild(c), c.className && (c.className = c.className.replace(/nojs/, "")), d(c, "mouseover", function (a) {
            i(a.target || a.srcElement);
        }), d(c, "mouseout", function (a) {
            j(a.target || a.srcElement);
        }), d(c, "click", k), d(c, "click", function (a) {
            l(a.target || a.srcElement);
        }), d(document.getElementById("wp-admin-bar-logout"), "click", function () {
            if ("sessionStorage" in window)
                try {
                    for (var a in sessionStorage)
                        -1 != a.indexOf("wp-autosave-") && sessionStorage.removeItem(a);
                }
                catch (b) {
                }
        })), b.location.hash && b.scrollBy(0, -32), navigator.userAgent && -1 === document.body.className.indexOf("no-font-face") && /Android (1.0|1.1|1.5|1.6|2.0|2.1)|Nokia|Opera Mini|w(eb)?OSBrowser|webOS|UCWEB|Windows Phone OS 7|XBLWP7|ZuneWP7|MSIE 7/.test(navigator.userAgent) && (document.body.className += " no-font-face");
    });
}(document, window);
var wpAjax = jQuery.extend({ unserialize: function (a) {
    var b, c, d, e, f = {};
    if (!a)
        return f;
    b = a.split("?"), b[1] && (a = b[1]), c = a.split("&");
    for (d in c)
        (!jQuery.isFunction(c.hasOwnProperty) || c.hasOwnProperty(d)) && (e = c[d].split("="), f[e[0]] = e[1]);
    return f;
}, parseAjaxResponse: function (a, b, c) {
    var d = {}, e = jQuery("#" + b).empty(), f = "";
    return a && "object" == typeof a && a.getElementsByTagName("wp_ajax") ? (d.responses = [], d.errors = !1, jQuery("response", a).each(function () {
        var b, e = jQuery(this), g = jQuery(this.firstChild);
        b = { action: e.attr("action"), what: g.get(0).nodeName, id: g.attr("id"), oldId: g.attr("old_id"), position: g.attr("position") }, b.data = jQuery("response_data", g).text(), b.supplemental = {}, jQuery("supplemental", g).children().each(function () {
            b.supplemental[this.nodeName] = jQuery(this).text();
        }).size() || (b.supplemental = !1), b.errors = [], jQuery("wp_error", g).each(function () {
            var e, g, h, i = jQuery(this).attr("code");
            e = { code: i, message: this.firstChild.nodeValue, data: !1 }, g = jQuery('wp_error_data[code="' + i + '"]', a), g && (e.data = g.get()), h = jQuery("form-field", g).text(), h && (i = h), c && wpAjax.invalidateForm(jQuery("#" + c + ' :input[name="' + i + '"]').parents(".form-field:first")), f += "<p>" + e.message + "</p>", b.errors.push(e), d.errors = !0;
        }).size() || (b.errors = !1), d.responses.push(b);
    }), f.length && e.html('<div class="error">' + f + "</div>"), d) : isNaN(a) ? !e.html('<div class="error"><p>' + a + "</p></div>") : (a = parseInt(a, 10), -1 == a ? !e.html('<div class="error"><p>' + wpAjax.noPerm + "</p></div>") : 0 === a ? !e.html('<div class="error"><p>' + wpAjax.broken + "</p></div>") : !0);
}, invalidateForm: function (a) {
    return jQuery(a).addClass("form-invalid").find("input").one("change wp-check-valid-field", function () {
        jQuery(this).closest(".form-invalid").removeClass("form-invalid");
    });
}, validateForm: function (a) {
    return a = jQuery(a), !wpAjax.invalidateForm(a.find(".form-required").filter(function () {
        return "" === jQuery("input:visible", this).val();
    })).size();
} }, wpAjax || { noPerm: "You do not have permission to do that.", broken: "An unidentified error has occurred." });
jQuery(document).ready(function (a) {
    a("form.validate").submit(function () {
        return wpAjax.validateForm(a(this));
    });
});
/*! jQuery Color v@2.1.1 with SVG Color Names http://github.com/jquery/jquery-color | jquery.org/license */
(function (a, b) {
    function m(a, b, c) {
        var d = h[b.type] || {};
        return a == null ? c || !b.def ? null : b.def : (a = d.floor ? ~~a : parseFloat(a), isNaN(a) ? b.def : d.mod ? (a + d.mod) % d.mod : 0 > a ? 0 : d.max < a ? d.max : a);
    }
    function n(b) {
        var c = f(), d = c._rgba = [];
        return b = b.toLowerCase(), l(e, function (a, e) {
            var f, h = e.re.exec(b), i = h && e.parse(h), j = e.space || "rgba";
            if (i)
                return f = c[j](i), c[g[j].cache] = f[g[j].cache], d = c._rgba = f._rgba, !1;
        }), d.length ? (d.join() === "0,0,0,0" && a.extend(d, k.transparent), c) : k[b];
    }
    function o(a, b, c) {
        return c = (c + 1) % 1, c * 6 < 1 ? a + (b - a) * c * 6 : c * 2 < 1 ? b : c * 3 < 2 ? a + (b - a) * (2 / 3 - c) * 6 : a;
    }
    var c = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor", d = /^([\-+])=\s*(\d+\.?\d*)/, e = [{ re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/, parse: function (a) {
        return [a[1], a[2], a[3], a[4]];
    } }, { re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/, parse: function (a) {
        return [a[1] * 2.55, a[2] * 2.55, a[3] * 2.55, a[4]];
    } }, { re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/, parse: function (a) {
        return [parseInt(a[1], 16), parseInt(a[2], 16), parseInt(a[3], 16)];
    } }, { re: /#([a-f0-9])([a-f0-9])([a-f0-9])/, parse: function (a) {
        return [parseInt(a[1] + a[1], 16), parseInt(a[2] + a[2], 16), parseInt(a[3] + a[3], 16)];
    } }, { re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/, space: "hsla", parse: function (a) {
        return [a[1], a[2] / 100, a[3] / 100, a[4]];
    } }], f = a.Color = function (b, c, d, e) {
        return new a.Color.fn.parse(b, c, d, e);
    }, g = { rgba: { props: { red: { idx: 0, type: "byte" }, green: { idx: 1, type: "byte" }, blue: { idx: 2, type: "byte" } } }, hsla: { props: { hue: { idx: 0, type: "degrees" }, saturation: { idx: 1, type: "percent" }, lightness: { idx: 2, type: "percent" } } } }, h = { "byte": { floor: !0, max: 255 }, percent: { max: 1 }, degrees: { mod: 360, floor: !0 } }, i = f.support = {}, j = a("<p>")[0], k, l = a.each;
    j.style.cssText = "background-color:rgba(1,1,1,.5)", i.rgba = j.style.backgroundColor.indexOf("rgba") > -1, l(g, function (a, b) {
        b.cache = "_" + a, b.props.alpha = { idx: 3, type: "percent", def: 1 };
    }), f.fn = a.extend(f.prototype, { parse: function (c, d, e, h) {
        if (c === b)
            return this._rgba = [null, null, null, null], this;
        if (c.jquery || c.nodeType)
            c = a(c).css(d), d = b;
        var i = this, j = a.type(c), o = this._rgba = [];
        d !== b && (c = [c, d, e, h], j = "array");
        if (j === "string")
            return this.parse(n(c) || k._default);
        if (j === "array")
            return l(g.rgba.props, function (a, b) {
                o[b.idx] = m(c[b.idx], b);
            }), this;
        if (j === "object")
            return c instanceof f ? l(g, function (a, b) {
                c[b.cache] && (i[b.cache] = c[b.cache].slice());
            }) : l(g, function (b, d) {
                var e = d.cache;
                l(d.props, function (a, b) {
                    if (!i[e] && d.to) {
                        if (a === "alpha" || c[a] == null)
                            return;
                        i[e] = d.to(i._rgba);
                    }
                    i[e][b.idx] = m(c[a], b, !0);
                }), i[e] && a.inArray(null, i[e].slice(0, 3)) < 0 && (i[e][3] = 1, d.from && (i._rgba = d.from(i[e])));
            }), this;
    }, is: function (a) {
        var b = f(a), c = !0, d = this;
        return l(g, function (a, e) {
            var f, g = b[e.cache];
            return g && (f = d[e.cache] || e.to && e.to(d._rgba) || [], l(e.props, function (a, b) {
                if (g[b.idx] != null)
                    return c = g[b.idx] === f[b.idx], c;
            })), c;
        }), c;
    }, _space: function () {
        var a = [], b = this;
        return l(g, function (c, d) {
            b[d.cache] && a.push(c);
        }), a.pop();
    }, transition: function (a, b) {
        var c = f(a), d = c._space(), e = g[d], i = this.alpha() === 0 ? f("transparent") : this, j = i[e.cache] || e.to(i._rgba), k = j.slice();
        return c = c[e.cache], l(e.props, function (a, d) {
            var e = d.idx, f = j[e], g = c[e], i = h[d.type] || {};
            if (g === null)
                return;
            f === null ? k[e] = g : (i.mod && (g - f > i.mod / 2 ? f += i.mod : f - g > i.mod / 2 && (f -= i.mod)), k[e] = m((g - f) * b + f, d));
        }), this[d](k);
    }, blend: function (b) {
        if (this._rgba[3] === 1)
            return this;
        var c = this._rgba.slice(), d = c.pop(), e = f(b)._rgba;
        return f(a.map(c, function (a, b) {
            return (1 - d) * e[b] + d * a;
        }));
    }, toRgbaString: function () {
        var b = "rgba(", c = a.map(this._rgba, function (a, b) {
            return a == null ? b > 2 ? 1 : 0 : a;
        });
        return c[3] === 1 && (c.pop(), b = "rgb("), b + c.join() + ")";
    }, toHslaString: function () {
        var b = "hsla(", c = a.map(this.hsla(), function (a, b) {
            return a == null && (a = b > 2 ? 1 : 0), b && b < 3 && (a = Math.round(a * 100) + "%"), a;
        });
        return c[3] === 1 && (c.pop(), b = "hsl("), b + c.join() + ")";
    }, toHexString: function (b) {
        var c = this._rgba.slice(), d = c.pop();
        return b && c.push(~~(d * 255)), "#" + a.map(c, function (a) {
            return a = (a || 0).toString(16), a.length === 1 ? "0" + a : a;
        }).join("");
    }, toString: function () {
        return this._rgba[3] === 0 ? "transparent" : this.toRgbaString();
    } }), f.fn.parse.prototype = f.fn, g.hsla.to = function (a) {
        if (a[0] == null || a[1] == null || a[2] == null)
            return [null, null, null, a[3]];
        var b = a[0] / 255, c = a[1] / 255, d = a[2] / 255, e = a[3], f = Math.max(b, c, d), g = Math.min(b, c, d), h = f - g, i = f + g, j = i * .5, k, l;
        return g === f ? k = 0 : b === f ? k = 60 * (c - d) / h + 360 : c === f ? k = 60 * (d - b) / h + 120 : k = 60 * (b - c) / h + 240, h === 0 ? l = 0 : j <= .5 ? l = h / i : l = h / (2 - i), [Math.round(k) % 360, l, j, e == null ? 1 : e];
    }, g.hsla.from = function (a) {
        if (a[0] == null || a[1] == null || a[2] == null)
            return [null, null, null, a[3]];
        var b = a[0] / 360, c = a[1], d = a[2], e = a[3], f = d <= .5 ? d * (1 + c) : d + c - d * c, g = 2 * d - f;
        return [Math.round(o(g, f, b + 1 / 3) * 255), Math.round(o(g, f, b) * 255), Math.round(o(g, f, b - 1 / 3) * 255), e];
    }, l(g, function (c, e) {
        var g = e.props, h = e.cache, i = e.to, j = e.from;
        f.fn[c] = function (c) {
            i && !this[h] && (this[h] = i(this._rgba));
            if (c === b)
                return this[h].slice();
            var d, e = a.type(c), k = e === "array" || e === "object" ? c : arguments, n = this[h].slice();
            return l(g, function (a, b) {
                var c = k[e === "object" ? a : b.idx];
                c == null && (c = n[b.idx]), n[b.idx] = m(c, b);
            }), j ? (d = f(j(n)), d[h] = n, d) : f(n);
        }, l(g, function (b, e) {
            if (f.fn[b])
                return;
            f.fn[b] = function (f) {
                var g = a.type(f), h = b === "alpha" ? this._hsla ? "hsla" : "rgba" : c, i = this[h](), j = i[e.idx], k;
                return g === "undefined" ? j : (g === "function" && (f = f.call(this, j), g = a.type(f)), f == null && e.empty ? this : (g === "string" && (k = d.exec(f), k && (f = j + parseFloat(k[2]) * (k[1] === "+" ? 1 : -1))), i[e.idx] = f, this[h](i)));
            };
        });
    }), f.hook = function (b) {
        var c = b.split(" ");
        l(c, function (b, c) {
            a.cssHooks[c] = { set: function (b, d) {
                var e, g, h = "";
                if (a.type(d) !== "string" || (e = n(d))) {
                    d = f(e || d);
                    if (!i.rgba && d._rgba[3] !== 1) {
                        g = c === "backgroundColor" ? b.parentNode : b;
                        while ((h === "" || h === "transparent") && g && g.style)
                            try {
                                h = a.css(g, "backgroundColor"), g = g.parentNode;
                            }
                            catch (j) {
                            }
                        d = d.blend(h && h !== "transparent" ? h : "_default");
                    }
                    d = d.toRgbaString();
                }
                try {
                    b.style[c] = d;
                }
                catch (j) {
                }
            } }, a.fx.step[c] = function (b) {
                b.colorInit || (b.start = f(b.elem, c), b.end = f(b.end), b.colorInit = !0), a.cssHooks[c].set(b.elem, b.start.transition(b.end, b.pos));
            };
        });
    }, f.hook(c), a.cssHooks.borderColor = { expand: function (a) {
        var b = {};
        return l(["Top", "Right", "Bottom", "Left"], function (c, d) {
            b["border" + d + "Color"] = a;
        }), b;
    } }, k = a.Color.names = { aqua: "#00ffff", black: "#000000", blue: "#0000ff", fuchsia: "#ff00ff", gray: "#808080", green: "#008000", lime: "#00ff00", maroon: "#800000", navy: "#000080", olive: "#808000", purple: "#800080", red: "#ff0000", silver: "#c0c0c0", teal: "#008080", white: "#ffffff", yellow: "#ffff00", transparent: [null, null, null, 0], _default: "#ffffff" };
})(jQuery), jQuery.extend(jQuery.Color.names, { aliceblue: "#f0f8ff", antiquewhite: "#faebd7", aquamarine: "#7fffd4", azure: "#f0ffff", beige: "#f5f5dc", bisque: "#ffe4c4", blanchedalmond: "#ffebcd", blueviolet: "#8a2be2", brown: "#a52a2a", burlywood: "#deb887", cadetblue: "#5f9ea0", chartreuse: "#7fff00", chocolate: "#d2691e", coral: "#ff7f50", cornflowerblue: "#6495ed", cornsilk: "#fff8dc", crimson: "#dc143c", cyan: "#00ffff", darkblue: "#00008b", darkcyan: "#008b8b", darkgoldenrod: "#b8860b", darkgray: "#a9a9a9", darkgreen: "#006400", darkgrey: "#a9a9a9", darkkhaki: "#bdb76b", darkmagenta: "#8b008b", darkolivegreen: "#556b2f", darkorange: "#ff8c00", darkorchid: "#9932cc", darkred: "#8b0000", darksalmon: "#e9967a", darkseagreen: "#8fbc8f", darkslateblue: "#483d8b", darkslategray: "#2f4f4f", darkslategrey: "#2f4f4f", darkturquoise: "#00ced1", darkviolet: "#9400d3", deeppink: "#ff1493", deepskyblue: "#00bfff", dimgray: "#696969", dimgrey: "#696969", dodgerblue: "#1e90ff", firebrick: "#b22222", floralwhite: "#fffaf0", forestgreen: "#228b22", gainsboro: "#dcdcdc", ghostwhite: "#f8f8ff", gold: "#ffd700", goldenrod: "#daa520", greenyellow: "#adff2f", grey: "#808080", honeydew: "#f0fff0", hotpink: "#ff69b4", indianred: "#cd5c5c", indigo: "#4b0082", ivory: "#fffff0", khaki: "#f0e68c", lavender: "#e6e6fa", lavenderblush: "#fff0f5", lawngreen: "#7cfc00", lemonchiffon: "#fffacd", lightblue: "#add8e6", lightcoral: "#f08080", lightcyan: "#e0ffff", lightgoldenrodyellow: "#fafad2", lightgray: "#d3d3d3", lightgreen: "#90ee90", lightgrey: "#d3d3d3", lightpink: "#ffb6c1", lightsalmon: "#ffa07a", lightseagreen: "#20b2aa", lightskyblue: "#87cefa", lightslategray: "#778899", lightslategrey: "#778899", lightsteelblue: "#b0c4de", lightyellow: "#ffffe0", limegreen: "#32cd32", linen: "#faf0e6", mediumaquamarine: "#66cdaa", mediumblue: "#0000cd", mediumorchid: "#ba55d3", mediumpurple: "#9370db", mediumseagreen: "#3cb371", mediumslateblue: "#7b68ee", mediumspringgreen: "#00fa9a", mediumturquoise: "#48d1cc", mediumvioletred: "#c71585", midnightblue: "#191970", mintcream: "#f5fffa", mistyrose: "#ffe4e1", moccasin: "#ffe4b5", navajowhite: "#ffdead", oldlace: "#fdf5e6", olivedrab: "#6b8e23", orange: "#ffa500", orangered: "#ff4500", orchid: "#da70d6", palegoldenrod: "#eee8aa", palegreen: "#98fb98", paleturquoise: "#afeeee", palevioletred: "#db7093", papayawhip: "#ffefd5", peachpuff: "#ffdab9", peru: "#cd853f", pink: "#ffc0cb", plum: "#dda0dd", powderblue: "#b0e0e6", rosybrown: "#bc8f8f", royalblue: "#4169e1", saddlebrown: "#8b4513", salmon: "#fa8072", sandybrown: "#f4a460", seagreen: "#2e8b57", seashell: "#fff5ee", sienna: "#a0522d", skyblue: "#87ceeb", slateblue: "#6a5acd", slategray: "#708090", slategrey: "#708090", snow: "#fffafa", springgreen: "#00ff7f", steelblue: "#4682b4", tan: "#d2b48c", thistle: "#d8bfd8", tomato: "#ff6347", turquoise: "#40e0d0", violet: "#ee82ee", wheat: "#f5deb3", whitesmoke: "#f5f5f5", yellowgreen: "#9acd32" });
!function (a) {
    var b, c = { add: "ajaxAdd", del: "ajaxDel", dim: "ajaxDim", process: "process", recolor: "recolor" };
    b = { settings: { url: ajaxurl, type: "POST", response: "ajax-response", what: "", alt: "alternate", altOffset: 0, addColor: null, delColor: null, dimAddColor: null, dimDelColor: null, confirm: null, addBefore: null, addAfter: null, delBefore: null, delAfter: null, dimBefore: null, dimAfter: null }, nonce: function (b, c) {
        var d = wpAjax.unserialize(b.attr("href"));
        return c.nonce || d._ajax_nonce || a("#" + c.element + ' input[name="_ajax_nonce"]').val() || d._wpnonce || a("#" + c.element + ' input[name="_wpnonce"]').val() || 0;
    }, parseData: function (b, c) {
        var d, e = [];
        try {
            d = a(b).attr("data-wp-lists") || "", d = d.match(new RegExp(c + ":[\\S]+")), d && (e = d[0].split(":"));
        }
        catch (f) {
        }
        return e;
    }, pre: function (b, c, d) {
        var e, f;
        return c = a.extend({}, this.wpList.settings, { element: null, nonce: 0, target: b.get(0) }, c || {}), a.isFunction(c.confirm) && ("add" != d && (e = a("#" + c.element).css("backgroundColor"), a("#" + c.element).css("backgroundColor", "#FF9966")), f = c.confirm.call(this, b, c, d, e), "add" != d && a("#" + c.element).css("backgroundColor", e), !f) ? !1 : c;
    }, ajaxAdd: function (c, d) {
        c = a(c), d = d || {};
        var e, f, g, h, i, j = this, k = b.parseData(c, "add");
        return d = b.pre.call(j, c, d, "add"), d.element = k[2] || c.attr("id") || d.element || null, k[3] ? d.addColor = "#" + k[3] : d.addColor = d.addColor || "#FFFF33", d ? c.is('[id="' + d.element + '-submit"]') ? d.element ? (d.action = "add-" + d.what, d.nonce = b.nonce(c, d), e = a("#" + d.element + " :input").not('[name="_ajax_nonce"], [name="_wpnonce"], [name="action"]'), (f = wpAjax.validateForm("#" + d.element)) ? (d.data = a.param(a.extend({ _ajax_nonce: d.nonce, action: d.action }, wpAjax.unserialize(k[4] || ""))), g = a.isFunction(e.fieldSerialize) ? e.fieldSerialize() : e.serialize(), g && (d.data += "&" + g), a.isFunction(d.addBefore) && (d = d.addBefore(d), !d) ? !0 : d.data.match(/_ajax_nonce=[a-f0-9]+/) ? (d.success = function (c) {
            return h = wpAjax.parseAjaxResponse(c, d.response, d.element), i = c, !h || h.errors ? !1 : !0 === h ? !0 : (jQuery.each(h.responses, function () {
                b.add.call(j, this.data, a.extend({}, d, { pos: this.position || 0, id: this.id || 0, oldId: this.oldId || null }));
            }), j.wpList.recolor(), a(j).trigger("wpListAddEnd", [d, j.wpList]), void b.clear.call(j, "#" + d.element));
        }, d.complete = function (b, c) {
            if (a.isFunction(d.addAfter)) {
                var e = a.extend({ xml: b, status: c, parsed: h }, d);
                d.addAfter(i, e);
            }
        }, a.ajax(d), !1) : !0) : !1) : !0 : !b.add.call(j, c, d) : !1;
    }, ajaxDel: function (c, d) {
        c = a(c), d = d || {};
        var e, f, g, h = this, i = b.parseData(c, "delete");
        return d = b.pre.call(h, c, d, "delete"), d.element = i[2] || d.element || null, i[3] ? d.delColor = "#" + i[3] : d.delColor = d.delColor || "#faa", d && d.element ? (d.action = "delete-" + d.what, d.nonce = b.nonce(c, d), d.data = a.extend({ action: d.action, id: d.element.split("-").pop(), _ajax_nonce: d.nonce }, wpAjax.unserialize(i[4] || "")), a.isFunction(d.delBefore) && (d = d.delBefore(d, h), !d) ? !0 : d.data._ajax_nonce ? (e = a("#" + d.element), "none" != d.delColor ? e.css("backgroundColor", d.delColor).fadeOut(350, function () {
            h.wpList.recolor(), a(h).trigger("wpListDelEnd", [d, h.wpList]);
        }) : (h.wpList.recolor(), a(h).trigger("wpListDelEnd", [d, h.wpList])), d.success = function (b) {
            return f = wpAjax.parseAjaxResponse(b, d.response, d.element), g = b, !f || f.errors ? (e.stop().stop().css("backgroundColor", "#faa").show().queue(function () {
                h.wpList.recolor(), a(this).dequeue();
            }), !1) : void 0;
        }, d.complete = function (b, c) {
            a.isFunction(d.delAfter) && e.queue(function () {
                var e = a.extend({ xml: b, status: c, parsed: f }, d);
                d.delAfter(g, e);
            }).dequeue();
        }, a.ajax(d), !1) : !0) : !1;
    }, ajaxDim: function (c, d) {
        if ("none" == a(c).parent().css("display"))
            return !1;
        c = a(c), d = d || {};
        var e, f, g, h, i, j, k = this, l = b.parseData(c, "dim");
        return d = b.pre.call(k, c, d, "dim"), d.element = l[2] || d.element || null, d.dimClass = l[3] || d.dimClass || null, l[4] ? d.dimAddColor = "#" + l[4] : d.dimAddColor = d.dimAddColor || "#FFFF33", l[5] ? d.dimDelColor = "#" + l[5] : d.dimDelColor = d.dimDelColor || "#FF3333", d && d.element && d.dimClass ? (d.action = "dim-" + d.what, d.nonce = b.nonce(c, d), d.data = a.extend({ action: d.action, id: d.element.split("-").pop(), dimClass: d.dimClass, _ajax_nonce: d.nonce }, wpAjax.unserialize(l[6] || "")), a.isFunction(d.dimBefore) && (d = d.dimBefore(d), !d) ? !0 : (e = a("#" + d.element), f = e.toggleClass(d.dimClass).is("." + d.dimClass), g = b.getColor(e), e.toggleClass(d.dimClass), h = f ? d.dimAddColor : d.dimDelColor, "none" != h ? e.animate({ backgroundColor: h }, "fast").queue(function () {
            e.toggleClass(d.dimClass), a(this).dequeue();
        }).animate({ backgroundColor: g }, { complete: function () {
            a(this).css("backgroundColor", ""), a(k).trigger("wpListDimEnd", [d, k.wpList]);
        } }) : a(k).trigger("wpListDimEnd", [d, k.wpList]), d.data._ajax_nonce ? (d.success = function (b) {
            return i = wpAjax.parseAjaxResponse(b, d.response, d.element), j = b, !i || i.errors ? (e.stop().stop().css("backgroundColor", "#FF3333")[f ? "removeClass" : "addClass"](d.dimClass).show().queue(function () {
                k.wpList.recolor(), a(this).dequeue();
            }), !1) : void 0;
        }, d.complete = function (b, c) {
            a.isFunction(d.dimAfter) && e.queue(function () {
                var e = a.extend({ xml: b, status: c, parsed: i }, d);
                d.dimAfter(j, e);
            }).dequeue();
        }, a.ajax(d), !1) : !0)) : !0;
    }, getColor: function (a) {
        var b = jQuery(a).css("backgroundColor");
        return b || "#ffffff";
    }, add: function (c, d) {
        c = a("string" == typeof c ? a.trim(c) : c);
        var e, f, g, h = a(this), i = !1, j = { pos: 0, id: 0, oldId: null };
        return "string" == typeof d && (d = { what: d }), d = a.extend(j, this.wpList.settings, d), c.size() && d.what ? (d.oldId && (i = a("#" + d.what + "-" + d.oldId)), !d.id || d.id == d.oldId && i && i.size() || a("#" + d.what + "-" + d.id).remove(), i && i.size() ? (i.before(c), i.remove()) : isNaN(d.pos) ? (e = "after", "-" == d.pos.substr(0, 1) && (d.pos = d.pos.substr(1), e = "before"), f = h.find("#" + d.pos), 1 === f.size() ? f[e](c) : h.append(c)) : ("comment" != d.what || 0 === a("#" + d.element).length) && (d.pos < 0 ? h.prepend(c) : h.append(c)), d.alt && ((h.children(":visible").index(c[0]) + d.altOffset) % 2 ? c.removeClass(d.alt) : c.addClass(d.alt)), "none" != d.addColor && (g = b.getColor(c), c.css("backgroundColor", d.addColor).animate({ backgroundColor: g }, { complete: function () {
            a(this).css("backgroundColor", "");
        } })), h.each(function () {
            this.wpList.process(c);
        }), c) : !1;
    }, clear: function (b) {
        var c, d, e = this;
        b = a(b), e.wpList && b.parents("#" + e.id).size() || b.find(":input").each(function () {
            a(this).parents(".form-no-clear").size() || (c = this.type.toLowerCase(), d = this.tagName.toLowerCase(), "text" == c || "password" == c || "textarea" == d ? this.value = "" : "checkbox" == c || "radio" == c ? this.checked = !1 : "select" == d && (this.selectedIndex = null));
        });
    }, process: function (b) {
        var c = this, d = a(b || document);
        d.delegate('form[data-wp-lists^="add:' + c.id + ':"]', "submit", function () {
            return c.wpList.add(this);
        }), d.delegate('a[data-wp-lists^="add:' + c.id + ':"], input[data-wp-lists^="add:' + c.id + ':"]', "click", function () {
            return c.wpList.add(this);
        }), d.delegate('[data-wp-lists^="delete:' + c.id + ':"]', "click", function () {
            return c.wpList.del(this);
        }), d.delegate('[data-wp-lists^="dim:' + c.id + ':"]', "click", function () {
            return c.wpList.dim(this);
        });
    }, recolor: function () {
        var b, c, d = this;
        d.wpList.settings.alt && (b = a(".list-item:visible", d), b.size() || (b = a(d).children(":visible")), c = [":even", ":odd"], d.wpList.settings.altOffset % 2 && c.reverse(), b.filter(c[0]).addClass(d.wpList.settings.alt).end().filter(c[1]).removeClass(d.wpList.settings.alt));
    }, init: function () {
        var a = this;
        a.wpList.process = function (b) {
            a.each(function () {
                this.wpList.process(b);
            });
        }, a.wpList.recolor = function () {
            a.each(function () {
                this.wpList.recolor();
            });
        };
    } }, a.fn.wpList = function (d) {
        return this.each(function () {
            var e = this;
            this.wpList = { settings: a.extend({}, b.settings, { what: b.parseData(this, "list")[1] || "" }, d) }, a.each(c, function (a, c) {
                e.wpList[a] = function (a, d) {
                    return b[c].call(e, a, d);
                };
            });
        }), b.init.call(this), this.wpList.process(), this;
    };
}(jQuery);
function quicktags(a) {
    return new QTags(a);
}
function edInsertContent(a, b) {
    return QTags.insertContent(b);
}
function edButton(a, b, c, d, e) {
    return QTags.addButton(a, b, c, d, e, "", -1);
}
var QTags, edCanvas, edButtons = [], edAddTag = function () {
}, edCheckOpenTags = function () {
}, edCloseAllTags = function () {
}, edInsertImage = function () {
}, edInsertLink = function () {
}, edInsertTag = function () {
}, edLink = function () {
}, edQuickLink = function () {
}, edRemoveTag = function () {
}, edShowButton = function () {
}, edShowLinks = function () {
}, edSpell = function () {
}, edToolbar = function () {
};
!function () {
    function a(a) {
        return a = a || "", a = a.replace(/&([^#])(?![a-z1-4]{1,8};)/gi, "&#038;$1"), a.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
    var b, c = function (a) {
        var b, d, e, f;
        "undefined" != typeof jQuery ? jQuery(document).ready(a) : (b = c, b.funcs = [], b.ready = function () {
            if (!b.isReady)
                for (b.isReady = !0, d = 0; d < b.funcs.length; d++)
                    b.funcs[d]();
        }, b.isReady ? a() : b.funcs.push(a), b.eventAttached || (document.addEventListener ? (e = function () {
            document.removeEventListener("DOMContentLoaded", e, !1), b.ready();
        }, document.addEventListener("DOMContentLoaded", e, !1), window.addEventListener("load", b.ready, !1)) : document.attachEvent && (e = function () {
            "complete" === document.readyState && (document.detachEvent("onreadystatechange", e), b.ready());
        }, document.attachEvent("onreadystatechange", e), window.attachEvent("onload", b.ready), (f = function () {
            try {
                document.documentElement.doScroll("left");
            }
            catch (a) {
                return void setTimeout(f, 50);
            }
            b.ready();
        })()), b.eventAttached = !0));
    }, d = function () {
        var a, b = new Date;
        return a = function (a) {
            var b = a.toString();
            return b.length < 2 && (b = "0" + b), b;
        }, b.getUTCFullYear() + "-" + a(b.getUTCMonth() + 1) + "-" + a(b.getUTCDate()) + "T" + a(b.getUTCHours()) + ":" + a(b.getUTCMinutes()) + ":" + a(b.getUTCSeconds()) + "+00:00";
    }();
    b = QTags = function (a) {
        if ("string" == typeof a)
            a = { id: a };
        else if ("object" != typeof a)
            return !1;
        var d, e, f, g, h, i = this, j = a.id, k = document.getElementById(j), l = "qt_" + j;
        return j && k ? (i.name = l, i.id = j, i.canvas = k, i.settings = a, "content" !== j || "string" != typeof adminpage || "post-new-php" !== adminpage && "post-php" !== adminpage ? f = l + "_toolbar" : (edCanvas = k, f = "ed_toolbar"), d = document.getElementById(f), d || (d = document.createElement("div"), d.id = f, d.className = "quicktags-toolbar"), k.parentNode.insertBefore(d, k), i.toolbar = d, e = function (a) {
            a = a || window.event;
            var b, c = a.target || a.srcElement, d = c.clientWidth || c.offsetWidth;
            d && / ed_button /.test(" " + c.className + " ") && (i.canvas = k = document.getElementById(j), b = c.id.replace(l + "_", ""), i.theButtons[b] && i.theButtons[b].callback.call(i.theButtons[b], c, k, i));
        }, h = function () {
            window.wpActiveEditor = j;
        }, g = document.getElementById("wp-" + j + "-wrap"), d.addEventListener ? (d.addEventListener("click", e, !1), g && g.addEventListener("click", h, !1)) : d.attachEvent && (d.attachEvent("onclick", e), g && g.attachEvent("onclick", h)), i.getButton = function (a) {
            return i.theButtons[a];
        }, i.getButtonElement = function (a) {
            return document.getElementById(l + "_" + a);
        }, b.instances[j] = i, void (b.instances[0] || (b.instances[0] = b.instances[j], c(function () {
            b._buttonsInit();
        })))) : !1;
    }, b.instances = {}, b.getInstance = function (a) {
        return b.instances[a];
    }, b._buttonsInit = function () {
        var a, c, d, e, f, g, h, i, j, k, l = this, m = ",strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,";
        for (g in l.instances)
            if ("0" !== g) {
                h = l.instances[g], a = h.canvas, c = h.name, d = h.settings, f = "", e = {}, k = "", d.buttons && (k = "," + d.buttons + ",");
                for (j in edButtons)
                    edButtons[j] && (i = edButtons[j].id, k && -1 !== m.indexOf("," + i + ",") && -1 === k.indexOf("," + i + ",") || edButtons[j].instance && edButtons[j].instance !== g || (e[i] = edButtons[j], edButtons[j].html && (f += edButtons[j].html(c + "_"))));
                k && -1 !== k.indexOf(",dfw,") && (e.dfw = new b.DFWButton, f += e.dfw.html(c + "_")), "rtl" === document.getElementsByTagName("html")[0].dir && (e.textdirection = new b.TextDirectionButton, f += e.textdirection.html(c + "_")), h.toolbar.innerHTML = f, h.theButtons = e, "undefined" != typeof jQuery && jQuery(document).triggerHandler("quicktags-init", [h]);
            }
        l.buttonsInitDone = !0;
    }, b.addButton = function (a, c, d, e, f, g, h, i, j) {
        var k;
        if (a && c) {
            if (h = h || 0, e = e || "", j = j || {}, "function" == typeof d)
                k = new b.Button(a, c, f, g, i, j), k.callback = d;
            else {
                if ("string" != typeof d)
                    return;
                k = new b.TagButton(a, c, d, e, f, g, i, j);
            }
            if (-1 === h)
                return k;
            if (h > 0) {
                for (; "undefined" != typeof edButtons[h];)
                    h++;
                edButtons[h] = k;
            }
            else
                edButtons[edButtons.length] = k;
            this.buttonsInitDone && this._buttonsInit();
        }
    }, b.insertContent = function (a) {
        var b, c, d, e, f, g = document.getElementById(wpActiveEditor);
        return g ? (document.selection ? (g.focus(), b = document.selection.createRange(), b.text = a, g.focus()) : g.selectionStart || 0 === g.selectionStart ? (f = g.value, c = g.selectionStart, d = g.selectionEnd, e = g.scrollTop, g.value = f.substring(0, c) + a + f.substring(d, f.length), g.selectionStart = c + a.length, g.selectionEnd = c + a.length, g.scrollTop = e, g.focus()) : (g.value += a, g.focus()), !0) : !1;
    }, b.Button = function (a, b, c, d, e, f) {
        this.id = a, this.display = b, this.access = "", this.title = d || "", this.instance = e || "", this.attr = f || {};
    }, b.Button.prototype.html = function (b) {
        var c, d, e, f = this.title ? ' title="' + a(this.title) + '"' : "", g = this.attr && this.attr.ariaLabel ? ' aria-label="' + a(this.attr.ariaLabel) + '"' : "", h = this.display ? ' value="' + a(this.display) + '"' : "", i = this.id ? ' id="' + a(b + this.id) + '"' : "", j = (e = window.wp) && e.editor && e.editor.dfw;
        return "fullscreen" === this.id ? '<button type="button"' + i + ' class="ed_button qt-dfw qt-fullscreen"' + f + g + "></button>" : "dfw" === this.id ? (c = j && j.isActive() ? "" : ' disabled="disabled"', d = j && j.isOn() ? " active" : "", '<button type="button"' + i + ' class="ed_button qt-dfw' + d + '"' + f + g + c + "></button>") : '<input type="button"' + i + ' class="ed_button button button-small"' + f + g + h + " />";
    }, b.Button.prototype.callback = function () {
    }, b.TagButton = function (a, c, d, e, f, g, h, i) {
        var j = this;
        b.Button.call(j, a, c, f, g, h, i), j.tagStart = d, j.tagEnd = e;
    }, b.TagButton.prototype = new b.Button, b.TagButton.prototype.openTag = function (a, b) {
        b.openTags || (b.openTags = []), this.tagEnd && (b.openTags.push(this.id), a.value = "/" + a.value, this.attr.ariaLabelClose && a.setAttribute("aria-label", this.attr.ariaLabelClose));
    }, b.TagButton.prototype.closeTag = function (a, b) {
        var c = this.isOpen(b);
        c !== !1 && b.openTags.splice(c, 1), a.value = this.display, this.attr.ariaLabel && a.setAttribute("aria-label", this.attr.ariaLabel);
    }, b.TagButton.prototype.isOpen = function (a) {
        var b = this, c = 0, d = !1;
        if (a.openTags)
            for (; d === !1 && c < a.openTags.length;)
                d = a.openTags[c] === b.id ? c : !1, c++;
        else
            d = !1;
        return d;
    }, b.TagButton.prototype.callback = function (a, b, c) {
        var d, e, f, g, h, i, j, k, l = this, m = b.value, n = m ? l.tagEnd : "";
        document.selection ? (b.focus(), k = document.selection.createRange(), k.text.length > 0 ? l.tagEnd ? k.text = l.tagStart + k.text + n : k.text = k.text + l.tagStart : l.tagEnd ? l.isOpen(c) === !1 ? (k.text = l.tagStart, l.openTag(a, c)) : (k.text = n, l.closeTag(a, c)) : k.text = l.tagStart, b.focus()) : b.selectionStart || 0 === b.selectionStart ? (d = b.selectionStart, e = b.selectionEnd, f = e, g = b.scrollTop, h = m.substring(0, d), i = m.substring(e, m.length), j = m.substring(d, e), d !== e ? l.tagEnd ? (b.value = h + l.tagStart + j + n + i, f += l.tagStart.length + n.length) : (b.value = h + j + l.tagStart + i, f += l.tagStart.length) : l.tagEnd ? l.isOpen(c) === !1 ? (b.value = h + l.tagStart + i, l.openTag(a, c), f = d + l.tagStart.length) : (b.value = h + n + i, f = d + n.length, l.closeTag(a, c)) : (b.value = h + l.tagStart + i, f = d + l.tagStart.length), b.selectionStart = f, b.selectionEnd = f, b.scrollTop = g, b.focus()) : (n ? l.isOpen(c) !== !1 ? (b.value += l.tagStart, l.openTag(a, c)) : (b.value += n, l.closeTag(a, c)) : b.value += l.tagStart, b.focus());
    }, b.SpellButton = function () {
    }, b.CloseButton = function () {
        b.Button.call(this, "close", quicktagsL10n.closeTags, "", quicktagsL10n.closeAllOpenTags);
    }, b.CloseButton.prototype = new b.Button, b._close = function (a, b, c) {
        var d, e, f = c.openTags;
        if (f)
            for (; f.length > 0;)
                d = c.getButton(f[f.length - 1]), e = document.getElementById(c.name + "_" + d.id), a ? d.callback.call(d, e, b, c) : d.closeTag(e, c);
    }, b.CloseButton.prototype.callback = b._close, b.closeAllTags = function (a) {
        var c = this.getInstance(a);
        b._close("", c.canvas, c);
    }, b.LinkButton = function () {
        var a = { ariaLabel: quicktagsL10n.link };
        b.TagButton.call(this, "link", "link", "", "</a>", "", "", "", a);
    }, b.LinkButton.prototype = new b.TagButton, b.LinkButton.prototype.callback = function (a, c, d, e) {
        var f, g = this;
        return "undefined" != typeof wpLink ? void wpLink.open(d.id) : (e || (e = "http://"), void (g.isOpen(d) === !1 ? (f = prompt(quicktagsL10n.enterURL, e), f && (g.tagStart = '<a href="' + f + '">', b.TagButton.prototype.callback.call(g, a, c, d))) : b.TagButton.prototype.callback.call(g, a, c, d)));
    }, b.ImgButton = function () {
        var a = { ariaLabel: quicktagsL10n.image };
        b.TagButton.call(this, "img", "img", "", "", "", "", "", a);
    }, b.ImgButton.prototype = new b.TagButton, b.ImgButton.prototype.callback = function (a, c, d, e) {
        e || (e = "http://");
        var f, g = prompt(quicktagsL10n.enterImageURL, e);
        g && (f = prompt(quicktagsL10n.enterImageDescription, ""), this.tagStart = '<img src="' + g + '" alt="' + f + '" />', b.TagButton.prototype.callback.call(this, a, c, d));
    }, b.DFWButton = function () {
        b.Button.call(this, "dfw", "", "f", quicktagsL10n.dfw);
    }, b.DFWButton.prototype = new b.Button, b.DFWButton.prototype.callback = function () {
        var a;
        (a = window.wp) && a.editor && a.editor.dfw && window.wp.editor.dfw.toggle();
    }, b.TextDirectionButton = function () {
        b.Button.call(this, "textdirection", quicktagsL10n.textdirection, "", quicktagsL10n.toggleTextdirection);
    }, b.TextDirectionButton.prototype = new b.Button, b.TextDirectionButton.prototype.callback = function (a, b) {
        var c = "rtl" === document.getElementsByTagName("html")[0].dir, d = b.style.direction;
        d || (d = c ? "rtl" : "ltr"), b.style.direction = "rtl" === d ? "ltr" : "rtl", b.focus();
    }, edButtons[10] = new b.TagButton("strong", "b", "<strong>", "</strong>", "", "", "", { ariaLabel: quicktagsL10n.strong, ariaLabelClose: quicktagsL10n.strongClose }), edButtons[20] = new b.TagButton("em", "i", "<em>", "</em>", "", "", "", { ariaLabel: quicktagsL10n.em, ariaLabelClose: quicktagsL10n.emClose }), edButtons[30] = new b.LinkButton, edButtons[40] = new b.TagButton("block", "b-quote", "\n\n<blockquote>", "</blockquote>\n\n", "", "", "", { ariaLabel: quicktagsL10n.blockquote, ariaLabelClose: quicktagsL10n.blockquoteClose }), edButtons[50] = new b.TagButton("del", "del", '<del datetime="' + d + '">', "</del>", "", "", "", { ariaLabel: quicktagsL10n.del, ariaLabelClose: quicktagsL10n.delClose }), edButtons[60] = new b.TagButton("ins", "ins", '<ins datetime="' + d + '">', "</ins>", "", "", "", { ariaLabel: quicktagsL10n.ins, ariaLabelClose: quicktagsL10n.insClose }), edButtons[70] = new b.ImgButton, edButtons[80] = new b.TagButton("ul", "ul", "<ul>\n", "</ul>\n\n", "", "", "", { ariaLabel: quicktagsL10n.ul, ariaLabelClose: quicktagsL10n.ulClose }), edButtons[90] = new b.TagButton("ol", "ol", "<ol>\n", "</ol>\n\n", "", "", "", { ariaLabel: quicktagsL10n.ol, ariaLabelClose: quicktagsL10n.olClose }), edButtons[100] = new b.TagButton("li", "li", "	<li>", "</li>\n", "", "", "", { ariaLabel: quicktagsL10n.li, ariaLabelClose: quicktagsL10n.liClose }), edButtons[110] = new b.TagButton("code", "code", "<code>", "</code>", "", "", "", { ariaLabel: quicktagsL10n.code, ariaLabelClose: quicktagsL10n.codeClose }), edButtons[120] = new b.TagButton("more", "more", "<!--more-->\n\n", "", "", "", "", { ariaLabel: quicktagsL10n.more }), edButtons[140] = new b.CloseButton;
}();
/**
 * jQuery.query - Query String Modification and Creation for jQuery
 * Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2009/8/13
 *
 * @author Blair Mitchelmore
 * @version 2.1.7
 *
 **/
new function (e) {
    var d = e.separator || "&";
    var c = e.spaces === false ? false : true;
    var a = e.suffix === false ? "" : "[]";
    var g = e.prefix === false ? false : true;
    var b = g ? e.hash === true ? "#" : "?" : "";
    var f = e.numbers === false ? false : true;
    jQuery.query = new function () {
        var h = function (m, l) {
            return m != undefined && m !== null && (!!l ? m.constructor == l : true);
        };
        var i = function (r) {
            var l, q = /\[([^[]*)\]/g, n = /^([^[]+)(\[.*\])?$/.exec(r), o = n[1], p = [];
            while (l = q.exec(n[2])) {
                p.push(l[1]);
            }
            return [o, p];
        };
        var k = function (s, r, q) {
            var t, p = r.shift();
            if (typeof s != "object") {
                s = null;
            }
            if (p === "") {
                if (!s) {
                    s = [];
                }
                if (h(s, Array)) {
                    s.push(r.length == 0 ? q : k(null, r.slice(0), q));
                }
                else {
                    if (h(s, Object)) {
                        var n = 0;
                        while (s[n++] != null) {
                        }
                        s[--n] = r.length == 0 ? q : k(s[n], r.slice(0), q);
                    }
                    else {
                        s = [];
                        s.push(r.length == 0 ? q : k(null, r.slice(0), q));
                    }
                }
            }
            else {
                if (p && p.match(/^\s*[0-9]+\s*$/)) {
                    var m = parseInt(p, 10);
                    if (!s) {
                        s = [];
                    }
                    s[m] = r.length == 0 ? q : k(s[m], r.slice(0), q);
                }
                else {
                    if (p) {
                        var m = p.replace(/^\s*|\s*$/g, "");
                        if (!s) {
                            s = {};
                        }
                        if (h(s, Array)) {
                            var l = {};
                            for (var n = 0; n < s.length; ++n) {
                                l[n] = s[n];
                            }
                            s = l;
                        }
                        s[m] = r.length == 0 ? q : k(s[m], r.slice(0), q);
                    }
                    else {
                        return q;
                    }
                }
            }
            return s;
        };
        var j = function (l) {
            var m = this;
            m.keys = {};
            if (l.queryObject) {
                jQuery.each(l.get(), function (n, o) {
                    m.SET(n, o);
                });
            }
            else {
                jQuery.each(arguments, function () {
                    var n = "" + this;
                    n = n.replace(/^[?#]/, "");
                    n = n.replace(/[;&]$/, "");
                    if (c) {
                        n = n.replace(/[+]/g, " ");
                    }
                    jQuery.each(n.split(/[&;]/), function () {
                        var o = decodeURIComponent(this.split("=")[0] || "");
                        var p = decodeURIComponent(this.split("=")[1] || "");
                        if (!o) {
                            return;
                        }
                        if (f) {
                            if (/^[+-]?[0-9]+\.[0-9]*$/.test(p)) {
                                p = parseFloat(p);
                            }
                            else {
                                if (/^[+-]?[0-9]+$/.test(p)) {
                                    p = parseInt(p, 10);
                                }
                            }
                        }
                        p = (!p && p !== 0) ? true : p;
                        if (p !== false && p !== true && typeof p != "number") {
                            p = p;
                        }
                        m.SET(o, p);
                    });
                });
            }
            return m;
        };
        j.prototype = { queryObject: true, has: function (l, m) {
            var n = this.get(l);
            return h(n, m);
        }, GET: function (m) {
            if (!h(m)) {
                return this.keys;
            }
            var l = i(m), n = l[0], p = l[1];
            var o = this.keys[n];
            while (o != null && p.length != 0) {
                o = o[p.shift()];
            }
            return typeof o == "number" ? o : o || "";
        }, get: function (l) {
            var m = this.GET(l);
            if (h(m, Object)) {
                return jQuery.extend(true, {}, m);
            }
            else {
                if (h(m, Array)) {
                    return m.slice(0);
                }
            }
            return m;
        }, SET: function (m, r) {
            var o = !h(r) ? null : r;
            var l = i(m), n = l[0], q = l[1];
            var p = this.keys[n];
            this.keys[n] = k(p, q.slice(0), o);
            return this;
        }, set: function (l, m) {
            return this.copy().SET(l, m);
        }, REMOVE: function (l) {
            return this.SET(l, null).COMPACT();
        }, remove: function (l) {
            return this.copy().REMOVE(l);
        }, EMPTY: function () {
            var l = this;
            jQuery.each(l.keys, function (m, n) {
                delete l.keys[m];
            });
            return l;
        }, load: function (l) {
            var n = l.replace(/^.*?[#](.+?)(?:\?.+)?$/, "$1");
            var m = l.replace(/^.*?[?](.+?)(?:#.+)?$/, "$1");
            return new j(l.length == m.length ? "" : m, l.length == n.length ? "" : n);
        }, empty: function () {
            return this.copy().EMPTY();
        }, copy: function () {
            return new j(this);
        }, COMPACT: function () {
            function l(o) {
                var n = typeof o == "object" ? h(o, Array) ? [] : {} : o;
                if (typeof o == "object") {
                    function m(r, p, q) {
                        if (h(r, Array)) {
                            r.push(q);
                        }
                        else {
                            r[p] = q;
                        }
                    }
                    jQuery.each(o, function (p, q) {
                        if (!h(q)) {
                            return true;
                        }
                        m(n, p, l(q));
                    });
                }
                return n;
            }
            this.keys = l(this.keys);
            return this;
        }, compact: function () {
            return this.copy().COMPACT();
        }, toString: function () {
            var n = 0, r = [], q = [], m = this;
            var o = function (s) {
                s = s + "";
                if (c) {
                    s = s.replace(/ /g, "+");
                }
                return encodeURIComponent(s);
            };
            var l = function (s, t, u) {
                if (!h(u) || u === false) {
                    return;
                }
                var v = [o(t)];
                if (u !== true) {
                    v.push("=");
                    v.push(o(u));
                }
                s.push(v.join(""));
            };
            var p = function (t, s) {
                var u = function (v) {
                    return !s || s == "" ? [v].join("") : [s, "[", v, "]"].join("");
                };
                jQuery.each(t, function (v, w) {
                    if (typeof w == "object") {
                        p(w, u(v));
                    }
                    else {
                        l(q, u(v), w);
                    }
                });
            };
            p(this.keys);
            if (q.length > 0) {
                r.push(b);
            }
            r.push(q.join(d));
            return r.join("");
        } };
        return new j(location.search, location.hash);
    };
}(jQuery.query || {});
var setCommentsList, theList, theExtraList, commentReply;
!function (a) {
    var b, c, d, e, f, g, h, i, j, k = document.title, l = a("#dashboard_right_now").length;
    b = function (a) {
        var b = parseInt(a.html().replace(/[^0-9]+/g, ""), 10);
        return isNaN(b) ? 0 : b;
    }, c = function (a, b) {
        var c = "";
        if (!isNaN(b)) {
            if (b = 1 > b ? "0" : b.toString(), b.length > 3) {
                for (; b.length > 3;)
                    c = thousandsSeparator + b.substr(b.length - 3) + c, b = b.substr(0, b.length - 3);
                b += c;
            }
            a.html(b);
        }
    }, f = function (e, f) {
        var g, h, i = ".post-com-count-" + f, j = "comment-count-no-comments", k = "comment-count-approved";
        d("span.approved-count", e), f && (g = a("span." + k, i), h = a("span." + j, i), g.each(function () {
            var d = a(this), f = b(d) + e;
            1 > f && (f = 0), 0 === f ? d.removeClass(k).addClass(j) : d.addClass(k).removeClass(j), c(d, f);
        }), h.each(function () {
            var b = a(this);
            e > 0 ? b.removeClass(j).addClass(k) : b.addClass(j).removeClass(k), c(b, e);
        }));
    }, d = function (d, e) {
        a(d).each(function () {
            var d = a(this), f = b(d) + e;
            1 > f && (f = 0), c(d, f);
        });
    }, h = function (b) {
        if (l && b && b.i18n_comments_text) {
            var c = a("#dashboard_right_now");
            a(".comment-count a", c).text(b.i18n_comments_text), a(".comment-mod-count a", c).text(b.i18n_moderation_text).parent()[b.in_moderation > 0 ? "removeClass" : "addClass"]("hidden");
        }
    }, g = function (d) {
        var e, f, g, h;
        j = j || new RegExp(adminCommentsL10n.docTitleCommentsCount.replace("%s", "\\([0-9" + thousandsSeparator + "]+\\)") + "?"), i = i || a("<div />"), e = k, h = j.exec(document.title), h ? (h = h[0], i.html(h), g = b(i) + d) : (i.html(0), g = d), g >= 1 ? (c(i, g), f = j.exec(document.title), f && (e = document.title.replace(f[0], adminCommentsL10n.docTitleCommentsCount.replace("%s", i.text()) + " "))) : (f = j.exec(e), f && (e = e.replace(f[0], adminCommentsL10n.docTitleComments))), document.title = e;
    }, e = function (d, e) {
        var f, h, i = ".post-com-count-" + e, j = "comment-count-no-pending", k = "post-com-count-no-pending", m = "comment-count-pending";
        l || g(d), a("span.pending-count").each(function () {
            var e = a(this), f = b(e) + d;
            1 > f && (f = 0), e.closest(".awaiting-mod")[0 === f ? "addClass" : "removeClass"]("count-0"), c(e, f);
        }), e && (f = a("span." + m, i), h = a("span." + j, i), f.each(function () {
            var e = a(this), f = b(e) + d;
            1 > f && (f = 0), 0 === f ? (e.parent().addClass(k), e.removeClass(m).addClass(j)) : (e.parent().removeClass(k), e.addClass(m).removeClass(j)), c(e, f);
        }), h.each(function () {
            var b = a(this);
            d > 0 ? (b.parent().removeClass(k), b.removeClass(j).addClass(m)) : (b.parent().addClass(k), b.addClass(j).removeClass(m)), c(b, d);
        }));
    }, setCommentsList = function () {
        var b, c, g, i, j, k, m, n, o, p = 0;
        b = a('input[name="_total"]', "#comments-form"), c = a('input[name="_per_page"]', "#comments-form"), g = a('input[name="_page"]', "#comments-form"), k = function (a, c, d) {
            p > c || (d && (p = c), b.val(a.toString()));
        }, i = function (b, c) {
            var d, g, i, j, k = a("#" + c.element);
            !0 !== c.parsed && (j = c.parsed.responses[0]), d = a("#replyrow"), g = a("#comment_ID", d).val(), i = a("#replybtn", d), k.is(".unapproved") ? (c.data.id == g && i.text(adminCommentsL10n.replyApprove), k.find("div.comment_status").html("0")) : (c.data.id == g && i.text(adminCommentsL10n.reply), k.find("div.comment_status").html("1")), o = a("#" + c.element).is("." + c.dimClass) ? 1 : -1, j ? (h(j.supplemental), e(o, j.supplemental.postId), f(-1 * o, j.supplemental.postId)) : (e(o), f(-1 * o));
        }, j = function (d, e) {
            var f, h, i, j, k, l, m, n = !1, o = a(d.target).attr("data-wp-lists");
            return d.data._total = b.val() || 0, d.data._per_page = c.val() || 0, d.data._page = g.val() || 0, d.data._url = document.location.href, d.data.comment_status = a('input[name="comment_status"]', "#comments-form").val(), -1 != o.indexOf(":trash=1") ? n = "trash" : -1 != o.indexOf(":spam=1") && (n = "spam"), n && (h = o.replace(/.*?comment-([0-9]+).*/, "$1"), i = a("#comment-" + h), f = a("#" + n + "-undo-holder").html(), i.find(".check-column :checkbox").prop("checked", !1), i.siblings("#replyrow").length && commentReply.cid == h && commentReply.close(), i.is("tr") ? (j = i.children(":visible").length, m = a(".author strong", i).text(), k = a('<tr id="undo-' + h + '" class="undo un' + n + '" style="display:none;"><td colspan="' + j + '">' + f + "</td></tr>")) : (m = a(".comment-author", i).text(), k = a('<div id="undo-' + h + '" style="display:none;" class="undo un' + n + '">' + f + "</div>")), i.before(k), a("strong", "#undo-" + h).text(m), l = a(".undo a", "#undo-" + h), l.attr("href", "comment.php?action=un" + n + "comment&c=" + h + "&_wpnonce=" + d.data._ajax_nonce), l.attr("data-wp-lists", "delete:the-comment-list:comment-" + h + "::un" + n + "=1"), l.attr("class", "vim-z vim-destructive"), a(".avatar", i).first().clone().prependTo("#undo-" + h + " ." + n + "-undo-inside"), l.click(function (b) {
                b.preventDefault(), e.wpList.del(this), a("#undo-" + h).css({ backgroundColor: "#ceb" }).fadeOut(350, function () {
                    a(this).remove(), a("#comment-" + h).css("backgroundColor", "").fadeIn(300, function () {
                        a(this).show();
                    });
                });
            })), d;
        }, m = function (c, g) {
            var i, j, m, o, q, r, s, t, u = !0 === g.parsed ? {} : g.parsed.responses[0], v = !0 === g.parsed ? "" : u.supplemental.status, w = !0 === g.parsed ? "" : u.supplemental.postId, x = !0 === g.parsed ? "" : u.supplemental, y = a(g.target).parent(), z = a("#" + g.element), A = z.hasClass("approved"), B = z.hasClass("unapproved"), C = z.hasClass("spam"), D = z.hasClass("trash");
            h(x), y.is("span.undo") ? y.hasClass("unspam") ? (q = -1, "trash" === v ? r = 1 : "1" === v ? t = 1 : "0" === v && (s = 1)) : y.hasClass("untrash") && (r = -1, "spam" === v ? q = 1 : "1" === v ? t = 1 : "0" === v && (s = 1)) : y.is("span.spam") ? (A ? t = -1 : B ? s = -1 : D && (r = -1), q = 1) : y.is("span.unspam") ? (A ? s = 1 : B ? t = 1 : D ? y.hasClass("approve") ? t = 1 : y.hasClass("unapprove") && (s = 1) : C && (y.hasClass("approve") ? t = 1 : y.hasClass("unapprove") && (s = 1)), q = -1) : y.is("span.trash") ? (A ? t = -1 : B ? s = -1 : C && (q = -1), r = 1) : y.is("span.untrash") ? (A ? s = 1 : B ? t = 1 : D && (y.hasClass("approve") ? t = 1 : y.hasClass("unapprove") && (s = 1)), r = -1) : y.is("span.approve:not(.unspam):not(.untrash)") ? (t = 1, s = -1) : y.is("span.unapprove:not(.unspam):not(.untrash)") ? (t = -1, s = 1) : y.is("span.delete") && (C ? q = -1 : D && (r = -1)), s && (e(s, w), d("span.all-count", s)), t && (f(t, w), d("span.all-count", t)), q && d("span.spam-count", q), r && d("span.trash-count", r), l || (j = b.val() ? parseInt(b.val(), 10) : 0, a(g.target).parent().is("span.undo") ? j++ : j--, 0 > j && (j = 0), "object" == typeof c ? u.supplemental.total_items_i18n && p < u.supplemental.time ? (i = u.supplemental.total_items_i18n || "", i && (a(".displaying-num").text(i), a(".total-pages").text(u.supplemental.total_pages_i18n), a(".tablenav-pages").find(".next-page, .last-page").toggleClass("disabled", u.supplemental.total_pages == a(".current-page").val())), k(j, u.supplemental.time, !0)) : u.supplemental.time && k(j, u.supplemental.time, !1) : k(j, c, !1)), theExtraList && 0 !== theExtraList.size() && 0 !== theExtraList.children().size() && (theList.get(0).wpList.add(theExtraList.children(":eq(0):not(.no-items)").remove().clone()), n(), m = a(":animated", "#the-comment-list"), o = function () {
                a("#the-comment-list tr:visible").length || theList.get(0).wpList.add(theExtraList.find(".no-items").clone());
            }, m.length ? m.promise().done(o) : o());
        }, n = function (b) {
            var c = a.query.get(), d = a(".total-pages").text(), e = a('input[name="_per_page"]', "#comments-form").val();
            c.paged || (c.paged = 1), c.paged > d || (b ? (theExtraList.empty(), c.number = Math.min(8, e)) : (c.number = 1, c.offset = Math.min(8, e) - 1), c.no_placeholder = !0, c.paged++, !0 === c.comment_type && (c.comment_type = ""), c = a.extend(c, { action: "fetch-list", list_args: list_args, _ajax_fetch_list_nonce: a("#_ajax_fetch_list_nonce").val() }), a.ajax({ url: ajaxurl, global: !1, dataType: "json", data: c, success: function (a) {
                theExtraList.get(0).wpList.add(a.rows);
            } }));
        }, theExtraList = a("#the-extra-comment-list").wpList({ alt: "", delColor: "none", addColor: "none" }), theList = a("#the-comment-list").wpList({ alt: "", delBefore: j, dimAfter: i, delAfter: m, addColor: "none" }).bind("wpListDelEnd", function (b, c) {
            var d = a(c.target).attr("data-wp-lists"), e = c.element.replace(/[^0-9]+/g, "");
            (-1 != d.indexOf(":trash=1") || -1 != d.indexOf(":spam=1")) && a("#undo-" + e).fadeIn(300, function () {
                a(this).show();
            });
        });
    }, commentReply = { cid: "", act: "", init: function () {
        var b = a("#replyrow");
        a("a.cancel", b).click(function () {
            return commentReply.revert();
        }), a("a.save", b).click(function () {
            return commentReply.send();
        }), a("input#author-name, input#author-email, input#author-url", b).keypress(function (a) {
            return 13 == a.which ? (commentReply.send(), a.preventDefault(), !1) : void 0;
        }), a("#the-comment-list .column-comment > p").dblclick(function () {
            commentReply.toggle(a(this).parent());
        }), a("#doaction, #doaction2, #post-query-submit").click(function () {
            a("#the-comment-list #replyrow").length > 0 && commentReply.close();
        }), this.comments_listing = a('#comments-form > input[name="comment_status"]').val() || "";
    }, addEvents: function (b) {
        b.each(function () {
            a(this).find(".column-comment > p").dblclick(function () {
                commentReply.toggle(a(this).parent());
            });
        });
    }, toggle: function (b) {
        "none" !== a(b).css("display") && (a("#replyrow").parent().is("#com-reply") || window.confirm(adminCommentsL10n.warnQuickEdit)) && a(b).find("a.vim-q").click();
    }, revert: function () {
        return a("#the-comment-list #replyrow").length < 1 ? !1 : (a("#replyrow").fadeOut("fast", function () {
            commentReply.close();
        }), !1);
    }, close: function () {
        var b, c = a("#replyrow");
        c.parent().is("#com-reply") || (this.cid && "edit-comment" == this.act && (b = a("#comment-" + this.cid), b.fadeIn(300, function () {
            b.show();
        }).css("backgroundColor", "")), "undefined" != typeof QTags && QTags.closeAllTags("replycontent"), a("#add-new-comment").css("display", ""), c.hide(), a("#com-reply").append(c), a("#replycontent").css("height", "").val(""), a("#edithead input").val(""), a(".error", c).empty().hide(), a(".spinner", c).removeClass("is-active"), this.cid = "");
    }, open: function (b, c, d) {
        var e, f, g, h, i, j = this, k = a("#comment-" + b), l = k.height(), m = 0;
        return j.close(), j.cid = b, e = a("#replyrow"), f = a("#inline-" + b), d = d || "replyto", g = "edit" == d ? "edit" : "replyto", g = j.act = g + "-comment", m = a("> th:visible, > td:visible", k).length, e.hasClass("inline-edit-row") && 0 !== m && a("td", e).attr("colspan", m), a("#action", e).val(g), a("#comment_post_ID", e).val(c), a("#comment_ID", e).val(b), "edit" == d ? (a("#author-name", e).val(a("div.author", f).text()), a("#author-email", e).val(a("div.author-email", f).text()), a("#author-url", e).val(a("div.author-url", f).text()), a("#status", e).val(a("div.comment_status", f).text()), a("#replycontent", e).val(a("textarea.comment", f).val()), a("#edithead, #editlegend, #savebtn", e).show(), a("#replyhead, #replybtn, #addhead, #addbtn", e).hide(), l > 120 && (i = l > 500 ? 500 : l, a("#replycontent", e).css("height", i + "px")), k.after(e).fadeOut("fast", function () {
            a("#replyrow").fadeIn(300, function () {
                a(this).show();
            });
        })) : "add" == d ? (a("#addhead, #addbtn", e).show(), a("#replyhead, #replybtn, #edithead, #editlegend, #savebtn", e).hide(), a("#the-comment-list").prepend(e), a("#replyrow").fadeIn(300)) : (h = a("#replybtn", e), a("#edithead, #editlegend, #savebtn, #addhead, #addbtn", e).hide(), a("#replyhead, #replybtn", e).show(), k.after(e), k.hasClass("unapproved") ? h.text(adminCommentsL10n.replyApprove) : h.text(adminCommentsL10n.reply), a("#replyrow").fadeIn(300, function () {
            a(this).show();
        })), setTimeout(function () {
            var b, c, d, e, f;
            b = a("#replyrow").offset().top, c = b + a("#replyrow").height(), d = window.pageYOffset || document.documentElement.scrollTop, e = document.documentElement.clientHeight || window.innerHeight || 0, f = d + e, c > f - 20 ? window.scroll(0, c - e + 35) : d > b - 20 && window.scroll(0, b - 35), a("#replycontent").focus().keyup(function (a) {
                27 == a.which && commentReply.revert();
            });
        }, 600), !1;
    }, send: function () {
        var b = {};
        return a("#replysubmit .error").hide(), a("#replysubmit .spinner").addClass("is-active"), a("#replyrow input").not(":button").each(function () {
            var c = a(this);
            b[c.attr("name")] = c.val();
        }), b.content = a("#replycontent").val(), b.id = b.comment_post_ID, b.comments_listing = this.comments_listing, b.p = a('[name="p"]').val(), a("#comment-" + a("#comment_ID").val()).hasClass("unapproved") && (b.approve_parent = 1), a.ajax({ type: "POST", url: ajaxurl, data: b, success: function (a) {
            commentReply.show(a);
        }, error: function (a) {
            commentReply.error(a);
        } }), !1;
    }, show: function (b) {
        var c, g, i, j, k, m = this;
        return "string" == typeof b ? (m.error({ responseText: b }), !1) : (c = wpAjax.parseAjaxResponse(b), c.errors ? (m.error({ responseText: wpAjax.broken }), !1) : (m.revert(), c = c.responses[0], i = "#comment-" + c.id, "edit-comment" == m.act && a(i).remove(), c.supplemental.parent_approved && (k = a("#comment-" + c.supplemental.parent_approved), e(-1, c.supplemental.parent_post_id), "moderated" == this.comments_listing) ? void k.animate({ backgroundColor: "#CCEEBB" }, 400, function () {
            k.fadeOut();
        }) : (c.supplemental.i18n_comments_text && (l ? h(c.supplemental) : (f(1, c.supplemental.parent_post_id), d("span.all-count", 1))), g = a.trim(c.data), a(g).hide(), a("#replyrow").after(g), i = a(i), m.addEvents(i), j = i.hasClass("unapproved") ? "#FFFFE0" : i.closest(".widefat, .postbox").css("backgroundColor"), void i.animate({ backgroundColor: "#CCEEBB" }, 300).animate({ backgroundColor: j }, 300, function () {
            k && k.length && k.animate({ backgroundColor: "#CCEEBB" }, 300).animate({ backgroundColor: j }, 300).removeClass("unapproved").addClass("approved").find("div.comment_status").html("1");
        }))));
    }, error: function (b) {
        var c = b.statusText;
        a("#replysubmit .spinner").removeClass("is-active"), b.responseText && (c = b.responseText.replace(/<.[^<>]*?>/g, "")), c && a("#replysubmit .error").html(c).show();
    }, addcomment: function (b) {
        var c = this;
        a("#add-new-comment").fadeOut(200, function () {
            c.open(0, b, "add"), a("table.comments-box").css("display", ""), a("#no-comments").remove();
        });
    } }, a(document).ready(function () {
        var b, c, d, e;
        setCommentsList(), commentReply.init(), a(document).on("click", "span.delete a.delete", function (a) {
            a.preventDefault();
        }), "undefined" != typeof a.table_hotkeys && (b = function (b) {
            return function () {
                var c, d;
                c = "next" == b ? "first" : "last", d = a(".tablenav-pages ." + b + "-page:not(.disabled)"), d.length && (window.location = d[0].href.replace(/\&hotkeys_highlight_(first|last)=1/g, "") + "&hotkeys_highlight_" + c + "=1");
            };
        }, c = function (b, c) {
            window.location = a("span.edit a", c).attr("href");
        }, d = function () {
            a("#cb-select-all-1").data("wp-toggle", 1).trigger("click").removeData("wp-toggle");
        }, e = function (b) {
            return function () {
                var c = a('select[name="action"]');
                a('option[value="' + b + '"]', c).prop("selected", !0), a("#doaction").click();
            };
        }, a.table_hotkeys(a("table.widefat"), ["a", "u", "s", "d", "r", "q", "z", ["e", c], ["shift+x", d], ["shift+a", e("approve")], ["shift+s", e("spam")], ["shift+d", e("delete")], ["shift+t", e("trash")], ["shift+z", e("untrash")], ["shift+u", e("unapprove")]], { highlight_first: adminCommentsL10n.hotkeys_highlight_first, highlight_last: adminCommentsL10n.hotkeys_highlight_last, prev_page_link_cb: b("prev"), next_page_link_cb: b("next"), hotkeys_opts: { disableInInput: !0, type: "keypress", noDisable: '.check-column input[type="checkbox"]' }, cycle_expr: "#the-comment-list tr", start_row_index: 0 })), a("#the-comment-list").on("click", ".comment-inline", function (b) {
            b.preventDefault();
            var c = a(this), d = "replyto";
            "undefined" != typeof c.data("action") && (d = c.data("action")), commentReply.open(c.data("commentId"), c.data("postId"), d);
        });
    });
}(jQuery);
/*!
 * jQuery UI Core 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/category/ui-core/
 */
!function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery);
}(function (a) {
    function b(b, d) {
        var e, f, g, h = b.nodeName.toLowerCase();
        return "area" === h ? (e = b.parentNode, f = e.name, b.href && f && "map" === e.nodeName.toLowerCase() ? (g = a("img[usemap='#" + f + "']")[0], !!g && c(g)) : !1) : (/^(input|select|textarea|button|object)$/.test(h) ? !b.disabled : "a" === h ? b.href || d : d) && c(b);
    }
    function c(b) {
        return a.expr.filters.visible(b) && !a(b).parents().addBack().filter(function () {
            return "hidden" === a.css(this, "visibility");
        }).length;
    }
    a.ui = a.ui || {}, a.extend(a.ui, { version: "1.11.4", keyCode: { BACKSPACE: 8, COMMA: 188, DELETE: 46, DOWN: 40, END: 35, ENTER: 13, ESCAPE: 27, HOME: 36, LEFT: 37, PAGE_DOWN: 34, PAGE_UP: 33, PERIOD: 190, RIGHT: 39, SPACE: 32, TAB: 9, UP: 38 } }), a.fn.extend({ scrollParent: function (b) {
        var c = this.css("position"), d = "absolute" === c, e = b ? /(auto|scroll|hidden)/ : /(auto|scroll)/, f = this.parents().filter(function () {
            var b = a(this);
            return d && "static" === b.css("position") ? !1 : e.test(b.css("overflow") + b.css("overflow-y") + b.css("overflow-x"));
        }).eq(0);
        return "fixed" !== c && f.length ? f : a(this[0].ownerDocument || document);
    }, uniqueId: function () {
        var a = 0;
        return function () {
            return this.each(function () {
                this.id || (this.id = "ui-id-" + ++a);
            });
        };
    }(), removeUniqueId: function () {
        return this.each(function () {
            /^ui-id-\d+$/.test(this.id) && a(this).removeAttr("id");
        });
    } }), a.extend(a.expr[":"], { data: a.expr.createPseudo ? a.expr.createPseudo(function (b) {
        return function (c) {
            return !!a.data(c, b);
        };
    }) : function (b, c, d) {
        return !!a.data(b, d[3]);
    }, focusable: function (c) {
        return b(c, !isNaN(a.attr(c, "tabindex")));
    }, tabbable: function (c) {
        var d = a.attr(c, "tabindex"), e = isNaN(d);
        return (e || d >= 0) && b(c, !e);
    } }), a("<a>").outerWidth(1).jquery || a.each(["Width", "Height"], function (b, c) {
        function d(b, c, d, f) {
            return a.each(e, function () {
                c -= parseFloat(a.css(b, "padding" + this)) || 0, d && (c -= parseFloat(a.css(b, "border" + this + "Width")) || 0), f && (c -= parseFloat(a.css(b, "margin" + this)) || 0);
            }), c;
        }
        var e = "Width" === c ? ["Left", "Right"] : ["Top", "Bottom"], f = c.toLowerCase(), g = { innerWidth: a.fn.innerWidth, innerHeight: a.fn.innerHeight, outerWidth: a.fn.outerWidth, outerHeight: a.fn.outerHeight };
        a.fn["inner" + c] = function (b) {
            return void 0 === b ? g["inner" + c].call(this) : this.each(function () {
                a(this).css(f, d(this, b) + "px");
            });
        }, a.fn["outer" + c] = function (b, e) {
            return "number" != typeof b ? g["outer" + c].call(this, b) : this.each(function () {
                a(this).css(f, d(this, b, !0, e) + "px");
            });
        };
    }), a.fn.addBack || (a.fn.addBack = function (a) {
        return this.add(null == a ? this.prevObject : this.prevObject.filter(a));
    }), a("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (a.fn.removeData = function (b) {
        return function (c) {
            return arguments.length ? b.call(this, a.camelCase(c)) : b.call(this);
        };
    }(a.fn.removeData)), a.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()), a.fn.extend({ focus: function (b) {
        return function (c, d) {
            return "number" == typeof c ? this.each(function () {
                var b = this;
                setTimeout(function () {
                    a(b).focus(), d && d.call(b);
                }, c);
            }) : b.apply(this, arguments);
        };
    }(a.fn.focus), disableSelection: function () {
        var a = "onselectstart" in document.createElement("div") ? "selectstart" : "mousedown";
        return function () {
            return this.bind(a + ".ui-disableSelection", function (a) {
                a.preventDefault();
            });
        };
    }(), enableSelection: function () {
        return this.unbind(".ui-disableSelection");
    }, zIndex: function (b) {
        if (void 0 !== b)
            return this.css("zIndex", b);
        if (this.length)
            for (var c, d, e = a(this[0]); e.length && e[0] !== document;) {
                if (c = e.css("position"), ("absolute" === c || "relative" === c || "fixed" === c) && (d = parseInt(e.css("zIndex"), 10), !isNaN(d) && 0 !== d))
                    return d;
                e = e.parent();
            }
        return 0;
    } }), a.ui.plugin = { add: function (b, c, d) {
        var e, f = a.ui[b].prototype;
        for (e in d)
            f.plugins[e] = f.plugins[e] || [], f.plugins[e].push([c, d[e]]);
    }, call: function (a, b, c, d) {
        var e, f = a.plugins[b];
        if (f && (d || a.element[0].parentNode && 11 !== a.element[0].parentNode.nodeType))
            for (e = 0; e < f.length; e++)
                a.options[f[e][0]] && f[e][1].apply(a.element, c);
    } };
});
/*!
 * jQuery UI Widget 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/jQuery.widget/
 */
!function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery);
}(function (a) {
    var b = 0, c = Array.prototype.slice;
    return a.cleanData = function (b) {
        return function (c) {
            var d, e, f;
            for (f = 0; null != (e = c[f]); f++)
                try {
                    d = a._data(e, "events"), d && d.remove && a(e).triggerHandler("remove");
                }
                catch (g) {
                }
            b(c);
        };
    }(a.cleanData), a.widget = function (b, c, d) {
        var e, f, g, h, i = {}, j = b.split(".")[0];
        return b = b.split(".")[1], e = j + "-" + b, d || (d = c, c = a.Widget), a.expr[":"][e.toLowerCase()] = function (b) {
            return !!a.data(b, e);
        }, a[j] = a[j] || {}, f = a[j][b], g = a[j][b] = function (a, b) {
            return this._createWidget ? void (arguments.length && this._createWidget(a, b)) : new g(a, b);
        }, a.extend(g, f, { version: d.version, _proto: a.extend({}, d), _childConstructors: [] }), h = new c, h.options = a.widget.extend({}, h.options), a.each(d, function (b, d) {
            return a.isFunction(d) ? void (i[b] = function () {
                var a = function () {
                    return c.prototype[b].apply(this, arguments);
                }, e = function (a) {
                    return c.prototype[b].apply(this, a);
                };
                return function () {
                    var b, c = this._super, f = this._superApply;
                    return this._super = a, this._superApply = e, b = d.apply(this, arguments), this._super = c, this._superApply = f, b;
                };
            }()) : void (i[b] = d);
        }), g.prototype = a.widget.extend(h, { widgetEventPrefix: f ? h.widgetEventPrefix || b : b }, i, { constructor: g, namespace: j, widgetName: b, widgetFullName: e }), f ? (a.each(f._childConstructors, function (b, c) {
            var d = c.prototype;
            a.widget(d.namespace + "." + d.widgetName, g, c._proto);
        }), delete f._childConstructors) : c._childConstructors.push(g), a.widget.bridge(b, g), g;
    }, a.widget.extend = function (b) {
        for (var d, e, f = c.call(arguments, 1), g = 0, h = f.length; h > g; g++)
            for (d in f[g])
                e = f[g][d], f[g].hasOwnProperty(d) && void 0 !== e && (a.isPlainObject(e) ? b[d] = a.isPlainObject(b[d]) ? a.widget.extend({}, b[d], e) : a.widget.extend({}, e) : b[d] = e);
        return b;
    }, a.widget.bridge = function (b, d) {
        var e = d.prototype.widgetFullName || b;
        a.fn[b] = function (f) {
            var g = "string" == typeof f, h = c.call(arguments, 1), i = this;
            return g ? this.each(function () {
                var c, d = a.data(this, e);
                return "instance" === f ? (i = d, !1) : d ? a.isFunction(d[f]) && "_" !== f.charAt(0) ? (c = d[f].apply(d, h), c !== d && void 0 !== c ? (i = c && c.jquery ? i.pushStack(c.get()) : c, !1) : void 0) : a.error("no such method '" + f + "' for " + b + " widget instance") : a.error("cannot call methods on " + b + " prior to initialization; attempted to call method '" + f + "'");
            }) : (h.length && (f = a.widget.extend.apply(null, [f].concat(h))), this.each(function () {
                var b = a.data(this, e);
                b ? (b.option(f || {}), b._init && b._init()) : a.data(this, e, new d(f, this));
            })), i;
        };
    }, a.Widget = function () {
    }, a.Widget._childConstructors = [], a.Widget.prototype = { widgetName: "widget", widgetEventPrefix: "", defaultElement: "<div>", options: { disabled: !1, create: null }, _createWidget: function (c, d) {
        d = a(d || this.defaultElement || this)[0], this.element = a(d), this.uuid = b++, this.eventNamespace = "." + this.widgetName + this.uuid, this.bindings = a(), this.hoverable = a(), this.focusable = a(), d !== this && (a.data(d, this.widgetFullName, this), this._on(!0, this.element, { remove: function (a) {
            a.target === d && this.destroy();
        } }), this.document = a(d.style ? d.ownerDocument : d.document || d), this.window = a(this.document[0].defaultView || this.document[0].parentWindow)), this.options = a.widget.extend({}, this.options, this._getCreateOptions(), c), this._create(), this._trigger("create", null, this._getCreateEventData()), this._init();
    }, _getCreateOptions: a.noop, _getCreateEventData: a.noop, _create: a.noop, _init: a.noop, destroy: function () {
        this._destroy(), this.element.unbind(this.eventNamespace).removeData(this.widgetFullName).removeData(a.camelCase(this.widgetFullName)), this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled ui-state-disabled"), this.bindings.unbind(this.eventNamespace), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus");
    }, _destroy: a.noop, widget: function () {
        return this.element;
    }, option: function (b, c) {
        var d, e, f, g = b;
        if (0 === arguments.length)
            return a.widget.extend({}, this.options);
        if ("string" == typeof b)
            if (g = {}, d = b.split("."), b = d.shift(), d.length) {
                for (e = g[b] = a.widget.extend({}, this.options[b]), f = 0; f < d.length - 1; f++)
                    e[d[f]] = e[d[f]] || {}, e = e[d[f]];
                if (b = d.pop(), 1 === arguments.length)
                    return void 0 === e[b] ? null : e[b];
                e[b] = c;
            }
            else {
                if (1 === arguments.length)
                    return void 0 === this.options[b] ? null : this.options[b];
                g[b] = c;
            }
        return this._setOptions(g), this;
    }, _setOptions: function (a) {
        var b;
        for (b in a)
            this._setOption(b, a[b]);
        return this;
    }, _setOption: function (a, b) {
        return this.options[a] = b, "disabled" === a && (this.widget().toggleClass(this.widgetFullName + "-disabled", !!b), b && (this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus"))), this;
    }, enable: function () {
        return this._setOptions({ disabled: !1 });
    }, disable: function () {
        return this._setOptions({ disabled: !0 });
    }, _on: function (b, c, d) {
        var e, f = this;
        "boolean" != typeof b && (d = c, c = b, b = !1), d ? (c = e = a(c), this.bindings = this.bindings.add(c)) : (d = c, c = this.element, e = this.widget()), a.each(d, function (d, g) {
            function h() {
                return b || f.options.disabled !== !0 && !a(this).hasClass("ui-state-disabled") ? ("string" == typeof g ? f[g] : g).apply(f, arguments) : void 0;
            }
            "string" != typeof g && (h.guid = g.guid = g.guid || h.guid || a.guid++);
            var i = d.match(/^([\w:-]*)\s*(.*)$/), j = i[1] + f.eventNamespace, k = i[2];
            k ? e.delegate(k, j, h) : c.bind(j, h);
        });
    }, _off: function (b, c) {
        c = (c || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace, b.unbind(c).undelegate(c), this.bindings = a(this.bindings.not(b).get()), this.focusable = a(this.focusable.not(b).get()), this.hoverable = a(this.hoverable.not(b).get());
    }, _delay: function (a, b) {
        function c() {
            return ("string" == typeof a ? d[a] : a).apply(d, arguments);
        }
        var d = this;
        return setTimeout(c, b || 0);
    }, _hoverable: function (b) {
        this.hoverable = this.hoverable.add(b), this._on(b, { mouseenter: function (b) {
            a(b.currentTarget).addClass("ui-state-hover");
        }, mouseleave: function (b) {
            a(b.currentTarget).removeClass("ui-state-hover");
        } });
    }, _focusable: function (b) {
        this.focusable = this.focusable.add(b), this._on(b, { focusin: function (b) {
            a(b.currentTarget).addClass("ui-state-focus");
        }, focusout: function (b) {
            a(b.currentTarget).removeClass("ui-state-focus");
        } });
    }, _trigger: function (b, c, d) {
        var e, f, g = this.options[b];
        if (d = d || {}, c = a.Event(c), c.type = (b === this.widgetEventPrefix ? b : this.widgetEventPrefix + b).toLowerCase(), c.target = this.element[0], f = c.originalEvent)
            for (e in f)
                e in c || (c[e] = f[e]);
        return this.element.trigger(c, d), !(a.isFunction(g) && g.apply(this.element[0], [c].concat(d)) === !1 || c.isDefaultPrevented());
    } }, a.each({ show: "fadeIn", hide: "fadeOut" }, function (b, c) {
        a.Widget.prototype["_" + b] = function (d, e, f) {
            "string" == typeof e && (e = { effect: e });
            var g, h = e ? e === !0 || "number" == typeof e ? c : e.effect || c : b;
            e = e || {}, "number" == typeof e && (e = { duration: e }), g = !a.isEmptyObject(e), e.complete = f, e.delay && d.delay(e.delay), g && a.effects && a.effects.effect[h] ? d[b](e) : h !== b && d[h] ? d[h](e.duration, e.easing, f) : d.queue(function (c) {
                a(this)[b](), f && f.call(d[0]), c();
            });
        };
    }), a.widget;
});
/*!
 * jQuery UI Mouse 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/mouse/
 */
!function (a) {
    "function" == typeof define && define.amd ? define(["jquery", "./widget"], a) : a(jQuery);
}(function (a) {
    var b = !1;
    return a(document).mouseup(function () {
        b = !1;
    }), a.widget("ui.mouse", { version: "1.11.4", options: { cancel: "input,textarea,button,select,option", distance: 1, delay: 0 }, _mouseInit: function () {
        var b = this;
        this.element.bind("mousedown." + this.widgetName, function (a) {
            return b._mouseDown(a);
        }).bind("click." + this.widgetName, function (c) {
            return !0 === a.data(c.target, b.widgetName + ".preventClickEvent") ? (a.removeData(c.target, b.widgetName + ".preventClickEvent"), c.stopImmediatePropagation(), !1) : void 0;
        }), this.started = !1;
    }, _mouseDestroy: function () {
        this.element.unbind("." + this.widgetName), this._mouseMoveDelegate && this.document.unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate);
    }, _mouseDown: function (c) {
        if (!b) {
            this._mouseMoved = !1, this._mouseStarted && this._mouseUp(c), this._mouseDownEvent = c;
            var d = this, e = 1 === c.which, f = "string" == typeof this.options.cancel && c.target.nodeName ? a(c.target).closest(this.options.cancel).length : !1;
            return e && !f && this._mouseCapture(c) ? (this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function () {
                d.mouseDelayMet = !0;
            }, this.options.delay)), this._mouseDistanceMet(c) && this._mouseDelayMet(c) && (this._mouseStarted = this._mouseStart(c) !== !1, !this._mouseStarted) ? (c.preventDefault(), !0) : (!0 === a.data(c.target, this.widgetName + ".preventClickEvent") && a.removeData(c.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function (a) {
                return d._mouseMove(a);
            }, this._mouseUpDelegate = function (a) {
                return d._mouseUp(a);
            }, this.document.bind("mousemove." + this.widgetName, this._mouseMoveDelegate).bind("mouseup." + this.widgetName, this._mouseUpDelegate), c.preventDefault(), b = !0, !0)) : !0;
        }
    }, _mouseMove: function (b) {
        if (this._mouseMoved) {
            if (a.ui.ie && (!document.documentMode || document.documentMode < 9) && !b.button)
                return this._mouseUp(b);
            if (!b.which)
                return this._mouseUp(b);
        }
        return (b.which || b.button) && (this._mouseMoved = !0), this._mouseStarted ? (this._mouseDrag(b), b.preventDefault()) : (this._mouseDistanceMet(b) && this._mouseDelayMet(b) && (this._mouseStarted = this._mouseStart(this._mouseDownEvent, b) !== !1, this._mouseStarted ? this._mouseDrag(b) : this._mouseUp(b)), !this._mouseStarted);
    }, _mouseUp: function (c) {
        return this.document.unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate), this._mouseStarted && (this._mouseStarted = !1, c.target === this._mouseDownEvent.target && a.data(c.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(c)), b = !1, !1;
    }, _mouseDistanceMet: function (a) {
        return Math.max(Math.abs(this._mouseDownEvent.pageX - a.pageX), Math.abs(this._mouseDownEvent.pageY - a.pageY)) >= this.options.distance;
    }, _mouseDelayMet: function () {
        return this.mouseDelayMet;
    }, _mouseStart: function () {
    }, _mouseDrag: function () {
    }, _mouseStop: function () {
    }, _mouseCapture: function () {
        return !0;
    } });
});
/*!
 * jQuery UI Sortable 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/sortable/
 */
!function (a) {
    "function" == typeof define && define.amd ? define(["jquery", "./core", "./mouse", "./widget"], a) : a(jQuery);
}(function (a) {
    return a.widget("ui.sortable", a.ui.mouse, { version: "1.11.4", widgetEventPrefix: "sort", ready: !1, options: { appendTo: "parent", axis: !1, connectWith: !1, containment: !1, cursor: "auto", cursorAt: !1, dropOnEmpty: !0, forcePlaceholderSize: !1, forceHelperSize: !1, grid: !1, handle: !1, helper: "original", items: "> *", opacity: !1, placeholder: !1, revert: !1, scroll: !0, scrollSensitivity: 20, scrollSpeed: 20, scope: "default", tolerance: "intersect", zIndex: 1e3, activate: null, beforeStop: null, change: null, deactivate: null, out: null, over: null, receive: null, remove: null, sort: null, start: null, stop: null, update: null }, _isOverAxis: function (a, b, c) {
        return a >= b && b + c > a;
    }, _isFloating: function (a) {
        return /left|right/.test(a.css("float")) || /inline|table-cell/.test(a.css("display"));
    }, _create: function () {
        this.containerCache = {}, this.element.addClass("ui-sortable"), this.refresh(), this.offset = this.element.offset(), this._mouseInit(), this._setHandleClassName(), this.ready = !0;
    }, _setOption: function (a, b) {
        this._super(a, b), "handle" === a && this._setHandleClassName();
    }, _setHandleClassName: function () {
        this.element.find(".ui-sortable-handle").removeClass("ui-sortable-handle"), a.each(this.items, function () {
            (this.instance.options.handle ? this.item.find(this.instance.options.handle) : this.item).addClass("ui-sortable-handle");
        });
    }, _destroy: function () {
        this.element.removeClass("ui-sortable ui-sortable-disabled").find(".ui-sortable-handle").removeClass("ui-sortable-handle"), this._mouseDestroy();
        for (var a = this.items.length - 1; a >= 0; a--)
            this.items[a].item.removeData(this.widgetName + "-item");
        return this;
    }, _mouseCapture: function (b, c) {
        var d = null, e = !1, f = this;
        return this.reverting ? !1 : this.options.disabled || "static" === this.options.type ? !1 : (this._refreshItems(b), a(b.target).parents().each(function () {
            return a.data(this, f.widgetName + "-item") === f ? (d = a(this), !1) : void 0;
        }), a.data(b.target, f.widgetName + "-item") === f && (d = a(b.target)), d && (!this.options.handle || c || (a(this.options.handle, d).find("*").addBack().each(function () {
            this === b.target && (e = !0);
        }), e)) ? (this.currentItem = d, this._removeCurrentsFromItems(), !0) : !1);
    }, _mouseStart: function (b, c, d) {
        var e, f, g = this.options;
        if (this.currentContainer = this, this.refreshPositions(), this.helper = this._createHelper(b), this._cacheHelperProportions(), this._cacheMargins(), this.scrollParent = this.helper.scrollParent(), this.offset = this.currentItem.offset(), this.offset = { top: this.offset.top - this.margins.top, left: this.offset.left - this.margins.left }, a.extend(this.offset, { click: { left: b.pageX - this.offset.left, top: b.pageY - this.offset.top }, parent: this._getParentOffset(), relative: this._getRelativeOffset() }), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), this.originalPosition = this._generatePosition(b), this.originalPageX = b.pageX, this.originalPageY = b.pageY, g.cursorAt && this._adjustOffsetFromHelper(g.cursorAt), this.domPosition = { prev: this.currentItem.prev()[0], parent: this.currentItem.parent()[0] }, this.helper[0] !== this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), g.containment && this._setContainment(), g.cursor && "auto" !== g.cursor && (f = this.document.find("body"), this.storedCursor = f.css("cursor"), f.css("cursor", g.cursor), this.storedStylesheet = a("<style>*{ cursor: " + g.cursor + " !important; }</style>").appendTo(f)), g.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", g.opacity)), g.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", g.zIndex)), this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", b, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions(), !d)
            for (e = this.containers.length - 1; e >= 0; e--)
                this.containers[e]._trigger("activate", b, this._uiHash(this));
        return a.ui.ddmanager && (a.ui.ddmanager.current = this), a.ui.ddmanager && !g.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b), this.dragging = !0, this.helper.addClass("ui-sortable-helper"), this._mouseDrag(b), !0;
    }, _mouseDrag: function (b) {
        var c, d, e, f, g = this.options, h = !1;
        for (this.position = this._generatePosition(b), this.positionAbs = this._convertPositionTo("absolute"), this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs), this.options.scroll && (this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - b.pageY < g.scrollSensitivity ? this.scrollParent[0].scrollTop = h = this.scrollParent[0].scrollTop + g.scrollSpeed : b.pageY - this.overflowOffset.top < g.scrollSensitivity && (this.scrollParent[0].scrollTop = h = this.scrollParent[0].scrollTop - g.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - b.pageX < g.scrollSensitivity ? this.scrollParent[0].scrollLeft = h = this.scrollParent[0].scrollLeft + g.scrollSpeed : b.pageX - this.overflowOffset.left < g.scrollSensitivity && (this.scrollParent[0].scrollLeft = h = this.scrollParent[0].scrollLeft - g.scrollSpeed)) : (b.pageY - this.document.scrollTop() < g.scrollSensitivity ? h = this.document.scrollTop(this.document.scrollTop() - g.scrollSpeed) : this.window.height() - (b.pageY - this.document.scrollTop()) < g.scrollSensitivity && (h = this.document.scrollTop(this.document.scrollTop() + g.scrollSpeed)), b.pageX - this.document.scrollLeft() < g.scrollSensitivity ? h = this.document.scrollLeft(this.document.scrollLeft() - g.scrollSpeed) : this.window.width() - (b.pageX - this.document.scrollLeft()) < g.scrollSensitivity && (h = this.document.scrollLeft(this.document.scrollLeft() + g.scrollSpeed))), h !== !1 && a.ui.ddmanager && !g.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b)), this.positionAbs = this._convertPositionTo("absolute"), this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"), c = this.items.length - 1; c >= 0; c--)
            if (d = this.items[c], e = d.item[0], f = this._intersectsWithPointer(d), f && d.instance === this.currentContainer && e !== this.currentItem[0] && this.placeholder[1 === f ? "next" : "prev"]()[0] !== e && !a.contains(this.placeholder[0], e) && ("semi-dynamic" === this.options.type ? !a.contains(this.element[0], e) : !0)) {
                if (this.direction = 1 === f ? "down" : "up", "pointer" !== this.options.tolerance && !this._intersectsWithSides(d))
                    break;
                this._rearrange(b, d), this._trigger("change", b, this._uiHash());
                break;
            }
        return this._contactContainers(b), a.ui.ddmanager && a.ui.ddmanager.drag(this, b), this._trigger("sort", b, this._uiHash()), this.lastPositionAbs = this.positionAbs, !1;
    }, _mouseStop: function (b, c) {
        if (b) {
            if (a.ui.ddmanager && !this.options.dropBehaviour && a.ui.ddmanager.drop(this, b), this.options.revert) {
                var d = this, e = this.placeholder.offset(), f = this.options.axis, g = {};
                f && "x" !== f || (g.left = e.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollLeft)), f && "y" !== f || (g.top = e.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollTop)), this.reverting = !0, a(this.helper).animate(g, parseInt(this.options.revert, 10) || 500, function () {
                    d._clear(b);
                });
            }
            else
                this._clear(b, c);
            return !1;
        }
    }, cancel: function () {
        if (this.dragging) {
            this._mouseUp({ target: null }), "original" === this.options.helper ? this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper") : this.currentItem.show();
            for (var b = this.containers.length - 1; b >= 0; b--)
                this.containers[b]._trigger("deactivate", null, this._uiHash(this)), this.containers[b].containerCache.over && (this.containers[b]._trigger("out", null, this._uiHash(this)), this.containers[b].containerCache.over = 0);
        }
        return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), "original" !== this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(), a.extend(this, { helper: null, dragging: !1, reverting: !1, _noFinalSort: null }), this.domPosition.prev ? a(this.domPosition.prev).after(this.currentItem) : a(this.domPosition.parent).prepend(this.currentItem)), this;
    }, serialize: function (b) {
        var c = this._getItemsAsjQuery(b && b.connected), d = [];
        return b = b || {}, a(c).each(function () {
            var c = (a(b.item || this).attr(b.attribute || "id") || "").match(b.expression || /(.+)[\-=_](.+)/);
            c && d.push((b.key || c[1] + "[]") + "=" + (b.key && b.expression ? c[1] : c[2]));
        }), !d.length && b.key && d.push(b.key + "="), d.join("&");
    }, toArray: function (b) {
        var c = this._getItemsAsjQuery(b && b.connected), d = [];
        return b = b || {}, c.each(function () {
            d.push(a(b.item || this).attr(b.attribute || "id") || "");
        }), d;
    }, _intersectsWith: function (a) {
        var b = this.positionAbs.left, c = b + this.helperProportions.width, d = this.positionAbs.top, e = d + this.helperProportions.height, f = a.left, g = f + a.width, h = a.top, i = h + a.height, j = this.offset.click.top, k = this.offset.click.left, l = "x" === this.options.axis || d + j > h && i > d + j, m = "y" === this.options.axis || b + k > f && g > b + k, n = l && m;
        return "pointer" === this.options.tolerance || this.options.forcePointerForContainers || "pointer" !== this.options.tolerance && this.helperProportions[this.floating ? "width" : "height"] > a[this.floating ? "width" : "height"] ? n : f < b + this.helperProportions.width / 2 && c - this.helperProportions.width / 2 < g && h < d + this.helperProportions.height / 2 && e - this.helperProportions.height / 2 < i;
    }, _intersectsWithPointer: function (a) {
        var b = "x" === this.options.axis || this._isOverAxis(this.positionAbs.top + this.offset.click.top, a.top, a.height), c = "y" === this.options.axis || this._isOverAxis(this.positionAbs.left + this.offset.click.left, a.left, a.width), d = b && c, e = this._getDragVerticalDirection(), f = this._getDragHorizontalDirection();
        return d ? this.floating ? f && "right" === f || "down" === e ? 2 : 1 : e && ("down" === e ? 2 : 1) : !1;
    }, _intersectsWithSides: function (a) {
        var b = this._isOverAxis(this.positionAbs.top + this.offset.click.top, a.top + a.height / 2, a.height), c = this._isOverAxis(this.positionAbs.left + this.offset.click.left, a.left + a.width / 2, a.width), d = this._getDragVerticalDirection(), e = this._getDragHorizontalDirection();
        return this.floating && e ? "right" === e && c || "left" === e && !c : d && ("down" === d && b || "up" === d && !b);
    }, _getDragVerticalDirection: function () {
        var a = this.positionAbs.top - this.lastPositionAbs.top;
        return 0 !== a && (a > 0 ? "down" : "up");
    }, _getDragHorizontalDirection: function () {
        var a = this.positionAbs.left - this.lastPositionAbs.left;
        return 0 !== a && (a > 0 ? "right" : "left");
    }, refresh: function (a) {
        return this._refreshItems(a), this._setHandleClassName(), this.refreshPositions(), this;
    }, _connectWith: function () {
        var a = this.options;
        return a.connectWith.constructor === String ? [a.connectWith] : a.connectWith;
    }, _getItemsAsjQuery: function (b) {
        function c() {
            h.push(this);
        }
        var d, e, f, g, h = [], i = [], j = this._connectWith();
        if (j && b)
            for (d = j.length - 1; d >= 0; d--)
                for (f = a(j[d], this.document[0]), e = f.length - 1; e >= 0; e--)
                    g = a.data(f[e], this.widgetFullName), g && g !== this && !g.options.disabled && i.push([a.isFunction(g.options.items) ? g.options.items.call(g.element) : a(g.options.items, g.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), g]);
        for (i.push([a.isFunction(this.options.items) ? this.options.items.call(this.element, null, { options: this.options, item: this.currentItem }) : a(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]), d = i.length - 1; d >= 0; d--)
            i[d][0].each(c);
        return a(h);
    }, _removeCurrentsFromItems: function () {
        var b = this.currentItem.find(":data(" + this.widgetName + "-item)");
        this.items = a.grep(this.items, function (a) {
            for (var c = 0; c < b.length; c++)
                if (b[c] === a.item[0])
                    return !1;
            return !0;
        });
    }, _refreshItems: function (b) {
        this.items = [], this.containers = [this];
        var c, d, e, f, g, h, i, j, k = this.items, l = [[a.isFunction(this.options.items) ? this.options.items.call(this.element[0], b, { item: this.currentItem }) : a(this.options.items, this.element), this]], m = this._connectWith();
        if (m && this.ready)
            for (c = m.length - 1; c >= 0; c--)
                for (e = a(m[c], this.document[0]), d = e.length - 1; d >= 0; d--)
                    f = a.data(e[d], this.widgetFullName), f && f !== this && !f.options.disabled && (l.push([a.isFunction(f.options.items) ? f.options.items.call(f.element[0], b, { item: this.currentItem }) : a(f.options.items, f.element), f]), this.containers.push(f));
        for (c = l.length - 1; c >= 0; c--)
            for (g = l[c][1], h = l[c][0], d = 0, j = h.length; j > d; d++)
                i = a(h[d]), i.data(this.widgetName + "-item", g), k.push({ item: i, instance: g, width: 0, height: 0, left: 0, top: 0 });
    }, refreshPositions: function (b) {
        this.floating = this.items.length ? "x" === this.options.axis || this._isFloating(this.items[0].item) : !1, this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
        var c, d, e, f;
        for (c = this.items.length - 1; c >= 0; c--)
            d = this.items[c], d.instance !== this.currentContainer && this.currentContainer && d.item[0] !== this.currentItem[0] || (e = this.options.toleranceElement ? a(this.options.toleranceElement, d.item) : d.item, b || (d.width = e.outerWidth(), d.height = e.outerHeight()), f = e.offset(), d.left = f.left, d.top = f.top);
        if (this.options.custom && this.options.custom.refreshContainers)
            this.options.custom.refreshContainers.call(this);
        else
            for (c = this.containers.length - 1; c >= 0; c--)
                f = this.containers[c].element.offset(), this.containers[c].containerCache.left = f.left, this.containers[c].containerCache.top = f.top, this.containers[c].containerCache.width = this.containers[c].element.outerWidth(), this.containers[c].containerCache.height = this.containers[c].element.outerHeight();
        return this;
    }, _createPlaceholder: function (b) {
        b = b || this;
        var c, d = b.options;
        d.placeholder && d.placeholder.constructor !== String || (c = d.placeholder, d.placeholder = { element: function () {
            var d = b.currentItem[0].nodeName.toLowerCase(), e = a("<" + d + ">", b.document[0]).addClass(c || b.currentItem[0].className + " ui-sortable-placeholder").removeClass("ui-sortable-helper");
            return "tbody" === d ? b._createTrPlaceholder(b.currentItem.find("tr").eq(0), a("<tr>", b.document[0]).appendTo(e)) : "tr" === d ? b._createTrPlaceholder(b.currentItem, e) : "img" === d && e.attr("src", b.currentItem.attr("src")), c || e.css("visibility", "hidden"), e;
        }, update: function (a, e) {
            (!c || d.forcePlaceholderSize) && (e.height() || e.height(b.currentItem.innerHeight() - parseInt(b.currentItem.css("paddingTop") || 0, 10) - parseInt(b.currentItem.css("paddingBottom") || 0, 10)), e.width() || e.width(b.currentItem.innerWidth() - parseInt(b.currentItem.css("paddingLeft") || 0, 10) - parseInt(b.currentItem.css("paddingRight") || 0, 10)));
        } }), b.placeholder = a(d.placeholder.element.call(b.element, b.currentItem)), b.currentItem.after(b.placeholder), d.placeholder.update(b, b.placeholder);
    }, _createTrPlaceholder: function (b, c) {
        var d = this;
        b.children().each(function () {
            a("<td>&#160;</td>", d.document[0]).attr("colspan", a(this).attr("colspan") || 1).appendTo(c);
        });
    }, _contactContainers: function (b) {
        var c, d, e, f, g, h, i, j, k, l, m = null, n = null;
        for (c = this.containers.length - 1; c >= 0; c--)
            if (!a.contains(this.currentItem[0], this.containers[c].element[0]))
                if (this._intersectsWith(this.containers[c].containerCache)) {
                    if (m && a.contains(this.containers[c].element[0], m.element[0]))
                        continue;
                    m = this.containers[c], n = c;
                }
                else
                    this.containers[c].containerCache.over && (this.containers[c]._trigger("out", b, this._uiHash(this)), this.containers[c].containerCache.over = 0);
        if (m)
            if (1 === this.containers.length)
                this.containers[n].containerCache.over || (this.containers[n]._trigger("over", b, this._uiHash(this)), this.containers[n].containerCache.over = 1);
            else {
                for (e = 1e4, f = null, k = m.floating || this._isFloating(this.currentItem), g = k ? "left" : "top", h = k ? "width" : "height", l = k ? "clientX" : "clientY", d = this.items.length - 1; d >= 0; d--)
                    a.contains(this.containers[n].element[0], this.items[d].item[0]) && this.items[d].item[0] !== this.currentItem[0] && (i = this.items[d].item.offset()[g], j = !1, b[l] - i > this.items[d][h] / 2 && (j = !0), Math.abs(b[l] - i) < e && (e = Math.abs(b[l] - i), f = this.items[d], this.direction = j ? "up" : "down"));
                if (!f && !this.options.dropOnEmpty)
                    return;
                if (this.currentContainer === this.containers[n])
                    return void (this.currentContainer.containerCache.over || (this.containers[n]._trigger("over", b, this._uiHash()), this.currentContainer.containerCache.over = 1));
                f ? this._rearrange(b, f, null, !0) : this._rearrange(b, null, this.containers[n].element, !0), this._trigger("change", b, this._uiHash()), this.containers[n]._trigger("change", b, this._uiHash(this)), this.currentContainer = this.containers[n], this.options.placeholder.update(this.currentContainer, this.placeholder), this.containers[n]._trigger("over", b, this._uiHash(this)), this.containers[n].containerCache.over = 1;
            }
    }, _createHelper: function (b) {
        var c = this.options, d = a.isFunction(c.helper) ? a(c.helper.apply(this.element[0], [b, this.currentItem])) : "clone" === c.helper ? this.currentItem.clone() : this.currentItem;
        return d.parents("body").length || a("parent" !== c.appendTo ? c.appendTo : this.currentItem[0].parentNode)[0].appendChild(d[0]), d[0] === this.currentItem[0] && (this._storedCSS = { width: this.currentItem[0].style.width, height: this.currentItem[0].style.height, position: this.currentItem.css("position"), top: this.currentItem.css("top"), left: this.currentItem.css("left") }), (!d[0].style.width || c.forceHelperSize) && d.width(this.currentItem.width()), (!d[0].style.height || c.forceHelperSize) && d.height(this.currentItem.height()), d;
    }, _adjustOffsetFromHelper: function (b) {
        "string" == typeof b && (b = b.split(" ")), a.isArray(b) && (b = { left: +b[0], top: +b[1] || 0 }), "left" in b && (this.offset.click.left = b.left + this.margins.left), "right" in b && (this.offset.click.left = this.helperProportions.width - b.right + this.margins.left), "top" in b && (this.offset.click.top = b.top + this.margins.top), "bottom" in b && (this.offset.click.top = this.helperProportions.height - b.bottom + this.margins.top);
    }, _getParentOffset: function () {
        this.offsetParent = this.helper.offsetParent();
        var b = this.offsetParent.offset();
        return "absolute" === this.cssPosition && this.scrollParent[0] !== this.document[0] && a.contains(this.scrollParent[0], this.offsetParent[0]) && (b.left += this.scrollParent.scrollLeft(), b.top += this.scrollParent.scrollTop()), (this.offsetParent[0] === this.document[0].body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && a.ui.ie) && (b = { top: 0, left: 0 }), { top: b.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0), left: b.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0) };
    }, _getRelativeOffset: function () {
        if ("relative" === this.cssPosition) {
            var a = this.currentItem.position();
            return { top: a.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(), left: a.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft() };
        }
        return { top: 0, left: 0 };
    }, _cacheMargins: function () {
        this.margins = { left: parseInt(this.currentItem.css("marginLeft"), 10) || 0, top: parseInt(this.currentItem.css("marginTop"), 10) || 0 };
    }, _cacheHelperProportions: function () {
        this.helperProportions = { width: this.helper.outerWidth(), height: this.helper.outerHeight() };
    }, _setContainment: function () {
        var b, c, d, e = this.options;
        "parent" === e.containment && (e.containment = this.helper[0].parentNode), ("document" === e.containment || "window" === e.containment) && (this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, "document" === e.containment ? this.document.width() : this.window.width() - this.helperProportions.width - this.margins.left, ("document" === e.containment ? this.document.width() : this.window.height() || this.document[0].body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]), /^(document|window|parent)$/.test(e.containment) || (b = a(e.containment)[0], c = a(e.containment).offset(), d = "hidden" !== a(b).css("overflow"), this.containment = [c.left + (parseInt(a(b).css("borderLeftWidth"), 10) || 0) + (parseInt(a(b).css("paddingLeft"), 10) || 0) - this.margins.left, c.top + (parseInt(a(b).css("borderTopWidth"), 10) || 0) + (parseInt(a(b).css("paddingTop"), 10) || 0) - this.margins.top, c.left + (d ? Math.max(b.scrollWidth, b.offsetWidth) : b.offsetWidth) - (parseInt(a(b).css("borderLeftWidth"), 10) || 0) - (parseInt(a(b).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, c.top + (d ? Math.max(b.scrollHeight, b.offsetHeight) : b.offsetHeight) - (parseInt(a(b).css("borderTopWidth"), 10) || 0) - (parseInt(a(b).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top]);
    }, _convertPositionTo: function (b, c) {
        c || (c = this.position);
        var d = "absolute" === b ? 1 : -1, e = "absolute" !== this.cssPosition || this.scrollParent[0] !== this.document[0] && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent, f = /(html|body)/i.test(e[0].tagName);
        return { top: c.top + this.offset.relative.top * d + this.offset.parent.top * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : f ? 0 : e.scrollTop()) * d, left: c.left + this.offset.relative.left * d + this.offset.parent.left * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : f ? 0 : e.scrollLeft()) * d };
    }, _generatePosition: function (b) {
        var c, d, e = this.options, f = b.pageX, g = b.pageY, h = "absolute" !== this.cssPosition || this.scrollParent[0] !== this.document[0] && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent, i = /(html|body)/i.test(h[0].tagName);
        return "relative" !== this.cssPosition || this.scrollParent[0] !== this.document[0] && this.scrollParent[0] !== this.offsetParent[0] || (this.offset.relative = this._getRelativeOffset()), this.originalPosition && (this.containment && (b.pageX - this.offset.click.left < this.containment[0] && (f = this.containment[0] + this.offset.click.left), b.pageY - this.offset.click.top < this.containment[1] && (g = this.containment[1] + this.offset.click.top), b.pageX - this.offset.click.left > this.containment[2] && (f = this.containment[2] + this.offset.click.left), b.pageY - this.offset.click.top > this.containment[3] && (g = this.containment[3] + this.offset.click.top)), e.grid && (c = this.originalPageY + Math.round((g - this.originalPageY) / e.grid[1]) * e.grid[1], g = this.containment ? c - this.offset.click.top >= this.containment[1] && c - this.offset.click.top <= this.containment[3] ? c : c - this.offset.click.top >= this.containment[1] ? c - e.grid[1] : c + e.grid[1] : c, d = this.originalPageX + Math.round((f - this.originalPageX) / e.grid[0]) * e.grid[0], f = this.containment ? d - this.offset.click.left >= this.containment[0] && d - this.offset.click.left <= this.containment[2] ? d : d - this.offset.click.left >= this.containment[0] ? d - e.grid[0] : d + e.grid[0] : d)), { top: g - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : i ? 0 : h.scrollTop()), left: f - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : i ? 0 : h.scrollLeft()) };
    }, _rearrange: function (a, b, c, d) {
        c ? c[0].appendChild(this.placeholder[0]) : b.item[0].parentNode.insertBefore(this.placeholder[0], "down" === this.direction ? b.item[0] : b.item[0].nextSibling), this.counter = this.counter ? ++this.counter : 1;
        var e = this.counter;
        this._delay(function () {
            e === this.counter && this.refreshPositions(!d);
        });
    }, _clear: function (a, b) {
        function c(a, b, c) {
            return function (d) {
                c._trigger(a, d, b._uiHash(b));
            };
        }
        this.reverting = !1;
        var d, e = [];
        if (!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null, this.helper[0] === this.currentItem[0]) {
            for (d in this._storedCSS)
                ("auto" === this._storedCSS[d] || "static" === this._storedCSS[d]) && (this._storedCSS[d] = "");
            this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");
        }
        else
            this.currentItem.show();
        for (this.fromOutside && !b && e.push(function (a) {
            this._trigger("receive", a, this._uiHash(this.fromOutside));
        }), !this.fromOutside && this.domPosition.prev === this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent === this.currentItem.parent()[0] || b || e.push(function (a) {
            this._trigger("update", a, this._uiHash());
        }), this !== this.currentContainer && (b || (e.push(function (a) {
            this._trigger("remove", a, this._uiHash());
        }), e.push(function (a) {
            return function (b) {
                a._trigger("receive", b, this._uiHash(this));
            };
        }.call(this, this.currentContainer)), e.push(function (a) {
            return function (b) {
                a._trigger("update", b, this._uiHash(this));
            };
        }.call(this, this.currentContainer)))), d = this.containers.length - 1; d >= 0; d--)
            b || e.push(c("deactivate", this, this.containers[d])), this.containers[d].containerCache.over && (e.push(c("out", this, this.containers[d])), this.containers[d].containerCache.over = 0);
        if (this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", "auto" === this._storedZIndex ? "" : this._storedZIndex), this.dragging = !1, b || this._trigger("beforeStop", a, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.cancelHelperRemoval || (this.helper[0] !== this.currentItem[0] && this.helper.remove(), this.helper = null), !b) {
            for (d = 0; d < e.length; d++)
                e[d].call(this, a);
            this._trigger("stop", a, this._uiHash());
        }
        return this.fromOutside = !1, !this.cancelHelperRemoval;
    }, _trigger: function () {
        a.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel();
    }, _uiHash: function (b) {
        var c = b || this;
        return { helper: c.helper, placeholder: c.placeholder || a([]), position: c.position, originalPosition: c.originalPosition, offset: c.positionAbs, item: c.currentItem, sender: b ? b.element : null };
    } });
});
var postboxes;
!function (a) {
    var b = a(document);
    postboxes = { handle_click: function () {
        var c, d = a(this), e = d.parent(".postbox"), f = e.attr("id");
        "dashboard_browser_nag" !== f && (e.toggleClass("closed"), c = !e.hasClass("closed"), d.hasClass("handlediv") ? d.attr("aria-expanded", c) : d.closest(".postbox").find("button.handlediv").attr("aria-expanded", c), "press-this" !== postboxes.page && postboxes.save_state(postboxes.page), f && (!e.hasClass("closed") && a.isFunction(postboxes.pbshow) ? postboxes.pbshow(f) : e.hasClass("closed") && a.isFunction(postboxes.pbhide) && postboxes.pbhide(f)), b.trigger("postbox-toggled", e));
    }, add_postbox_toggles: function (c, d) {
        var e = a(".postbox .hndle, .postbox .handlediv");
        this.page = c, this.init(c, d), e.on("click.postboxes", this.handle_click), a(".postbox .hndle a").click(function (a) {
            a.stopPropagation();
        }), a(".postbox a.dismiss").on("click.postboxes", function (b) {
            var c = a(this).parents(".postbox").attr("id") + "-hide";
            b.preventDefault(), a("#" + c).prop("checked", !1).triggerHandler("click");
        }), a(".hide-postbox-tog").bind("click.postboxes", function () {
            var d = a(this), e = d.val(), f = a("#" + e);
            d.prop("checked") ? (f.show(), a.isFunction(postboxes.pbshow) && postboxes.pbshow(e)) : (f.hide(), a.isFunction(postboxes.pbhide) && postboxes.pbhide(e)), postboxes.save_state(c), postboxes._mark_area(), b.trigger("postbox-toggled", f);
        }), a('.columns-prefs input[type="radio"]').bind("click.postboxes", function () {
            var b = parseInt(a(this).val(), 10);
            b && (postboxes._pb_edit(b), postboxes.save_order(c));
        });
    }, init: function (b, c) {
        var d = a(document.body).hasClass("mobile"), e = a(".postbox .handlediv");
        a.extend(this, c || {}), a("#wpbody-content").css("overflow", "hidden"), a(".meta-box-sortables").sortable({ placeholder: "sortable-placeholder", connectWith: ".meta-box-sortables", items: ".postbox", handle: ".hndle", cursor: "move", delay: d ? 200 : 0, distance: 2, tolerance: "pointer", forcePlaceholderSize: !0, helper: "clone", opacity: .65, stop: function () {
            var c = a(this);
            return c.find("#dashboard_browser_nag").is(":visible") && "dashboard_browser_nag" != this.firstChild.id ? void c.sortable("cancel") : void postboxes.save_order(b);
        }, receive: function (b, c) {
            "dashboard_browser_nag" == c.item[0].id && a(c.sender).sortable("cancel"), postboxes._mark_area();
        } }), d && (a(document.body).bind("orientationchange.postboxes", function () {
            postboxes._pb_change();
        }), this._pb_change()), this._mark_area(), e.each(function () {
            var b = a(this);
            b.attr("aria-expanded", !b.parent(".postbox").hasClass("closed"));
        });
    }, save_state: function (b) {
        var c = a(".postbox").filter(".closed").map(function () {
            return this.id;
        }).get().join(","), d = a(".postbox").filter(":hidden").map(function () {
            return this.id;
        }).get().join(",");
        a.post(ajaxurl, { action: "closed-postboxes", closed: c, hidden: d, closedpostboxesnonce: jQuery("#closedpostboxesnonce").val(), page: b });
    }, save_order: function (b) {
        var c, d = a(".columns-prefs input:checked").val() || 0;
        c = { action: "meta-box-order", _ajax_nonce: a("#meta-box-order-nonce").val(), page_columns: d, page: b }, a(".meta-box-sortables").each(function () {
            c["order[" + this.id.split("-")[0] + "]"] = a(this).sortable("toArray").join(",");
        }), a.post(ajaxurl, c);
    }, _mark_area: function () {
        var b = a("div.postbox:visible").length, c = a("#post-body #side-sortables");
        a("#dashboard-widgets .meta-box-sortables:visible").each(function () {
            var c = a(this);
            1 == b || c.children(".postbox:visible").length ? c.removeClass("empty-container") : c.addClass("empty-container");
        }), c.length && (c.children(".postbox:visible").length ? c.removeClass("empty-container") : "280px" == a("#postbox-container-1").css("width") && c.addClass("empty-container"));
    }, _pb_edit: function (b) {
        var c = a(".metabox-holder").get(0);
        c && (c.className = c.className.replace(/columns-\d+/, "columns-" + b)), a(document).trigger("postboxes-columnchange");
    }, _pb_change: function () {
        var b = a('label.columns-prefs-1 input[type="radio"]');
        switch (window.orientation) {
            case 90:
            case -90:
                b.length && b.is(":checked") || this._pb_edit(2);
                break;
            case 0:
            case 180: a("#poststuff").length ? this._pb_edit(1) : b.length && b.is(":checked") || this._pb_edit(2);
        }
    }, pbshow: !1, pbhide: !1 };
}(jQuery);
var ajaxWidgets, ajaxPopulateWidgets, quickPressLoad;
jQuery(document).ready(function (a) {
    function b() {
        if (!(document.documentMode && document.documentMode < 9)) {
            a("body").append('<div class="quick-draft-textarea-clone" style="display: none;"></div>');
            var b = a(".quick-draft-textarea-clone"), c = a("#content"), d = c.height(), e = a(window).height() - 100;
            b.css({ "font-family": c.css("font-family"), "font-size": c.css("font-size"), "line-height": c.css("line-height"), "padding-bottom": c.css("paddingBottom"), "padding-left": c.css("paddingLeft"), "padding-right": c.css("paddingRight"), "padding-top": c.css("paddingTop"), "white-space": "pre-wrap", "word-wrap": "break-word", display: "none" }), c.on("focus input propertychange", function () {
                var f = a(this), g = f.val() + "&nbsp;", h = b.css("width", f.css("width")).text(g).outerHeight() + 2;
                c.css("overflow-y", "auto"), h === d || h >= e && d >= e || (d = h > e ? e : h, c.css("overflow", "hidden"), f.css("height", d + "px"));
            });
        }
    }
    var c, d = a("#welcome-panel"), e = a("#wp_welcome_panel-hide");
    c = function (b) {
        a.post(ajaxurl, { action: "update-welcome-panel", visible: b, welcomepanelnonce: a("#welcomepanelnonce").val() });
    }, d.hasClass("hidden") && e.prop("checked") && d.removeClass("hidden"), a(".welcome-panel-close, .welcome-panel-dismiss a", d).click(function (b) {
        b.preventDefault(), d.addClass("hidden"), c(0), a("#wp_welcome_panel-hide").prop("checked", !1);
    }), e.click(function () {
        d.toggleClass("hidden", !this.checked), c(this.checked ? 1 : 0);
    }), ajaxWidgets = ["dashboard_primary"], ajaxPopulateWidgets = function (b) {
        function c(b, c) {
            var d, e = a("#" + c + " div.inside:visible").find(".widget-loading");
            e.length && (d = e.parent(), setTimeout(function () {
                d.load(ajaxurl + "?action=dashboard-widgets&widget=" + c + "&pagenow=" + pagenow, "", function () {
                    d.hide().slideDown("normal", function () {
                        a(this).css("display", "");
                    });
                });
            }, 500 * b));
        }
        b ? (b = b.toString(), -1 !== a.inArray(b, ajaxWidgets) && c(0, b)) : a.each(ajaxWidgets, c);
    }, ajaxPopulateWidgets(), postboxes.add_postbox_toggles(pagenow, { pbshow: ajaxPopulateWidgets }), quickPressLoad = function () {
        var c, d = a("#quickpost-action");
        a('#quick-press .submit input[type="submit"], #quick-press .submit input[type="reset"]').prop("disabled", !1), c = a("#quick-press").submit(function (b) {
            function d() {
                var b = a(".drafts ul li").first();
                b.css("background", "#fffbe5"), setTimeout(function () {
                    b.css("background", "none");
                }, 1e3);
            }
            b.preventDefault(), a("#dashboard_quick_press #publishing-action .spinner").show(), a('#quick-press .submit input[type="submit"], #quick-press .submit input[type="reset"]').prop("disabled", !0), a.post(c.attr("action"), c.serializeArray(), function (b) {
                a("#dashboard_quick_press .inside").html(b), a("#quick-press").removeClass("initial-form"), quickPressLoad(), d(), a("#title").focus();
            });
        }), a("#publish").click(function () {
            d.val("post-quickpress-publish");
        }), a("#title, #tags-input, #content").each(function () {
            var b = a(this), c = a("#" + this.id + "-prompt-text");
            "" === this.value && c.removeClass("screen-reader-text"), c.click(function () {
                a(this).addClass("screen-reader-text"), b.focus();
            }), b.blur(function () {
                "" === this.value && c.removeClass("screen-reader-text");
            }), b.focus(function () {
                c.addClass("screen-reader-text");
            });
        }), a("#quick-press").on("click focusin", function () {
            wpActiveEditor = "content";
        }), b();
    }, quickPressLoad(), a(".meta-box-sortables").sortable("option", "containment", "#wpwrap");
});
//     Underscore.js 1.6.0
//     http://underscorejs.org
//     (c) 2009-2014 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.
(function () {
    var n = this, t = n._, r = {}, e = Array.prototype, u = Object.prototype, i = Function.prototype, a = e.push, o = e.slice, c = e.concat, l = u.toString, f = u.hasOwnProperty, s = e.forEach, p = e.map, h = e.reduce, v = e.reduceRight, g = e.filter, d = e.every, m = e.some, y = e.indexOf, b = e.lastIndexOf, x = Array.isArray, w = Object.keys, _ = i.bind, j = function (n) {
        return n instanceof j ? n : this instanceof j ? void (this._wrapped = n) : new j(n);
    };
    "undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = j), exports._ = j) : n._ = j, j.VERSION = "1.6.0";
    var A = j.each = j.forEach = function (n, t, e) {
        if (null == n)
            return n;
        if (s && n.forEach === s)
            n.forEach(t, e);
        else if (n.length === +n.length) {
            for (var u = 0, i = n.length; i > u; u++)
                if (t.call(e, n[u], u, n) === r)
                    return;
        }
        else
            for (var a = j.keys(n), u = 0, i = a.length; i > u; u++)
                if (t.call(e, n[a[u]], a[u], n) === r)
                    return;
        return n;
    };
    j.map = j.collect = function (n, t, r) {
        var e = [];
        return null == n ? e : p && n.map === p ? n.map(t, r) : (A(n, function (n, u, i) {
            e.push(t.call(r, n, u, i));
        }), e);
    };
    var O = "Reduce of empty array with no initial value";
    j.reduce = j.foldl = j.inject = function (n, t, r, e) {
        var u = arguments.length > 2;
        if (null == n && (n = []), h && n.reduce === h)
            return e && (t = j.bind(t, e)), u ? n.reduce(t, r) : n.reduce(t);
        if (A(n, function (n, i, a) {
            u ? r = t.call(e, r, n, i, a) : (r = n, u = !0);
        }), !u)
            throw new TypeError(O);
        return r;
    }, j.reduceRight = j.foldr = function (n, t, r, e) {
        var u = arguments.length > 2;
        if (null == n && (n = []), v && n.reduceRight === v)
            return e && (t = j.bind(t, e)), u ? n.reduceRight(t, r) : n.reduceRight(t);
        var i = n.length;
        if (i !== +i) {
            var a = j.keys(n);
            i = a.length;
        }
        if (A(n, function (o, c, l) {
            c = a ? a[--i] : --i, u ? r = t.call(e, r, n[c], c, l) : (r = n[c], u = !0);
        }), !u)
            throw new TypeError(O);
        return r;
    }, j.find = j.detect = function (n, t, r) {
        var e;
        return k(n, function (n, u, i) {
            return t.call(r, n, u, i) ? (e = n, !0) : void 0;
        }), e;
    }, j.filter = j.select = function (n, t, r) {
        var e = [];
        return null == n ? e : g && n.filter === g ? n.filter(t, r) : (A(n, function (n, u, i) {
            t.call(r, n, u, i) && e.push(n);
        }), e);
    }, j.reject = function (n, t, r) {
        return j.filter(n, function (n, e, u) {
            return !t.call(r, n, e, u);
        }, r);
    }, j.every = j.all = function (n, t, e) {
        t || (t = j.identity);
        var u = !0;
        return null == n ? u : d && n.every === d ? n.every(t, e) : (A(n, function (n, i, a) {
            return (u = u && t.call(e, n, i, a)) ? void 0 : r;
        }), !!u);
    };
    var k = j.some = j.any = function (n, t, e) {
        t || (t = j.identity);
        var u = !1;
        return null == n ? u : m && n.some === m ? n.some(t, e) : (A(n, function (n, i, a) {
            return u || (u = t.call(e, n, i, a)) ? r : void 0;
        }), !!u);
    };
    j.contains = j.include = function (n, t) {
        return null == n ? !1 : y && n.indexOf === y ? n.indexOf(t) != -1 : k(n, function (n) {
            return n === t;
        });
    }, j.invoke = function (n, t) {
        var r = o.call(arguments, 2), e = j.isFunction(t);
        return j.map(n, function (n) {
            return (e ? t : n[t]).apply(n, r);
        });
    }, j.pluck = function (n, t) {
        return j.map(n, j.property(t));
    }, j.where = function (n, t) {
        return j.filter(n, j.matches(t));
    }, j.findWhere = function (n, t) {
        return j.find(n, j.matches(t));
    }, j.max = function (n, t, r) {
        if (!t && j.isArray(n) && n[0] === +n[0] && n.length < 65535)
            return Math.max.apply(Math, n);
        var e = -1 / 0, u = -1 / 0;
        return A(n, function (n, i, a) {
            var o = t ? t.call(r, n, i, a) : n;
            o > u && (e = n, u = o);
        }), e;
    }, j.min = function (n, t, r) {
        if (!t && j.isArray(n) && n[0] === +n[0] && n.length < 65535)
            return Math.min.apply(Math, n);
        var e = 1 / 0, u = 1 / 0;
        return A(n, function (n, i, a) {
            var o = t ? t.call(r, n, i, a) : n;
            u > o && (e = n, u = o);
        }), e;
    }, j.shuffle = function (n) {
        var t, r = 0, e = [];
        return A(n, function (n) {
            t = j.random(r++), e[r - 1] = e[t], e[t] = n;
        }), e;
    }, j.sample = function (n, t, r) {
        return null == t || r ? (n.length !== +n.length && (n = j.values(n)), n[j.random(n.length - 1)]) : j.shuffle(n).slice(0, Math.max(0, t));
    };
    var E = function (n) {
        return null == n ? j.identity : j.isFunction(n) ? n : j.property(n);
    };
    j.sortBy = function (n, t, r) {
        return t = E(t), j.pluck(j.map(n, function (n, e, u) {
            return { value: n, index: e, criteria: t.call(r, n, e, u) };
        }).sort(function (n, t) {
            var r = n.criteria, e = t.criteria;
            if (r !== e) {
                if (r > e || r === void 0)
                    return 1;
                if (e > r || e === void 0)
                    return -1;
            }
            return n.index - t.index;
        }), "value");
    };
    var F = function (n) {
        return function (t, r, e) {
            var u = {};
            return r = E(r), A(t, function (i, a) {
                var o = r.call(e, i, a, t);
                n(u, o, i);
            }), u;
        };
    };
    j.groupBy = F(function (n, t, r) {
        j.has(n, t) ? n[t].push(r) : n[t] = [r];
    }), j.indexBy = F(function (n, t, r) {
        n[t] = r;
    }), j.countBy = F(function (n, t) {
        j.has(n, t) ? n[t]++ : n[t] = 1;
    }), j.sortedIndex = function (n, t, r, e) {
        r = E(r);
        for (var u = r.call(e, t), i = 0, a = n.length; a > i;) {
            var o = i + a >>> 1;
            r.call(e, n[o]) < u ? i = o + 1 : a = o;
        }
        return i;
    }, j.toArray = function (n) {
        return n ? j.isArray(n) ? o.call(n) : n.length === +n.length ? j.map(n, j.identity) : j.values(n) : [];
    }, j.size = function (n) {
        return null == n ? 0 : n.length === +n.length ? n.length : j.keys(n).length;
    }, j.first = j.head = j.take = function (n, t, r) {
        return null == n ? void 0 : null == t || r ? n[0] : 0 > t ? [] : o.call(n, 0, t);
    }, j.initial = function (n, t, r) {
        return o.call(n, 0, n.length - (null == t || r ? 1 : t));
    }, j.last = function (n, t, r) {
        return null == n ? void 0 : null == t || r ? n[n.length - 1] : o.call(n, Math.max(n.length - t, 0));
    }, j.rest = j.tail = j.drop = function (n, t, r) {
        return o.call(n, null == t || r ? 1 : t);
    }, j.compact = function (n) {
        return j.filter(n, j.identity);
    };
    var M = function (n, t, r) {
        return t && j.every(n, j.isArray) ? c.apply(r, n) : (A(n, function (n) {
            j.isArray(n) || j.isArguments(n) ? t ? a.apply(r, n) : M(n, t, r) : r.push(n);
        }), r);
    };
    j.flatten = function (n, t) {
        return M(n, t, []);
    }, j.without = function (n) {
        return j.difference(n, o.call(arguments, 1));
    }, j.partition = function (n, t) {
        var r = [], e = [];
        return A(n, function (n) {
            (t(n) ? r : e).push(n);
        }), [r, e];
    }, j.uniq = j.unique = function (n, t, r, e) {
        j.isFunction(t) && (e = r, r = t, t = !1);
        var u = r ? j.map(n, r, e) : n, i = [], a = [];
        return A(u, function (r, e) {
            (t ? e && a[a.length - 1] === r : j.contains(a, r)) || (a.push(r), i.push(n[e]));
        }), i;
    }, j.union = function () {
        return j.uniq(j.flatten(arguments, !0));
    }, j.intersection = function (n) {
        var t = o.call(arguments, 1);
        return j.filter(j.uniq(n), function (n) {
            return j.every(t, function (t) {
                return j.contains(t, n);
            });
        });
    }, j.difference = function (n) {
        var t = c.apply(e, o.call(arguments, 1));
        return j.filter(n, function (n) {
            return !j.contains(t, n);
        });
    }, j.zip = function () {
        for (var n = j.max(j.pluck(arguments, "length").concat(0)), t = new Array(n), r = 0; n > r; r++)
            t[r] = j.pluck(arguments, "" + r);
        return t;
    }, j.object = function (n, t) {
        if (null == n)
            return {};
        for (var r = {}, e = 0, u = n.length; u > e; e++)
            t ? r[n[e]] = t[e] : r[n[e][0]] = n[e][1];
        return r;
    }, j.indexOf = function (n, t, r) {
        if (null == n)
            return -1;
        var e = 0, u = n.length;
        if (r) {
            if ("number" != typeof r)
                return e = j.sortedIndex(n, t), n[e] === t ? e : -1;
            e = 0 > r ? Math.max(0, u + r) : r;
        }
        if (y && n.indexOf === y)
            return n.indexOf(t, r);
        for (; u > e; e++)
            if (n[e] === t)
                return e;
        return -1;
    }, j.lastIndexOf = function (n, t, r) {
        if (null == n)
            return -1;
        var e = null != r;
        if (b && n.lastIndexOf === b)
            return e ? n.lastIndexOf(t, r) : n.lastIndexOf(t);
        for (var u = e ? r : n.length; u--;)
            if (n[u] === t)
                return u;
        return -1;
    }, j.range = function (n, t, r) {
        arguments.length <= 1 && (t = n || 0, n = 0), r = arguments[2] || 1;
        for (var e = Math.max(Math.ceil((t - n) / r), 0), u = 0, i = new Array(e); e > u;)
            i[u++] = n, n += r;
        return i;
    };
    var R = function () {
    };
    j.bind = function (n, t) {
        var r, e;
        if (_ && n.bind === _)
            return _.apply(n, o.call(arguments, 1));
        if (!j.isFunction(n))
            throw new TypeError;
        return r = o.call(arguments, 2), e = function () {
            if (!(this instanceof e))
                return n.apply(t, r.concat(o.call(arguments)));
            R.prototype = n.prototype;
            var u = new R;
            R.prototype = null;
            var i = n.apply(u, r.concat(o.call(arguments)));
            return Object(i) === i ? i : u;
        };
    }, j.partial = function (n) {
        var t = o.call(arguments, 1);
        return function () {
            for (var r = 0, e = t.slice(), u = 0, i = e.length; i > u; u++)
                e[u] === j && (e[u] = arguments[r++]);
            for (; r < arguments.length;)
                e.push(arguments[r++]);
            return n.apply(this, e);
        };
    }, j.bindAll = function (n) {
        var t = o.call(arguments, 1);
        if (0 === t.length)
            throw new Error("bindAll must be passed function names");
        return A(t, function (t) {
            n[t] = j.bind(n[t], n);
        }), n;
    }, j.memoize = function (n, t) {
        var r = {};
        return t || (t = j.identity), function () {
            var e = t.apply(this, arguments);
            return j.has(r, e) ? r[e] : r[e] = n.apply(this, arguments);
        };
    }, j.delay = function (n, t) {
        var r = o.call(arguments, 2);
        return setTimeout(function () {
            return n.apply(null, r);
        }, t);
    }, j.defer = function (n) {
        return j.delay.apply(j, [n, 1].concat(o.call(arguments, 1)));
    }, j.throttle = function (n, t, r) {
        var e, u, i, a = null, o = 0;
        r || (r = {});
        var c = function () {
            o = r.leading === !1 ? 0 : j.now(), a = null, i = n.apply(e, u), e = u = null;
        };
        return function () {
            var l = j.now();
            o || r.leading !== !1 || (o = l);
            var f = t - (l - o);
            return e = this, u = arguments, 0 >= f ? (clearTimeout(a), a = null, o = l, i = n.apply(e, u), e = u = null) : a || r.trailing === !1 || (a = setTimeout(c, f)), i;
        };
    }, j.debounce = function (n, t, r) {
        var e, u, i, a, o, c = function () {
            var l = j.now() - a;
            t > l ? e = setTimeout(c, t - l) : (e = null, r || (o = n.apply(i, u), i = u = null));
        };
        return function () {
            i = this, u = arguments, a = j.now();
            var l = r && !e;
            return e || (e = setTimeout(c, t)), l && (o = n.apply(i, u), i = u = null), o;
        };
    }, j.once = function (n) {
        var t, r = !1;
        return function () {
            return r ? t : (r = !0, t = n.apply(this, arguments), n = null, t);
        };
    }, j.wrap = function (n, t) {
        return j.partial(t, n);
    }, j.compose = function () {
        var n = arguments;
        return function () {
            for (var t = arguments, r = n.length - 1; r >= 0; r--)
                t = [n[r].apply(this, t)];
            return t[0];
        };
    }, j.after = function (n, t) {
        return function () {
            return --n < 1 ? t.apply(this, arguments) : void 0;
        };
    }, j.keys = function (n) {
        if (!j.isObject(n))
            return [];
        if (w)
            return w(n);
        var t = [];
        for (var r in n)
            j.has(n, r) && t.push(r);
        return t;
    }, j.values = function (n) {
        for (var t = j.keys(n), r = t.length, e = new Array(r), u = 0; r > u; u++)
            e[u] = n[t[u]];
        return e;
    }, j.pairs = function (n) {
        for (var t = j.keys(n), r = t.length, e = new Array(r), u = 0; r > u; u++)
            e[u] = [t[u], n[t[u]]];
        return e;
    }, j.invert = function (n) {
        for (var t = {}, r = j.keys(n), e = 0, u = r.length; u > e; e++)
            t[n[r[e]]] = r[e];
        return t;
    }, j.functions = j.methods = function (n) {
        var t = [];
        for (var r in n)
            j.isFunction(n[r]) && t.push(r);
        return t.sort();
    }, j.extend = function (n) {
        return A(o.call(arguments, 1), function (t) {
            if (t)
                for (var r in t)
                    n[r] = t[r];
        }), n;
    }, j.pick = function (n) {
        var t = {}, r = c.apply(e, o.call(arguments, 1));
        return A(r, function (r) {
            r in n && (t[r] = n[r]);
        }), t;
    }, j.omit = function (n) {
        var t = {}, r = c.apply(e, o.call(arguments, 1));
        for (var u in n)
            j.contains(r, u) || (t[u] = n[u]);
        return t;
    }, j.defaults = function (n) {
        return A(o.call(arguments, 1), function (t) {
            if (t)
                for (var r in t)
                    n[r] === void 0 && (n[r] = t[r]);
        }), n;
    }, j.clone = function (n) {
        return j.isObject(n) ? j.isArray(n) ? n.slice() : j.extend({}, n) : n;
    }, j.tap = function (n, t) {
        return t(n), n;
    };
    var S = function (n, t, r, e) {
        if (n === t)
            return 0 !== n || 1 / n == 1 / t;
        if (null == n || null == t)
            return n === t;
        n instanceof j && (n = n._wrapped), t instanceof j && (t = t._wrapped);
        var u = l.call(n);
        if (u != l.call(t))
            return !1;
        switch (u) {
            case "[object String]": return n == String(t);
            case "[object Number]": return n != +n ? t != +t : 0 == n ? 1 / n == 1 / t : n == +t;
            case "[object Date]":
            case "[object Boolean]": return +n == +t;
            case "[object RegExp]": return n.source == t.source && n.global == t.global && n.multiline == t.multiline && n.ignoreCase == t.ignoreCase;
        }
        if ("object" != typeof n || "object" != typeof t)
            return !1;
        for (var i = r.length; i--;)
            if (r[i] == n)
                return e[i] == t;
        var a = n.constructor, o = t.constructor;
        if (a !== o && !(j.isFunction(a) && a instanceof a && j.isFunction(o) && o instanceof o) && "constructor" in n && "constructor" in t)
            return !1;
        r.push(n), e.push(t);
        var c = 0, f = !0;
        if ("[object Array]" == u) {
            if (c = n.length, f = c == t.length)
                for (; c-- && (f = S(n[c], t[c], r, e));)
                    ;
        }
        else {
            for (var s in n)
                if (j.has(n, s) && (c++, !(f = j.has(t, s) && S(n[s], t[s], r, e))))
                    break;
            if (f) {
                for (s in t)
                    if (j.has(t, s) && !c--)
                        break;
                f = !c;
            }
        }
        return r.pop(), e.pop(), f;
    };
    j.isEqual = function (n, t) {
        return S(n, t, [], []);
    }, j.isEmpty = function (n) {
        if (null == n)
            return !0;
        if (j.isArray(n) || j.isString(n))
            return 0 === n.length;
        for (var t in n)
            if (j.has(n, t))
                return !1;
        return !0;
    }, j.isElement = function (n) {
        return !(!n || 1 !== n.nodeType);
    }, j.isArray = x || function (n) {
        return "[object Array]" == l.call(n);
    }, j.isObject = function (n) {
        return n === Object(n);
    }, A(["Arguments", "Function", "String", "Number", "Date", "RegExp"], function (n) {
        j["is" + n] = function (t) {
            return l.call(t) == "[object " + n + "]";
        };
    }), j.isArguments(arguments) || (j.isArguments = function (n) {
        return !(!n || !j.has(n, "callee"));
    }), "function" != typeof /./ && (j.isFunction = function (n) {
        return "function" == typeof n;
    }), j.isFinite = function (n) {
        return isFinite(n) && !isNaN(parseFloat(n));
    }, j.isNaN = function (n) {
        return j.isNumber(n) && n != +n;
    }, j.isBoolean = function (n) {
        return n === !0 || n === !1 || "[object Boolean]" == l.call(n);
    }, j.isNull = function (n) {
        return null === n;
    }, j.isUndefined = function (n) {
        return n === void 0;
    }, j.has = function (n, t) {
        return f.call(n, t);
    }, j.noConflict = function () {
        return n._ = t, this;
    }, j.identity = function (n) {
        return n;
    }, j.constant = function (n) {
        return function () {
            return n;
        };
    }, j.property = function (n) {
        return function (t) {
            return t[n];
        };
    }, j.matches = function (n) {
        return function (t) {
            if (t === n)
                return !0;
            for (var r in n)
                if (n[r] !== t[r])
                    return !1;
            return !0;
        };
    }, j.times = function (n, t, r) {
        for (var e = Array(Math.max(0, n)), u = 0; n > u; u++)
            e[u] = t.call(r, u);
        return e;
    }, j.random = function (n, t) {
        return null == t && (t = n, n = 0), n + Math.floor(Math.random() * (t - n + 1));
    }, j.now = Date.now || function () {
        return (new Date).getTime();
    };
    var T = { escape: { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#x27;" } };
    T.unescape = j.invert(T.escape);
    var I = { escape: new RegExp("[" + j.keys(T.escape).join("") + "]", "g"), unescape: new RegExp("(" + j.keys(T.unescape).join("|") + ")", "g") };
    j.each(["escape", "unescape"], function (n) {
        j[n] = function (t) {
            return null == t ? "" : ("" + t).replace(I[n], function (t) {
                return T[n][t];
            });
        };
    }), j.result = function (n, t) {
        if (null == n)
            return void 0;
        var r = n[t];
        return j.isFunction(r) ? r.call(n) : r;
    }, j.mixin = function (n) {
        A(j.functions(n), function (t) {
            var r = j[t] = n[t];
            j.prototype[t] = function () {
                var n = [this._wrapped];
                return a.apply(n, arguments), z.call(this, r.apply(j, n));
            };
        });
    };
    var N = 0;
    j.uniqueId = function (n) {
        var t = ++N + "";
        return n ? n + t : t;
    }, j.templateSettings = { evaluate: /<%([\s\S]+?)%>/g, interpolate: /<%=([\s\S]+?)%>/g, escape: /<%-([\s\S]+?)%>/g };
    var q = /(.)^/, B = { "'": "'", "\\": "\\", "\r": "r", "\n": "n", "	": "t", "\u2028": "u2028", "\u2029": "u2029" }, D = /\\|'|\r|\n|\t|\u2028|\u2029/g;
    j.template = function (n, t, r) {
        var e;
        r = j.defaults({}, r, j.templateSettings);
        var u = new RegExp([(r.escape || q).source, (r.interpolate || q).source, (r.evaluate || q).source].join("|") + "|$", "g"), i = 0, a = "__p+='";
        n.replace(u, function (t, r, e, u, o) {
            return a += n.slice(i, o).replace(D, function (n) {
                return "\\" + B[n];
            }), r && (a += "'+\n((__t=(" + r + "))==null?'':_.escape(__t))+\n'"), e && (a += "'+\n((__t=(" + e + "))==null?'':__t)+\n'"), u && (a += "';\n" + u + "\n__p+='"), i = o + t.length, t;
        }), a += "';\n", r.variable || (a = "with(obj||{}){\n" + a + "}\n"), a = "var __t,__p='',__j=Array.prototype.join," + "print=function(){__p+=__j.call(arguments,'');};\n" + a + "return __p;\n";
        try {
            e = new Function(r.variable || "obj", "_", a);
        }
        catch (o) {
            throw o.source = a, o;
        }
        if (t)
            return e(t, j);
        var c = function (n) {
            return e.call(this, n, j);
        };
        return c.source = "function(" + (r.variable || "obj") + "){\n" + a + "}", c;
    }, j.chain = function (n) {
        return j(n).chain();
    };
    var z = function (n) {
        return this._chain ? j(n).chain() : n;
    };
    j.mixin(j), A(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function (n) {
        var t = e[n];
        j.prototype[n] = function () {
            var r = this._wrapped;
            return t.apply(r, arguments), "shift" != n && "splice" != n || 0 !== r.length || delete r[0], z.call(this, r);
        };
    }), A(["concat", "join", "slice"], function (n) {
        var t = e[n];
        j.prototype[n] = function () {
            return z.call(this, t.apply(this._wrapped, arguments));
        };
    }), j.extend(j.prototype, { chain: function () {
        return this._chain = !0, this;
    }, value: function () {
        return this._wrapped;
    } }), "function" == typeof define && define.amd && define("underscore", [], function () {
        return j;
    });
}).call(this);
window.wp = window.wp || {}, function (a, b) {
    var c, d, e = {}, f = Array.prototype.slice;
    c = function () {
    }, d = function (a, d, e) {
        var f;
        return f = d && d.hasOwnProperty("constructor") ? d.constructor : function () {
            var b = a.apply(this, arguments);
            return b;
        }, b.extend(f, a), c.prototype = a.prototype, f.prototype = new c, d && b.extend(f.prototype, d), e && b.extend(f, e), f.prototype.constructor = f, f.__super__ = a.prototype, f;
    }, e.Class = function (a, c, d) {
        var f, g = arguments;
        return a && c && e.Class.applicator === a && (g = c, b.extend(this, d || {})), f = this, this.instance && (f = function () {
            return f.instance.apply(f, arguments);
        }, b.extend(f, this)), f.initialize.apply(f, g), f;
    }, e.Class.extend = function (a, b) {
        var c = d(this, a, b);
        return c.extend = this.extend, c;
    }, e.Class.applicator = {}, e.Class.prototype.initialize = function () {
    }, e.Class.prototype.extended = function (a) {
        for (var b = this; "undefined" != typeof b.constructor;) {
            if (b.constructor === a)
                return !0;
            if ("undefined" == typeof b.constructor.__super__)
                return !1;
            b = b.constructor.__super__;
        }
        return !1;
    }, e.Events = { trigger: function (a) {
        return this.topics && this.topics[a] && this.topics[a].fireWith(this, f.call(arguments, 1)), this;
    }, bind: function (a) {
        return this.topics = this.topics || {}, this.topics[a] = this.topics[a] || b.Callbacks(), this.topics[a].add.apply(this.topics[a], f.call(arguments, 1)), this;
    }, unbind: function (a) {
        return this.topics && this.topics[a] && this.topics[a].remove.apply(this.topics[a], f.call(arguments, 1)), this;
    } }, e.Value = e.Class.extend({ initialize: function (a, c) {
        this._value = a, this.callbacks = b.Callbacks(), this._dirty = !1, b.extend(this, c || {}), this.set = b.proxy(this.set, this);
    }, instance: function () {
        return arguments.length ? this.set.apply(this, arguments) : this.get();
    }, get: function () {
        return this._value;
    }, set: function (a) {
        var b = this._value;
        return a = this._setter.apply(this, arguments), a = this.validate(a), null === a || _.isEqual(b, a) ? this : (this._value = a, this._dirty = !0, this.callbacks.fireWith(this, [a, b]), this);
    }, _setter: function (a) {
        return a;
    }, setter: function (a) {
        var b = this.get();
        return this._setter = a, this._value = null, this.set(b), this;
    }, resetSetter: function () {
        return this._setter = this.constructor.prototype._setter, this.set(this.get()), this;
    }, validate: function (a) {
        return a;
    }, bind: function () {
        return this.callbacks.add.apply(this.callbacks, arguments), this;
    }, unbind: function () {
        return this.callbacks.remove.apply(this.callbacks, arguments), this;
    }, link: function () {
        var a = this.set;
        return b.each(arguments, function () {
            this.bind(a);
        }), this;
    }, unlink: function () {
        var a = this.set;
        return b.each(arguments, function () {
            this.unbind(a);
        }), this;
    }, sync: function () {
        var a = this;
        return b.each(arguments, function () {
            a.link(this), this.link(a);
        }), this;
    }, unsync: function () {
        var a = this;
        return b.each(arguments, function () {
            a.unlink(this), this.unlink(a);
        }), this;
    } }), e.Values = e.Class.extend({ defaultConstructor: e.Value, initialize: function (a) {
        b.extend(this, a || {}), this._value = {}, this._deferreds = {};
    }, instance: function (a) {
        return 1 === arguments.length ? this.value(a) : this.when.apply(this, arguments);
    }, value: function (a) {
        return this._value[a];
    }, has: function (a) {
        return "undefined" != typeof this._value[a];
    }, add: function (a, b) {
        return this.has(a) ? this.value(a) : (this._value[a] = b, b.parent = this, b.extended(e.Value) && b.bind(this._change), this.trigger("add", b), this._deferreds[a] && this._deferreds[a].resolve(), this._value[a]);
    }, create: function (a) {
        return this.add(a, new this.defaultConstructor(e.Class.applicator, f.call(arguments, 1)));
    }, each: function (a, c) {
        c = "undefined" == typeof c ? this : c, b.each(this._value, function (b, d) {
            a.call(c, d, b);
        });
    }, remove: function (a) {
        var b;
        this.has(a) && (b = this.value(a), this.trigger("remove", b), b.extended(e.Value) && b.unbind(this._change), delete b.parent), delete this._value[a], delete this._deferreds[a];
    }, when: function () {
        var a = this, c = f.call(arguments), d = b.Deferred();
        return b.isFunction(c[c.length - 1]) && d.done(c.pop()), b.when.apply(b, b.map(c, function (c) {
            return a.has(c) ? void 0 : a._deferreds[c] = a._deferreds[c] || b.Deferred();
        })).done(function () {
            var e = b.map(c, function (b) {
                return a(b);
            });
            return e.length !== c.length ? void a.when.apply(a, c).done(function () {
                d.resolveWith(a, e);
            }) : void d.resolveWith(a, e);
        }), d.promise();
    }, _change: function () {
        this.parent.trigger("change", this);
    } }), b.extend(e.Values.prototype, e.Events), e.ensure = function (a) {
        return "string" == typeof a ? b(a) : a;
    }, e.Element = e.Value.extend({ initialize: function (a, c) {
        var d, f, g, h = this, i = e.Element.synchronizer.html;
        this.element = e.ensure(a), this.events = "", this.element.is("input, select, textarea") && (this.events += "change", i = e.Element.synchronizer.val, this.element.is("input") ? (d = this.element.prop("type"), e.Element.synchronizer[d] && (i = e.Element.synchronizer[d]), "text" === d || "password" === d ? this.events += " keyup" : "range" === d && (this.events += " input propertychange")) : this.element.is("textarea") && (this.events += " keyup")), e.Value.prototype.initialize.call(this, null, b.extend(c || {}, i)), this._value = this.get(), f = this.update, g = this.refresh, this.update = function (a) {
            a !== g.call(h) && f.apply(this, arguments);
        }, this.refresh = function () {
            h.set(g.call(h));
        }, this.bind(this.update), this.element.bind(this.events, this.refresh);
    }, find: function (a) {
        return b(a, this.element);
    }, refresh: function () {
    }, update: function () {
    } }), e.Element.synchronizer = {}, b.each(["html", "val"], function (a, b) {
        e.Element.synchronizer[b] = { update: function (a) {
            this.element[b](a);
        }, refresh: function () {
            return this.element[b]();
        } };
    }), e.Element.synchronizer.checkbox = { update: function (a) {
        this.element.prop("checked", a);
    }, refresh: function () {
        return this.element.prop("checked");
    } }, e.Element.synchronizer.radio = { update: function (a) {
        this.element.filter(function () {
            return this.value === a;
        }).prop("checked", !0);
    }, refresh: function () {
        return this.element.filter(":checked").val();
    } }, b.support.postMessage = !!window.postMessage, e.Messenger = e.Class.extend({ add: function (a, b, c) {
        return this[a] = new e.Value(b, c);
    }, initialize: function (a, c) {
        var d = window.parent == window ? null : window.parent;
        b.extend(this, c || {}), this.add("channel", a.channel), this.add("url", a.url || ""), this.add("origin", this.url()).link(this.url).setter(function (a) {
            return a.replace(/([^:]+:\/\/[^\/]+).*/, "$1");
        }), this.add("targetWindow", null), this.targetWindow.set = function (a) {
            var b = this._value;
            return a = this._setter.apply(this, arguments), a = this.validate(a), null === a || b === a ? this : (this._value = a, this._dirty = !0, this.callbacks.fireWith(this, [a, b]), this);
        }, this.targetWindow(a.targetWindow || d), this.receive = b.proxy(this.receive, this), this.receive.guid = b.guid++, b(window).on("message", this.receive);
    }, destroy: function () {
        b(window).off("message", this.receive);
    }, receive: function (a) {
        var b;
        a = a.originalEvent, this.targetWindow() && (this.origin() && a.origin !== this.origin() || "string" == typeof a.data && "{" === a.data[0] && (b = JSON.parse(a.data), b && b.id && "undefined" != typeof b.data && (!b.channel && !this.channel() || this.channel() === b.channel) && this.trigger(b.id, b.data)));
    }, send: function (a, b) {
        var c;
        b = "undefined" == typeof b ? null : b, this.url() && this.targetWindow() && (c = { id: a, data: b }, this.channel() && (c.channel = this.channel()), this.targetWindow().postMessage(JSON.stringify(c), this.origin()));
    } }), b.extend(e.Messenger.prototype, e.Events), e = b.extend(new e.Values, e), e.get = function () {
        var a = {};
        return this.each(function (b, c) {
            a[c] = b.get();
        }), a;
    }, a.customize = e;
}(wp, jQuery);
window.wp = window.wp || {}, function (a, b) {
    var c, d = wp.customize;
    b.extend(b.support, { history: !(!window.history || !history.pushState), hashchange: "onhashchange" in window && (void 0 === document.documentMode || document.documentMode > 7) }), c = b.extend({}, d.Events, { initialize: function () {
        this.body = b(document.body), c.settings && b.support.postMessage && (b.support.cors || !c.settings.isCrossDomain) && (this.window = b(window), this.element = b('<div id="customize-container" />').appendTo(this.body), this.bind("open", this.overlay.show), this.bind("close", this.overlay.hide), b("#wpbody").on("click", ".load-customize", function (a) {
            a.preventDefault(), c.link = b(this), c.open(c.link.attr("href"));
        }), b.support.history && this.window.on("popstate", c.popstate), b.support.hashchange && (this.window.on("hashchange", c.hashchange), this.window.triggerHandler("hashchange")));
    }, popstate: function (a) {
        var b = a.originalEvent.state;
        b && b.customize ? c.open(b.customize) : c.active && c.close();
    }, hashchange: function () {
        var a = window.location.toString().split("#")[1];
        a && 0 === a.indexOf("wp_customize=on") && c.open(c.settings.url + "?" + a), a || b.support.history || c.close();
    }, beforeunload: function () {
        return c.saved() ? void 0 : c.settings.l10n.saveAlert;
    }, open: function (a) {
        if (!this.active) {
            if (c.settings.browser.mobile)
                return window.location = a;
            this.originalDocumentTitle = document.title, this.active = !0, this.body.addClass("customize-loading"), this.saved = new d.Value(!0), this.iframe = b("<iframe />", { src: a, title: c.settings.l10n.mainIframeTitle }).appendTo(this.element), this.iframe.one("load", this.loaded), this.messenger = new d.Messenger({ url: a, channel: "loader", targetWindow: this.iframe[0].contentWindow }), this.messenger.bind("ready", function () {
                c.messenger.send("back");
            }), this.messenger.bind("close", function () {
                b.support.history ? history.back() : b.support.hashchange ? window.location.hash = "" : c.close();
            }), b(window).on("beforeunload", this.beforeunload), this.messenger.bind("activated", function (a) {
                a && (window.location = a);
            }), this.messenger.bind("saved", function () {
                c.saved(!0);
            }), this.messenger.bind("change", function () {
                c.saved(!1);
            }), this.messenger.bind("title", function (a) {
                window.document.title = a;
            }), this.pushState(a), this.trigger("open");
        }
    }, pushState: function (a) {
        var c = a.split("?")[1];
        b.support.history && window.location.href !== a ? history.pushState({ customize: a }, "", a) : !b.support.history && b.support.hashchange && c && (window.location.hash = "wp_customize=on&" + c), this.trigger("open");
    }, opened: function () {
        c.body.addClass("customize-active full-overlay-active");
    }, close: function () {
        if (this.active) {
            if (!this.saved() && !confirm(c.settings.l10n.saveAlert))
                return void history.forward();
            this.active = !1, this.trigger("close"), this.originalDocumentTitle && (document.title = this.originalDocumentTitle), this.link && this.link.focus();
        }
    }, closed: function () {
        c.iframe.remove(), c.messenger.destroy(), c.iframe = null, c.messenger = null, c.saved = null, c.body.removeClass("customize-active full-overlay-active").removeClass("customize-loading"), b(window).off("beforeunload", c.beforeunload);
    }, loaded: function () {
        c.body.removeClass("customize-loading");
    }, overlay: { show: function () {
        this.element.fadeIn(200, c.opened);
    }, hide: function () {
        this.element.fadeOut(200, c.closed);
    } } }), b(function () {
        c.settings = _wpCustomizeLoaderSettings, c.initialize();
    }), d.Loader = c;
}(wp, jQuery);
/*
 * Thickbox 3.1 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
*/
if (typeof tb_pathToImage != 'string') {
    var tb_pathToImage = thickboxL10n.loadingAnimation;
}
/*!!!!!!!!!!!!!!!!! edit below this line at your own risk !!!!!!!!!!!!!!!!!!!!!!!*/
//on page load call tb_init
jQuery(document).ready(function () {
    tb_init('a.thickbox, area.thickbox, input.thickbox'); //pass where to apply thickbox
    imgLoader = new Image(); // preload image
    imgLoader.src = tb_pathToImage;
});
/*
 * Add thickbox to href & area elements that have a class of .thickbox.
 * Remove the loading indicator when content in an iframe has loaded.
 */
function tb_init(domChunk) {
    jQuery('body').on('click', domChunk, tb_click).on('thickbox:iframe:loaded', function () {
        jQuery('#TB_window').removeClass('thickbox-loading');
    });
}
function tb_click() {
    var t = this.title || this.name || null;
    var a = this.href || this.alt;
    var g = this.rel || false;
    tb_show(t, a, g);
    this.blur();
    return false;
}
function tb_show(caption, url, imageGroup) {
    try {
        if (typeof document.body.style.maxHeight === "undefined") {
            jQuery("body", "html").css({ height: "100%", width: "100%" });
            jQuery("html").css("overflow", "hidden");
            if (document.getElementById("TB_HideSelect") === null) {
                jQuery("body").append("<iframe id='TB_HideSelect'>" + thickboxL10n.noiframes + "</iframe><div id='TB_overlay'></div><div id='TB_window' class='thickbox-loading'></div>");
                jQuery("#TB_overlay").click(tb_remove);
            }
        }
        else {
            if (document.getElementById("TB_overlay") === null) {
                jQuery("body").append("<div id='TB_overlay'></div><div id='TB_window' class='thickbox-loading'></div>");
                jQuery("#TB_overlay").click(tb_remove);
                jQuery('body').addClass('modal-open');
            }
        }
        if (tb_detectMacXFF()) {
            jQuery("#TB_overlay").addClass("TB_overlayMacFFBGHack"); //use png overlay so hide flash
        }
        else {
            jQuery("#TB_overlay").addClass("TB_overlayBG"); //use background and opacity
        }
        if (caption === null) {
            caption = "";
        }
        jQuery("body").append("<div id='TB_load'><img src='" + imgLoader.src + "' width='208' /></div>"); //add loader to the page
        jQuery('#TB_load').show(); //show loader
        var baseURL;
        if (url.indexOf("?") !== -1) {
            baseURL = url.substr(0, url.indexOf("?"));
        }
        else {
            baseURL = url;
        }
        var urlString = /\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
        var urlType = baseURL.toLowerCase().match(urlString);
        if (urlType == '.jpg' || urlType == '.jpeg' || urlType == '.png' || urlType == '.gif' || urlType == '.bmp') {
            TB_PrevCaption = "";
            TB_PrevURL = "";
            TB_PrevHTML = "";
            TB_NextCaption = "";
            TB_NextURL = "";
            TB_NextHTML = "";
            TB_imageCount = "";
            TB_FoundURL = false;
            if (imageGroup) {
                TB_TempArray = jQuery("a[rel=" + imageGroup + "]").get();
                for (TB_Counter = 0; ((TB_Counter < TB_TempArray.length) && (TB_NextHTML === "")); TB_Counter++) {
                    var urlTypeTemp = TB_TempArray[TB_Counter].href.toLowerCase().match(urlString);
                    if (!(TB_TempArray[TB_Counter].href == url)) {
                        if (TB_FoundURL) {
                            TB_NextCaption = TB_TempArray[TB_Counter].title;
                            TB_NextURL = TB_TempArray[TB_Counter].href;
                            TB_NextHTML = "<span id='TB_next'>&nbsp;&nbsp;<a href='#'>" + thickboxL10n.next + "</a></span>";
                        }
                        else {
                            TB_PrevCaption = TB_TempArray[TB_Counter].title;
                            TB_PrevURL = TB_TempArray[TB_Counter].href;
                            TB_PrevHTML = "<span id='TB_prev'>&nbsp;&nbsp;<a href='#'>" + thickboxL10n.prev + "</a></span>";
                        }
                    }
                    else {
                        TB_FoundURL = true;
                        TB_imageCount = thickboxL10n.image + ' ' + (TB_Counter + 1) + ' ' + thickboxL10n.of + ' ' + (TB_TempArray.length);
                    }
                }
            }
            imgPreloader = new Image();
            imgPreloader.onload = function () {
                imgPreloader.onload = null;
                // Resizing large images - original by Christian Montoya edited by me.
                var pagesize = tb_getPageSize();
                var x = pagesize[0] - 150;
                var y = pagesize[1] - 150;
                var imageWidth = imgPreloader.width;
                var imageHeight = imgPreloader.height;
                if (imageWidth > x) {
                    imageHeight = imageHeight * (x / imageWidth);
                    imageWidth = x;
                    if (imageHeight > y) {
                        imageWidth = imageWidth * (y / imageHeight);
                        imageHeight = y;
                    }
                }
                else if (imageHeight > y) {
                    imageWidth = imageWidth * (y / imageHeight);
                    imageHeight = y;
                    if (imageWidth > x) {
                        imageHeight = imageHeight * (x / imageWidth);
                        imageWidth = x;
                    }
                }
                // End Resizing
                TB_WIDTH = imageWidth + 30;
                TB_HEIGHT = imageHeight + 60;
                jQuery("#TB_window").append("<a href='' id='TB_ImageOff'><span class='screen-reader-text'>" + thickboxL10n.close + "</span><img id='TB_Image' src='" + url + "' width='" + imageWidth + "' height='" + imageHeight + "' alt='" + caption + "'/></a>" + "<div id='TB_caption'>" + caption + "<div id='TB_secondLine'>" + TB_imageCount + TB_PrevHTML + TB_NextHTML + "</div></div><div id='TB_closeWindow'><a href='#' id='TB_closeWindowButton'><span class='screen-reader-text'>" + thickboxL10n.close + "</span><div class='tb-close-icon'></div></a></div>");
                jQuery("#TB_closeWindowButton").click(tb_remove);
                if (!(TB_PrevHTML === "")) {
                    function goPrev() {
                        if (jQuery(document).unbind("click", goPrev)) {
                            jQuery(document).unbind("click", goPrev);
                        }
                        jQuery("#TB_window").remove();
                        jQuery("body").append("<div id='TB_window'></div>");
                        tb_show(TB_PrevCaption, TB_PrevURL, imageGroup);
                        return false;
                    }
                    jQuery("#TB_prev").click(goPrev);
                }
                if (!(TB_NextHTML === "")) {
                    function goNext() {
                        jQuery("#TB_window").remove();
                        jQuery("body").append("<div id='TB_window'></div>");
                        tb_show(TB_NextCaption, TB_NextURL, imageGroup);
                        return false;
                    }
                    jQuery("#TB_next").click(goNext);
                }
                jQuery(document).bind('keydown.thickbox', function (e) {
                    if (e.which == 27) {
                        tb_remove();
                    }
                    else if (e.which == 190) {
                        if (!(TB_NextHTML == "")) {
                            jQuery(document).unbind('thickbox');
                            goNext();
                        }
                    }
                    else if (e.which == 188) {
                        if (!(TB_PrevHTML == "")) {
                            jQuery(document).unbind('thickbox');
                            goPrev();
                        }
                    }
                    return false;
                });
                tb_position();
                jQuery("#TB_load").remove();
                jQuery("#TB_ImageOff").click(tb_remove);
                jQuery("#TB_window").css({ 'visibility': 'visible' }); //for safari using css instead of show
            };
            imgPreloader.src = url;
        }
        else {
            var queryString = url.replace(/^[^\?]+\??/, '');
            var params = tb_parseQuery(queryString);
            TB_WIDTH = (params['width'] * 1) + 30 || 630; //defaults to 630 if no parameters were added to URL
            TB_HEIGHT = (params['height'] * 1) + 40 || 440; //defaults to 440 if no parameters were added to URL
            ajaxContentW = TB_WIDTH - 30;
            ajaxContentH = TB_HEIGHT - 45;
            if (url.indexOf('TB_iframe') != -1) {
                urlNoQuery = url.split('TB_');
                jQuery("#TB_iframeContent").remove();
                if (params['modal'] != "true") {
                    jQuery("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>" + caption + "</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton'><span class='screen-reader-text'>" + thickboxL10n.close + "</span><div class='tb-close-icon'></div></a></div></div><iframe frameborder='0' hspace='0' allowTransparency='true' src='" + urlNoQuery[0] + "' id='TB_iframeContent' name='TB_iframeContent" + Math.round(Math.random() * 1000) + "' onload='tb_showIframe()' style='width:" + (ajaxContentW + 29) + "px;height:" + (ajaxContentH + 17) + "px;' >" + thickboxL10n.noiframes + "</iframe>");
                }
                else {
                    jQuery("#TB_overlay").unbind();
                    jQuery("#TB_window").append("<iframe frameborder='0' hspace='0' allowTransparency='true' src='" + urlNoQuery[0] + "' id='TB_iframeContent' name='TB_iframeContent" + Math.round(Math.random() * 1000) + "' onload='tb_showIframe()' style='width:" + (ajaxContentW + 29) + "px;height:" + (ajaxContentH + 17) + "px;'>" + thickboxL10n.noiframes + "</iframe>");
                }
            }
            else {
                if (jQuery("#TB_window").css("visibility") != "visible") {
                    if (params['modal'] != "true") {
                        jQuery("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>" + caption + "</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton'><div class='tb-close-icon'></div></a></div></div><div id='TB_ajaxContent' style='width:" + ajaxContentW + "px;height:" + ajaxContentH + "px'></div>");
                    }
                    else {
                        jQuery("#TB_overlay").unbind();
                        jQuery("#TB_window").append("<div id='TB_ajaxContent' class='TB_modal' style='width:" + ajaxContentW + "px;height:" + ajaxContentH + "px;'></div>");
                    }
                }
                else {
                    jQuery("#TB_ajaxContent")[0].style.width = ajaxContentW + "px";
                    jQuery("#TB_ajaxContent")[0].style.height = ajaxContentH + "px";
                    jQuery("#TB_ajaxContent")[0].scrollTop = 0;
                    jQuery("#TB_ajaxWindowTitle").html(caption);
                }
            }
            jQuery("#TB_closeWindowButton").click(tb_remove);
            if (url.indexOf('TB_inline') != -1) {
                jQuery("#TB_ajaxContent").append(jQuery('#' + params['inlineId']).children());
                jQuery("#TB_window").bind('tb_unload', function () {
                    jQuery('#' + params['inlineId']).append(jQuery("#TB_ajaxContent").children()); // move elements back when you're finished
                });
                tb_position();
                jQuery("#TB_load").remove();
                jQuery("#TB_window").css({ 'visibility': 'visible' });
            }
            else if (url.indexOf('TB_iframe') != -1) {
                tb_position();
                jQuery("#TB_load").remove();
                jQuery("#TB_window").css({ 'visibility': 'visible' });
            }
            else {
                var load_url = url;
                load_url += -1 === url.indexOf('?') ? '?' : '&';
                jQuery("#TB_ajaxContent").load(load_url += "random=" + (new Date().getTime()), function () {
                    tb_position();
                    jQuery("#TB_load").remove();
                    tb_init("#TB_ajaxContent a.thickbox");
                    jQuery("#TB_window").css({ 'visibility': 'visible' });
                });
            }
        }
        if (!params['modal']) {
            jQuery(document).bind('keydown.thickbox', function (e) {
                if (e.which == 27) {
                    tb_remove();
                    return false;
                }
            });
        }
    }
    catch (e) {
    }
}
//helper functions below
function tb_showIframe() {
    jQuery("#TB_load").remove();
    jQuery("#TB_window").css({ 'visibility': 'visible' }).trigger('thickbox:iframe:loaded');
}
function tb_remove() {
    jQuery("#TB_imageOff").unbind("click");
    jQuery("#TB_closeWindowButton").unbind("click");
    jQuery("#TB_window").fadeOut("fast", function () {
        jQuery('#TB_window,#TB_overlay,#TB_HideSelect').trigger("tb_unload").unbind().remove();
    });
    jQuery('body').removeClass('modal-open');
    jQuery("#TB_load").remove();
    if (typeof document.body.style.maxHeight == "undefined") {
        jQuery("body", "html").css({ height: "auto", width: "auto" });
        jQuery("html").css("overflow", "");
    }
    jQuery(document).unbind('.thickbox');
    return false;
}
function tb_position() {
    var isIE6 = typeof document.body.style.maxHeight === "undefined";
    jQuery("#TB_window").css({ marginLeft: '-' + parseInt((TB_WIDTH / 2), 10) + 'px', width: TB_WIDTH + 'px' });
    if (!isIE6) {
        jQuery("#TB_window").css({ marginTop: '-' + parseInt((TB_HEIGHT / 2), 10) + 'px' });
    }
}
function tb_parseQuery(query) {
    var Params = {};
    if (!query) {
        return Params;
    } // return empty object
    var Pairs = query.split(/[;&]/);
    for (var i = 0; i < Pairs.length; i++) {
        var KeyVal = Pairs[i].split('=');
        if (!KeyVal || KeyVal.length != 2) {
            continue;
        }
        var key = unescape(KeyVal[0]);
        var val = unescape(KeyVal[1]);
        val = val.replace(/\+/g, ' ');
        Params[key] = val;
    }
    return Params;
}
function tb_getPageSize() {
    var de = document.documentElement;
    var w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
    var h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
    arrayPageSize = [w, h];
    return arrayPageSize;
}
function tb_detectMacXFF() {
    var userAgent = navigator.userAgent.toLowerCase();
    if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox') != -1) {
        return true;
    }
}
var tb_position;
jQuery(document).ready(function (a) {
    tb_position = function () {
        var b = a("#TB_window"), c = a(window).width(), d = a(window).height() - (c > 792 ? 60 : 20), e = c > 792 ? 772 : c - 20;
        return b.size() && (b.width(e).height(d), a("#TB_iframeContent").width(e).height(d), b.css({ "margin-left": "-" + parseInt(e / 2, 10) + "px" }), "undefined" != typeof document.body.style.maxWidth && b.css({ top: "30px", "margin-top": "0" })), a("a.thickbox").each(function () {
            var b = a(this).attr("href");
            b && (b = b.replace(/&width=[0-9]+/g, ""), b = b.replace(/&height=[0-9]+/g, ""), a(this).attr("href", b + "&width=" + e + "&height=" + d));
        });
    }, a(window).resize(function () {
        tb_position();
    }), a(".plugin-card, .plugins .plugin-version-author-uri").on("click", "a.thickbox", function (b) {
        b.preventDefault(), b.stopPropagation(), tb_click.call(this), a("#TB_title").css({ "background-color": "#23282d", color: "#cfcfcf" }), a("#TB_ajaxWindowTitle").html("<strong>" + plugininstallL10n.plugin_information + "</strong>&nbsp;" + a(this).data("title")), a("#TB_iframeContent").attr("title", plugininstallL10n.plugin_information + " " + a(this).data("title")), a("#TB_closeWindowButton").focus();
    }), a("#plugin-information-tabs a").click(function (b) {
        var c = a(this).attr("name");
        b.preventDefault(), a("#plugin-information-tabs a.current").removeClass("current"), a(this).addClass("current"), "description" !== c && a(window).width() < 772 ? a("#plugin-information-content").find(".fyi").hide() : a("#plugin-information-content").find(".fyi").show(), a("#section-holder div.section").hide(), a("#section-" + c).show();
    });
});
window.wp = window.wp || {}, function () {
    wp.shortcode = { next: function (a, b, c) {
        var d, e, f = wp.shortcode.regexp(a);
        return f.lastIndex = c || 0, (d = f.exec(b)) ? "[" === d[1] && "]" === d[7] ? wp.shortcode.next(a, b, f.lastIndex) : (e = { index: d.index, content: d[0], shortcode: wp.shortcode.fromMatch(d) }, d[1] && (e.content = e.content.slice(1), e.index++), d[7] && (e.content = e.content.slice(0, -1)), e) : void 0;
    }, replace: function (a, b, c) {
        return b.replace(wp.shortcode.regexp(a), function (a, b, d, e, f, g, h, i) {
            if ("[" === b && "]" === i)
                return a;
            var j = c(wp.shortcode.fromMatch(arguments));
            return j ? b + j + i : a;
        });
    }, string: function (a) {
        return new wp.shortcode(a).string();
    }, regexp: _.memoize(function (a) {
        return new RegExp("\\[(\\[?)(" + a + ")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)", "g");
    }), attrs: _.memoize(function (a) {
        var b, c, d = {}, e = [];
        for (b = /([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*'([^']*)'(?:\s|$)|([\w-]+)\s*=\s*([^\s'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/g, a = a.replace(/[\u00a0\u200b]/g, " "); c = b.exec(a);)
            c[1] ? d[c[1].toLowerCase()] = c[2] : c[3] ? d[c[3].toLowerCase()] = c[4] : c[5] ? d[c[5].toLowerCase()] = c[6] : c[7] ? e.push(c[7]) : c[8] && e.push(c[8]);
        return { named: d, numeric: e };
    }), fromMatch: function (a) {
        var b;
        return b = a[4] ? "self-closing" : a[6] ? "closed" : "single", new wp.shortcode({ tag: a[2], attrs: a[3], type: b, content: a[5] });
    } }, wp.shortcode = _.extend(function (a) {
        _.extend(this, _.pick(a || {}, "tag", "attrs", "type", "content"));
        var b = this.attrs;
        this.attrs = { named: {}, numeric: [] }, b && (_.isString(b) ? this.attrs = wp.shortcode.attrs(b) : _.isEqual(_.keys(b), ["named", "numeric"]) ? this.attrs = b : _.each(a.attrs, function (a, b) {
            this.set(b, a);
        }, this));
    }, wp.shortcode), _.extend(wp.shortcode.prototype, { get: function (a) {
        return this.attrs[_.isNumber(a) ? "numeric" : "named"][a];
    }, set: function (a, b) {
        return this.attrs[_.isNumber(a) ? "numeric" : "named"][a] = b, this;
    }, string: function () {
        var a = "[" + this.tag;
        return _.each(this.attrs.numeric, function (b) {
            a += /\s/.test(b) ? ' "' + b + '"' : " " + b;
        }), _.each(this.attrs.named, function (b, c) {
            a += " " + c + '="' + b + '"';
        }), "single" === this.type ? a + "]" : "self-closing" === this.type ? a + " /]" : (a += "]", this.content && (a += this.content), a + "[/" + this.tag + "]");
    } });
}(), function () {
    wp.html = _.extend(wp.html || {}, { attrs: function (a) {
        var b, c;
        return "/" === a[a.length - 1] && (a = a.slice(0, -1)), b = wp.shortcode.attrs(a), c = b.named, _.each(b.numeric, function (a) {
            /\s/.test(a) || (c[a] = "");
        }), c;
    }, string: function (a) {
        var b = "<" + a.tag, c = a.content || "";
        return _.each(a.attrs, function (a, c) {
            b += " " + c, "" !== a && (_.isBoolean(a) && (a = a ? "true" : "false"), b += '="' + a + '"');
        }), a.single ? b + " />" : (b += ">", b += _.isObject(c) ? wp.html.string(c) : c, b + "</" + a.tag + ">");
    } });
}();
var wpActiveEditor, send_to_editor;
send_to_editor = function (a) {
    var b, c = "undefined" != typeof tinymce, d = "undefined" != typeof QTags;
    if (wpActiveEditor)
        c && (b = tinymce.get(wpActiveEditor));
    else if (c && tinymce.activeEditor)
        b = tinymce.activeEditor, wpActiveEditor = b.id;
    else if (!d)
        return !1;
    if (b && !b.isHidden() ? b.execCommand("mceInsertContent", !1, a) : d ? QTags.insertContent(a) : document.getElementById(wpActiveEditor).value += a, window.tb_remove)
        try {
            window.tb_remove();
        }
        catch (e) {
        }
};
var tb_position;
!function (a) {
    tb_position = function () {
        var b = a("#TB_window"), c = a(window).width(), d = a(window).height(), e = c > 833 ? 833 : c, f = 0;
        return a("#wpadminbar").length && (f = parseInt(a("#wpadminbar").css("height"), 10)), b.size() && (b.width(e - 50).height(d - 45 - f), a("#TB_iframeContent").width(e - 50).height(d - 75 - f), b.css({ "margin-left": "-" + parseInt((e - 50) / 2, 10) + "px" }), "undefined" != typeof document.body.style.maxWidth && b.css({ top: 20 + f + "px", "margin-top": "0" })), a("a.thickbox").each(function () {
            var b = a(this).attr("href");
            b && (b = b.replace(/&width=[0-9]+/g, ""), b = b.replace(/&height=[0-9]+/g, ""), a(this).attr("href", b + "&width=" + (e - 80) + "&height=" + (d - 85 - f)));
        });
    }, a(window).resize(function () {
        tb_position();
    });
}(jQuery);
/**
 * Attempt to re-color SVG icons used in the admin menu or the toolbar
 *
 */
window.wp = window.wp || {};
wp.svgPainter = (function ($, window, document, undefined) {
    'use strict';
    var selector, base64, painter, colorscheme = {}, elements = [];
    $(document).ready(function () {
        // detection for browser SVG capability
        if (document.implementation.hasFeature('http://www.w3.org/TR/SVG11/feature#Image', '1.1')) {
            $(document.body).removeClass('no-svg').addClass('svg');
            wp.svgPainter.init();
        }
    });
    /**
     * Needed only for IE9
     *
     * Based on jquery.base64.js 0.0.3 - https://github.com/yckart/jquery.base64.js
     *
     * Based on: https://gist.github.com/Yaffle/1284012
     *
     * Copyright (c) 2012 Yannick Albert (http://yckart.com)
     * Licensed under the MIT license
     * http://www.opensource.org/licenses/mit-license.php
     */
    base64 = (function () {
        var c, b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/', a256 = '', r64 = [256], r256 = [256], i = 0;
        function init() {
            while (i < 256) {
                c = String.fromCharCode(i);
                a256 += c;
                r256[i] = i;
                r64[i] = b64.indexOf(c);
                ++i;
            }
        }
        function code(s, discard, alpha, beta, w1, w2) {
            var tmp, length, buffer = 0, i = 0, result = '', bitsInBuffer = 0;
            s = String(s);
            length = s.length;
            while (i < length) {
                c = s.charCodeAt(i);
                c = c < 256 ? alpha[c] : -1;
                buffer = (buffer << w1) + c;
                bitsInBuffer += w1;
                while (bitsInBuffer >= w2) {
                    bitsInBuffer -= w2;
                    tmp = buffer >> bitsInBuffer;
                    result += beta.charAt(tmp);
                    buffer ^= tmp << bitsInBuffer;
                }
                ++i;
            }
            if (!discard && bitsInBuffer > 0) {
                result += beta.charAt(buffer << (w2 - bitsInBuffer));
            }
            return result;
        }
        function btoa(plain) {
            if (!c) {
                init();
            }
            plain = code(plain, false, r256, b64, 8, 6);
            return plain + '===='.slice((plain.length % 4) || 4);
        }
        function atob(coded) {
            var i;
            if (!c) {
                init();
            }
            coded = coded.replace(/[^A-Za-z0-9\+\/\=]/g, '');
            coded = String(coded).split('=');
            i = coded.length;
            do {
                --i;
                coded[i] = code(coded[i], true, r64, a256, 6, 8);
            } while (i > 0);
            coded = coded.join('');
            return coded;
        }
        return {
            atob: atob,
            btoa: btoa
        };
    })();
    return {
        init: function () {
            painter = this;
            selector = $('#adminmenu .wp-menu-image, #wpadminbar .ab-item');
            this.setColors();
            this.findElements();
            this.paint();
        },
        setColors: function (colors) {
            if (typeof colors === 'undefined' && typeof window._wpColorScheme !== 'undefined') {
                colors = window._wpColorScheme;
            }
            if (colors && colors.icons && colors.icons.base && colors.icons.current && colors.icons.focus) {
                colorscheme = colors.icons;
            }
        },
        findElements: function () {
            selector.each(function () {
                var $this = $(this), bgImage = $this.css('background-image');
                if (bgImage && bgImage.indexOf('data:image/svg+xml;base64') != -1) {
                    elements.push($this);
                }
            });
        },
        paint: function () {
            // loop through all elements
            $.each(elements, function (index, $element) {
                var $menuitem = $element.parent().parent();
                if ($menuitem.hasClass('current') || $menuitem.hasClass('wp-has-current-submenu')) {
                    // paint icon in 'current' color
                    painter.paintElement($element, 'current');
                }
                else {
                    // paint icon in base color
                    painter.paintElement($element, 'base');
                    // set hover callbacks
                    $menuitem.hover(function () {
                        painter.paintElement($element, 'focus');
                    }, function () {
                        // Match the delay from hoverIntent
                        window.setTimeout(function () {
                            painter.paintElement($element, 'base');
                        }, 100);
                    });
                }
            });
        },
        paintElement: function ($element, colorType) {
            var xml, encoded, color;
            if (!colorType || !colorscheme.hasOwnProperty(colorType)) {
                return;
            }
            color = colorscheme[colorType];
            // only accept hex colors: #101 or #101010
            if (!color.match(/^(#[0-9a-f]{3}|#[0-9a-f]{6})$/i)) {
                return;
            }
            xml = $element.data('wp-ui-svg-' + color);
            if (xml === 'none') {
                return;
            }
            if (!xml) {
                encoded = $element.css('background-image').match(/.+data:image\/svg\+xml;base64,([A-Za-z0-9\+\/\=]+)/);
                if (!encoded || !encoded[1]) {
                    $element.data('wp-ui-svg-' + color, 'none');
                    return;
                }
                try {
                    if ('atob' in window) {
                        xml = window.atob(encoded[1]);
                    }
                    else {
                        xml = base64.atob(encoded[1]);
                    }
                }
                catch (error) {
                }
                if (xml) {
                    // replace `fill` attributes
                    xml = xml.replace(/fill="(.+?)"/g, 'fill="' + color + '"');
                    // replace `style` attributes
                    xml = xml.replace(/style="(.+?)"/g, 'style="fill:' + color + '"');
                    // replace `fill` properties in `<style>` tags
                    xml = xml.replace(/fill:.*?;/g, 'fill: ' + color + ';');
                    if ('btoa' in window) {
                        xml = window.btoa(xml);
                    }
                    else {
                        xml = base64.btoa(xml);
                    }
                    $element.data('wp-ui-svg-' + color, xml);
                }
                else {
                    $element.data('wp-ui-svg-' + color, 'none');
                    return;
                }
            }
            $element.attr('style', 'background-image: url("data:image/svg+xml;base64,' + xml + '") !important;');
        }
    };
})(jQuery, window, document);
!function (a, b, c) {
    var d = function () {
        function d() {
            var c, d, f, h;
            "string" == typeof b.pagenow && (z.screenId = b.pagenow), "string" == typeof b.ajaxurl && (z.url = b.ajaxurl), "object" == typeof b.heartbeatSettings && (c = b.heartbeatSettings, !z.url && c.ajaxurl && (z.url = c.ajaxurl), c.interval && (z.mainInterval = c.interval, z.mainInterval < 15 ? z.mainInterval = 15 : z.mainInterval > 120 && (z.mainInterval = 120)), c.minimalInterval && (c.minimalInterval = parseInt(c.minimalInterval, 10), z.minimalInterval = c.minimalInterval > 0 && c.minimalInterval <= 600 ? 1e3 * c.minimalInterval : 0), z.minimalInterval && z.mainInterval < z.minimalInterval && (z.mainInterval = z.minimalInterval), z.screenId || (z.screenId = c.screenId || "front"), "disable" === c.suspension && (z.suspendEnabled = !1)), z.mainInterval = 1e3 * z.mainInterval, z.originalInterval = z.mainInterval, "undefined" != typeof document.hidden ? (d = "hidden", h = "visibilitychange", f = "visibilityState") : "undefined" != typeof document.msHidden ? (d = "msHidden", h = "msvisibilitychange", f = "msVisibilityState") : "undefined" != typeof document.webkitHidden && (d = "webkitHidden", h = "webkitvisibilitychange", f = "webkitVisibilityState"), d && (document[d] && (z.hasFocus = !1), y.on(h + ".wp-heartbeat", function () {
                "hidden" === document[f] ? (l(), b.clearInterval(z.checkFocusTimer)) : (m(), document.hasFocus && (z.checkFocusTimer = b.setInterval(g, 1e4)));
            })), document.hasFocus && (z.checkFocusTimer = b.setInterval(g, 1e4)), a(b).on("unload.wp-heartbeat", function () {
                z.suspend = !0, z.xhr && 4 !== z.xhr.readyState && z.xhr.abort();
            }), b.setInterval(o, 3e4), y.ready(function () {
                z.lastTick = e(), k();
            });
        }
        function e() {
            return (new Date).getTime();
        }
        function f(a) {
            var c, d = a.src;
            if (d && /^https?:\/\//.test(d) && (c = b.location.origin ? b.location.origin : b.location.protocol + "//" + b.location.host, 0 !== d.indexOf(c)))
                return !1;
            try {
                if (a.contentWindow.document)
                    return !0;
            }
            catch (e) {
            }
            return !1;
        }
        function g() {
            z.hasFocus && !document.hasFocus() ? l() : !z.hasFocus && document.hasFocus() && m();
        }
        function h(a, b) {
            var c;
            if (a) {
                switch (a) {
                    case "abort": break;
                    case "timeout":
                        c = !0;
                        break;
                    case "error": if (503 === b && z.hasConnected) {
                        c = !0;
                        break;
                    }
                    case "parsererror":
                    case "empty":
                    case "unknown": z.errorcount++, z.errorcount > 2 && z.hasConnected && (c = !0);
                }
                c && !q() && (z.connectionError = !0, y.trigger("heartbeat-connection-lost", [a, b]));
            }
        }
        function i() {
            z.hasConnected = !0, q() && (z.errorcount = 0, z.connectionError = !1, y.trigger("heartbeat-connection-restored"));
        }
        function j() {
            var c, d;
            z.connecting || z.suspend || (z.lastTick = e(), d = a.extend({}, z.queue), z.queue = {}, y.trigger("heartbeat-send", [d]), c = { data: d, interval: z.tempInterval ? z.tempInterval / 1e3 : z.mainInterval / 1e3, _nonce: "object" == typeof b.heartbeatSettings ? b.heartbeatSettings.nonce : "", action: "heartbeat", screen_id: z.screenId, has_focus: z.hasFocus }, z.connecting = !0, z.xhr = a.ajax({ url: z.url, type: "post", timeout: 3e4, data: c, dataType: "json" }).always(function () {
                z.connecting = !1, k();
            }).done(function (a, b, c) {
                var d;
                return a ? (i(), a.nonces_expired && y.trigger("heartbeat-nonces-expired"), a.heartbeat_interval && (d = a.heartbeat_interval, delete a.heartbeat_interval), y.trigger("heartbeat-tick", [a, b, c]), void (d && t(d))) : void h("empty");
            }).fail(function (a, b, c) {
                h(b || "unknown", a.status), y.trigger("heartbeat-error", [a, b, c]);
            }));
        }
        function k() {
            var a = e() - z.lastTick, c = z.mainInterval;
            z.suspend || (z.hasFocus ? z.countdown > 0 && z.tempInterval && (c = z.tempInterval, z.countdown--, z.countdown < 1 && (z.tempInterval = 0)) : c = 12e4, z.minimalInterval && c < z.minimalInterval && (c = z.minimalInterval), b.clearTimeout(z.beatTimer), c > a ? z.beatTimer = b.setTimeout(function () {
                j();
            }, c - a) : j());
        }
        function l() {
            z.hasFocus = !1;
        }
        function m() {
            z.userActivity = e(), z.suspend = !1, z.hasFocus || (z.hasFocus = !0, k());
        }
        function n() {
            z.userActivityEvents = !1, y.off(".wp-heartbeat-active"), a("iframe").each(function (b, c) {
                f(c) && a(c.contentWindow).off(".wp-heartbeat-active");
            }), m();
        }
        function o() {
            var b = z.userActivity ? e() - z.userActivity : 0;
            b > 3e5 && z.hasFocus && l(), (z.suspendEnabled && b > 6e5 || b > 36e5) && (z.suspend = !0), z.userActivityEvents || (y.on("mouseover.wp-heartbeat-active keyup.wp-heartbeat-active touchend.wp-heartbeat-active", function () {
                n();
            }), a("iframe").each(function (b, c) {
                f(c) && a(c.contentWindow).on("mouseover.wp-heartbeat-active keyup.wp-heartbeat-active touchend.wp-heartbeat-active", function () {
                    n();
                });
            }), z.userActivityEvents = !0);
        }
        function p() {
            return z.hasFocus;
        }
        function q() {
            return z.connectionError;
        }
        function r() {
            z.lastTick = 0, k();
        }
        function s() {
            z.suspendEnabled = !1;
        }
        function t(a, b) {
            var c, d = z.tempInterval ? z.tempInterval : z.mainInterval;
            if (a) {
                switch (a) {
                    case "fast":
                    case 5:
                        c = 5e3;
                        break;
                    case 15:
                        c = 15e3;
                        break;
                    case 30:
                        c = 3e4;
                        break;
                    case 60:
                        c = 6e4;
                        break;
                    case 120:
                        c = 12e4;
                        break;
                    case "long-polling": return z.mainInterval = 0, 0;
                    default: c = z.originalInterval;
                }
                z.minimalInterval && c < z.minimalInterval && (c = z.minimalInterval), 5e3 === c ? (b = parseInt(b, 10) || 30, b = 1 > b || b > 30 ? 30 : b, z.countdown = b, z.tempInterval = c) : (z.countdown = 0, z.tempInterval = 0, z.mainInterval = c), c !== d && k();
            }
            return z.tempInterval ? z.tempInterval / 1e3 : z.mainInterval / 1e3;
        }
        function u(a, b, c) {
            return a ? c && this.isQueued(a) ? !1 : (z.queue[a] = b, !0) : !1;
        }
        function v(a) {
            return a ? z.queue.hasOwnProperty(a) : void 0;
        }
        function w(a) {
            a && delete z.queue[a];
        }
        function x(a) {
            return a ? this.isQueued(a) ? z.queue[a] : c : void 0;
        }
        var y = a(document), z = { suspend: !1, suspendEnabled: !0, screenId: "", url: "", lastTick: 0, queue: {}, mainInterval: 60, tempInterval: 0, originalInterval: 0, minimalInterval: 0, countdown: 0, connecting: !1, connectionError: !1, errorcount: 0, hasConnected: !1, hasFocus: !0, userActivity: 0, userActivityEvents: !1, checkFocusTimer: 0, beatTimer: 0 };
        return d(), { hasFocus: p, connectNow: r, disableSuspend: s, interval: t, hasConnectionError: q, enqueue: u, dequeue: w, isQueued: v, getQueuedItem: x };
    };
    b.wp = b.wp || {}, b.wp.heartbeat = new d;
}(jQuery, window);
!function (a) {
    function b() {
        var b, d = a("#wp-auth-check"), f = a("#wp-auth-check-form"), g = e.find(".wp-auth-fallback-expired"), h = !1;
        f.length && (a(window).on("beforeunload.wp-auth-check", function (a) {
            a.originalEvent.returnValue = window.authcheckL10n.beforeunload;
        }), b = a('<iframe id="wp-auth-check-frame" frameborder="0">').attr("title", g.text()), b.load(function () {
            var b, i;
            h = !0;
            try {
                i = a(this).contents().find("body"), b = i.height();
            }
            catch (j) {
                return e.addClass("fallback"), d.css("max-height", ""), f.remove(), void g.focus();
            }
            b ? i && i.hasClass("interim-login-success") ? c() : d.css("max-height", b + 40 + "px") : i && i.length || (e.addClass("fallback"), d.css("max-height", ""), f.remove(), g.focus());
        }).attr("src", f.data("src")), a("#wp-auth-check-form").append(b)), a("body").addClass("modal-open"), e.removeClass("hidden"), b ? (b.focus(), setTimeout(function () {
            h || (e.addClass("fallback"), f.remove(), g.focus());
        }, 1e4)) : g.focus();
    }
    function c() {
        a(window).off("beforeunload.wp-auth-check"), "undefined" == typeof adminpage || "post-php" !== adminpage && "post-new-php" !== adminpage || "undefined" == typeof wp || !wp.heartbeat || (a(document).off("heartbeat-tick.wp-auth-check"), wp.heartbeat.connectNow()), e.fadeOut(200, function () {
            e.addClass("hidden").css("display", ""), a("#wp-auth-check-frame").remove(), a("body").removeClass("modal-open");
        });
    }
    function d() {
        var a = parseInt(window.authcheckL10n.interval, 10) || 180;
        f = (new Date).getTime() + 1e3 * a;
    }
    var e, f;
    a(document).on("heartbeat-tick.wp-auth-check", function (a, f) {
        "wp-auth-check" in f && (d(), !f["wp-auth-check"] && e.hasClass("hidden") ? b() : f["wp-auth-check"] && !e.hasClass("hidden") && c());
    }).on("heartbeat-send.wp-auth-check", function (a, b) {
        (new Date).getTime() > f && (b["wp-auth-check"] = !0);
    }).ready(function () {
        d(), e = a("#wp-auth-check-wrap"), e.find(".wp-auth-check-close").on("click", function () {
            c();
        });
    });
}(jQuery);
var wpLink;
!function (a) {
    function b() {
        return c.dom.getParent(c.selection.getNode(), "a");
    }
    var c, d, e, f, g, h = {}, i = {}, j = "ontouchend" in document;
    wpLink = { timeToTriggerRiver: 150, minRiverAJAXDuration: 200, riverBottomThreshold: 5, keySensitivity: 100, lastSearch: "", textarea: "", init: function () {
        h.wrap = a("#wp-link-wrap"), h.dialog = a("#wp-link"), h.backdrop = a("#wp-link-backdrop"), h.submit = a("#wp-link-submit"), h.close = a("#wp-link-close"), h.text = a("#wp-link-text"), h.url = a("#wp-link-url"), h.nonce = a("#_ajax_linking_nonce"), h.openInNewTab = a("#wp-link-target"), h.search = a("#wp-link-search"), i.search = new e(a("#search-results")), i.recent = new e(a("#most-recent-results")), i.elements = h.dialog.find(".query-results"), h.queryNotice = a("#query-notice-message"), h.queryNoticeTextDefault = h.queryNotice.find(".query-notice-default"), h.queryNoticeTextHint = h.queryNotice.find(".query-notice-hint"), h.dialog.keydown(wpLink.keydown), h.dialog.keyup(wpLink.keyup), h.submit.click(function (a) {
            a.preventDefault(), wpLink.update();
        }), h.close.add(h.backdrop).add("#wp-link-cancel a").click(function (a) {
            a.preventDefault(), wpLink.close();
        }), a("#wp-link-search-toggle").on("click", wpLink.toggleInternalLinking), i.elements.on("river-select", wpLink.updateFields), h.search.on("focus.wplink", function () {
            h.queryNoticeTextDefault.hide(), h.queryNoticeTextHint.removeClass("screen-reader-text").show();
        }).on("blur.wplink", function () {
            h.queryNoticeTextDefault.show(), h.queryNoticeTextHint.addClass("screen-reader-text").hide();
        }), h.search.on("keyup input", function () {
            var a = this;
            window.clearTimeout(d), d = window.setTimeout(function () {
                wpLink.searchInternalLinks.call(a);
            }, 500);
        }), h.url.on("paste", function () {
            setTimeout(wpLink.correctURL, 0);
        }), h.url.on("blur", wpLink.correctURL);
    }, correctURL: function () {
        var b = a.trim(h.url.val());
        b && g !== b && !/^(?:[a-z]+:|#|\?|\.|\/)/.test(b) && (h.url.val("http://" + b), g = b);
    }, open: function (b) {
        var d, e = a(document.body);
        e.addClass("modal-open"), wpLink.range = null, b && (window.wpActiveEditor = b), window.wpActiveEditor && (this.textarea = a("#" + window.wpActiveEditor).get(0), "undefined" != typeof tinymce && (e.append(h.backdrop, h.wrap), d = tinymce.get(wpActiveEditor), c = d && !d.isHidden() ? d : null, c && tinymce.isIE && (c.windowManager.bookmark = c.selection.getBookmark())), !wpLink.isMCE() && document.selection && (this.textarea.focus(), this.range = document.selection.createRange()), h.wrap.show(), h.backdrop.show(), wpLink.refresh(), a(document).trigger("wplink-open", h.wrap));
    }, isMCE: function () {
        return c && !c.isHidden();
    }, refresh: function () {
        var a = "";
        i.search.refresh(), i.recent.refresh(), wpLink.isMCE() ? wpLink.mceRefresh() : (h.wrap.hasClass("has-text-field") || h.wrap.addClass("has-text-field"), document.selection ? a = document.selection.createRange().text || "" : "undefined" != typeof this.textarea.selectionStart && this.textarea.selectionStart !== this.textarea.selectionEnd && (a = this.textarea.value.substring(this.textarea.selectionStart, this.textarea.selectionEnd) || ""), h.text.val(a), wpLink.setDefaultValues()), j ? h.url.focus().blur() : h.url.focus()[0].select(), i.recent.ul.children().length || i.recent.ajax(), g = h.url.val().replace(/^http:\/\//, "");
    }, hasSelectedText: function (a) {
        var b = c.selection.getContent();
        if (/</.test(b) && (!/^<a [^>]+>[^<]+<\/a>$/.test(b) || -1 === b.indexOf("href=")))
            return !1;
        if (a) {
            var d, e = a.childNodes;
            if (0 === e.length)
                return !1;
            for (d = e.length - 1; d >= 0; d--)
                if (3 != e[d].nodeType)
                    return !1;
        }
        return !0;
    }, mceRefresh: function () {
        var a, b = c.selection.getNode(), d = c.dom.getParent(b, "a[href]"), e = this.hasSelectedText(d);
        d ? (a = d.innerText || d.textContent, h.url.val(c.dom.getAttrib(d, "href")), h.openInNewTab.prop("checked", "_blank" === c.dom.getAttrib(d, "target")), h.submit.val(wpLinkL10n.update)) : (a = c.selection.getContent({ format: "text" }), this.setDefaultValues()), e ? (h.text.val(a || ""), h.wrap.addClass("has-text-field")) : (h.text.val(""), h.wrap.removeClass("has-text-field"));
    }, close: function () {
        a(document.body).removeClass("modal-open"), wpLink.isMCE() ? c.focus() : (wpLink.textarea.focus(), wpLink.range && (wpLink.range.moveToBookmark(wpLink.range.getBookmark()), wpLink.range.select())), h.backdrop.hide(), h.wrap.hide(), g = !1, a(document).trigger("wplink-close", h.wrap);
    }, getAttrs: function () {
        return wpLink.correctURL(), { href: a.trim(h.url.val()), target: h.openInNewTab.prop("checked") ? "_blank" : "" };
    }, buildHtml: function (a) {
        var b = '<a href="' + a.href + '"';
        return a.target && (b += ' target="' + a.target + '"'), b + ">";
    }, update: function () {
        wpLink.isMCE() ? wpLink.mceUpdate() : wpLink.htmlUpdate();
    }, htmlUpdate: function () {
        var a, b, c, d, e, f, g, i = wpLink.textarea;
        i && (a = wpLink.getAttrs(), b = h.text.val(), a.href && (c = wpLink.buildHtml(a), document.selection && wpLink.range ? (i.focus(), wpLink.range.text = c + (b || wpLink.range.text) + "</a>", wpLink.range.moveToBookmark(wpLink.range.getBookmark()), wpLink.range.select(), wpLink.range = null) : "undefined" != typeof i.selectionStart && (d = i.selectionStart, e = i.selectionEnd, g = b || i.value.substring(d, e), c = c + g + "</a>", f = d + c.length, d !== e || g || (f -= 4), i.value = i.value.substring(0, d) + c + i.value.substring(e, i.value.length), i.selectionStart = i.selectionEnd = f), wpLink.close(), i.focus()));
    }, mceUpdate: function () {
        var a, d, e = wpLink.getAttrs();
        return wpLink.close(), c.focus(), tinymce.isIE && c.selection.moveToBookmark(c.windowManager.bookmark), e.href ? (a = b(), h.wrap.hasClass("has-text-field") && (d = h.text.val() || e.href), a ? (d && ("innerText" in a ? a.innerText = d : a.textContent = d), c.dom.setAttribs(a, e)) : d ? c.selection.setNode(c.dom.create("a", e, c.dom.encode(d))) : c.execCommand("mceInsertLink", !1, e), void c.nodeChanged()) : void c.execCommand("unlink");
    }, updateFields: function (a, b) {
        h.url.val(b.children(".item-permalink").val());
    }, setDefaultValues: function () {
        var a, b = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i, d = /^(https?|ftp):\/\/[A-Z0-9.-]+\.[A-Z]{2,4}[^ "]*$/i;
        this.isMCE() ? a = c.selection.getContent() : document.selection && wpLink.range ? a = wpLink.range.text : "undefined" != typeof this.textarea.selectionStart && (a = this.textarea.value.substring(this.textarea.selectionStart, this.textarea.selectionEnd)), a && b.test(a) ? h.url.val("mailto:" + a) : a && d.test(a) ? h.url.val(a.replace(/&amp;|&#0?38;/gi, "&")) : h.url.val(""), h.submit.val(wpLinkL10n.save);
    }, searchInternalLinks: function () {
        var b, c = a(this), d = c.val();
        if (d.length > 2) {
            if (i.recent.hide(), i.search.show(), wpLink.lastSearch == d)
                return;
            wpLink.lastSearch = d, b = c.parent().find(".spinner").addClass("is-active"), i.search.change(d), i.search.ajax(function () {
                b.removeClass("is-active");
            });
        }
        else
            i.search.hide(), i.recent.show();
    }, next: function () {
        i.search.next(), i.recent.next();
    }, prev: function () {
        i.search.prev(), i.recent.prev();
    }, keydown: function (a) {
        var b, c;
        27 === a.keyCode ? (wpLink.close(), a.stopImmediatePropagation()) : 9 === a.keyCode && (c = a.target.id, "wp-link-submit" !== c || a.shiftKey ? "wp-link-close" === c && a.shiftKey && (h.submit.focus(), a.preventDefault()) : (h.close.focus(), a.preventDefault())), (38 === a.keyCode || 40 === a.keyCode) && (!document.activeElement || "link-title-field" !== document.activeElement.id && "url-field" !== document.activeElement.id) && (b = 38 === a.keyCode ? "prev" : "next", clearInterval(wpLink.keyInterval), wpLink[b](), wpLink.keyInterval = setInterval(wpLink[b], wpLink.keySensitivity), a.preventDefault());
    }, keyup: function (a) {
        (38 === a.keyCode || 40 === a.keyCode) && (clearInterval(wpLink.keyInterval), a.preventDefault());
    }, delayedCallback: function (a, b) {
        var c, d, e, f;
        return b ? (setTimeout(function () {
            return d ? a.apply(f, e) : void (c = !0);
        }, b), function () {
            return c ? a.apply(this, arguments) : (e = arguments, f = this, void (d = !0));
        }) : a;
    }, toggleInternalLinking: function (a) {
        var b = h.wrap.hasClass("search-panel-visible");
        h.wrap.toggleClass("search-panel-visible", !b), setUserSetting("wplink", b ? "0" : "1"), h[b ? "url" : "search"].focus(), a.preventDefault();
    } }, e = function (b, c) {
        var d = this;
        this.element = b, this.ul = b.children("ul"), this.contentHeight = b.children("#link-selector-height"), this.waiting = b.find(".river-waiting"), this.change(c), this.refresh(), a("#wp-link .query-results, #wp-link #link-selector").scroll(function () {
            d.maybeLoad();
        }), b.on("click", "li", function (b) {
            d.select(a(this), b);
        });
    }, a.extend(e.prototype, { refresh: function () {
        this.deselect(), this.visible = this.element.is(":visible");
    }, show: function () {
        this.visible || (this.deselect(), this.element.show(), this.visible = !0);
    }, hide: function () {
        this.element.hide(), this.visible = !1;
    }, select: function (a, b) {
        var c, d, e, f;
        a.hasClass("unselectable") || a == this.selected || (this.deselect(), this.selected = a.addClass("selected"), c = a.outerHeight(), d = this.element.height(), e = a.position().top, f = this.element.scrollTop(), 0 > e ? this.element.scrollTop(f + e) : e + c > d && this.element.scrollTop(f + e - d + c), this.element.trigger("river-select", [a, b, this]));
    }, deselect: function () {
        this.selected && this.selected.removeClass("selected"), this.selected = !1;
    }, prev: function () {
        if (this.visible) {
            var a;
            this.selected && (a = this.selected.prev("li"), a.length && this.select(a));
        }
    }, next: function () {
        if (this.visible) {
            var b = this.selected ? this.selected.next("li") : a("li:not(.unselectable):first", this.element);
            b.length && this.select(b);
        }
    }, ajax: function (a) {
        var b = this, c = 1 == this.query.page ? 0 : wpLink.minRiverAJAXDuration, d = wpLink.delayedCallback(function (c, d) {
            b.process(c, d), a && a(c, d);
        }, c);
        this.query.ajax(d);
    }, change: function (a) {
        this.query && this._search == a || (this._search = a, this.query = new f(a), this.element.scrollTop(0));
    }, process: function (b, c) {
        var d = "", e = !0, f = "", g = 1 == c.page;
        b ? a.each(b, function () {
            f = e ? "alternate" : "", f += this.title ? "" : " no-title", d += f ? '<li class="' + f + '">' : "<li>", d += '<input type="hidden" class="item-permalink" value="' + this.permalink + '" />', d += '<span class="item-title">', d += this.title ? this.title : wpLinkL10n.noTitle, d += '</span><span class="item-info">' + this.info + "</span></li>", e = !e;
        }) : g && (d += '<li class="unselectable no-matches-found"><span class="item-title"><em>' + wpLinkL10n.noMatchesFound + "</em></span></li>"), this.ul[g ? "html" : "append"](d);
    }, maybeLoad: function () {
        var a = this, b = this.element, c = b.scrollTop() + b.height();
        !this.query.ready() || c < this.contentHeight.height() - wpLink.riverBottomThreshold || setTimeout(function () {
            var c = b.scrollTop(), d = c + b.height();
            !a.query.ready() || d < a.contentHeight.height() - wpLink.riverBottomThreshold || (a.waiting.addClass("is-active"), b.scrollTop(c + a.waiting.outerHeight()), a.ajax(function () {
                a.waiting.removeClass("is-active");
            }));
        }, wpLink.timeToTriggerRiver);
    } }), f = function (a) {
        this.page = 1, this.allLoaded = !1, this.querying = !1, this.search = a;
    }, a.extend(f.prototype, { ready: function () {
        return !(this.querying || this.allLoaded);
    }, ajax: function (b) {
        var c = this, d = { action: "wp-link-ajax", page: this.page, _ajax_linking_nonce: h.nonce.val() };
        this.search && (d.search = this.search), this.querying = !0, a.post(ajaxurl, d, function (a) {
            c.page++, c.querying = !1, c.allLoaded = !a, b(a, d);
        }, "json");
    } }), a(document).ready(wpLink.init);
}(jQuery);
