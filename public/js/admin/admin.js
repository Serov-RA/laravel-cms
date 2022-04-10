var Admin = {
    initEvent: function() {
        Admin.initTrigger();
    },
    initTrigger: function () {
        this.filterRelation();
        this.dateTimePicker();
        this.datePicker();
        this.htmlRedactor();
        this.codeRedactor();
        this.dropzone();
    },
    dropzone: function() {
        $('.dropzone_upload').each(function() {
            var options = $(this).data('options');

            if (options.successEval) {
                options.success = function (file) {
                    eval(options.successEval)
                }
            } else if (window.dropzoneSuccessEval) {
                options.success = dropzoneSuccessEval;
            }

            if (options.errorEval) {
                options.error = function (file) {
                    eval(options.errorEval)
                }
            } else if (window.dropzoneErrorEval) {
                options.error = dropzoneErrorEval;
            }


            $(this).dropzone(options);
        });
    },
    htmlRedactor: function() {
        $('textarea.html_redactor').each(function() {
            $(this).redactor($(this).data('options'));
        });
    },
    codeRedactor: function () {
        $('textarea.code_redactor').each(function() {
            CodeMirror.fromTextArea(document.getElementById($(this).attr('id')), {
                'lineNumbers': true,
                'matchBrackets': true,
                'continueComments': 'Enter',
                'extraKeys': {'Ctrl-/': 'toggleComment'}
            });
        });
    },
    dateTimePicker: function() {
        $('.picker_datetime').each(function () {
            $(this).datetimepicker({
                format: 'd.m.Y H:i:s'
            });
        })
    },
    datePicker: function () {
        $('.picker_date').each(function() {
            $(this).datetimepicker({
                timepicker: false,
                format: 'd.m.Y'
            });
        })
    },
    filterRelation: function() {
        $('.relation_field').each(function(idx, el){
            var table = $(this).data('table');
            var field = $(this).data('field');
            var input = $('#relation_field_' + field);
            var filter_field = this;

            $(this).autocomplete({
                source: indexUrl + '/autocomplete/' + itemId + '?table=' + table + '&field=' + field,
                minLength: 0,
                autoFocus: true,
                select: function(event, ui) {
                    input.val(ui.item.id);
                    input.trigger('change');
                },
                close: function(event, ui) {
                    if ($(input).val() === '') {
                        $(filter_field).val('');
                        $('#relation_field_addon' + field).removeClass('fa-link');
                        $('#relation_field_addon' + field).addClass('fa-unlink');
                    } else {
                        $('#relation_field_addon' + field).removeClass('fa-unlink');
                        $('#relation_field_addon' + field).addClass('fa-link');
                    }
                },
                change: function(event, ui) {
                    if ($(filter_field).val() === '') {
                        $(input).val('');
                        $('#relation_field_addon' + field).removeClass('fa-link');
                        $('#relation_field_addon' + field).addClass('fa-unlink');
                    } else {
                        $('#relation_field_addon' + field).removeClass('fa-unlink');
                        $('#relation_field_addon' + field).addClass('fa-link');
                    }
                }
            });
        });
    },
    selectMenu: function(url) {
        $('#sidebar-menu').find('a[href="' + url + '"]').parent('li').addClass('current-page');

        $('#sidebar-menu').find('a').filter(function () {
            return $(this).attr('href') == url;
        }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
            Admin.setContentHeight();
        }).parent().addClass('active');
    },
    setContentHeight: function() {
        $('.right_col').css('min-height', $(window).height());

        var bodyHeight = $('body').outerHeight(),
            footerHeight = $('body').hasClass('footer_fixed') ? -10 : $('footer').height(),
            leftColHeight = $('.left_col').eq(1).height() + $('.sidebar-footer').height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        contentHeight -= $('.nav_menu').height() + footerHeight;
        $('.right_col').css('min-height', contentHeight);
    }
}

$(document).ready(Admin.initEvent);
