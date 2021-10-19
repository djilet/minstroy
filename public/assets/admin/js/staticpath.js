$.fn.liTranslit = function (options) {
    // настройки по умолчанию
    var o = $.extend({
        elName: '#title',
        elAlias: '#slug',
        elId: '#id',
        elParent_id: null,
        lang: null,
        table: ''
    }, options);
    return this.each(function () {
        var elName = $(this).find(o.elName),
            elAlias = $(this).find(o.elAlias),
            nameVal;

        function tr(el) {
            nameVal = el.val();
            if (el.val() !== "") {
                validate(nameVal);
            }
        };
        elName.change(function () {
            tr($(this));
        });

        function validate(staticpath) {
            $.ajax({
                url: "/admin/create-slug",
                type: "POST",
                data: {
                    "title": staticpath,
                    "table": o.table,
                    "id": $(o.elId).val(),
                    "parent_id": $(o.elParent_id + ' option:selected').val(),
                    "lang": o.lang,
                },
                dataType: "JSON",
                success: function (data) {
                    if (data && data.slug) {
                        elAlias.val(data.slug);
                    }
                }
            });
        }
    });
};