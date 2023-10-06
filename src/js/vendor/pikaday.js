import Pikaday from "pikaday";
import moment from "moment";

window.onload = function () {
    var isArray, renderTHTitle, picker;

    isArray = function (obj) {
        return (/Array/).test(Object.prototype.toString.call(obj));
    };

    renderTHTitle = function (instance, c, year, month, refYear, randId) {
        var i, j, arr,
            opts = instance._o,
            isMinYear = year === opts.minYear,
            isMaxYear = year === opts.maxYear,
            html = '<div id="' + randId + '" class="pika-title" role="heading" aria-live="assertive">',
            monthHtml,
            yearHtml,
            prev = true,
            next = true;

        for (arr = [], i = 0; i < 12; i++) {
            arr.push('<option value="' + (year === refYear ? i - c : 12 + i - c) + '"' +
                (i === month ? ' selected="selected"' : '') +
                ((isMinYear && i < opts.minMonth) || (isMaxYear && i > opts.maxMonth) ? ' disabled="disabled"' : '') + '>' +
                opts.i18n.months[i] + '</option>');
        }

        monthHtml = '<div class="pika-label">' + opts.monthSuffix + '&nbsp;' + opts.i18n.months[month] + '<select class="pika-select pika-select-month" tabindex="-1">' + arr.join('') + '</select></div>';

        if (isArray(opts.yearRange)) {
            i = opts.yearRange[0];
            j = opts.yearRange[1] + 1;
        } else {
            i = year - opts.yearRange;
            j = 1 + year + opts.yearRange;
        }

        for (arr = []; i < j && i <= opts.maxYear; i++) {
            if (i >= opts.minYear) {
                arr.push('<option value="' + i + '"' + (i === year ? ' selected="selected"' : '') + '>' + (i + 543) + '</option>');
            }
        }
        yearHtml = '<div class="pika-label">' + opts.yearSuffix + '&nbsp;' + (year + 543) + '<select class="pika-select pika-select-year" tabindex="-1">' + arr.join('') + '</select></div>';

        if (opts.showMonthAfterYear) {
            html += yearHtml + monthHtml;
        } else {
            html += monthHtml + yearHtml;
        }

        if (isMinYear && (month === 0 || opts.minMonth >= month)) {
            prev = false;
        }

        if (isMaxYear && (month === 11 || opts.maxMonth <= month)) {
            next = false;
        }

        if (c === 0) {
            html += '<button class="pika-prev' + (prev ? '' : ' is-disabled') + '" type="button">' + opts.i18n.previousMonth + '</button>';
        }
        if (c === (instance._o.numberOfMonths - 1)) {
            html += '<button class="pika-next' + (next ? '' : ' is-disabled') + '" type="button">' + opts.i18n.nextMonth + '</button>';
        }

        return html += '</div>';
    };

    picker = new Pikaday({
        field: document.getElementById('datepicker'),
        format: 'D/M/YYYY',
        yearSuffix: 'พ.ศ.',
        monthSuffix: 'เดือน',
        numberOfMonths: 1,
        i18n: {
            previousMonth: 'เดือนก่อนหน้า',
            nextMonth: 'เดือนถัดไป',
            months: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            weekdays: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
            weekdaysShort: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส']
        },
        onDraw: function (obj) {
            var html = '',
                randId;
            for (var c = 0; c < obj._o.numberOfMonths; c++) {
                randId = 'pika-title-' + Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 2);
                html += '<div class="pika-lendar">' + renderTHTitle(obj, c, obj.calendars[c].year, obj.calendars[c].month, obj.calendars[0].year, randId) + obj.render(obj.calendars[c].year, obj.calendars[c].month, randId) + '</div>';
            }
            obj.el.innerHTML = html;
        },
        parse: function (value, format) {
            var tempDate3 = moment(value, format);
            return tempDate3.subtract(543, 'years').toDate();
        },
        toString(date, format) {
            return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear() + 543}`;
        }
    });
};