@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-3.5 rounded-xl bg-cyan-neon/10 border border-cyan-neon/30 text-cyan-bright font-medium text-xs shadow-[0_0_10px_rgba(0,209,255,0.15)]']) }}>
        {{ $status }}
    </div>
@endif
