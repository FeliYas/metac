@props(['novedad'])

<div
    class="h-[488px] border border-gray-200 transition-transform duration-300 hover:shadow-2xl hover:-translate-y-1 transform group">
    <div>
        <img src="{{ $novedad->path }}" alt="{{ $novedad->titulo }}"
            class="object-cover w-full h-[260px] overflow-hidden ">
    </div>
    <div class="p-4 flex flex-col justify-between h-[228px]">
        <div>
            <p class="text-main-color font-bold uppercase">{{ $novedad->epigrafe }}</p>
            <h3 class="font-bold text-2xl text-black line-clamp-2">{{ $novedad->titulo }}</h3>
        </div>
        <div class="custom-summernote lg:text-left text-black line-clamp-2">
            <p>{!! $novedad->descripcion !!}</p>
        </div>
        <a href="{{ route('novedad', $novedad->id) }}" class="text-gray-400 font-medium transition-colors duration-300 group-hover:text-[#5C93CB]">
            Leer más
        </a>
    </div>
</div>
