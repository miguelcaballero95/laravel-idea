@props([
    'label' => false,
    'type' => 'text',
    'value' => '',
    'name'
])
<div class="space-y-2">
    @if($label)
        <label for="{{$name}}" class="label">{{$label}}</label>
    @endif
    @if($type === 'textarea')
        <textarea
            name="{{$name}}"
            id="{{$name}}"
            class="textarea"
        >{{old($name, $value)}}</textarea>
    @else
        <input
            type="{{$type}}"
            class="input"
            id="{{$name}}"
            name="{{$name}}"
            value="{{old($name, $value)}}"
            {{$attributes}} >
    @endif
    <x-form.error name="{{$name}}"/>
</div>
