<div id="menu-create" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="menu-edit-form" action="{{ route('admin.menu.store') }}" method="post" class="ajax" autocomplete="off">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Создать меню</h4>
                </div>
                <div class="alert alert-error" style="display:none;"></div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="title" class="required">Заголовок</label>
                        <input name="title" id="title" class="form-control" type="text" value="">
                    </div>
                    <div class="form-group">
                        <label for="slug" class="required">MENU_</label>
                        <input name="slug" id="slug" class="form-control" type="text" value="">
                    </div>
                </div>

                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn btn-icon" data-dismiss="modal"><i class="fa fa-ban"></i>Отмена</button>
                    <button type="submit" class="btn btn-success btn-icon"><i class="fa fa-save"></i>Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
