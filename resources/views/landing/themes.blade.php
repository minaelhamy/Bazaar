

<div class="row theme_image g-3">
    @foreach ($newpath as $path)
    <div class="col-6">
        <div class="theme-selection rounded border cursor-pointer"><img src='{{$path}}' alt="" class="w-100 rounded"></div>
    </div>
    @endforeach
</div>


