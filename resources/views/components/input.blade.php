{{--ICON LIST--}}
@php
    $icons = [
        'name'              =>'<i class="fa-sharp fa-solid fa-quote-left"></i>',
        'email'             =>'<i class="fa-solid fa-envelope"></i>',
        'city'              =>'<i class="fa-solid fa-city"></i>',
        'state'             =>'<i class="fa-sharp fa-solid fa-location-dot"></i>',
        'date'              =>'<i class="fa-solid fa-calendar-days"></i>',
        'password'          =>'<i class="fa-solid fa-eye"></i>',
        'password-cross'    =>'<i class="fa-sharp fa-solid fa-eye-slash"></i>',
        'confirm-password'  =>'<i class="fa-solid fa-eye"></i>',
        'age'               =>'<i class="fa-solid fa-cake-candles"></i>',
        'select'            =>'<i class="fa-solid fa-square-chevron-down"></i>',
        'role_id'           =>'<i class="fa-solid fa-user-tag"></i>',
        'checkbox'          =>'<i class="fa-solid fa-question"></i>'
    ];
@endphp

<label for="{{$id??""}}" class="form-label">{{$label}}</label>
<div class="input-group">
    <span class="input-group-text">{!!  $icons[$name]!!}</span>
    @switch($type)
        @case('text')
        @case('number')
        @case('date')
        @case('email')
        @case('password')
            <input type="{{$type}}"
                   class="form-control @if($errors->has($name)) is-invalid @endif"
                   name="{{$name}}" value="{{$value??old($value)}}"
                   id="{{$id??''}}"
                   aria-describedby="{{$id}}" required>
            @break
        @case('checkbox')
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="verifyEmail" id="btnradio1" autocomplete="off"
                       @checked(!empty($checked))  value=true>

                <label class="btn btn-outline-primary" id="ratioBtnWrapper1" for="btnradio1">Verified</label>

                <input type="radio" class="btn-check" name="verifyEmail" id="btnradio2" autocomplete="off"
                       @checked(empty($checked))  value=false>
                <label class="btn btn-outline-danger" id="ratioBtnWrapper2" for="btnradio2">Not Verified</label>

            </div>
            @break;
        @case('select')
            <select name="{{$name}}" id="{{$id??""}}" class="form-control  @if($errors->has($name)) is-invalid @endif">
                @foreach($keyVal as $key => $val)
                    <option value="{{$val}}">{{$key}}</option>
                @endforeach
            </select>
            @break
    @endswitch
    <div class="invalid-feedback" id="{{$id??''}}">
        @error($name)
        {{$message}}
        @enderror
    </div>
</div>
