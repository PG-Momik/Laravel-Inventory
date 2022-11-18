<tr>
    <td>
        {{$product->name}}
    </td>
    <td>
        <a href="{{route('categories.show', ['category'=>$product->category->id])}}">{{$product->category->name}}</a>
    </td>
    <td class="text-center">
        {{$product->quantity}}
    </td>
    <td class="d-flex text-center" style="column-gap: 0.5em">
        <a href="{{route('products.show', ['product'=>$product])}}"
           class="w-50 rounded-0 py-1 btn btn-outline-primary">
            <i class="fa-solid fa-eye"></i>
        </a>
        <form action="{{route('products.destroy', ['product'=>$product])}}"
              method="post" class="w-50">
            @csrf
            @method('delete')
            <button type="submit"
                    class="w-100 rounded-0 py-1 btn btn-outline-warning">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
