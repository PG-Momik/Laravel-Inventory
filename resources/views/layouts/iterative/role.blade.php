<tr class="align-middle">
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

    <td class="text-center" style="column-gap: 0.5em">
        <a href="{{route('roles.show', ['role'=>$role])}}"
           class="w-100 rounded-0 py-1 btn btn-outline-primary my-1">
            <i class="fa-solid fa-eye"></i>
        </a>
        @can('trash roles')
            <form action="{{route('roles.destroy', ['role'=>$role])}}"
                  method="post" class="w-100 my-1">
                @csrf
                @method('delete')
                <button type="submit"
                        class="w-100 rounded-0 py-1 btn btn-outline-warning">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        @endcan
    </td>
</tr>
