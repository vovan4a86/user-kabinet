// import 'focus-visible';
// import './plugins';
// import './modules';
// import { utils } from './modules/utility';
// import { scrollTop } from './modules/scrollTop';
// import { maskedInputs } from './modules/inputMask';
//
// utils();
//
// scrollTop({ trigger: '.scrolltop' });
//
// maskedInputs({
//   phoneSelector: 'input[name="phone"]',
//   emailSelector: 'input[name="email"]'
// });

import 'bootstrap';
import $ from 'jquery';

export const sendAjax = (url, data, callback, type) => {
    data = data || {};
    if (typeof type == 'undefined') type = 'json';
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        // processData: false,
        // contentType: false,
        dataType: type,
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        },
        success: function (json) {
            if (typeof callback == 'function') {
                callback(json);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Не удалось выполнить запрос! Ошибка на сервере.');
        },
    });
}

export const login = () => {
    $('#login-form').submit(function (e) {
        e.preventDefault();
        const form = $(this);
        const url = $(form).attr('action');
        const data = form.serialize();

        sendAjax(url, data, function (json) {
            if (json.success) {
                location.href = json.redirect;
            } else {
                $('.error').text(json.errors);
            }
        });

    })
}
// login();

export const registration = () => {
    $('#user-reg').submit(function (e) {
        e.preventDefault();
        const form = $(this);
        const url = $(form).attr('action');
        let data = form.serialize();

        sendAjax(url, data, function (json) {
            if (json.success) {
                location.href = json.redirect;
            }
        });
    })
}
// registration();


export const logout = () => {
    $('#form-logout').submit(function (e) {
        e.preventDefault();
        const url = $(this).attr('action');
        sendAjax(url);
    });
}
// logout();
