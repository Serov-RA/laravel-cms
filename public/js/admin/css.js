cssEdit = {
    initEvent: function() {
        cssEdit.initTrigger();
    },
    initTrigger: function () {
        $('#css-select').on('change', this.cssSelectEvent);

        $("#select_css_list").sortable({
            item: "> li",
            handle: ".fa-reorder",
            update: this.resortCss
        }).disableSelection();

        $('#select_css_list button').on('click', this.delCssEvent);
    },
    cssSelectEvent: function() {
        cssEdit.cssSelectTrigger(this);
    },
    cssSelectTrigger: function(obj) {
        var css_id = $(obj).val();
        var css_name = $(obj).find('option[value=' + css_id + ']').html();
        var css_pos = $('#select_css_list li').length + 1;

        var html = '<li class="list-group-item text-left" id="css_item_' + css_id + '">';
        html += '<i class="fa fa-reorder" style="cursor: move"></i> &nbsp; <span style="float: right">';
        html += '<button type="button" class="close" aria-label="Close" data-css="' + css_id +'">';
        html += '<span aria-hidden="true">&times;</span></button></span>';
        html += '<a href="/admin/site/css/edit/' + css_id +'" target="_blank">' + css_name + '</a>'
        html += '<div class="form-group field-' + cssEditForm + '-' + css_id + '-block_pos">';
        html += '<input type="hidden" id="' + cssEditForm + '-' + css_id + '-block_pos" class="form-control" name="' + cssEditField + '[' + css_id + '][block_pos]" value="' + css_pos + '"></div></li>';

        $('#select_css_list').append(html);

        $('#emp_css_sel').prop('selected', true);
        $(obj).find('option[value=' + css_id + ']').prop('disabled', true);
        $('#css_item_' + css_id + ' button').on('click', this.delCssEvent);
    },
    delCssEvent: function() {
        cssEdit.delCssTrigger(this);
    },
    delCssTrigger: function(obj) {
        var css_id = $(obj).data('css');
        $('#css_item_' + css_id).remove();
        $('#css-select option[value=' + css_id + ']').prop('disabled', false);

        if ($('#select_css_list li').length) {
            this.resortCss();
        }
    },
    resortCss: function() {
        $('#select_css_list input').each(function(idx, el) {
            var sort = idx + 1;
            $(this).val(sort);
        });
    }
}

$(document).ready(cssEdit.initEvent);
