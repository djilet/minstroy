$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
$(document).ready(function() {
    
    $('form.multiple-remove .btn-danger').click(function(e) {
        e.preventDefault();

        var form = $(this).closest('form');
        var list = form.data('list');
        var item = form.data('item');
        
        var checked = $(list).find('input[name="' + item + '"]:checked');
        if (checked.length === 0) {
            alert('Вы не выбрали ни одной категории');
            return;
        }
        
        ModalConfirm('Подтвердите удаление', 'Вы действительно хотите удалить выбранные категории?', function(){
            form.append(checked).submit();
        });
    });
    
    $('form.remove-confirmed button').click(function() {
        var title = $(this).data('title');
        var form = $(this).closest('form');
        ModalConfirm('Подтвердите удаление', 'Вы действительно хотите удалить "' + title + '"?', function(){
            form.submit();
        });
    });
    
});


function ModalConfirm(title, message, onconfirm)
{
    if(!$.isFunction(onconfirm))
        onconfirm = function(){};
    html = '<div id="confirm-dialog" class="modal">';
    html +='	<div class="modal-dialog">';
    html +='		<div class="modal-content">';
    html +='			<div class="modal-header">';
    html +='				<button type="button" class="close" aria-hidden="true">&times;</button>';
    html +='				<h4 class="modal-title">' + title + '</h4>';
    html +='			</div>';
    html +='			<div class="modal-body">'+message+'</div>';
    html +='			<div class="modal-footer">';
    html +='				<button id="confirm-no" type="button" class="btn btn-icon"><i class="fa fa-ban"></i> No </button>';
    html +='				<button id="confirm-yes" type="button" class="btn btn-warning btn-icon"><i class="fa fa-check"></i> Yes </button>';
    html +='			</div>';
    html +='		</div>';
    html +='	</div>';
    html +='</div>';

    $(html).modal('show');

    $('#confirm-yes').click(function(){
        onconfirm();
        $(this).closest('.modal').modal('hide');
        $(this).closest('.modal').remove();
    });

    //custom handler to close and completely remove dialog
    $('#confirm-no, #confirm-dialog .close, #confirm-dialog .modal-backdrop').click(function(){
        $('#confirm-dialog').modal('hide').remove();
    });
}

function createCKEditor(name, toolbarSet, width, height, params)
{
    var toolbars = {basic: [ [ 'Source'], [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-','RemoveFormat'], [ 'NumberedList','BulletedList']],
        standart: [['Source','-','Maximize','-','Templates' ], [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll'], [ 'Link','Unlink','Anchor' ], [ 'Image','Flash','Table','HorizontalRule','SpecialChar','Iframe' ], '/',
            ['Format', '-', 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ], [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
        ]
    };
    var cfg = {
        // contentsCss : [
        //     '<?php echo PROJECT_PATH; ?>website/<?php echo WEBSITE_FOLDER; ?>/fckconfig/fck_editorarea.css',
        //     '<?php echo PROJECT_PATH; ?>website/<?php echo WEBSITE_FOLDER; ?>/fckconfig/font-awesome.min.css',
        // ],
        width: width || '100%',
        height : height || '400px',
        toolbar: toolbars[toolbarSet] ? toolbars[toolbarSet] : toolbars['standart'],

        filebrowserImageBrowseUrl: '/filemanager?type=Images',
        filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/filemanager?type=Files',
        filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
    };
    if(typeof params === 'object') {
        for (var attrname in params) { cfg[attrname] = params[attrname]; }
    }
    var editor = CKEDITOR.replace(name, cfg);

    CKEDITOR.dtd.$removeEmpty['i'] = false;
}

function saveOpenTab(parent, name, link, replace)
{
    $(parent).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        localStorage.setItem(name, e.target.hash);
        
        if (link) {
            var href = $(link).attr('href').split('?');
            $(link).attr('href', href[0] + '?' + name + '=' + e.target.hash.replace(replace || '', ''));
        }
    });
    
    var tab = window.location.hash || localStorage.getItem(name);
    if (tab !== undefined && $(parent + ' .nav-tabs a[href="' + tab + '"]').length > 0) {
        $(parent + ' .nav-tabs a[href="' + tab + '"]').click();
        localStorage.setItem(name, tab);
    } else {
        $(parent + ' a[data-toggle="tab"]').trigger('shown.bs.tab');
    }
}


function GetMessenger()
{
    return Messenger({
        extraClasses: 'messenger-fixed messenger-on-right messenger-on-top',
        theme: 'flat'
    });
}

function CreateMessage(msg, type)
{
    return GetMessenger().post({
        message: msg,
        type: type,
        showCloseButton: true,
        hideAfter: 4
    });
}