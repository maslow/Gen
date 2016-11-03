/**
 * Created by wangfugen on 6/20/16.
 */

var Config = {
    api_url: '/public/api',
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
        var complete = options.complete;
        options.complete = function (xhr) {
            if (complete != undefined || complete != null) {
                complete(xhr);
            }
        };

        options.url = this.url(options.url);

        var token = Identity.getAccessToken();
        if (token !== null) {
            options.beforeSend = function (request) {
                request.setRequestHeader("Authorization", "Bearer " + token);
            };
        }
        return $.ajax(options);
    },
    error: function (xhr) {
        var message = 'Error';
        var title = xhr.statusText;
        //noinspection JSUnresolvedVariable
        var res = xhr.responseJSON;
        if (xhr.status == 403) {
            message = res.message;
        } else if (xhr.status == 401) {
            Identity.clear();
            Identity.loginRequired();
        } else if (xhr.status == 422) {
            if (res instanceof Array) {
                message = res[0].message;
            } else {
                message = res.message;
            }
        } else if (xhr.status == 406) {
            message = res.message;
        }
        return {title: title, message: message};
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