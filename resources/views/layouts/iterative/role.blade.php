<tr>
    <td class="fs-5">{{$role->name}}</td>
    <td class="fs-5">{{$role->users_count}}</td>
    <td>
        <div class="col mx-0 d-flex">
            <span class="col fs-5">Add: </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-users"></i></label>
                <input type="checkbox" @checked($role->id == 1 || $role->id == 2)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-boxes-stacked"></i></label>
                <input type="checkbox" @checked($role->id == 1 || $role->id == 2)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-tags"></i></label>
                <input type="checkbox" @checked($role->id == 1 || $role->id ==2)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-cash-register"></i></label>
                <input type="checkbox" @checked($role->id == 1 || $role->id ==2)>
            </span>
        </div>

        <div class="col mx-0 d-flex">
            <span class="col fs-5">Edit:</span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-users"></i></label>
                <input type="checkbox" @checked($role->id == 1 || $role->id ==2)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-boxes-stacked"></i></label>
                <input type="checkbox" @checked($role->id == 1 || $role->id ==2)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-tags"></i></label>
                <input type="checkbox" @checked($role->id == 1)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-cash-register"></i></label>
                <input type="checkbox" @checked($role->id == 1)>
            </span>
        </div>

        <div class="col mx-0 d-flex">
            <span class="col fs-5">Delete:</span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-users"></i></label>
                <input type="checkbox" @checked($role->id == 1)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-boxes-stacked"></i></label>
                <input type="checkbox" @checked($role->id == 1)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-tags"></i></label>
                <input type="checkbox" @checked($role->id == 1)>
            </span>
            <span class="col fs-5">
                <label for=""><i class="fa-solid fa-cash-register"></i></label>
                <input type="checkbox">
            </span>
        </div>
    </td>

    <td class="text-center align-middle" style="">
        <a href="{{route('roles.show', ['role'=>$role])}}"
           class=" col-12 mx-0 btn btn-sm btn-outline-primary rounded-0 fs-6 my-1">
            <i class="fa-solid fa-eye"></i>
        </a>

        <a href="" class=" col-12 mx-0 no-underline">
            <form action="{{route('roles.destroy', ['role'=>$role])}}"
                  method="post">
                @csrf
                @method('delete')
                <button
                    class="btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12 fs-6 my-1">
                    <i class="fa-solid fa-trash"></i>
                </button>

            </form>
        </a>
    </td>
</tr>
