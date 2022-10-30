<div class="col-4">
    <label
        for="id{{$product->id}}">{{$product->category->name}}</label>
    <input type="checkbox"
           name="category"
           id="id{{$product->id}}"
           class="form-check-input categoryCheckbox"
           value="{{$product->id}}"
           autocomplete="off">
</div>
