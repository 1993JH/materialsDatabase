<x-layouts::auth :title="__('Log in')" container-class="max-w-6xl">
    <main class="mx-auto w-full max-w-3xl px-6 pb-16 pt-4">
        <section class="about-fade-up mx-auto w-full max-w-md overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/80 shadow-sm backdrop-blur">
            <div class="about-gradient-shift border-b border-zinc-200/70 bg-linear-to-r from-amber-100/70 via-cyan-100/70 to-teal-100/70 px-6 py-6 md:px-8">
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700">Welcome Back</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Log in to your account</h1>
                <p class="mt-2 text-sm text-zinc-700">Enter your email and password below to access Wall-E.</p>
            </div>

            <div class="about-fade-up about-delay-1 grid gap-6 p-6 md:p-8">
                <div class="mx-auto w-full max-w-xs rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm">
                    <x-auth-session-status class="mx-auto mb-4 w-full max-w-[13.5rem] text-center font-medium text-sm text-green-600" :status="session('status')" />

                    <form method="POST" action="{{ route('login.store') }}" class="mx-auto flex w-full max-w-[13.5rem] flex-col gap-6">
                        @csrf

                        <flux:input
                            name="email"
                            :label="__('Email address')"
                            :value="old('email')"
                            type="email"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="email@example.com"
                        />

                        <div class="relative">
                            <flux:input
                                name="password"
                                :label="__('Password')"
                                type="password"
                                required
                                autocomplete="current-password"
                                :placeholder="__('Password')"
                                viewable
                            />

                            @if (Route::has('password.request'))
                                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                                    {{ __('Forgot your password?') }}
                                </flux:link>
                            @endif
                        </div>

                        <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                        <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                            {{ __('Log in') }}
                        </flux:button>
                    </form>

                    @if (Route::has('register'))
                        <div class="mx-auto mt-5 w-full max-w-[13.5rem] space-x-1 text-center text-sm text-zinc-600 rtl:space-x-reverse">
                            <span>{{ __('Don\'t have an account?') }}</span>
                            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-layouts::auth>

