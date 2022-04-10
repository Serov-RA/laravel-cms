jsEdit = {
    initEvent: function() {
        jsEdit.initTrigger();
    },
    initTrigger: function () {
        $('#js-select').on('change', this.jsSelectEvent);

        $(".page_js_list").sortable({
            item: "> li.js-reorder",
            handle: ".fa-reorder",
            update: this.resortJs,
            connectWith: ".page_js_list"
        }).disableSelection();

        $('.page_js_list button').on('click', this.delJsEvent);
    },
    jsSelectEvent: function() {
        jsEdit.jsSelectTrigger(this);
    },
    jsSelectTrigger: function(obj) {
        var js_id = $(obj).val();
        var js_name = $(obj).find('option[value=' + js_id + ']').html();
        var js_pos = $('#page_js_list_' + jsDefaultPos + ' li').length + 1;

        var html = '<li class="list-group-item text-left" id="js_item_' + js_id + '">';
        html += '<i class="fa fa-reorder" style="cursor: move"></i> &nbsp; <span style="float: right">';
        html += '<button type="button" class="close" aria-label="Close" data-js="' + js_id +'">';
        html += '<span aria-hidden="true">&times;</span></button></span>';
        html += '<a href="/admin/site/js/edit/' + js_id +'" target="_blank">' + js_name + '</a>'
        html += '<div class="form-group field-' + jsEditForm + '-' + js_id + '-block_pos">';
        html += '<input type="hidden" id="' + jsEditForm + '-' + js_id + '-block_pos" class="form-control input_block_pos" name="' + jsEditField + '[' + js_id + '][block_pos]" value="' + js_pos + '">';
        html += '</div><div class="form-group field-' + jsEditForm + '-' + js_id + '-view_pos">';
        html += '<input type="hidden" id="' + jsEditForm + '-' + js_id + '-view_pos" class="form-control input_view_pos" name="' + jsEditField + '[' + js_id + '][view_pos]" value="' + jsDefaultPos + '"></div></li>';

        $('#page_js_list_' + jsDefaultPos).append(html);

        $('#emp_js_sel').prop('selected', true);
        $(obj).find('option[value=' + js_id + ']').prop('disabled', true);
        $('#js_item_' + js_id + ' button').on('click', this.delJsEvent);
    },
    delJsEvent: function() {
        jsEdit.delJsTrigger(this);
    },
    delJsTrigger: function(obj) {
        var js_id = $(obj).data('js');
        $('#js_item_' + js_id).remove();
        $('#js-select option[value=' + js_id + ']').prop('disabled', false);

        if ($('#select_js_list li').length) {
            this.resortJs();
        }
    },
    resortJs: function() {
        $(".page_js_list").each(function() {
            var pos = $(this).data('pos');

            if ($(this).find('li').length) {
                $(this).find('li input.input_block_pos').each(function(idx, el) {
                    var sort = idx + 1;
                    $(this).val(sort);
                });

                $(this).find('li input.input_view_pos').each(function(idx, el) {
                    $(this).val(pos);
                });
            }
        });
    }
}

$(document).ready(jsEdit.initEvent);
