<div class="col-md-8 col-12 my-1 align-items-center justify-content-between">
    <form action="{{$searchRoute}}"
          method="post"
          class="w-100 d-flex">
        @csrf
        @method('post')

        <input type="text" name="search-field"
               class="form-control px-3 w-75 border-0"
               placeholder="Search {{$placeholder}}"
               value="{{$searchKeyword??''}}"
               style="max-height: 50px; border-radius: 20px 0 0 20px">

        <div class="w-25 d-flex justify-content-center">
            <button type="submit"
                    class="btn btn-outline-dark w-50 rounded-0">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <button type="reset"
                    class="btn btn-outline-light w-50" style="border-radius: 0 20px 20px 0">
                <i class="fa-sharp fa-solid fa-rotate-left"></i>
            </button>
        </div>
    </form>
</div>
