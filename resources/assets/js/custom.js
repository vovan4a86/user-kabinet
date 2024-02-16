function sendAjax(url, data, callback, type){
    data = data || {};
    if (typeof type == 'undefined') type = 'json';
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        // processData: false,
        // contentType: false,
        dataType: type,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        },
        success: function(json){
            if (typeof callback == 'function') {
                callback(json);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert('Не удалось выполнить ajax запрос! Ошибка на сервере.');
        },
    });
}

function setCity(elem, e) {
    e.preventDefault();
    const cityLabel = $('.cities-page__current');
    const homeLink = cityLabel.data('home');
    const cur_url = cityLabel.data('current');

    const url = '/ajax/set-city';

    const data = {
        city_id: elem.dataset.id,
    }
    if (cur_url === homeLink + '/') {
        sendAjax(url, data, function (json) {
            if (typeof json.success !== 'undefined') {
                location.reload(true);
            }
        })
    } else {
        sendAjax(url, data, function () {
            redirect_to_current_city(elem.dataset.id, cur_url);
        });
    }
}

function redirect_to_current_city(city_id, cur_url) {
    $.ajax({
        type: 'post',
        url: '/ajax/get-correct-region-link',
        data: {city_id, cur_url},
        dataType: 'json',
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        },
        success: function (json) {
            if (typeof json.redirect != 'undefined') {
                location.href = json.redirect;
                // console.log(json.redirect);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Не удалось выполнить запрос! Ошибка на сервере.');
        }
    });
}

function resetForm(form) {
    $(form).trigger('reset');
    $(form).find('.err-msg-block').remove();
    $(form).find('.has-error').remove();
    $(form).find('.invalid').attr('title', '').removeClass('invalid');
}

function sendCallback(frm, e) {
    e.preventDefault();
    var form = $(frm);
    var data = form.serialize();
    var url = form.attr('action');
    sendAjax(url, data, function (json) {
        if (typeof json.errors !== 'undefined') {
            let focused = false;
            for (var key in json.errors) {
                if (!focused) {
                    form.find('#' + key).focus();
                    focused = true;
                }
                form.find('#' + key).after('<span class="has-error">' + json.errors[key] + '</span>');
            }
            form.find('.popup__fields').after('<div class="err-msg-block has-error">Заполните, пожалуйста, обязательные поля.</div>');
        } else {
            resetForm(form);
            $('.carousel__button.is-close').click();
        }
    });
}

function sendFeedback(frm, e) {
    e.preventDefault();
    var form = $(frm);
    var data = form.serialize();
    var url = form.attr('action');
    sendAjax(url, data, function (json) {
        if (typeof json.errors !== 'undefined') {
            let focused = false;
            for (var key in json.errors) {
                if (!focused) {
                    form.find('#' + key).focus();
                    focused = true;
                }
                form.find('#' + key).after('<span class="has-error">' + json.errors[key] + '</span>');
            }
            form.find('.s-callback__submit').after('<div class="err-msg-block has-error">Заполните, пожалуйста, обязательные поля.</div>');
        } else {
            resetForm(form);
        }
    });
}

function sendCalc(frm, e) {
    e.preventDefault();
    var form = $(frm);
    var text = $('#calc .popup__label').text()
    var data = form.serialize() + '&text=' + text;

    var url = form.attr('action');
    sendAjax(url, data, function (json) {
        if (typeof json.errors !== 'undefined') {
            let focused = false;
            for (var key in json.errors) {
                if (!focused) {
                    form.find('#' + key).focus();
                    focused = true;
                }
                form.find('#' + key).after('<span class="has-error">' + json.errors[key] + '</span>');
            }
            form.find('.popup__fields').after('<div class="err-msg-block has-error">Заполните, пожалуйста, обязательные поля.</div>');
        } else {
            resetForm(form);
            $('.carousel__button.is-close').click();
        }
    });
}
