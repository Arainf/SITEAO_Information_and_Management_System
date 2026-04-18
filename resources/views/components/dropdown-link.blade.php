<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2.5 text-start text-sm transition duration-150 ease-in-out no-underline']) }}
   style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;"
   onmouseover="this.style.background='#f8fafc'; this.style.color='#181d26';"
   onmouseout="this.style.background='transparent'; this.style.color='rgba(4,14,32,0.69)';">
    {{ $slot }}
</a>
