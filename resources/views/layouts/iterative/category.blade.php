<tr class="align-middle">
    <td>{{$category->name}}</td>
    <td>{{$category->created_at->format('jS \of F, Y')}}</td>
    <td>{{$category->products_count}}</td>
    <td class="text-center d-flex row mx-0 align-middle" style="">
        <a href="{{route('categories.show', ['category'=>$category])}}"
           class="col-md-6 btn btn-sm btn-outline-primary rounded-0 fs-6 my-1">
            <i class="fa-solid fa-eye"></i>
        </a>

        <a href="" class="col-md-6 p-0 no-underline">
            <form action="{{route('categories.destroy', ['category'=>$category])}}"
                  method="post">
                @csrf
                @method('delete')
                <button
                    class="col-12 btn btn-sm bg-outline-yellow rounded-0 text-yellow fs-6 my-1 mx-2">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </a>
    </td>
</tr>
