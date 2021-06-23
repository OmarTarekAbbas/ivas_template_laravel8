@if (isset($_REQUEST['category_id']))
    <div class="form-group">
        <label for="textfield5" class="col-sm-3 col-lg-2 control-label">@lang('messages.Category.Category')<span
                class="text-danger">*</span></label>
        <div class="col-sm-9 col-lg-10 controls">
            <select name="category_id" class="form-control chosen-rtl">
                <option id="category_{{ $_REQUEST['category_id'] }}" value="{{ $_REQUEST['category_id'] }}">
                    {{ $_REQUEST['title'] }}</option>
            </select>
        </div>
    </div>
@else
    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label">@lang('messages.Category.Category')<span
                class="text-danger">*</span></label>
        <div class="col-sm-9 col-lg-10 controls">
            {!! Form::select('category_id', $categorys->pluck('title', 'id'), null, ['class' => 'form-control chosen-rtl', 'required']) !!}
        </div>
    </div>
@endif
