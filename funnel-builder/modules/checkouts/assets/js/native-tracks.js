/*global wfacp_analytics_data*/
(function ($) {
    class Events {
        constructor(data) {
            if (undefined == data) {
                return;
            }
            this.event_data = [];
            this.instance_timeout = null;
            this.timeout = 1500;
            this.track_name = '';
            this.track_id = data.id;
            this.settings = data.settings;
            this.positions = data.positions;
            this.all_data  = data;
            this.data = {
                'add_to_cart': data.add_to_cart, 'checkout': data.checkout, 'payment_info': {}, 'last_checkout_data': null
            };

            this.add_to_cart_run = false;
            this.checkout_event_run = false;
            this.is_bumpevent = false;
            this.init();
            this.attach_triggers();

        }

        init() {

        }

        static enqueue_js() {
        }

        fire_events(type) {
            if (this.positions.add_to_cart === type && false == this.add_to_cart_run) {
                /*
                 *hide add to cart on native checkout
                 * only run in case wffn_pending_event is true
                 * when add to cart run for custom ajax
                 */
                if (typeof this.all_data !== 'undefined' && this.all_data.hasOwnProperty('wffn_pending_event') && 'true' === this.all_data.wffn_pending_event) {
                    this.add_to_cart();
                }
                this.add_to_cart_run = true;
            }
            if (this.positions.checkout === type) {
                this.checkout();
            }
        }

        attach_triggers() {
            let self = this;
            let step_changed = false;
            let payment_trigger = false;
            $('#billing_email').on('change', function () {
                try {
                    self.fire_events('email');
                }catch (error) {
                    console.log( error );
                }

            });

            if (document.readyState === 'complete' || document.readyState === 'loading') {
                $(document).ready(function () {
                    try {
                        self.fire_events('load');
                        self.load();
                        self.single_event_trigger();
                    }catch (error) {
                        console.log( error );
                    }
                });
            } else {
                $(window).on('load', function () {
                    try {
                        self.fire_events('load');
                        self.load();
                        self.single_event_trigger();
                    }catch (error) {
                        console.log( error );
                    }
                });
            }

        }

        single_event_trigger() {
            let self = this;
            let step_button = $('.wfacp_next_page_button');
            let step_changed = false;
            let payment_trigger = false;
            if (step_button.length > 0) {
                $(document.body).on('wfacp_step_switching', function () {
                    try {
                        if (false === step_changed) {
                            self.fire_events('button');
                            step_changed = true;
                        }
                    }catch (error) {
                        console.log( error );
                    }
                });
            }

            $(document.body).on('angelleye_paypal_onclick', function () {
                try {
                    if (false === payment_trigger) {
                        self.payment_info();
                        payment_trigger = true;
                    }
                }catch (error) {
                    console.log( error );
                }
            });
            $(document.body).on('kp_auth_success', function () {
                try {
                    if (false === payment_trigger) {
                        self.payment_info();
                        payment_trigger = true;
                    }
                }catch (error) {
                    console.log( error );
                }
            });

            $(document.body).on('wfob_product_added', function (e, v) {
                try {
                    self.track_bump_item(v);
                }catch (error) {
                    console.log( error );
                }
            });
            $(document.body).on('wfob_product_removed', function (e, v) {
                try {
                    self.track_remove_bump_item(v);
                }catch (error) {
                    console.log( error );
                }
            });

            $(document.body).on('wfacp_product_added', function (e, v) {
                try {
                    self.add_item(v);
                }catch (error) {
                    console.log( error );
                }
            });

            $(document.body).on('wfacp_checkout_data', function (e, v) {
                if (v.hasOwnProperty('checkout') && v.hasOwnProperty('checkout')) {
                    if (v.checkout === undefined) {
                        return;
                    }
                    if (!v.checkout.hasOwnProperty(self.track_name)) {
                        return;
                    }
                    self.data.checkout = v.checkout[self.track_name];
                    step_changed = false;
                    self.checkout_event_run = false;
                }
            });
        }

        add_item(v, by_pass = false) {
            if (v.hasOwnProperty('item_key') && v.hasOwnProperty('item') && v.item.hasOwnProperty(v.item_key)) {
                let item_key = v.item_key;
                if (undefined == this.data.add_to_cart) {
                    return;
                }
                if (this.data.add_to_cart.hasOwnProperty(item_key)) {
                    return;
                }
                if (v.item[item_key].hasOwnProperty(this.track_name)) {
                    if (v.item.hasOwnProperty('is_bundle') && v.item[item_key][this.track_name].length !== 0) {
                        let b_items = v.item[item_key][this.track_name];
                        for (let b_item in b_items) {
                            this.data.add_to_cart[b_item] = b_items[b_item];
                            this.single_add_to_cart(b_items[b_item], by_pass);
                        }

                    } else {
                        this.data.add_to_cart[item_key] = v.item[item_key];
                        this.single_add_to_cart(v.item[item_key][this.track_name], by_pass);
                    }
                }

                this.checkout_event_run = false;
            }
        }

        remove_item(v) {
            if (v.hasOwnProperty('item_key') && v.hasOwnProperty('item') && v.item.hasOwnProperty(v.item_key)) {
                let item_key = v.item_key;
                if (this.data.add_to_cart.hasOwnProperty(item_key)) {
                    delete this.data.add_to_cart[item_key];
                }

                if (v.item[item_key].hasOwnProperty(this.track_name)) {
                    if (v.item.hasOwnProperty('is_bundle') && v.item[item_key][this.track_name].length !== 0) {
                        let b_items = v.item[item_key][this.track_name];
                        for (let b_item in b_items) {
                            this.remove_add_to_cart(b_items[b_item], v.item.cart_key);
                        }
                        ;
                    } else {
                        this.remove_add_to_cart(v.item[item_key][this.track_name], v.item.cart_key);
                    }
                }

                this.checkout_event_run = false;
            }
        }


        single_add_to_cart(data, by_pass = false) {
            if (this.settings.add_to_cart === 'true' || true == by_pass) {
                this.event_single_add_to_cart(data);
            }
        }


        remove_add_to_cart(data) {
            this.event_remove_add_to_cart(data);
        }


        event_single_add_to_cart(data) {

        }

        event_remove_add_to_cart(data) {

        }

        add_to_cart() {
            if (this.settings.add_to_cart === 'true') {
                this.event_add_to_cart();
            }
        }

        event_add_to_cart() {

        }

        checkout() {
            let current_data = JSON.stringify(this.data.checkout);
            if (this.settings.checkout === 'true' && (current_data !== this.data.last_checkout_data) && false == this.checkout_event_run) {
                this.event_checkout(current_data);
                this.data.last_checkout_data = current_data;
                this.checkout_event_run = true;
            }
        }

        event_checkout(current_data) {


        }

        payment_info() {
            if (this.settings.payment === 'true') {
                this.event_payment_info();
            }
        }

        event_payment_info() {
        }

        load() {

        }

        get_event_id() {
            let d = new Date();
            let time = d.getTime();
            return parseInt(time / 1000);
        }

        custom_event(event, data) {

        }

        // Bump Event

        track_bump_item(data) {
            let present = typeof wfob_frontend == 'object' && wfob_frontend.hasOwnProperty('track');
            if (present) {
                if ('1' == wfob_frontend.track[this.track_name].add_to_cart) {
                    this.is_bumpevent = true;
                    this.add_item(data, true);
                }
                if (wfob_frontend.track[this.track_name].hasOwnProperty('custom_bump') && '1' == wfob_frontend.track[this.track_name].custom_bump) {
                    let customData = {"post_id": wfacp_analytics_data.wfacp_frontend.id, "page_title": wfacp_analytics_data.wfacp_frontend.title};
                    this.custom_event('Woofunnels_Bump', customData);
                }
            }
        }

        track_remove_bump_item(data) {
            let present = typeof wfob_frontend == 'object' && wfob_frontend.hasOwnProperty('track');
            if (present) {
                if ('1' == wfob_frontend.track[this.track_name].add_to_cart) {
                    this.is_bumpevent = true;
                    this.remove_item(data);
                }
            }
        }

    }

    class Facebook extends Events {
        constructor(data,count) {
            super(data);
            this.pixel_event_data = {"AddToCart": {}, "InitiateCheckout": {}, "AddPaymentInfo": {}, "PageView": {}};
            this.track_name = 'pixel';
            this.eventCount = count;
        }

        static enqueue_js() {
            (function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    let pl = n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.defer = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            })(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        }


        send_ajax() {
            this.wfacp_send_ajax({
                'action': 'analytics', 'type': 'post', 'data': {
                    'event_data': this.event_data, 'source': window.location.href,
                }
            }, (rsp) => {
                this.event_data = [];
                this.timeout = 100;
            });
        }

        wfacp_send_ajax(data, cb) {

            let url = wfacp_analytics_data.wfacp_frontend.admin_ajax;
            if (data.hasOwnProperty('url')) {
                url = data.url;
            }
            if (this.eventCount !== 0) {
                return;
            }
            data.action = "wfacp_" + data.action;
            if (wfacp_analytics_data.wfacp_frontend.hasOwnProperty('wc_endpoints') && wfacp_analytics_data.wfacp_frontend.wc_endpoints.hasOwnProperty(data.action)) {
                url = wfacp_analytics_data.wfacp_frontend.wc_endpoints[data.action];
            }
            $(document.body).trigger(data.action, data);
            let send_data = {
                'action': data.action,
                'wfacp_nonce': wfacp_analytics_data.wfacp_frontend.wfacp_nonce,
                'data': data.data,
                'post_data': $('form.woocommerce-checkout').serialize()
            };

            let have_headers = false;
            if (data.hasOwnProperty('headers')) {
                have_headers = true;
            }

            let ajax_obj = {
                'url': url,
                'type': data.type,
                'data': send_data,
                success: (rsp) => {
                    if (true === have_headers) {
                        cb(rsp);
                        return;
                    }

                    if (typeof rsp !== "object") {
                        rsp = {'status': false};
                    }
                    if (typeof cb === 'function') {
                        rsp.action = data.action;
                        cb(rsp);

                    }
                },
                error: (rsp) => {
                    if (true == have_headers) {
                        cb(rsp);
                        return;
                    }
                    if (rsp.hasOwnProperty('status')) {
                        if (403 == rsp.status) {
                            console.log('Aero: Page is cached');
                        } else if (500 == rsp.status) {
                            console.log('Aero: Site Contain fatal error');
                        } else if (502 == rsp.status) {
                            console.log('Aero: Bad gateway');
                        } else {
                            console.log('Aero: Error', rsp.status);
                        }
                    }
                }
            };
            if (data.hasOwnProperty('headers')) {
                ajax_obj.headers = data.headers;
                ajax_obj.data = data.data;
            }
            $.ajax(ajax_obj);

        }

        send_data(event, data, event_id) {
            this.event_data.push({
                'event': event, 'data': data, 'event_id': event_id
            });
            clearTimeout(this.instance_timeout);
            this.instance_timeout = setTimeout(() => {
                this.send_ajax();
            }, this.timeout);
        }

        fbq(event, data, isCustom) {

            let event_id = this.get_event_id();
            if ('true' == wfacp_analytics_data.conversion_api) {
                this.send_data(event, data, event_id);
            }
            data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(data) : data;
            if (typeof isCustom === 'undefined') {
                fbq('trackSingle', this.track_id, event, data, {'eventID': event_id});
            } else {
                fbq('trackCustom', event, data, {'eventID': event_id});
            }

        }

        init() {
            if (wfacp_analytics_data.hasOwnProperty('fb_advanced') && wfacp_analytics_data.fb_advanced.length !== 0) {
                fbq('init', this.track_id, wfacp_analytics_data.fb_advanced);
            } else {
                fbq('init', this.track_id);
            }
        }

        event_add_to_cart() {
            let add_to_cart = this.data.add_to_cart;

            for (let item_key in add_to_cart) {
                this.event_single_add_to_cart(add_to_cart[item_key], item_key);
            }
        }

        event_single_add_to_cart(data, item_key) {

            if (typeof fbq === 'function') {
                this.fbq('AddToCart', data);
            }
        }

        event_remove_add_to_cart(data, item_key) {
            if (typeof fbq === 'function') {
                this.fbq('RemoveFromCart', data);
            }
        }

        event_checkout(current_data) {
            if (typeof fbq === 'function') {
                this.fbq('InitiateCheckout', JSON.parse(current_data));
            }
        }

        event_payment_info() {
            if (typeof fbq === 'function') {
                if (!this.data.hasOwnProperty("checkout") || typeof this.data.checkout == "undefined" || this.data.checkout == undefined) {
                    this.fbq('AddPaymentInfo', {});
                } else {
                    let parse_data = JSON.stringify(this.data.checkout);
                    parse_data = JSON.parse(parse_data);
                    delete parse_data.content_name;
                    delete parse_data.content_type;
                    delete parse_data.num_ids;
                    delete parse_data.num_items;
                    delete parse_data.plugin;
                    delete parse_data.subtotal;
                    delete parse_data.user_roles;
                    this.fbq('AddPaymentInfo', parse_data);
                }
            }
        }

        load() {
            if (typeof wffnTracking !== "undefined" && '1' == wffnTracking.pixel.settings.page_view) {
                return;
            }
            if (typeof fbq === 'function' && '' !== this.track_id && this.settings.page_view === 'true') {
                this.fbq('PageView', {});
            }
        }

        custom_event(event, data) {
            this.fbq(event, data, true);
        }
    }

    class Google extends Events {
        constructor(data) {
            super(data);
            this.track_name = 'google_ua';
            window.dataLayer = window.dataLayer || [];
            this.gtag('config', this.track_id);
        }

        static enqueue_js(analytics_id) {
            (function (window, document, src) {
                var a = document.createElement('script'), m = document.getElementsByTagName('script')[0];
                a.defer = 1;
                a.src = src;
                m.parentNode.insertBefore(a, m);
            })(window, document, '//www.googletagmanager.com/gtag/js?id=' + analytics_id);

            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function gtag() {
                dataLayer.push(arguments);
            };

            gtag('js', new Date());
        }

        load() {
            if (typeof wffnTracking !== "undefined" && '1' == wffnTracking.ga.settings.page_view) {
                return;
            }
            if (this.settings.page_view === 'true' && this.track_name === 'google_ads') {
                this.gtag('event', 'page_view', {send_to: this.track_id});
            }

        }

        gtag() {
            dataLayer.push(arguments);
        }

        event_checkout(checkout_data) {

            var event_data = [];
            if ('google_ua' === this.track_name) {
                checkout_data = JSON.parse(checkout_data);
                if (checkout_data.length > 0) {
                    event_data = checkout_data[0];
                    event_data.send_to = this.track_id;
                    event_data.event_category = "ecommerce";
                    event_data.non_interaction = true;
                }
            } else {
                event_data = {
                    send_to: this.track_id, event_category: "ecommerce", items: JSON.parse(checkout_data), non_interaction: true
                };
            }

            event_data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(event_data) : event_data;
            this.gtag('event', 'begin_checkout', event_data);
        }

        event_single_add_to_cart(data) {
            data.send_to = this.track_id;
            data.event_category = "ecommerce";
            data.non_interaction = true;
            var event_data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(data) : data;
            this.gtag('event', 'add_to_cart', event_data);
        }

        event_remove_add_to_cart(data, item_key) {
            data.send_to = this.track_id;
            data.event_category = "ecommerce";
            data.non_interaction = true;
            var event_data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(data) : data;

            this.gtag('event', 'remove_from_cart', event_data);
        }

        event_add_to_cart() {
            if (typeof this.data.add_to_cart == "undefined" || undefined == this.data.add_to_cart) {
                return;
            }
            let data = JSON.stringify(this.data.add_to_cart);
            data = JSON.parse(data);
            for (let item_key in data) {
                this.event_single_add_to_cart(data[item_key]);
            }
        }

        event_payment_info() {
            var event_data = {send_to: this.track_id, non_interaction: true};
            if ('google_ua' === this.track_name) {
                var checkout_data = this.data.checkout;
                if (checkout_data.length > 0) {
                    event_data = checkout_data[0];
                    event_data.send_to = this.track_id;
                    event_data.non_interaction = true;
                }
            }

            event_data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(event_data) : event_data;

            this.gtag('event', 'add_payment_info', event_data);
        }


        custom_event(event, data) {
            var event_data = {
                send_to: this.track_id, event_category: "ecommerce", items: [data], non_interaction: true
            };
            event_data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(event_data) : event_data;

            this.gtag('event', event, event_data);
        }

    }

    class Google_ads extends Google {
        constructor(data) {
            super(data);
            this.track_name = 'google_ads';
            this.idlabel = (typeof data.idlabel !== "undefined") ? data.idlabel : '';
            this.bumpIdlabel = (typeof data.bumpIdlabel !== "undefined") ? data.bumpIdlabel : '';
            window.dataLayer = window.dataLayer || [];
        }

        event_single_add_to_cart(data) {
            data = this.set_send_id( data );
            data.event_category = "ecommerce";
            data.non_interaction = true;
            var event_data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(data) : data;
            this.gtag('event', 'add_to_cart', event_data);
        }

        event_remove_add_to_cart(data, item_key) {
            data = this.set_send_id( data );
            data.event_category = "ecommerce";
            data.non_interaction = true;
            var event_data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(data) : data;

            this.gtag('event', 'remove_from_cart', event_data);
        }

        set_send_id( data ) {
            if ( this.is_bumpevent === true ) {
                data.send_to = (typeof this.bumpIdlabel !== "undefined") && '' !== this.bumpIdlabel ? this.bumpIdlabel : this.track_id;
            } else {
                data.send_to = (typeof this.idlabel !== "undefined") && '' !== this.idlabel ? this.idlabel : this.track_id;
            }
            return data;
        }
        event_checkout() {

        }

        event_payment_info() {
        }

    }

    class Pinterest extends Events {
        constructor(data) {
            super(data);
            this.track_name = 'pint';
        }

        static enqueue_js() {
            !function (e) {
                if (!window.pintrk) {
                    window.pintrk = function () {
                        window.pintrk.queue.push(Array.prototype.slice.call(arguments));
                    };
                    var n = window.pintrk;
                    n.queue = [], n.version = "3.0";
                    var t = document.createElement("script");
                    t.defer = !0, t.src = e;
                    var r = document.getElementsByTagName("script")[0];
                    r.parentNode.insertBefore(t, r);
                }
            }("https://s.pinimg.com/ct/core.js");
        }

        init(tracks_ids) {

            let ids = this.track_id.split(',');
            ids.forEach(function (pixelId) {
                pintrk('load', pixelId);
            });
        }

        load() {
            if (typeof wffnTracking !== "undefined" && '1' == wffnTracking.pint.settings.page_view) {
                return;
            }
            if (typeof pintrk === 'function' && '' !== this.track_id && this.settings.page_view === 'true') {
                pintrk('page');
            }
        }

        send_data(event, data, event_id) {
        }

        pint(event, data) {
            if (window.pintrk) {
                data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(data) : data;
                pintrk('track', event, data);
            }
        }

        event_checkout(checkout_data) {
            let c_data = JSON.parse(checkout_data);
            this.pint('InitiateCheckout', c_data);
        }

        event_single_add_to_cart(data) {
            this.pint('addtocart', data);
        }

        event_remove_add_to_cart(data, item_key) {
            this.pint('RemoveFromCart', data);
        }

        event_add_to_cart() {
            if (typeof this.data.add_to_cart == "undefined" || undefined == this.data.add_to_cart) {
                return;
            }
            let data = JSON.stringify(this.data.add_to_cart);
            data = JSON.parse(data);
            for (let item_key in data) {
                this.event_single_add_to_cart(data[item_key]);
            }
        }

        custom_event(event, data) {
            this.pint(event, data);
        }


    }

    class TikTok extends Events {
        constructor(data) {
            super(data);
            this.track_name = 'tiktok';
        }

        static enqueue_js() {
            !function (w, d, t) {
                w.TiktokAnalyticsObject = t;
                var ttq = w[t] = w[t] || [];
                ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"], ttq.setAndDefer = function (t, e) {
                    t[e] = function () {
                        t.push([e].concat(Array.prototype.slice.call(arguments, 0)));
                    };
                };
                for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
                ttq.instance = function (t) {
                    for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
                    return e;
                }, ttq.load = function (e, n) {
                    var i = "https://analytics.tiktok.com/i18n/pixel/events.js";
                    ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = i, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {};
                    var o = document.createElement("script");
                    o.type = "text/javascript", o.defer = !0, o.src = i + "?sdkid=" + e + "&lib=" + t;
                    var a = document.getElementsByTagName("script")[0];
                    a.parentNode.insertBefore(o, a)
                };
            }(window, document, 'ttq');
        }

        init() {
            if (typeof ttq !== "object") {
                return;
            }
            ttq.load(this.track_id);
            if (wfacp_analytics_data.hasOwnProperty('tiktok_advanced') && wfacp_analytics_data.tiktok_advanced.length !== 0) {
                ttq.instance(this.track_id).identify(wfacp_analytics_data.tiktok_advanced)
            }
        }

        load() {
            if (typeof wffnTracking !== "undefined" && '1' == wffnTracking.tiktok.settings.page_view) {
                return;
            }
            if ('' !== this.track_id && this.settings.page_view === 'true') {
                ttq.page();
            }
        }

        ttq(event, data) {
            if (typeof ttq !== "object") {
                return;
            }
            let self = this;
            setTimeout(function () {
                let event_id = self.get_event_id();
                ttq.instance(self.track_id).track(event, data);
            }, 1200);
        }

        event_payment_info() {
            if (typeof ttq === 'object') {
                this.ttq('AddPaymentInfo', this.data.checkout);
            }
        }

        event_checkout(checkout_data) {
            let c_data = JSON.parse(checkout_data);
            let c_self = this;
            setTimeout(function () {
                c_self.ttq('InitiateCheckout', c_data);
            }, 200);
        }

        event_single_add_to_cart(data) {
            this.ttq('AddToCart', data);
        }

        event_remove_add_to_cart(data, item_key) {
            // Not Supporting
            // this.ttq('Custom', {'event': 'remove_from_cart', 'data': data});
        }

        event_add_to_cart() {
            if (typeof this.data.add_to_cart == "undefined" || undefined == this.data.add_to_cart) {
                return;
            }
            let data = JSON.stringify(this.data.add_to_cart);
            data = JSON.parse(data);
            for (let item_key in data) {
                this.event_single_add_to_cart(data[item_key]);
            }
        }
    }

    class SnapChat extends Events {
        constructor(data) {
            super(data);
            this.track_name = 'snapchat';
        }

        static enqueue_js() {
            (function (win, doc, sdk_url) {
                if (win.snaptr) {
                    return;
                }

                var tr = win.snaptr = function () {
                    tr.handleRequest ? tr.handleRequest.apply(tr, arguments) : tr.queue.push(arguments);
                };
                tr.queue = [];
                var s = 'script';
                var new_script_section = doc.createElement(s);
                new_script_section.defer = !0;
                new_script_section.src = sdk_url;
                var insert_pos = doc.getElementsByTagName(s)[0];
                insert_pos.parentNode.insertBefore(new_script_section, insert_pos);
            })(window, document, 'https://sc-static.net/scevent.min.js');
        }

        init() {
            if (typeof snaptr !== "function") {
                return;
            }
            snaptr('init', this.track_id, {
                'integration': 'woocommerce',
                'user_email': this.settings.user_email
            });
        }

        load() {
            if (typeof wffnTracking !== "undefined" && '1' == wffnTracking.snapchat.settings.page_view) {
                return;
            }
            if ('' !== this.track_id && this.settings.page_view === 'true') {
                this.snaptr('PAGE_VIEW', {'content_id': wfacp_analytics_data.wfacp_frontend.id});
            }
        }

        snaptr(event, data) {
            if (typeof snaptr !== "function") {
                return;
            }

            data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent(data) : data;

            snaptr('track', event, data);
        }

        event_checkout(checkout_data) {
            let c_data = JSON.parse(checkout_data);
            this.snaptr('START_CHECKOUT', c_data);
        }

        event_payment_info() {
            // Not Supported
            //this.snaptr('ADD_PAYMENT_INFO', this.data.checkout);
        }

        event_single_add_to_cart(data) {
            this.snaptr('ADD_CART', data);
        }

        event_remove_add_to_cart(data, item_key) {
            // Not Supported
            // this.snaptr('REMOVE_FROM_CART', data);
        }

        event_add_to_cart() {
            if (typeof this.data.add_to_cart == "undefined" || undefined == this.data.add_to_cart) {
                return;
            }
            let data = JSON.stringify(this.data.add_to_cart);
            data = JSON.parse(data);
            for (let item_key in data) {
                this.event_single_add_to_cart(data[item_key]);
            }
        }

        custom_event(event, data) {
            //this.snaptr(event, data);
        }
    }

    class Loader {
        static init() {


            $(document).ready(function () {


                if ('1' === wfacp_analytics_data.wfacp_frontend.is_customizer || 'yes' == wfacp_analytics_data.wfacp_frontend.edit_mode) {
                    return;
                }

                if (1 != wfacp_analytics_data.shouldRender) {
                    return;
                }
                let bwf_gtag_load = false;

                wfacp_analytics_data.wfacp_frontend.tracks = {};
                if (wfacp_analytics_data.hasOwnProperty('pixel') && wfacp_analytics_data.pixel.hasOwnProperty('id')) {
                    Facebook.enqueue_js();
                    let ids = wfacp_analytics_data.pixel.id.split(',');
                    if (ids.length > 0) {
                        wfacp_analytics_data.wfacp_frontend.tracks.facebook = {};
                        for (let f = 0; f < ids.length; f++) {
                            let f_id = ids[f];
                            let temp = JSON.stringify(wfacp_analytics_data.pixel);
                            temp = JSON.parse(temp);
                            temp.id = f_id.trim();
                            wfacp_analytics_data.wfacp_frontend.tracks.facebook[f_id] = new Facebook(temp,f);
                        }
                    }
                }
                if (wfacp_analytics_data.hasOwnProperty('google_ua') && wfacp_analytics_data.google_ua.hasOwnProperty('id')) {
                    let ids = wfacp_analytics_data.google_ua.id.split(',');
                    if (ids.length > 0) {
                        if (!bwf_gtag_load) {
                            Google.enqueue_js(ids[0]);
                            bwf_gtag_load = true;
                        }
                        wfacp_analytics_data.wfacp_frontend.tracks.google_ua = {};
                        for (let f = 0; f < ids.length; f++) {
                            let f_id = ids[f];
                            let temp = JSON.stringify(wfacp_analytics_data.google_ua);
                            temp = JSON.parse(temp);
                            temp.id = f_id.trim();
                            wfacp_analytics_data.wfacp_frontend.tracks.google_ua[f_id] = new Google(temp);
                        }
                    }
                }
                if (wfacp_analytics_data.hasOwnProperty('google_ads') && wfacp_analytics_data.google_ads.hasOwnProperty('id')) {
                    let ids = wfacp_analytics_data.google_ads.id.split(',');
                    let gadLabels = [];

                    if ( typeof wfacp_analytics_data.google_ads.cart_labels === "string") {
                        gadLabels = wfacp_analytics_data.google_ads.cart_labels.split(',');
                    }
                    /**
                     * get bump cart labels
                     */
                    let bumpGadLabels = [];
                    if ((typeof wfob_frontend == 'object') && wfob_frontend.hasOwnProperty('track') && wfob_frontend.track.hasOwnProperty('google_ads')) {
                        if (typeof wfob_frontend.track.google_ads.cart_labels === "string") {
                            bumpGadLabels = wfob_frontend.track.google_ads.cart_labels.split(',');
                        }
                    }

                    if (ids.length > 0) {
                        if (!bwf_gtag_load) {
                            Google.enqueue_js(ids[0]);
                            bwf_gtag_load = true;
                        }
                        wfacp_analytics_data.wfacp_frontend.tracks.google_ads = {};
                        for (let f = 0; f < ids.length; f++) {
                            let f_id = ids[f];
                            let temp = JSON.stringify(wfacp_analytics_data.google_ads);
                            temp = JSON.parse(temp);
                            temp.id = f_id.trim();
                            /**
                             * set checkout add to cart labels
                             */
                            if ("undefined" !== typeof gadLabels[f] && gadLabels[f] !== "") {
                                temp.idlabel = temp.id + '/' + gadLabels[f].trim();
                            }
                            /**
                             * set bump add to cart labels
                             */
                            if ("undefined" !== typeof bumpGadLabels[f] && bumpGadLabels[f] !== "") {
                                temp.bumpIdlabel = temp.id + '/' + bumpGadLabels[f].trim();
                            }
                            wfacp_analytics_data.wfacp_frontend.tracks.google_ads[f_id] = new Google_ads(temp);
                        }
                    }
                }
                if (wfacp_analytics_data.hasOwnProperty('pint') && wfacp_analytics_data.pint.hasOwnProperty('id')) {
                    let ids = wfacp_analytics_data.pint.id.split(',');
                    if (ids.length > 0) {
                        Pinterest.enqueue_js();
                        wfacp_analytics_data.wfacp_frontend.tracks.pint = {};
                        for (let f = 0; f < ids.length; f++) {
                            let f_id = ids[f];
                            let temp = JSON.stringify(wfacp_analytics_data.pint);
                            temp = JSON.parse(temp);
                            temp.id = f_id.trim();
                            wfacp_analytics_data.wfacp_frontend.tracks.pint[f_id] = new Pinterest(temp);
                        }
                    }
                }
                if (wfacp_analytics_data.hasOwnProperty('tiktok') && wfacp_analytics_data.tiktok.hasOwnProperty('id')) {
                    let ids = wfacp_analytics_data.tiktok.id.split(',');
                    if (ids.length > 0) {
                        TikTok.enqueue_js();
                        wfacp_analytics_data.wfacp_frontend.tracks.tiktok = {};
                        for (let f = 0; f < ids.length; f++) {
                            let f_id = ids[f];
                            let temp = JSON.stringify(wfacp_analytics_data.tiktok);
                            temp = JSON.parse(temp);
                            temp.id = f_id.trim();
                            wfacp_analytics_data.wfacp_frontend.tracks.tiktok[f_id] = new TikTok(temp);
                        }
                    }
                }
                if (wfacp_analytics_data.hasOwnProperty('snapchat') && wfacp_analytics_data.snapchat.hasOwnProperty('id')) {
                    let ids = wfacp_analytics_data.snapchat.id.split(',');
                    if (ids.length > 0) {
                        SnapChat.enqueue_js();
                        wfacp_analytics_data.wfacp_frontend.tracks.snapchat = {};
                        for (let f = 0; f < ids.length; f++) {
                            let f_id = ids[f];
                            let temp = JSON.stringify(wfacp_analytics_data.snapchat);
                            temp = JSON.parse(temp);
                            temp.id = f_id.trim();
                            wfacp_analytics_data.wfacp_frontend.tracks.snapchat[f_id] = new SnapChat(temp);
                        }
                    }
                }

            });

        }
    }

    try {
        Loader.init();
    } catch (e) {
        console.log(e);
    }
})(jQuery);