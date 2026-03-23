@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-1 border-violet-200 hover:border-violet-800 p-1 border-violet-400 rounded-md shadow-sm focus:border-[#6A0DAD] focus:ring-[#6A0DAD] focus:outline-none']) }}>