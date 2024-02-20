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
        type: 'POST',
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

export const sendFiles = (url, data, callback, type) => {
    if (typeof type == 'undefined') type = 'json';
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        cache: false,
        dataType: type,
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        },
        success: function(json, textStatus, jqXHR)
        {
            if (typeof callback == 'function') {
                callback(json);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            alert('Не удалось выполнить запрос! Ошибка на сервере.');
        }
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

export const loadImage = () => {
    $('#userImage').change(function () {
        const url = '/ajax/user/upload-image';
        const id = $(this).data('id');
        const image = $(this).prop('files')[0];
        let data = new FormData();
        data.append('id', id);
        data.append('image', image);
        sendFiles(url, data, function (json) {
            if (json.success && json.image) {
                $('.user-image').replaceWith(json.image);
            }
            if (!json.success) {
                const error = '<div style="color:red;">' + json.error + '</div>'
                $('.custom-file').after(error);
            }
        });
    });
}
loadImage();

//сохранить информацию о пользователе
export const saveUserInfo = () => {
    $('form.user-info').submit(function (e) {
        e.preventDefault();
        const url = $(this).attr('action');
        let data = $(this).serialize();

        sendAjax(url, data, function (json) {
            if (json.success && json.header_user) {
                $('.header-user').replaceWith(json.header_user);
            }
            if (!json.success) {
                const error = '<div style="color:red;">' + json.error + '</div>'
                $('.user-info .btn-success').after(error);
            }
        });
    });
}
saveUserInfo();

//оставить отзыв
export const sendOpinion = () => {
    $('#send-opinion').submit(function (e) {
        e.preventDefault();
        const url = $(this).attr('action');
        let data = $(this).serialize();

        sendAjax(url, data, function (json) {
            if (json.success && json.redirect) {
                location.href = json.redirect;
            }
            if (!json.success) {
                const error = '<div style="color:red;">' + json.error + '</div>';
                $('#send-opinion').find('.btn-success').after(error);
            }
        });
    });
}
sendOpinion();
