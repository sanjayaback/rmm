@php
    $messages = [
        'success' => ['classes' => 'bg-green-500/15 border-green-500/30 text-green-300', 'icon' => '✓'],
        'error'   => ['classes' => 'bg-red-500/15 border-red-500/30 text-red-300',   'icon' => '✕'],
        'info'    => ['classes' => 'bg-blue-500/15 border-blue-500/30 text-blue-300',  'icon' => 'ℹ'],
        'warning' => ['classes' => 'bg-yellow-500/15 border-yellow-500/30 text-yellow-300', 'icon' => '⚠'],
    ];
@endphp

@if(session()->hasAny(['success','error','info','warning']) || $errors->any())
<div class="fixed top-20 right-4 z-[9999] space-y-2 max-w-sm w-full pointer-events-none" id="flash-container">

    @foreach($messages as $type => $cfg)
        @if(session($type))
        <div class="flash-msg pointer-events-auto flex items-start gap-3 {{ $cfg['classes'] }} border rounded-xl px-4 py-3 shadow-2xl"
             x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8">
            <span class="text-base leading-none mt-0.5 flex-shrink-0">{{ $cfg['icon'] }}</span>
            <p class="text-sm flex-1 leading-relaxed">{{ session($type) }}</p>
            <button @click="show = false" class="opacity-60 hover:opacity-100 text-lg leading-none flex-shrink-0">×</button>
        </div>
        @endif
    @endforeach

    @if($errors->any())
    <div class="flash-msg pointer-events-auto bg-red-500/15 border border-red-500/30 text-red-300 rounded-xl px-4 py-3 shadow-2xl"
         x-data="{ show: true }" x-show="show"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm font-semibold mb-1">Please fix the following:</p>
                <ul class="text-xs space-y-0.5 opacity-80">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button @click="show = false" class="opacity-60 hover:opacity-100 text-lg leading-none mt-0.5">×</button>
        </div>
    </div>
    @endif
</div>
@endif
