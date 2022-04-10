pageEdit = {
    initEvent: function() {
        console.log('init');
        pageEdit.initTrigger();
    },
    initTrigger: function () {
        console.log('uiiii');
        $('#page-name').on('change', this.changeTitleEvent);
        $('#relation_field_pid').on('change', this.changePidEvent);
        $('#page-alias').on('blur', this.changeAliasEvent);
    },
    changeTitleEvent: function() {
        console.log('change');
        pageEdit.changeTitleTrigger(this);
    },
    changeTitleTrigger: function(obj) {
        var value = $(obj).val();

        if (value == '') {
            return;
        }

        if ($('#page-meta_title').val() == '') {
            $('#page-meta_title').val(value);
        }

        if ($('#page-alias').val() == '') {
            this.checkPageAlias();
        }
    },
    changePidEvent: function() {
        pageEdit.changePidTrigger(this);
    },
    changePidTrigger: function(obj) {
        if ($('#page-name').val() == '' && $('#page-alias').val() == '') {
            return;
        }

        this.checkPageAlias();
    },
    changeAliasEvent: function() {
        setTimeout(pageEdit.changeAliasTrigger(this), 300);
    },
    changeAliasTrigger: function(obj) {
        if ($(obj).val() == '') {
            return;
        }

        this.checkPageAlias();
    },
    checkPageAlias: function() {
        var data = {
            item_id: itemId,
            pid: $('#relation_field_pid').val(),
            title: $('#page-name').val(),
            alias: $('#page-alias').val()
        };

        $.ajax({
            url: '/admin/site/page/alias',
            method: 'get',
            data: data,
            dataType: 'JSON',
            success: function(msg) {
                if (msg.status == 'ok') {
                    if (msg.alias) {
                        $('#page-alias').val(msg.alias);
                    }

                    $('.field-page-alias .help-block').html('');
                    $('.field-page-alias').removeClass('has-error');
                } else {
                    $('.field-page-alias .help-block').html(msg.error);
                    $('.field-page-alias').addClass('has-error');
                }

                if (msg.parent) {
                    $('.field-page-alias .input-group-addon').html(msg.parent);
                }
            }
        });
    }
}

$(document).ready(pageEdit.initEvent);
