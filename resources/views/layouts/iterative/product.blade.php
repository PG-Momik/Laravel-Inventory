<tr>
    <td>{{$product->name}}</td>
    <td>
        <a href="{{route('categories.show', ['category'=>$product->category->id])}}">{{$product->category->name}}</a>
    </td>
    <td class="text-center">{{$product->quantity}}</td>
    <td class="d-flex text-center" style="column-gap: 0.8vw">
        <a href="{{route('products.show', ['product'=>$product])}}"
           class="col btn btn-sm btn-outline-primary rounded-0 px-2">
            <i class="fa-solid fa-eye"></i>
        </a>

        <a href="" class="col no-underline">
            <form action="{{route('products.destroy', ['product'=>$product])}}"
                  method="post">
                @csrf
                @method('delete')
                <button
                    class="btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12 ">
                    <i class="fa-solid fa-trash"></i>
                </button>

            </form>
        </a>
    </td>
</tr>
