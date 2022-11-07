<tr>
    <td>{{$user->name}}</td>
    <td>{{$user->email}}</td>
    <td>{{$user->role->name}}</td>
    <td class="text-center">
        <a href="{{route('users.transactions', ['id'=>$user->id])}}">
            {{$user->transactions_count}}
        </a>
    </td>
    <td class="d-flex text-center" style="column-gap: 0.8vw">
        <a href="{{route('users.show', ['user'=>$user])}}"
           class="col btn btn-sm btn-outline-primary rounded-0 px-2">
            <i class="fa-solid fa-eye"></i>
        </a>

        <a href="" class="col no-underline">
            <form action="{{route('users.destroy', ['user'=>$user->id])}}"
                  method="post">
                @csrf
                @method('delete')
                <button
                    class="btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12 px-4">
                    <i class="fa-solid fa-trash"></i>
                </button>

            </form>
        </a>
    </td>
</tr>
