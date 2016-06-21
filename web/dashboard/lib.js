/**
 * Created by wangfugen on 6/20/16.
 */

var Config = {
    api_url: 'http://localhost/php/gen/web/api',
    login_url: 'login.html'
};

var Identity = {
    param: '__identity',
    get: function () {
        var identity = localStorage.getItem(this.param);
        return identity ? JSON.parse(identity) : null;
    },
    set: function (data) {
        localStorage.setItem(this.param, JSON.stringify(data));
    },
    clear: function () {
        localStorage.removeItem(this.param);
    },
    getUserId: function () {
        var identity = this.get();
        return identity ? identity['user_id'] : null;
    },
    getAccessToken: function () {
        var identity = this.get();
        return identity ? identity['access-token'] : null;
    },
    loginRequired: function () {
        if (this.get() === null) {
            window.location.href = Config.login_url;
        }
    }
};

var API = {
    call: function (options) {
        options.url = this.url(options.url);

        var token = Identity.getAccessToken();
        if (token !== null) {
            options.beforeSend = function (request) {
                request.setRequestHeader("Authorization", "Bearer " + token);
            };
        }
        return $.ajax(options);
    },
    url: function (url) {
        return Config.api_url + url;
    }
};

var Debug = {
    log: function (message) {
        console.log(message);
    },
    trace: function (message) {
        console.log(message);
    },
    error: function (message) {
        console.log(message);
    },
    warning: function (message) {
        console.log(message);
    }
};