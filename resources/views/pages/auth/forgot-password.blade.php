<x-layouts::auth :title="__('Forgot password')" container-class="max-w-6xl">
    <main class="mx-auto w-full max-w-3xl px-6 pb-16 pt-4">
        <section class="about-fade-up mx-auto w-full max-w-md overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/80 shadow-sm backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/70">
            <div class="about-gradient-shift border-b border-zinc-200/70 bg-linear-to-r from-amber-100/70 via-cyan-100/70 to-teal-100/70 px-6 py-6 dark:border-zinc-800 dark:from-amber-900/30 dark:via-cyan-900/20 dark:to-teal-900/30 md:px-8">
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-300">Password Reset</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Forgot password</h1>
                <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">Enter your email to receive a password reset link.</p>
            </div>

            <div class="about-fade-up about-delay-1 grid gap-6 p-6 md:p-8">
                <div class="mx-auto w-full max-w-xs rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <x-auth-session-status class="mx-auto mb-4 w-full max-w-[13.5rem] text-center font-medium text-sm text-green-600" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="mx-auto flex w-full max-w-[13.5rem] flex-col gap-6">
                        @csrf

                        <flux:input
                            name="email"
                            :label="__('Email address')"
                            type="email"
                            required
                            autofocus
                            placeholder="email@example.com"
                        />

                        <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                            {{ __('Email password reset link') }}
                        </flux:button>
                    </form>

                    <div class="mx-auto mt-5 w-full max-w-[13.5rem] space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
                        <span>{{ __('Or, return to') }}</span>
                        <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts::auth>
