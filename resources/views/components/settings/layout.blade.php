<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[260px]">
        <div class="rounded-xl border bg-white/70 dark:bg-zinc-900/60 backdrop-blur shadow-[0_1px_8px_rgba(0,0,0,0.06)]"
            style="border-color:#bdb76b">
            <div class="p-3">
                @php($isProfile = request()->routeIs('profile.edit'))
                @php($isPassword = request()->routeIs('password.edit'))
                @php($isAppearance = request()->routeIs('appearance.edit'))

                <flux:navlist class="grid gap-2">
                    <flux:navlist.item :href="route('profile.edit')" wire:navigate
                        class="rounded-lg border transition hover:translate-x-[1px]"
                        :class="$isProfile ? 'bg-[#bdb76b] text-black border-[#bdb76b] shadow-sm' :
                            'border-[#bdb76b]/30 hover:bg-[#bdb76b]/10 text-zinc-800 dark:text-zinc-200'">
                        {{ __('Profile') }}
                    </flux:navlist.item>

                    <flux:navlist.item :href="route('password.edit')" wire:navigate
                        class="rounded-lg border transition hover:translate-x-[1px]"
                        :class="$isPassword ? 'bg-[#bdb76b] text-black border-[#bdb76b] shadow-sm' :
                            'border-[#bdb76b]/30 hover:bg-[#bdb76b]/10 text-zinc-800 dark:text-zinc-200'">
                        {{ __('Password') }}
                    </flux:navlist.item>

                    <flux:navlist.item :href="route('appearance.edit')" wire:navigate
                        class="rounded-lg border transition hover:translate-x-[1px]"
                        :class="$isAppearance ? 'bg-[#bdb76b] text-black border-[#bdb76b] shadow-sm' :
                            'border-[#bdb76b]/30 hover:bg-[#bdb76b]/10 text-zinc-800 dark:text-zinc-200'">
                        {{ __('Appearance') }}
                    </flux:navlist.item>
                </flux:navlist>
            </div>
        </div>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <div class="rounded-xl border bg-white/80 dark:bg-zinc-900/60 backdrop-blur p-6 shadow-[0_1px_12px_rgba(0,0,0,0.06)]"
            style="border-color:#bdb76b">
            <flux:heading class="!text-zinc-900 dark:!text-white">{{ $heading ?? '' }}</flux:heading>
            <flux:subheading class="!text-zinc-600 dark:!text-zinc-300">{{ $subheading ?? '' }}</flux:subheading>

            <div class="mt-5 w-full max-w-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
